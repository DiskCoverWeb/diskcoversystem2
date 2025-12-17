<?php
include(dirname(__DIR__,2).'/modelo/contratos/reporte_GFNM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */

$controlador = new reporte_GFNC();
if(isset($_GET['ddl_indicador_gestion']))
{
	$q = "";
	if(isset($_GET['q'])){$q = $_GET['q'];}
	echo json_encode($controlador->ddl_indicador_gestion($q));
}
if(isset($_GET['cargar_lista']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_lista($parametros));
}

if(isset($_GET['imprimir_excel']))
{
	
    $parametros = $_GET;
	echo json_encode($controlador->imprimir_excel($parametros));
}
if(isset($_GET['imprimir_pdf']))
{
   	$parametros = $_GET;
	echo json_encode($controlador->imprimir_pdf($parametros));
}




class reporte_GFNC
{
	
	private $modelo;
	private $pdf;
	
	function __construct()
	{
	   $this->modelo = new  reporte_GFNM();	    
	   $this->pdf = new cabecera_pdf(); 
	}


	function ddl_indicador_gestion($query)
	{ 
		// print_r($query);die();
		$data = array();
		$datos = $this->modelo->ddl_indicador_gestion($query);
		foreach ($datos as $key => $value) {
			$data[] = array('id'=>$value['Codigo'],'text'=>$value['Descripcion'],'data'=>$value);
		}
		return $data;
			
	}

	function cargar_lista($parametros)
	{
		return $this->modelo->cargar_lista($parametros['grupo'],$parametros['indicador']);
	}

	function imprimir_excel($parametros)
	{
    	
    	// $resp =  $this->modelo->cargar_lista($parametros['indicador']);
		$datos =  $this->modelo->cargar_lista($parametros['indicador']);
    	$tablaHTML =array();
		   	 $tablaHTML[0]['medidas']=array(18,55,25,25,25,25,25,25,25,25,25,25,25,25,25);
	         $tablaHTML[0]['datos']=array('Codigo','Descripcion','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre','Total');
	         $tablaHTML[0]['tipo'] ='C';
	         $pos = 1;
			foreach ($datos as $key => $value) {
				// print_r($he);die();
				 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];				 
		         $tablaHTML[$pos]['datos']= array($value['Codigo'],$value['Descripcion'],$value['Enero'],$value['Febrero'],$value['Marzo'],$value['Abril'],$value['Mayo'],$value['Junio'],$value['Julio'],$value['Agosto'],$value['Septiembre'],$value['Octubre'],$value['Noviembre'],$value['Diciembre'],$value['Total_Meses']);
		         $tablaHTML[$pos]['tipo'] ='N';
		         $pos+=1;
			}

			excel_generico('Reporte indicador',$tablaHTML);
	}
	function imprimir_pdf($parametros)
	{
		  $datos =  $this->modelo->cargar_lista($parametros['indicador']);
		// print_r($datos);die();
	    $tablaHTML = array();
		$tablaHTML[0]['medidas']=array(18,55,15,15,15,15,15,15,15,15,18,15,15,18,15);
		$tablaHTML[0]['alineado']=array('L','L','R','R','R','R','R','R','R','R','R','R','R','R','R');
        $tablaHTML[0]['datos']=array('Codigo','Descripcion','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre','Total');
		$tablaHTML[0]['estilo']='B';
		$tablaHTML[0]['borde'] = '1';
		$pos=1;

		 foreach ($datos as $key => $value) {
			$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
	        $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
	        $tablaHTML[$pos]['datos']= array($value['Codigo'],$value['Descripcion'],$value['Enero'],$value['Febrero'],$value['Marzo'],$value['Abril'],$value['Mayo'],$value['Junio'],$value['Julio'],$value['Agosto'],$value['Septiembre'],$value['Octubre'],$value['Noviembre'],$value['Diciembre'],$value['Total_Meses']);
	        $tablaHTML[$pos]['borde'] = $tablaHTML[0]['borde'];
	       $pos = $pos+1;
			
		 }
		

		// $this->pdf->cabecera_reporte_MC('PLAN DE CUENTAS',$tablaHTML,$contenido=false,$image=false,'','',9,true,25);
		$this->pdf->cabecera_reporte_MC('REPORTE INDICADORES',$tablaHTML,$contenido=false,$image=false,$Fechaini=false,$Fechafin=false,false,true,25,'L');
	}


}
?>