<?php
/** 
 * AUTOR DE RUTINA	: Teddy Moreira
 * MODIFICADO POR : Teddy Moreira
 * FECHA CREACION	: 10/03/2025
 * FECHA MODIFICACION : 11/03/2025
 * DESCIPCION : Clase modelo para Referenciales SRI
 */
include(dirname(__DIR__, 2) . '/funciones/funciones.php');
@session_start();

class referenciales_sriM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function GuardarProducto($parametros)
    {
        
        $sql = "INSERT INTO Tabla_Referenciales_SRI(Tipo_Referencia, Codigo, Descripcion, Abreviado, TFA, TPFA) 
                VALUES ('" . $parametros['tp'] . "', '" . $parametros['codigo'] . "', '" . $parametros['concepto'] . "', '".$parametros['abreviado']."', ".$parametros['tfa'].", " . $parametros['tpfa'] . ")";
        return $this->db->datos($sql);
    }
    
    function verificarProductos($codigo)
    {
        $sql = "SELECT * 
                FROM Tabla_Referenciales_SRI
                WHERE Codigo = '".$codigo."' 
                ORDER BY Tipo_Referencia, Codigo";
        
        //print_r($sql);die();
        return $this->db->datos($sql);
    }

    function EliminarProducto($parametros)
    {
        foreach ($parametros as $registro) {
            $id = $registro['ID'];
            
            $sqlEliminar = "DELETE FROM Tabla_Referenciales_SRI 
                    WHERE ID = $id";
                    
            $this->db->datos($sqlEliminar);
        }
        return true;
    }

    function ListaEliminar($parametros)
    {
        $sql = "SELECT * 
            FROM Tabla_Referenciales_SRI
            WHERE Codigo = '" . $parametros['codigo'] . "' 
            AND Tipo_Referencia = '".$parametros['tp']."'";
        return $this->db->datos($sql);
    }

    function EditarProducto($parametros)
    {
        $sql = "UPDATE Tabla_Referenciales_SRI 
            SET Tipo_Referencia = '" . $parametros['tp'] . "', 
                Codigo = '" . $parametros['codigo'] . "', 
                Descripcion = '" . $parametros['concepto'] . "', 
                Abreviado = '".$parametros['abreviado']."', 
                TFA = ".$parametros['tfa'].", 
                TPFA = ".$parametros['tpfa']." 
            WHERE ID = " . $parametros['id'];
        //print_r($sql); die();
        return $this->db->datos($sql);
    }

    function ListaReferenciales(){
        $sql = "SELECT * 
                FROM Tabla_Referenciales_SRI
                ORDER BY Tipo_Referencia, Codigo";
        //print_r($sql);die();
        return $this->db->datos($sql);
    }

}

?>