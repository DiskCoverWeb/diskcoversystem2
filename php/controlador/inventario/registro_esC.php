<?php 
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;
include('../../modelo/inventario/registro_esM.php');
require_once('../../funciones/funciones.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
// include('../../controlador/contabilidad/incomC.php');
/**
 * 
 */
$controlador =  new registro_esC();
if(isset($_GET['iniciar_aseinto']))
{
	echo  json_encode($controlador->IniciarAsientosAdo($_POST['Trans_No']));
}
if(isset($_GET['familias']))
{
	if(!isset($_GET['q']))
	{
		$_GET['q'] =''; 
	}

	echo  json_encode($controlador->familias($_GET['q']));
}

if(isset($_GET['producto']))
{
	if(!isset($_GET['q']))
	{
		$_GET['q'] =''; 
	}
	echo  json_encode($controlador->producto($_GET['fami'],$_GET['q']));
}
if(isset($_GET['contracuenta']))
{
  if(!isset($_GET['q']))
  {
    $_GET['q'] =''; 
  }
  echo  json_encode($controlador->contracuenta($_GET['q']));
}
if(isset($_GET['ListarProveedorUsuario']))
{
  if(!isset($_GET['q']))
  {
    $_GET['q'] =''; 
  }
  echo  json_encode($controlador->ListarProveedorUsuario($_GET['cta'],$_GET['contra'],$_GET['q']));
}

if(isset($_GET['leercuenta']))
{
  echo  json_encode($controlador->LeerCta($_POST['parametros']));
}
if(isset($_GET['Trans_Kardex']))
{
  echo  json_encode($controlador->Trans_Kardex());
}
if(isset($_GET['bodega']))
{
  echo  json_encode($controlador->bodega());
}
if(isset($_GET['marca']))
{
  echo  json_encode($controlador->marca());
}
if(isset($_GET['detalle_articulos']))
{
  $parametros = $_POST['parametros'];
  echo  json_encode($controlador->producto_detalle($parametros));
}
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
if(isset($_GET['DCSustento']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->DCSustento($parametros));
}
if(isset($_GET['DCDctoModif']))
{
  // $parametros = $_POST['DCRetIBienes'];
  echo  json_encode($controlador->DCDctoModif());
}
if(isset($_GET['DCPorcenIva']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->DCPorcenIva($parametros));
}
if(isset($_GET['DCPorcenIce']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->DCPorcenIce($parametros));
}
if(isset($_GET['DCTipoPago']))
{
  // $parametros = $_POST['DCRetIBienes'];
  echo  json_encode($controlador->DCTipoPago());
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
if(isset($_GET['DCPais']))
{
  // $parametros = $_POST['DCRetIBienes'];
  echo  json_encode($controlador->DCPais());
}
if(isset($_GET['Carga_RetencionIvaBienes_Servicios']))
{
  // $parametros = $_POST['DCRetIBienes'];
  echo  json_encode($controlador->Carga_RetencionIvaBienes_Servicios());
}

if(isset($_GET['DCTipoComprobante']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->DCTipoComprobante($parametros));
}

if(isset($_GET['DCBenef_Data']))
{
   $parametros = $_POST['parametros'];
   // print_r($parametros);die();
  echo  json_encode($controlador->DCBenef_Data($parametros));
}
if(isset($_GET['grabacion']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->grabacion($parametros));
}

if(isset($_GET['ingresar_asiento']))
{
   $parametros = $_POST['parametros'];

  echo  json_encode($controlador->modal_ingresar_asiento($parametros));
}
if(isset($_GET['Insertar_DataGrid']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->Insertar_DataGrid($parametros));
}
if(isset($_GET['Cargar_DataGrid']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->Cargar_DataGrid($parametros['Trans_No']));
}

if(isset($_GET['Ult_fact_Prove']))
{
  // print_r($_POST);die();
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->Ult_fact_Prove($parametros));
}

if(isset($_GET['cancelar']))
{
   $parametros = $_POST['Trans_No'];
  echo  json_encode($controlador->cancelar($parametros));
}

if(isset($_GET['Documento_Modificado']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->Documento_Modificado($parametros));
}
if(isset($_GET['validar_autorizacion']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->validar_autorizacion($parametros));
}
if(isset($_GET['validar_numero']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->validar_numero($parametros));
}
if(isset($_GET['leercodigo']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->codigo_proveedor($parametros['ruc']));
}
if(isset($_GET['serie_ultima']))
{  
   $parametros = $_POST['parametros'];
   echo  json_encode($controlador->serie_ultima($parametros));

}
if(isset($_GET['serie_ultima_tc']))
{  
   $parametros = $_POST['parametros'];
   echo  json_encode($controlador->serie_ultima_tc($parametros));

}
if(isset($_GET['validar_factura']))
{  
   $parametros = $_POST['parametros'];
   echo  json_encode($controlador->validar_factura($parametros));

}

if(isset($_GET['eliminar_air']))
{  
   $parametros = $_POST['parametros'];
   echo  json_encode($controlador->eliminar_air($parametros));
}
if(isset($_GET['cambiar_codigo_sec']))
{  
   $parametros = $_POST['parametros'];
   echo  json_encode($controlador->cambiar_codigo_sec($parametros));
}


if(isset($_GET['grid_kardex'])){
  $parametros = $_POST['parametros'];
  echo json_encode($controlador->grid_kardex($parametros));
}

if(isset($_GET['trans_kardex_opcional'])){
  echo json_encode($controlador->trans_kardex_opcional());
}

if(isset($_GET['stock_actual_inventario'])){
  $parametros = $_POST['parametros'];
  echo json_encode($controlador->stock_actual_inventario($parametros));
}

if(isset($_GET['IngresoAsientoK'])){
  $parametros = $_POST['parametros'];
  echo json_encode($controlador->IngresoAsientoK($parametros));
}

if(isset($_GET['seleccionar_comprobante'])){
  $parametros = $_POST['parametros'];
  echo json_encode($controlador->seleccionar_comprobante($parametros));
}

if(isset($_GET['grabar_comprobante'])){
  $parametros = $_POST['parametros'];
  echo json_encode($controlador->grabar_comprobante($parametros));
}


class registro_esC
{
	private $modelo;
  private $pdf;
  // private $incom ;
	function __construct()
	{
		$this->modelo = new  registro_esM();
    $this->pdf = new cabecera_pdf();
    // $this->incom = new  incomC();
	}

  function seleccionar_comprobante($parametros){
    try{
      return $this->modelo->seleccionar_comprobante($parametros);
    }catch(Exception $e){
      return array('res' => 0, 'msg' => $e->getMessage());
    }
  }

  function grabar_comprobante($parametros){
    try{
      $parametros['DetalleComp'] = G_NINGUNO;
      $parametros['Codigo'] = Leer_Cta_Catalogo(SinEspaciosDer2($parametros['DCCtaObra']))['Codigo_Catalogo'];
      $parametros['Ln_No'] = 1;
      $parametros['Opcion_Mulp'] = False;
      $Asiento = 1;
      $parametros['CodigoCli'] = $parametros['CodigoCli'] == '' ? G_NINGUNO : $parametros['CodigoCli'];
      $Total_Factura = 0;
      $Si_No = False;
      $NumComp = '';
      $parametros['Factura_No'] = $parametros['Factura_No'] <= 0 ? 0 : $parametros['Factura_No'];
      $this->modelo->delete_asientos($parametros);
      $AdoKardex = $this->modelo->select_asientoK($parametros);
      if(count($AdoKardex) > 0){
        $Si_No = True;
        $FechaComp = $parametros['MBFechaI'];
        $FechaTexto = $parametros['MBFechaI'];
        $parametros['CLTP'] = $parametros['CLTP'] == '' ? "CD" : $parametros['CLTP'];
        switch($parametros['CLTP']){
          case "CD":
            $NumComp = ReadSetDataNum("Diario", True, True);
            break;
          case "ND":
            $NumComp = ReadSetDataNum("NotaDebito", True, True);
            break;
          case "NC":
            $NumComp = ReadSetDataNum("NotaCredito", True, True);
            break;
        } 
        $CodigoInv = $AdoKardex[0]['CODIGO_INV'];
        $Cta_Inventario = $AdoKardex[0]['CTA_INVENTARIO'];
        $Total = 0;
        $ValorDH = 0;
        foreach($AdoKardex as $row){
          if($Cta_Inventario != $row['CTA_INVENTARIO']){
            if($parametros['OpcI']){
              $temp = InsertarAsientos($Cta_Inventario, 0, $ValorDH, 0, $parametros);
              $parametros['Ln_No'] = $temp['Ln_No'];
              $parametros['Cuenta'] = $temp['Cuenta'];
              $parametros['SubCta'] = $temp['SubCta'];
            }else{
              $temp = InsertarAsientos($Cta_Inventario, 0, 0, $ValorDH, $parametros);
              $parametros['Ln_No'] = $temp['Ln_No'];
              $parametros['Cuenta'] = $temp['Cuenta'];
              $parametros['SubCta'] = $temp['SubCta'];
            }
            $parametros['Ln_No'] += 1;
            $CodigoInv = $row['CODIGO_INV'];
            $Cta_Inventario = $row['CTA_INVENTARIO'];
            $ValorDH = 0;
          }
          $ValorDH += $row['VALOR_TOTAL'];
        }
        if($parametros['OpcI']){
          $temp = InsertarAsientos($Cta_Inventario, 0, $ValorDH, 0, $parametros);
          $parametros['Ln_No'] = $temp['Ln_No'];
          $parametros['Cuenta'] = $temp['Cuenta'];
          $parametros['SubCta'] = $temp['SubCta'];
        }else{
          $temp = InsertarAsientos($Cta_Inventario, 0, 0, $ValorDH, $parametros);
          $parametros['Ln_No'] = $temp['Ln_No'];
          $parametros['Cuenta'] = $temp['Cuenta'];
          $parametros['SubCta'] = $temp['SubCta'];
        }
      }
      
      $parametros['OpcTM'] = 1;
      $parametros['OpcDH'] = 2;
      $parametros['ValorDH'] = $ValorDH;
      $parametros['NoCheque'] = G_NINGUNO;
      //Grabamos el asiento de la compra
      $AdoRet = $this->modelo->select_asiento_compras($parametros);
      if(count($AdoRet) > 0){
        $Cta = $AdoRet[0]['Cta_Servicio'];
        $parametros['DetalleComp'] = "Retencion del " . $$AdoRet[0]['Porc_Bienes'] . "%, Factura No. " .$$AdoRet[0]['Secuencial'] . ", de " .$parametros['NombreCliente'];
        $parametros['Codigo'] = Leer_Cta_Catalogo($Cta);
        $ValorDH = $$AdoRet[0]['ValorRetServicios'];
        $parametros['ValorDH'] = $ValorDH;
        $Total_RetIVA += $$AdoRet[0]['ValorRetServicios'];
        if($ValorDH > 0){
          InsertarAsiento($parametros);
        }
        $Cta = $AdoRet[0]['Cta_Bienes'];
        $parametros['DetalleComp'] = "Retencion del " . $$AdoRet[0]['Porc_Servicios'] . "%, Factura No. " .$$AdoRet[0]['Secuencial'] . ", de " .$parametros['NombreCliente'];
        $parametros['Codigo'] = Leer_Cta_Catalogo($Cta);
        $ValorDH = $$AdoRet[0]['ValorRetBienes'];
        $parametros['ValorDH'] = $ValorDH;
        if($ValorDH > 0){
          InsertarAsiento($parametros);
        }
      }
      //Grabamos el asiento de las retenciones
      $AdoRet = $this->modelo->select_asiento_air($parametros);
      if(count($AdoRet) > 0){
        foreach($AdoRet as $row){
          $Cta = $row['Cta_Retencion'];
          $parametros['DetalleComp'] = "Retencion (" . $row['CodRet'] . ") No. " .$row['SecRetencion'] . " del " . ($row['Porcentaje'] * 100) . "%, de " . $row['NombreCliente'];
          $parametros['Codigo'] = Leer_Cta_Catalogo($Cta);
          $ValorDH = $row['ValRet'];
          $parametros['ValorDH'] = $ValorDH;
          $Total_Ret += $row['ValRet'];
          if($ValorDH > 0){
            InsertarAsiento($parametros);
          }
        }
      }
      $parametros['DetalleComp'] = G_NINGUNO;
      //Contra Cuenta del kardex
      if($Si_No){
        $AdoKardex = $this->modelo->select_asiento_k_contra_cta($parametros);
        if(count($AdoKardex) > 0){
          $parametros['SubCta'] = $AdoKardex[0]['TC'];
          $Contra_Cta = $AdoKardex[0]['CONTRA_CTA'];
          $Total = 0;
          $ValorDH = 0;
          $SubCta = '';
          if($parametros['OpcE'] && ($parametros['CheqContraCta'] == 0)){
            foreach($AdoKardex as $row){
              if($Contra_Cta <> $row['CONTRA_CTA']){
                if($parametros['OpcI']){
                  $temp = InsertarAsientos($Contra_Cta, 0, 0, $ValorDH, $parametros);
                  $parametros['Ln_No'] = $temp['Ln_No'];
                  $parametros['Cuenta'] = $temp['Cuenta'];
                  $parametros['SubCta'] = $temp['SubCta'];
                }else{
                  $temp = InsertarAsientos($Contra_Cta, 0, $ValorDH, 0, $parametros);
                  $parametros['Ln_No'] = $temp['Ln_No'];
                  $parametros['Cuenta'] = $temp['Cuenta'];
                  $parametros['SubCta'] = $temp['SubCta'];
                }
                $SubCta = $row['TC'];
                $Contra_Cta = $row['CONTRA_CTA'];
                $ValorDH = 0;
              }
              $ValorDH += $row['VALOR_TOTAL'];
            }
            $parametros['SubCta'] = $SubCta;
            $parametros['ValorDH'] = $ValorDH;
            if($parametros['OpcI']){
              $temp = InsertarAsientos($Contra_Cta, 0, 0, $ValorDH, $parametros);
              $parametros['Ln_No'] = $temp['Ln_No'];
              $parametros['Cuenta'] = $temp['Cuenta'];
              $parametros['SubCta'] = $temp['SubCta'];
            }else{
              $temp = InsertarAsientos($Contra_Cta, 0, $ValorDH, 0, $parametros);
              $parametros['Ln_No'] = $temp['Ln_No'];
              $parametros['Cuenta'] = $temp['Cuenta'];
              $parametros['SubCta'] = $temp['SubCta'];
            }
          }
        }
        $TotInventario = $this->TotalInventario($parametros);
        $Total = $TotInventario['total'];
        $Total_IVA = $TotInventario['total_iva'];
        //Insertamos el IVA de la compra
        if($parametros['OpcI']){
          $temp = InsertarAsientos($_SESSION['SETEOS']['Cta_IVA_Inventario'], 0, $Total_IVA, 0, $parametros);
          $parametros['Ln_No'] = $temp['Ln_No'];
          $parametros['Cuenta'] = $temp['Cuenta'];
          $parametros['SubCta'] = $temp['SubCta'];
        }else{
          $temp = InsertarAsientos($_SESSION['SETEOS']['Cta_IVA_Inventario'], 0, 0, $Total_IVA, $parametros);
          $parametros['Ln_No'] = $temp['Ln_No'];
          $parametros['Cuenta'] = $temp['Cuenta'];
          $parametros['SubCta'] = $temp['SubCta'];
        }
        if(intval($parametros['TxtDifxDec']) > 0){
          $temp = InsertarAsientos($_SESSION['SETEOS']['Cta_Faltantes'], 0, intval($parametros['TxtDifxDec']), 0, $parametros);
          $parametros['Ln_No'] = $temp['Ln_No'];
          $parametros['Cuenta'] = $temp['Cuenta'];
          $parametros['SubCta'] = $temp['SubCta'];
        }else if(intval($parametros['TxtDifxDec']) < 0){
          $temp = InsertarAsientos($_SESSION['SETEOS']['Cta_Faltantes'], 0, 0, -1 * intval($parametros['TxtDifxDec']), $parametros);
          $parametros['Ln_No'] = $temp['Ln_No'];
          $parametros['Cuenta'] = $temp['Cuenta'];
          $parametros['SubCta'] = $temp['SubCta'];
        }
        $AdoAsientos = $this->modelo->select_asientos($parametros);
        $Debe = 0;
        $Haber = 0;
        if(count($AdoAsientos) > 0){
          foreach($AdoAsientos as $row){
            $Debe += $row['DEBE'];
            $Haber += $row['HABER'];
          }
        }
        if($parametros['CheqContraCta']){
          $Contra_Cta = SinEspaciosDer2($parametros['DCCtaObra']);
          $Codigo = Leer_Cta_Catalogo($Contra_Cta);
          $ValorDH = $Debe - $Haber;
          $ValorDH = $ValorDH < 0? $ValorDH * -1 : $ValorDH;
          switch($SubCta){
            case "C":
            case "P":
            case "G":
            case "I":
              $Total_Factura = $ValorDH;
              SetAdoAddNew("Asiento_SC");
              SetAdoFields("TM", "1");
              SetAdoFields("Factura", intval($parametros['TxtFactNo']));
              SetAdoFields("Codigo", $parametros['CodigoCliente']);
              SetAdoFields("FECHA_V", $parametros['MBVence']);
              SetAdoFields("Cta", $Contra_Cta);
              SetAdoFields("TC", $SubCta);
              SetAdoFields("T_No", $parametros['Trans_No']);
              $parametros['OpcI'] ? SetAdoFields("DH", "2") : SetAdoFields("DH", "1");
              SetAdoFields("Valor", $ValorDH);
              SetAdoFields("Periodo", $_SESSION['INGRESO']['periodo']);
              SetAdoUpdate();
              break;
          }
          if($parametros['OpcI']){
            $temp = InsertarAsientos($Contra_Cta, 0, 0, $ValorDH, $parametros);
            $parametros['Ln_No'] = $temp['Ln_No'];
            $parametros['Cuenta'] = $temp['Cuenta'];
            $parametros['SubCta'] = $temp['SubCta'];
          }else{
            $temp = InsertarAsientos($Contra_Cta, 0, $ValorDH, 0, $parametros);
            $parametros['Ln_No'] = $temp['Ln_No'];
            $parametros['Cuenta'] = $temp['Cuenta'];
            $parametros['SubCta'] = $temp['SubCta'];
          }
        }
        if($parametros['Cod_Benef'] == "X"){
          $parametros['CodigoCliente'] = G_NINGUNO;
        }
      }
      $AdoAsientos = $this->modelo->select_asientos($parametros);
      $Debe = 0;
      $Haber = 0;
      $msg = "";
      if(count($AdoAsientos) > 0){
        foreach($AdoAsientos as $row){
          $Debe += $row['DEBE'];
          $Haber += $row['HABER'];
        }
        if(($Debe - $Haber) != 0){
          $msg = "Verifique el comprobante, no cuadra por: " . ($Debe - $Haber);
          //throw new Exception("Verifique el comprobante, no cuadra por: " . ($Debe - $Haber));
        }
        $Co = datos_Co();
        $Co['T'] = G_NORMAL;
        $Co['TP'] = $parametros['CLTP'];
        $Co['Fecha'] = $FechaTexto;
        $Co['Numero'] = $NumComp;
        $Co['Concepto'] = $parametros['TextConcepto'];
        $Co['CodigoB'] = $parametros['CodigoCli'];
        $Co['Efectivo'] = 0;
        $Co['Monto_Total'] = $Total;
        $Co['Usuario'] = $_SESSION['INGRESO']['CodigoU'];
        $Co['T_No'] = $parametros['Trans_No'];
        $Co['Item'] = $_SESSION['INGRESO']['item'];
        $Co['RetSecuencial'] = False;//Por defecto es falso?
        if(strlen($parametros['TextOrden']) > 1){
          $Co['Concepto'] = $Co['Concepto'] . ", Orden No. " . $parametros['TextOrden'];
        }
        if(intval($parametros['TxtFactNo']) > 0){
          $Co['Concepto'] = $Co['Concepto'] . ", Factura No. " . $parametros['TxtFactNo'];
        }
        GrabarComprobante($Co);
        $pdf1 = ImprimirComprobantesDe(False, $Co);
        $pdf2 = Datos_Nota_Inventario($NumComp, $parametros['TextOrden'], "CD", $FechaTexto, $FechaTexto, $Total);
        mayorizar_inventario_sp();
        if($msg == ""){
          $msg = "Comprobante grabado con exito";
        }
        return array('res' => 1, 'msg' => $msg, 'pdf1' => $pdf1, 'pdf2' => $pdf2);
      }else{
        throw new Exception("No existen Datos para procesar");
      }
    }catch(Exception $e){
      return array('res' => 0, 'msg' => $e->getMessage());
    }
  }

  function IngresoAsientoK($parametros){
    try{
      SetAdoAddNew("Asiento_K");
      SetAdoFields("DH", $parametros['OpcDH']);
      SetAdoFields("CODIGO_INV", $parametros['CodigoInv']);
      //
      SetAdoFields("P_DESC", $parametros['TextDesc']);
      SetAdoFields("P_DESC1", $parametros['TextDesc1']);
      SetAdoFields("PRODUCTO", $parametros['Producto']);
      SetAdoFields("CANT_ES", $parametros['Entrada']);
      SetAdoFields("VALOR_UNIT", $parametros['ValorUnit']);
      SetAdoFields("VALOR_TOTAL", $parametros['ValorTotal']);
      SetAdoFields("IVA", $parametros['SubTotal_IVA']);
      SetAdoFields("CTA_INVENTARIO", $parametros['Cta_Inventario']);
      SetAdoFields("CONTRA_CTA", $parametros['Contra_Cta']);
      SetAdoFields("CANTIDAD", $parametros['Cantidad']);
      SetAdoFields("SALDO", $parametros['Saldo']);
      SetAdoFields("UNIDAD", $parametros['UNIDAD']);
      SetAdoFields("CodBod", $parametros['Cod_Bodega']);
      SetAdoFields("CodMar", $parametros['Cod_Marca']);
      SetAdoFields("Item", $_SESSION['INGRESO']['item']);
      SetAdoFields("CodigoU", $_SESSION['INGRESO']['CodigoU']);
      SetAdoFields("T_No", $parametros['Trans_No']);
      SetAdoFields("SUBCTA", $parametros['SubCtaGen']);
      SetAdoFields("TC", $parametros['SubCta']);
      SetAdoFields("Codigo_B", $parametros['CodigoCliente']);
      SetAdoFields("COD_BAR", $parametros['TxtCodBar']);
      SetAdoFields("ORDEN", $parametros['TextOrden']);
      SetAdoFields("Lote_No", $parametros['TxtLoteNo']);
      SetAdoFields("Fecha_Fab", $parametros['MBFechaFab']);
      SetAdoFields("Fecha_Exp", $parametros['MBFechaExp']);
      SetAdoFields("Reg_Sanitario", $parametros['TxtRegSanitario']);
      SetAdoFields("Modelo", $parametros['TxtModelo']);
      SetAdoFields("Procedencia", $parametros['TxtProcedencia']);
      SetAdoFields("Serie_No", $parametros['TxtSerieNo']);
      SetAdoUpdate();
      return $this->TotalInventario($parametros);
    }catch(Exception $e){
      return array('res' => 0, 'msg' => $e->getMessage());
    }
  }

  function TotalInventario($parametros){
    try{
      $AdoKardex = $this->modelo->grid_kardex($parametros);
      $datos = $AdoKardex['datos'];
      $Total = 0;
      $Total_IVA = 0;
      if(count($datos) > 0){
        foreach($datos as $value){
          $Total += $value['VALOR_TOTAL'];
          $Total_IVA += $value['IVA'];
        }
        return array('res' => 1, 'total' => $Total, 'total_iva' => $Total_IVA);
      }else{
        throw new Exception("No se encontraron registros");
      }
    }catch(Exception $e){
      return array('res' => 0, 'msg' => $e->getMessage());
    }
  }

  function stock_actual_inventario($parametros){
    $CantBodegas = 0;
    $Cantidad = 0;
    $ValorUnit = 0;
    $SaldoAnterior = 0;
    if(strlen($parametros['Fecha_Inv']) < 10){
      $parametros['Fecha_Inv'] = FechaSistema();
    }
    if(strlen($parametros['Codigo_Inventario']) <= 1){
      $parametros['Codigo_Inventario'] = G_NINGUNO;
    }else{
      try{
        $AdoStock = $this->modelo->stock_actual_inventario($parametros);
        if(count($AdoStock) > 0){
          $Cantidad = $AdoStock[0]['Stock'];
          $ValorUnit = $AdoStock[0]['TCosto'];
          $SaldoAnterior = $AdoStock[0]['Saldo_Inv'];
          return array('res' => 1, 'cantidad' => $Cantidad, 'valor_unit' => $ValorUnit, 'saldo_anterior' => $SaldoAnterior);
        }else{
          throw new Exception("No se encontraron registros");
        }
      }catch(Exception $e){
        return array('res' => 0, 'msg' => $e->getMessage());
      }
    }
  }

  function grid_kardex($parametros){
    try{
      $response = $this->modelo->grid_kardex($parametros);
      if(count($response['datos']) > 0){
        return array('res' => 1, 'tabla' => $response['tabla']);
      }else{
        return array('res' => 0, 'msg' => 'No se encontraron registros');
      }
    }catch(Exception $e){
      return array('res' => 0, 'msg' => $e->getMessage());
    }
  }

  function trans_kardex_opcional(){
    try{
      $datos = $this->modelo->Trans_Kardex();
      $res = [];
      foreach($datos as $value){
        $res[] = [
          'id' => $value['Numero'],
          'text' => $value['Numero']
        ];
      }
      return $res;
    }catch(Exception $e){
      return array('res' => 0, 'msg' => $e->getMessage());
    }
  }

	function familias($query)
	{
		$datos = $this->modelo->familias($query);
		return $datos;
	}
	function producto($fami,$query)
	{
	 	$opciones = ReadSetDataNum("PorCodigo", True, False); 
		$datos = $this->modelo->Producto($fami,$query,$opciones);
		return $datos;
	}
  function  codigo_proveedor($CodigoCliente)
  {
    $datos = $this->modelo->codigo_proveedor($CodigoCliente);
      return isset($datos[0]['Codigo'])?$datos[0]['Codigo']:G_NINGUNO;    

  }
  function producto_detalle($parametros)
  {
    $opciones = ReadSetDataNum("PorCodigo", True, False); 
    $CodigoInv ='';
    $porc_iva=0;
    $fami = $parametros['fami'];
    $evaluar = False;
    if($opciones==1) {
      $CodigoInv=$parametros['nom'];
    }else
    {
      $CodigoInv=$parametros['arti'];
    }

    $datos = $this->modelo->producto_detalle($fami,$CodigoInv,'','','',$opciones);
    if(count($datos)>0)
    {
       $CodigoInv = $datos[0]["Codigo_Inv"];
       $evaluar = True;
    }else
    {
      $datos = $this->modelo->producto_detalle($fami,'',$CodigoInv,'','',$opciones);
      if (count($datos)>0) 
      {
         $CodigoInv = $datos[0]["Codigo_Inv"];
         $evaluar = True;        
      }else
      {
         $datos = $this->modelo->producto_detalle($fami,'','',$CodigoInv,'',$opciones);
         //print_r($datos);die();
         if (count($datos)>0) 
         {

          $CodigoInv = $datos["Codigo_Inv"];
          $evaluar = True;
          
         }else
         {
           $evaluar = False;
         }

      }
    }
     $datos1 = $this->modelo->producto_detalle($fami,'','','',$CodigoInv,$opciones);
     $iva = $this->modelo->Tabla_Por_ICE_IVA();
     if(count($iva)>0)
     {
      $porc_iva = ($iva[0]['Porc']/100);
     }
     $datos_art = array();
     if (count($datos1)>0) {
      // print_r($datos1);die();
       $datos_art = array('si_no' =>$datos1['IVA'] ,'unidad'=>$datos1['Unidad'],'producto'=>$datos1['Producto'],'cta_inventario'=>$datos1['Cta_Inventario'],'contra_cta1'=>$datos1['Cta_Costo_Venta'],'registrosani'=>$datos1['Reg_Sanitario'],'codigo'=>$datos['Codigo_Inv']);
     }
    return $datos_art;
  }
  function contracuenta($query)
  {
    $datos = $this->modelo->contracuenta($query);
    return $datos;
  }
   function ListarProveedorUsuario($cta,$contra,$query)
  {
    $datos = $this->modelo->ListarProveedorUsuario($cta,$contra,$query);
    return $datos;
  }

  function Trans_Kardex()
  {
    $datos = $this->modelo->Trans_Kardex();
    return $datos;
  }
   function bodega()
  {
    $datos = $this->modelo->bodega();
    return $datos;
  }
   function marca()
  {
    $datos = $this->modelo->marca();
    return $datos;
  }

  function leerCta($parametros)
  {
    $CodigoCta = $parametros['cuenta'];
   $cta = $this->modelo->LeerCta($CodigoCta);
    $datos = array();
    if(count($cta)>0)
    {
      $tipo='';
      if($cta[0]['Tipo_Pago']<=0)
      {
        $tipo = '01';
      }else
      {
        $tipo = $cta[0]['Tipo_Pago'];
      }
      $datos = array('Codigo' =>$cta[0]['Codigo'] ,'Cuenta' =>$cta[0]['Cuenta'] ,'SubCta' =>$cta[0]['TC'] ,'Moneda_US' =>$cta[0]['ME'] ,'TipoCta' =>$cta[0]['DG'] ,'TipoPago' => $tipo);
    }    
  
    return $datos;
  }

	function IniciarAsientosAdo($Trans_No)
	{
		if($Trans_No <=0)
		{
			$Trans_No=1;
		}
		$this->modelo->borrar_asientos($Trans_No);

		$this->modelo->dtaAsiento_sc($Trans_No);
		$this->modelo->dtaAsiento_b($Trans_No);
		$this->modelo->dtaAsiento_air($Trans_No);
		$this->modelo->dtaAsiento_compras($Trans_No);
		$this->modelo->dtaAsiento_ventas($Trans_No);
		$this->modelo->dtaAsiento_impo($Trans_No);
		$this->modelo->dtaAsiento_expo($Trans_No);
		$this->modelo->dtaAsiento_k($Trans_No);
		$this->modelo->dtaAsiento($Trans_No);

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
   function DCSustento($parametros)
  {
     // print_r($parametros);die();

    $fecha = $parametros['fecha'];
    $datos = $this->modelo->DCSustento($fecha);
     // print_r($datos);die();
    return $datos;
  }
   function DCDctoModif()
  {
    $datos = $this->modelo->DCDctoModif();
     // print_r($datos);die();
    return $datos;
  }
   function DCPorcenIva($parametros)
  {
    $fecha = $parametros['fecha'];
    // $datos = $this->modelo->DCPorcenIva($fecha);
    $datos = Porcentajes_IVA($fecha); 
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
   function DCTipoPago()
  {
    $datos = $this->modelo->DCTipoPago();
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
  function DCPais()
  {
    $datos = $this->modelo->DCPais();
     // print_r($datos);die();
    return $datos;
  }
   function DCTipoComprobante($parametros)
  {
    // print_r($parametros);die();
    $cadena = '';
    $datos = $this->modelo->DCSustento($parametros['fecha']);
    if(count($datos)>0)
    {
      $datos = $this->modelo->DCSustento($parametros['fecha'],$parametros['DCSustento']);
      if(count($datos)>0)
      {
         $cadena = $datos[0]['Codigo_Tipo_Comprobante'];
         $cadena = str_replace(' ',',',$cadena);
      }
    }
    // print_r($cadena);die();
    $datos = $this->modelo->DCTipoComprobante($cadena,$parametros['TipoBenef']);
     // print_r($datos);die();
    return $datos;
  }
     
  function DCBenef_Data($parametros)
  {
    // print_r($parametros);die();
    $datos[0]['AgenteRetencion'] = '';
    $datos[0]['micro']=''; 
    $datos = $this->modelo->ListarProveedorUsuario($parametros['cta'],$parametros['contra'],$parametros['DCBenef']);
    $datos[0]['AgenteRetencion'] ='.';
    $datos[0]['micro'] = '.';
    if(count($datos)>0)
    {
      $tipoB = Tipo_Contribuyente_SP_MYSQL($datos[0]['CICLIENTE']);
      $datos[0]['AgenteRetencion'] = $tipoB['@Agente'];
      $datos[0]['micro'] = $tipoB['@micro'];     
      switch ($datos[0]['tipodoc']) {
         case 'C':
           $datos[0]['estado'] = 'CEDULA';
           break;
            case 'P':
           $datos[0]['estado'] = 'PASAPORTE';
           break;
            case 'R':
           $datos[0]['estado'] = 'RUC ACTIVO';
           break;
       } 
      
    // print_r($tipB);die();
    }
    if(count($datos)>0)
    {
    if($datos[0]['tipodoc']=='R')
    {

       $datos = array_merge($datos[0], array('si_no'=>FALSE));
     // array_push($datos, array('si_no'=>FALSE ));
    }else
    {      
       $datos = array_merge($datos[0], array('si_no'=>FALSE));
    }
  }else
  {
     $datos = array_merge($datos[0], array('si_no'=>FALSE));
     // array_push($datos,array('si_no' =>FALSE));
  }
  
    return $datos;
  }

  function Carga_RetencionIvaBienes_Servicios()
  {
    $datos = $this->modelo->Carga_RetencionIvaBienes_Servicios();
    // print_r($datos);die();
    return $datos;
  }
  function grabacion($parametros)
  {
    // print_r($parametros);die();
    SetAdoAddNew("Asiento_Compras"); 
    SetAdoFields("IdProv",$parametros["IdProv"]);
    SetAdoFields("DevIva",$parametros["DevIva"]);
    SetAdoFields("CodSustento",$parametros["CodSustento"]); 
    SetAdoFields("TipoComprobante",$parametros["TipoComprobante"]); 
    SetAdoFields("Establecimiento",$parametros["Establecimiento"]); 
    SetAdoFields("PuntoEmision",$parametros["PuntoEmision"]); 
    SetAdoFields("Secuencial",$parametros["Secuencial"]); //CTNumero
    SetAdoFields("Autorizacion",$parametros["Autorizacion"]); 
    SetAdoFields("FechaEmision",$parametros["FechaEmision"]); 
    SetAdoFields("FechaRegistro",$parametros["FechaRegistro"]);
    SetAdoFields("FechaCaducidad",$parametros["FechaCaducidad"]); 
    SetAdoFields("BaseNoObjIVA",number_format($parametros["BaseNoObjIVA"],2,'.',''));
    SetAdoFields("BaseImponible",number_format($parametros["BaseImponible"],2,'.',''));
    SetAdoFields("BaseImpGrav",number_format($parametros["BaseImpGrav"],2,'.',''));
    SetAdoFields("PorcentajeIva",$parametros["PorcentajeIva"]); 
    SetAdoFields("MontoIva",number_format($parametros["MontoIva"],2,'.',''));
    SetAdoFields("BaseImpIce",number_format($parametros["BaseImpIce"],2,'.',''));
    SetAdoFields("PorcentajeIce",$parametros["PorcentajeIce"]);
    SetAdoFields("MontoIce",number_format($parametros["MontoIce"],2,'.',''));
    SetAdoFields("Porc_Bienes",$parametros["Porc_Bienes"]);
    SetAdoFields("MontoIvaBienes",number_format($parametros["MontoIvaBienes"],2,'.',''));
    SetAdoFields("PorRetBienes",$parametros["PorRetBienes"]); 
    SetAdoFields("ValorRetBienes",number_format($parametros["ValorRetBienes"],2,'.',''));
    SetAdoFields("Porc_Servicios",$parametros["Porc_Servicios"]);
    SetAdoFields("MontoIvaServicios",number_format($parametros["MontoIvaServicios"],2,'.',''));
    SetAdoFields("PorRetServicios",$parametros["PorRetServicios"]);                 
    SetAdoFields("ValorRetServicios",$parametros["ValorRetServicios"]);

     if($parametros['TipoComprobante']=='5' || $parametros['TipoComprobante']==4)
     {
        SetAdoFields("DocModificado",$parametros['DocModificado']);
        SetAdoFields("FechaEmiModificado",$parametros['FechaEmiModificado']);
        SetAdoFields("EstabModificado",$parametros['EstabModificado']);
        SetAdoFields("PtoEmiModificado",$parametros['PtoEmiModificado']);
        SetAdoFields("SecModificado",$parametros['SecModificado']);
        SetAdoFields("AutModificado",$parametros['AutModificado']);

     }else
     {
        SetAdoFields("DocModificado",0);
        SetAdoFields("FechaEmiModificado",date('Y-m-d'));
        SetAdoFields("EstabModificado","000");
        SetAdoFields("PtoEmiModificado","000");
        SetAdoFields("SecModificado","0000000");
        SetAdoFields("AutModificado","0000000000");
     }
     if($parametros['ContratoPartidoPolitico']=="")
     {
         SetAdoFields("ContratoPartidoPolitico","0000000000");
     }else
     {
        SetAdoFields("ContratoPartidoPolitico",$parametros['ContratoPartidoPolitico']);
     }
     if(is_numeric($parametros["MontoTituloOneroso"]))
     {
       SetAdoFields("MontoTituloOneroso",number_format($parametros["MontoTituloOneroso"],2,'.',''));      
     }
     if(is_numeric($parametros["MontoTituloGratuito"]))
     {
       SetAdoFields("MontoTituloGratuito",number_format($parametros["MontoTituloGratuito"],2,'.',''));      
     }

      if($parametros['CFormaPago']==2)
      {
        SetAdoFields("PagoLocExt","02");
        SetAdoFields("PaisEfecPago",$parametros['DCPais']);
        SetAdoFields("AplicConvDobTrib",$parametros['OpcSiAplicaDoble']);
        SetAdoFields("PagExtSujRetNorLeg",$parametros['OpcSiFormaLegal']);
      }else
      {
        SetAdoFields("PagoLocExt","01");
        SetAdoFields("PaisEfecPago","NA");
        SetAdoFields("AplicConvDobTrib","NA");
        SetAdoFields("PagExtSujRetNorLeg","NA");
      }

        SetAdoFields("FormaPago",$parametros["FormaPago"]);
        SetAdoFields("A_No","1");
        SetAdoFields("T_No",1);
        SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
        SetAdoFields("Item",$_SESSION['INGRESO']['item']);
        SetAdoFields("Cta_Servicio",$parametros["Servicio"]);
        SetAdoFields("Cta_Bienes",$parametros["Bienes"]);
        // print_r($datos);die(); 

        if(SetAdoUpdate()==1)
        {
          $this->proceso_asientos($parametros);

          // si el tipo es distinto entonces este crea el haber autoamtico ya que no viene de incom
          if($parametros['tipo']!='')
          {
              $r = $this->generar_comprobante_tipo2($parametros);
              return $r;
          }else
          {
            return 1;
          }
        }
  }


  function generar_comprobante_tipo2($parametros)
  {
    $cta = 'Cta_Proveedores';
    $datos1 = $this->modelo->buscar_cta($cta);
    $datos2 = $this->modelo->LeerCta($datos1[0]['Codigo']);
    if(count($datos2)==0)
      {
        return -2;  // falta setear cuneta de proveedores
      }
    // print_r($datos2);die();
    if(count($datos1)==0)
      {


        SetAdoAddNew("Cta_Procesos");
        SetAdoFields('Detalle',$cta);
        SetAdoFields('Codigo','2.1');
        SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);
        SetAdoFields('Item',$_SESSION['INGRESO']['item']);
        SetAdoFields('Campo','.');
        SetAdoFields('Lst','0');
        SetAdoFields('X','.');
        SetAdoUpdate();
        $datos1[] = array('Codigo'=>'2.1','Detalle'=>$cta);
      }
      $valor = floatval($parametros['ValorRetBienes'])+floatval($parametros['ValorRetServicios'])+floatval($parametros['RetIva']);

      //inserta en asiento sc
      $parametros_sc = array(
            'be'=>$parametros['IdProv'],
            'ru'=> '',
            'co'=> $datos2[0]['Codigo'],// codigo de cuenta cc
            'tip'=>'CD',//tipo de cuenta(CE,CD,..--) biene de catalogo subcuentas TC
            'tic'=> '1', //debito o credito (1 o 2);
            'sub'=> $parametros['CI_RUC'], //Codigo se trae catalogo subcuenta o ruc del proveedor en caso de que se este ingresando
            'sub2'=>$parametros['NombreCliente'],//nombre del beneficiario
            'fecha_sc'=> $parametros['FechaEmision'], //fecha 
            'fac2'=>$parametros['Secuencial'],
            'mes'=>0,
            'valorn'=>  $valor,//valor de sub cuenta 
            'moneda'=> 1, /// moneda 1
            'Trans'=>'.',//detalle que se trae del asiento
            'T_N'=> $_SESSION['INGRESO']['modulo_'],
            't'=> 'P',                        
        );
        $resp = ingresar_asientos_SC($parametros_sc);
        $this->modelo->insertar_aseinto($datos2[0]['Codigo'],$datos2[0]['Cuenta'],0,$valor,0,$chq_as='.','.',$parametros['FechaCaducidad'],$t_no=1,$A_No=4,'P');


        return 1;
  }


  function proceso_asientos($parametros)
  {
    // print_r($parametros);die();
    $Trans_No = 1; //cambiar por variable modulo_
    $OpcTM = 1;
    $OpcDH = 1;
    $NoCheque = G_NINGUNO;
    $Total_RetIVA = 0;
    $ValorDH= 0; // vambiar por valor que se coloque en cotizacion
    $A_No = 0;
    $fecha = $parametros['FechaEmision'];
    if(Leer_Campo_Empresa('Registrar_IVA')!=0)
    {
       $Cta =buscar_cta_iva_inventario();
       $DetalleComp = "Registro del IVA en compras Doc. No. ".$parametros['Establecimiento'].$parametros['PuntoEmision']."-".$parametros['Secuencial'].", ".$parametros['NombreCliente'];
        $datosCTA = LeerCta($Cta); 
       if($ValorDH > 0)
        {          
             $this->ingresar_asientos($DetalleComp,$ValorDH,$datosCTA,$OpcTM,$OpcDH,$A_No,$parametros['opcion_mult'],$fecha);
        } 
    }
    $OpcDH = 2;
    $compras = $this->modelo->dtaAsiento_compras($Trans_No);
    if(count($compras)>0)
    {
        $A_No = 1;
      foreach ($compras as $key => $value) {
        // print_r($value);die();
         $Cta = $compras[0]["Cta_Servicio"];
         $DetalleComp = "Retencion del ".$compras[0]["Porc_Servicios"]."%, Factura No. ".$compras[0]["Secuencial"].", de ".$parametros['NombreCliente'];
          $datosCTA = LeerCta($Cta); 
         $ValorDH = $compras[0]["ValorRetServicios"];
         $Total_RetIVA = $Total_RetIVA +$compras[0]["ValorRetServicios"];
         if ($ValorDH > 0)
          {
             $this->ingresar_asientos($DetalleComp,$ValorDH,$datosCTA,$OpcTM,$OpcDH,$A_No,$parametros['opcion_mult'],$fecha);
            // InsertarAsiento AdoAsientos
          }
        // 'Porcentaje por Bienes: 0,70,100
         $Cta = $compras[0]["Cta_Bienes"];
         $DetalleComp = "Retencion del ".$compras[0]["Porc_Bienes"]."%, Factura No. ".$compras[0]["Secuencial"].", de ".$parametros['NombreCliente'];
         $datosCTA = LeerCta($Cta); 
         $ValorDH = $compras[0]["ValorRetBienes"];
         $Total_RetIVA = $Total_RetIVA + $compras[0]["ValorRetBienes"];
         if($ValorDH > 0){
          // print_r($ValorDH);die();
            $this->ingresar_asientos($DetalleComp,$ValorDH,$datosCTA,$OpcTM,$OpcDH,$A_No,$parametros['opcion_mult'],$fecha);
        }
        $A_No+=1;
      }
    }

    $air = $this->modelo->Cargar_DataGrid($Trans_No);
    $air = $air['datos'];
    $Total_Ret = 0;

    if(count($air)>0)
    {
      $Cta = $air[0]["Cta_Retencion"];
      $DetalleComp = "Retencion (".$air[0]["CodRet"].") No. ".$air[0]["SecRetencion"]." del ".round(($air[0]["Porcentaje"] * 100),1)."%, de ".$parametros['NombreCliente'];      
       $datosCTA = LeerCta($Cta); 
      $ValorDH = $air[0]["ValRet"];
      $Total_Ret = $Total_Ret + $air[0]["ValRet"];
      if($ValorDH > 0 ){
        $this->ingresar_asientos($DetalleComp,$ValorDH,$datosCTA,$OpcTM,$OpcDH,$A_No,$parametros['opcion_mult'],$fecha);
        }  

    }
  }


  function ingresar_asientos($DetalleComp,$ValorDH,$datosCTA,$OpcTM,$OpcDH,$A_No,$Opcion_Mulp,$fecha)
  {
    $InsertarCta = True;
    $Dolar =round($_SESSION['INGRESO']['Cotizacion'],2);
    $Ln_No_A = 0;
    $CodigoCli = '.';
    $NoCheque = '.';
    $Codigo = $datosCTA[0]['Codigo'];
    $Moneda_US = $datosCTA[0]['Moneda_US'];
    $Cuenta = $datosCTA[0]['Cuenta'];
    $SubCta = $datosCTA[0]['SubCta'];
    $Trans_No = 1;
    $OpcCoop = False;
    $CodigoCC = '.';
  if(empty($CodigoCli)){$CodigoCli = G_NINGUNO;}
  if(is_null($CodigoCli)){$CodigoCli = G_NINGUNO;}
  if($NoCheque == G_NINGUNO){$CodigoCli = G_NINGUNO;}
  
  $ValorDHAux = round($ValorDH, 2);
  // 'MsgBox ValorDHAux

  if($Codigo <> G_NINGUNO) {
     $Debe = 0; $Haber = 0;
     // 'And Moneda_US = False Then ValorDH = Redondear(ValorDH * Dolar,2)
     // print_r($Moneda_US);die();
     if($OpcTM == 2 Or $Moneda_US!=0){
        if ($Opcion_Mulp !='/') {
          // print_r('sss');
           $ValorDH = $ValorDH * $Dolar;
        }else{
           if($Dolar <= 0){
              $MsgBox = "No se puede Dividir para cero, cambie la Cotización.";
              $ValorDH = 0;
              // print_r($Dolar);
           }else{
              $ValorDH = intval($ValorDH / $Dolar);
              // print_r('saaa');
           }
        }
     }
     switch ($OpcDH) {
       case '1':$Debe = $ValorDH;break;
       case '2':$Haber = $ValorDH;break;       
     }

     if($ValorDH <> 0 And $Cuenta <> G_NINGUNO)
     {
        switch ($SubCta) {
          case 'C':
          case 'P':
          case 'G':
          case 'I':
          case 'CP':
          case 'PM':
          case 'CC':
            // sSQL = "SELECT * "
             
            //           & "FROM Asiento " _
            //           & "WHERE TC = '" & SubCta & "' " _
            //           & "AND CODIGO = '" & Codigo & "' " _
            //           & "AND T_No = " & Trans_No & " " _
            //           & "AND Item = '" & NumEmpresa & "' " _
            //           & "AND CodigoU = '" & CodigoUsuario & "' "
            //      Select Case OpcDH
            //        Case 1: sSQL = sSQL & "AND DEBE > 0 "
            //        Case 2: sSQL = sSQL & "AND HABER > 0 "
            //      End Select
            //      Select_AdoDB AdoRegSC, sSQL
            //      If AdoRegSC.RecordCount > 0 Then
            //         InsertarCta = False
            //         Ln_No_A = AdoRegSC.Fields("A_No")
            //      End If
            //      AdoRegSC.Close
            break;
        }
      // print_r('expression');die();
            SetAdoAddNew("Asiento");
            SetAdoFields("PARCIAL_ME",0);
            SetAdoFields("ME",0);
            SetAdoFields("CODIGO",$Codigo);
            SetAdoFields("CUENTA",$Cuenta);
            SetAdoFields("DETALLE",trim(substr($DetalleComp, 0, 60)));
             if ($OpcCoop){
                if($Moneda_US){
                   $Debe = round($Debe / $Dolar, 2);
                   $Haber = round($Haber / $Dolar, 2);
                }else{
                   $Debe = round($Debe, 2);
                   $Haber = round($Haber, 2);
                }
             }
               SetAdoFields("PARCIAL_ME",0);
                if($Moneda_US==1 Or $OpcTM == 2){
                   if (($Debe - $Haber) < 0){ $ValorDHAux = -$ValorDHAux;
                  SetAdoFields("PARCIAL_ME",$ValorDHAux);
                  SetAdoFields("ME",1);
                }
                $Debe = round($Debe, 2);
                $Haber = round($Haber, 2);
             }
            SetAdoFields("DEBE",$Debe);
            SetAdoFields("HABER",$Haber);
            SetAdoFields("EFECTIVIZAR",$fecha);
            SetAdoFields("CHEQ_DEP",$NoCheque);
            SetAdoFields("CODIGO_C",$CodigoCli);
            SetAdoFields("CODIGO_CC",$CodigoCC);
            SetAdoFields("T_No",$Trans_No);
            SetAdoFields("Item",$_SESSION['INGRESO']['item']);
            SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
            SetAdoFields("TC",$SubCta);
            if($InsertarCta)
            {
              SetAdoFields("A_No",$A_No);
              // insertar
            }else
            {
              SetAdoFields("A_No",$Ln_No_A);
              // actualizar
            }  

            // print_r($datos);die();    
            SetAdoUpdate();       
     }//abre en 
  }
  }

  function Insertar_DataGrid($parametros)
  {
    // print_r($parametros);die();
     if($parametros['BaseImp']=='')
     {
       $parametros['BaseImp'] = 0;
     }
     if($parametros['BaseImp']>0)
     {
       SetAdoAddNew("Asiento_Air");  
       SetAdoFields("CodRet" ,$parametros["CodRet"]);
       SetAdoFields("Detalle",$parametros["Detalle"]);
       SetAdoFields("BaseImp",number_format($parametros["BaseImp"],2,'.',''));
       SetAdoFields("Porcentaje",number_format( $parametros["Porcentaje"],2,'.','') / 100);
       SetAdoFields("ValRet",number_format($parametros["ValRet"],2,'.',''));
       SetAdoFields("EstabRetencion",$parametros["EstabRetencion"]);
       SetAdoFields("PtoEmiRetencion",$parametros["PtoEmiRetencion"]);
       SetAdoFields("SecRetencion",$parametros["SecRetencion"]);
       SetAdoFields("AutRetencion",$parametros["AutRetencion"]);
       SetAdoFields("FechaEmiRet",$parametros["FechaEmiRet"]);
       SetAdoFields("Cta_Retencion",$parametros["Cta_Retencion"]);
       SetAdoFields("EstabFactura",$parametros["EstabFactura"]);
       SetAdoFields("PuntoEmiFactura",$parametros["PuntoEmiFactura"]);
       SetAdoFields("Factura_No",$parametros["Factura_No"]);
       SetAdoFields("IdProv",$parametros["IdProv"]);
       SetAdoFields("A_No",$this->modelo->Maximo_De("Asiento_Air", "A_No"));    
       SetAdoFields("T_No","1");
       SetAdoFields("Tipo_Trans",$parametros['Tipo_Trans']);
       SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
       SetAdoFields("Item",$_SESSION['INGRESO']['item']);
       
       return SetAdoUpdate();
     }
     else
     {
      return -1;
     } 
     
   
  }

  function Cargar_DataGrid($Trans_No)
  {
     $datos = $this->modelo->Cargar_DataGrid($Trans_No);
     $total = 0;
     foreach ($datos['datos'] as $key => $value) {
        $total+=$value['ValRet'];
     }
     return array('tbl'=>$datos['tbl'],'total'=>$total);

  }

  function Ult_fact_Prove($parametros)
  {
    // print_r($parametros);die();
    // print_r('sss');die();
    $datos = $this->modelo->Ult_fact_Prove($parametros['proveedor']);
    // print_r($datos);die();
    if(count($datos)>0)
    {
      $fact = array('secu'=>$datos[0]['Secuencial']+1,'fech_cad'=>$datos[0]['FechaCaducidad']->format('Y-m-d'),'esta'=>$datos[0]['Establecimiento'],'punto'=>$datos[0]['PuntoEmision'],'auto'=>$datos[0]['Autorizacion']);
      return $fact;
       // TxtNumSerietres = AdoAux.Recordset.Fields("Secuencial") + 1
      // MBFechaCad = AdoAux.Recordset.Fields("FechaCaducidad")
      // TxtNumSerieUno = AdoAux.Recordset.Fields("Establecimiento")
      // TxtNumSerieDos = AdoAux.Recordset.Fields("PuntoEmision")
      // TxtNumAutor = AdoAux.Recordset.Fields("Autorizacion")
    }else
    {
       $fact = array('secu'=>'0000001','fech_cad'=>date('Y-m-d'),'esta'=>'001','punto'=>'001','auto'=>'00000001');
      return $fact;

    }
  }

  function cancelar($Trans_No)
  {
    $res = $this->modelo->cancelar($Trans_No);
    return $res;
  }

  function Documento_Modificado($parametros)
  {
    $datos = $this->modelo->Documento_Modificado($parametros['proveedor']);
    if(count($datos)>0)
    {
      return $datos[0]['Secuencial'];
    }else
    {
      return '';
    }
  }


  function grabar_asiento_compras($parametros)
  {
    // print_r($parametros);die();
    SetAdoFields("IdProv",$parametros['IdProv']);
    SetAdoFields("DevIva",$parametros['DevIva']);
    SetAdoFields("CodSustento",$parametros['CodSustento']);
    SetAdoFields("TipoComprobante",$parametros['TipoComprobante']);
    SetAdoFields("Establecimiento",$parametros['Establecimiento']);
    SetAdoFields("PuntoEmision",$parametros['PuntoEmision']);
    SetAdoFields("Secuencial",$parametros['Secuencial']);
    SetAdoFields("Autorizacion",$parametros['Autorizacion']);
    SetAdoFields("FechaEmision",$parametros['FechaEmision']);
    SetAdoFields("FechaRegistro",$parametros['FechaRegistro']);
    SetAdoFields("FechaCaducidad",$parametros['FechaCaducidad']);
    SetAdoFields("BaseNoObjIVA",$parametros['BaseNoObjIVA']);
    SetAdoFields("BaseImponible",$parametros['BaseImponible']);
    SetAdoFields("BaseImpGrav",$parametros['BaseImpGrav']);
    SetAdoFields("PorcentajeIva",$parametros['PorcentajeIva']);
    SetAdoFields("MontoIva",$parametros['MontoIva']);
    SetAdoFields("BaseImpIce",$parametros['BaseImpIce']);
    SetAdoFields("PorcentajeIce",$parametros['PorcentajeIce']);
    SetAdoFields("MontoIce",$parametros['MontoIce']);
    SetAdoFields("Porc_Bienes",$parametros['Porc_Bienes']);
    SetAdoFields("MontoIvaBienes",$parametros['MontoIvaBienes']);
    SetAdoFields("PorRetBienes",$parametros['PorRetBienes']);
    SetAdoFields("ValorRetBienes",$parametros['ValorRetBienes']);
    SetAdoFields("Porc_Servicios",$parametros['Porc_Servicios']);
    SetAdoFields("MontoIvaServicios",$parametros['MontoIvaServicios']);
    SetAdoFields("PorRetServicios",$parametros['PorRetServicios']);
    SetAdoFields("ValorRetServicios",$parametros['ValorRetServicios']);
    SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU']);
    SetAdoFields('Item',$_SESSION['INGRESO']['item']);
    return SetAdoUpdate();
     
  }
   function modal_ingresar_asiento($parametros)
     {
      print_r($parametros);die();
        $cuenta = $this->modelo->cuentas_todos($parametros['cta']); 
        $parametros_asiento = array(
        "va" => round($parametros['val'],2),
        "dconcepto1" => '.',
        "codigo" => $parametros['cta'],
        "cuenta" => $cuenta[0]['Cuenta'],
        "efectivo_as" => date('Y-m-d'),
        "chq_as" =>0,
        "moneda" => $parametros['tm'],
        "tipo_cue" => $parametros['dh'],
        "cotizacion" => 0,
        "con" => 0,
        "t_no" => '1',
        "tc"=>$cuenta[0]['TC'],                 
      );

        // print_r($parametros_asiento);die();

         $resp = ingresar_asientos($parametros_asiento);
         if($resp==1)
         {
          return 1;
         }else
         {
          return -1;
         }
     }

     function validar_autorizacion($parametros)
     {
      $n = strlen($parametros['auto']);
      if($n<10)
      {
        $ce = str_repeat('0',10-$n);
        $parametros['auto'] = $ce.$parametros['auto'];

      }
      $r =  ReadSetDataNum("RE_SERIE_".$parametros['serie'], True, False);
        if($parametros['numero'] !=  ReadSetDataNum("RE_SERIE_".$parametros['serie'], True, False))
        {
          $titulo = "SECUENCIAL DE RETENCION";
          $mensajes = "Número de Retención: ".$parametros['serie']."-".$parametros['numero']." no esta en orden secuencial. QUIERE PROCESARLA?";
          // If BoxMensaje = vbYes Then Co.RetSecuencial = False
          return array('titulo'=>$titulo,'mensaje'=>$mensajes);
      }
      return 1;
     }
     function validar_numero($parametros)
     {
      $RetNueva = True;
      $datos = $this->modelo->existe_numero($parametros['uno'],$parametros['dos'],$parametros['ret']);
      if(count($datos)>0)
      {
        return 1;
      }else
      {
        return -1;
      }
     }

     function serie_ultima_tc($parametros)
     {
      // print_r($parametros);die();
        $serie1 = substr($parametros['serie'],0,3);
        $serie2 = substr($parametros['serie'],3,6);
        $numero = 1;
        if($parametros['serie']!='')
        {
          switch ($parametros['TC']) {
            case '3':
              $numero =ReadSetDataNum("LC_SERIE_".$parametros['serie'],True,false);
              break;
            
            default:
              $numero = G_NINGUNO;
              break;
          }
        }
        $datos_auto = $this->modelo->numero_autorizacion_tc($serie1,$serie2,$parametros['fechaReg'],$parametros['TC']);

        // print_r($datos_auto);die();
        if(!empty($datos_auto)){
          if(strlen($datos_auto[0]['AutRetencion'])>=13)
          {
            $autori = $_SESSION['INGRESO']['RUC'];

          }else
          {
            $autori = $datos_auto[0]['AutRetencion'];
          }
        }else
        {
          $autori=1;
        }

        $datos = array('numero'=>$numero,'autorizacion'=>$autori);
        return $datos;
     }

     function serie_ultima($parametros)
     {
      // print_r($parametros);die();
        $serie1 = substr($parametros['serie'],0,3);
        $serie2 = substr($parametros['serie'],3,6);
        $numero = 1;
        if($parametros['serie']!='')
        {
          // switch ($parametros['TC']) {
          //   case '3':
          //     $numero =ReadSetDataNum("LC_SERIE_".$parametros['serie'],True,false);
          //     break;
            
          //   default:
              $numero =ReadSetDataNum("RE_SERIE_".$parametros['serie'],True,false);
              // break;
          // }
        }
        $datos_auto = $this->modelo->numero_autorizacion($serie1,$serie2,$parametros['fechaReg']);

        // print_r($datos_auto);die();
        if(!empty($datos_auto)){
          if(strlen($datos_auto[0]['AutRetencion'])>=13)
          {
            $autori = $_SESSION['INGRESO']['RUC'];

          }else
          {
            $autori = $datos_auto[0]['AutRetencion'];
          }
        }else
        {
          $autori=1;
        }

        $datos = array('numero'=>$numero,'autorizacion'=>$autori);
        return $datos;
     }

     function validar_factura($parametros)
     {
        $uno = substr($parametros['serie'],0,3);
        $dos = substr($parametros['serie'],3,6); 
        $datos = $this->modelo->validar_factura($parametros['IdProv'],$uno,$dos,$parametros['numero'],$parametros['auto']);
        if(count($datos)>0)
        {
          return -1;
        }else
        {
          return 1;
        }

     }

     function eliminar_air($parametros)
     {
       return $this->modelo->eliminar_air($parametros['a_no'],$parametros['cod']);
     }

     function cambiar_codigo_sec($parametros)
     {

        $num = $parametros['numero'];
        $SQLs = 'RE_SERIE_'.$parametros['serie'];
        $datos = $this->modelo->existe_numero(substr($parametros['serie'],0,3),substr($parametros['serie'],3,6),$num);
        if(count($datos)==0)
        {
          return $this->modelo->cambiar_codigo_sec($num,$SQLs);
        }
     }
}
?>