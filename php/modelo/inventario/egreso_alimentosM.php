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
class egreso_alimentosM
{
	
	private $db ;
	function __construct()
	{
	   $this->db = new db();
	}

	function areas($query)
	{
		$sql = "SELECT   Proceso, Cmds, ID,Picture
				FROM      Catalogo_Proceso
				WHERE  Item = '".$_SESSION['INGRESO']['item']."' 
				AND Nivel = 95
				AND TP = 'AREAEGRE' ";
			if($query)
			{
				$sql.=" AND Proceso like '%".$query."%' ";
			}
				$sql.=" ORDER BY Nivel, Proceso";
		return $this->db->datos($sql);
	}
	function motivo_egreso($query)
	{
		$sql = "SELECT   Proceso, Cmds, ID,Picture
			FROM      Catalogo_Proceso
			WHERE   Item = '".$_SESSION['INGRESO']['item']."' 
			AND Nivel = 94
			AND TP = 'MOTIVOS' ";
			if($query)
			{
				$sql.=" AND Proceso like '%".$query."%' ";
			}
			$sql.=" ORDER BY Nivel, TP, Cmds, Proceso";

		return $this->db->datos($sql);
	}
	function buscar_producto($query=false,$id=false,$grupo=false)
	{
		$sql = "SELECT TK.*,C.Cliente,CP.Producto,CP.Unidad 
			FROM Trans_Kardex TK
			INNER JOIN Catalogo_Productos CP on TK.Codigo_Inv = CP.Codigo_Inv 
			INNER JOIN Clientes C on TK.Codigo_P = C.Codigo
			WHERE TK.Item = '".$_SESSION['INGRESO']['item']."'
			AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."'
			AND TK.Item = CP.Item
			AND TK.Periodo = CP.Periodo
			AND TK.T ='E' ";
			if($query)
			{
				$sql.=" AND TK.Codigo_Barra='".$query."'";
			}
			if($id)
			{
				$sql.=" AND TK.ID='".$id."'";
			}
			if($grupo)
			{
				$sql.=" CP.Codigo_Inv = '".$grupo."'";
			}
		return $this->db->datos($sql);
	}

	function buscar_producto_egreso($query=false,$id=false)
	{
		$sql = "SELECT TK.*,C.Cliente,CP.Producto,CP.Unidad 
			FROM Trans_Kardex TK
			INNER JOIN Catalogo_Productos CP on TK.Codigo_Inv = CP.Codigo_Inv 
			INNER JOIN Clientes C on TK.Codigo_P = C.Codigo
			WHERE TK.Item = '".$_SESSION['INGRESO']['item']."'
			AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."'
			AND TK.CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
			AND TK.Item = CP.Item
			AND TK.Periodo = CP.Periodo
			AND TK.T ='S' ";
			if($query)
			{
				$sql.=" AND TK.Codigo_Barra='".$query."'";
			}
			if($id)
			{
				$sql.=" AND TK.ID='".$id."'";
			}
		return $this->db->datos($sql);
	}

	function areas_checking($query)
	{
		$sql = "SELECT
			CPO.Cmds,
			CPO.Proceso AS Proceso,CPO.Picture
			FROM Trans_Kardex TK
			INNER JOIN Catalogo_Proceso CPO ON TK.Codigo_Tra = CPO.Cmds
			WHERE TK.Item = '".$_SESSION['INGRESO']['item']."'
			AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."'
			AND TK.CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'";
			if($query)
			{
				$sql.=" AND Proceso like '%".$query."%' ";
			}
			$sql.="AND TK.T ='G'  GROUP by CPO.Cmds,CPO.Proceso,CPO.Picture";
			return $this->db->datos($sql);
	}

	function lista_egreso_checking($query=false,$id=false,$area =false)
	{
		$sql = "SELECT
				TK.Fecha,
				TK.Detalle,
			    TK.Orden_No,
				procedencia,
			    MAX(C.Cliente) AS Cliente,
			    MAX(CP.Producto) AS Producto,
			    MAX(CP.Unidad) AS Unidad,
			    MAX(C1.Cliente) AS usuario,
			    MAX(CPO.Proceso) AS area,
			    MAX(CPO1.Proceso) AS Motivo
			FROM Trans_Kardex TK
			INNER JOIN Catalogo_Productos CP ON TK.Codigo_Inv = CP.Codigo_Inv 
			INNER JOIN Clientes C ON TK.Codigo_P = C.Codigo
			INNER JOIN Clientes C1 ON TK.CodigoU = C1.Codigo
			INNER JOIN Catalogo_Proceso CPO ON TK.Codigo_Tra = CPO.Cmds
			INNER JOIN Catalogo_Proceso CPO1 ON TK.Modelo = CPO1.Cmds
			WHERE TK.Item = '".$_SESSION['INGRESO']['item']."'
			AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."'
			AND TK.CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
			AND TK.Item = CP.Item
			AND TK.Periodo = CP.Periodo
			AND TK.T ='G' ";
			if($query)
			{
				$sql.=" AND TK.Codigo_Barra='".$query."'";
			}
			if($id)
			{
				$sql.=" AND TK.ID='".$id."'";
			}
			if($area)
			{
				$sql.=" AND TK.Codigo_Tra ='".$area."'";
			}
			$sql.=" GROUP by Orden_No,TK.Fecha,TK.Detalle,procedencia";
			// print_r($sql);die();
		return $this->db->datos($sql);
	}

	function lista_egreso_checking_devuelto($query=false,$id=false,$area=false,$desde=false,$hasta=false)
	{
		$sql = "SELECT
				TK.Fecha,
				TK.Detalle,
			    TK.Orden_No,
				procedencia,
			    MAX(C.Cliente) AS Cliente,
			    MAX(CP.Producto) AS Producto,
			    MAX(CP.Unidad) AS Unidad,
			    MAX(C1.Cliente) AS usuario,
			    MAX(CPO.Proceso) AS area,
			    MAX(CPO1.Proceso) AS Motivo
			FROM Trans_Kardex TK
			INNER JOIN Catalogo_Productos CP ON TK.Codigo_Inv = CP.Codigo_Inv 
			INNER JOIN Clientes C ON TK.Codigo_P = C.Codigo
			INNER JOIN Clientes C1 ON TK.CodigoU = C1.Codigo
			INNER JOIN Catalogo_Proceso CPO ON TK.Codigo_Tra = CPO.Cmds
			INNER JOIN Catalogo_Proceso CPO1 ON TK.Modelo = CPO1.Cmds
			WHERE TK.Item = '".$_SESSION['INGRESO']['item']."'
			AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."'
			AND TK.CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
			AND TK.Item = CP.Item
			AND TK.Periodo = CP.Periodo
			AND TK.T ='R' ";
			if($query)
			{
				$sql.=" AND TK.Codigo_Barra='".$query."'";
			}
			if($id)
			{
				$sql.=" AND TK.ID='".$id."'";
			}
			if($area)
			{
				$sql.=" AND TK.Codigo_Tra ='".$area."'";
			}
			if($desde!=false && $hasta!=false)
			{
				if($desde==$hasta)
				{
					$sql.=" AND TK.Fecha ='".$desde."'";
				}else
				{
					$sql.=" AND TK.Fecha between '".$desde."' AND '".$hasta."'";
				}
				
			}
			$sql.=" GROUP by Orden_No,TK.Fecha,TK.Detalle,procedencia";
			// print_r($sql);die();
		return $this->db->datos($sql);
	}

	function eliminar($id)
	{
		$sql = "DELETE FROM Trans_Kardex WHERE ID = '".$id."'";
		return $this->db->String_Sql($sql);
	}

	function eliminar_all()
	{
		$sql = "DELETE FROM Trans_Kardex 
		WHERE T='S' 
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'";
		return $this->db->String_Sql($sql);
	}

	function numero_Registro($fecha)
	{
		$sql = "select COUNT(*) as num from Trans_Kardex TK 
				WHERE TK.Item = '".$_SESSION['INGRESO']['item']."'
				AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND TK.CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
				AND TK.T ='G'
				AND TK.Fecha = '".$fecha."'
				Group By Fecha";

		return $this->db->datos($sql);
	}

	function cargar_motivo_lista($query=false,$id=false,$orden=false)
	{		
		$sql = "SELECT TK.*,C.Cliente,CP.Producto,CP.Unidad 
			FROM Trans_Kardex TK
			INNER JOIN Catalogo_Productos CP on TK.Codigo_Inv = CP.Codigo_Inv 
			INNER JOIN Clientes C on TK.Codigo_P = C.Codigo
			WHERE TK.Item = '".$_SESSION['INGRESO']['item']."'
			AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."'
			AND TK.CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
			AND TK.Item = CP.Item
			AND TK.Periodo = CP.Periodo ";
			if($query)
			{
				$sql.=" AND TK.Codigo_Barra='".$query."'";
			}
			if($id)
			{
				$sql.=" AND TK.ID='".$id."'";
			}
			if($orden)
			{
				$sql.=" AND TK.Orden_No='".$orden."'";
			}
		return $this->db->datos($sql);
	}


	function catalog_cuentas($query=false)
	{
		$sql = "SELECT Periodo, Clave, TC, ME, DG, Item, TB, Codigo, Cuenta, Presupuesto, Saldo_Anterior, Debitos, Creditos, Saldo_Mes, Saldo_Total, Saldo_Total_ME, Total_N6, Total_N5, Total_N4, Total_N3, Total_N2, Total_N1, Listar,Tipo_Pago, CC, X, ID, TP
		FROM Catalogo_Cuentas
		WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."'  
		AND Item = '".$_SESSION['INGRESO']['item']."' ";
		if($query)
		{
			$sql.=" AND Cuenta like '%".$query."%' ";
		}
		$sql.=" AND TC IN ('G', 'CC')";
		return $this->db->datos($sql);
	}

}

?>