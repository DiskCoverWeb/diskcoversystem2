<?php 
require(dirname(__DIR__,2).'/modelo/facturacion/notas_creditoM.php');
require_once(dirname(__DIR__,3)."/lib/fpdf/cabecera_pdf.php");

$controlador = new notas_creditoC();
if(isset($_GET['delete_sientos_nc']))
{
	echo json_encode($controlador->delete_sientos_nc());
}

if(isset($_GET['DCBodega']))
{
	echo json_encode($controlador->DCBodega());
}

if(isset($_GET['DCMarca']))
{
	echo json_encode($controlador->DCMarca());
}

if(isset($_GET['generar_pdf']))
{
	echo json_encode($controlador->generar_pdf());
}
if(isset($_GET['DCContraCta']))
{
	$q = '';
	if(isset($_GET['q'])){ $q = $_GET['q'];}
	echo json_encode($controlador->DCContraCta($q));
}

if(isset($_GET['DCArticulo']))
{
	$q = '';
	if(isset($_GET['q'])){ $q = $_GET['q'];}
	echo json_encode($controlador->DCArticulo($q));
}

if(isset($_GET['tabla']))
{
	$parametros = array();
	echo json_encode($controlador->cargar_tabla($parametros));
}

if(isset($_GET['DCLineas']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCLineas($parametros));
}
if(isset($_GET['numero_autorizacion']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->numero_autorizacion($parametros));
}

if(isset($_GET['DCTC']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCTC($parametros));
}

if(isset($_GET['DCSerie']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCSerie($parametros));
}

if(isset($_GET['Detalle_Factura']))
{
	$parametros = $_POST['parametros'];
	// print_r($parametros);die();
	echo json_encode($controlador->Detalle_Factura($parametros));
}

if(isset($_GET['Lineas_Factura']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->Lineas_Factura($parametros));
}

if(isset($_GET['DCFactura']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCFactura($parametros));
}

if(isset($_GET['cliente']))
{
	$q = '';
	$serie = '';
	if(isset($_GET['q'])){ $q = $_GET['q'];}
	if(isset($_GET['serie'])){ $serie = $_GET['serie'];}
	echo json_encode($controlador->Listar_Facturas_Pendientes_NC($q,$serie));
}

if(isset($_GET['guardar']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar($parametros));
}

if(isset($_GET['eliminar_linea']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->eliminar_linea($parametros));
}

if(isset($_GET['generar_nota_credito']))
{
	$parametros = $_POST;
	echo json_encode($controlador->generar_nota_credito($parametros));
}

/**
 * 
 */
class notas_creditoC
{
	private $modelo;	
	private $sri;
	function __construct()
	{

		$this->modelo = new notas_creditoM(); 
		$this->sri = new autorizacion_sri(); 
		// code...
	}

	function DCBodega()
	{
		$datos =  $this->modelo->catalogo_bodega();
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('codigo'=>$value['CodBod'],'nombre'=>$value['Bodega']);
		}
		return $list;
	}

	function DCMarca()
	{
		$datos =  $this->modelo->catalogo_marca();
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('codigo'=>$value['CodMar'],'nombre'=>$value['Marca']);
		}
		return $list;
	}

	function DCContraCta($query)
	{
		$datos =  $this->modelo->Catalogo_Cuentas($query);
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('id'=>$value['Codigo'],'text'=>$value['NomCuenta'],'data'=>$value);
		}
		return $list;
	}

	function DCArticulo($query)
	{
		$datos =  $this->modelo->Catalogo_Productos($query);
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('id'=>$value['Codigo_Inv'],'text'=>$value['Producto'],'data'=>$value);
		}
		return $list;
	}

	function cargar_tabla($parametro)
	{
		$IVA_NC = 0;
		$Total_Con_IVA = 0;
		$Total_Desc2  = 0;
		$Total_Sin_IVA  = 0;
		$Total_Desc = 0;
		$SubTotal_NC = 0;

		 $table = $this->modelo->cargar_tabla($parametro,$tabla=1);
		 $totales = $this->modelo->cargar_tabla($parametro);

		 foreach ($totales as $key => $value) {
		 		  if($value["TOTAL_IVA"] > 0 ){
               $IVA_NC = $IVA_NC + $value["TOTAL_IVA"];
               $Total_Con_IVA = $Total_Con_IVA + $value["SUBTOTAL"];
               $Total_Desc2 = $Total_Desc2 + $value["DESCUENTO"];
           }else{
               $Total_Sin_IVA = $Total_Sin_IVA + $value["SUBTOTAL"];
               $Total_Desc = $Total_Desc + $value["DESCUENTO"];
           }
           $SubTotal_NC = $SubTotal_NC + $value["SUBTOTAL"];
		 }

		return  array('tabla'=>$table,'TxtIVA'=>$IVA_NC,'TxtConIVA'=>$Total_Con_IVA,'TxtDescuento'=>$Total_Desc2+$Total_Desc,'TxtSinIVA'=>$Total_Sin_IVA,'TxtSaldo'=>$SubTotal_NC,'LblTotalDC'=>$SubTotal_NC+$IVA_NC - ($Total_Desc + $Total_Desc2) );
	}

	function Listar_Facturas_Pendientes_NC($q,$serie)
	{
		$datos = $this->modelo->Listar_Facturas_Pendientes_NC($q,$serie);
		$cli = array();	
		foreach ($datos as $key => $value) {
			$cli[] = array('id'=>$value['Codigo'],'text'=>$value['Cliente'],'data'=>$value);
		}
		return $cli;
	}

	function DClineas($parametro)
	{
		// print_r($parametro);die();
		$datos = $this->modelo->DClineas($parametro['fecha']);
		$list = array();		
		foreach ($datos as $key => $value) {
			$list[] = array('codigo'=>$value['Serie'],'nombre'=>$value['Concepto'],'Autorizacion'=>$value['Autorizacion']); 
		}
		if(count($list)==0)
		{
			$list[] = array('codigo'=>'','nombre'=>'No existen datos');	
		}
		return $list;
	}


	function numero_autorizacion($parametro)
	{
		 $numero  = ReadSetDataNum("NC_SERIE_".$parametro['serie'], True, False);
		 return $numero;

	}

	function DCTC($parametro)
	{
		// print_r($parametro);die();
		$datos = $this->modelo->DCTC($parametro['CodigoC']);
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('codigo'=>$value['TC'],'nombre'=>$value['TC']); 
		}
		return $list;
	}

	function DCSerie($parametro)
	{
		$datos = $this->modelo->DCSerie($parametro['TC'],$parametro['CodigoC']);
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('codigo'=>$value['Serie'],'nombre'=>$value['Serie']); 
		}
		return $list;
	}

	function DCFactura($parametro)
	{
		// print_r($parametro);die();
		$factura = false;
		if(isset($parametro['Factura']))
		{
			$factura = $parametro['Factura'];
		}
		$datos = $this->modelo->DCFactura($parametro['Serie'],$parametro['TC'],$parametro['CodigoC'],$factura);
		$list = array();
		foreach ($datos as $key => $value) {
			$detalle = $this->modelo->DCFacturaAll($parametro['Serie'],$parametro['TC'],$parametro['CodigoC'],$value['Factura']);
			$list[] = array('codigo'=>$value['Factura'],'nombre'=>$value['Factura'],'data'=>$detalle[0]); 
		}
		// print_r($list);die();
		return $list;
	}

	function Detalle_Factura($parametro)
	{
		return $this->modelo->Factura_detalle($parametro['Factura'],$parametro['Serie'],$parametro['TC'],$parametro['CodigoC']);
	}


	function delete_sientos_nc()
	{
		return $this->modelo->delete_asiento_nc();
	}

	function Lineas_Factura($parametros)
	{
		// print_r($parametro);die();
     $DocConInv = false;
     $Ln_No = 0;
     $this->modelo->delete_asiento_nc();
    
     $datos = $this->modelo->lineas_factura($parametros['Factura'],$parametros['Serie'],$parametros['TC'],$parametros['Autorizacion']);
      //print_r($datos);die();     
      if(count($datos)  > 0)
      {
          // $FA.Cod_Ejec = .fields("Cod_Ejec")
          // $FA.Porc_C = .fields("Porc_C")
          $NoMes = $datos[0]["Mes_No"];
          $MiMes = $datos[0]["Mes"];
          $Cod_Bodega = $datos[0]["CodBodega"];
          foreach ($datos as $key => $value) 
          {
             $Ok_Inv = Leer_Codigo_Inv($value["Codigo"], $parametros['Fecha']);
              
              SetAdoAddNew("Asiento_NC");
              SetAdoFields("CODIGO", $value["Codigo"]);
							SetAdoFields("CANT", $value["Cantidad"]);
							SetAdoFields("PRODUCTO", $value["Producto"]);
							SetAdoFields("SUBTOTAL", $value["Total"]);
							SetAdoFields("DESCUENTO", $value["Total_Desc"] + $value["Total_Desc2"]);
							SetAdoFields("TOTAL_IVA", $value["Total_IVA"]);
							SetAdoFields("CodBod", $value["CodBodega"]);
							SetAdoFields("CodMar", $value["CodMarca"]);
							SetAdoFields("Codigo_C", $parametros["CodigoC"]);
							SetAdoFields("Item", $_SESSION['INGRESO']['item']);
							SetAdoFields("CodigoU", $_SESSION['INGRESO']['CodigoU']);
							SetAdoFields("PVP", $value["Precio"]);
							SetAdoFields("COSTO", $Ok_Inv['datos']['Costo']);
							SetAdoFields("Cod_Ejec", $value["Cod_Ejec"]);
							SetAdoFields("Porc_C", $value["Porc_C"]);
							SetAdoFields("Porc_IVA", $value["Porc_IVA"]);
							SetAdoFields("Mes_No", $value["Mes_No"]);
							SetAdoFields("Mes", $value["Mes"]);
							SetAdoFields("Anio", $value["Ticket"]);
							SetAdoFields("A_No", $Ln_No);

							if ($Ok_Inv['datos']['Con_Kardex']) {
							  SetAdoFields("Ok", $Ok_Inv['datos']['Con_Kardex']);
							  SetAdoFields("Cta_Inventario", $Ok_tInv['Cta_Inventario']);
							  SetAdoFields("Cta_Costo", $Ok_tInv['Cta_Costo_Venta']);
							}
 							SetAdoUpdate();
     
             $Ln_No = $Ln_No + 1;
             // DocConInv = DatInv.Con_Kardex
          }
      }

     // Listar_Articulos_Malla
     // If DocConInv Then DCBodega.SetFocus Else DGAsiento_NC.SetFocus
	}

	function guardar($parametros)
	{
		// print_r($parametros);die();
		$SubTotalDesc = 0;
    	$SubTotalIVA = 0;
		$SubTotal_NC = $parametros['Saldo'];
		$IVA_NC = $parametros['IVA'];
		$Total_Desc = $parametros['Descuento'];


		$lista = $this->modelo->cargar_tabla($parametros,false);
		$totalAsientosNC = 0;
		// lineas_factura($parametros['Factura'],$parametros['Serie'],$parametros['TC'],$parametros['Autorizacion']);
		foreach ($lista as $key => $value) {
			
			$totalAsientosNC = $totalAsientosNC + number_format($value['SUBTOTAL']);
		}
		

			$Ln_No  = count($lista)+1;
		if($parametros['TextCant'] > 0 &&  $parametros['TextVUnit'] > 0 ){
       $SubTotalDesc = $parametros['TextDesc'];
       $SubTotal = number_format($parametros['TextCant'] * $parametros['TextVUnit'],2,'.','');
       $product = Leer_Codigo_Inv($parametros['productos'],$parametros['MBoxFecha']);
       $BanIVA = $product['datos']['IVA'];
       if($BanIVA==1 && $parametros['TC'] <> "NV"){ $SubTotalIVA = number_format(($SubTotal-$SubTotalDesc)*$parametros['IVAPor'], 4,'.','');}
       $Total = $SubTotal_NC + $SubTotal + $IVA_NC + $SubTotalIVA - $SubTotalDesc - $Total_Desc;
      if($parametros['TotalDC']+$Total > $parametros['TotalFA'] )
		{
			return -4;
		}
       SetAdoAddNew("Asiento_NC");
       SetAdoFields("CODIGO", $parametros["productos"]);
			 SetAdoFields("CANT", $parametros["TextCant"]);
			 SetAdoFields("PRODUCTO", $parametros["productosName"]);
			 SetAdoFields("SUBTOTAL", $SubTotal);
			 SetAdoFields("DESCUENTO", $SubTotalDesc);
			 SetAdoFields("TOTAL_IVA", $SubTotalIVA);
			 SetAdoFields("CodBod", $parametros["Cod_Bodega"]);
			 SetAdoFields("CodMar", $parametros["Cod_Marca"]);
			 SetAdoFields("Codigo_C", $parametros["CodigoC"]);
			 SetAdoFields("Item", $_SESSION["INGRESO"]["item"]);
			 SetAdoFields("CodigoU", $_SESSION["INGRESO"]["CodigoU"]);
			 SetAdoFields("PVP", number_format($parametros["TextVUnit"], $_SESSION["INGRESO"]["Dec_PVP"], ".", ""));
			 SetAdoFields("COSTO", $product["datos"]["Costo"]);
			 SetAdoFields("Mes_No", date("m", strtotime($parametros["MBoxFecha"])));
			 SetAdoFields("Mes", MesesLetras(date("m", strtotime($parametros["MBoxFecha"]))));
			 SetAdoFields("Anio", date("Y", strtotime($parametros["MBoxFecha"])));
			 SetAdoFields("Porc_IVA", $_SESSION["INGRESO"]["porc"]);
			 SetAdoFields("A_No", $Ln_No);
 
			 if ($product["datos"]["Con_Kardex"]) {
			   SetAdoFields("Ok", $product["datos"]["Con_Kardex"]);
			   SetAdoFields("Cta_Inventario", $product["datos"]["Cta_Inventario"]);
			   SetAdoFields("Cta_Costo", $product["datos"]["Cta_Costo_Venta"]);
			 }
 			 return SetAdoUpdate();
		}else
		{
			 return -1;
		}
	}

	function eliminar_linea($parametros)
	{
		// print_r($parametros);die();
		return  $this->modelo->delete_asientonNC($parametros['codigo'],$parametros['a_no']);
	}

	function generar_nota_credito($parametros)
	{


		// print_r($parametros);die();
		$FA = array();
		$SubTotalCosto = 0;
		$Grupo = '';
		$FA['Serie_NC'] = $parametros['TextCheqNo'];
		$FA['Serie'] = $parametros['DCSerie'];
		$FA['TC'] = $parametros['DCTC'];
		$FA['Factura'] = $parametros['DCFactura'];	    
		$FA['Nota_Credito'] = $parametros['TextCompRet'];
		$FA['Autorizacion_NC'] = $parametros['TextBanco'];
		$FA['Autorizacion'] = $parametros['TxtAutorizacion'];
    $FA['CodigoC'] = $parametros['DCClientes'];
    $FA['Cliente'] = $parametros['Cliente'];
    $cliente_cta =  $this->modelo->Listar_Facturas_Pendientes_NC($parametros['Cliente']);
    $FA['Cta_CxP'] = $cliente_cta[0]['Cta_CxP'];
    $FA['Nota'] = $parametros['TxtConcepto'];

		$this->modelo->delete_Detalle_Nota_Credito($FA['Serie_NC'],$FA['Nota_Credito']);

		$FAC = $this->modelo->Factura_detalle($parametros['DCFactura'],$parametros['DCSerie'],$parametros['DCTC']);
	  $FA['T'] = $FAC[0]["T"];
    $FA['Fecha'] = $FAC[0]["Fecha"];
    $FA['Cta_CxP'] = $FAC[0]["Cta_CxP"];
    $FA['Cod_CxC'] = $FAC[0]["Cod_CxC"];
    $FA['Porc_IVA'] = $FAC[0]["Porc_IVA"];
    $FA['Total_MN'] = $FAC[0]["Total_MN"];
    $FA['Saldo_MN'] = $FAC[0]["Saldo_MN"];
    $FA['Autorizacion'] = $FAC[0]["Autorizacion"];
    $FA['Descuento'] = $FAC[0]["Descuento"];
    $FA['IVA'] = $FAC[0]["IVA"];
    if($FAC[0]["IVA"] > 0){ $FA['Porc_NC'] = $FAC[0]["Porc_IVA"];}

		$MBoxFecha = $parametros['MBoxFecha'];

		$IVA_NC = 0;
		$Total_Con_IVA = 0;
		$Total_Desc2  = 0;
		$Total_Sin_IVA  = 0;
		$Total_Desc = 0;
		$SubTotal_NC = 0;

		 $totales = $this->modelo->cargar_tabla($parametros);
		 foreach ($totales as $key => $value) {
		 		  if($value["TOTAL_IVA"] > 0 ){
               $IVA_NC = $IVA_NC + $value["TOTAL_IVA"];
               $Total_Con_IVA = $Total_Con_IVA + $value["SUBTOTAL"];
               $Total_Desc2 = $Total_Desc2 + $value["DESCUENTO"];
           }else{
               $Total_Sin_IVA = $Total_Sin_IVA + $value["SUBTOTAL"];
               $Total_Desc = $Total_Desc + $value["DESCUENTO"];
           }
           $SubTotal_NC = $SubTotal_NC + $value["SUBTOTAL"];
		 }

		    	// print_r($parametros);die();
		    if( floatval($parametros['LblTotalDC']) <= floatval($parametros['LblSaldo']))
		    {
		       if($parametros['ReIngNC']==0){ $FA['Nota_Credito'] = ReadSetDataNum("NC_SERIE_".$FA['Serie_NC'],True,True); }
		        $FA['Fecha_NC'] = $MBoxFecha;
		        $Contra_Cta = $parametros['DCContraCta'];
		        if(strlen($Contra_Cta) <= 1 ){ $Contra_Cta = ReadAdoCta("Cta_Devolucion_Ventas"); }
		        $Listar_Articulos_Malla = $this->modelo->cargar_tabla($parametros,$tabla = false);		        
		        Actualiza_Procesado_Kardex_Factura($FA);
		        
		        //$resp = $this->modelo->delete_Detalle_Nota_Credito($FA['Serie_NC'],$FA['Nota_Credito']);
		        
		        $FA['ClaveAcceso_NC'] = G_NINGUNO;
		        $FA['SubTotal_NC'] = 0;
		        $FA['Total_IVA_NC'] = 0;
		        $FA['Descuento_NC'] = 0;
		        $Cantidad = 0;
		        if(strlen($FA['Autorizacion_NC']) >= 13 ){ $TMail['TipoDeEnvio'] = "CE"; }

		        foreach ($Listar_Articulos_Malla  as $key => $value) 
		        {		        	
              $FA['SubTotal_NC'] = $FA['SubTotal_NC']+ $value["SUBTOTAL"];
              $FA['Total_IVA_NC'] = $FA['Total_IVA_NC']+ $value["TOTAL_IVA"];
              $FA['Descuento_NC'] = $FA['Descuento_NC']+ $value["DESCUENTO"];
              $SubTotalCosto = number_format(($value["SUBTOTAL"] / $value["CANT"]), 6,'.','');
              // 'SubTotal = Redondear(.Fields("CANT") * SubTotalCosto, 2)
              $SubTotal = number_format($value["CANT"] * $value["COSTO"], 2,'.','');
              
              // 'Grabamos el detalle de la NC
              // 'Cta_Devolucion, , Porc_IVA,
              SetAdoAddNew("Detalle_Nota_Credito");
              SetAdoFields("T", G_NORMAL);
							SetAdoFields("CodigoC", $value["Codigo_C"]);
							SetAdoFields("Cta_Devolucion", $Contra_Cta);
							SetAdoFields("Fecha", $FA["Fecha_NC"]);
							SetAdoFields("Serie", $FA["Serie_NC"]);
							SetAdoFields("Secuencial", $FA["Nota_Credito"]);
							SetAdoFields("Autorizacion", $FA["Autorizacion_NC"]);
							SetAdoFields("Codigo_Inv", $value["CODIGO"]);
							SetAdoFields("Cantidad", $value["CANT"]);
							SetAdoFields("Producto", $value["PRODUCTO"]);
							SetAdoFields("CodBodega", $value["CodBod"]);
							SetAdoFields("Total_IVA", $value["TOTAL_IVA"]);
							SetAdoFields("Precio", $value["PVP"]);
							SetAdoFields("Total", $value["SUBTOTAL"]);
							SetAdoFields("CodMar", $value["CodMar"]);
							SetAdoFields("Cod_Ejec", $value["Cod_Ejec"]);
							SetAdoFields("Porc_C", $value["Porc_C"]);
							SetAdoFields("Porc_IVA", $value["Porc_IVA"]);
							SetAdoFields("Mes_No", $value["Mes_No"]);
							SetAdoFields("Mes", $value["Mes"]);
							SetAdoFields("Anio", $value["Anio"]);
							SetAdoFields("TC", $FA["TC"]);
							SetAdoFields("Serie_FA", $FA["Serie"]);
							SetAdoFields("Factura", $FA["Factura"]);
							SetAdoFields("Item", $_SESSION["INGRESO"]["item"]);
							SetAdoFields("Periodo", $_SESSION["INGRESO"]["periodo"]);
							SetAdoFields("CodigoU", $_SESSION["INGRESO"]["CodigoU"]);
							SetAdoFields("A_No", $key + 1);
								SetAdoUpdate();

             // 'Grabamos en el Kardex la factura
              if($value["Ok"])
              {
              	SetAdoAddNew("Trans_Kardex");
              	SetAdoFields("T", G_NORMAL);
								SetAdoFields("TP", G_NINGUNO);
								SetAdoFields("Numero", '0');
								SetAdoFields("TC", $FA["TC"]);
								SetAdoFields("Serie", $FA["Serie"]);
								SetAdoFields("Fecha", $FA["Fecha_NC"]);
								SetAdoFields("Factura", $FA["Factura"]);
								SetAdoFields("Codigo_P", $FA["CodigoC"]);
								SetAdoFields("CodigoL", $FA["Cod_CxC"]);
								SetAdoFields("Codigo_Inv", $value["CODIGO"]);
								SetAdoFields("Total_IVA", $value["TOTAL_IVA"]);
								SetAdoFields("Entrada", $value["CANT"]);
								SetAdoFields("PVP", $value["PVP"]);
								SetAdoFields("Valor_Unitario", $value["COSTO"]);
								SetAdoFields("Costo", $value["COSTO"]);
								SetAdoFields("Valor_Total", number_format($value["CANT"] * $value["COSTO"], 2, '.', ''));
								SetAdoFields("Total", number_format($value["CANT"] * $value["COSTO"], 2, '.', ''));
								SetAdoFields("Descuento", $value["DESCUENTO"]);
								SetAdoFields("Detalle", "NC:" . $FA["Serie_NC"] . "-" . generaCeros($FA["Nota_Credito"], 9) . "-" . $FA["Cliente"]);
								SetAdoFields("Cta_Inv", $value["Cta_Inventario"]);
								SetAdoFields("Contra_Cta", $value["Cta_Costo"]);
								SetAdoFields("CodBodega", $value["CodBod"]);
								SetAdoFields("CodMarca", $value["CodMar"]);
								SetAdoFields("Item", $_SESSION["INGRESO"]["item"]);
								SetAdoFields("Periodo", $_SESSION["INGRESO"]["periodo"]);
								SetAdoFields("CodigoU", $_SESSION["INGRESO"]["CodigoU"]);
									SetAdoUpdate();
              }
		        }

		        $TA['T'] = G_NORMAL;
		        $TA['TP'] = $FA['TC'];
		        $TA['Serie'] = $FA['Serie'];
		        $TA['Factura'] = $FA['Factura'];
		        $TA['Autorizacion'] = $FA['Autorizacion'];
		        $TA['Fecha'] = $MBoxFecha;
		        $TA['CodigoC'] = $FA['CodigoC'];
		        $TA['Cta_CxP'] = $FA['Cta_CxP'];
		        $TA['Cta'] = $Contra_Cta;
		        
		        $TA['Serie_NC'] = $FA['Serie_NC'];
		        $TA['Autorizacion_NC'] = $FA['Autorizacion_NC'];
		        $TA['Nota_Credito'] = $FA['Nota_Credito'];
		        
		        $TA['Banco'] = "NOTA DE CREDITO";
		        $TA['Cheque'] = "VENTAS SIN IVA";
		        $TA['Abono'] = $Total_Sin_IVA - $Total_Desc;
		        Grabar_Abonos($TA);

		        
		        $TA['Banco'] = "NOTA DE CREDITO";
		        $TA['Cheque'] = "VENTAS CON IVA";
		        $TA['Abono'] = $Total_Con_IVA - $Total_Desc2;
		        Grabar_Abonos($TA);

		        $Cta_IVA = $_SESSION['SETEOS']['Cta_IVA'];	        
		        $TA['Cta'] = $Cta_IVA;
		        $TA['Banco'] = "NOTA DE CREDITO";
		        $TA['Cheque'] = "I.V.A.";
		        $TA['Abono'] = $FA['Total_IVA_NC'];

		        // print_r($TA);die();
		        Grabar_Abonos($TA);


		        if( $parametros['TxtConcepto'] == ""){ $parametros['TxtConcepto'] = G_NINGUNO;}
		        
		        $resp = $this->modelo->Actualizar_facturas_trans_abonos($parametros['TxtConcepto'] ,$FA);


		        if(($FA['SubTotal_NC'] + $FA['Total_IVA_NC']) > 0 && strLen($FA['Autorizacion_NC']) >= 13)
		        { 

		        	  $resp = $this->sri->SRI_Crear_Clave_Acceso_Nota_Credito($FA); 

		        	  // crea pdf
		        	  $this->modelo->pdf_nota_credito($FA);
		        	  $clave = $this->sri->Clave_acceso($FA['Fecha_NC'],'04',$FA['Serie_NC'],$FA['Nota_Credito']);		        	 
		        	 return array('respuesta'=>$resp,'pdf'=>$FA['Serie_NC'].'-'.generaCeros($FA['Nota_Credito'],7),'clave'=>$clave);
		  			}

			        $Ln_No = 0;
			        $this->modelo->delete_asientonNC();		

			        // hay que generar esta funcion o proceso almacenado        
			        // Actualizar_Saldos_Facturas_SP($FA['TC'],$FA['Serie'],$FA['Factura']);

			        return array('respuesta'=>1,'pdf'=>$FA['Serie_NC'].'-'.generaCeros($FA['Nota_Credito'],7),'clave'=>'');

			        // esto pasasr avista
			        
			        // Listar_Facturas_Pendientes_NC();
			        // Listar_Articulos_Malla
			        // RatonNormal
			        // MsgBox "Proceso Terminado con éxito"
			        // MBoxFecha.SetFocus
				    
			}else
			{
				return array('respuesta'=>5);
			}
	}

	function generar_pdf()
	{
	/*	$TFA['TC'] = 'NC';
		$TFA['Serie'] = '001003';
		$TFA['Autorizacion'] = '0604202201070216417900110010030000006691234567814';
		$TFA['Factura'] = '669';
		$TFA['Serie_NC'] = '001003';
		$TFA['Nota_Credito'] = '71';
		$TFA['CodigoC'] = '1792558662';

		$TFA['Fecha_NC'] = '2012-12-03';

		$TFA['Fecha'] = '2012-01-03';
		$TFA['Autorizacion_NC'] = '0902202304070216417900110010030000000711234567818';
		$TFA['ClaveAcceso_NC']  = '0902202304070216417900110010030000000711234567818';
		$TFA['Porc_IVA'] = '12';
		$TFA['Descuento']=0;
		$TFA['Descuento2'] = 0;
		$TFA['IVA'] = '0';
		$TFA['Total_MN'] = 0;
		$TFA['Nota'] = '- Nota de Crédito de: VACA PRIETO WALTER JALIL';

*/
		//$FA['Autorizacion_NC'] = $parametros['TextBanco'];
		//

		

		 $this->modelo->pdf_nota_credito($TFA);
	}
		        	 
}
?>