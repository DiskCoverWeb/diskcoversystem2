<?php
    error_reporting(E_ALL);
ini_set('display_errors', '1');  
	//require_once('fpdf.php');
	require_once('PDF_MC_Table.php');
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
	
class PDF1 extends PDF_MC_Table
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
		}
		else
		{
			$logo="diskcover";
		}
		$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
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
		$arr=array('R.U.C '.$_SESSION['INGRESO']["RUCEnt"]);
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
		if($TipoComp='CD')
		{
			$arr=array('COMPROBANTE DE DIARIO');
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
		$arr=array('COTIZACIÓN');
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
	$pdf = new PDF1('P','pt','LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();
	
	
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




