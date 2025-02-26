<?php

require_once(dirname(__DIR__,2)."/db/db1.php");
include(dirname(__DIR__,2).'/funciones/funciones.php');
@session_start(); 
/**
 * 
 */
class CatalogoM
{
    private $db;

    public function __construct(){
        $this->db = new db();
    }

    function SelectDatos($sSQL)
    {
        return $this->db->datos($sSQL);
    }

    function getSqlListarCatalogoInventario($Codigo, $Codigo1, $pdf=false){
        if($pdf){
			$sSQL = "SELECT TC,Codigo_Inv,Producto,PVP,Codigo_Barra,Unidad," .
			"Reg_Sanitario " ;
		}else{
			$sSQL = "SELECT TC,Codigo_Inv,Producto,PVP,Codigo_Barra,Cta_Inventario,Unidad,Cantidad," .
			"Cta_Costo_Venta,Cta_Ventas,Cta_Ventas_0,Cta_Ventas_Ant,Cta_Venta_Anticipada," .
			"IVA,INV,Codigo_IESS, Codigo_RES, Marca,Reg_Sanitario,Ayuda " ;
		}

		$sSQL .=   " FROM Catalogo_Productos " .
		"WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' " .
		"AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' ";
		if ($Codigo != G_NINGUNO && $Codigo1 != G_NINGUNO) {
			$sSQL .= "AND Codigo_Inv BETWEEN '" . $Codigo . "' and '" . $Codigo1 . "' ";
		}
		if (@$_POST['CheqPM'] == 1) $sSQL .= "AND TC = 'P' ";
		$sSQL .= "ORDER BY Codigo_Inv ";
        return $sSQL;
    }
}