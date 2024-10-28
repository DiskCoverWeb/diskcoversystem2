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
    private $CatalogoM; 
    private $NumEmpresa; 
    private $Periodo_Contable;

    function __construct()
    {
       	$this->CatalogoM = new  CatalogoM();
		$this->NumEmpresa = $_SESSION['INGRESO']['item'];
		$this->Periodo_Contable = $_SESSION['INGRESO']['periodo'];     
    }

    public function ListarCatalogoInventario() {
		$sSQL = $this->getSqlListarCatalogoInventario($_POST);
		$medida = $_POST["heightDisponible"]-36;
		$DGQuery = grilla_generica_new($sSQL,'Catalogo_Productos','ProductoCatalogo',"PRODUCTOS DE INVENTARIO",$botones=false,$check=false,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,$medida,$num_decimales=2,false,$paginacion_view= false,$estilo=1, $class_titulo='text-left');

		return compact('DGQuery');
	}

	public function generarExcelListarCatalogoInventario(){
		$sql = $this->getSqlListarCatalogoInventario($_GET);
		return exportar_excel_generico_SQl("PRODUCTOS DE INVENTARIO",$sql);
	}

	public function generarPdfRListarCatalogoInventario(){
		$sql = $this->getSqlListarCatalogoInventario($_GET, pdf:true);
		$result = $this->CatalogoM->SelectDatos($sql);
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

	public function getSqlListarCatalogoInventario($POST, $pdf=false){

		$Codigo = CambioCodigoKardex($POST['MBoxCtaI']);
		$Codigo1 = CambioCodigoKardex($POST['MBoxCtaF']);

		if ($Codigo == "") $Codigo = G_NINGUNO;
		if ($Codigo1 == "") $Codigo1 = G_NINGUNO;

		if($pdf){
			$sSQL = "SELECT TC,Codigo_Inv,Producto,PVP,Codigo_Barra,Unidad," .
			"Reg_Sanitario " ;
		}else{
			$sSQL = "SELECT TC,Codigo_Inv,Producto,PVP,Codigo_Barra,Cta_Inventario,Unidad,Cantidad," .
			"Cta_Costo_Venta,Cta_Ventas,Cta_Ventas_0,Cta_Ventas_Ant,Cta_Venta_Anticipada," .
			"IVA,INV,Codigo_IESS, Codigo_RES, Marca,Reg_Sanitario,Ayuda " ;
		}

		$sSQL .=   " FROM Catalogo_Productos " .
		"WHERE Item = '" . $this->NumEmpresa . "' " .
		"AND Periodo = '" . $this->Periodo_Contable . "' ";
		if ($Codigo != G_NINGUNO && $Codigo1 != G_NINGUNO) {
			$sSQL .= "AND Codigo_Inv BETWEEN '" . $Codigo . "' and '" . $Codigo1 . "' ";
		}
		if (@$_POST['CheqPM'] == 1) $sSQL .= "AND TC = 'P' ";
		$sSQL .= "ORDER BY Codigo_Inv ";

		return $sSQL;
	}
}