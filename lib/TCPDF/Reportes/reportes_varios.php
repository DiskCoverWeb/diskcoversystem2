<?php 
require_once('tcpdf_include.php');
class reportes_varios
{
	private $conn;
	function __construct()
	{
		$this->conn = cone_ajax();
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

	function Imprimir_Punto_Venta($info,$descagar=true)
{	
	$cid=$this->conn;
	$sql = "SELECT *
	FROM Trans_Abonos TA
	WHERE TA.Factura = ".$info['factura'][0]['Factura']." 
	AND TA.Serie = '".$info['factura'][0]['Serie']."' 
	AND TA.Periodo = '".$_SESSION['INGRESO']['periodo']."'
	AND TA.Item = '".$_SESSION['INGRESO']['item']."'";
	
	$stmt = sqlsrv_query($cid, $sql);
	if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			return '';
			die( print_r( sqlsrv_errors(), true));  
		}   

	$filasTA = array();	
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
		{
		$filasTA[] = $row;
		//echo $row[0];
		}

	$sql = "SELECT Usuario
	FROM Accesos 
	WHERE Codigo = '".$info['lineas'][0]['CodigoU']."'";
	$stmt = sqlsrv_query($cid, $sql);
	if( $stmt === false)  
	{  
		echo "Error en consulta PA.\n";  
		return '';
		die( print_r( sqlsrv_errors(), true));  
	}   
		
	$filasUsuario = array();	
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
	{
		$filasUsuario[] = $row;
		//echo $row[0];
	}

	/*$sql = "SELECT Usuario
	FROM Accesos 
	WHERE Codigo = '".$info['lineas'][0]['CodigoU']."'";
	$stmt = sqlsrv_query($cid, $sql);
	if( $stmt === false)  
	{  
		echo "Error en consulta PA.\n";  
		return '';
		die( print_r( sqlsrv_errors(), true));  
	}   
		
	$filasUsuario = array();	
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
	{
		$filasUsuario[] = $row;
		//echo $row[0];
	}*/

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
	// print_r($info);die();
	// print_r($_SESSION['INGRESO']);
	// $orientation='P',$unit='mm', array(45,350)
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

	$anchoFact = $ancho_PV * 1.75;
		
	$pdf = new TCPDF('P', 'mm', 'A3');
	$pdf->setPrintHeader(false); // Desactivar encabezado
	$pdf->setPrintFooter(false); // Opcional: Desactivar pie de página
	$pdf->setMargins(0,0);
	$pdf->SetFont('Courier','',8);
	$pdf->AddPage('P');

	// print_r($info);die();
	if($Grafico_PV){
		$anchoImg = $ancho_PV * 1.75;
		$altoImg = $anchoFact * 0.38;
		$pdf->Image($src,0,0,$anchoImg,$altoImg);
		$pdf->SetY($altoImg);
	}
	if($Encabezado_PV){
		if($_SESSION['INGRESO']['Nombre_Comercial']==$_SESSION['INGRESO']['Razon_Social'])
		{
			$pdf->MultiCell($anchoFact,3,$_SESSION['INGRESO']['Razon_Social'],0,'L');
			$pdf->MultiCell($anchoFact,3,'R.U.C '.$_SESSION['INGRESO']['RUC'],0,'L');
			//$pdf->Ln(6);
		}else if($info['factura'][0]['TC'] == 'DO' || $info['factura'][0]['TC'] == "NDO" || $info['factura'][0]['TC'] == "NDU"){
			$pdf->MultiCell($anchoFact,3,$_SESSION['INGRESO']['Razon_Social'],0,'L');
			if($_SESSION['INGRESO']['IDEntidad'] != '65'){
				$pdf->MultiCell($anchoFact,3,$_SESSION['INGRESO']['Nombre_Comercial'],0,'L');
			}
			$pdf->MultiCell($anchoFact,3,'R.U.C '.$_SESSION['INGRESO']['RUC'],0,'L');
			$pdf->Ln(3);
			$pdf->MultiCell($anchoFact,3,'DONACION DE ALIMENTOS',0,'L');
			$pdf->Ln(3);
		}else{
			$pdf->MultiCell($anchoFact,3,$_SESSION['INGRESO']['Razon_Social'],0,'L');
			$pdf->MultiCell($anchoFact,3,$_SESSION['INGRESO']['Nombre_Comercial'],0,'L');
			$pdf->MultiCell($anchoFact,3,'R.U.C '.$_SESSION['INGRESO']['RUC'],0,'L');
		}
		$pdf->MultiCell($anchoFact,3,'Direccion: '.$_SESSION['INGRESO']['Direccion'],0, 'L');
		$pdf->MultiCell($anchoFact,3,'Telefono: '.$_SESSION['INGRESO']['Telefono1'],0, 'L');
	}

	if($Encabezado_PV){
		if($info['factura'][0]['TC'] == "PV"){
			$pdf->MultiCell($anchoFact,3,"T I C K E T   No. 000-000-".str_pad($info['factura'][0]['Factura'], 7, '0', STR_PAD_LEFT),0,'L');
			$pdf->Ln(3);
		}else if($info['factura'][0]['TC'] == "NV"){
			$pdf->MultiCell($anchoFact,3,"Autorizacion del SRI No.",0,'L');
			$pdf->MultiCell($anchoFact,3,$info['factura'][0]['Autorizacion'],0,'L');
			$pdf->MultiCell($anchoFact,3,"NOTA DE VENTA No. ".$info['factura'][0]['Serie']."-".str_pad($info['factura'][0]['Factura'], 7, '0', STR_PAD_LEFT),0,'L');
		}else if($info['factura'][0]['TC'] == 'DO' || $info['factura'][0]['TC'] == "NDO" || $info['factura'][0]['TC'] == "NDU"){
			$pdf->MultiCell($anchoFact,3,"NOTA DE DONACION No. ".$info['factura'][0]['Serie']."-".str_pad($info['factura'][0]['Factura'], 7, '0', STR_PAD_LEFT),0,'L');
		}else{
			$pdf->MultiCell($anchoFact,3,"Autorizacion del SRI No.",0,'L');
			$pdf->MultiCell($anchoFact,3,$info['factura'][0]['Autorizacion'],0,'L');
			$pdf->MultiCell($anchoFact,3,"FACTURA No. ".$info['factura'][0]['Serie']."-".str_pad($info['factura'][0]['Factura'], 7, '0', STR_PAD_LEFT),0,'L');
			if(strlen($info['especial']) > 1){
				$pdf->MultiCell($anchoFact,3,"Contribuyente Especial No. ".$info['especial'],0,'L');
			}
			$pdf->MultiCell($anchoFact,3,"OBLIGADO A LLEVAR CONTABILIDAD: ".$info['conta'],0,'L');
		}
	}else{
		$pdf->MultiCell($anchoFact,3,"Transaccion (".$info['factura'][0]['TC'].") No.".str_pad($info['factura'][0]['Factura'], 7, '0', STR_PAD_LEFT),0,'L');
		$pdf->Ln(3);
	}
	
	$pdf->MultiCell($anchoFact,3,"Fecha de Emision: ".$info['factura'][0]['Fecha']->format('Y/m/d'),0,'L');
	if($_SESSION['INGRESO']['IDEntidad'] == '65'){
		$pdf->MultiCell($anchoFact,3,"Atendido por: ".$filasUsuario[0]['Usuario'],0,'L');
	}else{
		$pdf->MultiCell($anchoFact,3,"Hora de Proceso: ".$info['factura'][0]['Hora'],0,'L');
	}

	if(strlen($info['factura'][0]['Autorizacion']) < 13 && $_SESSION['INGRESO']['IDEntidad'] != '65'){
		$pdf->MultiCell($anchoFact,3,"Fecha de caducidad: ".substr(mesesLetras($info['factura'][0]['Vencimiento']->format('m')), 0, 3).'/'.$info['factura'][0]['Vencimiento']->format('Y'),0,'L');
	}
	$pdf->Cell($anchoFact,3,str_repeat('-', $ancho_PV),0,1,'L');
	if($_SESSION['INGRESO']['IDEntidad'] == '65'){
		$pdf->MultiCell($anchoFact,3,"Usuario: ".$info['factura'][0]['Cliente'],0,'L');
	}else{
		$pdf->MultiCell($anchoFact,3,"Cliente: ".$info['factura'][0]['Cliente'],0,'L');
	}
	$pdf->MultiCell($anchoFact,3,"R.U.C/C.I.: ".$info['factura'][0]['RUC_CI'],0,'L');

	if($info['factura'][0]['Telefono'] <> G_NINGUNO){$pdf->MultiCell($anchoFact,3,"Telefono: ".$info['factura'][0]['Telefono'],0,'L');}
	if($info['factura'][0]['Direccion'] <> G_NINGUNO){$pdf->MultiCell($anchoFact,3,"Direccion: ".$info['factura'][0]['Direccion'],0,'L');}
	if($info['factura'][0]['Email'] <> G_NINGUNO){$pdf->MultiCell($anchoFact,3,"Email: ".$info['factura'][0]['Email'],0,'L');}

	if($info['factura'][0]['TC'] == "DO" || $info['factura'][0]['TC'] == "NDO" || $info['factura'][0]['TC'] == "NDU"){
		if($_SESSION['INGRESO']['IDEntidad'] == '65'){
			$pdf->MultiCell($anchoFact,3,"Hora de llegada de la OS: ",0,'L');
			$pdf->MultiCell($anchoFact,3,"Hora de Atención: ",0,'L');
			$pdf->MultiCell($anchoFact,3,"Estado: ",0,'L');
			$pdf->MultiCell($anchoFact,3,"No Gavetas pendientes por entregar: ",0,'L');
		}else{
			$pdf->MultiCell($anchoFact,3,"Codigo: ",0,'L');
			$pdf->MultiCell($anchoFact,3,"Aporte: ",0,'L');
			$pdf->MultiCell($anchoFact,3,"Numero de Gavetas: ",0,'L');
			$pdf->MultiCell($anchoFact,3,"Atencion: ",0,'L');
		}
		$pdf->Cell($anchoFact,3,str_repeat('=', $ancho_PV),0, 1, 'L');
		if($_SESSION['INGRESO']['IDEntidad'] == '65'){
			$linea1F = "P R O D U C T O";
			$linea2F = "CANTIDAD(KG)";
			$pdf->MultiCell($anchoFact,3,$linea1F.str_repeat(' ', $ancho_PV - (strlen($linea1F)+strlen($linea2F))).$linea2F,0,'L');
			
		}else{
			$pdf->MultiCell($anchoFact,3,"P R O D U C T O/CODIGO CANTIDAD(KG)",0,'L');
			
		}
		$pdf->Cell($anchoFact,3,str_repeat('=', $ancho_PV),0, 1, 'L');
	}else{
		$pdf->Cell($anchoFact,3,str_repeat('=', $ancho_PV),0, 1, 'L');
		$pdf->MultiCell($anchoFact,3,"PRODUCTO/Cant x PVP/TOTAL",0,'L');
		$pdf->Cell($anchoFact,3,str_repeat('=', $ancho_PV),0, 1, 'L');
	}
	$Efectivo = $info['factura'][0]['Efectivo'];


	$Total = 0;
	$TotalPVP = 0;

	if(($ancho_PV - 26) > 0){$CantBlancos = str_repeat(' ', $ancho_PV - 26);}else{$CantBlancos = "";}

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
				
				$stmt = sqlsrv_query($cid, $sql);
				if( $stmt === false)  
				{  
					echo "Error en consulta PA.\n";  
					return '';
					die( print_r( sqlsrv_errors(), true));  
				}
				$filasCP = array();	
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
				{
					$filasCP[] = $row;
					//echo $row[0];
				}

				if($value['Tipo_Hab'] <> G_NINGUNO){$Producto .= "(".$value['Tipo_Hab'].")";}
				if($_SESSION['INGRESO']['IDEntidad'] == '65'){
					$pdf->MultiCell($anchoFact,3,$Producto.str_repeat(' ', $ancho_PV - (strlen($Producto)+strlen($CodigoN))).$CodigoN,0,'L');
					//$pdf->MultiCell($anchoFact,3,.$CodigoN,0,'L');
				}else{
					$pdf->MultiCell($anchoFact,3,$Producto,0,'L');
					$pdf->MultiCell($anchoFact,3,$CodigoC.str_repeat(' ', 25 - strlen($CodigoC))." ".str_repeat(' ', 10 - strlen($CodigoN)).$CodigoN,0,'L');
				}
				$PVP = (float)number_format($filasCP[0]['PVP_3'], 2, '.', '') * (float)$value['Cantidad'];
				$Total += $value['Cantidad'];
				$TotalPVP += $PVP;
			}else{
				$pdf->MultiCell($anchoFact,3, $value['Producto'],0,'L');
				$Producto = $this->SetearBlancos(strval($value['Cantidad'])."x".number_format($value['Precio'], 2, '.', ','), 12, 0, false)." "
							. $this->SetearBlancos(strval($value['Total']), $ancho_PV - 13, 0, true, false, true);
				$pdf->MultiCell($anchoFact,3, $Producto,0,'L');
				$Total += $value['Cantidad'];
			}

			if($info['factura'][0]['TC'] <> "PV"){$Total_IVA += $value['Total_IVA'];}
		}
	}

	if($info['factura'][0]['TC'] == "DO" || $info['factura'][0]['TC'] == "NDO" || $info['factura'][0]['TC'] == "NDU"){
		$pdf->Cell($anchoFact,3,str_repeat('-', $ancho_PV),0, 1, 'L');
		if($_SESSION['INGRESO']['IDEntidad'] == '65'){
			$pdf->MultiCell($anchoFact,3, $CantBlancos."    K I L O S".$this->SetearBlancos(strval($Total), 12, 0, true, false, true),0,'L');
		}else{
			$pdf->MultiCell($anchoFact,3, $CantBlancos."    T O T A L".$this->SetearBlancos(strval($Total), 12, 0, true, false, true),0,'L');
		}

		if($_SESSION['INGRESO']['IDEntidad'] != '65'){
			$pdf->Ln(3);
			$pdf->Ln(3);
			$pdf->Ln(3);
			$pdf->MultiCell($anchoFact,3, "_____________      _______________",0,'L');
			$pdf->MultiCell($anchoFact,3, "Entregado por      Recibi Conforme",0,'L');
		}

		$pdf->Ln(3);
		$pdf->MultiCell($anchoFact,3, "IMPORTANTE:",0,'L');

		if($_SESSION['INGRESO']['IDEntidad'] == '65'){
			$Producto = "Los productos donados han perdido valor comercial, pero conservan valor social. Han sido clasificados y están en " .
				"buen estado. Se recomienda su CONSUMO INMEDIATO y está PROHIBIDA SU COMERCIALIZACIÓN. El BAQ no se responsabiliza por " .
				"efectos negativos por consumo fuera del tiempo sugerido. Con su firma, el beneficiario acepta haber sido informado del " .
				"estado de los productos, que los recibe voluntariamente, los usará con fines benéficos y bajo su responsabilidad.";
				$pdf->MultiCell($anchoFact,3, $Producto,0,'J');
		}else{
			$Producto = "Los productos donados, han perdido valor comercial por diferentes motivos, pero mantienen un valor social. " .
				"Estos productos han pasado por un proceso de clasificación y se encuentran en buen estado. Se recomienda su " .
				"consumo INMEDIATO y se prohíbe su comercialización. " . $_SESSION['INGRESO']['Razon_Social'] . " no se responsabiliza por " .
				"cualquier efecto negativo que causare el consumo de alimentos en un tiempo mayor al sugerido.  Con su firma " .
				"el beneficiario acepta que ha sido informado sobre el estado de los productos, que los recibe con su consentimiento, " .
				"que los usará para fines benéficos y bajo su completa responsabilidad.";
				$pdf->MultiCell($anchoFact,3, $Producto,0,'L');
		}

		$pdf->Ln(3);
		$pdf->Ln(3);
		$pdf->Ln(3);

		//Nueva parte para imprimir nota de donación
		$pdf->Cell($anchoFact,3,str_repeat('-', $ancho_PV),0,1,'L');
		if($Grafico_PV && $_SESSION['INGRESO']['IDEntidad'] != '65'){
			$anchoImg = $ancho_PV * 1.75;
			$altoImg = $anchoFact * 0.38;
			$nuevaAltura = $pdf->GetY();
			$pdf->Image($src,0,$nuevaAltura,$anchoImg,$altoImg);
			$pdf->SetY($altoImg + $nuevaAltura);
		}
		if($Encabezado_PV){
			if($_SESSION['INGRESO']['Nombre_Comercial']==$_SESSION['INGRESO']['Razon_Social'])
			{
				$pdf->MultiCell($anchoFact,3,$_SESSION['INGRESO']['Razon_Social'],0,'L');
				$pdf->MultiCell($anchoFact,3,'R.U.C '.$_SESSION['INGRESO']['RUC'],0,'L');
				//$pdf->Ln(6);
			}else if($info['factura'][0]['TC'] == 'DO' || $info['factura'][0]['TC'] == "NDO" || $info['factura'][0]['TC'] == "NDU"){
				if($_SESSION['INGRESO']['IDEntidad'] != '65'){
					$pdf->MultiCell($anchoFact,3,$_SESSION['INGRESO']['Razon_Social'],0,'L');
					$pdf->MultiCell($anchoFact,3,$_SESSION['INGRESO']['Nombre_Comercial'],0,'L');
					$pdf->MultiCell($anchoFact,3,'R.U.C '.$_SESSION['INGRESO']['RUC'],0,'L');
				}else{
					$pdf->SetFont('Courier','B',9);
				}
				$pdf->Ln(3);
				$pdf->MultiCell($anchoFact,3,'A P O R T E   S O L I D A R I O',0,'L');
				$pdf->Ln(3);
			}else{
				$pdf->MultiCell($anchoFact,3,$_SESSION['INGRESO']['Razon_Social'],0,'L');
				$pdf->MultiCell($anchoFact,3,$_SESSION['INGRESO']['Nombre_Comercial'],0,'L');
				$pdf->MultiCell($anchoFact,3,'R.U.C '.$_SESSION['INGRESO']['RUC'],0,'L');
			}
			if($_SESSION['INGRESO']['IDEntidad'] != '65'){
				$pdf->MultiCell($anchoFact,3,'Direccion: '.$_SESSION['INGRESO']['Direccion'],0, 'L');
				$pdf->MultiCell($anchoFact,3,'Telefono: '.$_SESSION['INGRESO']['Telefono1'],0, 'L');
				$pdf->Ln(3);
			}else{
				$pdf->SetFont('Courier','',8);
			}
			$pdf->MultiCell($anchoFact,3,"NOTA DE DONACION No. ".$info['factura'][0]['Serie']."-".str_pad($info['factura'][0]['Factura'], 7, '0', STR_PAD_LEFT),0,'L');
			
		}


		$pdf->Ln(3);
		$pdf->MultiCell($anchoFact,3,"Fecha de Emision: ".$info['factura'][0]['Fecha']->format('Y/m/d'),0,'L');
		$pdf->Cell($anchoFact,3,str_repeat('-', $ancho_PV),0,1,'L');
		if($_SESSION['INGRESO']['IDEntidad'] == '65'){
			$pdf->MultiCell($anchoFact,3,"Usuario: ".$info['factura'][0]['Cliente'],0,'L');
		}else{
			$pdf->MultiCell($anchoFact,3,"Cliente: ".$info['factura'][0]['Cliente'],0,'L');
		}
		$pdf->MultiCell($anchoFact,3,"R.U.C/C.I.: ".$info['factura'][0]['RUC_CI'],0,'L');
		if($info['factura'][0]['Telefono'] <> G_NINGUNO){$pdf->MultiCell($anchoFact,3,"Telefono: ".$info['factura'][0]['Telefono'],0,'L');}
		$pdf->Cell($anchoFact,3,str_repeat('-', $ancho_PV),0,1,'L');
		/*$Producto = "Queremos agradecerle por su aporte solidario de USD ".$info['factura'][0]['Total_MN'].", donación que nos permitirá " .
					"incrementar la atención a un mayor número de personas en vulnerabilidad alimentaria. Usted es muy importante para nosotros.";*/
		$Producto = "El costo comercial de los kilos entregados es de USD ".number_format((float)$TotalPVP, 2, '.', '').". Su aporte solidario " . 
					"de USD ".number_format((float)$info['factura'][0]['Total_MN'], 2, '.', '')." representa menos del 10% de este valor y " .
					"nos ayuda a cubrir costos operativos para asistir a más personas en situación de vulnerabilidad alimentaria.";
		$pdf->MultiCell($anchoFact,3, $Producto,0,'J');
		$pdf->Ln(3);
					
		$Producto = "Puede donar en efectivo, por depósito o transferencia a la cuenta de ahorros Banco Pichincha N.º 3708204100 " .
					"a nombre de ".$_SESSION['INGRESO']['Razon_Social'].".";
		$pdf->MultiCell($anchoFact,3, $Producto,0,'J');
		
		$pdf->Ln(3);
		$pdf->Ln(3);
		$pdf->Ln(3);

		if($_SESSION['INGRESO']['IDEntidad'] == '65'){
			$pdf->MultiCell($anchoFact,3, "_____________    ___________________",0,'L');
			$pdf->MultiCell($anchoFact,3, "     BAQ         Organización Social",0,'L');
			$pdf->MultiCell($anchoFact,3, $_SESSION['INGRESO']['RUC']."          ".$info['factura'][0]['RUC_CI'],0,'L');
		}else{
			$pdf->MultiCell($anchoFact,3, "_____________      _______________",0,'L');
			$pdf->MultiCell($anchoFact,3, "   RECIBIDO            ENTREGADO",0,'L');
		}


		$pdf->Ln(3);
		$pdf->Ln(3);
		$pdf->Ln(3);
		$pdf->Cell($anchoFact,3,str_repeat('-', $ancho_PV),0,1,'L');
		//$pdf->AddPage('P');
		//$pdf->SetFont('Courier','',8);

		if($Grafico_PV){
			$anchoImg = $ancho_PV * 1.75;
			$altoImg = $anchoFact * 0.38;
			$nuevaAltura = $pdf->GetY();
			$pdf->Image($src,0,$nuevaAltura,$anchoImg,$altoImg);
			$pdf->SetY($altoImg + $nuevaAltura);
		}
		if($Encabezado_PV){
			if($_SESSION['INGRESO']['Nombre_Comercial']==$_SESSION['INGRESO']['Razon_Social'])
			{
				$pdf->MultiCell($anchoFact,3,$_SESSION['INGRESO']['Razon_Social'],0,'L');
				$pdf->MultiCell($anchoFact,3,'R.U.C '.$_SESSION['INGRESO']['RUC'],0,'L');
				//$pdf->Ln(6);
			}else if($info['factura'][0]['TC'] == 'DO' || $info['factura'][0]['TC'] == "NDO" || $info['factura'][0]['TC'] == "NDU"){
				if($_SESSION['INGRESO']['IDEntidad'] != '65'){
					$pdf->MultiCell($anchoFact,3,$_SESSION['INGRESO']['Razon_Social'],0,'L');
					$pdf->MultiCell($anchoFact,3,$_SESSION['INGRESO']['Nombre_Comercial'],0,'L');
				}
				$pdf->MultiCell($anchoFact,3,'R.U.C '.$_SESSION['INGRESO']['RUC'],0,'L');
			}else{
				$pdf->MultiCell($anchoFact,3,$_SESSION['INGRESO']['Razon_Social'],0,'L');
				$pdf->MultiCell($anchoFact,3,$_SESSION['INGRESO']['Nombre_Comercial'],0,'L');
				$pdf->MultiCell($anchoFact,3,'R.U.C '.$_SESSION['INGRESO']['RUC'],0,'L');
			}
			$pdf->MultiCell($anchoFact,3,'Telefono: '.$_SESSION['INGRESO']['Telefono1'],0, 'L');
			$pdf->MultiCell($anchoFact,3,'Direccion: '.$_SESSION['INGRESO']['Direccion'],0, 'L');
		}
		$pdf->Ln(3);
		$textoReciboCaja = "INGRESO No. ";
		if($_SESSION['INGRESO']['IDEntidad'] == '65'){
			$textoReciboCaja = "RECIBO CAJA No. ";
		}

		if(count($filasTA)>0){
			$pdf->MultiCell($anchoFact,3,$textoReciboCaja . $filasTA[0]['Fecha']->format('Y').'-'.$filasTA[0]['Recibo_No'],0,'L');
		}else{
			$pdf->MultiCell($anchoFact,3,$textoReciboCaja . '0000',0,'L');
		}
		$pdf->Ln(3);
		$pdf->MultiCell($anchoFact,3,"Fecha: ".$info['factura'][0]['Fecha']->format('Y/m/d'),0,'L');
		$pdf->MultiCell($anchoFact,3,"Por USD ".number_format((float)$info['factura'][0]['Total_MN'], 2, '.', ''),0,'L');
		$pdf->MultiCell($anchoFact,3,"La suma de: ".str_pad((int)($info['factura'][0]['Total_MN'] * 100), 2, "0", STR_PAD_LEFT)."/100",0,'L');
		$pdf->Cell($anchoFact,3,str_repeat('-', $ancho_PV),0,1,'L');
		$pdf->MultiCell($anchoFact,3,"Usuario: ".$info['factura'][0]['Cliente'],0,'L');
		$pdf->MultiCell($anchoFact,3,"NOTA DE DONACION No. ".$info['factura'][0]['Serie']."-".str_pad($info['factura'][0]['Factura'], 7, '0', STR_PAD_LEFT),0,'L');
		$pdf->Cell($anchoFact,3,str_repeat('-', $ancho_PV),0,1,'L');
		if($_SESSION['INGRESO']['IDEntidad'] == '65'){
			$pdf->MultiCell($anchoFact,3,"POR CONCEPTO DE: DONACION",0,'L');
		}else{
			$pdf->MultiCell($anchoFact,3,"POR CONCEPTO DE:",0,'L');
		}
		$pdf->Cell($anchoFact,3,str_repeat('-', $ancho_PV),0,1,'L');
		$pdf->MultiCell($anchoFact,3,"Fecha: ".$info['factura'][0]['Fecha']->format('Y/m/d').' - EFECTIVO MN - '.str_pad($info['factura'][0]['Factura'], 7, '0', STR_PAD_LEFT).' - Por USD '.number_format((float)$info['factura'][0]['Total_MN'], 2, '.', ''),0,'L');
		$pdf->Cell($anchoFact,3,str_repeat('-', $ancho_PV),0,1,'L');
		$pdf->Ln(3);
		$pdf->Ln(3);
		$pdf->Ln(3);
		$pdf->MultiCell($anchoFact,3, "_____________      _______________",0,'L');
		$pdf->MultiCell($anchoFact,3, "CONFORME      	    PROCESADO",0,'L');
		$pdf->MultiCell($anchoFact,3, "C.I./R.U.C      	  POR ".$filasUsuario[0]['Usuario'],0,'L');
		$pdf->MultiCell($anchoFact,3,"".$info['factura'][0]['RUC_CI']."      ".$info['factura'][0]['CodigoU'],0,'L');
	}else{
		if(count($info['factura']) > 0){
			if($info['factura'][0]['TC'] == 'PV'){
				$SubTotal = $info['factura'][0]['Total'];
				$Total = $info['factura'][0]['Total'];
				$Total_IVA = 0;
				$Total_Servicios = 0;
				$Total_Desc = 0;
			}else{
				$SubTotal = $info['factura'][0]['SubTotal'];
				$Total = $info['factura'][0]['Total_MN'];
				$Total_IVA = $info['factura'][0]['IVA'];
				$Total_Servicios = $info['factura'][0]['Servicio'];
				$Total_Desc = $info['factura'][0]['Descuento'];
			}
		}
		$pdf->MultiCell($anchoFact,3, str_repeat('-', $ancho_PV),0,'L');
		
		if(($ancho_PV - 26) > 0){$CantBlancos = str_repeat(' ', $ancho_PV-26);}else{$CantBlancos = "";}
		$pdf->MultiCell($anchoFact,3, "Cajero:        SUBTOTAL ".$this->SetearBlancos(strval($SubTotal), 12, 0, true, false, true),0,'L');
		$pdf->MultiCell($anchoFact,3, $Codigo1."       I.V.A ".strval($info['PorcIva']*100)."% ".$this->SetearBlancos(strval($Total_IVA), 12, 0, true, false, true),0,'L');
		if($Total_Servicios > 0){
			$pdf->MultiCell($anchoFact,3, $CantBlancos."     SERVICIO ".$this->SetearBlancos(strval($Total_Servicios), 12, 0, true, false, true),0,'L');
			
		}
		if($Total_Desc > 0){
			$pdf->MultiCell($anchoFact,3, $CantBlancos."    DESCUENTO ".$this->SetearBlancos(strval($Total_Desc), 12, 0, true, false, true),0,'L');
		}
		$pdf->MultiCell($anchoFact,3, str_repeat("=", $ancho_PV),0,'L');
		
		$Producto = "";
		if($info['factura'][0]['TC'] == 'PV'){
			$Producto = $CantBlancos."TOTAL TICKET  ";
		}else if($info['factura'][0]['TC'] == 'NV'){
			$Producto = $CantBlancos."TOTAL NOTA V. ";
		}else{
			$Producto = $CantBlancos."TOTAL FACTURA ";
		}
		$Producto .= $this->SetearBlancos(strval($Total), 12, 0, true, false, true);
		$pdf->MultiCell($anchoFact,3, $Producto,0,'L');
		
		if($Efectivo > 0){
			$pdf->MultiCell($anchoFact,3, $CantBlancos . "     EFECTIVO " . $this->SetearBlancos(strval($Efectivo), 12, 0, true, false, true),0,'L');
			$pdf->MultiCell($anchoFact,3, $CantBlancos . "       CAMBIO " . $this->SetearBlancos(strval($Efectivo - $Total), 12, 0, true, false, true),0,'L');
		}
		$pdf->Ln(3);
		if($info['factura'][0]['TC'] == 'PV'){
			$pdf->MultiCell($anchoFact,3, "ORIGINAL: CLIENTE",0,'L');
			$pdf->MultiCell($anchoFact,3, "COPIA   : EMISOR",0,'L');
			if($info['factura'][0]['Cotizacion'] > 0){
				$pdf->MultiCell($anchoFact,3, "COTIZACION: ".number_format($info['factura'][0]['Cotizacion'], 2, '.', ','),0,'L');
			}
		}
		$pdf->MultiCell($anchoFact,3, str_repeat("-", $ancho_PV),0,'L');
		if($info['factura'][0]['TC'] == 'PV'){
			$pdf->MultiCell($anchoFact,3, "RECLAME SU DOCUMENTO EN CAJA",0,'L');
		}
		$pdf->MultiCell($anchoFact,3, "Su Documento sera enviado al correo electronico registrado.",0,'L');
		$pdf->Ln(3);
		$pdf->MultiCell($anchoFact,3, str_repeat(" ", (int)round(($ancho_PV - 21)/2))."GRACIAS POR SU COMPRA",0,'L');
		$pdf->Ln(3);
	}
	
	if($descagar)
	{
		$pdf->Output(dirname(__DIR__,3).'/TEMP/'.$info['factura'][0]['Serie'].'-'.generaCeros($info['factura'][0]['Factura'],7).'.pdf','F');
	}else
	{
		$pdf->Output('');
	}
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