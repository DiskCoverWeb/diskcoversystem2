<?php 
require_once('tcpdf_include.php');
require_once(dirname(__DIR__,3)."/php/db/db1.php");
class reportes_varios
{
	private $db;
	function __construct()
	{
		$this->db = new db();
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

function Imprimir_Punto_Venta($info,$abonos,$descagar=true,)
{	
	$filasTA = $abonos;

	$sql = "SELECT Usuario
	FROM Accesos 
	WHERE Codigo = '".$info['lineas'][0]['CodigoU']."'";

	$filasUsuario = $this->db->datos($sql);


	$CantBlancos = "";
	$Grafico_PV = Leer_Campo_Empresa("Grafico_PV");
	$ancho_PV = Leer_Campo_Empresa("Cant_Ancho_PV");
	$Encabezado_PV = Leer_Campo_Empresa("Encabezado_PV");
	
	$Total = 0;
	$Total_IVA = 0;
	$Codigo1 = $info['factura'][0]['CodigoU'];
	$info['Cliente'] = $info['factura'][0]['Cliente'];
	$Codigo1 = substr($Codigo1, 0, 4)."X".substr($Codigo1, strlen($Codigo1)-1, 2);
	$Producto = "";
	if($ancho_PV < 26){
		$ancho_PV = 26;
	}
	if(isset($_SESSION['INGRESO']['Logo_Tipo']))
		{
		$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		//si es jpg
		$src = dirname(__DIR__,3).'/img/logotipos/'.$logo.'.jpg'; 
		if(!file_exists($src))
		{
			$src = dirname(__DIR__,3).'/img/logotipos/'.$logo.'.gif'; 
			if(!file_exists($src))
			{
				$src = dirname(__DIR__,3).'/img/logotipos/'.$logo.'.png'; 
				if(!file_exists($src))
				{
					$logo="diskcover_web";
					$src= dirname(__DIR__,3).'/img/logotipos/'.$logo.'.gif';

				}

			}

		}
	}
// print_r($info);die();

$src = str_replace(dirname(__DIR__,3),'', $src);

//3.77 = a 1 pixel

$margenesX = 6;
$margenesY = 2;
$anchoFact = $ancho_PV * 1.75;
$anchoImg = ($ancho_PV * 1.75)*3.77;
$altoImg = ($anchoFact * 0.38)*3.77;

$ticket  = '

<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial;
            font-size: 12px;
            width: 60mm;       /* Ancho seguro para 58mm de papel */
            margin: 0;         /* Elimina márgenes */
            padding: 5mm;        /* Elimina relleno */


        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Fuerza el ajuste de columnas */
            white-space: pre-wrap; 
        }
        td, th {
            padding: 2px;
            word-break: break-word; /* Rompe palabras largas */
            overflow: hidden;       /* Oculta contenido que exceda */
        }
    </style>
</head>
<body>
<pre>
<table style="font-family: Arial">
<tr><td colspan="3"><img src="../../../'.$src.'" style="width: 210px; height: '.$altoImg.'px;"></img></td></tr>';
if($Encabezado_PV){
if($_SESSION['INGRESO']['Nombre_Comercial']==$_SESSION['INGRESO']['Razon_Social'])
{
	$ticket.='<tr><td colspan="3" style="text-align: center;"><b>'.$_SESSION['INGRESO']['Razon_Social'].'</b></td></tr>';
	$ticket.='<tr><td colspan="3" style="text-align: center;"><b>'.'R.U.C '.$_SESSION['INGRESO']['RUC'].'</b></td></tr>';
	//$pdf->Ln(6);
}else if($info['factura'][0]['TC'] == 'DO' || $info['factura'][0]['TC'] == "NDO" || $info['factura'][0]['TC'] == "NDU"){
	$ticket.='<tr><td colspan="3" style="text-align: center;"><b>'.$_SESSION['INGRESO']['Razon_Social'].'</b></td></tr>';
	$ticket.='<tr><td colspan="3" style="text-align: center;"><b>'.'R.U.C '.$_SESSION['INGRESO']['RUC'].'</b></td></tr>';
	$ticket.='<tr><td colspan="3" style="text-align: center;"><b>'.'DONACIÓN DE ALIMENTOS'.'</b></td></tr>';
	$ticket.="<tr><td></td></tr><tr><td></td></tr>";

}else{
	$ticket.='<tr><td colspan="3">'.$_SESSION['INGRESO']['Razon_Social'].'</td></tr>';
	$ticket.='<tr><td colspan="3">'.$_SESSION['INGRESO']['Nombre_Comercial'].'</td></tr>';
	$ticket.='<tr><td colspan="3">'.'R.U.C '.$_SESSION['INGRESO']['RUC'].'</td></tr>';
}
	$ticket.='<tr><td colspan="3" style="text-align: justify;">Dirección: '.$_SESSION['INGRESO']['Direccion'].'</td></tr>';
	$ticket.='<tr><td colspan="3" style="text-align: justify;">Teléfono: '.$_SESSION['INGRESO']['Telefono1'].'</td></tr>';
}

if($Encabezado_PV){
if($info['factura'][0]['TC'] == "PV"){
	$ticket.='<tr><td colspan="3">T I C K E T   No. 000-000-'.str_pad($info['factura'][0]['Factura'], 7, '0', STR_PAD_LEFT).'</td></tr>';
}else if($info['factura'][0]['TC'] == "NV"){
	$ticket.='<tr><td colspan="3">Autorizacion del SRI No.</td></tr>';
	$ticket.='<tr><td colspan="3">'.$info['factura'][0]['Autorizacion'].'</td></tr>';
	$ticket.='<tr><td colspan="3">NOTA DE VENTA No. <span>'.$info['factura'][0]['Serie']."-".str_pad($info['factura'][0]['Factura'], 7, '0', STR_PAD_LEFT).'<span></td></tr>';
}else if($info['factura'][0]['TC'] == 'DO' || $info['factura'][0]['TC'] == "NDO" || $info['factura'][0]['TC'] == "NDU"){
	$ticket.='<tr><td colspan="3"><span style="font-size:11px; text-align: justify;">NOTA DE DONACIÓN No. <span style="color:red">'.$info['factura'][0]['Serie']."-".str_pad($info['factura'][0]['Factura'], 7, '0', STR_PAD_LEFT).'<span></span></td></tr>';
}else{
	$ticket.='<tr><td colspan="3">Autorizacion del SRI No.'.'</td></tr>';
	$ticket.='<tr><td colspan="3">'.$info['factura'][0]['Autorizacion'].'</td></tr>';
	$ticket.='<tr><td colspan="3"> FACTURA No. '.$info['factura'][0]['Serie']."-".str_pad($info['factura'][0]['Factura'], 7, '0', STR_PAD_LEFT).'</td></tr>';
	if(strlen($info['especial']) > 1){
		$ticket.='<tr><td colspan="3">Contribuyente Especial No. '.$info['especial'].'</td></tr>';
	}
	$ticket.='<tr><td colspan="3">OBLIGADO A LLEVAR CONTABILIDAD: '.$info['conta'].'</td></tr>';
}
}else{
$ticket.='<tr><td colspan="3">Transaccion ('.$info['factura'][0]['TC'].") No.".str_pad($info['factura'][0]['Factura'], 7, '0', STR_PAD_LEFT).'</td></tr>';
}

$ticket.='<tr><td colspan="3">Fecha de Emisión: '.$info['factura'][0]['Fecha']->format('Y/m/d').'</td></tr>';
	if($_SESSION['INGRESO']['IDEntidad'] == '65'){
		$ticket.="<tr><td colspan='3' style='text-align: justify;'>Atendido por: ".$filasUsuario[0]['Usuario'].'</td></tr>';
	}else{
		$ticket.="<tr><td colspan='3'>Hora de Proceso: ".$info['factura'][0]['Hora'].'</td></tr>';
	}

	if(strlen($info['factura'][0]['Autorizacion']) < 13 && $_SESSION['INGRESO']['IDEntidad'] != '65'){
		$ticket.="<tr><td colspan='3'>Fecha de caducidad: ".substr(mesesLetras($info['factura'][0]['Vencimiento']->format('m')), 0, 3).'/'.$info['factura'][0]['Vencimiento']->format('Y').'</td></tr>';
	}
	$ticket.="<tr><td colspan='3'>-------------------------------------------------------</td></tr>";
	if($_SESSION['INGRESO']['IDEntidad'] == '65'){
		$ticket.="<tr><td colspan='3'>Usuario: ".$info['factura'][0]['Cliente'].'</td></tr>';
	}else{
		$ticket.="<tr><td colspan='3'>Cliente: ".$info['factura'][0]['Cliente'].'</td></tr>';
	}
	$ticket.="<tr><td colspan='3'>R.U.C/C.I.: ".$info['factura'][0]['RUC_CI'].'</td></tr>';

	if($info['factura'][0]['Telefono'] <> G_NINGUNO){$ticket.="<tr><td colspan='3'>Teléfono: ".$info['factura'][0]['Telefono'].'</td></tr>';}
	if($info['factura'][0]['Direccion'] <> G_NINGUNO){$ticket.="<tr><td colspan='3'>Dirección: ".$info['factura'][0]['Direccion'].'</td></tr>';}
	if($info['factura'][0]['Email'] <> G_NINGUNO){$ticket.="<tr><td colspan='3'>Email: ".$info['factura'][0]['Email'].'</td></tr>';}

	if($info['factura'][0]['TC'] == "DO" || $info['factura'][0]['TC'] == "NDO" || $info['factura'][0]['TC'] == "NDU"){
		if($_SESSION['INGRESO']['IDEntidad'] == '65'){
			$ticket.="<tr><td colspan='3'>Hora de llegada de la OS:</td></tr>";
			$ticket.="<tr><td colspan='3'>Hora de Atención:</td></tr>";
			$ticket.="<tr><td colspan='3'>Estado: </td></tr>";
			$ticket.="<tr><td colspan='3'>N° Gavetas pendientes:</td></tr>";
		}else{
			$ticket.="<tr><td colspan='3'>Codigo: </td></tr>";
			$ticket.="<tr><td colspan='3'>Aporte: </td></tr>";
			$ticket.="<tr><td colspan='3'>Numero de Gavetas: </td></tr>";
			$ticket.="<tr><td colspan='3'>Atencion: </td></tr>";
		}
	$ticket.="<tr><td colspan='3'>===============================</td></tr>";

		if($_SESSION['INGRESO']['IDEntidad'] == '65'){
			$linea1F = "P R O D U C T O";
			$linea2F = "CANT (KG)";
			$ticket.="<tr><td colspan='2'>".$linea1F.'</td><td>'.$linea2F."</td></tr>";
			
		}
	$ticket.="<tr><td colspan='3'>===============================</td></tr>";

	}
	$Efectivo = $info['factura'][0]['Efectivo'];


	$Total = 0;
	$TotalPVP = 0;


	if(count($info['lineas']) > 0){
		foreach($info['lineas'] as $key => $value){
			if($info['factura'][0]['TC'] == "DO" || $info['factura'][0]['TC'] == "NDO" || $info['factura'][0]['TC'] == "NDU"){
				$CodigoC = $value['Codigo'];
				$CodigoN = number_format($value['Cantidad'], 2, '.', '');
				$Producto = $value['Producto'];

				$sql="SELECT *
					FROM Catalogo_Productos 
					WHERE Item = '".$_SESSION['INGRESO']['item']."' 
					AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
					AND Codigo_Inv = '".$CodigoC."'";
				$filasCP = $this->db->datos($sql);
				
				
				if($value['Tipo_Hab'] <> G_NINGUNO){$Producto .= "(".$value['Tipo_Hab'].")";}
				if($_SESSION['INGRESO']['IDEntidad'] == '65'){
					$ticket.="<tr><td colspan='2'>".$Producto."</td><td align='right'>".$CodigoN."</td></tr>";
					//$pdf->MultiCell($anchoFact,3,.$CodigoN,0,'L');
				}
				
				$PVP = number_format(floatval($filasCP[0]['PVP_3']), 2, '.', '') * floatval($value['Cantidad']);
				$Total += $value['Cantidad'];
				$TotalPVP += $PVP;

				
			}

			if($info['factura'][0]['TC'] <> "PV"){$Total_IVA += $value['Total_IVA'];} 
		}
	}


	if($info['factura'][0]['TC'] == "DO" || $info['factura'][0]['TC'] == "NDO" || $info['factura'][0]['TC'] == "NDU")
	{

	$ticket.="<tr><td colspan='3'>-------------------------------------------------------</td></tr>";
		if($_SESSION['INGRESO']['IDEntidad'] == '65'){
			$ticket.="<tr><td colspan='2'>TOTAL KILOS</td><td align='right'>".number_format($Total,2,'.','')."</td></tr>";
		}

				
		$ticket.="<tr><td></td></tr><tr><td></td></tr>";
		$ticket.="<tr><td colspan='3'>IMPORTANTE:</td></tr>";

		if($_SESSION['INGRESO']['IDEntidad'] == '65'){
			$Producto = "Los productos donados han perdido valor comercial, pero conservan valor social. Han sido clasificados y están en " .
				"buen estado. Se recomienda su CONSUMO INMEDIATO y está PROHIBIDA SU COMERCIALIZACIÓN. El BAQ no se responsabiliza por " .
				"efectos negativos por consumo fuera del tiempo sugerido. Con su firma, el beneficiario acepta haber sido informado del " .
				"estado de los productos, que los recibe voluntariamente, los usará con fines benéficos y bajo su responsabilidad.";
			$ticket.="<tr><td colspan='3' style=' text-align: justify;'>".$Producto."</td></tr>";
		}
	}

	if($Encabezado_PV){
	if($_SESSION['INGRESO']['Nombre_Comercial']==$_SESSION['INGRESO']['Razon_Social'])
	{
		$ticket.="<tr><td colspan='3'>".$_SESSION['INGRESO']['Razon_Social']."</td></tr>";
		$ticket.="<tr><td colspan='3'>R.U.C ".$_SESSION['INGRESO']['RUC']."</td></tr>";
		//$pdf->Ln(6);
	}else if($info['factura'][0]['TC'] == 'DO' || $info['factura'][0]['TC'] == "NDO" || $info['factura'][0]['TC'] == "NDU"){
		$ticket.="<tr><td colspan='3'>-------------------------------------------------------</td></tr>";
		$ticket.="<tr><td colspan='3'></td></tr><tr><td colspan='3'></td></tr>";
		$ticket.="<tr><td colspan='3' style='text-align:center'><b>APORTE SOLIDARIO</b></td></tr>";
		$ticket.="<tr><td colspan='3'></td></tr>";
	}else{
		$ticket.="<tr><td colspan='3'>".$_SESSION['INGRESO']['Razon_Social']."</td></tr>";
		$ticket.="<tr><td colspan='3'>".$_SESSION['INGRESO']['Nombre_Comercial']."</td></tr>";
		$ticket.="<tr><td colspan='3'>R.U.C ".$_SESSION['INGRESO']['RUC']."</td></tr>";
	}


	$ticket.="<tr><td colspan='3'><span style='font-size:11px;'>NOTA DE DONACION No. <span style='color:red;'>".$info['factura'][0]['Serie']."-".str_pad($info['factura'][0]['Factura'], 7, '0', STR_PAD_LEFT)."</span></span></td></tr>";	
}
	$ticket.="<tr><td colspan='3'></td></tr><tr><td colspan='3'></td></tr>";
	$ticket.="<tr><td colspan='3'>Fecha de Emisión: ".$info['factura'][0]['Fecha']->format('Y/m/d')."</td></tr>";
	$ticket.="<tr><td colspan='3'>-------------------------------------------------------</td></tr>";
	if($_SESSION['INGRESO']['IDEntidad'] == '65'){
		$ticket.="<tr><td colspan='3'>Usuario: ".$info['factura'][0]['Cliente']."</td></tr>";
	}else{
		$ticket.="<tr><td colspan='3'>Cliente: ".$info['factura'][0]['Cliente']."</td></tr>";
	}
		$ticket.="<tr><td colspan='3'>R.U.C/C.I.: ".$info['factura'][0]['RUC_CI']."</td></tr>";
		if($info['factura'][0]['Telefono'] <> G_NINGUNO){$ticket.="<tr><td colspan='3'>Telefono: ".$info['factura'][0]['Telefono']."</td></tr>";}

	$ticket.="<tr><td colspan='3'>-------------------------------------------------------</td></tr>";
	$Producto = "<b>El costo comercial de los kilos entregados es de USD ".number_format((float)$TotalPVP, 2, '.', '').". Su aporte solidario de USD ".number_format((float)$info['factura'][0]['Total_MN'], 2, '.', '')." representa menos del 10% de este valor</b> y nos ayuda a cubrir costos operativos para asistir a más personas en situación de vulnerabilidad alimentaria.";

	$ticket.="<tr><td colspan='3'  style=' text-align: justify;'>".$Producto."</td></tr>";
	$ticket.="<tr><td colspan='3'></td></tr><tr><td colspan='3'></td></tr>";
					
	$Producto = "Puede donar en efectivo, por depósito o transferencia a la cuenta de ahorros Banco Pichincha N.º 3708204100 " .
					"a nombre de ".$_SESSION['INGRESO']['Razon_Social'].".";
	$ticket.="<tr><td colspan='3'  style=' text-align: justify;'>".$Producto."</td></tr>";

	$ticket.="<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>";
	$ticket.="<tr><td colspan='3'>____________           _______________</td></tr>";
	$ticket.="<tr><td colspan='3'>           B A Q              Organización Social</td></tr>";
	$ticket.="<tr><td colspan='3'> ".$_SESSION['INGRESO']['RUC']."            ".$info['factura'][0]['RUC_CI']."</td></tr>";

	$ticket.="<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>";

	$ticket.="<tr><td colspan='3' style='text-align:center'><b>".$_SESSION['INGRESO']['LeyendaFAT']."</b></td></tr>";

	// $ticket.='<tr><td colspan="3" style="text-align:center"><b>"No desperdiciar, alimentar bien, cambiar vidas"</b></td></tr>';

	$ticket.="<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>";
	$ticket.="<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>";

	$ticket.="<tr><td colspan='3'>_________________________________</td></tr>";
	
	if(count($filasTA)>0){
	$ticket.="<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>";
	$ticket.="<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>";
	$ticket.= '<tr><td colspan="3"><img src="../../../'.$src.'" style="width: 210px; height: '.$altoImg.'px;"></img></td></tr>';
	if($Encabezado_PV){
		if($_SESSION['INGRESO']['Nombre_Comercial']==$_SESSION['INGRESO']['Razon_Social'])
		{
			$ticket.="<tr><td colspan='3'>".$_SESSION['INGRESO']['Razon_Social']."</td></tr>";
			$ticket.="<tr><td colspan='3'>R.U.C ".$_SESSION['INGRESO']['RUC']."</td></tr>";
			//$pdf->Ln(6);
		}else if($info['factura'][0]['TC'] == 'DO' || $info['factura'][0]['TC'] == "NDO" || $info['factura'][0]['TC'] == "NDU"){
			
			$ticket.="<tr><td colspan='3'>R.U.C ".$_SESSION['INGRESO']['RUC']."</td></tr>";
		}else{
			$ticket.="<tr><td colspan='3'>".$_SESSION['INGRESO']['Razon_Social']."</td></tr>";
			$ticket.="<tr><td colspan='3'>".$_SESSION['INGRESO']['Nombre_Comercial']."</td></tr>";
			$ticket.="<tr><td colspan='3'>R.U.C ".$_SESSION['INGRESO']['RUC']."</td></tr>";
		}
		$ticket.="<tr><td colspan='3'>Teléfono: ".$_SESSION['INGRESO']['Telefono1']."</td></tr>";
		$ticket.="<tr><td colspan='3'>Dirección: ".$_SESSION['INGRESO']['Direccion']."</td></tr>";
	}
	$textoReciboCaja = "INGRESO No. ";
	if($_SESSION['INGRESO']['IDEntidad'] == '65'){
		$textoReciboCaja = "RECIBO CAJA No. ";
	}


	$ticket.="<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>";		
		$ticket.="<tr><td colspan='3' style='text-align:center'><b>".$textoReciboCaja . $filasTA[0]['Fecha']->format('Y').'-'.$filasTA[0]['Recibo_No']."<b></td></tr>";

		// $ticket.="<tr><td colspan='3' style='text-align:center'><b>".$textoReciboCaja ."0000</b></td></tr>";
		$ticket.="<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>";

		$ticket.="<tr><td colspan='3'>Fecha: ".$info['factura'][0]['Fecha']->format('Y/m/d')."</td></tr>";
		$ticket.="<tr><td colspan='3'>Por USD ".number_format((float)$info['factura'][0]['Total_MN'], 2, '.', '')."</td></tr>";
		$ticket.="<tr><td colspan='3'>La suma de: ".str_pad((int)($info['factura'][0]['Total_MN'] * 100), 2, "0", STR_PAD_LEFT)."/100</td></tr>";

		$ticket.="<tr><td colspan='3'>-------------------------------------------------------</td></tr>";
		$ticket.="<tr><td colspan='3'>Usuario: ".$info['factura'][0]['Cliente']."</td></tr>";
		$ticket.="<tr><td colspan='3'>NOTA DE DONACION No. ".$info['factura'][0]['Serie']."-".str_pad($info['factura'][0]['Factura'], 7, '0', STR_PAD_LEFT)."</td></tr>";

		$ticket.="<tr><td colspan='3'>-------------------------------------------------------</td></tr>";


		if($_SESSION['INGRESO']['IDEntidad'] == '65'){
			$ticket.="<tr><td colspan='3'>POR CONCEPTO DE: DONACION</td></tr>";
		}else{
			$ticket.="<tr><td colspan='3'>POR CONCEPTO DE:</td></tr>";
		}

		$ticket.="<tr><td colspan='3'>-------------------------------------------------------</td></tr>";
		foreach ($filasTA as $key => $value) {
			// print_r($value);die();
			$ticket.="<tr><td colspan='3'>Fecha: ".$info['factura'][0]['Fecha']->format('Y/m/d').' - '.$value['Banco'].' - '.str_pad($info['factura'][0]['Factura'], 7, '0', STR_PAD_LEFT).' - Por USD '.number_format((float)$value['Abono'], 2, '.', '')."</td></tr>";
		}

		// $ticket.="<tr><td colspan='3'>Fecha: ".$info['factura'][0]['Fecha']->format('Y/m/d').' - EFECTIVO MN - '.str_pad($info['factura'][0]['Factura'], 7, '0', STR_PAD_LEFT).' - Por USD '.number_format((float)$info['factura'][0]['Total_MN'], 2, '.', '')."</td></tr>";
		$ticket.="<tr><td colspan='3'>-------------------------------------------------------</td></tr>";

		$ticket.="<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>";
		$ticket.="<tr><td colspan='3'>______________          ______________</td></tr>";
		$ticket.="<tr><td colspan='3'>      Conforme                       Procesado</td></tr>";
		$ticket.="<tr><td colspan='3'>      C.I./R.U.C                   Por: ".$filasUsuario[0]['Usuario']."</td></tr>";
		$ticket.="<tr><td colspan='3'> ".$info['factura'][0]['RUC_CI']."               ".$info['factura'][0]['CodigoU']."</td></tr>";
	}
$ticket.='
</table></pre>
</body>
</html>';

return $ticket;
}


function Imprimir_Punto_Venta_recibo($info,$abonos,$descagar=true,)
{	
	$filasTA = $abonos;

	$sql = "SELECT Usuario
	FROM Accesos 
	WHERE Codigo = '".$info['lineas'][0]['CodigoU']."'";

	$filasUsuario = $this->db->datos($sql);


	$CantBlancos = "";
	$Grafico_PV = Leer_Campo_Empresa("Grafico_PV");
	$ancho_PV = Leer_Campo_Empresa("Cant_Ancho_PV");
	$Encabezado_PV = Leer_Campo_Empresa("Encabezado_PV");
	
	$Total = 0;
	$Total_IVA = 0;
	$Codigo1 = $info['factura'][0]['CodigoU'];
	$info['Cliente'] = $info['factura'][0]['Cliente'];
	$Codigo1 = substr($Codigo1, 0, 4)."X".substr($Codigo1, strlen($Codigo1)-1, 2);
	$Producto = "";
	if($ancho_PV < 26){
		$ancho_PV = 26;
	}
	if(isset($_SESSION['INGRESO']['Logo_Tipo']))
		{
		$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		//si es jpg
		$src = dirname(__DIR__,3).'/img/logotipos/'.$logo.'.jpg'; 
		if(!file_exists($src))
		{
			$src = dirname(__DIR__,3).'/img/logotipos/'.$logo.'.gif'; 
			if(!file_exists($src))
			{
				$src = dirname(__DIR__,3).'/img/logotipos/'.$logo.'.png'; 
				if(!file_exists($src))
				{
					$logo="diskcover_web";
					$src= dirname(__DIR__,3).'/img/logotipos/'.$logo.'.gif';

				}

			}

		}
	}
// print_r($info);die();

$src = str_replace(dirname(__DIR__,3),'', $src);

//3.77 = a 1 pixel

$margenesX = 6;
$margenesY = 2;
$anchoFact = $ancho_PV * 1.75;
$anchoImg = ($ancho_PV * 1.75)*3.77;
$altoImg = ($anchoFact * 0.38)*3.77;

$ticket  = '

<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial;
            font-size: 12px;
            width: 60mm;       /* Ancho seguro para 58mm de papel */
            margin: 0;         /* Elimina márgenes */
            padding: 5mm;        /* Elimina relleno */


        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Fuerza el ajuste de columnas */
            white-space: pre-wrap; 
        }
        td, th {
            padding: 2px;
            word-break: break-word; /* Rompe palabras largas */
            overflow: hidden;       /* Oculta contenido que exceda */
        }
    </style>
</head>
<body>
<pre>
<table style="font-family: Arial">';
	
	if(count($filasTA)>0)
	{
	$ticket.="<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>";
	$ticket.="<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>";
	$ticket.= '<tr><td colspan="3"><img src="../../../'.$src.'" style="width: 210px; height: '.$altoImg.'px;"></img></td></tr>';
	if($Encabezado_PV){
		if($_SESSION['INGRESO']['Nombre_Comercial']==$_SESSION['INGRESO']['Razon_Social'])
		{
			$ticket.="<tr><td colspan='3'>".$_SESSION['INGRESO']['Razon_Social']."</td></tr>";
			$ticket.="<tr><td colspan='3'>R.U.C ".$_SESSION['INGRESO']['RUC']."</td></tr>";
			//$pdf->Ln(6);
		}else if($info['factura'][0]['TC'] == 'DO' || $info['factura'][0]['TC'] == "NDO" || $info['factura'][0]['TC'] == "NDU"){
			
			$ticket.="<tr><td colspan='3'>R.U.C ".$_SESSION['INGRESO']['RUC']."</td></tr>";
		}else{
			$ticket.="<tr><td colspan='3'>".$_SESSION['INGRESO']['Razon_Social']."</td></tr>";
			$ticket.="<tr><td colspan='3'>".$_SESSION['INGRESO']['Nombre_Comercial']."</td></tr>";
			$ticket.="<tr><td colspan='3'>R.U.C ".$_SESSION['INGRESO']['RUC']."</td></tr>";
		}
		$ticket.="<tr><td colspan='3'>Teléfono: ".$_SESSION['INGRESO']['Telefono1']."</td></tr>";
		$ticket.="<tr><td colspan='3'>Dirección: ".$_SESSION['INGRESO']['Direccion']."</td></tr>";
	}
	$textoReciboCaja = "INGRESO No. ";
	if($_SESSION['INGRESO']['IDEntidad'] == '65'){
		$textoReciboCaja = "RECIBO CAJA No. ";
	}


	$ticket.="<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>";		
		$ticket.="<tr><td colspan='3' style='text-align:center'><b>".$textoReciboCaja . $filasTA[0]['Fecha']->format('Y').'-'.$filasTA[0]['Recibo_No']."<b></td></tr>";

		// $ticket.="<tr><td colspan='3' style='text-align:center'><b>".$textoReciboCaja ."0000</b></td></tr>";
		$ticket.="<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>";

		$ticket.="<tr><td colspan='3'>Fecha: ".$info['factura'][0]['Fecha']->format('Y/m/d')."</td></tr>";
		$ticket.="<tr><td colspan='3'>Por USD ".number_format((float)$info['factura'][0]['Total_MN'], 2, '.', '')."</td></tr>";
		$ticket.="<tr><td colspan='3'>La suma de: ".str_pad((int)($info['factura'][0]['Total_MN'] * 100), 2, "0", STR_PAD_LEFT)."/100</td></tr>";

		$ticket.="<tr><td colspan='3'>-------------------------------------------------------</td></tr>";
		$ticket.="<tr><td colspan='3'>Usuario: ".$info['factura'][0]['Cliente']."</td></tr>";
		$ticket.="<tr><td colspan='3'>NOTA DE DONACION No. ".$info['factura'][0]['Serie']."-".str_pad($info['factura'][0]['Factura'], 7, '0', STR_PAD_LEFT)."</td></tr>";

		$ticket.="<tr><td colspan='3'>-------------------------------------------------------</td></tr>";


		if($_SESSION['INGRESO']['IDEntidad'] == '65'){
			$ticket.="<tr><td colspan='3'>POR CONCEPTO DE: DONACION</td></tr>";
		}else{
			$ticket.="<tr><td colspan='3'>POR CONCEPTO DE:</td></tr>";
		}

		$ticket.="<tr><td colspan='3'>-------------------------------------------------------</td></tr>";
		foreach ($filasTA as $key => $value) {
			// print_r($value);die();
			$ticket.="<tr><td colspan='3'>Fecha: ".$info['factura'][0]['Fecha']->format('Y/m/d').' - '.$value['Banco'].' - '.str_pad($info['factura'][0]['Factura'], 7, '0', STR_PAD_LEFT).' - Por USD '.number_format((float)$value['Abono'], 2, '.', '')."</td></tr>";
		}

		// $ticket.="<tr><td colspan='3'>Fecha: ".$info['factura'][0]['Fecha']->format('Y/m/d').' - EFECTIVO MN - '.str_pad($info['factura'][0]['Factura'], 7, '0', STR_PAD_LEFT).' - Por USD '.number_format((float)$info['factura'][0]['Total_MN'], 2, '.', '')."</td></tr>";
		$ticket.="<tr><td colspan='3'>-------------------------------------------------------</td></tr>";

		$ticket.="<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>";
		$ticket.="<tr><td colspan='3'>______________          ______________</td></tr>";
		$ticket.="<tr><td colspan='3'>      Conforme                       Procesado</td></tr>";
		$ticket.="<tr><td colspan='3'>      C.I./R.U.C                   Por: ".$filasUsuario[0]['Usuario']."</td></tr>";
		$ticket.="<tr><td colspan='3'> ".$info['factura'][0]['RUC_CI']."               ".$info['factura'][0]['CodigoU']."</td></tr>";
	}else
	{
		$ticket.='<tr><td><h1>SIN RECIBO DE CAJA - SIN ABONOS</h1></td></tr>';
	}
$ticket.='
</table></pre>
</body>
</html>';

return $ticket;
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

}