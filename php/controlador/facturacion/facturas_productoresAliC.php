<?php
require(dirname(__DIR__, 2) . '/modelo/facturacion/facturas_productoresAliM.php');
require(dirname(__DIR__, 2) . '/modelo/facturacion/facturas_distribucion_famM.php');
require(dirname(__DIR__, 2) . '/modelo/facturacion/punto_ventaM.php');
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
if(isset($_GET["buscar_linea"]))
{
	$parametros = $_POST['parametros'];	
	echo json_encode($controlador->buscar_linea($parametros));
}

if(isset($_GET["generar_factura"]))
{
	$parametros = $_POST['parametros'];	
	echo json_encode($controlador->generar_factura($parametros));
}

class facturas_productoresAliC
{
	private $modelo;
	private $sri;
	private $pdf;
	private $modelo_fam;
	private $punto_venta;
	function __construct()
	{
		$this->modelo = new facturas_productoresAliM();
		$this->modelo_fam = new facturas_distribucion_famM();
		$this->sri = new autorizacion_sri();
		$this->pdf = new cabecera_pdf();
		$this->punto_venta = new punto_ventaM();
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

	function buscar_linea($parametros)
	{
		$datos = $this->modelo->pedido_seleccionado($parametros['Pedido'],$parametros['id']);
		return $datos;
		// print_r($datos);die();
	}

	function generar_factura($parametros)
	{
		$Factura_No = 1;
		$lineas =  json_decode($parametros['lineas'], true);
		// print_r($lineas);DIE();
		// print_r($parametros);die();

		// actualizar en trans_comisiones
		foreach ($lineas as $key => $value) {
			SetAdoAddNew('Trans_Comision');
			SetAdoFields('Total', $value['cant']);
			SetAdoFieldsWhere('ID', $value['id']);
			SetAdoUpdateGeneric();
		}

		if($parametros['TC']=='NPA')
		{
			$Factura_No = ReadSetDataNum($parametros['TC'] . "_SERIE_" . $parametros['serie'], True, True);
		}



		//genera en asientosF para FACTURA
		$producto = Leer_Codigo_Inv('FA.97',date('Y-m-d'));
		if($producto['respueta']!='1')
		{
			// no se encontro el producto que se estaba buscando
			return -3;
		}
		// else {
			$total = 0;
			$this->modelo->DeleteAsientoF($parametros['pedido']);
			$cliente = Leer_Datos_Cliente_FA($parametros['CI']);
			foreach ($lineas as $key => $value) {
				$linea_pedido = $this->modelo->pedido_seleccionado($parametros['pedido'],$value['id']);
				$total = $total+$value['total'];			
			}

			SetAdoAddNew('Asiento_F');
			SetAdoFields('Codigo_Cliente', $cliente['CodigoC']);
			SetAdoFields('CODIGO', $producto['datos']['Codigo_Inv']);
			SetAdoFields('PRODUCTO', $producto['datos']['Producto']);
			SetAdoFields('Orden_No', $parametros['pedido']);
			SetAdoFields('CodigoU', $_SESSION['INGRESO']['CodigoU']);
			SetAdoFields('A_No', 1);
			SetAdoFields('CANT', 1);
			SetAdoFields('PRECIO',$total);
			SetAdoFields('TOTAL', $total);
			SetAdoFields('Serie',$parametros['serie']);
			SetAdoFields('Item',$_SESSION['INGRESO']['item']);
			SetAdoUpdate();

			$parametros['TC'] = 'FA';
			$parametros['FacturaNo'] = $Factura_No;

			$resp = $this->GenerarFacturaUni($parametros);
			$this->modelo->ActualizarCodigoFactura($parametros['TC'] . "_SERIE_" . $parametros['serie'],$Factura_No);
		// }


		// print_r($resp);die();


		//genera en asientosF para NPA
		$this->modelo->DeleteAsientoF($parametros['pedido']);
		foreach ($lineas as $key => $value) {
			$linea_pedido = $this->modelo->pedido_seleccionado($parametros['pedido'],$value['id']);
			// print_r($linea_pedido);die();
			SetAdoAddNew('Asiento_F');
			SetAdoFields('Codigo_Cliente', $linea_pedido[0]['CodigoC']);
			SetAdoFields('CODIGO', $linea_pedido[0]['Codigo_Inv']);
			SetAdoFields('PRODUCTO', $linea_pedido[0]['Producto']);
			SetAdoFields('Orden_No', $linea_pedido[0]['Orden_No']);
			SetAdoFields('CodigoU', $linea_pedido[0]['CodigoU']);
			SetAdoFields('HABIT', $linea_pedido[0]['Cta']);
			SetAdoFields('CodBod', $linea_pedido[0]['CodBodega']);
			SetAdoFields('A_No', ($key+1));
			SetAdoFields('CANT', $linea_pedido[0]['Total']);
			SetAdoFields('PRECIO', $value['pvp']);
			SetAdoFields('TOTAL', $value['total']);
			SetAdoFields('Serie',$parametros['serie']);
			SetAdoFields('Item',$_SESSION['INGRESO']['item']);
			SetAdoUpdate();
		}
		//genera factura nota npa
		$parametros['TC'] = 'NPA';
		$resp_npa = $this->GenerarFacturaUni($parametros);

		// ingreso a trans_kardex 
		$listado_kardex = $this->modelo->detalle_Factura('NPA',$parametros['serie'],$Factura_No);
		// print_r($listado_kardex);die();
		foreach ($listado_kardex as $key => $value) {
			SetAdoAddNew('Trans_Kardex');
			SetAdoFields('Codigo_P', $value['CodigoC']);
			SetAdoFields('Codigo_Inv', $value['Codigo']);
			SetAdoFields('Codigo_Barra', $value['CodBodega']);
			SetAdoFields('TP','CD');
			SetAdoFields('Salida', $value['Cant']);
			SetAdoFields('Valor_Unitario', $value['Precio']);
			SetAdoFields('PVP', $value['Precio']);
			SetAdoFields('Valor_Total',$value['Total']);
			SetAdoFields('Total', $value['Total']);
			SetAdoFields('Costo', $value['Precio']);
			SetAdoFields('Cta_Inv', $value['Cta_Inventario']);
			SetAdoFields('Contra_Cta', $value['Cta_Costo_Venta']);
			SetAdoFields('TC', $value['DFTC']);
			SetAdoFields('Factura', $value['FACT']);
			SetAdoFields('Serie',$value['Serie']);			
			SetAdoFields('CodigoL', $value['CodigoL']);
			SetAdoFields('Detalle', 'FA:'.$value['Cliente']);
			SetAdoFields('Cmds', $value['No_Hab']);
			SetAdoFields('Item',$_SESSION['INGRESO']['item']);
			SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);
			SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU']);
			SetAdoUpdate();
		}


		//marca como facturada el pedido
		SetAdoAddNew('Trans_Comision');
		SetAdoFields('TP','F');
		SetAdoFieldsWhere('Orden_No', $parametros['pedido']);
		SetAdoUpdateGeneric();

		return $resp;
		
	}

	function GenerarFacturaUni($parametros)
	{

		// print_r($parametros);die();
		
		$electronico = 0;
		if (isset($parametros['electronico'])) {
			$electronico = $parametros['electronico'];
		}

		// FechaValida MBFecha
		$FechaTexto = $parametros['MBFecha'];
		$FA = Calculos_Totales_Factura();
		$datos = $this->modelo_fam->catalogo_lineas($parametros['TC'], $parametros['serie'], $FechaTexto, $FechaTexto,$electronico);
		$cliente = Leer_Datos_Cliente_FA($parametros['CI']);

		if (count($datos) > 0 && count($cliente)>0)  {
			// print_r($datos);die();
			// $FA['Nota'] = $parametros['TxtNota'];
			// $FA['Observacion'] = $parametros['TxtObservacion'];

			// print_r($FA);die();
			
			$FA['FacturaNo'] = $parametros['FacturaNo'];
			$FA['CodigoC'] = $cliente['CodigoC'];
			$FA['codigoCliente'] = $cliente['CodigoC'];
			$FA['TextCI'] = $parametros['CI'];
			$FA['TxtEmail'] = $cliente['EmailC'];
			$FA['Cliente'] = trim(str_replace($cliente['CI_RUC'] . ' -', '', $cliente['Cliente']));
			$FA['TC'] = $parametros['TC'];
			$FA['Serie'] = $parametros['serie'];
			$FA['Cta_CxP'] = $datos[0]['CxC'];
			$FA['Autorizacion'] = $datos[0]['Autorizacion'];
			$FA['FechaTexto'] = $FechaTexto;
			$FA['Fecha'] = $FechaTexto;
			$FA['Total'] = $FA['Total_MN'];
			$FA['Total_Abonos'] = 0;
			$FA['TextBanco'] = $parametros['TextBanco'];
			$FA['TextCheqNo'] = $parametros['TextCheqNo'];
			$FA['DCBancoC'] = $parametros['DCBancoC'];
			// $FA['T'] = $parametros['T'];
			// $FA['CodDoc'] = $parametros['CodDoc'];
			$FA['valorBan'] = $parametros['valorBanco'];
			$FA['TxtEfectivo'] = $parametros['valorEfectivo'];
			$FA['Cod_CxC'] = $datos[0]['Codigo'];
			$FA['Remision'] = 0;
			if (isset($parametros['tipo_pago'])) {
				$FA['Tipo_Pago'] = $parametros['tipo_pago'];
			} else {
				$FA['Tipo_Pago'] = '01';
			}
			$FA['Porc_IVA'] = (floatval($parametros['PorcIva'])/100);

			$Moneda_US = False;
			$TextoFormaPago = G_PAGOCONT;

			// print_r($FA);die();
			$resp =  $this->ProcGrabar($FA);
			return $resp;
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
		$datos = $this->punto_venta->DGAsientoF();
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
			$Factura_No = $FA['FacturaNo'];
			$FA['Factura'] = $Factura_No;
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
			}else if ($TipoFactura == "NPA") {
				Control_Procesos("F", "Grabar Nota de Donacion Productores Aliados No. " . $Factura_No, '');
			}else {
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

			// print_r($FA);die();
			if ($FA['TC'] <> "CP" &&  $FA['TC'] <> "NPA") {
				$Evaluar = True;
				$FechaTexto = $FA['FechaTexto'];
				$Total_Factura = $Total_Factura - $FA['valorBan'];
				// if($FA['TxtEfectivo']>$Total_Factura){$Total_Factura= }

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
				if ($FA['TC'] <> "DO" && $FA['TC'] <> "NPA") {
					//la respuesta puede se texto si envia numero significa que todo saliobien
					$resp = $this->sri->Autorizar_factura_o_liquidacion($FA);
					if($resp[0]=='-1')
					{
						return $resp;
					}
					$clave = $this->sri->Clave_acceso($TA['Fecha'], '01', $TA['Serie'], $Factura_No);
					$imp_guia = '';
					$clave_guia = '';
					
					// print_r($rep);die();
					// SRI_Crear_Clave_Acceso_Facturas($FA,true); 
					$FA['Desde'] = $FA['Factura'];
					$FA['Hasta'] = $FA['Factura'];
					// Imprimir_Facturas_CxC(FacturasPV, FA, True, False, True, True);
					$TFA = Imprimir_Punto_Venta_Grafico_datos($FA);
					$TFA['CLAVE'] = $clave;
					$TFA['PorcIva'] = $FA['Porc_IVA'];
					$imp = $FA['Serie'] . '-' . generaCeros($FA['Factura'], 7);

					$this->punto_venta->pdf_factura_elec($FA['Factura'], $FA['Serie'], $FA['codigoCliente'], $imp, $clave, $periodo = false, 0, 1);
					// print_r($resp);die();
					if ($resp[0] == 1) {
						if ($_SESSION['INGRESO']['Impresora_Rodillo'] == 0 && $_SESSION['INGRESO']['Grafico_PV'] == 0) {
							$resp['rodillo'] = $_SESSION['INGRESO']['Impresora_Rodillo'];
							$resp['pdf'] = $imp; 
							
							return $rep; 

						} else if ($_SESSION['INGRESO']['Impresora_Rodillo'] == 1 && $_SESSION['INGRESO']['Grafico_PV'] == 0) {
							// impresion matricial
							return array('respuesta' => $resp, 'pdf' => $imp, 'clave' => $clave,'rodillo' => $_SESSION['INGRESO']['Impresora_Rodillo'],'factura'=>$FA['Factura'],'serie'=>$FA['Serie']);

						} else if ($_SESSION['INGRESO']['Impresora_Rodillo'] == 0 && $_SESSION['INGRESO']['Grafico_PV'] == 1) {
							$this->pdf->Imprimir_Punto_Venta_Grafico($TFA);
							return array('respuesta' => $resp, 'pdf' => $imp, 'clave' => $clave,'rodillo' => $_SESSION['INGRESO']['Impresora_Rodillo'],'factura'=>$FA['Factura'],'serie'=>$FA['Serie']);
						} else {
							$this->pdf->Imprimir_Punto_Venta_Grafico($TFA);
							return array('respuesta' => $resp, 'pdf' => $imp, 'clave' => $clave,'rodillo' => $_SESSION['INGRESO']['Impresora_Rodillo'],'factura'=>$FA['Factura'],'serie'=>$FA['Serie']);
						}

					} else {

						$respuesta = array('msj'=>$resp,'rodillo'=>$_SESSION['INGRESO']['Impresora_Rodillo'],'pdf'=> $imp,'factura'=>$FA['Factura'],'serie'=>$FA['Serie']); 

						return $respuesta; 

						// array('respuesta' => -1, 'pdf' => $imp, 'text' => $rep, 'clave' => $clave, 'respuesta_guia' => $rep1, 'pdf_guia' => $imp_guia, 'clave_guia' => $clave_guia, 'rodillo' => $_SESSION['INGRESO']['Impresora_Rodillo']);
					}
				}
			} else {
				$TFA = Imprimir_Punto_Venta_Grafico_datos($FA);
				$TFA['CLAVE'] = '.';
				//$TFA['PorcIva'] = $FA['Porc_IVA'];
				$TFA['PorcIva'] = $_SESSION['INGRESO']['porc'];
				$this->pdf->Imprimir_Punto_Venta($TFA);
				//Imprimir_Punto_Venta_Grafico($TFA);
				$imp = $FA['Serie'] . '-' . generaCeros($FA['Factura'], 7);
				$rep = 1;
				if ($rep == 1) {
					return array('respuesta' => $rep, 'pdf' => $imp);
				} else {
					return array('respuesta' => -1, 'pdf' => $imp, 'text' => $rep);
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


}