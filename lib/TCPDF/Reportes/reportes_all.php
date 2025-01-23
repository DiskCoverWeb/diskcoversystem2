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
	private $sucursal;
	public function SetearDatos($datos,$sucursal)
	{
		$this->datos = $datos;
		$this->sucursal = $sucursal;
	}

	public function Header() {
        // Logo

        // print_r($this->datos[0]);die();
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
		if ($suc != '001' && count($this->sucursal) > 0 && $this->sucursal[0]['Nombre_Establecimiento'] != '.' && $this->sucursal[0]['Nombre_Establecimiento'] != '') {
			$this->SetX(14);
			$this->MultiCell(84, 2,'Direccion de establecimiento / Sucursal', $border, '', 0, 1, '', '', true);
			$this->MultiCell(84, 2,$this->sucursal[0]['Direccion_Establecimiento'], $border, '', 0, 1, '', '', true);
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
		$this->writeHTMLCell(105, 3,'', '','<span style="color:red">Agente de Retención Resolución:' . $agente.'</span>', $border, 1, false, true, '', false);
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
		case 'RE':
			$this->MultiCell($row_col, 2,'Retencion No.', $border, '', 0, 1, '', '', true);
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
	}
	$this->writeHTMLCell(52, 5,'', '','<span style="color:red">'.$rimpe.'</span>', $border, 1, false, true, '', false);
	
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
		$pdf->SetearDatos($datos,$sucursal);
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

		$pdf->StartTransform();
		$cuardo_3_X=13;
		$cuardo_3_Y=55;
		$medita_total_3 = 190;
		$pdf->Rect($cuardo_3_X,$cuardo_3_Y,$medita_total_3, 19, 'D');
		$pdf->StopTransform();


		//============================================cuadro cliente ==========================================

	$pdf->SetFont('helvetica', 'B', 7);
	$cuardo_3_X  = $cuardo_3_X+1;
	$pdf->SetXY($cuardo_3_X,$cuardo_3_Y);
	$row_1 = 160;
	$pdf->MultiCell($row_1, 3,'Razón social/nombres y apellidos:', $border, '', 0, 1, '', '', true);
	$pdf->SetXY($cuardo_3_X+$row_1,$cuardo_3_Y);
	$pdf->MultiCell($medita_total_3-$row_1, 3,'Identificación:', $border, '', 0, 1, '', '', true);

	$pdf->SetX($cuardo_3_X,$cuardo_3_Y+$pdf->GetY());
	$posicion_row  = $pdf->GetY();
	$pdf->MultiCell($row_1, 3,mb_convert_encoding($datos[0]['Razon_Social'], 'ISO-8859-1', 'UTF-8'), $border, '', 0, 1, '', '', true);
	$pdf->SetXY($cuardo_3_X+$row_1,$posicion_row);
	$pdf->MultiCell($medita_total_3-$row_1, 3,$datos[0]['RUC_CI'], $border, '', 0, 1, '', '', true);

	$pdf->SetX($cuardo_3_X);
	$row_1 = 120;
	$posicion_row  = $pdf->GetY();
	$pdf->MultiCell($row_1, 4,'Dirección:'. $datos[0]['Direccion_RS'], $border, '', 0, 1, '', '', true);
	$pdf->SetXY($cuardo_3_X+$row_1,$posicion_row);
	$pdf->MultiCell(($medita_total_3-$row_1)/2, 4,'Fecha emisión: ' . $datos[0]['Fecha']->format('Y-m-d'), $border, '', 0, 1, '', '', true);
	$pdf->SetXY($cuardo_3_X+$row_1+($medita_total_3-$row_1)/2,$posicion_row);
	if(isset($datos[0]['Fecha_V']))
	{
		$pdf->MultiCell(($medita_total_3-$row_1)/2, 4,'Fecha pago: ' . $datos[0]['Fecha_V']->format('Y-m-d'), $border, '', 0, 1, '', '', true);
	}


	$pdf->SetX($cuardo_3_X);
	$row_1 = 120;
	$DiasPago = strval(strtotime($datos[0]['Fecha_V']->format('Y-m-d')) - strtotime($datos[0]['Fecha']->format('Y-m-d'))) / (60 * 60 * 24);
	$posicion_row  = $pdf->GetY();
	$mon= '';
	if ('DOLAR' == 'DOLAR') {
		$mon = 'USD';
	} else {
		$mon = 'USD';
		//se busca otras monedas
	}
	$pdf->MultiCell($row_1, 2,'FORMA DE PAGO: ' . $datos[0]['Tipo_Pago'], $border, '', 0, 1, '', '', true);
	$pdf->SetXY($cuardo_3_X+$row_1,$posicion_row);
	$pdf->MultiCell(($medita_total_3-$row_1)/2, 3,'MONTO '.$mon.'              ' . number_format($datos[0]['Total_MN'], 2, '.', ','), $border, '', 0, 1, '', '', true);
	$pdf->SetXY($cuardo_3_X+$row_1+($medita_total_3-$row_1)/2,$posicion_row);
	$pdf->MultiCell(($medita_total_3-$row_1)/2, 3,'Condición de venta: ' . $DiasPago . ' días', $border, '', 0, 1, '', '', true);


	$pdf->SetX($cuardo_3_X);
	$row_1 = 120;
	$posicion_row  = $pdf->GetY();
	$pdf->MultiCell($row_1,4,'', $border, '', 0, 1, '', '', true);
	$pdf->SetXY($cuardo_3_X+$row_1,$posicion_row);
	$pdf->MultiCell($medita_total_3-$row_1, 4,'No. Orden de Compra: 0', $border, '', 0, 1, '', '', true);


	//================================fin cuadro cliente========================================================= 


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

function imprimirDocEle_guia($datos, $detalle, $educativo, $matri = false, $nombre = "", $formato = null, $nombre_archivo = null, $va = null, $imp1 = false, $sucursal = array())
{

		$border = 0;
		$punto = substr($datos[0]['Serie'], 3, 6);
		$suc = substr($datos[0]['Serie'], 0, 3);

		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetearDatos($datos,$sucursal);
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


		$pdf->StartTransform();
		$cuardo_3_X=13;
		$cuardo_3_Y=55;
		$medita_total_3 = 190;
		$pdf->Rect($cuardo_3_X,$cuardo_3_Y,$medita_total_3, 15, 'D');
		$pdf->StopTransform();


		//============================================cuadro cliente ==========================================

		$pdf->SetFont('helvetica', 'B', 7);
		$cuardo_3_X  = $cuardo_3_X+1;
		$pdf->SetXY($cuardo_3_X,$cuardo_3_Y);
		$row_1 = 160;
		$pdf->MultiCell($row_1, 3,'Razón social/nombres y apellidos:', $border, '', 0, 1, '', '', true);
		$pdf->SetXY($cuardo_3_X+$row_1,$cuardo_3_Y);
		$pdf->MultiCell($medita_total_3-$row_1, 3,'Identificación:', $border, '', 0, 1, '', '', true);

		$pdf->SetX($cuardo_3_X,$cuardo_3_Y+$pdf->GetY());
		$posicion_row  = $pdf->GetY();
		$pdf->MultiCell($row_1, 3,mb_convert_encoding($datos[0]['Razon_Social'], 'ISO-8859-1', 'UTF-8'), $border, '', 0, 1, '', '', true);
		$pdf->SetXY($cuardo_3_X+$row_1,$posicion_row);
		$pdf->MultiCell($medita_total_3-$row_1, 3,$datos[0]['RUC_CI'], $border, '', 0, 1, '', '', true);

		$posicion_row  = $pdf->GetY();
		$pdf->SetX($cuardo_3_X);
		$row_1 = 140;
		$pdf->MultiCell($row_1, 4,'Dirección:'. $datos[0]['Direccion_RS'], $border, '', 0, 1, '', '', true);
		$pdf->SetXY($cuardo_3_X+$row_1,$posicion_row);
		$pdf->MultiCell(($medita_total_3-$row_1), 4,'Motivo de traslado:'. $datos[0]['Observacion'], $border, '', 0, 1, '', '', true);

		$posicion_row  = $pdf->GetY();
		$pdf->SetX($cuardo_3_X);
		$row_1 = 110;
		$pdf->MultiCell($row_1, 4,'Autorizacion:'. $datos[0]['Autorizacion'], $border, '', 0, 1, '', '', true);
		$pdf->SetXY($cuardo_3_X+$row_1,$posicion_row);
		$pdf->MultiCell((($medita_total_3-$row_1)/2), 4,'Factura No: ' . $datos[0]['Serie'] . '-' . generaCeros($datos[0]['Factura'], 9), $border, '', 0, 1, '', '', true);
		$cuardo_3_X = 54;
		$pdf->SetXY($cuardo_3_X+$row_1,$posicion_row);
		$pdf->MultiCell((($medita_total_3-$row_1)/2), 4,'Fecha emison: ' . $datos[0]['Fecha']->format('Y-m-d'), $border, '', 0, 1, '', '', true);

		$posicion_row = $pdf->GetY()+1;
		$ini_y = $posicion_row;
		if(isset($datos[0]['Fecha_V']))
		{
			$pdf->SetXY($cuardo_3_X+$row_1+($medita_total_3-$row_1)/2,$posicion_row);
			$pdf->MultiCell(($medita_total_3-$row_1)/2, 4,'Fecha pago: ' . $datos[0]['Fecha_V']->format('Y-m-d'), $border, '', 0, 1, '', '', true);
		}



	//============================================cuadro transpor ==========================================
		$row_1 = 160;
		$cuardo_3_X = 13;
		$pdf->SetY($posicion_row);
		$pdf->MultiCell($row_1, 3,'Razón social/nombres y apellidos:(Transportista)', $border, '', 0, 1, '', '', true);
		$pdf->SetXY($cuardo_3_X+$row_1,$posicion_row);
		$pdf->MultiCell($medita_total_3-$row_1, 3,'Identificación:', $border, '', 0, 1, '', '', true);

		
		$posicion_row = $pdf->GetY();
		$pdf->MultiCell($row_1, 3,mb_convert_encoding($datos[0]['Comercial'], 'ISO-8859-1', 'UTF-8'), $border, '', 0, 1, '', '', true);
		$pdf->SetXY($cuardo_3_X+$row_1,$posicion_row);
		$pdf->MultiCell($medita_total_3-$row_1, 3,$datos[0]['CIRUC_Comercial'], $border, '', 0, 1, '', '', true);


		$posicion_row = $pdf->GetY();
		$row_1 = 100;
		$pdf->MultiCell($row_1, 3,"Punto de Partida:", $border, '', 0, 1, '', '', true);
		$pdf->SetXY($cuardo_3_X+$row_1,$posicion_row);
		$pdf->MultiCell((($medita_total_3-$row_1)/3), 3,"Fecha Inicio:", $border, '', 0, 1, '', '', true);

		$pdf->SetXY($cuardo_3_X+$row_1+30,$posicion_row);
		$pdf->MultiCell((($medita_total_3-$row_1)/3), 3,"Fecha fin: ", $border, '', 0, 1, '', '', true);
		
		$pdf->SetXY($cuardo_3_X+$row_1+60,$posicion_row);
		$pdf->MultiCell((($medita_total_3-$row_1)/3), 3,"Placa: ", $border, '', 0, 1, '', '', true);



		$posicion_row = $pdf->GetY();
		$row_1 = 100;
		$pdf->MultiCell($row_1, 3, $datos[0]['CiudadGRI'], $border, '', 0, 1, '', '', true);
		$pdf->SetXY($cuardo_3_X+$row_1,$posicion_row);
		$pdf->MultiCell((($medita_total_3-$row_1)/3), 3,$datos[0]['FechaGRI']->format('Y-m-d'), $border, '', 0, 1, '', '', true);

		$pdf->SetXY($cuardo_3_X+$row_1+30,$posicion_row);
		$pdf->MultiCell((($medita_total_3-$row_1)/3), 3,$datos[0]['FechaGRF']->format('Y-m-d'), $border, '', 0, 1, '', '', true);
		
		$pdf->SetXY($cuardo_3_X+$row_1+60,$posicion_row);
		$pdf->MultiCell((($medita_total_3-$row_1)/3), 3, $datos[0]['Placa_Vehiculo'], $border, '', 0, 1, '', '', true);

		$posicion_row = $pdf->GetY()+1;

	//================================fin cuadro trasnpor========================================================= 

	//============================================cuadro llegada==========================================
		$row_1 = 160;
		$cuardo_3_X = 13;
		$pdf->SetY($posicion_row);
		$pdf->MultiCell($row_1, 3,'Razón social/nombres y apellidos:(Punto de llegada)', $border, '', 0, 1, '', '', true);
		$pdf->SetXY($cuardo_3_X+$row_1,$posicion_row);
		$pdf->MultiCell($medita_total_3-$row_1, 3,'Identificación:', $border, '', 0, 1, '', '', true);

		
		$posicion_row = $pdf->GetY();
		$pdf->MultiCell($row_1, 3,mb_convert_encoding($datos[0]['Entrega'], 'ISO-8859-1', 'UTF-8'), $border, '', 0, 1, '', '', true);
		$pdf->SetXY($cuardo_3_X+$row_1,$posicion_row);
		$pdf->MultiCell($medita_total_3-$row_1, 3,$datos[0]['CIRUC_Entrega'], $border, '', 0, 1, '', '', true);


		$posicion_row = $pdf->GetY();
		// $row_1 = 100;
		$pdf->MultiCell($medita_total_3, 3,'Destino: De ' . $datos[0]['CiudadGRI'] . ' a ' . $datos[0]['CiudadGRF'] . ', ' . $datos[0]['Lugar_Entrega'], $border, '', 0, 1, '', '', true);

		$fin_y = $pdf->GetY();

		// print_r($ini_y.'--'.$fin_y);die();
		$pdf->Rect($cuardo_3_X,$ini_y,$medita_total_3, $fin_y-$ini_y, 'D');
		$pdf->StopTransform();

	//================================fin cuadro llegada========================================================= 


	//================================cuadro detalle========================================================= 
		$posicion_row = $pdf->GetY()+1;

		$pdf->SetY($posicion_row);
		$ini_y = $posicion_row;

		$tbl = '<table border="1"  cellpadding="1">
					<tr>
						<td width="100px" style="border:0.1px solid rgb(64,64,64);">Codigo Unitario</td>
						<td width="100px" style="border:0.1px solid rgb(64,64,64);">Codigo Auxiliar</td>
						<td width="60px" style="border:0.1px solid rgb(64,64,64);">Cantidad</td>
						<td width="414px" style="border:0.1px solid rgb(64,64,64);">Descripción</td>
					</tr>';
		$imp_mes = $datos[0]['Imp_Mes'];
		foreach ($detalle as $key => $value) {

			if ($imp_mes != 0) {
				$value['Producto'] = $value['Producto'] . ' ' . $value['Mes'] . '/' . $value['Ticket'];
			}
			$tbl.='<tr>
						<td width="100px" style="border:0.1px solid rgb(64,64,64);">'.$value['Codigo'].'</td>
						<td width="100px" style="border:0.1px solid rgb(64,64,64);"></td>
						<td width="60px" style="border:0.1px solid rgb(64,64,64);" align="right">'.number_format($value['Cantidad'],2, '.', '').'</td>
						<td width="414px" style="border:0.1px solid rgb(64,64,64);">'.$value['Producto'].'</td>
					</tr>';
		}
				$tbl.='</table>';

		
		$pdf->writeHTML($tbl, true, false, false, false, '');


	//===================================================fin cuadrpo detalle======================================

	//====================================================cuadro datos adicionales======================
		$posicion_row = $pdf->GetY()+1;
		$ini_y = $posicion_row;

		$tbl = '<table cellpadding="1">
					<tr>
						<td width="674px" border="1">INFORMACION ADICIONAL</td>
					</tr>
					<tr>
					<td  border="1">
						<table>
							<tr border="1">
								<td width="224px">Email: ' . $educativo[0]['Email'].'</td>
								<td width="224px">Email2: ' . $educativo[0]['Email_tras'].'</td>
								<td width="224px">Telefono:' . $educativo[0]['Telefono'].'</td>						
							</tr>
						</table>
					</td>
					</tr>';

		$tbl.='</table>';

		
		$pdf->writeHTML($tbl, true, false, false, false, '');

		

	/*
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

*/

	if ($imp1 == false || $imp1 == 0) {
		$pdf->Output('Guia_remision_'.$datos[0]['Serie_GR'] . '-' . generaCeros($datos[0]['Remision'], 7).'.pdf', 'I');
	}
	if ($imp1 == 1) {
		$pdf->Output('F', dirname(__DIR__, 2) . '/TEMP/' . $datos[0]['Serie_GR'] . '-' . generaCeros($datos[0]['Remision'], 7) . '.pdf');

		// $pdf->Output('TEMP/'.$nombre.'.pdf','F'); 
	}
}



function imprimirDocEle_ret($datos, $detalle, $nombre_archivo = null, $imp1 = false,$sucursal = array())
{
	// print_r($datos);die();
		$border = 0;
		$punto = substr($datos[0]['Serie_Retencion'], 3, 6);
		$suc = substr($datos[0]['Serie_Retencion'], 0, 3);
		$datos[0]['Serie'] = $datos[0]['Serie_Retencion'];

		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetearDatos($datos,$sucursal);
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

		$pdf->StartTransform();
		$cuardo_3_X=13;
		$cuardo_3_Y=55;
		$medita_total_3 = 190;
		$pdf->Rect($cuardo_3_X,$cuardo_3_Y,$medita_total_3, 14, 'D');
		$pdf->StopTransform();

		//============================================cuadro cliente ==========================================

		$pdf->SetFont('helvetica', 'B', 7);
		$cuardo_3_X  = $cuardo_3_X+1;
		$pdf->SetXY($cuardo_3_X,$cuardo_3_Y);
		$row_1 = 160;
		$pdf->MultiCell($row_1, 3,'Razón social/nombres y apellidos:', $border, '', 0, 1, '', '', true);
		$pdf->SetXY($cuardo_3_X+$row_1,$cuardo_3_Y);
		$pdf->MultiCell($medita_total_3-$row_1, 3,'Identificación:', $border, '', 0, 1, '', '', true);

		$pdf->SetX($cuardo_3_X,$cuardo_3_Y+$pdf->GetY());
		$posicion_row  = $pdf->GetY();
		$pdf->MultiCell($row_1, 3,mb_convert_encoding($datos[0]['Razon_Social'], 'ISO-8859-1', 'UTF-8'), $border, '', 0, 1, '', '', true);
		$pdf->SetXY($cuardo_3_X+$row_1,$posicion_row);
		$pdf->MultiCell($medita_total_3-$row_1, 3,$datos[0]['RUC_CI'], $border, '', 0, 1, '', '', true);

		$posicion_row  = $pdf->GetY();
		$pdf->SetX($cuardo_3_X);
		$row_1 = 140;
		$pdf->MultiCell($row_1, 4,'Dirección:'. $datos[0]['Direccion'], $border, '', 0, 1, '', '', true);
		$pdf->SetXY($cuardo_3_X+$row_1,$posicion_row);
		$pdf->MultiCell(($medita_total_3-$row_1), 4,'Periodo Fiscal:'.  $datos[0]['Fecha']->format('Y-m-d'), $border, '', 0, 1, '', '', true);

		$posicion_row  = $pdf->GetY();
		$pdf->SetX($cuardo_3_X);
		// $row_1 = 140;
		$pdf->MultiCell($row_1, 4,'Documento tipo Factura No::'. $datos[0]['Establecimiento'] . $datos[0]['PuntoEmision'] . '-' . generaCeros($datos[0]['Secuencial'], 9), $border, '', 0, 1, '', '', true);

		$pdf->SetXY($cuardo_3_X+$row_1,$posicion_row);
		$pdf->MultiCell((($medita_total_3-$row_1)), 4,'Fecha emison: ' . $datos[0]['Fecha']->format('Y-m-d'), $border, '', 0, 1, '', '', true);
	
		$posicion_row = $pdf->GetY()+2;
		$ini_y = $posicion_row;
		$pdf->SetY($posicion_row);

		$impuestos = '';
		$Descripcion = '';
		$Base = '';
		$Porcentaje = '';
		$ValorRet = '';
		$valRet = 0;
		foreach ($detalle as $key => $value) {
			$impuestos.= '<tr><td>'.$value['Concepto'].'</td></tr>';
			$Descripcion.= '<tr><td>'.$value['CodRet'].'</td></tr>';
			$Base.= '<tr><td align="right">'.number_format($value['BaseImp'], 2, '.', '').'</td></tr>';
			$Porcentaje.= '<tr><td>'.(number_format($value['Porcentaje'], 2, '.', '')*100).'%</td></tr>';
			$ValorRet.= '<tr><td align="right">'. number_format($value['ValRet'], 2, '.', '').'</td></tr>';
			// print_r($value);die();

			$valRet += $value['ValRet'];
		}

		$pdf->SetFont('helvetica', '', 7);
		$tbl = '<table cellpadding="1" border="1">
					<tr>
						<td style="width:70px"><b>Impuesto</b></td>
						<td style="width:345px"><b>Descripcion</b></td>
						<td style="width:70px"><b>Codigo Retencion</b></td>
						<td style="width:60px"><b>Base Imponible</b></td>
						<td style="width:70px"><b>Porcentaje Retenido</b></td>
						<td style="width:60px"><b>Valor Retenido</b></td>

					</tr>
					<tr>
						<td border="1">
							<table></table>
						</td>
						<td border="1">
							<table cellpadding="1">'.$impuestos.'</table>
						</td>
						<td border="1">
							<table cellpadding="1">'.$Descripcion.'</table>
						</td>
						<td  border="1">
							<table cellpadding="1">'.$Base.'</table>
						</td>
						<td  border="1">
							<table cellpadding="1">'.$Porcentaje.'</table>
						</td>
						<td  border="1">
							<table cellpadding="1">'.$ValorRet.'</table>
						</td>
					</tr>';

		$tbl.='</table>';		
		$pdf->writeHTML($tbl, true, false, false, false, '');

		//====================================================cuadro datos adicionales======================

		$pdf->SetFont('helvetica', '', 7);
		$posicion_row = $pdf->GetY()+1;
		$ini_y = $posicion_row;

		$tbl = '<table border="1" cellpadding="2">
					<tr>
						<td width="484px" border="1"><b>INFORMACION ADICIONAL</b></td>
					</tr>
					<tr>
						<td>Telefono: ' . $datos[0]['Email'] . ', Tipo Comprobante:' . $datos[0]['TP'] . '-' . $datos[0]['Numero'] . ', Email:' . $datos[0]['Email'].'
						</td>							
					</tr>
					
				</table>';
// <tr>
// 						// <td>'.$_SESSION['INGRESO']['LeyendaFA'].'</td>							
// 					</tr>
		
		$pdf->writeHTML($tbl, true, false, false, false, '');

		$y = $pdf->GetY();

		$anc = ($y-$posicion_row)+22;

		// print_r($anc);die();

		$pdf->SetFont('helvetica', '', 7);
		$pdf->SetXY(151,$ini_y-1);
		$tbl = '<table  cellpadding="1" width="185px">
					<tr>
						<td>
							<table border="1" cellpadding="2">
								<tr>
									<td width="124px" style="font-size:10px;" height="'.$anc.'px"><br><br/>TOTAL RETENIDO:</td>
									<td width="62px" style="font-size:10px;" align="right" height="'.$anc.'px"><br><br/>'.number_format($valRet, 2, '.', '').'</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>';

		
		$pdf->writeHTML($tbl, true, false, false, false, '');

		


		$pdf->Output('Guia_remision_.pdf', 'I');


	if ($imp1 == false || $imp1 == 0) {
		$pdf->Output('Retencion_' . $datos[0]['Serie_Retencion'] . '-' . generaCeros($datos[0]['SecRetencion'], 9) . '.pdf', 'I');
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