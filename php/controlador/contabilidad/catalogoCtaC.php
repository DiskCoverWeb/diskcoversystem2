<?php
include(dirname(__DIR__,2).'/modelo/contabilidad/catalogoCtaM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */

if(isset($_GET['consultar']))
{
	$controlador = new catalogoCtaC();
	$parametros = $_POST['parametros'];
	//print_r( $parametros);
	echo json_encode($controlador->cargar_datos($parametros));
}
if(isset($_GET['imprimir_pdf']))
{
	$controlador = new catalogoCtaC();
	$parametros = array('OpcT'=>$_GET["OpcT"],'OpcG'=>$_GET["OpcG"],'OpcD'=>$_GET["OpcD"],'txt_CtaI'=>$_GET['txt_CtaI'],'txt_CtaF'=>$_GET['txt_CtaF']);
	$controlador->imprimir_pdf($parametros);

}
if(isset($_GET['imprimir_excel']))
{   
	// print_r($_GET);die();
	$controlador = new catalogoCtaC();
	$parametros = $_GET;
	$controlador->imprimir_excel($parametros);	
}
class catalogoCtaC
{
	
	private $modelo;
	private $pdf;
	
	function __construct()
	{
	   $this->modelo = new  catalogoCtaM();	   
	   $this->pdf = new cabecera_pdf();
	}


	function cargar_datos($parametros)
	{     
      $datos = $this->modelo->cargar_datos_cuenta_tabla($parametros['OpcT'],$parametros['OpcG'],$parametros['OpcD'],$parametros['txt_CtaI'],$parametros['txt_CtaF']);
      // print_r($datos);die();
      return $datos;
		
	}

	function imprimir_excel($parametros)
	{

	 $_SESSION['INGRESO']['ti']='PLAN DE CUENTAS';
	 $this->modelo->cargar_datos_cuenta_datos($parametros['OpcT'],$parametros['OpcG'],$parametros['OpcD'],$parametros['txt_CtaI'],$parametros['txt_CtaF'],true);
	}

	function imprimir_pdf($parametros)
	{
	    $datos = $this->modelo->cargar_datos_cuenta_datos($parametros['OpcT'],$parametros['OpcG'],$parametros['OpcD'],$parametros['txt_CtaI'],$parametros['txt_CtaF']);
		// print_r($datos);die();
	    $tablaHTML = array();
		$tablaHTML[0]['medidas']=array(15,10,10,10,25,70,25,25);
		$tablaHTML[0]['alineado']=array('L','L','L','L','L','L','R','R');
        $tablaHTML[0]['datos']=array('Clave','TC','ME','DG','Codigo','Cuenta','Presupuesto','Codigo_ext');
		$tablaHTML[0]['estilo']='B';
		$tablaHTML[0]['borde'] = '1';
		$pos=1;

		 foreach ($datos as $key => $value) {
			$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
	        $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
	        $tablaHTML[$pos]['datos']=array($value['Clave'],$value['TC'],$value['ME'],$value['DG'],$value['Codigo'],$value['Cuenta'],$value['Presupuesto'],$value['Codigo_Ext']);
	        // $tablaHTML[$pos]['datos']=array('','','','','','','','');
	        $tablaHTML[$pos]['borde'] = $tablaHTML[0]['borde'];
	       $pos = $pos+1;
			
		 }
		

		// $this->pdf->cabecera_reporte_MC('PLAN DE CUENTAS',$tablaHTML,$contenido=false,$image=false,'','',9,true,25);
		$this->pdf->cabecera_reporte_MC($titulo=false,$tablaHTML,$contenido=false,$image=false,$Fechaini=false,$Fechafin=false,false,true,25,'P');

	}
}
?>