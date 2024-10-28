<?php

require_once(dirname(__DIR__, 2) . "/modelo/facturacion/FPensionesM.php");

/*
    AUTOR DE RUTINA	: Leonardo Súñiga
    FECHA CREACION	: 01/03/2024
    FECHA MODIFICACION: P/01/2024
    DESCIPCIÓN		: Controlador del modal FPensiones, se encarga de la parte logica 
*/

$controlador = new FPensionesC();

if (isset($_GET['DCInv'])) {
    $query = '';
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }
    echo json_encode($controlador->DCInv($query));
}

if (isset($_GET['ExistenRubros'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Existen_Rubros($parametros));
}

if (isset($_GET['InsertarPensiones'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Insertar_Pensiones($parametros));
}

if (isset($_GET['EliminarPensiones'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Eliminar_Pensiones($parametros));
}

if (isset($_GET['Tipo_Cambio_Valor'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Tipo_Cambio_Valor($parametros));
}

if (isset($_GET['Copiar_Mes'])) {
    echo json_encode($controlador->Copiar_Mes());
}

if (isset($_GET['Multas'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Multas($parametros));
}

if (isset($_GET['Copiar_Mes_KeyDown'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->LstCopiar_KeyDown($parametros));
}

class FPensionesC
{

    private $modelo;

    public function __construct()
    {
        $this->modelo = new FPensionesM();
    }

    public function LstCopiar_KeyDown($parametros)
    {
        try {
            $Copiar_Periodo = SinEspaciosIzq($parametros['LstCopiar']);
            $Copiar_Mes = SinEspaciosDer2($parametros['LstCopiar']);
            $Mifecha = $this->Rango_Fechas_Proceso($parametros['Contador'], $parametros['FechaTexto']);
            $this->modelo->KeyDown_Delete($parametros, $Mifecha);
            $Mifecha = $parametros['FechaTexto'];
            for ($i = 1; $i <= $parametros['Contador']; $i++) {
                $NoDias = intval(date("d", strtotime($Mifecha)));
                $NoMes = intval(date("m", strtotime($Mifecha)));
                $NoAnio = intval(date("Y", strtotime($Mifecha)));
                $Mesl = MesesLetras($NoMes);
                $this->modelo->KeyDown_Insert($parametros, $NoAnio, $NoMes, $Mesl, $Mifecha, $Copiar_Periodo, $Copiar_Mes);
                $Mifecha = new DateTime($Mifecha);
                $Mifecha->add(new DateInterval("P1M"));
                $Mifecha = $Mifecha->format("d/m/Y");
            }
            Eliminar_Nulos_SP("Clientes_Facturacion");
            return array("res" => "1", "msj" => "Proceso Terminado");
        } catch (Exception $e) {
            return array("res" => "0", "msj" => "Error al insertar los datos", 'error' => $e->getMessage());
        }
    }

    public function Multas($parametros)
    {
        try {
            //Actualizar_Abonos_Facturas_SP();
            $Mifecha = $this->Rango_Fechas_Proceso($parametros['Contador'], $parametros['FechaTexto']);
            $this->modelo->Multas_Delete_Update($parametros, $Mifecha);
            $Mifecha = $parametros['FechaTexto'];
            for ($i = 1; $i <= $parametros['Contador']; $i++) {
                $NoDias = intval(date("d", strtotime($Mifecha)));
                $NoMes = intval(date("m", strtotime($Mifecha)));
                $NoAnio = intval(date("Y", strtotime($Mifecha)));
                $Mesl = MesesLetras($NoMes);
                $this->modelo->Multas_Insert($parametros, $NoAnio, $NoMes, $Mifecha, $Mesl);
                $Mifecha = new DateTime($Mifecha);
                $Mifecha->add(new DateInterval("P1M"));
                $Mifecha = $Mifecha->format("d/m/Y");
            }
            Eliminar_Nulos_SP("Clientes_Facturacion");
            return array("res" => "1", "msj" => "Proceso Terminado");
        } catch (Exception $e) {
            return array("res" => "0", "msj" => "Error al insertar los datos", 'error' => $e->getMessage());
        }

    }

    public function Copiar_Mes()
    {
        try {
            $AdoAux = $this->modelo->Copiar_Mes();
            if (count($AdoAux) > 0) {
                return array("res" => "1", "datos" => $AdoAux);
            } else {
                throw new Exception("No se encontraron datos");
            }
        } catch (Exception $e) {
            return array("res" => "0", "msj" => "No se encontraron datos", 'error' => $e->getMessage());
        }
    }

    public function Tipo_Cambio_Valor($parametros)
    {
        try {
            $Mifecha = $parametros['FechaTexto'];
            $Mifecha = $this->Rango_Fechas_Proceso($parametros['Contador'], $Mifecha);
            $this->modelo->Tipo_Cambio_Valor($parametros, $Mifecha);
            return array("res" => "1", "msj" => "Proceso Terminado");
        } catch (Exception $e) {
            return array("res" => "0", "msj" => "Error al cambiar el valor", 'error' => $e->getMessage());
        }
    }

    public function DCInv($query)
    {
        try {
            $datos = $this->modelo->DCInv($query);
            $res = [];
            if (count($datos) == 0) {
                $res[] = 
                [
                    'id' => 0,
                    'text' => 'No se encontraron datos'
                ];
            }else{
                foreach($datos as $row){
                    $res[] = 
                    [
                        'id' => $row['NomProd'],
                        'text' => $row['NomProd']
                    ];
                }
            }
            return ['results' => $res];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function Eliminar_Pensiones($parametros)
    {
        try {
            $Mifecha = $parametros['FechaTexto'];
            $Mifecha = $this->Rango_Fechas_Proceso($parametros['Contador'], $Mifecha);
            $this->modelo->DeleteClientesFacturacion($parametros, $Mifecha);
            return array("res" => "1", "msj" => "Proceso Terminado");
        } catch (Exception $e) {
            return array("res" => "0", "msj" => "Error al eliminar pensiones", 'error' => $e->getMessage());
        }
    }

    public function Existen_Rubros($parametros)
    {
        try {
            $Mifecha = $parametros['FechaTexto'];
            $Titulo = "";
            $Mensaje = "";
            if ($parametros['Codigo1'] === "") {
                $parametros['Codigo1'] = G_NINGUNO;
            }
            if ($parametros['Codigo2'] === "") {
                $parametros['Codigo2'] = G_NINGUNO;
            }

            $Mifecha = $this->Rango_Fechas_Proceso($parametros['Contador'], $Mifecha);
            $AdoAux = $this->modelo->AdoAuxInsertar($parametros, $Mifecha);
            if (count($AdoAux) > 0) {
                $Titulo = "GENERACION DE RUBROS A FACTURAR POR LOTE ";
                $Mensaje = "Actualmente ya existe rubros a facturar en este rango de Grupo y de fechas. \n
                                Realmente desea borrar estos datos he ingresar los nuevos ";
                return array("res" => "1", "titulo" => $Titulo, "msj" => $Mensaje);
            } else {
                throw new Exception("No existen rubros a facturar en este rango de Grupo y de fechas");
            }
        } catch (Exception $e) {
            return array("res" => "0", "msj" => $e->getMessage());
        }
    }

    public function Insertar_Pensiones($parametros)
    {
        try {
            $Mifecha = $parametros['FechaTexto'];
            $NoDiaT = date("d", strtotime($Mifecha));
            if ($parametros['Codigo1'] === "") {
                $parametros['Codigo1'] = G_NINGUNO;
            }
            if ($parametros['Codigo2'] === "") {
                $parametros['Codigo2'] = G_NINGUNO;
            }
            $Mifecha = $this->Rango_Fechas_Proceso($parametros['Contador'], $Mifecha);
            //No se vuelve a comprobar si existen rubros.
            $this->modelo->DeleteClientesFacturacion($parametros, $Mifecha);
            $Mifecha = $parametros['FechaTexto'];

            for ($i = 1; $i <= $parametros['Contador']; $i++) {
                $NoDias = intval(date("d", strtotime($Mifecha)));
                $NoMes = intval(date("m", strtotime($Mifecha)));
                $NoAnio = intval(date("Y", strtotime($Mifecha)));
                $Mesl = MesesLetras($NoMes);
                $this->modelo->InsertClientesFacturacion($parametros, $NoAnio, $NoMes, $Mifecha, $Mesl);
                $Mifecha = new DateTime($Mifecha);
                $Mifecha->add(new DateInterval("P1M"));
                $Mifecha = $Mifecha->format("Y-m-d");
            }
            Eliminar_Nulos_SP("Clientes_Facturacion");
            return array("res" => "1", "msj" => "Proceso Terminado");
        } catch (Exception $e) {
            return array("res" => "0", "msj" => "Error al insertar los datos", 'error' => $e->getMessage());
        }
    }

    /**
     * Método que se encarga de calcular el último dia de una fecha sumado un número de meses determinado
     * @param $numMeses
     * @param $fecha
     * @return DateTime
     */
    private function Rango_Fechas_Proceso($numMeses, $fecha): DateTime
    {
        $fecha = new DateTime($fecha);
        if($numMeses == 1){
            $fecha->modify('last day of this month');
            $fecha->format("Y-m-d");
            return $fecha;
        }else{
            $fecha->add(new DateInterval("P" . $numMeses . "M"));
            $fecha->modify('last day of this month');
            $fecha->format("Y-m-d");
            return $fecha;
        }
    }


}