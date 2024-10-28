<?php
include(dirname(__DIR__,2).'/funciones/funciones.php');
include(dirname(__DIR__,3).'/lib/fpdf/reporte_de.php');
@session_start(); 
/**
 * 
 */
class lista_facturasM
{
	private $conn;	
	private $db;
	function __construct()
	{
		$this->conn = cone_ajax();
		$this->db = new db();
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

   function facturas_emitidas_tabla($codigo,$periodo=false,$desde=false,$hasta=false,$serie=false,$autorizados = false)
   {
   	$cid = $this->conn;

   			// print_r($codigo);die();
		
		$sql ="SELECT T,TC,Serie,Autorizacion,Factura,Fecha,SubTotal,Con_IVA,IVA,Descuento+Descuento2 as Descuentos,Total_MN as Total,Saldo_MN as Saldo,RUC_CI,TB,Razon_Social,CodigoC,ID 
		FROM Facturas 
		WHERE Item = '".$_SESSION['INGRESO']['item']."' 
		AND TC = 'FA' ";
		if($_SESSION['INGRESO']['periodo']=='.')
		{
			$sql.=" AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
		}else
		{
			$year =  $dateNew = DateTime::createFromFormat('d/m/Y', $_SESSION['INGRESO']['periodo'])->format('Y');
			// print_r($year);die();
			$sql.=" AND Periodo BETWEEN '01/01/".$year."' AND '".$_SESSION['INGRESO']['periodo']."'";
		}
		if($codigo!='T' && $codigo!='')
		{
			// si el codigo es T se refiere a todos
		   $sql.=" AND CodigoC ='".$codigo."'";
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
        // if($periodo && $periodo!='.' && $periodo!='')
        // {
       	//  $sql.= " AND Fecha BETWEEN '".$desde."' AND '".$hasta."'";
        // }
        if($desde!='' && $hasta!='')
       {
       	 $sql.= " AND Fecha BETWEEN   '".$desde."' AND '".$hasta."' ";
       }

       $sql.=" ORDER BY Serie,Factura DESC "; 
	$sql.=" OFFSET ".$_SESSION['INGRESO']['paginacionIni']." ROWS FETCH NEXT ".$_SESSION['INGRESO']['numreg']." ROWS ONLY;";   
    // print_r($_SESSION['INGRESO']);
	// print_r($sql);die();    
	return $this->db->datos($sql);

       // return $datos;
   }

   function facturas_lineas_emitidas_tabla($codigo=false,$serie=false,$autorizados=false,$factura=false)
   {
   	$cid = $this->conn;

   			// print_r($codigo);die();		
		$sql ="SELECT *
		FROM Detalle_Factura
		WHERE Item = '".$_SESSION['INGRESO']['item']."' 
		AND TC = 'FA' ";
		if($_SESSION['INGRESO']['periodo']=='.')
		{
			$sql.=" AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
		}else
		{
			$year =  $dateNew = DateTime::createFromFormat('d/m/Y', $_SESSION['INGRESO']['periodo'])->format('Y');
			// print_r($year);die();
			$sql.=" AND Periodo BETWEEN '01/01/".$year."' AND '".$_SESSION['INGRESO']['periodo']."'";
		}
		if($codigo!='T' && $codigo!='')
		{
			// si el codigo es T se refiere a todos
		   $sql.=" AND CodigoC ='".$codigo."'";
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
        if($factura)
        {
       	 $sql.= " AND Factura = '".$factura."'";
        }
       
       $sql.=" ORDER BY Serie,Factura DESC "; 
	// $sql.=" OFFSET ".$_SESSION['INGRESO']['paginacionIni']." ROWS FETCH NEXT ".$_SESSION['INGRESO']['numreg']." ROWS ONLY;";   
    // print_r($_SESSION['INGRESO']);
	// print_r($sql);die();    
	return $this->db->datos($sql);

       // return $datos;
   }

    function facturas_emitidas_tabla_op1($codigo,$periodo=false,$desde=false,$hasta=false,$serie=false,$autorizados = false)
   {
   	$cid = $this->conn;

   			// print_r($codigo);die();
		
		$sql ="SELECT F.T,F.TC,F.Serie,F.Autorizacion,F.Factura,F.Fecha,Mes,Ticket,SubTotal,Con_IVA,IVA,Descuento+Descuento2 as Descuentos,Total_MN as Total,Saldo_MN as Saldo,RUC_CI,TB,Razon_Social,F.CodigoC,F.ID 
		FROM Facturas F
		INNER JOIN detalle_Factura  DF on F.Factura = DF.Factura
		WHERE F.Item = '".$_SESSION['INGRESO']['item']."' 
		AND F.TC = 'FA' ";
		if($_SESSION['INGRESO']['periodo']=='.')
		{
			$sql.=" AND F.Periodo = '".$_SESSION['INGRESO']['periodo']."'";
		}else
		{
			$year =  $dateNew = DateTime::createFromFormat('d/m/Y', $_SESSION['INGRESO']['periodo'])->format('Y');
			// print_r($year);die();
			$sql.=" AND F.Periodo BETWEEN '01/01/".$year."' AND '".$_SESSION['INGRESO']['periodo']."'";
		}
		if($codigo!='T' && $codigo!='')
		{
			// si el codigo es T se refiere a todos
		   $sql.=" AND F.CodigoC ='".$codigo."'";
		} 
		if($serie)
		{
			// si el codigo es T se refiere a todos
		   $sql.=" AND F.Serie ='".$serie."'";
		}
		if($autorizados)
		{
			// print_r($autorizados);die();
			if($autorizados==1)
			{
				$sql.=" AND Len(F.Autorizacion)>13";
			}else
			{
				$sql.=" AND Len(F.Autorizacion)=13";
			}
		} 
        // if($periodo && $periodo!='.' && $periodo!='')
        // {
       	//  $sql.= " AND Fecha BETWEEN '".$desde."' AND '".$hasta."'";
        // }
        if($desde!='' && $hasta!='')
       {
       	 $sql.= " AND F.Fecha BETWEEN   '".$desde."' AND '".$hasta."' ";
       }
       $sql.="group by F.T,F.TC,F.Serie,F.Autorizacion,F.Factura,F.Fecha,Mes,Ticket,SubTotal,Con_IVA,IVA,Descuento+Descuento2,Total_MN,Saldo_MN,RUC_CI,TB,Razon_Social,F.CodigoC,F.ID";

       $sql.=" ORDER BY Serie,Factura DESC "; 
	$sql.=" OFFSET ".$_SESSION['INGRESO']['paginacionIni']." ROWS FETCH NEXT ".$_SESSION['INGRESO']['numreg']." ROWS ONLY;";   
    // print_r($_SESSION['INGRESO']);
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

   	$sql1="SELECT * 
   	FROM Detalle_Factura 
   	WHERE Factura = '".$cod."' 
   	AND CodigoC='".$ci."' 
   	AND DF.TC = 'FA'
   	AND Item = '".$_SESSION['INGRESO']['item']."'
	AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' ";	
	$detalle_fac = $this->db->datos($sql1);

	// $sql2 = "SELECT * FROM lista_tipo_contribuyente WHERE RUC = '".$_SESSION['INGRESO']['RUC']."'";
	// ejecutar_procesos_almacenados($sql,$parametros,$retorna=false,$tipo='MYSQL');
	$tipo_con = Tipo_Contribuyente_SP_MYSQL($_SESSION['INGRESO']['RUC']);

   $clave_acceso =  $datos_fac[0]['Autorizacion'];
   $sql2="SELECT * 
    FROM Trans_Abonos 
    WHERE Factura = '".$cod."' 
    AND CodigoC='".$ci."' 
    AND Item = '".$_SESSION['INGRESO']['item']."'
    AND Autorizacion = '".$clave_acceso."'
    AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' "; 
  $detalle_abonos = $this->db->datos($sql2);

	$sucursal = $this->catalogo_lineas('FA',$ser);
	$forma_pago = $this->DCTipoPago($datos_fac[0]['Tipo_Pago']);
	if(count($forma_pago)>0)
	{
		$datos_fac[0]['Tipo_Pago'] = $forma_pago[0]['CTipoPago'];
	}

	// print_r($forma_pago);die();
	if(count($sucursal)==0)
	{
		$sucursal = array();
	}
	if(count($datos_fac)>0 && count($tipo_con)>0)
	{
		$datos_fac['Tipo_contribuyente'] = $tipo_con;
	}
	// array_push($datos_fac, $tipo_con);


    $datos_cli_edu=$this->cliente_matri($ci);
	   if($datos_cli_edu != '' && !empty($datos_cli_edu))
	   {
	   		imprimirDocEle_fac($datos_fac,$detalle_fac,$datos_cli_edu,'matr',null,'factura',null,null,false,$detalle_abonos,$sucursal);
	   }else
	   {
		    $datos_cli_edu=$this->Cliente($ci);
		    imprimirDocEle_fac($datos_fac,$detalle_fac,$datos_cli_edu,false,null,'factura',null,null,false,$detalle_abonos,$sucursal);
	   }

   }

   function pdf_factura_descarga($cod,$ser,$ci,$periodo=false,$imp=1)
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
         AND DF.TC = '" . $TFA['TC'] . "' 
         AND DF.Serie = '" . $TFA['Serie'] . "' 
         AND DF.Autorizacion = '" . $TFA['Autorizacion'] . "' 
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
    // print_r($imp);die();
	   if($datos_cli_edu != '' && !empty($datos_cli_edu))
	   {
	   	  // print_r($imp."aaa");die();
	   	    imprimirDocEle_fac($datos_fac,$detalle_fac,$datos_cli_edu,'matr',$id,null,'factura',null,$imp,false,false,$sucursal);
	   }else
	   {
		    $datos_cli_edu=$this->Cliente($ci);
		    imprimirDocEle_fac($datos_fac,$detalle_fac,$datos_cli_edu,$id,null,'factura',null,null,$imp,false,$sucursal);
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
		

	   // print_r($sql);
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
	   $sql = "SELECT CodigoC as 'Codigo',C.Cliente as 'Cliente',C.CI_RUC,C.Email,C.Direccion,C.Telefono  
	   FROM Facturas F
	   INNER JOIN Clientes C ON F.CodigoC = C.Codigo 
	   AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
	   AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
	    ";
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
	   $sql.=" GROUP BY CodigoC,C.Cliente,C.CI_RUC,C.Email,C.Direccion,C.Telefono ";
	   $sql.=" ORDER BY C.Cliente OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY;";
	   
	   // print_r($sql);die();
		$result = $this->db->datos($sql);
	   return $result;
   }

   function Cliente_facturas_estado($cod,$grupo = false,$query=false, $estado)
   {
	   $sql = "SELECT CodigoC as 'Codigo',C.Cliente as 'Cliente',C.CI_RUC,C.Email,C.Direccion,C.Telefono  
	   FROM Facturas F
	   INNER JOIN Clientes C ON F.CodigoC = C.Codigo  
	   AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
	   AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'   
	   ";
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
	   /*if($clave)
	   {
	   	$sql.=" and C.Clave= '".$clave."'";
	   }*/
	   $sql.=" AND F.T = '".$estado."' ";
	   $sql.=" GROUP BY CodigoC,C.Cliente,C.CI_RUC,C.Email,C.Direccion,C.Telefono ";
	   $sql.=" ORDER BY C.Cliente OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY;";
	   
		//print_r($sql);
		$result = $this->db->datos($sql);
	   return $result;
   }

   //Gerencia -> Cartera Clientes -> btn Buscar
   function Cliente_facturas_electronicas($fecha_inicio, $fecha_fin, $estado, $codigoC = false, $serie = false)
   { //By Leo
	   // $sql = "SELECT F.Razon_Social,F.T,F.Serie,F.Factura,F.Fecha,F.Fecha_V,F.Total_MN,F.Saldo_MN,C.CI_RUC,F.TC,
	   // F.Abonos_MN,F.Total_ME,F.Saldo_ME,F.Autorizacion,
	   // F.RUC_CI As RUC_CI_SRI,F.Forma_Pago,C.Telefono,C.Celular,C.Ciudad,
	   // C.Direccion,C.DireccionT,C.Email,C.Grupo,DATEDIFF(day,'" .date('Y-m-d'). "',F.Fecha_V) As Dias_De_Mora,
	   // A.Nombre_Completo As Ejecutivo,C.Plan_Afiliado As Sectorizacion,A.Cod_Ejec,F.Chq_Posf 
	   // FROM Facturas As F,Clientes As C,Accesos As A 
	   // WHERE F.Fecha BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "'
	   // AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
	   // AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
	   // AND F.T = '" . $estado ."' ";
	   
	   $sql = "SELECT F.Razon_Social,F.T,F.Serie,F.Factura,F.Fecha,F.Fecha_V,F.Total_MN,F.Saldo_MN,C.CI_RUC,F.TC,F.Autorizacion,C.Codigo
	   FROM Facturas As F,Clientes As C,Accesos As A 
	   WHERE F.Fecha BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "'
	   AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
	   AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
	   AND F.T = '" . $estado ."' ";

	   if($codigoC){
			$sql .= "AND F.CodigoC = '".$codigoC."'";
	   }

	   if($serie){
			$sql .= "AND F.Serie = '".$serie."'";
	   }
	   
	   $sql .= "AND C.Codigo = F.CodigoC
	   AND A.Codigo = F.Cod_Ejec 
	   AND F.TC NOT IN ('C','P') 
	   ORDER BY C.Cliente,F.Razon_Social,F.TC,F.Serie,F.Fecha,F.Factura 
	   ";
	   $result = $this -> db -> datos($sql);
	   //print_r($result);die();
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
  	$sql="DELETE FROM Trans_Abonos 
  	WHERE Factura ='".$parametros['factura']."' 
  	AND Serie = '".$parametros['serie']."' 
  	AND CodigoC = '".$parametros['codigo']."' 
  	AND Item = '".$_SESSION['INGRESO']['item']."' 
  	AND Periodo = '".$_SESSION['INGRESO']['periodo']."';";
  	return $this->db->String_Sql($sql);
  }


  function factura_detalle($cod,$ser,$ci=false,$periodo=false)
  {
   	$id='factura_'.$ci;
   	$cid = $this->conn;
   	$sql="SELECT * 
   	FROM Facturas 
   	WHERE  Item = '".$_SESSION['INGRESO']['item']."'
   	AND Serie='".$ser."' 
   	AND Factura='".$cod."'";
   	if($ci)
   	{ 
   		$sql.=" AND CodigoC='".$ci."'";
   	}
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