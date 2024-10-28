<?php
//Llamada al modelo
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once("../funciones/funciones.php");
$login = new pruebaC();
if(isset($_GET['proceso']))
{
	echo json_encode($login->pruebaa());
} 
if(isset($_GET['proceso2']))
{
	echo json_encode($login->prueba2());
} 
if(isset($_GET['proceso3']))
{
	echo json_encode($login->prueba3());
} 

/**
 * 
 */
class pruebaC
{

	function __construct()
	{
	}

	function pruebaa()
	{
		return mayorizar_inventario_sp();
	}

function prueba2()
	{
		return mayorizar_inventario_sp2();
	}

function prueba3()
	{
		return mayorizar_inventario_sp3();
	}

	

}


?>
