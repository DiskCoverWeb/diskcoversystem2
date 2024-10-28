<?php
include(dirname(__DIR__,2).'/funciones/funciones.php');
$controlador = new FChangeValoresC();
if (isset($_GET['CambiarValores'])) {
    echo json_encode($controlador->CambiarValores($_POST));
}


class FChangeValoresC
{
	private $db ;
    function __construct()
    {
    	$this->db = new db();
    }

    function CambiarValores($parametros)
    {
		extract($parametros);
		$_SESSION['Co']['TP'] = $TPChangeValores;
		$_SESSION['Co']['Numero'] = $NumeroChangeValores;
		$Asiento = $AsientoChangeValores;
		$Cta = $CtaChangeValores;
		$Cadena = "";

		$sql = "SELECT T.Debe,T.Haber,T.Detalle as NomCtaSup,T.Cheq_Dep,T.ID 
             FROM Transacciones As T, Catalogo_Cuentas As Ca 
             WHERE T.TP = '".$_SESSION['Co']['TP']."' 
             AND T.Numero = ".$_SESSION['Co']['Numero']." 
             AND T.Item = '".$_SESSION['INGRESO']['item']."' 
             AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
             AND T.ID = '".$Asiento."' 
             AND T.Item = Ca.Item 
             AND T.Periodo = Ca.Periodo 
             AND T.Cta = Ca.Codigo 
             ORDER BY T.ID,Debe DESC,T.Cta ";
             $result = $this->db->datos($sql);
        if(count($result)<=0){
        	return ["message"=>"No se encontro el registro", "ico" => "warning"] ;
        }
        extract($result[0]);
		if ($TxtConceptoChangeValores != $TxtConceptoChangeValoresOld) {
		    $sSQL = "UPDATE Comprobantes " .
		            "SET Concepto = '" . $TxtConceptoChangeValores . "' " .
		            "WHERE TP = '" . $_SESSION['Co']['TP']. "' " .
		            "AND Numero = " . $_SESSION['Co']['Numero'] . " " .
		            "AND Item = '" . $_SESSION['INGRESO']['item'] . "' " .
		            "AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' ";
		    Ejecutar_SQL_SP($sSQL);
		    $Cadena .= "Concepto del Comprobante" . PHP_EOL;
		}

		if (floatval($TxtDebeChangeValores) != $Debe) {
		    $sSQL = "UPDATE Transacciones " .
		            "SET Debe = " . floatval($TxtDebeChangeValores) . " " .
		            "WHERE TP = '" . $_SESSION['Co']['TP']. "' " .
		            "AND Numero = " . $_SESSION['Co']['Numero'] . " " .
		            "AND Item = '" . $_SESSION['INGRESO']['item'] . "' " .
		            "AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
		            "AND ID = " . $Asiento . " " .
		            "AND Cta = '" . $Cta . "' ";
		    Ejecutar_SQL_SP($sSQL);
		    $Cadena .= "El Valor del Debe" . PHP_EOL;
		}

		if (floatval($TxtHaberChangeValores) != $Haber) {
		    $sSQL = "UPDATE Transacciones " .
		            "SET Haber = " . floatval($TxtHaberChangeValores) . " " .
		            "WHERE TP = '" . $_SESSION['Co']['TP']. "' " .
		            "AND Numero = " . $_SESSION['Co']['Numero'] . " " .
		            "AND Item = '" . $_SESSION['INGRESO']['item'] . "' " .
		            "AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
		            "AND ID = " . $Asiento . " " .
		            "AND Cta = '" . $Cta . "' ";
		    Ejecutar_SQL_SP($sSQL);
		    $Cadena .= "El Valor del Haber" . PHP_EOL;
		}

		if ($TxtDepositoChangeValores != $Cheq_Dep) {
		    $sSQL = "UPDATE Transacciones " .
		            "SET Cheq_Dep = '" . $TxtDepositoChangeValores . "' " .
		            "WHERE TP = '" . $_SESSION['Co']['TP']. "' " .
		            "AND Numero = " . $_SESSION['Co']['Numero'] . " " .
		            "AND Item = '" . $_SESSION['INGRESO']['item'] . "' " .
		            "AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
		            "AND ID = " . $Asiento . " " .
		            "AND Cta = '" . $Cta . "' ";
		    Ejecutar_SQL_SP($sSQL);
		    $Cadena .= "El Valor del Cheque o Deposito" . PHP_EOL;
		}

		if ($TxtDetalleChangeValores != $NomCtaSup) {
		    $sSQL = "UPDATE Transacciones " .
		            "SET Detalle = '" . $TxtDetalleChangeValores . "' " .
		            "WHERE TP = '" . $_SESSION['Co']['TP']. "' " .
		            "AND Numero = " . $_SESSION['Co']['Numero'] . " " .
		            "AND Item = '" . $_SESSION['INGRESO']['item'] . "' " .
		            "AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
		            "AND ID = " . $Asiento . " " .
		            "AND Cta = '" . $Cta . "' ";
		    Ejecutar_SQL_SP($sSQL);
		    $Cadena .= "El Detalle de la Transaccion" . PHP_EOL;
		}

		if ($Cadena == "") {
			return ["message"=>"No se ha realizado ningun cambio", "ico" => "warning"] ;
		} else {
		    Actualiza_Procesado_Tabla("Transacciones", true);
		    Actualiza_Procesado_Tabla("Trans_SubCtas", true);
		    Actualiza_Procesado_Tabla("Trans_Kardex", true);
		    return ["message"=>"Proceso realizado, se actualizaron: ". PHP_EOL.$Cadena, "ico" => "success"] ;
		}

    	
    }
}