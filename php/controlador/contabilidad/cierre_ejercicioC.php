<?php
include(dirname(__DIR__,2).'/modelo/contabilidad/cierre_ejercicioM.php');
include(dirname(__DIR__,2).'/funciones/funciones.php');
/**
 * 
 */

$controlador = new cierre_ejercicioC();
if(isset($_GET['procesar']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->Procesar_Cierre_Ejercicio($parametros));
}

if(isset($_GET['grabar']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->Grabar_Cierre_Ejercicio($parametros));
}
if(isset($_GET['actualizar']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->Actualizar_Cierre_Ejercicio($parametros));
}
if(isset($_GET['imprimir']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->Imprimir($parametros));
}

 class cierre_ejercicioC
 {
 	private $modelo;
 	private $Trans_No;
 	function __construct()
 	{
 		$this->modelo = new cierre_ejercicioM();
 		$this->Trans_No = 1;
 	}

 	function Procesar_Cierre_Ejercicio($parametro)
 	{
 		print_r($parametro);die();
	    $Evaluar = True;
	    $ErrorSubCtas = False;
	    $Ln_No = 1;
	    $TipoCta = G_NINGUNO;
	    $Fecha_Vence = $_SESSION['INGRESO']['Fechaf'];
	    $CheqSinConc = $parametro['CheqSinConc'];
	    $MBoxCtaI = $parametro['MBoxCtaI'];
	    $Trans_No = $this->Trans_No;
	    
	    mayorizar_inventario_sp($_SESSION['INGRESO']['Fechaf']);
  
   // 'Variables Generales de Entrada
   // 'CodigoCli, ValorDH, Dolar, OpcTM, OpcDH, Codigo, Cuenta, Fecha_Vence, NoCheque, Trans_No, DetalleComp

    	$this->modelo->UPDATE_Trans_SubCtas_C();
    	$this->modelo->UPDATE_Trans_SubCtas_P();
    
  

	  if($CheqSinConc=='true')
	  	{
	  		$this->modelo->UPDATE_Transacciones();
	  	}
 		//'IniciarAsientosDe DGBalance, AdoCtas
  		$CodigoCli =G_NINGUNO;

  		$this->modelo->DELETE_Asiento_K($Trans_No);
  		$this->modelo-> DELETE_Asiento_R($Trans_No);
    
 		// 'ProcesarBalance CierreEjercicio, FechaIni, FechaFin, AdoCtas, AdoTrans, False
 		$AdoCtas = IniciarAsientosDe($Trans_No);
	  // DGBalance.Visible = False
	  // RatonReloj
  		$Codigo1 = CambioCodigoCta($MBoxCtaI);
  		$Leer_Codigo_cta = Leer_Cta_Catalogo($Codigo1);
  		$Codigo = Leer_Codigo_cta['Codigo_Catalogo'];
  		$Cuenta = $Leer_Codigo_cta['Cuenta']; 

  		if($Cuenta == G_NINGUNO){ return -2; /*"Cuenta no asignada en el Catalogo de Cuentas" */ }
    	$AdoTrans = $this->modelo->SELECT_Catalogo_Cuentas($Codigo);
  		$Contador = 0;

	   if(count($AdoTrans)>0)
	   {
	      foreach ($AdoTrans as $key => $value) 
	      {	      	
	          $DetalleComp = G_NINGUNO;
	          $Contador = $Contador + 1;
	          $SiTieneSubModulo = False;
	          $Debe = 0; $Haber = 0;
	          $TipoCta = $value["DG"];
	          $Moneda_US = $value["ME"];
	          $Codigo = $value["Codigo"];
	          $Cuenta = $value["Cuenta"];
	          $TipoDoc = $value["TC"];
	          $Total = number_format($value["Saldo_Total"], 2,'.','');
	          $Total_ME = number_format($value["Saldo_Total_ME"], 2,'.','');
	          if($_SESSION['INGRESO']['OpcCoop'])
	          {
	             if($_SESSION['INGRESO']['Moneda'])
	             {
	                switch (substr($Codigo, 1, 1))
	                {
	                  case "1": if($Total_ME >= 0){ $Debe = $Total_ME; }else{ $Haber = -$Total_ME;} break;
	                  case "2": if($Total_ME >= 0){ $Haber = $Total_ME; }else{ $Debe = -$Total_ME;} break;
	                  case "3": if($Total_ME >= 0){ $Haber = $Total_ME; }else{ $Debe = -$Total_ME;} break;
	                }
	             }else{
	                switch (substr($Codigo, 1, 1))
	                {
	                  case "1": if($Total >= 0){ $Debe = $Total; }else{$Haber = -$Total;}break;
	                  case "2": if($Total >= 0){ $Haber =$Total; }else{$Debe = -$Total;}break;
	                  case "3": if($Total >= 0){ $Haber =$Total; }else{$Debe = -$Total;}break;
	                }
	             }
	          }else{
	              switch (substr($Codigo, 1, 1))
	                {
	               	case "1": if($Total >= 0){$Debe = $Total; } else{ $Haber = -$Total;} break;
	               	case "2": if($Total >= 0){$Haber = $Total; } else{ $Debe = -$Total;} break;
	               	case "3": if($Total >= 0){$Haber = $Total; } else{ $Debe = -$Total;} break;
	             	}
	          }
	          $OpcTM = 1;
	         // 'If Debe < 0 Or Haber < 0 Then MsgBox Debe & vbCrLf & Haber
	          if($Debe > 0)
	          {
	             $OpcDH = 1
	             $ValorDH = $Debe;
	          }else{
	             $OpcDH = 2
	             $ValorDH = $Haber;
	          }
	         // 'MsgBox Codigo & vbCrLf & ValorDH
	         // 'If Codigo = "1.1.03.11.04" Then MsgBox TipoDoc & vbCrLf & TCredito

	          switch($TipoDoc)
	          {
	            case "BA":               //'(BA) Si la cuenta es de bancos
	                 $SumaCheqDebe = 0;
	                 $SumaCheqHaber = 0;
	                 $TempBaOpcDH = $OpcDH;
	                 $TempBaValorDH = $ValorDH;

	                 $AdoCheques = $this->modelo->SELECT_Transacciones($Codigo,$FechaFin);

	                 if(count($AdoCheques) > 0)
	                 {
	                   // 'Insertamos Cheques girados y no cobrados
	                    foreach ($AdoCheques as $key => $value) 
	                    {	                    
	                       $ValorDH = 0;
	                       $SumaCheqDebe = $SumaCheqDebe + $value["Debe"];
	                       $SumaCheqHaber = $SumaCheqHaber + $value["Haber"];
	                       $CodigoCli = $value["Codigo_C"];
	                       if($value["Debe"] > 0){
	                          $ValorDH = $value["Debe"];
	                          $OpcDH = 1
	                          // $AdoCtas['DEBE'] = $ValorDH ;
	                       }else if($value["Haber"] > 0){
	                          $ValorDH = $value["Haber"];
	                          $OpcDH = 2
	                          // $AdoCtas['HABER'] = $ValorDH ;
	                       }
	                       $Fecha_Vence = $value["Fecha_Efec"]->format('Y-m-d');
	                       $NoCheque = $value["Cheq_Dep"]
	                       $DetalleComp = $value["TP"] "-".generaCeros($value["Numero"], 8).". ".trim($value["Cliente"]);

								 // $AdoCtas['CODIGO'] = $Codigo ;
							    // $AdoCtas['CUENTA'] = $Cuenta ;
							    // $AdoCtas['PARCIAL_ME'] = 0;
							    // $AdoCtas['CHEQ_DEP'] = $NoCheque ;
							    // $AdoCtas['DETALLE'] = $DetalleComp ;
							    // $AdoCtas['EFECTIVIZAR'] = $Fecha_Vence ;
							    // $AdoCtas['CODIGO_C'] = $CodigoCli;
							    // $AdoCtas['BENEFICIARIO'] = $value["Cliente"];						    
							    // $AdoCtas['T_No'] => $Ln_No;
							    // $AdoCtas['Item'] => $_SESSION['INGRESO']['item'];
							    // $AdoCtas['CodigoU'] => $_SESSION['INGRESO']['CodigoU'];
							    // $AdoCtas['A_No'] => $key+1; 

	                      // 'MsgBox "Cheq: " & ValorDH
	                       InsertarAsiento AdoCtas  ///ver como hacer esta funcion
	                       AdoCheques.Recordset.MoveNext

	                    }
	                 }
	                 $CodigoCli = G_NINGUNO;
	                 $Fecha_Vence = $FechaFinal;
	                 $NoCheque = G_NINGUNO;
	                 $DetalleComp = G_NINGUNO;
	                 $OpcDH = $TempBaOpcDH;
	                 $ValorDH = $TempBaValorDH;
	                // 'MsgBox ValorDH
	                 if($OpcDH == 1){
	                    $ValorDH = $ValorDH + $SumaCheqHaber -$SumaCheqDebe;
	                 }else{
	                    $ValorDH = $ValorDH - $SumaCheqDebe + $SumaCheqHaber;
	                 }
	                 if($ValorDH < 0){
	                    $ValorDH = -$ValorDH;
	                    $OpcDH = 2;
	                 }
	                 InsertarAsiento AdoCtas   ///ver como hacer esta funcion
	                 break;
	            Case "C":    //'CxC Asignamos los submodulos
	                 $TDebito = 0
	                 $TCredito = 0
	                 $AdoSubCtaDet = $this->SELECT_Trans_SubCtas($FechaFin,$Codigo);
	                 if(count($AdoSubCtaDet)>0){
	                 		foreach ($AdoSubCtaDet as $key => $value) {
	                       if($value["TSaldo"] > 0 ){
	                          $TDebito = $TDebito + $value["TSaldo"];
	                       }else If($value["TSaldo"] < 0){
	                          $TCredito = $TCredito + (-$value["TSaldo"]);
	                       }
	                    }
	                 }
	                 $OpcDH = 1;
	                 $ValorDH = $TDebito;
	                 InsertarAsiento AdoCtas
	                 $OpcDH = 2;
	                 $ValorDH = $TCredito;
	                 InsertarAsiento AdoCtas
	                 break;
	            case "P":   //'CxP Asignamos los submodulos
	                 $TDebito = 0;
	                 $TCredito = 0;
	                 $AdoSubCtaDet = $this->modelo->SELECT_Trans_SubCtas2($FechaFin,$Codigo);
	                 
	                 if(count($AdoSubCtaDet)> 0)
	                 {
	                    foreach ($AdoSubCtaDet as $key => $value) {
	                    	  if($value["TSaldo"] > 0 ){
	                          $TCredito = $TCredito + $value["TSaldo"];
	                       }else if($value["TSaldo"] < 0 ){
	                          $TDebito = $TDebito + (-$value["TSaldo"]);
	                       }
	                     }
	                 }
	                 $OpcDH = 2;
	                 $ValorDH = $TCredito;
	                 InsertarAsiento AdoCtas
	                 $OpcDH = 1
	                 $ValorDH = $TDebito;
	                 InsertarAsiento AdoCtas
	                 break;
	            default:
	                 InsertarAsiento AdoCtas
	          		break;
	        }
	    }
  $SumaDebe = 0; $SumaHaber = 0;
 // 'Leemos la Cta de Utilidad/Perdida
  $Codigo1 = CambioCodigoCta($MBoxCtaI);
  $Codigo = Leer_Cta_Catalogo($Codigo1);
  $Ln_No = 1;
  $DGBalance = $this->modelo->SELECT_Asiento($Trans_No);
  // Select_Adodc_Grid DGBalance, AdoCtas, SQL2
  // DGBalance.Visible = False
   if(count($DGBalance) > 0){
      foreach ($DGBalance as $key => $value) {
          $SumaDebe = $SumaDebe + $value["DEBE"];
          $SumaHaber = $SumaHaber + $value["HABER"];
          $value["A_No"] = $Ln_No;
          $Ln_No = $Ln_No + 1;
      }
       $Diferencia = $SumaDebe - $SumaHaber;
       $Codigo = $Codigo1;
       $OpcTM = 1;
       if($Diferencia > 0){
          $OpcDH = 2;
          $ValorDH = $Diferencia;
       }else{
          $OpcDH = 1
          $ValorDH = -$Diferencia;
       }
       InsertarAsiento AdoCtas  ///realizar la funcion
       $SumaDebe = 0; $SumaHaber = 0;
      foreach ($DGBalance as $key => $value) {
          $SumaDebe = $SumaDebe + $value["DEBE"];
          $SumaHaber = $SumaHaber + $value["HABER"];
      }
   }
  
 // 'Asiento de SubCtas de los saldos con fecha de vencimiento
 // RatonReloj
  $TipoSubCta = G_NINGUNO;
  // $DGBanco.Visible = False;
  
  $AdoBanco = $this->modelo->SELECT_Asiento_SC($Trans_No);

  $AdoTrans = $this->modelo->SELECT_Clientes($FechaFin);  
  // Select_Adodc AdoTrans, sSQL
 // 'MsgBox sSQL
  Contador = 0
  With AdoTrans.Recordset
   If .RecordCount > 0 Then
      .MoveFirst
       Progreso_Barra.Incremento = 0
       Progreso_Barra.Valor_Maximo = .RecordCount
       Progreso_Barra.Mensaje_Box = "SUBMODULOS"
       Progreso_Esperar
       Do While Not .EOF
          Contador = Contador + 1
          Saldo = 0
          Saldo_ME = 0
          Valor = 0
          OpcTM = 1
          Debitos = .fields("TDebitos")
          Creditos = .fields("TCreditos")
          Codigo = .fields("Codigo")
          Beneficiario = .fields("Cliente")
          SubCtaGen = .fields("Cta")
          Mifecha = .fields("Fecha_Venc")
          Factura_No = .fields("Factura")
          TipoSubCta = .fields("TC")
          Progreso_Barra.Mensaje_Box = "Submódulos de: " & SubCtaGen & " => " & Beneficiario
          Select Case .fields("TC")
            Case "C"
                 Saldo = Debitos - Creditos
                 ValorDH = Saldo
                 OpcDH = 1
                 If ValorDH < 0 Then
                    ValorDH = -ValorDH
                    OpcDH = 2
                 End If
            Case "P"
                 Saldo = Creditos - Debitos
                 ValorDH = Saldo
                 OpcDH = 2
                 If ValorDH < 0 Then
                    ValorDH = -ValorDH
                    OpcDH = 1
                 End If
          End Select
         'If SubCtaGen = "1.1.04.03.01" And Codigo = "GRUP2" And Factura_No = 18553 Then MsgBox ValorDH & " .."
          If ValorDH > 0 Then
             OpcDH1 = OpcDH
             Factura_No1 = Factura_No
             ValorSubModulo = ValorDH
             sSQL = "SELECT TS.Factura, TS.Fecha_V, TS.Debitos, TS.Creditos, C.Codigo " _
                  & "FROM Trans_SubCtas As TS, Clientes As C " _
                  & "WHERE TS.Item = '" & NumEmpresa & "' " _
                  & "AND TS.Periodo = '" & Periodo_Contable & "' " _
                  & "AND TS.Fecha <= #" & FechaFin & "# " _
                  & "AND TS.Fecha_V > #" & FechaFin & "# " _
                  & "AND TS.Codigo = '" & Codigo & "' " _
                  & "AND TS.Cta = '" & SubCtaGen & "' " _
                  & "AND TS.TC = '" & TipoSubCta & "' " _
                  & "AND TS.Factura = " & Factura_No & " " _
                  & "AND TS.T <> 'A' " _
                  & "AND TS.Codigo = C.Codigo "
             Select_Adodc AdoRet, sSQL
             If AdoRet.Recordset.RecordCount > 0 Then
               'MsgBox "Tiene prestamos superiores: " & SubCtaGen & vbCrLf & Codigo & vbCrLf & AdoRet.Recordset.RecordCount
                Do While Not AdoRet.Recordset.EOF
                   If AdoRet.Recordset.fields("Debitos") > 0 Then
                      ValorDH = AdoRet.Recordset.fields("Debitos")
                      If ValorDH = Debitos Then ValorDH = 0
                      OpcDH = 1
                   Else
                      ValorDH = AdoRet.Recordset.fields("Creditos")
                      If ValorDH = Creditos Then ValorDH = 0
                      OpcDH = 2
                   End If
                   Fecha_V1 = AdoRet.Recordset.fields("Fecha_V")
                   Factura_No = AdoRet.Recordset.fields("Factura")
                  'If SubCtaGen = "2.1.03.01.03" And Factura_No = 79498 Then MsgBox ValorSubModulo & " .."
                  'If SubCtaGen = "1.1.04.03.01" And Codigo = "GRUP2" And Factura_No = 18553 Then MsgBox ValorDH & " .."
                   If ValorDH > 0 Then
                      SetAddNew AdoBanco
                      SetFields AdoBanco, "Fecha_V", Fecha_V1
                      SetFields AdoBanco, "TC", TipoSubCta
                      SetFields AdoBanco, "Factura", Factura_No
                      SetFields AdoBanco, "Codigo", Codigo
                      SetFields AdoBanco, "Beneficiario", Beneficiario
                      SetFields AdoBanco, "Cta", SubCtaGen
                      SetFields AdoBanco, "DH", OpcDH
                      SetFields AdoBanco, "Valor", ValorDH
                      SetFields AdoBanco, "TM", OpcTM
                      SetFields AdoBanco, "Item", NumEmpresa
                      SetFields AdoBanco, "T_No", Trans_No
                      SetFields AdoBanco, "CodigoU", CodigoUsuario
                      SetUpdate AdoBanco
                      ValorSubModulo = ValorSubModulo - ValorDH
                   End If
                   AdoRet.Recordset.MoveNext
                Loop
             End If
            'If SubCtaGen = "2.1.03.01.03" And ValorSubModulo < 0 Then MsgBox ValorSubModulo & " .."
            'If ValorSubModulo < 0 Then MsgBox "Negativo: " & SubCtaGen & vbCrLf & ValorSubModulo
             If ValorSubModulo <> 0 Then
               'MsgBox SubCtaGen & vbCrLf & Codigo & vbCrLf & " Valor Submodulo = " & ValorSubModulo
                OpcDH = OpcDH1
                Factura_No = Factura_No1
                ValorDH = ValorSubModulo
                Select Case TipoSubCta
                  Case "C"
                       If ValorDH < 0 Then
                          OpcDH = 2
                          ValorDH = -ValorDH
                       End If
                  Case "P"
                       If ValorDH < 0 Then
                          OpcDH = 1
                          ValorDH = -ValorDH
                       End If
                End Select
                SetAddNew AdoBanco
                SetFields AdoBanco, "Fecha_V", Mifecha
                SetFields AdoBanco, "TC", TipoSubCta
                SetFields AdoBanco, "Factura", Factura_No
                SetFields AdoBanco, "Codigo", Codigo
                SetFields AdoBanco, "Beneficiario", Beneficiario
                SetFields AdoBanco, "Cta", SubCtaGen
                SetFields AdoBanco, "DH", OpcDH
                SetFields AdoBanco, "Valor", ValorDH
                SetFields AdoBanco, "TM", OpcTM
                SetFields AdoBanco, "Item", NumEmpresa
                SetFields AdoBanco, "T_No", Trans_No
                SetFields AdoBanco, "CodigoU", CodigoUsuario
                SetUpdate AdoBanco
             End If
          End If
          Progreso_Esperar
         .MoveNext
       Loop
   End If
  End With
  
 'Procedemos sacar los stock segun el tipo de inventario
  Progreso_Barra.Mensaje_Box = "RECORRIENDO INVENTARIO"
  Progreso_Iniciar
  Select Case TipoKardex
    Case "SERIE": sSQL = "SELECT TK.CodBodega, TK.Codigo_Inv, TK.Serie_No, CP.Cta_Inventario, CP.Producto, CP.Costo, "
    Case "BARRA": sSQL = "SELECT TK.CodBodega, TK.Codigo_Inv, TK.Codigo_Barra, CP.Cta_Inventario, CP.Producto, CP.Costo, "
    Case Else:    sSQL = "SELECT TK.CodBodega, TK.Codigo_Inv, CP.Cta_Inventario, CP.Producto, CP.Costo, "
  End Select
  sSQL = sSQL _
       & "(SUM(TK.Entrada) - SUM(TK.Salida)) As TExistencia " _
       & "FROM Trans_Kardex As TK, Catalogo_Productos As CP " _
       & "WHERE TK.Fecha <= #" & FechaFin & "# " _
       & "AND TK.Item = '" & NumEmpresa & "' " _
       & "AND TK.Periodo = '" & Periodo_Contable & "' " _
       & "AND TK.T <> '" & Anulado & "' " _
       & "AND TK.Item = CP.Item " _
       & "AND TK.Periodo = CP.Periodo " _
       & "AND TK.Codigo_Inv = CP.Codigo_Inv "
  Select Case TipoKardex
    Case "SERIE"
         sSQL = sSQL _
              & "GROUP BY TK.CodBodega,TK.Codigo_Inv,TK.Serie_No,CP.Cta_Inventario,CP.Producto, CP.Costo " _
              & "HAVING (SUM(TK.Entrada) - SUM(TK.Salida)) <> 0 " _
              & "ORDER BY TK.CodBodega,TK.Codigo_Inv,TK.Serie_No,CP.Cta_Inventario,CP.Producto, CP.Costo "
    Case "BARRA"
         sSQL = sSQL _
              & "GROUP BY TK.CodBodega,TK.Codigo_Inv,TK.Codigo_Barra,CP.Cta_Inventario,CP.Producto, CP.Costo " _
              & "HAVING (SUM(TK.Entrada) - SUM(TK.Salida)) <> 0 " _
              & "ORDER BY TK.CodBodega,TK.Codigo_Inv,TK.Codigo_Barra,CP.Cta_Inventario,CP.Producto, CP.Costo "
    Case Else
         sSQL = sSQL _
              & "GROUP BY TK.CodBodega,TK.Codigo_Inv,CP.Cta_Inventario,CP.Producto, CP.Costo " _
              & "HAVING (SUM(TK.Entrada) - SUM(TK.Salida)) <> 0 " _
              & "ORDER BY TK.CodBodega,TK.Codigo_Inv,CP.Cta_Inventario,CP.Producto, CP.Costo "
  End Select
  Select_Adodc AdoInv, sSQL
  Contra_Cta = CambioCodigoCta(MBoxCtaI)
  Total = 0
  With AdoInv.Recordset
   If .RecordCount > 0 Then
       Progreso_Barra.Valor_Maximo = .RecordCount + 100
       Do While Not .EOF
          CodigoInv = .fields("Codigo_Inv")
          Cta_Inventario = .fields("Cta_Inventario")
          Cantidad = .fields("TExistencia")
          Producto = .fields("Producto")
          Cod_Bodega = .fields("CodBodega")
          Precio = Redondear(.fields("Costo"), Dec_Costo)
          If Precio <= 0 Then Precio = 0.01
          If Cantidad > 0 Then
             OpcDH = 1
             Total = Total + Redondear(Cantidad * Precio, 2)
          Else
             OpcDH = 2
             Total = Total + Redondear(Cantidad * Precio, 2)
             Cantidad = -Cantidad
          End If
          Progreso_Barra.Mensaje_Box = "Inventario de " & CodigoInv & " - " & Producto
          Progreso_Esperar
          SetAdoAddNew "Asiento_K"
          SetAdoFields "CODIGO_INV", CodigoInv
          SetAdoFields "PRODUCTO", Producto
          SetAdoFields "CANT_ES", Cantidad
          SetAdoFields "CANTIDAD", Cantidad
          SetAdoFields "VALOR_UNIT", Precio
          SetAdoFields "VALOR_TOTAL", Redondear(Cantidad * Precio, 2)
          SetAdoFields "DH", OpcDH
          SetAdoFields "CTA_INVENTARIO", Cta_Inventario
          SetAdoFields "CONTRA_CTA", Codigo1
          SetAdoFields "Item", NumEmpresa
          SetAdoFields "CodigoU", CodigoUsuario
          SetAdoFields "T_No", Trans_No
          SetAdoFields "A_No", Contador
          SetAdoFields "TC", Pendiente
          SetAdoFields "CodBod", Cod_Bodega
          Select Case TipoKardex
            Case "SERIE": SetAdoFields "Serie_No", .fields("Serie_No")
            Case "BARRA": SetAdoFields "COD_BAR", .fields("Codigo_Barra")
          End Select
          SetAdoUpdate
         .MoveNext
       Loop
   End If
  End With
  
  sSQL = "SELECT * " _
       & "FROM Asiento_SC " _
       & "WHERE Item = '" & NumEmpresa & "' " _
       & "AND CodigoU = '" & CodigoUsuario & "' " _
       & "AND T_No = " & Trans_No & " " _
       & "ORDER BY TC,Cta,Beneficiario,Fecha_V,Factura "
  Select_Adodc_Grid DGBanco, AdoBanco, sSQL
  
  sSQL = "SELECT * " _
       & "FROM Asiento_K " _
       & "WHERE Item = '" & NumEmpresa & "' " _
       & "AND T_No = " & Trans_No & " " _
       & "AND CodigoU = '" & CodigoUsuario & "' " _
       & "ORDER BY CodBod,CODIGO_INV "
  SQLDec = "VALOR_UNIT " & CStr(Dec_Costo) & "|VALOR_TOTAL 2|."
  Select_Adodc_Grid DGInv, AdoInv, sSQL
  
  sSQL = "SELECT C.Cliente,T.Fecha_Efec,T.Cheq_Dep,T.Haber As Monto,T.TP,T.Numero,CC.Cuenta " _
       & "FROM Transacciones As T,Catalogo_Cuentas As CC,Clientes As C " _
       & "WHERE T.Item = '" & NumEmpresa & "' " _
       & "AND T.Periodo = '" & Periodo_Contable & "' " _
       & "AND T.Fecha <= #" & FechaFin & "# " _
       & "AND T.C = " & Val(adFalse) & " " _
       & "AND CC.TC = 'BA' " _
       & "AND T.Haber > 0 " _
       & "AND T.T <> 'A' " _
       & "AND T.Item = CC.Item " _
       & "AND T.Periodo = CC.Periodo " _
       & "AND T.Cta = CC.Codigo " _
       & "AND T.Codigo_C = C.Codigo " _
       & "ORDER BY T.Cta,C.Cliente,T.Fecha_Efec,T.Cheq_Dep "
  Select_Adodc_Grid DGCheques, AdoCheques, sSQL
  DGBanco.Visible = True
 'Fin de subCtas
  SQL2 = "SELECT * " _
       & "FROM Asiento " _
       & "WHERE Item = '" & NumEmpresa & "' " _
       & "AND T_No = " & Trans_No & " " _
       & "AND CodigoU = '" & CodigoUsuario & "' " _
       & "ORDER BY A_No "
  Select_Adodc_Grid DGBalance, AdoCtas, SQL2
  If AdoCtas.Recordset.RecordCount > 0 Then AdoCtas.Recordset.MoveLast
  LabelTotInv.Caption = Format(Total, "#,##0.00")
  LabelTotDebe.Caption = Format(SumaDebe, "#,##0.00")
  LabelTotHaber.Caption = Format(SumaHaber, "#,##0.00")
  LabelTotSaldo.Caption = Format(SumaDebe - SumaHaber, "#,##0.00")
  CierreEjercicio.Caption = "CIERRE DEL EJERCICIO"
  RatonNormal
  DGBalance.Visible = True
  Progreso_Final
  If Round(SumaDebe - SumaHaber) <> 0 Then
     MsgBox "Usuario: " & NombreUsuario & vbCrLf & vbCrLf _
          & "No se puede Cerrar el Ejercicio Contable," & vbCrLf & vbCrLf _
          & "Revise el Catalogo de Cuentas."
  End If


 	}
 	function Grabar_Cierre_Ejercicio($parametro)
 	{

 	}

 	function Actualizar_Cierre_Ejercicio($parametro)
 	{

 	}
 	function Imprimir($parametro)
 	{

 	}
 }
?>