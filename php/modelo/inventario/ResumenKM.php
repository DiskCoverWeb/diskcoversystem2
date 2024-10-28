<?php
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

class ResumenKM
{
    private $db ;
    public $NumEmpresa;
    public $Periodo_Contable;

    function __construct()
    {
        $this->db = new db();
        $this->NumEmpresa = $_SESSION['INGRESO']['item'];
        $this->Periodo_Contable = $_SESSION['INGRESO']['periodo'];
    }

    function ExecuteDB($sSQL)
    {
        return $this->db->String_Sql($sSQL);
    }

    function SelectDB($sSQL)
    {
        return $this->db->datos($sSQL);
    }

} 
?>