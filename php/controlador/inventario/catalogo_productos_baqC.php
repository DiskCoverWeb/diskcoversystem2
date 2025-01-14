<?php 
require_once(dirname(__DIR__,2)."/modelo/inventario/catalogo_productos_baqM.php");
require_once(dirname(__DIR__,3)."/lib/fpdf/generar_codigo_barras.php");


$controlador = new catalogo_productos_baqC();
if(isset($_GET['TVcatalogo']))
{
  $nivel = $_POST['nivel'];
  $codigo = $_POST['cod'];
  echo json_encode($controlador->TVcatalogo($nivel,$codigo));
}
if(isset($_GET['TVcatalogo_Bodega']))
{
  $nivel = $_POST['nivel'];
  $codigo = $_POST['cod'];
  echo json_encode($controlador->TVcatalogo_Bodega($nivel,$codigo));
}
if(isset($_GET['LlenarInv']))
{
	$parametros = $_POST['parametros'];
  echo json_encode($controlador->LlenarInv($parametros));
}
if(isset($_GET['LlenarInvBod']))
{
	$parametros = $_POST['parametros'];
  echo json_encode($controlador->LlenarInvBod($parametros));
}
if(isset($_GET['guardarINV']))
{
	$parametros = $_POST;
  echo json_encode($controlador->guardarINV($parametros));
}
if(isset($_GET['guardarINVBod']))
{
	$parametros = $_POST;
  echo json_encode($controlador->guardarINVBod($parametros));
}
if(isset($_GET['eliminarINV']))
{
	$codigo = $_POST['codigo'];
  echo json_encode($controlador->eliminarINV($codigo));
}
if(isset($_GET['eliminarINVBod']))
{
	$codigo = $_POST['codigo'];
  echo json_encode($controlador->eliminarINVBod($codigo));
}

if(isset($_GET['cod_barras']))
{
	$codigo = $_GET['codigo'];
	$cant = $_GET['cant'];
  echo json_encode($controlador->cod_barras($codigo,$cant));
}

if(isset($_GET['cod_barras_grupo']))
{
	$codigo = $_GET['codigo'];
  echo json_encode($controlador->cod_barras_grupo($codigo));
}

/**
 * 
 */
class catalogo_productos_baqC
{
	private $modelo;
	private $barras;
	function __construct()
	{
		$this->modelo = new catalogo_productos_baqM();
		$this->barras = new generar_codigo_barras();
	}

	function TVcatalogo($nl='',$codigo=false)
	{
		if($nl==''){$nl=1;}
		$cuenta  = $_SESSION['INGRESO']['Formato_Inventario'];
		$partes = explode('.',$cuenta);
		$len = strlen($partes[0]);
		$productos = $this->modelo->TVCatalogo(false,'I',$len);


		$h = '';
		if($codigo=='false')
		{
		$nnl=$nl+1;
			foreach ($productos as $key => $value) {
				$hijo = $this->exite_hijo($value['Codigo_Inv']);
				 if(count($hijo)>0)
				 {
				 		$h.='<li  title="Presione Suprimir para eliminar" style="padding:5px 0;">
							    <label id="label_'.str_replace('.','_',$value['Codigo_Inv']).'" for="'.$value['Codigo_Inv'].'" style="height:fit-content;">'.$value['Codigo_Inv'].' '.$value['Producto'].'</label>
							    <input type="checkbox" id="'.$value['Codigo_Inv'].'" onclick="TVcatalogo('.$nnl.',\''.$value['Codigo_Inv'].'\')" />
							   <ol id="hijos_'.str_replace('.','_',$value['Codigo_Inv']).'"></ol></li>';
				 }else
				 {

				 	$h.='<li  title="Presione Suprimir para eliminar" style="padding:5px 0;">
							    <label id="label_'.str_replace('.','_',$value['Codigo_Inv']).'" for="'.$value['Codigo_Inv'].'" style="height:fit-content;">'.$value['Codigo_Inv'].' '.$value['Producto'].'</label>
							    <input type="checkbox" id="'.$value['Codigo_Inv'].'" onclick="TVcatalogo('.$nnl.',\''.$value['Codigo_Inv'].'\')" />
							   <ol id="hijos_'.str_replace('.','_',$value['Codigo_Inv']).'"></ol></li>';

							   
				 	 // $h.='<li class="file" id="label_'.str_replace('.','_',$value['Codigo_Inv']).'" title="Presione Suprimir para eliminar"><a href="">'.$value['Codigo_Inv'].' '.$value['Producto'].'</a></li>';

				 	 // $h.='<li class="file" id="label_'.str_replace('.','_',$value['Codigo_Inv']).'" title="Presione Suprimir para eliminar" ><a href="#" onclick="detalle('.$nnl.',\''.$value['Codigo_Inv'].'\')">'.$value['Codigo_Inv'].' '.$value['Producto'].'</a></li>';
				 }
			}

		}else
		{
				// print_r($codigo);
			$datos =  $this->exite_hijo($codigo);
			// print_r($datos);
			 // print_r($nl); die();
			$nnl=$nl+1;
			foreach ($datos as $key => $value) {

			// print_r(count(explode('.', $value['Codigo_Inv'])));
			// print_r($nnl);
			// die();
				if(count(explode('.', $value['Codigo_Inv']))==$nl)
				{
				$hijo = $this->exite_hijo($value['Codigo_Inv']);
				 if(count($hijo)>0)
				 {
				 		$h.='<li title="Presione Suprimir para eliminar" style="padding:5px 0;">
							    <label id="label_'.str_replace('.','_',$value['Codigo_Inv']).'" for="'.str_replace('.','_',$value['Codigo_Inv']).'" style="height:fit-content;">'.$value['Codigo_Inv'].' '.$value['Producto'].'</label>
							    <input type="checkbox" id="'.str_replace('.','_',$value['Codigo_Inv']).'" onclick="TVcatalogo('.$nnl.',\''.$value['Codigo_Inv'].'\')" />
							   <ol id="hijos_'.str_replace('.','_',$value['Codigo_Inv']).'"></ol></li>';
				 }else
				 {
				 	 if($value['TC']=='I')
				 	 {
				 	 	$h.='<li>
							    <label id="label_'.str_replace('.','_',$value['Codigo_Inv']).'" for="'.str_replace('.','_',$value['Codigo_Inv']).'" style="height:fit-content;">'.$value['Codigo_Inv'].' '.$value['Producto'].'</label>
							    <input type="checkbox" id="'.str_replace('.','_',$value['Codigo_Inv']).'" onclick="TVcatalogo('.$nnl.',\''.$value['Codigo_Inv'].'\')" />
							   <ol id="hijos_'.str_replace('.','_',$value['Codigo_Inv']).'"></ol></li>';

				 	 }else{
				 	 $h.='<li class="file" id="label_'.str_replace('.','_',$value['Codigo_Inv']).'" title="Presione Suprimir para eliminar" style="padding:5px 0;"><a href="#" onclick="detalle('.$nnl.',\''.$value['Codigo_Inv'].'\')">'.$value['Codigo_Inv'].' '.$value['Producto'].'</a></li>';
				 	}
				 }
				}
			}
		}	

		return $h;
	}

	function TVcatalogo_Bodega($nl='',$codigo=false)
	{
		if($nl==''){$nl=1;}
		$cuenta  = $_SESSION['INGRESO']['Formato_Inventario'];
		$partes = explode('.',$cuenta);
		$len = strlen($partes[0]);
		$productos = $this->modelo->TVCatalogo_Bodega(false,'N',$len);

		$h = '';
		if($codigo=='false')
		{
		$nnl=$nl+1;
			foreach ($productos as $key => $value) {
				$hijo = $this->exite_hijo_bodega($value['CodBod']);
				 if(count($hijo)>0)
				 {
				 		$h.='<li  title="Presione Suprimir para eliminar">
							    <label id="label_'.str_replace('.','_',$value['CodBod']).'" for="'.$value['CodBod'].'">'.$value['CodBod'].' '.$value['Producto'].'</label>
							    <input type="checkbox" id="'.$value['CodBod'].'" onclick="TVcatalogo('.$nnl.',\''.$value['CodBod'].'\')" />
							   <ol id="hijos_'.str_replace('.','_',$value['CodBod']).'"></ol></li>';
				 }else
				 {

				 	$h.='<li  title="Presione Suprimir para eliminar">
							    <label id="label_'.str_replace('.','_',$value['CodBod']).'" for="'.$value['CodBod'].'">'.$value['CodBod'].' '.$value['Producto'].'</label>
							    <input type="checkbox" id="'.$value['CodBod'].'" onclick="TVcatalogo('.$nnl.',\''.$value['CodBod'].'\')" />
							   <ol id="hijos_'.str_replace('.','_',$value['CodBod']).'"></ol></li>';

							   
				 	 // $h.='<li class="file" id="label_'.str_replace('.','_',$value['Codigo_Inv']).'" title="Presione Suprimir para eliminar"><a href="">'.$value['Codigo_Inv'].' '.$value['Producto'].'</a></li>';

				 	 // $h.='<li class="file" id="label_'.str_replace('.','_',$value['Codigo_Inv']).'" title="Presione Suprimir para eliminar" ><a href="#" onclick="detalle('.$nnl.',\''.$value['Codigo_Inv'].'\')">'.$value['Codigo_Inv'].' '.$value['Producto'].'</a></li>';
				 }
			}

		}else
		{
				// print_r($codigo);
			$datos =  $this->exite_hijo_bodega($codigo);
			// print_r($datos);
			 // print_r($nl); die();
			$nnl=$nl+1;
			foreach ($datos as $key => $value) {

				// print_r($value);die();

			// print_r(count(explode('.', $value['Codigo_Inv'])));
			// print_r($nnl);
			// die();
				if(count(explode('.', $value['CodBod']))==$nl)
				{
				$hijo = $this->exite_hijo_bodega($value['CodBod']);
				 if(count($hijo)>0)
				 {
				 		$h.='<li title="Presione Suprimir para eliminar" >
							    <label id="label_'.str_replace('.','_',$value['CodBod']).'" for="'.str_replace('.','_',$value['CodBod']).'">'.$value['CodBod'].' '.$value['Producto'].'</label>
							    <input type="checkbox" id="'.str_replace('.','_',$value['CodBod']).'" onclick="TVcatalogo('.$nnl.',\''.$value['CodBod'].'\')" />
							   <ol id="hijos_'.str_replace('.','_',$value['CodBod']).'"></ol></li>';
				 }else
				 {
				 	 if($value['TC']=='I')
				 	 {
				 	 	$h.='<li>
							    <label id="label_'.str_replace('.','_',$value['CodBod']).'" for="'.str_replace('.','_',$value['CodBod']).'">'.$value['CodBod'].' '.$value['Producto'].'</label>
							    <input type="checkbox" id="'.str_replace('.','_',$value['CodBod']).'" onclick="TVcatalogo('.$nnl.',\''.$value['CodBod'].'\')" />
							   <ol id="hijos_'.str_replace('.','_',$value['CodBod']).'"></ol></li>';

				 	 }else{
				 	 $h.='<li class="file" id="label_'.str_replace('.','_',$value['CodBod']).'" title="Presione Suprimir para eliminar" ><a href="#" onclick="detalle('.$nnl.',\''.$value['CodBod'].'\')">'.$value['CodBod'].' '.$value['Producto'].'</a></li>';
				 	}
				 }
				}
			}
		}	

		return $h;
	}
	function exite_hijo($codigo)
	{
		$productos = $this->modelo->TVCatalogo($codigo);
		return $productos;
	}
	function exite_hijo_bodega($codigo)
	{
		$productos = $this->modelo->TVcatalogo_Bodega($codigo);
		return $productos;
	}
	function LlenarInv($parametros)
	{
		 $codigo = $parametros['codigo'];
		 $detalle = $this->modelo->TVCatalogo($query=false,$TC=false,$len=false,$codigo);
		 return $detalle;		
	}
	function LlenarInvBod($parametros)
	{
		 $codigo = $parametros['codigo'];
		 $detalle = $this->modelo->TVcatalogo_Bodega($query=false,$TC=false,$len=false,$codigo);
		 return $detalle;		
	}

	function guardarINV($parametros)
	{
		// print_r($parametros);die();
		if(substr($parametros['txt_codigo'],-1)=='.'){ $parametros['txt_codigo'] = substr($parametros['txt_codigo'],0,-1);}
		$codigoInv = $this->modelo->TVCatalogo($query=false,$TC=false,$len=false,$codigo=$parametros['txt_codigo']);
	 	
	 	SetAdoAddNew("Catalogo_Productos");
	 	SetAdoFields("Codigo_Inv", $parametros['txt_codigo']);
		SetAdoFields("Producto", $parametros['txt_concepto']);
		SetAdoFields("TC", $parametros['cbx_tipo']);
		SetAdoFields("Unidad", $parametros['txt_unidad']);
		SetAdoFields("Maximo", $parametros['maximo']);
		SetAdoFields("Minimo", $parametros['minimo']);
		//SetAdoFields("Gramaje", $parametros['txt_gramaje']);
		SetAdoFields("PVP", $parametros['pvp']);
		SetAdoFields("PVP_2", $parametros['pvp2']);
		SetAdoFields("PVP_3", $parametros['pvp3']);
		//SetAdoFields("Marca", $parametros['txt_marca']);
		//SetAdoFields("Reg_Sanitario", $parametros['txt_reg_sanitario']);
		//SetAdoFields("Codigo_IESS", $parametros['txt_iess']);
		//SetAdoFields("Codigo_RES", $parametros['txt_codres']);
		//SetAdoFields("Codigo_Barra", $parametros['txt_barras']);
		SetAdoFields("Cta_Inventario", $parametros['cta_inventario']);
		SetAdoFields("Cta_Costo_Venta", $parametros['cta_costo_venta']);
		SetAdoFields("Cta_Ventas", $parametros['cta_venta']);
		SetAdoFields("Cta_Ventas_0", $parametros['cta_tarifa_0']);
		SetAdoFields("Cta_Ventas_Ant", $parametros['cta_venta_anterior']);
		SetAdoFields("Cta_Ventas_Anticipadas", '.');
		SetAdoFields("Detalle", '');
		//SetAdoFields("PX", $parametros['txt_posx']);
		//SetAdoFields("PY", $parametros['txt_posy']);
		SetAdoFields("Item_Banco", $parametros['txt_codbanco']);
		SetAdoFields("Desc_Item", $parametros['txt_descripcion']);
		SetAdoFields("Utilidad", $parametros['txt_utilidad']);
		SetAdoFields("Ayuda", $parametros['txt_formula']);
		SetAdoFields("Ubicacion", $parametros['txt_ubicacion']);
		SetAdoFields("Periodo", $_SESSION['INGRESO']['periodo']);
		SetAdoFields("Item", $_SESSION['INGRESO']['item']);
		SetAdoFields("IVA", isset($parametros['rbl_iva']) && $parametros['rbl_iva'] == 'on' ? 1 : 0);
		SetAdoFields("INV", isset($parametros['rbl_inv']) && $parametros['rbl_inv'] == 'on' ? 1 : 0);
		SetAdoFields("Div", isset($parametros['cbx_calcular']) && $parametros['cbx_calcular'] == 'div' ? 1 : 0);
		SetAdoFields("Agrupacion", isset($parametros['rbl_agrupacion']) && $parametros['rbl_agrupacion'] == 'on' ? 1 : 0);
		SetAdoFields("Por_Reservas", isset($parametros['rbl_reserva']) && $parametros['rbl_reserva'] == 'on' ? 1 : 0);

		if(count($codigoInv)>0)
	  {	  	
      SetAdoFieldsWhere("ID", $codigoInv[0]['ID']);
      return SetAdoUpdateGeneric();
		}else{
			return SetAdoUpdate();
		}
	}

	function guardarINVBod($parametros)
	{
		// print_r($parametros);die();
		if(substr($parametros['txt_codigo'],-1)=='.'){ $parametros['txt_codigo'] = substr($parametros['txt_codigo'],0,-1);}
		$codigoInv = $this->modelo->TVcatalogo_Bodega($query=false,$TC=false,$len=false,$codigo=$parametros['txt_codigo']);
	 	
	 	SetAdoAddNew("Catalogo_Bodegas");
	 	SetAdoFields("CodBod", $parametros['txt_codigo']);
		SetAdoFields("Bodega", $parametros['txt_concepto']);
		SetAdoFields("TC", 'N');
		/*SetAdoFields("Unidad", $parametros['txt_unidad']);
		SetAdoFields("Maximo", $parametros['maximo']);
		SetAdoFields("Minimo", $parametros['minimo']);
		SetAdoFields("Gramaje", $parametros['txt_gramaje']);
		SetAdoFields("PVP", $parametros['pvp']);
		SetAdoFields("PVP_2", $parametros['pvp2']);
		SetAdoFields("PVP_3", $parametros['pvp3']);
		SetAdoFields("Marca", $parametros['txt_marca']);
		SetAdoFields("Reg_Sanitario", $parametros['txt_reg_sanitario']);
		SetAdoFields("Codigo_IESS", $parametros['txt_iess']);
		SetAdoFields("Codigo_RES", $parametros['txt_codres']);
		SetAdoFields("Codigo_Barra", $parametros['txt_barras']);
		SetAdoFields("Cta_Inventario", $parametros['cta_inventario']);
		SetAdoFields("Cta_Costo_Venta", $parametros['cta_costo_venta']);
		SetAdoFields("Cta_Ventas", $parametros['cta_venta']);
		SetAdoFields("Cta_Ventas_0", $parametros['cta_tarifa_0']);
		SetAdoFields("Cta_Ventas_Ant", $parametros['cta_venta_anterior']);
		SetAdoFields("Cta_Ventas_Anticipadas", '.');
		SetAdoFields("Detalle", '');
		SetAdoFields("PX", $parametros['txt_posx']);
		SetAdoFields("PY", $parametros['txt_posy']);
		SetAdoFields("Item_Banco", $parametros['txt_codbanco']);
		SetAdoFields("Desc_Item", $parametros['txt_descripcion']);
		SetAdoFields("Utilidad", $parametros['txt_utilidad']);
		SetAdoFields("Ayuda", $parametros['txt_formula']);
		SetAdoFields("Ubicacion", $parametros['txt_ubicacion']);
		SetAdoFields("Periodo", $_SESSION['INGRESO']['periodo']);
		SetAdoFields("Item", $_SESSION['INGRESO']['item']);
		SetAdoFields("IVA", isset($parametros['rbl_iva']) && $parametros['rbl_iva'] == 'on' ? 1 : 0);
		SetAdoFields("INV", isset($parametros['rbl_inv']) && $parametros['rbl_inv'] == 'on' ? 1 : 0);
		SetAdoFields("Div", isset($parametros['cbx_calcular']) && $parametros['cbx_calcular'] == 'div' ? 1 : 0);
		SetAdoFields("Agrupacion", isset($parametros['rbl_agrupacion']) && $parametros['rbl_agrupacion'] == 'on' ? 1 : 0);
		SetAdoFields("Por_Reservas", isset($parametros['rbl_reserva']) && $parametros['rbl_reserva'] == 'on' ? 1 : 0);
*/
		if(count($codigoInv)>0)
	  {	  	
      SetAdoFieldsWhere("ID", $codigoInv[0]['ID']);
      return SetAdoUpdateGeneric();
		}else{
			return SetAdoUpdate();
		}
	}

	function cod_barras($codigo,$cant)
	{
		$codigoInv = $this->modelo->TVCatalogo($query=false,$TC=false,$len=false,$codigo=$codigo);
		if(count($codigoInv)>0)
		{
			$this->barras->generar_barras($cant,$codigoInv[0]);
		}
	 
		// print_r($parametros);die();
	}

	function cod_barras_grupo($codigo)
	{
		$hijos = $this->exite_hijo($codigo);
		if(count($hijos)>0)
		{			
				$this->barras->generar_barras_grupo($hijos);
	  }else
	  {
	  	return -1;
	  }
	 
		// print_r($parametros);die();
	}

	function eliminarINV($codigo)
	{
		// $codigoInv = $this->modelo->TVCatalogo($query=$codigo,$TC=false,$len=false,$codigo=false);
		// if(count($codigoInv)>0)
		// {			
		// 	return -1;
		// }else
		// {
			$tk = $this->modelo->trans_kardex($codigo);
			// print_r($tk);
			if(count($tk)>0)
			{
				return -1;

			}else
			{
				$df =  $this->modelo->detalle_factura($codigo);
				// print_r($df);die();
				if(count($df)>0)
				{
					return -1;
				}else
				{
					return  $this->modelo->eliminar_cuenta($codigo);
				}
			}
		// }
	 
	}
	function eliminarINVBod($codigo)
	{
		// $codigoInv = $this->modelo->TVCatalogo($query=$codigo,$TC=false,$len=false,$codigo=false);
		// if(count($codigoInv)>0)
		// {			
		// 	return -1;
		// }else
		// {
			$tk = $this->modelo->trans_kardex_Bod($codigo);
			// print_r($tk);
			if(count($tk)>0)
			{
				return -1;

			}else
			{
				$df =  $this->modelo->detalle_factura_Bod($codigo);
				// print_r($df);die();
				if(count($df)>0)
				{
					return -1;
				}else
				{
					return  $this->modelo->eliminar_cuenta_bod($codigo);
				}
			}
		// }
	 
	}
}

?>