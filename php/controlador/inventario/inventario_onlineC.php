<?php 
require(dirname(__DIR__,2).'/modelo/inventario/inventario_onlineM.php');
require(dirname(__DIR__,2).'/modelo/farmacia/ingreso_descargosM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
$_SESSION['INGRESO']['modulo_']='60';
/**
 * 
 */
$controlador = new inventario_onlineC();
if(isset($_GET['existe_cuenta']))
{
	echo json_encode($controlador->cuenta_existente());
}
if (isset($_GET['producto'])) {
	if(!isset($_GET['q'])){	$_GET['q'] ='';	}
	if(!isset($_GET['centro'])){	$_GET['centro'] ='';}
	echo json_encode($controlador->lista_producto($_GET['centro'],$_GET['q']));
}
if (isset($_GET['rubro'])) {
	if(!isset($_GET['q']))
	{
		$_GET['q'] =''; 
	}	
	$cc = $_GET['pro'];
	echo json_encode($controlador->lista_rubro($_GET['q'],$cc));
}
if (isset($_GET['rubro_bajas'])) {
	if(!isset($_GET['q']))
	{
		$_GET['q'] =''; 
	}
	echo json_encode($controlador->lista_rubro_bajas($_GET['q']));
}
if (isset($_GET['cc'])) {
	if(!isset($_GET['q']))
	{
		$_GET['q'] =''; 
	}
	$pro = $_GET['pro'];
	echo json_encode($controlador->lista_cc($_GET['q'],$pro));
}
if (isset($_GET['entrega'])) {
	
	echo json_encode($controlador->lista_entrega());
}
if (isset($_GET['guardar'])) {
	
	echo json_encode($controlador->guardar_entrega($_POST['parametros']));
}
if (isset($_GET['eliminar_linea'])) {
	echo json_encode($controlador->eliminar_linea_entrega($_POST['parametros']));
}
if (isset($_GET['reporte_pdf'])) {
	echo json_encode($controlador->reporte_pdf());
}
if (isset($_GET['reporte_excel'])) {
	echo json_encode($controlador->reporte_EXcel());
}
if (isset($_GET['producto_id'])) {
	echo json_encode($controlador->producto_id($_GET['centro'],$_GET['q']));
}
if (isset($_GET['generar_asiento'])) {
	echo json_encode($controlador->datos_asientos());
}
if (isset($_GET['datos_asiento'])) {
	echo json_encode($controlador->datos_asientos($_POST['fechaA']));
}

if (isset($_GET['datos_asiento_SC'])) {
	echo json_encode($controlador->datos_asiento_SC($_POST['fecha']));
}

if (isset($_GET['datos_comprobante'])) {
	 $pro = '';
	 if(isset($_POST['proyecto']))
	 {
	 	 $pro = $_POST['proyecto'];
	 }
	echo json_encode($controlador->datos_comprobante($_POST['codigo'],$pro));
}
// if (isset($_GET['Trans_kardex'])) {
// 	echo json_encode($controlador->ingresar_trans_kardex_salidas($_POST['comprobante'],$_POST['f']));
// }
if (isset($_GET['eliminar_asientos_k'])) {
	echo json_encode($controlador->eliminar_asientos_k());
}
if (isset($_GET['stock_kardex'])) {
	echo json_encode($controlador->stock_kardex($_POST['id']));
}
// if (isset($_GET['costo_venta'])) {
// 	echo json_encode($controlador->costo_venta($_POST['id']));
// }

if (isset($_GET['costo_existencias'])) {
	echo json_encode($controlador->costo_existencias($_POST['codigoInv']));
}

if (isset($_GET['codmarca'])) {
	echo json_encode($controlador->codmarca());
}
if (isset($_GET['proyecto'])) {
	echo json_encode($controlador->proyecto());
}
if (isset($_GET['generar_comprobante'])) {
	$fecha = $_POST['fecha'];
	echo json_encode($controlador->generar_factura($fecha));
}
if (isset($_GET['mayorizar'])) {
	 $parametros = $_POST['parametros'];
	echo json_encode($controlador->mayorizar($parametros));
}

if (isset($_GET['validar_presupuesto'])) {
	$parametro = $_POST['parametros'];
	echo json_encode($controlador->validar_presupuesto($parametro));
}

if (isset($_GET['subir_archivo'])) {
    try {

		$ftpHost = "ftp.diskcoversystem.com";
		$ftpUserName = "ftpuser";
		$ftpPassword = "ftp2023User";
		$ftpPuerto = 21;

		//FTP Connection
		$connId = ftp_connect($ftpHost, $ftpPuerto);

		//Login to FTP
		$ftpLogin = ftp_login($connId, $ftpUserName, $ftpPassword);

		ftp_pasv($connId, true);

		$localFilePath  = $_FILES['archivo'];
		$carpetaDestino = dirname(__DIR__, 3) . "/TEMP/";
		$nombreArchivoDestino = $carpetaDestino . basename($localFilePath['name']);//Destino temporal para guardar el archivo
		$remoteFilePath = '/files/' . $localFilePath['name'];//Path donde se va a guardar el archivo

        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == UPLOAD_ERR_OK) {

            if (!is_dir($carpetaDestino)) {
                // Intentar crear la carpeta, el 0777 es el modo de permiso más permisivo
                if (!mkdir($carpetaDestino, 0777, true)) { // true permite la creación de estructuras de directorios anidados
					ftp_close($connId);
                    throw new Exception("No se pudo crear la carpeta");
                }
            }

            if (move_uploaded_file($localFilePath['tmp_name'], $nombreArchivoDestino)) {
				if(ftp_put($connId, $remoteFilePath, $nombreArchivoDestino, FTP_BINARY)){
					ftp_close($connId);
					echo json_encode(array("res" => 1, "mensaje" => "Archivo guardado", "nombreArchivo" => basename($nombreArchivoDestino) ));
				}else{
					ftp_close($connId);
					throw new Exception("No se pudo guardar el archivo en el servidor FTP");
				}
				ftp_close($connId);
                //echo json_encode(array("res" => 1, "mensaje" => "Archivo guardado", "nombreArchivo" => basename($nombreArchivoDestino) ));
            } else {
				ftp_close($connId);
                throw new Exception("No se pudo guardar el archivo en el servidor");
            }
        }else{
			ftp_close($connId);
			throw new Exception("Error al subir el archivo");
		}
    } catch (Exception $e) {
        echo json_encode(array("res" => 0, "mensaje" => "Error al enviar los correos", "error" => $e->getMessage()));
    }
}

if (isset($_GET['eliminar_archivo'])) {
	try {
		$ftpHost = "ftp.diskcoversystem.com";
		$ftpUserName = "ftpuser";
		$ftpPassword = "ftp2023User";
		$ftpPuerto = 21;

		$connId = ftp_connect($ftpHost);

		// login to FTP server
		$ftpLogin = ftp_login($connId, $ftpUserName, $ftpPassword);

		$parametros = $_POST['parametros'];

		// server file path
		$file = '/files/' . $parametros['archivo'];
		$archivoDestino = dirname(__DIR__, 3) . "/TEMP/" . $parametros['archivo'];

		// try to delete file on server
		if(ftp_delete($connId, $file)){
			unlink($archivoDestino);
			echo json_encode(array("res" => 1, "mensaje" => "Archivo eliminado"));
		}else{
			throw new Exception("No se pudo eliminar el archivo del servidor FTP");
		}

		// close the connection
		ftp_close($connId);
	} catch (Exception $e) {
		echo json_encode(array("res" => 0, "mensaje" => "Error al eliminar el archivo", "error" => $e->getMessage()));
	}
}

if (isset($_GET['procesar_archivo'])) {
	try {
		$parametros = $_POST['parametros'];
		$respuesta = $controlador->procesar_archivo_csv($parametros);
		echo json_encode($respuesta);
	} catch (Exception $e) {
		echo json_encode(array("res" => 0, "mensaje" => $e->getMessage(), "error" => $e->getMessage()));
	}
}

if(isset($_GET['asiento_csv'])){
	echo json_encode($controlador->asiento_csv());

}

class inventario_onlineC
{
	private $modelo;
	private $pdf;
	private $ing_des;
	
	function __construct()
	{
		$this->modelo = new inventario_onlineM();		
		$this->pdf = new cabecera_pdf();	
		$this->ing_des = new ingreso_descargosM();
		// $this->pdftable = new PDF_MC_Table();			
	}

	function asiento_csv(){
		try{
			$datos = $this->modelo->asiento_csv();
			return array("res" => 1, "tbl" => $datos['datos']);
		}catch(Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	function procesar_archivo_csv($parametros){
		try{
			$PathArchivo = dirname(__DIR__, 3) . '/TEMP/' . $parametros['archivo'];
			$tmp = Subir_Archivo_CSV_SP($PathArchivo, $parametros['fecha']);
			if($tmp === 1){
				return array("res" => 1, "mensaje" => "Archivo procesado correctamente");
			}else{
				throw new Exception("Error al procesar el archivo");
			}

		}catch(Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	function cuenta_existente()
	{
		$resp = $this->modelo->cuenta_existente();
		return $resp;
	}

	function lista_producto($centro,$query)
	{
		// print_r($centro);die();
		// $resp =  $this->modelo->lista_productos_por_centro_costo($centro,$query);
		$resp = $this->modelo->listar_articulos($query);
		return $resp;
	}
	function lista_rubro($query,$cc)
	{
		// print_r($query);die();
		$resp = $this->modelo->listar_rubro($query,$cc);
		return $resp;
	}

	function lista_rubro_bajas($query)
	{
		// print_r($query);die();
		$resp = $this->modelo->listar_rubro_bajas($query);
		return $resp;
	}
	function lista_cc($query,$proyecto)
	{
		$resp = $this->modelo->listar_cc($query,$proyecto);
		return $resp;
	}

	function lista_entrega()
	{
		$resp = $this->modelo->lista_entrega();
		// $resp= array_map(array($this, 'encode'), $resp);
		// print_r($resp);die();
		return $resp;
	}
	function producto_id($centro,$query)
	{
		$resp =  $this->modelo->lista_productos_por_centro_costo($centro,$query);
		// $resp = $this->modelo->lista_hijos_id($query);
		// $resp= array_map(array($this, 'encode'), $resp);
		// print_r($resp);die();
		return $resp;
	}
	function datos_asientos($fecha)
	{
		$debe = $this->modelo->datos_asiento_debe($fecha);		
		$haber = $this->modelo->datos_asiento_haber($fecha);
		// print_r($haber);die();
		$desperdicios_debe = $this->modelo->desperdicios_debe($fecha);
		$desperdicios_haber = $this->modelo->desperdicios_haber($fecha);
		$datos1 = array();
		$datos2= array();
		foreach ($debe as $key => $value) {
			// print_r($value);die();
			$cuenta = $this->modelo->catalogo_cuentas($value['cuenta']);
			// $d = array('valor'=>$value['total'],'dconcepto1'=>mb_convert_encoding($cuenta[0]['Cuenta'], 'UTF-8'),'codigo'=>$value['cuenta'],'cuenta'=>mb_convert_encoding($cuenta[0]['Cuenta'], 'UTF-8'),'tipo_cue'=>1,'fecha'=>$value['fecha']->format('Y-m-d'));

			$d = array('valor'=>round($value['total'],2),'dconcepto1'=>$cuenta[0]['Cuenta'],'codigo'=>$value['cuenta'],'cuenta'=>$cuenta[0]['Cuenta'],'tipo_cue'=>1,'fecha'=>$value['fecha']->format('Y-m-d'));
			array_push($datos1, $d);

		}

		if(count($desperdicios_debe)>0)
		{
		$cuenta1 = $this->modelo->catalogo_cuentas($_SESSION['INGRESO']['CTA_DESPERDICIO']);
		$tot = 0;
		foreach ($desperdicios_debe as $key => $value) {
			$tot =$tot+ $value['TOTAL'];
			// $d = array('valor'=>$value['total'],'dconcepto1'=>mb_convert_encoding($cuenta[0]['Cuenta'], 'UTF-8'),'codigo'=>$value['cuenta'],'cuenta'=>mb_convert_encoding($cuenta[0]['Cuenta'], 'UTF-8'),'tipo_cue'=>1,'fecha'=>$value['fecha']->format('Y-m-d'))
		}
		$d1 = array('valor'=>round($tot,2),'dconcepto1'=>$cuenta1[0]['Cuenta'],'codigo'=>$_SESSION['INGRESO']['CTA_DESPERDICIO'],'cuenta'=>$cuenta1[0]['Cuenta'],'tipo_cue'=>1,'fecha'=>$value['fecha']->format('Y-m-d'));
			array_push($datos1, $d1);
		}

		foreach ($haber as $key => $value) {
			$cuenta = $this->modelo->catalogo_cuentas($value['cuenta']);
			// $h = array('valor'=>$value['total'],'dconcepto1'=>mb_convert_encoding($cuenta[0]['Cuenta'], 'UTF-8'),'codigo'=>$value['cuenta'],'cuenta'=>mb_convert_encoding($cuenta[0]['Cuenta'], 'UTF-8'),'tipo_cue'=>2,'fecha'=>$value['fecha']->format('Y-m-d'));
			// array_push($datos2,$h);

			$h = array('valor'=>round($value['total'],2),'dconcepto1'=>$cuenta[0]['Cuenta'],'codigo'=>$value['cuenta'],'cuenta'=>$cuenta[0]['Cuenta'],'tipo_cue'=>2,'fecha'=>$value['fecha']->format('Y-m-d'));
			array_push($datos2,$h);

		}
		foreach ($desperdicios_haber as $key => $value) {
			$cuenta = $this->modelo->catalogo_cuentas($value['CTA_INVENTARIO']);
			// $h = array('valor'=>$value['total'],'dconcepto1'=>mb_convert_encoding($cuenta[0]['Cuenta'], 'UTF-8'),'codigo'=>$value['cuenta'],'cuenta'=>mb_convert_encoding($cuenta[0]['Cuenta'], 'UTF-8'),'tipo_cue'=>2,'fecha'=>$value['fecha']->format('Y-m-d'));
			// array_push($datos2,$h);

			$h = array('valor'=>round($value['TOTAL'],2),'dconcepto1'=>$cuenta[0]['Cuenta'],'codigo'=>$value['CTA_INVENTARIO'],'cuenta'=>$cuenta[0]['Cuenta'],'tipo_cue'=>2,'fecha'=>$value['fecha']->format('Y-m-d'));
			array_push($datos2,$h);
		}
		$resp = array('debe'=>$datos1,'haber'=>$datos2);
		// print_r($resp);die();
		return $resp;
	}
	function datos_asiento_SC($fecha)
	{
		$resp = $this->modelo->datos_asiento_SC($fecha);
		$desperdicio = $this->modelo-> desperdicios_debe($fecha);
		$datos = array(); 
		foreach ($resp as $key => $value) {
			$cuenta = $this->modelo->catalogo_cuentas($value['CONTRA_CTA']);
			//print_r($cuenta);die();
			$sub = $this->modelo->catalogo_subcuentas($value['SUBCTA']);
			// print_r($sub);die();
			// $SC = array('benericiario'=>$cuenta[0]['Cuenta'],'ruc'=>'','Codigo'=>$value['CONTRA_CTA'],'tipo'=>$cuenta[0]['TC'],'tic'=>1,'sub'=>$value['SUBCTA'],'fecha'=>$value['Fecha_Fab']->format('Y-m-d'),'fac2'=>0,'valorn'=>$value['total'],'moneda'=>1,'Trans'=>mb_convert_encoding($sub[0]['Detalle'], 'UTF-8'),'T_N'=>60,'t'=>$value['SUBCTA']);
			// array_push($datos, $SC);
				$SC = array('benericiario'=>$cuenta[0]['Cuenta'],'ruc'=>'','Codigo'=>$value['CONTRA_CTA'],'tipo'=>$cuenta[0]['TC'],'tic'=>1,'sub'=>$value['SUBCTA'],'fecha'=>$value['Fecha_Fab']->format('Y-m-d'),'fac2'=>0,'valorn'=>round($value['total'],2),'moneda'=>1,'Trans'=>$sub[0]['Detalle'],'T_N'=>60,'t'=>$value['SUBCTA']);
			array_push($datos, $SC);

		} 
		foreach ($desperdicio as $key => $value) {
			$cuenta = $this->modelo->catalogo_cuentas($_SESSION['INGRESO']['CTA_DESPERDICIO']);
			$sub = $this->modelo->catalogo_subcuentas($value['Codigo_Dr']);
			$SC = array('benericiario'=>$cuenta[0]['Cuenta'],'ruc'=>'','Codigo'=>$_SESSION['INGRESO']['CTA_DESPERDICIO'],'tipo'=>$cuenta[0]['TC'],'tic'=>1,'sub'=>$value['Codigo_Dr'],'fecha'=>$value['fecha']->format('Y-m-d'),'fac2'=>0,'valorn'=>round($value['TOTAL'],2),'moneda'=>1,'Trans'=>$sub[0]['Detalle'],'T_N'=>60,'t'=>$_SESSION['INGRESO']['CTA_DESPERDICIO']);
			array_push($datos, $SC);
		}
		 // print_r($datos);die();
		return $datos;
	}
    function encode($arr) 
    {
     $new = array(); 
    foreach($arr as $key => $value) {
      //echo is_array($value);
      if(!is_object($value))
      {
         if($value == '.')
         {

         $new[$key] = '';
         }else{

          $new[mb_convert_encoding($key, 'UTF-8')] = mb_convert_encoding($value, 'UTF-8');
          // $new[$key] = $value;
         }
        }else
        {
          //print_r($value);
          $new[$key] = $value;          
        }

     }
     return $new;
    }

	function guardar_entrega($parametro)
	{
		// print_r($parametro);die();
		
		$id =count($this->lista_entrega())+1;
		if($parametro['id']=='')
		{
			 SetAdoAddNew("Asiento_K");
		   SetAdoFields('CODIGO_INV',$parametro['codigo']);
		   SetAdoFields('PRODUCTO',$parametro['producto']);
		   SetAdoFields('UNIDAD',$parametro['uni']);
		   SetAdoFields('CANT_ES',$parametro['cant']);
		   SetAdoFields('CTA_INVENTARIO',$parametro['cta_pro']);
		   SetAdoFields('SUBCTA',$parametro['rubro']);		   
		   SetAdoFields('CodigoU',$_SESSION['INGRESO']['Id']);   
		   SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		   SetAdoFields('Consumos',number_format($parametro['bajas'],2,'.',''));
		   SetAdoFields('Procedencia',$parametro['observacion']);
		   SetAdoFields('A_No',$id);
		   SetAdoFields('Fecha_Fab',date('Y-m-d',strtotime($parametro['fecha'])));
		   SetAdoFields('Codigo_Dr',$parametro['bajas_por']);
		   SetAdoFields('TC',$parametro['TC']);
		   SetAdoFields('VALOR_TOTAL',number_format($parametro['total'],2,'.',''));
		   SetAdoFields('CANTIDAD',$parametro['cant']);
		   SetAdoFields('VALOR_UNIT',number_format($parametro['valor'],$_SESSION['INGRESO']['Dec_PVP'],'.',''));
		   SetAdoFields('DH',2);
		   SetAdoFields('CONTRA_CTA',$parametro['cc']);
	   	 SetAdoFields('CodMar',$parametro['codma']);
		   // print_r($datos);die();
		 		  $resp = SetAdoUpdate();

       //actualiza presupuesto
		  		 $presu =$this->modelo->actualizar_trans_presupuesto($parametro);
				   if(count($presu)>0){
						 $consu = $presu[0]['Total'];

						SetAdoAddNew("Trans_Presupuestos");
						SetAdoFields('Total',$consu+$parametro['cant']);

						SetAdoFieldsWhere('ID', $presu[0]['ID']);
						return SetAdoUpdateGeneric();
				   }	
		 	 return $resp;
	    }else
	    {
	    	// print_r($parametro);die();
	     SetAdoAddNew("Asiento_K");
	     SetAdoFields('CODIGO_INV',strval($parametro['codigo']));
		   SetAdoFields('PRODUCTO',$parametro['producto']);
		   SetAdoFields('UNIDAD',$parametro['uni']);
		   SetAdoFields('CANT_ES',$parametro['cant']);
		   SetAdoFields('CTA_INVENTARIO',$parametro['cta_pro']);
		   SetAdoFields('SUBCTA',$parametro['rubro']);
		   SetAdoFields('Consumos',number_format($parametro['bajas'],2,'.',''));
		   SetAdoFields('Procedencia',$parametro['observacion']);
		   SetAdoFields('Fecha_Fab',date('Y-m-d'));
		   SetAdoFields('Codigo_Dr',$parametro['bajas_por']);
		   SetAdoFields('TC',$parametro['TC']);
		   SetAdoFields('VALOR_TOTAL',number_format($parametro['total'],2,'.',''));
		   SetAdoFields('CANTIDAD',$parametro['cant']);
		   SetAdoFields('VALOR_UNIT',number_format($parametro['valor'],$_SESSION['INGRESO']['Dec_PVP'],'.',''));
		   SetAdoFields('CONTRA_CTA',$parametro['cc']);

		   SetAdoFieldsWhere('CODIGO_INV',strval($parametro['ante']));
		   SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);
		   SetAdoFieldsWhere('A_No',$id-1);
		   // print_r($datos);die();

		   SetAdoFieldsWhere('ID', $presu[0]['ID']);
			 return SetAdoUpdateGeneric();
	    }
	    // print_r($resp);die();
		
	}
	function eliminar_linea_entrega($parametro)
	{
		$codigo = $parametro['id'];
		$pos = $parametro['id_'];
		$datos = $this->modelo->buscar_asientoK($codigo,$pos);
		$parametro = array('cc'=>$datos[0]['CONTRA_CTA'],'codigo'=>$parametro['id']);
		$presu =$this->modelo->actualizar_trans_presupuesto($parametro);

		if(count($presu)>0){
			 $consu = $presu[0]['Total'];

		   $datosP[0]['campo']='Total';
		   $datosP[0]['dato']=$consu-$datos[0]['CANTIDAD'];

		   $where[0]['campo']='ID';
		   $where[0]['valor']=$presu[0]['ID'];
		   update_generico($datosP,'Trans_Presupuestos',$where);		
		   }	


		$resp = $this->modelo->eliminar($codigo,$pos);
		return $resp;
	}

	function reporte_pdf()
	{
		$titulo = 'E N T R E G A  DE  M A T E R I A L E S';
		$sizetable = 8;
		$mostrar = true;
		$resp = $this->modelo->cargar_datos_cuenta_datos();
		// print_r($resp);die();
		$tablaHTML = array();
		$tablaHTML[0]['medidas']=array(18,23,35,10,35,23,18,28);
		$tablaHTML[0]['alineado']=array('L','L','L','R','L','L','C','L');
		$tablaHTML[0]['datos']=array('FECHA','CODIGO','PRODUCTO','CANT','CENTRO DE COSTOS','RUBRO','BAJAS O DESPER','OBSERVACIONES');
		$tablaHTML[0]['estilo']='BI';
		$tablaHTML[0]['borde'] = '1';
		$pos = 1;
		foreach ($resp as $key => $value) {

			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
				$tablaHTML[$pos]['alineado']=array('L','L','L','C','L','L','R','L');
		    $tablaHTML[$pos]['datos']=array($value['FECHA']->format('Y-m-d'),$value['CODIGO'],$value['PRODUCTO'],$value['CANT'],$value['Centro de costos'],$value['rubro'],$value['bajas o desper'],$value['Observaciones']);
		    $tablaHTML[$pos]['borde'] ='T';
		    $pos+=1;

			// print_r($value);die();
			// $rubro = $this->modelo->listar_rubro($value['SUBCTA']);
			// $cc =  $this->modelo->listar_cc_info($value['CTA_INVENTARIO']);
			// // print_r($cc);print_r($rubro);die();
			// $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		 //    $tablaHTML[$pos]['alineado']=array('L','L','L','C','L','L','R','L');
		 //    $tablaHTML[$pos]['datos']=array($value['Fecha_Fab']->format('Y-m-d'),$value['CODIGO_INV'],$value['PRODUCTO'],$value['CANT_ES'],$cc[0]['text'],$rubro[0]['text'],$value['Consumos'],$value['Procedencia']);

		 // $tablaHTML[$pos]['borde'] ='T';

		 //    $pos+=1;
		}
		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,'','',$sizetable,$mostrar,25);
	}

	function reporte_EXcel()
	{
		$datos = $this->modelo->cargar_datos_cuenta_datos();
		// print_r($datos);die();
		$titulo = 'E N T R E G A   D E   M A T E R I A L E S';
		$tablaHTML[0]['medidas']=array(18,18,30,18,18,18,18,18,18);
    $tablaHTML[0]['datos']=array('FECHA','CODIGO','PRODUCTO','CANT','CENTRO DE COSTOS','RUBRO','BAJAS DESPERDICIOS','Baja por','	Observaciones');
    $tablaHTML[0]['tipo'] ='C';
    $pos = 1;

    foreach ($datos as $key => $value) {
		    $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		    $tablaHTML[$pos]['datos']=array($value['FECHA'],$value['CODIGO'],$value['PRODUCTO'],$value['CANT'],$value['Centro de costos'],$value['rubro'],$value['bajas o desper'],$value['Baja por'],$value['Observaciones']);
		    $tablaHTML[$pos]['tipo'] ='N';
		    $pos+=1;
    }

	   excel_generico($titulo,$tablaHTML);

	}

	// function datos_comprobante($codigo,$concepto=false)
	// {
	// 	$datos_Asi = $this->modelo->datos_comprobante();
	// 	$debe = 0;
	// 	$haber = 0;
		
	// 	foreach ($datos_Asi as $key => $value) {
	// 		$debe+=$value['DEBE'];
	// 		$haber+=$value['HABER'];
	// 	}
	// 	if(strval($debe) == strval($haber))
	// 	{
	// 		if($debe != 0 && $haber != 0)
	// 		{

	// 		// print_r($debe."-".$haber.'---'); die();
	// 		  $datosCom = array('ru'=>$_SESSION['INGRESO']['CodigoU'],'tip'=>'CD','fecha1'=>date('Y-m-d'),'concepto'=>'Salida de inventario '.$concepto.' '.date('Y-m-d'),'totalh'=>round($haber,2),'num_com'=>$codigo);
	// 	    }else
	// 	    {
 //   // print_r($debe."-".$haber); die();
	// 	    	$this->modelo->delete_SC_ASientos();
	// 	    	sleep(3);
	// 	    	return -2;
	// 	    }
	// 	}else
	// 	{

 //   		 print_r($debe."-".$haber); die();
	// 		$this->modelo->delete_SC_ASientos();
	// 		sleep(3);
	// 		return -1;
	// 	}

	// 	// print_r($datosCom);die();
	// 	return $datosCom;
	// }

function ingresar_trans_kardex_salidas($comprobante,$fechaC)
 {
		$datos_K = $this->modelo->lista_entrega($fechaC);

		// print_r($datos_K);die();
		// $comprobante = explode('.',$comprobante);
		// $comprobante = explode('-',trim($comprobante[1]));
		// $comprobante = $comprobante[1];
		$resp = 1;
		foreach ($datos_K as $key => $value) {
		   $datos_inv = $this->modelo->lista_hijos_id($value['CODIGO_INV']);
		   // print_r($datos_inv.'-'.$datos_inv[0]['id']);die();
		   $cant[2] = 0;
		   if(count($datos_inv)>1)
		   {
		    $cant = explode(',',$datos_inv[0]['id']);	
		   }
		   	 // print_r($datos_inv);

	     SetAdoAddNew("Trans_Kardex");		   	 
		    SetAdoFields('Codigo_Inv',$value['CODIGO_INV']); 
		    SetAdoFields('Fecha',$fechaC); 
		    SetAdoFields('Numero',$comprobante);  
		    SetAdoFields('T','N'); 
		    SetAdoFields('TP','CD'); 
		    SetAdoFields('Codigo_P',$_SESSION['INGRESO']['CodigoU']); 
		    SetAdoFields('Cta_Inv',$value['CTA_INVENTARIO']); 
		    SetAdoFields('Contra_Cta',$value['CONTRA_CTA']); 
		    SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']); 
		    SetAdoFields('Salida',$value['CANTIDAD']); 
		    SetAdoFields('Valor_Unitario',number_format($value['VALOR_UNIT'],$_SESSION['INGRESO']['Dec_PVP'],'.','')); 
		    SetAdoFields('Valor_Total',number_format($value['VALOR_TOTAL'],2,'.','')); 
		    SetAdoFields('Costo',number_format($value['VALOR_UNIT'],2,'.','')); 
		    SetAdoFields('Total',number_format($value['VALOR_TOTAL'],2,'.',''));
		    SetAdoFields('Existencia',intval($cant[2])-intval($value['CANTIDAD']));
		    SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU']);
		    SetAdoFields('Item',$_SESSION['INGRESO']['item']);
		    SetAdoFields('CodBodega','01');
		    SetAdoFields('CodigoL',$value['SUBCTA']);		    
		    SetAdoFields('CodMarca',$value['CodMar']);
		    	 // $this->modelo->insertar_trans_kardex($datos);
		    if(SetAdoUpdate()!=1)
		     {
		     		$resp = 0;
		     }
		
		}

			return $resp;

	}
function eliminar_asientos_k()
 {
 	$resp = $this->modelo->eliminar_aiseto_K();
 	return $resp;
 }

 
 function costo_existencias($codigo_inv)
 {
 	 $FechaInventario = date('Y-m-d');
 	 $CodBodega = '01';
 	 $resp = Leer_Codigo_Inv($codigo_inv,$FechaInventario,$CodBodega,$CodMarca='');
 	// $resp = $this->modelo->costo_venta($id);
 	// // print_r($resp);die();
 	return $resp;
 }

 function codmarca()
 {
 	$datos = $this->modelo->codmarca();
 	 foreach ($datos as $key => $value) {
 	 	 $res[] = array('codigo'=>$value['CodMar'],'nombre'=>$value['Marca']);
 	 }
 	 return $res;
 }
 function proyecto()
 {
 	 $datos = $this->modelo->proyectos();
 	 $res = array();
 	 foreach ($datos as $key => $value) {
 	 	 $res[] = array('codigo'=>$value['Codigo'],'nombre'=>$value['Cuenta']);
 	 }
 	 return $res;
 }

	function generar_factura($fecha)
	{
		
		$asientos_SC = $this->datos_asiento_SC($fecha);

		// print_r($asientos_SC);die();

		// print_r($asientos_SC);die();

		$parametros_debe = array();
		$parametros_haber = array();
		// $fecha = date('Y-m-d');

		// print_r($asientos_SC);die();
		foreach ($asientos_SC as $key => $value) {
			 $cuenta = $this->modelo->catalogo_cuentas($value['Codigo']);
			 $sub = $this->modelo->catalogo_subcuentas($value['sub']);
			$parametros = array(
                    'be'=>$cuenta[0]['Cuenta'],
                    'ru'=> '',
                    'co'=> $value['Codigo'],// codigo de cuenta cc
                    'tip'=>$cuenta[0]['TC'],//tipo de cuenta(CE,CD,..--) biene de catalogo subcuentas TC
                    'tic'=> 1, //debito o credito (1 o 2);
                    'sub'=> $value['sub'], //Codigo se trae catalogo subcuenta
                    'sub2'=>$cuenta[0]['Cuenta'],//nombre del beneficiario
                    'fecha_sc'=> $value['fecha'], //fecha 
                    'fac2'=>0,
                    'mes'=> 0,
                    'valorn'=> round($value['valorn'],2),//valor de sub cuenta 
                    'moneda'=> 1, /// moneda 1
                    'Trans'=>$sub[0]['Detalle'],//detalle que se trae del asiento
                    'T_N'=> '60',
                    't'=> $cuenta[0]['TC'],                        
                  );
      $this->ing_des->generar_asientos_SC($parametros);
		}
			// print_r('expresop');die();
		$datos_asientos = $this->datos_asientos($fecha);
		// print_r($datos_asientos);die();

		//asientos para el debe
		$asiento_debe = $datos_asientos['debe'];
		
		foreach ($asiento_debe as $key => $value) 
		{
			// print_r($value);die();
			$cuenta = $this->modelo->catalogo_cuentas($value['codigo']);		
				$parametros_debe = array(
				 					"va" =>round($value['valor'],2),//valor que se trae del otal sumado
                  "dconcepto1" =>$cuenta[0]['Cuenta'],
                  "codigo" => $value['codigo'], // cuenta de codigo de 
                  "cuenta" => $cuenta[0]['Cuenta'], // detalle de cuenta;
                  "efectivo_as" =>$value['fecha'], // observacion si TC de catalogo de cuenta
                  "chq_as" => 0,
                  "moneda" => 1,
                  "tipo_cue" => 1,
                  "cotizacion" => 0,
                  "con" => 0,// depende de moneda
                  "t_no" => '60',
			);
				 $this->ing_des->ingresar_asientos($parametros_debe);
		}

        // asiento para el haber
		$asiento_haber  = $datos_asientos['haber'];
		foreach ($asiento_haber as $key => $value) {
			$cuenta = $this->modelo->catalogo_cuentas($value['codigo']);			
				$parametros_haber = array(
                  "va" =>round($value['valor'],2),//valor que se trae del otal sumado
                  "dconcepto1" =>$cuenta[0]['Cuenta'],
                  "codigo" => $value['codigo'], // cuenta de codigo de 
                  "cuenta" => $cuenta[0]['Cuenta'], // detalle de cuenta;
                  "efectivo_as" =>$value['fecha'], // observacion si TC de catalogo de cuenta
                  "chq_as" => 0,
                  "moneda" => 1,
                  "tipo_cue" => 2,
                  "cotizacion" => 0,
                  "con" => 0,// depende de moneda
                  "t_no" => '60',
                );
        $this->ing_des->ingresar_asientos($parametros_haber);
		}


		// print_r('expression');die();

		// print_r($fecha.'-'.$nombre.'-'.$ruc);die();
		// $parametros = array('tip'=> 'CD','fecha'=>$fecha);
	 //    $num_comprobante = $this->modelo->numero_comprobante($parametros);

	    $num_comprobante = numero_comprobante1('Diario',true,true,$fecha);
	    $dat_comprobantes = $this->modelo->datos_comprobante();;
	    $debe = 0;
			$haber = 0;

			// print_r($dat_comprobantes);die();
		foreach ($dat_comprobantes as $key => $value) {
			$debe+=$value['DEBE'];
			$haber+=$value['HABER'];
		}



		 $ruc= $_SESSION['INGRESO']['CodigoU'];
		if(strval($debe)==strval($haber))
		{

			if($debe !=0 && $haber!=0)
			{
				 $parametro_comprobante = array(
        	        'ru'=> $ruc, //codigo del cliente que sale co el ruc del beneficiario codigo
        	        'tip'=>'CD',//tipo de cuenta contable cd, etc
        	        "fecha1"=> $fecha,// fecha actual 2020-09-21
        	        'concepto'=>'Salida de inventario con CI: '.$ruc.' el dia '.$fecha, //detalle de la transaccion realida
        	        'totalh'=> round($haber,2), //total del haber
        	        'num_com'=> '.'.date('Y', strtotime($fecha)).'-'.$num_comprobante, // codigo de comprobante de esta forma 2019-9000002
        	         "t_no" => '60',
        	        );
				 // print_r($nombre);print_r($ruc);print_r($fecha);
				 // print_r($parametro_comprobante);die();
                $resp = $this->ing_des->generar_comprobantes($parametro_comprobante);

                // print_r($resp);die();
                // $cod = explode('-',$num_comprobante);
                // die();
                if($resp==$num_comprobante)
                {
                	if($this->ingresar_trans_kardex_salidas($num_comprobante,$fecha)==1)
                	{
                		$resp = $this->modelo->eliminar_aiseto_K($fecha);
                		if($resp==1)
                		{
                			// mayorizar_inventario_sp();
                			return array('resp'=>1,'com'=>$num_comprobante);
                		}else
                		{
                			return array('resp'=>-1,'com'=>'No se pudo eliminar asiento_K');
                		}
                	}else
                	{
                		return array('resp'=>-1,'com'=>'Uno o todos No se pudo registrar en Trans_Kardex');
                	}
                }else
                {
        	        return array('resp'=>-1,'com'=>$resp);
                }

			}else
			{

			// print_r($debe.'-'.$haber.'---');die();
				// print_r($debe."-".$haber); 
				$this->modelo->delete_SC_ASientos();
				 return array('resp'=>-1,'com'=>'Los resultados son 0');

			}
		}else
		{
			$this->modelo->delete_SC_ASientos();
			return array('resp'=>-1,'com'=>'No coinciden','debe'=>$debe,'haber'=>$haber);

		}

	}

	function mayorizar($parametro)
	{
		$fecha = $parametro['fecha'];
		return mayorizar_inventario_sp($fecha);
	}

	function validar_presupuesto($parametro)
	{
		$datos = $this->modelo->lista_productos_por_centro_costo($parametro['centro'],$query=false,$parametro['codigo']);
		return $datos;
		// print_r($datos);die();
	}


}
?>