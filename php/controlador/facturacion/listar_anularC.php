<?php 
require_once(dirname(__DIR__,2)."/modelo/facturacion/listar_anularM.php");
require_once(dirname(__DIR__,2)."/comprobantes/SRI/autorizar_sri.php");

/**
 * 
 */
$controlador =  new listar_anularC();
if(isset($_GET['DCTipo']))
{
	// $parametros = $_POST['parametros'];
	// print_r('expression');die();
	echo json_encode($controlador->DCTipo());
}
if(isset($_GET['DCSerie']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCSerie($parametros));
}
if(isset($_GET['DCFact']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCFact($parametros));
}
if(isset($_GET['detalle_factura']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->detalle_factura($parametros));
}
if(isset($_GET['abonos_fac']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->abonos_factura($parametros));
}
if(isset($_GET['guias']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guias($parametros));
}
if(isset($_GET['contabilizacion']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->contabilizacion($parametros));
}
if(isset($_GET['resultado_sri']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->resultado_sri($parametros));
}
if(isset($_GET['anular_factura']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->anular_factura($parametros));
}
if(isset($_GET['anular']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->anular($parametros));
}
if(isset($_GET['Anular_en_masa']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->Anular_en_masa($parametros));
}
if(isset($_GET['Volver_Autorizar']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->Volver_Autorizar($parametros));
}
if(isset($_GET['exportar_excel_validador']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->exportar_excel_validador($parametros));
}
if(isset($_GET['excel_exportar']))
{
	$parametros = $_GET;
	echo json_encode($controlador->excel_exportar($parametros));
}
if(isset($_GET['actualizar_kardex']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->actualizar_kardex($parametros));
}
if(isset($_GET['validar_existencia']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->validar_existencia($parametros));
}


class listar_anularC
{
	private $modelo;
	private $sri;
	
	function __construct()
	{
		$this->modelo = new listar_anularM();
		$this->sri = new autorizacion_sri();
	}

	function DCTipo()
	{
		$datos = $this->modelo->DCTipo();
		
		$tc = array();
		foreach ($datos as $key => $value) {
			$tc[] = array('codigo'=>$value['TC'],'nombre'=>$value['TC']);
		}
		return $tc; 
	}

	function DCSerie($parametros)
	{
		$datos = $this->modelo->DCSerie($parametros['tc']);
		$serie = array();
		foreach ($datos as $key => $value) {
			$serie[] = array('codigo'=>$value['Serie'],'nombre'=>$value['Serie']);
		}
		return $serie; 
	}
	function DCFact($parametros)
	{
		$datos = $this->modelo->Listar_Factura_NotaVentas($parametros['tc'],$parametros['serie']);
		$DCFact = array();
		$DCFact[] = array('codigo'=>'','nombre'=>'Seleccione');
		foreach ($datos as $key => $value) {
			$DCFact[] = array('codigo'=>$value['Autorizacion'],'nombre'=>$value['Factura']);
		}
		return $DCFact; 
	}

	function  detalle_factura($parametros)
	{

		// print_r($parametros);die();
		sp_Actualizar_Saldos_Facturas($parametros['tc'],$parametros['serie'],$parametros['factura']);
		$TxtXML = "";
		// DGDetalle.Visible = False
		// DGDetalle.BackColor = &H80000005
		// 'Volvemos a recalcular los totales de la factura


		$TFA = $this->modelo->Listar_Factura_NotaVentas_all($parametros['tc'],$parametros['serie'],$parametros['factura'],$parametros['Autorizacion']);
		$FA = $TFA[0];
		// print_r($TFA);die();
	 	$FA = Leer_Datos_FA_NV($FA);
	 	// print_r($FA);die();
 		// 'Procesamos Factura
		  if($FA['Si_Existe_Doc']){
		    // 'Consultamos el detalle de la factura
		     $lineas = $this->modelo->detalle_factura($FA,1);
		     return array('FA'=>$FA,'detalle'=>$lineas);

		     // SQLDec = "Precio " & CStr(Dec_PVP) & "|Total 2|Total_IVA 4|."
		     // Select_Adodc_Grid DGDetalle, AdoDetalle, SQL2, SQLDec
		     // DGDetalle.Visible = True
		    // 'Recolectamos los item de la factura a buscar
		    /* 
		     FechaComp = FA.Fecha
		    
		    // 'LabelFormaPa.Caption = .Fields("Forma_Pago")
		     LabelServicio.Caption = Format$(FA.Servicio, "#,##0.00")
		     LabelConIVA.Caption = Format$(FA.Con_IVA, "#,##0.00")
		     LabelSubTotal.Caption = Format$(FA.Sin_IVA, "#,##0.00")
		     LabelDesc.Caption = Format$(FA.Descuento + FA.Descuento2, "#,##0.00")
		     
		     LabelIVA.Caption = Format$(FA.Total_IVA, "#,##0.00")
		     LabelServicio.Caption = Format$(FA.Servicio, "#,##0.00")
		     LabelTotal.Caption = Format$(FA.Total_MN, "#,##0.00")
		     LabelSaldoAct.Caption = Format$(FA.Saldo_MN, "#,##0.00")
		    */
		    // 'Consultamos los pagos Interes de Tarjetas y Abonos de Bancos con efectivo
		    // 'Procesamos el Saldo de la Factura
		  }else{
		  	/*
		     DGDetalle.Visible = True
		     RatonNormal
		     MsgBox "Esta Factura no existe."
		     DCTipo.SetFocus
		     */
		  }
  // SSTabDetalle.Tab = 0
	}

	function abonos_factura($parametros)
	{
		// print_r($parametros);die();
		return $this->modelo->abonos_factura($parametros,1);
	}
	function guias($parametros)
	{
		return $this->modelo-> guias($parametros,1);
	}
	function contabilizacion($parametros)
	{
		// print_r($parametros);die();
		 
		$this->modelo->eliminar_asiento();
        $Trans_No = 253;
        Insertar_Ctas_Cierre_SP("CXC", 1,$Trans_No);
        $FA = Leer_Datos_FA_NV($parametros);    
        $datos = $this->modelo->contabilizacion($parametros);
        // print_r($datos);die();
        if(count($datos)>0)
        {
        	foreach ($datos as $key => $value) {
        		// print_r($value);die();
        	   $Valor = $value["TTotal"]-$value["TTotal_Desc"]- $value["TTotal_Desc2"] +$value["TTotal_IVA"];
               Insertar_Ctas_Cierre_SP($FA['Cta_CxP'], $Valor,$Trans_No);
               Insertar_Ctas_Cierre_SP($value["Cta_Venta"], -1*$value["TTotal"],$Trans_No);
               Insertar_Ctas_Cierre_SP($_SESSION['SETEOS']['Cta_Desc'], $value["TTotal_Desc"],$Trans_No);
               Insertar_Ctas_Cierre_SP($_SESSION['SETEOS']['Cta_Desc2'],$value["TTotal_Desc2"],$Trans_No);
               Insertar_Ctas_Cierre_SP($_SESSION['SETEOS']['Cta_IVA'], -1*$value["TTotal_IVA"],$Trans_No);
        	}
        }         
           
        $Trans_No = 254;
        Insertar_Ctas_Cierre_SP("ABONO",1,$Trans_No);
        $AdoAuxDB = $this->modelo->AdoAuxDB($FA);
        if(count($AdoAuxDB)>0)
        {
        	foreach ($AdoAuxDB as $key => $value) {
        		Insertar_Ctas_Cierre_SP($value["Cta"], $value["TAbono"],$Trans_No);
                Insertar_Ctas_Cierre_SP($value["Cta_CxP"], -1*$value["TAbono"],$Trans_No);
        	}                         
        }
         $Debe = 0;
         $Haber = 0;
         $DGDetalle = $this->modelo->DGDetalle();
         if(count($DGDetalle)>0)
         {
         	foreach ($DGDetalle as $key => $value) {
         		// print_r($value);die();
         		$Debe = $Debe + number_format($value["DEBE"],2,'.','');
                $Haber = $Haber + number_format($value["HABER"],2,'.','');
         	}
         }
         $tbl= $this->modelo->DGDetalle($tabla=1);

         return array('LabelDebe'=>$Debe,'LabelHaber'=>$Haber,'LblDiferencia'=>$Debe-$Haber,'tbl'=>$tbl);     


	}
	function resultado_sri($parametros)
	{
		 // 'Listamos el error en la autorizacion del documento si tuvier error
		$TxtXML = '';
		$FA = Leer_Datos_FA_NV($parametros);    		
       if(strlen($FA['Autorizacion']) >= 13)
       {
         // CheqClaveAcceso.Caption = "Clave de Accceso: " & FA.TC & " "
          $Cadena = SRI_Mensaje_Error($FA['ClaveAcceso']);
          if(strlen($Cadena) > 1){
             $TxtXML = "Clave de Accceso: ".$FA["ClaveAcceso"]." <br> ".
                    "--------------------------------------------------- <br>".
                    $Cadena."<br> ".
                    "---------------------------------------------------";
          }else{
             $TxtXML = "Clave de Accceso: ".$FA["ClaveAcceso"]." <br>".
             	"--------------------------------------------------------------- <br> ".
               "OK: No existe ningun error en su aprobacion  <br> ".
               "----------------------------------------------------------------";
          }
       }
       return $TxtXML;
	}

	function anular_factura($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->anular_factura($parametros);
		if(count($datos)>0)
		{
			if($datos[0]['T']=='A')
			{
				return -1;
			}else
			{
				return 1;				
			}
		}
	}

	function anular($parametros)
	{
		// print_r($parametros);die();
		$TFA = $this->modelo->Listar_Factura_NotaVentas_all($parametros['TC'],$parametros['Serie'],$parametros['Factura'],$parametros['Autorizacion']);
		$FA = $TFA[0];
		// print_r($TFA);die();
	 	$FA = Leer_Datos_FA_NV($FA);
	
  		$fecha = FechaValida($parametros['MBoxFecha']);
  		$NC['Serie'] = '';$NC['Autorizacion'] = '';
  		$Total_Saldos_ME = 0;
  		$NombreCliente = $FA['Cliente'];
  		if($FA['Factura'] > 0)
  		{
		    $FA['Fecha_NC'] = $parametros['MBoxFecha'];
		    $FA['Serie_NC'] =$NC['Serie'];
		    $FA['Autorizacion_NC'] = $NC['Autorizacion'];
		    //------- descomentar cuando este la funcion de procesos)
        	Control_Procesos("A", "Anulación de ".$FA['TC']."-".$FA['Serie']." No. ".generaCeros($FA['Factura'],9));
        	$ConceptoComp = "Anulación de la ".$FA['TC']."-".$FA['Serie']." No. ".$FA['Factura'].", del Cliente: ".$NombreCliente;

        	$this->modelo->delete_trans_abonos($FA);      
           
       // 'Borramos las facturas del kardex de anulacion
       		$this->modelo->delete_Trans_kardex($FA);
           
       		$this->modelo->actualizar_factura($FA,$parametros['MBoxFecha'],$ConceptoComp,$Total_Saldos_ME);

        	$this->modelo->actualizar_detalle_factura($FA);
        	return 1;
  		}
  	}
  	function Anular_en_masa($parametros)
  	{
  		// print_r($parametros);die();
  		/*
		Dim IdFact As Long
  		If ClaveAdministrador Then*/

     if(intval($parametros['TextFDesde']) <= 0){$TextFDesde = "0"; }
     if(intval($parametros['TextFHasta']) <= 0){$TextFHasta = "0"; }
     $TextFDesde = TextoValido($parametros['TextFDesde']);
     $TextFHasta = TextoValido($parametros['TextFHasta']);
     $Factura_Desde = intval($TextFDesde);
     $Factura_Hasta = intval($TextFHasta);
     $MBFecha = date('Y-m-d');
     $FA['Cod_CxC'] = $parametros['Cod_CxC'];
	 $FA['Cta_CxP'] = $parametros['Cta_CxP'];


     Control_Procesos("F", "Anulacion de Facturas en masa desde ".$Factura_Desde." a la ".$Factura_Hasta);
     //Progreso_Barra.Mensaje_Box = "Anulacion de Facturas en masa"
     // Progreso_Iniciar
     if(($Factura_Hasta - $Factura_Desde) >= 0)
     {
     	$IdFact = $Factura_Desde;
        for($i = $IdFact;$i <=$Factura_Hasta;$i++)
        {
           // Progreso_Barra.Mensaje_Box = "Anular Factura No. " & Format(IdFact, "000000000")
        	$FA['TC'] = $parametros['TC'];
        	$FA['Serie'] = $parametros['Serie'];          
        	$FA['Autorizacion'] = $parametros['Autorizacion'];
            $FA['Factura'] = $IdFact;

            $this->modelo->delete_factura($FA);
            
            $this->modelo->delete_detalle_factura($FA);
            
            $this->modelo->delete_trans_abonos($FA);
            
            SetAdoAddNew("Facturas");
            SetAdoFields("T", "A");
            SetAdoFields("CodigoC", "9999999999");
            SetAdoFields("Razon_Social", "CONSUMIDOR FINAL");
            SetAdoFields("RUC_CI", "9999999999999");
            SetAdoFields("TB", "R");
            SetAdoFields("Fecha", $MBFecha);
            SetAdoFields("Fecha_C", $MBFecha);
            SetAdoFields("Fecha_V", $MBFecha);
            SetAdoFields("TC", $FA['TC']);
            SetAdoFields("Cod_CxC", $FA['Cod_CxC']);
            SetAdoFields("Cta_CxP", $FA['Cta_CxP']);
            SetAdoFields("Factura",$FA['Factura']);
            SetAdoFields("Serie", $FA['Serie']);
            SetAdoFields("Autorizacion", $FA['Autorizacion']);
            SetAdoFields("Item", $_SESSION['INGRESO']['item']);
            SetAdoFields("Periodo", $_SESSION['INGRESO']['periodo']);
            SetAdoFields("CodigoU", $_SESSION['INGRESO']['CodigoU']);
            SetAdoUpdate();
        }
        return 1;
     }else{ return -1;}
	}


	function Volver_Autorizar($parametros)
	{
		$TFA = $this->modelo->Listar_Factura_NotaVentas_all($parametros['TC'],$parametros['Serie'],$parametros['Factura'],$parametros['Autorizacion']);
		$FA = $TFA[0];
		$FA = Leer_Datos_FA_NV($FA);
		if(date('Y-m-d') <= $_SESSION['INGRESO']['Fecha_ce'])
		  {
		    Actualizar_Razon_Social($parametros['MBFecha']);
		    $TxtXML = "";
		    // print_r($FA);die();
		    if(strlen($FA['Autorizacion']) == 13 && $FA['Estado_SRI'] <> "OK")
		    {
		    	$FA['FacturaNo'] = $FA['Factura'];		    	
		    	$clave = $this->sri->Clave_acceso($FA['Fecha']->format('Y-m-d'),'01', $FA['Serie'],$FA['Factura']); 
		    	$ArrayAutorizacion = $this->sri->Autorizar_factura_o_liquidacion($FA);			    	
		    	$imp = $FA['Serie'].'-'.generaCeros($FA['Factura'],7);

           		$res= array('respuesta'=>$ArrayAutorizacion,'pdf'=>$imp,'clave'=>$clave,'rodillo'=>$_SESSION['INGRESO']['Impresora_Rodillo']);	       		
		        if($ArrayAutorizacion == 1){ 
		        	$this->modelo->pdf_factura_elec($FA['Factura'],$FA['Serie'],$FA['codigoCliente'],$imp,$clave,$periodo=false,1,1);
		        	return $res; 
		        }
		        //dr devolvio sin autorizad
		       
		    }else{
		       // 'Pagina de Conexion con el SRI
		       // Progreso_Barra.Mensaje_Box = "Actualizando XML"
		       $URLAutorizacion = Leer_Campo_Empresa("Web_SRI_Autorizado");
		       // print_r($URLAutorizacion);die();
		       $SRI_Autorizacion['Clave_De_Acceso'] = $FA['ClaveAcceso'];
		       $SRI_Autorizacion['Estado_SRI'] = "CF";
		       $SRI_Autorizacion['Error_SRI'] = "";
		       $RutaXMLAutorizado = dirname(__DIR__,1).'/comprobantes/entidades/entidad_'.generaCeros($_SESSION['INGRESO']['IDEntidad'],3).'/CE'.generaCeros($_SESSION['INGRESO']['item'],3)."/Autorizados/".$FA['ClaveAcceso'].".xml";

		       $RutaXMLRechazado =dirname(__DIR__,1).'/comprobantes/entidades/entidad_'.generaCeros($_SESSION['INGRESO']['IDEntidad'],3).'/CE'.generaCeros($_SESSION['INGRESO']['item'],3)."/No_autorizados/".$FA['ClaveAcceso'].".xml";
		       for($Tiempo_Espera = 1;$Tiempo_Espera<=3; $Tiempo_Espera++)
		       {
		       	// print_r($FA);die();
		       		$ArrayAutorizacion = $this->sri->Autorizar_factura_o_liquidacion($FA);		       		
		           if($ArrayAutorizacion == 1){ return 1; break;}
		       }
		      // 'Ok Documento Firmado y Autorizado
		       if($ArrayAutorizacion == 1 /*1 es igual a "AUTORIZADO"*/ )
		       {
		          // Progreso_Barra.Mensaje_Box = "[Ok] " & "Actualizando " & FA.TC _
		          //                            & " No. " & FA.Serie & "-" & Format(FA.Factura, "000000000")
		          // Progreso_Esperar True
		          // SRI_Autorizacion.Estado_SRI = "OK"
		          // SRI_Autorizacion.Error_SRI = "OK"
		          // SRI_Autorizacion.Autorizacion = ArrayAutorizacion(1)
		          // SRI_Autorizacion.Fecha_Autorizacion = Format$(MidStrg(ArrayAutorizacion(2), 1, 10), "dd/MM/yyyy")
		          // SRI_Autorizacion.Hora_Autorizacion = MidStrg(ArrayAutorizacion(2), 12, 8)
		          // SRI_Autorizacion.Documento_XML = Leer_Archivo_Texto(RutaXMLAutorizado)
		          // SRI_Actualizar_Autorizacion_Factura FA, SRI_Autorizacion
		       }else{
		          // SRI_Autorizacion.Error_SRI = ArrayAutorizacion(0) & " " & ArrayAutorizacion(1)
		       }
		       return 1;
		       // MsgBox "Esta Factura ya esta autorizada"
		       
		    }
		  }else{
		  	return -1;
		   /* RatonNormal
		    MsgBox MensajeNoAutorizarCE*/
		  }
	}

	function exportar_excel_validador($parametros)
	{
		$FA = $this->modelo->Listar_Factura_NotaVentas_all($parametros['TC'],$parametros['Serie'],$parametros['Factura'],$parametros['Autorizacion']);
		$detalles = $this->modelo->detalle_factura($FA[0]);
		if(count($detalles)>0)
		{
			return 1;
		}else
		{
			return -1;
		}
		// print_r($detalles);die();
	}

	function excel_exportar($parametros)
	{
		// falta de estructurar
		$FA = $this->modelo->Listar_Factura_NotaVentas_all($parametros['TC'],$parametros['Serie'],$parametros['Factura'],$parametros['Autorizacion']);
		$detalles = $this->modelo->detalle_factura($FA[0]);


		$head = array();
		$head_size = array();
		foreach ($detalles as $key => $value) {
			foreach ($value as $key2 => $value2) {
				array_push($head, $key2);
				array_push($head_size,strlen($key2)*5);
			}			
		}

		// print_r($head);die();

		 $tablaHTML =array();
	   	 $tablaHTML[0]['medidas']=$head_size;
         $tablaHTML[0]['datos']=$head;
         $tablaHTML[0]['tipo'] ='C';
         $pos = 1;
		foreach ($detalles as $key => $value) {
			$line = array();
			foreach ($head as $key2 => $value2) {				
				array_push($line, $value[$value2]);
			}
			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
	         $tablaHTML[$pos]['datos']=$line;
	         $tablaHTML[$pos]['tipo'] ='N';
	         $pos+=1;
		}
	    excel_generico($titulo='DETALLE FACTURA',$tablaHTML);

	}

	function actualizar_kardex($parametros)
	{
		// print_r($parametros);die();
		$TFA = $this->modelo->Listar_Factura_NotaVentas_all($parametros['TC'],$parametros['Serie'],$parametros['Factura'],$parametros['Autorizacion']);
		$FA = $TFA[0];
		$detalles = $this->modelo->detalle_factura($FA);
        // print_r($detalles);die();
         if(count($detalles)>0)
         {
         	$this->modelo->delete_trans_kardex2($FA);
            
            foreach ($detalles as $key => $value) {
            	$DatInv =  Leer_Codigo_Inv($value["Codigo"], $FA['Fecha']->format('Y-m-d'), $value["CodBodega"], $value["CodMarca"]);
            	// print_r($DatInv);die();
                if($DatInv['respueta']==1)
                {
                	$DatInv = $DatInv['datos'];
                   if($DatInv['Costo'] > 0)
                   {
                      SetAdoAddNew("Trans_Kardex");
                      SetAdoFields("T", G_NORMAL);
                      SetAdoFields("TC", $FA['TC']);
                      SetAdoFields("Serie", $FA['Serie']);
                      SetAdoFields("Fecha", $FA['Fecha']);
                      SetAdoFields("Factura", $FA['Factura']);
                      SetAdoFields("Codigo_P", $FA['CodigoC']);
                      SetAdoFields("CodBodega", $value["CodBodega"]);
                      SetAdoFields("CodMarca", $value["CodMarca"]);
                      SetAdoFields("Codigo_Inv", $value["Codigo"]);
                      SetAdoFields("CodigoL", $FA['Cod_CxC']);
                      SetAdoFields("Lote_No", $value["Lote_No"]);
                      SetAdoFields("Fecha_Fab", $DatInv['Fecha_Fab']);
                      SetAdoFields("Fecha_Exp", $DatInv['Fecha_Exp']);
                      SetAdoFields("Procedencia", $value["Procedencia"]);
                      SetAdoFields("Modelo", $value["Modelo"]);
                      SetAdoFields("Serie_No", $value["Serie_No"]);
                      SetAdoFields("Total_IVA", $value["Total_IVA"]);
                      SetAdoFields("Porc_C", $FA['Porc_C']);
                      SetAdoFields("Salida", $value["Cantidad"]);
                      SetAdoFields("PVP", $value["Precio"]);
                      SetAdoFields("Valor_Unitario", $value["Precio"]);
                      SetAdoFields("Costo", $DatInv['Costo']);
                      SetAdoFields("Valor_Total", number_format($value["Cantidad"] * $value["Precio"],2,'.',''));
                      SetAdoFields("Total", number_format($value["Cantidad"] * $DatInv['Costo'], 2,'.',''));
                      SetAdoFields("Detalle",substr("FA: ".$FA['Cliente'],0, 100));
                      SetAdoFields("Codigo_Barra", $DatInv['Codigo_Barra']);
                     // 'SetAdoFields "Orden_No", $value["Numero"]
                      SetAdoFields("Cta_Inv",$DatInv['Cta_Inventario']);
                      SetAdoFields("Contra_Cta",$DatInv['Cta_Costo_Venta']);
                      SetAdoFields("Item",$_SESSION['INGRESO']['item']);
                      SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
                      SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
                      SetAdoUpdate();
                   }
                   
                }
               // 'Salida si es por recetas
                $AdoDBReceta = $this->modelo->Catalogo_Recetas($value["Codigo"]);
               
                if(count($AdoDBReceta)> 0)
                {
                	foreach ($AdoDBReceta as $key2 => $value2) 
                	{                	
                		$DatInv =  Leer_Codigo_Inv($value2["Codigo_Receta"],date('Y-m-d'), $value["CodBodega"], $value["CodMarca"]);
                      if($DatInv['respueta']==1)
                      {
                      	$DatInv = $DatInv['datos'];
                         if($DatInv['Costo'] > 0)
                         {
                            $CantidadAnt = $value["Cantidad"] * $value2["Cantidad"];
                            $ValorTotal = number_format($CantidadAnt * $DatInv['Costo'], 2,'.','');
                            SetAdoAddNew("Trans_Kardex");
                            SetAdoFields("T", G_NORMAL);
                            SetAdoFields("TC", $FA['TC']);
                            SetAdoFields("Serie", $FA['Serie']);
                            SetAdoFields("Fecha", $FA['Fecha']);
                            SetAdoFields("Factura", $FA['Factura']);
                            SetAdoFields("Codigo_P", $FA['CodigoC']);
                            SetAdoFields("CodBodega",$value["CodBodega"]);
                            SetAdoFields("CodMarca", $value["CodMarca"]);
                            SetAdoFields("Codigo_Inv", $value2["Codigo_Receta"]);
                            //      SetAdoFields "CodigoL", FA.Cod_CxC
                            //      SetAdoFields "Lote_No", .fields("Lote_No")
                            //      SetAdoFields "Fecha_Fab", .fields("Fecha_Fab")
                            //      SetAdoFields "Fecha_Exp", .fields("Fecha_Exp")
                            //      SetAdoFields "Procedencia", .fields("Procedencia")
                            //      SetAdoFields "Modelo", .fields("Modelo")
                            //      SetAdoFields "Serie_No", .fields("Serie_No")
                            //      SetAdoFields "Porc_C", .fields("Porc_C")
                            SetAdoFields( "PVP", $DatInv['Costo']);
                            SetAdoFields( "Valor_Unitario", $DatInv['Costo']);
                            SetAdoFields( "Salida", $CantidadAnt);
                            SetAdoFields( "Valor_Total", $ValorTotal);
                            SetAdoFields( "Costo", $DatInv['Costo']);
                            SetAdoFields( "Total", $ValorTotal);
                            SetAdoFields( "Detalle", substr("FA: RE-".$FA['Cliente'],0, 100));
                            SetAdoFields( "Cta_Inv", $DatInv['Cta_Inventario']);
                            SetAdoFields( "Contra_Cta", $DatInv['Cta_Costo_Venta']);
                            SetAdoFields( "Item", $_SESSION['INGRESO']['item']);
                            SetAdoFields( "Periodo", $_SESSION['INGRESO']['periodo']);
                            SetAdoFields( "CodigoU", $_SESSION['INGRESO']['CodigoU']);
                            SetAdoUpdate();
                        }
                      }
                    }
                }
            }//fin de primer ofreach
        }//fin del primer if

        return 1;
        // MsgBox "Proceso Terminado"
	}

	function validar_existencia($parametros)
	{
		// print_r($parametros);die();
	}

}
?>