<?php 
include 'pacienteC.php'; 
include (dirname(__DIR__,2).'/modelo/farmacia/descargosM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */
$controlador = new ingreso_descargosC();
if(isset($_GET['pedido']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->pedidos_paciente($parametros));
}

if(isset($_GET['cargar_pedidos']))
{	
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_pedidos($parametros));
}
if(isset($_GET['areas']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->areas_descargo($query));
}

if(isset($_GET['actualizar_his']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->Actualizar_his($parametros));
}

if(isset($_GET['eli_pedido']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->eliminar_pedido($parametros));
}
if(isset($_GET['imprimir_pdf']))
{
	$parametros= $_GET;
     $controlador->imprimir_pdf($parametros);
}
if(isset($_GET['tabla_detalles']))
{
	$parametros= $_POST['parametros'];
     echo json_encode($controlador->tabla_detalles($parametros));
}
if(isset($_GET['imprimir_excel']))
{   
	$parametros= $_GET;
	$controlador->imprimir_excel($parametros);	
}
if(isset($_GET['imprimir_excel_nega']))
{   
	$parametros= $_GET;
	$controlador->imprimir_excel_nega($parametros);	
}
if(isset($_GET['imprimir_pdf_nega']))
{   
	$parametros= $_GET;
	$controlador->imprimir_pdf_nega($parametros);	
}

class ingreso_descargosC 
{
	private $modelo;
	private $paciente;
	private $pdf;
	function __construct()
	{
		$this->modelo = new descargosM();
		$this->paciente = new pacienteC();
		$this->pdf = new cabecera_pdf();
	}

	function pedidos_paciente($parametros)
	{
		// print_r($parametros);die();

		$num_his = $parametros['cod'];
		if($num_his==0)
		{
		   $parametros=array('query'=>$parametros['ci'],'tipo'=>'R1','codigo'=>'');
		    $dat = $this->paciente->buscar_ficha($parametros);
		    // print_r($parametros);die();
		    $datos = array();
		    foreach ($dat as $key => $value) {
		   	 $datos[0] = array('CI_RUC'=>$value['CI_RUC'],'Cliente'=>$value['Cliente'],'Matricula'=>$value['Matricula']);
		   }
		   return $datos;
		}else
		{
			 // print_r('ssss');die();
		   $parametros=array('query'=>$parametros['ci'],'tipo'=>'R1','codigo'=>'');
		   $dat[0] = $this->paciente->buscar_ficha($parametros);
		   $datos = array();
		   foreach ($dat as $key => $value) {
		   		 $datos[0] = array('CI_RUC'=>$value['ci'],'Cliente'=>$value['nombre'],'Matricula'=>$value['matricula']);
		   }

		   // print_r($datos);die();
		   return $datos;

		}

	}


	function imprimir_pdf_nega($parametros)
	{

		// print_r($_SESSION['NEGATIVOS']['CODIGO_INV']);die();

			$nega = $this->ordenes_negativas(false,$parametros['txt_tipo_filtro'],$parametros['txt_query'],$parametros['txt_desde'],$parametros['txt_hasta'],false);

			$neg = explode(',', str_replace("'","", $nega['cod_inv']));


			// print_r($neg);die();

			$titulo = 'REPORTE INVENTARIO EN NEGATIVOS';
			$tablaHTML = array();
			$Fechaini= $parametros['txt_desde'];
			$Fechafin=$parametros['txt_hasta'];
			$mostrar = true;
			$sizetable = 7;

	  $medidas = array(56,55,62,17);
		$alineado = array('L','L','L','L');
		$pos = 0;
		$borde = 1;
		// print_r($datos);die();
		$gran_total = 0;
		foreach ($nega['ordenes'] as $key => $value){
			$da = $this->modelo->pedido_paciente(false,$tipo='P',$value['ORDEN'],$desde=false,$hasta =false,$busfe=false);
			// print_r($da);die();
			$tablaHTML[$pos]['medidas']=$medidas;
		    $tablaHTML[$pos]['alineado']=$alineado;
		    $tablaHTML[$pos]['datos']=array('');
		    // $tablaHTML[$pos]['borde'] =$borde;
		    $pos+=1;
			$tablaHTML[$pos]['medidas']=$medidas;
		    $tablaHTML[$pos]['alineado']=$alineado;
		    // print_r($value);die();
		    $tablaHTML[$pos]['datos']=array('<b>Nombre:
		    	'.$da[0]['nombre'],'<b>PROCEDIMIENTO:
		    	'.$da[0]['Detalle'],'<b>AREA :
		    	'.$da[0]['subcta'],'<b>No.DESC:
		    	'.$value['ORDEN']);
		    $tablaHTML[$pos]['borde'] =$borde;

		    if($parametros['txt_tipo_filtro']=='f')
		    {
		    	$fechas = $this->modelo->cargar_lineas_pedidos_por_fecha($value['ORDEN'],$Fechaini,$Fechafin);
		    	foreach ($fechas as $key1 => $value1) {
		    		$pos+=1;
		    		$tablaHTML[$pos]['medidas']=array(190);
		            $tablaHTML[$pos]['alineado']=array('L');
		            $tablaHTML[$pos]['datos']=array('<b>Fecha de descargo:'.$value1['Fecha_Fab']->format('Y-m-d'));
		            $tablaHTML[$pos]['borde'] =$borde;
		    		$lineas = $this->modelo->cargar_lineas_pedidos($value['ORDEN'],$value1['Fecha_Fab']->format('Y-m-d'));

		    		$pos+=1;
		    		 $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		             $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		             $tablaHTML[$pos]['datos']=array('<b>CODIGO','<b>PRODUCTO','<b>CANTIDAD','<b>VALOR UNI','<b>VALOR TOTAL');
		             $tablaHTML[$pos]['borde'] =$borde;

		             $total = 0;
		    		 foreach ($lineas as $key => $value2) {
		    		 	$pos+=1;
		    		    $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		                $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		                $tablaHTML[$pos]['datos']=array($value2['CODIGO_INV'],$value2['PRODUCTO'],$value2['CANTIDAD'],$value2['VALOR_UNIT'],$value2['VALOR_TOTAL']);
		                $tablaHTML[$pos]['borde'] =$borde;
		                $total+=$value2['VALOR_TOTAL'];
		    		 }
		    		 $pos+=1;
		    		    $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		                $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		                $tablaHTML[$pos]['datos']=array('','','','TOTAL',$total);
		                $tablaHTML[$pos]['borde'] =$borde;
		                $gran_total+=$total;
		    	     $pos+=1;		    		
		    	}
		    }else
		    {
		    	$fechas = $this->modelo->cargar_lineas_pedidos_por_fecha($value['ORDEN']);
		    	foreach ($fechas as $key1 => $value1) {
		    		$pos+=1;
		    		$tablaHTML[$pos]['medidas']=array(190);
		            $tablaHTML[$pos]['alineado']=array('L');
		            $tablaHTML[$pos]['datos']=array('<b>Fecha de descargo:'.$value1['Fecha_Fab']->format('Y-m-d'));
		            $tablaHTML[$pos]['borde'] =$borde;
		    		$lineas = $this->modelo->cargar_lineas_pedidos($value['ORDEN'],$value1['Fecha_Fab']->format('Y-m-d'));

		    		$pos+=1;
		    		 $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		             $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		             $tablaHTML[$pos]['datos']=array('<b>CODIGO','<b>PRODUCTO','<b>CANTIDAD','<b>VALOR UNI','<b>VALOR TOTAL');
		             $tablaHTML[$pos]['borde'] =$borde;
		             $total=0;
		             $exis = false;
		    		 foreach ($lineas as $key => $value2) {
		    		 	$pos+=1;
		    		 	foreach ($neg as $key => $valuen) {
		    		 		if ($valuen==$value2['CODIGO_INV']) {
		    		 			$exis =true;
		    		 		}
		    		 	}
		    		 	if($exis==true)
		    		 	{
		    		 		 $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		                $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		                $tablaHTML[$pos]['datos']=array("* * ".$value2['CODIGO_INV'],$value2['PRODUCTO'],$value2['CANTIDAD'],$value2['VALOR_UNIT'],$value2['VALOR_TOTAL']);
		                $tablaHTML[$pos]['borde'] =$borde;

		                $total+=$value2['VALOR_TOTAL'];
		                $exis=false;
		              }else
		              {
		              	 $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		                $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		                $tablaHTML[$pos]['datos']=array($value2['CODIGO_INV'],$value2['PRODUCTO'],$value2['CANTIDAD'],$value2['VALOR_UNIT'],$value2['VALOR_TOTAL']);
		                $tablaHTML[$pos]['borde'] =$borde;

		                $total+=$value2['VALOR_TOTAL'];
		              }		    		   
		    		 }
		    		 $pos+=1;
		    		    $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		                $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		                $tablaHTML[$pos]['datos']=array('','','','TOTAL',$total);
		                $tablaHTML[$pos]['borde'] =$borde;
		                 $gran_total+=$total;
		    	     $pos+=1;	    		
		    	}
		    }

			// foreach ($lineas as $key => $value) {
			// 	$lin+=1;
			// }
			$pos+=1;
			
		}
		 $tablaHTML[$pos]['medidas']=array(39,100,13,21,18);
		 $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		 $tablaHTML[$pos]['datos']=array('','','','<b>GRAN TOTAL ',$gran_total);
		 $tablaHTML[$pos]['borde'] =array('T');



			$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$Fechaini,$Fechafin,$sizetable,$mostrar,25,'P');

			// print_r($nega);die();

	}

	function cargar_pedidos($parametros)
	{
		if ($parametros['nega']=='true') {
		if(!isset($_SESSION['NEGATIVOS']['CODIGO_INV']))
		{
		$nega = $this->ordenes_negativas($parametros['codigo'],$parametros['tipo'],$parametros['query'],$parametros['desde'],$parametros['hasta'],$parametros['busfe']);
	    }else
	    {
	    	$nega = $_SESSION['NEGATIVOS']['CODIGO_INV'] ;
	    }
	   }
		// print_r($parametros);die();
	   $arti[0] = '';
		if($parametros['arti']!='')
		{
			$arti = explode('_', $parametros['arti']);
		}
		$datos = $this->modelo->pedido_paciente($parametros['codigo'],$parametros['tipo'],$parametros['query'],$parametros['desde'],$parametros['hasta'],$parametros['busfe'],$parametros['area'],$arti[0]);
		$tr='';
		// print_r($nega);die();		
		foreach ($datos as $key => $value) {			
			$bur = '';
			// print_r($nega)die();

		if ($parametros['nega']=='true') {
			if(isset($nega['ordenes'])){
		
			foreach ($nega['ordenes'] as $key1 => $value1) {
				if($value1['ORDEN'] == $value['ORDEN'])
				{
						$bur = '<i class="fa fa-circle-o text-red"></i>';
						break;
				}
			}
		  }else
		  {
		  	foreach ($nega as $key1 => $value1) {
				if($value1['ORDEN'] == $value['ORDEN'])
				{
						$bur = '<i class="fa fa-circle-o text-red"></i>';
						break;
				}
			}

		  }
		} 
			
			$item = $key+1;
			$d =  dimenciones_tabl(strlen($item));
			$d2 =  dimenciones_tabl(strlen($value['ORDEN']));
			$d3 =  dimenciones_tabl(strlen($value['nombre']));
			$d4 =  dimenciones_tabl(strlen($value['subcta']));
			$d5 =  dimenciones_tabl(strlen($value['importe']));
			$d6 =  dimenciones_tabl(strlen($value['Fecha_Fab']->format('Y-m-d')));
			$d7 =  dimenciones_tabl(strlen('E'));
			$tr.='<tr>
  					<td width="'.$d.'">'.$item.'</td>
  					<td width="'.$d2.'">'.$value['ORDEN'].'</td>
  					<td width="'.$d3.'">'.$bur.' '.$value['nombre'].'</td>
  					<td width="'.$d4.'">'.$value['subcta'].'</td>
  					<td width="'.$d5.'">'.$value['importe'].'</td>
  					<td width="'.$d6.'">'.$value['Fecha_Fab']->format('Y-m-d').'</td>
  					<td width="'.$d7.'">E</td>
  					<td width="90px">
  						<a href="../vista/farmacia.php?mod='.$_SESSION['INGRESO']['modulo_'].'&acc=ingresar_descargos&acc1=Ingresar%20Descargos&b=1&po=subcu&num_ped='.$value['ORDEN'].'&area='.$value['area'].'-'.$value['Detalle'].'&cod='.$value['his'].'#" class="btn btn-sm btn-primary" title="Editar pedido"><span class="glyphicon glyphicon-pencil"></span></a>
  						<button class="btn btn-sm btn-danger" onclick="eliminar_pedido(\''.$value['ORDEN'].'\',\''.$value['area'].'\')"><span class="glyphicon glyphicon-trash"></span></button>
  					</td>
  				</tr>';
		}
		if(count($datos)>0)
		{
			$tabla = array('num_lin'=>0,'tabla'=>$tr);
			return $tabla;
		}else
		{
			$tabla = array('num_lin'=>0,'tabla'=>'<tr><td colspan="7" class="text-center"><b><i>Sin registros...<i></b></td></tr>');
			return $tabla;		
		}

	}

	function ordenes_negativas($codigo_b,$tipo,$query,$desde,$hasta ,$busfe)
	{
		$datos = $this->modelo->productos_procesados($codigo_b,$tipo,$query,$desde,$hasta,$busfe);
		// print_r($datos);die();
		$negativos = '';
		foreach ($datos as $key => $value) 
		{
			$costo =  costo_venta($value['CODIGO_INV']);
			$nega = 0;
			if(!empty($costo))
			{
				$exis = number_format($costo[0]['Existencia']-$value['CANTIDAD'],2);
				if($exis<0)
				{
					$negativos.="'".$value['CODIGO_INV']."',";
				}
			}
		}
		// print_r($negativos);die();
		$negativos = substr($negativos,0,-1);
		$datos = $this->modelo->ordenes_producto_nega($codigo_b,$tipo,$query,$desde,$hasta,$busfe,$negativos);
		// print_r($datos);die();
	    $_SESSION['NEGATIVOS']['CODIGO_INV']  = $datos;
		return array('ordenes'=>$datos,'cod_inv'=>$negativos);

		// print_r($datos);die();

	}

	function areas_descargo($query)
	{
		$datos = $this->modelo->area_descargo($query);
		$areas = array();
		foreach ($datos as $key => $value) {
			$areas[] = array('id'=>$value['Codigo'],'text'=>$value['Detalle']);
			// $areas[] = array('id'=>$value['Codigo'],'text'=>$value['Detalle']);
		}
		return $areas;
	}

	function Actualizar_his($parametros)
	{
		$datos[0]['campo'] ='Matricula';
		$datos[0]['dato'] =$parametros['num'];
		$datosWhere[0]['campo']='CI_RUC';
		$datosWhere[0]['valor']=strval($parametros['ci']);
		$datosWhere[0]['tipo']='string';
		$buscar = array('query'=>$parametros['num'],'tipo'=>'C1','codigo'=>'');
		$exis =  $this->paciente->historial_existente($buscar);
		// print_r($exis);die();
		if($exis==1)
		{
			$resp = $this->modelo->actualizar_his($datos,$datosWhere);
		    return $resp;
		}else
		{
			return -2;
		}
		
	}

	function eliminar_pedido($parametros)
	{
		$resp = $this->modelo->elimina_pedido($parametros);
		return $resp;
	}

	function imprimir_pdf($parametros)
    {
    	// print_r($parametros);die();
  	    $desde = str_replace('-','',$parametros['txt_desde']);
		$hasta = str_replace('-','',$parametros['txt_hasta']);

		$articulo = explode('_',$parametros['ddl_articulo']);
		$datos = $this->modelo->pedido_paciente_distintos(false,$parametros['rbl_buscar'],$parametros['txt_query'],$parametros['txt_desde'],$parametros['txt_hasta'],$parametros['txt_tipo_filtro'],$parametros['ddl_areas_filtro'],$articulo[0]);


		$titulo = 'D E S C A R G O S  R E A L I Z A D O S';
		$sizetable =7;
		$mostrar = TRUE;
		$Fechaini = $parametros['txt_desde'] ;//str_replace('-','',$parametros['Fechaini']);
		$Fechafin = $parametros['txt_hasta']; //str_replace('-','',$parametros['Fechafin']);
		$tablaHTML= array();
		
		$medidas = array(56,55,62,17);
		$alineado = array('L','L','L','L');
		$pos = 0;
		$borde = 1;
		// print_r($datos);die();
		$gran_total = 0;
		foreach ($datos as $key => $value){
			$tablaHTML[$pos]['medidas']=$medidas;
		    $tablaHTML[$pos]['alineado']=$alineado;
		    $tablaHTML[$pos]['datos']=array('');
		    // $tablaHTML[$pos]['borde'] =$borde;
		    $pos+=1;
			$tablaHTML[$pos]['medidas']=$medidas;
		    $tablaHTML[$pos]['alineado']=$alineado;
		    // print_r($value);die();
		    $tablaHTML[$pos]['datos']=array('<b>Nombre:
		    	'.$value['nombre'],'<b>PROCEDIMIENTO:
		    	'.$value['Detalle'],'<b>AREA :
		    	'.$value['subcta'],'<b>No.DESC:
		    	'.$value['ORDEN']);
		    $tablaHTML[$pos]['borde'] =$borde;

		    if($parametros['txt_tipo_filtro']=='f')
		    {
		    	$fechas = $this->modelo->cargar_lineas_pedidos_por_fecha($value['ORDEN'],$Fechaini,$Fechafin);
		    	foreach ($fechas as $key1 => $value1) {
		    		$pos+=1;
		    		$tablaHTML[$pos]['medidas']=array(190);
		            $tablaHTML[$pos]['alineado']=array('L');
		            $tablaHTML[$pos]['datos']=array('<b>Fecha de descargo:'.$value1['Fecha_Fab']->format('Y-m-d'));
		            $tablaHTML[$pos]['borde'] =$borde;
		    		$lineas = $this->modelo->cargar_lineas_pedidos($value['ORDEN'],$value1['Fecha_Fab']->format('Y-m-d'));

		    		$pos+=1;
		    		 $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		             $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		             $tablaHTML[$pos]['datos']=array('<b>CODIGO','<b>PRODUCTO','<b>CANTIDAD','<b>VALOR UNI','<b>VALOR TOTAL');
		             $tablaHTML[$pos]['borde'] =$borde;

		             $total = 0;
		    		 foreach ($lineas as $key => $value2) {
		    		 	$pos+=1;
		    		    $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		                $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		                $tablaHTML[$pos]['datos']=array($value2['CODIGO_INV'],$value2['PRODUCTO'],$value2['CANTIDAD'],$value2['VALOR_UNIT'],$value2['VALOR_TOTAL']);
		                $tablaHTML[$pos]['borde'] =$borde;
		                $total+=$value2['VALOR_TOTAL'];
		    		 }
		    		 $pos+=1;
		    		    $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		                $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		                $tablaHTML[$pos]['datos']=array('','','','TOTAL',$total);
		                $tablaHTML[$pos]['borde'] =$borde;
		                $gran_total+=$total;
		    	     $pos+=1;		    		
		    	}
		    }else
		    {
		    	$fechas = $this->modelo->cargar_lineas_pedidos_por_fecha($value['ORDEN']);
		    	foreach ($fechas as $key1 => $value1) {
		    		$pos+=1;
		    		$tablaHTML[$pos]['medidas']=array(190);
		            $tablaHTML[$pos]['alineado']=array('L');
		            $tablaHTML[$pos]['datos']=array('<b>Fecha de descargo:'.$value1['Fecha_Fab']->format('Y-m-d'));
		            $tablaHTML[$pos]['borde'] =$borde;
		    		$lineas = $this->modelo->cargar_lineas_pedidos($value['ORDEN'],$value1['Fecha_Fab']->format('Y-m-d'));

		    		$pos+=1;
		    		 $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		             $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		             $tablaHTML[$pos]['datos']=array('<b>CODIGO','<b>PRODUCTO','<b>CANTIDAD','<b>VALOR UNI','<b>VALOR TOTAL');
		             $tablaHTML[$pos]['borde'] =$borde;
		             $total=0;
		    		 foreach ($lineas as $key => $value2) {
		    		 	$pos+=1;
		    		    $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		                $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		                $tablaHTML[$pos]['datos']=array($value2['CODIGO_INV'],$value2['PRODUCTO'],$value2['CANTIDAD'],$value2['VALOR_UNIT'],$value2['VALOR_TOTAL']);
		                $tablaHTML[$pos]['borde'] =$borde;

		                $total+=$value2['VALOR_TOTAL'];
		    		 }
		    		 $pos+=1;
		    		    $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		                $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		                $tablaHTML[$pos]['datos']=array('','','','TOTAL',$total);
		                $tablaHTML[$pos]['borde'] =$borde;
		                 $gran_total+=$total;
		    	     $pos+=1;	    		
		    	}
		    }

			// foreach ($lineas as $key => $value) {
			// 	$lin+=1;
			// }
			$pos+=1;
			
		}
		 $tablaHTML[$pos]['medidas']=array(39,100,13,21,18);
		 $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		 $tablaHTML[$pos]['datos']=array('','','','<b>GRAN TOTAL ',$gran_total);
		 $tablaHTML[$pos]['borde'] =array('T');


		if($parametros['txt_tipo_filtro']=='f')
		{
			$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$Fechaini,$Fechafin,$sizetable,$mostrar,25,'P');
		}else
		{
			$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,false,false,$sizetable,$mostrar,25,'P');
		
		}

  }

  function tabla_detalles($parametros)
  {
  	// print_r($parametros);die();
  	    $desde = str_replace('-','',$parametros['desde']);
		$hasta = str_replace('-','',$parametros['hasta']);

		$arti[0] = '';
		if($parametros['arti']!='')
		{
			$arti = explode('_', $parametros['arti']);
		}
		$datos = $this->modelo->pedido_paciente_distintos(false,$parametros['tipo'],$parametros['query'],$parametros['desde'],$parametros['hasta'],$parametros['busfe'],$parametros['area'],$arti[0]);

         $html = '';
		foreach ($datos as $key => $value){
			$html.='<tr style="background: skyblue;">
                <td colspan="2"><b>NOMBRE: </b> '.$value['nombre'].'</td>
                <td><b>PROCEDIMIENTO: </b> '.$value['Detalle'].'</td>
                <td><b>AREA: </b> '.$value['subcta'].'</td>
                <td><b>No. DESCARGO</b>'.$value['ORDEN'].'</td>                
              </tr>';
		    if($parametros['busfe']=='f')
		    {
		    	$fechas = $this->modelo->cargar_lineas_pedidos_por_fecha($value['ORDEN'],$desde,$hasta);
		    	foreach ($fechas as $key1 => $value1) {

		    		$html.='<tr  style="background: skyblue;">
                              <td colspan="5"><b>FECHA DE DESCARGO:</b>'.$value1['Fecha_Fab']->format('Y-m-d').'</td>
                           </tr>
                           <tr>
                             <td><b>CODIGO</b></td>
                             <td><b>PRODUCTO</b></td>
                             <td><b>CANTIDAD</b></td>
                             <td><b>VALOR UNI</b></td>
                             <td><b>VALOR TOTAL</b></td>
                           </tr>';
		             $total = 0;

		    		$lineas = $this->modelo->cargar_lineas_pedidos($value['ORDEN'],$value1['Fecha_Fab']->format('Y-m-d'));
		    		 foreach ($lineas as $key => $value2) {
		    		 $html.='<tr>
                             <td>'.$value2['CODIGO_INV'].'</td>
                             <td>'.$value2['PRODUCTO'].'</td>
                             <td>'.$value2['CANTIDAD'].'</td>
                             <td>'.$value2['VALOR_UNIT'].'</td>
                             <td>'.$value2['VALOR_TOTAL'].'</td>
                           </tr>';
		                $total+=$value2['VALOR_TOTAL'];
		    		 }
		    		$html.='<tr>
		    		          <td colspan="3"></td>
                              <td><b>TOTAL</b></td>
                              <td><b>'.$total.'</b></td>
                           </tr>';
		    	}
		    }else
		    {
		    	$fechas = $this->modelo->cargar_lineas_pedidos_por_fecha($value['ORDEN']);
		    	foreach ($fechas as $key1 => $value1) {
		    		$html.='<tr style="background: skyblue;">
                              <td colspan="5"><b>FECHA DE DESCARGO:</b>'.$value1['Fecha_Fab']->format('Y-m-d').'</td>
                           </tr>
                           <tr>
                             <td><b>CODIGO</b></td>
                             <td><b>PRODUCTO</b></td>
                             <td><b>CANTIDAD</b></td>
                             <td><b>VALOR UNI</b></td>
                             <td><b>VALOR TOTAL</b></td>
                           </tr>';
		             $total=0;		             
		    		$lineas = $this->modelo->cargar_lineas_pedidos($value['ORDEN'],$value1['Fecha_Fab']->format('Y-m-d'));
		    		 foreach ($lineas as $key => $value2) {
		    		 	 $html.='<tr>
                             <td>'.$value2['CODIGO_INV'].'</td>
                             <td>'.$value2['PRODUCTO'].'</td>
                             <td>'.$value2['CANTIDAD'].'</td>
                             <td>'.$value2['VALOR_UNIT'].'</td>
                             <td>'.$value2['VALOR_TOTAL'].'</td>
                           </tr>';
		                $total+=$value2['VALOR_TOTAL'];
		    		 }
		    		$html.='<tr>
		    		          <td colspan="3"></td>
                              <td><b>TOTAL</b></td>
                              <td><b>'.$total.'</b></td>
                           </tr>';    		
		    	}
		    }
		}

		// print_r($html);die();

		return $html;

  }

 //  function imprimir_excel1($parametros)
	// {
	//  $_SESSION['INGRESO']['ti']='DESCARGOS REALIZADOS';
	//  $Fechaini = $parametros['txt_desde'] ;//str_replace('-','',$parametros['Fechaini']);
 //     $Fechafin = $parametros['txt_hasta']; //str_replace('-','',$parametros['Fechafin']);
		
	//  $datos = $this->modelo->pedido_paciente_distintos(false,$parametros['rbl_buscar'],$parametros['txt_query'],$parametros['txt_desde'],$parametros['txt_hasta'],$parametros['txt_tipo_filtro']);

	//  $registros = array();
	//  $reg_fecha = array();
	//  $reg_lineas = array();
	//  foreach ($datos as $key => $value){
	//  	    // print_r($value);die();
	// 		$registros[$key] = array('Nombre'=>$value['nombre'],'Procedimiento'=>$value['Detalle'],'Area'=>$value['subcta'],'Descargo'=>$value['ORDEN'],'registros'=>array());
	// 	    if($parametros['txt_tipo_filtro']=='f')
	// 	    {
	// 	    	$fechas = $this->modelo->cargar_lineas_pedidos_por_fecha($value['ORDEN'],$Fechaini,$Fechafin);

	// 	    	foreach ($fechas as $key1 => $value1) {

	// 	    		$reg_fecha[$value1['Fecha_Fab']->format('Y-m-d')]= array();		    		
	// 	    		$lineas = $this->modelo->cargar_lineas_pedidos($value['ORDEN'],$value1['Fecha_Fab']->format('Y-m-d'));
	// 	    		 foreach ($lineas as $key2 => $value2) {
	// 	    		 	$reg_lineas[] = array('codigo'=>$value2['CODIGO_INV'],'cantidad'=>$value2['CANTIDAD'],'producto'=>$value2['PRODUCTO'],'pre_uni'=>$value2['VALOR_UNIT'],'total'=>$value2['VALOR_TOTAL']);
	// 	    		 }

	// 	    		  array_push($reg_fecha[$value1['Fecha_Fab']->format('Y-m-d')],$reg_lineas);
	// 	    		  $reg_lineas = array();

	// 	    		  // print_r($reg_fecha[$value1['Fecha_Fab']->format('Y-m-d')]);die();
	// 	    	}
	// 	    	   array_push($registros[$key]['registros'],$reg_fecha);
	// 	    	   $reg_fecha=array();	  		    		 
	// 	    }else
	// 	    {
	// 	    	$fechas = $this->modelo->cargar_lineas_pedidos_por_fecha($value['ORDEN']);
	// 	    	foreach ($fechas as $key1 => $value1) {		    		
	// 	    		$reg_fecha[$value1['Fecha_Fab']->format('Y-m-d').'_'.$value['ORDEN']]= array();	
	// 	    		$lineas = $this->modelo->cargar_lineas_pedidos($value['ORDEN'],$value1['Fecha_Fab']->format('Y-m-d'));  		
	// 	    		 foreach ($lineas as $key2 => $value2) {
	// 	    		 	$reg_lineas[$key2] = array('codigo'=>$value2['CODIGO_INV'],'cantidad'=>$value2['CANTIDAD'],'producto'=>$value2['PRODUCTO'],'pre_uni'=>$value2['VALOR_UNIT'],'total'=>$value2['VALOR_TOTAL']);		    		 	
	// 	    		 }

	// 	    		// print_r($reg_lineas);		print_r($reg_fecha[$value1['Fecha_Fab']->format('Y-m-d').'_'.$value['ORDEN']]);	  
	// 	    		  array_push($reg_fecha[$value1['Fecha_Fab']->format('Y-m-d').'_'.$value['ORDEN']],$reg_lineas);
	// 	    		  $reg_lineas = array();
	// 	    	}

	// 	    	   array_push($registros[$key]['registros'],$reg_fecha);
	// 	    	   $reg_fecha=array();	    		
	// 	    }

	// 	    // print_r($registros);die();

	// 		// foreach ($lineas as $key => $value) {
	// 		// 	$lin+=1;
	// 		// }

	// 			// print_r($registros[$key]);die();
	// 	}

	// 	    		  // print_r($reg_fecha['2021-03-05']);die();

	// 	// print_r($registros);die();

	//  $this->modelo->imprimir_excel($registros);
	// }


  function imprimir_excel($parametros)
	{
	 $titulo='D E S C A R G O S  R E A L I Z A D O S';
	 $Fechaini = $parametros['txt_desde'] ;//str_replace('-','',$parametros['Fechaini']);
     $Fechafin = $parametros['txt_hasta']; //str_replace('-','',$parametros['Fechafin']);

     $arti[0] = '';
	if($parametros['ddl_articulo']!='')
	{
		$arti = explode('_', $parametros['ddl_articulo']);
	}
		
	 $datos = $this->modelo->pedido_paciente_distintos(false,$parametros['rbl_buscar'],$parametros['txt_query'],$parametros['txt_desde'],$parametros['txt_hasta'],$parametros['txt_tipo_filtro'],$parametros['ddl_areas_filtro'],$arti[0]);

	 $registros = array();
	 $reg_fecha = array();
	 $reg_lineas = array();
	 $tablaHTML= array();

	 // print_r($datos);die();

	  $pos=0;

	 foreach ($datos as $key => $value) {
	 	 $tablaHTML[$pos]['medidas']=array(50,8,18,18,18);
         $tablaHTML[$pos]['datos']=array('Nombre: '.$value['nombre'],'','Procedimiento: '.$value['Detalle'],'Area: '.$value['subcta'],'Descargo No: '.$value['ORDEN']);
         $tablaHTML[$pos]['tipo'] ='C';
         $tablaHTML[$pos]['col-total'] =5;
         $tablaHTML[$pos]['unir'] =array('AB');
         $pos+=1;
          if($parametros['txt_tipo_filtro']=='f')
		    {
		    	$fechas = $this->modelo->cargar_lineas_pedidos_por_fecha($value['ORDEN'],$Fechaini,$Fechafin);
		    	$gran_total = 0;
		    	foreach ($fechas as $key => $value1) {	    		
		    		$lineas = $this->modelo->cargar_lineas_pedidos($value['ORDEN'],$value1['Fecha_Fab']->format('Y-m-d'));  
		    		 $tablaHTML[$pos]['medidas']=array(50,50,50,50,50);
		             $tablaHTML[$pos]['datos']=array('FECHA: '.$value1['Fecha_Fab']->format('Y-m-d'),'','','','');
		             $tablaHTML[$pos]['tipo'] ='SUB';
		             $tablaHTML[$pos]['unir'] = array('ABCD');
		             $pos+=1;		    		 
		    		 $tablaHTML[$pos]['medidas']=array(50,20,50,25,25);
		             $tablaHTML[$pos]['datos']=array('CODIGO','CANTIDAD','PRODUCTO','PRECIO UNI','TOTAL');
		             $tablaHTML[$pos]['tipo'] ='SUB';
		             $total=0;
		             $pos+=1;
		    		 foreach ($lineas as $key2 => $value2) {
		    		 	 $tablaHTML[$pos]['medidas']=$tablaHTML[$pos-1]['medidas'];
			             $tablaHTML[$pos]['datos']=array($value2['CODIGO_INV'],$value2['CANTIDAD'],$value2['PRODUCTO'],$value2['VALOR_UNIT'],$value2['VALOR_TOTAL']);
			             $tablaHTML[$pos]['tipo'] ='N';
			             $total+=$value2['VALOR_TOTAL'];
			             $pos+=1;		 	
		    		 }	
		    		 	 $tablaHTML[$pos]['medidas']=$tablaHTML[$pos-1]['medidas'];
			             $tablaHTML[$pos]['datos']=array('','','','TOTAL', $total);
			             $tablaHTML[$pos]['tipo'] ='N';
			             $gran_total+=$total;
			             $pos+=1;		 	

		    	}
		    	 $tablaHTML[$pos]['medidas']=$tablaHTML[$pos-1]['medidas'];
	             $tablaHTML[$pos]['datos']=array('','','','GRAN TOTAL', $gran_total);
	             $tablaHTML[$pos]['tipo'] ='SUB';
	             $pos+=1;	

		    }else
		    {
		    	$fechas = $this->modelo->cargar_lineas_pedidos_por_fecha($value['ORDEN']);
		    	$gran_total = 0;
		    	foreach ($fechas as $key => $value1) {	    		
		    		$lineas = $this->modelo->cargar_lineas_pedidos($value['ORDEN'],$value1['Fecha_Fab']->format('Y-m-d'));  
		    		 $tablaHTML[$pos]['medidas']=array(50,50,50,50,50);
		             $tablaHTML[$pos]['datos']=array('FECHA: '.$value1['Fecha_Fab']->format('Y-m-d'),'','','','');
		             $tablaHTML[$pos]['tipo'] ='SUB';
		             $tablaHTML[$pos]['unir'] = array('ABCD');
		             $pos+=1;		    		 
		    		 $tablaHTML[$pos]['medidas']=array(50,20,50,25,25);
		             $tablaHTML[$pos]['datos']=array('CODIGO','CANTIDAD','PRODUCTO','PRECIO UNI','TOTAL');
		             $tablaHTML[$pos]['tipo'] ='SUB';
		             $total=0;
		             $pos+=1;
		    		 foreach ($lineas as $key2 => $value2) {
		    		 	 $tablaHTML[$pos]['medidas']=$tablaHTML[$pos-1]['medidas'];
			             $tablaHTML[$pos]['datos']=array($value2['CODIGO_INV'],$value2['CANTIDAD'],$value2['PRODUCTO'],$value2['VALOR_UNIT'],$value2['VALOR_TOTAL']);
			             $tablaHTML[$pos]['tipo'] ='N';
			             $total+=$value2['VALOR_TOTAL'];
			             $pos+=1;		 	
		    		 }	
		    		 	 $tablaHTML[$pos]['medidas']=$tablaHTML[$pos-1]['medidas'];
			             $tablaHTML[$pos]['datos']=array('','','','TOTAL', $total);
			             $tablaHTML[$pos]['tipo'] ='N';
			             $gran_total+=$total;
			             $pos+=1;		 	

		    	}
		    	 $tablaHTML[$pos]['medidas']=$tablaHTML[$pos-1]['medidas'];
	             $tablaHTML[$pos]['datos']=array('','','','GRAN TOTAL', $gran_total);
	             $tablaHTML[$pos]['tipo'] ='SUB';
	             $pos+=1;	

		    }
	 	
	 }
	 excel_generico($titulo,$tablaHTML);
	}

function imprimir_excel_nega1($parametros)
	{
		 $_SESSION['INGRESO']['ti']='ordenes en negativo';
		$nega = $this->ordenes_negativas(false,$parametros['txt_tipo_filtro'],$parametros['txt_query'],$parametros['txt_desde'],$parametros['txt_hasta'],false);

			$neg = explode(',', str_replace("'","", $nega['cod_inv']));
			$Fechaini= $parametros['txt_desde'];
			$Fechafin=$parametros['txt_hasta'];


	 $registros = array();
	 $reg_fecha = array();
	 $reg_lineas = array();
	 foreach ($nega['ordenes'] as $key => $value){
	 	// print_r($value);die();

			$da = $this->modelo->pedido_paciente(false,$tipo='P',$value['ORDEN'],$desde=false,$hasta =false,$busfe=false);
	 	    // print_r($value);die();
			$registros[$key] = array('Nombre'=>$da[0]['nombre'],'Procedimiento'=>$da[0]['Detalle'],'Area'=>$da[0]['subcta'],'Descargo'=>$value['ORDEN'],'registros'=>array());
		    if($parametros['txt_tipo_filtro']=='f')
		    {
		    	$fechas = $this->modelo->cargar_lineas_pedidos_por_fecha($value['ORDEN'],$Fechaini,$Fechafin);

		    	foreach ($fechas as $key1 => $value1) {

		    		$reg_fecha[$value1['Fecha_Fab']->format('Y-m-d')]= array();		    		
		    		$lineas = $this->modelo->cargar_lineas_pedidos($value['ORDEN'],$value1['Fecha_Fab']->format('Y-m-d'));
		    		 foreach ($lineas as $key2 => $value2) {
		    		 	$reg_lineas[] = array('codigo'=>$value2['CODIGO_INV'],'cantidad'=>$value2['CANTIDAD'],'producto'=>$value2['PRODUCTO'],'pre_uni'=>$value2['VALOR_UNIT'],'total'=>$value2['VALOR_TOTAL']);
		    		 }

		    		  array_push($reg_fecha[$value1['Fecha_Fab']->format('Y-m-d')],$reg_lineas);
		    		  $reg_lineas = array();

		    		  // print_r($reg_fecha[$value1['Fecha_Fab']->format('Y-m-d')]);die();
		    	}
		    	   array_push($registros[$key]['registros'],$reg_fecha);
		    	   $reg_fecha=array();	  		    		 
		    }else
		    {
		    	$fechas = $this->modelo->cargar_lineas_pedidos_por_fecha($value['ORDEN']);
		    	$esta = false;
		    	foreach ($fechas as $key1 => $value1) {		    		
		    		$reg_fecha[$value1['Fecha_Fab']->format('Y-m-d').'_'.$value['ORDEN']]= array();	
		    		$lineas = $this->modelo->cargar_lineas_pedidos($value['ORDEN'],$value1['Fecha_Fab']->format('Y-m-d'));  		
		    		 foreach ($lineas as $key2 => $value2) {
		    		 	$indice = array_search($value2['CODIGO_INV'],$neg,true);

		    		 	if($indice)
		    		 	{

		    		  		$reg_lineas[$key2] = array('codigo'=>'**'.$value2['CODIGO_INV'],'cantidad'=>$value2['CANTIDAD'],'producto'=>$value2['PRODUCTO'],'pre_uni'=>$value2['VALOR_UNIT'],'total'=>$value2['VALOR_TOTAL']);
		    		  }else
		    		  {
		    		  	$reg_lineas[$key2] = array('codigo'=>$value2['CODIGO_INV'],'cantidad'=>$value2['CANTIDAD'],'producto'=>$value2['PRODUCTO'],'pre_uni'=>$value2['VALOR_UNIT'],'total'=>$value2['VALOR_TOTAL']);
		    		  }
		    		 		    		 	
		    		 }

		    		// print_r($reg_lineas);		print_r($reg_fecha[$value1['Fecha_Fab']->format('Y-m-d').'_'.$value['ORDEN']]);	  
		    		  array_push($reg_fecha[$value1['Fecha_Fab']->format('Y-m-d').'_'.$value['ORDEN']],$reg_lineas);
		    		  $reg_lineas = array();
		    	}

		    	   array_push($registros[$key]['registros'],$reg_fecha);
		    	   $reg_fecha=array();	    		
		    }

		    // print_r($registros);die();

			// foreach ($lineas as $key => $value) {
			// 	$lin+=1;
			// }

				// print_r($registros[$key]);die();
		}

		    		  // print_r($reg_fecha['2021-03-05']);die();

		// print_r($registros);die();

	 $this->modelo->imprimir_excel($registros);
	}


	function imprimir_excel_nega($parametros)
	{
		// print_r($parametros);die();
		$titulo='O R D E N E S  E N  N E G A T I V O';
		$nega = $this->ordenes_negativas(false,$parametros['txt_tipo_filtro'],$parametros['txt_query'],$parametros['txt_desde'],$parametros['txt_hasta'],false);

			$neg = explode(',', str_replace("'","", $nega['cod_inv']));
			$Fechaini= $parametros['txt_desde'];
			$Fechafin=$parametros['txt_hasta'];
			$tablaHTML= array();
			$pos = 0;
		foreach ($nega['ordenes'] as $key => $value) {
			$da = $this->modelo->pedido_paciente(false,$tipo='P',$value['ORDEN'],$desde=false,$hasta =false,$busfe=false);
			 $tablaHTML[$pos]['medidas']=array(50,8,18,18,18);
	         $tablaHTML[$pos]['datos']=array('Nombre: '.$da[0]['nombre'],'','Procedimiento: '.$da[0]['Detalle'],'Area: '.$da[0]['subcta'],'Descargo No: '.$value['ORDEN']);
	         $tablaHTML[$pos]['tipo'] ='C';
	         $tablaHTML[$pos]['col-total'] =5;
	         $tablaHTML[$pos]['unir'] =array('AB');
	         $pos+=1;
	          if($parametros['txt_tipo_filtro']=='f')
	            {
            		$fechas = $this->modelo->cargar_lineas_pedidos_por_fecha($value['ORDEN'],$Fechaini,$Fechafin);
            		// print_r($fechas);die();
			    	$gran_total = 0;
			    	foreach ($fechas as $key => $value1) {	    		
			    		$lineas = $this->modelo->cargar_lineas_pedidos($value['ORDEN'],$value1['Fecha_Fab']->format('Y-m-d'));  
			    		 $tablaHTML[$pos]['medidas']=array(50,50,50,50,50);
			             $tablaHTML[$pos]['datos']=array('FECHA: '.$value1['Fecha_Fab']->format('Y-m-d'),'','','','');
			             $tablaHTML[$pos]['tipo'] ='SUB';
			             $tablaHTML[$pos]['unir'] = array('ABCD');
			             $pos+=1;		    		 
			    		 $tablaHTML[$pos]['medidas']=array(50,20,50,25,25);
			             $tablaHTML[$pos]['datos']=array('CODIGO','CANTIDAD','PRODUCTO','PRECIO UNI','TOTAL');
			             $tablaHTML[$pos]['tipo'] ='SUB';
			             $total=0;
			             $pos+=1;
			    		 foreach ($lineas as $key2 => $value2) {
			    		 	$indice = array_search($value2['CODIGO_INV'],$neg,true);
			    		 	// print_r($indice);die();
			    		 	if($indice)
			    		 	{
				    		 	 $tablaHTML[$pos]['medidas']=$tablaHTML[$pos-1]['medidas'];
					             $tablaHTML[$pos]['datos']=array('***'.$value2['CODIGO_INV'],$value2['CANTIDAD'],$value2['PRODUCTO'],$value2['VALOR_UNIT'],$value2['VALOR_TOTAL']);
					             $tablaHTML[$pos]['tipo'] ='N';
					             $total+=$value2['VALOR_TOTAL'];
					             $pos+=1;
				             }else
				             {
				             	$tablaHTML[$pos]['medidas']=$tablaHTML[$pos-1]['medidas'];
					             $tablaHTML[$pos]['datos']=array($value2['CODIGO_INV'],$value2['CANTIDAD'],$value2['PRODUCTO'],$value2['VALOR_UNIT'],$value2['VALOR_TOTAL']);
					             $tablaHTML[$pos]['tipo'] ='N';
					             $total+=$value2['VALOR_TOTAL'];
					             $pos+=1;

				             }		 	
			    		 }	
			    		 	 $tablaHTML[$pos]['medidas']=$tablaHTML[$pos-1]['medidas'];
				             $tablaHTML[$pos]['datos']=array('','','','TOTAL', $total);
				             $tablaHTML[$pos]['tipo'] ='N';
				             $gran_total+=$total;
				             $pos+=1;		 	

			    	}
			    	 $tablaHTML[$pos]['medidas']=$tablaHTML[$pos-1]['medidas'];
		             $tablaHTML[$pos]['datos']=array('','','','GRAN TOTAL', $gran_total);
		             $tablaHTML[$pos]['tipo'] ='SUB';
		             $pos+=1;

	            }else
	            {
	            	$fechas = $this->modelo->cargar_lineas_pedidos_por_fecha($value['ORDEN']);
			    	$gran_total = 0;
			    	foreach ($fechas as $key => $value1) {	    		
			    		$lineas = $this->modelo->cargar_lineas_pedidos($value['ORDEN'],$value1['Fecha_Fab']->format('Y-m-d'));  
			    		 $tablaHTML[$pos]['medidas']=array(50,50,50,50,50);
			             $tablaHTML[$pos]['datos']=array('FECHA: '.$value1['Fecha_Fab']->format('Y-m-d'),'','','','');
			             $tablaHTML[$pos]['tipo'] ='SUB';
			             $tablaHTML[$pos]['unir'] = array('ABCD');
			             $pos+=1;		    		 
			    		 $tablaHTML[$pos]['medidas']=array(50,20,50,25,25);
			             $tablaHTML[$pos]['datos']=array('CODIGO','CANTIDAD','PRODUCTO','PRECIO UNI','TOTAL');
			             $tablaHTML[$pos]['tipo'] ='SUB';
			             $total=0;
			             $pos+=1;
			    		 foreach ($lineas as $key2 => $value2) {
			    		 	$indice = array_search($value2['CODIGO_INV'],$neg,true);
			    		 	// print_r($indice);die();
			    		 	if($indice)
			    		 	{
				    		 	 $tablaHTML[$pos]['medidas']=$tablaHTML[$pos-1]['medidas'];
					             $tablaHTML[$pos]['datos']=array('***'.$value2['CODIGO_INV'],$value2['CANTIDAD'],$value2['PRODUCTO'],$value2['VALOR_UNIT'],$value2['VALOR_TOTAL']);
					             $tablaHTML[$pos]['tipo'] ='N';
					             $total+=$value2['VALOR_TOTAL'];
					             $pos+=1;
				             }else
				             {
				             	$tablaHTML[$pos]['medidas']=$tablaHTML[$pos-1]['medidas'];
					             $tablaHTML[$pos]['datos']=array($value2['CODIGO_INV'],$value2['CANTIDAD'],$value2['PRODUCTO'],$value2['VALOR_UNIT'],$value2['VALOR_TOTAL']);
					             $tablaHTML[$pos]['tipo'] ='N';
					             $total+=$value2['VALOR_TOTAL'];
					             $pos+=1;

				             }		 	
			    		 }	
			    		 	 $tablaHTML[$pos]['medidas']=$tablaHTML[$pos-1]['medidas'];
				             $tablaHTML[$pos]['datos']=array('','','','TOTAL', $total);
				             $tablaHTML[$pos]['tipo'] ='N';
				             $gran_total+=$total;
				             $pos+=1;		 	

			    	}
			    	 $tablaHTML[$pos]['medidas']=$tablaHTML[$pos-1]['medidas'];
		             $tablaHTML[$pos]['datos']=array('','','','GRAN TOTAL', $gran_total);
		             $tablaHTML[$pos]['tipo'] ='SUB';
		             $pos+=1;	
		   		 }	

	        }


			
		// }


	 // $registros = array();
	 // $reg_fecha = array();
	 // $reg_lineas = array();
	 // foreach ($nega['ordenes'] as $key => $value){
	 // 	// print_r($value);die();

		// 	$da = $this->modelo->pedido_paciente(false,$tipo='P',$value['ORDEN'],$desde=false,$hasta =false,$busfe=false);
	 // 	    // print_r($value);die();
		// 	$registros[$key] = array('Nombre'=>$da[0]['nombre'],'Procedimiento'=>$da[0]['Detalle'],'Area'=>$da[0]['subcta'],'Descargo'=>$value['ORDEN'],'registros'=>array());
		//     if($parametros['txt_tipo_filtro']=='f')
		//     {
		//     	$fechas = $this->modelo->cargar_lineas_pedidos_por_fecha($value['ORDEN'],$Fechaini,$Fechafin);

		//     	foreach ($fechas as $key1 => $value1) {

		//     		$reg_fecha[$value1['Fecha_Fab']->format('Y-m-d')]= array();		    		
		//     		$lineas = $this->modelo->cargar_lineas_pedidos($value['ORDEN'],$value1['Fecha_Fab']->format('Y-m-d'));
		//     		 foreach ($lineas as $key2 => $value2) {
		//     		 	$reg_lineas[] = array('codigo'=>$value2['CODIGO_INV'],'cantidad'=>$value2['CANTIDAD'],'producto'=>$value2['PRODUCTO'],'pre_uni'=>$value2['VALOR_UNIT'],'total'=>$value2['VALOR_TOTAL']);
		//     		 }

		//     		  array_push($reg_fecha[$value1['Fecha_Fab']->format('Y-m-d')],$reg_lineas);
		//     		  $reg_lineas = array();

		//     		  // print_r($reg_fecha[$value1['Fecha_Fab']->format('Y-m-d')]);die();
		//     	}
		//     	   array_push($registros[$key]['registros'],$reg_fecha);
		//     	   $reg_fecha=array();	  		    		 
		//     }else
		//     {
		//     	$fechas = $this->modelo->cargar_lineas_pedidos_por_fecha($value['ORDEN']);
		//     	$esta = false;
		//     	foreach ($fechas as $key1 => $value1) {		    		
		//     		$reg_fecha[$value1['Fecha_Fab']->format('Y-m-d').'_'.$value['ORDEN']]= array();	
		//     		$lineas = $this->modelo->cargar_lineas_pedidos($value['ORDEN'],$value1['Fecha_Fab']->format('Y-m-d'));  		
		//     		 foreach ($lineas as $key2 => $value2) {
		//     		 	$indice = array_search($value2['CODIGO_INV'],$neg,true);

		//     		 	if($indice)
		//     		 	{

		//     		  		$reg_lineas[$key2] = array('codigo'=>'**'.$value2['CODIGO_INV'],'cantidad'=>$value2['CANTIDAD'],'producto'=>$value2['PRODUCTO'],'pre_uni'=>$value2['VALOR_UNIT'],'total'=>$value2['VALOR_TOTAL']);
		//     		  }else
		//     		  {
		//     		  	$reg_lineas[$key2] = array('codigo'=>$value2['CODIGO_INV'],'cantidad'=>$value2['CANTIDAD'],'producto'=>$value2['PRODUCTO'],'pre_uni'=>$value2['VALOR_UNIT'],'total'=>$value2['VALOR_TOTAL']);
		//     		  }
		    		 		    		 	
		//     		 }

		//     		// print_r($reg_lineas);		print_r($reg_fecha[$value1['Fecha_Fab']->format('Y-m-d').'_'.$value['ORDEN']]);	  
		//     		  array_push($reg_fecha[$value1['Fecha_Fab']->format('Y-m-d').'_'.$value['ORDEN']],$reg_lineas);
		//     		  $reg_lineas = array();
		//     	}

		//     	   array_push($registros[$key]['registros'],$reg_fecha);
		//     	   $reg_fecha=array();	    		
		//     }

		    // print_r($registros);die();

			// foreach ($lineas as $key => $value) {
			// 	$lin+=1;
			// }

				// print_r($registros[$key]);die();
		// }

		    		  // print_r($reg_fecha['2021-03-05']);die();

		// print_r($registros);die();


	 excel_generico($titulo,$tablaHTML);
	 // $this->modelo->imprimir_excel($registros);
	}







}