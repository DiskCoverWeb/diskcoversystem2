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
    	// print_r($tbl);die();
    	foreach ($tbl as $key => $value) {
    		$exis = $this->sri->catalogo_lineas($value['TP'], $value['Serie_Retencion']);
    		// print_r($exis);die();
			$tbl[$key]['ExisteSerie'] = 'No';
			if(count($exis)>0)
			{
				$tbl[$key]['ExisteSerie'] = 'Si';
			}
    	}

    	return $tbl;
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