<?php
$tipo = 2; //Se usa para saber que debe regresar dos carpetas en chequear_seguridad
require_once(dirname(__DIR__, 2) . "/db/chequear_seguridad.php");
require_once(dirname(__DIR__, 2) . "/modelo/facturacion/lista_facturasM.php");
require_once(dirname(__DIR__, 2) . "/modelo/facturacion/punto_ventaM.php");
require(dirname(__DIR__, 3) . '/lib/fpdf/cabecera_pdf.php');
if (!class_exists('enviar_emails')) {
	require_once(dirname(__DIR__, 3) . '/lib/phpmailer/enviar_emails.php');
}

$controlador = new lista_facturasC();
if (isset($_GET['tabla'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->tabla_facturas($parametros));
}
if (isset($_GET['tabla_factura_electronica'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->tabla_factura_electronica($parametros));
}
if (isset($_GET['tabla_lineas'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->tabla_facturas_lineas($parametros));
}
if (isset($_GET['tablaAu'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->tabla_facturas($parametros));
}
if (isset($_GET['perido'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->factura_periodo($parametros));
}
if (isset($_GET['ver_fac'])) {
	// print_r('sss');die();
	$controlador->ver_fac_pdf($_GET['codigo'], $_GET['ser'], $_GET['ci'], $_GET['per'], $_GET['auto']);
}
if (isset($_GET['imprimir_pdf'])) {
	$parametros = $_GET;
	$controlador->imprimir_pdf($parametros);
}
if (isset($_GET['imprimir_pdf_lineas'])) {
	$parametros = $_GET;
	$controlador->imprimir_pdf_lineas($parametros);
}
if (isset($_GET['imprimir_excel'])) {
	$parametros = $_GET;
	$controlador->imprimir_excel($parametros);
}
if (isset($_GET['imprimir_excel_fac'])) {
	$parametros = $_GET;
	$controlador->imprimir_excel_fac($parametros);
}
if (isset($_GET['imprimir_excel_factura_electronica'])) {
	$parametros = $_GET;
	$controlador->imprimir_excel_factura_electronica($parametros); //By Leo
}
if (isset($_GET['imprimir_pdf_factura_electronica'])) {
	$parametros = $_GET;
	$controlador->imprimir_pdf_factura_electronica($parametros); //By Leo
}
if (isset($_GET['imprimir_excel_fac_line'])) {
	$parametros = $_GET;
	$controlador->imprimir_excel_fac_line($parametros);
}
if (isset($_GET['grupos'])) {
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	echo json_encode($controlador->grupos($query));
}
if (isset($_GET['clientes'])) {
	$query = '';
	$grupo = $_GET['g'];
	$cartera = 0;
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	$estado = $_GET['estado'];
	echo json_encode($controlador->clientes2_x_grupo($query, $grupo, $estado));

	// if (isset($_GET['cartera'])) {
	// 	$cartera = $_GET['cartera'];
	// }
	// echo json_encode($controlador->clientes_x_grupo($query, $grupo,$cartera));
}

if (isset($_GET['clientes2'])) {
	$query = '';
	$grupo = $_GET['g'];
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}	
	$cartera = 0;
	if (isset($_GET['cartera'])) {
		$cartera = $_GET['cartera'];
	}
	echo json_encode($controlador->clientes_x_grupo($query, $grupo,$cartera));
	// $estado = $_GET['estado'];
	// echo json_encode($controlador->clientes2_x_grupo($query, $grupo, $estado));
}
if (isset($_GET['clientes_datos'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->clientes_datos($parametros));
}

if (isset($_GET['validar'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->validar_cliente($parametros));
}

if (isset($_GET['enviar_mail'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->enviar_mail($parametros));
}
if (isset($_GET['re_autorizar'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->autorizar($parametros));
}
if (isset($_GET['autorizar_bloque'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->autorizar_bloque($parametros));
}
if (isset($_GET['Anular'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->anular($parametros));
}
if (isset($_GET['enviar_email_detalle'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->enviar_email_detalle($parametros));
}

if (isset($_GET['descargar_factura'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->descargar_factura($parametros));
}

if (isset($_GET['descargar_xml'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->descargar_xml($parametros));
}
if (isset($_GET['generar_xml'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->generar_xml($parametros));
}

class lista_facturasC
{
	private $modelo;
	private $email;
	public $pdf;
	private $punto_venta;
	private $empresaGeneral;
	private $sri;

	public function __construct()
	{
		$this->modelo = new lista_facturasM();
		$this->pdf = new cabecera_pdf();
		$this->email = new enviar_emails();
		$this->empresaGeneral = $this->modelo->Empresa_data();
		$this->sri = new autorizacion_sri();
		$this->punto_venta = new punto_ventaM();
		//$this->modelo = new MesaModel();
	}


	function tabla_facturas($parametros)
	{
		$autorizados = false;
		if (isset($parametros['auto'])) {
			$autorizados = $parametros['auto'];
		}
		// print_r($parametros);die();
		$codigo = $parametros['ci'];

		$tbl = $this->modelo->facturas_emitidas_tabla($codigo, $parametros['per'], $parametros['desde'], $parametros['hasta'], $parametros['serie'], $autorizados);
		$tr = '';
		foreach ($tbl as $key => $value) {
			$exis = $this->modelo->catalogo_lineas($value['TC'], $value['Serie']);
			$autorizar = '';
			$anular = '';
			$cli_data = $this->modelo->Cliente($value['CodigoC']);
			$email = '';
			if (count($cli_data) > 0) {
				if ($cli_data[0]['Email'] != '.' && $cli_data[0]['Email'] != '') {
					$email .= $cli_data[0]['Email'] . ',';
				}
				if ($cli_data[0]['EmailR'] != '.' && $cli_data[0]['EmailR'] != '') {
					$email .= $cli_data[0]['EmailR'] . ',';
				}
				if ($cli_data[0]['Email2'] != '.' && $cli_data[0]['Email2'] != '') {
					$email .= $cli_data[0]['Email2'] . ',';
				}
			}

			$tr .= '<tr>
            <td>
            <div class="input-group-btn">
								<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones
								<span class="fa fa-caret-down"></span></button>
								<ul class="dropdown-menu">
								<li><a href="#" onclick="Ver_factura(\'' . $value['Factura'] . '\',\'' . $value['Serie'] . '\',\'' . $value['CodigoC'] . '\',\'' . $value['Autorizacion'] . '\')"><i class="fa fa-eye"></i> Ver factura</a></li>';
			if (count($exis) > 0 && strlen($value['Autorizacion']) == 13 && $parametros['tipo'] != '') {
				$tr .= '<li><a href="#" onclick="autorizar(\'' . $value['TC'] . '\',\'' . $value['Factura'] . '\',\'' . $value['Serie'] . '\',\'' . $value['Fecha']->format('Y-m-d') . '\',\'' . $email. '\')" ><i class="fa fa-paper-plane"></i>Autorizar</a></li>';
			}
			if ($value['T'] != 'A' && $parametros['tipo'] != '') {
				$tr .= '<li><a href="#" onclick="anular_factura(\'' . $value['Factura'] . '\',\'' . $value['Serie'] . '\',\'' . $value['CodigoC'] . '\')"><i class="fa fa-times-circle"></i>Anular factura</a></li>';
			}
			$tr .= '<li><a href="#" onclick=" modal_email_fac(\'' . $value['Factura'] . '\',\'' . $value['Serie'] . '\',\'' . $value['CodigoC'] . '\',\'' . $email . '\')"><i class="fa fa-envelope"></i> Enviar Factura por email</a></li>
								<li><a href="#" onclick="descargar_fac(\'' . $value['Factura'] . '\',\'' . $value['Serie'] . '\',\'' . $value['CodigoC'] . '\')"><i class="fa fa-download"></i> Descargar Factura</a></li>';
			if (strlen($value['Autorizacion']) > 13) {
				$tr .= '<li><a href="#" onclick="descargar_xml(\'' . $value['Autorizacion'] . '\')"><i class="fa fa-download"></i> Descargar XML</a></li>';
			}else if(strlen($value['Autorizacion']) <= 13 && $value['T'] != 'A')
			{
				$tr .= '<li><a href="#" onclick="generar_xml(\'' . $value['Factura'] . '\',\'' . $value['Serie'] . '\',\'' . $value['TC'] . '\')"><i class="fa fa-download"></i> Generar XML</a></li>';
			}
			$tr .= '
								</ul>
						</div>


            </td>
            <td>' . $value['T'] . '</td>
            <td>' . $value['Razon_Social'] . '</td>
            <td>' . $value['TC'] . '</td>
            <td>' . $value['Serie'] . '</td>
            <td>' . $value['Autorizacion'] . '</td>
            <td>' . $value['Factura'] . '</td>
            <td>' . $value['Fecha']->format('Y-m-d') . '</td>           
            <td class="text-right">' . $value['SubTotal'] . '</td>
            <td class="text-right">' . $value['Con_IVA'] . '</td>
            <td class="text-right">' . $value['IVA'] . '</td>
            <td class="text-right">' . $value['Descuentos'] . '</td>
            <td class="text-right">' . $value['Total'] . '</td>
            <td class="text-right">' . $value['Saldo'] . '</td>
            <td>' . $value['RUC_CI'] . '</td>
            <td>' . $value['TB'] . '</td>
          </tr>';
		}

		// print_r($tr);die();

		return $tr;
	}

	function tabla_factura_electronica($parametros)
	{ //By Leo
		$codigoC = $parametros['ci'] != 'T' ? $parametros['ci'] : ''; //if de una linea.
		$serie = $parametros['serie'] != '' ? $parametros['serie'] : '';
		$desde = $parametros['desde'];
		$hasta = $parametros['hasta'];
		$estado = $parametros['estado'];
		//print_r($parametros);die();
		$resultado = $this->modelo->Cliente_facturas_electronicas($desde, $hasta, $estado, $codigoC, $serie);
		//die();

		if (empty($resultado)) {
			return '0';
		}

		$tablaHtml = '
		<table>
			<thead id="cabecera">
				<tr><td></td>';

		$columnas = array_keys($resultado[0]); //Obtenemos cuantas columnas hay que mostrar a partir del primer resultado

		foreach ($columnas as $columna) {
			$tablaHtml .= '<th>' . $columna . '</th>'; //Generamos las columnas con la respectiva etiqueta
		}

		$tablaHtml .= '
				</tr>
			</thead>
			<tbody>';

		foreach ($resultado as $fila) {
			$i = '';
			$tablaHtml .= '<tr><td><button type="button" class="btn btn-default btn-xs" onclick="Ver_factura(\''.$fila['Factura'].'\',\''.$fila['Serie'].'\',\''.$fila['Codigo'].'\',\''.$fila['Autorizacion'].'\')"><i class="fa fa-eye"></i></button></td>';
			foreach ($columnas as $columna) {
				$valor = $fila[$columna];
				$clase = '';
				if (is_numeric($valor)) {
					$clase = "text-right";
				} elseif ($valor instanceof DateTime) {
					$valor = $valor->format('Y-m-d');
				}
				$tablaHtml .= '<td class="' . $clase . '">' . $valor . '</td>';
			}
			$tablaHtml .= '</tr>';
		}
		$tablaHtml .= '
			</tbody>
		</table>';

		$Sum_SaldoMN = 0;
		foreach ($resultado as $fila) {
			if (isset($fila['Saldo_MN']) && is_numeric($fila['Saldo_MN'])) {
				$Sum_SaldoMN += $fila['Saldo_MN'];
			}
		}

		return array('tabla' => $tablaHtml, 'Sum_SaldoMN' => number_format($Sum_SaldoMN, 2, '.', ','));
	}

	function tabla_facturas_lineas($parametros)
	{
		$autorizados = false;
		if (isset($parametros['auto'])) {
			$autorizados = $parametros['auto'];
		}
		// print_r($parametros);die();
		$codigo = $parametros['ci'];

		$tbl = $this->modelo->facturas_emitidas_tabla($codigo, $parametros['per'], $parametros['desde'], $parametros['hasta'], $parametros['serie'], $autorizados);
		$tr = '';
		foreach ($tbl as $key => $value) {

			$line = $this->modelo->facturas_lineas_emitidas_tabla($value['CodigoC'], $value['Serie'], false, $value['Factura']);
			foreach ($line as $key => $value2) {
				$tr .= '<tr>            
	            <td>' . $value2['T'] . '</td>
	            <td>' . $value2['Producto'] . '</td>
	            <td>' . $value2['TC'] . '</td>
	            <td>' . $value2['Serie'] . '</td>
	            <td>' . $value2['Autorizacion'] . '</td>
	            <td>' . $value2['Factura'] . '</td>
	            <td>' . $value2['Fecha']->format('Y-m-d') . '</td>    
	            <td>' . $value2['Mes'] . '</td>    
	            <td>' . $value2['Ticket'] . '</td>           
	            <td class="text-right">' . $value2['Total_IVA'] . '</td>
	            <td class="text-right">' . $value2['Total_Desc'] . '</td>
	            <td class="text-right">' . $value2['Total'] . '</td>
	            <td>' . $value2['CodigoC'] . '</td>
	          </tr>';
			}
			// print_r($tr);die();

		}

		// print_r($tr);die();

		return $tr;
	}
	function factura_periodo($parametros)
	{
		$datos = $this->modelo->facturas_perido($parametros['codigo']);
		$opcion = '';
		foreach ($datos as $key => $value) {
			$year = '.';
			if ($value['Periodo'] != '.') {
				$year = explode('/', $value['Periodo']);
				$year = $year[2];
			}
			$opcion .= '<option value="' . $year . '">' . $year . '</option>';
		}
		return $opcion;
	}
	function ver_fac_pdf($cod, $ser, $ci, $per, $auto)
	{
		// print_r($cod);die();
		$nombre = $ser . '-' . generaCeros($cod, 7);
		if ($_SESSION['INGRESO']['Impresora_Rodillo'] == 0) {
			$this->punto_venta->pdf_factura_elec($cod, $ser, $ci, $nombre, $auto, $per, $aprobado = false);
		} else {
			// print_r('expression');die();
			$this->punto_venta->pdf_factura_elec_rodillo($cod, $ser, $ci, $nombre, $auto, $per, $aprobado = false);
		}
		// $this->modelo->pdf_factura($cod,$ser,$ci,$per);

	}
	function imprimir_pdf($parametros)
	{
		// print_r($parametros);die();
		$serie = explode(' ', $parametros['DCLinea']);
		$serie = (isset($serie[1])) ? $serie[1] : false;
		$codigo = $parametros['ddl_cliente'];
		$tbl = $this->modelo->facturas_emitidas_tabla($codigo, $parametros['ddl_periodo'], $parametros['txt_desde'], $parametros['txt_hasta'], $serie);


		$titulo = 'L I S T A  D E  F A C T U R A S';
		$sizetable = 7;
		$mostrar = TRUE;
		// $Fechaini = $parametros['txt_desde'] ;//str_replace('-','',$parametros['Fechaini']);
		// $Fechafin = $parametros['txt_hasta']; //str_replace('-','',$parametros['Fechafin']);
		$tablaHTML = array();
		$pos = 0;
		$borde = 1;
		// print_r($datos);die();
		$pos = 1;
		$tablaHTML[0]['medidas'] = array(7, 10, 15, 50, 15, 20, 15, 15, 15, 15, 15, 20, 7, 50, 15);
		$tablaHTML[0]['alineado'] = array('L', 'L', 'L', 'L', 'L', 'L', 'R', 'R', 'R', 'R', 'R', 'L', 'L', 'L', 'L');
		$tablaHTML[0]['datos'] = array('T', 'TC', 'Serie', 'Autorizacion', 'Factura', 'Fecha', 'SubTotal', 'Con Iva', 'IVA', 'Total', 'Saldo', 'Ruc', 'TB', 'Razon social', 'ID');
		$tablaHTML[0]['borde'] = $borde;
		$tablaHTML[0]['estilo'] = 'b';

		$datos = $tbl;

		foreach ($datos as $key => $value) {

			$tablaHTML[$pos]['medidas'] = $tablaHTML[0]['medidas'];
			$tablaHTML[$pos]['alineado'] = $tablaHTML[0]['alineado'];
			$tablaHTML[$pos]['datos'] = array($value['T'], $value['TC'], $value['Serie'], $value['Autorizacion'] . ' ', $value['Factura'], $value['Fecha']->format('Y-m-d'), $value['SubTotal'], $value['Con_IVA'], $value['IVA'], $value['Total'], $value['Saldo'], $value['RUC_CI'], $value['TB'], $value['Razon_Social'], $value['ID']);
			$tablaHTML[$pos]['borde'] = $borde;
			$pos += 1;
		}

		$this->pdf->cabecera_reporte_MC($titulo, $tablaHTML, $contenido = false, $image = false, $Fechaini = false, $Fechafin = false, $sizetable, $mostrar, 15, 'H');
	}

	function imprimir_pdf_factura_electronica($parametros)
	{ //By Leo
		//print_r($parametros);die();

		$codigoC = $parametros['codigoC'] != 'T' ? $parametros['codigoC'] : '';
		$serie = $parametros['serie'] != '' ? $parametros['serie'] : '';
		$desde = $parametros['desde'];
		$hasta = $parametros['hasta'];
		$estado = $parametros['estado'];

		$tbl = $this->modelo->Cliente_facturas_electronicas($desde, $hasta, $estado, $codigoC, $serie);
		//print_r($tbl);die();


		$titulo = 'L I S T A  D E  F A C T U R A S';
		$sizetable = 7;
		$mostrar = TRUE;

		$cantidadDatos = count($tbl[0]); //Obtenemos cuantas columnas necesitamos
		$columnas = array_keys($tbl[0]); //Obtenemos las columnas

		$tablaHTML = array();
		$medidas = array();
		$alineado = array();
		
		for ($i = 1; $i <= $cantidadDatos; $i++) {
			$medidas[] = 23;
			$alineado[] = 'L';
		}

		$tablaHTML[0]['medidas'] = $medidas;
		$tablaHTML[0]['alineado'] = $alineado;
		$tablaHTML[0]['datos'] = $columnas; // Columnas a mostrar
		$tablaHTML[0]['borde'] = 1;
		$tablaHTML[0]['estilo'] = 'b';

		$datos = $tbl;

		$cliente_anterior = '';
		$cliente_actual = '';
		$total = 0;
		foreach ($datos as $key => $value) {
			$tmp = array();
			$tmpTotal = array();
			// $cliente_actual = $value['CI_RUC'];
			// if($cliente_anterior==''){ $cliente_anterior= $value['CI_RUC'];}
			// if($cliente_anterior==$cliente_actual){
				$total+=$value['Saldo_MN'];
			// }
			foreach ($columnas as $columna) {
				$valor = $value[$columna];

				if ($valor instanceof DateTime) {
					$valor = $valor->format('Y-m-d');
				}	
				
				$tmp[] = $valor;
			}
			// if($cliente_anterior!=$cliente_actual)
			// {
			// 	//utima linea total
			// 	$i = 0;
			// 	foreach ($columnas as $columna) {
			// 		$valor = $i == 0 ? 'TOTAL' : '';
			// 		if($columna=='Saldo_MN'){$valor = $total;}
					
			// 		$tmpTotal[] = $valor;
			// 		$i++;
			// 	}
			// 	$tablaHTML[] = array(
			// 		'medidas' => $medidas,
			// 		'alineado' => $alineado,
			// 		'datos' => $tmpTotal,
			// 		'borde' => 1
			// 	);
			// 	$total = $value['Saldo_MN'];
			// 	$cliente_anterior= $value['CI_RUC'];
			// 	// print_r($tablaHTML);die();
			// }
			$tablaHTML[] = array(
				'medidas' => $medidas,
				'alineado' => $alineado,
				'datos' => $tmp,
				'borde' => 1
			);
		}

		$i = 0;
		foreach ($columnas as $columna) {
			$valor = $i == 0 ? 'TOTAL' : '';
			if($columna=='Saldo_MN'){$valor = $total;}
			
			$tmpTotal[] = $valor;
			$i++;
		}
		$tablaHTML[] = array(
			'medidas' => $medidas,
			'alineado' => $alineado,
			'datos' => $tmpTotal,
			'borde' => 1
		);


		$this->pdf->cabecera_reporte_MC($titulo, $tablaHTML, $contenido = false, $image = false, $Fechaini = false, $Fechafin = false, $sizetable, $mostrar, 15, 'H');
	}

	function imprimir_pdf_lineas($parametros)
	{
		// print_r($parametros);die();
		$serie = explode(' ', $parametros['DCLinea']);
		$serie = (isset($serie[1])) ? $serie[1] : false;
		$codigo = $parametros['ddl_cliente'];
		$tbl = $this->modelo->facturas_emitidas_tabla($codigo, $parametros['ddl_periodo'], $parametros['txt_desde'], $parametros['txt_hasta'], $serie);


		$titulo = 'L I N E A S  D E  F A C T U R A S';
		$sizetable = 7;
		$mostrar = TRUE;
		// $Fechaini = $parametros['txt_desde'] ;//str_replace('-','',$parametros['Fechaini']);
		// $Fechafin = $parametros['txt_hasta']; //str_replace('-','',$parametros['Fechafin']);
		$tablaHTML = array();
		$pos = 0;
		$borde = 1;
		// print_r($datos);die();
		$pos = 1;
		$tablaHTML[0]['medidas'] = array(6, 50, 9, 15, 75, 12, 18, 20, 12, 12, 20, 12, 20);
		$tablaHTML[0]['datos'] = array('T', 'Producto', 'TC', 'Serie', 'Autorizacion', 'Factura', 'Fecha', 'Mes', 'Año', 'IVA', 'Descuentos', 'Total', 'RUC_CI');
		$tablaHTML[0]['alineado'] = array('L', 'L', 'L', 'L', 'L', 'L', 'L', 'L', 'R', 'R', 'R', 'L', 'L', 'L', 'L');
		$tablaHTML[0]['borde'] = $borde;
		$tablaHTML[0]['estilo'] = 'b';

		$datos = $tbl;

		foreach ($datos as $key => $value) {
			$line = $this->modelo->facturas_lineas_emitidas_tabla($value['CodigoC'], $value['Serie'], false, $value['Factura']);
			foreach ($line as $key => $value2) {
				$tablaHTML[$pos]['medidas'] = $tablaHTML[0]['medidas'];
				$tablaHTML[$pos]['alineado'] = $tablaHTML[0]['alineado'];
				$tablaHTML[$pos]['datos'] = array($value2['T'], $value2['Producto'], $value2['TC'], $value2['Serie'], $value2['Autorizacion'], $value2['Factura'], $value2['Fecha']->format('Y-m-d'), $value2['Mes'], $value2['Ticket'], $value2['Total_IVA'], $value2['Total_Desc'], $value2['Total'], $value2['CodigoC']);
				$tablaHTML[$pos]['borde'] = $borde;
				$pos += 1;
			}

		}

		$this->pdf->cabecera_reporte_MC($titulo, $tablaHTML, $contenido = false, $image = false, $Fechaini = false, $Fechafin = false, $sizetable, $mostrar, 15, 'H');
	}


	function imprimir_excel_fac($parametros)
	{
		$serie = explode(' ', $parametros['DCLinea']);
		$serie = $serie[1];
		$codigo = $parametros['ddl_cliente'];
		$tbl = $this->modelo->facturas_emitidas_tabla($codigo, $parametros['ddl_periodo'], $parametros['txt_desde'], $parametros['txt_hasta'], $serie);

		$b = 1;
		$titulo = 'F A C T U R A S   E M I T I D A S';
		if ($tc == 'LC') {
			$titulo = 'L I Q U I D A C I O N   D E  C O M P R A S   E M I T I D A S';
		}
		$tablaHTML = array();
		$tablaHTML[0]['medidas'] = array(6, 6, 13, 25, 15, 23, 18, 12, 12, 12, 12, 12, 12, 6, 20);
		$tablaHTML[0]['datos'] = array('T', 'TC', 'Serie', 'Autorizacion', 'Factura', 'Fecha', 'SubTotal', 'Con_IVA', 'IVA', 'Descuentos', 'Total', 'Saldo', 'RUC_CI', 'TB', 'Razon_Social');
		$tablaHTML[0]['tipo'] = 'C';
		$pos = 1;
		$compro1 = '';
		foreach ($tbl as $key => $value) {
			$tablaHTML[$pos]['medidas'] = $tablaHTML[0]['medidas'];
			$tablaHTML[$pos]['datos'] = array($value['T'], $value['TC'], $value['Serie'], $value['Autorizacion'], $value['Factura'], $value['Fecha']->format('Y-m-d'), $value['SubTotal'], $value['Con_IVA'], $value['IVA'], $value['Descuentos'], $value['Total'], $value['Saldo'], $value['RUC_CI'], $value['TB'], $value['Razon_Social']);
			$tablaHTML[$pos]['tipo'] = 'N';
			$pos += 1;
		}
		excel_generico($titulo, $tablaHTML);

	}

	function imprimir_excel_factura_electronica($parametros) //By Leo
	{
		$codigoC = $parametros['codigoC'] != 'T' ? $parametros['codigoC'] : '';
		$serie = $parametros['serie'] != '' ? $parametros['serie'] : '';
		$desde = $parametros['desde'];
		$hasta = $parametros['hasta'];
		$estado = $parametros['estado'];
		//print_r($parametros);die();
		$tbl = $this->modelo->Cliente_facturas_electronicas($desde, $hasta, $estado, $codigoC, $serie);

		$b = 1;
		$titulo = 'F A C T U R A S   E M I T I D A S';

		$cantidadDatos = count($tbl[0]); //Obtenemos cuantas columnas necesitamos
		$columnas = array_keys($tbl[0]); //Obtenemos las columnas 
		$tablaHTML = array();
		$medidas = array();

		for ($i = 1; $i <= $cantidadDatos; $i++) {
			$medidas[] = 10;
		}

		$tablaHTML[0]['medidas'] = $medidas;
		$tablaHTML[0]['datos'] = $columnas;
		$tablaHTML[0]['tipo'] = 'C';

		$total = 0;
		foreach ($tbl as $key => $value) {
			
			$tmp = array();
			$tmpTotal = array();
			$total+=$value['Saldo_MN'];

			foreach ($columnas as $columna) {
				$valor = $value[$columna];

				if ($valor instanceof DateTime) {
					$valor = $valor->format('Y-m-d');
				}

				$tmp[] = $valor;
			}

			$tablaHTML[] = array(
				'medidas' => $medidas,
				'datos' => $tmp,
				'tipo' => 'N'
			);
		}

		$i = 0;
		foreach ($columnas as $columna) {
			$valor = $i == 0 ? 'TOTAL' : '';
			if($columna=='Saldo_MN'){$valor = $total;}
			
			$tmpTotal[] = $valor;
			$i++;
		}
		$tablaHTML[] = array(
			'medidas' => $medidas,
			'datos' => $tmpTotal,
			'tipo' => 'N'
		);


		excel_generico(str_replace(' ','',$titulo), $tablaHTML);

	}

	function imprimir_excel_fac_line($parametros)
	{
		$serie = explode(' ', $parametros['DCLinea']);
		$serie = $serie[1];
		$codigo = $parametros['ddl_cliente'];
		$tbl = $this->modelo->facturas_emitidas_tabla($codigo, $parametros['ddl_periodo'], $parametros['txt_desde'], $parametros['txt_hasta'], $serie);

		$b = 1;
		$titulo = 'F A C T U R A S  L I N E A S';

		$tablaHTML = array();
		$tablaHTML[0]['medidas'] = array(6, 20, 13, 25, 15, 23, 18, 12, 12, 12, 12, 12, 12, 20);
		$tablaHTML[0]['datos'] = array('T', 'Producto', 'TC', 'Serie', 'Autorizacion', 'Factura', 'Fecha', 'Mes', 'Año', 'IVA', 'Descuentos', 'Total', 'RUC_CI');
		$tablaHTML[0]['tipo'] = 'C';
		$pos = 1;
		$compro1 = '';
		foreach ($tbl as $key => $value) {

			$line = $this->modelo->facturas_lineas_emitidas_tabla($value['CodigoC'], $value['Serie'], false, $value['Factura']);
			foreach ($line as $key => $value2) {
				$tablaHTML[$pos]['medidas'] = $tablaHTML[0]['medidas'];
				$tablaHTML[$pos]['datos'] = array($value2['T'], $value2['Producto'], $value2['TC'], $value2['Serie'], $value2['Autorizacion'], $value2['Factura'], $value2['Fecha']->format('Y-m-d'), $value2['Mes'], $value2['Ticket'], $value2['Total_IVA'], $value2['Total_Desc'], $value2['Total'], $value2['CodigoC']);
				$tablaHTML[$pos]['tipo'] = 'N';
				$pos += 1;
			}


		}
		excel_generico($titulo, $tablaHTML);

	}

	function imprimir_excel($parametros)
	{
		print_r($parametros);
		die();
		$empresa = explode('_', $parametros['ddl_entidad']);
		$parametros['ddl_entidad'] = $empresa[0];
		$datos = $this->modelo->tabla_registros($parametros['ddl_entidad'], $parametros['ddl_empresa'], $parametros['ddl_usuario'], $parametros['ddl_modulos'], $parametros['txt_desde'], $parametros['txt_hasta'], $parametros['ddl_num_reg']);
		$reg = array();
		foreach ($datos as $key => $value) {
			$ent = $this->modelo->entidades(false, $value['RUC']);
			$ent = explode('_', $ent[0]['id']);
			$empresas = $this->modelo->empresas($ent[1], false, $value['Item']);
			$reg[] = array('Fecha' => $value['Fecha'], 'Hora' => $value['Hora'], 'Entidad' => $value['enti'], 'IP_Acceso' => $value['IP_Acceso'], 'Aplicacion' => $value['Aplicacion'], 'Tarea' => $value['Tarea'], 'Empresa' => $empresas[0]['text'], 'Usuario' => $value['nom']);
		}
		$this->modelo->imprimir_excel($reg);
	}

	function grupos($query)
	{
		$datos = $this->modelo->grupos($query);
		$res[] = array('id' => '.', 'text' => 'TODOS');
		foreach ($datos as $key => $value) {
			$res[] = array('id' => $value['Grupo'], 'text' => $value['Grupo']);
		}
		return $res;
	}

	function clientes_x_grupo($query, $grupo,$cartera=false)
	{
		if ($grupo == '.') {
			$grupo = '';
		}
		$cod = '';
		$datos = $this->modelo->Cliente_facturas($cod, $grupo, $query);
		if(!$cartera)
		{
			$res[0] = array('id' => 'T', 'text' => 'Todos', 'email' => '', 'data' => '');
		}
		foreach ($datos as $key => $value) {
			$res[] = array('id' => $value['Codigo'], 'text' => $value['Cliente'] . '  CI:' . $value['CI_RUC'], 'email' => $value['Email'], 'data' => $value);
		}
		return $res;
	}

	function clientes2_x_grupo($query, $grupo, $estado)
	{
		if ($grupo == '.') {
			$grupo = '';
		}
		$cod = '';
		$datos = $this->modelo->Cliente_facturas_estado($cod, $grupo, $query, $estado);
		$res[0] = array('id' => 'T', 'text' => 'Todos', 'email' => '', 'data' => '');
		foreach ($datos as $key => $value) {
			$res[] = array('id' => $value['Codigo'], 'text' => $value['Cliente'] . '  CI:' . $value['CI_RUC'], 'email' => $value['Email'], 'data' => $value);
		}
		return $res;
	}
	function validar_cliente($parametros)
	{
		// print_r($parametros);die();
		if ($parametros['tip'] == '2') {
			$parametros['cla'] = false;
			if ($parametros['cli'] == 'T') {
				return 1;
			}
		}
		$dato = $this->modelo->Cliente_facturas($parametros['cli'], false, false, $parametros['cla']);
		if (empty($dato)) {
			return -1;
		} else {
			return 1;
		}

	}

	function clientes_datos($parametros)
	{
		$grupo = '';
		if ($parametros['gru'] != '.') {
			$grupo = $parametros['gru'];
		}
		$query = '';
		$datos = $this->modelo->Cliente($parametros['ci'], $grupo, $query);
		return $datos;
	}
	function enviar_mail($parametros)
	{
		$empresaGeneral = array_map(array($this, 'encode1'), $this->empresaGeneral);

		$nueva_Clave = generate_clave(8);
		// $datos[0]['campo']='Clave';
		// $datos[0]['dato']=$nueva_Clave;

		// $where[0]['campo'] = 'Codigo';
		// $where[0]['valor'] = $parametros['ci'];
		// $where[0]['tipo'] = 'string';

		$email_conexion = $empresaGeneral[0]['Email_Conexion'];
		$email_pass = $empresaGeneral[0]['Email_Contraseña'];
		$correo_apooyo = "info@diskcoversystem.com"; //correo que saldra ala do del emisor
		$cuerpo_correo = 'Se a generado una clave temporar para que usted pueda ingresar:' . $nueva_Clave;
		$titulo_correo = 'EMAIL DE RECUPERACION DE CLAVE';
		$archivos = false;
		$correo = $parametros['ema'];


		SetAdoAddNew("Clientes");
		SetAdoFields("Clave", nueva_Clave);
		SetAdoFieldsWhere("Codigo", $parametros['ci']);
		$resp = SetAdoUpdateGeneric();

		// print_r($correo);die();

		if ($resp == 1) {
			if ($this->email->recuperar_clave($archivos, $correo, $cuerpo_correo, $titulo_correo, $correo_apooyo, 'Email de recuperacion', $email_conexion, $email_pass) == 1) {
				return 1;
			} else {
				return -1;
			}
		} else {
			return -1;
		}
	}


	function encode1($arr)
	{
		$new = array();
		foreach ($arr as $key => $value) {
			if (!is_object($value)) {
				if ($key == 'Archivo_Foto') {
					if (!file_exists('../../img/img_estudiantes/' . $value)) {
						$value = '';
						//$new[mb_convert_encoding($key, 'UTF-8')] = mb_convert_encoding($value, 'UTF-8');
						$new[$key] = $value;
					}
				}
				if ($value == '.') {
					$new[$key] = '';
				} else {
					//$new[mb_convert_encoding($key, 'UTF-8')] = mb_convert_encoding($value, 'UTF-8');
					$new[$key] = $value;
				}
			} else {
				//print_r($value);
				$new[$key] = $value->format('Y-m-d');
			}
		}
		return $new;
	}

	function autorizar($parametros)
	{
		// print_r($parametros);die();
		$cliente_factura = $this->modelo->factura_detalle($parametros['FacturaNo'], $parametros['serie'], $ci = false, $periodo = false);
		// print_r($cliente_factura);die();

		$respuesta = $this->sri->Actualizar_factura($cliente_factura[0]['RUC_CI'], $parametros['FacturaNo'], $parametros['serie']);
		if ($respuesta == -1) {
			return -1;
		}
		$TC = '01';
		if (isset($parametros['tc']) && $parametros['tc'] == 'LC') {
			$TC = '03';
		}
		// print_r('ss');die();
		$rep = $this->sri->Autorizar_factura_o_liquidacion($parametros);
		$clave = $this->sri->Clave_acceso($parametros['Fecha'], $TC, $parametros['serie'], $parametros['FacturaNo']);
		$imp = '';
		if ($rep == 1) {
			return array('respuesta' => $rep, 'pdf' => $imp, 'clave' => $clave);
		} else {
			try {
				if (json_encode($rep) == false) { //si retorna false puede ser por la codificación debido a caracteres especiales, como tildes.
					$rep = mb_convert_encoding($rep, 'UTF-8');
				}
			} catch (Exception $e) {
			}
			return array('respuesta' => -1, 'pdf' => $imp, 'text' => $rep, 'clave' => $clave);
		}

		// return $res;
	}

	function autorizar_bloque($parametros)
	{
		// print_r($parametros);die();

		$codigo = $parametros['ci'];
		$datos = $this->modelo->facturas_emitidas_tabla($codigo, $parametros['per'], $parametros['desde'], $parametros['hasta'], $parametros['serie'], $parametros['auto']);


		foreach ($datos as $key => $value) {
			// print_r($value);die();
			$parametros2 = array('TC' => $value['TC'], 'FacturaNo' => $value['Factura'], 'serie' => $value['Serie'], 'Fecha' => $value['Fecha']->format('Y-m-d'));
			$resp[$value['Factura']] = $this->autorizar($parametros2);
		}

		$tabla = '<table><tr>
    			<td>Factura</td>
    			<td>Numero de Autorizacion</td>
    			<td>Estado</td>
    			<td>Detalle</td>
    		</tr>';
		foreach ($resp as $key => $value) {
			if ($value['respuesta'] != 1) {
				$deta = $this->sri->error_sri($value['clave']);
				if ($deta['adicional'] != '') {
					$sms = $deta['adicional'];
				} else {
					$sms = $deta['mensaje'];
				}
				$tabla .= '<tr>
	    			<td>' . $key . '</td>
	    			<td>' . $value['clave'] . '</td>
	    			<td>Rechazado</td>
	    			<td>' . $sms . '</td>
	    		</tr>';
			} else {
				$tabla .= '<tr>
	    			<td>' . $key . '</td>
	    			<td>' . $value['clave'] . '</td>
	    			<td>Autorizado</td>
	    			<td></td>
	    		</tr>';
			}
		}
		$tabla .= '</table>';

		return $tabla;
	}

	function anular($parametros)
	{
		// print_r($parametros);die();
		SetAdoAddNew("Facturas");
		SetAdoFields("T", "A");
		SetAdoFields("Nota", 'Anulción de Factura No.' . $parametros['factura'] . '.');

		SetAdoFieldsWhere('Serie', $parametros['serie']);
		SetAdoFieldsWhere('Factura', $parametros['factura']);
		SetAdoFieldsWhere('CodigoC', $parametros['codigo']);
		SetAdoFieldsWhere('Item', $_SESSION['INGRESO']['item']);
		SetAdoFieldsWhere('Periodo', $_SESSION['INGRESO']['periodo']);
		SetAdoUpdateGeneric();

		//actualiza en detalle facturas    	 
		SetAdoAddNew("Detalle_Factura");
		SetAdoFields("T", "A");

		SetAdoFieldsWhere('Serie', $parametros['serie']);
		SetAdoFieldsWhere('Factura', $parametros['factura']);
		SetAdoFieldsWhere('CodigoC', $parametros['codigo']);
		SetAdoFieldsWhere('Item', $_SESSION['INGRESO']['item']);
		SetAdoFieldsWhere('Periodo', $_SESSION['INGRESO']['periodo']);
		SetAdoUpdateGeneric();

		return $this->modelo->eliminar_abonos($parametros);
	}

	function enviar_email_detalle($parametros)
	{
		$to_correo = substr($parametros['to'], 0, -1);
		$cuerpo_correo = $parametros['cuerpo'];
		$titulo_correo = $parametros['titulo'];

		$this->modelo->pdf_factura_descarga($parametros['fac'], $parametros['serie'], $parametros['codigoc'], false, 1);


		$archivos[0] = $parametros['serie'] . '-' . generaCeros($parametros['fac'], 7) . '.pdf';

		$datos = $this->modelo->factura_detalle($parametros['fac'], $parametros['serie'], $parametros['codigoc']);
		$rutaA = dirname(__DIR__, 2) . '/comprobantes/entidades/entidad_' . generaCeros($_SESSION['INGRESO']['IDEntidad'], 3) . '/CE' . generaCeros($_SESSION['INGRESO']['item'], 3) . '/Autorizados/' . $datos[0]['Autorizacion'] . '.xml';
		if (file_exists($rutaA)) {
			$archivos[1] = $datos[0]['Autorizacion'] . '.xml';
		} else {
			// crea las carpetas si no existen
			$carpeta_entidad = dirname(__DIR__, 2) . '/comprobantes/entidades/entidad_' . generaCeros($_SESSION['INGRESO']['IDEntidad'], 3);
			$empresa = generaCeros($_SESSION['INGRESO']['item'], 3);
			$carpeta_autorizados = "";
			$carpeta_generados = "";
			$carpeta_firmados = "";
			$carpeta_no_autori = "";
			if (file_exists($carpeta_entidad)) {
				$carpeta_comprobantes = $carpeta_entidad . '/CE' . $empresa;
				if (file_exists($carpeta_comprobantes)) {
					$carpeta_autorizados = $carpeta_comprobantes . "/Autorizados";
					$carpeta_generados = $carpeta_comprobantes . "/Generados";
					$carpeta_firmados = $carpeta_comprobantes . "/Firmados";
					$carpeta_no_autori = $carpeta_comprobantes . "/No_autorizados";
					$carpeta_rechazados = $carpeta_comprobantes . "/Rechazados";
					$carpeta_rechazados = $carpeta_comprobantes . "/Enviados";

					if (!file_exists($carpeta_autorizados)) {
						mkdir($carpeta_entidad . "/CE" . $empresa . "/Autorizados", 0777);
					}
					if (!file_exists($carpeta_generados)) {
						mkdir($carpeta_entidad . '/CE' . $empresa . '/Generados', 0777);
					}
					if (!file_exists($carpeta_firmados)) {
						mkdir($carpeta_entidad . '/CE' . $empresa . '/Firmados', 0777);
					}
					if (!file_exists($carpeta_no_autori)) {
						mkdir($carpeta_entidad . '/CE' . $empresa . '/No_autorizados', 0777);
					}
					if (!file_exists($carpeta_rechazados)) {
						mkdir($carpeta_entidad . '/CE' . $empresa . '/Rechazados', 0777);
					}
					if (!file_exists($carpeta_rechazados)) {
						mkdir($carpeta_entidad . '/CE' . $empresa . '/Enviados', 0777);
					}
				} else {
					mkdir($carpeta_entidad . '/CE' . $empresa, 0777);
					mkdir($carpeta_entidad . "/CE" . $empresa . "/Autorizados", 0777);
					mkdir($carpeta_entidad . '/CE' . $empresa . '/Generados', 0777);
					mkdir($carpeta_entidad . '/CE' . $empresa . '/Firmados', 0777);
					mkdir($carpeta_entidad . '/CE' . $empresa . '/No_autorizados', 0777);
					mkdir($carpeta_entidad . '/CE' . $empresa . '/Rechazados', 0777);
					mkdir($carpeta_entidad . '/CE' . $empresa . '/Enviados', 0777);
				}
			} else {
				mkdir($carpeta_entidad, 0777);
				mkdir($carpeta_entidad . '/CE' . $empresa, 0777);
				mkdir($carpeta_entidad . "/CE" . $empresa . "/Autorizados", 0777);
				mkdir($carpeta_entidad . '/CE' . $empresa . '/Generados', 0777);
				mkdir($carpeta_entidad . '/CE' . $empresa . '/Firmados', 0777);
				mkdir($carpeta_entidad . '/CE' . $empresa . '/No_autorizados', 0777);
				mkdir($carpeta_entidad . '/CE' . $empresa . '/Rechazados', 0777);
				mkdir($carpeta_entidad . '/CE' . $empresa . '/Enviados', 0777);
			}




			$docs = $this->modelo->trans_documentos($datos[0]['Autorizacion']);
			if (count($docs) > 0) {

				$contenido = $docs[0]['Documento_Autorizado'];
				$archivo = fopen($rutaA, 'a');
				fputs($archivo, $contenido);
				fclose($archivo);
				$archivos[1] = $datos[0]['Autorizacion'] . '.xml';
			}
		}
		$cuerpo_correo = '
Este correo electronico fue generado automaticamente a usted desde El Sistema Financiero Contable DiskCover System, porque figura como correo electronico alternativo de ' . $_SESSION['INGRESO']['Razon_Social'] . '. Nosotros respetamos su privacidad y solamente se utiliza este medio para mantenerlo informado sobre nuestras ofertas, promociones y comunicados. No compartimos, publicamos o vendemos su informacion personal fuera de nuestra empresa. Este mensaje fue procesado por un funcionario que forma parte de la Institucion.

Por la atencion que se de al presente quedo de usted.

Atentamente,

 ' . $_SESSION['INGRESO']['Razon_Social'] . '

Esta direccion de correo electronico no admite respuestas. En caso de requerir atencion personalizada por parte de un asesor de Servicio al Cliente de VACA PRIETO WALTER JALIL, podra solicitar ayuda mediante los canales oficiales que detallamos a continuación: Telefonos: 026052430 /  Correo: infosistema@diskcoversystem.com.

www.diskcoversystem.com
QUITO - ECUADOR';

		return $this->email->enviar_email($archivos, $to_correo, $cuerpo_correo, $titulo_correo, $HTML = false);

	}

	function descargar_factura($parametros)
	{
		$this->modelo->pdf_factura_descarga($parametros['fac'], $parametros['serie'], $parametros['codigoc']);
		return $parametros['serie'] . '-' . generaCeros($parametros['fac'], 7) . '.pdf';
	}

	function descargar_xml($parametros)
	{
		$rutaA = dirname(__DIR__, 2) . '/comprobantes/entidades/entidad_' . generaCeros($_SESSION['INGRESO']['IDEntidad'], 3) . '/CE' . generaCeros($_SESSION['INGRESO']['item'], 3) . '/Autorizados/' . $parametros['xml'] . '.xml';

		$rutaB = 'comprobantes/entidades/entidad_' . generaCeros($_SESSION['INGRESO']['IDEntidad'], 3) . '/CE' . generaCeros($_SESSION['INGRESO']['item'], 3) . '/Autorizados/' . $parametros['xml'] . '.xml';
		if (file_exists($rutaA)) {

			$rutaArchivo = $rutaA ;
			$archivo = fopen($rutaArchivo, 'r+');
			$contenido = fread($archivo, filesize($rutaArchivo));
			$contenidoSinBOM = str_replace('ï»¿', '', $contenido);
			rewind($archivo);
			fwrite($archivo, $contenidoSinBOM);
			ftruncate($archivo, ftell($archivo));
			fclose($archivo);
			
				SetAdoAddNew("Trans_Documentos");
				SetAdoFields("Documento_Autorizado", $contenidoSinBOM);
				SetAdoFieldsWhere("Clave_Acceso", $parametros['xml']);
				SetAdoUpdateGeneric();				
			

			return array('ruta' => $rutaB, 'xml' => $parametros['xml'] . '.xml');
		} else {
			// crea las carpetas si no existen
			$carpeta_entidad = dirname(__DIR__, 2) . '/comprobantes/entidades/entidad_' . generaCeros($_SESSION['INGRESO']['IDEntidad'], 3);
			$empresa = generaCeros($_SESSION['INGRESO']['item'], 3);
			$carpeta_autorizados = "";
			$carpeta_generados = "";
			$carpeta_firmados = "";
			$carpeta_no_autori = "";
			if (file_exists($carpeta_entidad)) {
				$carpeta_comprobantes = $carpeta_entidad . '/CE' . $empresa;
				if (file_exists($carpeta_comprobantes)) {
					$carpeta_autorizados = $carpeta_comprobantes . "/Autorizados";
					$carpeta_generados = $carpeta_comprobantes . "/Generados";
					$carpeta_firmados = $carpeta_comprobantes . "/Firmados";
					$carpeta_no_autori = $carpeta_comprobantes . "/No_autorizados";
					$carpeta_rechazados = $carpeta_comprobantes . "/Rechazados";
					$carpeta_rechazados = $carpeta_comprobantes . "/Enviados";

					if (!file_exists($carpeta_autorizados)) {
						mkdir($carpeta_entidad . "/CE" . $empresa . "/Autorizados", 0777);
					}
					if (!file_exists($carpeta_generados)) {
						mkdir($carpeta_entidad . '/CE' . $empresa . '/Generados', 0777);
					}
					if (!file_exists($carpeta_firmados)) {
						mkdir($carpeta_entidad . '/CE' . $empresa . '/Firmados', 0777);
					}
					if (!file_exists($carpeta_no_autori)) {
						mkdir($carpeta_entidad . '/CE' . $empresa . '/No_autorizados', 0777);
					}
					if (!file_exists($carpeta_rechazados)) {
						mkdir($carpeta_entidad . '/CE' . $empresa . '/Rechazados', 0777);
					}
					if (!file_exists($carpeta_rechazados)) {
						mkdir($carpeta_entidad . '/CE' . $empresa . '/Enviados', 0777);
					}
				} else {
					mkdir($carpeta_entidad . '/CE' . $empresa, 0777);
					mkdir($carpeta_entidad . "/CE" . $empresa . "/Autorizados", 0777);
					mkdir($carpeta_entidad . '/CE' . $empresa . '/Generados', 0777);
					mkdir($carpeta_entidad . '/CE' . $empresa . '/Firmados', 0777);
					mkdir($carpeta_entidad . '/CE' . $empresa . '/No_autorizados', 0777);
					mkdir($carpeta_entidad . '/CE' . $empresa . '/Rechazados', 0777);
					mkdir($carpeta_entidad . '/CE' . $empresa . '/Enviados', 0777);
				}
			} else {
				mkdir($carpeta_entidad, 0777);
				mkdir($carpeta_entidad . '/CE' . $empresa, 0777);
				mkdir($carpeta_entidad . "/CE" . $empresa . "/Autorizados", 0777);
				mkdir($carpeta_entidad . '/CE' . $empresa . '/Generados', 0777);
				mkdir($carpeta_entidad . '/CE' . $empresa . '/Firmados', 0777);
				mkdir($carpeta_entidad . '/CE' . $empresa . '/No_autorizados', 0777);
				mkdir($carpeta_entidad . '/CE' . $empresa . '/Rechazados', 0777);
				mkdir($carpeta_entidad . '/CE' . $empresa . '/Enviados', 0777);
			}





			$docs = $this->modelo->trans_documentos($parametros['xml']);
			// print_r($docs);die();
			if (count($docs) > 0) {


				$contenido = $docs[0]['Documento_Autorizado'];
				$archivo = fopen($rutaA, 'a');
				$rutaArchivo = $rutaA ;
				$archivo = fopen($rutaArchivo, 'r+');
				$contenidoSinBOM = str_replace('ï»¿', '', $contenido);
				rewind($archivo);
				fwrite($archivo, $contenidoSinBOM);
				ftruncate($archivo, ftell($archivo));
				fclose($archivo);

				
					SetAdoAddNew("Trans_Documentos");
					SetAdoFields("Documento_Autorizado", $contenidoSinBOM);
					SetAdoFieldsWhere("Clave_Acceso", $parametros['xml']);
					SetAdoUpdateGeneric();			
				

				
				

				return array('ruta' => $rutaB, 'xml' => $parametros['xml'] . '.xml');
			} else {
				return -1;
			}
		}
	}

	function generar_xml($parametros)
	{
		// print_r($parametros);die();
		$xml = $this->sri->generar_solo_xml_factura($parametros);	   
		// print_r($xml);die(); 
		if($xml['respuesta']==1)
		{
			$rutaB = 'comprobantes/entidades/entidad_' . generaCeros($_SESSION['INGRESO']['IDEntidad'], 3) . '/CE' . generaCeros($_SESSION['INGRESO']['item'], 3) . '/Generados/' . $xml['Clave'] . '.xml';
			return array('respuesta'=> 1,'ruta' => $rutaB, 'xml' => $xml['Clave'] . '.xml');
		}else
		{
			return array('respuesta'=>$xml['respuesta'],'ruta' => '', 'xml' => $xml['Clave'] . '.xml');
		}
	}



}
?>