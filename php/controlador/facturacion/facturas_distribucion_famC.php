<?php
require(dirname(__DIR__, 2) . '/modelo/facturacion/facturas_distribucion_famM.php');
require_once(dirname(__DIR__, 2) . '/modelo/facturacion/punto_ventaM.php');
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

if(isset($_GET['cargarOrden'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->cargarOrden($parametros));
}

if (isset($_GET['LblSerieNDU'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->SerieNDU($parametros));
}
if (isset($_GET['finalizarFactura'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->finalizarFactura($parametros));
}

if (isset($_GET['DCEfectivo'])) {
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	echo json_encode($controlador->DCEfectivo($query));
}

if (isset($_GET['quitar_de_facturar'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->quitar_de_facturar($parametros));
}



class facturas_distribucion_fam
{
	private $modelo;
	private $sri;
	private $pdf;
	private $punto_venta;
	function __construct()
	{
		$this->modelo = new facturas_distribucion_famM();
		$this->sri = new autorizacion_sri();
		$this->pdf = new cabecera_pdf();
		$this->punto_venta =  new punto_ventaM();
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
		// print_r($parametros);die();
		$integrantes = $this->modelo->IntegrantesGrupo($parametros['grupo']);
		$datos = $this->modelo->cargar_asignacion($parametros['orden'],false,'F',$parametros['fechaPick']);
		foreach ($datos as $key => $value) {
			$datos[$key]['NumIntegrante'] = count($integrantes);
		}
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
		// print_r($parametros);die();

		$this->modelo->ProductosSisponible($parametros['orden'],'F');
		$electronico = 0;
		if (isset($parametros['electronico'])) {
			$electronico = $parametros['electronico'];
		}
		$datosFA['MBFecha'] = $parametros['fecha'];		
		$datosTick['MBFecha'] = $parametros['fecha'];

		$FechaTexto =  $parametros['fecha'];
		$datosFA['TC'] = $parametros['TipoFactura'];
		$datosFA['Serie'] = $parametros['Serie'];

		$datosTick['TC'] = $parametros['TipoNDU'];
		$datosTick['Serie'] = $parametros['Serie'];
		$datosFA['FacturaNo'] = $parametros['TextNDUNo'];

		$CodigoL = '.';
		$CodigoL2 = $this->modelo->catalogo_lineas($parametros['TC'], $parametros['Serie'], $FechaTexto, $FechaTexto, $electronico);
		if (count($CodigoL2) > 0) {
			$CodigoL = $CodigoL2[0]['Codigo'];
		}
		$integrantes = json_decode($parametros['integrantes'],true);
		$abonos = json_decode($parametros['Abonointegrantes'],true);

// print_r($abonos);die();

			
		$respuesta_final = array();

		foreach ($integrantes as $key => $value) {

			$cliente = Leer_Datos_Cliente_FA($key);
			$datosFA['CI'] = $key;
			$datosTick['CI'] = $key;
			$lineas = $value;
			$Ln_No = 1;
			// print_r($cliente);die();
			// $datosFA['T'] = $cliente['TB'];

			$datosFA['DCEfectivo'] =  $abonos[$key]['ctaEfectivo'];
			$datosFA['TxtEfectivo'] = $abonos[$key]['valorEfectivo'];
			$datosFA['TextBanco'] = $abonos[$key]['documento'];
			$datosFA['TextCheqNo'] =  $abonos[$key]['ctaBancos'];
			$datosFA['DCBancoC'] =  $abonos[$key]['ctaBancos'];
			$datosFA['CodDoc'] = '01';
			$datosFA['valorBan'] =  $abonos[$key]['valorBanco'];
			$datosFA['PorcIva'] = trim($parametros['PorcIva']);			
			$datosFA['TC'] = "FA";

			$Factura_No = 1;
			if($parametros['TC']=='NDU')
			{
				$Factura_No = ReadSetDataNum($parametros['TC'] . "_SERIE_" . $parametros['Serie'], True, true);
			}

			// print_r($Factura_No);die();
			// die();

			$datosFA['FacturaNo'] = $Factura_No;

			$producto = Leer_Codigo_Inv('FA.98',date('Y-m-d'));
			if($producto['respueta']!='1')
			{
				// no se encontro el producto que se estaba buscando
				return -3;
			}
			// else {
				$total = 0;
				$this->modelo->DeleteAsientoF($parametros['orden']);
				$cliente = Leer_Datos_Cliente_FA($datosFA['CI']);
				foreach ($lineas as $key2 => $value2) {
					$total = $total+$value2['total'];			
				}

				// print_r($cliente);die();

				SetAdoAddNew('Asiento_F');
				SetAdoFields('Codigo_Cliente', $cliente['CodigoC']);
				SetAdoFields('CODIGO', $producto['datos']['Codigo_Inv']);
				SetAdoFields('PRODUCTO', $producto['datos']['Producto']);
				SetAdoFields('Orden_No', $parametros['orden']);
				SetAdoFields('CodigoU', $_SESSION['INGRESO']['CodigoU']);
				SetAdoFields('A_No', 1);
				SetAdoFields('CANT', 1);
				SetAdoFields('PRECIO', number_format($total,$_SESSION['INGRESO']['Dec_PVP'],'.',''));
				SetAdoFields('TOTAL', number_format($total,2,'.',''));
				SetAdoFields('Serie',$parametros['Serie']);
				SetAdoFields('Item',$_SESSION['INGRESO']['item']);
				SetAdoUpdate();
				// print_r($datosFA);die();

				$factura =  $this->GenerarFacturaUni($datosFA);


			// print_r($factura);
			// die();

			$datosTick['FacturaNo'] = $Factura_No;			
			$datosTick['DCEfectivo'] = 0;
			$datosTick['TxtEfectivo'] =0;
			$datosTick['TextBanco'] = 0;
			$datosTick['TextCheqNo'] = 0;
			$datosTick['DCBancoC'] = '';
			$datosTick['CodDoc'] = '01';
			$datosTick['valorBan'] = 0;
			$datosTick['PorcIva'] = $parametros['PorcIva'];
			$datosFA['TC'] = "NDU";
			$datosTick['TC'] = "NDU";

			//insertya en trasn kardex

			$this->ingresar_trans_kardex_salidas_FA($lineas,$parametros,$factura['factura'],$factura['serie'],$datosFA['CI']);

			//genera lineas de tickes
			foreach ($lineas as $key2 => $value2) {
				$producto = Leer_Codigo_Inv($value2['Codigo'],$datosFA['MBFecha']);
				$cmds = $this->modelo->asignacion_familias($parametros['orden'],false,'F',false,$value2['Codigo']);
				if($producto['respueta']==1)
				{
					$producto = $producto['datos'];
					// print_r($producto);die();
					// print_r($value2);die();
					SetAdoAddNew("Asiento_F");
		            SetAdoFields("CODIGO", $value2['Codigo']);
		            SetAdoFields("CODIGO_L", $CodigoL);
		            SetAdoFields("PRODUCTO", $producto['Producto']);
		            SetAdoFields("CANT", $value2['cantidad']);
		            SetAdoFields("PRECIO", $value2['pvp']);
		            SetAdoFields("TOTAL", $value2['total']);
		            // SetAdoFields("Total_Desc", $value['Total_Desc']);
		            // SetAdoFields("Total_IVA", $value['Total_IVA']);
		            SetAdoFields("Serie_No", $datosFA['Serie'] );		            
		            SetAdoFields("CodBod", $cmds[0]['CodBodega']);
		            SetAdoFields("Costo", number_format($producto['Costo'],2,'.',''));
		            // SetAdoFields("Cta", $parametros['cta']);
		            SetAdoFields("Item", $_SESSION['INGRESO']['item']);
		            SetAdoFields("CodigoU", $_SESSION['INGRESO']['CodigoU']);
		            SetAdoFields("A_No", $Ln_No);
		            SetAdoFields("Cmds", $cmds[0]['Cmds']);
		            // SetAdoFields("Numero", $ordenP);
		            SetAdoUpdate();
		            $Ln_No = $Ln_No + 1;
	        	}
			}


			$Ticket = $this->GenerarTicketUni($datosTick);
			// $this->ingresar_trans_kardex_salidas_FA($integrantes);
			array_push($respuesta_final, $factura);
			$this->modelo->ActualizarCodigoFactura($parametros['TC'] . "_SERIE_" . $parametros['Serie'],$Factura_No);
		}

		return $respuesta_final;
		// print_r($respuesta_final);die();

	}

	function ingresar_trans_kardex_salidas_FA($lineas,$parametros,$factura,$serie,$codigo_p)
	{
		// print_r($lineas);
		// print_r($parametros);die();

		$productosDistribuidos = json_decode($parametros['integrantes'],true);

		$listadoK = array();

			$lineasKardex = array();
			foreach ($lineas as $key2 => $value2) {
				$cantidad = $value2['cantidad'];
				$CodigoProducto = $this->modelo->cargar_asignacion($parametros['orden'],$tipo=false,'F',$parametros['fechaPick'],$value2['Codigo']);
				$cmds = $this->modelo->asignacion_familias($parametros['orden'],false,'F',false,$value2['Codigo']);

				$i = 0;
				while ($cantidad!=0) {
					$disponible = $CodigoProducto[$i]['Porc'];
					if($disponible>0)
					{
						if($disponible>=$cantidad)
						{
							$tomado = $cantidad;
							$cantidad = 0;
						}
						else if($disponible<$cantidad)
						{
							$cantidad = $cantidad-$disponible;
							$tomado = $disponible;
						}
						
						if($cantidad>=0)
						{
							$dispo = $disponible-$tomado;
							$this->modelo->ACtualizarDisponibilidad($parametros['orden'],'F',$CodigoProducto[$i]['CodBodega'],$dispo);

							$lines = array('CodBarras'=>$CodigoProducto[$i]['Codigo_Barra'],'cantidad'=>$tomado,'Valor_Unitario'=>$value2['pvp'],'codigo_Inv'=>$value2['Codigo'],'fecha'=>$parametros['fecha'],'Cmds'=>$cmds[0]['Cmds'],'CodBodega'=>$cmds[0]['CodBodega'],'Codigo_P'=>$codigo_p);
							array_push($lineasKardex, $lines);
							// $i++;
						}
					}
					$i++;
				}				
			}

			// print_r($lineasKardex);die();
			// $listadoK[$key] = $lineasKardex;
	


		// print_r($lineasKardex);die();



		$resp = 1;
		$lista = '';
		// foreach ($listadoK as $key => $value) {

			$lineas = $lineasKardex;
			foreach ($lineas as $key2 => $value2) {
								
				SetAdoAddNew('Trans_Kardex');
				SetAdoFields('Numero', 0);
				SetAdoFields('T', 'N');
				SetAdoFields('TP', '.');
				SetAdoFields('Costo', number_format($value2['Valor_Unitario'], $_SESSION['INGRESO']['Dec_PVP'], '.', ''));
				SetAdoFields('Total', number_format(($value2['Valor_Unitario']*$value2['cantidad']), 2, '.', ''));
				SetAdoFields('Codigo_P', $value2['Codigo_P']);
				SetAdoFields('CodBodega', $value2['CodBodega']);
				SetAdoFields('Detalle', 'Salida de inventario (FA) para  con FACTURA : ' .  $factura . ' el dia ' . $value2['fecha']);
				SetAdoFields('Procesado', 0);
				// SetAdoFields('Total_IVA', number_format($value['Total_IVA'], 2, '.', ''));
				SetAdoFields('Codigo_Inv', $value2['codigo_Inv']);
				SetAdoFields('Salida', $value2['cantidad']);
				SetAdoFields('Valor_Unitario', number_format($value2['Valor_Unitario'], $_SESSION['INGRESO']['Dec_PVP'], '.', ''));
				SetAdoFields('Valor_Total', number_format(($value2['Valor_Unitario']*$value2['cantidad']), 2, '.', ''));
				SetAdoFields('CodigoU', $_SESSION['INGRESO']['CodigoU']);
				SetAdoFields('Item', $_SESSION['INGRESO']['item']);
				SetAdoFields('Periodo', $_SESSION['INGRESO']['periodo']);
				SetAdoFields('Factura', $factura);
				SetAdoFields('Codigo_Barra', $value2['CodBarras']);
				SetAdoFields('TC','FA');
				SetAdoFields('Serie',$serie);
				SetAdoFields("Cmds",$value2['Cmds']);
				SetAdoUpdate();

			}

		// }
		// print_r($resp);die();
		return $resp;

	}





	function GenerarFacturaUni($parametros)
	{
		
		$electronico = 0;
		if (isset($parametros['electronico'])) {
			$electronico = $parametros['electronico'];
		}

		// FechaValida MBFecha
		$FechaTexto = $parametros['MBFecha'];
		$FA = Calculos_Totales_Factura();
		$datos = $this->modelo->catalogo_lineas($parametros['TC'], $parametros['Serie'], $FechaTexto, $FechaTexto, $electronico);
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
			// $FA['T'] = $parametros['T'];
			$FA['CtaEfectivo'] = $parametros['DCEfectivo'];
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

			$Moneda_US = False;
			$TextoFormaPago = G_PAGOCONT;

			// print_r($FA);die();
			$resp =  $this->ProcGrabar($FA);
			return $resp;
		} else {
			return array('respuesta' => -1, 'text' => "Cuenta CxC sin setear en catalogo de lineas");
		}
	}


	function GenerarTicketUni($parametros)
	{
		
		$electronico = 0;
		if (isset($parametros['electronico'])) {
			$electronico = $parametros['electronico'];
		}

		// FechaValida MBFecha
		$FechaTexto = $parametros['MBFecha'];
		$FA = Calculos_Totales_Factura();
		$datos = $this->modelo->catalogo_lineas($parametros['TC'], $parametros['Serie'], $FechaTexto, $FechaTexto, $electronico);
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
			// $FA['T'] = $parametros['T'];
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

			$Moneda_US = False;
			$TextoFormaPago = G_PAGOCONT;

			// print_r($FA);die();
			$resp =  $this->ProcGrabar($FA);
			// print_r($resp);die();
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

			// if($FA['TC']=='NDU')
			// {
			// 	$Factura_No = ReadSetDataNum($FA['TC'] . "_SERIE_" . $FA['Serie'], True, True);
			// }else{ 
			// 	$Factura_No = $FA['FacturaNo']; 
			// 	$this->modelo->ActualizarCodigoFactura($FA['TC'] . "_SERIE_" . $FA['Serie'],$Factura_No);

			// 	}


			$FA['Factura'] =$Factura_No;
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
			}else if ($TipoFactura == "NDU") {
				Control_Procesos("F", "Grabar Nota de Donacion No. " . $Factura_No, '');
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
				if ($FA['TC'] <> "DO" && $FA['TC'] <> "NDU") {
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
				// print_r('dddd');die();
				
							
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




	function cargarOrden($parametros)
    {
        // print_r($parametros);die();
        $tr = '';
        $cantidad = 0;
        $res = array();
        $datos = $this->modelo->listaAsignacion($parametros['pedido'],'KF',false,false,false);
        $integrantes = $this->modelo->IntegrantesGrupo($parametros['grupo']);
        // print_r($integrantes);die();

        $detalle = '';
        $ddlGrupoPro = '';
        $total = 0;
        $ctotal = 0;
        return $datos;
    }

    function SerieNDU($parametros)
	{
		$parametros['TipoFactura'] = $parametros['TC'];
		$datosAdoLinea = $this->modelo->AdoLinea($parametros);
		// print_r($datosAdoLinea);die();
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
						return $mensaje;
		}
		$NumComp = ReadSetDataNum($parametros['TC']."_SERIE_" . $serie, false, False);
		return array('mensaje' => $mensaje, 'serie' => $serie, 'NumCom' => generaCeros($NumComp, 9), 'CodigoL' => $CodigoL, 'Cta_Cobrar' => $Cta_Cobrar, 'Autorizacion' => $Autorizacion);
	}

	function finalizarFactura($parametros)
	{
		SetAdoAddNew("Detalle_Factura");
		SetAdoFields("T",'N');
		SetAdoFieldsWhere('Orden_No',$parametros['orden']);
		return  SetAdoUpdateGeneric();
		    
	}
	function DCEfectivo($query)
	{
		$datos = $this->modelo->DCEfectivo($query);
		// print_r($datos);die();
		foreach ($datos as $key => $value) {
			$res[] = array('id' => $value['Codigo'], 'text' => $value['NomCuenta']);
		}
		return $res;
	}

	function quitar_de_facturar($parametros)
	{
		SetAdoAddNew("Detalle_Factura");
		SetAdoFields("T",'K');
		SetAdoFieldsWhere('Orden_No',$parametros['orden']);
		SetAdoUpdateGeneric();

		SetAdoAddNew("Trans_Comision");
		SetAdoFields("T",'P');
		SetAdoFieldsWhere('Orden_No',$parametros['orden']);
		return SetAdoUpdateGeneric();
	}



}

?>