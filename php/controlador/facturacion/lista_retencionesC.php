<?php
require_once(dirname(__DIR__,2)."/modelo/facturacion/lista_retencionesM.php");
require_once(dirname(__DIR__,2)."/modelo/facturacion/punto_ventaM.php");
require_once(dirname(__DIR__,2)."/modelo/contabilidad/incomM.php");
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
if(!class_exists('enviar_emails'))
{
	require(dirname(__DIR__,3).'/lib/phpmailer/enviar_emails.php');
}
// require(dirname(__DIR__,2)."/comprobantes/SRI/autorizar_sri.php");

$controlador = new lista_retencionesC();

if(isset($_GET['tabla']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->tabla_facturas($parametros));
}

if(isset($_GET['autorizar_retencion']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->autorizar_sri($parametros));
}

if(isset($_GET['Ver_retencion']))
{
  $controlador->ver_retencion_pdf($_GET['retencion'],$_GET['serie'],$_GET['numero'],$_GET['tp']);
}

if(isset($_GET['enviar_email_detalle']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->enviar_email_detalle($parametros));
}

if(isset($_GET['descargar_retencion']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->descargar_retencion($parametros));
}

if(isset($_GET['descargar_xml']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->descargar_xml($parametros));
}




/**
 * 
 */
class lista_retencionesC
{	
	private $modelo;
    private $email;
    public $pdf;
    private $punto_venta;
    private $empresaGeneral;
	private $sri;
	private $incom;
    
	public function __construct(){
    	$this->modelo = new lista_retencionesM();
		$this->pdf = new cabecera_pdf();
		$this->email = new enviar_emails();
		$this->empresaGeneral = Empresa_data();
		$this->sri = new autorizacion_sri();
		$this->punto_venta = new punto_ventaM();
		$this->incom = new incomM();
        //$this->modelo = new MesaModel();
    }

     function tabla_facturas($parametros)
    {
    	// print_r($parametros);die();
    	$codigo = $parametros['ci'];
    	$tbl = $this->modelo->retenciones_emitidas_tabla($codigo,$parametros['desde'],$parametros['hasta'],$parametros['serie']);
    	$tr='';
    	foreach ($tbl as $key => $value) {
    		 $exis =  true;//$this->sri->catalogo_lineas('RE',$value['Serie_Retencion']);
    		 $autorizar = '';$anular = '';
    		 $cli_data = Cliente($value['IdProv']);
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
    		 // print_r($exis);die();$retencion,$numero,$serie_r
    		$tr.='<tr>
            <td>
            <div class="input-group-btn">
					<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones
					<span class="fa fa-caret-down"></span></button>
					<ul class="dropdown-menu">
					<li><a href="#" onclick="Ver_retencion(\''.$value['SecRetencion'].'\',\''.$value['Serie_Retencion'].'\',\''.$value['Numero'].'\',\''.$value['TP'].'\')"><i class="fa fa-eye"></i> Ver Retencion</a></li>';
					if(strlen($value['AutRetencion'])==13)
					{
						$tr.='<li><a href="#" onclick="autorizar(\''.$value['SecRetencion'].'\',\''.$value['Serie_Retencion'].'\',\''.$value['Fecha']->format('Y-m-d').'\')" ><i class="fa fa-paper-plane"></i>Autorizar</a></li>';
					}else if(strlen($value['AutRetencion'])==13)
					{
						$tr.='<li><a class="btn-danger"><i class="fa fa-info"></i>Para autorizar Asigne el catalo de lineas la serie:'.$value['Serie_Retencion'].'</a></li>';
					}
					if($value['T']!='A')
					{
						$tr.='<li><a href="#" onclick="anular_factura(\''.$value['SecRetencion'].'\',\''.$value['Serie_Retencion'].'\',\''.$value['IdProv'].'\')"><i class="fa fa-times-circle"></i>Anular Retencion</a></li>';
					}
					$tr.='<li><a href="#" onclick=" modal_email_ret(\''.$value['SecRetencion'].'\',\''.$value['Serie_Retencion'].'\',\''.$value['Numero'].'\',\''.$value['AutRetencion'].'\',\''.$email.'\')"><i class="fa fa-envelope"></i> Enviar Retencion por email</a></li>
					<li><a href="#" onclick="descargar_ret(\''.$value['SecRetencion'].'\',\''.$value['Serie_Retencion'].'\',\''.$value['Numero'].'\')"><i class="fa fa-download"></i> Descargar Retencion</a></li>';
					if(strlen($value['AutRetencion'])>13)
					{
					 $tr.='<li><a href="#" onclick="descargar_xml(\''.$value['AutRetencion'].'\')"><i class="fa fa-download"></i> Descargar XML</a></li>';
					}
					 $tr.='
					</ul>
			</div>


            </td>
            <td>'.$value['T'].'</td>
            <td>'.$value['Cliente'].'</td>
            <td>'.$value['TD'].'</td>
            <td>'.$value['Serie_Retencion'].'</td>
            <td>'.$value['AutRetencion'].'</td>
            <td>'.$value['SecRetencion'].'</td>
            <td>'.$value['Fecha']->format('Y-m-d').'</td>
            <td class="text-right">'.$value['BaseImponible'].'</td>
            <td>'.$value['CI_RUC'].'</td>
            <td>RE</td>
            <td>'.$value['Numero'].'</td>
            <td>'.$value['TP'].'</td>
          </tr>';
    	}

    	// print_r($tr);die();

    	return $tr;
    }


    function autorizar_sri($parametros)
    {
    	$datos = $this->modelo->retenciones_buscar($parametros['serie'],$parametros['Retencion'],$parametros['Fecha']);
    	// print_r($datos);die();
    	$parametros_xml = array();
        $parametros_xml['Autorizacion_R']=$datos[0]['AutRetencion'];
        $parametros_xml['Retencion']=$datos[0]['SecRetencion'];
        $parametros_xml['Serie_R']=$datos[0]['Serie_Retencion'];
        $parametros_xml['TP']=$datos[0]['TP'];
        $parametros_xml['Fecha']=$parametros['Fecha'];
        $parametros_xml['Numero']=$datos[0]['Numero'];
        $parametros_xml['ruc']=$datos[0]['CI_RUC'];

        $autorizacion = $this->sri->Clave_acceso($parametros['Fecha'],'07',$parametros['serie'],$parametros['Retencion']);
        $res = $this->sri->Autorizar_retencion($parametros_xml);

       return array('respuesta'=>$res,'pdf'=>'','text'=>$res,'clave'=>$autorizacion);


        // return $res;

    }

    function ver_retencion_pdf($retencion,$serie_r,$numero,$tp)
    {
    	// print_r($cod);die();
    	// $TP = 'CE';  	 
      	$this->incom->reporte_retencion($numero,$tp,$retencion,$serie_r,$imp=0);
	 	
   
    }

     function descargar_retencion($parametros)
    {
    	$TP = 'CD';  	 
    	$numero =$parametros['numero'];
    	$retencion = $parametros['retencion'];
    	$serie_r = $parametros['serie'];
      	$this->incom->reporte_retencion($numero,$TP,$retencion,$serie_r,$imp=1);    	
       return 'RE_'.$parametros['serie'].'-'.generaCeros($parametros['retencion'],9).'.pdf';
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
    		$this->sri->generar_carpetas(generaCeros($_SESSION['INGRESO']['IDEntidad'],3),generaCeros($_SESSION['INGRESO']['item'],3));

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

    function enviar_email_detalle($parametros)
    {
    	$to_correo = substr($parametros['to'],0,-1);
    	$cuerpo_correo = $parametros['cuerpo'];
    	$titulo_correo = $parametros['titulo'];

    	$TP = 'CD';  	 
    	$numero =$parametros['numero'];
    	$retencion = $parametros['retencion'];
    	$serie_r = $parametros['serie'];
      	$this->incom->reporte_retencion($numero,$TP,$retencion,$serie_r,$imp=1);    	

    	$archivos[0] ='RE_'.$parametros['serie'].'-'.generaCeros($parametros['retencion'],9).'.pdf';

    	$autorizar = $parametros['autoriza'];

    	
    	$rutaA = dirname(__DIR__,2).'/comprobantes/entidades/entidad_'.generaCeros($_SESSION['INGRESO']['IDEntidad'],3).'/CE'.generaCeros($_SESSION['INGRESO']['item'],3).'/Autorizados/'.$autorizar.'.xml';
    	if(file_exists($rutaA))
    	{
    		$archivos[1] = $autorizar.'.xml';
    	}else
    	{
    		$this->sri->generar_carpetas(generaCeros($_SESSION['INGRESO']['IDEntidad'],3),generaCeros($_SESSION['INGRESO']['item'],3));

    		$docs = $this->modelo->trans_documentos($autorizar);
    		if(count($docs)>0)
    		{

    			$contenido = $docs[0]['Documento_Autorizado'];
					$archivo = fopen($rutaA,'a');
					fputs($archivo,$contenido);
					fclose($archivo);
    			$archivos[1] = $autorizar.'.xml';
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



}


?>