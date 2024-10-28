<?php 
require_once(dirname(__DIR__,2)."/modelo/inventario/almacenamiento_bodegaM.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");


/* para T cuando sea 
	
	E = Esta almacenado;
*/
$controlador = new almacenamiento_bodegaC();

if(isset($_GET['search_contabilizado']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar_contabilizado($query));

}
if(isset($_GET['asignar_bodega']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->asignar_bodega($parametros));
}
if(isset($_GET['desasignar_bodega']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->desasignar_bodega($parametros));
}
if(isset($_GET['lineas_pedido']))
{
	$parametros= $_POST['parametros'];	
	echo json_encode($controlador->cargar_productos($parametros));
}
if(isset($_GET['contenido_bodega']))
{
	$parametros= $_POST['parametros'];	
	echo json_encode($controlador->contenido_bodega($parametros));
}
if(isset($_GET['productos_asignados']))
{
	$parametros= $_POST['parametros'];	
	echo json_encode($controlador->productos_asignados($parametros));
}
if(isset($_GET['eliminar_bodega']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->eliminar_bodega($parametros));
}
if(isset($_GET['cargar_info']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_info($parametros));
}
// if(isset($_GET['autocom_pro']))
// {
// 	$query = '';
// 	if(isset($_GET['q']))
// 	{
// 		$query = $_GET['q'];
// 	}
// 	echo json_encode($controlador->autocomplet_producto($query));
// }
// if(isset($_GET['autocom_pro2']))
// {
// 	$query = '';
// 	if(isset($_GET['q']))
// 	{
// 		$query = $_GET['q'];
// 	}
// 	echo json_encode($controlador->autocomplet_producto2($query));
// }

if(isset($_GET['cargar_lugar']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_lugar($parametros));
}

if(isset($_GET['cargar_empaques']))
{
	// $parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_empaques());
}
// if(isset($_GET['editar_precio']))
// {
// 	$parametros = $_POST['parametros'];
// 	echo json_encode($controlador->editar_precio($parametros));
// }
// if(isset($_GET['editar_checked']))
// {
// 	$parametros = $_POST['parametros'];
// 	echo json_encode($controlador->editar_checked($parametros));
// }
// if(isset($_GET['actualizar_trans_kardex']))
// {
// 	$parametros = $_POST['parametros'];
// 	echo json_encode($controlador->actualizar_trans_kardex($parametros));
// }
// if(isset($_GET['eli_all_pedido']))
// {
// 	$parametros = $_POST['parametros'];
// 	echo json_encode($controlador->eli_all_pedido($parametros));
// }
// if(isset($_GET['contabilizar']))
// {
// 	$parametros = $_POST;
// 	echo json_encode($controlador->contabilizar($parametros));
// }
if(isset($_GET['lista_bodegas_arbol']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_bodegas_arbol($parametros));
}
if(isset($_GET['lista_bodegas_arbol2']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_bodegas_arbol2($parametros));
}

/**
 * 
 */
class almacenamiento_bodegaC
{
	private $modelo;
	private $barras;
	function __construct()
	{
		$this->modelo = new almacenamiento_bodegaM();
	}


	function buscar_contabilizado($cod)
	{
		$datos = $this->modelo->Buscar_productos_ingresados($cod);
		$result = array();
		$color2 = '#000000';
		$color = '';
		$fecha_now = new DateTime();
		foreach ($datos as $key => $value) {
			// print_r($value);die();

		$fecha1 = new DateTime();
      	$fecha2 = new DateTime($value['Fecha_Exp']->format('Y-m-d'));
      	$diferenciaEnSegundos = $fecha2->getTimestamp() - $fecha1->getTimestamp();

		$dias = intval($diferenciaEnSegundos / 86400);
		if($value['Cod_C']=='AR01')
		{
			$color2 = '#0070C0';
		}
		if($dias<=0){$color = '#ffff00';}else if ($dias<=8 && $dias>0) { $color = '#ff0000';}
		
		 $result[] = array("id"=>$value['ID'],"text"=>$value['Codigo_Barra'],'data'=>$value,'fondo'=>$color,'texto'=>$color2);
		 $color2 = '#000000';
		 $color = '';
		}
		return $result;
	}



	function cargar_productos($parametros)
    {
    	// print_r($parametros);die();
    	// print_r($ordenes);die();
    	$datos = $this->modelo->Buscar_productos_ingresados($parametros['num_ped']);

    	// print_r($datos);die();
    	$ls='';
		foreach ($datos as $key => $value) 
		{
			$parametros['codigo'] = $value['Codigo_Inv'];
			$prod = $this->modelo->catalogo_productos($value['Codigo_Inv']);
			if(count($prod)>0){

				 $ls.= '<li class="list-group-item"><a href="#" style="padding:0px"><label><input type="checkbox" class="rbl_pedido" value="'.$value['ID'].'">'.$prod[0]['Producto'].'</label>
				 		<div class="btn-group pull-right">
				 				<span class="label-primary btn-sm btn">'.$value['Entrada'].' '.$prod[0]['Unidad'].'</span>				
				 		</div>
				 </a>
				 <ul style="padding: 20px;">';
				 		 $ls.= $this->cargar_info($parametros);
				 	$ls.='</ul> 
				 </li>';
			}
			
      }	

      	return $ls;	
    }

    function cargar_productos_trans_pedidos($parametros)
    {
    	// print_r($parametros);die();
    	// print_r($ordenes);die();
    	$datos = $this->modelo->cargar_pedidos_trans_pedidos($parametros['num_ped'],false);
    	$ls='';		
		foreach ($datos as $key => $value) 
		{
			if($value['Codigo_Sup']=='.')
			{
				$ls.= '<li><a href="#" style="padding-right:0px"><label> <!-- <input type="checkbox" class="rbl_pedido" value="'.$value['ID'].'-R" > --> '.$value['Producto'].'</label><span class="label label-danger pull-right">'.$value['Cantidad'].'</span></a></li>';
			}else{
				$ls.= '<li><a href="#" style="padding-right:0px"><label><!-- <input type="checkbox" checked disabled class="rbl_pedido" value="'.$value['ID'].'-R" > -->  '.$value['Producto'].'</label><span class="label label-danger pull-right">'.$value['Cantidad'].'</span></a></li>';
			}
		}
		
		return $ls;
    }

    function contenido_bodega($parametros)
    {
    	// print_r($parametros);die();
    	// print_r($ordenes);die();
    	$datos = $this->modelo->cargar_pedidos_trans($parametros['num_ped'],false,false,$parametros['bodega']);    	
    	$datos2 = $this->modelo->cargar_pedidos_trans_pedidos($parametros['num_ped'],false,$parametros['bodega']);

    	$ls='';
		foreach ($datos as $key => $value) 
		{			
			$ls.= '<li><a href="#" style="padding:0px"><label><input type="checkbox" class="rbl_pedido_des" value="'.$value['ID'].'">  '.$value['Producto'].'</label><span class="label label-primary pull-right">'.$value['Entrada'].'</span></a></li>';
				
      }	
      foreach ($datos2 as $key => $value) 
		{			
			$ls.= '<li><a href="#" style="padding:0px"><label><input type="checkbox" class="rbl_pedido_des" value="'.$value['ID'].'-R">  '.$value['Producto'].'</label><span class="label label-primary pull-right">'.$value['Cantidad'].'</span></a></li>';
				
      }	

      	return $ls;	
    }

    function productos_asignados($parametros)
    {
    	// print_r($parametros);die();
    	// print_r($ordenes);die();
    	$bodega = $parametros['bodegas'];
    	$datos = $this->modelo->cargar_agregado_en_bodega(false,false,false,$bodega);
    	// print_r($datos);die();
    	$ls='';		
		foreach ($datos as $key => $value) 
		{
			$stock = 0;
			$dato_inv = Leer_Codigo_Inv($value['Codigo_Inv'],date('Y-m-d'));
			if($dato_inv['respueta']==1)
			{
				$stock = $dato_inv['datos']['Stock'].' '.$dato_inv['datos']['Unidad'];
			}
			// print_r($dato_inv);die();
			if($value['CodBodega']!='.' && $value['CodBodega']!='-1')
			{
				$ruta = $this->ruta_bodega($value['CodBodega']);
				$ls.= '<tr>
					<td>'.$value['Producto'].'</td>
					<td>'.$stock.'</td>
					<td>'.$ruta.'</td>
					<td>
						<button type="button" onclick="eliminar_bodega(\''.$value['ID'].'\')" class="btn btn-danger btn-sm" title="Eliminar Bodega"><i class="fa fa-trash"></i></button>
						<button type="button" onclick="$(\'#txt_cod_bodega\').val(\''.$value['CodBodega'].'\');$(\'#txt_bodega_title\').text(\''.$ruta.'\');contenido_bodega()" class="btn btn-primary btn-sm" title="Ver Bodega" ><i class="fa fa-eye"></i></button>
					</td>
				</tr>';	

			}
		}
		
		return $ls;
    }

  

	function asignar_bodega($parametros)
	{
		$id = substr($parametros['id'],0,-1);
		$id = explode(',',$id);
		foreach ($id as $key => $value) {
			$tipo = explode('-', $value);
			if(count($tipo)>1)
			{
				SetAdoAddNew('Trans_Pedidos');
				SetAdoFields('Codigo_Sup',$parametros['bodegas']);		
				SetAdoFieldsWhere('ID',$tipo[0]);	
				SetAdoFieldsWhere('TC',"E");
				SetAdoUpdateGeneric();
				//a transpedidos
			}else{
				// a transkardex
				// print_r($value);die();
				SetAdoAddNew('Trans_Kardex');
				SetAdoFields('CodBodega',$parametros['bodegas']);	
				SetAdoFields('Fecha_DUI',date('Y-m-d'));	
				SetAdoFields('T',"E");		
				SetAdoFieldsWhere('ID',$value);
				SetAdoUpdateGeneric();
			}
		}
		return 1;
	}

	function desasignar_bodega($parametros)
	{
		$id = substr($parametros['id'],0,-1);
		$id = explode(',',$id);
		foreach ($id as $key => $value) {
			$tipo = explode('-', $value);
			if(count($tipo)>1)
			{
				SetAdoAddNew('Trans_Pedidos');
				SetAdoFields('Codigo_Sup','.');		
				SetAdoFieldsWhere('ID',$tipo[0]);
				SetAdoUpdateGeneric();
				//a transpedidos
			}else{
				// a transkardex
				// print_r($parametros);die();
				SetAdoAddNew('Trans_Kardex');
				SetAdoFields('CodBodega','-1');		
				SetAdoFieldsWhere('ID',$value);
				SetAdoUpdateGeneric();
			}
		}
		return 1;
	}
	function eliminar_bodega($parametros)
	{

		SetAdoAddNew('Trans_Kardex');
		SetAdoFields('CodBodega','-1');		
		SetAdoFieldsWhere('ID',$parametros['id']);
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


		// print_r($nivel_solicitado);die();

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
				$ruta = $this->ruta_bodega($prefijo);
				if($hijos==1)
				{
					$html.='<li>
						       <input type="checkbox" id="c'.$prefijo.'" />
						       <label class="tree_bod_label" for="c'.$prefijo.'" id="c_'.$prefijo.'" onclick="cargar_bodegas(\''.($nivel_solicitado+1).'\',\''.$prefijo.'\');cargar_nombre_bodega(\''.$ruta.'\',\'.\',\''.$nivel_solicitado.'\')">'.$value['Bodega'].'</label>
						       	<ul id="h'.$prefijo.'">
						       	</ul>
					       	</li>';
					$hijos=0;
				}else
				{
					$html.='<li><span class="tree_bod_label" id="c_'.str_replace('.','_',$prefijo).'" onclick="cargar_nombre_bodega(\''.$ruta.'\',\''.$value['CodBod'].'\',\''.$nivel_solicitado.'\')">'.$value['Bodega'].'</span></li>';
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
					$ruta = $this->ruta_bodega($prefijo);
					
					if (substr($value['CodBod'], 0, strlen($padre)) === $padre) {
						// print_r('padre');die();
					if($hijos==1)
					{
						// print_r($value2['CodBod'].'-'.$value['Bodega']);die();
						$html.='<li>
							       <input type="checkbox" id="c'.str_replace('.','_',$prefijo).'" />
							       <label class="tree_bod_label" for="c'.str_replace('.','_',$prefijo).'" id="c_'.str_replace('.','_',$prefijo).'" onclick="cargar_bodegas(\''.($nivel_solicitado+1).'\',\''.str_replace('.','_',$prefijo).'\');cargar_nombre_bodega(\''.$ruta.'\',\'.\',\''.$nivel_solicitado.'\')">'.$value['Bodega'].'</label>
							       	<ul id="h'.str_replace('.','_',$prefijo).'">
							       	</ul>
						       	</li>';
						$hijos=0;
					}else
					{
						$html.='<li><span class="tree_bod_label" id="c_'.str_replace('.','_',$prefijo).'" onclick="cargar_nombre_bodega(\''.$ruta.'\',\''.$value['CodBod'].'\',\''.$nivel_solicitado.'\')">'.$value['Bodega'].'</span></li>';
					}
				}
				

			}
			
		}

		return $html;

	}

	function lista_bodegas_arbol2($parametros)
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


		// print_r($nivel_solicitado);die();

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
				$ruta = $this->ruta_bodega($prefijo);
				if($hijos==1)
				{
					$html.='<li>
						       <input type="checkbox" id="c'.$prefijo.'" />
						       <label class="tree_bod_label" for="c'.$prefijo.'" id="c_'.$prefijo.'" onclick="cargar_bodegas2(\''.($nivel_solicitado+1).'\',\''.$prefijo.'\');cargar_nombre_bodega2(\''.$ruta.'\',\'.\',\''.$nivel_solicitado.'\')">'.$value['Bodega'].'</label>
						       	<ul id="h'.$prefijo.'">
						       	</ul>
					       	</li>';
					$hijos=0;
				}else
				{
					$html.='<li><span class="tree_bod_label" id="c_'.str_replace('.','_',$prefijo).'" onclick="cargar_nombre_bodega2(\''.$ruta.'\',\''.$value['CodBod'].'\',\''.$nivel_solicitado.'\')">'.$value['Bodega'].'</span></li>';
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
					$ruta = $this->ruta_bodega($prefijo);
					
					if (substr($value['CodBod'], 0, strlen($padre)) === $padre) {
						// print_r('padre');die();
					if($hijos==1)
					{
						// print_r($value2['CodBod'].'-'.$value['Bodega']);die();
						$html.='<li>
							       <input type="checkbox" id="c'.str_replace('.','_',$prefijo).'" />
							       <label class="tree_bod_label" for="c'.str_replace('.','_',$prefijo).'" id="c_'.str_replace('.','_',$prefijo).'" onclick="cargar_bodegas2(\''.($nivel_solicitado+1).'\',\''.str_replace('.','_',$prefijo).'\');cargar_nombre_bodega2(\''.$ruta.'\',\'.\',\''.$nivel_solicitado.'\')">'.$value['Bodega'].'</label>
							       	<ul id="h'.str_replace('.','_',$prefijo).'">
							       	</ul>
						       	</li>';
						$hijos=0;
					}else
					{
						$html.='<li><span class="tree_bod_label" id="c_'.str_replace('.','_',$prefijo).'" onclick="cargar_nombre_bodega2(\''.$ruta.'\',\''.$value['CodBod'].'\',\''.$nivel_solicitado.'\')">'.$value['Bodega'].'</span></li>';
					}
				}
				

			}
			
		}

		return $html;

	}


	function ruta_bodega($padre)
	{
		$datos = explode('.',$padre);
		$camino = '';
		$buscar = '';
		foreach ($datos as $key => $value) {
			$camino.= $value.'.';
			$buscar.= "'".substr($camino, 0,-1)."',";
		}

		$buscar = substr($buscar, 0,-1);
		$pasos = $this->modelo->ruta_bodega_select($buscar);
		$ruta = '';
		foreach ($pasos as $key => $value) {
			$ruta.=$value['Bodega'].'/';			
		}
		$ruta = substr($ruta,0,-1);
		return $ruta;
	}

	function cargar_info($parametros)
	{
		$datos = $this->modelo->catalogo_productos($parametros['codigo']);
		// print_r($datos);die();
		$li = '';
		if(count($datos)>0)
		{
			$categorias = $datos[0]['Categorias'];
			if($categorias!='.')
			{
				$datos = $this->modelo->cargar_categorias($categorias);
				foreach ($datos as $key => $value) {
					if($value['DC']=='C')
					{
						$li.="<li><u>".$value['Proceso']."</u></li>";
					}else
					{					
						$li.="<li><i class='fa fa-arrow-right'></i> ".$value['Proceso']."</li>";
					}
					// print_r($value);die();
				}
			}else
			{
				$li.="<li><i class='fa fa-circle-info'></i> Sin categorias asignadas</li>";
			}
		}

		return $li;
		// print_r($datos);die();
	}

	function cargar_lugar($parametros)
	{
		// print_r($parametros);die();
		$codigo = explode('.',$parametros['codigo']);
		$ruta = '';
		$pasos = '';
		foreach ($codigo as $key => $value) {
			$pasos.=$value.'.';
			$pasos2 = substr($pasos,0,-1);
			$ruta.= "'".$pasos2."',";
		}
		$ruta = substr($ruta,0,-1);
		$patch = $this->modelo->ruta_bodega_select($ruta);
		$url = '';
		foreach ($patch as $key => $value) {
			$url.=$value['Bodega'].'/';
		}
		$url = substr($url,0,-1);
		return $url;
	}

	function cargar_empaques()
	{
		$datos = $this->modelo->cargar_empaques();
		return $datos;
	}
}

?>