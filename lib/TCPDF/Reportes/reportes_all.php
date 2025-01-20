<?php 
require_once('tcpdf_include.php');
/**
 * 
 */

/**
 * 
 */
class MYPDF extends TCPDF
{
	private $datos;
	public function SetearDatos($datos)
	{
		$this->datos = $datos;
	}

	public function Header() {
        // Logo
        $this->SetXY(13,7);
      		$src = url_logo();
		if ($src !== '.') {
			$this->Image($src, 13, 5, 42, 20);
		} 

		$i = 0;
		$agente = '';
		$rimpe = '';
		if (isset($this->datos['Tipo_contribuyente']) && count($this->datos['Tipo_contribuyente']) > 0) {
			// print_r($this->datos['Tipo_contribuyente']);die();
			$agente = $this->datos['Tipo_contribuyente']['@Agente'];
			if ($this->datos['Tipo_contribuyente']['@micro'] != '.') {
				$rimpe = $this->datos['Tipo_contribuyente']['@micro'];
			}
		}

		// Start Transformation

		$lineStyle = array(
		    'width' => 0.1, // Grosor de la línea
		    'cap' => 'butt', // Forma del extremo ('butt', 'round', 'square')
		    'join' => 'miter', // Forma de la unión ('miter', 'round', 'bevel')
		    'dash' => 0, // Estilo de la línea (0 = sólida, otro valor = guionada)
		    'color' => array(32,32,32) // Color en RGB
		);

		// Aplicar el estilo de la línea
		$this->SetLineStyle($lineStyle);


		$this->StartTransform();
		$this->Rect(13, 27, 84, 27, 'D');
		$this->StopTransform();


		$this->StartTransform();
		$this->Rect(98, 7, 105, 47, 'D');
		$this->StopTransform();


		$this->StartTransform();
		$cuardo_3_X=13;
		$cuardo_3_Y=55;
		$medita_total_3 = 190;
		$this->Rect($cuardo_3_X,$cuardo_3_Y,$medita_total_3, 19, 'D');
		$this->StopTransform();


	//=======================================cuadro izquierda inicial=================
	$border = 0;
	$this->SetXY(14,27);
	$this->SetFont('helvetica', 'B', 8);
	if ($_SESSION['INGRESO']['Nombre_Comercial'] == $_SESSION['INGRESO']['Razon_Social']) {
		$this->MultiCell(55, 2, '[DEFAULT] '.$_SESSION['INGRESO']['Razon_Social'], $border, '', 0, 1, '', '', true);

	} else {
		//razon social
		$this->SetX(14);
		$arr = array($_SESSION['INGRESO']['Razon_Social']); //mio		
		$this->MultiCell(84, 2,$_SESSION['INGRESO']['Razon_Social'], $border, '', 0, 1, '', '', true);
		$this->SetX(14);
		$arr = array($_SESSION['INGRESO']['Nombre_Comercial']);
		$this->MultiCell(84, 2,$_SESSION['INGRESO']['Nombre_Comercial'], $border, '', 0, 1, '', '', true);		
	}

	$this->SetX(14);
	$this->SetFont('helvetica', 'B', 7);
	$this->MultiCell(84, 2,'Dirección Matríz', $border, '', 0, 1, '', '', true);

	$this->SetX(14);
	$this->SetFont('helvetica', '', 7);
	$this->MultiCell(84, 2,$_SESSION['INGRESO']['Direccion'], $border, '', 0, 1, '', '', true);

	
	//sucursal si es diferente a 001
	$punto = substr($this->datos[0]['Serie'], 3, 6);
	$suc = substr($this->datos[0]['Serie'], 0, 3);
	// print_r($punto);die();
	if (isset($this->datos[0]['Serie'])) {
		if ($suc != '001' && count($sucursal) > 0 && $sucursal[0]['Nombre_Establecimiento'] != '.' && $sucursal[0]['Nombre_Establecimiento'] != '') {
			$this->SetX(14);
			$this->MultiCell(84, 2,'Direccion de establecimiento / Sucursal', $border, '', 0, 1, '', '', true);
			$this->MultiCell(84, 2,$sucursal[0]['Direccion_Establecimiento'], $border, '', 0, 1, '', '', true);
		}
	}

	if (strlen($_SESSION['INGRESO']['Telefono1']) > 1) {
		$this->SetX(14);
		$this->MultiCell(84, 2,mb_convert_encoding($_SESSION['INGRESO']['Telefono1'], 'UTF-8'), $border, '', 0, 1, '', '', true);
	}

	if (strlen($_SESSION['INGRESO']['Email']) > 1) {
		$this->SetX(14);
		$this->MultiCell(84, 2,mb_convert_encoding($_SESSION['INGRESO']['Email'], 'UTF-8'), $border, '', 0, 1, '', '', true);
	}

	$conta = 'NO';
	if ($_SESSION['INGRESO']['Obligado_Conta'] != 'NO') {
		$conta = 'SI'; //mio
	}

	$this->SetX(14);
	 $this->writeHTMLCell(84, 2,'', '','<b>Obligado a llevar contabilidad:</b>'.$conta, $border, 1, false, true, '', false);
	//==================================fin cuadro izquierda inicial==================

	//==================================cuadro derecha inicial ========================

	 // columna 1 
	$cuardo_2_X = 99;
	$cuardo_2_y = 7;

 	$this->SetXY($cuardo_2_X,$cuardo_2_y); 	
	$this->SetFont('helvetica', 'B', 11);
	$this->MultiCell(53, 2,'R.U.C. ' . $_SESSION['INGRESO']['RUC'], $border, '', 0, 1, '', '', true);

	// /-----------------------------------------------------------------------------
	 if ($agente != '' && $agente != '.') {	
		$this->SetX($cuardo_2_X); 	
		$this->SetFont('helvetica', 'B', 7);
		$this->writeHTMLCell(105, 3,'', '','<span style="color:red">Agente de Retención Resolución:' . $agente.'</span>', $border=1, 1, false, true, '', false);
	 }
	// ----------------------------------------------------------------------------------

	
	$this->SetFont('helvetica', 'B', 11);
	$this->SetX($cuardo_2_X);
	$row_col = 72;
	switch ($this->datos[0]['TC']) {
		case 'LC':
			$this->MultiCell($row_col, 2,'Liquidacion compra No.', $border, '', 0, 1, '', '', true);
			break;
		case 'FA':
			$this->MultiCell($row_col, 2,'Factura No.', $border, '', 0, 1, '', '', true);
			break;
		case 'GR':
			$this->MultiCell($row_col, 2,'Guia de Remision No.', $border, '', 0, 1, '', '', true);
			break;
		case 'LC':
			$this->MultiCell($row_col, 2,'Liquidacion compra No.', $border, '', 0, 1, '', '', true);
			break;
		case 'LC':
			$this->MultiCell($row_col, 2,'Liquidacion compra No.', $border, '', 0, 1, '', '', true);
			break;
		
		default:
			// code...
			break;
	}

	$this->SetX($cuardo_2_X);
	
	$this->SetFont('helvetica', 'B', 8);
	$this->MultiCell($row_col, 2,'FECHA Y HORA DE AUTORIZACIÓN:', $border, '', 0, 1, '', '', true);

	$this->SetX($cuardo_2_X);
	$this->MultiCell($row_col, 2,'EMISIÓN', $border, '', 0, 1, '', '', true);

	$this->SetX($cuardo_2_X);
	$this->MultiCell($row_col, 2,'AMBIENTE', $border, '', 0, 1, '', '', true);

	$this->SetX($cuardo_2_X);
	$this->MultiCell(105, 5,'NUMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO', $border, '', 0, 1, '', '', true);
		// codigo de barras
		$style = array(
		    'position' => '',
		    'align' => 'C',
		    'stretch' => false,
		    'fitwidth' => true,
		    'cellfitalign' => '',
		    'border' => false,
		    'hpadding' => 'auto',
		    'vpadding' => 'auto',
		    'fgcolor' => array(0,0,0),
		    'bgcolor' => false, //array(255,255,255),
		    'text' => true,
		    'font' => 'helvetica',
		    'fontsize' => 8,
		    'stretchtext' => 4
		);

		$this->SetX($cuardo_2_X);
		//clave de acceso barcode y numero	
		if ($this->datos[0]['Clave_Acceso'] != $this->datos[0]['Autorizacion']) {
			$this->write1DBarcode($this->datos[0]['Autorizacion'], 'C128', '', '', 105, 18, 0.4, $style, 'N');
		} else if ($this->datos[0]['Clave_Acceso'] > 39) {				
			$this->write1DBarcode($this->datos[0]['Clave_Acceso'], 'C128', '', '',105, 18, 0.4, $style, 'N');
		}
		
	// fin columna 1



	// COLUMNA 2

	$cuardo_2_X_2 = 151;
	$cuardo_2_y_2 = 7;

	$this->SetXY($cuardo_2_X_2,$cuardo_2_y_2); 
	$this->SetFont('helvetica', 'B', 7);
	if ($rimpe != '') {	
		$this->writeHTMLCell(52, 5,'', '','<span style="color:red">'.$rimpe.'</span>', $border, 1, false, true, '', false);
	}
	// /-----------------------------------------------------------------------------

	$this->SetX($cuardo_2_X+$row_col); 
	if ($agente != '' && $agente != '.') {	
		$this->SetXY($cuardo_2_X+$row_col,15); 
	}
	// ----------------------------------------------------------------------------------
	
	//NUMERO DE FACTURA
	$this->SetX($cuardo_2_X+$row_col); 	
	$this->SetFont('helvetica', '', 9);
	$this->writeHTMLCell(33, 5,'', '','<span style="color:red">'.substr($this->datos[0]['Serie'], 0, 3) . '-' .substr($this->datos[0]['Serie'], 3, 6). '-' . generaCeros($this->datos[0]['Factura'], 9).'</span>', $border, 1, false, true, '', false);
	
	$this->SetFont('helvetica', '', 8);
	// fecha y hora	
	$this->SetX($cuardo_2_X+$row_col); 
	$this->MultiCell(33, 2,$this->datos[0]['Fecha_Aut']->format('Y-m-d  h:m:s'), $border, '', 0, 1, '', '', true);

	//emisión
	$this->SetX($cuardo_2_X+$row_col); 
	$this->MultiCell(33, 2,'NORMAL', $border, '', 0, 1, '', '', true);

	//ambiente
	$ambiente = substr($this->datos[0]['Autorizacion'], 23, 1);
	// print_r($ambiente);die();
	$am = '';
	if ($ambiente == 2) { $am = 'PRODUCCION';} else if ($ambiente == 1) {$am = 'PRUEBA';}	
	$this->SetX($cuardo_2_X+$row_col); 
	$this->MultiCell(33, 2, $am, $border, '', 0, 1, '', '', true);

	// FIN COLUMNA 2



	
	//========================= fin cuadro derecha inicial========================



	//============================================cuadro cliente ==========================================

	$this->SetFont('helvetica', 'B', 7);
	$cuardo_3_X  = $cuardo_3_X+1;
	$this->SetXY($cuardo_3_X,$cuardo_3_Y);
	$row_1 = 160;
	$this->MultiCell($row_1, 3,'Razón social/nombres y apellidos:', $border, '', 0, 1, '', '', true);
	$this->SetXY($cuardo_3_X+$row_1,$cuardo_3_Y);
	$this->MultiCell($medita_total_3-$row_1, 3,'Identificación:', $border, '', 0, 1, '', '', true);

	$this->SetX($cuardo_3_X,$cuardo_3_Y+$this->GetY());
	$posicion_row  = $this->GetY();
	$this->MultiCell($row_1, 3,mb_convert_encoding($this->datos[0]['Razon_Social'], 'ISO-8859-1', 'UTF-8'), $border, '', 0, 1, '', '', true);
	$this->SetXY($cuardo_3_X+$row_1,$posicion_row);
	$this->MultiCell($medita_total_3-$row_1, 3,$this->datos[0]['RUC_CI'], $border, '', 0, 1, '', '', true);

	$this->SetX($cuardo_3_X);
	$row_1 = 120;
	$posicion_row  = $this->GetY();
	$this->MultiCell($row_1, 4,'Dirección:'. $this->datos[0]['Direccion_RS'], $border, '', 0, 1, '', '', true);
	$this->SetXY($cuardo_3_X+$row_1,$posicion_row);
	$this->MultiCell(($medita_total_3-$row_1)/2, 4,'Fecha emisión: ' . $this->datos[0]['Fecha']->format('Y-m-d'), $border, '', 0, 1, '', '', true);
	$this->SetXY($cuardo_3_X+$row_1+($medita_total_3-$row_1)/2,$posicion_row);
	$this->MultiCell(($medita_total_3-$row_1)/2, 4,'Fecha pago: ' . $this->datos[0]['Fecha_V']->format('Y-m-d'), $border, '', 0, 1, '', '', true);


	$this->SetX($cuardo_3_X);
	$row_1 = 120;
	$DiasPago = strval(strtotime($this->datos[0]['Fecha_V']->format('Y-m-d')) - strtotime($this->datos[0]['Fecha']->format('Y-m-d'))) / (60 * 60 * 24);
	$posicion_row  = $this->GetY();
	$mon= '';
	if ('DOLAR' == 'DOLAR') {
		$mon = 'USD';
	} else {
		$mon = 'USD';
		//se busca otras monedas
	}
	$this->MultiCell($row_1, 2,'FORMA DE PAGO: ' . $this->datos[0]['Tipo_Pago'], $border, '', 0, 1, '', '', true);
	$this->SetXY($cuardo_3_X+$row_1,$posicion_row);
	$this->MultiCell(($medita_total_3-$row_1)/2, 3,'MONTO '.$mon.'              ' . number_format($this->datos[0]['Total_MN'], 2, '.', ','), $border, '', 0, 1, '', '', true);
	$this->SetXY($cuardo_3_X+$row_1+($medita_total_3-$row_1)/2,$posicion_row);
	$this->MultiCell(($medita_total_3-$row_1)/2, 3,'Condición de venta: ' . $DiasPago . ' días', $border, '', 0, 1, '', '', true);


	$this->SetX($cuardo_3_X);
	$row_1 = 120;
	$posicion_row  = $this->GetY();
	$this->MultiCell($row_1,4,'', $border, '', 0, 1, '', '', true);
	$this->SetXY($cuardo_3_X+$row_1,$posicion_row);
	$this->MultiCell($medita_total_3-$row_1, 4,'No. Orden de Compra: 0', $border, '', 0, 1, '', '', true);


	//================================fin cuadro cliente========================================================= 


    }
}


class reportes_all
{
	
	function __construct()
	{
		// code...
	}


	function imprimirDocEle_fac($datos, $detalle, $educativo, $matri = false, $nombre = "", $formato = null, $nombre_archivo = null, $va = null, $imp1 = false, $abonos = array(), $sucursal = array())
	{
		$border = 0;
		$punto = substr($datos[0]['Serie'], 3, 6);
		$suc = substr($datos[0]['Serie'], 0, 3);

		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetearDatos($datos);
		// remove default header/footer
		$pdf->setPrintHeader(true);
		$pdf->setPrintFooter(false);
		$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->setMargins(13, PDF_MARGIN_TOP, 10);
		$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/spa.php')) {
			require_once(dirname(__FILE__).'/lang/spa.php');
			$pdf->setLanguageArray($l);
		}

		$pdf->AddPage('P', 'A4');

		$pdf->SetXY(13,73);

		//================================Inicio Guía de Remisión=========================================================
	//TOMADO DE VB SRI_Generar_Documento_PDF(
	switch ($datos[0]['TC']) {
		case 'FA':
			if (isset($datos[0]['Remision']) && $datos[0]['Remision'] > 0) {
				$pdf->StartTransform();
				$cuardo_4_X=13;
				$cuardo_4_Y=75;
				$medita_total_4 = 190;				
				$row_1 = 120;
				$pdf->SetXY($cuardo_4_X,$cuardo_4_Y);
				$pdf->SetFont('helvetica', 'B', 7);
				$inicio = $pdf->GetY();
				$pdf->MultiCell(($row_1), 3,"Guía Remisión: " . $datos[0]['Serie_GR'] . "-" . sprintf("%09d", $datos[0]['Remision']), $border, '', 0, 1, '', '', true);

				$pdf->SetXY($cuardo_4_X+$row_1,$cuardo_4_Y);
				$pdf->MultiCell(($medita_total_4-$row_1), 3,"Entrega: " . $datos[0]['Comercial'], $border, '', 0, 1, '', '', true);
				$row_2 = $pdf->GetY();
				$pdf->MultiCell(70, 3,"Pedido: " . $datos[0]['Pedido'], $border, '', 0, 1, '', '', true);
				$medida = $pdf->GetX();
				$pdf->SetXY($medida+70,$row_2);
				$pdf->MultiCell(50, 3,"Zona: " . ULCase($datos[0]['CiudadGRF']) . " - " . $datos[0]['Zona'], $border, '', 0, 1, '', '', true);

				$medida = $pdf->GetX();
				$pdf->SetXY($medida+70+50,$row_2);
				$pdf->MultiCell(70, 3,((strlen($datos[0]['Lugar_Entrega']) > 1) ? "Lugar de Entrega: " . $datos[0]['Lugar_Entrega'] : "Lugar de Entrega: " . $datos[0]['DireccionC']), $border, '', 0, 1, '', '', true);
				$final = $pdf->GetY();
				$h = $final-$inicio;

				$pdf->Rect($cuardo_4_X,$cuardo_4_Y,$medita_total_4, $h, 'D');
				$pdf->StopTransform();

				$pdf->SetY($final);
				
			}
			break;

		default:
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
	$tbl = '
			<table cellpadding="1">
			    <tr>
			        <td width="65px" style="border:0.1px solid rgb(64,64,64);"><b>  '.$titulo1.'</b></td>
			        <td width="65px" style="border:0.1px solid rgb(64,64,64);"><b>  '.$titulo2.'</b></td>
			        <td width="40px" style="border:0.1px solid rgb(64,64,64);"><b> Cantidad Total</b></td>
			        <td width="40px" style="border:0.1px solid rgb(64,64,64);"><b> Cantidad  Bonif.</b></td>
			        <td width="245px" style="font-size: 12px;border:0.1px solid rgb(64,64,64);"><b> D e s c r i p c i ó n</b></td>
			        <td width="53px" style="border:0.1px solid rgb(64,64,64);"><b> Lote No. /Orden No</b></td>
			        <td width="45px" style="border:0.1px solid rgb(64,64,64);"><b> Precio Unitario</b></td>
			        <td width="41px" style="border:0.1px solid rgb(64,64,64);"><b> Valor Descuento</b></td>
			        <td width="30px" style="border:0.1px solid rgb(64,64,64);"><b> Desc. %</b></td>
			        <td width="50px" align="right" style="border:0.1px solid rgb(64,64,64);"><b> Valor Total</b></td>
			    </tr>';
	
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
		
		$tbl.= '
			    <tr>
			        <td width="65px" style="border:0.1px solid rgb(64,64,64);">  '.$CODIGO1.'</td>
			        <td width="65px" style="border:0.1px solid rgb(64,64,64);">  '.$CODIGO2.'</td>
			        <td width="40px" style="border:0.1px solid rgb(64,64,64);"  align="right">'.number_format($value['Cantidad'], 2, '.', '').'</td>
			        <td width="40px" style="border:0.1px solid rgb(64,64,64);"></td>
			        <td width="245px" style="border:0.1px solid rgb(64,64,64);">'.$Producto.'</td>
			        <td width="53px" style="border:0.1px solid rgb(64,64,64);"></td>
			        <td width="45px" style="border:0.1px solid rgb(64,64,64);"  align="right">'.number_format($value['Precio'], $_SESSION['INGRESO']['Dec_PVP'], '.', '').'</td>
			        <td width="41px" style="border:0.1px solid rgb(64,64,64);"  align="right">'.$totaldes.'</td>
			        <td width="30px" style="border:0.1px solid rgb(64,64,64);"  align="right">'. $descto.'</td>
			        <td width="50px" style="border:0.1px solid rgb(64,64,64);" align="right"> '.$totalfac.'</td>
			    </tr>';
		

	}

	


	if (count($detalle_descuento) > 0) {
		foreach ($detalle_descuento as $key => $value) {
			$tbl.= '
			    <tr>
			        <td width="65px" style="border:0.1px solid rgb(64,64,64);"></td>
			        <td width="65px" style="border:0.1px solid rgb(64,64,64);"></td>
			        <td width="40px" style="border:0.1px solid rgb(64,64,64);"  align="right"></td>
			        <td width="40px" style="border:0.1px solid rgb(64,64,64);"></td>
			        <td width="245px" style="border:0.1px solid rgb(64,64,64);">'.$value['detalle'].'</td>
			        <td width="53px" style="border:0.1px solid rgb(64,64,64);"></td>
			        <td width="45px" style="border:0.1px solid rgb(64,64,64);"  align="right"></td>
			        <td width="41px" style="border:0.1px solid rgb(64,64,64);"  align="right"></td>
			        <td width="30px" style="border:0.1px solid rgb(64,64,64);"  align="right"></td>
			        <td width="50px" style="border:0.1px solid rgb(64,64,64);"align="right"> '.$totaldes.'</td>
			    </tr>';
		}
	}

	$tbl.='</table>';

	$pdf->SetFont('helvetica', '', 6);
	$pdf->SetY($pdf->GetY()+3);
	$pdf->SetDrawColor(64, 64,64);
	$pdf->writeHTML($tbl, true, false, false, false, '');


	//===================================================fin cuadrpo detalle======================================

	//====================================================cuadro datos adicionales======================
	$posicion_datos_adicionales = $pdf->GetY();
	$pdf->SetFont('helvetica', '', 6);
	$tbl= '<table cellpadding="2">
			    <tr>
			        <td width="245px" style="border:0.1px solid rgb(64,64,64);"><b>INFORMACIÓN ADICIONAL</b></td>
			        <td width="45px"  style="border:0.1px solid rgb(64,64,64);"><b>Fecha</b></td>
			        <td width="96px"  style="border:0.1px solid rgb(64,64,64);"><b>Detalle del pago</b></td>
			        <td width="60px"  style="border:0.1px solid rgb(64,64,64);"><b>Monto Abono</b></td>
			    </tr>
			    <tr>
			    	<td height="101px"  style="border:0.1px solid rgb(64,64,64);">';	

	
	if (isset($dato[0]['Telefono_RS']) && $datos[0]['Telefono_RS'] != '.' && $datos[0]['Telefono_RS'] != '') {
		$tbl.='Telefono: ' . $datos[0]['Telefono_RS'].'<br>';
	}

	

	///----------------------  infomrmacion adicional cuando punto de venta es diferente de 001 ----------------------
	// print_r($sucursal);die();


	if (!empty($sucursal) && count($sucursal) > 0 && $punto != '001' && $suc == '001' && strlen($sucursal[0]['RUC_Establecimiento']) == 13) {

		$tbl.='Punto Emision: ' . $datos[0]['Serie'].'<br>';
		
		if ($sucursal[0]['Nombre_Establecimiento'] != '.' && $sucursal[0]['Nombre_Establecimiento'] != '') {
			$tbl.=$sucursal[0]['Nombre_Establecimiento'].'<br>';
		}
		if ($sucursal[0]['RUC_Establecimiento'] != '.' && $sucursal[0]['RUC_Establecimiento'] != '') {
			$tbl.='Ruc punto Emision: ' . $sucursal[0]['RUC_Establecimiento'].'<br>';
		}
		if ($sucursal[0]['Direccion_Establecimiento'] != '.' && $sucursal[0]['Direccion_Establecimiento'] != '') {
			$tbl.='Direccion punto Emision: ' . $sucursal[0]['Direccion_Establecimiento'].'<br>';
		}
		if ($sucursal[0]['Telefono_Estab'] != '.' && $sucursal[0]['Telefono_Estab'] != '') {
			$tbl.= 'Telefono punto Emision: ' . $sucursal[0]['Telefono_Estab'].'<br>';
		}
		if ($sucursal[0]['Email_Establecimiento'] != '.' && $sucursal[0]['Email_Establecimiento'] != '') {
			$tbl.= 'Email punto Emision: ' . $sucursal[0]['Email_Establecimiento'].'<br>';
		}
		if ($sucursal[0]['Cta_Establecimiento'] != '.' && $sucursal[0]['Cta_Establecimiento'] != '') {
			$tbl.= 'Cta Punto Emision: ' . $sucursal[0]['Cta_Establecimiento'].'<br>';
		}
		if ($sucursal[0]['Placa_Vehiculo'] != '.' && $sucursal[0]['Placa_Vehiculo'] != '') {
			$tbl.= 'Placas: ' . $sucursal[0]['Placa_Vehiculo'].'<br>';
		}
	}

	///----------------------  fin infomrmacion adicional cuando punto de venta es diferente de 001 ----------------------


	///----------------------  infomrmacion adicional cuan establecimiento es diferente de 001 ----------------------

	if (!empty($sucursal) && count($sucursal) > 0 && $suc != '001' && strlen($sucursal[0]['RUC_Establecimiento']) == 13) {
		$tbl.= 'Establecimiento: ' . $datos[0]['Serie'].'<br>';
		$pdf->Row($arr, 10);

		if ($sucursal[0]['Nombre_Establecimiento'] != '.' && $sucursal[0]['Nombre_Establecimiento'] != '') {
			$tbl.= $sucursal[0]['Nombre_Establecimiento'].'<br>';
		}
		if ($sucursal[0]['RUC_Establecimiento'] != '.' && $sucursal[0]['RUC_Establecimiento'] != '') {
			$tbl.= 'Ruc establecimiento: ' . $sucursal[0]['RUC_Establecimiento'].'<br>';
		}
		if ($sucursal[0]['Telefono_Estab'] != '.' && $sucursal[0]['Telefono_Estab'] != '') {
			$tbl.= 'Telefono establecimiento: ' . $sucursal[0]['Telefono_Estab'].'<br>';
			$pdf->Row($arr, 10);
		}
		if ($sucursal[0]['Email_Establecimiento'] != '.' && $sucursal[0]['Email_Establecimiento'] != '') {
			$tbl.= 'Email establecimiento: ' . $sucursal[0]['Email_Establecimiento'].'<br>';
		}
		if ($sucursal[0]['Cta_Establecimiento'] != '.' && $sucursal[0]['Cta_Establecimiento'] != '') {
			$tbl.= 'Cta_Establecimiento: ' . $sucursal[0]['Cta_Establecimiento'].'<br>';
		}


		if ($sucursal[0]['Placa_Vehiculo'] != '.' && $sucursal[0]['Placa_Vehiculo'] != '') {
			$tbl.= 'Placas: ' . $sucursal[0]['Placa_Vehiculo'].'<br>';
		}
	}

	//================================Inicio INFORMACION ADICIONAL=======================
	if (isset($datos[0]['Cliente']) && $datos[0]['Razon_Social'] != $datos[0]['Cliente']) {
		$tbl.='Beneficiario: ' . $datos[0]['Cliente'] . '<br>';
		if (isset($datos[0]['CI_RUC'])) {
			$tbl.= 'Codigo: ' . $datos[0]['CI_RUC'] . '<br>';
		}
	}
	if (isset($datos[0]['Grupo']) && isset($datos[0]['Curso']) && isset($datos[0]['DireccionC']) && $datos[0]['Grupo'] != G_NINGUNO && $datos[0]['Curso'] != $datos[0]['DireccionC'] && $datos[0]['Imp_Mes']) {
		$tbl.= 'Grupo: ' . $datos[0]['Grupo'] . "-" . $datos[0]['Curso'] . '<br>';
	}
	if (isset($datos[0]['EmailC']) && strlen($datos[0]['EmailC']) > 1 && strpos($datos[0]['EmailC'], "@") > 0) {
		$tbl.= 'Email: ' . $datos[0]['EmailC'] . '<br>';
	}
	if (isset($datos[0]['EmailR']) && strlen($datos[0]['EmailR']) > 1 && strpos($datos[0]['EmailR'], "@") > 0 && strpos($datos[0]['EmailC'], $datos[0]['EmailR']) === false) {
		$tbl.= 'Email2: ' . $datos[0]['EmailR'] . '<br>';
	}
	if (isset($datos[0]['Contacto']) && strlen($datos[0]['Contacto']) > 1) {
		$tbl.= 'Referencia: ' . $datos[0]['Contacto'] . '<br>';
	}
	if (isset($datos[0]['TelefonoC']) && strlen($datos[0]['TelefonoC']) > 1) {
		$tbl.= 'Teléfono: ' . $datos[0]['TelefonoC'] . '<br>';
	}
	if (strlen($datos[0]['Nota']) > 1) {
		$tbl.= 'Nota: ' . $datos[0]['Nota'] . '<br>';
	}
	if (strlen($datos[0]['Observacion']) > 1) {
		$tbl.= 'Observacion: ' . $datos[0]['Observacion'] . '<br>';
	}

	$tbl.='</td><td colspan="3"  style="border:0.1px solid rgb(64,64,64);">
		<table cellpadding="1">';


	//================================Inicio INFORMACION DE ABONOS=======================
	$fechas_abonos = $detalle_abonos = $monto_abonos = "";
	// print_r($abonos);die();
	if (count($abonos)>0 ) {
		foreach ($abonos as $key => $value) {
			$tbl.='<tr>
				<td width="45px">'.$value['Fecha']->format('Y-m-d') .'</td>
				<td width="96">'.$value['Banco'] .'</td>
				<td width="60" align="right">'.$value['Abono'] .'</td>
			</tr>';
		}
	}

	$tbl.='</table></td></tr>
	<tr>
		<td colspan="4"  style="border:0.1px solid rgb(64,64,64);">
		'.$_SESSION['INGRESO']['LeyendaFA'].'
		</td>
	</tr>

	</table>';

	$pdf->writeHTML($tbl, true, false, false, false, '');
	
	
	//================================Fin INFORMACION ADICIONAL=======================



	//========================================= cuadro totales======================================

	$pdf->SetXY(140,$posicion_datos_adicionales);

	$sub_con_iva = 0;
	$sub_sin_iva = 0;
	foreach ($detalle as $key => $value) {

		// print_r($value);
		if (number_format($value['Total_IVA'], 2, '.', '') != '0.00') {
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
	if ($_SESSION['INGRESO']['Servicio'] != 0) {
		$arr = "SERVICIO " . $_SESSION['INGRESO']['Servicio'] . "%:";
	} else {
		$arr = "SERVICIO:";
	}
	// print_r($bai.'-'.$ba0);


	$tbl='<table  style="border:0.1px solid rgb(64,64,64);" cellpadding="1" >
			<tr>
				<td width="225px"  style="border:0.1px solid rgb(64,64,64);">
					<table cellpadding="1">
						<tr>
							<td width="175px">SUBTOTAL '.$imp.'%:</td>
							<td width="50px" align="right">'.number_format( $bai,'2','.','').'</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="225px" style="border:0.1px solid rgb(64,64,64);">
					<table cellpadding="1">
						<tr>
							<td width="175px">SUBTOTAL 0%:</td>
							<td width="50px" align="right">'.number_format( $ba0,'2','.','').'</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="225px" style="border:0.1px solid rgb(64,64,64);">
					<table cellpadding="1">
						<tr>
							<td width="175px">TOTAL DESCUENTO:</td>
							<td width="50px" align="right">'.number_format($descu,'2','.','').'</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="225px" style="border:0.1px solid rgb(64,64,64);">
					<table cellpadding="1">
						<tr>
							<td width="175px">SUBTOTAL NO OBJETO DE IVA: 0%:</td>
							<td width="50px" align="right">0.00</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="225px" style="border:0.1px solid rgb(64,64,64);">
					<table cellpadding="1">
						<tr>
							<td width="175px">SUBTOTAL EXENTO DE IVA:</td>
							<td width="50px" align="right">0.00</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="225px" style="border:0.1px solid rgb(64,64,64);">
					<table cellpadding="1">
						<tr>
							<td width="175px">SUBTOTAL SIN IMPUESTOS:</td>
							<td width="50px" align="right">'.number_format($ba0 - $descu,'2','.','').'</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="225px" style="border:0.1px solid rgb(64,64,64);">
					<table cellpadding="1">
						<tr>
							<td width="175px">ICE:</td>
							<td width="50px" align="right">0.00</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="225px" style="border:0.1px solid rgb(64,64,64);">
					<table cellpadding="1">
						<tr>
							<td width="175px">IVA ' . $imp . '%:</td>
							<td width="50px" align="right">'.number_format($vimp1,'2','.','').'</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="225px" style="border:0.1px solid rgb(64,64,64);">
					<table cellpadding="1">
						<tr>
							<td width="175px">IVA 0%:</td>
							<td width="50px" align="right">'.number_format($vimp0,'2','.','').'</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="225px" style="border:0.1px solid rgb(64,64,64);">
					<table cellpadding="1">
						<tr>
							<td width="175px">'.$arr.'</td>
							<td width="50px" align="right">'.number_format($datos[0]['Servicio'],'2','.','').'</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="225px" style="border:0.1px solid rgb(64,64,64);">
					<table cellpadding="1">
						<tr>
							<td width="175px">VALOR TOTAL:</td>
							<td width="50px" align="right">'.number_format($datos[0]['Total_MN'],'2','.','').'</td>
						</tr>
					</table>
				</td>
			</tr>

		</table>';


	$pdf->writeHTML($tbl, true, false, false, false, '');

	$texto ="Debo y Pagaré incondicionalmente a la orden de ".$_SESSION['INGRESO']['Razon_Social']." el valor expresado en este documento mas el máximo interés legal por mora, vigente en el Sistema Financiero
Nacional desde la fecha de vencimiento, SIN PROTESTO, exímase de presentación para el pago así como la falta de estos hechos. Renuncio fuero y domicilio y me someto a los jueces competentes
de la ciudad de Quito, Distrito Metropolitano, y al trámite verbal sumario o ejecutivo a elección de ".$_SESSION['INGRESO']['Razon_Social']." o de sus cesionarios. Acepto que ".$_SESSION['INGRESO']['Razon_Social'].",
ceda y transfiera en cualquier momento los derechos que emanan de la presente factura-pagaré sin que sea necesaria notificación algún ni nueva aceptación de mi parte. Suscribo la presente
factura-pagaré en conformidad con todos sus términos";

	$pdf->MultiCell(190, 4,$texto, $border, '', 0, 1, '', '', true);

	$pdf->SetY($pdf->GetY()+2);
	$pdf->Line($pdf->GetX(), $pdf->GetY(), 190 ,$pdf->GetY());
	$pdf->SetY($pdf->GetY()+2);

	$texto = "RESOLUCION: El Articulo 97 del Reglamento para la Aplicacion de la Ley de Regimen Tributario Interno manifiesta que los agentes de retencion de impuestos deberan extender un comprobante de
retencion dentro del plazo maximo de CINCO dias de recibido el comprobante de venta.";

	$pdf->MultiCell(190, 4,$texto, $border, '', 0, 1, '', '', true);

		//========================================= fin cuadro totales======================================



	if ($imp1 == false || $imp1 == 0) {
		$pdf->Output('Facturas_'.$suc . '_' . $punto . '_' . generaCeros($datos[0]['Factura'], 9).'.pdf', 'I');
	}
	if ($imp1 == 1) {
		if(!file_exists(dirname(__DIR__, 2) . '/TEMP/'))
		{
			mkdir(dirname(__DIR__, 2) . '/TEMP/', 0777);
		}
		$pdf->Output(dirname(__DIR__, 2) . '/TEMP/' . $datos[0]['Serie'] . '-' . generaCeros($datos[0]['Factura'], 7) . '.pdf','F');

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

}
?>