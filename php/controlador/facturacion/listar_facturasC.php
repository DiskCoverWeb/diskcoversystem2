<?php
require_once(dirname(__DIR__,2)."/modelo/facturacion/listar_facturasM.php");

$controlador = new listar_facturasC();

if(isset($_GET['serie']))
{
   $datos = $controlador->numeroSerie();
   echo json_encode($datos);
}

if(isset($_GET['secuencial']))
{
  $datos = $controlador->numeroSecuencial();
  echo json_encode($datos);
}

if(isset($_GET['minmaxsecuencial']))
{
  $datos = $controlador->minmaxsecuencial();
  echo json_encode($datos);
}

if(isset($_GET['buscarfactura']))
{
  $datos = $controlador->BuscarFactura();
  echo json_encode($datos);
}

class listar_facturasC
{
	private $modelo;
  private $pdf;
	public function __construct(){
    $this->modelo = new listar_facturasM();
  }

  public function factura_formatos(){
    $datos = $this->modelo->factura_formatos();
    $formatos = [];
    $formatos[0] = array('codigo'=>'','nombre'=>'No existen datos.');
    $i = 0;
    foreach ($datos as $value) {
      $formatos[$i] = array('TC'=>mb_convert_encoding($value['TC'], 'UTF-8'));
      $i++;
    }
    return $formatos;
  }

  public function numeroSerie(){
    $tc = $_POST['TC'];
    $datos = $this->modelo->numeroSerie($tc);
    $serie = [];
    $serie[0] = array('codigo'=>'','nombre'=>'No existen datos.');
    $i = 0;
    foreach ($datos as $value) {
      $serie[$i] = array('codigo'=>$value['Serie'],'nombre'=>$value['Serie']);
      $i++;
    }
    return $serie;
  }

  public function numeroSecuencial(){
    $tc = $_POST['TC'];
    $serie = $_POST['serie'];
    $datos = $this->modelo->numeroSecuencial($tc,$serie);
    $secuencial = [];
    foreach ($datos as $value) {
      $secuencial[] = array('nombre' => $value['Factura'], 'codigo' => mb_convert_encoding($value['Autorizacion']."/".$value['Clave_Acceso']."/".$value['CodigoC']."/".$value['Razon_Social']."/".$value['Factura'], 'UTF-8'));
    }
    return $secuencial;
  }

  public function minmaxsecuencial(){
    $tc = $_POST['TC'];
    $serie = $_POST['serie'];
    $datos = $this->modelo->minmaxSecuencial($tc,$serie);
    $secuencial = [];
    $secuencial[0] = array('desde'=>0,'hasta'=>0);
    if ($datos) {
      $secuencial = $datos;
    }
    return $secuencial;
  }

  public function BuscarFactura(){
    //acabar esta funcion
    $CSQL1 = ""; 
    $CSQL2 = "";
    $CSQL3 = "";
    $CSQL4 = "";
    $CSQL5 = ""; 
    $CSQL6 = "";
    $TxtXML = "";
    //DGAbonos.Visible = False
    //DGDetalle.Visible = False
    //DGAsiento.Visible = False
 
  /*ReDim CtasProc(30) As CtasAsiento
  For IE = 0 To UBound(CtasProc) - 1
      CtasProc(IE).Cta = "0"
      CtasProc(IE).Valor = 0
  Next IE
  
 'Volvemos a recalcular los totales de la factura
  With AdoFactList.Recordset
   If .RecordCount > 0 Then
      .MoveFirst
      .Find ("Factura = " & FA.Factura & " ")
       If Not .EOF Then FA.Autorizacion = .Fields("Autorizacion")
   End If
  End With
 ' MsgBox ".."
  Leer_Datos_FA_NV FA
 ' MsgBox "..."
 'Procesamos Factura
  If FA.Si_Existe_Doc Then*/
    $TC = $_POST['TC'];
    $Serie = $_POST['Serie'];
    $Autorizacion = $_POST['Autorizacion'];
    $Factura = $_POST = $_POST['Factura'];
    $datos = $this->modelo->factura($TC,$Serie,$Autorizacion,$Factura);
     /*
     SelectDataGrid DGAbonos, AdoAbonos, SQL3
     If AdoAbonos.Recordset.RecordCount > 0 Then
        Do While Not AdoAbonos.Recordset.EOF
           SetearCtasCierre AdoAbonos.Recordset.Fields("Cta")
           SetearCtasCierre AdoAbonos.Recordset.Fields("Cta_CxP")
           SetearCtasCierre AdoAbonos.Recordset.Fields("Cta_Cobrar")
           AdoAbonos.Recordset.MoveNext
        Loop
     End If
    'Consultamos el detalle de la factura
     SetearCtasCierre FA.Cta_CxP
     SetearCtasCierre Cta_IVA
     SetearCtasCierre Cta_Desc
     SetearCtasCierre Cta_Desc2
      
     InsValorCta FA.Cta_CxP, FA.Total_MN
     InsValorCta Cta_Desc, FA.Descuento
     InsValorCta Cta_Desc2, FA.Descuento2
     InsValorCta Cta_IVA, -FA.Total_IVA*/
     
    //Listamos el error en la autorizacion del documento si tuvier error
    // if (strlen($Autorizacion)) {
    //   # code...
    // }
    //  If Len(FA.Autorizacion) >= 13 Then
    //      Label20.Caption = "Clave de Accceso: " & FA.TC & " "
    //      Cadena = SRI_Mensaje_Error(FA.ClaveAcceso)
    //      If Len(Cadena) > 1 Then
    //         TxtXML = "Clave de Accceso: " & FA.ClaveAcceso & vbCrLf _
    //                & String(100, "-") & vbCrLf _
    //                & Cadena _
    //                & String(100, "-")
    //      Else
    //         TxtXML = "Clave de Accceso: " & FA.ClaveAcceso & vbCrLf _
    //                & String(100, "-") & vbCrLf _
    //                & "OK: No existe ningun error en su aprobacion" & vbCrLf _
    //                & String(100, "-")
    //      End If
    //  End If
    //  If SQL_Server Then
    //     sSQL = "UPDATE Trans_Abonos " _
    //          & "SET Tipo_Cta = CC.TC " _
    //          & "FROM Trans_Abonos As TA, Catalogo_Cuentas As CC "
    //  Else
    //     sSQL = "UPDATE Trans_Abonos As TA, Catalogo_Cuentas As CC " _
    //          & "SET TA.Tipo_Cta = CC.TC "
    //  End If
    //  sSQL = sSQL _
    //       & "WHERE TA.Item = '" & NumEmpresa & "' " _
    //       & "AND TA.Periodo = '" & Periodo_Contable & "' " _
    //       & "AND TA.TP = '" & FA.TC & "' " _
    //       & "AND TA.Serie = '" & FA.Serie & "' " _
    //       & "AND TA.Factura = " & FA.Factura & " " _
    //       & "AND TA.Autorizacion = '" & FA.Autorizacion & "' " _
    //       & "AND TA.Item = CC.Item " _
    //       & "AND TA.Periodo = CC.Periodo " _
    //       & "AND TA.Cta = CC.Codigo "
    //  Conectar_Ado_Execute sSQL
     
     /*
     'FA.Autorizacion_GR = Ninguno
     SQL2 = "SELECT Serie_GR, Remision, Clave_Acceso_GR, Autorizacion_GR, Fecha, CodigoC, Comercial, CIRUC_Comercial, " _
          & "Entrega, CIRUC_Entrega, CiudadGRI, CiudadGRF, Placa_Vehiculo, FechaGRE, FechaGRI, FechaGRF, Pedido, Zona, " _
          & "Hora_Aut_GR, Estado_SRI_GR, Error_FA_SRI, Fecha_Aut_GR, TC, Serie, Factura, Autorizacion, Lugar_Entrega, " _
          & "Periodo, Item " _
          & "FROM Facturas_Auxiliares " _
          & "WHERE Item = '" & NumEmpresa & "' " _
          & "AND Periodo = '" & Periodo_Contable & "' " _
          & "AND Remision > 0 " _
          & "AND TC = '" & FA.TC & "' " _
          & "AND Serie = '" & FA.Serie & "' " _
          & "AND Factura = " & FA.Factura & " " _
          & "AND Autorizacion = '" & FA.Autorizacion & "' "
     SelectDataGrid DGGuiaRemision, AdoGuiaRemision, SQL2
      
     SQL2 = "SELECT DF.Codigo,DF.Producto,DF.Cantidad,DF.Precio,DF.Total,DF.Total_Desc,DF.Total_Desc2,DF.Total_IVA," _
          & "ROUND(((DF.Total-(DF.Total_Desc+DF.Total_Desc2))+DF.Total_IVA),2,0) As Valor_Total,DF.Mes,DF.Ticket," _
          & "DF.Serie,DF.Factura,DF.Autorizacion,CP.Detalle,CP.Cta_Ventas,CP.Reg_Sanitario,CP.Marca,Lote_No, DF.Modelo, " _
          & "DF.Procedencia, DF.Serie_No, DF.CodigoC, Cantidad_NC, SubTotal_NC,DF.CodMarca,DF.CodBodega," _
          & "DF.Tonelaje,Total_Desc_NC,Total_IVA_NC,DF.Periodo,DF.Codigo_Barra,DF.ID " _
          & "FROM Detalle_Factura As DF,Catalogo_Productos As CP " _
          & "WHERE DF.Item = '" & NumEmpresa & "' " _
          & "AND DF.Periodo = '" & Periodo_Contable & "' " _
          & "AND DF.TC = '" & FA.TC & "' " _
          & "AND DF.Serie = '" & FA.Serie & "' " _
          & "AND DF.Autorizacion = '" & FA.Autorizacion & "' " _
          & "AND DF.Factura = " & FA.Factura & " " _
          & "AND DF.Periodo = CP.Periodo " _
          & "AND DF.Item = CP.Item " _
          & "AND DF.Codigo = CP.Codigo_Inv " _
          & "ORDER BY CP.Cta_Ventas,DF.Codigo,DF.ID "
     SQLDec = "Precio " & CStr(Dec_PVP) & "|Total 2|Total_IVA 4|."
     SelectDataGrid DGDetalle, AdoDetalle, SQL2, SQLDec
     If AdoDetalle.Recordset.RecordCount > 0 Then
        Do While Not AdoDetalle.Recordset.EOF
           Contra_Cta = AdoDetalle.Recordset.Fields("Cta_Ventas")
           SetearCtasCierre Contra_Cta
           InsValorCta Contra_Cta, -AdoDetalle.Recordset.Fields("Total")
           AdoDetalle.Recordset.MoveNext
        Loop
        AdoDetalle.Recordset.MoveFirst
     End If
      
      FinBucle = True
     'Recolectamos los item de la factura a buscar
      LabelEstado.Caption = FA.T
      Label7.Caption = FA.Grupo
      LabelFechaPe.Caption = FA.Fecha
      FechaComp = FA.Fecha
      LabelCodigo.Caption = FA.CodigoC
      LabelCliente.Caption = FA.Cliente
      Label8.Caption = FA.Razon_Social & ", CI/RUC: " & FA.CI_RUC & vbCrLf _
                     & "Dirección: " & FA.DireccionC & ", Teléfono: " & FA.TelefonoC & vbCrLf _
                     & "Emails: " & FA.EmailC & "; " & FA.EmailR & vbCrLf _
                     & "Elaborado por: " & FA.Digitador & " (" & FA.Hora & ")"
                     
      LabelVendedor.Caption = " Ejecutivo: " & FA.Ejecutivo_Venta
      DireccionGuia = FA.Comercial
      TxtAutorizacion = FA.Autorizacion
      TxtClaveAcceso = FA.ClaveAcceso
      TxtObs = FA.Observacion
      LabelTransp.Caption = FA.Nota
      Label15.Caption = DireccionGuia
     'LabelFormaPa.Caption = .Fields("Forma_Pago")
      LabelServicio.Caption = Format$(FA.Servicio, "#,##0.00")
      LabelConIVA.Caption = Format$(FA.Con_IVA, "#,##0.00")
      LabelSubTotal.Caption = Format$(FA.Sin_IVA, "#,##0.00")
      LabelDesc.Caption = Format$(FA.Descuento + FA.Descuento2, "#,##0.00")
      LabelIVA.Caption = Format$(FA.Total_IVA, "#,##0.00")
      LabelTotal.Caption = Format$(FA.Total_MN, "#,##0.00")
      LabelSaldoAct.Caption = Format$(FA.Saldo_MN, "#,##0.00")
      Select Case LabelEstado.Caption
        Case Anulado
             LabelEstado.Caption = "Anulada"
        Case Pendiente, Normal
             LabelEstado.Caption = "Pendiente"
        Case Cancelado
             LabelEstado.Caption = "Cancelada"
        Case Else
             LabelEstado.Caption = "No existe"
      End Select

     'Consultamos los pagos Interes de Tarjetas y Abonos de Bancos con efectivo
      
      FA.Total_Abonos = 0
      FA.SubTotal_NC = 0
      FA.Total_IVA_NC = 0
      SQL3 = "SELECT C,T,Fecha,Banco,Cheque,Abono,Serie,Factura,Autorizacion,Protestado,CodigoC,Cta_CxP,Cta,Tipo_Cta," _
           & "Fecha_Aut_NC,Serie_NC,Secuencial_NC,Autorizacion_NC,Clave_Acceso_NC,TP,Recibo_No,Comprobante,Estado_SRI_NC,Hora_Aut_NC,Periodo,Item,CodigoU,Cod_Ejec " _
           & "FROM Trans_Abonos " _
           & "WHERE Item = '" & NumEmpresa & "' " _
           & "AND Periodo = '" & Periodo_Contable & "' " _
           & "AND Autorizacion = '" & FA.Autorizacion & "' " _
           & "AND TP = '" & FA.TC & "' " _
           & "AND Serie = '" & FA.Serie & "' " _
           & "AND Factura = " & FA.Factura & " " _
           & "ORDER BY TP,Fecha,Cta,Cta_CxP,Abono,Banco,Cheque "
      SelectDataGrid DGAbonos, AdoAbonos, SQL3
      If AdoAbonos.Recordset.RecordCount > 0 Then
        'Len(AdoAbonos.Recordset.Fields("Clave_Acceso_NC")) >= 13 And
         Do While Not AdoAbonos.Recordset.EOF
            If AdoAbonos.Recordset.Fields("TP") <> "TJ" Then
               FA.Total_Abonos = FA.Total_Abonos + AdoAbonos.Recordset.Fields("Abono")
               If AdoAbonos.Recordset.Fields("Banco") = "NOTA DE CREDITO" Then
                  FA.Porc_NC = FA.Porc_IVA
                  FA.Fecha_NC = AdoAbonos.Recordset.Fields("Fecha")
                  FA.Fecha_Aut_NC = AdoAbonos.Recordset.Fields("Fecha_Aut_NC")
                  FA.Serie_NC = AdoAbonos.Recordset.Fields("Serie_NC")
                  FA.Nota_Credito = AdoAbonos.Recordset.Fields("Secuencial_NC")
                  FA.Autorizacion_NC = AdoAbonos.Recordset.Fields("Autorizacion_NC")
                  FA.ClaveAcceso_NC = AdoAbonos.Recordset.Fields("Clave_Acceso_NC")
                  If AdoAbonos.Recordset.Fields("Cheque") = "VENTAS" Then
                     FA.SubTotal_NC = FA.SubTotal_NC + AdoAbonos.Recordset.Fields("Abono")
                  Else
                     FA.Total_IVA_NC = FA.Total_IVA_NC + AdoAbonos.Recordset.Fields("Abono")
                  End If
               End If
            End If
           'MsgBox AdoAbonos.Recordset.Fields("Cta") & vbCrLf & AdoAbonos.Recordset.Fields("Cta_CxP") & vbCrLf & AdoAbonos.Recordset.Fields("Abono") & " - " & Anulado
            If AdoAbonos.Recordset.Fields("T") <> Anulado Then
                InsValorCta AdoAbonos.Recordset.Fields("Cta"), AdoAbonos.Recordset.Fields("Abono")
                InsValorCta AdoAbonos.Recordset.Fields("Cta_CxP"), -AdoAbonos.Recordset.Fields("Abono")
            End If
            AdoAbonos.Recordset.MoveNext
         Loop
      End If
      
   'Procesamos el Saldo de la Factura
    FA.Saldo_MN = FA.Total_MN - FA.Total_Abonos
    If FA.Saldo_MN <= 0 Then TipoCta = Cancelado Else TipoCta = Pendiente
    LabelSaldoAct.Caption = Format$(FA.Saldo_MN, "#,##0.00")
    If FA.T <> Anulado Then
       sSQL = "UPDATE Detalle_Factura " _
            & "SET T = '" & TipoCta & "' " _
            & "WHERE Factura = " & FA.Factura & " " _
            & "AND Autorizacion = '" & FA.Autorizacion & "' " _
            & "AND Serie = '" & FA.Serie & "' " _
            & "AND TC = '" & FA.TC & "' " _
            & "AND Item = '" & NumEmpresa & "' " _
            & "AND Periodo = '" & Periodo_Contable & "' "
       Conectar_Ado_Execute sSQL
         
       sSQL = "UPDATE Facturas " _
            & "SET T = '" & TipoCta & "', Saldo_MN = " & FA.Saldo_MN & " " _
            & "WHERE Factura = " & FA.Factura & " " _
            & "AND Autorizacion = '" & FA.Autorizacion & "' " _
            & "AND Serie = '" & FA.Serie & "' " _
            & "AND TC = '" & FA.TC & "' " _
            & "AND Item = '" & NumEmpresa & "' " _
            & "AND Periodo = '" & Periodo_Contable & "' "
       Conectar_Ado_Execute sSQL
    End If
      
     'Listamos el asiento individual de la factura
      Trans_No = 255
      Cadena = ""
      IniciarAsientosDe DGAsiento, AdoAsiento
      For IE = 0 To UBound(CtasProc) - 1
          If Len(CtasProc(IE).Cta) > 1 Then
             Cadena = Cadena & CtasProc(IE).Cta & " = " & CtasProc(IE).Valor & vbCrLf
             If CtasProc(IE).Valor >= 0 Then
                InsertarAsientos AdoAsiento, CtasProc(IE).Cta, 0, CtasProc(IE).Valor, 0
             Else
                InsertarAsientos AdoAsiento, CtasProc(IE).Cta, 0, 0, -CtasProc(IE).Valor
             End If
          End If
      Next IE
     'MsgBox Cadena
      Debe = 0
      Haber = 0
      sSQL = "SELECT * " _
           & "FROM Asiento " _
           & "WHERE Item = '" & NumEmpresa & "' " _
           & "AND T_No = " & Trans_No & " " _
           & "AND CodigoU = '" & CodigoUsuario & "' " _
           & "ORDER BY DEBE DESC,HABER "
      SelectDataGrid DGAsiento, AdoAsiento, sSQL
      With AdoAsiento.Recordset
       If .RecordCount > 0 Then
           Do While Not .EOF
              Debe = Debe + .Fields("Debe")
              Haber = Haber + .Fields("Haber")
             .MoveNext
           Loop
       End If
      End With
      Frame1.Caption = FA.CxC_Clientes & ": EN BLOQUE"
      LabelDebe.Caption = Format$(Debe, "#,##0.00")
      LabelHaber.Caption = Format$(Haber, "#,##0.00")
      LblDiferencia.Caption = Format$(Debe - Haber, "#,##0.00")
      
      DGAbonos.Visible = True
      DGDetalle.Visible = True
      DGAsiento.Visible = True
      RatonNormal
  Else
      DGAbonos.Visible = True
      DGDetalle.Visible = True
      DGAsiento.Visible = True
      RatonNormal
      MsgBox "Esta Factura no existe."
      DCTipo.SetFocus
  End If
End Sub*/
  }
        
}
?>