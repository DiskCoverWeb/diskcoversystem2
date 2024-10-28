<?php


error_reporting(E_ALL);
ini_set('display_errors', '1');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;

use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Shared\Date;

use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

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

function excel_generico($titulo,$datos=false)
{
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

	$estilo_cabecera = array('font' => ['bold' => true,],
							 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,],
							 'borders' => [	'top' => ['borderStyle' => Border::BORDER_THIN,	],],
							 'fill' => ['fillType' => Fill::FILL_GRADIENT_LINEAR,'rotation' => 90,'startColor' => ['argb' => '0086c7',],
							 'endColor' => ['argb' => 'FFFFFFFF',],],
							 );
	$linea1 = array('font' => ['bold' => true,],
								'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,],
								'borders' => ['top' => ['borderStyle' => Border::BORDER_THIN,],],
								'fill' => ['fillType' => Fill::FILL_GRADIENT_LINEAR,'rotation' => 90,'startColor' => ['argb' => '0086c7',],
								'endColor' => ['argb' => '0086c7',],],
								);
	$estilo_subcabecera = array('font' => ['bold' => true,],
								'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT,],
								'borders' => ['top' => ['borderStyle' => Border::BORDER_THIN,],],
								'fill' => ['fillType' => Fill::FILL_GRADIENT_LINEAR,'rotation' => 90,'startColor' => ['argb' => '0086c7',],
								'endColor' => ['argb' => 'FFFFFFFF',],],
								);
	 $centrar = array( 'alignment' => array('horizontal' => Alignment::HORIZONTAL_CENTER,) );
	 $derecha = array( 'alignment' => array('horizontal' => Alignment::HORIZONTAL_RIGHT,) );
	 $izquierda = array( 'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT,) );
	 $negrita = array('font' => ['bold' => true,],'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT,]);
	 $negritaC = array('font' => ['bold' => true,],'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,]);
	 $negritaR = array('font' => ['bold' => true,],'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT,]);

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
			// print_r($datos[0]['datos']);die();
			$col = count($datos[0]['datos']);
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
			$sheet->getColumnDimension($le.'1')->setWidth(100); 
			$sheet->getStyle($le.'1')->getAlignment()->setWrapText(true);		
			$sheet->setCellValue($le.'1',$richText1);
			$drawing->setCoordinates($le.'1');

			$sheet->mergeCells('B1:'.$ti.'1');
			$spreadsheet->getActiveSheet()->getStyle($let.'1:'.$le.'1')->getFill()->getStartColor()->setARGB('0086c7');


			$num+=1;
		}

		foreach ($datos as $key => $value) {
			$tipo = $value['tipo'];
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


				$let++;
			}
			$let='A';
			$num+=1;
		// print_r($value);die();		
			// $sheet->setCellValue('B1', 'Clinica Santa Barbara CENTRO MEDICO MATERNAL PAEZ ALMEIDA NARANJO SOCIEDAD COLECTIVA CIVIL');
		}

	    $write = new Xlsx($spreadsheet);
	    if($titulo!='')
	    {
	    	$write->save(dirname(__DIR__,2).'/php/vista/TEMP/'.$titulo.'.xlsx');
	        download_file(dirname(__DIR__,2).'/php/vista/TEMP/'.$titulo.".xlsx");

	 		 //$write->save($titulo.'.xlsx');
			// echo "<meta http-equiv='refresh' content='0;url=".$titulo.".xlsx'/>";
			// exit;
		}else
		{
			$write->save(dirname(__DIR__,2).'/php/vista/TEMP/reporte_sin_nombre.xlsx');
	        download_file(dirname(__DIR__,2).'/php/vista/TEMP/reporte_sin_nombre.xlsx');
			// $write->save('reporte_sin_nombre.xlsx');
			// echo "<meta http-equiv='refresh' content='0;url=reporte_sin_nombre.xlsx'/>";
			// exit;
		}

		
	      // NOMBRE DEL ARCHIVO Y CHARSET
	      //header("Content-type: application/vnd.ms-excel");
         // header("Content-Disposition: attachment; filename=INVENTARIO.xls");
         // header("Pragma: no-cache");
          //header("Expires: 0");


          // $salida=fopen('php://output', 'w');

 
}

function excel_file($stmt,$ti=null,$camne=null,$b=null,$base=null) 
{
	//require_once __DIR__ . '/vendor/phpoffice/phpspreadsheet/src/Bootstrap.php';
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	//$sheet->setCellValue('A1', 'Hello World !');

	// Set document properties
	$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
		->setLastModifiedBy('Maarten Balliauw')
		->setTitle('Office 2007 XLSX Test Document')
		->setSubject('Office 2007 XLSX Test Document')
		->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
		->setKeywords('office 2007 openxml php')
		->setCategory('Test result file');

	if($base==null or $base=='SQL SERVER')
	{
		//echo $base.' entrooo ';
		//para las celdas de excel
		$ie = 1;
		$je = 'B';
		//para sabar hasta donde unir celdas
		$je1=$je;
		//verificamos los campos
		//cantidad de campos
		$cant=0;
		//guardamos los campos
		$campo='';
		//obtenemos los campos 
		foreach( sqlsrv_field_metadata( $stmt ) as $fieldMetadata ) {
			foreach( $fieldMetadata as $name => $value) {
				if(!is_numeric($value))
				{
					if($value!='')
					{
						$cant++;
						//redimencionar
						$spreadsheet->getActiveSheet()->getColumnDimension($je1)
					->setAutoSize(true);
						$je1++;
					}
				}
			}
		}
		//cabecera
		//diseño de nombres de campos
		//'horizontal' => Alignment::HORIZONTAL_RIGHT,
		//nos posicionamos 3 posiciones anteriores campo
		$je2='B';
		for($i=0;$i<($cant-3);$i++)
		{
			$je2++;
		}
		$je1=$je2;
		$je3=$je2;
		$je3++;
		$spreadsheet->getActiveSheet()->getStyle('A2:'.$je3.'2')->applyFromArray(
			[
					'font' => [
						'bold' => true,
					],
					'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_CENTER,
					],
					'borders' => [
						'top' => [
							'borderStyle' => Border::BORDER_THIN,
						],
					],
					'fill' => [
						'fillType' => Fill::FILL_GRADIENT_LINEAR,
						'rotation' => 90,
						'startColor' => [
							/*'argb' => 'FFA0A0A0',*/
							'argb' => '0086c7',
						],
						'endColor' => [
							'argb' => 'FFFFFFFF',
						],
					],
				]
		);
		//redimencionar
		//$spreadsheet->getActiveSheet()->getColumnDimension($je.''.$ie.':'.$je1.''.$ie)->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getStyle('B1')->applyFromArray(
			[
					'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_CENTER,
					],
					
			]
		);
		//logo empresa
		//echo __DIR__ ;
		////die();
		$drawing = new Drawing();
		$drawing->setName('Logo');
		$drawing->setDescription('Logo');
		//windows
		//$drawing->setPath(__DIR__ . '\logosMod.gif');
		//linux
		//$drawing->setPath(__DIR__ . '/logosMod.gif');
		if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
			//si es jpg
			$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
			if (@getimagesize($src)) 
			{ 
				$logo=$logo.'.jpg';
			}
			else
			{
				//si es gif
				$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
				if (@getimagesize($src)) 
				{ 
					$logo=$logo.'.gif';
				}
				else
				{
					//si es png
					$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
					if (@getimagesize($src)) 
					{ 
						$logo=$logo.'.png';
					}
					else
					{
						$logo="DEFAULT.jpg";
					}
				}
			}
		}
		else
		{
			$logo="DEFAULT.jpg";
		}
		/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		}
		else
		{
			$logo="DEFAULT";
		}*/
		$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo);
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates('A1');
		$drawing->setOffsetX(10);
		$drawing->setOffsetY(20);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//otro logo
		$drawing = new Drawing();
		$drawing->setName('Logo1');
		$drawing->setDescription('Logo1');
		//windows
		//$drawing->setPath(__DIR__ . '\logosMod.gif');
		//linux
		$drawing->setPath(__DIR__ . '/logosMod.gif');
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates($je3.'1');
		$drawing->setOffsetX(100);
		$drawing->setOffsetY(10);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//unir celdas
		$spreadsheet->getActiveSheet()->mergeCells($je.''.$ie.':'.$je2.''.$ie);
		if($_SESSION['INGRESO']['Razon_Social']!=$_SESSION['INGRESO']['noempr'])
		{
			$richText2 = new RichText();
			$richText2->createText($_SESSION['INGRESO']['noempr'].' ');

			$red = $richText2->createTextRun($_SESSION['INGRESO']['Razon_Social']);
			$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$spreadsheet->getActiveSheet()->setCellValue('B1', $richText2);
			//ampliar columna
			//$spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setWrapText(true);
		}
		else
		{
			$spreadsheet->getActiveSheet()->setCellValue('B1', $_SESSION['INGRESO']['noempr'].'');
		}

		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setName('Candara');
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setUnderline(Font::UNDERLINE_SINGLE);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('B1')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
		$spreadsheet->getActiveSheet()->getColumnDimension('B1')->setAutoSize(true);
		$je1++;

		$richText1 = new RichText();
		$richText1->createText(''."\n");

		
		$redf=$richText1->createTextRun("Hora: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("H:i")."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Fecha: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("d-m-Y") ."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Usuario: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$red = $richText1->createTextRun($_SESSION['INGRESO']['Nombre']);
		$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$red->getFont()->setSize(8);
			
		$spreadsheet->getActiveSheet()
			->getCell($je1.''.$ie)
			->setValue($richText1);

			//ampliar columna
		$spreadsheet->getActiveSheet()
			->getStyle($je1.''.$ie)
			->getAlignment()->setWrapText(true);
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, Date::PHPToExcel(gmmktime(0, 0, 0, date('m'), date('d'), date('Y'))));
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_XLSX15);
		$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		//$spreadsheet->getActiveSheet()->getColumnDimension($je1)->setAutoSize(true);

		 $spreadsheet->getActiveSheet()->getColumnDimension($je1)->setWidth(23);
	    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
       
		//$je1++;
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, '');
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->setFillType(Fill::FILL_SOLID);
		//$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('FF808080');

		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('0086c7');

		//si lleva o no border
		$bor='';
		if($b!=null and $b!='0')
		{
			$bor='table-bordered1';
			//style="border-top: 1px solid #bce8f1;"
		}
		//nombre de columnas
		//para las celdas de excel
		$ie = 2;
		$je = 'A';
		//cantidad campos
		$cant=0;
		//guardamos los campos
		$campo='';
		//tipo de campos
		$tipo_campo=array();
		//guardamos posicion de un campo ejemplo fecha
		$cam_fech=array();
		//contador para fechas
		$cont_fecha=0;
		//obtenemos los campos 

		foreach( sqlsrv_field_metadata( $stmt ) as $fieldMetadata ) {
			$ie = 2;
			//$camp='';
			$i=0;
			//tipo de campo
			$ban=0;
			//texto
			if($fieldMetadata['Type']==-9)
			{
				$tipo_campo[($cant)]="style='text-align: left;'";
				$ban=1;
			}
			//numero
			if($fieldMetadata['Type']==3)
			{
				//number_format($item_i['nombre'],2, ',', '.')
				$tipo_campo[($cant)]="style='text-align: right;'";
				$ban=1;
			}
			//echo $fieldMetadata['Type'].' ccc <br>';
			//echo $fieldMetadata['Name'].' ccc <br>';
			//caso fecha
			if($fieldMetadata['Type']==93)
			{
				$tipo_campo[($cant)]="style='text-align: left;'";
				$ban=1;
				$cam_fech[$cont_fecha]=$cant;
				//contador para fechas
				$cont_fecha++;
			}
			//caso bit
			if($fieldMetadata['Type']==-7)
			{
				$tipo_campo[($cant)]="style='text-align: left;'";
				$ban=1;
			}
			//caso int
			if($fieldMetadata['Type']==4)
			{
				$tipo_campo[($cant)]="style='text-align: right;'";
				$ban=1;
			}
			//caso tinyint
			if($fieldMetadata['Type']==-6)
			{
				$tipo_campo[($cant)]="style='text-align: right;'";
				$ban=1;
			}
			//caso smallint
			if($fieldMetadata['Type']==5)
			{
				$tipo_campo[($cant)]="style='text-align: right;'";
				$ban=1;
			}
			//caso real
			if($fieldMetadata['Type']==7)
			{
				$tipo_campo[($cant)]="style='text-align: right;'";
				$ban=1;
			}
			//caso float
			if($fieldMetadata['Type']==6)
			{
				$tipo_campo[($cant)]="style='text-align: right;'";
				$ban=1;
			}
			//uniqueidentifier
			if($fieldMetadata['Type']==-11)
			{
				$tipo_campo[($cant)]="style='text-align: right;'";
				$ban=1;
			}
			//ntext
			if($fieldMetadata['Type']==-10)
			{
				$tipo_campo[($cant)]="style='text-align: left;'";
				$ban=1;
			}
			//ntext
			if($fieldMetadata['Type']==12)
			{
				$tipo_campo[($cant)]="style='text-align: left;'";
				$ban=1;
			}
			if($ban==0)
			{
				echo ' no existe tipo '.$value;
				die();
			}
			foreach( $fieldMetadata as $name => $value) {
				
				if(!is_numeric($value))
				{
					if($value!='')
					{
						//echo "<th ".$tipo_campo[$cant].">".$value."</th>";
						$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $value);
						$spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
						$camp=$value;
						$campo[$cant]=$camp;
						//echo  $value.' dd '.$campo[$cant].'<br>';
						$cant++;
						$ie++;
						//echo $value.' cc '.$cant.' ';
					}
				}
			   //echo "$name: $value<br />";
			}
			//incrementamos la celda
			$je++;
		}
		$ie = 3;
		$je = 'A';

		//imprimir valores
		//echo $cant.' fffff ';
		//obtener la configuracion para celdas personalizadas
		//campos a evaluar
		$campoe=array();
		//valor a verificar
		$campov=array();
		//campo a afectar 
		$campoaf=array();
		//adicional
		$adicional=array();
		//signos para comparar
		$signo=array();
		//titulo de proceso
		$tit=array();
		//indice de registros a comparar con datos
		$ind=0;
		//obtener valor en caso de mas de una condicion
		$con_in=0;
		if($camne!=null)
		{
			for($i=0;$i<count($camne['TITULO']);$i++)
			{
				if($camne['TITULO'][$i]=='color_fila')
				{	
					$tit[$ind]=$camne['TITULO'][$i];
					//temporar para indice
					//$temi=$i;
					//buscamos campos a evaluar
					$camneva = explode(",", $camne['CAMPOE'][$i]);
					//si solo es un campo
					if(count($camneva)==1)
					{
						$camneva1 = explode("=", $camneva[0]);
						$campoe[$ind]=$camneva1[0];
						$campov[$ind]=$camneva1[1];
						//echo ' pp '.$campoe[$ind].' '.$campov[$ind];
					}
					else
					{
						//hacer bucle
					}
					//para los campos a afectar
					if(count($camne['CAMPOA'])==1 AND $i==0)
					{
						if($camne['CAMPOA'][$i]=='TODOS' OR $camne['CAMPOA'][$i]='')
						{
							$campoaf[$ind]='TODOS';
						}
						else
						{
							//otras opciones
						}
					}
					else
					{
						//bucle
						if(!empty($camne['CAMPOA'][$i]))
						{
							if($camne['CAMPOA'][$i]=='TODOS' OR $camne['CAMPOA'][$i]='')
							{
								$campoaf[$ind]='TODOS';
							}
							else
							{
								//otras opciones
							}
						}
					}
					//valor adicional en este caso color
					if(count($camne['ADICIONAL'])==1 AND $i==0)
					{
						$adicional[$ind]=$camne['ADICIONAL'][$i];
					}
					else
					{
						//bucle
						if(!empty($camne['ADICIONAL'][$i]))
						{
							$adicional[$ind]=$camne['ADICIONAL'][$i];
						}
					}
					//signo de comparacion
					if(count($camne['SIGNO'])==1 AND $i==0)
					{
						$signo[$ind]=$camne['SIGNO'][$i];
					}
					else
					{
						//bucle
						if(!empty($camne['SIGNO'][$i]))
						{
							$signo[$ind]=$camne['SIGNO'][$i];
						}
					}
					$ind++;
					//echo ' pp '.count($camneva);
				}
				//caso italica, subrayar, indentar
				if($camne['TITULO'][$i]=='italica' OR $camne['TITULO'][$i]=='subrayar' OR $camne['TITULO'][$i]=='indentar')
				{
					$tit[$ind]=$camne['TITULO'][$i];
						//buscamos campos a evaluar
					if(!is_array($camne['CAMPOE'][$i]))
					{
						$camneva = explode(",", $camne['CAMPOE'][$i]);
						//si solo es un campo
						if(count($camneva)==1)
						{
							$camneva1 = explode("=", $camneva[0]);
							$campoe[$ind]=$camneva1[0];
							$campov[$ind]=$camneva1[1];
							//echo ' pp '.$campoe[$ind].' '.$campov[$ind];
						}
						else
						{
							//hacer bucle
						}
					}
					else
					{
						//es mas de un campo
						$con_in = count($camne['CAMPOE'][$i]);
						//recorremos registros
						for($j=0;$j<$con_in;$j++)
						{
							//echo $camne['CAMPOE'][$i][$j].' ';
							$camneva = explode(",", $camne['CAMPOE'][$i][$j]);
							//si solo es un campo
							if(count($camneva)==1)
							{
								$camneva1 = explode("=", $camneva[0]);
								$campoe[$ind][$j]=$camneva1[0];
								$campov[$ind][$j]=$camneva1[1];
								//echo ' pp '.$campoe[$ind][$j].' '.$campov[$ind][$j];
							}
						}
					}
					//para los campos a afectar
					if(!is_array($camne['CAMPOA'][$i]))
					{
						if(count($camne['CAMPOA'])==1 AND $i==0)
						{
							$campoaf[$ind]=$camne['CAMPOA'][$i];
						}
						else
						{
							//bucle
							if(!empty($camne['CAMPOA'][$i]))
							{
								//otras opciones
								$campoaf[$ind]=$camne['CAMPOA'][$i];
							}
						}
					}
					else
					{
						//recorremos el ciclo
						//es mas de un campo
						$con_in = count($camne['CAMPOA'][$i]);
						//recorremos registros
						for($j=0;$j<$con_in;$j++)
						{
							$campoaf[$ind][$j]=$camne['CAMPOA'][$i][$j];
							//echo ' pp '.$campoaf[$ind][$j];
						}
					}
					//valor adicional en este caso color
					
						if(count($camne['ADICIONAL'])==1 AND $i==0)
						{
							$adicional[$ind]=$camne['ADICIONAL'][$i];
						}
						else
						{
							//bucle
							if(!empty($camne['ADICIONAL'][$i]))
							{
								//es mas de un campo
								$con_in = count($camne['ADICIONAL'][$i]);
								for($j=0;$j<$con_in;$j++)
								{
									$adicional[$ind][$j]=$camne['ADICIONAL'][$i][$j];
									//echo ' pp '.$adicional[$ind][$j];
								}
							}
						}
					
					
					//signo de comparacion
					if(!is_array($camne['SIGNO'][$i]))
					{
						if(count($camne['SIGNO'])==1 AND $i==0)
						{
							$signo[$ind]=$camne['SIGNO'][$i];
						}
						else
						{
							//bucle
							if(!empty($camne['SIGNO'][$i]))
							{
								$signo[$ind]=$camne['SIGNO'][$i];
							}
						}
					}
					else
					{
						//es mas de un campo
						$con_in = count($camne['SIGNO'][$i]);
						for($j=0;$j<$con_in;$j++)
						{
							$signo[$ind][$j]=$camne['SIGNO'][$i][$j];
							//echo ' pp '.$signo[$ind][$j];
						}
					}
					$ind++;
				}
			}
		}
		$i=0;


		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			//comparamos con los valores de los array para personalizar las celdas
			//para titulo color fila
			$cfila1='';
			$cfila2='';
			//indentar
			$inden='';
			$indencam=array();
			$indencam1=array();
			//contador para caso indentar
			$conin=0;
			//contador caso para saber si cumple varias condiciones ejemplo italica TC=P OR TC=C
			$ca_it=0;
			//variable para colocar italica
			$ita1='';
			$ita2='';
			//contador para caso italicas
			$conita=0;
			//valores de campo a afectar
			$itacam1=array();
			//variables para subrayar
			//valores de campo a afectar en caso subrayar
			$subcam1=array();
			//contador caso subrayar
			$consub=0;
			//contador caso para saber si cumple varias condiciones ejemplo subrayar TC=P OR TC=C
			$ca_sub=0;
			//variable para colocar subrayar
			$sub1='';
			$sub2='';
			for($i=0;$i<$ind;$i++)
			{
				if($tit[$i]=='color_fila')
				{
					if(!is_array($campoe[$i]))
					{
						//campo a comparar
						$tin=$campoe[$i];
						//comparamos valor
						if($signo[$i]=='=')
						{
							if($row[$tin]==$campov[$i])
							{
								if($adicional[$i]=='black')
								{
									//activa condicion
									$cfila1='<B>';
									$cfila2='</B>';
								}
							}
						}
					}
				}
				if($tit[$i]=='indentar')
				{	
					if(!is_array($campoe[$i]))
					{
						//campo a comparar
						$tin=$campoe[$i];
						//comparamos valor
						if($signo[$i]=='=')
						{
							if($campov[$i]=='contar')
							{
								$inden1 = explode(".", $row[$tin]);
								//echo ' '.count($inden1);
								//hacemos los espacios
								//$inden=str_repeat("&nbsp;&nbsp;", count($inden1));
								if(count($inden1)>1)
								{
									$indencam1[$conin]=str_repeat("    ", (count($inden1)-1));
								}
								else
								{
									$indencam1[$conin]="";
								}
							}
							$indencam[$conin]=$campoaf[$i];
							//echo $indencam[$conin].' dd ';
							$conin++;
						}
					}
				}
				if($tit[$i]=='italica')
				{	
					if(!is_array($campoe[$i]))
					{
						
					}
					else
					{
						//es mas de un campo
						$con_in = count($campoe[$i]);
						$ca_it=0;
						for($j=0;$j<$con_in;$j++)
						{
							$tin=$campoe[$i][$j];
							//echo ' pp '.$tin[$i][$j];
							//comparamos valor
							if($signo[$i][$j]=='=')
							{
								//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
								if($row[$tin]==$campov[$i][$j])
								{
									$ca_it++;
								}
							}
							//si es diferente
							if($signo[$i][$j]=='<>')
							{
								//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
								if($row[$tin]<>$campov[$i][$j])
								{
									$ca_it++;
								}
							}
							
						}
						$con_in = count($campoaf[$i]);
						for($j=0;$j<$con_in;$j++)
						{
							$itacam1[$conita]=$campoaf[$i][$j];
							//echo $itacam1[$conita].' ';
							$conita++;
						}
						//echo $ca_it.' cdcd '.count($campoe[$i]).'<br/>';
						if($ca_it==count($campoe[$i]))
						{
							$ita1='<em>';
							$ita2='</em>';
						}
						else
						{
							$ita1='';
							$ita2='';
						}
					}
					
				}
				if($tit[$i]=='subrayar')
				{	
					if(!is_array($campoe[$i]))
					{
						
					}
					else
					{
						//es mas de un campo
						$con_in = count($campoe[$i]);
						$ca_sub=0;
						$ca_sub1=0;
						for($j=0;$j<$con_in;$j++)
						{
							$tin=$campoe[$i][$j];
							//echo ' pp '.$tin[$i][$j];
							//comparamos valor
							if($signo[$i][$j]=='=')
							{
								//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
								if($row[$tin]==$campov[$i][$j])
								{
									$ca_sub++;
									$ca_sub1++;
								}
							}
							//si es diferente
							if($signo[$i][$j]=='<>')
							{
								//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
								if($row[$tin]<>$campov[$i][$j])
								{
									$ca_sub++;
								}
							}
							
						}
						$con_in = count($campoaf[$i]);
						for($j=0;$j<$con_in;$j++)
						{
							$subcam1[$consub]=$campoaf[$i][$j];
							//echo $subcam1[$consub].' ';
							$consub++;
						}
						//echo $ca_it.' cdcd '.count($campoe[$i]).'<br/>';
						$sub1='';
						$sub2='';
						//condicion para verificar si signo es "=" o no
						if($ca_sub1==0)
						{
							//condicion en caso de distintos
							if($ca_sub==count($campoe[$i]))
							{
								$sub1='<u>';
								$sub2='</u>';
							}
							else
							{
								$sub1='';
								$sub2='';
							}
						}
						else
						{
							$sub1='<u>';
							$sub2='</u>';
						}
					}
				}
			}
			$je='A';
			for($i=0;$i<$cant;$i++)
			{
				
				//caso indentar
				for($j=0;$j<count($indencam);$j++)
				{
					if($indencam[$j]==$i)
					{
						$inden=$indencam1[$j];
					}
					else
					{
						$inden='';
					}
				}
				//caso italica
				$ita3="";
				$ita4="";
				//letra
				$ita5="Calibri";
				for($j=0;$j<count($itacam1);$j++)
				{
					//echo $itacam1[$j].' ssscc '.$i;
					if($itacam1[$j]==$i)
					{
						$ita3=$ita1;
						$ita4=$ita2;
						if($ita3=='<em>')
						{
							$ita5="Arial Narrow";
						}
					}
					
				}
				//caso subrayado
				$sub3="";
				$sub4="";
				for($j=0;$j<count($subcam1);$j++)
				{
					//echo $itacam1[$j].' ssscc '.$i;
					if($subcam1[$j]==$i)
					{
						$sub3=$sub1;
						$sub4=$sub2;
					}
					
				}
				//caso de campos fechas
				for($j=0;$j<count($cam_fech);$j++)
				{
					//echo $itacam1[$j].' ssscc '.$i;
					if($cam_fech[$j]==$i)
					{
						//$row[$i]=$row[$i]->format('Y-m-d H:i:s');
						$row[$i]=$row[$i]->format('Y-m-d');
					}
					
				}
				//echo "<br/>";
				//formateamos texto si es decimal
				if($tipo_campo[$i]=="style='text-align: right;'")
				{
					//si es cero colocar -
					
					if(number_format($row[$i],2, '.', ',')==0 OR number_format($row[$i],2, '.', ',')=='0,00')
					{
						if($sub3=='<u>')
						{
							$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
								[
										'font' => [
											'underline' => Font::UNDERLINE_SINGLE,
										],
									]
							);
						}
						if($cfila1=='<B>')
						{
							$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
								[
										'font' => [
											'bold' => true,
										],
									]
							);

						}
						//echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$sub3.$inden."-".$sub4.$ita4.$cfila2."</td>";
						$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $inden."-");
						$spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
					}
					else
					{
						//si es negativo colocar rojo
						if($row[$i]<0)
						{
							if($sub3=='<u>')
							{
								$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
									[
											'font' => [
												'underline' => Font::UNDERLINE_SINGLE,
											],
										]
								);
							}
							if($cfila1=='<B>')
							{
								$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
									[
											'font' => [
												'bold' => true,
											],
										]
								);

							}
							//reemplazo una parte de la cadena por otra
							$longitud_cad = strlen($tipo_campo[$i]); 
							$cam2 = substr_replace($tipo_campo[$i],"color: red;'",$longitud_cad-1,1); 
							//echo "<td ".$cam2." > ".$cfila1.$ita3.$inden.$sub3."".number_format($row[$i],2, ',', '.')."".$sub4.$ita4.$cfila2."</td>";
							$richText2 = new RichText();
							$red = $richText2->createTextRun(number_format($row[$i],2, '.', ','));
							$red->getFont()->setColor(new Color(Color::COLOR_RED));
							$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $inden.$richText2);
							$spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
							
						}
						else
						{
							if($sub3=='<u>')
							{
								$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
									[
											'font' => [
												'underline' => Font::UNDERLINE_SINGLE,
											],
										]
								);
							}
							if($cfila1=='<B>')
							{
								$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
									[
											'font' => [
												'bold' => true,
											],
										]
								);

							}
							//echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$inden.$sub3."".number_format($row[$i],2, ',', '.')."".$sub4.$ita4.$cfila2."</td>";
							$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $inden.number_format($row[$i],2, '.', ','));
							$spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
						}
					}
					
				}
				else
				{
					//echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$inden.$sub3."".$row[$i]."".$sub4.$ita4.$cfila2."</td>";
							$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
							[
									'alignment' => [
										'horizontal' => Alignment::HORIZONTAL_LEFT,
									],
									'font' => [
										'name' => $ita5,
										//'underline' => Font::UNDERLINE_SINGLE,
									],
								]
						);
					if($sub3=='<u>')
					{
						$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
							[
									'font' => [
										'underline' => Font::UNDERLINE_SINGLE,
									],
								]
						);
					}
					if($cfila1=='<B>')
					{
						$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
							[
									'font' => [
										'bold' => true,
										'name' => $ita5,
									],
								]
						);

					}
					$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $inden.$row[$i]);
					$spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
				}
				$je++;
			}
			$ie++;
		}	
	}
	else
	{
		if($base=='MYSQL')
		{
			$info_campo = $stmt->fetch_fields();
			//para las celdas de excel
			$ie = 1;
			$je = 'B';
			//para sabar hasta donde unir celdas
			$je1=$je;
			//verificamos los campos
			//cantidad de campos
			$cant=0;
			//guardamos los campos
			$campo='';
			foreach ($info_campo as $valor) 
			{
				$cant++;
				//echo $valor->name;
			}
			//die();
			//cabecera
			//diseño de nombres de campos
			//'horizontal' => Alignment::HORIZONTAL_RIGHT,
			//nos posicionamos 3 posiciones anteriores campo
			$je2='B';
			for($i=0;$i<($cant-3);$i++)
			{
				$je2++;
			}
			$je1=$je2;
			$je3=$je2;
			$je3++;
			$spreadsheet->getActiveSheet()->getStyle('A3:'.$je3.'3')->applyFromArray(
				[
						'font' => [
							'bold' => true,
						],
						'alignment' => [
							'horizontal' => Alignment::HORIZONTAL_CENTER,
						],
						'borders' => [
							'top' => [
								'borderStyle' => Border::BORDER_THIN,
							],
						],
						'fill' => [
							'fillType' => Fill::FILL_GRADIENT_LINEAR,
							'rotation' => 90,
							'startColor' => [
								/*'argb' => 'FFA0A0A0',*/
								'argb' => '0086c7',
							],
							'endColor' => [
								'argb' => 'FFFFFFFF',
							],
						],
					]
			);
			//redimencionar
			//$spreadsheet->getActiveSheet()->getColumnDimension($je.''.$ie.':'.$je1.''.$ie)->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getStyle('B1')->applyFromArray(
				[
						'alignment' => [
							'horizontal' => Alignment::HORIZONTAL_CENTER,
						],
						
				]
			);
			//logo empresa
			//echo __DIR__ ;
			//die();
			$drawing = new Drawing();
			$drawing->setName('Logo');
			$drawing->setDescription('Logo');
			//windows
			//$drawing->setPath(__DIR__ . '\logosMod.gif');
			//linux
			//$drawing->setPath(__DIR__ . '/logosMod.gif');
			if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
			{
				$logo=$_SESSION['INGRESO']['Logo_Tipo'];
				//si es jpg
				$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
				if (@getimagesize($src)) 
				{ 
					$logo=$logo.'.jpg';
				}
				else
				{
					//si es gif
					$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
					if (@getimagesize($src)) 
					{ 
						$logo=$logo.'.gif';
					}
					else
					{
						//si es png
						$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
						if (@getimagesize($src)) 
						{ 
							$logo=$logo.'.png';
						}
						else
						{
							$logo="DEFAULT.jpg";
						}
					}
				}
			}
			else
			{
				$logo="DEFAULT.jpg";
			}
			/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
			{
				$logo=$_SESSION['INGRESO']['Logo_Tipo'];
			}
			else
			{
				$logo="DEFAULT";
			}*/
			$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo);
			//$drawing->setPath('logosMod.gif');
			$drawing->setHeight(36);
			$drawing->setCoordinates('A1');
			$drawing->setOffsetX(10);
			$drawing->setOffsetY(20);
			$drawing->setWorksheet($spreadsheet->getActiveSheet());
			//otro logo
			$drawing = new Drawing();
			$drawing->setName('Logo1');
			$drawing->setDescription('Logo1');
			//windows
			//$drawing->setPath(__DIR__ . '\logosMod.gif');
			//linux
			$drawing->setPath(__DIR__ . '/logosMod.gif');
			//$drawing->setPath('logosMod.gif');
			$drawing->setHeight(36);
			$drawing->setCoordinates($je3.'1');
			$drawing->setOffsetX(100);
			$drawing->setOffsetY(10);
			$drawing->setWorksheet($spreadsheet->getActiveSheet());
			//unir celdas
			$spreadsheet->getActiveSheet()->mergeCells($je.''.$ie.':'.$je2.''.$ie);
			if($_SESSION['INGRESO']['Razon_Social']!=$_SESSION['INGRESO']['noempr'])
			{
				$richText2 = new RichText();
				$richText2->createText($_SESSION['INGRESO']['noempr'].' ');

				$red = $richText2->createTextRun($_SESSION['INGRESO']['Razon_Social']);
				$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
				$spreadsheet->getActiveSheet()->setCellValue('B1', $richText2);
				//ampliar columna
				//$spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setWrapText(true);
			}
			else
			{
				$spreadsheet->getActiveSheet()->setCellValue('B1', $_SESSION['INGRESO']['noempr'].'');
			}
			$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setName('Candara');
			$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
			$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
			$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setUnderline(Font::UNDERLINE_SINGLE);
			$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
			
			$spreadsheet->getActiveSheet()->getStyle('B1')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
			$spreadsheet->getActiveSheet()->getColumnDimension('B1')->setAutoSize(true);
			$je1++;
			$richText1 = new RichText();
			$richText1->createText(''."\n");
			
			$redf=$richText1->createTextRun("Hora: ");
			$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$redf->getFont()->setSize(8);
			$redf->getFont()->setBold(true);
			
			$redf=$richText1->createTextRun(date("H:i")."\n");
			$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$redf->getFont()->setSize(8);
			
			$redf=$richText1->createTextRun("Fecha: ");
			$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$redf->getFont()->setSize(8);
			$redf->getFont()->setBold(true);
			
			$redf=$richText1->createTextRun(date("d-m-Y") ."\n");
			$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$redf->getFont()->setSize(8);
			
			$redf=$richText1->createTextRun("Usuario: ");
			$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$redf->getFont()->setSize(8);
			$redf->getFont()->setBold(true);
			
			$red = $richText1->createTextRun($_SESSION['INGRESO']['Nombre']);
			$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$red->getFont()->setSize(8);
			
			$spreadsheet->getActiveSheet()
				->getCell($je1.''.$ie)
				->setValue($richText1);

				//ampliar columna
			$spreadsheet->getActiveSheet()
				->getStyle($je1.''.$ie)
				->getAlignment()->setWrapText(true);
			//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, Date::PHPToExcel(gmmktime(0, 0, 0, date('m'), date('d'), date('Y'))));
			//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_XLSX15);
			$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
			$spreadsheet->getActiveSheet()->getColumnDimension($je1)->setAutoSize(true);
			//$je1++;
			//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, '');
			//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
			
			$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->setFillType(Fill::FILL_SOLID);
			//$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('FF808080');

			$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('0086c7');
			
			//si lleva o no border
			$bor='';
			if($b!=null and $b!='0')
			{
				$bor='table-bordered1';
				//style="border-top: 1px solid #bce8f1;"
			}
			//nombre de columnas
			//para las celdas de excel
			$ie = 3;
			$je = 'A';
			//cantidad campos
			$cant=0;
			//guardamos los campos
			$campo='';
			//tipo de campos
			$tipo_campo=array();
			//guardamos posicion de un campo ejemplo fecha
			$cam_fech=array();
			//contador para fechas
			$cont_fecha=0;
			//obtenemos los campos 
			foreach ($info_campo as $valor) 
			{
				//$camp='';
				$i=0;
				//tipo de campo
				/*
				tinyint_    1   boolean_    1   smallint_    2 int_        3
				float_      4   double_     5   real_        5 timestamp_    7
				bigint_     8   serial      8   mediumint_    9 date_        10
				time_       11  datetime_   12  year_        13 bit_        16
				decimal_    246 text_       252 tinytext_    252 mediumtext_    252
				longtext_   252 tinyblob_   252 mediumblob_    252 blob_        252
				longblob_   252 varchar_    253 varbinary_    253 char_        254
				binary_     254
				*/
				$ban=0;
				//texto
				if( $valor->type==7 OR $valor->type==8 OR $valor->type==10
				 OR $valor->type==11 OR $valor->type==12 OR $valor->type==13 OR $valor->type==16 
				 OR $valor->type==252 OR $valor->type==253 OR $valor->type==254 )
				{
					$tipo_campo[($cant)]="style='text-align: left;'";
					$ban=1;
				}
				if( $valor->type==10 OR $valor->type==11 OR $valor->type==12  )
				{
					$tipo_campo[($cant)]="style='text-align: left; width:80px;'";
					$ban=1;
				}
				
				//numero
				if($valor->type==3 OR $valor->type==2 OR $valor->type==4 OR $valor->type==5
				 OR isset($valor->type['Type'])==8 OR $valor->type==8 OR $valor->type==9 OR $valor->type==246)
				{
					//number_format($item_i['nombre'],2, ',', '.')
					$tipo_campo[($cant)]="style='text-align: right;'";
					$ban=1;
				}
				if($ban==0)
				{
					echo ' no existe tipo '.$valor->type.' '.$valor->name.' '.$valor->table;
				}
				/*echo "<th ".$tipo_campo[$cant].">".$valor->name."</th>";
							$camp=$valor->name;
							$campo[$cant]=$camp;*/
				//echo "<th ".$tipo_campo[$cant].">".$value."</th>";
							$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $valor->name);
							$spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
							$camp=$valor->name;
							$campo[$cant]=$camp;
							//echo ' dd '.$campo[$cant];
							$cant++;
							//$ie++;
							$je++;
			}
			$ie = 4;
			$je = 'A';
			//imprimir valores
			//echo $cant.' fffff ';
			//obtener la configuracion para celdas personalizadas
			//campos a evaluar
			$campoe=array();
			//valor a verificar
			$campov=array();
			//campo a afectar 
			$campoaf=array();
			//adicional
			$adicional=array();
			//signos para comparar
			$signo=array();
			//titulo de proceso
			$tit=array();
			//indice de registros a comparar con datos
			$ind=0;
			//obtener valor en caso de mas de una condicion
			$con_in=0;
			if($camne!=null)
			{
				for($i=0;$i<count($camne['TITULO']);$i++)
				{
					if($camne['TITULO'][$i]=='color_fila')
					{	
						$tit[$ind]=$camne['TITULO'][$i];
						//temporar para indice
						//$temi=$i;
						//buscamos campos a evaluar
						$camneva = explode(",", $camne['CAMPOE'][$i]);
						//si solo es un campo
						if(count($camneva)==1)
						{
							$camneva1 = explode("=", $camneva[0]);
							$campoe[$ind]=$camneva1[0];
							$campov[$ind]=$camneva1[1];
							//echo ' pp '.$campoe[$ind].' '.$campov[$ind];
						}
						else
						{
							//hacer bucle
						}
						//para los campos a afectar
						if(count($camne['CAMPOA'])==1 AND $i==0)
						{
							if($camne['CAMPOA'][$i]=='TODOS' OR $camne['CAMPOA'][$i]='')
							{
								$campoaf[$ind]='TODOS';
							}
							else
							{
								//otras opciones
							}
						}
						else
						{
							//bucle
							if(!empty($camne['CAMPOA'][$i]))
							{
								if($camne['CAMPOA'][$i]=='TODOS' OR $camne['CAMPOA'][$i]='')
								{
									$campoaf[$ind]='TODOS';
								}
								else
								{
									//otras opciones
								}
							}
						}
						//valor adicional en este caso color
						if(count($camne['ADICIONAL'])==1 AND $i==0)
						{
							$adicional[$ind]=$camne['ADICIONAL'][$i];
						}
						else
						{
							//bucle
							if(!empty($camne['ADICIONAL'][$i]))
							{
								$adicional[$ind]=$camne['ADICIONAL'][$i];
							}
						}
						//signo de comparacion
						if(count($camne['SIGNO'])==1 AND $i==0)
						{
							$signo[$ind]=$camne['SIGNO'][$i];
						}
						else
						{
							//bucle
							if(!empty($camne['SIGNO'][$i]))
							{
								$signo[$ind]=$camne['SIGNO'][$i];
							}
						}
						$ind++;
						//echo ' pp '.count($camneva);
					}
					//caso italica, subrayar, indentar
					if($camne['TITULO'][$i]=='italica' OR $camne['TITULO'][$i]=='subrayar' OR $camne['TITULO'][$i]=='indentar')
					{
						$tit[$ind]=$camne['TITULO'][$i];
							//buscamos campos a evaluar
						if(!is_array($camne['CAMPOE'][$i]))
						{
							$camneva = explode(",", $camne['CAMPOE'][$i]);
							//si solo es un campo
							if(count($camneva)==1)
							{
								$camneva1 = explode("=", $camneva[0]);
								$campoe[$ind]=$camneva1[0];
								$campov[$ind]=$camneva1[1];
								//echo ' pp '.$campoe[$ind].' '.$campov[$ind];
							}
							else
							{
								//hacer bucle
							}
						}
						else
						{
							//es mas de un campo
							$con_in = count($camne['CAMPOE'][$i]);
							//recorremos registros
							for($j=0;$j<$con_in;$j++)
							{
								//echo $camne['CAMPOE'][$i][$j].' ';
								$camneva = explode(",", $camne['CAMPOE'][$i][$j]);
								//si solo es un campo
								if(count($camneva)==1)
								{
									$camneva1 = explode("=", $camneva[0]);
									$campoe[$ind][$j]=$camneva1[0];
									$campov[$ind][$j]=$camneva1[1];
									//echo ' pp '.$campoe[$ind][$j].' '.$campov[$ind][$j];
								}
							}
						}
						//para los campos a afectar
						if(!is_array($camne['CAMPOA'][$i]))
						{
							if(count($camne['CAMPOA'])==1 AND $i==0)
							{
								$campoaf[$ind]=$camne['CAMPOA'][$i];
							}
							else
							{
								//bucle
								if(!empty($camne['CAMPOA'][$i]))
								{
									//otras opciones
									$campoaf[$ind]=$camne['CAMPOA'][$i];
								}
							}
						}
						else
						{
							//recorremos el ciclo
							//es mas de un campo
							$con_in = count($camne['CAMPOA'][$i]);
							//recorremos registros
							for($j=0;$j<$con_in;$j++)
							{
								$campoaf[$ind][$j]=$camne['CAMPOA'][$i][$j];
								//echo ' pp '.$campoaf[$ind][$j];
							}
						}
						//valor adicional en este caso color
						
							if(count($camne['ADICIONAL'])==1 AND $i==0)
							{
								$adicional[$ind]=$camne['ADICIONAL'][$i];
							}
							else
							{
								//bucle
								if(!empty($camne['ADICIONAL'][$i]))
								{
									//es mas de un campo
									$con_in = count($camne['ADICIONAL'][$i]);
									for($j=0;$j<$con_in;$j++)
									{
										$adicional[$ind][$j]=$camne['ADICIONAL'][$i][$j];
										//echo ' pp '.$adicional[$ind][$j];
									}
								}
							}
						
						
						//signo de comparacion
						if(!is_array($camne['SIGNO'][$i]))
						{
							if(count($camne['SIGNO'])==1 AND $i==0)
							{
								$signo[$ind]=$camne['SIGNO'][$i];
							}
							else
							{
								//bucle
								if(!empty($camne['SIGNO'][$i]))
								{
									$signo[$ind]=$camne['SIGNO'][$i];
								}
							}
						}
						else
						{
							//es mas de un campo
							$con_in = count($camne['SIGNO'][$i]);
							for($j=0;$j<$con_in;$j++)
							{
								$signo[$ind][$j]=$camne['SIGNO'][$i][$j];
								//echo ' pp '.$signo[$ind][$j];
							}
						}
						$ind++;
					}
				}
			}
			$i=0;
			while ($row = $stmt->fetch_row()) 
			{
				//comparamos con los valores de los array para personalizar las celdas
				//para titulo color fila
				$cfila1='';
				$cfila2='';
				//indentar
				$inden='';
				$indencam=array();
				$indencam1=array();
				//contador para caso indentar
				$conin=0;
				//contador caso para saber si cumple varias condiciones ejemplo italica TC=P OR TC=C
				$ca_it=0;
				//variable para colocar italica
				$ita1='';
				$ita2='';
				//contador para caso italicas
				$conita=0;
				//valores de campo a afectar
				$itacam1=array();
				//variables para subrayar
				//valores de campo a afectar en caso subrayar
				$subcam1=array();
				//contador caso subrayar
				$consub=0;
				//contador caso para saber si cumple varias condiciones ejemplo subrayar TC=P OR TC=C
				$ca_sub=0;
				//variable para colocar subrayar
				$sub1='';
				$sub2='';
				for($i=0;$i<$ind;$i++)
				{
					if($tit[$i]=='color_fila')
					{
						if(!is_array($campoe[$i]))
						{
							//campo a comparar
							$tin=$campoe[$i];
							//comparamos valor
							if($signo[$i]=='=')
							{
								if($row[$tin]==$campov[$i])
								{
									if($adicional[$i]=='black')
									{
										//activa condicion
										$cfila1='<B>';
										$cfila2='</B>';
									}
								}
							}
						}
					}
					if($tit[$i]=='indentar')
					{	
						if(!is_array($campoe[$i]))
						{
							//campo a comparar
							$tin=$campoe[$i];
							//comparamos valor
							if($signo[$i]=='=')
							{
								if($campov[$i]=='contar')
								{
									$inden1 = explode(".", $row[$tin]);
									//echo ' '.count($inden1);
									//hacemos los espacios
									//$inden=str_repeat("&nbsp;&nbsp;", count($inden1));
									if(count($inden1)>1)
									{
										$indencam1[$conin]=str_repeat("    ", (count($inden1)-1));
									}
									else
									{
										$indencam1[$conin]="";
									}
								}
								$indencam[$conin]=$campoaf[$i];
								//echo $indencam[$conin].' dd ';
								$conin++;
							}
						}
					}
					if($tit[$i]=='italica')
					{	
						if(!is_array($campoe[$i]))
						{
							
						}
						else
						{
							//es mas de un campo
							$con_in = count($campoe[$i]);
							$ca_it=0;
							for($j=0;$j<$con_in;$j++)
							{
								$tin=$campoe[$i][$j];
								//echo ' pp '.$tin[$i][$j];
								//comparamos valor
								if($signo[$i][$j]=='=')
								{
									//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
									if($row[$tin]==$campov[$i][$j])
									{
										$ca_it++;
									}
								}
								//si es diferente
								if($signo[$i][$j]=='<>')
								{
									//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
									if($row[$tin]<>$campov[$i][$j])
									{
										$ca_it++;
									}
								}
								
							}
							$con_in = count($campoaf[$i]);
							for($j=0;$j<$con_in;$j++)
							{
								$itacam1[$conita]=$campoaf[$i][$j];
								//echo $itacam1[$conita].' ';
								$conita++;
							}
							//echo $ca_it.' cdcd '.count($campoe[$i]).'<br/>';
							if($ca_it==count($campoe[$i]))
							{
								$ita1='<em>';
								$ita2='</em>';
							}
							else
							{
								$ita1='';
								$ita2='';
							}
						}
						
					}
					if($tit[$i]=='subrayar')
					{	
						if(!is_array($campoe[$i]))
						{
							
						}
						else
						{
							//es mas de un campo
							$con_in = count($campoe[$i]);
							$ca_sub=0;
							$ca_sub1=0;
							for($j=0;$j<$con_in;$j++)
							{
								$tin=$campoe[$i][$j];
								//echo ' pp '.$tin[$i][$j];
								//comparamos valor
								if($signo[$i][$j]=='=')
								{
									//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
									if($row[$tin]==$campov[$i][$j])
									{
										$ca_sub++;
										$ca_sub1++;
									}
								}
								//si es diferente
								if($signo[$i][$j]=='<>')
								{
									//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
									if($row[$tin]<>$campov[$i][$j])
									{
										$ca_sub++;
									}
								}
								
							}
							$con_in = count($campoaf[$i]);
							for($j=0;$j<$con_in;$j++)
							{
								$subcam1[$consub]=$campoaf[$i][$j];
								//echo $subcam1[$consub].' ';
								$consub++;
							}
							//echo $ca_it.' cdcd '.count($campoe[$i]).'<br/>';
							$sub1='';
							$sub2='';
							//condicion para verificar si signo es "=" o no
							if($ca_sub1==0)
							{
								//condicion en caso de distintos
								if($ca_sub==count($campoe[$i]))
								{
									$sub1='<u>';
									$sub2='</u>';
								}
								else
								{
									$sub1='';
									$sub2='';
								}
							}
							else
							{
								$sub1='<u>';
								$sub2='</u>';
							}
						}
					}
				}
				$je='A';
				for($i=0;$i<$cant;$i++)
				{
					//caso indentar
					for($j=0;$j<count($indencam);$j++)
					{
						if($indencam[$j]==$i)
						{
							$inden=$indencam1[$j];
						}
						else
						{
							$inden='';
						}
					}
					//caso italica
					$ita3="";
					$ita4="";
					//letra
					$ita5="Calibri";
					for($j=0;$j<count($itacam1);$j++)
					{
						//echo $itacam1[$j].' ssscc '.$i;
						if($itacam1[$j]==$i)
						{
							$ita3=$ita1;
							$ita4=$ita2;
							if($ita3=='<em>')
							{
								$ita5="Arial Narrow";
							}
						}
						
					}
					//caso subrayado
					$sub3="";
					$sub4="";
					for($j=0;$j<count($subcam1);$j++)
					{
						//echo $itacam1[$j].' ssscc '.$i;
						if($subcam1[$j]==$i)
						{
							$sub3=$sub1;
							$sub4=$sub2;
						}
						
					}
					//caso de campos fechas
					for($j=0;$j<count($cam_fech);$j++)
					{
						//echo $itacam1[$j].' ssscc '.$i;
						if($cam_fech[$j]==$i)
						{
							//$row[$i]=$row[$i]->format('Y-m-d H:i:s');
							$row[$i]=$row[$i]->format('Y-m-d');
						}
						
					}
					//echo "<br/>";
					//formateamos texto si es decimal
					if($tipo_campo[$i]=="style='text-align: right;'")
					{
						//si es cero colocar -
						
						if(number_format($row[$i],2, '.', ',')==0 OR number_format($row[$i],2, '.', ',')=='0,00')
						{
							if($sub3=='<u>')
							{
								$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
									[
											'font' => [
												'underline' => Font::UNDERLINE_SINGLE,
											],
										]
								);
							}
							if($cfila1=='<B>')
							{
								$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
									[
											'font' => [
												'bold' => true,
											],
										]
								);

							}
							//echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$sub3.$inden."-".$sub4.$ita4.$cfila2."</td>";
							$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $inden."-");
							$spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
						}
						else
						{
							//si es negativo colocar rojo
							if($row[$i]<0)
							{
								if($sub3=='<u>')
								{
									$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
										[
												'font' => [
													'underline' => Font::UNDERLINE_SINGLE,
												],
											]
									);
								}
								if($cfila1=='<B>')
								{
									$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
										[
												'font' => [
													'bold' => true,
												],
											]
									);

								}
								//reemplazo una parte de la cadena por otra
								$longitud_cad = strlen($tipo_campo[$i]); 
								$cam2 = substr_replace($tipo_campo[$i],"color: red;'",$longitud_cad-1,1); 
								//echo "<td ".$cam2." > ".$cfila1.$ita3.$inden.$sub3."".number_format($row[$i],2, ',', '.')."".$sub4.$ita4.$cfila2."</td>";
								$richText2 = new RichText();
								$red = $richText2->createTextRun(number_format($row[$i],2, '.', ','));
								$red->getFont()->setColor(new Color(Color::COLOR_RED));
								$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $inden.$richText2);
								$spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
								
							}
							else
							{
								if($sub3=='<u>')
								{
									$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
										[
												'font' => [
													'underline' => Font::UNDERLINE_SINGLE,
												],
											]
									);
								}
								if($cfila1=='<B>')
								{
									$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
										[
												'font' => [
													'bold' => true,
												],
											]
									);

								}
								//echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$inden.$sub3."".number_format($row[$i],2, ',', '.')."".$sub4.$ita4.$cfila2."</td>";
								$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $inden.number_format($row[$i],2, '.', ','));
								$spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
							}
						}
						
					}
					else
					{
						//echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$inden.$sub3."".$row[$i]."".$sub4.$ita4.$cfila2."</td>";
								$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
								[
										'alignment' => [
											'horizontal' => Alignment::HORIZONTAL_LEFT,
										],
										'font' => [
											'name' => $ita5,
											//'underline' => Font::UNDERLINE_SINGLE,
										],
									]
							);
						if($sub3=='<u>')
						{
							$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
								[
										'font' => [
											'underline' => Font::UNDERLINE_SINGLE,
										],
									]
							);
						}
						if($cfila1=='<B>')
						{
							$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
								[
										'font' => [
											'bold' => true,
											'name' => $ita5,
										],
									]
							);

						}
						$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $inden.$row[$i]);
						$spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
					}
					$je++;
				}
				$ie++;
			}
			/*//obtenemos los campos 
			foreach( sqlsrv_field_metadata( $stmt ) as $fieldMetadata ) 
			{
				foreach( $fieldMetadata as $name => $value) {
					if(!is_numeric($value))
					{
						if($value!='')
						{
							$cant++;
							//redimencionar
							$spreadsheet->getActiveSheet()->getColumnDimension($je1)
						->setAutoSize(true);
							$je1++;
						}
					}
				}
			}*/
		}
	}
	$spreadsheet->setActiveSheetIndex(0);

	$writer = new Xlsx($spreadsheet);

	if($ti == '' OR $ti==null)
	{
	$writer->save(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($_SESSION['INGRESO']['ti']).'.xlsx');
	//$writer->save('php://output');
	download_file(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($_SESSION['INGRESO']['ti']).".xlsx", trim($_SESSION['INGRESO']['ti']).".xlsx");
    }else
    {
    
		$writer->save(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($ti).'.xlsx');
		//$writer->save('php://output');
	    download_file(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($ti).".xlsx", trim($ti).".xlsx");

    }
}

function excel_file_libro_banco($re,$ti=null,$camne=null,$b=null,$base=null) 
{
	// print_r($sub);
 //  	   die();
	//header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
    header('Content-Disposition: attachment; filename='.trim($ti).".xlsx");
	//require_once __DIR__ . '/vendor/phpoffice/phpspreadsheet/src/Bootstrap.php';

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	//$sheet->setCellValue('A1', 'Hello World !');

	// Set document properties
	$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
		->setLastModifiedBy('Maarten Balliauw')
		->setTitle('Office 2007 XLSX Test Document')
		->setSubject('Office 2007 XLSX Test Document')
		->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
		->setKeywords('office 2007 openxml php')
		->setCategory('Test result file');

		//echo $base.' entrooo ';
		//para las celdas de excel
		$ie = 1;
		$je = 'B';
		//para sabar hasta donde unir celdas
		$je1=$je;
		//verificamos los campos
		//cantidad de campos
		$cant=0;
		//guardamos los campos
		$campo='';
		//obtenemos los campos 
		$cabecera = array('FECHA','TD','NUMERO','CHEQ/DEP','BENEFICIARIO','CONCEPTO','PARCIAL_ME','DEBE','HABER','SALDO');
		foreach( $cabecera as $fieldMetadata ) 
		{
		  $cant++;
		  $je1++;
		}
		//cabecera
		//diseño de nombres de campos
		//'horizontal' => Alignment::HORIZONTAL_RIGHT,
		//nos posicionamos 3 posiciones anteriores campo
		$je2='B';
		for($i=0;$i<($cant-3);$i++)
		{
			$je2++;
		}
		$je1=$je2;
		$je3=$je2;
		$je3++;
		$estilo_cabecera = array(
			
					'font' => [
						'bold' => true,
					],
					'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_CENTER,
					],
					'borders' => [
						'top' => [
							'borderStyle' => Border::BORDER_THIN,
						],
					],
					'fill' => [
						'fillType' => Fill::FILL_GRADIENT_LINEAR,
						'rotation' => 90,
						'startColor' => [
							/*'argb' => 'FFA0A0A0',*/
							'argb' => '0086c7',
						],
						'endColor' => [
							'argb' => 'FFFFFFFF',
						],
					],
				
		);
		$estilo_subcabecera = array(
			
					'font' => [
						'bold' => true,
					],
					'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_LEFT,
					],
					'borders' => [
						'top' => [
							'borderStyle' => Border::BORDER_THIN,
						],
					],
					'fill' => [
						'fillType' => Fill::FILL_GRADIENT_LINEAR,
						'rotation' => 90,
						'startColor' => [
							/*'argb' => 'FFA0A0A0',*/
							'argb' => '0086c7',
						],
						'endColor' => [
							'argb' => 'FFFFFFFF',
						],
					],
				
		);
		$spreadsheet->getActiveSheet()->getStyle('A2:'.$je3.'2')->applyFromArray($estilo_cabecera);
		//redimencionar
		//$spreadsheet->getActiveSheet()->getColumnDimension($je.''.$ie.':'.$je1.''.$ie)->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getStyle('B1')->applyFromArray(
			[
					'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_CENTER,
					],
					
			]
		);
		//logo empresa
		//echo __DIR__ ;
		////die();
		$drawing = new Drawing();
		$drawing->setName('Logo');
		$drawing->setDescription('Logo');
		//windows
		//$drawing->setPath(__DIR__ . '\logosMod.gif');
		//linux
		//$drawing->setPath(__DIR__ . '/logosMod.gif');
		if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
			//si es jpg
			$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
			if (@getimagesize($src)) 
			{ 
				$logo=$logo.'.jpg';
			}
			else
			{
				//si es gif
				$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
				if (@getimagesize($src)) 
				{ 
					$logo=$logo.'.gif';
				}
				else
				{
					//si es png
					$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
					if (@getimagesize($src)) 
					{ 
						$logo=$logo.'.png';
					}
					else
					{
						$logo="DEFAULT.jpg";
					}
				}
			}
		}
		else
		{
			$logo="DEFAULT.jpg";
		}
		/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		}
		else
		{
			$logo="DEFAULT";
		}*/
		$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo);
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates('A1');
		$drawing->setOffsetX(10);
		$drawing->setOffsetY(20);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//otro logo
		$drawing = new Drawing();
		$drawing->setName('Logo1');
		$drawing->setDescription('Logo1');
		//windows
		//$drawing->setPath(__DIR__ . '\logosMod.gif');
		//linux
		$drawing->setPath(__DIR__ . '/logosMod.gif');
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates($je3.'1');
		$drawing->setOffsetX(100);
		$drawing->setOffsetY(10);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//unir celdas
		$spreadsheet->getActiveSheet()->mergeCells($je.''.$ie.':'.$je2.''.$ie);
		if($_SESSION['INGRESO']['Razon_Social']!=$_SESSION['INGRESO']['noempr'])
		{
			$richText2 = new RichText();
			$richText2->createText($_SESSION['INGRESO']['noempr'].' ');

			$red = $richText2->createTextRun($_SESSION['INGRESO']['Razon_Social']);
			$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$spreadsheet->getActiveSheet()->setCellValue('B1', $richText2);
			//ampliar columna
			//$spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setWrapText(true);
		}
		else
		{
			$spreadsheet->getActiveSheet()->setCellValue('B1', $_SESSION['INGRESO']['noempr'].'');
		}

		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setName('Candara');
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setUnderline(Font::UNDERLINE_SINGLE);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('B1')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
		$spreadsheet->getActiveSheet()->getColumnDimension('B1')->setAutoSize(true);
		$je1++;

		$richText1 = new RichText();
		$richText1->createText(''."\n");

		
		$redf=$richText1->createTextRun("Hora: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("H:i")."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Fecha: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("d-m-Y") ."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Usuario: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$red = $richText1->createTextRun($_SESSION['INGRESO']['Nombre']);
		$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$red->getFont()->setSize(8);
			
		$spreadsheet->getActiveSheet()
			->getCell($je1.''.$ie)
			->setValue($richText1);


			//ampliar columna
		$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getAlignment()->setWrapText(true);
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, Date::PHPToExcel(gmmktime(0, 0, 0, date('m'), date('d'), date('Y'))));
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_XLSX15);
		$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		//$spreadsheet->getActiveSheet()->getColumnDimension($je1)->setAutoSize(true);

	    $spreadsheet->getActiveSheet()->getColumnDimension($je1)->setWidth(23);
	    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
        
		//$je1++;
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, '');
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->setFillType(Fill::FILL_SOLID);
		//$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('FF808080');

		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('0086c7');

		//si lleva o no border
		$bor='';
		if($b!=null and $b!='0')
		{
			$bor='table-bordered1';
			//style="border-top: 1px solid #bce8f1;"
		}
		//nombre de columnas
		//para las celdas de excel
		$ie = 2;
		$je = 'A';
		//cantidad campos
		$cant=0;
		//guardamos los campos
		$campo='';
		//tipo de campos
		$tipo_campo=array();
		//guardamos posicion de un campo ejemplo fecha
		$cam_fech=array();
		//contador para fechas
		$cont_fecha=0;
		//obtenemos los campos 
	$border = array(
		'borders' => [
			'allBorders' => [
				 	'borderStyle' => Border::BORDER_THIN,
				 		 ],
				 	 ],
		);
	$border_sub = array(
		
		'font' => [
						'italic' => true,
					],
		'borders' => [
			'allBorders' => [
				 	'borderStyle' => Border::BORDER_THIN,
				 		 ],
				 	 ],
		);
	$border_sub1 = array(
		'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_LEFT,
					],
		'font' => [
						'italic' => true,
					],
		'borders' => [
			'allBorders' => [
				 	'borderStyle' => Border::BORDER_THIN,
				 		 ],
				 	 ],
		);
	$border_sub2 = array(
		'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_RIGHT,
					],
		'font' => [
						'italic' => true,
					],
		'borders' => [
			'allBorders' => [
				 	'borderStyle' => Border::BORDER_THIN,
				 		 ],
				 	 ],
		);

	
			foreach($cabecera as $value) {
				
			$spreadsheet->getActiveSheet()->setCellValue($je.'4', $value);			
		    $spreadsheet->getActiveSheet()->getStyle('A4:'.$je3.'4')->applyFromArray($estilo_cabecera);
			// $spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
			$je++;
		}
		$ie = 5;
		$je = 'A';
        $debe=0; $haber=0;$saldo=0;
        $fecha ='';
		$count = 1;
		$mes = 0;

		
		foreach ($re as $key => $value) 
		{
			if($mes==0)
			{
				$mes = $value['Fecha']->format('n');
				if($fecha == '')
				{
					$fecha = $value['Fecha']->format('Y-m-d');
				}else
				{
					if($fecha == $value['Fecha']->format('Y-m-d'))
					{
						$fecha ='';
					}else
					{
						$fecha = $value['Fecha']->format('Y-m-d');
				
					}
				}
// $fecha,$value['TP'],$value['Numero'],$value['Cheq_Dep'],$value['Cliente'],$value['Concepto'],$value['Parcial_ME'],$value['Debe'],$value['Haber'],$value['Saldo']
			    $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, $fecha)->getColumnDimension('A')->setWidth(11);
			    $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, $value['TP'])->getColumnDimension('B')->setWidth(4);
			    $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, strval($value['Numero']))->getColumnDimension('C')->setWidth(11);
			    $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, strval($value['Cheq_Dep']))->getColumnDimension('D')->setWidth(11);
			    $spreadsheet->getActiveSheet()->setCellValue('E'.$ie, utf8_decode($value['Cliente']))->getColumnDimension('E')->setWidth(44);
			    $spreadsheet->getActiveSheet()->getStyle('E')->getAlignment()->setWrapText(true);
			    $spreadsheet->getActiveSheet()->setCellValue('F'.$ie, utf8_decode($value['Concepto']))->getColumnDimension('F')->setWidth(44);
			    $spreadsheet->getActiveSheet()->getStyle('F')->getAlignment()->setWrapText(true);
			    $spreadsheet->getActiveSheet()->setCellValue('G'.$ie,strval($value['Parcial_ME']))->getColumnDimension('G')->setWidth(23);
			    $spreadsheet->getActiveSheet()->setCellValue('H'.$ie,strval($value['Debe']))->getColumnDimension('H')->setWidth(23);			
			    $spreadsheet->getActiveSheet()->setCellValue('I'.$ie,strval($value['Haber']))->getColumnDimension('I')->setWidth(23);
			    $spreadsheet->getActiveSheet()->setCellValue('J'.$ie,strval($value['Saldo']))->getColumnDimension('J')->setWidth(23);			    
			    $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':J'.$ie)->applyFromArray($border);
			     $ie = $ie+1;				
			    
			     $fecha = $value['Fecha']->format('Y-m-d');
			     $debe = floatval($value['Debe']) + $debe;
			     $haber =floatval($value['Haber'])+ $haber;
			     $saldo =floatval($value['Saldo']);

			  }else
			  {
			  	if($mes == $value['Fecha']->format('n'))
			  	{
			  		if($fecha == '')
				        {
					       $fecha = $value['Fecha']->format('Y-m-d');
				        }else
				        {
					      if($fecha == $value['Fecha']->format('Y-m-d'))
					      {
						      $fecha ='';
					      }else
					      {
						      $fecha = $value['Fecha']->format('Y-m-d');
				
					      }
				        }

			            $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, $fecha)->getColumnDimension('A')->setWidth(11);
			    $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, $value['TP'])->getColumnDimension('B')->setWidth(4);
			    $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, strval($value['Numero']))->getColumnDimension('C')->setWidth(11);
			    $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, strval($value['Cheq_Dep']))->getColumnDimension('D')->setWidth(11);
			    $spreadsheet->getActiveSheet()->setCellValue('E'.$ie, utf8_decode($value['Cliente']))->getColumnDimension('E')->setWidth(44);
			    $spreadsheet->getActiveSheet()->getStyle('E')->getAlignment()->setWrapText(true);
			    $spreadsheet->getActiveSheet()->setCellValue('F'.$ie, utf8_decode($value['Concepto']))->getColumnDimension('F')->setWidth(44);
			    $spreadsheet->getActiveSheet()->getStyle('F')->getAlignment()->setWrapText(true);
			    $spreadsheet->getActiveSheet()->setCellValue('G'.$ie,strval($value['Parcial_ME']))->getColumnDimension('G')->setWidth(23);
			    $spreadsheet->getActiveSheet()->setCellValue('H'.$ie,strval($value['Debe']))->getColumnDimension('H')->setWidth(23);			
			    $spreadsheet->getActiveSheet()->setCellValue('I'.$ie,strval($value['Haber']))->getColumnDimension('I')->setWidth(23);
			    $spreadsheet->getActiveSheet()->setCellValue('J'.$ie,strval($value['Saldo']))->getColumnDimension('J')->setWidth(23);			    
			    $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':J'.$ie)->applyFromArray($border);
			      $ie = $ie+1;			
			          
			             $fecha = $value['Fecha']->format('Y-m-d');
			             $debe = floatval($value['Debe']) + $debe;
			             $haber =floatval($value['Haber'])+ $haber;
			             $saldo =floatval($value['Saldo']);

			  	}else
			  	{
			  	$spreadsheet->getActiveSheet()->setCellValue('A'.$ie, 'Fin de: '.mes_X_nombre($mes))->getColumnDimension('A')->setWidth(11);
		        $spreadsheet->getActiveSheet()->mergeCells('A'.$ie.':D'.$ie);		
			    $spreadsheet->getActiveSheet()->setCellValue('E'.$ie,'')->getColumnDimension('C')->setWidth(11);
			    $spreadsheet->getActiveSheet()->setCellValue('F'.$ie, 'Totales')->getColumnDimension('D')->setWidth(44);
			    $spreadsheet->getActiveSheet()->setCellValue('G'.$ie,'')->getColumnDimension('E')->setWidth(23);
			    $spreadsheet->getActiveSheet()->setCellValue('H'.$ie, strval($debe))->getColumnDimension('F')->setWidth(23);			
			    $spreadsheet->getActiveSheet()->setCellValue('I'.$ie,strval($haber))->getColumnDimension('G')->setWidth(23);
			    $spreadsheet->getActiveSheet()->setCellValue('J'.$ie,strval($saldo))->getColumnDimension('H')->setWidth(23);
			    $spreadsheet->getActiveSheet()->getStyle('G'.$ie.':J'.$ie)->applyFromArray($border_sub2);	
		        $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':J'.$ie)->applyFromArray($estilo_subcabecera);		
		         $ie = $ie+1;

		         	$spreadsheet->getActiveSheet()->setCellValue('A'.$ie, 'Inicio de:'.mes_X_nombre($mes));
		            $spreadsheet->getActiveSheet()->mergeCells('A'.$ie.':J'.$ie);				            
		            $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':J'.$ie)->applyFromArray($estilo_subcabecera);

		           $ie = $ie+1;


		         $debe=0; $haber=0;$saldo=0;
			  			$mes = $value['Fecha']->format('n');
				        if($fecha == '')
				          {
					          $fecha = $value['Fecha']->format('Y-m-d');
				          }else
				          {
					         if($fecha == $value['Fecha']->format('Y-m-d'))
					         {
						         $fecha ='';
					         }else
					         {
						         $fecha = $value['Fecha']->format('Y-m-d');
				
					         }
				          }

			    $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, $fecha)->getColumnDimension('A')->setWidth(11);
			    $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, $value['TP'])->getColumnDimension('B')->setWidth(4);
			    $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, strval($value['Numero']))->getColumnDimension('C')->setWidth(11);
			    $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, strval($value['Cheq_Dep']))->getColumnDimension('D')->setWidth(11);
			    $spreadsheet->getActiveSheet()->setCellValue('E'.$ie, utf8_decode($value['Cliente']))->getColumnDimension('E')->setWidth(44);
			    $spreadsheet->getActiveSheet()->getStyle('E')->getAlignment()->setWrapText(true);
			    $spreadsheet->getActiveSheet()->setCellValue('F'.$ie, utf8_decode($value['Concepto']))->getColumnDimension('F')->setWidth(44);
			    $spreadsheet->getActiveSheet()->getStyle('F')->getAlignment()->setWrapText(true);
			    $spreadsheet->getActiveSheet()->setCellValue('G'.$ie,strval($value['Parcial_ME']))->getColumnDimension('G')->setWidth(23);
			    $spreadsheet->getActiveSheet()->setCellValue('H'.$ie,strval($value['Debe']))->getColumnDimension('H')->setWidth(23);			
			    $spreadsheet->getActiveSheet()->setCellValue('I'.$ie,strval($value['Haber']))->getColumnDimension('I')->setWidth(23);
			    $spreadsheet->getActiveSheet()->setCellValue('J'.$ie,strval($value['Saldo']))->getColumnDimension('J')->setWidth(23);			    
			    $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':J'.$ie)->applyFromArray($border);
			     $ie = $ie+1;	
			     $fecha = $value['Fecha']->format('Y-m-d'); 

			             $debe = floatval($value['Debe']) + $debe;
			             $haber =floatval($value['Haber'])+ $haber;
			             $saldo =floatval($value['Saldo']);       

			  	}


			  }

		}
	
	$spreadsheet->setActiveSheetIndex(0);


	$writer = new Xlsx($spreadsheet);

	if($ti == '' OR $ti==null)
	{
	$writer->save(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($_SESSION['INGRESO']['ti']).'.xlsx');
	//$writer->save('php://output');
	download_file(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($_SESSION['INGRESO']['ti']).".xlsx", trim($_SESSION['INGRESO']['ti']).".xlsx");
    }else
    {
    
		$writer->save(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($ti).'.xlsx');
		//$writer->save('php://output');
	    download_file(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($ti).".xlsx", trim($ti).".xlsx");

    }
}

function excel_file_mayor_auxi($re,$sub,$ti=null,$camne=null,$b=null,$base=null) 
{
	// print_r($sub);
 //  	   die();
	//header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
    header('Content-Disposition: attachment; filename='.trim($ti).".xlsx");
	//require_once __DIR__ . '/vendor/phpoffice/phpspreadsheet/src/Bootstrap.php';

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	//$sheet->setCellValue('A1', 'Hello World !');

	// Set document properties
	$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
		->setLastModifiedBy('Maarten Balliauw')
		->setTitle('Office 2007 XLSX Test Document')
		->setSubject('Office 2007 XLSX Test Document')
		->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
		->setKeywords('office 2007 openxml php')
		->setCategory('Test result file');

		//echo $base.' entrooo ';
		//para las celdas de excel
		$ie = 1;
		$je = 'B';
		//para sabar hasta donde unir celdas
		$je1=$je;
		//verificamos los campos
		//cantidad de campos
		$cant=0;
		//guardamos los campos
		$campo='';
		//obtenemos los campos 
		$cabecera = array('FECHA','TD','NUMERO','CONCEPTO','PARCIAL_ME','DEBE','HABER','SALDO');
		foreach( $cabecera as $fieldMetadata ) 
		{
		  $cant++;
		  $je1++;
		}
		//cabecera
		//diseño de nombres de campos
		//'horizontal' => Alignment::HORIZONTAL_RIGHT,
		//nos posicionamos 3 posiciones anteriores campo
		$je2='B';
		for($i=0;$i<($cant-3);$i++)
		{
			$je2++;
		}
		$je1=$je2;
		$je3=$je2;
		$je3++;
			$estilo_subcabecera = array('font' => ['bold' => true,],
								'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT,],
								'borders' => ['top' => ['borderStyle' => Border::BORDER_THIN,],],
								'fill' => ['fillType' => Fill::FILL_GRADIENT_LINEAR,'rotation' => 90,'startColor' => ['argb' => '0086c7',],
								'endColor' => ['argb' => 'FFFFFFFF',],],
								);
		$estilo_cabecera = array(
			
					'font' => [
						'bold' => true,
					],
					'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_CENTER,
					],
					'borders' => [
						'top' => [
							'borderStyle' => Border::BORDER_THIN,
						],
					],
					'fill' => [
						'fillType' => Fill::FILL_GRADIENT_LINEAR,
						'rotation' => 90,
						'startColor' => [
							/*'argb' => 'FFA0A0A0',*/
							'argb' => '0086c7',
						],
						'endColor' => [
							'argb' => 'FFFFFFFF',
						],
					],
				
		);
		
		$spreadsheet->getActiveSheet()->getStyle('A2:'.$je3.'2')->applyFromArray($estilo_cabecera);
		//redimencionar
		//$spreadsheet->getActiveSheet()->getColumnDimension($je.''.$ie.':'.$je1.''.$ie)->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getStyle('B1')->applyFromArray(
			[
					'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_CENTER,
					],
					
			]
		);
		//logo empresa
		//echo __DIR__ ;
		////die();
		$drawing = new Drawing();
		$drawing->setName('Logo');
		$drawing->setDescription('Logo');
		//windows
		//$drawing->setPath(__DIR__ . '\logosMod.gif');
		//linux
		//$drawing->setPath(__DIR__ . '/logosMod.gif');
		if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
			//si es jpg
			$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
			if (@getimagesize($src)) 
			{ 
				$logo=$logo.'.jpg';
			}
			else
			{
				//si es gif
				$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
				if (@getimagesize($src)) 
				{ 
					$logo=$logo.'.gif';
				}
				else
				{
					//si es png
					$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
					if (@getimagesize($src)) 
					{ 
						$logo=$logo.'.png';
					}
					else
					{
						$logo="DEFAULT.jpg";
					}
				}
			}
		}
		else
		{
			$logo="DEFAULT.jpg";
		}
		/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		}
		else
		{
			$logo="DEFAULT";
		}*/
		$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo);
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates('A1');
		$drawing->setOffsetX(10);
		$drawing->setOffsetY(20);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//otro logo
		$drawing = new Drawing();
		$drawing->setName('Logo1');
		$drawing->setDescription('Logo1');
		//windows
		//$drawing->setPath(__DIR__ . '\logosMod.gif');
		//linux
		$drawing->setPath(__DIR__ . '/logosMod.gif');
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates($je3.'1');
		$drawing->setOffsetX(100);
		$drawing->setOffsetY(10);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//unir celdas
		$spreadsheet->getActiveSheet()->mergeCells($je.''.$ie.':'.$je2.''.$ie);
		if($_SESSION['INGRESO']['Razon_Social']!=$_SESSION['INGRESO']['noempr'])
		{
			$richText2 = new RichText();
			$richText2->createText($_SESSION['INGRESO']['noempr'].' ');

			$red = $richText2->createTextRun($_SESSION['INGRESO']['Razon_Social']);
			$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$spreadsheet->getActiveSheet()->setCellValue('B1', $richText2);
			//ampliar columna
			//$spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setWrapText(true);
		}
		else
		{
			$spreadsheet->getActiveSheet()->setCellValue('B1', $_SESSION['INGRESO']['noempr'].'');
		}

		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setName('Candara');
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setUnderline(Font::UNDERLINE_SINGLE);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('B1')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
		$spreadsheet->getActiveSheet()->getColumnDimension('B1')->setAutoSize(true);
		$je1++;

		$richText1 = new RichText();
		$richText1->createText(''."\n");

		
		$redf=$richText1->createTextRun("Hora: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("H:i")."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Fecha: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("d-m-Y") ."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Usuario: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$red = $richText1->createTextRun($_SESSION['INGRESO']['Nombre']);
		$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$red->getFont()->setSize(8);
			
		$spreadsheet->getActiveSheet()
			->getCell($je1.''.$ie)
			->setValue($richText1);


			//ampliar columna
		$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getAlignment()->setWrapText(true);
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, Date::PHPToExcel(gmmktime(0, 0, 0, date('m'), date('d'), date('Y'))));
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_XLSX15);
		$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		//$spreadsheet->getActiveSheet()->getColumnDimension($je1)->setAutoSize(true);

	    $spreadsheet->getActiveSheet()->getColumnDimension($je1)->setWidth(23);
	    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
        
		//$je1++;
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, '');
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->setFillType(Fill::FILL_SOLID);
		//$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('FF808080');

		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('0086c7');

		//si lleva o no border
		$bor='';
		if($b!=null and $b!='0')
		{
			$bor='table-bordered1';
			//style="border-top: 1px solid #bce8f1;"
		}
		//nombre de columnas
		//para las celdas de excel
		$ie = 2;
		$je = 'A';
		//cantidad campos
		$cant=0;
		//guardamos los campos
		$campo='';
		//tipo de campos
		$tipo_campo=array();
		//guardamos posicion de un campo ejemplo fecha
		$cam_fech=array();
		//contador para fechas
		$cont_fecha=0;
		//obtenemos los campos 
	$border = array(
		'borders' => [
			'allBorders' => [
				 	'borderStyle' => Border::BORDER_THIN,
				 		 ],
				 	 ],
		);
	$border_sub = array(
		
		'font' => [
						'italic' => true,
					],
		'borders' => [
			'allBorders' => [
				 	'borderStyle' => Border::BORDER_THIN,
				 		 ],
				 	 ],
		);
	$border_sub1 = array(
		'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_LEFT,
					],
		'font' => [
						'italic' => true,
					],
		'borders' => [
			'allBorders' => [
				 	'borderStyle' => Border::BORDER_THIN,
				 		 ],
				 	 ],
		);
	$border_sub2 = array(
		'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_RIGHT,
					],
		'font' => [
						'italic' => true,
					],
		'borders' => [
			'allBorders' => [
				 	'borderStyle' => Border::BORDER_THIN,
				 		 ],
				 	 ],
		);

	
			foreach($cabecera as $value) {
				
			$spreadsheet->getActiveSheet()->setCellValue($je.'4', $value);			
		    $spreadsheet->getActiveSheet()->getStyle('A4:'.$je3.'4')->applyFromArray($estilo_cabecera);
			// $spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
			$je++;
		}
		$ie = 5;
		$je = 'A';
        $debe=0; $haber=0;$saldo=0;
        $fecha ='';
		$count = 1;
		$mes = 0;

		
		foreach ($re as $key => $value) 
		{
			if($mes==0)
			{
				$mes = $value['Fecha']->format('n');
				if($fecha == '')
				{
					$fecha = $value['Fecha']->format('Y-m-d');
				}else
				{
					if($fecha == $value['Fecha']->format('Y-m-d'))
					{
						$fecha ='';
					}else
					{
						$fecha = $value['Fecha']->format('Y-m-d');
				
					}
				}

			    $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, $fecha)->getColumnDimension('A')->setWidth(11);
			    $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, $value['TP'])->getColumnDimension('B')->setWidth(4);
			    $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, strval($value['Numero']))->getColumnDimension('C')->setWidth(11);
			    $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, utf8_decode($value['Cliente']))->getColumnDimension('D')->setWidth(44);
			    $spreadsheet->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true);
			    $spreadsheet->getActiveSheet()->setCellValue('E'.$ie,'')->getColumnDimension('E')->setWidth(23);
			    $spreadsheet->getActiveSheet()->setCellValue('F'.$ie,'')->getColumnDimension('F')->setWidth(23);			
			    $spreadsheet->getActiveSheet()->setCellValue('G'.$ie,'')->getColumnDimension('G')->setWidth(23);
			    $spreadsheet->getActiveSheet()->setCellValue('H'.$ie,'')->getColumnDimension('H')->setWidth(23);			    
			    $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':H'.$ie)->applyFromArray($border);
			     $ie = $ie+1;				

			    $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, '')->getColumnDimension('A')->setWidth(11);
			    $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, '')->getColumnDimension('B')->setWidth(4);
			    $spreadsheet->getActiveSheet()->setCellValue('C'.$ie,'')->getColumnDimension('C')->setWidth(11);
			    $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, $value['Concepto'])->getColumnDimension('D')->setWidth(44);
			    $spreadsheet->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true);
			    $spreadsheet->getActiveSheet()->setCellValue('E'.$ie,strval($value['Parcial_ME']))->getColumnDimension('E')->setWidth(23);
			    $spreadsheet->getActiveSheet()->setCellValue('F'.$ie,strval($value['Debe']))->getColumnDimension('F')->setWidth(23);			
			    $spreadsheet->getActiveSheet()->setCellValue('G'.$ie,strval($value['Haber']))->getColumnDimension('G')->setWidth(23);
			    $spreadsheet->getActiveSheet()->setCellValue('H'.$ie,strval($value['Saldo']))->getColumnDimension('H')->setWidth(23);
			    $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':H'.$ie)->applyFromArray($border);	
			     $ie = $ie+1;
			     foreach ($sub as $key => $value1) {
			     	if($value1['Numero'] == $value['Numero'])
		        	{
		        		if($value1['Debitos'] == '.0000')
		        		{
		        			$p='R';
		        			$parcial = $value1['Creditos'];

			               $spreadsheet->getActiveSheet()->getStyle('E'.$ie)->applyFromArray($border_sub2);	
		        		}else
		        		{
		        			$p='L';
		        			$parcial = $value1['Debitos'];

			               $spreadsheet->getActiveSheet()->getStyle('E'.$ie)->applyFromArray($border_sub1);	
		        		}
		                   $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, '')->getColumnDimension('A')->setWidth(11);
			               $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, '')->getColumnDimension('B')->setWidth(4);
			               $spreadsheet->getActiveSheet()->setCellValue('C'.$ie,'')->getColumnDimension('C')->setWidth(11);
			               $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, '*'.$value1['Cliente'])->getColumnDimension('D')->setWidth(44);
			               $spreadsheet->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true);
			               $spreadsheet->getActiveSheet()->setCellValue('E'.$ie,strval($parcial))->getColumnDimension('E')->setWidth(23);
			               $spreadsheet->getActiveSheet()->setCellValue('F'.$ie,'')->getColumnDimension('F')->setWidth(23);			
			               $spreadsheet->getActiveSheet()->setCellValue('G'.$ie,'')->getColumnDimension('G')->setWidth(23);
			               $spreadsheet->getActiveSheet()->setCellValue('H'.$ie,'')->getColumnDimension('H')->setWidth(23);
			               $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':H'.$ie)->applyFromArray($border_sub);	
		                 $ie = $ie+1;
		        	}
			     }
			     $fecha = $value['Fecha']->format('Y-m-d');
			     $debe = floatval($value['Debe']) + $debe;
			     $haber =floatval($value['Haber'])+ $haber;
			     $saldo =floatval($value['Saldo'])+ $saldo;

			  }else
			  {
			  	if($mes == $value['Fecha']->format('n'))
			  	{
			  		if($fecha == '')
				        {
					       $fecha = $value['Fecha']->format('Y-m-d');
				        }else
				        {
					      if($fecha == $value['Fecha']->format('Y-m-d'))
					      {
						      $fecha ='';
					      }else
					      {
						      $fecha = $value['Fecha']->format('Y-m-d');
				
					      }
				        }

			            $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, $fecha)->getColumnDimension('A')->setWidth(11);
			            $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, $value['TP'])->getColumnDimension('B')->setWidth(4);
			            $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, strval($value['Numero']))->getColumnDimension('C')->setWidth(11);
			            $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, utf8_decode($value['Cliente']))->getColumnDimension('D')->setWidth(44);
			            $spreadsheet->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true);
			            $spreadsheet->getActiveSheet()->setCellValue('E'.$ie,'')->getColumnDimension('E')->setWidth(23);
			            $spreadsheet->getActiveSheet()->setCellValue('F'.$ie,'')->getColumnDimension('F')->setWidth(23);			
			            $spreadsheet->getActiveSheet()->setCellValue('G'.$ie,'')->getColumnDimension('G')->setWidth(23);
			            $spreadsheet->getActiveSheet()->setCellValue('H'.$ie,'')->getColumnDimension('H')->setWidth(23);			    
			            $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':H'.$ie)->applyFromArray($border);
			             $ie = $ie+1;				

			            $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, '')->getColumnDimension('A')->setWidth(11);
			            $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, '')->getColumnDimension('B')->setWidth(4);
			            $spreadsheet->getActiveSheet()->setCellValue('C'.$ie,'')->getColumnDimension('C')->setWidth(11);
			            $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, $value['Concepto'])->getColumnDimension('D')->setWidth(44);
			            $spreadsheet->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true);
			            $spreadsheet->getActiveSheet()->setCellValue('E'.$ie,strval($value['Parcial_ME']))->getColumnDimension('E')->setWidth(23);
			            $spreadsheet->getActiveSheet()->setCellValue('F'.$ie,strval($value['Debe']))->getColumnDimension('F')->setWidth(23);			
			            $spreadsheet->getActiveSheet()->setCellValue('G'.$ie,strval($value['Haber']))->getColumnDimension('G')->setWidth(23);
			            $spreadsheet->getActiveSheet()->setCellValue('H'.$ie,strval($value['Saldo']))->getColumnDimension('H')->setWidth(23);
			            $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':H'.$ie)->applyFromArray($border);	
			             $ie = $ie+1;
			             foreach ($sub as $key => $value1) {
			     	if($value1['Numero'] == $value['Numero'])
		        	{
		        		if($value1['Debitos'] == '.0000')
		        		{
		        			$p='R';
		        			$parcial = $value1['Creditos'];

			               $spreadsheet->getActiveSheet()->getStyle('E'.$ie)->applyFromArray($border_sub2);	
		        		}else
		        		{
		        			$p='L';
		        			$parcial = $value1['Debitos'];

			               $spreadsheet->getActiveSheet()->getStyle('E'.$ie)->applyFromArray($border_sub1);	
		        		}
		                   $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, '')->getColumnDimension('A')->setWidth(11);
			               $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, '')->getColumnDimension('B')->setWidth(4);
			               $spreadsheet->getActiveSheet()->setCellValue('C'.$ie,'')->getColumnDimension('C')->setWidth(11);
			               $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, '*'.$value1['Cliente'])->getColumnDimension('D')->setWidth(44);
			               $spreadsheet->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true);
			               $spreadsheet->getActiveSheet()->setCellValue('E'.$ie,strval($parcial))->getColumnDimension('E')->setWidth(23);
			               $spreadsheet->getActiveSheet()->setCellValue('F'.$ie,'')->getColumnDimension('F')->setWidth(23);			
			               $spreadsheet->getActiveSheet()->setCellValue('G'.$ie,'')->getColumnDimension('G')->setWidth(23);
			               $spreadsheet->getActiveSheet()->setCellValue('H'.$ie,'')->getColumnDimension('H')->setWidth(23);
			               $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':H'.$ie)->applyFromArray($border_sub);	
		                 $ie = $ie+1;
		        	}
			     }
			             $fecha = $value['Fecha']->format('Y-m-d');
			             $debe = floatval($value['Debe']) + $debe;
			             $haber =floatval($value['Haber'])+ $haber;
			             $saldo =floatval($value['Saldo'])+ $saldo;

			  	}else
			  	{
			  	$spreadsheet->getActiveSheet()->setCellValue('A'.$ie, 'Fin de: '.mes_X_nombre($mes))->getColumnDimension('A')->setWidth(11);
		        $spreadsheet->getActiveSheet()->mergeCells('A'.$ie.':B'.$ie);		
			    $spreadsheet->getActiveSheet()->setCellValue('C'.$ie,'')->getColumnDimension('C')->setWidth(11);
			    $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, 'Totales')->getColumnDimension('D')->setWidth(44);
			    $spreadsheet->getActiveSheet()->setCellValue('E'.$ie,'')->getColumnDimension('E')->setWidth(23);
			    $spreadsheet->getActiveSheet()->setCellValue('F'.$ie, strval($debe))->getColumnDimension('F')->setWidth(23);			
			    $spreadsheet->getActiveSheet()->setCellValue('G'.$ie,strval($haber))->getColumnDimension('G')->setWidth(23);
			    $spreadsheet->getActiveSheet()->setCellValue('H'.$ie,strval($saldo))->getColumnDimension('H')->setWidth(23);
		        $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':H'.$ie)->applyFromArray($estilo_subcabecera);		
		         $ie = $ie+1;

		         	$spreadsheet->getActiveSheet()->setCellValue('A'.$ie, 'Inicio de:'.mes_X_nombre($mes));
		            $spreadsheet->getActiveSheet()->mergeCells('A'.$ie.':H'.$ie);				            
		            $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':H'.$ie)->applyFromArray($estilo_subcabecera);

		           $ie = $ie+1;


		         $debe=0; $haber=0;$saldo=0;
			  			$mes = $value['Fecha']->format('n');
		        if($fecha == '')
		          {
			          $fecha = $value['Fecha']->format('Y-m-d');
		          }else
		          {
			         if($fecha == $value['Fecha']->format('Y-m-d'))
			         {
				         $fecha ='';
			         }else
			         {
				         $fecha = $value['Fecha']->format('Y-m-d');
		
			         }
		          }

				    $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, $fecha)->getColumnDimension('A')->setWidth(11);
				    $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, $value['TP'])->getColumnDimension('B')->setWidth(4);
				    $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, strval($value['Numero']))->getColumnDimension('C')->setWidth(11);
				    $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, utf8_decode($value['Cliente']))->getColumnDimension('D')->setWidth(44);
				    $spreadsheet->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true);
				    $spreadsheet->getActiveSheet()->setCellValue('E'.$ie,'')->getColumnDimension('E')->setWidth(23);
				    $spreadsheet->getActiveSheet()->setCellValue('F'.$ie,'')->getColumnDimension('F')->setWidth(23);			
				    $spreadsheet->getActiveSheet()->setCellValue('G'.$ie,'')->getColumnDimension('G')->setWidth(23);
				    $spreadsheet->getActiveSheet()->setCellValue('H'.$ie,'')->getColumnDimension('H')->setWidth(23);			    
				    $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':H'.$ie)->applyFromArray($border);
				     $ie = $ie+1;				

				    $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, '')->getColumnDimension('A')->setWidth(11);
				    $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, '')->getColumnDimension('B')->setWidth(4);
				    $spreadsheet->getActiveSheet()->setCellValue('C'.$ie,'')->getColumnDimension('C')->setWidth(11);
				    $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, $value['Concepto'])->getColumnDimension('D')->setWidth(44);
				    $spreadsheet->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true);
				    $spreadsheet->getActiveSheet()->setCellValue('E'.$ie,strval($value['Parcial_ME']))->getColumnDimension('E')->setWidth(23);
				    $spreadsheet->getActiveSheet()->setCellValue('F'.$ie,strval($value['Debe']))->getColumnDimension('F')->setWidth(23);			
				    $spreadsheet->getActiveSheet()->setCellValue('G'.$ie,strval($value['Haber']))->getColumnDimension('G')->setWidth(23);
				    $spreadsheet->getActiveSheet()->setCellValue('H'.$ie,strval($value['Saldo']))->getColumnDimension('H')->setWidth(23);
				    $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':H'.$ie)->applyFromArray($border);	
				     $ie = $ie+1;
				     foreach ($sub as $key => $value1) {
				     	if($value1['Numero'] == $value['Numero'])
			        	{
			        		if($value1['Debitos'] == '.0000')
			        		{
			        			$p='R';
			        			$parcial = $value1['Creditos'];

				               $spreadsheet->getActiveSheet()->getStyle('E'.$ie)->applyFromArray($border_sub2);	
			        		}else
			        		{
			        			$p='L';
			        			$parcial = $value1['Debitos'];

				               $spreadsheet->getActiveSheet()->getStyle('E'.$ie)->applyFromArray($border_sub1);	
			        		}
			                   $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, '')->getColumnDimension('A')->setWidth(11);
				               $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, '')->getColumnDimension('B')->setWidth(4);
				               $spreadsheet->getActiveSheet()->setCellValue('C'.$ie,'')->getColumnDimension('C')->setWidth(11);
				               $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, '*'.$value1['Cliente'])->getColumnDimension('D')->setWidth(44);
				               $spreadsheet->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true);
				               $spreadsheet->getActiveSheet()->setCellValue('E'.$ie,strval($parcial))->getColumnDimension('E')->setWidth(23);
				               $spreadsheet->getActiveSheet()->setCellValue('F'.$ie,'')->getColumnDimension('F')->setWidth(23);			
				               $spreadsheet->getActiveSheet()->setCellValue('G'.$ie,'')->getColumnDimension('G')->setWidth(23);
				               $spreadsheet->getActiveSheet()->setCellValue('H'.$ie,'')->getColumnDimension('H')->setWidth(23);
				               $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':H'.$ie)->applyFromArray($border_sub);	
			                 $ie = $ie+1;
			        	}
				     }
			     $fecha = $value['Fecha']->format('Y-m-d'); 

			             $debe = floatval($value['Debe']) + $debe;
			             $haber =floatval($value['Haber'])+ $haber;
			             $saldo =floatval($value['Saldo'])+ $saldo;       

			  	}


			  }

		}
	
	$spreadsheet->setActiveSheetIndex(0);


	$writer = new Xlsx($spreadsheet);

	if($ti == '' OR $ti==null)
	{
	$writer->save(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($_SESSION['INGRESO']['ti']).'.xlsx');
	//$writer->save('php://output');
	download_file(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($_SESSION['INGRESO']['ti']).".xlsx", trim($_SESSION['INGRESO']['ti']).".xlsx");
    }else
    {
    
		$writer->save(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($ti).'.xlsx');
		//$writer->save('php://output');
	    download_file(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($ti).".xlsx", trim($ti).".xlsx");

    }
}

function excel_file_descargos($re=false,$ti=null,$camne=null,$b=null,$base=null) 
{

	//header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
    header('Content-Disposition: attachment; filename='.trim($ti).".xlsx");
	//require_once __DIR__ . '/vendor/phpoffice/phpspreadsheet/src/Bootstrap.php';

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	//$sheet->setCellValue('A1', 'Hello World !');

	// Set document properties
	$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
		->setLastModifiedBy('Maarten Balliauw')
		->setTitle('Office 2007 XLSX Test Document')
		->setSubject('Office 2007 XLSX Test Document')
		->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
		->setKeywords('office 2007 openxml php')
		->setCategory('Test result file');

		//echo $base.' entrooo ';
		//para las celdas de excel
		$ie = 1;
		$je = 'B';
		//para sabar hasta donde unir celdas
		$je1=$je;
		//verificamos los campos
		//cantidad de campos
		$cant=0;
		//guardamos los campos
		$campo='';
		//obtenemos los campos 
		$cabecera = array('NOMBRE','PROCEDIMIENTO','AREA','No. DESCARGO');
		foreach( $cabecera as $fieldMetadata ) 
		{
		  $cant++;
		  $je1++;
		}
		//cabecera
		//diseño de nombres de campos
		//'horizontal' => Alignment::HORIZONTAL_RIGHT,
		//nos posicionamos 3 posiciones anteriores campo
		$je2='B';
		for($i=0;$i<($cant-3);$i++)
		{
			$je2++;
		}
		$je1=$je2;
		$je3=$je2;
		$je3++;
		$estilo_cabecera = array(
			
					'font' => [
						'bold' => true,
					],
					'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_CENTER,
					],
					'borders' => [
						'top' => [
							'borderStyle' => Border::BORDER_THIN,
						],
					],
					'fill' => [
						'fillType' => Fill::FILL_GRADIENT_LINEAR,
						'rotation' => 90,
						'startColor' => [
							/*'argb' => 'FFA0A0A0',*/
							'argb' => '0086c7',
						],
						'endColor' => [
							'argb' => 'FFFFFFFF',
						],
					],
				
		);
		$spreadsheet->getActiveSheet()->getStyle('A2:'.$je3.'2')->applyFromArray($estilo_cabecera);
		//redimencionar
		//$spreadsheet->getActiveSheet()->getColumnDimension($je.''.$ie.':'.$je1.''.$ie)->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getStyle('B1')->applyFromArray(
			[
					'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_CENTER,
					],
					
			]
		);
		//logo empresa
		//echo __DIR__ ;
		////die();
		$drawing = new Drawing();
		$drawing->setName('Logo');
		$drawing->setDescription('Logo');
		//windows
		//$drawing->setPath(__DIR__ . '\logosMod.gif');
		//linux
		//$drawing->setPath(__DIR__ . '/logosMod.gif');
		if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
			//si es jpg
			$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
			if (@getimagesize($src)) 
			{ 
				$logo=$logo.'.jpg';
			}
			else
			{
				//si es gif
				$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
				if (@getimagesize($src)) 
				{ 
					$logo=$logo.'.gif';
				}
				else
				{
					//si es png
					$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
					if (@getimagesize($src)) 
					{ 
						$logo=$logo.'.png';
					}
					else
					{
						$logo="DEFAULT.jpg";
					}
				}
			}
		}
		else
		{
			$logo="DEFAULT.jpg";
		}
		/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		}
		else
		{
			$logo="DEFAULT";
		}*/
		$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo);
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates('A1');
		$drawing->setOffsetX(10);
		$drawing->setOffsetY(20);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//otro logo
		$drawing = new Drawing();
		$drawing->setName('Logo1');
		$drawing->setDescription('Logo1');
		//windows
		//$drawing->setPath(__DIR__ . '\logosMod.gif');
		//linux
		$drawing->setPath(__DIR__ . '/logosMod.gif');
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates($je3.'1');
		$drawing->setOffsetX(100);
		$drawing->setOffsetY(10);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//unir celdas
		$je2++;
		$spreadsheet->getActiveSheet()->mergeCells($je.''.$ie.':'.$je2.''.$ie);
		if($_SESSION['INGRESO']['Razon_Social']!=$_SESSION['INGRESO']['noempr'])
		{
			$richText2 = new RichText();
			$richText2->createText($_SESSION['INGRESO']['noempr'].' ');

			$red = $richText2->createTextRun($_SESSION['INGRESO']['Razon_Social']);
			$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$spreadsheet->getActiveSheet()->setCellValue('B1', $richText2);
			//ampliar columna
			//$spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setWrapText(true);
		}
		else
		{
			$spreadsheet->getActiveSheet()->setCellValue('B1', $_SESSION['INGRESO']['noempr'].'');
		}

		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setName('Candara');
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setUnderline(Font::UNDERLINE_SINGLE);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('B1')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
		$spreadsheet->getActiveSheet()->getColumnDimension('B1')->setAutoSize(true);
		$je1++;$je1++;

		$richText1 = new RichText();
		$richText1->createText(''."\n");

		
		$redf=$richText1->createTextRun("Hora: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("H:i")."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Fecha: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("d-m-Y") ."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Usuario: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$red = $richText1->createTextRun($_SESSION['INGRESO']['Nombre']);
		$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$red->getFont()->setSize(8);
			
		$spreadsheet->getActiveSheet()
			->getCell($je1.''.$ie)
			->setValue($richText1);
         
			//ampliar columna
		$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getAlignment()->setWrapText(true);
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, Date::PHPToExcel(gmmktime(0, 0, 0, date('m'), date('d'), date('Y'))));
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_XLSX15);
		$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		//$spreadsheet->getActiveSheet()->getColumnDimension($je1)->setAutoSize(true);

	    $spreadsheet->getActiveSheet()->getColumnDimension($je1)->setWidth(23);
	    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
        
		//$je1++;
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, '');
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->setFillType(Fill::FILL_SOLID);
		//$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('FF808080');

		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('0086c7');

		//si lleva o no border
		$bor='';
		if($b!=null and $b!='0')
		{
			$bor='table-bordered1';
			//style="border-top: 1px solid #bce8f1;"
		}
		//nombre de columnas
		//para las celdas de excel
		$ie = 2;
		$je = 'A';
		//cantidad campos
		$cant=0;
		//guardamos los campos
		$campo='';
		//tipo de campos
		$tipo_campo=array();
		//guardamos posicion de un campo ejemplo fecha
		$cam_fech=array();
		//contador para fechas
		$cont_fecha=0;
		//obtenemos los campos 
	$border = array(
		'borders' => [
			'allBorders' => [
				 	'borderStyle' => Border::BORDER_THIN,
				 		 ],
				 	 ],
		);

	
			// foreach($cabecera as $value) {
			// 	if($je=='B')
			// 	{
			// 		$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $value)->mergeCells('B'.$ie.':C'.$ie);	
			// 		$je++;
			// 	}else
			// 	{
			// 		$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $value);
			// 	}
			// $spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $value)->mergeCells('B'.$ie.':C'.$ie);	
			// $spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
		// 	$je++;
		// }
		$ie = 3;
		$je = 'A';
		$temp = '';
        $total_debe= 0;
		$total_haber = 0;
		$count = 1;

		//---------------------------------------------INICIO DE TODO------------------------------

	     $GRAN_TOTAL = 0;
		foreach ($re as $key => $value) {
			$spreadsheet->getActiveSheet()->setCellValue('A'.$ie, 'NOMBRE: '.$value['Nombre']);
			$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(45);
			$spreadsheet->getActiveSheet()->setCellValue('B'.$ie, 'PROCEDIMIENTO: '.$value['Procedimiento'])->mergeCells('B'.$ie.':C'.$ie);	
			$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(60);			
			$spreadsheet->getActiveSheet()->setCellValue('D'.$ie, 'AREA: '.$value['Area']);
			$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(23);
			$spreadsheet->getActiveSheet()->setCellValue('E'.$ie, 'NO. DESCARGO:'.$value['Descargo']);
			$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(23);					
		    $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':E'.$ie)->applyFromArray($estilo_cabecera);
			$ie+=1;
			$pos=0;
			foreach ($value['registros'][0] as $key2 => $value2) {
				
					$spreadsheet->getActiveSheet()->setCellValue('A'.$ie, 'Fecha de descargo: '.$key2);
			        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(50);
			        $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':E'.$ie)->applyFromArray($estilo_cabecera);
			        $ie+=1;
			         $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, 'CODIGO');
			         $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
					 $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, 'CANTIDAD');
			         $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(10);
					 $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, 'PRODUCTO');
			         $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(50);
					 $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, 'PRECIO UNI');
			         $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(50);
					 $spreadsheet->getActiveSheet()->setCellValue('E'.$ie, 'TOTAL');
			         $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);
			        $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':E'.$ie)->applyFromArray($estilo_cabecera);

			        $ie+=1;	
			        $TOTAL = 0;
			        foreach ($value2[0] as $key3 => $value3) {
					        $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, $value3['codigo']);
			                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
					        $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, $value3['cantidad']);
			                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(10);
					        $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, $value3['producto']);
			                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(50);
					        $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, strval($value3['pre_uni']));
			                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(50);
					        $spreadsheet->getActiveSheet()->setCellValue('E'.$ie, $value3['total']);
			                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);
			                $TOTAL+=$value3['total'];
			                $ie+=1;				        
			        }

					        $spreadsheet->getActiveSheet()->setCellValue('D'.$ie,'TOTAL');
			                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(25);
					        $spreadsheet->getActiveSheet()->setCellValue('E'.$ie, strval($TOTAL));
			                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);
			                $GRAN_TOTAL+=$TOTAL;
			        $ie+=2;
			}
			
		}
		//--------------------------------------------FIN DE TODO---------------------------------

		    $ie = $ie+1;
			$spreadsheet->getActiveSheet()->setCellValue('D'.$ie, 'GRAN TOTAL ');
			$spreadsheet->getActiveSheet()->setCellValue('E'.$ie,strval($GRAN_TOTAL));
			$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':E'.$ie)->applyFromArray($estilo_cabecera);

	
	$spreadsheet->setActiveSheetIndex(0);


	$writer = new Xlsx($spreadsheet);

	if($ti == '' OR $ti==null)
	{
	$writer->save(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($_SESSION['INGRESO']['ti']).'.xlsx');
	//$writer->save('php://output');
	download_file(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($_SESSION['INGRESO']['ti']).".xlsx", trim($_SESSION['INGRESO']['ti']).".xlsx");
    }else
    {
    
		$writer->save(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($ti).'.xlsx');
		//$writer->save('php://output');
	    download_file(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($ti).".xlsx", trim($ti).".xlsx");

    }
}

function excel_file_auditoria($re=false,$ti=null,$camne=null,$b=null,$base=null) 
{

	//header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
    header('Content-Disposition: attachment; filename='.trim($ti).".xlsx");
	//require_once __DIR__ . '/vendor/phpoffice/phpspreadsheet/src/Bootstrap.php';

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	//$sheet->setCellValue('A1', 'Hello World !');

	// Set document properties
	$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
		->setLastModifiedBy('Maarten Balliauw')
		->setTitle('Office 2007 XLSX Test Document')
		->setSubject('Office 2007 XLSX Test Document')
		->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
		->setKeywords('office 2007 openxml php')
		->setCategory('Test result file');

		//echo $base.' entrooo ';
		//para las celdas de excel
		$ie = 1;
		$je = 'B';
		//para sabar hasta donde unir celdas
		$je1=$je;
		//verificamos los campos
		//cantidad de campos
		$cant=0;
		//guardamos los campos
		$campo='';
		//obtenemos los campos 
		$cabecera = array('FECHA Y HORA','ENTIDAD','IP ACCESO','MODULO','TAREA REALIZADA','EMPRESA','USAURIO');
		foreach( $cabecera as $fieldMetadata ) 
		{
		  $cant++;
		  $je1++;
		}
		//cabecera
		//diseño de nombres de campos
		//'horizontal' => Alignment::HORIZONTAL_RIGHT,
		//nos posicionamos 3 posiciones anteriores campo
		$je2='B';
		for($i=0;$i<($cant-3);$i++)
		{
			$je2++;
		}
		$je1=$je2;
		$je3=$je2;
		$je3++;
		$estilo_cabecera = array(
			
					'font' => [
						'bold' => true,
					],
					'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_CENTER,
					],
					'borders' => [
						'top' => [
							'borderStyle' => Border::BORDER_THIN,
						],
					],
					'fill' => [
						'fillType' => Fill::FILL_GRADIENT_LINEAR,
						'rotation' => 90,
						'startColor' => [
							/*'argb' => 'FFA0A0A0',*/
							'argb' => '0086c7',
						],
						'endColor' => [
							'argb' => 'FFFFFFFF',
						],
					],
				
		);
		$spreadsheet->getActiveSheet()->getStyle('A2:'.$je3.'2')->applyFromArray($estilo_cabecera);
		//redimencionar
		//$spreadsheet->getActiveSheet()->getColumnDimension($je.''.$ie.':'.$je1.''.$ie)->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getStyle('B1')->applyFromArray(
			[
					'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_CENTER,
					],
					
			]
		);
		//logo empresa
		//echo __DIR__ ;
		////die();
		$drawing = new Drawing();
		$drawing->setName('Logo');
		$drawing->setDescription('Logo');
		//windows
		//$drawing->setPath(__DIR__ . '\logosMod.gif');
		//linux
		//$drawing->setPath(__DIR__ . '/logosMod.gif');
		if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
			//si es jpg
			$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
			if (@getimagesize($src)) 
			{ 
				$logo=$logo.'.jpg';
			}
			else
			{
				//si es gif
				$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
				if (@getimagesize($src)) 
				{ 
					$logo=$logo.'.gif';
				}
				else
				{
					//si es png
					$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
					if (@getimagesize($src)) 
					{ 
						$logo=$logo.'.png';
					}
					else
					{
						$logo="DEFAULT.jpg";
					}
				}
			}
		}
		else
		{
			$logo="DEFAULT.jpg";
		}
		/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		}
		else
		{
			$logo="DEFAULT";
		}*/
		$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo);
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates('A1');
		$drawing->setOffsetX(10);
		$drawing->setOffsetY(20);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//otro logo
		$drawing = new Drawing();
		$drawing->setName('Logo1');
		$drawing->setDescription('Logo1');
		//windows
		//$drawing->setPath(__DIR__ . '\logosMod.gif');
		//linux
		$drawing->setPath(__DIR__ . '/logosMod.gif');
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates($je3.'1');
		$drawing->setOffsetX(100);
		$drawing->setOffsetY(10);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//unir celdas
		$je2++;
		$spreadsheet->getActiveSheet()->mergeCells($je.''.$ie.':'.$je2.''.$ie);
		if($_SESSION['INGRESO']['Razon_Social']!=$_SESSION['INGRESO']['noempr'])
		{
			$richText2 = new RichText();
			$richText2->createText($_SESSION['INGRESO']['noempr'].' ');

			$red = $richText2->createTextRun($_SESSION['INGRESO']['Razon_Social']);
			$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$spreadsheet->getActiveSheet()->setCellValue('B1', $richText2);
			//ampliar columna
			//$spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setWrapText(true);
		}
		else
		{
			$spreadsheet->getActiveSheet()->setCellValue('B1', $_SESSION['INGRESO']['noempr'].'');
		}

		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setName('Candara');
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setUnderline(Font::UNDERLINE_SINGLE);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('B1')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
		$spreadsheet->getActiveSheet()->getColumnDimension('B1')->setAutoSize(true);
		$je1++;$je1++;

		$richText1 = new RichText();
		$richText1->createText(''."\n");

		
		$redf=$richText1->createTextRun("Hora: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("H:i")."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Fecha: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("d-m-Y") ."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Usuario: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$red = $richText1->createTextRun($_SESSION['INGRESO']['Nombre']);
		$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$red->getFont()->setSize(8);
			
		$spreadsheet->getActiveSheet()
			->getCell($je1.''.$ie)
			->setValue($richText1);
         
			//ampliar columna
		$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getAlignment()->setWrapText(true);
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, Date::PHPToExcel(gmmktime(0, 0, 0, date('m'), date('d'), date('Y'))));
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_XLSX15);
		$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		//$spreadsheet->getActiveSheet()->getColumnDimension($je1)->setAutoSize(true);

	    $spreadsheet->getActiveSheet()->getColumnDimension($je1)->setWidth(23);
	    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
        
		//$je1++;
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, '');
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->setFillType(Fill::FILL_SOLID);
		//$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('FF808080');

		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('0086c7');

		//si lleva o no border
		$bor='';
		if($b!=null and $b!='0')
		{
			$bor='table-bordered1';
			//style="border-top: 1px solid #bce8f1;"
		}
		//nombre de columnas
		//para las celdas de excel
		$ie = 2;
		$je = 'A';
		//cantidad campos
		$cant=0;
		//guardamos los campos
		$campo='';
		//tipo de campos
		$tipo_campo=array();
		//guardamos posicion de un campo ejemplo fecha
		$cam_fech=array();
		//contador para fechas
		$cont_fecha=0;
		//obtenemos los campos 
	$border = array(
		'borders' => [
			'allBorders' => [
				 	'borderStyle' => Border::BORDER_THIN,
				 		 ],
				 	 ],
		);

	
			foreach($cabecera as $value) {
				// if($je=='B')
				// {
				// 	$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $value)->mergeCells('B'.$ie.':C'.$ie);	
				// 	$je++;
				// }else
				// {
				// 	$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $value);
				// }
			$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $value);	
			$spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
			$je++;
		}
		$ie = 3;
		$je = 'A';
		$temp = '';
        $total_debe= 0;
		$total_haber = 0;
		$count = 1;

		//---------------------------------------------INICIO DE TODO------------------------------

	     $GRAN_TOTAL = 0;
		foreach ($re as $key => $value) {
			// print_r($value);die();
			$spreadsheet->getActiveSheet()->setCellValue('A'.$ie,$value['Fecha'].' '.$value['Hora']);
			$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(45);
			$spreadsheet->getActiveSheet()->setCellValue('B'.$ie,$value['Entidad']);
			// ->mergeCells('B'.$ie.':C'.$ie);	
			$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(60);			
			$spreadsheet->getActiveSheet()->setCellValue('C'.$ie,$value['IP_Acceso']);
			$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(23);
			$spreadsheet->getActiveSheet()->setCellValue('D'.$ie, $value['Aplicacion']);
			$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(23);		
			$spreadsheet->getActiveSheet()->setCellValue('E'.$ie, $value['Tarea']);
			$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(23);		
			$spreadsheet->getActiveSheet()->setCellValue('F'.$ie,$value['Empresa']);
			$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(23);	
			$spreadsheet->getActiveSheet()->setCellValue('G'.$ie,$value['Usuario']);
			$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(23);							
		    // $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':E'.$ie)->applyFromArray($estilo_cabecera);
			$ie+=1;
			$pos=0;
		}
		//--------------------------------------------FIN DE TODO---------------------------------

		    $ie = $ie+1;
				
	$spreadsheet->setActiveSheetIndex(0);


	$writer = new Xlsx($spreadsheet);

	if($ti == '' OR $ti==null)
	{
	$writer->save(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($_SESSION['INGRESO']['ti']).'.xlsx');
	//$writer->save('php://output');
	download_file(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($_SESSION['INGRESO']['ti']).".xlsx", trim($_SESSION['INGRESO']['ti']).".xlsx");
    }else
    {
    
		$writer->save(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($ti).'.xlsx');
		//$writer->save('php://output');
	    download_file(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($ti).".xlsx", trim($ti).".xlsx");

    }
}

function excel_file_comp($re=false,$ti=null,$camne=null,$b=null,$base=null) 
{

	//header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
    header('Content-Disposition: attachment; filename='.trim($ti).".xlsx");
	//require_once __DIR__ . '/vendor/phpoffice/phpspreadsheet/src/Bootstrap.php';

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	//$sheet->setCellValue('A1', 'Hello World !');

	// Set document properties
	$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
		->setLastModifiedBy('Maarten Balliauw')
		->setTitle('Office 2007 XLSX Test Document')
		->setSubject('Office 2007 XLSX Test Document')
		->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
		->setKeywords('office 2007 openxml php')
		->setCategory('Test result file');

		//echo $base.' entrooo ';
		//para las celdas de excel
		$ie = 1;
		$je = 'B';
		//para sabar hasta donde unir celdas
		$je1=$je;
		//verificamos los campos
		//cantidad de campos
		$cant=0;
		//guardamos los campos
		$campo='';
		//obtenemos los campos 
		$cabecera = array('NOMBRE','CONCEPTO','FECHA','No. COMPROB');
		foreach( $cabecera as $fieldMetadata ) 
		{
		  $cant++;
		  $je1++;
		}
		//cabecera
		//diseño de nombres de campos
		//'horizontal' => Alignment::HORIZONTAL_RIGHT,
		//nos posicionamos 3 posiciones anteriores campo
		$je2='B';
		for($i=0;$i<($cant-3);$i++)
		{
			$je2++;
		}
		$je1=$je2;
		$je3=$je2;
		$je3++;
		$estilo_cabecera = array(
			
					'font' => [
						'bold' => true,
					],
					'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_CENTER,
					],
					'borders' => [
						'top' => [
							'borderStyle' => Border::BORDER_THIN,
						],
					],
					'fill' => [
						'fillType' => Fill::FILL_GRADIENT_LINEAR,
						'rotation' => 90,
						'startColor' => [
							/*'argb' => 'FFA0A0A0',*/
							'argb' => '0086c7',
						],
						'endColor' => [
							'argb' => 'FFFFFFFF',
						],
					],
				
		);
		$spreadsheet->getActiveSheet()->getStyle('A2:'.$je3.'2')->applyFromArray($estilo_cabecera);
		//redimencionar
		//$spreadsheet->getActiveSheet()->getColumnDimension($je.''.$ie.':'.$je1.''.$ie)->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getStyle('B1')->applyFromArray(
			[
					'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_CENTER,
					],
					
			]
		);
		//logo empresa
		//echo __DIR__ ;
		////die();
		$drawing = new Drawing();
		$drawing->setName('Logo');
		$drawing->setDescription('Logo');
		//windows
		//$drawing->setPath(__DIR__ . '\logosMod.gif');
		//linux
		//$drawing->setPath(__DIR__ . '/logosMod.gif');
		if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
			//si es jpg
			$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
			if (@getimagesize($src)) 
			{ 
				$logo=$logo.'.jpg';
			}
			else
			{
				//si es gif
				$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
				if (@getimagesize($src)) 
				{ 
					$logo=$logo.'.gif';
				}
				else
				{
					//si es png
					$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
					if (@getimagesize($src)) 
					{ 
						$logo=$logo.'.png';
					}
					else
					{
						$logo="DEFAULT.jpg";
					}
				}
			}
		}
		else
		{
			$logo="DEFAULT.jpg";
		}
		/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		}
		else
		{
			$logo="DEFAULT";
		}*/
		$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo);
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates('A1');
		$drawing->setOffsetX(10);
		$drawing->setOffsetY(20);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//otro logo
		$drawing = new Drawing();
		$drawing->setName('Logo1');
		$drawing->setDescription('Logo1');
		//windows
		//$drawing->setPath(__DIR__ . '\logosMod.gif');
		//linux
		$drawing->setPath(__DIR__ . '/logosMod.gif');
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates($je3.'1');
		$drawing->setOffsetX(100);
		$drawing->setOffsetY(10);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//unir celdas
		$je2++;
		$spreadsheet->getActiveSheet()->mergeCells($je.''.$ie.':'.$je2.''.$ie);
		if($_SESSION['INGRESO']['Razon_Social']!=$_SESSION['INGRESO']['noempr'])
		{
			$richText2 = new RichText();
			$richText2->createText($_SESSION['INGRESO']['noempr'].' ');

			$red = $richText2->createTextRun($_SESSION['INGRESO']['Razon_Social']);
			$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$spreadsheet->getActiveSheet()->setCellValue('B1', $richText2);
			//ampliar columna
			//$spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setWrapText(true);
		}
		else
		{
			$spreadsheet->getActiveSheet()->setCellValue('B1', $_SESSION['INGRESO']['noempr'].'');
		}

		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setName('Candara');
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setUnderline(Font::UNDERLINE_SINGLE);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('B1')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
		$spreadsheet->getActiveSheet()->getColumnDimension('B1')->setAutoSize(true);
		$je1++;$je1++;

		$richText1 = new RichText();
		$richText1->createText(''."\n");

		
		$redf=$richText1->createTextRun("Hora: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("H:i")."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Fecha: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("d-m-Y") ."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Usuario: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$red = $richText1->createTextRun($_SESSION['INGRESO']['Nombre']);
		$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$red->getFont()->setSize(8);
			
		$spreadsheet->getActiveSheet()
			->getCell($je1.''.$ie)
			->setValue($richText1);
         
			//ampliar columna
		$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getAlignment()->setWrapText(true);
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, Date::PHPToExcel(gmmktime(0, 0, 0, date('m'), date('d'), date('Y'))));
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_XLSX15);
		$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		//$spreadsheet->getActiveSheet()->getColumnDimension($je1)->setAutoSize(true);

	    $spreadsheet->getActiveSheet()->getColumnDimension($je1)->setWidth(23);
	    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
        
		//$je1++;
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, '');
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->setFillType(Fill::FILL_SOLID);
		//$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('FF808080');

		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('0086c7');

		//si lleva o no border
		$bor='';
		if($b!=null and $b!='0')
		{
			$bor='table-bordered1';
			//style="border-top: 1px solid #bce8f1;"
		}
		//nombre de columnas
		//para las celdas de excel
		$ie = 2;
		$je = 'A';
		//cantidad campos
		$cant=0;
		//guardamos los campos
		$campo='';
		//tipo de campos
		$tipo_campo=array();
		//guardamos posicion de un campo ejemplo fecha
		$cam_fech=array();
		//contador para fechas
		$cont_fecha=0;
		//obtenemos los campos 
	$border = array(
		'borders' => [
			'allBorders' => [
				 	'borderStyle' => Border::BORDER_THIN,
				 		 ],
				 	 ],
		);

	
			// foreach($cabecera as $value) {
			// 	if($je=='B')
			// 	{
			// 		$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $value)->mergeCells('B'.$ie.':C'.$ie);	
			// 		$je++;
			// 	}else
			// 	{
			// 		$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $value);
			// 	}
			// $spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $value)->mergeCells('B'.$ie.':C'.$ie);	
			// $spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
		// 	$je++;
		// }
		$ie = 3;
		$je = 'A';
		$temp = '';
        $total_debe= 0;
		$total_haber = 0;
		$count = 1;

		//---------------------------------------------INICIO DE TODO------------------------------

		// print_r($re);die();

	     $GRAN_TOTAL = 0;
		foreach ($re as $key => $value) {
			$spreadsheet->getActiveSheet()->setCellValue('A'.$ie, 'NOMBRE: '.$value['Nombre']);
			$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(45);
			$spreadsheet->getActiveSheet()->setCellValue('B'.$ie, 'CONCEPTO: '.$value['Concepto'])->mergeCells('B'.$ie.':C'.$ie);	
			$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(60);			
			$spreadsheet->getActiveSheet()->setCellValue('D'.$ie, 'Fecha: '.$value['fecha']);
			$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(23);
			$spreadsheet->getActiveSheet()->setCellValue('E'.$ie, 'NO. Compro:'.$value['comprobante']);
			$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(23);					
		    $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':E'.$ie)->applyFromArray($estilo_cabecera);
			$ie+=1;
			$pos=0;

			        // print_r($value);die();
			foreach ($value['registros'] as $key2 => $value2) {

				
			         $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, 'CODIGO');
			         $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
					 $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, 'CANTIDAD');
			         $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(10);
					 $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, 'PRODUCTO');
			         $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(50);
					 $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, 'PRECIO UNI');
			         $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(50);
					 $spreadsheet->getActiveSheet()->setCellValue('E'.$ie, 'TOTAL');
			         $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);
			        $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':E'.$ie)->applyFromArray($estilo_cabecera);

			        $ie+=1;	
			        $TOTAL = 0;
			        foreach ($value2 as $key3 => $value3) {
			        // print_r($value3);die();
					        $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, $value3['codigo']);
			                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
					        $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, strval($value3['cantidad']));
			                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(10);
					        $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, $value3['producto']);
			                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(50);
					        $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, strval($value3['pre_uni']));
			                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(50);
					        $spreadsheet->getActiveSheet()->setCellValue('E'.$ie, $value3['total']);
			                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);
			                $TOTAL+=$value3['total'];
			                $ie+=1;				        
			        }

					        $spreadsheet->getActiveSheet()->setCellValue('D'.$ie,'TOTAL');
			                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(25);
					        $spreadsheet->getActiveSheet()->setCellValue('E'.$ie, strval($TOTAL));
			                $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);
			                $GRAN_TOTAL+=$TOTAL;
			        $ie+=2;
			}
			
		}
		//--------------------------------------------FIN DE TODO---------------------------------

		    $ie = $ie+1;
			$spreadsheet->getActiveSheet()->setCellValue('D'.$ie, 'GRAN TOTAL ');
			$spreadsheet->getActiveSheet()->setCellValue('E'.$ie,strval($GRAN_TOTAL));
			$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':E'.$ie)->applyFromArray($estilo_cabecera);

	
	$spreadsheet->setActiveSheetIndex(0);


	$writer = new Xlsx($spreadsheet);

	if($ti == '' OR $ti==null)
	{
	$writer->save(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($_SESSION['INGRESO']['ti']).'.xlsx');
	//$writer->save('php://output');
	download_file(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($_SESSION['INGRESO']['ti']).".xlsx", trim($_SESSION['INGRESO']['ti']).".xlsx");
    }else
    {
    
		$writer->save(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($ti).'.xlsx');
		//$writer->save('php://output');
	    download_file(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($ti).".xlsx", trim($ti).".xlsx");

    }
}

function historiaClienteExcel($datos,$ti='HistoriaCliente',$camne=null,$b=null,$base=null,$download=true)
{

	//header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
    header('Content-Disposition: attachment; filename='.trim($ti).".xlsx");
	//require_once __DIR__ . '/vendor/phpoffice/phpspreadsheet/src/Bootstrap.php';

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	//$sheet->setCellValue('A1', 'Hello World !');

	// Set document properties
	$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
		->setLastModifiedBy('Maarten Balliauw')
		->setTitle('Office 2007 XLSX Test Document')
		->setSubject('Office 2007 XLSX Test Document')
		->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
		->setKeywords('office 2007 openxml php')
		->setCategory('Test result file');

	//echo $base.' entrooo ';
	//para las celdas de excel
	$ie = 1;
	$je = 'B';
	//para sabar hasta donde unir celdas
	$je1=$je;
	//verificamos los campos
	//cantidad de campos
	$cant=0;
	//guardamos los campos
	$campo='';
	//obtenemos los campos 
	$cabecera = array('TD','Fecha','Serie','Factura','Detalle','Año','Mes','Total','Abonos','Mes No','No');
	foreach( $cabecera as $fieldMetadata ) 
	{
		$cant++;
		$je1++;
	}
	//cabecera
	//diseño de nombres de campos
	//'horizontal' => Alignment::HORIZONTAL_RIGHT,
	//nos posicionamos 3 posiciones anteriores campo
	$je2='B';
	for($i=0;$i<($cant-3);$i++)
	{
		$je2++;
	}
	$je1=$je2;
	$je3=$je2;
	$je3++;
	$estilo_cabecera = array(
		
				'font' => [
					'bold' => true,
				],
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
				'borders' => [
					'top' => [
						'borderStyle' => Border::BORDER_THIN,
					],
				],
				'fill' => [
					'fillType' => Fill::FILL_GRADIENT_LINEAR,
					'rotation' => 90,
					'startColor' => [
						/*'argb' => 'FFA0A0A0',*/
						'argb' => '0086c7',
					],
					'endColor' => [
						'argb' => 'FFFFFFFF',
					],
				],
			
	);
	$spreadsheet->getActiveSheet()->getStyle('A2:'.'L2')->applyFromArray($estilo_cabecera);
	//redimencionar
	//$spreadsheet->getActiveSheet()->getColumnDimension($je.''.$ie.':'.$je1.''.$ie)->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getStyle('B1')->applyFromArray(
		[
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
				
		]
	);
	//logo empresa
	//echo __DIR__ ;
	////die();
	$drawing = new Drawing();
	$drawing->setName('Logo');
	$drawing->setDescription('Logo');
	//windows
	//$drawing->setPath(__DIR__ . '\logosMod.gif');
	//linux
	//$drawing->setPath(__DIR__ . '/logosMod.gif');

		if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
			//si es jpg
			$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
			if (@getimagesize($src)) 
			{ 
				$logo=$logo.'.jpg';
			}
			else
			{
				//si es gif
				$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
				if (@getimagesize($src)) 
				{ 
					$logo=$logo.'.gif';
				}
				else
				{
					//si es png
					$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
					if (@getimagesize($src)) 
					{ 
						$logo=$logo.'.png';
					}
					else
					{
						$logo="DEFAULT.jpg";
					}
				}
			}
		}
		else
		{
			$logo="DEFAULT.jpg";
		}
		/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		}
		else
		{
			$logo="DEFAULT";
		}*/
		$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo);
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates('A1');
		$drawing->setOffsetX(10);
		$drawing->setOffsetY(20);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//otro logo
		$drawing = new Drawing();
		$drawing->setName('Logo1');
		$drawing->setDescription('Logo1');
		//windows
		//$drawing->setPath(__DIR__ . '\logosMod.gif');
		//linux
		$drawing->setPath(__DIR__ . '/logosMod.gif');
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates($je3.'1');
		$drawing->setOffsetX(100);
		$drawing->setOffsetY(10);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//unir celdas
		$je2++;
		$spreadsheet->getActiveSheet()->mergeCells($je.''.$ie.':'.$je2.''.$ie);
		if($_SESSION['INGRESO']['Razon_Social']!=$_SESSION['INGRESO']['noempr'])
		{
			$richText2 = new RichText();
			$richText2->createText($_SESSION['INGRESO']['noempr'].' ');

			$red = $richText2->createTextRun($_SESSION['INGRESO']['Razon_Social']);
			$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$spreadsheet->getActiveSheet()->setCellValue('B1', $richText2);
			//ampliar columna
			//$spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setWrapText(true);
		}
		else
		{
			$spreadsheet->getActiveSheet()->setCellValue('B1', $_SESSION['INGRESO']['noempr'].'');
		}

		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setName('Candara');
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setUnderline(Font::UNDERLINE_SINGLE);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('B1')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
		$spreadsheet->getActiveSheet()->getColumnDimension('B1')->setAutoSize(true);
		$je1++;$je1++;

		$richText1 = new RichText();
		$richText1->createText(''."\n");

		
		$redf=$richText1->createTextRun("Hora: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("H:i")."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Fecha: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("d-m-Y") ."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Usuario: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$red = $richText1->createTextRun($_SESSION['INGRESO']['Nombre']);
		$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$red->getFont()->setSize(8);
			
		$spreadsheet->getActiveSheet()
			->getCell($je1.''.$ie)
			->setValue($richText1);
         
			//ampliar columna
		$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getAlignment()->setWrapText(true);
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, Date::PHPToExcel(gmmktime(0, 0, 0, date('m'), date('d'), date('Y'))));
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_XLSX15);
		$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		//$spreadsheet->getActiveSheet()->getColumnDimension($je1)->setAutoSize(true);

	    $spreadsheet->getActiveSheet()->getColumnDimension($je1)->setWidth(23);
	    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
        
		//$je1++;
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, '');
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->setFillType(Fill::FILL_SOLID);
		//$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('FF808080');

		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('0086c7');

		//si lleva o no border
		$bor='';
		if($b!=null and $b!='0')
		{
			$bor='table-bordered1';
			//style="border-top: 1px solid #bce8f1;"
		}
		//nombre de columnas
		//para las celdas de excel
		$ie = 2;
		$je = 'A';
		//cantidad campos
		$cant=0;
		//guardamos los campos
		$campo='';
		//tipo de campos
		$tipo_campo=array();
		//guardamos posicion de un campo ejemplo fecha
		$cam_fech=array();
		//contador para fechas
		$cont_fecha=0;
		//obtenemos los campos 
	$border = array(
		'borders' => [
			'allBorders' => [
				 	'borderStyle' => Border::BORDER_THIN,
				 		 ],
				 	 ],
		);

	$spreadsheet->getActiveSheet()->setCellValue('A2', 'TD');
    $spreadsheet->getActiveSheet()->setCellValue('B2', 'Fecha');
    $spreadsheet->getActiveSheet()->setCellValue('C2', 'Serie');
    $spreadsheet->getActiveSheet()->setCellValue('D2', 'Factura');
    $spreadsheet->getActiveSheet()->setCellValue('E2', 'Detalle');
    $spreadsheet->getActiveSheet()->setCellValue('G2', 'Año');
    $spreadsheet->getActiveSheet()->setCellValue('H2', 'Mes');
    $spreadsheet->getActiveSheet()->setCellValue('I2', 'Total');
    $spreadsheet->getActiveSheet()->setCellValue('J2', 'Abonos');
    $spreadsheet->getActiveSheet()->setCellValue('K2', 'Mes No');
    $spreadsheet->getActiveSheet()->setCellValue('L2', 'No');
		$ie = 3;
		$je = 'A';
		$temp = '';
        $total_debe= 0;
		$total_haber = 0;
		$cont = $ie;
		$spreadsheet->getActiveSheet()->mergeCells('E2:F2');
		//---------------------------------------------INICIO DE TODO------------------------------

	while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
		$spreadsheet->getActiveSheet()->mergeCells('E'.$cont.':F'.$cont);
    	$spreadsheet->getActiveSheet()->setCellValue('A'.$cont, utf8_encode($value['TD']));
    	$spreadsheet->getActiveSheet()->setCellValue('B'.$cont, utf8_encode($value['Fecha']->format('Y-m-d')));
    	$spreadsheet->getActiveSheet()->setCellValue('C'.$cont, utf8_encode($value['Serie']));
    	$spreadsheet->getActiveSheet()->setCellValue('D'.$cont, utf8_encode($value['Factura']));
    	$spreadsheet->getActiveSheet()->setCellValue('E'.$cont, utf8_encode($value['Detalle']));
    	$spreadsheet->getActiveSheet()->setCellValue('G'.$cont, utf8_encode($value['Anio']));
    	$spreadsheet->getActiveSheet()->setCellValue('H'.$cont, utf8_encode($value['Mes']));
    	$spreadsheet->getActiveSheet()->setCellValue('I'.$cont, utf8_encode($value['Total']));
    	$spreadsheet->getActiveSheet()->setCellValue('J'.$cont, utf8_encode($value['Abonos']));
    	$spreadsheet->getActiveSheet()->setCellValue('K'.$cont, utf8_encode($value['Mes_No']));
    	$spreadsheet->getActiveSheet()->setCellValue('L'.$cont, utf8_encode($value['No']));
    	$cont++;
    }
		//--------------------------------------------FIN DE TODO---------------------------------

		    $ie = $ie+1;
				
	$spreadsheet->setActiveSheetIndex(0);

	$writer = new Xlsx($spreadsheet);

	if($ti == '' OR $ti==null)
	{
		$writer->save(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($_SESSION['INGRESO']['ti']).'.xlsx');
		//$writer->save('php://output');
		if ($download) {
			download_file(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($_SESSION['INGRESO']['ti']).".xlsx", trim($_SESSION['INGRESO']['ti']).".xlsx");
		}
    }else
    {
		$writer->save(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($ti).'.xlsx');
		//$writer->save('php://output');
		if ($download) {
	    	download_file(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($ti).".xlsx", trim($ti).".xlsx");
		}

    }
}

function kardexExcel($titulos,$datos,$ti='ControlExistencias',$camne=null,$b=null,$base=null,$download=true)
{

	//header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
    header('Content-Disposition: attachment; filename='.trim($ti).".xlsx");
	//require_once __DIR__ . '/vendor/phpoffice/phpspreadsheet/src/Bootstrap.php';

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	//$sheet->setCellValue('A1', 'Hello World !');

	// Set document properties
	$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
		->setLastModifiedBy('Maarten Balliauw')
		->setTitle('Office 2007 XLSX Test Document')
		->setSubject('Office 2007 XLSX Test Document')
		->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
		->setKeywords('office 2007 openxml php')
		->setCategory('Test result file');

	//echo $base.' entrooo ';
	//para las celdas de excel
	$ie = 1;
	$je = 'B';
	//para sabar hasta donde unir celdas
	$je1=$je;
	//verificamos los campos
	//cantidad de campos
	$cant=0;
	//guardamos los campos
	$campo='';
	//obtenemos los campos 
	$cabecera = explode(",", $titulos);
	foreach( $cabecera as $fieldMetadata ) 
	{
		$cant++;
		$je1++;
	}
	//cabecera
	//diseño de nombres de campos
	//'horizontal' => Alignment::HORIZONTAL_RIGHT,
	//nos posicionamos 3 posiciones anteriores campo
	$je2='B';
	for($i=0;$i<($cant-3);$i++)
	{
		$je2++;
	}
	$je1=$je2;
	$je3=$je2;
	$je3++;
	$estilo_cabecera = array(
		
				'font' => [
					'bold' => true,
				],
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
				'borders' => [
					'top' => [
						'borderStyle' => Border::BORDER_THIN,
					],
				],
				'fill' => [
					'fillType' => Fill::FILL_GRADIENT_LINEAR,
					'rotation' => 90,
					'startColor' => [
						/*'argb' => 'FFA0A0A0',*/
						'argb' => '0086c7',
					],
					'endColor' => [
						'argb' => 'FFFFFFFF',
					],
				],
			
	);
	$spreadsheet->getActiveSheet()->getStyle('A2:'.$je1.'2')->applyFromArray($estilo_cabecera);
	//redimencionar
	//$spreadsheet->getActiveSheet()->getColumnDimension($je.''.$ie.':'.$je1.''.$ie)->setAutoSize(true);
	$spreadsheet->getActiveSheet()->getStyle('B1')->applyFromArray(
		[
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_CENTER,
				],
				
		]
	);
	//logo empresa
	//echo __DIR__ ;
	////die();
	$drawing = new Drawing();
	$drawing->setName('Logo');
	$drawing->setDescription('Logo');
	//windows
	//$drawing->setPath(__DIR__ . '\logosMod.gif');
	//linux
	//$drawing->setPath(__DIR__ . '/logosMod.gif');

		if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
			//si es jpg
			$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
			if (@getimagesize($src)) 
			{ 
				$logo=$logo.'.jpg';
			}
			else
			{
				//si es gif
				$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
				if (@getimagesize($src)) 
				{ 
					$logo=$logo.'.gif';
				}
				else
				{
					//si es png
					$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
					if (@getimagesize($src)) 
					{ 
						$logo=$logo.'.png';
					}
					else
					{
						$logo="DEFAULT.jpg";
					}
				}
			}
		}
		else
		{
			$logo="DEFAULT.jpg";
		}
		/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		}
		else
		{
			$logo="DEFAULT";
		}*/
		$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo);
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates('A1');
		$drawing->setOffsetX(10);
		$drawing->setOffsetY(20);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//otro logo
		$drawing = new Drawing();
		$drawing->setName('Logo1');
		$drawing->setDescription('Logo1');
		//windows
		//$drawing->setPath(__DIR__ . '\logosMod.gif');
		//linux
		$drawing->setPath(__DIR__ . '/logosMod.gif');
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates($je3.'1');
		$drawing->setOffsetX(100);
		$drawing->setOffsetY(10);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//unir celdas
		$je2++;
		$spreadsheet->getActiveSheet()->mergeCells($je.''.$ie.':'.$je1.''.$ie);
		if($_SESSION['INGRESO']['Razon_Social']!=$_SESSION['INGRESO']['noempr'])
		{
			$richText2 = new RichText();
			$richText2->createText($_SESSION['INGRESO']['noempr'].' ');

			$red = $richText2->createTextRun($_SESSION['INGRESO']['Razon_Social']);
			$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$spreadsheet->getActiveSheet()->setCellValue('B1', $richText2);
			//ampliar columna
			//$spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setWrapText(true);
		}
		else
		{
			$spreadsheet->getActiveSheet()->setCellValue('B1', $_SESSION['INGRESO']['noempr'].'');
		}

		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setName('Candara');
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setUnderline(Font::UNDERLINE_SINGLE);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('B1')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
		$spreadsheet->getActiveSheet()->getColumnDimension('B1')->setAutoSize(true);
		$je1++;$je1++;

		$richText1 = new RichText();
		$richText1->createText(''."\n");

		
		$redf=$richText1->createTextRun("Hora: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("H:i")."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Fecha: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("d-m-Y") ."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Usuario: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$red = $richText1->createTextRun($_SESSION['INGRESO']['Nombre']);
		$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$red->getFont()->setSize(8);
			
		$spreadsheet->getActiveSheet()
			->getCell($je1.''.$ie)
			->setValue($richText1);
         
			//ampliar columna
		$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getAlignment()->setWrapText(true);
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, Date::PHPToExcel(gmmktime(0, 0, 0, date('m'), date('d'), date('Y'))));
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_XLSX15);
		$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		//$spreadsheet->getActiveSheet()->getColumnDimension($je1)->setAutoSize(true);

	    $spreadsheet->getActiveSheet()->getColumnDimension($je2)->setWidth(23);
	    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
        
		//$je1++;
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, '');
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je2.''.$ie)->getFill()->setFillType(Fill::FILL_SOLID);
		//$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('FF808080');

		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je2.''.$ie)->getFill()->getStartColor()->setARGB('0086c7');

		//si lleva o no border
		$bor='';
		if($b!=null and $b!='0')
		{
			$bor='table-bordered1';
			//style="border-top: 1px solid #bce8f1;"
		}
		//nombre de columnas
		//para las celdas de excel
		$ie = 2;
		$je = 'A';
		//cantidad campos
		$cant=0;
		//guardamos los campos
		$campo='';
		//tipo de campos
		$tipo_campo=array();
		//guardamos posicion de un campo ejemplo fecha
		$cam_fech=array();
		//contador para fechas
		$cont_fecha=0;
		//obtenemos los campos 
	$border = array(
		'borders' => [
			'allBorders' => [
				 	'borderStyle' => Border::BORDER_THIN,
				 		 ],
				 	 ],
		);
		$cabecera = explode(",", $titulos);
		foreach($cabecera as $value) {
				
			$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $value);			
		    $spreadsheet->getActiveSheet()->getStyle('A2:'.$je3.'2')->applyFromArray($estilo_cabecera);
			// $spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
			$je++;
		}
		$ie = 3;
		$je = 'A';
		$temp = '';
        $total_debe= 0;
		$total_haber = 0;
		$cont = $ie;
		$spreadsheet->getActiveSheet()->mergeCells('E2:F2');
		//---------------------------------------------INICIO DE TODO------------------------------
	$i = 0;
	while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
		$spreadsheet->getActiveSheet()->mergeCells('E'.$cont.':F'.$cont);
    	$spreadsheet->getActiveSheet()->setCellValue($je.$cont, utf8_encode($value[$i]));
    	$cont++;
    	$je++;
    	$i++;
    }

    	$je = 'A';
	    $tamano = count($cabecera);
	    $cont = 1;
	    $i= 3;
	    foreach ($datos as $value) {
	    	$spreadsheet->getActiveSheet()->setCellValue($je.''.$i, $value);
	    	//$spreadsheet->getActiveSheet()->mergeCells($je.$cont.':F'.$cont);
	    	$je++;
	    	$cont++;
	    	if ( $cont > $tamano) {
	    		$je = 'A';
	    		$cont = 1;
	    		$i++;
	    	}
	    }
		//--------------------------------------------FIN DE TODO---------------------------------

		    $ie = $ie+1;
				
	$spreadsheet->setActiveSheetIndex(0);

	$writer = new Xlsx($spreadsheet);
	if($ti == '' OR $ti==null)
	{
		$writer->save(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($_SESSION['INGRESO']['ti']).'.xlsx');
		//$writer->save('php://output');
		if ($download) {
			download_file(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($_SESSION['INGRESO']['ti']).".xlsx", trim($_SESSION['INGRESO']['ti']).".xlsx");
		}
    }else
    {
		$writer->save(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($ti).'.xlsx');
		//$writer->save('php://output');
		if ($download) {
	    	download_file(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($ti).".xlsx", trim($ti).".xlsx");
		}

    }
}

function excel_file_diario($re,$ti=null,$camne=null,$b=null,$base=null) 
{

	//header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
    header('Content-Disposition: attachment; filename='.trim($ti).".xlsx");
	//require_once __DIR__ . '/vendor/phpoffice/phpspreadsheet/src/Bootstrap.php';

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	//$sheet->setCellValue('A1', 'Hello World !');

	// Set document properties
	$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
		->setLastModifiedBy('Maarten Balliauw')
		->setTitle('Office 2007 XLSX Test Document')
		->setSubject('Office 2007 XLSX Test Document')
		->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
		->setKeywords('office 2007 openxml php')
		->setCategory('Test result file');

		//echo $base.' entrooo ';
		//para las celdas de excel
		$ie = 1;
		$je = 'B';
		//para sabar hasta donde unir celdas
		$je1=$je;
		//verificamos los campos
		//cantidad de campos
		$cant=0;
		//guardamos los campos
		$campo='';
		//obtenemos los campos 
		$cabecera = array('COMPROB','CONCEPTO','CODIGO','CUENTA','DEBE','HABER');
		foreach( $cabecera as $fieldMetadata ) 
		{
		  $cant++;
		  $je1++;
		}
		//cabecera
		//diseño de nombres de campos
		//'horizontal' => Alignment::HORIZONTAL_RIGHT,
		//nos posicionamos 3 posiciones anteriores campo
		$je2='B';
		for($i=0;$i<($cant-3);$i++)
		{
			$je2++;
		}
		$je1=$je2;
		$je3=$je2;
		$je3++;
		$estilo_cabecera = array(
			
					'font' => [
						'bold' => true,
					],
					'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_CENTER,
					],
					'borders' => [
						'top' => [
							'borderStyle' => Border::BORDER_THIN,
						],
					],
					'fill' => [
						'fillType' => Fill::FILL_GRADIENT_LINEAR,
						'rotation' => 90,
						'startColor' => [
							/*'argb' => 'FFA0A0A0',*/
							'argb' => '0086c7',
						],
						'endColor' => [
							'argb' => 'FFFFFFFF',
						],
					],
				
		);
		$spreadsheet->getActiveSheet()->getStyle('A2:'.$je3.'2')->applyFromArray($estilo_cabecera);
		//redimencionar
		//$spreadsheet->getActiveSheet()->getColumnDimension($je.''.$ie.':'.$je1.''.$ie)->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getStyle('B1')->applyFromArray(
			[
					'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_CENTER,
					],
					
			]
		);
		//logo empresa
		//echo __DIR__ ;
		////die();
		$drawing = new Drawing();
		$drawing->setName('Logo');
		$drawing->setDescription('Logo');
		//windows
		//$drawing->setPath(__DIR__ . '\logosMod.gif');
		//linux
		//$drawing->setPath(__DIR__ . '/logosMod.gif');
		if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
			//si es jpg
			$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
			if (@getimagesize($src)) 
			{ 
				$logo=$logo.'.jpg';
			}
			else
			{
				//si es gif
				$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
				if (@getimagesize($src)) 
				{ 
					$logo=$logo.'.gif';
				}
				else
				{
					//si es png
					$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
					if (@getimagesize($src)) 
					{ 
						$logo=$logo.'.png';
					}
					else
					{
						$logo="DEFAULT.jpg";
					}
				}
			}
		}
		else
		{
			$logo="DEFAULT.jpg";
		}
		/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		}
		else
		{
			$logo="DEFAULT";
		}*/
		$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo);
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates('A1');
		$drawing->setOffsetX(10);
		$drawing->setOffsetY(20);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//otro logo
		$drawing = new Drawing();
		$drawing->setName('Logo1');
		$drawing->setDescription('Logo1');
		//windows
		//$drawing->setPath(__DIR__ . '\logosMod.gif');
		//linux
		$drawing->setPath(__DIR__ . '/logosMod.gif');
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates($je3.'1');
		$drawing->setOffsetX(100);
		$drawing->setOffsetY(10);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//unir celdas
		$spreadsheet->getActiveSheet()->mergeCells($je.''.$ie.':'.$je2.''.$ie);
		if($_SESSION['INGRESO']['Razon_Social']!=$_SESSION['INGRESO']['noempr'])
		{
			$richText2 = new RichText();
			$richText2->createText($_SESSION['INGRESO']['noempr'].' ');

			$red = $richText2->createTextRun($_SESSION['INGRESO']['Razon_Social']);
			$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$spreadsheet->getActiveSheet()->setCellValue('B1', $richText2);
			//ampliar columna
			//$spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setWrapText(true);
		}
		else
		{
			$spreadsheet->getActiveSheet()->setCellValue('B1', $_SESSION['INGRESO']['noempr'].'');
		}

		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setName('Candara');
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setUnderline(Font::UNDERLINE_SINGLE);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('B1')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
		$spreadsheet->getActiveSheet()->getColumnDimension('B1')->setAutoSize(true);
		$je1++;

		$richText1 = new RichText();
		$richText1->createText(''."\n");

		
		$redf=$richText1->createTextRun("Hora: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("H:i")."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Fecha: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("d-m-Y") ."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Usuario: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$red = $richText1->createTextRun($_SESSION['INGRESO']['Nombre']);
		$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$red->getFont()->setSize(8);
			
		$spreadsheet->getActiveSheet()
			->getCell($je1.''.$ie)
			->setValue($richText1);


			//ampliar columna
		$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getAlignment()->setWrapText(true);
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, Date::PHPToExcel(gmmktime(0, 0, 0, date('m'), date('d'), date('Y'))));
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_XLSX15);
		$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		//$spreadsheet->getActiveSheet()->getColumnDimension($je1)->setAutoSize(true);

	    $spreadsheet->getActiveSheet()->getColumnDimension($je1)->setWidth(23);
	    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
        
		//$je1++;
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, '');
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->setFillType(Fill::FILL_SOLID);
		//$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('FF808080');

		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('0086c7');

		//si lleva o no border
		$bor='';
		if($b!=null and $b!='0')
		{
			$bor='table-bordered1';
			//style="border-top: 1px solid #bce8f1;"
		}
		//nombre de columnas
		//para las celdas de excel
		$ie = 2;
		$je = 'A';
		//cantidad campos
		$cant=0;
		//guardamos los campos
		$campo='';
		//tipo de campos
		$tipo_campo=array();
		//guardamos posicion de un campo ejemplo fecha
		$cam_fech=array();
		//contador para fechas
		$cont_fecha=0;
		//obtenemos los campos 
	$border = array(
		'borders' => [
			'allBorders' => [
				 	'borderStyle' => Border::BORDER_THIN,
				 		 ],
				 	 ],
		);

	
			foreach($cabecera as $value) {
				
			$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $value);			
		    $spreadsheet->getActiveSheet()->getStyle('A2:'.$je3.'2')->applyFromArray($estilo_cabecera);
			// $spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
			$je++;
		}
		$ie = 3;
		$je = 'A';
		$temp = '';
        $total_debe= 0;
		$total_haber = 0;
		$count = 1;
		
		foreach ($re as $key => $value) {
			$total_debe+= $value['Debe'];
		    $total_haber+= $value['Haber'];
		
			if($temp == '')
			{
				$temp = $value['Numero'];
			$spreadsheet->getActiveSheet()->setCellValue('A'.$ie, $value['Fecha']->format('Y-m-d').' '.$value['TP'].'-'.$value['Numero']);
			$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(23);

			$spreadsheet->getActiveSheet()->setCellValue('B'.$ie, utf8_decode($value['Concepto']));
			$spreadsheet->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(60);

			$spreadsheet->getActiveSheet()->setCellValue('C'.$ie, $value['Cta']);
			$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(18);

			$spreadsheet->getActiveSheet()->setCellValue('D'.$ie, utf8_decode($value['Cuenta']));
			$spreadsheet->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true);
			$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(44);

			$spreadsheet->getActiveSheet()->setCellValue('E'.$ie,$value['Debe'])->getColumnDimension('E')->setWidth(23);
			
			$spreadsheet->getActiveSheet()->setCellValue('F'.$ie,$value['Haber']);
			//$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth();
			$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':F'.$ie)->applyFromArray($border);
			
			$ie = $ie+1;
		 }else
		 {
		 	if($temp == $value['Numero'])
		 	{
		 		 $spreadsheet->getActiveSheet()->setCellValue('A'.$ie,'');
			     $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(23);

			     $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, '');
			     $spreadsheet->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);
			     $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(60);


			     $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, $value['Cta']);
			     $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(18);


			     $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, utf8_encode($value['Cuenta']));
			     $spreadsheet->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true);

			     $spreadsheet->getActiveSheet()->setCellValue('E'.$ie,$value['Debe']);

			     $spreadsheet->getActiveSheet()->setCellValue('F'.$ie,$value['Haber']);
			    // $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);

			     $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':F'.$ie)->applyFromArray($border);
			     $ie = $ie+1; 
			     
		 	}else
		 	  {
		 	  	 $count = $count+1;
		 
		 		 $ie = $ie+1;
		 		 $temp = $value['Numero'];
			     
			     $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, $value['Fecha']->format('Y-m-d').' '.$value['TP'].'-'.$value['Numero']);
			     $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(23);

			     $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, utf8_encode($value['Concepto']));
			     $spreadsheet->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);
			     $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(60);


			     $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, $value['Cta']);
			     $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(18);


			     $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, utf8_encode($value['Cuenta']));
			     $spreadsheet->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true);


			     $spreadsheet->getActiveSheet()->setCellValue('E'.$ie,$value['Debe']);


			     $spreadsheet->getActiveSheet()->setCellValue('F'.$ie,$value['Haber']);
			    // $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);
			     $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':F'.$ie)->applyFromArray($border);

			     $ie = $ie+1;
			    
		 	}

		 }
		}

		    $ie = $ie+1;
			$spreadsheet->getActiveSheet()->setCellValue('D'.$ie, 'Total');
			$spreadsheet->getActiveSheet()->setCellValue('E'.$ie,$total_debe);
			$spreadsheet->getActiveSheet()->setCellValue('F'.$ie,$total_haber);

	
	$spreadsheet->setActiveSheetIndex(0);


	$writer = new Xlsx($spreadsheet);

	if($ti == '' OR $ti==null)
	{
	$writer->save(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($_SESSION['INGRESO']['ti']).'.xlsx');
	//$writer->save('php://output');
	download_file(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($_SESSION['INGRESO']['ti']).".xlsx", trim($_SESSION['INGRESO']['ti']).".xlsx");
    }else
    {
    
		$writer->save(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($ti).'.xlsx');
		//$writer->save('php://output');
	    download_file(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($ti).".xlsx", trim($ti).".xlsx");

    }
}

function excel_file_libro($re,$stmt,$ti=null,$camne=null,$b=null,$base=null) 
{

	//header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
    header('Content-Disposition: attachment; filename='.trim($ti).".xlsx");
	//require_once __DIR__ . '/vendor/phpoffice/phpspreadsheet/src/Bootstrap.php';

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	//$sheet->setCellValue('A1', 'Hello World !');

	// Set document properties
	$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
		->setLastModifiedBy('Maarten Balliauw')
		->setTitle('Office 2007 XLSX Test Document')
		->setSubject('Office 2007 XLSX Test Document')
		->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
		->setKeywords('office 2007 openxml php')
		->setCategory('Test result file');

	// Set style for header row using alternative method
	
	/*$spreadsheet->getActiveSheet()->getStyle('E3')->applyFromArray(
		[
				'borders' => [
					'right' => [
						'borderStyle' => Border::BORDER_THIN,
					],
				],
			]
	);*/
	if($base==null or $base=='SQL SERVER')
	{
		//echo $base.' entrooo ';
		//para las celdas de excel
		$ie = 1;
		$je = 'B';
		//para sabar hasta donde unir celdas
		$je1=$je;
		//verificamos los campos
		//cantidad de campos
		$cant=6;
		//guardamos los campos
		$campo='';
		//obtenemos los campos 

		//cabecera
		//diseño de nombres de campos
		//'horizontal' => Alignment::HORIZONTAL_RIGHT,
		//nos posicionamos 3 posiciones anteriores campo
		$je2='B';
		for($i=0;$i<($cant-3);$i++)
		{
			$je2++;
		}
		$je1=$je2;
		$je3=$je2;
		$je3++;
		$spreadsheet->getActiveSheet()->getStyle('A2:'.$je3.'2')->applyFromArray(
			[
					'font' => [
						'bold' => true,
					],
					'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_CENTER,
					],
					'borders' => [
						'top' => [
							'borderStyle' => Border::BORDER_THIN,
						],
					],
					'fill' => [
						'fillType' => Fill::FILL_GRADIENT_LINEAR,
						'rotation' => 90,
						'startColor' => [
							/*'argb' => 'FFA0A0A0',*/
							'argb' => '0086c7',
						],
						'endColor' => [
							'argb' => 'FFFFFFFF',
						],
					],
				]
		);
		//redimencionar
		//$spreadsheet->getActiveSheet()->getColumnDimension($je.''.$ie.':'.$je1.''.$ie)->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getStyle('B1')->applyFromArray(
			[
					'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_CENTER,
					],
					
			]
		);
		//logo empresa
		//echo __DIR__ ;
		////die();
		$drawing = new Drawing();
		$drawing->setName('Logo');
		$drawing->setDescription('Logo');
		//windows
		//$drawing->setPath(__DIR__ . '\logosMod.gif');
		//linux
		//$drawing->setPath(__DIR__ . '/logosMod.gif');
		if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
			//si es jpg
			$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
			if (@getimagesize($src)) 
			{ 
				$logo=$logo.'.jpg';
			}
			else
			{
				//si es gif
				$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
				if (@getimagesize($src)) 
				{ 
					$logo=$logo.'.gif';
				}
				else
				{
					//si es png
					$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
					if (@getimagesize($src)) 
					{ 
						$logo=$logo.'.png';
					}
					else
					{
						$logo="DEFAULT.jpg";
					}
				}
			}
		}
		else
		{
			$logo="DEFAULT.jpg";
		}
		/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		}
		else
		{
			$logo="DEFAULT";
		}*/
		$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo);
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates('A1');
		$drawing->setOffsetX(10);
		$drawing->setOffsetY(20);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//otro logo
		$drawing = new Drawing();
		$drawing->setName('Logo1');
		$drawing->setDescription('Logo1');
		//windows
		//$drawing->setPath(__DIR__ . '\logosMod.gif');
		//linux
		$drawing->setPath(__DIR__ . '/logosMod.gif');
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates($je3.'1');
		$drawing->setOffsetX(100);
		$drawing->setOffsetY(10);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//unir celdas
		$spreadsheet->getActiveSheet()->mergeCells($je.''.$ie.':'.$je2.''.$ie);
		if($_SESSION['INGRESO']['Razon_Social']!=$_SESSION['INGRESO']['noempr'])
		{
			$richText2 = new RichText();
			$richText2->createText($_SESSION['INGRESO']['noempr'].' ');

			$red = $richText2->createTextRun($_SESSION['INGRESO']['Razon_Social']);
			$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$spreadsheet->getActiveSheet()->setCellValue('B1', $richText2);
			//ampliar columna
			//$spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setWrapText(true);
		}
		else
		{
			$spreadsheet->getActiveSheet()->setCellValue('B1', $_SESSION['INGRESO']['noempr'].'');
		}

		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setName('Candara');
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setUnderline(Font::UNDERLINE_SINGLE);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('B1')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
		$spreadsheet->getActiveSheet()->getColumnDimension('B1')->setAutoSize(true);
		$je1++;

		$richText1 = new RichText();
		$richText1->createText(''."\n");

		
		$redf=$richText1->createTextRun("Hora: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("H:i")."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Fecha: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("d-m-Y") ."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Usuario: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$red = $richText1->createTextRun($_SESSION['INGRESO']['Nombre']);
		$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$red->getFont()->setSize(8);
			
		$spreadsheet->getActiveSheet()
			->getCell($je1.''.$ie)
			->setValue($richText1);

			//ampliar columna
		$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getAlignment()->setWrapText(true);
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, Date::PHPToExcel(gmmktime(0, 0, 0, date('m'), date('d'), date('Y'))));
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_XLSX15);
		$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

	    $spreadsheet->getActiveSheet()->getColumnDimension($je1)->setWidth(23);
	    $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
		//$spreadsheet->getActiveSheet()->getColumnDimension($je1)->setAutoSize(true);
		//$je1++;
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, '');
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->setFillType(Fill::FILL_SOLID);
		//$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('FF808080');

		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('0086c7');

		//si lleva o no border
		$bor='';
		if($b!=null and $b!='0')
		{
			$bor='table-bordered1';
			//style="border-top: 1px solid #bce8f1;"
		}
		//nombre de columnas
		//para las celdas de excel
		$ie = 2;
		$je = 'A';
		//cantidad campos
		$cant=0;
		//guardamos los campos
		$campo='';
		//tipo de campos
		$tipo_campo=array();
		//guardamos posicion de un campo ejemplo fecha
		$cam_fech=array();
		//contador para fechas
		$cont_fecha=0;
		//obtenemos los campos 
		


		$ie = $ie;
		$je = 'A';
		$temp = '';
		
	}

	$stilo_titu = array(
		'font' => [
			'bold' => true,
			      ],
	    'borders' => [
			'allBorders' => [
				 	'borderStyle' => Border::BORDER_THIN,
				 		 ],
				 	 ],
	    'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_LEFT,
		     			],
		'fill' => [
					'fillType' => Fill::FILL_GRADIENT_LINEAR,
			    	'rotation' => 90,
					'startColor' => [
								  	'argb' => '0086c7',
								  	],
					'endColor' => [
								'argb' => 'FFFFFFFF',
						         ],
				],
	);
		$estilo_sub = array(
		'font' => [
			'italic' => true,
			'size'=>9,

			      ],
	    'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_LEFT,
		     			],
	);

		$border = array(
		'borders' => [
			'allBorders' => [
				 	'borderStyle' => Border::BORDER_THIN,
				 		 ],
				 	 ],
		);
$total_debe1=0;
$total_haber1=0;
$count = 1;
	foreach ($re as $key => $value) 
	{
		if($temp =='')
		 {
		 	$total_debe1 = floatval($value['Debe']) + $total_debe1;
			$total_haber1 = floatval($value['Haber'])+ $total_haber1;

			$temp = $value['Numero'];
			$spreadsheet->getActiveSheet()->setCellValue('A'.$ie, 'Fecha:'.$value['Fecha']->format('Y-m-d'));
		    $spreadsheet->getActiveSheet()->mergeCells('A'.$ie.':B'.$ie);	
		    $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, 'Elaborado:'.$value['CodigoU'])->getColumnDimension('C')->setWidth(11);
		    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(11);

		    $spreadsheet->getActiveSheet()->mergeCells('C'.$ie.':D'.$ie);
		    $spreadsheet->getActiveSheet()->setCellValue('E'.$ie, 'TP:'.$value['TP'])->getColumnDimension('E')->setWidth(22);	
		    $spreadsheet->getActiveSheet()->setCellValue('F'.$ie, 'Numero:'.$value['Numero']);
		    $spreadsheet->getActiveSheet()->getStyle($je.''.$ie.':F'.$ie.':')->applyFromArray($stilo_titu);
		    $ie = $ie+1;	
		    $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, 'Concepto:'.$value['Concepto']);
		    $spreadsheet->getActiveSheet()->mergeCells('A'.$ie.':F'.$ie);
		         $spreadsheet->getActiveSheet()->getStyle($je.''.$ie.':F'.$ie.':')->applyFromArray($stilo_titu);
		    $ie = $ie+1;
		    $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, 'CODIGO')->getColumnDimension('A')->setWidth(18);
		    $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, 'CUENTA')->getColumnDimension('B')->setWidth(60);
		    $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, 'PARCIAL_ME');
		    $spreadsheet->getActiveSheet()->mergeCells('C'.$ie.':D'.$ie);		
		    $spreadsheet->getActiveSheet()->setCellValue('E'.$ie, 'DEBE');
		    $spreadsheet->getActiveSheet()->setCellValue('F'.$ie, 'HABER')->getColumnDimension('F')->setWidth(22);
		    $spreadsheet->getActiveSheet()->getStyle($je.''.$ie.':F'.$ie.':')->applyFromArray($stilo_titu);
		   // $spreadsheet->getActiveSheet()->getStyle('A3:F3:A4:F4:')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		    $ie = $ie+1;	


			$spreadsheet->getActiveSheet()->setCellValue('A'.$ie, $value['Cta']);
		    $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, utf8_encode($value['Cuenta']));
		    $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, '');
		    $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, '');
		    $spreadsheet->getActiveSheet()->setCellValue('E'.$ie, $value['Debe']);
		    $spreadsheet->getActiveSheet()->setCellValue('F'.$ie, $value['Haber']);		    
		    $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':F'.$ie.':')->applyFromArray($border);		    
		    $ie++;
		    if($value['Detalle'] != '.')
		   		{
		   			$spreadsheet->getActiveSheet()->setCellValue('A'.$ie,'');
		            $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, utf8_encode($value['Detalle']));
		            $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, '');
		            $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, '');
		            $spreadsheet->getActiveSheet()->setCellValue('E'.$ie,'');
		            $spreadsheet->getActiveSheet()->setCellValue('F'.$ie, '');		    
		            $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':F'.$ie.':')->applyFromArray($border);		    
		            $ie++;		   			
		   		}

		    foreach ($stmt as $key2 => $value2) 
		        {
		   			if($value['Cta'] == $value2['Cta'] && $value['Numero'] == $value2['Numero'])
		   			{
		   				$spreadsheet->getActiveSheet()->setCellValue('A'.$ie, '');
		                $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, '  '.$value2['Cliente'])->getStyle('B'.$ie)->applyFromArray($estilo_sub);
		   
		   				if(round($value2['Debitos'],2) == 0)
		   				{		   					
		                   $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, '');
		                   $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, $value2['Creditos']);
		   				}else
		   				{
		   				   $spreadsheet->getActiveSheet()->setCellValue('C'.$ie,$value2['Debitos']);
		                   $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, '');
		   				}		   				
		                   $spreadsheet->getActiveSheet()->setCellValue('E'.$ie, '');
		                   $spreadsheet->getActiveSheet()->setCellValue('F'.$ie, '');                
		                   $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':F'.$ie.':')->applyFromArray($border);		
		                   $ie++;             

		   			}
		   		}

		   		

		   
		 }
		 else
		 {
		 	if($temp == $value['Numero'])
		 	{
		 		$total_debe1 = floatval($value['Debe']) + $total_debe1;
			    $total_haber1 =floatval($value['Haber'])+ $total_haber1;

		 		$spreadsheet->getActiveSheet()->setCellValue('A'.$ie, $value['Cta']);
		        $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, utf8_encode($value['Cuenta']));
		        $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, '');
		        $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, '');
		        $spreadsheet->getActiveSheet()->setCellValue('E'.$ie, $value['Debe']);
		        $spreadsheet->getActiveSheet()->setCellValue('F'.$ie, $value['Haber']);
		        $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':F'.$ie.':')->applyFromArray($border);	
		        $ie++;
		        if($value['Detalle'] != '.')
		   		{
		   			$spreadsheet->getActiveSheet()->setCellValue('A'.$ie,'');
		            $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, utf8_encode($value['Detalle']));
		            $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, '');
		            $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, '');
		            $spreadsheet->getActiveSheet()->setCellValue('E'.$ie,'');
		            $spreadsheet->getActiveSheet()->setCellValue('F'.$ie, '');		    
		            $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':F'.$ie.':')->applyFromArray($border);		    
		            $ie++;		   			
		   		}
		        foreach ($stmt as $key2 => $value2) {
		   			if($value['Cta'] == $value2['Cta'] && $value['Numero'] == $value2['Numero'])
		   			{
		   				$spreadsheet->getActiveSheet()->setCellValue('A'.$ie, '');
		                $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, '  '.$value2['Cliente'])->getStyle('B'.$ie)->applyFromArray($estilo_sub);
		                
		   
		   				if(round($value2['Debitos'],2) == 0)
		   				{		   					
		                   $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, '');
		                   $spreadsheet->getActiveSheet()->setCellValue('D'.$ie,$value2['Creditos']);
		   				}else
		   				{
		   				   $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, $value2['Debitos']);
		                   $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, '');
		   				}		   				
		                   $spreadsheet->getActiveSheet()->setCellValue('E'.$ie, '');
		                   $spreadsheet->getActiveSheet()->setCellValue('F'.$ie, '');		                    
		                   $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':F'.$ie.':')->applyFromArray($border);		
		                   $ie++;             

		   			}
		   		}

		   		
		   		
		   		}else{
		 		//totales
		 	       	$count = $count+1;
		 	       
		 		$spreadsheet->getActiveSheet()->setCellValue('A'.$ie, '');
		        $spreadsheet->getActiveSheet()->setCellValue('B'.$ie,'');
		        $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, '');
		        $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, 'Totales');
		        $spreadsheet->getActiveSheet()->setCellValue('E'.$ie, strval($total_debe1));
		        $spreadsheet->getActiveSheet()->setCellValue('F'.$ie, strval($total_haber1));
		        $ie++;
		       //fin de totales
		         

		 		 $ie = $ie+1;	
		 		 $temp = $value['Numero'];
		 		 $total_debe=0;
			     $total_haber=0;

			     $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, 'Fecha:'.$value['Fecha']->format('Y-m-d'));
		         $spreadsheet->getActiveSheet()->mergeCells('A'.$ie.':B'.$ie);	
		         $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, 'Elaborado:'.$value['CodigoU']);

		         $spreadsheet->getActiveSheet()->mergeCells('C'.$ie.':D'.$ie);
		         $spreadsheet->getActiveSheet()->setCellValue('E'.$ie, 'TP:'.$value['TP']);	
		         $spreadsheet->getActiveSheet()->setCellValue('F'.$ie, 'Numero:'.$value['Numero']);
		         $spreadsheet->getActiveSheet()->getStyle($je.''.$ie.':F'.$ie.':')->applyFromArray($stilo_titu);
		         $ie = $ie+1;	
		         $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, 'Concepto:'.$value['Concepto']);
		         $spreadsheet->getActiveSheet()->mergeCells('A'.$ie.':F'.$ie);
		         $spreadsheet->getActiveSheet()->getStyle($je.''.$ie.':F'.$ie.':')->applyFromArray($stilo_titu);
		         $ie = $ie+1;
		         $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, 'CODIGO');
		         $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, 'CUENTA');
		         $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, 'PARCIAL_ME');
		         $spreadsheet->getActiveSheet()->mergeCells('C'.$ie.':D'.$ie);		
		         $spreadsheet->getActiveSheet()->setCellValue('E'.$ie, 'DEBE');
		         $spreadsheet->getActiveSheet()->setCellValue('F'.$ie, 'HABER');
		         $spreadsheet->getActiveSheet()->getStyle($je.''.$ie.':F'.$ie.':')->applyFromArray($stilo_titu);
		         $ie = $ie+1;	


			     $spreadsheet->getActiveSheet()->setCellValue('A'.$ie, $value['Cta']);
		         $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, utf8_encode($value['Cuenta']));
		         $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, '');
		         $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, '');
		         $spreadsheet->getActiveSheet()->setCellValue('E'.$ie, $value['Debe']);
		         $spreadsheet->getActiveSheet()->setCellValue('F'.$ie, $value['Haber']);
		         $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':F'.$ie.':')->applyFromArray($border);	

		         $ie++;  
		         if($value['Detalle'] != '.')
		   		{
		   			$spreadsheet->getActiveSheet()->setCellValue('A'.$ie,'');
		            $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, utf8_encode($value['Detalle']));
		            $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, '');
		            $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, '');
		            $spreadsheet->getActiveSheet()->setCellValue('E'.$ie,'');
		            $spreadsheet->getActiveSheet()->setCellValue('F'.$ie, '');		    
		            $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':F'.$ie.':')->applyFromArray($border);		    
		            $ie++;		   			
		   		}
		         foreach ($stmt as $key2 => $value2) {
		   			if($value['Cta'] == $value2['Cta'] && $value['Numero'] == $value2['Numero'])
		   			{
		   				$spreadsheet->getActiveSheet()->setCellValue('A'.$ie, '');
		                $spreadsheet->getActiveSheet()->setCellValue('B'.$ie, '  '.$value2['Cliente'])->getStyle('B'.$ie)->applyFromArray($estilo_sub);
		   				if(round($value2['Debitos'],2) == 0)
		   				{		   					
		                   $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, '');
		                   $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, $value2['Creditos']);
		   				}else
		   				{
		   				   $spreadsheet->getActiveSheet()->setCellValue('C'.$ie, $value2['Debitos']);
		                   $spreadsheet->getActiveSheet()->setCellValue('D'.$ie, '');
		   				}		   				
		                   $spreadsheet->getActiveSheet()->setCellValue('E'.$ie, '');
		                   $spreadsheet->getActiveSheet()->setCellValue('F'.$ie, '');			                    
		                   $spreadsheet->getActiveSheet()->getStyle('A'.$ie.':F'.$ie.':')->applyFromArray($border);	
		                   $ie++;             

		   			}
		   		}
		   		$total_debe1 = floatval($value['Debe'])+$total_debe1;
			    $total_haber1 =floatval($value['Haber']) + $total_haber1; 
			   

		 	}
		 }
	}
	
	$spreadsheet->setActiveSheetIndex(0);


	$writer = new Xlsx($spreadsheet);

	if($ti == '' OR $ti==null)
	{
	$writer->save(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($_SESSION['INGRESO']['ti']).'.xlsx');
	//$writer->save('php://output');
	download_file(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($_SESSION['INGRESO']['ti']).".xlsx", trim($_SESSION['INGRESO']['ti']).".xlsx");
    }else
    {
    
		$writer->save(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($ti).'.xlsx');
		//$writer->save('php://output');
	    download_file(dirname(__DIR__,2).'/php/vista/TEMP/'.trim($ti).".xlsx", trim($ti).".xlsx");

    }
}


?>