<?php
//Llamada al modelo
error_reporting(E_ALL);
ini_set('display_errors', '1');
include("../../modelo/inventario/ingreso_presupuestoM.php");
include(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');

$controlador = new ingreso_presupuestoC();
 
if(isset($_GET['centro_costo']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->centro_costos($_GET['proyecto'],$query));
} 
if(isset($_GET['producto']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->catalogo_productos($query));
} 
if(isset($_GET['listado']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_pedidos($parametros));
} 
if(isset($_GET['guardar']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->agregar_pedidos($parametros));
} 

if(isset($_GET['update']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->actualizar_pedidos($parametros));
} 

if(isset($_GET['eliminar']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->eliminar($parametros));
} 
if(isset($_GET['reporte_excel']))
{
	$parametros = $_GET;
	echo json_encode($controlador->reporte_excel($parametros));
} 
if(isset($_GET['reporte_pdf']))
{
	$parametros = $_GET;
	echo json_encode($controlador->reporte_pdf($parametros));
} 

/**
 * 
 */
class ingreso_presupuestoC
{
	private $modelo;
	private $pdf;

	function __construct()
	{
		$this->modelo = new ingreso_presupuestoM();
		$this->pdf = new cabecera_pdf();

	}

	function centro_costos($proyecto,$query)
	{
		$datos = $this->modelo->centro_costos($proyecto,$query);
		$op=array();
		foreach ($datos as $key => $value) {
			$op[] = array('id'=>$value['Codigo'],'text'=>$value['Cuenta']);			
		}

		return $op;

	}

	function catalogo_productos($query)
	{
		$datos = $this->modelo->catalogo_productos($query);
		$op=array();
		foreach ($datos as $key => $value) {
			$op[] = array('id'=>$value['Codigo_Inv'],'text'=>$value['Producto']);			
		}

		return $op;

	}

	function lista_pedidos($parametros)
	{
		// print_r($parametros);die();
		$centro ='';
		foreach ($parametros['centro'] as $key => $value) {
		    $centro.="'".$value."',";
		}
		$centro = substr($centro,0,-1);
		$datos = $this->modelo->lista_pedidos($centro);
		$op='';
		foreach ($datos as $key => $value) {
			$op.='<tr>
					<td>'.$value['Detalle'].'</td>
					<td>'.$value['Producto'].'</td>
					<td style="width: 10%;"> <input  id="txt_cant'.$value['ID'].'" name="txt_cant'.$value['ID'].'"value="'.$value['Cantidad'].'"  class ="form-control input-sm"/></td>
					<td><button class="btn btn-danger btn-sm" onclick="eliminar(\''.$value['ID'].'\')"><i class="fa fa-trash"></i></button>
					<button class="btn btn-primary btn-sm" onclick="update(\''.$value['ID'].'\')"><i class="fa fa-save"></i></button>
					</td>
				</tr>';
			
		}
		return $op;

	}

	function agregar_pedidos($parametros)
	{
		// print_r($parametros);die();
		$resp = 1;
		foreach ($parametros['centro'] as $key => $value) {

			SetAdoAddNew("Trans_Presupuestos");
			SetAdoFields('Codigo_Inv',$parametros['producto']);
			SetAdoFields('Cta',$value);
			SetAdoFields('Presupuesto',$parametros['cantidad']);
			SetAdoFields('Item',$_SESSION['INGRESO']['item']);
			SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);	
			$res = SetAdoUpdate();
			if($res==-1){$resp=-1;}		
		}
		return $resp;
		
	}

	function actualizar_pedidos($parametros)
	{
		SetAdoAddNew("Trans_Presupuestos");
		SetAdoFields("Presupuesto", $parametros['cantidad']);
		SetAdoFieldsWhere("ID", $parametros['id']);
		return SetAdoUpdateGeneric();
	}

	function eliminar($parametros)
	{
		// print_r($parametros);die();
		return $this->modelo->delete($parametros['id']);
	}
	function reporte_excel($parametros)
	{
		$centro_GET = explode(',',$parametros['centro']);
		$centro ='';
		foreach ($centro_GET as $key => $value) {
		    $centro.="'".$value."',";
		}
		$centro = substr($centro,0,-1);
		$result = $this->modelo->lista_pedidos($centro);
		$b = 1;
	     $titulo='I N G R E S O   D E   P R E S U P U E S T O S';
	     $tablaHTML =array();
	     $tablaHTML[0]['medidas']=array(30,30,18);
	     $tablaHTML[0]['datos']=array($parametros['proye'],'','');
	     $tablaHTML[0]['tipo'] ='C';
	     $tablaHTML[0]['unir'] =array('AC');

	     $tablaHTML[1]['medidas']=array(30,30,18);
	     $tablaHTML[1]['datos']=array('CENTRO DE COSTOS','PRODUCTOS','CANTIDAD');
	     $tablaHTML[1]['tipo'] ='C';
	     $pos = 2;
	     $compro1='';
	    foreach ($result as $key => $value) {
	          $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
	          $tablaHTML[$pos]['datos']=array($value['Detalle'],$value['Producto'],$value['Cantidad']);
	          $tablaHTML[$pos]['tipo'] ='N';          
	          $pos+=1;
	    }
	      excel_generico($titulo,$tablaHTML);  


	}
	function reporte_pdf($parametros)
	{
		$centro_GET = explode(',',$parametros['centro']);
		$centro ='';
		foreach ($centro_GET as $key => $value) {
		    $centro.="'".$value."',";
		}
		$centro = substr($centro,0,-1);
		$result = $this->modelo->lista_pedidos($centro);


		$titulo='I N G R E S O   D E   P R E S U P U E S T O S';
		$sizetable =8;
		$mostrar = TRUE;
		$tablaHTML= array();

		$contenido[0] = array('tipo'=>'titulo','posicion'=>'top-tabla','valor'=>$parametros['proye'],'tamaño'=>12);
		
		$tablaHTML[0]['medidas']=array(80,80,28);
		$tablaHTML[0]['alineado']=array('L','L','D');
		$tablaHTML[0]['datos']=array('CENTRO DE COSTOS','PRODUCTO','CANTIDAD');
		$tablaHTML[0]['estilo']='B';
		$tablaHTML[0]['borde'] =1;
		$pos=1;
		 foreach ($result as $key => $value) {
	          $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
			  $tablaHTML[$pos]['alineado']= $tablaHTML[0]['alineado'];
	          $tablaHTML[$pos]['datos']=array($value['Detalle'],$value['Producto'],$value['Cantidad']);
	          // $tablaHTML[$pos]['estilo']='B';
			  $tablaHTML[$pos]['borde'] =1;
	          $pos+=1;
	    }	

		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido,$image=false,$desde=false,$hasta=false,$sizetable,$mostrar,25,'P');


		print_r($datos);die();

	}

}


?>
