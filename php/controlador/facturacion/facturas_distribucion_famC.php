<?php
require(dirname(__DIR__, 2) . '/modelo/facturacion/facturas_distribucion_famM.php');
require_once(dirname(__DIR__, 2) . "/comprobantes/SRI/autorizar_sri.php");
require_once(dirname(__DIR__, 3) . "/lib/fpdf/cabecera_pdf.php");
/**
 * 
 */
$controlador = new facturas_distribucion_fam();
if(isset($_GET["ddl_pedidos_familia"])){
	$q = '';
	if(isset($_GET['q']))
	{
		$q = $_GET['q'];
	}
	echo json_encode($controlador->ddl_pedidos_familia($q));
}
if(isset($_GET["cargar_asignacion"])){
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_asignacion($parametros));
}

if(isset($_GET["IntegrantesGrupo"])){
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->IntegrantesGrupo($parametros));
}

if(isset($_GET["GenerarFactura"])){
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->GenerarFactura($parametros));
}


class facturas_distribucion_fam
{
	private $modelo;
	private $sri;
	private $pdf;
	function __construct()
	{
		$this->modelo = new facturas_distribucion_famM();
		$this->sri = new autorizacion_sri();
		$this->pdf = new cabecera_pdf();
	}

	
	function ddl_pedidos_familia($query)
	{
		$datos = $this->modelo->ddl_pedidos_familia(false,'KF',false,false);
		$lista = array();
		foreach ($datos as $key => $value) {
			$lista[] = array('data'=>$value,'id'=>$value['Orden_No'],'text'=>$value['Orden_No']);
		}
		// print_r($datos);die();
		return $lista;
	}
	function cargar_asignacion($parametros)
	{
		$datos = $this->modelo->cargar_asignacion($parametros['orden'],false,'F',$parametros['fechaPick']);
		// print_r($datos);die();
		return $datos;
	}

	function IntegrantesGrupo($parametros)
	{
		$datos = $this->modelo->IntegrantesGrupo($parametros['grupo']);
		return $datos;
		print_r($parametros);die();

	}

	function GenerarFactura($parametros)
	{
		// $datos = $this->modelo->IntegrantesGrupo($parametros['grupo']);
		// return $datos;
		print_r($parametros);die();

	}


}

?>