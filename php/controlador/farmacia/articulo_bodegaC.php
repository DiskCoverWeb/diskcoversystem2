<?php 
require(dirname(__DIR__,2).'/modelo/farmacia/articulo_bodegaM.php');
/**
 * 
 */
$controlador = new articulo_bodegaC();
if(isset($_GET['provincias']))
{
	$respuesta = $controlador->provincias();
	echo json_encode($respuesta);
}
if(isset($_GET['pacientes']))
{
	$parametros = $_POST['parametros'];
	$respuesta = $controlador->cargar_paciente($parametros);
	echo json_encode($respuesta);
}
class articulo_bodegaC
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new articulo_bodegaM();
	}

	function cargar_paciente($parametros)
	{
		$datos = $this->modelo->cargar_paciente($parametros,$parametros['pag']);
		$paginacion = paginancion('Clientes',$parametros['fun'],$parametros['pag']);
		// print_r($paginacion);die();
		$tr = '';
		foreach ($datos as $key => $value) 
		{
			$d =  dimenciones_tabl(strlen($value['ID']));
			$d2 =  dimenciones_tabl(strlen($value['Codigo']));
			$d3 =  dimenciones_tabl(strlen($value['Cliente']));
			$d4 =  dimenciones_tabl(strlen($value['CI_RUC']));
			$d5 =  dimenciones_tabl(strlen($value['Telefono']));
			$tr.='<tr>
  					<td width="'.$d.'">'.$value['ID'].'</td>
  					<td width="'.$d2.'">'.$value['Matricula'].'</td>
  					<td width="'.$d3.'">'.$value['Cliente'].'</td>
  					<td width="'.$d4.'">'.$value['CI_RUC'].'</td>
  					<td width="'.$d5.'">'.$value['Telefono'].'</td>
  					<td width="90px">
  					    <a href="../vista/farmacia.php?mod='.$_SESSION['INGRESO']['modulo_'].'&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu&cod='.$value['Matricula'].'&ci='.$value['CI_RUC'].'#" class="btn btn-sm btn-default" title="Ver Historial"><span class="glyphicon glyphicon-th-large"></span></a>
  						<button class="btn btn-sm btn-primary" onclick="buscar_cod(\'E\',\''.$value['CI_RUC'].'\')" title="Editar paciente"><span class="glyphicon glyphicon-pencil"></span></button>  						
  						<button class="btn btn-sm btn-danger" title="Eliminar paciente"  onclick="eliminar(\''.$value['ID'].'\',\''.$value['CI_RUC'].'\')" ><span class="glyphicon glyphicon-trash"></span></button>
  					</td>
  				</tr>';
			
		}
		$tabla = array('tr'=>$tr,'pag'=>$paginacion);

		// print_r($tabla);die();
		return $tabla;
		
	}
}

?>