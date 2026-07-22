<?php 
require_once(dirname(__DIR__,1)."/db/db1.php");

/**
 * 
 */
class sp_generales 
{
	private $db;
	function __construct()
	{
		$this->db = new db();
	}

	function Grabar_Factura_SP($TFA)
	{
	   // 'Formacion del JSON para enviar al SP de grabado
        $JSONFactura = array
        (
	         "T"=>$TFA['T'],        	
	         "TC"=>$TFA['TC'],
	         "CodigoC"=>$TFA['CodigoC'],
	         "CodigoB"=>$TFA['CodigoB'],
	         "CodigoA"=>$TFA['CodigoA'],
	         "CodigoDr"=>$TFA['CodigoDr'],
	         "Curso"=>$TFA['Curso'],
	         "Contacto"=>$TFA['Contacto'],
	         "Forma_Pago"=>$TFA['Forma_Pago'],
	         "Cod_Ejec"=>$TFA['Cod_Ejec'],
	         "Vendedor"=>$TFA['Vendedor'],
	         "Afiliado"=>$TFA['Afiliado'],
	         "Digitador"=>$TFA['Digitador'],
	         "Nivel"=>$TFA['Nivel'],
	         "Nota"=>$TFA['Nota'],
	         "Observacion"=>$TFA['Observacion'],
	         "Definitivo"=>$TFA['Definitivo'],
	         "Codigo_T"=>$TFA['Codigo_T'],
	         "Declaracion"=>$TFA['Declaracion'],
	         "SubCta"=>$TFA['SubCta'],
	         "Serie"=>$TFA['Serie'],
	         "Serie_GR"=>$TFA['Serie_GR'],
	         "Autorizacion"=>$TFA['Autorizacion'],
	         "Autorizacion_GR"=>$TFA['Autorizacion_GR'],
	         "Fecha_Tours"=>$TFA['Fecha_Tours'],
	         "Fecha"=>$TFA['Fecha'],
	         "Fecha_V"=>$TFA['Fecha_V'],
	         "FechaGRE"=>$TFA['FechaGRE'],
	         "FechaGRI"=>$TFA['FechaGRI'],
	         "FechaGRF"=>$TFA['FechaGRF'],
	         "CiudadGRI"=>$TFA['CiudadGRI'],
	         "CiudadGRF"=>$TFA['CiudadGRF'],
	         "Comercial"=>$TFA['Comercial'],
	         "Entrega"=>$TFA['Entrega'],
	         "Pedido"=>$TFA['Pedido'],
	         "Zona"=>$TFA['Zona'],
	         "Placa_Vehiculo"=>$TFA['Placa_Vehiculo'],
	         "Lugar_Entrega"=>$TFA['Lugar_Entrega'],
	         "DireccionEstab"=>$TFA['DireccionEstab'],
	         "NombreEstab"=>$TFA['NombreEstab'],
	         "TelefonoEstab"=>$TFA['TelefonoEstab'],
	         "LogoTipoEstab"=>$TFA['LogoTipoEstab'],
	         "Tipo_Pago"=>$TFA['Tipo_Pago'],
	         "Tipo_Comp"=>$TFA['Tipo_Comp'],
	         "Cod_CxC"=>$TFA['Cod_CxC'],
	         "Orden_Compra"=>$TFA['Orden_Compra'],
	         "Recibo_No"=>$TFA['Recibo_No'],
	         "SP"=>$TFA['SP'],
	         "ME_"=>$TFA['ME_'],
	         "Com_Pag"=>$TFA['Com_Pag'],
	         "Imp_Mes"=>$TFA['Imp_Mes'],
	         "Nuevo_Doc"=>$TFA['Nuevo_Doc'],
	         "EsPorReembolso"=>$TFA['EsPorReembolso'],
	         "Gavetas"=>$TFA['Gavetas'],
	         "TDT"=>$TFA['TDT'],
	         "Cont_Salidas"=>2,
	         "Factura"=>$TFA['Factura'],
	         "DAU"=>$TFA['DAU'],
	         "FUE"=>$TFA['FUE'],
	         "Remision"=>$TFA['Remision'],
	         "Solicitud"=>$TFA['Solicitud'],
	         "Retencion"=>$TFA['Retencion'],
	         
	         "Porc_C"=>$TFA['Porc_C'],
	         "Cotizacion"=>$TFA['Cotizacion'],
	         "Porc_NC"=>$TFA['Porc_NC'],
	         "Porc_IVA"=>$TFA['Porc_IVA'],
	         
	         "Comision"=>$TFA['Comision'],
	         "Propina"=>$TFA['Propina'],
	         "Cantidad"=>$TFA['Cantidad'],
	         "Kilos"=>$TFA['Kilos'],         
	         "Efectivo"=>$TFA['Efectivo'],
	        // 'Datos por default
	         "Item"=>$_SESSION['INGRESO']['item'],
	         "Periodo"=>$_SESSION['INGRESO']['periodo'],
	         "CodigoU"=>$_SESSION['INGRESO']['CodigoU'],
	         "T_No",=>$TFA['Trans_No']
     	)

     	$JSON_InPut = json_encode($JSONFactura,true);
     	$JSON_InPut = str_replace(array('True','Verdadero','False','Falso'),array('1','1','0','0'), $JSON_InPut);

		  $ListaSucursales = G_NINGUNO;
		  $JSON_OutPut = "";
		  $parametros = array(
		    array(&$JSON_InPut, SQLSRV_PARAM_IN),
		    array(&$JSON_OutPut, SQLSRV_PARAM_OUT),
		  );

		  //print_r($parametros);die();
		  $sql = "EXEC sp_Grabar_Factura @JSON_InPut=?,@JSON_OutPut=?";
		   $respuesta = $conn->ejecutar_procesos_almacenados($sql,$parametros);
	      if($respuesta==1)
	      {
	        return $JSON_OutPut;
	      }
	      return $respuesta;   


	}


}
?>