<?php 
$_SESSION['INGRESO']['modulo_']='99';
include (dirname(__DIR__,2).'/modelo/farmacia/pacienteM.php');
include (dirname(__DIR__,2).'/modelo/farmacia/ingreso_descargosM.php');
include (dirname(__DIR__,2).'/modelo/farmacia/articulosM.php');
/**
 * 
 */
$controlador = new articulosC();
if(isset($_GET['productos']))
{
	$parametros= $_POST['parametros'];	
	echo json_encode($controlador->cargar_productos($parametros));
}
if(isset($_GET['search']))
{
	$query = $_POST['search'];
	echo json_encode($controlador->autocompletar($query));

}
if(isset($_GET['search_ruc']))
{
	$query = $_POST['search'];
	echo json_encode($controlador->autocompletar_busqueda_ruc($query));

}
if(isset($_GET['searchAbre']))
{
	$query = $_POST['search'];
	echo json_encode($controlador->autocompletarAbre($query));
}
if(isset($_GET['validar_abre']))
{
	$query = $_POST['parametros'];
	// print_r($query);die();
	echo json_encode($controlador->validar_abreviatura($query));

}
if(isset($_GET['familias']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->familias($query));
}
if(isset($_GET['familias2']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->familias2($query));
}

if(isset($_GET['cuenta']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->catalogo_cuenta($query));
}

if(isset($_GET['producto_nuevo']))
{
	$parametros = $_POST;
	echo json_encode($controlador->Ingresar_producto($parametros));
}

if(isset($_GET['proveedor_eliminar']))
{
	$parametros = $_POST;
	echo json_encode($controlador->eliminar_proveedor($parametros));
}

if(isset($_GET['proveedor_nuevo']))
{
	$parametros = $_POST;
	echo json_encode($controlador->Ingresar_proveedor($parametros));
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
if(isset($_GET['proveedores']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->proveedores($query));
}

if(isset($_GET['add_producto']))
{
	$parametros = $_POST;
	echo json_encode($controlador->agreagar_producto($parametros));
}

if(isset($_GET['lin_eli']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lineas_eli($parametros));
}

if(isset($_GET['generar_factura']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->generar_factura($parametros));
}

if(isset($_GET['buscar_ultimo']))
{
	$parametros = $_POST['cta'];
	echo json_encode($controlador->buscar_ultimo($parametros));
}

if(isset($_GET['cuenta_asignar']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	$cuenta = $_GET['cuenta_asignar'];
	echo json_encode($controlador->cuentas_asignar($cuenta,$query));
}

if(isset($_GET['eliminar_ingreso']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->eliminar_factura($parametros));
}

if(isset($_GET['Articulos_imagen']))
{
	$parametros = $_POST;
	echo json_encode($controlador->agregar_articulo_foto($_FILES,$_POST));
}

if(isset($_GET['num_com']))
{
	$fecha = $_POST['fecha'];
	echo json_encode(numero_comprobante1('Diario',true,false,$fecha));
}

if(isset($_GET['eliminar_articulos']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->eliminar_articulos($id));
}
if(isset($_GET['detalle_articulos']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->detalle_articulos($id));
}

if(isset($_GET['familia_new']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->familia_new($parametros));
}
if(isset($_GET['guardar_recibido']))
{
	$parametros = $_POST;
	echo json_encode($controlador->guardar_recibido($parametros));
}
if(isset($_GET['pedido']))
{
	$parametros = $_POST['parametros'];
		// print_r('ddd');die();
	echo json_encode($controlador->cargar_pedidos($parametros));
}


class articulosC 
{
	private $modelo;
	private $ing_descargos;
	private $paciente;
	function __construct()
	{
		$this->modelo = new articulosM();
		$this->ing_descargos = new ingreso_descargosM();
		$this->paciente = new pacienteM();
	}

	function autocomplet_producto($query)
	{
		$datos = $this->modelo->cargar_productos($query);
		// print_r($datos);die();
		$productos = array();
		foreach ($datos as $key => $value) {			
			$Familia = $this->modelo->familia_pro(substr($value['Codigo_Inv'],0,5));
			// $costo =  $this->ing_descargos->costo_venta($value['Codigo_Inv']);
			// $costoTrans = $this->ing_descargos->costo_producto($value['Codigo_Inv']);

			$FechaInventario = date('Y-m-d');
		 	$CodBodega = '01';
		 	$costo_existencias = Leer_Codigo_Inv($value['Codigo_Inv'],$FechaInventario,$CodBodega,$CodMarca='');



			if(empty($Familia))
			{
				$Familia[0]['Producto'] = '-';
				$Familia[0]['Codigo_Inv'] = '.';
			}
			if($costo_existencias['respueta']!=1)
			{
				$costo[0]['Existencia'] = 0;
				$costoTrans[0]['Costo'] = 0;				
			}else
			{
				$costo[0]['Existencia'] = $costo_existencias['datos']['Stock'];
				$costoTrans[0]['Costo'] = $costo_existencias['datos']['Costo'];		
			}
			

			$productos[] = array('id'=>$Familia[0]['Producto'].'_'.$Familia[0]['Codigo_Inv'].'_'.$value['Codigo_Inv'].'_'.$costoTrans[0]['Costo'].'_'.$value['Cta_Inventario'].'_'.$value['Producto'].'_'.$value['Unidad'].'_'.$value['Ubicacion'].'_'.$value['IVA'].'_'.$costo[0]['Existencia'].'_'.$value['Reg_Sanitario'].'_'.$value['Maximo'].'_'.$value['Minimo'],'text'=>$value['Producto']);

		}
		return $productos;
		// print_r($productos);die();
	}


	function crear_tabla_datos($orden,$prove)
	{
		$show = 'none';
    	$cabecera_tabla = '
    	<div class="table-responsive">

  		<table class="table table-hover text-md" id="tbl_style">
  			<thead>
  				<th>ITEM</th>
  				<th>FECHA</th>
  				<th>REFERENCIA</th>
  				<th>DESCRIPCION</th>
          <th class="text-right">CANTIDAD</th>
          <th class="text-right">PRECIO</th>
  		 <th class="text-right">DCTO</th>
  		 <th class="text-right">SUB TOTAL</th>
          <th class="text-right">IVA</th>
  				<th class="text-right">TOTAL</th>
  				<th></th>
  			</thead>
  			<tbody>';

    	$datos = $this->modelo->cargar_productos_pedido($orden,$prove);
		// $paginacion = paginancion('Asiento_K',$parametros['fun'],$parametros['pag']);

    	$num_reg = count($datos);
    	$tr='';
    	$subtotal=0;$ivatotal = 0;$total=0;
    	$d7=0;
    	$d=0;
    	$dcto = 0;
    	foreach ($datos as $key => $value) {

    		// print_r($value['Fecha_DUI']->format('Y-m-d'));
    		$show = 'block';
    		$parametros = array('codigo'=>$value['SUBCTA'],'query'=>'');
    		$nombre =  $this->paciente->cargar_paciente_proveedor($parametros);
    		// print_r($nombre);die();
    		$provee = $nombre[0]['Cliente'];
    		$subtotal+=number_format(($value['VALOR_UNIT']*$value['CANTIDAD'])-$value['P_DESC'],2,'.','');
    		$ivatotal+=$value['IVA'];
    		$total+=$value['VALOR_TOTAL'];
    		$dcto+=$value['P_DESC'];

    		$fecha = $value['Fecha_DUI']->format('Y-m-d');

    		$su = number_format(($value['VALOR_UNIT']*$value['CANTIDAD'])-number_format($value['P_DESC'],2),2,'.','');

			$d =   dimenciones_tabl(strlen($value['A_No']));
			$d1 =   dimenciones_tabl(strlen($fecha));
			$d2 =   dimenciones_tabl(strlen($value['CODIGO_INV']));
			$d3 =   dimenciones_tabl(strlen($value['PRODUCTO']));
			$d4 =   dimenciones_tabl(strlen($value['CANTIDAD']));
			$d5 =   dimenciones_tabl(strlen($value['VALOR_UNIT']));
			$d6 =   dimenciones_tabl(strlen($value['IVA']));
			$d7 =   dimenciones_tabl(strlen($value['VALOR_TOTAL']));
    		$tr.='<tr>
  					<td width="'.$d.'">'.$value['A_No'].'</td>
  					<td width="'.$d1.'">'.$fecha.'</td>
  					<td width="'.$d2.'">'.$value['CODIGO_INV'].'</td>
  					<td width="'.$d3.'">'.$value['PRODUCTO'].'</td>
  					<td width="'.$d4.'" class="text-right">'.$value['CANTIDAD'].'</td>
  					<td width="'.$d5.'" class="text-right">'.$value['VALOR_UNIT'].'</td>
  					<td width="'.$d7.'" class="text-right">'.$value['P_DESC'].'</td>
  					<td width="'.$d6.'" class="text-right">'.$su.'</td>
  					<td width="'.$d7.'" class="text-right">'.$value['IVA'].'</td>
  					<td width="'.$d7.'" class="text-right">'.$value['VALOR_TOTAL'].'</td>
  					<td width="10px">
  						<!-- <button class="btn btn-xs btn-primary" onclick="editar_lin()" title="Editar paciente"><span class="glyphicon glyphicon-floppy-disk"></span></button> -->
  						<button class="btn btn-xs btn-danger" title="Eliminar paciente"  onclick="eliminar_lin(\''.$value['A_No'].'\',\''.$orden.'\',\''.$prove.'\')" ><span class="glyphicon glyphicon-trash"></span></button>
  					</td>
  				</tr>';
			
		}
		$tr.='<tr>
  					<td width="'.$d.'" colspan="5"></td>
  					<td width="'.$d7.'"><b>TOTALES</b></td>
  					<td width="'.$d7.'" class="text-right">'.number_format($dcto,2,'.','').'</td>
  					<td width="'.$d7.'" class="text-right">'.number_format($subtotal,2,'.','').'</td>
  					<td width="'.$d7.'" class="text-right">'.number_format($ivatotal,2,'.','').'</td>
  					<td width="'.$d7.'" class="text-right">'.number_format($total,2,'.','').'</td>
  				</tr>';
		if($num_reg==0)
		{
			$tr.= '<tr><td colspan="7" class="text-center"><b><i>Sin registros...<i></b></td></tr>';
		}else
		{

		}

		$footer_tabla ='</tbody></table>
		<input type="hidden" id="iva_'.$orden.'" value="'.$ivatotal.'"/>		 
		</div>
		<div class="col-sm-7" style=" display:'.$show.'">		
		<button type="button" class="btn btn-primary" onclick="generar_factura(\''.$orden.'\',\''.$prove.'\')" ><i class="fa fa-archive"></i> Registrar Ingreso</button>
		<button type="button" class="btn btn-default" onclick="subir(\''.$orden.'\',\''.$prove.'\')"><i class="fa fa-upload"></i> Subir Documento</button>
		<button type="button" class="btn btn-danger" onclick="eliminar_todo(\''.$orden.'\',\''.$prove.'\')"><i class="fa fa-trash"></i> Eliminar Todo</button> <br><br>
		</div>';

		 return $cabecera_tabla.$tr.$footer_tabla;
	}

    function cargar_productos($parametros)
    {
    	$ordenes = $this->modelo->cargar_productos_pedido_TAB();
    	$datos = $this->modelo->cargar_productos_pedido();
    	
         $tab=' <ul class="nav nav-tabs">';
         $content = '<div class="tab-content">';
    	foreach ($ordenes as $key => $value) {
    		if($value['SUBCTA']!='.')
    		{
    		$prove = $this->modelo->proveedores(false,$value['SUBCTA']);
    		if($key==0)
    		{

    		    $content.='<div id="'.$value['SUBCTA'].'-'.$value['ORDEN'].'" class="tab-pane fade in active">';
    			$tab.='<li class="active"><a data-toggle="tab" href="#'.$value['SUBCTA'].'-'.$value['ORDEN'].'">'.$prove[0]['Cliente'].' Num Factura: '.$value['ORDEN'].'</a></li>';
    		}else
    		{

    		    $content.='<div id="'.$value['SUBCTA'].'-'.$value['ORDEN'].'" class="tab-pane fade">';
    			$tab.='<li><a data-toggle="tab" href="#'.$value['SUBCTA'].'-'.$value['ORDEN'].'">'.$prove[0]['Cliente'].' Num Factura: '.$value['ORDEN'].'</a></li>';
    		}

    		    $content.= $this->crear_tabla_datos($value['ORDEN'],$value['SUBCTA']);
    		$content.='</div>';
    	  }
    	}
    	$tab.='</ul>';
    	$content.='</div>';
    	$tabs_tabla = $tab.$content;
    	if(!isset($datos[0]['A_No']))
    	{
    		$datos[0]['A_No'] = 0;
    	}
    	if(count($ordenes)==0)
    	{
    		$tabs_tabla =$this->crear_tabla_datos('1','1');
    	}
		$tabla = array('pag'=>'','tabla'=>$tabs_tabla,'item'=>$datos[0]['A_No']);	
			// print_r($tabla);die();
		return $tabla;		
    }

	function proveedores($query)
	{
		$datos = $this->modelo->proveedores($query);
		if($datos!=-1)
		{
		     $prov = array();
		     foreach ($datos as $key => $value) {
			  // print_r($value);die();
			  $prov[] = array('id'=>$value['CI_RUC'].'-'.$value['Cta'].'-'.$value['Codigo'],'text'=>$value['Cliente']);
		     }
		     return $prov;
		 }else
		 {
		 	return -1;
		 }
	}

	function familias($query)
	{
		$datos = $this->modelo->familia_pro(false,$query);
		$familias = array();
		$format_inv= count(explode('.', $_SESSION['INGRESO']['Formato_Inventario']))-1;
		foreach ($datos as $key => $value) {

			$fa =count(explode('.',$value['Codigo_Inv']));
			if($format_inv == $fa)
			{
				$tiene = $this->modelo->familia_con_productos($value['Codigo_Inv']);
				if($tiene[0]['cant']!=0)
				{
				  $familias[] = array('id'=>$value['Codigo_Inv'].'-'.$value['Cta_Inventario'],'text'=>$value['Producto']);
				}
			}			
		}
		return $familias;
	}

	function familias2($query)
	{
		$datos = $this->modelo->familia_pro(false,$query);
		$familias = array();
		$format_inv= count(explode('.', $_SESSION['INGRESO']['Formato_Inventario']))-1;
		foreach ($datos as $key => $value) {
			$familias[] = array('id'=>$value['Codigo_Inv'].'-'.$value['Cta_Inventario'],'text'=>$value['Producto']);	
		}
		return $familias;
	}
	function catalogo_cuenta($query)
	{
		$datos = $this->modelo->catalogo_cuentas(false,$query);
		$cta = array();
		foreach ($datos as $key => $value) {
			$cta[] = array('id'=>$value['Codigo'],'text'=>$value['Cuenta']);			
		}
		return $cta;
	}

	function Ingresar_producto($parametros)
	{
		if(!isset($parametros['ddl_cta_venta']))   { $parametros['ddl_cta_venta'] = '';}
		if(!isset($parametros['ddl_cta_ventas_0'])){ $parametros['ddl_cta_ventas_0'] = '';}
		if(!isset($parametros['ddl_cta_vnt_anti'])){ $parametros['ddl_cta_vnt_anti'] = '';}


		$cta_i = explode('-',$parametros['ddl_familia_modal']);

		SetAdoAddNew("Catalogo_Productos"); 
		SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);
		SetAdoFields('TC','P');
		SetAdoFields('Codigo_Inv',$cta_i[0].'.'.$parametros['txt_ref']);
		SetAdoFields('Producto',strtoupper($parametros['txt_nombre']));
		SetAdoFields('Unidad',$parametros['txt_uni']);
		SetAdoFields('Minimo',$parametros['txt_min']);
		SetAdoFields('Maximo',$parametros['txt_max']);
		SetAdoFields('Cta_Costo_Venta',$parametros['ddl_cta_CV']);
		SetAdoFields('INV','1');
		SetAdoFields('Stock_Actual',0);
		SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		SetAdoFields('Cta_Inventario',$parametros['ddl_cta_inv']); 
		SetAdoFields('Reg_Sanitario',$parametros['txt_reg_sanitario']); 
		SetAdoFields('Codigo_Barras',$parametros['txt_cod_barras']); 
		SetAdoFields('Cta_Ventas',$parametros['ddl_cta_venta']); 
		SetAdoFields('Cta_Ventas_0',$parametros['ddl_cta_ventas_0']);
		SetAdoFields('Cta_Ventas_Anticipadas',$parametros['ddl_cta_vnt_anti']); 
		SetAdoFields('T','N'); 

		// print_r($parametros);die();
		if(isset($parametros['txt_id']) AND $parametros['txt_id'] !='')
		{
			SetAdoFieldsWhere('ID',$parametros['txt_id']);
			SetAdoUpdateGeneric();
		}else
		{   
			return SetAdoUpdate();
		}

	}
	function Ingresar_proveedor($parametros)
	{

		$codigo = Digito_Verificador($parametros['txt_ruc']);
		// print_r($codigo);die();
		$existe = $this->modelo->clientes_all(false,$codigo['Codigo_RUC_CI']);
		$cli = '';
		
		    SetAdoAddNew("Clientes"); 
		    SetAdoFields('FA','1');
		    SetAdoFields('T','N');
		    SetAdoFields('Codigo',$codigo['Codigo_RUC_CI']);
		    SetAdoFields('Cliente',$parametros['txt_nombre_prove']);
		    SetAdoFields('CI_RUC',$parametros['txt_ruc']);
		    SetAdoFields('Email',$parametros['txt_email']);
		    SetAdoFields('Email2',$parametros['txt_email2']);
		    SetAdoFields('Telefono',$parametros['txt_telefono']);
		    SetAdoFields('Direccion',$parametros['txt_direccion']);
		    SetAdoFields('Actividad',$parametros['actividad']);
		    SetAdoFields('Cod_Ejec',$parametros['txt_ejec']);
		    SetAdoFields('Tipo_Pasaporte',$parametros['CTipoProv']);
		    SetAdoFields('Parte_Relacionada',$parametros['CParteR']);
		if(empty($existe))
		{			
		    SetAdoFields('Fecha',strval(date('Y-m-d')));
		    $cli = SetAdoUpdate();
		}else{
			SetAdoFieldsWhere('ID',$parametros['txt_id_prove']);
			$cli =  SetAdoUpdateGeneric();
		}

		 $exist = $this->modelo->catalogo_Cxcxp($codigo['Codigo_RUC_CI']);

		 if(empty($exist))
		 {
		    SetAdoAddNew("Catalogo_CxCxP"); 
		    SetAdoFields('Codigo',$codigo['Codigo_RUC_CI']);
		    SetAdoFields('Cta',$this->modelo->buscar_cta_proveedor());
		    SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		    SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);
		    SetAdoFields('TC','P');
		    $cta = SetAdoUpdate();
		 }else{$cta = 1;}

		 // print_r($cta.'-'.$cli);die();

		 if($cta != 1 && $cli !=1)
		 {
		 	return -2;
		 }else
		 {
		 	return 1;
		 }

	}

	function eliminar_proveedor($parametros)
	{

		// $codigo = Digito_Verificador($parametros['txt_ruc']);
		// print_r($codigo);die();
		$existe = $this->modelo->clientes_all(false,false,false,false,$parametros['idProv']);

		$codigo = $existe[0]['Codigo'];
		$datos = $this->modelo->buscar_si_existe($codigo);
		if(count($datos)==0)
		{

			return $this->modelo->eliminar_proveedor($parametros['idProv']);

		}else
		{
			return -1;
		}
		// print_r($datos);die();

		
	}

	function agreagar_producto($parametro)
	{
		   // print_r($parametro);die();

		   $val_descto = (($parametro['txt_precio']*$parametro['txt_canti'])*$parametro['txt_descto'])/100;
		   $pro = $this->modelo->buscar_cta_proveedor();
		   $producto = explode('_',$parametro['ddl_producto']);
		   
		   $prove = explode('-', $parametro['ddl_proveedor']);

		   SetAdoAddNew("Asiento_K"); 
		   SetAdoFields('CODIGO_INV',$parametro['txt_referencia']);
		   SetAdoFields('PRODUCTO',$producto[5]);
		   SetAdoFields('UNIDAD',$parametro['txt_unidad']); 
		   SetAdoFields('CANT_ES',$parametro['txt_canti']);
		   SetAdoFields('CTA_INVENTARIO',$producto[4]);
		   SetAdoFields('SUBCTA',$prove[2]);		   
		   SetAdoFields('CodigoU',$_SESSION['INGRESO']['Id']);   
		   SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		   SetAdoFields('A_No',$parametro['A_No']+1);

		   SetAdoFields('Fecha_DUI',$parametro['txt_fecha']);

		   SetAdoFields('TC','P');
		   SetAdoFields('VALOR_TOTAL',number_format($parametro['txt_total'],4,'.',''));
		   SetAdoFields('CANTIDAD',$parametro['txt_canti']);
		   SetAdoFields('VALOR_UNIT',number_format($parametro['txt_precio'],7,'.',''));
		   //round($parametro['txt_precio'],2,PHP_ROUND_HALF_DOWN);
		   SetAdoFields('DH',1);
		   SetAdoFields('CONTRA_CTA',$pro);
		   SetAdoFields('ORDEN',$parametro['txt_num_fac']);
		   SetAdoFields('IVA',number_format($parametro['txt_iva'],4,'.',''));
		   SetAdoFields('Fecha_Fab',$parametro['txt_fecha_ela']);		   
		   SetAdoFields('Fecha_Exp',$parametro['txt_fecha_exp']);		   
		   SetAdoFields('Reg_Sanitario',$parametro['txt_reg_sani']);		   
		   SetAdoFields('Lote_No',$parametro['txt_lote']);		   
		   SetAdoFields('Procedencia',$parametro['txt_procedencia']);		   
		   SetAdoFields('Serie_No',$parametro['txt_serie']);
		   SetAdoFields('P_DESC',$val_descto); 

		   // print_r($datos);die();

		   return SetAdoUpdate();
	}

	function lineas_eli($parametros)
	{
		$resp = $this->modelo->lineas_eli($parametros);
		return $resp;
	}

	function generar_factura($parametros)
	{
		 if($parametros['iva_exist']!=0)
		 {
		   $cuenta_iva =$this->modelo->buscar_cta_iva_inventario();
		   if($cuenta_iva!=-1)
		   {
		   	$ruc = $this->modelo->proveedores(false,$parametros['prove']);
			$res = $this->generar_factura_entrada($parametros['num_fact'],$ruc[0]['CI_RUC'],$parametros['prove']);
			return $res;
		   }else
		   {
		   	  return array('resp'=>-2);
		   }
		 }else{
		  
			$ruc = $this->modelo->proveedores(false,$parametros['prove']);
			if(count($ruc)==0)
			{
				$ruc = $this->modelo->clientes_all($query=false,$parametros['prove']);
			}
			if(count($ruc)==0)
			{
				$ruc[0]['CI_RUC'] = '.';
			}
			$res = $this->generar_factura_entrada($parametros['num_fact'],$ruc[0]['CI_RUC'],$parametros['prove']);
			return $res;
		}
			// print_r('expression');die();		
	}

	function generar_factura_entrada($orden,$ruc,$CodigoPrv)
	{
		// print_r($CodigoPrv);die();
		if($this->modelo->misma_fecha($orden,$CodigoPrv)==-1)
		{
			return array('resp'=>-3,'com'=>'');
		}
		$ruc1 = $ruc;

		//esto se realiza  solo para devoluciones en donde CodigoPrV tiene que ser el codigo de la sub cuenta traido desde la vista
		if($ruc=='.')
		{
			$ruc1 = $CodigoPrv;
		}
		$asientos_SC = $this->modelo->datos_asiento_SC($orden,$CodigoPrv);

		$parametros_debe = array();
		$parametros_haber = array();
		$nombre='';
		// $fecha=date('Y-m-d');

		// print_r($asientos_SC);die();
		foreach ($asientos_SC as $key => $value) {
			 $cuenta = $this->ing_descargos->catalogo_cuentas($value['CONTRA_CTA']);
			 // print_r($cuenta);die();
			 if(count($cuenta)==0){ $cuenta[0]['Cuenta'] = '.'; $cuenta[0]['TC'] = 'CD';$cuenta[0]['Cuenta']='.'; $cuenta[0]['TC']='.';}
			 $sub = $this->modelo->proveedores($query=false,$value['SUBCTA']);

			 // print_r($sub);die();
			 if(count($sub)==0){$sub[0]['Cliente']='.';$sub[0]['Codigo']='.';}
			 $nombre=$sub[0]['Cliente'];
			 // print_r($sub);die();
			$parametros = array(
                    'be'=>$cuenta[0]['Cuenta'],
                    'ru'=> '',
                    'co'=> $value['CONTRA_CTA'],// codigo de cuenta cc
                    'tip'=>$cuenta[0]['TC'],//tipo de cuenta(CE,CD,..--) biene de catalogo subcuentas TC
                    'tic'=> 2, //debito o credito (1 o 2);
                    'sub'=> $sub[0]['Codigo'], //Codigo se trae catalogo subcuenta o ruc del proveedor en caso de que se este ingresando
                    'sub2'=>$cuenta[0]['Cuenta'],//nombre del beneficiario
                    'fecha_sc'=> $value['Fecha_DUI']->format('Y-m-d'), //fecha 
                    'fac2'=>$orden,
                    'mes'=> 0,
                    'valorn'=> round($value['total'],2),//valor de sub cuenta 
                    'moneda'=> 1, /// moneda 1
                    'Trans'=>$sub[0]['Cliente'],//detalle que se trae del asiento
                    'T_N'=> '99',
                    't'=> $cuenta[0]['TC'],                        
                  );
                  $this->ing_descargos->generar_asientos_SC($parametros);
		}
		
		// print_r($asientos_SC);die();

		//asientos para el debe
		$asiento_debe = $this->modelo->datos_asiento_haber($orden,$CodigoPrv);
		$tiene_iva =false;
		foreach ($asiento_debe as $key => $value) {
			 if($value['IVA']!=0)
			 {
			 	$tiene_iva = true;
			 	break;
			 }
		}

		if($tiene_iva ==true)
		{
			$fecha = $asiento_debe[0]['fecha']->format('Y-m-d');	
			$total_iva = $this->modelo->iva_comprobante($orden,$CodigoPrv);
			$cuenta_iva =$this->modelo->buscar_cta_iva_inventario();
		    foreach ($total_iva as $key => $value) 
		    {
				    $parametros_debe = array(
				     "va" =>number_format($value['IVA'],2,'.',''),//valor que se trae del otal sumado
                      "dconcepto1" =>'Cta_IVA_Inventario',
                      "codigo" => $cuenta_iva, // cuenta de codigo de 
                      "cuenta" => 'Cta_IVA_Inventario', // detalle de cuenta;
                      "efectivo_as" =>$fecha, // observacion si TC de catalogo de cuenta
                      "chq_as" => 0,
                      "moneda" => 1,
                      "tipo_cue" => 1,
                      "cotizacion" => 0,
                      "con" => 0,// depende de moneda
                      "t_no" => '99',
			    );
				     $this->ing_descargos->ingresar_asientos($parametros_debe);
		    }

		    $siento_debe_con_iva = $this->modelo->datos_asiento_haber_CON_IVA($orden,$CodigoPrv);
		   foreach ($siento_debe_con_iva as $key => $value) 
		    {
			    // print_r($value);die();
			       $cuenta = $this->ing_descargos->catalogo_cuentas($value['cuenta']);		
			       // print_r($cuenta);die();
				    $parametros_debe = array(
				     "va" =>number_format($value['sub'],2,'.',''),//valor que se trae del otal sumado
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
				     $this->ing_descargos->ingresar_asientos($parametros_debe);
		    }
		}else
		{
		    $fecha = $asiento_debe[0]['fecha']->format('Y-m-d');	
		    foreach ($asiento_debe as $key => $value) 
		    {
			    // print_r($value);die();
			       $cuenta = $this->ing_descargos->catalogo_cuentas($value['cuenta']);		
			       // print_r($cuenta);die();
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
				     $this->ing_descargos->ingresar_asientos($parametros_debe);
		    }
        }
        // asiento para el haber
		$asiento_haber  =  $this->modelo->datos_asiento_debe($orden,$CodigoPrv);
		foreach ($asiento_haber as $key => $value) {
			$cuenta = $this->ing_descargos->catalogo_cuentas($value['cuenta']);
			if(count($cuenta)==0){$cuenta[0]['Cuenta']='.';}			
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
                $this->ing_descargos->ingresar_asientos($parametros_haber);
		}
		// print_r($fecha);die();
		// $parametros = array('tip'=> 'CD','fecha'=>$fecha);
	    $num_comprobante = numero_comprobante1('Diario',true,true,$fecha);
	    $dat_comprobantes = $this->ing_descargos->datos_comprobante();
	    $debe = 0;
		$haber = 0;
		foreach ($dat_comprobantes as $key => $value) {
			$debe+=$value['DEBE'];
			$haber+=$value['HABER'];
		}
		// print_r($debe.'-'.$haber);die();
		if(strval($debe)==strval($haber))
		{
			if($debe !=0 && $haber!=0)
			{
				 $parametro_comprobante = array(
        	        'ru'=> $ruc, //codigo del cliente que sale co el ruc del beneficiario codigo
        	        'tip'=>'CD',//tipo de cuenta contable cd, etc
        	        "fecha1"=> $fecha,// fecha actual 2020-09-21
        	        'concepto'=>'Entrada de inventario por '.$nombre.' de la factura '.$orden.' el dia '.$fecha, //detalle de la transaccion realida
        	        'totalh'=> round($haber,2), //total del haber
        	        'num_com'=>'.'.date('Y', strtotime($fecha)).'-'.$num_comprobante, // codigo de comprobante de esta forma 2019-9000002 '' hasta que no se cambie la funcion ese debe ser el formato'
        	        );

                $resp = $this->ing_descargos->generar_comprobantes($parametro_comprobante);
                // print_r('ssss');die();
                // $cod = explode('-',$num_comprobante);
                if($resp==$num_comprobante)
                {
                	if($this->ingresar_trans_kardex_entrada($orden,$num_comprobante,$fecha,$CodigoPrv,$nombre)==1)
                	{
                		$resp = $this->modelo->eliminar_aiseto_K($orden,$CodigoPrv);
                		if($resp==1)
                		{
                			$this->modelo->eliminar_aiseto();
                			$this->modelo->eliminar_aiseto_sc($orden);                			
                			mayorizar_inventario_sp();
                			return array('resp'=>1,'com'=>$num_comprobante);
                		}else
                		{
                			return array('resp'=>-1,'com'=>'No se pudo eliminar asiento_K');
                		}
                	}else
                	{
                		return array('resp'=>-1,'com'=>'Uno o todos No se pudo registrar en Trans_Kardex');
                	}
                }else
                {

			     $this->modelo->eliminar_aiseto();
			     $this->modelo->eliminar_aiseto_sc($orden);
        	        return array('resp'=>-1,'com'=>$resp);
                }

			}else
			{
				// print_r($debe."-".$haber); 

			     $this->modelo->eliminar_aiseto();
			     $this->modelo->eliminar_aiseto_sc($orden);
				 return array('resp'=>-1,'com'=>'Los resultados son 0');

			}
		}else
		{
			$this->modelo->eliminar_aiseto();
			$this->modelo->eliminar_aiseto_sc($orden);
			return array('resp'=>-1,'com'=>'No coinciden');

		}
	}


//en proceso
	function ingresar_trans_kardex_entrada($orden,$comprobante,$fechaC,$CodigoPrv,$nombre)
    {
		$datos_K = $this->modelo->cargar_pedidos($orden,$CodigoPrv);
		// $comprobante = explode('.',$comprobante);
		// $comprobante = explode('-',trim($comprobante[1]));
		$comprobante = $comprobante;
		$resp = 1;
		foreach ($datos_K as $key => $value) {
		   $datos_inv = $this->ing_descargos->lista_hijos_id($value['CODIGO_INV']);
		    $cant[2] = 0;
		   if(count($datos_inv)>0)
		   {
		   	 $cant = explode(',',$datos_inv[0]['id']);
		   	 $cant[2] = $cant[2];
		   }
		   	
		    SetAdoAddNew("Trans_Kardex"); 		
		    SetAdoFields('Codigo_Inv',$value['CODIGO_INV']); 
		    SetAdoFields('Fecha',$fechaC); 
		    SetAdoFields('Numero',$comprobante);  
		    SetAdoFields('T','N'); 
		    SetAdoFields('TP','CD'); 
		    SetAdoFields('Codigo_P',$_SESSION['INGRESO']['CodigoU']); 
		    SetAdoFields('Cta_Inv',$value['CTA_INVENTARIO']); 
		    SetAdoFields('Contra_Cta',$value['CONTRA_CTA']); 
		    SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']); 
		    SetAdoFields('Entrada',$value['CANTIDAD']); 
		    SetAdoFields('Valor_Unitario',number_format($value['VALOR_UNIT'],$_SESSION['INGRESO']['Dec_PVP'],'.','')); 
		    SetAdoFields('Valor_Total',number_format($value['VALOR_TOTAL'],2)); 
		    SetAdoFields('Costo',number_format($value['VALOR_UNIT'],2)); 
		    SetAdoFields('Total',number_format($value['VALOR_TOTAL'],2));
		    if(isset($cant[2]))
		    {
		    	if(!is_numeric($cant[2])){$cant = 0;}
		    	SetAdoFields('Existencia',number_format($cant[2],2)+intval($value['CANTIDAD']));
		    	// print_r($cant[2]);
		    }else
		    {
		    	SetAdoFields('Existencia',number_format(0,2)+intval($value['CANTIDAD']));
		    }
		    SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU']);
		    SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		    SetAdoFields('CodBodega','01');
		    SetAdoFields('CodigoL',$value['SUBCTA']);
		    SetAdoFields('Detalle','Entrada de inventario por '.$nombre.' de la factura '.$orden.' el dia '.$fechaC);
		    SetAdoFields('Fecha_Exp',$value['Fecha_Exp']->format('Y-m-d'));
		    SetAdoFields('Fecha_Fab',$value['Fecha_Fab']->format('Y-m-d'));
		    SetAdoFields('Reg_Sanitario',$value['Reg_Sanitario']);
		    SetAdoFields('Lote_No',$value['Lote_No']);
		    SetAdoFields('Procedencia',$value['Procedencia']);
		    SetAdoFields('Serie_No',$value['Serie_No']);
		    SetAdoFields('Factura',$value['ORDEN']);
		    SetAdoFields('Codigo_Dr',$value['Codigo_Dr']);


		     if(SetAdoUpdate()!=1)
		     {
		     	$resp = 0;
		     } 
	}
	return $resp;

}

function cuentas_asignar($cuenta,$query)
{
	$datos = $this->modelo->cuentas_asignar($cuenta,$query);
	$cta = array();
	foreach ($datos as $key => $value) {
		$cta[] = array('id'=>$value['Codigo'],'text'=>$value['Cuenta']);
	}
	return $cta;
}
function buscar_ultimo($cta)
{
	$dato =$this->modelo->buscar_ultimo($cta);	
	$format_inv= explode('.', $_SESSION['INGRESO']['Formato_Inventario']);
	$pos = count($format_inv);
	$num_car =strlen($format_inv[$pos-1]);
	$ceros = str_repeat('0',$num_car-1);
	$num =$ceros.'1';
	if(!empty($dato))
	{
		$numero = explode('.',$dato[0]['Codigo_Inv']);
		$numero = $numero[$pos-1]+1;
		$num = $numero;
		$numero = strlen($numero);
		$falta = $num_car-$numero;
		if($falta>0)
		{
			$ceros = str_repeat('0',$falta);
			$num = $ceros.''.$num;
		}		
		$cod_in = $cta.'.'.$num;
		$existe = true;
	    while ($existe == true) {
		    $cod = $this->modelo->buscar_cod_existente($cod_in);
		    if(empty($cod))
		    {
			    $existe =false;
			    break;
		    }else{
		    	 $num = $ceros.($num+1);
		         $cod_in = $cta.'.'.$num;
		    }
		   
	    }

	    // print_r($num);die();

	}
	return $num;	
}

function eliminar_factura($parametros)
{
	$resp = $this->modelo->eliminar_aiseto_K($parametros['orden'],$parametros['pro']);
	return $resp;
}


 function agregar_articulo_foto($file,$post)
   {
   	// print_r($file);
   	// print_r($post);die();
   	// $ruta='../../vista/TEMP/';//ruta carpeta donde queremos copiar las imágenes
   	$ruta =  dirname(__DIR__,3).'/img/ING_CXP/'.$_SESSION['INGRESO']['item'].'/';
   	// print_r($ruta);die();
   	if (!file_exists($ruta)) {
       mkdir($ruta, 0777, true);
    }
    if($file['file_img']['type']=="image/jpeg" || $file['file_img']['type']=="image/pjpeg" || $file['file_img']['type']=="image/gif" || $file['file_img']['type']=="image/png" || $file['file_img']['type']=="application/pdf")
      {
   	     $uploadfile_temporal=$file['file_img']['tmp_name'];
   	     $tipo = explode('/', $file['file_img']['type']);
          $nombre = $post['txt_nom_img'].'.'.$tipo[1];
        
   	     $nuevo_nom=$ruta.$nombre;
   	     if (is_uploaded_file($uploadfile_temporal))
   	     {
   		     move_uploaded_file($uploadfile_temporal,$nuevo_nom);
   		     $base=1;
   		     // if($post['txt_id']!='')
   		     // 	{
   		     // 		$base = $this->modelo->img_guardar($nuevo_nom,$post['txt_id']);
   		     // 	} else
   		     // 	{
   		     // 		$base = $this->modelo->img_guardar($nuevo_nom,'',$post['txt_nom_img']);
   		     // 	}  		     
   		     if($base==1)
   		     {
   		     	return 1;
   		     }else
   		     {
   		     	return -1;
   		     }

   	     }
   	     else
   	     {
   		     return -1;
   	     } 
     }else
     {
     	return -2;
     }

  }

  function autocompletar($query)
	{

		$datos = $this->modelo->clientes_all($query);
		// print_r($datos);die();
		$result = array();
		foreach ($datos as $key => $value) {
			 $result[] = array("value"=>$value['ID'],"label"=>$value['Cliente'],'dir'=>$value['Direccion'],'tel'=>$value['Telefono'],'email'=>$value['Email'],'email2'=>$value['Email2'],'CI'=>$value['CI_RUC'],'Actividad'=>$value['Actividad'],'Cod_Ejec'=>$value['Cod_Ejec'],'Parte_Relacionada'=>$value['Parte_Relacionada'],'Tipo_Pasaporte'=>$value['Tipo_Pasaporte']);
		}
		return $result;
	}
	function autocompletar_busqueda_ruc($query)
	{

		$datos = $this->modelo->clientes_all(false,false,false,$query);
		// print_r($datos);die();
		$result = array();
		foreach ($datos as $key => $value) {
			 $result[] = array("value"=>$value['ID'],"label"=>$value['CI_RUC'],'dir'=>$value['Direccion'],'tel'=>$value['Telefono'],'email'=>$value['Email'],'email2'=>$value['Email2'],'Nombre'=>$value['Cliente'],'Actividad'=>$value['Actividad'],'Cod_Ejec'=>$value['Cod_Ejec'],'Parte_Relacionada'=>$value['Parte_Relacionada'],'Tipo_Pasaporte'=>$value['Tipo_Pasaporte']);
		}
		return $result;
	}

  	function autocompletarAbre($query)
	{

	$datos = $this->modelo->clientes_all(false,false,$query);
	// print_r($datos);die();
	$result = array();
	foreach ($datos as $key => $value) {
		 $result[] = array("value"=>$value['ID'],"Cliente"=>$value['Cliente'],'dir'=>$value['Direccion'],'tel'=>$value['Telefono'],'email'=>$value['Email'],'CI'=>$value['CI_RUC'],'Actividad'=>$value['Actividad'],'label'=>$value['Cod_Ejec']);
	}
	return $result;
	}

	function validar_abreviatura($parametros)
	{
		$datos = $this->modelo->clientes_all(false,false,$parametros['abre']);
		if(count($datos)>0)
		{
			// print_r($datos);
			// print_r($parametros);die();

			if($parametros['id']==$datos[0]['ID'])
			{
				return -1;
			}else{
				return 1;
			}
		}else
		{
			return -1;
		}
	// print_r($datos);die();
	}



   function eliminar_articulos($id)
   {
   	return $this->modelo->eliminar_articulos($id);
   }

   function detalle_articulos($id)
   {

   	$datos = $this->modelo->articulos($id);
   	// print_r($datos);die();
   	$cta_inv='';
   	if($datos[0]['Cta_Inventario']!='.' && $datos[0]['Cta_Inventario']!='')
   	{
   		$cta_inv = $this->modelo->catalogo_cuentas($datos[0]['Cta_Inventario'],$query=false);
   	}
   	$cta_CV='';
   	if($datos[0]['Cta_Costo_Venta']!='.' && $datos[0]['Cta_Costo_Venta']!='')
   	{
   		$cta_CV = $this->modelo->catalogo_cuentas($datos[0]['Cta_Costo_Venta'],$query=false);
   	}
   	$cta_V = '';
   	if($datos[0]['Cta_Ventas']!='.' && $datos[0]['Cta_Ventas']!='')
   	{
   		$cta_V = $this->modelo->catalogo_cuentas($datos[0]['Cta_Ventas'],$query=false);
   	}
   	$cta_V0 = '';
   	if($datos[0]['Cta_Ventas_0']!='.' && $datos[0]['Cta_Ventas_0']!='')
   	{
   		$cta_V0 = $this->modelo->catalogo_cuentas($datos[0]['Cta_Ventas_0'],$query=false);
   	}
   	$cta_VA = '';
   	if($datos[0]['Cta_Ventas_0']!='.' && $datos[0]['Cta_Ventas_0']!='')
   	{
   		$cta_VA = $this->modelo->catalogo_cuentas($datos[0]['Cta_Venta_Anticipada'],$query=false);
   	}
   	$codi = explode('.',$datos[0]['Codigo_Inv']);
   	$partes = count($codi);
   	$f = '';
   	for ($i=0; $i < $partes-1; $i++) { 
   		$f.=$codi[$i].'.';
   	}
   	$f = substr($f,0,-1);
   	$fami = $this->modelo->familia_pro($f,$query = false);

   	return array('datos'=>$datos,'inv'=>$cta_inv,'cv'=>$cta_CV,'v'=>$cta_V,'v0'=>$cta_V0,'va'=>$cta_VA,'fami'=>$fami,'num'=>$codi[$partes-1]);
   }

   function familia_new($parametros)
   {
   	$codigo = $parametros['codigo'];
   	$nombre = $parametros['nombre'];


   	if(count($this->modelo->familia_pro($codigo,$query=false,$exacto=false))>0)
   	{

   	// print_r($codigo);die();
   		return -2;
   	}
   	if(count($this->modelo->familia_pro($Codigo=false,$nombre,$exacto=true))>0)
   	{

   	// print_r($nombre);die();
   		return -3;
   	}


   	 SetAdoAddNew("Catalogo_Productos"); 		
   	 SetAdoFields('Codigo_Inv',$codigo);
	 SetAdoFields('Producto', strtoupper($nombre));
	 SetAdoFields('Item',$_SESSION['INGRESO']['item']);
	 SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);
	 SetAdoFields('TC','I');
	 SetAdoFields('INV','1');
	 SetAdoFields('Cta_Inventario','0');

	 SetAdoUpdate();

	 // print_r($datos);die();
	 if($this->modelo->guardar($table='Catalogo_Productos',$datos)==null)
	 {
	 	return 1;
	 }	   

   }

   //guardado pa donacion de alimentos
   function guardar_recibido($parametro)
	{
		$producto = explode('_',$parametro['ddl_producto']);
		$num_ped = 99999;
		// print_r($producto);
		// print_r($parametro);die();
		
		   SetAdoAddNew("Trans_Kardex"); 		
		   SetAdoFields('Codigo_Inv',$parametro['txt_referencia']);
		   SetAdoFields('Producto',$producto[5]);
		   SetAdoFields('UNIDAD',$producto[6]); /**/
		   SetAdoFields('Salida',$parametro['txt_canti']);
		   SetAdoFields('Cta_Inv',$producto[4]);
		   // SetAdoFields('CodigoL',$parametro['rubro']);		   
		   SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU']);   
		   SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		   SetAdoFields('Orden_No',$num_ped); 
		   SetAdoFields('A_No',$parametro['A_No']+1);
		   SetAdoFields('Fecha',date('Y-m-d',strtotime($parametro['txt_fecha'])));
		   // SetAdoFields('TC',$parametro['TC']);
		   SetAdoFields('Valor_Total',number_format($parametro['txt_total'],2,'.',''));
		   SetAdoFields('CANTIDAD',$parametro['txt_canti']);
		   SetAdoFields('Valor_Unitario',number_format($parametro['txt_precio'],$_SESSION['INGRESO']['Dec_PVP'],'.',''));
		   SetAdoFields('DH',2);
		   // SetAdoFields('Contra_Cta',$parametro['cc']);
		   // SetAdoFields('Descuento',$parametro['descuento']);
		   // SetAdoFields('Codigo_P',$parametro['CodigoP']);
		   // SetAdoFields('Detalle',$parametro['pro']);
		   // SetAdoFields('Centro_Costo',$parametro['area']);
		   // SetAdoFields('Codigo_Dr',$parametro['solicitante']);
		   // SetAdoFields('Costo',number_format($parametro['valor'],2,'.',''));

		   // if($parametro['iva']!=0)
		   // {
		   	   // $datos[19]['campo']='IVA';
		       // $datos[19]['dato']=(round($parametro['total'],2)*1.12)-round($parametro['total'],2);
		   // }
		   // print_r($datos);die();
		   $resp = SetAdoUpdate();
		   // $num = $num_ped;
		   return  $respuesta = array('ped'=>$num_ped,'resp'=>$resp);		
	}

	function cargar_pedidos($parametros)
    {
    	print_r($parametros);die();
    	// $ordenes = $this->modelo->cargar_pedidos_fecha_trans($parametros['num_ped'],$parametros['area']);
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



}