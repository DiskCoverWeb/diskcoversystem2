<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');
$tipo=2;require_once(dirname(__DIR__,2)."/db/chequear_seguridad.php");
require_once(dirname(__DIR__,2).'/modelo/educativo/detalle_estudianteM.php'); 
require_once(dirname(__DIR__,2).'/funciones/funciones.php');
require_once(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
// require(dirname(__DIR__,2).'/lib/phpmailer/class.phpmailer.php');
// require(dirname(__DIR__,2).'/lib/phpmailer/class.smtp.php');
require_once(dirname(__DIR__,3).'/lib/phpmailer/enviar_emails.php');
// include(dirname(__DIR__,3)."/lib/excel/plantilla.php");
/**
 * 
 */

$controlador = new detalle_estudianteC();
if(isset($_GET['cursos']))
{
	//$controlador = new detalle_estudianteC();
	echo json_encode($controlador->lista_cursos());
}
if(isset($_GET['validar_estudiante']))
{
	echo json_encode($controlador->validar_estudiante($_POST['usu'],$_POST['pass'],$_POST['nuevo']));

}
if(isset($_GET['existente']))
{
  echo json_encode($controlador->usu_regi($_POST['usu']));
}
if(isset($_GET['existente_clave']))
{
  echo json_encode($controlador->usu_clave_regi($_POST['ci'],$_POST['cla']));
}
if(isset($_GET['cliente_proveedor']))
{
  echo json_encode($controlador->cliente_proveedor($_POST['cli']));
}
if(isset($_GET['fac_emi']))
{
  echo json_encode($controlador->fact_emitidas($_POST['codigo']));

}
if(isset($_GET['imprimir_excel']))
{
  $periodo = '';
  if(isset($_GET['per']))
  {
    $perido = $_GET['per'];
  }
  $tc ='FA';
  if(isset($_GET['TC']))
  {
    $tc = $_GET['TC'];
  }
  $controlador->exportar_Excel($_GET['codigo'],$perido,$tc);

}
if(isset($_GET['imprimir_pdf']))
{
  $controlador->exportar_pdf($_GET['codigo']);

}
if(isset($_GET['naciones']))
{
  //echo 'hola';
  echo json_encode(naciones_todas());
  //print_r(provincia_todas());
}
if(isset($_GET['provincias']))
{
	//echo 'hola';
  $pais = '';
  if(isset($_POST['pais']))
  {
    $pais = $_POST['pais'];
  }
	echo json_encode(provincia_todas($pais));
	//print_r(provincia_todas());
}
if(isset($_GET['ciudad']))
{
	echo json_encode(todas_ciudad($_POST['idpro']));
}
if(isset($_GET['cargar_imagen']))
{
   echo json_encode($controlador->guardar_foto($_FILES,$_POST));
}
if(isset($_GET['nuevo']))
{
	$parametros = $_POST['parametro'];
	echo json_encode($controlador->nuevo_estudiante($parametros));
}

if(isset($_GET['update_alumno']))
{
	$parametros = $_POST['parametro'];
	//print_r($parametros);
	echo json_encode($controlador->actualizar_alumno($parametros));
}
if(isset($_GET['update_alumno_matricula']))
{
  $parametros = $_POST['parametro'];
  //print_r($parametros);
  echo json_encode($controlador->actualizar_alumno_matricula($parametros));
}
if(isset($_GET['update_fami']))
{
	$parametros = $_POST['parametro'];
	//print_r($parametros);
	echo  json_encode($controlador->actualizar_fami($parametros));
}
if(isset($_GET['update_repre']))
{
	$parametros = $_POST['parametro'];
	//print_r($parametros);
	echo json_encode($controlador->actualizar_repre($parametros));
}
if(isset($_GET['generar_archivos']))
{
	//$parametros = $_POST['parametro'];
	//print_r($parametros);
	echo json_encode($controlador->generar_pdf($_GET['usu'],$_GET['pass'],$_GET['nuevo_usu'],$_GET['email']));
}

if(isset($_GET['generar_archivos_matricula']))
{
  //$parametros = $_POST['parametro'];
  //print_r($parametros);
  $descargar = false;
  if(isset($_GET['descargar']))
  {
    $descargar = $_GET['descargar'];
  }
  echo json_encode($controlador->generar_pdf_matricula($_GET['usu'],$_GET['pass'],$descargar));
}

if(isset($_GET['generar_archivos2']))
{
	//$parametros = $_POST['parametro'];
	//print_r($parametros);
	echo json_encode($controlador->pdf_registro($_GET['usu'],$_GET['pass'],$_GET['nuevo_usu'],$_GET['email']));
}
if(isset($_GET['generar_archivos3']))
{
	//$parametros = $_POST['parametro'];
	//print_r($parametros);
	echo json_encode($controlador->pdf_matricula($_GET['usu'],$_GET['pass'],$_GET['nuevo_usu'],$_GET['email']));
}
if(isset($_GET['num_matricula']))
{
	$curso = $_POST['curso'];
	//print_r($parametros);
	echo json_encode($controlador->codigo_matricula($curso));
}
if(isset($_GET['enviar_email_']))
{
	echo json_encode($controlador->enviar_email_($_POST['usu'],$_POST['pass'],$_POST['nuevo_usu']));
}
if(isset($_GET['enviar_email_evidencia']))
{
  echo json_encode($controlador->enviar_email_evidencia($_POST['parametros']));
}
if(isset($_GET['enviar_email_evidencia_matricula']))
{
  echo json_encode($controlador->enviar_email_evidencia_matricula($_POST['parametros']));
}
if(isset($_GET['cargar_file']))
{
   echo json_encode($controlador->guardar_file($_FILES,$_POST));
}
if(isset($_GET['cargar_pago']))
{
   echo json_encode($controlador->guardar_pago($_FILES,$_POST));
}
if(isset($_GET['ver_fac']))
{
  $controlador->ver_fac_pdf($_GET['codigo'],$_GET['ser'],$_GET['ci']);
}
if(isset($_GET['nueva_matricula']))
{
  $parametros = $_POST['usuario'];
  //print_r($parametros);
  echo json_encode($controlador->nueva_matricula($parametros));
}
if(isset($_GET['eliminar_pago']))
{
  $parametros = $_POST['parametros'];
  //print_r($parametros);
  echo json_encode($controlador->eliminar_pago($parametros));
}
class detalle_estudianteC
{
	private $modelo;
	private $empresa;
	private $est_d;
	private $empresaGeneral;
  private $email;
  private $sri;
  private $pdf;
	
	function __construct()
	{
		$this->modelo = new detalle_estudianteM();
		$this->pdf = new cabecera_pdf();		
		$this->empresa = $this->modelo->institucion_data();
		$this->empresaGeneral = $this->modelo->Empresa_data();
    $this->email = new enviar_emails();
    $this->sri = new autorizacion_sri();

	}

	function lista_cursos()
	{
		$datos =  $this->modelo->cargar_cursos();
    return $datos;// = array_map(array($this, 'encode'), $datos);
	}
	function validar_estudiante($usu,$pass,$nuevo)
	{
		$datos = $this->modelo->login($usu,$pass,$nuevo);
    if(!empty($datos)){
      return array_map(array($this, 'encode'), $datos);
    }else
    {
      return -2;
    }
	}

  function usu_regi($ci)
  {
    return $this->modelo->usuario_registrado($ci);
  }
   function usu_clave_regi($ci,$cla)
  {
    return $this->modelo->usuario_registrado_clave($ci,$cla);
  }
  function cliente_proveedor($ci)
  {
    $cambiar = $this->modelo->cliente_proveedor($ci);
    if(empty($cambiar))
    {
       $dato[0]['campo']='FA';
       $dato[0]['dato']=1;   

      $this->modelo->actualizar_datos($dato,'Clientes','Codigo',$ci);
      return 'si';

    }else
    {
     return 'no';
    }
  }

  function fact_emitidas($codigo)
  {
    $tbl = $this->modelo->facturas_emitidas_tabla($codigo);
    // print_r($tbl);
    return $tbl;
  }
	function encode($arr) {
	$new = array(); 
    foreach($arr as $key => $value) {
    	//echo is_array($value);
    	if(!is_object($value))
    	{
    	if($key=='Archivo_Foto')
    	{
         	if (!file_exists('../../img/img_estudiantes/'.$value)) 
         	{
         		$value='';
				$new[mb_convert_encoding($key, 'UTF-8')] = $value;
         	} 
         } 

         if($value == '.')
         {

         $new[mb_convert_encoding($key, 'UTF-8')] = '';
         }else{

          $new[mb_convert_encoding($key, 'UTF-8')] = $value;
         }
        }else
        {
          $new[mb_convert_encoding($key, 'UTF-8')] = $value->format('Y-m-d');        	
        }

     }
     return $new;
    }

  function guardar_foto($file,$post)
   {

   	$ruta="../../img/img_estudiantes/";//ruta carpeta donde queremos copiar las imágenes
   	if (!file_exists($ruta)) {
       mkdir($ruta, 0777, true);
    }
    if($file['file']['type']=="image/jpeg" || $file['file']['type']=="image/pjpeg" || $file['file']['type']=="image/gif" || $file['file']['type']=="image/png")
      {
   	     $uploadfile_temporal=$file['file']['tmp_name'];
   	     $tipo = explode('/', $file['file']['type']);
         $nombre = $post['codigo_foto'].'.'.$tipo[1];
        
   	     $nuevo_nom=$ruta.$nombre;
   	     if (is_uploaded_file($uploadfile_temporal))
   	     {
   		     move_uploaded_file($uploadfile_temporal,$nuevo_nom);
   		     $base = $this->modelo->img_guardar($nombre,$post['codigo']);
   		     if($base=='1')
   		     {
   		     	return 'ok';
   		     }else
   		     {
   		     	return '-1';
   		     }

   	     }
   	     else
   	     {
   		     return '-1';
   	     } 
     }else
     {
     	return '-2';
     }

  }

  function guardar_file($file,$post)
   {
     $formato = array('pdf','jpeg','jpg','gif','png');

   //	print_r($post);
   	$ruta="../vista/TEMP/";//ruta carpeta donde queremos copiar las imágenes

   	
    if($file['file']['type']=="application/pdf" || $file['file']['type']=="image/jpeg" || $file['file']['type']=="image/gif" || $file['file']['type']=="image/png")
      {
   	     $uploadfile_temporal=$file['file']['tmp_name'];
         $tipo = explode('/',$file['file']['type']);
   	     $nombre = $post['nom'].'_rep.'.$tipo[1];
         //print($nombre);
         foreach ($formato as $key => $value) {
          if(file_exists($ruta.$post['nom'].'_rep.'.$value))
          {
            rename ($ruta.$post['nom'].'_rep.'.$value, $ruta.$post['nom'].'_rep_'.date("Y-m-d").'.'.$value);
          }
         }
   	     $nuevo_nom=$ruta.$nombre;
   	     if (is_uploaded_file($uploadfile_temporal))
   	     {
   		     move_uploaded_file($uploadfile_temporal,$nuevo_nom);
   		    // $base = $this->modelo->img_guardar($nombre,$post['codigo']);
   		    return 1;

   	     }
   	     else
   	     {
   		     return -1;
   	     } 
     }else
     {
     	return -2;
     }

  }

  function eliminar_pago($parametros)
  {
    // print_r($parametros);die();
    SetAdoAddNew("Clientes_Datos_Extras");          
    SetAdoFields("Evidencias",'');

    SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);
    SetAdoFieldsWhere('Codigo',$parametros['usuario']);
    return SetAdoUpdateGeneric();

  }


  function guardar_pago($file,$post)
   {

    $ruta = dirname(__DIR__,2).'/comprobantes/pagos_subidos/entidad_'.$_SESSION['INGRESO']['IDEntidad'].'/empresa_'.$_SESSION['INGRESO']['item'].'/';
    if(!file_exists($ruta))
    {
      $ruta1 = dirname(__DIR__,2).'/comprobantes/pagos_subidos/';
      $ruta2 = dirname(__DIR__,2).'/comprobantes/pagos_subidos/entidad_'.$_SESSION['INGRESO']['IDEntidad'];
      mkdir($ruta1,0777);
      mkdir($ruta2,0777);      
      mkdir($ruta,0777);
    }
     $uploadfile_temporal=$file['file']['tmp_name'];
     $tipo = explode('/', $file['file']['type']);
     $nombre = $_SESSION['INGRESO']['item'].'_'.$post['nom_1'].'_pago.'.$tipo[1];
    // print_r($file);print_r($post);die();
     $nuevo_nom=$ruta.$nombre;

     if (is_uploaded_file($uploadfile_temporal))
     {
        move_uploaded_file($uploadfile_temporal,$nuevo_nom);
        SetAdoAddNew("Clientes_Datos_Extras");          
        SetAdoFields("Evidencias",$nombre);

        SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);
        SetAdoFieldsWhere('Codigo',$post['nom_1']);
        SetAdoUpdateGeneric();
        return 1;
     }


/*
     $formato = array('pdf','jpeg','jpg','gif','png');
   // print_r($post);
  //  $ruta="../vista/TEMP/".$_SESSION['INGRESO']['item']."_TEMP/".$_SESSION['INGRESO']['item'].'_PAGOS';//ruta carpeta donde queremos copiar las imágenes
     $ruta="../vista/TEMP/";
      if (!file_exists($ruta)) {
       mkdir($ruta, 0777, true);
    }    
    if($file['file']['type']=="application/pdf" || $file['file']['type']=="image/jpeg" || $file['file']['type']=="image/gif" || $file['file']['type']=="image/png")
      {
         $uploadfile_temporal=$file['file']['tmp_name'];
         $tipo = explode('/',$file['file']['type']);
         $nombre = $_SESSION['INGRESO']['item'].'_'.$post['nom_1'].'_pago.'.$tipo[1];
         //print($nombre);
         $nuevo_nom=$ruta.$nombre;
         foreach ($formato as $key => $value) {
          if(file_exists($ruta.$_SESSION['INGRESO']['item'].'_'.$post['nom_1'].'_pago.'.$value))
          {
            rename ($ruta.$_SESSION['INGRESO']['item'].'_'.$post['nom_1'].'_pago.'.$value, $ruta.$_SESSION['INGRESO']['item'].'_'.$post['nom_1'].'_pago_'.date("Y-m-d").'.'.$value);
          }
         }
         if (is_uploaded_file($uploadfile_temporal))
         {
           move_uploaded_file($uploadfile_temporal,$nuevo_nom);
          // $base = $this->modelo->img_guardar($nombre,$post['codigo']);
          return 1;

         }
         else
         {
           return -1;
         } 
     }else
     {
      return -2;
     }

     */

  }


  function codigo_matricula($curso)
  {
  	$numero = $this->modelo->codigo_matricula($curso);
  	 $codigo = substr($curso,0,-1);
  	 $resultado = str_replace(".", "", $codigo);
  	 //str_replace(search, replace, subject)
  	 $matri = $resultado.$numero;

  	 //print_r($matri);
  	return $matri;
  }

  function nuevo_estudiante($parametros){
    $datos =  Digito_verificador($parametros['codigo']);
    if($datos['Codigo_RUC_CI']!=2)
    {
      if(empty($this->usu_clave_regi($parametros['codigo'],$parametros['clave'])))
      {
        SetAdoAddNew('Clientes');
        SetAdoFields("Codigo", $datos['Codigo_RUC_CI']);
        SetAdoFields("CI_RUC", $this->sri->quitar_carac($parametros['codigo']));
        SetAdoFields("FA", true);
        SetAdoFields("Clave", $parametros['clave']);
        SetAdoFields("T", G_NORMAL);
        SetAdoFields("Item", $_SESSION['INGRESO']['item']);
        SetAdoFields("TD", $datos['Tipo_Beneficiario']);
        if(SetAdoUpdate()!=1){
          return "Error al registrar en Clientes";
        }
        Eliminar_Nulos_SP("Clientes");
      }

        SetAdoAddNew('Clientes_Matriculas');
        SetAdoFields("Codigo", $parametros['codigo']);
        SetAdoFields("FA", true);
        SetAdoFields("Clave", $parametros['clave']);
        SetAdoFields("T", G_NORMAL);
        SetAdoFields("Item", $_SESSION['INGRESO']['item']);
        SetAdoFields("TD", $datos['Tipo_Beneficiario']);
        if(SetAdoUpdate()!=1){
          return "Error al registrar en Clientes_Matriculas";
        }
        Eliminar_Nulos_SP("Clientes_Matriculas");
        return 'ok';
    }else
    {
      ob_end_clean();
      return 'ci';
    }

  }


  function actualizar_alumno($parametros)
  {
  	//print_r($parametros);
  	 $sexo = '';
  	 if($parametros['M']=='true')
  	 {
  	 	$sexo = 'M';
  	 }
  	 else
  	 {
  	 	$sexo = 'F';
  	 }

  	$datos[0]['campo']='Cliente';
  	$datos[0]['dato']=strtoupper($parametros['nombre']);
  	$datos[1]['campo']='Grupo';
  	$datos[1]['dato']=strtoupper($parametros['select_curso']);
  	$datos[2]['campo']='Sexo';
  	$datos[2]['dato']=strtoupper($sexo);
  	$datos[3]['campo']='Email';
  	$datos[3]['dato']=$parametros['email'];
  	$datos[4]['campo']='Prov';
  	$datos[4]['dato']=$parametros['provincia'];
  	$datos[5]['campo']='Ciudad';
  	$datos[5]['dato']=strtoupper($parametros['ciudad']);
  	$datos[6]['campo']='Fecha_N';
    $datos[6]['dato']=$parametros['fechan'];    
  	$datos[7]['campo']='Direccion';
    $datos[7]['dato']=strtoupper($parametros['nom_curso']);
  	$datos[8]['campo']='DireccionT';
    $datos[8]['dato']=strtoupper($parametros['dir_est']);
  	$datos[9]['campo']='Cedula';
    $datos[9]['dato']=$parametros['cedula'];

    $datos1[0]['campo']='Nacionalidad';
    $datos1[0]['dato']=strtoupper($parametros['nacionalidad']);
    $datos1[1]['campo']='Procedencia';
    $datos1[1]['dato']=strtoupper($parametros['procedencia']);
    $datos1[2]['campo']='Observaciones';
    $datos1[2]['dato']=strtoupper($parametros['observacion']);
    $datos1[3]['campo']='Grupo_No';
  	$datos1[3]['dato']=$parametros['select_curso'];
    $datos1[4]['campo']='Matricula_No';
  	$datos1[4]['dato']=$parametros['matricula_n'];
    $datos1[5]['campo']='Folio_No';
  	$datos1[5]['dato']=$parametros['tomo'];    
    $datos1[6]['campo']='Fecha_M';
    $datos1[6]['dato']=$parametros['fecha_m'];
    $datos1[7]['campo']='Fecha_N';
    $datos1[7]['dato']=$parametros['fechan'];      
  	
    //$datos[7]['campo']='Matricula';
  	//$datos[7]['dato']=$parametros['nombre_r'];  	
  	$this->modelo->actualizar_datos($datos,'Clientes','CI_RUC',$parametros['codigo']);
  	 return $this->modelo->actualizar_datos($datos1,'Clientes_Matriculas','Codigo',$parametros['codigo']);
  	// return $this->modelo-> actualizar_estudiante($parametros);
  }

  function actualizar_alumno_matricula($parametros)
  {
    //print_r($parametros);
     $sexo = '';
     if($parametros['M']=='true')
     {
      $sexo = 'M';
     }
     else
     {
      $sexo = 'F';
     }

     SetAdoAddNew("Clientes");          
     SetAdoFields("Cliente",strtoupper($parametros['nombre']));
     SetAdoFields("Grupo",strtoupper($parametros['select_curso']));
     SetAdoFields("Sexo",strtoupper($sexo));
     SetAdoFields("Email",$parametros['email']);
     SetAdoFields("Prov",$parametros['provincia']);
     SetAdoFields("Ciudad",strtoupper($parametros['ciudad']));
     SetAdoFields("Fecha_N",$parametros['fechan']);
     SetAdoFields("Direccion",strtoupper($parametros['nom_curso']));
     // SetAdoFields("DireccionT",strtoupper($parametros['dir_est']));
     SetAdoFields("Cedula",$parametros['cedula']);
     SetAdoFields("Ciudad",strtoupper($parametros['ciudad']));
     SetAdoFieldsWhere('CI_RUC',$parametros['codigo']);
     SetAdoUpdateGeneric();




    SetAdoAddNew("Clientes_Matriculas");          
    SetAdoFields("Nacionalidad",strtoupper($parametros['nacionalidad']));
    // SetAdoFields("Procedencia",strtoupper($parametros['procedencia']));
    // SetAdoFields("Observaciones",strtoupper($parametros['observacion']));
    SetAdoFields("Grupo_No",$parametros['select_curso']);
    // SetAdoFields("Matricula_No",$parametros['matricula_n']);
    // SetAdoFields("Folio_No",$parametros['tomo']);
    // SetAdoFields("Fecha_M",$parametros['fecha_m']);
    SetAdoFields("Fecha_N",$parametros['fechan']);

    SetAdoFieldsWhere('Codigo',$parametros['codigo']);
   return SetAdoUpdateGeneric();

  }

  function actualizar_repre($parametros)
  {
  	$datos[0]['campo']='Representante';
  	$datos[0]['dato']=strtoupper($parametros['nombre_r']);
  	$datos[1]['campo']='CI_R';
  	$datos[1]['dato']=$parametros['ci_r'];
  	$datos[2]['campo']='Profesion_R';
  	$datos[2]['dato']=strtoupper($parametros['profesion_r']);
  	$datos[3]['campo']='Lugar_Trabajo_R';
  	$datos[3]['dato']=strtoupper($parametros['trabajo_r']);
  	$datos[4]['campo']='Ocupacion_R';
  	$datos[4]['dato']=strtoupper($parametros['ocupacion_r']);
  	$datos[5]['campo']='Email_R';
    $datos[5]['dato']=$parametros['email_r'];
    $datos[6]['campo']='Representante_Alumno';
  	$datos[6]['dato']=strtoupper($parametros['nombre_r']);
    $datos[7]['campo']='Telefono_RS';
    $datos[7]['dato']=$parametros['celular_r'];

    $datos1[0]['campo']='Telefono_R';
    $datos1[0]['dato']=$parametros['telefono_r'];
    $datos1[1]['campo']='Email2';
    $datos1[1]['dato']=$parametros['email_fac_r'];
    

     $this->modelo->actualizar_datos($datos1,'Clientes','CI_RUC',$parametros['codigo']);
     Eliminar_Nulos_SP("Clientes");
   	 $rps = $this->modelo->actualizar_datos($datos,'Clientes_Matriculas','Codigo',$parametros['codigo']);
     Eliminar_Nulos_SP("Clientes_Matriculas");
     return $rps;
    // return $this->modelo->actualizar_datos($datos1,'Clientes','Codigo',$parametros['codigo']);

  }
   function actualizar_fami($parametros)
  {
  	$datos[0]['campo']='Nombre_Padre';
  	$datos[0]['dato']=strtoupper($parametros['nombre_p']);
  	$datos[1]['campo']='Nacionalidad_P';
  	$datos[1]['dato']=strtoupper($parametros['nacionalidad_p']);
  	$datos[2]['campo']='Profesion_P';
  	$datos[2]['dato']=strtoupper($parametros['profesion_p']);
  	$datos[3]['campo']='Lugar_Trabajo_P';
  	$datos[3]['dato']=strtoupper($parametros['trabajo_p']);
  	$datos[4]['campo']='Telefono_Trabajo_P';
  	$datos[4]['dato']=$parametros['telefono_p'];
  	$datos[5]['campo']='CI_P';
  	$datos[5]['dato']=$parametros['ci_p'];
  	$datos[6]['campo']='Celular_P';
  	$datos[6]['dato']=$parametros['celular_p'];
  	$datos[7]['campo']='Nombre_Madre';
  	$datos[7]['dato']=strtoupper($parametros['nombre_m']);
  	$datos[8]['campo']='Nacionalidad_M';
  	$datos[8]['dato']=strtoupper($parametros['nacionalidad_m']);
  	$datos[9]['campo']='Profesion_M';
  	$datos[9]['dato']=strtoupper($parametros['profesion_m']);
  	$datos[10]['campo']='Lugar_Trabajo_M';
  	$datos[10]['dato']=strtoupper($parametros['trabajo_m']);
  	$datos[11]['campo']='Telefono_Trabajo_M';
  	$datos[11]['dato']=$parametros['telefono_m'];
  	$datos[12]['campo']='CI_M';
  	$datos[12]['dato']=$parametros['ci_m'];
  	$datos[13]['campo']='Celular_M';
  	$datos[13]['dato']=$parametros['celular_m'];
  	$datos[14]['campo']='Email_M';
  	$datos[14]['dato']=$parametros['email_m'];
  	$datos[15]['campo']='Email_P';
  	$datos[15]['dato']=$parametros['email_p'];
    $datos[16]['campo']='Ocupacion_P';
    $datos[16]['dato']=strtoupper($parametros['ocupacion_p']);
    $datos[17]['campo']='Ocupacion_M';
    $datos[17]['dato']=strtoupper($parametros['ocupacion_m']);
    $rps = $this->modelo->actualizar_datos($datos,'Clientes_Matriculas','Codigo',$parametros['codigo']);
    Eliminar_Nulos_SP("Clientes_Matriculas");
    return  $rps;
  }

  function generar_pdf_matricula($usu,$pass,$descargar=false)
  {

    $fecha = new DateTime();
    $fecha->setTimezone(new DateTimeZone('America/Guayaquil'));
    setlocale(LC_ALL, 'es_ES.utf8');
    $fecha = $fecha->format('l d \d\e F \d\e\l Y');
    $datos = $this->modelo->login($usu,$pass,false);


    $titulo = 'REGISTRO DE MATRICULA CON REPRESENTANTE';
    $name_doc = str_replace(' ','_', $titulo).'_'.$datos[0]['CI_RUC'];
    $sizetable =10;
    $mostrar = true;

    $tablaHTML= array();

    $url = dirname(__DIR__,2).'/img/img_estudiantes/'.$datos[0]['Archivo_Foto'];
    if(!file_exists($url))
    {
      $url = dirname(__DIR__,2).'/img/img_estudiantes/SINFOTO.jpg';
    }

     $image[] = array('url'=>$url,'x'=>10,'y'=>55,'width'=>50,'height'=>50);
     $url_qr = 
     $qr =array('dato_qr'=>'.','name_qr'=>$datos[0]['CI_RUC'],'x'=>5,'y'=>100,'width'=>60,'height'=>60);

    

    $tablaHTML[0]['medidas']=array(125);
    $tablaHTML[0]['alineado']=array('C');
    $tablaHTML[0]['datos']=array('<b>DATOS PERSONALES');
    $tablaHTML[0]['ML'] =65;
    // $tablaHTML[0]['borde'] =1;

    $tablaHTML[1]['medidas']=array(125);
    $tablaHTML[1]['alineado']=array('L');
    $tablaHTML[1]['datos']=array('<b>APELLIDOS Y NOMBRES');
    $tablaHTML[1]['borde'] =1;
    $tablaHTML[1]['ML'] =65;

    $tablaHTML[2]['medidas']=array(125);
    $tablaHTML[2]['alineado']=array('L');
    $tablaHTML[2]['datos']=array($datos[0]['Cliente']);
    $tablaHTML[2]['borde'] =1;
    $tablaHTML[2]['ML'] =65;

    $tablaHTML[3]['medidas']=array(125);
    $tablaHTML[3]['alineado']=array('L');
    $tablaHTML[3]['datos']=array('<b>CEDULA / RUC');
    $tablaHTML[3]['borde'] =1;
    $tablaHTML[3]['ML'] =65;

    $tablaHTML[4]['medidas']=array(125);
    $tablaHTML[4]['alineado']=array('L');
    $tablaHTML[4]['datos']=array($datos[0]['CI_RUC']);
    $tablaHTML[4]['borde'] =1;
    $tablaHTML[4]['ML'] =65;

    $tablaHTML[5]['medidas']=array(63,62);
    $tablaHTML[5]['alineado']=array('L','L');
    $tablaHTML[5]['datos']=array('<b>FECHA DE NACIMIENTO','<b>SEXO');
    $tablaHTML[5]['borde'] =1;
    $tablaHTML[5]['ML'] =65;

    $sexo = 'MASCULINO';
    if($datos[0]['Sexo']=='F'){$sexo = 'FEMENINO';}

    $tablaHTML[6]['medidas']=array(63,62);
    $tablaHTML[6]['alineado']=array('L','L');
    $tablaHTML[6]['datos']=array($datos[0]['Fecha_N']->format("Y-m-d"),$sexo);
    $tablaHTML[6]['borde'] =1;
    $tablaHTML[6]['ML'] =65;

    $tablaHTML[7]['medidas']=array(42,42,41);
    $tablaHTML[7]['alineado']=array('L','L','L');
    $tablaHTML[7]['datos']=array('<b>NACIONALIDAD','<b>PROVINCIA','<b>CIUDAD');
    $tablaHTML[7]['borde'] =1;
    $tablaHTML[7]['ML'] =65;

// print_r($datos[0]);die();
    $prov = '.';
    if($datos[0]['Prov']!='.' && $datos[0]['Prov']!='')
    {
      $prov = provincia_todas('593',$datos[0]['Prov']);
      $prov = $prov[0]['Descripcion_Rubro'];
    }

    $tablaHTML[8]['medidas']=array(42,42,41);
    $tablaHTML[8]['alineado']=array('L','L','L');
    $tablaHTML[8]['datos']=array($datos[0]['Nacionalidad'],$prov,$datos[0]['Ciudad']);
    $tablaHTML[8]['borde'] =1;
    $tablaHTML[8]['ML'] =65;

    $tablaHTML[9]['medidas']=array(125);
    $tablaHTML[9]['alineado']=array('L');
    $tablaHTML[9]['datos']=array('<b>EMAIL');
    $tablaHTML[9]['borde'] =1;
    $tablaHTML[9]['ML'] =65;

    $tablaHTML[10]['medidas']=array(125);
    $tablaHTML[10]['alineado']=array('L','L','L');
    $tablaHTML[10]['datos']=array($datos[0]['Email']);
    $tablaHTML[10]['borde'] =1;
    $tablaHTML[10]['ML'] =65;

    $tablaHTML[11]['medidas']=array(125);
    $tablaHTML[11]['alineado']=array('C');
    $tablaHTML[11]['datos']=array('<b>DATOS REPRESENTANTE');
    $tablaHTML[11]['ML'] =65;
    // $tablaHTML[11]['borde'] =1;

    $tablaHTML[12]['medidas']=array(125);
    $tablaHTML[12]['alineado']=array('L');
    $tablaHTML[12]['datos']=array('<b>APELLIDOS Y NOMBRES');
    $tablaHTML[12]['borde'] =1;
    $tablaHTML[12]['ML'] =65;

    $tablaHTML[13]['medidas']=array(125);
    $tablaHTML[13]['alineado']=array('L','L','L');
    $tablaHTML[13]['datos']=array($datos[0]['Representante_Alumno']);
    $tablaHTML[13]['borde'] =1;
    $tablaHTML[13]['ML'] =65;

    $tablaHTML[14]['medidas']=array(125);
    $tablaHTML[14]['alineado']=array('L');
    $tablaHTML[14]['datos']=array('<b>CEDULA DE IDENTIDAD');
    $tablaHTML[14]['borde'] =1;
    $tablaHTML[14]['ML'] =65;

    $tablaHTML[15]['medidas']=array(125);
    $tablaHTML[15]['alineado']=array('L','L','L');
    $tablaHTML[15]['datos']=array($datos[0]['CI_R']);
    $tablaHTML[15]['borde'] =1;
    $tablaHTML[15]['ML'] =65;

    $tablaHTML[16]['medidas']=array(63,62);
    $tablaHTML[16]['alineado']=array('L','L');
    $tablaHTML[16]['datos']=array('<b>TELEFONO','<b>CELULAR');
    $tablaHTML[16]['borde'] =1;
    $tablaHTML[16]['ML'] =65;

    $tablaHTML[17]['medidas']=array(63,62);
    $tablaHTML[17]['alineado']=array('L','L');
    $tablaHTML[17]['datos']=array($datos[0]['Telefono_RS'],$sexo);
    $tablaHTML[17]['borde'] =1;
    $tablaHTML[17]['ML'] =65;


    $tablaHTML[18]['medidas']=array(125);
    $tablaHTML[18]['alineado']=array('L');
    $tablaHTML[18]['datos']=array('<b>DIRECCION DE LA FACTURA');
    $tablaHTML[18]['borde'] =1;
    $tablaHTML[18]['ML'] =65;

    $tablaHTML[19]['medidas']=array(125);
    $tablaHTML[19]['alineado']=array('L','L','L');
    $tablaHTML[19]['datos']=array($datos[0]['Lugar_Trabajo_R']);
    $tablaHTML[19]['borde'] =1;
    $tablaHTML[19]['ML'] =65;

    $tablaHTML[20]['medidas']=array(63,62);
    $tablaHTML[20]['alineado']=array('L','L');
    $tablaHTML[20]['datos']=array('<b>EMAIL 1','<b>EMAIL 2');
    $tablaHTML[20]['borde'] =1;
    $tablaHTML[20]['ML'] =65;


    $tablaHTML[21]['medidas']=array(63,62);
    $tablaHTML[21]['alineado']=array('L','L');
    $tablaHTML[21]['datos']=array($datos[0]['Email2'],$datos[0]['Email_R']);
    $tablaHTML[21]['borde'] =1;    
    $tablaHTML[21]['ML'] =65;


    $tablaHTML[22]['medidas']=array(73,73);
    $tablaHTML[22]['alineado']=array('C','C');
    $tablaHTML[22]['datos']=array('____________________________________','___________________________________');
    // $tablaHTML[22]['borde'] =1;     
    $tablaHTML[22]['ML'] =40;   
    $tablaHTML[22]['MT'] =100;

    $tablaHTML[23]['medidas']=array(73,73);
    $tablaHTML[23]['alineado']=array('C','C');
    $tablaHTML[23]['datos']=array($this->empresa[0]['Rector'],$this->empresa[0]['Secretario1']);
    // $tablaHTML[22]['borde'] =1;     
    $tablaHTML[23]['ML'] =40;   

    $tablaHTML[24]['medidas']=array(73,73);
    $tablaHTML[24]['alineado']=array('C','C');
    $tablaHTML[24]['datos']=array('<b>'.$this->empresa[0]['Texto_Rector'],'<b>'.$this->empresa[0]['Texto_Secretario1']);
    // $tablaHTML[22]['borde'] =1;     
    $tablaHTML[24]['ML'] =40;  

// print_r($datos);die();
// print_r($mostrar.'--'.$descargar);die();
    if($descargar){$descargar = true;}else{$descargar=false;}
   return $this->pdf->cabecera_reporte_colegio_matricula($titulo,$tablaHTML,$name_doc,$contenido=false,$image,$qr,false,false,$sizetable,$mostrar,10,false,$descargar);
  }

  function enviar_email_evidencia_matricula($parametros)
  {
     $empresaGeneral = $this->empresaGeneral;
      $titulo_correo = 'Registro de matricula';
      $datos = $this->modelo->login($parametros['usuario'],$parametros['password'],'false');


      $titulo = 'REGISTRO DE MATRICULA CON REPRESENTANTE';
      $name_doc = str_replace(' ','_', $titulo).'_'.$datos[0]['CI_RUC'];

      $cliente =$datos[0]['Cliente'];
      $cuerpo_correo = 'Registro de matricula '.$cliente;
      $to_correo = $empresaGeneral[0]['Email_Contabilidad'].','.$datos[0]['Email2'].','.$datos[0]['Email_R'];
      $archivos=array();
      if(file_exists(dirname(__DIR__,3).'/TEMP/'.$name_doc.'.pdf'))
      {
        $archivos[] = dirname(__DIR__,3).'/TEMP/'.$name_doc.'.pdf';
      }
      return  enviar_email_comprobantes($archivos, $to_correo, $cuerpo_correo, $titulo_correo, $HTML = false);
  }

  function generar_pdf($usu,$pass,$nuevo,$email)
  {

    $fecha = new DateTime();
    $fecha->setTimezone(new DateTimeZone('America/Guayaquil'));
    setlocale(LC_ALL, 'es_ES.utf8');
    $fecha = $fecha->format('l d \d\e F \d\e\l Y');

  	$datos = $this->modelo->login($usu,$pass,$nuevo);

    // print_r($datos);die();
    $genero = 'la';
    $genero1 = 'a';
    if($datos[0]['Sexo']== 'M')
    {
    $genero = 'el';
    $genero1 = 'o';
    }
  	$tablaHtml='<table><tr><td></td></tr><tr><td><td></tr></table>';
  	$texto = 'La '.mb_convert_encoding($this->empresa[0]['Institucion1'], 'ISO-8859-1','UTF-8').' '.mb_convert_encoding($this->empresa[0]['Institucion2'], 'ISO-8859-1','UTF-8').' De Conformidad con el Reglamento a la Ley Orgánica de Educación Intercultural, registra la matricula  de '.$genero.' estudiante:';
  	$texto2 ='El infrascrito, representante de '.$genero.' estudiante matriculad'.$genero1.', declara que se encuentra conforme con los datos que <br>anteceden y firma sometiéndose a las disposiciones del citado reglamento.<br>Lugar y Fecha: '.mb_convert_encoding($fecha, 'UTF-8');
  	//print_r($datos[0]['Fecha_N']->format('Y-m-d'));
     $nombre = $datos[0]['Cliente'];
     $nom_ma = '&nbsp;';
     if($datos[0]['Nombre_Madre'] != '.' && $datos[0]['Nombre_Madre'] != '' )
     {
      $nom_ma = $datos[0]['Nombre_Madre'];
     }
      $na_ma = '&nbsp;';
     if($datos[0]['Nacionalidad_M'] != '.' && $datos[0]['Nacionalidad_M'] != '' )
     {
      $na_ma = $datos[0]['Nacionalidad_M'];
     }
      $nom_pa = '&nbsp;';
     if($datos[0]['Nombre_Padre'] != '.' && $datos[0]['Nombre_Padre'] != '' )
     {
      $nom_pa = $datos[0]['Nombre_Padre'];
     }
      $na_pa = '&nbsp;';
     if($datos[0]['Nacionalidad_P'] != '.' && $datos[0]['Nacionalidad_P'] != '' )
     {
      $na_pa = $datos[0]['Nacionalidad_P'];
     }
       $nom_re = '&nbsp;';
     if($datos[0]['Representante_Alumno'] != '.' && $datos[0]['Representante_Alumno'] != '' )
     {
      $nom_re = $datos[0]['Representante_Alumno'];
     }
	$tablaHtml.='<table>
	<tr>
		<td width="380"><b><u>'.$datos[0]['Direccion'].'</u></b></td><td  width="380"><b>'.$datos[0]['Curso_Superior'].'</b></td>
	</tr>
	<tr><td width="170"><b>Lugar de nacimiento:</b></td><td width="350">'.$datos[0]['Ciudad'].'</td><td width="70"><b>Fecha</b></td><td width="170">'.$datos[0]['Fecha_N']->format('Y-m-d').'</td></tr>
	<tr><td width="170"><b>Direccion domicilio:</b></td><td width="590">'.$datos[0]['DireccionT'].'</td></tr>
	<tr><td width="170"><b>Telefono</b></td><td width="300">'.$datos[0]['Telefono'].'</td><td width="110"><b>Nacionalidad:</b></td><td width="180">'.$datos[0]['Nacionalidad'].'</td></tr>
	<tr><td width="170"><b>Plantel que proviene:</b></td><td width="590">'.$datos[0]['Procedencia'].'</td></tr>
	<tr><td width="760">&nbsp;</td></tr>
	<tr><td width="760"><b>DATOS DEL PADRE</b></td></tr>
	<tr><td width="110"><b>Nombre:</b></td><td width="295">'.$nom_pa.'</td><td width="90"><b>Telefono:</b></td><td width="265">'.$datos[0]['Celular_P'].'</td></tr><tr><td width="110"><b>Nacionalidad:</b></td><td width="295">'.$na_pa.'</td><td  width="90"><b>Profesion:</b></td><td width="300">'.$datos[0]['Profesion_P'].'</td></tr><tr><td  width="110"><b>&nbsp;</b></td><td width="295">&nbsp;</td><td width="90"><b>Ocupacion:</b></td><td width="300">'.$datos[0]['Ocupacion_P'].'</td></tr>
	<tr><td width="760">&nbsp;</td></tr>
	<tr><td width="760"><b>DATOS DE LA MADRE</b></td></tr>
	<tr><td width="110"><b>Nombre:</b></td><td width="295">'.$nom_ma.'</td><td width="90"><b>Telefono:</b></td><td width="300">'.$datos[0]['Celular_M'].'</td></tr><tr><td width="110"><b>Nacionalidad:</b></td><td width="295">'.$na_ma.'</td><td width="90"><b>Profesion:</b></td><td width="300">'.$datos[0]['Profesion_M'].'</td></tr><tr><td width="110"><b>&nbsp;</b></td><td width="295">&nbsp;</td><td width="90"><b>Ocupacion:</b></td><td width="300">'.$datos[0]['Ocupacion_M'].'</td></tr>
	<tr><td width="760">&nbsp;</td></tr>
	<tr><td width="760"><b>DATOS DEL REPRESENTANTE</b></td></tr>
	<tr><td width="110"><b>Nombre:</b></td><td width="295">'.$nom_re.'</td><td width="90"><b>Telefono:</b></td><td width="180">'.$datos[0]['Telefono_RS'].'</td></tr><tr><td height="" width="110"><b>&nbsp;</b></td><td width="295">&nbsp;</td><td width="90"><b>Profesion:</b></td><td width="300">'.$datos[0]['Profesion_R'].'</td></tr><tr><td width="110"><b>&nbsp;</b></td><td width="295">&nbsp;</td><td width="90"><b>Ocupacion:</b></td><td width="300">'.$datos[0]['Ocupacion_R'].'</td></tr>
</table>
<table>
    <tr>
		<td height="10" width="750">'.$texto2.'</td>
		
	</tr>
    <tr>
		<td height="300" width="380">&nbsp;</td>
		<td height="300" width="380">&nbsp;</td>
	</tr>
	<tr>
		<td height="" width="380" ALIGN="CENTER">---------------------------------------------</td>
		<td height="" width="380" ALIGN="CENTER">---------------------------------------------</td>
	</tr>
	<tr>
		<td height="" width="380" ALIGN="CENTER">REPRESENTANTE</td>
		<td height="" width="380" ALIGN="CENTER">'.$this->empresa[0]['Texto_Secretario1'].'</td>
	</tr>
</table>';

		// contenido solo tiene 2 opciones para u posicion top-tabla y button-tabla 
		// si coloca una tabla este saldra en la mitad de texto,titulo dependiendo la posicion
		// tipo = texto-titulo
		//posicion = button-table  / top-table
        $image=false;
		$contenido[0]['tipo'] ='texto';
		$contenido[0]['valor'] = mb_convert_encoding($texto, 'ISO-8859-1','UTF-8');
		$contenido[0]['posicion'] ='top-tabla';
		$contenido[1]['tipo'] ='titulo';
		$contenido[1]['valor'] = mb_convert_encoding($nombre, 'ISO-8859-1','UTF-8');
		$contenido[1]['posicion'] ='top-tabla';
		


		$this->pdf->cabecera_reporte_colegio($_SESSION['INGRESO']['item'].'_ACTA_DE_MATRICULA_'.$usu,'ACTA DE MATRICULA',mb_convert_encoding($tablaHtml, 'ISO-8859-1','UTF-8'),$contenido,$image,'','',8,false,$email);
		//$this->pdf_registro($usu,$pass,$nuevo);
		//$this->pdf_matricula($usu,$pass,$nuevo);
		
  }

  function pdf_registro($usu,$pass,$nuevo,$email)
  {
    $contenido=false;

    $fecha = new DateTime();
    $fecha->setTimezone(new DateTimeZone('America/Guayaquil'));
    setlocale(LC_ALL, 'es_ES.utf8');
    $fecha = $fecha->format('l d \d\e F \d\e\l Y');
  	$datos = $this->modelo->login($usu,$pass,$nuevo);
  	$tablaHtml='<table>
	<tr><td width="350" ALIGN="RIGHT"><b>EL ALUMNO(A):</b></td><td width="350">'.strtoupper($datos[0]['Cliente']).'</td></tr>
	<tr><td height="150" width="150"><b>CURSO</b></td><td height="150"  width="500">'.$datos[0]['Direccion'].'</td></tr>
	<tr><td height="150" width="150"><b>CICLO</b></td><td height="150">'.$this->empresa[0]['Anio_Lectivo'].'</td></tr>
	<tr><td height="150" width="150"><b>Nivel de estudio</b></td><td height="150">'.mb_convert_encoding($datos[0]['Curso_Superior'], 'UTF-8').'</td></tr>
	<tr><td height="150" width="150"><b>MATRICULA No</b></td><td height="150">'.$datos[0]['Matricula_No'].'</td></tr>
	<tr><td height="150" width="150"><b>FOLIO No</b></td><td height="150">'.$datos[0]['Folio_No'].'</td></tr>
	<tr><td height="150" width="300">'.mb_convert_encoding($fecha, 'UTF-8').'</td></tr>
</table>
<table>
	<tr><td height="160" width="350">&nbsp;</td><td height="160" width="350">&nbsp;</td></tr>
	<tr><td height="20" width="350" ALIGN="CENTER">______________________________</td><td height="20" width="350" ALIGN="CENTER">______________________________</td></tr>
	<tr><td height="20" width="350"  ALIGN="CENTER"><I>'.$this->empresa[0]['Rector'].'</I></td><td height="20" width="350"  ALIGN="CENTER"><I>'.$this->empresa[0]['Secretario1'].'<I></td></tr>
	<tr><td height="20" width="350"  ALIGN="CENTER"><b>'.$this->empresa[0]['Texto_Rector'].'</b></td><td height="20" width="350"  ALIGN="CENTER"><B>'.$this->empresa[0]['Texto_Secretario1'].'</B></td></tr>
	<tr><td height="150">&nbsp;</td></tr>
	<tr><td width="700">NOTA:DOCUMENTO NO VALIDO SIN FIRMA Y SELLO DE LA INSTITUCION</td></tr>
</table>';
if($datos[0]['Archivo_Foto'] !='.' && $datos[0]['Archivo_Foto'] !='')
{
    if (!file_exists('../../img/img_estudiantes/'.$datos[0]['Archivo_Foto'])) 
    {
    	$url='../../../img/jpg/sinimagen.jpg';
    }else
    {
    	$url='../../img/img_estudiantes/'.$datos[0]['Archivo_Foto'];
    }
  }else
   {
    	$url='../../../img/jpg/sinimagen.jpg';

   }

		$image[0]['url']=$url;
		$image[0]['x']=150;
		$image[0]['y']= 60;
		$image[0]['width']=40;
		$image[0]['height']=40;

  	$this->pdf->cabecera_reporte_colegio($_SESSION['INGRESO']['item'].'_HOJA_DE_REGISTRO_'.$usu,'HOJA DE REGISTRO',mb_convert_encoding($tablaHtml, 'ISO-8859-1','UTF-8'),$contenido,$image,'','',10,false,$email);


  }

  function pdf_matricula($usu,$pass,$nuevo,$email)
  {
  	$contenido=false;
  	$image = null;
  	$datos = $this->modelo->login($usu,$pass,$nuevo);
  
  	$tablaHtml='<table>
	<tr>
		<td width="750"><b><u>REGISTRO DE MATRICULA</u></b></td>
	</tr>
	<tr><td width="200" height=""><b>MATRICULA No</b></td><td width="550">'.$datos[0]['Matricula_No'].'</td></tr>
	<tr><td width="200" height=""><b>Folio No</b></td><td width="550">'.$datos[0]['Folio_No'].'</td></tr>
	<tr><td width="200" height=""><b>FECHA DE MATRICULA</b></td><td width="550">'.$datos[0]['Fecha_M']->format("Y-m-d").'</td></tr><tr><td width="200" height=""><b>NACIONALIDAD</b></td><td width="550">'.$datos[0]['Nacionalidad'].'</td></tr>';
  if($datos[0]['Especialidad'] != '.')
  {
    $tablaHtml.='<tr><td width="200" height=""><b>ESPECIALIDAD</b></td><td width="550">'.$datos[0]['Especialidad'].'</td></tr>';
  }	
	$tablaHtml.='<tr><td width="200" height=""><b>AÑO</b></td><td width="550">'.$this->empresa[0]['Anio_Lectivo'].'</td></tr>
	<tr><td width="200" height=""><b>SECCION</b></td><td width="550">'.$datos[0]['Seccion'].'</td></tr>
	<tr><td width="200" height=""><b>NIVEL DE ESTUDIO</b></td><td width="550">'.$datos[0]['Curso_Superior'].'</td></tr>
	<tr><td width="200" height=""><b>CURSO - GRADO</b></td><td width="550">'.mb_convert_encoding($datos[0]['Grupo'], 'UTF-8').' '.$datos[0]['Direccion'].'</td></tr>
	<tr><td height="20" width="550">&nbsp;</td></tr>
	<tr><td width="200" height=""><b><u>DATOS PERSONALES</u></b></td><td width="" height=""></td></tr>
	<tr><td width="200" height=""><b>NOMBRES Y APELLIDOS</b></td><td width="550">'.$datos[0]['Cliente'].'</td></tr>
	<tr><td width="200" height=""><b>CEDULA</b></td><td width="550">'.$datos[0]['CI_RUC'].'</td></tr>
	<tr><td width="200" height=""><b>LUGAR DE NACIMIENTO</b></td><td width="550">'.$datos[0]['Ciudad'].'</td></tr>
	<tr><td width="200" height=""><b>FECHA DE NACIMIENTO</b></td><td width="550">'.$datos[0]['Fecha_N']->format("Y-m-d").'</td></tr>
	<tr><td width="200" height=""><b>DOMICILIO</b></td><td width="550" >'.$datos[0]['DireccionT'].'</td></tr>
	<tr><td width="200" height=""><b>TELEFONO</b></td><td width="550">'.$datos[0]['Telefono'].'</td></tr>
	<tr><td height="20" width="550">&nbsp;</td></tr>
	<tr><td><b><u>DATOS FAMILIARES</u></b></td><td width="" height=""></td></tr>
	<tr><td width="200" height=""><b>NOMBRES DEL PADRE</b></td><td width="550">'.$datos[0]['Nombre_Padre'].'</td></tr>
	<tr><td width="200" height=""><b>NACIONALIDAD</b></td><td width="550">'.$datos[0]['Nacionalidad_P'].'</td></tr>
	<tr><td width="200" height=""><b>PROFESION</b></td><td width="550">'.$datos[0]['Profesion_P'].'</td></tr>
	<tr><td width="200" height=""><b>LUGAR DE TRABAJO</b></td><td  width="550" >'.$datos[0]['Lugar_Trabajo_P'].'</td></tr>
	<tr><td width="200" height=""><b>TELEFONO</b></td><td width="550" >'.$datos[0]['Celular_P'].'</td></tr>
	<tr><td height="20" width="550">&nbsp;</td></tr>
	<tr><td width="200" height=""><b>NOMBRES DEL MADRE</b></td><td width="550">'.$datos[0]['Nombre_Madre'].'</td></tr>
	<tr><td width="200" height=""><b>NACIONALIDAD</b></td><td width="550">'.$datos[0]['Nacionalidad_M'].'</td></tr>
	<tr><td width="200" height=""><b>PROFESION</b></td><td width="550">'.$datos[0]['Profesion_M'].'</td></tr>
	<tr><td width="200" height=""><b>LUGAR DE TRABAJO</b></td><td  width="550">'.$datos[0]['Lugar_Trabajo_M'].'</td></tr>
	<tr><td width="200" height=""><b>TELEFONO</b></td><td width="550">'.$datos[0]['Celular_M'].'</td></tr>
	<tr><td height="20">&nbsp;</td></tr>
	<tr><td width="200" height=""><b>REPRESENTANTE</b></td><td width="550">'.$datos[0]['Representante_Alumno'].'</td></tr>
	<tr><td width="200" height=""><b>CEDULA DE IDENTIDAD</b></td><td width="550">'.$datos[0]['CI_R'].'</td></tr>
	<tr><td width="200" height=""><b>TELEFONO</b></td><td width="550">'.$datos[0]['Telefono_RS'].'</td></tr><tr><td width="200" height=""><b>Email</b></td><td width="550">'.$datos[0]['Email2'].'</td></tr> 
</table>
<table>
	<tr><td height="30" width="350">&nbsp;</td><td height="30" width="350">&nbsp;</td></tr>
	<tr><td height="20" width="350" ALIGN="CENTER">______________________________</td><td height="20" width="350" ALIGN="CENTER">______________________________</td></tr>
	<tr><td height="20" width="350"  ALIGN="CENTER"><I>'.$this->empresa[0]['Rector'].'</I></td><td height="20" width="350"  ALIGN="CENTER"><I>'.$this->empresa[0]['Secretario1'].'<I></td></tr>
	<tr><td height="20" width="350"  ALIGN="CENTER"><b>'.$this->empresa[0]['Texto_Rector'].'</b></td><td height="20" width="350"  ALIGN="CENTER"><B>'.$this->empresa[0]['Texto_Secretario1'].'</B></td></tr>
	<tr><td height="25">&nbsp;</td></tr><tr><td height="20" width="700" ALIGN="CENTER">______________________________</td></tr><tr><td height="25" width="700"  ALIGN="CENTER"><I>Representante Legal</I></td></tr>
	<tr><td width="750">NOTA:DOCUMENTO NO VALIDO SIN FIRMA Y SELLO DE LA INSTITUCION</td></tr>
</table>';
if($datos[0]['Archivo_Foto'] !='.' && $datos[0]['Archivo_Foto'] !='')
{
if (!file_exists('../../img/img_estudiantes/'.$datos[0]['Archivo_Foto'])) 
    {
    	$url='../../../img/jpg/sinimagen.jpg';
    }else
    {
    	$url='../../img/img_estudiantes/'.$datos[0]['Archivo_Foto'];
    }
   }else
   {
    	$url='../../../img/jpg/sinimagen.jpg';

   }
    $image[0]['url']=$url;
		$image[0]['x']=150;
		$image[0]['y']= 50;
		$image[0]['width']=40;
		$image[0]['height']=40;

    $this->pdf->cabecera_reporte_colegio($_SESSION['INGRESO']['item'].'_HOJA_DE_MATRICULA_'.$usu,'HOJA DE MATRICULA',mb_convert_encoding($tablaHtml, 'ISO-8859-1','UTF-8'),$contenido,$image,'','',10,false,$email);

  }

   function exportar_Excel($codigo,$perido,$tc)
  {
    $reporte_Excel = true;
    $this->modelo->facturas_emitidas_excel($codigo,$reporte_Excel,$perido,$tc);
  }

  function exportar_pdf($codigo)
  {
    $contenido=false;
    $image = false;
    $email = 'false';
    $mostrar = true;
    $reporte_Excel=false;
    $datos = $this->modelo->facturas_emitidas_excel($codigo,$reporte_Excel);
  
    $tablaHtml='<table>
    <tr><td width="20"><b><u>T</u></b></td><td width="20"><b><u>TC</u></b></td><td width="45"><b><u>Serie</u></b></td><td width="70"><b><u>Autorizacion</u></b></td><td width="50"><b><u>Factura</u></b></td><td width="55"><b><u>Fecha</u></b></td><td width="50"><b><u>SubTotal</u></b></td><td width="50"><b><u>Con Iva</u></b></td><td width="40"><b><u>IVA</u></b></td><td width="40"><b><u>Total</u></b></td><td width="40"><b><u>Saldo</u></b></td><td width="75" ><b><u>Ruc</u></b></td><td width="20"><b><u>TB</u></b></td><td width="190"><b><u>Razon social</u></b></td></tr>
    </table>';
    foreach ($datos as $key => $value) {
      $tablaHtml.='<table border="RIGHT"><tr><td width="20">'.$value['T'].'</td><td  width="20">'.$value['TC'].'</td><td  width="45">'.$value['Serie'].'</td><td width="70">'.$value['Autorizacion'].'</td><td width="50">'.$value['Factura'].'</td><td width="55">'.$value['Fecha']->format('Y-m-d').'</td><td width="50"  ALIGN="RIGHT">'.$value['SubTotal'].'</td><td width="50"  ALIGN="RIGHT">'.$value['Con_IVA'].'</td><td width="40"  ALIGN="RIGHT">'.$value['IVA'].'</td><td width="40"  ALIGN="RIGHT">'.$value['Total'].'</td><td width="40"  ALIGN="RIGHT">'.$value['Saldo'].'</td><td width="75">'.$value['RUC_CI'].'</td><td  width="20">'.$value['TB'].'</td><td width="190">'.$value['Razon_Social'].'</td></tr>
    </table>';
    }
   // $tablaHtml.='';

    $this->pdf->cabecera_reporte_colegio('Facturas_emitidas','Factura Emitida',mb_convert_encoding($tablaHtml, 'ISO-8859-1','UTF-8'),$contenido,$image,'','',7,$mostrar,$email);

  }

  function enviar_email_evidencia($parametros)
  {
    $empresaGeneral = $this->empresaGeneral;
    $titulo_correo = 'Comprobante de pago';
    $datos = $this->modelo->login($parametros['usuario'],$parametros['password'],'false');

    $cuerpo_correo = 'Comprobante de pago de '.utf8_decode($datos[0]['Cliente']);
    $to_correo = $empresaGeneral[0]['Email_Contabilidad'];
    $archivos[] =  $ruta = dirname(__DIR__,2).'/comprobantes/pagos_subidos/entidad_'.$_SESSION['INGRESO']['IDEntidad'].'/empresa_'.$_SESSION['INGRESO']['item'].'/'.$datos[0]['Evidencias'];
    enviar_email_comprobantes($archivos, $to_correo, $cuerpo_correo, $titulo_correo, $HTML = false);
  }


  function enviar_email_($usu,$pass,$nuevo)
  {
  	
  	$datos = $this->modelo->login($usu,$pass,$nuevo);  
    $empresaGeneral = array_map(array($this, 'encode1'), $this->empresaGeneral);
   // print_r($empresaGeneral);
    
  	$archivos=array($_SESSION['INGRESO']['item'].'_HOJA_DE_MATRICULA_'.$usu.'.pdf',$_SESSION['INGRESO']['item'].'_HOJA_DE_REGISTRO_'.$usu.'.pdf',$_SESSION['INGRESO']['item'].'_ACTA_DE_MATRICULA_'.$usu.'.pdf');
    $archivosC = array($_SESSION['INGRESO']['item'].'_HOJA_DE_MATRICULA_'.$usu.'.pdf');
     $tipos = array('jpg','png','jpeg','gif','pdf');
    foreach ($tipos as $key => $value) {
     if(file_exists('../vista/TEMP/'.$datos[0]['CI_RUC'].'_rep.'.$value))
      {
           //return 'existe';
           array_push($archivos,$datos[0]['CI_RUC'].'_rep.'.$value);
      }
      if(file_exists('../vista/TEMP/'.$_SESSION['INGRESO']['item'].'_'.$datos[0]['CI_RUC'].'_pago.'.$value))
      {
           //return 'existe';
          // array_push($archivos,$_SESSION['INGRESO']['item'].'_'.$datos[0]['CI_RUC'].'_pago.'.$value);
           array_push($archivosC,$_SESSION['INGRESO']['item'].'_'.$datos[0]['CI_RUC'].'_pago.'.$value);
      }
    }

  
  	//$correo='ejfc19omoshiroi@gmail.com,ejfc_omoshiroi@hotmail.com';
  	$cuerpo_correo = 'Gracias por registrarse en la institucion';
  	$titulo_correo = 'Documentos de matricula';
  	$correo_apooyo="info@diskcoversystem.com";
  	$nombre = "Secretaria";
    $nombre1 = "Colecturia";
    $email_conexion = $empresaGeneral[0]['Email_Conexion'];
    $email_pass =  $empresaGeneral[0]['Email_Contraseña'];
  	$correo1= $datos[0]['Email_R'].','.$datos[0]['Email_M'].','.$datos[0]['Email_P'];
    //print_r($correo1);
    //print_r($email_conexion);
    // print_r($this->empresa);die();

  	$this->email->enviar_email($archivos,$correo1,$cuerpo_correo,$titulo_correo,$correo_apooyo,$nombre,$email_conexion,$email_pass);
     
  	  $correo2 = $this->empresa[0]['Mail_Colegio'].',ejfc_omoshiroi@hotmail.com';

   // print_r($correo2);
  	// if(
  		$this->email->enviar_email($archivos,$correo2,$cuerpo_correo,$titulo_correo,$correo_apooyo,$nombre,$email_conexion,$email_pass);
    // {

      //datos para colecturai
      $correo3 = $empresaGeneral[0]['Email_Contabilidad'].',ejfc_omoshiroi@hotmail.com,ejfc19omoshiroi@gmail.com';

    //print_r($correo3);
      return $this->email->enviar_email($archivosC,$correo3,$cuerpo_correo,$titulo_correo,$correo_apooyo,$nombre1,$email_conexion,$email_pass);

    // }

  }

    function encode1($arr) {
  $new = array(); 
    foreach($arr as $key => $value) {
      //echo is_array($value);
      if(!is_object($value))
      {
      if($key=='Archivo_Foto')
      {
          if (!file_exists('../../img/img_estudiantes/'.$value)) 
          {
            $value='';
            $new[$key] = $value;
          } 
         } 

         if($value == '.')
         {

         $new[$key] = '';
         }else{

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

  function ver_fac_pdf($cod,$ser,$ci)
  {
    $this->modelo->pdf_factura($cod,$ser,$ci);
  }

  function nueva_matricula($usuario)
  {
    SetAdoAddNew('Clientes_Matriculas');
    SetAdoFields("Codigo", $usuario);
    SetAdoFields("T", G_NORMAL);
    if(SetAdoUpdate()!=1){
      return "Error al registrar en Clientes_Matriculas";
    }
    Eliminar_Nulos_SP("Clientes_Matriculas");
    return null;
  }


}
//while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
?>