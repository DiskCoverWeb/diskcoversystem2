<?php
include(dirname(__DIR__,2).'/funciones/funciones.php');
$controlador = new FCierre_CajaC();
if (isset($_GET['CargarDCCuenta'])) {
    echo json_encode($controlador->CargarDCCuenta($_GET));
}else
if (isset($_GET['CambiarCta'])) {
    echo json_encode($controlador->CambiarCta($_POST));
}


class FCierre_CajaC
{
	private $db ;
    function __construct()
    {
    	$this->db = new db();
    }

    function CargarDCCuenta($parametros)
    {
    	extract($parametros);
	    $sSQL = "SELECT CONCAT(Codigo, ' - ', Cuenta) AS text, Codigo as id " .
	            "FROM Catalogo_Cuentas " .
	            "WHERE Item = '".$_SESSION['INGRESO']['item']."' " .
	            "AND Periodo = '".$_SESSION['INGRESO']['periodo']."' " .
	            "AND DG = 'D' " .
	            "AND Codigo <> '$Codigo1' ";

	    if(isset($q) && $q!=G_NINGUNO){
	    	if (is_numeric(str_replace(".", "", $q))) {
	        $sSQL .= "AND Codigo LIKE '$q%' ";
		    } else {
		        $sSQL .= "AND Cuenta LIKE '%$q%' ";
		    }
	    }

	    $sSQL .= "ORDER BY Codigo";
    	return $this->db->datos($sSQL);
    }

    function CambiarCta($parametros)
    {
    	extract($parametros);
    	$Codigo2 = SinEspaciosIzq($Codigo2);
		if ($Codigo2 == "") {
		    $Codigo2 = G_NINGUNO;
		}

		if ($Codigo1 !== G_NINGUNO) {
			$_SESSION['Co']['TP'] = $TP;
			$_SESSION['Co']['Numero'] = $Numero;

		    switch ($Producto) {
		        case "Catalogo":
		            Actualiza_Cuenta_Tabla("Transacciones", "Cta", $Codigo1, $Codigo2);
		            Actualiza_Cuenta_Tabla("Trans_SubCtas", "Cta", $Codigo1, $Codigo2);
		            Actualiza_Cuenta_Tabla("Trans_Compras", "Cta_Servicio", $Codigo1, $Codigo2);
		            Actualiza_Cuenta_Tabla("Trans_Compras", "Cta_Bienes", $Codigo1, $Codigo2);
		            Actualiza_Cuenta_Tabla("Trans_Air", "Cta_Retencion", $Codigo1, $Codigo2);
		            Actualiza_Cuenta_Tabla("Trans_Kardex", "Cta_Inv", $Codigo1, $Codigo2);
		            Actualiza_Cuenta_Tabla("Trans_Kardex", "Contra_Cta", $Codigo1, $Codigo2);
		            Actualiza_Cuenta_Tabla("Facturas", "Cta_CxP", $Codigo1, $Codigo2);
		            Actualiza_Cuenta_Tabla("Trans_Abonos", "Cta_CxP", $Codigo1, $Codigo2);
		            Actualiza_Cuenta_Tabla("Trans_Abonos", "Cta", $Codigo1, $Codigo2);
		            Actualiza_Cuenta_Tabla("Catalogo_CxCxP", "Cta", $Codigo1, $Codigo2);
		            Actualiza_Cuenta_Tabla("Catalogo_Lineas", "CxC", $Codigo1, $Codigo2);
		            Actualiza_Cuenta_Tabla("Catalogo_Lineas", "CxC_Anterior", $Codigo1, $Codigo2);
		            break;
		        default:
		            Actualiza_Cuenta_Tabla("Transacciones", "Cta", $Codigo1, $Codigo2, true, $Asiento);
		            Actualiza_Cuenta_Tabla("Trans_SubCtas", "Cta", $Codigo1, $Codigo2, true);
		            Actualiza_Cuenta_Tabla("Trans_Compras", "Cta_Servicio", $Codigo1, $Codigo2, true);
		            Actualiza_Cuenta_Tabla("Trans_Compras", "Cta_Bienes", $Codigo1, $Codigo2, true);
		            Actualiza_Cuenta_Tabla("Trans_Air", "Cta_Retencion", $Codigo1, $Codigo2, true);
		            Actualiza_Cuenta_Tabla("Trans_Kardex", "Cta_Inv", $Codigo1, $Codigo2, true);
		            Actualiza_Cuenta_Tabla("Trans_Kardex", "Contra_Cta", $Codigo1, $Codigo2, true);
		    }

		    Actualiza_Procesado_Tabla("Transacciones", null, "Cta", $Codigo1);
		    Actualiza_Procesado_Tabla("Transacciones", null, "Cta", $Codigo2);
		    Actualiza_Procesado_Tabla("Trans_SubCtas", null, "Cta", $Codigo1);
		    Actualiza_Procesado_Tabla("Trans_SubCtas", null, "Cta", $Codigo2);
		    Actualiza_Procesado_Tabla("Trans_Kardex", null, "Cta_Inv", $Codigo1);
		    Actualiza_Procesado_Tabla("Trans_Kardex", null, "Cta_Inv", $Codigo2);
		    Actualiza_Procesado_Tabla("Trans_Kardex", null, "Contra_Cta", $Codigo1);
		    Actualiza_Procesado_Tabla("Trans_Kardex", null, "Contra_Cta", $Codigo2);

		    return ["message"=>"Proceso terminado", "ico" => "success"] ;
		} else {
		    return ["message"=>"No se puede cambiar la Cuenta", "ico" => "warning"] ;
		}


    }
}