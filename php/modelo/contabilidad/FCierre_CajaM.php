<?php
include(dirname(__DIR__,2).'/funciones/funciones.php');
@session_start(); 
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