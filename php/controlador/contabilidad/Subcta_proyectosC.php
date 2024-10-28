<?php
include(dirname(__DIR__,2).'/modelo/contabilidad/Subcta_proyectosM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */
$controlador = new Subcta_proyectosC();

if(isset($_GET['DGCostos']))
{
	$parametros =$_POST['parametros'];
	echo json_encode($controlador->DGCostos($parametros));
}

if(isset($_GET['DCProyecto']))
{
	echo json_encode($controlador->DCProyecto());
}

if(isset($_GET['DCSubModulos']))
{
	echo json_encode($controlador->DCSubModulos());
}

if(isset($_GET['DCCtasProyecto']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCCtasProyecto($parametros));
}
if(isset($_GET['agregar']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->agregar($parametros));
}
if(isset($_GET['eliminar']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->eliminar($parametros['id']));
}
if(isset($_GET['imprimir_excel']))
{
	$parametros = $_GET;
	echo json_encode($controlador->imprimir_excel($parametros));
}


class Subcta_proyectosC
{
	
	private $modelo;
	private $pdf;
	
	function __construct()
	{
	   $this->modelo = new  Subcta_proyectosM();	   
	   $this->pdf = new cabecera_pdf();
	}


	function DGCostos($parametros)
	{
		// print_r($parametros);die();
		$resp =  $this->modelo->DGCostos($query=false,$parametros['TodasCtas'],$parametros['CodSubCta'],$parametros['SubCta']);
		return $resp['tbl'];
		// print_r($resp);die();
	}

	function DCProyecto()
	{
		$resp =  $this->modelo->DCProyecto();
		$datos[0]=array('codigo'=>'','nombre'=>'Seleccione un proyecto');
		foreach ($resp as $key => $value) {
			$datos[] = array('codigo'=>$value['Codigo'],'nombre'=>$value['Cuenta']);
		}
		// print_r($resp);die();
		return $datos;
	}

	function DCSubModulos()
    {
    	$resp =  $this->modelo->DCSubModulos();
    	$datos[0]=array('codigo'=>'','nombre'=>'Seleccione una subcuenta');
    	foreach ($resp as $key => $value) {
    		$datos[] = array('codigo'=>$value['Codigo'],'nombre'=>$value['Detalle']);
    	}
    	// print_r($resp);die();
    	return $datos;
    }

    function DCCtasProyecto($parametros)
    {
    	$resp =  $this->modelo->DCCtasProyecto($parametros['codigo']);
    	$datos[0]=array('codigo'=>'','nombre'=>'Seleccione una cuenta');
    	foreach ($resp as $key => $value) {
    		$datos[] = array('codigo'=>$value['Codigo'],'nombre'=>$value['Cuenta']);
    	}
    	// print_r($resp);die();
    	return $datos;
    }

    function agregar($parametros)
    {
    	// print_r($parametros);die();


    	SetAdoAddNew("Trans_Presupuestos");
    	SetAdoFields('Cta',$parametros['cta']);
    	SetAdoFields('Codigo',$parametros['codigo']);
    	SetAdoFields('Item',$_SESSION['INGRESO']['item']);
    	SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);
    	$e = $this->modelo->existe($parametros['cta'],$parametros['codigo']);
    	if($e==false)
    	{
			return SetAdoUpdate();
    	}else
    	{
    		return 2;
    	}
    	// $datos[0]['campo'] ='';
    	// $datos[0]['dato'] = 
    }

    function eliminar($id)
    {
    	return $this->modelo->eliminar($id);
    }

    function imprimir_excel($parametros)
    {

    	// print_r($parametros);die();
    	$parametros['TodasCtas'] = 'false';
    	if($parametros['ddl_cuenta_pro']=='')
    	{
    		$parametros['TodasCtas'] ='true';
    	}
    	$resp =  $this->modelo->DGCostos($query=false,$parametros['TodasCtas'],$parametros['ddl_proyecto'],$parametros['ddl_cuenta_pro']);
		$datos =  $resp['datos'];
    	$tablaHTML =array();
		   	 $tablaHTML[0]['medidas']=array(18,25,55,25,25);
	         $tablaHTML[0]['datos']=array('Cta','Cuenta','Detalle','Codigo','ID');
	         $tablaHTML[0]['tipo'] ='C';
	         $pos = 1;
			foreach ($datos as $key => $value) {
				// print_r($he);die();
				 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];				 
		         $tablaHTML[$pos]['datos']= array($value['Cta'],$value['Cuenta'],$value['Detalle'],$value['Codigo'],$value['ID']);
		         $tablaHTML[$pos]['tipo'] ='N';
		         $pos+=1;
			}

			excel_generico('SubCuentas De Proyectos',$tablaHTML);
    }
 



	
}
?>