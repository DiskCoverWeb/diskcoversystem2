<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$idEntidad = $_SESSION['INGRESO']['IDEntidad'] ?? null;
$item = $_SESSION['INGRESO']['item'] ?? null;

if ($idEntidad === null || $item === null) {
    $_SESSION = [];
}
include(dirname(__DIR__,2).'/db/variables_globales.php');//
include(dirname(__DIR__,2).'/funciones/funciones.php');
/**
 * 
 */
class articulo_bodegaM
{
	private $conn ;
	function __construct()
	{
	   $this->conn = cone_ajax();
	}

	function lista_articulos($referencia=false,$producto=false)
	{

	}
}

?>