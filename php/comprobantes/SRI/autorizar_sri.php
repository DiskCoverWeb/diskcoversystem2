<?php 
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

date_default_timezone_set('America/Guayaquil');

@session_start(); 

$controlador = new autorizacion_sri();
if(isset($_GET['autorizar']))
{
	$parametros = $_POST['parametros'];
     echo json_encode($controlador->Autorizar($parametros));
}

/**
 * 
 */
class autorizacion_sri
{
	private $clave;
	//Metodo de encriptación
	private $method;
	private $iv;
	private $conn;
	private $db;
	// Puedes generar una diferente usando la funcion $getIV()
	private $linkSriAutorizacion;
	private $linkSriRecepcion;
	function __construct()
	{
		$this->clave = 'Una cadena, muy, muy larga para mejorar la encriptacion';
		$this->method = 'aes-256-cbc';
		$this->iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
		// $this->conn = new Conectar();
		$this->db = new db();

       if(isset($_SESSION['INGRESO']['Web_SRI_Autorizado'])){$this->linkSriAutorizacion = $_SESSION['INGRESO']['Web_SRI_Autorizado'];}
 	   if(isset($_SESSION['INGRESO']['Web_SRI_Recepcion'])){$this->linkSriRecepcion = $_SESSION['INGRESO']['Web_SRI_Recepcion'];}
	}
	function encriptar($dato)
	{
		return openssl_encrypt ($dato, $this->method, $this->clave, false, $this->iv);
	}
	function desencriptar($dato)
	{
		 return openssl_decrypt($dato, $this->method, $this->clave, false, $this->iv);
	}


	function Clave_acceso($fecha,$tipo_com,$serie,$numfac)
	{
		// print_r($fecha);die();
		if(is_object($fecha))
		{
			$fecha = $fecha->format('Y-m-d');
		}
		$ambiente = $_SESSION['INGRESO']['Ambiente'];
	    $Fecha1 = explode("-",$fecha);
		$fechaem=$Fecha1[2].'/'.$Fecha1[1].'/'.$Fecha1[0];
	    $fecha = str_replace('/','',$fechaem);
	    $ruc=$_SESSION['INGRESO']['RUC'];
	    $numfac=$this->generaCeros($numfac, '9');
	    $emi='1';
	    $nume='12345678';
	    $ambiente=$_SESSION['INGRESO']["Ambiente"];

	    $Clave = $fecha.$tipo_com.$ruc.$ambiente.$serie.$numfac.$nume.$emi;	
	    $dig=$this->digito_verificador($Clave);

	    // print_r($Clave.$dig);
	    return $Clave.$dig;
	}

	function Autorizar_factura_o_liquidacion($parametros)
	{
		// 1 para autorizados
	    //-1 para no autorizados y devueltas
	    // 2 para devueltas
	    //-2 no existe la factura
	    //-3 la conexion al sri no esta optima
	    // texto del erro en forma de matris
		$cabecera['ambiente']=$_SESSION['INGRESO']['Ambiente'];
	    $cabecera['ruta_ce']=$_SESSION['INGRESO']['Ruta_Certificado'];
	    $cabecera['clave_ce']=$_SESSION['INGRESO']['Clave_Certificado'];
	    $cabecera['nom_comercial_principal']=$this->quitar_carac($_SESSION['INGRESO']['Nombre_Comercial']);
	    $cabecera['razon_social_principal']=$this->quitar_carac($_SESSION['INGRESO']['Razon_Social']);
	    $cabecera['ruc_principal']=$_SESSION['INGRESO']['RUC'];
	    $cabecera['direccion_principal']= $this->quitar_carac($_SESSION['INGRESO']['Direccion']);
	    $cabecera['Entidad'] = generaCeros($_SESSION['INGRESO']['IDEntidad'],3);
	   
	    if(isset($parametros['serie'])){
	    	$cabecera['serie']=$parametros['serie'];
	    	$cabecera['esta']=substr($parametros['serie'],0,3); 
	    	$cabecera['pto_e']=substr($parametros['serie'],3,5); 	    
	    }else if(isset($parametros['Serie']))
	    {	    	
	    	$parametros['serie'] = $parametros['Serie'];
	    	$cabecera['serie']=$parametros['Serie'];
	    	$cabecera['esta']=substr($parametros['Serie'],0,3); 
	   		$cabecera['pto_e']=substr($parametros['Serie'],3,5); 	    
	    }

	    if(isset($parametros['num_fac'])){
	    	$cabecera['factura']=$parametros['num_fac'];
	    }else if(isset($parametros['FacturaNo']))
	    {	    	
	    	$cabecera['factura']=$parametros['FacturaNo'];
	    }
	    else if(isset($parametros['Factura']))
	    {	    	
	    	$cabecera['factura']=$parametros['Factura'];
	    }	    
	    $cabecera['item']=$_SESSION['INGRESO']['item'];

	    if(isset($parametros['tc'])){
	    	$cabecera['tc']=$parametros['tc'];
	    }else if(isset($parametros['TC']))
	    {	    	
	    	$cabecera['tc']=$parametros['TC'];
	    }

	    if(isset($parametros['cod_doc'])){
	    	$cabecera['cod_doc']=$parametros['cod_doc'];
	    }

	    $cabecera['periodo']=$_SESSION['INGRESO']['periodo'];
		if( isset($cabecera['tc']) && $cabecera['tc']=='LC' || isset($cabecera['TC']) && $cabecera['TC']=='LC')
		{
			$cabecera['cod_doc']='03';
		}else if($cabecera['tc']=='FA')
		{
			$cabecera['cod_doc']='01';
		}

		//sucursal
		 $sucursal = $this->catalogo_lineas($cabecera['tc'],$cabecera['serie']);
		 if(count($sucursal)>0)
		 {
		 	$cabecera['Nombre_Establecimiento'] = $sucursal[0]['Nombre_Establecimiento'];
		 	$cabecera['Direccion_Establecimiento'] = $sucursal[0]['Direccion_Establecimiento'];
		 	$cabecera['Telefono_Establecimiento'] = $sucursal[0]['Telefono_Estab'];
		 	$cabecera['Ruc_Establecimiento'] = $sucursal[0]['RUC_Establecimiento'];
		 	$cabecera['Email_Establecimiento'] = $sucursal[0]['Email_Establecimiento'];
		 	$cabecera['Placa_Vehiculo'] ='.';
		 	$cabecera['Cta_Establecimiento'] = '.';
		 	if(isset($sucursal[0]['Placa_Vehiculo']))
		 	{
		 		$cabecera['Placa_Vehiculo'] = $sucursal[0]['Placa_Vehiculo'];
		 	}
		 	if (isset($sucursal[0]['Cta_Establecimiento'])) {
		 		$cabecera['Cta_Establecimiento'] = $sucursal[0]['Cta_Establecimiento'];
		 	}		 	
		 }
				//datos de factura
	    		$datos_fac = $this->datos_factura($cabecera['serie'],$cabecera['factura'],$cabecera['tc']);
	    		if(count($datos_fac)==0)
	    		{
	    			return -2;
	    		}
	    		// print_r($datos_fac);die();
	    	    $cabecera['RUC_CI']=$this->quitar_carac($datos_fac[0]['RUC_CI']);
				$cabecera['Fecha']=$datos_fac[0]['Fecha']->format('Y-m-d');
				$cabecera['Razon_Social']=$this->quitar_carac($datos_fac[0]['Razon_Social']);
				$cabecera['Direccion_RS']=$this->quitar_carac($datos_fac[0]['Direccion_RS']);
				$cabecera['Sin_IVA']= $this->money_formato($datos_fac[0]['Sin_IVA'],2);
				$cabecera['Descuento'] = $this->money_formato($datos_fac[0]['Descuento']+$datos_fac[0]['Descuento2'],2);
				$cabecera['baseImponible'] = $datos_fac[0]['Sin_IVA']+$cabecera['Descuento'];
				$cabecera['Porc_IVA'] = $this->money_formato($datos_fac[0]['Porc_IVA'],2);

				// print_r($cabecera['Porc_IVA']);die();
				$cabecera['Con_IVA'] = $datos_fac[0]['Con_IVA'];
				$cabecera['Total_MN'] = $this->money_formato($datos_fac[0]['Total_MN'],2);
				$cabecera['Observacion'] = $datos_fac[0]['Observacion'];
				$cabecera['Nota'] = $datos_fac[0]['Nota'];

				$cabecera['Nota'] = $datos_fac[0]['Nota'];
				if($datos_fac[0]['Tipo_Pago'] == '.')
				{
					$cabecera['formaPago']='01';
				}else
				{
					$cabecera['formaPago']=$datos_fac[0]['Tipo_Pago'];
				}
				$cabecera['Propina']= number_format($datos_fac[0]['Servicio'],2,'.','');
				$cabecera['Autorizacion']=$datos_fac[0]['Autorizacion'];
				$cabecera['Imp_Mes']=$datos_fac[0]['Imp_Mes'];
				$cabecera['SP']=$datos_fac[0]['SP'];
				$cabecera['CodigoC']=$datos_fac[0]['CodigoC'];
				$cabecera['TelefonoC']=$datos_fac[0]['Telefono_RS'];
				$cabecera['Orden_Compra']=$datos_fac[0]['Orden_Compra'];
				$cabecera['baseImponibleSinIva'] = $this->money_formato(($cabecera['Sin_IVA']-$datos_fac[0]['Desc_0']),2);
				$cabecera['baseImponibleConIva'] = $this->money_formato(($cabecera['Con_IVA']-$datos_fac[0]['Desc_X']),2);
				$cabecera['totalSinImpuestos'] = $this->money_formato(($cabecera['Sin_IVA']+$cabecera['Con_IVA']-$cabecera['Descuento']),2);
				$cabecera['IVA'] = $this->money_formato($datos_fac[0]['IVA'],2);
				$cabecera['descuentoAdicional']= $this->money_formato(0,2);
				$cabecera['moneda']="DOLAR";
				$cabecera['tipoIden']='';
				// print_r($cabecera);die();

			//datos de cliente
	    	$datos_cliente = $this->datos_cliente($datos_fac[0]['CodigoC']);
	    	// print_r($datos_cliente);die();
	    	    $cabecera['Cliente']=$this->quitar_carac($datos_cliente[0]['Cliente']);
				$cabecera['DireccionC']=$this->quitar_carac($datos_cliente[0]['Direccion']);
				$cabecera['TelefonoC']=$datos_cliente[0]['Telefono'];
				$cabecera['EmailR']=$this->quitar_carac($datos_cliente[0]['Email2']);
				$cabecera['EmailC']=$this->quitar_carac($datos_cliente[0]['Email']);
				$cabecera['Contacto']=$datos_cliente[0]['Contacto'];
				$cabecera['Grupo']=$datos_cliente[0]['Grupo'];

			//codigo verificador 
				if($cabecera['RUC_CI']=='9999999999999')
				  {
				  	$cabecera['tipoIden']='07';
			      }else
			      {
			      	$cod_veri = Digito_verificador($datos_fac[0]['RUC_CI']);
			      	// print_r($cod_veri);die();
			      	switch ($cod_veri['Tipo_Beneficiario']) {
			      		case 'R':
			      			$cabecera['tipoIden']='04';
			      			break;
			      		case 'C':
			      			$cabecera['tipoIden']='05';
			      			break;
			      		case 'P':
			      			$cabecera['tipoIden']='06';
			      			break;
			      	}
			      }

			      // print_r($cabecera);die();
			    $cabecera['codigoPorcentaje']=0;
			    $porceiva = (floatval($cabecera['Porc_IVA'])*100);
			    if($porceiva>0)
			    {
			    	$iva = Porcentajes_IVA($cabecera['Fecha'],$porceiva);
			    	if(count($iva)>0)
			    	{
			    		$cabecera['codigoPorcentaje']=$iva[0]['Codigo'];
			    	}
			    }
			    // if((floatval($cabecera['Porc_IVA'])*100)>12)
			    // {
			    //    $cabecera['codigoPorcentaje']=3;
			    // }else
			    // {
			    //   $cabecera['codigoPorcentaje']=2;
			    // }

			   //detalle de factura
			    $detalle = array();
			    $cuerpo_fac = $this->detalle_factura($cabecera['serie'],$cabecera['factura'],$cabecera['Autorizacion'],$cabecera['tc']);
			    foreach ($cuerpo_fac as $key => $value) 
			    {			    	
			    	$producto = $this->datos_producto($value['Codigo']);
			    	$detalle[$key]['Codigo'] =  $value['Codigo'];
			    	$detalle[$key]['Cod_Aux'] =  $producto[0]['Desc_Item'];
				    $detalle[$key]['Cod_Bar'] =  $producto[0]['Codigo_Barra'];
				    $detalle[$key]['Producto'] = $this->quitar_carac($value['Producto']);
				    $detalle[$key]['Cantidad'] = $this->money_formato($value['Cantidad'],6);
				    $detalle[$key]['Precio'] =  $this->money_formato($value['Precio'],6);
				    $detalle[$key]['descuento'] = $this->money_formato(($value['Total_Desc']+$value['Total_Desc2']),2);
				  if ($cabecera['Imp_Mes']==true)
				  {
				   	$detalle[$key]['Producto'] = $this->quitar_carac($value['Producto']).', '.$value['Ticket'].': '.$value['Mes'].' ';
				  }
				  if($cabecera['SP']==true)
				  {
				  	$detalle[$key]['Producto'] = $this->quitar_carac($value['Producto']).', Lote No. '.$value['Lote_No'].
					', ELAB. '.$value['Fecha_Fab'].
					', VENC. '.$value['Fecha_Exp'].
					', Reg. Sanit. '.$value['Reg_Sanitario'].
					', Modelo: '.$value['Modelo'].
					', Procedencia: '.$value['Procedencia'];
				  }
				   $detalle[$key]['SubTotal'] =  $this->money_formato(($value['Cantidad']*$value['Precio'])-($value['Total_Desc']+$value['Total_Desc2']),2);
				   $detalle[$key]['Serie_No'] = $value['Serie_No'];
				   $detalle[$key]['Total_IVA'] = $this->money_formato($value['Total_IVA'],2);
				   $detalle[$key]['Porc_IVA']= $this->money_formato($value['Porc_IVA'],2);
			    }
			    $cabecera['fechaem']=  date("d/m/Y", strtotime($cabecera['Fecha']));		

			    // $linkSriAutorizacion = $_SESSION['INGRESO']['Web_SRI_Autorizado'];
 	    		// $linkSriRecepcion = $_SESSION['INGRESO']['Web_SRI_Recepcion'];
			    // print_r($cabecera);print_r($detalle);die();
			    $cabecera['ClaveAcceso'] =$this->Clave_acceso($parametros['Fecha'],$cabecera['cod_doc'],$parametros['serie'],$parametros['FacturaNo']);

			    // print_r($cabecera);
// print_r($detalle);die();
			    // die();
		
	            
	           $xml = $this->generar_xml($cabecera,$detalle);
	           // print_r('expression');
	           // die();

	           if($xml==1)
	           {
	           	 $firma = $this->firmar_documento(
	           	 	$cabecera['ClaveAcceso'],
	           	 	 generaCeros($_SESSION['INGRESO']['IDEntidad'],3),
	           	 	$_SESSION['INGRESO']['item'],
	           	 	$_SESSION['INGRESO']['Clave_Certificado'],
	           	 	$_SESSION['INGRESO']['Ruta_Certificado']);
	           	 // print($firma);die();

	           	 if($firma==1)
	           	 {
	           	 	clearstatcache();
	           	 
	           	 	$validar_autorizado = $this->comprobar_xml_sri(
	           	 		$cabecera['ClaveAcceso'],
	           	 		$this->linkSriAutorizacion);
	           	 	if($validar_autorizado == -1)
			   		 {
			   		 	$enviar_sri = $this->enviar_xml_sri(
			   		 		$cabecera['ClaveAcceso'],
			   		 		$this->linkSriRecepcion);
			   		 	if($enviar_sri==1)
			   		 	{
			   		 		//una vez enviado comprobamos el estado de la factura
			   		 		$resp =  $this->comprobar_xml_sri($cabecera['ClaveAcceso'],$this->linkSriAutorizacion);
			   		 		if($resp==1)
			   		 		{
			   		 			// print('dd');
			   		 			$resp = $this->actualizar_datos_CE($cabecera['ClaveAcceso'],$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['Entidad'],$cabecera['Autorizacion'],$cabecera['Fecha'],$datos_fac[0]['CodigoC']);
			   		 			return  $resp;
			   		 		}
			   		 		// print_r($resp);die();
			   		 	}else
			   		 	{
			   		 		return $enviar_sri;
			   		 	}

			   		 }else 
			   		 {
			   		 	// if($validar_autorizado==2)
			   		 	// {
			   		 	// 	$ruta_enviados=dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Enviados/'.$cabecera['ClaveAcceso'].'.xml';
			   		 	// 	if(!file_exists($ruta_enviados))
			   		 	// 	{
			   		 	// 		return -3;
			   		 	// 	}
			   		 	// }
			   		 	if($validar_autorizado==1)
			   		 	{
			   		 		 $this->actualizar_datos_CE($cabecera['ClaveAcceso'],$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['Entidad'],$cabecera['Autorizacion'],$cabecera['Fecha'],$datos_fac[0]['CodigoC']);
			   		 	}
			   		 	// RETORNA SI YA ESTA AUTORIZADO O SI FALL LA REVISIO EN EL SRI
			   			return $validar_autorizado;
			   		 }
	           	 }else
	           	 {
	           	 	//RETORNA SI FALLA AL FIRMAR EL XML
	           	 	return $firma;
	           	 }
	           }else
	           {
	           	//RETORNA SI FALLA EL GENERAR EL XML o si ya esta en la carpeta de autorizados
	           	$this->actualizar_datos_CE($cabecera['ClaveAcceso'],$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['Entidad'],$cabecera['Autorizacion'],$cabecera['Fecha'],$datos_fac[0]['CodigoC']);
	           	return $xml;
	           }

	           // print_r($respuesta);die();
	}

	
	function datos_factura($serie,$fact,$tc,$restringirAut=true)
	{
		// $con = $this->conn->conexion();
		$sql = "SELECT * From Facturas 
		WHERE Item = '".$_SESSION['INGRESO']['item']."' 
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND TC = '".$tc."' 
		AND Serie = '".$serie."' 
		AND Factura = ".$fact." "; 
		if($restringirAut)
		{
			$sql.="AND LEN(Autorizacion) = 13 ";
		}
		$sql.=" AND T <> 'A' ";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function retencion_compras($numero,$tipoCom,$serie_r,$retencion)
    {
    	$sql="SELECT C.Cliente,C.CI_RUC,C.TD,C.Direccion,C.Telefono,C.Email,TC.* 
		FROM Trans_Compras As TC, Clientes As C 
		WHERE TC.Item = '".$_SESSION['INGRESO']['item']."' 
		AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND TC.Serie_Retencion = '".$serie_r."' 
		AND TC.SecRetencion = ".$retencion." 
		AND TC.TP =  '".$tipoCom."' 
		AND TC.Numero = '".$numero."'
		AND LEN(TC.AutRetencion) = 13 
		AND TC.IdProv = C.Codigo 
		ORDER BY Serie_Retencion,SecRetencion";
		// print_r($sql);die();

         $result = $this->db->datos($sql);
	     return $result;
    }


	function datos_cliente($codigo=false,$ci_ruc = false)
	{

		// $con = $this->conn->conexion();
		$sql = "SELECT * From Clientes WHERE  T='N'";
		if($codigo)
			{
				$sql.=" And Codigo = '".$codigo."'";
			}
			if($ci_ruc)
			{
				$sql.=" And CI_RUC = '".$ci_ruc."'";
			}
		// print_r($sql);die();
		// $stmt = sqlsrv_query($con, $sql);
	 //   if( $stmt === false)  
	 //   {  
		//  echo "Error en consulta PA.\n";  
		//  die( print_r( sqlsrv_errors(), true));  
	 //   }
	 //   $datos = array();
	 //   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		// 	{
		// 		$datos[] = $row;
	 //        }
	 //        // print_r($datos);die();
	 //        return $datos;
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function datos_cliente_todo($codigo=false,$ci_ruc=false)
	{
		$sql = "SELECT * From Clientes WHERE T='N' ";

		if($codigo)
		{
			$sql.=" AND Codigo = '".$codigo."'";
		}
		if($ci_ruc)
		{
			$sql.= " AND CI_RUC = '".$ci_ruc."'";
		}
		// print_r($sql);die();
		// $stmt = sqlsrv_query($con, $sql);
	 //   if( $stmt === false)  
	 //   {  
		//  echo "Error en consulta PA.\n";  
		//  die( print_r( sqlsrv_errors(), true));  
	 //   }
	 //   $datos = array();
	 //   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		// 	{
		// 		$datos[] = $row;
	 //        }
	 //        // print_r($datos);die();
	 //        return $datos;
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function detalle_factura($serie,$factura,$autorizacion,$tc)
	{
		// $con = $this->conn->conexion();
		$sql="SELECT DF.*,CP.Reg_Sanitario,CP.Marca 
		FROM Detalle_Factura As DF, Catalogo_Productos As CP
		 WHERE DF.Item = '".$_SESSION['INGRESO']['item']."'
		    AND DF.Periodo = '".$_SESSION['INGRESO']['periodo']."'
		    AND DF.TC = '".$tc."'
		    AND DF.Serie = '".$serie."' 
			AND DF.Autorizacion = '".$autorizacion."' 
			AND DF.Factura = '".$factura."' 
			AND LEN(DF.Autorizacion) >= 13 
			AND DF.T <> 'A' 
			AND DF.Item = CP.Item 
			AND DF.Periodo = CP.Periodo 
			AND DF.Codigo = CP.Codigo_Inv 
			ORDER BY DF.ID,DF.Codigo;";

			// print_r($sql);die();
			$datos = $this->db->datos($sql);
	        return $datos;
	}

	function datos_producto($codigo)
	{
		$sql="SELECT * from Catalogo_Productos WHERE Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Codigo_Inv = '".$codigo."';";
		
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function SRI_Crear_Clave_Acceso_Nota_Credito($TFA)
	{
		$Con_Inv = False;
		$Autorizar_XML = True;
    if(strlen($_SESSION['INGRESO']['Fecha_Igualar']) == 10)
    {
       if($TFA['Fecha']->format('Y-m-d') < $_SESSION['INGRESO']['Fecha_Igualar'] ){ $Autorizar_XML = False;}
    }
    
   // 'Autorizamos la Nota de Credito
    if($Autorizar_XML){
		    
		$TFA = Leer_Datos_FA_NV($TFA);

		// print_r($TFA);die();
		//    'NOTA DE CREDITO
		$SubT_Con_Inv = False;
		$Total_Sin_IVA = 0;
		$Total_Con_IVA = 0;
		$Total_Desc = 0;
		$Total_Desc2 = 0;
		$TFA['Total_IVA_NC'] = 0;
		    
	    $sql = "SELECT Autorizacion, Codigo_Inv, Producto, Cantidad, Precio, Total, Total_IVA, Descuento, Cta_Devolucion, CodBodega, Porc_IVA, Mes, Mes_No , Anio, ID
	        FROM Detalle_Nota_Credito
	        WHERE Item = '".$_SESSION['INGRESO']['item']."'
	        AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
	        AND Serie = '".$TFA['Serie_NC']."'
	        AND Secuencial = ".$TFA['Nota_Credito']."
	        ORDER BY ID ";
	    $AdoDBNC = $datos = $this->db->datos($sql);
	    if(count($AdoDBNC)>0)
	    {
	    	$Con_Inv = True;
	    	foreach ($AdoDBNC as $key => $value) {
	    		$Total_Desc = $Total_Desc + $value["Descuento"];
	            if($value["Total_IVA"] == 0){
	                $Total_Sin_IVA = $Total_Sin_IVA + $value["Total"];
	            }else{
	                $Total_Con_IVA = $Total_Con_IVA + $value["Total"];
	            }
	            $TFA['Total_IVA_NC'] = $TFA['Total_IVA_NC'] + $value["Total_IVA"];	    		
	    	}
	    }
	       
		    if(count($AdoDBNC)<= 0 )
		    {
		       $sql = "SELECT *
		            FROM Trans_Abonos
		            WHERE Item = '".$_SESSION['INGRESO']['item']."'
		            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
		            AND Autorizacion = '".$TFA['Autorizacion'] & "'
		            AND Serie = '".$TFA['Serie'] & "'
		            AND TP = '".$TFA['TC'] & "'
		            AND Factura = ".$TFA['Factura'] & "
		            AND Serie_NC = '".$TFA['Serie_NC'] & "'
		            AND Secuencial_NC = ".$TFA['Nota_Credito'] & "
		            AND Banco = 'NOTA DE CREDITO'
		            ORDER BY TP,Fecha,Cta,Cta_CxP,Abono,Banco,Cheque ";
		       	   $AdoDBNC = $this->db->datos($sql);
		       
		         if(count($AdoDBNC) > 0)
		         {
		         	foreach ($AdoDBNC as $key => $value) 
		         	{	
		                if($value["Cheque"] = "I.V.A.")
		                {
		                    $TFA['Total_IVA_NC'] = $value["Abono"];
		                    $SubT_Con_Inv = True;
		                }else if($value["Cheque"] == "VENTAS SIN IVA")
		                {
		                    $Total_Sin_IVA = $value["Abono"];
		                }else{
		                    $Total_Con_IVA = $value["Abono"];
		                }
		            }		
		         }
		    }
		    $Total_Sin_IVA = number_format($Total_Sin_IVA, 2,'.','');
		    $Total_Con_IVA = number_format($Total_Con_IVA, 2,'.','');
		    $TFA['Total_IVA_NC'] = number_format($TFA['Total_IVA_NC'], 2,'.','');
		    $TFA['SubTotal_NC'] = number_format($Total_Sin_IVA + $Total_Con_IVA, 2,'.','');
		    $TextoXML = "";
		}

		if(!isset($TFA['Porc_NC']) || $TFA['Porc_NC'] == 0){
            $TFA['Porc_IVA'] = Validar_Porc_IVA($TFA['Fecha_NC']);
         }else{
            $TFA['Porc_IVA'] = number_format($TFA['Porc_NC'],2,'.','');
         }


		// print_r($TFA);die();
		$TFA['TOTAL_SIN_IMPUESTOS'] = number_format($Total_Sin_IVA + $Total_Con_IVA - $Total_Desc,2,'.','');
		$TFA['VALOR_MODIFICACION'] = number_format($Total_Sin_IVA + $Total_Con_IVA - $Total_Desc + $TFA['Total_IVA_NC'],2,'.','');
		$TFA['BASEIMPONIBLE'] = number_format($Total_Con_IVA - $Total_Desc,2,'.','');
		$TFA['ClaveAcceso_NC'] = $this->Clave_acceso($TFA['Fecha_NC'],'04',$TFA['Serie_NC'],$TFA['Nota_Credito']);
		$aut = $TFA['ClaveAcceso_NC'];
		// print_r($TFA);die();
		 $xml = $this->generar_xml_nota_credito($TFA,$AdoDBNC);
// die();
		 if($xml==1)
	       {
	       	 $firma = $this->firmar_documento(
	       	 	$aut,
	       	 	generaCeros($_SESSION['INGRESO']['IDEntidad'],3),
	       	 	$_SESSION['INGRESO']['item'],
	       	 	$_SESSION['INGRESO']['Clave_Certificado'],
	       	 	$_SESSION['INGRESO']['Ruta_Certificado']);
	       	 // print($firma);die();
	       	 if($firma==1)
	       	 {
	       	 	$validar_autorizado = $this->comprobar_xml_sri(
	       	 		$aut,
	       	 		$this->linkSriAutorizacion);

	       	 	// print_r($validar_autorizado);die();
	       	 	if($validar_autorizado == -1)
		   		 {
		   		 	$enviar_sri = $this->enviar_xml_sri(
		   		 		$aut,
		   		 		$this->linkSriRecepcion);
		   		 	if($enviar_sri==1)
		   		 	{
		   		 		//una vez enviado comprobamos el estado de la factura
		   		 		// sleep(3);
		   		 		$resp =  $this->comprobar_xml_sri($aut,$this->linkSriAutorizacion);
		   		 		if($resp==1)
		   		 		{
		   		 			$this->SRI_Actualizar_Autorizacion_Nota_Credito($TFA);
		   		 			$this->SRI_Actualizar_Documento_XML($TFA['ClaveAcceso_NC']);
		   		 			return  $resp;
		   		 		}else
		   		 		{
		   		 			return $resp;
		   		 		}
		   		 		// print_r($resp);die();
		   		 	}else
		   		 	{
		   		 		return $enviar_sri;
		   		 	}

		   		 }else 
		   		 {
		   		 	// $resp = $this->actualizar_datos_CE($cabecera['ClaveAcceso'],$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['Entidad'],$cabecera['Autorizacion']);
		   		 	// RETORNA SI YA ESTA AUTORIZADO O SI FALL LA REVISIO EN EL SRI
		   			return $validar_autorizado;
		   		 }
	       	 }else
	       	 {
	       	 	//RETORNA SI FALLA AL FIRMAR EL XML
	       	 	return $firma;
	       	 }
	       }else
	       {
	       	//RETORNA SI FALLA EL GENERAR EL XML
	       	return $xml;
	       }


	}

	//inicio guia remision
	function SRI_Crear_Clave_Acceso_Guia_Remision($TFA)
	{
		// print_r($TFA);die();
		$fecha_igualar = Leer_Campo_Empresa('Fecha_Igualar');

		$Autorizar_XML = True;
	    if(strlen($fecha_igualar) == 10){
	       // if(CFechaLong(TFA.Fecha) < CFechaLong(Fecha_Igualar)){$Autorizar_XML = False;}
	    }
	    $TextoXML = "";
	    
	    // If Autorizar_XML Then
	   // 'Averiguamos si la Factura esta a nombre del Representante
	    $TBeneficiario = Leer_Datos_Clientes($TFA['CodigoC'],$codigo=1);
	   // 'MsgBox TBeneficiario.RUC_CI_Rep & vbCrLf & TBeneficiario.Representante & vbCrLf & TBeneficiario.TD_Rep

	  
	    
	    $TFA['Cliente'] = $TBeneficiario['Representante'];
	    $TFA['TD'] = $TBeneficiario['TD_R'];
	    $TFA['CI_RUC'] = $TBeneficiario['CI_RUC_R'];
	    $TFA['TelefonoC'] = $TBeneficiario['Telefono_R'];
	    $TFA['DireccionC'] = $TBeneficiario['DireccionT'];
	    $TFA['Curso'] = $TBeneficiario['Direccion'];
	    $TFA['Grupo'] = $TBeneficiario['Grupo'];
	    $TFA['EmailC'] = $TBeneficiario['Email'];
	    $TFA['EmailR'] = $TBeneficiario['Email2'];
   		// 'Detalle de descuentos
  	  	$sql = "SELECT DF.*,CP.Reg_Sanitario,CP.Marca
        FROM Detalle_Factura As DF, Catalogo_Productos As CP
        WHERE DF.Item = '".$_SESSION['INGRESO']['item']."'
        AND DF.Periodo =  '".$_SESSION['INGRESO']['periodo']."'
        AND DF.TC = '".$TFA['TC']."'
        AND DF.Serie = '".$TFA['Serie']."'
        AND DF.Autorizacion = '".$TFA['Autorizacion']."'
        AND DF.Factura = ".$TFA['Factura']."
        AND LEN(DF.Autorizacion) >= 13
        AND DF.T <> 'A'
        AND DF.Item = CP.Item
        AND DF.Periodo = CP.Periodo
        AND DF.Codigo = CP.Codigo_Inv
        ORDER BY DF.ID,DF.Codigo ";
   		$AdoDBDet = $this->db->datos($sql);
      
   // 'Encabezado de la Guia de Remision
    	$sql2 = "SELECT F.*,GR.Remision,GR.Comercial,GR.CIRUC_Comercial,GR.Entrega,GR.CIRUC_Entrega,GR.CiudadGRI,GR.CiudadGRF,
        GR.Placa_Vehiculo,GR.FechaGRE,GR.FechaGRI,GR.FechaGRF,GR.Pedido,GR.Zona,GR.Serie_GR,GR.Autorizacion_GR,
        GR.Clave_Acceso_GR,GR.Hora_Aut_GR,GR.Estado_SRI_GR,GR.Error_FA_SRI,GR.Fecha_Aut_GR 
        FROM Facturas As F, Facturas_Auxiliares As GR 
        WHERE F.Item = '".$_SESSION['INGRESO']['item']."'
        AND F.Periodo =  '".$_SESSION['INGRESO']['periodo']."'
        AND F.TC = '".$TFA['TC']."' 
        AND F.Serie = '".$TFA['Serie']."' 
        AND F.Autorizacion = '".$TFA['Autorizacion']."' 
        AND F.Factura = ".$TFA['Factura']." 
        AND LEN(GR.Autorizacion_GR) = 13 
        AND GR.Remision > 0 
        AND F.T <> 'A' 
        AND F.Item = GR.Item 
        AND F.Periodo = GR.Periodo 
        AND F.TC = GR.TC
        AND F.Serie = GR.Serie 
        AND F.Autorizacion = GR.Autorizacion 
        AND F.Factura = GR.Factura ";
    	$AdoDBFA = $this->db->datos($sql2);
    	
    	// print_r($sql);print_r($sql2);die();

	     if(count($AdoDBFA) > 0)
	     {
	         $Autorizar_XML = True;
	         $TFA['T'] = $AdoDBFA[0]["T"];
	         $TFA['SP'] = $AdoDBFA[0]["SP"];
	         $TFA['Porc_IVA'] = $AdoDBFA[0]["Porc_IVA"];
	         $TFA['Imp_Mes'] = $AdoDBFA[0]["Imp_Mes"];
	         $TFA['Fecha'] = $AdoDBFA[0]["Fecha"];
	         $TFA['Vencimiento'] = $AdoDBFA[0]["Vencimiento"];
	         $TFA['SubTotal'] = $AdoDBFA[0]["SubTotal"];
	         $TFA['Sin_IVA'] = $AdoDBFA[0]["Sin_IVA"];
	         $TFA['Con_IVA'] = $AdoDBFA[0]["Con_IVA"];
	         $TFA['Descuento'] = $AdoDBFA[0]["Descuento"];
	         $TFA['Descuento2'] = $AdoDBFA[0]["Descuento2"];
	         $TFA['Total_IVA'] = $AdoDBFA[0]["IVA"];
	         $TFA['Total_MN'] = $AdoDBFA[0]["Total_MN"];
	         $TFA['Razon_Social'] = $AdoDBFA[0]["Razon_Social"];
	         $TFA['RUC_CI'] = $AdoDBFA[0]["RUC_CI"];
	         $TFA['TB'] = $AdoDBFA[0]["TB"];
	         
	        // -- 'MsgBox "Validar Porc IVA"
	         Validar_Porc_IVA($TFA['Fecha']->format('Y-m-d'));
	        // -- 'Generamos la Clave de acceso
	        // -- '& Format$(TFA.Fecha, "ddmmyyyy") &
	         if(strlen($TFA['Autorizacion_GR']) >= 13){
	         	if(is_object($TFA['FechaGRE'])){ $TFA['FechaGRE'] = $TFA['FechaGRE']->format('Y-m-d');}
	            $TFA['ClaveAcceso_GR'] = $this->Clave_acceso($TFA['FechaGRE'],'06',$TFA['Serie_GR'],$TFA['Remision']);
	         }else{
	            $TFA['ClaveAcceso_GR'] = G_NINGUNO;
	         }
	         // TFA.Hora_GR = Format$(Time, FormatoTimes)
	         // SRI_Autorizacion.Clave_De_Acceso = TFA.ClaveAcceso_GR
	         	$sql = "UPDATE Facturas_Auxiliares 
	            SET Clave_Acceso_GR = '" .$TFA['ClaveAcceso_GR']."' 
	            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
	            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
	            AND TC = '" .$TFA['TC']."' 
	            AND Serie = '" .$TFA['Serie']."' 
	            AND Factura = " .$TFA['Factura']."
	            AND Remision = " .$TFA['Remision']."  
	            AND CodigoC = '" .$TFA['CodigoC']."' 
	            AND Autorizacion = '" .$TFA['Autorizacion']."' ";
	         	$this->db->String_Sql($sql);
	         // Ejecutar_SQL_SP sSQL

	         $xml = $this->generar_xml_guia_remision($TFA,$AdoDBDet);
	          if($xml==1)
	           {
	           	 $firma = $this->firmar_documento(
	           	 	$TFA['ClaveAcceso_GR'],
	           	 	 generaCeros($_SESSION['INGRESO']['IDEntidad'],3),
	           	 	$_SESSION['INGRESO']['item'],
	           	 	$_SESSION['INGRESO']['Clave_Certificado'],
	           	 	$_SESSION['INGRESO']['Ruta_Certificado']);
	           	 // print($firma);die();
	           	 if($firma==1)
	           	 {
	           	 	$validar_autorizado = $this->comprobar_xml_sri(
	           	 		$TFA['ClaveAcceso_GR'],
	           	 		$this->linkSriAutorizacion);
	           	 	// print_r($validar_autorizado);die();
	           	 	if($validar_autorizado == -1)
			   		 {
			   		 	$enviar_sri = $this->enviar_xml_sri(
			   		 		$TFA['ClaveAcceso_GR'],
			   		 		$this->linkSriRecepcion);
			   		 	if($enviar_sri==1)
			   		 	{
			   		 		//una vez enviado comprobamos el estado de la factura
			   		 		$resp =  $this->comprobar_xml_sri($TFA['ClaveAcceso_GR'],$this->linkSriAutorizacion);
			   		 		if($resp==1)
			   		 		{
			   		 			// print('dd');
			   		 			$resp = $this->actualizar_datos_GR($TFA);
			   		 			return  $resp;
			   		 		}
			   		 		// print_r($resp);die();
			   		 	}else
			   		 	{
			   		 		return $enviar_sri;
			   		 	}

			   		 }else 
			   		 {
			   		 	// print_r('expressiondd');die();
			   		 	if($validar_autorizado==1)
			   		 	{
			   		 		return $this->actualizar_datos_GR($TFA);
			   		 	}
			   		 	// RETORNA SI YA ESTA AUTORIZADO O SI FALL LA REVISIO EN EL SRI
			   			return $validar_autorizado;
			   		 }
	           	 }else
	           	 {
	           	 	//RETORNA SI FALLA AL FIRMAR EL XML
	           	 	return $firma;
	           	 }
	           }else
	           {
	           	//RETORNA SI FALLA EL GENERAR EL XML o si ya esta en la carpeta de autorizados
	            $resp = $this->actualizar_datos_GR($TFA);
	           	return $xml;
	           }

	       }else
	       {
	       	 // print_r($sql);die();
	       	 return -1;
	       }

        
         
    }

	function SRI_Crear_Clave_Acceso_Facturas($TFA){

		// print_r($TFA);die();
		$fecha_igualar = Leer_Campo_Empresa('Fecha_Igualar');

		$Autorizar_XML = True;
	    if(strlen($fecha_igualar) == 10){
	       // if(CFechaLong(TFA.Fecha) < CFechaLong(Fecha_Igualar)){$Autorizar_XML = False;}
	    }
	    $TextoXML = "";
	    
	    // If Autorizar_XML Then
	   // 'Averiguamos si la Factura esta a nombre del Representante
	    $TBeneficiario = Leer_Datos_Clientes($TFA['CodigoC'],$codigo=1);
	   // 'MsgBox TBeneficiario.RUC_CI_Rep & vbCrLf & TBeneficiario.Representante & vbCrLf & TBeneficiario.TD_Rep

	  
	    
	    $TFA['Cliente'] = $TBeneficiario['Representante'];
	    $TFA['TD'] = $TBeneficiario['TD_R'];
	    $TFA['CI_RUC'] = $TBeneficiario['CI_RUC_R'];
	    $TFA['TelefonoC'] = $TBeneficiario['Telefono_R'];
	    $TFA['DireccionC'] = $TBeneficiario['DireccionT'];
	    $TFA['Curso'] = $TBeneficiario['Direccion'];
	    $TFA['Grupo'] = $TBeneficiario['Grupo'];
	    $TFA['EmailC'] = $TBeneficiario['Email'];
	    $TFA['EmailR'] = $TBeneficiario['Email2'];
   		// 'Detalle de descuentos
  	  	$sql = "SELECT DF.*,CP.Reg_Sanitario,CP.Marca
        FROM Detalle_Factura As DF, Catalogo_Productos As CP
        WHERE DF.Item = '".$_SESSION['INGRESO']['item']."'
        AND DF.Periodo =  '".$_SESSION['INGRESO']['periodo']."'
        AND DF.TC = '".$TFA['TC']."'
        AND DF.Serie = '".$TFA['Serie']."'
        AND DF.Autorizacion = '".$TFA['Autorizacion']."'
        AND DF.Factura = ".$TFA['Factura']."
        AND LEN(DF.Autorizacion) >= 13
        AND DF.T <> 'A'
        AND DF.Item = CP.Item
        AND DF.Periodo = CP.Periodo
        AND DF.Codigo = CP.Codigo_Inv
        ORDER BY DF.ID,DF.Codigo ";
   		$AdoDBDet = $this->db->datos($sql);$fecha_igualar = Leer_Campo_Empresa('Fecha_Igualar');

		$Autorizar_XML = True;

		//Encabezado de la factura
		$sql2 = "SELECT T, SP, Porc_IVA, Imp_Mes, Fecha, Vencimiento, SubTotal, Sin_IVA, 
		Con_IVA, IVA, Total_MN, Razon_Social, RUC_CI, TB, Descuento, Descuento2, Servicio
		FROM Facturas
		WHERE Item = '" .$_SESSION['INGRESO']['item']. "'
		AND Periodo =  '" .$_SESSION['INGRESO']['periodo']. "'
		AND TC = '" .$TFA['TC']. "'
		AND Serie = '" .$TFA['Serie']. "'
		AND Autorizacion = '" .$TFA['Autorizacion']. "'
		AND Factura = '".$TFA['Factura']."'
		AND LEN(Autorizacion) = 13
		AND T <> 'A'";

		$AdoDBFA = $this->db->datos($sql2);

		if(count($AdoDBFA) > 0){
			$Autorizar_XML = True;
			$TFA['T'] = $AdoDBFA[0]['T'];
			$TFA['SP'] = $AdoDBFA[0]['SP'];
			$TFA['Porc_IVA'] = $AdoDBFA[0]['Porc_IVA'];
			$TFA['Imp_Mes'] = $AdoDBFA[0]['Imp_Mes'];
			$TFA['Fecha'] = $AdoDBFA[0]['Fecha'];
			$TFA['Vencimiento'] = $AdoDBFA[0]['Vencimiento'];
			$TFA['SubTotal'] = $AdoDBFA[0]['SubTotal'];
			$TFA['Sin_IVA'] = $AdoDBFA[0]['Sin_IVA'];
			$TFA['Con_IVA'] = $AdoDBFA[0]['Con_IVA'];
			$TFA['Total_IVA'] = $AdoDBFA[0]['IVA'];
			$TFA['Servicio'] = $AdoDBFA[0]['Servicio'];
			$TFA['Total_MN'] = $AdoDBFA[0]['Total_MN'];
			$TFA['Razon_Social'] = $AdoDBFA[0]['Razon_Social'];
			$TFA['RUC_CI'] = $AdoDBFA[0]['RUC_CI'];
			$TFA['TB'] = $AdoDBFA[0]['TB'];
			$TFA['Descuento'] = $AdoDBFA[0]['Descuento'];
			$TFA['Descuento2'] = $AdoDBFA[0]['Descuento2'];
			$TFA['Total_Descuento'] = $AdoDBFA[0]['Descuento'] + $AdoDBFA[0]['Descuento2'];

			Validar_Porc_IVA($TFA['Fecha']->format('Y-m-d'));

			if(strlen($TFA['Autorizacion']) >= 13){
				$TFA['Fecha'] = is_object($TFA['Fecha']) ? $TFA['Fecha'] -> format('Y-m-d') : $TFA['Fecha'];
				$TFA['ClaveAcceso'] = $this->Clave_acceso($TFA['Fecha'],'01',$TFA['Serie'],$TFA['Factura']);
			}else{
				$TFA['ClaveAcceso'] = G_NINGUNO;
			}
			$TipoIdent = "P";
			switch($TFA['TB']){
				case "R":
					$TipoIdent = $TFA['CI_RUC'] == str_repeat("9",13) ? "07" : "04";
					break;
				case "C":
					$TipoIdent = "05";
					break;
				case "P":
					$TipoIdent = "06";
					break;
				default:
					$TipoIdent = "07";
					break;
			}

			$TipoProveReemb = substr($TFA['CI_RUC'], 2, 1) == "9" ? "02" : "01";

			$sql3 = "UPDATE Facturas 
			SET Clave_Acceso = '" .$TFA['ClaveAcceso']."' 
			WHERE Item = '".$_SESSION['INGRESO']['item']."' 
			AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			AND TC = '" .$TFA['TC']."' 
			AND Serie = '" .$TFA['Serie']."' 
			AND Factura = " .$TFA['Factura']."
			AND CodigoC = '" .$TFA['CodigoC']."' 
			AND Autorizacion = '" .$TFA['Autorizacion']."' ";
			// print_r('expression');die();
			$this->db->String_Sql($sql3);

			$xml = $this->generar_xml($TFA,$AdoDBDet);
			if($xml==1)
	           {
	           	 $firma = $this->firmar_documento(
	           	 	$TFA['ClaveAcceso'],
	           	 	 generaCeros($_SESSION['INGRESO']['IDEntidad'],3),
	           	 	$_SESSION['INGRESO']['item'],
	           	 	$_SESSION['INGRESO']['Clave_Certificado'],
	           	 	$_SESSION['INGRESO']['Ruta_Certificado']);
	           	 // print($firma);die();
	           	 if($firma==1)
	           	 {
	           	 	$validar_autorizado = $this->comprobar_xml_sri(
	           	 		$TFA['ClaveAcceso'],
	           	 		$this->linkSriAutorizacion);
	           	 	// print_r($validar_autorizado);die();
	           	 	if($validar_autorizado == -1)
			   		 {
			   		 	$enviar_sri = $this->enviar_xml_sri(
			   		 		$TFA['ClaveAcceso'],
			   		 		$this->linkSriRecepcion);
			   		 	if($enviar_sri==1)
			   		 	{
			   		 		//una vez enviado comprobamos el estado de la factura
			   		 		$resp =  $this->comprobar_xml_sri($TFA['ClaveAcceso'],$this->linkSriAutorizacion);
			   		 		if($resp==1)
			   		 		{
			   		 			// print('dd');
			   		 			$resp = $this->actualizar_datos_GR($TFA);
			   		 			return  $resp;
			   		 		}
			   		 		// print_r($resp);die();
			   		 	}else
			   		 	{
			   		 		return $enviar_sri;
			   		 	}

			   		 }else 
			   		 {
			   		 	// print_r('expressiondd');die();
			   		 	if($validar_autorizado==1)
			   		 	{
			   		 		return $this->actualizar_datos_GR($TFA);
			   		 	}
			   		 	// RETORNA SI YA ESTA AUTORIZADO O SI FALL LA REVISIO EN EL SRI
			   			return $validar_autorizado;
			   		 }
	           	 }else
	           	 {
	           	 	//RETORNA SI FALLA AL FIRMAR EL XML
	           	 	return $firma;
	           	 }
	           }else
	           {
	           	//RETORNA SI FALLA EL GENERAR EL XML o si ya esta en la carpeta de autorizados
	            $resp = $this->actualizar_datos_GR($TFA);
	           	return $xml;
	           }
		}else{
			return -1;
		}
	}

    function SRI_Crear_Clave_Acceso_Guia_Remision_sin_factura($TFA)
	{
		$fecha_igualar = Leer_Campo_Empresa('Fecha_Igualar');

		$Autorizar_XML = True;
	    if(strlen($fecha_igualar) == 10){
	       // if(CFechaLong(TFA.Fecha) < CFechaLong(Fecha_Igualar)){$Autorizar_XML = False;}
	    }
	    $TextoXML = "";
	    
	    // If Autorizar_XML Then
	   // 'Averiguamos si la Factura esta a nombre del Representante
	    $TBeneficiario = Leer_Datos_Clientes($TFA['CodigoC'],$codigo=1);
	   // 'MsgBox TBeneficiario.RUC_CI_Rep & vbCrLf & TBeneficiario.Representante & vbCrLf & TBeneficiario.TD_Rep
	    
	    $TFA['Cliente'] = $TBeneficiario['Cliente'];
	    $TFA['TD'] = $TBeneficiario['TD'];
	    $TFA['CI_RUC'] = $TBeneficiario['CI_RUC'];
	    $TFA['TelefonoC'] = $TBeneficiario['Telefono'];
	    $TFA['DireccionC'] = $TBeneficiario['Direccion'];
	    $TFA['Curso'] = $TBeneficiario['Direccion'];
	    $TFA['Grupo'] = $TBeneficiario['Grupo'];
	    $TFA['EmailC'] = $TBeneficiario['Email'];
	    $TFA['EmailR'] = $TBeneficiario['Email2'];

	    $TFA['T'] = 'P';
	    $TFA['SP'] = '0';
	    $TFA['Razon_Social'] = $TBeneficiario['Cliente'];
	    $TFA['RUC_CI'] = $TBeneficiario['CI_RUC'];
	    $TFA['TB'] =  $TBeneficiario['TD'];
	        

   		// 'Detalle de descuentos
  	  	$sql = "SELECT DF.*,CP.Reg_Sanitario,CP.Marca
        FROM Detalle_Factura As DF, Catalogo_Productos As CP
        WHERE DF.Item = '".$_SESSION['INGRESO']['item']."'
        AND DF.Periodo =  '".$_SESSION['INGRESO']['periodo']."'
        AND DF.TC = '".$TFA['TC']."'
        AND DF.Serie = '".$TFA['Serie']."'
        AND DF.Autorizacion = '".$TFA['Autorizacion']."'
        AND DF.Factura = ".$TFA['Remision']."
        AND DF.Item = CP.Item
        AND DF.Periodo = CP.Periodo
        AND DF.Codigo = CP.Codigo_Inv
        ORDER BY DF.ID,DF.Codigo ";
        // print_r($sql);
   		$AdoDBDet = $this->db->datos($sql);

   		// print_r($AdoDBDet);
   		// die();
      
		// 'Encabezado de la Guia de Remision
    	
		$sql = "SELECT GR.Remision,GR.Comercial,GR.CIRUC_Comercial,GR.Entrega,GR.CIRUC_Entrega,GR.CiudadGRI,GR.CiudadGRF,
		        GR.Placa_Vehiculo,GR.FechaGRE,GR.FechaGRI,GR.FechaGRF,GR.Pedido,GR.Zona,GR.Serie_GR,GR.Autorizacion_GR,
		        GR.Clave_Acceso_GR,GR.Hora_Aut_GR,GR.Estado_SRI_GR,GR.Error_FA_SRI,GR.Fecha_Aut_GR,GR.Fecha 
		        FROM Facturas_Auxiliares As GR 
		        WHERE GR.Item = '".$_SESSION['INGRESO']['item']."'
		        AND GR.Periodo =  '".$_SESSION['INGRESO']['periodo']."'
		        AND GR.TC = '".$TFA['TC']."' 
		        AND GR.Serie = '".$TFA['Serie']."' 
		        AND GR.Autorizacion = '".$TFA['Autorizacion']."' 
		        AND GR.Factura =".$TFA['Factura']." 
		        AND Remision = '".$TFA['Remision']."'
		        AND LEN(GR.Autorizacion_GR) = 13 
		        AND GR.Remision > 0 ";
		$AdoDBFA = $this->db->datos($sql);
    	// print_r($sql);
    	// print_r($AdoDBFA);
    	// print_r($TFA);
    	// die();

	     if(count($AdoDBFA) > 0)
	     {
	         $Autorizar_XML = True;
	         $TFA['Autorizacion_GR'] = $AdoDBFA[0]['Autorizacion_GR'];
	         $TFA['Serie_GR'] = $AdoDBFA[0]['Serie_GR'];
	         $TFA['Remision'] = $AdoDBFA[0]['Remision'];
	         $TFA['Fecha'] = $AdoDBFA[0]['FechaGRE'];

	        // -- 'MsgBox "Validar Porc IVA"
	         Validar_Porc_IVA($TFA['Fecha']->format('Y-m-d'));
	        // -- 'Generamos la Clave de acceso
	        // -- '& Format$(TFA.Fecha, "ddmmyyyy") &
	         if(strlen($TFA['Autorizacion_GR']) >= 13){
	            $TFA['ClaveAcceso_GR'] = $this->Clave_acceso($TFA['FechaGRE']->format('Y-m-d'),'06',$TFA['Serie_GR'],$TFA['Remision']);
	         }else{
	            $TFA['ClaveAcceso_GR'] = G_NINGUNO;
	         }

	         // print_r($TFA);die();
	         // TFA.Hora_GR = Format$(Time, FormatoTimes)
	         // SRI_Autorizacion.Clave_De_Acceso = TFA.ClaveAcceso_GR
	         	$sql = "UPDATE Facturas_Auxiliares 
	            SET Clave_Acceso_GR = '" .$TFA['ClaveAcceso_GR']."' 
	            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
	            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
	            AND TC = '" .$TFA['TC']."' 
	            AND Serie = '" .$TFA['Serie']."' 
	            AND Factura = " .$TFA['Factura']." 
	            AND Remision = " .$TFA['Remision']." 
	            AND CodigoC = '" .$TFA['CodigoC']."' 
	            AND Autorizacion = '" .$TFA['Autorizacion']."' ";
	         	$this->db->String_Sql($sql);
	         // Ejecutar_SQL_SP sSQL

	         	// print_r($TFA);print_r($AdoDBDet);
// die();
	         $xml = $this->generar_xml_guia_remision($TFA,$AdoDBDet);
	         // die();
	          if($xml==1)
	           {
	           	 $firma = $this->firmar_documento(
	           	 	$TFA['ClaveAcceso_GR'],
	           	 	 generaCeros($_SESSION['INGRESO']['IDEntidad'],3),
	           	 	$_SESSION['INGRESO']['item'],
	           	 	$_SESSION['INGRESO']['Clave_Certificado'],
	           	 	$_SESSION['INGRESO']['Ruta_Certificado']);
	           	 // print($firma);die();
	           	 if($firma==1)
	           	 {
	           	 	$validar_autorizado = $this->comprobar_xml_sri(
	           	 		$TFA['ClaveAcceso_GR'],
	           	 		$this->linkSriAutorizacion);
	           	 	// print_r($validar_autorizado);die();
	           	 	if($validar_autorizado == -1)
			   		 {
			   		 	$enviar_sri = $this->enviar_xml_sri(
			   		 		$TFA['ClaveAcceso_GR'],
			   		 		$this->linkSriRecepcion);
			   		 	if($enviar_sri==1)
			   		 	{
			   		 		//una vez enviado comprobamos el estado de la factura
			   		 		$resp =  $this->comprobar_xml_sri($TFA['ClaveAcceso_GR'],$this->linkSriAutorizacion);
			   		 		if($resp==1)
			   		 		{
			   		 			// print('dd');
			   		 			$resp = $this->actualizar_datos_GR($TFA);
			   		 			return  $resp;
			   		 		}
			   		 		// print_r($resp);die();
			   		 	}else
			   		 	{
			   		 		return $enviar_sri;
			   		 	}

			   		 }else 
			   		 {
			   		 	// print_r('expressiondd');die();
			   		 	if($validar_autorizado==1)
			   		 	{
			   		 		return $this->actualizar_datos_GR($TFA);
			   		 	}
			   		 	// RETORNA SI YA ESTA AUTORIZADO O SI FALL LA REVISIO EN EL SRI
			   			return $validar_autorizado;
			   		 }
	           	 }else
	           	 {
	           	 	//RETORNA SI FALLA AL FIRMAR EL XML
	           	 	return $firma;
	           	 }
	           }else
	           {
	           	//RETORNA SI FALLA EL GENERAR EL XML o si ya esta en la carpeta de autorizados
	            $resp = $this->actualizar_datos_GR($TFA);
	           	return $xml;
	           }

	       }else
	       {
	       	 // print_r($sql);die();
	       	 return -1;
	       }

        
         
    }

	function generar_xml_guia_remision($cabecera,$detalle)
	{

	// print_r($cabecera);
	// print_r('expression');
	// print_r($detalle);
	// die();
	$entidad = $_SESSION['INGRESO']['IDEntidad'];
	$empresa = $_SESSION['INGRESO']['item'];
	$this->generar_carpetas($entidad,$empresa);
	$ambiente =$_SESSION['INGRESO']['Ambiente'];
	$RIMPE =  $this->datos_rimpe();
	$sucursal = $this->catalogo_lineas('RE',$cabecera['Serie_GR']);
	 if(count($sucursal)>0)
	 {
	 	$cabecera[0]['Nombre_Establecimiento'] = $sucursal[0]['Nombre_Establecimiento'];
	 	$cabecera[0]['Direccion_Establecimiento'] = $sucursal[0]['Direccion_Establecimiento'];
	 	$cabecera[0]['Telefono_Establecimiento'] = $sucursal[0]['Telefono_Estab'];
	 	$cabecera[0]['Ruc_Establecimiento'] = $sucursal[0]['RUC_Establecimiento'];
	 	$cabecera[0]['Email_Establecimiento'] = $sucursal[0]['Email_Establecimiento'];
	 	$cabecera[0]['Placa_Vehiculo'] ='.';
	 	$cabecera[0]['Cta_Establecimiento'] = '.';
	 	if(isset($sucursal[0]['Placa_Vehiculo']))
	 	{
	 		$cabecera['Placa_Vehiculo'] = $sucursal[0]['Placa_Vehiculo'];
	 	}
	 	if (isset($sucursal[0]['Cta_Establecimiento'])) {
	 		$cabecera['Cta_Establecimiento'] = $sucursal[0]['Cta_Establecimiento'];
	 	}		 	
	 }

	$carpeta_autorizados = dirname(__DIR__)."/entidades/entidad_".generaCeros($entidad,3).'/CE'.generaCeros($empresa,3)."/Autorizados";		  
	if(file_exists($carpeta_autorizados.'/'.$cabecera['ClaveAcceso_GR'].'.xml'))
	{
		$respuesta = array('1'=>'Autorizado');
		return $respuesta;
	}

	    $xml = new DOMDocument( "1.0", "UTF-8");
        $xml->formatOutput = true;
        $xml->preserveWhiteSpace = false;
	    $xml->xmlStandalone = true;

	    $xml_inicio = $xml->createElement( "guiaRemision" );
        $xml_inicio->setAttribute( "id", "comprobante" );
        $xml_inicio->setAttribute( "version", "1.0.0" );
        //informacion de cabecera
	    $xml_infotributaria = $xml->createElement("infoTributaria");
	    $xml_ambiente = $xml->createElement("ambiente",$ambiente);
	    $xml_tipoEmision = $xml->createElement("tipoEmision","1");
	    $xml_razonSocial = $xml->createElement("razonSocial",$_SESSION['INGRESO']['Razon_Social']);
	    $xml_nombreComercial = $xml->createElement("nombreComercial",$_SESSION['INGRESO']['Nombre_Comercial']);
	    $xml_ruc = $xml->createElement("ruc",$_SESSION['INGRESO']['RUC']);
	    $xml_claveAcceso = $xml->createElement("claveAcceso",$cabecera['ClaveAcceso_GR']);
	    $xml_codDoc = $xml->createElement("codDoc",'06');
	    $xml_estab = $xml->createElement("estab",substr($cabecera['Serie_GR'], 0,3));
	    $xml_ptoEmi = $xml->createElement("ptoEmi",substr($cabecera['Serie_GR'], 3,3));
	    $xml_secuencial = $xml->createElement("secuencial",$this->generaCeros($cabecera['Remision'],9));
	    $xml_dirMatriz = $xml->createElement("dirMatriz",$_SESSION['INGRESO']['Direccion']);




        $xml_infotributaria->appendChild($xml_ambiente);
        $xml_infotributaria->appendChild($xml_tipoEmision);
        $xml_infotributaria->appendChild($xml_razonSocial);
        $xml_infotributaria->appendChild($xml_nombreComercial);
        $xml_infotributaria->appendChild($xml_ruc);
        $xml_infotributaria->appendChild($xml_claveAcceso);
        $xml_infotributaria->appendChild($xml_codDoc);
        $xml_infotributaria->appendChild($xml_estab);
        $xml_infotributaria->appendChild($xml_ptoEmi);
        $xml_infotributaria->appendChild($xml_secuencial);
        $xml_infotributaria->appendChild($xml_dirMatriz);

		if(count($RIMPE)>0)
		{
			if($RIMPE['@micro']!='.' && $RIMPE['@micro']!='' && $RIMPE['@micro']=='CONTRIBUYENTE RÉGIMEN RIMPE' )
			{
				$xml_contribuyenteRimpe = $xml->createElement( "contribuyenteRimpe",$RIMPE['@micro']);
				$xml_infotributaria->appendChild( $xml_contribuyenteRimpe);
			}
			if($RIMPE['@Agente']!='.' && $RIMPE['@Agente']!='')
			{
				$xml_agenteRetencion = $xml->createElement( "agenteRetencion",'1');
				$xml_infotributaria->appendChild( $xml_agenteRetencion);
			}
		}

        // $xml->appendChild($xml_infotributaria);

        $xml_inicio->appendChild($xml_infotributaria);
        //fin de cabecera


	    $xml_infoCompGuia = $xml->createElement( "infoGuiaRemision");
	    
	    if(isset($cabecera[0]['Nombre_Establecimiento']) &&  strlen($cabecera[0]['Nombre_Establecimiento'])>0 && $cabecera['Nombre_Establecimiento']!='.')
		{
			$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",$cabecera[0]['Direccion_Establecimiento']);
		}else
		{
			$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",strtoupper($_SESSION['INGRESO']['Direccion']));
		}    
	    $xml_infoCompGuia->appendChild($xml_dirEstablecimiento);

	    $xml_dirpartida = $xml->createElement('dirPartida',$cabecera['CiudadGRI']);
	    $xml_infoCompGuia->appendChild($xml_dirpartida);

	    $xml_razonsocialtrans = $xml->createElement('razonSocialTransportista',$cabecera['Comercial']);
	    $xml_infoCompGuia->appendChild($xml_razonsocialtrans);

	    $DigVerif =  Digito_Verificador($cabecera["CIRUCComercial"]);
	     // print_r($cabecera["CIRUCComercial"]);
	    // print_r($DigVerif);die();
        $TipoIdent = "P";
        switch (trim($DigVerif['Tipo_Beneficiario'])) {
        	case 'R':
        		if($cabecera['CIRUCComercial']=='9999999999999'){$TipoIdent = '07';}else{$TipoIdent = '04';}
        		break;
        	case 'C':
        	 	$TipoIdent = '05';
        		break;
        	case 'P':
        	 	$TipoIdent = '06';
        		break;
        	default:        	
        		 	$TipoIdent = '07';
        		break;
        }
           

	    $xml_tipoidetrans = $xml->createElement('tipoIdentificacionTransportista',$TipoIdent);
	    $xml_infoCompGuia->appendChild($xml_tipoidetrans);

	    $xml_ructrans = $xml->createElement('rucTransportista',$cabecera['CIRUCComercial']);
	    $xml_infoCompGuia->appendChild($xml_ructrans);

	    $xml_rise = $xml->createElement('rise','000');
	    $xml_infoCompGuia->appendChild($xml_rise);

		$xml_obligadoContabilidad = $xml->createElement("obligadoContabilidad",$_SESSION['INGRESO']['Obligado_Conta']);
	    $xml_infoCompGuia->appendChild($xml_obligadoContabilidad);

	    $fecha = date("d/m/Y", strtotime($cabecera['FechaGRI']));
	    $xml_fechainitrans = $xml->createElement('fechaIniTransporte', $fecha);
	    $xml_infoCompGuia->appendChild($xml_fechainitrans);

	    $fecha2 =  date("d/m/Y", strtotime($cabecera['FechaGRF']));
	    $xml_fechafintrans = $xml->createElement('fechaFinTransporte', $fecha2);
	    $xml_infoCompGuia->appendChild($xml_fechafintrans);

	    $xml_placa = $xml->createElement('placa',$cabecera['Placa_Vehiculo']);
	    $xml_infoCompGuia->appendChild($xml_placa);	


	    $xml_destinatarios = $xml->createElement( "destinatarios");

	        $xml_destinatario = $xml->createElement( "destinatario"); 

	        $xml_iddestinatario = $xml->createElement('identificacionDestinatario',$cabecera['CIRUCEntrega']);
	    	$xml_destinatario->appendChild($xml_iddestinatario);

	    	$xml_razondestinatario = $xml->createElement('razonSocialDestinatario',$cabecera['Entrega']);
	    	$xml_destinatario->appendChild($xml_razondestinatario);	


	    	$xml_dirdestinatario = $xml->createElement('dirDestinatario',$cabecera['CiudadGRF']);
	    	$xml_destinatario->appendChild($xml_dirdestinatario);	

	    	$xml_motivo = $xml->createElement('motivoTraslado',"Translado de mercaderia");
	    	$xml_destinatario->appendChild($xml_motivo);	

	    	$xml_ruta = $xml->createElement('ruta',"De ".$cabecera["CiudadGRI"]." a ".$cabecera["CiudadGRF"]);
	    	$xml_destinatario->appendChild($xml_ruta);

	    	switch ($cabecera['TC']) {
	    		case 'FA':
	    		$xml_coddocsus = $xml->createElement('codDocSustento','01');
	    			break;
	    		case 'NV':
	    		$xml_coddocsus = $xml->createElement('codDocSustento','02');
	    			break;    				    		
	    		default:
	    		$xml_coddocsus = $xml->createElement('codDocSustento','00');
	    			break;
	    	}
	    	$xml_destinatario->appendChild($xml_coddocsus);

	    	$cadena=substr($cabecera['Serie'], 0, 3)."-".substr($cabecera['Serie'], 3, 6)."-".generaCeros($cabecera['Factura'],9);
	    	$xml_numdocsus = $xml->createElement('numDocSustento',$cadena);
	    	$xml_destinatario->appendChild($xml_numdocsus);

	    	$xml_numautodocsus = $xml->createElement('numAutDocSustento',$cabecera["Autorizacion"]);
	    	$xml_destinatario->appendChild($xml_numautodocsus);

	    	$xml_fechaemidocsus = $xml->createElement('fechaEmisionDocSustento',$cabecera["Fecha"]->format('d/m/Y'));
	    	$xml_destinatario->appendChild($xml_fechaemidocsus);

	    		if(count($detalle)>0)
	    		{
	    			$xml_detalles = $xml->createElement( "detalles");
	    			foreach ($detalle as $key => $value) {	    					
	    	 			$xml_detalle = $xml->createElement( "detalle");

	    	 			$Producto = trim($value["Producto"]);
	    	 			if(isset($cabecera['Imp_Mes']) && $cabecera['Imp_Mes']){
		                       if(strlen($value["Ticket"]) > 1){ $Producto = $Producto.", ".$value["Ticket"];}
		                       if(strlen($value["Mes"]) > 1){ $Producto = $Producto.": ".$value["Mes"];}
                    	}
	                    if($cabecera['SP']){
	                       $Producto = $Producto
	                                .", Lote No. ".$value["Lote_No"]
	                                .", ELAB. ".$value["Fecha_Fab"]
	                                .", VENC. ".$value["Fecha_Exp"]
	                                .", Reg. Sanit. ".$value["Reg_Sanitario"]
	                                .", Modelo: ".$value["Modelo"]
	                                .", Serie No. ".$value["Serie_No"]
	                                .", Procedencia: ".$value["Procedencia"];
	                    }
	                    $SubTotal = ($value["Cantidad"] * $value["Precio"]) - ($value["Total_Desc"] + $value["Total_Desc2"]);

	    	 			  	$xml_codigo = $xml->createElement('codigoInterno',$value["Codigo"]);
	    	 			  	$xml_detalle->appendChild($xml_codigo);

	    	 			  	$xml_codigo = $xml->createElement('descripcion',$this->quitar_carac($Producto));
	    	 			  	$xml_detalle->appendChild($xml_codigo);


	    	 			  	$xml_codigo = $xml->createElement('cantidad',$value["Cantidad"]);
	    	 			  	$xml_detalle->appendChild($xml_codigo);

	    	 			$xml_detalles->appendChild($xml_detalle);   		
	    			}

	    			$xml_destinatario->appendChild($xml_detalles);
	    		}

	    $xml_destinatarios->appendChild($xml_destinatario);


	  
        $xml_inicio->appendChild($xml_infoCompGuia);        
        $xml_inicio->appendChild($xml_destinatarios);




        //fin de xml retencion
        $xml_infoAdicional = $xml->createElement("infoAdicional");

        if($cabecera['Cliente'] <>G_NINGUNO &&  $cabecera['Razon_Social'] <> $cabecera['Cliente'])
        {
           if(strlen($cabecera['Cliente']) > 1){
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['Cliente']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Beneficiario");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);
        	}

            if(strlen($cabecera['Curso']) > 1){
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['Grupo']."-".$cabecera['Curso']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Ubicacion");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);
        	}
	    }

	    if (strlen($cabecera['DireccionC']) > 1){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['DireccionC']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Direccion");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);

        	}
         if (strlen($cabecera['TelefonoC']) > 1){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['TelefonoC']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Telefono");
        	$xml_infoAdicional->appendChild($xml_campoAdicional);
        	}
         if( strlen($cabecera['EmailC']) > 1){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['EmailC']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Email");
        	$xml_infoAdicional->appendChild($xml_campoAdicional);
        	}         

         if(isset($cabecera['Observacion']) && $cabecera['Observacion']!='.'){
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['Observacion']);
        	 $xml_campoAdicional->setAttribute( "nombre", "motivoTraslado");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);
        	}
        if(isset($cabecera['Nota']) && $cabecera['Nota']!='.'){
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['Nota']);
        	 $xml_campoAdicional->setAttribute( "nombre", "notaAuxiliar");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);
        	}
         if(isset($cabecera['Lugar_Entrega'])){
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['Lugar_Entrega']);
        	 $xml_campoAdicional->setAttribute( "nombre", "lugarEntrega");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);
        	}


        	$xml_inicio->appendChild($xml_infoAdicional);
        	$xml->appendChild($xml_inicio);

		     $ruta_G = dirname(__DIR__).'/entidades/entidad_'.generaCeros($entidad,3)."/CE".generaCeros($empresa,3).'/Generados';
		     // print_r($ruta_G);die();
			if($archivo = fopen($ruta_G.'/'.$cabecera['ClaveAcceso_GR'].'.xml',"w+b"))
			  {
			  	fwrite($archivo,$xml->saveXML());
			  	// die();
			  	return 1;
			  }else
			  {
			  	return -1;
			  }


	}

	function generar_xml_nota_credito($cabecera,$detalle)
	{

	// print_r($cabecera);
	// print_r('expression');
	// print_r($detalle);
	// die();
	$entidad = $_SESSION['INGRESO']['IDEntidad'];
	$empresa = $_SESSION['INGRESO']['item'];
	$this->generar_carpetas($entidad,$empresa);
	$ambiente =$_SESSION['INGRESO']['Ambiente'];
	$RIMPE =  $this->datos_rimpe();
	$sucursal = $this->catalogo_lineas('NC',$cabecera['Serie_NC']);
	 if(count($sucursal)>0)
	 {
	 	$cabecera[0]['Nombre_Establecimiento'] = $sucursal[0]['Nombre_Establecimiento'];
	 	$cabecera[0]['Direccion_Establecimiento'] = $sucursal[0]['Direccion_Establecimiento'];
	 	$cabecera[0]['Telefono_Establecimiento'] = $sucursal[0]['Telefono_Estab'];
	 	$cabecera[0]['Ruc_Establecimiento'] = $sucursal[0]['RUC_Establecimiento'];
	 	$cabecera[0]['Email_Establecimiento'] = $sucursal[0]['Email_Establecimiento'];
	 	$cabecera[0]['Placa_Vehiculo'] ='.';
	 	$cabecera[0]['Cta_Establecimiento'] = '.';
	 	if(isset($sucursal[0]['Placa_Vehiculo']))
	 	{
	 		$cabecera['Placa_Vehiculo'] = $sucursal[0]['Placa_Vehiculo'];
	 	}
	 	if (isset($sucursal[0]['Cta_Establecimiento'])) {
	 		$cabecera['Cta_Establecimiento'] = $sucursal[0]['Cta_Establecimiento'];
	 	}		 	
	 }

	$carpeta_autorizados = dirname(__DIR__)."/entidades/entidad_".generaCeros($entidad,3).'/CE'.generaCeros($empresa,3)."/Autorizados";		  
	if(file_exists($carpeta_autorizados.'/'.$cabecera['ClaveAcceso_NC'].'.xml'))
	{
		$respuesta = array('1'=>'Autorizado');
		return $respuesta;
	}

	    $xml = new DOMDocument( "1.0", "UTF-8");
        $xml->formatOutput = true;
        $xml->preserveWhiteSpace = false;
	    $xml->xmlStandalone = true;

	    $xml_inicio = $xml->createElement( "notaCredito" );
        $xml_inicio->setAttribute( "id", "comprobante" );
        $xml_inicio->setAttribute( "version", "1.0.0" );
        //informacion de cabecera
	    $xml_infotributaria = $xml->createElement("infoTributaria");
	    $xml_ambiente = $xml->createElement("ambiente",$ambiente);
	    $xml_tipoEmision = $xml->createElement("tipoEmision","1");
	    $xml_razonSocial = $xml->createElement("razonSocial",$this->quitar_carac($_SESSION['INGRESO']['Razon_Social']));
	    $xml_nombreComercial = $xml->createElement("nombreComercial",$this->quitar_carac($_SESSION['INGRESO']['Nombre_Comercial']));
	    $xml_ruc = $xml->createElement("ruc",$_SESSION['INGRESO']['RUC']);
	    $xml_claveAcceso = $xml->createElement("claveAcceso",$cabecera['ClaveAcceso_NC']);
	    $xml_codDoc = $xml->createElement("codDoc",'04');
	    $xml_estab = $xml->createElement("estab",substr($cabecera['Serie_NC'], 0,3));
	    $xml_ptoEmi = $xml->createElement("ptoEmi",substr($cabecera['Serie_NC'], 3,3));
	    $xml_secuencial = $xml->createElement("secuencial",$this->generaCeros($cabecera['Nota_Credito'],9));
	    $xml_dirMatriz = $xml->createElement("dirMatriz",$_SESSION['INGRESO']['Direccion']);




        $xml_infotributaria->appendChild($xml_ambiente);
        $xml_infotributaria->appendChild($xml_tipoEmision);
        $xml_infotributaria->appendChild($xml_razonSocial);
        $xml_infotributaria->appendChild($xml_nombreComercial);
        $xml_infotributaria->appendChild($xml_ruc);
        $xml_infotributaria->appendChild($xml_claveAcceso);
        $xml_infotributaria->appendChild($xml_codDoc);
        $xml_infotributaria->appendChild($xml_estab);
        $xml_infotributaria->appendChild($xml_ptoEmi);
        $xml_infotributaria->appendChild($xml_secuencial);
        $xml_infotributaria->appendChild($xml_dirMatriz);

		if(count($RIMPE)>0)
		{
			if($RIMPE['@micro']!='.' && $RIMPE['@micro']!='' && $RIMPE['@micro']=='CONTRIBUYENTE RÉGIMEN RIMPE' )
			{
				$xml_contribuyenteRimpe = $xml->createElement( "contribuyenteRimpe",$RIMPE['@micro']);
				$xml_infotributaria->appendChild( $xml_contribuyenteRimpe);
			}
			if($RIMPE['@Agente']!='.' && $RIMPE['@Agente']!='')
			{
				$xml_agenteRetencion = $xml->createElement( "agenteRetencion",'1');
				$xml_infotributaria->appendChild( $xml_agenteRetencion);
			}
		}

        // $xml->appendChild($xml_infotributaria);

        $xml_inicio->appendChild($xml_infotributaria);
        //fin de cabecera

	    $xml_infoNotaCredito = $xml->createElement( "infoNotaCredito");
	    
	    	$xml_fechaemidocsus = $xml->createElement('fechaEmision',date('d/m/Y',strtotime($cabecera["Fecha_NC"])));
	    	$xml_infoNotaCredito->appendChild($xml_fechaemidocsus);

	
	    if(isset($cabecera[0]['Nombre_Establecimiento']) &&  strlen($cabecera[0]['Nombre_Establecimiento'])>0 && $cabecera[0]['Nombre_Establecimiento']!='.')
		{
			$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",$cabecera[0]['Direccion_Establecimiento']);
		}else
		{
			$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",strtoupper($_SESSION['INGRESO']['Direccion']));
		}    
	   $xml_infoNotaCredito->appendChild($xml_dirEstablecimiento);

	    // print_r($cabecera);

	    //codigo verificador 
      	switch ($cabecera['TB']) {
      		case 'R':
      			if($cabecera['RUC_CI']=='9999999999999')
				  {
				  	$cabecera['tipoIden']='07';
			      }else{
      				$cabecera['tipoIden']='04';
      			}
      			break;
      		case 'C':
      			$cabecera['tipoIden']='05';
      			break;
      		case 'P':
      			$cabecera['tipoIden']='06';
      			break;
      			default:
      			$cabecera['tipoIden']='07';
      			break;
      	}

      	$xml_tipoIdentificacionComprador = $xml->createElement( "tipoIdentificacionComprador",$cabecera['tipoIden'] );
		$xml_infoNotaCredito->appendChild( $xml_tipoIdentificacionComprador );	 

		$xml_razonSocialComprador = $xml->createElement( "razonSocialComprador",$cabecera['Razon_Social'] );
		$xml_infoNotaCredito->appendChild( $xml_razonSocialComprador );

		$xml_identificacionComprador = $xml->createElement( "identificacionComprador",$cabecera['RUC_CI'] );		
		$xml_infoNotaCredito->appendChild( $xml_identificacionComprador );

		$xml_obligadoContabilidad = $xml->createElement("obligadoContabilidad",$_SESSION['INGRESO']['Obligado_Conta']);
	    $xml_infoNotaCredito->appendChild($xml_obligadoContabilidad);
		   

	    $xml_codDocModificado = $xml->createElement('codDocModificado','01');
	    $xml_infoNotaCredito->appendChild($xml_codDocModificado);

	    $xml_numDocModificado = $xml->createElement('numDocModificado',substr($cabecera['Serie'],0,3).'-'.substr($cabecera['Serie'],3,6).'-'.generaCeros($cabecera['Factura'],9));
		$xml_infoNotaCredito->appendChild($xml_numDocModificado);

		$xml_fechaEmisionDocSustento = $xml->createElement('fechaEmisionDocSustento',$cabecera['Fecha']->format('d/m/Y'));
		$xml_infoNotaCredito->appendChild($xml_fechaEmisionDocSustento);


	    $xml_totalSinImpuestos = $xml->createElement('totalSinImpuestos', $cabecera['TOTAL_SIN_IMPUESTOS']);
	    $xml_infoNotaCredito->appendChild($xml_totalSinImpuestos);

	    $xml_valorModificacion = $xml->createElement('valorModificacion', $cabecera['VALOR_MODIFICACION']);
	    $xml_infoNotaCredito->appendChild($xml_valorModificacion);

	    $xml_moneda = $xml->createElement('moneda','DOLAR');
	    $xml_infoNotaCredito->appendChild($xml_moneda);	

	    $xml_totalConImpuestos = $xml->createElement( "totalConImpuestos" );
		//sin iva
		$xml_totalImpuesto = $xml->createElement( "totalImpuesto" );		 

		$xml_codigo = $xml->createElement( "codigo",'2' );
		if(($cabecera['Porc_IVA'] * 100) > 12 ){
             $xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'3' );
           }else{
             $xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'2' );
           }

		$xml_baseImponible = $xml->createElement( "baseImponible",$cabecera['BASEIMPONIBLE'] );
		//$xml_tarifa = $xml->createElement( "tarifa",'0.00' );
		$xml_valor = $xml->createElement( "valor",$cabecera['Total_IVA_NC'] );
		
		$xml_totalImpuesto->appendChild( $xml_codigo );
		$xml_totalImpuesto->appendChild( $xml_codigoPorcentaje );
		$xml_totalImpuesto->appendChild( $xml_baseImponible );
		//$xml_totalImpuesto->appendChild( $xml_tarifa );
		$xml_totalImpuesto->appendChild( $xml_valor );
		$xml_totalConImpuestos->appendChild( $xml_totalImpuesto );
        
        $xml_infoNotaCredito->appendChild( $xml_totalConImpuestos );

        $xml_motivo = $xml->createElement('motivo','Anulacion por Nota de Credito');
	    $xml_infoNotaCredito->appendChild($xml_motivo);	


        $xml_inicio->appendChild($xml_infoNotaCredito);  

		if(count($detalle)>0)
		{
			$xml_detalles = $xml->createElement( "detalles");
			foreach ($detalle as $key => $value) {	    					
	 			$xml_detalle = $xml->createElement( "detalle");

	 			$CodAdicional = CambioCodigoCtaSup($value["Codigo_Inv"]);

	 			$Producto = trim($this->quitar_carac($value["Producto"]));	 			
                // $SubTotal = ($value["Cantidad"] * $value["Precio"]) - ($value["Total_Desc"] + $value["Total_Desc2"]);

 			  	$xml_codigo = $xml->createElement('codigoInterno',$value["Codigo_Inv"]);
 			  	$xml_detalle->appendChild($xml_codigo);

 			  	$xml_codigoAdi = $xml->createElement('codigoAdicional',$CodAdicional);
 			  	$xml_detalle->appendChild($xml_codigoAdi);

 			  	$xml_descripcion = $xml->createElement('descripcion',$Producto);
 			  	$xml_detalle->appendChild($xml_descripcion);

 			  	$xml_cantidad = $xml->createElement('cantidad',$value["Cantidad"]);
 			  	$xml_detalle->appendChild($xml_cantidad);

 			  	$xml_precio = $xml->createElement('precioUnitario',$value["Precio"]);
 			  	$xml_detalle->appendChild($xml_precio);

 			  	$xml_descuento = $xml->createElement('descuento',$value["Descuento"]);
 			  	$xml_detalle->appendChild($xml_descuento);

 			  	$xml_sinImpu = $xml->createElement('precioTotalSinImpuesto',$value["Total"]-$value["Descuento"]);
 			  	$xml_detalle->appendChild($xml_sinImpu);

 			  	$xml_impuestos = $xml->createElement( "impuestos" );
				$xml_impuesto = $xml->createElement( "impuesto" );
				$xml_codigo = $xml->createElement( "codigo",'2' );

					if($value['Total_IVA'] == 0)
					{
						$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'0' );
						$xml_tarifa = $xml->createElement( "tarifa",'0.00' );
					}
					else
					{
						if(($cabecera['Porc_IVA']*100) > 12)
						{
							$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'3' );
						}
						else
						{
							$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'2' );
						}
						$xml_tarifa = $xml->createElement( "tarifa",$cabecera['Porc_IVA']*100 );
						
					}
					$xml_baseImponible = $xml->createElement( "baseImponible",$value['Total']-$value['Descuento'] );
					$xml_valor = $xml->createElement( "valor",number_format($value['Total_IVA'],2,'.','')  );

					$xml_impuesto->appendChild( $xml_codigo );
					$xml_impuesto->appendChild( $xml_codigoPorcentaje );
					$xml_impuesto->appendChild( $xml_tarifa );
					$xml_impuesto->appendChild( $xml_baseImponible );
					$xml_impuesto->appendChild( $xml_valor );
				
					$xml_impuestos->appendChild( $xml_impuesto );
					$xml_detalle->appendChild( $xml_impuestos );
					$xml_detalles->appendChild( $xml_detalle );


	 			$xml_detalles->appendChild($xml_detalle);   		
			}

			$xml_inicio->appendChild($xml_detalles);
		}      
        // $xml_inicio->appendChild($xml_destinatarios);




        //fin de xml retencion
        $xml_infoAdicional = $xml->createElement("infoAdicional");

        if($cabecera['Cliente'] <>G_NINGUNO &&  $cabecera['Razon_Social'] <> $cabecera['Cliente'])
        {
           if( isset($cabecera['Cliente']) &&  strlen($cabecera['Cliente']) > 1){
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['Cliente']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Beneficiario");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);
        	}

            if( isset($cabecera['Curso']) &&   strlen($cabecera['Curso']) > 1){
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['Grupo']."-".$cabecera['Curso']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Ubicacion");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);
        	}
	    }

	    if ( isset($cabecera['DireccionC']) &&  strlen($cabecera['DireccionC']) > 1){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['DireccionC']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Direccion");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);

        	}
         if ( isset($cabecera['TelefonoC']) && strlen($cabecera['TelefonoC']) > 1){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['TelefonoC']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Telefono");
        	$xml_infoAdicional->appendChild($xml_campoAdicional);
        	}
         if( isset($cabecera['EmailC']) && strlen($cabecera['EmailC']) > 1){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['EmailC']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Email");
        	$xml_infoAdicional->appendChild($xml_campoAdicional);
        	}
        	$xml_inicio->appendChild($xml_infoAdicional);
        
        	$xml->appendChild($xml_inicio);

		     $ruta_G = dirname(__DIR__).'/entidades/entidad_'.generaCeros($entidad,3)."/CE".generaCeros($empresa,3).'/Generados';
		     // print_r($ruta_G);die();
			if($archivo = fopen($ruta_G.'/'.$cabecera['ClaveAcceso_NC'].'.xml',"w+b"))
			  {
			  	fwrite($archivo,$xml->saveXML());
			  	// die();
			  	return 1;
			  }else
			  {
			  	return -1;
			  }


	}
	

    function generaCeros($numero, $tamaño=null)
    {
	   //obtengop el largo del numero
	   $largo_numero = strlen($numero);
	   //especifico el largo maximo de la cadena
	   if($tamaño==null)
	   {
		  $largo_maximo = 7;
	   }
	   else
	   {
		 $largo_maximo = $tamaño;
	   }
	   //tomo la cantidad de ceros a agregar
	   $agregar = $largo_maximo - $largo_numero;
	   //agrego los ceros
	   for($i =0; $i<$agregar; $i++){
	     $numero = "0".$numero;
	   }
	   //retorno el valor con ceros
	   return $numero;
    }

    function digito_verificador($cadena)
    {
	    $cadena=trim($cadena);
	    $baseMultiplicador=7;
	    $aux=new SplFixedArray(strlen($cadena));
	    $aux=$aux->toArray();
	    $multiplicador=2;
	    $total=0;
	    $verificador=0;
	    for($i=count($aux)-1;$i>=0;--$i)
	    {
		    $aux[$i] = substr($cadena,$i,1);
		    $aux[$i] *= $multiplicador;
		    $multiplicador++;
		    if($multiplicador > $baseMultiplicador)
		    {
			    $multiplicador=2;
		    }
			$total+=$aux[$i];
	    }
	    $verificador = $total % 11;
	    $verificador = 11 - $verificador;
	    if ($verificador == 10) {
	    	$verificador = 1;
	    }
	    if ($verificador == 11) {
	    	$verificador = 0;
	    }
	    /*if(($total==0)||($total==1)) $verificador=0;
	    else
	    {
		    $verificador=(11-($total%11)==11)?0:11-($total%11);
	    }
	    if($verificador==10)
	    {
		    $verificador=1;
	    }*/
	    return $verificador;
    }


    //parametros clave de acceso
    /*
    1 Fecha de Emisión Numérico             ddmmaaaa       8 Obligatorio <claveAcceso> 
    2 Tipo de Comprobante                   Tabla 3        2 
    3 Número de RUC                         1234567890001  13 
    4 Tipo de Ambiente                      Tabla 4        1 
    5 Serie                                 001001         6 
    6 Número del Comprobante (secuencial)   000000001      9 
    7 Código Numérico                       Numérico       8 
    8 Tipo de Emisión                       Tabla 2        1 
    9 Dígito Verificador (módulo 11 )       Numérico       1*/
function generar_xml($cabecera,$detalle)
{
		$RIMPE =  $this->datos_rimpe();
   	    $entidad=$cabecera['Entidad']; //cambiar por la entidad
	    $empresa=$cabecera['item'];
	    $numero=$this->generaCeros($cabecera['factura'], '9');
	    $ambiente=$cabecera['ambiente'];
	    $codDoc=$cabecera['cod_doc'];
	    $compro = $cabecera['ClaveAcceso'];

        //verificamos si existe una carpeta de la entidad si no existe las creamos
	    $carpeta_entidad = dirname(__DIR__)."/entidades/entidad_".$entidad;
	    $carpeta_autorizados = "";		  
        $carpeta_generados = "";
        $carpeta_firmados = "";
        $carpeta_no_autori = "";
        if(!file_exists(dirname(__DIR__)."/entidades"))
		{
			mkdir(dirname(__DIR__)."/entidades", 0777);
		}

		if(file_exists($carpeta_entidad))
		{
			$carpeta_comprobantes = $carpeta_entidad.'/CE'.$empresa;
			if(file_exists($carpeta_comprobantes))
			{
			  $carpeta_autorizados = $carpeta_comprobantes."/Autorizados";		  
			  $carpeta_generados = $carpeta_comprobantes."/Generados";
			  $carpeta_firmados = $carpeta_comprobantes."/Firmados";
			  $carpeta_no_autori = $carpeta_comprobantes."/No_autorizados";
			  $carpeta_rechazados = $carpeta_comprobantes."/Rechazados";
			  $carpeta_enviados = $carpeta_comprobantes."/Enviados";

				if(!file_exists($carpeta_autorizados))
				{
					mkdir($carpeta_entidad."/CE".$empresa."/Autorizados", 0777);
				}
				if(!file_exists($carpeta_generados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/Generados', 0777);
				}
				if(!file_exists($carpeta_firmados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/Firmados', 0777);
				}
				if(!file_exists($carpeta_no_autori))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/No_autorizados', 0777);
				}
				if(!file_exists($carpeta_rechazados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/Rechazados', 0777);
				}
				if(!file_exists($carpeta_enviados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/Enviados', 0777);
				}
			}else
			{
				mkdir($carpeta_entidad.'/CE'.$empresa, 0777);
				mkdir($carpeta_entidad."/CE".$empresa."/Autorizados", 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/Generados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/Firmados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/No_autorizados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/Rechazados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/Enviados', 0777);
			}
		}else
		{
			   mkdir($carpeta_entidad, 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa, 0777);
			   mkdir($carpeta_entidad."/CE".$empresa."/Autorizados", 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/Generados', 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/Firmados', 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/No_autorizados', 0777);	  
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/Rechazados', 0777);  
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/Enviados', 0777);
		}
		

		if(file_exists($carpeta_autorizados.'/'.$compro.'.xml'))
		{
			$respuesta = 'Documento ya autorizado';
			return $respuesta;
		}
	
		// "Create" the document.
		$xml = new DOMDocument( "1.0", "UTF-8" );
		$xml->formatOutput = true;
		$xml->preserveWhiteSpace = false; 

		// Create some elements.
		switch ($codDoc) {
			case '01':
				$xml_factura = $xml->createElement( "factura" );
				break;
			case '07':
				$xml_factura = $xml->createElement( "comprobanteRetencion" );
				break;
			case '03':
				$xml_factura = $xml->createElement( "liquidacionCompra" );
				break;
			case '04':
				$xml_factura = $xml->createElement( "notaCredito" );
				break;
			case '05':
				$xml_factura = $xml->createElement( "notaDebito" );
				break;
			case '06':
				$xml_factura = $xml->createElement( "guiaRemision" );
				break;
			
			
		}
		
		$xml_factura->setAttribute( "id", "comprobante" );
		$xml_factura->setAttribute( "version", "1.1.0" );
		$xml_infoTributaria = $xml->createElement( "infoTributaria" );
		$xml_ambiente = $xml->createElement( "ambiente",$ambiente );
		$xml_tipoEmision = $xml->createElement( "tipoEmision",'1' );
		$xml_razonSocial = $xml->createElement( "razonSocial",$cabecera['razon_social_principal']);
		$xml_nombreComercial = $xml->createElement( "nombreComercial",$cabecera['nom_comercial_principal'] );
		$xml_ruc = $xml->createElement( "ruc",$cabecera['ruc_principal'] );
		$xml_claveAcceso = $xml->createElement( "claveAcceso",$compro);
		$xml_codDoc = $xml->createElement( "codDoc",$codDoc );
		$xml_estab = $xml->createElement( "estab",$cabecera['esta'] );
		$xml_ptoEmi = $xml->createElement( "ptoEmi",$cabecera['pto_e'] );
		$xml_secuencial = $xml->createElement( "secuencial",$numero );
		$xml_dirMatriz = $xml->createElement( "dirMatriz",$cabecera['direccion_principal'] );
			
			
		$xml_infoTributaria->appendChild( $xml_ambiente );
		$xml_infoTributaria->appendChild( $xml_tipoEmision );
		$xml_infoTributaria->appendChild( $xml_razonSocial );
		$xml_infoTributaria->appendChild( $xml_nombreComercial );
		$xml_infoTributaria->appendChild( $xml_ruc );
		$xml_infoTributaria->appendChild( $xml_claveAcceso );
		$xml_infoTributaria->appendChild( $xml_codDoc );
		$xml_infoTributaria->appendChild( $xml_estab );
		$xml_infoTributaria->appendChild( $xml_ptoEmi );
		$xml_infoTributaria->appendChild( $xml_secuencial );
		$xml_infoTributaria->appendChild( $xml_dirMatriz );
		if(count($RIMPE)>0)
		{
			if($RIMPE['@micro']!='.' && $RIMPE['@micro']!='' && $RIMPE['@micro']=='CONTRIBUYENTE RÉGIMEN RIMPE' )
			{
				$xml_contribuyenteRimpe = $xml->createElement( "contribuyenteRimpe",$RIMPE['@micro']);
				$xml_infoTributaria->appendChild( $xml_contribuyenteRimpe);
			}
			if($RIMPE['@Agente']!='.' && $RIMPE['@Agente']!='')
			{
				$xml_agenteRetencion = $xml->createElement( "agenteRetencion",'1');
				$xml_infoTributaria->appendChild( $xml_agenteRetencion);
			}
		}


		$xml_infoFactura = $xml->createElement( "infoFactura" );
		if($codDoc=='03')
		{
			$xml_infoFactura = $xml->createElement( "infoLiquidacionCompra" );
	    }

		$xml_fechaEmision = $xml->createElement( "fechaEmision",$cabecera['fechaem'] );

		$estable = $cabecera['esta'];
		$punto = $cabecera['pto_e'];
		if(isset($cabecera['Nombre_Establecimiento']) &&  strlen($cabecera['Nombre_Establecimiento'])>0 && $cabecera['Nombre_Establecimiento']!='.')
		{
			$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",$cabecera['Direccion_Establecimiento']);

		}else
		{
			$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",$cabecera['direccion_principal']);
		}
		
		
		$xml_obligadoContabilidad = $xml->createElement( "obligadoContabilidad",$_SESSION['INGRESO']['Obligado_Conta']);

		$xml_tipoIdentificacionComprador = $xml->createElement( "tipoIdentificacionComprador",$cabecera['tipoIden'] );
		$xml_razonSocialComprador = $xml->createElement( "razonSocialComprador",$cabecera['Razon_Social'] );
		$xml_identificacionComprador = $xml->createElement( "identificacionComprador",$cabecera['RUC_CI'] );
		$xml_totalSinImpuestos = $xml->createElement( "totalSinImpuestos",$cabecera['totalSinImpuestos']);
		$xml_totalDescuento = $xml->createElement( "totalDescuento",$cabecera['Descuento']);

		if($codDoc=='03')
		{
			$xml_tipoIdentificacionComprador = $xml->createElement( "tipoIdentificacionProveedor",$cabecera['tipoIden'] );
			$xml_razonSocialComprador = $xml->createElement( "razonSocialProveedor",$cabecera['Razon_Social'] );
			$xml_identificacionComprador = $xml->createElement( "identificacionProveedor",$cabecera['RUC_CI'] );		
			
		}

		$xml_infoFactura->appendChild( $xml_fechaEmision );
		$xml_infoFactura->appendChild( $xml_dirEstablecimiento );
		$xml_infoFactura->appendChild( $xml_obligadoContabilidad );
		$xml_infoFactura->appendChild( $xml_tipoIdentificacionComprador );
		$xml_infoFactura->appendChild( $xml_razonSocialComprador );
		$xml_infoFactura->appendChild( $xml_identificacionComprador );
		$xml_infoFactura->appendChild( $xml_totalSinImpuestos );
		$xml_infoFactura->appendChild( $xml_totalDescuento );

		$xml_totalConImpuestos = $xml->createElement( "totalConImpuestos" );
		//sin iva
		$xml_totalImpuesto = $xml->createElement( "totalImpuesto" );
		$xml_codigo = $xml->createElement( "codigo",'2' );
		$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'0'  );
		$xml_descuentoAdicional = $xml->createElement( "descuentoAdicional",$cabecera['descuentoAdicional']);
		$xml_baseImponible = $xml->createElement( "baseImponible",$cabecera['baseImponibleSinIva']);
		//$xml_tarifa = $xml->createElement( "tarifa",'0.00' );
		$xml_valor = $xml->createElement( "valor",'0.00' );
		
		$xml_totalImpuesto->appendChild( $xml_codigo );
		$xml_totalImpuesto->appendChild( $xml_codigoPorcentaje );
		$xml_totalImpuesto->appendChild( $xml_descuentoAdicional );
		$xml_totalImpuesto->appendChild( $xml_baseImponible );
		//$xml_totalImpuesto->appendChild( $xml_tarifa );
		$xml_totalImpuesto->appendChild( $xml_valor );
		$xml_totalConImpuestos->appendChild( $xml_totalImpuesto );
		if(($cabecera['Con_IVA']) > 0)
		{
			$xml_totalImpuesto = $xml->createElement( "totalImpuesto" );
			$xml_codigo = $xml->createElement( "codigo",'2' );
			$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",$cabecera['codigoPorcentaje'] );
			$xml_descuentoAdicional = $xml->createElement( "descuentoAdicional",$cabecera['descuentoAdicional'] );
			$xml_baseImponible = $xml->createElement( "baseImponible",$cabecera['baseImponibleConIva'] );
			$xml_tarifa = $xml->createElement( "tarifa",($cabecera['Porc_IVA']*100) );
			$xml_valor = $xml->createElement( "valor",$cabecera['IVA'] );
			
			$xml_totalImpuesto->appendChild( $xml_codigo );
			$xml_totalImpuesto->appendChild( $xml_codigoPorcentaje );
			$xml_totalImpuesto->appendChild( $xml_descuentoAdicional );
			$xml_totalImpuesto->appendChild( $xml_baseImponible );
			$xml_totalImpuesto->appendChild( $xml_tarifa );
			$xml_totalImpuesto->appendChild( $xml_valor );
			
			$xml_totalConImpuestos->appendChild( $xml_totalImpuesto );
		}
		$xml_infoFactura->appendChild( $xml_totalConImpuestos );
		if($codDoc=='01')
		{
			$xml_propina = $xml->createElement( "propina",$cabecera['Propina'] );		
			$xml_infoFactura->appendChild( $xml_propina );
		}

		$xml_importeTotal = $xml->createElement( "importeTotal",$cabecera['Total_MN']);
		$xml_moneda = $xml->createElement( "moneda",$cabecera['moneda'] );

		$xml_pagos = $xml->createElement("pagos");
		$xml_pago = $xml->createElement("pago");
		   $xml_formapago = $xml->createElement( "formaPago",$cabecera['formaPago']);
		   $xml_total = $xml->createElement( "total",$cabecera['Total_MN']);
		   $xml_pago->appendChild( $xml_formapago );
		   $xml_pago->appendChild($xml_total);

		   $xml_pagos->appendChild($xml_pago);


		$xml_infoFactura->appendChild( $xml_importeTotal );
		$xml_infoFactura->appendChild( $xml_moneda );
		$xml_infoFactura->appendChild( $xml_pagos );


		$xml_detalles = $xml->createElement( "detalles");
		foreach ($detalle as $key => $value) {
			if($value['Cod_Bar'] !='' or $value['Codigo']!='')
			{
				$xml_detalle = $xml->createElement( "detalle" );
				if($cabecera['SP']==true)
				{
					if(strlen($value['Cod_Bar'])>1)
					{
						$xml_codigoPrincipal = $xml->createElement( "codigoPrincipal",$value['Cod_Bar'] );
					}
					$xml_detalle->appendChild( $xml_codigoPrincipal );
					if(strlen($detalle[$i]['Cod_Aux'])>1)
					{
						$xml_codigoAuxiliar = $xml->createElement( "codigoAuxiliar",$value['Cod_Aux'] );
					}
					else
					{
						$xml_codigoAuxiliar = $xml->createElement( "codigoAuxiliar",$value['Codigo'] );
					}
					$xml_detalle->appendChild( $xml_codigoAuxiliar );

				}else
				{

					$cod_au = str_replace('.','', $value['Codigo']);
					$cod =explode('.', $value['Codigo']);
						$num_partes = count($cod);
						$val_cod = '';
						for ($i=0; $i <$num_partes-1 ; $i++) { 
							$val_cod.= $cod[$i].'.';
							$val_cod = substr($val_cod,0,-1);
						}

					if(strlen($value['Cod_Aux'])>1)
					{
						$xml_codigoPrincipal = $xml->createElement( "codigoPrincipal",$value['Cod_Aux'] );
					}
					else
					{					
						$xml_codigoPrincipal = $xml->createElement( "codigoPrincipal",$value['Codigo']);
					}
					$xml_detalle->appendChild( $xml_codigoPrincipal );
					// if(strlen($value['Cod_Bar'])>1)
					// {
						// $xml_codigoAuxiliar = $xml->createElement( "codigoAuxiliar",$val_cod);
						// $xml_detalle->appendChild( $xml_codigoAuxiliar );
					// }
				}

				$xml_descripcion = $xml->createElement( "descripcion",preg_replace("/[\r\n|\n|\r]+/", " ",$value['Producto']));
				$xml_unidadMedida = $xml->createElement( "unidadMedida",$cabecera['moneda'] );
				$xml_cantidad = $xml->createElement( "cantidad", $value['Cantidad']);
				$xml_precioUnitario = $xml->createElement( "precioUnitario",$value['Precio']);
				$xml_descuento = $xml->createElement( "descuento",$value['descuento'] );
				$xml_precioTotalSinImpuesto = $xml->createElement( "precioTotalSinImpuesto",$value['SubTotal'] );
				
				$xml_detalle->appendChild( $xml_codigoPrincipal );
				
				$xml_detalle->appendChild( $xml_descripcion );
				$xml_detalle->appendChild( $xml_unidadMedida );
				$xml_detalle->appendChild( $xml_cantidad );
				$xml_detalle->appendChild( $xml_precioUnitario );
				$xml_detalle->appendChild( $xml_descuento );
				$xml_detalle->appendChild( $xml_precioTotalSinImpuesto );
				if(strlen($value['Serie_No'])>1)
				{
					$detallesAdicionales = $xml->createElement( "detallesAdicionales" );
					$detAdicional = $xml->createElement( "detAdicional" );
					$detAdicional->setAttribute( "nombre", "Serie_No" );
					$detAdicional->setAttribute( "valor", $value['Serie_No'] );
					$detallesAdicionales->appendChild( $detAdicional );
					$xml_detalle->appendChild( $detallesAdicionales );
				}
				$xml_impuestos = $xml->createElement( "impuestos" );
				$xml_impuesto = $xml->createElement( "impuesto" );
				$xml_codigo = $xml->createElement( "codigo",'2' );

				if($value['Total_IVA'] == 0)
				{
					$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'0' );
					$xml_tarifa = $xml->createElement( "tarifa",'0' );
				}
				else
				{

					//se cambia por el valor de iva en la tabla

					$porceiva = (floatval($cabecera['Porc_IVA'])*100);
				    if($porceiva>0)
				    {
				    	$iva = Porcentajes_IVA($cabecera['Fecha'],$porceiva);
				    	if(count($iva)>0)
				    	{
				    		$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",$iva[0]['Codigo']);
				    	}
				    }



					// if(($value['Porc_IVA']*100) > 12)
					// {
					// 	$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'3' );
					// }
					// else
					// {
					// 	$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'2' );
					// }
					$xml_tarifa = $xml->createElement( "tarifa", ($value['Porc_IVA']*100));
					
				}
				$xml_baseImponible = $xml->createElement( "baseImponible",$value['SubTotal'] );
				$xml_valor = $xml->createElement( "valor",$value['Total_IVA']  );
				$xml_impuesto->appendChild( $xml_codigo );
				$xml_impuesto->appendChild( $xml_codigoPorcentaje );
				$xml_impuesto->appendChild( $xml_tarifa );
				$xml_impuesto->appendChild( $xml_baseImponible );
				$xml_impuesto->appendChild( $xml_valor );
			
				$xml_impuestos->appendChild( $xml_impuesto );
				$xml_detalle->appendChild( $xml_impuestos );
				$xml_detalles->appendChild( $xml_detalle );
			}
		}
		$xml_infoAdicional = $xml->createElement( "infoAdicional");
		//agregar informacion por default
			// $xml_campoAdicional = $xml->createElement( "campoAdicional",'.' );
			// $xml_campoAdicional->setAttribute( "nombre", "adi" );
			// $xml_infoAdicional->appendChild( $xml_campoAdicional );

		// print_r($cabecera);die();
		if($cabecera['Cliente']<>'.' AND $cabecera['Cliente']!=$cabecera['Razon_Social'])
		{
			if(strlen($cabecera['Cliente'])>1)
			{
				$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Cliente']);
				$xml_campoAdicional->setAttribute( "nombre", "Beneficiario" );
				$xml_infoAdicional->appendChild($xml_campoAdicional );
			}
			if(strlen($cabecera['Grupo'])>1)
			{
				$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Grupo'] );
				$xml_campoAdicional->setAttribute( "nombre", "Ubicacion");
				$xml_infoAdicional->appendChild($xml_campoAdicional );
			}
		}
		if(strlen($cabecera['DireccionC'])>1)
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['DireccionC'] );
			$xml_campoAdicional->setAttribute( "nombre", "Direccion" );
			$xml_infoAdicional->appendChild($xml_campoAdicional );
		}
		if(strlen($cabecera['TelefonoC'])>1)
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['TelefonoC'] );
			$xml_campoAdicional->setAttribute( "nombre", "Telefono" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}
		if(strlen($cabecera['EmailC'])>1)
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['EmailC'] );
			$xml_campoAdicional->setAttribute( "nombre", "Email" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}
		if(strlen($cabecera['EmailR'])>1)
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['EmailR'] );
			$xml_campoAdicional->setAttribute( "nombre", "Email2" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}
		if(strlen($cabecera['Contacto'])>1)
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Contacto'] );
			$xml_campoAdicional->setAttribute( "nombre", "Referencia" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}
		if(strlen($cabecera['Orden_Compra'])>1)
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Orden_Compra'] );
			$xml_campoAdicional->setAttribute( "nombre", "ordenCompra" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}
		if(strlen($cabecera['Observacion'])>1)
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Observacion'] );
			$xml_campoAdicional->setAttribute( "nombre", "Observacion" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}
		if(strlen($cabecera['Nota'])>1)
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Nota'] );
			$xml_campoAdicional->setAttribute( "nombre", "Nota" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}
		if(count($RIMPE)>0)
		{
			if($RIMPE['@micro']!='CONTRIBUYENTE RÉGIMEN RIMPE')
			{
				$xml_campoAdicional = $xml->createElement( "campoAdicional",$RIMPE['@micro']);
				$xml_campoAdicional->setAttribute( "nombre", "tipoContribuyente" );
				$xml_infoAdicional->appendChild( $xml_campoAdicional );
			}			
		}


		$estable = $cabecera['esta'];
		$punto = $cabecera['pto_e'];

	///----------------------  infomrmacion adicional cuando punto de venta es diferente de 001 ----------------------
	// print_r($sucursal);die();
	if($estable=='001' && $punto!='001')
	{
		$xml_campoAdicional = $xml->createElement( "campoAdicional", $cabecera['esta'].$cabecera['pto_e']);
		$xml_campoAdicional->setAttribute( "nombre", "seriePuntoEmision" );
		$xml_infoAdicional->appendChild( $xml_campoAdicional );

		if(isset($cabecera['Nombre_Establecimiento']) && $cabecera['Nombre_Establecimiento']!='.' && $cabecera['Nombre_Establecimiento']!='')
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Nombre_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "socioRazonSocial" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}

		if(isset($cabecera['Ruc_Establecimiento']) && $cabecera['Ruc_Establecimiento']!='' && $cabecera['Ruc_Establecimiento']!='.')
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Ruc_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "socioRUC" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}

		if(isset($cabecera['Direccion_Establecimiento']) && $cabecera['Direccion_Establecimiento']!='.' && $cabecera['Direccion_Establecimiento']!='')
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Direccion_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "socioDireccion" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}

		if(isset($cabecera['Telefono_Establecimiento']) && $cabecera['Telefono_Establecimiento']!='.' && $cabecera['Telefono_Establecimiento']!='')
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Telefono_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "socioTelefono" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}

		if(isset($cabecera['Email_Establecimiento']) && $cabecera['Email_Establecimiento']!='.' && $cabecera['Email_Establecimiento']!='')
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Email_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "socioEmail" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}

		if(isset($cabecera['Placa_Vehiculo']))
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Placa_Vehiculo'] );
			$xml_campoAdicional->setAttribute( "nombre", "PlacaVehiculo" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}

		if(isset($cabecera['Cta_Establecimiento']))
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Cta_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "CtaEstablecimiento" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}
	}

	///----------------------  fin infomrmacion adicional cuando punto de venta es diferente de 001 ----------------------


	///----------------------  infomrmacion adicional cuan establecimiento es diferente de 001 ----------------------

    if($estable!='001')
	{

		$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['esta'].$cabecera['pto_e']);
		$xml_campoAdicional->setAttribute( "nombre", "serieEstablecimiento" );
		$xml_infoAdicional->appendChild( $xml_campoAdicional );

		if($cabecera['Nombre_Establecimiento']!='.' && $cabecera['Nombre_Establecimiento']!='')
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Nombre_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "NombreEstablecimiento" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}

		if($cabecera['Ruc_Establecimiento']!='' && $cabecera['Ruc_Establecimiento']!='.')
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Ruc_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "RUCEstablecimiento" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}
       /*
       este dato ya viene al inicio del xml en direstablecimiento
		if($cabecera['Direccion_Establecimiento']!='.' && $cabecera['Direccion_Establecimiento']!='')
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Direccion_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "direccionEstablecimiento" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}
       */
		if($cabecera['Telefono_Establecimiento']!='.' && $cabecera['Telefono_Establecimiento']!='')
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Telefono_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "TelefonoEstablecimiento" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}

		if($cabecera['Email_Establecimiento']!='.' && $cabecera['Email_Establecimiento']!='')
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Email_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "EmailEstablecimiento" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}

		if(isset($cabecera['Placa_Vehiculo']))
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Placa_Vehiculo'] );
			$xml_campoAdicional->setAttribute( "nombre", "PlacaVehiculo" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}

		if(isset($cabecera['Cta_Establecimiento']))
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Cta_Establecimiento'] );
			$xml_campoAdicional->setAttribute( "nombre", "CtaEstablecimiento" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}		
	}


		$xml_factura->appendChild( $xml_infoTributaria );
		$xml_factura->appendChild( $xml_infoFactura );
		$xml_factura->appendChild( $xml_detalles );
		$xml_factura->appendChild( $xml_infoAdicional );


		$xml->appendChild($xml_factura);

		$ruta_G = dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Generados';
		if($archivo = fopen($ruta_G.'/'.$compro.'.xml',"w+b"))
		  {
		  	fwrite($archivo,$xml->saveXML());
		  	 
		  	 return 1;
		  }else
		  {
		  	// print_r('sss')
		  	return -1;
		  }	
}


function Autorizar_retencion($parametros)
{
	$datos = $this->retencion_compras($parametros['Numero'],$parametros['TP'],$parametros['Serie_R'],$parametros['Retencion']);
    // print_r($datos);die();
    if(count($datos)>0)
    {
      $fecha = $datos[0]['FechaEmision']->format('Y-m-d');
      $TFA[0]["Serie_R"] = $datos[0]["Serie_Retencion"];
      $TFA[0]["Retencion"] = $datos[0]["SecRetencion"];
      $TFA[0]["Autorizacion_R"] = $datos[0]["AutRetencion"];
      $TFA[0]["Autorizacion"] = generaCeros($datos[0]["Autorizacion"],10);
      $TFA[0]["Fecha"] = $datos[0]["FechaEmision"];
      $TFA[0]["Vencimiento"] = $datos[0]["FechaRegistro"];
      $TFA[0]["Serie"] = $datos[0]["Establecimiento"].$datos[0]["PuntoEmision"];
      $TFA[0]["Factura"] = $datos[0]["Secuencial"];
      $TFA[0]["Hora"] = date('H:m:s');
      $TFA[0]["Cliente"] = $datos[0]["Cliente"];
      $TFA[0]["CI_RUC"] = $datos[0]["CI_RUC"];
      $TFA[0]["TD"] = $datos[0]["TD"];
      $TFA[0]["DireccionC"] = $datos[0]["Direccion"];
      $TFA[0]["TelefonoC"] = $datos[0]["Telefono"];
      $TFA[0]["EmailC"] = $datos[0]["Email"];
      $CodSustento = $datos[0]["CodSustento"];

      $TFA[0]["Ruc"] = $datos[0]["CI_RUC"];
      $TFA[0]["TP"] = $parametros['TP'];
      $TFA[0]["Numero"] = $parametros['Numero'];
      $TFA[0]["TipoComprobante"] = '0'.$datos[0]["TipoComprobante"];

      $aut =  $this->Clave_acceso($TFA[0]['Fecha']->format('Y-m-d'),'07',$TFA[0]["Serie_R"],$TFA[0]["Retencion"]);
      $TFA[0]["ClaveAcceso"]  = $aut;
      $TFA[0]['codigoPorcentaje'] = $datos[0]['PorcentajeIva'];

	    if($TFA[0]['codigoPorcentaje']!='.' && $TFA[0]['codigoPorcentaje']!='')
	    {
	    	$iva = Porcentajes_IVA($fecha,false,$TFA[0]['codigoPorcentaje']);

	    	// print_r($iva);die();
	    	if(count($iva)>0)
	    	{
	   			$TFA[0]['PorcentajeIva'] =$iva[0]['Porc'];
      
	    	}
	    }

      	// print_r($datos);die();
	    // print_r($TFA);die();



      $xml = $this->generar_xml_retencion($TFA,$datos);
      // die();

       // $linkSriAutorizacion = $_SESSION['INGRESO']['Web_SRI_Autorizado'];
 	   // $linkSriRecepcion = $_SESSION['INGRESO']['Web_SRI_Recepcion'];
	           if($xml==1)
	           {
	           	 $firma = $this->firmar_documento(
	           	 	$aut,
	           	 	generaCeros($_SESSION['INGRESO']['IDEntidad'],3),
	           	 	$_SESSION['INGRESO']['item'],
	           	 	$_SESSION['INGRESO']['Clave_Certificado'],
	           	 	$_SESSION['INGRESO']['Ruta_Certificado']);
	           	 // print($firma);die();
	           	 if($firma==1)
	           	 {
	           	 	$validar_autorizado = $this->comprobar_xml_sri(
	           	 		$aut,
	           	 		$this->linkSriAutorizacion);
	           	 	if($validar_autorizado == -1)
			   		 {
			   		 	$enviar_sri = $this->enviar_xml_sri(
			   		 		$aut,
			   		 		$this->linkSriRecepcion);
			   		 	if($enviar_sri==1)
			   		 	{
			   		 		//una vez enviado comprobamos el estado de la factura
			   		 		$resp =  $this->comprobar_xml_sri($aut,$this->linkSriAutorizacion);
			   		 		if($resp==1)
			   		 		{
			   		 			$resp = $this->actualizar_datos_CER($aut,$parametros['TP'],$TFA[0]["Serie_R"],$TFA[0]["Retencion"],generaCeros($_SESSION['INGRESO']['IDEntidad'],3),$TFA[0]["Autorizacion_R"],$TFA[0]['Fecha']->format('Y-m-d'));
			   		 			return  $resp;
			   		 		}else
			   		 		{
			   		 			return $resp;
			   		 		}
			   		 		// print_r($resp);die();
			   		 	}else
			   		 	{
			   		 		return $enviar_sri;
			   		 	}

			   		 }else 
			   		 {
			   		 	if($validar_autorizado==1)
			   		 	{
			   		 		$resp = $this->actualizar_datos_CER($aut,$parametros['TP'],$TFA[0]["Serie_R"],$TFA[0]["Retencion"],generaCeros($_SESSION['INGRESO']['IDEntidad'],3),$TFA[0]["Autorizacion_R"],$TFA[0]['Fecha']->format('Y-m-d'));
			   		 	}
			   		 	// $resp = $this->actualizar_datos_CE($cabecera['ClaveAcceso'],$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['Entidad'],$cabecera['Autorizacion']);
			   		 	// RETORNA SI YA ESTA AUTORIZADO O SI FALL LA REVISIO EN EL SRI
			   			return $validar_autorizado;
			   		 }
	           	 }else
	           	 {
	           	 	//RETORNA SI FALLA AL FIRMAR EL XML
	           	 	return $firma;
	           	 }
	           }else
	           {
	           	//RETORNA SI FALLA EL GENERAR EL XML
	           	return $xml;
	           }
	       }



}



function generar_xml_retencion($cabecera,$detalle)
{

	// print_r($cabecera);
	// print_r($_SESSION['INGRESO']);
	// print_r($detalle);
	// die();
	$entidad = $_SESSION['INGRESO']['IDEntidad'];
	$empresa = $_SESSION['INGRESO']['item'];
	$this->generar_carpetas($entidad,$empresa);
	$ambiente =$_SESSION['INGRESO']['Ambiente'];
	$RIMPE =  $this->datos_rimpe();
	/*
	$sucursal = $this->catalogo_lineas('RE',$cabecera[0]['Serie_R']);
	 if(count($sucursal)>0)
	 {
	 	$cabecera[0]['Nombre_Establecimiento'] = $sucursal[0]['Nombre_Establecimiento'];
	 	$cabecera[0]['Direccion_Establecimiento'] = $sucursal[0]['Direccion_Establecimiento'];
	 	$cabecera[0]['Telefono_Establecimiento'] = $sucursal[0]['Telefono_Estab'];
	 	$cabecera[0]['Ruc_Establecimiento'] = $sucursal[0]['RUC_Establecimiento'];
	 	$cabecera[0]['Email_Establecimiento'] = $sucursal[0]['Email_Establecimiento'];
	 	$cabecera[0]['Placa_Vehiculo'] ='.';
	 	$cabecera[0]['Cta_Establecimiento'] = '.';
	 	if(isset($sucursal[0]['Placa_Vehiculo']))
	 	{
	 		$cabecera[0]['Placa_Vehiculo'] = $sucursal[0]['Placa_Vehiculo'];
	 	}
	 	if (isset($sucursal[0]['Cta_Establecimiento'])) {
	 		$cabecera[0]['Cta_Establecimiento'] = $sucursal[0]['Cta_Establecimiento'];
	 	}		 	
	 }*/

	$carpeta_autorizados = dirname(__DIR__)."/entidades/entidad_".generaCeros($entidad,3).'/CE'.generaCeros($empresa,3)."/Autorizados";		  
	if(file_exists($carpeta_autorizados.'/'.$cabecera[0]['ClaveAcceso'].'.xml'))
	{
		$respuesta = array('1'=>'Autorizado');
		return $respuesta;
	}

	    $xml = new DOMDocument( "1.0", "UTF-8");
        $xml->formatOutput = true;
        $xml->preserveWhiteSpace = false;
	    $xml->xmlStandalone = true;

	    $xml_inicio = $xml->createElement( "comprobanteRetencion" );
        $xml_inicio->setAttribute( "id", "comprobante" );
        $xml_inicio->setAttribute( "version", "2.0.0" );
        //informacion de cabecera
	    $xml_infotributaria = $xml->createElement("infoTributaria");
	    $xml_ambiente = $xml->createElement("ambiente",$ambiente);
	    $xml_tipoEmision = $xml->createElement("tipoEmision","1");
	    $xml_razonSocial = $xml->createElement("razonSocial",$_SESSION['INGRESO']['Razon_Social']);
	    $xml_nombreComercial = $xml->createElement("nombreComercial",$_SESSION['INGRESO']['Nombre_Comercial']);
	    $xml_ruc = $xml->createElement("ruc",$_SESSION['INGRESO']['RUC']);
	    $xml_claveAcceso = $xml->createElement("claveAcceso",$cabecera[0]['ClaveAcceso']);
	    $xml_codDoc = $xml->createElement("codDoc",'07');
	    $xml_estab = $xml->createElement("estab",substr($cabecera[0]['Serie_R'], 0,3));
	    $xml_ptoEmi = $xml->createElement("ptoEmi",substr($cabecera[0]['Serie_R'], 3,3));
	    $xml_secuencial = $xml->createElement("secuencial",$this->generaCeros($cabecera[0]['Retencion'],9));
	    $xml_dirMatriz = $xml->createElement("dirMatriz",$_SESSION['INGRESO']['Direccion']);




        $xml_infotributaria->appendChild($xml_ambiente);
        $xml_infotributaria->appendChild($xml_tipoEmision);
        $xml_infotributaria->appendChild($xml_razonSocial);
        $xml_infotributaria->appendChild($xml_nombreComercial);
        $xml_infotributaria->appendChild($xml_ruc);
        $xml_infotributaria->appendChild($xml_claveAcceso);
        $xml_infotributaria->appendChild($xml_codDoc);
        $xml_infotributaria->appendChild($xml_estab);
        $xml_infotributaria->appendChild($xml_ptoEmi);
        $xml_infotributaria->appendChild($xml_secuencial);
        $xml_infotributaria->appendChild($xml_dirMatriz);

        // print_r($RIMPE);die();

        if(count($RIMPE)>0)
		{
			if($RIMPE['@Agente']!='.' && $RIMPE['@Agente']!='')
			{
				$xml_agenteRetencion = $xml->createElement( "agenteRetencion",'1');
				$xml_infotributaria->appendChild( $xml_agenteRetencion);
			}
			if($RIMPE['@micro']!='.' && $RIMPE['@micro']!='' && $RIMPE['@micro']=='CONTRIBUYENTE RÉGIMEN RIMPE' )
			{
				$xml_contribuyenteRimpe = $xml->createElement( "contribuyenteRimpe",$RIMPE['@micro']);
				$xml_infotributaria->appendChild( $xml_contribuyenteRimpe);
			}
			
		}

        // $xml->appendChild($xml_infotributaria);

        $xml_inicio->appendChild($xml_infotributaria);
        //fin de cabecera


	    $xml_infoCompRetencion = $xml->createElement( "infoCompRetencion");
	    $xml_fechaEmision = $xml->createElement( "fechaEmision",$cabecera[0]['Fecha']->format('d/m/Y'));
	    if(isset($cabecera[0]['Nombre_Establecimiento']) &&  strlen($cabecera[0]['Nombre_Establecimiento'])>0 && $cabecera[0]['Nombre_Establecimiento']!='.')
		{
			$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",$cabecera[0]['Direccion_Establecimiento']);

		}else
		{
			$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",strtoupper($_SESSION['INGRESO']['Direccion']));
		}

	    // $xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",strtoupper($_SESSION['INGRESO']['Direccion']));
	    
	    $xml_obligadoContabilidad = $xml->createElement( "obligadoContabilidad",$_SESSION['INGRESO']['Obligado_Conta']);
	    switch ($cabecera[0]['TD']) {
	    	case 'R':
	    		if($cabecera[0]['CI_RUC']=="9999999999999"){$cabecera[0]['TD'] = '07';}else{$cabecera[0]['TD']='04';}
	    		break;
	    	case 'C':
	    		$cabecera[0]['TD'] = '05';
	    		break;
	    	case 'P':
	    		$cabecera[0]['TD'] = '06';
	    		break;
	    }
	    $xml_tipoIdentificacionSujetoRetenido = $xml->createElement( "tipoIdentificacionSujetoRetenido",$cabecera[0]['TD']);

        if($detalle[0]["PagoLocExt"] == "01"){        	
            $xml_parterel  = $xml->createElement("parteRel",'NO');
          }else{                    
            $xml_tipoSujetoRetenido = $xml->createElement('tipoSujetoRetenido',$detalle[0]["PagoLocExt"]);
	    	$xml_parterel  = $xml->createElement("parteRel",'SI');
          }

	    $xml_razonSocialSujetoRetenido = $xml->createElement( "razonSocialSujetoRetenido",$cabecera[0]['Cliente']);
	    $xml_identificacionSujetoRetenido = $xml->createElement( "identificacionSujetoRetenido",$cabecera[0]['CI_RUC']);
	    $xml_periodoFiscal = $xml->createElement( "periodoFiscal",$cabecera[0]['Fecha']->format('m/Y'));

    
	    $xml_infoCompRetencion->appendChild($xml_fechaEmision);
	    $xml_infoCompRetencion->appendChild($xml_dirEstablecimiento);
	    // ojo con esto de contribuyente	
	    // if(strlen($ContEspec)>1){
	    // 	$xml_contribuyenteEspecial = $xml->createElement( "contribuyenteEspecial",$ContEspec);
	    //     $xml_infoCompRetencion->appendChild($xml_contribuyenteEspecial);
	    // }
	    $xml_infoCompRetencion->appendChild($xml_obligadoContabilidad);
	    $xml_infoCompRetencion->appendChild($xml_tipoIdentificacionSujetoRetenido);	
	    if($detalle[0]['PagoLocExt']!='01')
	    { 
	    	$xml_infoCompRetencion->appendChild($xml_tipoSujetoRetenido);   
	    }
	    $xml_infoCompRetencion->appendChild($xml_parterel);
	    $xml_infoCompRetencion->appendChild($xml_razonSocialSujetoRetenido);
	    $xml_infoCompRetencion->appendChild($xml_identificacionSujetoRetenido);
	    $xml_infoCompRetencion->appendChild($xml_periodoFiscal);

        $xml_inicio->appendChild($xml_infoCompRetencion);

        $Total_Servicio = 0;
        $Total_Propinas = 0;
        $Total_Comision = 0;
        $Total_Sin_No_IVA = 0;
        $Total_Sin_IVA = number_format($detalle[0]['BaseImponible'],2,'.','');
        $Total_Con_IVA = number_format($detalle[0]['BaseImpGrav'],2,'.','');
        $Total_IVA = number_format($detalle[0]['MontoIva'],2,'.','');
        $Total_SubTotal = number_format($Total_Sin_IVA + $Total_Con_IVA,2,'.','');
        $Total_Factura = number_format($Total_SubTotal + $Total_IVA,2,'.','');



        $xml_docsSustento = $xml->createElement("docsSustento");
        $xml_docSustento = $xml->createElement("docSustento");

        $xml_codsustento = $xml->createElement("codSustento",generaCeros($detalle[0]['CodSustento'],2));
        $xml_coddocsustento = $xml->createElement("codDocSustento",generaCeros($detalle[0]['TipoComprobante'],2));
        $xml_numdocsustento = $xml->createElement("numDocSustento",$cabecera[0]['Serie'].generaCeros($cabecera[0]['Factura'],9));
        $xml_fechaemisiondocsustento = $xml->createElement("fechaEmisionDocSustento",$cabecera[0]['Fecha']->format('d/m/Y'));
        $xml_fecharegistrocontable = $xml->createElement("fechaRegistroContable",$cabecera[0]['Vencimiento']->format('d/m/Y'));
        $xml_numautodocsustento = $xml->createElement("numAutDocSustento",$cabecera[0]['Autorizacion']);
        $xml_pagolocext = $xml->createElement("pagoLocExt",$detalle[0]['PagoLocExt']);
        $xml_totalsinimpuesto = $xml->createElement("totalSinImpuestos",number_format($Total_SubTotal,2,'.',''));
        $xml_importetotal = $xml->createElement("importeTotal",number_format($Total_Factura,2,'.',''));


         $xml_docSustento->appendChild($xml_codsustento);
         $xml_docSustento->appendChild($xml_coddocsustento);
         $xml_docSustento->appendChild($xml_numdocsustento);
         $xml_docSustento->appendChild($xml_fechaemisiondocsustento);
         $xml_docSustento->appendChild($xml_fecharegistrocontable);
         $xml_docSustento->appendChild($xml_numautodocsustento);
         $xml_docSustento->appendChild($xml_pagolocext);
         $xml_docSustento->appendChild($xml_totalsinimpuesto);
         $xml_docSustento->appendChild($xml_importetotal);




        $xml_impuestodocssustento =$xml->createElement("impuestosDocSustento");

        
        $xml_impuestodocsustento =$xml->createElement("impuestoDocSustento");
        $xml_codimpuestodocsustento = $xml->createElement("codImpuestoDocSustento",'2');
        $xml_codigoprocentaje = $xml->createElement("codigoPorcentaje",$cabecera[0]['codigoPorcentaje']);
        $xml_baseimponible1 = $xml->createElement("baseImponible", $Total_Con_IVA);
        $xml_tarifa = $xml->createElement("tarifa",$cabecera[0]['PorcentajeIva']);
        $xml_valorimpuesto = $xml->createElement("valorImpuesto",$Total_IVA);

        $xml_impuestodocsustento->appendChild($xml_codimpuestodocsustento);
        $xml_impuestodocsustento->appendChild($xml_codigoprocentaje);
        $xml_impuestodocsustento->appendChild($xml_baseimponible1);
        $xml_impuestodocsustento->appendChild($xml_tarifa);
        $xml_impuestodocsustento->appendChild($xml_valorimpuesto);
        $xml_impuestodocssustento->appendChild($xml_impuestodocsustento);

        $xml_impuestodocssustento->appendChild($xml_impuestodocsustento);        


        // print_r($Total_Sin_IVA);die();
        $xml_impuestodocsustento2 =$xml->createElement("impuestoDocSustento");
        $xml_codimpuestodocsustento = $xml->createElement("codImpuestoDocSustento",'2');
        $xml_codigoprocentaje = $xml->createElement("codigoPorcentaje",'0');
        $xml_baseimponible2 = $xml->createElement("baseImponible",$Total_Sin_IVA);
        $xml_tarifa = $xml->createElement("tarifa",'0');
        $xml_valorimpuesto = $xml->createElement("valorImpuesto",'0.00');

        $xml_impuestodocsustento2->appendChild($xml_codimpuestodocsustento);
        $xml_impuestodocsustento2->appendChild($xml_codigoprocentaje);
        $xml_impuestodocsustento2->appendChild($xml_baseimponible2);
        $xml_impuestodocsustento2->appendChild($xml_tarifa);
        $xml_impuestodocsustento2->appendChild($xml_valorimpuesto);

        $xml_impuestodocssustento->appendChild($xml_impuestodocsustento2);


        $xml_retenciones =$xml->createElement("retenciones");




         // 'RETENCIONES AIR
            $sql = "SELECT * 
           FROM Trans_Air
           WHERE Item = '".$_SESSION['INGRESO']['item']."'
           AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
           AND Numero = ".$cabecera[0]['Numero']."
           AND TP = '".$cabecera[0]['TP']."'
           AND Tipo_Trans = 'C'
           AND EstabRetencion = '".substr($cabecera[0]['Serie_R'] ,0, 3)."'
           AND PtoEmiRetencion = '".substr($cabecera[0]['Serie_R'], 3, 3)."'
           AND SecRetencion = '".$cabecera[0]['Retencion']."'
           AND AutRetencion = '".$cabecera[0]['Autorizacion_R']."'
           ORDER BY ID ";
           // print_r($sql);die();
           $result = $this->db->datos($sql);
          foreach ($result as $key => $value) {
          	$xml_retencion =$xml->createElement("retencion");
	        $xml_codigo = $xml->createElement("codigo",'1');
	        $xml_codigoretencion = $xml->createElement("codigoRetencion",$value['CodRet']);
	        $xml_baseimponible3= $xml->createElement("baseImponible",number_format($value['BaseImp'],2,'.',''));
	        $xml_porcentajeretencion = $xml->createElement("porcentajeRetener",number_format(($value["Porcentaje"] * 100),2,'.',''));
	        $xml_valorretenido = $xml->createElement("valorRetenido",number_format($value['ValRet'],2,'.',''));

	        $xml_retencion->appendChild($xml_codigo);
	        $xml_retencion->appendChild($xml_codigoretencion);
	        $xml_retencion->appendChild($xml_baseimponible3);
	        $xml_retencion->appendChild($xml_porcentajeretencion);
	        $xml_retencion->appendChild($xml_valorretenido);

	        $xml_retenciones->appendChild($xml_retencion);
	    }



	    if($detalle[0]['Porc_Bienes']>0)
	    {
	    	$xml_retencion =$xml->createElement("retencion");
	    	switch ($detalle[0]['Porc_Bienes']) {
	    		case '10':  $CodigoA = '9';
	    			break;	    		
	    		case '30': $CodigoA = '1';
	    			break;	    		
	    		case '70': $CodigoA = '2';
	    			break;
	    		case '100': $CodigoA = '3';
	    			break;
	    		default:  $CodigoA = '2';
	    		break;
	    	}
	    	
	    	$Total = number_format($detalle[0]["MontoIvaBienes"], 2,'.','');
            $Retencion = intval($detalle[0]["Porc_Bienes"]);
            $Valor = number_format(($Total * ($Retencion / 100)), 2,'.','');


            $xml_codigo = $xml->createElement("codigo",'2');
	        $xml_codigoretencion = $xml->createElement("codigoRetencion",$CodigoA);
	        $xml_baseimponible4 = $xml->createElement("baseImponible",$Total);
	        $xml_porcentajeretencion = $xml->createElement("porcentajeRetener",$Retencion);
	        $xml_valorretenido = $xml->createElement("valorRetenido",$Valor);

	        $xml_retencion->appendChild($xml_codigo);
	        $xml_retencion->appendChild($xml_codigoretencion);
	        $xml_retencion->appendChild($xml_baseimponible4);
	        $xml_retencion->appendChild($xml_porcentajeretencion);
	        $xml_retencion->appendChild($xml_valorretenido);

	        $xml_retenciones->appendChild($xml_retencion);

	    }

	     if($detalle[0]['Porc_Servicios']>0)
	    {	    	
	    	$xml_retencion =$xml->createElement("retencion");
	    	switch ($detalle[0]['Porc_Servicios']) {	
	    		case '20': $CodigoA = '10';
	    			break;
	    		case '30':$CodigoA = '1';
	    			break;
	    		case '70':$CodigoA = '2';
	    			break;
	    		case '100':$CodigoA = '3';
	    			break;
	    		default:  $CodigoA = '2';
	    		break;
	    	}
	    	$Total =  number_format($detalle[0]["MontoIvaServicios"], 2,'.','');
            $Retencion = intval($detalle[0]["Porc_Servicios"]);
            $Valor = number_format(($Total * ($Retencion / 100)), 2,'.','');

	    	$xml_codigo = $xml->createElement("codigo",'2');
	        $xml_codigoretencion = $xml->createElement("codigoRetencion",$CodigoA);
	        $xml_baseimponible5 = $xml->createElement("baseImponible",$Total);
	        $xml_porcentajeretencion = $xml->createElement("porcentajeRetener",$Retencion);
	        $xml_valorretenido = $xml->createElement("valorRetenido",$Valor);

	        $xml_retencion->appendChild($xml_codigo);
	        $xml_retencion->appendChild($xml_codigoretencion);
	        $xml_retencion->appendChild($xml_baseimponible5);
	        $xml_retencion->appendChild($xml_porcentajeretencion);
	        $xml_retencion->appendChild($xml_valorretenido);

	        $xml_retenciones->appendChild($xml_retencion);

	    }

        $xml_pagos =$xml->createElement("pagos");
        $xml_pago =$xml->createElement("pago");

        $xml_formapago = $xml->createElement("formaPago",$detalle[0]['FormaPago']);
        $xml_total = $xml->createElement("total",$Total_Factura);

        $xml_pago->appendChild($xml_formapago);
        $xml_pago->appendChild($xml_total);

        $xml_pagos->appendChild($xml_pago);


		$xml_docsSustento->appendChild($xml_docSustento);
		$xml_docSustento->appendChild($xml_impuestodocssustento);
		$xml_docSustento->appendChild($xml_retenciones);
		$xml_docSustento->appendChild($xml_pagos);

		$xml_inicio->appendChild($xml_docsSustento);




        //fin de xml retencion
        $xml_infoAdicional = $xml->createElement("infoAdicional");

       
        if (strlen($cabecera[0]['DireccionC']) > 1){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera[0]['DireccionC']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Direccion");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);

        	}
         if (strlen($cabecera[0]['TelefonoC']) > 1){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera[0]['TelefonoC']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Telefono");
        	$xml_infoAdicional->appendChild($xml_campoAdicional);
        	}
         if( strlen($cabecera[0]['EmailC']) > 1){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera[0]['EmailC']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Email");
        	$xml_infoAdicional->appendChild($xml_campoAdicional);
        	}

        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera[0]['TP'].'-'.$this->generaCeros($cabecera[0]['Numero'],9));
        	 $xml_campoAdicional->setAttribute( "nombre", "Comprobante No");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);
         //     $AgenteRetencion ='ssss'; 
         // if ($AgenteRetencion<>'.'){ 
        	//  $xml_campoAdicional = $xml->createElement("campoAdicional",$AgenteRetencion);
        	//  $xml_campoAdicional->setAttribute( "nombre", "Agente de Retencion");
        	//  $xml_infoAdicional->appendChild($xml_campoAdicional);
        	// }
        	$MicroEmpresa = 's';
         if ($MicroEmpresa<>'.'){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",' ');
        	 $xml_campoAdicional->setAttribute( "nombre", "Contribuyente Regimen Microempresas");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);
        	}

        	$xml_inicio->appendChild($xml_infoAdicional);
        	$xml->appendChild($xml_inicio);

		     $ruta_G = dirname(__DIR__).'/entidades/entidad_'.generaCeros($entidad,3)."/CE".generaCeros($empresa,3).'/Generados';
		     // print_r($ruta_G);die();
			if($archivo = fopen($ruta_G.'/'.$cabecera[0]['ClaveAcceso'].'.xml',"w+b"))
			  {
			  	fwrite($archivo,$xml->saveXML());
			  	// die();
			  	return 1;
			  }else
			  {
			  	return -1;
			  }
}



  function firmar_documento($nom_doc,$entidad,$empresa,$pass,$p12)
    {	

 	    $firmador = dirname(__DIR__).'/SRI/firmar/firmador.jar';
 	    $url_generados=dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Generados/';
 	    $url_firmados =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Firmados/';
 	    $url_rechazado =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Rechazados/';
 	    $certificado_1 = dirname(__DIR__).'/certificados/';
 	    if(file_exists($certificado_1.$p12))
	       {
	       	
	       	if(file_exists($url_generados.$nom_doc.".xml"))
	       	{
	       		// print_r("java -jar ".$firmador." ".$nom_doc.".xml ".$url_generados." ".$url_firmados." ".$certificado_1." ".$p12." ".$pass);die();

	        	exec("java -jar ".$firmador." ".$nom_doc.".xml ".$url_generados." ".$url_firmados." ".$certificado_1." ".$p12." ".$pass, $f);

	        	// print_r($f);die();
	        	if(count($f)<6 && !empty($f))
		 		{
		 			return 1;		 		
		 		}else
		 		{		 			
		 			$respuesta = 'Error al generar XML o al firmar';
		 			return $respuesta;          
		        }
		    }else
		    {
		    	$respuesta = 'XML generado no encontrado';
	 			return $respuesta;
		    }
	 	   }else
	 	   {
	 	   		$respuesta = 'No se han encontrado Certificados';
	 			return $respuesta;
	 	   }

 		// $quijoteCliente =  dirname(__DIR__).'/SRI/firmar/QuijoteLuiClient-1.2.1.jar';
 	 //    $url_No_autorizados =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/No_autorizados/';
 	 //    $url_autorizado =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Autorizados/';

 	 //    $linkSriAutorizacion = $_SESSION['INGRESO']['Web_SRI_Autorizado'];
 	 //    $linkSriRecepcion = $_SESSION['INGRESO']['Web_SRI_Recepcion'];
 	   
 		
    }

    //comprueba si el xml ya se envio al sri
    // 1 para autorizados
    //-1 para no autorizados
    // 2 para devueltas
    function comprobar_xml_sri($clave_acceso,$link_autorizacion)
    {
    	$entidad =  generaCeros($_SESSION['INGRESO']['IDEntidad'],3);
    	$empresa = $_SESSION['INGRESO']['item'];
    	$comprobar_sri = dirname(__DIR__).'/SRI/firmar/sri_comprobar.jar';
    	$url_autorizado=dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Autorizados/';
 	    $url_No_autorizados =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/No_autorizados/';
 	   
    	// print_r("java -jar ".$comprobar_sri." ".$clave_acceso." ".$url_autorizado." ".$url_No_autorizados." ".$link_autorizacion);die();
   		 exec("java -jar ".$comprobar_sri." ".$clave_acceso." ".$url_autorizado." ".$url_No_autorizados." ".$link_autorizacion,$f);   	
   		 // print_r($f);
   		 // die();
   		 if(empty($f))
   		 {
   		 	return;
   		 }


   		 $resp = explode('-',$f[0]);

   		 // print_r($resp);
   		 if(count($resp)>1)
   		 {
   		 	$resp[1] = trim($resp[1]);
   		 	if(!isset($resp[1]) && $resp[0]=='Error al validar el comprobante estado NO AUTORIZADO')
	   		{
	   			return -1;
	   		}
   		 	// print_r($resp[1]);
   		 	//cuando null NO PROCESADO es liquidacion de compras
	   		 if(isset($resp[1]) && $resp[1]=='FACTURA NO PROCESADO' || isset($resp[1]) && $resp[1]=='LIQUIDACION DE COMPRAS NO PROCESADO' || $resp[1] == 'COMPROBANTE DE RETENCION NO PROCESADO' || $resp[1]=='GUIA DE REMISION NO PROCESADO' || isset($resp[1]) && $resp[1]=='NOTA DE CREDITO NO PROCESADO')
	   		 {
	   		 	// print_r($resp[1].'<br>');

	   		 	return -1;
	   		 }else if(isset($resp[1]) && $resp[1]=='FACTURA AUTORIZADO' || isset($resp[1]) && $resp[1]=='LIQUIDACION DE COMPRAS AUTORIZADO' || $resp[1] == 'COMPROBANTE DE RETENCION AUTORIZADO' || isset($resp[1]) && $resp[1]=='GUIA DE REMISION AUTORIZADO' || isset($resp[1]) && $resp[1]=='NOTA DE CREDITO AUTORIZADO')
	   		 {
	   		 	// print_r('as');
	   		 	return 1;
	   		 }else
	   		 {
	   			return 'ERROR COMPROBACION -'.$f[0];
	   		 }
	   	}else
	   	{
	   		return 2;
	   	}
    }

    //envia el xml asia el sri
    function enviar_xml_sri($clave_acceso,$url_recepcion)
    {
    	$entidad =  generaCeros($_SESSION['INGRESO']['IDEntidad'],3);
    	$empresa = $_SESSION['INGRESO']['item'];

    	$ruta_firmados=dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Firmados/';
    	$ruta_enviados=dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Enviados/';
 	    $ruta_rechazados =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Rechazados/';
    	$enviar_sri = dirname(__DIR__).'/SRI/firmar/sri_enviar.jar';

    	if(!file_exists($ruta_firmados.$clave_acceso.'.xml'))
    	{
    		$respuesta = ' XML firmado no encontrado';
	 		return $respuesta;
    	}
    	 // print_r("java -jar ".$enviar_sri." ".$clave_acceso." ".$ruta_firmados." ".$ruta_enviados." ".$ruta_rechazados." ".$url_recepcion);die();
   		 exec("java -jar ".$enviar_sri." ".$clave_acceso." ".$ruta_firmados." ".$ruta_enviados." ".$ruta_rechazados." ".$url_recepcion,$f);
   		 // print_r($f);die();
   		 if(count($f)>0)
   		 {
	   		 $resp = explode('-',$f[0]);
	   		 if($resp[1]=='RECIBIDA')
	   		 {
	   		 	return 1;
	   		 }else if($resp[1]=='DEVUELTA')
	   		 {
	   		 	return 2;
	   		 }else if($resp[1]==null || $resp[1]=='' )
	   		 {
	   		 	//es devuelta
	   		 	return 2;
	   		 }else
	   		 {  
	   		 	return $f;
	   		 }
   		}else
   		{
   			// algo paso
   			return 2;
   		}
    }

    function actualizar_datos_GR($TFA)
    {
    	$TFA['Fecha_Aut_GR'] = date('Y-m-d');
	    $TFA['Hora_GR'] = date("H:i:s");
	    $sql = "UPDATE Facturas_Auxiliares
	        SET Autorizacion_GR = '".$TFA['ClaveAcceso_GR']."',
	        Fecha_Aut_GR = '".$TFA['Fecha_Aut_GR']."',
	        Hora_Aut_GR = '".$TFA['Hora_GR']."',
	        Estado_SRI_GR = 'OK'
	        WHERE Item = '".$_SESSION['INGRESO']['item']."'
	        AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
	        AND TC = '".$TFA['TC']."'
	        AND Serie = '".$TFA['Serie']."'
	        AND Factura = ".$TFA['Factura']."
	        AND Remision = " .$TFA['Remision']." 
	        AND CodigoC = '".$TFA['CodigoC']."'
	        AND Autorizacion = '".$TFA['Autorizacion']."' ";
	    return    $this->db->String_Sql($sql);

    }

    function actualizar_datos_CE($autorizacion,$tc,$serie,$factura,$entidad,$autorizacion_ant,$fecha_emi = false,$codigoC=false)
    {
    	   $fecha = date('Y-m-d');
    	   if($fecha_emi)
    	   {
    		 $fecha = date("Y-m-d", strtotime(str_replace('/','-',$fecha_emi)));
    	    }
			$con = $this->db->conexion();
			$sql ="UPDATE Facturas SET Autorizacion='".$autorizacion."',Clave_Acceso='".$autorizacion."' 
			WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			AND TC = '".$tc."' 
			AND Serie = '".$serie."' 
			AND Factura = ".$factura." 
			AND LEN(Autorizacion) = 13 
			AND T <> 'A'; ";
			// print_r($sql);die();
			$stmt = sqlsrv_query($con, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			$sql="UPDATE Detalle_Factura SET Autorizacion='".$autorizacion."' 
			WHERE Item = '".$_SESSION['INGRESO']['item']."' 
			AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			AND TC = '".$tc."' 
			AND Serie = '".$serie."' 
			AND Autorizacion = '".$autorizacion_ant."' 
			AND Factura = ".$factura." 
			AND LEN(Autorizacion) >= 13 
			AND T <> 'A'; ";
			//echo $sql;
			$stmt = sqlsrv_query($con, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			//modificamos trans abonos
			$sql="UPDATE Trans_Abonos SET Autorizacion='".$autorizacion."',Clave_Acceso='".$autorizacion."' WHERE Item = '".$_SESSION['INGRESO']['item']."' 
			 AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			 AND TP = '".$tc."' 
			 AND Serie = '".$serie."' 
			AND Autorizacion = '".$autorizacion_ant."' 
			AND Factura = ".$factura." 
			AND LEN(Autorizacion) >= 13 
			AND T <> 'A'; ";
			$stmt = sqlsrv_query($con, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			//modificamos factura axiliares
			$sql = "UPDATE Facturas_Auxiliares 
                 SET Autorizacion = '".$autorizacion."' 
                 WHERE Item = '".$_SESSION['INGRESO']['item']."' 
                 AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
                 AND TC = '" .$tc."' 
                 AND Serie = '" .$serie."' 
                 AND Factura = ".$factura." 
                 AND CodigoC = '".$codigoC."' 
                 AND Autorizacion = '".$autorizacion_ant."' ";
            $this->db->String_Sql($sql);
            

			//creamos trans_documentos
			//echo $ban1[2];
			$url_autorizado =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$_SESSION['INGRESO']['item'].'/Autorizados/'.$autorizacion.'.xml';

			$archivo = fopen($url_autorizado,"rb");
			if( $archivo == false ) 
			{
				echo "Error al abrir el archivo";
			}
			else
			{
				rewind($archivo);   // Volvemos a situar el puntero al principio del archivo
				$cadena2 = fread($archivo, filesize($url_autorizado));  // Leemos hasta el final del archivo
				$cadena2 = str_replace('ï»¿', '', $cadena2);

				if( $cadena2 == false )
					echo "Error al leer el archivo";
				else
				{
					//echo "<p>\$contenido1 es: [".$cadena1."]</p>";
					//echo "<p>\$contenido2 es: [".$cadena2."]</p>";
				}
			}
			// Cerrar el archivo:
			fclose($archivo);
			$sql="INSERT INTO Trans_Documentos
		    (Item,Periodo,Clave_Acceso,Documento_Autorizado,TD,Serie,Documento,Fecha,X)
			 VALUES
		    ('".$_SESSION['INGRESO']['item']."' 
		    ,'".$_SESSION['INGRESO']['periodo']."' 
		    ,'".$autorizacion."'
		    ,'".$cadena2."'
		    ,'".$tc."' 
		    ,'".$serie."' 
		    ,".$factura." 
		    ,'".$fecha."'
			,'.');";
			//echo $sql;
			$stmt = sqlsrv_query($con, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			return 1;
    }
    function quitar_carac($query)
    {
    	// $query = preg_replace("[\n|\r|\n\r]", "", $query);
    	$query = preg_replace("/[\n\r\x{EF}\x{BB}\x{BF}]/u", "", $query);
    	$buscar = array('á','é','í','ó','ú','Á','É','Í','Ó','Ú','Ñ','ñ','/','?','�','-','.','ï»¿');
    	$remplaza = array('a','e','i','o','u','A','E','I','O','U','N','n','','','','','',);
    	$corregido = str_replace($buscar, $remplaza, $query);
    	 // print_r($corregido);
    	$corregido = trim($corregido);
    	return $corregido;

    }

    function datos_rimpe()
    {
    	$tipo_con = Tipo_Contribuyente_SP_MYSQL($_SESSION['INGRESO']['RUC']);
    	// print_r($sql);die();
    	return $tipo_con;
    }

  function catalogo_lineas($TC,$SerieFactura)
  {
  	$sql = "SELECT *
         FROM Catalogo_Lineas
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
         AND Fact = '".$TC."'
         AND Serie = '".$SerieFactura."'
         AND Autorizacion = '".$_SESSION['INGRESO']['RUC']."'
         AND TL <> 0
         ORDER BY Codigo ";
         // print_r($sql);die();
	  return $this->db->datos($sql);

  }

  function catalogo_lineas_sri($TC,$SerieFactura,$emision,$vencimiento,$electronico=false)
  {
  	$sql = "SELECT *
         FROM Catalogo_Lineas
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
         AND Fact = '".$TC."'
         AND Serie = '".$SerieFactura."'
         AND CONVERT(DATE,Fecha) <= '".$emision."'
         AND CONVERT(DATE,Vencimiento) >= '".$vencimiento."'";
         if($electronico)
         {
           $sql.=" AND len(Autorizacion)=13";
         }
         $sql.=" ORDER BY Codigo ";
         // print_r($sql);die();
	  return $this->db->datos($sql);
  }

  function recuperar_cliente_xml_a_factura($documento,$autorizacion,$entidad,$empresa)
  {
  	 $ruta_G = dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Autorizados';
		     // print_r($ruta_G);die();
	if($archivo = fopen($ruta_G.'/'.$autorizacion.'.xml',"w+b"))
	  {
	  	fwrite($archivo,$documento);
	  }

  	$texto = file_get_contents($ruta_G.'/'.$autorizacion.'.xml');
  	$texto = str_replace('ï»¿','', $texto);
  	$texto = str_replace('<![CDATA[<?xml version="1.0" encoding="UTF-8" standalone="no"?>','', $texto);
	$texto = str_replace('<![CDATA[<?xml version="1.0" encoding="UTF-8"?>','', $texto);
	  	$texto = str_replace(']]>','', $texto);
	  	// print_r($texto);die();
	$xml = simplexml_load_string($texto);

	$objJsonDocument = json_encode($xml);
	$factura = json_decode($objJsonDocument, TRUE);
	// print_r($factura);die();
	$tributaria = $factura['comprobante']['factura']['infoTributaria'];
	$cabecera = $factura['comprobante']['factura']['infoFactura'];
	$detalle = $factura['comprobante']['factura']['detalles'];

	// print_r($tributaria);die();



	$serie = $tributaria['estab'].''.$tributaria['ptoEmi'];
	$CI_RUC = $cabecera['identificacionComprador'];	
	$cliente_xml = $cabecera['razonSocialComprador'];
	$Fecha = date('Y-m-d',strtotime(str_replace('/','-',$cabecera['fechaEmision'])));
	$codigoC = $this->datos_cliente($codigo=false,$CI_RUC);
	// print_r($CI_RUC);die();
	if(count($codigoC)>0)
	{
		return array('Codigo' =>$codigoC[0]['Codigo'],'Fecha'=>$Fecha);
	}else
	{
		$veri = Digito_verificador($CI_RUC);
		// print_r($datos);die();
		SetAdoAddNew("Clientes");
    	SetAdoFields("FA",1);
    	SetAdoFields("CI_RUC",$CI_RUC);
    	SetAdoFields("Cliente",$cliente_xml);
    	SetAdoFields("TD",$veri['Tipo_Beneficiario']);
    	SetAdoFields("Codigo",$veri['Codigo_RUC_CI']);
    	SetAdoUpdate();
		
		return array('Codigo' =>$veri['Codigo_RUC_CI'],'Fecha'=>$Fecha);

		// return -1;
	}
}


function recuperar_xml_a_factura($documento,$autorizacion,$entidad,$empresa)
{
	$this->generar_carpetas($entidad,$empresa);
	$respuesta = 1;
	//busco el archivo xml
	$ruta_G = dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/Autorizados';
	// print_r($ruta_G);die();
	if($archivo = fopen($ruta_G.'/'.$autorizacion.'.xml',"w+b"))
	{
		fwrite($archivo,$documento);
	}

	$texto = file_get_contents($ruta_G.'/'.$autorizacion.'.xml');
	$texto = str_replace('EN PROCESO','nulo', $texto,$remplazado);
	if($remplazado>0)
	{
		return -2;
	}

	$texto = str_replace('ï»¿','', $texto);
	$texto = str_replace('<![CDATA[<?xml version="1.0" encoding="UTF-8" standalone="no"?>','', $texto);
  	$texto = str_replace('<![CDATA[<?xml version="1.0" encoding="UTF-8"?>','', $texto);
  	$texto = str_replace(']]>','', $texto);

	// print_r($texto);die();
	$xml = simplexml_load_string($texto);


	$objJsonDocument = json_encode($xml);
	$factura = json_decode($objJsonDocument, TRUE);
	// print_r($factura);die();
	$tributaria = $factura['comprobante']['factura']['infoTributaria'];
	$cabecera = $factura['comprobante']['factura']['infoFactura'];
	$detalle = $factura['comprobante']['factura']['detalles'];

	// print_r($tributaria);die();



	$serie = $tributaria['estab'].''.$tributaria['ptoEmi'];
	$CI_RUC = $cabecera['identificacionComprador'];
	$Fecha = date('Y-m-d',strtotime(str_replace('/','-',$cabecera['fechaEmision'])));
	$codigoC = $this->datos_cliente($codigo=false,$CI_RUC);
	if(count($codigoC)==0)
	{
		return -1;
	}
	$cliente = Leer_Datos_Cliente_FA($codigoC[0]['Codigo']);

	// print_r($cliente);die();

	$CodigoL = '.';
	$CodigoL2 = $this->catalogo_lineas_sri('FA',$serie,$Fecha,$Fecha,1);
	// print_r($CodigoL2);die();
	if(count($CodigoL2)>0)
	{
		$CodigoL = $CodigoL2[0]['Codigo'];
	}

	$A_No = 0;
	$Real3 = 0;
	$Real2 = 0;

	if(isset($detalle['detalle']['codigoPrincipal']))
	{
	//no se hace nada
	}else
	{
		$detalle = $detalle['detalle'];
	}
	foreach ($detalle as $key => $value) 
	{
	// print_r($value);die();
		$producto = Leer_Codigo_Inv($value['codigoPrincipal'],$Fecha,$CodBodega='',$CodMarca='');
		$Real1 = number_format(floatval($value['cantidad']),2,'.','') * number_format($value['precioUnitario'],6,'.','');
		if($producto['datos']['IVA']!=0)
		{
			$Real3 = number_format(($Real1 - $Real2) * $_SESSION['INGRESO']['porc'],2,'.','');
		}else
		{
			$Real3 = 0;
		}
		if(!isset($value['descuento']))
		{
			$value['descuento'] = 0;
		}

		// print_r($producto);
		// print_r($value);
		// die();

		SetAdoAddNew("Asiento_F");
		SetAdoFields("CODIGO",$value['codigoPrincipal']);
		SetAdoFields("CODIGO_L",$CodigoL);
		SetAdoFields("PRODUCTO",$value['descripcion']);
		SetAdoFields("Tipo_Hab",'.');
		SetAdoFields("CANT",number_format(floatval($value['cantidad']),2,'.',''));
		SetAdoFields("PRECIO",number_format($value['precioUnitario'],$_SESSION['INGRESO']['Dec_PVP'],'.',''));
		SetAdoFields("TOTAL",number_format(floatval($value['cantidad']),2,'.','') * number_format($value['precioUnitario'],6,'.',''));
		SetAdoFields("Total_IVA",$Real3);
		SetAdoFields("Item",$empresa);
		SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
		SetAdoFields("Codigo_Cliente",$cliente['CodigoC']);
		SetAdoFields("A_No",$A_No+1);
		SetAdoFields("CodBod",'.');
		SetAdoFields("COSTO",$producto['datos']['Costo']);
		SetAdoFields("Total_Desc",$value['descuento']);
		SetAdoFields("FECHA",$Fecha);

		if($producto['datos']['Costo']>0)
		{
			SetAdoFields("Cta_Inv",$producto['datos']['Cta_Inventario']);
			SetAdoFields("Cta_Costo",$producto['datos']['Cta_Costo_Venta']);
		}
	    

		if(SetAdoUpdate()!=1)
		{
			$respuesta = -1;
		};
	}

	return $respuesta;


}

function Actualizar_factura($CI_RUC,$FacturaNo,$serie)
{
	$digito = Digito_verificador($CI_RUC);
	$cli = $this->datos_cliente_todo(false,$CI_RUC);
	// print_r($digito);
	// print_r($cli);die();

	if(count($cli)>0)
	{			
		if($cli[0]['TD']=='' || $cli[0]['TD']=='.' || $cli[0]['Codigo']=='' || $cli[0]['Codigo']=='.' || $cli[0]['TD']!=$digito['Tipo_Beneficiario'] || $cli[0]['Codigo']!= $digito['Codigo_RUC_CI'])
		{
			SetAdoAddNew("Clientes");
	    	SetAdoFields("Codigo",$digito['Codigo_RUC_CI']);
	    	SetAdoFields("TD",$digito['Tipo_Beneficiario']);
	    	SetAdoFields("FA",1);

			SetAdoFieldsWhere('CI_RUC',$CI_RUC);
			SetAdoUpdateGeneric();
		}

		$cliente = Leer_Datos_Cliente_FA($digito['Codigo_RUC_CI']);
		// print_r($cliente);die();
		SetAdoAddNew("Facturas");
		SetAdoFields('CodigoC',$cliente['CodigoC']);
		SetAdoFields('TB',$cliente['TD']);
		SetAdoFields('Razon_Social',$cliente['Razon_Social']);
		SetAdoFields('Direccion_RS',$cliente['DireccionC']);
		SetAdoFields('Telefono_RS',$cliente['TelefonoC']);

		SetAdoFieldsWhere('Serie',$serie);
		SetAdoFieldsWhere('Factura',$FacturaNo);
		SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);
		SetAdoFieldsWhere('Periodo',$_SESSION['INGRESO']['periodo']);
		SetAdoFieldsWhere('TC','FA');
		SetAdoUpdateGeneric();

		SetAdoAddNew("Detalle_Factura");
		SetAdoFields('T',"P");
		SetAdoFieldsWhere('Serie',$serie);
		SetAdoFieldsWhere('Factura',$FacturaNo);
		SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);
		SetAdoFieldsWhere('Periodo',$_SESSION['INGRESO']['periodo']);
		SetAdoFieldsWhere('TC','FA');
		SetAdoUpdateGeneric();


		// die();

		return 1;
}else
{
return -1;
}
}


function SRI_Actualizar_Documento_XML($ClaveDeAcceso)
{
	 if(strLen($ClaveDeAcceso) >= 13)
	 {
	 	$url_autorizado =dirname(__DIR__).'/entidades/entidad_'.generaCeros($_SESSION['INGRESO']['IDEntidad'],3)."/CE".generaCeros($_SESSION['INGRESO']['item'],3).'/Autorizados/'.$ClaveDeAcceso.'.xml';

			$archivo = fopen($url_autorizado,"rb");
			if( $archivo == false ) 
			{
				echo "Error al abrir el archivo";
			}
			else
			{
				rewind($archivo);   // Volvemos a situar el puntero al principio del archivo
				$DatosXMLA = fread($archivo, filesize($url_autorizado));  // Leemos hasta el final del archivo
				$DatosXMLA = str_replace('ï»¿', '', $DatosXMLA);
				if( $DatosXMLA == false )
				{
					echo "Error al leer el archivo";
				}				
			}

	    if(strLen($DatosXMLA) > 1 )
	    {
	       $SerieF = substr($ClaveDeAcceso, 24, 6);
	       $Documento = intval(substr($ClaveDeAcceso, 30, 9));
	       switch (substr($ClaveDeAcceso, 8, 2)) {
	       	case '01':
	       		$TD = "FA";
	       		break;
	       	case '03':
	       		$TD = "LC";
	       		break;
	       	case '04':
	       		$TD = "NC";
	       		break;
	       	case '07':
	       		 $TD = "RE";
	       		break;	       	
	       	default:
	       		$TD = "XX";
	       		break;
	       }

	       $sql = "SELECT * 
	        FROM Trans_Documentos 
	        WHERE Item = '".$_SESSION['INGRESO']['item']."' 
	        AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
	        AND Clave_Acceso = '".$ClaveDeAcceso."' ";
	        $datos = $this->db->datos($sql);
	        if(count($datos)<=0)
	        {
	        	SetAdoAddNew("Trans_Documentos");
    			SetAdoFields("Item",$_SESSION['INGRESO']['item']);
    			SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
    			SetAdoFields("Clave_Acceso",$ClaveDeAcceso);
    			SetAdoFields("TD",$TD);
    			SetAdoFields("Serie",$SerieF);
    			SetAdoFields("Documento",$Documento);
    			SetAdoFields("Documento_Autorizado",$DatosXMLA);
    			SetAdoUpdate();
	        }
	    }
	 }
}

function SRI_Actualizar_Autorizacion_Nota_Credito($TFA)
{
	$Fecha_Autorizacion= date('Y-m-d');
    $Hora_Autorizacion= date('H:i:s');
    $Estado_SRI= 'OK';

    $sql = "UPDATE Trans_Abonos
        SET Autorizacion_NC = '".$TFA['ClaveAcceso_NC']."',
        Fecha_Aut_NC = '".BuscarFecha($Fecha_Autorizacion)."',
        Hora_Aut_NC = '".$Hora_Autorizacion."',
        Estado_SRI_NC = '".$Estado_SRI."'
        WHERE Item = '".$_SESSION['INGRESO']['item']."'
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
        AND TP = '".$TFA['TC']."'
        AND Serie = '".$TFA['Serie']."'
        AND Factura = ".$TFA['Factura']."
        AND CodigoC = '".$TFA['CodigoC']."'
        AND Autorizacion = '".$TFA['Autorizacion']."'
        AND Serie_NC = '".$TFA['Serie_NC']."'
        AND Secuencial_NC = ".$TFA['Nota_Credito']."
        AND Banco = 'NOTA DE CREDITO' ";
        $this->db->String_Sql($sql);
    
    $sql = "UPDATE Detalle_Nota_Credito 
        SET Autorizacion = '".$TFA['ClaveAcceso_NC']."' 
        WHERE Item = '".$_SESSION['INGRESO']['item']."' 
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND TC = '".$TFA['TC']."' 
        AND Serie_FA = '".$TFA['Serie']."' 
        AND Factura = ".$TFA['Factura']." 
        AND Serie = '".$TFA['Serie_NC']."' 
        AND Secuencial = ".$TFA['Nota_Credito']." ";
        $this->db->String_Sql($sql);
}

function actualizar_datos_CER($autorizacion,$tc,$serie,$retencion,$entidad,$autorizacion_ant,$fecha)
 {

 	$res = $this->actualizar_trans_compras($tc,$retencion,$serie,$autorizacion,$autorizacion_ant);
 	$res2 = $this->atualizar_trans_air($tc,$retencion,$serie,$autorizacion,$autorizacion_ant);
	$url_autorizado =dirname(__DIR__,2).'/comprobantes/entidades/entidad_'.$entidad."/CE".$_SESSION['INGRESO']['item'].'/Autorizados/'.$autorizacion.'.xml';
	$archivo = fopen($url_autorizado,"rb");
		if( $archivo != false ) 
		{			
			rewind($archivo);   // Volvemos a situar el puntero al principio del archivo
			$cadena2 = fread($archivo, filesize($url_autorizado));  // Leemos hasta el final del archivo
			if( $cadena2 == false ){
				echo "Error al leer el archivo";
			}
		}
		// Cerrar el archivo:
		fclose($archivo);	

	$res3 = $this->guardar_documento($autorizacion,$cadena2,$serie,$retencion,$fecha);	
		//echo $sql;
	if($res==1)
	{
		if($res2==1)
		{
			if($res3==1)
			{
				return 1;
			}else
			{
				return -3;
			}
		}else
		{
			return -2;
		}
	}else
	{
		return -1;
	}
		
		// return 1;

 }
  function guardar_documento($autorizacion,$cadena2,$serie,$retencion,$Fecha)
     {

		$cadena2 = str_replace('ï»¿', '', $cadena2);
     	$sql="INSERT INTO Trans_Documentos
		    (Item,Periodo,Clave_Acceso,Documento_Autorizado,TD,Serie,Documento,Fecha,X)
			 VALUES
		    ('".$_SESSION['INGRESO']['item']."' 
		    ,'".$_SESSION['INGRESO']['periodo']."' 
		    ,'".$autorizacion."'
		    ,'".$cadena2."'
		    ,'RE' 
		    ,'".$serie."' 
		    ,".$retencion." 
		    ,".$Fecha."
			,'.');";
		return $this->db->String_Sql($sql);

     }

 function actualizar_trans_compras($tp,$retencion,$serie,$autorizacion,$autAnte)
 {
 	$sql ="UPDATE Trans_Compras SET AutRetencion='".$autorizacion."' 
 		WHERE Item = '".$_SESSION['INGRESO']['item']."' 
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND TP = '".$tp."' 
		AND SecRetencion = '".$retencion."'
		AND Serie_Retencion = '".$serie."' 
		AND LEN(AutRetencion) = 13
		AND AutRetencion = '".$autAnte."'";
		// print_r($sql);die();
	return $this->db->String_Sql($sql);

 }
 function atualizar_trans_air($tp,$retencion,$serie,$autorizacion,$autAnte)
 {
 	$sql="UPDATE Trans_Air SET AutRetencion='".$autorizacion."' 
 	WHERE Item = '".$_SESSION['INGRESO']['item']."' 
	AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' 
	AND TP = '".$tp."' 
	AND EstabRetencion = '".substr($serie,0,3)."'
	AND PtoEmiRetencion =  '".substr($serie,3,6)."'
	AND SecRetencion = '".$retencion."'
	AND LEN(AutRetencion) = 13  
	AND AutRetencion ='".$autAnte."'";
	return $this->db->String_Sql($sql);
 }


 function money_formato($valor,$decimales)
 {

 	$valor = number_format($valor,$decimales,'.','');
 	if(strpos($valor,'.')!==false)
 	{
 		$numero_decimales = explode('.',$valor);
 		$deci = substr($numero_decimales[1],0,$decimales);
 		if($numero_decimales[0]==''){$numero_decimales[0] = 0;}
 		return $numero_decimales[0].'.'.$deci;
 	}else
 	{
 		if($decimales!=0)
 		{
 			$ceros = generaCeros($decimales);
 			return $valor.'.'.$ceros;
 		}else
 		{
 			return $valor;
 		}
 	}
 }


function error_sri($clave)
{
	$clave = $clave.'.xml';
	$entidad = generaCeros($_SESSION['INGRESO']['IDEntidad'],3);
	$carpeta_entidad = dirname(__DIR__,2)."/comprobantes/entidades/entidad_".$entidad;
	$carpeta_comprobantes = $carpeta_entidad.'/CE'.generaCeros($_SESSION['INGRESO']['item'],3);
	$carpeta_no_autori = $carpeta_comprobantes."/No_autorizados";
	$carpeta_rechazados = $carpeta_comprobantes."/Rechazados";
			  
	    

	$ruta1 = $carpeta_no_autori.'/'.$clave;
	$ruta2 = $carpeta_rechazados.'/'.$clave;

	// print_r($ruta1);print_r($ruta2);die();
	if(file_exists($ruta1))
	{

	// print_r($ruta);die();
		$xml = simplexml_load_file($ruta1);
		$codigo = $xml->mensajes->mensaje->mensaje->identificador;
		$mensaje = $xml->mensajes->mensaje->mensaje->mensaje;
		$adicional = $xml->mensajes->mensaje->mensaje->informacionAdicional;
		$estado = $xml->estado;
		$fecha = $xml->fechaAutorizacion;
		// print_r($mensaje);die();
		return  array('estado'=>$estado,'codigo'=>$codigo,'mensaje'=>$mensaje,'adicional'=>$adicional,'fecha'=>$fecha);
	}

	if(file_exists($ruta2))
	{
	    // print_r($ruta2);die();
		$fp = fopen($ruta2, "r");
		 $linea = '';
		while (!feof($fp)){
		    $linea.= fgets($fp);
		}
		fclose($fp);
		$linea = str_replace('ns2:','', $linea);
		$xml = simplexml_load_string($linea);

		$codigo = $xml->respuestaSolicitud->comprobantes->comprobante->mensajes->mensaje->identificador;
		$mensaje = $xml->respuestaSolicitud->comprobantes->comprobante->mensajes->mensaje->mensaje;
		$adicional = $xml->respuestaSolicitud->comprobantes->comprobante->mensajes->mensaje->informacionAdicional;
		$estado = $xml->respuestaSolicitud->estado;
		$fecha = '';
		// print_r($mensaje);die();
		return  array('estado'=>$estado,'codigo'=>$codigo,'mensaje'=>$mensaje,'adicional'=>$adicional,'fecha'=>$fecha);

	}
}



	function generar_carpetas($entidad,$empresa)
	{
	if(strlen($entidad)<3){$entidad=generaCeros($entidad,3);} if(strlen($empresa)<3){$empresa=generaCeros($empresa,3);}
	    $carpeta_entidad=dirname(__DIR__)."/entidades/entidad_".$entidad; $carpeta_autorizados="" ; $carpeta_generados="" ;
	    $carpeta_firmados="" ; $carpeta_no_autori="" ; if(file_exists($carpeta_entidad)) {
	    $carpeta_comprobantes=$carpeta_entidad.'/CE'.$empresa; if(file_exists($carpeta_comprobantes)) {
	    $carpeta_autorizados=$carpeta_comprobantes."/Autorizados"; $carpeta_generados=$carpeta_comprobantes."/Generados";
	    $carpeta_firmados=$carpeta_comprobantes."/Firmados"; $carpeta_no_autori=$carpeta_comprobantes."/No_autorizados";
	    $carpeta_rechazados=$carpeta_comprobantes."/Rechazados"; $carpeta_rechazados=$carpeta_comprobantes."/Enviados";
	    if(!file_exists($carpeta_autorizados)) { mkdir($carpeta_entidad."/CE".$empresa."/Autorizados", 0777); }
	    if(!file_exists($carpeta_generados)) { mkdir($carpeta_entidad.'/CE'.$empresa.'/Generados', 0777); }
	    if(!file_exists($carpeta_firmados)) { mkdir($carpeta_entidad.'/CE'.$empresa.'/Firmados', 0777); }
	    if(!file_exists($carpeta_no_autori)) { mkdir($carpeta_entidad.'/CE'.$empresa.'/No_autorizados', 0777); }
	    if(!file_exists($carpeta_rechazados)) { mkdir($carpeta_entidad.'/CE'.$empresa.'/Rechazados', 0777); }
	    if(!file_exists($carpeta_rechazados)) { mkdir($carpeta_entidad.'/CE'.$empresa.'/Enviados', 0777); } }else {
	    mkdir($carpeta_entidad.'/CE'.$empresa, 0777); mkdir($carpeta_entidad."/CE".$empresa."/Autorizados", 0777);
	    mkdir($carpeta_entidad.'/CE'.$empresa.'/Generados', 0777); mkdir($carpeta_entidad.'/CE'.$empresa.'/Firmados', 0777);
	    mkdir($carpeta_entidad.'/CE'.$empresa.'/No_autorizados', 0777); mkdir($carpeta_entidad.'/CE'.$empresa.'/Rechazados',
	    0777); mkdir($carpeta_entidad.'/CE'.$empresa.'/Enviados', 0777); } }else { mkdir($carpeta_entidad, 0777);
	    mkdir($carpeta_entidad.'/CE'.$empresa, 0777); mkdir($carpeta_entidad."/CE".$empresa."/Autorizados", 0777);
	    mkdir($carpeta_entidad.'/CE'.$empresa.'/Generados', 0777); mkdir($carpeta_entidad.'/CE'.$empresa.'/Firmados', 0777);
	    mkdir($carpeta_entidad.'/CE'.$empresa.'/No_autorizados', 0777); mkdir($carpeta_entidad.'/CE'.$empresa.'/Rechazados',
	    0777); mkdir($carpeta_entidad.'/CE'.$empresa.'/Enviados', 0777); } 
	} 


	function generar_solo_xml_factura($parametros)
	{
		// 1 para autorizados
	    //-1 para no autorizados y devueltas
	    // 2 para devueltas
	    //-2 no existe la factura
	    // texto del erro en forma de matris
		$cabecera['ambiente']=$_SESSION['INGRESO']['Ambiente'];
	    $cabecera['ruta_ce']=$_SESSION['INGRESO']['Ruta_Certificado'];
	    $cabecera['clave_ce']=$_SESSION['INGRESO']['Clave_Certificado'];
	    $cabecera['nom_comercial_principal']=$this->quitar_carac($_SESSION['INGRESO']['Nombre_Comercial']);
	    $cabecera['razon_social_principal']=$this->quitar_carac($_SESSION['INGRESO']['Razon_Social']);
	    $cabecera['ruc_principal']=$_SESSION['INGRESO']['RUC'];
	    $cabecera['direccion_principal']= $this->quitar_carac($_SESSION['INGRESO']['Direccion']);
	    $cabecera['Entidad'] = generaCeros($_SESSION['INGRESO']['IDEntidad'],3);
	   
	    if(isset($parametros['serie'])){
	    	$cabecera['serie']=$parametros['serie'];
	    	$cabecera['esta']=substr($parametros['serie'],0,3); 
	    	$cabecera['pto_e']=substr($parametros['serie'],3,5); 	    
	    }else if(isset($parametros['Serie']))
	    {	    	
	    	$parametros['serie'] = $parametros['Serie'];
	    	$cabecera['serie']=$parametros['Serie'];
	    	$cabecera['esta']=substr($parametros['Serie'],0,3); 
	   		$cabecera['pto_e']=substr($parametros['Serie'],3,5); 	    
	    }

	    if(isset($parametros['num_fac'])){
	    	$cabecera['factura']=$parametros['num_fac'];
	    }else if(isset($parametros['FacturaNo']))
	    {	    	
	    	$cabecera['factura']=$parametros['FacturaNo'];
	    }
	    else if(isset($parametros['Factura']))
	    {	    	
	    	$cabecera['factura']=$parametros['Factura'];
	    }	    
	    $cabecera['item']=$_SESSION['INGRESO']['item'];

	    if(isset($parametros['tc'])){
	    	$cabecera['tc']=$parametros['tc'];
	    }else if(isset($parametros['TC']))
	    {	    	
	    	$cabecera['tc']=$parametros['TC'];
	    }

	    if(isset($parametros['cod_doc'])){
	    	$cabecera['cod_doc']=$parametros['cod_doc'];
	    }

	    $cabecera['periodo']=$_SESSION['INGRESO']['periodo'];
		if( isset($cabecera['tc']) && $cabecera['tc']=='LC' || isset($cabecera['TC']) && $cabecera['TC']=='LC')
		{
			$cabecera['cod_doc']='03';
		}else if($cabecera['tc']=='FA')
		{
			$cabecera['cod_doc']='01';
		}

		//sucursal
		 $sucursal = $this->catalogo_lineas($cabecera['tc'],$cabecera['serie']);
		 if(count($sucursal)>0)
		 {
		 	$cabecera['Nombre_Establecimiento'] = $sucursal[0]['Nombre_Establecimiento'];
		 	$cabecera['Direccion_Establecimiento'] = $sucursal[0]['Direccion_Establecimiento'];
		 	$cabecera['Telefono_Establecimiento'] = $sucursal[0]['Telefono_Estab'];
		 	$cabecera['Ruc_Establecimiento'] = $sucursal[0]['RUC_Establecimiento'];
		 	$cabecera['Email_Establecimiento'] = $sucursal[0]['Email_Establecimiento'];
		 	$cabecera['Placa_Vehiculo'] ='.';
		 	$cabecera['Cta_Establecimiento'] = '.';
		 	if(isset($sucursal[0]['Placa_Vehiculo']))
		 	{
		 		$cabecera['Placa_Vehiculo'] = $sucursal[0]['Placa_Vehiculo'];
		 	}
		 	if (isset($sucursal[0]['Cta_Establecimiento'])) {
		 		$cabecera['Cta_Establecimiento'] = $sucursal[0]['Cta_Establecimiento'];
		 	}		 	
		 }
				//datos de factura
		 // print_r($cabecera);die();
	    		$datos_fac = $this->datos_factura($cabecera['serie'],$cabecera['factura'],$cabecera['tc'],false);
	    		if(count($datos_fac)==0)
	    		{
	    			return -2;
	    		}
	    		// print_r($datos_fac);die();
	    	    $cabecera['RUC_CI']=$this->quitar_carac($datos_fac[0]['RUC_CI']);
				$cabecera['Fecha']=$datos_fac[0]['Fecha']->format('Y-m-d');
				$cabecera['Razon_Social']=$this->quitar_carac($datos_fac[0]['Razon_Social']);
				$cabecera['Direccion_RS']=$this->quitar_carac($datos_fac[0]['Direccion_RS']);
				$cabecera['Sin_IVA']= $this->money_formato($datos_fac[0]['Sin_IVA'],2);
				$cabecera['Descuento'] = $this->money_formato($datos_fac[0]['Descuento']+$datos_fac[0]['Descuento2'],2);
				$cabecera['baseImponible'] = $datos_fac[0]['Sin_IVA']+$cabecera['Descuento'];
				$cabecera['Porc_IVA'] = $this->money_formato($datos_fac[0]['Porc_IVA'],2);

				// print_r($cabecera['Porc_IVA']);die();
				$cabecera['Con_IVA'] = $datos_fac[0]['Con_IVA'];
				$cabecera['Total_MN'] = $this->money_formato($datos_fac[0]['Total_MN'],2);
				$cabecera['Observacion'] = $datos_fac[0]['Observacion'];
				$cabecera['Nota'] = $datos_fac[0]['Nota'];

				$cabecera['Nota'] = $datos_fac[0]['Nota'];
				if($datos_fac[0]['Tipo_Pago'] == '.')
				{
					$cabecera['formaPago']='01';
				}else
				{
					$cabecera['formaPago']=$datos_fac[0]['Tipo_Pago'];
				}
				$cabecera['Propina']= number_format($datos_fac[0]['Servicio'],2,'.','');
				$cabecera['Autorizacion']=$datos_fac[0]['Autorizacion'];
				$cabecera['Imp_Mes']=$datos_fac[0]['Imp_Mes'];
				$cabecera['SP']=$datos_fac[0]['SP'];
				$cabecera['CodigoC']=$datos_fac[0]['CodigoC'];
				$cabecera['TelefonoC']=$datos_fac[0]['Telefono_RS'];
				$cabecera['Orden_Compra']=$datos_fac[0]['Orden_Compra'];
				$cabecera['baseImponibleSinIva'] = $this->money_formato(($cabecera['Sin_IVA']-$datos_fac[0]['Desc_0']),2);
				$cabecera['baseImponibleConIva'] = $this->money_formato(($cabecera['Con_IVA']-$datos_fac[0]['Desc_X']),2);
				$cabecera['totalSinImpuestos'] = $this->money_formato(($cabecera['Sin_IVA']+$cabecera['Con_IVA']-$cabecera['Descuento']),2);
				$cabecera['IVA'] = $this->money_formato($datos_fac[0]['IVA'],2);
				$cabecera['descuentoAdicional']= $this->money_formato(0,2);
				$cabecera['moneda']="DOLAR";
				$cabecera['tipoIden']='';
				// print_r($cabecera);die();

			//datos de cliente
	    	$datos_cliente = $this->datos_cliente($datos_fac[0]['CodigoC']);
	    	// print_r($datos_cliente);die();
	    	    $cabecera['Cliente']=$this->quitar_carac($datos_cliente[0]['Cliente']);
				$cabecera['DireccionC']=$this->quitar_carac($datos_cliente[0]['Direccion']);
				$cabecera['TelefonoC']=$datos_cliente[0]['Telefono'];
				$cabecera['EmailR']=$this->quitar_carac($datos_cliente[0]['Email2']);
				$cabecera['EmailC']=$this->quitar_carac($datos_cliente[0]['Email']);
				$cabecera['Contacto']=$datos_cliente[0]['Contacto'];
				$cabecera['Grupo']=$datos_cliente[0]['Grupo'];

			//codigo verificador 
				if($cabecera['RUC_CI']=='9999999999999')
				  {
				  	$cabecera['tipoIden']='07';
			      }else
			      {
			      	$cod_veri = Digito_verificador($datos_fac[0]['RUC_CI']);
			      	// print_r($cod_veri);die();
			      	switch ($cod_veri['Tipo_Beneficiario']) {
			      		case 'R':
			      			$cabecera['tipoIden']='04';
			      			break;
			      		case 'C':
			      			$cabecera['tipoIden']='05';
			      			break;
			      		case 'P':
			      			$cabecera['tipoIden']='06';
			      			break;
			      	}
			      }

			      // print_r($cabecera);die();
			    $cabecera['codigoPorcentaje']=0;
			    if((floatval($cabecera['Porc_IVA'])*100)>12)
			    {
			       $cabecera['codigoPorcentaje']=3;
			    }else
			    {
			      $cabecera['codigoPorcentaje']=2;
			    }
			   //detalle de factura
			    $detalle = array();
			    $cuerpo_fac = $this->detalle_factura($cabecera['serie'],$cabecera['factura'],$cabecera['Autorizacion'],$cabecera['tc']);
			    foreach ($cuerpo_fac as $key => $value) 
			    {			    	
			    	$producto = $this->datos_producto($value['Codigo']);
			    	$detalle[$key]['Codigo'] =  $value['Codigo'];
			    	$detalle[$key]['Cod_Aux'] =  $producto[0]['Desc_Item'];
				    $detalle[$key]['Cod_Bar'] =  $producto[0]['Codigo_Barra'];
				    $detalle[$key]['Producto'] = $this->quitar_carac($value['Producto']);
				    $detalle[$key]['Cantidad'] = $this->money_formato($value['Cantidad'],6);
				    $detalle[$key]['Precio'] =  $this->money_formato($value['Precio'],6);
				    $detalle[$key]['descuento'] = $this->money_formato(($value['Total_Desc']+$value['Total_Desc2']),2);
				  if ($cabecera['Imp_Mes']==true)
				  {
				   	$detalle[$key]['Producto'] = $this->quitar_carac($value['Producto']).', '.$value['Ticket'].': '.$value['Mes'].' ';
				  }
				  if($cabecera['SP']==true)
				  {
				  	$detalle[$key]['Producto'] = $this->quitar_carac($value['Producto']).', Lote No. '.$value['Lote_No'].
					', ELAB. '.$value['Fecha_Fab'].
					', VENC. '.$value['Fecha_Exp'].
					', Reg. Sanit. '.$value['Reg_Sanitario'].
					', Modelo: '.$value['Modelo'].
					', Procedencia: '.$value['Procedencia'];
				  }
				   $detalle[$key]['SubTotal'] =  $this->money_formato(($value['Cantidad']*$value['Precio'])-($value['Total_Desc']+$value['Total_Desc2']),2);
				   $detalle[$key]['Serie_No'] = $value['Serie_No'];
				   $detalle[$key]['Total_IVA'] = $this->money_formato($value['Total_IVA'],2);
				   $detalle[$key]['Porc_IVA']= $this->money_formato($value['Porc_IVA'],2);
			    }
			    $cabecera['fechaem']=  date("d/m/Y", strtotime($cabecera['Fecha']));		

			    // $linkSriAutorizacion = $_SESSION['INGRESO']['Web_SRI_Autorizado'];
 	    		// $linkSriRecepcion = $_SESSION['INGRESO']['Web_SRI_Recepcion'];
			    // print_r($cabecera);print_r($detalle);die();
			    $cabecera['ClaveAcceso'] =$this->Clave_acceso($cabecera['Fecha'],$cabecera['cod_doc'],$cabecera['serie'],$cabecera['factura']);
	            
	            $xml = $this->generar_xml($cabecera,$detalle);	
	           	return array('respuesta'=>$xml,'Clave'=>$cabecera['ClaveAcceso']);
	            
	       }

} 
?>