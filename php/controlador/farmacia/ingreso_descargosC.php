<?php 
date_default_timezone_set('America/Guayaquil');
include (dirname(__DIR__,2).'/modelo/farmacia/pacienteM.php');
include (dirname(__DIR__,2).'/modelo/farmacia/descargosM.php');
include (dirname(__DIR__,2).'/modelo/farmacia/ingreso_descargosM.php');
$_SESSION['INGRESO']['modulo_']='99';

/**
 * 
 */
$controlador = new ingreso_descargosC();
// sp_Reindexar_Periodo();
if(isset($_GET['paciente']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar_paciente($query));
}

if(isset($_GET['solicitante']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar_solicitante($query));
}

if(isset($_GET['producto']))
{
	$tipo = $_GET['tipo'];
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar_producto($query,$tipo));
}

if(isset($_GET['cc']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar_cc($query));
}

if(isset($_GET['subcuenta']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar_subcuenta($query));
}

if(isset($_GET['guardar']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_entrega($parametros));
}
if(isset($_GET['guardar_bod']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_entrega_bod($parametros));
}

if(isset($_GET['pedido']))
{
	$parametros = $_POST['parametros'];
		// print_r('ddd');die();
	echo json_encode($controlador->cargar_pedidos($parametros));
}

if(isset($_GET['pedido_bod']))
{
	$parametros = $_POST['parametros'];
		// print_r('ddd');die();
	echo json_encode($controlador->cargar_pedidos_bod($parametros));
}

if(isset($_GET['lin_edi']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lineas_edi($parametros));
}
if(isset($_GET['lin_eli']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lineas_eli($parametros));
}
if(isset($_GET['facturar']))
{
	$orden = $_POST['orden'];
    $ruc = $_POST['ruc'];
    $area = $_POST['area'];
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];

	echo json_encode($controlador->generar_factura($orden,$ruc,$area,$nombre,$fecha));
}

if(isset($_GET['facturar_bodega']))
{
		$orden = $_POST['orden'];
    $ruc = $_POST['ruc'];
    $area = $_POST['area'];
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];

    // print_r($orden);print_r($ruc);print_r($area);print_r($nombre);print_r($fecha);die();

	echo json_encode($controlador->generar_factura_bodega($orden,$ruc,$area,$nombre,$fecha));
}

if(isset($_GET['areas']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->areas($parametros['cod']));
}
if(isset($_GET['edi_proce']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->editar_procedimiento($parametros));
}


if(isset($_GET['num_com']))
{
	$fecha = $_POST['fecha'];
	echo json_encode(numero_comprobante1('Diario',true,false,$fecha));
}

if (isset($_GET['mayorizar'])) {
	echo json_encode($controlador->mayorizar());
}


class ingreso_descargosC 
{
	private $modelo;
	private $paciente;
	private $descargos;
	public $cods_negativos = '';
	function __construct()
	{
		$this->modelo = new ingreso_descargosM();
		$this->paciente = new pacienteM();
		$this->descargos = new descargosM();
		// mayorizar_inventario_sp();
		// mayorizar_inventario_sp();
	}
	function buscar_paciente($query)
	{
		// print_r($query);die();
		$parametros = array('tipo'=>'N','query'=>$query,'codigo'=>'');
		$datos = $this->paciente->cargar_paciente($parametros);
		$paciente = array();
		foreach ($datos as $key => $value) {
			// $paciente[]= array('id'=>$value['CI_RUC'],'text'=>$value['Cliente']);
			$paciente[]= array('id'=>$value['CI_RUC'],'text'=>$value['Cliente']);
		}
		// print_r($paciente);die();
		return $paciente;
	}

	function buscar_solicitante($query)
	{
		// print_r($query);die();
		$datos = $this->modelo->buscar_solicitante($query);
		$soli = array();
		foreach ($datos as $key => $value) {
			// $paciente[]= array('id'=>$value['CI_RUC'],'text'=>$value['Cliente']);
			$soli[]= array('id'=>$value['Codigo'],'text'=>$value['Ejecutivo']);
		}
		// print_r($paciente);die();
		return $soli;
	}

	function buscar_producto($query,$tipo)
	{
		// print_r($tipo);die();

		$producto = array();
		$exist = 0;
		$costo = 0;
		// if($query!='')
		// {
			$datos = $this->modelo->buscar_producto($query,$tipo);
		
		foreach ($datos as $key => $value) {
			$FechaInventario = date('Y-m-d');
 	 		$CodBodega = '01';
			$costo_existencias =  Leer_Codigo_Inv($value['Codigo_Inv'],$FechaInventario,$CodBodega,$CodMarca='');

			// $costo_venta = $this->modelo->costo_venta();
			// $costotra = $this->modelo->costo_producto($value['Codigo_Inv']);
			// print_r($costo_existencias);die();
			if($costo_existencias['respueta']==1)
			{
				$exist = $costo_existencias['datos']['Stock'];				
				$costo = $costo_existencias['datos']['Costo'];				
			}
		

			if($tipo=='ref')
			{
				$producto[] = array('id'=>$value['Codigo_Inv'].'_'.$costo.'_'.$value['Producto'].'_'.$value['Cta_Inventario'].'_'.$value['TC'].'_'.$value['Cta_Costo_Venta'].'_'.$value['IVA'].'_'.$value['Unidad'].'_'.$exist.'_'.$value['Maximo'].'_'.$value['Minimo'],'text'=>$value['Codigo_Inv']);
			}else
			{
				$producto[] = array('id'=>$value['Codigo_Inv'].'_'.$costo.'_'.$value['Producto'].'_'.$value['Cta_Inventario'].'_'.$value['TC'].'_'.$value['Cta_Costo_Venta'].'_'.$value['IVA'].'_'.$value['Unidad'].'_'.$exist.'_'.$value['Maximo'].'_'.$value['Minimo'],'text'=>$value['Producto']);
			}
		// }
	  }
	  // print_r($producto);die();
		return $producto;
	}

	function buscar_cc($query)
	{
		$cc = $this->modelo->buscar_cc($query);
		$datos =  array();
		foreach ($cc as $key => $value) {
			$datos[] = array('id'=>$value['Codigo'].'-'.$value['TC'],'text'=>$value['Cuenta']);
		}
		// print_r($datos);die();
		return $datos;
	}

    function cargar_pedidos($parametros)
    {
    	// print_r($parametros);die();
    	$ordenes = $this->modelo->cargar_pedidos_fecha_trans($parametros['num_ped'],$parametros['area']);
    	// print_r($ordenes);die();
    	$datos1 = $this->modelo->cargar_pedidos_trans($parametros['num_ped'],$parametros['area'],false,false,$parametros['paciente']);
    	 $neg = false;
    	 $num = 0;
    	 $procedimiento = '';
         $tab='<ul class="nav nav-tabs">';
         $content = '<div class="tab-content">';
    	foreach ($ordenes as $key => $value) {
    		if($value['SUBCTA']!='.')
    		{

    		if($key==0)
    		{

    		    $content.='<input type="hidden" id="txt_f"  value="'.$value['Fecha']->format('Y-m-d').'"><div id="'.$value['SUBCTA'].'-'.$value['Fecha']->format('Y-m-d').'" class="tab-pane fade in active">';
    			$tab.='<li class="active"><a data-toggle="tab" href="#'.$value['SUBCTA'].'-'.$value['Fecha']->format('Y-m-d').'">'.'Fecha: '.$value['Fecha']->format('Y-m-d').'</a></li>';
    		}else
    		{

    		    $content.='<div id="'.$value['SUBCTA'].'-'.$value['Fecha']->format('Y-m-d').'" class="tab-pane fade">';
    			$tab.='<li><a data-toggle="tab" href="#'.$value['SUBCTA'].'-'.$value['Fecha']->format('Y-m-d').'">'.'Fecha: '.$value['Fecha']->format('Y-m-d').'</a></li>';
    		}
    		  $datos =  $this->cargar_pedidos_tab($value['ORDEN'],$value['SUBCTA'],$value['Fecha']->format('Y-m-d'),$parametros['paciente']);
    		  // print_r($datos);die();

    		  $num = $datos['num_lin'];
    		  if($datos['neg']==true)
    		  {
    		  	$neg = $datos['neg'];
    		  }
    		  $procedimiento = $datos['detalle'];
    		  // print_r($datos);die();
    		  $ruc = $datos['ruc'];
    		  // print_r($datos['tabla']);die();

    		$content.= $datos['tabla'];
    		$content.='</div>';
    	  }
    	}
    	$tab.='</ul>';
    	$content.='</div>';
    	$tabs_tabla = $tab.$content;
    	if(!isset($datos1[0]['A_No']))
    	{
    		$datos1[0]['A_No'] = 0;
    	}
    	$or = count($ordenes);
    	if($or==0)
    	{
    		$tabla = array('num_lin'=>0,'tabla'=>'<tr><td colspan="7" class="text-center"><b><i>Sin registros...<i></b></td></tr>','item'=>0,'neg'=>$neg,'detalle'=>$procedimiento);
			return $tabla;		
    	}

    	$tabla = array('num_lin'=>$num,'tabla'=> $tabs_tabla,'ruc'=>$ruc,'item'=>$num,'neg'=>$neg,'detalle'=>$procedimiento);

			// print_r($tabla);die();
		return $tabla;		
    }

	

	function cargar_pedidos_tab($orden,$SUBCTA,$fecha,$paciente)
	{
		$datos = $this->modelo->cargar_pedidos_trans($orden,$SUBCTA,$fecha,false,$paciente);

		// print_r($datos);die();
		$num = count($datos);

		$tr = '';
		$iva = 0;$subtotal=0;$total=0;
		$negativos = false;
		$procedimiento = '';
		$cabecera = '<table class="table-sm table-hover" style="width:100%">
        <thead>
          <th>ITEM</th>
          <th>FECHA</th>
          <th>REFERENCIA</th>
          <th>DESCRIPCION</th>
          <th class="text-right">CANTIDAD</th>
          <th class="text-right">COSTO</th>
          <!-- <th>PVP</th> -->
          <!-- <th>DCTO %</th> -->
          <th class="text-right">IVA</th>
          <th class="text-right">IMPORTE</th>
          <th>Stock(-)</th>
        </thead>
        <tbody id="tbl_body">';
        $pie = ' 
        </tbody>
      </table>';
      $d='';
		foreach ($datos as $key => $value) 
		{
			// print_r($value);die();
			$iva+=number_format($value['Total_IVA'],2);
			// print_r($value['VALOR_UNIT']);
			$sub = $value['Valor_Unitario']*$value['Salida'];
			// print_r($sub);die();
			// if(is_float($sub))
			// {
			// 	$subtotal+=number_format($sub,2);
			// }else
			// {
			  $subtotal+=$sub;
			// }

			  $procedimiento=$value['Detalle'];

			$total+=$value['Valor_Total'];


			$FechaInventario = $fecha;
 	 		$CodBodega = '01';
			$costo_existencias =  Leer_Codigo_Inv($value['Codigo_Inv'],$FechaInventario,$CodBodega,$CodMarca='');



			// $costo =  $this->modelo->costo_producto($value['Codigo_Inv']);
			// $existencias =  $this->modelo->costo_venta($value['Codigo_Inv']);

			if($costo_existencias['respueta']!=1){$costo_existencias['datos']['Stock'] = 0; $costo_existencias['datos']['Costo'] = 0;}
			else{
				$exis = number_format($costo_existencias['datos']['Stock']-$value['Salida'],2);
				if($exis<0)
				{
					$nega = $exis;
					$negativos = true;
				}
			}
			$nega = 0;			
			// if(empty($costo))
			// {
			// 	$costo[0]['Costo'] = 0;
			// 	// $costo[0]['Existencia'] = 0;
			// }else
			// {
			// 	$exis = number_format($existencias[0]['Existencia']-$value['Salida'],2);
			// 	if($exis<0)
			// 	{
			// 		$nega = $exis;
			// 		$negativos = true;
			// 	}
			// }

			if($d=='')
			{
			$d =  dimenciones_tabl(strlen($value['ID']));
			$d1 =  dimenciones_tabl(strlen($value['Fecha']->format('Y-m-d')));
			$d2 =  dimenciones_tabl(strlen($value['Codigo_Inv']));
			$d3 =  dimenciones_tabl(strlen($value['Producto']));
			$d4 =  dimenciones_tabl(strlen($value['Salida']));
			$d5 =  dimenciones_tabl(strlen($value['Valor_Unitario']));
			$d6 =  dimenciones_tabl(strlen($value['Total_IVA']));
			$d7 =  dimenciones_tabl(strlen($value['Valor_Total']));
		  }
			$tr.='<tr>
  					<td width="'.$d.'">'.$key.'</td>
  					<td width="'.$d1.'">'.$value['Fecha']->format('Y-m-d').'</td>
  					<td width="'.$d2.'">'.$value['Codigo_Inv'].'</td>
  					<td width="'.$d3.'">'.$value['Producto'].'</td>
  					<td width="'.$d4.'" class="text-right">
  					     <input type="text" class=" text-right form-control input-sm" id="txt_can_lin_'.$value['ID'].'" value="'.$value['Salida'].'" onblur="calcular_totales(\''.$value['ID'].'\');"/>
  					</td>
  					<td width="'.$d5.'">
  					     <input type="text" onblur="calcular_totales(\''.$value['ID'].'\');" class="text-right form-control input-sm" id="txt_pre_lin_'.$value['ID'].'" value="'.number_format($value['Valor_Unitario'],2).'" readonly=""/>
  					</td>
  					<td width="'.$d6.'">
  					     <input type="text" onblur="calcular_totales(\''.$value['ID'].'\');" class="text-right form-control input-sm" id="txt_iva_lin_'.$value['ID'].'" value="'.number_format($value['Total_IVA'],4).'" readonly=""/>
  					</td>
  					<td width="'.$d7.'">
  					     <input type="text" class="text-right form-control input-sm" id="txt_tot_lin_'.$value['ID'].'" value="'.number_format($value['Valor_Total'],4).'" readonly="" />
  					</td>
  					<td width="'.$d7.'">
  					     <input type="text" class="form-control input-sm" id="txt_negarivo_'.$value['ID'].'" value="'.$nega.'" readonly="" />
  					</td>
  					<td width="90px">
  						<button class="btn btn-sm btn-primary" onclick="editar_lin(\''.$value['ID'].'\')" title="Editar paciente"><span class="glyphicon glyphicon-floppy-disk"></span></button> 
  						<button class="btn btn-sm btn-danger" title="Eliminar paciente"  onclick="eliminar_lin(\''.$value['ID'].'\')" ><span class="glyphicon glyphicon-trash"></span></button>
  					</td>
  				</tr>';
			
		}
		$tr.='<tr><td colspan="2"><button type="button" class="btn btn-primary" onclick="generar_factura(\''.$fecha.'\')" id="btn_comprobante"><i class="fa fa-file-text-o"></i> Generar comprobante</button></td><td colspan="4"></td><td class="text-right">Total:</td><td><input type="text" class="form-control input-sm" value="'.$subtotal.'"></td><td colspan="2"></td></tr>';
		// print_r($datos);die();
		if($num!=0)
		{
			// print_r($tr);die();
			$tabla = array('num_lin'=>$num,'tabla'=>$cabecera.$tr.$pie,'ruc'=>$datos[0]['Codigo_P'],'item'=>$num,'subtotal'=>$subtotal,'iva'=>$iva,'total'=>$total+$iva,'neg'=>$negativos,'detalle'=>$procedimiento);	
			return $tabla;		
		}else
		{
			$tabla = array('num_lin'=>0,'tabla'=>'<tr><td colspan="7" class="text-center"><b><i>Sin registros...<i></b></td></tr>','item'=>0,'subtotal'=>$subtotal,'iva'=>$iva,'total'=>$total+$iva,'ruc'=>$datos[0]['Codigo_P'],'neg'=>$negativos,'detalle'=>$procedimiento);
			return $tabla;		
		}
		

	}

	   function cargar_pedidos_bod($parametros)
    {
    	// print_r($parametros);die();
    	$ordenes = $this->modelo->cargar_pedidos_fecha_trans($parametros['num_ped'],$parametros['area']);
    	// print_r($ordenes);die();
    	$datos1 = $this->modelo->cargar_pedidos_trans($parametros['num_ped'],$parametros['area'],false,false,$parametros['paciente']);
    	 $neg = false;
    	 $num = 0;
    	 $procedimiento = '';
         $tab='<ul class="nav nav-tabs">';
         $content = '<div class="tab-content">';
    	foreach ($ordenes as $key => $value) {
    		if($value['SUBCTA']!='.')
    		{

    		if($key==0)
    		{

    		    $content.='<input type="hidden" id="txt_f"  value="'.$value['Fecha']->format('Y-m-d').'"><div id="'.$value['SUBCTA'].'-'.$value['Fecha']->format('Y-m-d').'" class="tab-pane fade in active">';
    			$tab.='<li class="active"><a data-toggle="tab" href="#'.$value['SUBCTA'].'-'.$value['Fecha']->format('Y-m-d').'">'.'Fecha: '.$value['Fecha']->format('Y-m-d').'</a></li>';
    		}else
    		{

    		    $content.='<div id="'.$value['SUBCTA'].'-'.$value['Fecha']->format('Y-m-d').'" class="tab-pane fade">';
    			$tab.='<li><a data-toggle="tab" href="#'.$value['SUBCTA'].'-'.$value['Fecha']->format('Y-m-d').'">'.'Fecha: '.$value['Fecha']->format('Y-m-d').'</a></li>';
    		}
    		  $datos =  $this->cargar_pedidos_tab_bod($value['ORDEN'],$value['SUBCTA'],$value['Fecha']->format('Y-m-d'),$parametros['paciente']);
    		  // print_r($datos);die();

    		  $num = $datos['num_lin'];
    		  if($datos['neg']==true)
    		  {
    		  	$neg = $datos['neg'];
    		  }
    		  $procedimiento = $datos['detalle'];
    		  $ruc = $datos['ruc'];
    		  // print_r($datos['tabla']);die();

    		$content.= $datos['tabla'];
    		$content.='</div>';
    	  }
    	}
    	$tab.='</ul>';
    	$content.='</div>';
    	$tabs_tabla = $tab.$content;
    	if(!isset($datos1[0]['A_No']))
    	{
    		$datos1[0]['A_No'] = 0;
    	}
    	$or = count($ordenes);
    	if($or==0)
    	{
    		$tabla = array('num_lin'=>0,'tabla'=>'<tr><td colspan="7" class="text-center"><b><i>Sin registros...<i></b></td></tr>','item'=>0,'neg'=>$neg,'detalle'=>$procedimiento);
			return $tabla;		
    	}

    	$tabla = array('num_lin'=>$num,'tabla'=> $tabs_tabla,'ruc'=>$ruc,'item'=>$num,'neg'=>$neg,'detalle'=>$procedimiento);

			// print_r($tabla);die();
		return $tabla;		
    }

	

	function cargar_pedidos_tab_bod($orden,$SUBCTA,$fecha,$paciente)
	{
		$datos = $this->modelo->cargar_pedidos_trans($orden,$SUBCTA,$fecha,false,$paciente);

		// print_r($datos);die();
		$num = count($datos);

		$tr = '';
		$iva = 0;$subtotal=0;$total=0;
		$negativos = false;
		$procedimiento = '';
		$cabecera = '<table class="table-sm" style="width:100%">
        <thead>
          <th>ITEM</th>
          <th>FECHA</th>
          <th>REFERENCIA</th>
          <th>DESCRIPCION</th>
          <th class="text-right">CANTIDAD</th>
          <th class="text-right">COSTO</th>
          <!-- <th>PVP</th> -->
          <!-- <th>DCTO %</th> -->
          <th class="text-right">IVA</th>
          <th class="text-right">IMPORTE</th>
          <th>Stock(-)</th>
        </thead>
        <tbody id="tbl_body">';
        $pie = ' 
        </tbody>
      </table>';
      $d='';
		foreach ($datos as $key => $value) 
		{
			// print_r($value);die();
			$iva+=number_format($value['Total_IVA'],2);
			// print_r($value['VALOR_UNIT']);
			$sub = $value['Valor_Unitario']*$value['Salida'];
			// print_r($sub);die();
			// if(is_float($sub))
			// {
			// 	$subtotal+=number_format($sub,2);
			// }else
			// {
			  $subtotal+=$sub;
			// }

			  $procedimiento=$value['Detalle'];

			$total+=$value['Valor_Total'];


			$FechaInventario = $fecha;
 	 		$CodBodega = '01';
			$costo_existencias =  Leer_Codigo_Inv($value['Codigo_Inv'],$FechaInventario,$CodBodega,$CodMarca='');



			// $costo =  $this->modelo->costo_producto($value['Codigo_Inv']);
			// $existencias =  $this->modelo->costo_venta($value['Codigo_Inv']);

			if($costo_existencias['respueta']!=1){$costo_existencias['datos']['Stock'] = 0; $costo_existencias['datos']['Costo'] = 0;}
			else{
				$exis = number_format($costo_existencias['datos']['Stock']-$value['Salida'],2);
				if($exis<0)
				{
					$nega = $exis;
					$negativos = true;
				}
			}
			$nega = 0;			
			// if(empty($costo))
			// {
			// 	$costo[0]['Costo'] = 0;
			// 	// $costo[0]['Existencia'] = 0;
			// }else
			// {
			// 	$exis = number_format($existencias[0]['Existencia']-$value['Salida'],2);
			// 	if($exis<0)
			// 	{
			// 		$nega = $exis;
			// 		$negativos = true;
			// 	}
			// }

			if($d=='')
			{
			$d =  dimenciones_tabl(strlen($value['ID']));
			$d1 =  dimenciones_tabl(strlen($value['Fecha']->format('Y-m-d')));
			$d2 =  dimenciones_tabl(strlen($value['Codigo_Inv']));
			$d3 =  dimenciones_tabl(strlen($value['Producto']));
			$d4 =  dimenciones_tabl(strlen($value['Salida']));
			$d5 =  dimenciones_tabl(strlen($value['Valor_Unitario']));
			$d6 =  dimenciones_tabl(strlen($value['Total_IVA']));
			$d7 =  dimenciones_tabl(strlen($value['Valor_Total']));
		  }
			$tr.='<tr>
  					<td width="'.$d.'">'.$key.'</td>
  					<td width="'.$d1.'">'.$value['Fecha']->format('Y-m-d').'</td>
  					<td width="'.$d2.'">'.$value['Codigo_Inv'].'</td>
  					<td width="'.$d3.'">'.$value['Producto'].'</td>
  					<td width="'.$d4.'" class="text-right">
  					     <input type="text" class=" text-right form-control input-xs" id="txt_can_lin_'.$value['ID'].'" value="'.$value['Salida'].'" onblur="calcular_totales(\''.$value['ID'].'\');"/>
  					</td>
  					<td width="'.$d5.'">
  					     <input type="text" onblur="calcular_totales(\''.$value['ID'].'\');" class="text-right form-control input-xs" id="txt_pre_lin_'.$value['ID'].'" value="'.number_format($value['Valor_Unitario'],2).'" readonly=""/>
  					</td>
  					<td width="'.$d6.'">
  					     <input type="text" onblur="calcular_totales(\''.$value['ID'].'\');" class="text-right form-control input-xs" id="txt_iva_lin_'.$value['ID'].'" value="'.number_format($value['Total_IVA'],4).'" readonly=""/>
  					</td>
  					<td width="'.$d7.'">
  					     <input type="text" class="text-right form-control input-xs" id="txt_tot_lin_'.$value['ID'].'" value="'.number_format($value['Valor_Total'],4).'" readonly="" />
  					</td>
  					<td width="'.$d7.'">
  					     <input type="text" class="form-control input-xs" id="txt_negarivo_'.$value['ID'].'" value="'.$nega.'" readonly="" />
  					</td>
  					<td width="90px">
  						<button class="btn btn-xs btn-primary" onclick="editar_lin(\''.$value['ID'].'\')" title="Editar paciente"><span class="glyphicon glyphicon-floppy-disk"></span></button> 
  						<button class="btn btn-xs btn-danger" title="Eliminar paciente"  onclick="eliminar_lin(\''.$value['ID'].'\')" ><span class="glyphicon glyphicon-trash"></span></button>
  					</td>
  				</tr>';
			
		}
		$tr.='<tr><td colspan="2"><button type="button" class="btn btn-primary" onclick="generar_factura(\''.$fecha.'\')" id="btn_comprobante"><i class="fa fa-file-text-o"></i> Generar comprobante</button></td><td colspan="4"></td><td class="text-right">Total:</td><td><input type="text" class="form-control input-xs" value="'.$subtotal.'"></td><td colspan="2"></td></tr>';
		// print_r($datos);die();
		if($num!=0)
		{
			// print_r($tr);die();
			$tabla = array('num_lin'=>$num,'tabla'=>$cabecera.$tr.$pie,'ruc'=>$datos[0]['Codigo_P'],'item'=>$num,'subtotal'=>$subtotal,'iva'=>$iva,'total'=>$total+$iva,'neg'=>$negativos,'detalle'=>$procedimiento);	
			return $tabla;		
		}else
		{
			$tabla = array('num_lin'=>0,'tabla'=>'<tr><td colspan="7" class="text-center"><b><i>Sin registros...<i></b></td></tr>','item'=>0,'subtotal'=>$subtotal,'iva'=>$iva,'total'=>$total+$iva,'neg'=>$negativos,'detalle'=>$procedimiento);
			return $tabla;		
		}
		

	}

	function guardar_entrega($parametro)
	{
		// print_r($parametro);die();
		$num_ped =$parametro['num_ped'];
		if($num_ped=='')
		{
			$num_ped = $this->modelo->asignar_num_pedido_clinica();
			$revisar = $this->modelo->COD_PEDIDO_CLINICA_EXISTENTE_TRANS($num_ped);
			while ($revisar == -1) {
				$num_ped+=1;
				$revisar = $this->modelo->COD_PEDIDO_CLINICA_EXISTENTE_TRANS($num_ped);			
			}
			SetAdoAddNew("Codigos"); 	
			SetAdoFields('Numero',$num_ped+1);

			SetAdoFieldsWhere('Concepto','PEDIDO_CLINICA');
			SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);
			SetAdoFieldsWhere('Periodo',$_SESSION['INGRESO']['periodo']);
			SetAdoUpdateGeneric();
		}
		   SetAdoAddNew("Trans_Kardex"); 		
		   SetAdoFields('Codigo_Inv',$parametro['codigo']);
		   SetAdoFields('Producto',$parametro['producto']);
		   SetAdoFields('UNIDAD',$parametro['uni']); /**/
		   SetAdoFields('Salida',$parametro['cant']);
		   SetAdoFields('Cta_Inv',$parametro['cta_pro']);
		   SetAdoFields('CodigoL',$parametro['rubro']);		   
		   SetAdoFields('CodigoU',$_SESSION['INGRESO']['Id']);   
		   SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		   SetAdoFields('Orden_No',$num_ped); /**/
		   SetAdoFields('A_No',$parametro['id']+1);
		   SetAdoFields('Fecha',date('Y-m-d',strtotime($parametro['fecha'])));
		   SetAdoFields('TC',$parametro['TC']);
		   SetAdoFields('Valor_Total',number_format($parametro['total'],2,'.',''));
		   SetAdoFields('CANTIDAD',$parametro['cant']);
		   SetAdoFields('Valor_Unitario',number_format($parametro['valor'],$_SESSION['INGRESO']['Dec_PVP'],'.',''));
		   SetAdoFields('DH',2);
		   SetAdoFields('Contra_Cta',$parametro['cc']);
		   SetAdoFields('Descuento',$parametro['descuento']);
		   SetAdoFields('Codigo_P',$parametro['CodigoP']);
		   SetAdoFields('Detalle',$parametro['pro']);
		   SetAdoFields('Centro_Costo',$parametro['area']);
		   SetAdoFields('Codigo_Dr',$parametro['solicitante']);
		   SetAdoFields('Costo',number_format($parametro['valor'],2,'.',''));
		   SetAdoFields('TP','_');
		   SetAdoFields('CodBodega','01');

		   if($parametro['iva']!=0)
		   {
		   	   // $datos[19]['campo']='IVA';
		       // $datos[19]['dato']=(round($parametro['total'],2)*1.12)-round($parametro['total'],2);
		   }
		   // print_r($datos);die();
		   $resp = SetAdoUpdate();
		   $num = $num_ped;
		   return  $respuesta = array('ped'=>$num,'resp'=>$resp);
		
	}

	function guardar_entrega_bod($parametro)
	{
		// print_r($parametro);die();
		$num_ped =$parametro['num_ped'];
		if($num_ped=='')
		{
			$num_ped = $this->modelo->asignar_num_pedido_clinica_bod();
			$revisar = $this->modelo->COD_PEDIDO_CLINICA_EXISTENTE_TRANS($num_ped);
			while ($revisar == -1) {
				$num_ped+=1;
				$revisar = $this->modelo->COD_PEDIDO_CLINICA_EXISTENTE_TRANS($num_ped);			
			}
			SetAdoAddNew("Codigos"); 	
			SetAdoFields('Numero',$num_ped+1);

			SetAdoFieldsWhere('Concepto','PEDIDO_CLINICA');
			SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);
			SetAdoFieldsWhere('Periodo',$_SESSION['INGRESO']['periodo']);
			SetAdoUpdateGeneric();
		}

		   SetAdoAddNew("Trans_Kardex"); 	
		   SetAdoFields('Codigo_Inv',$parametro['codigo']);
		   SetAdoFields('Producto',$parametro['producto']);
		   SetAdoFields('UNIDAD',$parametro['uni']); /**/
		   SetAdoFields('Salida',$parametro['cant']);
		   SetAdoFields('Cta_Inv',$parametro['cta_pro']);
		   SetAdoFields('CodigoL',$parametro['rubro']);		   
		   SetAdoFields('CodigoU',$_SESSION['INGRESO']['Id']);   
		   SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		   SetAdoFields('Orden_No',$num_ped); /**/
		   SetAdoFields('A_No',$parametro['id']+1);
		   SetAdoFields('Fecha',date('Y-m-d',strtotime($parametro['fecha'])));
		   SetAdoFields('TC',$parametro['TC']);
		   SetAdoFields('Valor_Total',round($parametro['total'],2));
		   SetAdoFields('CANTIDAD',$parametro['cant']);
		   SetAdoFields('Valor_Unitario',number_format($parametro['valor'],$_SESSION['INGRESO']['Dec_PVP'],'.',''));
		   SetAdoFields('DH',2);
		   SetAdoFields('Contra_Cta',$parametro['cc']);
		   SetAdoFields('Descuento',$parametro['descuento']);
		   SetAdoFields('Codigo_P',$parametro['CodigoP']);
		   SetAdoFields('Detalle',$parametro['pro']);
		   SetAdoFields('Centro_Costo',$parametro['area']);
		   SetAdoFields('Codigo_Dr',$parametro['solicitante']);
		   SetAdoFields('Costo',round($parametro['valor'],2));

		   if($parametro['iva']!=0)
		   {
		   	   // $datos[19]['campo']='IVA';
		       // $datos[19]['dato']=(round($parametro['total'],2)*1.12)-round($parametro['total'],2);
		   }
		   // print_r($datos);die();
	
		   $resp = SetAdoUpdate();
		   $num = $num_ped;
		   return  $respuesta = array('ped'=>$num,'resp'=>$resp);
		
	}



	function lista_entrega()
	{
		$resp = $this->modelo->lista_entrega();
		$resp= array_map(array($this, 'encode'), $resp);
		// print_r($resp);die();
		return $resp;
	}

	function lineas_eli($parametros)
	{
		$resp = $this->modelo->lineas_eli($parametros);
		return $resp;

	}

	function lineas_edi($parametros)
	{

			$datos[0]['campo']='Salida';
		  $datos[0]['dato']=$parametros['can'];
			$datos[1]['campo']='Valor_Unitario';
		  $datos[1]['dato']=$parametros['pre'];
			$datos[2]['campo']='Valor_Total';
		  $datos[2]['dato']=$parametros['tot'];

		    $campoWhere[0]['campo']='ID';
		    $campoWhere[0]['valor']=$parametros['lin'];

		$resp = $this->modelo->lineas_edi($datos,$campoWhere);
		return $resp;
		
	}

	function cargar_ficha($parametros)
	{
		$datos = $this->paciente->cargar_paciente($parametros);
		print_r($datos);
	}
	function generar_factura($orden,$ruc,$area,$nombre,$fecha)
	{

		$negativos = '';
		$datos = $this->modelo->cargar_pedidos_trans($orden,$area,$fecha,false,$ruc);

		// print_r($datos);
		// print_r($ruc);die();
		foreach ($datos as $key => $value) 
		{
			$costo =  $this->modelo->costo_venta($value['Codigo_Inv']);
		// print_r($costo);die();
			$nega = 0;
			if(empty($costo))
			{
				$costo[0]['Costo'] = 0;
				$costo[0]['Existencia'] = 0;
			}else
			{
				$exis = number_format($costo[0]['Existencia']-$value['Salida'],2);
				if($exis<0)
				{
					$negativos.=$value['Codigo_Inv'].',';
				}
			}
		}
		$negativos = substr($negativos,0,-1);

		$asientos_SC = $this->modelo->datos_asiento_SC_trans($orden,$fecha,$negativos);
		// $asientos_SC = $this->modelo->datos_asiento_SC($orden,$fecha,$negativos);

		// print_r($asientos_SC);die();

		// print_r($asientos_SC);die();

		$parametros_debe = array();
		$parametros_haber = array();
		// $fecha = date('Y-m-d');

		// print_r($asientos_SC);die();
		foreach ($asientos_SC as $key => $value) {
			 $cuenta = $this->modelo->catalogo_cuentas($value['CONTRA_CTA']);
			 $sub = $this->modelo->catalogo_subcuentas($value['SUBCTA']);
			$parametros = array(
                    'be'=>$cuenta[0]['Cuenta'],
                    'ru'=> '',
                    'co'=> $value['CONTRA_CTA'],// codigo de cuenta cc
                    'tip'=>$cuenta[0]['TC'],//tipo de cuenta(CE,CD,..--) biene de catalogo subcuentas TC
                    'tic'=> 1, //debito o credito (1 o 2);
                    'sub'=> $value['SUBCTA'], //Codigo se trae catalogo subcuenta
                    'sub2'=>$cuenta[0]['Cuenta'],//nombre del beneficiario
                    'fecha_sc'=> $value['Fecha_Fab']->format('Y-m-d'), //fecha 
                    'fac2'=>0,
                    'mes'=> 0,
                    'valorn'=> round($value['total'],2),//valor de sub cuenta 
                    'moneda'=> 1, /// moneda 1
                    'Trans'=>$sub[0]['Detalle'],//detalle que se trae del asiento
                    'T_N'=> '99',
                    't'=> $sub[0]['TC'],                        
                  );
                  $this->modelo->generar_asientos_SC($parametros);
		}

		// print_r('expression');die();

		//asientos para el debe
		$asiento_debe = $this->modelo->datos_asiento_debe_trans($orden,$fecha,$negativos);
		// print_r($asiento_debe);die();
		$fecha = $asiento_debe[0]['fecha']->format('Y-m-d');
		
		foreach ($asiento_debe as $key => $value) 
		{
			// print_r($value);die();
			$cuenta = $this->modelo->catalogo_cuentas($value['cuenta']);		
				$parametros_debe = array(
				 "va" =>round($value['total'],2),//valor que se trae del otal sumado
                  "dconcepto1" =>$cuenta[0]['Cuenta'],
                  "codigo" => $value['cuenta'], // cuenta de codigo de 
                  "cuenta" => $cuenta[0]['Cuenta'], // detalle de cuenta;
                  "efectivo_as" =>$value['fecha']->format('Y-m-d'), // observacion si TC de catalogo de cuenta
                  "chq_as" => 0,
                  "moneda" => 1,
                  "tipo_cue" => 1,
                  "cotizacion" => 0,
                  "con" => 0,// depende de moneda
                  "t_no" => '99',
			);
				 $this->modelo->ingresar_asientos($parametros_debe);
		}
		// print_r('expresion');die();

        // asiento para el haber
		$asiento_haber  = $this->modelo->datos_asiento_haber_trans($orden,$fecha,$negativos);
		// print_r($asiento_haber);die();
		foreach ($asiento_haber as $key => $value) {
			$cuenta = $this->modelo->catalogo_cuentas($value['cuenta']);		
			// print_r($cuenta);die();	
				$parametros_haber = array(
                  "va" =>round($value['total'],2),//valor que se trae del otal sumado
                  "dconcepto1" =>$cuenta[0]['Cuenta'],
                  "codigo" => $value['cuenta'], // cuenta de codigo de 
                  "cuenta" => $cuenta[0]['Cuenta'], // detalle de cuenta;
                  "efectivo_as" =>$value['fecha']->format('Y-m-d'), // observacion si TC de catalogo de cuenta
                  "chq_as" => 0,
                  "moneda" => 1,
                  "tipo_cue" => 2,
                  "cotizacion" => 0,
                  "con" => 0,// depende de moneda
                  "t_no" => '99',
                );
                $this->modelo->ingresar_asientos($parametros_haber);
		}


		// print_r('expression');die();

		// print_r($fecha.'-'.$nombre.'-'.$ruc);die();
		// $parametros = array('tip'=> 'CD','fecha'=>$fecha);
	 //    $num_comprobante = $this->modelo->numero_comprobante($parametros);

	    $num_comprobante = numero_comprobante1('Diario',true,true,$fecha);
	    $dat_comprobantes = $this->modelo->datos_comprobante();
	    $debe = 0;
		$haber = 0;
		foreach ($dat_comprobantes as $key => $value) {
			$debe+=$value['DEBE'];
			$haber+=$value['HABER'];
		}
		if(strval($debe)==strval($haber))
		{
			if($debe !=0 && $haber!=0)
			{
				 $parametro_comprobante = array(
        	        'ru'=> $ruc, //codigo del cliente que sale co el ruc del beneficiario codigo
        	        'tip'=>'CD',//tipo de cuenta contable cd, etc
        	        "fecha1"=> $fecha,// fecha actual 2020-09-21
        	        'concepto'=>'Salida de inventario para '.$nombre.' con CI: '.$ruc.' el dia '.$fecha, //detalle de la transaccion realida
        	        'totalh'=> round($haber,2), //total del haber
        	        'num_com'=> '.'.date('Y', strtotime($fecha)).'-'.$num_comprobante, // codigo de comprobante de esta forma 2019-9000002
        	        );
				 // print_r($nombre);print_r($ruc);print_r($fecha);
				 // print_r($parametro_comprobante);die();
                $resp = $this->modelo->generar_comprobantes($parametro_comprobante);
                // $cod = explode('-',$num_comprobante);
                // die();
                if($resp==$num_comprobante)
                {
                	if($this->ingresar_trans_kardex_salidas($orden,$num_comprobante,$fecha,$area,$ruc,$nombre,$negativos)==1)
                	{
                		 	// mayorizar_inventario_sp();
                			return array('resp'=>1,'com'=>$num_comprobante);
                		
                	}else
                	{
                		return array('resp'=>-1,'com'=>'Uno o todos No se pudo registrar en Trans_Kardex');
                	}
                }else
                {
        	        return array('resp'=>-1,'com'=>$resp);
                }

			}else
			{
				// print_r($debe."-".$haber); 
				 return array('resp'=>-1,'com'=>'Los resultados son 0');

			}
		}else
		{
			$this->modelo->eliminar_asieto();
			$this->modelo->eliminar_aiseto_sc($fecha);
			return array('resp'=>-1,'com'=>'No coinciden','debe'=>$debe,'haber'=>$haber);

		}

	}

	function generar_factura_bodega($orden,$ruc,$area,$nombre,$fecha)
	{

		$negativos = '';
		$datos = $this->modelo->cargar_pedidos_trans($orden,$area,$fecha,false,$ruc);

		// print_r($datos);
		// print_r($ruc);die();
		foreach ($datos as $key => $value) 
		{
			$costo =  $this->modelo->costo_venta($value['Codigo_Inv']);
		// print_r($costo);die();
			$nega = 0;
			if(empty($costo))
			{
				$costo[0]['Costo'] = 0;
				$costo[0]['Existencia'] = 0;
			}else
			{
				$exis = number_format($costo[0]['Existencia']-$value['Salida'],2);
				if($exis<0)
				{
					$negativos.=$value['CODIGO_INV'].',';
				}
			}
		}
		$negativos = substr($negativos,0,-1);

		$asientos_SC = $this->modelo->datos_asiento_SC_trans($orden,$fecha,$negativos);
		// $asientos_SC = $this->modelo->datos_asiento_SC($orden,$fecha,$negativos);

		// print_r($asientos_SC);die();

		// print_r($asientos_SC);die();

		$parametros_debe = array();
		$parametros_haber = array();
		// $fecha = date('Y-m-d');

		// print_r($asientos_SC);die();
		foreach ($asientos_SC as $key => $value) {
			 $cuenta = $this->modelo->catalogo_cuentas($value['CONTRA_CTA']);
			 $sub = $this->modelo->catalogo_subcuentas($value['SUBCTA']);
			$parametros = array(
                    'be'=>$cuenta[0]['Cuenta'],
                    'ru'=> '',
                    'co'=> $value['CONTRA_CTA'],// codigo de cuenta cc
                    'tip'=>$cuenta[0]['TC'],//tipo de cuenta(CE,CD,..--) biene de catalogo subcuentas TC
                    'tic'=> 1, //debito o credito (1 o 2);
                    'sub'=> $value['SUBCTA'], //Codigo se trae catalogo subcuenta
                    'sub2'=>$cuenta[0]['Cuenta'],//nombre del beneficiario
                    'fecha_sc'=> $value['Fecha_Fab']->format('Y-m-d'), //fecha 
                    'fac2'=>0,
                    'mes'=> 0,
                    'valorn'=> round($value['total'],2),//valor de sub cuenta 
                    'moneda'=> 1, /// moneda 1
                    'Trans'=>$sub[0]['Detalle'],//detalle que se trae del asiento
                    'T_N'=> '99',
                    't'=> $cuenta[0]['TC'],                        
                  );
                  $this->modelo->generar_asientos_SC($parametros);
		}

		// print_r('expression');die();

		//asientos para el debe
		$asiento_debe = $this->modelo->datos_asiento_debe_trans($orden,$fecha,$negativos);
		// print_r($asiento_debe);die();
		$fecha = $asiento_debe[0]['fecha']->format('Y-m-d');
		
		foreach ($asiento_debe as $key => $value) 
		{
			// print_r($value);die();
			$cuenta = $this->modelo->catalogo_cuentas($value['cuenta']);		
				$parametros_debe = array(
				 "va" =>round($value['total'],2),//valor que se trae del otal sumado
                  "dconcepto1" =>$cuenta[0]['Cuenta'],
                  "codigo" => $value['cuenta'], // cuenta de codigo de 
                  "cuenta" => $cuenta[0]['Cuenta'], // detalle de cuenta;
                  "efectivo_as" =>$value['fecha']->format('Y-m-d'), // observacion si TC de catalogo de cuenta
                  "chq_as" => 0,
                  "moneda" => 1,
                  "tipo_cue" => 1,
                  "cotizacion" => 0,
                  "con" => 0,// depende de moneda
                  "t_no" => '99',
			);
				 $this->modelo->ingresar_asientos($parametros_debe);
		}
		// print_r('expresion');die();

        // asiento para el haber
		$asiento_haber  = $this->modelo->datos_asiento_haber_trans($orden,$fecha,$negativos);
		// print_r($asiento_haber);die();
		foreach ($asiento_haber as $key => $value) {
			$cuenta = $this->modelo->catalogo_cuentas($value['cuenta']);		
			// print_r($cuenta);die();	
				$parametros_haber = array(
                  "va" =>round($value['total'],2),//valor que se trae del otal sumado
                  "dconcepto1" =>$cuenta[0]['Cuenta'],
                  "codigo" => $value['cuenta'], // cuenta de codigo de 
                  "cuenta" => $cuenta[0]['Cuenta'], // detalle de cuenta;
                  "efectivo_as" =>$value['fecha']->format('Y-m-d'), // observacion si TC de catalogo de cuenta
                  "chq_as" => 0,
                  "moneda" => 1,
                  "tipo_cue" => 2,
                  "cotizacion" => 0,
                  "con" => 0,// depende de moneda
                  "t_no" => '99',
                );
                $this->modelo->ingresar_asientos($parametros_haber);
		}


		// print_r('expression');die();

		// print_r($fecha.'-'.$nombre.'-'.$ruc);die();
		// $parametros = array('tip'=> 'CD','fecha'=>$fecha);
	 //    $num_comprobante = $this->modelo->numero_comprobante($parametros);

	    $num_comprobante = numero_comprobante1('Diario',true,true,$fecha);
	    $dat_comprobantes = $this->modelo->datos_comprobante();
	    $debe = 0;
		$haber = 0;
		foreach ($dat_comprobantes as $key => $value) {
			$debe+=$value['DEBE'];
			$haber+=$value['HABER'];
		}
		if(strval($debe)==strval($haber))
		{
			if($debe !=0 && $haber!=0)
			{
				 $parametro_comprobante = array(
        	        'ru'=> $ruc, //codigo del cliente que sale co el ruc del beneficiario codigo
        	        'tip'=>'CD',//tipo de cuenta contable cd, etc
        	        "fecha1"=> $fecha,// fecha actual 2020-09-21
        	        'concepto'=>'Salida de inventario bodega  para '.$nombre.' con CI: '.$ruc.' el dia '.$fecha, //detalle de la transaccion realida
        	        'totalh'=> round($haber,2), //total del haber
        	        'num_com'=> '.'.date('Y', strtotime($fecha)).'-'.$num_comprobante, // codigo de comprobante de esta forma 2019-9000002
        	        );
				 // print_r($nombre);print_r($ruc);print_r($fecha);
				 // print_r($parametro_comprobante);die();
                $resp = $this->modelo->generar_comprobantes($parametro_comprobante);
                // $cod = explode('-',$num_comprobante);
                // die();
                if($resp==$num_comprobante)
                {
                	if($this->ingresar_trans_kardex_salidas($orden,$num_comprobante,$fecha,$area,$ruc,$nombre,$negativos)==1)
                	{
                		 	// mayorizar_inventario_sp();
                			return array('resp'=>1,'com'=>$num_comprobante);
                		
                	}else
                	{
                		return array('resp'=>-1,'com'=>'Uno o todos No se pudo registrar en Trans_Kardex');
                	}
                }else
                {
        	        return array('resp'=>-1,'com'=>$resp);
                }

			}else
			{
				// print_r($debe."-".$haber); 
				 return array('resp'=>-1,'com'=>'Los resultados son 0');

			}
		}else
		{
			$this->modelo->eliminar_asieto();
			$this->modelo->eliminar_aiseto_sc($fecha);
			return array('resp'=>-1,'com'=>'No coinciden','debe'=>$debe,'haber'=>$haber);

		}

	}


	function ingresar_trans_kardex_salidas($orden,$comprobante,$fechaC,$area,$ruc,$nombre,$negativos)
    {
		$datos_K = $this->modelo->cargar_pedidos_trans($orden,$area,$fechaC,$negativos,$ruc);
		// print_r($datos_K);
		// $comprobante = explode('.',$comprobante);
		// $comprobante = explode('-',trim($comprobante[1]));
		$comprobante = $comprobante;
		$resp = 1;
		$lista = '';
		foreach ($datos_K as $key => $value) {
			// print_r($value);die();
		   $datos_inv = $this->modelo->lista_hijos_id($value['Codigo_Inv']);
		   // print_r($datos_inv.'-'.$datos_inv[0]['id']);die();
		    $cant[2] = 0;
		   if(count($datos_inv)>0)
		   {
		   	 $cant = explode(',',$datos_inv[0]['id']);
		   }
		    $datos[0]['campo'] ='Numero';
		    $datos[0]['dato'] =$comprobante;  
		    $datos[1]['campo'] ='T';
		    $datos[1]['dato'] ='N'; 
		    $datos[2]['campo'] ='TP';
		    $datos[2]['dato'] ='CD'; 
		    $datos[3]['campo'] ='Costo';
		    $datos[3]['dato'] =round($value['Valor_Unitario'],2); 
		    $datos[4]['campo'] ='Total';
		    $datos[4]['dato'] =round($value['Valor_Total'],2);
		    $datos[5]['campo'] ='Existencia';
		    $datos[5]['dato'] =round(($cant[2]),2)-round(($value['Salida']),2);
		    $datos[6]['campo'] ='CodBodega';
		    $datos[6]['dato'] ='01';

		    $datos[7]['campo'] ='Detalle';
		    $datos[7]['dato'] ='Salida de inventario para '.$nombre.' con CI: '.$ruc.' el dia '.$fechaC;
		    $datos[8]['campo'] ='Procesado';
		    $datos[8]['dato'] =0;

		    $where[0]['campo'] = 'ID'; 
		    $where[0]['valor'] = $value['ID'];

		    $res = update_generico($datos,'Trans_Kardex',$where);



		    $datosAr[0]['campo'] ='Procesado';
		    $datosAr[0]['dato'] =0;
		    $whereAr[0]['campo'] = 'Codigo_Inv'; 
		    $whereAr[0]['valor'] = $value['Codigo_Inv'];
		    $whereAr[1]['campo'] = 'Item'; 
		    $whereAr[1]['valor'] = $_SESSION['INGRESO']['item'];
		    $whereAr[2]['campo'] = 'Periodo'; 
		    $whereAr[2]['valor'] = $_SESSION['INGRESO']['periodo'];
		    $resA = update_generico($datosAr,'Trans_Kardex',$whereAr);

		    if($res!=1)
		    {
		    	$resp = 0;
		    }

	}
	                		// print_r($resp);die();
	return $resp;

}

function areas($codigo)
{
	$resp = $this->descargos->area_descargo(false,$codigo);
	return $resp;
}
function editar_procedimiento($parametros)
{
	    $campoWhere[0]['campo']='Orden_No';
		  $campoWhere[0]['valor']=$parametros['ped'];
		  $campoWhere[0]['tipo'] ='string';

		$datos[0]['campo']='Detalle';
		$datos[0]['dato']=$parametros['text'];

		  return update_generico($datos,'Trans_Kardex',$campoWhere);
}

function mayorizar()
{
		return mayorizar_inventario_sp();
}

}
?>