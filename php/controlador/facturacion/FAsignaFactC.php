<?php

require_once(dirname(__DIR__, 2) . "/modelo/facturacion/FAsignaFactM.php");

/*
    AUTOR DE RUTINA	: Leonardo Súñiga
    FECHA CREACION	: 04/03/2024
    FECHA MODIFICACION: 06/03/2024
    DESCIPCIÓN		: Controlador del modal FAsignaFact, se encarga de la parte logica
*/

$controlador = new FAsignaFactC();

if (isset($_GET['AdoRubros'])) {
    echo json_encode($controlador->AdoRubros());
}

if (isset($_GET['DCInv'])) {
    echo json_encode($controlador->DCInv());
}

if (isset($_GET['Listar_Rubros_Grupo'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Listar_Rubros_Grupo($parametros));
}

if (isset($_GET['Command1_Click'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Command1_Click($parametros));
}

if (isset($_GET['Ctrl_V'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Ctrl_V($parametros));
}

if (isset($_GET['Ctrl_D'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Ctrl_D($parametros));
}

if (isset($_GET['Ctrl_2'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Ctrl_D2($parametros));
}

class FAsignaFactC
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new FAsignaFactM();
    }

    public function Ctrl_V($parametros)
    {
        try {
            $this->modelo->UpdateValor($parametros);
            return array("res" => "1", "msj" => "Valor actualizado");
        } catch (Exception $e) {
            return array("res" => "0", "msj" => "Error al actualizar el valor", "error" => $e->getMessage());
        }
    }

    public function Ctrl_D($parametros)
    {
        try {
            $this->modelo->UpdateDescuento($parametros);
            return array("res" => "1", "msj" => "Descuento actualizado");
        } catch (Exception $e) {
            return array("res" => "0", "msj" => "Error al actualizar el descuento", "error" => $e->getMessage());
        }
    }

    public function Ctrl_D2($parametros)
    {
        try {
            $this->modelo->UpdateDescuento2($parametros);
            return array("res" => "1", "msj" => "Descuento2 actualizado");
        } catch (Exception $e) {
            return array("res" => "0", "msj" => "Error al actualizar el descuento", "error" => $e->getMessage());
        }
    }

    public function AdoRubros(): array
    {
        try {
            $AdoRubros = $this->modelo->AdoRubros();
            if (count($AdoRubros) === 0) {
                throw new Exception("No se encontraron rubros");
            }
            return array("res" => "1", "datos" => $AdoRubros);
        } catch (Exception $e) {
            return array("res" => "0", "msj" => "No se encontraron rubros", "error" => $e->getMessage());
        }
    }

    public function DCInv()
    {
        try {
            $datos = $this->modelo->DCInv();
            if (count($datos) == 0) {
                throw new Exception("No se encontraron productos");
            }
            return array("res" => "1", "datos" => $datos);
        } catch (Exception $e) {
            return array("res" => "0", "msj" => $e->getMessage());
        }
    }

    public function Listar_Rubros_Grupo($parametros)
    {
        try {
            $datos = $this->modelo->Listar_Rubros_Grupo($parametros);
            if (count($datos['AdoRubros']) == 0) {
                throw new Exception("No se encontraron rubros");
            }
            return array("res" => "1", "tbl" => $datos['datos']);
        } catch (Exception $e) {
            return array("res" => "0", "msj" => $e->getMessage());
        }
    }

    public function Command1_Click($parametros)
    {
        try {
            $parametros['CodigoP'] = SinEspaciosIzq($parametros['CodigoP']);
            $this->modelo->Command1_Click($parametros);
            return array("res" => "1", "msj" => "Proceso Terminado");
        } catch (Exception $e) {
            return array("res" => "0", "msj" => "Error al ejecutar el proceso", "error" => $e->getMessage());
        }
    }
}