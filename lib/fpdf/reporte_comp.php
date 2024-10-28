<?php
    error_reporting(E_ALL);
	ini_set('display_errors', '1');  
	//require('fpdf.php');
	require('PDF_MC_Table.php');	
	require_once("../../funciones/numeros_en_letras.php");

	if(isset($_POST['nombrep']))
	{
		$nombre=addslashes($_POST['nombrep']);
		$tabla=addslashes($_POST['sep']);
		$pagina=addslashes($_POST['paginap']);
		$campo=addslashes($_POST['campo']);
		$valor=addslashes($_POST['valor']);
		$empresa=addslashes($_POST['empresa']);
		$rif=addslashes($_POST['rif']);
		$va = split("=",$valor);
		//echo $nombre.' 2 '.$tabla.' 3 '.$pagina.' 4 '.$campo.' 5 '.$va[1];
		//die();
	}
	
class PDF extends PDF_MC_Table
{
	//pdf objeto , numero= el comprobante, TipoComp= tipo de comprobante CD,CE,etc...
	//Fecha = fecha del comprobante, $pag = numero de pagina
	function Header1($pdf,$Numero,$TipoComp,$Fecha,$pag)
	{
		$pdf->SetFont('Arial','B',30);
		$x=31;
		$pdf->SetXY($x, 20);
		if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social'])
		{
			$pdf->Cell(555,70,'','1',1,'Q');
		}
		else
		{
			$pdf->Cell(555,60,'','1',1,'Q');
		}
		$x=41;
		$pdf->SetXY($x, 20);
		//$pdf->SetWidths(array(250));
		if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
			//si es jpg
			$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
			if (@getimagesize($src)) 
			{ 
				$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.jpg',40,22,80,40,'','https://www.discoversystem.com');
			}
			else
			{
				//si es gif
				$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
				if (@getimagesize($src)) 
				{ 
					$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.gif',40,22,80,40,'','https://www.discoversystem.com');
				}
				else
				{
					//si es png
					$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
					if (@getimagesize($src)) 
					{ 
						$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
					}
				}
			}
		}
		else
		{
			$logo="diskcover";
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
		}
		//$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
		/*$pdf->SetWidths(array(65,215,65,65,60,55));
		$pdf->SetAligns(array("L","L","R","R","R","R"));
		//$arr=array($arr1[$i]);
		$arr=array($arr1[$i],$arr3[$i],$arr5[$i],
		$arr7[$i],$arr8[$i],$arr10[$i]);
		$pdf->Row($arr,10,1);*/
		$pdf->SetFont('Arial','B',12);
		$pdf->SetXY(180, 25);
		$pdf->SetWidths(array(250));
		$pdf->SetAligns(array("C",));
		//$arr=array($arr1[$i]);
		$arr=array($_SESSION['INGRESO']['Razon_Social']);
		$pdf->Row($arr,10);
		$y=25;
		//si razon social es distinto de nombre comercial imprimir
		if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social'])
		{
			$pdf->SetXY(180, 35);
			$pdf->SetWidths(array(250));
			$pdf->SetAligns(array("C",));
			//$arr=array($arr1[$i]);
			$pdf->SetFont('Arial','B',6);
			$arr=array($_SESSION['INGRESO']['Nombre_Comercial']);
			$pdf->Row($arr,10);
			$y=$y+10;
		}
		$y=$y+10;
		$pdf->SetXY(180, $y);
		$pdf->SetWidths(array(250));
		$pdf->SetAligns(array("C",));
		//$arr=array($arr1[$i]);
		$pdf->SetFont('Arial','B',10);
		$arr=array('R.U.C '.$_SESSION['INGRESO']["RUC"]);
		$pdf->Row($arr,10);
		$y=$y+10;
		$pdf->SetXY(180, $y);
		$pdf->SetWidths(array(270));
		$pdf->SetAligns(array("C",));
		//$arr=array($arr1[$i]);
		$pdf->SetFont('Arial','B',6);
		$arr=array($_SESSION['INGRESO']['Direccion'].' - '.$_SESSION['INGRESO']['Telefono1'].' / '.$_SESSION['INGRESO']['FAX']);
		$pdf->Row($arr,10);
		$y=$y+20;
		$pdf->SetXY(180, $y);
		$pdf->SetWidths(array(250));
		$pdf->SetAligns(array("C",));
		//$arr=array($arr1[$i]);
		$pdf->SetFont('Arial','B',18);
		if($TipoComp=='CD')
		{
			$arr=array('COMPROBANTE DE DIARIO');
		}
		if($TipoComp=='CI')
		{
			$arr=array('COMPROBANTE DE INGRESO');
			$pdf->SetFont('Arial','B',16);
		}
		if($TipoComp=='CE')
		{
			$arr=array('COMPROBANTE DE EGRESO');
			$pdf->SetFont('Arial','B',16);
		}
		if($TipoComp=='ND')
		{
			$arr=array('NOTA DE DEBITO');
		}
		if($TipoComp=='NC')
		{
			$arr=array('NOTA DE CREDITO');
		}
		$pdf->Row($arr,10);
		//agregamos la informacion en los laterales izquierdo
		$pdf->SetXY(470, 25);
		$pdf->Cell(110,15,'','1',1,'Q');
		$pdf->SetXY(470, 27);
		$pdf->SetFont('Arial','B',12);
		
		$pdf->SetWidths(array(28,92));
		$pdf->SetAligns(array("L","L"));
		//$arr=array($arr1[$i]);
		//agregamos ceros
		$num=8-strlen($Numero);
		//echo strlen($Numero);
		//die();
		$Fecha1 = explode("-", $Fecha);
		//echo $pdf->generaCeros($Numero,8);
		//die();
		$arr=array('No. ',substr($Fecha1[0], 2, 2).'-'.$pdf->generaCeros($Numero,8));
		$pdf->Row($arr,10);
		
		$pdf->SetXY(470, 40);
		$pdf->Cell(110,15,'','1',1,'Q');
		$pdf->SetXY(470, 42);
		$pdf->SetFont('Arial','',9);
		
		$pdf->SetWidths(array(35,85));
		$pdf->SetAligns(array("L","L"));
		//$arr=array($arr1[$i]);
		$arr=array('Fecha: ',$Fecha);
		$pdf->Row($arr,10);
		
		$pdf->SetXY(470, 55);
		$pdf->Cell(110,15,'','1',1,'Q');
		$pdf->SetXY(470, 57);
		$pdf->SetFont('Arial','',9);
		
		$pdf->SetWidths(array(55,65));
		$pdf->SetAligns(array("L","L"));
		//$arr=array($arr1[$i]);
		$arr=array('Pagina No. ',$pag);
		$pdf->Row($arr,10);
	}
	//otro header
	function Header2($pdf,$Numero,$TipoComp,$Fecha,$pag)
	{
		$pdf->SetFont('Arial','B',30);
		$x=31;
		$pdf->SetXY($x, 20);
		if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social'])
		{
			$pdf->Cell(555,70,'','1',1,'Q');
		}
		else
		{
			$pdf->Cell(555,60,'','1',1,'Q');
		}
		$x=41;
		$pdf->SetXY($x, 20);
		//$pdf->SetWidths(array(250));
		if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
			//si es jpg
			$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
			if (@getimagesize($src)) 
			{ 
				$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.jpg',40,22,80,40,'','https://www.discoversystem.com');
			}
			else
			{
				//si es gif
				$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
				if (@getimagesize($src)) 
				{ 
					$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.gif',40,22,80,40,'','https://www.discoversystem.com');
				}
				else
				{
					//si es png
					$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
					if (@getimagesize($src)) 
					{ 
						$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
					}
				}
			}
		}
		else
		{
			$logo="diskcover";
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
		}
		/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		}
		else
		{
			$logo="diskcover";
		}*/
		//echo __DIR__ . '/../../img/logotipos/'.$logo.'.png';
		//$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
		//si existe png
		/*if (is_file(__DIR__ . '/../../img/logotipos/'.$logo.'.png')) 
		{
			echo " entro 1 ";
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
		}
		//si existe jpg
		if (is_file(__DIR__ . '/../../img/logotipos/'.$logo.'.jpg')) 
		{
			echo " entro 2 ";
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.jpg',40,22,80,40,'','https://www.discoversystem.com');
		}
		//si existe gif
		if (is_file(__DIR__ . '/../../img/logotipos/'.$logo.'.gif')) 
		{
			echo " entro 3 ";
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.gif',40,22,80,40,'','https://www.discoversystem.com');
		}
		die();*/
		/*$pdf->SetWidths(array(65,215,65,65,60,55));
		$pdf->SetAligns(array("L","L","R","R","R","R"));
		//$arr=array($arr1[$i]);
		$arr=array($arr1[$i],$arr3[$i],$arr5[$i],
		$arr7[$i],$arr8[$i],$arr10[$i]);
		$pdf->Row($arr,10,1);*/
		$pdf->SetFont('Arial','B',12);
		$pdf->SetXY(180, 25);
		$pdf->SetWidths(array(250));
		$pdf->SetAligns(array("C",));
		//$arr=array($arr1[$i]);
		$arr=array($_SESSION['INGRESO']['Nombre_Comercial']);
		$pdf->Row($arr,10);
		$y=25;
		//si razon social es distinto de nombre comercial imprimir
		if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social'])
		{
			$pdf->SetXY(180, 35);
			$pdf->SetWidths(array(250));
			$pdf->SetAligns(array("C",));
			//$arr=array($arr1[$i]);
			$pdf->SetFont('Arial','B',6);
			$arr=array($_SESSION['INGRESO']['Razon_Social']);
			$pdf->Row($arr,10);
			$y=$y+10;
		}
		$y=$y+10;
		$pdf->SetXY(180, $y);
		$pdf->SetWidths(array(250));
		$pdf->SetAligns(array("C",));
		//$arr=array($arr1[$i]);
		$pdf->SetFont('Arial','B',10);
		$arr=array('R.U.C '.$_SESSION['INGRESO']["RUC"]);
		$pdf->Row($arr,10);
		$y=$y+10;
		$pdf->SetXY(180, $y);
		$pdf->SetWidths(array(270));
		$pdf->SetAligns(array("C",));
		//$arr=array($arr1[$i]);
		$pdf->SetFont('Arial','B',6);
		$arr=array($_SESSION['INGRESO']['Direccion'].' - '.$_SESSION['INGRESO']['Telefono1'].' / '.$_SESSION['INGRESO']['FAX']);
		$pdf->Row($arr,10);
		$y=$y+20;
		$pdf->SetXY(180, $y);
		$pdf->SetWidths(array(250));
		$pdf->SetAligns(array("C",));
		//$arr=array($arr1[$i]);
		$pdf->SetFont('Arial','B',18);
		if($TipoComp=='ERRORES')
		{
			$arr=array('ERRORES');
		}
		$pdf->Row($arr,10);
		//agregamos la informacion en los laterales izquierdo
		$pdf->SetXY(470, 25);
		$pdf->Image(__DIR__ . '/../../img/logotipos/DEFAULT.png',540,22,30,18,'','https://www.discoversystem.com');
		//$pdf->Cell(110,15,'','1',1,'Q');
		$pdf->SetXY(470, 27);
		$pdf->SetFont('Arial','B',8);
		//92
		$pdf->SetWidths(array(28));
		$pdf->SetAligns(array("L"));
		
		$Fecha1 = explode("-", $Fecha);
		//echo $pdf->generaCeros($Numero,8);
		//die();
		$arr=array('Hora: ');
		$pdf->Row($arr,10);
		
		$pdf->SetXY(498, 27);
		$pdf->SetFont('Arial','',8);
		$pdf->SetWidths(array(80));
		$pdf->SetAligns(array("L"));
	
		$arr=array(date("H:i:s"));
		$pdf->Row($arr,10);
		
		$pdf->SetXY(470, 37);
		$pdf->SetFont('Arial','B',8);
		
		$pdf->SetWidths(array(50));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array('Pagina No.');
		$pdf->Row($arr,10);
		
		$pdf->SetXY(520, 37);
		$pdf->SetFont('Arial','',8);
		
		$pdf->SetWidths(array(35));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array($pdf->generaCeros($pag,4));
		$pdf->Row($arr,10);
		
		$pdf->SetXY(470, 47);
		$pdf->SetFont('Arial','B',8);
		
		$pdf->SetWidths(array(35));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array('Fecha: ');
		$pdf->Row($arr,10);
		
		$pdf->SetXY(505, 47);
		$pdf->SetFont('Arial','',8);
		
		$pdf->SetWidths(array(65));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array($Fecha);
		$pdf->Row($arr,10);
		
		$pdf->SetXY(470, 57);
		$pdf->SetFont('Arial','B',8);
		
		$pdf->SetWidths(array(45));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array('Usuario: ');
		$pdf->Row($arr,10);
		
		$pdf->SetXY(515, 57);
		$pdf->SetFont('Arial','',8);
		
		$pdf->SetWidths(array(85));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array($_SESSION['INGRESO']['Nombre']);
		$pdf->Row($arr,10);
	}
	//pie de pagina
	function Footer1($pdf,$usuario,$x, $y)
	{
		//pie de pagina
		$pdf->SetXY($x, $y);
		$pdf->Cell(111,30,'','1',1,'Q');
		$pdf->SetXY($x+2, $y+2);
		$pdf->SetWidths(array(107));
		$pdf->SetAligns(array("C"));
		//$arr=array($arr1[$i]);
		$arr=array('COTIZACION');
		$pdf->Row($arr,10);
		//usuario
		$pdf->SetFont('Arial','',7);
		$pdf->SetXY($x+111, $y);
		$pdf->Cell(111,17,'','1',1,'Q');
		$pdf->SetXY($x+111, $y+17);
		$pdf->Cell(111,13,'','1',1,'Q');
		$pdf->SetXY($x+113, $y+2);
		$pdf->SetWidths(array(107));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array($usuario);
		$pdf->Row($arr,10);
		//elaborado por
		$pdf->SetXY($x+115, $y+17);
		$pdf->SetWidths(array(107));
		$pdf->SetAligns(array("C"));
		//$arr=array($arr1[$i]);
		$arr=array("Elaborado por");
		$pdf->Row($arr,10);
		//contador
		$pdf->SetFont('Arial','',7);
		$pdf->SetXY($x+111+111, $y);
		$pdf->Cell(111,17,'','1',1,'Q');
		$pdf->SetXY($x+111+111, $y+17);
		$pdf->Cell(111,13,'','1',1,'Q');
		$pdf->SetXY($x+113+111, $y+2);
		$pdf->SetWidths(array(107));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array('');
		$pdf->Row($arr,10);
		//Contador
		$pdf->SetXY($x+115+111, $y+17);
		$pdf->SetWidths(array(107));
		$pdf->SetAligns(array("C"));
		//$arr=array($arr1[$i]);
		$arr=array("Contador");
		$pdf->Row($arr,10);
		//aprobado
		$pdf->SetFont('Arial','',7);
		$pdf->SetXY($x+111+111+111, $y);
		$pdf->Cell(111,17,'','1',1,'Q');
		$pdf->SetXY($x+111+111+111, $y+17);
		$pdf->Cell(111,13,'','1',1,'Q');
		$pdf->SetXY($x+113+111+111, $y+2);
		$pdf->SetWidths(array(107));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array('');
		$pdf->Row($arr,10);
		//aprobado
		$pdf->SetXY($x+115+111+111, $y+17);
		$pdf->SetWidths(array(107));
		$pdf->SetAligns(array("C"));
		//$arr=array($arr1[$i]);
		$arr=array("Aprobado por");
		$pdf->Row($arr,10);
		//conforme
		$pdf->SetFont('Arial','',7);
		$pdf->SetXY($x+111+111+111+111, $y);
		$pdf->Cell(111,17,'','1',1,'Q');
		$pdf->SetXY($x+111+111+111+111, $y+17);
		$pdf->Cell(111,13,'','1',1,'Q');
		$pdf->SetXY($x+113+111+111+111, $y+2);
		$pdf->SetWidths(array(107));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array('');
		$pdf->Row($arr,10);
		//conforme
		$pdf->SetXY($x+115+111+111+111, $y+17);
		$pdf->SetWidths(array(107));
		$pdf->SetAligns(array("C"));
		//$arr=array($arr1[$i]);
		$arr=array("Conforme");
		$pdf->Row($arr,10);
	}
	//agregar ceros a cadena a la izquierda
	function generaCeros($numero,$largo_maximo=null){
		 //obtengop el largo del numero
		 $largo_numero = strlen($numero);
		 //especifico el largo maximo de la cadena
		 if($largo_maximo==null)
		 {
			$largo_maximo = 7;
		 }
		 //tomo la cantidad de ceros a agregar
		 $agregar = $largo_maximo - $largo_numero;
		 //agrego los ceros
		 for($i =0; $i<$agregar; $i++){
		 $numero = "0".$numero;
		 }
		 //retorno el valor con ceros
		 return $numero;
	}
}//Fin de la clase

//imprimir errores mayorizacion
/* $stmt= consulta 
   $id codigo unico para error $formato formato del reporte aqui es pdf
   $nombre_archivo ruta y nombre del archivo xml generado $va 0 para saber si lee de una variable o 1 un archivo xml
   $imp1 para saber si se descarga o no null o 1 descarga 0 es para temp y adjuntar al correo
 */
function imprimirDocERRORPDF($stmt,$id=null,$formato=null,$nombre_archivo=null,$va=null,$imp1=null)
{
	$pdf = new PDF('P','pt','LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();
	$x=31;
	if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social'])
	{
		$y=90;
	}
	else
	{
		$y=80;
	}
	$pdf->Header2($pdf,'vvv','ERRORES',date('d/m/Y'),'1');
	$pdf->SetXY($x, $y);
	
	$pdf->SetXY($x+2, $y+2);
	
	$pdf->SetFont('Arial','B',9);
	
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		$pdf->SetX($x+2);
		$pdf->SetFont('Arial','',9);
		$pdf->SetWidths(array(549));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array($row[0]);
		$pdf->Row($arr,10);
		$y=$y+10;
	}
	//echo '1';
	//die();
	if($imp1==null or $imp1==1)
	{
		$pdf->Output();
	}
	if($imp1==0)
	{
		//$pdf->Output('TEMP/'.$id.'.pdf','F'); 
	}
}

//imprimir comprobante diario
/* $stmt= variable con datos xml stmt2 transacciones stmt4 las Retenciones del IVA stmt5 las Retenciones de la fuente
   stmt6 SubCtas $id id del campo $formato formato del reporte aqui es pdf
   $nombre_archivo ruta y nombre del archivo xml generado $va 0 para saber si lee de una variable o 1 un archivo xml
   $imp1 paqra saber si se descarga o no null o 1 descarga 0 es para temp y adjuntar al correo
   $stmt2_count = cantidad Listar las Transacciones. 
   $stmt4_count = cantidad Listar las Retenciones del IVA.
   $stmt5_count = cantidad las Retenciones de la Fuente.
   $stmt6_count = cantidad Llenar SubCtas
   $stmt1 cabecera */
 function imprimirCD($stmt, $stmt2, $stmt4, $stmt5, $stmt6, $stmt1, $id=null,$formato=null,$nombre_archivo=null,$va=null,$imp1=null,
 $stmt2_count=null,$stmt4_count=null,$stmt5_count=null,$stmt6_count=null)
{

	$pdf = new PDF('P','pt','LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();
	

	   $TipoComp =  $stmt[0]['TP']; // $row[2];
		$Numero = $stmt[0]['Numero']; // $row[3];
		$Fecha =  $stmt[0]['Fecha']->format('Y-m-d'); // $row[4]->format('Y-m-d');
		$Concepto= $stmt[0]['Concepto']; // $row[7];
		$efectivo1= $stmt[0]['Efectivo']; // $row[9];
		$Monto_Total= $stmt[0]['Monto_Total']; // $row[10];


		$t = $stmt1[0]['T'];    // $row1[2];
		$Fecha = $stmt1[0]['Fecha']->format('Y-m-d');    // $row1[5]->format('Y-m-d');
		$codigoB = $stmt1[0]['Codigo_B'];    // $row1[6];
		$beneficiario = $stmt1[0]['Cliente'];    // $row1[26];
		$concepto1 = $stmt1[0]['Concepto'];    // $row1[8];
		$efectivo = number_format($stmt1[0]['Efectivo'],2, '.', ',');
		$est="Normal";
		$cliente = $stmt1[0]['Cliente'];    // $row1[26];

		// print_r($stmt1);die();
		$ruc_ci = $stmt1[0]['CI_RUC'];    // $row1[21];
		if($t == 'A')
		{
			$est="ANULADO";
		}
		$usuario= $stmt1[0]['Nombre_Completo']; 

	$sumdb=0;
	$sumcr=0;
	$cantidad_reg=$stmt2_count+$stmt6_count;
	// if($cantidad_reg<35)
	// {
		//para sacar parte del header
		$i=0;
		$pag=1;
		$pdf->Header1($pdf,$Numero,$TipoComp,$Fecha,$pag);
		//logo
		$i=0;
		if($va==1)
		{
			//$autorizacion = simplexml_load_file($nombre_archivo);
		}
		else
		{
			//$stmt = str_replace("﻿", "", $stmt);
			//$autorizacion =simplexml_load_string($stmt);
		}
		$x=31;
		if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social'])
		{
			$y=90;
		}
		else
		{
			$y=80;
		}
		//concepto
		$pdf->SetXY($x, $y);
		$pdf->Cell(555,30,'','1',1,'Q');
		
		$pdf->SetXY($x+2, $y+2);
		
		$pdf->SetFont('Arial','B',9);
		
		$pdf->SetWidths(array(65));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array('Concepto de: ');
		$pdf->Row($arr,10);
		
		$pdf->SetXY($x+2+65, $y+2);
		
		$pdf->SetFont('Arial','',9);
		
		$pdf->SetWidths(array(488));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array($Concepto);
		$pdf->Row($arr,10);
		//titulo
		$y=$y+30;
		$pdf->SetXY($x, $y);
		$pdf->Cell(555,15,'','1',1,'Q');
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($x+2, $y+2);
		$pdf->SetWidths(array(535));
		$pdf->SetAligns(array("C"));
		//$arr=array($arr1[$i]);
		$arr=array('CONTABILIZACION');
		$pdf->Row($arr,10);
		//saber si es una o mas pagina
		//echo ' '.$stmt2_count.' '.$stmt6_count.' ';
		/*****agregar a funcion***********/
		//cabecera de tabla
		$y=$y+15;
		$pdf->SetXY($x, $y);
		
		$pdf->Cell(97,15,'','1',1,'Q');
		$pdf->SetXY($x+97, $y);
		$pdf->Cell(189,15,'','1',1,'Q');
		$pdf->SetXY($x+189+97, $y);
		$pdf->Cell(97,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97+86, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetXY($x, $y+2);
		$pdf->SetWidths(array(97,189,97,86,86));
		$pdf->SetAligns(array("C","C","C","C","C"));
		//$arr=array($arr1[$i]);
		$arr=array("CODIGO", "CONCEPTO", "PARCIAL M/E", "DEBE", "HABER");
		$pdf->Row($arr,10);		
		//------------------------------puede ser pa otro tipo de comprobante ------------------
		// print_r($stmt6);die();
		$ii = 0;
		foreach ($stmt6 as $key => $value) {

			$subc[$ii]['cta']= $value['Cta']; //$row1[0];
			$subc[$ii]['cliente']= $value['Cliente']; //$row1[3];
			$subc[$ii]['Fechav']= $value['Fecha_V']; //$row1[7];
			$subc[$ii]['debe']= $value['Debitos']; //$row1[5];
			$subc[$ii]['haber']= $value['Creditos']; //$row1[6];
			$subc[$ii]['No']= $value['Factura']; //$row1[2];
			$subc[$ii]['vp']= $value['Prima']; //$row1[9];
			
			$ii++;
		}
		//-------------------------------------------------------------------------------------
		$pdf->SetXY($x, $pdf->GetY()+6);		
		$y_detalle = $pdf->GetY();
		$x_detalle = $pdf->GetX();

		$status ='';		
		if(count($stmt2)>0)
		{
			foreach ($stmt2 as $key => $value) {
				$parc='';$debe='-';$haber='-';
				$arr=array();
				if($value['Parcial_ME']!=0 and $value['Parcial_ME']!='0.00')
				{
					$parc = number_format($value['Parcial_ME'],2, '.', ',');
				}
				if($value['Debe']!=0 and $value['Debe']!='0.00')
				{
					$sumdb=$sumdb+$value['Debe'];
					$debe = number_format($value['Debe'],2, '.', '');
				}
				if($value['Haber']!=0 and $value['Haber']!='0.00')
				{
					$sumcr=$sumcr+$value['Haber'];
					$haber = number_format($value['Haber'],2, '.', '');
				}
				$pdf->SetAligns(array("L","L","R","R","R"));
				$arr=array($value['Cta'], $value['Cuenta'], $parc,$debe,$haber);
				$pdf->Row($arr,10,'LR');
				$pdf->SetXY($x, $pdf->GetY());	
				//en caso de haber sub modulos se agrega
				if($value['Detalle']!='.' && $value['Detalle']!='')
				{

					$pdf->SetFont('Arial','',8);
					$arr=array("",$value['Detalle'],'','','');
					$pdf->Row($arr,10,'LR');
					$pdf->SetXY($x, $pdf->GetY());	
					$pdf->SetFont('Arial','',10);
				}				
				// print_r($pdf->GetPageHeight());
				

			}
			 
		}

		$y_detalle_fin = $pdf->GetY();
		// $pdf->SetXY($x_detalle-2, $y_detalle-3);	
		// $pdf->Cell(97,$y_detalle_fin-$y_detalle+5,'',1);
		// $pdf->Cell(189,$y_detalle_fin-$y_detalle+5,'',1);
		// $pdf->Cell(97,$y_detalle_fin-$y_detalle+5,'',1);
		// $pdf->Cell(86,$y_detalle_fin-$y_detalle+5,'',1);		
		// $pdf->Cell(86,$y_detalle_fin-$y_detalle+5,'',1);


		$pdf->SetXY($x+2, $y_detalle_fin+6);	
		$y_total = $pdf->GetY();
		$x_total = $pdf->GetX();

		//si esta anulado imprimir
		if($status=='A')
		{
			$pdf->SetXY($x+8, $y+2);
			$pdf->SetFont('Arial','B',14);
			$pdf->SetFillColor(255, 255, 255); 
			$pdf->Cell(120,25,'','1',1,'Q',TRUE);
			//$pdf->cabeceraHorizontal(array(' '),$x+8,$y,120,30,20,5);
			//$pdf->cabeceraHorizontal(array(' '),$x+10,$y+5,115,23,20,5);
			$pdf->SetXY($x+10, $y+5);
			$pdf->Cell(115,20,'','1',1,'Q',TRUE);
			$pdf->SetXY($x+11, $y+7);
			$pdf->SetWidths(array(120));
			$pdf->SetAligns(array("C"));
			//$arr=array($arr1[$i]);
			//$pdf->cabeceraHorizontal(array(' '),$x,$y,96,15,20,5);
			$arr=array('ANULADO');
			$pdf->Row($arr,15);
			//$y=$y+15;
		}

		$pdf->SetWidths(array(383,84,84));
		$pdf->SetAligns(array("R","R","R"));
		//$arr=array($arr1[$i]);
		$arr=array('TOTALES',  number_format($sumdb,2, '.', ','), number_format($sumcr,2, '.', ','));
		$pdf->Row($arr,10);
		$y_totales_fin = $pdf->GetY();
		//------------------------------marcos total ----------------
		$pdf->SetXY($x_detalle, $y_total-4);	
		$pdf->Cell(97,$y_totales_fin-$y_total+5,'',1);
		$pdf->Cell(189,$y_totales_fin-$y_total+5,'',1);
		$pdf->Cell(97,$y_totales_fin-$y_total+5,'',1);
		$pdf->Cell(86,$y_totales_fin-$y_total+5,'',1);		
		$pdf->Cell(86,$y_totales_fin-$y_total+5,'',1);
		//-----------------------------------------------------------

		$pdf->SetXY($x+2, $y_totales_fin);	
		$pdf->Footer1($pdf,$usuario,$x, $y_totales_fin);
		
	/*}
	else
	{
		// print_r('expression');die();
		//varias paginas
		//select * from lista_empresas where RUC_CI_NIC='1791863798001'
		//delete from lista_empresas where ID=175
		$pagi=ceil($cantidad_reg/35);
		//echo ' ddd '.$pagi;
		//die();
		//obtenemos los datos
		$i=0;
		$cta=array();
		$conc=array();
		$parc=array();
		$debe=array();
		$haber=array();
		$detalle = array();
		$status = array();
		//llenamos los detalles 
		$ii=0;
		$subc=array();
		foreach ($stmt6 as $key => $value)
		{
			$subc[$ii]['cta']= $value['Cta']; //$row1[0];
			$subc[$ii]['cliente']= $value['Cliente']; //$row1[3];
			$subc[$ii]['Fechav']= $value['Fecha_V']; //$row1[7];
			$subc[$ii]['debe']= $value['Debitos']; //$row1[5];
			$subc[$ii]['haber']= $value['Creditos']; //$row1[6];
			$subc[$ii]['No']= $value['Factura']; //$row1[2];
			$subc[$ii]['vp']= $value['Prima']; //$row1[9];
			$ii++;
		}
		foreach ($stmt2 as $key => $row1)
		{
			$cta[$i] = $row1['Cta'];
			$conc[$i] = $row1['Cuenta'];
			$parc[$i]='';
			$debe[$i]='';
			$haber[$i]='';
			if($row1['Parcial_ME']!=0 and $row1['Parcial_ME']!='0.00')
			{
				$parc[$i] = number_format($row1['Parcial_ME'],2, '.', ',');
			}
			if($row1['Debe']!=0 and $row1['Debe']!='0.00')
			{
				$sumdb=$sumdb+$row1['Debe'];
				$debe[$i] = number_format($row1['Debe'],2, '.', ',');
			}
			if($row1['Haber']!=0 and $row1['Haber']!='0.00')
			{
				$sumcr=$sumcr+$row1['Haber'];
				$haber[$i] = number_format($row1['Haber'],2, '.', ',');
			}
			$detalle[$i] = $row1['Detalle'];
			$status[$i] = $row1['T'];
			
			$i++;
		}
		/*****agregar a funcion***********/
	/*	$pag=1;
		for($i=0;$i<$pagi-1;$i++)
		{
			//echo $i.'<br>';
			$pdf->Header1($pdf,$Numero,$TipoComp,$Fecha,$pag);
			$pag=$pag+1;
			$x=31;
			if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social'])
			{
				$y=90;
			}
			else
			{
				$y=80;
			}
			//concepto
			$pdf->SetXY($x, $y);
			$pdf->Cell(555,30,'','1',1,'Q');
			
			$pdf->SetXY($x+2, $y+2);
			
			$pdf->SetFont('Arial','B',9);
			
			$pdf->SetWidths(array(65));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$arr=array('Concepto de: ');
			$pdf->Row($arr,10);
			
			$pdf->SetXY($x+2+65, $y+2);
			
			$pdf->SetFont('Arial','',9);
			
			$pdf->SetWidths(array(468));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$arr=array($Concepto);
			$pdf->Row($arr,10);
			//titulo
			$y=$y+30;
			$pdf->SetXY($x, $y);
			$pdf->Cell(555,15,'','1',1,'Q');
			$pdf->SetFont('Arial','B',10);
			$pdf->SetXY($x+2, $y+2);
			$pdf->SetWidths(array(535));
			$pdf->SetAligns(array("C"));
			//$arr=array($arr1[$i]);
			$arr=array('CONTABILIZACION');
			$pdf->Row($arr,10);
			/*****agregar a funcion***********/
			//cabecera de tabla
		/*	$y=$y+15;
			$pdf->SetXY($x, $y);
			$pdf->Cell(97,15,'','1',1,'Q');
			$pdf->SetXY($x+97, $y);
			$pdf->Cell(189,15,'','1',1,'Q');
			$pdf->SetXY($x+189+97, $y);
			$pdf->Cell(97,15,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97, $y);
			$pdf->Cell(86,15,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97+86, $y);
			$pdf->Cell(86,15,'','1',1,'Q');
			$pdf->SetXY($x+2, $y+2);
			$pdf->SetWidths(array(97,189,97,86,86));
			$pdf->SetAligns(array("C","C","C","C","C"));
			//$arr=array($arr1[$i]);
			$arr=array("CODIGO", "CONCEPTO", "PARCIAL M/E", "DEBE", "HABER");
			$pdf->Row($arr,10);
			//detalle comprobante
			$y=$y+15;
			$pdf->SetXY($x, $y);
			$pdf->Cell(97,510,'','1',1,'Q');
			$pdf->SetXY($x+97, $y);
			$pdf->Cell(189,510,'','1',1,'Q');
			$pdf->SetXY($x+189+97, $y);
			$pdf->Cell(97,510,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97, $y);
			$pdf->Cell(86,510,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97+86, $y);
			$pdf->Cell(86,510,'','1',1,'Q');
			$pdf->SetFont('Arial','',9);
			$pdf->SetXY($x+2, $y+2);
			//mostramos registro
			if($i==0)
			{
				$j1=0;
				$limite=23;
			}
			else
			{
				$j1=$j1+23;
				$limite=$limite+23;
			}
			for($j=$j1;$j<$limite;$j++)
			{
				if(count($cta)<=$j)
				{
					break;
				}
				$pdf->SetX($x+2);
				$pdf->SetFont('Arial','',9);
				$pdf->SetWidths(array(97,189,95,84,84));
				$pdf->SetAligns(array("L","L","R","R","R"));
				//$arr=array($arr1[$i]);
				$arr=array($cta[$j], $conc[$j], $parc[$j], $debe[$j],$haber[$j]);
				$pdf->Row($arr,10);
				$y=$y+10;
				if($detalle[$j]<>'.')
				{
					$pdf->SetFont('Arial','',8);
					//$pdf->SetXY($x+2, $y+2);
					$pdf->SetWidths(array(97,180,95,84,84));
					$pdf->SetAligns(array("L","L","R","R","R"));
					//$arr=array($arr1[$i]);
					$arr=array('', $detalle[$j], '', '', '');
					$pdf->Row($arr,10);
					$y=$y+10;
				}
				//verificamos si hay mas detalles
				for($ii=0;$ii<count($subc);$ii++)
				{
					if($cta==$subc[$ii]['cta'])
					{
						$cliente = $subc[$ii]['cliente'];
						$Fechav = $subc[$ii]['Fechav']->format('Y-m-d');
						$debe='';
						$haber='';
						if($subc[$ii]['debe']!=0 and $subc[$ii]['debe']!='0.00')
						{
							$debe = number_format($subc[$ii]['debe'],2, '.', ',');
						}
						if($subc[$ii]['haber']!=0 and $subc[$ii]['haber']!='0.00')
						{
							$haber = number_format($subc[$ii]['haber'],2, '.', ',');
						}
						$pdf->SetX($x+2);
						$pdf->SetFont('Arial','I',7);
						//echo " aqui ";
						//	die();
						//$pdf->SetXY($x+2, $y+2);
						$pdf->SetWidths(array(97,94,94,48, 48,85,84));
						$pdf->SetAligns(array("L","L","L","R","R","R","R"));
						//$arr=array($arr1[$i]);
						if($subc[$ii]['No']<>'.')
						{
							$arr=array('', $cliente, 'No. '.$pdf->generaCeros($subc[$ii]['No'],7), $debe, $haber, '', '');
						}
						else
						{
							if($subc[$ii]['vp']<>0)
							{
								$arr=array('', $cliente, 'Valor Prima ', $subc[$ii]['vp'], $haber, '', '');
							}
							else
							{
								$arr=array('', $cliente, 'Venc. '.$Fechav, $debe, $haber, '', '');
							}
						}
						
						$pdf->Row($arr,10);
						$y=$y+10;
					}
				}
			}
			/*$pdf->SetWidths(array(97,189,97,86,86));
			$pdf->SetAligns(array("C","C","C","C","C"));
			//$arr=array($arr1[$i]);
			$arr=array('xxx', "CONCEPTO", "PARCIAL M/E", "DEBE", "HABER");
			$pdf->Row($arr,10);*/
			//$y=$y+10;
			//totales
			//$pos=150-$cantidad_reg*10;
			//echo $y;
			//die();
			//$y=$y+$pos-10;
			
		/*	if($i<($pagi-2))
			{
				$y=510-10+160;
				$pdf->SetXY($x+2, $y+2);
				$pdf->SetFont('Arial','I',7);
				//$pdf->SetXY($x+2, $y+2);
				$pdf->SetWidths(array(150));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array('CONTINUARA EN LA SIGUIENTE PAGINA...');
				$pdf->Row($arr,10);
				$pdf->AddPage();
			}
		}
		$pdf->SetXY($x+2, $y+2);
		
		//si esta anulado imprimir
		if($status=='A')
		{
			$pdf->SetXY($x+8, $y+2);
			$pdf->SetFont('Arial','B',14);
			$pdf->SetFillColor(255, 255, 255); 
			$pdf->Cell(120,25,'','1',1,'Q',TRUE);
			//$pdf->cabeceraHorizontal(array(' '),$x+8,$y,120,30,20,5);
			//$pdf->cabeceraHorizontal(array(' '),$x+10,$y+5,115,23,20,5);
			$pdf->SetXY($x+10, $y+5);
			$pdf->Cell(115,20,'','1',1,'Q',TRUE);
			$pdf->SetXY($x+11, $y+7);
			$pdf->SetWidths(array(120));
			$pdf->SetAligns(array("C"));
			//$arr=array($arr1[$i]);
			//$pdf->cabeceraHorizontal(array(' '),$x,$y,96,15,20,5);
			$arr=array('ANULADO');
			$pdf->Row($arr,15);
			//$y=$y+15;
		}
		$y=510-10+160;
		$pdf->SetXY($x, $y);
		$pdf->Cell(383,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97+86, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetFont('Arial','',9);
		$pdf->SetXY($x+2, $y+2);
		$pdf->SetWidths(array(383,84,84));
		$pdf->SetAligns(array("R","R","R"));
		//$arr=array($arr1[$i]);
		$arr=array('TOTALES',  number_format($sumdb,2, '.', ','), number_format($sumcr,2, '.', ','));
		$pdf->Row($arr,10);
		$y=$y+15;
		$pdf->Footer1($pdf,$usuario,$x, $y);
		//die();
	}*/
	//$pdf->AddPage();
	//datos cliente
	/******************/
	/******************/
	/**************110****/
	//$pdf->cabeceraHorizontal(array(' '),20,185,574,90,20,5);
	// $pdf->SetFont('Arial','B',6);
	// $pdf->SetXY(41, 149);
	// //para posicion automatica
	// $y=35;
	
	if($imp1==null or $imp1==1)
	{
		$pdf->Output();
	}
	if($imp1==0)
	{
		$pdf->Output('TEMP/'.$id.'.pdf','F'); 
	}
}
//imprimir comprobante egreso
/* $stmt= variable con datos xml stmt2 transacciones stmt4 las Retenciones del IVA stmt5 las Retenciones de la fuente
   stmt6 SubCtas $id id del campo $formato formato del reporte aqui es pdf
   $nombre_archivo ruta y nombre del archivo xml generado $va 0 para saber si lee de una variable o 1 un archivo xml
   $imp1 paqra saber si se descarga o no null o 1 descarga 0 es para temp y adjuntar al correo
   $stmt2_count = cantidad Listar las Transacciones. 
   $stmt4_count = cantidad Listar las Retenciones del IVA.
   $stmt5_count = cantidad las Retenciones de la Fuente.
   $stmt6_count = cantidad Llenar SubCtas
   $stmt1 cabecera */
 function imprimirCE($stmt, $stmt2, $stmt4, $stmt5, $stmt6, $stmt1, $stmt8, $id=null,$formato=null,$nombre_archivo=null,$va=null,$imp1=null,
 $stmt2_count=null,$stmt4_count=null,$stmt5_count=null,$stmt6_count=null, $stmt8_count=null)
{
	$pdf = new PDF('P','pt','LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();

	$TipoComp =  $stmt[0]['TP']; // $row[2];
	$Numero = $stmt[0]['Numero']; // $row[3];
	$Fecha =  $stmt[0]['Fecha']->format('Y-m-d'); // $row[4]->format('Y-m-d');
	$Concepto= $stmt[0]['Concepto']; // $row[7];
	$efectivo1= $stmt[0]['Efectivo']; // $row[9];
	$Monto_Total= $stmt[0]['Monto_Total']; // $row[10];


	$t = $stmt1[0]['T'];    // $row1[2];
	$Fecha = $stmt1[0]['Fecha']->format('Y-m-d');    // $row1[5]->format('Y-m-d');
	$codigoB = $stmt1[0]['Codigo_B'];    // $row1[6];
	$beneficiario = $stmt1[0]['Cliente'];    // $row1[26];
	$concepto1 = $stmt1[0]['Concepto'];    // $row1[8];
	$efectivo = number_format($stmt1[0]['Efectivo'],2, '.', ',');
	$est="Normal";
	$cliente = $stmt1[0]['Cliente'];    // $row1[26];
	$ruc_ci = $stmt1[0]['CI_RUC'];    // $row1[21];
	if($t == 'A')
	{
		$est="ANULADO";
	}
	$usuario= $stmt1[0]['Nombre_Completo']; 

	$sumdb=0;
	$sumcr=0;
	if($stmt8_count<>0)
	{
		$cantidad_reg=$stmt2_count+($stmt8_count-1);
	}
	else
	{
		$cantidad_reg=$stmt2_count+4;
	}
	if($cantidad_reg<31)
	{
		//para sacar parte del header
		$i=0;
		$pag=1;
		$pdf->Header1($pdf,$Numero,$TipoComp,$Fecha,$pag);
		//logo
		$i=0;

		$x=31;
		if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social'])
		{
			$y=90;
		}
		else
		{
			$y=80;
		}
		//imprimir cantidad,cheque depositos
		
		//pagado a
		$pdf->SetXY($x, $y);
		$pdf->Cell(555,15,'','1',1,'Q');
		
		$pdf->SetXY($x+2, $y+2);
		
		$pdf->SetFont('Arial','B',9);
		
		$pdf->SetWidths(array(65));
		$pdf->SetAligns(array("L"));
		$arr=array('Pagado a: ');
		$pdf->Row($arr,10);
		
		$pdf->SetXY($x+2+65, $y+2);
		
		$pdf->SetFont('Arial','',9);
		
		$pdf->SetWidths(array(338));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array($cliente);
		$pdf->Row($arr,10);
		
		$pdf->SetXY($x+2+65+338, $y+2);
		
		$pdf->SetFont('Arial','B',9);
		
		$pdf->SetWidths(array(60));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array("R.U.C / C.I. ");
		$pdf->Row($arr,10);
		
		$pdf->SetXY($x+2+65+288+110, $y+2);
		
		$pdf->SetFont('Arial','',9);
		
		$pdf->SetWidths(array(90));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array($ruc_ci);
		$pdf->Row($arr,10);
		
		if($stmt8_count<>0 || $Monto_Total>0)
		{
			//cantidad a
			$y=$y+15;
			$pdf->SetXY($x, $y);
			$pdf->Cell(555,30,'','1',1,'Q');
			
			$pdf->SetXY($x+2, $y+2);
			
			$pdf->SetFont('Arial','B',9);
			
			$pdf->SetWidths(array(75));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$Monto_Total1=$num = NumerosEnLetras::convertir(number_format($Monto_Total,2, '.', ''),'',true,'Centavos', true);
			$arr=array('La cantidad de: ');
			$pdf->Row($arr,10);
			
			$pdf->SetXY($x+2+75, $y+2);
			
			$pdf->SetFont('Arial','',9);
			
			$pdf->SetWidths(array(478));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$arr=array($Monto_Total1);
			$pdf->Row($arr,10);
			$y=$y+30;
		}

		$i=0;

		if(count($stmt8)>0) {
			
			if($stmt8[0]['TC']=='BA')
			{
				$pdf->SetXY($x, $y);
				$pdf->Cell(555,30,'','1',1,'Q');
				
				$pdf->SetXY($x+2, $y+2);
				
				$pdf->SetFont('Arial','B',9);
				
				$pdf->SetWidths(array(65));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array('Cheque '.$_SESSION['INGRESO']['S_M'].'. ');
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+65, $y+2);
				
				$pdf->SetFont('Arial','',9);
				
				$pdf->SetWidths(array(70));
				$pdf->SetAligns(array("R"));
				
				$arr=array(number_format(($stmt8[0]['monto']),2, '.', ','));
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+135, $y+2);
				
				$pdf->SetFont('Arial','B',9);
				
				$pdf->SetWidths(array(63));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array(' Banco: ');
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+130+65, $y+2);
				
				$pdf->SetFont('Arial','',9);
				
				$pdf->SetWidths(array(190));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array($stmt8[0]['Cuenta']);
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+385, $y+2);
				
				$pdf->SetFont('Arial','B',9);
				
				$pdf->SetWidths(array(65));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array('Cheque No.');
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+385+65, $y+2);
				
				$pdf->SetFont('Arial','',9);
				
				$pdf->SetWidths(array(103));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array($stmt8[0]['Cheq_Dep']);
				$pdf->Row($arr,10);
			}
			if($stmt8[0]['TC']=='CJ')
			{
				$pdf->SetXY($x, $y);
				$pdf->Cell(555,30,'','1',1,'Q');
				
				$pdf->SetXY($x+2, $y+2);
				
				$pdf->SetFont('Arial','B',9);
				
				$pdf->SetWidths(array(65));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array('Efectivo '.$_SESSION['INGRESO']['S_M'].'. ');
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+65, $y+2);
				
				$pdf->SetFont('Arial','',9);
				
				$pdf->SetWidths(array(70));
				$pdf->SetAligns(array("R"));
				
				$arr=array(number_format(($stmt8[0]['monto']),2, '.', ','));
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+135, $y+2);
				
				$pdf->SetFont('Arial','B',9);
				
				$pdf->SetWidths(array(63));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array(' Caja: ');
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+130+65, $y+2);
				
				$pdf->SetFont('Arial','',9);
				
				$pdf->SetWidths(array(190));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array($stmt8[0]['Cuenta']);
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+385, $y+2);
				
				$pdf->SetFont('Arial','B',9);
				
				$pdf->SetWidths(array(65));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array('Retiro No.');
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+385+65, $y+2);
				
				$pdf->SetFont('Arial','',9);
				
				$pdf->SetWidths(array(103));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array($stmt8[0]['Cheq_Dep']);
				$pdf->Row($arr,10);
			}				
			$y=$y+15;
			// $i++;
		}
		
		/*--------------------------------------------*/
		
		
		//concepto
		$y=$y+15;
		$pdf->SetXY($x, $y);
		$pdf->Cell(555,30,'','1',1,'Q');
		
		$pdf->SetXY($x+2, $y+2);
		
		$pdf->SetFont('Arial','B',9);
		
		$pdf->SetWidths(array(65));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array('Concepto de: ');
		$pdf->Row($arr,10);
		
		$pdf->SetXY($x+2+65, $y+2);
		
		$pdf->SetFont('Arial','',9);
		
		$pdf->SetWidths(array(488));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array(mb_convert_encoding($Concepto, 'UTF-8'));
		$pdf->Row($arr,10);
		//titulo
		$y=$y+30;
		$pdf->SetXY($x, $y);
		$pdf->Cell(555,15,'','1',1,'Q');
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($x+2, $y+2);
		$pdf->SetWidths(array(535));
		$pdf->SetAligns(array("C"));
		//$arr=array($arr1[$i]);
		$arr=array('CONTABILIZACION');
		$pdf->Row($arr,10);
		$y=$y+15;
		$pdf->SetXY($x, $y);
		
		$pdf->Cell(97,15,'','1',1,'Q');
		$pdf->SetXY($x+97, $y);
		$pdf->Cell(189,15,'','1',1,'Q');
		$pdf->SetXY($x+189+97, $y);
		$pdf->Cell(97,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97+86, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetXY($x+2, $y+2);
		$pdf->SetWidths(array(97,189,97,86,86));
		$pdf->SetAligns(array("C","C","C","C","C"));
		//$arr=array($arr1[$i]);
		$arr=array("CODIGO", "CONCEPTO", "PARCIAL M/E", "DEBE", "HABER");
		$pdf->Row($arr,10);
		//detalle comprobante
		$y=$y+15;
		$pdf->SetXY($x, $y);
		if($cantidad_reg<=10)
		{
			$cantidad_reg1=15*10;
			if($stmt8_count<>0)
			{
				$pdf->Cell(97,105,'','1',1,'Q');
				$pdf->SetXY($x+97, $y);
				$pdf->Cell(189,105,'','1',1,'Q');
				$pdf->SetXY($x+189+97, $y);
				$pdf->Cell(97,105,'','1',1,'Q');
				$pdf->SetXY($x+97+189+97, $y);
				$pdf->Cell(86,105,'','1',1,'Q');
				$pdf->SetXY($x+97+189+97+86, $y);
				$pdf->Cell(86,105,'','1',1,'Q');
			}
			else
			{
				$pdf->Cell(97,165,'','1',1,'Q');
				$pdf->SetXY($x+97, $y);
				$pdf->Cell(189,165,'','1',1,'Q');
				$pdf->SetXY($x+189+97, $y);
				$pdf->Cell(97,165,'','1',1,'Q');
				$pdf->SetXY($x+97+189+97, $y);
				$pdf->Cell(86,165,'','1',1,'Q');
				$pdf->SetXY($x+97+189+97+86, $y);
				$pdf->Cell(86,165,'','1',1,'Q');
			}
		}
		else
		{
			$x1=$x;
			$y1=$y;
			
		}
		//llenamos los detalles 
		$ii=0;
		$subc=array();
		foreach ($stmt6 as $key => $value) {

			$subc[$ii]['cta']= $value['Cta']; //$row1[0];
			$subc[$ii]['cliente']= $value['Cliente']; //$row1[3];
			$subc[$ii]['Fechav']= $value['Fecha_V']; //$row1[7];
			$subc[$ii]['debe']= $value['Debitos']; //$row1[5];
			$subc[$ii]['haber']= $value['Creditos']; //$row1[6];
			$subc[$ii]['No']= $value['Factura']; //$row1[2];
			$subc[$ii]['vp']= $value['Prima']; //$row1[9];
			
			$ii++;
		}
		$pdf->SetXY($x+2, $y+2);
		$status ='';
		if(count($stmt2)>0)
		{
			
			$status = $stmt2[0]['T'];
			foreach ($stmt2 as $key => $value) {
				$parc='';$debe='-';$haber='-';
				$arr=array();
				if($value['Parcial_ME']!=0 and $value['Parcial_ME']!='0.00')
				{
					$parc = number_format($value['Parcial_ME'],2, '.', ',');
				}
				if($value['Debe']!=0 and $value['Debe']!='0.00')
				{
					$sumdb=$sumdb+$value['Debe'];
					$debe = number_format($value['Debe'],2, '.', '');
				}
				if($value['Haber']!=0 and $value['Haber']!='0.00')
				{
					$sumcr=$sumcr+$value['Haber'];
					$haber = number_format($value['Haber'],2, '.', '');
				}
				$pdf->SetAligns(array("L","L","R","R","R"));
				$arr=array($value['Cta'], $value['Cuenta'], $parc,$debe,$haber);
				$pdf->Row($arr,10,'LR');
				$pdf->SetXY($x, $pdf->GetY());	
				//en caso de haber sub modulos se agrega
				if($value['Detalle']!='.' && $value['Detalle']!='')
				{

					$pdf->SetFont('Arial','',8);
					$arr=array("",$value['Detalle'],'','','');
					$pdf->Row($arr,10,'LR');
					$pdf->SetXY($x, $pdf->GetY());	
					$pdf->SetFont('Arial','',10);
				}				
				// print_r($pdf->GetPageHeight());
				

			}
			
		}
		
		//si esta anulado imprimir
		if($status=='A')
		{
			$pdf->Image(__DIR__ . '/../../img/gif/ANULADO.GIF',$x+15,$pdf->GetY()-20,180,45);
		}
		$y = $pdf->GetY();

		if($cantidad_reg>10)
		{
			$pdf->SetXY($x1, $y1);
			//echo $pdf->GetY($y);
			//die();
			$cantidad_reg1=$cantidad_reg*10+($pdf->GetY($y)-80);
			$pdf->Cell(97,$cantidad_reg1,'','1',1,'Q');
			$pdf->SetXY($x1+97, $y1);
			$pdf->Cell(189,$cantidad_reg1,'','1',1,'Q');
			$pdf->SetXY($x1+189+97, $y1);
			$pdf->Cell(97,$cantidad_reg1,'','1',1,'Q');
			$pdf->SetXY($x1+97+189+97, $y1);
			$pdf->Cell(86,$cantidad_reg1,'','1',1,'Q');
			$pdf->SetXY($x1+97+189+97+86, $y1);
			$pdf->Cell(86,$cantidad_reg1,'','1',1,'Q');
			$pos=$cantidad_reg1-$cantidad_reg*10;
		}
		else
		{
			$pos=$cantidad_reg1-10;
		}
		//totales
		
		//echo $y.' '.$pos.' '.$pdf->GetY($y);
		//die();
		if($cantidad_reg>10)
		{
			$y=$y+$pos-30;
		}
		else
		{
			$y=$y+$pos-10;
			$y=330;
		}
		$y = $y+87;
		$pdf->SetXY($x, $y);
		$pdf->Cell(383,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97+86, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetFont('Arial','',9);
		$pdf->SetXY($x+2, $y+2);
		$pdf->SetWidths(array(383,84,84));
		$pdf->SetAligns(array("R","R","R"));
		//$arr=array($arr1[$i]);
		$arr=array('TOTALES',  number_format($sumdb,2, '.', ','), number_format($sumcr,2, '.', ','));
		$pdf->Row($arr,10);
		$y=$y+15;
		$pdf->Footer1($pdf,$usuario,$x, $y);
		
	}
	else
	{
		$pagi=ceil($cantidad_reg/35);
		//obtenemos los datos
		$i=0;
		$cta=array();
		$conc=array();
		$parc=array();
		$debe=array();
		$haber=array();
		$detalle = array();
		$status = "";
		//llenamos los detalles 
		$ii=0;
		$subc=array();
		foreach ($stmt6 as $key => $row1)
		{
			$subc[$ii]['cta']=$row1['Cta']; 
			$subc[$ii]['cliente']=$row1['Cliente'];
			$subc[$ii]['Fechav']=$row1['Fecha_V'];
			$subc[$ii]['debe']=$row1['Debitos'];
			$subc[$ii]['haber']=$row1['Creditos'];
			$subc[$ii]['No']=$row1['Factura'];
			$subc[$ii]['vp']=$row1['Prima'];

			$ii++;
		}
		foreach ($stmt2 as $key => $row1)
		{
			$cta[$i] = $row1['Cta'];
			$conc[$i] = $row1['Cuenta'];
			$parc[$i]='';
			$debe[$i]='';
			$haber[$i]='';
			if($row1['Parcial_ME']!=0 and $row1['Parcial_ME']!='0.00')
			{
				$parc[$i] = number_format($row1[2],2, '.', ',');
			}
			if($row1['Debe']!=0 and $row1['Debe']!='0.00')
			{
				$sumdb=$sumdb+$row1['Debe'];
				$debe[$i] = number_format($row1[3],2, '.', ',');
			}
			if($row1['Haber']!=0 and $row1['Haber']!='0.00')
			{
				$sumcr=$sumcr+$row1['Haber'];
				$haber[$i] = number_format($row1['Haber'],2, '.', ',');
			}
			$detalle[$i] = $row1['Detalle'];
			$status = $row1['T'];
			
			$i++;
		}
		/*****agregar a funcion***********/
		$pag=1;
		for($i=0;$i<$pagi-1;$i++)
		{
			//echo $i.'<br>';
			$pdf->Header1($pdf,$Numero,$TipoComp,$Fecha,$pag);
			$pag=$pag+1;
			$x=31;
			if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social'])
			{
				$y=90;
			}
			else
			{
				$y=80;
			}
			//pagado a
			$pdf->SetXY($x, $y);
			$pdf->Cell(555,15,'','1',1,'Q');
			
			$pdf->SetXY($x+2, $y+2);
			
			$pdf->SetFont('Arial','B',9);
			
			$pdf->SetWidths(array(65));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$arr=array('Pagado a: ');
			$pdf->Row($arr,10);
			
			$pdf->SetXY($x+2+65, $y+2);
			
			$pdf->SetFont('Arial','',9);
			
			$pdf->SetWidths(array(338));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$arr=array($cliente);
			$pdf->Row($arr,10);
			
			$pdf->SetXY($x+2+65+338, $y+2);
			
			$pdf->SetFont('Arial','B',9);
			
			$pdf->SetWidths(array(60));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$arr=array("R.U.C / C.I. ");
			$pdf->Row($arr,10);
			
			$pdf->SetXY($x+2+65+288+110, $y+2);
			
			$pdf->SetFont('Arial','',9);
			
			$pdf->SetWidths(array(90));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$arr=array($ruc_ci);
			$pdf->Row($arr,10);
			
			if($stmt8_count<>0 || $Monto_Total>0)
			{
				//cantidad a
				$y=$y+15;
				$pdf->SetXY($x, $y);
				$pdf->Cell(555,30,'','1',1,'Q');
				
				$pdf->SetXY($x+2, $y+2);
				
				$pdf->SetFont('Arial','B',9);
				
				$pdf->SetWidths(array(75));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$Monto_Total1=$num = NumerosEnLetras::convertir(number_format($Monto_Total,2, '.', ''),'',true,'Centavos', true);
				$arr=array('La cantidad de: ');
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+75, $y+2);
				
				$pdf->SetFont('Arial','',9);
				
				$pdf->SetWidths(array(478));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array($Monto_Total1);
				$pdf->Row($arr,10);
				$y=$y+15;
			}
			//cheque efectivo deposito
			foreach ($stmt8 as $key => $row1) 
			{
				if($row1['TC']=='BA')
				{
					$pdf->SetXY($x, $y);
					$pdf->Cell(555,30,'','1',1,'Q');
					
					$pdf->SetXY($x+2, $y+2);
					
					$pdf->SetFont('Arial','B',9);
					
					$pdf->SetWidths(array(65));
					$pdf->SetAligns(array("L"));
					//$arr=array($arr1[$i]);
					$arr=array('Cheque '.$_SESSION['INGRESO']['S_M'].'. ');
					$pdf->Row($arr,10);
					
					$pdf->SetXY($x+2+65, $y+2);
					
					$pdf->SetFont('Arial','',9);
					
					$pdf->SetWidths(array(70));
					$pdf->SetAligns(array("R"));
					
					$arr=array(number_format(($row1['monto']),2, '.', ','));
					$pdf->Row($arr,10);
					
					$pdf->SetXY($x+2+135, $y+2);
					
					$pdf->SetFont('Arial','B',9);
					
					$pdf->SetWidths(array(63));
					$pdf->SetAligns(array("L"));
					//$arr=array($arr1[$i]);
					$arr=array(' Banco: ');
					$pdf->Row($arr,10);
					
					$pdf->SetXY($x+2+130+65, $y+2);
					
					$pdf->SetFont('Arial','',9);
					
					$pdf->SetWidths(array(190));
					$pdf->SetAligns(array("L"));
					//$arr=array($arr1[$i]);
					$arr=array($row1['Cuenta']);
					$pdf->Row($arr,10);
					
					$pdf->SetXY($x+2+385, $y+2);
					
					$pdf->SetFont('Arial','B',9);
					
					$pdf->SetWidths(array(65));
					$pdf->SetAligns(array("L"));
					//$arr=array($arr1[$i]);
					$arr=array('Cheque No.');
					$pdf->Row($arr,10);
					
					$pdf->SetXY($x+2+385+65, $y+2);
					
					$pdf->SetFont('Arial','',9);
					
					$pdf->SetWidths(array(103));
					$pdf->SetAligns(array("L"));
					//$arr=array($arr1[$i]);
					$arr=array($row1['Cheq_Dep']);
					$pdf->Row($arr,10);
				}
				if($row1['TC']=='CJ')
				{
					$pdf->SetXY($x, $y);
					$pdf->Cell(555,30,'','1',1,'Q');
					
					$pdf->SetXY($x+2, $y+2);
					
					$pdf->SetFont('Arial','B',9);
					
					$pdf->SetWidths(array(65));
					$pdf->SetAligns(array("L"));
					//$arr=array($arr1[$i]);
					$arr=array('Efectivo '.$_SESSION['INGRESO']['S_M'].'. ');
					$pdf->Row($arr,10);
					
					$pdf->SetXY($x+2+65, $y+2);
					
					$pdf->SetFont('Arial','',9);
					
					$pdf->SetWidths(array(70));
					$pdf->SetAligns(array("R"));
					
					$arr=array(number_format(($row1['monto']),2, '.', ','));
					$pdf->Row($arr,10);
					
					$pdf->SetXY($x+2+135, $y+2);
					
					$pdf->SetFont('Arial','B',9);
					
					$pdf->SetWidths(array(63));
					$pdf->SetAligns(array("L"));
					//$arr=array($arr1[$i]);
					$arr=array(' Caja: ');
					$pdf->Row($arr,10);
					
					$pdf->SetXY($x+2+130+65, $y+2);
					
					$pdf->SetFont('Arial','',9);
					
					$pdf->SetWidths(array(190));
					$pdf->SetAligns(array("L"));
					//$arr=array($arr1[$i]);
					$arr=array($row1['Cuenta']);
					$pdf->Row($arr,10);
					
					$pdf->SetXY($x+2+385, $y+2);
					
					$pdf->SetFont('Arial','B',9);
					
					$pdf->SetWidths(array(65));
					$pdf->SetAligns(array("L"));
					//$arr=array($arr1[$i]);
					$arr=array('Retiro No.');
					$pdf->Row($arr,10);
					
					$pdf->SetXY($x+2+385+65, $y+2);
					
					$pdf->SetFont('Arial','',9);
					
					$pdf->SetWidths(array(103));
					$pdf->SetAligns(array("L"));
					//$arr=array($arr1[$i]);
					$arr=array($row1['Cheq_Dep']);
					$pdf->Row($arr,10);
				}			
				$y=$y+15;
			}
			//concepto
			$y=$y+15;
			$pdf->SetXY($x, $y);
			$pdf->Cell(555,30,'','1',1,'Q');
			
			$pdf->SetXY($x+2, $y+2);
			
			$pdf->SetFont('Arial','B',9);
			
			$pdf->SetWidths(array(65));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$arr=array('Concepto de: ');
			$pdf->Row($arr,10);
			
			$pdf->SetXY($x+2+65, $y+2);
			
			$pdf->SetFont('Arial','',9);
			
			$pdf->SetWidths(array(468));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$arr=array(mb_convert_encoding($Concepto, 'UTF-8'));
			$pdf->Row($arr,10);
			//titulo
			$y=$y+30;
			$pdf->SetXY($x, $y);
			$pdf->Cell(555,15,'','1',1,'Q');
			$pdf->SetFont('Arial','B',10);
			$pdf->SetXY($x+2, $y+2);
			$pdf->SetWidths(array(535));
			$pdf->SetAligns(array("C"));
			//$arr=array($arr1[$i]);
			$arr=array('CONTABILIZACION');
			$pdf->Row($arr,10);
			/*****agregar a funcion***********/
			//cabecera de tabla
			$y=$y+15;
			$pdf->SetXY($x, $y);
			$pdf->Cell(97,15,'','1',1,'Q');
			$pdf->SetXY($x+97, $y);
			$pdf->Cell(189,15,'','1',1,'Q');
			$pdf->SetXY($x+189+97, $y);
			$pdf->Cell(97,15,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97, $y);
			$pdf->Cell(86,15,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97+86, $y);
			$pdf->Cell(86,15,'','1',1,'Q');
			$pdf->SetXY($x+2, $y+2);
			$pdf->SetWidths(array(97,189,97,86,86));
			$pdf->SetAligns(array("C","C","C","C","C"));
			//$arr=array($arr1[$i]);
			$arr=array("CODIGO", "CONCEPTO", "PARCIAL M/E", "DEBE", "HABER");
			$pdf->Row($arr,10);
			//detalle comprobante
			$y=$y+15;
			$pdf->SetXY($x, $y);
			$pdf->Cell(97,465,'','1',1,'Q');
			$pdf->SetXY($x+97, $y);
			$pdf->Cell(189,465,'','1',1,'Q');
			$pdf->SetXY($x+189+97, $y);
			$pdf->Cell(97,465,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97, $y);
			$pdf->Cell(86,465,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97+86, $y);
			$pdf->Cell(86,465,'','1',1,'Q');
			$pdf->SetFont('Arial','',9);
			$pdf->SetXY($x+2, $y+2);
			//mostramos registro
			if($i==0)
			{
				$j1=0;
				$limite=23;
			}
			else
			{
				$j1=$j1+23;
				$limite=$limite+23;
			}
			if(isset($cta[$j1])){
				for($j=$j1;$j<$limite;$j++)
				{
					if(count($cta)==$j)
					{
						break;
					}
					$pdf->SetX($x+2);
					$pdf->SetFont('Arial','',9);
					$pdf->SetWidths(array(97,189,95,84,84));
					$pdf->SetAligns(array("L","L","R","R","R"));
					//$arr=array($arr1[$i]);
					$arr=array($cta[$j], $conc[$j], $parc[$j], $debe[$j],$haber[$j]);
					$pdf->Row($arr,10);
					$y=$y+10;
					if($detalle[$j]<>'.')
					{
						$pdf->SetFont('Arial','',8);
						//$pdf->SetXY($x+2, $y+2);
						$pdf->SetWidths(array(97,180,95,84,84));
						$pdf->SetAligns(array("L","L","R","R","R"));
						//$arr=array($arr1[$i]);
						$arr=array('', $detalle[$j], '', '', '');
						$pdf->Row($arr,10);
						$y=$y+10;
					}
					//verificamos si hay mas detalles
					for($ii=0;$ii<count($subc);$ii++)
					{
						if($cta==$subc[$ii]['cta'])
						{
							$cliente = $subc[$ii]['cliente'];
							$Fechav = $subc[$ii]['Fechav']->format('Y-m-d');
							$debe='';
							$haber='';
							if($subc[$ii]['debe']!=0 and $subc[$ii]['debe']!='0.00')
							{
								$debe = number_format($subc[$ii]['debe'],2, '.', ',');
							}
							if($subc[$ii]['haber']!=0 and $subc[$ii]['haber']!='0.00')
							{
								$haber = number_format($subc[$ii]['haber'],2, '.', ',');
							}
							$pdf->SetX($x+2);
							$pdf->SetFont('Arial','I',7);
							//$pdf->SetXY($x+2, $y+2);
							$pdf->SetWidths(array(97,94,94,48, 48,85,84));
							$pdf->SetAligns(array("L","L","L","R","R","R","R"));
							//$arr=array($arr1[$i]);
							if($subc[$ii]['No']<>'.')
							{
								$arr=array('', $cliente, 'No. '.$pdf->generaCeros($subc[$ii]['No'],7), $debe, $haber, '', '');
							}
							else
							{
								if($subc[$ii]['vp']<>0)
								{
									$arr=array('', $cliente, 'Valor Prima ', $subc[$ii]['vp'], $haber, '', '');
								}
								else
								{
									$arr=array('', $cliente, 'Venc. '.$Fechav, $debe, $haber, '', '');
								}
							}
							
							$pdf->Row($arr,10);
							$y=$y+10;
						}
					}
				}
			}
			if($i<($pagi-2))
			{
				$y=$y+15;
				$pdf->SetXY($x+2, $y+2);
				$pdf->SetFont('Arial','I',7);
				//$pdf->SetXY($x+2, $y+2);
				$pdf->SetWidths(array(150));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array('CONTINUARA EN LA SIGUIENTE PAGINA...');
				$pdf->Row($arr,10);
				$pdf->AddPage();
			}
		}
		
		//si esta anulado imprimir
		if($status=='A')
		{
			$pdf->Image(__DIR__ . '/../../img/gif/ANULADO.GIF',$x+15,$pdf->GetY()-20,180,45);
		}

		$y = $pdf->GetY()+20;
		$pdf->SetXY($x, $y);
		$pdf->Cell(383,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97+86, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetFont('Arial','',9);
		$pdf->SetXY($x+2, $y+2);
		$pdf->SetWidths(array(383,84,84));
		$pdf->SetAligns(array("R","R","R"));
		//$arr=array($arr1[$i]);
		$arr=array('TOTALES',  number_format($sumdb,2, '.', ','), number_format($sumcr,2, '.', ','));
		$pdf->Row($arr,10);
		$y=$y+15;
		$pdf->Footer1($pdf,$usuario,$x, $y);
		//die();
	}
	//$pdf->AddPage();
	//datos cliente
	/******************/
	/******************/
	/**************110****/
	//$pdf->cabeceraHorizontal(array(' '),20,185,574,90,20,5);
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY(41, 149);
	//para posicion automatica
	$y=35;
	
	if($imp1==null or $imp1==1)
	{
		$pdf->Output();
	}
	if($imp1==0)
	{
		$pdf->Output('TEMP/'.$id.'.pdf','F'); 
	}
}
//imprimir comprobante ingreso
/* $stmt= variable con datos xml stmt2 transacciones stmt4 las Retenciones del IVA stmt5 las Retenciones de la fuente
   stmt6 SubCtas $id id del campo $formato formato del reporte aqui es pdf
   $nombre_archivo ruta y nombre del archivo xml generado $va 0 para saber si lee de una variable o 1 un archivo xml
   $imp1 paqra saber si se descarga o no null o 1 descarga 0 es para temp y adjuntar al correo
   $stmt2_count = cantidad Listar las Transacciones. 
   $stmt4_count = cantidad Listar las Retenciones del IVA.
   $stmt5_count = cantidad las Retenciones de la Fuente.
   $stmt6_count = cantidad Llenar SubCtas
   $stmt1 cabecera */
 function imprimirCI($stmt, $stmt2, $stmt4, $stmt5, $stmt6, $stmt1, $stmt8, $id=null,$formato=null,$nombre_archivo=null,$va=null,$imp1=null,
 $stmt2_count=null,$stmt4_count=null,$stmt5_count=null,$stmt6_count=null,$stmt8_count=null)
{
	$pdf = new PDF('P','pt','LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();
	// while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	// {
	// print_r($stmt);die();
		$TipoComp =  $stmt[0]['TP']; // $row[2];
		$Numero = $stmt[0]['Numero']; // $row[3];
		$Fecha =  $stmt[0]['Fecha']->format('Y-m-d'); // $row[4]->format('Y-m-d');
		$Concepto= $stmt[0]['Concepto']; // $row[7];
		$efectivo1= $stmt[0]['Efectivo']; // $row[9];
		$Monto_Total= $stmt[0]['Monto_Total']; // $row[10];
	// }
	//cabecera y pie de pagina
	// while( $row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_NUMERIC) ) 
	// {
		// print_r($stmt1);die();
		$t = $stmt1[0]['T'];    // $row1[2];
		$Fecha = $stmt1[0]['Fecha']->format('Y-m-d');    // $row1[5]->format('Y-m-d');
		$codigoB = $stmt1[0]['Codigo_B'];    // $row1[6];
		$beneficiario = $stmt1[0]['Cliente'];    // $row1[26];
		$concepto1 = $stmt1[0]['Concepto'];    // $row1[8];
		$efectivo = number_format($stmt1[0]['Efectivo'],2, '.', ',');
		$est="Normal";
		$cliente = $stmt1[0]['Cliente'];    // $row1[26];
		$ruc_ci = $stmt1[0]['CI_RUC'];    // $row1[21];
		if($t == 'A')
		{
			$est="ANULADO";
		}
		$usuario= $stmt1[0]['Nombre_Completo'];    // $row1[20];
		//echo $t.' '.$Fecha.' '.$codigoB.' '.$beneficiario.' '.$concepto.' '.$efectivo.' '.$est.' '.$usuario;
	// }
	$sumdb=0;
	$sumcr=0;
	$cantidad_reg=$stmt2_count+$stmt6_count;
	if($cantidad_reg<31)
	{
		//para sacar parte del header
		$i=0;
		$pag=1;
		$pdf->Header1($pdf,$Numero,$TipoComp,$Fecha,$pag);
		//logo
		$i=0;
		if($va==1)
		{
			//$autorizacion = simplexml_load_file($nombre_archivo);
		}
		else
		{
			//$stmt = str_replace("﻿", "", $stmt);
			//$autorizacion =simplexml_load_string($stmt);
		}
		$x=31;
		if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social'])
		{
			$y=90;
		}
		else
		{
			$y=80;
		}
		//imprimir cantidad,cheque depositos
		
		//$num = NumerosEnLetras::convertir(1988208.99);
		//recibido de
		$pdf->SetXY($x, $y);
		//$ancho_=round((strlen($cliente)/52), 0, PHP_ROUND_HALF_UP)+1;
		//$ancho_=$ancho_*15;
		//echo $ancho_.' vvv '.(strlen($cliente));
		//die();
		$pdf->Cell(555,15,'','1',1,'Q');

		$pdf->SetXY($x+2, $y+2);
		
		$pdf->SetFont('Arial','B',9);
		
		$pdf->SetWidths(array(65));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array('Recibido de: ');
		$pdf->Row($arr,10);
		
		$pdf->SetXY($x+2+65, $y+2);
		if((strlen($cliente)/52)>=1)
		{
			$pdf->SetFont('Arial','',7);
		}
		else
		{
			$pdf->SetFont('Arial','',9);
		}
		
		$pdf->SetWidths(array(338));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array($cliente);
		$pdf->Row($arr,10);
		
		$pdf->SetXY($x+2+65+338, $y+2);
		
		$pdf->SetFont('Arial','B',9);
		
		$pdf->SetWidths(array(60));
		$pdf->SetAligns(array("R"));
		//$arr=array($arr1[$i]);
		$arr=array("R.U.C / C.I. ");
		$pdf->Row($arr,10);
		
		$pdf->SetXY($x+2+65+288+110, $y+2);
		
		$pdf->SetFont('Arial','',9);
		
		$pdf->SetWidths(array(90));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array($ruc_ci);
		$pdf->Row($arr,10);
		
		if($stmt8_count<>0)
		{
			//cantidad a
			$y=$y+15;
			$pdf->SetXY($x, $y);
			$pdf->Cell(555,30,'','1',1,'Q');
			
			$pdf->SetXY($x+2, $y+2);
			
			$pdf->SetFont('Arial','B',9);
			
			$pdf->SetWidths(array(75));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$Monto_Total1=$num = NumerosEnLetras::convertir(number_format($Monto_Total,2, '.', ''),'',true,'Centavos');
			$arr=array('La cantidad de: ');
			$pdf->Row($arr,10);
			
			$pdf->SetXY($x+2+75, $y+2);
			
			$pdf->SetFont('Arial','',9);
			
			$pdf->SetWidths(array(478));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$arr=array($Monto_Total1);
			$pdf->Row($arr,10);
			$y=$y+30;
		}
		//cheque efectivo deposito
		/*
			select cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep,sum(t.Haber) as monto
			 from Transacciones as t, Catalogo_Cuentas as cc
			 where t.Item='001' and t.Periodo='.' 
			 and t.TP='CE' and t.Numero='5000093'
			 and cc.TC IN ('BA','CJ')
			 and t.Haber>0
			 and t.Item=cc.Item
			 and t.Periodo=cc.Periodo
			 and t.Cta=cc.Codigo
			 group by cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep
			 order by cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep

			 select cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep,sum(t.Debe) as monto
			 from Transacciones as t, Catalogo_Cuentas as cc
			 where t.Item='001' and t.Periodo='.' 
			 and t.TP='CI' and t.Numero='5000096'
			 and cc.TC IN ('BA','CJ')
			 and t.Debe>0
			 and t.Item=cc.Item
			 and t.Periodo=cc.Periodo
			 and t.Cta=cc.Codigo
			 group by cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep
			 order by cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep
		*/
		
		$i=0;
		// while( $row1 = sqlsrv_fetch_array( $stmt8, SQLSRV_FETCH_NUMERIC) ) 
		// {

				if(count($stmt8)>0) {
			
			if($stmt8[0]['TC']=='BA')
			{
				$pdf->SetXY($x, $y);
				$pdf->Cell(555,30,'','1',1,'Q');
				
				$pdf->SetXY($x+2, $y+2);
				
				$pdf->SetFont('Arial','B',9);
				
				$pdf->SetWidths(array(65));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array('Cheque '.$_SESSION['INGRESO']['S_M'].'. ');
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+65, $y+2);
				
				$pdf->SetFont('Arial','',9);
				
				$pdf->SetWidths(array(70));
				$pdf->SetAligns(array("R"));
				
				$arr=array(number_format(($stmt8[0]['monto']),2, '.', ','));
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+135, $y+2);
				
				$pdf->SetFont('Arial','B',9);
				
				$pdf->SetWidths(array(63));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array(' Banco: ');
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+130+65, $y+2);
				
				$pdf->SetFont('Arial','',9);
				
				$pdf->SetWidths(array(190));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array($stmt8[0]['Cuenta']);
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+385, $y+2);
				
				$pdf->SetFont('Arial','B',9);
				
				$pdf->SetWidths(array(65));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array('Deposito No.');
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+385+65, $y+2);
				
				$pdf->SetFont('Arial','',9);
				
				$pdf->SetWidths(array(103));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array($stmt8[0]['Cheq_Dep']);
				$pdf->Row($arr,10);
			}
			if($stmt8[0]['TC']=='CJ')
			{
				$pdf->SetXY($x, $y);
				$pdf->Cell(555,30,'','1',1,'Q');
				
				$pdf->SetXY($x+2, $y+2);
				
				$pdf->SetFont('Arial','B',9);
				
				$pdf->SetWidths(array(65));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array('Efectivo '.$_SESSION['INGRESO']['S_M'].'. ');
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+65, $y+2);
				
				$pdf->SetFont('Arial','',9);
				
				$pdf->SetWidths(array(70));
				$pdf->SetAligns(array("R"));
				
				$arr=array(number_format(($stmt8[0]['monto']),2, '.', ','));
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+135, $y+2);
				
				$pdf->SetFont('Arial','B',9);
				
				$pdf->SetWidths(array(63));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array(' Caja: ');
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+130+65, $y+2);
				
				$pdf->SetFont('Arial','',9);
				
				$pdf->SetWidths(array(190));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array($stmt8[0]['Cuenta']);
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+385, $y+2);
				
				$pdf->SetFont('Arial','B',9);
				
				$pdf->SetWidths(array(65));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array('Deposito No.');
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+385+65, $y+2);
				
				$pdf->SetFont('Arial','',9);
				
				$pdf->SetWidths(array(103));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array($stmt8[0]['Cheq_Dep']);
				$pdf->Row($arr,10);
			}				
			$y=$y+15;
			// $i++;
		}
		/*--------------------------------------------*/
		
		
		//concepto
		$y=$y+15;
		$pdf->SetXY($x, $y);
		$pdf->Cell(555,30,'','1',1,'Q');
		
		$pdf->SetXY($x+2, $y+2);
		
		$pdf->SetFont('Arial','B',9);
		
		$pdf->SetWidths(array(65));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array('Concepto de: ');
		$pdf->Row($arr,10);
		
		$pdf->SetXY($x+2+65, $y+2);
		
		$pdf->SetFont('Arial','',9);
		
		$pdf->SetWidths(array(488));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array($Concepto);
		$pdf->Row($arr,10);
		//titulo
		$y=$y+30;
		$pdf->SetXY($x, $y);
		$pdf->Cell(555,15,'','1',1,'Q');
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($x+2, $y+2);
		$pdf->SetWidths(array(535));
		$pdf->SetAligns(array("C"));
		//$arr=array($arr1[$i]);
		$arr=array('CONTABILIZACION');
		$pdf->Row($arr,10);
		//saber si es una o mas pagina
		//echo ' '.$stmt2_count.' '.$stmt6_count.' ';
		/*****agregar a funcion***********/
		//cabecera de tabla
		$y=$y+15;
		$pdf->SetXY($x, $y);
		
		$pdf->Cell(97,15,'','1',1,'Q');
		$pdf->SetXY($x+97, $y);
		$pdf->Cell(189,15,'','1',1,'Q');
		$pdf->SetXY($x+189+97, $y);
		$pdf->Cell(97,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97+86, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetXY($x+2, $y+2);
		$pdf->SetWidths(array(97,189,97,86,86));
		$pdf->SetAligns(array("C","C","C","C","C"));
		//$arr=array($arr1[$i]);
		$arr=array("CODIGO", "CONCEPTO", "PARCIAL M/E", "DEBE", "HABER");
		$pdf->Row($arr,10);
		//detalle comprobante
		$y=$y+15;
		$pdf->SetXY($x, $y);
		if($cantidad_reg<=10)
		{
			$cantidad_reg1=15*10;
			/*$pdf->Cell(97,105,'','1',1,'Q');
			$pdf->SetXY($x+97, $y);
			$pdf->Cell(189,105,'','1',1,'Q');
			$pdf->SetXY($x+189+97, $y);
			$pdf->Cell(97,105,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97, $y);
			$pdf->Cell(86,105,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97+86, $y);
			$pdf->Cell(86,105,'','1',1,'Q');*/
			if($stmt8_count<>0)
			{
				$pdf->Cell(97,105,'','1',1,'Q');
				$pdf->SetXY($x+97, $y);
				$pdf->Cell(189,105,'','1',1,'Q');
				$pdf->SetXY($x+189+97, $y);
				$pdf->Cell(97,105,'','1',1,'Q');
				$pdf->SetXY($x+97+189+97, $y);
				$pdf->Cell(86,105,'','1',1,'Q');
				$pdf->SetXY($x+97+189+97+86, $y);
				$pdf->Cell(86,105,'','1',1,'Q');
			}
			else
			{
				$pdf->Cell(97,165,'','1',1,'Q');
				$pdf->SetXY($x+97, $y);
				$pdf->Cell(189,165,'','1',1,'Q');
				$pdf->SetXY($x+189+97, $y);
				$pdf->Cell(97,165,'','1',1,'Q');
				$pdf->SetXY($x+97+189+97, $y);
				$pdf->Cell(86,165,'','1',1,'Q');
				$pdf->SetXY($x+97+189+97+86, $y);
				$pdf->Cell(86,165,'','1',1,'Q');
			}
		}
		else
		{
			//$cantidad_reg1=$cantidad_reg*10;
			$x1=$x;
			$y1=$y;
			
		}
		//$pdf->SetFont('Arial','',9);
		//llenamos los detalles 
		$ii=0;
		$subc=array();
		// while( $row1 = sqlsrv_fetch_array( $stmt6, SQLSRV_FETCH_NUMERIC) ) 
		// {
		// print_r($stmt6);die();
		foreach ($stmt6 as $key => $value) {			
			$subc[$ii]['cta']=$value['Cta'];// $row1[0];
			$subc[$ii]['cliente']=$value['Cliente'];// $row1[3];
			$subc[$ii]['Fechav']=$value['Fecha_V'];// $row1[7];
			$subc[$ii]['debe']=$value['Debitos'];// $row1[5];
			$subc[$ii]['haber']=$value['Creditos'];// $row1[6];
			$subc[$ii]['No']=$value['Factura'];// $row1[2];
			$subc[$ii]['vp']=$value['Prima'];// $row1[9];
			
			$ii++;
		}
		// }
		$pdf->SetXY($x+2, $y+2);
		
		// while( $row1 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC) ) 
		// {

		// print_r($stmt2);die();
		$status='';
		if(count($stmt2)>0)
		{
			foreach ($stmt2 as $key => $value) {
				$parc='';$debe='-';$haber='-';
				$arr=array();
				if($value['Parcial_ME']!=0 and $value['Parcial_ME']!='0.00')
				{
					$parc = number_format($value['Parcial_ME'],2, '.', ',');
				}
				if($value['Debe']!=0 and $value['Debe']!='0.00')
				{
					$sumdb=$sumdb+$value['Debe'];
					$debe = number_format($value['Debe'],2, '.', '');
				}
				if($value['Haber']!=0 and $value['Haber']!='0.00')
				{
					$sumcr=$sumcr+$value['Haber'];
					$haber = number_format($value['Haber'],2, '.', '');
				}
				$pdf->SetAligns(array("L","L","R","R","R"));
				$arr=array($value['Cta'], $value['Cuenta'], $parc,$debe,$haber);
				$pdf->Row($arr,10,'LR');
				$pdf->SetXY($x, $pdf->GetY());	
				//en caso de haber sub modulos se agrega
				if($value['Detalle']!='.' && $value['Detalle']!='')
				{

					$pdf->SetFont('Arial','',8);
					$arr=array("",$value['Detalle'],'','','');
					$pdf->Row($arr,10,'LR');
					$pdf->SetXY($x, $pdf->GetY());	
					$pdf->SetFont('Arial','',10);
				}				
				// print_r($pdf->GetPageHeight());
				

			}

			// // print_r("expression");die();
			// // print_r($stmt2);die();
			// $pdf->SetX($x+2);
			// $pdf->SetFont('Arial','',9);
			// $cta = $stmt2[0]['Cta'];//$row1[0];
			// $conc = $stmt2[0]['Cuenta'];//$row1[1];
			// $parc='';
			// $debe='';
			// $haber='';
			// if($stmt2[0]['Parcial_ME']!=0 and $stmt2[0]['Parcial_ME']!='0.00')
			// {
			// 	$parc = number_format($stmt2[0]['Parcial_ME'],2, '.', ',');
			// }
			// if($stmt2[0]['Debe']!=0 and $stmt2[0]['Debe']!='0.00')
			// {
			// 	$sumdb=$sumdb+$stmt2[0]['Debe'];
			// 	$debe = number_format($stmt2[0]['Debe'],2, '.', ',');
			// }
			// if($stmt2[0]['Haber']!=0 and $stmt2[0]['Haber']!='0.00')
			// {
			// 	$sumcr=$sumcr+$stmt2[0]['Haber'];//$row1[4];
			// 	$haber = number_format($stmt2[0]['Haber'],2, '.', ',');
			// }
			// $detalle = $stmt2[0]['Detalle'];//$row1[5];
			// $status = $stmt2[0]['T'];//$row1[13];
			
			// $pdf->SetWidths(array(97,189,95,84,84));
			// $pdf->SetAligns(array("L","L","R","R","R"));
			// //$arr=array($arr1[$i]);
			// $arr=array($cta, $conc, $parc, $debe,$haber);
			// $pdf->Row($arr,10);
			// $y=$y+10;
			// if($detalle<>'.')
			// {
			// 	$pdf->SetFont('Arial','',8);
			// 	//$pdf->SetXY($x+2, $y+2);
			// 	$pdf->SetWidths(array(97,180,95,84,84));
			// 	$pdf->SetAligns(array("L","L","R","R","R"));
			// 	//$arr=array($arr1[$i]);
			// 	$arr=array('', $detalle, '', '', '');
			// 	$pdf->Row($arr,10);
			// 	$y=$y+10;
			// }
			// //verificamos si hay mas detalles
			// for($ii=0;$ii<count($subc);$ii++)
			// {
			// 	if($cta==$subc[$ii]['cta'])
			// 	{
			// 		$cliente = $subc[$ii]['cliente'];
			// 		$Fechav = $subc[$ii]['Fechav']->format('Y-m-d');
			// 		$debe='';
			// 		$haber='';
			// 		if($subc[$ii]['debe']!=0 and $subc[$ii]['debe']!='0.00')
			// 		{
			// 			$debe = number_format($subc[$ii]['debe'],2, '.', ',');
			// 		}
			// 		if($subc[$ii]['haber']!=0 and $subc[$ii]['haber']!='0.00')
			// 		{
			// 			$haber = number_format($subc[$ii]['haber'],2, '.', ',');
			// 		}
			// 		$pdf->SetX($x+2);
			// 		$pdf->SetFont('Arial','I',7);
			// 		//echo " aqui ";
			// 		//	die();
			// 		//$pdf->SetXY($x+2, $y+2);
			// 		$pdf->SetWidths(array(97,94,94,48, 48,85,84));
			// 		$pdf->SetAligns(array("L","L","L","R","R","R","R"));
			// 		//$arr=array($arr1[$i]);
			// 		if($subc[$ii]['No']<>'.')
			// 		{
			// 			$arr=array('', $cliente, 'No. '.$pdf->generaCeros($subc[$ii]['No'],7), $debe, $haber, '', '');
			// 		}
			// 		else
			// 		{
			// 			if($subc[$ii]['vp']<>0)
			// 			{
			// 				$arr=array('', $cliente, 'Valor Prima ', $subc[$ii]['vp'], $haber, '', '');
			// 			}
			// 			else
			// 			{
			// 				$arr=array('', $cliente, 'Venc. '.$Fechav, $debe, $haber, '', '');
			// 			}
			// 		}
					
			// 		$pdf->Row($arr,10);
			// 		$y=$y+10;
			// 	}
			// }
		}
		// }
		//detalle sub cuenta
		if($cantidad_reg>10)
		{
			$y=$y+20;
		}
		$y=$y+10;
		//$pdf->SetXY($x+2, ($pdf->GetY($y)-80));
		//si esta anulado imprimir
		if($status=='A')
		{
			$pdf->SetXY($x+8, $y+2);
			$pdf->SetFont('Arial','B',14);
			$pdf->SetFillColor(255, 255, 255); 
			$pdf->Cell(120,25,'','1',1,'Q',TRUE);
			//$pdf->cabeceraHorizontal(array(' '),$x+8,$y,120,30,20,5);
			//$pdf->cabeceraHorizontal(array(' '),$x+10,$y+5,115,23,20,5);
			$pdf->SetXY($x+10, $y+5);
			$pdf->Cell(115,20,'','1',1,'Q',TRUE);
			$pdf->SetXY($x+11, $y+7);
			$pdf->SetWidths(array(120));
			$pdf->SetAligns(array("C"));
			//$arr=array($arr1[$i]);
			//$pdf->cabeceraHorizontal(array(' '),$x,$y,96,15,20,5);
			$arr=array('ANULADO');
			$pdf->Row($arr,15);
			//$y=$y+15;
		}
		if($cantidad_reg>10)
		{
			$pdf->SetXY($x1, $y1);
			//echo $pdf->GetY($y);
			//die();
			$cantidad_reg1=$cantidad_reg*10+($pdf->GetY($y)-80);
			$pdf->Cell(97,$cantidad_reg1,'','1',1,'Q');
			$pdf->SetXY($x1+97, $y1);
			$pdf->Cell(189,$cantidad_reg1,'','1',1,'Q');
			$pdf->SetXY($x1+189+97, $y1);
			$pdf->Cell(97,$cantidad_reg1,'','1',1,'Q');
			$pdf->SetXY($x1+97+189+97, $y1);
			$pdf->Cell(86,$cantidad_reg1,'','1',1,'Q');
			$pdf->SetXY($x1+97+189+97+86, $y1);
			$pdf->Cell(86,$cantidad_reg1,'','1',1,'Q');
			$pos=$cantidad_reg1-$cantidad_reg*10;
		}
		else
		{
			$pos=$cantidad_reg1-10;
		}
		//totales
		
		//echo $y.' '.$pos.' '.$pdf->GetY($y);
		//die();
		if($cantidad_reg>10)
		{
			$y=$y+$pos-30;
		}
		else
		{
			$y=$y+$pos-10;
			$y=330;
		}
		
		$pdf->SetXY($x, $y);
		$pdf->Cell(383,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97+86, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetFont('Arial','',9);
		$pdf->SetXY($x+2, $y+2);
		$pdf->SetWidths(array(383,84,84));
		$pdf->SetAligns(array("R","R","R"));
		//$arr=array($arr1[$i]);
		$arr=array('TOTALES',  number_format($sumdb,2, '.', ','), number_format($sumcr,2, '.', ','));
		$pdf->Row($arr,10);
		$y=$y+15;
		$pdf->Footer1($pdf,$usuario,$x, $y);
		
	}
	else
	{
		//varias paginas
		//select * from lista_empresas where RUC_CI_NIC='1791863798001'
		//delete from lista_empresas where ID=175
		$pagi=ceil($cantidad_reg/35);
		//echo ' ddd '.$pagi;
		//die();
		//obtenemos los datos
		$i=0;
		$cta=array();
		$conc=array();
		$parc=array();
		$debe=array();
		$haber=array();
		$detalle = array();
		$status = array();
		//llenamos los detalles 
		$ii=0;
		$subc=array();
		// while( $row1 = sqlsrv_fetch_array( $stmt6, SQLSRV_FETCH_NUMERIC) ) 
		// {

			print_r($stmt6);die();
			foreach ($stmt6 as $key => $value) {
			
			$subc[$ii]['cta']= $value[''];// $row1[0];
			$subc[$ii]['cliente']= $value[''];// $row1[3];
			$subc[$ii]['Fechav']= $value[''];// $row1[7];
			$subc[$ii]['debe']= $value[''];// $row1[5];
			$subc[$ii]['haber']= $value[''];// $row1[6];
			$subc[$ii]['No']= $value[''];// $row1[2];
			$subc[$ii]['vp']= $value[''];// $row1[9];

			
			$ii++;
		}
		// }
		while( $row1 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC) ) 
		{
			$cta[$i] = $row1[0];
			$conc[$i] = $row1[1];
			$parc[$i]='';
			$debe[$i]='';
			$haber[$i]='';
			if($row1[2]!=0 and $row1[2]!='0.00')
			{
				$parc[$i] = number_format($row1[2],2, '.', ',');
			}
			if($row1[3]!=0 and $row1[3]!='0.00')
			{
				$sumdb=$sumdb+$row1[3];
				$debe[$i] = number_format($row1[3],2, '.', ',');
			}
			if($row1[4]!=0 and $row1[4]!='0.00')
			{
				$sumcr=$sumcr+$row1[4];
				$haber[$i] = number_format($row1[4],2, '.', ',');
			}
			$detalle[$i] = $row1[5];
			$status[$i] = $row1[13];
			
			$i++;
		}
		/*****agregar a funcion***********/
		$pag=1;
		for($i=0;$i<$pagi-1;$i++)
		{
			//echo $i.'<br>';
			$pdf->Header1($pdf,$Numero,$TipoComp,$Fecha,$pag);
			$pag=$pag+1;
			$x=31;
			if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social'])
			{
				$y=90;
			}
			else
			{
				$y=80;
			}
			//recibido de
			$pdf->SetXY($x, $y);
			$pdf->Cell(555,15,'','1',1,'Q');
			
			$pdf->SetXY($x+2, $y+2);
			
			$pdf->SetFont('Arial','B',9);
			
			$pdf->SetWidths(array(65));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$arr=array('Recibido de: ');
			$pdf->Row($arr,10);
			
			$pdf->SetXY($x+2+65, $y+2);
			
			$pdf->SetFont('Arial','',9);
			
			$pdf->SetWidths(array(338));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$arr=array($cliente);
			$pdf->Row($arr,10);
			
			$pdf->SetXY($x+2+65+338, $y+2);
			
			$pdf->SetFont('Arial','B',9);
			
			$pdf->SetWidths(array(60));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$arr=array("R.U.C / C.I. ");
			$pdf->Row($arr,10);
			
			$pdf->SetXY($x+2+65+288+110, $y+2);
			
			$pdf->SetFont('Arial','',9);
			
			$pdf->SetWidths(array(90));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$arr=array($ruc_ci);
			$pdf->Row($arr,10);
			
			if($stmt8_count<>0)
			{
				//cantidad a
				$y=$y+15;
				$pdf->SetXY($x, $y);
				$pdf->Cell(555,30,'','1',1,'Q');
				
				$pdf->SetXY($x+2, $y+2);
				
				$pdf->SetFont('Arial','B',9);
				
				$pdf->SetWidths(array(75));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$Monto_Total1=$num = NumerosEnLetras::convertir(number_format($Monto_Total,2, '.', ''),'',true,'Centavos');
				$arr=array('La cantidad de: ');
				$pdf->Row($arr,10);
				
				$pdf->SetXY($x+2+75, $y+2);
				
				$pdf->SetFont('Arial','',9);
				
				$pdf->SetWidths(array(478));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array($Monto_Total1);
				$pdf->Row($arr,10);
				$y=$y+30;
			}
			//cheque efectivo deposito
			/*
				select cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep,sum(t.Haber) as monto
				 from Transacciones as t, Catalogo_Cuentas as cc
				 where t.Item='001' and t.Periodo='.' 
				 and t.TP='CE' and t.Numero='5000093'
				 and cc.TC IN ('BA','CJ')
				 and t.Haber>0
				 and t.Item=cc.Item
				 and t.Periodo=cc.Periodo
				 and t.Cta=cc.Codigo
				 group by cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep
				 order by cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep

				 select cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep,sum(t.Debe) as monto
				 from Transacciones as t, Catalogo_Cuentas as cc
				 where t.Item='001' and t.Periodo='.' 
				 and t.TP='CI' and t.Numero='5000096'
				 and cc.TC IN ('BA','CJ')
				 and t.Debe>0
				 and t.Item=cc.Item
				 and t.Periodo=cc.Periodo
				 and t.Cta=cc.Codigo
				 group by cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep
				 order by cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep
			*/
			
			$i=0;
			while( $row1 = sqlsrv_fetch_array( $stmt8, SQLSRV_FETCH_NUMERIC) ) 
			{
				if($row1[0]=='BA')
				{
					$pdf->SetXY($x, $y);
					$pdf->Cell(555,30,'','1',1,'Q');
					
					$pdf->SetXY($x+2, $y+2);
					
					$pdf->SetFont('Arial','B',9);
					
					$pdf->SetWidths(array(65));
					$pdf->SetAligns(array("L"));
					//$arr=array($arr1[$i]);
					$arr=array('Cheque '.$_SESSION['INGRESO']['S_M'].'. ');
					$pdf->Row($arr,10);
					
					$pdf->SetXY($x+2+65, $y+2);
					
					$pdf->SetFont('Arial','',9);
					
					$pdf->SetWidths(array(70));
					$pdf->SetAligns(array("R"));
					
					$arr=array(number_format(($row1[4]),2, '.', ','));
					$pdf->Row($arr,10);
					
					$pdf->SetXY($x+2+135, $y+2);
					
					$pdf->SetFont('Arial','B',9);
					
					$pdf->SetWidths(array(63));
					$pdf->SetAligns(array("L"));
					//$arr=array($arr1[$i]);
					$arr=array(' Banco: ');
					$pdf->Row($arr,10);
					
					$pdf->SetXY($x+2+130+65, $y+2);
					
					$pdf->SetFont('Arial','',9);
					
					$pdf->SetWidths(array(190));
					$pdf->SetAligns(array("L"));
					//$arr=array($arr1[$i]);
					$arr=array($row1[2]);
					$pdf->Row($arr,10);
					
					$pdf->SetXY($x+2+385, $y+2);
					
					$pdf->SetFont('Arial','B',9);
					
					$pdf->SetWidths(array(65));
					$pdf->SetAligns(array("L"));
					//$arr=array($arr1[$i]);
					$arr=array('Deposito No.');
					$pdf->Row($arr,10);
					
					$pdf->SetXY($x+2+385+65, $y+2);
					
					$pdf->SetFont('Arial','',9);
					
					$pdf->SetWidths(array(103));
					$pdf->SetAligns(array("L"));
					//$arr=array($arr1[$i]);
					$arr=array($row1[3]);
					$pdf->Row($arr,10);
				}
				if($row1[0]=='CJ')
				{
					$pdf->SetXY($x, $y);
					$pdf->Cell(555,30,'','1',1,'Q');
					
					$pdf->SetXY($x+2, $y+2);
					
					$pdf->SetFont('Arial','B',9);
					
					$pdf->SetWidths(array(65));
					$pdf->SetAligns(array("L"));
					//$arr=array($arr1[$i]);
					$arr=array('Efectivo '.$_SESSION['INGRESO']['S_M'].'. ');
					$pdf->Row($arr,10);
					
					$pdf->SetXY($x+2+65, $y+2);
					
					$pdf->SetFont('Arial','',9);
					
					$pdf->SetWidths(array(70));
					$pdf->SetAligns(array("R"));
					
					$arr=array(number_format(($row1[4]),2, '.', ','));
					$pdf->Row($arr,10);
					
					$pdf->SetXY($x+2+135, $y+2);
					
					$pdf->SetFont('Arial','B',9);
					
					$pdf->SetWidths(array(63));
					$pdf->SetAligns(array("L"));
					//$arr=array($arr1[$i]);
					$arr=array(' Caja: ');
					$pdf->Row($arr,10);
					
					$pdf->SetXY($x+2+130+65, $y+2);
					
					$pdf->SetFont('Arial','',9);
					
					$pdf->SetWidths(array(190));
					$pdf->SetAligns(array("L"));
					//$arr=array($arr1[$i]);
					$arr=array($row1[2]);
					$pdf->Row($arr,10);
					
					$pdf->SetXY($x+2+385, $y+2);
					
					$pdf->SetFont('Arial','B',9);
					
					$pdf->SetWidths(array(65));
					$pdf->SetAligns(array("L"));
					//$arr=array($arr1[$i]);
					$arr=array('Deposito No.');
					$pdf->Row($arr,10);
					
					$pdf->SetXY($x+2+385+65, $y+2);
					
					$pdf->SetFont('Arial','',9);
					
					$pdf->SetWidths(array(103));
					$pdf->SetAligns(array("L"));
					//$arr=array($arr1[$i]);
					$arr=array($row1[3]);
					$pdf->Row($arr,10);
				}				
				$y=$y+15;
				$i++;
			}
			//concepto
			$pdf->SetXY($x, $y);
			$pdf->Cell(555,30,'','1',1,'Q');
			
			$pdf->SetXY($x+2, $y+2);
			
			$pdf->SetFont('Arial','B',9);
			
			$pdf->SetWidths(array(65));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$arr=array('Concepto de: ');
			$pdf->Row($arr,10);
			
			$pdf->SetXY($x+2+65, $y+2);
			
			$pdf->SetFont('Arial','',9);
			
			$pdf->SetWidths(array(468));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$arr=array($Concepto);
			$pdf->Row($arr,10);
			//titulo
			$y=$y+30;
			$pdf->SetXY($x, $y);
			$pdf->Cell(555,15,'','1',1,'Q');
			$pdf->SetFont('Arial','B',10);
			$pdf->SetXY($x+2, $y+2);
			$pdf->SetWidths(array(535));
			$pdf->SetAligns(array("C"));
			//$arr=array($arr1[$i]);
			$arr=array('CONTABILIZACION');
			$pdf->Row($arr,10);
			/*****agregar a funcion***********/
			//cabecera de tabla
			$y=$y+15;
			$pdf->SetXY($x, $y);
			$pdf->Cell(97,15,'','1',1,'Q');
			$pdf->SetXY($x+97, $y);
			$pdf->Cell(189,15,'','1',1,'Q');
			$pdf->SetXY($x+189+97, $y);
			$pdf->Cell(97,15,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97, $y);
			$pdf->Cell(86,15,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97+86, $y);
			$pdf->Cell(86,15,'','1',1,'Q');
			$pdf->SetXY($x+2, $y+2);
			$pdf->SetWidths(array(97,189,97,86,86));
			$pdf->SetAligns(array("C","C","C","C","C"));
			//$arr=array($arr1[$i]);
			$arr=array("CODIGO", "CONCEPTO", "PARCIAL M/E", "DEBE", "HABER");
			$pdf->Row($arr,10);
			//detalle comprobante
			$y=$y+15;
			$pdf->SetXY($x, $y);
			$pdf->Cell(97,465,'','1',1,'Q');
			$pdf->SetXY($x+97, $y);
			$pdf->Cell(189,465,'','1',1,'Q');
			$pdf->SetXY($x+189+97, $y);
			$pdf->Cell(97,465,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97, $y);
			$pdf->Cell(86,465,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97+86, $y);
			$pdf->Cell(86,465,'','1',1,'Q');
			$pdf->SetFont('Arial','',9);
			$pdf->SetXY($x+2, $y+2);
			//mostramos registro
			if($i==0)
			{
				$j1=0;
				$limite=23;
			}
			else
			{
				$j1=$j1+23;
				$limite=$limite+23;
			}
			for($j=$j1;$j<$limite;$j++)
			{
				if(count($cta)==$j)
				{
					break;
				}
				$pdf->SetX($x+2);
				$pdf->SetFont('Arial','',9);
				$pdf->SetWidths(array(97,189,95,84,84));
				$pdf->SetAligns(array("L","L","R","R","R"));
				//$arr=array($arr1[$i]);
				$arr=array($cta[$j], $conc[$j], $parc[$j], $debe[$j],$haber[$j]);
				$pdf->Row($arr,10);
				$y=$y+10;
				if($detalle[$j]<>'.')
				{
					$pdf->SetFont('Arial','',8);
					//$pdf->SetXY($x+2, $y+2);
					$pdf->SetWidths(array(97,180,95,84,84));
					$pdf->SetAligns(array("L","L","R","R","R"));
					//$arr=array($arr1[$i]);
					$arr=array('', $detalle[$j], '', '', '');
					$pdf->Row($arr,10);
					$y=$y+10;
				}
				//verificamos si hay mas detalles
				for($ii=0;$ii<count($subc);$ii++)
				{
					if($cta==$subc[$ii]['cta'])
					{
						$cliente = $subc[$ii]['cliente'];
						$Fechav = $subc[$ii]['Fechav']->format('Y-m-d');
						$debe='';
						$haber='';
						if($subc[$ii]['debe']!=0 and $subc[$ii]['debe']!='0.00')
						{
							$debe = number_format($subc[$ii]['debe'],2, '.', ',');
						}
						if($subc[$ii]['haber']!=0 and $subc[$ii]['haber']!='0.00')
						{
							$haber = number_format($subc[$ii]['haber'],2, '.', ',');
						}
						$pdf->SetX($x+2);
						$pdf->SetFont('Arial','I',7);
						//echo " aqui ";
						//	die();
						//$pdf->SetXY($x+2, $y+2);
						$pdf->SetWidths(array(97,94,94,48, 48,85,84));
						$pdf->SetAligns(array("L","L","L","R","R","R","R"));
						//$arr=array($arr1[$i]);
						if($subc[$ii]['No']<>'.')
						{
							$arr=array('', $cliente, 'No. '.$pdf->generaCeros($subc[$ii]['No'],7), $debe, $haber, '', '');
						}
						else
						{
							if($subc[$ii]['vp']<>0)
							{
								$arr=array('', $cliente, 'Valor Prima ', $subc[$ii]['vp'], $haber, '', '');
							}
							else
							{
								$arr=array('', $cliente, 'Venc. '.$Fechav, $debe, $haber, '', '');
							}
						}
						
						$pdf->Row($arr,10);
						$y=$y+10;
					}
				}
			}
			/*$pdf->SetWidths(array(97,189,97,86,86));
			$pdf->SetAligns(array("C","C","C","C","C"));
			//$arr=array($arr1[$i]);
			$arr=array('xxx', "CONCEPTO", "PARCIAL M/E", "DEBE", "HABER");
			$pdf->Row($arr,10);*/
			//$y=$y+10;
			//totales
			//$pos=150-$cantidad_reg*10;
			//echo $y;
			//die();
			//$y=$y+$pos-10;
			
			if($i<($pagi-2))
			{
				$y=465-10+160;
				$pdf->SetXY($x+2, $y+2);
				$pdf->SetFont('Arial','I',7);
				//$pdf->SetXY($x+2, $y+2);
				$pdf->SetWidths(array(150));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array('CONTINUARA EN LA SIGUIENTE PAGINA...');
				$pdf->Row($arr,10);
				$pdf->AddPage();
			}
		}
		$pdf->SetXY($x+2, $y+2);
		//si esta anulado imprimir
		if($status=='A')
		{
			$pdf->SetXY($x+8, $y+2);
			$pdf->SetFont('Arial','B',14);
			$pdf->SetFillColor(255, 255, 255); 
			$pdf->Cell(120,25,'','1',1,'Q',TRUE);
			//$pdf->cabeceraHorizontal(array(' '),$x+8,$y,120,30,20,5);
			//$pdf->cabeceraHorizontal(array(' '),$x+10,$y+5,115,23,20,5);
			$pdf->SetXY($x+10, $y+5);
			$pdf->Cell(115,20,'','1',1,'Q',TRUE);
			$pdf->SetXY($x+11, $y+7);
			$pdf->SetWidths(array(120));
			$pdf->SetAligns(array("C"));
			//$arr=array($arr1[$i]);
			//$pdf->cabeceraHorizontal(array(' '),$x,$y,96,15,20,5);
			$arr=array('ANULADO');
			$pdf->Row($arr,15);
			//$y=$y+15;
		}
		$y=465-10+160;
		$pdf->SetXY($x, $y);
		$pdf->Cell(383,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97+86, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetFont('Arial','',9);
		$pdf->SetXY($x+2, $y+2);
		$pdf->SetWidths(array(383,84,84));
		$pdf->SetAligns(array("R","R","R"));
		//$arr=array($arr1[$i]);
		$arr=array('TOTALES',  number_format($sumdb,2, '.', ','), number_format($sumcr,2, '.', ','));
		$pdf->Row($arr,10);
		$y=$y+15;
		$pdf->Footer1($pdf,$usuario,$x, $y);
		//die();
	}
	//$pdf->AddPage();
	//datos cliente
	/******************/
	/******************/
	/**************110****/
	//$pdf->cabeceraHorizontal(array(' '),20,185,574,90,20,5);
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY(41, 149);
	//para posicion automatica
	$y=35;
	
	if($imp1==null or $imp1==1)
	{
		$pdf->Output();
	}
	if($imp1==0)
	{
		$pdf->Output('TEMP/'.$id.'.pdf','F'); 
	}
}
//imprimir comprobante nota de debito
/* $stmt= variable con datos xml stmt2 transacciones stmt4 las Retenciones del IVA stmt5 las Retenciones de la fuente
   stmt6 SubCtas $id id del campo $formato formato del reporte aqui es pdf
   $nombre_archivo ruta y nombre del archivo xml generado $va 0 para saber si lee de una variable o 1 un archivo xml
   $imp1 paqra saber si se descarga o no null o 1 descarga 0 es para temp y adjuntar al correo
   $stmt2_count = cantidad Listar las Transacciones. 
   $stmt4_count = cantidad Listar las Retenciones del IVA.
   $stmt5_count = cantidad las Retenciones de la Fuente.
   $stmt6_count = cantidad Llenar SubCtas
   $stmt1 cabecera */
 function imprimirND($stmt, $stmt2, $stmt4, $stmt5, $stmt6, $stmt1, $id=null,$formato=null,$nombre_archivo=null,$va=null,$imp1=null,
 $stmt2_count=null,$stmt4_count=null,$stmt5_count=null,$stmt6_count=null)
{
	$pdf = new PDF('P','pt','LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		$TipoComp = $row[2];
		$Numero =$row[3];
		$Fecha = $row[4]->format('Y-m-d');
		$Concepto=$row[7];
	}
	//cabecera y pie de pagina
	while( $row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_NUMERIC) ) 
	{
		$t = $row1[2];
		$Fecha = $row1[5]->format('Y-m-d');
		$codigoB = $row1[6];
		$beneficiario = $row1[26];
		$concepto1 = $row1[8];
		$efectivo = number_format($row1[10],2, '.', ',');
		$est="Normal";
		if($t == 'A')
		{
			$est="ANULADO";
		}
		$usuario= $row1[20];
		//echo $t.' '.$Fecha.' '.$codigoB.' '.$beneficiario.' '.$concepto.' '.$efectivo.' '.$est.' '.$usuario;
	}
	$sumdb=0;
	$sumcr=0;
	$cantidad_reg=$stmt2_count+$stmt6_count;
	if($cantidad_reg<35)
	{
		//para sacar parte del header
		$i=0;
		$pag=1;
		$pdf->Header1($pdf,$Numero,$TipoComp,$Fecha,$pag);
		//logo
		$i=0;
		if($va==1)
		{
			//$autorizacion = simplexml_load_file($nombre_archivo);
		}
		else
		{
			//$stmt = str_replace("﻿", "", $stmt);
			//$autorizacion =simplexml_load_string($stmt);
		}
		$x=31;
		if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social'])
		{
			$y=90;
		}
		else
		{
			$y=80;
		}
		
		//concepto
		$pdf->SetXY($x, $y);
		$pdf->Cell(555,30,'','1',1,'Q');
		
		$pdf->SetXY($x+2, $y+2);
		
		$pdf->SetFont('Arial','B',9);
		
		$pdf->SetWidths(array(65));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array('Concepto de: ');
		$pdf->Row($arr,10);
		
		$pdf->SetXY($x+2+65, $y+2);
		
		$pdf->SetFont('Arial','',9);
		
		$pdf->SetWidths(array(488));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array($Concepto);
		$pdf->Row($arr,10);
		//titulo
		$y=$y+30;
		$pdf->SetXY($x, $y);
		$pdf->Cell(555,15,'','1',1,'Q');
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($x+2, $y+2);
		$pdf->SetWidths(array(535));
		$pdf->SetAligns(array("C"));
		//$arr=array($arr1[$i]);
		$arr=array('CONTABILIZACION');
		$pdf->Row($arr,10);
		//saber si es una o mas pagina
		//echo ' '.$stmt2_count.' '.$stmt6_count.' ';
		/*****agregar a funcion***********/
		//cabecera de tabla
		$y=$y+15;
		$pdf->SetXY($x, $y);
		
		$pdf->Cell(97,15,'','1',1,'Q');
		$pdf->SetXY($x+97, $y);
		$pdf->Cell(189,15,'','1',1,'Q');
		$pdf->SetXY($x+189+97, $y);
		$pdf->Cell(97,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97+86, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetXY($x+2, $y+2);
		$pdf->SetWidths(array(97,189,97,86,86));
		$pdf->SetAligns(array("C","C","C","C","C"));
		//$arr=array($arr1[$i]);
		$arr=array("CODIGO", "CONCEPTO", "PARCIAL M/E", "DEBE", "HABER");
		$pdf->Row($arr,10);
		//detalle comprobante
		$y=$y+15;
		$pdf->SetXY($x, $y);
		if($cantidad_reg<=10)
		{
			$cantidad_reg1=15*10;
			$pdf->Cell(97,150,'','1',1,'Q');
			$pdf->SetXY($x+97, $y);
			$pdf->Cell(189,150,'','1',1,'Q');
			$pdf->SetXY($x+189+97, $y);
			$pdf->Cell(97,150,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97, $y);
			$pdf->Cell(86,150,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97+86, $y);
			$pdf->Cell(86,150,'','1',1,'Q');
		}
		else
		{
			//$cantidad_reg1=$cantidad_reg*10;
			$x1=$x;
			$y1=$y;
			
		}
		//$pdf->SetFont('Arial','',9);
		//llenamos los detalles 
		$ii=0;
		$subc=array();
		while( $row1 = sqlsrv_fetch_array( $stmt6, SQLSRV_FETCH_NUMERIC) ) 
		{
			$subc[$ii]['cta']=$row1[0];
			$subc[$ii]['cliente']=$row1[3];
			$subc[$ii]['Fechav']=$row1[7];
			$subc[$ii]['debe']=$row1[5];
			$subc[$ii]['haber']=$row1[6];
			$subc[$ii]['No']=$row1[2];
			$subc[$ii]['vp']=$row1[9];
			
			$ii++;
		}
		$pdf->SetXY($x+2, $y+2);
		
		while( $row1 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC) ) 
		{
			$pdf->SetX($x+2);
			$pdf->SetFont('Arial','',9);
			$cta = $row1[0];
			$conc = $row1[1];
			$parc='';
			$debe='';
			$haber='';
			if($row1[2]!=0 and $row1[2]!='0.00')
			{
				$parc = number_format($row1[2],2, '.', ',');
			}
			if($row1[3]!=0 and $row1[3]!='0.00')
			{
				$sumdb=$sumdb+$row1[3];
				$debe = number_format($row1[3],2, '.', ',');
			}
			if($row1[4]!=0 and $row1[4]!='0.00')
			{
				$sumcr=$sumcr+$row1[4];
				$haber = number_format($row1[4],2, '.', ',');
			}
			$detalle = $row1[5];
			$status = $row1[13];
			
			$pdf->SetWidths(array(97,189,95,84,84));
			$pdf->SetAligns(array("L","L","R","R","R"));
			//$arr=array($arr1[$i]);
			$arr=array($cta, $conc, $parc, $debe,$haber);
			$pdf->Row($arr,10);
			$y=$y+10;
			if($detalle<>'.')
			{
				$pdf->SetFont('Arial','',8);
				//$pdf->SetXY($x+2, $y+2);
				$pdf->SetWidths(array(97,180,95,84,84));
				$pdf->SetAligns(array("L","L","R","R","R"));
				//$arr=array($arr1[$i]);
				$arr=array('', $detalle, '', '', '');
				$pdf->Row($arr,10);
				$y=$y+10;
			}
			//verificamos si hay mas detalles
			for($ii=0;$ii<count($subc);$ii++)
			{
				if($cta==$subc[$ii]['cta'])
				{
					$cliente = $subc[$ii]['cliente'];
					$Fechav = $subc[$ii]['Fechav']->format('Y-m-d');
					$debe='';
					$haber='';
					if($subc[$ii]['debe']!=0 and $subc[$ii]['debe']!='0.00')
					{
						$debe = number_format($subc[$ii]['debe'],2, '.', ',');
					}
					if($subc[$ii]['haber']!=0 and $subc[$ii]['haber']!='0.00')
					{
						$haber = number_format($subc[$ii]['haber'],2, '.', ',');
					}
					$pdf->SetX($x+2);
					$pdf->SetFont('Arial','I',7);
					//echo " aqui ";
					//	die();
					//$pdf->SetXY($x+2, $y+2);
					$pdf->SetWidths(array(97,94,94,48, 48,85,84));
					$pdf->SetAligns(array("L","L","L","R","R","R","R"));
					//$arr=array($arr1[$i]);
					if($subc[$ii]['No']<>'.')
					{
						$arr=array('', $cliente, 'No. '.$pdf->generaCeros($subc[$ii]['No'],7), $debe, $haber, '', '');
					}
					else
					{
						if($subc[$ii]['vp']<>0)
						{
							$arr=array('', $cliente, 'Valor Prima ', $subc[$ii]['vp'], $haber, '', '');
						}
						else
						{
							$arr=array('', $cliente, 'Venc. '.$Fechav, $debe, $haber, '', '');
						}
					}
					
					$pdf->Row($arr,10);
					$y=$y+10;
				}
			}
		}
		//detalle sub cuenta
		if($cantidad_reg>10)
		{
			$y=$y+20;
		}
		$y=$y+10;
		//$pdf->SetXY($x+2, ($pdf->GetY($y)-80));
		//si esta anulado imprimir
		if($status=='A')
		{
			$pdf->SetXY($x+8, $y+2);
			$pdf->SetFont('Arial','B',14);
			$pdf->SetFillColor(255, 255, 255); 
			$pdf->Cell(120,25,'','1',1,'Q',TRUE);
			//$pdf->cabeceraHorizontal(array(' '),$x+8,$y,120,30,20,5);
			//$pdf->cabeceraHorizontal(array(' '),$x+10,$y+5,115,23,20,5);
			$pdf->SetXY($x+10, $y+5);
			$pdf->Cell(115,20,'','1',1,'Q',TRUE);
			$pdf->SetXY($x+11, $y+7);
			$pdf->SetWidths(array(120));
			$pdf->SetAligns(array("C"));
			//$arr=array($arr1[$i]);
			//$pdf->cabeceraHorizontal(array(' '),$x,$y,96,15,20,5);
			$arr=array('ANULADO');
			$pdf->Row($arr,15);
			//$y=$y+15;
		}
		if($cantidad_reg>10)
		{
			$pdf->SetXY($x1, $y1);
			//echo $pdf->GetY($y);
			//die();
			$cantidad_reg1=$cantidad_reg*10+($pdf->GetY($y)-80);
			$pdf->Cell(97,$cantidad_reg1,'','1',1,'Q');
			$pdf->SetXY($x1+97, $y1);
			$pdf->Cell(189,$cantidad_reg1,'','1',1,'Q');
			$pdf->SetXY($x1+189+97, $y1);
			$pdf->Cell(97,$cantidad_reg1,'','1',1,'Q');
			$pdf->SetXY($x1+97+189+97, $y1);
			$pdf->Cell(86,$cantidad_reg1,'','1',1,'Q');
			$pdf->SetXY($x1+97+189+97+86, $y1);
			$pdf->Cell(86,$cantidad_reg1,'','1',1,'Q');
			$pos=$cantidad_reg1-$cantidad_reg*10;
		}
		else
		{
			$pos=$cantidad_reg1-10;
		}
		//totales
		
		//echo $y.' '.$pos.' '.$pdf->GetY($y);
		//die();
		if($cantidad_reg>10)
		{
			$y=$y+$pos-30;
		}
		else
		{
			$y=$y+$pos-10;
			$y=300;
		}
		
		$pdf->SetXY($x, $y);
		$pdf->Cell(383,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97+86, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetFont('Arial','',9);
		$pdf->SetXY($x+2, $y+2);
		$pdf->SetWidths(array(383,84,84));
		$pdf->SetAligns(array("R","R","R"));
		//$arr=array($arr1[$i]);
		$arr=array('TOTALES',  number_format($sumdb,2, '.', ','), number_format($sumcr,2, '.', ','));
		$pdf->Row($arr,10);
		$y=$y+15;
		$pdf->Footer1($pdf,$usuario,$x, $y);
		
	}
	else
	{
		//varias paginas
		//select * from lista_empresas where RUC_CI_NIC='1791863798001'
		//delete from lista_empresas where ID=175
		$pagi=ceil($cantidad_reg/35);
		//echo ' ddd '.$pagi;
		//die();
		//obtenemos los datos
		$i=0;
		$cta=array();
		$conc=array();
		$parc=array();
		$debe=array();
		$haber=array();
		$detalle = array();
		$status = array();
		//llenamos los detalles 
		$ii=0;
		$subc=array();
		while( $row1 = sqlsrv_fetch_array( $stmt6, SQLSRV_FETCH_NUMERIC) ) 
		{
			$subc[$ii]['cta']=$row1[0];
			$subc[$ii]['cliente']=$row1[3];
			$subc[$ii]['Fechav']=$row1[7];
			$subc[$ii]['debe']=$row1[5];
			$subc[$ii]['haber']=$row1[6];
			$subc[$ii]['No']=$row1[2];
			$subc[$ii]['vp']=$row1[9];
			
			$ii++;
		}
		while( $row1 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC) ) 
		{
			$cta[$i] = $row1[0];
			$conc[$i] = $row1[1];
			$parc[$i]='';
			$debe[$i]='';
			$haber[$i]='';
			if($row1[2]!=0 and $row1[2]!='0.00')
			{
				$parc[$i] = number_format($row1[2],2, '.', ',');
			}
			if($row1[3]!=0 and $row1[3]!='0.00')
			{
				$sumdb=$sumdb+$row1[3];
				$debe[$i] = number_format($row1[3],2, '.', ',');
			}
			if($row1[4]!=0 and $row1[4]!='0.00')
			{
				$sumcr=$sumcr+$row1[4];
				$haber[$i] = number_format($row1[4],2, '.', ',');
			}
			$detalle[$i] = $row1[5];
			$status[$i] = $row1[13];
			
			$i++;
		}
		/*****agregar a funcion***********/
		$pag=1;
		for($i=0;$i<$pagi-1;$i++)
		{
			//echo $i.'<br>';
			$pdf->Header1($pdf,$Numero,$TipoComp,$Fecha,$pag);
			$pag=$pag+1;
			$x=31;
			if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social'])
			{
				$y=90;
			}
			else
			{
				$y=80;
			}
			//concepto
			$pdf->SetXY($x, $y);
			$pdf->Cell(555,30,'','1',1,'Q');
			
			$pdf->SetXY($x+2, $y+2);
			
			$pdf->SetFont('Arial','B',9);
			
			$pdf->SetWidths(array(65));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$arr=array('Concepto de: ');
			$pdf->Row($arr,10);
			
			$pdf->SetXY($x+2+65, $y+2);
			
			$pdf->SetFont('Arial','',9);
			
			$pdf->SetWidths(array(468));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$arr=array($Concepto);
			$pdf->Row($arr,10);
			//titulo
			$y=$y+30;
			$pdf->SetXY($x, $y);
			$pdf->Cell(555,15,'','1',1,'Q');
			$pdf->SetFont('Arial','B',10);
			$pdf->SetXY($x+2, $y+2);
			$pdf->SetWidths(array(535));
			$pdf->SetAligns(array("C"));
			//$arr=array($arr1[$i]);
			$arr=array('CONTABILIZACION');
			$pdf->Row($arr,10);
			/*****agregar a funcion***********/
			//cabecera de tabla
			$y=$y+15;
			$pdf->SetXY($x, $y);
			$pdf->Cell(97,15,'','1',1,'Q');
			$pdf->SetXY($x+97, $y);
			$pdf->Cell(189,15,'','1',1,'Q');
			$pdf->SetXY($x+189+97, $y);
			$pdf->Cell(97,15,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97, $y);
			$pdf->Cell(86,15,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97+86, $y);
			$pdf->Cell(86,15,'','1',1,'Q');
			$pdf->SetXY($x+2, $y+2);
			$pdf->SetWidths(array(97,189,97,86,86));
			$pdf->SetAligns(array("C","C","C","C","C"));
			//$arr=array($arr1[$i]);
			$arr=array("CODIGO", "CONCEPTO", "PARCIAL M/E", "DEBE", "HABER");
			$pdf->Row($arr,10);
			//detalle comprobante
			$y=$y+15;
			$pdf->SetXY($x, $y);
			$pdf->Cell(97,510,'','1',1,'Q');
			$pdf->SetXY($x+97, $y);
			$pdf->Cell(189,510,'','1',1,'Q');
			$pdf->SetXY($x+189+97, $y);
			$pdf->Cell(97,510,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97, $y);
			$pdf->Cell(86,510,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97+86, $y);
			$pdf->Cell(86,510,'','1',1,'Q');
			$pdf->SetFont('Arial','',9);
			$pdf->SetXY($x+2, $y+2);
			//mostramos registro
			if($i==0)
			{
				$j1=0;
				$limite=23;
			}
			else
			{
				$j1=$j1+23;
				$limite=$limite+23;
			}
			for($j=$j1;$j<$limite;$j++)
			{
				if(count($cta)==$j)
				{
					break;
				}
				$pdf->SetX($x+2);
				$pdf->SetFont('Arial','',9);
				$pdf->SetWidths(array(97,189,95,84,84));
				$pdf->SetAligns(array("L","L","R","R","R"));
				//$arr=array($arr1[$i]);
				$arr=array($cta[$j], $conc[$j], $parc[$j], $debe[$j],$haber[$j]);
				$pdf->Row($arr,10);
				$y=$y+10;
				if($detalle[$j]<>'.')
				{
					$pdf->SetFont('Arial','',8);
					//$pdf->SetXY($x+2, $y+2);
					$pdf->SetWidths(array(97,180,95,84,84));
					$pdf->SetAligns(array("L","L","R","R","R"));
					//$arr=array($arr1[$i]);
					$arr=array('', $detalle[$j], '', '', '');
					$pdf->Row($arr,10);
					$y=$y+10;
				}
				//verificamos si hay mas detalles
				for($ii=0;$ii<count($subc);$ii++)
				{
					if($cta==$subc[$ii]['cta'])
					{
						$cliente = $subc[$ii]['cliente'];
						$Fechav = $subc[$ii]['Fechav']->format('Y-m-d');
						$debe='';
						$haber='';
						if($subc[$ii]['debe']!=0 and $subc[$ii]['debe']!='0.00')
						{
							$debe = number_format($subc[$ii]['debe'],2, '.', ',');
						}
						if($subc[$ii]['haber']!=0 and $subc[$ii]['haber']!='0.00')
						{
							$haber = number_format($subc[$ii]['haber'],2, '.', ',');
						}
						$pdf->SetX($x+2);
						$pdf->SetFont('Arial','I',7);
						//echo " aqui ";
						//	die();
						//$pdf->SetXY($x+2, $y+2);
						$pdf->SetWidths(array(97,94,94,48, 48,85,84));
						$pdf->SetAligns(array("L","L","L","R","R","R","R"));
						//$arr=array($arr1[$i]);
						if($subc[$ii]['No']<>'.')
						{
							$arr=array('', $cliente, 'No. '.$pdf->generaCeros($subc[$ii]['No'],7), $debe, $haber, '', '');
						}
						else
						{
							if($subc[$ii]['vp']<>0)
							{
								$arr=array('', $cliente, 'Valor Prima ', $subc[$ii]['vp'], $haber, '', '');
							}
							else
							{
								$arr=array('', $cliente, 'Venc. '.$Fechav, $debe, $haber, '', '');
							}
						}
						
						$pdf->Row($arr,10);
						$y=$y+10;
					}
				}
			}
			/*$pdf->SetWidths(array(97,189,97,86,86));
			$pdf->SetAligns(array("C","C","C","C","C"));
			//$arr=array($arr1[$i]);
			$arr=array('xxx', "CONCEPTO", "PARCIAL M/E", "DEBE", "HABER");
			$pdf->Row($arr,10);*/
			//$y=$y+10;
			//totales
			//$pos=150-$cantidad_reg*10;
			//echo $y;
			//die();
			//$y=$y+$pos-10;
			
			if($i<($pagi-2))
			{
				$y=510-10+160;
				$pdf->SetXY($x+2, $y+2);
				$pdf->SetFont('Arial','I',7);
				//$pdf->SetXY($x+2, $y+2);
				$pdf->SetWidths(array(150));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array('CONTINUARA EN LA SIGUIENTE PAGINA...');
				$pdf->Row($arr,10);
				$pdf->AddPage();
			}
		}
		//si esta anulado imprimir
		if($status=='A')
		{
			$pdf->SetXY($x+8, $y+2);
			$pdf->SetFont('Arial','B',14);
			$pdf->SetFillColor(255, 255, 255); 
			$pdf->Cell(120,25,'','1',1,'Q',TRUE);
			//$pdf->cabeceraHorizontal(array(' '),$x+8,$y,120,30,20,5);
			//$pdf->cabeceraHorizontal(array(' '),$x+10,$y+5,115,23,20,5);
			$pdf->SetXY($x+10, $y+5);
			$pdf->Cell(115,20,'','1',1,'Q',TRUE);
			$pdf->SetXY($x+11, $y+7);
			$pdf->SetWidths(array(120));
			$pdf->SetAligns(array("C"));
			//$arr=array($arr1[$i]);
			//$pdf->cabeceraHorizontal(array(' '),$x,$y,96,15,20,5);
			$arr=array('ANULADO');
			$pdf->Row($arr,15);
			//$y=$y+15;
		}
		$y=510-10+160;
		$pdf->SetXY($x, $y);
		$pdf->Cell(383,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97+86, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetFont('Arial','',9);
		$pdf->SetXY($x+2, $y+2);
		$pdf->SetWidths(array(383,84,84));
		$pdf->SetAligns(array("R","R","R"));
		//$arr=array($arr1[$i]);
		$arr=array('TOTALES',  number_format($sumdb,2, '.', ','), number_format($sumcr,2, '.', ','));
		$pdf->Row($arr,10);
		$y=$y+15;
		$pdf->Footer1($pdf,$usuario,$x, $y);
		//die();
	}
	//$pdf->AddPage();
	//datos cliente
	/******************/
	/******************/
	/**************110****/
	//$pdf->cabeceraHorizontal(array(' '),20,185,574,90,20,5);
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY(41, 149);
	//para posicion automatica
	$y=35;
	
	if($imp1==null or $imp1==1)
	{
		$pdf->Output();
	}
	if($imp1==0)
	{
		$pdf->Output('TEMP/'.$id.'.pdf','F'); 
	}
}
//imprimir comprobante nota credito
/* $stmt= variable con datos xml stmt2 transacciones stmt4 las Retenciones del IVA stmt5 las Retenciones de la fuente
   stmt6 SubCtas $id id del campo $formato formato del reporte aqui es pdf
   $nombre_archivo ruta y nombre del archivo xml generado $va 0 para saber si lee de una variable o 1 un archivo xml
   $imp1 paqra saber si se descarga o no null o 1 descarga 0 es para temp y adjuntar al correo
   $stmt2_count = cantidad Listar las Transacciones. 
   $stmt4_count = cantidad Listar las Retenciones del IVA.
   $stmt5_count = cantidad las Retenciones de la Fuente.
   $stmt6_count = cantidad Llenar SubCtas
   $stmt1 cabecera */
 function imprimirNC($stmt, $stmt2, $stmt4, $stmt5, $stmt6, $stmt1, $id=null,$formato=null,$nombre_archivo=null,$va=null,$imp1=null,
 $stmt2_count=null,$stmt4_count=null,$stmt5_count=null,$stmt6_count=null)
{
	$pdf = new PDF('P','pt','LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		$TipoComp = $row[2];
		$Numero =$row[3];
		$Fecha = $row[4]->format('Y-m-d');
		$Concepto=$row[7];
	}
	//cabecera y pie de pagina
	while( $row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_NUMERIC) ) 
	{
		$t = $row1[2];
		$Fecha = $row1[5]->format('Y-m-d');
		$codigoB = $row1[6];
		$beneficiario = $row1[26];
		$concepto1 = $row1[8];
		$efectivo = number_format($row1[10],2, '.', ',');
		$est="Normal";
		if($t == 'A')
		{
			$est="ANULADO";
		}
		$usuario= $row1[20];
		//echo $t.' '.$Fecha.' '.$codigoB.' '.$beneficiario.' '.$concepto.' '.$efectivo.' '.$est.' '.$usuario;
	}
	$sumdb=0;
	$sumcr=0;
	$cantidad_reg=$stmt2_count+$stmt6_count;
	if($cantidad_reg<35)
	{
		//para sacar parte del header
		$i=0;
		$pag=1;
		$pdf->Header1($pdf,$Numero,$TipoComp,$Fecha,$pag);
		//logo
		$i=0;
		if($va==1)
		{
			//$autorizacion = simplexml_load_file($nombre_archivo);
		}
		else
		{
			//$stmt = str_replace("﻿", "", $stmt);
			//$autorizacion =simplexml_load_string($stmt);
		}
		$x=31;
		if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social'])
		{
			$y=90;
		}
		else
		{
			$y=80;
		}
		
		//concepto
		$pdf->SetXY($x, $y);
		$pdf->Cell(555,30,'','1',1,'Q');
		
		$pdf->SetXY($x+2, $y+2);
		
		$pdf->SetFont('Arial','B',9);
		
		$pdf->SetWidths(array(65));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array('Concepto de: ');
		$pdf->Row($arr,10);
		
		$pdf->SetXY($x+2+65, $y+2);
		
		$pdf->SetFont('Arial','',9);
		
		$pdf->SetWidths(array(488));
		$pdf->SetAligns(array("L"));
		//$arr=array($arr1[$i]);
		$arr=array($Concepto);
		$pdf->Row($arr,10);
		//titulo
		$y=$y+30;
		$pdf->SetXY($x, $y);
		$pdf->Cell(555,15,'','1',1,'Q');
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($x+2, $y+2);
		$pdf->SetWidths(array(535));
		$pdf->SetAligns(array("C"));
		//$arr=array($arr1[$i]);
		$arr=array('CONTABILIZACION');
		$pdf->Row($arr,10);
		//saber si es una o mas pagina
		//echo ' '.$stmt2_count.' '.$stmt6_count.' ';
		/*****agregar a funcion***********/
		//cabecera de tabla
		$y=$y+15;
		$pdf->SetXY($x, $y);
		
		$pdf->Cell(97,15,'','1',1,'Q');
		$pdf->SetXY($x+97, $y);
		$pdf->Cell(189,15,'','1',1,'Q');
		$pdf->SetXY($x+189+97, $y);
		$pdf->Cell(97,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97+86, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetXY($x+2, $y+2);
		$pdf->SetWidths(array(97,189,97,86,86));
		$pdf->SetAligns(array("C","C","C","C","C"));
		//$arr=array($arr1[$i]);
		$arr=array("CODIGO", "CONCEPTO", "PARCIAL M/E", "DEBE", "HABER");
		$pdf->Row($arr,10);
		//detalle comprobante
		$y=$y+15;
		$pdf->SetXY($x, $y);
		if($cantidad_reg<=10)
		{
			$cantidad_reg1=15*10;
			$pdf->Cell(97,150,'','1',1,'Q');
			$pdf->SetXY($x+97, $y);
			$pdf->Cell(189,150,'','1',1,'Q');
			$pdf->SetXY($x+189+97, $y);
			$pdf->Cell(97,150,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97, $y);
			$pdf->Cell(86,150,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97+86, $y);
			$pdf->Cell(86,150,'','1',1,'Q');
		}
		else
		{
			//$cantidad_reg1=$cantidad_reg*10;
			$x1=$x;
			$y1=$y;
			
		}
		//$pdf->SetFont('Arial','',9);
		//llenamos los detalles 
		$ii=0;
		$subc=array();
		while( $row1 = sqlsrv_fetch_array( $stmt6, SQLSRV_FETCH_NUMERIC) ) 
		{
			$subc[$ii]['cta']=$row1[0];
			$subc[$ii]['cliente']=$row1[3];
			$subc[$ii]['Fechav']=$row1[7];
			$subc[$ii]['debe']=$row1[5];
			$subc[$ii]['haber']=$row1[6];
			$subc[$ii]['No']=$row1[2];
			$subc[$ii]['vp']=$row1[9];
			
			$ii++;
		}
		$pdf->SetXY($x+2, $y+2);
		
		while( $row1 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC) ) 
		{
			$pdf->SetX($x+2);
			$pdf->SetFont('Arial','',9);
			$cta = $row1[0];
			$conc = $row1[1];
			$parc='';
			$debe='';
			$haber='';
			if($row1[2]!=0 and $row1[2]!='0.00')
			{
				$parc = number_format($row1[2],2, '.', ',');
			}
			if($row1[3]!=0 and $row1[3]!='0.00')
			{
				$sumdb=$sumdb+$row1[3];
				$debe = number_format($row1[3],2, '.', ',');
			}
			if($row1[4]!=0 and $row1[4]!='0.00')
			{
				$sumcr=$sumcr+$row1[4];
				$haber = number_format($row1[4],2, '.', ',');
			}
			$detalle = $row1[5];
			$status = $row1[13];
			
			$pdf->SetWidths(array(97,189,95,84,84));
			$pdf->SetAligns(array("L","L","R","R","R"));
			//$arr=array($arr1[$i]);
			$arr=array($cta, $conc, $parc, $debe,$haber);
			$pdf->Row($arr,10);
			$y=$y+10;
			if($detalle<>'.')
			{
				$pdf->SetFont('Arial','',8);
				//$pdf->SetXY($x+2, $y+2);
				$pdf->SetWidths(array(97,180,95,84,84));
				$pdf->SetAligns(array("L","L","R","R","R"));
				//$arr=array($arr1[$i]);
				$arr=array('', $detalle, '', '', '');
				$pdf->Row($arr,10);
				$y=$y+10;
			}
			//verificamos si hay mas detalles
			for($ii=0;$ii<count($subc);$ii++)
			{
				if($cta==$subc[$ii]['cta'])
				{
					$cliente = $subc[$ii]['cliente'];
					$Fechav = $subc[$ii]['Fechav']->format('Y-m-d');
					$debe='';
					$haber='';
					if($subc[$ii]['debe']!=0 and $subc[$ii]['debe']!='0.00')
					{
						$debe = number_format($subc[$ii]['debe'],2, '.', ',');
					}
					if($subc[$ii]['haber']!=0 and $subc[$ii]['haber']!='0.00')
					{
						$haber = number_format($subc[$ii]['haber'],2, '.', ',');
					}
					$pdf->SetX($x+2);
					$pdf->SetFont('Arial','I',7);
					//echo " aqui ";
					//	die();
					//$pdf->SetXY($x+2, $y+2);
					$pdf->SetWidths(array(97,94,94,48, 48,85,84));
					$pdf->SetAligns(array("L","L","L","R","R","R","R"));
					//$arr=array($arr1[$i]);
					if($subc[$ii]['No']<>'.')
					{
						$arr=array('', $cliente, 'No. '.$pdf->generaCeros($subc[$ii]['No'],7), $debe, $haber, '', '');
					}
					else
					{
						if($subc[$ii]['vp']<>0)
						{
							$arr=array('', $cliente, 'Valor Prima ', $subc[$ii]['vp'], $haber, '', '');
						}
						else
						{
							$arr=array('', $cliente, 'Venc. '.$Fechav, $debe, $haber, '', '');
						}
					}
					
					$pdf->Row($arr,10);
					$y=$y+10;
				}
			}
		}
		//detalle sub cuenta
		if($cantidad_reg>10)
		{
			$y=$y+20;
		}
		$y=$y+10;
		//$pdf->SetXY($x+2, ($pdf->GetY($y)-80));
		
		//si esta anulado imprimir
		if($status=='A')
		{
			$pdf->SetXY($x+8, $y+2);
			$pdf->SetFont('Arial','B',14);
			$pdf->SetFillColor(255, 255, 255); 
			$pdf->Cell(120,25,'','1',1,'Q',TRUE);
			//$pdf->cabeceraHorizontal(array(' '),$x+8,$y,120,30,20,5);
			//$pdf->cabeceraHorizontal(array(' '),$x+10,$y+5,115,23,20,5);
			$pdf->SetXY($x+10, $y+5);
			$pdf->Cell(115,20,'','1',1,'Q',TRUE);
			$pdf->SetXY($x+11, $y+7);
			$pdf->SetWidths(array(120));
			$pdf->SetAligns(array("C"));
			//$arr=array($arr1[$i]);
			//$pdf->cabeceraHorizontal(array(' '),$x,$y,96,15,20,5);
			$arr=array('ANULADO');
			$pdf->Row($arr,15);
			//$y=$y+15;
		}
		if($cantidad_reg>10)
		{
			$pdf->SetXY($x1, $y1);
			//echo $pdf->GetY($y);
			//die();
			$cantidad_reg1=$cantidad_reg*10+($pdf->GetY($y)-80);
			$pdf->Cell(97,$cantidad_reg1,'','1',1,'Q');
			$pdf->SetXY($x1+97, $y1);
			$pdf->Cell(189,$cantidad_reg1,'','1',1,'Q');
			$pdf->SetXY($x1+189+97, $y1);
			$pdf->Cell(97,$cantidad_reg1,'','1',1,'Q');
			$pdf->SetXY($x1+97+189+97, $y1);
			$pdf->Cell(86,$cantidad_reg1,'','1',1,'Q');
			$pdf->SetXY($x1+97+189+97+86, $y1);
			$pdf->Cell(86,$cantidad_reg1,'','1',1,'Q');
			$pos=$cantidad_reg1-$cantidad_reg*10;
		}
		else
		{
			$pos=$cantidad_reg1-10;
		}
		//totales
		
		//echo $y.' '.$pos.' '.$pdf->GetY($y);
		//die();
		if($cantidad_reg>10)
		{
			$y=$y+$pos-30;
		}
		else
		{
			$y=$y+$pos-10;
			$y=300;
		}
		
		$pdf->SetXY($x, $y);
		$pdf->Cell(383,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97+86, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetFont('Arial','',9);
		$pdf->SetXY($x+2, $y+2);
		$pdf->SetWidths(array(383,84,84));
		$pdf->SetAligns(array("R","R","R"));
		//$arr=array($arr1[$i]);
		$arr=array('TOTALES',  number_format($sumdb,2, '.', ','), number_format($sumcr,2, '.', ','));
		$pdf->Row($arr,10);
		$y=$y+15;
		$pdf->Footer1($pdf,$usuario,$x, $y);
		
	}
	else
	{
		//varias paginas
		//select * from lista_empresas where RUC_CI_NIC='1791863798001'
		//delete from lista_empresas where ID=175
		$pagi=ceil($cantidad_reg/35);
		//echo ' ddd '.$pagi;
		//die();
		//obtenemos los datos
		$i=0;
		$cta=array();
		$conc=array();
		$parc=array();
		$debe=array();
		$haber=array();
		$detalle = array();
		$status = array();
		//llenamos los detalles 
		$ii=0;
		$subc=array();
		while( $row1 = sqlsrv_fetch_array( $stmt6, SQLSRV_FETCH_NUMERIC) ) 
		{
			$subc[$ii]['cta']=$row1[0];
			$subc[$ii]['cliente']=$row1[3];
			$subc[$ii]['Fechav']=$row1[7];
			$subc[$ii]['debe']=$row1[5];
			$subc[$ii]['haber']=$row1[6];
			$subc[$ii]['No']=$row1[2];
			$subc[$ii]['vp']=$row1[9];
			
			$ii++;
		}
		while( $row1 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC) ) 
		{
			$cta[$i] = $row1[0];
			$conc[$i] = $row1[1];
			$parc[$i]='';
			$debe[$i]='';
			$haber[$i]='';
			if($row1[2]!=0 and $row1[2]!='0.00')
			{
				$parc[$i] = number_format($row1[2],2, '.', ',');
			}
			if($row1[3]!=0 and $row1[3]!='0.00')
			{
				$sumdb=$sumdb+$row1[3];
				$debe[$i] = number_format($row1[3],2, '.', ',');
			}
			if($row1[4]!=0 and $row1[4]!='0.00')
			{
				$sumcr=$sumcr+$row1[4];
				$haber[$i] = number_format($row1[4],2, '.', ',');
			}
			$detalle[$i] = $row1[5];
			$status[$i] = $row1[13];
			
			$i++;
		}
		/*****agregar a funcion***********/
		$pag=1;
		for($i=0;$i<$pagi-1;$i++)
		{
			//echo $i.'<br>';
			$pdf->Header1($pdf,$Numero,$TipoComp,$Fecha,$pag);
			$pag=$pag+1;
			$x=31;
			if($_SESSION['INGRESO']['Nombre_Comercial']<>$_SESSION['INGRESO']['Razon_Social'])
			{
				$y=90;
			}
			else
			{
				$y=80;
			}
			//concepto
			$pdf->SetXY($x, $y);
			$pdf->Cell(555,30,'','1',1,'Q');
			
			$pdf->SetXY($x+2, $y+2);
			
			$pdf->SetFont('Arial','B',9);
			
			$pdf->SetWidths(array(65));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$arr=array('Concepto de: ');
			$pdf->Row($arr,10);
			
			$pdf->SetXY($x+2+65, $y+2);
			
			$pdf->SetFont('Arial','',9);
			
			$pdf->SetWidths(array(468));
			$pdf->SetAligns(array("L"));
			//$arr=array($arr1[$i]);
			$arr=array($Concepto);
			$pdf->Row($arr,10);
			//titulo
			$y=$y+30;
			$pdf->SetXY($x, $y);
			$pdf->Cell(555,15,'','1',1,'Q');
			$pdf->SetFont('Arial','B',10);
			$pdf->SetXY($x+2, $y+2);
			$pdf->SetWidths(array(535));
			$pdf->SetAligns(array("C"));
			//$arr=array($arr1[$i]);
			$arr=array('CONTABILIZACION');
			$pdf->Row($arr,10);
			/*****agregar a funcion***********/
			//cabecera de tabla
			$y=$y+15;
			$pdf->SetXY($x, $y);
			$pdf->Cell(97,15,'','1',1,'Q');
			$pdf->SetXY($x+97, $y);
			$pdf->Cell(189,15,'','1',1,'Q');
			$pdf->SetXY($x+189+97, $y);
			$pdf->Cell(97,15,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97, $y);
			$pdf->Cell(86,15,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97+86, $y);
			$pdf->Cell(86,15,'','1',1,'Q');
			$pdf->SetXY($x+2, $y+2);
			$pdf->SetWidths(array(97,189,97,86,86));
			$pdf->SetAligns(array("C","C","C","C","C"));
			//$arr=array($arr1[$i]);
			$arr=array("CODIGO", "CONCEPTO", "PARCIAL M/E", "DEBE", "HABER");
			$pdf->Row($arr,10);
			//detalle comprobante
			$y=$y+15;
			$pdf->SetXY($x, $y);
			$pdf->Cell(97,510,'','1',1,'Q');
			$pdf->SetXY($x+97, $y);
			$pdf->Cell(189,510,'','1',1,'Q');
			$pdf->SetXY($x+189+97, $y);
			$pdf->Cell(97,510,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97, $y);
			$pdf->Cell(86,510,'','1',1,'Q');
			$pdf->SetXY($x+97+189+97+86, $y);
			$pdf->Cell(86,510,'','1',1,'Q');
			$pdf->SetFont('Arial','',9);
			$pdf->SetXY($x+2, $y+2);
			//mostramos registro
			if($i==0)
			{
				$j1=0;
				$limite=23;
			}
			else
			{
				$j1=$j1+23;
				$limite=$limite+23;
			}
			for($j=$j1;$j<$limite;$j++)
			{
				if(count($cta)==$j)
				{
					break;
				}
				$pdf->SetX($x+2);
				$pdf->SetFont('Arial','',9);
				$pdf->SetWidths(array(97,189,95,84,84));
				$pdf->SetAligns(array("L","L","R","R","R"));
				//$arr=array($arr1[$i]);
				$arr=array($cta[$j], $conc[$j], $parc[$j], $debe[$j],$haber[$j]);
				$pdf->Row($arr,10);
				$y=$y+10;
				if($detalle[$j]<>'.')
				{
					$pdf->SetFont('Arial','',8);
					//$pdf->SetXY($x+2, $y+2);
					$pdf->SetWidths(array(97,180,95,84,84));
					$pdf->SetAligns(array("L","L","R","R","R"));
					//$arr=array($arr1[$i]);
					$arr=array('', $detalle[$j], '', '', '');
					$pdf->Row($arr,10);
					$y=$y+10;
				}
				//verificamos si hay mas detalles
				for($ii=0;$ii<count($subc);$ii++)
				{
					if($cta==$subc[$ii]['cta'])
					{
						$cliente = $subc[$ii]['cliente'];
						$Fechav = $subc[$ii]['Fechav']->format('Y-m-d');
						$debe='';
						$haber='';
						if($subc[$ii]['debe']!=0 and $subc[$ii]['debe']!='0.00')
						{
							$debe = number_format($subc[$ii]['debe'],2, '.', ',');
						}
						if($subc[$ii]['haber']!=0 and $subc[$ii]['haber']!='0.00')
						{
							$haber = number_format($subc[$ii]['haber'],2, '.', ',');
						}
						$pdf->SetX($x+2);
						$pdf->SetFont('Arial','I',7);
						//echo " aqui ";
						//	die();
						//$pdf->SetXY($x+2, $y+2);
						$pdf->SetWidths(array(97,94,94,48, 48,85,84));
						$pdf->SetAligns(array("L","L","L","R","R","R","R"));
						//$arr=array($arr1[$i]);
						if($subc[$ii]['No']<>'.')
						{
							$arr=array('', $cliente, 'No. '.$pdf->generaCeros($subc[$ii]['No'],7), $debe, $haber, '', '');
						}
						else
						{
							if($subc[$ii]['vp']<>0)
							{
								$arr=array('', $cliente, 'Valor Prima ', $subc[$ii]['vp'], $haber, '', '');
							}
							else
							{
								$arr=array('', $cliente, 'Venc. '.$Fechav, $debe, $haber, '', '');
							}
						}
						
						$pdf->Row($arr,10);
						$y=$y+10;
					}
				}
			}
			/*$pdf->SetWidths(array(97,189,97,86,86));
			$pdf->SetAligns(array("C","C","C","C","C"));
			//$arr=array($arr1[$i]);
			$arr=array('xxx', "CONCEPTO", "PARCIAL M/E", "DEBE", "HABER");
			$pdf->Row($arr,10);*/
			//$y=$y+10;
			//totales
			//$pos=150-$cantidad_reg*10;
			//echo $y;
			//die();
			//$y=$y+$pos-10;
			
			if($i<($pagi-2))
			{
				$y=510-10+160;
				$pdf->SetXY($x+2, $y+2);
				$pdf->SetFont('Arial','I',7);
				//$pdf->SetXY($x+2, $y+2);
				$pdf->SetWidths(array(150));
				$pdf->SetAligns(array("L"));
				//$arr=array($arr1[$i]);
				$arr=array('CONTINUARA EN LA SIGUIENTE PAGINA...');
				$pdf->Row($arr,10);
				$pdf->AddPage();
			}
		}
		
		//si esta anulado imprimir
		if($status=='A')
		{
			$pdf->SetXY($x+8, $y+2);
			$pdf->SetFont('Arial','B',14);
			$pdf->SetFillColor(255, 255, 255); 
			$pdf->Cell(120,25,'','1',1,'Q',TRUE);
			//$pdf->cabeceraHorizontal(array(' '),$x+8,$y,120,30,20,5);
			//$pdf->cabeceraHorizontal(array(' '),$x+10,$y+5,115,23,20,5);
			$pdf->SetXY($x+10, $y+5);
			$pdf->Cell(115,20,'','1',1,'Q',TRUE);
			$pdf->SetXY($x+11, $y+7);
			$pdf->SetWidths(array(120));
			$pdf->SetAligns(array("C"));
			//$arr=array($arr1[$i]);
			//$pdf->cabeceraHorizontal(array(' '),$x,$y,96,15,20,5);
			$arr=array('ANULADO');
			$pdf->Row($arr,15);
			//$y=$y+15;
		}
		$y=510-10+160;
		$pdf->SetXY($x, $y);
		$pdf->Cell(383,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetXY($x+97+189+97+86, $y);
		$pdf->Cell(86,15,'','1',1,'Q');
		$pdf->SetFont('Arial','',9);
		$pdf->SetXY($x+2, $y+2);
		$pdf->SetWidths(array(383,84,84));
		$pdf->SetAligns(array("R","R","R"));
		//$arr=array($arr1[$i]);
		$arr=array('TOTALES',  number_format($sumdb,2, '.', ','), number_format($sumcr,2, '.', ','));
		$pdf->Row($arr,10);
		$y=$y+15;
		$pdf->Footer1($pdf,$usuario,$x, $y);
		//die();
	}
	//$pdf->AddPage();
	//datos cliente
	/******************/
	/******************/
	/**************110****/
	//$pdf->cabeceraHorizontal(array(' '),20,185,574,90,20,5);
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY(41, 149);
	//para posicion automatica
	$y=35;
	
	if($imp1==null or $imp1==1)
	{
		$pdf->Output();
	}
	if($imp1==0)
	{
		$pdf->Output('TEMP/'.$id.'.pdf','F'); 
	}
}

?>