<?php
require_once(dirname(__DIR__,2)."/modelo/facturacion/lista_notas_creditoM.php");
require_once(dirname(__DIR__,2)."/modelo/facturacion/punto_ventaM.php");
require_once(dirname(__DIR__,2)."/modelo/facturacion/notas_creditoM.php");
require_once(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
if(!class_exists('enviar_emails'))
{
	require_once(dirname(__DIR__,3).'/lib/phpmailer/enviar_emails.php');
}
// require(dirname(__DIR__,2)."/comprobantes/SRI/autorizar_sri.php");

$controlador = new lista_notas_creditoC();

if(isset($_GET['tabla']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->tabla_facturas($parametros));
}

if(isset($_GET['autorizar_nota']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->autorizar_sri($parametros));
}

if(isset($_GET['Ver_nota_credito']))
{
  $controlador->ver_nota_credito_pdf($_GET['nota'],$_GET['serie']);
}

if(isset($_GET['enviar_email_detalle']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->enviar_email_detalle($parametros));
}

if(isset($_GET['descargar_notacredito']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->descargar_notacredito($parametros));
}

if(isset($_GET['descargar_xml']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->descargar_xml($parametros));
}




/**
 * 
 */
class lista_notas_creditoC
{	
	private $modelo;
    private $email;
    public $pdf;
    private $punto_venta;
    private $empresaGeneral;
    private $sri;
    private $notas_credito;
    
	public function __construct(){
    	$this->modelo = new lista_notas_creditoM();
		$this->pdf = new cabecera_pdf();
		$this->email = new enviar_emails();
		$this->empresaGeneral = Empresa_data();
		$this->sri = new autorizacion_sri();
		$this->punto_venta = new punto_ventaM();
        $this->notas_credito = new notas_creditoM();
    }

   function tabla_facturas($parametros)
    {

    	$serie = false;
    	if(isset($parametros['serie']) && $parametros['serie']!='')
    	{
    		$serie = explode(" ", $parametros['serie']);
    		$serie = $serie[1];
    	}
    	$codigo = $parametros['ci'];
    	$tbl = $this->modelo->notas_credito_emitidas_tabla($codigo,$parametros['desde'],$parametros['hasta'],$serie);
    	$tr='';

    	foreach ($tbl as $key => $value) {
    		$exis = $this->sri->catalogo_lineas($value['TP'], $value['Serie']);
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
    	// print_r($parametros);die();
    	$datos = $this->modelo->notas_credito_emitidas_tabla($codigo=false,$desde=false,$hasta=false,$parametros['serie'],$parametros['nota']);

    	// print_r($datos);

    $TFA['Serie_NC'] = $parametros['serie'];
		$TFA['Nota_Credito'] = $parametros['nota'];
		$TFA['Serie'] = $datos[0]['Serie'];
		$TFA['TC'] = $datos[0]['Serie'];
		$TFA['Factura'] = $datos[0]['Factura'];
		$TFA['Porc_NC'] = $datos[0]['Porc_IVA'];
		$TFA['Autorizacion'] = $datos[0]['Autorizacion'];	
		$TFA['Fecha'] = $datos[0]['FechaF'];		
		$TFA['Fecha_NC'] = $datos[0]['Fecha']->format('Y-m-d');

		$TFA['Cod_Ejec'] = $datos[0]['Cod_Ejec'];
		$TFA['CodigoU'] = $datos[0]['CodigoU'];
		$TFA['Tipo_Pago'] = $datos[0]['Tipo_Pago'];
		$TFA['Cod_CxC'] = $datos[0]['Cod_CxC']	;
		$TFA['TB'] = $datos[0]['TB'];
		$TFA['RUC_CI'] = $datos[0]['CI_RUC'];
		$TFA['Cliente'] = $datos[0]['Cliente'];
		$TFA['Razon_Social'] = $datos[0]['Cliente'];
		// die();


        $res = $this->sri->SRI_Crear_Clave_Acceso_Nota_Credito($TFA);
       $clave = $this->sri->Clave_acceso($TFA['Fecha_NC'],'04',$TFA['Serie_NC'],$TFA['Nota_Credito']);		        	 
		return array('respuesta'=>$res,'pdf'=>$TFA['Serie_NC'].'-'.generaCeros($TFA['Nota_Credito'],7),'clave'=>$clave);


        // return $res;

    }

    function ver_nota_credito_pdf($nota,$serie)
    {
    	$datos = $this->modelo->notas_credito_emitidas_tabla($codigo=false,$desde=false,$hasta=false,$serie,$secuencia_NC=$nota);

    	// print_r($datos);die();

    	$TFA['TC'] = $datos[0]['TC'];    	
    	$TFA['imprimir'] = 0;
		$TFA['Serie'] = $datos[0]['Serie'];
		$TFA['Autorizacion'] = $datos[0]['Autorizacion'];
		$TFA['Factura'] = $datos[0]['Factura'];
		$TFA['Serie_NC'] = $datos[0]['Serie_NC'];
		$TFA['Nota_Credito'] = $datos[0]['Secuencial_NC'];
		$TFA['CodigoC'] = $datos[0]['Codigo'];		
		$TFA['Fecha'] = $datos[0]['FechaF'];	
		$TFA['IVA'] = $datos[0]['IVA'];
		$TFA['Porc_IVA'] = $datos[0]['Porc_IVA'];
		$TFA['Nota'] = $datos[0]['Nota'];
		$TFA['Total_MN'] = $datos[0]['Total_MN'];		
		$TFA['Fecha_NC'] = $datos[0]['Fecha'];
		$TFA['Autorizacion_NC'] = $datos[0]['Autorizacion_NC']; 
		$TFA['ClaveAcceso_NC'] = $datos[0]['Autorizacion_NC'];

		$TFA['SubTotal_NC'] = 0;
		$TFA['Descuento'] = 0;
		$lineas = $this->modelo->lineas_nota_credito($TFA['Serie'],$TFA['Factura']);
		$SubTotal_NC = 0;
		$descuento = 0;
		foreach ($lineas as $key => $value) {
			$SubTotal_NC = $SubTotal_NC + $value["SUBTOTAL"];			
		    $descuento = $value['Total_Desc']+$value['Total_Desc2'];
		}
		$TFA['Descuento'] =  $descuento;
		$TFA['SubTotal_NC'] = $SubTotal_NC;
/*
		$TFA['Total_IVA_NC'] = 0;
		/*$TFA['Total_IVA_NC']
		$TFA['SubTotal_NC']*/

/*
    	$TFA['TC'] = 'NC';
		$TFA['Serie'] = '001003';
		$TFA['Autorizacion'] = '0604202201070216417900110010030000006691234567814';
		$TFA['Factura'] = '669';
		$TFA['Serie_NC'] = '001003';
		$TFA['Nota_Credito'] = '71';
		$TFA['CodigoC'] = '1792558662';

		$TFA['Fecha_NC'] = date('Y-m-d');

		$TFA['Fecha'] = date('Y-m-d');
		$TFA['Autorizacion_NC'] = '0902202304070216417900110010030000000711234567818';
		$TFA['ClaveAcceso_NC']  = '0902202304070216417900110010030000000711234567818';
		$TFA['Porc_IVA'] = '12';
		$TFA['Descuento']=0;
		$TFA['Descuento2'] = 0;
		$TFA['IVA'] = '0';
		$TFA['Total_MN'] = 0;
		$TFA['Nota'] = '- Nota de Crédito de: VACA PRIETO WALTER JALIL';


    	// print_r($nota);die();*/
      	$this->notas_credito->pdf_nota_credito($TFA);
	 	
   
    }

     function descargar_notacredito($parametros)
    {
    	$datos = $this->modelo->notas_credito_emitidas_tabla($codigo=false,$desde=false,$hasta=false,$parametros['serie_nc'],$parametros['nota']);

    	// print_r($datos);die();
    	if(count($datos)>0)
    	{
	    	$TFA['TC'] = $datos[0]['TC'];    	
	    	$TFA['imprimir'] = 1;
			$TFA['Serie'] = $datos[0]['Serie'];
			$TFA['Autorizacion'] = $datos[0]['Autorizacion'];
			$TFA['Factura'] = $datos[0]['Factura'];
			$TFA['Serie_NC'] = $datos[0]['Serie_NC'];
			$TFA['Nota_Credito'] = $datos[0]['Secuencial_NC'];
			$TFA['CodigoC'] = $datos[0]['Codigo'];		
			$TFA['Fecha'] = $datos[0]['FechaF'];	
			$TFA['IVA'] = $datos[0]['IVA'];
			$TFA['Porc_IVA'] = $datos[0]['Porc_IVA'];
			$TFA['Nota'] = $datos[0]['Nota'];
			$TFA['Total_MN'] = $datos[0]['Total_MN'];		
			$TFA['Fecha_NC'] = $datos[0]['Fecha'];
			$TFA['Autorizacion_NC'] = $datos[0]['Autorizacion_NC']; 
			$TFA['ClaveAcceso_NC'] = $datos[0]['Autorizacion_NC'];

			$TFA['SubTotal_NC'] = 0;
			$TFA['Descuento'] = 0;
			$lineas = $this->modelo->lineas_nota_credito($TFA['Serie'],$TFA['Factura']);
			$SubTotal_NC = 0;
			$descuento = 0;
			foreach ($lineas as $key => $value) {
				$SubTotal_NC = $SubTotal_NC + $value["SUBTOTAL"];			
			    $descuento = $value['Total_Desc']+$value['Total_Desc2'];
			}
			$TFA['Descuento'] =  $descuento;
			$TFA['SubTotal_NC'] = $SubTotal_NC;

	      	$this->notas_credito->pdf_nota_credito($TFA);    	
	       return $parametros['serie_nc'].'-'.generaCeros($parametros['nota'],7).'.pdf';
   		}else
   		{
   			return -1;
   		}
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

    	$datos = $this->modelo->notas_credito_emitidas_tabla($codigo=false,$desde=false,$hasta=false,$parametros['serie_nc'],$parametros['nota']);

    	// print_r($datos);die();

    	$TFA['TC'] = $datos[0]['TC'];    	
    	$TFA['imprimir'] = 1;
		$TFA['Serie'] = $datos[0]['Serie'];
		$TFA['Autorizacion'] = $datos[0]['Autorizacion'];
		$TFA['Factura'] = $datos[0]['Factura'];
		$TFA['Serie_NC'] = $datos[0]['Serie_NC'];
		$TFA['Nota_Credito'] = $datos[0]['Secuencial_NC'];
		$TFA['CodigoC'] = $datos[0]['Codigo'];		
		$TFA['Fecha'] = $datos[0]['FechaF'];	
		$TFA['IVA'] = $datos[0]['IVA'];
		$TFA['Porc_IVA'] = $datos[0]['Porc_IVA'];
		$TFA['Nota'] = $datos[0]['Nota'];
		$TFA['Total_MN'] = $datos[0]['Total_MN'];		
		$TFA['Fecha_NC'] = $datos[0]['Fecha'];
		$TFA['Autorizacion_NC'] = $datos[0]['Autorizacion_NC']; 
		$TFA['ClaveAcceso_NC'] = $datos[0]['Autorizacion_NC'];

		$TFA['SubTotal_NC'] = 0;
		$TFA['Descuento'] = 0;
		$lineas = $this->modelo->lineas_nota_credito($TFA['Serie'],$TFA['Factura']);
		$SubTotal_NC = 0;
		$descuento = 0;
		foreach ($lineas as $key => $value) {
			$SubTotal_NC = $SubTotal_NC + $value["SUBTOTAL"];			
		    $descuento = $value['Total_Desc']+$value['Total_Desc2'];
		}
		$TFA['Descuento'] =  $descuento;
		$TFA['SubTotal_NC'] = $SubTotal_NC;

      	$this->notas_credito->pdf_nota_credito($TFA);     	

    	$archivos[0] =$parametros['serie_nc'].'-'.generaCeros($parametros['nota'],7).'.pdf';

    	$autorizar = $parametros['autoriza'];
// print_r('expression');die();
    	
    	$rutaA = dirname(__DIR__,2).'/comprobantes/entidades/entidad_'.generaCeros($_SESSION['INGRESO']['IDEntidad'],3).'/CE'.generaCeros($_SESSION['INGRESO']['item'],3).'/Autorizados/'.$autorizar.'.xml';

    		// print_r($rutaA);die();
    	if(file_exists($rutaA))
    	{
    		$archivos[1] = $autorizar.'.xml';
    		// print_r($archivos);die();
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

    	// print_r($archivo);die();
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