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
class farmacia_internaM
{
	
	private $conn ;
	function __construct()
	{
	   $this->conn = new db();
	}

	function tabla_ingresos($query=false,$comprobante=false,$factura=false,$serie=false)
	{
		$sql="SELECT Fecha_DUI as 'Fecha',Cliente as 'Proveedor',Factura,Serie_No,Numero as 'Comprobante',SUM(Valor_Total) as Total
		FROM Trans_Kardex T
		RIGHT JOIN Clientes C ON T.CodigoL=C.Codigo
		WHERE Item = '".$_SESSION['INGRESO']['item']."' 
		AND Periodo  ='".$_SESSION['INGRESO']['periodo']."' 
		AND Entrada <> 0";
		if($query)
		{
			$sql.=" AND CodigoL='".$query."'";
		}
		if($comprobante)
		{
			$sql.=" AND Numero like '%".$comprobante."%'";
		}
		if($factura)
		{
			$sql.=" AND Factura like '%".$factura."%'";
		}
		if($serie)
		{
			$sql.=" AND Serie_No like '%".$serie."%'";
		}
		$sql.="GROUP BY Numero,Codigo_P,Factura,Serie_No,Fecha_DUI,Cliente
		ORDER BY Fecha_DUI DESC";
		// $sql.=' OFFSET 0 ROWS FETCH NEXT 100 ROWS ONLY;';

		// $reg = array(0,100);
		// print_r($sql);die();

	    $botones[0] = array('boton'=>'Ver detalle', 'icono'=>'<i class="fa fa-eye"></i>', 'tipo'=>'default', 'id'=>'1,Factura,Comprobante' );
		$tbl = grilla_generica_new($sql,'Trans_Kardex T ',$id_tabla='tbl_ingresos',null,$botones,false,false,1,1,1,500,2,false);
		// print_r($tbl);die();
		$datos = $this->conn->datos($sql);
		return array('tbl'=>$tbl,'datos'=>$datos);
	}


	function tabla_catalogo($query,$tipo)
	{
		// print_r($query);
		// print_r($tipo);die();
		$sql = "SELECT Codigo_Inv as 'Codigo',Producto,Valor_Total,Stock_Actual as 'Cantidad' 
		FROM Catalogo_Productos 
		WHERE INV = 1
		AND T<>'I'  
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND Item = '".$_SESSION['INGRESO']['item']."' 
		AND LEN(Cta_Inventario)>3 AND LEN(Cta_Costo_Venta)>3 AND ";
		if($tipo =='desc')
		{
		 $sql.="Producto LIKE '%".$query."%'";
		}else
		{
			$sql.=" Codigo_Inv LIKE '%".$query."%'";
		}
		$sql.=' ORDER BY Producto';
		$sql.=" OFFSET 0 ROWS FETCH NEXT 100 ROWS ONLY;";
// 
		// print_r($sql);die();

        $tbl = grilla_generica_new($sql,'Catalogo_Productos',$id_tabla='tbl_pro',null,$botones=false,false,false,1,1,1,500,2,false);
		// print_r($tbl);die();
		$datos = $this->conn->datos($sql);
		return array('tbl'=>$tbl,'datos'=>$datos);
	}


	function tabla_catalogo_bodega($query,$tipo,$familia=false,$ubicacion=false,$limite = true)
	{
		// print_r($query);
		// print_r($tipo);die();
		$sql = "SELECT CP.ID,Codigo_Inv as 'Codigo',Producto,Valor_Total,Stock_Actual as 'Cantidad',CC.Cuenta,Valor_Unit,Ubicacion
		FROM Catalogo_Productos CP
		INNER JOIN Catalogo_Cuentas CC ON CC.Codigo = CP.Cta_Inventario
		WHERE INV = 1
		AND T<>'I'
		AND CP.Periodo = CC.Periodo
		AND CP.Item =CC.Item
		AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND CP.Item = '".$_SESSION['INGRESO']['item']."'
		AND LEN(Cta_Inventario)>3 AND LEN(Cta_Costo_Venta)>3 ";
		if($tipo =='desc')
		{
		 $sql.=" AND Producto LIKE '%".$query."%'";
		}else
		{
			if($query!='')
			{
			 $sql.=" AND Codigo_Inv LIKE '%".$query."%'";
			}
		}
		if($familia)
		{
			$sql.=" AND Codigo_Inv LIKE '".$familia."%' ";
		}

		if($ubicacion)
		{
			$sql.=" AND Ubicacion LIKE '%".$ubicacion."%' ";
		}

		$sql.=' ORDER BY Producto';
		if($limite)
		{
		  $sql.=" OFFSET 0 ROWS FETCH NEXT 100 ROWS ONLY;";
		}

		// print_r($sql);die();
// 
		$datos = $this->conn->datos($sql);
		return $datos;
	}


	function pedido_paciente($nombre=false,$ci=false,$historia=false,$departamento=false,$procedimiento=false,$desde=false,$hasta =false,$busfe=false)
	{
		$sql = "SELECT TK.Fecha as 'Fecha',C.Cliente as 'Paciente',CI_RUC AS 'Cedula',C.Matricula as 'Historia',CS.Detalle as 'Departamento',SUM(VALOR_TOTAL) as 'importe',TK.Detalle as 'Procedimiento',Orden_No,Numero as 'comprobante'
			FROM Trans_Kardex TK
			LEFT JOIN Clientes C ON C.CI_RUC = TK.Codigo_P 
			LEFT JOIN Catalogo_SubCtas CS ON CS.Codigo = TK.CodigoL
			WHERE Orden_No <>'.'
			AND Salida <>0
      		AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."'
			AND TK.Item = '".$_SESSION['INGRESO']['item']."'
			AND CS.Item = TK.Item
			AND CS.Periodo = TK.Periodo";
		if($historia)
		{
			$sql.=" AND Matricula like '".$historia."%' ";
		}
		if($ci!='')
		{
			$sql.=" AND Codigo_P LIKE '".$ci."%' ";
		}
		if ($nombre!='') 
		{
			$sql.=" AND Cliente LIKE '%".$nombre."%'";
		}

		if($departamento)
		{		
		  $sql.=" AND CS.Detalle like '".$departamento."%'";
		}
		if($procedimiento)
		{		
		  $sql.=" AND TK.Detalle like '%".$procedimiento."%'";
		}
		
		if($busfe=='true')
		{		
			  $sql.=" AND TK.Fecha BETWEEN '".$desde."' and '".$hasta."'";
		}

		$sql.=" GROUP BY Orden_No ,CI_RUC,TK.Fecha,C.Cliente,TK.CodigoL,CS.Detalle,C.Matricula,TK.Detalle,Matricula,Numero ORDER BY TK.Fecha DESC";
		// $sql.=" OFFSET 0 ROWS FETCH NEXT 100 ROWS ONLY;";
		
		// print_r($sql);die();
		 $botones[0] = array('boton'=>'Ver pedido', 'icono'=>'<i class="fa fa-eye"></i>', 'tipo'=>'default', 'id'=>'4,Orden_No,comprobante' );
		$tbl = grilla_generica_new($sql,' Asiento_K A','tbl_pedi',false,$botones,$check=false,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,500);
		$datos = $this->conn->datos($sql);
       return array('tbl'=>$tbl,'datos'=>$datos) ;
	}




	function descargos_medicamentos($query=false,$paciente=false,$ci=false,$departamento=false,$desde=false,$hasta=false,$tipo=false)
	{
		// print_r($tipo);die();
		$sql = "SELECT T.Fecha,CP.Producto,Cliente,CI_RUC as 'Cedula',C.Matricula,Centro_Costo as 'Departamento',Salida as 'Cantidad'
		FROM Trans_Kardex T
		INNER JOIN Catalogo_Productos CP ON T.Codigo_Inv = CP.Codigo_Inv
		INNER JOIN Clientes C ON T.Codigo_P = C.Codigo  
		WHERE T.Item = ".$_SESSION['INGRESO']['item']." 
		AND T.Periodo  ='".$_SESSION['INGRESO']['periodo']."' 
		AND Entrada = 0 
		AND Matricula <> 0 
		AND Numero <> 0 ";
		if($query)
		{
			$sql.=" AND CP.Producto like '%".$query."%'";
		}
		if($paciente)
		{
			$sql.=" AND Cliente like '%".$paciente."%'";
		}

		if($ci)
		{
			$sql.=" AND CI_RUC like '".$ci."%'";
		}
		if($departamento)
		{
			$sql.=" AND Centro_Costo like '%".$departamento."%'";
		}
		if($tipo=='true')
		{
			$sql.=" AND T.Fecha BETWEEN '".$desde."' AND '".$hasta."'";
		}

		$sql.="GROUP BY T.Fecha,CP.Producto,Cliente,CI_RUC,C.Matricula,Centro_Costo,Numero,Salida
		ORDER BY T.Fecha DESC ";
		 $sql.=" OFFSET 0 ROWS FETCH NEXT 100 ROWS ONLY;";
		 // print_r($sql);die();
		$tbl = grilla_generica_new($sql,' Trans_Kardex T','tbl_medi',false,$botones=false,$check=false,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,500);
		$datos = $this->conn->datos($sql);
		 // print_r($datos);die();
       return array('tbl'=>$tbl,'datos'=>$datos) ;
	}

	function costo_producto($Codigo_Inv)
	{

		$cid = $this->conn;
		$sql = "SELECT TOP 1 Codigo_Inv,Costo,Valor_Unitario,Existencia,Total,T,Fecha  
               FROM Trans_Kardex 
               WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
               AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
               AND Fecha <= '".date('Y-m-d')."' 
               AND Codigo_Inv = '".$Codigo_Inv."' 
               AND T <> 'A' 
               ORDER BY Fecha DESC,TP DESC, Numero DESC,ID DESC ";
               // print_r($sql);die();
       		$datos = $this->conn->datos($sql);
       return $datos;

	}

	function costo_producto_comprobante($Codigo_Inv)
	{

		$cid = $this->conn;
		$sql="SELECT  (SUM(Entrada)-SUM(Salida)) as 'Existencia'
			FROM Trans_Kardex
			WHERE Fecha <= '".date('Y-m-d')."' 
			AND Codigo_Inv = '".$Codigo_Inv."' 
			AND Item =  '".$_SESSION['INGRESO']['item']. "' 
			AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			AND T <> 'A'
	        AND Numero <> 0
			AND CodBodega ='01' ";

			
		// $sql = "SELECT TOP 1 Codigo_Inv,Costo,Valor_Unitario,Existencia,Total,T,Fecha  
  //              FROM Trans_Kardex 
  //              WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
  //              AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
  //              AND Fecha <= '".date('Y-m-d')."' 
  //              AND Codigo_Inv = '".$Codigo_Inv."' 
  //              AND T <> 'A' 
  //              AND Numero <> 0 
  //              ORDER BY Fecha DESC,TP DESC, Numero DESC,ID DESC ";
               // print_r($sql);die();
       		$datos = $this->conn->datos($sql);
       return $datos;

	}


	function fecha_cantidad_ultimo_ingreso($Codigo_Inv)
	{

		$cid = $this->conn;
		$sql = "SELECT TOP 1 Codigo_Inv,Costo,Valor_Unitario,Existencia,Total,T,Fecha,Entrada  
               FROM Trans_Kardex 
               WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
               AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
               AND Fecha <= '".date('Y-m-d')."' 
               AND Codigo_Inv = '".$Codigo_Inv."' 
               AND T <> 'A' 
               AND Entrada <> 0
               ORDER BY Fecha DESC,TP DESC, Numero DESC,ID DESC ";
               // print_r($sql);die();
       		$datos = $this->conn->datos($sql);
       return $datos;

	}

	function detalle_reportes_ingresos($numero)
	{
		$sql="SELECT TK.Fecha,CP.Producto,Entrada,TK.Valor_Unitario,TK.Valor_Total,TK.CodigoL,Cliente FROM Trans_Kardex  TK
			INNER JOIN Catalogo_Productos CP ON TK.Codigo_Inv = CP.Codigo_Inv 
			INNER JOIN Clientes C ON TK.CodigoL = C.Codigo
			WHERE TK.Item = '".$_SESSION['INGRESO']['item']."' 
			AND TK.Periodo  ='".$_SESSION['INGRESO']['periodo']."' 
			AND Numero = '".$numero."'
			AND TK.Item = CP.Item
			AND TK.Periodo = CP.Periodo";

			// print_r($sql);die();
			$datos = $this->conn->datos($sql);
       return $datos;
	}


	function detalle_reportes_descargos($numero=false,$factura=false)
	{
		$sql="SELECT TK.Fecha,CP.Producto,Salida,TK.Valor_Unitario,TK.Valor_Total,TK.Codigo_P,Cliente 
			FROM Trans_Kardex  TK
			INNER JOIN Catalogo_Productos CP ON TK.Codigo_Inv = CP.Codigo_Inv 
			INNER JOIN Clientes C ON TK.Codigo_P = C.Codigo
			WHERE TK.Item = '".$_SESSION['INGRESO']['item']."' 
			AND TK.Periodo  ='".$_SESSION['INGRESO']['periodo']."' 
			AND TK.Item = CP.Item
			AND TK.Periodo = CP.Periodo";
			if($numero)
			{
				$sql.=" AND Numero = '".$numero."'";
			}
			if($factura)
			{
				$sql.=" AND Orden_No ='".$factura."' AND Numero =0 ";
			}

			// print_r($sql);die();
			$datos = $this->conn->datos($sql);
       return $datos;
	}


}

?>