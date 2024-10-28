<?php
require(dirname(__DIR__, 2) . '/modelo/facturacion/punto_ventaM.php');
require_once(dirname(__DIR__, 2) . "/comprobantes/SRI/autorizar_sri.php");
require_once(dirname(__DIR__, 3) . "/lib/fpdf/cabecera_pdf.php");
/**
 * 
 */
$controlador = new punto_ventaC();
if (isset($_GET['DCCliente'])) {
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	echo json_encode($controlador->Listar_Clientes_PV($query));
}
if (isset($_GET['DCCliente_exacto'])) {
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	echo json_encode($controlador->Listar_Clientes_PV_exacto($query));
}

if (isset($_GET['DCArticulo'])) {
	$query = '';
	$TC = 'FA';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	if (isset($_GET['TC'])) {
		$TC = $_GET['TC'];
	}
	$Grupo_Inv = G_NINGUNO;
	echo json_encode($controlador->DCArticulos($Grupo_Inv, $TC, $query));
}
if (isset($_GET['LblSerie'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->SerieFactura($parametros));
}
if (isset($_GET['DCBodega'])) {
	// $parametros = $_POST['parametros'];
	echo json_encode($controlador->DCBodega());
}
if (isset($_GET['DCBanco'])) {
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	echo json_encode($controlador->DCBanco($query));
}
if (isset($_GET['ArtSelec'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->articulo_seleccionado($parametros));
}
if (isset($_GET['DGAsientoF'])) {
	// $parametros = $_POST['parametros'];
	echo json_encode($controlador->DGAsientoF());
}
if (isset($_GET['IngresarAsientoF'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->IngresarAsientoF($parametros));
}
if (isset($_GET['eliminar_linea'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->eliminar_linea($parametros));
}
if (isset($_GET['Calculos_Totales_Factura'])) {
	echo json_encode($controlador->Calculos_Totales_Factura());
}
if (isset($_GET['editar_factura'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->ReCalcular_PVP_Factura($parametros));
}
if (isset($_GET['generar_factura'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->generar_factura($parametros));
}

if (isset($_GET['generar_factura_elec'])) {
	//print_r($_POST);die();
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->generar_factura_elec($parametros));
}

if (isset($_GET['validar_cta'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->validar_cta($parametros));
}

if (isset($_GET['error_sri'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->error_sri($parametros));
}

if (isset($_GET["AdoLinea"])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->AdoLinea($parametros));
}

if (isset($_GET["AdoAuxCatalogoProductos"])) {
	echo json_encode($controlador->AdoAuxCatalogoProductos());
}

if (isset($_GET["ClienteDatosExtras"])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->ClienteDatosExtras($parametros));
}

if (isset($_GET["ClienteSaldoPendiente"])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->ClienteSaldoPendiente($parametros));
}

if (isset($_GET["DCDireccion"])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCDireccion($parametros));
}

if (isset($_GET["ingresarDir"])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->ingresarDir($parametros));
}

if(isset($_GET["enviar_email_comprobantes"])){
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->enviar_email_comprobantes($parametros));
}

class punto_ventaC
{
	private $modelo;
	private $sri;
	private $pdf;
	function __construct()
	{
		$this->modelo = new punto_ventaM();
		$this->sri = new autorizacion_sri();
		$this->pdf = new cabecera_pdf();
	}

	function Listar_Clientes_PV($query)
	{
		$datos = $this->modelo->Listar_Clientes_PV($query);
		$res = array();
		foreach ($datos as $key => $value) {
			$res[] = array('id' => $value['Codigo'], 'text' => $value['CI_RUC'] . ' - ' . $value['Cliente'], 'data' => array($value));
		}
		return $res;
		// print_r($datos);die();
	}
	function Listar_Clientes_PV_exacto($query)
	{
		$datos = $this->modelo->Listar_Clientes_PV_exacto($query);
		$res = array();
		foreach ($datos as $key => $value) {
			$res[] = array('id' => $value['Codigo'], 'text' => $value['CI_RUC'] . ' - ' . $value['Cliente'], 'data' => array($value));
		}
		return $res;
		// print_r($datos);die();
	}

	function AdoLinea($parametros)
	{
		$emision = date('Y-m-d');
		$vencimiento = date('Y-m-d');
		// busca serie de empresa
		$serie = Leer_Campo_Empresa("Serie_FA");
		if ($serie == '.') {
			// busca serie de usuario
			$serie = $this->modelo->getSerieUsuario($_SESSION['INGRESO']['CodigoU']);
			if (count($serie) > 0 && isset($serie[0]['Serie_FA'])) {
				$serie = $serie[0]['Serie_FA'];
			}
			// busca en catalogo de lineas si no en existe o es punto
			if ($serie == '.') {
				$datos = $this->modelo->getCatalogoLineas13($emision, $vencimiento);
				$serie = $datos[0]['Serie'];
			}
		}
		$parametros['SerieFactura'] = $serie;

		$datosAdoLinea = $this->modelo->AdoLinea($parametros);
		$mensaje = "";
		if (count($datosAdoLinea) > 0) {
			$CodigoL = $datosAdoLinea[0]['Codigo'];
			$Cta_Cobrar = $datosAdoLinea[0]['CxC'];
			$Autorizacion = $datosAdoLinea[0]['Autorizacion'];
			$TC = $datosAdoLinea[0]['Fact'];
			$serie = $datosAdoLinea[0]['Serie'];
		} else {
			$mensaje = "Falta Organizar la CxC en Puntos de Venta.
						Salga de este proceso y llame al su técnico
						o al Contador de su Organizacion.";
		}
		$NumComp = ReadSetDataNum($TC . "_SERIE_" . $serie, True, False);
		return array('mensaje' => $mensaje, 'SerieFactura' => $serie, 'NumComp' => generaCeros($NumComp, 9), 'CodigoL' => $CodigoL, 'Cta_Cobrar' => $Cta_Cobrar, 'Autorizacion' => $Autorizacion);
	}

	function AdoAuxCatalogoProductos()
	{
		$datos = $this->modelo->AdoAuxCatalogoProductos();
		return $datos;
	}

	function ClienteDatosExtras($parametros)
	{
		$datos = $this->modelo->ClientesDatosExtras($parametros);
		return $datos;
	}

	function ClienteSaldoPendiente($parametros)
	{
		$datos = $this->modelo->ClienteSaldoPendiente($parametros);
		return $datos;
	}

	function DCDireccion($parametros)
	{
		$datos = $this->modelo->DCDireccion($parametros);
		return $datos;
	}

	function ingresarDir($parametros)
	{
		SetAdoAddNew("Clientes_Datos_Extras");
		SetAdoFields("Tipo_Dato", "DIRECCION");
		SetAdoFields("Codigo", $parametros['CodigoCliente']);
		SetAdoFields("Direccion", $parametros['DireccionAux']);
		return SetAdoUpdate();
	}

	function SerieFactura($parametros)
	{

		// print_r($parametros);die();
		$emision = date('Y-m-d');
		$vencimiento = date('Y-m-d');
		// busca serie de empresa
		$serie = Leer_Campo_Empresa("Serie_FA");
		if ($serie == '.') {
			// busca serie de usuario
			$serie = $this->modelo->getSerieUsuario($_SESSION['INGRESO']['CodigoU']);
			if (count($serie) > 0 && isset($serie[0]['Serie_FA'])) {
				$serie = $serie[0]['Serie_FA'];
			}
			// busca en catalogo de lineas si no en existe o es punto
			if ($serie == '.') {
				$datos = $this->modelo->getCatalogoLineas13($emision, $vencimiento);
				$serie = $datos[0]['Serie'];
			}
		}
		$NumComp = ReadSetDataNum($parametros['TC'] . "_SERIE_" . $serie, True, False);

		$res = array('serie' => $serie, 'NumCom' => generaCeros($NumComp, 9));

		return $res;
	}

	function DCBodega()
	{
		$datos = $this->modelo->DCBodega();
		// print_r($datos);die();
		$res = array();
		foreach ($datos as $key => $value) {
			$res[] = array('codigo' => $value['CodBod'], 'nombre' => $value['Bodega']);
		}
		return $res;
	}
	function DCBanco($query)
	{
		$datos = $this->modelo->DCBanco($query);
		// print_r($datos);die();
		foreach ($datos as $key => $value) {
			$res[] = array('id' => $value['Codigo'], 'text' => $value['NomCuenta']);
		}
		return $res;

	}
	function DCArticulos($Grupo_Inv, $TipoFactura, $query)
	{
		$datos = $this->modelo->DCArticulos($Grupo_Inv, $TipoFactura, $query);
		$res = array();
		foreach ($datos as $key => $value) {
			$res[] = array('id' => $value['Codigo_Inv'], 'text' => $value['Producto']);
		}
		return $res;
		// print_r($datos);die();
	}
	function articulo_seleccionado($parametros)
	{
		$datos = Leer_Codigo_Inv($parametros['codigo'], $parametros['fecha'], $parametros['CodBod']);
		return $datos;
	}
	function DGAsientoF()
	{
		$datos = $this->modelo->DGAsientoF($grilla = 1);
		// print_r($datos);die();
		return $datos['tbl'];
	}

	function IngresarAsientoF($parametros)
	{
		// print_r($parametros);die();
		$electronico = 0;
		if (isset($parametros['electronico'])) {
			$electronico = $parametros['electronico'];
		}
		$Porc_Iva = floatval($parametros['PorcIva']/100);
		$TextVUnit = $parametros['TextVUnit'];
		$TextCant = $parametros['TextCant'];
		$TipoFactura = $parametros['TC'];
		$TxtDocumentos = $parametros['TxtDocumentos'];
		$Real1 = $parametros['VTotal'];
		$TxtRifaD = $parametros['TxtRifaD'];
		$TxtRifaH = $parametros['TxtRifaH'];
		$TextServicios = $parametros['TextServicios'];
		$CodigoL = '.';
		$producto = Leer_Codigo_Inv($parametros['Codigo'], $parametros['fecha'], $parametros['CodBod']);
		$CodigoL2 = $this->modelo->catalogo_lineas($parametros['TC'], $parametros['Serie'], $parametros['fecha'], $parametros['fecha'], $electronico);
		if (count($CodigoL2) > 0) {
			$CodigoL = $CodigoL2[0]['Codigo'];
		}
		// print_r($CodigoL);die();
		$articulo['IVA'] = 0;
		if ($producto['respueta'] == 1) {
			$articulo = $producto['datos'];
		}
		$Grabar_PV = True;
		$Cant_Item_PV = 50;
		$Lineas = $this->modelo->DGAsientoF();
		$A_No = 0;
		if (count($Lineas['datos']) > 0) {
			$A_No = $Lineas['datos'][count($Lineas['datos']) - 1]['A_No'];
		}

		// print_r($A_No);die();
		$Lineas = count($Lineas['datos']);


		// print_r($parametros);
		// die();
		if ($Cant_Item_PV > 0 and $Lineas > $Cant_Item_PV) {
			$Grabar_PV = False;
		}
		// 'MsgBox Cant_Item_PV
		if ($Grabar_PV) {
			$VTotal = number_format($Real1, 2, '.', '');
			$Real1 = 0;
			$Real2 = 0;
			$Real3 = 0;
			if (is_numeric($TextVUnit) and is_numeric($TextCant)) {
				// 'If Val(TextVUnit) = 0 Then TextVUnit = "0.01"
				if (intval($TextCant) == 0) {
					$TextCant = "1";
				}
				if ($parametros['opc'] == 'OpcMult') {
					$Real1 = $TextCant * $TextVUnit;
				} else {
					$Real1 = $TextCant / $TextVUnit;
				}
			}
			if ($Real1 >= 0) {
				switch ($TipoFactura) {
					case 'NV':
					case 'PV':
						$Real3 = 0;
						break;
					default:
						if ($articulo['IVA'] != 0) {
							$Real3 = number_format(($Real1 - $Real2) * $Porc_Iva, 2, '.', '');
						} else {
							$Real3 = 0;
						}
						break;
				}
				$VTotal = number_format($Real1, 2, '.', '');
				if ($parametros['TextVDescto'] == '') {
					$parametros['TextVDescto'] = 0;
				}
				$Dscto = number_format($parametros['TextVDescto'], 2, '.', '');
				//          	 print_r($articulo);
				// die();	

				if (strlen($TxtDocumentos) > 1) {
					@$articulo['Producto'] = $articulo['Producto'] . " - " . $TxtDocumentos;
				}
				if (is_numeric($TxtRifaD) && is_numeric($TxtRifaH) && intval($TxtRifaD) < intval($TxtRifaH)) {
					// For i = Val(TxtRifaD) To Val(TxtRifaH)
					//     ProductoAux = Producto & " " & Format(i, "000000")
					//     SetAddNew AdoAsientoF
					//     SetFields AdoAsientoF, "CODIGO", Codigos
					//     SetFields AdoAsientoF, "CODIGO_L", CodigoL
					//     SetFields AdoAsientoF, "PRODUCTO", MidStrg(ProductoAux, 1, 150)
					//     SetFields AdoAsientoF, "Tipo_Hab", MidStrg(TxtDocumentos, 1, 12)
					//     SetFields AdoAsientoF, "CANT", 1
					//     SetFields AdoAsientoF, "PRECIO", CCur(TextVUnit)
					//     SetFields AdoAsientoF, "TOTAL", Real1
					//     SetFields AdoAsientoF, "Total_IVA", Real3
					//     SetFields AdoAsientoF, "Item", NumEmpresa
					//     SetFields AdoAsientoF, "CodigoU", CodigoUsuario
					//     SetFields AdoAsientoF, "A_No", Ln_No
					//     SetUpdate AdoAsientoF
					//     Ln_No = Ln_No + 1
					// Next i
				} else {
					// print_r($articulo);
					// die();	
					if (isset($parametros['Producto'])) {
						// esto se usa en facturacion_elec al cambiar el nombre
						$articulo['Producto'] = $parametros['Producto'];
					}

					SetAdoAddNew('Asiento_F');
					SetAdoFields('CODIGO', $articulo['Codigo_Inv']);
					SetAdoFields('CODIGO_L', $CodigoL);
					SetAdoFields('PRODUCTO', $articulo['Producto']);
					SetAdoFields('Tipo_Hab', substr($TxtDocumentos, 0, 40));
					SetAdoFields('CANT', number_format(floatval($TextCant), 2, '.', ''));
					SetAdoFields('PRECIO', number_format($TextVUnit, $_SESSION['INGRESO']['Dec_PVP'], '.', ''));
					SetAdoFields('TOTAL', $Real1);
					SetAdoFields('Total_IVA', $Real3);
					SetAdoFields('Item', $_SESSION['INGRESO']['item']);
					SetAdoFields('CodigoU', $_SESSION['INGRESO']['CodigoU']);
					SetAdoFields('Codigo_Cliente', $parametros['CodigoCliente']);
					SetAdoFields('A_No', $A_No + 1);
					SetAdoFields('CodBod', $parametros['CodBod']);
					SetAdoFields('COSTO', $articulo['Costo']);
					SetAdoFields('Total_Desc', $Dscto);
					SetAdoFields('SERVICIO', $TextServicios);
					if ($articulo['Costo'] > 0) {
						SetAdoFields('Cta_Inv', $articulo['Cta_Inventario']);
						SetAdoFields('Cta_Costo', $articulo['Cta_Costo_Venta']);
					}
					return SetAdoUpdate();

				}
			}
			//       print_r($parametros);
			// die();
		} else {
			return 2;
			// 'TxtEfectivo.SetFocus
		}
		// TextCant.Text = "0"
		// DCArticulo.SetFocus
	}

	function eliminar_linea($parametros)
	{
		return $this->modelo->ELIMINAR_ASIENTOF($parametros['cod'], $parametros['A_no']);
	}

	function Calculos_Totales_Factura()
	{
		$datos = Calculos_Totales_Factura();
		return $datos;
	}

	function ReCalcular_PVP_Factura($parametros)
	{
		$Total_FA = $parametros['total'];
		$CantRubros = 0;
		$datos = $this->modelo->DGAsientoF();
		$datos = $datos['datos'];
		if (count($datos) > 0) {
			foreach ($datos as $key => $value) {
				$CantRubros = $CantRubros + $value["CANT"];
			}
			if ($CantRubros == 0) {
				$CantRubros = 1;
			}
			$PVPTemp = number_format($Total_FA / $CantRubros, 8, '.', ',');
			foreach ($datos as $key => $value) {
				$dato[0]['campo'] = 'PRECIO';
				$dato[0]['dato'] = $PVPTemp;
				$dato[1]['campo'] = 'TOTAL';
				$dato[1]['dato'] = number_format($PVPTemp * $value['CANT'], 4, '.', ',');
				$campoWhere[0]['campo'] = 'A_No';
				$campoWhere[0]['valor'] = $value['A_No'];
				$resp = update_generico($dato, 'Asiento_F', $campoWhere);
				if ($resp == -1) {
					return -1;
				}
			}
			return 1;
		}
	}


	//funcion que se ejecuta en punto de venta en facturacion
	function generar_factura($parametros)
	{
		// print_r($parametros);die();
		$this->sri->Actualizar_factura($parametros['CI'], $parametros['TextFacturaNo'], $parametros['Serie']);

		// FechaValida MBFecha
		$FechaTexto = $parametros['MBFecha'];
		$FA = Calculos_Totales_Factura();

		// print_r(floatval(number_format($FA['Total_MN'],4,'.','')).'-'.floatval(number_format($parametros['TxtEfectivo'],4,'.','')).'-');
		// print_r(floatval(number_format($FA['Total_MN'],4,'.',''))-floatval(number_format($parametros['TxtEfectivo'],4,'.',''))); die();
		if ((floatval(number_format($parametros['TxtEfectivo'], 4, '.', '')) + floatval(number_format($parametros['valorBan'], 4, '.', '')) - floatval(number_format($FA['Total_MN'], 4, '.', ''))) >= 0) {
			$electronico = 0;
			if (isset($parametros['electronico'])) {
				$electronico = $parametros['electronico'];
			}
			//$datos = $this->modelo->catalogo_lineas($parametros['TC'], $parametros['Serie'], $FechaTexto, $FechaTexto, $electronico);
			//if (count($datos) > 0) {
				// print_r($datos);die();
				$FA['Nota'] = $parametros['TxtNota'];
				$FA['Observacion'] = $parametros['TxtObservacion'];
				$FA['Gavetas'] = intval($parametros['TxtGavetas']);
				$FA['codigoCliente'] = $parametros['CodigoCliente'];
				$FA['CodigoC'] = $parametros['CodigoCliente'];
				$FA['TextCI'] = $parametros['CI'];
				$FA['TxtEmail'] = $parametros['email'];
				$FA['Cliente'] = trim(str_replace($parametros['CI'] . ' -', '', $parametros['NombreCliente']));
				$FA['TC'] = $parametros['TC'];
				$FA['Serie'] = $parametros['Serie'];
				$FA['Cta_CxP'] = $parametros['Cta_Cobrar'];
				$FA['Autorizacion'] = $parametros['Autorizacion'];
				$FA['FechaTexto'] = $FechaTexto;
				$FA['Fecha'] = $FechaTexto;
				$FA['Total'] = $FA['Total_MN'];
				$FA['Total_Abonos'] = 0;
				$FA['TextBanco'] = $parametros['TextBanco'];
				$FA['TextCheqNo'] = $parametros['TextCheqNo'];
				$FA['DCBancoC'] = $parametros['DCBancoC'];
				$FA['T'] = $parametros['T'];
				$FA['CodDoc'] = $parametros['CodDoc'];
				$FA['valorBan'] = $parametros['valorBan'];
				$FA['TxtEfectivo'] = $parametros['TxtEfectivo'];
				$FA['Cod_CxC'] = $parametros['CodigoL'];
				$FA['CLAVE'] = ".";
				$FA['Porc_IVA'] = (floatval($parametros['PorcIva'])/100);

				$Moneda_US = False;
				$TextoFormaPago = G_PAGOCONT;
				// print_r($parametros);die();	       
				return $this->ProcGrabar($FA);
			//} else {
			//	return array('respuesta' => -1, 'text' => "Cuenta CxC sin setear en catalogo de lineas");
			//}
		} else {
			return array('respuesta' => -5, 'text' => "El Efectivo no alcanza para grabar");
		}
	}

	function generar_factura_abono_cero($parametros)
	{
		// print_r($parametros);die();
		// FechaValida MBFecha

		$this->sri->Actualizar_factura($parametros['CI'], $parametros['TextFacturaNo'], $parametros['Serie']);

		$FechaTexto = $parametros['MBFecha'];
		$FA = Calculos_Totales_Factura();

		// print_r(floatval(number_format($FA['Total_MN'],4,'.','')).'-'.floatval(number_format($parametros['TxtEfectivo'],4,'.','')).'-');
		// print_r(floatval(number_format($FA['Total_MN'],4,'.',''))-floatval(number_format($parametros['TxtEfectivo'],4,'.',''))); die();
		if ((floatval(number_format($parametros['TxtEfectivo'], 4, '.', '')) + floatval(number_format($parametros['valorBan'], 4, '.', '')) - floatval(number_format($FA['Total_MN'], 4, '.', ''))) >= 0) {
			$electronico = 0;
			if (isset($parametros['electronico'])) {
				$electronico = $parametros['electronico'];
			}
			$datos = $this->modelo->catalogo_lineas($parametros['TC'], $parametros['Serie'], $FechaTexto, $FechaTexto, $electronico);
			if (count($datos) > 0) {
				// print_r($datos);die();
				$FA['Nota'] = $parametros['TxtNota'];
				$FA['Observacion'] = $parametros['TxtObservacion'];
				$FA['Gavetas'] = intval($parametros['TxtGavetas']);
				$FA['CodigoC'] = $parametros['CodigoCliente'];
				$FA['TextCI'] = $parametros['CI'];
				$FA['TxtEmail'] = $parametros['email'];
				$FA['Cliente'] = trim(str_replace($parametros['CI'] . ' -', '', $parametros['NombreCliente']));
				$FA['TC'] = $parametros['TC'];
				$FA['Serie'] = $parametros['Serie'];
				$FA['Cta_CxP'] = $datos[0]['CxC'];
				$FA['Autorizacion'] = $datos[0]['Autorizacion'];
				$FA['FechaTexto'] = $FechaTexto;
				$FA['Fecha'] = $FechaTexto;
				$FA['Total'] = $FA['Total_MN'];
				$FA['Total_Abonos'] = 0;
				$FA['TextBanco'] = $parametros['TextBanco'];
				$FA['TextCheqNo'] = $parametros['TextCheqNo'];
				$FA['DCBancoC'] = $parametros['DCBancoC'];
				$FA['T'] = $parametros['T'];
				$FA['CodDoc'] = $parametros['CodDoc'];
				$FA['valorBan'] = $parametros['valorBan'];
				$FA['TxtEfectivo'] = $parametros['TxtEfectivo'];

				$Moneda_US = False;
				$TextoFormaPago = G_PAGOCONT;
				// print_r($parametros);die();
				return $this->ProcGrabar_Abono_cero($FA);
			} else {
				return array('respuesta' => -1, 'text' => "Cuenta CxC sin setear en catalogo de lineas");
			}
		} else {
			return array('respuesta' => -5, 'text' => "El Efectivo no alcanza para grabar");
		}
	}

	// funcion para vista de facturar electronico , sin restriccion de que la factura este en cero
	function generar_factura_elec($parametros)
	{
		// print_r($parametros);die();
		$this->sri->Actualizar_factura($parametros['CI'], $parametros['TextFacturaNo'], $parametros['Serie']);

		$electronico = 0;
		if (isset($parametros['electronico'])) {
			$electronico = $parametros['electronico'];
		}

		// FechaValida MBFecha
		$FechaTexto = $parametros['MBFecha'];
		$FA = Calculos_Totales_Factura();
		$datos = $this->modelo->catalogo_lineas($parametros['TC'], $parametros['Serie'], $FechaTexto, $FechaTexto, $electronico);
		if (count($datos) > 0) {
			// print_r($datos);die();
			$FA['Nota'] = $parametros['TxtNota'];
			$FA['Observacion'] = $parametros['TxtObservacion'];
			$FA['Gavetas'] = intval($parametros['TxtGavetas']);
			$FA['CodigoC'] = $parametros['CodigoCliente'];
			$FA['codigoCliente'] = $parametros['CodigoCliente'];
			$FA['TextCI'] = $parametros['CI'];
			$FA['TxtEmail'] = $parametros['email'];
			$FA['Cliente'] = trim(str_replace($parametros['CI'] . ' -', '', $parametros['NombreCliente']));
			$FA['TC'] = $parametros['TC'];
			$FA['Serie'] = $parametros['Serie'];
			$FA['Cta_CxP'] = $datos[0]['CxC'];
			$FA['Autorizacion'] = $datos[0]['Autorizacion'];
			$FA['FechaTexto'] = $FechaTexto;
			$FA['Fecha'] = $FechaTexto;
			$FA['Total'] = $FA['Total_MN'];
			$FA['Total_Abonos'] = 0;
			$FA['TextBanco'] = $parametros['TextBanco'];
			$FA['TextCheqNo'] = $parametros['TextCheqNo'];
			$FA['DCBancoC'] = $parametros['DCBancoC'];
			$FA['T'] = $parametros['T'];
			$FA['CodDoc'] = $parametros['CodDoc'];
			$FA['valorBan'] = $parametros['valorBan'];
			$FA['TxtEfectivo'] = $parametros['TxtEfectivo'];
			$FA['Cod_CxC'] = $datos[0]['Codigo'];
			$FA['Remision'] = 0;
			if (isset($parametros['tipo_pago'])) {
				$FA['Tipo_Pago'] = $parametros['tipo_pago'];
			} else {
				$FA['Tipo_Pago'] = '01';
			}
			$FA['Porc_IVA'] = (floatval($parametros['PorcIva'])/100);


			//datos para guia de remision
			if (isset($parametros['LblGuiaR']) && $parametros['LblGuiaR'] != 0) {
				// print_r('dasd');die();
				$RazonSocial = explode('_', $parametros['DCRazonSocial']);
				$CI_RUC = $RazonSocial[0];
				$DIR = $RazonSocial[1];
				$EmpresaEntrega = explode('_', $parametros['DCEmpresaEntrega']);
				$CI_RUC_E = $EmpresaEntrega[0];

				$serie_gr = explode('_', $parametros['DCSerieGR']);
				// $dire_E = $EmpresaEntrega[1];
				// $Direccion = $EmpresaEntrega[2];

				$TFA['Remision'] = 1;
				$FA['ClaveAcceso_GR'] = G_NINGUNO;
				$FA['Autorizacion_GR'] = $parametros['LblAutGuiaRem'];
				$FA['Serie_GR'] = $serie_gr[1];
				$FA['Remision'] = $parametros['LblGuiaR'];
				$FA['FechaGRE'] = $parametros['MBoxFechaGRE'];
				$FA['FechaGRI'] = $parametros['MBoxFechaGRI'];
				$FA['FechaGRF'] = $parametros['MBoxFechaGRF'];
				$FA['Placa_Vehiculo'] = $parametros['TxtPlaca'];
				$FA['Lugar_Entrega'] = $parametros['TxtLugarEntrega'];
				$FA['Zona'] = $parametros['TxtZona'];
				$FA['CiudadGRI'] = $parametros['DCCiudadI'];
				$FA['CiudadGRF'] = $parametros['DCCiudadF'];
				$FA['Comercial'] = $parametros['Razon'];
				$FA['CIRUCComercial'] = $CI_RUC;

				$FA['Entrega'] = $parametros['Entrega'];
				$FA['CIRUCEntrega'] = $CI_RUC_E;
				$FA['Dir_EntregaGR'] = G_NINGUNO;
				$FA['Pedido'] = $parametros['TxtPedido'];
			}

			$Moneda_US = False;
			$TextoFormaPago = G_PAGOCONT;
			return $this->ProcGrabar($FA);
		} else {
			return array('respuesta' => -1, 'text' => "Cuenta CxC sin setear en catalogo de lineas");
		}
	}



	function ProcGrabar($FA)
	{
		$conn = new db();
		$Grafico_PV = Leer_Campo_Empresa("Grafico_PV");
		if(!isset($FA['Porc_IVA']))
		{
			$FA['Porc_IVA'] = $_SESSION['INGRESO']['porc'];
		}
		// 'Seteamos los encabezados para las facturas
		// $FA = Calculos_Totales_Factura();
		$Dolar = 0;

		// print_r($FA);die();
		$datos = $this->modelo->DGAsientoF();
		$datos = $datos['datos'];
		// $servicios = 0;
		// foreach ($datos as $key => $value) {
		// 	$servicios+= $value['SERVICIO'];
		//  }
		if (count($datos) > 0) {
			$HoraTexto = date("H:i:s");
			$Total_FacturaME = 0;
			$Moneda_US = False;
			if ($Moneda_US) {
				$Total_Factura = number_format(($FA['Sin_IVA'] + $FA['Con_IVA'] - $FA['Descuento'] - $FA['Descuento2'] + $FA['Total_IVA'] + $FA['Servicio']) * $Dolar, 2, '.', '');
				$Total_FacturaME = number_format($FA['Sin_IVA'] + $FA['Con_IVA'] - $FA['Descuento'] - $FA['Descuento2'] + $FA['Total_IVA'] + $FA['Servicio'], 2, '.', ',');
			} else {
				$Total_Factura = number_format($FA['Sin_IVA'] + $FA['Con_IVA'] - $FA['Descuento'] - $FA['Descuento2'] + $FA['Total_IVA'] + $FA['Servicio'], 2, '.', '');
				$Total_FacturaME = 0;
			}
			$Saldo = $Total_Factura;
			$Saldo_ME = $Total_FacturaME;
			if ($Saldo < 0) {
				$Saldo = 0;
			}
			$FA['Nuevo_Doc'] = True;
			$FA['Saldo_MN'] = $Saldo;
			$Factura_No = ReadSetDataNum($FA['TC'] . "_SERIE_" . $FA['Serie'], True, True);
			$FA['Factura'] = $Factura_No;
			$FA['FacturaNo'] = $Factura_No;
			$TipoFactura = $FA['TC'];
			if ($TipoFactura == "PV") {
				Control_Procesos("F", "Grabar Ticket No. " . $Factura_No, '');
			} else if ($TipoFactura == "NV") {
				Control_Procesos("F", "Grabar Nota de Venta No. " . $Factura_No, '');
			} else if ($TipoFactura == "CP") {
				Control_Procesos("F", "Grabar Cheque Protestado No. " . $Factura_No, '');
			} else if ($TipoFactura == "LC") {
				Control_Procesos("F", "Grabar Liquidacion de Compras No. " . $Factura_No, '');
			} else if ($TipoFactura == "DO") {
				Control_Procesos("F", "Grabar Nota de Donacion No. " . $Factura_No, '');
			} else {
				Control_Procesos("F", "Grabar Factura No. " . $Factura_No, '');
			}
			// $this->modelo->delete_factura($TipoFactura,$Factura_No);

			$TextoFormaPago = G_PAGOCRED;
			$T = G_PENDIENTE;
			// 'Grabamos el numero de factura

			// print_r($FA);die();
			$r = Grabar_Factura1($FA);
			if ($r != 1) {
				return $r;
			}

			// $this->ingresar_trans_kardex_salidas_FA($FA['Factura'],$FA['codigoCliente'],$FA['Cliente'],$FA['FechaTexto'],$TipoFactura); //($FA['Factura'],$codigoCliente);
			// die();

			// print_r($FA);die();
			if ($FA['TC'] <> "CP") {
				$Evaluar = True;
				$FechaTexto = $FA['FechaTexto'];
				$Total_Factura = $Total_Factura - $FA['valorBan'];
				// if($FA['TxtEfectivo']>$Total_Factura){$Total_Factura= }

				// 'Abono en efectivo

				// 'Abono en efectivo
				$TA['T'] = G_NORMAL;
				$TA['TP'] = $TipoFactura;
				$TA['Fecha'] = $FechaTexto;
				$TA['Cta_CxP'] = $FA['Cta_CxP'];
				$TA['Cta'] = $_SESSION['SETEOS']['Cta_CajaG'];
				$TA['Banco'] = "EFECTIVO MN";
				$TA['Cheque'] = generaCeros($FA['Factura'], 8);
				$TA['Factura'] = $FA['Factura'];
				$TA['Serie'] = $FA['Serie'];
				$TA['Autorizacion'] = $FA['Autorizacion'];
				$TA['CodigoC'] = $FA['codigoCliente'];
				$TA['codigoCliente'] = $FA['codigoCliente'];
				// $Total_Factura = 0;
				$TA['Abono'] = $FA['TxtEfectivo'];
				$TA['Saldo'] = $Total_Factura - $FA['TxtEfectivo'];
				// print_r('adasdasdasd');die();
				Grabar_Abonos($TA);


				// 'Abono de Factura Banco
				$TA['T'] = G_NORMAL;
				$TA['TP'] = $TipoFactura;
				$TA['Fecha'] = $FechaTexto;
				$TA['Cta'] = $FA['DCBancoC'];
				$TA['Cta_CxP'] = $FA['Cta_CxP'];
				$TA['Banco'] = $FA['TextBanco'];
				$TA['Cheque'] = $FA['TextCheqNo'];
				$TA['Factura'] = $Factura_No; //pendiente
				$Total_Bancos = 0;
				$TA['Abono'] = $FA['valorBan'];
				// print_r($TA);die();
				Grabar_Abonos($TA);
				// print_r($TA);die();



				$FA['TC'] = $TA['TP'];
				$FA['Serie'] = $TA['Serie'];
				$FA['Autorizacion'] = $TA['Autorizacion'];
				$FA['Factura'] = $Factura_No;
				$sql = "UPDATE Facturas
          SET Saldo_MN = 0 ";
				if (isset($FA['TxtEfectivo']) && $FA['TxtEfectivo'] == 0) {
					$sql .= ",T = 'P'";
				} else {
					$sql .= " ,T = 'C' ";
				}
				$sql .= "
          WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
          AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
          AND Factura = " . $Factura_No . "
          AND TC = '" . $TipoFactura . "'
          AND CodigoC = '" . $FA['codigoCliente'] . "'
          AND Autorizacion = '" . $FA['Autorizacion'] . "'
          AND Serie = '" . $FA['Serie'] . "' ";

				$conn->String_Sql($sql);
			}

			if (strlen($FA['Autorizacion']) >= 13) {

				// print_r('si');die();
				// print_r('drrrrddd');die();
				$imp_guia = '';
				if ($FA['TC'] <> "DO") {
					//la respuesta puede se texto si envia numero significa que todo saliobien
					$rep = $this->sri->Autorizar_factura_o_liquidacion($FA);
					$clave = $this->sri->Clave_acceso($TA['Fecha'], '01', $TA['Serie'], $Factura_No);
					$rep1 = 0;
					$imp_guia = '';
					$clave_guia = '';
					if (isset($FA['Remision']) &&  $FA['Remision'] != 0) {
						$FA['Autorizacion'] = $clave;
						if ($FA['Autorizacion_GR'] >= 13) {
							$rep1 = $this->sri->SRI_Crear_Clave_Acceso_Guia_Remision($FA);
							$this->modelo->pdf_guia_remision_elec($FA, $FA['Autorizacion_GR'], $periodo = false, 0, 1);
							$clave_guia = $this->sri->Clave_acceso($FA['FechaGRE'], '06', $FA['Serie_GR'], $FA['Remision']);
							$imp_guia = $FA['Serie_GR'] . '-' . generaCeros($FA['Remision'], 7);
							$GR = ReadSetDataNum("GR_SERIE_" . $FA['Serie_GR'], True, True);
						}
					}
					// print_r($rep);die();
					// SRI_Crear_Clave_Acceso_Facturas($FA,true); 
					$FA['Desde'] = $FA['Factura'];
					$FA['Hasta'] = $FA['Factura'];
					// Imprimir_Facturas_CxC(FacturasPV, FA, True, False, True, True);
					$TFA = Imprimir_Punto_Venta_Grafico_datos($FA);
					$TFA['CLAVE'] = $clave;
					$TFA['PorcIva'] = $FA['Porc_IVA'];
					$imp = $FA['Serie'] . '-' . generaCeros($FA['Factura'], 7);

					$this->modelo->pdf_factura_elec($FA['Factura'], $FA['Serie'], $FA['codigoCliente'], $imp, $clave, $periodo = false, 0, 1);
					// print_r('ex');die();
					if ($rep == 1) {
						if ($_SESSION['INGRESO']['Impresora_Rodillo'] == 0 && $_SESSION['INGRESO']['Grafico_PV'] == 0) {

							return array('respuesta' => $rep, 'pdf' => $imp, 'clave' => $clave, 'respuesta_guia' => $rep1, 'pdf_guia' => $imp_guia, 'clave_guia' => $clave_guia, 'rodillo' => $_SESSION['INGRESO']['Impresora_Rodillo']);

						} else if ($_SESSION['INGRESO']['Impresora_Rodillo'] == 1 && $_SESSION['INGRESO']['Grafico_PV'] == 0) {
							// impresion matricial
							return array('respuesta' => $rep, 'pdf' => $imp, 'clave' => $clave, 'respuesta_guia' => $rep1, 'pdf_guia' => $imp_guia, 'clave_guia' => $clave_guia, 'rodillo' => $_SESSION['INGRESO']['Impresora_Rodillo']);

						} else if ($_SESSION['INGRESO']['Impresora_Rodillo'] == 0 && $_SESSION['INGRESO']['Grafico_PV'] == 1) {
							$this->pdf->Imprimir_Punto_Venta_Grafico($TFA);
							return array('respuesta' => $rep, 'pdf' => $imp, 'clave' => $clave, 'respuesta_guia' => $rep1, 'pdf_guia' => $imp_guia, 'clave_guia' => $clave_guia, 'rodillo' => $_SESSION['INGRESO']['Impresora_Rodillo']);
						} else {

							$this->pdf->Imprimir_Punto_Venta_Grafico($TFA);
							return array('respuesta' => $rep, 'pdf' => $imp, 'clave' => $clave, 'respuesta_guia' => $rep1, 'pdf_guia' => $imp_guia, 'clave_guia' => $clave_guia, 'rodillo' => $_SESSION['INGRESO']['Impresora_Rodillo']);
						}

					} else {
						return array('respuesta' => -1, 'pdf' => $imp, 'text' => $rep, 'clave' => $clave, 'respuesta_guia' => $rep1, 'pdf_guia' => $imp_guia, 'clave_guia' => $clave_guia, 'rodillo' => $_SESSION['INGRESO']['Impresora_Rodillo']);
					}
				}
			} else {
				// print_r('dddd');die();
				if ($Grafico_PV) {
					$TFA = Imprimir_Punto_Venta_Grafico_datos($FA);
					Imprimir_Punto_Venta_Grafico($TFA);
					Imprimir_Punto_Venta_Grafico($TFA);
				} else {
					$TFA = Imprimir_Punto_Venta_Grafico_datos($FA);
					$TFA['CLAVE'] = '.';
					$this->pdf->Imprimir_Punto_Venta_Grafico($TFA);
					$imp = $FA['Serie'] . '-' . generaCeros($FA['Factura'], 7);
					$rep = 1;
					if ($rep == 1) {
						return array('respuesta' => $rep, 'pdf' => $imp);
					} else {
						return array('respuesta' => -1, 'pdf' => $imp, 'text' => $rep);
					}

					// ojo ver cula se piensa imprimir
					// Imprimir_Punto_Venta($FA);
				}
			}
			$sql = "DELETE 
      FROM Asiento_F
      WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
      AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' ";
			$conn->String_Sql($sql);
			return 1;
		} else {
			return "No se puede grabar la Factura,  falta datos.";
		}
	}


	function validar_cta($parametros)
	{
		$electronico = 0;
		if (isset($parametros['electronico'])) {
			$electronico = $parametros['electronico'];
		}
		// print_r($parametros);die();
		$datos = $this->modelo->catalogo_lineas($parametros['TC'], $parametros['Serie'], $parametros['Fecha'], $parametros['Fecha'], $electronico);
		$Cta_CxP = isset($datos[0]['CxC']) ? $datos[0]['CxC'] : G_NINGUNO;
		// print_r($datos);die();
		if ($Cta_CxP <> G_NINGUNO) {
			$ExisteCtas = array();
			$ExisteCtas[0] = $Cta_CxP;
			$ExisteCtas[1] = $_SESSION['SETEOS']['Cta_CajaG']; //$Cta_CajaG;
			$ExisteCtas[2] = $_SESSION['SETEOS']['Cta_CajaGE']; //$Cta_CajaGE;
			$ExisteCtas[3] = $_SESSION['SETEOS']['Cta_CajaBA']; //$Cta_CajaBA;
			return VerSiExisteCta($ExisteCtas);
		} else {
			return 'No se encontro Catalogo de Lineas';
		}
	}

	function ingresar_trans_kardex_salidas_FA($orden, $ruc, $nombre, $fechaC, $TipoFactura)
	{
		$datos_K = $this->modelo->cargar_pedidos_factura($orden, $ruc);
		// print_r($datos_K);die();
		// print_r($datos_K);
		// $comprobante = explode('.',$comprobante);
		// $comprobante = explode('-',trim($comprobante[1]));
		// $comprobante = $comprobante;
		$resp = 1;
		$lista = '';
		foreach ($datos_K as $key => $value) {
			// print_r($value);die();
			$datos_inv = $this->modelo->lista_hijos_id($value['Codigo']);
			// print_r($datos_inv);die();
			$cant[2] = 0;
			if (count($datos_inv) > 0) {
				$cant = explode(',', $datos_inv[0]['id']);
			}

			SetAdoAddNew('Trans_Kardex');
			SetAdoFields('Numero', 0);
			SetAdoFields('T', 'N');
			SetAdoFields('TP', '.');
			SetAdoFields('Costo', number_format($value['Precio'], 2, '.', ''));
			SetAdoFields('Total', number_format($value['Total'], 2, '.', ''));
			SetAdoFields('Existencia', number_format(($cant[2]), 2, '.', '') - number_format(($value['Cantidad']), 2, '.', ''));
			SetAdoFields('CodBodega', '01');
			SetAdoFields('Detalle', 'Salida de inventario (' . $TipoFactura . ') para ' . $nombre . ' con CI: ' . $ruc . ' el dia ' . $fechaC);
			SetAdoFields('Procesado', 0);
			SetAdoFields('Total_IVA', number_format($value['Total_IVA'], 2, '.', ''));
			SetAdoFields('Codigo_Inv', $value['Codigo']);
			SetAdoFields('Salida', $value['Cantidad']);
			SetAdoFields('Valor_Unitario', number_format($value['Precio'], $_SESSION['INGRESO']['Dec_PVP'], '.', ''));
			SetAdoFields('Valor_Total', number_format($value['Total'], 2, '.', ''));
			SetAdoFields('CodigoU', $_SESSION['INGRESO']['CodigoU']);
			SetAdoFields('Item', $_SESSION['INGRESO']['item']);
			SetAdoFields('Periodo', $_SESSION['INGRESO']['periodo']);
			SetAdoFields('Factura', $orden);
			$res = SetAdoUpdate();



			if ($res != 1) {
				$resp = 0;
			}

		}
		// print_r($resp);die();
		return $resp;

	}

	function error_sri($parametros)
	{
		$clave = $parametros['clave'] . '.xml';
		$entidad = generaCeros($_SESSION['INGRESO']['IDEntidad'], 3);
		$carpeta_entidad = dirname(__DIR__, 2) . "/comprobantes/entidades/entidad_" . $entidad;
		$carpeta_comprobantes = $carpeta_entidad . '/CE' . generaCeros($_SESSION['INGRESO']['item'], 3);
		$carpeta_no_autori = $carpeta_comprobantes . "/No_autorizados";
		$carpeta_rechazados = $carpeta_comprobantes . "/Rechazados";



		$ruta1 = $carpeta_no_autori . '/' . $clave;
		$ruta2 = $carpeta_rechazados . '/' . $clave;

		// print_r($ruta1);print_r($ruta2);die();
		if (file_exists($ruta1)) {

			// print_r($ruta);die();
			$xml = simplexml_load_file($ruta1);
			$codigo = $xml->mensajes->mensaje->mensaje->identificador;
			$mensaje = $xml->mensajes->mensaje->mensaje->mensaje;
			$adicional = $xml->mensajes->mensaje->mensaje->informacionAdicional;
			$estado = $xml->estado;
			$fecha = $xml->fechaAutorizacion;
			// print_r($mensaje);die();
			return array('estado' => $estado, 'codigo' => $codigo, 'mensaje' => $mensaje, 'adicional' => $adicional, 'fecha' => $fecha);
		}

		if (file_exists($ruta2)) {
			// print_r($ruta2);die();
			$fp = fopen($ruta2, "r");
			$linea = '';
			while (!feof($fp)) {
				$linea .= fgets($fp);
			}
			fclose($fp);
			$linea = str_replace('ns2:', '', $linea);
			$xml = simplexml_load_string($linea);

			$codigo = $xml->respuestaSolicitud->comprobantes->comprobante->mensajes->mensaje->identificador;
			$mensaje = $xml->respuestaSolicitud->comprobantes->comprobante->mensajes->mensaje->mensaje;
			$adicional = $xml->respuestaSolicitud->comprobantes->comprobante->mensajes->mensaje->informacionAdicional;
			$estado = $xml->respuestaSolicitud->estado;
			$fecha = '';
			// print_r($mensaje);die();
			return array('estado' => $estado, 'codigo' => $codigo, 'mensaje' => $mensaje, 'adicional' => $adicional, 'fecha' => $fecha);

		}
	}

	function enviar_email_comprobantes($parametros)
	{
		$to_correo = $parametros['correo'];
		$titulo_correo = 'comprobantes electronicos';
        $cuerpo_correo = 'comprobantes electronico';

        $lista_archivos = array();

        if(isset($parametros['pdf']) && count($parametros['pdf']))
        {
        	foreach ($parametros['pdf'] as $key => $value) {
        		if (file_exists(dirname(__DIR__, 3) . '/TEMP/' . $value)) {
                $lista_archivos[] = dirname(__DIR__, 3) . '/TEMP/' . $value;
              }
        	}
        }

        if(isset($parametros['clave']) && count($parametros['clave'])>0)
        {
        	foreach ($parametros['clave'] as $key => $value) {
        		 if (file_exists(dirname(__DIR__, 3) . '/php/comprobantes/entidades/entidad_' . generaCeros($_SESSION['INGRESO']['IDEntidad'], 3) . '/CE' . generaCeros($_SESSION['INGRESO']['item'], 3) . '/Autorizados/' .$value)) {
                 $lista_archivos[] = dirname(__DIR__, 3) . '/php/comprobantes/entidades/entidad_' . generaCeros($_SESSION['INGRESO']['IDEntidad'], 3) . '/CE' . generaCeros($_SESSION['INGRESO']['item'], 3) . '/Autorizados/' . $value;
              	}
        	}
        }
		return enviar_email_comprobantes($lista_archivos, $to_correo, $cuerpo_correo, $titulo_correo, $HTML = false);
	}


}

?>