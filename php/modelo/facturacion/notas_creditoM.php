<?php 
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");
require_once(dirname(__DIR__,3)."/lib/fpdf/reporte_de.php");
require_once(dirname(__DIR__,3)."/lib/phpmailer/enviar_emails.php");
	
/**
 * 
 */
class notas_creditoM
{
	private $db;
	private $pdf;
	function __construct()
	{		
      $this->db = new db();
      $this->pdf = new cabecera_pdf(); 
	}



	function cargar_tabla($parametro,$tabla = false)
	{
		$sql = "SELECT *
        FROM Asiento_NC
        WHERE Item = '".$_SESSION['INGRESO']['item']."'
        AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
        ORDER BY A_No ";
        if($tabla)
        {
	        $medida = medida_pantalla($_SESSION['INGRESO']['Height_pantalla'])-320;
	        $botones[0] = array('boton'=>'eliminar', 'icono'=>'<i class="fa fa-trash"></i>', 'tipo'=>'danger', 'id'=>'CODIGO,A_No' );
			$tbl = grilla_generica_new($sql,'Transacciones As T,Comprobantes As C,Clientes As Cl','tbl_lib',false,$botones,$check=false,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,$medida);
		}else
		{
			$tbl = $this->db->datos($sql);
		}
       return $tbl;
	}

	function Listar_Facturas_Pendientes_NC($codigo=false,$serie=false)
	{ 
		$sql = "SELECT C.Grupo, C.Codigo, C.Cliente, F.Cta_CxP, SUM(F.Total_MN) As TotFact 
       	FROM Clientes As C, Facturas As F 
       	WHERE F.Item = '".$_SESSION['INGRESO']['item']."' 
       	AND F.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND F.Serie = '".$serie."'
       	AND NOT F.TC IN ('DO','OP') 
       	AND F.T <> 'A' 
       	AND F.Saldo_MN <> 0 
       	AND C.Codigo = F.CodigoC";
       	if($codigo)
       	{
       		$sql.=" AND Cliente like '%".$codigo."%' ";
       	} 
       	$sql.=" GROUP BY C.Grupo, C.Codigo, C.Cliente, F.Cta_CxP 
       	ORDER BY C.Cliente ";

       	// print_r($sql);die();
       	return $this->db->datos($sql);
   }

   function DClineas($MBoxFecha,$Cta_CxP=false)
   {
   	   $sql = "SELECT Codigo, Concepto, CxC,Serie,Autorizacion  
       FROM Catalogo_Lineas 
       WHERE Fact = 'NC' 
       AND Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       AND Fecha <= '".BuscarFecha($MBoxFecha)."' 
       AND Vencimiento >= '".BuscarFecha($MBoxFecha)."' ";
       if($Cta_CxP==true && strlen($Cta_CxP) > 2 ){ $sql.=" AND '".$Cta_CxP."' IN (CxC,CxC_Anterior) ";}
	  	$sql.=" ORDER BY CxC, Concepto ";
	  	// print_r($sql);die();
	  	return $this->db->datos($sql);
   }

   function delete_asiento_nc()
   {
   	  $sql = "DELETE
	  FROM Asiento_NC 
	  WHERE Item = '".$_SESSION['INGRESO']['item']."' 
	  AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' ";
	  return $this->db->String_Sql($sql);
   }

   function catalogo_bodega(){
       $sql = "SELECT * 
       FROM Catalogo_Bodegas 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       ORDER BY CodBod, Bodega ";
       return $this->db->datos($sql);
	}

	function catalogo_marca()
	{   
	  $sql = "SELECT * 
	  FROM Catalogo_Marcas 
	  WHERE Item = '".$_SESSION['INGRESO']['item']."' 
	  AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
	  ORDER BY Marca ";
	  return $this->db->datos($sql);
	}


	function Catalogo_Cuentas($query)
	{  
	  $sql = "SELECT Codigo,Codigo+SPACE(10)+Cuenta As NomCuenta 
	    FROM Catalogo_Cuentas 
	    WHERE SUBSTRING(Codigo,1,1) IN ('1','2','4','5','6') 
	    AND DG = 'D' 
	    AND Item = '".$_SESSION['INGRESO']['item']."' 
	    AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
	    if($query)
	    {
	    	$sql.=" AND Codigo+' '+Cuenta like '%".$query."%'";
	    }
	    $sql.="  ORDER BY Codigo ";
	    // print_r($sql);die();
	    return $this->db->datos($sql);
	}

	function Catalogo_Productos($query)
	{  
	  $sql = "SELECT Producto, Codigo_Inv, PVP, IVA, Cta_Inventario 
	    FROM Catalogo_Productos 
	    WHERE TC = 'P' 
	    AND Item = '".$_SESSION['INGRESO']['item']."' 
	    AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
	    if($query)
	    {
	    	$sql.=" AND Producto like '%".$query."%'";
	    } 
	    $sql.=" ORDER BY Producto ";
	    return $this->db->datos($sql);
	}

	function DCTC($CodigoC){
  	// 'MsgBox sSQL
	  $sql = "SELECT TC 
	       FROM Facturas 
	       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
	       AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
	       AND CodigoC = '".$CodigoC."'
	       AND T = '".G_PENDIENTE."' 
	       AND TC <> 'OP' 
	       GROUP BY TC 
	       ORDER BY TC ";
	    return $this->db->datos($sql);
	  // SelectDB_Combo DCTC, AdoTC, sSQL, "TC"
	  // If AdoTC.Recordset.RecordCount <= 0 Then MsgBox "Este Cliente no ha empezado a generar facturas"
	}
  	
  	function DCSerie($TC,$CodigoC)
  	{
	   $sql = "SELECT Serie 
	      FROM Facturas 
	      WHERE Item = '".$_SESSION['INGRESO']['item']."' 
	      AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
	      AND CodigoC = '".$CodigoC."' 
	      AND TC = '".$TC."' 
	      AND T = '".G_PENDIENTE."' 
	      GROUP BY Serie 
	      ORDER BY Serie ";
	      return $this->db->datos($sql);
  	}

  	function DCFactura($Serie,$TC,$CodigoC,$FA=false)
  	{
	   $sql = "SELECT Factura 
	     FROM Facturas 
	     WHERE Item = '".$_SESSION['INGRESO']['item']."' 
	     AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
	     AND CodigoC = '".$CodigoC."' 
	     AND TC = '".$TC."' 
	     AND Serie = '".$Serie."' 
	     AND T = '".G_PENDIENTE."'";
	     if($FA)
	     {
	     	$sql.="AND Factura = '".$FA."' ";
	     } 
	     $sql.=" AND Saldo_MN > 0 
	     GROUP BY Factura 
	     ORDER BY Factura ";
	     // print_r($sql);die();
	     return $this->db->datos($sql);

	}

	function DCFacturaAll($Serie,$TC,$CodigoC,$FA)
  	{
	   $sql = "SELECT *
	     FROM Facturas 
	     WHERE Item = '".$_SESSION['INGRESO']['item']."' 
	     AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
	     AND CodigoC = '".$CodigoC."' 
	     AND TC = '".$TC."' 
	     AND Serie = '".$Serie."' 
	     AND Factura = '".$FA."' 
	     AND T = '".G_PENDIENTE."' 
	     AND Saldo_MN > 0 
	     ORDER BY Factura ";
	     return $this->db->datos($sql);

	}

	function Factura_detalle($Factura,$Serie,$TC)
	{
	  $sql = "SELECT T,Fecha,Cta_CxP,Cod_CxC,Porc_IVA,Total_MN,Saldo_MN,IVA,Autorizacion,Descuento 
	     FROM Facturas 
	     WHERE Item = '".$_SESSION['INGRESO']['item']."' 
	     AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
	     AND TC = '".$TC."' 
	     AND Serie = '".$Serie."' 
	     AND Factura = ".$Factura." 
	     AND T <> '".G_ANULADO."' 
	     AND Saldo_MN > 0 
	     ORDER BY Autorizacion ";
	     // print_r($sql);die();
	     return $this->db->datos($sql);
	}

	function lineas_factura($Factura,$Serie,$TC,$Autorizacion)
	{
		$sql = "SELECT Codigo,Cantidad,Precio,Producto,Total,Total_Desc,Total_Desc2,Total_IVA,CodBodega,CodMarca,Cod_Ejec,Porc_C,Porc_IVA,Mes_No,Mes,Ticket 
          	FROM Detalle_Factura 
          	WHERE Item = '".$_SESSION['INGRESO']['item']."' 
          	AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
          	AND TC = '".$TC."' 
          	AND Serie = '".$Serie."' 
          	AND Factura = ".$Factura." 
          	AND Autorizacion = '".$Autorizacion."' 
          	ORDER BY ID ";
        return $this->db->datos($sql);
	}

	function delete_Detalle_Nota_Credito($serieNC,$Nota_Credito)
	{
	  $sql = "DELETE 
	         FROM Detalle_Nota_Credito 
	         WHERE Item ='".$_SESSION['INGRESO']['item']."' 
	         AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
	         AND Serie = '".$serieNC."' 
	         AND Secuencial = ".$Nota_Credito; 
	  	return $this->db->String_Sql($sql);
	}

	function Actualizar_facturas_trans_abonos($TxtConcepto,$FA)
	{
	    $sql = "UPDATE Facturas
        	SET Nota = '".$TxtConcepto."'
        	WHERE Item = '".$_SESSION['INGRESO']['item']."' 
        	AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
        	AND Factura = ".$FA['Factura']."
        	AND TC = '".$FA['TC']."'
        	AND Serie = '".$FA['Serie']."'
            AND Autorizacion = '".$FA['Autorizacion']."';";
	            
        $sql2 = "UPDATE Trans_Abonos
            SET Serie_NC = '".$FA['Serie_NC']."',
            Autorizacion_NC = '".$FA['Autorizacion_NC']."',
            Secuencial_NC = '".$FA['Nota_Credito']."',
            Clave_Acceso_NC = '".G_NINGUNO."',
            Estado_SRI_NC = 'CG'
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND Factura = ".$FA['Factura']."
            AND TP = '".$FA['TC']."'
            AND Serie = '".$FA['Serie']."'
            AND Autorizacion = '".$FA['Autorizacion']."'; ";
	  	return $this->db->String_Sql($sql.$sql2);
	}


	function delete_asientonNC($codigo=False,$A_no=false)
	{
		$sql = "DELETE 
        FROM Asiento_NC
        WHERE Item =  '".$_SESSION['INGRESO']['item']."' 
        AND CodigoU =  '".$_SESSION['INGRESO']['CodigoU']."'";
        if($codigo)
        {
        	$sql.=" AND CODIGO = '".$codigo."'";
        }
        if($A_no)
        {
         $sql.=" AND A_No = '".$A_no."'";
     	}
	  	return $this->db->String_Sql($sql);
	}

	function pdf_nota_credito($TFA)
	{
		$imp = 1;
		if(isset($TFA['imprimir'])){$imp = $TFA['imprimir'];}
		$sql = "SELECT *
		    FROM Trans_Abonos
		    WHERE Item = '".$_SESSION['INGRESO']['item']."'
		    AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
		    AND TP = '".$TFA['TC']."'
		    AND Serie = '".$TFA['Serie']."'
		    AND Autorizacion = '".$TFA['Autorizacion']."'
		    AND Factura = ".$TFA['Factura']."
		    AND Banco = 'NOTA DE CREDITO'
		    ORDER BY Cheque DESC ";

		  $AdoDBDet = $this->db->datos($sql);
		  if(count($AdoDBDet))
		  {
		  	$TFA['Fecha_NC'] = $AdoDBDet[0]["Fecha"];
		    $TFA['Serie_NC'] = $AdoDBDet[0]["Serie_NC"];
		    $TFA['ClaveAcceso_NC'] = $AdoDBDet[0]["Clave_Acceso_NC"];
		    $TFA['Autorizacion_NC'] = $AdoDBDet[0]["Autorizacion_NC"];
		    $TFA['Nota_Credito'] = $AdoDBDet[0]["Secuencial_NC"];
			foreach ($AdoDBDet as $key => $value) {
				if($value['Cheque']=='I.V.A')
				{
					$TFA['Total_IVA_NC'] = $TFA['Total_IVA_NC']+ $value["Abono"];
				}else
				{
					$TFA['SubTotal_NC']= $TFA['SubTotal_NC']+ $value["Abono"];
				}
			}
		     
		  }

		$sql = "SELECT Autorizacion, Codigo_Inv, Producto, Cantidad, Precio, Total, Total_IVA, Descuento, Cta_Devolucion, CodBodega, Porc_IVA, Mes, Mes_No , Anio, ID
		    FROM Detalle_Nota_Credito
		    WHERE Item = '".$_SESSION['INGRESO']['item']."'
		    AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
		    AND Serie = '".$TFA['Serie_NC']."'
		    AND Secuencial = ".$TFA['Nota_Credito']."
		    ORDER BY ID ";
		$AdoDBDetFA = $this->db->datos($sql);


		$tipo_con = Tipo_Contribuyente_SP_MYSQL($_SESSION['INGRESO']['RUC']);
		  if(count($AdoDBDetFA)>0 && count($tipo_con)>0)
		  {
		    $TFA['Tipo_contribuyente'] = $tipo_con;
		  }
		  $sucursal = $this->catalogo_lineas_('NC',$TFA['Serie_NC']);
		$datos_cli_edu=$this->Cliente($TFA['CodigoC']);
	    
	    //$archivos = array('0'=>$nombre_archivo.'.pdf','1'=>$TFA['Autorizacion_GR'].'.xml');
	    $to_correo = '';
	    if(count($datos_cli_edu)>0)
	    {
	      if($datos_cli_edu[0]['Email']!='.' && $datos_cli_edu[0]['Email']!='')
	      {
	        $to_correo.= $datos_cli_edu[0]['Email'].',';
	      }
	      if($datos_cli_edu[0]['Email2']!='.' && $datos_cli_edu[0]['Email2']!='')
	      {
	        $to_correo.= $datos_cli_edu[0]['Email2'].',';
	      }
	      if($datos_cli_edu[0]['EmailR']!='.' && $datos_cli_edu[0]['EmailR']!='')
	      {
	        $to_correo.= $datos_cli_edu[0]['EmailR'].',';
	      }
	      // $to_correo = substr($to_correo, 0,-1);
	    }

	    // print_r($TFA);	    
	    // print_r($AdoDBDetFA);
	    // print_r($AdoDBDet);
	    // print_r($datos_cli_edu);
	    // die();
    

		imprimirDocEle_NC($TFA,$AdoDBDetFA,$datos_cli_edu,$matri=false,$nombre='ddddd',$formato=null,$nombre_archivo=null,$va=null,$imp,$AdoDBDet=false,$sucursal=array());
	}

  function Cliente($cod,$grupo = false,$query=false,$clave=false)
   {
     $sql = "SELECT * from Clientes WHERE T='N' ";
     if($cod){
      $sql.=" and Codigo= '".$cod."'";
     }
     if($grupo)
     {
      $sql.=" and Grupo= '".$grupo."'";
     }
     if($query)
     {
      $sql.=" and Cliente +' '+ CI_RUC like '%".$query."%'";
     }
     if($clave)
     {
      $sql.=" and Clave= '".$clave."'";
     }

     $sql.=" ORDER BY ID OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY;";

     $result = $this->db->datos($sql);

       // $result =  encode($result);
        // print_r($result);
        return $result;
   }


  function catalogo_lineas_($TC,$SerieFactura)
  {
    $sql = "SELECT *
         FROM Catalogo_Lineas
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
         AND Fact = '".$TC."'
         AND Serie = '".$SerieFactura."'
         AND Autorizacion = '".$_SESSION['INGRESO']['RUC']."'
         AND TL <> 0
         ORDER BY Codigo ";
         // print_r($sql);die();
    return $this->db->datos($sql);

  }


}
?>