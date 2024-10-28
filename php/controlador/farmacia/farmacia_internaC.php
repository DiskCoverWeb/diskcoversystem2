<?php 
include (dirname(__DIR__,2).'/modelo/farmacia/farmacia_internaM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */
$controlador = new farmacia_internaC();
if(isset($_GET['tabla_ingresos']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->tabla_ingresos($parametros));
}
if(isset($_GET['tabla_catalogo']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->tabla_catalogo($parametros));
}
if(isset($_GET['tabla_catalogo_bodega']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->tabla_catalogo_bodega($parametros));
}
if(isset($_GET['cargar_pedidos']))
{	
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_pedidos($parametros));
}

if(isset($_GET['descargos_medicamentos']))
{	
	$parametros = $_POST['parametros'];
	$paginacion = $_POST['paginacion'];
	echo json_encode($controlador->descargos_medicamentos($parametros,$paginacion));
}

if(isset($_GET['imprimir_pdf']))
{	
	$parametros = $_GET;
	echo json_encode($controlador->reporte_pdf($parametros));
}

if(isset($_GET['imprimir_excel']))
{	
	$parametros = $_GET;
	echo json_encode($controlador->reporte_excel($parametros));
}

if(isset($_GET['imprimir_pdf_bode']))
{	
	$parametros = $_GET;
	echo json_encode($controlador->reporte_pdf_bode($parametros));
}

if(isset($_GET['imprimir_excel_bode']))
{	
	$parametros = $_GET;
	echo json_encode($controlador->reporte_excel_bode($parametros));
}


if(isset($_GET['imprimir_excel_detalle']))
{	
	$parametros = $_GET;
	echo json_encode($controlador->reporte_excel_detalle($parametros));
}

if(isset($_GET['detalle_reporte_ingresos']))
{	
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->detalle_reporte_ingreso($parametros));
}
if(isset($_GET['detalle_reporte_descargos']))
{	
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->detalle_reporte_descargos($parametros));
}
class farmacia_internaC 
{
	private $modelo;
	private $ing_descargos;
	private $pdf;
	function __construct()
	{
		$this->modelo = new farmacia_internaM();
		$this->pdf = new cabecera_pdf();
	}

	function tabla_ingresos($parametros)
	{
		$pro[2] = '';
		if($parametros['proveedor']!='')
		{
		$pro = explode('-',$parametros['proveedor']);
	    }
		// print_r($parametros);die();
		$datos = $this->modelo->tabla_ingresos($pro[2],$parametros['comprobante'],$parametros['factura'],$parametros['serie']);
		return $datos['tbl'];
	}

	function tabla_catalogo($parametros)
	{
		$query ='';
		if($parametros['descripcion']!='')
		{
			$q = explode('_',$parametros['descripcion']);
			$query = $q[0];
		}
		if($parametros['referencia']!='')
		{
			$q = explode('_',$parametros['referencia']);
			$query = $q[0];
		}
		$datos = $this->modelo->tabla_catalogo($query,$parametros['tipo']);
		$tr='';
		foreach ($datos['datos'] as $key => $value) {
			// print_r($value);die();
			$tra = $this->modelo->costo_producto_comprobante($value['Codigo']);
			$ad = $this->modelo->fecha_cantidad_ultimo_ingreso($value['Codigo']);
			if(count($tra)>0 && count($ad)>0)
			{
				// print_r($ad);die();
			$tr.='<tr>
			<td>'.$value['Codigo'].'</td>
			<td>'.$value['Producto'].'</td>
			<td>'.$tra[0]['Existencia'].'</td>
			<td>'.number_format($ad[0]['Valor_Unitario'],2).'</td>
			<td>'.$ad[0]['Fecha']->format('Y-m-d').'</td>
			<td>'.$ad[0]['Entrada'].'</td>
			</tr>';			
		   }
		}
		return $tr;
	}

	function tabla_catalogo_bodega($parametros)
	{
		$query ='';
		if($parametros['descripcion']!='')
		{
			$q = explode('_',$parametros['descripcion']);
			$query = $q[0];
		}
		if($parametros['referencia']!='')
		{
			$q = explode('_',$parametros['referencia']);
			$query = $q[0];
		}
		$familia = false;
		if($parametros['familia']!='')
		{
			$familia = $parametros['familia'];
		}
		$ubicacion = false;
		if($parametros['ubi']!='')
		{
			$ubicacion = $parametros['ubi'];
		}


		$datos = $this->modelo->tabla_catalogo_bodega($query,$parametros['tipo'],$familia,$ubicacion);
		$tr='';
		foreach ($datos as $key => $value) {
			// print_r($value);die();
			$tra = $this->modelo->costo_producto_comprobante($value['Codigo']);
			if(count($tra)>0)
			{
				if ($value['Valor_Unit'] === null) {
					$value['Valor_Unit'] = 0;
				}
			$tr.='<tr>
			<td>'.$value['Codigo'].'</td>
			<td>'.$value['Producto'].'</td>
			<td>'.$value['Cuenta'].'</td>
			<td>'.number_format($value['Valor_Unit'],2).'</td>
			<td>'.$tra[0]['Existencia'].'</td>
			<td>'.$value['Ubicacion'].'</td>
			<td>
				<button class="btn btn-primary btn-sm" onclick="cargar_datos('.$value['ID'].')"><i class="fa fa-pencil"></i></button>
				<button class="btn btn-danger btn-sm" onclick="eliminar('.$value['ID'].')"><i class="fa fa-trash-o"></i></button>
			</td>
			</tr>';			
		   }
		}
		return $tr;
	}


	function cargar_pedidos($parametros)
	{
		
		// print_r($parametros);die();
		$datos = $this->modelo->pedido_paciente($parametros['nom'],$parametros['ci'],$parametros['historia'],$parametros['depar'],$parametros['proce'],$parametros['desde'],$parametros['hasta'],$parametros['busfe']);
		return $datos;

	}

    function descargos_medicamentos($parametros,$paginacion)
    {
    	// print_r($parametros);die();
    	$resp = $this->modelo->descargos_medicamentos($parametros['medicamento'],$parametros['nom'],$parametros['ci'],$parametros['depar'],$parametros['desde'],$parametros['hasta'],$parametros['busfe']);
    	$total_consumido = 0;
    	foreach ($resp['datos'] as $key => $value) {
    		$total_consumido+=$value['Cantidad'];
    	}
    	$resp['Total_consumido'] = $total_consumido;
    	return $resp;
    }
    function reporte_pdf($parametros)
    {
    	// print_r($parametros);die();
    	switch ($parametros['opcion']) {
    		case 1:
	    		$pro[2] = '';
				if($parametros['ddl_proveedor']!='')
				{
				$pro = explode('-',$parametros['ddl_proveedor']);
			    }
			    $titulo = 'R E P O R T E   D E   I N G R E S O S';
			    $Fechaini = ''; $Fechafin='';
	    		$datos = $this->modelo->tabla_ingresos($pro[2],$parametros['txt_comprobante'],$parametros['txt_factura']);
	    			 $tablaHTML[0]['medidas']=array(25,100,30,25);
		             $tablaHTML[0]['alineado']=array('L','L','L','L');
		             $tablaHTML[0]['datos']=array('<b>Fecha','<b>Cliente','<b>Comprobante','<b>Factura');
		             $tablaHTML[0]['borde'] =1;
		             $pos = 1;
	    		foreach ($datos['datos'] as $key => $value) {
	    			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		             $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		             $tablaHTML[$pos]['datos']=array($value['Fecha']->format('Y-m-d'),$value['Cliente'],$value['Comprobante'],$value['Factura']);
		             $tablaHTML[$pos]['borde'] =1;
		             $pos+=1;
	    		}
	    		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$Fechaini,$Fechafin,false,true,25,'P');
    			break;
    		case 2:
    			$query ='';
				if(isset($parametros['ddl_descripcion']) && $parametros['ddl_descripcion']!='')
				{
					$q = explode('_',$parametros['ddl_descripcion']);
					$query = $q[0];
				}
				if(isset($parametros['ddl_referencia']) && $parametros['ddl_referencia']!='')
				{
					$q = explode('_',$parametros['ddl_referencia']);
					$query = $q[0];
				}
			    $titulo = 'L I S T A D O   D E L   C A T A L O G O';
			    $Fechaini = ''; $Fechafin='';
	    		$datos = $this->modelo->tabla_catalogo($query,'ref');
	    		$listado= array();
	    		foreach ($datos['datos'] as $key => $value) {
	    			$tra = $this->modelo->costo_producto_comprobante($value['Codigo']);
	    			if(count($tra)>0)
	    			{
	    			$listado[] = array('Codigo'=>$value['Codigo'],'Producto'=>$value['Producto'],'Cantidad'=>$tra[0]['Existencia'],'Valor_Total'=>$tra[0]['Total']);
	    		    }
	    		}
	    			 $tablaHTML[0]['medidas']=array(25,100,30,25);
		             $tablaHTML[0]['alineado']=array('L','L','L','L');
		             $tablaHTML[0]['datos']=array('<b>Codigo','<b>Producto','<b>Cantidad','<b>Valor Total');
		             $tablaHTML[0]['borde'] =1;
		             $pos = 1;
	    		foreach ($listado as $key => $value) {
	    			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		             $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		             $tablaHTML[$pos]['datos']=array($value['Codigo'],$value['Producto'],$value['Cantidad'],$value['Valor_Total']);
		             $tablaHTML[$pos]['borde'] =1;
		             $pos+=1;
	    		}
	    		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$Fechaini,$Fechafin,false,true,25,'P');
    			break;
    		case 4:
    			$f = '';
    			if(isset($parametros['rbl_fecha'])){$f=1;}

    			$datos = $this->modelo->pedido_paciente($parametros['txt_paciente'],$parametros['txt_ci'],$parametros['txt_historia'],$parametros['txt_departamento'],$parametros['txt_procedimiento'],$parametros['txt_desde'],$parametros['txt_hasta'],$f);
    			// print_r($datos);die();
    		 	$titulo = 'DESCARGOS PARA VISUALIZAR POR PACIENTE';
    		 	$Fechaini =''; $Fechafin='';
    		 	if($parametros['txt_desde'] != $parametros['txt_hasta'])
    		 	{
			    $Fechaini =$parametros['txt_desde'] ; $Fechafin=$parametros['txt_hasta'];
			    }
			    $tablaHTML = array();
	    			 $tablaHTML[0]['medidas']=array(25,85,25,20,50,20,48);
		             $tablaHTML[0]['alineado']=array('L','L','L','L','L','R','L');
		             $tablaHTML[0]['datos']=array('<b>Fecha','<b>Paciente','<b>Cedula','<b>Historia','<b>Departamento','<b>Importe','<b>	Procedimiento');
		             $tablaHTML[0]['borde'] =1;
		             $pos = 1;
	    		foreach ($datos['datos'] as $key => $value) {
	    			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		             $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		             $tablaHTML[$pos]['datos']=array($value['Fecha']->format('Y-m-d'),$value['Paciente'],$value['Cedula'],$value['Historia'],$value['Departamento'],$value['importe'],$value['Procedimiento']);
		             $tablaHTML[$pos]['borde'] =1;
		             $pos+=1;
	    		}
	    		// print_r($tablaHTML);die();
	    		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$Fechaini,$Fechafin,false,true,25,'L');
    			break;
    		case 5:
    		    $f = '';
    			if(isset($parametros['rbl_fecha5'])){$f='true';}
    			$Fechaini =''; $Fechafin='';
    		 	if($parametros['txt_desde5'] != $parametros['txt_hasta5'])
    		 	{
			    $Fechaini =$parametros['txt_desde5'] ; $Fechafin=$parametros['txt_hasta5'];
			    }
    			$titulo = 'VISUALIZACION DE DESCARGOS DE FARMACIA INTERNA';
			    $Fechaini = ''; $Fechafin='';
	    		$datos = $this->modelo->descargos_medicamentos($parametros['txt_medicamento'],$parametros['txt_paciente5'],$parametros['txt_ci_ruc'],$parametros['txt_departamento5'],$parametros['txt_desde5'],$parametros['txt_hasta5'],$f);
	    			 $tablaHTML[0]['medidas']=array(25,80,85,25,23,45);
		             $tablaHTML[0]['alineado']=array('L','L','L','L','L','L');
		             $tablaHTML[0]['datos']=array('<b>Fecha','<b>Producto','<b>Cliente','<b>Cedula','<b>Matricula','<b>Departamento');
		             $tablaHTML[0]['borde'] =1;
		             $pos = 1;
	    		foreach ($datos['datos'] as $key => $value) {
	    			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		             $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		             $tablaHTML[$pos]['datos']=array($value['Fecha']->format('Y-m-d'),$value['Producto'],$value['Cliente'],$value['Cedula'],$value['Matricula'],$value['Departamento']);
		             $tablaHTML[$pos]['borde'] =1;
		             $pos+=1;
	    		}
	    		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$Fechaini,$Fechafin,false,true,25,'L');
    			break;    			
    		
    		default:
    			// code...
    			break;
    	}

    }

     function reporte_pdf_bode($parametros)
    {

    	// print_r($parametros);die();
    	$query ='';
    	$tipo = 'ref';
		if(isset($parametros['ddl_descripcion']) && $parametros['ddl_descripcion']!='')
		{
			$q = explode('_',$parametros['ddl_descripcion']);
			$query = $q[0];
		}
		if( isset($parametros['ddl_referencia']) &&  $parametros['ddl_referencia']!='')
		{
			$q = explode('_',$parametros['ddl_referencia']);
			$query = $q[0];
		}
		$familia = false;
		if( isset($parametros['ddl_familia']) &&  $parametros['ddl_familia']!='')
		{
			$familia = $parametros['ddl_familia'];
		}
		$ubicacion = false;
		if($parametros['txt_ubicacion']!='')
		{
			$ubicacion = $parametros['txt_ubicacion'];
		}
		$datos = $this->modelo->tabla_catalogo_bodega($query,$tipo,$familia,$ubicacion,false);

		// print_r($datos);die();
	    $titulo = 'L I S T A  D E  A R T I C U L O S';
	    $Fechaini = ''; $Fechafin='';
			 $tablaHTML[0]['medidas']=array(25,80,30,25,25);
             $tablaHTML[0]['alineado']=array('L','L','L','L','L');
             $tablaHTML[0]['datos']=array('<b>Codigo','<b>Producto','<b>Familia','<b>Precio','<b>Stock');
             $tablaHTML[0]['borde'] =1;
             $pos = 1;
		foreach ($datos as $key => $value) {
			$tra = $this->modelo->costo_producto_comprobante($value['Codigo']);
			// print_r($tra);die();
			if(count($tra)>0)
			{

			 	 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
            	 $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
            	 $tablaHTML[$pos]['datos']=array($value['Codigo'],$value['Producto'],$value['Cuenta'],$value['Valor_Unit'],$tra[0]['Existencia']);
            	 $tablaHTML[$pos]['borde'] =1;
            	 $pos+=1;
         	}
		}
		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$Fechaini,$Fechafin,false,true,25,'P');

    }

    	// $proveedor = $datos[0]['Cliente'];
    	// $fecha = $datos[0]['Fecha']->format('Y-m-d');
    	// $total = 0;
    	// $tr='';
    	// foreach ($datos as $key => $value) {
    	// 	$tr.='<tr>
    	// 	<td>'.$value['Fecha']->format('Y-m-d').'</td>
    	// 	<td>'.$value['Producto'].'</td>
    	// 	<td>'.$value['Entrada'].'</td>
    	// 	<td>'.$value['Valor_Unitario'].'</td>
    	// 	<td>'.$value['Valor_Total'].'</td>
    	// 	</tr>';
    	// 	$total+=$value['Valor_Total'];
    	// }

    function reporte_excel_detalle($parametros)
    {
    	// print_r($parametros);die();
    	switch ($parametros['opcion']) {
    		case '1':
    			$titulo = 'D E T A L L E   D E   F A C T U R A';
			    $Fechaini = ''; $Fechafin='';
			    $numero = $parametros['comprobante'];
	    		$datos = $this->modelo->detalle_reportes_ingresos($numero);
	    		
	    		$tablaHTML[0]['medidas']=array(13,50,18,18,18);
		        $tablaHTML[0]['datos']=array('','Proveedor:'.$datos[0]['Cliente'],'Fecha: '.$datos[0]['Fecha']->format('Y-m-d'),'Numero factura:'.$parametros['factura'],'');
		        $tablaHTML[0]['tipo'] ='C';
		        $tablaHTML[0]['unir'] = array('AB','DE');

	    			 $tablaHTML[1]['medidas']=array(13,50,18,18,18);
		             $tablaHTML[1]['datos']=array('Fecha','Producto','Cantidad','PVP','Total');
		             $tablaHTML[1]['tipo'] ='C';
		             $pos = 2;
		             $total = 0;
	    		foreach ($datos as $key => $value) {
	    			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		             $tablaHTML[$pos]['datos']=array($value['Fecha']->format('Y-m-d'),$value['Producto'],$value['Entrada'],$value['Valor_Unitario'],$value['Valor_Total']);
		             $tablaHTML[$pos]['tipo'] ='N';
		             $pos+=1;
		             $total+=$value['Valor_Total'];
	    		}
	    			$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		             $tablaHTML[$pos]['datos']=array('','','','Total',$total);
		             $tablaHTML[$pos]['tipo'] ='SUB';

	    		 excel_generico($titulo,$tablaHTML);

    			break;
    		
    		case '4':
    			$titulo = 'D E T A L L E   D E   D E S C A R G O';
			    $Fechaini = ''; $Fechafin='';
			    $numero = $parametros['comprobante'];
			    $orden = false;
			    $label = 'Numero de Pedido:';
	    		if($numero>0){$orden = false; $label = 'Numero de comprobante:'; }else{$numero = false;$orden = $parametros['factura'];}
	    		if($numero>0){$num = $numero; }else{ $num= $orden;}
		    	$datos = $this->modelo->detalle_reportes_descargos($numero,$orden);	    		

	    		$tablaHTML[0]['medidas']=array(13,50,18,18,18);
		        $tablaHTML[0]['datos']=array('','Proveedor:'.$datos[0]['Cliente'],'Fecha: '.$datos[0]['Fecha']->format('Y-m-d'),$label.$num,'');
		        $tablaHTML[0]['tipo'] ='C';
		        $tablaHTML[0]['unir'] = array('AB','DE');

	    			 $tablaHTML[1]['medidas']=array(13,50,18,18,18);
		             $tablaHTML[1]['datos']=array('Fecha','Producto','Cantidad','PVP','Total');
		             $tablaHTML[1]['tipo'] ='C';
		             $pos = 2;
		             $total = 0;
	    		foreach ($datos as $key => $value) {
	    			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		             $tablaHTML[$pos]['datos']=array($value['Fecha']->format('Y-m-d'),$value['Producto'],$value['Entrada'],$value['Valor_Unitario'],$value['Valor_Total']);
		             $tablaHTML[$pos]['tipo'] ='N';
		             $pos+=1;
		             $total+=$value['Valor_Total'];
	    		}
	    			$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		             $tablaHTML[$pos]['datos']=array('','','','Total',$total);
		             $tablaHTML[$pos]['tipo'] ='SUB';

	    		 excel_generico($titulo,$tablaHTML);
    			break;
    	}

    }

    function reporte_excel($parametros)
    {
    		switch ($parametros['opcion']) {
    		case 1:
	    		$pro[2] = '';
				if($parametros['ddl_proveedor']!='')
				{
				$pro = explode('-',$parametros['ddl_proveedor']);
			    }
			    $titulo = 'R E P O R T E   D E   I N G R E S O S';
			    $Fechaini = ''; $Fechafin='';
	    		$datos = $this->modelo->tabla_ingresos($pro[2],$parametros['txt_comprobante'],$parametros['txt_factura']);
	    			 $tablaHTML[0]['medidas']=array(13,50,18,18);
		             $tablaHTML[0]['datos']=array('Fecha','Cliente','Comprobante','Factura');
		             $tablaHTML[0]['tipo'] ='C';
		             $pos = 1;
	    		foreach ($datos['datos'] as $key => $value) {
	    			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		             $tablaHTML[$pos]['datos']=array($value['Fecha']->format('Y-m-d'),$value['Proveedor'],$value['Comprobante'],$value['Factura']);
		             $tablaHTML[$pos]['tipo'] ='N';
		             $pos+=1;
	    		}
	    		 excel_generico($titulo,$tablaHTML);
	    		// $this->pdf->cabecera_reporte_MC($titulo,$tablaHTML);
    			break;
    		case 2:
    			$query ='';
				if(isset($parametros['ddl_descripcion']) && $parametros['ddl_descripcion']!='')
				{
					$q = explode('_',$parametros['ddl_descripcion']);
					$query = $q[0];
				}
				if(isset($parametros['ddl_referencia']) && $parametros['ddl_referencia']!='')
				{
					$q = explode('_',$parametros['ddl_referencia']);
					$query = $q[0];
				}
			    $titulo = 'L I S T A D O   D E L   C A T A L O G O';
			    $Fechaini = ''; $Fechafin='';
	    		$datos = $this->modelo->tabla_catalogo($query,'ref');
	    		$listado = array();
	    		foreach ($datos['datos'] as $key => $value) {
	    			$tra = $this->modelo->costo_producto($value['Codigo']);
	    			$tr = $this->modelo->fecha_cantidad_ultimo_ingreso($value['Codigo']);
	    			if(count($tra)>0)
	    			{
	    			$listado[] = array('Codigo'=>$value['Codigo'],'Producto'=>$value['Producto'],'Cantidad'=>$tra[0]['Existencia'],'Valor_Total'=>$tra[0]['Total'],'fecha'=>$tr[0]['Fecha']->format('Y-m-d'),'can'=>$tr[0]['Entrada']);
	    		    }
	    		}
	    			 $tablaHTML[0]['medidas']=array(15,50,30,25,30,25);
		             $tablaHTML[0]['datos']=array('Codigo','Producto','Cantidad','Valor Total','Ultimo ingreso','Cantidad ing.');
		             $tablaHTML[0]['tipo'] ='C';
		             $pos = 1;
	    		foreach ($listado as $key => $value) {
	    			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		             $tablaHTML[$pos]['datos']=array($value['Codigo'],$value['Producto'],$value['Cantidad'],$value['Valor_Total'],$value['fecha'],$value['can']);
		             $tablaHTML[$pos]['tipo'] ='N';
		             $pos+=1;
	    		}

	    		 excel_generico($titulo,$tablaHTML);
	    		//$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$Fechaini,$Fechafin,false,true,25,'P');
    			break;
    		case 4:
    			$f = '';
    			if(isset($parametros['rbl_fecha'])){$f=1;}

    			$datos = $this->modelo->pedido_paciente($parametros['txt_paciente'],$parametros['txt_ci'],$parametros['txt_historia'],$parametros['txt_departamento'],$parametros['txt_procedimiento'],$parametros['txt_desde'],$parametros['txt_hasta'],$f);
    			// print_r($datos);die();
    		 	$titulo = 'DESCARGOS PARA VISUALIZAR POR PACIENTE';
    		 	$Fechaini =''; $Fechafin='';
    		 	if($parametros['txt_desde'] != $parametros['txt_hasta'])
    		 	{
			    $Fechaini =$parametros['txt_desde'] ; $Fechafin=$parametros['txt_hasta'];
			    }
			    $tablaHTML = array();
	    			 $tablaHTML[0]['medidas']=array(13,50,25,20,50,20,48);
		             $tablaHTML[0]['datos']=array('Fecha','Paciente','Cedula','Historia','Departamento','Importe','Procedimiento');
		             $tablaHTML[0]['tipo'] ='C';
		             $pos = 1;
	    		foreach ($datos['datos'] as $key => $value) {
	    			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		             $tablaHTML[$pos]['datos']=array($value['Fecha']->format('Y-m-d'),$value['Paciente'],$value['Cedula'],$value['Historia'],$value['Departamento'],$value['importe'],$value['Procedimiento']);
		             $tablaHTML[$pos]['tipo'] ='N';
		             $pos+=1;
	    		}
	    		// print_r($tablaHTML);die();	    		
	    		 excel_generico($titulo,$tablaHTML);
    			break;
    		case 5:
    		// print_r($parametros);die();
    		    $f = '';
    			if(isset($parametros['rbl_fecha5'])){$f=1;}
    			$Fechaini =''; $Fechafin='';
    		 	if($parametros['txt_desde5'] != $parametros['txt_hasta5'])
    		 	{
			    $Fechaini =$parametros['txt_desde5'] ; $Fechafin=$parametros['txt_hasta5'];
			    }
    			$titulo = 'VISUALIZACION DE DESCARGOS DE FARMACIA INTERNA';
			    $Fechaini = ''; $Fechafin='';
	    		$datos = $this->modelo->descargos_medicamentos($parametros['txt_medicamento'],$parametros['txt_paciente5'],$parametros['txt_ci_ruc'],$parametros['txt_departamento5'],$parametros['txt_desde5'],$parametros['txt_hasta5'],$f);
	    			 $tablaHTML[0]['medidas']=array(13,50,50,25,23,40,25);
		             $tablaHTML[0]['datos']=array('Fecha','Producto','Cliente','Cedula','Matricula','Departamento','Cantidad');
		             $tablaHTML[0]['tipo'] ='C';
		             $pos = 1;
		             $total = 0;
	    		foreach ($datos['datos'] as $key => $value) {
	    			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		             $tablaHTML[$pos]['datos']=array($value['Fecha']->format('Y-m-d'),$value['Producto'],$value['Cliente'],$value['Cedula'],$value['Matricula'],$value['Departamento'],$value['Cantidad']);
		             $tablaHTML[$pos]['tipo'] ='N';
		             $pos+=1;
		             $total+=$value['Cantidad'];
	    		}	    				
	    			$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		             $tablaHTML[$pos]['datos']=array('','','','','','Total Cosumido',$total);
		             $tablaHTML[$pos]['tipo'] ='SUB';
		             $pos+=1;
		             $total+=$value['Cantidad'];

	    		 excel_generico($titulo,$tablaHTML);
    			break;    			
    		
    		default:
    			// code...
    			break;
    	}
    }


    function reporte_excel_bode($parametros)
    {
		// print_r($parametros);die();
	    // $titulo = 'L I S T A   D E   A R T I C U L O S';
	    $query ='';
    	$tipo = 'ref';
		if(isset($parametros['ddl_descripcion']) && $parametros['ddl_descripcion']!='')
		{
			$q = explode('_',$parametros['ddl_descripcion']);
			$query = $q[0];
		}
		if( isset($parametros['ddl_referencia']) &&  $parametros['ddl_referencia']!='')
		{
			$q = explode('_',$parametros['ddl_referencia']);
			$query = $q[0];
		}
		$familia = false;
		if( isset($parametros['ddl_familia']) &&  $parametros['ddl_familia']!='')
		{
			$familia = $parametros['ddl_familia'];
		}
		$ubicacion = false;
		if($parametros['txt_ubicacion']!='')
		{
			$ubicacion = $parametros['txt_ubicacion'];
		}
		$datos = $this->modelo->tabla_catalogo_bodega($query,$tipo,$familia,$ubicacion,false);

		// print_r($datos);die();
	    $titulo = 'L I S T A  D E  A R T I C U L O S';
	    $Fechaini = ''; $Fechafin='';
			 $tablaHTML[0]['medidas']=array(25,80,30,25,25);
             $tablaHTML[0]['alineado']=array('L','L','L','L','L');
             $tablaHTML[0]['datos']=array('Codigo','Producto','Familia',' Precio','Stock');
             $tablaHTML[0]['tipo'] ='C';
             $pos = 1;
		foreach ($datos as $key => $value) {
			$tra = $this->modelo->costo_producto_comprobante($value['Codigo']);
			// print_r($tra);die();
			if(count($tra)>0)
			{

			 	 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
            	 $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
            	 $tablaHTML[$pos]['datos']=array($value['Codigo'],$value['Producto'],$value['Cuenta'],$value['Valor_Unit'],$tra[0]['Existencia']);
            	 $tablaHTML[$pos]['tipo'] ='N';
            	 $pos+=1;
         	}
		}
		 excel_generico($titulo,$tablaHTML);
	    		// $this->pdf->cabecera_reporte_MC($titulo,$tablaHTML);
    }




    function detalle_reporte_ingreso($parametros)
    {
    	$numero = $parametros['comprobante'];
    	$datos = $this->modelo->detalle_reportes_ingresos($numero);
    	$proveedor = $datos[0]['Cliente'];
    	$fecha = $datos[0]['Fecha']->format('Y-m-d');
    	$total = 0;
    	$tr='';
    	foreach ($datos as $key => $value) {
    		$tr.='<tr>
    		<td>'.$value['Fecha']->format('Y-m-d').'</td>
    		<td>'.$value['Producto'].'</td>
    		<td>'.$value['Entrada'].'</td>
    		<td>'.$value['Valor_Unitario'].'</td>
    		<td>'.$value['Valor_Total'].'</td>
    		</tr>';
    		$total+=$value['Valor_Total'];
    	}
    	$datos = array('tabla'=>$tr,'proveedor'=>$proveedor,'fecha'=>$fecha,'total'=>number_format($total,2,'.',''));
    	return $datos;
    	print_r($datos);die();
    }

    function detalle_reporte_descargos($parametros)
    {
    	// print_r($parametros);die();
    	$numero = $parametros['comprobante'];
    	$orden = $parametros['orden'];
    	if($numero>0){$orden = false;}
    	$datos = $this->modelo->detalle_reportes_descargos($numero,$orden);
    	$proveedor = $datos[0]['Cliente'];
    	$fecha = $datos[0]['Fecha']->format('Y-m-d');
    	$total = 0;
    	$tr='';
    	foreach ($datos as $key => $value) {
    		$tr.='<tr>
    		<td>'.$value['Fecha']->format('Y-m-d').'</td>
    		<td>'.$value['Producto'].'</td>
    		<td>'.$value['Salida'].'</td>
    		<td>'.$value['Valor_Unitario'].'</td>
    		<td>'.$value['Valor_Total'].'</td>
    		</tr>';
    	$total+=$value['Valor_Total']; 
    	}
    	$datos = array('tabla'=>$tr,'proveedor'=>$proveedor,'fecha'=>$fecha,'total'=>number_format($total,2,'.',''));
    	return $datos;
    	print_r($datos);die();
    }

}