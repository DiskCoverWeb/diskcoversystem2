<?php
require(dirname(__DIR__, 2) . '/modelo/facturacion/facturas_distribucionM.php');
require_once(dirname(__DIR__, 2) . "/comprobantes/SRI/autorizar_sri.php");
require_once(dirname(__DIR__, 3) . "/lib/fpdf/cabecera_pdf.php");
/**
 * 
 */
$controlador = new facturas_distribucion();
if(isset($_GET["LlenarSelectIVA"])){
	$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
	$fecha = str_replace("-", "", $fecha);
	echo json_encode($controlador->LlenarSelectIVA($fecha));
}

if(isset($_GET['GuardarBouche'])){
	//$parametros = $_POST;
    
    // Verifica si se ha subido un archivo
    /*if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $localFilePath  = $_FILES['imagen'];
        $carpetaDestino = dirname(__DIR__, 2) . "/img/png/";
        $nombreArchivoDestino = $carpetaDestino . $parametros['picture'] . '.png';//Destino temporal para guardar el archivo

        /*if (!is_dir($carpetaDestino)) {
            // Intentar crear la carpeta, el 0777 es el modo de permiso más permisivo
            if (!mkdir($carpetaDestino, 0777, true)) { // true permite la creación de estructuras de directorios anidados
                throw new Exception("No se pudo crear la carpeta");
            }
        }

        if (move_uploaded_file($localFilePath['tmp_name'], $nombreArchivoDestino)) {
            echo json_encode($controlador->GuardarProducto($parametros));
        } else {
            throw new Exception("No se pudo guardar el archivo en el servidor");
        }
    
    } else {
        throw new Exception("Error al subir el archivo");
    }*/
	$file = $_FILES;
	$ruta = dirname(__DIR__,2).'/comprobantes/pagos_subidos/entidad_'.$_SESSION['INGRESO']['IDEntidad'].'/empresa_'.$_SESSION['INGRESO']['item'].'/';
    if(!file_exists($ruta))
    {
		$ruta1 = dirname(__DIR__,2).'/comprobantes/pagos_subidos/';
		if(!file_exists($ruta1)){
			mkdir($ruta1,0777);
		}
		$ruta2 = dirname(__DIR__,2).'/comprobantes/pagos_subidos/entidad_'.$_SESSION['INGRESO']['IDEntidad'];
		if(!file_exists($ruta2)){
			mkdir($ruta2,0777);
		}
      //mkdir($ruta2,0777);      
      mkdir($ruta,0777);
    }
     $uploadfile_temporal=$file['file']['tmp_name'];
     $tipo = explode('/', $file['file']['type']);
	 $nom_archivo = $_POST['fecha'].$_POST['n_factura'].$_POST['serie'];
     $nombre = $_SESSION['INGRESO']['item'].'_'.$nom_archivo.'_pago.'.$tipo[1];
    // print_r($file);print_r($post);die();
     $nuevo_nom=$ruta.$nombre;
	 
	 $r = array(
		"res" => 0,
		"documento" => "No existe documento."
	 );
     if (is_uploaded_file($uploadfile_temporal))
     {
		 
		if(move_uploaded_file($uploadfile_temporal,$nuevo_nom)){
			$r['res'] = 1;
			$r['documento'] = $nombre;
		}
        /*SetAdoAddNew("Clientes_Datos_Extras");          
        SetAdoFields("Evidencias",$nombre);

        SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);
        SetAdoFieldsWhere('Codigo',$post['nom_1']);
        SetAdoUpdateGeneric();*/
        
     }
	 echo json_encode($r);
}

if (isset($_GET['DCLineas'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCLinea($parametros));
 }

if (isset($_GET['ActualizarAsientoF'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->actualizarAsientoF($parametros));
}

if(isset($_GET["ConsultarGavetas"])){
	echo json_encode($controlador->consultarGavetas());
}

if(isset($_GET['ValoresGavetas'])){
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->valoresGavetas($parametros));
}

if(isset($_GET["consultarEvaluacionFundaciones"])){
	echo json_encode($controlador->consultarEvaluacionFundaciones());
}  

if(isset($_GET["ConsultarProductos"])){
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->ConsultarProductos($parametros));
}

if(isset($_GET["LlenarSelectTipoFactura"])){
	//$fecha = $_GET['fecha'];
	echo json_encode($controlador->LlenarSelectTipoFactura());
}

if(isset($_GET['GrabarGavetas'])){
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->grabar_gavetas($parametros));
}

if(isset($_GET['GrabarEvaluaciones'])){
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->grabar_evaluacion($parametros));
}

if (isset($_GET['DCCliente'])) {
	$query = '';
	$parametros = array(
		'donacion' => "",
		'fecha' => ""
	);
	if (isset($_GET['query'])) {
		$query = $_GET['query'];
	}
	if(isset($_GET['v_donacion'])){$parametros['donacion'] = $_GET['v_donacion'];}
	if(isset($_GET['fecha'])){$parametros['fecha'] = $_GET['fecha'];}
	//print_r($_GET);die();
	echo json_encode($controlador->Listar_Clientes_PV($query, $parametros));
}
if (isset($_GET['DCCliente_exacto'])) {
	$query = '';
	if (isset($_GET['query'])) {
		$query = $_GET['query'];
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

class facturas_distribucion
{
	private $modelo;
	private $sri;
	private $pdf;
	function __construct()
	{
		$this->modelo = new facturas_distribucionM();
		$this->sri = new autorizacion_sri();
		$this->pdf = new cabecera_pdf();
	}

	function ConsultarProductos($params){
		$datos = $this->modelo->ConsultarProductos($params);
		$res = array();
		if(count($datos) > 0){
			$contenido = array();
			foreach($datos as $key => $value){
				$fecha=$value['Fecha']->format("Y-m-d");
				$producto = Leer_Codigo_Inv($value['Codigo_Inv'], $fecha, $value['CodBodega'], $CodMarca='');
				//Leer_Codigo_Inv($parametros['Codigo'], $parametros['fecha'], $parametros['CodBod'])
				$contenido[] = array(
					"Detalles" => $value,
					"Productos" => $producto['datos']
				);
				//array_push($detalles, $producto);
			}
			/*$asignacion = array();
			foreach($datos as $key => $value){
				$productos = $this->modelo->ConsultarKardex($value['CodBodega']);
				$asignacion[] = array(
					'CodBodega' => $productos[0]['Codigo_Barra'],
					'Usuario' => $value['Nombre_Completo'],
					'Producto' => $productos[0]['Producto'],
					'Cantidad' => $value['Total'],
					'PVP' => $productos[0]['PVP'],
					'Total' => $value['Total'] * $productos[0]['PVP']
				);
				$detalles['Unidad'] = $productos[0]['Unidad'];
			}*/
			$res = array(
				"res" => 1,
				"contenido" => $contenido
			);
		}else{
			$res = array(
				"res" => 0,
				"contenido" => "No se encontraron datos"
			);
		}
		return $res;
	}

	function DCLinea($parametros)
   {
      $datos = $this->modelo->DCLinea($parametros['TC'], $parametros['Fecha']);
      $lis = array();
      foreach ($datos as $key => $value) {
         $lis[] = array('codigo' => $value['Codigo'], 'nombre' => $value['Concepto']);
      }
      return $lis;
   }

	function consultarGavetas(){
		//print_r($_SESSION);die();
		$datos = $this->modelo->consultarGavetas();
		$respuesta = array();
		if(count($datos) > 0){
			$respuesta =  array(
				'res' => 1,
				'contenido' => $datos
			);
		}else{
			$respuesta = array(
				'res' => 0,
				'contenido' => "No se encontraron datos de gavetas."
			);
		}
		return $respuesta;
	}

	function valoresGavetas($parametros){
		$datos = $this->modelo->valoresGavetas($parametros);
		$respuesta = array();
		if(count($datos) > 0){
			$respuesta =  array(
				'res' => 1,
				'contenido' => $datos
			);
		}else{
			$respuesta = array(
				'res' => 0,
				'contenido' => "No se encontraron valores de gavetas."
			);
		}
		return $respuesta;
	}
	function consultarEvaluacionFundaciones(){
		//print_r($_SESSION);die();
		$datos = $this->modelo->consultarEvaluacionFundaciones();
		$respuesta = array();
		if(count($datos) > 0){
			$respuesta =  array(
				'res' => 1,
				'contenido' => $datos
			);
		}else{
			$respuesta = array(
				'res' => 0,
				'contenido' => "No se encontraron datos de evaluacion de fundaciones."
			);
		}
		//print_r($_SESSION);die();
		return $respuesta;
	}
	

	function LlenarSelectIVA($fecha)
	{
		$datos = $this->modelo->LlenarSelectIVA($fecha);
		$res = array();
		foreach ($datos as $key => $value) {
			$res[] = array('id' => $value['Codigo'], 'text' => $value['Porc']);
		}
		return $res;
	}

	function LlenarSelectTipoFactura()
	{
		$datos = $this->modelo->LlenarSelectTipoFactura();
		$res = array();
		foreach ($datos as $key => $value) {
			$res[] = array('id' => $value['Codigo'], 'text' => $value['Concepto'], 'data' => array($value));
		}
		return $res;
	}

	function Listar_Clientes_PV($query, $parametros)
	{
		$datos = $this->modelo->Listar_Clientes_PV($query, $parametros);
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

	function CompilarString($cadSQL, $lString = 0, $quitarPuntos = false)
    {
        if ($lString > 0) {
            $cadSQL = substr($cadSQL, 0, $lString);
        }

        // Eliminación de caracteres específicos
        $caracteresAEliminar = ['|', "\r", "\n", "'", ",", "$", "#", "&", "'"];
        foreach ($caracteresAEliminar as $char) {
            $cadSQL = str_replace($char, '', $cadSQL);
        }

        // Reducción de espacios múltiples a un solo espacio
        $cadSQL = preg_replace('/\s+/', ' ', $cadSQL);

        // Manejo de cadenas nulas o vacías
        if (is_null($cadSQL) || $cadSQL === '') {
            $cadSQL = '.';
        }

        // Eliminación de puntos al inicio y al final, si es necesario
        if ($quitarPuntos) {
            $cadSQL = trim($cadSQL, '.');
        }

        // Valor por defecto en caso de cadena vacía
        if ($cadSQL === '') {
            $cadSQL = G_NINGUNO; // Asumiendo que Ninguno es un valor por defecto
        }

        return $cadSQL;
    }

	function SetearBlancos($strg, $longStrg, $noBlancos, $esNumero, $conLineas = false, $decimales = false)
    {
        if (is_null($strg) || empty($strg)) {
            $strg = "";
        }
        $strg = $this->CompilarString($strg);
        if ($esNumero) {
            if ($decimales) {
                $sinEspacios = number_format(floatval($strg), 2, '.', '');
            } else {
                $sinEspacios = strval(intval($strg));
            }
            if (strlen($sinEspacios) < $longStrg) {
                $sinEspacios = str_pad($sinEspacios, $longStrg, ' ', STR_PAD_LEFT);
            }
        } else {
            if ($longStrg > 0) {
                $sinEspacios = $strg . str_repeat(" ", $longStrg);
                $sinEspacios = substr($sinEspacios, 0, $longStrg);
            } else {
                $sinEspacios = trim($strg);
            }
        }
        if ($noBlancos > 0) {
            $sinEspacios .= str_repeat(" ", $noBlancos);
        }
        if ($conLineas) {
            $sinEspacios .= "|";
        }
        if ($sinEspacios === "") {
            $sinEspacios = " ";
        }
        return $sinEspacios;
    }

	
	function Imprimir_Punto_Venta($TFA){
		$AdoDBFactura = array();
		$AdoDBDetalle = array();
		//Dim CadenaMoneda As String
		//Dim Numero_Letras As String
		$Cant_Ln = 0;
		$Cant_Item_PV = 0;
		$CantGuion = 0;
		$CantBlancos = "";
		try{
			$mensajes = "Imprimir Factura No. " . $TFA['TextFacturaNo'];
			$titulo = "IMPRESION";
			$bandera = false;

			// va algo

			$CantGuion = Leer_Campo_Empresa("Cant_Ancho_PV");
			if($CantGuion < 26) {$CantGuion=26;}
			$SubTotal = 0;
			$Total = 0;
			$Total_IVA = 0;
			$Total_Servicio = 0;
			$Total_Desc = 0;
			
			$PosLinea = 0.1;
			$Producto = "";
			if($TFA['TC'] == "PV")
			{
				$AdoDBFactura = $this->modelo->consultaTrans_Ticket($TFA);
			}else
			{
				$AdoDBFactura = $this->modelo->consultaFactura($TFA);
			}
			//Iniciamos la consulta de impresion
			if(count($AdoDBFactura) > 0){
				//Encabezado de la Factura
				if($_SESSION['INGRESO']['Encabezado_PV'])
				{
					$Producto = " " . PHP_EOL
						. str_pad('', (int)(($CantGuion - strlen($_SESSION['empresa'])) / 2)) . strtoupper($_SESSION['empresa']) . PHP_EOL
						. str_pad('', (int)(($CantGuion - strlen($_SESSION['Nombre_Comercial'])) / 2)) . $_SESSION['Nombre_Comercial'] . PHP_EOL
						. str_pad('', (int)(($CantGuion - strlen(strtoupper($_SESSION['Gerente']))) / 2)) . strtoupper($_SESSION['Gerente']) . PHP_EOL
						. str_pad('', (int)(($CantGuion - strlen("R.U.C. " . $_SESSION['RUC'])) / 2)) . "R.U.C. " . $_SESSION['RUC'] . PHP_EOL
						. str_pad('', (int)(($CantGuion - strlen("Telefono: " . $_SESSION['Telefono1'])) / 2)) . "Telefono: " . $_SESSION['Telefono1'] . PHP_EOL
						. $_SESSION['Direccion'] . PHP_EOL;

					$Cant_Ln = $Cant_Ln + 7;
					if($TFA['TC'] = "PV")
					{
						$Producto .= " " . PHP_EOL . "T I C K E T   No. " . sprintf("000-000-%07d", $TFA['TextFacturaNo']) . PHP_EOL . " " . PHP_EOL;
						$Cant_Ln = $Cant_Ln + 1;
					}else if($TFA['TC'] = "NV")
					{
						$Producto .= "Auto. SRI: " . $TFA['Autorizacion'] . " - Caduca: " . strtoupper(substr(mesesLetras(date('n', strtotime($TFA['Fecha']))), 0, 3)) . "/" . date('Y', strtotime($TFA['Fecha'])) . "\r\n" . " " . "\r\n" . "NOTA DE VENTA No. " . $TFA['Serie'] . "-" . sprintf('%07d', $TFA['TextFacturaNo']) . "\r\n" . " " . "\r\n";
						$Cant_Ln += 2;

						/*$Producto .= "Auto. SRI: " . TFA['Autorizacion'] . " - Caduca: " . MidStrg(UCaseStrg(MesesLetras(Month(TFA['Fecha']))), 1, 3) . "/" . Year(TFA['Fecha']) . vbCrLf . " " . vbCrLf . "NOTA DE VENTA No. " . Serie . "-" . Format$(TFA.Factura, "0000000") . vbCrLf . " " . vbCrLf;
						Cant_Ln = Cant_Ln + 2*/
					}else
					{
						$Producto .= "Auto. SRI: " . $TFA['Autorizacion'] . " - Caduca: " . strtoupper(substr(mesesLetras(date('n', strtotime($TFA['Fecha']))), 0, 3)) . "/" . date('Y', strtotime($TFA['Fecha'])) . "\r\n" . " " . "\r\n" .
									"FACTURA No. " . $TFA['Serie'] . "-" . sprintf('%07d', $TFA['TextFacturaNo']) . "\r\n" . " " . "\r\n";

						$Cant_Ln += 2;

						/*Producto = Producto & "Auto. SRI: " & TFA['Autorizacion'] & " - Caduca: " & MidStrg(UCaseStrg(MesesLetras(Month(TFA['Fecha']))), 1, 3) & "/" & Year(TFA['Fecha']) & vbCrLf & " " & vbCrLf _
								& "FACTURA No. " & TFA['Serie'] & "-" & Format$(TFA.Factura, "0000000") & vbCrLf & " " & vbCrLf
						Cant_Ln = Cant_Ln + 2*/
					}
				}else{
					$Producto = "\r\n" . " " . "\r\n" . " " . "\r\n" . " " . "\r\n" . " " . "\r\n" .
								"Transaccion(" . $TFA['TC'] . ") No." . sprintf('%07d', $TFA['TextFacturaNo']) . "\r\n" . " " . "\r\n";
					$Cant_Ln += 4;
				}
					/*
				Else
					Producto = vbCrLf & " " & vbCrLf & " " & vbCrLf & " " & vbCrLf & " " & vbCrLf _
							& "Transaccion(" & TFA.TC & ") No." & Format$(TFA.Factura, "0000000") & vbCrLf & " " & vbCrLf
					Cant_Ln = Cant_Ln + 4
				End If*/
				$Producto .= "Fecha: " . $_SESSION['INGRESO']['Fecha'] . " - Hora: " . $AdoDBFactura['Hora'] . "\r\n";
				$Producto .= "Cliente: " . "\r\n" . 
							substr($AdoDBFactura['Cliente'], 0, 33) . "\r\n";
				$Producto .= "R.U.C./C.I.: " . $AdoDBFactura['CI_RUC'] . "\r\n" .
							"Cajero: " . substr($_SESSION['INGRESO']['CodigoU'], 0, 6) . "\r\n";

				if ($AdoDBFactura['Telefono'] !== $_SESSION['INGRESO']['ninguno']) {
					$Producto .= "Telefono: " . $AdoDBFactura['Telefono'] . "\r\n";
				}
				if ($AdoDBFactura['Direccion'] !== $_SESSION['INGRESO']['ninguno']) {
					$Producto .= "Direccion: " . "\r\n" . $AdoDBFactura['Direccion'] . "\r\n";
				}
				if ($AdoDBFactura['Email'] !== $_SESSION['INGRESO']['ninguno']) {
					$Producto .= "Email: " . "\r\n" . $AdoDBFactura['Email'] . "\r\n";
				}

				$Producto .= str_repeat("-", $CantGuion) . "\r\n" .
							"PRODUCTO/Cant x PVP/TOTAL" . "\r\n" .
							str_repeat("-", $CantGuion) . "\r\n";

				$Efectivo = $AdoDBFactura['Efectivo'];
				$Cant_Ln += 6;

				
				/*Producto = Producto & "Fecha: " & FechaSistema & " - Hora: " & .fields("Hora") & vbCrLf
				Producto = Producto & "Cliente: " & vbCrLf _
							& MidStrg(.fields("Cliente"), 1, 33) & vbCrLf
				Producto = Producto & "R.U.C./C.I.: " & .fields("CI_RUC") & vbCrLf _
							& "Cajero: " & MidStrg(CodigoUsuario, 1, 6) & vbCrLf
				If .fields("Telefono") <> Ninguno Then Producto = Producto & "Telefono: " & .fields("Telefono") & vbCrLf
				If .fields("Direccion") <> Ninguno Then Producto = Producto & "Direccion: " & vbCrLf & .fields("Direccion") & vbCrLf
				If .fields("Email") <> Ninguno Then Producto = Producto & "Email: " & vbCrLf & .fields("Email") & vbCrLf
				Producto = Producto & String$(CantGuion, "-") & vbCrLf _
							& "PRODUCTO/Cant x PVP/TOTAL" & vbCrLf _
							& String$(CantGuion, "-") & vbCrLf
							Efectivo = .fields("Efectivo")
				Cant_Ln = Cant_Ln + 6*/

			}
			//Comenzamos a recoger los detalles de la factura
			if($TFA['TC'] == "PV")
			{
				$AdoDBDetalle = $this->modelo->consultarDetalleTicketProds($TFA);
			}else
			{
				$AdoDBDetalle = $this->modelo->consultarDetalleFactProds($TFA);
			}

			if (count($AdoDBDetalle) > 0) {
				foreach($AdoDBDetalle as $key => $value)
				{
					$Producto .= $value["Producto"] . "\n" .
								$this->SetearBlancos($value["Cantidad"] . "x" . number_format($value["Precio"], 2), 12, 0, false) . " " .
								$this->SetearBlancos($value["Total"], $CantGuion - 13, 0, true, '', true) . "\n";
					
					$Total += $value["Total"];
					
					if ($TFA->TC != "PV") {
						$Total_IVA += $value["Total_IVA"];
					}
					
					$Cant_Ln++;
				}
			}

			//Pie de factura
			//===========================================================
			if(count($AdoDBFactura) > 0)
			{
				if($TFA['TC'] == "PV"){
					$SubTotal = $AdoDBFactura["Total"];
					$Total = $AdoDBFactura["Total"];
					$Total_IVA = 0;
					$Total_Servicio = 0;
					$Total_Desc = 0;
				}else
				{
					$SubTotal = $AdoDBFactura["SubTotal"];
					$Total = $AdoDBFactura["Total_MN"];
					$Total_IVA = $AdoDBFactura["IVA"];
					$Total_Servicio = $AdoDBFactura["Servicio"];
					$Total_Desc = $AdoDBFactura["Descuento"];
				}
				$Producto .= str_repeat("-", $CantGuion) . "\n";
				$Cant_Ln = $Cant_Ln + 1;
				#If Total_IVA Then
				
				if (($CantGuion - 26) > 0) {
					$CantBlancos = str_repeat(" ", $CantGuion - 26);
				} else {
					$CantBlancos = "";
				}
				
				$Producto .= $CantBlancos . "     SUBTOTAL " . $this->SetearBlancos(strval($SubTotal), 12, 0, true, false, true) . "\n" .
							$CantBlancos . "    I.V.A " . ($TFA['PorcIva'] * 100) . "% " . $this->SetearBlancos(strval($Total_IVA), 12, 0, true, false, true) . "\n";
				
				$Cant_Ln++;
				$Producto .= $this->SetearBlancos(strval($Total), 12, 0, true, false, true) . "\n";

				if ($Efectivo > 0) {
					$Producto .= $CantBlancos . "     EFECTIVO " . $this->SetearBlancos(strval($Efectivo), 12, 0, true, false, true) . "\n" .
								$CantBlancos . "       CAMBIO " . $this->SetearBlancos(strval($Efectivo - $Total), 12, 0, true, false, true) . "\n";
				}

				if ($TFA['TC'] != "PV") {
					$Producto .= "ORIGINAL: CLIENTE\n" .
								"COPIA   : EMISOR\n";
					if ($AdoDBFactura["Cotizacion"] > 0) {
						$Producto .= "COTIZACION: " . number_format($AdoDBFactura["Cotizacion"], 2) . "\n";
					}
				}

				$Producto .= str_repeat("=", $CantGuion) . "\n";

				if ($TFA['TC'] == "PV") {
					$Producto .= "RECLAME SU FACTURA EN CAJA\n";
				}

				$Producto .= "  GRACIAS POR SU COMPRA \n" .
							" \n" .
							" \n" .
							" \n";

				$Cant_Ln += $Cant_Item_PV;
			}
			//Enviamos a la Impresora
			
			//TipoCourier
			//TipoConsola
			//TipoCourierNew
			/*Printer.FontName = TipoCourierNew
			If Copia_PV Then
				If Cant_Item_PV < Cant_Ln Then Cant_Item_PV = Cant_Ln
				Cadena = ""
				Cant_Ln = Cant_Item_PV - Cant_Ln
				If Cant_Ln <= 0 Then Cant_Ln = 1
				For I = 1 To Cant_Ln
					Cadena = Cadena & "` " & vbCrLf
				Next I
				Producto = Producto & Cadena & Producto & vbCrLf & Cadena
			End If
			PrinterTexto 0.5, PosLinea, Producto
			Printer.EndDoc
			AdoDBDetalle.Close
			AdoDBFactura.Close
			End If
			RatonNormal*/
		}catch(Exception $e){
			/*RatonNormal
			ErrorDeImpresion
			Exit Sub*/
		}
	}

	/*
	
	Public Sub Imprimir_Punto_Venta(TFA As Tipo_Facturas)
	Dim AdoDBFactura As ADODB.Recordset
	Dim AdoDBDetalle As ADODB.Recordset
	Dim CadenaMoneda As String
	Dim Numero_Letras As String
	Dim Cant_Ln As Byte
	Dim CantGuion As Byte
	Dim CantBlancos As String

	On Error GoTo Errorhandler
	Mensajes = "Imprmir Factura No. " & TFA.Factura
	Titulo = "IMPRESION"
	Bandera = False
	SetPrinters.Show 1
	If PonImpresoraDefecto(SetNombrePRN) Then
	Escala_Centimetro 1, TipoTerminal, 9
	RatonReloj
	CantGuion = CByte(Leer_Campo_Empresa("Cant_Ancho_PV"))
	If CantGuion < 26 Then CantGuion = 26
	Total = 0: Total_IVA = 0
	Cant_Ln = 0
	PosLinea = 0.1
	Producto = ""
	If TFA.TC = "PV" Then
		sSQL = "SELECT F.*,C.Cliente,C.CI_RUC,C.Telefono,C.Direccion,C.Ciudad,C.Grupo,C.Email " _
			& "FROM Trans_Ticket As F,Clientes As C " _
			& "WHERE F.Ticket = " & TFA.Factura & " " _
			& "AND F.TC = '" & TFA.TC & "' " _
			& "AND F.Periodo = '" & Periodo_Contable & "' " _
			& "AND F.Item = '" & NumEmpresa & "' " _
			& "AND C.Codigo = F.CodigoC "
	Else
		sSQL = "SELECT F.*,C.Cliente,C.CI_RUC,C.Telefono,C.Direccion,C.Ciudad,C.Grupo,C.Email " _
			& "FROM Facturas As F,Clientes As C " _
			& "WHERE F.Factura = " & TFA.Factura & " " _
			& "AND F.TC = '" & TFA.TC & "' " _
			& "AND F.Periodo = '" & Periodo_Contable & "' " _
			& "AND F.Item = '" & NumEmpresa & "' " _
			& "AND C.Codigo = F.CodigoC "
	End If
	Select_AdoDB AdoDBFactura, sSQL
	'Iniciamos la consulta de impresion
	With AdoDBFactura
	If .RecordCount > 0 Then
		'Encabezado de la Factura
		If Encabezado_PV Then
			Producto = " " & vbCrLf _
					& Space((CantGuion - Len(Empresa)) / 2) & UCaseStrg(Empresa) & vbCrLf _
					& Space((CantGuion - Len(NombreComercial)) / 2) & NombreComercial & vbCrLf _
					& Space((CantGuion - Len(UCaseStrg(NombreGerente))) / 2) & UCaseStrg(NombreGerente) & vbCrLf _
					& Space((CantGuion - Len("R.U.C. " & RUC)) / 2) & "R.U.C. " & RUC & vbCrLf _
					& Space((CantGuion - Len("Telefono: " & Telefono1)) / 2) & "Telefono: " & Telefono1 & vbCrLf _
					& Direccion & vbCrLf
			Cant_Ln = Cant_Ln + 7
			If TFA.TC = "PV" Then
				Producto = Producto & " " & vbCrLf & "T I C K E T   No. 000-000-" & Format$(TFA.Factura, "0000000") & vbCrLf & " " & vbCrLf
				Cant_Ln = Cant_Ln + 1
			ElseIf TFA.TC = "NV" Then
				Producto = Producto & "Auto. SRI: " & Autorizacion & " - Caduca: " & MidStrg(UCaseStrg(MesesLetras(Month(Fecha_Vence))), 1, 3) & "/" & Year(Fecha_Vence) & vbCrLf & " " & vbCrLf _
						& "NOTA DE VENTA No. " & SerieFactura & "-" & Format$(TFA.Factura, "0000000") & vbCrLf & " " & vbCrLf
				Cant_Ln = Cant_Ln + 2
			Else
				Producto = Producto & "Auto. SRI: " & Autorizacion & " - Caduca: " & MidStrg(UCaseStrg(MesesLetras(Month(Fecha_Vence))), 1, 3) & "/" & Year(Fecha_Vence) & vbCrLf & " " & vbCrLf _
						& "FACTURA No. " & SerieFactura & "-" & Format$(TFA.Factura, "0000000") & vbCrLf & " " & vbCrLf
				Cant_Ln = Cant_Ln + 2
			End If
		Else
			Producto = vbCrLf & " " & vbCrLf & " " & vbCrLf & " " & vbCrLf & " " & vbCrLf _
					& "Transaccion(" & TFA.TC & ") No." & Format$(TFA.Factura, "0000000") & vbCrLf & " " & vbCrLf
			Cant_Ln = Cant_Ln + 4
		End If
		Producto = Producto & "Fecha: " & FechaSistema & " - Hora: " & .fields("Hora") & vbCrLf
		Producto = Producto & "Cliente: " & vbCrLf _
					& MidStrg(.fields("Cliente"), 1, 33) & vbCrLf
		Producto = Producto & "R.U.C./C.I.: " & .fields("CI_RUC") & vbCrLf _
					& "Cajero: " & MidStrg(CodigoUsuario, 1, 6) & vbCrLf
		If .fields("Telefono") <> Ninguno Then Producto = Producto & "Telefono: " & .fields("Telefono") & vbCrLf
		If .fields("Direccion") <> Ninguno Then Producto = Producto & "Direccion: " & vbCrLf & .fields("Direccion") & vbCrLf
		If .fields("Email") <> Ninguno Then Producto = Producto & "Email: " & vbCrLf & .fields("Email") & vbCrLf
		Producto = Producto & String$(CantGuion, "-") & vbCrLf _
					& "PRODUCTO/Cant x PVP/TOTAL" & vbCrLf _
					& String$(CantGuion, "-") & vbCrLf
					Efectivo = .fields("Efectivo")
		Cant_Ln = Cant_Ln + 6
	End If
	End With
	'Comenzamos a recoger los detalles de la factura
	If TFA.TC = "PV" Then
		sSQL = "SELECT DF.*,CP.Detalle,CP.Codigo_Barra " _
			& "FROM Trans_Ticket As DF,Catalogo_Productos As CP " _
			& "WHERE DF.Ticket = " & TFA.Factura & " " _
			& "AND DF.TC = '" & TFA.TC & "' " _
			& "AND DF.Item = '" & NumEmpresa & "' " _
			& "AND DF.Periodo = '" & Periodo_Contable & "' " _
			& "AND DF.Item = CP.Item " _
			& "AND DF.Periodo = CP.Periodo " _
			& "AND DF.Codigo_Inv = CP.Codigo_Inv " _
			& "ORDER BY DF.D_No "
	Else
		sSQL = "SELECT DF.*,CP.Detalle,CP.Codigo_Barra " _
			& "FROM Detalle_Factura As DF,Catalogo_Productos As CP " _
			& "WHERE DF.Factura = " & TFA.Factura & " " _
			& "AND DF.TC = '" & TFA.TC & "' " _
			& "AND DF.Item = '" & NumEmpresa & "' " _
			& "AND DF.Periodo = '" & Periodo_Contable & "' " _
			& "AND DF.Item = CP.Item " _
			& "AND DF.Periodo = CP.Periodo " _
			& "AND DF.Codigo = CP.Codigo_Inv " _
			& "ORDER BY DF.Codigo "
	End If
	Select_AdoDB AdoDBDetalle, sSQL
	With AdoDBDetalle
	If .RecordCount > 0 Then
		Do While (Not .EOF)
			Producto = Producto & .fields("Producto") & vbCrLf _
					& SetearBlancos(CStr(.fields("Cantidad")) & "x" & Format$(.fields("Precio"), "#,##0.00"), 12, 0, False) & " " _
					& SetearBlancos(CStr(.fields("Total")), CantGuion - 13, 0, True, , True) & vbCrLf
			Total = Total + .fields("Total")
			If TFA.TC <> "PV" Then Total_IVA = Total_IVA + .fields("Total_IVA")
			Cant_Ln = Cant_Ln + 1
			.MoveNext
		Loop
	End If
	End With
	'Pie de factura
	'===========================================================
	With AdoDBFactura
	If .RecordCount > 0 Then
		If TFA.TC = "PV" Then
			SubTotal = .fields("Total")
			Total = .fields("Total")
			Total_IVA = 0
			Total_Servicio = 0
			Total_Desc = 0
		Else
			SubTotal = .fields("SubTotal")
			Total = .fields("Total_MN")
			Total_IVA = .fields("IVA")
			Total_Servicio = .fields("Servicio")
			Total_Desc = .fields("Descuento")
		End If
		Producto = Producto & String$(CantGuion, "-") & vbCrLf
		Cant_Ln = Cant_Ln + 1
		'If Total_IVA Then
		If (CantGuion - 26) > 0 Then CantBlancos = String$(CantGuion - 26, " ") Else CantBlancos = ""
			Producto = Producto _
					& CantBlancos & "     SUBTOTAL " & SetearBlancos(CStr(SubTotal), 12, 0, True, False, True) & vbCrLf _
					& CantBlancos & "    I.V.A " & Porc_IVA * 100 & "% " & SetearBlancos(CStr(Total_IVA), 12, 0, True, False, True) & vbCrLf
			Cant_Ln = Cant_Ln + 1
			
		If Total_Servicio > 0 Then
			Producto = Producto _
					& CantBlancos & "     SERVICIO " & SetearBlancos(CStr(Total_Servicio), 12, 0, True, False, True) & vbCrLf
			Cant_Ln = Cant_Ln + 1
		End If
		If Total_Desc > 0 Then
			Producto = Producto _
					& CantBlancos & "    DESCUENTO " & SetearBlancos(CStr(Total_Desc), 12, 0, True, False, True) & vbCrLf
			Cant_Ln = Cant_Ln + 1
		End If
		If TFA.TC = "PV" Then
			Producto = Producto & CantBlancos & "TOTAL TICKET  "
		ElseIf TFA.TC = "NV" Then
			Producto = Producto & CantBlancos & "TOTAL NOTA V. "
		Else
			Producto = Producto & CantBlancos & "TOTAL FACTURA "
		End If
		Producto = Producto & SetearBlancos(CStr(Total), 12, 0, True, False, True) & vbCrLf
		If Efectivo > 0 Then
			Producto = Producto _
					& CantBlancos & "     EFECTIVO " & SetearBlancos(CStr(Efectivo), 12, 0, True, False, True) & vbCrLf _
					& CantBlancos & "       CAMBIO " & SetearBlancos(CStr(Efectivo - Total), 12, 0, True, False, True) & vbCrLf
		End If
		If TFA.TC <> "PV" Then
			Producto = Producto & "ORIGINAL: CLIENTE" & vbCrLf _
								& "COPIA   : EMISOR" & vbCrLf
			If .fields("Cotizacion") > 0 Then Producto = Producto & "COTIZACION: " & Format$(.fields("Cotizacion"), "#,##0.00") & vbCrLf
		End If
		Producto = Producto & String$(CantGuion, "=") & vbCrLf
		If TFA.TC = "PV" Then Producto = Producto & "RECLAME SU FACTURA EN CAJA" & vbCrLf
		Producto = Producto & "  GRACIAS POR SU COMPRA " & vbCrLf & " " & vbCrLf _
					& " " & vbCrLf & " " & vbCrLf & " " & vbCrLf
		Cant_Ln = Cant_Ln + Cant_Item_PV
	End If
	End With
	'Enviamos a la Impresora
	'TipoCourier
	'TipoConsola
	'TipoCourierNew
	Printer.FontName = TipoCourierNew
	If Copia_PV Then
		If Cant_Item_PV < Cant_Ln Then Cant_Item_PV = Cant_Ln
		Cadena = ""
		Cant_Ln = Cant_Item_PV - Cant_Ln
		If Cant_Ln <= 0 Then Cant_Ln = 1
		For I = 1 To Cant_Ln
			Cadena = Cadena & "` " & vbCrLf
		Next I
		Producto = Producto & Cadena & Producto & vbCrLf & Cadena
	End If
	PrinterTexto 0.5, PosLinea, Producto
	Printer.EndDoc
	AdoDBDetalle.Close
	AdoDBFactura.Close
	End If
	RatonNormal
	Exit Sub
	Errorhandler:
		RatonNormal
		ErrorDeImpresion
		Exit Sub
	End Sub

	*/


	function AdoLinea($parametros)
	{
		/*$emision = date('Y-m-d');
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
		$parametros['SerieFactura'] = $serie;*/

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
		$direcciones = $this->modelo->ClientesDEDireccion($parametros);
		$horarioEnt = $this->modelo->ClientesDEHoraEntrega($parametros);
		
		$datos = array(
			"direcciones" => $direcciones,
			"horarioEnt" => $horarioEnt[0]['Hora_Ent']
		);
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
		if (!isset($parametros['Serie'])) {
			$parametros['Serie'] = $_SESSION['INGRESO']['Serie_FA'];
		}
		$Porc_Iva = floatval($parametros['PorcIva']/100);
		$TextVUnit = $parametros['TextVUnit'];
		$TextCant = $parametros['TextCant'];
		$TipoFactura = $parametros['TC'];
		$TxtDocumentos = $parametros['TxtDocumentos'];
		$Real1 = $parametros['VTotal'];
		$TxtRifaD = '.';
		$TxtRifaH = '.';
		$TextServicios = $parametros['TextServicios'];
		$CodigoL = '.';
		$Serie = '.';
		$producto = Leer_Codigo_Inv($parametros['Codigo'], $parametros['fecha'], $parametros['CodBod']);
		$CodigoL2 = $this->modelo->catalogo_lineas($parametros['TC'], $parametros['Serie'], $parametros['fecha'], $parametros['fecha'], $electronico);
		if (count($CodigoL2) > 0) {
			$CodigoL = $CodigoL2[0]['Codigo'];
			$Serie = $CodigoL2[0]['Serie'];
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
			//$VTotal = number_format($Real1, 2, '.', '');
			//$Real1 = 0;
			$Real2 = 0;
			$Real3 = 0;
			if (is_numeric($TextVUnit) and is_numeric($TextCant)) {
				// 'If Val(TextVUnit) = 0 Then TextVUnit = "0.01"
				if (intval($TextCant) == 0) {
					$TextCant = "1";
				}
				/*if ($parametros['opc'] == 'OpcMult') {
					$Real1 = $TextCant * $TextVUnit;
				} else {
					$Real1 = $TextCant / $TextVUnit;
				}*/
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
				//$VTotal = number_format($Real1, 2, '.', '');
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
					if(isset($parametros['cheking'])){
						SetAdoFields('Cheking', $parametros['cheking']);
					}
					SetAdoFields('Serie', $Serie);
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
	function actualizarAsientoF($parametros)
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
		$TxtRifaD = '.';
		$TxtRifaH = '.';
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
			//$VTotal = number_format($Real1, 2, '.', '');
			//$Real1 = 0;
			$Real2 = 0;
			$Real3 = 0;
			if (is_numeric($TextVUnit) and is_numeric($TextCant)) {
				// 'If Val(TextVUnit) = 0 Then TextVUnit = "0.01"
				if (intval($TextCant) == 0) {
					$TextCant = "1";
				}
				/*if ($parametros['opc'] == 'OpcMult') {
					$Real1 = $TextCant * $TextVUnit;
				} else {
					$Real1 = $TextCant / $TextVUnit;
				}*/
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
				//$VTotal = number_format($Real1, 2, '.', '');
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
					//print_r('funciona hasta aqui');die();

					SetAdoAddNew('Asiento_F');
					//SetAdoFields('CODIGO', $articulo['Codigo_Inv']);
					//SetAdoFields('CODIGO_L', $CodigoL);
					//SetAdoFieldsWhere('PRODUCTO', $articulo['Producto']);
					//SetAdoFields('Tipo_Hab', substr($TxtDocumentos, 0, 40));
					SetAdoFields('CANT', number_format(floatval($TextCant), 2, '.', ''));
					SetAdoFields('PRECIO', number_format($TextVUnit, $_SESSION['INGRESO']['Dec_PVP'], '.', ''));
					SetAdoFields('TOTAL', $Real1);
					SetAdoFields('Total_IVA', $Real3);
					//SetAdoFields('Item', $_SESSION['INGRESO']['item']);
					//SetAdoFields('CodigoU', $_SESSION['INGRESO']['CodigoU']);
					//SetAdoFields('Codigo_Cliente', $parametros['CodigoCliente']);
					//SetAdoFields('A_No', $A_No + 1);
					//SetAdoFields('CodBod', $parametros['CodBod']);
					SetAdoFields('COSTO', $articulo['Costo']);
					SetAdoFields('Total_Desc', $Dscto);
					SetAdoFields('Cheking', $parametros['cheking']);
					SetAdoFields('SERVICIO', $TextServicios);
					SetAdoFields('RUTA', $parametros['comentario']);
					if ($articulo['Costo'] > 0) {
						SetAdoFields('Cta_Inv', $articulo['Cta_Inventario']);
						SetAdoFields('Cta_Costo', $articulo['Cta_Costo_Venta']);
					}
					SetAdoFieldsWhere('CODIGO', $articulo['Codigo_Inv']);
					SetAdoFieldsWhere('CODIGO_L', $CodigoL);
					SetAdoFieldsWhere('Item', $_SESSION['INGRESO']['item']);
					SetAdoFieldsWhere('PRODUCTO', $articulo['Producto']);
					SetAdoFieldsWhere('Codigo_Cliente', $parametros['CodigoCliente']);
					SetAdoFieldsWhere('Serie', $parametros['Serie']);
					
					return SetAdoUpdateGeneric();

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

	function grabar_evaluacion($parametros){
		$arrEvaluaciones = $parametros['evaluaciones'];
		$resp = 1;
		//print_r($parametros);
		foreach($arrEvaluaciones as $key => $value){
			//print_r($value);
			SetAdoAddNew("Clientes_Datos_Extras");
			SetAdoFields("Item",$_SESSION['INGRESO']['item']);
			SetAdoFields("Tipo_Dato", "EVALUACI");
			SetAdoFields("Codigo",$parametros["cliente"]);
			SetAdoFields("Acreditacion",$value["cod_inv"]);
			//SetAdoFields("TP",".");
			SetAdoFields("GC",$value['bueno']);
			SetAdoFields("Causa",$parametros['comentario']);
			if(SetAdoUpdate()!=1)
			{
				$resp = 0;
			} 
		}
		return array('res' => $resp);
	}

	function grabar_gavetas($parametros){
		$arrGavetas = array();
		$res1 = 1;
		if(isset($parametros['gavetas']))
		{
			$arrGavetas = $parametros['gavetas'];
			$res1 = 0;
		}
		foreach($arrGavetas as $key => $value){
			$producto = Leer_Codigo_Inv($value['cod_inv'], $parametros["fecha"], $CodBodega='', $CodMarca='');
			$gaveta = $producto['datos'];
			$existencia = $this->modelo->existenciaProducto($parametros["cliente"], $value["cod_inv"]);

			if($value['entregadas'] != 0){
				
				$existencia += $value['entregadas'];
				SetAdoAddNew("Trans_Kardex");
				SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
				SetAdoFields("Item",$_SESSION['INGRESO']['item']);
				SetAdoFields("T",$gaveta['Tipo_SubMod']);
				//SetAdoFields("TP",".");
				SetAdoFields("CodBodega",".");
				SetAdoFields("Codigo_Barra",$gaveta["Codigo_Barra"]);
				SetAdoFields("Codigo_Inv",$value["cod_inv"]);
				SetAdoFields("Fecha",$parametros["fecha"]);
				SetAdoFields("Entrada", $value["entregadas"]);
				SetAdoFields("Salida", 0);
				SetAdoFields("Existencia",$existencia);
				SetAdoFields("Cta_Inv",$gaveta["Cta_Inventario"]);
				SetAdoFields("Contra_Cta",$gaveta["Cta_Costo_Venta"]);
				SetAdoFields("Codigo_P",$parametros["cliente"]);
				SetAdoFields("Fecha_Fab",$parametros["fecha"]);
				SetAdoFields("Fecha_Exp",$parametros["fecha"]);
				SetAdoFields("TC",$parametros["TC"]);
				SetAdoFields("Serie",$parametros["serie"]);
				$res1 = SetAdoUpdate();
				
			}

			if($value['devueltas'] != 0){
				if($value['entregadas'] == 0 || ($value['entregadas'] != 0 && $res1 != 0)){
					$existencia -= $value['devueltas'];
					SetAdoAddNew("Trans_Kardex");
					SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
					SetAdoFields("Item",$_SESSION['INGRESO']['item']);
					SetAdoFields("T",$gaveta['Tipo_SubMod']);
					//SetAdoFields("TP",".");
					SetAdoFields("CodBodega",".");
					SetAdoFields("Codigo_Barra",$gaveta["Codigo_Barra"]);
					SetAdoFields("Codigo_Inv",$value["cod_inv"]);
					SetAdoFields("Fecha",$parametros["fecha"]);
					SetAdoFields("Entrada", 0);
					SetAdoFields("Salida", $value['devueltas']);
					SetAdoFields("Existencia",$existencia);
					SetAdoFields("Cta_Inv",$gaveta["Cta_Inventario"]);
					SetAdoFields("Contra_Cta",$gaveta["Cta_Costo_Venta"]);
					SetAdoFields("Codigo_P",$parametros["cliente"]);
					SetAdoFields("Fecha_Fab",$parametros["fecha"]);
					SetAdoFields("Fecha_Exp",$parametros["fecha"]);
					SetAdoFields("TC",$parametros["TC"]);
					SetAdoFields("Serie",$parametros["serie"]);
					$res1 = SetAdoUpdate();
				}
			}
			/*SetAdoAddNew("Trans_Kardex");
			SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
			SetAdoFields("Item",$_SESSION['INGRESO']['item']);
			SetAdoFields("T",$gaveta['Tipo_SubMod']);
			//SetAdoFields("TP",".");
			SetAdoFields("CodBodega",".");
			SetAdoFields("Codigo_Barra",$gaveta["Codigo_Barra"]);
			SetAdoFields("Codigo_Inv",$value["cod_inv"]);
			SetAdoFields("Fecha",$parametros["fecha"]);
			SetAdoFields("Entrada",$value["devueltas"]);
			SetAdoFields("Salida",$value["entregadas"]);
			SetAdoFields("Existencia",$value["pendientes"]);
			SetAdoFields("Cta_Inv",$value["Cta_Inventario"]);
			SetAdoFields("Contra_Cta",$value["Cta_Costo_Venta"]);
			SetAdoFields("Codigo_P",$parametros["cliente"]);
			SetAdoFields("Fecha_Fab",$parametros["fecha"]);
			SetAdoFields("Fecha_Exp",$parametros["fecha"]);
			SetAdoFields("TC",$parametros["TC"]);*/

			/*SetAdoFields("Numero",$Numero);
			SetAdoFields("Fecha",$Fecha);
			SetAdoFields("Codigo_Dr",$value["Codigo_Dr"]);// ' C1.CodigoDr
			SetAdoFields("Codigo_Tra",$value["Codigo_Tra"]); // ' C1.CodigoDr
			SetAdoFields("Codigo_P",$value["Codigo_B"]);
			SetAdoFields("Descuento",$value["P_DESC"]);
			SetAdoFields("Descuento1",$value["P_DESC1"]);
			SetAdoFields("Valor_Total",$value["VALOR_TOTAL"]);
			SetAdoFields("Existencia",$value["CANTIDAD"]);
			SetAdoFields("Valor_Unitario",$value["VALOR_UNIT"]);
			SetAdoFields("Total",$value["SALDO"]);
			SetAdoFields("Cta_Inv",$value["CTA_INVENTARIO"]);
			SetAdoFields("Contra_Cta",$value["CONTRA_CTA"]);
			SetAdoFields("Orden_No",$value["ORDEN"]);
			SetAdoFields("CodMarca",$value["CodMar"]);
			
			SetAdoFields("Costo",$value["VALOR_UNIT"]);
			SetAdoFields("PVP",$value["PVP"]);
			SetAdoFields("No_Refrendo",$value["No_Refrendo"]);
			SetAdoFields("Lote_No",$value["Lote_No"]);
			SetAdoFields("Fecha_Fab",$value["Fecha_Fab"]);
			SetAdoFields("Fecha_Exp",$value["Fecha_Exp"]);
			SetAdoFields("Modelo",$value["Modelo"]);
			SetAdoFields("Serie_No",$value["Serie_No"]);
			SetAdoFields("Procedencia",$value["Procedencia"]);*/
		}
		if($res1){
			return array(
				"res" => 1,
				"contenido" => "Se guardaron las gavetas correctamente."
			);
		}else{
			return array(
				"res" => 0,
				"contenido" => "No se pudieron guardar las gavetas."
			);
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
				//$FA['Nota'] = $parametros['TxtNota'];
				//$FA['Observacion'] = $parametros['TxtObservacion'];
				//$FA['Gavetas'] = intval($parametros['TxtGavetas']);
				$FA['codigoCliente'] = $parametros['CodigoCliente'];
				$FA['CodigoC'] = $parametros['CodigoCliente'];
				$FA['TextCI'] = $parametros['CI'];
				$FA['TxtEmail'] = $parametros['email'];
				$FA['Cliente'] = trim(str_replace($parametros['CI'] . ' -', '', $parametros['NombreCliente']));
				$FA['TC'] = $parametros['TC'];
				/*if($parametros['TC'] == "NDO" || $parametros['TC'] == "NDU"){
					$FA['TC'] = "DO";
				}else{
				}*/
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
				$FA['TxtPorcIva'] = $parametros['PorcIva'];
				$FA['Porc_IVA'] = (floatval($parametros['PorcIva'])/100);
				$FA['FATextVUnit'] = $parametros['FATextVUnit'];
				$FA['FAVTotal'] = $parametros['FAVTotal'];
				$FA['FACodLinea'] = $parametros['FACodLinea'];
				if(isset($parametros['Comprobante'])){
					$FA['Comprobante'] = $parametros['Comprobante'];
				}

				$Moneda_US = False;
				$TextoFormaPago = G_PAGOCONT;
				// print_r($parametros);die();	       
				//return $this->ProcGrabar($FA);
				$r = $this->ProcGrabar($FA);
				if($r['respuesta'] == 1){
					// Hacer el borrado Trans_Comision
					$r2 = $this->generar_factura_FA($FA, $r);
					$this->modelo->EliminarTransComision($FA['Fecha'], $FA['CodigoC'], $parametros['CodigoU']);
					return $r2;
				}

				return $r;
			//} else {
			//	return array('respuesta' => -1, 'text' => "Cuenta CxC sin setear en catalogo de lineas");
			//}
		} else {
			return array('respuesta' => -5, 'text' => "El Efectivo no alcanza para grabar");
		}
	}

	function generar_factura_FA($FA, $res){
		$FA['TC'] = 'FA';
		$params = array(
			'TextVUnit' => $FA['FATextVUnit'],
			'VTotal' => $FA['FAVTotal'],
			'fecha' => $FA['Fecha'],
			'CodigoCliente' => $FA['codigoCliente'],
			'PorcIva' => $FA['TxtPorcIva'],
			'TC' => $FA['TC'],
			'TextCant' => 1,
			'TxtDocumentos' => '.',
			'Codigo' => 'FA.99',
			'CodBod' => '',
			'TextServicios' => '.',
			'TextVDescto' => '0',
		);
		$this->IngresarAsientoF($params);

		$Lineas = $this->modelo->DCLinea($FA['TC'], $FA['Fecha'], $FA['FACodLinea']);
		$FA['Serie'] = $Lineas[0]['Serie'];
		$FA['Cta_CxP'] = $Lineas[0]['CxC'];
		$FA['Autorizacion'] = $Lineas[0]['Autorizacion'];
		$FA['CodigoL'] = $Lineas[0]['Codigo'];

		$FATotales = Calculos_Totales_Factura();
		$FA['SubTotal'] = $FATotales['SubTotal'];
		$FA['Con_IVA'] = $FATotales['Con_IVA'];
		$FA['Sin_IVA'] = $FATotales['Sin_IVA'];
		$FA['Descuento'] = $FATotales['Descuento'];
		$FA['Total_IVA'] = $FATotales['Total_IVA'];
		$FA['Total_MN'] = $FATotales['Total_MN'];
		$FA['Total_ME'] = $FATotales['Total_ME'];
		$FA['Descuento2'] = $FATotales['Descuento2'];
		$FA['Descuento_0'] = $FATotales['Descuento_0'];
		$FA['Descuento_X'] = $FATotales['Descuento_X'];
		$FA['Servicio'] = $FATotales['Servicio'];

		$FA['Total'] = $FA['Total_MN'];

		$r = $this->ProcGrabar($FA);

		$r2 = array(0 => $r, 1 => $res);
		return $r2;
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
		// print_r($FA);die();
		//Grabar_Factura1($FA);
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
			} else if ($TipoFactura == "NDO" || $TipoFactura == "NDU") {
				Control_Procesos("F", "Grabar Nota de Donacion No. " . $Factura_No, '');
			} else {
				Control_Procesos("F", "Grabar Factura No. " . $Factura_No, '');
			}
			// $this->modelo->delete_factura($TipoFactura,$Factura_No);

			$TextoFormaPago = G_PAGOCRED;
			$T = G_PENDIENTE;
			// 'Grabamos el numero de factura

			//print_r($FA);die();
			$r = Grabar_Factura1($FA);
			//print_r($FA['TC']);die();
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
				if(isset($FA['Comprobante'])){$TA['Comprobante'] = $FA['Comprobante'];}
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
			//print_r($FA);die();

			if (strlen($FA['Autorizacion']) >= 13) {

				// print_r('si');die();
				// print_r('drrrrddd');die();
				// print_r($FA)
				$imp_guia = '';
				if ($FA['TC'] <> "NDO" || $FA['TC'] <> "NDU") {
					//la respuesta puede se texto si envia numero significa que todo saliobien
					$rep = $this->sri->Autorizar_factura_o_liquidacion($FA);

					// print_r($rep);die();
					$clave = $this->sri->Clave_acceso($TA['Fecha'], '01', $TA['Serie'], $Factura_No);
					
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

							return array('respuesta' => $rep, 'pdf' => $imp, 'clave' => $clave,'rodillo' => $_SESSION['INGRESO']['Impresora_Rodillo']);
						} else {

							$this->pdf->Imprimir_Punto_Venta_Grafico($TFA);
							return array('respuesta' => $rep, 'pdf' => $imp, 'clave' => $clave, 'rodillo' => $_SESSION['INGRESO']['Impresora_Rodillo']);
						}

					} else {
						return array('respuesta' => -1, 'pdf' => $imp, 'text' => $rep, 'clave' => $clave,'rodillo' => $_SESSION['INGRESO']['Impresora_Rodillo']);
					}
				}
			} else {
				// print_r('dddd');die();
				if ($Grafico_PV) {
					$TFA = Imprimir_Punto_Venta_Grafico_datos($FA);
					$TFA['PorcIva'] = $FA['Porc_IVA'];
					$this->pdf->Imprimir_Punto_Venta_Grafico($TFA);
					//Imprimir_Punto_Venta_Grafico($TFA);
				} else {
					$TFA = Imprimir_Punto_Venta_Grafico_datos($FA);
					$TFA['CLAVE'] = '.';
					$TFA['PorcIva'] = $FA['Porc_IVA'];
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
			//print_r($imp);
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