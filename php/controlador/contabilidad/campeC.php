<?php
include(dirname(__DIR__,2).'/modelo/contabilidad/campeM.php');
/**
 * 
 */

$controlador = new campeC();
if(isset($_GET['lista']))
{
	$parametros = $_POST['modulo'];
	//print_r( $parametros);
	echo json_encode($controlador->cargar_datos($parametros));
}
if(isset($_GET['cambiarPeriodo']))
{
	$periodo = $_POST['periodo'];
	echo json_encode($controlador->cambiarPeriodo($periodo));
}

class campeC
{
	
	private $modelo;
	private $pdf;
	
	function __construct()
	{
	   $this->modelo = new  campeM();	   
	}


	function cargar_datos($modulo)
	{ 
		$datos = array();
		switch ($modulo) {
			case '11':
			$datos = $this->modelo->peridos();
				break;
			case '02':
			$datos = $this->modelo->facturas_periodo();
				break;			
			default:
			$datos = $this->modelo->catalogoCta_periodo();
				break;
		}
	$tr = '';
	foreach ($datos as $key => $value) {
		// print_r($value);die();
		$id = str_replace('/','', $value['Periodo']);
		$tr.='<div class="periodo alert alert-default alert-dismissible" style="border:1px solid; margin-bottom:4px" onclick="seleccionar_periodo(\''.$id.'\')" id="item_'.$id.'">
                <h4><i class="icon fa fa-calendar"></i>'.$value['Periodo'].'</h4>
                <!-- Info alert preview. This alert is dismissable. -->
              </div>	';
	}
	$tr.='<div class="periodo alert alert-default alert-dismissible" style="border:1px solid; margin-bottom:4px" onclick="seleccionar_periodo()" id="item_actual">
                <h4><i class="icon fa fa-calendar"></i>Periodo actual</h4>
                <!-- Info alert preview. This alert is dismissable. -->
              </div>	';


	return $tr;		
	}

	function cambiarPeriodo($periodo)
	{
		// print_r($periodo);die();
		switch ($periodo) {
			case 'actual':
				$_SESSION['INGRESO']['periodo'] = '.';
				break;			
			default:
			 $dateNew = DateTime::createFromFormat('dmY', $periodo)->format('d/m/Y');
			 $_SESSION['INGRESO']['periodo'] = $dateNew;
			  // $newDate = date('Y-m-d',$periodo);
			  // print_r($dateNew);die();
				break;
		}
		return 1;

	}
}
?>