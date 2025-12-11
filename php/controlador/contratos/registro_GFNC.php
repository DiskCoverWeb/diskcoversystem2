<?php
include(dirname(__DIR__,2).'/modelo/contratos/registro_GFNM.php');
/**
 * 
 */

$controlador = new registro_GFNC();
if(isset($_GET['ddl_indicador_gestion']))
{
	$q = "";
	if(isset($_GET['q'])){$q = $_GET['q'];}
	echo json_encode($controlador->ddl_indicador_gestion($q));
}
if(isset($_GET['guardar_valor']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_valor($parametros));
}
if(isset($_GET['guardar_indicador']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_indicador($parametros));
}

class registro_GFNC
{
	
	private $modelo;
	private $pdf;
	
	function __construct()
	{
	   $this->modelo = new  registro_GFNM();	   
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

	function guardar_valor($parametros)
	{
		SetAdoAddNew("Catalogo_APIs"); 	
		
		switch($parametros['mes'])
        {
            case 1:            
			SetAdoFields('Enero',$parametros['valor']);
                break;
            case 2:            
			SetAdoFields('Febrero',$parametros['valor']);
                break;
            case 3:            
			SetAdoFields('Marzo',$parametros['valor']);
                break;
            case 4:            
			SetAdoFields('Abril',$parametros['valor']);
                break;
            case 5:            
			SetAdoFields('Mayo',$parametros['valor']);
                break;
            case 6:            
			SetAdoFields('Junio',$parametros['valor']);
                break;
            case 7:            
			SetAdoFields('Julio',$parametros['valor']);
                break;
            case 8:            
			SetAdoFields('Agosto',$parametros['valor']);
                break;
            case 9:            
			SetAdoFields('Septiembre',$parametros['valor']);
                break;
            case 10:            
			SetAdoFields('Octubre',$parametros['valor']);
                break;
            case 11:            
			SetAdoFields('Noviembre',$parametros['valor']);
                break;
            case 12:            
			SetAdoFields('Diciembre',$parametros['valor']);
                break;
            default:
                break;
        }

			SetAdoFieldsWhere('Codigo',$parametros['indicador']);
			SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);
			SetAdoFieldsWhere('Periodo',$_SESSION['INGRESO']['periodo']);
			return SetAdoUpdateGeneric();
	}

	function guardar_indicador($parametros)
	{		
		SetAdoAddNew("Catalogo_APIs"); 	
		SetAdoFields('Codigo',$parametros['codigo']);
		SetAdoFields('Descripcion',$parametros['indicador']);
		SetAdoFields('ID',"1");
		SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);
		SetAdoUpdate();

		print_r($parametros);die();

	}


}
?>