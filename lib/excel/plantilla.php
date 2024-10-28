<?php
// @session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;


use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Color;

// use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
// use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Style;
// use PhpOffice\PhpSpreadsheet\Style\Font;
// use PhpOffice\PhpSpreadsheet\Style\Protection;

function download_file($archivo, $downloadfilename = null) 
{

    if (file_exists($archivo)) {
        $downloadfilename = $downloadfilename !== null ? $downloadfilename : basename($archivo);
		
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $downloadfilename);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($archivo));
		
        ob_clean();
        flush();
        readfile($archivo);
		
        exit;
    }

}


function logo_ruta()
{
	$src = dirname(__DIR__,2).'/img/logotipos/DEFAULT.jpg';
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
	  return $src;
}

function excel_generico($titulo,$datos=false,$url=false)
{
	//diskcover_002/TEMP/EMPRESA_;
	if(!$url){
	$url = 	dirname(__DIR__,2).'/TEMP/EMPRESA_'.$_SESSION['INGRESO']['item'].'/';  
	}  
	
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

	$estilo_cabecera = array('font' => ['bold' => true,],
							 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,],
							'borders' => [	'top' => ['borderStyle' => Border::BORDER_THIN,	],],
							'fill' => ['fillType' =>  Fill::FILL_GRADIENT_LINEAR,'rotation' => 90,'startColor' => ['rgb' => '436BEE', ], 'endColor' => ['rgb' => 'FFFFFF', ], ],);
	$linea1 = array('font' => ['bold' => true,],
								'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,],
								'borders' => ['top' => ['borderStyle' => Border::BORDER_THIN,],],
								'fill' => ['fillType' =>  Fill::FILL_SOLID,'rotation' => 90,'startColor' => ['rgb' => '436BEE', ], 'endColor' => ['rgb' => 'FFFFFF', ], ],);
	$estilo_subcabecera = array('font' => ['bold' => true,],
								'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT,],
								'borders' => ['top' => ['borderStyle' => Border::BORDER_THIN,],],
								'fill' => ['fillType' =>  Fill::FILL_GRADIENT_LINEAR,'rotation' => 90,'startColor' => ['rgb' => '436BEE', ], 'endColor' => ['rgb' => 'FFFFFF', ], ],);
	$estilo_subcabecera_r = array('font' => ['bold' => true,],
								'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT,],
								'borders' => ['top' => ['borderStyle' => Border::BORDER_THIN,],],
								'fill' => ['fillType' =>  Fill::FILL_GRADIENT_LINEAR,'rotation' => 90,'startColor' => ['rgb' => '436BEE', ], 'endColor' => ['rgb' => 'FFFFFF', ], ],);
	 $centrar = array( 'alignment' => array('horizontal' => Alignment::HORIZONTAL_CENTER,) );
	 $derecha = array( 'alignment' => array('horizontal' => Alignment::HORIZONTAL_RIGHT,) );
	 $izquierda = array( 'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT,) );
	 $negrita = array('font' => ['bold' => true,],'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT,]);
	 $negritaC = array('font' => ['bold' => true,],'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]);
	 $negritaR = array('font' => ['bold' => true,],'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT,]);
	 $italica = array('font' => ['italic' => true,],'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT,]);

	//---------------------inserta imagen 1------------------
	$objDrawing = new Drawing();
	$objDrawing->setName('test_img');
	$objDrawing->setDescription('test_img');
	$objDrawing->setPath(logo_ruta());
	$objDrawing->setCoordinates('A1');                  
	//setOffsetX works properly
	$objDrawing->setOffsetX(5); 
	$objDrawing->setOffsetY(5);                
	//set width, height
	$objDrawing->setWidth(100); 
	$objDrawing->setHeight(35); 
	$objDrawing->setWorksheet($spreadsheet->getActiveSheet());
	$sheet->getRowDimension('1')->setRowHeight(45);
    $sheet->getColumnDimension('A')->setWidth(100);  
   //--------------------fin inserta imagen 1----------------

    //------------------imagen 2----------------------
    $drawing = new Drawing();
	$drawing->setName('Logo1');
	$drawing->setDescription('Logo1');
	$drawing->setPath(__DIR__ . '/logosMod.gif');
	$drawing->setHeight(32);
	$drawing->setOffsetX(90);
	$drawing->setOffsetY(5);
	$drawing->setWorksheet($spreadsheet->getActiveSheet());
	//-------------------fin imagen 2--------------------

        $richText1 = new RichText();
		
		$redf=$richText1->createTextRun("Hora: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(7);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("H:i")."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(7);
		
		$redf=$richText1->createTextRun("Fecha: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(7);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("d-m-Y") ."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(7);
		
		$redf=$richText1->createTextRun("Usuario: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(7);
		$redf->getFont()->setBold(true);
		
		$red = $richText1->createTextRun($_SESSION['INGRESO']['Nombre']);
		$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$red->getFont()->setSize(7);

		//---------------------nombre de la empresa central---------------
	    $sheet->getStyle('B1')->getAlignment()->setWrapText(true);
	    $sheet->getColumnDimension('B')->setWidth(40);	
		$sheet->getStyle('B1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
	    if($_SESSION['INGRESO']['Razon_Social']!=$_SESSION['INGRESO']['noempr'])
		{
			$spreadsheet->getActiveSheet()->setCellValue('B1', $_SESSION['INGRESO']['Razon_Social'].'');
		}
		else
		{
			$spreadsheet->getActiveSheet()->setCellValue('B1', $_SESSION['INGRESO']['noempr'].'');
		}

		//-------------------------fin de nombre central de empresa----------------

	    $num=2;
		$let='A';
		if($titulo!='')
		{
			if(count($datos)>0)
			{
			    if(!isset($datos[0]['col-total']))
			    {
					$col = count($datos[0]['datos']);
				}else{
					$col = $datos[0]['col-total'];
				}
				// print_r($col);
				$le = $let;
				$ti =$let;
				for ($i=1; $i <$col ; $i++) { $le++;}
				for ($i=1; $i <$col-1 ; $i++) { $ti++;}
				
				// print_r($le);die();
				$sheet->mergeCells($let.''.$num.':'.$le.''.$num);
				$sheet->setCellValue($let.''.$num, $titulo);
				$sheet->getStyle($let.''.$num)->applyFromArray($estilo_cabecera);			
				$sheet->getStyle($let.'1:'.$le.'1')->applyFromArray($linea1);

				$sheet->getStyle($le.'1')->applyFromArray($izquierda);
				// $sheet->getColumnDimension($le.'1')->setWidth(100); 
				$sheet->getStyle($le.'1')->getAlignment()->setWrapText(true);		
				$sheet->setCellValue($le.'1',$richText1);
				$drawing->setCoordinates($le.'1');
				if($ti!='A' && $ti!='B'){
					$sheet->mergeCells('B1:'.$ti.'1');
				}
				$spreadsheet->getActiveSheet()->getStyle($let.'1:'.$le.'1')->getFill()->getStartColor()->setARGB('436BEE');


				$num+=1;
			}
		}

		foreach ($datos as $key => $value) {
			// print_r($value);die();

					$tipo  = '';
			if(isset($value['tipo']))
			{
					$tipo = $value['tipo'];
				}
			if(isset($value['unir'])){
				foreach ($value['unir'] as $key => $value3) {
					 $can_cel = strlen($value3); if($can_cel>2){$fin = substr($value3,-1); $inicio = substr($value3,0,1);}else{ $fin = substr($value3,1);$inicio = substr($value3,0,-1); } }	
					 // print_r($inicio.'-'.$fin);die();
					 $sheet->mergeCells($inicio.''.$num.':'.$fin.''.$num);
				}
				
			foreach ($value['datos'] as $key1 => $value1) {
				// $style = $izquierda;
				// $ali = $value['alineado'][$key1];
				// if($ali=='C'){$style = $centrar;}else if ($ali=='R') {$style = $derecha;}
				$sheet->getColumnDimension($let)->setWidth($value['medidas'][$key1]);
				
				$sheet->setCellValue($let.''.$num, $value1);			    	
			    
				if($tipo=='C')
				{
					$sheet->getStyle($let.''.$num)->applyFromArray($estilo_cabecera);
				}
				if($tipo=='SUB')
				{
					$sheet->getStyle($let.''.$num)->applyFromArray($estilo_subcabecera);
				}
				if($tipo=='SUBR')
				{
					$sheet->getStyle($let.''.$num)->applyFromArray($estilo_subcabecera_r);
				}
				if($tipo=='B')
				{
					$sheet->getStyle($let.''.$num)->applyFromArray($negrita);
				}
				if($tipo=='BC')
				{
					$sheet->getStyle($let.''.$num)->applyFromArray($negritaC);
				}
				if($tipo=='BR')
				{
					$sheet->getStyle($let.''.$num)->applyFromArray($negritaR);
				}
				if($tipo=='I')
				{
					$sheet->getStyle($let.''.$num)->applyFromArray($italica);
				}
				if($tipo=='D')
				{
					$sheet->getStyle($let.''.$num)->applyFromArray($derecha);
				}


				$let++;
			}
			$let='A';
			$num+=1;
		// print_r($value);die();		
			// $sheet->setCellValue('B1', 'Clinica Santa Barbara CENTRO MEDICO MATERNAL PAEZ ALMEIDA NARANJO SOCIEDAD COLECTIVA CIVIL');
		}

	    $write = new Xlsx($spreadsheet);
	   	if(!is_dir($url))
	    {
	    	mkdir($url,0777,true);
	    }
	    // print_r($url.$titulo);die();
	    // $url = '';
	    $write->save($url.$titulo.'.xlsx');
	    $ruta = $url.$titulo.'.xlsx';
	    
	    download_file($ruta, $titulo.'.xlsx'); 


		// echo "<meta http-equiv='refresh' content='0;url=".$url.$titulo.".xlsx'/>";
		// exit;

}

function excel_simple($datos, $path, $tituloHoja = 'Hoja 1'){
	$spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle($tituloHoja);

    $fila = 1;
    foreach ($datos as $dato) {
        $columna = 'A';
        foreach ($dato as $valor) {
            $sheet->setCellValue($columna.$fila, $valor);
            $columna++; // Incrementa la letra de la columna
        }
        $fila++; // Siguiente fila
    }

    // Ajustar automáticamente el tamaño de todas las columnas usadas
    foreach ($sheet->getColumnIterator() as $column) {
        $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    }
    
    $writer = new Xlsx($spreadsheet);
    $writer->save($path);
}

function Exportar_AdoDB_Excel($datosAdo, $path, $tituloHoja = "Hoja 1"){
	$spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle($tituloHoja);

	if(!empty($datosAdo)){
		$columna = 'A';
		foreach($datosAdo[0] as $key => $value){
			$sheet->setCellValue($columna.'1', $key);
            $columna++;
		}
	}

	$fila = 2;
	foreach ($datosAdo as $dato) {
        $columna = 'A';
        foreach ($dato as $valor) {
            $sheet->setCellValue($columna.$fila, conversionToString($valor));
            $columna++;
        }
        $fila++;
    }

	foreach ($sheet->getColumnIterator() as $column) {
        $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    }

	$writer = new Xlsx($spreadsheet);
    $writer->save($path);
	
}
?>