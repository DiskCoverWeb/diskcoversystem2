<?php 
require_once('tcpdf_include.php');
class reportes_varios
{
	
	function __construct()
	{
		// code...
	}

	function etiqueta_recepcion_BAQ($datos)
	{
		$pdf  = new TCPDF();

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetMargins(5,5,5);

		// set auto page breaks
		$pdf->SetAutoPageBreak(FALSE);

		// set image scale factor
		// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


		$pdf->AddPage('L', array(60, 20));
		// $pdf->AddPage('L','A4');
		$style = array(
		    // 'border' => 2,
		    'vpadding' => 'auto',
		    'hpadding' => 'auto',
		    'fgcolor' => array(0,0,0),
		    'bgcolor' => false, //array(255,255,255)
		    'module_width' => 1, // width of a single module in points
		    'module_height' => 1 // height of a single module in points
		);

		// print_r($datos);die();				
		$pdf->SetFont('times', 'B',8);
		$pdf->SetXY(3,3);
		$pdf->writeHTMLCell(44, 3,15,2,$datos[0]['Cliente'].'<br>'.$datos[0]['Codigo_qr'],0, 1, false, true, 'C', false);
		// $pdf->MultiCell(55, 3,$datos[0]['Cliente'],0,'C');
		// $pdf->Text(20, 10,$datos[0]['Codigo_qr']);
		$pdf->write2DBarcode($datos[0]['Codigo_qr'], 'QRCODE,L', 3, 5, 14, 14, $style, 'N');
		$pdf->Output($datos[0]['Codigo_qr'].'.pdf', 'I');
	

	}

	function etiqueta_clasificacion_BAQ($datos)
	{
		$pdf  = new TCPDF();

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetMargins(5,5,5);

		// set auto page breaks
		$pdf->SetAutoPageBreak(FALSE);

		// set image scale factor
		// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		foreach ($datos as $key => $value) {
			// print_r($value);die();
			$pdf->AddPage('L', array(60, 20));
			// $pdf->AddPage('L','A4');
			$style = array(
			    // 'border' => 2,
			    'vpadding' => 'auto',
			    'hpadding' => 'auto',
			    'fgcolor' => array(0,0,0),
			    'bgcolor' => false, //array(255,255,255)
			    'module_width' => 1, // width of a single module in points
			    'module_height' => 1 // height of a single module in points
			);

			// print_r($datos);die();				
			$pdf->SetFont('times', 'B',8);
			$pdf->SetXY(3,3);
			$pdf->MultiCell(55, 3,$value['Producto'],0,'L');
			$pdf->Text(16, 10,$value['Codigo_Barra']);
			$pdf->write2DBarcode($value['Codigo_Barra'], 'QRCODE,L', 3, 5, 14, 14, $style, 'N');
		}

		$pdf->Output($value['Codigo_Barra'].'.pdf', 'I');
	

	}
}