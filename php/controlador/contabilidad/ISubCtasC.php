<?php
require_once(dirname(__DIR__, 2) . "/modelo/contabilidad/ISubCtasM.php");

$controlador = new ISubCtasC();

if (isset($_GET['ListarSubCtas'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->ListarSubCtas($parametros));
}

if (isset($_GET['LlenarCta'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->LlenarCta($parametros));
}

if (isset($_GET['Eliminar'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Eliminar($parametros));
}

if (isset($_GET['EliminarSubCta'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->EliminarSubCta($parametros));
}

if (isset($_GET['GrabarCta'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->GrabarCta($parametros));
}

class ISubCtasC
{

    private $modelo;

    public function __construct()
    {
        $this->modelo = new ISubCtasM();
    }

    public function ListarSubCtas($parametros)
    {
        return $this->modelo->ListarSubCtas($parametros);
    }

    public function LlenarCta($parametros)
    {
        $parametros['CodigoCta'] = SinEspaciosIzq($parametros['CodigoCta']);
        $datos = $this->modelo->LlenarCta($parametros);
        return $datos;
    }

    public function Eliminar($parametros){
        return $this->modelo->Eliminar($parametros);
    }

    public function EliminarSubCta($parametros){
        return $this->modelo->EliminarSubCta($parametros);
    }

    public function GrabarCta($parametros){
        return $this->modelo->GrabarCta($parametros);
    }
}
?>