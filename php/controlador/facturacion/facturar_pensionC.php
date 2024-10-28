<?php
require_once(dirname(__DIR__,2)."/modelo/facturacion/facturarM.php");
require_once(dirname(__DIR__,2)."/modelo/facturacion/facturar_pensionM.php");
require_once(dirname(__DIR__,2)."/modelo/facturacion/catalogo_productosM.php");
require_once(dirname(__DIR__,2)."/comprobantes/SRI/autorizar_sri.php");
require_once(dirname(__DIR__,3)."/lib/excel/plantilla.php");
require_once(dirname(__DIR__,3).'/lib/phpmailer/enviar_emails.php');
date_default_timezone_set('America/Guayaquil'); 
if(!class_exists('cabecera_pdf'))
{
  require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
}

$controlador = new facturar_pensionC();
if(isset($_GET["ExcelReporteConsumo"])){
  echo json_decode($controlador->generarExcelReporteConsumoAgua($_GET));
  exit();
}

if(isset($_GET["PdfReporteConsumo"])){
  echo json_decode($controlador->generarPdfReporteConsumoAgua($_GET));
  exit();
}

if(isset($_GET['cliente']))
{
	$query = '';
	if(isset($_GET['q']) && $_GET['q'] != ''  )
	{
		$query = $_GET['q']; 
	}
  if (isset($_GET['total'])) {
    $datos = $controlador->totalClientes();
  }
  echo json_encode($controlador->getClientes($query, false, @$_GET['all']));
}
if(isset($_GET['clienteBasic']))
{
  $query = '';
  if(isset($_GET['q']) && $_GET['q'] != ''  )
  {
    $query = $_GET['q']; 
  }
  echo json_encode($controlador->getclienteBasic($query));
}

if(isset($_GET['numFactura']))
{
  $query = '';
  $DCLinea = $_POST['DCLinea'];
  $fact = SinEspaciosIzq($DCLinea);
  $serie = SinEspaciosDer($DCLinea);
  $DCLinea = explode(" ", $DCLinea);
  $autorizacion = $DCLinea[2];
  $codigo = ReadSetDataNum($fact."_SERIE_".$serie, True, False);
  echo json_encode(array('codigo' =>generaCeros($codigo,8),'serie' => $serie,'autorizacion' => $autorizacion));
  exit();
}

if(isset($_GET['catalogo']))
{
	$datos = $controlador->getCatalogoLineas();
  echo json_encode($datos);
}

if(isset($_GET['catalogoProducto']))
{
	$datos = $controlador->getCatalogoProductosC();
  echo json_encode($datos);
}

if(isset($_GET['historiaCliente']))
{
  $controlador->historiaCliente();
}

if(isset($_GET['historiaClienteExcel']))
{
  $controlador->historiaClienteExcel($_REQUEST['codigoCliente']);
}

if(isset($_GET['historiaClientePDF']))
{
  $controlador->historiaClientePDF($_REQUEST['codigoCliente']);
}

if(isset($_GET['DeudaPensionPDF']))
{
  $parametros = $_GET['lineas'];
  // print_r($parametros);die();
  $controlador->DeudaPensionPDF($_GET['codigoCliente'],$parametros);
}

if(isset($_GET['enviarCorreo']))
{
  $controlador->enviarCorreo();
}

if(isset($_GET['saldoFavor']))
{
	$controlador->getSaldoFavor();
}

if(isset($_GET['saldoPendiente']))
{
	$controlador->getSaldoPendiente();
}

if(isset($_GET['guardarPension']))
{
  echo json_encode($controlador->guardarFacturaPension());
  exit();
}

if(isset($_GET['guardarLineas']))
{
  echo json_encode($controlador->guardarLineas());
}

if(isset($_GET['CatalogoProductosByPeriodo']))
{
    //se definen los parametros que deseamos obtener
    $columnas = [
      'Codigo_Inv as id',
      'Producto as text'
    ];
  $controlador->CatalogoProductosByPeriodo($columnas);

}
else if(isset($_GET['GuardarInsPreFacturas']))
{
  $hayData = false;
  $CheqProducto   = @$_POST['PFcheckProducto'];
  $TxtCantidad    = @$_POST['PFcantidad']; 
  $TxtValor       = @$_POST['PFvalor']; 
  if(is_array($CheqProducto)){
    foreach ($CheqProducto as $item => $check) {
      if($check=='on' || $check == '1'){
        $Cantidad = @(int)$TxtCantidad[$item];
        $Valor = $TxtValor[$item];
        if ($Cantidad > 0 && $Valor > 0){
          $hayData = true;
          break;
        }
      }
    }
  }

  if($hayData){
    //Eliminamos pensiones si los tuviera
    $controlador->deleteClientesFacturacionProductoClienteAnioMes($_POST);
    //Procedemos a insertar las pensiones
    $controlador->insertClientesFacturacionProductoClienteAnioMes($_POST);
  }else{
    echo json_encode(array("rps" => 0 , "mensaje" => "Por favor complete la información."));
  }
  exit();
}
else if(isset($_GET['EliminarInsPreFacturas']))
{
  $hayData = false;
  $CheqProducto   = @$_POST['PFcheckProducto'];
  $TxtCantidad    = @$_POST['PFcantidad']; 
  $TxtValor       = @$_POST['PFvalor']; 
  if(is_array($CheqProducto)){
    foreach ($CheqProducto as $item => $check) {
      if($check=='on' || $check == '1'){
        $Cantidad = @(int)$TxtCantidad[$item];
        if ($Cantidad > 0 ){
          $hayData = true;
          break;
        }
      }
    }
  }

  if($hayData){
    $controlador->deleteClientesFacturacionProductoClienteAnioMes($_POST, true);
  }else{
    echo json_encode(array("rps" => 0 , "mensaje" => "Por favor complete la información."));
  }
  exit();
}

if(isset($_GET['clienteMatricula']))
{
  echo json_encode($controlador->getClientesMatriculas(@$_GET['codigoCliente']));
}
if(isset($_GET['cargarBancos']))
{
  $columnas = [
      'Codigo as id',
      'Descripcion as text'
    ];

  $filtro = '';
  if(isset($_GET['q']) && $_GET['q'] != ''  )
  {
    $filtro = $_GET['q']; 
  }
  echo json_encode($controlador->getcargarBancos($columnas, @$_GET['id'],$filtro, @$_GET['limit']));
}
if(isset($_GET['DCGrupo_No']))
{
  $query = '';
  if(isset($_GET['q']))
  {
    $query = $_GET['q'];
  }
  echo json_encode($controlador->getDCGrupo($query));
}

if(isset($_GET['DireccionByGrupo']))
{
  echo json_encode($controlador->getCargarDireccionByGrupo(@$_GET['grupo']));
}

if(isset($_GET['ActualizaDatosCliente']))
{
  echo json_encode($controlador->ActualizaDatosCliente($_POST));
  exit();
}

if(isset($_GET['getMBHistorico']))
{
  $datos = $controlador->getMBHistorico();
  echo json_encode($datos);
}

if(isset($_GET['BuscarClienteCodigo']))
{
  $data = $controlador->getClientes('',"{$_GET['BuscarClienteCodigo']}");
  if(count($data)>0){
    echo json_encode(['rps'=>true, 'data' => $data[0]]);
  }else{
    echo json_encode(['rps'=>false, 'data' => [], 'mensaje' => 'Usuario no encontrado']);
  }
}

if(isset($_GET['BuscarClienteCodigoMedidor']))
{
  echo json_encode($controlador->getClienteCodigoMedidor("{$_GET['BuscarClienteCodigoMedidor']}"));
  exit();
}

if(isset($_GET['GuardarConsumoAgua']))
{
  echo json_encode($controlador->GuardarConsumoAgua($_POST));
  exit();
}

if(isset($_GET['GuardarCambiosMedidorAgua']))
{
  echo json_encode($controlador->GuardarCambiosMedidorAgua($_POST));
  exit();
}

if(isset($_GET["TablaReporteConsumo"])){
  echo json_encode($controlador->TablaReporteConsumo($_POST));
  exit();
}

if(isset($_GET["SeriesFacturaConsumo"])){
  echo json_encode($controlador->SeriesFacturaConsumo($_POST));
  exit();
}

class facturar_pensionC
{
  private $facturacion;
	private $catalogoProductosModel;
  private $pdf;
  private $facturas;
  private $email;
  private $autorizar_sri;


	public function __construct(){
        $this->facturacion = new facturar_pensionM();
        $this->facturas = new facturarM();
        $this->catalogoProductosModel = new catalogo_productosM();
        $this->autorizar_sri = new autorizacion_sri();
        $this->pdf = new cabecera_pdf();
        $this->email = new enviar_emails(); 
        //$this->modelo = new MesaModel();
    }


  public function getclienteBasic($query){
    $datos = $this->facturacion->getclienteBasic($query);
    $clientes = [];
    foreach ($datos as $value) {
      $clientes[] = array('id'=>$value['Cliente'],'text'=>$value['Cliente'],'data'=>array( 'codigo' => $value['Codigo']));
    }
    return $clientes;
  }

	public function getClientes($query, $ruc=false, $all=false){
    // Leer_Datos_Cliente_SP($codigo)
		$datos = $this->facturacion->getClientes($query,$ruc);
    // print_r($datos);die();
		$clientes = [];
    if($all){
      $clientes[] = array('id'=>G_NINGUNO,'text'=>'TODOS','data'=>array('codigo' =>G_NINGUNO));
    }
    // print_r($datos);
		foreach ($datos as $value) {
			$clientes[] = array('id'=>$value['Cliente'],'text'=>$value['Cliente'],'data'=>array('email'=> $value['Email'],'direccion' => $value['Direccion'],'direccion1'=>$value['DireccionT'], 'telefono' =>$value['Telefono'], 'ci_ruc' => $value['CI_RUC'], 'codigo' => $value['Codigo'], 'cliente' => $value['Cliente'], 'grupo' => $value['Grupo'], 'tdCliente' => $value['TD'], 'Archivo_Foto'=> $value['Archivo_Foto'], 'Archivo_Foto_Url'=> BuscarArchivo_Foto_Estudiante($value['Archivo_Foto']), 'RUC_CI_Rep' => $value['CI_RUC_R'] 
      , 'Representante' => $value['Representante'], 'Telefono_R' => $value['Telefono_R'], 'EmailR' => $value['EmailR'], 'TD_R' => $value['TD_R'])); //,'dataMatricula'=>$matricula);
		}

    // print_r($clientes);die();
    return $clientes;
	}

  public function totalClientes(){
    $datos = $this->facturacion->getClientes('total');
    $total = count($datos);
    echo json_encode(array('registros'=>$total));
    exit();
  }

	public function getCatalogoLineas(){
		$emision = $_POST['fechaEmision'];
		$vencimiento = $_POST['fechaVencimiento'];
    $tipo = "'FA','NV'";
    if(isset($_POST['tipo']))
    {
      $tipo = "'".$_POST['tipo']."'";
    }

    //busco serie_FA en accesos SQLSERVER
    $usuario = $this->facturacion->getSerieUsuario($_SESSION['INGRESO']['CodigoU']);
    // print_r($usuario);die();
    $serie = '.';
    $datos = array();
    if(count($usuario)>0)
      { 
        if(isset($usuario[0]['Serie_FA']))
          {
            $serie = $usuario[0]['Serie_FA'];
          }
      }
    //buscar serie de usuario
   
    if($serie=='.'){ 

      // print_r($_SESSION['INGRESO']);die();
      if($_SESSION['INGRESO']['Serie_FA']!='.')
        { 
          $empresa = Empresa_data();
          // print_r($empresa);die();
          $serie = $empresa[0]['Serie_FA'];  //$_SESSION['INGRESO']['Serie_FA'];
        }

    }
    if($serie!='.'){
      // si hay serie busco en catalogo lineas
      $datos = $this->facturacion->getCatalogoLineas($emision,$vencimiento,$serie,$tipo);
      if(count($datos)==0)
      {
        return array();
      }  
    }else{
      $datos = $this->facturacion->getCatalogoLineas13($emision,$vencimiento,$tipo);
      if(count($datos)==0)
      {
        return array();
      } 
    }

    $catalogo = [];
    foreach ($datos as $value) {
      $catalogo[] = array('id'=>$value['Fact']." ".$value['Serie']." ".$value['Autorizacion']." ".$value['CxC']." ".$value['Codigo'] ,'text'=>mb_convert_encoding($value['Concepto'],'UTF-8'));
    }    
    return $catalogo;
	}

	public function getCatalogoProductosC(){
    $codigoCliente = $_POST['codigoCliente'];
		$CMedidor = isset($_POST['CMedidor']) ? $_POST['CMedidor'] : G_NINGUNO;
		$datos = $this->facturacion->getCatalogoProductos($codigoCliente,$CMedidor);
		$catalogo = [];
		foreach ($datos as $value) {
			$catalogo[] = array('mes'=> mb_convert_encoding($value['Mes'], 'UTF-8'),'codigo'=> mb_convert_encoding($value['Codigo_Inv'], 'UTF-8'),'periodo'=> mb_convert_encoding($value['Periodos'], 'UTF-8'),'producto'=>$value['Producto'],'valor'=> mb_convert_encoding($value['Valor'], 'UTF-8'), 'descuento'=> mb_convert_encoding($value['Descuento'], 'UTF-8'),'descuento2'=> mb_convert_encoding($value['Descuento2'], 'UTF-8'),'iva'=> mb_convert_encoding($value['IVA'], 'UTF-8'),'CodigoL'=> mb_convert_encoding($value['Codigo'], 'UTF-8'),'CodigoL'=> mb_convert_encoding($value['Codigo'], 'UTF-8'),'Credito_No'=>$value['Credito_No'],'Codigo_Auto'=>$value['Codigo_Auto']);
		}
    return $catalogo;
	}

  public function historiaCliente(){
    $codigoCliente = $_POST['codigoCliente'];
    if ($codigoCliente == "") {
      $codigoCliente = G_NINGUNO;
    }
    $datos = $this->facturacion->historiaCliente($codigoCliente);
    // print_r($codigoCliente);die();
    $historia = [];
    foreach ($datos as $value) {
      $historia[] = array('TD'=> mb_convert_encoding($value['TD'], 'UTF-8'),'Fecha'=> mb_convert_encoding($value['Fecha']->format('Y-m-d'), 'UTF-8'),'Serie'=> mb_convert_encoding($value['Serie'], 'UTF-8'),'Factura'=> mb_convert_encoding($value['Factura'], 'UTF-8'),'Detalle'=> $value['Detalle'], 'Anio'=> mb_convert_encoding($value['Anio'], 'UTF-8'),'Mes'=> mb_convert_encoding($value['Mes'], 'UTF-8'),'Total'=> mb_convert_encoding($value['Total'], 'UTF-8'),'Abonos'=> mb_convert_encoding($value['Abonos'], 'UTF-8'),'Mes_No'=> mb_convert_encoding($value['Mes_No'], 'UTF-8'),'No'=> mb_convert_encoding($value['No'], 'UTF-8') );
    }
    echo json_encode($historia);
    exit();
  }

  public function historiaClienteExcel($codigo,$download = true){
    $codigoCliente = $codigo;
    if ($codigoCliente == "") {
      $codigoCliente = G_NINGUNO;
    }
    $datos = $this->facturacion->historiaCliente($codigoCliente);

    $tablaHTML =array();
       $tablaHTML[0]['medidas']=array(9,20,9,9,50,9,9,9,9,9,9);
         $tablaHTML[0]['datos']=array('TD','Fecha','Serie','Factura','Detalle','Año','Mes','Total','Abonos','Mes No','No');
         $tablaHTML[0]['tipo'] ='C';
         $pos = 1;
    foreach ($datos as $key => $value) {
       $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
           $tablaHTML[$pos]['datos']=array($value['TD'],$value['Fecha'],$value['Serie'],$value['Factura'],$value['Detalle'],$value['Anio'],$value['Mes'],number_format($value['Total'],2),number_format($value['Abonos'],2),$value['Mes_No'],$value['No']);
           $tablaHTML[$pos]['tipo'] ='N';
           $pos+=1;
    }
    excel_generico($titulo='Historia del cliente',$tablaHTML);

    // historiaClienteExcel($datos,$ti='HistoriaCliente',$camne=null,$b=null,$base=null,$download);
  }

  public function historiaClientePDf($codigo,$download = true){
    $codigoCliente = $codigo;
    if ($codigoCliente == "") {
      $codigoCliente = G_NINGUNO;
    }
    $datos = $this->facturacion->historiaCliente($codigoCliente);
    $cli = $this->facturacion->getClientes(false,$codigoCliente);
    // print_r($cli);die();

    $AdoAux = $this->facturas->FechaInicialHistoricoFacturas();
    @$AdoAux = (isset($AdoAux[0]["MinFecha"]))?$AdoAux[0]["MinFecha"]->format("Ymd"):"";
    $FechaInicial = (($AdoAux!="")?PrimerDiaMes($AdoAux,"Ymd"):"20000101");

    Reporte_Cartera_Clientes_SP($FechaInicial, UltimoDiaMes2(date('d/m/Y'),'Ymd'), $codigoCliente);
    $AdoCarteraDB = $this->facturacion->Reporte_Cartera_Clientes_PDF_Data($_SESSION['INGRESO']['CodigoU']);
    $titulo = 'REPORTE CARTERA DE CLIENTES';
    $parametros['desde'] = false;
    $parametros['hasta'] = false;
    $sizetable = 8;
    $mostrar = true;
    $tablaHTML = array();

    $EmailCli = "";
    $EmailCli = Insertar_Mail($EmailCli, $AdoCarteraDB[0]['EmailR']);
    $EmailCli = Insertar_Mail($EmailCli, $AdoCarteraDB[0]['Email2']);
    $EmailCli = Insertar_Mail($EmailCli, $AdoCarteraDB[0]['Email']);

    $contenido[0]['tipo'] = 'texto';
    $contenido[0]['posicion'] = 'top-tabla';
    $contenido[0]['valor'] = 'CLIENTE: '.$AdoCarteraDB[0]['Cliente'];
    $contenido[0]['estilo'] = 'I';
    $contenido[0]['tamaño'] = '9';
    $contenido[0]['separacion'] = '1';
    $contenido[1]['tipo'] = 'texto';
    $contenido[1]['posicion'] = 'top-tabla';
    $contenido[1]['valor'] = 'UBICACION: '.$AdoCarteraDB[0]['Direccion'];
    $contenido[1]['estilo'] = 'I';
    $contenido[1]['tamaño'] = '9';
    $contenido[1]['separacion'] = '1';
    $contenido[2]['tipo'] = 'texto';
    $contenido[2]['posicion'] = 'top-tabla';
    $contenido[2]['valor'] = 'EMAILS: '.$EmailCli;
    $contenido[2]['estilo'] = 'I';
    $contenido[2]['tamaño'] = '9';
    $contenido[3]['tipo'] = 'texto';
    $contenido[3]['posicion'] = 'top-tabla';
    $contenido[3]['valor'] = 'La informacion presente reposa en la base de dato de la Institucion, corte realizado desde '.FechaStrg($FechaInicial,"Ymd").' al '.FechaStrg(date("Ymd"),"Ymd").', cualquier informacion adicional comuniquese a la institucion';
    $contenido[3]['estilo'] = 'I';
    $contenido[3]['tamaño'] = '8';

    $tablaHTML[0]['medidas'] = array(6,6,13,15,17,80,14,8,15,16,16);
    $tablaHTML[0]['alineado'] = array('L','L','L','L','L','L','L','L','L','L','L');
    $tablaHTML[0]['datos'] = array('T','TC','Serie','Factura','Fecha','Detalle','Año','Mes','Cargos','Abonos'/*,'Mes No','No'*/);
    $tablaHTML[0]['borde'] = "BT";
    $tablaHTML[0]['estilo'] = 'B';
    $tablaHTML[0]['sizetable'] = $sizetable;

    $count = 1;
    $factura="";
    foreach ($AdoCarteraDB as $value) {
          $tablaHTML[$count]['borde'] = 0;
      $tablaHTML[$count]['medidas'] = $tablaHTML[0]['medidas'];
      $tablaHTML[$count]['alineado'] = array('L','L','L','R','L','L','R','R','R','R','R');
      $tablaHTML[$count]['datos'] = array($value['T'],$value['TC'],$value['Serie'],$value['Factura'],$value['Fecha']->format('d/m/Y'),$value['Detalle'], $value['Anio'],str_pad($value['Mes'], 2, "0", STR_PAD_LEFT),number_format($value['Cargos'],2),number_format($value['Abonos'],2)/*,$value['Mes_No'],$value['No']*/);
      if(strpos($value['Detalle'], 'SALDO TOTAL')){
          $tablaHTML[$count]['medidas'] = array(188);
          $tablaHTML[$count]['alineado'] = array('C');
          $tablaHTML[$count]['datos'] = array($value['Detalle']);
          $tablaHTML[$count]['borde'] = $tablaHTML[0]['borde'];
      }
      $count+=1;
    }
    $this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido,$image=false,$parametros['desde'],$parametros['hasta'],$sizetable,$mostrar,18,$orientacion='P',$download, $tablaHTML[0]);
  }

  public function DeudaPensionPDF($codigo,$lineas,$download = true){

    $lin = json_decode($lineas, true);
    // print_r($lin);die();
    $codigoCliente = $codigo;
    if ($codigoCliente == "") {
      $codigoCliente = G_NINGUNO;
    }
    // $datos = $this->facturacion->historiaCliente($codigoCliente);

    $titulo = 'HistoriaCliente';
    $fechaini = false;
    $fechafin = false;
    $sizetable = 8;
    $mostrar = true;
    $tablaHTML = array();


    $tablaHTML[0]['medidas'] = array(20,18,15,40,25,25,25,25);
    $tablaHTML[0]['alineado'] = array('L','L','L','L','R','R','R','R');
    $tablaHTML[0]['datos'] = array('MES','CODIGO','AÑO','PRODUCTO','VALOR','DESCUENTO','DESC. P.P.','TOTAL');
    $tablaHTML[0]['borde'] = 'B';

    $count = 1;
    $total = 0;
    foreach ($lin as $key => $value) {
      $tablaHTML[$count]['medidas'] = $tablaHTML[0]['medidas'];
      $tablaHTML[$count]['alineado'] = $tablaHTML[0]['alineado'];
      $tablaHTML[$count]['datos'] = array($value['mes'],$value['cod'],$value['ani'],$value['pro'],number_format($value['val'],2),number_format($value['des'],2),number_format($value['p.p'],2),number_format($value['tot'],2));
      $tablaHTML[$count]['borde'] = 'B';
      $count=$count+1;
      $total+=$value['tot'];
    }
    $count=$count+1;   
    $tablaHTML[$count]['medidas'] = array(128,40,25);
    $tablaHTML[$count]['alineado'] = array('L','R','R');
    $tablaHTML[$count]['datos'] = array('CORTE AL '.date('r'),'TOTAL A PAGAR USD ',number_format($total,2));

    $tablaHTML[$count+1]['medidas'] = array(190);
    $tablaHTML[$count+1]['alineado'] = array('L');
    $tablaHTML[$count+1]['datos'] = array('Los datos presentados en este reporte, reflejan los valores pendientes de pago, por concepto de Pensiones Educativas.');

    $this->pdf->DeudapendientePensionesPDF($titulo,$tablaHTML,$contenido=false,$image=false,$fechaini,$fechafin,$sizetable,$mostrar,$sal_hea_body=30,$orientacion='P',$download=true);                                         
  }



  public function enviarCorreo(){
    //Eliminar archivos temporales
    if (file_exists(dirname(__DIR__,3).'/TEMP/EMPRESA_'.$_SESSION['INGRESO']['item'].'/Historia del cliente.xlsx')) {
      unlink(dirname(__DIR__,3).'/TEMP/EMPRESA_'.$_SESSION['INGRESO']['item'].'/Historia del cliente.xlsx');
    }
    if (file_exists(dirname(__DIR__,3).'/TEMP/REPORTE CARTERA DE CLIENTES.pdf')) {
      unlink(dirname(__DIR__,3).'/TEMP/REPORTE CARTERA DE CLIENTES.pdf');
    }
    $this->historiaClientePDf($_REQUEST['codigoCliente'],false);
    $this->historiaClienteExcel($_REQUEST['codigoCliente'],false);
    $archivos[0] = dirname(__DIR__,3).'/TEMP/EMPRESA_'.$_SESSION['INGRESO']['item'].'/Historia del cliente.xlsx';
    $archivos[1] = dirname(__DIR__,3).'/TEMP/REPORTE CARTERA DE CLIENTES.pdf';
    $to_correo = $_REQUEST['email'];
    $titulo_correo = 'Historial de cliente';
    $nombre = 'DiskCover System';
    $cuerpo_correo = 'Estimado (a) ha recibido su historial en formato PDF y EXCEL';
    $cuerpo_correo .= '<br>'.mb_convert_encoding('
    <pre>
      -----------------------------------
      SERVIRLES ES NUESTRO COMPROMISO, DISFRUTARLO ES EL SUYO.


      Este correo electrónico fue generado automáticamente del Sistema Financiero Contable DiskCover System a usted porque figura como correo electrónico alternativo de Oblatas de San Francisco de Sales.
      Nosotros respetamos su privacidad y solamente se utiliza este correo electrónico para mantenerlo informado sobre nuestras ofertas, promociones y comunicados. No compartimos, publicamos o vendemos su información personal fuera de nuestra empresa. Para obtener más información, comunicate a nuestro Centro de Atención al Cliente Teléfono: 052310304. Este mensaje fue recibido por: DiskCover Sytem.

      Por la atención que se de al presente quedo de usted.


      Esta dirección de correo electrónico no admite respuestas. En caso de requerir atención personalizada por parte de un asesor de servicio al cliente de DiskCover System, Usted podrá solicitar ayuda mediante los canales de atención al cliente oficiales que detallamos a continuación: Telefonos: (+593) 02-321-0051/098-652-4396/099-965-4196/098-910-5300.
      Emails: prisma_net@hotmail.es/diskcover@msn.com.

      www.diskcoversystem.com
      QUITO - ECUADOR</pre>', 'ISO-8859-1','UTF-8');
    $this->email->enviar_historial($archivos,$to_correo,$cuerpo_correo,$titulo_correo,$nombre);
    exit();
    
  }

	public function getCatalogoCuentas(){
		$datos = $this->facturacion->getCatalogoCuentas();
		$cuentas = [];
    $cuentas[0] = array('codigo'=>'','nombre'=>'No existen datos.');
    $i = 0;
    foreach ($datos as $value) {
			$cuentas[$i] = array('codigo'=>$value['Codigo']."/".$value['TC'],'nombre'=>$value['Codigo']." - ".$value['NomCuenta']);
      $i++;
		}
		return $cuentas;
	}

	public function getNotasCredito(){
		$datos = $this->facturacion->getNotasCredito();
		$cuentas = [];
    $cuentas[0] = array('codigo'=>'','nombre'=>'No existen datos.');
    $i = 0;
		foreach ($datos as $value) {
			$cuentas[$i] = array('codigo'=>$value['Codigo'],'nombre'=>$value['Codigo']." - ".$value['NomCuenta']);
      $i++;
		}
		return $cuentas;
	}

  public function getAnticipos(){
    $codigo = Leer_Seteos_Ctas('Cta_Anticipos_Clientes');
    $datos = $this->facturacion->getAnticipos($codigo);
    $cuentas = [];
    $cuentas[0] = array('codigo'=>'','nombre'=>'No existen datos.');
    $i = 0;
    foreach ($datos as $value) {
      $cuentas[$i] = array('codigo'=>$value['Codigo'],'nombre'=>$value['Codigo']." - ".$value['NomCuenta']);
      $i++;
    }
    return $cuentas;
  }

	public function getSaldoFavor(){
		$codigoCliente = $_POST['codigoCliente'];
		$datos = $this->facturacion->getSaldoFavor($codigoCliente);
    // print_r($datos);
		// $catalogo = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC);
		echo json_encode($datos);
		exit();
	}

	public function getSaldoPendiente(){
		$codigoCliente = $_POST['codigoCliente'];
		$datos = $this->facturacion->getSaldoPendiente($codigoCliente);
		// $catalogo = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC);
		echo json_encode($datos);
		exit();
	}

	public function guardarFacturaPension(){
    if(empty(trim($_POST['DCLinea']))){
      echo json_encode(array('respuesta'=>6, 'text'=>"Se debe indicar una Linea de Catalogo"));
      exit();
    }
    
    if($_POST['saldoTotal']<0){
      echo json_encode(array('respuesta'=>6,'text'=>"El total de abonos supera el total de la factura."));
      exit();
    }

    if($_POST['saldoTotal']>0){//Significa que es un pago parcial
      $datos = $this->facturacion->getAsiento();
      if(count($datos)>0){
        $intersection = array_intersect(array_column($datos, 'CODIGO'), array(JG01, JG02, JG03));
        if (!empty($intersection)) {
          echo json_encode(array('respuesta'=>6,'text'=>"Esta operacion no se puede procesar con pago parcial."));
          exit();
        }
      }else{
        echo json_encode(array('respuesta'=>-1,'text'=>"No se encontraron asientos para procesar."));
        exit();
      }
    }

		$TextRepresentante = $_POST['TextRepresentante'];
		$TxtDireccion = $_POST['TxtDireccion'];
		$TxtTelefono = $_POST['TxtTelefono'];
		$TextFacturaNo = $_POST['TextFacturaNo'];
		$Grupo_No = $_POST['Grupo_No'];
  	$TextCI = $_POST['TextCI'];
  	$TD_Rep = $_POST['TD_Rep'];
  	$TxtEmail = $_POST['TxtEmail'];
  	$TxtDirS = $_POST['TxtDirS'];
		$codigoCliente = $_POST['codigoCliente'];
		$update = $_POST['update'];
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

    $DCDebito = $_POST['DCDebito'];
    $CTipoCta = $_POST['CTipoCta'];
    $TxtCtaNo = $_POST['TxtCtaNo'];
    $MBFecha = $_POST['MBFecha'];
    $CheqPorDeposito = @($_POST['CheqPorDeposito']=='on' || $_POST['CheqPorDeposito'] == '1')?true:false;
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

   	if ($update) {
   		$updateCliF = $this->facturacion->updateClientesFacturacion($Grupo_No,$codigoCliente);
   		$updateCli = $this->facturacion->updateClientes($TxtTelefono,$TxtDirS,$TxtDireccion,$TxtEmail,$Grupo_No,$codigoCliente);
      $this->facturacion->Actualiza_Datos_Cliente($_POST);
   	}

    $TC = @SinEspaciosIzq($_POST['DCLinea']);
    $serie = @SinEspaciosDer($_POST['DCLinea']);
    //traer secuencial de catalogo lineas
   	$TextFacturaNo = ReadSetDataNum($TC."_SERIE_".$serie, True, False);
   	return $this->Grabar_FA_Pensiones($_POST,$TextFacturaNo);
	}


	public function Grabar_FA_Pensiones($FA,$TextFacturaNo){
    $codigoCliente = $FA['codigoCliente'];
		//Seteamos los encabezados para las facturas
		$Estudiante['cedula'] = $FA['TextCI'];
		$Estudiante['fonopaga'] = $FA['TxtTelefono'];
  	$Estudiante['pagador'] = $FA['TextRepresentante'];
		$Estudiante['direcpaga'] = $FA['TxtDireccion'];
    $resultado = explode(" ", $FA['DCLinea']);
    $FA['Autorizacion'] = $resultado[2];
    $FA['Cta_CxP'] = $resultado[3];
    $FA['Fecha'] = $FA['Fecha'];
		//Procedemos a grabar la factura
  	$datos = $this->facturacion->getAsiento();
    if(count($datos)<=0){
      return (array('respuesta'=>-1,'text'=>"No se encontraron asientos para procesar."));
    }
    $cliente = Leer_Datos_Cliente_FA($codigoCliente);
    $TFA = Calculos_Totales_Factura($codigoCliente);
    if(isset($cliente['Razon_Social']) && $cliente['Razon_Social']=='CONSUMIDOR FINAL'){
      $Con_IVA_ = number_format($TFA['Con_IVA'], 2,'.','');
      $Sin_IVA_ = number_format($TFA['Sin_IVA'], 2,'.','');
      $SubTotal_ = $Sin_IVA_ + $Con_IVA_ - $TFA['Descuento'] - $TFA['Descuento2'];
      if($SubTotal_>MONTO_MAXIMO_FACTURACION){
        return (array('respuesta'=>-1,'text'=>"Por Ley no se puede emitir una facturar por mas de ".MONTO_MAXIMO_FACTURACION));
      }
    }
    $SaldoPendiente = 0;
    $Total_Abonos = $FA['TxtEfectivo']+$FA['TextCheque']+$FA['TxtNCVal']+$FA['saldoFavor'];
    $totalIva = 0;
    foreach ($datos as $key => $value) {
       $totalIva = $totalIva+$value['Total_IVA'];
       $Valor = $value["TOTAL"];
       $Total_Desc = $value["Total_Desc"]+$value["Total_Desc2"];
       $ValorDH = $Valor - $Total_Desc;
       $Codigo = $value["Codigo_Cliente"];
       $Codigo1 = $value["CODIGO"];
       $Codigo2 = $value["Mes"];
       $Codigo3 = (substr($value["CODIGO"], 0, 3) === "JG." && $value["CORTE"]!=0)?$value["CORTE"]:$value["HABIT"];
       $Anio1 = $value["TICKET"];
       $ID_Reg = $value["A_No"];
       $Medidor_Asiento = $value["Tipo_Hab"];
       $Total_Abonos = $Total_Abonos - $ValorDH;
          if($Total_Abonos >= 0){
            $this->facturacion->actualizar_Clientes_Facturacion($Valor,$Anio1,$Codigo,$Codigo1,$Codigo2,$Codigo3, $Medidor_Asiento);
          }else{
            $Valor = $Valor + $Total_Abonos;
            if($Valor > 0){
              $this->facturacion->actualizar_Clientes_Facturacion2($Total_Abonos,$Total_Desc,$Anio1,$Codigo,$Codigo1,$Codigo2,$Codigo3, $Medidor_Asiento);
              $Total_Abonos = $Total_Abonos + $Total_Desc;
              $Valor = $Valor - $Total_Desc;
              $this->facturacion->actualizar_asiento_F($Valor,$ID_Reg);
            }else{
              $this->facturacion->deleteAsientoEd($ID_Reg);              
            }
          }
    }

    foreach ($datos as $key => $value) {
      $FA['CodigoC'] = $codigoCliente;
      $FA['Tipo_PRN'] = "FM";
      $FA['FacturaNo'] = $TextFacturaNo;
      $FA['Nuevo_Doc'] = true;
      $FA['Factura'] = intval($TextFacturaNo);
      $FA['TC'] = SinEspaciosIzq($FA['DCLinea']);
      $FA['Serie'] = SinEspaciosDer($FA['DCLinea']);
      if (Existe_Factura($FA)) {
        
      }
      
      $DiarioCaja = ReadSetDataNum("Recibo_No", True, True);
      if ($FA['Nuevo_Doc']) {
        $FA['Factura'] = ReadSetDataNum($FA['TC']."_SERIE_".$FA['Serie'], True, True);
      }
      $SubTotal_NC = $FA['TxtNC'];
      $Total_Anticipo = $FA['saldoFavor'];
      $Total_Bancos = $FA['TextCheque'];
      $Total_Efectivo = $FA['TxtEfectivo'];
      $TotalCajaMN = $FA['Total'] - $Total_Bancos - $SubTotal_NC;
      $TextoFormaPago = "CONTADO";
      $Total_Abonos = $TotalCajaMN + $Total_Bancos + $SubTotal_NC;
      $FA['Total_Abonos'] = $Total_Abonos;
      $FA['T'] = G_PENDIENTE;
      if(isset($FA['saldoTotal']) && $FA['saldoTotal']==0)
      {
        $FA['T'] = 'C';        
      }
      $FA['Saldo_MN'] = $FA['Total'] - $Total_Abonos;
      if($totalIva==0)
      {
        $FA['Porc_IVA'] = $_SESSION['INGRESO']['porc'];
      }else
      {
        $FA['Porc_IVA'] = floatval($FA['PorcIva']/100); //$_SESSION['INGRESO']['porc'];
      }
      $FA['Cliente'] = $FA['TextRepresentante'];
      $FA['me'] = $value['HABIT'];
      $TA['me'] = $value['HABIT'];
      $TA['Recibi_de'] = $FA['Cliente'];
      $Cta = SinEspaciosIzq($FA['DCBanco']);
      $Cta1 = SinEspaciosIzq($FA['DCNC']);
      $Valor = $value["TOTAL"];
      $Codigo = $value["Codigo_Cliente"];
      $Codigo1 = $value["CODIGO"];
      $Codigo2 = $value["Mes"];
      $Codigo3 = ".";
      $Anio1 = $value["TICKET"];
      //Grabamos el numero de factura
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
      if(strlen($FA['TextBanco'])<=1){
        $TA['Banco'] = strtoupper($FA['DCBanco']);
      }else{
        $TA['Banco'] = strtoupper($FA['TextBanco'].' - '.$FA['Grupo_No']);
      }
      $TA['Cheque'] = $FA['chequeNo'];
      $TA['Abono'] = $Total_Bancos;

      // print_r($TA);die();
      Grabar_Abonos($TA);

      //Abono de Factura
      $TA['Cta'] = $_SESSION['SETEOS']['Cta_CajaG'];
      $TA['Banco'] = "EFECTIVO MN";
      $TA['Cheque'] = strtoupper($FA['Grupo_No']);
      $TA['Abono'] = $Total_Efectivo;
      $TA['Comprobante'] = "";
      $TA['Codigo_Inv'] = "";
      // print_r($TA);die();
      Grabar_Abonos($TA);

      //Forma del Abono SubTotal NC
      if ($SubTotal_NC > 0) {
        $SubTotal_NC = $SubTotal_NC - $TFA['Total_IVA'];
        $TA['Cta'] = $Cta1;
        $TA['Banco'] = "NOTA DE CREDITO";
        $TA['Cheque'] = "VENTAS";
        $TA['Serie_NC'] = G_NINGUNO;
        $TA['Autorizacion_NC'] = G_NINGUNO;
        $TA['Nota_Credito'] = 0;
        $TA['Abono'] = $SubTotal_NC;
        Grabar_Abonos($TA);
      }
      
      //Abonos Anticipados Cta_Ant_Cli
       $TA['Cta'] = SinEspaciosIzq($FA['DCAnticipo']);
       if(strlen($FA['TextBanco']) > 1) { $TA['Banco'] = strtoupper($FA['TextBanco']); } else { $TA['Banco'] = "ANTICIPO PENSIONES";};
       $TA['Cheque'] = strtoupper($FA['Grupo_No']);
       $TA['Abono'] = $Total_Anticipo;
       Grabar_Abonos($TA);
     
      //Forma del Abono IVA NC
      if ($TFA['Total_IVA'] > 0) {
        $TA['Cta'] = $_SESSION['SETEOS']['Cta_IVA'];
        $TA['Banco'] = "NOTA DE CREDITO";
        $TA['Cheque'] = "I.V.A.";
        $TA['Serie_NC'] = G_NINGUNO;
        $TA['Autorizacion_NC'] = G_NINGUNO;
        $TA['Nota_Credito'] = 0;
        $TA['Abono'] = $TFA['Total_IVA'];
        Grabar_Abonos($TA);
      }
     
      //Abono de Factura
      $TA['T'] = G_NORMAL;
      $TA['TP'] = "TJ";
      $TACta = $Cta;
      $TA['Cta_CxP'] = $FA['Cta_CxP'];
      $TA['Banco'] = "INTERES POR TARJETA";
      $TA['Cheque'] =  $FA['chequeNo'];
      $TA['Abono'] = intval($FA['TextInteres']);
      $TA['Recibi_de'] = $FA['Cliente'];
      Grabar_Abonos($TA);
       
      $TA['T'] = $FA['T'];
      $TA['TP'] = $FA['TC'];
      $TA['Serie'] = $FA['Serie'];
      $TA['Factura'] = $FA['Factura'];
      $TA['Autorizacion'] = $FA['Autorizacion'];
      $TA['CodigoC'] = $FA['codigoCliente'];
      $conn = new db();
        $sql = "UPDATE Facturas
            SET Saldo_MN = 0 ";
          if (isset($FA['saldoTotal']) && $FA['saldoTotal'] == 0) {
            $sql .= ",T = 'C'";
          } else {
            $sql .= " ,T = 'P' ";
          }
        $sql .= "
          WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
          AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
          AND Factura = " . $TextFacturaNo . "
          AND TC = '" . $FA['TC'] . "'
          AND CodigoC = '" . $FA['codigoCliente'] . "'
          AND Autorizacion = '" . $FA['Autorizacion'] . "'
          AND Serie = '" . $FA['Serie'] . "' ";

        $conn->String_Sql($sql);


      $TxtEfectivo = "0.00";
      if (strlen($FA['Autorizacion']) >= 13) 
      {
        $FA['Desde'] = $FA['Factura'];
        $FA['Hasta'] = $FA['Factura'];
      }
      $FA['serie'] = $FA['Serie'];
      $FA['num_fac'] = $FA['Factura'];
      $FA['tc'] = $FA['TC'];
      $FA['cod_doc'] = '01';

    if (strlen($FA['Autorizacion']) == 13) {
      try {
        $rep = $resultado = $this->autorizar_sri->Autorizar_factura_o_liquidacion($FA);
        $dataFac = $this->facturacion->getDataBasicFactura($FA['Serie'], $FA['Factura'], $FA['CodigoC']);
        if($rep==1)
        {
          $resultado = array('respuesta'=>$rep, 'auto'=>$dataFac['Autorizacion'], 'per' => $dataFac['Periodo']);
        }else{ $resultado = array('respuesta'=>-1,'text'=>((!is_null($rep))?mb_convert_encoding($rep, 'UTF-8'):$rep), 'auto'=>$dataFac['Autorizacion'], 'per' => $dataFac['Periodo']);}
      } catch (Exception $e) {
        $resultado = array('respuesta'=>-1,'text'=>$e->getMessage());
      }
    }else{ 
      $resultado = array('respuesta'=>5, 'auto' =>((isset($FA['Autorizacion']))?$FA['Autorizacion']:G_NINGUNO), 'per'=> $_SESSION['INGRESO']['item']);
    }
    return $resultado;
    }
  }

  public function guardarLineas(){
    $this->facturacion->deleteAsiento($_POST['codigoCliente']);
    $datos = array();
    $Contador = 0;
    if(isset($_POST['datos'])){
      foreach ($_POST['datos'] as $key => $producto) {
        SetAdoAddNew('Asiento_F');
        SetAdoFields("CODIGO", $producto['Codigo']);
        SetAdoFields("CODIGO_L", $producto['CodigoL']);
        SetAdoFields("PRODUCTO", $producto['Producto']);
        SetAdoFields("CANT", 1);
        SetAdoFields("PRECIO", $producto['Precio']);
        SetAdoFields("Total_Desc", $producto['Total_Desc']);
        SetAdoFields("Total_Desc2", $producto['Total_Desc2']);
        SetAdoFields("TOTAL", $producto['Precio']);
        if($producto['Iva']!=0)
        {
          SetAdoFields("Total_IVA", ($producto['Precio'] * ($producto['Iva_val'] / 100)));
        }else
        {
           SetAdoFields("Total_IVA", ($producto['Precio'] * ($producto['Iva'] / 100)));
        }
        SetAdoFields("Cta", 'Cuenta');
        SetAdoFields("Codigo_Cliente", $_POST['codigoCliente']);
        SetAdoFields("Mes", $producto['MiMes']);
        SetAdoFields("TICKET", $producto['Periodo']);
        SetAdoFields("CodigoU", $_SESSION['INGRESO']['CodigoU']);

        // print_r($producto);die();
        if(isset($producto['CORTE'])){
          SetAdoFields("CORTE", $producto['CORTE']);
        }
        if(isset($producto['Tipo_Hab'])){
          SetAdoFields("Tipo_Hab", $producto['Tipo_Hab']);
        }

        SetAdoFields("A_No", $Contador);
        $Contador++;
        $stmt = SetAdoUpdate();
      }
    }
    Eliminar_Nulos_SP("Asiento_F");
    return (@count($_POST['datos'])==($Contador));
  }
  //El parametro columnas es un array que definen los parametros que deseamos obtener de la consulta sql
  public function CatalogoProductosByPeriodo(array $columnas){
    echo json_encode($this->catalogoProductosModel->getCatalogoProductosByPeriodo($columnas));
    exit();
  }

  public function deleteClientesFacturacionProductoClienteAnioMes($POST, $responder = false)
  {
    $CheqProducto   = @$POST['PFcheckProducto'];
    $DCProducto     = @$POST['PFselectProducto']; 
    $MBFechaP       = @$POST['PFfechaInicial']; 
    $TxtCantidad    = @$POST['PFcantidad']; 
    $TxtValor       = @$POST['PFvalor']; 
    $TxtDescuento   = @$POST['PFdescuento']; 
    $TxtDescuento2  = @$POST['PFdescuento2']; 
    $codigoCliente  = @$POST['PFcodigoCliente']; 

    if($codigoCliente!=""){
      $respuestaDB = $peticionesDB = 0;

      $medidor =  ((isset($POST['CMedidorPrefactura']))?$POST['CMedidorPrefactura']:G_NINGUNO);

      foreach ($CheqProducto as $item => $check) {
        if($check=='on' || $check == '1'){
          $Cantidad = @(int)$TxtCantidad[$item];
          $Valor = @(float)$TxtValor[$item];
          if ($Cantidad > 0){
            $Mifecha = PrimerDiaMes($MBFechaP[$item]);
            $CodigoInv = @$DCProducto[$item];
            $CodigoInv = ($CodigoInv!="")?$CodigoInv:G_NINGUNO;
            for ($i=0; $i < $Cantidad; $i++) { 
              $NoMes = ObtenerMesFecha($Mifecha);
              $Anio = ObtenerAnioFecha($Mifecha);
              $peticionesDB++;
              $respuestaDB += $this->facturacion->deleteClientes_FacturacionProductoClienteAnioMes($codigoCliente, $CodigoInv, $Anio, $NoMes, $medidor);
              $Mifecha = PrimerDiaSeguienteMes($Mifecha);
            }
          }
        }
      }
      if($responder){
        echo json_encode(array("rps" => 1 , "mensaje" => "Proceso finalizado correctamente" ));
      }
    }else{
      if($responder){
        echo json_encode(array("rps" => false , "mensaje" => "Debe seleccionar un cliente." ));
      }
    }
  }

  public function insertClientesFacturacionProductoClienteAnioMes($POST)
  {
    $CheqProducto   = @$POST['PFcheckProducto'];
    $DCProducto     = @$POST['PFselectProducto']; 
    $MBFechaP       = @$POST['PFfechaInicial']; 
    $TxtCantidad    = @$POST['PFcantidad']; 
    $TxtValor       = @$POST['PFvalor']; 
    $TxtDescuento   = @$POST['PFdescuento']; 
    $TxtDescuento2  = @$POST['PFdescuento2']; 
    $codigoCliente  = @$POST['PFcodigoCliente']; 
    $GrupoNo        = @$POST['PFGrupoNo']; 

    if($codigoCliente!=""){
      $respuestaDB = $peticionesDB = 0;

      $medidor =  ((isset($POST['CMedidorPrefactura']))?$POST['CMedidorPrefactura']:G_NINGUNO);

      foreach ($CheqProducto as $item => $check) {
        if($check=='on' || $check == '1'){
          $Cantidad = @(int)$TxtCantidad[$item];
          $Valor = @(float)$TxtValor[$item];
          $Total_Desc = @(float)(($TxtDescuento[$item]=="")?0:$TxtDescuento[$item]);
          $Total_Desc2 = @(float)(($TxtDescuento2[$item]=="")?0:$TxtDescuento2[$item]);
          if ($Cantidad > 0 && $Valor > 0){
            $Mifecha = PrimerDiaMes($MBFechaP[$item],'Ymd');
            $CodigoInv = @$DCProducto[$item];
            $CodigoInv = ($CodigoInv!="")?$CodigoInv:G_NINGUNO;
            for ($i=0; $i < $Cantidad; $i++) { 
              $NoMes = ObtenerMesFecha($Mifecha,'Ymd');
              $Anio = ObtenerAnioFecha($Mifecha,'Ymd');
              $peticionesDB++;
              $respuestaDB += $this->facturacion->insertClientes_FacturacionProductoClienteAnioMes($codigoCliente, $CodigoInv, $Valor, $GrupoNo, $NoMes, $Anio, $Mifecha, $Total_Desc, $Total_Desc2,G_NINGUNO,$medidor);
              $Mifecha = PrimerDiaSeguienteMes($Mifecha,'Ymd');     
            }
          }
        }
      }
      echo json_encode(array("rps" => 1 , "mensaje" => "PROCESO EXITOSO."));
    }else{
      echo json_encode(array("rps" => false , "mensaje" => "Debe seleccionar un cliente." ));
    }
  }

  public function getClientesMatriculas($codigoCliente)
  {
    return $this->facturacion->getClientesMatriculas($codigoCliente);
  }

  public function getcargarBancos($columnas, $id, $filtro, $limit)
  {
    return $this->facturacion->getBancos($columnas,$id, $filtro, $limit);
  }

  public function getCargarDireccionByGrupo($grupo)
  {
    return $this->facturacion->getDireccionByGrupo($grupo);
  }   

  public function getDCGrupo($query)
  {
    $datos = $this->facturacion->getDCGrupo($query);
    $lis = array();
    foreach ($datos as $key => $value) {
      $lis[] =array('id'=>$value['Grupo'],'text'=>$value['Grupo']);
    }
    return $lis;
  }

  public function ActualizaDatosCliente($post)
  {
    if($this->facturacion->Actualiza_Datos_Cliente($post)){
      return (array("rps" => 1 , "mensaje" => "PROCESO EXITOSO."));
    }else{
      return (array("rps" => 0 , "mensaje" => "No fue posible procesar su solicitud."));
    }
  }

  public function getMBHistorico()
  {
    $AdoAux = $this->facturas->FechaInicialHistoricoFacturas();
    @$AdoAux = (isset($AdoAux[0]["MinFecha"]))?$AdoAux[0]["MinFecha"]->format("Y-m-d"):"";
    $FechaInicial = (($AdoAux!="")?PrimerDiaMes($AdoAux,"Y-m-d"):"2000-01-01");

    return (array("MBHistorico" => $FechaInicial ));
  }

  public function getClienteCodigoMedidor($CMedidor){
    $dataCliente = $this->facturacion->BuscarClienteCodigoMedidor($CMedidor);
    if(count($dataCliente)>0){
      $data = $dataCliente[0];
      $ClienteFacturacion = $this->facturacion->getUltimoRegistroClientes_Facturacion($data['Codigo'], $CMedidor, "'".JG01."','".JG04."'");
      $DetalleFactura = $this->facturacion->getUltimoRegistroDetalleFactura($data['Codigo'], $CMedidor, "'".JG01."','".JG04."'");
      if (count($ClienteFacturacion) > 0 && ($ClienteFacturacion[0]['Periodo'] >= @$DetalleFactura[0]['Ticket'] && $ClienteFacturacion[0]['Num_Mes'] >= @$DetalleFactura[0]['Mes_No'])) {
        $data['ultimaMedida'] = $ClienteFacturacion[0]['Credito_No'];
        $data['fechaUltimaMedida'] = MesesLetras($ClienteFacturacion[0]['Num_Mes']) . "/" . $ClienteFacturacion[0]['Periodo'];
      } elseif (count($DetalleFactura) > 0) {
        $data['ultimaMedida'] = $DetalleFactura[0]['Corte'];
        $data['fechaUltimaMedida'] = MesesLetras($DetalleFactura[0]['Mes_No']) . "/" . $DetalleFactura[0]['Ticket'];
      } else {
        $data['ultimaMedida'] = $data['Acreditacion'];
        $data['fechaUltimaMedida'] = "";
      }

      $data['ultimaMedida'] = (is_numeric($data['ultimaMedida']))?$data['ultimaMedida']:0;
      return array('rps'=>true, 'data'=>$data);
    }else{
      return array('rps'=>false, 'mensaje'=>'Medidor no encontrado');
    }
  }

  public function GuardarConsumoAgua($parametros){
    extract($parametros);
    if(!isset($Lectura) || $Lectura==""){
      return (array("rps" => false , "mensaje" => "No se indico la lectura"));
    }

    if($CMedidor != "" && $CMedidor!="."){
      $dataCliente = @$this->getClienteCodigoMedidor($CMedidor)['data'];
      $LecturaAnterior = ((!is_null($dataCliente['ultimaMedida']) && is_numeric($dataCliente['ultimaMedida']))?$dataCliente['ultimaMedida']:0);
      //OBTENER CONSUMO ACTUAL
      if($Lectura<$LecturaAnterior){//si la lectura actual es menor que la anterior, se asume que el contador llego a 10000 y se reinicio
        $anterior = LIMITE_MEDIDOR-$LecturaAnterior;
        $consumoActual = $anterior+$Lectura;

      }else{
        $consumoActual = $Lectura-$LecturaAnterior; 
      }
      $rangoValores = $this->facturacion->getCatalogo_Cyber_Tiempo();
      if(count($rangoValores)<=0){
        return (array("rps" => false , "mensaje" => "No se ha configurado los rangos de valores para el calculo del excedente."));
      }
      $valorMinimo = ($rangoValores[0]['Desde'])-1;
      $excedente = (($consumoActual>$valorMinimo)?$consumoActual-$valorMinimo:0);
      $productos = $this->catalogoProductosModel->TVCatalogo("JG","P");

      $periodo = $this->facturacion->getPeriodoAbierto();
      if(count($periodo)>0){
        $dataperiodo = explode(" ", $periodo[0]['Detalle']);
        $NoMes = nombre_X_mes($dataperiodo[1]);
        $Anio = $dataperiodo[0];
        $Mifecha = "$Anio-$NoMes-01";
      }else{
        return (array("rps" => false , "mensaje" => "Debe activar el mes a procesar."));
      }

      if($dataCliente["fechaUltimaMedida"]==mes_X_nombre($NoMes)."/$Anio" || @$this->validarExisteLecturaREgistradaAnoMes($CMedidor, $codigoCliente, $Anio, $NoMes, JG01 )){
        return (array("rps" => false , "mensaje" => "Ya se registro la lectura para ".mes_X_nombre($NoMes)."/$Anio"));
      }

      $montoExcedente = 0;
      if($excedente>0){
        foreach ($rangoValores as $key => $rango) {
          $valorRango = calcularValorRango($rango['Desde'], $rango['Hasta']);
          if(($excedente-$valorRango) <= 0){
            $montoExcedente += $excedente*$rango['Valor'];
            break;
          }else{
            $excedente -= $valorRango;
            $montoExcedente += $valorRango*$rango['Valor'];
          }
        }
      }

      //insert consumo
      $clave = array_search(JG01, array_column($productos, "Codigo_Inv"));
      if ($clave !== false) {
        $productoConsumo = $productos[$clave];
        $this->facturacion->insertClientes_FacturacionProductoClienteAnioMes($codigoCliente, $productoConsumo['Codigo_Inv'], $productoConsumo['PVP'], G_NINGUNO, $NoMes, $Anio, $Mifecha, 0, 0, $Lectura,$CMedidor);

        //insert alcantarilado
        $clave = array_search(JG02, array_column($productos, "Codigo_Inv"));
        if ($clave !== false) {
          $productoAlcantarillado = $productos[$clave];
          $this->facturacion->insertClientes_FacturacionProductoClienteAnioMes($codigoCliente, $productoAlcantarillado['Codigo_Inv'], $productoAlcantarillado['PVP'], G_NINGUNO, $NoMes, $Anio, $Mifecha, 0, 0,G_NINGUNO,$CMedidor);
        }

        //insert excedente
        if($montoExcedente>0){
          $this->facturacion->insertClientes_FacturacionProductoClienteAnioMes($codigoCliente, JG03, $montoExcedente, G_NINGUNO, $NoMes, $Anio, $Mifecha, 0, 0,G_NINGUNO,$CMedidor);
        }
        return (array("rps" => true , "mensaje" => "Consumo registrado con exito."));
      }else{
        return (array("rps" => false , "mensaje" => "No se ha configurado el producto para guardar el consumo."));
      }
    }else{
      return (array("rps" => 0 , "mensaje" => "Debe indicar el medidor."));
    }
  }
  public function GuardarCambiosMedidorAgua($parametros)
  {
    extract($parametros);
    if($CMedidor != "" && $CMedidor!="."){
      $dataCliente = @$this->getClienteCodigoMedidor($CMedidor);
      if($dataCliente['rps']){
        if(isset($Encerar) && ($Encerar=='1' || $Encerar=='on')){
          $productos = $this->catalogoProductosModel->TVCatalogo("JG","P", false, JG04);
          if(count($productos)>0){

            $Mifecha = PrimerDiaMes(date('Ymd'),'Ymd');
            $periodo = $this->facturacion->getPeriodoAbierto();
            if(count($periodo)>0){
              $dataperiodo = explode(" ", $periodo[0]['Detalle']);
              $NoMes = nombre_X_mes($dataperiodo[1]);
              $Anio = $dataperiodo[0];
            }else{
              $NoMes = ObtenerMesFecha($Mifecha,'YmdHis');
              $Anio = ObtenerAnioFecha($Mifecha,'YmdHis');
            }

            $this->facturacion->insertClientes_FacturacionProductoClienteAnioMes($codigoCliente, $productos[0]['Codigo_Inv'], $productos[0]['PVP'], G_NINGUNO, $NoMes, $Anio, $Mifecha, 0, 0, 0,$CMedidor);
            return (array("rps" => true , "mensaje" => "Cambios guardados con exito."));
          }else{
            return (array("rps" => false , "mensaje" => "No se ha configurado el producto para Reinicio del Medidor."));
          }
        }
      }else{
        return (array("rps" => false , "mensaje" => "No se ha encontrado informacion del medidor."));
      }
    }else{
      return (array("rps" => 0 , "mensaje" => "Debe indicar el medidor."));
    }
  }

  public function validarExisteLecturaREgistradaAnoMes($cMedidor, $codigoCliente, $Anio, $NoMes, $Codigo_Inv )
  {
    return ($this->facturacion->AnyRegistroClientes_FacturacionAnoMes($codigoCliente, $cMedidor, $Codigo_Inv, $Anio, $NoMes))?true:    $this->facturacion->AnyRegistroDetalleFacturaAnoMes($codigoCliente, $cMedidor, $Codigo_Inv, $Anio, $NoMes);
  }

  public function TablaReporteConsumo($parametros)
  {
    extract($parametros);
    $sql = $this->getSqlReporteConsumoAgua($parametros);
    $tabla='';
    $medida = medida_pantalla($_SESSION['INGRESO']['Height_pantalla'])-144;
    $tabla = grilla_generica_new($sql,(($Tipo=='1')?'Clientes_Facturacion':'Detalle_Factura'),$id_tabla=false,"Reporte Consumo de Agua",$botones=false,$check=false,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,$medida,$num_decimales=2);
    return $tabla;
  }

  public function getSqlReporteConsumoAgua($parametros){
    extract($parametros);
    $Codigo_Auto = ($CMedidorFiltro!=G_NINGUNO)?str_pad($CMedidorFiltro, 6, "0", STR_PAD_LEFT):G_NINGUNO;
    
    switch ($Tipo) {
      case '2': //Facturado
        $sql = "SELECT DF.Mes, DF.Ticket As Periodo, DF.Factura, DF.Serie, F.Razon_Social, DF.Producto, DF.Tipo_Hab As Medidor, ROUND(Df.Corte,0) As Lectura, Df.Total As Valor
                FROM Detalle_Factura As DF, Facturas As F
                WHERE DF.Item = '".$_SESSION['INGRESO']['item']."'
                AND DF.Factura = F.Factura
                And DF.CodigoC = F.CodigoC
                AND DF.Tipo_Hab <> '.'
                AND DF.Codigo LIKE 'JG.%'
                ".(($codigoCliente!="" && $codigoCliente!=G_NINGUNO)?" AND DF.CodigoC = '".$codigoCliente."'":"")."

                ".(($fechai!="" && $fechai!=G_NINGUNO)?" AND DF.Fecha >= '".$fechai."'":"")."

                ".(($fechaf!="" && $fechaf!=G_NINGUNO)?" AND DF.Fecha <= '".$fechaf."'":"")."

                ".(($serie!="ALL")?" AND DF.Serie = '".$serie."'":"")."

                ".(($Codigo_Auto!=G_NINGUNO)?" AND DF.Tipo_Hab='$Codigo_Auto' ":"")."";
        break;
      
      default:
        $sql = "SELECT CF.Mes,CF.Periodo, C.Cliente, CP.Producto, CF.Codigo_Auto as Medidor, CF.Credito_No as Lectura,CF.Valor
          FROM Clientes_Facturacion As CF,Clientes As C, Catalogo_Productos As CP
          WHERE CF.Item = '".$_SESSION['INGRESO']['item']."'
          AND CF.Mes <> '.'
          AND CF.Codigo_Auto <> '.'
          AND CF.Codigo = C.Codigo
          AND CF.Codigo_Inv = CP.Codigo_Inv 
          AND CP.Codigo_Inv LIKE 'JG.%'
          ".(($codigoCliente!="" && $codigoCliente!=G_NINGUNO)?" AND CF.Codigo = '".$codigoCliente."'":"")."

          ".(($fechai!="" && $fechai!=G_NINGUNO)?" AND CF.Fecha >= '".$fechai."'":"")."

          ".(($fechaf!="" && $fechaf!=G_NINGUNO)?" AND CF.Fecha <= '".$fechaf."'":"")."

          ".(($Codigo_Auto!=G_NINGUNO)?" AND CF.Codigo_Auto='$Codigo_Auto' ":"")."

          ORDER BY CF.Periodo,CF.Num_Mes,CF.Codigo_Inv,CF.Credito_No";
        break;
    }
    return $sql;
  }

  public function generarExcelReporteConsumoAgua($parametros){
    $sql = $this->getSqlReporteConsumoAgua($parametros);
    extract($parametros);
    return exportar_excel_generico_SQl("Reporte Consumo de Agua - ".(($Tipo=='2')?'Facturado':'Prefacturas'),$sql);
  }

  public function generarPdfReporteConsumoAgua($parametros){
    $sql = $this->getSqlReporteConsumoAgua($parametros);
    extract($parametros);
    $result = $this->facturacion->SelectDatos($sql);
    $campos = array();
    foreach ($result[0] as $key => $value) {
      array_push($campos,$key);
    }
    $ali =array();
    $medi =array();
    foreach ($campos as $key => $value) {
      switch ($value) {
        case 'Mes':
          $ali[$key] = 'L';
          $medi[$key] =18;
          break;
        case 'Periodo':
          $ali[$key] = 'L';
          $medi[$key] =15;
          break;
        case 'Cliente':
          $ali[$key] = 'L';
          $medi[$key] =55;
          break;
        case 'Razon_Social':
          $ali[$key] = 'L';
          $medi[$key] =45;
          break;
        case 'Producto':
          $ali[$key] = 'L';
          $medi[$key] =40;
          break;
        
        default:
          $val =  strlen(trim($value));
          if($val != 2){
            $ali[$key] = is_numeric($val) ? 'R':'L';
            $medi[$key] = $val*2.5;
          }else{
            $ali[$key] = is_numeric($val) ? 'R':'L';
            $medi[$key] = $val*4;
          }
          break;
      }
    }

    $pdf = new cabecera_pdf();  
    $titulo = "Reporte Consumo de Agua - ".(($Tipo=='2')?'Facturado':'Prefacturas');
    $mostrar = true;
    $sizetable =8;
    $tablaHTML = array();
    $tablaHTML[0]['medidas']=$medi;
    $tablaHTML[0]['alineado']=$ali;
    $tablaHTML[0]['datos']=$campos;
    $tablaHTML[0]['estilo']='BI';
    $tablaHTML[0]['borde'] = '1';
    $pos = 1;
    foreach ($result as $key => $value) {
      $datos = array();
      foreach ($value as $key1 => $valu) {
        if($key1=="Lectura" && $valu!=G_NINGUNO){
          array_push($datos, number_format($valu, 0, '.', ''));
        }else
        if ( $key1!="Medidor" && $key1!="Lectura" && $key1!="Periodo" && $key1!="Factura" && $key1!="Serie" && (is_int($valu) || is_numeric($valu))) {
          array_push($datos, number_format($valu, 2, '.', ''));
        } else {
          array_push($datos, $valu);
        }
      }
      $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
      $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
      $tablaHTML[$pos]['datos']=$datos;
      $tablaHTML[$pos]['estilo']='I';
      $tablaHTML[$pos]['borde'] = '1';
      $pos = $pos+1;
    }
    $pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$fechai,$fechaf,$sizetable,$mostrar);
  }

  public function SeriesFacturaConsumo($parametros)
  {
    $sql ="SELECT DF.Serie
            FROM Detalle_Factura As DF
            WHERE DF.Item = '".$_SESSION['INGRESO']['item']."'
            AND DF.Tipo_Hab <> '.'
            AND DF.Codigo LIKE 'JG.%'
            Group by DF.Serie";
    return $this->facturacion->SelectDatos($sql);
  }
}
?>