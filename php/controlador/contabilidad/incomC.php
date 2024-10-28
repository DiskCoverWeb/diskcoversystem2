<?php 
include(dirname(__DIR__,2).'/modelo/contabilidad/incomM.php');
// include(dirname(__DIR__,2).'/comprobantes/SRI/autorizar_sri.php');
date_default_timezone_set('America/Guayaquil'); 
/**
 * 
 */
$controlador = new incomC();
if(isset($_GET['beneficiario']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->cargar_beneficiario($query));
}
if(isset($_GET['beneficiario_C']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->cargar_beneficiario_C($query));
}
if(isset($_GET['beneficiario_p']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->cargar_beneficiario_pro($query));
}
if(isset($_GET['cuentas_efectivo']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->cuentas_efectivo($query));
}
if(isset($_GET['ListarAsientoB']))
{
	echo json_encode($controlador->ListarAsientoB());
}
if(isset($_GET['cuentas_banco']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->cuentas_banco($query));
}
if(isset($_GET['cuentasTodos']))
{
	$query = '';
    $clave='';
    $tc = '';
    $codigo = '';
    if(isset($_GET['q']['term']))
    {
    	$query  = $_GET['q']['term'];
    }
    $cta = $_GET['tip'];
	// print_r($cta);die();
	if(is_numeric($cta))
	{
		if(strpos($cta,'.')!==false )
    	{
    		$codigo = $cta;
    	}else{
			$clave = $cta;
		}
	}else if(ctype_alpha($cta))
	{
		// tipo de cuenta
		$tc = $cta;
	}else
	{
		$codigo = $cta;
	}

    $parametros = array('query'=>$query,'codigo'=>$codigo,'clave'=>$clave,'tc'=>$tc);
	echo json_encode($controlador->cuentas_Todos($parametros));
}

if(isset($_GET['asientoB']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->InsertarAsientoBanco($parametros));
}
if(isset($_GET['EliAsientoB']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->delete_asientoB($parametros));
}
if(isset($_GET['EliAsientoBTodos']))
{
	echo json_encode($controlador->delete_asientoBTodos());
}
if(isset($_GET['tabs_contabilidad']))
{
	echo json_encode($controlador->cargar_tablas());
}
if(isset($_GET['tabs_sc']))
{
	echo json_encode($controlador->cargar_tablas_sc());
}
if(isset($_GET['tabs_sc_modal']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_tablas_sc_modal($parametros));
}

if(isset($_GET['tabs_retencion']))
{
	echo json_encode($controlador->cargar_tablas_retencion());
}
if(isset($_GET['tabs_tab4']))
{
	echo json_encode($controlador->cargar_tablas_tab4());
}
if(isset($_GET['subcuentas']))
{
	$parametros = $_POST['parametros'];
	echo json_decode($controlador->listar_subcuentas($parametros));
}
if(isset($_GET['TipoCuenta']))
{
	$codigo = $_POST['codigo'];
	echo json_encode($controlador->LeerCta($codigo));
}

if(isset($_GET['modal_generar_sc']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->modal_generar_asiento_SC($parametros));
}
if(isset($_GET['modal_ingresar_asiento']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->modal_ingresar_asiento($parametros));
}
if(isset($_GET['modal_limpiar_asiento']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->modal_subcta_limpiar($parametros));
}
if(isset($_GET['eliminar_retenciones']))
{
    echo json_encode($controlador->eliminar_retenciones());
}
if(isset($_GET['modal_detalle_aux']))
{
	// print_r($_POST['q']);die();
	if(!isset($_POST['q'])){$_POST['q'] = '';}
	$parametros = array(
    		'tc'=>$_GET['tc'],
    		'OpcDH'=>$_GET['OpcDH'],
    		'OpcTM'=>$_GET['OpcTM'],
    		'cta'=>$_GET['cta'],
    		'query'=>$_POST['q']);
	echo json_encode($controlador->detalle_aux_submodulo($parametros));
}

if(isset($_GET['modal_subcta_catalogo']))
{

	if(!isset($_GET['q']))
	{
		$_GET['q'] = '';
	}

	$parametros = array(
    		'tc'=>$_GET['tc'],
    		'OpcDH'=>$_GET['OpcDH'],
    		'OpcTM'=>$_GET['OpcTM'],
    		'cta'=>$_GET['cta'],
    		'query'=>$_GET['q']);
	echo json_encode($controlador->catalogo_subcta($parametros));
	
}
if(isset($_GET['modal_subcta_cta']))
{
	if(!isset($_GET['q']))
	{
		$_GET['q'] = '';
	}
	$parametros = array('tc'=>$_GET['tc'],
						'nivel'=>$_GET['nivel'],
		    			'query'=>$_GET['q']);
	echo json_encode($controlador->catalogo_subcta2($parametros));
	
}
if(isset($_GET['totales_asientos']))
{
	// $parametros = $_POST['parametros'];
	echo json_encode($controlador->datos_de_asientos());
}
if(isset($_GET['generar_comprobante']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->generar_comprobante($parametros));
}
if(isset($_GET['generar_comprobante2']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->generar_comprobante2($parametros));
}
if(isset($_GET['eliminarregistro']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->eliminar_registro($parametros));
}
if(isset($_GET['eliminarregistrogasto']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->eliminar_registrogasto($parametros));
}

if(isset($_GET['num_comprobante']))
{    
    $parametros = $_POST['parametros'];

    // print_r($parametros);die();
    echo json_encode(numero_comprobante1($parametros['tip'],true,false,$parametros['fecha']));
}
if(isset($_GET['generar_xml']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->SRI_Crear_Clave_Acceso_Retenciones($parametros));
}

if(isset($_GET['borrar_asientos']))
{
    // $parametros = $_POST['parametros'];
    echo json_encode($controlador->borrar_asientos());
}
if(isset($_GET['listar_comprobante']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->listar_comprobante($parametros));
}
if(isset($_GET['Tipo_De_Comprobante_No']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Tipo_De_Comprobante_No($parametros));
}
if(isset($_GET['Llenar_Encabezado_Comprobante']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Llenar_Encabezado_Comprobante($parametros));
}

if(isset($_GET['ing1']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->ingresar_asiento($parametros));
}
if(isset($_GET['eliminar_asientos']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->eliminar_asientos($parametros));
}
if(isset($_GET['edit_beneficiario']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->edit_beneficiario($parametros));
}
if(isset($_GET['CallListar_Comprobante_SP']))
{
    echo json_encode($controlador->CallListar_Comprobante_SP($_POST));
}

if(isset($_GET['load_subcuentas'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->load_subcuentas($parametros));
}

if(isset($_GET['Commandl_Click'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->commandl_click($parametros));
}

if(isset($_GET['Command2_Click'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->command2_click($parametros));
}
if(isset($_GET['ExistenMovimientos'])){
    $parametros = $_POST;
    echo json_encode($controlador->ExistenMovimientos($parametros));
}
if(isset($_GET['DCBanco_LostFocus'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->DCBanco_LostFocus($parametros));
}
if(isset($_GET['guardar_diferencia'])){
    $parametros = $_POST;
    echo json_encode($controlador->guardar_diferencia($parametros));
}
if(isset($_GET['facturas_pendientes'])){
    $parametros = $_GET;
    echo json_encode($controlador->Facturas_Pendientes_SC($parametros));
}


class incomC
{
	private $modelo;
    private $sri;	
	function __construct()
	{
		$this->modelo = new incomM();
        $this->sri = new autorizacion_sri();
	}

	function cargar_beneficiario($query)
	{
		$datos = $this->modelo->beneficiarios($query);
		$bene = array();
		foreach ($datos as $key => $value) {
			$bene[] = array('id'=>$value['id'].'-'.$value['email'],'text'=>$value['nombre']);
			// $bene[] = array('id'=>$value['id'].'-'.$value['email'],'text'=>$value['nombre']);//para produccion
		}
		return $bene;
	}

	function cargar_beneficiario_C($query)
	{
		$datos = $this->modelo->beneficiarios_c($query);
		$bene = array();
		foreach ($datos as $key => $value) {
			$bene[] = array('id'=>$value['id'].'-'.$value['email'],'text'=>$value['nombre']);
			// $bene[] = array('id'=>$value['id'].'-'.$value['email'],'text'=>$value['nombre']);//para produccion
		}
		return $bene;
	}

	function cargar_beneficiario_pro($query)
	{
		$datos = $this->modelo->beneficiarios_pro($query);
		$bene = array();
		foreach ($datos as $key => $value) {
			$bene[] = array('id'=>$value['id'].'-'.$value['email'].'-'.$value['TD'].'-'.$value['CI_RUC'].'-'.$value['Codigo'],'text'=>$value['nombre']);

			// $bene[] = array('id'=>$value['id'].'-'.$value['email'],'text'=>$value['nombre']);//para produccion
		}
		return $bene;
	}

	function cuentas_efectivo($query)
	{
		$datos = $this->modelo->cuentas_efectivo($query);
		$cuenta = array();
		foreach ($datos as $key => $value) {
			$cuenta[] = array('id'=>$value['Codigo'],'text'=>$value['cuenta']);
			// $cuenta[] = array('id'=>$value['Codigo'],'text'=>$value['cuenta']);//para produccion
		}
		return $cuenta;

	}



	function cuentas_banco($query)
	{
		$datos = $this->modelo->cuentas_banco($query);
		$cuenta = array();
		foreach ($datos as $key => $value) {
			$cuenta[] = array('id'=>$value['Codigo'],'text'=>$value['cuenta']);
			// $cuenta[] = array('id'=>$value['Codigo'],'text'=>$value['cuenta']);//para produccion
		}
		return $cuenta;

	}

	function cuentas_Todos($parametros)
	{
		// print_r($parametros);die();
		// $datos = $this->modelo->cuentas_todos($query,$tipo,$tipoCta);
		$datos = $this->modelo->cuentas_todos($parametros['query'],$parametros['codigo'],$parametros['clave'],$parametros['tc']);
		$cuenta = array();
		foreach ($datos as $key => $value) {
            // if($tipo=='')
            // {
                $cuenta[] = array('id'=>$value['Codigo'],'text'=>$value['Nombre_Cuenta']);
			// $cuenta[] = array('id'=>$value['Codigo'],'text'=>$value['Nombre_Cuenta']);//para produccion
            // }else
            // {                
            //     $cuenta[] = array('value'=>$value['Codigo'],'label'=>$value['Nombre_Cuenta']);
            // }
		}
		return $cuenta;

	}

	function InsertarAsientoBanco($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->cargar_asientosB();
		if(count($datos)>0){  $this->delete_asientoBTodos(); }
		SetAdoAddNew("Asiento_B");
        SetAdoFields("ME",0);
        SetAdoFields("CTA_BANCO",$parametros['banco']);
        SetAdoFields("BANCO",$parametros['bancoC']);
        SetAdoFields("CHEQ_DEP",$parametros['cheque']);
        SetAdoFields("EFECTIVIZAR",$parametros['fecha']);
        SetAdoFields("VALOR",$parametros['valor']);
        SetAdoFields("T_No",1); 
        SetAdoFields("Item",$_SESSION['INGRESO']['item']);
        SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
		
		return SetAdoUpdate();
	}

	function delete_asientoB($parametros)
	{
		$cta = $parametros['cta'];
		$cheq = $parametros['cheque'];
		$resp = $this->modelo->delete_asientoB($cta,$cheq);
		if($resp == 1)
		{
			return 1; 
		}else
		{
			return -1;
		}
	}
	function delete_asientoBTodos()
	{
		$resp = $this->modelo->delete_asientoBTodos();
		if($resp == 1)
		{
			return 1; 
		}else
		{
			return -1;
		}
	}

	function cargar_tablas()
	{
		$asiento= $this->modelo->DG_asientos();
		return $asiento;   
	}

	function cargar_tablas_sc()
	{
		$sc= $this->modelo->DG_asientos_SC();
		return $sc;   
	}

	function cargar_tablas_retencion()
	{
		$b= $this->modelo->DG_AC();
		$r= $this->modelo->DG_asientoR();		

		return array('b'=>$b,'r'=>$r['tbl'],'datos'=>$r['datos']);
	}
	function cargar_tablas_tab4()
	{
		// $AC= $this->modelo->DG_AC();
		$AV= $this->modelo->DG_AV();
		$AE= $this->modelo->DG_AE();
		$AI= $this->modelo->DG_AI();
		return $AV.$AE.$AI;   
	}

	function LeerCta($CodigoCta)
	{
		$Cuenta = '.';
        $Codigo = '.';
        $TipoCta = "G";
        $SubCta = "N";
        $TipoPago = "01";
        $Moneda_US = False;
		$datos= $this->modelo->LeerCta($CodigoCta);
		if(count($datos)>0)
		{
			foreach ($datos as $key => $value) {
				$Codigo = $value["Codigo"];
				$Cuenta = $value["Cuenta"];
				$SubCta = $value["TC"];
				$Moneda_US = $value["ME"];
				$TipoCta = $value["DG"];
				$TipoPago = $value["Tipo_Pago"];
				if (strlen($TipoPago) <= 0){$TipoPago = "01";}
			}
		}
		return array('cuenta'=>$Cuenta,'codigo'=>$Codigo,'tipocta'=>$TipoCta,'subcta'=>$SubCta,'tipopago'=>$TipoPago,'moneda'=>$Moneda_US);
     }


     function catalogo_subcta($parametros)
     {
     	// print_r($parametros);die();
     	if($parametros['tc']=='C' ||  $parametros['tc']== "P" || $parametros['tc']=="CP" )
     	{
     		$datos = $this->modelo->Catalogo_CxCxP($parametros['tc'],$parametros['cta'],$parametros['query']);
     		$ddl =array();
     		foreach ($datos as $key => $value) {
     			$ddl[]=array('id'=>$value['Codigo'],'text'=>$value['NomCuenta'],'data'=>$value);
     		}
     		return $ddl;
     	}else
     	{
     		$datos_tabla = $this->modelo->catalogo_subcta_grid($parametros['tc'],$parametros['cta'],$parametros['OpcDH'],$parametros['OpcTM']);
     	    $datos = $this->modelo->catalogo_subcta($parametros['tc']);
     	    foreach ($datos as $key => $value) {
     			$ddl[]=array('id'=>$value['Codigo'],'text'=>$value['Detalle'],'data'=>$value);
     		}
     		return $ddl;     	

     	}
     }


     function catalogo_subcta2($parametros)
     {

     	// print_r($parametros);die();
     	if($parametros['tc']=='C' ||  $parametros['tc']== "P" || $parametros['tc']=="CP" )
     	{
     		// $datos = $this->modelo->Catalogo_CxCxP($parametros['tc'],$parametros['cta'],$parametros['query']);
     		// $ddl =array();
     		// foreach ($datos as $key => $value) {
     		// 	$ddl[]=array('id'=>$value['Codigo'],'text'=>$value['NomCuenta']);
     		// }
     		// return $ddl;
     	}else
     	{
     		
     	    $datos = $this->modelo->catalogo_subcta($parametros['tc'],1,$parametros['nivel']);
     	    foreach ($datos as $key => $value) {
     			$ddl[]=array('id'=>$value['Codigo'],'text'=>$value['Detalle']);
     		}
     		return $ddl;     	

     	}
     }

     function detalle_aux_submodulo($parametros)
     {
     	// print_r($parametros);die();
     	$list = array();
     	$result = $this->modelo->detalle_aux_submodulo($parametros['query'],$parametros['tc']);
     	foreach ($result as $key => $value) {
     		$list[] = array('value'=>$value['Detalle_SubCta'],'label'=>$value['Detalle_SubCta']);
     	}
     	return $list;
     }

     function modal_generar_asiento_SC($parametros)
     {
     	// print_r($parametros);die();
     	$parametros_sc = array(
            'be'=>$parametros['ben'],
            'ru'=> '',
            'co'=> $parametros['cta'],// codigo de cuenta cc
            'tip'=>$parametros['tipoc'],//tipo de cuenta(CE,CD,..--) biene de catalogo subcuentas TC
            'tic'=> $parametros['dh'], //debito o credito (1 o 2);
            'sub'=> $parametros['codigo'], //Codigo se trae catalogo subcuenta o ruc del proveedor en caso de que se este ingresando
            'sub2'=>$parametros['ben'],//nombre del beneficiario
            'fecha_sc'=> $parametros['fec'], //fecha 
            'fac2'=>$parametros['fac'],
            'mes'=> $parametros['mes'],
            'valorn'=> round($parametros['val'],2),//valor de sub cuenta 
            'moneda'=> $parametros['tm'], /// moneda 1
            'Trans'=>$parametros['aux'],//detalle que se trae del asiento
            'T_N'=> $_SESSION['INGRESO']['modulo_'],
            't'=> $parametros['tc'],
            'serie'=>$parametros['serie']                        
        );

        $resp = ingresar_asientos_SC($parametros_sc);
        if($resp==null)
        {
        	return array('resp'=>1,'total'=>$parametros['val']);
        }else
        {
        	return array('resp'=>-1,'total'=>$parametros['val']);

        }
     }

     function modal_ingresar_asiento($parametros)
     {
     	$valor = $this->modelo->DG_asientos_SC_total($parametros['dh']);
     	$cuenta = $this->modelo->cuentas_todos($parametros['cta'],'',''); 
        $parametros_asiento = array(
				"va" => round($valor[0]['total'],2),
				"dconcepto1" => '.',
				"codigo" => $parametros['cta'],
				"cuenta" => $cuenta[0]['Cuenta'],
				"efectivo_as" => $parametros['fec'],
				"chq_as" =>0,
				"moneda" => $parametros['tm'],
				"tipo_cue" => $parametros['dh'],
				"cotizacion" => 0,
				"con" => 0,
				"t_no" => '1',
				"tc"=>$cuenta[0]['TC'],									
			);
         $resp = ingresar_asientos($parametros_asiento);
         if($resp==1)
         {
         	return 1;
         }else
         {
         	return -1;
         }
     }
     function cargar_tablas_sc_modal($parametros)
     {
     	$datos = $this->modelo->catalogo_subcta_grid($parametros['tc'],$parametros['cta'],$parametros['dh'],$parametros['tm']);
     	// print_r($datos);die();
     	return $datos;
     }
     function modal_subcta_limpiar($parametros)
     {
     	$this->modelo->limpiar_asiento_SC($parametros['tc'],$parametros['cta'],$parametros['dh'],$parametros['tm']); //TODO: Aqui revisar
     }
     function asientos_grabados()
     {
     	$asiento = $this->modelo->asiento();
     	$debe = 0;
     	$haber = 0;
     	foreach ($asiento as $key => $value) {
     		$debe+=$value['DEBE'];
     		$haber+=$value['HABER'];
     	}
     	if(($debe-$haber)<>0)
     	{
     		return 2;//Las transacciones no cuadran correctamente  "corrija los resultados de las cuentas"
     	}
     	$asiento_sc = $this->modelo->asiento_sc();
     }

     function datos_de_asientos()
     {
     	$asiento = $this->modelo->asientos();
     	$debe = 0;
     	$haber = 0;
     	$Ctas_Modificar = '';
     	foreach ($asiento as $key => $value) {
     		$debe+=$value['DEBE'];
     		$haber+=$value['HABER'];
     		$Ctas_Modificar.= $value['CODIGO'].',';
     	}
     	return array('debe'=>$debe,'haber'=>$haber,'diferencia'=>$debe-$haber,'Ctas_Modificar'=>$Ctas_Modificar);

     }

    function generar_comprobante($parametros)
    {

    	// print_r($parametros);die();
    	$ModificarComp = False;
     	$Monto_Total = $parametros['monto_total'];// LabelTotal.Caption)
     	$Trans_No = $_SESSION['INGRESO']['modulo_'];
     	// Asientos_Grabados
     	$AdoAsientos = $this->modelo->asientos();
     	$AdoAsientosSC = $this->modelo->asientos_SC();
     	// ------------------------------------------------     
  		$OpcSubCtaDH = 1;
  		$TextoImprimio = "";
  		$bene = $this->modelo->beneficiarios($parametros['ruc']);

  		if(count($AdoAsientos)> 0)
  		{
          if($parametros['NuevoComp']){
             If($parametros['tip']=='CD'){ $NumComp = ReadSetDataNum("Diario", True, True,$parametros['fecha']);}
             If($parametros['tip']=='CI'){ $NumComp = ReadSetDataNum("Ingresos", True, True,$parametros['fecha']);}
             If($parametros['tip']=='CE'){ $NumComp = ReadSetDataNum("Egresos", True, True,$parametros['fecha']);}
             If($parametros['tip']=='ND'){ $NumComp = ReadSetDataNum("NotaDebito", True, True,$parametros['fecha']);}
             If($parametros['tip']=='NC'){ $NumComp = ReadSetDataNum("NotaCredito", True, True,$parametros['fecha']);}
          }else{
          	$NumComp = explode('-', $parametros['num_com']);
          	$NumComp = trim($NumComp[1]);
          }

          // print_r($NumComp);die();

          $FechaTexto = $parametros['fecha'];
          $Co = datos_Co();
          $Co['Autorizacion_R'] = $parametros['Autorizacion_R'];
          $Co['Serie_R'] = $parametros['Serie_R'];
          $Co['CodigoB'] = $bene[0]['Codigo'];
          $Co['RetSecuencial'] = $parametros['Retencion'];
          $Co['TP'] = $parametros['tip'];
          $Co['TC'] = $parametros['tip'];
          $Co['Cotizacion'] = $parametros['TextCotiza'];
          $Co['T'] = G_NORMAL;
          $Co['Fecha'] =$FechaTexto;
          $Co['Numero'] =$NumComp;
          $Co['Monto_Total'] =$Monto_Total;
          $Co['Concepto'] =$parametros['concepto'];
         // 'Co.CodigoB'] =$CodigoBenef
          $Co['Efectivo'] =$parametros['Abono'];
          $Co['Cotizacion'] =$parametros['TextCotiza'];
          $Co['Item'] =$_SESSION['INGRESO']['item'];
          $Co['Usuario'] =$_SESSION['INGRESO']['CodigoU'];
          $Co['T_No'] =$Trans_No;
          // print_r($parametros);
          // print_r($Co);die();
         // 'Grabamos el Comprobante
          $resp = GrabarComprobante($Co);

          // print_r('dd');die();
        // ' Seteamos para el siguiente comprobante
        //  DGAsientosB.Visible = False
         // RatonNormal
          // ImprimirComprobantesDe False, Co
          // If CheqCopia.value Then ImprimirComprobantesDe False, Co
          BorrarAsientos($Trans_No,true);
          $Co['Numero'] = $NumComp + 1;
          if($ModificarComp){
             // ModificarComp = False
             $CopiarComp = False;
             // NuevoComp = True
             // Unload FComprobantes
             // Exit Sub
             return array('respuesta'=> 1,'NumCom'=>$NumComp);
          }else{
             // ModificarComp = False
             // CopiarComp = False
             $NuevoComp = True;
             // Tipo_De_Comprobante_No Co
             // MBoxFecha.SetFocus
             return array('respuesta'=> 1,'NumCom'=>$NumComp,'aut_res'=>$resp['aut'],'clave'=>$resp['clave']);
          }
       }else{
       	return  array('respuesta'=>-2,'NumCom'=>$NumComp);
          // MsgBox "Warning: Falta de Ingresar datos."
          // DGAsientos.Visible = True
          // TextCodigo.SetFocus
       }
    }

     function generar_comprobante2($parametros)
     {
     	// print_r($parametros);die();
     	$Autorizacion_LC=''; //revisar
     	$T_No='01';
         if($parametros['tip']=='CD'){$tip = 'Diario';}
         else if($parametros['tip']=='CI'){$tip = 'Ingresos';}
         else if($parametros['tip']=='CE'){$tip = 'Egresos';}
         else if($parametros['tip']=='ND'){$tip = 'NotaDebito';}
         else if($parametros['tip']=='NC'){$tip= 'NotaCredito';}

         if(isset($parametros['modificado']) && $parametros['modificado']==0)
         {
         	if($parametros['tip']!='Diario' &&  $parametros['tip'] != 'Ingresos' && $parametros['tip']!='Egresos' && $parametros['tip']!='NotaDebito'&& $parametros['tip']!='NotaCredito')
         	{
         		$num_com = ReadSetDataNum("RE_SERIE_".$parametros['Serie_R'], True, True);
         	}else{
         		$num_com = numero_comprobante1($tip,true,true,$parametros['fecha']);
        	}
         }else
         {
         	ReadSetDataNum("RE_SERIE_".$parametros['Serie_R'], True, True);
         	$num_com = explode('-',$parametros['num_com']);
         	$num_com = $num_com[1];
         }
         // $num_com = '123654789';

     	$parametro_comprobante = array(
            'ru'=> $parametros['ruc'], //codigo del cliente que sale co el ruc del beneficiario codigo
            'tip'=>$parametros['tip'],//tipo de cuenta contable cd, etc
            "fecha1"=> $parametros['fecha'],// fecha actual 2020-09-21
            'concepto'=>$parametros['concepto'], //detalle de la transaccion realida
            'totalh'=> $parametros['totalh'], //total del haber
            'num_com'=> '.'.date('Y', strtotime($parametros['fecha'])).'-'.$num_com, // codigo de comprobante de esta forma 2019-9000002
            );



				 // print_r($nombre);print_r($ruc);print_r($fecha);
				 // print_r($parametro_comprobante);die();

            // $cod = explode('-',$parametros['num_com']);
            // print_r($cod);die();

          $Autorizacion_LC=  $parametros['Autorizacion_LC'];
          $TP = $parametros['tip'];
     	  $T = $parametros['T'];
     	  $Fecha = $parametros['fecha'];
     	  $Numero = $num_com;
          $ClaveAcceso = G_NINGUNO;
          $RUC_CI = $parametros['ruc'];
          $CodigoB = $parametros['CodigoB'];
          $Serie_R=  $parametros['Serie_R'];
          $Retencion=  $parametros['Retencion'];
          $Autorizacion_R=  $parametros['Autorizacion_R'];
          $Beneficiario = $parametros['bene'];
          $TD = $parametros['TD'];
          $Email = $parametros['email'];
          $Ctas_Modificar = substr($parametros['Cta_modificar'], 0 ,-1);

          // print_r($parametros);die();
     	 if (strlen($Autorizacion_LC) >= 13){$Autorizacion_LC = ReadSetDataNum("LC_SERIE_".$Serie_LC, True, True);}

     	 if(strlen($Autorizacion_R)>=13){
     	 $reg = $this->modelo->Asiento_Air_Com($Autorizacion_R,$T_No);

     	 // print_r($reg);die();
     	 $RetNueva =false;
     	 if(count($reg) >0 && $RetNueva && $RetSecuencial)
     	 {
     	 	$retencion = ReadSetDataNum("RE_SERIE_".$Serie_R, True, True);
     	 }
     	}
     	 

        
          // 'Actualizar las Ctas a mayoriazar
          $res= $this->modelo->Actualizar_Ctas_a_mayorizar($TP,$num_com);
          	 // print_r($res);die();

          if(count($res)>0)
          {
          	foreach ($res as $key => $value) {
          		$this->modelo->Actualiza_Procesado_Kardex($value["Codigo_Inv"]);
          	}
          }

          $this->modelo->EliminarComprobantes($TP,$Numero);
          
          // ' Por Bodegas
          $ConBodegas = False;
          if(count($this->modelo->Por_Bodegas())>1)
          {
          	$ConBodegas = True;
          }



          // ' Grabamos SubCtas
          $reg = $this->modelo->Grabamos_SubCtas($T_No);

          // print_r($reg);die();

          if(count($reg)>0)
          {
          	foreach ($reg as $key => $value) {
          		$Valor = $value["Valor"];
                $Valor_ME = $value["Valor_ME"];
                $Codigo = $value["Codigo"];
                $TipoCta = $value["TC"];
                $OpcDH = intval($value["DH"]);
                $Factura_No = $value["Factura"];
                $Fecha_Vence = $value["FECHA_V"];
                $Cta_Cobrar = trim($value["Cta"]);
                if($Valor <> 0 || $Valor_ME <> 0)
                {
                	SetAdoAddNew("Trans_SubCtas");
                    SetAdoFields("T",$T);
                    SetAdoFields("TP",$TP);
                    SetAdoFields("Numero",$Numero);
                    SetAdoFields("Fecha",$Fecha);
                    SetAdoFields("Item",$_SESSION['INGRESO']['item']);
                    SetAdoFields("TC",$TipoCta);
                    SetAdoFields("Cta",$Cta_Cobrar);
                    SetAdoFields("Codigo",$Codigo);
                    SetAdoFields("Fecha_V",$Fecha_Vence->format('Y-m-d'));
                    SetAdoFields("Factura",$Factura_No);
                    SetAdoFields("Detalle_SubCta",$value["Detalle_SubCta"]);
                    SetAdoFields("Prima",$value["Prima"]);
                    if($OpcDH == 1)
                    {
                    	SetAdoFields("Debitos",$Valor);
                    	SetAdoFields("Parcial_ME",$Valor_ME);
                    }else
                    {
                        SetAdoFields("Creditos",$Valor);
                        SetAdoFields("Parcial_ME",'-'.$Valor_ME);
                    }

                    SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
                    // $NumTrans = $NumTrans + 1;
                    //funcion para actualizar o ingresar aqui
                     // print_r($datos);die();
                }

          	 $resp = SetAdoUpdate();
          	}
          }


           // 'RETENCIONES COMPRAS
          $rc = $this->modelo->RETENCIONES_COMPRAS($T_No);

          // print_r($rc);die();

          if(count($rc)>0)
          {
          	foreach ($rc as $key => $value) {
          		// 'Generacion de la Retencion si es Electronica
                $FechaTexto = $value["FechaRegistro"]->format('Y-m-d');
                $CodSustento = generaCeros($value["CodSustento"],2);
                SetAdoAddNew("Trans_Compras");
                SetAdoFields("IdProv",$CodigoB);
                SetAdoFields("DevIva",$value["DevIva"]);
                SetAdoFields("CodSustento",$value["CodSustento"]);
                SetAdoFields("TipoComprobante",$value["TipoComprobante"]);
                SetAdoFields("Establecimiento",$value["Establecimiento"]);
                SetAdoFields("PuntoEmision",$value["PuntoEmision"]);
                SetAdoFields("Secuencial",$value["Secuencial"]);
                SetAdoFields("Autorizacion",$value["Autorizacion"]);
                SetAdoFields("FechaEmision",$value["FechaEmision"]->format('Y-m-d'));
                SetAdoFields("FechaRegistro",$value["FechaRegistro"]->format('Y-m-d'));
                SetAdoFields("FechaCaducidad",$value["FechaCaducidad"]->format('Y-m-d'));
                SetAdoFields("BaseNoObjIVA",number_format($value["BaseNoObjIVA"],2,'.',''));
                SetAdoFields("BaseImponible",number_format($value["BaseImponible"],2,'.',''));
                SetAdoFields("BaseImpGrav",number_format($value["BaseImpGrav"],2,'.',''));
                SetAdoFields("PorcentajeIva",$value["PorcentajeIva"]);
                SetAdoFields("MontoIva",number_format($value["MontoIva"],2,'.',''));
                SetAdoFields("BaseImpIce",number_format($value["BaseImpIce"],2,'.',''));
                SetAdoFields("PorcentajeIce",$value["PorcentajeIce"]);
                SetAdoFields("MontoIce",number_format($value["MontoIce"],2,'.',''));
                SetAdoFields("MontoIvaBienes",number_format($value["MontoIvaBienes"],2,'.',''));
                SetAdoFields("PorRetBienes",number_format($value["PorRetBienes"],2,'.',''));
                SetAdoFields("ValorRetBienes",number_format($value["ValorRetBienes"],2,'.',''));
                SetAdoFields("MontoIvaServicios",number_format($value["MontoIvaServicios"],2,'.',''));
                SetAdoFields("PorRetServicios",$value["PorRetServicios"]);
                SetAdoFields("ValorRetServicios",$value["ValorRetServicios"]);
                SetAdoFields("Porc_Bienes",$value["Porc_Bienes"]);
                SetAdoFields("Porc_Servicios",$value["Porc_Servicios"]);
                SetAdoFields("Cta_Servicio",$value["Cta_Servicio"]);
                SetAdoFields("Cta_Bienes",$value["Cta_Bienes"]);
                SetAdoFields("Linea_SRI",0);
                SetAdoFields("DocModificado",$value["DocModificado"]);
                SetAdoFields("FechaEmiModificado",$value["FechaEmiModificado"]->format('Y-m-d'));
                SetAdoFields("EstabModificado",$value["EstabModificado"]);
                SetAdoFields("PtoEmiModificado",$value["PtoEmiModificado"]);
                SetAdoFields("SecModificado",$value["SecModificado"]);
                SetAdoFields("AutModificado",$value["AutModificado"]);
                SetAdoFields("ContratoPartidoPolitico",$value["ContratoPartidoPolitico"]);
                SetAdoFields("MontoTituloOneroso",number_format($value["MontoTituloOneroso"],2,'.',''));
                SetAdoFields("MontoTituloGratuito",number_format($value["MontoTituloGratuito"],2,'.',''));
                SetAdoFields("PagoLocExt",$value["PagoLocExt"]);
                SetAdoFields("PaisEfecPago",$value["PaisEfecPago"]);
                SetAdoFields("AplicConvDobTrib",$value["AplicConvDobTrib"]);
                SetAdoFields("PagExtSujRetNorLeg",$value["PagExtSujRetNorLeg"]);
                SetAdoFields("FormaPago",$value["FormaPago"]);
                SetAdoFields("Serie_Retencion",$Serie_R);
                SetAdoFields("SecRetencion",$Retencion);
                SetAdoFields("AutRetencion",$Autorizacion_R);
                SetAdoFields("Clave_Acceso",G_NINGUNO);
                SetAdoFields("T",G_NORMAL);
                SetAdoFields("TP",$TP);
                SetAdoFields("Numero",$Numero);
                SetAdoFields("Fecha",$Fecha);
                SetAdoFields("Item",$_SESSION['INGRESO']['item']);
                SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
               // print($datosC);die();
          		 $resp = SetAdoUpdate();
                // SetAdoUpdate
          	}
          }

          // print_r($resp);die();

          // ' RETENCIONES VENTAS
           $rv = $this->modelo->RETENCIONES_VENTAS($T_No);    

                // print_r($rv);die();      
          if(count($rv)>0)
          {
          	foreach ($rv as $key => $value) {
          		$FechaTexto = $value["FechaRegistro"]->format('Y-m-d');
                SetAdoAddNew("Trans_Ventas");
                SetAdoFields("IdProv",$CodigoB);
                SetAdoFields("TipoComprobante",$value["TipoComprobante"]);
                SetAdoFields("FechaRegistro",$value["FechaRegistro"]);
                SetAdoFields("FechaEmision",$value["FechaEmision"]);
                SetAdoFields("Establecimiento",$value["Establecimiento"]);
                SetAdoFields("PuntoEmision",$value["PuntoEmision"]);
                SetAdoFields("Secuencial",$value["Secuencial"]);
                SetAdoFields("NumeroComprobantes",$value["NumeroComprobantes"]);
                SetAdoFields("BaseImponible",$value["BaseImponible"]);
                SetAdoFields("IvaPresuntivo",$value["IvaPresuntivo"]);
                SetAdoFields("BaseImpGrav",$value["BaseImpGrav"]);
                SetAdoFields("PorcentajeIva",$value["PorcentajeIva"]);
                SetAdoFields("MontoIva",$value["MontoIva"]);
                SetAdoFields("BaseImpIce",$value["BaseImpIce"]);
                SetAdoFields("PorcentajeIce",$value["PorcentajeIce"]);
                SetAdoFields("MontoIce",$value["MontoIce"]);
                SetAdoFields("MontoIvaBienes",$value["MontoIvaBienes"]);
                SetAdoFields("PorRetBienes",$value["PorRetBienes"]);
                SetAdoFields("ValorRetBienes",$value["ValorRetBienes"]);
                SetAdoFields("MontoIvaServicios",$value["MontoIvaServicios"]);
                SetAdoFields("PorRetServicios",$value["PorRetServicios"]);
                SetAdoFields("ValorRetServicios",$value["ValorRetServicios"]);
                SetAdoFields("RetPresuntiva",$value["RetPresuntiva"]);
                SetAdoFields("Porc_Bienes",$value["Porc_Bienes"]);
                SetAdoFields("Porc_Servicios",$value["Porc_Servicios"]);
                SetAdoFields("Cta_Servicio",$value["Cta_Servicio"]);
                SetAdoFields("Cta_Bienes",$value["Cta_Bienes"]);
                SetAdoFields("Tipo_Pago",$value["Tipo_Pago"]);
                SetAdoFields("Linea_SRI",0);
                SetAdoFields("T",G_NORMAL);
                SetAdoFields("TP",$TP);
                SetAdoFields("Numero",$Numero);
                SetAdoFields("Fecha",$Fecha);
               // 'Razonocial
               // 'MsgBoC1.Beneficiario
                SetAdoFields("RUC_CI",$RUC_CI);
                SetAdoFields("IB",$TD);
                SetAdoFields("Razon_Social",$Beneficiario);
                // SetAdoUpdate          	
          	}
          	 $resp = SetAdoUpdate();
          }

          // ' RETENCIONES EXPORTACION
          $re = $this->modelo->RETENCIONES_EXPORTACION($T_No);

                // print_r($re);die();  

          if(count($re)>0)
          {
          	foreach ($re as $key => $value) {
          		 SetAdoAddNew("Trans_Exportaciones");
                 SetAdoFields("Codigo",$value["Codigo"]);
                 SetAdoFields("CtasxCobrar",$value["CtasxCobrar"]);
                 SetAdoFields("ExportacionDe",$value["ExportacionDe"]);
                 SetAdoFields("TipoComprobante",$value["TipoComprobante"]);
                 SetAdoFields("FechaEmbarque",$value["FechaEmbarque"]);
                 SetAdoFields("NumeroDctoTransporte",$value["NumeroDctoTransporte"]);
                 SetAdoFields("IdFiscalProv",$CodigoB);
                 SetAdoFields("ValorFOB",$value["ValorFOB"]);
                 SetAdoFields("DevIva",$value["DevIva"]);
                 SetAdoFields("FacturaExportacion",$value["FacturaExportacion"]);
                 SetAdoFields("ValorFOBComprobante",$value["ValorFOBComprobante"]);
                 SetAdoFields("DistAduanero",$value["DistAduanero"]);
                 SetAdoFields("Anio",$value["Anio"]);
                 SetAdoFields("Regimen",$value["Regimen"]);
                 SetAdoFields("Correlativo",$value["Correlativo"]);
                 SetAdoFields("Verificador",$value["Verificador"]);
                 SetAdoFields("Establecimiento",$value["Establecimiento"]);
                 SetAdoFields("PuntoEmision",$value["PuntoEmision"]);
                 SetAdoFields("Secuencial",$value["Secuencial"]);
                 SetAdoFields("Autorizacion",$value["Autorizacion"]);
                 SetAdoFields("FechaEmision",$value["FechaEmision"]);
                 SetAdoFields("FechaRegistro",$value["FechaRegistro"]);
                 SetAdoFields("Linea_SRI",0);
                 SetAdoFields("T",G_NORMAL);
                 SetAdoFields("TP",$TP);
                 SetAdoFields("Numero",$Numero);
                 SetAdoFields("Fecha",$Fecha);
                 // SetAdoUpdate
          	}          	
          	 $resp = SetAdoUpdate();
          }

          // ' RETENCIONES IMPORTACIONES
           $ri = $this->modelo->RETENCIONES_IMPORTACIONES($T_No);

                // print_r($ri);die();  

          if(count($ri)>0)
          {
          	foreach ($ri as $key => $value) {
          		 $FechaTexto = $value["FechaLiquidacion"]->format('Y-m-d');
                 SetAdoAddNew("Trans_Importaciones");
                 SetAdoFields("CodSustento",$value["CodSustento"]);
                 SetAdoFields("ImportacionDe",$value["ImportacionDe"]);
                 SetAdoFields("FechaLiquidacion",$value["FechaLiquidacion"]);
                 SetAdoFields("TipoComprobante",$value["TipoComprobante"]);
                 SetAdoFields("DistAduanero",$value["DistAduanero"]);
                 SetAdoFields("Anio",$value["Anio"]);
                 SetAdoFields("Regimen",$value["Regimen"]);
                 SetAdoFields("Correlativo",$value["Correlativo"]);
                 SetAdoFields("Verificador",$value["Verificador"]);
                 SetAdoFields("IdFiscalProv",$CodigoB);
                 SetAdoFields("ValorCIF",$value["ValorCIF"]);
                 SetAdoFields("BaseImponible",$value["BaseImponible"]);
                 SetAdoFields("BaseImpGrav",$value["BaseImpGrav"]);
                 SetAdoFields("PorcentajeIva",$value["PorcentajeIva"]);
                 SetAdoFields("MontoIva",$value["MontoIva"]);
                 SetAdoFields("BaseImpIce",$value["BaseImpIce"]);
                 SetAdoFields("PorcentajeIce",$value["PorcentajeIce"]);
                 SetAdoFields("MontoIce",$value["MontoIce"]);
                 SetAdoFields("Linea_SRI",0);
                 SetAdoFields("T",G_NORMAL);
                 SetAdoFields("TP",$TP);
                 SetAdoFields("Numero",$Numero);
                 SetAdoFields("Fecha",$Fecha);
                 // SetAdoUpdate
          	}

          	  $resp = SetAdoUpdate();
          }

          // ' RETENCIONES AIR
           $ra = $this->modelo->RETENCIONES_AIR($T_No);   

                // print_r($ra);die();            
          if(count($ra)>0)
          {
          	foreach ($ra as $key => $value) {
          		  SetAdoAddNew("Trans_Air");
                  SetAdoFields("CodRet",$value["CodRet"]);
                  SetAdoFields("BaseImp",$value["BaseImp"]);
                  SetAdoFields("Porcentaje",number_format($value["Porcentaje"],2));
                  SetAdoFields("ValRet",$value["ValRet"]);
                  SetAdoFields("EstabRetencion",$value["EstabRetencion"]);
                  SetAdoFields("PtoEmiRetencion",$value["PtoEmiRetencion"]);
                  SetAdoFields("Tipo_Trans",$value["Tipo_Trans"]);
                  SetAdoFields("IdProv",$CodigoB);
                  SetAdoFields("Cta_Retencion",$value["Cta_Retencion"]);
                  SetAdoFields("EstabFactura",$value["EstabFactura"]);
                  SetAdoFields("PuntoEmiFactura",$value["PuntoEmiFactura"]);
                  SetAdoFields("Factura_No",$value["Factura_No"]);
                  SetAdoFields("Linea_SRI",0);
                  SetAdoFields("T",G_NORMAL);
                  SetAdoFields("TP",$TP);
                  SetAdoFields("Numero",$Numero);
                  SetAdoFields("Fecha",$Fecha);
                  SetAdoFields("SecRetencion",$Retencion);
                  SetAdoFields("AutRetencion",$Autorizacion_R);                  
                  SetAdoFields("Item",$_SESSION['INGRESO']['item']);
                  SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
                  // SetAdoUpdate
                  // NumTrans = NumTrans + 1
             $resp = SetAdoUpdate();

             // print_r($resp);die();

          	}

          }

          // ' Grabamos Retencion de Rol de Pagos
           $rp = $this->modelo->Retencion_Rol_Pagos($T_No);

                // print_r($rp);die();  

          if(count($rp['res'])>0)
          {
          	 SetAdoAddNew("Trans_Rol_Pagos");
          	foreach ($rp['res'] as $key => $value) {
          		$count = 1;
          		foreach ($rp['smtp'] as $key1 => $value1) {
          			SetAdoFields("'".$value1['COLUMN_NAME']."'","'".$value1['COLUMN_NAME']."'");           			
          		}
          		//buscar en  el array para remplazar en estos
                 SetAdoFields("CodigoU",$CodigoUsuario);
                 SetAdoFields("Item",$_SESSION['INGRESO']['item']);
                 SetAdoFields("Fecha",$Fecha);
                 SetAdoFields("T",$Normal);
                 SetAdoFields("TP",$TP);
                 SetAdoFields("Numero",$Numero);
                 SetAdoFields("Codigo",$CodigoB);
                //  SetAdoUpdate
                // .MoveNext
          	}
          	 $resp = SetAdoUpdate();
          }


          // ' Grabamos Inventarios
          $Inv_Promedio =false;
           $gi = $this->modelo->Grabamos_Inventarios($T_No);   

                // print_r($gi);die();         
          if(count($gi)>0)
          {
          	foreach ($gi as $key => $value) {
          		// ' Asiento de Inventario
                SetAdoAddNew("Trans_Kardex");
                SetAdoFields("T",G_NORMAL);
                SetAdoFields("TP",$TP);
                SetAdoFields("Numero",$Numero);
                SetAdoFields("Fecha",$Fecha);
                SetAdoFields("Codigo_Dr",$value["Codigo_Dr"]);// ' C1.CodigoDr
                SetAdoFields("Codigo_Tra",$value["Codigo_Tra"]); // ' C1.CodigoDr
                SetAdoFields("Codigo_Inv",$value["CODIGO_INV"]);
                SetAdoFields("Codigo_P",$value["Codigo_B"]);
                SetAdoFields("Descuento",$value["P_DESC"]);
                SetAdoFields("Descuento1",$value["P_DESC1"]);
                SetAdoFields("Valor_Total",$value["VALOR_TOTAL"]);
                SetAdoFields("Existencia",$value["CANTIDAD"]);
                SetAdoFields("Valor_Unitario",$value["VALOR_UNIT"]);
                SetAdoFields("Total",$value["SALDO"]);
                SetAdoFields("Cta_Inv",$value["CTA_INVENTARIO"]);
                SetAdoFields("Contra_Cta",$value["CONTRA_CTA"]);
                SetAdoFields("Orden_No",$value["ORDEN"]);
                SetAdoFields("CodBodega",$value["CodBod"]);
                SetAdoFields("CodMarca",$value["CodMar"]);
                SetAdoFields("Codigo_Barra",$value["COD_BAR"]);
                SetAdoFields("Costo",$value["VALOR_UNIT"]);
                SetAdoFields("PVP",$value["PVP"]);
                SetAdoFields("No_Refrendo",$value["No_Refrendo"]);
                SetAdoFields("Lote_No",$value["Lote_No"]);
                SetAdoFields("Fecha_Fab",$value["Fecha_Fab"]);
                SetAdoFields("Fecha_Exp",$value["Fecha_Exp"]);
                SetAdoFields("Modelo",$value["Modelo"]);
                SetAdoFields("Serie_No",$value["Serie_No"]);
                SetAdoFields("Procedencia",$value["Procedencia"]);
                if($Inv_Promedio){
                   $Cantidad = $value["CANTIDAD"];
                   $Saldo = $value["SALDO"];
                   if($Cantidad <= 0){$Cantidad = 1;}
                   SetAdoFields("Costo", number_format($Saldo / $Cantidad,2,'.',''));
                }
                if($value["DH"] == 1){
                   SetAdoFields("Entrada",$value["CANT_ES"]);
                }else{
                   SetAdoFields("Salida",$value["CANT_ES"]);
                   $Si_No = False;
                }
                SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
                SetAdoFields("Item",$_SESSION['INGRESO']['item']);
                // SetAdoUpdate
                // AdoTemp.MoveNext                
                $NumTrans = $NumTrans + 1;

          	}          	
          	 $resp = SetAdoUpdate();
          }


          // ' Grabamos Prestamos
            $gp = $this->modelo->Grabamos_Prestamos($T_No);

                // print_r($gp);die();   

          if(count($gp)>0)
          {
          	 $TotalCapital = 0;
             $TotalInteres = 0;
          	foreach ($gp as $key => $value) {
          		if( $value["Cuotas"] > 0 ){
                    SetAdoAddNew("Trans_Prestamos");
                    SetAdoFields("T","P");
                    SetAdoFields("Fecha",$value["Fecha"]);
                    SetAdoFields("TP",$TP);
                    SetAdoFields("Credito_No",$_SESSION['INGRESO']['item'].''.generaCeros($Numero,7));
                    SetAdoFields("Cta",$Cta);
                    SetAdoFields("Cuenta_No",$CodigoB);
                    SetAdoFields("Cuota_No",$value["Cuotas"]);
                    SetAdoFields("Interes",$value["Interes"]);
                    SetAdoFields("Capital",$value["Capital"]);
                    SetAdoFields("Pagos",$value["Pagos"]);
                    SetAdoFields("Saldo",$value["Saldo"]);
                    SetAdoFields("CodigoU",$value["CodigoU"]);
                    SetAdoFields("Item",$_SESSION['INGRESO']['item']);
                    // SetAdoUpdate//actualiza
                    $resp = SetAdoUpdate();
                }
                $TotalCapital = $TotalCapital + $value["Capital"];
                $TotalInteres = $TotalInteres + $value["Interes"];
                $TotalAbonos = $value["Pagos"];
                $Cta = $value["Cta"];
                $NumMeses = $value["Cuotas"];
                // AdoTemp.MoveNext
          	}
          	 SetAdoAddNew("Prestamos");
             SetAdoFields("T","P");
             SetAdoFields("Fecha",$Fecha);
             SetAdoFields("TP",$TP);
             SetAdoFields("Credito_No",$_SESSION['INGRESO']['item'].''.generaCeros($Numero,7));
             SetAdoFields("Cta",$Cta);
             SetAdoFields("Cuenta_No",$CodigoB);
             SetAdoFields("Meses",NumMeses);
             SetAdoFields("Tasa",number_format(($TotalInteres * 12) / ($TotalCapital * $NumMeses),4,'.',''));
             SetAdoFields("Interes",$TotalInteres);
             SetAdoFields("Capital",$TotalCapital);
             SetAdoFields("Pagos",$TotalAbonos);
             SetAdoFields("Saldo_Pendiente",$TotalCapital);
             SetAdoFields("Item",$_SESSION['INGRESO']['item']);

             $resp = SetAdoUpdate();
             // SetAdoUpdate ingresa creo

          }

           // ' Grabamos Comprobantes
        $resp = generar_comprobantes($parametro_comprobante);

        
          // ' Grabamos Transacciones
           $gt = $this->modelo->Grabamos_Transacciones($T_No);

                // print_r($gt);die();   

          if(count($gt)>0)
          {
          	foreach ($gt as $key => $value) {
          		$Moneda_US = $value["ME"];
                $Cta = trim($value["CODIGO"]);
                $Debe = number_format($value["DEBE"], 2);
                $Haber = number_format($value["HABER"], 2);
                $Parcial = number_format($value["PARCIAL_ME"], 2);
                $NoCheque = $value["CHEQ_DEP"];
                $CodigoCC = $value["CODIGO_CC"];
                $Fecha_Vence = $value["EFECTIVIZAR"];
                $DetalleComp = $value["DETALLE"];
                $CodigoP = $value["CODIGO_C"];
                if($CodigoP == '.'){ $CodigoP = $CodigoB;}
                // 'MsgBox C1.T_No & vbCrLf & C1.Concepto & vbCrLf & Debe & vbCrLf & Haber
                if (stristr($Ctas_Modificar, $Cta) === false) {  $Ctas_Modificar.= $Ctas_Modificar.''.$Cta.",";}
                // if (InStr(C1.Ctas_Modificar, $Cta) == 0){ C1.Ctas_Modificar == C1.Ctas_Modificar.''.$Cta.",";}
                if(($Debe + $Haber) > 0){
                   SetAdoAddNew("Transacciones");
                   SetAdoFields("T",$T);
                   SetAdoFields("Fecha",$Fecha);
                   SetAdoFields("TP",$TP);
                   SetAdoFields("Numero",$Numero);
                   SetAdoFields("Cta",$Cta);
                   SetAdoFields("Parcial_ME",$Parcial);
                   SetAdoFields("Debe",$Debe);
                   SetAdoFields("Haber",$Haber);
                   SetAdoFields("Cheq_Dep",$NoCheque);
                   SetAdoFields("Fecha_Efec",$Fecha_Vence->format('Y-m-d'));
                   SetAdoFields("Detalle",$DetalleComp);
                   SetAdoFields("Codigo_C",$CodigoP);
                   SetAdoFields("C_Costo",$CodigoCC);
                   SetAdoFields("Item",$_SESSION['INGRESO']['item']);
                   // SetAdoelds "C", True
                   SetAdoFields("Procesado",0);
                   // $datosGT[16]['campo']= "Pagar";
                   // $datosGT[16]['dato']=0;
                  //  SetAdoUpdate
                  // NumTrans = NumTrans + 1

          	     // print_r($datosGT);die();
                }                
          	     if(isset($datosGT)){
          	     	$resp = SetAdoUpdate();
          	     }
          	}
          }

          // print_r('expression');die();

           

          // 'Pasamos a colocar las cuentas que se tienen que mayorizar despuesde grabar el comprobante

        // print_r($Ctas_Modificar);die();
            if(strlen($Ctas_Modificar) > 1 ){
            	$Ctas = explode(',', $Ctas_Modificar);
            	foreach ($Ctas as $key => $value) {
            		 $this->modelo->cuentas_a_mayorizar($value);
            	}
            }


            // 'Actualiza el Email del beneficiario
            if(strlen($Email) > 3 ){
            	$this->modelo->actualizar_email($Email,$CodigoB);
            }

  


           // 'Pasamos a Autorizar la retencion si es electronica
            $parametros_xml = array();
            $parametros_xml['Autorizacion_R']=$Autorizacion_R;
            $parametros_xml['Retencion']=$Retencion;
            $parametros_xml['Serie_R']=$Serie_R;
            $parametros_xml['TP']=$TP;
            $parametros_xml['Fecha']=$Fecha;
            $parametros_xml['Numero']=$Numero;
            $parametros_xml['ruc']=$parametros['ruc'];
            // print_r($Autorizacion_R);
            // exit();
            // print_r($parametros);die();
            if(strlen($Autorizacion_R) >= 13){
            	$res = '0';
            	$res = $this->sri->Autorizar_retencion($parametros_xml);

            	// $res = $this->SRI_Crear_Clave_Acceso_Retencines($parametros_xml); //function xml
            	// print_r($res);die();
				$aut = $this->sri->Clave_acceso($parametros['fecha'],'07',$Serie_R,generaCeros($parametros['Retencion'],9));
				$pdf = 'RE_'.$Serie_R.'-'.generaCeros($parametros['Retencion'],7); 
				$this->modelo->reporte_retencion($Numero,$TP,$Retencion,$Serie_R,$imp=1);

            // print_r($parametros);die();
				// if($res==1)
				// {
					 $Trans_No = $T_No;
           			 $this->modelo->BorrarAsientos($Trans_No,true);
				// }
           			 // print_r(array('respuesta'=>$res,'pdf'=>$pdf,'text'=>$res,'clave'=>$aut));die();
				return array('respuesta'=>$res,'pdf'=>$pdf,'text'=>$res,'clave'=>$aut);
            	  
				
            	// if(!is_null($res))
            	// {
            	//  return $res;
             //    }
            }else
            {
            	return 1;
            }


           // 'Eliminamos Asientos contables
           
            // Control_Procesos Normal, "Grabar Comprobante de: " & C1.TP & " No. " & C1.Numero
            //   if($this->ingresar_trans_Air($num_com,$parametros['tip'])==1){
            //     $resp = generar_comprobantes($parametro_comprobante);
            // // print_r($resp);die();
            //     if($resp==$num_com)
            //     {
            //         return 1;
            //     }else
            //     {
            //         return -1;
            //     }
            //   }else
            //   {
            //     echo " no se genero";
            //   }

            // return 1;
           

     }
     function ingresar_trans_Air($numero,$tipo)
     {
        $air = $this->modelo->DG_asientoR_datos();
        $fallo = false;
        if(count($air)>0)
        {
            foreach ($air as $key => $value) {
            	SetAdoAddNew("Trans_Air");
                SetAdoFields('CodRet',$value['CodRet']);
                SetAdoFields('Detalle',$value['Detalle']);
                SetAdoFields('BaseImp',$value['BaseImp']);
                SetAdoFields('Porcentaje',$value['Porcentaje']);
                SetAdoFields('ValRet',$value['ValRet']);
                SetAdoFields('EstabRetencion',$value['EstabRetencion']);
                SetAdoFields('PtoEmiRetencion',$value['PtoEmiRetencion']);
                SetAdoFields('SecRetencion',$value['SecRetencion']);
                SetAdoFields('AutRetencion',$value['AutRetencion']);
                SetAdoFields('FechaEmiRet',$value['FechaEmiRet']->format('Y-m-d'));
                SetAdoFields('Cta_Retencion',$value['Cta_Retencion']);
                SetAdoFields('EstabFactura',$value['EstabFactura']);
                SetAdoFields('PuntoEmiFactura',$value['PuntoEmiFactura']);
                SetAdoFields('Factura_No',value['Factura_No']);
                SetAdoFields('IdProv',$value['IdProv']);
                SetAdoFields('Item',$value['Item']);
                SetAdoFields('CodigoU',$value['CodigoU']);
                SetAdoFields('A_No',$value['A_No']);
                SetAdoFields('T_No',$value['T_No']);
                SetAdoFields('Tipo_Trans',$value['Tipo_Trans']);
                SetAdoFields('T','N');
                SetAdoFields('Numero',$numero);
                SetAdoFields('TP',$tipo);

            
            }
           $resp = SetAdoUpdate(); 
           if($resp ==-1)
           {
             $fallo = true;
           }
        }
        // print_r($air);die();
        if($fallo==false)
        {
            return 1;
        }
        else{
            return -1;
        }
     }
     function eliminar_retenciones()
     {
        return $this->modelo->eliminacion_retencion();
     }
     function eliminar_registro($parametros)
     {
        $Codigo = '';
        $tabla = '';
       switch ($parametros['tabla']) {
           case 'asiento':
             $Codigo = "CODIGO = '".$parametros['Codigo']."' ";
            $tabla = 'Asiento';
               break;
            case 'asientoSC':
             $Codigo = "Codigo = '".$parametros['Codigo']."' ";
             $tabla = 'Asiento_SC';
               # code...
               break;
            case 'asientoB':
             $Codigo = "CTA_BANCO = '".$parametros['Codigo']."' ";
             $tabla = 'Asiento_B';
               # code...
               break;
            case 'inpor':
             $Codigo = "Cod_Sustento = '".$parametros['Codigo']."' ";
             $tabla = 'Asiento_Importaciones';
               # code...
               break;
            case 'expo':
             $Codigo = "Codigo = '".$parametros['Codigo']."' ";
            $tabla = 'Asiento_Exportaciones';
               # code...
               break;
            case 'ventas':
             $Codigo = "IdProv = '".$parametros['Codigo']."' ";
            $tabla = 'Asiento_Ventas';
               # code...
               break;
            case 'compras':
             $Codigo = "IdProv = '".$parametros['Codigo']."' ";
            $tabla = 'Asiento_Compras';
               # code...
               break;
            case 'air':             
             $Codigo = "CodRet = '".$parametros['Codigo']."' ";
             $tabla = 'Asiento_Air';
               # code...
               break;
       }

       if(isset($parametros['ID'])){
       	$Codigo .= ' AND ID='.$parametros['ID'].' ';
       }
       return $this->modelo->eliminar_registros($tabla,$Codigo);
     }

    

     function actualizar_datos_CER($autorizacion,$tc,$serie,$retencion,$entidad,$autorizacion_ant)
     {

     	$res = $this->modelo->actualizar_trans_compras($tc,$retencion,$serie,$autorizacion,$autorizacion_ant);
     	$res2 = $this->modelo->atualizar_trans_air($tc,$retencion,$serie,$autorizacion,$autorizacion_ant);
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

		$res3 = $this->modelo->guardar_documento($autorizacion,$cadena2,$serie,$retencion);	
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
     function ListarAsientoB()
     {
     	$Opcb = 1;
     	$tbl = $this->modelo->ListarAsientoTemSQL('',$Opcb,false,false);
     	return $tbl;
     }

     function borrar_asientos()
     {
     	return $this->modelo->BorrarAsientos('1',true);
     }

    function listar_comprobante($parametros)
    {
    	$dato = explode('-',$parametros);
    	$parametros = array('TP'=>$dato[0],'Numero'=>$dato[1],'Item'=>$_SESSION['INGRESO']['item']);
    // $parametros = base64_decode($parametros);
    // $parametros = unserialize($parametros);
    // print_r($parametros);die();

    $Trans_No = 0;
    $Ln_No = 0;
    $LnSC_No = 0;
    $Ret_No = 0;
    $C1_CodigoB = '';
    $C1_Beneficiario ='';
    $C1_Email ='';
    $C1_Concepto ='';
    $C1_Cotizacion ='';
    $C1_Monto_Total ='';
    $C1_Efectivo ='';
    $C1_RUC_CI ='';
    $C1_TD ='';
    $C1_Item = $parametros['Item'];
    $C1_Ctas_Modificar = '';


// ' Determinamos espacios de memoria para grabar
    if($Trans_No <= 0){	$Trans_No = 1;}
    if($Ln_No <= 0){$Ln_No = 1;}
    if($LnSC_No <= 0){$LnSC_No = 1;}
    if($Ret_No <= 0){$Ret_No = 1;}
    $ExisteComp = False;
//     Co.RetNueva = True;
//     // 'Encabezado del Comprobante
    $enca = $this->modelo->Encabezado_Comprobante($parametros);
    if(count($enca)>0)
    {
       $C1_CodigoB = $enca[0]["Codigo_B"];
       $C1_Beneficiario = $enca[0]["Cliente"];
       $C1_Email = $enca[0]["Email"];
       $C1_Concepto = $enca[0]["Concepto"];
       $C1_Cotizacion = $enca[0]["Cotizacion"];
       $C1_Monto_Total = $enca[0]["Monto_Total"];
       $C1_Efectivo = $enca[0]["Efectivo"];
       $C1_RUC_CI = $enca[0]["CI_RUC"];
       $C1_TD = $enca[0]["TD"];
       $ExisteComp = True;

    }else
    {
       $C1_CodigoB = G_NINGUNO;
       $C1_Beneficiario = G_NINGUNO;
       $C1_Email = Ninguno;
       $C1_Concepto = G_NINGUNO;
       $C1_Cotizacion = 0;
       $C1_Monto_Total = 0;
       $C1_Efectivo = 0;
       $C1_RUC_CI = G_NINGUNO;
       $C1_TD = G_NINGUNO;

    }
//  'Si existe el comprobante lo presentamos
    if($ExisteComp){
        // 'Llenar Cuentas de Transacciones
     	$AdoRegistros = $this->modelo->transacciones_comprobante($parametros['TP'],$parametros['Numero'],$parametros['Item']);
     	if(count($AdoRegistros)>0)
     	{
     		foreach ($AdoRegistros as $key => $value) {
     		 $Si_No = 0;
             if($value["Parcial_ME"] <> 0){$Si_No = 1;}
             SetAdoAddNew("Asiento");
             SetAdoFields("CODIGO",$value["Cta"]);
             SetAdoFields("CUENTA",$value["Cuenta"]);
             SetAdoFields("PARCIAL_ME",$value["Parcial_ME"]);
             SetAdoFields("DEBE",$value["Debe"]);
             SetAdoFields("HABER",$value["Haber"]);
             SetAdoFields("ME",$Si_No);
             SetAdoFields("CHEQ_DEP",$value["Cheq_Dep"]);
             SetAdoFields("EFECTIVIZAR",$value["Fecha_Efec"]->format('Y-m-d'));
             SetAdoFields("DETALLE",str_replace(',','',$value["Detalle"]));
             SetAdoFields("CODIGO_C",$value["Codigo_C"]);
             SetAdoFields("T_No",$Trans_No);
             SetAdoFields("Item",$C1_Item);
             SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
             SetAdoFields("A_No",$Ln_No);

             // print_r($datos);die();
             $resp = SetAdoUpdate();
             $pos = strpos($C1_Ctas_Modificar, $value["Cta"]);
             if ($pos === false) {
             	 $C1_Ctas_Modificar = $C1_Ctas_Modificar.$value["Cta"].",";
             } 
             $Ln_No = $Ln_No + 1;
     		}

     	}
     	     
        //'Llenar Bancos
     	if(count($AdoRegistros)>0)
     	{
     		$datos = array();
     		foreach ($AdoRegistros as $key => $value) {
     			if($value["Cheq_Dep"] <> G_NINGUNO){
                    $Si_No = 0;
                    if($value["Parcial_ME"] <> 0){$Si_No = 1;}
                       SetAdoAddNew("Asiento_B");
                       SetAdoFields("CTA_BANCO",$value["Cta"]);
                       SetAdoFields("BANCO",$value["Cuenta"]);
                       SetAdoFields("CHEQ_DEP",$value["Cheq_Dep"]);
                       SetAdoFields("EFECTIVIZAR",$value["Fecha_Efec"]->format('Y-m-d'));
                       SetAdoFields("VALOR",abs($value["Debe"]-$value["Haber"]));
                       SetAdoFields("ME",$Si_No);
                       SetAdoFields("T_No",$Trans_No);
                       SetAdoFields("Item",$C1_Item);
                       SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);

     	              // print_r($datos);die();
                      $resp = SetAdoUpdate();
     			}    			
     		}
     	}


        //'Listar las Retenciones Air
        $Ret_No = 1;
        $AdoRegistros = $this->modelo->retenciones_comprobantes($parametros['TP'],$parametros['Numero'],$parametros['Item']);
        // print_r($AdoRegistros);
        if(count($AdoRegistros['respuesta'])>0)
        {       	
     		$datos = array();
     		SetAdoAddNew("Asiento_Air");
        	foreach ($AdoRegistros['respuesta'] as $key => $value) {
             $K = sqlsrv_field_metadata($AdoRegistros['stmt']); 
             $count = 0;         

        		foreach ($K as $key1 => $value1) {
        			// print_r($value);die();
        			if($value1['Name']!='CodigoU' && $value1['Name']!='T_No' && $value1['Name']!='Item' && $value1['Name']!='A_No')
        			{
        				SetAdoFields($value1['Name'],$value[$value1['Name']]);
                    }     			
          		}

             SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
             SetAdoFields("T_No",$Trans_No);
             SetAdoFields("Item",$C1_Item);
             SetAdoFields("A_No",$Ret_No);

     		// print_r($datos);die();
            
             $Ret_No = $Ret_No + 1;
             $resp =  SetAdoUpdate();
        	}

        }
     
        //'Listar las Compras
        $Ret_No = 1;
        $AdoRegistros = $this->modelo->Listar_Compras($parametros['TP'],$parametros['Numero'],$parametros['Item']);
        if(count($AdoRegistros['respuesta'])>0)
        {
     		$datos = array();
        	foreach ($AdoRegistros['respuesta'] as $key => $value) {
        		if($value['SecRetencion']>0)
        		{
        			$Co_RetNueva = False;
                    $Co_Serie_R = $value["Serie_Retencion"];
                    $Co_Retencion = $value["SecRetencion"];
        		}
        		$count = 1;
        		 $K = sqlsrv_field_metadata($AdoRegistros['stmt']); 
                 $count = 0; 


                 SetAdoAddNew("Asiento_Compras");        
        		foreach ($K as $key1 => $value1) {
        			// print_r($value);die();
        			if($value1['Name']!='CodigoU' && $value1['Name']!='T_No' && $value1['Name']!='Item' && $value1['Name']!='A_No')
        			{
	          			if(is_object($value[$value1['Name']])) 
	          			{
	          				 SetAdoFields($value1['Name'], $value[$value1['Name']]->format('Y-m-d'));

	          			}else
	          			{
	          				SetAdoFields($value1['Name'],$value[$value1['Name']]);
          				}   
                    	$count = $count+1; 
                    }     			
          		}
        		 // For K = 0 To .Fields.Count - 1
            //     SetAdoFields .Fields(K).Name, .Fields(K)
            // Next K

          		SetAdoFields($value1['Name'],$value[$value1['Name']]);

                SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
                SetAdoFields("T_No",$Trans_No);
                SetAdoFields("Item",$C1_Item);
                SetAdoFields("A_No",$Ret_No);
                // print_r($datos);die();
                $Ret_No = $Ret_No + 1;                
                $resp = SetAdoUpdate();

        	}
        }
     
        //'Listar las Ventas
        $Ret_No = 1;
        $AdoRegistros = $this->modelo->Listar_Ventas($parametros['TP'],$parametros['Numero'],$parametros['Item']);
        if(count($AdoRegistros['respuesta'])>0)
        {
     		$datos = array();
        	foreach ($AdoRegistros['respuesta'] as $key => $value) {        		
        		$count = 1;
        		 $K = sqlsrv_field_metadata($AdoRegistros['stmt']); 
                 $count = 0; 

                 SetAdoAddNew("Asiento_Ventas");      
        		foreach ($K as $key1 => $value1) {
        			// print_r($value);die();
        			if($value1['Name']!='CodigoU' && $value1['Name']!='T_No' && $value1['Name']!='Item' && $value1['Name']!='A_No')
        			{
	          			if(is_object($value[$value1['Name']])) 
	          			{
	          				 SetAdoFields($value1['Name'], $value[$value1['Name']]->format('Y-m-d'));

	          			}else
	          			{
	          				SetAdoFields($value1['Name'],$value[$value1['Name']]);
          				}   
                    	$count = $count+1; 
                    }     			
          		}
           //  For K = 0 To .Fields.Count - 1
           //    SetAdoFields .Fields(K).Name, .Fields(K)
           //  Next K
                SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
                SetAdoFields("T_No",$Trans_No);
                SetAdoFields("Item",$C1_Item);
                SetAdoFields("A_No",$Ret_No);
                $Ret_No = $Ret_No + 1;
                $resp = SetAdoUpdate();
        	}

        }

     
        //'Listar las Importaciones
        $Ret_No = 1;
        $AdoRegistrosdo = $this->modelo->Listar_Importaciones($parametros['TP'],$parametros['Numero'],$parametros['Item']);
        // print_r($AdoRegistros);die();
        if(count($AdoRegistros['respuesta'])>0)
        {
     		$datos = array();
     		SetAdoAddNew("Asiento_Importaciones");
        	foreach ($AdoRegistros as $key => $value) {
        		$count = 1;
        		 $K = sqlsrv_field_metadata($AdoRegistros['stmt']); 
                 $count = 0;         

        		foreach ($K as $key1 => $value1) {
        			// print_r($value);die();
        			if($value1['Name']!='CodigoU' && $value1['Name']!='T_No' && $value1['Name']!='Item' && $value1['Name']!='A_No')
        			{
          			if(is_object($value[$value1['Name']])) 
	          			{
	          				 SetAdoFields($value1['Name'], $value[$value1['Name']]->format('Y-m-d'));

	          			}else
	          			{
	          				SetAdoFields($value1['Name'],$value[$value1['Name']]);
          				}   
                    	$count = $count+1; 
                    }     			
          		}
        		SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
                SetAdoFields("T_No",$Trans_No);
                SetAdoFields("Item",$C1_Item);
                SetAdoFields("A_No",$Ret_No);
                $Ret_No = $Ret_No + 1;                
                $resp = SetAdoUpdate();
        		
        	}
        }


        //'Listar las Compras
        $Ret_No = 1;
        $AdoRegistros = $this->modelo->Listar_las_Compras($parametros['TP'],$parametros['Numero'],$parametros['Item']);
        if(count($AdoRegistros['respuesta'])>0)
        {
        	SetAdoAddNew("Asiento_Exportaciones");
        	foreach ($AdoRegistros as $key => $value) {
        		$count = 1;
        		 $K = sqlsrv_field_metadata($AdoRegistros['stmt']); 
                 $count = 0;         

        		foreach ($K as $key1 => $value1) {
        			// print_r($value);die();
        			if($value1['Name']!='CodigoU' && $value1['Name']!='T_No' && $value1['Name']!='Item' && $value1['Name']!='A_No')
        			{
          				if(is_object($value[$value1['Name']])) 
	          			{
	          				 SetAdoFields($value1['Name'], $value[$value1['Name']]->format('Y-m-d'));

	          			}else
	          			{
	          				SetAdoFields($value1['Name'],$value[$value1['Name']]);
          				}   
                    	$count = $count+1; 
                    }     			
          		}
        	    SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
                SetAdoFields("T_No",$Trans_No);
                SetAdoFields("Item",$C1_Item);
                SetAdoFields("A_No",$Ret_No);
                $Ret_No = $Ret_No + 1;
                $resp = SetAdoUpdate();  		
        	}
        }
     
        // 'Llenar SubCuentas
        $AdoRegistros =  $this->modelo->Llenar_SubCuentas($parametros['TP'],$parametros['Numero'],$parametros['Item']);
       if(count($AdoRegistros)>0)
       {
       	  SetAdoAddNew("Asiento_SC");
    	 foreach ($AdoRegistros as $key => $value) {
                SetAdoFields("FECHA_V",$value["Fecha_V"]->format('Y-m-d'));
                SetAdoFields("TC",$value["TC"]);
                SetAdoFields("Codigo",$value["Codigo"]);
                SetAdoFields("Beneficiario",$value["Detalle"]);
                SetAdoFields("Factura",$value["Factura"]);
                SetAdoFields("Prima",$value["Prima"]);
                SetAdoFields("Valor",number_format($value["VALOR"],2,'.',''));
                SetAdoFields("Valor_Me",number_format($value["Parcial_ME"],2,'.',''));
                SetAdoFields("Detalle_SubCta",$value["Detalle_SubCta"]);
                SetAdoFields("Cta",$value["Cta"]);
                SetAdoFields("TM","1");
                SetAdoFields("DH","1");
                if($value["Parcial_ME"] > 0){
                    SetAdoFields("TM","2");
                }
                if($value["VALOR"] < 0 ){                    
                	SetAdoFields("DH","2");
                }
                SetAdoFields("T_No",$Trans_No);
                SetAdoFields("Item",$C1_Item);
                SetAdoFields("SC_No",$LnSC_No);
                SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
                $LnSC_No = $LnSC_No + 1;

                $resp = SetAdoUpdate();
    		
    	    }
       }     

                // print_r($AdoRegistros);die();

        $AdoRegistros =  $this->modelo->Llenar_SubCuentas2($parametros['TP'],$parametros['Numero'],$parametros['Item']);
         // print_r($AdoRegistros);die();
       if(count($AdoRegistros)>0)
       {
       		  SetAdoAddNew("Asiento_SC");
    	foreach ($AdoRegistros as $key => $value) {
              SetAdoFields("FECHA_V",$value["Fecha_V"]->format('Y-m-d'));
              SetAdoFields("TC",$value["TC"]);
              SetAdoFields("Codigo",$value["Codigo"]);
              SetAdoFields("Beneficiario",$value["Detalle"]);
              SetAdoFields("Factura",$value["Factura"]);
              SetAdoFields("Prima",$value["Prima"]);
              SetAdoFields("Valor",number_format($value["VALOR"],2,'.',''));
              SetAdoFields("Valor_Me",number_format($value["Parcial_ME"],2,'.',''));
              SetAdoFields("Detalle_SubCta",$value["Detalle_SubCta"]);
              SetAdoFields("Cta",$value["Cta"]);             
              SetAdoFields("T_No", $Trans_No);
              SetAdoFields("Item",$C1_Item);
              SetAdoFields("SC_No",$LnSC_No);
              SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
              if($value["Parcial_ME"] > 0){
                  SetAdoFields("TM","2");
              }else
              {
               SetAdoFields("TM","1");
              }
              if($value["VALOR"] < 0){
                  SetAdoFields("DH", "2");
              }else
              {
                SetAdoFields("DH","1"); 
              }

              $LnSC_No = $LnSC_No + 1;
    		  $resp = SetAdoUpdate();
    	}
       }
   }
}

function Tipo_De_Comprobante_No($parametros)
{
	// print_r($parametros);die();
    $y = explode('-',$parametros);
     if($y[0]=='CD'){ $tp = "Diario";}
     if($y[0]=='CI'){ $tp = "Ingresos";}
     if($y[0]=='CE'){ $tp = "Egresos";}
     if($y[0]=='ND'){ $tp = "NotaDebito";}
     if($y[0]=='NC'){ $tp = "NotaCredito";}

	return $ret = 'Comprobante de '.$tp.' No. '.$y[0].'-'.generaCeros($y[1],8);
}
function Llenar_Encabezado_Comprobante($parametros)
{
	$dato = explode('-',$parametros);
	$tp = $dato[0];$com = $dato[1];
	$parametros = array('TP'=>$tp,'Numero'=>$com,'Item'=>$_SESSION['INGRESO']['item']);
	$comprobante = $this->modelo->Encabezado_Comprobante($parametros);
	// print_r($comprobante);die();
	
     return  array('beneficiario'=>$comprobante[0]['Cliente'],'RUC_CI'=>$comprobante[0]['CI_RUC'],'email'=>$comprobante[0]['Email'],'Concepto'=>$comprobante[0]['Concepto'],'CodigoB'=>$comprobante[0]['Codigo_B'],'fecha'=>$comprobante[0]['Fecha']->format('Y-m-d'));  
}

function ingresar_asiento($parametros)
{
	// print_r($parametros);die();
	//ingresar asiento 
		$va = $parametros['va'];
		$dconcepto1 = $parametros['dconcepto1'];
		$codigo = $parametros['codigo'];
		$cuenta = $parametros['cuenta'];
		$bene = explode('-', $parametros['bene']);
		$bene = $bene[0];

		if(!is_numeric($va) || $va<=0){
			return array('resp'=>-3,'tbl'=>'','totales'=>'','obs'=>'Valor debe ser mayor a cero');
		}
		if(isset($parametros['t_no']))
		{
			$t_no = $parametros['t_no'];
		}else{
			$t_no = 1;
		}
		if(isset($parametros['efectivo_as']))
		{
			$efectivo_as = $parametros['efectivo_as'];
		}else{
			$efectivo_as = '';
		}
		if(isset($parametros['chq_as']))
		{
			$chq_as = $parametros['chq_as'];
		}else{
			$chq_as = '';
		}
		
		$moneda = $parametros['moneda'];
		$tipo_cue = $parametros['tipo_cue'];
		
		if($efectivo_as=='' || $efectivo_as==null)
		{
			$efectivo_as=$fecha;
		}
		if($chq_as=='' || $chq_as==null)
		{
			$chq_as='.';
		}
		$parcial = 0;
		if($moneda==2)
		{
			$cotizacion = $parametros['cotizacion'];
			$con = $parametros['con'];
			if($tipo_cue==1)
			{
				if($con=='/')
				{
					$debe=$va/$cotizacion;
				}else{
					$debe=$va*$cotizacion;
				}
				$parcial = $va;
				$haber=0;
			}
			if($tipo_cue==2)
			{
				if($con=='/')
				{
					$haber=$va/$cotizacion;
				}else{
					$haber=$va*$cotizacion;
				}
				$parcial = $va;
				$debe=0;
			}
		}else{
			if($tipo_cue==1)
			{
				$debe=$va;
				$haber=0;
			}
			if($tipo_cue==2)
			{
				$debe=0;
				$haber=$va;
			}
		}
		//verificar si ya existe en ese modulo ese registro
		  $stmt = []; // 202311-14 Se quita validacion a peticion de walter $this->modelo->verificar_existente($codigo,$va);
		
		//print_r($sql);die();
		
		//para contar registro
		$i=0;
		$i=count($stmt);
		if($t_no == '60')
		{
			$i=0;
		}
		//echo $i.' -- '.$sql;
		//seleccionamos el valor siguiente

		$stmt = $this->modelo->valor_siguiente();
		
		$A_No=0;
		$ii=0;
		if(count($stmt)>0)
		{
			foreach ($stmt as $key => $value) {
				$A_No = $value['A_No'];
				$ii++;
			}
		}
		if($ii==0)
		{
			$A_No++;
		}else
		{
			$A_No++;
		}
		
		//si no existe guardamos
		if($i==0)
		{
			
			$res = $this->modelo->insertar_aseinto($codigo,$cuenta,$parcial,$debe,$haber,$chq_as,$dconcepto1,$efectivo_as,$t_no,$A_No,$bene);
			if($res==-1)  
			{  
				 return array('resp'=>-1,'tbl'=>'','totales'=>'','obs'=>'no se pudo insertar en asiento');  
			}
			else
			{
				$tbl = $this->modelo->listar_asientos($tabla=1);
				$totales = ''; // ListarTotalesTemSQL_AJAX(null,null,'1','0,1,clave');
				 return array('resp'=>1,'tbl'=>$tbl,'totales'=>$totales,'obs'=>'');  			
			}
		}
		else
		{
			// 		grilla_generica($stmt,null,NULL,'1','0,1,clave','asi');
			// 		ListarTotalesTemSQL_AJAX(null,null,'1','0,1,clave');
			 return array('resp'=>-2,'tbl'=>'','totales'=>'','obs'=>'El asiento puede estar repetido');  
		}
		
	
}

function eliminar_asientos($parametros)
{
	$Trans_No = $parametros['T_No'];
	return $this->modelo->BorrarAsientos($Trans_No,true);
}


function edit_beneficiario($parametros)
{
	$bene =  explode('-',$parametros['beneficiario']);
	$bene = $bene[0];

	$datos = $this->modelo->asientos();
	if(count($datos)>0)
	{
		SetAdoAddNew("Asiento");          
        SetAdoFields("BENEFICIARIO",$bene);

        SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);
        SetAdoFieldsWhere('CodigoU',$_SESSION['INGRESO']['CodigoU']);
        SetAdoFieldsWhere('T_No',$_SESSION['INGRESO']['modulo_']);
        SetAdoUpdateGeneric();
	}

	// print_r($datos);die();
	// print_r($parametros);die();
}

function CallListar_Comprobante_SP($parametros)
{
	extract($parametros);
	$CodigoCC = G_NINGUNO;
    $_SESSION['Trans_No'] = 1;
    $_SESSION['Ln_No'] = 1;
    $_SESSION['Ret_No'] = 1;
    $_SESSION['LnSC_No'] = 1;

    BorrarAsientos($_SESSION['Trans_No'],true);

	$Co = datos_Co();
	$Co['CodigoInvModificar'] = "";
	$Co['TP'] = addslashes($TP);
	$Co['Numero'] = addslashes($NumeroComp);

	if (strlen(@$Co['TP']) < 2) {
        $Co['TP'] = G_COMPDIARIO;
    }

    Control_Procesos(G_NORMAL, "Modificar Comprobante de: " . $Co['TP'] . " No. " . $Co['Numero']);
    $data = Listar_Comprobante_SP($Co, $_SESSION['Trans_No'], $_SESSION['Ln_No'], $_SESSION['Ret_No'], $_SESSION['LnSC_No']);
    $Co = $data['C1'];
    $_SESSION['Trans_No'] = $data['Trans_No'];
    $_SESSION['Ln_No'] = $data['Ln_No'];
    $_SESSION['Ret_No'] = $data['Ret_No'];
    $_SESSION['LnSC_No'] = $data['LnSC_No'];

	$NumComp_format = str_pad($Co['Numero'], 8, "0", STR_PAD_LEFT);
	$_SESSION['Co'] = $Co;
	return array('Co' => $Co, 'NumComp_format'=>$NumComp_format);
}

function load_subcuentas($parametros){//TODO: Sumatoria_CC
    $this->modelo->activate_cc($parametros);
    $tmp = $this->modelo->load_subcuentas($parametros);
    if(empty($tmp)){
        $tablaHtml = '
        <style>
        .customT{
            display:revert;
        }
       // #myTable {
         //   margin-left: auto;
           // margin-right: auto;
       // }
        </style>
        <table id="myTable" class="table table-sm">
        <thead class="customT">
            <tr style="display:revert;" class="customT">
                <th style="width:200px" class="text-center">Beneficiario</th>
                <th style="width:75px" class="text-center">Valor</th>
            </tr>
            </thead>
        </table>
        ';
        return $tablaHtml;
    }

    $tablaHtml = '
    <style>
        .customT{
            display:revert;
        }
    </style>
		<table id="myTable" class="table table-sm">
			<thead id="cabecera" class="customT">
				<tr>';

    $columnas = array_keys($tmp[0]);
    $columnasDeseadas = ['Beneficiario', 'Valor'];

    foreach ($columnas as $columna) {
        if (in_array($columna, $columnasDeseadas)) {
        	if($columna=='Valor')
        	{
            	$tablaHtml .= '<th style="width: 100px;">' . $columna . '</th>'; // Añadir solo las columnas deseadas
            }else{

            	$tablaHtml .= '<th>' . $columna . '</th>'; // Añadir solo las columnas deseadas
            }
        }
    }

    $tablaHtml .= '
                 </tr>
            </thead>
            <tbody class="customT">';

            foreach ($tmp as $fila) {
                $tablaHtml .= '<tr class="customT">';
                foreach ($columnas as $columna) {
                    // De nuevo, comprobar si la columna actual está en el array de columnas deseadas
                    if (in_array($columna, $columnasDeseadas)) {
                        $valor = $fila[$columna];
                        $clase = '';
                        $editable = '';
                
                        if ($columna === 'Valor') {
                            $editable = ' contenteditable="true"';
                            $clase = "text-right editable-decimal";
                            $valor = number_format((float)$valor, 2, '.', '');
                        }else{
                            $clase = "text-left";
                        }
                
                        $tablaHtml .= '<td class="' . $clase . '"' . $editable . '>' . $valor . '</td>';
                    }
                }
                $tablaHtml .= '</tr>';
            }
		$tablaHtml .= '
			</tbody>
		</table>';
    return $tablaHtml;
}

function commandl_click($parametros){
    $this->modelo->commandl_click($parametros);
    
}

function command2_click($parametros){
    $this->modelo->command2_click($parametros);
}

function ExistenMovimientos($parametros)
{
  $Trans_No = $parametros['Trans_No'];
  $ExisteMov = 0;
  if($Trans_No <= 0 ){ $Trans_No = 1;}
  $datos = $this->modelo->ExistenMovimientos($Trans_No);
  if(count($datos)>0)
  {
  	$ExisteMov = 1;
  }
  return $ExisteMov;
}

	function Trans_SubCtas($parametros)
	{

		$datos =  $this->modelo->Trans_SubCtas($SubCta);
		print_r($datos);
	}

	function DCBanco_LostFocus($parametros)
	{
		// print_r($parametros);die();
		 $fecha = BuscarFecha($parametros['MBoxFecha']);
	     $Cta_Aux = $parametros['CBanco'];
	     if($Cta_Aux == ""){$Cta_Aux = ".";}
	     $TextCheque = "00000001";

	     $numero_cheque = $this->modelo->Transacciones_BA($Cta_Aux,$fecha);
	     if(count($numero_cheque)>0)
	     {
	     	if($numero_cheque[0]['Ultimo_Chep']!='')
	     	{
	     	 	$TextCheque = generaCeros($numero_cheque[0]['Ultimo_Chep']+1,8);
	     	}
	     }
	     return $TextCheque;
	     
	}

	function guardar_diferencia($parametros)
	{
		// print_r($parametros);die();
	   $asientos = $this->modelo->asientos();
       if(isset($parametros['efec'])){
          $OpcDH = 2; $ValorDH = $parametros['vae'];
          $Codigo = Leer_Cta_Catalogo($parametros['conceptoe']);
          $Cadena = $Codigo['Cuenta'];
          if($Codigo['Moneda_US']){ $ValorDH = number_format($ValorDH * $parametros['cotizacion'], 2,'.','');}
          $Fecha_Vence = $parametros['fecha1'];
          $NoCheque = G_NINGUNO;
          $AdoAsientos = array('OpcDH'=>$OpcDH,'CodigoCli'=>'.','CodigoCC'=>'.','DetalleComp'=>'.','ValorDH'=>$ValorDH,'Codigo'=>$Codigo['Codigo_Catalogo'],'Cuenta'=>$Codigo['Cuenta'],'Fecha_Vence'=>$parametros['fecha1'],'NoCheque'=>$NoCheque,'Trans_No'=>$parametros['Trans_No'],'Ln_No'=>(count($asientos)+1),'Dolar'=>$parametros['cotizacion'],'Moneda_US'=>$Codigo['Moneda_US'],'SubCta'=>$Codigo['SubCta']);
           InsertarAsientosC($AdoAsientos);
       	}
        $SumaBancos = 0;
        $A_No = count($asientos)+1;
        $AdoAsientos = $this->modelo->asientos(false,false);
        // print_r($AdoAsientos);die();
        $calculos = CalculosTotalAsientos($AdoAsientos);
               if(isset($parametros['ban'])){
                  $SumaBancos =$calculos['SumaDebe'] - $calculos['SumaHaber'];
                  $OpcDH = 2; $ValorDH = $calculos['SumaDebe'] - $calculos['SumaHaber'];
                  $Codigo = Leer_Cta_Catalogo($parametros['conceptob']);
                  $Cadena = $Codigo['Cuenta'];
                  $DGAsientosB = $this->modelo->cargar_asientosB();
                  if(count($DGAsientosB)>0)
                  {
                  	foreach ($DGAsientosB as $key => $value) {
                  		 $Fecha_Vence = $value["EFECTIVIZAR"];
                      	 $NoCheque = $value["CHEQ_DEP"];
                  //     .fields("VALOR") = SumaBancos
                  	}
                  	 $AdoAsientos = array('OpcDH'=>$OpcDH,'CodigoCli'=>'.','CodigoCC'=>'.','DetalleComp'=>'.','ValorDH'=>$ValorDH,'Codigo'=>$Codigo['Codigo_Catalogo'],'Cuenta'=>$Codigo['Cuenta'],'Fecha_Vence'=>$Fecha_Vence,'NoCheque'=>$NoCheque,'Trans_No'=>$parametros['Trans_No'],'Ln_No'=>(count($asientos)+1),'Dolar'=>$parametros['cotizacion'],'Moneda_US'=>$Codigo['Moneda_US'],'SubCta'=>$Codigo['SubCta']);
                  	  if($_SESSION['INGRESO']['OpcCoop']==1 && $AdoAsientos['Moneda_US']==1){$ValorDH = $ValorDH * $AdoAsientos['Dolar'];}

                  	  // print_r($AdoAsientos);die();
                 	 InsertarAsientosC($AdoAsientos);
                  }                 
               }
               
       	return 1;
	}

	function Facturas_Pendientes_SC($parametros)
	{
		$empresa = Empresa_data(); 
		$AgruparSubMod = $empresa[0]['Det_SubMod'];
		$lista = array();
		// print_r($parametros);die();
		$datos = $this->modelo->Facturas_Pendientes_SC($AgruparSubMod,$parametros['SubCta'],$parametros['Codigo'],$parametros['cta'],$parametros['fecha']);
		foreach ($datos as $key => $value) {
			$lista[] = array('id'=>$value['Saldos_MN'],'label'=>$value['Factura'],'data'=>$value);
		}

		return $lista;		
	}

}
?>