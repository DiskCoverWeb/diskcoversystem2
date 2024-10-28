<?php 
include(dirname(__DIR__,2).'/modelo/inventario/kardexM.php');
    require_once(dirname(__DIR__,3)."/lib/excel/plantilla.php");
if(!class_exists('cabecera_pdf'))
{
  require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
}

$controlador = new kardexC();
if(isset($_GET["generarExcelKardex"])){
    $controlador->CargaInicial($_GET);
    echo json_decode($controlador->generarExcelKardex($_GET));
    exit();
}else

if(isset($_GET['generarPDF']))
{
    $controlador->CargaInicial($_GET);
    $controlador->generarPDF($_GET);
    exit();
}
elseif(isset($_GET['cambiarProducto']))
{
	$codigoProducto = $_POST['codigoProducto'];
  	$datos = $controlador->ListarProductos('P',$codigoProducto);
  	echo json_encode($datos);
}

if(isset($_GET['Consultar_Tipo_Kardex']))
{
    $controlador->CargaInicial($_POST);
  	echo json_encode($controlador->Consultar_Tipo_De_Kardex($_GET['EsKardexIndividual'],$_POST));
}

if(isset($_GET['consulta_kardex']))
{
    $controlador->CargaInicial($_POST);
  	echo json_encode($controlador->Consultar_Kardex($_POST));
}

if(isset($_GET['kardex_total']))
{
  	echo json_encode($controlador->kardex_total());
}

if(isset($_GET['funcion']))
{
  $controlador->funcionInicio();
}
if(isset($_GET['ActualizarSerie']))
{
  echo json_encode($controlador->ActualizarSerie($_POST));
}
if(isset($_GET['CambiaCodigodeBarra']))
{
  echo json_encode($controlador->CambiaCodigodeBarra($_POST));
}
if(isset($_GET['ListarArticulos']))
{
  echo json_encode($controlador->ListarArticulos(true));
}
if(isset($_GET['ConfirmarCambiar_Articulo']))
{
  echo json_encode($controlador->ConfirmarCambiar_Articulo($_POST));
}

class kardexC
{
	private $modelo;
	private $pdf;
  public $NumEmpresa;
  public $Periodo_Contable;
	function __construct()
	{
		$this->modelo = new kardexM();
		$this->pdf = new cabecera_pdf();
    $this->NumEmpresa = $_SESSION['INGRESO']['item'];
    $this->Periodo_Contable = $_SESSION['INGRESO']['periodo'];
	}

	public function ListarProductos($tipo,$codigoProducto){
		$datos = $this->modelo->ListarProductos($tipo,$codigoProducto);
        $productos = [];
		foreach ($datos as $key => $value) {
			$productos[] = array('LabelCodigo'=>$value['Codigo_Inv']."/".$value['Minimo']."/".$value['Maximo']."/".$value['Unidad']."/".$value['Producto'],'nombre'=>mb_convert_encoding($value['NomProd'], 'UTF-8'));
		}
		if(count($productos)<=0){
			$productos[0] = array('LabelCodigo'=>'','nombre'=>'No existen datos.');
		}
		return $productos;
	}

	public function bodegas(){
		$datos = $this->modelo->bodegas();
		$bodegas = [];
		foreach ($datos as $key => $value) {
			$bodegas[] = array('LabelCodigo'=>$value['CodBod'],'nombre'=>mb_convert_encoding($value['CodBod'], 'UTF-8')." - ".mb_convert_encoding($value['Bodega'], 'UTF-8'));
		}
		if(count($bodegas)<=0){
			$bodegas[0] = array('LabelCodigo'=>'','nombre'=>'No existen datos.');
		}
		return $bodegas;
	}

	public function Consultar_Tipo_De_Kardex($EsKardexIndividual,$parametros){
		extract($parametros);
		$error = false;
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

	$GrupoInv = "";
    $Debe = 0;
    $Haber = 0;
    if(@$DCTInv!=""){
        $DCTInv = explode("/",$DCTInv);
        $DCTInv = $DCTInv[0];
    }
    $GrupoInv = trim($DCTInv);
    $Codigo = $LabelCodigo;
    $Codigo1 = trim($DCBodega);
    if ($Codigo == "") {
        $Codigo = ".";
    }
    if ($GrupoInv == "") {
        $GrupoInv = "*";
    }
  
    $sSQL = "SELECT TK.Codigo_Inv, CP.Producto, CP.Unidad, TK.CodBodega AS Bodega, TK.Fecha, TK.TP, TK.Numero, TK.Entrada, TK.Salida, TK.Existencia AS Stock, TK.Costo, " .
            "TK.Total AS Saldo, TK.Valor_Unitario, TK.Valor_Total, TK.TC, TK.Serie, TK.Factura, TK.Cta_Inv, TK.Contra_Cta, TK.Serie_No, TK.Codigo_Barra, TK.Lote_No, " .
            "TK.Codigo_Tra AS CI_RUC_CC, CM.Marca AS 'Marca_Tipo_Proceso', TK.Detalle, TK.Centro_Costo AS Beneficiario_Centro_Costo, TK.Orden_No, TK.ID " .
            "FROM Trans_Kardex AS TK, Catalogo_Productos AS CP, Catalogo_Marcas AS CM " .
            "WHERE TK.Item = '" . $this->NumEmpresa . "' " .
            "AND TK.Periodo = '" . $this->Periodo_Contable . "' " .
            "AND TK.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' " .
            "AND TK.T = '" . G_NORMAL . "' ";
    if ($EsKardexIndividual=="true" || (is_bool($EsKardexIndividual) && $EsKardexIndividual)) {
        $sSQL = $sSQL . "AND TK.Codigo_Inv = '" . $Codigo . "' ";
    } else {
        if ($GrupoInv != "*") {
            $sSQL = $sSQL . "AND TK.Codigo_Inv LIKE '" . $GrupoInv . "%' ";
        }
    }
    if (isset($CheqBod) && $CheqBod == 1) {
        $sSQL = $sSQL . "AND TK.CodBodega = '" . $Codigo1 . "' ";
    }
    $sSQL = $sSQL .
            "AND TK.Item = CP.Item " .
            "AND TK.Item = CM.Item " .
            "AND TK.Periodo = CP.Periodo " .
            "AND TK.Periodo = CM.Periodo " .
            "AND TK.Codigo_Inv = CP.Codigo_Inv " .
            "AND TK.CodMarca = CM.CodMar " .
            "ORDER BY TK.Codigo_Inv, TK.Fecha,TK.Entrada DESC,TK.Salida,TK.TP,TK.Numero,TK.ID ";
    //$SQLDec = "TK.Costo " . strval($Dec_Costo) . "| TK.Valor_Unitario " . strval($Dec_Costo) . "|,TK.Valor_Total 2|.";
    $AdoKardex = $this->modelo->SelectDB($sSQL);
    
    if ($EsKardexIndividual) {
      if (count($AdoKardex) > 0) {
        foreach ($AdoKardex as $key => $Fields) {
            $Debe = $Debe + $Fields["Entrada"];
            $Haber = $Haber + $Fields["Salida"];
        }
      }
    } 

    $LabelExitencia = number_format($Debe - $Haber, 2);

    // $botones[0] = array('boton'=>'Imprime Codigos de Barra', 'icono'=>'<i class="fa fa-file-pdf-o"></i>', 'tipo'=>'danger mr-1', 'id'=>'ImprimeCodigosBarra');
    $botones[1] = array('boton'=>'Cambiar Articulo', 'icono'=>'<i class="fa fa-refresh"></i>', 'tipo'=>'warning mr-1', 'id'=>'Producto,ID,TC,Serie,Factura,Codigo_Inv' );
    $botones[2] = array('boton'=>'Cambia Codigo de Barra', 'icono'=>'<i class="fa fa-barcode"></i>', 'tipo'=>'info mr-1', 'id'=>'Producto,ID,TC,Serie,Factura,Codigo_Inv' );
    $botones[3] = array('boton'=>'Cambia la Serie', 'icono'=>'<i class="fa fa-retweet"></i>', 'tipo'=>'success', 'id'=>'Producto,ID,TC,Serie,Factura,Codigo_Inv' );
    $med_b = "110";
    $_SESSION['DGKardex']['sSQL'] = $sSQL ;

    if($heightDisponible>135){
        $heightDisponible-=35;
    }
    $DGKardex = grilla_generica_new($sSQL,'Trans_Kardex','myTable','',$botones,false,false,1,1,1,$heightDisponible, med_b:$med_b);
		return compact('error','DGKardex','LabelExitencia');;
	}

	public function Consultar_Kardex($parametros){
		extract($parametros);
        $error = false;
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
        $Codigo = $LabelCodigo;
        $Codigo1 = trim($DCBodega);
        $Debe = 0;
        $Haber = 0;
        if ($Codigo == "") {
            $Codigo = ".";
        }

		$sSQL  =  "SELECT K.Codigo_Inv, K.Codigo_Barra, SUM(Entrada) As Entradas, SUM(Salida) As Salidas, SUM(Entrada-Salida) As Stock_Kardex 
            FROM Trans_Kardex As K, Comprobantes As C 
            WHERE K.Fecha BETWEEN '".$FechaIni."' AND '".$FechaFin."' 
            AND K.Codigo_Inv = '".$Codigo."'
            AND K.T = '".G_NORMAL."' 
            AND K.Item = '".$_SESSION['INGRESO']['item']."' 
            AND K.Periodo = '".$_SESSION['INGRESO']['periodo']."'";
        if (isset($CheqBod) && $CheqBod=='1') {
          $sSQL  .= "AND K.CodBodega = '".$Codigo1."' ";
        }
        $sSQL  .= "AND K.TP = C.TP 
                AND K.Fecha = C.Fecha 
                AND K.Numero = C.Numero 
                AND K.Item = C.Item 
                AND K.Periodo = C.Periodo 
                GROUP BY K.Codigo_Inv, K.Codigo_Barra
                HAVING SUM(Entrada-Salida) >=1 
                ORDER BY K.Codigo_Inv, K.Codigo_Barra ";
        //$_SESSION['DGKardex']['sSQL'] = $sSQL ;
        if($heightDisponible>135){
            $heightDisponible-=35;
        }
        $DGKardex = grilla_generica_new($sSQL ,'Trans_Kardex As K, Comprobantes As C','myTable','',false,false,false,1,1,1,$heightDisponible);

        $AdoKardex = $this->modelo->SelectDB($sSQL);
        if (count($AdoKardex) > 0) {
            foreach ($AdoKardex as $key => $Fields) {
              $Debe += $Fields["Stock_Kardex"];
            }
        }
        $LabelExitencia = number_format($Debe - $Haber, 2);
		return compact('error','DGKardex','LabelExitencia');
	}

	public function funcionInicio(){
		$this->modelo->funcionInicio();
	}

    public function ActualizarSerie($parametros){
        extract($parametros);
        if (strlen($CodigoP) > 1) {
          $sSQL = "UPDATE Trans_Kardex " .
            "SET Serie_No = '" . $CodigoP . "' " .
            "WHERE ID = " . $ID_Reg . " " .
            "AND Item = '" . $this->NumEmpresa . "' " .
            "AND Periodo = '" . $this->Periodo_Contable . "'";
            $rps=$this->modelo->ExecuteDB($sSQL);

          if (strlen($TC) == 2 && strlen($Serie) == 6 && $Factura > 0) {
            $sSQL = "UPDATE Detalle_Factura " .
              "SET Serie_No = '" . $CodigoP . "' " .
              "WHERE Item = '" . $this->NumEmpresa . "' " .
              "AND Periodo = '" . $this->Periodo_Contable . "' " .
              "AND TC = '" . $TC . "' " .
              "AND Serie = '" . $Serie . "' " .
              "AND Factura = " . $Factura . " " .
              "AND Codigo = '" . $CodigoInv . "'";
            $rps=$this->modelo->ExecuteDB($sSQL);
            if($rps!=1){
                return ['rps'=>false, 'mensaje'=>'No fue posible actualizar las facturas.'];
            }
          }
            if($rps!=1){
                return ['rps'=>false, 'mensaje'=>'No fue posible actualizar el kardex.'];
            }else{
                return ['rps'=>true, 'mensaje'=>'Proceso Terminado, vuelva a listar el documento'];
            }
        }
    }
    public function CambiaCodigodeBarra($parametros)
    {
        extract($parametros);
        if (strlen($CodigoB) > 1) {
          $sSQL = "UPDATE Trans_Kardex " .
            "SET Codigo_Barra = '" . $CodigoB . "' " .
            "WHERE ID = " . $ID_Reg . " " .
            "AND Item = '" . $this->NumEmpresa . "' " .
            "AND Periodo = '" . $this->Periodo_Contable . "' ";
            $rps=$this->modelo->ExecuteDB($sSQL);

          if (strlen($TC) == 2 && strlen($Serie) == 6 && $Factura > 0) {
            $sSQL = "UPDATE Detalle_Factura " .
            "SET Codigo_Barra = '" . $CodigoB . "' " .
            "WHERE Item = '" . $this->NumEmpresa . "' " .
            "AND Periodo = '" . $this->Periodo_Contable . "' " .
            "AND TC = '" . $TC . "' " .
            "AND Serie = '" . $Serie . "' " .
            "AND Factura = " . $Factura . " " .
            "AND Codigo = '" . $CodigoInv . "' ";
            $rps=$this->modelo->ExecuteDB($sSQL);
            if($rps!=1){
                return ['rps'=>false, 'mensaje'=>'No fue posible actualizar las facturas.'];
            }
          }
            if($rps!=1){
                return ['rps'=>false, 'mensaje'=>'No fue posible actualizar el kardex.'];
            }else{
                return ['rps'=>true, 'mensaje'=>'Proceso Terminado, vuelva a listar el documento'];
            }
        }
    }

    public function ListarArticulos($SoActivos)
    {
        $DCArt = $this->modelo->Listar_Articulos($SoActivos, true);
        $rps = true;
        return compact('rps','DCArt');
    }

    public function ConfirmarCambiar_Articulo($parametros)
    {
        extract($parametros);
        if (strlen($DCArt) > 1) {
          $sSQL = "UPDATE Trans_Kardex 
                SET Codigo_Inv = '" . $DCArt . "'
                WHERE ID = " . $ID_Reg . "
                AND Item = '" . $this->NumEmpresa . "'
                AND Periodo = '" . $this->Periodo_Contable . "'";
            $rps=$this->modelo->ExecuteDB($sSQL);

          if (strlen($TC) == 2 && strlen($Serie) == 6 && $Factura > 0) {
            $sSQL = "UPDATE Detalle_Factura 
            SET Codigo = '" . $DCArt. "'
            WHERE Item = '" . $this->NumEmpresa . "'
            AND Periodo = '" . $this->Periodo_Contable . "'
            AND TC = '" . $TC . "'
            AND Serie = '" . $Serie . "'
            AND Factura = " . $Factura . "
            AND Codigo = '" . $CodigoInv . "'";
            $rps=$this->modelo->ExecuteDB($sSQL);
            if($rps!=1){
                return ['rps'=>false, 'mensaje'=>'No fue posible actualizar las facturas.'];
            }
          }
            if($rps!=1){
                return ['rps'=>false, 'mensaje'=>'No fue posible actualizar el kardex.'];
            }else{
                return ['rps'=>true, 'mensaje'=>'Proceso Terminado, vuelva a listar el Kardex'];
            }
        }
    }
    public function generarExcelKardex($parametros){
        if(isset($_SESSION['DGKardex']['sSQL'])){
            extract($parametros);
            $titulo = 'Kardex del '.BuscarFecha($MBoxFechaI).' al '. BuscarFecha($MBoxFechaF);
            $sSQL   = $_SESSION['DGKardex']['sSQL'];
            $medidas = array(12,30,15,12,18,9,12,12,12,12,12,15,15,15,10,20,20,15,15,20,25,15,15,20,25,25,15,15,9);
            return exportar_excel_generico_SQl($titulo,$sSQL, $medidas, fecha_sin_hora:true);
        }else{
            die("Primero debe cargar la informacion");
        }
    }

	public function generarPDF($parametros){
        if(!isset($_SESSION['DGKardex']['sSQL'])){
            die("Primero debe cargar la informacion");
        }

        extract($parametros);
        $query = $_SESSION['DGKardex']['sSQL'];
        // Extraer la sección entre "SELECT" y "WHERE" usando una expresión regular
        $pattern = "/SELECT(.*?)FROM/s";
        $matches = [];
        preg_match($pattern, $query, $matches);
        $sectionToReplace = $matches[1];

        // Reemplazar la sección extraída con el nuevo contenido deseado
        $newSection = " TK.CodBodega AS Bod, TK.Fecha, TK.TP, TK.Numero, TK.Detalle, TK.Entrada, TK.Salida, TK.Valor_Unitario, TK.Valor_Total, TK.Existencia AS Cantidad, TK.Costo as Costo_Prom, TK.Total AS Saldo_Total ";
        $replacedQuery = str_replace($sectionToReplace, $newSection, $query);
        $result = $this->modelo->SelectDB($replacedQuery);
        $campos = array();
        foreach ($result[0] as $key => $value) {
            array_push($campos,$key);
        }
        $medi =array(8,17,9,28,90,13,12,23,18,15,19,18);
        $medip =array(8,17,9,28,90,13,12,23,18,15,19,18);

        $pdf = new cabecera_pdf();  
        $titulo = "CONTROL DE EXISTENCIAS";
        $mostrar = true;
        $sizetable =8;
        $tablaHTML = array();

        $tablaHTML[0]['medidas']=array(30, 30);
        $tablaHTML[0]['alineado']=array('L', 'L');
        $tablaHTML[0]['datos']=array($LabelCodigo,$LabelUnidad);
        $tablaHTML[0]['estilo']='I';
        $tablaHTML[0]['borde'] = '0';

        $tablaHTML[1]['medidas']=array(150);
        $tablaHTML[1]['alineado']=array('L');
        $tablaHTML[1]['datos']=array($NombreProducto);
        $tablaHTML[1]['estilo']='I';
        $tablaHTML[1]['borde'] = '0';

        $tablaHTML[2]['medidas']=array(100);
        $tablaHTML[2]['alineado']=array('L');
        $tablaHTML[2]['datos']=array("");
        $tablaHTML[2]['estilo']='I';
        $tablaHTML[2]['borde'] = '0';

        $tablaHTML[3]['medidas']=$medi;
        $tablaHTML[3]['alineado']=array('L','C','C','R','L','R','R','R','R','R','R','R');
        $tablaHTML[3]['datos']=$campos;
        $tablaHTML[3]['estilo']='B';
        $tablaHTML[3]['borde'] = 'B';
        $pos = 4;

        $TipoDoc = "";
        $Numero = "";
        $Detalle = "";
        $MiFecha = "";

        foreach ($result as $key => $value) {

            $Entrada = ($value["Entrada"]!=0)? number_format($value["Entrada"], 2, '.', ''):"";
            $Salida = ($value["Salida"]!=0)? number_format($value["Salida"], 2, '.', ''):"";
            $Stock = ($value["Cantidad"]!=0)? number_format($value["Cantidad"], 2, '.', ''):"";
            $Valor_Unitario = ($value["Valor_Unitario"]!=0)? number_format($value["Valor_Unitario"], 2, '.', ''):"";
            $Valor_Total = ($value["Valor_Total"]!=0)? number_format($value["Valor_Total"], 2, '.', ''):"";
            $Costo = ($value["Costo_Prom"]!=0)? number_format($value["Costo_Prom"], 2, '.', ''):"";
            $Saldo = number_format($value["Saldo_Total"], 2, '.', '');
            
            $data = array($value["Bod"],$value["Fecha"]->format('Y-m-d'),"",$value['Numero'],"",$Entrada, $Salida, $Valor_Unitario, $Valor_Total, $Stock, $Costo, $Saldo);
            if($TipoDoc!=$value['TP'] || $Numero != $value['Numero']){
                $TipoDoc = $value['TP'];
                $Numero = $value['Numero'];
                $data[2] = $TipoDoc;
                $data[4] = $value['Detalle'];
            }

            $tablaHTML[$pos]['medidas']=$tablaHTML[3]['medidas'];
            $tablaHTML[$pos]['alineado']=$tablaHTML[3]['alineado'];
            $tablaHTML[$pos]['datos']=$data;
            $tablaHTML[$pos]['estilo']='I';
            $tablaHTML[$pos]['borde'] = 'LR';
            $pos = $pos+1;
            $Detalle = $value['Detalle'];
            $MiFecha = $value["Fecha"]->format('Y-m-d');
        }
        $tablaHTML[$pos-1]['borde'] = 'LRB';
        $pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$MBoxFechaI,$MBoxFechaF,$sizetable,$mostrar, orientacion: 'L', mostrar_cero:true);
	}

    public function CargaInicial($parametros)
    {
        extract($parametros);
        $FechaIni = BuscarFecha($MBoxFechaI);
        $FechaFin = BuscarFecha($MBoxFechaF);
        $Codigo1 = trim(@$DCBodega);
        if ($Codigo1 == "") {
            $Codigo1 = ".";
        }

        $sSQL = "UPDATE Trans_Kardex " .
            "SET Codigo_Tra = '.' " .
            "WHERE Item = '" . $this->NumEmpresa . "' " .
            "AND Periodo = '" . $this->Periodo_Contable . "' " .
            "AND Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'";
        Ejecutar_SQL_SP($sSQL);

        $sSQL = "UPDATE Trans_Kardex " .
            "SET Centro_Costo = SUBSTRING(C.Cliente,1,50), Codigo_Tra = C.CI_RUC " .
            "FROM Trans_Kardex AS TK, Clientes AS C " .
            "WHERE TK.Item = '" . $this->NumEmpresa . "' " .
            "AND TK.Periodo = '" . $this->Periodo_Contable . "' " .
            "AND TK.Codigo_P <> '.' " .
            "AND TK.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' " .
            "AND TK.Codigo_P = C.Codigo";
        Ejecutar_SQL_SP($sSQL);

        $sSQL = "UPDATE Trans_Kardex " .
            "SET Centro_Costo = SUBSTRING(CS.Detalle,1,50), Codigo_Tra = CS.Codigo " .
            "FROM Trans_Kardex AS TK, Catalogo_SubCtas AS CS " .
            "WHERE TK.Item = '" . $this->NumEmpresa . "' " .
            "AND TK.Periodo = '" . $this->Periodo_Contable . "' " .
            "AND TK.Codigo_P <> '.' " .
            "AND TK.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' " .
            "AND TK.Item = CS.Item " .
            "AND TK.Periodo = CS.Periodo " .
            "AND TK.Codigo_P = CS.Codigo";
        Ejecutar_SQL_SP($sSQL);

        $sSQL = "UPDATE Trans_Kardex " .
            "SET Centro_Costo = SUBSTRING(CS.Detalle,1,50), Codigo_Tra = CS.Codigo " .
            "FROM Trans_Kardex AS TK, Catalogo_SubCtas AS CS " .
            "WHERE TK.Item = '" . $this->NumEmpresa . "' " .
            "AND TK.Periodo = '" . $this->Periodo_Contable . "' " .
            "AND TK.CodigoL <> '.' " .
            "AND TK.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' " .
            "AND TK.Item = CS.Item " .
            "AND TK.Periodo = CS.Periodo " .
            "AND TK.CodigoL = CS.Codigo";
        Ejecutar_SQL_SP($sSQL);

        $sSQL = "UPDATE Trans_Kardex " .
            "SET Centro_Costo = '.' " .
            "WHERE Centro_Costo IS NULL";
        Ejecutar_SQL_SP($sSQL);

    }

}

?>