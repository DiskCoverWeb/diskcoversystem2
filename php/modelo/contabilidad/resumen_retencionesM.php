<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$idEntidad = $_SESSION['INGRESO']['IDEntidad'] ?? null;
$item = $_SESSION['INGRESO']['item'] ?? null;

if ($idEntidad === null || $item === null) {
    $_SESSION = [];
}
include(dirname(__DIR__,2).'/funciones/funciones.php');
/**
 * 
 */
class resumen_retencionesM
{	
		
    private $conn ;
	function __construct()
	{
	   $this->conn = cone_ajax();
	}

	function mostrar_malla($parametros)
	{
	   	
	}


}
?>