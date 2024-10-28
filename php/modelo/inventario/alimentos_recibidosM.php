<?php 
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

/**
 * 
 */
class alimentos_recibidosM
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

	function cta_procesos($query=false)
	{
		$sql="SELECT  TP, Proceso, Cta_Debe, Cta_Haber, ID,Picture
			FROM         Catalogo_Proceso
			WHERE  Item = '".$_SESSION['INGRESO']['item']."' 
			AND Nivel = 99";
			if($query)
			{
				$sql.=" AND Proceso Like '%".$query."%'";
			}
			$sql.= " ORDER BY TP";
			// print_r($sql);die();
		return $this->db->datos($sql);
	}
	function detalle_ingreso($cod=false,$query=false,$ci=false)
	{
		$sql="SELECT C.ID,C.Cliente,C.Codigo,C.CI_RUC,C.TD,C.Grupo,C.Actividad,C.Cod_Ejec 
        FROM Clientes As C,Catalogo_CxCxP As CP 
        WHERE CP.TC = 'P' 
        AND CP.Item = '".$_SESSION['INGRESO']['item']."' 
        AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND C.Codigo<>'.'
        AND LEN(C.Cod_Ejec)<=5
        AND C.Cod_Ejec <> '.'
        AND C.Codigo = CP.Codigo";
        if($query)
        {
        	$sql.=" AND C.Cliente like '%".$query."%'";
        } 
        if($cod)
        {
        	$sql.=" AND C.Codigo = '".$cod."'";
        } 
        if($ci)
        {
        	$sql.=" AND C.CI_RUC = '".$ci."'";
        } 
        $sql.=" GROUP BY C.ID,C.Cliente,C.Codigo,C.CI_RUC,C.TD,C.Grupo,C.Actividad,C.Cod_Ejec   
        ORDER BY C.Cliente";
        // print_r($sql);die();
		return $this->db->datos($sql);
	}

	function buscar_transCorreos($cod=false,$fecha=false)
	{
		$sql = "select TC.ID,TC.T,TC.Mensaje,TC.Fecha_P,TC.Fecha,TC.CodigoP,TC.Cod_C,CP.Proceso,TC.TOTAL,TC.Envio_No,C.Cliente,C.CI_RUC,C.Cod_Ejec,TC.Porc_C,TC.Cod_R,CP.Cta_Debe,CP.Cta_Haber,Giro_No,C.Actividad,TC.Llamadas,TC.CodigoU,C2.Cliente as 'Responsable'   
		from Trans_Correos TC
		inner join Clientes C on TC.CodigoP = C.Codigo 
		INNER JOIN Catalogo_Proceso CP ON TC.Cod_C = CP.TP
		inner join Clientes C2 on TC.CodigoU = C2.Codigo 
		where TC.Item = '".$_SESSION['INGRESO']['item']."'
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND TC.Item = CP.Item
		AND (TC.T = 'I' OR TC.T = 'C')";  

		// ver por que se coloco el esto ->	OR TC.T = 'C'  
		if($cod)
		{
			$sql.= " AND Envio_No  like  '%".$cod."%'";
		}
		if($fecha)
		{
			$sql.= " AND TC.Fecha_P =  '".$fecha."'";
		}
		$sql.=" ORDER BY ID desc";  

		// print_r($sql);die();
		return $this->db->datos($sql);
	}
	function buscar_transCorreos_ingresos($cod=false,$fecha=false,$fechah=false)
	{
		$sql = "select TC.ID,TC.T,TC.Mensaje,TC.Fecha_P,TC.Fecha,TC.CodigoP,TC.Cod_C,CP.Proceso,TC.TOTAL,TC.Envio_No,C.Cliente,C.CI_RUC,C.Cod_Ejec,TC.Porc_C,TC.Cod_R,CP.Cta_Debe,CP.Cta_Haber,Giro_No,C.Actividad,TC.Llamadas,TC.CodigoU,C2.Cliente as 'Responsable'   
		from Trans_Correos TC
		inner join Clientes C on TC.CodigoP = C.Codigo 
		INNER JOIN Catalogo_Proceso CP ON TC.Cod_C = CP.TP
		inner join Clientes C2 on TC.CodigoU = C2.Codigo 
		where TC.Item = '".$_SESSION['INGRESO']['item']."'
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND TC.Item = CP.Item
		AND TC.T = 'I' ";
		if($cod)
		{
			$sql.= " AND Envio_No  like  '%".$cod."%'";
		}
		if($fecha!=false && $fechah!=false)
		{
			$sql.= " AND TC.Fecha_P between  '".$fecha."' AND '".$fechah."'";
		}		
		$sql.=" ORDER BY ID desc";  

		// print_r($sql);die();
		return $this->db->datos($sql);
	}

	function buscar_transCorreos_procesados_all($cod=false,$fecha=false,$fechah=false,$id=false)
	{
		$sql = "select DISTINCT TC.ID,TC.T,TC.Mensaje,TC.Fecha_P,TC.Fecha,TC.CodigoP,TC.Cod_C,CP.Proceso,TC.TOTAL,TC.Envio_No,C.Cliente,C.CI_RUC,C.Cod_Ejec,TC.Porc_C,TC.Cod_R,CP.Cta_Debe,CP.Cta_Haber,Giro_No,C.Actividad,TC.Llamadas    
		from Trans_Correos TC
		inner join Clientes C on TC.CodigoP = C.Codigo 
		INNER JOIN Catalogo_Proceso CP ON TC.Cod_C = CP.TP
		where TC.Item = '".$_SESSION['INGRESO']['item']."'
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND TC.Item = CP.Item
		AND TC.T <> 'I'";
		if($cod)
		{
			$sql.= " AND Envio_No  like  '%".$cod."%'";
		}
		if($fecha!=false && $fechah!=false)
		{
			$sql.= " AND TC.Fecha_P between  '".$fecha."' AND '".$fechah."'";
		}
		if($id)
		{
			$sql.= " AND TC.ID =  '".$id."'";
		}
		$sql.=" ORDER BY ID desc";  

		// print_r($sql);die();
		return $this->db->datos($sql);
	}

	function buscar_transCorreos_all($cod=false,$fecha=false,$id=false)
	{
		$sql = "select TC.ID,TC.T,TC.Mensaje,TC.Fecha_P,TC.Fecha,TC.CodigoP,TC.Cod_C,CP.Proceso,TC.TOTAL,TC.Envio_No,C.Cliente,C.CI_RUC,C.Cod_Ejec,TC.Porc_C,TC.Cod_R,CP.Cta_Debe,CP.Cta_Haber,Giro_No,C.Actividad,TC.Llamadas,TC.CodigoU,C2.Cliente as 'Responsable'    
		from Trans_Correos TC
		inner join Clientes C on TC.CodigoP = C.Codigo 
		inner join Clientes C2 on TC.CodigoU = C2.Codigo 
		INNER JOIN Catalogo_Proceso CP ON TC.Cod_C = CP.TP
		where TC.Item = '".$_SESSION['INGRESO']['item']."'
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
		";
		if($cod)
		{
			$sql.= " AND Envio_No  like  '%".$cod."%'";
		}
		if($fecha)
		{
			$sql.= " AND TC.Fecha_P =  '".$fecha."'";
		}
		if($id)
		{
			$sql.= " AND TC.ID =  '".$id."'";
		}
		$sql.=" ORDER BY ID desc";  

		// print_r($sql);die();
		return $this->db->datos($sql);
	}


	function buscar_transCorreos_procesados($cod=false,$fecha=false)
	{
		$sql = "select TC.ID,TC.T,TC.Mensaje,TC.Fecha_P,TC.Fecha,TC.CodigoP,TC.Cod_C,CP.Proceso,TC.TOTAL,TC.Envio_No,C.Cliente,C.CI_RUC,C.Cod_Ejec,TC.Porc_C,TC.Cod_R,C.Actividad,TC.Llamadas,TC.CodigoU,C2.Cliente as 'Responsable'     
		from Trans_Correos TC
		inner join Clientes C on TC.CodigoP = C.Codigo 
		inner join Clientes C2 on TC.CodigoU = C2.Codigo 
		INNER JOIN Catalogo_Proceso CP ON TC.Cod_C = CP.TP
		where TC.Item = '".$_SESSION['INGRESO']['item']."'
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND TC.Item = CP.Item
		AND TC.T = 'P' ";
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
	function buscar_transCorreos_contabilizadios($cod=false,$fecha=false)
	{
		$sql = "select TC.ID,TC.T,TC.Mensaje,TC.Fecha_P,TC.Fecha,TC.CodigoP,TC.Cod_C,CP.Proceso,TC.TOTAL,TC.Envio_No,C.Cliente,C.CI_RUC,C.Cod_Ejec,TC.Porc_C,TC.Cod_R,C.Actividad,TC.Llamadas   
		from Trans_Correos TC
		inner join Clientes C on TC.CodigoP = C.Codigo 
		INNER JOIN Catalogo_Proceso CP ON TC.Cod_C = CP.TP
		where TC.Item = '".$_SESSION['INGRESO']['item']."'
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

	function cargar_pedidos_trans($orden,$fecha=false,$nombre=false)
	{
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT T.*,P.Producto,P.Unidad,P.TDP,A.Nombre_Completo 
     FROM Trans_Kardex  T ,Catalogo_Productos P, Accesos A        
     WHERE T.Item = '".$_SESSION['INGRESO']['item']."' 
     AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."'
     AND Orden_No = '".$orden."' 
     AND T.Codigo_Inv NOT LIKE 'GA.%'";
     // AND T.CodigoL = '".$SUBCTA."'
     // AND T.Codigo_P = '".$paciente."'
     $sql.="AND Numero =0
     AND T.Item = P.Item
     AND T.Periodo = P.Periodo
	 AND T.Codigo_Inv = P.Codigo_Inv
	 AND T.CodigoU = A.Codigo ";
     if($fecha)
     {
     	$sql.=" AND T.Fecha = '".$fecha."'";
     }   
     if($nombre)
     {
     	$sql.=" AND P.Producto = '".$nombre."'";
     }     
     $sql.=" ORDER BY T.ID DESC";
     // print_r($sql);die();

     return $this->db->datos($sql);
       
	}
	function cargar_pedidos_trans_pedidos($orden,$fecha=false,$codigo_inv=false)
	{
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT T.*,P.Producto ,A.Nombre_Completo,P.UNIDAD 
     FROM Trans_Pedidos  T ,Catalogo_Productos P, Accesos A    
     WHERE T.Item = '".$_SESSION['INGRESO']['item']."' 
     AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
     AND Orden_No = '".$orden."'
     AND T.Item = P.Item
     AND T.Periodo = P.Periodo
	 AND T.Codigo_Inv = P.Codigo_Inv
	 AND T.CodigoU = A.Codigo ";
     if($fecha)
     {
     	$sql.=" AND T.Fecha = '".$fecha."'";
     }    
     if($codigo_inv)
     {
     	$sql.=" AND T.Codigo_Inv = '".$codigo_inv."'";
     }     
     $sql.=" ORDER BY T.ID DESC";
     // print_r($sql);
     // die();

     return $this->db->datos($sql);
       
	}
	function lineas_eli($parametros)
	{
		$sql = "DELETE FROM Trans_Kardex WHERE  ID ='".$parametros['lin']."'";
		return $this->db->String_Sql($sql);
	}
	function lineas_eli_pedido($parametros)
	{
		$sql = "DELETE FROM Trans_Pedidos WHERE  ID ='".$parametros['lin']."'";
		return $this->db->String_Sql($sql);
	}
	function eli_all_pedido($pedido)
	{
		$sql = "DELETE FROM Trans_Pedidos WHERE  Orden_No ='".$pedido."'";
		return $this->db->String_Sql($sql);
	}
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
	function cargar_productos($query=false,$pag=false)
	{
		if($pag==false)
		{
			$pag = 0;
		}
		$sql = "SELECT ID,Codigo_Inv,Producto,TC,Minimo,Maximo,Cta_Inventario,Unidad,Ubicacion,IVA,Reg_Sanitario,PVP,TDP 
		FROM Catalogo_Productos  
		WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND item='".$_SESSION['INGRESO']['item']."'  
		AND TC='P' 
		AND LEN(Cta_Inventario)>3 
		AND LEN(Cta_Costo_Venta)>3 
		AND LEN(Item_Banco)>= 2 
		AND LEN(Item_Banco)<= 5 ";
		if($query) 
		{
			$sql.=" AND Codigo_Inv+' '+Producto LIKE '%".$query."%'";
		}
		$sql.=" ORDER BY ID OFFSET ".$pag." ROWS FETCH NEXT 25 ROWS ONLY;";
		
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
       return $datos;
	}
	function familia_pro($Codigo=false,$query= false,$exacto=false)
	{
		$sql = "SELECT ID,Codigo_Inv,Producto,TC,Minimo,Maximo,Cta_Inventario,Unidad,Item_Banco,PVP,TDP 
		        FROM Catalogo_Productos  
		        WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		        AND item='".$_SESSION['INGRESO']['item']."'  
		        AND TC='P' 
		        AND INV='1'";
		if($Codigo)
		{
			$sql.="	 AND Codigo_Inv ='".$Codigo."'"; 
		}
		if($query)
		{
			if($exacto)
			{
				$sql.= " and Producto = '".$query."'";
			}else
			{
				$sql.= " and Producto LIKE '%".$query."%'";
			}
		}
		$sql.= " ORDER BY Producto";
		// print_r($sql);die();
		return $this->db->datos($sql);

	}

	function cargar_productos2($query=false,$pag=false)
	{
		if($pag==false)
		{
			$pag = 0;
		}
		$sql = "SELECT ID,Codigo_Inv,Producto,TC,Minimo,Maximo,Cta_Inventario,Unidad,Ubicacion,IVA,Reg_Sanitario,PVP,TDP 
		FROM Catalogo_Productos  
		WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND item='".$_SESSION['INGRESO']['item']."'  
		AND TC='P' 
		AND TDP <> 'R'
		AND LEN(Cta_Inventario)>3 
		AND LEN(Cta_Costo_Venta)>3 
		AND LEN(Item_Banco)>= 2 
		AND LEN(Item_Banco)<= 5 ";
		if($query) 
		{
			$sql.=" AND Codigo_Inv+' '+Producto LIKE '%".$query."%'";
		}
		$sql.=" ORDER BY ID OFFSET ".$pag." ROWS FETCH NEXT 25 ROWS ONLY;";
		
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
       return $datos;
	}
	function familia_pro2($Codigo=false,$query= false,$exacto=false)
	{
		$sql = "SELECT ID,Codigo_Inv,Producto,TC,Minimo,Maximo,Cta_Inventario,Unidad,Item_Banco,PVP,TDP 
		        FROM Catalogo_Productos  
		        WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		        AND item='".$_SESSION['INGRESO']['item']."'  
		        AND TC='P' 
		        AND TDP <> 'R'
		        AND INV='1'";
		if($Codigo)
		{
			$sql.="	 AND Codigo_Inv ='".$Codigo."'"; 
		}
		if($query)
		{
			if($exacto)
			{
				$sql.= " and Producto = '".$query."'";
			}else
			{
				$sql.= " and Producto LIKE '%".$query."%'";
			}
		}
		$sql.= " ORDER BY Producto";
		// print_r($sql);die();
		return $this->db->datos($sql);

	}



	function eliminar_pedido($id)
	{
		$sql = "DELETE FROM Trans_Correos WHERE ID = '".$id."'";
		return $this->db->String_Sql($sql);
	}

	function bodegas($nivel=false)
	{
		$sql = "SELECT TC, CodBod, Bodega, Item, Periodo, X, ID
		FROM Catalogo_Bodegas
		WHERE Item = '".$_SESSION['INGRESO']['item']."' 
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";

		return $this->db->datos($sql);
	}

	function autoincrementable($fecha){
		
		$sql = "SELECT count(*) as cant 
		FROM Trans_Correos 
		WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND Fecha_P =  '".$fecha."'";

		// print_r($sql);die();
		return $this->db->datos($sql);
	}

	function numeracion_dia_categoria($fecha,$categoria)
	{
		$sql = "SELECT Codigo_Barra
				FROM Trans_Kardex
				WHERE (CodBodega = '-1') 
				AND (Codigo_Barra LIKE '%".$categoria."-%') 
				AND (Fecha_Fab = '".$fecha."')
				AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND Item = '".$_SESSION['INGRESO']['item']."' 
				ORDER BY ID DESC";
				// print_r($sql);die();
		return $this->db->datos($sql);
	}

	function existe_en_transKarder($orden=false,$codigoInv=false)
	{
		$sql = "SELECT Codigo_Barra
				FROM Trans_Kardex
				WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND Item = '".$_SESSION['INGRESO']['item']."' ";
				if($orden)
				{
					$sql.=" AND Orden_No = '".$orden."'";
				}
				if($codigoInv)
				{
					$sql.=" AND Codigo_Inv = '".$codigoInv."'";
				}
				$sql.=" ORDER BY ID DESC";
				// print_r($sql);die();
		return $this->db->datos($sql);
	}


	function sucursales($query = false,$codigo=false,$id=false)
	{
		$sql = "SELECT ID,Codigo, Direccion, TP
			FROM Clientes_Datos_Extras 
			WHERE Item = '".$_SESSION['INGRESO']['item']."'";
			if($codigo)
			{  
				$sql.=" AND Codigo = '".$codigo."' ";
			} 
			if($id)
			{
				$sql.= " AND ID = '".$id."' ";
			}
			$sql.=" AND Tipo_Dato = 'TIPO_PROV' 
			ORDER BY Direccion, Fecha_Registro DESC";

		return $this->db->datos($sql);

	}


	function clientes($query = false,$codigo=false,$id=false)
	{
		$sql = "SELECT ".Full_Fields('Clientes')."
			FROM Clientes 
			WHERE Cliente like '%".$query."%'";
			if($codigo)
			{  
				$sql.=" AND Codigo = '".$codigo."' ";
			} 
			if($id)
			{
				$sql.= " AND ID = '".$id."' ";
			}

			// print_r($sql);die();
			
		return $this->db->datos($sql);

	}

	function listar_notificaciones($codigo=false,$estado = false,$id=false,$pedido=false)
	{
		$sql = "SELECT * FROM 
				Trans_Memos 
				WHERE Item = '".$_SESSION['INGRESO']['item']."'				
				AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
				if($estado)
				{
					$sql.=" AND T = '".$estado."'";
				}
				if($codigo)
				{
					$sql.=" AND Codigo = '".$codigo."'";
				}
				if($id)
				{
					$sql.=" AND ID = '".$id."'";
				}
				if($pedido)
				{
					$sql.=" AND Atencion = '".$pedido."'";
				}
			$sql.=" ORDER BY ID DESC";

				// print_r($sql);die();
			
		return $this->db->datos($sql);
	}

	function preguntas_transporte()
	{
		$sql = "SELECT      Proceso, ID,Cmds,TP
				FROM         Catalogo_Proceso
				WHERE  Item = '".$_SESSION['INGRESO']['item']."' 
				AND Nivel = 96
				AND TP = 'ESTTRANS'
				ORDER BY Cmds, Proceso";
		return $this->db->datos($sql);
	}

	function estado_trasporte($pedido)
	{

		$sql = "SELECT TF.ID,CP.Proceso,Cumple,Carga,CodigoC,CP2.Proceso as 'placa',Conductor,Codigo_Inv
				FROM Trans_Fletes TF 
				inner join Catalogo_Proceso CP on CP.Cmds = TF.TP 
				left join Catalogo_Proceso CP2 on CP2.Cmds = TF.CodigoC 
				WHERE  TF.Item = '".$_SESSION['INGRESO']['item']."' 
				AND TF.Periodo = '".$_SESSION['INGRESO']['periodo']."'
				AND Codigo_Inv= '".$pedido."'";
				// print_r($sql);die();
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

	
	function placas_auto($tipo)
	{
		$sql = "SELECT   TOP (200) Item, Nivel, TP, Proceso, DC, Cheque, Mi_Cta, Cmds, Cta_Debe, Cta_Haber, Picture, Color, X, ID
		FROM      Catalogo_Proceso
		WHERE   (Item = '".$_SESSION['INGRESO']['item']."') AND (Cmds LIKE '".$tipo.".%')
		ORDER BY Cmds";		
		// print_r($sql);die();
		return $this->db->datos($sql);
	}

	function gavetas()
	{
		$sql = "SELECT   Periodo, TC, Codigo_Inv, Producto
			FROM      Catalogo_Productos
			WHERE   (Item = '".$_SESSION['INGRESO']['item']."') AND (Codigo_Inv LIKE 'GA.%')
			ORDER BY Codigo_Inv";
			// print_r($sql);die();
		return $this->db->datos($sql);
		
	}



}
?>