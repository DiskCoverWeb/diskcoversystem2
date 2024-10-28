<?php
$tipo=2;require_once(dirname(__DIR__,2)."/db/chequear_seguridad.php");
require_once(dirname(__DIR__,2)."/modelo/facturacion/lista_liquidacionCompraM.php");
require_once(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
require_once(dirname(__DIR__,3).'/lib/phpmailer/enviar_emails.php');
// require(dirname(__DIR__,2)."/comprobantes/SRI/autorizar_sri.php");

$controlador = new lista_liquidacionCompraC();
if(isset($_GET['tabla']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->tabla_facturas($parametros));
}
if(isset($_GET['tablaAu']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->tabla_facturas($parametros));
}
if(isset($_GET['perido']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->factura_periodo($parametros));
}
if(isset($_GET['ver_fac']))
{
  $controlador->ver_fac_pdf($_GET['codigo'],$_GET['ser'],$_GET['ci'],$_GET['per']);
}
if(isset($_GET['imprimir_pdf']))
{
	$parametros= $_GET;
     $controlador->imprimir_pdf($parametros);
}
if(isset($_GET['imprimir_excel']))
{   
	$parametros= $_GET;
	$controlador->imprimir_excel($parametros);	
}
if(isset($_GET['grupos']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->grupos($query));
}
if(isset($_GET['clientes']))
{
	$query = '';
	$grupo = $_GET['g'];
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->clientes_x_grupo($query,$grupo));
}
if(isset($_GET['clientes2']))
{
	$query = '';
	$grupo = $_GET['g'];
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->clientes2_x_grupo($query,$grupo));
}
if(isset($_GET['clientes_datos']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->clientes_datos($parametros));
}

if(isset($_GET['validar']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->validar_cliente($parametros));
}

if(isset($_GET['enviar_mail']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->enviar_mail($parametros));
}
if(isset($_GET['re_autorizar']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->autorizar($parametros));
}
if(isset($_GET['autorizar_bloque']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->autorizar_bloque($parametros));
}
if(isset($_GET['Anular']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->anular($parametros));
}
if(isset($_GET['enviar_email_detalle']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->enviar_email_detalle($parametros));
}

if(isset($_GET['descargar_factura']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->descargar_factura($parametros));
}

if(isset($_GET['descargar_xml']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->descargar_xml($parametros));
}

class lista_liquidacionCompraC
{
	private $modelo;
  private $email;
  private $pdf;
 	private $empresaGeneral;
 	private $sri;
	
	public function __construct(){
    $this->modelo = new lista_liquidacionCompraM();
		$this->pdf = new cabecera_pdf();
		$this->email = new enviar_emails();
		$this->empresaGeneral = $this->modelo->Empresa_data();
		$this->sri = new autorizacion_sri();
        //$this->modelo = new MesaModel();
    }


    function tabla_facturas($parametros)
    {

    	$autorizados = false;
    	if(isset($parametros['auto'])){ $autorizados = $parametros['auto'];}
    	// print_r($parametros);die();
    	$codigo = $parametros['ci'];
    	$tbl = $this->modelo->facturas_emitidas_tabla($codigo,$parametros['per'],$parametros['desde'],$parametros['hasta'],false,$autorizados);
    	$tr='';
    	foreach ($tbl as $key => $value) {
    		 $exis = $this->modelo->catalogo_lineas($value['TC'],$value['Serie']);
    		 $autorizar = '';$anular = '';
    		 $cli_data = $this->modelo->Cliente($value['CodigoC']);
    		 $email = '';
    		 if(count($cli_data)>0)
    		 {
    		 	 if($cli_data[0]['Email']!='.' && $cli_data[0]['Email']!='')
    		 	 {
    		 	 	 $email.=$cli_data[0]['Email'].',';
    		 	 }
    		 	 if($cli_data[0]['EmailR']!='.' && $cli_data[0]['EmailR']!='')
    		 	 {
    		 	 	 $email.=$cli_data[0]['EmailR'].',';
    		 	 }
    		 	 if($cli_data[0]['Email2']!='.' && $cli_data[0]['Email2']!='')
    		 	 {
    		 	 	 $email.=$cli_data[0]['Email2'].',';
    		 	 }
    		 }
    		 // if(count($exis)>0 && strlen($value['Autorizacion'])==13 && $parametros['tipo']!='')
    		 // {
    		 // 	$autorizar = '<button type="button" class="btn btn-xs btn-primary" title="Autorizar"><i class="fa fa-paper-plane"></i></button>';
    		 // }
    		 // if($value['T']!='A' && $parametros['tipo']!='')
    		 // {
    		 // 	$anular = '<button type="button" class="btn btn-xs btn-danger"  title="Anular factura"><i class="fa fa-times-circle"></i></button>';
    		 // }
    		$tr.='<tr>
            <td>
            <div class="input-group-btn">
								<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones
								<span class="fa fa-caret-down"></span></button>
								<ul class="dropdown-menu">
								<li><a href="#" onclick="Ver_factura(\''.$value['Factura'].'\',\''.$value['Serie'].'\',\''.$value['CodigoC'].'\')"><i class="fa fa-eye"></i> Ver Liquidacion de compra</a></li>';
								if(count($exis)>0 && strlen($value['Autorizacion'])==13 && $parametros['tipo']!='')
    		 				{
									$tr.='<li><a href="#" onclick="autorizar(\''.$value['TC'].'\',\''.$value['Factura'].'\',\''.$value['Serie'].'\',\''.$value['Fecha']->format('Y-m-d').'\')" ><i class="fa fa-paper-plane"></i>Autorizar</a></li>';
								}
								if($value['T']!='A' && $parametros['tipo']!='')
    		 				{
									$tr.='<li><a href="#" onclick="anular_factura(\''.$value['Factura'].'\',\''.$value['Serie'].'\',\''.$value['CodigoC'].'\')"><i class="fa fa-times-circle"></i>Anular Liquidacion</a></li>';
								}
								$tr.='<li><a href="#" onclick=" modal_email_fac(\''.$value['Factura'].'\',\''.$value['Serie'].'\',\''.$value['CodigoC'].'\',\''.$email.'\')"><i class="fa fa-envelope"></i> Enviar liquidacion de compra por email</a></li>
								<li><a href="#" onclick="descargar_fac(\''.$value['Factura'].'\',\''.$value['Serie'].'\',\''.$value['CodigoC'].'\')"><i class="fa fa-download"></i> Descargar Liquidacion de compra</a></li>';
								if(strlen($value['Autorizacion'])>13)
								{
								 $tr.='<li><a href="#" onclick="descargar_xml(\''.$value['Autorizacion'].'\')"><i class="fa fa-download"></i> Descargar XML</a></li>';
								}
								 $tr.='
								</ul>
						</div>


            </td>
            <td>'.$value['T'].'</td>
            <td>'.$value['Razon_Social'].'</td>
            <td>'.$value['TC'].'</td>
            <td>'.$value['Serie'].'</td>
            <td>'.$value['Autorizacion'].'</td>
            <td>'.$value['Factura'].'</td>
            <td>'.$value['Fecha']->format('Y-m-d').'</td>
            <td class="text-right">'.$value['SubTotal'].'</td>
            <td class="text-right">'.$value['Con_IVA'].'</td>
            <td class="text-right">'.$value['IVA'].'</td>
            <td class="text-right">'.$value['Descuentos'].'</td>
            <td class="text-right">'.$value['Total'].'</td>
            <td class="text-right">'.$value['Saldo'].'</td>
            <td>'.$value['RUC_CI'].'</td>
            <td>'.$value['TB'].'</td>
          </tr>';
    	}

    	// print_r($tr);die();

    	return $tr;
    }
    function factura_periodo($parametros)
    {
    	$datos = $this->modelo->facturas_perido($parametros['codigo']);
    	$opcion = '';
    	foreach ($datos as $key => $value) {  
    		$year = '.';
    		if($value['Periodo']!='.')
    		{
    	   $year = explode('/',$value['Periodo']);
    	   $year = $year[2];
    	  }
    		$opcion.='<option value="'.$year.'">'.$year.'</option>';
    	}
    	return $opcion;
    }
    function ver_fac_pdf($cod,$ser,$ci,$per)
    {
    	// print_r($cod);die();
    	$this->modelo->pdf_factura($cod,$ser,$ci,$per);
    }
    function imprimir_pdf($parametros)
    {
    	// print_r($parametros);die();
    	$codigo = $parametros['ddl_cliente'];
    	$tbl = $this->modelo->facturas_emitidas_tabla($codigo,$parametros['ddl_periodo']);
    	// print_r($tbl);die();

  // 	    $desde = str_replace('-','',$parametros['txt_desde']);
		// $hasta = str_replace('-','',$parametros['txt_hasta']);
		// $empresa = explode('_', $parametros['ddl_entidad']);
		// $parametros['ddl_entidad'] = $empresa[0];

		// print_r($parametros);die();

		// $datos = $this->modelo->pedido_paciente_distintos(false,$parametros['rbl_buscar'],$parametros['txt_query'],$parametros['txt_desde'],$parametros['txt_hasta'],$parametros['txt_tipo_filtro']);


		$titulo = 'L I S T A  D E  F A C T U R A S';
		$sizetable =7;
		$mostrar = TRUE;
		// $Fechaini = $parametros['txt_desde'] ;//str_replace('-','',$parametros['Fechaini']);
		// $Fechafin = $parametros['txt_hasta']; //str_replace('-','',$parametros['Fechafin']);
		$tablaHTML= array();		
		$pos = 0;
		$borde = 1;
		// print_r($datos);die();
		$pos=1;
		$tablaHTML[0]['medidas']=array(7,7,10,15,50,15,20,15,15,15,15,15,20,7,50,15);
		$tablaHTML[0]['alineado']=array('L','L','L','L','L','L','L','R','R','R','R','R','L','L','L','L');
		$tablaHTML[0]['datos']=array('No','T','TC','Serie','Autorizacion','Factura','Fecha','SubTotal','Con Iva','IVA','Total','Saldo','Ruc','TB','Razon social','ID');
		$tablaHTML[0]['borde'] =$borde;
		$tablaHTML[0]['estilo'] ='b';

		$datos = $tbl;
		
		foreach ($datos as $key => $value) {			

		    $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		    $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		    $tablaHTML[$pos]['datos']=array($key+1,$value['T'],$value['TC'],$value['Serie'],$value['Autorizacion'].' ',$value['Factura'],$value['Fecha']->format('Y-m-d'),$value['SubTotal'],$value['Con_IVA'],$value['IVA'],$value['Total'],$value['Saldo'],$value['RUC_CI'],$value['TB'],$value['Razon_Social'],$value['ID']);
		    $tablaHTML[$pos]['borde'] =$borde;
			$pos+=1;
		}
	   
		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$Fechaini=false,$Fechafin=false,$sizetable,$mostrar,15,'H');
  }

  function imprimir_excel($parametros)
  {
		$empresa = explode('_', $parametros['ddl_entidad']);
		$parametros['ddl_entidad'] = $empresa[0];
  	$datos = $this->modelo->tabla_registros($parametros['ddl_entidad'],$parametros['ddl_empresa'],$parametros['ddl_usuario'],$parametros['ddl_modulos'],$parametros['txt_desde'],$parametros['txt_hasta'],$parametros['ddl_num_reg']);
  	$reg = array();
  	foreach ($datos as $key => $value) {
			 $ent = $this->modelo->entidades(false,$value['RUC']);
			 $ent = explode('_', $ent[0]['id']);
			 $empresas = $this->modelo->empresas($ent[1],false,$value['Item']);
  		$reg[] = array('Fecha'=>$value['Fecha'],'Hora'=>$value['Hora'],'Entidad'=>$value['enti'],'IP_Acceso'=>$value['IP_Acceso'],'Aplicacion'=>$value['Aplicacion'],'Tarea'=>$value['Tarea'],'Empresa'=>$empresas[0]['text'],'Usuario'=>$value['nom']); 
  	}
	 $this->modelo->imprimir_excel($reg);
  }

  function grupos($query)
  {
  	$datos = $this->modelo->grupos($query);
  	$res[] = array('id'=>'.','text'=>'TODOS');
  	foreach ($datos as $key => $value) {
  		$res[] = array('id'=>$value['Grupo'],'text'=>$value['Grupo']);
  	}
  	return $res;
  }

  function clientes_x_grupo($query,$grupo)
  {
  	if($grupo=='.'){$grupo= '';}
  	$cod ='';
  	$datos = $this->modelo->Cliente_facturas($cod,$grupo,$query);
  	$res = array();
  	foreach ($datos as $key => $value) {
  		$res[] = array('id'=>$value['Codigo'],'text'=>$value['Cliente'].'  CI:'.$value['CI_RUC'],'email'=>$value['Email'],'data'=>$value);
  	}
  	return $res;
  }

  function clientes2_x_grupo($query,$grupo)
  {
  	if($grupo=='.'){$grupo= '';}
  	$cod ='';
  	$datos = $this->modelo->Cliente_facturas($cod,$grupo,$query);
  	$res[0] = array('id'=>'T','text'=>'Todos','email'=>'','data'=>'');
  	foreach ($datos as $key => $value) {
  		$res[] = array('id'=>$value['Codigo'],'text'=>$value['Cliente'].'  CI:'.$value['CI_RUC'],'email'=>$value['Email'],'data'=>$value);
  	}
  	return $res;
  }
  function validar_cliente($parametros)
  {
  	// print_r($parametros);die();
  	if($parametros['tip']=='2')
  	{
  		$parametros['cla']=false;
  		if($parametros['cli']=='T')
  		{
  			 return 1;
  		}
  	}
  	$dato = $this->modelo->Cliente_facturas($parametros['cli'],false,false,$parametros['cla']);
  	if(empty($dato))
  	{
  		return -1;
  	}else
  	{
  		return 1;
  	}

  } 

   function clientes_datos($parametros)
  {
    $grupo='';
  	if($parametros['gru']!='.'){$grupo= $parametros['gru'];}
  	$query ='';
  	$datos = $this->modelo->Cliente($parametros['ci'],$grupo,$query);
  	return $datos;
  }
  function enviar_mail($parametros)
  {
    $empresaGeneral = array_map(array($this, 'encode1'), $this->empresaGeneral);

  	$nueva_Clave = generate_clave(8);

  	$email_conexion = $empresaGeneral[0]['Email_Conexion'];
    $email_pass =  $empresaGeneral[0]['Email_Contraseña'];
    // print_r($empresaGeneral[0]);die();
  	$correo_apooyo="info@diskcoversystem.com"; //correo que saldra ala do del emisor
  	$cuerpo_correo = 'Se a generado una clave temporar para que usted pueda ingresar:'. $nueva_Clave;
  	$titulo_correo = 'EMAIL DE RECUPERACION DE CLAVE';
  	$archivos = false;
  	$correo = $parametros['ema'];
  	
  	SetAdoAddNew("Clientes");
		SetAdoFields("Clave", $nueva_Clave);
		SetAdoFieldsWhere("Codigo", $parametros['ci']);
		SetAdoUpdateGeneric();

  	$resp = SetAdoUpdateGeneric();  	
  	
  	if($resp==1)
  	{
  		if($this->email->recuperar_clave($archivos,$correo,$cuerpo_correo,$titulo_correo,$correo_apooyo,'Email de recuperacion',$email_conexion,$email_pass)==1){
  			return 1;
  		}else
  		{
  			return -1;
  		}
  	}else
  	{
  		return -1;
  	}
  }


 function encode1($arr) {
    $new = array(); 
    foreach($arr as $key => $value) {
      if(!is_object($value))
      {
      	if($key=='Archivo_Foto')
      		{
      			if (!file_exists('../../img/img_estudiantes/'.$value)) 
      				{
      					$value='';
      					//$new[utf8_encode($key)] = utf8_encode($value);
      					$new[$key] = $value;
      				}
      		} 
         if($value == '.')
         {
         	$new[$key] = '';
         }else{
         	//$new[utf8_encode($key)] = utf8_encode($value);
         	$new[$key] = $value;
         }
      }else
        {
          //print_r($value);
          $new[$key] = $value->format('Y-m-d');          
        }
     }
     return $new;
    }

    function autorizar($parametros)
    {
    	// print_r($parametros);die();
    	// $datos[0]['campo'] = 'Autorizacion';
    	// $datos[0]['dato'] = $_SESSION['INGRESO']['RUC'];
    	
    	// $campoWhere[0]['campo'] = 'Item';
    	// $campoWhere[0]['valor'] = $_SESSION['INGRESO']['item'];
    	// $campoWhere[1]['campo'] = 'Periodo';
    	// $campoWhere[1]['valor'] = $_SESSION['INGRESO']['periodo'];
    	// $campoWhere[2]['campo'] = 'TC';
    	// $campoWhere[2]['valor'] = $parametros['tc'];
    	// $campoWhere[3]['campo'] = 'Serie';
    	// $campoWhere[3]['valor'] = $parametros['serie'];
    	// $campoWhere[4]['campo'] = 'Factura';
    	// $campoWhere[4]['valor'] = $parametros['FacturaNo'];

    	 // $this->modelo->ingresar_update($datos,'Facturas',$campoWhere);

    	// $clave = $this->sri->Clave_acceso($parametros['Fecha'],'01', $parametros['serie'],$parametros['FacturaNo']);

    	$TC = '03';
    	if(isset($parametros['tc']) && $parametros['tc']=='FA'){$TC = '01';}
    	if(isset($parametros['tc']) && $parametros['tc']=='LC' || isset($parametros['TC']) && $parametros['TC']=='LC')
    	{
    		$clave = $this->sri->Clave_acceso($parametros['Fecha'],$TC, $parametros['serie'],$parametros['FacturaNo']);
    	}
    	$rep= $this->sri->Autorizar_factura_o_liquidacion($parametros);
       $imp = '';
       if($rep==1)
       {
       		return array('respuesta'=>$rep,'pdf'=>$imp,'clave'=>$clave);
       }else{ return array('respuesta'=>-1,'pdf'=>$imp,'text'=>$rep,'clave'=>$clave);}

    	// return $res;
    }

     function autorizar_bloque($parametros)
    {
    	// print_r($parametros);die();

    	$codigo = $parametros['ci'];
    	$datos = $this->modelo->facturas_emitidas_tabla($codigo,$parametros['per'],$parametros['desde'],$parametros['hasta'],false,$parametros['auto']);


    	foreach ($datos as $key => $value) {
    		// print_r($value);die();
    		$parametros2 = array('TC'=>$value['TC'],'FacturaNo'=>$value['Factura'],'serie'=>$value['Serie'],'Fecha'=>$value['Fecha']->format('Y-m-d'));
    		$resp[$value['Factura']] = $this->autorizar($parametros2);
    	}

    	$tabla = '<table><tr>
    			<td>Liquidacion Compra</td>
    			<td>Numero de Autorizacion</td>
    			<td>Estado</td>
    			<td>Detalle</td>
    		</tr>';
    		// print_r($resp);die();
    	foreach ($resp as $key => $value) {
    		if($value['respuesta']!=1)
    		{
    			$deta = $this->sri->error_sri($value['clave']);
    			// print_r($value['clave']);

    			// print_r($deta);die();
    			if($deta['adicional']!='')
    			{
    				$sms = $deta['adicional'];
    			}else{
    				$sms = $deta['mensaje'];
    			}
	    		$tabla.='<tr>
	    			<td>'.$key.'</td>
	    			<td>'.$value['clave'].'</td>
	    			<td>Rechazado</td>
	    			<td>'.$sms.'</td>
	    		</tr>';
	    	}else
	    	{
	    	$tabla.='<tr>
	    			<td>'.$key.'</td>
	    			<td>'.$value['clave'].'</td>
	    			<td>Autorizado</td>
	    			<td></td>
	    		</tr>';
	    	}
    	}
    	$tabla.= '</table>';

    	return $tabla;
    }


    function anular($parametros)
    {
    	SetAdoAddNew("Facturas");
			SetAdoFields("T", G_ANULADO);
			SetAdoFields("Nota", "Anulación de Factura No.".$parametros['factura'].".");
			SetAdoFieldsWhere("Serie", $parametros['serie']);
			SetAdoFieldsWhere("Factura", $parametros['factura']);
			SetAdoFieldsWhere("CodigoC", $parametros['codigo']);
			SetAdoUpdateGeneric();

    	SetAdoAddNew("Detalle_Factura");
			SetAdoFields("T", G_ANULADO);
			SetAdoFieldsWhere("Serie", $parametros['serie']);
			SetAdoFieldsWhere("Factura", $parametros['factura']);
			SetAdoFieldsWhere("CodigoC", $parametros['codigo']);
			SetAdoUpdateGeneric();

    	return $this->modelo->eliminar_abonos($parametros);
    }

    function enviar_email_detalle($parametros)
    {
    	$to_correo = substr($parametros['to'],0,-1);
    	$cuerpo_correo = $parametros['cuerpo'];
    	$titulo_correo = $parametros['titulo'];

    	$this->modelo->pdf_factura_descarga($parametros['fac'],$parametros['serie'],$parametros['codigoc']);

    	$archivos[0] =$parametros['serie'].'-'.generaCeros($parametros['fac'],7).'.pdf';

    	$datos = $this->modelo->factura_detalle($parametros['fac'],$parametros['serie'],$parametros['codigoc']);
    	$rutaA = dirname(__DIR__,2).'/comprobantes/entidades/entidad_'.generaCeros($_SESSION['INGRESO']['IDEntidad'],3).'/CE'.generaCeros($_SESSION['INGRESO']['item'],3).'/Autorizados/'.$datos[0]['Autorizacion'].'.xml';
    	if(file_exists($rutaA))
    	{
    		$archivos[1] = $datos[0]['Autorizacion'].'.xml';
    	}else
    	{
    		// crea las carpetas si no existen
      $carpeta_entidad = dirname(__DIR__,2).'/comprobantes/entidades/entidad_'.generaCeros($_SESSION['INGRESO']['IDEntidad'],3);
      $empresa = generaCeros($_SESSION['INGRESO']['item'],3);
	    $carpeta_autorizados = "";		  
        $carpeta_generados = "";
        $carpeta_firmados = "";
        $carpeta_no_autori = "";
		if(file_exists($carpeta_entidad))
		{
			$carpeta_comprobantes = $carpeta_entidad.'/CE'.$empresa;
			if(file_exists($carpeta_comprobantes))
			{
			  $carpeta_autorizados = $carpeta_comprobantes."/Autorizados";		  
			  $carpeta_generados = $carpeta_comprobantes."/Generados";
			  $carpeta_firmados = $carpeta_comprobantes."/Firmados";
			  $carpeta_no_autori = $carpeta_comprobantes."/No_autorizados";
			  $carpeta_rechazados = $carpeta_comprobantes."/Rechazados";
			  $carpeta_rechazados = $carpeta_comprobantes."/Enviados";

				if(!file_exists($carpeta_autorizados))
				{
					mkdir($carpeta_entidad."/CE".$empresa."/Autorizados", 0777);
				}
				if(!file_exists($carpeta_generados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/Generados', 0777);
				}
				if(!file_exists($carpeta_firmados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/Firmados', 0777);
				}
				if(!file_exists($carpeta_no_autori))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/No_autorizados', 0777);
				}
				if(!file_exists($carpeta_rechazados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/Rechazados', 0777);
				}
				if(!file_exists($carpeta_rechazados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/Enviados', 0777);
				}
			}else
			{
				mkdir($carpeta_entidad.'/CE'.$empresa, 0777);
				mkdir($carpeta_entidad."/CE".$empresa."/Autorizados", 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/Generados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/Firmados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/No_autorizados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/Rechazados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/Enviados', 0777);
			}
		}else
		{
			   mkdir($carpeta_entidad, 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa, 0777);
			   mkdir($carpeta_entidad."/CE".$empresa."/Autorizados", 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/Generados', 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/Firmados', 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/No_autorizados', 0777);	  
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/Rechazados', 0777);  
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/Enviados', 0777);
		}




    		$docs = $this->modelo->trans_documentos($datos[0]['Autorizacion']);
    		if(count($docs)>0)
    		{

    			$contenido = $docs[0]['Documento_Autorizado'];
					$archivo = fopen($rutaA,'a');
					fputs($archivo,$contenido);
					fclose($archivo);
    			$archivos[1] = $datos[0]['Autorizacion'].'.xml';
    		}
    	}
    	$cuerpo_correo = '
Este correo electronico fue generado automaticamente a usted desde El Sistema Financiero Contable DiskCover System, porque figura como correo electronico alternativo de '.$_SESSION['INGRESO']['Razon_Social'].'. Nosotros respetamos su privacidad y solamente se utiliza este medio para mantenerlo informado sobre nuestras ofertas, promociones y comunicados. No compartimos, publicamos o vendemos su informacion personal fuera de nuestra empresa. Este mensaje fue procesado por un funcionario que forma parte de la Institucion.

Por la atencion que se de al presente quedo de usted.

Atentamente,

 '.$_SESSION['INGRESO']['Razon_Social'].'

Esta direccion de correo electronico no admite respuestas. En caso de requerir atencion personalizada por parte de un asesor de Servicio al Cliente de VACA PRIETO WALTER JALIL, podra solicitar ayuda mediante los canales oficiales que detallamos a continuación: Telefonos: 026052430 /  Correo: infosistema@diskcoversystem.com.

www.diskcoversystem.com
QUITO - ECUADOR';

    	return  $this->email->enviar_email($archivos,$to_correo,$cuerpo_correo,$titulo_correo,$HTML=false);
    	
    }

    function descargar_factura($parametros)
    {
    	$this->modelo->pdf_factura_descarga($parametros['fac'],$parametros['serie'],$parametros['codigoc']);
       return $parametros['serie'].'-'.generaCeros($parametros['fac'],7).'.pdf';
    }

    function descargar_xml($parametros)
    {
    	$rutaA = dirname(__DIR__,2).'/comprobantes/entidades/entidad_'.generaCeros($_SESSION['INGRESO']['IDEntidad'],3).'/CE'.generaCeros($_SESSION['INGRESO']['item'],3).'/Autorizados/'.$parametros['xml'].'.xml';

    	$rutaB = 'comprobantes/entidades/entidad_'.generaCeros($_SESSION['INGRESO']['IDEntidad'],3).'/CE'.generaCeros($_SESSION['INGRESO']['item'],3).'/Autorizados/'.$parametros['xml'].'.xml';
    	if(file_exists($rutaA))
    	{
    		return array('ruta'=>$rutaB,'xml'=>$parametros['xml'].'.xml');
    	}else
    	{
    		// crea las carpetas si no existen
      $carpeta_entidad = dirname(__DIR__,2).'/comprobantes/entidades/entidad_'.generaCeros($_SESSION['INGRESO']['IDEntidad'],3);
      $empresa = generaCeros($_SESSION['INGRESO']['item'],3);
	    $carpeta_autorizados = "";		  
        $carpeta_generados = "";
        $carpeta_firmados = "";
        $carpeta_no_autori = "";
		if(file_exists($carpeta_entidad))
		{
			$carpeta_comprobantes = $carpeta_entidad.'/CE'.$empresa;
			if(file_exists($carpeta_comprobantes))
			{
			  $carpeta_autorizados = $carpeta_comprobantes."/Autorizados";		  
			  $carpeta_generados = $carpeta_comprobantes."/Generados";
			  $carpeta_firmados = $carpeta_comprobantes."/Firmados";
			  $carpeta_no_autori = $carpeta_comprobantes."/No_autorizados";
			  $carpeta_rechazados = $carpeta_comprobantes."/Rechazados";
			  $carpeta_rechazados = $carpeta_comprobantes."/Enviados";

				if(!file_exists($carpeta_autorizados))
				{
					mkdir($carpeta_entidad."/CE".$empresa."/Autorizados", 0777);
				}
				if(!file_exists($carpeta_generados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/Generados', 0777);
				}
				if(!file_exists($carpeta_firmados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/Firmados', 0777);
				}
				if(!file_exists($carpeta_no_autori))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/No_autorizados', 0777);
				}
				if(!file_exists($carpeta_rechazados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/Rechazados', 0777);
				}
				if(!file_exists($carpeta_rechazados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/Enviados', 0777);
				}
			}else
			{
				mkdir($carpeta_entidad.'/CE'.$empresa, 0777);
				mkdir($carpeta_entidad."/CE".$empresa."/Autorizados", 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/Generados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/Firmados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/No_autorizados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/Rechazados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/Enviados', 0777);
			}
		}else
		{
			   mkdir($carpeta_entidad, 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa, 0777);
			   mkdir($carpeta_entidad."/CE".$empresa."/Autorizados", 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/Generados', 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/Firmados', 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/No_autorizados', 0777);	  
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/Rechazados', 0777);  
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/Enviados', 0777);
		}





    		$docs = $this->modelo->trans_documentos($parametros['xml']);
    		if(count($docs)>0)
    		{


    			$contenido = $docs[0]['Documento_Autorizado'];
					$archivo = fopen($rutaA,'a');
					fputs($archivo,$contenido);
					fclose($archivo);
    			return array('ruta'=>$rutaB,'xml'=>$parametros['xml'].'.xml');
    		}else
    		{
    			return -1;
    		}
    	}
    }

        
}
?>