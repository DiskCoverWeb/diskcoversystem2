<?php
require_once(dirname(__DIR__,2).'/modelo/inventario/solicitud_materialM.php');
require_once(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
require_once(dirname(__DIR__,3).'/lib/phpmailer/enviar_emails.php');

$controlador = new solicitud_materialC();

if(isset($_GET['productos']))
{
	$query = '';$fami = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	if(isset($_GET['fami']))
	{
		$fami = $_GET['fami'];
	}
	echo json_encode($controlador->autocomplet_producto($fami,$query));
}

if(isset($_GET['familia']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->autocomplet_familia($query));
}

if(isset($_GET['marca']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->autocomplet_marca($query));
}
if(isset($_GET['guardar_marca']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_marca($parametros));
}

if(isset($_GET['guardar_linea']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_linea($parametros));
}
if(isset($_GET['linea_pedido']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->linea_pedido($parametros));
}

if(isset($_GET['eliminar_linea']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->eliminar_linea($parametros));
}

if(isset($_GET['grabar_solicitud']))
{
	echo json_encode($controlador->grabar_solicitud());
}

if(isset($_GET['pedidos_contratista']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->pedidos_contratista($parametros));
}
if(isset($_GET['EliminarSolicitud']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->EliminarSolicitud($parametros));
}


if(isset($_GET['envio_pedidos_contratista']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->envio_pedidos_contratista($parametros));
}


if(isset($_GET['pedidos_solicitados']))
{
	$query = $_POST['parametros'];
	
	echo json_encode($controlador->pedidos_solicitados($query));
}

if(isset($_GET['guardar_linea_aprobacion']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_linea_aprobacion($parametros));
}
if(isset($_GET['lineas_pedido_solicitados']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lineas_pedido_solicitados($parametros));
}

if(isset($_GET['grabar_solicitud_proveedor']))
{
	$parametros = $_POST['parametros'];
	// print_r($parametros['aprobacion']);die();
	echo json_encode($controlador->grabar_solicitud_proveedor($parametros));
}

if(isset($_GET['pedido_solicitados_proveedor']))
{
	$query = '';
	$parametros = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	if(isset($_POST['parametros']))
	{
		$parametros = $_POST['parametros'];
	}
	echo json_encode($controlador->pedido_solicitados_proveedor($query,$parametros));
}


if(isset($_GET['lineas_pedido_solicitados_proveedor']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lineas_pedido_solicitados_proveedor($parametros));
}
if(isset($_GET['lista_proveedores']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->lista_proveedores($query));
}

if(isset($_GET['grabar_envio_solicitud']))
{
	$parametros = $_POST;
	echo json_encode($controlador->grabar_envio_solicitud($parametros));
}

if(isset($_GET['pedido_aprobacion_solicitados_proveedor']))
{
	$query = $_POST['parametros'];
	
	echo json_encode($controlador->pedido_aprobacion_solicitados_proveedor($query));
}

if(isset($_GET['lista_pedido_aprobacion_solicitados_proveedor']))
{
	$parametros = $_POST['parametros'];
	// if(isset($_POST['query']))
	// {
	// 	$query =$_POST['query'];
	// }
	echo json_encode($controlador->lista_pedido_aprobacion_solicitados_proveedor($parametros));
}



if(isset($_GET['lineas_pedido_aprobacion_solicitados_proveedor']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lineas_pedido_aprobacion_solicitados_proveedor($parametros));
}


if(isset($_GET['imprimir_pdf']))
{
	$orden = '';
	if(isset($_GET['orden_pdf']))
	{
		$orden = $_GET['orden_pdf'];
	}
	echo json_encode($controlador->imprimir_pdf($orden));
}

if(isset($_GET['imprimir_excel']))
{
	$orden = '';
	if(isset($_GET['orden_pdf']))
	{
		$orden = $_GET['orden_pdf'];
	}
	echo json_encode($controlador->imprimir_excel($orden));
}


if(isset($_GET['imprimir_pdf_envio']))
{
	$orden = '';
	if(isset($_GET['orden_pdf']))
	{
		$orden = $_GET['orden_pdf'];
	}
	echo json_encode($controlador->imprimir_pdf_envio($orden));
}
if(isset($_GET['imprimir_excel_envio']))
{
	$orden = '';
	if(isset($_GET['orden_pdf']))
	{
		$orden = $_GET['orden_pdf'];
	}
	echo json_encode($controlador->imprimir_excel_envio($orden));
}
if(isset($_GET['imprimir_pdf_proveedor']))
{
	$orden = '';
	if(isset($_GET['orden_pdf']))
	{
		$orden = $_GET['orden_pdf'];
	}
	echo json_encode($controlador->imprimir_pdf_proveedor($orden));
}
if(isset($_GET['imprimir_excel_proveedor']))
{
	$orden = '';
	if(isset($_GET['orden_pdf']))
	{
		$orden = $_GET['orden_pdf'];
	}
	echo json_encode($controlador->imprimir_excel_proveedor($orden));
}

if(isset($_GET['lista_provee']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_provee($parametros));
}

if(isset($_GET['guardar_seleccion_proveedor']))
{
	$parametros = $_POST;
	echo json_encode($controlador->guardar_seleccion_proveedor($parametros));
}

if(isset($_GET['grabar_compra_pedido']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->grabar_compra_pedido($parametros));
}

if(isset($_GET['AddProveedorExta']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->AddProveedorExta($parametros));
}
if(isset($_GET['eliminar_prove']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->eliminar_prove($parametros));
}





class solicitud_materialC
{
    private $modelo;
    private $email;

    function __construct()
    {
        $this->modelo = new solicitud_materialM();
        $this->email = new enviar_emails();
    }

    function autocomplet_producto($fami,$query)
	{
		// print_r($fami);die();
		$datos = $this->modelo->cargar_productos(false,$query);
		// print_r($datos);die();
		$productos = array();
		foreach ($datos as $key => $value) {			
			// $costo =  $this->ing_descargos->costo_venta($value['Codigo_Inv']);
			// $costoTrans = $this->ing_descargos->costo_producto($value['Codigo_Inv']);
			$codInv = explode('.',$value['Codigo_Inv']);
			$codFam = $codInv[0].'.'.$codInv[1];
			$familia = $this->modelo->cargar_familia($codFam,false);
			$value['familia']  = '';
			$value['codfamilia'] = '';
			if(count($familia)>0)
			{
				$value['familia'] = $familia[0]['Producto'];
				$value['codfamilia'] = $familia[0]['Codigo_Inv'];
			}
			// print_r($familia);die();

			$FechaInventario = date('Y-m-d');
		 	$CodBodega = '01';
		 	$costo_existencias = Leer_Codigo_Inv($value['Codigo_Inv'],$FechaInventario,$CodBodega,$CodMarca='');

			if($costo_existencias['respueta']!=1)
			{
				$value['Existencia'] = 0;
				$value['Costo'] = 0;				
			}else
			{
				$value['Existencia'] = $costo_existencias['datos']['Stock'];
				$value['Costo'] = number_format($costo_existencias['datos']['Costo'],$_SESSION['INGRESO']['Dec_PVP'],'.','');		
			}
			
			// print_r($_SESSION['INGRESO']);die();

			$productos[] = array('id'=>$value['Codigo_Inv'],'text'=>$value['Producto'],'data'=>$value);
		}

		// print_r($productos);die();
		return $productos;
		// print_r($productos);die();
	}

	function autocomplet_familia($query)
	{
		$datos = $this->modelo->cargar_familia($query);
		// print_r($datos);die();
		$productos = array();
		foreach ($datos as $key => $value) {			
			// $costo =  $this->ing_descargos->costo_venta($value['Codigo_Inv']);
			// $costoTrans = $this->ing_descargos->costo_producto($value['Codigo_Inv']);

			$FechaInventario = date('Y-m-d');
		 	$CodBodega = '01';
		 	$costo_existencias = Leer_Codigo_Inv($value['Codigo_Inv'],$FechaInventario,$CodBodega,$CodMarca='');

			if($costo_existencias['respueta']!=1)
			{
				$costo[0]['Existencia'] = 0;
				$costoTrans[0]['Costo'] = 0;				
			}else
			{
				$costo[0]['Existencia'] = $costo_existencias['datos']['Stock'];
				$costoTrans[0]['Costo'] = $costo_existencias['datos']['Costo'];		
			}
			

			$productos[] = array('id'=>$value['Codigo_Inv'],'text'=>$value['Producto'],'data'=>$value);
		}
		return $productos;
		// print_r($productos);die();
	}

	function autocomplet_marca($query)
	{
		$datos = $this->modelo->cargar_marca($query);
		// print_r($datos);die();
		$productos = array();
		foreach ($datos as $key => $value) {						

			$productos[] = array('id'=>$value['CodMar'],'text'=>$value['Marca'],'data'=>$value);
		}
		return $productos;
		// print_r($productos);die();
	}

	function guardar_linea($parametros)
	{
		// print_r($parametros);die();
		$producto = Leer_Codigo_Inv($parametros['productos'],$parametros['fecha']);
		if($producto['respueta']==1)
		{
			$articulo = $producto['datos'];
			// print_r($articulo);die();
				SetAdoAddNew("Trans_Pedidos");
		        SetAdoFields("Codigo_Inv",$parametros['productos']);
		        SetAdoFields("Fecha",$parametros['fecha']);
		        SetAdoFields("Fecha_Ent",$parametros['fechaEnt']);
		        SetAdoFields("Producto",$articulo['Producto']);
		        SetAdoFields("Cantidad",$parametros['cantidad']);        	
		        SetAdoFields("Cantidad_Total",$parametros['cantidad']);
		        SetAdoFields("Precio",$parametros['costo']);
		        SetAdoFields("TC",'P');		        
		        SetAdoFields("Total",$parametros['total']);
		        SetAdoFields("Item",$_SESSION['INGRESO']['item']);
		        SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
		        SetAdoFields("Codigo_Sup",$_SESSION['INGRESO']['CodigoU']);
		        SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
		        SetAdoFields("Comentario",$parametros['obs']);
		        SetAdoFields("CodMarca",$parametros['marca']);
				
				return SetAdoUpdate();
		}
	}

	function guardar_marca($parametros)
	{

		$numero =  ReadSetDataNum("Codigo_Marca",$ParaEmpresa=false,true,$Fecha=false);
		$num = generaCeros($numero,4);
		$CodMarca = "Marca_".$num;
		
				SetAdoAddNew("Catalogo_Marcas");
		        SetAdoFields("CodMar",$CodMarca);
		        SetAdoFields("Marca",$parametros['marca']);
		        SetAdoFields("Item",$_SESSION['INGRESO']['item']);
		        SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
				
				return SetAdoUpdate();
		
	}


	function linea_pedido($parametros)
	{
		$datos = $this->modelo->lineas_pedido($parametros['fecha']);
		$tr = '';
		$total = 0;
		foreach ($datos as $key => $value) {
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td>'.$value['Codigo_Inv'].'</td>
					<td>'.$value['Producto'].'</td>
					<td>'.$value['Cantidad_Total'].'</td>
					<td>'.$value['Unidad'].'</td>
					<td>'.$value['Precio'].'</td>
					<td>'.($value['Precio']*$value['Cantidad_Total']).'</td>
					<td>'.$value['Marca'].'</td>
					<td>'.$value['Fecha']->format('Y-m-d').'</td>
					<td>'.$value['Fecha_Ent']->format('Y-m-d').'</td>
					<td>'.$value['Comentario'].'</td>
					<td>
						<button class="btn btn-sm btn-danger" onclick="eliminar_linea(\''.$value['ID'].'\')"><i class="fa fa fa-trash me-0"></i></button>
					</td>
				</tr>';
				$total+=$value['Total'];
		}

		$tr.='<tr><td colspan="5"></td><td><b>TOTAL</b></td><td><b>'.$total.'</b></td><td></td><td></td><td></td></tr>';


		return $tr;
	}

	function pedidos_contratista($parametros)
	{
		$datos = $this->modelo->pedidos_contratista(false,false,$parametros['fecha'],$parametros['query']);
		$tr = '';
		$total = 0;
		foreach ($datos as $key => $value) {
			$tr.='<tr>
					<td><button class="btn btn-danger btn-sm" onclick="eliminar_solicitud(\''.$value['Orden_No'].'\')"><i class="fa fa-trash me-0"></i></button></td>
					<td>'.($key+1).'</td>
					<td><a href="inicio.php?mod='.$_SESSION['INGRESO']['modulo_'].'&acc=aprobacion_solicitud&orden='.$value['Orden_No'].'">'.$value['Cliente'].'</a></td>
					<td>'.$value['Orden_No'].'</td>					
					<td>'.$value['Fecha']->format('Y-m-d').'</td>
					<td>'.$value['Total'].'</td>					
					<td>
						<button type="button" class="btn btn-sm btn-default" onclick="imprimir_pdf(\''.$value['Orden_No'].'\')" title="Descargar detalle pedido pdf"><i class="fa fa-file-pdf"></i></butto>
						<button type="button" class="btn btn-sm btn-default" onclick="imprimir_excel(\''.$value['Orden_No'].'\')" title="Descargar detalle pedido excel"><i class="fa fa-file-excel"></i></butto>
					</td>					
				</tr>';
				$total+=$value['Total'];
		}
		return $tr;
	}

	function EliminarSolicitud($parametros)
	{
		$tc = false;
		if(isset($parametros['tipo'])){$tc = $parametros['tipo'];}
		return $this->modelo->EliminarSolicitud($parametros['orden'],$tc);
	}



	function eliminar_linea($parametros)
	{

		return $this->modelo->eliminar_linea($parametros['id']);
		print_r($parametros);die();
	}   

	function grabar_solicitud()
	{

		// print_r($_SESSION['INGRESO']);die();
		// $cod_orde = $this->modelo->
		$codigo = ReadSetDataNum("PC_SERIE_001001", false, True);

		$codigo = "PC_SERIE_001001_".$codigo;
		// print_r($codigo);die();
		$datos = $this->modelo->lineas_pedido();

		foreach ($datos as $key => $value) {

			SetAdoAddNew("Trans_Pedidos");          
        	SetAdoFields("Orden_No",$codigo);       
        	SetAdoFields("Fecha",date('Y-m-d'));     
        	SetAdoFields("TC",'S');

        	SetAdoFieldsWhere('ID',$value['ID']);
        	SetAdoUpdateGeneric();
		}
		return 1;		
	}


	function imprimir_pdf($orden)
	{

		$datos_pedido = $this->modelo-> pedidos_contratista($orden);

		$ali =array("L","L");
		$medi =array(40,130);
		$pdf = new cabecera_pdf();  
		$titulo = "R E Q U I S I C I O N   D E   M A T E R I A L";
		$mostrar = true;
		$sizetable =7;
		$tablaHTML = array();
		$tablaHTML[0]['medidas']=$medi;
		$tablaHTML[0]['alineado']=$ali;
		$tablaHTML[0]['datos']= array('<b>Numero:',$datos_pedido[0]['Orden_No']);
		$tablaHTML[1]['medidas']=$medi;
		$tablaHTML[1]['alineado']=$ali;
		$tablaHTML[1]['datos']= array('<b>Contratista:',$datos_pedido[0]['Cliente']);
		$tablaHTML[2]['medidas']=$medi;
		$tablaHTML[2]['alineado']=$ali;
		$tablaHTML[2]['datos']= array('<b>Precio Referencial Total:',$datos_pedido[0]['Total']);
		


		$datos_lineas = $this->modelo->lineas_pedido_solicitados($orden);
		$tablaHTML[3]['medidas']=array(8,40,20,80,25,30,25,20,15,15,20);
		$tablaHTML[3]['alineado']=array('C','L','L','L','L','L','L','R','R','R');
		$tablaHTML[3]['datos']=array('','FAMILIA','CODIGO','ITEM','MARCAS','OBSERVACION','FECHA ENTREGA','ULTIMO PRECIO','CANTIDAD','TOTAL');
		$tablaHTML[3]['estilo']='B';
		$tablaHTML[3]['borde'] = '1';
		// print_r($datos_lineas);die();

		$pos = 4;
		foreach ($datos_lineas as $key => $value) {

			// print_r($value);die();

			$codigo = $value['Codigo_Inv'];
			$resp = true;
			while ($resp) {
				$posicion = strrpos($codigo, '.');
				// Verificar que el punto fue encontrado
				if ($posicion !== false) {
				    $códigoModificado = substr($codigo, 0, $posicion);
				    $fami = $this->modelo->buscar_familia($códigoModificado);
				    if(count($fami)==0){$codigo = $códigoModificado; }else{$resp = false;}
				}
			}
			
			// print_r($fami);die();
		  $tablaHTML[$pos]['medidas']=$tablaHTML[3]['medidas'];
		  $tablaHTML[$pos]['alineado']=$tablaHTML[3]['alineado'];
		  $tablaHTML[$pos]['datos']=array($key+1,$fami[0]['Producto'],$value['Codigo_Inv'],$value['Producto'],$value['Marca'],$value['Comentario'],$value['Fecha']->format('Y-m-d'),$value['Precio'],$value['Cantidad'],$value['Total']);
		  $tablaHTML[$pos]['estilo']='I';
		  $tablaHTML[$pos]['borde'] = '1';
		  $pos = $pos+1;
		}
		$pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,"","",$sizetable,$mostrar, orientacion: 'L');

	}

	function imprimir_excel($orden)
	{

		$datos_pedido = $this->modelo-> pedidos_contratista($orden);

		$ali =array("L","L");
		$medi =array(10,40,130,10);
		$pdf = new cabecera_pdf();  
		$titulo = "R E Q U I S I C I O N   D E   M A T E R I A L";
		$mostrar = true;
		$sizetable =7;
		$tablaHTML = array();
		$tablaHTML[0]['medidas']=$medi;
		$tablaHTML[0]['datos']= array('Numero:','',$datos_pedido[0]['Orden_No'],'');
		$tablaHTML[0]['tipo']= 'B';	
		$tablaHTML[0]['unir']= array('AB','CDEFGHI');
		$tablaHTML[0]['col-total'] = 10;

		$tablaHTML[1]['medidas']=$medi;
		$tablaHTML[1]['datos']= array('Contratista:','',$datos_pedido[0]['Cliente'],'');
		$tablaHTML[1]['tipo']= 'B';	
		$tablaHTML[1]['unir']= array('AB','CDEFGHI');

		$tablaHTML[2]['medidas']=$medi;
		$tablaHTML[2]['datos']= array('Precio Referencial Total:','',$datos_pedido[0]['Total'],'');
		$tablaHTML[2]['tipo']= 'B';	
		$tablaHTML[2]['unir']= array('AB','CDEFGHI');
		


		$datos_lineas = $this->modelo->lineas_pedido_solicitados($orden);
		$tablaHTML[3]['medidas']=array(8,40,20,80,25,30,25,20,15,15,20);
		$tablaHTML[3]['datos']=array('','FAMILIA','CODIGO','ITEM','MARCAS','OBSERVACION','FECHA ENTREGA','ULTIMO PRECIO','CANTIDAD','TOTAL');
		$tablaHTML[3]['tipo']= 'SUB';	
		// print_r($datos_lineas);die();

		$pos = 4;
		foreach ($datos_lineas as $key => $value) {

			// print_r($value);die();

			$codigo = $value['Codigo_Inv'];
			$resp = true;
			while ($resp) {
				$posicion = strrpos($codigo, '.');
				// Verificar que el punto fue encontrado
				if ($posicion !== false) {
				    $códigoModificado = substr($codigo, 0, $posicion);
				    $fami = $this->modelo->buscar_familia($códigoModificado);
				    if(count($fami)==0){$codigo = $códigoModificado; }else{$resp = false;}
				}
			}
			
			// print_r($fami);die();
		  $tablaHTML[$pos]['medidas']=$tablaHTML[3]['medidas'];
		  $tablaHTML[$pos]['datos']=array($key+1,$fami[0]['Producto'],$value['Codigo_Inv'],$value['Producto'],$value['Marca'],$value['Comentario'],$value['Fecha']->format('Y-m-d'),$value['Precio'],$value['Cantidad'],$value['Total']);
		  $pos = $pos+1;
		}

	    excel_generico($titulo,$tablaHTML);

	}


	//---------------------------------------------------aprobacion de solicitud-----------------------------------------------------
	function pedidos_solicitados($parametros)
	{
		$datos = $this->modelo-> pedidos_contratista($parametros['orden']);
		return $datos;
		// print_r($datos);die();
	}


	function  lineas_pedido_solicitados($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->lineas_pedido_solicitados($parametros['orden']);

		$tr = '';
		foreach ($datos as $key => $value) {
			$producto_inv = Leer_Codigo_Inv($value['Codigo_Inv'],"");
			$Stock = 0;
			if($producto_inv['respueta']==1)
			{
				$Stock = $producto_inv['datos']['Stock'];
			}
			$tr.='<tr>
					<td>
						<button type="button" class="btn btn-sm btn-primary" onclick="guardar_linea_aprobacion(\''.$value['ID'].'\')" title="Guardar Cantidad" ><i class="fa fa fa-save me-0"></i></button>
						<button type="button" class="btn btn-sm btn-danger" onclick="eliminar_linea(\''.$value['ID'].'\')" title="Eliminar linea" ><i class="fa fa fa-trash me-0"></i></button>
						
					</td>
					<td><input type="checkbox" name="rbl_a_'.$value['ID'].'"  id="rbl_a_'.$value['ID'].'"></td>
					<td>'.($key+1).'</td>
					<td>'.$value['Codigo_Inv'].'</td>
					<td>'.$value['Producto'].'</td>
					<td>'.$value['Cantidad_Total'].'</td>
					<td>'.$Stock.'</td>
					<td width="20px">
					<input type="text" id="txt_cant_'.$value['ID'].'" name="txt_cant_'.$value['ID'].'" value="'.$value['Cantidad'].'" class="form-control form-control-sm"></td>
					<td>'.$value['Unidad'].'</td>
					<td>'.$value['Precio'].'</td>				
					<td>'.$value['Total'].'</td>				
					<td>'.$value['Fecha']->format('Y-m-d').'</td>
					<td>'.$value['Fecha_Ent']->format('Y-m-d').'</td>	
					<td>'.$value['Comentario'].'</td>
					
				</tr>';
		}
		return $tr;
	}

	function AddProveedorExta($parametros)
	{
		$linea = $this->modelo->Trans_Pedidos($parametros['linea'],false,false);
		//$proveedores = Leer_Datos_Clientes($parametros['proveedor'],true,false,false);

		$datos = $this->modelo->proveedores_seleccionados_x_producto($linea[0]['Codigo_Inv'],$linea[0]['Orden_No'],$parametros['proveedor']);

		if(count($datos)==0)
		{
			SetAdoAddNew("Trans_Ticket");
	        SetAdoFields("Codigo_Inv",$linea[0]['Codigo_Inv']);
	        SetAdoFields("Fecha",$linea[0]['Fecha']);
	        SetAdoFields("Producto",$linea[0]['Producto']);
	        SetAdoFields("Cantidad",$linea[0]['Cantidad']);
	        SetAdoFields("Precio",$linea[0]['Precio']);
	        SetAdoFields("CodigoC",$parametros['proveedor']);
	        SetAdoFields("Total", $linea[0]['Total']);
	        SetAdoFields("Item",$_SESSION['INGRESO']['item']);
	        SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
	        SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
	        SetAdoFields("Orden_No",$linea[0]['Orden_No']);				
			SetAdoUpdate();

			return 1;
		}else
		{
			return -2;
		}
	}


	function grabar_solicitud_proveedor($parametros)
	{
		// print_r($parametros);die();

		$aprobados = [];
		parse_str($parametros['aprobacion'], $aprobados);
		$datos = $this->modelo->lineas_pedido_solicitados($parametros['pedido']);
		foreach ($datos as $key => $value) {
			
			SetAdoAddNew("Trans_Pedidos");         
        	SetAdoFields("TC",'E');       
        	SetAdoFields("Fecha_Aprob",date('Y-m-d'));
        	SetAdoFieldsWhere('ID',$value['ID']);
        	SetAdoUpdateGeneric();
		}


		$aprob = array_keys($aprobados,'on');
		foreach ($aprob as $key => $value) {

			$id = str_replace('rbl_a_','', $value);

			SetAdoAddNew("Trans_Pedidos");         
        	SetAdoFields("Opc1",true);
        	SetAdoFields("Fecha_Aprob",date('Y-m-d'));
        	SetAdoFieldsWhere('ID',$id);
        	SetAdoUpdateGeneric();		

		}


			// print_r($aprobados);
			// print_r($aprob);die();

		return 1;
	}

	function guardar_linea_aprobacion($parametros)
	{
		$linea = $this->modelo->lineas_pedido_solicitados(false,$parametros['id_linea']);
		if(count($linea)>0)
		{
			SetAdoAddNew("Trans_Pedidos");         
        	SetAdoFields("Cantidad",$parametros['cantida']);
        	SetAdoFields("Total",$parametros['cantida']*$linea[0]['Precio']);

        	SetAdoFieldsWhere('ID',$parametros['id_linea']);
        	return SetAdoUpdateGeneric();

		}

	}


	function imprimir_pdf_envio($orden)
	{

		$datos_pedido = $this->modelo->envio_pedidos_contratista($orden);

		$datos_lineas = $this->modelo->lineas_pedido_solicitados_proveedor($orden);
		$total_apro = 0;
		$total_recha = 0;
		foreach ($datos_lineas as $key => $value) {
			if($value['Opc1']==1)
			{
				$total_apro = $total_apro+$value['Total'];
			}else
			{
				$total_recha = $total_recha+$value['Total'];
			}			
		}

		// print_r($datos_pedido);die();

		$ali =array("L","L");
		$medi =array(40,130);
		$pdf = new cabecera_pdf();  
		$titulo = "L I S T A D O   D E   M A T E R I A L E S   A P R O B A D O S ";
		$mostrar = true;
		$sizetable =7;
		$tablaHTML = array();
		$tablaHTML[0]['medidas']=$medi;
		$tablaHTML[0]['alineado']=$ali;
		$tablaHTML[0]['datos']= array('<b>Numero:',$datos_pedido[0]['Orden_No']);
		$tablaHTML[0]['size'] = 9;

		$tablaHTML[1]['medidas']=$medi;
		$tablaHTML[1]['alineado']=$ali;
		$tablaHTML[1]['datos']= array('<b>Contratista:',$datos_pedido[0]['Cliente']);
		$tablaHTML[1]['size'] = 9;

		$tablaHTML[2]['medidas']=$medi;
		$tablaHTML[2]['alineado']=$ali;
		$tablaHTML[2]['datos']= array('<b>Total Pedido:',number_format($datos_pedido[0]['Total'],2,'.',''));
		$tablaHTML[2]['size'] = 9;

		$tablaHTML[3]['medidas']=$medi;
		$tablaHTML[3]['alineado']=$ali;
		$tablaHTML[3]['datos']= array('<b>Total Aprobado:',number_format($total_apro,2,'.',''));
		$tablaHTML[3]['size'] = 9;

		$tablaHTML[4]['medidas']=$medi;
		$tablaHTML[4]['alineado']=$ali;
		$tablaHTML[4]['datos']= array('<b>Total Rechazado:',number_format($total_recha,2,'.',''));
		$tablaHTML[4]['size'] = 9;
		


		$datos_lineas = $this->modelo->lineas_pedido_solicitados_proveedor($orden);
		$tablaHTML[5]['medidas']=array(8,15,40,20,75,25,30,15,15,15,15);
		$tablaHTML[5]['alineado']=array('C','C','L','L','L','L','L','L','R','R','R');
		$tablaHTML[5]['datos']=array('','Aprobado','FAMILIA','CODIGO','ITEM','MARCAS','OBSERVACION','ENTREGA','ULTIMO PRECIO','CANTIDAD','TOTAL');
		$tablaHTML[5]['estilo']='B';
		$tablaHTML[5]['borde'] = '1';
		$tablaHTML[5]['size'] = 7;
		// print_r($datos_lineas);die();

		$pos = 6;
		foreach ($datos_lineas as $key => $value) {

			// print_r($value);die();

			$codigo = $value['Codigo_Inv'];
			$resp = true;
			while ($resp) {
				$posicion = strrpos($codigo, '.');
				// Verificar que el punto fue encontrado
				if ($posicion !== false) {
				    $códigoModificado = substr($codigo, 0, $posicion);
				    $fami = $this->modelo->buscar_familia($códigoModificado);
				    if(count($fami)==0){$codigo = $códigoModificado; }else{$resp = false;}
				}
			}
			$estado = 'NO';
			if($value['Opc1']==1){$estado = 'SI';}
			// print_r($fami);die();
		  $tablaHTML[$pos]['medidas']=$tablaHTML[5]['medidas'];
		  $tablaHTML[$pos]['alineado']=$tablaHTML[5]['alineado'];
		  $tablaHTML[$pos]['datos']=array($key+1,'<b>'.$estado,$fami[0]['Producto'],$value['Codigo_Inv'],$value['Producto'],$value['Marca'],$value['Comentario'],$value['Fecha']->format('Y-m-d'),$value['Precio'],$value['Cantidad'],number_format($value['Total'],2,'.',''));
		  $tablaHTML[$pos]['estilo']='I';
		  $tablaHTML[$pos]['borde'] = '1';
		  $tablaHTML[$pos]['size'] = 6;
		  $pos = $pos+1;
		}
		$pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,"","",$sizetable,$mostrar,15,'L',true,null,true);

		// ($titulo,$tablaHTML,$contenido=false,$image=false,"","",$sizetable,$mostrar,false,'L',false,null,true);

	}

	function imprimir_excel_envio($orden)
	{

		$datos_pedido = $this->modelo->envio_pedidos_contratista($orden);

		$datos_lineas = $this->modelo->lineas_pedido_solicitados_proveedor($orden);
		$total_apro = 0;
		$total_recha = 0;
		foreach ($datos_lineas as $key => $value) {
			if($value['Opc1']==1)
			{
				$total_apro = $total_apro+$value['Total'];
			}else
			{
				$total_recha = $total_recha+$value['Total'];
			}			
		}

		// print_r($datos_pedido);die();

		$ali =array("L","L","L","L");
		$medi =array(30,10,130,10);

	


		$titulo = "L I S T A D O   D E   M A T E R I A L E S   A P R O B A D O S ";
		$mostrar = true;
		$sizetable =7;
		$tablaHTML = array();
		$tablaHTML[0]['medidas']=$medi;
		$tablaHTML[0]['datos']= array('Numero:','',$datos_pedido[0]['Orden_No'],'');	
		$tablaHTML[0]['unir']= array('AB','CDEFGHI');
		$tablaHTML[0]['tipo']= 'B';
		$tablaHTML[0]['col-total'] = 11;

		$tablaHTML[1]['medidas']=$medi;
		$tablaHTML[1]['datos']= array('Contratista:','',$datos_pedido[0]['Cliente'],'');
		$tablaHTML[1]['tipo']= 'B';	
		$tablaHTML[1]['unir']= array('AB','CDEFGHI');

		$tablaHTML[2]['medidas']=$medi;
		$tablaHTML[2]['datos']= array('Total Pedido:','',number_format($datos_pedido[0]['Total'],2,'.',''),'');	
		$tablaHTML[2]['tipo']= 'B';
		$tablaHTML[2]['unir']= array('AB','CDEFGHI');

		$tablaHTML[3]['medidas']=$medi;
		$tablaHTML[3]['datos']= array('Total Aprobado:','',number_format($total_apro,2,'.',''),'');	
		$tablaHTML[4]['tipo']= 'B';
		$tablaHTML[3]['unir']= array('AB','CDEFGHI');

		$tablaHTML[4]['medidas']=$medi;
		$tablaHTML[4]['datos']= array('Total Rechazado:','',number_format($total_recha,2,'.',''),'');
		$tablaHTML[4]['tipo']= 'B';	
		$tablaHTML[4]['unir']= array('AB','CDEFGHI');
		


		$datos_lineas = $this->modelo->lineas_pedido_solicitados_proveedor($orden);
		$tablaHTML[5]['medidas']=array(8,15,40,20,75,25,30,15,15,15,15);
		$tablaHTML[5]['datos']=array('','Aprobado','FAMILIA','CODIGO','ITEM','MARCAS','OBSERVACION','ENTREGA','ULTIMO PRECIO','CANTIDAD','TOTAL');
		$tablaHTML[5]['tipo']= 'SUB';
	
		// print_r($datos_lineas);die();

		$pos = 6;
		foreach ($datos_lineas as $key => $value) {

			// print_r($value);die();

			$codigo = $value['Codigo_Inv'];
			$resp = true;
			while ($resp) {
				$posicion = strrpos($codigo, '.');
				// Verificar que el punto fue encontrado
				if ($posicion !== false) {
				    $códigoModificado = substr($codigo, 0, $posicion);
				    $fami = $this->modelo->buscar_familia($códigoModificado);
				    if(count($fami)==0){$codigo = $códigoModificado; }else{$resp = false;}
				}
			}
			$estado = 'NO';
			if($value['Opc1']==1){$estado = 'SI';}
			// print_r($fami);die();
		  $tablaHTML[$pos]['medidas']=$tablaHTML[5]['medidas'];
		  // $tablaHTML[$pos]['alineado']=$tablaHTML[5]['alineado'];
		  $tablaHTML[$pos]['datos']=array($key+1,$estado,$fami[0]['Producto'],$value['Codigo_Inv'],$value['Producto'],$value['Marca'],$value['Comentario'],$value['Fecha']->format('Y-m-d'),$value['Precio'],$value['Cantidad'],number_format($value['Total'],2,'.',''));
		  $pos = $pos+1;
		}


	    excel_generico($titulo,$tablaHTML);

		// ($titulo,$tablaHTML,$contenido=false,$image=false,"","",$sizetable,$mostrar,false,'L',false,null,true);

	}


	//--------------------------------------------------envio solicitud proveedor-----------------------------------------------------

	function envio_pedidos_contratista($parametros)
	{
		$datos = $this->modelo->envio_pedidos_contratista(false,false,$parametros['fecha'],$parametros['query']);
		$tr = '';
		$total = 0;
		foreach ($datos as $key => $value) {
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td><a href="inicio.php?mod='.$_SESSION['INGRESO']['modulo_'].'&acc=solicitud_proveedor&orden='.$value['Orden_No'].'">'.$value['Cliente'].'</a></td>
					<td>'.$value['Orden_No'].'</td>					
					<td>'.$value['Fecha']->format('Y-m-d').'</td>
					<td>'.$value['Total'].'</td>	
					<td>
					<div class="input-group-btn">
						<button type="button" class="btn btn-sm btn-danger" title="Eliminar pedido" onclick="eliminar_solicitud(\''.$value['Orden_No'].'\')"><i class="fa fa-trash me-0"></i></butto>
						<button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Informe</button>
						<ul class="dropdown-menu dropdown-menu-end">
							<li><a class="dropdown-item" href="#" onclick="imprimir_pdf(\''.$value['Orden_No'].'\')"> <i class="fa fa-file-pdf-o"></i>PDF</a></li>
							<li><a class="dropdown-item" href="#" onclick="imprimir_excel(\''.$value['Orden_No'].'\')"><i class="fa fa-file-excel-o"></i> Excel</a></li>
						</ul>
					</div>
					</td>						
				</tr>';
				$total+=$value['Total'];
		}
		return $tr;
	}


	function pedido_solicitados_proveedor($query,$parametros)
	{
		$orden=false;
		if($parametros!='')
		{
			$orden = $parametros['orden'];
		}

		// print_r($parametros);die();
		$datos = $this->modelo->envio_pedidos_contratista($orden,$query);
		// $lista = array();
		// foreach ($datos as $key => $value) {
		// 	$lista[] = array('id'=>$value['Orden_No'],'text'=>$value['Nombre_Completo'].' -- '.$value['Orden_No'],'data'=>$value);
		// }

		return $datos;
		// print_r($datos);die();
	}

	function lista_proveedores($query)
	{
		$datos = $this->modelo->proveedores($query);

		// print_r($datos);die();
		$lista = array();
		foreach ($datos as $key => $value) {
			$lista[] = array('id'=>$value['Codigo'],'text'=>$value['Cliente'],'data'=>$value);
		}

		return $lista;
		// print_r($datos);die();
	}



	function  lineas_pedido_solicitados_proveedor($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->lineas_pedido_solicitados_proveedor($parametros['orden']);
		$tr = '';
		foreach ($datos as $key => $value) {
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td>'.$value['Codigo_Inv'].'</td>
					<td>'.$value['Producto'].'</td>
					<td>'.$value['Cantidad'].'</td>
					<td>'.$value['Unidad'].'</td>
					<td>'.$value['Precio'].'</td>				
					<td>'.$value['Total'].'</td>
					<td>'.$value['Fecha']->format('Y-m-d').'</td>
					<td>'.$value['Fecha_Ent']->format('Y-m-d').'</td>	
					<td>'.$value['Comentario'].'</td>
					<td width="150px">
						<div class="input-group margin">
							<div class="input-group-btn">
								<button type="button" class="btn btn-sm btn-primary" onclick="addCliente();lineaSolProv('.$value['ID'].')"><i class="fa fa-user-plus me-0"></i></button>
							</div>
							<select class="form-select select2_prove" id="ddl_selector_'.$value['ID'].'" onclick="llenarProveedores(\'ddl_selector_'.$value['ID'].'\')" name="ddl_selector_'.$value['ID'].'[]" multiple="multiple">
								<option disabled value="">Seleccione proveedor</option>
							</select>
						</div>
					</td>
					<!---
					<td>
						<button class="btn btn-sm btn-primary" onclick="eliminar_linea(\''.$value['ID'].'\')"><i class="fa fa fa-save"></i></button>
					</td>
					-->
				</tr>';
		}
		return $tr;
	}

	function grabar_envio_solicitud($parametros)
	{
		// print_r($parametros);die();
		foreach ($parametros as $key => $value) {

			//id de el producto
			$id = str_replace('ddl_selector_', "", $key);
			if ($key !== $id) {
				$linea = $this->modelo->Trans_Pedidos($id,false,false);			
				$linea = $linea[0];
				// print_r($id);die();

				foreach ($value as $key2 => $value2) {
					// recorro todo los proveedores seleccionados
					// print_r($value);die();

					SetAdoAddNew("Trans_Ticket");
			        SetAdoFields("Codigo_Inv",$linea['Codigo_Inv']);
			        SetAdoFields("Fecha",$linea['Fecha']);
			        SetAdoFields("Producto",$linea['Producto']);
			        SetAdoFields("Cantidad",$linea['Cantidad']);
			        SetAdoFields("Precio",$linea['Precio']);
			        SetAdoFields("CodigoC",$value2);
			        SetAdoFields("Total", $linea['Total']);
			        SetAdoFields("Item",$_SESSION['INGRESO']['item']);
			        SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
			        SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
			        SetAdoFields("Orden_No",$linea['Orden_No']);				
					SetAdoUpdate();


					SetAdoAddNew("Trans_Pedidos");         
		        	SetAdoFields("TC",'T');

		        	SetAdoFieldsWhere('ID',$id);
		        	SetAdoUpdateGeneric();	        					

				}
			}
			// print_r($linea);die();
			// foreach ($value as $key2 => $value2) {

			// print_r($linea);die();


			// }
			// print_r($linea);die();
		}		
		return 1;
		// print_r($parametros);die();
	}


	//-------------------------------------------aprobacion solicitud proveedor-----------------------------------------------------


	function lista_pedido_aprobacion_solicitados_proveedor($parametros)
	{
		$datos = $this->modelo->lista_pedido_aprobacion_solicitados_proveedor(false,false,$parametros['fecha'],$parametros['query']);
		$tr = '';
		$total = 0;
		foreach ($datos as $key => $value) {
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td><a href="inicio.php?mod='.$_SESSION['INGRESO']['modulo_'].'&acc=aprobar_proveedor&orden='.$value['Orden_No'].'">'.$value['Cliente'].'</a></td>
					<td>'.$value['Orden_No'].'</td>					
					<td>'.$value['Fecha']->format('Y-m-d').'</td>
					<td>'.$value['Total'].'</td>
					<td>

						<div class="input-group-btn">
							<button type="button" class="btn btn-sm btn-danger" title="Eliminar pedido" onclick="eliminar_solicitud(\''.$value['Orden_No'].'\')"><i class="fa fa-trash me-0"></i></butto>
							<button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Informe</button>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="#" onclick="imprimir_pdf(\''.$value['Orden_No'].'\')"> <i class="fa fa-file-pdf-o"></i>PDF</a></li>
								<li><a class="dropdown-item" href="#" onclick="imprimir_excel(\''.$value['Orden_No'].'\')"><i class="fa fa-file-excel-o"></i> Excel</a></li>
							</ul>
						</div>

<!--
						<button type="button" class="btn btn-sm btn-default" onclick="imprimir_pdf(\''.$value['Orden_No'].'\')" ><i class="fa fa-file-pdf-o"></i></butto>
						<button type="button" class="btn btn-sm btn-default" onclick="imprimir_excel(\''.$value['Orden_No'].'\')" ><i class="fa fa-file-excel-o"></i></butto>

						-->
						
					</td>						
				</tr>';
				$total+=$value['Total'];
		}
		return $tr;
	}


	function pedido_aprobacion_solicitados_proveedor($parametros)
	{
		$datos = $this->modelo->lista_pedido_aprobacion_solicitados_proveedor($parametros['orden']);
		// $lista = array();
		// foreach ($datos as $key => $value) {
		// 	$lista[] = array('id'=>$value['Orden_No'],'text'=>$value['Nombre_Completo'].' -- '.$value['Orden_No'],'data'=>$value);
		// }

		return $datos;
		// print_r($datos);die();
	}

	// function lista_proveedores($query)
	// {
	// 	$datos = $this->modelo->proveedores($query);

	// 	// print_r($datos);die();
	// 	$lista = array();
	// 	foreach ($datos as $key => $value) {
	// 		$lista[] = array('id'=>$value['Codigo'],'text'=>$value['Cliente'],'data'=>$value);
	// 	}

	// 	return $lista;
	// 	// print_r($datos);die();
	// }



	function  lineas_pedido_aprobacion_solicitados_proveedor($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->lineas_pedido_aprobacion_solicitados_proveedor($parametros['orden']);
		$tr = '';
		foreach ($datos as $key => $value) {
			// print_r($value);die();
			$proveedores = $this->modelo->proveedores_seleccionados_x_producto($value['Codigo_Inv'],$parametros['orden']);
			$op = '';
			foreach ($proveedores as $key2 => $value2) {
				$op.='<option value="'.$value['CodigoC'].'"  selected="">'.$value2['Cliente'].'</option>';
			}
			// print_r($proveedores);die();
			$tr.='<tr>
					<td>'.($key+1).'</td>
					<td>'.$value['Codigo_Inv'].'</td>
					<td>'.$value['Producto'].'</td>
					<td>'.$value['Cantidad'].'</td>
					<td>'.$value['Unidad'].'</td>
					<td>'.$value['Precio'].'</td>
					<td>'.$value['Total'].'</td>
					<td>'.$value['Fecha']->format('Y-m-d').'</td>
					<td>'.$value['Fecha_Ent']->format('Y-m-d').'</td>
					<td>'.$value['Comentario'].'</td>
					<td width="28%">
						<div class="d-flex align-items-center">
							<select class="form-control select2_prove" id="ddl_selector_'.$value['ID'].'" name="ddl_selector_'.$value['ID'].'[]" multiple="multiple" row="2" disabled >
								'.$op.'
							</select>
							';
							if($value['CodigoC']=='.')
							{
							 $tr.='<span class="input-group-btn">
								<button type="button" class="btn btn-sm btn-primary" onclick="addCliente();lineaSolProv('.$value['ID'].')"><i class="fa fa-user-plus"></i></button>
							</span>
							';
							}
						$tr.='</div>
					</td>

					<!---
					<td width="28%">
					<select class="form-control select2_prove" id="ddl_proveedor_'.$value['ID'].'" name="ddl_proveedor_'.$value['ID'].'">
							'.$op.'
						</select>

					</td>

					-->
					<td>
						';
						if($value['CodigoC']!='.')
						{	$prov = $this->modelo->proveedores($query=false,$value['CodigoC']);
							if(count($prov)>0)
							{
							// print_r($prov);die();
								$tr.='<label> Proveedor:<br>'.$prov[0]['Cliente'].' Asignado </label>
								<br><button class="btn btn-sm btn-danger" type="button" onclick="eliminar_seleccion('.$value['ID'].')"><i class="fa fa-trash"></i> Eliminar Seleccionar</button>';
							}else
							{
								$datos = Leer_Datos_Clientes($value['CodigoC'],$Por_Codigo=true,false,false);
								$tr.="<label style='color: red;'>".$datos['Cliente']." no esta asignado a CxCxP </label>";
							}
						}else
						{
							$tr.='<button class="btn btn-sm btn-primary" type="button" onclick="mostrar_proveedor(\''.$value['ID'].'\',\''.$value['Codigo_Inv'].'\',\''.$value['Orden_No'].'\',\''.$value['Cantidad'].'\')"><i class="fa fa fa-user"></i> Seleccionar proveedor</button>';
						}
						$tr.='
					</td>
				</tr>';
				// print_r($tr);die();
		}
		return $tr;
	}

	function eliminar_prove($parametros)
	{
		SetAdoAddNew("Trans_Pedidos");               
    	SetAdoFields("CodigoC",'.');
    	SetAdoFieldsWhere('ID',$parametros['id']);
    	return SetAdoUpdateGeneric();
	}

	function imprimir_pdf_proveedor($orden)
	{

		$datos_pedido = $this->modelo->lista_pedido_aprobacion_solicitados_proveedor($orden);


		// print_r($datos_pedido);die();

		// $datos_lineas = $this->modelo->lineas_pedido_aprobacion_solicitados_proveedor($orden);
		$total_apro = 0;
		$total_recha = 0;
		// foreach ($datos_lineas as $key => $value) {
		// 	if($value['Opc1']==1)
		// 	{
		// 		$total_apro = $total_apro+$value['Total'];
		// 	}else
		// 	{
		// 		$total_recha = $total_recha+$value['Total'];
		// 	}			
		// }

		// print_r($datos_pedido);die();

		$ali =array("L","L");
		$medi =array(40,130);
		$pdf = new cabecera_pdf();  
		$titulo = "C O T I Z A C I  O N";
		$mostrar = true;
		$sizetable =7;
		$tablaHTML = array();
		$tablaHTML[0]['medidas']=$medi;
		$tablaHTML[0]['alineado']=$ali;
		$tablaHTML[0]['datos']= array('<b>Numero:',$datos_pedido[0]['Orden_No']);
		$tablaHTML[0]['size'] = 9;

		$tablaHTML[1]['medidas']=$medi;
		$tablaHTML[1]['alineado']=$ali;
		$tablaHTML[1]['datos']= array('<b>Contratista:',$datos_pedido[0]['Cliente']);
		$tablaHTML[1]['size'] = 9;

		$tablaHTML[2]['medidas']=$medi;
		$tablaHTML[2]['alineado']=$ali;
		$tablaHTML[2]['datos']= array('<b>Total Pedido:',number_format($datos_pedido[0]['Total'],2,'.',''));
		$tablaHTML[2]['size'] = 9;

		// $tablaHTML[3]['medidas']=$medi;
		// $tablaHTML[3]['alineado']=$ali;
		// $tablaHTML[3]['datos']= array('<b>Total Aprobado:',number_format($total_apro,2,'.',''));
		// $tablaHTML[3]['size'] = 9;

		// $tablaHTML[4]['medidas']=$medi;
		// $tablaHTML[4]['alineado']=$ali;
		// $tablaHTML[4]['datos']= array('<b>Total Rechazado:',number_format($total_recha,2,'.',''));
		// $tablaHTML[4]['size'] = 9;
		


		$datos_lineas = $this->modelo->lineas_pedido_aprobacion_solicitados_proveedor($orden);

		// print_r($datos_lineas);die();
		$tablaHTML[5]['medidas']=array(8,40,20,75,25,15,15,15,15,50);
		$tablaHTML[5]['alineado']=array('C','L','L','L','L','L','R','R','R','L');
		$tablaHTML[5]['datos']=array('','FAMILIA','CODIGO','ITEM','MARCAS','ENTREGA','ULTIMO PRECIO','CANTIDAD','TOTAL','PROVEEDORES');
		$tablaHTML[5]['estilo']='B';
		$tablaHTML[5]['borde'] = '1';
		$tablaHTML[5]['size'] = 7;
		// print_r($datos_lineas);die();

		$pos = 6;
		foreach ($datos_lineas as $key => $value) {

			$codigo = $value['Codigo_Inv'];			
			$proveedores_seleccionados = $this->modelo->proveedores_seleccionados_x_producto($codigo,$orden=false);
			$proveedores = '';
			foreach ($proveedores_seleccionados as $key2 => $value2) {
				$proveedores.=$value2['Cliente'].' ; ';
			}

			$resp = true;
			while ($resp) {
				$posicion = strrpos($codigo, '.');
				// Verificar que el punto fue encontrado
				if ($posicion !== false) {
				    $códigoModificado = substr($codigo, 0, $posicion);
				    $fami = $this->modelo->buscar_familia($códigoModificado);
				    if(count($fami)==0){$codigo = $códigoModificado; }else{$resp = false;}
				}
			}

			// print_r($fami);
			// print_r($fami);die();
		  $tablaHTML[$pos]['medidas']=$tablaHTML[5]['medidas'];
		  $tablaHTML[$pos]['alineado']=$tablaHTML[5]['alineado'];
		  $tablaHTML[$pos]['datos']=array($key+1,$fami[0]['Producto'],$value['Codigo_Inv'],$value['Producto'],$value['Marca'],$value['Fecha']->format('Y-m-d'),$value['Precio'],$value['Cantidad'],number_format($value['Total'],2,'.',''),$proveedores);
		  $tablaHTML[$pos]['estilo']='I';
		  $tablaHTML[$pos]['borde'] = '1';
		  $tablaHTML[$pos]['size'] = 6;
		  $pos = $pos+1;
		}
		// die();
		$pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,"","",$sizetable,$mostrar,15,'L',true,null,true);

		// ($titulo,$tablaHTML,$contenido=false,$image=false,"","",$sizetable,$mostrar,false,'L',false,null,true);

	}

	function imprimir_excel_proveedor($orden)
	{

		$datos_pedido = $this->modelo->lista_pedido_aprobacion_solicitados_proveedor($orden);


		// print_r($datos_pedido);die();

		// $datos_lineas = $this->modelo->lineas_pedido_aprobacion_solicitados_proveedor($orden);
		$total_apro = 0;
		$total_recha = 0;
		// foreach ($datos_lineas as $key => $value) {
		// 	if($value['Opc1']==1)
		// 	{
		// 		$total_apro = $total_apro+$value['Total'];
		// 	}else
		// 	{
		// 		$total_recha = $total_recha+$value['Total'];
		// 	}			
		// }

		// print_r($datos_pedido);die();

		$ali =array("L","L","L","L");
		$medi =array(30,10,130,10);
		$titulo = "C O T I Z A C I  O N";
		$mostrar = true;
		$sizetable =7;
		$tablaHTML = array();
		$tablaHTML[0]['medidas']=$medi;
		$tablaHTML[0]['datos']= array('Numero: ','',$datos_pedido[0]['Orden_No'],'');
		$tablaHTML[0]['unir']= array('AB','CDEFGHI');
		$tablaHTML[0]['tipo']= 'B';
		$tablaHTML[0]['col-total'] = 10;

		$tablaHTML[1]['medidas']=$medi;
		$tablaHTML[1]['datos']= array('Contratista:','',$datos_pedido[0]['Cliente'],'');
		$tablaHTML[1]['unir']= array('AB','CDEFGHI');

		$tablaHTML[2]['medidas']=$medi;
		$tablaHTML[2]['datos']= array('Total Pedido:','',number_format($datos_pedido[0]['Total'],2,'.',''),'');
		$tablaHTML[2]['unir']=  array('AB','CDEFGHI');



		$datos_lineas = $this->modelo->lineas_pedido_aprobacion_solicitados_proveedor($orden);

		// print_r($datos_lineas);die();
		$tablaHTML[3]['medidas']=array(8,40,20,75,25,15,15,15,15,50);
		$tablaHTML[3]['datos']=array('','FAMILIA','CODIGO','ITEM','MARCAS','ENTREGA','ULTIMO PRECIO','CANTIDAD','TOTAL','PROVEEDORES');
		$tablaHTML[3]['tipo']= 'SUB';
		// print_r($datos_lineas);die();

		$pos = 4;
		foreach ($datos_lineas as $key => $value) {

			$codigo = $value['Codigo_Inv'];			
			$proveedores_seleccionados = $this->modelo->proveedores_seleccionados_x_producto($codigo,$orden=false);
			$proveedores = '';
			foreach ($proveedores_seleccionados as $key2 => $value2) {
				$proveedores.=$value2['Cliente'].' ; ';
			}

			$resp = true;
			while ($resp) {
				$posicion = strrpos($codigo, '.');
				// Verificar que el punto fue encontrado
				if ($posicion !== false) {
				    $códigoModificado = substr($codigo, 0, $posicion);
				    $fami = $this->modelo->buscar_familia($códigoModificado);
				    if(count($fami)==0){$codigo = $códigoModificado; }else{$resp = false;}
				}
			}

			// print_r($fami);
			// print_r($fami);die();
		  $tablaHTML[$pos]['medidas']=$tablaHTML[3]['medidas'];
		  $tablaHTML[$pos]['datos']=array($key+1,$fami[0]['Producto'],$value['Codigo_Inv'],$value['Producto'],$value['Marca'],$value['Fecha']->format('Y-m-d'),$value['Precio'],$value['Cantidad'],number_format($value['Total'],2,'.',''),$proveedores);
		  $pos = $pos+1;
		}
		// // die();

	    excel_generico($titulo,$tablaHTML);


	}

	function lista_provee($parametros)
	{
		// print_r($parametros);die();
		$select = $this->modelo->lineas_pedido_aprobacion_solicitados_proveedor(false,false,$parametros['id']);
		// print_r($select);die();
		$data = $this->modelo->proveedores_seleccionados_x_producto($parametros['codigo'],$parametros['orden'],false,$select[0]['Cantidad']);
		$lista = '';
		$id = '';
		foreach ($data as $key => $value) {
			$id.=$value['IDT'].',';
			$lista.='<tr>
				<td>
					'.$value['Cliente'].'
					<input type="hidden" class="form-control form-control-sm" name="txt_codigoC_'.$value['IDT'].'" id="txt_codigoC_'.$value['IDT'].'" value="'.$value['CodigoC'].'">
					<input type="hidden" class="form-control form-control-sm" name="txt_idProv_'.$value['IDT'].'" id="txt_idProv_'.$value['IDT'].'" value="'.$value['IDT'].'">
				</td>
				<td>					
	                  <input type="text" class="form-control form-control-sm" name="txt_cantidad_prov_'.$value['IDT'].'" id="txt_cantidad_prov_'.$value['IDT'].'" value="0">
				</td>
				<td>
					<input type="text" class="form-control form-control-sm" value="'.$value['Precio'].'" name="txt_costoAnt" id="txt_costoAnt" readonly>
				</td>
				<td>
	                  <input type="text" class="form-control form-control-sm" name="txt_costoAct_'.$value['IDT'].'" id="txt_costoAct_'.$value['IDT'].'" value="0">
	             </td>
			</tr>';
		}
		$id = substr($id,0,-1);
		$lista.="<tr><td>Total</td><td><label id='lbl_total_linea'>".$data[0]['Cantidad']."</label></td><td></td></tr>";

		return array('option'=>$lista,'CostoTotal'=>$data[0]['Total'],'idProve'=>$id);

		// print_r($data);die();
	}


	function guardar_seleccion_proveedor($parametros)
	{
		// print_r($parametros);die();

		$lineas = array();
		$cant_total = 0;
		$precio_colocado = 1;
		$proveedores = explode(',',$parametros['txt_id_prove']);
		foreach ($proveedores as $key => $value) {
			$cant_total+= floatval($parametros['txt_cantidad_prov_'.$value]);	
			if($cant_total<=$parametros['total'])
			{

				if($parametros['txt_cantidad_prov_'.$value]!=0 && $parametros['txt_cantidad_prov_'.$value]!='' && $parametros['txt_costoAct_'.$value]!='' && $parametros['txt_costoAct_'.$value]!=0)
				{
					$lineas[$key]['cantidad'] = $parametros['txt_cantidad_prov_'.$value];	
					$lineas[$key]['costo'] = $parametros['txt_costoAct_'.$value];	
					$lineas[$key]['CodigoC'] = $parametros['txt_codigoC_'.$value];	
				}else if ($parametros['txt_cantidad_prov_'.$value]!=0 && $parametros['txt_cantidad_prov_'.$value]!='' && ($parametros['txt_costoAct_'.$value]=='' || $parametros['txt_costoAct_'.$value]==0)) {
					return -3;
				}
			}else
			{
				return -2;
			}
		}

		if($cant_total!=$parametros['total'])
		{
			return -2;
		}

		// reindexa
		$lineas = array_values($lineas);

		// print_r($lineas);die();
		$linea_org = $this->modelo->lineas_pedido_aprobacion_solicitados_proveedor(false,false,$parametros['txt_id_linea']);

		// print_r($linea_org);die();

		foreach ($lineas as $key => $value) {
			if($key==0)
			{
				SetAdoAddNew("Trans_Pedidos");         
		    	// SetAdoFields("TC",'B');  //BUY compra en ingles        
		    	SetAdoFields("CodigoC",$value['CodigoC']);
		    	SetAdoFields("Costo_Original",$value['costo']);
		    	SetAdoFields("Cantidad",$value['cantidad']);
		    	SetAdoFields("Total_Original",($value['cantidad']*$value['costo']));
		    	SetAdoFields("Total",($value['cantidad']*$value['costo']));
		    	SetAdoFields("Cantidad_Total",$value['cantidad']);
		    	SetAdoFieldsWhere('ID',$parametros['txt_id_linea']);
		    	SetAdoUpdateGeneric();	      
			}else
			{
				SetAdoAddNew("Trans_Pedidos");
		        SetAdoFields("Codigo_Inv",$linea_org[0]['Codigo_Inv']);
		        SetAdoFields("Fecha",$linea_org[0]['Fecha']);
		        SetAdoFields("Fecha_Ent",$linea_org[0]['Fecha_Ent']);
		        SetAdoFields("Producto",$linea_org[0]['Producto']);
		        SetAdoFields("Cantidad",$value['cantidad']);
		        SetAdoFields("Precio",$linea_org[0]['Precio']);
		        SetAdoFields("Costo_Original",$value['costo']);
		        SetAdoFields("TC",'T');
		    	SetAdoFields("Total",($value['cantidad']*$linea_org[0]['Precio']));
		    	SetAdoFields("Total_Original",($value['cantidad']*$value['costo']));
		        SetAdoFields("Item",$_SESSION['INGRESO']['item']);
		        SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
		        SetAdoFields("CodigoU",$linea_org[0]['CodigoU']);
		        SetAdoFields("Comentario",$linea_org[0]['Comentario']);
		        SetAdoFields("CodMarca",$linea_org[0]['CodMarca']);     
		    	SetAdoFields("CodigoC",$value['CodigoC']);    
		    	SetAdoFields("Orden_No",$linea_org[0]['Orden_No']);
		    	SetAdoFields("Cantidad_Total",$value['cantidad']);
				SetAdoUpdate();
			}
		}


		return 1;
		

		print_r($lineas);die();

		// SetAdoAddNew("Trans_Pedidos");         
    	// // SetAdoFields("TC",'B');  //BUY compra en ingles        
    	// SetAdoFields("CodigoC",$parametros['CodigoC']);
    	// SetAdoFields("HABIT",$parametros['costo']);

    	// SetAdoFieldsWhere('ID',$parametros['idProducto'] );
    	// return  SetAdoUpdateGeneric();	      

	}

	function grabar_compra_pedido($parametros)
	{

		$lineas = $this->modelo->lineas_pedido_aprobacion_solicitados_proveedor($parametros['orden']);
		foreach ($lineas as $key2 => $value2) {
			if($value2['CodigoC']!='.')
			{
				// print_r($value);die();
				SetAdoAddNew("Trans_Pedidos");         
		    	SetAdoFields("TC",'B');  //BUY compra en ingles  
		    	SetAdoFields("Fecha_Provee",'B');  //BUY compra en ingles    
		    	SetAdoFieldsWhere('ID',$value2['ID'] );
		    	SetAdoUpdateGeneric();
		    	$this->enviar_email($value2['CodigoC']);	   
	    	}   
		}

		return 1;

	}


	function enviar_email($proveedor)
	{
		$cliente = Leer_Datos_Clientes($proveedor,true,false,false);
		// print_r($cliente);die();
		$res = $this->email->enviar_email_generico(false, 
			$cliente['Email'].';'.$_SESSION['INGRESO']['Email'], 
			$cuerpo_correo = "Solicitud de Compra", 
			$titulo_correo = "Solicitud de compra", $HTML = false);
		// print_r($res);die();
	}
	




}
?>