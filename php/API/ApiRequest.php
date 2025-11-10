<?php 
require_once(dirname(__DIR__,2)."/php/controlador/loginC.php");
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
	    	$token = $controlador->getBearerToken();
	    	if($token==-1)
	    	{
				$controlador->errorJson("Token no valido");  
	    	}else
	    	{
	    		echo json_encode($controlador->dataInvoice($param));
	    	}
		}


		// echo json_encode($controlador->Apitoken($param));
	}
}



class ApiRequest
{
	private $login;
	private $AuthJWT;
	
	function __construct()
	{
		$this->login = new loginC();
		$this->AuthJWT = new AuthJWT();
	}

	function Apitoken($param)
	{
		$cartera_usu = '';
		$cartera_pass = '';

		if($param['cartera'])
		{
			$cartera_usu = '';
			$cartera_pass = '';
		}

		$parametros = array(
		 	 'usuario'=>$param['usuario'],
		 	 'entidad'=>$param['entidad'],
		 	 'item'=>$param['item'],
		 	 'empresa'=>$param['RUC'],
		 	 'pass'=>$param['clave'],
		 	 'cartera'=>$param['cartera'],
		 	 'cartera_usu'=>$cartera_usu,
		 	 'cartera_pass'=>$cartera_pass,
		 	 'localIp'=>$this->ip(),
			 'ipWAN'=>""
		 );
		 $data = $this->login->login($parametros);

		if($data==1)
		{
			$token = $this->AuthJWT->createAccessToken($param['RUC'],$param['usuario'], $param['item']); 
			$token = array("Response"=>1,'token' => $token);
			return $token;

		}else
		{
			return $this->errorJson("Credenciales Incorrectas");
		}
		// print_r($data);die();
	}

	function dataInvoice($param)
	{
		// print_r($param);

		return $param;
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