<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
//require('fpdf.php');
require('PDF_MC_Table.php');

if (isset($_POST['nombrep'])) {
	$nombre = addslashes($_POST['nombrep']);
	$tabla = addslashes($_POST['sep']);
	$pagina = addslashes($_POST['paginap']);
	$campo = addslashes($_POST['campo']);
	$valor = addslashes($_POST['valor']);
	$empresa = addslashes($_POST['empresa']);
	$rif = addslashes($_POST['rif']);
	$va = split("=", $valor);
	//echo $nombre.' 2 '.$tabla.' 3 '.$pagina.' 4 '.$campo.' 5 '.$va[1];
	//die();
}

class PDF extends PDF_MC_Table
{

}

function url_logo($logoName = false)
{
	$logo = $_SESSION['INGRESO']['Logo_Tipo'];
	if ($logoName) {
		$logo = $logoName;
	}

	$src_jpg = dirname(__DIR__, 2) . '/img/logotipos/' . $logo . '.jpg';
	//gif
	$src_gif = dirname(__DIR__, 2) . '/img/logotipos/' . $logo . '.gif';
	//png
	$src_png = dirname(__DIR__, 2) . '/img/logotipos/' . $logo . '.png';

	if (@getimagesize($src_png)) {
		return $src_png;
	} else if (@getimagesize($src_jpg)) {
		return $src_jpg;
	} else if (@getimagesize($src_gif)) {
		return $src_gif;
	} else {
		return '.';
	}
	//En caso de que ninguno de los 3 exista, no se muestra nada como logo. 
}

//Fin de la clase
//para imprimir factura y prefactura en formato pequeño
function imprimirDocElPF($stmt, $id = null, $formato = null, $nombre_archivo = null, $va = null, $imp1 = null, $param = null, $tipof = null, $conec = null, $ruta = null)
{
	if ($ruta == null) {
		$ruta = '../ajax/TEMP/';
	}
	if ($tipof != null) {
		$tipo = $tipof;
	} else {
		$tipo = 'PF';
	}
	// datos que se deben llenar desde la base de datos 
	$fecha = date("Y-m-d");
	$hoy = date('H:m:s');
	//obtenemos informacion
	if ($conec != null) {
		$cid = $conec;
	} else {
		$cid = cone_ajaxSQL();
	}
	$sql = "SELECT * FROM  Empresas WHERE
		Item = '" . $_SESSION['INGRESO']['item'] . "' 
	";

	//echo $sql;
	$stmt = sqlsrv_query($cid, $sql);
	if ($stmt === false) {
		echo "Error en consulta PA.\n";
		die(print_r(sqlsrv_errors(), true));
	}
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
		$nome = $row[6];
		$ruce = $row[8];
		$tele = $row[9];
		$dire = $row[12];
		$suc = $row[20];
		if ($suc == '' or $suc == null) {
			$suc = $dire;
		}
	}
	//datos cliente
	//$cid=cone_ajaxSQL();
	if ($tipo == 'PF') {
		$sql = " SELECT TOP(1) * FROM  Clientes
		WHERE  (CI_RUC LIKE '%9999999%')
		";
	} else {
		$sql = " SELECT * FROM  Clientes
		WHERE  (CI_RUC = '" . $param[0]['ruc'] . "')
		";
	}
	//echo $sql;
	//die();
	$stmt = sqlsrv_query($cid, $sql);
	if ($stmt === false) {
		echo "Error en consulta PA.\n";
		die(print_r(sqlsrv_errors(), true));
	}
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
		$cli = $row[5];
		$direcc = $row[17];
		$email = $row[14];
		$telef = $row[20];
	}
	if ($tipo == 'PF') {
		$sql = "SELECT  *
		FROM            Asiento_F
		WHERE        (HABIT = '" . $param[0]['mesa'] . "')";
		$stmt = sqlsrv_query($cid, $sql);
		if ($stmt === false) {
			echo "Error en consulta PA.\n";
			die(print_r(sqlsrv_errors(), true));
		}
		$preciot = 0;
		$iva = 0;
		$tota = 0;
		$i = 0;
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
			$precio = $row[9];
			$tot = $row[10];
			$pre = $row[4];
			$tota = $tot + $tota;
			$iva = $iva + $row[7];
			$cantidad = $row[1];
			$detalle = $row[3];
			$preciot = $preciot + $precio;
			$lineas[$i]['cant'] = $cantidad;
			if ($row[7] == 0) {
				$lineas[$i]['detalle'] = $detalle . ' (E)';
			} else {
				$lineas[$i]['detalle'] = $detalle;
			}
			$lineas[$i]['pvp'] = $pre;
			$lineas[$i]['total'] = $precio;
			$i++;
		}
		$datos = array(
			'numfactura' => '0',
			'numautorizacio' => '0',
			'fechafac' => $fecha,
			'horafac' => $hoy,
			'razon' => '.',
			'ci' => '0',
			'telefono' => $telef,
			'email' => $email,
			'subtotal' => $preciot,
			'dto' => '0.00',
			'iva' => $iva,
			'totalfac' => $tota
		);
	} else {
		$sql = "SELECT  *
		FROM     Detalle_Factura
		WHERE    (Item = '" . $_SESSION['INGRESO']['item'] . "') AND (Serie = '" . $param[0]['serie'] . "') 
		AND (Periodo = '" . $_SESSION['INGRESO']['periodo'] . "') AND (Factura = '" . $param[0]['factura'] . "')";
		$stmt = sqlsrv_query($cid, $sql);
		if ($stmt === false) {
			echo "Error en consulta PA.\n";
			die(print_r(sqlsrv_errors(), true));
		}
		$preciot = 0;
		$iva = 0;
		$tota = 0;
		$i = 0;
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
			$precio = $row[10];
			$tot = $row[11] + $row[12];
			$pre = $row[10];
			$tota = $tot + $tota;
			$iva = $iva + $row[12];
			$cantidad = $row[9];
			$detalle = $row[8];
			$preciot = $preciot + ($precio * $cantidad);
			$lineas[$i]['cant'] = $cantidad;
			if ($row[12] == 0) {
				$lineas[$i]['detalle'] = $detalle . ' (E)';
			} else {
				$lineas[$i]['detalle'] = $detalle;
			}
			$lineas[$i]['pvp'] = $pre;
			$lineas[$i]['total'] = $precio * $cantidad;
			$i++;
		}
		$datos = array(
			'numfactura' => $param[0]['factura'],
			'numautorizacio' => '0',
			'fechafac' => $fecha,
			'horafac' => $hoy,
			'razon' => $cli,
			'ci' => $param[0]['ruc'],
			'telefono' => $telef,
			'email' => $email,
			'subtotal' => $preciot,
			'dto' => '0.00',
			'iva' => $iva,
			'totalfac' => $tota
		);
		/*$datos = array('numfactura' => '0','numautorizacio' =>'0','fechafac'=>$fecha,
								'horafac' => $hoy,'razon'=>$cli,'ci'=>'1234567890',
								'telefono' =>'09999999999','email' =>'example@example.com','subtotal'=>'450.00','dto'=>'0.00','iva'=>'54.00','totalfac'=>'504.00' );
								
								$lineas = array(
								'0'=>array('cant' => '2','detalle'=>' servicio de mantenimineto','pvp'=>'450.00','total'=>'450.00' ),
								'1'=>array('cant' => '3','detalle'=>' servicio de mantenimineto de servidores','pvp'=>'450.00','total'=>'450.00' ),
								'2'=>array('cant' => '3','detalle'=>' servicio de mantenimineto de servidores','pvp'=>'450.00','total'=>'450.00' ),  );*/
	}

	// fin de datos de la base de datos




	$pdf = new FPDF('P', 'mm', array(90, 300));
	$pdf->AddPage();
	$salto = 5;
	// Logo
	//$pdf->Image('../../img/jpg/logo_doc.jpg',3,3,35,20);
	//../../img/jpg/logo_doc.jpg

	$src = url_logo();
	if ($src !== '.') {
		$pdf->Image($src, 40, 22, 80, 40);
	} 

	// Arial bold 15
	$pdf->SetFont('Arial', 'B', 12);
	// Título
	$pdf->Cell(30);
	$pdf->Cell(0, 5, 'RUC', 0, 0, 'C');
	$pdf->Ln($salto);
	$pdf->Cell(30);
	$pdf->Cell(0, 5, $ruce, 0, 0);
	$pdf->Ln($salto);
	$pdf->Cell(30);
	$pdf->Cell(0, 5, 'Telefono:' . $tele . '', 0, 0);
	$pdf->Ln($salto);
	$pdf->Cell(0, 5, $nome, 0, 0, 'C');
	//$pdf->Ln($salto);
	//$pdf->Cell(0,5,$nome,0,0,'C');


	$pdf->Ln($salto);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Ln($salto);
	$pdf->MultiCell(70, 3, $dire);
	$pdf->Ln(2);
	$pdf->Cell(0, 3, 'OBLIGADO A LLEVAR CONTABILIDAD: SI', 0, 0);
	$pdf->Ln($salto);
	$pdf->Cell(0, 0, '', 1, 0);



	$pdf->SetFont('');
	$pdf->Ln(1);
	if ($tipo == 'F') {
		$pdf->Cell(0, 5, 'FACTURA No:' . $datos['numfactura'], 0, 0);
	} else {
		$pdf->Cell(0, 5, 'PREFACTURA No:', 0, 0);
	}
	$pdf->Ln($salto);
	$pdf->MultiCell(70, 3, 'NUMERO DE AUTORIZACION ' . $datos['numautorizacio'], 0, 'L');
	$pdf->Ln(1);
	$pdf->Cell(35, 5, 'FECHA:' . $datos['fechafac'], 0, 0);
	$pdf->Cell(35, 5, 'HORA:' . $datos['horafac'], 0, 0);
	$pdf->Ln($salto);
	$pdf->Cell(40, 5, 'AMBIENTE:Produccion', 0, 0);
	$pdf->Cell(30, 5, 'EMISION:Normal', 0, 0);
	$pdf->Ln($salto);

	$pdf->MultiCell(70, 3, 'CLAVE DE ACCESO ' . $datos['numautorizacio'], 0, 'L');
	$pdf->Ln(3);
	$pdf->Cell(0, 0, '', 1, 0);

	$pdf->Ln(3);
	$pdf->MultiCell(70, 3, 'Razon social/Nombres y Apellidos: ' . $datos['razon'], 0, 'L');
	$pdf->Cell(35, 5, 'Identificacion:' . $datos['ci'], 0, 0);
	$pdf->Cell(35, 5, 'Telefono:' . $datos['telefono'], 0, 0);
	$pdf->Ln($salto);
	$pdf->Cell(0, 0, 'Email.' . $datos['email'], 0, 0);
	$pdf->Ln(3);
	$pdf->Cell(0, 0, '', 1, 0);

	$pdf->Ln(1);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(10, 2, 'Cant', 0, 0);
	$pdf->Cell(28, 2, 'PRODUCTO', 0, 0, 'C');
	$pdf->Cell(15, 2, 'P.V.P', 0, 0, 'R');
	$pdf->Cell(17, 2, 'TOTAL', 0, 0, 'R');
	$pdf->Ln($salto);

	//    se cargan las lineas de la factura
	foreach ($lineas as $value) {

		$pdf->SetFont('Arial', '', 8);
		$pdf->Cell(10, 3, $value['cant'], 0, 0);
		$y2 = $pdf->GetY();
		$pdf->MultiCell(28, 3, $value['detalle'], 0, 'L');
		$y = $pdf->GetY();
		$pdf->SetXY(48, $y2);
		$pdf->Cell(15, 3, number_format($value['pvp'], 2, $_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']), 0, 0, 'R');
		$pdf->Cell(17, 3, number_format($value['total'], 2, $_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']), 0, 0, 'R');
		$pdf->SetY($y);
		$pdf->Ln(3);
		$pdf->Cell(0, 0, '', 1, 0);
		$pdf->Ln(3);

	}
	// fin de carga de lineas de factura

	$pdf->SetFont('Arial', '', 8);
	$pdf->Cell(35, 2, 'Cajero', 0, 0, 'L');
	$pdf->Cell(17, 2, 'SUBTOTAL :', 0, 0, 'L');
	$pdf->Cell(17, 2, number_format($datos['subtotal'], 2, $_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']), 0, 0, 'R');
	$pdf->Ln(3);

	$pdf->Cell(35, 2, '', 0, 0, 'L');
	$pdf->Cell(17, 2, 'DESCUENTOS :', 0, 0, 'L');
	$pdf->Cell(17, 2, number_format($datos['dto'], 2, $_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']), 0, 0, 'R');
	$pdf->Ln(3);


	$pdf->Cell(35, 2, '', 0, 0, 'L');
	$pdf->Cell(17, 2, 'I.V.A 12% :', 0, 0, 'L');
	$pdf->Cell(17, 2, number_format($datos['iva'], 2, $_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']), 0, 0, 'R');
	$pdf->Ln(3);


	$y2 = $pdf->GetY();
	$pdf->MultiCell(35, 2, 'Su factura sera enviada al correo electronico registrado', 0, 'L');
	$y = $pdf->GetY();
	$pdf->SetXY(45, $y2);
	$pdf->Cell(17, 2, 'TOTAL :', 0, 0, 'L');
	$pdf->Cell(17, 2, number_format($datos['totalfac'], 2, $_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']), 0, 0, 'R');
	$pdf->Ln(3);
	$pdf->SetXY(45, $y);
	//$pdf->SetY($y);
	if ($tipo == 'PF') {
		$pdf->Cell(17, 2, 'Propina :', 0, 0, 'L');
		$pdf->Cell(17, 2, '', 0, 0, 'R');
		$pdf->SetY($y + 2);
	}
	$pdf->Ln(3);
	$pdf->Cell(0, 0, '', 1, 0);
	$pdf->Ln($salto);

	//agregamos las lineas para los datos
	if ($tipo == 'PF') {
		$pdf->SetFont('Arial', 'B', 8);
		$pdf->Cell(0, 5, 'Datos de factura', 0, 0, 'C');
		$pdf->Ln($salto);
		$pdf->SetFont('Arial', 'B', 10);
		/*$pdf->Cell(0,5,'Nombre  CI/RUC  Correo   Telf  DIR',0,0,'C');
								//$pdf->Cell(0,5,'TIPO PAGO                MONTO    ',0,0,'C');
								$pdf->Ln($salto);
								$pdf->Ln($salto);
								$pdf->Cell(0,0,'',1,0); 
								$pdf->Ln($salto);
								$pdf->Ln($salto);
								$pdf->Cell(0,0,'',1,0); 
								$pdf->Ln($salto);
								$pdf->Ln($salto);
								$pdf->Cell(0,0,'',1,0); 
								$pdf->Ln($salto);
								$pdf->Ln($salto);
								$pdf->Cell(0,0,'',1,0); 
								$pdf->Ln($salto);
								$pdf->Ln($salto);
								$pdf->Cell(0,0,'',1,0); 
								$pdf->Ln($salto);*/
		$pdf->Cell(0, 5, 'Nombre', 0, 0, 'L');
		//$pdf->Cell(0,5,'TIPO PAGO                MONTO    ',0,0,'C');

		$pdf->Ln($salto);
		$pdf->Ln($salto);
		$pdf->Cell(0, 0, '', 1, 0);
		$pdf->Ln($salto);
		$pdf->Cell(0, 5, 'CI/RUC', 0, 0, 'L');
		$pdf->Ln($salto);
		$pdf->Ln($salto);
		$pdf->Cell(0, 0, '', 1, 0);
		$pdf->Ln($salto);
		$pdf->Cell(0, 5, 'Correo', 0, 0, 'L');
		$pdf->Ln($salto);
		$pdf->Ln($salto);
		$pdf->Cell(0, 0, '', 1, 0);
		$pdf->Ln($salto);
		$pdf->Cell(0, 5, 'Telefono', 0, 0, 'L');
		$pdf->Ln($salto);
		$pdf->Ln($salto);
		$pdf->Cell(0, 0, '', 1, 0);
		$pdf->Ln($salto);
		$pdf->Cell(0, 5, 'Direccion', 0, 0, 'L');
		$pdf->Ln($salto);
		$pdf->Ln($salto);
		$pdf->Cell(0, 0, '', 1, 0);
		$pdf->Ln($salto);

	}

	$pdf->SetFont('Arial', 'B', 10);
	$pdf->Cell(0, 5, 'Fue un placer atenderle', 0, 0, 'C');
	$src = __DIR__ . '/../../img/png/cara_feliz.png';
	if (@getimagesize($src)) {
		$y = $pdf->GetY();
		$x = $pdf->GetX();
		$pdf->Image(__DIR__ . '/../../img/png/cara_feliz.png', ($x - 15), $y, 5, 5, '', 'https://www.discoversystem.com');
		$pdf->Image(__DIR__ . '/../../img/png/copa.png', ($x - 10), $y, 5, 5, '', 'https://www.discoversystem.com');
	}
	$pdf->Ln($salto);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell(0, 5, 'www.cofradiadelvino.com', 0, 0, 'C');
	$pdf->Ln($salto);


	if ($imp1 == null or $imp1 == 1) {
		$pdf->Output();
	}
	if ($imp1 == 0) {
		//echo dirname(__DIR__,1).'/php/vista/appr/ajax/TEMP/'.$id.'.pdf'."";
		//die();
		//$pdf->Output('TEMP/'.$id.'.pdf','F'); 
		//$pdf->Output('../ajax/TEMP/'.$id.'.pdf','F'); 
		$pdf->Output($ruta . $id . '.pdf', 'F');
	}
}
//imprimir doc electronico
/* $stmt= variable con datos xml $id id del campo $formato formato del reporte aqui es pdf
   $nombre_archivo ruta y nombre del archivo xml generado $va 0 para saber si lee de una variable o 1 un archivo xml
   $imp1 paqra saber si se descarga o no null o 1 descarga 0 es para temp y adjuntar al correo*/
function imprimirDocElP($stmt, $id = null, $formato = null, $nombre_archivo = null, $va = null, $imp1 = null, $param = null)
{
	$pdf = new PDF('P', 'pt', 'LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();


	$i = 0;
	if ($va == 1) {
		$autorizacion = simplexml_load_file($nombre_archivo);
	} else {
		$stmt = str_replace("ï»¿", "", $stmt);
		$autorizacion = simplexml_load_string($stmt);
	}
	$atrib = $autorizacion->attributes();

	//echo $autorizacion->fechaAutorizacion."<br>";
	//sustituimos
	$resultado = str_replace("<![CDATA[", "", $autorizacion->comprobante);
	$resultado = str_replace("]]>", "", $resultado);

	//echo etiqueta_xml($resultado,"<ruc>"); 
	//echo etiqueta_xml($resultado,"<razonSocial>"); 
	//echo $resultado;
	//$auto=simplexml_load_string($resultado);
	//echo ' ccc '.$auto->factura->infoTributaria;
	//die();
	$pdf->SetFont('Arial', 'B', 30);
	$x = 41;
	$pdf->SetXY($x, 20);
	//$pdf->SetWidths(array(250));
	$src = url_logo();
	if ($src !== '.') {
		$pdf->Image($src, 40, 22, 80, 40);
	} 

	/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
				{
					$logo=$_SESSION['INGRESO']['Logo_Tipo'];
				}
				else
				{
					$logo="diskcover";
				}
				$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,20,80,40,'','http://www.fpdf.org');*/
	//$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo.'.png');
	//$arr=array('NO TIENE LOGO');
	//$pdf->Row($arr,13);
	//panel
	/*
				SELECT        TOP (1) Periodo, TL, Codigo, Concepto, Fact, CxC, Cta_Venta, Logo_Factura, Largo, Ancho, Item, Individual, Espacios, Pos_Factura, Fact_Pag, Pos_Y_Fact, Serie, Autorizacion, Vencimiento, Fecha, Secuencial, ItemsxFA, 
								Grupo_I, Grupo_F, CxC_Anterior, Imp_Mes, Nombre_Establecimiento, Direccion_Establecimiento, Telefono_Estab, Logo_Tipo_Estab, Tipo_Impresion, ID, X
							FROM            Catalogo_Lineas
							WHERE        (LEN(Autorizacion) >= 13) AND (Periodo = '.') AND (Item = '001') AND (Fact = 'FA') AND (Fecha <= '2020-03-11') AND (Vencimiento >= '2020-03-11')
				
				SELECT        Opc, Grupo, Item, Fecha, Ciudad, Pais, Empresa, Gerente, RUC, Telefono1, Telefono2, FAX, Direccion, SubDir, Logo_Tipo, Alto, Servicio, S_M, Cta_Caja, Cotizacion, Sucursal, Email, Contador, CodBanco, Num_CD, 
					 Num_CE, Num_CI, Nombre_Comercial, Mod_Fact, Mod_Fecha, Plazo_Fijo, Det_Comp, CI_Representante, TD, RUC_Contador, CPais, No_Patronal, Dec_PVP, Dec_Costo, CProv, Grabar_PV, Num_Meses, Separar_Grupos, Credito, 
					 Medio_Rol, Sueldo_Basico, Cant_Item_PV, Copia_PV, Encabezado_PV, Calcular_Comision, Formato_Inventario, Cant_Ancho_PV, Grafico_PV, Formato_Activo, Num_ND, Num_NC, Referencia, Fecha_Rifa, Rifa, Monto_Minimo, 
					 Rol_2_Pagina, Cierre_Vertical, Tipo_Carga_Banco, Comision_Ejecutivo, Seguro, Nombre_Banco, Impresora_Rodillo, Costo_Bancario, Impresora_Defecto, Papel_Impresora, Marca_Agua, Seguro2, Cta_Banco, Mod_PVP, 
					 Abreviatura, Registrar_IVA, Imp_Recibo_Caja, Det_SubMod, Establecimientos, Email_Conexion, Email_Contraseña, Actualizar_Buses, Email_Contabilidad, Cierre_Individual, Email_Respaldos, Imp_Ceros, Tesorero, CIT, 
					 Razon_Social, Dec_IVA, Dec_Cant, Ambiente, Ruta_Certificado, Clave_Certificado, Web_SRI_Recepcion, Web_SRI_Autorizado, Codigo_Contribuyente_Especial, Formato_Cuentas, Email_Conexion_CE, Email_Contraseña_CE, 
					 No_ATS, Obligado_Conta, No_Autorizar, Email_Procesos, Email_CE_Copia, Estado, Firma_Digital, ID, SP, Combo, Por_CxC, Fecha_Igualar, Ret_Aut, LeyendaFA, Signo_Dec, Signo_Mil, Fecha_CE, LeyendaFAT, Centro_Costos, 
					 smtp_Servidor, smtp_Puerto, smtp_UseAuntentificacion, smtp_SSL
				FROM            Empresas
				*/
	//datos empresa
	$cid = cone_ajaxSQL();
	$sql = "SELECT * FROM  Empresas WHERE
		Item = '" . $_SESSION['INGRESO']['item'] . "' 
	";

	//echo $sql;
	$stmt = sqlsrv_query($cid, $sql);
	if ($stmt === false) {
		echo "Error en consulta PA.\n";
		die(print_r(sqlsrv_errors(), true));
	}
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
		$nome = $row[6];
		$ruce = $row[8];
		$dire = $row[12];
		$suc = $row[20];
		if ($suc == '' or $suc == null) {
			$suc = $dire;
		}
	}
	//datos cliente
	$cid = cone_ajaxSQL();
	$sql = " SELECT * FROM  Clientes
	WHERE  (CI_RUC = '" . $param[0]['ruc'] . "')
	";

	//echo $sql;
	$stmt = sqlsrv_query($cid, $sql);
	if ($stmt === false) {
		echo "Error en consulta PA.\n";
		die(print_r(sqlsrv_errors(), true));
	}
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
		$cli = $row[5];
		$direcc = $row[17];
	}
	$sql = "SELECT  *
	FROM            Asiento_F
	WHERE        (HABIT = '" . $param[0]['mesa'] . "')";
	$stmt = sqlsrv_query($cid, $sql);
	if ($stmt === false) {
		echo "Error en consulta PA.\n";
		die(print_r(sqlsrv_errors(), true));
	}
	$preciot = 0;
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
		$precio = $row[9];
		$preciot = $preciot + $precio;
	}
	$pdf->cabeceraHorizontal(array(' '), 285, 30, 280, 115, 20, 5);
	//texto 
	//ruc
	$pdf->SetFont('Arial', 'B', 13);
	$pdf->SetXY(285, 35);
	$pdf->SetWidths(array(70, 150));
	$arr = array('R.U.C.:     ', $ruce);
	$pdf->Row($arr, 10);
	//factura
	$pdf->SetXY(285, 47);
	$pdf->SetWidths(array(140));
	$arr = array('Factura No.');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 11);
	$pdf->SetXY(425, 47);
	$pdf->SetWidths(array(140));
	$arr = array('');
	/*$arr=array(etiqueta_xml($resultado,"<estab>").'-'.etiqueta_xml($resultado,"<ptoEmi>").'-'.
				etiqueta_xml($resultado,"<secuencial>"));*/
	$pdf->Row($arr, 10);
	//fecha y hora
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 61);
	$pdf->SetWidths(array(140));
	$arr = array('FECHA Y HORA DE AUTORIZACIÓN:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 61);
	$pdf->SetWidths(array(140));
	$arr = array('');
	//$arr=array($autorizacion->fechaAutorizacion);
	$pdf->Row($arr, 10);
	//emisión
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 73);
	$pdf->SetWidths(array(140));
	$arr = array('EMISIÓN:');
	$pdf->Row($arr, 13);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 73);
	$pdf->SetWidths(array(140));
	$arr = array('NORMAL:');
	$pdf->Row($arr, 10);
	//ambiente
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 85);
	$pdf->SetWidths(array(140));
	$arr = array('AMBIENTE: ');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 85);
	$pdf->SetWidths(array(140));
	if (etiqueta_xml($resultado, "<ambiente>") == 2) {
		$arr = array('PRODUCCIÓN');
	} else {
		$arr = array('PRUEBA');
	}
	$pdf->Row($arr, 10);

	//clave de acceso barcode y numero
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 97);
	$pdf->SetWidths(array(280));
	$arr = array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr, 10);
	/*$pdf->SetXY(410, 180);
				$pdf->SetWidths(array(275));
				$arr=array('000000000000000000000000000000000000000000000000');
				$pdf->Row($arr,13);*/
	//C set <claveAcceso>
	//$code=etiqueta_xml($resultado,"<claveAcceso>");
	$code = $atrib['numeroAutorizacion'];
	$code = '11';
	$pdf->SetXY(285, 109);
	$pdf->Code128(290, 109, $code, 260, 20);

	//$pdf->Write(5,'C set: "'.$code.'"');
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(285, 130);
	$pdf->SetWidths(array(275));
	$arr = array($code);
	$pdf->Row($arr, 10);
	/******************/
	/******************/
	/******************/
	$pdf->cabeceraHorizontal(array(' '), 40, 70, 242, 75, 20, 5);
	//razon social
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetXY($x, 75);
	$pdf->SetWidths(array(280));
	$arr = array($nome);
	$pdf->Row($arr, 10);


	//nombre comercial
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetXY($x, 87);
	$pdf->SetWidths(array(280));
	$arr = array($nome);
	//$arr=array( etiqueta_xml($resultado,"<nombreComercial"));
	$pdf->Row($arr, 10);
	//direccion matriz
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY($x, 97);
	$pdf->SetWidths(array(140));
	$arr = array('Dirección Matríz');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetXY($x, 107);
	$pdf->SetWidths(array(280));

	$arr = array($dire);
	$pdf->Row($arr, 10);
	//direccion sucursal
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY($x, 117);
	$pdf->SetWidths(array(140));
	$arr = array('Dirección Sucursal');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetXY($x, 127);
	$pdf->SetWidths(array(280));
	$arr = array($suc);
	$pdf->Row($arr, 10);
	//contab
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetXY($x, 135);
	$pdf->SetWidths(array(260));
	$arr = array('Obligado a llevar contabilidad:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetXY(165, 135);
	$pdf->SetWidths(array(20));
	$arr = array(etiqueta_xml($resultado, "<obligadoContabilidad>"));
	$pdf->Row($arr, 10);
	//$pdf->AddPage();
	//datos cliente
	/******************/
	/******************/
	/**************110****/
	//$pdf->cabeceraHorizontal(array(' '),20,185,574,90,20,5);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetXY(41, 149);
	//para posicion automatica
	$y = 35;
	$pdf->SetWidths(array(270, 185, 80));
	$arr = array('Razón social/nombres y apellidos:', '', 'Identificación:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 185, 80));

	$arr = array(mb_convert_encoding($cli, 'UTF-8'), '', mb_convert_encoding($param[0]['ruc'], 'UTF-8'));
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 155, 100));
	//para direccion
	//$arr1=etiqueta_xml($resultado,"<campoAdicional");
	$arr1 = $direcc;
	if (is_array($arr1)) {
		$adi = '';
		for ($i = 0; $i < count($arr1); $i++) {


			if ($i == 0) {
				$adi = $arr1[$i];
			}
			//echo $arr1[$i];
		}
	} else {
		$adi = '';
		$adi = $direcc;
	}
	$arr = array(
		'Dirección: ' . $adi,
		'Fecha emisión: ' . etiqueta_xml($resultado, "<fechaEmision>"),
		'Fecha pago: ' . etiqueta_xml($resultado, "<fechaEmision>")
	);
	$pdf->Row($arr, 10);
	$pdf->SetWidths(array(270, 155, 100));
	if (etiqueta_xml($resultado, "<moneda") == 'DOLAR') {
		$mon = 'USD';
	} else {
		$mon = 'USD';
		//se busca otras monedas
	}
	//die();
	$arr = array(
		'FORMA DE PAGO: COBRO ' . $cli,
		'MONTO: ' . $mon . '  ' . $preciot
		,
		'Condición de venta: ' . etiqueta_xml($resultado, "<fechaEmision>")
	);
	$pdf->Row($arr, 10);
	$y1 = $pdf->GetY();
	//echo $pdf->GetY().' '.$y;
	/*******************************************
	 ***********************************************
	 **************fin cabecera********************
	 ***********************************************
	 *********************************************/
	//die();
	$pdf->cabeceraHorizontal(array(' '), 40, 148, 525, ($pdf->GetY() - 148), 20, 5);
	//datos factura
	/******************/
	/******************/
	/******************/
	$pdf->SetFont('Arial', 'B', 6);
	$y = $y1 + 4;
	//$y=$y+188;//258
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(55, 55, 45, 45, 110, 45, 45, 40, 40, 45));
	$arr = array(
		"Codigo Unitario",
		"Codigo Auxiliar",
		"Cantidad Total",
		"Cantidad Bonif.",
		"Descripción",
		"Lote",
		"Precio Unitario",
		"Valor Descuento",
		"Desc. %",
		"Valor Total"
	);
	$pdf->Row($arr, 10, 1);
	$pdf->SetFont('Arial', '', 6);
	//imprimir detalles factura (hacer ciclo)
	//$pdf->SetXY(20, 313);
	//verificamos si es una o varias etiquetas
	$arr1 = etiqueta_xml($resultado, "<codigoPrincipal");
	if (is_array($arr1)) {
		$arr2 = etiqueta_xml($resultado, "<codigoAuxiliar");
		if ($arr2 == '') {
			$arr2 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr2[$i] = '';
			}
		}
		$arr3 = etiqueta_xml($resultado, "<cantidad");
		if ($arr3 == '') {
			$arr3 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr3[$i] = '';
			}
		}
		$arr4 = '';
		$arr5 = etiqueta_xml($resultado, "<descripcion");
		if ($arr5 == '') {
			$arr5 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr5[$i] = '';
			}
		}
		$arr6 = '';
		$arr7 = etiqueta_xml($resultado, "<precioUnitario");
		if ($arr7 == '') {
			$arr7 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr7[$i] = '';
			}
		}
		$arr8 = etiqueta_xml($resultado, "<descuento");
		if ($arr8 == '') {
			$arr8 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr8[$i] = '';
			}
		}
		$arr9 = '';
		$arr10 = etiqueta_xml($resultado, "<precioTotalSinImpuesto");
		if ($arr10 == '') {
			$arr10 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr10[$i] = '';
			}
		}
		for ($i = 0; $i < count($arr1); $i++) {
			$adi = '';
			$pdf->SetWidths(array(55, 55, 45, 45, 110, 45, 45, 40, 40, 45));
			$pdf->SetAligns(array("L", "L", "R", "R", "L", "L", "R", "R", "R", "R"));
			//$arr=array($arr1[$i]);
			$arr = array(
				$arr1[$i],
				$arr2[$i],
				$arr3[$i],
				$arr4,
				$arr5[$i],
				$arr6,
				$arr7[$i],
				$arr8[$i],
				$arr9,
				$arr10[$i]
			);
			$pdf->Row($arr, 10, 1);
		}
	} else {
		$pdf->SetWidths(array(55, 55, 45, 45, 110, 45, 45, 40, 40, 45));
		$pdf->SetAligns(array("L", "L", "R", "R", "L", "L", "R", "R", "R", "R"));
		$arr = array(
			etiqueta_xml($resultado, "<codigoPrincipal>"),
			etiqueta_xml($resultado, "<codigoAuxiliar>"),
			etiqueta_xml($resultado, "<cantidad>"),
			'',
			etiqueta_xml($resultado, "<descripcion>"),
			'',
			etiqueta_xml($resultado, "<precioUnitario>"),
			etiqueta_xml($resultado, "<descuento>"),
			'',
			etiqueta_xml($resultado, "<precioTotalSinImpuesto>")
		);
		$pdf->Row($arr, 10, 1);
	}
	//informacion adicional
	$pdf->SetFont('Arial', 'B', 6);
	//echo $pdf->GetY();
	//die();
	$y = $pdf->GetY();
	$pdf->SetXY(41, $y + 5);
	$pdf->SetWidths(array(140, 40, 95, 46));
	$arr = array("INFORMACIÓN ADICIONAL", "Fecha", "Deltalle del pago", "Monto Abono");
	$pdf->Row($arr, 10, 1);

	$y = $pdf->GetY() - 5; //377
	$pdf->SetFont('Arial', '', 7);
	//depende del valor de coordenada 'y' del detalle
	//informacion adicional
	$pdf->SetXY($x, $y + 5);
	$pdf->Cell(140, 60, '', '1', 1, 'Q');
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY($x, ($y + 8));
	$pdf->SetWidths(array(40));
	//verificamos si es una o varias etiquetas
	$arr1 = etiqueta_xml($resultado, "<campoAdicional");
	if (is_array($arr1)) {
		for ($i = 0; $i < count($arr1); $i++) {
			$adi = '';
			if ($i == 1) {
				$adi = 'Telefono: ';
			}
			if ($i == 2) {
				$adi = 'Email: ';
			}
			if ($i != 0) {
				$pdf->SetWidths(array(140));
				$arr = array($adi . $arr1[$i]);
				$pdf->Row($arr, 10);
			}
		}
	} else {
		$pdf->SetWidths(array(140));
		$pdf->Row($arr, 10);
	}
	//$arr=array(etiqueta_xml($resultado,"<campoAdicional"));
	/*die();
				$pdf->SetWidths(array(40));
				$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
				$pdf->Row($arr,13);
				$pdf->SetWidths(array(40));
				$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
				$pdf->Row($arr,13);
				$pdf->SetWidths(array(40));
				$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
				$pdf->Row($arr,13);*/
	//fecha
	$pdf->SetXY(181, $y + 5);
	$pdf->Cell(40, 60, '', '1', 1, 'Q');
	//detalle pago
	$pdf->SetXY(221, $y + 5);
	$pdf->Cell(95, 60, '', '1', 1, 'Q');
	//monto abono
	$pdf->SetXY(316, $y + 5);
	$pdf->Cell(46, 60, '', '1', 1, 'Q');

	//leyenda final
	$pdf->SetFont('Arial', '', 5);
	$pdf->SetXY($x, ($y + 65));
	$pdf->Cell(321, 46, '', '1', 1, 'Q');
	$pdf->SetXY($x, ($y + 68));
	$pdf->SetWidths(array(319));
	$arr = array('Para consultas, requerimientos o reclamos puede contactarse a nuestro Centro de Atención al Cliente Teléfono: 02-6052430, o escriba al correo
prisma_net@hotmail.es; para Transferencia o Depósitos hacer en El Banco Pichincha: Cta. Ahr. 4245946100 a Nombre de Walter Vaca Prieto/Cta. Cte
3422225804, a Nombre de PRISMANET PROFESIONAL S.A.'
	);
	$pdf->Row($arr, 8);
	//subtotales
	//depende del valor de coordenada 'y' del detalle
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, ($y - 10));
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y - 9));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	//obtenemos valor
	//$arr1=etiqueta_xml($resultado,"<totalImpuesto");
	$arr1 = porcion_xml($resultado, "<totalImpuesto>", "</totalImpuesto>");
	$imp = 0;
	$ba0 = 0;
	$bai = 0;
	$vimp0 = 0;
	$vimp1 = 0;
	if (is_array($arr1)) {
		for ($i = 0; $i < count($arr1); $i++) {
			//echo $arr1[$i].'<br>';
			$arr2 = etiqueta_xml($arr1[$i], "<tarifa");
			if (is_array($arr2)) {
				echo 'array';
				/*for ($j=0;$j<count($arr2);$j++)
																{
																	echo $arr2[$j].' ggg <br>';
																}*/
			} else {
				//echo $arr2.' fff <br>';
				if ($i == 1) {
					$imp = $arr2;
				}
			}
			$arr3 = etiqueta_xml($arr1[$i], "<baseImponible");
			if (is_array($arr3)) {
				/*for ($j=0;$j<count($arr2);$j++)
																{
																	echo $arr2[$j].' ggg <br>';
																}*/
			} else {
				//echo $arr3.' fff <br>';
				if ($i == 0) {
					$ba0 = $arr3;
				}
				if ($i == 1) {
					$bai = $arr3;
				}
			}
			$arr4 = etiqueta_xml($arr1[$i], "<valor");
			if (is_array($arr3)) {
				/*for ($j=0;$j<count($arr2);$j++)
																{
																	echo $arr2[$j].' ggg <br>';
																}*/
			} else {
				//echo $arr4.' fff <br>';
				if ($i == 0) {
					$vimp0 = $arr4;
				}
				if ($i == 1) {
					$vimp1 = $arr4;
				}
			}
		}
	}
	//die();
	$arr = array("SUBTOTAL " . $imp . "%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y - 9));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));

	$arr = array($bai);
	$pdf->Row($arr, 10);

	$y = $y - 10 + 11; //365
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL 0%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array($ba0);
	$pdf->Row($arr, 10);

	$y = $y + 11; //380
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("TOTAL DESCUENTO:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array(etiqueta_xml($resultado, "<totalDescuento>"));
	$pdf->Row($arr, 10);

	$y = $y + 11; //395
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL NO OBJETO DE IVA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array("000.00");
	$pdf->Row($arr, 10);

	$y = $y + 11; //410
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL EXENTO DE IVA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array("000.00");
	$pdf->Row($arr, 10);

	$y = $y + 11; //425
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL SIN IMPUESTOS:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array($ba0);
	$pdf->Row($arr, 10);

	$y = $y + 11; //440
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("ICE:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array("000.00");
	$pdf->Row($arr, 10);

	$y = $y + 11; //455
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("IVA " . $imp . "%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array($vimp1);
	$pdf->Row($arr, 10);

	$y = $y + 11; //470
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("IVA 0%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array($vimp0);
	$pdf->Row($arr, 10);

	$y = $y + 11; //485
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("PROPINA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array(etiqueta_xml($resultado, "<propina>"));
	$pdf->Row($arr, 10);

	$y = $y + 11; //500
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("VALOR TOTAL:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array(etiqueta_xml($resultado, "<importeTotal>"));
	$pdf->Row($arr, 10);
	//echo ' ddd '.$imp1;
	//die();
	if ($imp1 == null or $imp1 == 1) {
		$pdf->Output();
	}
	if ($imp1 == 0) {
		$pdf->Output('TEMP/' . $id . '.pdf', 'F');
	}
}
//imprimir doc electronico
/* $stmt= variable con datos xml $id id del campo $formato formato del reporte aqui es pdf
   $nombre_archivo ruta y nombre del archivo xml generado $va 0 para saber si lee de una variable o 1 un archivo xml
   $imp1 paqra saber si se descarga o no null o 1 descarga 0 es para temp y adjuntar al correo*/
function imprimirDocEl($stmt, $id = null, $formato = null, $nombre_archivo = null, $va = null, $imp1 = null)
{
	$pdf = new PDF('P', 'pt', 'LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();


	/*$pdf->SetFillColor(80, 150, 200);
				$pdf->Rect(20, 50, 95, 20, 'F');
				$pdf->Line(20, 50, 10, 40);
				$pdf->SetXY(20, 50);
				$pdf->Cell(15, 6, '10, 10', 0 , 1); //Celda


				$pdf->SetFillColor(80, 150, 200);
				$pdf->Rect(20, 50, 95, 20, 'F');
				$pdf->Line(20, 50, 10, 40);
				$pdf->SetXY(20, 50);
				$pdf->Cell(15, 6, '10, 10', 0 , 1); //Celda

				//Amarillo
				$pdf->SetFillColor(255, 215, 0);
				$pdf->Rect(110, 10, 45 , 20, 'F');
				$pdf->Line(110, 10, 115, 15);
				$pdf->SetXY(115, 15);
				$pdf->Cell(15, 6, '110, 10', 0 , 1);
				//Verde
				$pdf->SetFillColor(0, 128, 0);
				$pdf->Rect(160, 10, 40 , 20, 'F');
				$pdf->Line(160, 10, 165, 15);
				$pdf->SetXY(165, 15  );
				$pdf->Cell(15, 6, '160, 10', 0 , 1);
				//========================================
				 
				//========================================
				//  Segundo bloque - 1 rectángulo       ==
				//========================================
				//Salmón
				$pdf->SetFillColor(255, 99, 71);
				$pdf->Rect(10, 35, 190, 140, 'F');
				$pdf->Line(10, 35, 15, 40);
				$pdf->SetXY(15, 40);
				$pdf->Cell(15, 6, '10, 35', 0 , 1);
				//========================================
				 
				//========================================
				//  Tercer bloque - 2 rectángulos       ==
				//========================================
				//Rosa
				$pdf->SetFillColor(255, 20, 147);
				$pdf->Rect(10, 180, 90, 50, 'F');
				$pdf->Line(10, 180, 15, 185);
				$pdf->SetXY(15, 185);
				$pdf->Cell(15, 6, '10, 180', 0 , 1);
				//Café
				$pdf->SetFillColor(233, 150, 122);
				$pdf->Rect(110, 180, 90, 50, 'F');
				$pdf->Line(110, 180, 115, 185);
				$pdf->SetXY(115, 185);
				$pdf->Cell(15, 6, '110, 180', 0 , 1);
				//========================================
				 
				//========================================
				//  Cuarto bloque - 6 rectángulos       ==
				//========================================
				//Verde
				$pdf->SetFillColor(124, 252, 0);
				$pdf->Rect(10, 235, 40, 25, 'F');
				$pdf->Line(10, 235, 15, 240);
				$pdf->SetXY(15, 240);
				$pdf->Cell(15, 6, '10, 235', 0 , 1);
				//Café
				$pdf->SetFillColor(160 ,82, 40);
				$pdf->Rect(60, 235, 40, 25, 'F');
				$pdf->Line(60, 235, 65, 240);
				$pdf->SetXY(65, 240);
				$pdf->Cell(15, 6, '60, 235', 0 , 1);
				//Marrón
				$pdf->SetFillColor(128, 0 ,0);
				$pdf->Rect(10, 265, 40, 25, 'F');
				$pdf->Line(10, 265, 15, 270);
				$pdf->SetXY(15, 270);
				$pdf->Cell(15, 6, '10, 265', 0 , 1);
				//Morado
				$pdf->SetFillColor(153, 50, 204);
				$pdf->Rect(60, 265, 40, 25, 'F');
				$pdf->Line(60, 265, 65, 270);
				$pdf->SetXY(65, 270);
				$pdf->Cell(15, 6, '60, 265', 0 , 1);
				//Azul
				$pdf->SetFillColor(0, 191, 255);
				$pdf->Rect(110, 235, 90, 25, 'F');
				$pdf->Line(110, 235, 115, 240);
				$pdf->SetXY(115, 240);
				$pdf->Cell(15, 6, '110, 235', 0 , 1);
				//Verde
				$pdf->SetFillColor(173, 255, 47);
				$pdf->Rect(110, 265, 90, 25, 'F');
				$pdf->Line(110, 265, 115, 270);
				$pdf->SetXY(115, 270);
				$pdf->Cell(15, 6, '110, 265', 0 , 1);
				$pdf->AddPage();

				$miCabecera = array('Nombre de campo', 'Apellido', 'Matrícula campo');
				 
				$misDatos = array(
							array('nombre' => 'Esperbeneplatoledo', 'apellido' => 'Martínez', 'matricula' => '20420423'),
							array('nombre' => 'Araceli', 'apellido' => 'Morales', 'matricula' =>  '204909'),
							array('nombre' => 'Georginadavabulus', 'apellido' => 'Galindo', 'matricula' =>  '2043442'),
							array('nombre' => 'Luis', 'apellido' => 'Dolores', 'matricula' => '20411122'),
							array('nombre' => 'Mario', 'apellido' => 'Linares', 'matricula' => '2049990'),
							array('nombre' => 'Viridianapaliragama', 'apellido' => 'Badillo', 'matricula' => '20418855'),
							array('nombre' => 'Yadiramentoladosor', 'apellido' => 'García', 'matricula' => '20443335')
							);
							
				 $pdf->Ln(10);
				$pdf->tablaHorizontal($miCabecera, $misDatos);

				$pdf->AddPage();
				$pdf->cabeceraHorizontal(array('fgfdgfdgfdgfdgdfgffdgfdgfdgfdfdfd'));
				$pdf->Ln(50);
				$pdf->SetWidths(array(70,80,80,80,80,80,80,80,70));
				$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C'));
				$arr=array('Csssssssssssssssssssssssssssssssssssssssssssssssssss','C','C','C','C','C','C','C','C');
				$pdf->Row($arr,13);

				$pdf->AddPage();*/
	//logo
	$i = 0;
	if ($va == 1) {
		$autorizacion = simplexml_load_file($nombre_archivo);
	} else {
		$stmt = str_replace("ï»¿", "", $stmt);
		$autorizacion = simplexml_load_string($stmt);
	}
	$atrib = $autorizacion->attributes();

	//echo $autorizacion->fechaAutorizacion."<br>";
	//sustituimos
	$resultado = str_replace("<![CDATA[", "", $autorizacion->comprobante);
	$resultado = str_replace("]]>", "", $resultado);

	//echo etiqueta_xml($resultado,"<ruc>"); 
	//echo etiqueta_xml($resultado,"<razonSocial>"); 
	//echo $resultado;
	//$auto=simplexml_load_string($resultado);
	//echo ' ccc '.$auto->factura->infoTributaria;
	//die();
	$pdf->SetFont('Arial', 'B', 28);
	$x = 41;
	$pdf->SetXY($x, 20);
	//$pdf->SetWidths(array(250));
	$src = url_logo();
	if ($src !== '.') {
		$pdf->Image($src, 40, 22, 80, 40);
	} 
	/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
				{
					$logo=$_SESSION['INGRESO']['Logo_Tipo'];
				}
				else
				{
					$logo="diskcover";
				}
				$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,20,80,40,'','http://www.fpdf.org');*/
	//$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo.'.png');
	//$arr=array('NO TIENE LOGO');
	//$pdf->Row($arr,13);
	//panel
	$pdf->cabeceraHorizontal(array(' '), 285, 30, 280, 115, 20, 5);
	//texto 
	//ruc
	$pdf->SetFont('Arial', 'B', 13);
	$pdf->SetXY(285, 35);
	$pdf->SetWidths(array(70, 150));
	$arr = array('R.U.C.     ', etiqueta_xml($resultado, "<ruc>"));
	$pdf->Row($arr, 10);
	//factura
	$pdf->SetXY(285, 47);
	$pdf->SetWidths(array(140));
	$arr = array('Factura No.');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 11);
	$pdf->SetXY(425, 47);
	$pdf->SetWidths(array(140));
	$arr = array(
		etiqueta_xml($resultado, "<estab>") . '-' . etiqueta_xml($resultado, "<ptoEmi>") . '-' .
		etiqueta_xml($resultado, "<secuencial>")
	);
	$pdf->Row($arr, 10);
	//fecha y hora
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 61);
	$pdf->SetWidths(array(140));
	$arr = array('FECHA Y HORA DE AUTORIZACIÓN:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 61);
	$pdf->SetWidths(array(140));
	$arr = array($autorizacion->fechaAutorizacion);
	$pdf->Row($arr, 10);
	//emisión
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 73);
	$pdf->SetWidths(array(140));
	$arr = array('EMISIÓN:');
	$pdf->Row($arr, 13);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 73);
	$pdf->SetWidths(array(140));
	$arr = array('NORMAL:');
	$pdf->Row($arr, 10);
	//ambiente
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 85);
	$pdf->SetWidths(array(140));
	$arr = array('AMBIENTE: ');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 85);
	$pdf->SetWidths(array(140));
	if (etiqueta_xml($resultado, "<ambiente>") == 2) {
		$arr = array('PRODUCCIÓN');
	} else {
		$arr = array('PRUEBA');
	}
	$pdf->Row($arr, 10);

	//clave de acceso barcode y numero
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 97);
	$pdf->SetWidths(array(280));
	$arr = array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr, 10);
	/*$pdf->SetXY(410, 180);
				$pdf->SetWidths(array(275));
				$arr=array('000000000000000000000000000000000000000000000000');
				$pdf->Row($arr,13);*/
	//C set <claveAcceso>
	//$code=etiqueta_xml($resultado,"<claveAcceso>");
	$code = $atrib['numeroAutorizacion'];
	$pdf->SetXY(285, 109);
	$pdf->Code128(290, 109, $code, 260, 20);

	//$pdf->Write(5,'C set: "'.$code.'"');
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(285, 130);
	$pdf->SetWidths(array(275));
	$arr = array($code);
	$pdf->Row($arr, 10);
	/******************/
	/******************/
	/******************/
	$pdf->cabeceraHorizontal(array(' '), 40, 70, 242, 75, 20, 5);
	//razon social
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetXY($x, 75);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<razonSocial>"));
	$pdf->Row($arr, 10);
	//nombre comercial
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetXY($x, 87);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<nombreComercial"));
	$pdf->Row($arr, 10);
	//direccion matriz
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY($x, 97);
	$pdf->SetWidths(array(140));
	$arr = array('Dirección Matríz');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetXY($x, 107);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<dirMatriz>"));
	$pdf->Row($arr, 10);
	//direccion sucursal
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY($x, 117);
	$pdf->SetWidths(array(140));
	$arr = array('Dirección Sucursal');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetXY($x, 127);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<dirEstablecimiento>"));
	$pdf->Row($arr, 10);
	//contab
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetXY($x, 135);
	$pdf->SetWidths(array(260));
	$arr = array('Obligado a llevar contabilidad:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetXY(165, 135);
	$pdf->SetWidths(array(20));
	$arr = array(etiqueta_xml($resultado, "<obligadoContabilidad>"));
	$pdf->Row($arr, 10);
	//$pdf->AddPage();
	//datos cliente
	/******************/
	/******************/
	/**************110****/
	//$pdf->cabeceraHorizontal(array(' '),20,185,574,90,20,5);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetXY(41, 149);
	//para posicion automatica
	$y = 35;
	$pdf->SetWidths(array(270, 185, 80));
	$arr = array('Razón social/nombres y apellidos:', '', 'Identificación:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 185, 80));
	$arr = array(etiqueta_xml($resultado, "<razonSocialComprador>"), '', etiqueta_xml($resultado, "<identificacionComprador>"));
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 155, 100));
	//para direccion
	$arr1 = etiqueta_xml($resultado, "<campoAdicional");
	if (is_array($arr1)) {
		$adi = '';
		for ($i = 0; $i < count($arr1); $i++) {


			if ($i == 0) {
				$adi = $arr1[$i];
			}
			//echo $arr1[$i];
		}
	} else {
		$adi = '';
	}
	$arr = array(
		'Dirección: ' . $adi,
		'Fecha emisión: ' . etiqueta_xml($resultado, "<fechaEmision>"),
		'Fecha pago: ' . etiqueta_xml($resultado, "<fechaEmision>")
	);
	$pdf->Row($arr, 10);
	$pdf->SetWidths(array(270, 155, 100));
	if (etiqueta_xml($resultado, "<moneda") == 'DOLAR') {
		$mon = 'USD';
	} else {
		$mon = 'USD';
		//se busca otras monedas
	}
	//die();
	$arr = array(
		'FORMA DE PAGO: ' . etiqueta_xml($resultado, "<razonSocialComprador>"),
		'MONTO: ' . $mon . '  ' . etiqueta_xml($resultado, "<importeTotal>")
		,
		'Condición de venta: ' . etiqueta_xml($resultado, "<fechaEmision>")
	);
	$pdf->Row($arr, 10);
	$y1 = $pdf->GetY();
	//echo $pdf->GetY().' '.$y;
	/*******************************************
	 ***********************************************
	 **************fin cabecera********************
	 ***********************************************
	 *********************************************/
	//die();
	$pdf->cabeceraHorizontal(array(' '), 40, 148, 525, ($pdf->GetY() - 148), 20, 5);
	//datos factura
	/******************/
	/******************/
	/******************/
	$pdf->SetFont('Arial', 'B', 6);
	$y = $y1 + 4;
	//$y=$y+188;//258
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(55, 55, 45, 45, 110, 45, 45, 40, 40, 45));
	$arr = array(
		"Codigo Unitario",
		"Codigo Auxiliar",
		"Cantidad Total",
		"Cantidad Bonif.",
		"Descripción",
		"Lote",
		"Precio Unitario",
		"Valor Descuento",
		"Desc. %",
		"Valor Total"
	);
	$pdf->Row($arr, 10, 1);
	$pdf->SetFont('Arial', '', 6);
	//imprimir detalles factura (hacer ciclo)
	//$pdf->SetXY(20, 313);
	//verificamos si es una o varias etiquetas
	$arr1 = etiqueta_xml($resultado, "<codigoPrincipal");
	if (is_array($arr1)) {
		$arr2 = etiqueta_xml($resultado, "<codigoAuxiliar");
		if ($arr2 == '') {
			$arr2 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr2[$i] = '';
			}
		}
		$arr3 = etiqueta_xml($resultado, "<cantidad");
		if ($arr3 == '') {
			$arr3 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr3[$i] = '';
			}
		}
		$arr4 = '';
		$arr5 = etiqueta_xml($resultado, "<descripcion");
		if ($arr5 == '') {
			$arr5 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr5[$i] = '';
			}
		}
		$arr6 = '';
		$arr7 = etiqueta_xml($resultado, "<precioUnitario");
		if ($arr7 == '') {
			$arr7 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr7[$i] = '';
			}
		}
		$arr8 = etiqueta_xml($resultado, "<descuento");
		if ($arr8 == '') {
			$arr8 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr8[$i] = '';
			}
		}
		$arr9 = '';
		$arr10 = etiqueta_xml($resultado, "<precioTotalSinImpuesto");
		if ($arr10 == '') {
			$arr10 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr10[$i] = '';
			}
		}
		for ($i = 0; $i < count($arr1); $i++) {
			$adi = '';
			$pdf->SetWidths(array(55, 55, 45, 45, 110, 45, 45, 40, 40, 45));
			$pdf->SetAligns(array("L", "L", "R", "R", "L", "L", "R", "R", "R", "R"));
			//$arr=array($arr1[$i]);
			$arr = array(
				$arr1[$i],
				$arr2[$i],
				$arr3[$i],
				$arr4,
				$arr5[$i],
				$arr6,
				$arr7[$i],
				$arr8[$i],
				$arr9,
				$arr10[$i]
			);
			$pdf->Row($arr, 10, 1);
		}
	} else {
		$pdf->SetWidths(array(55, 55, 45, 45, 110, 45, 45, 40, 40, 45));
		$pdf->SetAligns(array("L", "L", "R", "R", "L", "L", "R", "R", "R", "R"));
		$arr = array(
			etiqueta_xml($resultado, "<codigoPrincipal>"),
			etiqueta_xml($resultado, "<codigoAuxiliar>"),
			etiqueta_xml($resultado, "<cantidad>"),
			'',
			etiqueta_xml($resultado, "<descripcion>"),
			'',
			etiqueta_xml($resultado, "<precioUnitario>"),
			etiqueta_xml($resultado, "<descuento>"),
			'',
			etiqueta_xml($resultado, "<precioTotalSinImpuesto>")
		);
		$pdf->Row($arr, 10, 1);
	}
	//informacion adicional
	$pdf->SetFont('Arial', 'B', 6);
	//echo $pdf->GetY();
	//die();
	$y = $pdf->GetY();
	$pdf->SetXY(41, $y + 5);
	$pdf->SetWidths(array(140, 40, 95, 46));
	$arr = array("INFORMACIÓN ADICIONAL", "Fecha", "Deltalle del pago", "Monto Abono");
	$pdf->Row($arr, 10, 1);

	$y = $pdf->GetY() - 5; //377
	$pdf->SetFont('Arial', '', 7);
	//depende del valor de coordenada 'y' del detalle
	//informacion adicional
	$pdf->SetXY($x, $y + 5);
	$pdf->Cell(140, 60, '', '1', 1, 'Q');
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY($x, ($y + 8));
	$pdf->SetWidths(array(40));
	//verificamos si es una o varias etiquetas
	$arr1 = etiqueta_xml($resultado, "<campoAdicional");
	if (is_array($arr1)) {
		for ($i = 0; $i < count($arr1); $i++) {
			$adi = '';
			if ($i == 1) {
				$adi = 'Telefono: ';
			}
			if ($i == 2) {
				$adi = 'Email: ';
			}
			if ($i != 0) {
				$pdf->SetWidths(array(140));
				$arr = array($adi . $arr1[$i]);
				$pdf->Row($arr, 10);
			}
		}
	} else {
		$pdf->SetWidths(array(140));
		$pdf->Row($arr, 10);
	}
	//$arr=array(etiqueta_xml($resultado,"<campoAdicional"));
	/*die();
				$pdf->SetWidths(array(40));
				$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
				$pdf->Row($arr,13);
				$pdf->SetWidths(array(40));
				$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
				$pdf->Row($arr,13);
				$pdf->SetWidths(array(40));
				$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
				$pdf->Row($arr,13);*/
	//fecha
	$pdf->SetXY(181, $y + 5);
	$pdf->Cell(40, 60, '', '1', 1, 'Q');
	//detalle pago
	$pdf->SetXY(221, $y + 5);
	$pdf->Cell(95, 60, '', '1', 1, 'Q');
	//monto abono
	$pdf->SetXY(316, $y + 5);
	$pdf->Cell(46, 60, '', '1', 1, 'Q');

	//leyenda final
	$pdf->SetFont('Arial', '', 5);
	$pdf->SetXY($x, ($y + 65));
	$pdf->Cell(321, 46, '', '1', 1, 'Q');
	$pdf->SetXY($x, ($y + 68));
	$pdf->SetWidths(array(319));
	$arr = array('Para consultas, requerimientos o reclamos puede contactarse a nuestro Centro de Atención al Cliente Teléfono: 02-6052430, o escriba al correo
prisma_net@hotmail.es; para Transferencia o Depósitos hacer en El Banco Pichincha: Cta. Ahr. 4245946100 a Nombre de Walter Vaca Prieto/Cta. Cte
3422225804, a Nombre de PRISMANET PROFESIONAL S.A.'
	);
	$pdf->Row($arr, 8);
	//subtotales
	//depende del valor de coordenada 'y' del detalle
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, ($y - 10));
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y - 9));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	//obtenemos valor
	//$arr1=etiqueta_xml($resultado,"<totalImpuesto");
	$arr1 = porcion_xml($resultado, "<totalImpuesto>", "</totalImpuesto>");
	$imp = 0;
	$ba0 = 0;
	$bai = 0;
	$vimp0 = 0;
	$vimp1 = 0;
	if (is_array($arr1)) {
		for ($i = 0; $i < count($arr1); $i++) {
			//echo $arr1[$i].'<br>';
			$arr2 = etiqueta_xml($arr1[$i], "<tarifa");
			if (is_array($arr2)) {
				echo 'array';
				/*for ($j=0;$j<count($arr2);$j++)
																{
																	echo $arr2[$j].' ggg <br>';
																}*/
			} else {
				//echo $arr2.' fff <br>';
				if ($i == 1) {
					$imp = $arr2;
				}
			}
			$arr3 = etiqueta_xml($arr1[$i], "<baseImponible");
			if (is_array($arr3)) {
				/*for ($j=0;$j<count($arr2);$j++)
																{
																	echo $arr2[$j].' ggg <br>';
																}*/
			} else {
				//echo $arr3.' fff <br>';
				if ($i == 0) {
					$ba0 = $arr3;
				}
				if ($i == 1) {
					$bai = $arr3;
				}
			}
			$arr4 = etiqueta_xml($arr1[$i], "<valor");
			if (is_array($arr3)) {
				/*for ($j=0;$j<count($arr2);$j++)
																{
																	echo $arr2[$j].' ggg <br>';
																}*/
			} else {
				//echo $arr4.' fff <br>';
				if ($i == 0) {
					$vimp0 = $arr4;
				}
				if ($i == 1) {
					$vimp1 = $arr4;
				}
			}
		}
	}
	//die();
	$arr = array("SUBTOTAL " . $imp . "%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y - 9));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));

	$arr = array($bai);
	$pdf->Row($arr, 10);

	$y = $y - 10 + 11; //365
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL 0%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array($ba0);
	$pdf->Row($arr, 10);

	$y = $y + 11; //380
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("TOTAL DESCUENTO:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array(etiqueta_xml($resultado, "<totalDescuento>"));
	$pdf->Row($arr, 10);

	$y = $y + 11; //395
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL NO OBJETO DE IVA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array("000.00");
	$pdf->Row($arr, 10);

	$y = $y + 11; //410
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL EXENTO DE IVA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array("000.00");
	$pdf->Row($arr, 10);

	$y = $y + 11; //425
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL SIN IMPUESTOS:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array($ba0);
	$pdf->Row($arr, 10);

	$y = $y + 11; //440
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("ICE:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array("000.00");
	$pdf->Row($arr, 10);

	$y = $y + 11; //455
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("IVA " . $imp . "%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array($vimp1);
	$pdf->Row($arr, 10);

	$y = $y + 11; //470
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("IVA 0%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array($vimp0);
	$pdf->Row($arr, 10);

	$y = $y + 11; //485
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("PROPINA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array(etiqueta_xml($resultado, "<propina>"));
	$pdf->Row($arr, 10);

	$y = $y + 11; //500
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("VALOR TOTAL:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array(etiqueta_xml($resultado, "<importeTotal>"));
	$pdf->Row($arr, 10);
	//echo ' ddd '.$imp1;
	//die();
	if ($imp1 == null or $imp1 == 1) {
		$pdf->Output();
	}
	if ($imp1 == 0) {
		$pdf->Output('TEMP/' . $id . '.pdf', 'F');
	}
}

function imprimirDocEle_guia($datos, $detalle, $educativo, $matri = false, $nombre = "", $formato = null, $nombre_archivo = null, $va = null, $imp1 = false, $sucursal = array())
{

	// print_r($datos);
	// print_r($detalle);die();
	$pdf = new PDF('P', 'pt', 'LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();
	$i = 0;
	$agente = '';
	$rimpe = '';
	if (isset($datos['Tipo_contribuyente']) && count($datos['Tipo_contribuyente']) > 0) {
		// print_r($datos['Tipo_contribuyente']);die();
		$agente = $datos['Tipo_contribuyente']['@Agente'];
		if ($datos['Tipo_contribuyente']['@micro'] != '.') {
			$rimpe = $datos['Tipo_contribuyente']['@micro'];
		}
	}



	$x = 41;
	$pdf->SetXY($x, 20);
	//$pdf->SetWidths(array(250));

	$src = url_logo();
	if ($src !== '.') {
		$pdf->Image($src, 40, 22, 80, 40);
	} 

	$pdf->Ln(60);
	$x = $pdf->GetX();
	$y = $pdf->GetY();
	$salto_ln = 5;
	$radio = 5;

	//=======================================cuadro izquierda inicial=================
	$margen_med = 240;
	$margen = 5;
	if ($_SESSION['INGRESO']['Nombre_Comercial'] == $_SESSION['INGRESO']['Razon_Social']) {
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->SetWidths(array($margen_med));
		$arr = array($_SESSION['INGRESO']['Razon_Social']); //mio
		$pdf->Row($arr, 10);
		$pdf->ln(10);
		;

	} else {
		//razon social
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->SetWidths(array($margen_med));
		$arr = array($_SESSION['INGRESO']['Razon_Social']); //mio
		$pdf->Row($arr, 8);
		$pdf->ln(8);
		// $pdf->Ln($salto_ln);		
		//nombre comercial
		$pdf->SetWidths(array($margen_med));
		$arr = array($_SESSION['INGRESO']['Nombre_Comercial']); //mio
		$pdf->Row($arr, 8);
		$pdf->ln(8);
	}

	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetWidths(array($margen_med));
	$arr = array('Dirección Matríz');
	$pdf->Row($arr, 10);

	$pdf->ln(10);
	;
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetWidths(array($margen_med));
	$arr = array($_SESSION['INGRESO']['Direccion']);
	$pdf->Row($arr, 10);
	$pdf->ln(10);
	;

	// print_r($datos[0]['Serie']);die();
	//sucursal si es diferente a 001
	$punto = substr($datos[0]['Serie'], 3, 6);
	$suc = substr($datos[0]['Serie'], 0, 3);

	// print_r($punto);die();
	if (isset($datos[0]['Serie'])) {
		if ($suc != '001' && count($sucursal) > 0 && $sucursal[0]['Nombre_Establecimiento'] != '.' && $sucursal[0]['Nombre_Establecimiento'] != '') {
			$pdf->SetWidths(array($margen_med));
			$arr = array('Direccion de establecimiento / Sucursal'); //mio
			$pdf->Row($arr, 10);
			$pdf->ln(10);
			;
			$arr = array($sucursal[0]['Direccion_Establecimiento']); //mio
			$pdf->Row($arr, 10);
			$pdf->ln(10);
			;
		}
	}

	if (strlen($_SESSION['INGRESO']['Telefono1']) > 1) {
		$pdf->SetWidths(array($margen_med));
		$arr = array($_SESSION['INGRESO']['Telefono1']); //mio
		$pdf->Row($arr, 10);
		$pdf->ln(10);
		;
	}
	if (strlen($_SESSION['INGRESO']['Email']) > 1) {
		$pdf->SetWidths(array($margen_med));
		$arr = array($_SESSION['INGRESO']['Email']); //mio
		$pdf->Row($arr, 10);
		$pdf->ln(10);
		;
	}

	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetWidths(array(200, 40));
	$conta = 'NO';
	if ($_SESSION['INGRESO']['Obligado_Conta'] != 'NO') {
		$conta = 'SI'; //mio
	}
	$arr = array('Obligado a llevar contabilidad:', $conta);
	$pdf->Row($arr, 10);
	$yfin = $pdf->GetY();
	$xfin = $pdf->GetX();
	$pdf->RoundedRect($x - $margen, $y - $margen, $margen_med, $yfin - $y + $margen, $radio, $style = '', $angle = '1234');
	$pdf->ln(2);
	// print_r($yfin);die();
	$y_alineado = $yfin;
	//==================================fin cuadro izquierda inicial==================

	//==================================cuadro derecha inicial invisible========================

	$medida_1 = $pdf->GETY();

	$pdf->SetTextColor(255, 255, 255);
	$x = 200 + 100;
	$pdf->SetY($yfin + 20);
	$y = $pdf->GetY();
	$margen_med2 = 280;
	$col_1 = 142;
	$pdf->SetXY($x, $pdf->GetY());
	$col_2 = $margen_med2 - $col_1;

	$misma_ln = $pdf->GetY();
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetWidths(array($col_1));
	$arr = array('R.U.C. ' . $_SESSION['INGRESO']['RUC']);
	$pdf->Row($arr, 10);

	if ($rimpe == '') {
		$pdf->SetXY($x, $misma_ln);
		// $pdf->SetTextColor(225,51,51);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetWidths(array($col_1, $col_2));
		$pdf->SetFont('Arial', '', 7);
		$arr = array('', $rimpe); //mio
		$pdf->Row($arr, 10);
	}
	// /-----------------------------------------------------------------------------
	if ($agente != '' && $agente != '.') {
		$pdf->SetX($x);
		// $pdf->SetTextColor(225,51,51);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array($col_1 + $col_2));
		$arr = array('Agente de Retención Resolución: ' . $agente);
		$pdf->Row($arr, 10);
	}
	// ----------------------------------------------------------------------------------
	$pdf->SetX($x);
	$pdf->SetTextColor(255, 255, 255);
	// $pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial', '', 12);
	$pdf->SetWidths(array($col_1));
	// print_r($datos);die();

	$misma_ln = $pdf->GetY();
	if (isset($datos[0]['TC']) && $datos[0]['TC'] == 'LC') {
		$arr = array('Liquidacion compra No.');
	} else {
		$arr = array('Factura No.');
	}
	$pdf->Row($arr, 10);

	$pdf->SetXY($x, $misma_ln);
	$pdf->SetFont('Arial', '', 11);
	// $pdf->SetTextColor(225,51,51);
	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetWidths(array($col_1, $col_2));
	$ptoEmi = substr($datos[0]['Serie'], 3, 6);
	$Serie = substr($datos[0]['Serie'], 0, 3);
	$arr = array('', $Serie . '-' . $ptoEmi . '-' . generaCeros($datos[0]['Factura'], 9)); //mio
	$pdf->Row($arr, 10);
	$pdf->SetTextColor(0);
	// print_r($datos);
	// fecha y hora


	$pdf->SetX($x);
	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('FECHA Y HORA DE AUTORIZACIÓN:', $datos[0]['Fecha_Aut']->format('Y-m-d  h:m:s'));
	$pdf->Row($arr, 10);


	//emisión
	$pdf->SetX($x);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('EMISIÓN:', 'NORMAL');
	$pdf->Row($arr, 13);


	//ambiente
	$ambiente = substr($datos[0]['Autorizacion'], 23, 1);
	// print_r($datos[0]['Autorizacion']);die();
	//print_r($ambiente);die();
	$am = '';
	if ($ambiente == 2) {
		$am = 'PRODUCCION';

	} else if ($ambiente == 1) {
		$am = 'PRUEBA';
	}
	$pdf->SetX($x);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('AMBIENTE: ', $am);
	$pdf->Row($arr, 10);


	//clave de acceso barcode y numero
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetX($x);
	$pdf->SetWidths(array($margen_med));
	$arr = array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr, 10);
	if ($datos[0]['Clave_Acceso'] != $datos[0]['Autorizacion']) {
		$code = $datos[0]['Clave_Acceso'];
		// $pdf->Code128($x,$pdf->GetY(),$code,$margen_med2-10,20);
		$pdf->ln(10);
		$pdf->SetY($pdf->GetY() + 20);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array(275));
		$pdf->SetX($x);
		// $arr=array($code);
		// $pdf->Row($arr,10);	
	} else if ($datos[0]['Clave_Acceso'] > 39) {
		$code = $datos[0]['Clave_Acceso'];
		// $pdf->Code128($x,$pdf->GetY(),$code,$margen_med2-10,20);
		$pdf->ln(10);
		$pdf->SetY($pdf->GetY() + 20);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array(275));
		$pdf->SetX($x);
		$arr = array($code);
		// $pdf->Row($arr,10);	
	}
	$pdf->ln(10);
	$yfin = $pdf->GetY();
	// $pdf->RoundedRect($x-$margen, $y-$margen, $margen_med2, $yfin-$y+$margen, 10, $style = '', $angle = '1234');		

	$medida_fin = $yfin;

	//==========================================
// 	if($medida_fin>$medida_1)
// 	{
	$medida_burbu = $medida_fin - $medida_1;
	$pos_burbuja_derecha_y = $y_alineado - $medida_burbu + $margen * 2;
	//   }else{

	//   	// print_r($pdf->PageNo());die();
//     $medida_burbu = $medida_1-$medida_fin;    
//     $pos_burbuja_derecha_y = $y_alineado+100+$margen*2;
// }

	// print_r($y_alineado*-1);die();
// print_r($pos_burbuja_derecha_y*-1);die();
	$x = 200 + 100;
	$pdf->SetAligns('L');
	$pdf->SetY($pos_burbuja_derecha_y);
	$y = $pdf->GetY();
	$margen_med2 = 280;
	$col_1 = 142;
	$pdf->SetXY($x, $pdf->GetY());
	$col_2 = $margen_med2 - $col_1;
	$pdf->SetTextColor(0, 0, 0);
	$misma_ln = $pdf->GetY();
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetWidths(array($col_1));
	$arr = array('R.U.C. ' . $_SESSION['INGRESO']['RUC']);
	$pdf->Row($arr, 10);

	if ($rimpe != '') {
		$pdf->SetXY($x, $misma_ln);
		$pdf->SetTextColor(225, 51, 51);
		$pdf->SetWidths(array($col_1, $col_2));
		$pdf->SetFont('Arial', '', 7);
		$arr = array('', $rimpe); //mio
		$pdf->Row($arr, 10);
	}
	// /-----------------------------------------------------------------------------
	if ($agente != '' && $agente != '.') {
		$pdf->SetX($x);
		$pdf->SetTextColor(225, 51, 51);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array($col_1 + $col_2));
		$arr = array('Agente de Retención Resolución: ' . $agente);
		$pdf->Row($arr, 10);
	}
	// ----------------------------------------------------------------------------------
	$pdf->SetX($x);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 12);
	$pdf->SetWidths(array($col_1));
	// print_r($datos);die();

	$misma_ln = $pdf->GetY();
	$arr = array('GUIA DE REMISION No.');
	$pdf->Row($arr, 10);

	$pdf->SetXY($x, $misma_ln);
	$pdf->SetFont('Arial', '', 11);
	$pdf->SetTextColor(225, 51, 51);
	$pdf->SetWidths(array($col_1, $col_2));
	$ptoEmi = substr($datos[0]['Serie_GR'], 3, 6);
	$Serie = substr($datos[0]['Serie_GR'], 0, 3);
	$arr = array('', $Serie . '-' . $ptoEmi . '-' . generaCeros($datos[0]['Remision'], 9)); //mio
	$pdf->Row($arr, 10);
	$pdf->SetTextColor(0);
	// print_r($datos);
	// fecha y hora


	$pdf->SetX($x);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('FECHA Y HORA DE AUTORIZACIÓN:', $datos[0]['Fecha_Aut_GR']->format('Y-m-d  h:m:s'));
	$pdf->Row($arr, 10);


	//emisión
	$pdf->SetX($x);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('EMISIÓN:', 'NORMAL');
	$pdf->Row($arr, 13);


	//ambiente
	$ambiente = substr($datos[0]['Autorizacion_GR'], 23, 1);
	// print_r($datos[0]['Autorizacion']);die();
	//print_r($ambiente);die();
	$am = '';
	if ($ambiente == 2) {
		$am = 'PRODUCCION';

	} else if ($ambiente == 1) {
		$am = 'PRUEBA';
	}
	$pdf->SetX($x);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('AMBIENTE: ', $am);
	$pdf->Row($arr, 10);


	//clave de acceso barcode y numero
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetX($x);
	$pdf->SetWidths(array($margen_med));
	$arr = array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr, 10);
	if ($datos[0]['Clave_Acceso'] != $datos[0]['Autorizacion_GR']) {
		$code = $datos[0]['Autorizacion_GR'];
		$pdf->ln(10);
		$pdf->Code128($x, $pdf->GetY(), $code, $margen_med2 - 10, 20);

		$pdf->SetY($pdf->GetY() + 20);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array(275));
		$pdf->SetX($x);
		$arr = array($code);
		$pdf->Row($arr, 10);

	} else if ($datos[0]['Clave_Acceso_GR'] > 39) {
		$code = $datos[0]['Clave_Acceso_GR'];
		$pdf->ln(10);
		$pdf->Code128($x, $pdf->GetY(), $code, $margen_med2 - 10, 20);

		$pdf->SetY($pdf->GetY() + 20);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array(275));
		$pdf->SetX($x);
		$arr = array($code);
		$pdf->Row($arr, 10);
	}
	$pdf->ln(10);
	$yfin = $pdf->GetY();
	$pdf->RoundedRect($x - $margen, $y - $margen, $margen_med2, $yfin - $y + $margen, 10, $style = '', $angle = '1234');

	$medida_fin = $pdf->GetY();


	//========================= fin cuadro derecha inicial========================







	//============================================cuadro cliente ==========================================
	$y = $pdf->GetY() + $margen * 2;
	$x = $pdf->GetX();
	$pdf->SetXY($x, $y);
	$margen_med3 = 540;

	// print_r($datos);die();


	$pdf->SetWidths(array(370, 90, 65));
	$arr = array('<b>Razón social/nombres y apellidos:', '', '<b>Identificación:'); //mio
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(370, 90, 65));
	$arr = array(mb_convert_encoding($datos[0]['Razon_Social'], 'ISO-8859-1', 'UTF-8'), '', $datos[0]['RUC_CI']); //mio
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 155, 100));

	$arr = array('<b>Dirección: ' . $datos[0]['Direccion_RS'], '', ''); //mio
	$pdf->Row($arr, 10);
	$pdf->SetWidths(array(270, 155, 100));
	// print_r($datos);die();	
	//die();
	$arr = array(
		'<b>Autorizacion: ' . $datos[0]['Autorizacion'],
		'<b>Factura No: ' . $datos[0]['Serie'] . '-' . generaCeros($datos[0]['Factura'], 9)
		,
		'<b>Fecha emison: ' . $datos[0]['Fecha']->format('Y-m-d')
	);
	$pdf->Row($arr, 10);

	// print_r($datos[0]);die();
	$pdf->SetWidths(array(270, 10, 270));
	$arr = array('<b>Motivo de traslado: ' . $datos[0]['Observacion'], '', '<b>Nota Auxiliar: ' . $datos[0]['Nota']);
	$pdf->Row($arr, 10);

	// if($datos[0]['Nota']!='.' && $datos[0]['Nota']!='')
	// {
	// $pdf->SetWidths(array(525));
	// $arr=array('Nota: '.$datos[0]['Nota']);
	// $pdf->Row($arr,10);
	// }

	$yfin = $pdf->GetY();
	$xfin = $pdf->GetX();
	$pdf->RoundedRect($x - $margen, $y - $margen, $margen_med3, $yfin - $y + $margen, $radio, $style = '', $angle = '1234');



	//================================fin cuadro cliente========================================================= 




	//============================================cuadro transpor ==========================================
	$y = $pdf->GetY() + $margen * 2;
	$x = $pdf->GetX();
	$pdf->SetXY($x, $y);
	$margen_med3 = 540;


	$pdf->SetWidths(array(370, 90, 65));
	$arr = array('<b>Razón social/nombres y apellidos:(Transportista)', '', '<b>Identificación:'); //mio
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(370, 90, 65));
	$arr = array(mb_convert_encoding($datos[0]['Comercial'], 'ISO-8859-1', 'UTF-8'), '', $datos[0]['CIRUC_Comercial']);
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 100, 100, 55));

	$arr = array('<b>Punto de Partida: ', '<b>Fecha Inicio: ', '<b>Fecha fin: ', '<b>Placa: '); //mio
	$pdf->Row($arr, 10);
	$arr = array($datos[0]['FechaGRI']->format('Y-m-d') . '        ' . $datos[0]['CiudadGRI'], $datos[0]['FechaGRI']->format('Y-m-d'), $datos[0]['FechaGRF']->format('Y-m-d'), $datos[0]['Placa_Vehiculo']); //mio
	$pdf->Row($arr, 10);
	$yfin = $pdf->GetY();
	$xfin = $pdf->GetX();
	$pdf->RoundedRect($x - $margen, $y - $margen, $margen_med3, $yfin - $y + $margen, $radio, $style = '', $angle = '1234');



	//================================fin cuadro trasnpor========================================================= 

	//============================================cuadro llegada==========================================
	$y = $pdf->GetY() + $margen * 2;
	$x = $pdf->GetX();
	$pdf->SetXY($x, $y);
	$margen_med3 = 540;


	$pdf->SetWidths(array(370, 90, 65));
	$arr = array('Razón social/nombres y apellidos:(Punto de llegada)', '', 'Identificación:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(370, 85, 80));
	$arr = array(mb_convert_encoding($datos[0]['Entrega'], 'ISO-8859-1', 'UTF-8'), '', $datos[0]['CIRUC_Entrega']); //mio
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(525));

	// print_r($datos[0]);die();
	$arr = array('Destino: De ' . $datos[0]['CiudadGRI'] . ' a ' . $datos[0]['CiudadGRF'] . ', ' . $datos[0]['Lugar_Entrega']);
	$pdf->Row($arr, 10);
	$pdf->ln(10);
	$yfin = $pdf->GetY();
	$xfin = $pdf->GetX();
	$pdf->RoundedRect($x - $margen, $y - $margen, $margen_med3, $yfin - $y + $margen, $radio, $style = '', $angle = '1234');



	//================================fin cuadro llegada========================================================= 


	//================================cuadro detalle========================================================= 

	//datos factura
	$y = $pdf->GetY() + $margen + 2;
	$x = $pdf->GetX() - $margen;
	$pdf->SetXY($x, $y);
	$margen_med3 = 540;

	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetWidths(array(55, 55, 45, 383));
	$arr = array("Codigo Unitario", "Codigo Auxiliar", "Cantidad ", "Descripción");
	$pdf->Row($arr, 10, 1);
	$pdf->SetFont('Arial', '', 6);

	// print_r($datos);die();
	$imp_mes = $datos[0]['Imp_Mes'];
	foreach ($detalle as $key => $value) {
		// print_r($value);die();
		$pdf->SetX($x);
		if ($imp_mes != 0) {
			$value['Producto'] = $value['Producto'] . ' ' . $value['Mes'] . '/' . $value['Ticket'];
		}
		$pdf->SetWidths(array(55, 55, 45, 383));
		$pdf->SetAligns(array("L", "L", "R", "L"));
		//$arr=array($arr1[$i]);
		$arr = array($value['Codigo'], '', number_format($value['Cantidad'], 2, '.', ''), $value['Producto']);
		$pdf->Row($arr, 10, 1);
		// $pdf->ln(10);  	
	}


	$yfin = $pdf->GetY();
	$xfin = $pdf->GetX();
	// $pdf->RoundedRect($x-$margen, $y-$margen, $margen_med3, $yfin-$y+$margen, $radio, $style = '', $angle = '1234');

	//===================================================fin cuadrpo detalle======================================

	//====================================================cuadro datos adicionales======================
	$y = $pdf->GetY();
	$x = $pdf->GetX() - $margen;
	$pdf->SetXY($x, $y);
	$misma_ln = $y;

	//informacion adicional
	$pdf->SetFont('Arial', 'B', 6);
	//echo $pdf->GetY();
	//die();
	$y = $pdf->GetY();
	$pdf->SetXY(41 - $margen, $y + 5);
	$pdf->SetWidths(array(537));
	// infofactura
	$arr = array("INFORMACIÓN ADICIONAL");
	$pdf->Row($arr, 10, 1);
	$pdf->ln(10);

	$y = $pdf->GetY() - 5; //377
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetXY($x, $y + 5);
	$pdf->SetFont('Arial', '', 5.5);
	$pdf->SetXY($x, ($y + 8));
	$pdf->SetWidths(array(40));
	//verificamos si es una o varias etiquetas
	// datos adicionales
	$pdf->SetWidths(array(200, 200, 135));
	$arr = array('<b>Email: ' . $educativo[0]['Email'], '<b>Email2: ' . $educativo[0]['Email_tras'], '<b>Telefono:' . $educativo[0]['Telefono']);
	$pdf->Row($arr, 10);
	//print_r($educativo);


	if (isset($dato[0]['Telefono_RS']) && $datos[0]['Telefono_RS'] != '.' && $datos[0]['Telefono_RS'] != '') {
		$arr = array('Telefono: ' . $datos[0]['Telefono_RS']);
		$pdf->Row($arr, 10);
		$pdf->SetWidths(array(140));
	}



	///----------------------  infomrmacion adicional cuando punto de venta es diferente de 001 ----------------------
	// print_r($sucursal);die();


	if (count($sucursal) > 0 && $punto != '001' && $suc == '001' && strlen($sucursal[0]['RUC_Establecimiento']) == 13) {

		$arr = array('Punto Emision: ' . $datos[0]['Serie']);
		$pdf->Row($arr, 10);

		if ($sucursal[0]['Nombre_Establecimiento'] != '.' && $sucursal[0]['Nombre_Establecimiento'] != '') {
			$arr = array($sucursal[0]['Nombre_Establecimiento']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['RUC_Establecimiento'] != '.' && $sucursal[0]['RUC_Establecimiento'] != '') {
			$arr = array('Ruc punto Emision: ' . $sucursal[0]['RUC_Establecimiento']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['Direccion_Establecimiento'] != '.' && $sucursal[0]['Direccion_Establecimiento'] != '') {
			$arr = array('Direccion punto Emision: ' . $sucursal[0]['Direccion_Establecimiento']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['Telefono_Estab'] != '.' && $sucursal[0]['Telefono_Estab'] != '') {
			$arr = array('Telefono punto Emision: ' . $sucursal[0]['Telefono_Estab']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['Email_Establecimiento'] != '.' && $sucursal[0]['Email_Establecimiento'] != '') {
			$arr = array('Email punto Emision: ' . $sucursal[0]['Email_Establecimiento']);
			$pdf->Row($arr, 10);
		}

		if ($sucursal[0]['Cta_Establecimiento'] != '.' && $sucursal[0]['Cta_Establecimiento'] != '') {
			$arr = array('Cta Punto Emision: ' . $sucursal[0]['Cta_Establecimiento']);
			$pdf->Row($arr, 10);
		}


		if ($sucursal[0]['Placa_Vehiculo'] != '.' && $sucursal[0]['Placa_Vehiculo'] != '') {
			$arr = array('Placas: ' . $sucursal[0]['Placa_Vehiculo']);
			$pdf->Row($arr, 10);
		}


	}

	///----------------------  fin infomrmacion adicional cuando punto de venta es diferente de 001 ----------------------


	///----------------------  infomrmacion adicional cuan establecimiento es diferente de 001 ----------------------

	if (count($sucursal) > 0 && $suc != '001' && strlen($sucursal[0]['RUC_Establecimiento']) == 13) {
		$arr = array('Establecimiento: ' . $datos[0]['Serie']);
		$pdf->Row($arr, 10);

		if ($sucursal[0]['Nombre_Establecimiento'] != '.' && $sucursal[0]['Nombre_Establecimiento'] != '') {
			$arr = array($sucursal[0]['Nombre_Establecimiento']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['RUC_Establecimiento'] != '.' && $sucursal[0]['RUC_Establecimiento'] != '') {
			$arr = array('Ruc establecimiento: ' . $sucursal[0]['RUC_Establecimiento']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['Telefono_Estab'] != '.' && $sucursal[0]['Telefono_Estab'] != '') {
			$arr = array('Telefono establecimiento: ' . $sucursal[0]['Telefono_Estab']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['Email_Establecimiento'] != '.' && $sucursal[0]['Email_Establecimiento'] != '') {
			$arr = array('Email establecimiento: ' . $sucursal[0]['Email_Establecimiento']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['Cta_Establecimiento'] != '.' && $sucursal[0]['Cta_Establecimiento'] != '') {
			$arr = array('Cta_Establecimiento: ' . $sucursal[0]['Cta_Establecimiento']);
			$pdf->Row($arr, 10);
		}


		if ($sucursal[0]['Placa_Vehiculo'] != '.' && $sucursal[0]['Placa_Vehiculo'] != '') {
			$arr = array('Placas: ' . $sucursal[0]['Placa_Vehiculo']);
			$pdf->Row($arr, 10);
		}


	}

	///---------------------- fin infomrmacion adicional cuan establecimiento es diferente de 001 ----------------------
	$yfin_adiconales = $pdf->GetY();
	$pdf->SetXY($x, $y + $margen);
	$pdf->Cell(537, $yfin_adiconales - $y - $margen, '', '1', 1, 'Q');
	$y_final_leyenda = $yfin_adiconales;



	if ($imp1 == false || $imp1 == 0) {
		$pdf->Output();
	}
	if ($imp1 == 1) {
		$pdf->Output('F', dirname(__DIR__, 2) . '/TEMP/' . $datos[0]['Serie_GR'] . '-' . generaCeros($datos[0]['Remision'], 7) . '.pdf');

		// $pdf->Output('TEMP/'.$nombre.'.pdf','F'); 
	}
}




function imprimirDocEle_fac($datos, $detalle, $educativo, $matri = false, $nombre = "", $formato = null, $nombre_archivo = null, $va = null, $imp1 = false, $abonos = false, $sucursal = array())
{

	// print_r($va);
	// print_r($imp1);
	// die();

	// print_r($_SESSION['INGRESO']);die();
	// print_r($sucursal);die();
	$pdf = new PDF('P', 'pt', 'LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();
	$i = 0;
	$agente = '';
	$rimpe = '';
	if (isset($datos['Tipo_contribuyente']) && count($datos['Tipo_contribuyente']) > 0) {
		// print_r($datos['Tipo_contribuyente']);die();
		$agente = $datos['Tipo_contribuyente']['@Agente'];
		if ($datos['Tipo_contribuyente']['@micro'] != '.') {
			$rimpe = $datos['Tipo_contribuyente']['@micro'];
		}
	}



	$x = 41;
	$pdf->SetXY($x, 20);
	//$pdf->SetWidths(array(250));
	$src = url_logo();
	if ($src !== '.') {
		$pdf->Image($src, 40, 22, 80, 40);
	} 


	$pdf->Ln(60);
	$x = $pdf->GetX();
	$y = $pdf->GetY();
	$salto_ln = 5;
	$radio = 5;

	//=======================================cuadro izquierda inicial=================
	$margen_med = 240;
	$margen = 5;
	if ($_SESSION['INGRESO']['Nombre_Comercial'] == $_SESSION['INGRESO']['Razon_Social']) {
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->SetWidths(array($margen_med));
		$arr = array($_SESSION['INGRESO']['Razon_Social']); //mio
		$pdf->Row($arr, 10);
		// $pdf->ln(5);

		// print_r($pdf->GetY());die();

	} else {
		//razon social
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->SetWidths(array($margen_med));
		$arr = array($_SESSION['INGRESO']['Razon_Social']); //mio
		$pdf->Row($arr, 8);
		// $pdf->ln(8);
		// $pdf->Ln($salto_ln);		
		//nombre comercial
		$pdf->SetWidths(array($margen_med));
		$arr = array($_SESSION['INGRESO']['Nombre_Comercial']); //mio
		$pdf->Row($arr, 8);
		// $pdf->ln(8);
	}
	// print_r($pdf->GetY());die();
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetWidths(array($margen_med));
	$arr = array('Dirección Matríz');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetWidths(array($margen_med));
	$arr = array($_SESSION['INGRESO']['Direccion']);
	$pdf->Row($arr, 10);

	// print_r($datos[0]['Serie']);die();
	//sucursal si es diferente a 001
	$punto = substr($datos[0]['Serie'], 3, 6);
	$suc = substr($datos[0]['Serie'], 0, 3);

	// print_r($punto);die();
	if (isset($datos[0]['Serie'])) {
		if ($suc != '001' && count($sucursal) > 0 && $sucursal[0]['Nombre_Establecimiento'] != '.' && $sucursal[0]['Nombre_Establecimiento'] != '') {
			$pdf->SetWidths(array($margen_med));
			$arr = array('Direccion de establecimiento / Sucursal'); //mio
			$pdf->Row($arr, 10);
			$pdf->ln(10);
			$arr = array($sucursal[0]['Direccion_Establecimiento']); //mio
			$pdf->Row($arr, 10);
			$pdf->ln(10);
		}
	}

	if (strlen($_SESSION['INGRESO']['Telefono1']) > 1) {
		$pdf->SetWidths(array($margen_med));
		$arr = array(mb_convert_encoding($_SESSION['INGRESO']['Telefono1'], 'UTF-8')); //mio
		$pdf->Row($arr, 10);
	}
	if (strlen($_SESSION['INGRESO']['Email']) > 1) {
		$pdf->SetWidths(array($margen_med));
		$arr = array(mb_convert_encoding($_SESSION['INGRESO']['Email'], 'UTF-8')); //mio
		$pdf->Row($arr, 10);
	}

	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetWidths(array(200, 40));
	$conta = 'NO';
	if ($_SESSION['INGRESO']['Obligado_Conta'] != 'NO') {
		$conta = 'SI'; //mio
	}
	$arr = array('Obligado a llevar contabilidad:', $conta);
	$pdf->Row($arr, 10);
	$yfin = $pdf->GetY();
	$xfin = $pdf->GetX();
	$pdf->RoundedRect($x - $margen, $y - $margen, $margen_med, $yfin - $y + $margen, $radio, $style = '', $angle = '1234');
	$pdf->ln(2);
	// print_r($yfin);die();
	$y_alineado = $yfin;
	//==================================fin cuadro izquierda inicial==================

	//==================================cuadro derecha inicial invisible========================

	$medida_1 = $pdf->GETY();

	$pdf->SetTextColor(255, 255, 255);
	$x = 200 + 100;
	$pdf->SetY($yfin + 20);
	$y = $pdf->GetY();
	$margen_med2 = 280;
	$col_1 = 142;
	$pdf->SetXY($x, $pdf->GetY());
	$col_2 = $margen_med2 - $col_1;

	$misma_ln = $pdf->GetY();
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetWidths(array($col_1));
	$arr = array('R.U.C. ' . $_SESSION['INGRESO']['RUC']);
	$pdf->Row($arr, 10);

	if ($rimpe == '') {
		$pdf->SetXY($x, $misma_ln);
		// $pdf->SetTextColor(225,51,51);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetWidths(array($col_1, $col_2));
		$pdf->SetFont('Arial', '', 7);
		$arr = array('', $rimpe); //mio
		$pdf->Row($arr, 10);
	}
	// /-----------------------------------------------------------------------------
	if ($agente != '' && $agente != '.') {
		$pdf->SetX($x);
		// $pdf->SetTextColor(225,51,51);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array($col_1 + $col_2));
		$arr = array('Agente de Retención Resolución: ' . $agente);
		$pdf->Row($arr, 10);
	}
	// ----------------------------------------------------------------------------------
	$pdf->SetX($x);
	$pdf->SetTextColor(255, 255, 255);
	// $pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial', '', 12);
	$pdf->SetWidths(array($col_1));
	// print_r($datos);die();

	$misma_ln = $pdf->GetY();
	if (isset($datos[0]['TC']) && $datos[0]['TC'] == 'LC') {
		$arr = array('Liquidacion compra No.');
	} else {
		$arr = array('Factura No.');
	}
	$pdf->Row($arr, 10);

	$pdf->SetXY($x, $misma_ln);
	$pdf->SetFont('Arial', '', 11);
	// $pdf->SetTextColor(225,51,51);
	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetWidths(array($col_1, $col_2));
	$ptoEmi = substr($datos[0]['Serie'], 3, 6);
	$Serie = substr($datos[0]['Serie'], 0, 3);
	$arr = array('', $Serie . '-' . $ptoEmi . '-' . generaCeros($datos[0]['Factura'], 9)); //mio
	$pdf->Row($arr, 10);
	$pdf->SetTextColor(0);
	// print_r($datos);die();
	// fecha y hora


	$pdf->SetX($x);
	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('FECHA Y HORA DE AUTORIZACIÓN:', $datos[0]['Fecha_Aut']->format('Y-m-d  h:m:s'));
	$pdf->Row($arr, 10);


	//emisión
	$pdf->SetX($x);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('EMISIÓN:', 'NORMAL');
	$pdf->Row($arr, 13);


	//ambiente
	$ambiente = substr($datos[0]['Autorizacion'], 23, 1);
	// print_r($datos[0]['Autorizacion']);die();
	//print_r($ambiente);die();
	$am = '';
	if ($ambiente == 2) {
		$am = 'PRODUCCION';

	} else if ($ambiente == 1) {
		$am = 'PRUEBA';
	}
	$pdf->SetX($x);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('AMBIENTE: ', $am);
	$pdf->Row($arr, 10);


	//clave de acceso barcode y numero
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetX($x);
	$pdf->SetWidths(array($margen_med));
	$arr = array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr, 10);
	if ($datos[0]['Clave_Acceso'] != $datos[0]['Autorizacion']) {

		$pdf->ln(10);
		$code = $datos[0]['Clave_Acceso'];
		// $pdf->Code128($x,$pdf->GetY(),$code,$margen_med2-10,20);

		$pdf->SetY($pdf->GetY() + 20);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array(275));
		$pdf->SetX($x);
		// $arr=array($code);
		// $pdf->Row($arr,10);	
	} else if ($datos[0]['Clave_Acceso'] > 39) {

		$pdf->ln(10);
		$code = $datos[0]['Clave_Acceso'];
		// $pdf->Code128($x,$pdf->GetY(),$code,$margen_med2-10,20);

		$pdf->SetY($pdf->GetY() + 20);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array(275));
		$pdf->SetX($x);
		$arr = array($code);
		// $pdf->Row($arr,10);	
	}
	$pdf->ln(10);
	$yfin = $pdf->GetY();
	// $pdf->RoundedRect($x-$margen, $y-$margen, $margen_med2, $yfin-$y+$margen, 10, $style = '', $angle = '1234');		

	$medida_fin = $yfin;

	//========================================== finde cuado invisible==============================000
// 	if($medida_fin>$medida_1)
// 	{
	$medida_burbu = $medida_fin - $medida_1;
	$pos_burbuja_derecha_y = $y_alineado - $medida_burbu + $margen * 2;
	//   }else{

	//   	// print_r($pdf->PageNo());die();
//     $medida_burbu = $medida_1-$medida_fin;    
//     $pos_burbuja_derecha_y = $y_alineado+100+$margen*2;
// }

	// print_r($y_alineado*-1);die();
// print_r($pos_burbuja_derecha_y*-1);die();
	$x = 200 + 100;
	$pdf->SetAligns('L');
	$pdf->SetY($pos_burbuja_derecha_y);
	$y = $pdf->GetY();
	$margen_med2 = 280;
	$col_1 = 142;
	$pdf->SetXY($x, $pdf->GetY());
	$col_2 = $margen_med2 - $col_1;
	$pdf->SetTextColor(0, 0, 0);
	$misma_ln = $pdf->GetY();
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetWidths(array($col_1));
	$arr = array('R.U.C. ' . $_SESSION['INGRESO']['RUC']);
	$pdf->Row($arr, 10);

	if ($rimpe != '') {
		$pdf->SetXY($x, $misma_ln);
		$pdf->SetTextColor(225, 51, 51);
		$pdf->SetWidths(array($col_1, $col_2));
		$pdf->SetFont('Arial', '', 7);
		$arr = array('', $rimpe); //mio
		$pdf->Row($arr, 10);
	}
	// /-----------------------------------------------------------------------------
	if ($agente != '' && $agente != '.') {
		$pdf->SetX($x);
		$pdf->SetTextColor(225, 51, 51);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array($col_1 + $col_2));
		$arr = array('Agente de Retención Resolución: ' . $agente);
		$pdf->Row($arr, 10);
	}
	// ----------------------------------------------------------------------------------
	$pdf->SetX($x);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 12);
	$pdf->SetWidths(array($col_1));
	// print_r($datos);die();

	$misma_ln = $pdf->GetY();
	if (isset($datos[0]['TC']) && $datos[0]['TC'] == 'LC') {
		$arr = array('Liquidacion compra No.');
	} else {
		$arr = array('Factura No.');
	}
	$pdf->Row($arr, 10);

	$pdf->SetXY($x, $misma_ln);
	$pdf->SetFont('Arial', '', 11);
	$pdf->SetTextColor(225, 51, 51);
	$pdf->SetWidths(array($col_1, $col_2));
	$ptoEmi = substr($datos[0]['Serie'], 3, 6);
	$Serie = substr($datos[0]['Serie'], 0, 3);
	$arr = array('', $Serie . '-' . $ptoEmi . '-' . generaCeros($datos[0]['Factura'], 9)); //mio
	$pdf->Row($arr, 10);
	$pdf->SetTextColor(0);
	// print_r($datos);
	// fecha y hora


	$pdf->SetX($x);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('FECHA Y HORA DE AUTORIZACIÓN:', $datos[0]['Fecha_Aut']->format('Y-m-d  h:m:s'));
	$pdf->Row($arr, 10);


	//emisión
	$pdf->SetX($x);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('EMISIÓN:', 'NORMAL');
	$pdf->Row($arr, 13);


	//ambiente
	$ambiente = substr($datos[0]['Autorizacion'], 23, 1);
	// print_r($datos[0]['Autorizacion']);die();
	//print_r($ambiente);die();
	$am = '';
	if ($ambiente == 2) {
		$am = 'PRODUCCION';

	} else if ($ambiente == 1) {
		$am = 'PRUEBA';
	}
	$pdf->SetX($x);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('AMBIENTE: ', $am);
	$pdf->Row($arr, 10);


	//clave de acceso barcode y numero
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetX($x);
	$pdf->SetWidths(array($margen_med));
	$arr = array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr, 10);
	if ($datos[0]['Clave_Acceso'] != $datos[0]['Autorizacion']) {
		$pdf->ln(10);
		$code = $datos[0]['Autorizacion'];
		$pdf->Code128($x, $pdf->GetY(), $code, $margen_med2 - 10, 20);

		$pdf->SetY($pdf->GetY() + 20);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array(275));
		$pdf->SetX($x);
		$arr = array($code);
		$pdf->Row($arr, 10);

	} else if ($datos[0]['Clave_Acceso'] > 39) {

		$pdf->ln(10);
		$code = $datos[0]['Clave_Acceso'];
		$pdf->Code128($x, $pdf->GetY(), $code, $margen_med2 - 10, 20);

		$pdf->SetY($pdf->GetY() + 20);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array(275));
		$pdf->SetX($x);
		$arr = array($code);
		$pdf->Row($arr, 10);
	}
	$pdf->ln(10);
	$yfin = $pdf->GetY();
	$pdf->RoundedRect($x - $margen, $y - $margen, $margen_med2, $yfin - $y + $margen, 10, $style = '', $angle = '1234');

	$medida_fin = $pdf->GetY();


	//========================= fin cuadro derecha inicial========================







	//============================================cuadro cliente ==========================================
	$y = $pdf->GetY() + $margen * 2;
	$x = $pdf->GetX();
	$pdf->SetXY($x, $y);
	$margen_med3 = 540;


	$pdf->SetWidths(array(370, 85, 80));
	$arr = array('Razón social/nombres y apellidos:', 'Identificación:', 'Telefono'); //mio
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(370, 85, 80));
	$arr = array(mb_convert_encoding($datos[0]['Razon_Social'], 'ISO-8859-1', 'UTF-8'), $datos[0]['RUC_CI'], $datos[0]['Telefono_RS']); //mio
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 155, 100));

	$arr = array('Dirección: ' . $datos[0]['Direccion_RS'], 'Fecha emisión: ' . $datos[0]['Fecha']->format('Y-m-d'), 'Fecha pago: ' . $datos[0]['Fecha_V']->format('Y-m-d')); //mio
	$pdf->Row($arr, 10);
	$pdf->SetWidths(array(270, 155, 100));
	if ('DOLAR' == 'DOLAR') {
		$mon = 'USD';
	} else {
		$mon = 'USD';
		//se busca otras monedas
	}
	//die();
	$DiasPago = strval(strtotime($datos[0]['Fecha_V']->format('Y-m-d')) - strtotime($datos[0]['Fecha']->format('Y-m-d'))) / (60 * 60 * 24);
	$arr = array(
		'FORMA DE PAGO: ' . $datos[0]['Tipo_Pago'],
		'MONTO: ' . number_format($datos[0]['Total_MN'], 2, '.', ',') . '  '
		,
		'Condición de venta: ' . $DiasPago . ' días'
	);
	$pdf->Row($arr, 10);

	$yfin = $pdf->GetY();
	$xfin = $pdf->GetX();
	$pdf->RoundedRect($x - $margen, $y - $margen, $margen_med3, $yfin - $y + $margen, $radio, $style = '', $angle = '1234');



	//================================fin cuadro cliente========================================================= 

	//================================Inicio Guía de Remisión=========================================================
	//TOMADO DE VB SRI_Generar_Documento_PDF(
	switch ($datos[0]['TC']) {
		case 'FA':
			if (isset($datos[0]['Remision']) && $datos[0]['Remision'] > 0) {
				$y = $pdf->GetY() + $margen * 2;
				$x = $pdf->GetX();
				$pdf->SetXY($x, $y);
				$pdf->SetFont('Arial', '', 6);
				$pdf->SetWidths(array(270, 155, 250));
				$arr = array("Guía Remisión: " . $datos[0]['Serie_GR'] . "-" . sprintf("%09d", $datos[0]['Remision']), "Entrega: " . $datos[0]['Comercial']);
				$pdf->Row($arr, 10);

				$pdf->SetWidths(array(140, 130, 250));
				$arr = array("Pedido: " . $datos[0]['Pedido'], "Zona: " . ULCase($datos[0]['CiudadGRF']) . " - " . $datos[0]['Zona'], ((strlen($datos[0]['Lugar_Entrega']) > 1) ? "Lugar de Entrega: " . $datos[0]['Lugar_Entrega'] : "Lugar de Entrega: " . $datos[0]['DireccionC']));
				$pdf->Row($arr, 10);

				$yfin = $pdf->GetY();
				$xfin = $pdf->GetX();
				$pdf->RoundedRect($x - $margen, $y - $margen, $margen_med3, $yfin - $y + $margen, $radio, $style = '', $angle = '1234');
			}
			break;

		default:
			// code...
			break;
	}
	//================================fin Guía de Remisión=========================================================

	//================================cuadro detalle========================================================= 

	//Tomado de SRI_Generar_PDF_FA( Linea 1259
	if ($datos[0]['SP']) {
		$titulo1 = "Codigo Auxiliar";
		$titulo2 = "Codigo Unitario";
	} else {
		$titulo1 = "Codigo Unitario";
		$titulo2 = "Codigo Auxiliar";
	}
	//FIN Tomado de SRI_Generar_PDF_FA( Linea 1259

	//datos factura
	$y = $pdf->GetY() + $margen + 2;
	$x = $pdf->GetX() - $margen;
	$pdf->SetXY($x, $y);
	$margen_med3 = 540;

	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetWidths(array(55, 55, 35, 45, 134, 45, 45, 40, 40, 45));
	$arr = array($titulo1, $titulo2, "Cantidad Total", "Cantidad Bonif.", "D e s c r i p c i ó n", "Lote No. /Orden No", "Precio Unitario", "Valor Descuento", "Desc. %", "Valor Total");
	$pdf->Row($arr, 10, 1);
	$pdf->SetFont('Arial', '', 6);

	// print_r($datos);die();
	$imp_mes = $datos[0]['Imp_Mes'];
	$detalle_descuento = array();
	foreach ($detalle as $key => $value) {

		if ($value['Tipo_Hab'] != '.') {
			if (count($detalle_descuento) > 0) {
				$exis = 0;
				foreach ($detalle_descuento as $key2 => $value2) {
					if ($value2['detalle'] == $value['Tipo_Hab']) {
						$detalle_descuento[$key2]['valor'] += $value['Total_Desc'];
						$exis = 1;
					}
				}
				if ($exis == 0) {
					$detalle_descuento[] = array('detalle' => $value['Tipo_Hab'], 'valor' => $value['Total_Desc']);
				}
			} else {
				$detalle_descuento[] = array('detalle' => $value['Tipo_Hab'], 'valor' => $value['Total_Desc']);
			}
		}
	}

	// print_r($detalle_descuento);die();
	foreach ($detalle as $key => $value) {
		//tomado de SRI_Generar_PDF_FA( Linea 1298
		if (strlen($value["Codigo_Barra"]) > 1) {
			$Cod_Bar = $value["Codigo_Barra"];
		} else {
			$Cod_Bar = (isset($value["Cod_Barras"])) ? $value["Cod_Barras"] : G_NINGUNO;
		}

		$Cod_Aux = (isset($value["Desc_Item"])) ? $value["Desc_Item"] : G_NINGUNO;
		$Total_Desc = $value["Total_Desc"] + $value["Total_Desc2"];

		if ($Total_Desc > 0 && $value["Total"] <> 0) {
			$Porc_Str = number_format((($Total_Desc * 100) / $value['Total']), 0, '.', '') . '%';
		} else {
			$Porc_Str = "";
		}

		$CODIGO1 = "";
		$CODIGO2 = "";
		if ($datos[0]['SP']) {
			if (strlen($Cod_Bar) > 1) {
				$CODIGO1 = $Cod_Bar;
			}
			$CODIGO2 = (strlen($Cod_Aux) > 1) ? $Cod_Aux : $value["Codigo"];

		} else {
			$CODIGO1 = (strlen($Cod_Aux) > 1) ? $Cod_Aux : $value["Codigo"];
			if (strlen($Cod_Bar) > 1) {
				$CODIGO2 = $Cod_Bar;
			}
		}
		//FIN tomado de  SRI_Generar_PDF_FA( Linea 1298
		$descto = '0';
		if ($value['Total'] > 0) {
			$descto = $Porc_Str;
		}
		$pdf->SetX($x);
		//INICIO PRODUCTO
		$Producto = $value["Producto"];
		if ($value["Codigo"] != "99.41" && $imp_mes) {
			$Producto = $Producto . " " . $value["Ticket"] . " " . $value["Mes"] . PHP_EOL;
		}

		if ($datos[0]['SP']) {
			if (CFechaLong($value["Fecha_Fab"]) != CFechaLong($value["Fecha_Exp"])) {
				$Producto .= "ELAB. " . $value["Fecha_Fab"] . ", VENC. " . $value["Fecha_Exp"] . " " . PHP_EOL;
			}

			if (strlen($value["Reg_Sanitario"]) > 1) {
				$Producto .= "Reg. Sanit. " . $value["Reg_Sanitario"] . PHP_EOL;
			}

			if (strlen($value["Modelo"]) > 1) {
				$Producto .= "Modelo: " . $value["Modelo"] . PHP_EOL;
			}

			if (strlen($value["Procedencia"]) > 1) {
				$Producto .= "Procedencia: " . $value["Procedencia"] . PHP_EOL;
			}
		}

		if (strlen($value["Serie_No"]) > 1) {
			$Producto .= "Serie No. " . $value["Serie_No"];
		}
		//FIN PRODUCTO

		if (strlen($value["Tipo_Hab"]) > 1) {
			if ($value['Total_Desc'] > 0) {
				$descto = '';
				$totaldes = '';
				$totalfac = number_format($value['Total'], 2, '.', '');

			} else {
				$totaldes = number_format($value['Total_Desc'], 2, '.', '') + number_format($value['Total_Desc2'], 2, '.', '');
				$totalfac = number_format($value['Total'], 2, '.', '');
			}
		} else {
			$totaldes = number_format($value['Total_Desc'], 2, '.', '') + number_format($value['Total_Desc2'], 2, '.', '');
			$totalfac = number_format($value['Total'], 2, '.', '');
		}
		$pdf->SetWidths(array(55, 55, 35, 45, 134, 45, 45, 40, 40, 45));
		$pdf->SetAligns(array("L", "L", "R", "R", "L", "L", "R", "R", "R", "R"));
		//$arr=array($arr1[$i]);
		$arr = array($CODIGO1, $CODIGO2, number_format($value['Cantidad'], 2, '.', ''), '', $Producto, '', number_format($value['Precio'], $_SESSION['INGRESO']['Dec_PVP'], '.', ''), $totaldes, $descto, $totalfac);
		$pdf->Row($arr, 10, 1);

		// if(strlen($value["Tipo_Hab"]) > 1)
		// {
		//   if($value['Total_Desc'] > 0){
		//   	$pdf->SetX(36);
		//   	$totaldes = number_format($value['Total_Desc'],2,'.','');
		//    $pdf->SetWidths(array(55,55,45,45,123,45,45,40,40,45));
		// 	$pdf->SetAligns(array("L","L","R","R","L","L","R","R","R","R"));
		// 	//$arr=array($arr1[$i]);
		// 	$arr=array('','','','',$value['Tipo_Hab'],'','','','',$totaldes);
		// 	$pdf->Row($arr,10,1); 
		// 	}
		// }        

	}

	if (count($detalle_descuento) > 0) {
		foreach ($detalle_descuento as $key => $value) {
			$pdf->SetX(36);
			$totaldes = number_format($value['valor'], 2, '.', '');
			$pdf->SetWidths(array(55, 55, 35, 45, 134, 45, 45, 40, 40, 45));
			$pdf->SetAligns(array("L", "L", "R", "R", "L", "L", "R", "R", "R", "R"));
			//$arr=array($arr1[$i]);
			$arr = array('', '', '', '', $value['detalle'], '', '', '', '', $totaldes);
			$pdf->Row($arr, 10, 1);
		}
	}



	$yfin = $pdf->GetY();
	$xfin = $pdf->GetX();
	// $pdf->RoundedRect($x-$margen, $y-$margen, $margen_med3, $yfin-$y+$margen, $radio, $style = '', $angle = '1234');

	//===================================================fin cuadrpo detalle======================================
	if ($yfin >= 612) {
		$pdf->AddPage();
	}
	// print_r($yfin);die();
	//====================================================cuadro datos adicionales======================
	$y = $pdf->GetY();
	$x = $pdf->GetX() - $margen;
	$pdf->SetXY($x, $y);
	$misma_ln = $y;

	//informacion adicional
	$pdf->SetFont('Arial', 'B', 6);
	//echo $pdf->GetY();
	//die();
	$y = $pdf->GetY();
	$pdf->SetXY(41 - $margen, $y + 5);
	$pdf->SetWidths(array(140, 40, 95, 46));
	// infofactura
	$arr = array("INFORMACIÓN ADICIONAL", "Fecha", "Detalle del pago", "Monto Abono");
	$pdf->Row($arr, 10, 1);

	$y = $pdf->GetY() - 5; //377
	$pdf->SetFont('Arial', '', 5.5);
	$pdf->SetX($x);
	$pdf->SetWidths(array(40));
	//verificamos si es una o varias etiquetas
	// datos adicionales
	$pdf->SetWidths(array(140));
	//print_r($educativo);


	if (isset($dato[0]['Telefono_RS']) && $datos[0]['Telefono_RS'] != '.' && $datos[0]['Telefono_RS'] != '') {
		$arr = array('Telefono: ' . $datos[0]['Telefono_RS']);
		$pdf->Row($arr, 10);
		$pdf->SetWidths(array(140));
	}



	///----------------------  infomrmacion adicional cuando punto de venta es diferente de 001 ----------------------
	// print_r($sucursal);die();


	if (!empty($sucursal) && count($sucursal) > 0 && $punto != '001' && $suc == '001' && strlen($sucursal[0]['RUC_Establecimiento']) == 13) {

		$arr = array('Punto Emision: ' . $datos[0]['Serie']);
		$pdf->Row($arr, 10);

		if ($sucursal[0]['Nombre_Establecimiento'] != '.' && $sucursal[0]['Nombre_Establecimiento'] != '') {
			$arr = array($sucursal[0]['Nombre_Establecimiento']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['RUC_Establecimiento'] != '.' && $sucursal[0]['RUC_Establecimiento'] != '') {
			$arr = array('Ruc punto Emision: ' . $sucursal[0]['RUC_Establecimiento']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['Direccion_Establecimiento'] != '.' && $sucursal[0]['Direccion_Establecimiento'] != '') {
			$arr = array('Direccion punto Emision: ' . $sucursal[0]['Direccion_Establecimiento']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['Telefono_Estab'] != '.' && $sucursal[0]['Telefono_Estab'] != '') {
			$arr = array('Telefono punto Emision: ' . $sucursal[0]['Telefono_Estab']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['Email_Establecimiento'] != '.' && $sucursal[0]['Email_Establecimiento'] != '') {
			$arr = array('Email punto Emision: ' . $sucursal[0]['Email_Establecimiento']);
			$pdf->Row($arr, 10);
		}

		if ($sucursal[0]['Cta_Establecimiento'] != '.' && $sucursal[0]['Cta_Establecimiento'] != '') {
			$arr = array('Cta Punto Emision: ' . $sucursal[0]['Cta_Establecimiento']);
			$pdf->Row($arr, 10);
		}


		if ($sucursal[0]['Placa_Vehiculo'] != '.' && $sucursal[0]['Placa_Vehiculo'] != '') {
			$arr = array('Placas: ' . $sucursal[0]['Placa_Vehiculo']);
			$pdf->Row($arr, 10);
		}


	}

	///----------------------  fin infomrmacion adicional cuando punto de venta es diferente de 001 ----------------------


	///----------------------  infomrmacion adicional cuan establecimiento es diferente de 001 ----------------------

	if (!empty($sucursal) && count($sucursal) > 0 && $suc != '001' && strlen($sucursal[0]['RUC_Establecimiento']) == 13) {
		$arr = array('Establecimiento: ' . $datos[0]['Serie']);
		$pdf->Row($arr, 10);

		if ($sucursal[0]['Nombre_Establecimiento'] != '.' && $sucursal[0]['Nombre_Establecimiento'] != '') {
			$arr = array($sucursal[0]['Nombre_Establecimiento']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['RUC_Establecimiento'] != '.' && $sucursal[0]['RUC_Establecimiento'] != '') {
			$arr = array('Ruc establecimiento: ' . $sucursal[0]['RUC_Establecimiento']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['Telefono_Estab'] != '.' && $sucursal[0]['Telefono_Estab'] != '') {
			$arr = array('Telefono establecimiento: ' . $sucursal[0]['Telefono_Estab']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['Email_Establecimiento'] != '.' && $sucursal[0]['Email_Establecimiento'] != '') {
			$arr = array('Email establecimiento: ' . $sucursal[0]['Email_Establecimiento']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['Cta_Establecimiento'] != '.' && $sucursal[0]['Cta_Establecimiento'] != '') {
			$arr = array('Cta_Establecimiento: ' . $sucursal[0]['Cta_Establecimiento']);
			$pdf->Row($arr, 10);
		}


		if ($sucursal[0]['Placa_Vehiculo'] != '.' && $sucursal[0]['Placa_Vehiculo'] != '') {
			$arr = array('Placas: ' . $sucursal[0]['Placa_Vehiculo']);
			$pdf->Row($arr, 10);
		}
	}

	//================================Inicio INFORMACION ADICIONAL=======================
	$info_adicional = "";
	if (isset($datos[0]['Cliente']) && $datos[0]['Razon_Social'] != $datos[0]['Cliente']) {
		$info_adicional .= 'Beneficiario: ' . $datos[0]['Cliente'] . PHP_EOL;

		if (isset($datos[0]['CI_RUC'])) {
			$info_adicional .= 'Codigo: ' . $datos[0]['CI_RUC'] . PHP_EOL;
		}
	}
	if (isset($datos[0]['Grupo']) && isset($datos[0]['Curso']) && isset($datos[0]['DireccionC']) && $datos[0]['Grupo'] != G_NINGUNO && $datos[0]['Curso'] != $datos[0]['DireccionC'] && $datos[0]['Imp_Mes']) {
		$info_adicional .= 'Grupo: ' . $datos[0]['Grupo'] . "-" . $datos[0]['Curso'] . PHP_EOL;
	}
	if (isset($datos[0]['EmailC']) && strlen($datos[0]['EmailC']) > 1 && strpos($datos[0]['EmailC'], "@") > 0) {
		$info_adicional .= 'Email: ' . $datos[0]['EmailC'] . PHP_EOL;
	}
	if (isset($datos[0]['EmailR']) && strlen($datos[0]['EmailR']) > 1 && strpos($datos[0]['EmailR'], "@") > 0 && strpos($datos[0]['EmailC'], $datos[0]['EmailR']) === false) {
		$info_adicional .= 'Email2: ' . $datos[0]['EmailR'] . PHP_EOL;
	}
	if (isset($datos[0]['Contacto']) && strlen($datos[0]['Contacto']) > 1) {
		$info_adicional .= 'Referencia: ' . $datos[0]['Contacto'] . PHP_EOL;
	}
	if (isset($datos[0]['TelefonoC']) && strlen($datos[0]['TelefonoC']) > 1) {
		$info_adicional .= 'Teléfono: ' . $datos[0]['TelefonoC'] . PHP_EOL;
	}
	if (strlen($datos[0]['Nota']) > 1) {
		$info_adicional .= 'Nota: ' . $datos[0]['Nota'] . PHP_EOL;
	}
	if (strlen($datos[0]['Observacion']) > 1) {
		$info_adicional .= 'Observacion: ' . $datos[0]['Observacion'] . PHP_EOL;
	}
	//================================Inicio INFORMACION DE ABONOS=======================
	$fechas_abonos = $detalle_abonos = $monto_abonos = "";
	// print_r($abonos);die();
	if (!empty($abonos) && $abonos && is_object($abonos)) {
		foreach ($abonos as $key => $value) {
			$fechas_abonos .= $value['Fecha']->format('Y-m-d') . PHP_EOL;
			$detalle_abonos .= $value['Banco'] . PHP_EOL;
			$monto_abonos .= $value['Abono'] . PHP_EOL;
		}
	}
	//================================Fin INFORMACION DE ABONOS=======================
	$info_ad = 140;
	$info_fe = 40;
	$info_de = 95;
	$info_monto = 46;

	$pdf->SetAligns(array("L", "L", "L", "R"));
	$pdf->SetWidths(array($info_ad, $info_fe, $info_de, $info_monto));
	$arr = array($info_adicional, $fechas_abonos, $detalle_abonos, $monto_abonos);
	$pdf->Row($arr, 10);
	//================================Fin INFORMACION ADICIONAL=======================

	///---------------------- fin infomrmacion adicional cuan establecimiento es diferente de 001 ----------------------

	//bordes de informacion adicional---------------------
	$yfin_adiconales = $pdf->GetY();
	$pdf->SetXY($x, $y + $margen);
	$pdf->Cell($info_ad, $yfin_adiconales - $y - $margen, '', '1', 1, 'Q');
	$pdf->SetXY($x + $info_ad, $y + $margen);
	$pdf->Cell($info_fe, $yfin_adiconales - $y - $margen, '', '1', 1, 'Q');

	$pdf->SetXY($x + $info_ad + $info_fe, $y + $margen);
	$pdf->Cell($info_de, $yfin_adiconales - $y - $margen, '', '1', 1, 'Q');
	$pdf->SetXY($x + $info_ad + $info_fe + $info_de, $y + $margen);
	$pdf->Cell($info_monto, $yfin_adiconales - $y - $margen, '', '1', 1, 'Q');
	//-----------------fin bordes de informacion adicional;

	$y_final_leyenda = $yfin_adiconales;

	// print_r($_SESSION['INGRESO']);die();
	//leyenda final
	$pdf->SetFont('Arial', '', 5);
	$pdf->SetXY($x, ($y_final_leyenda));
	$pdf->Cell(321, 46, '', '1', 1, 'Q');
	$pdf->SetXY($x, ($y_final_leyenda + 2));
	$pdf->SetWidths(array(319));
	$arr = array($_SESSION['INGRESO']['LeyendaFA']);
	$pdf->Row($arr, 8);
	//subtotales
	//depende del valor de coordenada 'y' del detalle
	$pdf->SetFont('Arial', '', 7);


	$yfin = $pdf->GetY();
	$xfin = $pdf->GetX();




	//========================================= cuadro totales======================================

	//	$arr1="<totalImpuesto";
	//$arr1=array("<totalImpuesto>","</totalImpuesto>");
	$sub_con_iva = 0;
	$sub_sin_iva = 0;
	foreach ($detalle as $key => $value) {

		// print_r($value);
		if (number_format($value['Total_IVA'], 2, '.', '') != '0.00') {
			// print_r($value['Total_IVA'].'-'.$value['Total']);
			$sub_con_iva += $value['Total'];
		} else {
			// print_r($value['Total_IVA'].'-'.$value['Total']);
			$sub_sin_iva += $value['Total'];

		}
	}
	$imp = round($datos[0]['Porc_IVA'] * 100);
	$ba0 = $sub_sin_iva;
	$bai = $sub_con_iva;
	$vimp0 = 0;
	$vimp1 = $datos[0]['IVA'];
	$descu = $datos[0]['Descuento'] + $datos[0]['Descuento2'];
	// print_r($bai.'-'.$ba0);

	// die();
	$margen_med4 = 210;

	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, ($y - 10));
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y - 9));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));

	$arr = array("SUBTOTAL " . $imp . "%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y - 9));
	$formateado = sprintf("%01.2f", $bai);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y - 10 + 11; //365
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(175));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL 0%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$arr = array(sprintf("%01.2f", $ba0));
	$formateado = sprintf("%01.2f", $ba0);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');
	//echo $formateado;
	//str_pad($ba0, 2, '0', STR_PAD_RIGHT);
	//exit();
	//$pdf->Row($arr,10);

	$y = $y + 11; //380
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("TOTAL DESCUENTO:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = sprintf("%01.2f", $descu);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //395
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL NO OBJETO DE IVA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = sprintf("%01.2f", "0.00");
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //410
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL EXENTO DE IVA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = sprintf("%01.2f", "0.00");
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //425
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL SIN IMPUESTOS:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = number_format($ba0 - $descu, 2, '.', '');
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //440
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("ICE:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = sprintf("%01.2f", "0.00");
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //455
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("IVA " . $imp . "%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = sprintf("%01.2f", $vimp1);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //470
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("IVA 0%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = sprintf("%01.2f", $vimp0);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //485
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	if ($_SESSION['INGRESO']['Servicio'] != 0) {
		$arr = array("SERVICIO " . $_SESSION['INGRESO']['Servicio'] . "%:");
	} else {
		$arr = array("SERVICIO:");
	}
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = number_format($datos[0]['Servicio'], 2, '.', '');
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //500
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(165));
	$pdf->SetAligns(array("L"));
	$arr = array("VALOR TOTAL:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$pdf->SetAligns(array("R"));
	$formateado = sprintf("%01.2f", $datos[0]['Total_MN']);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$yfin = $pdf->GetY();
	$xfin = $pdf->GetX();

	//========================================= fin cuadro totales======================================



	if ($imp1 == false || $imp1 == 0) {
		$pdf->Output();
	}
	if ($imp1 == 1) {
		if(!file_exists(dirname(__DIR__, 2) . '/TEMP/'))
		{
			mkdir(dirname(__DIR__, 2) . '/TEMP/', 0777);
		}
		$pdf->Output('F', dirname(__DIR__, 2) . '/TEMP/' . $datos[0]['Serie'] . '-' . generaCeros($datos[0]['Factura'], 7) . '.pdf');

		// $pdf->Output('TEMP/'.$nombre.'.pdf','F'); 
	}
}

function imprimir_Generar_Recibo_PDF($datos, $detalle, $imp1 = false, $abonos = false, $sucursal = array()){

	//print_r($datos); 
	//print_r($detalle);
	//die();
	$pdf = new PDF('P', 'pt', 'LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();
	$i = 0;
	$agente = '';
	$rimpe = '';
	if (isset($datos['Tipo_contribuyente']) && count($datos['Tipo_contribuyente']) > 0) {
		$agente = $datos['Tipo_contribuyente']['@Agente'];
		if ($datos['Tipo_contribuyente']['@micro'] != '.') {
			$rimpe = $datos['Tipo_contribuyente']['@micro'];
		}
	}

	$x = 41;
	$pdf->SetXY($x, 20);
	$src = url_logo();
	if ($src !== '.') {
		$pdf->Image($src, 40, 22, 80, 40);
	} 

	$pdf->Ln(60);
	$x = $pdf->GetX();
	$y = $pdf->GetY();
	$salto_ln = 5;
	$radio = 5;

	//=======================================cuadro izquierda inicial=================
	$margen_med = 240;
	$margen = 5;
	if ($_SESSION['INGRESO']['Nombre_Comercial'] == $_SESSION['INGRESO']['Razon_Social']) {
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->SetWidths(array($margen_med));
		$arr = array($_SESSION['INGRESO']['Razon_Social']); //mio
		$pdf->Row($arr, 10);
		// $pdf->ln(5);

		// print_r($pdf->GetY());die();

	} else {
		//razon social
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->SetWidths(array($margen_med));
		$arr = array($_SESSION['INGRESO']['Razon_Social']); //mio
		$pdf->Row($arr, 8);
		// $pdf->ln(8);
		// $pdf->Ln($salto_ln);		
		//nombre comercial
		$pdf->SetWidths(array($margen_med));
		$arr = array($_SESSION['INGRESO']['Nombre_Comercial']); //mio
		$pdf->Row($arr, 8);
		// $pdf->ln(8);
	}
	// print_r($pdf->GetY());die();
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetWidths(array($margen_med));
	$arr = array('Dirección Matríz');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetWidths(array($margen_med));
	$arr = array($_SESSION['INGRESO']['Direccion']);
	$pdf->Row($arr, 10);

	// print_r($datos[0]['Serie']);die();
	//sucursal si es diferente a 001
	//$punto = substr($datos[0]['Serie'], 3, 6);
	//$suc = substr($datos[0]['Serie'], 0, 3);

	// print_r($punto);die();
	/*if (isset($datos[0]['Serie'])) {
		if ($suc != '001' && count($sucursal) > 0 && $sucursal[0]['Nombre_Establecimiento'] != '.' && $sucursal[0]['Nombre_Establecimiento'] != '') {
			$pdf->SetWidths(array($margen_med));
			$arr = array('Direccion de establecimiento / Sucursal'); //mio
			$pdf->Row($arr, 10);
			$pdf->ln(10);
			$arr = array($sucursal[0]['Direccion_Establecimiento']); //mio
			$pdf->Row($arr, 10);
			$pdf->ln(10);
		}
	}*/

	if (strlen($_SESSION['INGRESO']['Telefono1']) > 1) {
		$pdf->SetWidths(array($margen_med));
		$arr = array(mb_convert_encoding($_SESSION['INGRESO']['Telefono1'], 'UTF-8')); //mio
		$pdf->Row($arr, 10);
	}
	if (strlen($_SESSION['INGRESO']['Email']) > 1) {
		$pdf->SetWidths(array($margen_med));
		$arr = array(mb_convert_encoding($_SESSION['INGRESO']['Email'], 'UTF-8')); //mio
		$pdf->Row($arr, 10);
	}

	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetWidths(array(200, 40));
	$conta = 'NO';
	if ($_SESSION['INGRESO']['Obligado_Conta'] != 'NO') {
		$conta = 'SI'; //mio
	}
	$arr = array('Obligado a llevar contabilidad:', $conta);
	$pdf->Row($arr, 10);
	$yfin = $pdf->GetY();
	$xfin = $pdf->GetX();
	$pdf->RoundedRect($x - $margen, $y - $margen, $margen_med, $yfin - $y + $margen, $radio, $style = '', $angle = '1234');
	$pdf->ln(2);
	// print_r($yfin);die();
	$y_alineado = $yfin;
	//==================================fin cuadro izquierda inicial==================

	//==================================cuadro derecha inicial invisible========================

	$medida_1 = $pdf->GETY();

	$pdf->SetTextColor(255, 255, 255);
	$x = 200 + 100;
	$pdf->SetY($yfin + 20);
	$y = $pdf->GetY();
	$margen_med2 = 280;
	$col_1 = 142;
	$pdf->SetXY($x, $pdf->GetY());
	$col_2 = $margen_med2 - $col_1;

	$misma_ln = $pdf->GetY();
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetWidths(array($col_1));
	$arr = array('R.U.C. ' . $_SESSION['INGRESO']['RUC']);
	$pdf->Row($arr, 10);

	if ($rimpe == '') {
		$pdf->SetXY($x, $misma_ln);
		// $pdf->SetTextColor(225,51,51);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetWidths(array($col_1, $col_2));
		$pdf->SetFont('Arial', '', 7);
		$arr = array('', $rimpe); //mio
		$pdf->Row($arr, 10);
	}
	// /-----------------------------------------------------------------------------
	if ($agente != '' && $agente != '.') {
		$pdf->SetX($x);
		// $pdf->SetTextColor(225,51,51);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array($col_1 + $col_2));
		$arr = array('Agente de Retención Resolución: ' . $agente);
		$pdf->Row($arr, 10);
	}
	// ----------------------------------------------------------------------------------
	$pdf->SetX($x);
	$pdf->SetTextColor(255, 255, 255);
	// $pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial', '', 12);
	$pdf->SetWidths(array($col_1));
	// print_r($datos);die();

	$misma_ln = $pdf->GetY();
	if (isset($datos[0]['TC']) && $datos[0]['TC'] == 'LC') {
		$arr = array('Liquidacion compra No.');
	} else {
		$arr = array('Factura No.');
	}
	$pdf->Row($arr, 10);

	$pdf->SetXY($x, $misma_ln);
	$pdf->SetFont('Arial', '', 11);
	// $pdf->SetTextColor(225,51,51);
	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetWidths(array($col_1, $col_2));
	//$ptoEmi = substr($datos[0]['Serie'], 3, 6);
	//$Serie = substr($datos[0]['Serie'], 0, 3);
	//$arr = array('', $Serie . '-' . $ptoEmi . '-' . generaCeros($datos[0]['Factura'], 9)); //mio
	$pdf->Row($arr, 10);
	$pdf->SetTextColor(0);
	// print_r($datos);die();
	// fecha y hora


	$pdf->SetX($x);
	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$fecha_actual = date('Y-m-d H:i:s');
$arr = array('FECHA Y HORA DE EMISION: ' . $fecha_actual);
	$pdf->Row($arr, 10);


	//emisión
	$pdf->SetX($x);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('EMISIÓN:', 'NORMAL');
	$pdf->Row($arr, 13);


	//ambiente
	$ambiente = substr($datos['Autorizacion'], 23, 1);
	// 
	//print_r($ambiente);die();
	$am = '';
	if ($ambiente == 2) {
		$am = 'PRODUCCION';

	} else if ($ambiente == 1) {
		$am = 'PRUEBA';
	}
	$pdf->SetX($x);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('AMBIENTE: ', $am);
	$pdf->Row($arr, 10);


	//clave de acceso barcode y numero
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetX($x);
	$pdf->SetWidths(array($margen_med));
	$arr = array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr, 10);
	/*if ($datos['Clave_Acceso'] != $datos['Autorizacion']) {

		$pdf->ln(10);
		$code = $datos['Clave_Acceso'];
		// $pdf->Code128($x,$pdf->GetY(),$code,$margen_med2-10,20);

		$pdf->SetY($pdf->GetY() + 20);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array(275));
		$pdf->SetX($x);
		// $arr=array($code);
		// $pdf->Row($arr,10);	
	} else if ($datos['Clave_Acceso'] > 39) {

		$pdf->ln(10);
		$code = $datos['Clave_Acceso'];
		// $pdf->Code128($x,$pdf->GetY(),$code,$margen_med2-10,20);

		$pdf->SetY($pdf->GetY() + 20);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array(275));
		$pdf->SetX($x);
		$arr = array($code);
		// $pdf->Row($arr,10);	
	}*/
	$pdf->ln(10);
	$yfin = $pdf->GetY();
	// $pdf->RoundedRect($x-$margen, $y-$margen, $margen_med2, $yfin-$y+$margen, 10, $style = '', $angle = '1234');		

	$medida_fin = $yfin;

	//========================================== finde cuado invisible==============================000
// 	if($medida_fin>$medida_1)
// 	{
	$medida_burbu = $medida_fin - $medida_1;
	$pos_burbuja_derecha_y = $y_alineado - $medida_burbu + $margen * 2;
	//   }else{

	//   	// print_r($pdf->PageNo());die();
//     $medida_burbu = $medida_1-$medida_fin;    
//     $pos_burbuja_derecha_y = $y_alineado+100+$margen*2;
// }

	// print_r($y_alineado*-1);die();
// print_r($pos_burbuja_derecha_y*-1);die();
	$x = 200 + 100;
	$pdf->SetAligns('L');
	$pdf->SetY($pos_burbuja_derecha_y);
	$y = $pdf->GetY();
	$margen_med2 = 280;
	$col_1 = 142;
	$pdf->SetXY($x, $pdf->GetY());
	$col_2 = $margen_med2 - $col_1;
	$pdf->SetTextColor(0, 0, 0);
	$misma_ln = $pdf->GetY();
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetWidths(array($col_1));
	$arr = array('R.U.C. ' . $_SESSION['INGRESO']['RUC']);
	$pdf->Row($arr, 10);

	if ($rimpe != '') {
		$pdf->SetXY($x, $misma_ln);
		$pdf->SetTextColor(225, 51, 51);
		$pdf->SetWidths(array($col_1, $col_2));
		$pdf->SetFont('Arial', '', 7);
		$arr = array('', $rimpe); //mio
		$pdf->Row($arr, 10);
	}
	// /-----------------------------------------------------------------------------
	if ($agente != '' && $agente != '.') {
		$pdf->SetX($x);
		$pdf->SetTextColor(225, 51, 51);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array($col_1 + $col_2));
		$arr = array('Agente de Retención Resolución: ' . $agente);
		$pdf->Row($arr, 10);
	}
	// ----------------------------------------------------------------------------------
	$pdf->SetX($x);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 12);
	$pdf->SetWidths(array($col_1));
	// print_r($datos);die();

	$misma_ln = $pdf->GetY();
	if (isset($datos[0]['TC']) && $datos[0]['TC'] == 'LC') {
		$arr = array('Liquidacion compra No.');
	} else {
		$arr = array('Factura No.');
	}
	$pdf->Row($arr, 10);

	$pdf->SetXY($x, $misma_ln);
	$pdf->SetFont('Arial', '', 11);
	$pdf->SetTextColor(225, 51, 51);
	$pdf->SetWidths(array($col_1, $col_2));
	//$ptoEmi = substr($datos[0]['Serie'], 3, 6);
	//$Serie = substr($datos[0]['Serie'], 0, 3);
	//$arr = array('', $Serie . '-' . $ptoEmi . '-' . generaCeros($datos[0]['Factura'], 9)); //mio
	$pdf->Row($arr, 10);
	$pdf->SetTextColor(0);
	// print_r($datos);
	// fecha y hora


	$pdf->SetX($x);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('FECHA Y HORA DE EMISION:', $fecha_actual);
	$pdf->Row($arr, 10);


	//emisión
	$pdf->SetX($x);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('EMISIÓN:', 'NORMAL');
	$pdf->Row($arr, 13);


	//ambiente
	$ambiente = substr($datos['Autorizacion'], 23, 1);
	// print_r($datos[0]['Autorizacion']);die();
	//print_r($ambiente);die();
	$am = '';
	if ($ambiente == 2) {
		$am = 'PRODUCCION';

	} else if ($ambiente == 1) {
		$am = 'PRUEBA';
	}
	$pdf->SetX($x);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('AMBIENTE: ', $am);
	$pdf->Row($arr, 10);


	//clave de acceso barcode y numero
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetX($x);
	$pdf->SetWidths(array($margen_med));
	$arr = array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr, 10);
	/*if ($datos[0]['Clave_Acceso'] != $datos[0]['Autorizacion']) {
		$pdf->ln(10);
		$code = $datos[0]['Autorizacion'];
		$pdf->Code128($x, $pdf->GetY(), $code, $margen_med2 - 10, 20);

		$pdf->SetY($pdf->GetY() + 20);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array(275));
		$pdf->SetX($x);
		$arr = array($code);
		$pdf->Row($arr, 10);

	} else if ($datos[0]['Clave_Acceso'] > 39) {

		$pdf->ln(10);
		$code = $datos[0]['Clave_Acceso'];
		$pdf->Code128($x, $pdf->GetY(), $code, $margen_med2 - 10, 20);

		$pdf->SetY($pdf->GetY() + 20);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array(275));
		$pdf->SetX($x);
		$arr = array($code);
		$pdf->Row($arr, 10);
	}*/
	$pdf->ln(10);
	$yfin = $pdf->GetY();
	$pdf->RoundedRect($x - $margen, $y - $margen, $margen_med2, $yfin - $y + $margen, 10, $style = '', $angle = '1234');

	$medida_fin = $pdf->GetY();


	//========================= fin cuadro derecha inicial========================







	//============================================cuadro cliente ==========================================
	$y = $pdf->GetY() + $margen * 2;
	$x = $pdf->GetX();
	$pdf->SetXY($x, $y);
	$margen_med3 = 540;


	$pdf->SetWidths(array(370, 85, 80));
	$arr = array( 'Identificación:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(370, 85, 80));
	$arr = array($datos['CI_RUC']); 
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 155, 100));

	$arr = array('Dirección: ' . $datos['DireccionC'], 'Fecha emisión: ' . $fecha_actual); //mio
	$pdf->Row($arr, 10);
	$pdf->SetWidths(array(270, 155, 100));
	if ('DOLAR' == 'DOLAR') {
		$mon = 'USD';
	} else {
		$mon = 'USD';
		//se busca otras monedas
	}
	//die();
	/*$DiasPago = strval(strtotime($datos['Fecha_V']->format('Y-m-d')) - strtotime($datos['Fecha']->format('Y-m-d'))) / (60 * 60 * 24);
	$arr = array(
		'FORMA DE PAGO: ' . $datos['Tipo_Pago'],
		'MONTO: ' . number_format($datos['Total_MN'], 2, '.', ',') . '  '
		,
		'Condición de venta: ' . $DiasPago . ' días'
	);*/
	$pdf->Row($arr, 10);

	$yfin = $pdf->GetY();
	$xfin = $pdf->GetX();
	$pdf->RoundedRect($x - $margen, $y - $margen, $margen_med3, $yfin - $y + $margen, $radio, $style = '', $angle = '1234');



	//================================fin cuadro cliente========================================================= 

	//================================Inicio Guía de Remisión=========================================================
	//TOMADO DE VB SRI_Generar_Documento_PDF(
	/*switch ($datos[0]['TC']) {
		case 'FA':
			if (isset($datos[0]['Remision']) && $datos[0]['Remision'] > 0) {
				$y = $pdf->GetY() + $margen * 2;
				$x = $pdf->GetX();
				$pdf->SetXY($x, $y);
				$pdf->SetFont('Arial', '', 6);
				$pdf->SetWidths(array(270, 155, 250));
				$arr = array("Guía Remisión: " . $datos[0]['Serie_GR'] . "-" . sprintf("%09d", $datos[0]['Remision']), "Entrega: " . $datos[0]['Comercial']);
				$pdf->Row($arr, 10);

				$pdf->SetWidths(array(140, 130, 250));
				$arr = array("Pedido: " . $datos[0]['Pedido'], "Zona: " . ULCase($datos[0]['CiudadGRF']) . " - " . $datos[0]['Zona'], ((strlen($datos[0]['Lugar_Entrega']) > 1) ? "Lugar de Entrega: " . $datos[0]['Lugar_Entrega'] : "Lugar de Entrega: " . $datos[0]['DireccionC']));
				$pdf->Row($arr, 10);

				$yfin = $pdf->GetY();
				$xfin = $pdf->GetX();
				$pdf->RoundedRect($x - $margen, $y - $margen, $margen_med3, $yfin - $y + $margen, $radio, $style = '', $angle = '1234');
			}
			break;

		default:
			// code...
			break;
	}*/
	//================================fin Guía de Remisión=========================================================

	//================================cuadro detalle========================================================= 

	//Tomado de SRI_Generar_PDF_FA( Linea 1259
	/*if ($datos[0]['SP']) {
		$titulo1 = "Codigo Auxiliar";
		$titulo2 = "Codigo Unitario";
	} else {
		$titulo1 = "Codigo Unitario";
		$titulo2 = "Codigo Auxiliar";
	}*/
	//FIN Tomado de SRI_Generar_PDF_FA( Linea 1259

	//datos factura
	$y = $pdf->GetY() + $margen + 2;
	$x = $pdf->GetX() - $margen;
	$pdf->SetXY($x, $y);
	$margen_med3 = 540;

	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetWidths(array(55, 55, 35, 45, 134, 45, 45, 40, 40, 45));
	$arr = array("", "", "Cantidad Total", "Cantidad Bonif.", "D e s c r i p c i ó n", "Lote No. /Orden No", "Precio Unitario", "Valor Descuento", "Desc. %", "Valor Total");
	$pdf->Row($arr, 10, 1);
	$pdf->SetFont('Arial', '', 6);

	// print_r($datos);die();
	//$imp_mes = $datos[0]['Imp_Mes'];
	$detalle_descuento = array();
	foreach ($detalle as $key => $value) {

		if ($value['Tipo_Hab'] != '.') {
			if (count($detalle_descuento) > 0) {
				$exis = 0;
				foreach ($detalle_descuento as $key2 => $value2) {
					if ($value2['detalle'] == $value['Tipo_Hab']) {
						$detalle_descuento[$key2]['valor'] += $value['Total_Desc'];
						$exis = 1;
					}
				}
				if ($exis == 0) {
					$detalle_descuento[] = array('detalle' => $value['Tipo_Hab'], 'valor' => $value['Total_Desc']);
				}
			} else {
				$detalle_descuento[] = array('detalle' => $value['Tipo_Hab'], 'valor' => $value['Total_Desc']);
			}
		}
	}

	// print_r($detalle_descuento);die();
	foreach ($detalle as $key => $value) {
		//tomado de SRI_Generar_PDF_FA( Linea 1298
		if (strlen($value["Codigo_Barra"]) > 1) {
			$Cod_Bar = $value["Codigo_Barra"];
		} else {
			$Cod_Bar = (isset($value["Cod_Barras"])) ? $value["Cod_Barras"] : G_NINGUNO;
		}

		$Cod_Aux = (isset($value["Desc_Item"])) ? $value["Desc_Item"] : G_NINGUNO;
		$Total_Desc = $value["Total_Desc"] + $value["Total_Desc2"];

		if ($Total_Desc > 0 && $value["Total"] <> 0) {
			$Porc_Str = number_format((($Total_Desc * 100) / $value['Total']), 0, '.', '') . '%';
		} else {
			$Porc_Str = "";
		}

		$CODIGO1 = "";
		$CODIGO2 = "";
		/*if ($datos[0]['SP']) {
			if (strlen($Cod_Bar) > 1) {
				$CODIGO1 = $Cod_Bar;
			}
			$CODIGO2 = (strlen($Cod_Aux) > 1) ? $Cod_Aux : $value["Codigo"];

		} else {
			$CODIGO1 = (strlen($Cod_Aux) > 1) ? $Cod_Aux : $value["Codigo"];
			if (strlen($Cod_Bar) > 1) {
				$CODIGO2 = $Cod_Bar;
			}
		}*/
		//FIN tomado de  SRI_Generar_PDF_FA( Linea 1298
		$descto = '0';
		if ($value['Total'] > 0) {
			$descto = $Porc_Str;
		}
		$pdf->SetX($x);
		//INICIO PRODUCTO
		$Producto = $value["Producto"];
		/*if ($value["Codigo"] != "99.41" && $imp_mes) {
			$Producto = $Producto . " " . $value["Ticket"] . " " . $value["Mes"] . PHP_EOL;
		}*/

		/*if ($datos[0]['SP']) {
			if (CFechaLong($value["Fecha_Fab"]) != CFechaLong($value["Fecha_Exp"])) {
				$Producto .= "ELAB. " . $value["Fecha_Fab"] . ", VENC. " . $value["Fecha_Exp"] . " " . PHP_EOL;
			}

			if (strlen($value["Reg_Sanitario"]) > 1) {
				$Producto .= "Reg. Sanit. " . $value["Reg_Sanitario"] . PHP_EOL;
			}

			if (strlen($value["Modelo"]) > 1) {
				$Producto .= "Modelo: " . $value["Modelo"] . PHP_EOL;
			}

			if (strlen($value["Procedencia"]) > 1) {
				$Producto .= "Procedencia: " . $value["Procedencia"] . PHP_EOL;
			}
		}*/

		if (strlen($value["Serie_No"]) > 1) {
			$Producto .= "Serie No. " . $value["Serie_No"];
		}
		//FIN PRODUCTO

		if (strlen($value["Tipo_Hab"]) > 1) {
			if ($value['Total_Desc'] > 0) {
				$descto = '';
				$totaldes = '';
				$totalfac = number_format($value['Total'], 2, '.', '');

			} else {
				$totaldes = number_format($value['Total_Desc'], 2, '.', '') + number_format($value['Total_Desc2'], 2, '.', '');
				$totalfac = number_format($value['Total'], 2, '.', '');
			}
		} else {
			$totaldes = number_format($value['Total_Desc'], 2, '.', '') + number_format($value['Total_Desc2'], 2, '.', '');
			$totalfac = number_format($value['Total'], 2, '.', '');
		}
		$pdf->SetWidths(array(55, 55, 35, 45, 134, 45, 45, 40, 40, 45));
		$pdf->SetAligns(array("L", "L", "R", "R", "L", "L", "R", "R", "R", "R"));
		//$arr=array($arr1[$i]);
		$arr = array($CODIGO1, $CODIGO2, number_format($value['Cantidad'], 2, '.', ''), '', $Producto, '', number_format($value['Precio'], $_SESSION['INGRESO']['Dec_PVP'], '.', ''), $totaldes, $descto, $totalfac);
		$pdf->Row($arr, 10, 1);

		// if(strlen($value["Tipo_Hab"]) > 1)
		// {
		//   if($value['Total_Desc'] > 0){
		//   	$pdf->SetX(36);
		//   	$totaldes = number_format($value['Total_Desc'],2,'.','');
		//    $pdf->SetWidths(array(55,55,45,45,123,45,45,40,40,45));
		// 	$pdf->SetAligns(array("L","L","R","R","L","L","R","R","R","R"));
		// 	//$arr=array($arr1[$i]);
		// 	$arr=array('','','','',$value['Tipo_Hab'],'','','','',$totaldes);
		// 	$pdf->Row($arr,10,1); 
		// 	}
		// }        

	}

	if (count($detalle_descuento) > 0) {
		foreach ($detalle_descuento as $key => $value) {
			$pdf->SetX(36);
			$totaldes = number_format($value['valor'], 2, '.', '');
			$pdf->SetWidths(array(55, 55, 35, 45, 134, 45, 45, 40, 40, 45));
			$pdf->SetAligns(array("L", "L", "R", "R", "L", "L", "R", "R", "R", "R"));
			//$arr=array($arr1[$i]);
			$arr = array('', '', '', '', $value['detalle'], '', '', '', '', $totaldes);
			$pdf->Row($arr, 10, 1);
		}
	}



	$yfin = $pdf->GetY();
	$xfin = $pdf->GetX();
	// $pdf->RoundedRect($x-$margen, $y-$margen, $margen_med3, $yfin-$y+$margen, $radio, $style = '', $angle = '1234');

	//===================================================fin cuadrpo detalle======================================
	if ($yfin >= 612) {
		$pdf->AddPage();
	}
	// print_r($yfin);die();
	//====================================================cuadro datos adicionales======================
	$y = $pdf->GetY();
	$x = $pdf->GetX() - $margen;
	$pdf->SetXY($x, $y);
	$misma_ln = $y;

	//informacion adicional
	$pdf->SetFont('Arial', 'B', 6);
	//echo $pdf->GetY();
	//die();
	$y = $pdf->GetY();
	$pdf->SetXY(41 - $margen, $y + 5);
	$pdf->SetWidths(array(140, 40, 95, 46));
	// infofactura
	$arr = array("INFORMACIÓN ADICIONAL", "Fecha", "Detalle del pago", "Monto Abono");
	$pdf->Row($arr, 10, 1);

	$y = $pdf->GetY() - 5; //377
	$pdf->SetFont('Arial', '', 5.5);
	$pdf->SetX($x);
	$pdf->SetWidths(array(40));
	//verificamos si es una o varias etiquetas
	// datos adicionales
	$pdf->SetWidths(array(140));
	//print_r($educativo);


	if (isset($dato[0]['Telefono_RS']) && $datos[0]['Telefono_RS'] != '.' && $datos[0]['Telefono_RS'] != '') {
		$arr = array('Telefono: ' . $datos[0]['Telefono_RS']);
		$pdf->Row($arr, 10);
		$pdf->SetWidths(array(140));
	}



	///----------------------  infomrmacion adicional cuando punto de venta es diferente de 001 ----------------------
	// print_r($sucursal);die();


	/*if (!empty($sucursal) && count($sucursal) > 0 && $punto != '001' && $suc == '001' && strlen($sucursal[0]['RUC_Establecimiento']) == 13) {

		$arr = array('Punto Emision: ' . $datos[0]['Serie']);
		$pdf->Row($arr, 10);

		if ($sucursal[0]['Nombre_Establecimiento'] != '.' && $sucursal[0]['Nombre_Establecimiento'] != '') {
			$arr = array($sucursal[0]['Nombre_Establecimiento']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['RUC_Establecimiento'] != '.' && $sucursal[0]['RUC_Establecimiento'] != '') {
			$arr = array('Ruc punto Emision: ' . $sucursal[0]['RUC_Establecimiento']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['Direccion_Establecimiento'] != '.' && $sucursal[0]['Direccion_Establecimiento'] != '') {
			$arr = array('Direccion punto Emision: ' . $sucursal[0]['Direccion_Establecimiento']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['Telefono_Estab'] != '.' && $sucursal[0]['Telefono_Estab'] != '') {
			$arr = array('Telefono punto Emision: ' . $sucursal[0]['Telefono_Estab']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['Email_Establecimiento'] != '.' && $sucursal[0]['Email_Establecimiento'] != '') {
			$arr = array('Email punto Emision: ' . $sucursal[0]['Email_Establecimiento']);
			$pdf->Row($arr, 10);
		}

		if ($sucursal[0]['Cta_Establecimiento'] != '.' && $sucursal[0]['Cta_Establecimiento'] != '') {
			$arr = array('Cta Punto Emision: ' . $sucursal[0]['Cta_Establecimiento']);
			$pdf->Row($arr, 10);
		}


		if ($sucursal[0]['Placa_Vehiculo'] != '.' && $sucursal[0]['Placa_Vehiculo'] != '') {
			$arr = array('Placas: ' . $sucursal[0]['Placa_Vehiculo']);
			$pdf->Row($arr, 10);
		}


	}*/

	///----------------------  fin infomrmacion adicional cuando punto de venta es diferente de 001 ----------------------


	///----------------------  infomrmacion adicional cuan establecimiento es diferente de 001 ----------------------

	/*if (!empty($sucursal) && count($sucursal) > 0 && $suc != '001' && strlen($sucursal[0]['RUC_Establecimiento']) == 13) {
		$arr = array('Establecimiento: ' . $datos[0]['Serie']);
		$pdf->Row($arr, 10);

		if ($sucursal[0]['Nombre_Establecimiento'] != '.' && $sucursal[0]['Nombre_Establecimiento'] != '') {
			$arr = array($sucursal[0]['Nombre_Establecimiento']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['RUC_Establecimiento'] != '.' && $sucursal[0]['RUC_Establecimiento'] != '') {
			$arr = array('Ruc establecimiento: ' . $sucursal[0]['RUC_Establecimiento']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['Telefono_Estab'] != '.' && $sucursal[0]['Telefono_Estab'] != '') {
			$arr = array('Telefono establecimiento: ' . $sucursal[0]['Telefono_Estab']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['Email_Establecimiento'] != '.' && $sucursal[0]['Email_Establecimiento'] != '') {
			$arr = array('Email establecimiento: ' . $sucursal[0]['Email_Establecimiento']);
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['Cta_Establecimiento'] != '.' && $sucursal[0]['Cta_Establecimiento'] != '') {
			$arr = array('Cta_Establecimiento: ' . $sucursal[0]['Cta_Establecimiento']);
			$pdf->Row($arr, 10);
		}


		if ($sucursal[0]['Placa_Vehiculo'] != '.' && $sucursal[0]['Placa_Vehiculo'] != '') {
			$arr = array('Placas: ' . $sucursal[0]['Placa_Vehiculo']);
			$pdf->Row($arr, 10);
		}
	}*/

	//================================Inicio INFORMACION ADICIONAL=======================
	$info_adicional = "";
	if (isset($datos[0]['Cliente']) && $datos[0]['Razon_Social'] != $datos[0]['Cliente']) {
		$info_adicional .= 'Beneficiario: ' . $datos[0]['Cliente'] . PHP_EOL;

		if (isset($datos[0]['CI_RUC'])) {
			$info_adicional .= 'Codigo: ' . $datos[0]['CI_RUC'] . PHP_EOL;
		}
	}
	if (isset($datos[0]['Grupo']) && isset($datos[0]['Curso']) && isset($datos[0]['DireccionC']) && $datos[0]['Grupo'] != G_NINGUNO && $datos[0]['Curso'] != $datos[0]['DireccionC'] && $datos[0]['Imp_Mes']) {
		$info_adicional .= 'Grupo: ' . $datos[0]['Grupo'] . "-" . $datos[0]['Curso'] . PHP_EOL;
	}
	if (isset($datos[0]['EmailC']) && strlen($datos[0]['EmailC']) > 1 && strpos($datos[0]['EmailC'], "@") > 0) {
		$info_adicional .= 'Email: ' . $datos[0]['EmailC'] . PHP_EOL;
	}
	if (isset($datos[0]['EmailR']) && strlen($datos[0]['EmailR']) > 1 && strpos($datos[0]['EmailR'], "@") > 0 && strpos($datos[0]['EmailC'], $datos[0]['EmailR']) === false) {
		$info_adicional .= 'Email2: ' . $datos[0]['EmailR'] . PHP_EOL;
	}
	if (isset($datos[0]['Contacto']) && strlen($datos[0]['Contacto']) > 1) {
		$info_adicional .= 'Referencia: ' . $datos[0]['Contacto'] . PHP_EOL;
	}
	if (isset($datos[0]['TelefonoC']) && strlen($datos[0]['TelefonoC']) > 1) {
		$info_adicional .= 'Teléfono: ' . $datos[0]['TelefonoC'] . PHP_EOL;
	}
	/*if (strlen($datos[0]['Nota']) > 1) {
		$info_adicional .= 'Nota: ' . $datos[0]['Nota'] . PHP_EOL;
	}*/
	/*if (strlen($datos[0]['Observacion']) > 1) {
		$info_adicional .= 'Observacion: ' . $datos[0]['Observacion'] . PHP_EOL;
	}*/
	//================================Inicio INFORMACION DE ABONOS=======================
	$fechas_abonos = $detalle_abonos = $monto_abonos = "";
	// print_r($abonos);die();
	if (!empty($abonos) && $abonos && is_object($abonos)) {
		foreach ($abonos as $key => $value) {
			$fechas_abonos .= $value['Fecha']->format('Y-m-d') . PHP_EOL;
			$detalle_abonos .= $value['Banco'] . PHP_EOL;
			$monto_abonos .= $value['Abono'] . PHP_EOL;
		}
	}
	//================================Fin INFORMACION DE ABONOS=======================
	$info_ad = 140;
	$info_fe = 40;
	$info_de = 95;
	$info_monto = 46;

	$pdf->SetAligns(array("L", "L", "L", "R"));
	$pdf->SetWidths(array($info_ad, $info_fe, $info_de, $info_monto));
	$arr = array($info_adicional, $fechas_abonos, $detalle_abonos, $monto_abonos);
	$pdf->Row($arr, 10);
	//================================Fin INFORMACION ADICIONAL=======================

	///---------------------- fin infomrmacion adicional cuan establecimiento es diferente de 001 ----------------------

	//bordes de informacion adicional---------------------
	$yfin_adiconales = $pdf->GetY();
	$pdf->SetXY($x, $y + $margen);
	$pdf->Cell($info_ad, $yfin_adiconales - $y - $margen, '', '1', 1, 'Q');
	$pdf->SetXY($x + $info_ad, $y + $margen);
	$pdf->Cell($info_fe, $yfin_adiconales - $y - $margen, '', '1', 1, 'Q');

	$pdf->SetXY($x + $info_ad + $info_fe, $y + $margen);
	$pdf->Cell($info_de, $yfin_adiconales - $y - $margen, '', '1', 1, 'Q');
	$pdf->SetXY($x + $info_ad + $info_fe + $info_de, $y + $margen);
	$pdf->Cell($info_monto, $yfin_adiconales - $y - $margen, '', '1', 1, 'Q');
	//-----------------fin bordes de informacion adicional;

	$y_final_leyenda = $yfin_adiconales;

	// print_r($_SESSION['INGRESO']);die();
	//leyenda final
	$pdf->SetFont('Arial', '', 5);
	$pdf->SetXY($x, ($y_final_leyenda));
	$pdf->Cell(321, 46, '', '1', 1, 'Q');
	$pdf->SetXY($x, ($y_final_leyenda + 2));
	$pdf->SetWidths(array(319));
	$arr = array($_SESSION['INGRESO']['LeyendaFA']);
	$pdf->Row($arr, 8);
	//subtotales
	//depende del valor de coordenada 'y' del detalle
	$pdf->SetFont('Arial', '', 7);


	$yfin = $pdf->GetY();
	$xfin = $pdf->GetX();




	//========================================= cuadro totales======================================

	//	$arr1="<totalImpuesto";
	//$arr1=array("<totalImpuesto>","</totalImpuesto>");
	$sub_con_iva = 0;
	$sub_sin_iva = 0;
	foreach ($detalle as $key => $value) {

		// print_r($value);
		if (number_format($value['Total_IVA'], 2, '.', '') != '0.00') {
			// print_r($value['Total_IVA'].'-'.$value['Total']);
			$sub_con_iva += $value['Total'];
		} else {
			// print_r($value['Total_IVA'].'-'.$value['Total']);
			$sub_sin_iva += $value['Total'];

		}
	}
	//$imp = round($datos[0]['Porc_IVA'] * 100);
	$ba0 = $sub_sin_iva;
	$bai = $sub_con_iva;
	$vimp0 = 0;
	//$vimp1 = $datos[0]['IVA'];
	//$descu = $datos[0]['Descuento'] + $datos[0]['Descuento2'];
	// print_r($bai.'-'.$ba0);

	// die();
	$margen_med4 = 210;

	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, ($y - 10));
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y - 9));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));

	//$arr = array("SUBTOTAL " . $imp . "%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y - 9));
	$formateado = sprintf("%01.2f", $bai);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y - 10 + 11; //365
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(175));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL 0%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$arr = array(sprintf("%01.2f", $ba0));
	$formateado = sprintf("%01.2f", $ba0);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');
	//echo $formateado;
	//str_pad($ba0, 2, '0', STR_PAD_RIGHT);
	//exit();
	//$pdf->Row($arr,10);

	$y = $y + 11; //380
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("TOTAL DESCUENTO:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	//$formateado = sprintf("%01.2f", $descu);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //395
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL NO OBJETO DE IVA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = sprintf("%01.2f", "0.00");
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //410
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL EXENTO DE IVA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = sprintf("%01.2f", "0.00");
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //425
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL SIN IMPUESTOS:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	//$formateado = number_format($ba0 - $descu, 2, '.', '');
	//$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //440
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("ICE:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = sprintf("%01.2f", "0.00");
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //455
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	//$arr = array("IVA " . $imp . "%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	//$formateado = sprintf("%01.2f", $vimp1);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //470
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("IVA 0%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = sprintf("%01.2f", $vimp0);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //485
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	if ($_SESSION['INGRESO']['Servicio'] != 0) {
		$arr = array("SERVICIO " . $_SESSION['INGRESO']['Servicio'] . "%:");
	} else {
		$arr = array("SERVICIO:");
	}
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	//$formateado = number_format($datos[0]['Servicio'], 2, '.', '');
	//$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //500
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(165));
	$pdf->SetAligns(array("L"));
	$arr = array("VALOR TOTAL:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$pdf->SetAligns(array("R"));
	//$formateado = sprintf("%01.2f", $datos[0]['Total_MN']);
	//$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$yfin = $pdf->GetY();
	$xfin = $pdf->GetX();

	//========================================= fin cuadro totales======================================



	if ($imp1 == false || $imp1 == 0) {
		$pdf->Output('F', dirname(__DIR__, 2) . '/TEMP/' . $detalle[0]['Serie'] . '-' . generaCeros($detalle[0]['Factura'], 7) . '.pdf');

		return ['nombre'=> $detalle[0]['Serie'] . '-' . generaCeros($detalle[0]['Factura'], 7)];
	}
	if ($imp1 == 1) {
		$pdf->Output('F', dirname(__DIR__, 2) . '/TEMP/' . $datos[0]['Serie'] . '-' . generaCeros($datos[0]['Factura'], 7) . '.pdf');

		// $pdf->Output('TEMP/'.$nombre.'.pdf','F'); 
	}

}




function imprimirDocEle_fac_anterior($datos, $detalle, $educativo, $matri = false, $nombre = "", $formato = null, $nombre_archivo = null, $va = null, $imp1 = false, $abonos = false, $sucursal = array())
{
	// print_r($datos);die();
	$pdf = new PDF('P', 'pt', 'LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();
	$i = 0;
	$agente = '';
	$rimpe = '';
	if (isset($datos['Tipo_contribuyente']) && count($datos['Tipo_contribuyente']) > 0) {
		$agente = $datos['Tipo_contribuyente'][0]['Agente_Retencion'];
		if ($datos['Tipo_contribuyente'][0]['RIMPE_E'] == 1) {
			$rimpe = 'Regimen RIMPE Emprendedores';
		}
	}



	$x = 41;
	$pdf->SetXY($x, 20);
	//$pdf->SetWidths(array(250));

	$src = url_logo();
	if ($src !== '.') {
		$pdf->Image($src, 40, 22, 80, 40);
	} 

	// print_r($datos);die();
	$tam = 9;
	$pdf->cabeceraHorizontal(array(' '), 285, 30, 280, 115, 20, 5);
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetXY(285, 35);
	$pdf->SetWidths(array(42, 100));
	$arr = array('R.U.C.', $_SESSION['INGRESO']['RUC']);
	$pdf->Row($arr, 10);
	$pdf->SetXY(425, 35);
	$pdf->SetWidths(array(140));

	if ($rimpe != '') {
		$pdf->SetTextColor(225, 51, 51);
		$pdf->SetFont('Arial', '', 7);
		$arr = array($rimpe); //mio
		$pdf->Row($arr, 10);
	}
	// /-----------------------------------------------------------------------------
	if ($agente != '' && $agente != '.') {
		$pdf->SetTextColor(225, 51, 51);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetXY(285, 45);
		$pdf->SetWidths(array(240));
		$arr = array('Agente de Retención Resolución: ' . $agente);
		$pdf->Row($arr, 10);
	}
	// ----------------------------------------------------------------------------------


	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 12);
	$pdf->SetXY(285, 55);
	$pdf->SetWidths(array(140));
	// print_r($datos);die();
	if (isset($datos[0]['TC']) && $datos[0]['TC'] == 'LC') {
		$arr = array('Liquidacion compra No.');
	} else {
		$arr = array('Factura No.');
	}
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 11);
	$pdf->SetXY(425, 55);
	$pdf->SetTextColor(225, 51, 51);
	$pdf->SetWidths(array(140));
	$ptoEmi = substr($datos[0]['Serie'], 3, 6);
	$Serie = substr($datos[0]['Serie'], 0, 3);
	$arr = array($Serie . '-' . $ptoEmi . '-' . generaCeros($datos[0]['Factura'], $tam)); //mio
	$pdf->Row($arr, 10);
	$pdf->SetTextColor(0);

	// print_r($datos);
	//fecha y hora
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 66);
	$pdf->SetWidths(array(140));
	$arr = array('FECHA Y HORA DE AUTORIZACIÓN:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 66);
	$pdf->SetWidths(array(140));
	$arr = array($datos[0]['Fecha_Aut']->format('Y-m-d  h:m:s')); //mio
	$pdf->Row($arr, 10);
	//emisión
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 74);
	$pdf->SetWidths(array(140));
	$arr = array('EMISIÓN:');
	$pdf->Row($arr, 13);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 74);
	$pdf->SetWidths(array(140));
	$arr = array('NORMAL:');
	$pdf->Row($arr, 10);
	//ambiente

	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 85);
	$pdf->SetWidths(array(140));
	$arr = array('AMBIENTE: ');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 85);
	$pdf->SetWidths(array(140));

	$ambiente = substr($datos[0]['Autorizacion'], 23, 1);
	// print_r($datos[0]['Autorizacion']);die();
	//print_r($ambiente);die();


	if ($ambiente == 2) {
		$arr = array('PRODUCCION');

	} else if ($ambiente == 1) {
		$arr = array('PRUEBA');
	} else {
		$arr = array('');
	}
	$pdf->Row($arr, 10);

	//clave de acceso barcode y numero
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 97);
	$pdf->SetWidths(array(280));
	$arr = array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr, 10);
	if ($datos[0]['Clave_Acceso'] != $datos[0]['Autorizacion']) {
		$code = $datos[0]['Autorizacion'];
		//$pdf->SetXY(285,109);
		//$pdf->Code128(290,109,$code,260,20);

		//$pdf->Write(5,'C set: "'.$code.'"');
		$pdf->SetFont('Arial', '', 9);

		$pdf->SetXY(285, 109);
		$pdf->Code128(290, 109, $code, 260, 20);

		//$pdf->Write(5,'C set: "'.$code.'"');
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetXY(285, 130);
		$pdf->SetWidths(array(275));

		$pdf->Cell(10, 10, $code);
	} else if ($datos[0]['Clave_Acceso'] > 39) {
		$code = $datos[0]['Clave_Acceso'];
		$pdf->SetXY(285, 109);
		$pdf->Code128(290, 109, $code, 260, 20);

		//$pdf->Write(5,'C set: "'.$code.'"');
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetXY(285, 130);
		$pdf->SetWidths(array(275));
		//$arr=array($code);
		//$pdf->Row($arr,10);
		$pdf->Cell(10, 10, $code);
	}


	/******************/
	/******************/
	/******************/

	$posy = 75;
	if ($_SESSION['INGRESO']['Nombre_Comercial'] == $_SESSION['INGRESO']['Razon_Social']) {
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->SetXY($x, $posy);
		$pdf->SetWidths(array(240));
		$arr = array(mb_convert_encoding($_SESSION['INGRESO']['Razon_Social'], 'iso-8859-1', 'UTF-8')); //mio
		$pdf->Row($arr, 10);

	} else {
		//razon social
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->SetXY($x, $posy);
		$pdf->SetWidths(array(240));
		$arr = array(mb_convert_encoding($_SESSION['INGRESO']['Razon_Social'], 'iso-8859-1', 'UTF-8')); //mio
		$pdf->Row($arr, 8);

		//nombre comercial
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->SetXY($x, $posy + 12);
		$pdf->SetWidths(array(240));
		$arr = array(mb_convert_encoding($_SESSION['INGRESO']['Nombre_Comercial'], 'iso-8859-1', 'UTF-8')); //mio
		$pdf->Row($arr, 8);
		//print_r($datos);


	}
	//direccion matriz
	// print_r($pdf->GetY());die();

	// print_r($_SESSION['INGRESO']);die();
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY($x, $pdf->GetY() + 3);
	$pdf->SetWidths(array(140));
	$arr = array('Dirección Matríz');

	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY($x, $pdf->GetY());
	$pdf->SetWidths(array(240));
	$arr = array(mb_convert_encoding($_SESSION['INGRESO']['Direccion'], 'iso-8859-1', 'UTF-8')); //mio
	$pdf->Row($arr, 10);

	// print_r($_SESSION['INGRESO']);die();
	if (count($sucursal) > 0 && $sucursal[0]['Nombre_Establecimiento'] != '.' && $sucursal[0]['Nombre_Establecimiento'] != '') {
		$pdf->SetWidths(array(240));
		$arr = array('Direccion de establecimiento / Sucursal'); //mio
		$pdf->Row($arr, 10);
		$arr = array($sucursal[0]['Direccion_Establecimiento']); //mio
		$pdf->Row($arr, 10);
	}

	if (strlen($_SESSION['INGRESO']['Telefono1']) > 1) {
		$pdf->SetWidths(array(240));
		$arr = array($_SESSION['INGRESO']['Telefono1']); //mio
		$pdf->Row($arr, 10);
	}
	if (strlen($_SESSION['INGRESO']['Email']) > 1) {
		$pdf->SetWidths(array(240));
		$arr = array($_SESSION['INGRESO']['Email']); //mio
		$pdf->Row($arr, 10);
	}


	//contab
	$cont = $pdf->GetY();
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetXY($x, $pdf->GetY());
	$pdf->SetWidths(array(260));
	$arr = array('Obligado a llevar contabilidad:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetXY(165, $cont);
	$pdf->SetWidths(array(20));
	if ($_SESSION['INGRESO']['Obligado_Conta'] == 'NO') {
		$arr = array('NO');
	} else {
		$arr = array('SI'); //mio
	}
	$pdf->Row($arr, 10);
	$salto_linea = 10;
	$a = $pdf->GetY() - $posy + $salto_linea;
	// print_r($a);die();
	if ($a < 105 && $a > 45) {
		// $salto_linea = 20;
		$a = $pdf->GetY() - $posy + $salto_linea;
	} else {
		$salto_linea = 25;
		$a = $pdf->GetY() - $posy + $salto_linea;
	}
	$conti = $pdf->GetY() + $salto_linea + 10;
	$pdf->cabeceraHorizontal(array(' '), 40, 70, 242, $a, 20, 2);

	$su = $pdf->GetY() + $posy - 35;
	$pdf->SetXY(41, $su + 45);
	if (count($sucursal) > 0 && $sucursal[0]['Nombre_Establecimiento'] != '.' && $sucursal[0]['Nombre_Establecimiento'] != '') {
		if ($sucursal[0]['Telefono_Estab'] == '.' || $sucursal[0]['Telefono_Estab'] == '') {
			$sucursal[0]['Telefono_Estab'] = '';
		}
		if ($sucursal[0]['Email_Establecimiento'] == '.' || $sucursal[0]['Email_Establecimiento'] == '') {
			$sucursal[0]['Email_Establecimiento'] = '';
		}
		if ($sucursal[0]['Email_Establecimiento'] == '.' || $sucursal[0]['Email_Establecimiento'] == '') {
			$sucursal[0]['Email_Establecimiento'] = '';
		}

		$pdf->SetFont('Arial', 'B', 6);
		$pdf->SetY($conti);
		$su = $pdf->GetY() - 13;
		$pdf->SetXY(41, $su);
		$pdf->Cell(525, 40, '', 1);
		$pdf->SetXY(41, $su);
		$pdf->SetWidths(array(225, 100, 100, 100));
		$arr = array('Nombre de establecimiento: ', 'RUC de establecimiento: ', 'Telefono de Establecimiento: ', 'Email de establecimiento: ');
		if (strlen($sucursal[0]['RUC_Establecimiento']) == 1) {
			$arr = array('Nombre de punto de venta: ', '', 'Telefono de punto de venta ');
		}
		$pdf->Row($arr, 10);

		$pdf->SetWidths(array(225, 100, 100, 100));
		$arr = array($sucursal[0]['Nombre_Establecimiento'], $sucursal[0]['RUC_Establecimiento'], $sucursal[0]['Telefono_Estab'], $sucursal[0]['Email_Establecimiento']);
		if (strlen($sucursal[0]['RUC_Establecimiento']) == 1) {
			$arr = array($sucursal[0]['Nombre_Establecimiento'], '', $sucursal[0]['Telefono_Estab']);
		}
		$pdf->Row($arr, 10);

		$pdf->SetXY(41, $su + 43);
	}



	$cuadro2 = $pdf->GetY();
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetXY(41, $pdf->GetY());
	//para posicion automatica









	//datos de cliente
	$y = 35;
	$pdf->SetWidths(array(270, 185, 80));
	$arr = array('Razón social/nombres y apellidos:', '', 'Identificación:'); //mio
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 185, 80));
	$arr = array(mb_convert_encoding($datos[0]['Razon_Social'], 'ISO-8859-1', 'UTF-8'), '', $datos[0]['RUC_CI']); //mio
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 155, 100));

	//para direccion
	/*$arr1="<campoAdicional";
				if(is_array($arr1))
				{
					$adi='';
					for ($i=0;$i<count($arr1);$i++)
					{
						
						
						if($i==0)
						{
							$adi=$arr1[$i];
						}
						//echo $arr1[$i];
					}
				}
				else
				{
					$adi='';
				}*/
	// print_r($educativo);die();
	if (count($educativo) > 0) {
		$arr = array('Dirección: ' . $educativo[0]['Direccion'], 'Fecha emisión: ' . $datos[0]['Fecha']->format('Y-m-d'), 'Fecha pago: ' . $datos[0]['Fecha']->format('Y-m-d')); //mio
		$pdf->Row($arr, 10);
		$pdf->SetWidths(array(270, 155, 100));
	}
	if ('DOLAR' == 'DOLAR') {
		$mon = 'USD';
	} else {
		$mon = 'USD';
		//se busca otras monedas
	}
	//die();
	$arr = array(
		'FORMA DE PAGO: ' . $datos[0]['Tipo_Pago'],
		'MONTO: ' . number_format($datos[0]['Total_MN'], 2, '.', ',') . '  '
		,
		'Condición de venta: ' . $datos[0]['Fecha_C']->format('Y-m-d')
	);
	$pdf->Row($arr, 10);
	// print_r($datos);die();
	if ($datos[0]['Nota'] != '.' && $datos[0]['Nota'] != '') {
		$pdf->SetWidths(array(525));
		$arr = array('Nota: ' . $datos[0]['Nota']);
		$pdf->Row($arr, 10);
	}

	$y1 = $pdf->GetY();
	//echo $pdf->GetY().' '.$y;
	/*******************************************
	 ***********************************************
	 **************fin cabecera********************
	 ***********************************************
	 *********************************************/
	//die();
	$pdf->cabeceraHorizontal(array(' '), 40, $cuadro2, 525, ($pdf->GetY() - $cuadro2), 20, 5);
	//datos factura
	/******************/
	/******************/
	/******************/
	$pdf->SetFont('Arial', 'B', 6);
	$y = $y1 + 4;
	//$y=$y+188;//258
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(55, 55, 45, 45, 110, 45, 45, 40, 40, 45));
	$arr = array("Codigo Unitario", "Codigo Auxiliar", "Cantidad Total", "Cantidad Bonif.", "Descripción", "Lote", "Precio Unitario", "Valor Descuento", "Desc. %", "Valor Total");
	$pdf->Row($arr, 10, 1);
	$pdf->SetFont('Arial', '', 6);
	//imprimir detalles factura (hacer ciclo)
	//$pdf->SetXY(20, 313);
	//verificamos si es una o varias etiquetas
	//print_r($detalle);

	// print_r($datos);die();
	$imp_mes = $datos[0]['Imp_Mes'];
	foreach ($detalle as $key => $value) {
		// print_r($value);die();
		if ($imp_mes != 0) {
			$value['Producto'] = $value['Producto'] . ' ' . $value['Mes'] . '/' . $value['Ticket'];
		}
		$pdf->SetWidths(array(55, 55, 45, 45, 110, 45, 45, 40, 40, 45));
		$pdf->SetAligns(array("L", "L", "R", "R", "L", "L", "R", "R", "R", "R"));
		//$arr=array($arr1[$i]);
		$arr = array($value['Codigo'], '', $value['Cantidad'], '', $value['Producto'], '', sprintf("%01.2f", $value['Precio']), $value['Total_Desc'], '', number_format($value['Total'], 2, '.', ''));
		$pdf->Row($arr, 10, 1);
	}



	//informacion adicional
	$pdf->SetFont('Arial', 'B', 6);
	//echo $pdf->GetY();
	//die();
	$y = $pdf->GetY();
	$pdf->SetXY(41, $y + 5);
	$pdf->SetWidths(array(140, 40, 95, 46));
	// infofactura
	$arr = array("INFORMACIÓN ADICIONAL", "Fecha", "Deltalle del pago", "Monto Abono");
	$pdf->Row($arr, 10, 1);

	$y = $pdf->GetY() - 5; //377
	$pdf->SetFont('Arial', '', 7);
	//depende del valor de coordenada 'y' del detalle
	//informacion adicional
	$pdf->SetXY($x, $y + 5);
	$pdf->Cell(140, 60, '', '1', 1, 'Q');
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY($x, ($y + 8));
	$pdf->SetWidths(array(40));
	//verificamos si es una o varias etiquetas
// datos adicionales
	$pdf->SetWidths(array(140));
	//print_r($educativo);

	///revisa si los datos vienen de detalle matricula o de cliente
	if ($matri) {

		// print_r($educativo);die();
		if (isset($educativo[0]['Telefono_RS']) && $educativo[0]['Telefono_RS'] != '.' && $educativo[0]['Telefono_RS'] != '') {
			$arr = array('Telefono: ' . $educativo[0]['Telefono_RS']);
			$pdf->Row($arr, 10);
			$pdf->SetWidths(array(140));
		}
		if (isset($educativo[0]['Lugar_Trabajo_R']) && $educativo[0]['Lugar_Trabajo_R'] != '.' && $educativo[0]['Lugar_Trabajo_R'] != '') {
			$arr = array('Direccion: ' . $educativo[0]['Lugar_Trabajo_R']);
			$pdf->Row($arr, 10);
			$pdf->SetWidths(array(140));
		}
		if (isset($educativo[0]['Email_R']) && $educativo[0]['Email_R'] != '.' && $educativo[0]['Email_R'] != '') {
			$arr = array('Emial: ' . $educativo[0]['Email_R']);
			$pdf->Row($arr, 10);
			$pdf->SetWidths(array(140));
		}
		if (isset($educativo[0]['Email_R']) && $educativo[0]['Email_R'] != '.' && $educativo[0]['Email_R'] != '') {
			$arr = array('Emial: ' . $educativo[0]['Email_R']);
			$pdf->Row($arr, 10);
			$pdf->SetWidths(array(140));
		}

		if (isset($datos[0]['Observacion']) && $datos[0]['Observacion'] != '.' && $datos[0]['Observacion'] != '') {
			// print_r('expression');die();
			$arr = array('Observacion: ' . $datos[0]['Observacion']);
			$pdf->Row($arr, 10);
			$pdf->SetWidths(array(140));
		}
	} else {

		// print_r($educativo);die();
		if (isset($educativo[0]['Telefono']) && $educativo[0]['Telefono'] != '.' && $educativo[0]['Telefono'] != '') {
			$arr = array('Telefono: ' . $educativo[0]['Telefono']);
			$pdf->Row($arr, 10);
			$pdf->SetWidths(array(140));
		}
		if (isset($educativo[0]['DirecionT']) && $educativo[0]['DirecionT'] != '.' && $educativo[0]['DirecionT'] != '') {
			$arr = array('Direccion: ' . $educativo[0]['DirecionT']);
			$pdf->Row($arr, 10);
			$pdf->SetWidths(array(140));
		}
		if (isset($educativo[0]['Email']) && $educativo[0]['Email'] != '.' && $educativo[0]['Email'] != '') {
			$arr = array('Email: ' . $educativo[0]['Email']);
			$pdf->Row($arr, 10);
			$pdf->SetWidths(array(140));
		}
		if (isset($educativo[0]['EmailR']) && $educativo[0]['EmailR'] != '.' && $educativo[0]['EmailR'] != '') {
			$arr = array('Email: ' . $educativo[0]['EmailR']);
			$pdf->Row($arr, 10);
			$pdf->SetWidths(array(140));
		}

		// print_r($datos);die();
		if (isset($datos[0]['Observacion']) && $datos[0]['Observacion'] != '.' && $datos[0]['Observacion'] != '') {
			// print_r('expression');die();
			$arr = array('Observacion: ' . $datos[0]['Observacion']);
			$pdf->Row($arr, 10);
			$pdf->SetWidths(array(140));
		}

	}


	//fecha
	if ($abonos) {
		$pdf->SetFont('Arial', '', 5);
		$y = $y + 5;
		$pdf->SetXY(181, $y);
		$pdf->Cell(40, 60, '', 1, 1, 'Q');
		//detalle pago
		$pdf->SetXY(221, $y);
		$pdf->Cell(95, 60, '', 1, 1, 'Q');
		//monto abono
		$pdf->SetXY(316, $y);
		$pdf->Cell(46, 60, '', 1, 1, 'Q');
		$y = $y;

		// print_r($abonos);die();
		foreach ($abonos as $key => $value) {
			// print_r($abonos);die();
			$pdf->SetXY(181, $y);
			$pdf->Cell(40, 15, $value['Fecha']->format('Y-m-d'), 0, 1, 'Q');
			//detalle pago
			$pdf->SetXY(221, $y);
			if ($value['Banco'] != '.' && $value['Banco'] != '' && $value['Cheque'] != '.' && $value['Cheque'] != '') {
				$pdf->Cell(95, 15, $value['Banco'] . ' ' . $value['Cheque'], 0, 1, 'Q');
			}
			//monto abono
			$pdf->SetXY(316, $y);
			$pdf->Cell(46, 15, $value['Abono'], 0, 1, 'R');
			$y = $y + 6;
		}
	}
	//leyenda final
	$pdf->SetFont('Arial', '', 5);
	$pdf->SetXY($x, ($y + 65));
	$pdf->Cell(321, 46, '', '1', 1, 'Q');
	$pdf->SetXY($x, ($y + 68));
	$pdf->SetWidths(array(319));
	$arr = array($_SESSION['INGRESO']['LeyendaFA']);
	$pdf->Row($arr, 8);
	//subtotales
	//depende del valor de coordenada 'y' del detalle
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, ($y - 10));
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y - 9));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));

	//obtenemos valor


	//	$arr1="<totalImpuesto";
	//$arr1=array("<totalImpuesto>","</totalImpuesto>");
	$sub_con_iva = 0;
	$sub_sin_iva = 0;
	foreach ($detalle as $key => $value) {

		//print_r($value['Total_IVA']);
		if ($value['Total_IVA'] != .0000) {
			$sub_con_iva += $value['Total'];
		} else {
			$sub_sin_iva += $value['Total'];

		}
	}
	$imp = round($datos[0]['Porc_IVA'] * 100);
	$ba0 = $sub_sin_iva;
	$bai = $sub_con_iva;
	$vimp0 = 0;
	$vimp1 = $datos[0]['IVA'];
	$descu = $datos[0]['Descuento'] + $datos[0]['Descuento2'];
	//print_r($datos);

	//die();
	$arr = array("SUBTOTAL " . $imp . "%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y - 9));
	$formateado = sprintf("%01.2f", $bai);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y - 10 + 11; //365
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL 0%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$arr = array(sprintf("%01.2f", $ba0));
	$formateado = sprintf("%01.2f", $ba0);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');
	//echo $formateado;
	//str_pad($ba0, 2, '0', STR_PAD_RIGHT);
	//exit();
	//$pdf->Row($arr,10);

	$y = $y + 11; //380
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("TOTAL DESCUENTO:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = sprintf("%01.2f", $descu);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //395
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL NO OBJETO DE IVA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = sprintf("%01.2f", "0.00");
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //410
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL EXENTO DE IVA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = sprintf("%01.2f", "0.00");
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //425
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL SIN IMPUESTOS:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = sprintf("%01.2f", $ba0);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //440
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("ICE:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = sprintf("%01.2f", "0.00");
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //455
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("IVA " . $imp . "%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = sprintf("%01.2f", $vimp1);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //470
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("IVA 0%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = sprintf("%01.2f", $vimp0);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //485
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("PROPINA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = sprintf("%01.2f", "0.00");
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y + 11; //500
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(165));
	$pdf->SetAligns(array("L"));
	$arr = array("VALOR TOTAL:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$pdf->SetAligns(array("R"));
	$formateado = sprintf("%01.2f", $datos[0]['Total_MN']);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');
	//echo ' ddd '.$imp1;
	//die();*/
	if ($imp1 == false || $imp1 == 0) {
		$pdf->Output();
	}
	if ($imp1 == 1) {
		if(!file_exists(dirname(__DIR__, 2) . '/TEMP/'))
		{
			mkdir(dirname(__DIR__, 2) . '/TEMP/', 0777);
		}
		$pdf->Output('F', dirname(__DIR__, 2) . '/TEMP/' . $datos[0]['Serie'] . '-' . generaCeros($datos[0]['Factura'], 7) . '.pdf');

		// $pdf->Output('TEMP/'.$nombre.'.pdf','F'); 
	}
}


function imprimirDocEle_NC($datos, $detalle, $cliente, $matri = false, $nombre = "", $formato = null, $nombre_archivo = null, $va = null, $imp1 = false, $abonos = false, $sucursal = array())
{

	// print_r($_SESSION['INGRESO']);die();
	// print_r($sucursal);die();
	$pdf = new PDF('P', 'pt', 'LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();
	$i = 0;
	$agente = '';
	$rimpe = '';
	if (isset($datos['Tipo_contribuyente']) && count($datos['Tipo_contribuyente']) > 0) {
		// print_r($datos['Tipo_contribuyente']);die();
		$agente = $datos['Tipo_contribuyente']['@Agente'];
		if ($datos['Tipo_contribuyente']['@micro'] != '.') {
			$rimpe = $datos['Tipo_contribuyente']['@micro'];
		}
	}



	$x = 41;
	$pdf->SetXY($x, 20);
	//$pdf->SetWidths(array(250));

	$src = url_logo();
	if ($src !== '.') {
		$pdf->Image($src, 40, 22, 80, 40);
	} 

	$pdf->Ln(60);
	$x = $pdf->GetX();
	$y = $pdf->GetY();
	$salto_ln = 5;
	$radio = 5;

	//=======================================cuadro izquierda inicial=================
	$margen_med = 240;
	$margen = 5;
	if ($_SESSION['INGRESO']['Nombre_Comercial'] == $_SESSION['INGRESO']['Razon_Social']) {
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->SetWidths(array($margen_med));
		$arr = array($_SESSION['INGRESO']['Razon_Social']); //mio
		$pdf->Row($arr, 10);
		$pdf->ln(10);

	} else {
		//razon social
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->SetWidths(array($margen_med));
		$arr = array($_SESSION['INGRESO']['Razon_Social']); //mio
		$pdf->Row($arr, 8);
		$pdf->ln(8);
		// $pdf->Ln($salto_ln);		
		//nombre comercial
		$pdf->SetWidths(array($margen_med));
		$arr = array($_SESSION['INGRESO']['Nombre_Comercial']); //mio
		$pdf->Row($arr, 8);
		$pdf->ln(8);
	}

	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetWidths(array($margen_med));
	$arr = array('Dirección Matríz');
	$pdf->Row($arr, 10);
	$pdf->ln(10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetWidths(array($margen_med));
	$arr = array($_SESSION['INGRESO']['Direccion']);
	$pdf->Row($arr, 10);
	$pdf->ln(10);

	// print_r($datos[0]['Serie']);die();
	//sucursal si es diferente a 001
	$punto = substr($datos['Serie'], 3, 6);
	$suc = substr($datos['Serie'], 0, 3);

	// print_r($punto);die();
	if (isset($datos['Serie'])) {
		if ($suc != '001' && count($sucursal) > 0 && $sucursal[0]['Nombre_Establecimiento'] != '.' && $sucursal[0]['Nombre_Establecimiento'] != '') {
			$pdf->SetWidths(array($margen_med));
			$arr = array('Direccion de establecimiento / Sucursal'); //mio
			$pdf->Row($arr, 10);
			$arr = array($sucursal[0]['Direccion_Establecimiento']); //mio
			$pdf->Row($arr, 10);
		}
	}

	if (strlen($_SESSION['INGRESO']['Telefono1']) > 1) {
		$pdf->SetWidths(array($margen_med));
		$arr = array($_SESSION['INGRESO']['Telefono1']); //mio
		$pdf->Row($arr, 10);
		$pdf->ln(10);
	}
	if (strlen($_SESSION['INGRESO']['Email']) > 1) {
		$pdf->SetWidths(array($margen_med));
		$arr = array($_SESSION['INGRESO']['Email']); //mio
		$pdf->Row($arr, 10);
		$pdf->ln(10);
	}

	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetWidths(array(200, 40));
	$conta = 'NO';
	if ($_SESSION['INGRESO']['Obligado_Conta'] != 'NO') {
		$conta = 'SI'; //mio
	}
	$arr = array('Obligado a llevar contabilidad:', $conta);
	$pdf->Row($arr, 10);
	$yfin = $pdf->GetY();
	$xfin = $pdf->GetX();
	$pdf->RoundedRect($x - $margen, $y - $margen, $margen_med, $yfin - $y + $margen, $radio, $style = '', $angle = '1234');
	$pdf->ln(2);
	// print_r($yfin);die();
	$y_alineado = $yfin;
	//==================================fin cuadro izquierda inicial==================

	//==================================cuadro derecha inicial invisible========================

	$medida_1 = $pdf->GETY();

	$pdf->SetTextColor(255, 255, 255);
	$x = 200 + 100;
	$pdf->SetY($yfin + 20);
	$y = $pdf->GetY();
	$margen_med2 = 280;
	$col_1 = 142;
	$pdf->SetXY($x, $pdf->GetY());
	$col_2 = $margen_med2 - $col_1;

	$misma_ln = $pdf->GetY();
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetWidths(array($col_1));
	$arr = array('R.U.C. ' . $_SESSION['INGRESO']['RUC']);
	$pdf->Row($arr, 10);

	if ($rimpe == '') {
		$pdf->SetXY($x, $misma_ln);
		// $pdf->SetTextColor(225,51,51);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetWidths(array($col_1, $col_2));
		$pdf->SetFont('Arial', '', 7);
		$arr = array('', $rimpe); //mio
		$pdf->Row($arr, 10);
	}
	// /-----------------------------------------------------------------------------
	if ($agente != '' && $agente != '.') {
		$pdf->SetX($x);
		// $pdf->SetTextColor(225,51,51);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array($col_1 + $col_2));
		$arr = array('Agente de Retención Resolución: ' . $agente);
		$pdf->Row($arr, 10);
	}
	// ----------------------------------------------------------------------------------
	$pdf->SetX($x);
	$pdf->SetTextColor(255, 255, 255);
	// $pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial', '', 12);
	$pdf->SetWidths(array($col_1));
	// print_r($datos);die();

	$misma_ln = $pdf->GetY();
	$arr = array('NOTA DE CREDITO No.');
	$pdf->Row($arr, 10);

	$pdf->SetXY($x, $misma_ln);
	$pdf->SetFont('Arial', '', 11);
	// $pdf->SetTextColor(225,51,51);
	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetWidths(array($col_1, $col_2));
	$ptoEmi = substr($datos['Serie'], 3, 6);
	$Serie = substr($datos['Serie'], 0, 3);
	$arr = array('', $Serie . '-' . $ptoEmi . '-' . generaCeros($datos['Factura'], 9)); //mio
	$pdf->Row($arr, 10);
	$pdf->SetTextColor(0);
	// print_r($datos);
	// fecha y hora


	$pdf->SetX($x);
	$pdf->SetTextColor(255, 255, 255);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('FECHA Y HORA DE AUTORIZACIÓN:', $datos['Fecha_NC']->format('Y-m-d'));
	$pdf->Row($arr, 10);


	//emisión
	$pdf->SetX($x);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('EMISIÓN:', 'NORMAL');
	$pdf->Row($arr, 13);


	//ambiente
	$ambiente = substr($datos['Autorizacion_NC'], 23, 1);
	// print_r($datos[0]['Autorizacion']);die();
	//print_r($ambiente);die();
	$am = '';
	if ($ambiente == 2) {
		$am = 'PRODUCCION';

	} else if ($ambiente == 1) {
		$am = 'PRUEBA';
	}
	$pdf->SetX($x);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('AMBIENTE: ', $am);
	$pdf->Row($arr, 10);


	//clave de acceso barcode y numero
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetX($x);
	$pdf->SetWidths(array($margen_med));
	$arr = array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr, 10);
	if ($datos['ClaveAcceso_NC'] != $datos['Autorizacion']) {
		$code = $datos['ClaveAcceso_NC'];
		// $pdf->Code128($x,$pdf->GetY(),$code,$margen_med2-10,20);
		$pdf->ln(10);
		$pdf->SetY($pdf->GetY() + 20);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array(275));
		$pdf->SetX($x);
		// $arr=array($code);
		// $pdf->Row($arr,10);	
	} else if ($datos['ClaveAcceso_NC'] > 39) {
		$code = $datos['ClaveAcceso_NC'];
		// $pdf->Code128($x,$pdf->GetY(),$code,$margen_med2-10,20);
		$pdf->ln(10);
		$pdf->SetY($pdf->GetY() + 20);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array(275));
		$pdf->SetX($x);
		$arr = array($code);
		// $pdf->Row($arr,10);	
	}
	$pdf->ln(10);
	$yfin = $pdf->GetY();
	// $pdf->RoundedRect($x-$margen, $y-$margen, $margen_med2, $yfin-$y+$margen, 10, $style = '', $angle = '1234');		

	$medida_fin = $yfin;

	//==========================================
// 	if($medida_fin>$medida_1)
// 	{
	$medida_burbu = $medida_fin - $medida_1;
	$pos_burbuja_derecha_y = $y_alineado - $medida_burbu + $margen * 2;
	//   }else{

	//   	// print_r($pdf->PageNo());die();
//     $medida_burbu = $medida_1-$medida_fin;    
//     $pos_burbuja_derecha_y = $y_alineado+100+$margen*2;
// }

	// print_r($y_alineado*-1);die();
// print_r($pos_burbuja_derecha_y*-1);die();
	$x = 200 + 100;
	$pdf->SetAligns('L');
	$pdf->SetY($pos_burbuja_derecha_y);
	$y = $pdf->GetY();
	$margen_med2 = 280;
	$col_1 = 142;
	$pdf->SetXY($x, $pdf->GetY());
	$col_2 = $margen_med2 - $col_1;
	$pdf->SetTextColor(0, 0, 0);
	$misma_ln = $pdf->GetY();
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetWidths(array($col_1));
	$arr = array('R.U.C. ' . $_SESSION['INGRESO']['RUC']);
	$pdf->Row($arr, 10);

	if ($rimpe != '') {
		$pdf->SetXY($x, $misma_ln);
		$pdf->SetTextColor(225, 51, 51);
		$pdf->SetWidths(array($col_1, $col_2));
		$pdf->SetFont('Arial', '', 7);
		$arr = array('', $rimpe); //mio
		$pdf->Row($arr, 10);
	}
	// /-----------------------------------------------------------------------------
	if ($agente != '' && $agente != '.') {
		$pdf->SetX($x);
		$pdf->SetTextColor(225, 51, 51);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array($col_1 + $col_2));
		$arr = array('Agente de Retención Resolución: ' . $agente);
		$pdf->Row($arr, 10);
	}
	// ----------------------------------------------------------------------------------
	$pdf->SetX($x);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 12);
	$pdf->SetWidths(array($col_1));
	// print_r($datos);die();

	$misma_ln = $pdf->GetY();
	$arr = array('NOTA DE CREDITO No.');
	$pdf->Row($arr, 10);

	$pdf->SetXY($x, $misma_ln);
	$pdf->SetFont('Arial', '', 11);
	$pdf->SetTextColor(225, 51, 51);
	$pdf->SetWidths(array($col_1, $col_2));
	$ptoEmi = substr($datos['Serie_NC'], 3, 6);
	$Serie = substr($datos['Serie_NC'], 0, 3);
	$arr = array('', $Serie . '-' . $ptoEmi . '-' . generaCeros($datos['Nota_Credito'], 9)); //mio
	$pdf->Row($arr, 10);
	$pdf->SetTextColor(0);
	// print_r($datos);
	// fecha y hora


	$pdf->SetX($x);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('FECHA Y HORA DE AUTORIZACIÓN:', $datos['Fecha_NC']->format('Y-m-d'));
	$pdf->Row($arr, 10);


	//emisión
	$pdf->SetX($x);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('EMISIÓN:', 'NORMAL');
	$pdf->Row($arr, 13);


	//ambiente
	$ambiente = substr($datos['Autorizacion_NC'], 23, 1);
	// print_r($datos[0]['Autorizacion']);die();
	//print_r($ambiente);die();
	$am = '';
	if ($ambiente == 2) {
		$am = 'PRODUCCION';

	} else if ($ambiente == 1) {
		$am = 'PRUEBA';
	}
	$pdf->SetX($x);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetWidths(array($col_1, $col_2));
	$arr = array('AMBIENTE: ', $am);
	$pdf->Row($arr, 10);


	//clave de acceso barcode y numero
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetX($x);
	$pdf->SetWidths(array($margen_med));
	$arr = array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr, 10);
	if ($datos['ClaveAcceso_NC'] != $datos['Autorizacion_NC']) {
		$pdf->ln(10);
		$code = $datos['Autorizacion_NC'];
		$pdf->Code128($x, $pdf->GetY(), $code, $margen_med2 - 10, 20);

		$pdf->SetY($pdf->GetY() + 20);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array(275));
		$pdf->SetX($x);
		$arr = array($code);
		$pdf->Row($arr, 10);

	} else if ($datos['ClaveAcceso_NC'] > 39) {
		$pdf->ln(10);
		$code = $datos['ClaveAcceso_NC'];
		$pdf->Code128($x, $pdf->GetY(), $code, $margen_med2 - 10, 20);

		$pdf->SetY($pdf->GetY() + 20);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetWidths(array(275));
		$pdf->SetX($x);
		$arr = array($code);
		$pdf->Row($arr, 10);
	}
	$pdf->ln(10);
	$yfin = $pdf->GetY();
	$pdf->RoundedRect($x - $margen, $y - $margen, $margen_med2, $yfin - $y + $margen, 10, $style = '', $angle = '1234');

	$medida_fin = $pdf->GetY();


	//========================= fin cuadro derecha inicial========================







	//============================================cuadro cliente ==========================================
	$y = $pdf->GetY() + $margen * 2;
	$x = $pdf->GetX();
	$pdf->SetXY($x, $y);
	$margen_med3 = 540;


	$pdf->SetWidths(array(380, 165));
	$arr = array('Razón social/nombres y apellidos:', 'Identificación:'); //mio
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(380, 165));
	$arr = array(mb_convert_encoding($cliente[0]['Cliente'], 'ISO-8859-1', 'UTF-8'), $cliente[0]['CI_RUC']); //mio
	$pdf->Row($arr, 10);

	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 255));
	$arr = array('Dirección: ' . $cliente[0]['Direccion']); //mio
	$pdf->Row($arr, 10);
	$pdf->ln(10);
	$estaF = substr($datos['Serie'], 0, 3);
	$puntoF = substr($datos['Serie'], 3, 6);

	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(380, 250));
	$arr = array('Comprobante que se Modifica, Factura No.: ' . $estaF . '-' . $puntoF . '-' . generaCeros($datos['Factura']), 'Fecha pago: ' . $datos['Fecha_NC']->format('Y-m-d')); //mio
	$pdf->Row($arr, 10);

	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(380, 250));
	$arr = array('Razon de la modificacion: ' . $estaF . '-' . $puntoF . '-' . generaCeros($datos['Factura']), 'Fecha Emisión (Comprobante a Modificar):' . $datos['Fecha']->format('Y-m-d')); //mio
	$pdf->Row($arr, 10);

	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(520));
	$arr = array($datos['Nota']); //mio
	$pdf->Row($arr, 10);
	$pdf->ln(10);


	if ('DOLAR' == 'DOLAR') {
		$mon = 'USD';
	} else {
		$mon = 'USD';
		//se busca otras monedas
	}
	//die();

	$yfin = $pdf->GetY();
	$xfin = $pdf->GetX();
	$pdf->RoundedRect($x - $margen, $y - $margen, $margen_med3, $yfin - $y + $margen, $radio, $style = '', $angle = '1234');



	//================================fin cuadro cliente========================================================= 

	//================================cuadro detalle========================================================= 

	//datos factura
	$y = $pdf->GetY() + $margen + 2;
	$x = $pdf->GetX() - $margen;
	$pdf->SetXY($x, $y);
	$margen_med3 = 540;

	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetWidths(array(55, 315, 45, 40, 40, 45));
	$arr = array("Codigo", "Descripción", "Cantidad", "Precio Unitario", "Descuento", "Valor Total");
	$pdf->Row($arr, 10, 1);
	$pdf->SetFont('Arial', '', 6);



	// print_r($detalle_descuento);die();
	$totaldes = 0;
	$descto = 0;
	$totalfac = 0;
	foreach ($detalle as $key => $value) {
		// print_r($value);
		$descto = number_format($value['Descuento'], 2, '.', '');
		// if($value['Total']>0)
		// {
		// 	$descto = number_format((($value['Descuento']*100)/$value['Total']),2,'.','').'%';
		// }
		$pdf->SetX($x);
		// if($imp_mes!=0)
		//{
		// $value['Producto'] = $value['Producto'].' '.$value['Mes'].'/'.$value['Ticket'];
		// }

		//if(strlen($value["Tipo_Hab"]) > 1)
		// {
		//   if($value['Total_Desc'] > 0){
		//   	$descto = '';
		//   	$totaldes ='';
		//	$totalfac = number_format($value['Total'],2,'.','');

		//}else
		// {
		// 	$totaldes = number_format($value['Total_Desc'],2,'.','');

		$totalfac = number_format($value['Total'], 2, '.', '');

		$pdf->SetWidths(array(55, 315, 45, 40, 40, 45));
		$pdf->SetAligns(array("L", "L", "R", "R", "R", "R"));
		//$arr=array($arr1[$i]);
		$arr = array($value['Codigo_Inv'], $value['Producto'], number_format($value['Cantidad'], 2, '.', ''), number_format($value['Precio'], 6, '.', ''), $descto, $totalfac);
		$pdf->Row($arr, 10, 1);


	}


	$yfin = $pdf->GetY();
	$xfin = $pdf->GetX();
	// $pdf->RoundedRect($x-$margen, $y-$margen, $margen_med3, $yfin-$y+$margen, $radio, $style = '', $angle = '1234');

	//===================================================fin cuadrpo detalle======================================

	//====================================================cuadro datos adicionales======================
	$y = $pdf->GetY();
	$x = $pdf->GetX() - $margen;
	$pdf->SetXY($x, $y);
	$misma_ln = $y;

	//informacion adicional
	$pdf->SetFont('Arial', 'B', 6);
	//echo $pdf->GetY();
	//die();
	$y = $pdf->GetY();
	$pdf->SetXY(41 - $margen, $y + 5);
	$pdf->SetWidths(array(140, 40, 95, 46));
	// infofactura
	$arr = array("INFORMACIÓN ADICIONAL", "Fecha", "Deltalle del pago", "Monto Abono");
	$pdf->Row($arr, 10, 1);

	$y = $pdf->GetY() - 5; //377
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetXY($x, $y + 5);
	$pdf->SetFont('Arial', '', 5.5);
	$pdf->SetXY($x, ($y + 8));
	$pdf->SetWidths(array(40));
	//verificamos si es una o varias etiquetas
	// datos adicionales
	$pdf->SetWidths(array(140));
	//print_r($educativo);


	if (isset($dato[0]['Telefono_RS']) && $datos[0]['Telefono_RS'] != '.' && $datos[0]['Telefono_RS'] != '') {
		$arr = array('Telefono: ' . $datos[0]['Telefono_RS']);
		$pdf->Row($arr, 10);
		$pdf->ln(10);
		$pdf->SetWidths(array(140));
	}



	///----------------------  infomrmacion adicional cuando punto de venta es diferente de 001 ----------------------
	// print_r($sucursal);die();


	if (count($sucursal) > 0 && $punto != '001' && $suc == '001' && strlen($sucursal[0]['RUC_Establecimiento']) == 13) {

		$arr = array('Punto Emision: ' . $datos[0]['Serie']);
		$pdf->Row($arr, 10);
		$pdf->ln(10);

		if ($sucursal[0]['Nombre_Establecimiento'] != '.' && $sucursal[0]['Nombre_Establecimiento'] != '') {
			$arr = array($sucursal[0]['Nombre_Establecimiento']);
			$pdf->Row($arr, 10);
			$pdf->ln(10);
		}
		if ($sucursal[0]['RUC_Establecimiento'] != '.' && $sucursal[0]['RUC_Establecimiento'] != '') {
			$arr = array('Ruc punto Emision: ' . $sucursal[0]['RUC_Establecimiento']);
			$pdf->Row($arr, 10);
			$pdf->ln(10);
		}
		if ($sucursal[0]['Direccion_Establecimiento'] != '.' && $sucursal[0]['Direccion_Establecimiento'] != '') {
			$arr = array('Direccion punto Emision: ' . $sucursal[0]['Direccion_Establecimiento']);
			$pdf->Row($arr, 10);
			$pdf->ln(10);
		}
		if ($sucursal[0]['Telefono_Estab'] != '.' && $sucursal[0]['Telefono_Estab'] != '') {
			$arr = array('Telefono punto Emision: ' . $sucursal[0]['Telefono_Estab']);
			$pdf->Row($arr, 10);
			$pdf->ln(10);
		}
		if ($sucursal[0]['Email_Establecimiento'] != '.' && $sucursal[0]['Email_Establecimiento'] != '') {
			$arr = array('Email punto Emision: ' . $sucursal[0]['Email_Establecimiento']);
			$pdf->Row($arr, 10);
			$pdf->ln(10);
		}

		if ($sucursal[0]['Cta_Establecimiento'] != '.' && $sucursal[0]['Cta_Establecimiento'] != '') {
			$arr = array('Cta Punto Emision: ' . $sucursal[0]['Cta_Establecimiento']);
			$pdf->Row($arr, 10);
			$pdf->ln(10);
		}


		if ($sucursal[0]['Placa_Vehiculo'] != '.' && $sucursal[0]['Placa_Vehiculo'] != '') {
			$arr = array('Placas: ' . $sucursal[0]['Placa_Vehiculo']);
			$pdf->Row($arr, 10);
			$pdf->ln(10);
		}


	}

	///----------------------  fin infomrmacion adicional cuando punto de venta es diferente de 001 ----------------------


	///----------------------  infomrmacion adicional cuan establecimiento es diferente de 001 ----------------------

	if (count($sucursal) > 0 && $suc != '001' && strlen($sucursal[0]['RUC_Establecimiento']) == 13) {
		$arr = array('Establecimiento: ' . $datos[0]['Serie']);
		$pdf->Row($arr, 10);
		$pdf->ln(10);

		if ($sucursal[0]['Nombre_Establecimiento'] != '.' && $sucursal[0]['Nombre_Establecimiento'] != '') {
			$arr = array($sucursal[0]['Nombre_Establecimiento']);
			$pdf->Row($arr, 10);
			$pdf->ln(10);
		}
		if ($sucursal[0]['RUC_Establecimiento'] != '.' && $sucursal[0]['RUC_Establecimiento'] != '') {
			$arr = array('Ruc establecimiento: ' . $sucursal[0]['RUC_Establecimiento']);
			$pdf->Row($arr, 10);
			$pdf->ln(10);
		}
		if ($sucursal[0]['Telefono_Estab'] != '.' && $sucursal[0]['Telefono_Estab'] != '') {
			$arr = array('Telefono establecimiento: ' . $sucursal[0]['Telefono_Estab']);
			$pdf->Row($arr, 10);
			$pdf->ln(10);
		}
		if ($sucursal[0]['Email_Establecimiento'] != '.' && $sucursal[0]['Email_Establecimiento'] != '') {
			$arr = array('Email establecimiento: ' . $sucursal[0]['Email_Establecimiento']);
			$pdf->Row($arr, 10);
			$pdf->ln(10);
		}
		if ($sucursal[0]['Cta_Establecimiento'] != '.' && $sucursal[0]['Cta_Establecimiento'] != '') {
			$arr = array('Cta_Establecimiento: ' . $sucursal[0]['Cta_Establecimiento']);
			$pdf->Row($arr, 10);
			$pdf->ln(10);
		}


		if ($sucursal[0]['Placa_Vehiculo'] != '.' && $sucursal[0]['Placa_Vehiculo'] != '') {
			$arr = array('Placas: ' . $sucursal[0]['Placa_Vehiculo']);
			$pdf->Row($arr, 10);
			$pdf->ln(10);
		}


	}
	if (isset($datos[0]['Observacion']) && $datos[0]['Observacion'] != '.' && $datos[0]['Observacion'] != '') {
		// print_r('expression');die();
		$arr = array('Observacion: ' . $datos[0]['Observacion']);
		$pdf->Row($arr, 10);
		$pdf->ln(10);
		$pdf->SetWidths(array(140));
	}
	if (isset($cliente[0]['Email']) && $cliente[0]['Email'] != '.' && $cliente[0]['Email'] != '') {
		// print_r('expression');die();
		$arr = array('Email: ' . $cliente[0]['Email']);
		$pdf->Row($arr, 10);
		$pdf->ln(10);
		$pdf->SetWidths(array(140));
	}


	///---------------------- fin infomrmacion adicional cuan establecimiento es diferente de 001 ----------------------
	$yfin_adiconales = $pdf->GetY();
	$pdf->SetXY($x, $y + $margen);
	$pdf->Cell(140, $yfin_adiconales - $y - $margen, '', '1', 1, 'Q');
	$y_final_leyenda = $yfin_adiconales;



	//fecha
	if ($abonos) {
		$pdf->SetFont('Arial', '', 5);
		$pdf->SetXY(140 + $pdf->GetX() - $margen, $y + $margen);

		// print_r($abonos);die();
		foreach ($abonos as $key => $value) {
			$pdf->SetWidths(array(40, 95, 46));
			$arr = array($value['Fecha']->format('Y-m-d'), $value['Banco'] . ' ' . $value['Cheque'], $value['Abono']);
			$pdf->Row($arr, 10, 1);
			$pdf->SetX(140 + $pdf->GetX() - $margen);

			// // print_r($abonos);die();
			// $pdf->SetXY(181, $y);
			// $pdf->Cell(40,15,$value['Fecha']->format('Y-m-d'),0,1,'Q');
			// //detalle pago
			// $pdf->SetXY(221, $y);
			// if($value['Banco']!='.' && $value['Banco']!='' && $value['Cheque']!='.' && $value['Cheque']!='')
			// {
			// 	$pdf->Cell(95,15,$value['Banco'].' '.$value['Cheque'],0,1,'Q');
			// }
			// //monto abono
			// $pdf->SetXY(316, $y);
			// $pdf->Cell(46,15,$value['Abono'],0,1,'R');
			// $y =$y+6;
		}
		$y_fin_abonos = $pdf->GetY();
		if ($yfin_adiconales < $y_fin_abonos) {
			$y_final_leyenda = $y_fin_abonos;
		}
	}
	//leyenda final
	$pdf->SetFont('Arial', '', 5);
	$pdf->SetXY($x, ($y_final_leyenda));
	$pdf->Cell(321, 46, '', '1', 1, 'Q');
	$pdf->SetXY($x, ($y_final_leyenda + 2));
	$pdf->SetWidths(array(319));
	$arr = array($_SESSION['INGRESO']['LeyendaFA']);
	$pdf->Row($arr, 8);
	//subtotales
	//depende del valor de coordenada 'y' del detalle
	$pdf->SetFont('Arial', '', 7);


	$yfin = $pdf->GetY();
	$xfin = $pdf->GetX();




	//========================================= cuadro totales======================================

	//	$arr1="<totalImpuesto";
	//$arr1=array("<totalImpuesto>","</totalImpuesto>");
	$sub_con_iva = 0;
	$sub_sin_iva = 0;
	foreach ($detalle as $key => $value) {

		//print_r($value['Total_IVA']);
		if ($value['Total_IVA'] != .0000) {
			$sub_con_iva += $value['Total'];
		} else {
			$sub_sin_iva += $value['Total'];

		}
	}
	$imp = number_format(($datos['Porc_IVA'] * 100), 0, '.', '');
	$ba0 = $sub_sin_iva;
	$bai = $sub_con_iva;
	$vimp0 = 0;
	$vimp1 = $datos['IVA'];
	$descu = $datos['Descuento'];
	//print_r($datos);

	//die();
	$margen_med4 = 210;

	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, ($y - 10));
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y - 9));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));

	$arr = array("SUBTOTAL " . $imp . "%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y - 9));
	$formateado = sprintf("%01.2f", $bai);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$y = $y - 10 + 11; //365
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(175));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL 0%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$arr = array(sprintf("%01.2f", $ba0));
	$formateado = sprintf("%01.2f", $ba0);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');
	//echo $formateado;
	//str_pad($ba0, 2, '0', STR_PAD_RIGHT);
	//exit();
	//$pdf->Row($arr,10);

	$y = $y + 11; //380
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("TOTAL DESCUENTO:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = sprintf("%01.2f", $descu);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');


	$y = $y + 11; //425
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL SIN IMPUESTOS:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = sprintf("%01.2f", $ba0);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');


	$y = $y + 11; //455
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("IVA " . $imp . "%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$formateado = sprintf("%01.2f", $vimp1);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	// print_r($datos);die();

	$y = $y + 11; //500
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell($margen_med4, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(165));
	$pdf->SetAligns(array("L"));
	$arr = array("VALOR TOTAL:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(528, ($y + 1));
	$pdf->SetAligns(array("R"));
	$formateado = sprintf("%01.2f", $datos['Total_MN']);
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	$yfin = $pdf->GetY();
	$xfin = $pdf->GetX();

	//========================================= fin cuadro totales======================================





	if ($imp1 == false || $imp1 == 0) {
		$pdf->Output();
	}
	if ($imp1 == 1) {
		if(!file_exists(dirname(__DIR__, 2) . '/TEMP/'))
		{
			mkdir(dirname(__DIR__, 2) . '/TEMP/', 0777);
		}
		$pdf->Output('F', dirname(__DIR__, 2) . '/TEMP/' . $datos['Serie_NC'] . '-' . generaCeros($datos['Nota_Credito'], 7) . '.pdf');

		// $pdf->Output('TEMP/'.$nombre.'.pdf','F'); 
	}
}




function imprimirDocEle_ret($datos, $detalle, $nombre_archivo = null, $imp1 = false)
{
	// print_r($datos);die();
	$pdf = new PDF('P', 'pt', 'LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();
	$i = 0;
	$agente = '';
	$rimpe = '';
	// print_r($datos);die();
	if (isset($datos['Tipo_contribuyente']) && count($datos['Tipo_contribuyente']) > 0) {
		$agente = $datos['Tipo_contribuyente'][0]['Agente_Retencion'];
		if ($datos['Tipo_contribuyente'][0]['RIMPE_E'] == 1) {
			$rimpe = 'Regimen RIMPE Emprendedores';
		}

		// print_r($agente);die();
	}



	$x = 41;
	$pdf->SetXY($x, 20);
	//$pdf->SetWidths(array(250));

	$src = url_logo();
	if ($src !== '.') {
		$pdf->Image($src, 40, 22, 80, 40);
	} 

	// print_r($datos);die();
	$tam = 9;
	$pdf->cabeceraHorizontal(array(' '), 285, 30, 280, 115, 20, 5);
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetXY(285, 35);
	$pdf->SetWidths(array(42, 100));
	$arr = array('R.U.C.', $_SESSION['INGRESO']['RUC']);
	$pdf->Row($arr, 10);
	$pdf->SetXY(425, 35);
	$pdf->SetWidths(array(140));

	if ($rimpe != '') {
		$pdf->SetTextColor(225, 51, 51);
		$pdf->SetFont('Arial', '', 7);
		$arr = array($rimpe); //mio
		$pdf->Row($arr, 10);
	}
	// /-----------------------------------------------------------------------------
	if ($agente != '' && $agente != '.') {
		$pdf->SetTextColor(225, 51, 51);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetXY(285, 45);
		$pdf->SetWidths(array(240));
		$arr = array('Agente de Retención Resolución: ' . $agente);
		$pdf->Row($arr, 10);
	}
	// ----------------------------------------------------------------------------------
// print_r($datos);die();

	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial', '', 12);
	$pdf->SetXY(285, 55);
	$pdf->SetWidths(array(140));
	$arr = array('Retención No.');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 11);
	$pdf->SetXY(425, 55);
	$pdf->SetTextColor(225, 51, 51);
	$pdf->SetWidths(array(140));
	$ptoEmi = substr($datos[0]['Serie_Retencion'], 3, 6);
	$Serie = substr($datos[0]['Serie_Retencion'], 0, 3);
	$arr = array($Serie . '-' . $ptoEmi . '-' . generaCeros($datos[0]['SecRetencion'], $tam)); //mio
	$pdf->Row($arr, 10);
	$pdf->SetTextColor(0);

	// print_r($datos);
	//fecha y hora
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 66);
	$pdf->SetWidths(array(140));
	$arr = array('FECHA Y HORA DE AUTORIZACIÓN:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 66);
	$pdf->SetWidths(array(140));
	$arr = array($datos[0]['Fecha_Aut']->format('Y-m-d  h:m:s')); //mio
	$pdf->Row($arr, 10);
	//emisión
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 74);
	$pdf->SetWidths(array(140));
	$arr = array('EMISIÓN:');
	$pdf->Row($arr, 13);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 74);
	$pdf->SetWidths(array(140));
	$arr = array('NORMAL:');
	$pdf->Row($arr, 10);
	//ambiente

	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 85);
	$pdf->SetWidths(array(140));
	$arr = array('AMBIENTE: ');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 85);
	$pdf->SetWidths(array(140));
	// print_r($datos);die();
	$ambiente = substr($datos[0]['AutRetencion'], 23, 1);
	// print_r($datos[0]['Autorizacion']);die();
	//print_r($ambiente);die();


	if ($ambiente == 2) {
		$arr = array('PRODUCCION');

	} else if ($ambiente == 1) {
		$arr = array('PRUEBA');
	} else {
		$arr = array('');
	}
	$pdf->Row($arr, 10);


	//clave de acceso barcode y numero
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 97);
	$pdf->SetWidths(array(280));
	$arr = array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr, 10);
	if ($datos[0]['Clave_Acceso'] != $datos[0]['AutRetencion']) {
		$code = $datos[0]['AutRetencion'];
		$pdf->SetXY(285, 109);
		$pdf->Code128(290, 109, $code, 260, 20);

		//$pdf->Write(5,'C set: "'.$code.'"');
		$pdf->SetFont('Arial', '', 9);
		$pdf->SetXY(285, 130);
		$pdf->SetWidths(array(275));
		//$arr=array($code);
		//$pdf->Row($arr,10);
		$pdf->Cell(10, 10, $code);
	} else if ($datos[0]['Clave_Acceso'] > 39) {
		$code = $datos[0]['Clave_Acceso'];
		$pdf->SetXY(285, 109);
		$pdf->Code128(290, 109, $code, 260, 20);

		//$pdf->Write(5,'C set: "'.$code.'"');
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetXY(285, 130);
		$pdf->SetWidths(array(275));
		//$arr=array($code);
		//$pdf->Row($arr,10);
		$pdf->Cell(10, 10, $code);
	}

	/******************/
	/******************/
	/******************/

	$posy = 75;
	if ($_SESSION['INGRESO']['Nombre_Comercial'] == $_SESSION['INGRESO']['Razon_Social']) {
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->SetXY($x, $posy);
		$pdf->SetWidths(array(280));
		$arr = array(mb_convert_encoding($_SESSION['INGRESO']['Razon_Social'], 'ISO-8859-1', 'UTF-8')); //mio
		$pdf->Row($arr, 10);
		$pdf->ln(10);

	} else {
		//razon social
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->SetXY($x, $posy);
		$pdf->SetWidths(array(280));
		$arr = array(mb_convert_encoding($_SESSION['INGRESO']['Razon_Social'], 'ISO-8859-1', 'UTF-8')); //mio
		$pdf->Row($arr, 10);
		$pdf->ln(10);

		//nombre comercial
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->SetXY($x, $posy + 12);
		$pdf->SetWidths(array(280));
		$arr = array(mb_convert_encoding($_SESSION['INGRESO']['Nombre_Comercial'], 'ISO-8859-1', 'UTF-8')); //mio
		$pdf->Row($arr, 10);
		$pdf->ln(10);
		//print_r($datos);


	}

	//direccion matriz
	// print_r($pdf->GetY());die();

	// print_r($_SESSION['INGRESO']);die();
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY($x, $pdf->GetY());
	$pdf->SetWidths(array(140));
	$arr = array('Dirección Matríz');
	$pdf->Row($arr, 10);
	$pdf->ln(10);

	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY($x, $pdf->GetY());
	$pdf->SetWidths(array(280));
	$arr = array(mb_convert_encoding($_SESSION['INGRESO']['Direccion'], 'ISO-8859-1', 'UTF-8')); //mio
	$pdf->Row($arr, 10);
	$pdf->ln(10);

	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY($x, $pdf->GetY());
	$pdf->SetWidths(array(140));
	$arr = array('Dirección sucursal');
	$pdf->Row($arr, 10);
	$pdf->ln(10);

	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY($x, $pdf->GetY());
	$pdf->SetWidths(array(280));
	$arr = array(mb_convert_encoding($_SESSION['INGRESO']['Direccion'], 'ISO-8859-1', 'UTF-8')); //mio
	$pdf->Row($arr, 10);
	$pdf->ln(10);

	//contab
	$cont = $pdf->GetY();
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetXY($x, $pdf->GetY());
	$pdf->SetWidths(array(260));
	$arr = array('Obligado a llevar contabilidad:');
	$pdf->Row($arr, 10);
	$pdf->ln(10);

	$pdf->SetFont('Arial', '', 6);
	$pdf->SetXY(165, $cont);
	$pdf->SetWidths(array(20));
	if ($_SESSION['INGRESO']['Obligado_Conta'] == 'NO') {
		$arr = array('NO');
	} else {
		$arr = array('SI'); //mio
	}
	$pdf->Row($arr, 10);
	$salto_linea = 5;
	$a = $pdf->GetY() - $posy + $salto_linea;
	// print_r($a);die();
	if ($a < 105 && $a > 45) {
		$salto_linea = 20;
		$a = $pdf->GetY() - $posy + $salto_linea;
	} else {
		$salto_linea = 35;
		$a = $pdf->GetY() - $posy + $salto_linea;
	}
	$conti = $pdf->GetY() + $salto_linea;
	$pdf->cabeceraHorizontal(array(' '), 40, 70, 242, $a, 20, 2);

	$pdf->SetY($conti);
	$cuadro2 = $pdf->GetY();
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetXY(41, $pdf->GetY());
	//para posicion automatica
	$y = 35;
	$pdf->SetWidths(array(270, 185, 80));
	$arr = array('Razón social/nombres y apellidos:', '', 'Identificación:'); //mio
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 185, 80));
	$arr = array(mb_convert_encoding($datos[0]['Cliente'], 'ISO-8859-1', 'UTF-8'), '', $datos[0]['CI_RUC']); //mio
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 155, 100));


	// print_r($datos);die();
	$arr = array('Dirección: ' . $datos[0]['Direccion'], '', 'Periodo Fiscal: ' . $datos[0]['Fecha']->format('Y-m-d')); //mio
	$pdf->Row($arr, 10);
	$pdf->SetWidths(array(270, 155, 100));
	if ('DOLAR' == 'DOLAR') {
		$mon = 'USD';
	} else {
		$mon = 'USD';
		//se busca otras monedas
	}
	// print_r($datos);die();
	//die();
	$arr = array('Documento tipo Factura No: ' . $datos[0]['Establecimiento'] . $datos[0]['PuntoEmision'] . '-' . generaCeros($datos[0]['Secuencial'], 9), '', 'Fecha Emisión: ' . $datos[0]['Fecha']->format('Y-m-d'));
	$pdf->Row($arr, 10);
	$y1 = $pdf->GetY();
	$pdf->cabeceraHorizontal(array(' '), 40, $cuadro2, 525, ($pdf->GetY() - $cuadro2), 20, 5);



	//datos factura	
	$pdf->SetFont('Arial', 'B', 6);
	$y = $y1 + 4;
	//$y=$y+188;//258
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(60, 310, 40, 40, 40, 40, 40));
	$arr = array("Impuesto", "Descripcion", "Codigo Retencion", "Base Imponible", "Porcentaje Retenido", "Valor Retenido");
	$pdf->Row($arr, 10, 1);
	$pdf->SetFont('Arial', '', 6);

	// print_r($detalle);die();
	$valRet = 0;
	foreach ($detalle as $key => $value) {
		// print_r($value);die();

		$pdf->SetWidths(array(60, 310, 40, 40, 40, 40, 40));
		$pdf->SetAligns(array("L", "L", "R", "R", "R", "R", "R"));
		//$arr=array($arr1[$i]);
		$arr = array('', $value['Concepto'], $value['CodRet'], number_format($value['BaseImp'], 2, '.', ''), $value['Porcentaje'], number_format($value['ValRet'], 2, '.', ''));
		$pdf->Row($arr, 10, 1);
		$valRet += $value['ValRet'];
	}


	//informacion adicional
	$pdf->SetFont('Arial', 'B', 6);
	//echo $pdf->GetY();
	//die();
	$y = $pdf->GetY();
	$pdf->SetXY(41, $y + 3);
	$pdf->SetWidths(array(405));
	// infofactura
	$arr = array("INFORMACIÓN ADICIONAL");
	$pdf->Row($arr, 10, 1);

	// print_r($datos);die();
	$y = $pdf->GetY() - 5; //377
	$pdf->SetFont('Arial', '', 6);
	//depende del valor de coordenada 'y' del detalle
	//informacion adicional
	$pdf->SetXY($x, $y + 5);
	$pdf->Cell(405, 20, 'Telefono: ' . $datos[0]['Email'] . ', Tipo Comprobante:' . $datos[0]['TP'] . '-' . $datos[0]['Numero'] . ', Email:' . $datos[0]['Email'], '1', 1, 'Q');


	///revisa si los datos vienen de detalle matricula o de cliente


	//leyenda final
	$pdf->SetFont('Arial', '', 5);
	$pdf->SetXY($x, $pdf->GetY() + 2);
	$pdf->SetWidths(array(405));
	$arr = array($_SESSION['INGRESO']['LeyendaFA']);
	$pdf->Row($arr, 8, 1);
	//subtotales
	//depende del valor de coordenada 'y' del detalle


	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(451, $y - 5);
	$pdf->Cell(120, 20, '', '1', 1, 'Q');
	$pdf->SetXY(451, $y);
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));

	//obtenemos valor



	// print_r($datos);
	// print_r($detalle);
	// die();
	$arr = array("TOTAL RETENIDO:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(530, $y);
	$formateado = number_format($valRet, 2, '.', '');
	$pdf->Cell(37, 10, $formateado, 0, 0, 'R');

	if ($imp1 == false || $imp1 == 0) {
		$pdf->Output();
	}
	if ($imp1 == 1) {
		if(!file_exists(dirname(__DIR__, 2) . '/TEMP/'))
		{
			mkdir(dirname(__DIR__, 2) . '/TEMP/', 0777);
		}
		$pdf->Output('F', dirname(__DIR__, 2) . '/TEMP/RE_' . $datos[0]['Serie_Retencion'] . '-' . generaCeros($datos[0]['SecRetencion'], 9) . '.pdf');

		// $pdf->Output('TEMP/'.$nombre.'.pdf','F'); 
	}
}

/* imprimirDocElNC
   $stmt= variable con datos xml $id id del campo $formato formato del reporte aqui es pdf
   $nombre_archivo ruta y nombre del archivo xml generado $va 0 para saber si lee de una variable o 1 un archivo xml
   $imp1 paqra saber si se descarga o no null o 1 descarga 0 es para temp y adjuntar al correo*/
function imprimirDocElNC($stmt, $id = null, $formato = null, $nombre_archivo = null, $va = null, $imp1 = null)
{
	$pdf = new PDF('P', 'pt', 'LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();

	//logo
	$i = 0;
	if ($va == 1) {
		$autorizacion = simplexml_load_file($nombre_archivo);
	} else {
		$stmt = str_replace("ï»¿", "", $stmt);
		$autorizacion = simplexml_load_string($stmt);
	}
	$atrib = $autorizacion->attributes();

	//echo $autorizacion->fechaAutorizacion."<br>";
	//sustituimos
	$resultado = str_replace("<![CDATA[", "", $autorizacion->comprobante);
	$resultado = str_replace("]]>", "", $resultado);

	//echo etiqueta_xml($resultado,"<ruc>"); 
	//echo etiqueta_xml($resultado,"<razonSocial>"); 
	//echo $resultado;
	//$auto=simplexml_load_string($resultado);
	//echo ' ccc '.$auto->factura->infoTributaria;
	//die();
	$pdf->SetFont('Arial', 'B', 30);
	$x = 41;
	$pdf->SetXY($x, 20);
	//$pdf->SetWidths(array(250));
	$src = url_logo();
	if ($src !== '.') {
		$pdf->Image($src, 40, 22, 80, 40);
	} 

	/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
				{
					$logo=$_SESSION['INGRESO']['Logo_Tipo'];
				}
				else
				{
					$logo="diskcover";
				}
				$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,20,80,40,'','http://www.fpdf.org');*/
	//$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo.'.png');
	//$arr=array('NO TIENE LOGO');
	//$pdf->Row($arr,13);
	//panel
	$pdf->cabeceraHorizontal(array(' '), 285, 30, 280, 115, 20, 5);
	//texto 
	//ruc
	$pdf->SetFont('Arial', 'B', 13);
	$pdf->SetXY(285, 35);
	$pdf->SetWidths(array(70, 150));
	$arr = array('R.U.C.:     ', etiqueta_xml($resultado, "<ruc>"));
	$pdf->Row($arr, 10);
	//factura
	$pdf->SetXY(285, 47);
	$pdf->SetWidths(array(140));
	$arr = array('Nota de credito No.');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 11);
	$pdf->SetXY(425, 47);
	$pdf->SetWidths(array(140));
	$arr = array(
		etiqueta_xml($resultado, "<estab>") . '-' . etiqueta_xml($resultado, "<ptoEmi>") . '-' .
		etiqueta_xml($resultado, "<secuencial>")
	);
	$pdf->Row($arr, 10);
	//fecha y hora
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 61);
	$pdf->SetWidths(array(140));
	$arr = array('FECHA Y HORA DE AUTORIZACIÓN:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 61);
	$pdf->SetWidths(array(140));
	$arr = array($autorizacion->fechaAutorizacion);
	$pdf->Row($arr, 10);
	//emisión
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 73);
	$pdf->SetWidths(array(140));
	$arr = array('EMISIÓN:');
	$pdf->Row($arr, 13);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 73);
	$pdf->SetWidths(array(140));
	$arr = array('NORMAL:');
	$pdf->Row($arr, 10);
	//ambiente
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 85);
	$pdf->SetWidths(array(140));
	$arr = array('AMBIENTE: ');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 85);
	$pdf->SetWidths(array(140));
	if (etiqueta_xml($resultado, "<ambiente>") == 2) {
		$arr = array('PRODUCCIÓN');
	} else {
		$arr = array('PRUEBA');
	}
	$pdf->Row($arr, 10);

	//clave de acceso barcode y numero
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 97);
	$pdf->SetWidths(array(280));
	$arr = array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr, 10);
	/*$pdf->SetXY(410, 180);
				$pdf->SetWidths(array(275));
				$arr=array('000000000000000000000000000000000000000000000000');
				$pdf->Row($arr,13);*/
	//C set <claveAcceso>
	//$code=etiqueta_xml($resultado,"<claveAcceso>");
	$code = $atrib['numeroAutorizacion'];
	$pdf->SetXY(285, 109);
	$pdf->Code128(290, 109, $code, 260, 20);

	//$pdf->Write(5,'C set: "'.$code.'"');
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(285, 130);
	$pdf->SetWidths(array(275));
	$arr = array($code);
	$pdf->Row($arr, 10);
	/******************/
	/******************/
	/******************/
	$pdf->cabeceraHorizontal(array(' '), 40, 70, 242, 75, 20, 5);
	//razon social
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetXY($x, 75);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<razonSocial>"));
	$pdf->Row($arr, 10);
	//nombre comercial
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetXY($x, 87);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<nombreComercial"));
	$pdf->Row($arr, 10);
	//direccion matriz
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY($x, 97);
	$pdf->SetWidths(array(140));
	$arr = array('Dirección Matríz');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetXY($x, 107);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<dirMatriz>"));
	$pdf->Row($arr, 10);
	//direccion sucursal
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY($x, 117);
	$pdf->SetWidths(array(140));
	$arr = array('Dirección Sucursal');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetXY($x, 127);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<dirEstablecimiento>"));
	$pdf->Row($arr, 10);
	//contab
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetXY($x, 135);
	$pdf->SetWidths(array(260));
	$arr = array('Obligado a llevar contabilidad:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetXY(165, 135);
	$pdf->SetWidths(array(20));
	$arr = array(etiqueta_xml($resultado, "<obligadoContabilidad>"));
	$pdf->Row($arr, 10);
	//$pdf->AddPage();
	//datos cliente
	/******************/
	/******************/
	/**************110****/
	//$pdf->cabeceraHorizontal(array(' '),20,185,574,90,20,5);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetXY(41, 149);
	//para posicion automatica
	$y = 35;
	$pdf->SetWidths(array(270, 185, 80));
	$arr = array('Razón social/nombres y apellidos:', '', 'Identificación:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 185, 80));
	$arr = array(etiqueta_xml($resultado, "<razonSocialComprador>"), '', etiqueta_xml($resultado, "<identificacionComprador>"));
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 155, 100));
	//para direccion
	$arr1 = etiqueta_xml($resultado, "<campoAdicional");
	if (is_array($arr1)) {
		$adi = '';
		for ($i = 0; $i < count($arr1); $i++) {


			if ($i == 0) {
				$adi = $arr1[$i];
			}
			//echo $arr1[$i];
		}
	} else {
		$adi = '';
	}
	$arr = array(
		'Dirección: ' . $adi,
		'Fecha emisión: ' . etiqueta_xml($resultado, "<fechaEmision>"),
		'Fecha pago: ' . etiqueta_xml($resultado, "<fechaEmision>")
	);
	$pdf->Row($arr, 10);
	$pdf->SetWidths(array(270, 155, 100));
	if (etiqueta_xml($resultado, "<moneda") == 'DOLAR') {
		$mon = 'USD';
	} else {
		$mon = 'USD';
		//se busca otras monedas
	}
	//die();
	$arr = array(
		'Comprobante que se modifica, Factura No. ' . etiqueta_xml($resultado, "<numDocModificado>"),
		'MONTO: ' . $mon . '  ' . etiqueta_xml($resultado, "<importeTotal>")
		,
		'Fecha emisión (comprobante a modificar): ' . etiqueta_xml($resultado, "<fechaEmisionDocSustento>")
	);
	$pdf->Row($arr, 10);
	$y1 = $pdf->GetY();
	//echo $pdf->GetY().' '.$y;
	/*******************************************
	 ***********************************************
	 **************fin cabecera********************
	 ***********************************************
	 *********************************************/
	//die();
	$pdf->cabeceraHorizontal(array(' '), 40, 148, 525, ($pdf->GetY() - 148), 20, 5);
	//datos factura
	/******************/
	/******************/
	/******************/
	$pdf->SetFont('Arial', 'B', 6);
	$y = $y1 + 4;
	//$y=$y+188;//258
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(65, 215, 65, 65, 60, 55));
	$arr = array(
		"Codigo Unitario",
		"Descripción",
		"Cantidad Total",
		"Precio Unitario",
		"Valor Descuento",
		"Valor Total"
	);
	$pdf->Row($arr, 10, 1);
	$pdf->SetFont('Arial', '', 6);
	//imprimir detalles factura (hacer ciclo)
	//$pdf->SetXY(20, 313);
	//verificamos si es una o varias etiquetas
	$arr1 = etiqueta_xml($resultado, "<codigoInterno");
	if (is_array($arr1)) {
		/*$arr2=etiqueta_xml($resultado,"<codigoAuxiliar");
								if($arr2=='')
								{
									$arr2=array();
									//llenamos array vacio
									for ($i=0;$i<count($arr1);$i++)
									{
										$arr2[$i]='';
									}
								}*/
		$arr3 = etiqueta_xml($resultado, "<cantidad");
		if ($arr3 == '') {
			$arr3 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr3[$i] = '';
			}
		}
		//$arr4='';
		$arr5 = etiqueta_xml($resultado, "<descripcion");
		if ($arr5 == '') {
			$arr5 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr5[$i] = '';
			}
		}
		//$arr6='';
		$arr7 = etiqueta_xml($resultado, "<precioUnitario");
		if ($arr7 == '') {
			$arr7 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr7[$i] = '';
			}
		}
		$arr8 = etiqueta_xml($resultado, "<descuento");
		if ($arr8 == '') {
			$arr8 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr8[$i] = '';
			}
		}
		//$arr9='';
		$arr10 = etiqueta_xml($resultado, "<precioTotalSinImpuesto");
		if ($arr10 == '') {
			$arr10 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr10[$i] = '';
			}
		}
		for ($i = 0; $i < count($arr1); $i++) {
			$adi = '';
			$pdf->SetWidths(array(65, 215, 65, 65, 60, 55));
			$pdf->SetAligns(array("L", "L", "R", "R", "R", "R"));
			//$arr=array($arr1[$i]);
			$arr = array(
				$arr1[$i],
				$arr3[$i],
				$arr5[$i],
				$arr7[$i],
				$arr8[$i],
				$arr10[$i]
			);
			$pdf->Row($arr, 10, 1);
		}
	} else {
		$pdf->SetWidths(array(65, 215, 65, 65, 60, 55));
		$pdf->SetAligns(array("L", "L", "R", "R", "R", "R"));
		$arr = array(
			etiqueta_xml($resultado, "<codigoInterno>"),
			etiqueta_xml($resultado, "<descripcion>"),
			etiqueta_xml($resultado, "<cantidad>"),
			etiqueta_xml($resultado, "<precioUnitario>"),
			etiqueta_xml($resultado, "<descuento>"),
			etiqueta_xml($resultado, "<precioTotalSinImpuesto>")
		);
		$pdf->Row($arr, 10, 1);
	}
	//informacion adicional
	$pdf->SetFont('Arial', 'B', 6);
	//echo $pdf->GetY();
	//die();
	$y = $pdf->GetY();
	$pdf->SetXY(41, $y + 5);
	$pdf->SetWidths(array(140, 40, 95, 46));
	$arr = array("INFORMACIÓN ADICIONAL", "Fecha", "Deltalle del pago", "Monto Abono");
	$pdf->Row($arr, 10, 1);

	$y = $pdf->GetY() - 5; //377
	$pdf->SetFont('Arial', '', 7);
	//depende del valor de coordenada 'y' del detalle
	//informacion adicional
	$pdf->SetXY($x, $y + 5);
	$pdf->Cell(140, 60, '', '1', 1, 'Q');
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY($x, ($y + 8));
	$pdf->SetWidths(array(40));
	//verificamos si es una o varias etiquetas
	$arr1 = etiqueta_xml($resultado, "<campoAdicional");
	if (is_array($arr1)) {
		for ($i = 0; $i < count($arr1); $i++) {
			$adi = '';
			if ($i == 1) {
				$adi = 'Telefono: ';
			}
			if ($i == 2) {
				$adi = 'Email: ';
			}
			if ($i != 0) {
				$pdf->SetWidths(array(140));
				$arr = array($adi . $arr1[$i]);
				$pdf->Row($arr, 10);
			}
		}
	} else {
		$pdf->SetWidths(array(140));
		$pdf->Row($arr, 10);
	}
	//$arr=array(etiqueta_xml($resultado,"<campoAdicional"));
	/*die();
				$pdf->SetWidths(array(40));
				$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
				$pdf->Row($arr,13);
				$pdf->SetWidths(array(40));
				$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
				$pdf->Row($arr,13);
				$pdf->SetWidths(array(40));
				$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
				$pdf->Row($arr,13);*/
	//fecha
	$pdf->SetXY(181, $y + 5);
	$pdf->Cell(40, 60, '', '1', 1, 'Q');
	//detalle pago
	$pdf->SetXY(221, $y + 5);
	$pdf->Cell(95, 60, '', '1', 1, 'Q');
	//monto abono
	$pdf->SetXY(316, $y + 5);
	$pdf->Cell(46, 60, '', '1', 1, 'Q');

	//leyenda final
	$pdf->SetFont('Arial', '', 5);
	$pdf->SetXY($x, ($y + 65));
	$pdf->Cell(321, 46, '', '1', 1, 'Q');
	$pdf->SetXY($x, ($y + 68));
	$pdf->SetWidths(array(319));
	$arr = array('Para consultas, requerimientos o reclamos puede contactarse a nuestro Centro de Atención al Cliente Teléfono: 02-6052430, o escriba al correo
prisma_net@hotmail.es; para Transferencia o Depósitos hacer en El Banco Pichincha: Cta. Ahr. 4245946100 a Nombre de Walter Vaca Prieto/Cta. Cte
3422225804, a Nombre de PRISMANET PROFESIONAL S.A.'
	);
	$pdf->Row($arr, 8);
	//subtotales
	//depende del valor de coordenada 'y' del detalle
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, ($y - 10));
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y - 9));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	//obtenemos valor
	//$arr1=etiqueta_xml($resultado,"<totalImpuesto");
	$arr1 = porcion_xml($resultado, "<totalImpuesto>", "</totalImpuesto>");
	$imp = 0;
	$ba0 = 0;
	$bai = 0;
	$vimp0 = 0;
	$vimp1 = 0;
	if (is_array($arr1)) {
		for ($i = 0; $i < count($arr1); $i++) {
			//echo $arr1[$i].'<br>';
			$arr2 = etiqueta_xml($arr1[$i], "<tarifa");
			if (is_array($arr2)) {
				echo 'array';
				/*for ($j=0;$j<count($arr2);$j++)
																{
																	echo $arr2[$j].' ggg <br>';
																}*/
			} else {
				//echo $arr2.' fff <br>';
				if ($i == 1) {
					$imp = $arr2;
				}
			}
			$arr3 = etiqueta_xml($arr1[$i], "<baseImponible");
			if (is_array($arr3)) {
				/*for ($j=0;$j<count($arr2);$j++)
																{
																	echo $arr2[$j].' ggg <br>';
																}*/
			} else {
				//echo $arr3.' fff <br>';
				if ($i == 0) {
					$ba0 = $arr3;
				}
				if ($i == 1) {
					$bai = $arr3;
				}
			}
			$arr4 = etiqueta_xml($arr1[$i], "<valor");
			if (is_array($arr3)) {
				/*for ($j=0;$j<count($arr2);$j++)
																{
																	echo $arr2[$j].' ggg <br>';
																}*/
			} else {
				//echo $arr4.' fff <br>';
				if ($i == 0) {
					$vimp0 = $arr4;
				}
				if ($i == 1) {
					$vimp1 = $arr4;
				}
			}
		}
	}
	//die();
	$arr = array("SUBTOTAL " . $imp . "%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y - 9));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));

	$arr = array($bai);
	$pdf->Row($arr, 10);

	$y = $y - 10 + 11; //365
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL 0%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array($ba0);
	$pdf->Row($arr, 10);

	$y = $y + 11; //380
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("TOTAL DESCUENTO:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array(etiqueta_xml($resultado, "<totalDescuento>"));
	$pdf->Row($arr, 10);

	$y = $y + 11; //395
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL NO OBJETO DE IVA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array("000.00");
	$pdf->Row($arr, 10);

	$y = $y + 11; //410
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL EXENTO DE IVA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array("000.00");
	$pdf->Row($arr, 10);

	$y = $y + 11; //425
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL SIN IMPUESTOS:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array($ba0);
	$pdf->Row($arr, 10);

	$y = $y + 11; //440
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("ICE:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array("000.00");
	$pdf->Row($arr, 10);

	$y = $y + 11; //455
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("IVA " . $imp . "%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array($vimp1);
	$pdf->Row($arr, 10);

	$y = $y + 11; //470
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("IVA 0%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array($vimp0);
	$pdf->Row($arr, 10);

	$y = $y + 11; //485
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("PROPINA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array(etiqueta_xml($resultado, "<propina>"));
	$pdf->Row($arr, 10);

	$y = $y + 11; //500
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("VALOR TOTAL:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array(etiqueta_xml($resultado, "<valorModificacion>"));
	$pdf->Row($arr, 10);
	//echo ' ddd '.$imp1;
	//die();
	if ($imp1 == null or $imp1 == 1) {
		$pdf->Output();
	}
	if ($imp1 == 0) {
		$pdf->Output('TEMP/' . $id . '.pdf', 'F');
	}
}
/* imprimirDocElRE
   $stmt= variable con datos xml $id id del campo $formato formato del reporte aqui es pdf
   $nombre_archivo ruta y nombre del archivo xml generado $va 0 para saber si lee de una variable o 1 un archivo xml
   $imp1 paqra saber si se descarga o no null o 1 descarga 0 es para temp y adjuntar al correo*/
function imprimirDocElRE($stmt, $id = null, $formato = null, $nombre_archivo = null, $va = null, $imp1 = null)
{
	$pdf = new PDF('P', 'pt', 'LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();

	//logo
	$i = 0;
	if ($va == 1) {
		$autorizacion = simplexml_load_file($nombre_archivo);
	} else {
		$stmt = str_replace("ï»¿", "", $stmt);
		$autorizacion = simplexml_load_string($stmt);
	}
	$atrib = $autorizacion->attributes();

	//echo $autorizacion->fechaAutorizacion."<br>";
	//sustituimos
	$resultado = str_replace("<![CDATA[", "", $autorizacion->comprobante);
	$resultado = str_replace("]]>", "", $resultado);

	//echo etiqueta_xml($resultado,"<ruc>"); 
	//echo etiqueta_xml($resultado,"<razonSocial>"); 
	//echo $resultado;
	//$auto=simplexml_load_string($resultado);
	//echo ' ccc '.$auto->factura->infoTributaria;
	//die();
	$pdf->SetFont('Arial', 'B', 30);
	$x = 41;
	$pdf->SetXY($x, 20);
	//$pdf->SetWidths(array(250));
	$src = url_logo();
	if ($src !== '.') {
		$pdf->Image($src, 40, 22, 80, 40);
	} 
	/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
				{
					$logo=$_SESSION['INGRESO']['Logo_Tipo'];
				}
				else
				{
					$logo="diskcover";
				}
				$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,20,80,40,'','http://www.fpdf.org');*/
	//$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo.'.png');
	//$arr=array('NO TIENE LOGO');
	//$pdf->Row($arr,13);
	//panel
	$pdf->cabeceraHorizontal(array(' '), 285, 30, 280, 115, 20, 5);
	//texto 
	//ruc
	$pdf->SetFont('Arial', 'B', 13);
	$pdf->SetXY(285, 35);
	$pdf->SetWidths(array(70, 150));
	$arr = array('R.U.C.:     ', etiqueta_xml($resultado, "<ruc>"));
	$pdf->Row($arr, 12);
	//retencion
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetXY(285, 47);
	$pdf->SetWidths(array(180));
	$arr = array('COMPROBANTE DE RETENCION No.');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 9);
	$pdf->SetXY(460, 47);
	$pdf->SetWidths(array(140));
	$arr = array(
		etiqueta_xml($resultado, "<estab>") . '-' . etiqueta_xml($resultado, "<ptoEmi>") . '-' .
		etiqueta_xml($resultado, "<secuencial>")
	);
	$pdf->Row($arr, 10);
	//fecha y hora
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 58);
	$pdf->SetWidths(array(140));
	$arr = array('FECHA Y HORA DE AUTORIZACIÓN:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 58);
	$pdf->SetWidths(array(140));
	$arr = array($autorizacion->fechaAutorizacion);
	$pdf->Row($arr, 10);
	//emisión
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 73);
	$pdf->SetWidths(array(140));
	$arr = array('EMISIÓN:');
	$pdf->Row($arr, 13);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 73);
	$pdf->SetWidths(array(140));
	$arr = array('NORMAL:');
	$pdf->Row($arr, 10);
	//ambiente
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 85);
	$pdf->SetWidths(array(140));
	$arr = array('AMBIENTE: ');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 85);
	$pdf->SetWidths(array(140));
	if (etiqueta_xml($resultado, "<ambiente>") == 2) {
		$arr = array('PRODUCCIÓN');
	} else {
		$arr = array('PRUEBA');
	}
	$pdf->Row($arr, 10);

	//clave de acceso barcode y numero
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 97);
	$pdf->SetWidths(array(280));
	$arr = array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr, 10);
	/*$pdf->SetXY(410, 180);
				$pdf->SetWidths(array(275));
				$arr=array('000000000000000000000000000000000000000000000000');
				$pdf->Row($arr,13);*/
	//C set <claveAcceso>
	//$code=etiqueta_xml($resultado,"<claveAcceso>");
	$code = $atrib['numeroAutorizacion'];
	$pdf->SetXY(285, 109);
	$pdf->Code128(290, 109, $code, 260, 20);

	//$pdf->Write(5,'C set: "'.$code.'"');
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(285, 130);
	$pdf->SetWidths(array(275));
	$arr = array($code);
	$pdf->Row($arr, 10);
	/******************/
	/******************/
	/******************/
	$pdf->cabeceraHorizontal(array(' '), 40, 70, 242, 75, 20, 5);
	//razon social
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetXY($x, 75);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<razonSocial>"));
	$pdf->Row($arr, 10);
	//nombre comercial
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetXY($x, 87);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<nombreComercial"));
	$pdf->Row($arr, 10);
	//direccion matriz
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY($x, 97);
	$pdf->SetWidths(array(140));
	$arr = array('Dirección Matríz');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetXY($x, 107);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<dirMatriz>"));
	$pdf->Row($arr, 10);
	//direccion sucursal
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY($x, 117);
	$pdf->SetWidths(array(140));
	$arr = array('Dirección Sucursal');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetXY($x, 127);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<dirEstablecimiento>"));
	$pdf->Row($arr, 10);
	//contab
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetXY($x, 135);
	$pdf->SetWidths(array(260));
	$arr = array('Obligado a llevar contabilidad:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetXY(165, 135);
	$pdf->SetWidths(array(20));
	$arr = array(etiqueta_xml($resultado, "<obligadoContabilidad>"));
	$pdf->Row($arr, 10);
	//$pdf->AddPage();
	//datos cliente
	/******************/
	/******************/
	/**************110****/
	//$pdf->cabeceraHorizontal(array(' '),20,185,574,90,20,5);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetXY(41, 149);
	//para posicion automatica
	$y = 35;
	$pdf->SetWidths(array(270, 185, 80));
	$arr = array('Razón social/nombres y apellidos:', '', 'Identificación:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 185, 80));
	$arr = array(etiqueta_xml($resultado, "<razonSocialSujetoRetenido>"), '', etiqueta_xml($resultado, "<identificacionSujetoRetenido>"));
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 155, 100));
	//para direccion
	$arr1 = etiqueta_xml($resultado, "<campoAdicional");
	if (is_array($arr1)) {
		$adi = '';
		for ($i = 0; $i < count($arr1); $i++) {


			if ($i == 0) {
				$adi = $arr1[$i];
			}
			//echo $arr1[$i];
		}
	} else {
		$adi = '';
	}
	$arr = array(
		'Dirección: ' . $adi,
		'',
		''
	);
	$pdf->Row($arr, 10);
	$pdf->SetWidths(array(270, 155, 100));
	if (etiqueta_xml($resultado, "<moneda") == 'DOLAR') {
		$mon = 'USD';
	} else {
		$mon = 'USD';
		//se busca otras monedas
	}
	//die();
	//para obtener el numero de documento
	$resul1 = etiqueta_xml($resultado, "<numDocSustento");

	if (is_array($resul1)) {
		$resul2 = substr($resul1[0], 0, 6);
		$resul3 = substr($resul1[0], 7, strlen($resul1[0]));
	} else {
		$resul2 = substr($resul1, 0, 6);
		$resul3 = substr($resul1, 7, strlen($resul1));
	}
	$arr = array(
		'Documento Tipo Factura No. ' . $resul2 . '-' . $resul3,
		'   '
		,
		'Fecha emisión: ' . etiqueta_xml($resultado, "<fechaEmision>")
	);
	$pdf->Row($arr, 10);
	$y1 = $pdf->GetY();
	//echo $pdf->GetY().' '.$y;
	/*******************************************
	 ***********************************************
	 **************fin cabecera********************
	 ***********************************************
	 *********************************************/
	//die();
	$pdf->cabeceraHorizontal(array(' '), 40, 148, 525, ($pdf->GetY() - 148), 20, 5);
	//datos factura
	/******************/
	/******************/
	/******************/
	$pdf->SetFont('Arial', 'B', 6);
	$y = $y1 + 4;
	//$y=$y+188;//258
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(75, 200, 65, 60, 65, 60));
	$arr = array(
		"Impuesto",
		"Descripción",
		"Codigo Retención",
		"Base Imponible",
		"Porcentaje Retenido",
		"Valor Retenido"
	);
	$pdf->Row($arr, 10, 1);
	$pdf->SetFont('Arial', '', 6);
	//imprimir detalles factura (hacer ciclo)
	//$pdf->SetXY(20, 313);
	//verificamos si es una o varias etiquetas
	$arr1 = etiqueta_xml($resultado, "<codigo>");
	//echo $arr1[0].' aaa '.$arr1[1];
	//die();
	$valor_ret = 0;
	if (is_array($arr1)) {
		//para colocar texto segun formato del SRI
		$arr11 = array();
		for ($i = 0; $i < count($arr1); $i++) {
			$arr11[$i] = impuesto_re($arr1[$i]);
		}

		$arr2 = etiqueta_xml($resultado, "<codigoRetencion");
		if ($arr2 == '') {
			$arr2 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr2[$i] = '';
			}
		} else {
			$arr22 = array();
			for ($i = 0; $i < count($arr2); $i++) {
				$arr22[$i] = concepto_re($arr2[$i]);
			}
		}
		$arr3 = etiqueta_xml($resultado, "<codigoRetencion");
		if ($arr3 == '') {
			$arr3 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr3[$i] = '';
			}
		}
		$arr4 = etiqueta_xml($resultado, "<baseImponible");
		if ($arr4 == '') {
			$arr4 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr4[$i] = '';
			}
		}
		$arr5 = etiqueta_xml($resultado, "<porcentajeRetener");
		if ($arr5 == '') {
			$arr5 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr5[$i] = '';
			}
		}
		$arr6 = etiqueta_xml($resultado, "<valorRetenido");
		if ($arr6 == '') {
			$arr6 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr6[$i] = '';
			}
		}
		//sumar totales
		for ($i = 0; $i < count($arr6); $i++) {
			$valor_ret = $valor_ret + $arr6[$i];
		}
		for ($i = 0; $i < count($arr1); $i++) {
			$adi = '';
			$pdf->SetWidths(array(75, 200, 65, 60, 65, 60));
			$pdf->SetAligns(array("L", "L", "L", "R", "R", "R"));
			//$arr=array($arr1[$i]);
			//$arr=array('','','','','','');
			$arr = array($arr11[$i], $arr22[$i], $arr3[$i], $arr4[$i], $arr5[$i], $arr6[$i]);
			$pdf->Row($arr, 10, 1);
		}
	} else {
		$pdf->SetWidths(array(75, 200, 65, 60, 65, 60));
		$pdf->SetAligns(array("L", "L", "L", "R", "R", "R"));
		$arr = array(
			impuesto_re(etiqueta_xml($resultado, "<codigo>")),
			concepto_re(etiqueta_xml($resultado, "<codigoRetencion>")),
			etiqueta_xml($resultado, "<codigoRetencion>"),
			etiqueta_xml($resultado, "<baseImponible>"),
			etiqueta_xml($resultado, "<porcentajeRetener>"),
			etiqueta_xml($resultado, "<valorRetenido>")
		);
		$pdf->Row($arr, 10, 1);
		$valor_ret = etiqueta_xml($resultado, "<valorRetenido>");
	}
	//informacion adicional
	$pdf->SetFont('Arial', 'B', 6);
	//echo $pdf->GetY();
	//die();
	$y = $pdf->GetY();
	$pdf->SetXY(41, $y + 5);
	$pdf->SetWidths(array(321));
	$arr = array("INFORMACIÓN ADICIONAL");
	$pdf->Row($arr, 10, 1);

	$y = $pdf->GetY() - 5; //377
	$pdf->SetFont('Arial', '', 7);
	//depende del valor de coordenada 'y' del detalle
	//informacion adicional
	$pdf->SetXY($x, $y + 5);
	$pdf->Cell(321, 30, '', '1', 1, 'Q');
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY($x, ($y + 8));
	$pdf->SetWidths(array(40));
	//verificamos si es una o varias etiquetas
	$arr1 = etiqueta_xml($resultado, "<campoAdicional");
	if (is_array($arr1)) {
		for ($i = 0; $i < count($arr1); $i++) {
			$adi = '';
			if ($i == 1) {
				$adi = 'Telefono: ';
			}
			if ($i == 2) {
				$adi = 'Email: ';
			}
			if ($i != 0 and $i <= 2) {
				$pdf->SetWidths(array(140));
				$arr = array($adi . $arr1[$i]);
				$pdf->Row($arr, 10);
			}
		}
	} else {
		$pdf->SetWidths(array(140));
		$pdf->Row($arr, 10);
	}
	//$arr=array(etiqueta_xml($resultado,"<campoAdicional"));
	/*die();
				$pdf->SetWidths(array(40));
				$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
				$pdf->Row($arr,13);
				$pdf->SetWidths(array(40));
				$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
				$pdf->Row($arr,13);
				$pdf->SetWidths(array(40));
				$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
				$pdf->Row($arr,13);*/
	//fecha
	$pdf->SetXY(181, $y + 5);
	//$pdf->Cell(40,60,'','1',1,'Q');
	//detalle pago
	$pdf->SetXY(221, $y + 5 + 2);
	$arr11 = porcion_xml($resultado, '<campoAdicional nombre="Comprobante No">', '</campoAdicional>');
	//$arr11=etiqueta_xml($resultado,'<campoAdicional nombre="Comprobante No">');
	//echo $arr11[0];
	//die();
	$pdf->SetWidths(array(140));
	$arr = array('Tipo Comprobante: ' . $arr11[0]);
	$pdf->Row($arr, 10);
	//$pdf->Cell(95,60,'','1',1,'Q');
	//monto abono
	$pdf->SetXY(316, $y + 5);
	//$pdf->Cell(46,60,'','1',1,'Q');

	//leyenda final
	//$pdf->SetFont('Arial','',5);
	$pdf->SetXY($x, ($y + 65));
	//$pdf->Cell(321,46,'','1',1,'Q');
	$pdf->SetXY($x, ($y + 68));
	//$pdf->SetWidths(array(319));
	/*$arr=array('Para consultas, requerimientos o reclamos puede contactarse a nuestro Centro de Atención al Cliente Teléfono: 02-6052430, o escriba al correo
			prisma_net@hotmail.es; para Transferencia o Depósitos hacer en El Banco Pichincha: Cta. Ahr. 4245946100 a Nombre de Walter Vaca Prieto/Cta. Cte
			3422225804, a Nombre de PRISMANET PROFESIONAL S.A.');*/
	//$pdf->Row($arr,8);
	//subtotales
	//depende del valor de coordenada 'y' del detalle
	//$pdf->SetFont('Arial','',7);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, ($y - 10));
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y - 9));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	//obtenemos valor
	//$arr1=etiqueta_xml($resultado,"<totalImpuesto");
	$arr1 = porcion_xml($resultado, "<totalImpuesto>", "</totalImpuesto>");
	$imp = 0;
	$ba0 = 0;
	$bai = 0;
	$vimp0 = 0;
	$vimp1 = 0;
	if (is_array($arr1)) {
		for ($i = 0; $i < count($arr1); $i++) {
			//echo $arr1[$i].'<br>';
			$arr2 = etiqueta_xml($arr1[$i], "<tarifa");
			if (is_array($arr2)) {
				echo 'array';
				/*for ($j=0;$j<count($arr2);$j++)
																{
																	echo $arr2[$j].' ggg <br>';
																}*/
			} else {
				//echo $arr2.' fff <br>';
				if ($i == 1) {
					$imp = $arr2;
				}
			}
			$arr3 = etiqueta_xml($arr1[$i], "<baseImponible");
			if (is_array($arr3)) {
				/*for ($j=0;$j<count($arr2);$j++)
																{
																	echo $arr2[$j].' ggg <br>';
																}*/
			} else {
				//echo $arr3.' fff <br>';
				if ($i == 0) {
					$ba0 = $arr3;
				}
				if ($i == 1) {
					$bai = $arr3;
				}
			}
			$arr4 = etiqueta_xml($arr1[$i], "<valor");
			if (is_array($arr3)) {
				/*for ($j=0;$j<count($arr2);$j++)
																{
																	echo $arr2[$j].' ggg <br>';
																}*/
			} else {
				//echo $arr4.' fff <br>';
				if ($i == 0) {
					$vimp0 = $arr4;
				}
				if ($i == 1) {
					$vimp1 = $arr4;
				}
			}
		}
	}
	//die();
	$arr = array("TOTAL RETENIDO ");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y - 9));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));

	$arr = array($valor_ret);
	$pdf->Row($arr, 10);

	/*$y=$y-10+11;//365
				$pdf->SetFont('Arial','B',7);
				$pdf->SetXY(365, $y);
				$pdf->Cell(201,11,'','1',1,'Q');
				$pdf->SetXY(365, ($y+1));
				$pdf->SetWidths(array(170));
				$pdf->SetAligns(array("L"));
				$arr=array("SUBTOTAL 0%:");
				$pdf->Row($arr,10);
				$pdf->SetFont('Arial','',7);
				$pdf->SetXY(510, ($y+1));
				$pdf->SetWidths(array(55));
				$pdf->SetAligns(array("R"));
				$arr=array($ba0);
				$pdf->Row($arr,10);
				
				$y=$y+11;//380
				$pdf->SetFont('Arial','B',7);
				$pdf->SetXY(365, $y);
				$pdf->Cell(201,11,'','1',1,'Q');
				$pdf->SetXY(365, ($y+1));
				$pdf->SetWidths(array(170));
				$pdf->SetAligns(array("L"));
				$arr=array("TOTAL DESCUENTO:");
				$pdf->Row($arr,10);
				$pdf->SetFont('Arial','',7);
				$pdf->SetXY(510, ($y+1));
				$pdf->SetWidths(array(55));
				$pdf->SetAligns(array("R"));
				$arr=array(etiqueta_xml($resultado,"<totalDescuento>"));
				$pdf->Row($arr,10);
				
				$y=$y+11;//395
				$pdf->SetFont('Arial','B',7);
				$pdf->SetXY(365, $y);
				$pdf->Cell(201,11,'','1',1,'Q');
				$pdf->SetXY(365, ($y+1));
				$pdf->SetWidths(array(170));
				$pdf->SetAligns(array("L"));
				$arr=array("SUBTOTAL NO OBJETO DE IVA:");
				$pdf->Row($arr,10);
				$pdf->SetFont('Arial','',7);
				$pdf->SetXY(510, ($y+1));
				$pdf->SetWidths(array(55));
				$pdf->SetAligns(array("R"));
				$arr=array("000.00");
				$pdf->Row($arr,10);
				
				$y=$y+11;//410
				$pdf->SetFont('Arial','B',7);
				$pdf->SetXY(365, $y);
				$pdf->Cell(201,11,'','1',1,'Q');
				$pdf->SetXY(365, ($y+1));
				$pdf->SetWidths(array(170));
				$pdf->SetAligns(array("L"));
				$arr=array("SUBTOTAL EXENTO DE IVA:");
				$pdf->Row($arr,10);
				$pdf->SetFont('Arial','',7);
				$pdf->SetXY(510, ($y+1));
				$pdf->SetWidths(array(55));
				$pdf->SetAligns(array("R"));
				$arr=array("000.00");
				$pdf->Row($arr,10);
				
				$y=$y+11;//425
				$pdf->SetFont('Arial','B',7);
				$pdf->SetXY(365, $y);
				$pdf->Cell(201,11,'','1',1,'Q');
				$pdf->SetXY(365, ($y+1));
				$pdf->SetWidths(array(170));
				$pdf->SetAligns(array("L"));
				$arr=array("SUBTOTAL SIN IMPUESTOS:");
				$pdf->Row($arr,10);
				$pdf->SetFont('Arial','',7);
				$pdf->SetXY(510, ($y+1));
				$pdf->SetWidths(array(55));
				$pdf->SetAligns(array("R"));
				$arr=array($ba0);
				$pdf->Row($arr,10);
				
				$y=$y+11;//440
				$pdf->SetFont('Arial','B',7);
				$pdf->SetXY(365, $y);
				$pdf->Cell(201,11,'','1',1,'Q');
				$pdf->SetXY(365, ($y+1));
				$pdf->SetWidths(array(170));
				$pdf->SetAligns(array("L"));
				$arr=array("ICE:");
				$pdf->Row($arr,10);
				$pdf->SetFont('Arial','',7);
				$pdf->SetXY(510, ($y+1));
				$pdf->SetWidths(array(55));
				$pdf->SetAligns(array("R"));
				$arr=array("000.00");
				$pdf->Row($arr,10);
				
				$y=$y+11;//455
				$pdf->SetFont('Arial','B',7);
				$pdf->SetXY(365, $y);
				$pdf->Cell(201,11,'','1',1,'Q');
				$pdf->SetXY(365, ($y+1));
				$pdf->SetWidths(array(170));
				$pdf->SetAligns(array("L"));
				$arr=array("IVA ".$imp."%:");
				$pdf->Row($arr,10);
				$pdf->SetFont('Arial','',7);
				$pdf->SetXY(510, ($y+1));
				$pdf->SetWidths(array(55));
				$pdf->SetAligns(array("R"));
				$arr=array($vimp1);
				$pdf->Row($arr,10);
				
				$y=$y+11;//470
				$pdf->SetFont('Arial','B',7);
				$pdf->SetXY(365, $y);
				$pdf->Cell(201,11,'','1',1,'Q');
				$pdf->SetXY(365, ($y+1));
				$pdf->SetWidths(array(170));
				$pdf->SetAligns(array("L"));
				$arr=array("IVA 0%:");
				$pdf->Row($arr,10);
				$pdf->SetFont('Arial','',7);
				$pdf->SetXY(510, ($y+1));
				$pdf->SetWidths(array(55));
				$pdf->SetAligns(array("R"));
				$arr=array($vimp0);
				$pdf->Row($arr,10);
				
				$y=$y+11;//485
				$pdf->SetFont('Arial','B',7);
				$pdf->SetXY(365, $y);
				$pdf->Cell(201,11,'','1',1,'Q');
				$pdf->SetXY(365, ($y+1));
				$pdf->SetWidths(array(170));
				$pdf->SetAligns(array("L"));
				$arr=array("PROPINA:");
				$pdf->Row($arr,10);
				$pdf->SetFont('Arial','',7);
				$pdf->SetXY(510, ($y+1));
				$pdf->SetWidths(array(55));
				$pdf->SetAligns(array("R"));
				$arr=array(etiqueta_xml($resultado,"<propina>"));
				$pdf->Row($arr,10);
				
				$y=$y+11;//500
				$pdf->SetFont('Arial','B',7);
				$pdf->SetXY(365, $y);
				$pdf->Cell(201,11,'','1',1,'Q');
				$pdf->SetXY(365, ($y+1));
				$pdf->SetWidths(array(170));
				$pdf->SetAligns(array("L"));
				$arr=array("VALOR TOTAL:");
				$pdf->Row($arr,10);
				$pdf->SetFont('Arial','',7);
				$pdf->SetXY(510, ($y+1));
				$pdf->SetWidths(array(55));
				$pdf->SetAligns(array("R"));
				$arr=array(etiqueta_xml($resultado,"<importeTotal>"));
				$pdf->Row($arr,10);*/
	//echo ' ddd '.$imp1;
	//die();
	if ($imp1 == null or $imp1 == 1) {
		$pdf->Output();
	}
	if ($imp1 == 0) {
		$pdf->Output('TEMP/' . $id . '.pdf', 'F');
	}
}
/* imprimirDocElGR
   $stmt= variable con datos xml $id id del campo $formato formato del reporte aqui es pdf
   $nombre_archivo ruta y nombre del archivo xml generado $va 0 para saber si lee de una variable o 1 un archivo xml
   $imp1 paqra saber si se descarga o no null o 1 descarga 0 es para temp y adjuntar al correo*/
function imprimirDocElGR($stmt, $id = null, $formato = null, $nombre_archivo = null, $va = null, $imp1 = null)
{
	$pdf = new PDF('P', 'pt', 'LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();

	//logo
	$i = 0;
	if ($va == 1) {
		$autorizacion = simplexml_load_file($nombre_archivo);
	} else {
		$stmt = str_replace("ï»¿", "", $stmt);
		$autorizacion = simplexml_load_string($stmt);
	}
	$atrib = $autorizacion->attributes();

	//echo $autorizacion->fechaAutorizacion."<br>";
	//sustituimos
	$resultado = str_replace("<![CDATA[", "", $autorizacion->comprobante);
	$resultado = str_replace("]]>", "", $resultado);

	//echo etiqueta_xml($resultado,"<ruc>"); 
	//echo etiqueta_xml($resultado,"<razonSocial>"); 
	//echo $resultado;
	//$auto=simplexml_load_string($resultado);
	//echo ' ccc '.$auto->factura->infoTributaria;
	//die();
	$pdf->SetFont('Arial', 'B', 30);
	$x = 41;
	$pdf->SetXY($x, 20);
	//$pdf->SetWidths(array(250));
	$src = url_logo();
	if ($src !== '.') {
		$pdf->Image($src, 40, 22, 80, 40);
	} 
	/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
				{
					$logo=$_SESSION['INGRESO']['Logo_Tipo'];
				}
				else
				{
					$logo="diskcover";
				}
				$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,20,80,40,'','http://www.fpdf.org');*/
	//$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo.'.png');
	//$arr=array('NO TIENE LOGO');
	//$pdf->Row($arr,13);
	//panel
	$pdf->cabeceraHorizontal(array(' '), 285, 30, 280, 115, 20, 5);
	//texto 
	//ruc
	$pdf->SetFont('Arial', 'B', 13);
	$pdf->SetXY(285, 35);
	$pdf->SetWidths(array(70, 150));
	$arr = array('R.U.C.:     ', etiqueta_xml($resultado, "<ruc>"));
	$pdf->Row($arr, 10);
	//factura
	$pdf->SetFont('Arial', 'B', 10);
	$pdf->SetXY(285, 49);
	$pdf->SetWidths(array(160));
	$arr = array('GUIA DE REMISION No.');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 11);
	$pdf->SetXY(425, 49);
	$pdf->SetWidths(array(160));
	$arr = array(
		etiqueta_xml($resultado, "<estab>") . '-' . etiqueta_xml($resultado, "<ptoEmi>") . '-' .
		etiqueta_xml($resultado, "<secuencial>")
	);
	$pdf->Row($arr, 10);
	//fecha y hora
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 61);
	$pdf->SetWidths(array(140));
	$arr = array('FECHA Y HORA DE AUTORIZACIÓN:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 61);
	$pdf->SetWidths(array(140));
	$arr = array($autorizacion->fechaAutorizacion);
	$pdf->Row($arr, 10);
	//emisión
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 73);
	$pdf->SetWidths(array(140));
	$arr = array('EMISIÓN:');
	$pdf->Row($arr, 13);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 73);
	$pdf->SetWidths(array(140));
	$arr = array('NORMAL:');
	$pdf->Row($arr, 10);
	//ambiente
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 85);
	$pdf->SetWidths(array(140));
	$arr = array('AMBIENTE: ');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 85);
	$pdf->SetWidths(array(140));
	if (etiqueta_xml($resultado, "<ambiente>") == 2) {
		$arr = array('PRODUCCIÓN');
	} else {
		$arr = array('PRUEBA');
	}
	$pdf->Row($arr, 10);

	//clave de acceso barcode y numero
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 97);
	$pdf->SetWidths(array(280));
	$arr = array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr, 10);
	/*$pdf->SetXY(410, 180);
				$pdf->SetWidths(array(275));
				$arr=array('000000000000000000000000000000000000000000000000');
				$pdf->Row($arr,13);*/
	//C set <claveAcceso>
	//$code=etiqueta_xml($resultado,"<claveAcceso>");
	$code = $atrib['numeroAutorizacion'];
	$pdf->SetXY(285, 109);
	$pdf->Code128(290, 109, $code, 260, 20);

	//$pdf->Write(5,'C set: "'.$code.'"');
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(285, 130);
	$pdf->SetWidths(array(275));
	$arr = array($code);
	$pdf->Row($arr, 10);
	/******************/
	/******************/
	/******************/
	$pdf->cabeceraHorizontal(array(' '), 40, 70, 242, 75, 20, 5);
	//razon social
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetXY($x, 75);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<razonSocial>"));
	$pdf->Row($arr, 10);
	//nombre comercial
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetXY($x, 87);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<nombreComercial"));
	$pdf->Row($arr, 10);
	//direccion matriz
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY($x, 97);
	$pdf->SetWidths(array(140));
	$arr = array('Dirección Matríz');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetXY($x, 107);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<dirMatriz>"));
	$pdf->Row($arr, 10);
	//direccion sucursal
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY($x, 117);
	$pdf->SetWidths(array(140));
	$arr = array('Dirección Sucursal');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetXY($x, 127);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<dirEstablecimiento>"));
	$pdf->Row($arr, 10);
	//contab
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetXY($x, 135);
	$pdf->SetWidths(array(260));
	$arr = array('Obligado a llevar contabilidad:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetXY(165, 135);
	$pdf->SetWidths(array(20));
	$arr = array(etiqueta_xml($resultado, "<obligadoContabilidad>"));
	$pdf->Row($arr, 10);
	//$pdf->AddPage();
	//datos cliente
	/******************/
	/******************/
	/**************110****/
	//$pdf->cabeceraHorizontal(array(' '),20,185,574,90,20,5);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetXY(41, 149);
	//para posicion automatica
	$y = 35;
	$pdf->SetWidths(array(270, 185, 80));
	$arr = array('Razón social/nombres y apellidos:', '', 'Identificación:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 185, 80));
	//para buscar cliente
	$cliente = etiqueta_xml($resultado, "<numDocSustento>");
	/*$resul2= substr($cliente, 0, 7);
				$resul3= substr($cliente, 7, strlen($cliente));
				echo $resul2.' '.$resul3;
				die();*/
	//$cliente1=array();
	$cliente1 = explode("-", $cliente);
	//echo $cliente1[0].' '.$cliente1[1].' '.$cliente1[2];
	//die();
	$cliente = buscar_cli($cliente1[0] . $cliente1[1], $cliente1[2]);
	$arr = array($cliente[0], '', $cliente[1]);
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 155, 100));
	//para direccion
	$arr1 = etiqueta_xml($resultado, "<campoAdicional");
	if (is_array($arr1)) {
		$adi = '';
		for ($i = 0; $i < count($arr1); $i++) {


			if ($i == 0) {
				$adi = $arr1[$i];
			}
			//echo $arr1[$i];
		}
	} else {
		$adi = '';
	}
	$arr = array(
		'Dirección: ' . $adi,
		'',
		''
	);
	$pdf->Row($arr, 10);
	/*$pdf->SetWidths(array(270,155,100));
				if(etiqueta_xml($resultado,"<moneda")=='DOLAR')
				{
					$mon='USD';
				}
				else
				{
					$mon='USD';
					//se busca otras monedas
				}
				//die();
				$arr=array('Comprobante que se modifica, Factura No. '.etiqueta_xml($resultado,"<numDocModificado>"),
				'MONTO: '.$mon.'  '.etiqueta_xml($resultado,"<importeTotal>")
				,'Fecha emisión (comprobante a modificar): '.etiqueta_xml($resultado,"<fechaEmisionDocSustento>"));
				$pdf->Row($arr,10);*/
	$y1 = $pdf->GetY();
	//echo $pdf->GetY().' '.$y;
	/*******************************************
	 ***********************************************
	 **************fin cabecera********************
	 ***********************************************
	 *********************************************/
	//die();

	$pdf->cabeceraHorizontal(array(' '), 40, 148, 525, ($pdf->GetY() - 148), 20, 5);
	/*************************************************
	 ****************************************************
	 ***************datos de comprobante****************
	 ****************************************************
	 **************************************************/
	$y = $y1 + 4;
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(260, 175, 90));
	$arr = array('Comprobante de venta: ' . etiqueta_xml($resultado, "<numDocSustento>"), '', 'Fecha Emisión: ' . etiqueta_xml($resultado, "<fechaEmisionDocSustento>"));
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row($arr, 10);

	$pdf->SetWidths(array(260, 175, 90));
	$arr = array('Número de autorización: ' . etiqueta_xml($resultado, "<numAutDocSustento>"), '', '');
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row($arr, 10);

	$pdf->SetWidths(array(260, 175, 90));
	$arr = array('Motivo del traslado: ' . etiqueta_xml($resultado, "<motivoTraslado>"), '', '');
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row($arr, 10);
	$y1 = $pdf->GetY();
	//$pdf->SetWidths(array(270,185,80));
	$pdf->cabeceraHorizontal(array(' '), 40, 182, 525, ($pdf->GetY() - 180), 20, 5);

	$y = $y1 + 3;
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(260, 175, 90));
	$arr = array('Razón social/nombres y apellidos: ', '', 'Identificación(Transportista)');
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row($arr, 10);

	$pdf->SetWidths(array(260, 175, 90));
	$arr = array(etiqueta_xml($resultado, "<razonSocialTransportista>"), '', etiqueta_xml($resultado, "<rucTransportista>"));
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row($arr, 10);

	$pdf->SetWidths(array(260, 175, 90));
	$arr = array('Punto de partida: ' . etiqueta_xml($resultado, "<dirPartida>"), '', 'Identificación:');
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row($arr, 10);

	$pdf->SetWidths(array(260, 175, 90));
	$arr = array(
		'Fecha inicio transporte: ' . etiqueta_xml($resultado, "<fechaIniTransporte>"),
		'Fecha fin transporte: ' . etiqueta_xml($resultado, "<fechaFinTransporte>"),
		'Placa: ' . etiqueta_xml($resultado, "<placa>")
	);
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row($arr, 10);

	$y1 = $pdf->GetY();
	//$pdf->SetWidths(array(260,175,90));
	$pdf->cabeceraHorizontal(array(' '), 40, 215, 525, ($pdf->GetY() - 215), 20, 5);

	$y = $y1 + 3;
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(260, 175, 90));
	$arr = array('Razón social/nombres y apellidos: ', '', 'Identificación');
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row($arr, 10);

	$pdf->SetWidths(array(260, 175, 90));
	$arr = array(etiqueta_xml($resultado, "<razonSocialDestinatario>"), '', etiqueta_xml($resultado, "<identificacionDestinatario>"));
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row($arr, 10);

	$pdf->SetWidths(array(260, 175, 90));
	$arr = array('Destino (punto de llegada): ' . etiqueta_xml($resultado, "<dirDestinatario>"), '', '');
	$pdf->SetFont('Arial', '', 6);
	$pdf->Row($arr, 10);

	$y1 = $pdf->GetY();
	//$pdf->SetWidths(array(270,185,80));
	$pdf->cabeceraHorizontal(array(' '), 40, 256, 525, ($pdf->GetY() - 256), 20, 5);
	//	$y1=$pdf->GetY()+10;
	//datos factura
	/******************/
	/******************/
	/******************/
	$pdf->SetFont('Arial', 'B', 6);
	$y = $y1 + 4;
	//$y=$y+188;//258
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(85, 85, 85, 270));
	$arr = array("Codigo Unitario", "Codigo Auxiliar", "Cantidad", "Descripción");
	$pdf->Row($arr, 10, 1);
	$pdf->SetFont('Arial', '', 6);
	//imprimir detalles factura (hacer ciclo)
	//$pdf->SetXY(20, 313);
	//verificamos si es una o varias etiquetas
	$arr1 = etiqueta_xml($resultado, "<codigoInterno");
	if (is_array($arr1)) {
		$arr2 = etiqueta_xml($resultado, "<codigoAdicional");
		if ($arr2 == '') {
			$arr2 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr2[$i] = '';
			}
		}
		$arr3 = etiqueta_xml($resultado, "<cantidad");
		if ($arr3 == '') {
			$arr3 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr3[$i] = '';
			}
		}
		//$arr4='';
		$arr5 = etiqueta_xml($resultado, "<descripcion");
		if ($arr5 == '') {
			$arr5 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr5[$i] = '';
			}
		}

		for ($i = 0; $i < count($arr1); $i++) {
			$adi = '';
			$pdf->SetWidths(array(85, 85, 85, 270));
			$pdf->SetAligns(array("L", "L", "R", "L"));
			//$arr=array($arr1[$i]);
			$arr = array(
				$arr1[$i],
				$arr2[$i],
				$arr3[$i],
				$arr5[$i]
			);
			$pdf->Row($arr, 10, 1);
		}
	} else {
		$pdf->SetWidths(array(85, 85, 85, 270));
		$pdf->SetAligns(array("L", "L", "R", "L"));
		$arr = array(
			etiqueta_xml($resultado, "<codigoInterno>"),
			etiqueta_xml($resultado, "<codigoAdicional>"),
			etiqueta_xml($resultado, "<cantidad>"),
			etiqueta_xml($resultado, "<descripcion>")
		);
		$pdf->Row($arr, 10, 1);
	}
	//informacion adicional
	$pdf->SetFont('Arial', 'B', 6);
	//echo $pdf->GetY();
	//die();
	$y = $pdf->GetY();
	$pdf->SetXY(41, $y + 5);
	$pdf->SetWidths(array(525));
	$arr = array("INFORMACIÓN ADICIONAL");
	$pdf->Row($arr, 10, 1);

	$y = $pdf->GetY() - 5; //377
	$pdf->SetFont('Arial', '', 7);
	//depende del valor de coordenada 'y' del detalle
	//informacion adicional
	$pdf->SetXY($x, $y + 5);
	$pdf->Cell(525, 40, '', '1', 1, 'Q');
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY($x, ($y + 8));
	$pdf->SetWidths(array(40));
	//verificamos si es una o varias etiquetas
	$arr1 = etiqueta_xml($resultado, "<campoAdicional");
	if (is_array($arr1)) {
		for ($i = 0; $i < count($arr1); $i++) {
			$adi = '';
			if ($i == 1) {
				$adi = 'Telefono: ';
			}
			if ($i == 2) {
				$adi = 'Email: ';
			}
			if ($i != 0) {
				$pdf->SetWidths(array(250));
				$arr = array($adi . $arr1[$i]);
				$pdf->Row($arr, 10);
			}
		}
	} else {
		$pdf->SetWidths(array(250));
		$pdf->Row($arr, 10);
	}
	//$arr=array(etiqueta_xml($resultado,"<campoAdicional"));
	/*die();
				$pdf->SetWidths(array(40));
				$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
				$pdf->Row($arr,13);
				$pdf->SetWidths(array(40));
				$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
				$pdf->Row($arr,13);
				$pdf->SetWidths(array(40));
				$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
				$pdf->Row($arr,13);*/
	//fecha
	$pdf->SetXY(181, $y + 5);
	//$pdf->Cell(40,60,'','1',1,'Q');
	//detalle pago
	$pdf->SetXY(221, $y + 5);
	//$pdf->Cell(95,60,'','1',1,'Q');
	//monto abono
	$pdf->SetXY(316, $y + 5);
	//$pdf->Cell(46,60,'','1',1,'Q');

	//leyenda final
	/*$pdf->SetFont('Arial','',5);
				$pdf->SetXY($x, ($y+65));
				$pdf->Cell(321,46,'','1',1,'Q');
				$pdf->SetXY($x, ($y+68));
				$pdf->SetWidths(array(319));
				$arr=array('Para consultas, requerimientos o reclamos puede contactarse a nuestro Centro de Atención al Cliente Teléfono: 02-6052430, o escriba al correo
			prisma_net@hotmail.es; para Transferencia o Depósitos hacer en El Banco Pichincha: Cta. Ahr. 4245946100 a Nombre de Walter Vaca Prieto/Cta. Cte
			3422225804, a Nombre de PRISMANET PROFESIONAL S.A.');
				$pdf->Row($arr,8);*/
	//subtotales
	//depende del valor de coordenada 'y' del detalle

	//echo ' ddd '.$imp1;
	//die();
	if ($imp1 == null or $imp1 == 1) {
		$pdf->Output();
	}
	if ($imp1 == 0) {
		$pdf->Output('TEMP/' . $id . '.pdf', 'F');
	}
}
/* imprimirDocElNV
   $stmt= variable con datos xml $id id del campo $formato formato del reporte aqui es pdf
   $nombre_archivo ruta y nombre del archivo xml generado $va 0 para saber si lee de una variable o 1 un archivo xml
   $imp1 paqra saber si se descarga o no null o 1 descarga 0 es para temp y adjuntar al correo*/
function imprimirDocElNV($stmt, $id = null, $formato = null, $nombre_archivo = null, $va = null, $imp1 = null)
{
	$pdf = new PDF('P', 'pt', 'LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();

	//logo
	$i = 0;
	if ($va == 1) {
		$autorizacion = simplexml_load_file($nombre_archivo);
	} else {
		$stmt = str_replace("ï»¿", "", $stmt);
		$autorizacion = simplexml_load_string($stmt);
	}
	$atrib = $autorizacion->attributes();

	//echo $autorizacion->fechaAutorizacion."<br>";
	//sustituimos
	$resultado = str_replace("<![CDATA[", "", $autorizacion->comprobante);
	$resultado = str_replace("]]>", "", $resultado);

	//echo etiqueta_xml($resultado,"<ruc>"); 
	//echo etiqueta_xml($resultado,"<razonSocial>"); 
	//echo $resultado;
	//$auto=simplexml_load_string($resultado);
	//echo ' ccc '.$auto->factura->infoTributaria;
	//die();
	$pdf->SetFont('Arial', 'B', 30);
	$x = 41;
	$pdf->SetXY($x, 20);
	//$pdf->SetWidths(array(250));
	$src = url_logo();
	if ($src !== '.') {
		$pdf->Image($src, 40, 22, 80, 40);
	} 
	/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
				{
					$logo=$_SESSION['INGRESO']['Logo_Tipo'];
				}
				else
				{
					$logo="diskcover";
				}
				$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,20,80,40,'','http://www.fpdf.org');*/
	//$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo.'.png');
	//$arr=array('NO TIENE LOGO');
	//$pdf->Row($arr,13);
	//panel
	$pdf->cabeceraHorizontal(array(' '), 285, 30, 280, 115, 20, 5);
	//texto 
	//ruc
	$pdf->SetFont('Arial', 'B', 13);
	$pdf->SetXY(285, 35);
	$pdf->SetWidths(array(70, 150));
	$arr = array('R.U.C.:     ', etiqueta_xml($resultado, "<ruc>"));
	$pdf->Row($arr, 10);
	//factura
	$pdf->SetXY(285, 47);
	$pdf->SetWidths(array(140));
	$arr = array('Nota de credito No.');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 11);
	$pdf->SetXY(425, 47);
	$pdf->SetWidths(array(140));
	$arr = array(
		etiqueta_xml($resultado, "<estab>") . '-' . etiqueta_xml($resultado, "<ptoEmi>") . '-' .
		etiqueta_xml($resultado, "<secuencial>")
	);
	$pdf->Row($arr, 10);
	//fecha y hora
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 61);
	$pdf->SetWidths(array(140));
	$arr = array('FECHA Y HORA DE AUTORIZACIÓN:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 61);
	$pdf->SetWidths(array(140));
	$arr = array($autorizacion->fechaAutorizacion);
	$pdf->Row($arr, 10);
	//emisión
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 73);
	$pdf->SetWidths(array(140));
	$arr = array('EMISIÓN:');
	$pdf->Row($arr, 13);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 73);
	$pdf->SetWidths(array(140));
	$arr = array('NORMAL:');
	$pdf->Row($arr, 10);
	//ambiente
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 85);
	$pdf->SetWidths(array(140));
	$arr = array('AMBIENTE: ');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 85);
	$pdf->SetWidths(array(140));
	if (etiqueta_xml($resultado, "<ambiente>") == 2) {
		$arr = array('PRODUCCIÓN');
	} else {
		$arr = array('PRUEBA');
	}
	$pdf->Row($arr, 10);

	//clave de acceso barcode y numero
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 97);
	$pdf->SetWidths(array(280));
	$arr = array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr, 10);
	/*$pdf->SetXY(410, 180);
				$pdf->SetWidths(array(275));
				$arr=array('000000000000000000000000000000000000000000000000');
				$pdf->Row($arr,13);*/
	//C set <claveAcceso>
	//$code=etiqueta_xml($resultado,"<claveAcceso>");
	$code = $atrib['numeroAutorizacion'];
	$pdf->SetXY(285, 109);
	$pdf->Code128(290, 109, $code, 260, 20);

	//$pdf->Write(5,'C set: "'.$code.'"');
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(285, 130);
	$pdf->SetWidths(array(275));
	$arr = array($code);
	$pdf->Row($arr, 10);
	/******************/
	/******************/
	/******************/
	$pdf->cabeceraHorizontal(array(' '), 40, 70, 242, 75, 20, 5);
	//razon social
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetXY($x, 75);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<razonSocial>"));
	$pdf->Row($arr, 10);
	//nombre comercial
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetXY($x, 87);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<nombreComercial"));
	$pdf->Row($arr, 10);
	//direccion matriz
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY($x, 97);
	$pdf->SetWidths(array(140));
	$arr = array('Dirección Matríz');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetXY($x, 107);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<dirMatriz>"));
	$pdf->Row($arr, 10);
	//direccion sucursal
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY($x, 117);
	$pdf->SetWidths(array(140));
	$arr = array('Dirección Sucursal');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetXY($x, 127);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<dirEstablecimiento>"));
	$pdf->Row($arr, 10);
	//contab
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetXY($x, 135);
	$pdf->SetWidths(array(260));
	$arr = array('Obligado a llevar contabilidad:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetXY(165, 135);
	$pdf->SetWidths(array(20));
	$arr = array(etiqueta_xml($resultado, "<obligadoContabilidad>"));
	$pdf->Row($arr, 10);
	//$pdf->AddPage();
	//datos cliente
	/******************/
	/******************/
	/**************110****/
	//$pdf->cabeceraHorizontal(array(' '),20,185,574,90,20,5);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetXY(41, 149);
	//para posicion automatica
	$y = 35;
	$pdf->SetWidths(array(270, 185, 80));
	$arr = array('Razón social/nombres y apellidos:', '', 'Identificación:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 185, 80));
	$arr = array(etiqueta_xml($resultado, "<razonSocialComprador>"), '', etiqueta_xml($resultado, "<identificacionComprador>"));
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 155, 100));
	//para direccion
	$arr1 = etiqueta_xml($resultado, "<campoAdicional");
	if (is_array($arr1)) {
		$adi = '';
		for ($i = 0; $i < count($arr1); $i++) {


			if ($i == 0) {
				$adi = $arr1[$i];
			}
			//echo $arr1[$i];
		}
	} else {
		$adi = '';
	}
	$arr = array(
		'Dirección: ' . $adi,
		'Fecha emisión: ' . etiqueta_xml($resultado, "<fechaEmision>"),
		'Fecha pago: ' . etiqueta_xml($resultado, "<fechaEmision>")
	);
	$pdf->Row($arr, 10);
	$pdf->SetWidths(array(270, 155, 100));
	if (etiqueta_xml($resultado, "<moneda") == 'DOLAR') {
		$mon = 'USD';
	} else {
		$mon = 'USD';
		//se busca otras monedas
	}
	//die();
	$arr = array(
		'Comprobante que se modifica, Factura No. ' . etiqueta_xml($resultado, "<numDocModificado>"),
		'MONTO: ' . $mon . '  ' . etiqueta_xml($resultado, "<importeTotal>")
		,
		'Fecha emisión (comprobante a modificar): ' . etiqueta_xml($resultado, "<fechaEmisionDocSustento>")
	);
	$pdf->Row($arr, 10);
	$y1 = $pdf->GetY();
	//echo $pdf->GetY().' '.$y;
	/*******************************************
	 ***********************************************
	 **************fin cabecera********************
	 ***********************************************
	 *********************************************/
	//die();
	$pdf->cabeceraHorizontal(array(' '), 40, 148, 525, ($pdf->GetY() - 148), 20, 5);
	//datos factura
	/******************/
	/******************/
	/******************/
	$pdf->SetFont('Arial', 'B', 6);
	$y = $y1 + 4;
	//$y=$y+188;//258
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(65, 215, 65, 65, 60, 55));
	$arr = array(
		"Codigo Unitario",
		"Descripción",
		"Cantidad Total",
		"Precio Unitario",
		"Valor Descuento",
		"Valor Total"
	);
	$pdf->Row($arr, 10, 1);
	$pdf->SetFont('Arial', '', 6);
	//imprimir detalles factura (hacer ciclo)
	//$pdf->SetXY(20, 313);
	//verificamos si es una o varias etiquetas
	$arr1 = etiqueta_xml($resultado, "<codigoInterno");
	if (is_array($arr1)) {
		/*$arr2=etiqueta_xml($resultado,"<codigoAuxiliar");
								if($arr2=='')
								{
									$arr2=array();
									//llenamos array vacio
									for ($i=0;$i<count($arr1);$i++)
									{
										$arr2[$i]='';
									}
								}*/
		$arr3 = etiqueta_xml($resultado, "<cantidad");
		if ($arr3 == '') {
			$arr3 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr3[$i] = '';
			}
		}
		//$arr4='';
		$arr5 = etiqueta_xml($resultado, "<descripcion");
		if ($arr5 == '') {
			$arr5 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr5[$i] = '';
			}
		}
		//$arr6='';
		$arr7 = etiqueta_xml($resultado, "<precioUnitario");
		if ($arr7 == '') {
			$arr7 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr7[$i] = '';
			}
		}
		$arr8 = etiqueta_xml($resultado, "<descuento");
		if ($arr8 == '') {
			$arr8 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr8[$i] = '';
			}
		}
		//$arr9='';
		$arr10 = etiqueta_xml($resultado, "<precioTotalSinImpuesto");
		if ($arr10 == '') {
			$arr10 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr10[$i] = '';
			}
		}
		for ($i = 0; $i < count($arr1); $i++) {
			$adi = '';
			$pdf->SetWidths(array(65, 215, 65, 65, 60, 55));
			$pdf->SetAligns(array("L", "L", "R", "R", "R", "R"));
			//$arr=array($arr1[$i]);
			$arr = array(
				$arr1[$i],
				$arr3[$i],
				$arr5[$i],
				$arr7[$i],
				$arr8[$i],
				$arr10[$i]
			);
			$pdf->Row($arr, 10, 1);
		}
	} else {
		$pdf->SetWidths(array(65, 215, 65, 65, 60, 55));
		$pdf->SetAligns(array("L", "L", "R", "R", "R", "R"));
		$arr = array(
			etiqueta_xml($resultado, "<codigoInterno>"),
			etiqueta_xml($resultado, "<descripcion>"),
			etiqueta_xml($resultado, "<cantidad>"),
			etiqueta_xml($resultado, "<precioUnitario>"),
			etiqueta_xml($resultado, "<descuento>"),
			etiqueta_xml($resultado, "<precioTotalSinImpuesto>")
		);
		$pdf->Row($arr, 10, 1);
	}
	//informacion adicional
	$pdf->SetFont('Arial', 'B', 6);
	//echo $pdf->GetY();
	//die();
	$y = $pdf->GetY();
	$pdf->SetXY(41, $y + 5);
	$pdf->SetWidths(array(140, 40, 95, 46));
	$arr = array("INFORMACIÓN ADICIONAL", "Fecha", "Deltalle del pago", "Monto Abono");
	$pdf->Row($arr, 10, 1);

	$y = $pdf->GetY() - 5; //377
	$pdf->SetFont('Arial', '', 7);
	//depende del valor de coordenada 'y' del detalle
	//informacion adicional
	$pdf->SetXY($x, $y + 5);
	$pdf->Cell(140, 60, '', '1', 1, 'Q');
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY($x, ($y + 8));
	$pdf->SetWidths(array(40));
	//verificamos si es una o varias etiquetas
	$arr1 = etiqueta_xml($resultado, "<campoAdicional");
	if (is_array($arr1)) {
		for ($i = 0; $i < count($arr1); $i++) {
			$adi = '';
			if ($i == 1) {
				$adi = 'Telefono: ';
			}
			if ($i == 2) {
				$adi = 'Email: ';
			}
			if ($i != 0) {
				$pdf->SetWidths(array(140));
				$arr = array($adi . $arr1[$i]);
				$pdf->Row($arr, 10);
			}
		}
	} else {
		$pdf->SetWidths(array(140));
		$pdf->Row($arr, 10);
	}
	//$arr=array(etiqueta_xml($resultado,"<campoAdicional"));
	/*die();
				$pdf->SetWidths(array(40));
				$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
				$pdf->Row($arr,13);
				$pdf->SetWidths(array(40));
				$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
				$pdf->Row($arr,13);
				$pdf->SetWidths(array(40));
				$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
				$pdf->Row($arr,13);*/
	//fecha
	$pdf->SetXY(181, $y + 5);
	$pdf->Cell(40, 60, '', '1', 1, 'Q');
	//detalle pago
	$pdf->SetXY(221, $y + 5);
	$pdf->Cell(95, 60, '', '1', 1, 'Q');
	//monto abono
	$pdf->SetXY(316, $y + 5);
	$pdf->Cell(46, 60, '', '1', 1, 'Q');

	//leyenda final
	$pdf->SetFont('Arial', '', 5);
	$pdf->SetXY($x, ($y + 65));
	$pdf->Cell(321, 46, '', '1', 1, 'Q');
	$pdf->SetXY($x, ($y + 68));
	$pdf->SetWidths(array(319));
	$arr = array('Para consultas, requerimientos o reclamos puede contactarse a nuestro Centro de Atención al Cliente Teléfono: 02-6052430, o escriba al correo
prisma_net@hotmail.es; para Transferencia o Depósitos hacer en El Banco Pichincha: Cta. Ahr. 4245946100 a Nombre de Walter Vaca Prieto/Cta. Cte
3422225804, a Nombre de PRISMANET PROFESIONAL S.A.'
	);
	$pdf->Row($arr, 8);
	//subtotales
	//depende del valor de coordenada 'y' del detalle
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, ($y - 10));
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y - 9));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	//obtenemos valor
	//$arr1=etiqueta_xml($resultado,"<totalImpuesto");
	$arr1 = porcion_xml($resultado, "<totalImpuesto>", "</totalImpuesto>");
	$imp = 0;
	$ba0 = 0;
	$bai = 0;
	$vimp0 = 0;
	$vimp1 = 0;
	if (is_array($arr1)) {
		for ($i = 0; $i < count($arr1); $i++) {
			//echo $arr1[$i].'<br>';
			$arr2 = etiqueta_xml($arr1[$i], "<tarifa");
			if (is_array($arr2)) {
				echo 'array';
				/*for ($j=0;$j<count($arr2);$j++)
																{
																	echo $arr2[$j].' ggg <br>';
																}*/
			} else {
				//echo $arr2.' fff <br>';
				if ($i == 1) {
					$imp = $arr2;
				}
			}
			$arr3 = etiqueta_xml($arr1[$i], "<baseImponible");
			if (is_array($arr3)) {
				/*for ($j=0;$j<count($arr2);$j++)
																{
																	echo $arr2[$j].' ggg <br>';
																}*/
			} else {
				//echo $arr3.' fff <br>';
				if ($i == 0) {
					$ba0 = $arr3;
				}
				if ($i == 1) {
					$bai = $arr3;
				}
			}
			$arr4 = etiqueta_xml($arr1[$i], "<valor");
			if (is_array($arr3)) {
				/*for ($j=0;$j<count($arr2);$j++)
																{
																	echo $arr2[$j].' ggg <br>';
																}*/
			} else {
				//echo $arr4.' fff <br>';
				if ($i == 0) {
					$vimp0 = $arr4;
				}
				if ($i == 1) {
					$vimp1 = $arr4;
				}
			}
		}
	}
	//die();
	$arr = array("SUBTOTAL " . $imp . "%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y - 9));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));

	$arr = array($bai);
	$pdf->Row($arr, 10);

	$y = $y - 10 + 11; //365
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL 0%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array($ba0);
	$pdf->Row($arr, 10);

	$y = $y + 11; //380
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("TOTAL DESCUENTO:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array(etiqueta_xml($resultado, "<totalDescuento>"));
	$pdf->Row($arr, 10);

	$y = $y + 11; //395
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL NO OBJETO DE IVA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array("000.00");
	$pdf->Row($arr, 10);

	$y = $y + 11; //410
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL EXENTO DE IVA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array("000.00");
	$pdf->Row($arr, 10);

	$y = $y + 11; //425
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL SIN IMPUESTOS:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array($ba0);
	$pdf->Row($arr, 10);

	$y = $y + 11; //440
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("ICE:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array("000.00");
	$pdf->Row($arr, 10);

	$y = $y + 11; //455
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("IVA " . $imp . "%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array($vimp1);
	$pdf->Row($arr, 10);

	$y = $y + 11; //470
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("IVA 0%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array($vimp0);
	$pdf->Row($arr, 10);

	$y = $y + 11; //485
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("PROPINA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array(etiqueta_xml($resultado, "<propina>"));
	$pdf->Row($arr, 10);

	$y = $y + 11; //500
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("VALOR TOTAL:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array(etiqueta_xml($resultado, "<valorModificacion>"));
	$pdf->Row($arr, 10);
	//echo ' ddd '.$imp1;
	//die();
	if ($imp1 == null or $imp1 == 1) {
		$pdf->Output();
	}
	if ($imp1 == 0) {
		$pdf->Output('TEMP/' . $id . '.pdf', 'F');
	}
}
/* imprimirDocElNV
   $stmt= variable con datos xml $id id del campo $formato formato del reporte aqui es pdf
   $nombre_archivo ruta y nombre del archivo xml generado $va 0 para saber si lee de una variable o 1 un archivo xml
   $imp1 paqra saber si se descarga o no null o 1 descarga 0 es para temp y adjuntar al correo*/
function imprimirDocElND($stmt, $id = null, $formato = null, $nombre_archivo = null, $va = null, $imp1 = null)
{
	$pdf = new PDF('P', 'pt', 'LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();

	//logo
	$i = 0;
	if ($va == 1) {
		$autorizacion = simplexml_load_file($nombre_archivo);
	} else {
		$stmt = str_replace("ï»¿", "", $stmt);
		$autorizacion = simplexml_load_string($stmt);
	}
	$atrib = $autorizacion->attributes();

	//echo $autorizacion->fechaAutorizacion."<br>";
	//sustituimos
	$resultado = str_replace("<![CDATA[", "", $autorizacion->comprobante);
	$resultado = str_replace("]]>", "", $resultado);

	//echo etiqueta_xml($resultado,"<ruc>"); 
	//echo etiqueta_xml($resultado,"<razonSocial>"); 
	//echo $resultado;
	//$auto=simplexml_load_string($resultado);
	//echo ' ccc '.$auto->factura->infoTributaria;
	//die();
	$pdf->SetFont('Arial', 'B', 30);
	$x = 41;
	$pdf->SetXY($x, 20);
	//$pdf->SetWidths(array(250));
	$src = url_logo();
	if ($src !== '.') {
		$pdf->Image($src, 40, 22, 80, 40);
	} 
	
	/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
				{
					$logo=$_SESSION['INGRESO']['Logo_Tipo'];
				}
				else
				{
					$logo="diskcover";
				}
				$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,20,80,40,'','http://www.fpdf.org');*/
	//$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo.'.png');
	//$arr=array('NO TIENE LOGO');
	//$pdf->Row($arr,13);
	//panel
	$pdf->cabeceraHorizontal(array(' '), 285, 30, 280, 115, 20, 5);
	//texto 
	//ruc
	$pdf->SetFont('Arial', 'B', 13);
	$pdf->SetXY(285, 35);
	$pdf->SetWidths(array(70, 150));
	$arr = array('R.U.C.:     ', etiqueta_xml($resultado, "<ruc>"));
	$pdf->Row($arr, 10);
	//factura
	$pdf->SetXY(285, 47);
	$pdf->SetWidths(array(140));
	$arr = array('Nota de credito No.');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 11);
	$pdf->SetXY(425, 47);
	$pdf->SetWidths(array(140));
	$arr = array(
		etiqueta_xml($resultado, "<estab>") . '-' . etiqueta_xml($resultado, "<ptoEmi>") . '-' .
		etiqueta_xml($resultado, "<secuencial>")
	);
	$pdf->Row($arr, 10);
	//fecha y hora
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 61);
	$pdf->SetWidths(array(140));
	$arr = array('FECHA Y HORA DE AUTORIZACIÓN:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 61);
	$pdf->SetWidths(array(140));
	$arr = array($autorizacion->fechaAutorizacion);
	$pdf->Row($arr, 10);
	//emisión
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 73);
	$pdf->SetWidths(array(140));
	$arr = array('EMISIÓN:');
	$pdf->Row($arr, 13);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 73);
	$pdf->SetWidths(array(140));
	$arr = array('NORMAL:');
	$pdf->Row($arr, 10);
	//ambiente
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 85);
	$pdf->SetWidths(array(140));
	$arr = array('AMBIENTE: ');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(425, 85);
	$pdf->SetWidths(array(140));
	if (etiqueta_xml($resultado, "<ambiente>") == 2) {
		$arr = array('PRODUCCIÓN');
	} else {
		$arr = array('PRUEBA');
	}
	$pdf->Row($arr, 10);

	//clave de acceso barcode y numero
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(285, 97);
	$pdf->SetWidths(array(280));
	$arr = array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr, 10);
	/*$pdf->SetXY(410, 180);
				$pdf->SetWidths(array(275));
				$arr=array('000000000000000000000000000000000000000000000000');
				$pdf->Row($arr,13);*/
	//C set <claveAcceso>
	//$code=etiqueta_xml($resultado,"<claveAcceso>");
	$code = $atrib['numeroAutorizacion'];
	$pdf->SetXY(285, 109);
	$pdf->Code128(290, 109, $code, 260, 20);

	//$pdf->Write(5,'C set: "'.$code.'"');
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(285, 130);
	$pdf->SetWidths(array(275));
	$arr = array($code);
	$pdf->Row($arr, 10);
	/******************/
	/******************/
	/******************/
	$pdf->cabeceraHorizontal(array(' '), 40, 70, 242, 75, 20, 5);
	//razon social
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetXY($x, 75);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<razonSocial>"));
	$pdf->Row($arr, 10);
	//nombre comercial
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetXY($x, 87);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<nombreComercial"));
	$pdf->Row($arr, 10);
	//direccion matriz
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY($x, 97);
	$pdf->SetWidths(array(140));
	$arr = array('Dirección Matríz');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetXY($x, 107);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<dirMatriz>"));
	$pdf->Row($arr, 10);
	//direccion sucursal
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->SetXY($x, 117);
	$pdf->SetWidths(array(140));
	$arr = array('Dirección Sucursal');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 8);
	$pdf->SetXY($x, 127);
	$pdf->SetWidths(array(280));
	$arr = array(etiqueta_xml($resultado, "<dirEstablecimiento>"));
	$pdf->Row($arr, 10);
	//contab
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetXY($x, 135);
	$pdf->SetWidths(array(260));
	$arr = array('Obligado a llevar contabilidad:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetXY(165, 135);
	$pdf->SetWidths(array(20));
	$arr = array(etiqueta_xml($resultado, "<obligadoContabilidad>"));
	$pdf->Row($arr, 10);
	//$pdf->AddPage();
	//datos cliente
	/******************/
	/******************/
	/**************110****/
	//$pdf->cabeceraHorizontal(array(' '),20,185,574,90,20,5);
	$pdf->SetFont('Arial', 'B', 6);
	$pdf->SetXY(41, 149);
	//para posicion automatica
	$y = 35;
	$pdf->SetWidths(array(270, 185, 80));
	$arr = array('Razón social/nombres y apellidos:', '', 'Identificación:');
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 185, 80));
	$arr = array(etiqueta_xml($resultado, "<razonSocialComprador>"), '', etiqueta_xml($resultado, "<identificacionComprador>"));
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 6);
	$pdf->SetWidths(array(270, 155, 100));
	//para direccion
	$arr1 = etiqueta_xml($resultado, "<campoAdicional");
	if (is_array($arr1)) {
		$adi = '';
		for ($i = 0; $i < count($arr1); $i++) {


			if ($i == 0) {
				$adi = $arr1[$i];
			}
			//echo $arr1[$i];
		}
	} else {
		$adi = '';
	}
	$arr = array(
		'Dirección: ' . $adi,
		'Fecha emisión: ' . etiqueta_xml($resultado, "<fechaEmision>"),
		'Fecha pago: ' . etiqueta_xml($resultado, "<fechaEmision>")
	);
	$pdf->Row($arr, 10);
	$pdf->SetWidths(array(270, 155, 100));
	if (etiqueta_xml($resultado, "<moneda") == 'DOLAR') {
		$mon = 'USD';
	} else {
		$mon = 'USD';
		//se busca otras monedas
	}
	//die();
	$arr = array(
		'Comprobante que se modifica, Factura No. ' . etiqueta_xml($resultado, "<numDocModificado>"),
		'MONTO: ' . $mon . '  ' . etiqueta_xml($resultado, "<importeTotal>")
		,
		'Fecha emisión (comprobante a modificar): ' . etiqueta_xml($resultado, "<fechaEmisionDocSustento>")
	);
	$pdf->Row($arr, 10);
	$y1 = $pdf->GetY();
	//echo $pdf->GetY().' '.$y;
	/*******************************************
	 ***********************************************
	 **************fin cabecera********************
	 ***********************************************
	 *********************************************/
	//die();
	$pdf->cabeceraHorizontal(array(' '), 40, 148, 525, ($pdf->GetY() - 148), 20, 5);
	//datos factura
	/******************/
	/******************/
	/******************/
	$pdf->SetFont('Arial', 'B', 6);
	$y = $y1 + 4;
	//$y=$y+188;//258
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(65, 215, 65, 65, 60, 55));
	$arr = array(
		"Codigo Unitario",
		"Descripción",
		"Cantidad Total",
		"Precio Unitario",
		"Valor Descuento",
		"Valor Total"
	);
	$pdf->Row($arr, 10, 1);
	$pdf->SetFont('Arial', '', 6);
	//imprimir detalles factura (hacer ciclo)
	//$pdf->SetXY(20, 313);
	//verificamos si es una o varias etiquetas
	$arr1 = etiqueta_xml($resultado, "<codigoInterno");
	if (is_array($arr1)) {
		/*$arr2=etiqueta_xml($resultado,"<codigoAuxiliar");
								if($arr2=='')
								{
									$arr2=array();
									//llenamos array vacio
									for ($i=0;$i<count($arr1);$i++)
									{
										$arr2[$i]='';
									}
								}*/
		$arr3 = etiqueta_xml($resultado, "<cantidad");
		if ($arr3 == '') {
			$arr3 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr3[$i] = '';
			}
		}
		//$arr4='';
		$arr5 = etiqueta_xml($resultado, "<descripcion");
		if ($arr5 == '') {
			$arr5 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr5[$i] = '';
			}
		}
		//$arr6='';
		$arr7 = etiqueta_xml($resultado, "<precioUnitario");
		if ($arr7 == '') {
			$arr7 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr7[$i] = '';
			}
		}
		$arr8 = etiqueta_xml($resultado, "<descuento");
		if ($arr8 == '') {
			$arr8 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr8[$i] = '';
			}
		}
		//$arr9='';
		$arr10 = etiqueta_xml($resultado, "<precioTotalSinImpuesto");
		if ($arr10 == '') {
			$arr10 = array();
			//llenamos array vacio
			for ($i = 0; $i < count($arr1); $i++) {
				$arr10[$i] = '';
			}
		}
		for ($i = 0; $i < count($arr1); $i++) {
			$adi = '';
			$pdf->SetWidths(array(65, 215, 65, 65, 60, 55));
			$pdf->SetAligns(array("L", "L", "R", "R", "R", "R"));
			//$arr=array($arr1[$i]);
			$arr = array(
				$arr1[$i],
				$arr3[$i],
				$arr5[$i],
				$arr7[$i],
				$arr8[$i],
				$arr10[$i]
			);
			$pdf->Row($arr, 10, 1);
		}
	} else {
		$pdf->SetWidths(array(65, 215, 65, 65, 60, 55));
		$pdf->SetAligns(array("L", "L", "R", "R", "R", "R"));
		$arr = array(
			etiqueta_xml($resultado, "<codigoInterno>"),
			etiqueta_xml($resultado, "<descripcion>"),
			etiqueta_xml($resultado, "<cantidad>"),
			etiqueta_xml($resultado, "<precioUnitario>"),
			etiqueta_xml($resultado, "<descuento>"),
			etiqueta_xml($resultado, "<precioTotalSinImpuesto>")
		);
		$pdf->Row($arr, 10, 1);
	}
	//informacion adicional
	$pdf->SetFont('Arial', 'B', 6);
	//echo $pdf->GetY();
	//die();
	$y = $pdf->GetY();
	$pdf->SetXY(41, $y + 5);
	$pdf->SetWidths(array(140, 40, 95, 46));
	$arr = array("INFORMACIÓN ADICIONAL", "Fecha", "Deltalle del pago", "Monto Abono");
	$pdf->Row($arr, 10, 1);

	$y = $pdf->GetY() - 5; //377
	$pdf->SetFont('Arial', '', 7);
	//depende del valor de coordenada 'y' del detalle
	//informacion adicional
	$pdf->SetXY($x, $y + 5);
	$pdf->Cell(140, 60, '', '1', 1, 'Q');
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY($x, ($y + 8));
	$pdf->SetWidths(array(40));
	//verificamos si es una o varias etiquetas
	$arr1 = etiqueta_xml($resultado, "<campoAdicional");
	if (is_array($arr1)) {
		for ($i = 0; $i < count($arr1); $i++) {
			$adi = '';
			if ($i == 1) {
				$adi = 'Telefono: ';
			}
			if ($i == 2) {
				$adi = 'Email: ';
			}
			if ($i != 0) {
				$pdf->SetWidths(array(140));
				$arr = array($adi . $arr1[$i]);
				$pdf->Row($arr, 10);
			}
		}
	} else {
		$pdf->SetWidths(array(140));
		$pdf->Row($arr, 10);
	}
	//$arr=array(etiqueta_xml($resultado,"<campoAdicional"));
	/*die();
				$pdf->SetWidths(array(40));
				$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
				$pdf->Row($arr,13);
				$pdf->SetWidths(array(40));
				$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
				$pdf->Row($arr,13);
				$pdf->SetWidths(array(40));
				$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
				$pdf->Row($arr,13);*/
	//fecha
	$pdf->SetXY(181, $y + 5);
	$pdf->Cell(40, 60, '', '1', 1, 'Q');
	//detalle pago
	$pdf->SetXY(221, $y + 5);
	$pdf->Cell(95, 60, '', '1', 1, 'Q');
	//monto abono
	$pdf->SetXY(316, $y + 5);
	$pdf->Cell(46, 60, '', '1', 1, 'Q');

	//leyenda final
	$pdf->SetFont('Arial', '', 5);
	$pdf->SetXY($x, ($y + 65));
	$pdf->Cell(321, 46, '', '1', 1, 'Q');
	$pdf->SetXY($x, ($y + 68));
	$pdf->SetWidths(array(319));
	$arr = array('Para consultas, requerimientos o reclamos puede contactarse a nuestro Centro de Atención al Cliente Teléfono: 02-6052430, o escriba al correo
prisma_net@hotmail.es; para Transferencia o Depósitos hacer en El Banco Pichincha: Cta. Ahr. 4245946100 a Nombre de Walter Vaca Prieto/Cta. Cte
3422225804, a Nombre de PRISMANET PROFESIONAL S.A.'
	);
	$pdf->Row($arr, 8);
	//subtotales
	//depende del valor de coordenada 'y' del detalle
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, ($y - 10));
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y - 9));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	//obtenemos valor
	//$arr1=etiqueta_xml($resultado,"<totalImpuesto");
	$arr1 = porcion_xml($resultado, "<totalImpuesto>", "</totalImpuesto>");
	$imp = 0;
	$ba0 = 0;
	$bai = 0;
	$vimp0 = 0;
	$vimp1 = 0;
	if (is_array($arr1)) {
		for ($i = 0; $i < count($arr1); $i++) {
			//echo $arr1[$i].'<br>';
			$arr2 = etiqueta_xml($arr1[$i], "<tarifa");
			if (is_array($arr2)) {
				echo 'array';
				/*for ($j=0;$j<count($arr2);$j++)
																{
																	echo $arr2[$j].' ggg <br>';
																}*/
			} else {
				//echo $arr2.' fff <br>';
				if ($i == 1) {
					$imp = $arr2;
				}
			}
			$arr3 = etiqueta_xml($arr1[$i], "<baseImponible");
			if (is_array($arr3)) {
				/*for ($j=0;$j<count($arr2);$j++)
																{
																	echo $arr2[$j].' ggg <br>';
																}*/
			} else {
				//echo $arr3.' fff <br>';
				if ($i == 0) {
					$ba0 = $arr3;
				}
				if ($i == 1) {
					$bai = $arr3;
				}
			}
			$arr4 = etiqueta_xml($arr1[$i], "<valor");
			if (is_array($arr3)) {
				/*for ($j=0;$j<count($arr2);$j++)
																{
																	echo $arr2[$j].' ggg <br>';
																}*/
			} else {
				//echo $arr4.' fff <br>';
				if ($i == 0) {
					$vimp0 = $arr4;
				}
				if ($i == 1) {
					$vimp1 = $arr4;
				}
			}
		}
	}
	//die();
	$arr = array("SUBTOTAL " . $imp . "%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y - 9));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));

	$arr = array($bai);
	$pdf->Row($arr, 10);

	$y = $y - 10 + 11; //365
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL 0%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array($ba0);
	$pdf->Row($arr, 10);

	$y = $y + 11; //380
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("TOTAL DESCUENTO:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array(etiqueta_xml($resultado, "<totalDescuento>"));
	$pdf->Row($arr, 10);

	$y = $y + 11; //395
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL NO OBJETO DE IVA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array("000.00");
	$pdf->Row($arr, 10);

	$y = $y + 11; //410
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL EXENTO DE IVA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array("000.00");
	$pdf->Row($arr, 10);

	$y = $y + 11; //425
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("SUBTOTAL SIN IMPUESTOS:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array($ba0);
	$pdf->Row($arr, 10);

	$y = $y + 11; //440
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("ICE:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array("000.00");
	$pdf->Row($arr, 10);

	$y = $y + 11; //455
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("IVA " . $imp . "%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array($vimp1);
	$pdf->Row($arr, 10);

	$y = $y + 11; //470
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("IVA 0%:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array($vimp0);
	$pdf->Row($arr, 10);

	$y = $y + 11; //485
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("PROPINA:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array(etiqueta_xml($resultado, "<propina>"));
	$pdf->Row($arr, 10);

	$y = $y + 11; //500
	$pdf->SetFont('Arial', 'B', 7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201, 11, '', '1', 1, 'Q');
	$pdf->SetXY(365, ($y + 1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr = array("VALOR TOTAL:");
	$pdf->Row($arr, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetXY(510, ($y + 1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr = array(etiqueta_xml($resultado, "<valorModificacion>"));
	$pdf->Row($arr, 10);
	//echo ' ddd '.$imp1;
	//die();
	if ($imp1 == null or $imp1 == 1) {
		$pdf->Output();
	}
	if ($imp1 == 0) {
		$pdf->Output('TEMP/' . $id . '.pdf', 'F');
	}
}

/**
 * Imprime un reporte en PDF con los datos de una consulta sql
 * @param array $datos Datos a imprimir
 * @param int $sizeLetra Tamaño de la letra
 * @param array $cabecerasEspecificadas Cabeceras específicas a imprimir
 * @return string Ruta del archivo generado
 */
function ImprimirAdodc($datos, $sizeLetra = 10, $cabecerasEspecificadas = []): string
{
	//TODO: Falta añadirle la cabecera para cada página
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial', '', $sizeLetra);
	$cabeceras = [];
	$anchoColumnas = [40, 60, 30];
	if(!empty($cabecerasEspecificadas)){
		$cabeceras = $cabecerasEspecificadas;
	}else if (!empty($datos)) {
		$cabeceras = array_keys((array) $datos[0]);
	}
	// Inicializar los anchos de las columnas
	$anchoColumnas = array_fill(0, count($cabeceras), 0);

	// Calcular el ancho de la columna basado en las cabeceras
	foreach ($cabeceras as $index => $header) {
		$anchoColumnas[$index] = $pdf->GetStringWidth($header) + 2; // +2 para un poco de margen
	}

	// Calcular el ancho de columna basado en los datos
	foreach ($datos as $row) {
		foreach ($cabeceras as $index => $header) {
			$texto = conversionToString($row[$header]);
			$anchoTexto = $pdf->GetStringWidth($texto) + 2; // +2 para un poco de margen
			if ($anchoTexto > $anchoColumnas[$index]) {
				$anchoColumnas[$index] = $anchoTexto;
			}
		}
	}

	// Dibujar las cabeceras con el ancho calculado
	foreach ($cabeceras as $index => $header) {
		$pdf->Cell($anchoColumnas[$index], 7, $header, 1);
	}
	$pdf->Ln();

	// Dibujar los datos con el ancho calculado
	foreach ($datos as $row) {
		foreach ($cabeceras as $index => $header) {
			// Asegurar de que el header exista en el row antes de imprimir
			if (isset($row[$header])) {
                $pdf->Cell($anchoColumnas[$index], 6, conversionToString($row[$header]), 1);
            } else {
                // Si el header no existe, podría imprimir una celda vacía o manejar como se prefiera
                $pdf->Cell($anchoColumnas[$index], 6, '', 1);
            }
		}
		$pdf->Ln();
	}
	if(!file_exists(dirname(__DIR__, 2) . '/TEMP/'))
		{
			mkdir(dirname(__DIR__, 2) . '/TEMP/', 0777);
		}
	$ruta = dirname(__DIR__, 2) . '\TEMP\Reporte_Clientes_' . date('Y-m-d_H-i-s') . '.pdf';
	$pdf->Output('F', $ruta);
	return $ruta;
}


?>