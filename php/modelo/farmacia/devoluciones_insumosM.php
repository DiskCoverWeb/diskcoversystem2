<?php 
// include(dirname(__DIR__,2).'/db/db1.php');//
if(!class_exists('variables_g'))
{
	include(dirname(__DIR__,2).'/db/variables_globales.php');//
    include(dirname(__DIR__,2).'/funciones/funciones.php');
}
@session_start(); 

/**
 * 
 */
class devoluciones_insumosM
{
	
	private $conn ;
	function __construct()
	{
	   $this->conn = new db();
	}

	function cargar_comprobantes($query=false,$desde=false,$hasta=false,$tipo='',$paginacion=false)
	{
		$sql="SELECT Numero,CP.Fecha,Concepto,Monto_Total,Cliente FROM Comprobantes CP 
		LEFT JOIN Clientes C ON CP.Codigo_B = C.Codigo
		WHERE TP='CD' 
		AND CP.T='N' 
		AND Item = '".$_SESSION['INGRESO']['item']."' 
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND Codigo_B <> '.' 
		AND Numero IN ( SELECT  DISTINCT Numero 
						FROM Trans_Kardex WHERE
						Item = '".$_SESSION['INGRESO']['item']."' 
						AND Periodo = '".$_SESSION['INGRESO']['periodo']."'  
						AND Entrada = 0 
					  )";
		if($tipo =='f')
		{
			$sql.= " AND CP.Fecha BETWEEN '".$desde."' AND '".$hasta."'";
		}
		if($query)
		{
			$sql.=" AND C.Cliente like '%".$query."%'";
		}

		$sql.=" ORDER BY CP.ID ";

		// print_r($sql);die();
		$num_reg = array('0','100','cargar_pedidos()');
	    $botones[0] = array('boton'=>'Ver detalle','icono'=>'<i class="fa fa-reorder"></i>', 'tipo'=>'primary', 'id'=>'Numero');
	    $datos = grilla_generica_new($sql,'Comprobantes CP',$id_tabla=false,false,$botones,false,$imagen=false,1,1,1,300,2,$num_reg=false,false);
	 

     
        return $datos;

	}

	function cargar_comprobantes_datos($query=false,$desde='',$hasta='',$tipo='',$numero=false)
	{
		

		$sql = "SELECT DISTINCT CP.Numero,CP.Fecha,Concepto,Monto_Total,Cliente,TK.CodigoL FROM Comprobantes CP 
		LEFT JOIN Clientes C ON CP.Codigo_B = C.Codigo
		LEFT JOIN Trans_Kardex TK ON CP.Numero = TK.Numero
		WHERE CP.TP='CD' 
		AND CP.T='N' 
		AND CP.Item = '".$_SESSION['INGRESO']['item']."'  
		AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."'  
		AND Codigo_B <> '.' 
		AND CP.Numero IN ( SELECT  DISTINCT Numero FROM Trans_Kardex T WHERE Item = '".$_SESSION['INGRESO']['item']."' AND  Periodo = '".$_SESSION['INGRESO']['periodo']."'    AND Entrada = 0 )";
		if($tipo =='f')
		{
			$sql.= " AND CP.Fecha BETWEEN '".$desde."' AND '".$hasta."'";
		}
		if($query)
		{
			$sql.=" AND C.Cliente like '%".$query."%'";
		}
		if($numero)
		{
			$sql.="AND CP.Numero ='".$numero."'";
		}

		// print_r($sql);die();
		// " AND CP.CodigoU = '".$_SESSION['INGRESO']['CodigoU']."';";

		 return $this->conn->datos($sql);

	}

	function trans_kardex($numero)
	{

		$sql="SELECT  Codigo_Inv,Salida,Valor_Unitario,Valor_Total  FROM Trans_Kardex WHERE Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Entrada = 0 AND Numero = '".$numero."' ";
		$datos = $this->conn->datos($sql);
        return $datos;

	}

	function trans_kardex_linea_all($id)
	{

		$sql="SELECT *  FROM Trans_Kardex WHERE Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Entrada = 0 AND ID = '".$id."' ";
		$datos = $this->conn->datos($sql);
        return $datos;

	}

	function trans_kardex_linea_devolucion($Codigo_Inv,$factura)
	{

		$sql="SELECT *  FROM Trans_Kardex WHERE Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND  Factura = '".$factura."' and Codigo_Inv = '".$Codigo_Inv."' ";
		// print_r($sql);die();
		$datos = $this->conn->datos($sql);
        return $datos;

	}


	function producto($Codigo_Inv)
	{

        $sql="SELECT Producto FROM Catalogo_Productos WHERE Codigo_Inv = '".$Codigo_Inv."' AND Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
	    $datos = $this->conn->datos($sql);
        return $datos;
	}

	function producto_all_detalle($Codigo_Inv)
	{

        $sql="SELECT * FROM Catalogo_Productos WHERE Codigo_Inv = '".$Codigo_Inv."' AND Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
	    $datos = $this->conn->datos($sql);
        return $datos;
	}
	function imprimir_excel($stmt1)
	{		
	     exportar_excel_comp($stmt1,null,null,1);
	}

	function lineas_trans_kardex($numero,$query=false)
	{

		$sql="SELECT  T.Codigo_Inv,Salida,Valor_Unitario,T.Valor_Total,C.Producto,T.ID,T.Utilidad,C.Utilidad as 'utilidad_C'  
		FROM Trans_Kardex T
       INNER JOIN Catalogo_Productos C ON T.Codigo_Inv = C.Codigo_Inv 
		WHERE C.Item = '".$_SESSION['INGRESO']['item']."' 
		AND C.Periodo ='".$_SESSION['INGRESO']['periodo']."' 
		AND Entrada = 0 
		AND Numero = '".$numero."' ";
		if($query)
		{
			$sql.=" AND T.Codigo_Inv+''+C.Producto like '%".$query."%' ";
		}
		$sql.=" ORDER BY C.Producto ";
		// print_r($sql);die();
		$datos = $this->conn->datos($sql);
        return $datos;
	}

	function familias($numero)
	{
		$sql ="SELECT DISTINCT SUBSTRING(Codigo_Inv,0,6) as 'familia'
		      FROM Trans_Kardex 
		      WHERE Item = '".$_SESSION['INGRESO']['item']."' 
		      AND Periodo ='".$_SESSION['INGRESO']['periodo']."' 
		      AND Entrada = 0 
		      AND Numero = '".$numero."' ";
		      $datos = $this->conn->datos($sql);
        return $datos;
	}

	function lista_devoluciones($comprobante)
	{
	  $sql = "SELECT CODIGO_INV as 'CODIGO PRODUCTO',PRODUCTO,CANTIDAD,VALOR_UNIT AS 'VALOR UNITARIO',VALOR_TOTAL AS 'VALOR TOTAL',Fecha_Fab AS 'FECHA',A_No FROM Asiento_K WHERE DH = '1' AND ORDEN = '".$comprobante."'";
	  $datos = $this->conn->datos($sql);
	  $botones[0] = array('boton'=>'Eliminar','icono'=>'<i class="fa fa-trash"></i>', 'tipo'=>'danger', 'id'=>$comprobante.',CODIGO PRODUCTO,A_No');

	  $tbl = grilla_generica_new($sql,'Asiento_K ','tbl_style',false,$botones,false,$imagen=false,1,1,1,300,2,$num_reg=false,false,false);
      return array('datos'=>$datos,'tabla'=>$tbl);

	}

	function lista_devoluciones_x_departamento($comprobante)
	{
	  $sql = "SELECT CODIGO_INV as 'CODIGO PRODUCTO',PRODUCTO,CANTIDAD,VALOR_UNIT AS 'VALOR UNITARIO',VALOR_TOTAL AS 'VALOR TOTAL',Fecha_Fab AS 'FECHA',SC.Detalle AS 'Area',A_No,ORDEN  
	  FROM Asiento_K K
	  INNER JOIN Catalogo_SubCtas SC ON K.SUBCTA = SC.Codigo   
	  WHERE DH = '1' 
	  AND ORDEN = '".$comprobante."'
	  AND CodigoU =".$_SESSION['INGRESO']['CodigoU']."
	  AND K.Item = '".$_SESSION['INGRESO']['item']."'
	  AND SC.Item = K.Item
	  AND SC.Periodo = '".$_SESSION['INGRESO']['periodo']."'";
	  // print_r($sql);die();
	  $datos = $this->conn->datos($sql);
	  $botones[0] = array('boton'=>'Eliminar','icono'=>'<i class="fa fa-trash"></i>', 'tipo'=>'danger', 'id'=>$comprobante.',CODIGO PRODUCTO,A_No');

	  $tbl = grilla_generica_new($sql,'Asiento_K ','tbl_style',false,$botones,false,$imagen=false,1,1,1,300,2,$num_reg=false,false,false);
      return array('datos'=>$datos,'tabla'=>$tbl);

	}

	function eliminar_linea_dev($codigo,$comprobante)
	{
		$sql = "DELETE FROM Asiento_K WHERE CODIGO_INV = '".$codigo."'  AND ORDEN = '".$comprobante."'";
		return  $this->conn->String_Sql($sql);

	}
	function eliminar_linea_dev_dep($codigo,$comprobante,$No)
	{
		$sql = "DELETE FROM Asiento_K WHERE CODIGO_INV = '".$codigo."'  AND ORDEN = '".$comprobante."' AND A_No=".$No;
		// print_r($sql);die();
		return  $this->conn->String_Sql($sql);

	}

	function ingresar_asiento_K($datos,$campoWhere=false)
	{
		// print_r($datos);die();
		if ($campoWhere) {
			$resp = update_generico($datos,'Asiento_K',$campoWhere);			
		  return $resp;
			
		}
	}
}

?>