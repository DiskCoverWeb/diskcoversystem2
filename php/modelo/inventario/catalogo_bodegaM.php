<?php
include(dirname(__DIR__, 2) . '/funciones/funciones.php');
@session_start();

class catalogo_bodegaM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function GuardarProducto($parametros)
    {
        $sql = "";
        if($parametros['nivel'] == '99'){
            $sql = "INSERT INTO Catalogo_Proceso(DC, Cmds, Proceso, TP, Nivel, Item, Picture, Color) 
                    VALUES ('" . $parametros['tipo'] . "', '0000', '" . $parametros['concepto'] . "', '".$parametros['codigo']."', '".$parametros['nivel']."', '" . $_SESSION['INGRESO']['item'] . "', '".$parametros['picture']."', '".$parametros['color']."')";
        }else {
            $sql = "INSERT INTO Catalogo_Proceso(DC, Cmds, Proceso, TP, Nivel, Item, Picture, Color) 
                    VALUES ('" . $parametros['tipo'] . "', '" . $parametros['codigo'] . "', '" . $parametros['concepto'] . "', '".$parametros['tp']."', '".$parametros['nivel']."', '" . $_SESSION['INGRESO']['item'] . "', '".$parametros['picture']."', '".$parametros['color']."')";
        }
        return $this->db->datos($sql);
    }
    
    function ListaProductos($parametros)
    {
        $sql = "";
        if($parametros['nivel'] == '99'){
            $sql = "SELECT DC, Cmds, Proceso, TP, Nivel, Item, ID, Picture
                    FROM Catalogo_Proceso
                    WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND Nivel = '".$parametros['nivel']."'
                    ORDER BY Cmds";
        }else{
            $sql = "SELECT DC, Cmds, Proceso, TP, Nivel, Item, ID, Picture, Color
                    FROM Catalogo_Proceso
                    WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND TP = '".$parametros['tp']."'
                    AND Nivel = '".$parametros['nivel']."'
                    ORDER BY Cmds";
        }
        return $this->db->datos($sql);
    }

    function EliminarProducto($parametros)
    {
        //$registrosAEliminar = $this->ListaIDsEliminar($parametros);
        foreach ($parametros as $registro) {
            $id = $registro['ID'];
            $sqlEliminar = "";
            if($registro['Nivel'] == '99'){
                $sqlEliminar = "DELETE FROM Catalogo_Proceso 
                        WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                        AND ID = '$id'
                        AND TP = '".$registro['TP']."'
                        AND Nivel = '".$registro['Nivel']."'";
            }else{
                $sqlEliminar = "DELETE FROM Catalogo_Proceso 
                        WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                        AND ID = '$id'
                        AND TP = '".$registro['TP']."'
                        AND Nivel = '".$registro['Nivel']."'";
            }
            $this->db->datos($sqlEliminar);
        }
        return true;
    }

    function ListaEliminar($parametros)
    {
        $sql = "";
        if($parametros['nivel'] == '99'){
            $sql = "SELECT ID, Cmds, Proceso, TP, Nivel 
                FROM Catalogo_Proceso
                WHERE TP LIKE '" . $parametros['codigo'] . "%'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Nivel = '".$parametros['nivel']."'";
        }else{
            $sql = "SELECT ID, Cmds, Proceso, TP, Nivel 
                FROM Catalogo_Proceso
                WHERE Cmds LIKE '" . $parametros['codigo'] . "%'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND TP = '".$parametros['tp']."'
                AND Nivel = '".$parametros['nivel']."'";
        }
        return $this->db->datos($sql);
    }

    function EditarProducto($parametros)
    {
        $sql = "";
        if($parametros['nivel'] == '99'){
            //print_r($parametros);die();
            $sql = "UPDATE Catalogo_Proceso 
                SET DC = '" . $parametros['tipo'] . "', 
                    Cmds = '0000', 
                    Proceso = '" . $parametros['concepto'] . "', 
                    Picture = '".$parametros['picture']."', 
                    Color = '".$parametros['color']."' 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND ID = '" . $parametros['id'] . "'
                AND TP = '" . $parametros['codigo'] . "'
                AND Nivel = '" . $parametros['nivel'] . "'";
        }else{
            $sql = "UPDATE Catalogo_Proceso 
                SET DC = '" . $parametros['tipo'] . "', 
                    Cmds = '" . $parametros['codigo'] . "', 
                    Proceso = '" . $parametros['concepto'] . "', 
                    Picture = '".$parametros['picture']."', 
                    Color = '".$parametros['color']."' 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND ID = '" . $parametros['id'] . "'
                AND TP = '" . $parametros['tp'] . "'
                AND Nivel = '" . $parametros['nivel'] . "'";
        }

        return $this->db->datos($sql);
    }

    function ListaTipoProcesosGenerales(){
        $sql = "SELECT TP, Proceso, DC, ID
                FROM Catalogo_Proceso
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Nivel = 00
                AND DC = '--'
                ORDER BY TP, Proceso";
        return $this->db->datos($sql);
    }
    function ListaTipoProcesosGeneralesAux($parametros){
        $sql = "SELECT TOP 1 TP 
                FROM Catalogo_Proceso
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Nivel = '" . $parametros['tp'] . "'
                AND TP LIKE ('%')
                ORDER BY Proceso";
        return $this->db->datos($sql);
    }
}

?>