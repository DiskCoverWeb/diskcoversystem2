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
class FCierre_CajaM
{	
		
    private $db ;
	function __construct()
	{
	   $this->db = new db();
	}

	function IniciarAsientosDe($Trans_No = 0)
	{
		if($Trans_No <= 0){ $Trans_No = 1; }
  		BorrarAsientos($Trans_No,true);

		$SQL2 = "SELECT *
	        FROM Asiento
	        WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
       		AND T_No = " . $Trans_No . " 
       		AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'
	        ";
       return $this->db->datos($SQL2);
	}

	function SelectDB($sSQL)
	{
       return $this->db->datos($sSQL);
	}
}