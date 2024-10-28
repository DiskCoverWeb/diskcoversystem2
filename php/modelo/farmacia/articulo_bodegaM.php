<?php 
include(dirname(__DIR__,2).'/db/variables_globales.php');//
include(dirname(__DIR__,2).'/funciones/funciones.php');
@session_start(); 
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