<?php 
@session_start();
if(!class_exists('PDF_MC_Table'))
{
	require('PDF_MC_Table.php');
}
if (!class_exists('FPDF')) {
    //$mi_clase = new MiClase();
   require('fpdf.php');
}

/**
 * 
 */
class generar_codigo_barras
{
	
	function __construct()
	{
		// code...
	}

	function generar_barras($cantidad=1,$datos=[])
	{
		// print_r($_SESSION['INGRESO']);die();
		// print_r($datos);die();

		$pdf = new  PDF_MC_Table();
		$pdf->AddPage();
		$pdf->SetFont('Arial','',6);

		//A set
		$x = 10;$y = 10;
		$w = 32;$h=10;
		$ln = 2;

		for ($i=0; $i < $cantidad ; $i++) { 
			
			$code1=$datos['Codigo_Barra'];
			if($code1=='' || $code1=='.'){$code1='0000000000';}
			
			$pdf->Code128($x,$y,$code1,$w,$h);
			$pdf->SetXY($x,$pdf->GetY()+8);
			$pdf->Ln($ln+1);		
			// $pdf->MultiCell($w+5,$ln,$_SESSION['INGRESO']['noempr'].'  *'.$_SESSION['INGRESO']['Nombre_Comercial'].'*');
			$pdf->Cell($w,$ln,$_SESSION['INGRESO']['noempr'].'  *'.$_SESSION['INGRESO']['Nombre_Comercial'].'*');
			// $pdf->Write($ln,$_SESSION['INGRESO']['noempr'].'  *'.$_SESSION['INGRESO']['Nombre_Comercial'].'*');
			
			$pdf->Ln($ln);
			$pdf->SetXY($x,$pdf->GetY());		
			// $pdf->MultiCell($w,$ln,$code1.'   '.$datos['PVP']);
			$pdf->Cell($x,$ln,$code1.'   '.$datos['PVP']);
			// $pdf->Write($ln,$code1.'         '.$datos['PVP']);
			
			$pdf->Ln($ln);
			$pdf->SetXY($x,$pdf->GetY());		
			// $pdf->MultiCell($w+5,$ln,$datos['Producto']);
			$pdf->Cell($x,$ln,$datos['Producto']);
			// $pdf->Write($ln,$datos['Producto']);
			$pdf->Ln(5);
			$x = $pdf->GetX();
			$y = $pdf->GetY();

		}


		$pdf->Output();

	}

	function generar_barras_grupo($datos)
	{
		// print_r($_SESSION['INGRESO']);die();
		// print_r($datos);die();
		$pdf = new PDF_MC_Table();
		$pdf->AddPage();
		$pdf->SetFont('Arial','',6);

		//A set
		$x = 10;$y = 10;
		$w = 32;$h=10;
		$ws =40;
		$ln = 2;
		$margen_y = 15;
		$margen_x = 8;
		$sizetable=12;
		$num = 6; //cantidad de etiquetas


		// fore

		// $tablaHTML[0]['medidas']=array($ws,$ws,$ws,$ws,$ws,$ws);
		// $tablaHTML[0]['alineado']=array('L','L','L','L','L','L');
		$medi = array();$ali =array();
		for ($k=0; $k < $num; $k++) { 
			array_push($medi, $ws);
			array_push($ali,'L');
		}
 //se posocoopnal los codigos de barra
		$i=1;$j=0;$l=0;
		$tablaHTML[$j]['datos']=array();
		foreach ($datos as $key => $value) {

			$code1=$value['Codigo_Barra'];
			if($code1=='' || $code1=='.'){$code1='0000000000';}

			$tablaHTML[$j]['medidas']=$medi;
			$tablaHTML[$j]['alineado']=$ali;

			if($i==$num)
			{  	
				$y = $y+$h+$margen_y;
				$x = 10; 			
				$i=1;
				$j+=1;
				$tablaHTML[$j]['datos']=array();
				$tablaHTML[$j]['medidas']=$medi;
				$tablaHTML[$j]['alineado']=$ali;
				// array_push($tablaHTML[$j]['datos'],$pdf->Code128($x,$y,$code1 ,$w,$h));
				
			}

			array_push($tablaHTML[$j]['datos'],$pdf->Code128($x,$y,$code1 ,$w,$h));
			$x = $w+$x+$margen_x;
			$i+=1;
		}


		// se colocan los nombres de las etiquetas
		$x = 9;$y = 10;
		$w = 32;$h=10;
		$ws =40;
		$ln = 2;
		$sizetable=12;
		$i =1;
		foreach ($datos as $key => $value) {
			$code1=$value['Codigo_Barra'];
			if($code1=='' || $code1=='.'){$code1='0000000000';}
			if($i==$num)
			{
				$y = $y+$h+$margen_y;
				$x = 10; 	
				$i=1;
				// $pdf->SetXY($x,$h+$y);				
				// $pdf->MultiCell($ws,$ln,$code1);
				// $pdf->SetXY($x,$pdf->GetY()+4);		
				// $pdf->MultiCell($ws,$ln,utf8_decode($value['Producto']));
				// $pdf->SetXY($x,$pdf->GetY()+4);		
				// $pdf->MultiCell($ws,$ln,$code1);
			}
				$pdf->SetXY($x,$h+$y);				
				$pdf->MultiCell($ws,$ln,$code1);
				$pdf->SetXY($x,$h+$y+2);		
				$pdf->MultiCell($ws,$ln,utf8_decode($value['Producto']));
				$pdf->SetXY($x,$pdf->GetY());		
				$pdf->MultiCell($ws,$ln,utf8_decode('*'.$_SESSION['INGRESO']['Nombre_Comercial'].'*'));
				$x = $w+$x+$margen_x;
			

			$i+=1;
		}
		


		// print_r($tablaHTML);die();

		foreach ($tablaHTML as $key => $value){
		    	if(isset($value['estilo']) && $value['estilo']!='')
		    	{
		    		$pdf->SetFont('Arial',$value['estilo'],$sizetable);
		    		$estiloRow = $value['estilo'];
		    	}else
		    	{
		    		$pdf->SetFont('Arial','',$sizetable);
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
		       $pdf->SetWidths($value['medidas']);
			   $pdf->SetAligns($value['alineado']);
			   //print_r($value['datos']);
			   $arr= $value['datos'];
			   $pdf->Row($arr,4,$borde,$estiloRow);		    	
		    }

		$pdf->Output();

	}
}

?>