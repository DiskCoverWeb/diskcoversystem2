<?php 
if(!class_exists('variables_g'))
{
	include(dirname(__DIR__,2).'/db/variables_globales.php');//
    include(dirname(__DIR__,2).'/funciones/funciones.php');
}
@session_start(); 

/**
 * 
 */
class descargosM
{
	
	private $conn ;
	private $conn1 ;
	function __construct()
	{
	   $this->conn = cone_ajax();
	   $this->conn1 = new db();
	}

	function pedido_paciente($codigo_b=false,$tipo=false,$query=false,$desde=false,$hasta =false,$busfe=false,$area =false,$articulo=false)
	{

		$cid = $this->conn;
		
		$sql = "SELECT SUM(Valor_Total) as 'importe',Orden_No as ORDEN,Codigo_P as 'Codigo_B',A.Fecha as 'Fecha_Fab',C.Cliente as 'nombre',A.CodigoL as 'area',CS.Detalle as 'subcta',C.Matricula as 'his',A.Detalle as 'Detalle'
			FROM Trans_Kardex A
			LEFT JOIN Clientes C ON C.Codigo = A.Codigo_P 
			LEFT JOIN Catalogo_SubCtas CS ON CS.Codigo = A.CodigoL
			WHERE Numero= 0 AND Orden_No <> '.'
			AND A.Item = '".$_SESSION['INGRESO']['item']."' AND A.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			AND CS.Item = A.Item
			AND CS.Periodo = A.Periodo";
		if($codigo_b)
		{
			$sql.=" AND Codigo_P = '".$codigo_b."' ";
		}
		if($tipo=='P' AND $query!='')
		{
			$sql.=" AND Orden_No = '".$query."' ";
		}
		if($tipo=='C' AND $query!='')
		{
			$sql.=" AND Codigo_P LIKE '".$query."%' ";
		}
		if ($tipo=='N' AND $query!='') 
		{
			$sql.=" AND Cliente LIKE '%".$query."%'";
		}
		if($busfe)
		{		
			  $sql.=" AND A.Fecha BETWEEN '".$desde."' and '".$hasta."'";
		}
		if($area)
		{
			$sql.=" AND CS.Codigo like '".$area."%'";
		}
		if($articulo)
		{
			$sql.=" AND Codigo_Inv = '".$articulo."'";
		}

		$sql.=" GROUP BY Orden_No ,Codigo_P,A.Fecha,C.Cliente,A.CodigoL,CS.Detalle,C.Matricula,A.Detalle ORDER BY A.Fecha DESC";
		$sql.=" OFFSET 0 ROWS FETCH NEXT 50 ROWS ONLY;";
		
/*
		$sql="SELECT top(50) Valor_Total as 'importe',Codigo_P as 'Codigo_B',C.Cliente as 'nombre',Orden_No as 'ORDEN',A.CodigoL as 'area',A.Fecha as 'Fecha_Fab',C.Matricula as 'his',A.Detalle as 'Detalle',CS.Detalle as 'subcta'
			FROM Trans_Kardex A
			LEFT JOIN Clientes C ON A.Codigo_P  = C.Codigo
			LEFT JOIN Catalogo_SubCtas CS ON  A.CodigoL = CS.Codigo
			WHERE Numero= 0 
			AND A.Item = '".$_SESSION['INGRESO']['item']."' 
			AND A.Periodo = '".$_SESSION['INGRESO']['periodo']."'
			AND Orden_No <> '.'
			AND CS.Item = A.Item
			AND CS.Periodo = A.Periodo";
			if($codigo_b)
			{
				$sql.=" AND Codigo_P = '".$codigo_b."' ";
			}
			if($tipo=='P' AND $query!='')
			{
				$sql.=" AND Orden_No = '".$query."' ";
			}
			if($tipo=='C' AND $query!='')
			{
				$sql.=" AND Codigo_P LIKE '".$query."%' ";
			}
			if ($tipo=='N' AND $query!='') 
			{
				$sql.=" AND Cliente LIKE '%".$query."%'";
			}
			if($busfe)
			{		
				  $sql.=" AND A.Fecha BETWEEN '".$desde."' and '".$hasta."'";
			}
			if($area)
			{
				$sql.=" AND CS.Codigo like '".$area."%'";
			}
			if($articulo)
			{
				$sql.=" AND Codigo_Inv = '".$articulo."'";
			}

			$sql.=" GROUP BY Valor_Total,Codigo_P,Orden_No,A.Fecha,C.Cliente,A.CodigoL,C.Matricula,A.Detalle,CS.Detalle";*/
		// print_r($sql);die();
		$stmt = sqlsrv_query($cid, $sql);
        $datos =  array();
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
            $datos[]=$row;
	   }
       return $datos;
	}


	function productos_procesados($codigo_b=false,$tipo=false,$query=false,$desde=false,$hasta =false,$busfe=false)
	{

		$cid = $this->conn;
		$sql = "SELECT DISTINCT A.Codigo_Inv as 'CODIGO_INV',Salida AS 'CANTIDAD'
		FROM Trans_Kardex A 
		LEFT JOIN Clientes C ON A.Codigo_P = C.CI_RUC 
		LEFT JOIN Catalogo_SubCtas CS ON CS.Codigo = A.CodigoL
		WHERE A.Item = CS.Item
		AND A.Periodo = CS.Periodo
		AND A.Item = CS.Item
		AND A.Numero =0
		AND A.Codigo_Inv <>'.'";
		if($codigo_b)
		{
			$sql.=" AND Codigo_P = '".$codigo_b."' ";
		}
		if($tipo=='P' AND $query!='')
		{
			$sql.=" AND Orden_No = '".$query."' ";
		}
		if($tipo=='C' AND $query!='')
		{
			$sql.=" AND Codigo_P LIKE '".$query."%' ";
		}
		if ($tipo=='N' AND $query!='') 
		{
			$sql.=" AND Cliente LIKE '%".$query."%'";
		}
		if($busfe)
		{		
			  $sql.=" AND T.Fecha BETWEEN '".$desde."' and '".$hasta."'";
		}

		// print_r($sql);die();
		$stmt = sqlsrv_query($cid, $sql);
        $datos =  array();
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
            $datos[]=$row;
	   }
       return $datos;
	}


	function ordenes_producto_nega($codigo_b=false,$tipo=false,$query=false,$desde=false,$hasta =false,$busfe=false,$negativos=false)
	{

		$cid = $this->conn;
		$sql = "SELECT DISTINCT Orden_No as 'ORDEN' 
		FROM Trans_Kardex A 
		LEFT JOIN Clientes C ON A.Codigo_P = C.CI_RUC 
		LEFT JOIN Catalogo_SubCtas CS ON CS.Codigo = A.CodigoL 
		WHERE A.Item = CS.Item
		AND A.Periodo = CS.Periodo 
		AND A.Numero = 0 ";
		if($codigo_b)
		{
			$sql.=" AND Codigo_P = '".$codigo_b."' ";
		}
		if($tipo=='P' AND $query!='')
		{
			$sql.=" AND Orden_No = '".$query."' ";
		}
		if($tipo=='C' AND $query!='')
		{
			$sql.=" AND Codigo_P LIKE '".$query."%' ";
		}
		if ($tipo=='N' AND $query!='') 
		{
			$sql.=" AND Cliente LIKE '%".$query."%'";
		}
		if($busfe)
		{		
			  $sql.=" AND Fecha BETWEEN '".$desde."' and '".$hasta."'";
		}
		if($negativos)
		{
			$sql.= " AND Codigo_Inv IN (".$negativos.")";
		}
		
		// print_r($sql);die();
		$stmt = sqlsrv_query($cid, $sql);
        $datos =  array();
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
            $datos[]=$row;
	   }
       return $datos;
	}

	function pedido_paciente_distintos($codigo_b=false,$tipo=false,$query=false,$desde=false,$hasta =false,$busfe=false,$area=false,$articulo=false)
	{

		$cid = $this->conn;
		$sql = "SELECT DISTINCT Orden_No as 'ORDEN',Codigo_P,C.Cliente as 'nombre',A.CodigoL as 'area',CS.Detalle as 'subcta',C.Matricula as 'his',A.Detalle as 'Detalle' 
			FROM Trans_Kardex A
			LEFT JOIN Clientes C ON A.Codigo_P = C.CI_RUC 
			LEFT JOIN Catalogo_SubCtas CS ON CS.Codigo = A.CodigoL 
			WHERE A.Item = '".$_SESSION['INGRESO']['item']."'
			AND A.Periodo = '".$_SESSION['INGRESO']['periodo']."'
			AND A.Item = CS.Item
			AND A.Periodo = CS.Periodo
			AND Numero = 0
			AND Orden_No <> '.'";
		if($codigo_b)
		{
			$sql.=" AND Codigo_P = '".$codigo_b."' ";
		}
		if($tipo=='P' AND $query!='')
		{
			$sql.=" AND Orden_No = '".$query."' ";
		}
		if($tipo=='C' AND $query!='')
		{
			$sql.=" AND Codigo_P LIKE '".$query."%' ";
		}
		if ($tipo=='N' AND $query!='') 
		{
			$sql.=" AND Cliente LIKE '%".$query."%'";
		}
		if($busfe)
		{		
			  $sql.=" AND A.Fecha BETWEEN '".$desde."' and '".$hasta."'";
		}
		if($area)
		{
			$sql.=" AND CS.Codigo like '".$area."%' ";
		}
		if($articulo)
		{
			$sql.=" AND Codigo_Inv = '".$articulo."' ";
		}

		$sql.=" GROUP BY Orden_No ,Codigo_P,C.Cliente,A.CodigoL,CS.Detalle,C.Matricula,A.Detalle ORDER BY Orden_No DESC ";
		// print_r($sql);die();
		$stmt = sqlsrv_query($cid, $sql);
        $datos =  array();
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
            $datos[]=$row;
	   }
       return $datos;
	}


	


	function area_descargo($query = false,$codigo = false)
	{
		$sql = "SELECT   TC, Codigo, Detalle
		FROM   Catalogo_SubCtas
		WHERE  TC='CC' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item= '".$_SESSION['INGRESO']['item']."'";
		if($query)
		{
			$sql.=" AND Detalle like '%".$query."%'";
		}
		if($codigo)
		{
			$sql.=" AND Codigo ='".$codigo."'";
		}
		// print_r($sql);die();
		return $this->conn1->datos($sql); 
		// $stmt = sqlsrv_query($cid, $sql);
  //       $datos =  array();
	 //   if( $stmt === false)  
	 //   {  
		//  echo "Error en consulta PA.\n";  
		//  return '';
		//  die( print_r( sqlsrv_errors(), true));  
	 //   }
	 //    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	 //   {
  //           $datos[]=$row;
	 //   }
  //      return $datos;
	}

	function actualizar_his($dato,$where)
	{
		return update_generico($dato,'Clientes',$where);
	}


function elimina_pedido($parametros)
	{

		$cid = $this->conn;
		$sql = "DELETE FROM Trans_Kardex WHERE Orden_No='".$parametros['ped']."' and CodigoL ='".$parametros['area']."' AND Numero=0";
		// print_r($sql);die();
		$stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  

			return -1;

		}else
		{
		   return 1;

		}		
	}

	// function elimina_pedido($parametros)
	// {

	// 	$cid = $this->conn;
	// 	$sql = "DELETE FROM Asiento_K WHERE ORDEN='".$parametros['ped']."' and SUBCTA ='".$parametros['area']."'";
	// 	// print_r($sql);die();
	// 	$stmt = sqlsrv_query($cid, $sql);
	// 	if( $stmt === false)  
	// 	{  
	// 		echo "Error en consulta PA.\n";  
	// 		die( print_r( sqlsrv_errors(), true));  

	// 		return -1;

	// 	}else
	// 	{
	// 	   return 1;

	// 	}		
	// }

	// function cargar_lineas_pedidos($orden,$fecha)
	// {
	// 	// print_r($hasta.'-'.$hasta);die();
 //     $cid = $this->conn;
 //    // 'LISTA DE CODIGO DE ANEXOS
 //     $sql = "SELECT * FROM Asiento_K WHERE ORDEN = '".$orden."'";
 //     if($fecha)
 //     {
 //     	$sql.=" AND Fecha_Fab = '".$fecha."'";
 //     }
 //     $sql.=" ORDER BY A_No DESC";
 //        // print_r($sql);die();
 //        $stmt = sqlsrv_query($cid, $sql);
 //        $datos =  array();
	//    if( $stmt === false)  
	//    {  
	// 	 echo "Error en consulta PA.\n";  
	// 	 return '';
	// 	 die( print_r( sqlsrv_errors(), true));  
	//    }
	//     while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	//    {
	// 	$datos[]=$row;	
	//    }
 //       return $datos;
	// }



function cargar_lineas_pedidos($orden,$fecha)
	{
		// print_r($hasta.'-'.$hasta);die();
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT T.Codigo_Inv AS 'CODIGO_INV',Salida AS 'CANTIDAD',Valor_Unitario as 'VALOR_UNIT',T.Valor_Total as 'VALOR_TOTAL',C.Producto as 'PRODUCTO'
			FROM Trans_Kardex T
			LEFT JOIN Catalogo_Productos C ON T.Codigo_Inv = C.Codigo_Inv 
			WHERE T.Item = C.Item
			AND T.Periodo = C.Periodo 
			AND Orden_No = '".$orden."'";
     if($fecha)
     {
     	$sql.=" AND T.Fecha = '".$fecha."'";
     }
     // $sql.=" ORDER BY A_No DESC";
        // print_r($sql);die();
        $stmt = sqlsrv_query($cid, $sql);
        $datos =  array();
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		$datos[]=$row;	
	   }
       return $datos;
	}


function cargar_lineas_pedidos_por_fecha($orden,$desde=false,$hasta=false)
	{
		// print_r($hasta.'-'.$hasta);die();
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT DISTINCT Fecha AS 'Fecha_Fab'  FROM Trans_Kardex WHERE Orden_No = '".$orden."' ";

     if($desde)
       { 
         $sql.= " AND Fecha BETWEEN '".$desde."' and '".$hasta."' ";
       }

      $sql.=" ORDER BY Fecha desc ";

        // print_r($sql);die();
        $stmt = sqlsrv_query($cid, $sql);
        $datos =  array();
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		$datos[]=$row;	
	   }
       return $datos;
	}


	// function cargar_lineas_pedidos_por_fecha($orden,$desde=false,$hasta=false)
	// {
	// 	// print_r($hasta.'-'.$hasta);die();
 //     $cid = $this->conn;
 //    // 'LISTA DE CODIGO DE ANEXOS
 //     $sql = "SELECT DISTINCT Fecha_Fab  FROM Asiento_K WHERE ORDEN = '".$orden."' ";

 //     if($desde)
 //       { 
 //         $sql.= " AND Fecha_Fab BETWEEN '".$desde."' and '".$hasta."' ";
 //       }

 //      $sql.=" ORDER BY Fecha_Fab desc ";

 //        // print_r($sql);die();
 //        $stmt = sqlsrv_query($cid, $sql);
 //        $datos =  array();
	//    if( $stmt === false)  
	//    {  
	// 	 echo "Error en consulta PA.\n";  
	// 	 return '';
	// 	 die( print_r( sqlsrv_errors(), true));  
	//    }
	//     while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	//    {
	// 	$datos[]=$row;	
	//    }
 //       return $datos;
	// }

	function imprimir_excel($stmt1)
	{		
	     exportar_excel_descargos($stmt1,null,null,1);
	}


}