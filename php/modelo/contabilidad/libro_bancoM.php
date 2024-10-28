<?php
error_reporting(-1);
//include(dirname(__DIR__).'/funciones/funciones.php');//
include(dirname(__DIR__,2).'/db/variables_globales.php');//
 class libro_bancoM
 {
 	private $conn;
 	function __construct()
 	{

		$this->conn = new db();

 	}
  function cuentas_()
  {
 	$sql= "SELECT Codigo, Codigo+'    '+Cuenta As Nombre_Cta 
          FROM Catalogo_Cuentas 
          WHERE TC = '".G_CTABANCOS."'
          AND DG = 'D'
          AND Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
          ORDER BY Codigo ";    
	    $result = $this->conn->datos($sql);
	   return $result;

  }

   function cuentas_filtrado($ini,$fin)
  {
  		if($ini =='')
  		{
  			$ini = 1;
  		}
  		if($fin == '')
  		{
  			$fin = $ini;
  		}
  		$sql ="SELECT Codigo, Codigo+'    '+Cuenta As Nombre_Cta 
       FROM Catalogo_Cuentas 
       WHERE DG = 'D'
        AND Cuenta <> '".G_NINGUNO."' 
        AND Item = '".$_SESSION['INGRESO']['item']."' 
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        ORDER BY Codigo ";
       $result = $this->conn->datos($sql);
     return $result;

  }


 function consultar_banco_($desde,$hasta,$CheckAgencia,$DCAgencia,$Checkusu,$DCUsuario,$DCCta,$soloReturnDatos=false)
 {

  $ConSucursal = $_SESSION['INGRESO']['Sucursal'];
  $sSQL = "SELECT Cta,T.Fecha,T.TP,T.Numero,Cheq_Dep,Cliente,C.Concepto,Debe,Haber,Saldo,Parcial_ME,Saldo_ME,T.T,T.Item
       FROM Transacciones As T,Comprobantes As C,Clientes As Cl
       WHERE T.Fecha BETWEEN '".$desde."' and '".$hasta."' 
       AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."'";

    if($CheckAgencia == 'true')
   {
   	 $sSQL.= " AND T.Item = '".$DCAgencia."' ";
   }else
   {
    if (!$ConSucursal) $sSQL .= "AND T.Item = '" . $_SESSION['INGRESO']['item'] . "' ";
   }
  
  	if($Checkusu == 'true')
  	{
  		$sSQL.=  " AND C.CodigoU = '".$DCUsuario."'" ;
    }

		$sSQL.=  " AND T.Cta = '".$DCCta."' 
      AND C.TP = T.TP 
      AND C.Numero = T.Numero 
      AND C.Fecha = T.Fecha 
      AND C.Item = T.Item 
      AND C.Codigo_B = Cl.Codigo 
      AND C.Periodo = T.Periodo 
      ORDER BY Cta,T.Fecha,T.TP,T.Numero,Debe DESC,Haber,T.ID ";
// echo "<pre>";print_r($sSQL);echo "</pre>";die();
    $medida = medida_pantalla($_SESSION['INGRESO']['Height_pantalla'])-170;  //el numero es el alto de los demas conponenetes sumados
    $DGBanco = grilla_generica_new($sSQL,'Transacciones As T,Comprobantes As C,Clientes As Cl','tbl_lib',false,$botones=false,$check=false,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,$medida);
    $AdoBanco = $this->conn->datos($sSQL);
    if($soloReturnDatos){
      return $AdoBanco;
    }

    $Debe = 0; $Haber = 0; $Saldo = 0;
    $Debe_ME = 0; $Haber_ME = 0; $Saldo_ME = 0;
    
    foreach ($AdoBanco as $key => $row) {
      $Debe += $row["Debe"];
      $Haber += $row["Haber"];
      $Saldo = $row["Saldo"];

      if ($row["Parcial_ME"] >= 0) {
          $Debe_ME += $row["Parcial_ME"];
      } else {
          $Haber_ME -= $row["Parcial_ME"];
      }

      $Saldo_ME = $row["Saldo_ME"];
    }

    $SaldoAnterior = CalculosSaldoAnt($DCCta, $Debe, $Haber, $Saldo);
    $LabelSaldoAntMN = number_format($SaldoAnterior,2,'.','');
    $LabelSaldoAntME = number_format($Saldo_ME - $Debe_ME + $Haber_ME,2,'.','');
    $LabelTotSaldo = number_format($Saldo,2,'.','');
    $LabelTotSaldoME = number_format($Saldo_ME,2,'.','');
    $LabelTotDebe = number_format($Debe,2,'.','');
    $LabelTotHaber = number_format($Haber,2,'.','');
    $LabelTotDebeME = number_format($Debe_ME,2,'.','');
    $LabelTotHaberME = number_format($Haber_ME,2,'.','');
    $TotalRegistros = count($AdoBanco);

    return compact(
        'SaldoAnterior',
        'LabelSaldoAntMN',
        'LabelSaldoAntME',
        'LabelTotSaldo',
        'LabelTotSaldoME',
        'LabelTotDebe',
        'LabelTotHaber',
        'LabelTotDebeME',
        'LabelTotHaberME',
        'DGBanco',
        'AdoBanco',
        'TotalRegistros'
    );
 }

 function consultar_banco_datos($desde,$hasta,$CheckAgencia,$DCAgencia,$Checkusu,$DCUsuario,$DCCta)
 {

  $sql = "SELECT Cta,T.Fecha,T.TP,T.Numero,Cheq_Dep,Cliente,C.Concepto,Debe,Haber,Saldo,Parcial_ME,Saldo_ME,T.T,T.Item
       FROM Transacciones As T,Comprobantes As C,Clientes As Cl
       WHERE T.Fecha BETWEEN '".$desde."' and '".$hasta."' 
       AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."'";

        if($CheckAgencia == 'true')
   {
   	 $sql.= " AND T.Item = '".$DCAgencia."' ";
   }else
   {
   	$sql.= "AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
   }

  
  	if($Checkusu == 'true')
  	{
  		$sql.=  "AND C.CodigoU = '".$DCUsuario."' 
  		AND T.Cta = '".$DCCta."' 
        AND C.TP = T.TP 
        AND C.Numero = T.Numero 
        AND C.Fecha = T.Fecha 
        AND C.Item = T.Item 
        AND C.Codigo_B = Cl.Codigo 
        AND C.Periodo = T.Periodo 
        ORDER BY Cta,T.Fecha,T.TP,T.Numero,Debe DESC,Haber,T.ID ";
  	}else
  	{
  		$sql.=  " AND T.Cta = '".$DCCta."' 
        AND C.TP = T.TP 
        AND C.Numero = T.Numero 
        AND C.Fecha = T.Fecha 
        AND C.Item = T.Item 
        AND C.Codigo_B = Cl.Codigo 
        AND C.Periodo = T.Periodo 
        ORDER BY Cta,T.Fecha,T.TP,T.Numero,Debe DESC,Haber,T.ID ";

  	}

 
     $result = $this->conn->datos($sql);
     return $result;
  
 }

 function imprimir_excel_LibroBanco($parametros,$sub)
 {

   $desde = str_replace('-','',$parametros['desde']);
	 $hasta = str_replace('-','',$parametros['hasta']);
	 $result = $this->consultar_banco_($desde,$hasta,$parametros['CheckAgencia'],$parametros['DCAgencia'],$parametros['CheckUsu'],$parametros['DCUsuario'],$parametros['DCCtas'], true);

    $b = 1;
    $titulo='L I B R O   B A N C O';
     $tablaHTML =array();
     $tablaHTML[0]['medidas']=array(20,18,30,25,50,50,18,20,20,20);
     $tablaHTML[0]['datos']=array('FECHA','TD','NUMERO','CHEQ/DEP','BENEFICIARIO','CONCEPTO','PARCIAL_ME','DEBE','HABER','SALDO');
     $tablaHTML[0]['tipo'] ='C';
     $pos = 1;
     $compro1='';
    foreach ($result as $key => $value) {
          $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
          $tablaHTML[$pos]['datos']=array($value['Fecha']->format('Y-m-d'),$value['Numero'],$value['Cheq_Dep'],$value['Cliente'],$value['Concepto'],$value['Parcial_ME'],number_format($value['Debe'],2,'.',''),number_format($value['Haber'],2,'.',''),$value['Saldo']);
          $tablaHTML[$pos]['tipo'] ='N';          
          $pos+=1;
    }
      excel_generico($titulo,$tablaHTML);  
  }

 } 
?>