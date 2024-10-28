<?php
//Llamada al modelo
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once("../modelo/usuario_model.php");
require_once(dirname(__DIR__, 2) . '/lib/phpmailer/enviar_emails.php');
$login = new login_controller();
if (isset($_GET['Entidad'])) {
	$entidad = $_POST['entidad'];
	$_SESSION['INGRESO']['Height_pantalla'] = $_GET['pantalla'];
	echo json_encode($login->validar_entidad($entidad));
}

if (isset($_GET['base_actual_'])) {
	echo json_encode($_SESSION['INGRESO']['base_actual']);
}

if (isset($_GET['Cartera_Entidad'])) {
	$entidad = $_POST['entidad'];
	$_SESSION['INGRESO']['Height_pantalla'] = $_GET['pantalla'];
	// if user from the share internet  
	if(!empty($_SERVER['HTTP_CLIENT_IP'])) {   
        $_SESSION['INGRESO']['IP_Wan'] = $_SERVER['HTTP_CLIENT_IP'];   
    }   
    //if user is from the proxy   
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   
        $_SESSION['INGRESO']['IP_Wan'] = $_SERVER['HTTP_X_FORWARDED_FOR'];   
    }   
    //if user is from the remote address   
    else{   
        $_SESSION['INGRESO']['IP_Wan'] = $_SERVER['REMOTE_ADDR'];   
    }

	echo json_encode($login->validar_entidad_cartera($entidad));
}
if (isset($_GET['Usuario'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($login->validar_usuario($parametro));
}
if (isset($_GET['Ingresar'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($login->login($parametro));
}
if (isset($_GET['Ingresar_cartera'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($login->login_cartera($parametro));
}
if (isset($_GET['recuperar'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($login->recuperar_clave($parametro));
}
if (isset($_GET['setear_empresa'])) {
	$parametro = $_POST['parametros'];
	$_SESSION['INGRESO']['CARTERA_ITEM'] = $parametro['item_cartera'];
	  
	// print_r($parametro);die();
}
if (isset($_GET['logout'])) {
	echo json_encode($login->logout());
}
if (isset($_GET['patch'])) {
	$parametro = $_POST['parametros'];
	$patch = $parametro['patch'];
	if($parametro['patch'] != ""){
		$patchseparado = explode(' > ', $parametro['patch']);
		$patch = strtoupper($patchseparado[0]) . " > " . $patchseparado[1];
	}
	$_SESSION['INGRESO']['PATCH'] = $patch;
	//$_SESSION['INGRESO']['PATCH'] = strtoupper($parametro['patch']);
}

if (isset($_GET['datos_notificacion'])) {
	$parametro = $_POST['parametros'];	
	echo json_encode($login->datos_notificacion($parametro));
}

/**
 * 
 */
class login_controller
{
	private $modelo;
	private $email;
	function __construct()
	{
		$this->modelo = new usuario_model();
		$this->email = new enviar_emails();
	}

	function validar_entidad($entidad)
	{
		$datos = $this->modelo->ValidarEntidad1($entidad);
		// $datos['cartera'] = 0;
		// if($datos['respuesta']==-1)
		// {
		// 	$datos = $this->modelo->empresa_cartera($entidad);
		// 	// print_r($datos);die();
		// 	if(count($datos)>0)
		// 	{
		// 		$datos['cartera'] = 1;
		// 		$datos['cartera_usu'] = 'Cartera';
		// 		$datos['cartera_pass'] = '999999';
		// 		$datos['respuesta'] = 1;
		// 		$datos['entidad'] = $datos[0]['ID_Empresa'];
		// 		$datos['Nombre'] = $datos[0]['Empresa'];
		// 		$datos['Item'] = $datos[0]['Item'];
		// 		$_SESSION['INGRESO']['CARTERA_ITEM'] = $datos[0]['Item'];
		// 	}else
		// 	{
		// 		//retorna -1 cuando no se encuentra la empresa 			
		// 		$datos['respuesta'] = -1;
		// 		$datos['entidad'] = '';
		// 		$datos['Nombre'] = '';
		// 	}

		// }
		return $datos;
		// print_r($datos);die();
	}

	function validar_entidad_cartera($entidad)
	{
		if (is_numeric($entidad) && strlen($entidad) == 10 || is_numeric($entidad) && strlen($entidad) == 13) {

			$datos = $this->modelo->empresa_cartera($entidad);
			// print_r($datos);die();
			$datos1 = array();
			if (count($datos) > 0) {
				foreach ($datos as $key => $value) {

					$url = '../../img/jpg/logo.jpg';
					$tipo_img = array('jpg', 'gif', 'png', 'jpeg');
					foreach ($tipo_img as $key2 => $value2) {
						if (file_exists(dirname(__DIR__, 2) . '/img/logotipos/' . $value['Logo_Tipo'] . '.' . $value2)) {
							$url = '../../img/logotipos/' . $value['Logo_Tipo'] . '.' . $value2;
							break;
						}
					}
					$datos1[] = array('entidad' => $value['ID_Empresa'], 'Nombre' => $value['Empresa'], 'Razon_Social' => $value['Razon_Social'], 'Item' => $value['Item'], 'Logo' => $url,'ci'=>$value['RUC_CI_NIC']);

				}
			}

			return $datos1;
		} else {
			return -2;
		}
		// print_r($datos);die();
	}

	function validar_usuario($parametro)
	{
		$datos = $this->modelo->ValidarUser1($parametro['usuario'], $parametro['entidad'], $parametro['item']);
		$datos['cartera_usu'] = 'Cartera';
		$datos['cartera_pass'] = '999999';
		return $datos;
	}
	function login($parametro)
	{
		// session_destroy();		
		// print_r($parametro);
		// print_r($datos);
		// die();

		// session_regenerate_id(true);	
		
		

		$_SESSION['INGRESO']['PC_MAC'] = "00:00:00:00:00:00";

		$_SESSION['INGRESO']['usuario'] = $parametro['usuario'];
		$_SESSION['INGRESO']['pass'] = $parametro['pass'];

		$_SESSION['INGRESO']['msjMora'] = true; //indica que se debe mostrado el msj de mora en caso de existir.
		//$_SESSION['INGRESO']['IP_Local'] = !empty($parametro['localIp']) ? $parametro['localIp'] : ".";

		if ($parametro['cartera'] == 1) {
			
			$_SESSION['INGRESO']['usuario'] = $parametro['cartera_usu'];
			$_SESSION['INGRESO']['pass'] = $parametro['cartera_pass'];
			$datos = $this->modelo->Ingresar($parametro['cartera_usu'], $parametro['cartera_pass'], $parametro['entidad'], $parametro['item']);
			// validar cliente en cartera
			$empresa = $this->modelo->empresa_cartera($parametro['empresa'], $parametro['entidad']);
			// print_r($empresa);die();
			$cliente = $this->modelo->buscar_cliente_cartera($parametro['usuario'], $parametro['pass'], $empresa);
			if (count($cliente) == 0) {
				return -2;
			} else {
				$_SESSION['INGRESO']['CARTERA_USUARIO'] = $parametro['usuario'];
				$_SESSION['INGRESO']['CARTERA_PASS'] = $parametro['pass'];
			}

			// print_r($cliente);die();
			// print_r($empresa);print_r($cliente);die();
		} else {
			$datos = $this->modelo->Ingresar($parametro['usuario'], $parametro['pass'], $parametro['entidad'], $parametro['item']);
		}

		// print_r($datos);die();


		return $datos;
	}
	function login_cartera($parametro)
	{
		$datos = $this->modelo->Ingresar($parametro['usuario'], $parametro['pass'], $parametro['entidad']);

		// print_r($parametro);
		// print_r($datos);
		// die();
		if ($parametro['cartera'] == 1) {
			// validar cliente en cartera
			$empresa = $this->modelo->empresa_cartera($parametro['empresa'], $parametro['entidad']);
			// print_r($empresa);die();
			$cliente = $this->modelo->buscar_cliente_cartera($parametro['cartera_usu'], $parametro['cartera_pass'], $empresa);
			if (count($cliente) == 0) {
				return -2;
			} else {
				$_SESSION['INGRESO']['CARTERA_USUARIO'] = $parametro['cartera_usu'];
				$_SESSION['INGRESO']['CARTERA_PASS'] = $parametro['cartera_pass'];
			}

			// print_r($cliente);die();
			// print_r($empresa);print_r($cliente);die();
		}


		return $datos;
	}

	function logout()
	{
		session_destroy();
		$_SESSION = array();  // Limpia todas las variables de la sesión
		if (ini_get("session.use_cookies")) {
		    $params = session_get_cookie_params();
		    setcookie(session_name(), '', time() - 42000,
		        $params["path"], $params["domain"],
		        $params["secure"], $params["httponly"]
		    );
		}

		return 1;
	}


	function recuperar_clave($parametro)
	{
		// print_r($parametro);die();
		//entra a buscar en cartera
		if (is_numeric($parametro['usuario'])) {
			$empresa = $this->modelo->empresa_cartera($parametro['empresa'], $parametro['entidad']);
			// print_r($empresa);die();
			$datos = $this->modelo->buscar_cliente_cartera($parametro['usuario'], false, $empresa);
			// print_r($datos);die();
			if (count($datos) > 0) {
				$datos_email = array(
					'nick' => $datos[0]['CI_RUC'],
					'clave' => $datos[0]['Clave'],
					'email' => $datos[0]['Email'],
					'entidad' => $empresa[0]['Razon_Social'],
					'ruc' => $parametro['empresa'],
					'usuario' => $datos[0]['Cliente'],
					'CI_usuario' => $datos[0]['CI_RUC'],
					'cartera' => 1,
				);
				// print_r($datos_email);die();
				$rep = $this->enviar_email($datos_email);
				return array('respuesta' => $rep, 'email' => $datos_email['email']);
			} else {
				return -1;
			}
		} else {
			//entra a buscar en usuarios de sistema
			// print_r($parametro);die();
			$datos = $this->modelo->datos_usuario_mysql($usuario = false, $entidad = false, $parametro['usuario']);
			// print_r($datos);die();
			if (count($datos) > 0) {
				$datos_email = array(
					'nick' => $datos[0]['Usuario'],
					'clave' => $datos[0]['Clave'],
					'email' => $datos[0]['Email'],
					'entidad' => '',
					'ruc' => $parametro['empresa'],
					'usuario' => $datos[0]['Nombre_Usuario'],
					'CI_usuario' => $datos[0]['CI_NIC'],
					'cartera' => 0,
				);
				// print_r($datos_email);die();
				$rep = $this->enviar_email($datos_email);
				return array('respuesta' => $rep, 'email' => $datos_email['email']);
			} else {
				return -1;
			}

			// print_r($datos);die();
		}

	}

	function datos_notificacion($parametros)
	{
		$id = $parametros['id_noti'];
		$lista = array();
		$datos = $this->modelo->datos_notificacion($id);
		foreach ($datos as $key => $value) {
			$proceso = $this->link_proceso($value['CC2']);
			$value['link'] = $proceso;
			$lista[] = $value; 
		}
		// print_r($lista);die();
		return $lista;
	}

	function link_proceso($num)
	{
		$pag = '';
		switch ($num) {
			case '1':
				$pag = 'alimentosRec';
				break;
			case '2':			
				$pag = 'alimentosRec2';
				break;
			case '3':
				$pag = 'alimentosRec3';
				break;
			default:
				// code...
				break;
		}
		return $pag;
	}

	function enviar_email($parametros)
	{
		$empresaGeneral = $this->modelo->Empresa_data($parametros['ruc']);
		if ($empresaGeneral == -1) {
			return 2;
		}
		$datos = $this->modelo->entidades_usuario($parametros['CI_usuario']);
		if ($parametros['cartera'] == 1) {
			$datos[0]['Nombre_Usuario'] = $parametros['usuario'];
			$datos[0]['Usuario'] = $parametros['nick'];
			$datos[0]['Clave'] = $parametros['clave'];
			$datos[0]['Email'] = $parametros['email'];
		}

		$email_conexion = 'info@diskcoversystem.com';
		$email_pass = 'info2021DiskCover';
		$correo_apooyo = "credenciales@diskcoversystem.com"; //correo que saldra ala do del emisor
		$cuerpo_correo = '
<pre>
Este correo electronico fue generado automaticamente del Sistema Administrativo Financiero Contable DiskCover System a usted porque figura como correo electronico alternativo en nuestra base de datos.

A solicitud de El(a) Sr(a) ' . $parametros['usuario'] . ' se envian sus credenciales de acceso:
</pre> 
<br>
<table>
<tr><td><b>Link:</b></td><td>https://erp.diskcoversystem.com</td></tr>';
		if ($parametros['cartera'] == 1) {
			$cuerpo_correo .= '<tr><td><b>Entidad:</b></td><td>' . $parametros['entidad'] . '</td></tr>
	<tr><td><b>Ruc:</b></td><td>' . $parametros['ruc'] . '</td></tr>';
		}
		$cuerpo_correo .= '<tr><td><b>Nombre Usuario:</b></td><td>' . $datos[0]['Nombre_Usuario'] . '</td></tr>
<tr><td><b>Usuario:</b></td><td>' . $datos[0]['Usuario'] . '</td></tr>
<tr><td><b>Clave:</b></td><td>' . $datos[0]['Clave'] . '</td></tr>
<tr><td><b>Email:</b></td><td>' . $datos[0]['Email'] . '</td></tr>
</table>
A este usuario se asigno las siguientes Entidades: 
<br>';
		if ($parametros['cartera'] == 0) {
			$cuerpo_correo .= '<table> <tr><td width="30%"><b>Codigo</b></td><td width="50%"><b>Entidad</b></td></tr>';
			foreach ($datos as $value) {
				$cuerpo_correo .= '<tr><td>' . $value['id'] . '</td><td>' . $value['text'] . '</td></tr>';
			}
		}
		$cuerpo_correo .= '</table><br>';
		$cuerpo_correo .= ' 
<pre>
Nosotros respetamos su privacidad y solamente se utiliza este correo electronico para mantenerlo informado sobre nuestras ofertas, promociones, claves de acceso y comunicados. No compartimos, publicamos o vendemos su informacion personal fuera de nuestra empresa.

Esta direccion de correo electronico no admite respuestas. En caso de requerir atencion personalizada por parte de un asesor de servicio al cliente; Usted podra solicitar ayuda mediante los canales de atencion al cliente oficiales que detallamos a continuacion:
</pre>
<table width="100%">
<tr>
 <td align="center">
 <hr>
    SERVIRLES ES NUESTRO COMPROMISO, DISFRUTARLO ES EL SUYO
<hr>
    </td>
    </tr>
    <tr>   
 <td align="center">
    www.diskcoversystem.com
    </td>
    </tr>
     <tr>   
 <td align="center">
        QUITO - ECUADOR
    </td>
    </tr>
  </table>
';

		$titulo_correo = 'Credenciales de acceso al sistema DiskCover System';
		$archivos = false;
		$correo = $parametros['email'];
		if ($this->email->enviar_credenciales($archivos, $correo, $cuerpo_correo, $titulo_correo, $correo_apooyo, 'Credenciales de acceso al sistema DiskCover System', $email_conexion, $email_pass, $html = 1, $empresaGeneral) == 1) {
			return 1;
		} else {
			return -1;
		}
	}


}


?>