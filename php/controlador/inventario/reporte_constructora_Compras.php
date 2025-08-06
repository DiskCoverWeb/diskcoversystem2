<?php 
require_once(dirname(__DIR__,2)."/modelo/inventario/reporte_constructora_ComprasM.php");
require_once(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
$controlador = new reporte_constructora_Compras();
if(isset($_GET['cargar_datos']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_datos($parametros));
}
if(isset($_GET['cargar_detalles']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_detalles($parametros));
}
if(isset($_GET['cargar_datos_orden']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_datos_orden($parametros));
}
if(isset($_GET['cargar_datos_historial']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_datos_historial($parametros));
}
if(isset($_GET['cargar_datos_tiempos']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_datos_tiempos($parametros));
}
if(isset($_GET['contratistas']))
{
	// $parametros = $_POST['parametros'];
	$query = '';
	if(isset($_GET['q'])){ $query = $_GET['q'];}
	echo json_encode($controlador->contratistas($query));
}

if(isset($_GET['ddl_articulos']))
{
	// $parametros = $_POST['parametros'];
	$query = '';
	if(isset($_GET['q'])){ $query = $_GET['q'];}
	echo json_encode($controlador->articulos($query));
}
if(isset($_GET['ddl_orden']))
{
	$contratista = '';
	if(isset($_GET['contratista']))
	{
		$contratista = $_GET['contratista'];
	}
	$query = '';
	if(isset($_GET['q'])){ $query = $_GET['q'];}
	echo json_encode($controlador->orden($query,$contratista));
}

if(isset($_GET['ddl_orden_arti']))
{
	$contratista = '';
	if(isset($_GET['arti']))
	{
		$contratista = $_GET['arti'];
	}
	$query = '';
	if(isset($_GET['q'])){ $query = $_GET['q'];}
	echo json_encode($controlador->orden_arti($query,$contratista));
}

//reportes
if(isset($_GET['cargar_detalles_pdf']))
{
	$parametros = $_GET;
	echo json_encode($controlador->cargar_datos_pdf($parametros));
}
if(isset($_GET['cargar_detalles_excel']))
{
	$parametros = $_GET;
	echo json_encode($controlador->cargar_datos_excel($parametros));
}

// reporte orden 
if(isset($_GET['cargar_orden_pdf']))
{
	$parametros = $_GET;
	echo json_encode($controlador->cargar_orden_pdf($parametros));
}
if(isset($_GET['cargar_orden_excel']))
{
	$parametros = $_GET;
	echo json_encode($controlador->cargar_orden_excel($parametros));
}

// reporte historial 
if(isset($_GET['cargar_historial_pdf']))
{
	$parametros = $_GET;
	echo json_encode($controlador->cargar_historial_pdf($parametros));
}
if(isset($_GET['cargar_historial_excel']))
{
	$parametros = $_GET;
	echo json_encode($controlador->cargar_historial_excel($parametros));
}

// reporte tiempos 
if(isset($_GET['cargar_tiempos_pdf']))
{
	$parametros = $_GET;
	echo json_encode($controlador->cargar_tiempos_pdf($parametros));
}
if(isset($_GET['cargar_tiempos_excel']))
{
	$parametros = $_GET;
	echo json_encode($controlador->cargar_tiempos_excel($parametros));
}

/**
 * 
 */
class reporte_constructora_Compras
{
	private $modelo;
	private $pdf;	
	function __construct()
	{
		$this->modelo = new reporte_constructora_ComprasM();
		$this->pdf = new cabecera_pdf();
	}

	function cargar_datos($parametros)
	{
		$datos = $this->modelo->cargar_datos($parametros);
		return $datos;
	}
	function cargar_detalles($parametros)
	{
		$data = $this->modelo->lineas_pedidos($parametros['orden'],$parametros['proveedor']);
		return $data;
		// print_r($data);die();
	}

	function cargar_datos_orden($parametros)
	{
		$datos = $this->modelo->lineas_pedidos_orden($parametros);
		return $datos;
	}

	function cargar_datos_historial($parametros)
	{
		$datos = $this->modelo->cargar_datos_historial($parametros);
		return $datos;
	}

	function cargar_datos_tiempos($parametros)
	{
		$datos = $this->modelo->cargar_datos_tiempos($parametros);
		return $datos;
	}


	function contratistas($query)
	{
		$datos = $this->modelo->contratistas($query);
		return $datos;
	}
	function orden($query,$contratista = false)
	{
		$datos = $this->modelo->orden($query,$contratista);
		return $datos;
	}

	function orden_arti($query,$contratista = false)
	{
		$datos = $this->modelo->orden_arti($query,$contratista);
		return $datos;
	}

	function articulos($parametros)
	{
		return $this->modelo->articulos($parametros);
	}


	// reportes

	function cargar_datos_pdf($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->cargar_datos($parametros);
		$titulo = 'REPORTE DETALLE - AHORRO';
		$sizetable =7;
		$mostrar = 1;
		$desde = ''; //$parametros['desde'];
		$hasta = ''; //$parametros['hasta'];
		$tablaHTML= array();

		$i = 0;
		foreach ($datos as $key => $value) {
			$lineas = $this->modelo->lineas_pedidos($value['Orden_No'],$value['Codigo_P']);
			$tablaHTML[$i]['medidas']=array(40,235);
			$tablaHTML[$i]['alineado']=array('L','L');
			$tablaHTML[$i]['datos']=array('<b>COMPROBANTE',$lineas[0]['Numero']);
			$tablaHTML[$i]['estilo']='BIU';
			$tablaHTML[$i]['borde'] =array('LT','RT');
			$i++;

			$tablaHTML[$i]['medidas']=array(40,235);
			$tablaHTML[$i]['alineado']=array('L','L');
			$tablaHTML[$i]['datos']=array('<b>Precio referencial',$value['valor_ref']);
			// $tablaHTML[$i]['estilo']='BIU';
			$tablaHTML[$i]['borde'] =array('L','R');
			$i++;

			$tablaHTML[$i]['medidas']=array(40,235);
			$tablaHTML[$i]['alineado']=array('L','L');
			$tablaHTML[$i]['datos']=array('<b>Precio compra',$value['valor_compra']);
			// $tablaHTML[$i]['estilo']='BIU';
			$tablaHTML[$i]['borde'] =array('L','R');
			$i++;

			$tablaHTML[$i]['medidas']=array(40,235);
			$tablaHTML[$i]['alineado']=array('L','L');
			$tablaHTML[$i]['datos']=array('<b>Ahorro',number_format(($value['valor_ref']-$value['valor_compra']),3,'.',''));
			// $tablaHTML[$i]['estilo']='BIU';
			$tablaHTML[$i]['borde'] =array('L','R');
			$i++;

			$tablaHTML[$i]['medidas']=array(40,235);
			$tablaHTML[$i]['alineado']=array('L','L');
			$tablaHTML[$i]['datos']=array('<b>Proveedor',$lineas[0]['Cliente']);
			// $tablaHTML[$i]['estilo']='BIU';
			$tablaHTML[$i]['borde'] =array('L','R');
			$i++;

			$tablaHTML[$i]['medidas']=array(10,40,30,40,40,13,12,30,12,30,18);
			$tablaHTML[$i]['alineado']=array('L','L','L','L','L','R','R','R','R','R','R');
			$tablaHTML[$i]['datos']=array('<b>No','Familia','Codigo','Item','Marcas','Cant','P.Uni','Precio Referencia','P. Uni','Previo compra','AHO/UNI');
			$tablaHTML[$i]['estilo']='B';
			$tablaHTML[$i]['borde'] =1;
			

			$j = $i;
			$j++;

			$total_ref = 0;
			$total_compra = 0;

			foreach ($lineas as $key2 => $value2) {
				$tablaHTML[$j]['medidas']=$tablaHTML[$i]['medidas'];
				$tablaHTML[$j]['alineado']=$tablaHTML[$i]['alineado'];
				$tablaHTML[$j]['datos']=array(
					($key2+1),
					$value2['familia'],
					$value2['Codigo_Inv'],
					$value2['Producto'],
					$value2['Marca'],
					$value2['Entrada'],
					$value2['Costo'], 
					number_format(($value2['Costo']*$value2['Entrada']),3,'.',''),
					$value2['Valor_Unitario'],
					$value2['Valor_Total'],
				number_format(($value2['Costo']*$value2['Entrada']-$value2['Valor_Total']),3,'.','')
				);
				// $tablaHTML[$j]['estilo']='BIU';
				$tablaHTML[$j]['borde']=1;
				$j++;
				$total_ref= $total_ref+($value2['Costo']*$value2['Entrada']);
				$total_compra= $total_compra+$value2['Valor_Total'];
			}
			$tablaHTML[$j]['medidas']=array(120,40,13,12,30,12,30,18);
			$tablaHTML[$j]['alineado']=array('L','L','L','L','R','L','R','L');
			$tablaHTML[$j]['datos']=array('<b>TOTAL','','','',$total_ref,'',$total_compra,'');
			$tablaHTML[$j]['estilo']='B';
			$tablaHTML[$j]['borde']=1;
			$j++;

			$tablaHTML[$j]['medidas']=$tablaHTML[$i]['medidas'];
			$tablaHTML[$j]['alineado']=$tablaHTML[$i]['alineado'];
			$tablaHTML[$j]['datos']=array('','','','','','','','','','','');
			// $tablaHTML[$j]['estilo']='BIU';
			// $tablaHTML[$j]['borde']=1;
			$j++;

			

			$i = $j;
		}

		// print_r($tablaHTML); die();


		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$desde,$hasta,$sizetable,$mostrar,15,'L',true,null,1,$nuevaPagina=false);
		// return $datos;
	}


	function cargar_datos_excel($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->cargar_datos($parametros);
		// print_r($datos);die();
		$titulo = 'REPORTE DETALLE - AHORRO';
		$sizetable =7;
		$mostrar = 1;
		$desde = ''; //$parametros['desde'];
		$hasta = ''; //$parametros['hasta'];
		$tablaHTML= array();
	
		$tablaHTML[0]['medidas']=array(10,20,10,23,10,10,10,10,10,10,10);
		$tablaHTML[0]['datos']=array('','','','','','','','','','','');
		$tablaHTML[0]['tipo']='B';
		$tablaHTML[0]['unir'] =array('AK');
	
		$i = 1;
		foreach ($datos as $key => $value) {
			$lineas = $this->modelo->lineas_pedidos($value['Orden_No'],$value['Codigo_P']);
			$tablaHTML[$i]['medidas']=array(40,20,235);
			$tablaHTML[$i]['alineado']=array('L','L','L');
			$tablaHTML[$i]['datos']=array('COMPROBANTE','',$lineas[0]['Numero']);
			$tablaHTML[$i]['tipo']='B';
			$tablaHTML[$i]['unir'] =array('AB');
			$i++;

			$tablaHTML[$i]['medidas']=$tablaHTML[0]['medidas'];
			// $tablaHTML[$i]['alineado']=$tablaHTML[0]['alineado'];
			$tablaHTML[$i]['datos']=array('Precio referencial','',$value['valor_ref']);
			$tablaHTML[$i]['estilo']='BIU';
			$tablaHTML[$i]['unir'] =array('AB');
			$i++;

			$tablaHTML[$i]['medidas']=$tablaHTML[0]['medidas'];
			// $tablaHTML[$i]['alineado']=$tablaHTML[0]['alineado'];
			$tablaHTML[$i]['datos']=array('Precio compra','',$value['valor_compra']);
			// $tablaHTML[$i]['estilo']='BIU';
			$tablaHTML[$i]['borde'] =array('L','R');
			$i++;

			$tablaHTML[$i]['medidas']=$tablaHTML[0]['medidas'];
			// $tablaHTML[$i]['alineado']=$tablaHTML[0]['alineado'];
			$tablaHTML[$i]['datos']=array('Ahorro','',number_format(($value['valor_ref']-$value['valor_compra']),3,'.',''));
			// $tablaHTML[$i]['estilo']='BIU';
			$tablaHTML[$i]['borde'] =array('L','R');
			$tablaHTML[$i]['unir'] =array('AB');
			$i++;

			$tablaHTML[$i]['medidas']=$tablaHTML[0]['medidas'];
			// $tablaHTML[$i]['alineado']=$tablaHTML[0]['alineado'];
			$tablaHTML[$i]['datos']=array('Proveedor','',$lineas[0]['Cliente']);
			$tablaHTML[$i]['tipo']='B';
			$tablaHTML[$i]['unir'] =array('AB');
			$i++;

			$tablaHTML[$i]['medidas']=array(10,40,30,40,40,13,12,30,12,30,18);
			$tablaHTML[$i]['alineado']=array('L','L','L','L','L','R','R','R','R','R','R');
			$tablaHTML[$i]['datos']=array('No','Familia','Codigo','Item','Marcas','Cant','P.Uni','Precio Referencia','P. Uni','Previo compra','AHO/UNI');
			$tablaHTML[$i]['tipo'] ='C';  
			

			$j = $i;
			$j++;

			$total_ref = 0;
			$total_compra = 0;

			foreach ($lineas as $key2 => $value2) {
				$tablaHTML[$j]['medidas']=$tablaHTML[$i]['medidas'];
				$tablaHTML[$j]['alineado']=$tablaHTML[$i]['alineado'];
				$tablaHTML[$j]['datos']=array(
					($key2+1),
					$value2['familia'],
					$value2['Codigo_Inv'],
					$value2['Producto'],
					$value2['Marca'],
					$value2['Entrada'],
					$value2['Costo'], 
					number_format(($value2['Costo']*$value2['Entrada']),3,'.',''),
					$value2['Valor_Unitario'],
					$value2['Valor_Total'],
				number_format(($value2['Costo']*$value2['Entrada']-$value2['Valor_Total']),3,'.','')
				);

				$j++;
				$total_ref= $total_ref+($value2['Costo']*$value2['Entrada']);
				$total_compra= $total_compra+$value2['Valor_Total'];
			}
			$tablaHTML[$j]['medidas']=array(120,40,13,12,30,12,30,18);
			$tablaHTML[$j]['alineado']=array('L','L','L','L','R','L','R','L');
			$tablaHTML[$j]['datos']=array('TOTAL','','','',$total_ref,'',$total_compra,'');
			$tablaHTML[$j]['tipo'] ='BR';   
			$j++;

			$tablaHTML[$j]['medidas']=$tablaHTML[$i]['medidas'];
			$tablaHTML[$j]['alineado']=$tablaHTML[$i]['alineado'];
			$tablaHTML[$j]['datos']=array('','','','','','','','','','','');
			$tablaHTML[0]['unir'] =array('AK');
			$j++;

			

			$i = $j;
		}
      excel_generico($titulo,$tablaHTML);  
	}


	// reporte orden 

	function cargar_orden_pdf($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->lineas_pedidos_orden($parametros);
		$titulo = 'O R D E N E S';
		$sizetable =7;
		$mostrar = 1;
		$desde = ''; //$parametros['desde'];
		$hasta = ''; //$parametros['hasta'];
		$tablaHTML= array();

		$tablaHTML[0]['medidas']=array(50,40,30,45,30);
		$tablaHTML[0]['alineado']=array('L','L','L','L','L');
		$tablaHTML[0]['datos']=array('CONTRATISTA','ORDEN','SEMANA DEL AÑO','FECHA','PRECIO TOTAL');
		$tablaHTML[0]['estilo']='B';
		$tablaHTML[0]['borde'] =1;


		$i = 1;
		foreach ($datos as $key => $value) {
			$tablaHTML[$i]['medidas']=$tablaHTML[0]['medidas'];
			$tablaHTML[$i]['alineado']=$tablaHTML[0]['alineado'];
			$tablaHTML[$i]['datos']=array($value['Cliente'],$value['Orden_No'],$value['semana'],$value['Fecha'],$value['total']);
			$tablaHTML[$i]['borde'] =1;
			$i++;
			
		}
		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$desde,$hasta,$sizetable,$mostrar,15,'P',true,null,1,$nuevaPagina=false);
		// return $datos;
	}


	function cargar_orden_excel($parametros)
	{
		$datos = $this->modelo->lineas_pedidos_orden($parametros);
		$titulo = 'O R D E N E S';
		$sizetable =7;
		$mostrar = 1;
		$desde = ''; //$parametros['desde'];
		$hasta = ''; //$parametros['hasta'];
		$tablaHTML= array();

		$tablaHTML[0]['medidas']=array(50,40,30,45,30);
		// $tablaHTML[0]['alineado']=array('L','L','L','L','L');
		$tablaHTML[0]['datos']=array('CONTRATISTA','ORDEN','SEMANA DEL AÑO','FECHA','PRECIO TOTAL');
		$tablaHTML[0]['tipo']='C';
		$tablaHTML[0]['borde'] =1;


		$i = 1;
		foreach ($datos as $key => $value) {
			$tablaHTML[$i]['medidas']=$tablaHTML[0]['medidas'];
			// $tablaHTML[$i]['alineado']=$tablaHTML[0]['alineado'];
			$tablaHTML[$i]['datos']=array($value['Cliente'],$value['Orden_No'],$value['semana'],$value['Fecha'],$value['total']);
			$tablaHTML[$i]['borde'] =1;
			$i++;
			
		}
      	excel_generico($titulo,$tablaHTML);  
	}

	// reporte historial

	function cargar_historial_pdf($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->cargar_datos_historial($parametros);
		$titulo = 'H I S T O R I A L';
		$sizetable =7;
		$mostrar = 1;
		$desde = ''; //$parametros['desde'];
		$hasta = ''; //$parametros['hasta'];
		$tablaHTML= array();

		$tablaHTML[0]['medidas']=array(45,40,40,16,30,20);
		$tablaHTML[0]['alineado']=array('L','L','L','L','L','L');
		$tablaHTML[0]['datos']=array('ORDEN','FECHA PROVEEDOR','PROVEEDOR','CANT','PRECIO COMPRA','TOTAL');
		$tablaHTML[0]['estilo']='B';
		$tablaHTML[0]['borde'] =1;


		$i = 1;
		foreach ($datos as $key => $value) {
			$tablaHTML[$i]['medidas']=$tablaHTML[0]['medidas'];
			$tablaHTML[$i]['alineado']=$tablaHTML[0]['alineado'];
			$tablaHTML[$i]['datos']=array($value['Orden_No'],$value['Fecha'],$value['Cliente'],$value['Entrada'],$value['Valor_Unitario'],$value['Valor_Total']);
			$tablaHTML[$i]['borde'] =1;
			$i++;
			
		}
		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$desde,$hasta,$sizetable,$mostrar,15,'P',true,null,1,$nuevaPagina=false);
		// return $datos;
	}


	function cargar_historial_excel($parametros)
	{
		$datos = $this->modelo->cargar_datos_historial($parametros);
		$titulo = 'H I S T O R I A L';
		$sizetable =7;
		$mostrar = 1;
		$desde = ''; //$parametros['desde'];
		$hasta = ''; //$parametros['hasta'];
		$tablaHTML= array();

		$tablaHTML[0]['medidas']=array(45,40,40,16,30,20);
		$tablaHTML[0]['alineado']=array('L','L','L','L','L','L');
		$tablaHTML[0]['datos']=array('ORDEN','FECHA PROVEEDOR','PROVEEDOR','CANT','PRECIO COMPRA','TOTAL');
		$tablaHTML[0]['tipo']='B';
		$tablaHTML[0]['borde'] =1;


		$i = 1;
		foreach ($datos as $key => $value) {
			$tablaHTML[$i]['medidas']=$tablaHTML[0]['medidas'];
			$tablaHTML[$i]['alineado']=$tablaHTML[0]['alineado'];
			$tablaHTML[$i]['datos']=array($value['Orden_No'],$value['Fecha'],$value['Cliente'],$value['Entrada'],$value['Valor_Unitario'],$value['Valor_Total']);
			$tablaHTML[$i]['borde'] =1;
			$i++;
			
		}
      	excel_generico($titulo,$tablaHTML);  
	}

	// reportes tiempos

	function cargar_tiempos_pdf($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->cargar_datos_tiempos($parametros);
		$titulo = 'T I E M P O S';
		$sizetable =7;
		$mostrar = 1;
		$desde = ''; //$parametros['desde'];
		$hasta = ''; //$parametros['hasta'];
		$tablaHTML= array();

		$tablaHTML[0]['medidas']=array(45,45,45,10,45,10,45,10);
		$tablaHTML[0]['alineado']=array('L','L','L','L','L','L','L','L');
		$tablaHTML[0]['datos']=array('ORDEN','FECHA SOLICITUD','FECHA APROBACION','DIAS','FECHA PROVEEDOR','DIAS APRO-PROVE','FECHA COMPRA','DIAS');
		$tablaHTML[0]['estilo']='B';
		$tablaHTML[0]['borde'] =1;


		$i = 1;
		foreach ($datos as $key => $value) {
			$tablaHTML[$i]['medidas']=$tablaHTML[0]['medidas'];
			$tablaHTML[$i]['alineado']=$tablaHTML[0]['alineado'];
			$tablaHTML[$i]['datos']=array($value['Orden_No'],$value['solicitud'],$value['aprobacion'],$value['dias1'],$value['proveedor'],$value['dias2'],$value['compra'],$value['dias3']);
			$tablaHTML[$i]['borde'] =1;
			$i++;
			
		}
		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$desde,$hasta,$sizetable,$mostrar,15,'L',true,null,1,$nuevaPagina=false);
		// return $datos;
	}


	function cargar_tiempos_excel($parametros)
	{
		$datos = $this->modelo->cargar_datos_tiempos($parametros);
		$titulo = 'T I E M P O S';
		$sizetable =7;
		$mostrar = 1;
		$desde = ''; //$parametros['desde'];
		$hasta = ''; //$parametros['hasta'];
		$tablaHTML= array();

		$tablaHTML[0]['medidas']=array(38,38,38,10,38,10,38,10);
		$tablaHTML[0]['alineado']=array('L','L','L','L','L','L','L','L');		
		$tablaHTML[0]['datos']=array('ORDEN','FECHA SOLICITUD','FECHA APROBACION',"DIAS",'FECHA PROVEEDOR','DIAS APRO-PROVE','FECHA COMPRA',"DIAS");
		$tablaHTML[0]['tipo']='B';
		$tablaHTML[0]['borde'] =1;


		$i = 1;
		foreach ($datos as $key => $value) {
			$tablaHTML[$i]['medidas']=$tablaHTML[0]['medidas'];
			$tablaHTML[$i]['alineado']=$tablaHTML[0]['alineado'];
			$tablaHTML[$i]['datos']=array($value['Orden_No'],$value['solicitud'],$value['aprobacion'],$value['dias1'],$value['proveedor'],$value['dias2'],$value['compra'],$value['dias3']);
			$tablaHTML[$i]['borde'] =1;
			$i++;
			
		}
      	excel_generico($titulo,$tablaHTML);  
	}




}
?>