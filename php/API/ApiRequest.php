<?php 
require_once(dirname(__DIR__,2)."/php/controlador/loginC.php");
require_once(dirname(__DIR__,2)."/php/db/db1.php");
require_once(dirname(__DIR__,2)."/php/modelo/empresa/cambioeM.php");

require_once("AuthJWT/AuthJWT.php");

$controlador = new ApiRequest();
if(isset($_GET['tokenRequest']))
{
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$param = json_decode(file_get_contents('php://input'), true);
		if (json_last_error() !== JSON_ERROR_NONE) {
			$controlador->errorJson("Json Invalido");      
	    }
		echo json_encode($controlador->Apitoken($param));
	}
}

if(isset($_GET['dataInvoice']))
{
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$param = json_decode(file_get_contents('php://input'), true);
		if (json_last_error() !== JSON_ERROR_NONE) {
			$controlador->errorJson("Json Invalido");      
	    }else{
	    	$token = $controlador->ValidateTokenBearer();
	    	if($token==-1)
	    	{
				$controlador->errorJson("Token no valido");  
	    	}else
	    	{
	    		echo json_encode($controlador->dataInvoice($token,$param),true);
	    	}
		}


		// echo json_encode($controlador->Apitoken($param));
	}
}

if(isset($_GET['InvoiceAdvance']))
{
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$param = json_decode(file_get_contents('php://input'), true);
		if (json_last_error() !== JSON_ERROR_NONE) {
			$controlador->errorJson("Json Invalido");      
	    }else{
	    	$token = $controlador->ValidateTokenBearer();
	    	if($token==-1)
	    	{
				$controlador->errorJson("Token no valido");  
	    	}else
	    	{
	    		echo json_encode($controlador->InvoiceAdvance($token,$param),true);
	    	}
		}


		// echo json_encode($controlador->Apitoken($param));
	}
}




class ApiRequest
{
	private $login;
	private $AuthJWT;
	private $db;
	private $cambioEmpresa;
	
	function __construct()
	{
		$this->login = new loginC();
		$this->AuthJWT = new AuthJWT();
		$this->db = new db();
		$this->cambioEmpresa = new cambioeM();
	}

	function Apitoken($param)
	{
		$cartera_usu = '';
		$cartera_pass = '';

		if($param['ApiCartera'])
		{
			$cartera_usu = '';
			$cartera_pass = '';
		}
		$parametros = array(
		 	 'usuario'=>$param['ApiUsuario'],
		 	 'entidad'=>$param['ApiEntidad'],
		 	 'item'=>$param['ApiItem'],
		 	 'empresa'=>$param['ApiRUC'],
		 	 'pass'=>$param['ApiClave'],
		 	 'cartera'=>$param['ApiCartera'],
		 	 'cartera_usu'=>$cartera_usu,
		 	 'cartera_pass'=>$cartera_pass,
		 	 'localIp'=>$this->ip(),
			 'ipWAN'=>""
		 );
		 $data = $this->login->login($parametros);

		if($data==1)
		{
			$token = $this->AuthJWT->createAccessToken($param['ApiEntidad'],$param['ApiRUC'],$param['ApiUsuario'], $param['ApiItem']); 
			$token = array("Response"=>1,'token' => $token);
			return $token;

		}else
		{
			return $this->errorJson("Credenciales Incorrectas");
		}
		// print_r($data);die();
	}

	function dataInvoice($token,$param)
	{
		// print_r($param);

		$token = $this->getBearerToken();
		$payload = $this->AuthJWT->peekToken($token);
		$empresa = $this->cambioEmpresa->empresas_datos($payload['entidad'],$payload['role']);
		$empresa = $empresa[0];
		// exec sp_Api_Leer_PreFactura '1391721943001', '017', '3050735657', @ListaCampos OUTPUT;

		$JSON_OutPut = "";
		$parametros = array(
	        array(&$payload['role'], SQLSRV_PARAM_IN),
	        array(&$param['ApiCliente'], SQLSRV_PARAM_IN),
	        array(&$JSON_OutPut, SQLSRV_PARAM_INOUT)
	      );

	    $sql = "EXEC sp_Api_Leer_PreFactura @Item=?, @ID_Identificacion=?, @JSON_OutPut=?";
		$datos = $this->db->ejecutar_procesos_almacenados_terceros($sql,$parametros,$empresa['usu'],$empresa['pass'],$empresa['host'],$empresa['base'],$empresa['Puerto'],1);

		$jsonReal = stripslashes($JSON_OutPut);
		$datos = json_decode($jsonReal, true);
	    return $datos;
	   
	}


	function InvoiceAdvance($token,$param)
	{
		// print_r($param);

		$token = $this->getBearerToken();
		$payload = $this->AuthJWT->peekToken($token);
		$empresa = $this->cambioEmpresa->empresas_datos($payload['entidad'],$payload['role']);
		$empresa = $empresa[0];
		// exec sp_Api_Leer_PreFactura '1391721943001', '017', '3050735657', @ListaCampos OUTPUT;

		$JSON_OutPut = "";
		$parametros = array(
	        array(&$payload['role'], SQLSRV_PARAM_IN),
	        array(&$param['ApiCliente'], SQLSRV_PARAM_IN),
	        array(&$param['IDTransaccion'], SQLSRV_PARAM_IN),
	        array(&$JSON_OutPut, SQLSRV_PARAM_INOUT)
	      );


	    $sql = "EXEC sp_Api_Grabar_Abonos_Factura @Item=?, @ID_Identificacion=?, @IDTransaccion=?, @JSON_OutPut=?";
		$datos = $this->db->ejecutar_procesos_almacenados_terceros($sql,$parametros,$empresa['usu'],$empresa['pass'],$empresa['host'],$empresa['base'],$empresa['Puerto'],1);

		$jsonReal = stripslashes($JSON_OutPut);
		$datos = json_decode($jsonReal, true);
	    return $datos;
	   
	}


	function ValidateTokenBearer()
	{
		$tokenTake = $this->getBearerToken();
		if($tokenTake!='')
	    {
	    	if($this->AuthJWT->validateToken($tokenTake))
	    	{
	    		return 1;
	    	}else{
	    		$this->errorJson($msj = "token vencido");
	    	}
	    }else
	    {
	    	return -1;
	    }
	}

	function getBearerToken() {

	    $headers = getallheaders();
	    $tokenTake = '';
	    
	    // Verificar el header Authorization
	    if (isset($headers['Authorization'])) {
	        $authHeader = $headers['Authorization'];
	        
	        // Extraer el token Bearer
	        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
	             $tokenTake = $matches[1];
	        }
	    }
	    
	    // Alternativa: verificar $_SERVER
	    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
	        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
	        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
	             $tokenTake =  $matches[1];
	        }
	    }
	    
	    // Otra alternativa
	    if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
	        $authHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
	        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
	             $tokenTake = $matches[1];
	        }
	    }

	   return $tokenTake;
	}


	function ip()
	{
	    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	        return $_SERVER['HTTP_CLIENT_IP'];
	    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	        // Si hay múltiples IPs, nos quedamos con la primera
	        $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
	        return trim($ipList[0]);
	    } else {
	        return $_SERVER['REMOTE_ADDR'];
	    }
	}

	function errorJson($msj = "Json Invalido")
	{
		http_response_code(400);
        echo json_encode(["Response"=>-1,'error' => $msj]);
        exit;
	}
}
?>