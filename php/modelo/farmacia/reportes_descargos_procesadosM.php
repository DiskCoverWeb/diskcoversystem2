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
class reportes_descargos_procesadosM
{
	
	private $conn ;
	function __construct()
	{
	   $this->conn = new db();
	}


	function cargar_comprobantes($query=false,$desde=false,$hasta=false,$tipo='',$paginacion=false,$area=false)
	{
		$sql="SELECT TOP 100 CP.Numero,CP.Fecha,Concepto,Monto_Total,Cliente,TK.Centro_Costo as 'Area' FROM Comprobantes CP 
		LEFT JOIN Clientes C ON CP.Codigo_B = C.Codigo
		LEFT JOIN Trans_Kardex TK ON CP.Numero = TK.Numero 
		WHERE CP.TP='CD' 
		AND CP.T='N' 
		AND CP.Item = '".$_SESSION['INGRESO']['item']."' 
		AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND Codigo_B <> '.' 
		AND CP.Item = TK.Item 
		AND CP.Periodo = TK.Periodo 
		AND CP.Numero IN ( SELECT  DISTINCT Numero FROM Trans_Kardex WHERE Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."'  AND Entrada = 0 )";
		if($area)
		{
			$sql.="	AND TK.CodigoL = '".$area."' ";
		}
		if($tipo =='f')
		{
			$sql.= " AND CP.Fecha BETWEEN '".$desde."' AND '".$hasta."'";
		}
		if($query)
		{
			if(is_numeric($query))
			{
				$sql.=" AND CP.Numero like '".$query."%'";
			}else{
				$sql.=" AND C.Cliente like '".$query."%'";
			}
		}
		// " AND CP.CodigoU = '".$_SESSION['INGRESO']['CodigoU']."';";

		$sql.=" GROUP BY CP.Numero,CP.Fecha,Concepto,Monto_Total,Cliente,TK.Centro_Costo ";

		// print_r($sql);die();
	    $botones[0] = array('boton'=>'Ver detalle','icono'=>'<i class="fa fa-reorder"></i>', 'tipo'=>'primary', 'id'=>'Numero');
	    $botones[1] = array('boton'=>'Ver Comprobante','icono'=>'<i class="fa fa-eye"></i>', 'tipo'=>'default', 'id'=>'Numero');
		//$num_reg = array('0','100','cargar_pedidos()');
	    $datos = grilla_generica_new($sql,'Comprobantes CP',$id_tabla=false,false,$botones,false,$imagen=false,1,1,1,300,2,$num_reg=false,false);
      
        return $datos;

	}

	function cargar_comprobantes_datos($query=false,$desde='',$hasta='',$tipo='',$numero=false)
	{
		$sql="SELECT CP.Numero,CP.Fecha,Concepto,Monto_Total,Cliente,Codigo_B,TK.Centro_Costo 
		FROM Comprobantes CP 
		LEFT JOIN Clientes C ON CP.Codigo_B = C.Codigo
		LEFT JOIN Trans_Kardex TK ON CP.Numero = TK.Numero 
		WHERE CP.TP='CD' 
		AND CP.T='N' 
		AND CP.Item = '".$_SESSION['INGRESO']['item']."' 
		AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND Codigo_B <> '.' 
		AND CP.Item = TK.Item 
		AND CP.Periodo = TK.Periodo 
		AND CP.Numero IN ( SELECT  DISTINCT Numero FROM Trans_Kardex WHERE Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."'  AND Entrada = 0 )";

		if($tipo =='f')
		{
			$sql.= " AND CP.Fecha BETWEEN '".$desde."' AND '".$hasta."'";
		}
		if($query)
		{
			if(is_numeric($query))
			{
				$sql.=" AND CP.Numero = '".$query."'";
			}else
			{
				$sql.=" AND C.Cliente like '".$query."%'";				
			}
		}
		if($numero)
		{
			$sql.="AND CP.Numero ='".$numero."'";
		}
		// " AND CP.CodigoU = '".$_SESSION['INGRESO']['CodigoU']."';";
		$sql.="GROUP BY CP.Numero,CP.Fecha,Concepto,Monto_Total,Cliente,Codigo_B,TK.Centro_Costo  ";

		// print_r($sql);die();

		 return $this->conn->datos($sql);

	}


	function trans_kardex_linea_devolucion($Codigo_Inv,$factura)
	{

		$sql="SELECT *  FROM Trans_Kardex WHERE Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND  Factura = '".$factura."' and Codigo_Inv = '".$Codigo_Inv."' ";
		// print_r($sql);die();
		$datos = $this->conn->datos($sql);
        return $datos;

	}
	

	function trans_kardex($numero)
	{

		$sql="SELECT T.Codigo_Inv,Salida,Valor_Unitario,T.Valor_Total,C.Producto
			FROM Trans_Kardex T, Catalogo_Productos C 
			WHERE T.Item = '".$_SESSION['INGRESO']['item']."'
			AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."'
			AND T.Periodo = C.Periodo
			AND T.Codigo_Inv = C.Codigo_Inv
			AND Entrada = 0 AND Numero = '".$numero."' ";

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
	function imprimir_excel($stmt1)
	{		
	     exportar_excel_comp($stmt1,null,null,1);
	}

	function lineas_trans_kardex($numero)
	{

		$sql="SELECT  T.Codigo_Inv,Salida,Valor_Unitario,T.Valor_Total,C.Producto,T.ID,T.Utilidad,C.Utilidad as 'utilidad_C'  
		FROM Trans_Kardex T
       INNER JOIN Catalogo_Productos C ON T.Codigo_Inv = C.Codigo_Inv 
		WHERE C.Item = '".$_SESSION['INGRESO']['item']."' 
		AND C.Periodo ='".$_SESSION['INGRESO']['periodo']."' 
		AND Entrada = 0 
		AND Numero = '".$numero."' ";
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

	function servicios()
	{
		$sql ="SELECT Codigo_Inv,Producto FROM Catalogo_Productos 
			WHERE TC='P' 
			AND Cta_Inventario='0' 
			AND Cta_Costo_Venta = '0'
			AND Cta_Ventas <> '0'
			AND Cta_Ventas_0 <> '0'
			AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
			AND Item = '".$_SESSION['INGRESO']['item']."'";
			$datos = $this->conn->datos($sql);
        return $datos;
	}

}

?>