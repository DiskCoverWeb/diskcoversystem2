<?php
include(dirname(__DIR__,2).'/modelo/inventario/CatalogoM.php');
if(!class_exists('cabecera_pdf'))
{
  require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
}

$controlador = new CatalogoC();
if(isset($_GET['ExcelListarCatalogoInventario']))
{
	echo json_encode($controlador->generarExcelListarCatalogoInventario());
}else
if(isset($_GET['PdfRListarCatalogoInventario']))
{
	echo json_encode($controlador->generarPdfRListarCatalogoInventario());
}else
if(isset($_GET['ListarCatalogoInventario']))
{
	echo json_encode($controlador->ListarCatalogoInventario());
}

class CatalogoC
{
    private $modelo; 

    function __construct()
    {
       	$this->modelo = new  CatalogoM();
    }

    function ListarCatalogoInventario() {
		$sSQL = $this->getSqlListarCatalogoInventario($_POST);
		$medida = $_POST["heightDisponible"]-36;
		$DGQuery = grilla_generica_new($sSQL);
		//print_r($DGQuery);
		return $DGQuery['data'];
	}

	function generarExcelListarCatalogoInventario(){
		$sql = $this->getSqlListarCatalogoInventario($_GET);
		return exportar_excel_generico_SQl("PRODUCTOS DE INVENTARIO",$sql);
	}

	function generarPdfRListarCatalogoInventario(){
		$sql = $this->getSqlListarCatalogoInventario($_GET, pdf:true);
		$result = $this->modelo->SelectDatos($sql);
		$campos = array();
		foreach ($result[0] as $key => $value) {
		  array_push($campos,$key);
		}
		$ali =array("C","L","L","R","R","R","R");
		$medi =array(10,30,130,15,30,25,35);
		$pdf = new cabecera_pdf();  
		$titulo = "PRODUCTOS DE INVENTARIO";
		$mostrar = true;
		$sizetable =8;
		$tablaHTML = array();
		$tablaHTML[0]['medidas']=$medi;
		$tablaHTML[0]['alineado']=$ali;
		$tablaHTML[0]['datos']=$campos;
		$tablaHTML[0]['estilo']='BI';
		$tablaHTML[0]['borde'] = '1';
		$pos = 1;
		foreach ($result as $key => $value) {
		  $datos = array();
		  foreach ($value as $key1 => $valu) {
		    if ( (is_int($valu) || is_numeric($valu))) {
		      array_push($datos, number_format($valu, 2, '.', ''));
		    } else {
		      array_push($datos, $valu);
		    }
		  }
		  $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		  $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		  $tablaHTML[$pos]['datos']=$datos;
		  $tablaHTML[$pos]['estilo']='I';
		  $tablaHTML[$pos]['borde'] = '1';
		  $pos = $pos+1;
		}
		$pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,"","",$sizetable,$mostrar, orientacion: 'L');
	}

	function getSqlListarCatalogoInventario($POST, $pdf=false){

		$Codigo = CambioCodigoKardex($POST['MBoxCtaI']);
		$Codigo1 = CambioCodigoKardex($POST['MBoxCtaF']);

		if ($Codigo == "") $Codigo = G_NINGUNO;
		if ($Codigo1 == "") $Codigo1 = G_NINGUNO;

		$sSQL = $this->modelo->getSqlListarCatalogoInventario($Codigo, $Codigo1, $pdf);
		
		return $sSQL;
	}
}