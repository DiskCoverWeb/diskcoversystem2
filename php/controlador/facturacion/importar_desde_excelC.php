<?php  
require_once(dirname(__DIR__,2)."/modelo/facturacion/importar_desde_excelM.php");

$controlador = new importar_desde_excel();
if(isset($_GET['DCLinea']))
{
	$query = isset($_GET['q']) ? $_GET['q']:"";
	$modulo =isset($_GET['modulo']) ? $_GET['modulo']:"";
	echo json_encode($controlador->DCLinea($modulo,$query));
}

if(isset($_GET['guardarINV']))
{
	$file = $_FILES;
	$parametros =isset($_POST['parametros']) ? $_POST['parametros']:"";
	$parametros = json_decode($parametros, true);
	echo json_encode($controlador->guardarINV($parametros,$file));
}

/**
 * 
 */
class importar_desde_excel
{
	private $modelo;	
	function __construct()
	{
		$this->modelo = new importar_desde_excelM();
	}

	function DCLinea($modulo,$query)
	{
		// $data = array();
		$data = $this->modelo->DCLineas($modulo,$query);  
		$lista = array();
		foreach ($data as $key => $value) {
			$lista[] = array('id'=>$value['Codigo'],'text'=>$value['Concepto'],'data'=>$value);
		}
		return $lista;
		// print_r($data);die();
	}

	function guardarINV($parametros,$file)
	{
		$SubidaExitosa = 1;
		$archivo_name = $file['archivo']['name'];
		$tipo = explode('.',$archivo_name);
		$Extension = strtoupper($tipo[count($tipo)-1]);
		$Tipo_Carga = $parametros['Tipo_Carga'];

		//$clientes = $this->modelo->Clientes();
		$tablas_temp = $this->modelo->Tabla_Temporal();

		// print_r($parametros);print_r($file);die();
		// print_r($file);die();		
		// print_r($Tipo_Carga);die();

		$MsgBox ="";

		if($Extension == "CSV")
		{
	       switch($Tipo_Carga)
	       {
	         case 1: 
	         	$this->modelo->Importar_Facturas();
	         break;
	         case 5: 
	         	$this->modelo->Importar_Contabilidad($parametros);
	         break;
	         case 15: 
	         	$this->modelo->Importar_Abonos_Transferencias();
	         break;
	         case 27: 
	         	$this->modelo->Importar_Compras_Diarias();
	         break;
	         case 99: 
	         	$this->modelo->Importar_Contabilidad_SubModulos();
	         break;
	       	}
    	}else{
       		switch($Tipo_Carga)
       		{
       			case 4: 
       				$this->modelo->Importar_Plan_Cuentas();
       				break;
          		case 6: 
          			$this->modelo->Importar_Abonos();
          			break;
	          	case 9: 
	          		$this->modelo->Importar_Inventarios();
          			break;
	          	case 10: 
	          		$this->modelo->Importar_Personas();
          			break;
		        case 18: 
		        	$this->modelo->Importar_Empleados();
          			break;
		        case 19: 
		        	$this->modelo->Importar_Descuento_Empleados();
          			break;
		        case 20: 
		        	$this->modelo->Importar_Facturas_Farmacias();
          			break;
          		case 24: 
          			$this->modelo->Importar_Retenciones_Farmacia();
                   
                   	$this->modelo->Generar_Asiento_Compras(True);
                   break;
		        case 25: 
		        	$this->modelo->Importar_Autorizacion_Electronica();
          			break;
		        case 100: 
		        	$this->modelo->Importar_Estudiantes_Representantes();
          			break;
		        case 104: 
		        	$this->modelo->Importar_Personas();
          			break;
		        case 106: 
		        	$this->modelo->Importar_Estudiantes_PreFacturas();
          			break;
		        case 107: 
		        	$this->modelo->Importar_Actualizacion_Estudiantes();
          			break;
		        case 253: 
		        	$this->modelo->Importar_Activos();
          			break;
          		default:
               		$MsgBox = "No se puede subir esta plantilla, el formato no es correcto";
               		$SubidaExitosa = 0;
               		break;
           }

    // RatonNormal
    // Progreso_Barra.Mensaje_Box = "Proceso Terminado con exito, Revise los resultados"
    // Progreso_Final
    
    // If Len(TextoImprimio) > 2 Then
    //    FInfoError.Show
    // Else
    //    If SubidaExitosa Then MsgBox "Proceso Terminado con exito, revise los resultados" Else MsgBox "No se puede subir esta plantilla, debe ser un archivo tipo CSV"
    }
    


	// 	print_r($tablas_temp);die();
	// 	print_r($parametros);
	// 	print_r($file);
	// 	die();

		return array("resp"=>$SubidaExitosa,"msj"=>$MsgBox);
	}
}

?>