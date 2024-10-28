<?php 
include 'pacienteC.php'; 
include (dirname(__DIR__,2).'/modelo/farmacia/reportes_descargos_procesadosM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */
$controlador = new reportes_descargos_procesadosC();
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

if(isset($_GET['Ver_comprobante_clinica']))
{   
	$parametros= $_GET['comprobante'];
	$controlador->ver_comprobante_clinica($parametros);	
}

if(isset($_GET['datos_comprobante']))
{   
	$parametros= $_POST['comprobante'];
	echo json_encode($controlador->datos_comprobante($parametros));	
}


class reportes_descargos_procesadosC 
{
	private $modelo;
	private $paciente;
	private $pdf;
	function __construct()
	{
		$this->modelo = new reportes_descargos_procesadosM();
		$this->paciente = new pacienteC();
		$this->pdf = new cabecera_pdf();
	}

	

	function cargar_pedidos($parametros,$paginacion)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->cargar_comprobantes($parametros['query'],$parametros['desde'],$parametros['hasta'],$parametros['busfe'],$paginacion,$parametros['area']);
		return $tabla = array('num_lin'=>0,'tabla'=>$datos);
		// print_r($datos);die();
		$tr='';
		// print_r($datos);die();
		foreach ($datos as $key => $value) {
			$item = $key+1;
			
			$d =  dimenciones_tabl(strlen($item));
			$d2 =  dimenciones_tabl(strlen($value['Numero']));
			$d3 =  dimenciones_tabl(strlen($value['Fecha']->format('Y-m-d')));
			$d4 =  dimenciones_tabl(strlen($value['Concepto']));
			$d5 =  dimenciones_tabl(strlen($value['Cliente']));
			$d6 =  dimenciones_tabl(strlen($value['Monto_Total']));
			$tr.='<tr>
  					<td width="'.$d.'">'.$item.'</td>
  					<td width="'.$d2.'">'.$value['Numero'].'</td>
  					<td width="'.$d3.'">'.$value['Fecha']->format('Y-m-d').'</td>
  					<td width="'.$d4.'">'.$value['Concepto'].'</td>
  					<td width="'.$d5.'">'.$value['Cliente'].'</td>
  					<td width="'.$d6.'">'.$value['Monto_Total'].'</td>
  					<td width="90px">
  						<!-- <a href="#" class="btn btn-sm btn-primary" title="Editar pedido"><span class="glyphicon glyphicon-pencil"></span></a>
  						<button class="btn btn-sm btn-default" title="Revisar contenido" onclick="eliminar_pedido()"><span class="glyphicon glyphicon-list"></span></button>-->
  					</td>
  				</tr>';
  		 
		}

		// print_r($tr);
		// print_r($datos); die();
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

	function imprimir_pdf($parametros)
    {
    	// print_r($parametros['txt_desde']);die();
  	    $desde = str_replace('-','',$parametros['txt_desde']);
		$hasta = str_replace('-','',$parametros['txt_hasta']);

		$datos = $this->modelo->cargar_comprobantes_datos($parametros['txt_query'],$parametros['txt_desde'],$parametros['txt_hasta'],$parametros['txt_tipo_filtro']);


		$titulo = 'CARGO DE INSUMOS Y MEDICAMENTOS DE PACIENTE PRIVADO';
		$sizetable =7;
		$mostrar = TRUE;
		$Fechaini = $parametros['txt_desde'] ;//str_replace('-','',$parametros['Fechaini']);
		$Fechafin = $parametros['txt_hasta']; //str_replace('-','',$parametros['Fechafin']);
		$tablaHTML= array();
		
		$medidas =array(17,52,17,70,17,17);
		$alineado = array('L','L','L','L','L','L');
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
		    $tablaHTML[$pos]['datos']=array('<b>Paciente:',$value['Cliente'],'<b>Detalle:',$value['Concepto'],'<b>No.Comp:',$value['Numero']);
		    $tablaHTML[$pos]['borde'] =$borde;

		    if($parametros['txt_tipo_filtro']=='f')
		    {
		    	     $lineas = $this->modelo->trans_kardex($value['Numero']);
		    		 $pos+=1;
		    		 $tablaHTML[$pos]['medidas']=array(39,85,16,25,25);
		             $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		             $tablaHTML[$pos]['datos']=array('<b>CODIGO','<b>PRODUCTO','<b>CANTIDAD','<b>VALOR UNI','<b>VALOR TOTAL');
		             $tablaHTML[$pos]['borde'] =$borde;

		             $total = 0;
		    		 foreach ($lineas as $key => $value2) {
		    		 
		    		 	$pro = $this->modelo->producto($value2['Codigo_Inv']);
		    		 	$pos+=1;
		    		    $tablaHTML[$pos]['medidas']=array(39,85,16,25,25);
		                $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		                $tablaHTML[$pos]['datos']=array($value2['Codigo_Inv'],$pro[0]['Producto'],$value2['Salida'],$value2['Valor_Unitario'],$value2['Valor_Total']);
		                $tablaHTML[$pos]['borde'] =$borde;
		                $total+=$value2['Valor_Total'];
		    		 }
		    		 $pos+=1;
		    		    $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		                $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		                $tablaHTML[$pos]['datos']=array('','','','TOTAL',$total);
		                $tablaHTML[$pos]['borde'] =$borde;
		                $gran_total+=$total;
		    	     $pos+=1;		    		
		    	
		    }else
		    {
		    	 $lineas = $this->modelo->trans_kardex($value['Numero']);
		    		 $pos+=1;
		    		 $tablaHTML[$pos]['medidas']=array(39,85,16,25,25);
		             $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		             $tablaHTML[$pos]['datos']=array('<b>CODIGO','<b>PRODUCTO','<b>CANTIDAD','<b>VALOR UNI','<b>VALOR TOTAL');
		             $tablaHTML[$pos]['borde'] =$borde;

		             $total = 0;
		    		 foreach ($lineas as $key => $value2) {		    		 	
		    		 	$pro = $this->modelo->producto($value2['Codigo_Inv']);
		    		 	$pos+=1;
		    		    $tablaHTML[$pos]['medidas']=array(39,85,16,25,25);
		                $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		                $tablaHTML[$pos]['datos']=array($value2['Codigo_Inv'],$pro[0]['Producto'],$value2['Salida'],$value2['Valor_Unitario'],$value2['Valor_Total']);
		                $tablaHTML[$pos]['borde'] =$borde;
		                $total+=$value2['Valor_Total'];
		    		 }
		    		 $pos+=1;
		    		    $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		                $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		                $tablaHTML[$pos]['datos']=array('','','','TOTAL',$total);
		                $tablaHTML[$pos]['borde'] =$borde;
		                $gran_total+=$total;
		    	     $pos+=1;		
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
		
		$datos = $this->modelo->cargar_comprobantes($parametros['query'],$parametros['desde'],$parametros['hasta'],$parametros['busfe']);

         $html = '';
		foreach ($datos as $key => $value){

			$html.='<tr style="background: skyblue;">
                <td colspan="2"><b>NOMBRE: </b> '.$value['Cliente'].'</td>
                <td><b>DETALLE: </b> '.$value['Concepto'].'</td>
                <td><b>FECHA: </b> '.$value['Fecha']->format('Y-m-d').'</td>
                <td><b>No. Compro.</b>'.$value['Numero'].'</td>                
              </tr>';
		    if($parametros['busfe']=='f')
		    {
		    		$html.='<tr  style="background: skyblue;">
                              
                           </tr>
                           <tr>
                             <td><b>CODIGO</b></td>
                             <td><b>PRODUCTO</b></td>
                             <td><b>CANTIDAD</b></td>
                             <td><b>VALOR UNI</b></td>
                             <td><b>VALOR TOTAL</b></td>
                           </tr>';
		             $total = 0;

		    		$lineas = $this->modelo->trans_kardex($value['Numero']);
		    		 foreach ($lineas as $key => $value2) {
		    		 	$pro = $this->modelo->producto($value2['Codigo_Inv']);
		    		 $html.='<tr>
                             <td>'.$value2['Codigo_Inv'].'</td>
                             <td>'.$pro[0]['Producto'].'</td>
                             <td>'.$value2['Salida'].'</td>
                             <td>'.$value2['Valor_Unitario'].'</td>
                             <td>'.$value2['Valor_Total'].'</td>
                           </tr>';
		                $total+=$value2['Valor_Total'];
		    		 }
		    		$html.='<tr>
		    		          <td colspan="3"></td>
                              <td><b>TOTAL</b></td>
                              <td><b>'.$total.'</b></td>
                           </tr>';
		    	
		    }else
		    {
		    	    $total = 0;

		    		$lineas = $this->modelo->trans_kardex($value['Numero']);
		    		 foreach ($lineas as $key => $value2) {
		    		 	$pro = $this->modelo->producto($value2['Codigo_Inv']);
		    		 $html.='<tr>
                             <td>'.$value2['Codigo_Inv'].'</td>
                             <td>'.$pro[0]['Producto'].'</td>
                             <td>'.$value2['Salida'].'</td>
                             <td>'.$value2['Valor_Unitario'].'</td>
                             <td>'.$value2['Valor_Total'].'</td>
                           </tr>';
		                $total+=$value2['Valor_Total'];
		    		 }
		    		$html.='<tr>
		    		          <td colspan="3"></td>
                              <td><b>TOTAL</b></td>
                              <td><b>'.$total.'</b></td>
                           </tr>';
		    	
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
		
	//  $datos = $this->modelo->cargar_comprobantes_datos($parametros['txt_query'],$parametros['txt_desde'],$parametros['txt_hasta'],$parametros['txt_tipo_filtro']);

	//  $registros = array();
	//  $reg_lineas = array();
	//  foreach ($datos as $key => $value){
	//  	    // print_r($value);die();
	// 		$registros[] = array('Nombre'=>$value['Cliente'],'fecha'=>$value['Fecha']->format('Y-m-d'),'Concepto'=>$value['Concepto'],'comprobante'=>$value['Numero'],'registros'=>array());
	// 	             $lineas = $this->modelo->trans_kardex($value['Numero']);
		    		
	// 	    		 foreach ($lineas as $key2 => $value2) {
	// 	    		 	$reg_lineas[] = array('codigo'=>$value2['Codigo_Inv'],'cantidad'=>$value2['Salida'],'producto'=>$value2['Producto'],'pre_uni'=>$value2['Valor_Unitario'],'total'=>$value2['Valor_Total']);
	// 	    		 	// $total+=$value2['Valor_Total'];
	// 	    		 }
	// 	    		  array_push($registros[$key]['registros'],$reg_lineas);
	// 	    	      $reg_lineas=array();
	// 	}

	// 	// $datos = $this->modelo->cargar_comprobantes_datos($parametros['txt_query'],$parametros['txt_desde'],$parametros['txt_hasta'],$parametros['txt_tipo_filtro']);


	// 	//  			$titulo = 'R E P O R T E   D E   I N G R E S O S';
	//  //    			 $tablaHTML[0]['medidas']=array(13,50,18,18);
	// 	//              $tablaHTML[0]['datos']=array('Fecha','Cliente','Comprobante','Factura');
	// 	//              $tablaHTML[0]['tipo'] ='C';
	// 	//              $pos = 1;
	//  //    		foreach ($datos['datos'] as $key => $value) {
	//  //    			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
	// 	//              $tablaHTML[$pos]['datos']=array($value['Fecha']->format('Y-m-d'),$value['Cliente'],$value['Comprobante'],$value['Factura']);
	// 	//              $tablaHTML[$pos]['tipo'] ='N';
	// 	//              $pos+=1;
	//  //    		}
	//  //    		 excel_generico($titulo,$tablaHTML);

	// 	// print_r($registros);die();


	//  $this->modelo->imprimir_excel($registros);
	// }

	function imprimir_excel($parametros)
	{
	 	 $Fechaini = $parametros['txt_desde'] ;//str_replace('-','',$parametros['Fechaini']);
     $Fechafin = $parametros['txt_hasta']; //str_replace('-','',$parametros['Fechafin']);
		

		$datos = $this->modelo->cargar_comprobantes_datos($parametros['txt_query'],$parametros['txt_desde'],$parametros['txt_hasta'],$parametros['txt_tipo_filtro']);


		 			$titulo = 'D E S C A R G O S  R E A L I Z A D O S';

		 			$pos = 0;
		 			$gran_total = 0;
		 			foreach ($datos as $key => $value) {
		 			
	    			 		 $tablaHTML[$pos]['medidas']=array(13,50,18,18,18);
		             $tablaHTML[$pos]['datos']=array('Nombre: '.$value['Cliente'],'Fecha: '.$value['Fecha']->format('Y-m-d'),'Concepto: '.$value['Concepto'],'','Comprobante: '.$value['Numero']);
		             $tablaHTML[$pos]['tipo'] ='C';
		             $tablaHTML[$pos]['unir'] = array('CD');
		             $pos+=1;
		             $lineas = $this->modelo->trans_kardex($value['Numero']);
		             $tablaHTML[$pos]['medidas']=array(43,18,50,50,20);
		             $tablaHTML[$pos]['datos']=array('Codigo','Cantidad','Producto','Pre uni','Total');
		             $tablaHTML[$pos]['tipo'] ='C';
		             $pos+=1;

		           $total = 0;
			    		foreach ($lineas as $key => $value2) {

			    			 $tablaHTML[$pos]['medidas']=$tablaHTML[$pos-1]['medidas'];
		             $tablaHTML[$pos]['datos']=array($value2['Codigo_Inv'],$value2['Salida'],$value2['Producto'],$value2['Valor_Unitario'],$value2['Valor_Total']);
		             $tablaHTML[$pos]['tipo'] ='N';
		             $total+=$value2['Valor_Total'];
		             $pos+=1;
			    		}
			    		 $tablaHTML[$pos]['medidas']=array(13,18,50,50,20);
	             $tablaHTML[$pos]['datos']=array('','','','Total',$total);
	             $tablaHTML[$pos]['tipo'] ='N';
		           $pos+=1;
		           $gran_total+=$total;
	    	}
	    	 $tablaHTML[$pos]['medidas']=array(13,18,50,50,20);
         $tablaHTML[$pos]['datos']=array('','','','',$gran_total);
         $tablaHTML[$pos]['tipo'] ='SUB';
         $pos+=1;
	    	
	    	excel_generico($titulo,$tablaHTML);

		// print_r($registros);die();


	 // $this->modelo->imprimir_excel($registros);
	}


	function  ver_comprobante($comprobante)
	{
		$datos = $this->modelo->cargar_comprobantes_datos($query=false,$desde='',$hasta='',$tipo='',$comprobante);
		$lineas = $this->modelo->lineas_trans_kardex($comprobante);
		// print_r($datos);print_r($lineas); die();
		$titulo = 'CARGO DE INSUMOS Y MEDICAMENTOS PARA PACIENTE';
		$mostrar = '1';
		$sizetable = 8;
		$tablaHTML = array();

		$tablaHTML[0]['medidas']= array(17,52,17,70,17,17);
    $tablaHTML[0]['alineado']=array('L','L','L','L','L');
    $tablaHTML[0]['datos']=array('<b>Paciente:',$datos[0]['Cliente'],'<b>Detalle:',$datos[0]['Concepto'],'<b>No.Comp:',$datos[0]['Numero']);
    $tablaHTML[0]['borde'] ='1';

    $pos=2;
    $total =0;
    $familias = $this->modelo->familias($comprobante);
    $reg = count($familias);
    $cab = 1;

    foreach ($familias as $key1 => $value1) {
    	$total_fam = 0;
    	$tablaHTML[$cab]['medidas']=array(39,83,18,25,25);
    	$tablaHTML[$cab]['alineado']=array('L','L','R','R','R');
    	$tablaHTML[$cab]['datos']=array('<b>CODIGO','<b>PRODUCTO','<b>CANTIDAD','<b>PRECIO UNI','<b>PRECIO TOTAL');
    	$tablaHTML[$cab]['borde'] =1;
	    foreach ($lineas as $key => $value) {
	    		$devo = $this->modelo->trans_kardex_linea_devolucion($value['Codigo_Inv'],$comprobante);
							 if(count($devo)>0)
							 {
							 	$ca= $value['Salida']-$devo[0]['Entrada'];
							 	if($ca>=0 )
							 	{
							 		$value['Salida']  = $ca;
							 		$tot1 = $ca*$value['Valor_Unitario'];
							 		$value['Valor_Total'] =$tot1;
							 	}
							 }

	    	if($value1['familia']==substr($value['Codigo_Inv'],0,5))
	    	{

	    		// print_r($value1['familia']);print_r(substr($value['Codigo_Inv'],0,5));die();
	    	  $uti = $value['Utilidad'];
	    	  if($value['Utilidad']=='' || $value['Utilidad']==0)
	    	  {
	    	  	$uti = number_format($value['utilidad_C']*100,2);
					  $parametros = array('utilidad'=>$uti,'linea'=>$value['ID']);
					  $this->guardar_utilidad($parametros);
					  $uti = number_format($value['utilidad_C']);
	    	  }
	    	  $gra_t = ($value['Valor_Total']*$uti)+$value['Valor_Total'];
	    	  $uni = 0;
	    	  if($value['Salida']!=0){
	    	  $uni = ($gra_t/$value['Salida']);
	    	  }
	    	 	$tablaHTML[$pos]['medidas']=$tablaHTML[1]['medidas'];
			    $tablaHTML[$pos]['alineado']= $tablaHTML[1]['alineado'];
			    $tablaHTML[$pos]['datos']=array($value['Codigo_Inv'],$value['Producto'],$value['Salida'],number_format($uni,2),number_format($gra_t,2));
			    $tablaHTML[$pos]['borde'] =1;

			    $pos+=2;
			    $total_fam+=number_format($gra_t,2);
			    $total+=number_format($gra_t,2);
			  }
	    }
	     $pos+=1;
	     $tablaHTML[$pos]['medidas']=array(140,25,25);
       $tablaHTML[$pos]['alineado']=array('L','L','R');
       $tablaHTML[$pos]['datos']=array('','<b>Total','<b>'.$total_fam);
       $tablaHTML[$pos]['borde'] =1;
       $pos+=1;
       if(($key1+1)!=$reg)
       {
       	 $tablaHTML[$pos]['medidas']=array(190);
	       $tablaHTML[$pos]['alineado']=array('L');
	       $tablaHTML[$pos]['datos']=array('');

	       $cab = $pos+1;
	       $pos+=1;
	       $pos+=1;
       }

    }
     $tablaHTML[$pos]['medidas']=array(190);
	   $tablaHTML[$pos]['alineado']=array('L');
	   $tablaHTML[$pos]['datos']=array('');
     $pos+=1;
     $tablaHTML[$pos]['medidas']=array(140,25,25);
     $tablaHTML[$pos]['alineado']=array('L','L','R');
     $tablaHTML[$pos]['datos']=array('','<b>Gran Total','<b>'.$total);
     $tablaHTML[$pos]['borde'] =1;


    


    // $tablaHTML[$pos]['medidas']=array(140,25,25);
    // $tablaHTML[$pos]['alineado']=array('L','L','R');
    // $tablaHTML[$pos]['datos']=array('','<b>Total','<b>'.$total);
    // $tablaHTML[$pos]['borde'] =1;

  	$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,false,false,$sizetable,$mostrar,25,'P');
	}


	function  ver_comprobante_clinica($comprobante)
	{
		$datos = $this->modelo->cargar_comprobantes_datos($query=false,$desde='',$hasta='',$tipo='',$comprobante);
		$lineas = $this->modelo->lineas_trans_kardex($comprobante);
		// print_r($datos);print_r($lineas); die();
		$titulo = 'CARGO DE INSUMOS Y MEDICAMENTOS PARA PACIENTE';
		$mostrar = '1';
		$sizetable = 8;
		$tablaHTML = array();

		$tablaHTML[0]['medidas']= array(17,52,17,70,17,17);
    $tablaHTML[0]['alineado']=array('L','L','L','L','L');
    $tablaHTML[0]['datos']=array('<b>Paciente:',$datos[0]['Cliente'],'<b>Detalle:',$datos[0]['Concepto'],'<b>No.Comp:',$datos[0]['Numero']);
    $tablaHTML[0]['borde'] ='1';

    $pos=2;
    $total =0;
    $familias = $this->modelo->familias($comprobante);
    $reg = count($familias);
    $cab = 1;

    foreach ($familias as $key1 => $value1) {
    	$total_fam = 0;
    	$tablaHTML[$cab]['medidas']=array(27,75,18,25,20,25);
    	$tablaHTML[$cab]['alineado']=array('L','L','R','R','R','R');
    	$tablaHTML[$cab]['datos']=array('<b>CODIGO','<b>PRODUCTO','<b>CANTIDAD','<b>PRECIO UNI','<b>UTILIDAD','<b>PRECIO TOTAL');
    	$tablaHTML[$cab]['borde'] =1;
	    foreach ($lineas as $key => $value) {
	    	$devo = $this->modelo->trans_kardex_linea_devolucion($value['Codigo_Inv'],$comprobante);
							 if(count($devo)>0)
							 {
							 	$ca= $value['Salida']-$devo[0]['Entrada'];
							 	if($ca>=0 )
							 	{
							 		$value['Salida']  = $ca;
							 		$tot1 = $ca*$value['Valor_Unitario'];
							 		$value['Valor_Total'] =$tot1;
							 	}
							 }

	    	if($value1['familia']==substr($value['Codigo_Inv'],0,5))
	    	{

	    		// print_r($value1['familia']);print_r(substr($value['Codigo_Inv'],0,5));die();
	    	  $uti = $value['Utilidad'];
	    	  if($value['Utilidad']=='' || $value['Utilidad']==0)
	    	  {
	    	  	$uti = number_format($value['utilidad_C']*100,2);
					  $parametros = array('utilidad'=>$uti,'linea'=>$value['ID']);
					  $this->guardar_utilidad($parametros);
					  $uti = number_format($value['utilidad_C']);
	    	  }
	    	  $gra_t = ($value['Valor_Total']*$uti)+$value['Valor_Total'];
	    	  $uni = 0;
	    	  if($value['Salida']!=0){
	    	  $uni = ($gra_t/$value['Salida']);
	    	  }
	    	 	$tablaHTML[$pos]['medidas']=$tablaHTML[1]['medidas'];
			    $tablaHTML[$pos]['alineado']= $tablaHTML[1]['alineado'];
			    $tablaHTML[$pos]['datos']=array($value['Codigo_Inv'],$value['Producto'],$value['Salida'],number_format($uni,2),$value['utilidad_C'],number_format($gra_t,2));
			    $tablaHTML[$pos]['borde'] =1;

			    $pos+=2;
			    $total_fam+=number_format($gra_t,2);
			    $total+=number_format($gra_t,2);
			  }
	    }
	     $pos+=1;
	     $tablaHTML[$pos]['medidas']=array(145,20,25);
       $tablaHTML[$pos]['alineado']=array('L','L','R');
       $tablaHTML[$pos]['datos']=array('','<b>Total','<b>'.$total_fam);
       $tablaHTML[$pos]['borde'] =1;
       $pos+=1;
       if(($key1+1)!=$reg)
       {
       	 $tablaHTML[$pos]['medidas']=array(190);
	       $tablaHTML[$pos]['alineado']=array('L');
	       $tablaHTML[$pos]['datos']=array('');

	       $cab = $pos+1;
	       $pos+=1;
	       $pos+=1;
       }

    }
     $tablaHTML[$pos]['medidas']=array(190);
	   $tablaHTML[$pos]['alineado']=array('L');
	   $tablaHTML[$pos]['datos']=array('');
     $pos+=1;
     $tablaHTML[$pos]['medidas']=array(145,20,25);
     $tablaHTML[$pos]['alineado']=array('L','L','R');
     $tablaHTML[$pos]['datos']=array('','<b>Gran Total','<b>'.$total);
     $tablaHTML[$pos]['borde'] =1;


    


    // $tablaHTML[$pos]['medidas']=array(140,25,25);
    // $tablaHTML[$pos]['alineado']=array('L','L','R');
    // $tablaHTML[$pos]['datos']=array('','<b>Total','<b>'.$total);
    // $tablaHTML[$pos]['borde'] =1;

  	$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,false,false,$sizetable,$mostrar,25,'P');
	}


	function datos_comprobante($comprobante)
	{
		$datos = $this->modelo->cargar_comprobantes_datos($query=false,$desde='',$hasta='',$tipo='',$comprobante);
		$lineas = $this->modelo->lineas_trans_kardex($comprobante);
		$tr='';
		foreach ($lineas as $key => $value) {
			$tr.='<tr>
			  			<td>'.$value['Codigo_Inv'].'</td>
			  			<td>'.$value['Producto'].'</td>
			  			<td>'.$value['Salida'].'</td>
			  			<td>'.$value['Valor_Unitario'].'</td>
			  			<td>'.$value['Valor_Total'].'</td>
        			<td><input class="form-control input-sm" onblur="calcular()"></td>
        			<td><input class="form-control input-sm" onblur="calcular()"></td>
        			<td><input class="form-control input-sm" readonly></td>
        		</tr>';
		}

		return array('cliente'=>$datos,'tabla'=>$tr);

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


}