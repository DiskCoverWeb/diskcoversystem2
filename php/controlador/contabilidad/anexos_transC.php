<?php 
include(dirname(__DIR__,2).'/modelo/contabilidad/anexos_transM.php');
if(!class_exists('variables_g'))
{
include(dirname(__DIR__,2).'/db/variables_globales.php');//
}
include(dirname(__DIR__,3).'/lib/fpdf/PDF_MC_Table.php');
/**
 * 
 */
$controlador = new anexos_transC();
if(isset($_GET['meses']))
{
	echo json_encode(meses_del_anio());
}
if(isset($_GET['year']))
{
	echo json_encode($controlador->year());
}
if(isset($_GET['generar_ats']))
{
  //$controlador->Carga_Paginas_AT('20200101','20200131','20200115');
	 echo json_encode($controlador->generar_ats($_POST['parametros']));
}
if(isset($_GET['vista_ats']))
{

  // $controlador->Carga_Paginas_AT('20200101','20200131','20200115');
  // // $parametros = '';
  $parametros = $_POST["parametros"];
  $fechas_ats = Fecha_Del_AT(nombre_X_mes($parametros['mes']),$parametros['year']);
  // print_r($fechas_ats);die();
  $url = $controlador->Carga_Paginas_AT($fechas_ats['FechaIni'],$fechas_ats['FechaFin'],$fechas_ats['FechaMit']);
  // print_r($url);die();
  echo json_encode($url);
}

class anexos_transC
{
	private $modelo;	
  private $pdf;
	function __construct()
	{
		$this->modelo = new anexos_transM();
    $this->pdf = new PDF_MC_Table();
	}
	function year()
	{
    $mes  =  $this->modelo->year();    
		$mese = array();
    foreach ($mes as $key => $value) {
      $mese[] = array('year'=>$value['Anio'],'num'=>'0'.($key+1));
    }

  	return $mese;

	}
	function generar_ats($parametros)
	{

		$mes = nombre_X_mes($parametros['mes']);
		$fechas_ats = Fecha_Del_AT($mes,$parametros['year']);
    // print_r($fechas_ats);die();
		$Id_Mes = $mes ;//date("m", strtotime($fechas_ats['FechaMit']));
        $Anio = $parametros['year'];     
		$FechaFin = $fechas_ats['FechaFin'];
		$FechaIni = $fechas_ats['FechaIni'];
		$FechaMid = $fechas_ats['FechaMit'];
     // FA.Factura = 0
     // FA.Fecha_Corte = FechaSistema
     // Progreso_Esperar
     
     // If InStr(1, CStr(FechaFin), "-") > 0 Then Numero = CLng(Replace(FechaFin, "-", "")) Else Numero = CLng(FechaFin)
	   $pos = '';
     $pos = strpos($FechaFin,"-");
     if ($pos !== false) 
     {
     	$Numero =  str_replace('-','', $FechaFin);
     }else 
     {
       $Numero = $FechaFin;
       // print_r($Numero);die();
       if(Actualizar_Datos_ATS_SP($_SESSION['INGRESO']['item'],$FechaIni,$FechaFin,$Numero)==1)
       	 {
       	 	// 'LISTA DE CODIGO DE ANEXOS
          // print_r('expression');die();
              $lista = $this->modelo->codigo_anexo($FechaMid);
       	 }
     }
     // comienza el proceso de generar ats
     //Consultar_Anexos
     // print_r('expression');die();
    $resultados =  $this->consulta_anexos($FechaIni,$FechaFin,false);

    // print_r($resultados);die();

		if($this->xml($mes,$Anio,$resultados,$FechaIni,$FechaFin)==1)
    {
       $carpeta = "AT".$_SESSION['INGRESO']['item'];
       $archivo ='AT'.date('m',strtotime($FechaIni)).''.date('Y',strtotime($FechaIni)).'.xml';
       $url = array('url'=> mb_convert_encoding(dirname(__DIR__)."/vista/TEMP/".$carpeta.'/'.$archivo, 'UTF-8'),'archivo'=>$archivo);
       return $url;
    }else
    {
      return '0';
    }
    
	}



	function consulta_anexos($FechaIni,$FechaFin,$ConSucursal)
	{
		$resultados =  array();
  // Dim FechaIniDep As String
  // Dim FechaFinDep As String
  // Dim CodCiudad As String
  // Dim Cont_RDEP As Integer
      
  // Anio_Anexo = Toolbar1.buttons(2).Caption
  // Mes_Anexo = Toolbar1.buttons(3).Caption
  // Fecha_Del_AT Mes_Anexo, Anio_Anexo
  
 // 'Renumero la Linea_SRI de todas las Tablas

   $resultado = $this->modelo->encerar_lineas_sri($FechaIni,$FechaFin,'');

  
 // 'Asignar el contador de las Transacciones
                        // print_r('expression');die(); 
  $this->modelo->Numerar_Lineas_SRI("Trans_Anulados","",$FechaIni,$FechaFin,$ConSucursal=false);
  
       // print_r('expresaaasion');die();
				// print_r('expression');die();

  $this->modelo->Numerar_Lineas_SRI("Trans_Compras","C",$FechaIni,$FechaFin,$ConSucursal=false);
  // Numerar_Lineas_SRI "Trans_Compras", "C"
  // // 'Numerar_Lineas_SRI "Trans_Ventas", "V"
  // Numerar_Lineas_SRI "Trans_Importaciones", "I"
   $this->modelo->Numerar_Lineas_SRI("Trans_Importaciones", "I",$FechaIni,$FechaFin,$ConSucursal=false);
  // Numerar_Lineas_SRI "Trans_Exportaciones", "E"
    $this->modelo->Numerar_Lineas_SRI("Trans_Exportaciones", "E",$FechaIni,$FechaFin,$ConSucursal=false);
      
 //  If Periodo_Contable = "" Then Periodo_Contable = Ninguno
 // 'CLIENTES
  ini_set('memory_limit', '-1');
  $sql= "SELECT * FROM Clientes WHERE Codigo <> '.' ORDER BY Cliente ";
  // $sql ="select *  from(SELECT ROW_NUMBER() OVER(ORDER BY ID) AS rownum,*  FROM Clientes) m   WHERE m.rownum BETWEEN 1 AND 10000 AND Codigo<>'.'";
  $CLIENTES = array();
 // preguntar para que clinetes esta aqui si no se utiliza //$this->modelo->traer_datos($sql);
 
 // 'ANULADOS

  // print_r($CLIENTES);die();
  $sql=  "SELECT * FROM Trans_Anulados WHERE FechaAnulacion Between '".$FechaIni."' AND '".$FechaFin."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item = '".$_SESSION['INGRESO']['item']."' ORDER BY Linea_SRI,FechaAnulacion ";
  $ANULADOS = $this->modelo->traer_datos($sql);
 //  SelectAdodc AdoAnulados, sSQL
  
 // 'COMPRAS
   $sql = "SELECT C.Cliente, C.Codigo, C.CI_RUC, C.TD, C.Tipo_Pasaporte, C.Parte_Relacionada, TC.* FROM Trans_Compras As TC, Clientes As C WHERE TC.Fecha Between '".$FechaIni."' AND '".$FechaFin."' 
       AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       AND TC.Item = '".$_SESSION['INGRESO']['item']."' 
       AND TC.IdProv = C.Codigo 
       ORDER BY TC.Linea_SRI,C.Cliente, C.CI_RUC, C.TD ";
     $COMPRAS = $this->modelo->traer_datos($sql);

  // print_r($COMPRAS);die();
 //  SelectAdodc AdoCompras, sSQL
  
 // 'IMPORTACIONES
   $sql = "SELECT C.Cliente, C.Codigo, C.CI_RUC, C.TD,TI.* 
     FROM Trans_Importaciones As TI, Clientes As C 
     WHERE TI.Fecha Between '".$FechaIni."' AND '".$FechaFin."'  
     AND TI.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
     AND TI.Item = '".$_SESSION['INGRESO']['item']."' 
     AND TI.IdFiscalProv = C.Codigo  
     ORDER BY TI.Linea_SRI,C.Cliente, C.CI_RUC, C.TD ";
     $IMPORTACIONES = $this->modelo->traer_datos($sql);
 //  SelectAdodc AdoImportaciones, sSQL
 

 // 'EXPORTACIONES
   $sql = "SELECT C.Cliente, C.Codigo, C.CI_RUC, C.TD,TE.* 
     FROM Trans_Exportaciones As TE, Clientes As C 
     WHERE TE.Fecha Between '".$FechaIni."' AND '".$FechaFin."'  
     AND TE.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
     AND TE.Item = '".$_SESSION['INGRESO']['item']."' 
     AND TE.IdFiscalProv = C.Codigo  
     ORDER BY TE.Linea_SRI,C.Cliente, C.CI_RUC, C.TD ";
    $EXPORTACIONES = $this->modelo->traer_datos($sql);
 //  SelectAdodc AdoExportaciones, sSQL
  
  if($_SESSION['INGRESO']['Tipo_Base']=="SQL SERVER")
  {
  	$FechaIniDep = DateTime::createFromFormat('d/m/Y', "01/01/".date('Y',strtotime($FechaFin)))->format('Ymd');
  	$FechaFinDep = DateTime::createFromFormat('d/m/Y', "31/12/".date('Y',strtotime($FechaFin)))->format('Ymd'); 
  }else
  {
  	$FechaIniDep = DateTime::createFromFormat('d/m/Y', "01/01/".date('Y',strtotime($FechaFin)))->format('Y-m-d');
  	$FechaFinDep = DateTime::createFromFormat('d/m/Y', "31/12/".date('Y',strtotime($FechaFin)))->format('Y-m-d'); 
  }
    
 // 'RELACION POR DEPENDENCIA
  $sql2 = "SELECT C.Codigo,C.Cliente,TD.Subtotal,TD.Item,TD.Periodo 
     FROM Catalogo_Rol_Pagos As TD,Clientes As C 
     WHERE TD.Fecha_107 Between '".$FechaIniDep."' AND '".$FechaFinDep."' 
     AND TD.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
     //ojo con esta secuencia el sql no se sabe a cual se liga
      if ($ConSucursal){
         if(strlen($No_ATS) > 3){
     	    $sql.="AND TD.Item NOT IN (".$No_ATS.") ";
     	  }
        }else{
         $sql.="AND TD.Item = '".$_SESSION['INGRESO']['item']."' ";
        }

   $sql2.="AND TD.Codigo = C.Codigo ORDER BY C.Cliente ";
   $realcion_depen = $this->modelo->traer_datos($sql2);
//   $this->modelo->generar_grilla($sql);
   // crea un grid com los roles de pago enviar una grilla 
 //  SelectDataGrid DGRolPagos, AdoRolPagos, SQL2

   if(count($realcion_depen)>0)
   {
   	  $Cont_RDEP = 1;
   	  foreach ($realcion_depen as $key => $value) {
   	  	$CodigoCli = $value["Codigo"];
   	  	 $sql = "UPDATE Catalogo_Rol_Pagos 
             SET Linea_SRI = ".$Cont_RDEP." 
             WHERE Fecha Between '".$FechaIniDep."' AND '".$FechaFinDep."' 
             AND Codigo = '".$CodigoCli."' 
             AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
             if($ConSucursal)
             {
             	if(strlen($No_ATS)>3)
             	{
             		 $sql.= "AND Item NOT IN (".$No_ATS.") ";
             	}
             }else
             {
             	$sql.="AND Item = '".$_SESSION['INGRESO']['item']."' ";
             }

         // 'Calculamos el SubTotal
          $sql .= "UPDATE Catalogo_Rol_Pagos 
             SET SubTotal=SuelSal+SobSuelComRemu+DecimTer+DecimCuar-ApoPerIess-RebEspDiscap-RebEspTerEd 
             WHERE Fecha Between '".$FechaIniDep."' AND '".$FechaFinDep."' 
             AND Codigo = '".$CodigoCli."' 
             AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
         if($ConSucursal)
             {
             	if(strlen($No_ATS)>3)
             	{
             		 $sql.= "AND Item NOT IN (".$No_ATS.") ";
             	}
             }else
             {
             	$sql.="AND Item = '".$_SESSION['INGRESO']['item']."' ";
             }
         // 'Conectar_Ado_Execute sSQL
         // 'Calculamos la base imponible
          $sql.= "UPDATE Catalogo_Rol_Pagos 
             SET BasImp = SubTotal-ImpRentEmpl 
             WHERE Fecha Between '".$FechaIniDep."' AND '".$FechaFinDep."' 
             AND Codigo = '".$CodigoCli."' 
             AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
          if($ConSucursal)
             {
             	if(strlen($No_ATS)>3)
             	{
             		 $sql.= "AND Item NOT IN (".$No_ATS.") ";
             	}
             }else
             {
             	$sql.="AND Item = '".$_SESSION['INGRESO']['item']."' ";
             }

   	  	$Cont_RDEP = $Cont_RDEP+1;   	  	
   	  }
   }
  $sql2 .= "SELECT E.RUC,C.Cliente,C.CI_RUC,C.TD,C.Direccion,C.DirNumero,C.Ciudad,C.Prov,C.Telefono,C.Pais,TD.* 
     FROM Catalogo_Rol_Pagos As TD, Clientes As C, Empresas As E 
     WHERE TD.Fecha_107 Between '".$FechaIniDep."' AND '".$FechaFinDep."' 
     AND TD.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
  if($ConSucursal)
    {
      if(strlen($No_ATS)>3)
     {
       $sql2_.= "AND Item NOT IN (".$No_ATS.") ";
     }
    }else
    {
     $sql2.="AND Item = '".$_SESSION['INGRESO']['item']."' ";
    }
  $sql2.= "AND TD.Item = E.Item   AND TD.Codigo = C.Codigo ORDER BY TD.Linea_SRI,C.Cliente ";

   // $Cat_Rol_Pag = $this->modelo->traer_datos($sql2);
   // $this->modelo->generar_grilla($sql2_);
 //  SelectDataGrid DGRolPagos, AdoRolPagos, SQL2
 // 'MsgBox SQL2 & vbCrLf & AdoRolPagos.Recordset.RecordCount
 
 // 'VENTAS
  $sql = "SELECT RUC_CI,TB,Razon_Social,TipoComprobante,RetPresuntiva,
     PorcentajeIva,PorcentajeIce,IvaPresuntivo,PorRetBienes,PorRetServicios,
     SUM(NumeroComprobantes) As NumeroComprobantesV,
     SUM(BaseImponible) As BaseImponibleV,
     SUM(BaseImpGrav) As BaseImpGravV,
     SUM(MontoIva) As MontoIvaV,
     SUM(BaseImpIce) As BaseImpIceV,
     SUM(MontoIce) As MontoIceV,
     SUM(MontoIvaBienes) As MontoIvaBienesV,
     SUM(ValorRetBienes) As ValorRetBienesV,
     SUM(MontoIvaServicios) As MontoIvaServiciosV,
     SUM(ValorRetServicios) As ValorRetServiciosV 
     FROM Trans_Ventas 
     WHERE Fecha Between '".$FechaIni."' AND '".$FechaFin."' 
     AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
     AND Item = '".$_SESSION['INGRESO']['item']."' 
     GROUP BY RUC_CI,TB,Razon_Social,TipoComprobante,RetPresuntiva,PorcentajeIva,PorcentajeIce,PorRetBienes,PorRetServicios,IvaPresuntivo 
     ORDER BY RUC_CI,TB,Razon_Social ";
   $VENTAS = $this->modelo->traer_datos($sql);
 //  SelectAdodc AdoVentas, sSQL
 
 // 'PUNTO DE VENTAS
 // 'TV.PuntoEmision,
  $sql = "SELECT TipoComprobante,Establecimiento, 
     RetPresuntiva,PorcentajeIva,PorcentajeIce,
     IvaPresuntivo,PorRetBienes,PorRetServicios,
     SUM(NumeroComprobantes) As NumeroComprobantesV,
     SUM(BaseImponible) As BaseImponibleV,
     SUM(BaseImpGrav) As BaseImpGravV,
     SUM(MontoIva) As MontoIvaV,
     SUM(BaseImpIce) As BaseImpIceV,
     SUM(MontoIce) As MontoIceV,
     SUM(MontoIvaBienes) As MontoIvaBienesV,
     SUM(ValorRetBienes) As ValorRetBienesV,
     SUM(MontoIvaServicios) As MontoIvaServiciosV,
     SUM(ValorRetServicios) As ValorRetServiciosV 
     FROM Trans_Ventas 
     WHERE Fecha Between '".$FechaIni."' AND '".$FechaFin."' 
     AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
     AND Item = '".$_SESSION['INGRESO']['item']."' 
     GROUP BY Establecimiento,PuntoEmision,TipoComprobante,RetPresuntiva,PorcentajeIva,PorcentajeIce,PorRetBienes,PorRetServicios,IvaPresuntivo 
     ORDER BY Establecimiento ";
     $PUNTO_VENTA = $this->modelo->traer_datos($sql);

 //  SelectAdodc AdoPuntosVentas, sSQL
 // 'MsgBox AdoVentas.Recordset.RecordCount & vbCrLf & AdoPuntosVentas.Recordset.RecordCount
 // 'LISTA LA BASES DE CIUDADES
  $sql = "SELECT * 
     FROM Tabla_Naciones 
     WHERE TR = 'C' 
     ORDER BY CProvincia, Descripcion_Rubro ";
 //  SelectAdodc AdoCiudades, sSQL
     $resultado = array('clientes' => $CLIENTES,'anulados'=>$ANULADOS,'compras'=>$COMPRAS,'importaciones'=> $IMPORTACIONES,'exportaciones'=>$EXPORTACIONES,'ventas'=>$VENTAS,'punto_venta'=>$PUNTO_VENTA);

     // print_r($resultado);die();

     return $resultado;
	}


	function xml($mes,$anio,$parametros,$FechaInicial,$FechaFin,$ConSucursal=false)
	{
    // $archivo = fopen('mio.xml',"u+");
		$total_ventas_estab = 0;
		$tota_nc_estab = 0;
		$numero_establecimientos = 0;
		for ($i=1; $i <1000 ; $i++) { 
			$Total_Ventas[$i] = 0;
            $Total_NC[$i] = 0;
		}
		if(count($parametros['punto_venta'])>0)
		{
			foreach ($parametros['punto_venta'] as $key => $value) {
				$KE = intval($value['Establecimiento']);
				$suma_ventas = $value['BaseImponibleV'];
				// print_r(intval($value['BaseImpGravV']));die();
				if(intval($value['BaseImpGravV']) <> 0)
				{
					$suma_ventas = 0;					
					$suma_ventas = $suma_ventas + $value["BaseImpGravV"];					
				}
				if($value['TipoComprobante']==4)
				{
					$tota_nc_estab= $tota_nc_estab + $suma_ventas;
					$Total_NC[$KE] = $Total_NC[$KE] + $suma_ventas;
				}else
				{
					$Total_Ventas[$KE] = $Total_Ventas[$KE] + $suma_ventas;
					$total_ventas_estab = $total_ventas_estab + $suma_ventas;
				}
				
			}


		}
		for ($i=1; $i <1000 ; $i++) { 
			if($Total_Ventas[$i] - $Total_NC[$i] <> 0)
			{
				$numero_establecimientos = $numero_establecimientos + 1;
			}
		}
		
   $ruta = '../../../php/vista/TEMP/';
   $ruta1 = '../vista/TEMP/';
   $carpeta = "AT".$_SESSION['INGRESO']['item'];
    if(!file_exists($ruta.$carpeta))
     {
       mkdir($ruta.''.$carpeta, 0777, true);
     }
   $archivo = 'AT'.date('m',strtotime($FechaInicial)).''.date('Y',strtotime($FechaInicial)).'.xml';
   fopen($ruta.$carpeta.'/'.$archivo, 'w');
   $url = $ruta1.$carpeta.'/'.$archivo;
   // print_r($url);die();
$xml = new XMLWriter();
$xml->openMemory();
// print_r(dirname(__DIR__,2));die();
$xml->openUri(dirname(__DIR__,3).'/php/vista/TEMP/'.$carpeta.'/'.$archivo);
$xml->setIndent(true);
$xml->setIndentString('	'); 
$xml->startDocument('1.0', 'UTF-8');
$xml->startElement('iva');
$xml->writeElement('TipoIDInformante',"R");
$xml->writeElement('IdInformante',$_SESSION['INGRESO']['RUC']);
// $xml->writeElement('razonSocial',$_SESSION['INGRESO']['Razon_Social']);
$xml->writeElement('razonSocial',$_SESSION['INGRESO']['Nombre_Comercial']);
$xml->writeElement('Anio',$anio);
$xml->writeElement('Mes',$mes);
if(($total_ventas_estab-$tota_nc_estab)<>0)
{
	$xml->writeElement('numEstabRuc',$numero_establecimientos);
}

$xml->writeElement('totalVentas', bcdiv(($total_ventas_estab-$tota_nc_estab), '1', 2));
$xml->writeElement('codigoOperativo', "IVA");
$xml->startElement('compras');

if(count($parametros['compras']))
{
	foreach ($parametros['compras'] as $key => $value) 
	{

		$tipodoc = "03";
		$td = $value['TD'];
		switch ($td) {
			case ($td == 'R'):
				$tipodoc = '01';
				break;
			case ($td=='C'):
				$tipodoc = '02';
				break;
			case ($td=='P'):
				$tipodoc = '03';
				break;
		}

		$xml->startElement('detalleCompras');
		$xml->writeElement('CodSustento', $value['CodSustento']);
		$xml->writeElement('tpIdProv',$tipodoc);
		$xml->writeElement('id_Prov', $value['CI_RUC']);
		if($value['TipoComprobante']<9)
		{
		$xml->writeElement('tipoComprobante',"0".$value['TipoComprobante']);
     	}else
     	{

		$xml->writeElement('tipoComprobante', $value['TipoComprobante']);
     	}
		// Si es Pasaporte genera la parte relacionada//
          if($value["TD"] == "P"){
             if(date('d-m-y',strtotime($FechaInicial)) >= date('d/m/Y',strtotime("01/05/2016"))){
                $xml->writeElement("tipoProv", "01");
                $xml->writeElement("denoProv", $value["Cliente"]);
                $xml->writeElement("parteRel", $value["Parte_Relacionada"]);
             }else{
                $xml->writeElement("tipoProv", $value["Tipo_Pasaporte"]);
                $xml->writeElement("parteRel", $value["Parte_Relacionada"]);
             }
          }else{
             $xml->writeElement("tipoProv", "01");
             $xml->writeElement("parteRel", "NO");
          }
          $xml->writeElement("fechaRegistro", $value["FechaRegistro"]->format('d/m/Y'));
          $xml->writeElement("establecimiento", $value["Establecimiento"]);
          $xml->writeElement("puntoEmision", $value["PuntoEmision"]);
          $xml->writeElement("secuencial", $value["Secuencial"]);
          $xml->writeElement("fechaEmision", $value["FechaEmision"]->format('d/m/Y'));
          $xml->writeElement("autorizacion", $value["Autorizacion"]);
          $xml->writeElement("baseNoGraIva", bcdiv($value["BaseNoObjIVA"], '9', 2));        // hay que aumentar
          $xml->writeElement("baseImponible", bcdiv($value["BaseImponible"], '9', 2));
          $xml->writeElement("baseImpGrav", bcdiv($value["BaseImpGrav"], '9', 2));
          $xml->writeElement("baseImpExe", "0.00");     //Por Adaptar en la pantalla
          $xml->writeElement("montoIce",bcdiv($value["MontoIce"], '9', 2));
          $xml->writeElement("montoIva",bcdiv($value["MontoIva"], '9', 2));
          $ValRetBien10 = 0;
          $ValRetServ20 = 0;
          $ValRetServ50 = 0;
          if ($value["Porc_Bienes"] == "10"){ $ValRetBien10 = $value["valorRetBienes"];}
          if ($value["Porc_Servicios"] == "20"){ $ValRetServ20 = $value["ValorRetServicios"];}
          if ($value["Porc_Servicios"] == "50"){ $ValRetServ50 = $value["ValorRetServicios"];}
           
          $xml->writeElement("valRetBien10", bcdiv($ValRetBien10, '9', 2));
          $xml->writeElement("valRetServ20", bcdiv($ValRetServ20, '9', 2));
          if($ValRetBien10 != 0) //ojo con esta sentencia
          {
             $xml->writeElement("valorRetBienes", bcdiv($value["ValorRetBienes"], '9', 2));
          }else{
             $xml->writeElement("valorRetBienes", "0.00");
          }
          $xml->writeElement("valRetServ50", bcdiv($ValRetServ50, '9', 2));
          if($ValRetServ20 == 0){
             if($value["ValorRetServicios"] == $value["MontoIva"]){
                 $xml->writeElement("valorRetServicios",  bcdiv(0, '1', 2));
                 $xml->writeElement("valRetServ100", bcdiv($value["ValorRetServicios"], '9', 2));    //'hay que aumentar
             }else{
                 $xml->writeElement("valorRetServicios", bcdiv($value["ValorRetServicios"], '9', 2));
                 $xml->writeElement("valRetServ100", bcdiv(0, '9', 2));  // 'hay que aumentar            
                 }
          }else{
             $xml->writeElement("valorRetServicios",bcdiv(0, '9', 2));
             $xml->writeElement("valRetServ100", bcdiv(0, '9', 2));    //'hay que aumentar
          }
                    
          $xml->writeElement("totbasesImpReemb", "0.00");    // 'Por Adaptar en la pantalla
          $xml->startElement("pagoExterior");
                $xml->writeElement("pagoLocExt", $value["PagoLocExt"]);
                if(is_numeric($value["PaisEfecPago"]))
                {
                   $xml->writeElement("paisEfecPago", $value["PaisEfecPago"]);
                }else{
                   $xml->writeElement("paisEfecPago", $value["PaisEfecPago"]);
                }
                $xml->writeElement("aplicConvDobTrib", $value["AplicConvDobTrib"]);
                $xml->writeElement("pagExtSujRetNorLeg", $value["PagExtSujRetNorLeg"]);
               // '$xml->writeElement("pagoRegFis", "NA")  'Por programar en la pantalla
         $xml->endElement();//cierra pago esterior
          
          if (($value["BaseNoObjIVA"] + $value["BaseImponible"] + $value["BaseImpGrav"] + $value["MontoIva"]) >= 1000 ){
             $xml->startElement("formasDePago");
                   $xml->writeElement("formaPago", $value["FormaPago"]);
             $xml->endElement();//cierraformasDePago
          }

         // 'Asigno a variables los campos para el concepto Air
          $CodigoCliente = $value["IdProv"];
          $Factura_No = $value["Secuencial"];
          $Codigo1 = $value["Establecimiento"];
          $Codigo2 = $value["PuntoEmision"];
          $Estab = "000";
          $PtoRet = "000";
          $FechaRet = $value["FechaRegistro"];
          $AutRet = "0000000000";
          $SecRet = 0;
          $Number = False;
          $Poner_Porc_Air = 0;
          $Porcentaje = "0";
         // 'Genero el AIR//
          $sql= "SELECT * 
             FROM Trans_Air 
             WHERE Fecha Between '".$FechaInicial."' AND '".$FechaFin."' 
             AND Periodo = '".$_SESSION['INGRESO']['periodo']."'"; 
          if($ConSucursal){
             if (strlen($No_ATS) > 3)
             {
             	$sql.=" AND Item NOT IN (".$No_ATS.") ";
             }
          }else{
             $sql.=" AND Item = '".$_SESSION['INGRESO']['item']."' ";
          }
          $sql.="AND T = '".G_NORMAL."'
             AND Tipo_Trans = 'C' 
             AND IdProv = '".$CodigoCliente."' 
             AND Factura_No = ".$Factura_No." 
             AND EstabFactura = '".$Codigo1."' 
             AND PuntoEmiFactura = '".$Codigo2."' 
             ORDER BY SecRetencion,EstabFactura,PuntoEmiFactura,Factura_No ";
             // print($sql);die();
          $AIR = $this->modelo->traer_datos($sql);
           $Codigo ='';
                $Total_SubTotal ='';
                $Porcentaje = '';
                $Valor = '';
                $Estab = '';
                $PtoRet = '';
                $SecRet = '';
                $AutRet = '';

          if(count($AIR)>0)
         {

          	$xml->startElement("air");
          	foreach ($AIR as $key1 => $value1) {
          	   
          		// 'Asigno en variables los campos
                if ($Porcentaje <> "0" )
                	{

                		$Poner_Porc_Air = 1;
                	}
                $Codigo = $value1["CodRet"];
                $Total_SubTotal = $value1["BaseImp"];
                $Porcentaje = $value1["Porcentaje"];
                $Valor = $value1["ValRet"];
                $Estab = $value1["EstabRetencion"];
                $PtoRet = $value1["PtoEmiRetencion"];
                $SecRet = $value1["SecRetencion"];
                $AutRet = $value1["AutRetencion"];
                if(strlen($AutRet) < 3)
                	{
                		$AutRet = "0000000000";
                	}
                $Number = True;
                // Print #NumFile, AbrirXML("detalleAir")
                $xml->startElement("detalleAir");
                $xml->writeElement("codRetAir", $Codigo);
                $xml->writeElement("baseImpAir", bcdiv($Total_SubTotal, '9', 2)); 
                $xml->writeElement("porcentajeAir", bcdiv(($Porcentaje * 100), '9', 2)); 
                $xml->writeElement("valRetAir", bcdiv($Valor, '9', 2)); 
                // Print #NumFile, CerrarXML("detalleAir")
                 $xml->endElement();//cierradetalleAir
                // 'If $value["TP") = "CE" And $value["Numero") = 3000338 Then MsgBox "..."
          	}
          	$xml->endElement();//cierra Air
          	 // Print #NumFile, CerrarXML("air")
          //   'Aqui se genera la segunda parte del detalle Compras
             if ($Poner_Porc_Air == 1  && $SecRet > 0){
                $xml->writeElement("estabRetencion1", $Estab);
                $xml->writeElement("ptoEmiRetencion1", $PtoRet);
                if($Number ==False){
                   $xml->writeElement("secRetencion1", "0");
                }else{
                   $xml->writeElement("secRetencion1",  bcdiv($SecRet, '9', 0)); 
                }
                $xml->writeElement("autRetencion1", $AutRet);
                $xml->writeElement("fechaEmiRet1", $FechaRet->format('d/m/Y'));
             }
             // // 'Aqui colocamos si existe una nota de credito o debito
           }
             $tipCom = $value["TipoComprobante"];
             if($tipCom == 4 || $tipCom == 5 || $tipCom==41)
             {
             	 $xml->writeElement("docModificado",  bcdiv($value["DocModificado"], '9', 2)); 
                 $xml->writeElement("estabModificado", $value["EstabModificado"]);
                 $xml->writeElement("ptoEmiModificado", $value["PtoEmiModificado"]);
                 $xml->writeElement("secModificado", $value["SecModificado"]);
                 $xml->writeElement("autModificado", $value["AutModificado"]);
             }
               $xml->endElement();//cierra detalleCompras

     
    }
$xml->endElement();//cierra Compras
}else
{

$xml->endElement();//cierra Compras
}

// VENTAS

 $Generar_Ventas = False;
  if (date('Y-m-d',strtotime($FechaInicial)) < date('Y-m-d',strtotime("01/01/2016")))
  	{
  		$Generar_Ventas = True;
  	}
  $sql = "SELECT Autorizacion
       FROM Facturas 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       AND Fecha Between '".$FechaInicial."' AND  '".$FechaFin."' 
       AND LEN(Autorizacion) < 13 
       ORDER BY Autorizacion ";


     $VENTAS = $this->modelo->traer_datos($sql);
      // SelectAdodc AdoAir, sSQL
     if(count($VENTAS)>0)
     {
     	 $Generar_Ventas = True;
     	 $TipoDeFactura = "F";
     }
     else
     {
     	$TipoDeFactura = "E";
     }
 // 'MsgBox TipoDeFactura & vbCrLf & Generar_Ventas
 // 'Generar_Ventas = True
  if($Generar_Ventas) 
  {

  	if(count($parametros['ventas'])>0)
  	{

  	    $xml->startElement("ventas");
  	    foreach ($parametros['ventas'] as $key => $value) {
  	    	$Contador = $value["NumeroComprobantesV"];
  	    	if($Contador <=0)
  	    	{
  	    		$Contador = 1;
  	    	}
  	    	$Factura_No = $Contador;
  	    	$CodigoCliente = $value["RUC_CI"];
  	    	$Estab = "000";
            $PtoRet = "000";
            $FechaRet = $FechaFin;
            $AutRet = "0000000000";
            $TipoDoc = "06";
            switch ($value["TB"]) {
            	case 'R':
            		$TipoDoc ="04";
            		break;
            	case 'C':
            		$TipoDoc ="05";
            		break;
            	case 'P':
            		$TipoDoc ="06";
            		break;
            	case 'F':
            		$TipoDoc ="07";
            		break;
            }
            if($value["RUC_CI"]=="9999999999999")
            {
            	$TipoDoc = "07";
            }
             // 'Genero el talón de Ventas
            // 'If .Fields("RUC_CI") = Ninguno Then MsgBox .Fields("RUC_CI")
             $xml->startElement("detalleVentas");
             $xml->writeElement("tpIdCliente",$TipoDoc);
             $xml->writeElement("idCliente", $value["RUC_CI"]);
             if($TipoDoc<>"07")
             {
             	$xml->writeElement("parteRelVtas", "NO");
             }
             if($TipoDoc == "06" && date('Y-m-d',strtotime($FechaInicial)) >= date('Y-m-d',strtotime("01/05/2016")))
             {
             	$xml->writeElement("tipoCliente", "01");
                $xml->writeElement("denoCli", $value["Razon_Social"]);
             }
                $xml->writeElement("tipoComprobante",$value["TipoComprobante"]);
                $xml->writeElement("tipoEmision", $TipoDeFactura);
                $xml->writeElement("numeroComprobantes",$Contador);
                $xml->writeElement("baseNoGraIva",   bcdiv(0, '9', 2));         //' hay que aumentar
                $xml->writeElement("baseImponible",bcdiv($value["BaseImponibleV"], '1', 2));
                $xml->writeElement("baseImpGrav", bcdiv($value["BaseImpGravV"], '1', 2));
                $xml->writeElement("montoIva", bcdiv($value["MontoIvaV"], '1', 2));
                $xml->writeElement("montoIce", "0.00");
                $xml->writeElement("valorRetIva", bcdiv($value["ValorRetBienesV"] + $value["ValorRetServiciosV"], '1', 2));
                 // 'Asigno a variables los campos
                 // 'Genero el AIR
                $Cargar_Air_AT = True;
                if($value["TipoComprobante"] == 4 || $value["TipoComprobante"] == 41)
                {
                	$Cargar_Air_AT = False; 
                }
                $SubTotal = 0;
                if($Cargar_Air_AT)
                {
                  $sql = "SELECT IdProv,SUM(ValRet) As TValRet 
                  FROM Trans_Air 
                  WHERE Fecha Between '".$FechaInicial."' AND '".$FechaFin."'  
                  AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
                  if($ConSucursal)
                  {
                  	if(strlen($No_ATS) > 3)
                  		{
                  		   $sql.="AND Item NOT IN (".$No_ATS.") ";
                  		}
                  }else
                  {
                  	 $sql .= "AND Item = '" .$_SESSION['INGRESO']['item']. "' ";
                  }
                   //    $sql.="AND EstabFactura = '" & Codigo1 & "' 
                   // ''  AND PuntoEmiFactura = '" & Codigo2 & "' ";
                  $sql.="AND T = '".G_NORMAL."'
                  AND Tipo_Trans = 'V' AND RUC_CI = '".$CodigoCliente."'
                  GROUP BY IdProv ";
                  $AdoAir = $this->modelo->traer_datos($sql);
                  if(count($AdoAir)>0)
                  {
                  	$SubTotal = $SubTotal + $AdoAir[0]["TValRet"];
                  }
                }
                 $xml->writeElement("valorRetRenta", bcdiv($SubTotal, '9', 2));
                 if(date('Y-m-d',strtotime($FechaInicial)) >= date('Y-m-d',strtotime("01/06/2016")))
                {
                 	for ($i=0; $i < 8; $i++) { 
                 		$Tipo_Pago1[$i] = "";
                 	}
                 	     $sql =  "SELECT Tipo_Pago 
                         FROM Facturas 
                         WHERE Fecha Between '".$FechaInicial."' AND '".$FechaFin."'  
                         AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
                         AND LEN(Tipo_Pago) > 1 ";
                         if ($ConSucursal) {
                         	if(strlen($No_ATS)>3)
                         	{
                         		$sql.="AND Item NOT IN (".$No_ATS.") ";
                         	}                        	
                         }else
                         {
                         	$sql .="AND Item = '" .$_SESSION['INGRESO']['item']. "' ";
                         }
                         $sql.="AND RUC_CI = '".$CodigoCliente."'
                         GROUP BY Tipo_Pago ORDER BY Tipo_Pago ";
                        $AdoAirt = $this->modelo->traer_datos($sql);
                        if(count($AdoAirt)>0)
                        {
                        	foreach ($AdoAirt as $key => $value1) {
                        		$Tipo_Pago = $value1["Tipo_Pago"];
                        		for ($i=0; $i < 8; $i++) { 
                        			if($Tipo_Pago <> $Tipo_Pago1[$i])
                        			{
                        				$Tipo_Pago1[$i] = $Tipo_Pago;
                        				break;
                        			}                        			
                        		}                        		
                        	}
                        }
                        // 'Ventas sin modulos de Facturacion
                        $sql = "SELECT Tipo_Pago 
                        FROM Trans_Ventas
                        WHERE Fecha Between '".$FechaInicial."' AND '".$FechaFin."' 
                        AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
                        AND LEN(Tipo_Pago) > 1 ";
                        if ($ConSucursal) {
                         	if(strlen($No_ATS)>3)
                         	{
                         		$sql.="AND Item NOT IN (".$No_ATS.") ";
                         	}                        	
                         }else
                         {
                         	$sql .="AND Item = '" .$_SESSION['INGRESO']['item']. "' ";
                         }
                         $sql.=" AND RUC_CI = '".$CodigoCliente."'  GROUP BY Tipo_Pago ORDER BY Tipo_Pago ";
                        $AdoAirtt = $this->modelo->traer_datos($sql);
                        if(count($AdoAirtt)>0)
                        {
                        	foreach ($AdoAirtt as $key => $value2) {
                        		$Tipo_Pago = $value2["Tipo_Pago"];
                        		for ($i=0; $i < 8; $i++) { 
                        			if($Tipo_Pago <> $Tipo_Pago1[$i])
                        			{
                        				$Tipo_Pago1[$i] = $Tipo_Pago;
                        			}                        			
                        		}                        		
                        	}
                        }
                        if($value["TipoComprobante"] == 4 || $value["TipoComprobante"] == 41)
                        {
                        	$Tipo_Pago ="";
                        }else
                        {
                        	
                        	 $Tipo_Pago = "";
                        	for ($i=0; $i < 8 ; $i++) { 
                        		 if ($Tipo_Pago1[$i] <> "")
                        		 {
                        		 	$Tipo_Pago = $Tipo_Pago."".$Tipo_Pago1[$i].",";
                        		 }
                        	}
                        	if ($Tipo_Pago == "") {
                        		$Tipo_Pago = '01,';
                        		$Tipo_Pago1[0]="01";
                        	}
                        }
                        if($Tipo_Pago <> "" )
                        {
                        	 $xml->startElement("formasDePago");
                        	 for ($i=0; $i < 8; $i++) { 
                        	 	if($Tipo_Pago1[$i] <> "")
                        	 	{
                        	 		$xml->writeElement("formaPago", $Tipo_Pago1[$i]);
                        	 	}
                        	 }
                        	 $xml->endElement(); //cierra forma de pago
                        }                  
                }
                   $xml->endElement(); //cierra "detalleVentas"
              
  	    }
  	     $xml->endElement(); //cierra "ventas")
  	}
  	 // 'Resumen Total de Ventas por Establecimientos
  if (($total_ventas_estab + $tota_nc_estab) > 0){
      $xml->startElement("ventasEstablecimiento");
     for ($KE=1; $KE <1000 ; $KE++) { 
     	 if($Total_Ventas[$KE] + $Total_NC[$KE] > 0){         
         $xml->startElement("ventaEst");
         $xml->writeElement("codEstab", str_pad($KE, 3, '0', STR_PAD_LEFT));
         $xml->writeElement("ventasEstab", bcdiv($Total_Ventas[$KE] - $Total_NC[$KE],'9',2));
         $xml->endElement(); //cierra ventaest
        }
     }
       $xml->endElement(); //cierra "ventasEstablecimiento")
   }
}
        
// 'EXPORTACIONES
if(count($parametros['exportaciones'])>0)
{
	$xml->startElement('exportaciones');
	foreach ($parametros['exportaciones'] as $key => $value) 
	{
		 $TipoDoc = "1";
		 if($value['TD'] == "R" || $value['TD'] == "C")
		 {
		 	$Tipo_Doc= "2";
		 }
         // 'Genero el talón de Exportaciones
          $xml->startElement("detalleExportaciones");
          $xml->writeElement("exportacionDe", $value["ExportacionDe"]);
          $xml->writeElement("tipoComprobante",str_pad($value["TipoComprobante"], 3, '0', STR_PAD_LEFT));
          if($value["ExportacionDe"]==1)
          {
          	 $xml->writeElement("distAduanero", $value["DistAduanero"]);
             $xml->writeElement("anio", $value["Anio"]);
             $xml->writeElement("regimen", $value["Regimen"]);
             $xml->writeElement("correlativo", $value["Correlativo"]);
             $xml->writeElement("verificador", $value["Verificador"]);
             $xml->writeElement("docTransp", $value["NumeroDctoTransporte"]);
          }
         $xml->writeElement("fechaEmbarque", $value["FechaEmbarque"]);
         $xml->writeElement("fue", "000001");                                      // '*
         $xml->writeElement("valorFOB", bcdiv($value["ValorFOB"], "9",2));
         $xml->writeElement("valorFOBComprobante",$value["ValorFOBComprobante"]);
         $xml->writeElement("establecimiento", $value["Establecimiento"]);
         $xml->writeElement("puntoEmision",$value["PuntoEmision"]);
         $xml->writeElement("secuencial", $value["Secuencial"]);
         $xml->writeElement("autorizacion", $value["Autorizacion"]);
         $xml->writeElement("fechaEmision", $value["FechaEmision"]->format('d/m/y'));
         $xml->endElement(); // cierre "detalleExportaciones"
	}
	 $xml->endElement(); // cierre exportaciones
}

 // 'ANULADOS 
if(count($parametros['anulados'])>0)
{
	$xml->startElement("anulados");
	foreach ($parametros['anulados'] as $key => $value) {
		$xml->startElement("detalleAnulados");
		$xml->writeElement("tipoComprobante",str_pad($value["TipoComprobante"], 2, '0', STR_PAD_LEFT));
        $xml->writeElement("establecimiento", $value["Establecimiento"]);
        $xml->writeElement("puntoEmision", $value["PuntoEmision"]);
        $xml->writeElement("secuencialInicio", $value["Secuencial1"]);
        $xml->writeElement("secuencialFin", $value["Secuencial2"]);
        $xml->writeElement("autorizacion", $value["Autorizacion"]);
        $xml->endElement(); 
         // Print #NumFile, CerrarXML("detalleAnulados")
	}
	 $xml->endElement(); //cerrar  "anulados"
}   
 $xml->endElement(); //cerrar  iva
// echo "../vista/TEMP/mio.xml";//

return 1;
 
	}

  function Carga_Paginas_AT($FechaIni,$FechaFin,$FechaMit)
  {
    // print_r($FechaIni);die();
    $pagina2 = False;
    $this->pdf->AddPage();
    $src=dirname(__DIR__,3)."/img/logotipos/SRI.jpg";
    $this->pdf->Image($src,10,3,35,20);      
    $this->pdf->SetFont('Arial','',14);
    $this->pdf->SetTextColor(7, 89, 147);
    $this->pdf->SetXY(65,10);
    $this->pdf->Cell(0,3,'TALON RESUMEN DE ANEXO TRANSACCIONAL',0,0,'C');
    $this->pdf->SetXY(65,15);
    $this->pdf->Cell(0,3,'SERVICIO DE RENTAS INTERNAS',0,0,'C');
    $this->pdf->SetXY(65,20);
    $this->pdf->Cell(0,3,$_SESSION['INGRESO']['Nombre_Comercial'],0,0,'C');
    $this->pdf->SetXY(65,25);
    $this->pdf->Cell(0,3,'RUC:'.$_SESSION['INGRESO']['RUC'],0,0,'C');


    $this->pdf->SetXY(4,35);   
    $this->pdf->SetFont('Arial','',10);
    $this->pdf->SetTextColor(0, 0, 0);
    $this->pdf->MultiCell(0,5,mb_convert_encoding('Certifico que la información contenida en el medio magnético adjunto al presente Anexo Transaccional para el período '.date('m',strtotime($FechaIni)).'-'.date('Y',strtotime($FechaIni)).' es fiel reflejo del siguiente reporte:', 'ISO-8859-1','UTF-8'),0,'L');


    // 'COMPRAS
       $compras =$this->modelo->Vista_Compras($FechaIni,$FechaFin);
        $tablacompras=array();
          if(count($compras) >0){
             $this->pdf->SetXY(5,$this->pdf->GetY()+5);
             $this->pdf->SetFont('Arial','',10);
             $this->pdf->SetTextColor(178, 30, 64);
             $this->pdf->Cell(0,3,"COMPRAS",0,0,'C');
             $this->pdf->SetXY(5,$this->pdf->GetY()+5);
             $Real1 = 0; $Real2 = 0; $Real3 = 0; $Real4 = 0; $Real5 = 0; $Total = 0;
             $count = 1;
             $tablacompras[0]['medidas']=array(10,105,20,20,20,20);
             $tablacompras[0]['alineado']=array('L','L','L','L','L','L');
             $tablacompras[0]['datos']=array(mb_convert_encoding("Cód", 'ISO-8859-1','UTF-8'),mb_convert_encoding("Transacción", 'ISO-8859-1','UTF-8'),"No. Reg.","BI tarifa 0%","BI tarifa 12%","Valor IVA");           
          foreach ($compras as $key => $value) {
             $tablacompras[$count]['medidas']=$tablacompras[0]['medidas'];
             $tablacompras[$count]['alineado']=$tablacompras[0]['alineado'];
             $tablacompras[$count]['datos']=array($value["TipoComprobante"],mb_convert_encoding($value["Descripcion"], 'ISO-8859-1','UTF-8'),$value["Cant"],$value["BI"],$value["BIG"],$value["MI"]);
             if($value["TipoComprobante"] == 4)
             {
              $Real1 = $Real1 - $value["BI"];
              $Real2 = $Real2 - $value["BIG"];
              $Real3 = $Real3 - $value["MI"];
             }else{
              $Real1 = $Real1 + $value["BI"];
              $Real2 = $Real2 + $value["BIG"];
              $Real3 = $Real3 + $value["MI"] ; 
            }
              $count = $count+1;
          }
             $tablacompras[$count]['medidas']=array(140,20,20,20);
             $tablacompras[$count]['alineado']=array('L','L','L','L');
             $tablacompras[$count]['datos']=array("TOTAL",$Real1,$Real2,$Real3);

           $totales = count($tablacompras);    
        foreach ($tablacompras as $key => $value){
          $this->pdf->SetFont('Arial','',8);
          if(isset($value['borde']) && $value['borde']!='0')
          {
            $borde=$value['borde'];
          }else
          {
            $borde =0;
          }
          $this->pdf->SetWidths($value['medidas']);
         $this->pdf->SetAligns($value['alineado']);
         $arr= $value['datos'];
         if($key==0){
          $this->pdf->Row($arr,4,'B','',null,true); 
          }else if ($key==$totales-1) {
            $this->pdf->SetXY(5,$this->pdf->GetY());
            $this->pdf->Row($arr,4,'T','',null,true); 
          }
          else{          
          $this->pdf->Row($arr,4,'','',null,true); 
         }
        }

          
         $this->pdf->SetXY(5,$this->pdf->GetY()+5);
         $this->pdf->SetFont('Arial','',10);
         $this->pdf->Cell(0,3,mb_convert_encoding("Se verificará con los casilleros asignados en la declaración de IVA (form.104) de acuerdo al siguiente esquema:", 'ISO-8859-1','UTF-8'),0,0,'L');
         $this->pdf->SetXY(5,$this->pdf->GetY()+3);
         $tablacompras1[0]['medidas']=array(115,30,30,30);
         $tablacompras1[0]['alineado']=array('L','L','L','L');
         $tablacompras1[0]['datos']=array(mb_convert_encoding("<u>Sustento Crédito Tributario", 'ISO-8859-1','UTF-8'),mb_convert_encoding("Casilleros", 'ISO-8859-1','UTF-8'),"Base","Impuesto"); 
          $compras1 = $this->modelo-> transacciones_compras($FechaIni,$FechaFin);
          $Real1 = 0;    // Base Imp 12%
          $Real2 = 0;    // IVA 12%
          $Real3 = 0;    // 0%
          $Real4 = 0;    // Base Imp 12%
          $Real5 = 0;    // IVA 12%
          $Real6 = 0;    //  0%
          foreach ($compras1 as $key => $value) {
            if($value['CodSustento'] == "01" || $value['CodSustento'] == "03" || $value['CodSustento'] == "06" )
            {
            $Real1 = $Real1 + $value["BIG"];
            $Real2 = $Real2 + $value["MI"];
            $Real3 = $Real3 + $value["BI"];

          }else{
            $Real4 = $Real4 + $value["BIG"];
            $Real5 = $Real5 + $value["MI"];
            $Real6 = $Real6 + $value["BI"];
            }
          }
           $tablacompras1[1]['medidas']=$tablacompras1[0]['medidas'];
           $tablacompras1[1]['alineado']= $tablacompras1[0]['alineado'];
           $tablacompras1[1]['datos']=array(mb_convert_encoding("Compras Netas (12/14)% -Sustento de crédito tributario corresponde a los códigos 1,3 y 6", 'ISO-8859-1','UTF-8'),"631+633+635",$Real1,$Real2);

           $tablacompras1[2]['medidas']=$tablacompras1[0]['medidas'];
           $tablacompras1[2]['alineado']= $tablacompras1[0]['alineado'];
           $tablacompras1[2]['datos']=array(mb_convert_encoding("Compras Netas 0% -Sustento de crédito tributario corresponde a los códigos 1,3 y 6", 'ISO-8859-1','UTF-8'),"601+603+605",$Real3,""); 

           $tablacompras1[3]['medidas']=$tablacompras1[0]['medidas'];
           $tablacompras1[3]['alineado']= $tablacompras1[0]['alineado'];
           $tablacompras1[3]['datos']=array(mb_convert_encoding("Pago por Reembolso de gastos (12/14)% -Corresponde a los códigos 8 y 9", 'ISO-8859-1','UTF-8'),"637",$Real4,$Real5); 

           $tablacompras1[4]['medidas']=$tablacompras1[0]['medidas'];
           $tablacompras1[4]['alineado']= $tablacompras1[0]['alineado'];
           $tablacompras1[4]['datos']=array(mb_convert_encoding( "Pago por Reembolso de gastos 0% -Corresponde a los códigos 8 y 9", 'ISO-8859-1','UTF-8'),"607",$Real6,"");   
         
          foreach ($tablacompras1 as $key => $value){
          $this->pdf->SetFont('Arial','',8);
          
          $this->pdf->SetWidths($value['medidas']);
         $this->pdf->SetAligns($value['alineado']);
         $arr= $value['datos'];                
         $this->pdf->Row($arr,4,'','',null,true);          
        }    
        }  


    // 'VENTAS 

       $ventas =$this->modelo->Vista_Ventas($FechaIni,$FechaFin);
       $tablaVentas = array();
       if(count($ventas) >0){
        $this->pdf->SetXY(5,$this->pdf->GetY()+5);
             $this->pdf->SetFont('Arial','',10);
             $this->pdf->SetTextColor(178, 30, 64);
             $this->pdf->Cell(0,3,"VENTAS",0,0,'C');
             $this->pdf->SetXY(5,$this->pdf->GetY()+5);
             $Real1 = 0; $Real2 = 0; $Real3 = 0; $Real4 = 0; $Real5 = 0; $Total = 0;
             $count = 1;
             $tablaVentas[0]['medidas']=array(10,105,20,20,20,20);
             $tablaVentas[0]['alineado']=array('L','L','L','L','L','L');
             $tablaVentas[0]['datos']=array(mb_convert_encoding("Cód", 'ISO-8859-1','UTF-8'),mb_convert_encoding("Transacción", 'ISO-8859-1','UTF-8'),"No. Reg.","BI tarifa 0%","BI tarifa 12%","Valor IVA");           
          foreach ($ventas as $key => $value) {
             $tablaVentas[$count]['medidas']=$tablaVentas[0]['medidas'];
             $tablaVentas[$count]['alineado']=$tablaVentas[0]['alineado'];
             $tablaVentas[$count]['datos']=array($value["TipoComprobante"],mb_convert_encoding($value["Descripcion"], 'ISO-8859-1','UTF-8'),$value["Cant"],$value["BI"],$value["BIG"],$value["MI"]);
             if($value["TipoComprobante"] == 4)
             {
              $Real1 = $Real1 - $value["BI"];
              $Real2 = $Real2 - $value["BIG"];
              $Real3 = $Real3 - $value["MI"];
             }else{
              $Real1 = $Real1 + $value["BI"];
              $Real2 = $Real2 + $value["BIG"];
              $Real3 = $Real3 + $value["MI"] ; 
            }
              $count = $count+1;
          }
             $tablaVentas[$count]['medidas']=array(140,20,20,20);
             $tablaVentas[$count]['alineado']=array('L','L','L','L');
             $tablaVentas[$count]['datos']=array("TOTAL",$Real1,$Real2,$Real3);

           $totales = count($tablaVentas);    
        foreach ($tablaVentas as $key => $value){
          $this->pdf->SetFont('Arial','',8);
          if(isset($value['borde']) && $value['borde']!='0')
          {
            $borde=$value['borde'];
          }else
          {
            $borde =0;
          }
          $this->pdf->SetWidths($value['medidas']);
         $this->pdf->SetAligns($value['alineado']);
         $arr= $value['datos'];
         if($key==0){
          $this->pdf->Row($arr,4,'B','',null,true); 
          }else if ($key==$totales-1) {
            $this->pdf->SetXY(5,$this->pdf->GetY());
            $this->pdf->Row($arr,4,'T','',null,true); 
          }
          else{          
          $this->pdf->Row($arr,4,'','',null,true); 
         }
        }

          
         $this->pdf->SetXY(5,$this->pdf->GetY()+5);
         $this->pdf->SetFont('Arial','',10);
         $this->pdf->Cell(0,3,mb_convert_encoding("Se verificará con los casilleros asignados en la declaración de IVA (form.104) de acuerdo al siguiente esquema:", 'ISO-8859-1','UTF-8'),0,0,'L');
         $this->pdf->SetXY(5,$this->pdf->GetY()+3);
         $tablaVentas1[0]['medidas']=array(115,30,30,30);
         $tablaVentas1[0]['alineado']=array('L','L','L','L');
         $tablaVentas1[0]['datos']=array(mb_convert_encoding("<u>Sustento Crédito Tributario", 'ISO-8859-1','UTF-8'),mb_convert_encoding("Casilleros", 'ISO-8859-1','UTF-8'),"Base","Impuesto"); 
          $ventas1 = $this->modelo->base_imponible($FechaIni,$FechaFin);
          $Real1 = 0;    // Base Imp 12%
          $Real2 = 0;    // IVA 12%
          $Real3 = 0;    // 0%
          $Real4 = 0;    // Base Imp 12%
          $Real5 = 0;    // IVA 12%
          $Real6 = 0;    //  0%
          foreach ($ventas1 as $key => $value) {
            $Real1 = $Real1 + $value["BIG"];
            $Real2 = $Real2 + $value["MI"];
            $Real3 = $Real3 + $value["BI"];
            $Real4 = $Real4 + $value["BIG"];
            $Real5 = $Real5 + $value["MI"];
            $Real6 = $Real6 + $value["BI"];
          }
           $tablaVentas1[1]['medidas']=$tablaVentas1[0]['medidas'];
           $tablaVentas1[1]['alineado']= $tablaVentas1[0]['alineado'];
           $tablaVentas1[1]['datos']=array(mb_convert_encoding("Ventas Netas Base Imponible 12%", 'ISO-8859-1','UTF-8'),"531+533+535+537",$Real1,$Real2);
           $tablaVentas1[2]['medidas']=$tablaVentas1[0]['medidas'];
           $tablaVentas1[2]['alineado']= $tablaVentas1[0]['alineado'];
           $tablaVentas1[2]['datos']=array(mb_convert_encoding("Ventas Netas Base Imponible 0%", 'ISO-8859-1','UTF-8'),"501+503+505+507",$Real3,""); 
           $tablaVentas1[3]['medidas']=$tablaVentas1[0]['medidas'];
           $tablaVentas1[3]['alineado']= $tablaVentas1[0]['alineado'];
           $tablaVentas1[3]['datos']=array(mb_convert_encoding("Ingresos por Reembolso de Gastos Tarifa 12%", 'ISO-8859-1','UTF-8'),"593",$Real4,$Real5); 
           $tablaVentas1[4]['medidas']=$tablaVentas1[0]['medidas'];
           $tablaVentas1[4]['alineado']= $tablaVentas1[0]['alineado'];
           $tablaVentas1[4]['datos']=array(mb_convert_encoding( "Ingresos por Reembolso de Gastos Tarifa0%", 'ISO-8859-1','UTF-8'),"509",$Real6,"");   
         
          foreach ($tablaVentas1 as $key => $value){
          $this->pdf->SetFont('Arial','',8);
          
          $this->pdf->SetWidths($value['medidas']);
         $this->pdf->SetAligns($value['alineado']);
         $arr= $value['datos'];                
         $this->pdf->Row($arr,4,'','',null,true);          
        }    
        }           


    // 'ANULADOS
          $this->pdf->SetXY(5,$this->pdf->GetY()+5);
           $anulados =$this->modelo->Vista_Anulados($FechaIni,$FechaFin);
           $total_A = 0;
             $this->pdf->SetFont('Arial','',10);
             $this->pdf->SetTextColor(178, 30, 64);
             $this->pdf->Cell(0,3,"ANULADOS",0,0,'C');
             $this->pdf->SetXY(5,$this->pdf->GetY()+5);
             if (count($anulados)>0)
             {
              foreach ($anulados as $key => $value) {
                $total_A =$value['Cantidad'];
              } 
             }
             $tablaAnulados[0]['medidas']=array(150,25,25);
             $tablaAnulados[0]['alineado']=array('L','L','R');
             $tablaAnulados[0]['datos']=array(mb_convert_encoding("Total de Comprobantes Anulados en el período informado(no incluye los dados de baja)", 'ISO-8859-1','UTF-8'), "SUMATORIA",$total_A);
    

        foreach ($tablaAnulados as $key => $value){
          $this->pdf->SetFont('Arial','',8);          
          $this->pdf->SetWidths($value['medidas']);
         $this->pdf->SetAligns($value['alineado']);
         $arr= $value['datos'];
         $this->pdf->Row($arr,12,$borde=0,'',null,true);  
        }           

    // 'RETENCION IMPUESTO RENTA
         $retencion_in =$this->modelo->Vista_Retencion_Impuesto_Renta($FechaIni,$FechaFin,$FechaMit);
         if(count($retencion_in)>0)
         {
            $this->pdf->SetXY(5,$this->pdf->GetY()+5);
             $this->pdf->SetFont('Arial','',10);
             $this->pdf->SetTextColor(178, 30, 64);
             $this->pdf->Cell(0,3,"RESUMEN DE RETENCIONES - AGENTE DE RETENCION",0,0,'C');
             $this->pdf->SetXY(5,$this->pdf->GetY()+5);
              $count = 1;
              $Real1 = 0; $Real2 = 0; $Real3 = 0; $Real4 = 0; $Real5 = 0; $Total = 0; 
             $tabla_RE_FU[0]['medidas']=array(20,120,20,20,20);
             $tabla_RE_FU[0]['alineado']=array('L','L','L','L','L','L');
             $tabla_RE_FU[0]['datos']=array(mb_convert_encoding("Cod", 'ISO-8859-1','UTF-8'),mb_convert_encoding("Concepto de Retención en la Fuente de Impuesto a la Renta", 'ISO-8859-1','UTF-8'),"No. Reg.","BaseI","Valor");
            foreach ($retencion_in as $key => $value) {
               $tabla_RE_FU[$count]['medidas']=array(20,120,20,20,20);
               $tabla_RE_FU[$count]['alineado']= $tabla_RE_FU[0]['alineado'];
               $tabla_RE_FU[$count]['datos']=array($value["CodRet"],mb_convert_encoding($value["Concepto"], 'ISO-8859-1','UTF-8'),$value['Cant'],$value["BI"],$value["VR"]);
                    $Total = $Total + $value["VR"];
                    $Real5 = $Real5 + $value["BI"]; 
               $count = $count +1;  
            }
             $tabla_RE_FU[$count]['medidas']=array(100,45,25,20,10);
             $tabla_RE_FU[$count]['alineado']=array('R','R','R','R','R');
             $tabla_RE_FU[$count]['datos']=array("TOTAL","",$Real5,$Total,'');
             $totales = count($tabla_RE_FU);  
             foreach ($tabla_RE_FU as $key => $value){
               $this->pdf->SetXY(5,$this->pdf->GetY());            
                $this->pdf->SetFont('Arial','',8); 
                $this->pdf->SetWidths($value['medidas']);
                $this->pdf->SetAligns($value['alineado']);
                $arr= $value['datos'];
                if($key==0)
                {
                   $this->pdf->Row($arr,4,'B','',null,true);
                }else if ($key==$totales-1) 
                {
                  $this->pdf->Row($arr,4,'T','',null,true); 
                }else
                {       
                  $this->pdf->Row($arr,4,'','',null,true); 
                }
            }      
          }
  
    // 'RETENCION FUENTE IVA
          $retencion_fu =$this->modelo->Vista_Retencion_Fuente_Iva($FechaIni,$FechaFin);
          // print_r($retencion_fu);die();
          $tabla_RE_FU = array();
          if(count($retencion_fu['compras'])>0)
          {

             $this->pdf->SetXY(5,$this->pdf->GetY()+5);
             $count = 1;
              $Real1 = 0; $Real2 = 0; $Real3 = 0; $Real4 = 0; $Real5 = 0; $Total = 0; 
             $tabla_RE_FU[0]['medidas']=array(40,80,25,15,20,15);
             $tabla_RE_FU[0]['alineado']=array('L','L','L','L','L','L');
             $tabla_RE_FU[0]['datos']=array(mb_convert_encoding("Operación: COMPRAS", 'ISO-8859-1','UTF-8'),mb_convert_encoding("Concepto de Retención en la Fuente del IVA", 'ISO-8859-1','UTF-8'),"No. Reg.","BaseI","% Ret.","Valor");
            foreach ($retencion_fu['compras'] as $key => $value) {
               $tabla_RE_FU[$count]['medidas']=array(20,100,25,15,20,15);
               $tabla_RE_FU[$count]['alineado']=$tabla_RE_FU[0]['alineado'];
               switch ($value["PorRetBienes"]) {
                 case '1':
                    $tabla_RE_FU[$count]['datos']=array("",mb_convert_encoding("Retención en Bienes", 'ISO-8859-1','UTF-8'),$value['Cant'],$value["BIB"],"30%",$value["VRB"]);
                    $Total = $Total + $value["VRB"];
                    $Real5 = $Real5 + $value["BIB"];
                   break;
                 
                 case '3':
                    $tabla_RE_FU[$count]['datos']=array("",mb_convert_encoding("Retención en Bienes", 'ISO-8859-1','UTF-8'),$value['Cant'],$value["BIB"],"100%",$value["VRB"]); 
                    $Total = $Total + $value["VRB"];
                    $Real5 = $Real5 + $value["BIB"];            
                   break;
               }

               switch ($value["PorRetServicios"]) {
                 case '2':
                    $tabla_RE_FU[$count]['datos']=array("",mb_convert_encoding("Retención en Servicios", 'ISO-8859-1','UTF-8'),$value['Cant'],$value["BIS"],"70%",$value["VRS"]);
                    $Total = $Total + $value["VRS"];
                    $Real5 = $Real5 + $value["BIS"];
                   break;
                 
                 case '4':
                    $tabla_RE_FU[$count]['datos']=array("",mb_convert_encoding("Retención en Servicios", 'ISO-8859-1','UTF-8'),$value['Cant'],$value["BIS"],"70%",$value["VRS"]);
                    $Total = $Total + $value["VRS"];
                    $Real5 = $Real5 + $value["BIS"];
                   break;
                 case '3':
                    $tabla_RE_FU[$count]['datos']=array("",mb_convert_encoding("Retención en Servicios", 'ISO-8859-1','UTF-8'),$value['Cant'],$value["BIS"],"100%",$value["VRS"]);  
                    $Total = $Total + $value["VRS"];   
                    $Real5 = $Real5 + $value["BIS"];      
                   break;
               }
              
               $count = $count +1;  
            }
             $tabla_RE_FU[$count]['medidas']=array(100,55,35);
             $tabla_RE_FU[$count]['alineado']=array('R','R','R');
             $tabla_RE_FU[$count]['datos']=array("TOTAL",$Total,$Real5);
             $totales = count($tabla_RE_FU); 
             // print_r($tabla_RE_FU);die(); 
             foreach ($tabla_RE_FU as $key => $value){
                $this->pdf->SetFont('Arial','',8); 
                $this->pdf->SetWidths($value['medidas']);
                $this->pdf->SetAligns($value['alineado']);
                $arr= $value['datos'];
                if($key==0)
                {
                   $this->pdf->Row($arr,4,'B','',null,true);
                }else if ($key==$totales-1) 
                {
                  $this->pdf->Row($arr,4,'T','',null,true); 
                }else
                {       
                  $this->pdf->Row($arr,4,'','',null,true); 
                }
            }      
          }

          if(count($retencion_fu['ventas'])>0)
          {

            $this->pdf->SetXY(5,$this->pdf->GetY()+5);
             $count = 1;
              $Real1 = 0; $Real2 = 0; $Real3 = 0; $Real4 = 0; $Real5 = 0; $Total = 0; 
             $tabla_RE_FU[0]['medidas']=array(40,80,25,15,20,15);
             $tabla_RE_FU[0]['alineado']=array('L','L','L','L','L','L');
             $tabla_RE_FU[0]['datos']=array(mb_convert_encoding("Operación: VENTAS", 'ISO-8859-1','UTF-8'),mb_convert_encoding("Resumen de Retenciones que le efectuaron en el Periodo", 'ISO-8859-1','UTF-8'),"No. Reg.","BaseI","% Ret.","Valor");
            foreach ($retencion_fu['ventas'] as $key => $value) {
               $tabla_RE_FU[$count]['medidas']=array(20,100,25,15,20,15);
               $tabla_RE_FU[$count]['alineado']=$tablaHTML[0]['alineado'];
               switch ($value["PorRetBienes"]) {
                 case '1':
                    $tabla_RE_FU[$count]['datos']=array("",mb_convert_encoding("Retención en Bienes", 'ISO-8859-1','UTF-8'),$value['Cant'],$value["BIB"],"30%",$value["VRB"]);
                    $Total = $Total + $value["VRB"];
                    $Real5 = $Real5 + $value["BIB"];
                   break;
                 
                 case '3':
                    $tabla_RE_FU[$count]['datos']=array("",mb_convert_encoding("Retención en Bienes", 'ISO-8859-1','UTF-8'),$value['Cant'],$value["BIB"],"100%",$value["VRB"]); 
                    $Total = $Total + $value["VRB"];
                    $Real5 = $Real5 + $value["BIB"];            
                   break;
               }

               switch ($value["PorRetServicios"]) {
                 case '2':
                    $tabla_RE_FU[$count]['datos']=array("",mb_convert_encoding("Retención en Servicios", 'ISO-8859-1','UTF-8'),$value['Cant'],$value["BIS"],"70%",$value["VRS"]);
                    $Total = $Total + $value["VRS"];
                    $Real5 = $Real5 + $value["BIS"];
                   break;
                 
                 case '4':
                    $tabla_RE_FU[$count]['datos']=array("",mb_convert_encoding("Retención en Servicios", 'ISO-8859-1','UTF-8'),$value['Cant'],$value["BIS"],"70%",$value["VRS"]);
                    $Total = $Total + $value["VRS"];
                    $Real5 = $Real5 + $value["BIS"];
                   break;
                 case '3':
                    $tabla_RE_FU[$count]['datos']=array("",mb_convert_encoding("Retención en Servicios", 'ISO-8859-1','UTF-8'),$value['Cant'],$value["BIS"],"100%",$value["VRS"]);  
                    $Total = $Total + $value["VRS"];   
                    $Real5 = $Real5 + $value["BIS"];      
                   break;
               }
              
               $count = $count +1;  
            }
             $tabla_RE_FU[$count]['medidas']=array(100,55,35);
             $tabla_RE_FU[$count]['alineado']=array('R','R','R');
             $tabla_RE_FU[$count]['datos']=array("TOTAL",$Total,$Real5);
             $totales = count($tabla_RE_FU);  
             foreach ($tabla_RE_FU as $key => $value){
                $this->pdf->SetFont('Arial','',8); 
                $this->pdf->SetWidths($value['medidas']);
                $this->pdf->SetAligns($value['alineado']);
                $arr= $value['datos'];
                if($key==0)
                {
                   $this->pdf->Row($arr,4,'B','',null,true);
                }else if ($key==$totales-1) 
                {
                  $this->pdf->Row($arr,4,'T','',null,true); 
                }else
                {       
                  $this->pdf->Row($arr,4,'','',null,true); 
                }
            }      
          }

           if(count($retencion_fu['retencion'])>0)
          {

             $this->pdf->SetXY(5,$this->pdf->GetY()+5);
             $this->pdf->SetFont('Arial','',10);
             $this->pdf->SetTextColor(178, 30, 64);
             $this->pdf->Cell(0,3,"RESUMEN DE RETENCIONES EN LA FUENTE QUE LE EFECTUARON",0,0,'C');
             $this->pdf->SetXY(5,$this->pdf->GetY()+5);
             $count = 1;
             $Real1 = 0; $Real2 = 0; $Real3 = 0; $Real4 = 0; $Real5 = 0; $Total = 0; 
             $tabla_RE_FU[0]['medidas']=array(15,100,20,20,20,20);
             $tabla_RE_FU[0]['alineado']=array('L','L','L','L','L','L');
             $tabla_RE_FU[0]['datos']=array(mb_convert_encoding("Codigo", 'ISO-8859-1','UTF-8'),mb_convert_encoding("Concepto de Retención efectuadas en Ventas", 'ISO-8859-1','UTF-8'),"No. Reg.","BaseI","% Ret.","Valor");
             foreach ($retencion_fu['retencion'] as $key => $value) 
             {
               $tabla_RE_FU[$count]['medidas']=array(20,100,20,20,20,20);
               $tabla_RE_FU[$count]['alineado']= $tabla_RE_FU[0]['alineado'];
               $tabla_RE_FU[$count]['datos']=array($value["CodRet"],mb_convert_encoding($value["Concepto"], 'ISO-8859-1','UTF-8'),$value['Cant'],$value["BI"],$value["Porcentaje"]."%",$value["VR"]);
               $Total = $Total + $value["VR"];
               $Real5 = $Real5 + $value["BI"]; 
               $count = $count +1;  
             }
             $tabla_RE_FU[$count]['medidas']=array(100,55,40);
             $tabla_RE_FU[$count]['alineado']=array('R','R','R');
             $tabla_RE_FU[$count]['datos']=array("TOTAL",$Real5,$Total);
             $totales = count($tabla_RE_FU);  
             foreach ($tabla_RE_FU as $key => $value)
             {
                $this->pdf->SetXY(5,$this->pdf->GetY());            
                $this->pdf->SetFont('Arial','',8); 
                $this->pdf->SetWidths($value['medidas']);
                $this->pdf->SetAligns($value['alineado']);
                $arr= $value['datos'];
                if($key==0)
                {
                   $this->pdf->Row($arr,4,'B','',null,true);
                }else if ($key==$totales-1) 
                {
                  $this->pdf->Row($arr,4,'T','',null,true); 
                }else
                {       
                  $this->pdf->Row($arr,4,'','',null,true); 
                }
                }      
          }

        $importaciones = $this->modelo->importaciones($FechaIni,$FechaFin);
        if(count($importaciones)>0)
        {
          $pagina2 = True;
        }

        $exportaciones = $this->modelo->exportaciones($FechaIni,$FechaFin);
        if(count($exportaciones)>0)
        {
          $pagina2 = True;
        }
        if($pagina2)
        {
          $this->pdf->AddPage();

          $tablaimpor=array();
          $count =1;
          if(count($compras) >0)
          {
             $this->pdf->SetFont('Arial','',10);
             $this->pdf->SetTextColor(178, 30, 64);
             $this->pdf->Cell(0,3,"I M P O R T A C I O N E S",0,0,'C');
             $this->pdf->SetXY(5,$this->pdf->GetY()+5);
             $Real1 = 0; $Real2 = 0; $Real3 = 0; $Real4 = 0; $Real5 = 0; $Total = 0;
             $count = 1;
             $tablaimpor[0]['medidas']=array(10,125,20,20,20);
             $tablaimpor[0]['alineado']=array('L','L','L','L','L');
             $tablaimpor[0]['datos']=array(mb_convert_encoding("Cód", 'ISO-8859-1','UTF-8'),mb_convert_encoding("Transacción", 'ISO-8859-1','UTF-8'),"No. Reg.","Valor CIF%","Valor IVA");           
             foreach ($importaciones as $key => $value) 
             {
                $tablaimpor[$count]['medidas']=$tablaimpor[0]['medidas'];
                $tablaimpor[$count]['alineado']=$tablaimpor[0]['alineado'];
                $tablaimpor[$count]['datos']=array($value["TipoComprobante"],mb_convert_encoding($value["Descripcion"], 'ISO-8859-1','UTF-8'),$value["Cant"],$value["VC"],$value["MI"]);
            
                $Real1 = $Real1 - $value["VC"];
                $Real2 = $Real2 - $value["MI"];
             
                $count = $count+1;
             }
             $tablaimpor[$count]['medidas']=array(180,20,20);
             $tablaimpor[$count]['alineado']=array('L','L','L');
             $tablaimpor[$count]['datos']=array("TOTAL",$Real1,$Real3);

             $totales = count($tablaimpor);    
             foreach ($tablaimpor as $key => $value)
             {
                $this->pdf->SetFont('Arial','',8);
                if(isset($value['borde']) && $value['borde']!='0')
                {
                  $borde=$value['borde'];
                }else
                {
                  $borde =0;
                }
                $this->pdf->SetWidths($value['medidas']);
                $this->pdf->SetAligns($value['alineado']);
                $arr= $value['datos'];
                if($key==0)
                {
                  $this->pdf->Row($arr,4,'B','',null,true); 
                }else if ($key==$totales-1) 
                {
                   $this->pdf->SetXY(5,$this->pdf->GetY());
                   $this->pdf->Row($arr,4,'T','',null,true); 
                }else
                {
                   $this->pdf->Row($arr,4,'','',null,true); 
                }
             }

             $impor1 = $this->modelo->Trans_Importaciones($FechaIni,$FechaFin);
             if(count($impor1)>0)
              {
                 $this->pdf->SetXY(5,$this->pdf->GetY()+5);
                 $tablacompras1[0]['medidas']=array(115,30,30,30);
                 $tablacompras1[0]['alineado']=array('L','L','L','L');
                 $tablacompras1[0]['datos']=array(mb_convert_encoding("Sustento Crédito Tributario", 'ISO-8859-1','UTF-8'),mb_convert_encoding("Casilleros", 'ISO-8859-1','UTF-8'),"Base","Impuesto"); 
                 $Real1 = 0;    // Base Imp 12%
                 $Real2 = 0;    // IVA 12%
                 $Real3 = 0;    // 0%
                 foreach ($impor1 as $key => $value) 
                 {
                   if($value['CodSustento'] == "01" || $value['CodSustento'] == "03" || $value['CodSustento'] == "06" )
                   {
                     $Real1 = $Real1 + $value["BIG"];
                     $Real2 = $Real2 + $value["MI"];
                     $Real3 = $Real3 + $value["BI"];
                   }
                 }
                 $tablacompras1[1]['medidas']=$tablacompras1[0]['medidas'];
                 $tablacompras1[1]['alineado']= $tablacompras1[0]['alineado'];
                 $tablacompras1[1]['datos']=array(mb_convert_encoding("Importaciones Netas 12% -Sustento de crédito tributario corresponde a los códigos 1,3 y 6", 'ISO-8859-1','UTF-8'),"639+641+643+645",$Real1,$Real2);

                 $tablacompras1[2]['medidas']=$tablacompras1[0]['medidas'];
                 $tablacompras1[2]['alineado']= $tablacompras1[0]['alineado'];
                 $tablacompras1[2]['datos']=array(mb_convert_encoding("Importaciones Netas 0% -Sustento de crédito tributario corresponde a los códigos 1,3 y 6", 'ISO-8859-1','UTF-8'),"609+611+613",$Real3,"");

                 foreach ($tablacompras1 as $key => $value)
                   {
                     $this->pdf->SetFont('Arial','',8);
                     $this->pdf->SetWidths($value['medidas']);
                     $this->pdf->SetAligns($value['alineado']);
                     $arr= $value['datos'];
                     $this->pdf->Row($arr,4,'','',null,true);
                   } 
              }
          }


          $this->pdf->SetXY(5,$this->pdf->GetY()+5);  
          $tablaexpor=array();
          $count =1;
          if(count($exportaciones) >0)
          {
             $this->pdf->SetFont('Arial','',10);
             $this->pdf->SetTextColor(178, 30, 64);
             $this->pdf->Cell(0,3,"E X P O R T A C I O N E S",0,0,'C');
             $this->pdf->SetXY(5,$this->pdf->GetY()+5);
             $Real1 = 0; $Real2 = 0; $Real3 = 0; $Real4 = 0; $Real5 = 0; $Total = 0;
             $count = 1;
             $tablaexpor[0]['medidas']=array(10,145,20,20);
             $tablaexpor[0]['alineado']=array('L','L','L','L','L');
             $tablaexpor[0]['datos']=array(mb_convert_encoding("Cód", 'ISO-8859-1','UTF-8'),mb_convert_encoding("Transacción", 'ISO-8859-1','UTF-8'),"No. Reg.","Valor FOB");           
             foreach ($importaciones as $key => $value) 
             {
                $tablaimpor[$count]['medidas']=$tablaexpor[0]['medidas'];
                $tablaimpor[$count]['alineado']=$tablaexpor[0]['alineado'];
                $tablaimpor[$count]['datos']=array($value["TipoComprobante"],mb_convert_encoding($value["Descripcion"], 'ISO-8859-1','UTF-8'),$value["Cant"],$value["VF"]);
            
                $Real1 = $Real1 + $value["VF"];             
                $count = $count+1;
             }
             $tablaexpor[$count]['medidas']=array(180,20,20);
             $tablaexpor[$count]['alineado']=array('L','L','L');
             $tablaexpor[$count]['datos']=array("TOTAL","",$Real1);

             $totales = count($tablaexpor);    
             foreach ($tablaexpor as $key => $value)
             {
                $this->pdf->SetFont('Arial','',8);
                if(isset($value['borde']) && $value['borde']!='0')
                {
                  $borde=$value['borde'];
                }else
                {
                  $borde =0;
                }
                $this->pdf->SetWidths($value['medidas']);
                $this->pdf->SetAligns($value['alineado']);
                $arr= $value['datos'];
                if($key==0)
                {
                  $this->pdf->Row($arr,4,'B','',null,true); 
                }else if ($key==$totales-1) 
                {
                   $this->pdf->SetXY(5,$this->pdf->GetY());
                   $this->pdf->Row($arr,4,'T','',null,true); 
                }else
                {
                   $this->pdf->Row($arr,4,'','',null,true); 
                }
             }

             $expor1 = $this->modelo->Trans_exportaciones($FechaIni,$FechaFin);
             if(count($expor1)>0)
              {
                 $this->pdf->SetXY(5,$this->pdf->GetY()+5);
                 $tablacompras1[0]['medidas']=array(115,30,30,30);
                 $tablacompras1[0]['alineado']=array('L','L','L','L');
                 $tablacompras1[0]['datos']=array(mb_convert_encoding("Sustento Crédito Tributario", 'ISO-8859-1','UTF-8'),mb_convert_encoding("Casilleros", 'ISO-8859-1','UTF-8'),"Base","Impuesto"); 
                 $Real1 = 0;    // Base Imp 12%
                 foreach ($expor1 as $key => $value) 
                 {                   
                     $Real1 = $Real1 + $value["VF"];                   
                 }
                 $tablacompras1[1]['medidas']=$tablacompras1[0]['medidas'];
                 $tablacompras1[1]['alineado']= $tablacompras1[0]['alineado'];
                 $tablacompras1[1]['datos']=array(mb_convert_encoding("Exportaciones Netas", 'ISO-8859-1','UTF-8'),"511+513",$Real1,"");


                 foreach ($tablacompras1 as $key => $value)
                   {
                     $this->pdf->SetFont('Arial','',8);
                     $this->pdf->SetWidths($value['medidas']);
                     $this->pdf->SetAligns($value['alineado']);
                     $arr= $value['datos'];
                     $this->pdf->Row($arr,4,'','',null,true);
                   } 
              }
          }     
        }

    $this->pdf->SetXY(5,$this->pdf->GetY()); 
    $this->pdf->MultiCell(0,5,mb_convert_encoding("Declaro que los datos contenidos en este anexo son verdaderos, por lo que asumo la responsabilidad correspondiente, de acuerdo a lo establecido en el Art.101 de la Codificación de la Ley de Régimen Tributario Interno", 'ISO-8859-1','UTF-8'),0,'L');
    $this->pdf->SetXY(5,$this->pdf->GetY()+15);
             $tablaFinal[0]['medidas']=array(100,100);
             $tablaFinal[0]['alineado']=array('C','C');
             $tablaFinal[0]['datos']=array("______________________________________","______________________________________");
             $tablaFinal[1]['medidas']=$tablaFinal[0]['medidas'];
             $tablaFinal[1]['alineado']= $tablaFinal[0]['alineado'];
             $tablaFinal[1]['datos']=array("Firma del Contador","Firma del representante");
             $tablaFinal[2]['medidas']=$tablaFinal[0]['medidas'];
             $tablaFinal[2]['alineado']= $tablaFinal[0]['alineado'];
             $tablaFinal[2]['datos']=array("Firma del Contador","Firma del representante");
             $totales = count($tabla_RE_FU);  
             foreach ($tablaFinal as $key => $value){
               $this->pdf->SetXY(5,$this->pdf->GetY());            
                $this->pdf->SetFont('Arial','',8); 
                $this->pdf->SetWidths($value['medidas']);
                $this->pdf->SetAligns($value['alineado']);
                $arr= $value['datos'];
                
                  $this->pdf->Row($arr,4,'','',null,true); 
              }  

          $ruta = '../../../php/vista/TEMP/';
          $ruta1 = '../vista/TEMP/';
          $carpeta = "AT".$_SESSION['INGRESO']['item'];
          if(!file_exists($ruta.$carpeta))
          {
           mkdir($ruta.''.$carpeta, 0777, true);
          }
          $archivo ='AT'.date('m',strtotime($FechaIni)).''.date('Y',strtotime($FechaIni)).'.pdf';

          // print_r($ruta."".$carpeta.'/'.$archivo);
          $this->pdf->Output('F',$ruta."".$carpeta.'/'.$archivo);
         // $this->pdf->Output();
          //print_r($ruta.$carpeta.'/'.$archivo);die();
          $url = $ruta1.$carpeta.'/'.$archivo;
          return $url;
            
  }
}
?>