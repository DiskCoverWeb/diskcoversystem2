<?php 
require_once(dirname(__DIR__,1)."/modelo/loginM.php");
require_once(dirname(__DIR__, 2) . '/lib/phpmailer/enviar_emails.php');
$controlador = new loginC();

if (isset($_GET['Cartera_Entidad'])) {

	$entidad = $_POST['entidad'];
	// $_SESSION['INGRESO']['Height_pantalla'] = $_GET['pantalla'];
	// // if user from the share internet  
	// if(!empty($_SERVER['HTTP_CLIENT_IP'])) {   
    //     $_SESSION['INGRESO']['IP_Wan'] = $_SERVER['HTTP_CLIENT_IP'];   
    // }   
    // //if user is from the proxy   
    // elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   
    //     $_SESSION['INGRESO']['IP_Wan'] = $_SERVER['HTTP_X_FORWARDED_FOR'];   
    // }   
    // //if user is from the remote address   
    // else{   
    //     $_SESSION['INGRESO']['IP_Wan'] = $_SERVER['REMOTE_ADDR'];   
    // }

	echo json_encode($controlador->validar_entidad_cartera($entidad));
}

if (isset($_GET['Validar_Usuario'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->validar_usuario($parametro));
}
if (isset($_GET['Ingresar'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->login($parametro));
}
if (isset($_GET['Ingresar_cartera'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->login_cartera($parametro));
}
if (isset($_GET['logout'])) {
	echo json_encode($controlador->logout());
}
if (isset($_GET['recuperar'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->recuperar_clave($parametro));
}



/**
 * 
 */
class loginC
{
	private $modelo;	
	private $email;
	function __construct()
	{
		$this->modelo = new loginM();		
		$this->email = new enviar_emails();
	}

	function validar_entidad_cartera($entidad)
	{
		// print_r($entidad);die();
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
	}

	function validar_usuario($parametro)
	{
		// print_r($parametro);die();
		$datos = $this->modelo->ValidarUser1($parametro['usuario'], $parametro['entidad'], $parametro['item']);
		$datos['cartera_usu'] = 'Cartera';
		$datos['cartera_pass'] = '999999';
		return $datos;
	}
	function login($parametro)
	{
		if (isset($_SESSION['INGRESO'])) {
			session_destroy();
		} 

		session_start();
		if(!empty($_SERVER['HTTP_CLIENT_IP'])) {   
	        $_SESSION['INGRESO']['IP_Wan'] = $_SERVER['HTTP_CLIENT_IP'];   
	    }    
	    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   
	        $_SESSION['INGRESO']['IP_Wan'] = $_SERVER['HTTP_X_FORWARDED_FOR'];   
	    }     
	    else{   
	        $_SESSION['INGRESO']['IP_Wan'] = $_SERVER['REMOTE_ADDR'];   
	    }
		$_SESSION['INGRESO']['PC_MAC'] = "00:00:00:00:00:00";
		$_SESSION['INGRESO']['CARTERA_ITEM'] =  $parametro['item'];
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
			if(count($datos)>0)
			{
				// variables de entidad y usuario logueado en mysql
				$_SESSION['INGRESO']['CodigoU'] = $datos[0]['CI_NIC'] ;
				$_SESSION['INGRESO']['item'] =  $datos[0]['Item'];
				$_SESSION['INGRESO']['Nombre_Completo']=$datos[0]['Nombre_Usuario'];
				$_SESSION['autentificado'] = 'VERDADERO';
				$_SESSION['INGRESO']['IDEntidad'] = $datos[0]['ID_Empresa'];
				$_SESSION['INGRESO']['Entidad_No'] = $datos[0]['ID_Empresa'];
				$_SESSION['INGRESO']['Nombre'] = $datos[0]['Nombre_Usuario'];
				$_SESSION['INGRESO']['Id'] = $datos[0]['CI_NIC'];
				$_SESSION['INGRESO']['Clave'] = $datos[0]['Clave'];
				$_SESSION['INGRESO']['Mail'] = $datos[0]['Usuario'];
				if($datos[0]['Foto']!='.')
				{
			      	$_SESSION['INGRESO']['Foto'] = $datos[0]['Foto'];
		      	}else{
		      		$_SESSION['INGRESO']['Foto'] = 'ejecutivo.png';
		      	}

		      	$this->variables_sistema($parametro['entidad'], $parametro['item']);

				return 1;
			}else
			{
				return -1;
			}
		}
	}


	function variables_sistema($Entidad,$Item)
	{
	   
	    $_SESSION['INGRESO']['LOCAL_SQLSERVER'] = 'NO'; //quitar despues    
	    $_SESSION['INGRESO']['Fecha_Actualizacion'] = '';

	    $empresa = $this->modelo->getEmpresas($Entidad,$Item);
	    // print_r($empresa);die();
	    if (count($empresa) > 0) 
	    {
	        $empresa[0]['Servicio'] = 0;
	        //datos base de mysql
	        $_SESSION['INGRESO']['empresa'] = $empresa[0]['IDEm'].'-'.$empresa[0]['Item'];;
	        $_SESSION['INGRESO']['noempr'] = $empresa[0]['Empresa'];
	    	$_SESSION['INGRESO']['ninguno'] = '.';	  
	        $_SESSION['INGRESO']['PATCH'] = ''; ///solo sirve para colocar la ruta actual de la pagina que se visita
	        $_SESSION['INGRESO']['RUCEnt'] = $empresa[0]['RUC_CI_NIC']; //ruc de la entidad
	        $_SESSION['INGRESO']['Entidad'] = $empresa[0]['Nombre_Entidad'];
	        $_SESSION['INGRESO']['IP_VPN_RUTA'] = $empresa[0]['IP_VPN_RUTA'];
	        $_SESSION['INGRESO']['Base_Datos'] = $empresa[0]['Base_Datos'];
	        $_SESSION['INGRESO']['Usuario_DB'] = $empresa[0]['Usuario_DB'];
	        $_SESSION['INGRESO']['Password_DB'] = $empresa[0]['Contrasena_DB'];
	        $_SESSION['INGRESO']['Tipo_Base'] = $empresa[0]['Tipo_Base'];
	        $_SESSION['INGRESO']['Puerto'] = $empresa[0]['Puerto'];
	        $_SESSION['INGRESO']['Fecha'] = $empresa[0]['Fecha'];
	        $_SESSION['INGRESO']['Logo_Tipo'] = $empresa[0]['Logo_Tipo'];
	        $_SESSION['INGRESO']['periodo'] = '.'; /////////
	        $_SESSION['INGRESO']['Razon_Social'] = $empresa[0]['Razon_Social'];
	        $_SESSION['INGRESO']['Fecha_ce'] = $empresa[0]['Fecha_CE'];
	        $_SESSION['INGRESO']['Fecha_P12'] = $empresa[0]['Fecha_P12'];
	        $_SESSION['INGRESO']['Porc_Serv'] = round($empresa[0]['Servicio'] / 100, 2);
	        $datos = getInfoIPS();
	        $_SESSION['INGRESO']['IP_Local'] = $datos['local_net_address'];
	        $_SESSION['INGRESO']['HOST_NAME'] = $datos['host_name'];	        
	        $_SESSION['INGRESO']['Serie_FA'] = $empresa[0]['Serie_FA'];

	        //datos de empresa seleccionada
	        $empresa = $this->modelo->getEmpresasDE($_SESSION['INGRESO']['item'], $_SESSION['INGRESO']['noempr']);
	        if(count($empresa)<1 || (!isset($empresa[0]['Razon_Social']) || !isset($empresa[0]['Email']) || !isset($empresa[0]['RUC']) )){
	            return array('rps'=> false, 'mensaje' => "Empresa no encontrada, por favor comuníquese con el Administrador del Sistema.");
	        }
	        $this->SeteosCtas();

	        // print_r($empresa);die();



	        $_SESSION['INGRESO']['Moneda'] = $empresa[0]['S_M'];
	        $_SESSION['INGRESO']['OpcCoop'] = $empresa[0]['Opc'];
	        $_SESSION['INGRESO']['NombrePais'] = $empresa[0]['Pais'];
	        $_SESSION['INGRESO']['Web_SRI_Autorizado'] = $empresa[0]['Web_SRI_Autorizado'];
	        $_SESSION['INGRESO']['Web_SRI_Recepcion'] = $empresa[0]['Web_SRI_Recepcion'];
	        $_SESSION['INGRESO']['Direccion'] = $empresa[0]['Direccion'];
	        $_SESSION['INGRESO']['Telefono1'] = $empresa[0]['Telefono1'];
	        $_SESSION['INGRESO']['CodigoDelBanco'] = $empresa[0]['CodBanco'];
	        $_SESSION['INGRESO']['FAX'] = $empresa[0]['FAX'];
	        $_SESSION['INGRESO']['Nombre_Comercial'] = $empresa[0]['Nombre_Comercial'];
	        $_SESSION['INGRESO']['Razon_Social'] = $empresa[0]['Razon_Social'];
	        $_SESSION['INGRESO']['Sucursal'] = $empresa[0]['Sucursal'];
	        $_SESSION['INGRESO']['Opc'] = $empresa[0]['Opc'];
	        $_SESSION['INGRESO']['noempr'] = $empresa[0]['Empresa'];
	        $_SESSION['INGRESO']['S_M'] = $empresa[0]['S_M'];
	        $_SESSION['INGRESO']['Num_Meses_CD'] = $empresa[0]['Num_CD'];
	        $_SESSION['INGRESO']['Num_Meses_CE'] = $empresa[0]['Num_CE'];
	        $_SESSION['INGRESO']['Num_Meses_CI'] = $empresa[0]['Num_CI'];
	        $_SESSION['INGRESO']['Num_Meses_ND'] = $empresa[0]['Num_ND'];
	        $_SESSION['INGRESO']['Num_Meses_NC'] = $empresa[0]['Num_NC'];
	        $_SESSION['INGRESO']['Email_Conexion_CE'] = $empresa[0]['Email_Conexion_CE'];
	        $_SESSION['INGRESO']['Formato_Cuentas'] = $empresa[0]['Formato_Cuentas'];
	        $_SESSION['INGRESO']['Formato_Inventario'] = $empresa[0]['Formato_Inventario'];
	        $_SESSION['INGRESO']['porc'] = $empresa[0]['porc'];
	        $_SESSION['INGRESO']['Ambiente'] = $empresa[0]['Ambiente'];
	        $_SESSION['INGRESO']['Obligado_Conta'] = $empresa[0]['Obligado_Conta'];
	        $_SESSION['INGRESO']['LeyendaFA'] = $empresa[0]['LeyendaFA'];
	        $_SESSION['INGRESO']['LeyendaFAT'] = $empresa[0]['LeyendaFAT'];
	        $_SESSION['INGRESO']['Debo_Pagare'] = $empresa[0]['Debo_Pagare'];
	        $_SESSION['INGRESO']['Email'] = $empresa[0]['Email'];
	        $_SESSION['INGRESO']['RUC'] = $empresa[0]['RUC'];
	        $_SESSION['INGRESO']['Gerente'] = $empresa[0]['Gerente'];
			$_SESSION['INGRESO']['Contador'] = $empresa[0]['Contador'];

	        $_SESSION['INGRESO']['modulo_'] = "";
	        
	        $_SESSION['INGRESO']['Det_Comp'] = $empresa[0]['Det_Comp'];
	        $_SESSION['INGRESO']['Signo_Dec'] = $empresa[0]['Signo_Dec'];
	        $_SESSION['INGRESO']['Signo_Mil'] = $empresa[0]['Signo_Mil'];
	        $_SESSION['INGRESO']['RUC_Contador'] = $empresa[0]['RUC_Contador'];
	        $_SESSION['INGRESO']['CI_Representante'] = $empresa[0]['CI_Representante'];
	        $_SESSION['INGRESO']['Ruta_Certificado'] = $empresa[0]['Ruta_Certificado'];
	        $_SESSION['INGRESO']['Clave_Certificado'] = $empresa[0]['Clave_Certificado'];
	        $_SESSION['INGRESO']['Dec_PVP'] = $empresa[0]['Dec_PVP'];
	        $_SESSION['INGRESO']['Dec_IVA'] = $empresa[0]['Dec_IVA'];
	        $_SESSION['INGRESO']['Dec_Costo'] = $empresa[0]['Dec_Costo'];
	        $_SESSION['INGRESO']['Cotizacion'] = $empresa[0]['Cotizacion'];
	        $_SESSION['INGRESO']['Servicio'] = $empresa[0]['Servicio'];
	        if($_SESSION['INGRESO']['Serie_FA']=='.' && $empresa[0]['Serie_FA']!='.')
	        {
	        	$_SESSION['INGRESO']['Serie_FA'] = $empresa[0]['Serie_FA'];
	        }

	        if (is_object($empresa[0]['Fecha_Igualar'])) {
	            $_SESSION['INGRESO']['Fecha_Igualar'] = $empresa[0]['Fecha_Igualar']->format('Y-m-d');
	        } else {
	            $_SESSION['INGRESO']['Fecha_Igualar'] = $empresa[0]['Fecha_Igualar'];
	        }
	        // print_r($empresa_d);die();
	        $_SESSION['INGRESO']['Ciudad'] = $empresa[0]['Ciudad'];
	        
	        $_SESSION['INGRESO']['accesoe'] = '0';
	        $_SESSION['INGRESO']['Email_Conexion'] = $empresa[0]['Email_Conexion'];
	        $_SESSION['INGRESO']['Email_Procesos'] = $empresa[0]['Email_Procesos'];
	        $_SESSION['INGRESO']['EmailContador'] = $empresa[0]['Email_Contabilidad'];
	        $_SESSION['INGRESO']['Impresora_Rodillo'] = $empresa[0]['Impresora_Rodillo'];

	        $_SESSION['INGRESO']['Email_Contrasena'] = $empresa[0]['Email_Contraseña'];
	        $_SESSION['INGRESO']['smtp_SSL'] = $empresa[0]['smtp_SSL'];
	        $_SESSION['INGRESO']['smtp_UseAuntentificacion'] = $empresa[0]['smtp_UseAuntentificacion'];
	        $_SESSION['INGRESO']['smtp_Puerto'] = $empresa[0]['smtp_Puerto'];
	        $_SESSION['INGRESO']['smtp_Servidor'] = $empresa[0]['smtp_Servidor'];
	        $_SESSION['INGRESO']['RUC_Operadora'] = '.';
	        if (isset($empresa[0]['RUC_Operadora'])) {
	            $_SESSION['INGRESO']['RUC_Operadora'] = $empresa[0]['RUC_Operadora'];
	        }

	        $_SESSION['INGRESO']['paginacionIni'] = 0;
	        $_SESSION['INGRESO']['paginacionFin'] = 100;
	        $_SESSION['INGRESO']['base_actual'] = '';

	        if (isset($empresa[0]['smtp_Secure'])) {
	            $_SESSION['INGRESO']['smtp_Secure'] = $empresa[0]['smtp_Secure'];
	        }

	        $modulos = $this->modelo->modulos_habiliatados();
	        $_SESSION['INGRESO']['modulo'] = $modulos;

	        $_SESSION['INGRESO']['CentroDeCosto'] = $empresa[0]['Centro_Costos'];
	        $_SESSION['INGRESO']['Copia_PV'] = $empresa[0]['Copia_PV'];
	        $_SESSION['INGRESO']['Mod_Fact'] = $empresa[0]['Mod_Fact'];
	        $_SESSION['INGRESO']['Mod_Fecha'] = $empresa[0]['Mod_Fecha'];
	        $_SESSION['INGRESO']['Plazo_Fijo'] = $empresa[0]['Plazo_Fijo'];
	        $_SESSION['INGRESO']['No_Autorizar'] = $empresa[0]['No_Autorizar'];
	        $_SESSION['INGRESO']['Mas_Grupos'] = $empresa[0]['Separar_Grupos'];
	        $_SESSION['INGRESO']['Medio_Rol'] = $empresa[0]['Medio_Rol'];
	        $_SESSION['INGRESO']['Encabezado_PV'] = $empresa[0]['Encabezado_PV'];
	        $_SESSION['INGRESO']['CalcComision'] = $empresa[0]['Calcular_Comision'];
	        $_SESSION['INGRESO']['Grafico_PV'] = $empresa[0]['Grafico_PV'];
	        $_SESSION['INGRESO']['ComisionEjec'] = $empresa[0]['Comision_Ejecutivo'];
	        $_SESSION['INGRESO']['ImpCeros'] = $empresa[0]['Imp_Ceros'];
	        $_SESSION['INGRESO']['Email_CE_Copia'] = $empresa[0]['Email_CE_Copia'];
	        $_SESSION['INGRESO']['Ret_Aut'] = $empresa[0]['Ret_Aut'];
	        $_SESSION['INGRESO']['ConciliacionAut'] = $empresa[0]['Conciliacion_Aut'];

	        //datos del periodo periodo

	        //esto se debe sacar de la entidad ----------------------

	        $periodo = $this->modelo->getPeriodoActualSQL();
	        if (count($periodo) > 0) {
	            $_SESSION['INGRESO']['Fechai'] = $periodo[0]['Fecha_Inicial']->format('Y-m-d');
	            $_SESSION['INGRESO']['Fechaf'] = $periodo[0]['Fecha_Final']->format('Y-m-d');
	        } else {
	            $_SESSION['INGRESO']['Fechai'] = date('Y-m-d');
	            $_SESSION['INGRESO']['Fechaf'] = date('Y-m-d');
	        }
	        // ---------------------------------------
	        $permiso = $this->modelo->getAccesoEmpresas();
	        if(count($permiso)>0)
			{
				foreach ($permiso as $key => $value) {
					// print_r($value);die();
				  $_SESSION['INGRESO']['accesoe']='1';
			    }

			}else
			{
				$_SESSION['INGRESO']['accesoe']='TODOS';
			}


	        //definimos variables, las cuales en el llamado de sp_Iniciar_Datos_Default se actualiza su valor.
	        $_SESSION['INGRESO']['No_ATS'] = 0;
	        $_SESSION['INGRESO']['ListSucursales'] = ".";
	        $_SESSION['INGRESO']['NombreProvincia'] = G_NINGUNO;
	        $_SESSION['INGRESO']['SiUnidadEducativa'] = 0;

	        //INICIO VALIDAMOS SI EL USUARIO TIENE PERMISO DE ACCESO AL SISTEMA
	        //return validacionAcceso($empresa[0]['Empresa'], $_SESSION['INGRESO']['Mail'], $_SESSION['INGRESO']['Clave']);
	        //FIN VALIDAMOS SI EL USUARIO TIENE PERMISO DE ACCESO AL SISTEMA

	        // print_r($_SESSION['INGRESO']);die();

	    } else {
	        $empresa = $this->modelo->getEmpresasId_sin_sqlserver($Entidad,$Item);
	        // print_r($empresa);die();

	        $_SESSION['INGRESO']['IP_VPN_RUTA'] = 'mysql.diskcoversystem.com';
	        $_SESSION['INGRESO']['Base_Datos'] = 'diskcover_empresas';
	        $_SESSION['INGRESO']['Usuario_DB'] = 'diskcover';
	        $_SESSION['INGRESO']['Password_DB'] = 'disk2017Cover';
	        $_SESSION['INGRESO']['Tipo_Base'] = 'My SQL';
	        $_SESSION['INGRESO']['Puerto'] = 13306;
	        //    $this->usuario = 'diskcover';
	        // $this->password =  'disk2017Cover';  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
	        // $this->servidor ='mysql.diskcoversystem.com';
	        // $this->database = 'diskcover_empresas';
	        // $this->puerto = 13306;	 


	        $_SESSION['INGRESO']['Logo_Tipo'] = $empresa[0]['Logo_Tipo'];
	        $_SESSION['INGRESO']['Nombre_Comercial'] = $empresa[0]['Empresa'];
	        $_SESSION['INGRESO']['Razon_Social'] = $empresa[0]['Razon_Social'];
	        $_SESSION['INGRESO']['noempr'] = $empresa[0]['Empresa'];
	        $_SESSION['INGRESO']['RUC'] = $empresa[0]['RUC_CI_NIC']; // ruc de la empresa
	        $_SESSION['INGRESO']['Gerente'] = $empresa[0]['Gerente'];
	        $_SESSION['INGRESO']['Ciudad'] = $empresa[0]['Ciudad'];
	        $_SESSION['INGRESO']['Direccion'] = '';
	        $_SESSION['INGRESO']['Telefono1'] = '';
	        $_SESSION['INGRESO']['FAX'] = '';
	        $_SESSION['INGRESO']['Email'] = '';
	        $_SESSION['INGRESO']['RUCEnt'] = $empresa[0]['RUC_CI_NIC']; //ruc de la entidad
	        $_SESSION['INGRESO']['Entidad'] = $empresa[0]['Nombre_Entidad'];

	        // print_r($_SESSION['INGRESO']);die();

	    }
	}
	function SeteosCtas()
	{
	    // // ' Establecemos Espacios y seteos de impresion
	    $Inv_Promedio = False;
	    $PVP_Al_Inicio = False;
	    // // ' Cta_Ret = "0"
	// //$_SESSION['SETEOS'][' ' Cta_Ret_IVA = "0"
	    $_SESSION['SETEOS']['Cta_IVA'] = "0";
	    $_SESSION['SETEOS']['Cta_IVA_Inventario'] = "0";
	    $_SESSION['SETEOS']['Cta_CxP_Retenciones'] = "0";
	    $_SESSION['SETEOS']['Cta_CxP_Propinas'] = "0";
	    $_SESSION['SETEOS']['Cta_Desc'] = "0";
	    $_SESSION['SETEOS']['Cta_Desc2'] = "0";
	    $_SESSION['SETEOS']['Cta_CajaG'] = "0";
	    $_SESSION['SETEOS']['Cta_General'] = "0";
	    $_SESSION['SETEOS']['Cta_CajaGE'] = "0";
	    $_SESSION['SETEOS']['Cta_CajaBA'] = "0";
	    $_SESSION['SETEOS']['Cta_Gastos'] = "0";
	    $_SESSION['SETEOS']['Cta_Diferencial'] = "0";
	    $_SESSION['SETEOS']['Cta_Comision'] = "0";
	    $_SESSION['SETEOS']['Cta_Mantenimiento'] = "0";
	    $_SESSION['SETEOS']['Cta_Fondo_Mortuorio'] = "0";
	    $_SESSION['SETEOS']['Cta_Tarjetas'] = "0";
	    $_SESSION['SETEOS']['Cta_Del_Banco'] = "0";
	    $_SESSION['SETEOS']['Cta_Seguro'] = "0";
	    $_SESSION['SETEOS']['Cta_Seguro_I'] = "0";
	    $_SESSION['SETEOS']['Cta_Proveedores'] = "0";
	    $_SESSION['SETEOS']['Cta_Anticipos'] = "0";    
	    $_SESSION['SETEOS']['Cta_Ret_Egreso'] = "0";
    	$_SESSION['SETEOS']['Cta_Provision_Compras'] = "0";
	    // 	// ' Consultamos las cuentas de la tabla
	    $datos = $this->modelo->SeteoCta();

	    // print_r($datos);die();

	    if (count($datos) > 0) {
	        $Cadena = '';
	        foreach ($datos as $key => $value) {
	            $Cadena .= $value["Detalle"];
	            switch ($value["Detalle"]) {
	                // case "Cta_Ret_IVA":
	                // '''Cta_Ret_IVA = .Fields("Codigo")
	                // break;
	                case "Cta_IVA":
	                    $_SESSION['SETEOS']['Cta_IVA'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_IVA'] = $value['DC'];
	                    break;
	                case "Cta_Descuentos":
	                    $_SESSION['SETEOS']['Cta_Desc'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_Desc'] = $value['DC'];
	                    break;
	                case "Cta_Descuentos_Pronto_Pago":
	                    $_SESSION['SETEOS']['Cta_Desc2'] = $value['Codigo'];
	                    break;
	                case "Cta_Caja_General":
	                    $_SESSION['SETEOS']['Cta_General'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_General'] = $value['DC'];
	                    break;
	                case "Cta_Caja_GMN":
	                    $_SESSION['SETEOS']['Cta_CajaG'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_CajaG'] = $value['DC'];
	                    break;
	                case "Cta_Caja_GME":
	                    $_SESSION['SETEOS']['Cta_CajaGE'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_CajaGE'] = $value['DC'];
	                    break;
	                case "Cta_Caja_BAU":
	                    $_SESSION['SETEOS']['Cta_CajaBA'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_CajaBA'] = $value['DC'];
	                    break;
	                case "Cta_Gastos":
	                    $_SESSION['SETEOS']['Cta_Gastos'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_Gastos'] = $value['DC'];
	                    break;
	                case "Cta_Diferencial_Cambiario":
	                    $_SESSION['SETEOS']['Cta_Diferencial'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_Diferencial'] = $value['DC'];
	                    break;
	                case "Cta_SubTotal":
	                    $_SESSION['SETEOS']['Cta_SubTotal'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_SubTotal'] = $value['DC'];
	                    break;
	                case "Cta_Comision":
	                    $_SESSION['SETEOS']['Cta_Comision'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_Comision'] = $value['DC'];
	                    break;
	                case "Cta_Faltantes":
	                    $_SESSION['SETEOS']['Cta_Faltantes'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_Faltantes'] = $value['DC'];
	                    break;
	                case "Cta_Protestos":
	                    $_SESSION['SETEOS']['Cta_Protestos'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_Protestos'] = $value['DC'];
	                    break;
	                case "Cta_Sobrantes":
	                    $_SESSION['SETEOS']['Cta_Sobrantes'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_Sobrantes'] = $value['DC'];
	                    break;
	                case "Cta_Suspenso":
	                    $_SESSION['SETEOS']['Cta_Suspenso'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_Suspenso'] = $value['DC'];
	                    break;
	                case "Cta_Libretas":
	                    $_SESSION['SETEOS']['Cta_Libretas'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_Libretas'] = $value['DC'];
	                    break;
	                case "Cta_Certificado":
	                    $_SESSION['SETEOS']['Cta_Certificado'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_Certificado'] = $value['DC'];
	                    break;
	                case "Cta_Certificado_Aportacion":
	                    $_SESSION['SETEOS']['Cta_Certificado_Apor'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_Certificado_Apor'] = $value['DC'];
	                    break;
	                case "Cta_Apertura":
	                    $_SESSION['SETEOS']['Cta_Apertura'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_Apertura'] = $value['DC'];
	                    break;
	                case "Cta_Transito":
	                    $_SESSION['SETEOS']['Cta_Transito'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_Transito'] = $value['DC'];
	                    break;
	                case "Cta_Cheque_Transito":
	                    $_SESSION['SETEOS']['Cta_Cheque_Transito'] = $value['Codigo'];
	                    break;
	                case "Cta_IVA_Inventario":
	                    $_SESSION['SETEOS']['Cta_IVA_Inventario'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_IVA_Inventario'] = $value['DC'];
	                    break;
	                case "Cta_CxP_Retenciones":
	                    $_SESSION['SETEOS']['Cta_CxP_Retenciones'] = $value['Codigo'];
	                    break;
	                case "Cta_CxP_Propinas":
	                    $_SESSION['SETEOS']['Cta_CxP_Propinas'] = $value['Codigo'];
	                    break;
	                case "Cta_Inventario":
	                    $_SESSION['SETEOS']['Cta_Inventario'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_Inventario'] = $value['DC'];
	                    break;
	                case "Cta_Mantenimiento":
	                    $_SESSION['SETEOS']['Cta_Mantenimiento'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_Mantenimiento'] = $value['DC'];
	                    break;
	                case "Cta_Fondo_Mortuorio":
	                    $_SESSION['SETEOS']['Cta_Fondo_Mortuorio'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_Fondo_Mortuorio'] = $value['DC'];
	                    break;
	                case "Cta_Servicios_Basicos":
	                    $_SESSION['SETEOS']['Cta_Servicios_Basicos'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_Servicios_Basicos'] = $value['DC'];
	                    break;
	                case "Cta_Servicio":
	                    $_SESSION['SETEOS']['Cta_Servicio'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_Servicio'] = $value['DC'];
	                    break;
	                case "Cta_Intereses":
	                    $_SESSION['SETEOS']['Cta_Interes'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_Interes'] = $value['DC'];
	                    break;
	                case "Cta_Intereses1":
	                    $_SESSION['SETEOS']['Cta_Interes1'] = $value['Codigo'];
	                    $_SESSION['SETEOS']['DC_Interes1'] = $value['DC'];
	                    break;
	                case "Cta_CxP_Tarjetas":
	                    $_SESSION['SETEOS']['Cta_Tarjetas'] = $value['Codigo'];
	                    break;
	                case "Cta_Caja_Vaucher":
	                    $_SESSION['SETEOS']['Cta_Caja_Vaucher'] = $value['Codigo'];
	                    break;
	                case "Cta_Banco":
	                    $_SESSION['SETEOS']['Cta_Del_Banco'] = $value['Codigo'];
	                    break;
	                case "Cta_Seguro_Desgravamen":
	                    $_SESSION['SETEOS']['Cta_Seguro'] = $value['Codigo'];
	                    break;
	                case "Cta_Impuesto_Renta_Empleado":
	                    $_SESSION['SETEOS']['Cta_Impuesto_Renta_Empleado'] = $value['Codigo'];
	                    break;
	                case "Cta_Seguro_Ingreso":
	                    $_SESSION['SETEOS']['Cta_Seguro_I'] = $value['Codigo'];
	                    break;
	                case "Cta_Proveedores":
	                    $_SESSION['SETEOS']['Cta_Proveedores'] = $value['Codigo'];
	                    break;
	                case "Cta_Anticipos":
	                    $_SESSION['SETEOS']['Cta_Anticipos'] = $value['Codigo'];
	                    break;
	                case 'Cta_Ret_Egreso':
	                     $_SESSION['SETEOS']['Cta_Ret_Egreso'] = $value['Codigo'];
	                    break;
	                case 'Cta_Provision_Compras':
                    	 $_SESSION['SETEOS']['Cta_Provision_Compras'] = $value['Codigo'];
                    	break;
	                case "Inv_Promedio":
	                    if ($value['Codigo'] == "TRUE") {
	                        $_SESSION['SETEOS']['Inv_Promedio'] = True;
	                    }
	                    break;
	                case "PVP_Al_Inicio":
	                    if ($value['Codigo'] == "TRUE") {
	                        $PVP_Al_Inicio = True;
	                    }
	                    break;
	            }
	        }

	    }

	    $SSQLSeteos = "";
	    // 'If Cta_Ret = "0" Then SSQLSeteos = SSQLSeteos & "Cta_Ret_Ingreso" & vbCrLf
	    if ($_SESSION['SETEOS']['Cta_IVA'] == "0") {
	        $SSQLSeteos .= "Cta_IVA ,";
	    }
	    if ($_SESSION['SETEOS']['Cta_Desc'] == "0") {
	        $SSQLSeteos .= "Cta_Descuentos ,";
	    }
	    if ($_SESSION['SETEOS']['Cta_Desc2'] == "0") {
	        $SSQLSeteos .= "Cta_Descuentos_Pronto_Pago ,";
	    }
	    if ($_SESSION['SETEOS']['Cta_CajaG'] == "0") {
	        $SSQLSeteos .= "Cta_Caja_GMN ,";
	    }
	    if ($_SESSION['SETEOS']['Cta_General'] == "0") {
	        $SSQLSeteos .= "Cta_Caja_General ,";
	    }
	    if ($_SESSION['SETEOS']['Cta_CajaGE'] == "0") {
	        $SSQLSeteos .= "Cta_Caja_GME ,";
	    }
	    if ($_SESSION['SETEOS']['Cta_CajaBA'] == "0") {
	        $SSQLSeteos .= "Cta_Caja_VAU ,";
	    }
	    if ($_SESSION['SETEOS']['Cta_Gastos'] == "0") {
	        $SSQLSeteos .= "Cta_Gastos ,";
	    }
	    if ($_SESSION['SETEOS']['Cta_Diferencial'] == "0") {
	        $SSQLSeteos .= "Cta_Diferencial_Cambiario ,";
	    }
	    if ($_SESSION['SETEOS']['Cta_IVA_Inventario'] == "0") {
	        $SSQLSeteos .= "Cta_IVA_Inventario ,";
	    }
	    if ($_SESSION['SETEOS']['Cta_Proveedores'] == "0") {
	        $SSQLSeteos .= "Cta_Proveedores ,";
	    }
	    if ($SSQLSeteos <> "") {
	        $SSQLSeteos = "Verifique el codigo de:" . $SSQLSeteos . "La proxima vez que ejecute el sistema se crearan estas cuentas ";

	        // print_r($SSQLSeteos);die();
	        return $SSQLSeteos;
	        // ' MsgBox SSQLSeteos
	        // ' CtasSeteos AdoRec
	    }
	}

	function logout()
	{
		session_destroy();
		$_SESSION = array();  // Limpia todas las variables de la sesión
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
			if (filter_var($parametro['usuario'], FILTER_VALIDATE_EMAIL)) {				
				$datos = $this->modelo->datos_usuario_mysql(false, $entidad = false,$parametro['usuario']);
			}else{
				$datos = $this->modelo->datos_usuario_mysql($parametro['usuario'], $entidad = false,false);
			}
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

		$email_conexion = 'informacion@diskcoversystem.com';
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