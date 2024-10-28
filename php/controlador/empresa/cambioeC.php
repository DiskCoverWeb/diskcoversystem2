<?php
require_once(dirname(__DIR__,2)."/modelo/empresa/cambioeM.php"); 
require_once(dirname(__DIR__,3)."/lib/fpdf/reporte_de.php");
/**
 * 
 */
$controlador = new cambioeC();

if(isset($_GET['ciudad']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->ciudad($parametros));
}
if(isset($_GET['empresas']))
{
	$query = ''; $ciu = ''; $ent = '';
	if(isset($_GET['q'])){ $query = $_GET['q'];	}
	if(isset($_GET['ciu'])){ $ciu = $_GET['ciu'];	}
	if(isset($_GET['ent'])){ $ent = $_GET['ent'];	}
	echo json_encode($controlador->empresas($query,$ent,$ciu));
}
if(isset($_GET['datos_empresa']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->datos_empresa($parametros));
}

if(isset($_GET['editar_datos_empresa']))
{
	$parametros = $_POST;
	echo json_encode($controlador->editar_datos_empresa($parametros));
}

if(isset($_GET['mensaje_masivo']))
{
	$parametros = $_POST;
	echo json_encode($controlador->mensaje_masivo($parametros));
}
if(isset($_GET['mensaje_grupo']))
{
	$parametros = $_POST;
	echo json_encode($controlador->mensaje_grupo($parametros));
}
if(isset($_GET['mensaje_indi']))
{
	$parametros = $_POST;
	echo json_encode($controlador->mensaje_indi($parametros));
}
if(isset($_GET['guardar_masivo']))
{
	$parametros = $_POST;
	echo json_encode($controlador->guardar_masivo($parametros));
}
if(isset($_GET['subdireccion']))
{
    $query = $_POST['txtsubdi'];
	echo json_encode($controlador->TextSubDir_LostFocus($query));
}
if(isset($_GET['asignar_clave']))
{
	$parametros = $_POST;
	echo json_encode($controlador->asignar_clave($parametros));
}
if(isset($_GET['provincias']))
{
  $pais = '';
  if(isset($_POST['pais']))
  {
    $pais = $_POST['pais'];
  }
	echo json_encode(provincia_todas($pais));
}
if(isset($_GET['ciudad2']))
{
	echo json_encode(todas_ciudad($_POST['idpro']));
}
if(isset($_GET['cargar_imagen']))
{
	echo json_encode($controlador->guardar_foto($_FILES,$_POST));
}
if(isset($_GET['cargar_firma']))
{
	echo json_encode($controlador->guardar_firma($_FILES,$_POST));
}

if(isset($_GET['ddl_estados']))
{
	echo json_encode($controlador->estados());
}

if(isset($_GET['ddl_nacionalidades']))
{
	echo json_encode(naciones_todas());
}
if(isset($_GET['cargar_imgs']))
{
	echo json_encode($controlador->cargar_imgs());
}
if(isset($_GET['guardarTipoContribuyente']))
{
	echo json_encode($controlador->guardarTipoContribuyente($_POST['parametros']));
}
if(isset($_GET['TVcatalogo']))
{
  $parametros = $_POST['parametros'];
  echo json_encode($controlador->TVcatalogo($parametros));
}
if(isset($_GET['guardar']))
{
	$parametros = $_POST;
  echo json_encode($controlador->GrabarArticulos($parametros));
}
if(isset($_GET['detalle']))
{
  $id = $_POST['id'];
  $item = $_POST['item'];
  $entidad = $_POST['entidad'];
  echo json_encode($controlador->detalle_linea($id, $item, $entidad));
}

class cambioeC 
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new cambioeM();
	}

	function ciudad($parametros)
	{
		$IDempresa = $parametros['entidad'];
		$datos = $this->modelo->ciudad($IDempresa);
		// print_r($datos);die();
		if(count($datos)>0)
		{
			$resp[0] = array('codigo'=>'0','nombre'=>'Seleccione Ciudad');
			$resp[1] = array('codigo'=>'','nombre'=>'Todos');
			foreach ($datos as $key => $value) {
				$resp[] = array('codigo'=>$value['Ciudad'],'nombre'=>$value['Ciudad']);
			}
	    }else
	    {
	    	$resp[0] = array('codigo'=>'0','nombre'=>'Ciudad no encontrada');
	    }
		return $resp;

	}

	function empresas($query,$ent,$ciu)
	{
		$datos = $this->modelo->entidad($query,$ent,$ciu);
		// print_r($dato);die();
		if(count($datos)>0)
		{
				foreach ($datos as $key => $value) {
				$resp[] = array('id'=>$value['ID'],'text'=>$value['Empresa'],'CI'=>$value['RUC_CI_NIC'],'data'=>$value);
			}
	    }else
	    {
	    	$resp[0] = array('id'=>'','text'=>'Empresa no encontrada');
	    }
		return $resp;
	}

	function  TextSubDir_LostFocus($query)
{
    $TextSubDir = TextoValido($query);
    $dato = $this->modelo->consulta_empresa();
    if(count($dato)>0)
    {
        if($TextSubDir == G_NINGUNO  )
        {
            $NumEmpSubDir = 0;
            if(count($dato) > 0)
            {
                $NumEmpSubDir = intval($dato[0]["Item"]);
            }
            $NumEmpSubDir = $NumEmpSubDir + 1;
            $TextSubDir = "EMPRE".generaCeros($NumEmpSubDir,3);
            return $TextSubDir;
        }else
        {
            $dato2 = $this->modelo->consulta_empresa($TextSubDir);
            if(count($dato2)>0)
            {
                if($_SESSION['INGRESO']['item'] <> $dato2[0]["Item"] )
                {
                    return null;
                }

            }
        }
    }
}


	function datos_empresa($parametros)
	{
		$ID = $parametros['empresas'];
		$sms = $parametros['sms'];
		// print_r($parametros);die();
		$datosEmp = array();
		$contribuyente = array();
		$datosEmp[0] = array();
		$datos = $this->modelo->datos_empresa($ID);
		// print_r($datos);die();
		$CI = '.';
		if(count($datos)>0)
		{
			$contribuyente = $this->modelo->tipoContribuyente($datos[0]['RUC_CI_NIC']);
			$empresaSQL = '';
			$empresaSQL2 = '';
			$empresaSQL3 = '';
			$logotipo = '.';
			if($datos[0]['IP_VPN_RUTA']!='.' && $datos[0]['Base_Datos'] !='.' && $datos[0]['Usuario_DB']!='.' && $datos[0]['Contrasena_DB']!='.' && $datos[0]['Tipo_Base']!='.')
			{
				$datosEmp = $this->modelo->datos_sql_terceros($datos[0],$datos[0]['IP_VPN_RUTA'],$datos[0]['Usuario_DB'],$datos[0]['Contrasena_DB'],$datos[0]['Base_Datos'],$datos[0]['Puerto']);
				if($datosEmp!= '-1' && count($datosEmp)>0)
				{					
					$src = url_logo($datosEmp[0]['Logo_Tipo']);
					$img_name = ''; 
					if($src!='' && $src!='.'){ $patch = explode('/',$src); $partes = count($patch); $img_name = $patch[$partes-1]; }else{$img_name = $datosEmp[0]['Logo_Tipo'].' (NO ENCONTRADO)';}	

					$datosEmp[0]['Logo_Tipo'] = $img_name;
					$rut = dirname(__DIR__,3);
					$src = str_replace($rut,"../../", $src);
					$datosEmp[0]['Logo_Tipo_url'] = $src;
					// $_SESSION['INGRESO']['Logo_Tipo']	= $src;			
				}
			}
		}
			
		return array('empresa1'=>$datos,'empresa2'=>$datosEmp,'tipoContribuyente'=>$contribuyente);
	}

	function estados()
	{
		$datos = $this->modelo->estado();
		$rep = '<option value="">No existe estados</option>';
		if(count($datos)>0)
		{
			$rep ='<option value="">Seleccione estado</option>';
			foreach ($datos as $key => $value) {
				$rep.='<option value="'.$value['Estado'].'">'.$value['Descripcion'].'</option>';
			}
		}
		return $rep;	
	}

	function editar_datos_empresa($parametros)
	{
		$contribuyente = $this->modelo->tipoContribuyente($parametros['TxtRuc']);
		if(count($contribuyente)==0)
		{
			$this->modelo->ingresar_tipo_contribuyente($parametros['TxtRuc']);
			control_procesos('N',"Grabar conribuyente especial",'Por:'.$_SESSION['INGRESO']['CodigoU']);
		}else
		{
			// print_r($contribuyente);print_r($parametros);die();
			$this->modelo->editar_tipo_contribuyente($parametros);			
			control_procesos('N',"Editado conribuyente especial",'Por:'.$_SESSION['INGRESO']['CodigoU']);
		}
		$text = $this->validar_cambios($parametros);
		$resp = $this->modelo->editar_datos_empresaMYSQL($parametros);
		if($parametros['txt_sqlserver']==1)
		{
			$resp = $this->modelo->editar_catalogoLineas_empresa($parametros);
			if($resp==1)
			{
				 $this->validar_cambiosSQL($parametros);
				return $this->modelo->editar_datos_empresa($parametros);
			}else
			{
				return $resp;
			}
		}
		return $resp;

	}
	function  mensaje_masivo($parametros)
	{
		return $this->modelo->mensaje_masivo($parametros);

	}
	function  mensaje_grupo($parametros)
	{
		return $this->modelo->mensaje_grupo($parametros);

	}
	function  mensaje_indi($parametros)
	{
		return $this->modelo->mensaje_indi($parametros);

	}
	function  guardar_masivo($parametros)
	{
		return $this->modelo->guardar_masivo($parametros);

	}

	function asignar_clave($parametros)
	{
		// print_r($parametros);die();
		return $this->modelo->asignar_clave($parametros);
	}
	function guardar_foto($file,$post)
	{
	    $ruta= dirname(__DIR__,3).'/img/logotipos/';//ruta carpeta donde queremos copiar las imágenes
	    if (!file_exists($ruta)) {
	       mkdir($ruta, 0777, true);
	    }
	    if($this->validar_formato_img($file)==1)
	    {
	         $uploadfile_temporal=$file['file_img']['tmp_name'];
	         // $tipo = explode('/', $file['file_img']['type']);
	         $nombre = $file['file_img']['full_path'];
	         $name = explode('.',$nombre);
	         $nuevo_nom=$ruta.$nombre;
	         if (is_uploaded_file($uploadfile_temporal))
	         {
	         	$em[0]['IP_VPN_RUTA'] = $post['Servidor'];
	         	$em[0]['Usuario_DB'] = $post['Usuario'];
	         	$em[0]['Contrasena_DB']= $post['Clave'];
	         	$em[0]['Base_Datos']= $post['Base'];
	         	$em[0]['Puerto']= $post['Puerto'];
	         	$r = $this->modelo->actualizar_foto($name[0],$post['ci_ruc'],$em);
	         	// print_r($r);die();
	         	if($r==1)
	         	{
	         		move_uploaded_file($uploadfile_temporal,$nuevo_nom);
	                return 1;
	         	}else
	         	{
	         		return -3;
	         	}	           
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
	function guardar_firma($file,$post)
	{
		// print_r($file);die();
		// print_r($post);die();
	    $ruta= dirname(__DIR__,2).'/comprobantes/certificados/';//ruta carpeta donde queremos copiar las imágenes
	    if (!file_exists($ruta)) {
	       mkdir($ruta, 0777, true);
	    }
	    if($this->validar_formato_firma($file)==1)
	    {
	         $uploadfile_temporal=$file['file_firma']['tmp_name'];
	         // $tipo = explode('/', $file['file_img']['type']);
	         $nombre = str_replace(' ','_', $file['file_firma']['full_path']);
	         $nuevo_nom=$ruta.$nombre;
	         if (is_uploaded_file($uploadfile_temporal))
	         {
	         	$em[0]['IP_VPN_RUTA'] = $post['Servidor'];
	         	$em[0]['Usuario_DB'] = $post['Usuario'];
	         	$em[0]['Contrasena_DB']= $post['Clave'];
	         	$em[0]['Base_Datos']= $post['Base'];
	         	$em[0]['Puerto']= $post['Puerto'];
	         	$this->modelo->actualizar_firma($nombre,$post['ci_ruc'],$em);
	           move_uploaded_file($uploadfile_temporal,$nuevo_nom);
	          
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
	function validar_formato_img($file)
	{
	    switch ($file['file_img']['type']) {
	      case 'image/jpeg':
	      case 'image/pjpeg':
	      case 'image/gif':
	      case 'image/png':
	         return 1;
	        break;      
	      default:
	        return -1;
	        break;
	    }

	}
	function validar_formato_firma($file)
	{
	    switch ($file['file_firma']['type']) {
	      case 'application/x-pkcs12':
	         return 1;
	        break;      
	      default:
	        return -1;
	        break;
	    }

	}

	function cargar_imgs()
	{
		$opciones = '';
        $directorio = dirname(__DIR__,3).'/img/logotipos'; 
            // print_r($directorio);die();
			$archivos = scandir($directorio);
			foreach ($archivos as $archivo) {
			    $rutaArchivo = $directorio . '/' . $archivo;
			    if (is_file($rutaArchivo) && pathinfo($rutaArchivo, PATHINFO_EXTENSION) === 'png') {
			    	$opciones.='<option value="'.$archivo.'">'.$archivo.'</option>';
			    }
			}   
		return $opciones;             
	}


	function guardarTipoContribuyente($parametros)
	{
	 	return $this->modelo->editar_tipo_contribuyente($parametros);
	}

	function validar_cambios($parametros)
	{
		// print_r($parametros);
		$texto = '';
		$empresas = $this->modelo->datos_empresa($parametros['empresas']);
		// print_r($empresas);die();
		$datosEmp = $this->modelo->datos_sql_terceros($empresas[0],$empresas[0]['IP_VPN_RUTA'],$empresas[0]['Usuario_DB'],$empresas[0]['Contrasena_DB'],$empresas[0]['Base_Datos'],$empresas[0]['Puerto']);

		if($empresas[0]['Empresa']!=$parametros['TxtEmpresa'])
		{
			$texto=$empresas[0]['Empresa'].' a '.$parametros['TxtEmpresa'];			
			control_procesos('N',"Edicion datos empres",$texto.' Por:'.$_SESSION['INGRESO']['CodigoU']);
		}
		if($empresas[0]['Razon_Social']!=$parametros['TxtRazonSocial'])
		{
			$texto=$empresas[0]['Razon_Social'].' a '.$parametros['TxtRazonSocial'];
			control_procesos('N',"Edicion datos empres",$texto.' Por:'.$_SESSION['INGRESO']['CodigoU']);
		}

		if($empresas[0]['Gerente']!=$parametros['TxtRepresentanteLegal'])
		{
			$texto=$empresas[0]['Gerente'].' a '.$parametros['TxtRepresentanteLegal'];

			control_procesos('N',"Edicion datos empres",$texto.' Por:'.$_SESSION['INGRESO']['CodigoU']);
		}
		if($empresas[0]['Contador']!=$parametros['TxtNombConta'])
		{
			$texto= $empresas[0]['Contador'].' a '.$parametros['TxtNombConta'];

			control_procesos('N',"Edicion datos empres",$texto.' Por:'.$_SESSION['INGRESO']['CodigoU']);
		}
		if($empresas[0]['RUC_Contador']!=$parametros['TxtRucConta'])
		{
			$texto=$empresas[0]['RUC_Contador'].' a '.$parametros['TxtRucConta'];

			control_procesos('N',"Edicion datos empres",$texto.' Por:'.$_SESSION['INGRESO']['CodigoU']);
		}
		if($empresas[0]['IP_VPN_RUTA']!=$parametros['Servidor'])
		{
			$texto='IP_VPN_RUTA '.$empresas[0]['IP_VPN_RUTA'].' a '.$parametros['Servidor'];

			control_procesos('N',"Edicion datos empres",$texto.' Por:'.$_SESSION['INGRESO']['CodigoU']);
		}
		if($empresas[0]['Base_Datos']!=$parametros['Base'])
		{
			$texto='Base_Datos'.$empresas[0]['Base_Datos'].' a '.$parametros['Base'];

			control_procesos('N',"Edicion datos empres",$texto.' Por:'.$_SESSION['INGRESO']['CodigoU']);
		}
		if($empresas[0]['Fecha_CE']!=$parametros['FechaCE'])
		{
			$texto=' Fecha_CE'.$empresas[0]['Fecha_CE'].' a '.$parametros['FechaCE'];

			control_procesos('N',"Edicion datos empresa",$texto.' Por:'.$_SESSION['INGRESO']['CodigoU']);
		}
		if($empresas[0]['Fecha_DB']!=$parametros['FechaDB'])
		{
			$texto=' Fecha_DB '.$empresas[0]['Fecha_DB'].' a '.$parametros['FechaDB'];

			control_procesos('N',"Edicion datos empres",$texto.' Por:'.$_SESSION['INGRESO']['CodigoU']);
		}
		if($empresas[0]['Fecha']!=$parametros['FechaR'])
		{
			$texto=' FechaR '.$empresas[0]['Fecha'].' a '.$parametros['FechaR'];

			control_procesos('N',"Edicion datos empres",$texto.' Por:'.$_SESSION['INGRESO']['CodigoU']);
		}
		if($empresas[0]['Fecha_P12']!=$parametros['FechaP12'])
		{
			$texto=' FechaP12 '.$empresas[0]['Fecha_P12'].' a '.$parametros['FechaP12'];

			control_procesos('N',"Edicion datos empres",$texto.' Por:'.$_SESSION['INGRESO']['CodigoU']);
		}

	}

	function validar_cambiosSQL($parametros)
	{
		// print_r($parametros);
		$texto = '';
		$empresas = $this->modelo->datos_empresa($parametros['empresas']);
		// print_r($empresas);die();
		$datosEmp = $this->modelo->datos_sql_terceros($empresas[0],$empresas[0]['IP_VPN_RUTA'],$empresas[0]['Usuario_DB'],$empresas[0]['Contrasena_DB'],$empresas[0]['Base_Datos'],$empresas[0]['Puerto']);
		// sqlparte
		if($parametros['optionsRadios']=='option1'){$ambiente = 1;}else{$ambiente=2;}

		if($datosEmp[0]['Ambiente']!=$ambiente)
		{
			$texto='Ambiente '.$datosEmp[0]['Ambiente'].' a '.$ambiente;

			control_procesos('N',"Edicion datos empres",$texto.' Por:'.$_SESSION['INGRESO']['CodigoU']);
		}
		if($datosEmp[0]['Ruta_Certificado']!=$parametros['TxtEXTP12'])
		{
			$texto=' '.$datosEmp[0]['Ruta_Certificado'].' a '.$parametros['TxtEXTP12'];

			control_procesos('N',"Edicion datos empres",$texto.' Por:'.$_SESSION['INGRESO']['CodigoU']);
		}
		if($datosEmp[0]['Clave_Certificado']!=$parametros['TxtContraExtP12'])
		{
			$texto=' '.$datosEmp[0]['Clave_Certificado'].' a '.$parametros['TxtContraExtP12'];

			control_procesos('N',"Edicion datos empres",$texto.' Por:'.$_SESSION['INGRESO']['CodigoU']);
		}
	}

	function TVcatalogo($parametros)
	{
		$item = $parametros['item'];
		$entidad = $parametros['ent'];
    // print_r($parametros);die();
    $nl = $parametros['nivel'];
	  $h='';
		if($nl!='')
		{

      if($nl==1)
      {
        $datos = $this->modelo->nivel1($entidad, $item);
        foreach ($datos as $key => $value) {
           $h.= '<li  title="Presione Suprimir para eliminar">
                  <label id="label_'.str_replace('.','_','A1_'.$key).'" for="A1_'.$key.'">'.$value['Autorizacion'].'</label>
                  <input type="checkbox" id="A1_'.$key.'" onclick="TVcatalogo(2,\'A1_'.$key.'\',\''.$value['Autorizacion'].'\',\'\',\'\')" />
                 <ol id="hijos_'.str_replace('.','_','A1_'.$key).'"></ol></li>';
        }
      }

      if($nl==2)
      {
        $datos = $this->modelo->nivel2($entidad, $item, $parametros['auto']);
        foreach ($datos as $key => $value) {
           $h.= '<li  title="Presione Suprimir para eliminar">
                  <label id="label_'.str_replace('.','_','A2_'.$key).'" for="A2_'.$key.'">'.$value['Serie'].'</label>
                  <input type="checkbox" id="A2_'.$key.'" onclick="TVcatalogo(3,\'A2_'.$key.'\',\''.$parametros['auto'].'\',\''.$value['Serie'].'\',\'\')" />
                 <ol id="hijos_'.str_replace('.','_','A2_'.$key).'"></ol></li>';
        }
      }

      if($nl==3)
      {
        $datos = $this->modelo->nivel3($entidad, $item, $parametros['auto'],$parametros['serie']);
        foreach ($datos as $key => $value) {
           $h.= '<li  title="Presione Suprimir para eliminar">
                  <label id="label_'.str_replace('.','_','A3_'.$key).'" for="A3_'.$key.'">'.$value['Fact'].'</label>
                  <input type="checkbox" id="A3_'.$key.'" onclick="TVcatalogo(4,\'A3_'.$key.'\',\''.$parametros['auto'].'\',\''.$parametros['serie'].'\',\''.$value['Fact'].'\')" />
                 <ol id="hijos_'.str_replace('.','_','A3_'.$key).'"></ol></li>';
        }
      }

      if($nl==4)
      {
        $datos = $this->modelo->nivel4($entidad, $item, $parametros['auto'],$parametros['serie'],$parametros['fact']);
        foreach ($datos as $key => $value) {
          $h.='<li class="file" id="label_'.str_replace('.','_','A4_'.$key).'" title=""><a href="#" onclick="detalle_linea(\''.$value['ID'].'\',\'A4_'.$key.'\')">'.$value['Concepto'].'</a></li>';


           // $h.= '<li  title="Presione Suprimir para eliminar">
           //        <label id="label_'.str_replace('.','_','A4_'.$key).'" for="A4_'.$key.'">'.$value['Concepto'].'</label>
           //        <input type="checkbox" id="A4_'.$key.'" onclick="detalle_linea(\''.$value['ID'].'\')" />
           //       <ol id="hijos_'.str_replace('.','_','A2_'.$key).'"></ol></li>';
        }
      }

		}else
		{
			$codigo = 'A';
		  $detalle = 'AUTORIZACIONES';

			 $h = '<li  title="Presione Suprimir para eliminar">
							    <label id="label_'.str_replace('.','_','A').'" for="A">AUTORIZACIONES</label>
							    <input type="checkbox" id="A" onclick="TVcatalogo(1,\'A\',\'\',\'\',\'\')" />
							   <ol id="hijos_'.str_replace('.','_','A').'"></ol></li>';
		}

		return $h;
	}
	function detalle_linea($id, $item, $entidad)
	{
    if(is_numeric($id))
    {
      $datos = $this->modelo->Catalogo_Lineas($entidad, $item, $id);
    }
		return $datos;
	}
	function  GrabarArticulos($parametros)
	{
        $Codigo = $parametros['TextCodigo'];
        $TxtLargo = TextoValido($parametros['TxtLargo']);
        $TxtAncho = TextoValido($parametros['TxtAncho']);
        $TxtAncho =  TextoValido($parametros['TxtEspa']);
        $TxtPosFact  =  TextoValido($parametros['TxtPosFact']);
		$Item = $parametros['item'];
		$Entidad = $parametros['entidad'];
        if($parametros['CTipo']== ""){$parametros['CTipo'] = "FA";} 

        $datos = $this->modelo->validar_codigo($Codigo, $Item, $Entidad);

        //SetAdoAddNew("Catalogo_Lineas");

		$sql = "";
		$campos = array();
        if(count($datos) <= 0 ){
            //control_procesos("F","Creación de Punto de Venta de ".$parametros['CTipo']."-".$parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos']);
            //control_procesos( "F","Creación de Fecha de Vencimiento de ".$parametros['TextCodigo']." ".$parametros['MBFechaVenc']);
            //control_procesos( "F","Creación de Autorización de ".$parametros['TextCodigo']." ".$parametros['TxtNumAutor']);
            //control_procesos("F","Creación de Serie de ".$parametros['TextCodigo']." ".$parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos']);
            //control_procesos( "F", "Creación de Secuencial Inicial de ".$parametros['TextCodigo']." ".$parametros['TxtNumSerietres1']);

			$campos['Codigo'] = "'".$parametros['TextCodigo']."'";
			$campos['Item'] = "'".intval($Item)."'";
			$campos['Periodo'] = "'".$_SESSION['INGRESO']['periodo']."'";
			$campos['TL'] = 1;

            /*SetAdoFields("Codigo", $parametros['TextCodigo']);
            SetAdoFields("Item", intval($Item));
            SetAdoFields("Periodo", $_SESSION['INGRESO']['periodo']);
            SetAdoFields("TL", 1);*/

            $Codigo = "A.".$parametros['TxtNumAutor'].".".$parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos'].".".$parametros['CTipo'].".".$parametros['TextCodigo'];
            $Cuenta = $parametros['TextLinea'];
        }else{
            //control_procesos("F", "Modificación de Punto de Venta de ".$parametros['CTipo']."-".$parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos']);
            $this->modelo->elimina_linea($Codigo, $Item, $Entidad);              
            $datos1 = $this->modelo->validar_codigo($Codigo, $Item, $Entidad);
            //if($parametros['MBFechaVenc'] <> $datos[0]["Vencimiento"]->format('Y-m-d')){ control_procesos("F", "Modifico: Fecha de Vencimiento de ".$parametros['TextCodigo']." ".$parametros['MBFechaVenc']);}
            //if($parametros['TxtNumAutor'] <> $datos[0]["Autorizacion"]){ control_procesos("F", "Modifico: Autorización de ".$parametros['TextCodigo']." ".$parametros['TxtNumAutor']);}
            //if($parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos'] <> $datos[0]["Serie"]){ control_procesos("F", "Modifico: Serie de ".$parametros['TextCodigo']." ".$parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos']);}
            //if($parametros['TxtNumSerietres1'] <> $datos[0]["Secuencial"]){ control_procesos("F","Modifico: Secuencial Inicial de ".$parametros['TextCodigo']." ".$parametros['TxtNumSerietres1']);}

			$campos['Codigo'] = "'".$parametros['TextCodigo']."'";
			$campos['Item'] = "'".$Item."'";
			$campos['Periodo'] = "'".$_SESSION['INGRESO']['periodo']."'";
			$campos['TL'] = 1;

            /*SetAdoFields("Codigo", $parametros['TextCodigo']);
            SetAdoFields("Item", $Item);
            SetAdoFields("Periodo", $_SESSION['INGRESO']['periodo']);
            SetAdoFields("TL", 1);*/
        }

		$campos['Concepto'] = "'".$parametros['TextLinea']."'";
		$campos['CxC'] = "'".substr($parametros['MBoxCta'], 0, -1)."'";
		$campos['CxC_Anterior'] = "'".substr($parametros['MBoxCta_Anio_Anterior'], 0, -1)."'";
		$campos['Cta_Venta'] = substr($parametros['MBoxCta_Venta'], 0, -1);
		$campos['Logo_Factura'] = "'".$parametros['TxtLogoFact']."'";
		$campos['Largo'] = $parametros['TxtLargo'];
		$campos['Ancho'] = $parametros['TxtAncho'];
		$campos['Espacios'] = $parametros['TxtEspa'];
		$campos['Pos_Factura'] = $parametros['TxtPosFact'];
		$campos['Pos_Y_Fact'] = $parametros['TxtPosY'];
		$campos['Fact_Pag'] = $parametros['TxtNumFact'];
		$campos['ItemsxFA'] = $parametros['TxtItems'];
		$campos['Fact'] = "'".$parametros['TxtLogoFact']."'";
		// 'SRI'
		$campos['Fecha'] = "'".$parametros['MBFechaIni']."'";
		$campos['Vencimiento'] = "'".$parametros['MBFechaVenc']."'";
		$campos['Secuencial'] = $parametros['TxtNumSerietres1'];
		$campos['Autorizacion'] = "'".$parametros['TxtNumAutor']."'";
		$campos['Serie'] = "'".$parametros['TxtNumSerieUno'] . $parametros['TxtNumSerieDos']."'";
		$campos['Nombre_Establecimiento'] = "'".$parametros['TxtNombreEstab']."'";
		$campos['Direccion_Establecimiento'] = "'".$parametros['TxtDireccionEstab']."'";
		$campos['Telefono_Estab'] = "'".$parametros['TxtTelefonoEstab']."'";
		$campos['Logo_Tipo_Estab'] = "'".$parametros['TxtLogoTipoEstab']."'";

        /*SetAdoFields("Concepto", $parametros['TextLinea']);
        SetAdoFields("CxC", substr($parametros['MBoxCta'], 0, -1));
        SetAdoFields("CxC_Anterior", substr($parametros['MBoxCta_Anio_Anterior'], 0, -1));
        SetAdoFields("Cta_Venta", substr($parametros['MBoxCta_Venta'], 0, -1));
        SetAdoFields("Logo_Factura", $parametros['TxtLogoFact']);
        SetAdoFields("Largo", $parametros['TxtLargo']);
        SetAdoFields("Ancho", $parametros['TxtAncho']);
        SetAdoFields("Espacios", $parametros['TxtEspa']);
        SetAdoFields("Pos_Factura", $parametros['TxtPosFact']);
        SetAdoFields("Pos_Y_Fact", $parametros['TxtPosY']);
        SetAdoFields("Fact_Pag", $parametros['TxtNumFact']);
        SetAdoFields("ItemsxFA", $parametros['TxtItems']);
        SetAdoFields("Fact", $parametros['CTipo']);
        // 'SRI
        SetAdoFields("Fecha", $parametros['MBFechaIni']);
        SetAdoFields("Vencimiento", $parametros['MBFechaVenc']);
        SetAdoFields("Secuencial", $parametros['TxtNumSerietres1']);
        SetAdoFields("Autorizacion", $parametros['TxtNumAutor']);
        SetAdoFields("Serie", $parametros['TxtNumSerieUno'] . $parametros['TxtNumSerieDos']);
        SetAdoFields("Nombre_Establecimiento", $parametros['TxtNombreEstab']);
        SetAdoFields("Direccion_Establecimiento", $parametros['TxtDireccionEstab']);
        SetAdoFields("Telefono_Estab", $parametros['TxtTelefonoEstab']);
        SetAdoFields("Logo_Tipo_Estab", $parametros['TxtLogoTipoEstab']);*/
        if (isset($parametros['CheqMes']) && $parametros['CheqMes'] != 'false') {
			$campos['Imp_Mes'] = 1;
            //SetAdoFields("Imp_Mes", 1);
        }
        //SetAdoUpdate();
		$this->modelo->crearActualizarRegistro($Entidad, $Item, "Catalogo_Lineas", $campos);


		//print_r($Entidad.",". $Item.",". $Codigo.",".$parametros['TxtNumSerieUno'].",".$parametros['TxtNumSerieDos'].",".$parametros['TxtNumAutor']);die();
		$datos = $this->modelo->facturas_formato($Entidad, $Item, $Codigo,$parametros['TxtNumSerieUno'],$parametros['TxtNumSerieDos'],$parametros['TxtNumAutor']);
        //SetAdoAddNew("Facturas_Formatos");

		$campos = array(
			"Cod_CxC" => "'".$parametros['TextCodigo']."'",
			"Item" => "'".$Item."'",
			"Periodo" => "'".$_SESSION['INGRESO']['periodo']."'",
			"Concepto" => "'".$parametros['TextLinea']."'",
			"Formato_Factura" => "'".$parametros['TxtLogoFact']."'",
			"Largo" => $parametros['TxtLargo'],
			"Ancho" => $parametros['TxtAncho'],
			"Espacios" => $parametros['TxtEspa'],
			"Pos_Factura" => $parametros['TxtPosFact'],
			"Pos_Y_Fact" => $parametros['TxtPosY'],
			"Fact_Pag" => $parametros['TxtNumFact'],
			"TC" => "'".$parametros['CTipo']."'",
			// 'SRI'
			"Fecha_Inicio" => "'".$parametros['MBFechaIni']."'",
			"Fecha_Final" => "'".$parametros['MBFechaVenc']."'",
			"Autorizacion" => "'".$parametros['TxtNumAutor']."'",
			"Serie" => "'".$parametros['TxtNumSerieUno'] . $parametros['TxtNumSerieDos']."'",
			"Nombre_Establecimiento" => "'".$parametros['TxtNombreEstab']."'",
			"Direccion_Establecimiento" => "'".$parametros['TxtDireccionEstab']."'",
			"Telefono_Estab" => "'".$parametros['TxtTelefonoEstab']."'",
			"Logo_Tipo_Estab" => "'".$parametros['TxtLogoTipoEstab']."'"
		);

        /*SetAdoFields("Cod_CxC", $parametros['TextCodigo']);
        SetAdoFields("Item", $Item);
        SetAdoFields("Periodo", $_SESSION['INGRESO']['periodo']);
        SetAdoFields("Concepto", $parametros['TextLinea']);
        SetAdoFields("Formato_Factura", $parametros['TxtLogoFact']);
        SetAdoFields("Largo", $parametros['TxtLargo']);
        SetAdoFields("Ancho", $parametros['TxtAncho']);
        SetAdoFields("Espacios", $parametros['TxtEspa']);
        SetAdoFields("Pos_Factura", $parametros['TxtPosFact']);
        SetAdoFields("Pos_Y_Fact", $parametros['TxtPosY']);
        SetAdoFields("Fact_Pag", $parametros['TxtNumFact']);
        SetAdoFields("TC", $parametros['CTipo']);
        // 'SRI
        SetAdoFields("Fecha_Inicio", $parametros['MBFechaIni']);
        SetAdoFields("Fecha_Final", $parametros['MBFechaVenc']);
        SetAdoFields("Autorizacion", $parametros['TxtNumAutor']);
        SetAdoFields("Serie", $parametros['TxtNumSerieUno'] . $parametros['TxtNumSerieDos']);
        SetAdoFields("Nombre_Establecimiento", $parametros['TxtNombreEstab']);
        SetAdoFields("Direccion_Establecimiento", $parametros['TxtDireccionEstab']);
        SetAdoFields("Telefono_Estab", $parametros['TxtTelefonoEstab']);
        SetAdoFields("Logo_Tipo_Estab", $parametros['TxtLogoTipoEstab']);*/
        if(count($datos)<=0)
        {
            //SetAdoUpdate();
			$this->modelo->crearActualizarRegistro($Entidad, $Item, "Facturas_Formatos", $campos);
        }else
        {
            //SetAdoFieldsWhere("ID", $datos[0]['ID']);
            //SetAdoUpdateGeneric();
			$this->modelo->crearActualizarRegistro($Entidad, $Item, "Facturas_Formatos", $campos, 1, array("ID" => $datos[0]['ID']));
        }

        // 'Numeracion de FA,NC,GR,ETC
        switch ($parametros['CTipo']) {
            case 'NC':
            $datos = $this->modelo->NC($Entidad, $Item, $parametros['TxtNumSerieUno'],$parametros['TxtNumSerieDos']);
            break;
            case 'GR':
            $datos = $this->modelo->GR($Entidad, $Item, $parametros['TxtNumSerieUno'],$parametros['TxtNumSerieDos']);
            break;                
            default:
            $datos = $this->modelo->FACTURAS($Entidad, $Item, $parametros['CTipo'],$parametros['TxtNumSerieUno'],$parametros['TxtNumSerieDos']);
            break;
        }
    
        if(count($datos)>0) {
            foreach ($datos as $key => $value) {
                $datos2 = $this->modelo->codigos($Entidad, $Item, $value['Periodo'],$value['Item'],$value['TC'],$value['Serie_X']);
				$campos = array();
                //SetAdoAddNew("Codigos");
                if(count($datos2)>0)
                {
					$campos['Numero'] = $value['TC_No']+1;
					//$campos['ID'] = $datos2[0]['ID'];

                    /*SetAdoFields("Numero", $value['TC_No']+1);
                    SetAdoFieldsWhere("ID", $datos2[0]['ID']);
                    SetAdoUpdateGeneric();*/

					$this->modelo->crearActualizarRegistro($Entidad, $Item, "Codigos", $campos, 1, array("ID" => $datos2[0]['ID']));
                }else
                {
					$campos['Item'] = "'".$value['Item']."'";
					$campos['Periodo'] = "'".$value['Periodo']."'";
					$campos['Concepto'] = "'".$value['TC']."_SERIE_".$value['Serie_X']."'";
					$campos['Numero'] = $value['TC_No']+1;

					$this->modelo->crearActualizarRegistro($Entidad, $Item, "Codigos", $campos);

                    /*SetAdoFields("Item", $value['Item']);
                    SetAdoFields("Periodo", $value['Periodo']);
                    SetAdoFields("Concepto", $value['TC']."_SERIE_".$value['Serie_X']);
                    SetAdoFields("Numero", $value['TC_No']+1);
                    SetAdoUpdate();*/
                }
            }
        }else{
            /*SetAdoAddNew("Codigos");
            SetAdoFields("Item", $Item);
            SetAdoFields("Periodo", $_SESSION['INGRESO']['periodo']);
            SetAdoFields("Concepto", $parametros['CTipo'] . "_SERIE_" . $parametros['TxtNumSerieUno'] . $parametros['TxtNumSerieDos']);
            SetAdoFields("Numero", intval($parametros['TxtNumSerietres1']));
            SetAdoUpdate();*/

			$campos['Item'] = "'".$Item."'";
			$campos['Periodo'] = "'".$_SESSION['INGRESO']['periodo']."'";
			$campos['Concepto'] = "'".$parametros['CTipo'] . "_SERIE_" . $parametros['TxtNumSerieUno'] . $parametros['TxtNumSerieDos']."'";
			$campos['Numero'] = intval($parametros['TxtNumSerietres1']);
			$this->modelo->crearActualizarRegistro($Entidad, $Item, "Codigos", $campos);
        }  

        return 1;

	}

}

?>