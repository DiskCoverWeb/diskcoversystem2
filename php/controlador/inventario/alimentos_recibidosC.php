<?php 
require_once(dirname(__DIR__,2)."/modelo/inventario/alimentos_recibidosM.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

/*
-----------------nota importante en campo (T)----------------------
I =>INGRESO NORMAL
R =>EDITAR INGRESO
P =>LISTO PARA CHECHING
C =>ESTA EN CLASIFICACION

E => YA ALMACENADO (APLICA A LINEAS)

*/


$controlador = new alimentos_recibidosC();
if(isset($_GET['proveedores']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->proveedores($query));
}
if(isset($_GET['guardar']))
{
	// print_r($_POST);die();
	parse_str($_POST['parametros'],$parametros);
	parse_str($_POST['transporte'],$transporte);
	parse_str($_POST['gavetas'],$gavetas);
	echo json_decode($controlador->guardar($parametros,$transporte,$gavetas));
}
if(isset($_GET['guardar2']))
{
	$parametros = $_POST;
	echo json_decode($controlador->guardar2($parametros));
}
if(isset($_GET['guardar_pedido']))
{
	$parametros = $_POST;
	echo json_encode($controlador->guardar_pedido($parametros));
}
if(isset($_GET['eliminar_pedido']))
{
	$parametros = $_POST;
	echo json_decode($controlador->eliminar_pedido($parametros));
}
if(isset($_GET['datos_pedido_edi']))
{
	$parametros = $_POST;
	echo json_encode($controlador->datos_pedido_edi($parametros));
}
if(isset($_GET['alimentos']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->cta_procesos($query));
}
if(isset($_GET['detalle_ingreso']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->detalle_ingreso($query));
}
if(isset($_GET['detalle_ingreso2']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->detalle_ingreso2($query));
}
if(isset($_GET['datos_ingreso']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->datos_ingreso($id));
}

if(isset($_GET['autoincrementable']))
{
	$parametros = $_POST['parametros'];
	$num = $controlador->autoincrementable($parametros);
	$num = generaCeros($num,3);
	echo json_encode($num);
}

//busqueda para clasificacion
if(isset($_GET['search']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar($query));

}
if(isset($_GET['pedidos_proce']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar_procesado($query));

}
if(isset($_GET['search_contabilizado']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar_contabilizado($query));

}
if(isset($_GET['guardar_recibido']))
{
	$parametros = $_POST;
	echo json_encode($controlador->guardar_recibido($parametros));
}
if(isset($_GET['pedido']))
{
	$parametros= $_POST['parametros'];	
	echo json_encode($controlador->cargar_productos($parametros));
}
if(isset($_GET['pedido_trans']))
{
	$parametros= $_POST['parametros'];	
	echo json_encode($controlador->cargar_productos_trans_pedidos($parametros));
}
if(isset($_GET['pedido_trans_datos']))
{
	$parametros= $_POST['parametros'];	
	echo json_encode($controlador->cargar_productos_trans_pedidos_datos($parametros));
}
if(isset($_GET['pedido_checking']))
{
	$parametros= $_POST['parametros'];	
	echo json_encode($controlador->cargar_productos_checking($parametros));
}
if(isset($_GET['lin_eli']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lineas_eli($parametros));
}
if(isset($_GET['lin_eli_pedido']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lineas_eli_pedido($parametros));
}
if(isset($_GET['autocom_pro']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->autocomplet_producto($query));
}
if(isset($_GET['autocom_pro2']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->autocomplet_producto2($query));
}

if(isset($_GET['cargar_datos']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_datos($parametros));
}

if(isset($_GET['cargar_datos_procesados']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_datos_procesados($parametros));
}

if(isset($_GET['producto_costo']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->producto_costo($parametros));
}
if(isset($_GET['editar_precio']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->editar_precio($parametros));
}
if(isset($_GET['editar_pedido']))
{
	$parametros = $_POST;
	echo json_encode($controlador->editar_pedido($parametros));
}
if(isset($_GET['editar_checked']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->editar_checked($parametros));
}
if(isset($_GET['actualizar_trans_kardex']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->actualizar_trans_kardex($parametros));
}
if(isset($_GET['eli_all_pedido']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->eli_all_pedido($parametros));
}
if(isset($_GET['contabilizar']))
{
	$parametros = $_POST;
	echo json_encode($controlador->contabilizar($parametros));
}
if(isset($_GET['lista_bodegas_arbol']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_bodegas_arbol($parametros));
}

if(isset($_GET['notificar_clasificacion']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->notificar_clasificacion($parametros));
}
if(isset($_GET['notificar_egresos']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->notificar_egresos($parametros));
}
if(isset($_GET['comentar_clasificacion']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->comentar_clasificacion($parametros));
}
if(isset($_GET['guardar_comentario_check']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_comentario_check($parametros));
}
if(isset($_GET['notificar_usuario']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->notificar_usuario($parametros));
}

if(isset($_GET['listar_notificaciones']))
{
	echo json_encode($controlador->listar_notificaciones());
}
if(isset($_GET['cambiar_estado']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cambiar_estado($parametros));
}
if(isset($_GET['cambiar_estado_solucionado']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cambiar_estado_solucionado($parametros));
}
if(isset($_GET['cambiar_a_clasificacion']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cambiar_a_clasificacion($parametros));
}
if(isset($_GET['preguntas_transporte']))
{
	// $parametros = $_POST['parametros'];
	echo json_encode($controlador->preguntas_transporte());
}
if(isset($_GET['estado_trasporte']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->estado_trasporte($parametros));
}

if(isset($_GET['gavetas']))
{
	echo json_encode($controlador->gavetas());
}
if(isset($_GET['placas_auto']))
{
	$tipo = $_POST['tipo'];
	echo json_encode($controlador->placas_auto($tipo));
}

/**
 * 
 */
class alimentos_recibidosC
{
	private $modelo;
	private $barras;
	private $modales;
	function __construct()
	{
		$this->modelo = new alimentos_recibidosM();
	}

	function guardar($parametros,$transporte,$gavetas)
	{
		// print_r($parametros);
		// print_r($transporte);
		// print_r($gavetas);
		// die();


		$parametros['fecha'] = $parametros['txt_fecha'];
		$codigo = substr($parametros['txt_codigo'],0,-3).generaCeros($this->autoincrementable($parametros),3);
		// print_r($parametros);die();
		SetAdoAddNew('Trans_Correos');
		SetAdoFields('T','I');
		SetAdoFields('Mensaje',$parametros['txt_comentario']);
		SetAdoFields('Fecha_P',$parametros['txt_fecha']);
		SetAdoFields('CodigoP',$parametros['ddl_ingreso']);
		SetAdoFields('Cod_C',$parametros['ddl_alimento']);
		SetAdoFields('Porc_C',number_format($parametros['txt_temperatura'],2,'.',''));
		// SetAdoFields('Cod_R',$parametros['cbx_estado_tran']);
		SetAdoFields('TOTAL',$parametros['txt_cant']);

		SetAdoFields('Envio_No',$codigo);
		SetAdoUpdate();

		if($parametros['rbx_trasporte']=='SI')
		{
			$op_vehi ='.';
			$tipo_ve = '.';
			$placa = '.';
			switch ($transporte['rb_op_vehiculo']) {
				case '1':
				$op_vehi = 'Interno';
				$placa = $transporte['ddl_datos_vehiculo'];
					break;
				
				default:				
				$op_vehi = 'Externo';
					break;
			}
			switch ($transporte['rb_tipo_vehiculo']) {
				case '1':
					$tipo_ve = 'Furgon';
					break;
				case '2':
					$tipo_ve = 'Camion';
					break;
				case '3':
					$tipo_ve = 'liviano';
					break;				
				default:
					$tipo_ve = 'liviano';
					break;
			}
			foreach ($transporte as $key => $value) {
				$Cmds = explode('_', $key);
				SetAdoAddNew('Trans_Fletes');
			    SetAdoFields('TP',$Cmds[0]);
			    SetAdoFields('Referencia',$Cmds[1]);
			    SetAdoFields('Cumple',$value);
			    SetAdoFields('CodigoC',$placa);	
			    SetAdoFields('Carga',$tipo_ve);	
			    SetAdoFields('Conductor',$op_vehi);	
			    SetAdoFields('Codigo_Inv',$codigo);	
			    SetAdoUpdate();		
			}
		}

		foreach ($gavetas as $key => $value) {
			if($value!='')
			{
				$codigo_inv = str_replace("_",'.', str_replace('txt_','', $key));
				SetAdoAddNew('Trans_Kardex');
				SetAdoFields('Codigo_Inv',$codigo_inv);
				SetAdoFields('TP','CD');
				SetAdoFields('T','N');
				SetAdoFields('Entrada',$value);
				SetAdoFields('Orden_No',$codigo);
				SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);
				SetAdoFields('Item',$_SESSION['INGRESO']['item']);
				SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU']);
				SetAdoUpdate();
			}
		}


		return 1;
	}

	function guardar2($parametros)
	{
		// print_r($parametros);die();
		SetAdoAddNew('Trans_Correos');
		SetAdoFields('T','P');		
		SetAdoFields('Llamadas',$parametros['txt_comentario2']);
		if($parametros['cbx_evaluacion']=='V')
		{			
			SetAdoFields('CI',1);
		}else
		{
			SetAdoFields('CI',0);
		}
		if(isset($parametros['rbl_recibido']))
		{
			SetAdoFields('C',1);
		}else
		{
			SetAdoFields('C',0);
		}
		SetAdoFields('SucIng',$parametros['cbx_evaluacion']);
		SetAdoFields('Cod_B',$parametros['ddl_sucursales']);
		SetAdoFieldsWhere('ID',$parametros['txt_id']);
		return SetAdoUpdateGeneric();

	}
	function cta_procesos($query)
	{
		$datos = $this->modelo->cta_procesos($query);
		$bene = array();
		foreach ($datos as $key => $value) {
			$bene[] = array('id'=>$value['TP'],'text'=>$value['Proceso'],'picture'=>$value['Picture']);
			// $bene[] = array('id'=>$value['id'].'-'.$value['email'],'text'=>$value['nombre']);//para produccion
		}
		return $bene;

	}

	function detalle_ingreso($query)
	{
		$datos = $this->modelo->detalle_ingreso();
		// $bene = array();
		// foreach ($datos as $key => $value) {
		// 	$bene[] = array('id'=>$value['ID'],'text'=>$value['Cliente']);
		// 	// $bene[] = array('id'=>$value['id'].'-'.$value['email'],'text'=>$value['nombre']);//para produccion
		// }
		return $datos;
	}
	function detalle_ingreso2($query)
	{
		$datos = $this->modelo->detalle_ingreso(false,$query);
		$bene = array();
		foreach ($datos as $key => $value) {
			$bene[] = array('id'=>$value['Codigo'],'text'=>$value['Cliente'],'data'=>$datos);
		}
		return $bene;
	}
	function datos_ingreso($cod)
	{
		// print_r($id);die();
		$datos = $this->modelo->detalle_ingreso($cod);
		return $datos[0];
	}
	function buscar($cod)
	{
		$datos = $this->modelo->buscar_transCorreos($cod);
		$result = array();
		foreach ($datos as $key => $value) {
		 $result[] = array("id"=>$value['Envio_No'],"text"=>$value['Envio_No'],'data'=>$value);
		}
		return $result;
	}
	function buscar_contabilizado($cod)
	{
		$datos = $this->modelo->buscar_transCorreos_contabilizadios($cod);
		$result = array();
		foreach ($datos as $key => $value) {
		 $result[] = array("id"=>$value['Envio_No'],"text"=>$value['Envio_No'],'data'=>$value);
		}
		return $result;
	}


	function buscar_procesado($cod)
	{
		$datos = $this->modelo->buscar_transCorreos_procesados($cod);
		$result = array();
		foreach ($datos as $key => $value) {
		 $result[] = array("id"=>$value['Envio_No'],"text"=>$value['Envio_No'],'data'=>$value);
		}
		return $result;
	}

	function proveedores($query)
	{
		$datos = $this->modelo->buscar_transCorreos($query);
		if($datos!=-1)
		{
		     $prov = array();
		     foreach ($datos as $key => $value) {
			  // print_r($value);die();
			  $prov[] = array('id'=>$value['ID'],'text'=>$value['Envio_No']);
		     }
		     return $prov;
		 }else
		 {
		 	return -1;
		 }
	}
	function guardar_recibido($parametro)
	{
		
		// print_r($parametro);die();
	   // SetAdoAddNew("Trans_Kardex");

		// print_r($parametro);die();


	   $num_ped = $parametro['txt_codigo']; 	
	   $producto = $this->modelo->catalogo_productos($parametro['txt_referencia']);
	   if($producto[0]['TDP']=='R')
	   {
	   	 $prod = $this->modelo->existe_en_transKarder($parametro['txt_codigo'],$producto[0]['Codigo_Inv']);
	   	 if(count($prod)>0)
	   	 {
	   	 	 return  $respuesta = array('ped'=>$num_ped,'resp'=>1,'total_add'=>'1');		
	   	 }
	   }
	   $fecha = date('Y-m-d');
	   $numeracion = $this->modelo->numeracion_dia_categoria($fecha,$producto[0]['Item_Banco']);
	   // print_r($numeracion);die();
	   $num = 1;
	   if(count($numeracion)>0){
	   		$num = substr($numeracion[0]['Codigo_Barra'],strlen($numeracion[0]['Codigo_Barra'])-3,strlen($numeracion[0]['Codigo_Barra']));
	   		$num = intval($num)+1;
	   	}

	   	// print_r($num);die();
	   $referencia = $parametro['txt_referencia'];
	   SetAdoAddNew("Trans_Kardex"); 		
	   SetAdoFields('Codigo_Inv',$referencia);
	   SetAdoFields('Producto',$producto[0]['Producto']);
	   SetAdoFields('UNIDAD',$producto[0]['Unidad']); /**/
	   SetAdoFields('Entrada',$parametro['txt_cantidad']);
	   SetAdoFields('Cta_Inv',$producto[0]['Cta_Inventario']);
	   SetAdoFields('Fecha_Fab',$parametro['txt_fecha_cla']);	
	   SetAdoFields('Fecha_Exp',$parametro['txt_fecha_exp']);	   
	   SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU']);   
	   SetAdoFields('Item',$_SESSION['INGRESO']['item']);
	   SetAdoFields('Orden_No',$num_ped); 
	   SetAdoFields('A_No',$parametro['A_No']+1);
	   SetAdoFields('Fecha',date('Y-m-d',strtotime($parametro['txt_fecha'])));
	   SetAdoFields('Valor_Total',number_format($producto[0]['PVP']*$parametro['txt_cantidad'],2,'.',''));
	   SetAdoFields('CANTIDAD',$parametro['txt_cantidad']);
	   SetAdoFields('Valor_Unitario',number_format($producto[0]['PVP'],$_SESSION['INGRESO']['Dec_PVP'],'.',''));
	   // SetAdoFields('DH',2);
	   SetAdoFields('Codigo_Barra',$parametro['txt_codigo'].'-'.$producto[0]['Item_Banco'].'-'.generaCeros($num,3));
	   SetAdoFields('CodBodega',-1);

	   SetAdoFields('Cta_Inv',$parametro['txt_cta_inv']);
	   SetAdoFields('Contra_Cta',$parametro['txt_contra_cta']);
	   SetAdoFields('Codigo_P',$parametro['txt_codigo_p']);
	   SetAdoFields('Codigo_Dr',$parametro['ddl_sucursales']);
	   SetAdoFields('Tipo_Empaque',$parametro['txt_paquetes']);
	   $resp = SetAdoUpdate();


	   SetAdoAddNew("Trans_Correos"); 		
	   SetAdoFields('T','C');
	   SetAdoFieldsWhere('ID',$parametro['txt_id']);
	   SetAdoUpdateGeneric();

	   return  $respuesta = array('ped'=>$num_ped,'resp'=>$resp,'total_add'=>'1');		
	}

	function actualizar_trans_kardex($parametro)
	{
		// print_r($parametro);die();
		$num_ped = $parametro['txt_codigo'];
		if($parametro['id']=='')
		{
			$lines_kardex = $this->modelo->cargar_pedidos_trans($num_ped,false,$parametro['producto']);
			$parametro['id'] = $lines_kardex[0]['ID'];
		}
			// if(count($lines_kardex)>0)
			// {
				SetAdoAddNew('Trans_Kardex');
				SetAdoFields('Entrada',number_format($parametro['total_cantidad'],2,'.',''));		
				SetAdoFieldsWhere('ID',$parametro['id']);
				SetAdoUpdateGeneric();
			// }
			return 1;
	}


	function guardar_pedido($parametro)
	{
			$num_ped = $parametro['txt_codigo'];
			$producto2 = $this->modelo->catalogo_productos($parametro['producto_pedido']);
			$referencia = $parametro['producto_pedido'];

			//actualiza en trans_correos el identificador de pedido
			SetAdoAddNew('Trans_Correos');
			SetAdoFields('Giro_No','R');		
			SetAdoFieldsWhere('ID',$parametro['txt_id']);
			SetAdoUpdateGeneric();

			//ingresamos las lineas en trasn ´pedidos			
		   SetAdoAddNew("Trans_Pedidos"); 		
		   SetAdoFields('Codigo_Inv',$producto2[0]['Codigo_Inv']);
		   SetAdoFields('Producto',$producto2[0]['Producto']);
		   SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU']);   
		   SetAdoFields('Item',$_SESSION['INGRESO']['item']);  
		   SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);
		   SetAdoFields('Orden_No',$num_ped); 
		   SetAdoFields('Fecha',$parametro['txt_fecha']);
		   SetAdoFields('Total',0);
		   SetAdoFields('Cantidad',$parametro['cantidad_pedido']);
		   SetAdoFields('Precio',0);
		   SetAdoFields('Cta_Inv',$producto2[0]['Cta_Inventario']);
		   SetAdoFields('CodigoC',$parametro['txt_codigo_p']);
		   return SetAdoUpdate();
	}

	function cargar_productos($parametros)
    {
    	// print_r($parametros);die();
    	// print_r($ordenes);die();
    	$datos = $this->modelo->cargar_pedidos_trans($parametros['num_ped'],false);

		// print_r($datos);die();
		$num = count($datos);

		$tr = '';
		$iva = 0;$subtotal=0;$total=0;
		$negativos = false;
		$procedimiento = '';
		$cabecera = '';
		$reciclaje = 0;
        $pie = ' 
        </tbody>
      </table>';
      $d='';
      $canti = 0;
      $canti2 = 0;
      $primeravez = 0;
		foreach ($datos as $key => $value) 
		{
			// print_r($value);die();
			$sucursal = '.';
			if($value['Codigo_Dr']!='.')
			{
				$dato_sucursal = $this->modelo->sucursales($query = false,$codigo=false,$value['Codigo_Dr']);
				// print_r($dato_sucursal);die();
				$sucursal = $dato_sucursal[0]['Direccion']; 
			} 

			$prod = $this->modelo->catalogo_productos($value['Codigo_Inv']);
			$art = $prod[0]['TDP'];

      		$canti = $canti+$value['Entrada'];      

			$iva+=number_format($value['Total_IVA'],2);
			// print_r($value['VALOR_UNIT']);
			$sub = $value['Valor_Unitario']*$value['Entrada'];
			$subtotal+=$sub;
			$procedimiento=$value['Detalle'];

			$total+=$value['Valor_Total'];

			$FechaInventario = $value['Fecha']->format('Y-m-d');
 	 		$CodBodega = '01';
			$costo_existencias =  Leer_Codigo_Inv($value['Codigo_Inv'],$FechaInventario,$CodBodega,$CodMarca='');
		
			if($costo_existencias['respueta']!=1){$costo_existencias['datos']['Stock'] = 0; $costo_existencias['datos']['Costo'] = 0;}
			else{
				$exis = number_format($costo_existencias['datos']['Stock']-$value['Entrada'],2);
				if($exis<0)
				{
					$nega = $exis;
					$negativos = true;
				}
			}
			$nega = 0;			
			

			if($d=='')
			{
			$d =  dimenciones_tabl(strlen($value['ID']));
			$d1 =  dimenciones_tabl(strlen($value['Fecha_Exp']->format('Y-m-d')));
			$d2 =  dimenciones_tabl(strlen($value['Fecha_Fab']->format('Y-m-d')));
			$d3 =  dimenciones_tabl(strlen($value['Producto']));
			$d4 =  dimenciones_tabl(strlen($value['Entrada']));
		  }
			$tr.='<tr>
  					<td width="'.$d.'">'.($key+1).'</td>
  					<td width="'.$d1.'">'.$value['Fecha_Fab']->format('Y-m-d').'</td>
  					<td width="'.$d2.'">'.$value['Fecha_Exp']->format('Y-m-d').'</td>
  					<td width="'.$d3.'">'.$value['Producto'].'</td>
  					<td width="'.$d4.'">'.number_format($value['Entrada'],2,'.','').' '.$value['Unidad'].'</td>
  					<td width="'.$d4.'">'.$value['Nombre_Completo'].'</td>
  					<td width="'.$d4.'">'.$value['Codigo_Barra'].'</td>
  					<td width="'.$d4.'">'.$sucursal.'</td>
  					<td>';
  					if($art!='.')
  					{
  						$tr.='<button class="btn btn-xs btn-primary" title="Agregar a '.$value['Producto'].'"  onclick=" show_producto2(\''.$value['ID'].'\')" ><i class=" fa fa-list"></i></button>';
  						$primeravez = 1;
  						$canti2 = $canti2+$value['Entrada'];
  					}

  					$tr.='<button class="btn btn-xs btn-danger" title="Eliminar linea"  onclick="eliminar_lin(\''.$value['ID'].'\',\''.$art.'\')" ><span class="glyphicon glyphicon-trash"></span></button>
  					</td>
  				</tr>';
			
		}
		$tr.='<tr>
  				<td colspan="4"><b>TOTALES</b></td>	
  				<td>'.$canti.'</td>	
  				<td></td>		
  			</tr>';

		if($num!=0)
		{
			// print_r($tr);die();
			$tabla = array('num_lin'=>$num,'tabla'=>$tr,'item'=>$num,'cant_total'=>$canti,'reciclaje'=>$canti2,'primera_vez'=>$primeravez);	
			return $tabla;		
		}else
		{
			$tabla = array('num_lin'=>0,'tabla'=>'<tr><td colspan="9" class="text-center"><b><i>Sin registros...<i></b></td></tr>','item'=>0,'cant_total'=>0,'reciclaje'=>0);
			return $tabla;		
		}		
    }

    function cargar_productos_trans_pedidos_datos($parametros)
    {
    	$datos = $this->modelo->cargar_pedidos_trans_pedidos($parametros['num_ped'],false);
    	return $datos;
    }

    function cargar_productos_trans_pedidos($parametros)
    {
    	// print_r($parametros);die();
    	// print_r($ordenes);die();
    	$datos = $this->modelo->cargar_pedidos_trans_pedidos($parametros['num_ped'],false);

		// print_r($datos);die();
		$num = count($datos);

		$tr = '';
		$iva = 0;$subtotal=0;$total=0;
		$negativos = false;
		$procedimiento = '';
		$cabecera = '';
		$reciclaje = 0;
        $pie = ' 
        </tbody>
      </table>';
      $d='';
      $canti = 0;
		foreach ($datos as $key => $value) 
		{
			// print_r($value);die();

      		$canti = $canti+$value['Cantidad'];
			$iva+=number_format($value['Total_IVA'],2);
			// print_r($value['VALOR_UNIT']);
			$sub = $value['Precio']*$value['Cantidad'];
			$subtotal+=$sub;
			
			$total+=$value['Total'];
		
			

			if($d=='')
			{
			$d =  dimenciones_tabl(strlen($value['ID']));
			$d1 =  dimenciones_tabl(strlen($value['Fecha']->format('Y-m-d')));
			$d2 =  dimenciones_tabl(strlen($value['Fecha']->format('Y-m-d')));
			$d3 =  dimenciones_tabl(strlen($value['Producto']));
			$d4 =  dimenciones_tabl(strlen($value['Cantidad']));
		  }
			$tr.='<tr>
  					<td width="'.$d.'">'.($key+1).'</td>
  					<td width="'.$d3.'">'.$value['Producto'].'</td>
  					<td width="'.$d4.'">'.number_format($value['Cantidad'],2,'.','').' '.$value['UNIDAD'].'</td>
  					<td width="90px">
  					<!--	<button class="btn btn-sm btn-primary" onclick="editar_lin(\''.$value['ID'].'\')" title="Editar linea"><span class="glyphicon glyphicon-floppy-disk"></span></button> -->
  						<button class="btn btn-sm btn-danger" title="Eliminar linea"  onclick="eliminar_lin_pedido(\''.$value['ID'].'\')" ><span class="glyphicon glyphicon-trash"></span></button>
  					</td>
  				</tr>';
			
		}
		$tr.='<tr>
  				<td colspan="2"><b>TOTALES</b></td>	
  				<td>'.$canti.'</td>	
  				<td></td>		
  			</tr>';

		if($num!=0)
		{
			// print_r($tr);die();
			$tabla = array('num_lin'=>$num,'tabla'=>$tr,'item'=>$num,'cant_total'=>$canti);	
			return $tabla;		
		}else
		{
			$tabla = array('num_lin'=>0,'tabla'=>'<tr><td colspan="9" class="text-center"><b><i>Sin registros...<i></b></td></tr>','item'=>0,'cant_total'=>0);
			return $tabla;		
		}		
    }


    function cargar_productos_checking($parametros)
    {
    	// print_r($parametros);die();
    	// print_r($ordenes);die();
    	$datos = $this->modelo->cargar_pedidos_trans($parametros['num_ped'],false);
    	// $pedido = $this->modelo->

		// print_r($datos);die();
		$num = count($datos);

		$tr = '';
		$iva = 0;$subtotal=0;$total=0;
		$negativos = false;
		$procedimiento = '';
		$cabecera = '';
        $pie = ' 
        </tbody>
      </table>';
      $d='';
      $canti = 0;
      $PVP = 0;      
      $total = 0;
      $reciclaje = 0;
		foreach ($datos as $key => $value) 
		{
			// print_r($datos);die();


      		$canti = $canti+$value['Entrada'];	
      		$PVP = $PVP+$value['Valor_Unitario'];	
      		$total = $total+$value['Valor_Total'];	

			if($d=='')
			{
			$d =  dimenciones_tabl(strlen($value['ID']));
			$d1 =  dimenciones_tabl(strlen($value['Fecha_Exp']->format('Y-m-d')));
			$d2 =  dimenciones_tabl(strlen($value['Fecha_Fab']->format('Y-m-d')));
			$d3 =  dimenciones_tabl(strlen($value['Producto']));
			$d4 =  dimenciones_tabl(strlen($value['Entrada']));
		  }
			$tr.='<tr>
  					<td width="'.$d.'">'.($key+1).'</td>
  					<td width="'.$d1.'">'.$value['Fecha_Exp']->format('Y-m-d').'</td>
  					<td width="'.$d2.'">'.$value['Fecha_Fab']->format('Y-m-d').'</td>
  					<td width="'.$d3.'">'.$value['Producto'].'</td>
  					<td width="'.$d4.'" id="txt_cant_ped_'.$value['ID'].'">'.number_format($value['Entrada'],2,'.','').' '.$value['Unidad'].'</td>
  					<td width="'.$d4.'"><input class="form-control"  id="txt_pvp_linea_'.$value['ID'].'" name="txt_pvp_linea_'.$value['ID'].'" onblur="recalcular('.$value['ID'].')" input-sm" value="'.$value['Valor_Unitario'].'"></td>
  					<td width="'.$d4.'"><input class="form-control" id="txt_total_linea_'.$value['ID'].'" name="txt_total_linea_'.$value['ID'].'"  input-sm" value="'.$value['Valor_Total'].'" readonly></td>

  					<td>	  					
						'.$value['Nombre_Completo'].'<br>
						<small class="label label-danger" onclick="abrir_modal_notificar(\''.$value['CodigoU'].'\')" title="Notificar"><i class="fa fa-commenting"></i></small>
						<!--- <small class="label label-danger" onclick="abrir_modal_notificar(\''.$value['CodigoU'].'\')"><i class="fa fa-exclamation-triangle"></i></small>
						<small class="label label-danger" onclick="abrir_modal_notificar(\''.$value['CodigoU'].'\')"><i class="fa fa-commenting"></i></small>
						<small class="label label-danger" onclick="abrir_modal_notificar(\''.$value['CodigoU'].'\')"><i class="fa fa-commenting"></i></small>
						 --->
  					 </td>
  					<td width="90px">';
  					if($value['T']=='C')
  					{
  					  $tr.='<input type="checkbox" class="rbl_conta" name="rbl_conta" id="rbl_conta_'.$value['ID'].'" value="'.$value['ID'].'" checked  />';
  					}else
  					{
  						$tr.='<input type="checkbox" class="rbl_conta" onclick="guardar_check()" name="rbl_conta" id="rbl_conta_'.$value['ID'].'" value="'.$value['ID'].'" />';
  					}
  					$tr.='</td>
  					<td>
  						<button class="btn btn-sm btn-primary" onclick="editar_precio('.$value['ID'].');guardar_check()"><i class="fa fa-save"></i></button>';
  						if($value['TDP']=='R')
  						{
  							$tr.='<button class="btn btn-sm btn-warning" onclick="cargar_tras_pedidos(\''.$value['Producto'].'\',\''.$parametros['num_ped'].'\')"><i class="fa fa-list"></i></button>';
  						}
  					$tr.='</td>
  				</tr>';
			
		}
		$tr.='<tr>
  				<td colspan="4"><b>TOTALES</b></td>	
  				<td>'.$canti.'</td>	
  				<td>'.$PVP.'</td>	
  				<td>'.$total.'</td>	
  				<td></td>		
  			</tr>';

		if($num!=0)
		{
			// print_r($tr);die();
			$tabla = array('num_lin'=>$num,'tabla'=>$tr,'item'=>$num,'cant_total'=>$canti);	
			return $tabla;		
		}else
		{
			$tabla = array('num_lin'=>0,'tabla'=>'<tr><td colspan="9" class="text-center"><b><i>Sin registros...<i></b></td></tr>','item'=>0,'cant_total'=>0);
			return $tabla;		
		}		
    }


   function lineas_eli($parametros)
	{
		// print_r($parametros);die();
		$resp = $this->modelo->lineas_eli($parametros);
		// if($parametros['TPD']==1)
		// {
		// 	SetAdoAddNew('Trans_Correos');	
		// SetAdoFields('Giro_No','.');
		// SetAdoFieldsWhere('Envio_No',$id);
		// SetAdoUpdateGeneric();

		// }
		return $resp;

	}
	function lineas_eli_pedido($parametros)
	{
		// print_r($parametros);die();
		$resp = $this->modelo->lineas_eli_pedido($parametros);
		return $resp;

	}
	function autocomplet_producto($query)
	{
		$datos = $this->modelo->cargar_productos($query);
		// print_r($datos);die();
		$productos = array();
		foreach ($datos as $key => $value) {			
			$Familia = $this->modelo->familia_pro(substr($value['Codigo_Inv'],0,5));
			$productos[] = array('id'=>$Familia[0]['Codigo_Inv'],'text'=>$value['Producto'],'data'=>$Familia);

		}
		return $productos;
		// print_r($productos);die();
	}
	function autocomplet_producto2($query)
	{
		$datos = $this->modelo->cargar_productos2($query);
		// print_r($datos);die();
		$productos = array();
		foreach ($datos as $key => $value) {			
			$Familia = $this->modelo->familia_pro2(substr($value['Codigo_Inv'],0,5));
			$productos[] = array('id'=>$Familia[0]['Codigo_Inv'],'text'=>$value['Producto'],'data'=>$Familia);

		}
		return $productos;
		// print_r($productos);die();
	}

	function producto_costo($parametros)
	{
		$query = $parametros['cta_inv'];
		$productos  = Leer_Codigo_Inv($query,date('Y-m-d'));
		return $productos['datos'];
		// print_r($productos);die();
	}

	function cargar_datos($parametros)
	{
		$query = $parametros['query'];
		$fecha = $parametros['fecha'];
		$fechah = $parametros['fechah'];

		$datos = $this->modelo->buscar_transCorreos_ingresos($query,$fecha,$fechah);
		// print_r($datos);die();
		$tr= '';
		foreach ($datos as $key => $value) {
			// print_r($value);die();
			$noti = $this->modelo->listar_notificaciones($_SESSION['INGRESO']['CodigoU'],'P',false,$value['Envio_No']);
			$alerta = '';
			if(count($noti)>0)
			{
				$alerta = '<div class="btn-group pull-right">
						<button type="button" class="label label-warning btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
							<i class="fa fa-commenting"></i>
						</button>
						<ul class="dropdown-menu">';
						foreach ($noti as $key2 => $value2) {
							$texto2 = str_replace(array("\r", "\n"), '', $value2['Texto_Memo']);
							$alerta.='<li><a href="#" onclick="mostrar_notificacion(\''.$texto2.'\',\''.$value2['ID'].'\',\''.$value['Envio_No'].'\')">Notificacion'.($key2+1).'</a></li>';
						}
						$alerta.='</ul>
					</div>	';
			}
			// print_r($value);
			// print_r($noti);die();
			$tr.='<tr>

					<td>'.($key+1).'</td>
					<td>'.$value['Envio_No'].'</td>
					<td>'.$value['Fecha_P']->format('Y-m-d').'</td>
					<td>'.$value['Cliente'].' 
					'.$alerta.'						
					</td>
					<td>'.$value['Proceso'].'</td>
					<td>'.number_format($value['TOTAL'],2,'.','').'</td>
					<td>'.$value['Porc_C'].'</td>
					<td>
					<button type="button" class="btn-sm btn-primary btn" onclick="editar_pedido(\''.$value['ID'].'\')"><i class="fa fa-pencil"></i></button>
					<button type="button" class="btn-sm btn-danger btn" onclick="eliminar_pedido(\''.$value['ID'].'\')"><i class="fa fa-trash"></i></button></td>

				  </tr>';
		}

		return $tr;
		// print_r($datos);die();
	}

	function cargar_datos_procesados($parametros)
	{
		$query = $parametros['query'];
		$fecha = $parametros['fecha'];
		$fechah = $parametros['fechah'];

		$datos = $this->modelo->buscar_transCorreos_procesados_all($query,$fecha,$fechah);
		$tr= '';
		foreach ($datos as $key => $value) {
			$proceso = 'Clasificacion';
			if($value['T']=='P'){$proceso = 'Checking';}
			if($value['T']=='R' || $value['T']=='I'){$proceso = 'Ingreso';}
			if($value['T']=='C'){$proceso = 'Clasificacion';}
			if($value['T']=='N'){$proceso = 'Almacenamiento';}

			$noti = $this->modelo->listar_notificaciones($_SESSION['INGRESO']['CodigoU'],'P',false,$value['Envio_No']);
			$alerta = '';
			if(count($noti)>0)
			{
				$alerta = '<div class="btn-group pull-right">
						<button type="button" class="label label-warning btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
							<i class="fa fa-commenting"></i>
						</button>
						<ul class="dropdown-menu">';
						foreach ($noti as $key2 => $value2) {
							$texto2 = str_replace(array("\r", "\n"), '', $value2['Texto_Memo']);
							$alerta.='<li><a href="#" onclick="mostrar_notificacion(\''.$texto2.'\',\''.$value2['ID'].'\',\''.$value['Envio_No'].'\')">Notificacion'.($key2+1).'</a></li>';
						}
						$alerta.='</ul>
					</div>	';
			}

			$tr.='<tr>			
					<td>'.($key+1).'</td>
					<td>'.$value['Envio_No'].'</td>
					<td>'.$value['Fecha_P']->format('Y-m-d').'</td>
					<td>'.$value['Cliente'].''.$alerta.'</td>
					<td>'.$value['Proceso'].'</td>
					<td>'.number_format($value['TOTAL'],2,'.','').'</td>
					<td>'.$value['Porc_C'].'</td>
					<td>'.$proceso.'</td>
					<td><button type="button" class="btn-sm btn-primary btn" onclick="editar_pedido(\''.$value['ID'].'\')"><i class="fa fa-pencil"></i></button></td>

				  </tr>';
		}

		return $tr;
		// print_r($datos);die();
	}


	function eliminar_pedido($data)
	{
		$id = $data['ID'];
		return $this->modelo->eliminar_pedido($id);
	} 
	function eli_all_pedido($data)
	{
		$id = $data['pedido'];
		SetAdoAddNew('Trans_Correos');	
		SetAdoFields('Giro_No','.');
		SetAdoFieldsWhere('Envio_No',$id);
		SetAdoUpdateGeneric();

		return $this->modelo->eli_all_pedido($id);
	} 
	function editar_precio($parametros)
	{
		// print_r($parametros);die();
		SetAdoAddNew('Trans_Kardex');
		SetAdoFields('Valor_Unitario',$parametros['pvp']);		
		SetAdoFields('Valor_Total',$parametros['total']);
		SetAdoFieldsWhere('ID',$parametros['id']);
		return SetAdoUpdateGeneric();

	}


	function editar_pedido($parametros)
	{
		// print_r($parametros);die();
		$cliente = $this->modelo->clientes(false,$parametros['ddl_ingreso_edi'],false);

		$codigo = explode('-', $parametros['txt_codigo_edi']);
		$new_cod = $cliente[0]['Cod_Ejec'];
		foreach ($codigo as $key => $value) {
			if($key!=0)
			{
				$new_cod.='-'.$value;
			}
		}

		$lineas_kardex = $this->modelo->cargar_pedidos_trans($parametros['txt_codigo_edi']);
		$lineas_pedidos = $this->modelo->cargar_pedidos_trans_pedidos($parametros['txt_codigo_edi']);

		foreach ($lineas_kardex as $key => $value) {

			$cod_barras = str_replace($parametros['txt_codigo_edi'],$new_cod,$value['Codigo_Barra']);

			SetAdoAddNew('Trans_Kardex');
			SetAdoFields('Codigo_Barra',$cod_barras);
			SetAdoFields('Orden_No',$new_cod);
			SetAdoFieldsWhere('ID',$value['ID']);
			SetAdoUpdateGeneric();
		
		}

		foreach ($lineas_pedidos as $key => $value) {
			SetAdoAddNew('Trans_Pedidos');
			SetAdoFields('Orden_No',$new_cod);
			SetAdoFieldsWhere('ID',$value['ID']);
			SetAdoUpdateGeneric();
		}


		SetAdoAddNew('Trans_Correos');
		SetAdoFields('CodigoP',$parametros['ddl_ingreso_edi']);
		SetAdoFields('Cod_C',$parametros['ddl_tipo_alimento_edi']);
		SetAdoFields('Porc_C',$parametros['txt_temperatura_edi']);
		SetAdoFields('TOTAL',$parametros['txt_cant_edi']);

		SetAdoFields('Envio_No',$new_cod);
		SetAdoFieldsWhere('ID',$parametros['txt_id_edi']);
		return SetAdoUpdateGeneric();

	}


	function editar_checked($parametros)
	{
		// print_r($parametros);die();
		$op =  substr($parametros['check'],0,-1);
		$op = explode(',', $op);

		$no_op =  substr($parametros['no_check'],0,-1);
		$no_op = explode(',', $no_op);



		foreach ($op as $key => $value) {
			
			SetAdoAddNew('Trans_Kardex');
			SetAdoFields('T','C');		
			SetAdoFieldsWhere('ID',$value);
			SetAdoUpdateGeneric();

		}
		foreach ($no_op as $key => $value) {
			
			SetAdoAddNew('Trans_Kardex');
			SetAdoFields('T','.');		
			SetAdoFieldsWhere('ID',$value);
			SetAdoUpdateGeneric();

		}

		return 1;

		// SetAdoAddNew('Trans_Kardex');
		// SetAdoFields('T','C');		
		// SetAdoFieldsWhere('ID',$parametros['id']);
		// return SetAdoUpdateGeneric();

	}

	function contabilizar($parametros)
	{
		SetAdoAddNew('Trans_Correos');
		SetAdoFields('T','N');		
		SetAdoFieldsWhere('ID',$parametros['txt_id']);
		return SetAdoUpdateGeneric();
	}

	function lista_bodegas_arbol($parametros)
	{
		// print_r($parametros);die();
		$nivel_solicitado = $parametros['nivel'];
		$padre = str_replace('_','.',$parametros['padre']);
		$datos = $this->modelo->bodegas();

		// analiza cuantos niveles tiene
		$niveles = 0;
		foreach ($datos as $key => $value) {
			$niv = explode('.', $value['CodBod']);
			if(count($niv)>$niveles)
			{
				$niveles = count($niv);
			}
		}
		
		//separa los niveles en grupos
		$grupo_nivel = array();
		for ($i=1; $i <= $niveles ; $i++) { 
			$grupo_nivel[$i] = array();
			foreach ($datos as $key => $value) {
				$niv = explode('.', $value['CodBod']);
				if(count($niv)==$i)
				{
					array_push($grupo_nivel[$i], $value);
				}
			}
		}

		// print_r($grupo_nivel[4]);die();


		$hijos = 0;
		$html = '';
		foreach ($grupo_nivel[$nivel_solicitado] as $key => $value) {
			if($padre=='')
			{
				$prefijo = $value['CodBod'];
				foreach ($grupo_nivel[$nivel_solicitado+1] as $key2 => $value2) {
					if (substr($value2['CodBod'], 0, strlen($prefijo)) === $prefijo) {
						$hijos = 1;
						break;
					} 
				}
				if($hijos==1)
				{
					$html.='<li>
						       <input type="checkbox" id="c'.$prefijo.'" />
						       <label class="tree_bod_label" for="c'.$prefijo.'" onclick="cargar_bodegas(\''.($nivel_solicitado+1).'\',\''.$prefijo.'\')">'.$value['Bodega'].'</label>
						       	<ul id="h'.$prefijo.'">
						       	</ul>
					       	</li>';
					$hijos=0;
				}else
				{
					$html.='<li><span class="tree_bod_label" onclick="alert(\'2222\')">'.$value['Bodega'].'</span></li>';
				}
			}else{
				//cuando viene con padre
					$prefijo = $value['CodBod'];
					if(isset($grupo_nivel[$nivel_solicitado+1]))
					{

						foreach ($grupo_nivel[$nivel_solicitado+1] as $key2 => $value2) {
							if (substr($value2['CodBod'], 0, strlen($padre)) === $padre) {
								$hijos = 1;
								break;
							} 
						}
					}
					
					if (substr($value['CodBod'], 0, strlen($padre)) === $padre) {
						// print_r('padre');die();
					if($hijos==1)
					{
						// print_r($value2['CodBod'].'-'.$value['Bodega']);die();
						$html.='<li>
							       <input type="checkbox" id="c'.str_replace('.','_',$prefijo).'" />
							       <label class="tree_bod_label" for="c'.str_replace('.','_',$prefijo).'" onclick="cargar_bodegas(\''.($nivel_solicitado+1).'\',\''.str_replace('.','_',$prefijo).'\')">'.$value['Bodega'].'</label>
							       	<ul id="h'.str_replace('.','_',$prefijo).'">
							       	</ul>
						       	</li>';
						$hijos=0;
					}else
					{
						$html.='<li><span class="tree_bod_label" onclick="alert(\'2222\')">'.$value['Bodega'].'</span></li>';
					}
				}
				

			}
			
		}

		return $html;

	}

	function autoincrementable($parametros)
	{
		$fecha = $parametros['fecha'];
		$datos = $this->modelo->autoincrementable($fecha);
		return ($datos[0]['cant']+1);
	}


	function datos_pedido_edi($parametros)
	{
		$datos = $this->modelo->buscar_transCorreos_all(false,false,$parametros['ID']);
		if(count($datos)>0)
		{
			$ingresados = $this->modelo->cargar_pedidos_trans($datos[0]['Envio_No'],false,false);
			$cant_ing = 0;
			foreach ($ingresados as $key => $value) {
				$cant_ing+=$value['Entrada'];
			}
			$datos[0]['ingresados'] = $cant_ing;
		}
		return $datos;
	}

	function notificar_clasificacion($parametros)
	{
		// print_r($parametros);die();

		// print_r($_SESSION['INGRESO']);die();
			$pedido = $this->modelo->buscar_transCorreos_all(false,false,$parametros['id']);
			if(isset($parametros['encargado']) && $parametros['encargado']!='')
				{ $pedido[0]['CodigoU'] = $parametros['encargado']; }

		   SetAdoAddNew("Trans_Memos"); 		
		   SetAdoFields('T','P');		   		
		   SetAdoFields('Asunto','De:'.$this->Nombre_proceso($parametros['de_proceso']).' a '.$this->Nombre_proceso($parametros['pa_proceso']));
		   SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU']);   
		   SetAdoFields('Item',$_SESSION['INGRESO']['item']);  
		   SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);
		   SetAdoFields('Codigo',$pedido[0]['CodigoU']); 
		   SetAdoFields('Texto_Memo',$parametros['notificar']);
		   SetAdoFields('Codigo',$pedido[0]['CodigoU']); 
		   SetAdoFields('Atencion',$parametros['pedido']);

		   SetAdoFields('CC1',$parametros['de_proceso']);
		   SetAdoFields('CC2',$parametros['pa_proceso']);
		   return SetAdoUpdate();		   
		   
	}

	function notificar_egresos($parametros)
	{
		// print_r($parametros);die();
		// print_r($_SESSION['INGRESO']);die();
		$pedido = $this->modelo->cargar_motivo_lista($query=false,$id=false,$parametros['pedido']);

		// print_r($pedido);die();

		   SetAdoAddNew("Trans_Memos"); 		
		   SetAdoFields('T','P');		   		
		   SetAdoFields('Asunto','De:'.$this->Nombre_proceso($parametros['de_proceso']).' a '.$this->Nombre_proceso($parametros['pa_proceso']));
		   SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU']);   
		   SetAdoFields('Item',$_SESSION['INGRESO']['item']);  
		   SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);
		   SetAdoFields('Codigo',$pedido[0]['CodigoU']); 
		   SetAdoFields('Texto_Memo',$parametros['notificar']);
		   SetAdoFields('Codigo',$pedido[0]['CodigoU']); 
		   SetAdoFields('Atencion',$parametros['pedido']);

		   SetAdoFields('CC1',$parametros['de_proceso']);
		   SetAdoFields('CC2',$parametros['pa_proceso']);
		   return SetAdoUpdate();		   
		   
	}

	function Nombre_proceso($num)
	{
		$nombre = '';
		switch ($num) {
			case '1':
				$nombre = 'Recepcion'; // code...
				break;
			case '2':
				$nombre = 'Clasificacion'; // code...
				break;
			case '3':
				$nombre = 'Checking'; // code...
				break;
			case '4':
				$nombre = 'Egresos'; // code...
				break;
			case '5':
				$nombre = 'Checking egreso'; // code...
				break;
			
		}
		return $nombre;
	}

	function comentar_clasificacion($parametros)
	{
		// print_r($parametros);die();
		return $this->editar_comentarios_trans_correos($parametros['id'],'Clasificacion',$parametros['notificar']);				   
	}

	function guardar_comentario_check($parametros)
	{
		// print_r($parametros);die();
		return $this->editar_comentarios_trans_correos($parametros['id'],'Checking',$parametros['notificar']);				   
	}


	function notificar_usuario($parametros)
	{
		// print_r($parametros);die();

		   SetAdoAddNew("Trans_Memos"); 		
		   SetAdoFields('T','P');		   		
		   SetAdoFields('Asunto','Resp:'.$this->Nombre_proceso($parametros['de_proceso']).' a '.$this->Nombre_proceso($parametros['pa_proceso']));
		   SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU']);   
		   SetAdoFields('Item',$_SESSION['INGRESO']['item']);  
		   SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);
		   SetAdoFields('Codigo',$parametros['usuario']); 
		   SetAdoFields('Texto_Memo',$parametros['notificar']);
		   SetAdoFields('Atencion',$parametros['pedido']);

		   SetAdoFields('CC1',$parametros['de_proceso']);
		   SetAdoFields('CC2',$parametros['pa_proceso']);

		   return SetAdoUpdate();
	}

	function listar_notificaciones()
	{
		// print_r($parametros);die();
		$noti = array();
		$notificacion = $this->modelo->listar_notificaciones($_SESSION['INGRESO']['CodigoU'],'P');
		foreach ($notificacion as $key => $value) {
			$noti[] = array('ID'=>$value['ID'],'Texto_Memo'=>preg_replace("[\n|\r|\n\r]",'<br>',$value['Texto_Memo']),'Asunto'=>$value['Asunto'],'Fecha'=>$value['Fecha'],'Pedido'=>$value['Atencion']);
		}

		// print_r($notificacion);die();
		return $noti;
	}

	function cambiar_estado($parametros)
	{
		$notificacion = $this->modelo->listar_notificaciones(false,false,$parametros['noti']);

		$parametros2 = array('asunto'=>'Respuesta Notificacion','notificar'=>$parametros['respuesta'],'usuario'=>$notificacion[0]['CodigoU'],'pedido'=>$notificacion[0]['Atencion'],'de_proceso'=>$notificacion[0]['CC2'],'pa_proceso'=>$notificacion[0]['CC1']);

		// print_r($parametros2);die();


 	    // SetAdoAddNew("Trans_Memos");	
		// SetAdoFields('T','N');
		// SetAdoFieldsWhere('ID',$parametros['noti']);
		// SetAdoUpdateGeneric();

		 return $this->notificar_usuario($parametros2);
	}

	function cambiar_estado_solucionado($parametros)
	{
		$notificacion = $this->modelo->listar_notificaciones(false,false,$parametros['noti']);


		// print_r($notificacion);die();
		if(count($notificacion)>0 && $notificacion[0]['CC2']!=3)
		{

			SetAdoAddNew("Trans_Correos");	
			SetAdoFields('T','R');
			if($notificacion[0]['CC1']==3){
				SetAdoFields('T','P');
			}else if($notificacion[0]['CC1']==2){ SetAdoFields('T','C'); }			
			SetAdoFieldsWhere('Envio_No',$notificacion[0]['Atencion']);

			// print('arg');die();
			SetAdoUpdateGeneric();
		}

		// print_r($notificacion);die();
 	    SetAdoAddNew("Trans_Memos");	
		SetAdoFields('T','N');
		SetAdoFieldsWhere('ID',$parametros['noti']);
		return SetAdoUpdateGeneric();
	}

	function cambiar_a_clasificacion($parametros)
	{
		// print_r($parametros);die();
 	    SetAdoAddNew("Trans_Correos");	
		SetAdoFields('T',$parametros['T']);
		SetAdoFieldsWhere('Envio_No',$parametros['pedido']);
		return SetAdoUpdateGeneric();
	}

	function estado_trasporte($parametros)
	{
		$codigo = $parametros['pedido'];
		return $this->modelo->estado_trasporte($codigo);
	}

	function editar_comentarios_trans_correos($pedido,$asunto,$texto)
	{
		SetAdoAddNew("Trans_Correos");	
		switch ($asunto) {
			case 'Recepcion':
				SetAdoFields('Mensaje',$texto);
				break;
			case 'Clasificacion':
				SetAdoFields('Llamadas',$texto);
				break;
			case 'Checking':
				SetAdoFields('Mensaje2',$texto);
				break;
		}
		SetAdoFieldsWhere('ID',$pedido);
	  return  SetAdoUpdateGeneric();
	}

	function preguntas_transporte()
	{
		$datos = $this->modelo->preguntas_transporte();
		$html='<li class="list-group-item">
					<a href="#" style="padding:0px">
							<label></label>
					 		<div class="btn-group pull-right">
					 			<span class="label-default btn-sm btn"><img src="../../img/png/bueno2.png" style="width: 14px;"></span>
					 			<span class="label-default btn-sm btn"><img src="../../img/png/close.png" style="width: 14px;"></span>
					 		</div>
				 	</a>
				</li>';
		foreach ($datos as $key => $value) {
			$html.='<li class="list-group-item">
						<a href="#" style="padding:0px">
								<label>'.$value['Proceso'].'</label>
						 		<div class="btn-group pull-right">
						 			<span class="label-success btn-sm btn"><input type="radio" class="rbl_opciones" name="'.$value['Cmds'].'_'.$value['TP'].'" id="'.$value['Cmds'].'_1" value="1"></span>
						 			<span class="label-danger btn-sm btn"><input type="radio" class="rbl_opciones"  name="'.$value['Cmds'].'_'.$value['TP'].'" id="'.$value['Cmds'].'_0" value="0"></span>
						 		</div>
					 	</a>
					</li>';
		}

		return $html;
	}


	function lista_bodegas_arbol2($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->bodegas();

		// analiza cuantos niveles tiene
		$niveles = 0;
		foreach ($datos as $key => $value) {
			$niv = explode('.', $value['CodBod']);
			if(count($niv)>$niveles)
			{
				$niveles = count($niv);
			}
		}

		//separa los niveles en grupos
		$grupo_nivel = array();
		for ($i=1; $i <= $niveles ; $i++) { 
			$grupo_nivel[$i] = array();
			foreach ($datos as $key => $value) {
				$niv = explode('.', $value['CodBod']);
				if(count($niv)==$i)
				{
					array_push($grupo_nivel[$i], $value);
				}
			}
		}

		//cracion del arbol desde el ultimo nivel hasta el primero
		$detalle = '';
		$grupo = '';
		for ($i=$niveles; $i >=1  ; $i--) { 
			foreach ($grupo_nivel[$i] as $key => $value) {

				//averiguo el nivel superior
				$niv = explode('.', $value['CodBod']);
				array_splice($niv, $niveles-1, 1);
				$nivel_grupo = '';
				foreach ($niv as $key2 => $value2) {
					$nivel_grupo.=$value2.'.';
				}
				$nivel_grupo = substr($nivel_grupo,0,-1);

				//agrego os detalles al grupo
				$grupo = '';
				foreach ($grupo_nivel[$i]  as $key3 => $value3) {
					if (strpos($value3['CodBod'], $nivel_grupo ) !== false) {
						$detalle.= '<li><span class="tree_bod_label">'.$value['Bodega'].'</span></li>';		
					}
				}

			}


		}

	}

	function gavetas()
	{
		$datos =  $this->modelo->gavetas();
		// print_r($datos);die();
		$tr = '';
		foreach ($datos as $key => $value) {
			$tr.='<tr><td>'.$value['Producto'].'</td><td><input type="text" id="txt_'.$value['Codigo_Inv'].'" name="txt_'.$value['Codigo_Inv'].'" class="form-control gavetas_ingreso"></td></tr>';
		}
		return $tr;
	}

	function placas_auto($tipo)
	{
		return $this->modelo->placas_auto($tipo);
	}


}

?>