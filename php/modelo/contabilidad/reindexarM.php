<?php 
include(dirname(__DIR__,2).'/funciones/funciones.php');


class reindexarM
{
	
	private $db ;
	function __construct()
	{
	   $this->db = new db();
	}

	function infoError()
	{
		 $sql = "SELECT Texto 
         FROM Tabla_Temporal 
         WHERE Item = '".$_SESSION['INGRESO']['item']."' 
         AND Modulo = '".$_SESSION['INGRESO']['modulo_']."' 
         AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' 
         ORDER BY ID ";
         return $this->db->datos($sql);
	}

	function Eliminar_Tabla_Temporal()
	{
		 $sql = "DELETE 
         		FROM Tabla_Temporal
         		WHERE Item = '".$_SESSION['INGRESO']['item']."' 
         		AND Modulo = '".$_SESSION['INGRESO']['modulo_']."' 
         		AND CodigoU =  '".$_SESSION['INGRESO']['CodigoU']."'  ";
        
         return $this->db->datos($sql);
	}

}
?>