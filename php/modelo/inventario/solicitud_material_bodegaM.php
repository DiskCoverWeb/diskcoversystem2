<?php 
include(dirname(__DIR__,2).'/db/variables_globales.php');//
include(dirname(__DIR__,2).'/funciones/funciones.php');
$_SESSION['INGRESO']['modulo_']='60';
/**
 * 
 */
class solicitud_material_bodegaM
{
	private $conn ;
	private $db;
	function __construct()
	{
	   $this->conn = cone_ajax();
	   $this->db = new db();
	}

	function asiento_csv(){
		try{
			$sql = "SELECT *
					FROM Asiento_CSV_" . $_SESSION['INGRESO']['CodigoU'] . " ";
			$AdoQuery = $this->db->datos($sql);
			$tabla = 'Asiento_CSV_' . $_SESSION['INGRESO']['CodigoU'];
			$datos = grilla_generica_new($sql, $tabla, '', '', false, false, false, 1, 1, 1, 100);
			return array('datos' => $datos);
		}catch(Exception $e){
			throw new Exception("Error al cargar los datos", 1);
		}
	}

	function listar_articulos($query='')
	{
			$cid = $this->conn;
     //$sql2="SELECT  Codigo_Inv,Producto,Unidad from Catalogo_Productos WHERE Item = '".$_SESSION['INGRESO']['item']."' AND TC = 'I' AND Periodo='".$_SESSION['INGRESO']['periodo']."'";
			$sql2 = "SELECT Codigo_Inv,Producto,TC,Valor_Total,Unidad,Stock_Actual,Cta_Inventario FROM Catalogo_Productos WHERE T <>'I' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item = '".$_SESSION['INGRESO']['item']."' AND LEN(Cta_Inventario)>3 AND LEN(Cta_Costo_Venta)>3 AND Producto LIKE '%".$query."%'";
     // print_r($sql2);die();

	   $datos1 =  $this->db->datos($sql2);
	   $datos = array();
	   foreach ($datos1 as $key => $value) {
	   	 $datos[]=array('id'=>$value['Codigo_Inv'].','.$value['Unidad'].','.$value['Stock_Actual'].','.$value['TC'].','.$value['Valor_Total'].','.$value['Cta_Inventario'],'text'=>$value['Producto']);		
	   }

       return $datos;
	}

	function lista_hijos($codigo,$query='')
	{
		$cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS

// 	 $sql = "SELECT CP.Codigo_Inv,CP.Producto,CP.TC,TK.Costo as 'Valor_Total',CP.Unidad, SUM(Entrada-Salida) As Stock_Actual ,CP.Cta_Inventario
// FROM Catalogo_Productos As CP, Trans_Kardex AS TK WHERE CP.INV = 1 AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."' AND CP.Item = '".$_SESSION['INGRESO']['item']."'AND LEN(CP.Cta_Inventario)>3 AND SUBSTRING(CP.Codigo_Inv,1, 5) = '".$codigo."'  AND CP.Producto LIKE '%".$query."%' AND TK.T<> 'A' AND CP.Periodo = TK.Periodo AND CP.Item = TK.Item AND CP.Codigo_Inv = TK.Codigo_Inv group by CP.Codigo_Inv,CP.Producto,CP.TC,CP.Valor_Total,CP.Unidad,TK.Costo,CP.Cta_Inventario having SUM(TK.Entrada-TK.Salida) <> 0 
// order by CP.Codigo_Inv,CP.Producto,CP.TC,CP.Valor_Total,CP.Unidad,CP.Cta_Inventario";
      $sql = "SELECT Codigo_Inv,Producto,TC,Valor_Total,Unidad,Stock_Actual,Cta_Inventario FROM Catalogo_Productos WHERE INV = 1 AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item = '".$_SESSION['INGRESO']['item']."' AND LEN(Cta_Inventario)>3 AND LEN(Cta_Costo_Venta)>3 AND Codigo_Inv LIKE '".$codigo."%'";

     // if($query !='')
     // {
     // 	$sql .=" AND Producto LIKE '%".$query."%'"; 
     // }
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
	   	array_push($datos, ['id'=>$row['Codigo_Inv'].','.$row['Unidad'].','.$row['Stock_Actual'].','.$row['TC'].','.$row['Valor_Total'].','.$row['Cta_Inventario'],'text'=>mb_convert_encoding($row['Producto'], 'UTF-8')]);
	   	// array_push($datos,  ['id'=>$row['Codigo_Inv'].','.$row['Unidad'].','.$row['Stock_Actual'].','.$row['TC'].','.$row['Valor_Total'].','.$row['Cta_Inventario'],'text'=>$row['Producto']]);
	   }
	   // print_r($datos);die();
       return $datos;

	}

	function lista_productos_por_centro_costo($centro,$query=false,$codigo_inv=false)
	{
		$sql="SELECT CC.Cuenta,CP.Producto,TP.Presupuesto,TP.Codigo_Inv,TP.Cta,TP.ID,TP.Total as 'consu',CP.* FROM Trans_Presupuestos TP 
			INNER JOIN Catalogo_Productos CP ON TP.Codigo_Inv = CP.Codigo_Inv
			INNER JOIN Catalogo_Cuentas CC ON TP.Cta = CC.Codigo
			WHERE TP.Item = '".$_SESSION['INGRESO']['item']."'
			AND TP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
			AND CP.Item = TP.Item
			AND CP.Periodo = TP.Periodo
			AND CC.Item = TP.Item
			AND CC.Periodo = TP.Periodo
			AND TP.Cta = CC.Codigo  
			AND TP.Cta ='".$centro."'";
			if($query)
			{
				$sql.=" AND CP.Producto like '%".$query."%'";
			}
			if($codigo_inv)
			{
				$sql.=" AND TP.Codigo_Inv = '".$codigo_inv."'";
			}	
			$sql.="ORDER BY CC.Cuenta,CP.Producto,TP.ID";
			// print_r($sql);die();
			$datos1 =  $this->db->datos($sql);
			// $datos = array();
			// foreach ($datos1 as $key => $value) {
			//    	 $datos[]=array('id'=>$value['Codigo_Inv'].','.$value['Unidad'].','.$value['Stock_Actual'].','.$value['TC'].','.$value['Valor_Total'].','.$value['Cta_Inventario'].','.$value['Presupuesto'].','.$value['consu'],'text'=>$value['Producto']);		
			// }
		     return $datos1;
	}

	function lista_hijos_id($query)
	{
			$cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
      $sql = "SELECT CP.Codigo_Inv,CP.Producto,CP.TC,TK.Costo as 'Valor_Total',CP.Unidad, SUM(Entrada-Salida) As Stock_Actual ,CP.Cta_Inventario
FROM Catalogo_Productos As CP, Trans_Kardex AS TK 
WHERE CP.INV = 1 
AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
AND CP.Item = '".$_SESSION['INGRESO']['item']."'
AND LEN(CP.Cta_Inventario)>3 
AND CP.Codigo_Inv LIKE '".$query."' 
AND TK.T<> 'A' 
AND CP.Periodo = TK.Periodo 
AND CP.Item = TK.Item 
AND CP.Codigo_Inv = TK.Codigo_Inv 
group by CP.Codigo_Inv,CP.Producto,CP.TC,CP.Valor_Total,CP.Unidad,TK.Costo,CP.Cta_Inventario having SUM(TK.Entrada-TK.Salida) <> 0 
order by CP.Codigo_Inv,CP.Producto,CP.TC,CP.Valor_Total,CP.Unidad,CP.Cta_Inventario";
   

     // print_r($sql);die();


		 $datos1 =  $this->db->datos($sql);
		 $datos = array();
		 foreach ($datos1 as $key => $value) {
		   	$datos[]=array('id'=>$value['Codigo_Inv'].','.$value['Unidad'].','.$value['Stock_Actual'],'text'=>$value['Producto']);		
		 }
	     return $datos;


   //      $stmt = sqlsrv_query($cid, $sql);
   //      $datos =  array();
	  //  if( $stmt === false)  
	  //  {  
		 // echo "Error en consulta PA.\n";  
		 // return '';
		 // die( print_r( sqlsrv_errors(), true));  
	  //  }
	  //   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	  //  {
	  //  	array_push($datos, ['id'=>$row['Codigo_Inv'].','.$row['Unidad'].','.$row['Stock_Actual'],'text'=>mb_convert_encoding($row['Producto'], 'UTF-8')]);
	  //  	// array_push($datos,  ['id'=>$row['Codigo_Inv'].','.$row['Unidad'].','.$row['Stock_Actual'],'text'=>$row['Producto']]);
	  //  }
   //     return $datos;

	}

	function Detalle_producto($query)
	{
			$cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
      $sql = "SELECT * FROM Catalogo_Productos CP WHERE INV = 1 
      AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
      AND Item = '".$_SESSION['INGRESO']['item']."'
      AND LEN(CP.Cta_Inventario)>3 AND Codigo_Inv = '".$query."'";
   

     // print_r($sql);die();


		$datos =  $this->db->datos($sql);		
	     return $datos;



	}



	function listar_cc($query='',$proyectos=false)
	{
			$cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT Codigo,Cuenta 
     FROM Catalogo_Cuentas 
     WHERE TC='G' 
     AND DG='D' 
     AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
     AND Item = '".$_SESSION['INGRESO']['item']."' ";
     if($query !='')
     {
     	$sql .=" AND Cuenta+' '+Codigo LIKE '%".$query."%'"; 
     }
      if($proyectos)
     {
     	$sql .=" AND Codigo LIKE '".$proyectos."%'"; 
     }
     	// print_r($sql);die();
		 $datos1 =  $this->db->datos($sql);

		 $datos = array();
		 foreach ($datos1 as $key => $value) {
		   	$datos[]=array('id'=>$value['Codigo'],'text'=>$value['Cuenta']);		
		 }
	     return $datos;

	}

	function listar_cc_info($query='')
	{
			$cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT Codigo,Cuenta FROM Catalogo_Cuentas 
     WHERE DG='D' 
     AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
     AND Item = '".$_SESSION['INGRESO']['item']."' ";
     if($query !='')
     {
     	$sql .=" AND Cuenta+' '+Codigo LIKE '%".$query."%'"; 
     }
	   $datos1 =  $this->db->datos($sql);
	   $datos = array();
	   foreach ($datos1 as $key => $value) {
	   	 $datos[]=array('id'=>$value['Codigo'],'text'=>$value['Cuenta']);		
	   }
       return $datos;

	}

	function listar_rubro($query='',$cc=false)
	{


		$sql = "SELECT CS.Detalle,CS.Codigo, 0 As Credito 
		FROM Catalogo_SubCtas As CS, Trans_Presupuestos As TP
		WHERE CS.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND CS.Item = '".$_SESSION['INGRESO']['item']."'
		AND TP.Cta = '".$cc."'
		AND CS.TC = 'G'
		AND TP.MesNo = 0
		AND CS.Periodo = TP.Periodo
		AND CS.Item = TP.Item
		AND CS.Codigo = TP.Codigo";
	     if($query !='')
	     {
	     	$sql .=" AND CS.Detalle+' '+CS.Codigo LIKE '%".$query."%'"; 
	     }
	     
	     $sql.= "  ORDER BY CS.Detalle ";

	      //print_r($sql);die();
		   $datos1 =  $this->db->datos($sql);
		   $datos = array();
		   foreach ($datos1 as $key => $value) {
		   	 $datos[]=array('id'=>$value['Codigo'],'text'=>$value['Detalle']);		
		   }
	       return $datos;
	}

	function listar_rubro_bajas($query='',$proyectos=false)
	{
	
	$cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT Codigo+','+TC as 'Codigo',Detalle FROM Catalogo_SubCtas WHERE TC='G' AND Nivel='01' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item = '".$_SESSION['INGRESO']['item']."' ";
     if($query !='')
     {
     	$sql .=" AND Detalle+' '+Codigo LIKE '%".$query."%'"; 
     }
      if($proyectos)
     {
     	$sql .=" AND Cta_Reembolso LIKE '".$proyectos."%'"; 
     }
     $sql.= "  ORDER BY Codigo ASC";

	   $datos1 =  $this->db->datos($sql);
	   $datos = array();
	   foreach ($datos1 as $key => $value) {
	   	 $datos[]=array('id'=>$value['Codigo'],'text'=>$value['Detalle']);		
	   }
	
       return $datos;
	}
	
	function lista_entrega($fecha=false,$orden=false)
	{
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT * FROM Asiento_K AK
		INNER JOIN Catalogo_Cuentas  CC ON AK.CONTRA_CTA = CC.Codigo
		INNER JOIN Catalogo_SubCtas  CS ON AK.SUBCTA = CS.Codigo
		WHERE CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' 
		AND AK.Item = '".$_SESSION['INGRESO']['item']."' 
		AND CC.Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND CC.Item = CS.Item
		AND CC.Periodo = CS.Periodo
		AND AK.Item = CC.Item
		AND AK.TC = 'P'";
         if($fecha)
         {
         	$sql .= " AND Fecha_Fab='".$fecha."'";
         }
        
          // print_r($sql);die();

	   $datos =  $this->db->datos($sql);
       return $datos;
	}

	function lista_entrega_salida($fecha=false,$orden=false)
	{
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT AK.Codigo_Inv,Producto,Salida,Contra_Cta,CodigoL,AK.ID,Cliente,Orden_No,AK.TC,CC.Cuenta,SC.Detalle
     	FROM Trans_Kardex AK
		INNER JOIN Catalogo_Cuentas  CC ON AK.CONTRA_CTA = CC.Codigo
		inneR JOIN Catalogo_Productos CP ON AK.Codigo_Inv = CP.Codigo_Inv
		INNER JOIN Clientes C ON AK.CodigoU = C.Codigo
		INNER JOIN Catalogo_SubCtas  SC ON AK.CodigoL = SC.Codigo
		AND AK.Item = '".$_SESSION['INGRESO']['item']."' 
		AND CC.Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND AK.Item = CC.Item
		AND AK.Item = CP.Item
		AND AK.Periodo = CP.Periodo
		AND AK.Item = SC.Item
		AND AK.Periodo = SC.Periodo
		AND AK.T = 'S'
		AND (AK.TC = '.' OR AK.TC = 'GC')";
         if($fecha)
         {
         	$sql .= " AND AK.Fecha='".$fecha."'";
         }
         if($orden)
         {
         	$sql.= " AND Orden_No='".$orden."'";
         }

         // print_r($sql);die();
	   $datos =  $this->db->datos($sql);
       return $datos;
	}

	function lista_entrega_salida_check($fecha=false,$orden=false)
	{
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT AK.Codigo_Inv,Producto,Salida,Contra_Cta,CodigoL,AK.ID,Cliente,Orden_No,AK.TC,CC.Cuenta,SC.Detalle
     	FROM Trans_Kardex AK
		INNER JOIN Catalogo_Cuentas  CC ON AK.CONTRA_CTA = CC.Codigo
		inneR JOIN Catalogo_Productos CP ON AK.Codigo_Inv = CP.Codigo_Inv
		INNER JOIN Clientes C ON AK.CodigoU = C.Codigo
		INNER JOIN Catalogo_SubCtas  SC ON AK.CodigoL = SC.Codigo
		WHERE AK.CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' 
		AND AK.Item = '".$_SESSION['INGRESO']['item']."' 
		AND CC.Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND AK.Item = CC.Item
		AND AK.Item = CP.Item
		AND AK.Periodo = CP.Periodo
		AND AK.Item = SC.Item
		AND AK.Periodo = SC.Periodo
		AND AK.T = 'S'
		AND AK.TC = 'K'";
         if($fecha)
         {
         	$sql .= " AND AK.Fecha='".$fecha."'";
         }
         if($orden)
         {
         	$sql.= " AND Orden_No='".$orden."'";
         }

         // print_r($sql);die();
	   $datos =  $this->db->datos($sql);
       return $datos;
	}

   function eliminar($codigo,$po)
	{
		 $cid=$this->conn;
		$sql = "DELETE Asiento_K WHERE CODIGO_INV = '".$codigo."' AND Item='".$_SESSION['INGRESO']['item']."' AND A_No='".$po."' AND CodigoU='".$_SESSION['INGRESO']['CodigoU']."'";
		// print_r($sql);die();
		$stmt = sqlsrv_query($cid, $sql);
	    if( $stmt === false)  
	      {  
		     echo "Error en consulta PA.\n";  
		     return -1;
		     die( print_r( sqlsrv_errors(), true));  
	      }
	      else{
	      	return 1;
	      }      

	}

	 function eliminar_aiseto_K($fecha=false)
	{
		 // $cid=$this->conn;
		$sql = "DELETE Asiento_K WHERE Item='".$_SESSION['INGRESO']['item']."' AND CodigoU='".$_SESSION['INGRESO']['CodigoU']."'";
		if($fecha)
		{
			$sql.=" AND Fecha_Fab = '".$fecha."'";
		}
		// print_r($sql);die();

		return $this->db->String_Sql($sql);

	}

	function cargar_datos_cuenta_datos($reporte_Excel=false)
	{
		$cid = $this->conn;
		  $sql = "SELECT Fecha_Fab as 'FECHA',CODIGO_INV AS 'CODIGO',PRODUCTO,CANT_ES AS 'CANT',C.Cuenta as 'Centro de costos',CS.Detalle as 'rubro',Consumos as 'bajas o desper',CS2.Detalle AS 'Baja por',Procedencia as 'Observaciones' FROM Asiento_K A 
			LEFT JOIN Catalogo_Cuentas C ON A.CTA_INVENTARIO = C.Codigo 
			LEFT JOIN Catalogo_SubCtas CS on A.SUBCTA = CS.Codigo 
			LEFT JOIN Catalogo_SubCtas CS2 on A.Codigo_Dr = CS2.Codigo  
			WHERE  CodigoU = '".$_SESSION['INGRESO']['Id']."' AND A.Item = '".$_SESSION['INGRESO']['item']."'
			AND C.Item = A.Item
			AND CS.Item = A.Item ";
		$datos =  $this->db->datos($sql);
       	return $datos;

	}

  function datos_asiento_debe($orden)
	{
    	// 'LISTA DE CODIGO DE ANEXOS
 	    $sql = "SELECT SUM(VALOR_TOTAL) as 'total',Contra_Cta as 'cuenta',Fecha as 'fecha',TC 
     	FROM Trans_Kardex  
     	WHERE Item = '".$_SESSION['INGRESO']['item']."' 
     	AND  Periodo = '".$_SESSION['INGRESO']['periodo']."' 
     	AND Orden_No = '".$orden."' 
     	AND TC = 'GC'
		GROUP BY Orden_No,Contra_Cta,Fecha,TC";
          // print_r($sql);die();
	   $datos =  $this->db->datos($sql);
       return $datos;
	}

	function datos_asiento_haber($orden)
	{
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT SUM(VALOR_TOTAL) as 'total',Cta_Inv as 'cuenta',Fecha as 'fecha',TC 
             FROM Trans_Kardex  
             WHERE Item = '".$_SESSION['INGRESO']['item']."' 
     	   AND  Periodo = '".$_SESSION['INGRESO']['periodo']."' 
             AND Orden_No = '".$orden."'  
             AND TC = 'GC'
             GROUP BY Orden_No,Cta_Inv,Fecha,TC";
          // print_r($sql);die();

	   $datos =  $this->db->datos($sql);
       return $datos;
	}

	

	function cuenta_existente()
	{
		 // $cid = $this->conn;
		 $sql="SELECT CP.Codigo as 'Codigo'  FROM Ctas_Proceso CP WHERE CP.Item = '".$_SESSION['INGRESO']['item']."' AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."' AND CP.Detalle = 'Cta_Desperdicio'";
		 // print_r($sql);die();
	   $datos =  $this->db->datos($sql);
	   if (count($datos)>0) {
	   	   $sql1="SELECT Codigo FROM Catalogo_Cuentas WHERE Codigo = '".$datos[0]['Codigo']."' AND  Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND DG = 'D' ";

        // print_r($sql1);die();

	   $datos1 =  $this->db->datos($sql1);
	   if(count($datos1)>0)	   {

	     	$_SESSION['INGRESO']['CTA_DESPERDICIO'] = $datos1[0]['Codigo'];
	   }else
	   {
	   	 return -2;
	   }

	   }else
	   {
	   	 return -1;
	   }

	}

	function catalogo_cuentas($cuenta)
	{

		 $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT * FROM Catalogo_Cuentas  WHERE Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Codigo = '".$cuenta."'";
          // print_r($sql);die();

	   $datos =  $this->db->datos($sql);
       return $datos;
	}
	function catalogo_subcuentas($cuenta)
	{

		 $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT * FROM Catalogo_SubCtas   WHERE Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Codigo = '".$cuenta."'";
          // print_r($sql);die();
	   $datos =  $this->db->datos($sql);
       return $datos;
	}

	function datos_comprobante($t_no=99)
	{
		$cid = $this->conn;
		$sql="SELECT * FROM Asiento WHERE CodigoU='".$_SESSION['INGRESO']['CodigoU']."' AND Item='".$_SESSION['INGRESO']['item']."' AND T_No = '".$t_no."'";
	   $datos =  $this->db->datos($sql);
       return $datos;
	}
	
	function stock_kardex($id)
	{
		// $cid = $this->conn;
		$sql="SELECT SUM(Entrada-Salida) as 'stock' 
		FROM Trans_Kardex 
		WHERE Item = '".$_SESSION['INGRESO']['item']."' 
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND  Fecha <= '".date('Y-m-d')."' 
		AND Codigo_Inv ='".$id."' 
		AND T <> 'A' ";
        $datos =  $this->db->datos($sql);
	
       return $datos;

	}
	function costo_venta($codigo_inv)
	{
		$cid = $this->conn;
		$sql = "SELECT TOP 1 Codigo_Inv,Costo 
		FROM Trans_Kardex
		WHERE Fecha <= '".date('Y-m-d')."'
		AND Codigo_Inv = '".$codigo_inv."'
		AND Item = '".$_SESSION['INGRESO']['item']."'
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND T <> 'A'
		ORDER BY Fecha DESC,TP DESC, Numero DESC,ID DESC";
		// $stmt = sqlsrv_query($cid, $sql);
        $datos =  $this->db->datos($sql);
	
       return $datos;

	}
	function delete_SC_ASientos(){
		$sql = "DELETE Asiento_SC WHERE  Item='".$_SESSION['INGRESO']['item']."' AND T_No='60' AND CodigoU='".$_SESSION['INGRESO']['CodigoU']."';";
		$sql.= "DELETE Asiento WHERE  Item='".$_SESSION['INGRESO']['item']."' AND T_No='60' AND CodigoU='".$_SESSION['INGRESO']['CodigoU']."';";
		//print_r($sql);die();

		return $this->db->String_Sql($sql);
	

	}

	function codmarca()
	{
		$sql = "SELECT   CodMar, Marca, Item, Periodo, X, ID
		FROM Catalogo_Marcas
		WHERE   Item = '".$_SESSION['INGRESO']['item']."' 
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND LEN(CodMar) = 2
		ORDER BY Marca ";
		return $this->db->datos($sql);

	}

	function proyectos()
	{
		$sql = "SELECT Codigo, Cuenta 
			 FROM Catalogo_Cuentas 
			 WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			 AND Item = '".$_SESSION['INGRESO']['item']."' 
			 AND TC = 'CC' 
			 AND DG = 'G' 
			 ORDER BY Codigo ";

		// $sql="SELECT CP.Codigo,CC.Cuenta 
		// 	FROM Catalogo_Cuentas as CC, Ctas_Proceso As CP
		// 	WHERE CC.DG='G' 
		// 	AND CC.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		// 	AND CC.Item = '".$_SESSION['INGRESO']['item']."' 
		// 	AND CP.Detalle like 'Cta_Proyecto:%'
		// 	AND CC.Periodo = CP.Periodo
		// 	AND CC.Item = CP.Item
		// 	AND CC.Codigo = CP.Codigo
		// 	ORDER BY CP.Codigo ";
			return $this->db->datos($sql);

	}

	function actualizar_trans_presupuesto($parametros)
	{
		$sql="SELECT *
		FROM Trans_Presupuestos
		WHERE Cta = '".$parametros['cc']."' AND Codigo_Inv = '".$parametros['codigo']."'";
		return $this->db->datos($sql);
	}

	function buscar_asientoK($codigo,$pos)
	{
		$sql="SELECT * 
		FROM Asiento_K 
		WHERE CODIGO_INV = '".$codigo."' 
		AND A_No = '".$pos."'
		AND Item = '".$_SESSION['INGRESO']['item']."'
		AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'";
		return $this->db->datos($sql);

	}

	function pedidos_contratista($query=false,$fecha=false)
	{
		$sql = "select TK.Fecha,Orden_No,Cliente,Codigo,TC
				From Trans_Kardex TK
				INNER JOIN Clientes C on TK.CodigoU = C.Codigo
				where TK.T = 'S' 
				AND Numero = 0  
				AND (TK.TC = '.' OR TK.TC='GC')";
				if($query)
				{
					$sql.=" AND C.Cliente like '%".$query."%' ";
				}
				if($fecha)
				{
					$sql.=" AND TK.Fecha = '".$fecha."'";
				}
			$sql.= " GROUP BY TK.Fecha,Orden_No,Cliente,Codigo,TC";
			// print_r($sql);die();
		return $this->db->datos($sql);
	}

	function pedidos_contratista_check($query=false,$fecha=false)
	{
		$sql = "select TK.Fecha,Orden_No,Cliente,Codigo,TC
				From Trans_Kardex TK
				INNER JOIN Clientes C on TK.CodigoU = C.Codigo
				where TK.T = 'S' 
				AND Numero = 0 
				AND TK.TC='K'";
				if($query)
				{
					$sql.=" AND C.Cliente like '%".$query."%' ";
				}
				if($fecha)
				{
					$sql.=" AND TK.Fecha = '".$fecha."'";
				}
			$sql.= " GROUP BY TK.Fecha,Orden_No,Cliente,Codigo,TC";
			// print_r($sql);die();
		return $this->db->datos($sql);
	}

	function eliminarAsientoK($T,$orden,$codigo_inv)
	{
		$sql = "DELETE FROM Asiento_k 
			   WHERE TC = '".$T."'
			   AND ORDEN = '".$orden."'
			   AND Item = '".$_SESSION['INGRESO']['item']."'
			   AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
			   AND Codigo_Inv = '".$codigo_inv."'";

			   // print_r($sql);die();

		return $this->db->String_Sql($sql);
	}

	function pedidos_contratista_comprobante($orden=false,$fecha=false)
	{
		$sql = "SELECT AK.Codigo_Inv,Producto,Salida,Contra_Cta,CodigoL,AK.ID,Cliente,C.CI_RUC,Orden_No,AK.TC,CC.Cuenta,SC.Detalle
	     	FROM Trans_Kardex AK
			INNER JOIN Catalogo_Cuentas  CC ON AK.CONTRA_CTA = CC.Codigo
			inneR JOIN Catalogo_Productos CP ON AK.Codigo_Inv = CP.Codigo_Inv
			INNER JOIN Clientes C ON AK.CodigoU = C.Codigo
			INNER JOIN Catalogo_SubCtas  SC ON AK.CodigoL = SC.Codigo
			AND AK.Item = '".$_SESSION['INGRESO']['item']."' 
			AND CC.Periodo = '".$_SESSION['INGRESO']['periodo']."'
			AND AK.Item = CC.Item
			AND AK.Item = CP.Item
			AND AK.Periodo = CP.Periodo
			AND AK.Item = SC.Item
			AND AK.Periodo = SC.Periodo
			AND AK.T = 'S'
			AND AK.TC = 'GC'";
	         if($fecha)
	         {
	         	$sql .= " AND AK.Fecha='".$fecha."'";
	         }
	         if($orden)
	         {
	         	$sql.= " AND Orden_No='".$orden."'";
	         }

			// print_r($sql);die();
		return $this->db->datos($sql);
	}

	function datos_asiento_SC($orden)
	{
		$sql="SELECT SUM(VALOR_TOTAL) as 'total',CONTRA_CTA,Fecha_Fab,TC,CodigoL,Fecha FROM Trans_Kardex  
				WHERE Item ='".$_SESSION['INGRESO']['item']."' 
				AND Periodo ='".$_SESSION['INGRESO']['periodo']."' 
				AND TC = 'GC'
				AND Orden_No = '".$orden."'
				GROUP BY CONTRA_CTA,Fecha_Fab,TC,CodigoL,Fecha";		
		return $this->db->datos($sql);
	}

	function eliminar_linea($id)
	{
		$sql = "DELETE FROM Trans_Kardex WHERE ID = '".$id."'";
		return $this->db->String_Sql($sql);
	}


}
?>