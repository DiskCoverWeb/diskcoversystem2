<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$idEntidad = $_SESSION['INGRESO']['IDEntidad'] ?? null;
$item = $_SESSION['INGRESO']['item'] ?? null;

if ($idEntidad === null || $item === null) {
    $_SESSION = [];
}
require_once(dirname(__DIR__, 2) . "/db/db1.php");
require_once(dirname(__DIR__, 2) . "/funciones/funciones.php");

/**
 * 
 */
class reporte_GFNM
{	
		
    private $conn ;
    private $db;
	function __construct()
	{
	   $this->db = new db();
	}

	function ddl_indicador_gestion($query=false)
	{

		$sql = "SELECT ".Full_Fields("Catalogo_APIs")." 
				FROM Catalogo_APIs 
				WHERE Item = '".$_SESSION['INGRESO']['item']."' 
				AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
				if($query)
				{
					$sql.=" AND Descripcion like '%".$query."%'";
				}
		$sql.=" ORDER BY Descripcion";

		return $this->db->datos($sql);
	}

	function cargar_lista($grupo=false,$codigo=false)
	{
		$sql = "SELECT ".Full_Fields("Catalogo_APIs")." 
				FROM Catalogo_APIs 
				WHERE Item = '".$_SESSION['INGRESO']['item']."' 
				AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
				if($grupo)
				{
					$sql.=" AND codigo like '".$grupo.".%'";
				}
				if($codigo)
				{
					$sql.=" AND codigo = '".$codigo."'";
				}
		$sql.=" ORDER BY Descripcion";
		return $this->db->datos($sql);

	}

}
?>