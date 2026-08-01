<?php
require_once(dirname(__DIR__,2).'/modelo/inventario/resumen_existenciasM.php');

$controlador = new resumen_existenciasC();


if (isset($_GET['Listatabla'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Listatabla($parametros));
}

if (isset($_GET['Beneficiario_new'])) {
    $query = '';
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
    }
    echo json_encode($controlador->agregar_nuevo_beneficiario($query));
}

if(isset($_GET['cargarOrden'])){
    $parametros = $_POST['param'];
    echo json_encode($controlador->cargarOrden($parametros));
}

if(isset($_GET['DCBodega'])){
    $query = isset($_GET['q']) ? $_GET['q']:"";
    echo json_encode($controlador->DCBodega($query));
}

if(isset($_GET['DCTInv'])){
    $query = isset($_GET['q']) ? $_GET['q']:"";
    echo json_encode($controlador->DCTInv($query));
}
if(isset($_GET['DCTipoBusqueda'])){
    $query = isset($_GET['q']) ? $_GET['q']:"";
    $opcion = isset($_GET['cbx']) ? $_GET['cbx']:"";
    $dcInv =  isset($_GET['DCInvSelec']) ? $_GET['DCInvSelec']:"";
    echo json_encode($controlador->DCTipoBusqueda($opcion,$dcInv,$query));
}

if(isset($_GET['DCCtaInv'])){
    $query = isset($_GET['q']) ? $_GET['q']:"";
    $opcion = isset($_GET['cbx']) ? $_GET['cbx']:"";
    echo json_encode($controlador->DCCtaInv($opcion,$query));
}

if(isset($_GET['DCSubModulo'])){
    $query = isset($_GET['q']) ? $_GET['q']:"";
    $opcion = isset($_GET['cbx']) ? $_GET['cbx']:"";
    echo json_encode($controlador->DCSubModulo($opcion,$query));
}

if(isset($_GET['Resumen_QR'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Resumen_QR($parametros));
}

if(isset($_GET['Resumen_Barras'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Resumen_Barras($parametros));
}

if(isset($_GET['Resumen_Lote'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Resumen_Lote($parametros));
}
if(isset($_GET['Stock'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Stock($parametros));
}




class resumen_existenciasC
{
    private $modelo;
    private $sri;
    private $egresos;

    function __construct()
    {
        $this->modelo = new resumen_existenciasM();
    }

    function Listatabla($parametros)
    {
        $data = $this->modelo->Listatabla();
        return $data;
        // print_r($data);die();
    }
    function DCBodega($query)
    {
        $lista = array();
        $data =  $this->modelo->DCBodega($query);
        foreach ($data as $key => $value) {
            $lista[] = array('id'=>$value['CodBod'],'text'=>$value['Bodegas']);
        }

        return $lista;
    }

    function DCTInv($query)
    {
        $lista = array();
        $data =  $this->modelo->DCTInv($query);
        foreach ($data as $key => $value) {
            $lista[] = array('id'=>$value['Codigo_Inv'],'text'=>$value['Producto']);
        }

        return $lista;
    }
    function DCTipoBusqueda($opcion,$dcInv,$query)
    {
        $lista = array();
        switch ($opcion) {
            case '1':
            $data =  $this->modelo->Catalogo_Marcas();
                break;
            case '2':            
            $data =  $this->modelo->Trans_Kardex_barras();
                break;
        
            case '3':
            $data =  $this->modelo->Trans_Kardex_lote();
                break;
        
            case '4':
            if($dcInv=="")
            {
               $data = $this->modelo->DCTInv($query);
               $dcInv = $data[0]['Codigo_Inv'];
               // print_r($data);die();
            }
            $data = $this->modelo->Catalogo_Productos($dcInv);
                break;
        }

        foreach ($data as $key => $value) {
            $lista[] = array('id'=>$value['Codigo'],'text'=>$value['Producto']);
        }

        return $lista;
    }

    function DCCtaInv($opcion,$query)
    {
        if($opcion==1) 
        {
            $data = $this->modelo->DCCtaInvOp1();
           
        }else{
            $data = $this->modelo->DCCtaInvOp2();
        }

        $lista = array();
        foreach ($data as $key => $value) {
            $lista[] = array('id'=>$value['Cta_Inv'],'text'=>$value['Cuenta']);
        }
        return $lista;


        // print_r($data);die();

    }
    function DCSubModulo($opcion,$query)
    {
        if($opcion==1)
        {
            $data = $this->modelo->DCSubModuloOp1();
  
        }else{
            $data = $this->modelo->DCSubModuloOp2();
        }

        $lista = array();
        foreach ($data as $key => $value) {
            $lista[] = array('id'=>$value['Codigo'],'text'=>$value['SubModulo']);
        }
        return $lista;
        // print_r($data);die();
    }

    function Stock($parametros)
    {
        $Debitos = 0;
        $Creditos = 0;
        $Total = 0;

        $QTipoInv = false;
        $Cod_Bodega = G_NINGUNO;
        Control_Procesos("I", "Proceso Stock de Inventario, del ".$parametros['inicial'].' al '.$parametros['final']);
        Reporte_Resumen_Existencias_SP($parametros['inicial'],$parametros['final'],$Cod_Bodega);

        // print_r($parametros);die();

      if($parametros['CheqProducto']=="true")
      {
         $Opcion = 2;
    //      RatonReloj
    //      MiTiempo = Time
    //      DGQuery.Visible = False
    //      Progreso_Barra.Mensaje_Box = "Procesando Resumen de Existencia"
    //      Progreso_Iniciar
    //      If CheqBod.value = 0 Then Cod_Bodega = Ninguno Else Cod_Bodega = SinEspaciosIzq(DCBodega)

    //     // 'SQLDec = "Promedio " & CStr(Dec_Costo) & "|Valor_Total 2|."
                                                   
    //      sSQL = "SELECT TC,Codigo_Inv,Stock_Anterior,Entradas,Salidas,Stock_Actual,Promedio,Valor_Total,Bodega " _
    //           & "FROM Catalogo_Productos " _
    //           & "WHERE Item = '" & NumEmpresa & "' " _
    //           & "AND Periodo = '" & Periodo_Contable & "' "
    //      If CheqMonto.value = 1 Then
    //         sSQL = sSQL & "AND Stock_Actual = " & Val(TxtMonto.Text) & " "
    //      Else
    //         sSQL = sSQL & "AND Stock_Actual <> 0 "
    //      End If
    //      sSQL = sSQL & "AND TC = 'P' " _
    //           & "ORDER BY Codigo_Inv "
    // // ''     If (OpcProducto.value = 1) And (Codigo3 <> "Todos") Then sSQL = sSQL & "AND Recibo = '" & Codigo3 & "' "
    // // ''     If CheqExist.value = 1 Then sSQL = sSQL & "AND Saldo_Actual <> 0 "
    // // ''     sSQL = sSQL & "ORDER BY Numero "
      }else{
         $Opcion = 1;
         $SQL_Tipo_Busqueda_CP = $this->SQL_Tipo_Busqueda_CP($parametros);
         $data = $this->modelo->Stock_Catalogo_Productos($parametros['CheqGrupo'],$SQL_Tipo_Busqueda_CP);
    }

    foreach ($data as $key => $value) {
        $Debitos +=  number_format($value["Entradas"] * $value["Costo_Unit"], 2,'.','');
        $Creditos +=  number_format($value["Salidas"] * $value["Costo_Unit"], 2,'.','');
        $Total +=  number_format($value["Valor_Total"], 2,'.','');
    }
        return array('data'=>$data,'Debitos'=>$Debitos,'Creditos'=>$Creditos,'Total'=>$Total);


//   SQLDec = "Costo_Unit " & CStr(Dec_Costo) & "|Total 2|."
//   Select_Adodc_Grid DGQuery, AdoDetKardex, sSQL, SQLDec
//  'MsgBox Opcion & vbCrLf & SQLDec & vbCrLf & Cod_Bodega
//   Total = 0
//   Debitos = 0
//   Creditos = 0
//   DGQuery.Visible = False
//   With AdoDetKardex.Recordset
//    If .RecordCount > 0 Then
//        Do While Not .EOF
//           If OpcProducto.value <> 1 Then
//              If .fields("TC") <> "I" Then
//                  Debitos = Debitos + Redondear(.fields("Entradas") * .fields("Costo_Unit"), 2)
//                  Creditos = Creditos + Redondear(.fields("Salidas") * .fields("Costo_Unit"), 2)
//                  Total = Total + Redondear(.fields("Valor_Total"), 2)
//              End If
//           End If
//          .MoveNext
//        Loop
//    End If
//   End With
//   DGQuery.Visible = True
//  'Total = Debitos - Creditos
//   LabelTot.Caption = Format(Total, "#,##0.00")
//   DGQuery.Visible = True
//   RatonNormal
// End Sub
    }


    function Resumen_QR($parametros)
    {
        $Debitos = 0;
        $Creditos = 0;
        $Stock_Inv = 0;

        $data = $this->modelo->Resumen_QR($parametros['inicial'],$parametros['final']);
        foreach ($data as $key => $value) {
            $Debitos+= $value['Entradas'];
            $Creditos+= $value['Salidas'];
            $Stock_Inv+= $value['Stock_QR'];
        }
        return array('data'=>$data,'Debitos'=>$Debitos,'Creditos'=>$Creditos,'Stock_Inv'=>$Stock_Inv);
    }

    function Resumen_Barras($parametros)
    {
        $Debitos = 0;
        $Creditos = 0;
        $Stock_Inv = 0;
        $SQL_Tipo_Busqueda = $this->SQL_Tipo_Busqueda($parametros);

        $data = $this->modelo->Resumen_Barras($parametros['inicial'],$parametros['final'],$SQL_Tipo_Busqueda);
        foreach ($data as $key => $value) {
            $Debitos+= $value['Entradas'];
            $Creditos+= $value['Salidas'];
            $Stock_Inv+= $value['Stock_Lote'];
        }
        return array('data'=>$data,'Debitos'=>$Debitos,'Creditos'=>$Creditos,'Stock_Inv'=>$Stock_Inv);

    }

    function Resumen_Lote($parametros)
    {
        $Debitos = 0;
        $Creditos = 0;
        $Stock_Inv = 0;
        $SQL_Tipo_Busqueda = $this->SQL_Tipo_Busqueda($parametros);

        $data = $this->modelo->Resumen_Lote($parametros['inicial'],$parametros['final'],$SQL_Tipo_Busqueda);
        foreach ($data as $key => $value) {
            $Debitos+= $value['Entradas'];
            $Creditos+= $value['Salidas'];
            $Stock_Inv+= $value['Stock_Lote'];
        }
        return array('data'=>$data,'Debitos'=>$Debitos,'Creditos'=>$Creditos,'Stock_Inv'=>$Stock_Inv);

    }

    function SQL_Tipo_Busqueda($parametros)
    {
        // print_r($parametros);die();
        $BSQL = " ";
        $CodigoInv = G_NINGUNO;
        // $data = $this->DCTipoBusqueda($parametros['cbxpro'],$parametros['DCInv'],"");

        if($parametros['cbxpro']=='4')
        {
            // if(count($data)>0)
            // {
                $CodigoInv = $parametros['DCTipoBusqueda'];
            // }
        }else{
             $CodigoInv = $parametros['DCTipoBusqueda'];
        }        
          
         if( $parametros['CheqBod'] <> 'false'){$BSQL = $BSQL." AND TK.CodBodega = '".$Cod_Bodega."' "; }

        if($parametros['CheqProducto'] <> 'false')
        {
             if($parametros['cbxpro']==2){
                $BSQL.= " AND TK.Codigo_Barra = '".$CodigoInv."' ";
             }else if($parametros['cbxpro']==3){
                $BSQL.= " AND TK.Lote_No = '".$CodigoInv."' ";
             }else{
                $BSQL.=" AND TK.Codigo_Inv = '".$CodigoInv."' ";
            }
        }
          
          if($parametros['CheqMonto'] <> 'false'){ $BSQL.=" AND CP.Stock_Actual = ".$parametros['TxtMonto']." "; }
          if($parametros['CheqExist']=='false'){ $BSQL.= " AND CP.Valor_Total <> 0 ";}
        //  // 'MsgBox BSQL
        return $BSQL;
    }

    function SQL_Tipo_Busqueda_CP($parametros)
    {
        // print_r($parametros);die();
        $BSQL = " ";
        $CodigoInv = G_NINGUNO;
        // $data = $this->DCTipoBusqueda($parametros['cbxpro'],$parametros['DCInv'],"");

        if($parametros['cbxpro']=='4')
        {
            // if(count($data)>0)
            // {
                $CodigoInv = $parametros['DCTipoBusqueda'];
            // }
        }else{
             $CodigoInv = $parametros['DCTipoBusqueda'];
        }        
                 
        if($parametros['CheqMonto'] <> 'false'){ $BSQL.=" AND CP.Stock_Actual = ".$parametros['TxtMonto']." "; }
        if($parametros['CheqExist']=='false'){ $BSQL.= " AND CP.Valor_Total <> 0 ";}
        //  // 'MsgBox BSQL
        return $BSQL;
    }
}
?>