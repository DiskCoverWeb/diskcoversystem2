<?php 
include 'pacienteC.php'; 
include (dirname(__DIR__,2).'/modelo/farmacia/devoluciones_insumosM.php');
include (dirname(__DIR__,2).'/modelo/farmacia/ingreso_descargosM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */
$controlador = new devoluciones_insumosC();
if(isset($_GET['cargar_pedidos']))
{
	$parametros = $_POST['parametros'];
	$paginacion = $_POST['paginacion'];
	echo json_encode($controlador->cargar_pedidos($parametros,$paginacion));
}
if(isset($_GET['tabla_detalles']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->tabla_detalles($parametros));
}
if(isset($_GET['imprimir_pdf']))
{
	$parametros= $_GET;
     $controlador->imprimir_pdf($parametros);
}

if(isset($_GET['formatoEgreso']))
{
	$parametros= $_GET;
     $controlador->imprimir_pdf($parametros);
}

if(isset($_GET['imprimir_excel']))
{   
	$parametros= $_GET;
	$controlador->imprimir_excel($parametros);	
}
if(isset($_GET['Ver_comprobante']))
{   
	$parametros= $_GET['comprobante'];
	$controlador->ver_comprobante($parametros);	
}

if(isset($_GET['datos_comprobante']))
{   
	$comprobante= $_POST['comprobante'];
	$query= $_POST['query'];
	echo json_encode($controlador->datos_comprobante($comprobante,$query));	
}
if(isset($_GET['costo']))
{   
	$parametros= $_POST['codigo'];
	echo json_encode($controlador->costo($parametros));	
}

if(isset($_GET['guardar_devolucion']))
{   
	$parametros= $_POST['parametros'];
	echo json_encode($controlador->guardar_devoluciones($parametros));	
}
if(isset($_GET['lista_devolucion']))
{   
	$comprobante= $_POST['comprobante'];
	echo json_encode($controlador->lista_devoluciones($comprobante));	
}
if(isset($_GET['lista_devolucion_dep']))
{   
	$comprobante= $_POST['comprobante'];
	echo json_encode($controlador->lista_devoluciones_x_departamento($comprobante));	
}
if(isset($_GET['eliminar_linea_dev']))
{   
	$parametros= $_POST['parametros'];
	echo json_encode($controlador->eliminar_linea_devo($parametros));	
}
if(isset($_GET['eliminar_linea_dev_dep']))
{   
	$parametros= $_POST['parametros'];
	echo json_encode($controlador->eliminar_linea_devo_dep($parametros));	
}

if(isset($_GET['guardar_devolucion_departamentos']))
{   
	$parametros= $_POST['parametros'];
	echo json_encode($controlador->guardar_devolucion_departamentos($parametros));	
}



class devoluciones_insumosC 
{
	private $modelo;
	private $paciente;
	private $pdf;
	private $descargos;
	function __construct()
	{
		$this->modelo = new devoluciones_insumosM();
		$this->paciente = new pacienteC();
		$this->pdf = new cabecera_pdf();
		$this->descargos = new ingreso_descargosM();
	}

	

	function cargar_pedidos($parametros,$paginacion)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->cargar_comprobantes($parametros['query'],$parametros['desde'],$parametros['hasta'],$parametros['busfe'],$paginacion);
		return $tabla = array('num_lin'=>0,'tabla'=>$datos);

	}

	

	function datos_comprobante($comprobante,$query1)
	{
		$datos = $this->modelo->cargar_comprobantes_datos($query=false,$desde='',$hasta='',$tipo='',$comprobante);
		$lineas = $this->modelo->lineas_trans_kardex($comprobante,$query1);
		$tr='';
		$tot=0;
		$num_lineas = count($lineas);
		foreach ($lineas as $key => $value) {
			$readonly = '';
			$key+=1;
			$registrado = $this->modelo->lista_devoluciones($comprobante);
			 $devo = $this->modelo->trans_kardex_linea_devolucion($value['Codigo_Inv'],$comprobante);
				 if(count($devo)>0)
				 {
				 	$ca= $value['Salida']-$devo[0]['Entrada'];
				 	if($ca>=0 )
				 	{
				 		$value['Salida']  = $ca;
				 	}
				 }

			foreach ($registrado['datos'] as $key2 => $value2) {				
				if($value['ID']==$value2['A_No'])
				{
					$readonly = 'readonly=""';
					break;
				}
			}
			$tr.='<tr>
			  			<td width="'.dimenciones_tabl(strlen('Codigo_Inv')).'" id="codigo_'.$key.'">'.$value['Codigo_Inv'].'</td>
			  			<td width="'.dimenciones_tabl(strlen($value['Producto'])).'" id="producto_'.$key.'">'.$value['Producto'].'</td>
			  			<td width="'.dimenciones_tabl(strlen('cant'.$value['Salida'])).'"><input class="form-control input-sm" value="'.$value['Salida'].'" readonly="" name="txt_salida_'.$key.'" id="txt_salida_'.$key.'"></td>
			  			<td width="'.dimenciones_tabl(strlen('Valor_Unitario')).'" class="text-right">'.number_format($value['Valor_Unitario'],2).'</td>
			  			<td width="'. dimenciones_tabl(strlen('Precio Total')).'" class="text-right">'.number_format($value['Valor_Total'],2).'</td>
        			<td  width="'.dimenciones_tabl(strlen('cant_dev')).'"><input class="form-control input-sm text-right" id="txt_cant_dev_'.$key.'"  value="0" onblur="calcular_dev(\''.$key.'\')" '.$readonly.'></td>
        			<td width="'.dimenciones_tabl(strlen('Valor utilidad')).'"><input class="form-control input-sm text-right" id="txt_valor_'.$key.'" value="0"   readonly></td>
        			<td width="'.dimenciones_tabl(strlen('total_dev')).'"><input class="form-control input-sm text-right" id="txt_gran_t_'.$key.'" value="0"  readonly=""></td>
        			<td><button onclick="calcular_dev(\''.$key.'\');guardar_devolucion(\''.$value['ID'].'\',\''.$key.'\')" id="btn_linea_'.$key.'" class="btn btn-primary"><i class="fa-icon fa fa-save"></i></button></td>
        		</tr>';
        	// $tot+=$gran;
		}
		$tr.='<tr><td colspan="6"></td><td class="text-right"><b>TOTAL</b></td><td class="text-right" id="txt_tt">'.$tot.'</td></tr>';

		return array('cliente'=>$datos,'tabla'=>$tr,'lineas'=>$num_lineas,'total'=>number_format($tot,2));

	}

	function guardar_utilidad($parametros)
	{
		$tabla = 'Trans_Kardex';
		$datos[0]['campo']='Utilidad';
		$datos[0]['dato']=$parametros['utilidad']/100;

		$campoWhere[0]['campo']='ID';
		$campoWhere[0]['valor']=$parametros['linea'];
		return update_generico($datos,$tabla,$campoWhere);

	}
	function costo($codigo)
	{
		$datos = $this->descargos->costo_producto($codigo);
		return $datos;
	}
    
    function guardar_devolucion_departamentos($parametro)
	{
		// print_r($parametro);die();
		if($parametro['cc']!='')
		{
		   	$cta = $parametro['cc'];
		   	 // $cc = explode('-',$parametro['cc']);
		}else
		{
			$cta = buscar_en_ctas_proceso('Cta_Devoluciones');
			if($cta==-1)
			{			 	
		   		SetAdoAddNew("Ctas_Proceso"); 	
		   	 	SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);
		   	 	SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		   	 	SetAdoFields('DC','D');
		   	 	SetAdoFields('Lst',0);
		   	 	SetAdoFields('Detalle','Cta_Devoluciones');
		   	 	SetAdoFields('Codigo','4.4.02.05.02');
		   	 	SetAdoUpdate();
		   	 	$cta = '4.4.02.05.02';
		   	 	// SetAdoFields('CONTRA_CTA','4.4.02.05.02');
		   	 }
	    }

		   $linea = $this->modelo->producto_all_detalle($parametro['codigo']);
		   // print_r($linea);die();
		   SetAdoAddNew("Asiento_K"); 	
		   
		   SetAdoFields('CODIGO_INV',$parametro['codigo']);
		   SetAdoFields('PRODUCTO',$parametro['producto']);
		   SetAdoFields('UNIDAD','');
		   SetAdoFields('CANT_ES',$parametro['cantidad']);
		   SetAdoFields('CTA_INVENTARIO',$linea[0]['Cta_Inventario']);
		   SetAdoFields('SUBCTA',$parametro['area']);		   //proveedor cod //area de donde biene
		   SetAdoFields('CONTRA_CTA',$cta);
		   SetAdoFields('CodigoU',$_SESSION['INGRESO']['Id']);   
		   SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		   SetAdoFields('A_No',$parametro['linea']+1);
		   SetAdoFields('Fecha_DUI',date('Y-m-d'));
		   SetAdoFields('TC','P');
		   SetAdoFields('VALOR_TOTAL',number_format($parametro['total'],2,'.',''));
		   SetAdoFields('CANTIDAD',$parametro['cantidad']);
		   SetAdoFields('VALOR_UNIT',number_format($parametro['precio'],$_SESSION['INGRESO']['Dec_PVP'],'.',''));
		   SetAdoFields('DH',1);		  
		   SetAdoFields('ORDEN',$parametro['comprobante']);
		   SetAdoFields('Codigo_B',$linea[0]['Codigo_P']);		   
		   SetAdoFields('Procedencia','Devolucion');
		   SetAdoFields('Codigo_Dr',$parametro['solicitante']);

		   // print_r($_SESSION['SetAdoAddNew']);die();
		   // print_r('dddd');die();

		  return SetAdoUpdate();
	
	    // print_r($resp);die();
	}


	function guardar_devoluciones($parametro)
	{
		// print_r($parametro);die();

		   $linea = $this->modelo->trans_kardex_linea_all($parametro['linea']);
		   // print_r($linea);die();

		   SetAdoAddNew("Asiento_K"); 
		   SetAdoFields('CODIGO_INV',$parametro['codigo']);
		   SetAdoFields('PRODUCTO',$parametro['producto']);
		   SetAdoFields('UNIDAD','');
		   SetAdoFields('CANT_ES',$parametro['cantidad']);
		   SetAdoFields('CTA_INVENTARIO',$linea[0]['Cta_Inv']);
		   SetAdoFields('SUBCTA',$linea[0]['CodigoL']);		   //proveedor cod
		   SetAdoFields('CodigoU',$_SESSION['INGRESO']['Id']);   
		   SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		   SetAdoFields('A_No',$parametro['linea']);
		   SetAdoFields('Fecha_DUI',date('Y-m-d'));
		   SetAdoFields('TC','P');
		   SetAdoFields('VALOR_TOTAL',number_format($parametro['total'],2,'.',''));
		   SetAdoFields('CANTIDAD',$parametro['cantidad']);
		   SetAdoFields('VALOR_UNIT',number_format($parametro['precio'],$_SESSION['INGRESO']['Dec_PVP'],'.',''));
		   SetAdoFields('DH',1);
		   SetAdoFields('CONTRA_CTA',$linea[0]['Contra_Cta']);
		   SetAdoFields('ORDEN',$parametro['comprobante']);

		   SetAdoFields('Codigo_B',$linea[0]['Codigo_P']);
		   SetAdoFields('Procedencia','Devolucion');

		 
// print_r($datos);die();
		   return SetAdoUpdate();
	}

	function lista_devoluciones($comprobante){
		$datos = $this->modelo->lista_devoluciones($comprobante);
		$li = count($datos['datos']);
		return array('tr'=>$datos['tabla'],'lineas'=>$li);
	}

	function lista_devoluciones_x_departamento($comprobante){
		$datos = $this->modelo->lista_devoluciones_x_departamento($comprobante);
		$li = count($datos['datos']);
		return array('tr'=>$datos['tabla'],'lineas'=>$li);
	}

	function eliminar_linea_devo($parametros)
	{
		return $this->modelo->eliminar_linea_dev($parametros['codigo'],$parametros['comprobante']);
	}
	function eliminar_linea_devo_dep($parametros)
	{
		return $this->modelo->eliminar_linea_dev_dep($parametros['codigo'],$parametros['comprobante'],$parametros['No']);
	}


}