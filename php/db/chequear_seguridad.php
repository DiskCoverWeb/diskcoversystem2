<?php
date_default_timezone_set('America/Guayaquil');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$idEntidad = $_SESSION['INGRESO']['IDEntidad'] ?? null;
$item = $_SESSION['INGRESO']['item'] ?? null;
$fecha_session = isset($_SESSION['INGRESO']['SESSION_FECHA']) ? $_SESSION['INGRESO']['SESSION_FECHA'] :null; 
// $fecha_session = '2026-07-26';
$fecha_actual = date('Y-m-d');
require_once("db1.php");
$modulo = '';
if(isset($_GET['mod'])){ $modulo = $_GET['mod']; }

if(!isset($_SESSION['INGRESO']['IDEntidad']) || !isset($_SESSION['INGRESO']['item']) || $fecha_session<$fecha_actual)
{
	echo "<script type='text/javascript'>window.location='".((isset($tipo)&&$tipo==2)?"../":"")."../vista/login.php'</script>";
	die();
}

$NombreModulo = '';
if($modulo!='')
{
	$db = new db();
	$sql = "SELECT modulo, aplicacion, icono, link FROM modulos WHERE modulo = '".$modulo."'";
	// print_r($sql);die();
	$consulta=$db->datos($sql,'MYSQL');

	// print_r($consulta);die();
	if(count($consulta)>0)
	{
	 	$NombreModulo = $consulta[0]['aplicacion'];
	 	$modulo_logo =  $consulta[0]['icono'];
	}
}


?>
