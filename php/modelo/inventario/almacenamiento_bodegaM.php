<?php 
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

/**
 * 
 */
class almacenamiento_bodegaM
{
	private $db;
	function __construct()
	{
	    $this->db = new db();
	}

	// function insert($tabla,$datos)
	// {
	// 	return insert_generico($tabla,$datos);
	// }

	// function cta_procesos($query=false)
	// {
	// 	$sql="SELECT  TP, Proceso, Cta_Debe, Cta_Haber, ID,Picture
	// 		FROM         Catalogo_Proceso
	// 		WHERE  Item = '".$_SESSION['INGRESO']['item']."' 
	// 		AND Nivel = 99";
	// 		if($query)
	// 		{
	// 			$sql.=" AND Proceso Like '%".$query."%'";
	// 		}
	// 		$sql.= " ORDER BY TP";
	// 		// print_r($sql);die();
	// 	return $this->db->datos($sql);
	// }
	// function detalle_ingreso($cod=false,$query=false,$ci=false)
	// {
	// 	$sql="SELECT C.ID,C.Cliente,C.Codigo,C.CI_RUC,C.TD,C.Grupo,C.Actividad,C.Cod_Ejec 
    //     FROM Clientes As C,Catalogo_CxCxP As CP 
    //     WHERE CP.TC = 'P' 
    //     AND CP.Item = '".$_SESSION['INGRESO']['item']."' 
    //     AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
    //     AND C.Codigo<>'.'
    //     AND LEN(C.Cod_Ejec)<=5
    //     AND C.Cod_Ejec <> '.'
    //     AND C.Codigo = CP.Codigo";
    //     if($query)
    //     {
    //     	$sql.=" AND C.Cliente like '%".$query."%'";
    //     } 
    //     if($cod)
    //     {
    //     	$sql.=" AND C.Codigo = '".$cod."'";
    //     } 
    //     if($ci)
    //     {
    //     	$sql.=" AND C.CI_RUC = '".$ci."'";
    //     } 
    //     $sql.=" GROUP BY C.ID,C.Cliente,C.Codigo,C.CI_RUC,C.TD,C.Grupo,C.Actividad,C.Cod_Ejec   
    //     ORDER BY C.Cliente";
    //     // print_r($sql);die();
	// 	return $this->db->datos($sql);
	// }

	// function buscar_transCorreos($cod=false,$fecha=false)
	// {
	// 	$sql = "select TC.ID,TC.T,TC.Mensaje,TC.Fecha_P,TC.Fecha,TC.CodigoP,TC.Cod_C,CP.Proceso,TC.TOTAL,TC.Envio_No,C.Cliente,C.CI_RUC,C.Cod_Ejec,TC.Porc_C,TC.Cod_R,CP.Cta_Debe,CP.Cta_Haber,Giro_No  
	// 	from Trans_Correos TC
	// 	inner join Clientes C on TC.CodigoP = C.Codigo 
	// 	INNER JOIN Catalogo_Proceso CP ON TC.Cod_C = CP.TP
	// 	where Item = '".$_SESSION['INGRESO']['item']."'
	// 	AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
	// 	AND TC.T = 'I' ";
	// 	if($cod)
	// 	{
	// 		$sql.= " AND Envio_No  like  '%".$cod."%'";
	// 	}
	// 	if($fecha)
	// 	{
	// 		$sql.= " AND TC.Fecha_P =  '".$fecha."'";
	// 	}

	// 	// print_r($sql);die();
	// 	return $this->db->datos($sql);
	// }

	function Buscar_productos_ingresados($id=false,$cod=false)
	{
		$sql = "SELECT TK.Periodo, TK.T, CodBodega, Codigo_Barra, Codigo_Inv, TK.Fecha, TP, Numero, Entrada, Salida, Valor_Unitario, Valor_Total, Existencia, Costo, TK.Total, Codigo_P, TK.Descuento, Descuento1, Cta_Inv, Contra_Cta, Orden_No, PVP, Total_IVA, TK.Porc_C, TK.CodigoU, TK.Item, TK.X, Stock_Bod, Unit_Bod, Valor_Bod, Stock_Barra, Costo_Bod, Unit_Barr, Costo_Barr, Valor_Barr, Total_Bod, Total_Barr, Solicitud, CodigoL, Cod_Tarifa, Fecha_DUI, No_Refrendo, DUI, Precio_FOB, Comision, Trans_Unit, Utilidad, Guia_No, CodMarca, Lote_No, Procesado, Codigo_Dr, Codigo_Tra, Fecha_Fab, Fecha_Exp, Modelo, Procedencia, Serie_No, TC, Serie, Factura, Detalle, Centro_Costo, Tipo_Empaque, TK.ID ,C.Cliente,CP.Cod_C
		FROM Trans_Kardex TK
		inner join Clientes C on TK.Codigo_P = C.Codigo
		inner join Trans_Correos CP on TK.Orden_No = CP.Envio_No
		WHERE Orden_No in(select TC.Envio_No
						from Trans_Correos TC
						inner join Clientes C on TC.CodigoP = C.Codigo 
						INNER JOIN Catalogo_Proceso CP ON TC.Cod_C = CP.TP
						where TC.Item = '".$_SESSION['INGRESO']['item']."'
						AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
						AND TC.T = 'N') 
		AND CodBodega = '-1'";
		if($id)
		{
			$sql.= " AND TK.ID  = '".$id."'";
		}
		if($cod)
		{
			$sql.= " AND Codigo_Barra  like  '%".$cod."%'";
		}
		$sql.=" ORDER BY Fecha_Exp ASC";

		// print_r($sql);die();
		return $this->db->datos($sql);
	}
	function buscar_transCorreos_contabilizadios($cod=false,$fecha=false)
	{
		$sql = "select TC.ID,TC.T,TC.Mensaje,TC.Fecha_P,TC.Fecha,TC.CodigoP,TC.Cod_C,CP.Proceso,TC.TOTAL,TC.Envio_No,C.Cliente,C.CI_RUC,C.Cod_Ejec,TC.Porc_C,TC.Cod_R 
		from Trans_Correos TC
		inner join Clientes C on TC.CodigoP = C.Codigo 
		INNER JOIN Catalogo_Proceso CP ON TC.Cod_C = CP.TP
		where Item = '".$_SESSION['INGRESO']['item']."'
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND TC.T = 'N' ";
		if($cod)
		{
			$sql.= " AND Envio_No  like  '%".$cod."%'";
		}
		if($fecha)
		{
			$sql.= " AND TC.Fecha_P =  '".$fecha."'";
		}
		return $this->db->datos($sql);
	}
	//------------------viene de trasnkardex--------------------

	function cargar_pedidos_trans($orden=false,$fecha=false,$nombre=false,$bodega=false)
	{
	     // 'LISTA DE CODIGO DE ANEXOS
	     $sql = "SELECT T.*,P.Producto 
	     FROM Trans_Kardex  T ,Catalogo_Productos P     
	     WHERE T.Item = '".$_SESSION['INGRESO']['item']."' 
	     AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."'
	     AND Numero =0
	     AND T.Item = P.Item
	     AND T.Periodo = P.Periodo
		 AND T.Codigo_Inv = P.Codigo_Inv";
		 if($orden)
		 {
	     	$sql.=" AND Orden_No = '".$orden."' ";
	     }
	     if($fecha)
	     {
	     	$sql.=" AND T.Fecha = '".$fecha."'";
	     }   
	     if($nombre)
	     {
	     	$sql.=" AND P.Producto = '".$nombre."'";
	     }     
	     if($bodega)
	     {
	     	$sql.=" AND T.CodBodega = '".$bodega."'";
	     }  
	     $sql.=" ORDER BY T.ID DESC";
	     // print_r($sql);die();

	     return $this->db->datos($sql);
       
	}

	function cargar_agregado_en_bodega($orden=false,$fecha=false,$nombre=false,$bodega=false)
	{
	     // 'LISTA DE CODIGO DE ANEXOS
	     $sql = "SELECT T.*,P.Producto 
	     FROM Trans_Kardex  T ,Catalogo_Productos P     
	     WHERE T.Item = '".$_SESSION['INGRESO']['item']."' 
	     AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."'
	     AND Numero =0
	     AND T.Item = P.Item
	     AND T.Periodo = P.Periodo
		 AND T.Codigo_Inv = P.Codigo_Inv
		 AND CodBodega <> '-1'
		 AND Orden_No <> '0'
		 AND T.T = 'E' ";
		 if($orden)
		 {
	     	$sql.=" AND Orden_No = '".$orden."' ";
	     }
	     if($fecha)
	     {
	     	$sql.=" AND T.Fecha_DUI = '".$fecha."'";
	     }   
	     if($nombre)
	     {
	     	$sql.=" AND P.Producto = '".$nombre."'";
	     }     
	     if($bodega)
	     {
	     	$sql.=" AND T.CodBodega = '".$bodega."'";
	     }  
	     $sql.=" ORDER BY T.ID DESC";
	     // print_r($sql);die();

	     return $this->db->datos($sql);
       
	}
	function cargar_pedidos_trans_pedidos($orden,$fecha=false,$bodega=false)
	{
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT T.*,P.Producto 
     FROM Trans_Pedidos  T ,Catalogo_Productos P
     WHERE T.Item = '".$_SESSION['INGRESO']['item']."' 
     AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
     AND Orden_No = '".$orden."'
     AND T.Item = P.Item
     AND T.Periodo = P.Periodo
	 AND T.Codigo_Inv = P.Codigo_Inv";
     if($fecha)
     {
     	$sql.=" AND T.Fecha = '".$fecha."'";
     }     
     if($bodega)
     {
     	$sql.=" AND T.Codigo_Sup = '".$bodega."'";
     }     
     $sql.=" ORDER BY T.ID DESC";
     // print_r($sql);die();

     return $this->db->datos($sql);
       
	}
	// function lineas_eli($parametros)
	// {
	// 	$sql = "DELETE FROM Trans_Kardex WHERE  ID ='".$parametros['lin']."'";
	// 	return $this->db->String_Sql($sql);
	// }
	// function lineas_eli_pedido($parametros)
	// {
	// 	$sql = "DELETE FROM Trans_Pedidos WHERE  ID ='".$parametros['lin']."'";
	// 	return $this->db->String_Sql($sql);
	// }
	// function eli_all_pedido($pedido)
	// {
	// 	$sql = "DELETE FROM Trans_Pedidos WHERE  Orden_No ='".$pedido."'";
	// 	return $this->db->String_Sql($sql);
	// }
	function catalogo_productos($codigo)
	{
		$sql = "SELECT * 
		FROM Catalogo_Productos
		WHERE Item = '".$_SESSION['INGRESO']['item']."'
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
		if($codigo)
		{
			$sql.=" AND Codigo_Inv='".$codigo."'";
		}
		// print_r($sql);die();
		return $this->db->datos($sql);
	}



	function ruta_bodega_select($ruta)
	{
		$sql = "SELECT TC, CodBod, Bodega, Item, Periodo, X, ID
		FROM Catalogo_Bodegas
		WHERE Item = '".$_SESSION['INGRESO']['item']."' 
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND CodBod in (".$ruta.")
		order by CodBod ASC ";

		return $this->db->datos($sql);
	}

	function bodegas($nivel=false)
	{
		$sql = "SELECT TC, CodBod, Bodega, Item, Periodo, X, ID
		FROM Catalogo_Bodegas
		WHERE Item = '".$_SESSION['INGRESO']['item']."' 
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";

		return $this->db->datos($sql);
	}

	function cargar_categorias($cmds)
	{
		$sql = "SELECT Cmds,DC, Proceso
			FROM Catalogo_Proceso
			WHERE Item = '".$_SESSION['INGRESO']['item']."'
			AND TP = 'CATEGORI'
			AND Nivel = 0
			AND Cmds IN ( ".$cmds." )
			ORDER BY Cmds";
			// print_r($sql);die();
			
		return $this->db->datos($sql);
	}

	function cargar_empaques($query=false)
	{
		$sql = "SELECT      Proceso, ID,Picture
		FROM         Catalogo_Proceso
		WHERE  Item = '".$_SESSION['INGRESO']['item']."'
		AND Nivel = 97";
		if($query)
		{
			$sql.=" AND Proceso like '%".$query."%'";
		}
		$sql.=" AND TP = 'EMPAQUE'
		ORDER BY Cmds, Proceso";
			// print_r($sql);die();
			
		return $this->db->datos($sql);
	}


}
?>