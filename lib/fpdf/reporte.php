<?php
    error_reporting(E_ALL);
ini_set('display_errors', '1');  
	//require('fpdf.php');
	require('PDF_MC_Table.php');

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
	
}//Fin de la clase


//Creación del objeto de la clase heredada
/**/

$pdf = new PDF('L','pt','LETTER');
$pdf->AliasNbPages('TPAG');
$pdf->SetTopMargin(5);
$pdf->SetLeftMargin(15);
$pdf->SetRightMargin(5);
$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'¡Hola, Mundo!');
$pdf->Ln(10);
$pdf->SetFont('Times','',12);
for($i=1;$i<=40;$i++)
    $pdf->Cell(0,10,'Imprimiendo línea número '.$i,0,1);
//$pdf->AddPage();
//textos y formato
$title = '20000 Leguas de Viaje Submarino';
$pdf->SetTitle($title);
$pdf->SetAuthor('Julio Verne');
$pdf->PrintChapter(1,'UN RIZO DE HUIDA','20k_c1.txt');
$pdf->PrintChapter(2,'LOS PROS Y LOS CONTRAS','20k_c2.txt');
//$pdf->AddPage();

//tablas
// Títulos de las columnas
$header = array('País', 'Capital', 'Superficie (km2)', 'Pobl. (en miles)');
// Carga de datos
$data = $pdf->LoadData('paises.txt');
$pdf->SetFont('Arial','',14);
$pdf->AddPage();
$pdf->BasicTable($header,$data);
$pdf->AddPage();
$pdf->ImprovedTable($header,$data);
$pdf->AddPage();
$pdf->FancyTable($header,$data);

//html
$html = 'Ahora puede imprimir fácilmente texto mezclando diferentes estilos: <b>negrita</b>, <i>itálica</i>,
<u>subrayado</u>, o ¡ <b><i><u>todos a la vez</u></i></b>!<br><br>También puede incluir enlaces en el
texto, como <a href="http://www.fpdf.org">www.fpdf.org</a>, o en una imagen: pulse en el logotipo.<br>
<br><br><br><br>';

$pdf->AddPage();
$pdf->SetFont('Arial','',20);
$pdf->Write(5,'Para saber qué hay de nuevo en este tutorial, pulse ');
$pdf->SetFont('','U');
$link = $pdf->AddLink();
$pdf->Write(5,'aquí',$link);
$pdf->SetFont('');
// Segunda página
$pdf->AddPage();
$pdf->SetLink($link);
$logo="DEFAULT";
$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',10,12,30,0,'','http://www.fpdf.org');
$pdf->SetLeftMargin(45);
$pdf->SetFontSize(14);
$pdf->WriteHTML($html);
$pdf->AddPage();


/*$pdf->SetFillColor(80, 150, 200);
$pdf->Rect(20, 50, 95, 20, 'F');
$pdf->Line(20, 50, 10, 40);
$pdf->SetXY(20, 50);
$pdf->Cell(15, 6, '10, 10', 0 , 1); //Celda*/


$pdf->SetFillColor(80, 150, 200);
$pdf->Rect(20, 50, 95, 20, 'F');
$pdf->Line(20, 50, 10, 40);
$pdf->SetXY(20, 50);
$pdf->Cell(15, 6, '10, 10', 0 , 1); //Celda

//Amarillo
$pdf->SetFillColor(255, 215, 0);
$pdf->Rect(110, 10, 45 , 20, 'F');
$pdf->Line(110, 10, 115, 15);
$pdf->SetXY(115, 15);
$pdf->Cell(15, 6, '110, 10', 0 , 1);
//Verde
$pdf->SetFillColor(0, 128, 0);
$pdf->Rect(160, 10, 40 , 20, 'F');
$pdf->Line(160, 10, 165, 15);
$pdf->SetXY(165, 15  );
$pdf->Cell(15, 6, '160, 10', 0 , 1);
//========================================
 
//========================================
//  Segundo bloque - 1 rectángulo       ==
//========================================
//Salmón
$pdf->SetFillColor(255, 99, 71);
$pdf->Rect(10, 35, 190, 140, 'F');
$pdf->Line(10, 35, 15, 40);
$pdf->SetXY(15, 40);
$pdf->Cell(15, 6, '10, 35', 0 , 1);
//========================================
 
//========================================
//  Tercer bloque - 2 rectángulos       ==
//========================================
//Rosa
$pdf->SetFillColor(255, 20, 147);
$pdf->Rect(10, 180, 90, 50, 'F');
$pdf->Line(10, 180, 15, 185);
$pdf->SetXY(15, 185);
$pdf->Cell(15, 6, '10, 180', 0 , 1);
//Café
$pdf->SetFillColor(233, 150, 122);
$pdf->Rect(110, 180, 90, 50, 'F');
$pdf->Line(110, 180, 115, 185);
$pdf->SetXY(115, 185);
$pdf->Cell(15, 6, '110, 180', 0 , 1);
//========================================
 
//========================================
//  Cuarto bloque - 6 rectángulos       ==
//========================================
//Verde
$pdf->SetFillColor(124, 252, 0);
$pdf->Rect(10, 235, 40, 25, 'F');
$pdf->Line(10, 235, 15, 240);
$pdf->SetXY(15, 240);
$pdf->Cell(15, 6, '10, 235', 0 , 1);
//Café
$pdf->SetFillColor(160 ,82, 40);
$pdf->Rect(60, 235, 40, 25, 'F');
$pdf->Line(60, 235, 65, 240);
$pdf->SetXY(65, 240);
$pdf->Cell(15, 6, '60, 235', 0 , 1);
//Marrón
$pdf->SetFillColor(128, 0 ,0);
$pdf->Rect(10, 265, 40, 25, 'F');
$pdf->Line(10, 265, 15, 270);
$pdf->SetXY(15, 270);
$pdf->Cell(15, 6, '10, 265', 0 , 1);
//Morado
$pdf->SetFillColor(153, 50, 204);
$pdf->Rect(60, 265, 40, 25, 'F');
$pdf->Line(60, 265, 65, 270);
$pdf->SetXY(65, 270);
$pdf->Cell(15, 6, '60, 265', 0 , 1);
//Azul
$pdf->SetFillColor(0, 191, 255);
$pdf->Rect(110, 235, 90, 25, 'F');
$pdf->Line(110, 235, 115, 240);
$pdf->SetXY(115, 240);
$pdf->Cell(15, 6, '110, 235', 0 , 1);
//Verde
$pdf->SetFillColor(173, 255, 47);
$pdf->Rect(110, 265, 90, 25, 'F');
$pdf->Line(110, 265, 115, 270);
$pdf->SetXY(115, 270);
$pdf->Cell(15, 6, '110, 265', 0 , 1);
$pdf->AddPage();

$miCabecera = array('Nombre de campo', 'Apellido', 'Matrícula campo');
 
$misDatos = array(
            array('nombre' => 'Esperbeneplatoledo', 'apellido' => 'Martínez', 'matricula' => '20420423'),
            array('nombre' => 'Araceli', 'apellido' => 'Morales', 'matricula' =>  '204909'),
            array('nombre' => 'Georginadavabulus', 'apellido' => 'Galindo', 'matricula' =>  '2043442'),
            array('nombre' => 'Luis', 'apellido' => 'Dolores', 'matricula' => '20411122'),
            array('nombre' => 'Mario', 'apellido' => 'Linares', 'matricula' => '2049990'),
            array('nombre' => 'Viridianapaliragama', 'apellido' => 'Badillo', 'matricula' => '20418855'),
            array('nombre' => 'Yadiramentoladosor', 'apellido' => 'García', 'matricula' => '20443335')
            );
			
 $pdf->Ln(10);
$pdf->tablaHorizontal($miCabecera, $misDatos);

$pdf->AddPage();
$pdf->cabeceraHorizontal(array('fgfdgfdgfdgfdgdfgffdgfdgfdgfdfdfd'));
$pdf->Ln(50);
$pdf->SetWidths(array(70,80,80,80,80,80,80,80,70));
$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C'));
$arr=array('Csssssssssssssssssssssssssssssssssssssssssssssssssss','C','C','C','C','C','C','C','C');
$pdf->Row($arr,13);

$pdf->Output();
?>




