<?php
include(dirname(__DIR__, 2) . '/funciones/funciones.php');
@session_start();

class categoriasM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function ConsultarCategoriaClientesDatosExtras($option)
    {
        $sql = "SELECT Tipo_Dato, Codigo, Beneficiario, ID 
                FROM Clientes_Datos_Extras 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Tipo_Dato = '" . $option . "' ";
        return $this->db->datos($sql);
    }

    function ListarCategorias()
    {
        $sql = "SELECT TP, Proceso, Cmds, ID 
                FROM Catalogo_Proceso 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Nivel = 0 
                AND TP = 'CATE' ";
        return $this->db->datos($sql);
    }

    function AsignarCategoria($parametros)
    {
        $sql = "INSERT INTO Catalogo_Proceso (TP, Proceso, Cmds, Item) 
                VALUES ('" . $parametros['tipo'] . "', '" . $parametros['proceso'] . "', '" . $parametros['cmds'] . "', '" . $_SESSION['INGRESO']['item'] . "')";
        return $this->db->datos($sql);
    }

    function EditarCategoriaPorId($id)
    {
        $sql = "SELECT TP, Proceso, Cmds, ID 
                FROM Catalogo_Proceso 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Nivel = 0 
                AND TP = 'CATE' 
                AND ID = '" . $id . "' ";
        return $this->db->datos($sql);
    }

    function EditarCategoriaCatalogoProcesoPorId($parametros)
    {
        $sql = "UPDATE Catalogo_Proceso 
                SET TP = '" . $parametros['tipo'] . "', 
                    Cmds = '" . $parametros['cmds'] . "', 
                    Proceso = '" . $parametros['proceso'] . "' 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND ID = '" . $parametros['id'] . "'";
        return $this->db->datos($sql);
    }

    function EliminarCategoriaCatalogoProcesosPorId($id)
    {
        $sql = "DELETE
                FROM Catalogo_Proceso 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND ID = '" . $id . "' ";
        return $this->db->datos($sql);
    }

    function ConsultarTipoIngreso()
    {
        $sql = "SELECT TP, Proceso, Cta_Debe, ID 
                FROM Catalogo_Proceso 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Nivel = 99
                ORDER BY TP";
        return $this->db->datos($sql);
    }

    function ConsultarCatalogoBodega($option)
    {
        $sql = "SELECT CodBod, Bodega, Item, Periodo, X, ID 
                FROM Catalogo_Bodegas 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $option . "'
                ORDER BY CodBod";
        return $this->db->datos($sql);
    }

    function AgregarCategoriaClientesDatosExtras($parametros)
    {
        $sql = "INSERT INTO Clientes_Datos_Extras (Tipo_Dato, Codigo, Beneficiario, Item) 
                VALUES ('" . $parametros['tipo'] . "', '" . $parametros['codigo'] . "', '" . $parametros['beneficiario'] . "', '" . $_SESSION['INGRESO']['item'] . "')";
        return $this->db->datos($sql);
    }

    function AgregarCategoriaGFN($parametros)
    {
        $sql = "INSERT INTO Catalogo_Proceso (TP, Proceso, Cmds) 
                VALUES ('" . $parametros['tp'] . "', '" . $parametros['proceso'] . "', '" . $parametros['cmds'] . "')";
        return $this->db->datos($sql);
    }

    function MostrarDatosPorId($id)
    {
        $sql = "SELECT Tipo_Dato, Codigo, Beneficiario, ID 
                FROM Clientes_Datos_Extras 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND ID = '" . $id . "' ";
        return $this->db->datos($sql);
    }

    function EditarCategoriaClientesDatosExtrasPorId($parametros)
    {
        $sql = "UPDATE Clientes_Datos_Extras 
                SET Tipo_Dato = '" . $parametros['tipo'] . "', 
                    Codigo = '" . $parametros['codigo'] . "', 
                    Beneficiario = '" . $parametros['beneficiario'] . "' 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND ID = '" . $parametros['id'] . "'";
        return $this->db->datos($sql);
    }

    function EliminarCategoriaClientesDatosExtrasPorId($id)
    {
        $sql = "DELETE
                FROM Clientes_Datos_Extras 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND ID = '" . $id . "' ";
        return $this->db->datos($sql);
    }
}
?>