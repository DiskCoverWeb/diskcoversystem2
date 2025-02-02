<?php
include(dirname(__DIR__,2).'/funciones/funciones.php');
include(dirname(__DIR__,3).'/lib/fpdf/reporte_de.php');
require_once(dirname(__DIR__, 3) . "/lib/TCPDF/Reportes/reportes_all.php");
@session_start(); 
/**
 * 
 */
class lista_liquidacionCompraM
{
	private $conn;	
	private $db;
  	private $reportes;
	function __construct()
	{
		$this->conn = cone_ajax();
		$this->db = new db();
    	$this->reportes = new reportes_all();
	}
 
   function facturas_emitidas_excel($codigo,$reporte_Excel=false,$periodo=false)
   {
   	$cid = $this->conn;
		
		$sql ="SELECT T,TC,Serie,Autorizacion,Factura,Fecha,SubTotal,Con_IVA,IVA,Descuento+Descuento2 as Descuentos,Total_MN as Total,Saldo_MN as Saldo,RUC_CI,TB,Razon_Social  FROM Facturas 
       WHERE CodigoC ='".$codigo."'
      AND Item = '".$_SESSION['INGRESO']['item']."' ";
       if($periodo && $periodo!='.')
       {
       	 $sql.=" AND Periodo BEETWEN '01/01/".$periodo."' AND '31/12".$periodo."'";
       }else
       {
       	$sql.=" AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' ";
       }

       $sql.="ORDER BY Fecha DESC"; 

      // print_r($sql);die();

       $stmt = sqlsrv_query($cid, $sql);
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }

	   $result = array();	
	   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		$result[] = $row;
		//echo $row[0];
	   }
	   if($reporte_Excel==false)
	   {
	   	  return $result;
	   }else
	   {
	   	 $stmt1 = sqlsrv_query($cid, $sql);
	     exportar_excel_generico($stmt1,'Facturasemitidas',null,null);

	   }

   }

   function facturas_perido($codigo)
   {
   	    $sql="SELECT Periodo
			FROM  Facturas
			WHERE CodigoC = '".$codigo."'
			GROUP BY Periodo
			ORDER BY Periodo";
			return $this->db->datos($sql);

   }

    function facturas_emitidas_tabla($codigo,$periodo=false,$desde=false,$hasta=false,$serie=false,$autorizados=false)
   {
   	$cid = $this->conn;
		
		$sql ="SELECT F.T,TC,Serie,Autorizacion,Factura,F.Fecha,SubTotal,Con_IVA,IVA,F.Descuento+Descuento2 as Descuentos,Total_MN as Total,Saldo_MN as Saldo,RUC_CI,F.TB,Razon_Social,CodigoC,F.ID,C.Email,C.Email2,C.EmailR 
		FROM Facturas F
		Left join Clientes C on F.CodigoC = C.Codigo
		WHERE TC='LC' 
		AND Item = '".$_SESSION['INGRESO']['item']."' ";
		if($codigo!='T')
		{
			// si el codigo es T se refiere a todos
		   $sql.=" AND F.CodigoC ='".$codigo."'";
		} 
        if($desde!='' && $hasta!='')
        {
       		 $sql.= " AND F.Fecha BETWEEN '".$desde."' AND '".$hasta."'";
        }
        if($serie)
		{
			// si el codigo es T se refiere a todos
		   $sql.=" AND Serie ='".$serie."'";
		}
		if($autorizados)
		{
			// print_r($autorizados);die();
			if($autorizados==1)
			{
				$sql.=" AND Len(Autorizacion)>13";
			}else
			{
				$sql.=" AND Len(Autorizacion)=13";
			}
		} 

       $sql.="ORDER BY ID DESC "; 
	$sql.=" OFFSET 0 ROWS FETCH NEXT 100 ROWS ONLY;";   

	// print_r($sql);die();    
	return $this->db->datos($sql);

       // return $datos;
   }

   function pdf_factura($cod,$ser,$ci,$periodo=false)
   {
   	$id='factura_'.$ci;
   	$cid = $this->conn;
   	$sql="SELECT * 
   	FROM Facturas 
   	WHERE Serie='".$ser."' 
   	AND Factura='".$cod."' 
   	AND CodigoC='".$ci."' 
   	AND Item = '".$_SESSION['INGRESO']['item']."' ";
   	if($periodo==false || $periodo =='.')
   	{
	   $sql.=" AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' ";
	}else
	{
      	$sql.=" AND Periodo BETWEEN '01/01/".$periodo."' AND '31/12".$periodo."'";
	}

	// print_r($sql);die();
	$datos_fac = $this->db->datos($sql);
    $datos_fac[0] = $TFA = Leer_Datos_FA_NV($datos_fac[0]);
   	$sSQL = "SELECT DF.*, CP.Reg_Sanitario, CP.Marca, CP.Desc_Item, CP.Codigo_Barra As Cod_Barras
         FROM Detalle_Factura DF, Catalogo_Productos CP 
         WHERE DF.Item = '" . $_SESSION['INGRESO']['item'] . "' 
         AND DF.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
         AND DF.Factura = " . $TFA['Factura'] . "  
         AND DF.Item = CP.Item 
         AND DF.TC = 'LC'
         AND DF.Periodo = CP.Periodo 
         AND DF.Codigo = CP.Codigo_Inv 
         ORDER BY DF.ID, DF.Codigo";

         // print_r($sSQL);die();
    $detalle_fac = $this->db->datos($sSQL);
    // print_r($detalle_fac);die();

	$tipo_con = Tipo_Contribuyente_SP_MYSQL($_SESSION['INGRESO']['RUC']);

	$sucursal = $this->catalogo_lineas('FA',$ser);
	$forma_pago = $this->DCTipoPago($datos_fac[0]['Tipo_Pago']);
	if(count($forma_pago)>0)
	{
		$datos_fac[0]['Tipo_Pago'] = $forma_pago[0]['CTipoPago'];
	}

	if(count($sucursal)==0)
	{
		$sucursal = array();
	}
	if(count($datos_fac)>0 && count($tipo_con)>0)
	{
		$datos_fac['Tipo_contribuyente'] = $tipo_con;
	}
	$abonos = array();

    $datos_cli_edu=$this->cliente_matri($ci);
	   if($datos_cli_edu != '' && !empty($datos_cli_edu))
	   {
	   		$this->reportes->imprimirDocEle_fac($datos_fac,$detalle_fac,$datos_cli_edu,'matr',$id,null,'factura',null,null,false,$abonos,$sucursal);
	   }else
	   {
		    $datos_cli_edu=$this->Cliente($ci);
		    $this->reportes->imprimirDocEle_fac($datos_fac,$detalle_fac,$datos_cli_edu,false,null,'factura',null,null,false,$abonos,$sucursal);
	   }

   }

   function pdf_factura_descarga($cod,$ser,$ci,$periodo=false)
   {
   	$id='factura_'.$ci;
   	$cid = $this->conn;
   	$sql="SELECT * 
   	FROM Facturas 
   	WHERE Serie='".$ser."' 
   	AND Factura='".$cod."' 
   	AND CodigoC='".$ci."' 
   	AND Item = '".$_SESSION['INGRESO']['item']."' ";
   	if($periodo==false || $periodo =='.')
   	{
	   $sql.=" AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' ";
	}else
	{
      	$sql.=" AND Periodo BETWEEN '01/01/".$periodo."' AND '31/12".$periodo."'";
	}

	// print_r($sql);die();
	$datos_fac = $this->db->datos($sql);
    $datos_fac[0] = $TFA = Leer_Datos_FA_NV($datos_fac[0]);

    $sSQL = "SELECT DF.*, CP.Reg_Sanitario, CP.Marca, CP.Desc_Item, CP.Codigo_Barra As Cod_Barras
         FROM Detalle_Factura DF, Catalogo_Productos CP 
         WHERE DF.Item = '" . $_SESSION['INGRESO']['item'] . "' 
         AND DF.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
         AND DF.Factura = " . $TFA['Factura'] . "  
         AND DF.Item = CP.Item 
         AND DF.Periodo = CP.Periodo 
         AND DF.Codigo = CP.Codigo_Inv 
         ORDER BY DF.ID, DF.Codigo";
    $detalle_fac = $this->db->datos($sSQL);

	$tipo_con = Tipo_Contribuyente_SP_MYSQL($_SESSION['INGRESO']['RUC']);
	if(count($datos_fac)>0 && count($tipo_con)>0)
	{
		$datos_fac['Tipo_contribuyente'] = $tipo_con;
	}
	// array_push($datos_fac, $tipo_con);


    $datos_cli_edu=$this->cliente_matri($ci);
    $sucursal = $this->catalogo_lineas('FA',$ser);
	   if($datos_cli_edu != '' && !empty($datos_cli_edu))
	   {
	   	    imprimirDocEle_fac($datos_fac,$detalle_fac,$datos_cli_edu,'matr',$id,null,'factura',null,null,1,false,$sucursal);
	   }else
	   {
		    $datos_cli_edu=$this->Cliente($ci);
		    imprimirDocEle_fac($datos_fac,$detalle_fac,$datos_cli_edu,$id,null,'factura',null,null,1,false,$sucursal);
	   }

   }

    function Cliente($cod,$grupo = false,$query=false,$clave=false)
   {
   	$cid=$this->conn;
	   $sql = "SELECT * from Clientes WHERE FA=1 ";
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
		

	   // print_r($sql);die();
	   $stmt = sqlsrv_query($cid, $sql);
	    if( $stmt === false)  
	      {  
		     echo "Error en consulta PA.\n";  
		     return '';
		     die( print_r( sqlsrv_errors(), true));  
	      }

	    $result = array();	
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
	      {
	    	$result[] = $row;
		    //echo $row[0];
	      }

	     // $result =  encode($result);
	      // print_r($result);
	      return $result;
   }


   function Cliente_facturas($cod,$grupo = false,$query=false,$clave=false)
   {
	   $sql = "SELECT CodigoC as 'Codigo',C.Cliente as 'Cliente',C.CI_RUC,C.Email FROM Facturas F
		INNER JOIN Clientes C ON F.CodigoC = C.Codigo WHERE C.T='N' ";
	   if($cod){
	   	$sql.=" and C.Codigo= '".$cod."'";
	   }
	   if($grupo)
	   {
	   	$sql.=" and C.Grupo= '".$grupo."'";
	   }
	   if($query)
	   {
	   	$sql.=" and C.Cliente +' '+ C.CI_RUC like '%".$query."%'";
	   }
	   if($clave)
	   {
	   	$sql.=" and C.Clave= '".$clave."'";
	   }

	   $sql.=" GROUP BY CodigoC,C.Cliente,C.CI_RUC,C.Email";
	   $sql.=" ORDER BY C.Cliente OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY;";
	   
	   // print_r($sql);die();
		$result = $this->db->datos($sql);
	   return $result;
   }
   

   

   function cliente_matri($codigo)
   {
   	$cid=$this->conn;
	   $sql = "SELECT * FROM Clientes_Matriculas WHERE Item = '".$_SESSION['INGRESO']['item']."'
		AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' and Codigo = '".$codigo."'";

		// print_r($sql);die();
	   $stmt = sqlsrv_query($cid, $sql);
	    if( $stmt === false)  
	      {  
		     echo "Error en consulta PA.\n";  
		     return '';
		     die( print_r( sqlsrv_errors(), true));  
	      }

	    $result = array();	
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
	      {
	    	$result[] = $row;
		    //echo $row[0];
	      }

	     // $result =  encode($result);
	      // print_r($result);
	      return $result;
   }

   function grupos($query)
   {
   	 $cid=$this->conn;
	   $sql = "SELECT DISTINCT Grupo FROM Clientes WHERE FA = '1' AND Grupo <>'.' ";
	   if($query)
	   {
	   	 $sql.=' AND Grupo LIKE "%'.$query.'%" ';
	   }
		// print_r($sql);die();
	   $stmt = sqlsrv_query($cid, $sql);
	    if( $stmt === false)  
	      {  
		     echo "Error en consulta PA.\n";  
		     return '';
		     die( print_r( sqlsrv_errors(), true));  
	      }

	    $result = array();	
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
	      {
	    	$result[] = $row;
	      }
	      // print_r($result);
	      return $result;
   }

    function Empresa_data()
   {
   	$cid=$this->conn;
	   $sql = "SELECT * FROM Empresas where Item='".$_SESSION['INGRESO']['item']."'";
	   $stmt = sqlsrv_query($cid, $sql);
	    if( $stmt === false)  
	      {  
		     echo "Error en consulta PA.\n";  
		     return '';
		     die( print_r( sqlsrv_errors(), true));  
	      }

	    $result = array();	
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
	      {
	    	$result[] = $row;
		    //echo $row[0];
	      }

	     // $result =  encode($result);
	      // print_r($result);
	      return $result;
   }

  function catalogo_lineas($TC,$SerieFactura)
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

  function eliminar_abonos($parametros)
  {
  	$sql="DELETE FROM Trans_Abonos WHERE Factura ='".$parametros['factura']."' AND Serie = '".$parametros['serie']."' AND CodigoC = '".$parametros['codigo']."';";
  	return $this->db->String_Sql($sql);
  }


  function factura_detalle($cod,$ser,$ci,$periodo=false)
  {
   	$id='factura_'.$ci;
   	$cid = $this->conn;
   	$sql="SELECT * 
   	FROM Facturas 
   	WHERE Serie='".$ser."' 
   	AND Factura='".$cod."' 
   	AND CodigoC='".$ci."' 
   	AND Item = '".$_SESSION['INGRESO']['item']."' ";
   	if($periodo==false || $periodo =='.')
   	{
	   $sql.=" AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' ";
	}else
	{
      	$sql.=" AND Periodo BETWEEN '01/01/".$periodo."' AND '31/12".$periodo."'";
	}

	// print_r($sql);die();
	return $this->db->datos($sql);
  }

  function trans_documentos($clave)
  {
  	$sql = "SELECT * 
  	FROM Trans_Documentos 
  	WHERE Clave_Acceso = '".$clave."'";
	return $this->db->datos($sql);
  }

   function DCTipoPago($cod=false)
   {
     $cid=$this->conn;
      $sql = "SELECT Codigo,(Codigo + ' ' + Descripcion) As CTipoPago
         FROM Tabla_Referenciales_SRI
         WHERE Tipo_Referencia = 'FORMA DE PAGO' ";
         if($cod)
         {
          $sql.=" AND Codigo = '".$cod."'";
         }
         $sql.=" ORDER BY Codigo ";
         // print_r($sql);die();
          $stmt = $this->db->datos($sql);
      return $stmt;
   }


  
}
?>