<?php
require_once(dirname(__DIR__,2)."/modelo/facturacion/cierre_diario_cajaM.php");
require_once(dirname(__DIR__,3)."/lib/fpdf/cabecera_pdf.php");
require_once(dirname(__DIR__,3)."/lib/phpmailer/enviar_emails.php");

$controlador = new cierre_diario_cajaC();
if(isset($_GET['consultar_cierre']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->consulta_cierre($parametros));
}
if(isset($_GET['pdf']))
{
	$parametros = $_GET;
	echo json_encode($controlador->generar_pdf($parametros));
}
if(isset($_GET['excel']))
{
	$parametros = $_GET;
	echo json_encode($controlador->generar_excel($parametros));
}
if(isset($_GET['generar_email']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->generar_email($parametros));
}

class cierre_diario_cajaC
{
	private $modelo;
    private $email;
    private $pdf;

	public function __construct(){
        $this->modelo = new cierre_diario_cajaM();
        $this->pdf = new cabecera_pdf();
        $this->email = new enviar_emails();
    }

    function consulta_cierre($parametros)
    {
    	if(isset($parametros['desde']) && isset($parametros['hasta']) && $parametros['desde']!='' && $parametros['hasta']!='')
    	{
    		$d = sp_Reporte_Cartera_Clientes($parametros['cliente'],$parametros['desde'],$parametros['hasta']);
    		// print_r($d);die();
    	}
        $tabla = $this->modelo->consultar_reporte_cartera($tabla=1);
        // print_r($tabla);die();
        return $tabla;
    }
    function generar_pdf($parametros)
    {
    	$descargar = true;
    	if(isset($parametros['descargar']))
    	{
    		$descargar = false;
    	}
		sp_Reporte_Cartera_Clientes($parametros['DCCliente'],$parametros['txt_desde'],$parametros['txt_hasta']);  	
        $tabla = $this->modelo->consultar_reporte_cartera();
        $titulo = 'REPORTE CARTERA DE CLIENTES';

        $tablaHTML = array();
		$tablaHTML[0]['medidas']=array(190);
		$tablaHTML[0]['alineado']=array('C');
        $tablaHTML[0]['datos']=array('REPORTE CARTERA DE CLIENTES');
		$tablaHTML[0]['estilo']='B';
		$tablaHTML[0]['borde'] = 'TB';
		// $pos=1;
		$cli = '';$dir = '';$emails='';
		if(count($tabla)>0)
		{
			$cli = $tabla[0]['Cliente'];
			$dir = $tabla[0]['Direccion'];
			$emails =$tabla[0]['Email'].','.$tabla[0]['EmailR'];
		}	
		
		$tablaHTML[1]['medidas']=array(190);
		$tablaHTML[1]['alineado']=array('L');
        $tablaHTML[1]['datos']=array('CLIENTE:'.$cli);
		// $tablaHTML[$pos]['borde'] = 'LR';

		// $pos = $pos+1;
		
		$tablaHTML[2]['medidas']=array(190);
		$tablaHTML[2]['alineado']=array('L');
        $tablaHTML[2]['datos']=array('UBICACION:'.$dir);
		// $tablaHTML[$pos]['borde'] = 'LR';

		// $pos = $pos+1;
		$tablaHTML[3]['medidas']=array(190);
		$tablaHTML[3]['alineado']=array('L');
        $tablaHTML[3]['datos']=array('EMAILS:'.$emails);
		// $tablaHTML[$pos]['borde'] = 'LR';

		// $pos = $pos+1;

		$tablaHTML[4]['medidas']=array(190);
		$tablaHTML[4]['alineado']=array('L');
        $tablaHTML[4]['datos']=array('La informacion presente reposa en la base de dato de la Institucion, corte realizado desde '.$parametros['txt_desde'].' al '.$parametros['txt_hasta'].', cualquier informacion adicional comuniquese a la institucion');
		// $tablaHTML[$pos]['borde'] = 'LR';

		// $pos = $pos+1;

		$tablaHTML[5]['medidas']=array(6,10,15,15,25,70,15,10,15,15);
		$tablaHTML[5]['alineado']=array('L','L','L','L','L','L','L','L','R','R');
        $tablaHTML[5]['datos']=array('T','TC','Serie','Factura','Fecha','Detalle','Anio','Mes','Cargos','Abonos');
		$tablaHTML[5]['estilo']='B';
		$tablaHTML[5]['borde'] = 'TB';


		$pos = 6;
		// print_r($tabla);die();
		 foreach ($tabla as $key => $value) {
			$tablaHTML[$pos]['medidas']=$tablaHTML[5]['medidas'];
	        $tablaHTML[$pos]['alineado']=$tablaHTML[5]['alineado'];
	        $tablaHTML[$pos]['datos']=array($value['T'],$value['TC'],$value['Serie'],$value['Factura'],$value['Fecha']->format('Y-m-d'),$value['Detalle'],$value['Anio'],$value['Mes'],number_format($value['Cargos'],2),number_format($value['Abonos'],2));
	        // $tablaHTML[$pos]['datos']=array('','','','','','','','');
	        // print_r($value['Detalle']);
	        if(trim($value['Detalle'])=='SALDO TOTAL')
	        {
	        	$tablaHTML[$pos]['borde'] ='TB';
	    	}
	       $pos = $pos+1;
			
		 }


		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$Fechaini=false,$Fechafin=false,9,true,15,'P',$descargar);




        // print_r($tabla);die();
    }
    function generar_excel($parametros)
    {
    	sp_Reporte_Cartera_Clientes($parametros['DCCliente'],$parametros['txt_desde'],$parametros['txt_hasta']);    
        $tabla = $this->modelo->consultar_reporte_cartera();

      //    $tablaHTML =array();
	   	 // $tablaHTML[0]['medidas']=array(9,9,9,9,20,50,18,18);
      //    $tablaHTML[0]['datos']=array('Clave','TC','ME','DG','Codigo','Cuenta','Presupuesto','Codigo_Ext');
      //    $tablaHTML[0]['tipo'] ='C';



        $tablaHTML = array();
		// $tablaHTML[0]['medidas']=array(190);
  //       $tablaHTML[0]['datos']=array('REPORTE CARTERA DE CLIENTES');
	 //    $tablaHTML[0]['tipo'] ='C';	 
	 //    $tablaHTML[0]['col-total'] = 10;     
	    // $tablaHTML[0]['unir'] ='AJ';
		// $pos=1;
		$cli = '';$dir = '';$emails='';
		if(count($tabla)>0)
		{
			$cli = $tabla[0]['Cliente'];
			$dir = $tabla[0]['Direccion'];
			$emails =$tabla[0]['Email'].','.$tabla[0]['EmailR'];
		}	


		$tablaHTML[0]['medidas']=array(190);
        $tablaHTML[0]['datos']=array('CLIENTE:'.$cli);
	    $tablaHTML[0]['tipo'] ='N';
		$tablaHTML[0]['unir'] = array('AJ');
	    $tablaHTML[0]['col-total'] = 10;    

		// $pos = $pos+1;
		$tablaHTML[1]['medidas']=array(190);
        $tablaHTML[1]['datos']=array('UBICACION:'.$dir);
	    $tablaHTML[1]['tipo'] ='N';
		$tablaHTML[1]['unir'] = array('AJ');
		// $tablaHTML[$pos]['borde'] = 'LR';

		// $pos = $pos+1;
		$tablaHTML[2]['medidas']=array(190);
        $tablaHTML[2]['datos']=array('EMAILS:'.$emails);
	    $tablaHTML[2]['tipo'] ='N';
		$tablaHTML[2]['unir'] = array('AJ');
		// $tablaHTML[$pos]['borde'] = 'LR';

		// $pos = $pos+1;

		$tablaHTML[3]['medidas']=array(190);
        $tablaHTML[3]['datos']=array('La informacion presente reposa en la base de dato de la Institucion, corte realizado desde '.$parametros['txt_desde'].' al '.$parametros['txt_hasta'].', cualquier informacion adicional comuniquese a la institucion');
	    $tablaHTML[3]['tipo'] ='N';	  	    
		$tablaHTML[3]['unir'] = array('AJ');
	  
		// $tablaHTML[$pos]['borde'] = 'LR';

		// $pos = $pos+1;

		$tablaHTML[4]['medidas']=array(6,10,10,10,15,70,15,10,15,15);
        $tablaHTML[4]['datos']=array('T','TC','Serie','Factura','Fecha','Detalle','Anio','Mes','Cargos','Abonos');
		$tablaHTML[4]['tipo'] ='SUB';


		$pos = 5;
		 foreach ($tabla as $key => $value) {
			$tablaHTML[$pos]['medidas']=$tablaHTML[4]['medidas'];
	        $tablaHTML[$pos]['datos']=array($value['T'],$value['TC'],$value['Serie'],$value['Factura'],$value['Fecha']->format('Y-m-d'),$value['Detalle'],$value['Anio'],$value['Mes'],number_format($value['Cargos'],2),number_format($value['Abonos'],2));
	        $tablaHTML[$pos]['tipo'] ='N';
			if(trim($value['Detalle'])=='SALDO TOTAL')
	        {	        	
	        	$tablaHTML[$pos]['tipo'] ='SUB';
	    	}
	       $pos = $pos+1;
			
		 }


	    excel_generico($titulo='REPORTE CARTERA DE CLIENTES',$tablaHTML);

    	// print_r($parametros);die();
    }

    function generar_email($parametros)
    {
     $cuerpo_correo = 'Envio automatizado de su cartera pendiente.
NOTA: En caso de tener inconformidad con los valores detallados en su Estado de Cuenta, comuniquese con atencion al Cliente.
.
Este correo electronico fue generado automaticamente a usted desde El Sistema Financiero Contable DiskCover System, porque figura como correo electronico alternativo de VACA PRIETO WALTER JALIL. Nosotros respetamos su privacidad y solamente se utiliza este medio para mantenerlo informado sobre nuestras ofertas, promociones y comunicados. No compartimos, publicamos o vendemos su informacion personal fuera de nuestra empresa. Este mensaje fue procesado por: Vaca Prieto Walter Jalil, funcionario que forma parte de la Institucion.

Por la atencion que se de al presente quedo de usted.

Atentamente,

Walter Jalil Vaca Prieto
VACA PRIETO WALTER JALIL

Esta direccion de correo electronico no admite respuestas. En caso de requerir atencion personalizada por parte de un asesor de Servicio al Cliente de VACA PRIETO WALTER JALIL, podra solicitar ayuda mediante los canales oficiales que detallamos a continuación: Telefonos: 026052430 /  Correo: infosistema@diskcoversystem.com.

www.diskcoversystem.com
QUITO - ECUADOR';
        $titulo_correo = 'Estimado(a): , usted tiene los siguientes pendientes.';
        $datos = $this->modelo->consultar_reporte_cartera();
        $to_correo = $datos[0]['Email'].','.$datos[0]['EmailR'];
        $correo_apoyo = 'diskcover.system@gmail.com';


       
        $parametros['descargar'] = true;
        $this->generar_pdf($parametros);
        $archivos = array(0 =>'REPORTE CARTERA DE CLIENTES.pdf');
        $res = $this->email->enviar_email($archivos,$to_correo,$cuerpo_correo,$titulo_correo,$HTML=false);

      return $res;
    }
   
}
?>