<?php
@session_start();
//Llamada al modelo
require_once("../modelo/usuario_model.php");
include_once("../funciones/funciones.php");
require_once(dirname(__DIR__, 2) . '/lib/phpmailer/enviar_emails.php');
/**
 * Mail:  diskcover@msn.com
 * web:   www.diskcoversystem.com
 * distribuidor: PrismaNet Profesional S.A.
 * obse: 1.- esta controlador desde un inicio no fue una clase;
 *       2.- se usa el isset si se esta llamdo por ajax
 *       3.- las fuciones realizadas se estan llamando desde otro php (tener cuidado si se quiere borrar algo)
 * 
 * modificcado por javier farinango
 * 
 * 
 */

if (isset($_GET['pantalla'])) {
    $_SESSION['INGRESO']['Height_pantalla'] = $_GET['height'];
    $_SESSION['INGRESO']['width_pantalla'] = $_GET['width'];
}
if (isset($_GET['paginacion'])) {
    $_SESSION['INGRESO']['paginacionIni'] = $_GET['ini'];
    $_SESSION['INGRESO']['numreg'] = $_GET['numreg'];
}
if (isset($_GET['IngClaves'])) {
    $parametros = $_POST['parametros'];
    // print_r($parametro);die();
    echo json_encode(IngClaves($parametros));
}

if(isset($_GET['IngClaveCredenciales'])) {
    $usuario = $_POST['usuario'];
    echo json_encode(IngClaveCredenciales($usuario));
}

if (isset($_GET['IngClaves_MYSQL'])) {
    $parametros = $_POST['parametros'];
    // print_r($parametro);die();
    echo json_encode(IngClaves_MYSQL($parametros));
}

if (isset($_GET['salir_empresa'])) {
    // print_r('expression');die();
    echo json_encode(eliminar_variables());
}

if (isset($_GET['validateStar'])) {
    extract($_POST);
    echo json_encode(Ver_Grafico_FormPict($NumModulo));
}
if (isset($_GET['validar_session_Activa']))
{
    if(!isset($_SESSION['INGRESO']))
    {
       echo json_encode(-1);
    }else{
       echo json_encode(1);
    }
}

if (isset($_GET['ConfirmacionComunicado'])) {
    extract($_POST);
    echo json_encode(ConfirmacionComunicado($NumModulo, $SeguirMostrando));
}
if (isset($_GET['control_errores'])) {
    $query = $_POST['parametros'];
    echo json_encode(control_errores($query));
}

if (isset($_GET['validar_estado'])) {
    echo json_encode(validar_estado_all());
}

if(isset($_GET['cargar_imagen']))
{
    $file = $_FILES;
    echo json_encode(guardar_img($file));
}

function guardar_img($file)
{

    $modelo = new usuario_model();
    $ruta= dirname(__DIR__,2).'/img/usuarios/';//ruta carpeta donde queremos copiar las imágenes
    if (!file_exists($ruta)) {
       mkdir($ruta, 0777, true);
    }
    if(validar_formato_img($file)==1)
    {
         $uploadfile_temporal=$file['file_img']['tmp_name'];
         $tipo = explode('/', $file['file_img']['type']);
         $nombre = $_SESSION['INGRESO']['CodigoU'].'.'.$tipo[1];            
         $nuevo_nom=$ruta.$nombre;
         if (is_uploaded_file($uploadfile_temporal))
         {
           move_uploaded_file($uploadfile_temporal,$nuevo_nom);
            $base = $modelo->editar_foto($nombre);
           if($base==1)
           {
            return $nombre;
           }else
           {
            return -1;
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


function IngClaves($parametros)
{
    // print_r($_SESSION['INGRESO']);die();
    $mensaje = '';
    $resultado = -1;
    $intentos = $parametros['intentos'];

    $per = new usuario_model();
    if ($parametros['pass'] == '') {
        $mensaje = 'Ingrese una clave valida';

    } else {
        $clave = $per->IngClave($parametros);
        // print_r($clave);
        // print_r($parametros);
        // die();
        if ($parametros['pass'] == $clave['clave'] and $parametros['intentos'] < 3) {
            $resultado = 1;
        } else if ($parametros['intentos'] >= 3) {
            $mensaje = "Sr(a). " . $_SESSION['INGRESO']['Nombre'] . ": \n 
             Usted no está autorizado \n
             a ingresar a esta opción.";
            $intentos = $parametros['intentos'] + 1;

        } else {
            $mensaje = "Sr(a). " . $_SESSION['INGRESO']['Nombre'] . ": \n  Clave incorrecta,";
            $intentos = $parametros['intentos'] + 1;

        }

        return array('msj' => $mensaje, 'respuesta' => $resultado, 'intentos' => $intentos);
    }

}


function datos_modulo($cod)
{
    $per = new usuario_model();
    $datos = $per->detalle_modulos($cod);
    return $datos;
}

function IngClaveCredenciales($usuario){
    $per = new usuario_model();
    $datos = $per->IngClaveCredenciales($usuario);
    if(count($datos) > 0){
        return array("res" => 1, "nombre" => $datos[0]['Nombre_Completo']);
    }else{
        return array("res" => 0);
    }
}

function IngClaves_MYSQL($parametros)
{
    // print_r($_SESSION['INGRESO']);die();

    // print_r($parametros);die();
    $mensaje = '';
    $resultado = -1;
    $intentos = $parametros['intentos'];

    $per = new usuario_model();
    if ($parametros['pass'] == '') {
        $mensaje = 'Ingrese una clave valida';

    } else {
        $clave = $per->IngClave_MYSQL($parametros);
        // print_r($clave);
        // print_r($parametros);
        // die();
        if ($parametros['pass'] == $clave['clave'] and $parametros['intentos'] < 3) {
            $resultado = 1;
        } else if ($parametros['intentos'] >= 3) {
            $mensaje = "Sr(a). " . $_SESSION['INGRESO']['Nombre'] . ": \n 
                   Usted no está autorizado \n
                   a ingresar a esta opción.";
            $intentos = $parametros['intentos'] + 1;

        } else {
            $mensaje = "Sr(a). " . $_SESSION['INGRESO']['Nombre'] . ": \n  Clave incorrecta,";
            $intentos = $parametros['intentos'] + 1;

        }

        return array('msj' => $mensaje, 'respuesta' => $resultado, 'intentos' => $intentos);
    }

}


function SeteosCtas()
{
    $modelo = new usuario_model();
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
    // 	// ' Consultamos las cuentas de la tabla
    $datos = $modelo->SeteoCta();

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


function variables_sistema($EmpresaEntidad, $NombreEmp, $ItemEmp)
{
    $_SESSION['INGRESO']['empresa'] = $EmpresaEntidad;
    $_SESSION['INGRESO']['noempr'] = $NombreEmp;
    $_SESSION['INGRESO']['item'] = $ItemEmp;
    $_SESSION['INGRESO']['ninguno'] = '.';

    $_SESSION['INGRESO']['LOCAL_SQLSERVER'] = 'NO'; //quitar despues    
    $_SESSION['INGRESO']['Fecha_Actualizacion'] = '';


    $cod = explode('-', $EmpresaEntidad);
    $empresa = getEmpresasId($cod[0]);
    // print_r($empresa);die();
    if (count($empresa) > 0) {
        $empresa[0]['Servicio'] = 0;
        //datos base de mysql
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

        //datos de empresa seleccionada
        $empresa = getEmpresasDE($_SESSION['INGRESO']['item'], $_SESSION['INGRESO']['noempr']);
        if(count($empresa)<1 || (!isset($empresa[0]['Razon_Social']) || !isset($empresa[0]['Email']) || !isset($empresa[0]['RUC']) )){
            return array('rps'=> false, 'mensaje' => "Empresa no encontrada, por favor comuníquese con el Administrador del Sistema.");
        }
        SeteosCtas();

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
        $_SESSION['INGRESO']['Email'] = $empresa[0]['Email'];
        $_SESSION['INGRESO']['RUC'] = $empresa[0]['RUC'];
        $_SESSION['INGRESO']['Gerente'] = $empresa[0]['Gerente'];

        $_SESSION['INGRESO']['modulo_'] = "33";
        
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

        $_SESSION['INGRESO']['Serie_FA'] = $empresa[0]['Serie_FA'];
        $_SESSION['INGRESO']['modulo'] = modulos_habiliatados();

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

        $periodo = getPeriodoActualSQL();
        if (count($periodo) > 0) {
            $_SESSION['INGRESO']['Fechai'] = $periodo[0]['Fecha_Inicial']->format('Y-m-d');
            $_SESSION['INGRESO']['Fechaf'] = $periodo[0]['Fecha_Final']->format('Y-m-d');
        } else {
            $_SESSION['INGRESO']['Fechai'] = date('Y-m-d');
            $_SESSION['INGRESO']['Fechaf'] = date('Y-m-d');
        }
        // ---------------------------------------
        $permiso = getAccesoEmpresas();

        //definimos variables, las cuales en el llamado de sp_Iniciar_Datos_Default se actualiza su valor.
        $_SESSION['INGRESO']['No_ATS'] = false;
        $_SESSION['INGRESO']['ListSucursales'] = "";
        $_SESSION['INGRESO']['NombreProvincia'] = G_NINGUNO;
        $_SESSION['INGRESO']['SiUnidadEducativa'] = false;

        //INICIO VALIDAMOS SI EL USUARIO TIENE PERMISO DE ACCESO AL SISTEMA
        //return validacionAcceso($empresa[0]['Empresa'], $_SESSION['INGRESO']['Mail'], $_SESSION['INGRESO']['Clave']);
        //FIN VALIDAMOS SI EL USUARIO TIENE PERMISO DE ACCESO AL SISTEMA

    } else {
        $modelo = new usuario_model();
        $empresa = $modelo->getEmpresasId_sin_sqlserver($cod[0]);
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

function eliminar_variables()
{
    //destruimos la sesion
    unset($_SESSION['INGRESO']['empresa']);
    unset($_SESSION['INGRESO']['noempr']);
    unset($_SESSION['INGRESO']['modulo_']);
    unset($_SESSION['INGRESO']['accion']);
    unset($_SESSION['INGRESO']['IP_VPN_RUTA']);
    unset($_SESSION['INGRESO']['Base_Datos']);
    unset($_SESSION['INGRESO']['Usuario_DB']);
    unset($_SESSION['INGRESO']['Password_DB']);
    unset($_SESSION['INGRESO']['Tipo_Base']);
    unset($_SESSION['INGRESO']['Puerto']);
    unset($_SESSION['INGRESO']['Fecha']);
    unset($_SESSION['INGRESO']['Fechai']);
    unset($_SESSION['INGRESO']['Fechaf']);
    unset($_SESSION['INGRESO']['Logo_Tipo']);
    unset($_SESSION['INGRESO']['Razon_Social']);
    unset($_SESSION['INGRESO']['Direccion']);
    unset($_SESSION['INGRESO']['Telefono1']);
    unset($_SESSION['INGRESO']['FAX']);
    unset($_SESSION['INGRESO']['Nombre_Comercial']);
    unset($_SESSION['INGRESO']['Razon_Social']);
    unset($_SESSION['INGRESO']['S_M']);
    unset($_SESSION['INGRESO']['porc']);
    //eliminar permisos
    unset($_SESSION['INGRESO']['accesoe']);
    unset($_SESSION['INGRESO']['modulo']);
    unset($_SESSION['INGRESO']['Serie_FA']);
    return 1;
}

//devuelve empresas asociadas al usuario  * modificado: javier fainango.
function getEmpresas($id_entidad, $cartera = false)
{
    $per = new usuario_model();
    $empresa = $per->getEmpresas($id_entidad, $cartera);
    // print_r($empresa);die();
    return $empresa;
}
//devuelve empresas seleccionada por el usuario ---* modificado: javier fainango.
function getEmpresasId($id_empresa)
{
    $modelo = new usuario_model();
    $empresa = $modelo->getEmpresasId($id_empresa);
    // print_r($empresa);die();
    // print_r($_SESSION); die();
    return $empresa;
}
//devuelve empresas seleccionada por el usuario de mysql sin credenciales sqlserver
function empresa_sin_creenciales_sqlserver($id_empresa)
{
    //echo ' dd '.$id_empresa;
    $per = new usuario_model();
    $empresa = $per->getEmpresasId_sin_sqlserver($id_empresa);
    // print_r($empresa);die();
    // print_r($_SESSION); die();
    return $empresa;
}
//devuelve inf del detalle de la empresa seleccionada por el usuario -------* modificado: javier fainango.
function getEmpresasDE($item, $nombre)
{
    $modelo = new usuario_model();
    $datos = $modelo->datos_empresa($item, $nombre);
    // print_r($datos);die();
    return $datos;
}
//perido actual funcion sql server --* modificado: javier fainango.
function getPeriodoActualSQL()
{
    $modulo = new usuario_model();
    $periodo = $modulo->get_periodo();
    return $periodo;
}

//obtener datos de usuario  
function getUsuario()
{
    //echo ' dd '.$id_empresa;
    if (isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base'] == 'SQL SERVER') {
        $per = new usuario_model();
        //hacemos conexion en sql
        $per->conexionSQL();
        $empresa = $per->getUsuarioSQL();
    }
    //mysql
    if (isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base'] == 'MySQL') {
        //echo ' sss '.$_SESSION['INGRESO']['Tipo_Base'];
        $per = new usuario_model();

        $empresa = $per->getUsuarioMYSQL();
    }

    return $empresa;
}


//verificar acceso usuario ------  * modificado: javier fainango.
function getAccesoEmpresas()
{
    $modelo = new usuario_model();
    $modelo->getAccesoEmpresasSQL();
}


//consultar modulo
function getModulo()
{
    //echo ' dd '.$id_empresa;
    if (isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base'] == 'SQL SERVER') {
        $per = new usuario_model();
        //hacemos conexion en sql
        $per->conexionSQL();
        $empresa = $per->getModuloSQL();
    }
    //mysql
    if (isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base'] == 'MySQL') {
        //echo ' sss '.$_SESSION['INGRESO']['Tipo_Base'];
        $per = new usuario_model();

        $empresa = $per->getModuloMYSQL();
    }

    return $empresa;
}

function modulos_habiliatados()
{
    $per = new usuario_model();
    $modulos = $per->modulos_registrados();
    return $modulos;

}

function contruir_modulos($modulos)
{
    $mod = "";
    $color = array('1' => 'bg-green', '2' => 'bg-yellow', '3' => 'bg-red', '4' => 'bg-aqua');

    $pos = 1;
    foreach ($modulos as $key => $value) {
        // print_r($value);die();
        $link = '';
        if ($value['link'] == '.') {
            $link = 'onclick="no_modulo();"';
        } else {
            $link = 'href="' . $value['link'] . '"';
        }
        $mod .= '<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box ' . $color[$pos] . '"  style="border-radius: 10px;">
            <div class="inner"><a ' . $link . ' style="color: #ffffff;">';
        if (strlen($value['apli']) < 9) {
            $mod .= '<h4><b>' . $value['apli'] . '</b></h4>';
        } else {
            $mod .= '<h4><b>' . $value['apli'] . '</b></h4>';

        }
        $mod .= '<p>Modulo</p>
              </a>
            </div>
            <div class="icon">';
        if ($value['icono'] != '.') {
            $mod .= '<i class="ion ion" style="padding-right: 15px;"><img  class="style_prevu_kit" src="' . $value['icono'] . '" class="icon" style="display:block;width:100%;margin-top: 35%;"></i>';
        } else {
            $mod .= '<i class="ion ion" style="padding-right: 15px;width: 80px;"></i>';
        }

        $mod .= '</div>
            <a ' . $link . ' class="small-box-footer">Click para ingresar <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>';
        $pos += 1;
        if ($pos == 5) {
            $pos = 1;
        }

    }
    return $mod;

}
function contruir_todos_modulos()
{

    $per = new usuario_model();
    $modulos = $per->modulos_todos();
    $mod = "";
    $color = array('1' => 'bg-green', '2' => 'bg-yellow', '3' => 'bg-red', '4' => 'bg-aqua');
    $pos = 1;
    foreach ($modulos as $key => $value) {
        $mod .= '<div class="col-lg-3 col-xs-6">
		<a href="' . $value['link'] . '">
          <!-- small box -->
          <div class="small-box ' . $color[$pos] . '" style="border-radius: 10px;">
            <div class="inner"><a href="' . $value['link'] . '" style="color: #ffffff;">';
        if (strlen($value['Aplicacion']) < 9) {
            $mod .= '<h3>' . $value['Aplicacion'] . '</h3>';
        } else {
            $mod .= '<h4 style="font-size: 30px;"><b>' . $value['Aplicacion'] . '</b></h4>';
        }

        $mod .= '<p>Modulo</p>
           <a>
            </div>
            <div class="icon">';
        if ($value['icono'] != '.') {
            $mod .= '<i class="ion ion-plus" style="padding-right: 15px;"><img src="' . $value['icono'] . '" class="icon" style="display:block;width:85%;margin-top: 35%;"></i>';
        } else {
            $mod .= '<i class="ion ion-plus"></i>';
        }

        $mod .= '</div>
          </a>
        </div>';
        $pos = $pos + 1;
        if ($pos == 5) {
            $pos = 1;
        }

    }
    return $mod;

    //style="display:block; height:80%; width:100%;"

}


function validacionAcceso($nombreEmpresa, $Usuario, $Clave)
{
    $conn = new db();
    $sSQL = "SELECT " . Full_Fields("Empresas") . " "
        . "FROM Empresas "
        . "WHERE Empresa = '" . $nombreEmpresa . "' ";
    $empresa = $conn->datos($sSQL);

    $sSQL = "SELECT " . Full_Fields("Accesos") . " "
        . "FROM Accesos "
        . "WHERE UPPER(Usuario) = '" . strtoupper($Usuario) . "' "
        . "AND UPPER(Clave) = '" . strtoupper($Clave) . "' ";
    $dataUser = $conn->datos($sSQL);

    // print_r($empresa);
    // print_r($dataUser);die();
    //*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
    //Asignacion de correos automáticos para envio a procesos automatizados
    $Lista_De_Correos = array();
    for ($i = 0; $i < 7; $i++) {
        $Lista_De_Correos[$i] = array(
            'Correo_Electronico' => CorreoDiskCover,
            'Contraseña' => ContrasenaDiskCover
        );
    }

    if (strlen($empresa[0]["Email_Conexion"]) > 1 && strlen($empresa[0]["Email_Contraseña"]) > 1) {
        $Lista_De_Correos[0]['Correo_Electronico'] = $empresa[0]["Email_Conexion"];
        $Lista_De_Correos[0]['Contraseña'] = $empresa[0]["Email_Contraseña"];
    }

    if (strlen($empresa[0]["Email_Conexion_CE"]) > 1 && strlen($empresa[0]["Email_Contraseña_CE"]) > 1) {
        $Lista_De_Correos[4]['Correo_Electronico'] = $empresa[0]["Email_Conexion_CE"];
        $Lista_De_Correos[4]['Contraseña'] = $empresa[0]["Email_Contraseña_CE"];
    }

    $Lista_De_Correos[6]['Correo_Electronico'] = 'credenciales@diskcoversystem.com';
    $Lista_De_Correos[6]['Contraseña'] = 'Dlcjvl1210@Credenciales';

    //|--=:******* CONECCON A MYSQL *******:=--|
    global $Fecha_CO, $Fecha_CE, $Fecha_VPN, $Fecha_DB, $Fecha_P12, $AgenteRetencion, $MicroEmpresa, $EstadoEmpresa, $DescripcionEstado, $NombreEntidad, $RepresentanteLegal, $MensajeEmpresa, $ComunicadoEntidad, $SerieFE, $Cartera, $Cant_FA, $TipoPlan, $PCActivo, $EstadoUsuario, $ConexionConMySQL;
    Datos_Iniciales_Entidad_SP_MySQL($empresa[0], $dataUser[0]);
    //|--=:******* --------.------- *******:=--|
    if ($ConexionConMySQL) {
        if ($empresa[0]["Estado"] != $EstadoEmpresa || $empresa[0]["Cartera"] != $Cartera || $empresa[0]["Cant_FA"] != $Cant_FA || $empresa[0]["Fecha_CE"]->format("Y-m-d") != $Fecha_CE || $empresa[0]["Fecha_P12"]->format("Y-m-d") != $Fecha_P12 || $empresa[0]["Tipo_Plan"] != $TipoPlan || $empresa[0]["Serie_FA"] != $SerieFE) {
            $sql = "UPDATE Empresas
                  SET Cartera = '$Cartera',
                  Cant_FA = '$Cant_FA', 
                  Fecha_CE = '$Fecha_CE', 
                  Fecha_P12 = '$Fecha_P12', 
                  Tipo_Plan = '$TipoPlan', 
                  Estado = '$EstadoEmpresa', 
                  Serie_FA = '$SerieFE'
                  WHERE ID = '" . $empresa[0]["ID"] . "'";
            $conn->String_Sql($sql);
        }
    }
    if (!$PCActivo) {
        $Cadena = $dataUser[0]['Nombre_Completo'] . "\nSu Equipo se encuentra en LISTA NEGRA, ingreso no autorizado, comuniquese con el Administrador del Sistema";
        unset($_SESSION['INGRESO']['IDEntidad']);
        unset($_SESSION['INGRESO']['empresa']);
        return array('rps' => false, "mensaje" => $Cadena);
    }
    if (!$EstadoUsuario) {
        $Cadena = $dataUser[0]['Nombre_Completo'] . "\nSu ingreso no esta autorizado, comuniquese con el Administrador del Sistema";
        unset($_SESSION['INGRESO']['IDEntidad']);
        unset($_SESSION['INGRESO']['empresa']);
        return array('rps' => false, "mensaje" => $Cadena);
    }

    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // Actualiza Datos iniciales de la Empresa
    $NumEmpresa = $empresa[0]['Item'];
    $Periodo_Contable = G_NINGUNO;
    $Dolar = 0;
    $RUCEmpresa = $empresa[0]['RUC'];
    $CodigoUsuario = $_SESSION['INGRESO']['CodigoU'];

    $EmailEmpresa = $empresa[0]["Email"];
    $EmailContador = $empresa[0]["Email_Contabilidad"];
    $EmailProcesos = $empresa[0]["Email_Procesos"];
    $Email_CE_Copia = (bool) $empresa[0]["Email_CE_Copia"];
    $NumModulo = "00";

    sp_Iniciar_Datos_Default($NumEmpresa, $Periodo_Contable, $Dolar, $RUCEmpresa, $CodigoUsuario, $Fecha_CE, $NumModulo);

    // Resultado del SP del MySQL
    $ListaFacturas = "";
    $TMail = new stdClass();
    $TMail->Subject = "CARTERA VENCIDA";
    $Evaluar = false;
    if ($Cartera != 0 && $Cant_FA != 0) {
        $ListaFacturas = "ESTIMADO " . strtoupper($Empresa) . ", SE LE COMUNICA QUE USTED MANTIENE UNA CARTERA VENCIDA DE USD " . number_format($Cartera, 2, '.', ',') . ", EQUIVALENTE A " . $Cant_FA . " FACTURA(S) EMITIDA(S) A USTED." . PHP_EOL;
    }

    switch ($EstadoEmpresa) {
        case "VEN30":
            $ListaFacturas .= "<b>PRIMER COMUNICADO DE ADVERTENCIA:</b><br> SU EMPRESA ESTA POR SER BLOQUEADA POR CARTERA DE 30 DIAS DE VENCIMIENTO, ";
            break;
        case "VEN60":
            $ListaFacturas .= "<b>SEGUNDO COMUNICADO DE ADVERTENCIA:</b><br> SU EMPRESA ESTA POR SER BLOQUEADA POR CARTERA DE 60 DIAS DE VENCIMIENTO, ";
            break;
        case "VEN90":
            $ListaFacturas .= "<b>TERCER COMUNICADO DE ADVERTENCIA:</b><br> SU EMPRESA ESTA POR SER BLOQUEADA POR CARTERA DE 90 DIAS DE VENCIMIENTO, ";
            break;
        case "VEN360":
            $ListaFacturas .= "<b>SU EMPRESA ESTA BLOQUEADA POR CARTERA DE 360 DIAS DE VENCIMIENTO</b><br> ";
            $TMail->Subject = "EMPRESA BLOQUEADA POR VENCIMIENTO MAYOR A 360 DIAS";
            $Evaluar = true;
            break;
        case "VEN180":
        case "MAS360":
            $ListaFacturas .= "<b>LO SENTIMOS, SU EMPRESA ESTA SUSPENDIDA EN EL SISTEMA</b><br> ";
            $TMail->Subject = "EMPRESA SUSPENDIDA";
            $Evaluar = true;
            break;
        case "BLOQ":
            $ListaFacturas .= "<b>LO SENTIMOS, SU EMPRESA NO ESTA ACTIVA EN EL SISTEMA</b><br> ";
            $TMail->Subject = "BLOQUEO DEFINITIVO, COMUNIQUESE A DISKCOVER SYSTEM";
            $Evaluar = true;
            break;
    }

    if (strlen($ListaFacturas) > 1) {
        $ListaFacturas .= "COMUNIQUESE CON SERVICIO AL CLIENTE DE DISKCOVER SYSTEM A LOS TELEFONOS: 098-910-5300/098-652-4396/099-965-4196, "
            . "O ENVIE UN MAIL A carteraclientes@diskcoversystem.com; CON EL COMPROBANTE DE DEPOSITO Y ASI PROCEDER A REALIZAR "
            . "LA ACTUALIZACION DE LA JUSTIFICACION EN EL SISTEMA." . PHP_EOL;

        $TMail->de = CorreoDiskCover;
        $TMail->Mensaje = $ListaFacturas;
        $TMail->Adjunto = "";
        $TMail->Credito_No = "";
        $TMail->para = "";
        $TMail->para = Insertar_Mail($TMail->para, $EmailEmpresa);
        $TMail->para = Insertar_Mail($TMail->para, $EmailContador);
        if ($Email_CE_Copia) {
            $TMail->para = Insertar_Mail($TMail->para, $EmailProcesos);
        }
        $email = new enviar_emails();
        //$email->FEnviarCorreos($TMail, $Lista_De_Correos, $empresa[0]["Item"]);
        return array('rps' => !$Evaluar, "mensaje" => $ListaFacturas);
    }

    return array('rps' => true);
}

/**
 * Verifica los estados de usuario, del pc y de la empresa.
 * Leonardo Súñiga y Dallyana Vanegas
 */



function Ver_Grafico_FormPict($NumModulo = 0, $CrearYa = false)
{
    $conn = new db();
    $NumEmpresa = $_SESSION['INGRESO']['item'];
    $CodigoUsuario = $_SESSION['INGRESO']['CodigoU'];
    $RUC = $_SESSION['INGRESO']['RUC'];

    if (strlen($NumEmpresa) >= 3 && strlen($NumModulo) > 1 && strlen($CodigoUsuario) > 1) {
        // Leemos el estado de la Empresa y su fecha de procesamiento
        $rps_estado = Estado_Empresa_SP_MySQL();
        if (strlen($rps_estado['@ComunicadoEntidad']) > 1 || strlen($rps_estado['@MensajeEmpresa']) > 1) {
            $Titulo = "COMUNICADO A LA ENTIDAD";
            $Mensajes = "INFORMATIVO:<br>"
                . "-----------<br>"
                . "Este es un mensaje automatico, enviado desde el Centro de "
                . "Control de Atención al Cliente, el informativo dice:<br>";

            if (strlen($rps_estado['@ComunicadoEntidad']) > 1) {
                $Mensajes .= strtoupper($rps_estado['@ComunicadoEntidad']) . '<br><br>';
            }
            if (strlen($rps_estado['@MensajeEmpresa']) > 1) {
                $Mensajes .= $rps_estado['@MensajeEmpresa'] . '<br><br>';
            }

            $Mensajes .= "En caso de requerir atención personalizada por parte de un asesor de servicio "
                . "al cliente de DiskCover System, usted podrá solicitar ayuda mediante los canales de "
                . "atención al cliente oficiales que detallamos a continuación: "
                . "Telefonos: 098-652-4396/099-965-4196/098-910-5300.<br><br>"
                . "Por la atención que se de al presente quedamos de usted.<br><br>"
                . "<b>DESEA SEGUIR RECIBIENDO ESTE COMUNICADO</b>";

            return array('rps' => false, "mensaje" => $Mensajes, "titulo" => $Titulo);
        }
    }
    return array('rps' => true);
}

function ConfirmacionComunicado($NumModulo = 0, $SeguirMostrando = true)
{
    $conn = new db();
    $NumEmpresa = $_SESSION['INGRESO']['item'];
    $CodigoUsuario = $_SESSION['INGRESO']['CodigoU'];
    $RUC = $_SESSION['INGRESO']['RUC'];

    if (strlen($NumEmpresa) >= 3 && strlen($NumModulo) > 1 && strlen($CodigoUsuario) > 1) {
        // Leemos el estado de la Empresa y su fecha de procesamiento
        $rps_estado = Estado_Empresa_SP_MySQL();
        if (strlen($rps_estado['@ComunicadoEntidad']) > 1 || strlen($rps_estado['@MensajeEmpresa']) > 1) {
            $Titulo = "COMUNICADO A LA ENTIDAD";
            $Mensajes = "INFORMATIVO:<br>"
                . "-----------<br>"
                . "Este es un mensaje automatico, enviado desde el Centro de "
                . "Control de Atención al Cliente, el informativo dice:<br>";

            if (strlen($rps_estado['@ComunicadoEntidad']) > 1) {
                $Mensajes .= strtoupper($rps_estado['@ComunicadoEntidad']) . '<br><br>';
            }
            if (strlen($rps_estado['@MensajeEmpresa']) > 1) {
                $Mensajes .= $rps_estado['@MensajeEmpresa'] . '<br><br>';
            }

            $Mensajes .= "En caso de requerir atención personalizada por parte de un asesor de servicio "
                . "al cliente de DiskCover System, usted podrá solicitar ayuda mediante los canales de "
                . "atención al cliente oficiales que detallamos a continuación: "
                . "Telefonos: 098-652-4396/099-965-4196/098-910-5300.<br><br>"
                . "Por la atención que se de al presente quedamos de usted.";

            if (!$SeguirMostrando) {
                //Datos del destinatario de mails, Cod datos de privacidad
                $TMail->Asunto = "Informativo enviado a Sr(a): " . $rps_estado['@Representante'] . ", representante de: " . $rps_estado['@NombreEntidad'];
                $TMail->Adjunto = "";
                $TMail->Credito_No = "";
                $TMail->Mensaje = $Mensajes;
                //Enviamos lista de mails
                $TMail->para = "";
                $TMail->para = Insertar_Mail($TMail->para, CorreoDiskCover);
                $TMail->para = Insertar_Mail($TMail->para, $_SESSION['INGRESO']['Email_Procesos']);
                $TMail->para = Insertar_Mail($TMail->para, $_SESSION['INGRESO']['EmailContador']);

                $email = new enviar_emails();
                //$email->FEnviarCorreos($TMail, null, $NumEmpresa);

                $sSQL = "UPDATE entidad "
                    . "SET Comunicado = '.' "
                    . "WHERE ID_Empresa = " . $IDEntidad . " ";
                $conn->String_Sql($sSQL, 'MYSQL');

                $sSQL = "UPDATE lista_empresas "
                    . "SET Mensaje = '.' "
                    . "WHERE ID_Empresa = " . $IDEntidad . " "
                    . "AND RUC_CI_NIC = '" . $RUC . "' "
                    . "AND Item = '" . $NumEmpresa . "' ";
                $conn->String_Sql($sSQL, 'MYSQL');
            }
        }
        //Actualizamos el estado y fecha de comprobantes electronico de la empresa
        $sSQL = "UPDATE Empresas "
            . "SET Estado = '" . $rps_estado['@EstadoEmpresa'] . "' "
            . "WHERE Item = '" . $NumEmpresa . "' "
            . "AND Estado <> '" . $rps_estado['@EstadoEmpresa'] . "' ";
        Ejecutar_SQL_SP($sSQL);

        $sSQL = "UPDATE Empresas "
            . "SET Fecha_CE = '" . BuscarFecha($rps_estado['@FechaCE']) . "' "
            . "WHERE Item = '" . $NumEmpresa . "' "
            . "AND Fecha_CE <> '" . BuscarFecha($rps_estado['@FechaCE']) . "' ";
        Ejecutar_SQL_SP($sSQL);
        if (!$rps_estado['@pActivo']) {
            $Cadena = $_SESSION['INGRESO']['Nombre_Completo'] . "\nSu Equipo se encuentra en LISTA NEGRA, ingreso no autorizado, comuniquese con el Administrador del Sistema";
            return array('rps' => false, "mensaje" => $Cadena, "titulo" => 'ACCESO DEL PC DENEGADO');
        }
        if (!$rps_estado['@EstadoUsuario']) {
            $Cadena = $_SESSION['INGRESO']['Nombre_Completo'] . "\nSu ingreso no esta autorizado, comuniquese con el Administrador del Sistema";
            return array('rps' => false, "mensaje" => $Cadena, "titulo" => 'ACCESO AL SISTEMA DENEGADO');
        }
    }

    return array('rps' => true);
}

function validar_estado_all()
{

    $conn = new db();
    $sSQL = "SELECT " . Full_Fields("Empresas") . " "
        . "FROM Empresas "
        . "WHERE Item = '".$_SESSION['INGRESO']['item']."'"
        ." AND RUC = '".$_SESSION['INGRESO']['RUC']."' "; //definido en panel.php

        // print_r($_SESSION['INGRESO']);
        // print_r($sSQL);die();
    $empresa = $conn->datos($sSQL);

    // print_r($empresa);die();

    $sSQL = "SELECT " . Full_Fields("Accesos") . " "
        . "FROM Accesos "
        . "WHERE UPPER(Usuario) = '" . $_SESSION['INGRESO']['usuario'] . "' " //definido en loginController.php
        . "AND UPPER(Clave) = '" . $_SESSION['INGRESO']['pass'] . "' "; //definido en loginController.php
    $dataUser = $conn->datos($sSQL);

    // print_r($dataUser);die();
    global $Fecha_CO, $Fecha_CE, $Fecha_VPN, $Fecha_DB, $Fecha_P12, $AgenteRetencion, $MicroEmpresa,
    $EstadoEmpresa, $DescripcionEstado, $NombreEntidad, $RepresentanteLegal, $MensajeEmpresa,
    $ComunicadoEntidad, $SerieFE, $Cartera, $Cant_FA, $TipoPlan, $PCActivo, $EstadoUsuario,
    $ConexionConMySQL;

    //llamada al SP de MySql
    Datos_Iniciales_Entidad_SP_MySQL($empresa[0], $dataUser[0]);
    if ($ConexionConMySQL) {
        if (
            $empresa[0]["Estado"] != $EstadoEmpresa || $empresa[0]["Cartera"] != $Cartera ||
            $empresa[0]["Cant_FA"] != $Cant_FA || $empresa[0]["Fecha_CE"]->format("Y-m-d") != $Fecha_CE ||
            $empresa[0]["Fecha_P12"]->format("Y-m-d") != $Fecha_P12 || $empresa[0]["Tipo_Plan"] != $TipoPlan ||
            $empresa[0]["Serie_FA"] != $SerieFE
        ) {
            $sql = "UPDATE Empresas
                  SET Cartera = '$Cartera',
                  Cant_FA = '$Cant_FA', 
                  Fecha_CE = '$Fecha_CE', 
                  Fecha_P12 = '$Fecha_P12', 
                  Tipo_Plan = '$TipoPlan', 
                  Estado = '$EstadoEmpresa', 
                  Serie_FA = '$SerieFE'
                  WHERE ID = '" . $empresa[0]["ID"] . "'";
            $conn->String_Sql($sql);
        }
    }
    //Revisado, todo correcto hasta aqui.


    if ($PCActivo == false) {
        $Cadena = $_SESSION['INGRESO']['Nombre_Completo'] . " su equipo se encuentra en LISTA NEGRA, ingreso no autorizado, comuniquese con el Administrador del Sistema";
        return json_encode(array('rps' => 'noActivo', "mensaje" => $Cadena, "titulo" => 'ACCESO DEL PC DENEGADO'));
    }
    
    if ($EstadoUsuario == false) {
        $Cadena = $_SESSION['INGRESO']['Nombre_Completo'] . " su ingreso no esta autorizado, comuniquese con el Administrador del Sistema";
        return json_encode(array('rps' => 'noAuto', "mensaje" => $Cadena, "titulo" => 'ACCESO AL SISTEMA DENEGADO'));
    }

    $ListaFacturas = "";
    $titulo = "";
    $rps = "";

    $NumEmpresa = $empresa[0]['Item'];
    $Periodo_Contable = G_NINGUNO;
    $Dolar = 0;
    $RUCEmpresa = $empresa[0]['RUC'];
    $CodigoUsuario = $_SESSION['INGRESO']['CodigoU'];
    $NumModulo = "00";

    sp_Iniciar_Datos_Default($NumEmpresa, $Periodo_Contable, $Dolar, $RUCEmpresa, $CodigoUsuario, $Fecha_CE, $NumModulo);
    if ($Cartera != 0 && $Cant_FA != 0) {
        $ListaFacturas = "ESTIMADO " . strtoupper($_SESSION['INGRESO']['noempr']) . ", SE LE COMUNICA QUE USTED MANTIENE UNA CARTERA VENCIDA DE USD " . number_format($Cartera, 2, '.', ',') . ", EQUIVALENTE A " . $Cant_FA . " FACTURA(S) EMITIDA(S) A USTED." . PHP_EOL;
    }

    switch ($EstadoEmpresa) {
        case "VEN30":
            $ListaFacturas .= "PRIMER COMUNICADO DE ADVERTENCIA: SU EMPRESA ESTA POR SER BLOQUEADA POR CARTERA DE 30 DIAS DE VENCIMIENTO, ";
            $titulo .= "CARTERA VENCIDA POR 30 DIAS";
            $rps .= "VEN30";
            break;
        case "VEN60":
            $ListaFacturas .= "SEGUNDO COMUNICADO DE ADVERTENCIA: SU EMPRESA ESTA POR SER BLOQUEADA POR CARTERA DE 60 DIAS DE VENCIMIENTO, ";
            $titulo .= "CARTERA VENCIDA POR 60 DIAS";
            $rps .= "VEN60";
            break;
        case "VEN90":
            $ListaFacturas .= "TERCER COMUNICADO DE ADVERTENCIA: SU EMPRESA ESTA POR SER BLOQUEADA POR CARTERA DE 90 DIAS DE VENCIMIENTO, ";
            $titulo .= "CARTERA VENCIDA POR 90 DIAS";
            $rps .= "VEN90";
            break;
        case "VEN360":
            $ListaFacturas .= "SU EMPRESA ESTA BLOQUEADA POR CARTERA DE 360 DIAS DE VENCIMIENTO, ";
            $titulo .= "EMPRESA BLOQUEADA POR CARTERA DE 360 DIAS DE VENCIMIENTO";
            $rps .= "VEN360";
            break;
        case "VEN180":
        case "MAS360":
            $ListaFacturas .= "LO SENTIMOS, SU EMPRESA ESTA SUSPENDIDA EN EL SISTEMA, ";
            $titulo .= "EMPRESA BLOQUEADA";
            $rps .= "MAS360";
            break;
        case "BLOQ":
            $ListaFacturas .= "LO SENTIMOS, SU EMPRESA NO ESTA ACTIVA EN EL SISTEMA,\n";
            $titulo .= "EMPRESA NO ACTIVA EN EL SISTEMA";
            $rps .= "BLOQ";
            break;
    }


    if (strlen(trim($ListaFacturas)) > 1) {
        $ListaFacturas .= "COMUNIQUESE CON SERVICIO AL CLIENTE DE DISKCOVER SYSTEM A LOS TELEFONOS: 098-910-5300/098-652-4396/099-965-4196,\n";
        $ListaFacturas .= "O ENVIE UN MAIL A carteraclientes@diskcoversystem.com; CON EL COMPROBANTE DE DEPOSITO Y ASI PROCEDER A REALIZAR\n";
        $ListaFacturas .= "LA ACTUALIZACION DE LA JUSTIFICACION EN EL SISTEMA.";
        
        //enviarCorreo($empresa, $titulo, $ListaFacturas);

        return json_encode(array('rps' => $rps, "mensaje" => $ListaFacturas, "titulo" => $titulo));
    }

    return json_encode(array('rps' => 'ok'));
}

function enviarCorreo($empresa, $titulo, $mensaje)
{
    $Lista_De_Correos = array();
    for ($i = 0; $i < 7; $i++) {
        $Lista_De_Correos[$i] = array(
            'Correo_Electronico' => CorreoDiskCover,
            'Contraseña' => ContrasenaDiskCover
        );
    }

    if (strlen($empresa[0]["Email_Conexion"]) > 1 && strlen($empresa[0]["Email_Contraseña"]) > 1) {
        $Lista_De_Correos[0]['Correo_Electronico'] = $empresa[0]["Email_Conexion"];
        $Lista_De_Correos[0]['Contraseña'] = $empresa[0]["Email_Contraseña"];
    }

    if (strlen($empresa[0]["Email_Conexion_CE"]) > 1 && strlen($empresa[0]["Email_Contraseña_CE"]) > 1) {
        $Lista_De_Correos[4]['Correo_Electronico'] = $empresa[0]["Email_Conexion_CE"];
        $Lista_De_Correos[4]['Contraseña'] = $empresa[0]["Email_Contraseña_CE"];
    }

    $Lista_De_Correos[6]['Correo_Electronico'] = 'credenciales@diskcoversystem.com';
    $Lista_De_Correos[6]['Contraseña'] = 'Dlcjvl1210@Credenciales';

    /*for ($i = 0; $i < 7; $i++) {
        echo '<script>';
        echo 'console.log("DEBUG Estado Activo: ' . $Lista_De_Correos[$i]['Correo_Electronico'] . '");';
        echo 'console.log("DEBUG Estado Activo: ' . $Lista_De_Correos[$i]['Contraseña'] . '");';
        echo '</script>';
    }*/

    $EmailEmpresa = $empresa[0]["Email"];
    $EmailContador = $empresa[0]["Email_Contabilidad"];
    $EmailProcesos = $empresa[0]["Email_Procesos"];
    $Email_CE_Copia = (bool) $empresa[0]["Email_CE_Copia"];

    $TMail = new stdClass();
    $TMail->de = CorreoDiskCover;
    $TMail->Subject = $titulo;
    $TMail->Mensaje = $mensaje;
    $TMail->Adjunto = "";
    $TMail->Credito_No = "";
    $TMail->para = "";
    $TMail->para = Insertar_Mail($TMail->para, $EmailEmpresa);
    $TMail->para = Insertar_Mail($TMail->para, "mailprueba@gmail.com");
    $TMail->para = Insertar_Mail($TMail->para, $EmailContador);
    if ($Email_CE_Copia) {
      $TMail->para = Insertar_Mail($TMail->para, $EmailProcesos);
    }
    $email = new enviar_emails();

    $rps = $email->FEnviarCorreos($TMail, $Lista_De_Correos);

    echo '<script>';

    if (!empty($rps)) {
        foreach ($rps as $result) {
            if (isset($result['error'])) {
                echo 'console.log("' . $result['mensaje'] . '");';
            } elseif (isset($result['rps'])) {
                if ($result['rps'] == true) {
                    echo 'console.log("Correo enviado con éxito a: ' . $result['para'] . '");';
                } else {
                    echo 'console.log("Ocurrió un problema en el envío del correo a: ' . $result['para'] . '");';
                }
            }
        }
    } else {
        echo 'console.log("Ocurrió un error al recibir datos de la función");';
    }
    echo '</script>';
}

function control_errores($parametros)
{
    // print_r($parametros);die();
    control_procesos('E',$parametros['Proceso'],$parametros['Tarea']);
}

?>