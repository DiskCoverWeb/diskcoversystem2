<?php 
include (dirname(__DIR__,2).'/modelo/farmacia/pacienteM.php');
include (dirname(__DIR__,2).'/modelo/farmacia/reportes_descargos_procesadosM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */
$controlador = new facturacion_insumosC();
if(isset($_GET['datos_comprobante']))
{   
	$parametros= $_POST['comprobante'];
	echo json_encode($controlador->datos_comprobante($parametros));	
}
if(isset($_GET['guardar_utilidad']))
{   
	$parametros= $_POST['parametros'];
	echo json_encode($controlador->guardar_utilidad($parametros));	
}

if(isset($_GET['reporte_excel']))
{   
	$comprobante= $_GET['comprobante'];
	// print_r($comprobante);die();
	echo json_encode($controlador->reporte_excel($comprobante));	
}

if(isset($_GET['reporte_excel_clinica']))
{   
	$comprobante= $_GET['comprobante'];
	// print_r($comprobante);die();
	echo json_encode($controlador->reporte_excel_clinica($comprobante));	
}

if(isset($_GET['servicios']))
{   
	// $comprobante= $_GET['comprobante'];
	// print_r($comprobante);die();
	echo json_encode($controlador->servicios());	
}

if(isset($_GET['facturar']))
{   
	$comprobante= $_POST['parametros'];
	echo json_encode($controlador->facturar($comprobante));	
}
if(isset($_GET['numFactura']))
{   
	// $comprobante= $_POST['parametros'];
	echo json_encode($controlador->num_facturar());	
}



class facturacion_insumosC 
{
	private $descargos_procesados;
	private $paciente;
	private $pdf;
	function __construct()
	{
		$this->descargos_procesados = new reportes_descargos_procesadosM();
		$this->paciente = new pacienteM();
		$this->pdf = new cabecera_pdf();
	}

	function datos_comprobante($comprobante)
	{
		$datos = $this->descargos_procesados->cargar_comprobantes_datos($query=false,$desde='',$hasta='',$tipo='',$comprobante);
		$lineas = $this->descargos_procesados->lineas_trans_kardex($comprobante);
		$tr='';
		$tot=0;
		$num_lineas = count($lineas);
		foreach ($lineas as $key => $value) {
			 $devo = $this->descargos_procesados->trans_kardex_linea_devolucion($value['Codigo_Inv'],$comprobante);
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
				 
			$key+=1;
			$uti = number_format($value['Utilidad']*100,2);
			if($value['Utilidad']=='' || $value['Utilidad']==0)
			{
				$uti = number_format($value['utilidad_C']*100,2);
				$parametros = array('utilidad'=>$uti,'linea'=>$value['ID']);
				$this->guardar_utilidad($parametros);
			}
			$valor =number_format( ($uti/100)*$value['Valor_Total'],2);
			$gran =number_format( $value['Valor_Total']+$valor,2);
			$tr.='<tr>
			  			<td width="'.dimenciones_tabl(strlen('Codigo_Inv')).'">'.$value['Codigo_Inv'].'</td>
			  			<td width="'.dimenciones_tabl(strlen($value['Producto'])).'">'.$value['Producto'].'</td>
			  			<td width="'.dimenciones_tabl(strlen($value['Salida'])).'">'.$value['Salida'].'</td>
			  			<td width="'.dimenciones_tabl(strlen('Valor_Unitario')).'" class="text-right">'.number_format($value['Valor_Unitario'],2).'</td>
			  			<td width="'. dimenciones_tabl(strlen('Precio Total')).'"><input class="form-control input-sm text-right" id="txt_to_'.$key.'" value="'.number_format($value['Valor_Total'],2).'"  readonly></td>
        			<td  width="'.dimenciones_tabl(strlen('% Utilidad')).'"><input class="form-control input-sm text-right" id="txt_porcentaje_'.$key.'"  value="'.$uti.'" onblur="calcular(\''.$key.'\')"></td>
        			<td width="'.dimenciones_tabl(strlen('Valor utilidad')).'"><input class="form-control input-sm text-right" id="txt_valor_'.$key.'" value="'.$valor.'"   readonly></td>
        			<td width="'.dimenciones_tabl(strlen('Gran total')).'"><input class="form-control input-sm text-right" id="txt_gran_t_'.$key.'" value="'.$gran.'" onblur="calcular_uti(\''.$key.'\')"></td>
        			<td><button onclick="guardar_uti(\''.$value['ID'].'\',\''.$key.'\')" id="btn_linea_'.$key.'" class="btn btn-primary"><i class="fa-icon fa fa-save"></i></button></td>
        		</tr>';
        	$tot+=$gran;
		}
		$tr.='<tr><td colspan="6"></td><td class="text-right"><b>TOTAL</b></td><td class="text-right" id="txt_tt">'.$tot.'</td></tr>';

		return array('cliente'=>$datos,'tabla'=>$tr,'lineas'=>$num_lineas,'total'=>number_format($tot,2));

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

	function reporte_excel($comprobante)
	{		          				

	    $datos = $this->descargos_procesados->cargar_comprobantes_datos($query=false,$desde='',$hasta='',$tipo='',$comprobante);
		$lineas = $this->descargos_procesados->lineas_trans_kardex($comprobante);
		$titulo = 'CARGO DE INSUMOS Y MEDICAMENTOS DE PACIENTE PRIVADO';
		
		$tablaHTML = array();

		$tablaHTML[0]['medidas']= array(17,44,17,70,17);
	    $tablaHTML[0]['datos']=array('Paciente:',$datos[0]['Cliente'],'Detalle:',$datos[0]['Concepto'],'No.Comp:'.$datos[0]['Numero']);
	    $tablaHTML[0]['tipo'] ='SUB';

	    $pos=2;
	    $total =0;
	    $familias = $this->descargos_procesados->familias($comprobante);
	    $reg = count($familias);
	    $cab = 1;
	    $total_fam = 0;

	    foreach ($familias as $key1 => $value1) {
	    	$tablaHTML[$cab]['medidas']=array(17,44,18,25,25);
	    	$tablaHTML[$cab]['datos']=array('CODIGO','PRODUCTO','CANTIDAD','PRECIO UNI','PRECIO TOTAL');
	    	$tablaHTML[$cab]['tipo'] ='SUB';
	    	$total_fam = 0;
		    foreach ($lineas as $key => $value) {
		    	 $devo = $this->descargos_procesados->trans_kardex_linea_devolucion($value['Codigo_Inv'],$comprobante);
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
		    	  $uni = ($gra_t/$value['Salida']);
		    	 	$tablaHTML[$pos]['medidas']=$tablaHTML[1]['medidas'];
				    $tablaHTML[$pos]['datos']=array($value['Codigo_Inv'],$value['Producto'],$value['Salida'],number_format($uni,2),number_format($gra_t,2));
				    $tablaHTML[$pos]['tipo'] ='N';

				    $pos+=2;
				    $total+=number_format($gra_t,2);
				    $total_fam+=number_format($gra_t,2);
				  }
		    }
		     $pos+=1;
		   $tablaHTML[$pos]['medidas']=array(17,44,17,70,17);
	       $tablaHTML[$pos]['datos']=array('','','','Total',''.$total_fam);
	       $tablaHTML[$pos]['tipo'] ='BR';
	       $pos+=1;
	       $cab =$pos+1;
	       $pos+=1;
	       if(($key1+1)!=$reg)
	       {
	       	 $tablaHTML[$pos]['medidas']=array(190);
		       $tablaHTML[$pos]['datos']=array('');
		        $pos+=1;
		        $pos+=1;
	       }

	    }

	     $pos+=1;
		   $tablaHTML[$pos]['medidas']=array(17,44,17,70,17);
	       $tablaHTML[$pos]['datos']=array('','','','Gran Total',''.$total);
	       $tablaHTML[$pos]['tipo'] ='BR';

		excel_generico($titulo,$tablaHTML);
	}



	function reporte_excel_clinica($comprobante)
	{		          				

	    $datos = $this->descargos_procesados->cargar_comprobantes_datos($query=false,$desde='',$hasta='',$tipo='',$comprobante);
		$lineas = $this->descargos_procesados->lineas_trans_kardex($comprobante);
		$titulo = 'CARGO DE INSUMOS Y MEDICAMENTOS DE PACIENTE PRIVADO';
		
		$tablaHTML = array();

		$tablaHTML[0]['medidas']= array(17,44,17,70,17,25);
	    $tablaHTML[0]['datos']=array('Paciente:',$datos[0]['Cliente'],'Detalle:',$datos[0]['Concepto'],'No.Comp:'.$datos[0]['Numero'],'');
	    $tablaHTML[0]['tipo'] ='SUB';

	    $pos=2;
	    $total =0;
	    $familias = $this->descargos_procesados->familias($comprobante);
	    $reg = count($familias);
	    $cab = 1;
	    $total_fam = 0;

	    foreach ($familias as $key1 => $value1) {
	    	$tablaHTML[$cab]['medidas']=array(17,44,18,25,25,25);
	    	$tablaHTML[$cab]['datos']=array('CODIGO','PRODUCTO','CANTIDAD','PRECIO UNI','UTILIDAD','PRECIO TOTAL');
	    	$tablaHTML[$cab]['tipo'] ='SUB';
	    	$total_fam = 0;
		    foreach ($lineas as $key => $value) {
		    	 $devo = $this->descargos_procesados->trans_kardex_linea_devolucion($value['Codigo_Inv'],$comprobante);
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
		    	  $uni = ($gra_t/$value['Salida']);
		    	 	$tablaHTML[$pos]['medidas']=$tablaHTML[1]['medidas'];
				    $tablaHTML[$pos]['datos']=array($value['Codigo_Inv'],$value['Producto'],$value['Salida'],number_format($uni,2),$value['utilidad_C'],number_format($gra_t,2));
				    $tablaHTML[$pos]['tipo'] ='N';

				    $pos+=2;
				    $total+=number_format($gra_t,2);
				    $total_fam+=number_format($gra_t,2);
				  }
		    }
		     $pos+=1;
		   $tablaHTML[$pos]['medidas']=array(17,44,17,70,25,17);
	       $tablaHTML[$pos]['datos']=array('','','','','Total',''.$total_fam);
	       $tablaHTML[$pos]['tipo'] ='BR';
	       $pos+=1;
	       $cab =$pos+1;
	       $pos+=1;
	       if(($key1+1)!=$reg)
	       {
	       	 $tablaHTML[$pos]['medidas']=array(190);
		       $tablaHTML[$pos]['datos']=array('');
		        $pos+=1;
		        $pos+=1;
	       }

	    }

	     $pos+=1;
		   $tablaHTML[$pos]['medidas']=array(17,44,17,70,25,17);
	       $tablaHTML[$pos]['datos']=array('','','','','Gran Total',''.$total);
	       $tablaHTML[$pos]['tipo'] ='BR';

		excel_generico($titulo,$tablaHTML);
	}


	function servicios()
	{
		$datos = $this->descargos_procesados->servicios();
		$lista = array();
		foreach ($datos as $key => $value) {
			$lista[] =array('id'=>$value['Codigo_Inv'],'text'=>$value['Producto']);
		}
		// print_r($datos);die();
		return $lista;
	}


	function facturar($parametros)
	{
		$compro = $this->descargos_procesados->cargar_comprobantes_datos($query=false,$desde='',$hasta='',$tipo='',$parametros['comprobante']);
		$codigo = array('query'=>$compro[0]['Cliente'],'tipo'=>'N1','codigo'=>'');
		$cliente = $this->paciente->cargar_paciente($codigo,$pag=false);

		// print_r($cliente);
		// print_r($parametro);die();


        SetAdoAddNew("Asiento_F");  		
		SetAdoFields('CODIGO',$parametros['servicio_cod']);
		SetAdoFields('CANT','1');
		SetAdoFields('PRODUCTO',$parametros['servicio']);
		SetAdoFields('PRECIO',$parametros['total']);
		SetAdoFields('TOTAL',$parametros['total']);
		SetAdoFields('Codigo_Cliente',$cliente[0]['Codigo']);
		SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU']);
		SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);
		SetAdoFields('Item',$_SESSION['INGRESO']['item']);
	  	SetAdoFields('CODIGO_L',$parametros['servicio_cod']);
      
        SetAdoFields('Cta','Cuenta');
        SetAdoFields('HABIT',G_PENDIENTE);
        SetAdoFields('Mes','.');
        SetAdoFields('TICKET',date('Y'));
        SetAdoFields('A_No',1);

        // factura_numero($ser);  
		$respuesta = SetAdoUpdate();
		if ($respuesta==null) {
			// [DCLinea] => FA 001001 1127782029001 1.1.01.05   


			$FA['Cliente'] = $cliente[0]['Cliente'];
		    $FA['TextCI'] =  $cliente[0]['CI_RUC'] ;
		    $FA['TxtEmail'] = $cliente[0]['Email'] ;
		    $FA['Serie'] ='001020' ; 
		    $FA['FacturaNo'] ='99999' ;
		    $FA['me']='P';
		    $FA['Total']= $parametros['total'];;
		    $FA['Total_Abonos']= $parametros['total'];; 
		    $FA['TC']='FA';
		    $FA['codigoCliente'] = $cliente[0]['Codigo'];
		    $FA['Autorizacion'] = '123456789';



			Grabar_Factura($FA);

			return 1;
		}

	}

	function num_facturar()
	{
		print_r($_SESSION['INGRESO']);die();
	}


}