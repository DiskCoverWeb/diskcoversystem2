<?php

if(!class_exists('PDF_MC_Table'))
{
	require('PDF_MC_Table.php');
}
if (!class_exists('FPDF')) {
    //$mi_clase = new MiClase();
   require('fpdf.php');
}
require_once(dirname(__DIR__).'/fpdf/QRcode/qrcode.class.php');

//include(dirname(__DIR__,2)."/php/db/db.php");
//echo dirname(__DIR__,1);

/**
 * 
 */


class cabecera_pdf
{
	private $pdf;
	private $conn;
	private $header_cuerpo;
	private $pdf_sin_cabecera;
	private $fpdf;
	private $pdftable;
	private $fechafin;
	private $fechaini;
	private $sizetable;

	function __construct()
	{
		$this->fpdf = new FPDF();
		$this->pdf = new PDFv();
		$this->pdftable = new PDF_MC();
		$this->pdf_sin_cabecera = new PDF_MC_SIN_HEADER();
		$this->fechafin='';
		$this->fechaini='';
		$this->sizetable ='12';
		$this->conn = cone_ajax();
		
	}	

	function generarDetalle($parametros)
	{
		$titulo = $parametros['titulo'];
		$datos = $parametros['datos'];

		$pdf = new PDFdetalle($titulo, $datos);
		$pdf->AddPage();
		$pdf->SetFont('Times', '', 12);

		$pdf->generarPDF();
	}


	function cabecera_reporte($titulo,$tablaHTML,$contenido=false,$image=false,$fechaini="",$fechafin="",$sizetable="",$mostrar=false,$sal_hea_body=30,$orientacion='P')
	{	

	    $this->pdf->fechaini = $fechaini; 
	    $this->pdf->fechafin = $fechafin; 
	    $this->pdf->titulo = $titulo;
	    $this->pdf->salto_header_cuerpo = $sal_hea_body;
	    $this->pdf->orientacion = $orientacion;
		$this->pdf->AddPage();
		 if($image)
		 {
		  foreach ($image as $key => $value) {
		  	//print_r($value);		 	
		 	 	 $this->pdf->Image($value['url'], $value['x'],$value['y'],$value['width'],$value['height']);
		 	 	 $this->pdf->Ln(5);		 	 
		 }
		}

		if($contenido)
		{
		 foreach ($contenido as $key => $value) {
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='top-tabla')
		 	 {
		 	 	//print_r($value);
		 	 	$this->pdf->SetFont('Arial','',11);
		 	 	$this->pdf->MultiCell(0,3,$value['valor']);
		 	 	$this->pdf->Ln(5);

		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='top-tabla')
		 	 {
		 	 	$this->pdf->SetFont('Arial','',18);
		 	 	$this->pdf->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdf->Ln(5);

		 	 }
		 }
        }
		 $this->pdf->SetFont('Arial','',$sizetable);
		 $this->pdf->WriteHTML($tablaHTML);

		  if($contenido)
		  {
		 foreach ($contenido as $key => $value) {
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$this->pdf->SetFont('Arial','',11);
		 	 	$this->pdf->MultiCell(0,3,$value['valor']);
		 	 	$this->pdf->Ln(5);
		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$this->pdf->SetFont('Arial','',18);
		 	 	$this->pdf->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdf->Ln(5);
		 	 }
		 }
		}
		//echo $titulo;
		//die();
		 if($mostrar==true)
	       {
		    $this->pdf->Output();

	       }else
	       {
		     $this->pdf->Output('D',$titulo.'.pdf',false);

	      }

	}
 
 function cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$fechaini="",$fechafin="",$sizetable="",$mostrar=false,$sal_hea_body=15,$orientacion='P',$download = true, $repetirCabecera=null, $mostrar_cero=false,$nuevaPagina=false)
	{	

	    $this->pdftable->fechaini = $fechaini; 
	    $this->pdftable->fechafin = $fechafin; 
	    $this->pdftable->titulo = $titulo;
	    $this->pdftable->salto_header_cuerpo = $sal_hea_body;
	    $this->pdftable->orientacion = $orientacion;
	    $estiloRow='';
		 $this->pdftable->AddPage($orientacion);
		
		 if($image)
		 {
		  foreach ($image as $key => $value) {
		  	//print_r($value);		 	
		 	 	 $this->pdftable->Image($value['url'], $value['x'],$value['y'],$value['width'],$value['height']);
		 	 	 $this->pdftable->Ln(5);		 	 
		 }
		}



		if($contenido)
		{
		 foreach ($contenido as $key => $value) {
		 	if(!isset($value['estilo'])){$value['estilo'] = '';}
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='top-tabla')
		 	 {
		 	 	$siz = 11;
		 	 	$separacion = 4;
		 	 	if(isset($value['tamaño'])){$siz = $value['tamaño'];}
		 	 	if(isset($value['separacion'])){$separacion = $value['separacion'];}
		 	 	//print_r($value);
		 	 	$this->pdftable->SetFont('Arial',$value['estilo'],$siz);
		 	 	$this->pdftable->MultiCell(0,3,$value['valor']);
		 	 	$this->pdftable->Ln($separacion);

		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='top-tabla')
		 	 {
		 	 	$siz = 18;
		 	 	$separacion = 4;
		 	 	if(isset($value['tamaño'])){$siz = $value['tamaño'];}
		 	 	if(isset($value['separacion'])){$separacion = $value['separacion'];}
		 	 	$this->pdftable->SetFont('Arial','',$siz);
		 	 	$this->pdftable->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdftable->Ln($separacion);

		 	 }
		 }
        }
            $this->pdftable->SetFont('Arial','',$sizetable);
		    foreach ($tablaHTML as $key => $value){
		    	if (isset($value['newpag']) && $value['newpag']==1 && $key!=0) {			    	
			    	$this->pdftable->AddPage($orientacion);
				}
		    	$tama = 7;
		    	$esti = '';

		    	// if(isset($value['estilo']) && $value['estilo']!='')
		    	// {
		    	// 	$this->pdftable->SetFont('Arial',$value['estilo'],$sizetable);
		    	// 	$estiloRow = $value['estilo'];
		    	// }else
		    	// {
		    	// 	$this->pdftable->SetFont('Arial','',$sizetable);
		    	// 	$estiloRow ='';
		    	// }
		    	// if(isset($value['borde']) && $value['borde']!='0')
		    	// {
		    	// 	$borde=$value['borde'];
		    	// }else
		    	// {
		    	// 	$borde =0;
		    	// }

		    	if(isset($value['estilo']) && $value['estilo']!='')
		    	{
		    		$esti = $value['estilo'];
		    	}
		    	if(isset($value['size']) && $value['size']!='')
		    	{
		    		$tama = $value['size'];
		    	}

		    	$this->pdftable->SetFont('Arial',$esti,$tama);
		    	$estiloRow = $esti;



		    	if(isset($value['borde']) && $value['borde']!='0')
		    	{
		    		$borde=$value['borde'];
		    	}else
		    	{
		    		$borde =0;
		    	}


		    //print_r($value['medida']);
		       $this->pdftable->SetWidths($value['medidas']);
			   $this->pdftable->SetAligns($value['alineado']);
			   //print_r($value['datos']);
			   $arr= $value['datos'];
			   if(!is_null($repetirCabecera) && is_array($repetirCabecera)){
			   	$repetirCabecera['row']['medidas'] = $value['medidas'];
			   	$repetirCabecera['row']['alineado'] = $value['alineado'];
			   }
			   $this->pdftable->Row($arr,4,$borde,$estiloRow,null,$mostrar_cero,$repetirCabecera);		    	
		    }
		

		  if($contenido)
		  {
		 foreach ($contenido as $key => $value) {
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$siz = 11;
		 	 	if(isset($value['tamaño'])){$siz = $value['tamaño'];}
		 	 	$this->pdftable->SetFont('Arial','',$siz);
		 	 	$this->pdftable->MultiCell(0,3,$value['valor']);
		 	 	$this->pdftable->Ln(5);
		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$siz = 18;
		 	 	if(isset($value['tamaño'])){$siz = $value['tamaño'];}
		 	 	$this->pdftable->SetFont('Arial','',$siz);
		 	 	$this->pdftable->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdftable->Ln(5);
		 	 }
		 }
		}
		//echo $titulo;
		//die();
		if ($download) {	
		 if($mostrar==true)
	       {
		    $this->pdftable->Output('',$titulo.'.pdf');

	       }else
	       {
		     $this->pdftable->Output('D',$titulo.'.pdf',false);

	      }
		}else{
			$this->pdftable->Output('F',dirname(__DIR__,2).'/TEMP/'.$titulo.'.pdf');
		}
		
	}

	function formatoPDFMatricial($HTML,$parametros,$datos_pre,$datos_empresa,$descagar=false)
	{	
		// $orientation='P',$unit='mm', array(45,350)
		$pdf = new FPDF();
		$pdf->setMargins(2,15, 11.7);
		$pdf->SetFont('Courier','',8);
		$pdf->AddPage('P');
		$pdf->Cell(0,0,'Transaccion ('.$parametros['TC'].'): No. '.$datos_pre['lineas'][0]['Serie'].'-'.$datos_pre['lineas'][0]['Factura']);
		$pdf->Ln(5);
		$pdf->Cell(0,0,'Autorizaion: ');
		$pdf->Ln(5);
		$pdf->SetFont('Courier','',7);
		$pdf->Cell(0,0,$datos_pre['lineas'][0]['Autorizacion']);
		$pdf->SetFont('Courier','',8);
		$pdf->Ln(5);
		$pdf->Cell(0,0,'Fecha: '.date('Y-m-d').' - Hora: '.date('H:m:s'));
		$pdf->Ln(5);
		$pdf->Cell(0,0,'Cliente: '.$datos_pre['cliente']['Cliente']);
		if ($datos_empresa[0]['Micro_2021']== '1') {
			$pdf->Ln(5);
			$pdf->Cell(0,0,'MICROEMPRESA');
		}
		if ($datos_empresa[0]['Agente_Retencion']!='.') 
		{
			$pdf->Ln(5);
			$pdf->Cell(0,0,mb_convert_encoding('Agente Retención: '.$datos_empresa[0]['agente_retencion'], 'UTF-8'));
		}		
		$pdf->Ln(5);
		$pdf->Cell(0,0,'R.U.C/C.I.: '.$datos_pre['cliente']['CI_RUC']);
		$pdf->Ln(5);
		$pdf->Cell(0,0,'Cajero: '.$_SESSION['INGRESO']['Nombre']);
		$pdf->Ln(5);
		$pdf->Cell(0,0,'Telefono: '.$datos_pre['cliente']['Telefono']);
		$pdf->Ln(5);
		$pdf->Cell(0,0,mb_convert_encoding('Dirección: '.$datos_pre['cliente']['Direccion'], 'ISO-8859-1','UTF-8'));
		$pdf->Ln(5);
		$pdf->Cell(0,0,'Producto/Cant x PVP/Total '.$datos_pre['cliente']['Direccion']);
		$pdf->Ln(5);
		$pdf->Cell(0,0,'--------------------------------------------');
		$pdf->Ln(5);
		foreach ($datos_pre['lineas'] as $key => $value) {
			$pdf->Cell(0,0,$value['Producto']);
			$pdf->Ln(2);
			$pdf->Cell(40,6,$value['Cantidad'].' X '.number_format($value['Precio2'],2),'',0);
			$pdf->Cell(35,6,number_format($value['Total'],2),'',0,'R');
			$pdf->Ln(5);
    	}
    	$pdf->Ln(5);
		$pdf->Cell(0,0,'--------------------------------------------');
		$pdf->Ln(4);
    	$pdf->Cell(55,6,'SUBTOTAL:','',0,'R');
		$pdf->Cell(20,6,number_format($datos_pre['tota'],2),'',0,'R');
		$pdf->Ln(4);
    	$pdf->Cell(55,6,'I.V.A 12%:','',0,'R');
		$pdf->Cell(20,6,number_format($datos_pre['iva'],2),'',0,'R');
		$pdf->Ln(4);
    	$pdf->Cell(55,6,'TOTAL FACTURA:','',0,'R');
		$pdf->Cell(20,6,number_format($datos_pre['tota'],2),'',0,'R');
		$pdf->Ln(4);
    	$pdf->Cell(55,6,'EFECTIVO:','',0,'R');
		$pdf->Cell(20,6,number_format($parametros['efectivo'],2),'',0,'R');
		$pdf->Ln(4);
    	$pdf->Cell(55,6,'CAMBIO:','',0,'R');
		$pdf->Cell(20,6,number_format($parametros['saldo'],2),'',0,'R');
		$pdf->Ln(4);
    	$pdf->Cell(0,6,'ORIGINAL: CLIENTE','');
    	$pdf->Ln(4);
    	$pdf->Cell(0,6,'COPIA:    EMISOR','');
		$pdf->Ln(5);
		$pdf->Cell(0,0,'--------------------------------------------');
		$pdf->Ln(5);
		$pdf->Cell(75,0,'Email:'.$datos_pre['cliente']['Email'],'',0,'L');
		$pdf->Ln(5);
		$pdf->Cell(75,0,'Fue un placer atenderle','',0,'C');
		$pdf->Ln(5);
		$pdf->Cell(75,0,'Gracias por su compra','',0,'C');

		if($descagar)
		{
			$pdf->Output('F',dirname(__DIR__,2).'/TEMP/'.$datos_pre['lineas'][0]['Autorizacion'].'.pdf');
		}else
		{
     		$pdf->Output();
		}
		
		// $this->FPDF->AddPage('P');
 	//  	$this->pdftable->SetFont('Arial','',18);
 	//  	$this->pdftable->Cell(0,3,'Prueba',0,0,'C');
 	//  	$this->pdftable->Ln(5);
		// //$this->pdftable->WriteHTML($HTML);
		// $this->pdftable->Output();
	}

	function Imprimir_Punto_Venta_Grafico($info,$descagar=true)
	{	
		// print_r($info);die();
		// print_r($_SESSION['INGRESO']);
		// $orientation='P',$unit='mm', array(45,350)
		if(isset($_SESSION['INGRESO']['Logo_Tipo']))
		   {
		   	$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		   	//si es jpg
		   	$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.jpg'; 
		   	if(!file_exists($src))
		   	{
		   		$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.gif'; 
		   		if(!file_exists($src))
		   		{
		   			$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.png'; 
		   			if(!file_exists($src))
		   			{
		   				$logo="diskcover_web";
		                $src= dirname(__DIR__,2).'/img/logotipos/'.$logo.'.gif';

		   			}

		   		}

		   	}
		  }


		$pdf = new FPDF();
		$pdf->setMargins(2,5);
		$pdf->SetFont('Arial','B',8);
		$pdf->AddPage('P');
		// print_r($info);die();
		
        $pdf->Image($src,5,5,25,10); 

		$pdf->SetX(45);
		$pdf->Cell(25,5,'R.U.C',0,1);
		$pdf->SetX(40);
		$pdf->Cell(25,5,$_SESSION['INGRESO']['RUC'],0,1);		
		$pdf->SetX(40);
		$pdf->Cell(0,0,'Telefono: '.$_SESSION['INGRESO']['Telefono1'],'',1);
		$pdf->Ln(5);	
		if($_SESSION['INGRESO']['Nombre_Comercial']==$_SESSION['INGRESO']['Razon_Social'])
		{
			$pdf->Cell(70,0,$_SESSION['INGRESO']['Razon_Social'],0,1,'C');
			$pdf->Ln(5);
		}else{
			$pdf->Cell(70,0,$_SESSION['INGRESO']['Razon_Social'],0,1,'C');
			$pdf->Ln(5);
			$pdf->Cell(70,0,$_SESSION['INGRESO']['Nombre_Comercial'],0,1,'C');
			$pdf->Ln(5);
		}
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(0,0,'Direccion Matriz:');
		$pdf->Ln(5);		
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(0,0,$_SESSION['INGRESO']['Direccion']);
		$pdf->SetFont('Arial','B',7);
		$pdf->Ln(5);
		$pdf->Cell(0,0,'FECHA DE EMISION: '.$info['factura'][0]['Fecha']->format('Y-m-d'),0,1);
		$pdf->Ln(3);		
		$pdf->Cell(0,0,'DOCUMENTO DE FA No. '.$info['factura'][0]['Serie'].'-'.generaCeros($info['factura'][0]['Factura'],7),0,1);
		$pdf->Ln(3);
		$amb = 'PRUEBA';
		if($_SESSION['INGRESO']['Ambiente']==2){$amb='PRODUCCION';}
		$pdf->Cell(0,0,'AMBIENTE:'.$amb,0,1);
		$pdf->Ln(3);
		$pdf->Cell(0,0,'CLAVE DE ACCESO:',0,1);
		$pdf->Ln(3);
		$pdf->Cell(0,0,$info['CLAVE'],0,1);
		$pdf->Ln(3);
		$pdf->SetFont('Arial','',7);
		$l = $pdf->GetY();
		$pdf->Line(0,$l,70,$l);
		$pdf->SetFont('Arial','B',7);
		$pdf->Ln(5);
		$pdf->Cell(70,0,'Razon Social/Nombres y Apellidos: ');
		$pdf->SetFont('Arial','',7);
		$pdf->Ln(5);
		$pdf->Cell(70,0,mb_convert_encoding( $info['factura'][0]['Razon_Social'], 'UTF-8'));
		$pdf->Ln(3);
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(18,0,'Identificacion: ');
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(20,0,$info['factura'][0]['RUC_CI']);
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(15,0,'Telef.:');
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(17,0,$info['factura'][0]['Telefono']);
		$pdf->Ln(3);
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(70,0,'Correo Electronico:');
		$pdf->Ln(3);		
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(70,0,$info['factura'][0]['Email']);
		$pdf->Ln(5);		
		$l = $pdf->GetY();
		$pdf->Line(0,$l,70,$l);
		$pdf->Ln(3);
		$pdf->SetFont('Arial','B',6);
		$pdf->Cell(8,0,'Cant.');
		$pdf->Cell(39,0,'P R O D U C T O');
		$pdf->Cell(10,0,'P.V.P.',0,0,'R');
		$pdf->Cell(10,0,'TOTAL',0,1,'R');
		$pdf->Ln(3);		
		$pdf->SetFont('Arial','',7);
		$l = $pdf->GetY();
		$pdf->Line(0,$l,70,$l);
		$pdf->Ln(3);
		$pdf->SetFont('Arial','',6);

		foreach ($info['lineas'] as $key => $value) {
			$y = $pdf->GetY();
			$pdf->Cell(8,2,$value['Cantidad']);
			$pdf->MultiCell(35,2,mb_convert_encoding($value['Producto'], 'ISO-8859-1','UTF-8'));
			$pdf->SetXY(48,$y);
			$pdf->Cell(10,2,number_format($value['Precio'],2,'.',''),0,0,'R');
			$pdf->Cell(10,2,number_format($value['Total'],2,'.',''),0,1,'R');
			$pdf->Ln(3);
			// $pdf->Row($value,null,1);
    	}

		$l = $pdf->GetY();
		$pdf->Line(0,$l,70,$l);

		$pdf->Ln(5);
		$pdf->Cell(36,0,'Cajero: 0702X79');
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(20,0,'SUBTOTAL.');		
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(10,0,number_format($info['factura'][0]['SubTotal'],2,'.',''),0,0,'R');
		$pdf->Ln(3);

		$pdf->Cell(36,0,'');
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(20,0,'DESCUENTO');		
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(10,0,number_format($info['factura'][0]['Descuento'],2,'.',''),0,0,'R');
		$pdf->Ln(3);

		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(36,0,'');
		$iva = floatval($info['PorcIva']) . "%";
		$pdf->Cell(20,0,'I.V.A. ' . $iva);
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(10,0,number_format($info['factura'][0]['IVA'],2,'.',''),0,0,'R');
		$pdf->Ln(3);

		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(36,0,'');
		$pdf->Cell(20,0,'T O T A L');		
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(10,0,number_format($info['factura'][0]['Total_MN'],2,'.',''),0,0,'R');
		$pdf->Ln(5);
		$l = $pdf->GetY();
		$pdf->Line(0,$l,70,$l);
		$pdf->Ln(5);

		$pdf->SetFont('Arial','',11);
		$pdf->Cell(70,0,'GRACIAS POR SU COLABORACION',0,1,'C');
		$pdf->SetFont('Arial','',7);
		$pdf->Ln(3);
		$pdf->Cell(70,0,'www.diskcoversystem.com',0,1,'C');



		if($descagar)
		{
			$pdf->Output('F',dirname(__DIR__,2).'/TEMP/'.$info['factura'][0]['Serie'].'-'.generaCeros($info['factura'][0]['Factura'],7).'.pdf');
		}else
		{
     		$pdf->Output();
		}
		
		// $this->FPDF->AddPage('P');
 	//  	$this->pdftable->SetFont('Arial','',18);
 	//  	$this->pdftable->Cell(0,3,'Prueba',0,0,'C');
 	//  	$this->pdftable->Ln(5);
		// //$this->pdftable->WriteHTML($HTML);
		// $this->pdftable->Output();
	}

	function Imprimir_Punto_Venta($info,$descagar=true)
	{	
		// print_r($info);die();
		// print_r($_SESSION['INGRESO']);
		// $orientation='P',$unit='mm', array(45,350)
		if(isset($_SESSION['INGRESO']['Logo_Tipo']))
		   {
		   	$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		   	//si es jpg
		   	$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.jpg'; 
		   	if(!file_exists($src))
		   	{
		   		$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.gif'; 
		   		if(!file_exists($src))
		   		{
		   			$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.png'; 
		   			if(!file_exists($src))
		   			{
		   				$logo="diskcover_web";
		                $src= dirname(__DIR__,2).'/img/logotipos/'.$logo.'.gif';

		   			}

		   		}

		   	}
		  }


		$pdf = new FPDF();
		$pdf->setMargins(2,5);
		$pdf->SetFont('Arial','B',8);
		$pdf->AddPage('P');
		// print_r($info);die();
		
        $pdf->Image($src,5,5,25,10); 

		$pdf->SetX(45);
		$pdf->Cell(25,5,'R.U.C',0,1);
		$pdf->SetX(40);
		$pdf->Cell(25,5,$_SESSION['INGRESO']['RUC'],0,1);		
		$pdf->SetX(40);
		$pdf->Cell(0,0,'Telefono: '.$_SESSION['INGRESO']['Telefono1'],'',1);
		$pdf->Ln(5);	
		if($_SESSION['INGRESO']['Nombre_Comercial']==$_SESSION['INGRESO']['Razon_Social'])
		{
			$pdf->Cell(70,0,$_SESSION['INGRESO']['Razon_Social'],0,1,'C');
			$pdf->Ln(5);
		}else{
			$pdf->Cell(70,0,$_SESSION['INGRESO']['Razon_Social'],0,1,'C');
			$pdf->Ln(5);
			$pdf->Cell(70,0,$_SESSION['INGRESO']['Nombre_Comercial'],0,1,'C');
			$pdf->Ln(5);
		}
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(0,0,'Direccion Matriz:');
		$pdf->Ln(5);		
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(0,0,$_SESSION['INGRESO']['Direccion']);
		$pdf->SetFont('Arial','B',7);
		$pdf->Ln(5);
		$pdf->Cell(0,0,'FECHA DE EMISION: '.$info['factura'][0]['Fecha']->format('Y-m-d'),0,1);
		$pdf->Ln(3);		
		$pdf->Cell(0,0,'DOCUMENTO DE FA No. '.$info['factura'][0]['Serie'].'-'.generaCeros($info['factura'][0]['Factura'],7),0,1);
		$pdf->Ln(3);
		$amb = 'PRUEBA';
		if($_SESSION['INGRESO']['Ambiente']==2){$amb='PRODUCCION';}
		$pdf->Cell(0,0,'AMBIENTE:'.$amb,0,1);
		$pdf->Ln(3);
		$pdf->Cell(0,0,'CLAVE DE ACCESO:',0,1);
		$pdf->Ln(3);
		$pdf->Cell(0,0,$info['CLAVE'],0,1);
		$pdf->Ln(3);
		$pdf->SetFont('Arial','',7);
		$l = $pdf->GetY();
		$pdf->Line(0,$l,70,$l);
		$pdf->SetFont('Arial','B',7);
		$pdf->Ln(5);
		$pdf->Cell(70,0,'Razon Social/Nombres y Apellidos: ');
		$pdf->SetFont('Arial','',7);
		$pdf->Ln(5);
		$pdf->Cell(70,0,mb_convert_encoding( $info['factura'][0]['Razon_Social'], 'UTF-8'));
		$pdf->Ln(3);
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(18,0,'Identificacion: ');
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(20,0,$info['factura'][0]['RUC_CI']);
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(15,0,'Telef.:');
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(17,0,$info['factura'][0]['Telefono']);
		$pdf->Ln(3);
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(70,0,'Correo Electronico:');
		$pdf->Ln(3);		
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(70,0,$info['factura'][0]['Email']);
		$pdf->Ln(5);		
		$l = $pdf->GetY();
		$pdf->Line(0,$l,70,$l);
		$pdf->Ln(3);
		$pdf->SetFont('Arial','B',6);
		$pdf->Cell(8,0,'Cant.');
		$pdf->Cell(39,0,'P R O D U C T O');
		$pdf->Cell(10,0,'P.V.P.',0,0,'R');
		$pdf->Cell(10,0,'TOTAL',0,1,'R');
		$pdf->Ln(3);		
		$pdf->SetFont('Arial','',7);
		$l = $pdf->GetY();
		$pdf->Line(0,$l,70,$l);
		$pdf->Ln(3);
		$pdf->SetFont('Arial','',6);

		foreach ($info['lineas'] as $key => $value) {
			$y = $pdf->GetY();
			$pdf->Cell(8,2,$value['Cantidad']);
			$pdf->MultiCell(35,2,mb_convert_encoding($value['Producto'], 'ISO-8859-1','UTF-8'));
			$pdf->SetXY(48,$y);
			$pdf->Cell(10,2,number_format($value['Precio'],2,'.',''),0,0,'R');
			$pdf->Cell(10,2,number_format($value['Total'],2,'.',''),0,1,'R');
			$pdf->Ln(3);
			// $pdf->Row($value,null,1);
    	}

		$l = $pdf->GetY();
		$pdf->Line(0,$l,70,$l);

		$pdf->Ln(5);
		$pdf->Cell(36,0,'Cajero: 0702X79');
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(20,0,'SUBTOTAL.');		
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(10,0,number_format($info['factura'][0]['SubTotal'],2,'.',''),0,0,'R');
		$pdf->Ln(3);

		$pdf->Cell(36,0,'');
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(20,0,'DESCUENTO');		
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(10,0,number_format($info['factura'][0]['Descuento'],2,'.',''),0,0,'R');
		$pdf->Ln(3);

		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(36,0,'');
		$iva = floatval($info['PorcIva']) . "%";
		$pdf->Cell(20,0,'I.V.A. ' . $iva);
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(10,0,number_format($info['factura'][0]['IVA'],2,'.',''),0,0,'R');
		$pdf->Ln(3);

		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(36,0,'');
		$pdf->Cell(20,0,'T O T A L');		
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(10,0,number_format($info['factura'][0]['Total_MN'],2,'.',''),0,0,'R');
		$pdf->Ln(5);
		$l = $pdf->GetY();
		$pdf->Line(0,$l,70,$l);
		$pdf->Ln(5);

		$pdf->SetFont('Arial','',11);
		$pdf->Cell(70,0,'GRACIAS POR SU COLABORACION',0,1,'C');
		$pdf->SetFont('Arial','',7);
		$pdf->Ln(3);
		$pdf->Cell(70,0,'www.diskcoversystem.com',0,1,'C');



		if($descagar)
		{
			$pdf->Output('F',dirname(__DIR__,2).'/TEMP/'.$info['factura'][0]['Serie'].'-'.generaCeros($info['factura'][0]['Factura'],7).'.pdf');
		}else
		{
     		$pdf->Output();
		}
		
		// $this->FPDF->AddPage('P');
 	//  	$this->pdftable->SetFont('Arial','',18);
 	//  	$this->pdftable->Cell(0,3,'Prueba',0,0,'C');
 	//  	$this->pdftable->Ln(5);
		// //$this->pdftable->WriteHTML($HTML);
		// $this->pdftable->Output();
	}


	function DeudapendientePensionesPDF($titulo,$tablaHTML,$contenido=false,$image=false,$fechaini="",$fechafin="",$sizetable="",$mostrar=false,$sal_hea_body=30,$orientacion='P',$download=true)
	{	
		$this->pdf_sin_cabecera->AddPage($orientacion);


	   $this->pdf_sin_cabecera->SetFont('Arial','',$sizetable);
	    foreach ($tablaHTML as $key => $value){
	    	if(isset($value['estilo']) && $value['estilo']!='')
	    	{
	    		$this->pdf_sin_cabecera->SetFont('Arial',$value['estilo'],$sizetable);
	    		$estiloRow = $value['estilo'];
	    	}else
	    	{
	    		$this->pdf_sin_cabecera->SetFont('Arial','',$sizetable);
	    		$estiloRow ='';
	    	}
	    	if(isset($value['borde']) && $value['borde']!='0')
	    	{
	    		$borde=$value['borde'];
	    	}else
	    	{
	    		$borde =0;
	    	}

	    //print_r($value['medida']);
	       $this->pdf_sin_cabecera->SetWidths($value['medidas']);
		   $this->pdf_sin_cabecera->SetAligns($value['alineado']);
		   //print_r($value['datos']);
		   $arr= $value['datos'];
		   $this->pdf_sin_cabecera->Row($arr,4,$borde,$estiloRow,null,$cero=true);		    	
	    }
		
		// print_r($_SESSION['INGRESO']);die();
		$this->pdf_sin_cabecera->Ln(30);
		$this->pdf_sin_cabecera->Cell(0,3,'--------------------------------------',0,0,'L');	
		$this->pdf_sin_cabecera->Ln(5);	
		$this->pdf_sin_cabecera->Cell(0,3,strtoupper($_SESSION['INGRESO']['Nombre']),0,0,'L');
		$this->pdf_sin_cabecera->Ln(5);
		$this->pdf_sin_cabecera->Cell(0,3,strtoupper('COLECTURIA'),0,0,'L');					
		

		if ($download) 
		{	
		 if($mostrar==true)
	       {
	       	//muestra en pantalla
		    $this->pdf_sin_cabecera->Output();
	       }else
	       {
	       	//descarga el pdf
		     $this->pdf_sin_cabecera->Output('D',$titulo.'.pdf',false);

	      }
		}else{
			//descarga en temporales
			$this->pdf_sin_cabecera->Output('F',dirname(__DIR__,2).'/php/vista/TEMP/'.$titulo.'.pdf');
		}
		
	}


	function cabecera_reporte_colegio_matricula($titulo,$tablaHTML,$name_doc=false,$contenido=false,$image=false,$qr=false,$fechaini="",$fechafin="",$sizetable="",$mostrar=false,$sal_hea_body=15,$orientacion='P',$download=true, $repetirCabecera=null, $mostrar_cero=false)
	{	



	  $this->pdftable->fechaini = $fechaini; 
	    $this->pdftable->fechafin = $fechafin; 
	    $this->pdftable->titulo = $titulo;
	    $this->pdftable->salto_header_cuerpo = $sal_hea_body;
	    $this->pdftable->orientacion = $orientacion;
	    $estiloRow='';
		 $this->pdftable->AddPage($orientacion);
		 if($image)
		 {
		  foreach ($image as $key => $value) {
		  	//print_r($value);		 	
		 	 	 $this->pdftable->Image($value['url'], $value['x'],$value['y'],$value['width'],$value['height']);
		 	 	 $this->pdftable->Ln(5);		 	 
		 }
		}

		if($qr)
		{

			$rutaQR = dirname(__DIR__,2).'/TEMP/';
			if(!file_exists($rutaQR))
			{
				 mkdir(dirname(__DIR__,2).'/TEMP/', 0777, true);
			}

			if($qr['dato_qr']!='' && $qr['dato_qr']!='.')
			{
				$mensaje = mb_convert_encoding($qr['dato_qr'], 'UTF-8', 'ISO-8859-1');
			}else{
				$mensaje = 'https://www.diskcoversystem.com';
			}

			$qrcode = new QRcode($mensaje,'L');
			$rs = $qrcode->displayPNG($qr['width'],array(255,255,255),array(0,0,0),$rutaQR.$qr['name_qr'].'.png');
			if($rs)
			{

				$this->pdftable->Image($rutaQR.$qr['name_qr'].'.png', $qr['x'],$qr['y'],$qr['width'],$qr['height']);
				$this->pdftable->Ln(5);	
			}
		}

		if($contenido)
		{
		 foreach ($contenido as $key => $value) {
		 	if(!isset($value['estilo'])){$value['estilo'] = '';}
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='top-tabla')
		 	 {
		 	 	$siz = 11;
		 	 	$separacion = 4;
		 	 	if(isset($value['tamaño'])){$siz = $value['tamaño'];}
		 	 	if(isset($value['separacion'])){$separacion = $value['separacion'];}
		 	 	//print_r($value);
		 	 	$this->pdftable->SetFont('Arial',$value['estilo'],$siz);
		 	 	$this->pdftable->MultiCell(0,3,$value['valor']);
		 	 	$this->pdftable->Ln($separacion);

		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='top-tabla')
		 	 {
		 	 	$siz = 18;
		 	 	$separacion = 4;
		 	 	if(isset($value['tamaño'])){$siz = $value['tamaño'];}
		 	 	if(isset($value['separacion'])){$separacion = $value['separacion'];}
		 	 	$this->pdftable->SetFont('Arial','',$siz);
		 	 	$this->pdftable->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdftable->Ln($separacion);

		 	 }
		 }
        }
                $this->pdftable->SetFont('Arial','',$sizetable);
                // $this->pdftable->SetX(65);
		    foreach ($tablaHTML as $key => $value){
		    	if(isset($value['MT']))
		    	{
		    		$n = $this->pdftable->GetY();
		    		$this->pdftable->SetY($value['MT']+$n);
		    	}
		    	if(isset($value['ML']))
		    	{
		    		$this->pdftable->SetX($value['ML']);
		    	}else{

		    		$this->pdftable->SetX(10);
		    	}
		    	if(isset($value['estilo']) && $value['estilo']!='')
		    	{
		    		$this->pdftable->SetFont('Arial',$value['estilo'],$sizetable);
		    		$estiloRow = $value['estilo'];
		    	}else
		    	{
		    		$this->pdftable->SetFont('Arial','',$sizetable);
		    		$estiloRow ='';
		    	}
		    	if(isset($value['borde']) && $value['borde']!='0')
		    	{
		    		$borde=$value['borde'];
		    	}else
		    	{
		    		$borde =0;
		    	}

		    //print_r($value['medida']);
		       $this->pdftable->SetWidths($value['medidas']);
			   $this->pdftable->SetAligns($value['alineado']);
			   //print_r($value['datos']);
			   $arr= $value['datos'];
			   if(!is_null($repetirCabecera) && is_array($repetirCabecera)){
			   	$repetirCabecera['row']['medidas'] = $value['medidas'];
			   	$repetirCabecera['row']['alineado'] = $value['alineado'];
			   }
			   $this->pdftable->Row($arr,4,$borde,$estiloRow,null,$mostrar_cero,$repetirCabecera);	
			   if(isset($value['ML']))
		    	{
		    		$this->pdftable->SetX($value['ML']);
		    	}	else{

		    		$this->pdftable->SetX(10);
		    	}
		    	//  if(isset($value['MT']))
		    	// {
		    	// 	$n = $this->pdftable->GetY();
		    	// 	$this->pdftable->SetY($value['MT']+$n);
		    	// }
			   // $this->pdftable->SetX(65);    	
		    }
		

		  if($contenido)
		  {
		 foreach ($contenido as $key => $value) {
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$siz = 11;
		 	 	if(isset($value['tamaño'])){$siz = $value['tamaño'];}
		 	 	$this->pdftable->SetFont('Arial','',$siz);
		 	 	$this->pdftable->MultiCell(0,3,$value['valor']);
		 	 	$this->pdftable->Ln(5);
		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$siz = 18;
		 	 	if(isset($value['tamaño'])){$siz = $value['tamaño'];}
		 	 	$this->pdftable->SetFont('Arial','',$siz);
		 	 	$this->pdftable->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdftable->Ln(5);
		 	 }
		 }
		}
		// echo $titulo;
		// die();
		 if($name_doc){$titulo = $name_doc;}
		if ($download) {	

			$ruta = dirname(__DIR__,2).'/TEMP/'.$titulo.'.pdf';
			if(!file_exists(dirname(__DIR__,2).'/TEMP/'))
			{
				 mkdir(dirname(__DIR__,2).'/TEMP/', 0777, true);    
			}
			try {
				$this->pdftable->Output('F',$ruta);
				return 1;				
			} catch (Exception $e) {
				return -1;				
			}
		 
		}else{

			if($mostrar==true)
	       {
		    $this->pdftable->Output('',$titulo.'.pdf');

	       }else
	       {
		     $this->pdftable->Output('D',$titulo.'.pdf',false);

	      }
			
		}

	}



	function cabecera_reporte_colegio($titulo,$nombre,$tablaHTML,$contenido=false,$image=false,$fechaini="",$fechafin="",$sizetable="",$mostrar=false,$email=false,$sal_hea_body=30)
	{	


	   $cid=$this->conn;
	   $sql = "SELECT * from Catalogo_Periodo_Lectivo where Item='".$_SESSION['INGRESO']['item']."'";
	   $stmt = sqlsrv_query($cid, $sql);
	    if( $stmt === false)  
	      {  
		     echo "Error en consulta PA.\n";  
		     return '';
		     die( print_r( sqlsrv_errors(), true));  
	      }   

	    $result = array();	
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
	      {
	    	$result[] = $row;
		    //echo $row[0];
	      }
	      //print_r($result[0]);
	      $_SESSION['INGRESO']['Nombre_Comercial'] =mb_convert_encoding($result[0]['Institucion1'], 'ISO-8859-1','UTF-8');
	      $_SESSION['INGRESO']['noempr']=mb_convert_encoding($result[0]['Institucion2'], 'ISO-8859-1','UTF-8');
	      $_SESSION['INGRESO']['Logo_Tipo'] = $result[0]['Logo_Tipo'];
	     // return $result;
//print($result[0]['Logo_Tipo']);

	    $this->pdf->fechaini = $fechaini; 
	    $this->pdf->fechafin = $fechafin; 
	    $this->pdf->titulo = $nombre;
	    $this->pdf->salto_header_cuerpo = $sal_hea_body;

		 $this->pdf->AddPage();
		 if($image)
		 {
		  foreach ($image as $key => $value) {
		  	//print_r($value);		 	
		 	 	 $this->pdf->Image($value['url'], $value['x'],$value['y'],$value['width'],$value['height']);
		 	 	// $this->pdf->Ln(5);		 	 
		 }
		}

		if($contenido)
		{
		 foreach ($contenido as $key => $value) {
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='top-tabla')
		 	 {
		 	 	//print_r($value);
		 	 	$this->pdf->SetFont('Arial','',11);
		 	 	$this->pdf->MultiCell(0,3,$value['valor']);
		 	 	$this->pdf->Ln(5);

		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='top-tabla')
		 	 {
		 	 	$this->pdf->SetFont('Arial','',18);
		 	 	$this->pdf->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdf->Ln(5);

		 	 }
		 }
        }
		 $this->pdf->SetFont('Arial','',$sizetable);
		 $this->pdf->WriteHTML($tablaHTML);

		  if($contenido)
		  {
		 foreach ($contenido as $key => $value) {
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$this->pdf->SetFont('Arial','',11);
		 	 	$this->pdf->MultiCell(0,3,$value['valor']);
		 	 	$this->pdf->Ln(5);
		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$this->pdf->SetFont('Arial','',18);
		 	 	$this->pdf->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdf->Ln(5);
		 	 }
		 }
		}
		//echo $titulo;
		//die();
		 if($mostrar==true)
	       {
		    $this->pdf->Output();

	       }else
	      { // $this->pdf->Output('D',$titulo.'.pdf',false);
		    if(file_exists(dirname(__DIR__,2).'/TEMP/'.$titulo.'.pdf'))
		      {
		      	 //unset('../../php/vista/TEMP/'.$titulo.'.pdf');
		      	unlink(dirname(__DIR__,2).'/TEMP/'.$titulo.'.pdf');  
		      }

		     if($email != 'false')
		     {
		       $this->pdf->Output('F',dirname(__DIR__,2).'/TEMP/'.$titulo.'.pdf');	
		     }
		     else
		     {
		      $this->pdf->Output('F',dirname(__DIR__,2).'/TEMP/'.$titulo.'.pdf');
		      $this->pdf->Output('D',$titulo.'.pdf',false);
		     }

	      }

	}
	function Imprimir_Codigo_Banco($datosAdo, $path){
		$pdf = new FPDF();
		$pdf->AddPage();
		foreach ($datosAdo as $dato) {
			// Añadir los detalles de la empresa
			if(strtoupper($_SESSION['INGRESO']['noempr']) <> strtoupper($_SESSION['INGRESO']['Nombre_Comercial'])){
				$pdf->SetFont('Arial', 'B', 13);
				$pdf->Cell(0, 10, strtoupper($_SESSION['INGRESO']['noempr']) . " - " . strtoupper($_SESSION['INGRESO']['Ciudad']), 0, 1, 'C');
				$pdf->SetFont('Arial', 'I', 8);
				$Cadena = $_SESSION['INGRESO']['Ciudad'] . ", " . $_SESSION['INGRESO']['Direccion'] . mb_convert_encoding("Teléfono:", 'ISO-8859-1', 'UTF-8') . $_SESSION['INGRESO']['Telefono1'] . ".";
				$pdf->Cell(0, 10, $Cadena, 0, 1, 'C');
				$pdf->SetFont('Arial', '', 8);
			}else{
				$pdf->SetFont('Arial', 'B', 14);
				$pdf->Cell(0, 10, strtoupper($_SESSION['INGRESO']['noempr']), 0, 1, 'C');
				$pdf->SetFont('Arial', 'I', 8);
				$Cadena = $_SESSION['INGRESO']['Ciudad'] . ", " . $_SESSION['INGRESO']['Direccion'] . mb_convert_encoding("Teléfono:", 'ISO-8859-1', 'UTF-8') . $_SESSION['INGRESO']['Telefono1'] . ".";
				$pdf->Cell(0, 10, $Cadena, 0, 1, 'C');
				$pdf->SetFont('Arial', '', 8);
			}
			$pdf->SetFont('Arial', 'B', 10);
			$Cadena = mb_convert_encoding("Señores de: ", 'ISO-8859-1', 'UTF-8') . $_SESSION['INGRESO']['noempr'] . " - " . $_SESSION['INGRESO']['Ciudad'] . ".";
			$pdf->Cell(45, 10, $Cadena, 0, 1);
			$pdf->SetFont('Arial', '', 10);
			$Cadena = "Reciban un cordial saludo de quienes conformamos DiskCover System, por medio de la presente le informamos a ustedes que el sistema de pagos es con el " .  mb_convert_encoding("código ", 'ISO-8859-1', 'UTF-8') . "siguiente: ";
			$pdf->MultiCell(0, 5, $Cadena, 0, 'J');
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->Cell(40, 10, 'ESTUDIANTE: ', 0, 0);
			$pdf->SetFont('Arial', '', 10);
			$pdf->Cell(40, 10, $dato['Cliente'], 0, 1);
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->Cell(40, 10, mb_convert_encoding("DIRECCIÓN: ", 'ISO-8859-1', 'UTF-8'), 0, 0);
			$pdf->SetFont('Arial', '', 10);
			$pdf->SetX(37);
			$pdf->Cell(40, 10, $dato['Direccion'], 0, 1);
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->Cell(40, 10, mb_convert_encoding("CÓDIGO DEL BANCO:", 'ISO-8859-1', 'UTF-8'), 0, 0); // El tercer parámetro '0' indica que no queremos que el cursor se mueva a la siguiente línea después de esta celda.
			$pdf->SetFont('Arial', '', 16);
			$pdf->SetX(55);
			$pdf->Cell(45, 10, $dato['CI_RUC'], 0, 1);
			// Mensaje de cierre
			$pdf->SetFont('Arial', '', 10);
			$pdf->Cell(0, 10, mb_convert_encoding("Agradecemos su comprensión y atención a la presente.", 'ISO-8859-1', 'UTF-8'), 0, 1);
			$pdf->Cell(0, 10, 'Atentamente, ', 0, 1);
			$pdf->Cell(0, 10, $_SESSION['INGRESO']['Gerente'], 0, 1);
			// Obtener la posición actual del cursor en Y
			$y = $pdf->GetY();
			// Dibujar la línea justo debajo del último texto
			$pdf->Line(10, $y, 200, $y);
		}
		$pdf->Output('F', $path);
	}

	/**Dallyana Vanegas */
	function generarEncabezado($pdf)
	{
		setlocale(LC_TIME, 'es_ES');

		$xBloque2 = $pdf->GetX(); 
		$yBloque2 = $pdf->GetY()-2; 

		$pdf->SetFont('Arial','BI',10);
		$pdf->Cell(0,5, $_SESSION['INGRESO']['noempr'] ,0,1,'C');
		$pdf->SetFont('Arial','I',10);
		$pdf->Cell(0,5,'DISKCOVER SYSTEM',0,1,'C');
		$pdf->SetFont('Arial','I',8);
		$pdf->Cell(0,5,'Quito, Desarrollo Para Programacion De Diskcover System. Telefono: 0229201331.',0,1,'C');
		
		$pdf->SetXY(-50, $yBloque2);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(0,10,'Hora: ',0,0);
		$pdf->SetFont('Arial','',8);
		$pdf->SetX(-42);
		$pdf->Cell(0,10,date('H:i:s'),0,0);

		$pdf->Image(dirname(__DIR__,2).'/img/logotipos/DiskCover.png',190,8, 20);
		$pdf->Ln(3);
		
		$pdf->SetXY(-50, $pdf->GetY());
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(0,10,'Pagina No: ',0,0);
		$pdf->SetFont('Arial','',8); 
		$pdf->SetX(-35);
		$pdf->Cell(0,10,sprintf('%04d', $pdf->PageNo()),0,0); 
		$pdf->Ln(3); 

		$pdf->SetXY(-50, $pdf->GetY());
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(0,10,'Fecha: ',0,0);
		$pdf->SetFont('Arial','',8);
		$pdf->SetX(-40);
		$pdf->Cell(0,10,date('l, d/M/Y'),0,0); 
		$pdf->Ln(3); 

		$pdf->SetXY(-50, $pdf->GetY());
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(0,10,'Usuario: ',0,0);
		$pdf->SetFont('Arial','',8); 
		$pdf->SetX(-38); 
		$pdf->Cell(0,10, $_SESSION['INGRESO']['Nombre'] ,0,0); 
		$pdf->Ln(10);		
	}

	/**Dallyana Vanegas */
	function generarPDFTabla($parametros, $path)
	{
		$datosAdo = $parametros['AdoQuery'];

		$pdf = new FPDF('P', 'mm', 'Letter');
		$pdf->SetAutoPageBreak(true, 15);
	
		$pdf->SetFont('Arial', 'BIU', 9);
		$anchoPagina = $pdf->GetPageWidth();
		$anchoUtilizable = $anchoPagina - 28;
	
		if (!empty($datosAdo)) {
			$headers = array_keys((array)$datosAdo[0]);
			$numColumnas = count($headers);
	
			$anchosColumnas = array_fill(0, $numColumnas, 0);
	
			foreach ($datosAdo as $fila) {
				foreach ($headers as $indice => $header) {
					$valor = $fila[$header];
					if (is_array($valor)) {
						$valor = reset($valor);
					}
					$anchoActual = $pdf->GetStringWidth($valor) + 6;
					$anchosColumnas[$indice] = max($anchosColumnas[$indice], $anchoActual);
				}
			}
	
			for ($i = 0; $i < $numColumnas; $i++) {
				$anchoColumna = $anchosColumnas[$i];
				$anchoEncabezado = $pdf->GetStringWidth($headers[$i]) + 6;
				if ($anchoEncabezado > $anchoColumna) {
					$anchosColumnas[$i] = $anchoEncabezado;
				}
			}
	
			$anchoTotalColumnas = array_sum($anchosColumnas);
	
			if ($anchoTotalColumnas > $anchoUtilizable) {
				$pdf->AddPage('L');
			} else {
				$pdf->AddPage();
			}
	
			$this->generarEncabezado($pdf);
			$pdf->SetFont('Arial', 'I', 14);
			$pdf->Cell(0, 5, $parametros['MensajeEncabData'], 0, 1, 'L');
			$pdf->SetFont('Arial', 'I', 12);
			$pdf->Cell(0, 5, 'Grupo No: ' .$parametros['Opcion'], 0, 1, 'L');
			$pdf->Ln(3);
	
			foreach ($headers as $indice => $header) {
				$pdf->Cell($anchosColumnas[$indice], 6, $header, 0, 0, 'C');
			}
			$pdf->Ln();
			$pdf->SetFont('Arial', '', 9);
	
			foreach ($datosAdo as $fila) {
				$pdf->SetX(15);
	
				foreach ($headers as $header) {
					$valor = $fila[$header];
					if (is_array($valor)) {
						$valor = reset($valor);
					}
					$pdf->Cell($anchosColumnas[array_search($header, $headers)], 6, $valor, 0, 0, 'C');
				}
				$pdf->Ln();
				$pdf->Cell(array_sum($anchosColumnas), 0, '', 'B');
				$pdf->Ln();
			}
		} else {
			$pdf->Cell(0, 10, 'No hay datos disponibles para mostrar en la tabla.', 0, 1);
		}
		$pdf->Output('F', $path);
	}

	/**Dallyana Vanegas */
	function Imprimir_Abono_Anticipado($CA, $path){
		$pdf = new FPDF('P', 'mm', 'Letter');
		$pdf->AddPage();
		$this->generarEncabezado($pdf);
		$pdf->SetFont('Arial', '', 12);
				
		$pdf->Cell(0,5,'ABONO ANTICIPADO No.',0,1);
		$pdf->Cell(0,5,'Fecha: ' . $CA["Fecha"],0,1);
		$pdf->Cell(0,5,'Por $' . $CA["Efectivo"],0,1);
		$pdf->Cell(0,5,'Beneficiario: ' . $CA["Beneficiario"],0,1);
		$pdf->Cell(0,5,'La suma de: ' . $CA["Efectivo"],0,1);
		$pdf->Cell(0,5,'POR CONCEPTO DE:',0,1);
		$pdf->Cell(0,5,$CA["Concepto"],0,1);
		$pdf->Cell(0,5,'C O N F O R M E',0,1);
		$pdf->Cell(0,5,'C.I./R.U.C. ' . $CA["RUC_CI"],0,1);
		
		$pdf->Output('F', $path);
	}

	function HeaderCompDiario($pdf){
		
	}

	function ImprimirCompDiario(array $DataComp, array $DataTrans, array $DataFact, array $DataRets, array $DataSubC1, array $DataSubC2, bool $ImpSoloReten, bool $NuevaPagina = false, bool $NoImpret = false){
		$pdf = new FPDF('P', 'cm', 'A4');
		$nameArchivo = "";
		$fuente = 'Times';
		$path = dirname(__DIR__,2).'/TEMP/COMPROBANTEDEDIARIO'.FechaSistema().'.pdf';
		$pdf->AddPage();
		$pdf->SetFont($fuente, 'B', 12);

		//Comienza header
		$margenesIniciales = array('x' => $pdf->GetX(), 'y' => $pdf->GetY()); //Punto 0.0

		$header = function($pdf, $DataComp){
			$fuente = 'Times';
			$x = $pdf->GetX();
			$y = $pdf->GetY();
			$w = $pdf->GetPageWidth() - 2;
			$h = 3; 
			$columnWidth = $w / 3;
			$pdf->Rect($x, $y, $w, $h);//Rectangulo de la informacion inicial
			$logo = $this->logo();

			//Primera columna
			$pdf->SetXY($x, $y);
			$pdf->Image($logo, $x+($columnWidth/12), $y+($h/4), $columnWidth/2, $h/2);

			//Segunda columna
			$pdf->SetXY($x + $columnWidth, $y);
			$pdf->Cell($columnWidth, 0.5, $_SESSION['INGRESO']['noempr'], 0, 1, 'C');

			$pdf->SetXY($x + $columnWidth, $y+0.5);
			$pdf->Cell($columnWidth, 0.5, $_SESSION['INGRESO']['Nombre_Comercial'], 0, 1, 'C');

			$pdf->SetXY($x + $columnWidth, $y+1);
			$pdf->SetFont($fuente, 'B', 10);
			$pdf->Cell($columnWidth, 0.5, 'R.U.C. ' . $_SESSION['INGRESO']['RUC'], 0, 1, 'C');

			$pdf->SetXY($x + $columnWidth, $y+1.5);
			$pdf->SetFont($fuente, 'B', 7);
			$pdf->Cell($columnWidth, 0.5, 'Dir.: ' . $_SESSION['INGRESO']['Direccion'] . ' - Telef.: ' . $_SESSION['INGRESO']['Telefono1'] . '/FAX: ' . $_SESSION['INGRESO']['FAX'], 0, 1, 'C');

			$pdf->SetXY($x + $columnWidth, $y+2);
			$pdf->SetFont($fuente, 'B', 16);
			$pdf->Cell($columnWidth, 0.5, 'COMPROBANTE DE DIARIO', 0, 1, 'C');
			
			//Tercera columna
			//Primera Fila
			$pdf->SetXY($x + 2.37 * $columnWidth, $y+0.5);
			$pdf->SetFont($fuente, 'B', 16);
			$pdf->Cell(1.5, 0.5, 'No.', 0, 0, 'L');
			$pdf->SetFont($fuente, '', 10);
			$fecha = $DataComp[0]['Fecha'];
			$mes = $fecha->format('m');
			$num = sprintf('%08d', $DataComp[0]['Numero']);
			$pdf->Cell(0, 0.5, $mes . "-" . $num, 0, 0, 'L');

			//Segunda Fila
			$pdf->SetXY($x + 2.37 * $columnWidth, $y+1.2);
			$pdf->SetFont($fuente, 'B', 12);
			$pdf->Cell(1.5, 0.5, 'Fecha:', 0, 0, 'L');
			$pdf->SetFont($fuente, '', 10);
			$pdf->Cell(0, 0.5, $fecha->format('d/M/Y'), 0, 0, 'L');

			//Tercera Fila
			$pdf->SetXY($x + 2.37 * $columnWidth, $y+1.9);
			$pdf->SetFont($fuente, 'B', 12);
			$pdf->Cell(2.5, 0.5, 'Pagina No:', 0, 0, 'L');
			$pdf->SetFont($fuente, '', 10);
			$pdf->Cell(0, 0.5, $pdf->PageNo(), 0, 0, 'L');

		};
		$header($pdf, $DataComp);
		//Termina header

		//Concepto inicio
		$x = $margenesIniciales['x'];
        $y = $margenesIniciales['y'] + 3;//+3 del rectangulo anterior
		$w = $pdf->GetPageWidth() - 2;
		
		$h = 1.5;//ancho del rectangulo actual
		$pdf->SetXY($x,$y);

		$pdf->Rect($x, $y, $w, $h);//Rectangulo concepto
		$pdf->SetFont($fuente, 'B', 10);
		$pdf->Cell(2.3, $h, 'Concepto de:', 0, 0, 'L');
		$pdf->SetFont($fuente, '', 10);
		$pdf->Cell(0, $h, $DataComp[0]['Concepto'], 0, 0, 'L');
		//Concepto fin

		//Contenido inicio
		//Rectangulo contabilizacion
		$x = $margenesIniciales['x'];
		$y = $margenesIniciales['y'] + 3 + 1.5;//+3 + 1.5 de los rectangulos anteriores
		$h = 0.5; //ancho del rectangulo actual
		$pdf->SetXY($x, $y);
		$pdf->Rect($x, $y, $w, $h);

		$pdf->SetFont($fuente, 'B', 10);
		$pdf->Cell(0, $h, 'CONTABILIZACION', 0, 0, 'C');

		//Rectangulo de la tabla
		$x = $margenesIniciales['x'];
		$y = $margenesIniciales['y'] + 3 + 1.5 + 0.5;//+3 + 1.5 + 0.5 de los rectangulos anteriores
		$h = 5;
		$pdf->SetXY($x, $y);
		$pdf->Rect($x, $y, $w, $h);

		$columnaWidht2 = ($w/5);

		//Lines separadoras
		$wcol1 = $x + $columnaWidht2 - 1;
		$wcol2 = $x + 2 * $columnaWidht2 + 1;
		$wcol3 = $x + 3 * $columnaWidht2 + 1;
		$wcol4 = $x + 4 * $columnaWidht2 + 1;
		$pdf->Line($wcol1, $y, $wcol1, $y + $h);
		$pdf->Line($wcol2, $y, $wcol2, $y + $h);
		$pdf->Line($wcol3, $y, $wcol3, $y + $h);
		$pdf->Line($wcol4, $y, $wcol4, $y + $h);

		//Cabeceras
		$pdf->SetFont($fuente, 'B', 11);
		$pdf->Cell($wcol1 - 1, 0.5, 'CODIGO', 1, 0, 'C');//Cabecera Codigo
		$pdf->Cell($columnaWidht2 + 2, 0.5, 'CONCEPTO', 1, 0, 'C');//Cabecera CONCEPTO
		$pdf->Cell($columnaWidht2, 0.5, 'PARCIAL/ME', 1, 0, 'C');//Cabecera PARCIAL/ME
		$pdf->Cell($columnaWidht2, 0.5, 'DEBE', 1, 0, 'C');//Cabecera DEBE
		$pdf->Cell($columnaWidht2-1, 0.5, 'HABER', 1, 0, 'C');//Cabecera HABER
		$pdf->Ln();

		$pdf->SetFont($fuente, '', 9);
		$TotParcialME = 0;
		$TotDebe = 0;
		$TotHaber = 0;
		foreach($DataTrans as $value){
			$pdf->Cell($wcol1 - 1, 0.5, $value['Cta'], 0, 0, 'C');//Codigo
			$pdf->Cell($columnaWidht2 + 2, 0.5, $value['Cuenta'], 0, 0, 'C');//Concepto
			$pdf->Cell($columnaWidht2, 0.5, $value['Parcial_ME'], 0, 0, 'C');//Parcial
			$TotParcialME += floatval($value['Parcial_ME']);
			$pdf->Cell($columnaWidht2, 0.5, $value['Debe'], 0, 0, 'C');//Debe
			$TotDebe += floatval($value['Debe']);
			$pdf->Cell($columnaWidht2 - 1, 0.5, $value['Haber'], 0, 0, 'C');//Haber
			$TotHaber += floatval($value['Haber']);
			//Salto de linea
			$pdf->Ln();
		}
		//Contenido fin

		//Rectantulo totales inicio
		$x = $margenesIniciales['x'];
		$y = $margenesIniciales['y'] + 3 + 1.5 + 0.5 + 5;//+3 + 1.5 + 0.5 + 10 de los rectangulos anteriores
		$h = 1;
		$pdf->SetXY($x, $y);
		$pdf->Rect($x, $y, $w, $h);

		$columnWidth = $w / 3;
		$wcol1 = $x + $columnWidth + 6.22;
		$wcol2 = $x + 2 * $columnWidth + 3.6;
		$pdf->Line($wcol1, $y, $wcol1, $y + $h);
		$pdf->Line($wcol2, $y, $wcol2, $y + $h);

		$pdf->SetFont($fuente, 'B', 12);
		$pdf->Cell(12.5, 1, 'TOTALES', 0, 0, 'R');
		$pdf->Cell(2.5, 1, strval($TotDebe), 0, 0, 'R');
		$pdf->Cell(3.5, 1, strval($TotHaber), 0, 0, 'R');
		//Rectangulo totales fin

		//Footer
		$x = $margenesIniciales['x'];
		$y = $margenesIniciales['y'] + 3 + 1.5 + 0.5 + 5 + 1;//+3 + 1.5 + 0.5 + 6 + 0.5de los rectangulos anteriores
		$h = 2;
		$pdf->SetXY($x, $y);
		$pdf->Rect($x, $y, $w, $h);

		$columnWidth = $w / 5;
		$pdf->Line($x + $columnWidth, $y, $x + $columnWidth, $y + $h);
		$pdf->Line($x + 2 * $columnWidth, $y, $x + 2 * $columnWidth, $y + $h);
		$pdf->Line($x + 3 * $columnWidth, $y, $x + 3 * $columnWidth, $y + $h);
		$pdf->Line($x + 4 * $columnWidth, $y, $x + 4 * $columnWidth, $y + $h);

		$pdf->Line($x + $columnWidth, $y + $h/1.5, $x + 5 * $columnWidth, $y + $h/1.5);

		$pdf->SetX($x + $columnWidth);
		$pdf->SetFont($fuente, '', 10);
		$pdf->Cell(0, 1, $DataComp[0]['Nombre_Completo'], 0, 0, 'L');
		$pdf->SetX($x);
		$pdf->SetFont($fuente, 'B', 12);
		$pdf->Cell(3.9, 1, 'COTIZACION', 0, 0, 'C');
		$pdf->Cell(3.9, 3.3, 'Elabordo por:', 0, 0, 'C');
		$pdf->Cell(3.9, 3.3, 'Contador', 0, 0, 'C');
		$pdf->Cell(3.9, 3.3, 'Aprobado por', 0, 0, 'C');
		$pdf->Cell(3.9, 3.3, 'Conforme', 0, 0, 'C');

		$pdf->Output('F',strtoupper($path));

		return basename($path);
	}

	function ImprimirCompNota_D_C(array $DataComp, array $DataTrans, array $DataSubC1, array $DataSubC2, string $Tipo_D_C){
		$pdf = new FPDF('P', 'cm', 'A4');
		$nameArchivo = "";
		switch($Tipo_D_C){
			case 'ND':
				$nameArchivo = 'NOTADEDEBITO'.FechaSistema().'.pdf';
				break;
			case 'NC':
				$nameArchivo = 'NOTADECREDITO'.FechaSistema().'.pdf';
				break;
		}
		$fuente = 'Times';
		$path = dirname(__DIR__,2).'/TEMP/'.$nameArchivo;
		$pdf->AddPage();
		$pdf->SetFont($fuente, 'B', 12);

		//Comienza header
		$margenesIniciales = array('x' => $pdf->GetX(), 'y' => $pdf->GetY()); //Punto 0.0

		$header = function($pdf, $DataComp, $Tipo_D_C){
			$fuente = 'Times';
			$x = $pdf->GetX();
			$y = $pdf->GetY();
			$w = $pdf->GetPageWidth() - 2;
			$h = 3; 
			$columnWidth = $w / 3;
			$pdf->Rect($x, $y, $w, $h);//Rectangulo de la informacion inicial
			$logo = $this->logo();

			//Primera columna
			$pdf->SetXY($x, $y);
			$pdf->Image($logo, $x+($columnWidth/12), $y+($h/4), $columnWidth/2, $h/2);

			//Segunda columna
			$pdf->SetXY($x + $columnWidth, $y);
			$pdf->Cell($columnWidth, 0.5, $_SESSION['INGRESO']['noempr'], 0, 1, 'C');

			$pdf->SetXY($x + $columnWidth, $y+0.5);
			$pdf->Cell($columnWidth, 0.5, $_SESSION['INGRESO']['Nombre_Comercial'], 0, 1, 'C');

			$pdf->SetXY($x + $columnWidth, $y+1);
			$pdf->SetFont($fuente, 'B', 10);
			$pdf->Cell($columnWidth, 0.5, 'R.U.C. ' . $_SESSION['INGRESO']['RUC'], 0, 1, 'C');

			$pdf->SetXY($x + $columnWidth, $y+1.5);
			$pdf->SetFont($fuente, 'B', 7);
			$pdf->Cell($columnWidth, 0.5, 'Dir.: ' . $_SESSION['INGRESO']['Direccion'] . ' - Telef.: ' . $_SESSION['INGRESO']['Telefono1'] . '/FAX: ' . $_SESSION['INGRESO']['FAX'], 0, 1, 'C');

			$pdf->SetXY($x + $columnWidth, $y+2);
			$pdf->SetFont($fuente, 'B', 16);
			switch($Tipo_D_C){
				case 'ND':
					$pdf->Cell($columnWidth, 0.5, 'NOTA DE DEBITO', 0, 1, 'C');
					break;
				case 'NC':
					$pdf->Cell($columnWidth, 0.5, 'NOTA DE CREDITO', 0, 1, 'C');
					break;
			}
			//Tercera columna
			//Primera Fila
			$pdf->SetXY($x + 2.37 * $columnWidth, $y+0.5);
			$pdf->SetFont($fuente, 'B', 16);
			$pdf->Cell(1.5, 0.5, 'No.', 0, 0, 'L');
			$pdf->SetFont($fuente, '', 10);
			$fecha = $DataComp[0]['Fecha'];
			$mes = $fecha->format('m');
			$num = sprintf('%08d', $DataComp[0]['Numero']);
			$pdf->Cell(0, 0.5, $mes . "-" . $num, 0, 0, 'L');

			//Segunda Fila
			$pdf->SetXY($x + 2.37 * $columnWidth, $y+1.2);
			$pdf->SetFont($fuente, 'B', 12);
			$pdf->Cell(1.5, 0.5, 'Fecha:', 0, 0, 'L');
			$pdf->SetFont($fuente, '', 10);
			$pdf->Cell(0, 0.5, $fecha->format('d/M/Y'), 0, 0, 'L');

			//Tercera Fila
			$pdf->SetXY($x + 2.37 * $columnWidth, $y+1.9);
			$pdf->SetFont($fuente, 'B', 12);
			$pdf->Cell(2.5, 0.5, 'Pagina No:', 0, 0, 'L');
			$pdf->SetFont($fuente, '', 10);
			$pdf->Cell(0, 0.5, $pdf->PageNo(), 0, 0, 'L');

		};
		$header($pdf, $DataComp, $Tipo_D_C);
		//Termina header

		//Concepto inicio
		$x = $margenesIniciales['x'];
        $y = $margenesIniciales['y'] + 3;//+3 del rectangulo anterior
		$w = $pdf->GetPageWidth() - 2;
		
		$h = 1.5;//ancho del rectangulo actual
		$pdf->SetXY($x,$y);

		$pdf->Rect($x, $y, $w, $h);//Rectangulo concepto
		$pdf->SetFont($fuente, 'B', 10);
		$pdf->Cell(2.3, $h, 'Concepto de:', 0, 0, 'L');
		$pdf->SetFont($fuente, '', 10);
		$pdf->Cell(0, $h, $DataComp[0]['Concepto'], 0, 0, 'L');
		//Concepto fin

		//Contenido inicio
		//Rectangulo contabilizacion
		$x = $margenesIniciales['x'];
		$y = $margenesIniciales['y'] + 3 + 1.5;//+3 + 1.5 de los rectangulos anteriores
		$h = 0.5; //ancho del rectangulo actual
		$pdf->SetXY($x, $y);
		$pdf->Rect($x, $y, $w, $h);

		$pdf->SetFont($fuente, 'B', 10);
		$pdf->Cell(0, $h, 'CONTABILIZACION', 0, 0, 'C');

		//Rectangulo de la tabla
		$x = $margenesIniciales['x'];
		$y = $margenesIniciales['y'] + 3 + 1.5 + 0.5;//+3 + 1.5 + 0.5 de los rectangulos anteriores
		$h = 5;
		$pdf->SetXY($x, $y);
		$pdf->Rect($x, $y, $w, $h);

		$columnaWidht2 = ($w/5);

		//Lines separadoras
		$wcol1 = $x + $columnaWidht2 - 1;
		$wcol2 = $x + 2 * $columnaWidht2 + 1;
		$wcol3 = $x + 3 * $columnaWidht2 + 1;
		$wcol4 = $x + 4 * $columnaWidht2 + 1;
		$pdf->Line($wcol1, $y, $wcol1, $y + $h);
		$pdf->Line($wcol2, $y, $wcol2, $y + $h);
		$pdf->Line($wcol3, $y, $wcol3, $y + $h);
		$pdf->Line($wcol4, $y, $wcol4, $y + $h);

		//Cabeceras
		$pdf->SetFont($fuente, 'B', 11);
		$pdf->Cell($wcol1 - 1, 0.5, 'CODIGO', 1, 0, 'C');//Cabecera Codigo
		$pdf->Cell($columnaWidht2 + 2, 0.5, 'CONCEPTO', 1, 0, 'C');//Cabecera CONCEPTO
		$pdf->Cell($columnaWidht2, 0.5, 'PARCIAL/ME', 1, 0, 'C');//Cabecera PARCIAL/ME
		$pdf->Cell($columnaWidht2, 0.5, 'DEBE', 1, 0, 'C');//Cabecera DEBE
		$pdf->Cell($columnaWidht2-1, 0.5, 'HABER', 1, 0, 'C');//Cabecera HABER
		$pdf->Ln();

		$pdf->SetFont($fuente, '', 9);
		$TotParcialME = 0;
		$TotDebe = 0;
		$TotHaber = 0;
		foreach($DataTrans as $value){
			$pdf->Cell($wcol1 - 1, 0.5, $value['Cta'], 0, 0, 'C');//Codigo
			$pdf->Cell($columnaWidht2 + 2, 0.5, $value['Cuenta'], 0, 0, 'C');//Concepto
			$pdf->Cell($columnaWidht2, 0.5, $value['Parcial_ME'], 0, 0, 'C');//Parcial
			$TotParcialME += floatval($value['Parcial_ME']);
			$pdf->Cell($columnaWidht2, 0.5, $value['Debe'], 0, 0, 'C');//Debe
			$TotDebe += floatval($value['Debe']);
			$pdf->Cell($columnaWidht2 - 1, 0.5, $value['Haber'], 0, 0, 'C');//Haber
			$TotHaber += floatval($value['Haber']);
			//Salto de linea
			$pdf->Ln();
		}
		//Contenido fin

		//Rectantulo totales inicio
		$x = $margenesIniciales['x'];
		$y = $margenesIniciales['y'] + 3 + 1.5 + 0.5 + 5;//+3 + 1.5 + 0.5 + 10 de los rectangulos anteriores
		$h = 1;
		$pdf->SetXY($x, $y);
		$pdf->Rect($x, $y, $w, $h);

		$columnWidth = $w / 3;
		$wcol1 = $x + $columnWidth + 6.22;
		$wcol2 = $x + 2 * $columnWidth + 3.6;
		$pdf->Line($wcol1, $y, $wcol1, $y + $h);
		$pdf->Line($wcol2, $y, $wcol2, $y + $h);

		$pdf->SetFont($fuente, 'B', 12);
		$pdf->Cell(12.5, 1, 'TOTALES', 0, 0, 'R');
		$pdf->Cell(2.5, 1, strval($TotDebe), 0, 0, 'R');
		$pdf->Cell(3.5, 1, strval($TotHaber), 0, 0, 'R');
		//Rectangulo totales fin

		//Footer
		$x = $margenesIniciales['x'];
		$y = $margenesIniciales['y'] + 3 + 1.5 + 0.5 + 5 + 1;//+3 + 1.5 + 0.5 + 6 + 0.5de los rectangulos anteriores
		$h = 2;
		$pdf->SetXY($x, $y);
		$pdf->Rect($x, $y, $w, $h);

		$columnWidth = $w / 5;
		$pdf->Line($x + $columnWidth, $y, $x + $columnWidth, $y + $h);
		$pdf->Line($x + 2 * $columnWidth, $y, $x + 2 * $columnWidth, $y + $h);
		$pdf->Line($x + 3 * $columnWidth, $y, $x + 3 * $columnWidth, $y + $h);
		$pdf->Line($x + 4 * $columnWidth, $y, $x + 4 * $columnWidth, $y + $h);

		$pdf->Line($x + $columnWidth, $y + $h/1.5, $x + 5 * $columnWidth, $y + $h/1.5);

		$pdf->SetX($x + $columnWidth);
		$pdf->SetFont($fuente, '', 10);
		$pdf->Cell(0, 1, $DataComp[0]['Nombre_Completo'], 0, 0, 'L');
		$pdf->SetX($x);
		$pdf->SetFont($fuente, 'B', 12);
		$pdf->Cell(3.9, 1, 'COTIZACION', 0, 0, 'C');
		$pdf->Cell(3.9, 3.3, 'Elabordo por:', 0, 0, 'C');
		$pdf->Cell(3.9, 3.3, 'Contador', 0, 0, 'C');
		$pdf->Cell(3.9, 3.3, 'Aprobado por', 0, 0, 'C');
		$pdf->Cell(3.9, 3.3, 'Conforme', 0, 0, 'C');

		$pdf->Output('F',strtoupper($path));

		return basename($path);
	}

	function Imprimir_Nota_Inventario(array $DatosNotaInventario){
		$pdf = new FPDF('P', 'cm', 'A4');
		$nameArchivo = "";
		$fuente = 'Times';
		$path = dirname(__DIR__,2).'/TEMP/NOTAINVENTARIO'.FechaSistema().'.pdf';
		$pdf->AddPage();
		$pdf->SetFont($fuente, 'B', 12);

		$DtaProv = $DatosNotaInventario['DtaProv'];
		$Datas = $DatosNotaInventario['Datas'];
		$AdoDBKardex = $DatosNotaInventario['AdoDBKardex'];
		$NombreCliente = $DatosNotaInventario['NombreCliente'];
		$Detalles_Ctas = $DatosNotaInventario['Detalles_Ctas'];
		$Numero = $DatosNotaInventario['Numero'];
		$Total_Inv = $DatosNotaInventario['Total_Inv'];

		$margenesIniciales = array('x' => $pdf->GetX(), 'y' => $pdf->GetY()); //Punto 0.0
	
		$header = function($pdf, $DatosNotaInventario, $margenesIniciales){
			$fuente = 'Times';
			$x = $pdf->GetX();
			$y = $pdf->GetY();
			$w = $pdf->GetPageWidth() - 2;
			$h = 3; 
			$columnWidth = $w / 3;
			$pdf->Rect($x, $y, $w, $h - 0.5);//Rectangulo de la informacion inicial
			$logo = $this->logo();

			//Primera columna
			$pdf->SetXY($x, $y);
			$pdf->Image($logo, $x+($columnWidth/12), $y+($h/4), $columnWidth/2, $h/2);

			//Segunda columna
			$pdf->SetXY($x + $columnWidth, $y);
			$pdf->Cell($columnWidth, 0.5, $_SESSION['INGRESO']['noempr'], 0, 1, 'C');

			$pdf->SetXY($x + $columnWidth, $y+0.5);
			$pdf->Cell($columnWidth, 0.5, $_SESSION['INGRESO']['Nombre_Comercial'], 0, 1, 'C');

			$pdf->SetXY($x + $columnWidth, $y+1);
			$pdf->SetFont($fuente, 'B', 7);
			$pdf->Cell($columnWidth, 0.5, 'Quito, ' . $_SESSION['INGRESO']['Direccion'] . ' - Telef.: ' . $_SESSION['INGRESO']['Telefono1'] . '/FAX: ' . $_SESSION['INGRESO']['FAX'], 0, 1, 'C');

			$pdf->SetXY($x + $columnWidth, $y+1.5);
			$pdf->SetFont($fuente, 'B', 13);
			$pdf->Cell($columnWidth, 0.5, 'NOTA DE ENTRADA/SALIDA DE INVENTARIO', 0, 1, 'C');
			
			//Tercera columna
			//Primera Fila
			$pdf->SetXY($x + 2.37 * $columnWidth, $y+0.5);
			$pdf->SetFont($fuente, 'B', 8);
			$pdf->Cell(1.5, 0.5, 'Hora:', 0, 0, 'L');
			$pdf->SetFont($fuente, '', 8);
			$pdf->Cell(0, 0.5, date('H:i:s'), 0, 0, 'L');

			//Segunda Fila
			$pdf->SetXY($x + 2.37 * $columnWidth, $y+1);
			$pdf->SetFont($fuente, 'B', 8);
			$pdf->Cell(2.5, 0.5, 'Pagina No.', 0, 0, 'L');
			$pdf->SetFont($fuente, '', 8);
			$pdf->Cell(0, 0.5, $pdf->PageNo(), 0, 0, 'L');

			//Tercera Fila
			$pdf->SetXY($x + 2.37 * $columnWidth, $y+1.5);
			$pdf->SetFont($fuente, 'B', 8);
			$pdf->Cell(1.5, 0.5, 'Fecha:', 0, 0, 'L');
			$pdf->SetFont($fuente, '', 8);
			$pdf->Cell(0, 0.5, date('d/m/Y'), 0, 0, 'L');

			//Cuarta Fila
			$pdf->SetXY($x + 2.37 * $columnWidth, $y+2);
			$pdf->SetFont($fuente, 'B', 8);
			$pdf->Cell(1.5, 0.5, 'Usuario:', 0, 0, 'L');
			$pdf->SetFont($fuente, '', 8);
			$pdf->Cell(0, 0.5, $_SESSION['INGRESO']['Nombre'], 0, 0, 'L');

			$x = $margenesIniciales['x'];
        	$y = $margenesIniciales['y'] + $h;//+3 del rectangulo anterior
			$w = $pdf->GetPageWidth() - 2;
		
			$h += 1.5;//ancho del rectangulo actual
			$pdf->SetXY($x,$y);
			$pdf->SetFont($fuente, 'B', 10);
			$pdf->Cell(2, 0.5, 'FECHA:', 0, 0, 'L');
			$pdf->SetFont($fuente, '', 10);
			$pdf->Cell(0, 0.5, date('d/m/Y'), 0, 0, 'L');
			$pdf->SetXY($x,$y);
			$pdf->SetFont($fuente, 'B', 10);
			if(count($DatosNotaInventario['DtaProv']) == 0){
				$pdf->Cell(0, 0.5, 'TP: CD, No. ', 0, 0, 'R');
				$pdf->Ln();
				$pdf->Cell(0, 0.5, 'BENEFICIARIO: ', 0, 0, 'L');
			}else{
				$pdf->Cell(0, 0.5, 'TP:CD, No. ' . sprintf('%08d',$DatosNotaInventario['Numero']), 0, 0, 'R');
				$pdf->Ln();
				$pdf->Cell(3, 0.5, 'BENEFICIARIO: ', 0, 0, 'L');
				$pdf->SetFont($fuente, '', 10);
				$pdf->Cell(3, 0.5, $DatosNotaInventario['NombreCliente'], 0, 0, 'L');
			}
			//draw line
			$pdf->Line($x, $y + 1, $w + 1, $y + 1);
			$x = $margenesIniciales['x'];
        	$y = $margenesIniciales['y'] + 4;
			$w = $pdf->GetPageWidth() - 2;
			$pdf->SetXY($x, $y);

			if(count($DatosNotaInventario['DtaProv']) == 0){
				$pdf->SetFont($fuente, 'B', 8);
				$pdf->Cell(4, 0.5, 'CUENTAS INVOLUCRADAS: ', 0, 0, 'L');
			}else{
				$pdf->SetFont($fuente, 'B', 8);
				$pdf->Cell(4, 0.5, 'CUENTAS INVOLUCRADAS: ', 0, 0, 'L');//FIXME
				$pdf->SetXY($x + 4, $pdf->GetY());//FIXME
				$Ctas = $DatosNotaInventario['Detalles_Ctas'];
				foreach($Ctas as $value){
					$pdf->Cell(4, 0.5, $value, 0, 1, 'L');
				}
			}


		};

		$header($pdf, $DatosNotaInventario, $margenesIniciales);

		//Dibujamos tabla
		$x = $margenesIniciales['x'];
		$y = $margenesIniciales['y'] + 5;
		$w = $pdf->GetPageWidth() - 2;
		
		//Draw a line
		$pdf->SetXY($x, $y);
		$pdf->Line($x, $y, $w + 1, $y);
		//Put headers
		$pdf->SetFont($fuente, 'B', 8);
		$pdf->Cell(3, 0.5, 'BOD', 0, 0, 'L');
		$pdf->Cell(3, 0.5, 'CODIGO/SERIE', 0, 0, 'L');
		$pdf->Cell(2, 0.5, 'CODIGO', 0, 0, 'L');
		$pdf->Cell(7, 0.5, 'PRODUCTO', 0, 0, 'L');
		$pdf->Cell(2, 0.5, 'ENTRADA', 0, 0, 'L');
		$pdf->Cell(2, 0.5, 'SALIDA', 0, 0, 'L');
		//draw an other line
		$pdf->Ln();
		$pdf->SetXY($x, $pdf->GetY());
		$pdf->Line($x, $pdf->GetY(), $w + 1, $pdf->GetY());

		//print_r($DatosNotaInventario);

		//Draw content
		$pdf->SetFont($fuente, '', 8);

		for($i = 0; $i < count($Datas); $i++){
			$pdf->Cell(3, 0.5, $Datas[$i]['CodBodega'], 0, 0, 'L');
			$pdf->Cell(3, 0.5, " ", 0, 0, 'L');
			$pdf->Cell(2, 0.5, $Datas[$i]['Codigo_Inv'], 0, 0, 'L');
			$pdf->SetFont($fuente, '', 7.5);
			$pdf->Cell(7, 0.5, $Datas[$i]['Producto'], 0, 0, 'L');
			$pdf->SetFont($fuente, '', 8);
			$pdf->Cell(2, 0.5, $Datas[$i]['Entrada'], 0, 0, 'L');
			$pdf->Cell(2, 0.5, $Datas[$i]['Salida'], 0, 0, 'L');
			$pdf->Ln();
		}

		$pdf->SetXY($x, $pdf->GetY());
		$pdf->Line($x, $pdf->GetY(), $w + 1, $pdf->GetY());

		$pdf->Cell(8, 0.5, 'TOTAL INVENTARIO', 0, 0, 'L');
		$pdf->Cell(3, 0.5, strval($Total_Inv), 0, 0, 'L');
		

		$pdf->SetFont($fuente, '', 10);
		$pdf->SetXY($x + 3, $pdf->GetY() + 2);
		$pdf->Cell(4, 0.5, 'AUTORIZADO', 'T', 0, 'C');
		$pdf->SetXY($pdf->GetX() + 1, $pdf->GetY());
		$pdf->Cell(4, 0.5, 'ENTREGUE CONFORME', 'T', 0, 'C');
		$pdf->SetXY($pdf->GetX() + 1, $pdf->GetY());
		$pdf->Cell(4, 0.5, 'RECIBI CONFORME', 'T', 0, 'C');

		if(count($DtaProv) == 0){
			$pdf->SetXY($margenesIniciales['x'], $margenesIniciales['y'] + 5);
			$pdf->SetFont($fuente, 'B', 16);
			//$pdf->Cell(0, 3, 'NO SE ENCONTRARON DATOS', 0, 1, 'C');
			$pdf->Output('F',strtoupper($path));
			return basename($path);
		}

		


		$pdf->Output('F',strtoupper($path));

		return basename($path);
	}

	function logo($logoName = false){
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
	}
	
}

class PDFv extends FPDF
{

	public $fechaini;
	public $fechafin;
	public $titulo;
	public $salto_header_cuerpo;
	public $orientacion;

    function Header()
    {
   
  // print($_SESSION['INGRESO']['Logo_Tipo']);
		if(isset($_SESSION['INGRESO']['Logo_Tipo']))
		   {
		   	$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		   	//si es jpg
		   	$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.jpg'; 
		   	if(!file_exists($src))
		   	{
		   		$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.gif'; 
		   		if(!file_exists($src))
		   		{
		   			$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.png'; 
		   			if(!file_exists($src))
		   			{
		   				$logo="diskcover";
		                $src= dirname(__DIR__,2).'/img/logotipos/'.$logo.'.gif';

		   			}

		   		}

		   	}
		  }

         $this->Image($src,10,3,35,20); 
         $this->SetFont('Times','b',12);
         $this->SetXY(10,10);

		$this->Cell(0,3,$_SESSION['INGRESO']['Nombre_Comercial'],0,0,'C');
		$this->SetFont('Times','I',13);
		$this->Ln(5);
		$this->Cell(0,3,strtoupper($_SESSION['INGRESO']['noempr']),0,0,'C');				
		$this->Ln(5);


		$this->SetFont('Times','I',11);
		$this->Cell(0,3,ucfirst(strtolower($_SESSION['INGRESO']['Direccion'].' Telefono: '.$_SESSION['INGRESO']['Telefono1'])),0,0,'C');

		$this->Ln(5);		
		$this->SetFont('Arial','b',12);

		$this->Cell(0,3,$this->titulo,0,0,'C');
		
		if($this->fechaini !='' && $this->fechaini != null  && $this->fechafin !='' && $this->fechafin != null){
		   $this->SetFont('Arial','b',10);
		   $this->Ln(5);		
		   $this->Cell(0,3,'DESDE: '.$this->fechaini.' HASTA:'.$this->fechafin,0,0,'C');
		   $this->Ln(10);	
		}

		if($this->orientacion == 'P')
		{
		  //inicio--------logo superior derecho//		
        $this->Image(dirname(__DIR__,2).'/img/logotipos/diskcov2.gif',182,3,20,8); 
		$this->Ln(2);		

		 $this->SetFont('Arial','b',8);
        // $this->pdf->SetXY(10,10);
		$this->SetXY(155,5);
        $this->Cell(9,2,'Hora: ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,date('h:i:s A'),0,0,'L');
		$this->Ln(2);		
		$this->SetFont('Arial','b',8);
		$this->SetXY(155,8);
        $this->Cell(17,2,'Pagina No.  ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,$this->PageNo(),0,0,'L');
		$this->Ln(2);
		$this->SetXY(155,11);
		$this->SetFont('Arial','b',8);		
        $this->Cell(10,2,'Fecha: ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,date("Y-m-d") ,0,0,'L');
		$this->Ln(2);
		$this->SetXY(155,14);
		$this->SetFont('Arial','b',8);	
        $this->Cell(12,2,'Usuario: ',0,0,'L');
		$this->SetFont('Arial','',8);	
        $this->Cell(0,2,$_SESSION['INGRESO']['Nombre_Completo'],0,0,'L');
		$this->Line(20, 35, 210-20, 35); 
        $this->Line(20, 36, 210-20, 36);
		$this->Ln($this->salto_header_cuerpo);
	}else
	{

		  //inicio--------logo superior derecho//		
        $this->Image(dirname(__DIR__,2).'/img/logotipos/diskcov2.gif',482,3,20,8); 
		$this->Ln(2);		

		 $this->SetFont('Arial','b',8);
        // $this->pdf->SetXY(10,10);
		$this->SetXY(255,5);
        $this->Cell(9,2,'Horas: ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,date('h:i:s A'),0,0,'L');
		$this->Ln(2);		
		$this->SetFont('Arial','b',8);
		$this->SetXY(255,8);
        $this->Cell(17,2,'Pagina No.  ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,$this->PageNo(),0,0,'L');
		$this->Ln(2);
		$this->SetXY(255,11);
		$this->SetFont('Arial','b',8);		
        $this->Cell(10,2,'Fecha: ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,date("Y-m-d") ,0,0,'L');
		$this->Ln(2);
		$this->SetXY(255,14);
		$this->SetFont('Arial','b',8);	
        $this->Cell(12,2,'Usuario: ',0,0,'L');
		$this->SetFont('Arial','',8);	
        $this->Cell(0,2,$_SESSION['INGRESO']['Nombre_Completo'],0,0,'L');
		$this->Line(20, 35, 300-20, 35); 
        $this->Line(20, 36, 300-20, 36);
		$this->Ln($this->salto_header_cuerpo);

	}
 }
}

class PDFdetalle extends FPDF {
	private $titulo; 
	private $datos; 

	function __construct($titulo, $datos) {
		parent::__construct();
		$this->titulo = $titulo;
		$this->datos = $datos;
	}

	function Headerd() {			
		$this -> Image(dirname(__DIR__,2).'/img/logotipos/DiskCove.jpg', 10, 8, 33);
		$this -> SetFont('Arial', 'B', 15);
		$this -> Cell(80);
		$this -> Cell(0, 3, $this->titulo, 0, 0, 'C');
		$this -> Ln(20);
	}

	function Footer() {
		$this -> SetY(-15);
		$this -> SetFont('Arial', 'I', 8);
		$this -> Cell(0, 10, $this -> PageNo(), 0, 0, 'C');
	}	
	
	public function generarPDF() {
		$this->Headerd();
	
		if (empty($this->datos)) {
			$this->SetFont('Arial', 'B', 12);
			$this->Cell(0, 10, 'No hay datos disponibles para mostrar en el PDF.', 0, 1);
		} else {
			foreach ($this->datos as $row) {
				$this->Cell(0, 10, 'Fecha: ' . $row['Fecha'], 0, 1);
				$this->Cell(0, 10, 'Producto: ' . $row['Producto'], 0, 1);
				$this->Cell(0, 10, 'Cantidad: ' . $row['Cantidad'], 0, 1);
				$this->Cell(0, 10, 'Precio: ' . $row['Precio'], 0, 1);
				$this->Cell(0, 10, 'A: ' . $row['A'], 0, 1);
				$this->Cell(0, 10, 'S: ' . $row['S'], 0, 1);
				$this->Ln(10);
			}
		}
	
		$this->Footer(); 
		$this->Output('I', 'orden_de_trabajo.pdf');
	}
}

class PDF_MC extends PDF_MC_Table
{

	public $fechaini;
	public $fechafin;
	public $titulo;
	public $salto_header_cuerpo;
	public $orientacion;

    function Header()
    {
   
   // print($_SESSION['INGRESO']['Logo_Tipo']);
    	
			      $this->SetTextColor(0,0,0);
		if(isset($_SESSION['INGRESO']['Logo_Tipo']))
		   {
		   	$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		   	//si es jpg
		   	$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.jpg'; 
		   	if(!file_exists($src))
		   	{
		   		$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.gif'; 
		   		if(!file_exists($src))
		   		{
		   			$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.png'; 
		   			if(!file_exists($src))
		   			{
		   				$logo="diskcover_web";
		                $src= dirname(__DIR__,2).'/img/logotipos/'.$logo.'.gif';

		   			}

		   		}

		   	}
		  }

         $this->Image($src,10,18,35,20); 
         $this->SetFont('Times','b',9);
         $this->SetXY(10,20);

         // print_r($_SESSION['INGRESO']);die();

         if($_SESSION['INGRESO']['Razon_Social']!=$_SESSION['INGRESO']['Nombre_Comercial'])
         {
			$this->Cell(0,3,$_SESSION['INGRESO']['Razon_Social'],0,0,'C');
			$this->SetFont('Times','I',9);
			$this->Ln(5);
			$this->Cell(0,3,strtoupper($_SESSION['INGRESO']['Nombre_Comercial']),0,0,'C');				
			$this->Ln(5);
		}else
		{
			$this->Cell(0,3,$_SESSION['INGRESO']['Razon_Social'],0,0,'C');
			$this->SetFont('Times','I',9);
			$this->Ln(5);
		}


		$this->SetFont('Times','I',8);
		$this->Cell(0,3,ucfirst(strtolower($_SESSION['INGRESO']['Direccion'].' Telefono: '.$_SESSION['INGRESO']['Telefono1'])),0,0,'C');

		$this->Ln(5);		
		$this->SetFont('Arial','b',8);

		$this->Cell(0,3,$this->titulo,0,0,'C');
		
		if($this->fechaini !='' && $this->fechaini != null  && $this->fechafin !='' && $this->fechafin != null){
		   $this->SetFont('Arial','b',10);
		   $this->Ln(5);		
		   $this->Cell(0,6,'DESDE: '.$this->fechaini.' HASTA:'.$this->fechafin,0,0,'C');
		   $this->Ln(10);	
		}

		if($this->orientacion == 'P')
		{
		  //inicio--------logo superior derecho//		
        $this->Image(dirname(__DIR__,2).'/img/logotipos/diskcov2.gif',175,18,15,5); 
		$this->Ln(2);

		$this->SetFont('Arial','b',5);
		$this->SetXY(190,19);
        $this->Cell(10,2,'Pagina No.  ',0,0,'L');
		$this->SetFont('Arial','',5);
        $this->Cell(0,2,$this->PageNo(),0,0,'L');
		$this->Ln(2);
		 $this->SetFont('Arial','b',5);
        // $this->pdf->SetXY(10,10);
		$this->SetXY(175,23);
        $this->Cell(9,2,'Hora: ',0,0,'L');
		$this->SetFont('Arial','',5);
        $this->Cell(0,2,date('h:i:s A'),0,0,'L');
		$this->Ln(2);				
		$this->SetXY(175,26);
		$this->SetFont('Arial','b',5);		
        $this->Cell(8,2,'Fecha: ',0,0,'L');
		$this->SetFont('Arial','',5);
        $this->Cell(0,2,date("Y-m-d") ,0,0,'L');
		$this->Ln(2);
		$this->SetXY(175,29);
		$this->SetFont('Arial','b',5);	
        $this->Cell(8,2,'Usuario: ',0,0,'L');
		$this->SetFont('Arial','',5);	
        $this->Cell(0,2,$_SESSION['INGRESO']['Nombre_Completo'],0,0,'L');
        $this->Ln(2);
		$this->SetXY(175,32);
		$this->SetFont('Arial','b',5);	
        $this->Cell(10,2,'https://www.diskcoversystem.com',0,0,'L');
		$this->Line(20, 45, 210-20, 45); 
        $this->Line(20, 46, 210-20, 46);
		$this->Ln($this->salto_header_cuerpo);
	}else
	{

		  //inicio--------logo superior derecho//		
		// 175,18,15,5); 
        $this->Image(dirname(__DIR__,2).'/img/logotipos/diskcov2.gif',270,18,20,8); 
		$this->Ln(2);		

		 $this->SetFont('Arial','b',8);
        // $this->pdf->SetXY(10,10);
		$this->SetXY(240,19);
        $this->Cell(9,2,'Hora: ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,date('h:i:s A'),0,0,'L');
		$this->Ln(2);		
		$this->SetFont('Arial','b',8);
		$this->SetXY(240,23);
        $this->Cell(17,2,'Pagina No.  ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,$this->PageNo(),0,0,'L');
		$this->Ln(2);
		$this->SetXY(240,26);
		$this->SetFont('Arial','b',8);		
        $this->Cell(10,2,'Fecha: ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,date("Y-m-d") ,0,0,'L');
		$this->Ln(2);
		$this->SetXY(240,29);
		$this->SetFont('Arial','b',8);	
        $this->Cell(12,2,'Usuario: ',0,0,'L');
		$this->SetFont('Arial','',8);	
        $this->Cell(0,2,$_SESSION['INGRESO']['Nombre_Completo'],0,0,'L');
        $this->Ln(2);
		$this->SetXY(240,32);
		$this->SetFont('Arial','b',5);	
        $this->Cell(10,2,'https://www.diskcoversystem.com',0,0,'L');
		$this->Line(20, 40, 300-20, 40); 
        $this->Line(20, 41, 300-20, 41);
		$this->Ln($this->salto_header_cuerpo);

	}

 }
}

class PDF_MC_SIN_HEADER extends PDF_MC_Table
{

	public $fechaini;
	public $fechafin;
	public $titulo;
	public $salto_header_cuerpo;
	public $orientacion;


    function Header()
    {
   
   // print($_SESSION['INGRESO']['Logo_Tipo']);
    	
			      $this->SetTextColor(0,0,0);
		if(isset($_SESSION['INGRESO']['Logo_Tipo']))
		   {
		   	$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		   	//si es jpg
		   	$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.jpg'; 
		   	if(!file_exists($src))
		   	{
		   		$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.gif'; 
		   		if(!file_exists($src))
		   		{
		   			$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.png'; 
		   			if(!file_exists($src))
		   			{
		   				$logo="diskcover_web";
		                $src= dirname(__DIR__,2).'/img/logotipos/'.$logo.'.gif';

		   			}

		   		}

		   	}
		  }

		  // print_r($_SESSION['INGRESO']);die();

         $this->Image($src,10,3,35,20); 
         $this->SetFont('Times','I',9);
         $this->SetXY(50,3);
		 $this->Cell(0,3,$_SESSION['INGRESO']['Nombre_Completo'],0,0,'L');
		 $this->Ln(5);		 
         $this->SetX(50);
		 $this->Cell(0,3,strtoupper($_SESSION['INGRESO']['Nombre_Comercial']),0,0,'L');				
		 $this->Ln(5);
		 $this->SetX(50);
		 $this->Cell(0,3,strtoupper('R.U.C: '.$_SESSION['INGRESO']['RUC']),0,0,'L');				
		 $this->Ln(3);
		 $this->SetX(50);
		 $this->SetFont('Times','I',7);
		 $this->Cell(0,3,ucfirst(strtolower($_SESSION['INGRESO']['Direccion'])),0,0,'L');
		 $this->Ln(3);
		 $this->SetX(50);
    	 $this->SetFont('Times','I',7);
		 $this->Cell(0,3,ucfirst(strtolower('Telefono: '.$_SESSION['INGRESO']['Telefono1'])),0,0,'L');		 
		 $this->Ln(3);
		 $this->SetX(50);
    	 $this->SetFont('Times','I',7);
		 $this->Cell(0,3,ucfirst(strtolower('QUITO-ECUADOR')),0,0,'L');
		 $this->Ln(8);
 }
}



?>