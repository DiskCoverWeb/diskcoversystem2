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
}