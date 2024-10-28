<?php
//Llamada al modelo
error_reporting(E_ALL);
ini_set('display_errors', '1');
include("../../modelo/inventario/egreso_alimentosM.php");
include(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');

$controlador = new egreso_alimentosC();
 
if(isset($_GET['areas']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->ddl_areas($query));
} 
if(isset($_GET['areas_checking']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->areas_checking($query));
} 
if(isset($_GET['motivos']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->ddl_motivo($query));
}
if(isset($_GET['buscar_producto']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->buscar_producto($parametros));
}
if(isset($_GET['add_egresos']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->add_egresos($parametros));
}
if(isset($_GET['listar_egresos']))
{
	echo json_encode($controlador->listar_egresos());
}
if(isset($_GET['lista_egreso_checking']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_egreso_checking($parametros));
}
if(isset($_GET['lista_egreso_checking_reportados']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_egreso_checking_reportados($parametros));
}
if(isset($_GET['eliminar_egreso']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->eliminar_egreso($id));
}
if(isset($_GET['eliminar_egreso_all']))
{
	echo json_encode($controlador->eliminar_egreso_all());
}
if(isset($_GET['guardar_egreso']))
{
	echo json_encode($controlador->guardar_egreso($_FILES,$_POST));
}
if(isset($_GET['cargar_motivo_lista']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_motivo_lista($parametros));
}
if(isset($_GET['catalog_cuentas']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->catalog_cuentas($parametros));
}
if(isset($_GET['cambiar_a_reportado']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cambiar_a_reportado($parametros));
}

/**
 * 
 */
class egreso_alimentosC
{
	private $modelo;
	private $pdf;

	function __construct()
	{
		$this->modelo = new egreso_alimentosM();
		$this->pdf = new cabecera_pdf();

	}

	function ddl_areas($query)
	{
		$datos = $this->modelo->areas($query);
		$op=array();
		foreach ($datos as $key => $value) {
			$op[] = array('id'=>$value['Cmds'],'text'=>trim($value['Proceso']),'data'=>$value);			
		}

		return $op;

	}

	function areas_checking($query)
	{
		$datos = $this->modelo->areas_checking($query);
		$op=array();
		foreach ($datos as $key => $value) {
			$op[] = array('id'=>$value['Cmds'],'text'=>trim($value['Proceso']),'data'=>$value);			
		}

		return $op;

	}

	function ddl_motivo($query)
	{
		$datos = $this->modelo->motivo_egreso($query);
		$op=array();
		foreach ($datos as $key => $value) {
			$op[] = array('id'=>$value['Cmds'],'text'=>$value['Proceso'],'data'=>$value);			
		}
		return $op;
	}

	function buscar_producto($parametros)
	{
		$datos = $this->modelo->buscar_producto($parametros['codigo']);
		return $datos;
	}

	function add_egresos($parametros)
	{
		$producto = $this->modelo->buscar_producto(false,$parametros['id']);
		$data = $producto[0];

		SetAdoAddNew('Trans_Kardex');
	    SetAdoFields('T','S');
	    SetAdoFields('Salida',$parametros['cantidad']);
	    SetAdoFields('CodBodega',$data['CodBodega']);
	    SetAdoFields('Codigo_Barra',$data['Codigo_Barra']);
	    SetAdoFields('Codigo_Inv',$data['Codigo_Inv']);	
	    SetAdoFields('Fecha',$parametros['fecha']);	
	    SetAdoFields('Codigo_P',$data['Codigo_P']);	
	    SetAdoFields('CodigoU',$data['CodigoU']);	
	    SetAdoFields('Valor_Unitario',$data['Valor_Unitario']);	

	    SetAdoFields('Codigo_Tra',$parametros['area']);	
	    SetAdoFields('Modelo',$parametros['motivo']);	
	    SetAdoFields('Detalle',$parametros['detalle']);	

	    SetAdoFields('Valor_Total',number_format(floatval($data['Valor_Unitario'])*floatval($parametros['cantidad']),2,'.',''));	
	     SetAdoFields('Total',number_format(floatval($data['Valor_Unitario'])*floatval($parametros['cantidad']),2,'.',''));	
	    SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);	
	    SetAdoFields('Item',$_SESSION['INGRESO']['item']);			
	    SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU']);		
	   return  SetAdoUpdate();		

	}	

	function listar_egresos()
	{
		$tr = '';
		$datos = $this->modelo->buscar_producto_egreso();
		foreach ($datos as $key => $value) {
			$tr.='<tr>			
			<td>'.($key+1).'</td>
			<td>'.$value['Fecha']->format('Y-m-d').'</td>
			<td>'.$value['Producto'].'</td>
			<td>'.$value['Salida'].' '.$value['Unidad'].'</td>
			<td><button type="button" class="btn btn-danger btn-sm" onclick="eliminar_egreso('.$value['ID'].')"><i class="fa fa-trash"></i></button></td>
			</tr>';
		}
		return $tr;
	}

	function eliminar_egreso($id)
	{
		return $this->modelo->eliminar($id);
		// print_r($id);die();
	}
	function eliminar_egreso_all()
	{
		return $this->modelo->eliminar_all();
		// print_r($id);die();
	}

	function guardar_egreso($file,$post)
	{
		if($this->validar_formato_img($file)!=1)
	    {
	    	return -2;
	    }
		// para el cheing de egreso se colocara la G
		$num = $this->modelo->numero_Registro(date('Y-m-d'));

		// print_r($num);die();
		$registro = '001';
		if(isset($num[0]['num']) && $num[0]['num']!=''){$registro = '00'.($num[0]['num']+1);}
		$orden = str_replace('-','', date('Y-m-d')).'-'.$registro;

		$ruta = dirname(__DIR__,2).'/comprobantes/sustentos/empresa_'.$_SESSION['INGRESO']['item'].'/';
		if(!file_exists($ruta))
		{
			$ruta1 = dirname(__DIR__,2).'/comprobantes/sustentos';
			mkdir($ruta1,0777);
			mkdir($ruta,0777);
		}
		 $uploadfile_temporal=$file['archivo']['tmp_name'];
   	     $tipo = explode('/', $file['archivo']['type']);
         $nombre = $orden.'.'.$tipo[1];
        
   	     $nuevo_nom=$ruta.$nombre;
   	     if (is_uploaded_file($uploadfile_temporal))
   	     {
   		     move_uploaded_file($uploadfile_temporal,$nuevo_nom);
   	     }

		SetAdoAddNew('Trans_Kardex');
	    SetAdoFields('T','G');	    	    	    
	    SetAdoFields('Orden_No',$orden);	 	    
	    SetAdoFields('Procedencia',$nombre);	

	    SetAdoFieldsWhere('Periodo',$_SESSION['INGRESO']['periodo']);	
	    SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);	
	    SetAdoFieldsWhere('CodigoU',$_SESSION['INGRESO']['CodigoU']);	
	    SetAdoFieldsWhere('T','S');				
	    SetAdoFieldsWhere('T','S');			

	   return  SetAdoUpdateGeneric();
	}

	 function validar_formato_img($file)
	  {

	  	// print_r($file);die();
	    switch ($file['archivo']['type']) {
	      case 'image/jpeg':
	      case 'image/pjpeg':
	      case 'image/gif':
	      case 'image/png':
	         return 1;
	        break;      
	      default:
	        return -1;
	        break;
	    }

	  }


	function lista_egreso_checking($parametros)
	{
		$area = false;
		if(isset($parametros['areas']))
		{
			$area = $parametros['areas'];
		}
		// print_r($parametros);die();
		$tr = '';
		$datos = $this->modelo->lista_egreso_checking(false,false,$area);
		foreach ($datos as $key => $value) {
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td>'.$value['Fecha']->format('Y-m-d').'</td>
					<td>
						<div class="input-group input-group-sm">
							'.$value['usuario'].'
							<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-sm" onclick="modal_mensaje(\''.$value['Orden_No'].'\')">
								<img src="../../img/png/user.png" style="width:20px">
							</button>
							</span>
						</div>
					</td>
					<td>
						<div class="input-group input-group-sm">
							'.$value['Motivo'].'
							<span class="input-group-btn">
							<button type="button" class="btn btn-default btn-sm" onclick="modal_motivo(\''.$value['Orden_No'].'\')">
								<img src="../../img/png/transporte_caja.png" style="width:20px">
							</button>
							</span>
						</div>
					</td>
					<td>'.$value['Detalle'].'</td>
					<td>
						<button type="button" class="btn btn-default btn-sm" onclick="mostra_doc(\''.$value['procedencia'].'\')">
							<img src="../../img/png/clip.png" style="width:20px">
						</button>
						<input type="file" id="file_doc" name="" style="display: none;">
					</td>
					<td>
						<select class="form-control input-sm" id="ddl_subcta_'.$value['Orden_No'].'" name="ddl_subcta_'.$value['Orden_No'].'">
							<option value="">Seleccione modulo</option>
							'.$this->catalog_cuentas().'
						</select>
					</td>
					<td>
						<input type="checkbox" name="">
					</td>
				</tr>';
			// $tr.='<tr>			
			
			// <td>'.$value['Producto'].'</td>
			// <td>'.$value['Salida'].'</td>
			// <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminar_egreso('.$value['ID'].')"><i class="fa fa-trash"></i></button></td>
			// </tr>';
		}
		return $tr;
	}

	function lista_egreso_checking_reportados($parametros)
	{
		$area = false;
		if(isset($parametros['areas']))
		{
			$area = $parametros['areas'];
		}
		// print_r($parametros);die();
		$tr = '';
		$datos = $this->modelo->lista_egreso_checking_devuelto(false,false,$area);
		foreach ($datos as $key => $value) {
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td>'.$value['Fecha']->format('Y-m-d').'</td>
					<td>
						<div class="input-group input-group-sm">
							'.$value['usuario'].'							
						</div>
					</td>
					<td>
						<div class="input-group input-group-sm">
							'.$value['area'].'							
						</div>
					</td>					
					<td>'.$value['Detalle'].'</td>
					<td>
						<div class="input-group input-group-sm">
							'.$value['Motivo'].'							
						</div>
					</td>
					
					<td>
						
					</td>
					<td>
					<button class="btn btn-primary"><i class="fa fa-save"></i></button>
					<button class="btn btn-primary"><i class="fa fa-trash"></i></button>
					<button class="btn btn-primary"><i class="fa fa-pen"></i></button>
					</td>
				</tr>';
			// $tr.='<tr>			
			
			// <td>'.$value['Producto'].'</td>
			// <td>'.$value['Salida'].'</td>
			// <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminar_egreso('.$value['ID'].')"><i class="fa fa-trash"></i></button></td>
			// </tr>';
		}
		return $tr;
	}


	function cargar_motivo_lista($parametros)
	{
		$tr='';
		$datos = $this->modelo->cargar_motivo_lista(false,false,$parametros['orden']);
		foreach ($datos as $key => $value) {
			// print_r($value);die();
			$stock = 0;
			$datos_stock = Leer_Codigo_Inv($value['Codigo_Inv'],date('Y-m-d'));
			if($datos_stock['respueta']==1)
			{
				$stock = $datos_stock['datos']['Stock'];
			}
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td>'.$value['Cliente'].'</td>
					<td>'.$value['Producto'].'</td>
					<td>'.$stock.'</td>
					<td>'.$value['Salida'].' '.$value['Unidad'].'</td>
					<td>'.$value['Valor_Unitario'].'</td>
					<td>'.($value['Valor_Unitario']*$value['Salida']).'</td>
					<td>
						<input type="radio" name="">
					</td>
				</tr>';
		}

		return $tr;
		// print_r($parametros);
	}

	function catalog_cuentas()
	{
		$datos = $this->modelo->catalog_cuentas();
		$tr='';
		foreach ($datos as $key => $value) {
			$tr.='<option value="'.$value['Codigo'].'">'.$value['Cuenta'].'</option>';
		}

		return $tr;
	}

	function cambiar_a_reportado($parametros)
	{
	   SetAdoAddNew("Trans_Kardex"); 		
	   SetAdoFields('T','R');
	   SetAdoFieldsWhere('Orden_No',$parametros['orden']);
	  return  SetAdoUpdateGeneric();

	}

}


?>
