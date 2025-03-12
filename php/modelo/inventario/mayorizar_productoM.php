<?php

require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");
/**
 */
class mayorizar_productoM
{
    private $conn;
    public function __construct()
    {
        $this->conn = new db();
    }

    public function Mayorizar_Inventario(){
        $sql = "UPDATE Trans_Kardex
                SET Procesado = 0
                WHERE Item = '".$_SESSION['INGRESO']['item']."'
                AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
        //print_r($sql); die();
        $result = $this->conn->String_Sql($sql);
        //print_r($result); die( );
        return $result;
    }
    public function Mayorizar_Inventario_SP($FechaCorteKardex){
        $TipoKardex = '';
        $parametros = array(
            array(&$_SESSION['INGRESO']['item'], SQLSRV_PARAM_IN),
            array(&$_SESSION['INGRESO']['periodo'], SQLSRV_PARAM_IN),
            array(&$_SESSION['INGRESO']['CodigoU'], SQLSRV_PARAM_IN),
            array(&$_SESSION['INGRESO']['modulo_'], SQLSRV_PARAM_IN),
            array(&$FechaCorteKardex, SQLSRV_PARAM_IN),
            array(&$TipoKardex, SQLSRV_PARAM_OUT),
            );
            
        $sql="EXEC sp_Mayorizar_Inventario @Item=?, @Periodo=?, @Usuario=?, @NumModulo=?, @FechaCorte=?, @TipoKardex=?";
        return  $this->conn->ejecutar_procesos_almacenados($sql,$parametros);
    }
}
?>