<?php
require_once("db1.php");
@session_start();
$modulo = '';
if(isset($_GET['mod'])){ $modulo = $_GET['mod']; }

if(!isset($_SESSION['INGRESO']['IDEntidad']) || !isset($_SESSION['INGRESO']['item']))
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
