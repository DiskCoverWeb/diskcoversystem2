<?php
include(dirname(__DIR__, 2) . '/modelo/contabilidad/FAbonosM.php');
// require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */


$controlador = new FAbonosC();

if (isset($_GET['DCVendedor'])) {
	// $parametros = $_POST['parametros'];
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	echo json_encode($controlador->DCVendedor($query));
}
if (isset($_GET['DCBanco'])) {
	// $parametros = $_POST['parametros'];
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	echo json_encode($controlador->DCBanco($query));
}

if (isset($_GET['DCTarjeta'])) {
	// $parametros = $_POST['parametros'];
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	echo json_encode($controlador->DCTarjeta($query));
}
if (isset($_GET['DCRetFuente'])) {
	// $parametros = $_POST['parametros'];
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	echo json_encode($controlador->DCRetFuente($query));
}
if (isset($_GET['DCRetISer'])) {
	// $parametros = $_POST['parametros'];
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	echo json_encode($controlador->DCRetISer($query));
}
if (isset($_GET['DCRetIBienes'])) {
	// $parametros = $_POST['parametros'];
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	echo json_encode($controlador->DCRetIBienes($query));
}

if (isset($_GET['DCCodRet'])) {
	// $parametros = $_POST['parametros'];
	$fecha = $_GET['MBFecha'];
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	echo json_encode($controlador->DCCodRet($query, $fecha));
}
if (isset($_GET['DCTipo'])) {

	echo json_encode($controlador->DCTipo());
}

if (isset($_GET['DCSerie'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCSerie($parametros));
}

if (isset($_GET['DCFactura'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCFactura($parametros));
}
if (isset($_GET['DCFactura1'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCFactura1($parametros));
}
if (isset($_GET['DCAutorizacion'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCAutorizacion($parametros));
}
if (isset($_GET['DiarioCaja'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DiarioCaja($parametros));
}
if (isset($_GET['Grabar_abonos'])) {
	$parametros = $_POST;
	echo json_encode($controlador->Grabar_abonos($parametros));
}
class FAbonosC
{

	private $modelo;
	// private $pdf;

	function __construct()
	{
		$this->modelo = new FAbonosM();
		// $this->pdf = new cabecera_pdf();
	}

	function DCVendedor($query)
	{
		$datos = $this->modelo->DCVendedor($query);
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('id' => $value['Codigo'], 'text' => $value['Nombre_Completo']);
		}
		return $list;

	}

	function DCBanco($query)
	{
		$datos = $this->modelo->DCBanco($query);
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('id' => $value['Codigo'], 'text' => $value['NomCuenta']);
		}
		return $list;

	}
	function DCTarjeta($query)
	{
		$datos = $this->modelo->DCTarjeta($query);
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('id' => $value['Codigo'], 'text' => $value['NomCuenta']);
		}
		return $list;

	}
	function DCRetFuente($query)
	{
		$datos = $this->modelo->DCRetFuente($query);
		$list = array();
		if (count($datos) > 0) {
			foreach ($datos as $key => $value) {
				$list[] = array('id' => $value['Codigo'], 'text' => $value['Cuentas']);
			}
		} else {
			$list[] = array('id' => '0', 'text' => 'No existen datos');
		}
		return $list;

	}
	function DCRetISer($query)
	{
		$datos = $this->modelo->DCRetISer($query);
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('id' => $value['Codigo'], 'text' => $value['Cuentas']);
		}
		return $list;

	}
	function DCRetIBienes($query)
	{
		$datos = $this->modelo->DCRetIBienes($query);
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('id' => $value['Codigo'], 'text' => $value['Cuentas']);
		}
		return $list;

	}
	function DCCodRet($query, $fecha)
	{
		$datos = $this->modelo->DCCodRet($query, $fecha);
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('id' => $value['Codigo'], 'text' => $value['Codigo']);
		}
		return $list;

	}
	function DCTipo()
	{
		$datos = $this->modelo->DCTipo();
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('codigo' => $value['TC'], 'nombre' => $value['TC']);
		}
		return $list;
	}

	function DCSerie($parametro)
	{
		$TC = $parametro['tipo'];
		$datos = $this->modelo->DCSerie($TC);
		// print_r($datos);die();
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('codigo' => $value['Serie'], 'nombre' => $value['Serie']);
		}
		return $list;
	}

	function DCFactura($parametro)
	{
		$TC = $parametro['tipo'];
		$Serie = $parametro['serie'];
		$datos = $this->modelo->DCFactura($TC, $Serie);
		// print_r($datos);die();
		$list = array();
		if (count($datos) > 0) {
			$list[] = array('codigo' => '', 'nombre' => 'Seleccione Factura');
			foreach ($datos as $key => $value) {
				$list[] = array('codigo' => $value['Factura'], 'nombre' => $value['Factura'], 'Factura' => $value);
			}
		} else {
			$list[] = array('codigo' => '', 'nombre' => 'No existen datos');
		}
		return $list;

	}

	function DCFactura1($parametro)
	{
		$TC = $parametro['tipo'];
		$Serie = $parametro['serie'];
		$factura = $parametro['factura'];
		$datos = $this->modelo->DCFactura($TC, $Serie, $factura);
		return $datos;
	}

	function DCAutorizacion($parametro)
	{
		$TC = $parametro['tipo'];
		$Serie = $parametro['serie'];
		$factura = $parametro['factura'];
		$datos = $this->modelo->DCAutorizacion($factura, $TC, $Serie);
		// print_r($datos);die();
		$list = array();
		if (count($datos) > 0) {
			foreach ($datos as $key => $value) {
				$list[] = array('codigo' => $value['Autorizacion'], 'nombre' => $value['Autorizacion']);
			}
		} else {
			$list[] = array('codigo' => '', 'nombre' => 'No existen datos');
		}
		return $list;

	}

	function DiarioCaja($parametros)
	{
		$DiarioCaja = ReadSetDataNum("Recibo_No", True, False);
		if ($parametros['CheqRecibo'] == true) {
			$num = generaCeros($DiarioCaja, 7);
			return $num;
		} else {
			return '';
		}
	}

	function Grabar_abonos($parametro)
	{
		$TA['Autorizacion'] = $parametro['DCAutorizacion'];
		$TA['CodigoC'] = $parametro['CodigoC'];
		$TA['Serie'] = $parametro['DCSerie'];
		$TA['Codigo_Inv'] = '.';
		$TA['Comprobante'] = '';
		$TA['Vendedor'] = $parametro['DCVendedor'];
		$TA['CI_RUC_Cli'] = '';

		$TA['Recibi_de'] = $parametro['LblCliente'];
		if ($parametro['CheqRecibo'] == 'on') {
			$DiarioCaja = ReadSetDataNum("Recibo_No", True, True);
		} else {
			$DiarioCaja = $parametro['TxtRecibo'];
		}
		$TA['DiarioCaja'] = $DiarioCaja;
		$TA['Recibo_No'] = $DiarioCaja;

		$banco = '';
		$Cta = '';
		// print_r($parametro);die();
		if ($parametro['DCBancoNom'] != '') {
			$banco1 = explode('  ', $parametro['DCBancoNom']);
			$banco = strlen($banco1[0]);
			$Cta = trim($banco1[0]);
		}
		// trim(substr($parametro['DCBancoNom'],$banco,strlen($parametro['DCBancoNom'])));

		$tarjeta = '';
		if ($parametro['DCTarjetaNom'] != '') {
			$tarjeta1 = explode('  ', $parametro['DCTarjetaNom']);
			$tarjeta = strlen($tarjeta1[0]);
		}

		$Cta1 = $parametro['DCTarjeta'];
		$NombreBanco1 = '';
		if ($parametro['DCTarjetaNom'] != '') {
			$NombreBanco1 = trim($tarjeta1[1]);
		}

		// 'Abono de Factura Caja MN
		$TA['T'] = G_NORMAL;
		$TA['TP'] = $parametro['DCTipo'];
		$TA['Fecha'] = $parametro['MBFecha'];
		$TA['Cta'] = 1; // revisar de donde sale estas variables Cta_CajaG;
		$TA['Cta_CxP'] = $parametro['Cta_Cobrar']; //Cta_Cobrar
		$TA['Banco'] = "EFECTIVO MN";
		$TA['Cheque'] = $parametro['LblGrupo']; //Grupo_No
		$TA['Factura'] = $parametro['DCFactura'];
		$TA['Abono'] = $parametro['TextCajaMN']; //TotalCajaMN
		Grabar_Abonos($TA);
		// 'Abono de Factura Caja ME
		$TA['T'] = G_NORMAL;
		$TA['TP'] = $parametro['DCTipo']; //TipoFactura
		$TA['Fecha'] = $parametro['MBFecha']; //MBFecha
		$TA['Cta'] = 1; // revisar de donde sale estas variables Cta_CajaGE
		$TA['Cta_CxP'] = $parametro['Cta_Cobrar']; //Cta_Cobrar
		$TA['Banco'] = "EFECTIVO MN";
		$TA['Cheque'] = $parametro['LblGrupo']; //Grupo_No
		$TA['Factura'] = $parametro['DCFactura'];
		$TA['Abono'] = $parametro['TextCajaME']; //TotalCajaMN
		// print_r($TA);die();
		Grabar_Abonos($TA);

		// 'Abono de Factura Banco
		$TA['T'] = G_NORMAL;
		$TA['TP'] = $parametro['DCTipo']; //TipoFactura
		$TA['Fecha'] = $parametro['MBFecha']; //MBFecha
		$TA['Cta'] = $parametro['DCBanco'];
		$TA['Cta_CxP'] = $parametro['Cta_Cobrar']; //Cta_Cobrar
		$TA['Banco'] = $parametro['TextBanco'];
		$TA['Cheque'] = $parametro['TextCheqNo'];
		$TA['Factura'] = $parametro['DCFactura'];
		$TA['Abono'] = $parametro['TextCheque'];
		// print_r($TA);die();
		Grabar_Abonos($TA);

		// 'Abono de Factura Tarjeta
		$TA['T'] = G_NORMAL;
		$TA['TP'] = $parametro['DCTipo']; //TipoFactura
		$TA['Fecha'] = $parametro['MBFecha']; //MBFecha
		$TA['Cta'] = $parametro['DCTarjeta'];
		$TA['Cta_CxP'] = $parametro['Cta_Cobrar']; //Cta_Cobrar
		$TA['Banco'] = $NombreBanco1; //revisar
		$TA['Cheque'] = $parametro['TextBaucher'];
		$TA['Factura'] = $parametro['DCFactura'];
		$TA['Abono'] = $parametro['TextTotalBaucher'];
		// print_r($TA);die();
		Grabar_Abonos($TA);


		//'Abono de Factura Interes Tarjeta
		$TA['T'] = G_NORMAL;
		$TA['TP'] = "TJ";
		$TA['Fecha'] = $parametro['MBFecha']; //MBFecha
		$TA['Cta'] = $Cta1; //revisar
		$TA['Cta_CxP'] = $_SESSION['SETEOS']['Cta_Tarjetas']; //Cta_Tarjetas //revisar poner variables de la misma secion
		$TA['Banco'] = "INTERES POR TARJETA";
		$TA['Cheque'] = $parametro['TextBaucher'];
		$TA['Factura'] = $parametro['DCFactura'];
		$TA['Abono'] = $parametro['TextInteres'];
		// print_r($TA);die();
		Grabar_Abonos($TA);


		// 'Abono de Factura Rete. IVA Bienes
		$Codigo1 = substr($parametro['TxtSerieRet'], 0, 3);
		$Codigo2 = substr($parametro['TxtSerieRet'], 3, 6);
		$TA['T'] = G_NORMAL;
		$TA['TP'] = $parametro['DCTipo']; //TipoFactura
		$TA['Fecha'] = $parametro['MBFecha']; //MBFecha
		$TA['Cta'] = $parametro['DCRetIBienes'];
		$TA['Cta_CxP'] = $parametro['Cta_Cobrar']; //Cta_Cobrar
		$TA['Banco'] = "RETENCION IVA BIENES";
		$TA['Cheque'] = $parametro['TextCompRet'];
		$TA['Factura'] = $parametro['DCFactura'];
		$TA['Abono'] = $parametro['TextRetIVAB'];
		$TA['AutorizacionR'] = $parametro['TxtAutoRet'];
		$TA['Establecimiento'] = $Codigo1;
		$TA['Emision'] = $Codigo2;
		$TA['Porcentaje'] = $parametro['CBienes'];
		Grabar_Abonos_Retenciones($TA);

		// 'Abono de Factura Ret IVA Servicio
		$TA['T'] = G_NORMAL;
		$TA['TP'] = $parametro['DCTipo']; //TipoFactura
		$TA['Fecha'] = $parametro['MBFecha']; //MBFecha
		$TA['Cta'] = $parametro['DCRetISer'];
		$TA['Cta_CxP'] = $parametro['Cta_Cobrar']; //Cta_Cobrar
		$TA['Banco'] = "RETENCION IVA SERVICIO";
		$TA['Cheque'] = $parametro['TextCompRet'];
		$TA['Factura'] = $parametro['DCFactura'];
		$TA['Abono'] = $parametro['TextRetIVAS'];
		$TA['AutorizacionR'] = $parametro['TxtAutoRet'];
		$TA['Establecimiento'] = $Codigo1;
		$TA['Emision'] = $Codigo2;
		$TA['Porcentaje'] = $parametro['CServicio'];
		Grabar_Abonos_Retenciones($TA);

		// 'Abono de Factura Ret. Fuente
		$TA['T'] = G_NORMAL;
		$TA['TP'] = $parametro['DCTipo']; //TipoFactura
		$TA['Fecha'] = $parametro['MBFecha']; //MBFecha
		$TA['Cta'] = $parametro['DCRetFuente'];
		$TA['Cta_CxP'] = $parametro['Cta_Cobrar']; //Cta_Cobrar
		$TA['Banco'] = "RETENCION FUENTE - " . $parametro['DCCodRet'];
		$TA['Cheque'] = $parametro['TextCompRet'];
		$TA['Factura'] = $parametro['DCFactura'];
		$TA['Abono'] = $parametro['TextRet'];
		$TA['AutorizacionR'] = $parametro['TxtAutoRet'];
		$TA['Establecimiento'] = $Codigo1;
		$TA['Emision'] = $Codigo2;
		$TA['Porcentaje'] = $parametro['TextPorc'];
		Grabar_Abonos_Retenciones($TA);

		Actualiza_Estado_Factura($TA);

		$T = "P";
		$SaldoDisp = $parametro['LabelPend'];
		if ($SaldoDisp <= 0) {
			$T = "C";
			$SaldoDisp = 0;
		}
		$this->modelo->Actualizar_factura($SaldoDisp, $T, $TA);
		// print_r($parametro);die();
		return Imprimir_Comprobante_Caja($TA);
	}


}
?>