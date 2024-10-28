<?php
include(dirname(__DIR__,2).'/modelo/contabilidad/FVentasM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */


$controlador = new FVentasC();
if(isset($_GET['DCRetIBienes']))
{
  // $parametros = $_POST['DCRetIBienes'];
  echo  json_encode($controlador->DCRetIBienes());
}
if(isset($_GET['DCRetISer']))
{
  // $parametros = $_POST['DCRetIBienes'];
  echo  json_encode($controlador->DCRetISer());
}
if(isset($_GET['DCPorcenIvaV']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->DCPorcenIva($parametros));
}
if(isset($_GET['DCPorcenIceV']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->DCPorcenIce($parametros));
}
if(isset($_GET['DCRetFuente']))
{
  // $parametros = $_POST['DCRetIBienes'];
  echo  json_encode($controlador->DCRetFuente());
}
if(isset($_GET['DCConceptoRet']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->DCConceptoRet($parametros));
}
if(isset($_GET['Cargar_DataGrid']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->Cargar_DataGrid($parametros['Trans_No']));
}

if(isset($_GET['DCTipoPago']))
{
  // $parametros = $_POST['DCRetIBienes'];
  echo  json_encode($controlador->DCTipoPago());
}
if(isset($_GET['grabacion']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->grabacion($parametros));
}
if(isset($_GET['eliminar_air']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->delete_asiento_air($parametros['Trans_No']));
}

class FVentasC
{
	
	private $modelo;
	private $pdf;
	
	function __construct()
	{
	   $this->modelo = new  FVentasM();	   
	   $this->pdf = new cabecera_pdf();
	}
	 function DCRetIBienes()
  {
    $datos = $this->modelo->DCRetIBienes();
    // print_r($datos);die();
    return $datos;
  }
  function DCRetISer()
  {
    $datos = $this->modelo->DCRetISer();
     // print_r($datos);die();
    return $datos;
  }
     function DCPorcenIva($parametros)
  {
    $fecha = $parametros['fecha'];
    $datos = $this->modelo->DCPorcenIva($fecha);
     // print_r($datos);die();
    return $datos;
  }
  function DCPorcenIce($parametros)
  {
    $fecha = $parametros['fecha'];
    $datos = $this->modelo->DCPorcenIce($fecha);
     // print_r($datos);die();
    return $datos;
  }
  function DCRetFuente()
  {
    $datos = $this->modelo->DCRetFuente();
     // print_r($datos);die();
    return $datos;
  }
  function DCConceptoRet($parametros)
  {
    $fecha = $parametros['fecha'];
    $datos = $this->modelo->DCConceptoRet($fecha);
     // print_r($datos);die();
    return $datos;
  }
     function DCTipoPago()
  {
    $datos = $this->modelo->DCTipoPago();
     // print_r($datos);die();
    return $datos;
  }
   function Cargar_DataGrid($Trans_No)
  {
    return $datos = $this->modelo->Cargar_DataGrid($Trans_No);
  }

   function grabacion($parametros)
  {
  	$T_No = 1;
  	$this->delete_asiento($T_No);
    SetAdoAddNew("Asiento_Ventas");
    SetAdoFields("IdProv", $parametros["IdProv"]);
    SetAdoFields("TipoComprobante", $parametros["TipoComprobante"]);
    SetAdoFields("FechaRegistro", $parametros["FechaRegistro"]);
    SetAdoFields("Establecimiento", $parametros["Establecimiento"]);
    SetAdoFields("PuntoEmision", $parametros["PuntoEmision"]);
    SetAdoFields("Secuencial", $parametros["Secuencial"]);
    SetAdoFields("NumeroComprobante", $parametros["NumeroComprobantes"]);
    SetAdoFields("FechaEmision", $parametros["FechaEmision"]);
    SetAdoFields("BaseImponible", round($parametros["BaseImponible"], 2, PHP_ROUND_HALF_ODD));
    SetAdoFields("IvaPresuntivo", $parametros["IvaPresuntivo"]);
    SetAdoFields("BaseImpGrav", round($parametros["BaseImpGrav"], 2, PHP_ROUND_HALF_ODD));
    SetAdoFields("PorcentajeIva", $parametros["PorcentajeIva"]);
    SetAdoFields("MontoIva", round($parametros["MontoIva"], 2, PHP_ROUND_HALF_ODD));
    SetAdoFields("BaseImpIce", round($parametros["BaseImpIce"], 2, PHP_ROUND_HALF_ODD));
    SetAdoFields("PorcentajeIce", $parametros["PorcentajeIce"]);
    SetAdoFields("MontoIce", round($parametros["MontoIce"], 2, PHP_ROUND_HALF_ODD));
    SetAdoFields("Porc_Bienes", $parametros["Porc_Bienes"]);
    SetAdoFields("MontoIvaBienes", round($parametros["MontoIvaBienes"], 2, PHP_ROUND_HALF_ODD));
    SetAdoFields("PorRetBienes", $parametros["PorRetBienes"]);
    SetAdoFields("ValorRetBienes", round($parametros["ValorRetBienes"], 2, PHP_ROUND_HALF_ODD));
    SetAdoFields("Porc_Servicios", $parametros["Porc_Servicios"]);
    SetAdoFields("MontoIvaServicios", round($parametros["MontoIvaServicios"], 2, PHP_ROUND_HALF_ODD));
    SetAdoFields("PorRetServicios", $parametros["PorRetServicios"]);
    SetAdoFields("ValorRetServicios", $parametros["ValorRetServicios"]);
    SetAdoFields("RetPresuntiva", $parametros["RetPresuntivo"]);
    SetAdoFields("Cta_Bienes", ($parametros['ChRetB'] ? $parametros["Bienes"] : '.'));
    SetAdoFields("Cta_Servicios", ($parametros['ChRetS'] ? $parametros["Servicio"] : '.'));
    SetAdoFields("Tipo_Pago", $parametros['Tipo_pago']);
    SetAdoFields("A_No", "1");
    SetAdoFields("T_No", $T_No);
    SetAdoFields("CodigoU", $_SESSION['INGRESO']['CodigoU']);
    SetAdoFields("Item", $_SESSION['INGRESO']['item']);
    return SetAdoUpdate();
  }
  function delete_asiento($T_No)
  {
  	 return $this->modelo->delete_asiento_venta($T_No);
  }
  function delete_asiento_air($T_No)
  {
  	// print_r($T_No);die();
  	return $this->modelo->delete_asiento_air($T_No);
  }
}
?>