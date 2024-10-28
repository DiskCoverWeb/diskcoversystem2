<?php
require_once(dirname(__DIR__,2)."/modelo/facturacion/divisasM.php");
require_once(dirname(__DIR__,2)."/comprobantes/SRI/autorizar_sri.php");
require_once(dirname(__DIR__,3)."/lib/excel/plantilla.php");
require_once(dirname(__DIR__,3).'/lib/phpmailer/enviar_emails.php');
if(!class_exists('cabecera_pdf'))
{
  require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
}

$controlador = new divisasC();

if(isset($_GET['guardarLineas']))
{
   echo json_encode($controlador->guardarLineas());
}

if(isset($_GET['guardarFactura']))
{
  echo json_encode($controlador->guardarFactura());
}

if(isset($_GET['ticketPDF']))
{
  $controlador->ticketPDF();
}
if(isset($_GET['ticketPDF_fac']))
{
  $controlador->ticketPDF_fac();
}

// compra y venta de divisas
if(isset($_GET['productos']))
{
  $codigoLinea = $_POST['DCLinea'];
  $TC = explode(" ", $codigoLinea);
  $datos = $controlador->getProductos($TC[0]);
  echo json_encode($datos);
}

//liquidacion de compras
if(isset($_GET['productos2']))
{
  
  $query = '';
  $TC = 'LC';
  if(isset($_GET['q']))
  {
    $query = $_GET['q'];
  }
  if(isset($_GET['TC']))
  {
    $TC = $_GET['TC'];
  } 
  $Grupo_Inv = G_NINGUNO;
  echo json_encode($controlador->getProductos2($Grupo_Inv,$TC,$query));
}


if(isset($_GET['cliente']))
{
  $query = 'consumidor final';
  if(isset($_GET['q']) && $_GET['q'] != ''  )
  {
    $query = $_GET['q']; 
  }
  echo json_encode($controlador->getClientes($query));
}


if(isset($_GET['catalogoLineas']))
{
  $fecha = $_POST['fecha'];
  $datos = $controlador->getCatalogoLineas($fecha);
  echo json_encode($datos);
}
if(isset($_GET['catalogoLineas_lc']))
{
  $fecha = $_POST['fecha'];
  $datos = $controlador->getCatalogoLineas_lc($fecha);
  echo json_encode($datos);
}

if(isset($_GET['guardar_datoC']))
{
  $parametros = $_POST['parametros'];
  $datos = $controlador->guardar_datosC($parametros);
  echo json_encode($datos);
}

if(isset($_GET['limpiar_grid']))
{
  // print_r('e');die();
  $datos = $controlador->limpiar_grid();
  echo json_encode($datos);
}
if(isset($_GET['cargarLineas']))
{
  // print_r('e');die();
  $datos = $controlador->cargaLineas();
  echo json_encode($datos);
}
if(isset($_GET['Eliminar']))
{
  // print_r('e');die();
  $datos = $controlador->Eliminar($_POST['cod']);
  echo json_encode($datos);
}

if(isset($_GET['buscar_facturas']))
{  
  $parametros = $_POST['parametros'];
  $datos = $controlador->buscar_facturas($parametros);
  echo json_encode($datos);
}

if(isset($_GET['autorizar_f']))
{
  $controlador->autorizar_fun();
}



class divisasC
{
	private $modelo;
  private $pdf;
  private $email;
  private $autorizar_sri;

	public function __construct(){
    $this->modelo = new divisasM();
    $this->autorizar_sri = new autorizacion_sri();
    $this->pdf = new cabecera_pdf();
    $this->email = new enviar_emails(); 
  }

  public function getCatalogoLineas($fecha){
    $datos = $this->modelo->getCatalogoLineas($fecha);
    $catalogo = [];
    foreach ($datos as $value) {
      $catalogo[] = array('codigo'=>$value['Fact']." ".$value['Serie']." ".$value['Autorizacion']." ".$value['CxC']." " ,'nombre'=>$value['Concepto']);
    }
    return $catalogo;
  }

    public function getCatalogoLineas_lc($fecha){
    $datos = $this->modelo->getCatalogoLineas_lc($fecha);
    $catalogo = [];
    foreach ($datos as $value) {
      $catalogo[] = array('codigo'=>$value['Fact']." ".$value['Serie']." ".$value['Autorizacion']." ".$value['CxC']." " ,'nombre'=>$value['Concepto']);
    }
    return $catalogo;
  }

  public function getProductos($TC){
    $datos = $this->modelo->getProductos($TC);
    $productos = [];
    foreach ($datos as $value) {
      // $productos[] = array('codigo'=>$value['Codigo_Inv']."/".utf8_encode($value['Producto'])."/".$value['PVP']."/".$value['Div'] ,'nombre'=> utf8_encode($value['Producto'])); 
      $productos[] = array('codigo'=>$value['Codigo_Inv']."/".$value['Producto']."/".$value['PVP']."/".$value['Div'] ,'nombre'=> $value['Producto']);
    }
    return $productos;
  }

  public function getProductos2($Grupo_Inv,$TipoFactura,$query)
  {
    $datos = $this->modelo->getProductos_normales($Grupo_Inv,$TipoFactura,$query);
    $res = array();
    foreach ($datos as $key => $value) {
      $res[] = array('id'=>$value['Codigo_Inv'],'text'=>$value['Producto']);
    }
    return $res;
    // print_r($datos);die();
  }

  public function getClientes($query){
    $datos = $this->modelo->getClientes($query);
    $clientes = [];
    foreach ($datos as $key => $value) {
      $clientes[] = array('id'=>$value['Cliente'],'text'=>$value['Cliente'].': '.$value['CI_RUC'],'data'=>array('email'=> $value['Email'],'direccion' =>$value['Direccion'], 'telefono' =>$value['Telefono'], 'ci_ruc' =>$value['CI_RUC'], 'codigo' =>$value['Codigo'],'cliente' => $value['Cliente'], 'grupo'=>$value['Grupo'], 'tdCliente' =>$value['TD']));
    }
    return $clientes;
  }

 
  public function guardarLineas(){
    $num = count($this->modelo->getAsiento());
    $datos = array();
    $producto = $_POST['datos'];
    $precio_nuevo = $_POST['datos']['Precio'];
    if($producto['Cantidad']>$producto['Total'])
    {
     $precio_nuevo = number_format(($producto['Total'] / $producto['Cantidad']),7,'.','');     
    }

    $totalNuevo = number_format($producto['Total'],2,'.','');

    SetAdoAddNew("Asiento_F");
    SetAdoFields("CODIGO", $producto['Codigo']);
    SetAdoFields("CODIGO_L", $producto['CodigoL']);
    SetAdoFields("PRODUCTO", $producto['Producto']);
    SetAdoFields("CANT", number_format($producto['Cantidad'], 2, '.', ''));
    SetAdoFields("PRECIO", $precio_nuevo);
    SetAdoFields("Total_Desc", $producto['Total_Desc']);
    SetAdoFields("Total_Desc2", $producto['Total_Desc2']);
    SetAdoFields("TOTAL", $totalNuevo);
    SetAdoFields("Total_IVA", number_format($producto['Total'] * ($producto['Iva'] / 100), 2, '.', ''));
    SetAdoFields("Cta", 'Cuenta');
    SetAdoFields("Item", $_SESSION['INGRESO']['item']);
    SetAdoFields("Codigo_Cliente", $_POST['codigoCliente']);
    SetAdoFields("HABIT", G_PENDIENTE);
    SetAdoFields("Mes", $producto['MiMes']);
    SetAdoFields("TICKET", $producto['Periodo']);
    SetAdoFields("CodigoU", $_SESSION['INGRESO']['CodigoU']);
    SetAdoFields("A_No", $num+1);
    SetAdoFields("PRECIO2", $producto['Precio']);
    return SetAdoUpdate();
  }

  public function guardarFactura(){
    $TextRepresentante = $_POST['TextRepresentante'];
    $TxtDireccion = $_POST['TxtDireccion'];
    $TxtTelefono = $_POST['TxtTelefono'];
    $TextFacturaNo = $_POST['TextFacturaNo'];
    $TxtGrupo = $_POST['TxtGrupo'];
    $TextCI = $_POST['TextCI'];
    $TD_Rep = $_POST['TD_Rep'];
    $TxtEmail = $_POST['TxtEmail'];
    $TxtDirS = $_POST['TxtDirS'];
    $codigoCliente = $_POST['codigoCliente'];
    $CtaPagoMax = "";
    $ValPagoMax = "";
    TextoValido($TextRepresentante,"" , true);
    TextoValido($TxtDireccion, "" , True);
    TextoValido($TxtTelefono, "" , True);
    TextoValido($TxtEmail);
    //cuentas
    $TextCheque = $_POST['TextCheque'];
    $DCBanco = $_POST['DCBanco'];
    $TxtEfectivo = $_POST['TxtEfectivo'];
    $TxtNC = $_POST['TxtNC'];
    $DCNC = $_POST['DCNC'];
    $Cta_CajaG = 1;
    $Titulo = "Formulario de Grabacion";
    $Mensajes = "Esta Seguro que desea grabar: La Factura No. ".$TextFacturaNo;
    $ValPagoMax = 0;
    $CtaPagoMax = "1";
    if ($ValPagoMax <= intval($TextCheque)) {
      $ValPagoMax = intval($TextCheque);
      $CtaPagoMax = SinEspaciosIzq($DCBanco);
    }
    if ($ValPagoMax <= intval($TxtEfectivo)) {
      $ValPagoMax = intval($TxtEfectivo);
      $CtaPagoMax = $Cta_CajaG;
    }
    if ($ValPagoMax <= intval($TxtNC)) {
      $ValPagoMax = intval($TxtNC);
      $CtaPagoMax = SinEspaciosIzq($DCNC);
    }
    $Cta_Aux = Leer_Cta_Catalogo($CtaPagoMax);
    if ($Cta_Aux) {
      $Tipo_Pago = $Cta_Aux['TipoPago'];
    }
    $TC = SinEspaciosIzq($_POST['DCLinea']);
    $serie = SinEspaciosDer($_POST['DCLinea']);
    //traer secuencial de catalogo lineas
    return $this->Grabar_FA($_POST,$TextFacturaNo);
  }


  public function Grabar_FA($FA,$TextFacturaNo){

    // print_r($FA);die();

    $FA = array_merge($FA,variables_tipo_factura());

    // print_r($FA);die();
    $codigoCliente = $FA['codigoCliente'];
    $FA['CodigoC'] = $FA['codigoCliente'];

    $resultado = explode(" ", $FA['DCLinea']);
    $FA['Autorizacion'] = $resultado[2];
    $FA['Cta_CxP'] = $resultado[3];
    //Procedemos a grabar la factura
    $datos = $this->modelo->getAsiento();
    // print_r($datos);die();
    foreach ($datos as $value) {
      $TFA = Calculos_Totales_Factura($codigoCliente);
      $FA['Tipo_PRN'] = "FM";
      $FA['FacturaNo'] = $TextFacturaNo;
      $FA['Nuevo_Doc'] = true;
      $FA['Factura'] = intval($TextFacturaNo);
      $FA['TC'] = SinEspaciosIzq($FA['DCLinea']);
      $FA['Serie'] = SinEspaciosDer($FA['DCLinea']);
      if (Existe_Factura($FA)) {
        $resultado = 5;
        $lc = ReadSetDataNum($FA['TC']."_SERIE_".$FA['Serie'], True, True);
        return $resultado;
      }
      $SaldoPendiente = 0;
      $DiarioCaja = ReadSetDataNum("Recibo_No", True, True);
      $lc = ReadSetDataNum($FA['TC']."_SERIE_".$FA['Serie'], True, True);
      
      $SubTotal_NC = $FA['DCNC'];
      $Total_Bancos = $FA['TextCheque'];
      $TotalCajaMN = $FA['Total'] ;
      $TextoFormaPago = "CONTADO";
      $Total_Abonos = $TotalCajaMN;
      $FA['Total_Abonos'] = $Total_Abonos;
      $FA['T'] = 'C';
      $FA['Saldo_MN'] = $FA['Total'] - $Total_Abonos;
      $FA['Porc_IVA'] = $value['Total_IVA'];
      $FA['Cliente'] = $FA['TextRepresentante'];
      $FA['me'] = $value['HABIT'];
      $TA['me'] = $value['HABIT'];
      $TA['Recibi_de'] = $FA['Cliente'];
      $Cta = SinEspaciosIzq($FA['DCBanco']);
      $Cta1 = SinEspaciosIzq($FA['DCNC']);
      // print_r($FA);die();

      Grabar_Factura1($FA);
      
      //Seteos de Abonos Generales para todos los tipos de abonos
      $TA['T'] = $FA['T'];
      $TA['TP'] = $FA['TC'];
      $TA['Serie'] = $FA['Serie'];
      $TA['Autorizacion'] = $FA['Autorizacion'];
      $TA['CodigoC'] = $FA['codigoCliente']; //codigo cliente
      $TA['Factura'] = $FA['Factura'];
      $TA['Fecha'] = $FA['Fecha'];
      $TA['Cta_CxP'] = $FA['Cta_CxP'];
      $TA['email'] = $FA['TxtEmail'];
      $TA['Comprobante'] = "";
      $TA['Codigo_Inv'] = "";
     
      //Abono de Factura Banco o Tarjetas
      $TA['Cta'] = $Cta;
      $TA['Banco'] = strtoupper($FA['TxtGrupo'])." - ".$FA['DCBanco'];
      $TA['Cheque'] = '';
      $TA['Abono'] = $Total_Bancos;
      // Grabar_Abonos($TA);

      $Cta_CajaG = 1;
      //Abono de Factura
      $TA['Cta'] = $Cta_CajaG;
      $TA['Banco'] = "EFECTIVO MN";
      $TA['Cheque'] = strtoupper($FA['TextCheque']);
      $TA['Abono'] = $TotalCajaMN;
      $TA['Comprobante'] = "";
      $TA['Codigo_Inv'] = "";
      // Grabar_Abonos($TA);

      //Forma del Abono SubTotal NC
      if ($SubTotal_NC > 0) {
        $SubTotal_NC = $SubTotal_NC - $TFA['Total_IVA'];
        $TA['Cta'] = $Cta1;
        $TA['Banco'] = "NOTA DE CREDITO";
        $TA['Cheque'] = "VENTAS";
        $TA['Abono'] = $SubTotal_NC;
        // Grabar_Abonos($TA);
      }
     
      //Forma del Abono IVA NC
      if ($TFA['Total_IVA'] > 0) {
        $TA['Cta'] = $Cta_IVA;
        $TA['Banco'] = "NOTA DE CREDITO";
        $TA['Cheque'] = "I.V.A.";
        $TA['Abono'] = $TFA['Total_IVA'];
        // Grabar_Abonos($TA);
      }
     
      $TextInteres = 0;
      //Abono de Factura
      $TA['T'] = G_NORMAL;
      $TA['TP'] = "TJ";
      $TACta = $Cta;
      $TA['Cta_CxP'] = $FA['Cta_CxP'];
      $TA['Banco'] = "INTERES POR TARJETA";
      $TA['Cheque'] =  $FA['TextCheque'];
      $TA['Abono'] = intval($TextInteres);
      $TA['Recibi_de'] = $FA['Cliente'];
      // Grabar_Abonos($TA);
       
      $TA['T'] = $FA['T'];
      $TA['TP'] = $FA['TC'];
      $TA['Serie'] = $FA['Serie'];
      $TA['Factura'] = $FA['Factura'];
      $TA['Autorizacion'] = $FA['Autorizacion'];
      $TA['CodigoC'] = $FA['codigoCliente'];

      $TxtEfectivo = "0.00";
      if (strlen($FA['Autorizacion']) >= 13) {
        $FA['Desde'] = $FA['Factura'];
        $FA['Hasta'] = $FA['Factura'];
      }
      $FA['serie'] = $FA['Serie'];
      $FA['num_fac'] = $FA['Factura'];
      $FA['tc'] = $FA['TC'];
      $FA['cod_doc'] = '01';
      
    }

    // print_r($FA);


    if (strlen($FA['Autorizacion']) == 13) {    
// print_r('expression');die();
       $resultado = $this->autorizar_sri->Autorizar_factura_o_liquidacion($FA);
    }else{
      $resultado = 4;
    }
    return $resultado;
    
  }

  public function ticketPDF(){

    date_default_timezone_set('America/Guayaquil');
    $ci = $_GET['CI'];
    $serie = $_GET['serie'];
    $fac = $_GET['fac'];
    $TC = $_GET['TC'];
    $efectivo = $_GET['efectivo'];
    $saldo = $_GET['saldo'];
    $parametros = array('tipo'=>'FA','ci'=>$ci,'serie'=>$serie,'factura'=>$fac,'TC' => $TC,'efectivo' => $efectivo,
      'saldo' => $saldo);
    $datos_pre  ="";
    $datos_pre =  $this->modelo->datos_factura($parametros);
    // print_r($datos_pre);die();
    $datos_empre =  $this->modelo->datos_empresa();
    if(isset($datos_pre['lineas'][0]['Factura'])){
    $datos_pre['lineas'][0]['Factura'] = generaCeros($datos_pre['lineas'][0]['Factura'],9);
    }
$cabe = '<pre>
Transaccion ('.$TC.'): No. '.$datos_pre['lineas'][0]['Serie'].'-'.$datos_pre['lineas'][0]['Factura'].'
Autorizacion:
'.$datos_pre['lineas'][0]['Autorizacion'].'
Fecha: '.date('Y-m-d').' - Hora: </b>'.date('H:m:s').'
Cliente: <br>'.$datos_pre['cliente']['Cliente'].'
R.U.C/C.I.: '.$datos_pre['cliente']['CI_RUC'].'
Cajero: '.$_SESSION['INGRESO']['Nombre'].'
Telefono: '.$datos_pre['cliente']['Telefono'].'
Direccion: '.$datos_pre['cliente']['Direccion'].'
---------------------------------------
PRODUCTO      /    Cant x PVP   / TOTAL</pre>' ;
$lineas = "<pre>
---------------------------------------
";

  foreach ($datos_pre['lineas'] as $key => $value) {
    // print_r($pro);die();
    if($value['Total_IVA']==0)
    {
      $lineas.= $value['Producto'];
    }else
    {
      $lineas.= $value['Producto'];
    }
    if($value['Precio2']==0){$value['Precio2'] = $value['Precio'];}
   
    $lineas.='     '.number_format($value['Cantidad'],2,'.','').' X '.number_format($value['Precio2'],2,'.','').'      '.number_format($value['Total'],2,'.','');
   
 }
   $lineas.='</pre>';
   $totales = "<pre>
                   SUBTOTAL:  ".number_format($datos_pre['tota'],2,'.','') ."
                  I.V.A 12%:   ".number_format($datos_pre['iva'],2,'.','') ."
              TOTAL FACTURA:  ".number_format($datos_pre['tota'],2,'.','')."
                   EFECTIVO:  ".number_format($efectivo,2,'.','')."</td>
                     CAMBIO:   ".number_format($saldo,2,'.','')."</td>
----------------------------------------</pre>";
$datos_extra = "<pre>
Email: ".$datos_pre['cliente']['Email']."
         Fue un placer atenderle 
          Gracias por su compra




<br>
<br>
</pre>";

    $html =  $cabe.$lineas.$totales.$datos_extra;
    if(isset($_GET['pdf']) && $_GET['pdf']=='no')
    {
      echo $html;
    }else
    {

      $this->pdf->formatoPDFMatricial($html,$parametros,$datos_pre,$datos_empre,1);
      $archivos =array($datos_pre['lineas'][0]['Autorizacion'].'.pdf');
      $this->email->enviar_email($archivos,$datos_pre['cliente']['Email'],'Comprobante: '.$datos_pre['lineas'][0]['Autorizacion'],$titulo_correo='COMPROBANTE MIL CAMBIOS',$correo_apoyo='ejfc19omoshiroi@gmail.com',$nombre='mil cambios',$HTML=false);

     $this->pdf->formatoPDFMatricial($html,$parametros,$datos_pre,$datos_empre);
    }
    //crea pdf para enviar por corre
    // $this->pdf->formatoPDFMatricial($html,$parametros,$datos_pre,$datos_empre,true);
    // $archivos =array($datos_pre['lineas'][0]['Autorizacion'].'pdf');
    // $this->email->enviar_email($archivos,$datos_pre['cliente']['Email'],'Comprobante',$titulo_correo='aaaa',$correo_apooyo='ejfc19omoshiroi@gmail.com',$nombre='dasdas',$HTML=false);
  }

  public function ticketPDF_fac(){

    date_default_timezone_set('America/Guayaquil');
    $ci = $_GET['CI'];
    $serie = $_GET['serie'];
    $fac = $_GET['fac'];
    $TC = $_GET['TC'];
    $efectivo = $_GET['efectivo'];
    $saldo = $_GET['saldo'];
    $totalFA = $_GET['totalFA'];
    $parametros = array('tipo'=>'FA','ci'=>$ci,'serie'=>$serie,'factura'=>$fac,'TC' => $TC,'efectivo' => $efectivo,
      'saldo' => $saldo);
    $datos_pre  ="";
    $datos_pre =  $this->modelo->datos_factura($parametros);
    $datos_empre =  $this->modelo->datos_empresa();
    $datos_pre['lineas'][0]['Factura'] = generaCeros($datos_pre['lineas'][0]['Factura'],9);

$cabe = '<pre>
Transaccion ('.$TC.'): No. '.$datos_pre['lineas'][0]['Serie'].'-'.$datos_pre['lineas'][0]['Factura'].'
Autorizacion:
'.$datos_pre['lineas'][0]['Autorizacion'].'
Fecha: '.date('Y-m-d').' - Hora: </b>'.date('H:m:s').'
Cliente: <br>'.$datos_pre['cliente']['Cliente'].'
R.U.C/C.I.: '.$datos_pre['cliente']['CI_RUC'].'
Cajero: '.$_SESSION['INGRESO']['Nombre'].'
Telefono: '.$datos_pre['cliente']['Telefono'].'
Direccion: '.$datos_pre['cliente']['Direccion'];
$lineas = "<pre>
---------------------------------------
";

$lineas.="<table style='width: 284px;'>
<tr>
<td>PRODUCTO</td>
<td>CANT</td>
<td>PVP</td>";
if($_SESSION['INGRESO']['Servicio'] != 0){
  $lineas.="<td>SERVICIO</td>";
}
$lineas .= "<td>TOTAL</td>
</tr>";
  foreach ($datos_pre['lineas'] as $key => $value) {
    $lineas.="<tr style='font-size: 11px;'>";   
      $lineas.='<td>'.$value['Producto'].'</td>';
      $lineas.='<td>'.number_format($value['Cantidad'],2,'.','').'</td>';
      $lineas.='<td>'.number_format($value['Precio'],2,'.','').'</td>';
      $lineas.='<td>'.number_format(($value['Total'] * $value['Cantidad'])
                                  + (($value['Total'] * $value['Cantidad'])
                                  * ($_SESSION['INGRESO']['Servicio']/100))  ,2,'.','').'</td>';
    $lineas.="<tr>";
 }
 $lineas.="</table>";
 $lineas.= "<pre>
----------------------------------------</pre>";
 $lineas.="<table style='solid;width: 284px;'>
   <tr>
      <td style='width: 155px;'></td><td> SUBTOTAL:</td> <td>".number_format($datos_pre['tota'],2,'.','') ."</td>
   </tr>
   <tr>
      <td style='width: 155px;'></td><td> I.V.A 12%:</td> <td>".number_format($datos_pre['iva'],2,'.','')."</td>
   </tr>
   <tr>
      <td style='width: 155px;'></td><td> TOTAL FACTURA:</td> <td>".number_format($totalFA,2,'.','')."</td>
   </tr>
   <tr>
      <td style='width: 155px;'></td><td> EFECTIVO:</td> <td>".number_format($efectivo,2,'.','')."</td>
   </tr>
   <tr>
      <td style='width: 155px;'></td><td> CAMBIO:</td> <td>".number_format($saldo,2,'.','')."</td>
   </tr>
 </table>";
   $totales = "<pre>
----------------------------------------</pre>";
$datos_extra = "<pre>
Email: ".$datos_pre['cliente']['Email']."
         Fue un placer atenderle 
          Gracias por su compra




<br>
<br>
</pre>";

    $html =  $cabe.$lineas.$totales.$datos_extra;
    if(isset($_GET['pdf']) && $_GET['pdf']=='no')
    {
      echo $html;
    }else
    {

      $this->pdf->formatoPDFMatricial($html,$parametros,$datos_pre,$datos_empre,1);
      $archivos =array($datos_pre['lineas'][0]['Autorizacion'].'.pdf');
      $this->email->enviar_email($archivos,$datos_pre['cliente']['Email'],'Comprobante: '.$datos_pre['lineas'][0]['Autorizacion'],$titulo_correo='COMPROBANTE MIL CAMBIOS',$correo_apoyo='ejfc19omoshiroi@gmail.com',$nombre='mil cambios',$HTML=false);

     $this->pdf->formatoPDFMatricial($html,$parametros,$datos_pre,$datos_empre);
    }
    //crea pdf para enviar por corre
    // $this->pdf->formatoPDFMatricial($html,$parametros,$datos_pre,$datos_empre,true);
    // $archivos =array($datos_pre['lineas'][0]['Autorizacion'].'pdf');
    // $this->email->enviar_email($archivos,$datos_pre['cliente']['Email'],'Comprobante',$titulo_correo='aaaa',$correo_apooyo='ejfc19omoshiroi@gmail.com',$nombre='dasdas',$HTML=false);
  }


  function limpiar_grid()
  {
    return $this->modelo->limpiarGrid();
  }

  function cargaLineas()
  {
    $reg = $this->modelo->cargarLineas();
    $total = 0;
    $iva_total = 0;
    foreach ($reg['datos'] as $key => $value) {
      $total+=$value['TOTAL'];
      $iva_total+=$value['Total_IVA'];     
    }
    return array('tbl'=>$reg['tbl'],'total'=>$total, 'iva_total'=>$iva_total);
  }

  function Eliminar($codigo)
  {
     return $this->modelo->limpiarGrid($codigo);
  }

  function guardar_datosC($parametros)
  {
    $datos[0]['campo'] = 'Email';
    $datos[0]['dato'] = $parametros['ema'];
    $datos[1]['campo'] = 'Telefono';
    $datos[1]['dato'] = $parametros['tel'];

    $where[0]['campo'] = 'Codigo';
    $where[0]['valor'] = $parametros['cod'];
    $where[0]['tipo'] = 'string';

    $resp =  update_generico($datos,'Clientes',$where);
    // print_r($resp);die();
    return $resp;
  }

  function buscar_facturas($parametros)
  {
    $tbl = $this->modelo->lista_facturas($parametros['factura'],$parametros['query']);
    return $tbl;
  }

  function autorizar_fun()
  {
     // print_r('sdasd9');die();

//      $clave_acceso = '0408202203070216417900110010020000000091234567816';

// $firma = $this->autorizar_sri->firmar_documento(
//                 $clave_acceso,
//                 $_SESSION['INGRESO']['IDEntidad'],
//                 $_SESSION['INGRESO']['item'],
//                 $_SESSION['INGRESO']['Clave_Certificado'],
//                 $_SESSION['INGRESO']['Ruta_Certificado']);


//    $link_autorizacion = 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl';


// $validar_autorizado = $this->autorizar_sri->comprobar_xml_sri(
//   $clave_acceso,
//   $link_autorizacion);

//   $link_recepcion = 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl';
//   $recibido = $this->autorizar_sri->enviar_xml_sri($clave_acceso,$link_recepcion);


// $validar_autorizado = $this->autorizar_sri->comprobar_xml_sri(
//   $clave_acceso,
//   $link_autorizacion);

   $d = $this->autorizar_sri->datos_rimpe();
print_r($d);die();


    //  $url_firmados = 'C:\xampp\htdocs\diskcoversystem\php\comprobantes/entidades/entidad_0/CE998/Firmados/';
    //  $url_autorizados = 'C:\xampp\htdocs\diskcoversystem\php\comprobantes/entidades/entidad_0/CE998/Autorizados/';
    //  $url_rechazados = 'C:\xampp\htdocs\diskcoversystem\php\comprobantes/entidades/entidad_0/CE998/No_autorizados/';
    //  $url_No_autorizados = 'C:\xampp\htdocs\diskcoversystem\php\comprobantes/entidades/entidad_0/CE998/No_autorizados/';
    //  $url_enviados = 'C:\xampp\htdocs\diskcoversystem\php\comprobantes/entidades/entidad_0/CE998/Enviados/';
    //  $link_recepcion = 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl';
    //  $link_autorizacion = 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl';

    // $datos =  $this->autorizar_sri->comprobar_xml_sri($clave_acceso,$link_recepcion,$link_autorizacion,$url_firmados,$url_No_autorizados, $url_autorizados,$url_rechazados);
    // // print_r($datos);die();

    // // $datos =  $this->autorizar_sri->enviar_xml_sri($clave_acceso,$url_firmados,$url_enviados,$url_rechazados,$link_recepcion);

    // print_r($firma);die();

   
  }
        
}
?>