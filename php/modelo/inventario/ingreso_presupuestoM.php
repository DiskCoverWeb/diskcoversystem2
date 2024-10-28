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
class ingreso_presupuestoM
{
	
	private $db ;
	function __construct()
	{
	   $this->db = new db();
	}

	function delete($id)
	{
		$sql = "DELETE FROM Trans_Presupuestos WHERE ID = '".$id."'";
		return $this->db->String_Sql($sql);
	}





	function centro_costos($proyecto=false,$query=false)
	{

		 $sql = "SELECT Codigo,Cuenta 
     FROM Catalogo_Cuentas 
     WHERE TC='G' 
     AND DG='D' 
     AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
     AND Item = '".$_SESSION['INGRESO']['item']."' ";
     if($proyecto)
     {
     	$sql .=" AND Codigo LIKE '".$proyecto."%'"; 
     }
     if($query)
     {
     	$sql .=" AND Cuenta+' '+Codigo LIKE '%".$query."%'"; 
     }

	// 	 $sql = "SELECT Codigo,Detalle 
	// 	 FROM Catalogo_SubCtas  
	// 	  WHERE TC = 'G'
	//      AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
	//      AND Item = '".$_SESSION['INGRESO']['item']."' ";
	//     // if($query !='')
	//      //{
	//     // 	$sql .=" AND Cuenta+' '+Codigo LIKE '%".$query."%'"; 
	//      //}
	//       //if($proyectos)
	//     // {
	//     // 	$sql .=" AND Codigo LIKE '".$proyectos."%'"; 
	//      //}

     // print_r($sql);die();

	    return $this->db->datos($sql);

	}

	function catalogo_productos($query=false)
	{
		$sql = "SELECT Codigo_Inv,Producto,TC,Valor_Total,Unidad,Stock_Actual,Cta_Inventario 
		FROM Catalogo_Productos 
		WHERE T <>'I' 
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND Item = '".$_SESSION['INGRESO']['item']."' 
		AND LEN(Cta_Inventario)>3 
		AND LEN(Cta_Costo_Venta)>3
		AND TC='P'";
		if($query)
		{
			$sql.=" AND Producto LIKE '%".$query."%'";
		}

      //print_r($sql);die();

	   return  $this->db->datos($sql);

	}

	function lista_pedidos($centro=false)
	{
		$sql = "SELECT CC.Cuenta as 'Detalle',CP.Producto,TP.Presupuesto as 'Cantidad',TP.Codigo_Inv,TP.Cta,TP.ID FROM Trans_Presupuestos TP 
			INNER JOIN Catalogo_Productos CP ON TP.Codigo_Inv = CP.Codigo_Inv
			INNER JOIN Catalogo_Cuentas CC ON TP.Cta = CC.Codigo
			WHERE TP.Item = '".$_SESSION['INGRESO']['item']."'
			AND TP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
			AND CP.Item = TP.Item
			AND CP.Periodo = TP.Periodo
			AND CC.Item = TP.Item
			AND CC.Periodo = TP.Periodo
			AND TP.Cta = CC.Codigo ";
		if($centro)
		{
			$sql.=" AND TP.Cta  in (".$centro.")";
		}

		$sql.="	ORDER BY CC.Cuenta,CP.Producto,TP.ID"; 
			// print_r($sql);die();
	  	return  $this->db->datos($sql);

	}

}

?>