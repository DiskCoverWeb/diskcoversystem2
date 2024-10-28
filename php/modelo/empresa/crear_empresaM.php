<?php
require_once(dirname(__DIR__,2)."/db/db1.php");
include(dirname(__DIR__,2).'/funciones/funciones.php');
class crear_empresaM
{
    private $db;
    function __construct()
    {
        $this->db = new db();
    }
    function consulta_empresa($subdir=false)
    {
        $sql = "SELECT *
        FROM Empresas
        WHERE Item <> '000'";
        if($subdir)
        {
            $sql.=" AND SubDir = '".$subdir."'";
        }
        $sql.="
        ORDER BY Item DESC";
        return $this->db->datos($sql);
    }
    function lista_empresas($query=false, $item=false)
    {
        $sql = "SELECT *
        FROM Empresas WHERE Item <> '000'";
        if($query)
        {
            $sql.=" AND Empresa like '%".$query."%'";
        }
        if($item)
        {
            $sql.=" AND Item ='".$item."'";
        }
        return  $this->db->datos($sql);
    }
    function delete_empresa($id)
	{
        $sql= "DELETE FROM Empresas WHERE Item = '".$id."'";
		return $this->db->String_Sql($sql);
	}
    function usuario($CI)
    {
        $sql = "SELECT Codigo, Usuario, Clave
        FROM Accesos        
        WHERE Codigo = '".$CI."'";
        return  $this->db->datos($sql);
    }
    function copia_empresa($nombre)
    {
        $sql= "SELECT Empresa, Item
            FROM Empresas
            WHERE Empresa <> '" .$nombre. "' ";
        return  $this->db->datos($sql);
    }

    function generarItem()
    {
        $Numero = 0;
        $sSQL = "SELECT Item
                 FROM Empresas
                 WHERE Item <> '000'
                 ORDER BY Item";
        $resultado = $this->db->datos($sSQL);

        if (count($resultado) > 0) {
            foreach ($resultado as $row) {
                $Numero = intval($row['Item']);
                for ($i = $Numero; $i <= 999; $i++) {
                    $found = false;
                    foreach ($resultado as $row) {
                        if ($row['Item'] == sprintf("%03d", $i)) {
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $Numero = $i;
                        break;
                    }
                }
            }
        }
        return str_pad($Numero, 3, '0', STR_PAD_LEFT);
    }
}
?>