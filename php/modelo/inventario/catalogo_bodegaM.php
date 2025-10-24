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
        // print_r($parametros);die();
        $sql = "";
        if($parametros['nivel'] == '0'){
            $sql = "INSERT INTO Catalogo_Proceso(DC, Cmds, Proceso, TP, Nivel, Item, Picture, Color, Cta_Debe, Cta_Haber) 
                    VALUES ('--', '" . $parametros['codigo'] . "', '" . strtoupper($parametros['concepto']) . "', '".$parametros['tp']."', '".$parametros['nivel']."', '" . $_SESSION['INGRESO']['item'] . "', '".$parametros['picture']."', '".$parametros['color']."', '".$parametros['cta_debe']."', '".$parametros['cta_haber']."')";
        }else {
            $sql = "INSERT INTO Catalogo_Proceso(DC, Cmds, Proceso, TP, Nivel, Item, Picture, Color, Cta_Debe, Cta_Haber) 
                    VALUES ('" . $parametros['tipo'] . "', '" . $parametros['codigo'] . "', '" . $parametros['concepto'] . "', '".$parametros['tp']."', '".$parametros['NivelSuperior']."', '" . $_SESSION['INGRESO']['item'] . "', '".$parametros['picture']."', '".$parametros['color']."', '".$parametros['cta_debe']."', '".$parametros['cta_haber']."')";
        }
        // print_r($sql); die();

        // INSERT INTO Catalogo_Proceso(DC, Cmds, Proceso, TP, Nivel, Item, Picture, Color, Cta_Debe, Cta_Haber) VALUES 
        //                             ('D','85.05.01','prueba sub Desnutrición ', 'PROGRAMA', '85.05', '001', '.', '.', '.', '.')

        return $this->db->datos($sql);
    }
    
    function ListaProductos($parametros)
    {
        $sql = "";
        if($parametros['nivel'] == '99'){
            $sql = "SELECT DC, Cmds, Proceso, TP, Nivel, Item, ID, Picture, Cta_Debe, Cta_Haber, Color
                    FROM Catalogo_Proceso
                    WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND Nivel = '".$parametros['nivel']."'
                    ORDER BY Cmds";
        }else{
            $sql = "SELECT DC, Cmds, Proceso, TP, Nivel, Item, ID, Picture, Cta_Debe, Cta_Haber, Color
                    FROM Catalogo_Proceso
                    WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND TP IN ('".implode("', '", $parametros['tp'])."') 
                    AND Nivel = '".$parametros['nivel']."'
                    ORDER BY Cmds";
        }
        //print_r($sql);die();
        return $this->db->datos($sql);
    }

    function verificarProductos($codigo)
    {
        $sql = "SELECT * 
                FROM Catalogo_Proceso
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Cmds = '".$codigo."' 
                ORDER BY Cmds";
        
        //print_r($sql);die();
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
                        AND ID = $id";
                        //AND TP = '".$registro['TP']."'
                        //AND Nivel = '".$registro['Nivel']."'";
            }else{
                $sqlEliminar = "DELETE FROM Catalogo_Proceso 
                        WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                        AND ID = $id";
                        //AND TP = '".$registro['TP']."'
                        //AND Nivel = '".$registro['Nivel']."'";
            }
            //print_r($sqlEliminar);die();
            $this->db->datos($sqlEliminar);
        }
        return true;
    }

    function ListaEliminar($parametros)
    {
        $sql = "";
        
        if($parametros['nivel'] == '0'){
            //if($parametros['tp'] == '00')$parametros['tp'] = '99';
            $sql = "SELECT ID, Cmds, Proceso, TP, Nivel 
                    FROM Catalogo_Proceso
                    WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND Cmds LIKE '" . $parametros['codigo'] . "%'";
        }else{
            $sql = "SELECT ID, Cmds, Proceso, TP, Nivel 
                FROM Catalogo_Proceso
                WHERE Cmds = '" . $parametros['codigo'] . "' 
                AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND TP = '".$parametros['tp']."' 
                AND Nivel = '".$parametros['NivelSuperior']."'";
        }

        /*if($parametros['nivel'] == '99'){
            $sql = "SELECT ID, Cmds, Proceso, TP, Nivel 
                FROM Catalogo_Proceso
                WHERE Cmds LIKE '" . $parametros['codigo'] . "%'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Nivel = '".$parametros['nivel']."'";
        }else{
            $sql = "SELECT ID, Cmds, Proceso, TP, Nivel 
                FROM Catalogo_Proceso
                WHERE Cmds LIKE '" . $parametros['codigo'] . "%'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND TP = '".$parametros['tp']."' 
                AND Nivel = '".$parametros['nivel']."'";
        }*/
        //print_r($this->db->datos($sql));die();
        return $this->db->datos($sql);
    }

    function EditarProducto($parametros)
    {
        $sql = "";
        if($parametros['nivel'] == '0'){
            //print_r($parametros);die();
            $sql = "UPDATE Catalogo_Proceso 
                SET DC = '--', 
                    Cmds = '" . $parametros['codigo'] . "', 
                    TP = '" . $parametros['tp'] . "', 
                    Proceso = '" . strtoupper($parametros['concepto']) . "', 
                    Picture = '".$parametros['picture']."', 
                    Color = '".$parametros['color']."', 
                    Cta_Debe = '".$parametros['cta_debe']."', 
                    Cta_Haber = '".$parametros['cta_haber']."' 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND ID = '" . $parametros['id'] . "'"/*
                AND TP = '" . $parametros['codigo'] . "'
                AND Nivel = '" . $parametros['nivel'] . "'"*/;
        }else{
            $sql = "UPDATE Catalogo_Proceso 
                SET DC = '" . $parametros['tipo'] . "', 
                    Cmds = '" . $parametros['codigo'] . "', 
                    TP = '" . $parametros['tp'] . "', 
                    Proceso = '" . $parametros['concepto'] . "', 
                    Picture = '".$parametros['picture']."', 
                    Color = '".$parametros['color']."', 
                    Cta_Debe = '".$parametros['cta_debe']."', 
                    Cta_Haber = '".$parametros['cta_haber']."' 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND ID = '" . $parametros['id'] . "'"/*
                AND TP IN ('" . implode("', '", $parametros['wtp']) . "') 
                AND Nivel = '" . $parametros['nivel'] . "'"*/;
        }
        //print_r($sql); die();
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
        $sql = "SELECT TP 
                FROM Catalogo_Proceso
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Nivel = '" . $parametros['tp'] . "'
                AND TP LIKE ('%')
                ORDER BY Proceso";
        //print_r($sql);die();
        return $this->db->datos($sql);
    }

    function ListaTipoProcesosGeneralesCompleto(){
        //$sql = "SELECT DC, Cmds, Proceso, TP, Nivel, Item, ID, Picture, Cta_Debe, Cta_Haber, Color
        $sql = "SELECT * 
                FROM Catalogo_Proceso
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND TP LIKE ('%') 
                ORDER BY Cmds";
        //print_r($sql);die();
        return $this->db->datos($sql);
    }

    function ListaCatalogoLineas(){
        $sql = "SELECT Fact
                FROM Catalogo_Lineas
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                GROUP BY Fact
                ORDER BY Fact";
        return $this->db->datos($sql);
    }
}

?>