<?php
require(dirname(__DIR__, 2) . '/modelo/facturacion/facturas_productoresAliM.php');
require_once(dirname(__DIR__, 2) . "/comprobantes/SRI/autorizar_sri.php");
require_once(dirname(__DIR__, 3) . "/lib/fpdf/cabecera_pdf.php");
/**
 * 
 */
$controlador = new facturas_productoresAliC();
if(isset($_GET["DCCliente"])){
	$parametros = $_GET;
	echo json_encode($controlador->DCCliente($parametros));
}

if(isset($_GET["pedido_seleccionado"]))
{
	$parametros = $_POST['parametros'];	
	echo json_encode($controlador->pedido_seleccionado($parametros));
}


class facturas_productoresAliC
{
	private $modelo;
	private $sri;
	private $pdf;
	function __construct()
	{
		$this->modelo = new facturas_productoresAliM();
		$this->sri = new autorizacion_sri();
		$this->pdf = new cabecera_pdf();
	}

	function DCCliente($parametros)
	{
		// print_r($parametros);die();
		$lista =array();
		$datos = $this->modelo->DCCliente();
		foreach ($datos as $key => $value) {
			$lista[] = array('id'=>$value['id'],'text'=>$value['text'],'data'=>$value);
		}
		return $lista;
	}

	function pedido_seleccionado($parametros)
	{
		$lineas = $this->modelo->pedido_seleccionado($parametros['Pedido']);
		return $lineas;
	}

}