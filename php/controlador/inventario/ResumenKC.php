<?php 
include(dirname(__DIR__,2).'/modelo/inventario/ResumenKM.php');
require_once(dirname(__DIR__,3)."/lib/excel/plantilla.php");
if(!class_exists('cabecera_pdf'))
{
    require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
}

$controlador = new ResumenKC();
if(isset($_GET["generarExcelResumenK"])){
    echo json_decode($controlador->generarExcelResumen($_GET));
    exit();
}else

if(isset($_GET['Imprimir_ResumenK']))
{
    $controlador->ImprimirPdf($_GET);
    exit();
}
elseif(isset($_GET['Form_Activate']))
{
    echo json_encode($controlador->Form_Activate($_POST));
}
elseif(isset($_GET['ConsultarStock']))
{
    echo json_encode($controlador->Stock( $_POST,$_GET['StockSuperior']));
}
elseif(isset($_GET['Resumen_Lote']))
{
    echo json_encode($controlador->Resumen_Lote( $_POST));
}
elseif(isset($_GET['Resumen_Barras']))
{
    echo json_encode($controlador->Resumen_Barras( $_POST));
}
elseif(isset($_GET['Listar_Por_Producto']))
{ 
    extract($_POST);
    $OpcMarca = (isset($ProductoPor) && $ProductoPor=="OpcMarca");
    $OpcBarra = (isset($ProductoPor) && $ProductoPor=="OpcBarra");
    $OpcLote = (isset($ProductoPor) && $ProductoPor=="OpcLote");
    $DCTInv = (isset($DCTInv) && $DCTInv!="")?$DCTInv:G_NINGUNO;
    echo json_encode(array("DCTipoBusqueda"=>$controlador->Listar_Por_Producto($OpcMarca, $OpcBarra, $OpcLote, $DCTInv)));
}
elseif(isset($_GET['Listar_Por_Tipo_SubModulo']))
{ 
    extract($_POST);
    $OpcGasto = (isset($SuModeloDe) && $SuModeloDe=="OpcGasto");
    echo json_encode(array("DCSubModulo"=>$controlador->Listar_Por_Tipo_SubModulo($OpcGasto)));
}
elseif(isset($_GET['Listar_Por_Tipo_Cta']))
{ 
    extract($_POST);
    $OpcInv = (isset($TipoCuentaDe) && $TipoCuentaDe=="OpcInv");
    echo json_encode(array("DCCtaInv"=>$controlador->Listar_Por_Tipo_Cta($OpcInv)));
}

class ResumenKC
{
    private $modelo;
    private $pdf;
    public $NumEmpresa;
    public $Periodo_Contable;
    public $CodigoUsuario;
    function __construct()
    {
        $this->modelo = new ResumenKM();
        $this->pdf = new cabecera_pdf();
        $this->NumEmpresa = $_SESSION['INGRESO']['item'];
        $this->Periodo_Contable = $_SESSION['INGRESO']['periodo'];
        $this->CodigoUsuario = $_SESSION['INGRESO']['CodigoU'];
    }

    public function generarExcelResumen($parametros){
        if(isset($_SESSION['ResumenKC']['AdoDetKardex'])){
            extract($parametros);
            $titulo = 'Existencia '.BuscarFecha($MBoxFechaI).' al '. BuscarFecha($MBoxFechaF);
            $sSQL   = $_SESSION['ResumenKC']['AdoDetKardex'];
            $medidas = array(12,30,15,12,18,9,12,12,12,12,12,15,15,15,10,20,20,15,15,20,25,15,15,20,25,25,15,15,9);
            return exportar_excel_generico_SQl($titulo,$sSQL, $medidas, fecha_sin_hora:true);
        }else{
            die("Primero debe cargar la informacion");
        }
    }

    public function ImprimirPdf($parametros){//pédiente
        if(!isset($_SESSION['ResumenKC']['AdoDetKardex']) || $_SESSION['ResumenKC']['AdoDetKardex']==""){
            die("Primero debe consultar la informacion");
        }
        extract($parametros);

        $SQLMsg1 = " ";
        if (isset($CheqBod) && $CheqBod == 1) {
            $SQLMsg1 = "POR BODEGA " . strtoupper($DCBodega);
        }
        if (isset($CheqSubMod) && $CheqSubMod == 1) {
            if ($SQLMsg1 != "") {
                $SQLMsg1 = $SQLMsg1 . " Y DE " . strtoupper($DCSubModulo);
            } else {
                $SQLMsg1 = " DE " . strtoupper($DCSubModulo);
            }
        }

        $titulo = "R E S U M E N    D E    E X I S T E N C I A S ".$SQLMsg1 ;
        $query = $_SESSION['ResumenKC']['AdoDetKardex'];

        $pattern = "/SELECT(.*?)FROM/s";
        $matches = [];
        preg_match($pattern, $query, $matches);
        $sectionToReplace = $matches[1];
        $ali =array("C","L","L","R","R","R","R","R","R","R","R","R","R","R","R");
        $medi =array(8,18,65,15,23,20,20,20,19,18,18,15,18);
        if($_SESSION['ResumenKC']['Opcion']==11){
            $newSection = " TK.Codigo_Inv, CP.Producto, TK.CodBodega as Bod, TK.Lote_No, TK.Fecha_Fab, TK.Fecha_Exp, TK.Procedencia, TK.Serie_No, SUM(TK.Entrada) As Entradas, SUM(TK.Salida) As Salidas, " .
              "SUM(TK.Entrada-TK.Salida) As Stock, AVG(Valor_Unitario) As Valor_Unit, " .
              "(SUM(TK.Entrada-TK.Salida) * AVG(Valor_Unitario)) As Total_Inv " ;
            $replacedQuery = str_replace($sectionToReplace, $newSection, $query);
            $medi =array(18,80,10,15,18,18,23,15,17,17,15,18,15);
        }else if($_SESSION['ResumenKC']['Opcion']==22){
            $newSection = " TK.Codigo_Inv, CP.Producto, TK.CodBodega AS Bod, TK.Codigo_Barra, SUM(TK.Entrada) As Entradas, SUM(TK.Salida) As Salidas, SUM(TK.Entrada-TK.Salida) As Stock_Lote, AVG(TK.Valor_Unitario) As Valor_Unit, ((SUM(TK.Entrada)-SUM(TK.Salida)) * AVG(TK.Valor_Unitario)) As Total_Inventario ";
            $replacedQuery = str_replace($sectionToReplace, $newSection, $query);
            $medi =array(22,115,10,25,18,18,23,21,25);
        }else{
            $replacedQuery = $query;
        }

        $result = $this->modelo->SelectDB($replacedQuery);
        $campos = array();
        foreach ($result[0] as $key => $value) {
          array_push($campos,$key);
        }
       
        $pdf = new cabecera_pdf();  
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
            if($valu instanceof DateTime){
                array_push($datos,$valu->format('Y-m-d'));
            }else if ( (is_int($valu) || is_numeric($valu))) {
              array_push($datos, number_format($valu, 2, '.', ''));
            } else { 
              array_push($datos,$valu);
            }
          }
          $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
          $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
          $tablaHTML[$pos]['datos']=$datos;
          $tablaHTML[$pos]['estilo']='I';
          $tablaHTML[$pos]['borde'] = '1';
          $pos = $pos+1;
        }
        $pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$MBoxFechaI,$MBoxFechaF,$sizetable,$mostrar, orientacion: 'L', mostrar_cero:true);
    }

    public function Stock($parametros, $StockSuperior)
    {
        extract($parametros);
        $error = false;
        $OpcMarca = (isset($ProductoPor) && $ProductoPor=="OpcMarca");
        $OpcBarra = (isset($ProductoPor) && $ProductoPor=="OpcBarra");
        $OpcLote = (isset($ProductoPor) && $ProductoPor=="OpcLote");
        $OpcProducto = (isset($ProductoPor) && $ProductoPor=="OpcProducto");
        $CheqProducto = (isset($CheqProducto) && $CheqProducto);
        $CheqBod = (isset($CheqBod) && $CheqBod);
        $CheqMonto = (isset($CheqMonto) && $CheqMonto);
        $CheqExist = (isset($CheqExist) && $CheqExist);
        $DCTInv = (isset($DCTInv) && $DCTInv!="")?$DCTInv:G_NINGUNO;
        $Cod_Bodega = (isset($Cod_Bodega) && $Cod_Bodega!="")?$Cod_Bodega:G_NINGUNO;

        $FechaValida = FechaValida($MBoxFechaI);
        if($FechaValida["ErrorFecha"]){
            return ['error' => true, "mensaje" =>$FechaValida["MsgBox"]];
        }
        $FechaValida = FechaValida($MBoxFechaF);
        if($FechaValida["ErrorFecha"]){
            return ['error' => true, "mensaje" =>$FechaValida["MsgBox"]];
        }
        $FechaIni = BuscarFecha($MBoxFechaI);
        $FechaFin = BuscarFecha($MBoxFechaF);

        control_procesos("I", "Proceso Stock de Inventario, del $MBoxFechaI al $MBoxFechaF");
        
        if (isset($CheqProducto) && $CheqProducto ==1) {
            $_SESSION['ResumenKC']['Opcion'] = 2;
            $tabla = "Saldo_Diarios";
            $sSQL = "SELECT Recibo As Serie_No, Comprobante As Detalle, " .
                    "Total As Promedio, Saldo_Anterior As Saldo_Ant, Ingresos As Entradas, " .
                    "Egresos As Salidas, Saldo_Actual As Stock_Act " .
                    "FROM Saldo_Diarios " .
                    "WHERE Item = '".$this->NumEmpresa."' " .
                    "AND CodigoU = '".$this->CodigoUsuario."' " .
                    "AND TP = 'INVE' ";
                    
            if (isset($CheqMonto) && $CheqMonto == 1) {
                $sSQL .= " AND Saldo_Actual = " . (float)$TxtMonto . " ";
            } else {
                $sSQL .= " AND Saldo_Actual <> 0 ";
            }
            $Codigo3 = "Todos";////TODO LS de donde sale sta variale
            if ($OpcProducto == 1 && $Codigo3 != "Todos") {
                $sSQL .= " AND Recibo = '$Codigo3' ";
            }
            
            if (isset($CheqExist) && $CheqExist == 1) {
                $sSQL .= " AND Saldo_Actual <> 0 ";
            }
            
            $sSQL .= " ORDER BY Numero ";
        } else {
            $_SESSION['ResumenKC']['Opcion'] = 1;
            Reporte_Resumen_Existencias_SP($MBoxFechaI, $MBoxFechaF, $Cod_Bodega);
            
            //INICIO SQL_Tipo_Busqueda
              $BSQL = " ";
              $CodigoInv = G_NINGUNO;
              if ($CheqProducto) {
                $CodigoInv = $DCTipoBusqueda;
              }
              
              if ($CheqBod) {
                  $BSQL .= " AND TK.CodBodega = '$Cod_Bodega' ";
              }
              
              if ($CheqProducto) {
                  if ($ProductoPor=="OpcBarra") {
                      $BSQL .= " AND TK.Codigo_Barra = '$CodigoInv' ";
                  } elseif ($ProductoPor=="OpcLote") {
                      $BSQL .= " AND TK.Lote_No = '$CodigoInv' ";
                  } else {
                      $BSQL .= " AND TK.Codigo_Inv = '$CodigoInv' ";
                  }
              }
              
              if ($CheqMonto) {
                $BSQL .= " AND CP.Stock_Actual = " . (float)$TxtMonto;
              }
              
              if ($CheqExist == 0) {
                $BSQL .= " AND CP.Valor_Total <> 0 ";
              }
            //FIN SQL_Tipo_Busqueda

            $tabla = "Catalogo_Productos";
            $sSQL = "SELECT TC, Codigo_Inv, Producto, Unidad, Stock_Anterior, Entradas, Salidas, Stock_Actual, " .
                    "Promedio As Costo_Unit, Valor_Total As Total, 0 As Diferencias, '' As Bodega, Ubicacion " .
                    "FROM Catalogo_Productos As CP " .
                    "WHERE Item = '".$this->NumEmpresa."' " .
                    "AND Periodo = '".$this->Periodo_Contable."' "
                    . $BSQL;
            
            if (isset($CheqGrupo) && $CheqGrupo <> 0) {
                $sSQL .= " AND Codigo_Inv LIKE '".$DCTInv."%' ";
            }
            
            $sSQL .= "ORDER BY Codigo_Inv ";
        }
        $_SESSION['ResumenKC']['AdoDetKardex'] = $sSQL;
        $AdoDetKardex =  $this->modelo->SelectDB($sSQL);
        
        $Total = 0;
        $Debitos = 0;
        $Creditos = 0;

        if(count($AdoDetKardex)>0){
            foreach ($AdoDetKardex as $key => $Fields) {
                if (!isset($OpcProducto) || $OpcProducto != 1) {
                    if ($Fields["TC"] != "I") {
                        $Debitos += number_format($Fields["Entradas"] * $Fields["Costo_Unit"], 2,'.','');
                        $Creditos += number_format($Fields["Salidas"] * $Fields["Costo_Unit"], 2,'.','');
                        $Total += number_format($Fields["Total"], 2,'.','');
                    }
                }
            }
        }
        
        $LabelTot = number_format($Total, 2,'.','');
        $heightDispo = ($heightDisponible>150)?$heightDisponible-45:$heightDisponible;
        $DGQuery = grilla_generica_new($sSQL,$tabla,'myTableDGQuery','',false,false,false,1,1,1,$heightDispo);
        return compact('error','DGQuery','LabelTot');
    }

    public function Listar_Por_Producto($OpcMarca, $OpcBarra, $OpcLote, $DCTInv)
    {
        $sSQL = $this->Listar_Por_ProductoSQL($OpcMarca, $OpcBarra, $OpcLote, $DCTInv);
        return $this->modelo->SelectDB($sSQL);
    }

    function Listar_Por_ProductoSQL($OpcMarca, $OpcBarra, $OpcLote, $DCTInv) {
        if ($OpcMarca) {
            $sSQL = "SELECT CodMar As codigo, Marca As nombre " .
                    "FROM Catalogo_Marcas " .
                    "WHERE Item = '".$this->NumEmpresa."' " .
                    "AND Periodo = '".$this->Periodo_Contable."' " .
                    "AND CodMar <> '.' " .
                    "ORDER BY Marca";
        } elseif ($OpcBarra) {
            $sSQL = "SELECT Codigo_Barra As codigo, Codigo_Barra As nombre " .
                    "FROM Trans_Kardex " .
                    "WHERE Item = '".$this->NumEmpresa."' " .
                    "AND Periodo = '".$this->Periodo_Contable."' " .
                    "GROUP BY Codigo_Barra " .
                    "ORDER BY Codigo_Barra";
        } elseif ($OpcLote) {
            $sSQL = "SELECT Lote_No As codigo, Lote_No As nombre " .
                    "FROM Trans_Kardex " .
                    "WHERE Item = '".$this->NumEmpresa."' " .
                    "AND Periodo = '".$this->Periodo_Contable."' " .
                    "GROUP BY Lote_No " .
                    "ORDER BY Lote_No";
        } else {
            $sSQL = "SELECT Codigo_Inv As codigo, Producto As nombre " .
                    "FROM Catalogo_Productos " .
                    "WHERE Item = '".$this->NumEmpresa."' " .
                    "AND Periodo = '".$this->Periodo_Contable."' " .
                    "AND LEN(Cta_Inventario) > 2 " .
                    "AND Codigo_Inv LIKE '$DCTInv%' " .
                    "AND TC = 'P' " .
                    "ORDER BY Codigo_Inv";
        }
        
        return $sSQL;
    }

    function Listar_Por_Tipo_SubModulo($OpcGasto) {
        if ($OpcGasto) {
            $sSQL = "SELECT TC, Codigo as codigo, Detalle AS nombre " .
                    "FROM Catalogo_SubCtas " .
                    "WHERE Item = '" . $this->NumEmpresa . "' " .
                    "AND Periodo = '" . $this->Periodo_Contable . "' " .
                    "AND Detalle <> '" . G_NINGUNO . "' " .
                    "ORDER BY TC, Detalle";
        } else {
            $sSQL = "SELECT CP.TC, CP.Codigo as codigo, CP.Cta, (C.Cliente + REPLICATE(' ', 60 - LEN(C.Cliente)) + CP.Cta) AS nombre " .
                    "FROM Catalogo_CxCxP AS CP, Clientes AS C " .
                    "WHERE CP.Item = '" . $this->NumEmpresa . "' " .
                    "AND CP.Periodo = '" . $this->Periodo_Contable . "' " .
                    "AND C.Cliente <> '" . G_NINGUNO . "' " .
                    "AND CP.TC = 'P' " .
                    "AND CP.Codigo = C.Codigo " .
                    "ORDER BY C.Cliente, CP.Cta";
        }
        return $this->modelo->SelectDB($sSQL);
    }

    function Listar_Por_Tipo_Cta($OpcInv) {
        if ($OpcInv) {
            $sSQL = "SELECT CC.Cuenta as codigo, TK.Cta_Inv as nombre " .
                    "FROM Catalogo_Cuentas AS CC, Trans_Kardex AS TK " .
                    "WHERE CC.Item = '" . $this->NumEmpresa . "' " .
                    "AND CC.Periodo = '" . $this->Periodo_Contable . "' " .
                    "AND LEN(TK.Cta_Inv) > 1 " .
                    "AND CC.Codigo = TK.Cta_Inv " .
                    "AND CC.Item = TK.Item " .
                    "AND CC.Periodo = TK.Periodo " .
                    "GROUP BY CC.Cuenta, TK.Cta_Inv " .
                    "ORDER BY CC.Cuenta, TK.Cta_Inv";
        } else {
            $sSQL = "SELECT CC.Cuenta as codigo, TK.Contra_Cta as nombre " .
                    "FROM Catalogo_Cuentas AS CC, Trans_Kardex AS TK " .
                    "WHERE CC.Item = '" . $this->NumEmpresa . "' " .
                    "AND CC.Periodo = '" . $this->Periodo_Contable . "' " .
                    "AND LEN(TK.Contra_Cta) > 1 " .
                    "AND CC.Codigo = TK.Contra_Cta " .
                    "AND CC.Item = TK.Item " .
                    "AND CC.Periodo = TK.Periodo " .
                    "GROUP BY CC.Cuenta, TK.Contra_Cta " .
                    "ORDER BY CC.Cuenta, TK.Contra_Cta";
        }
        return $this->modelo->SelectDB($sSQL);
    }


    public function ListarProductosResumenK(){
        $sSQL = "SELECT Codigo_Inv, Producto " .
            "FROM Catalogo_Productos " .
            "WHERE Item = '" . $this->NumEmpresa . "' " .
            "AND Periodo = '" . $this->Periodo_Contable . "' " .
            "AND TC = 'I' " .
            "AND INV <> 0 " .
            "ORDER BY Codigo_Inv";
        return $this->modelo->SelectDB($sSQL);
    }

    public function Form_Activate($parametros)
    {
        extract($parametros);
        mayorizar_inventario_sp(false, modulo_reemplazar:false);

        $sSQL = "SELECT Codigo_Inv, Producto " .
            "FROM Catalogo_Productos " .
            "WHERE Item = '" . $this->NumEmpresa . "' " .
            "AND Periodo = '" . $this->Periodo_Contable . "' " .
            "AND TC = 'I' " .
            "AND INV <> 0 " .
            "ORDER BY Codigo_Inv";
        return $this->modelo->SelectDB($sSQL);
        $heightDispo = ($heightDisponible>150)?$heightDisponible-45:$heightDisponible;
        $DGQuery = grilla_generica_new($sSQL,$tabla,'myTableDGQuery','',false,false,false,1,1,1,$heightDispo);
        return compact('DGQuery');
    }

    public function Resumen_Lote($parametros) {
        extract($parametros);
        $Debitos = 0;
        $Creditos = 0;
        $Stock_Inv = 0;

        $OpcMarca = (isset($ProductoPor) && $ProductoPor=="OpcMarca");
        $OpcBarra = (isset($ProductoPor) && $ProductoPor=="OpcBarra");
        $OpcLote = (isset($ProductoPor) && $ProductoPor=="OpcLote");
        $OpcProducto = (isset($ProductoPor) && $ProductoPor=="OpcProducto");
        $CheqProducto = (isset($CheqProducto) && $CheqProducto);
        $CheqBod = (isset($CheqBod) && $CheqBod);
        $CheqMonto = (isset($CheqMonto) && $CheqMonto);
        $CheqExist = (isset($CheqExist) && $CheqExist);
        $DCTInv = (isset($DCTInv) && $DCTInv!="")?$DCTInv:G_NINGUNO;
        $Cod_Bodega = (isset($Cod_Bodega) && $Cod_Bodega!="")?$Cod_Bodega:G_NINGUNO;

        $FechaIni = BuscarFecha($MBoxFechaI);
        $FechaFin = BuscarFecha($MBoxFechaF);

        //INICIO SQL_Tipo_Busqueda
          $BSQL = " ";
          $CodigoInv = G_NINGUNO;
          if ($CheqProducto) {
            $CodigoInv = $DCTipoBusqueda;
          }
          
          if ($CheqBod) {
              $BSQL .= " AND TK.CodBodega = '$Cod_Bodega' ";
          }
          
          if ($CheqProducto) {
              if ($ProductoPor=="OpcBarra") {
                  $BSQL .= " AND TK.Codigo_Barra = '$CodigoInv' ";
              } elseif ($ProductoPor=="OpcLote") {
                  $BSQL .= " AND TK.Lote_No = '$CodigoInv' ";
              } else {
                  $BSQL .= " AND TK.Codigo_Inv = '$CodigoInv' ";
              }
          }
          
          if ($CheqMonto) {
            $BSQL .= " AND CP.Stock_Actual = " . (float)$TxtMonto;
          }
          
          if ($CheqExist == 0) {
            $BSQL .= " AND CP.Valor_Total <> 0 ";
          }
        //FIN SQL_Tipo_Busqueda

        $sSQL = "SELECT TK.Codigo_Inv, CP.Producto, TK.CodBodega, TK.Lote_No, TK.Fecha_Fab, TK.Fecha_Exp, CP.Reg_Sanitario, " .
              "TK.Modelo, TK.Procedencia, TK.Serie_No, SUM(TK.Entrada) As Entradas, SUM(TK.Salida) As Salidas, " .
              "SUM(TK.Entrada-TK.Salida) As Stock_Lote, AVG(Valor_Unitario) As Valor_Unit, " .
              "(SUM(TK.Entrada-TK.Salida) * AVG(Valor_Unitario)) As Total_Inventario " .
              "FROM Catalogo_Productos As CP, Trans_Kardex As TK " .
              "WHERE CP.Item = '" . $this->NumEmpresa . "' " .
              "AND CP.Periodo = '" . $this->Periodo_Contable . "' " .
              "AND TK.Fecha BETWEEN '" . $FechaIni . "' and '" . $FechaFin . "' " .
              $BSQL .
              "AND CP.Item = TK.Item " .
              "AND CP.Periodo = TK.Periodo " .
              "AND CP.Codigo_Inv = TK.Codigo_Inv " .
              "GROUP BY TK.Codigo_Inv, CP.Producto, TK.CodBodega, TK.Lote_No, TK.Fecha_Fab, TK.Fecha_Exp, CP.Reg_Sanitario, " .
              "TK.Modelo, TK.Procedencia, TK.Serie_No " .
              "ORDER BY TK.Codigo_Inv, TK.Lote_No";

        $AdoDetKardex = $this->modelo->SelectDB($sSQL);
        $_SESSION['ResumenKC']['Opcion']=11;
        $_SESSION['ResumenKC']['AdoDetKardex'] = $sSQL;

        if(count($AdoDetKardex)>0){
            foreach ($AdoDetKardex as $key => $Fields) {
                $Debitos += number_format($Fields["Entradas"], 2,'.','');
                $Creditos += number_format($Fields["Salidas"], 2,'.','');
                $Stock_Inv += number_format($Fields["Stock_Lote"], 2,'.','');
            }
        }
        
        $LabelStock = number_format($Stock_Inv, 2,'.','');
        $heightDispo = ($heightDisponible>150)?$heightDisponible-45:$heightDisponible;
        $DGQuery = grilla_generica_new($sSQL,"Catalogo_Productos",'myTableDGQuery','',false,false,false,1,1,1,$heightDispo);
        return compact('DGQuery','LabelStock');
    }

    public function Resumen_Barras($parametros) {
        extract($parametros);

        $Debitos = 0;
        $Creditos = 0;
        $Stock_Inv = 0;

        $OpcMarca = (isset($ProductoPor) && $ProductoPor=="OpcMarca");
        $OpcBarra = (isset($ProductoPor) && $ProductoPor=="OpcBarra");
        $OpcLote = (isset($ProductoPor) && $ProductoPor=="OpcLote");
        $OpcProducto = (isset($ProductoPor) && $ProductoPor=="OpcProducto");
        $CheqProducto = (isset($CheqProducto) && $CheqProducto);
        $CheqBod = (isset($CheqBod) && $CheqBod);
        $CheqMonto = (isset($CheqMonto) && $CheqMonto);
        $CheqExist = (isset($CheqExist) && $CheqExist);
        $DCTInv = (isset($DCTInv) && $DCTInv!="")?$DCTInv:G_NINGUNO;
        $Cod_Bodega = (isset($Cod_Bodega) && $Cod_Bodega!="")?$Cod_Bodega:G_NINGUNO;

        $FechaIni = BuscarFecha($MBoxFechaI);
        $FechaFin = BuscarFecha($MBoxFechaF);

        //INICIO SQL_Tipo_Busqueda
        $BSQL = " ";
        $CodigoInv = G_NINGUNO;
        if ($CheqProducto) {
            $CodigoInv = $DCTipoBusqueda;
        }

        if ($CheqBod) {
            $BSQL .= " AND TK.CodBodega = '$Cod_Bodega' ";
        }

        if ($CheqProducto) {
            if ($ProductoPor=="OpcBarra") {
                $BSQL .= " AND TK.Codigo_Barra = '$CodigoInv' ";
            } elseif ($ProductoPor=="OpcLote") {
                $BSQL .= " AND TK.Lote_No = '$CodigoInv' ";
            } else {
                $BSQL .= " AND TK.Codigo_Inv = '$CodigoInv' ";
            }
        }

        if ($CheqMonto) {
            $BSQL .= " AND CP.Stock_Actual = " . (float)$TxtMonto;
        }

        if ($CheqExist == 0) {
            $BSQL .= " AND CP.Valor_Total <> 0 ";
        }
        //FIN SQL_Tipo_Busqueda

        $sSQL = "SELECT TK.Codigo_Inv, CP.Producto, TK.CodBodega, TK.Codigo_Barra, CP.Reg_Sanitario, ".
        "SUM(TK.Entrada) As Entradas, SUM(TK.Salida) As Salidas, ".
        "SUM(TK.Entrada-TK.Salida) As Stock_Lote, AVG(TK.Valor_Unitario) As Valor_Unit, ".
        "((SUM(TK.Entrada)-SUM(TK.Salida)) * AVG(TK.Valor_Unitario)) As Total_Inventario ".
        "FROM Catalogo_Productos As CP, Trans_Kardex As TK ".
        "WHERE CP.Item = '". $this->NumEmpresa . "' ".
        "AND CP.Periodo = '". $this->Periodo_Contable . "' ".
        "AND TK.Fecha BETWEEN '". $FechaIni . "' and '". $FechaFin . "' ".
        $BSQL .
        "AND CP.Item = TK.Item ".
        "AND CP.Periodo = TK.Periodo ".
        "AND CP.Codigo_Inv = TK.Codigo_Inv ".
        "GROUP BY TK.Codigo_Inv, CP.Producto, TK.CodBodega, TK.Codigo_Barra, CP.Reg_Sanitario ".
        "HAVING SUM(TK.Entrada-TK.Salida) <> 0 ".
        "ORDER BY TK.Codigo_Inv, TK.Codigo_Barra ";

        $AdoDetKardex = $this->modelo->SelectDB($sSQL);
        $_SESSION['ResumenKC']['Opcion']=22;
        $_SESSION['ResumenKC']['AdoDetKardex'] = $sSQL;

        if(count($AdoDetKardex)>0){
            foreach ($AdoDetKardex as $key => $Fields) {
                $Debitos += number_format($Fields["Entradas"], 2,'.','');
                $Creditos += number_format($Fields["Salidas"], 2,'.','');
                $Stock_Inv += number_format($Fields["Stock_Lote"], 2,'.','');
            }
        }

        $LabelStock = number_format($Stock_Inv, 2,'.','');
        $heightDispo = ($heightDisponible>150)?$heightDisponible-45:$heightDisponible;
        $DGQuery = grilla_generica_new($sSQL,"Catalogo_Productos",'myTableDGQuery','',false,false,false,1,1,1,$heightDispo);
        return compact('DGQuery','LabelStock');
    }
}

?>