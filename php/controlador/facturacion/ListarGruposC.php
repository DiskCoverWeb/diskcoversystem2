<?php

require_once(dirname(__DIR__, 2) . "/modelo/facturacion/ListarGruposM.php");
require_once(dirname(__DIR__, 3) . '/lib/fpdf/reporte_de.php');
require(dirname(__DIR__, 3) . '/lib/fpdf/cabecera_pdf.php');
require_once(dirname(__DIR__, 3) . '/lib/phpmailer/enviar_emails.php');

/*
    AUTOR DE RUTINA	: Leonardo Súñiga
    FECHA CREACION	: 06/01/2024
    FECHA MODIFICACION: 20/01/2024
    DESCIPCIÓN		: Controlador de la vista ListarGrupos
*/


$controlador = new ListarGruposC();

if (isset($_GET['ActualizarDatosRepresentantes'])) {
    echo json_encode($controlador->ActualizarDatosRepresentantes());
}

if (isset($_GET['DCGrupos'])) {
    echo json_encode($controlador->DCGrupos());
}

if (isset($_GET['DCTipoPago'])) {
    echo json_encode($controlador->DCTipoPago());
}

if (isset($_GET['DCProductos'])) {
    echo json_encode($controlador->DCProductos());
}

if (isset($_GET['DCLinea'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->DCLinea($parametros));
}

if (isset($_GET['MBFecha_LostFocus'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->MBFecha_LostFocus($parametros));
}

if (isset($_GET['Listar_Grupo'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Listar_Grupo($parametros));
}

if (isset($_GET['Listar_Clientes_Grupo'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Listar_Clientes_Grupo($parametros));
}

if (isset($_GET['DCLinea_LostFocus'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->DCLinea_LostFocus($parametros));
}

if (isset($_GET['Listar_Deuda_por_Api'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Listar_Deuda_por_Api($parametros));
}

if (isset($_GET['Pensiones_Mensuales_Anio'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Pensiones_Mensuales_Anio($parametros));
}

if (isset($_GET['Listado_Becados'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Listado_Becados($parametros));
}

if (isset($_GET['Nomina_Alumnos'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Nomina_Alumnos($parametros));
}

if (isset($_GET['Resumen_Pensiones_Mes'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Resumen_Pensiones_Mes($parametros));
}

if (isset($_GET['Listar_Clientes_Email'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Listar_Clientes_Email($parametros));
}

if (isset($_GET['Command5_Click'])) {
    try {
        $parametros = json_decode($_POST['parametros'], true);
        if (isset($_FILES['archivoEmail']) && $_FILES['archivoEmail']['error'] == UPLOAD_ERR_OK) {
            $archivo = $_FILES['archivoEmail'];
            $carpetaDestino = dirname(__DIR__, 3) . "/TEMP/";

            if (!is_dir($carpetaDestino)) {
                // Intentar crear la carpeta, el 0777 es el modo de permiso más permisivo
                if (!mkdir($carpetaDestino, 0777, true)) { // true permite la creación de estructuras de directorios anidados
                    throw new Exception("No se pudo crear la carpeta");
                }
            }

            $nombreArchivoDestino = $carpetaDestino . basename($archivo['name']);
            if (move_uploaded_file($archivo['tmp_name'], $nombreArchivoDestino)) {
                $parametros['archivoEmail'] = $nombreArchivoDestino;
                echo json_encode($controlador->tmp($parametros));
            } else {
                throw new Exception("No se pudo guardar el archivo");
            }

        } else {
            echo json_encode($controlador->tmp($parametros));
        }
    } catch (Exception $e) {
        echo json_encode(array("res" => 0, "mensaje" => "Error al enviar los correos", "error" => $e->getMessage()));
    }


}

if (isset($_GET['GenerarFacturas_Click'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->GenerarFacturas_Click($parametros));
}

if (isset($_GET['ProcGrabarMult'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->ProcGrabarMult($parametros));
}

if (isset($_GET['Listado_x_Grupos'])) {
    echo json_encode($controlador->Listado_x_Grupos());
}

if (isset($_GET['Recalcular_Fechas'])) {
    echo json_encode($controlador->Recalcular_Fechas());
}

if (isset($_GET['Impresora'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Impresora($parametros));
}

if (isset($_GET['Recibos'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Recibos($parametros));
}

if (isset($_GET['Excel'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Excel($parametros));
}

if (isset($_GET['Update_Direccion'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Update_Direccion($parametros));
}

if (isset($_GET['Update_Grupo'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Update_Grupo($parametros));
}

if (isset($_GET['Desactivar_Grupo'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Desactivar_Grupo($parametros));
}

if (isset($_GET['Eliminar_Rubros_Facturacion'])) {
    echo json_encode($controlador->Eliminar_Rubros_Facturacion());
}



class ListarGruposC
{

    private $modelo;
    private $pdf;

    private $email;


    public function __construct()
    {
        $this->modelo = new ListarGruposM();
        $this->pdf = new cabecera_pdf();
        $this->email = new enviar_emails();

    }

    public function Retirar_Beneficiarios($parametros)
    {
        try {
            $this->modelo->Retirar_Beneficiarios($parametros);
            return array("res" => 1, "msj" => "Beneficiarios retirados correctamente");
        } catch (Exception $e) {
            return array("res" => 0, "msj" => "Error al retirar beneficiarios", "error" => $e->getMessage());
        }
    }

    public function Eliminar_Rubros_Facturacion()
    {
        try {
            $this->modelo->Eliminar_Rubros_Facturacion();
            return array("res" => 1, "msj" => "Rubros eliminados correctamente");
        } catch (Exception $e) {
            return array("res" => 0, "msj" => "Error al eliminar rubros", "error" => $e->getMessage());
        }
    }

    public function Desactivar_Grupo($parametros)
    {
        try {
            $this->modelo->Desactivar_Grupo($parametros);
            return array("res" => 1, "msj" => "Grupo desactivado correctamente");
        } catch (Exception $e) {
            return array("res" => 0, "msj" => "Error al desactivar", "error" => $e->getMessage());
        }
    }

    public function Update_Grupo($parametros)
    {
        try {
            $this->modelo->Update_Grupo($parametros);
            return array("res" => 1, "msj" => "Grupo actualizado correctamente");
        } catch (Exception $e) {
            return array("res" => 0, "msj" => "Error al actualizar", "error" => $e->getMessage());
        }
    }

    public function Update_Direccion($parametros)
    {
        try {
            $this->modelo->Update_Direccion($parametros);
            return array("res" => 1, "msj" => "Direccion actualizada correctamente");
        } catch (Exception $e) {
            return array("res" => 0, "msj" => "Error al actualizar", "error" => $e->getMessage());
        }
    }

    public function Excel($parametros)
    {
        $AdoQuery = $parametros['AdoQuery'];
        try {
            if (count($AdoQuery) > 0) {
                $ruta = strtoupper(dirname(__DIR__, 3) . "/TEMP/Reporte_Excel" . date('Y-m-d_H-i-s') . ".XLSX");
                Exportar_AdoDB_Excel($AdoQuery, $ruta);
                return array('res' => 1, 'fileName' => basename($ruta));
            } else {
                throw new Exception("No se puede exportar a excel porque no hay datos");
            }
        } catch (Exception $e) {
            return array('res' => 0, 'mensaje' => $e->getMessage());
        }

    }

    public function Recibos($parametros)
    {
        $Opcion = intval($parametros['Opcion']);
        try {
            switch ($Opcion) {
                case 0:
                    $AdoAux = $this->modelo->Imprimir_Recibos_Cobros($parametros);
                    if (count($AdoAux) > 0) {
                        //TODO: Imprimir_Recibos_CxC_PreFA
                        $ruta = ImprimirAdodc($AdoAux, 9);
                        return array('res' => 1, 'fileName' => basename($ruta));
                    } else {
                        throw new Exception("No se puede imprimir el rango de recibos porque no hay datos");
                    }
                case 1:
                    $Codigo1 = $parametros['DCCliente'];
                    $Codigo2 = $parametros['DCCliente'];
                    if ($Codigo1 == "")
                        $Codigo1 = G_NINGUNO;
                    if ($Codigo2 == "")
                        $Codigo2 = G_NINGUNO;
                    $AdoAux = $this->modelo->Recibos_Case1($parametros, $Codigo1, $Codigo2);
                    if (count($AdoAux) > 0) {
                        //TODO: Imprimir_Recibos_CxC_PreFA
                        $ruta = ImprimirAdodc($AdoAux, 9);
                        return array('res' => 1, 'fileName' => basename($ruta));
                    } else {
                        throw new Exception("No se puede imprimir el rango de recibos porque no hay datos");
                    }
            }
        } catch (Exception $e) {
            return array('res' => 0, 'mensaje' => $e->getMessage());
        }
    }

    public function Impresora($parametros)
    {
        $Opcion = intval($parametros['Opcion']);
        $AdoQuery = $parametros['AdoQuery'];
        try {
            switch ($Opcion) {
                case 0:
                    $ruta = ImprimirAdodc($AdoQuery, 7, ['Cliente', 'Grupo', 'Direccion']);
                    return array('res' => 1, 'fileName' => basename($ruta));
                case 1:
                    $ruta = '';
                    //TODO: Imprimir_CxC_Grupos
                    return array('res' => 1, 'fileName' => $ruta);
                case 2:
                    $ruta = ImprimirAdodc($AdoQuery, 7, ['Cliente', 'Grupo', 'Direccion']);
                    return array('res' => 1, 'fileName' => basename($ruta));
                default:
                    throw new Exception("Opcion no valida");
            }
        } catch (Exception $e) {
            return array('res' => 0, 'mensaje' => $e->getMessage());
        }
    }

    public function Recalcular_Fechas()
    {
        try {
            $this->modelo->Recalcular_Fechas();
            return array('res' => 1, 'mensaje' => 'Fechas recalculadas correctamente');
        } catch (Exception $e) {
            return array('res' => 0, 'mensaje' => $e->getMessage());
        }
    }

    public function Listado_x_Grupos()
    {
        try {
            $AdoNiveles = $this->modelo->Listado_x_Grupos();
            $ruta = ImprimirAdodc($AdoNiveles);
            return array('res' => 1, 'mensaje' => 'Pdf generado correctamente', 'fileName' => basename($ruta));
        } catch (Exception $e) {
            return array('res' => 0, 'mensaje' => $e->getMessage());
        }
    }

    public function GenerarFacturas_Click($parametros)
    {
        $FA = Encerar_Factura($parametros['FA']);
        $FA['Tipo_Pago'] = $parametros['DCTipoPago'];
        $Mensaje = "";
        $Res = 1;
        $Titulo = "";
        if (strlen($FA['Tipo_Pago']) == 1) {
            $Mensaje = "NO HA SELECCIONADO LA FORMA DE PAGO";
            $Res = 0;
        } else {
            $FA['Cod_CxC'] = $parametros['DCLinea'];
            $tmp = Lineas_De_CxC($FA);
            $FA = array_merge($FA, $tmp['TFA']);
            $tempVenc = '';
            if($FA['Vencimiento'] instanceof DateTime){
                $tempVenc = $FA['Vencimiento']->getTimestamp();
            }else{
                $tempVenc = strtotime($FA['Vencimiento']);
            }
            if (strtotime($parametros['MBFechaI']) > $tempVenc) {
                $Mensaje = "No se puede General Facturas, porque la autorizacion ya esta caducada";
                $Res = 0;
            } else {
                if ($parametros['CTipoConsulta'] == "2") {
                    $FA['Factura'] = ReadSetDataNum($FA['TC'] . "_SERIE_" . $FA['Serie'], true, false);
                    $Mensaje = "Esta Seguro de grabar desde \n";
                    if ($parametros['TipoFactura'] == "NV") {
                        $Mensaje .= "La Nota de Venta No. " . $FA['Serie'] . "-" . $FA['Factura'];
                    } else {
                        $Mensaje .= "La Factura No. " . $FA['Serie'] . "-" . $FA['Factura'];
                    }
                    $Mensaje .= " en bloque ";
                    $Titulo = "Formulario de Grabación";
                    $Res = 1;
                    //Se vuelve a la vista, se pregunta con un swal.fire si se desea grabar
                }
            }
        }
        return array('response' => $Res, 'Mensaje' => $Mensaje, 'Titulo' => $Titulo, 'FA' => $FA);
    }

    public function ProcGrabarMult($parametros): array
    {
        $FA = $parametros['FA'];
        $FA['Porc_IVA'] = floatval($parametros['PorcIva'] / 100);
        $NoMes = date('m', strtotime($parametros['MBFechaI']));
        $Periodo_Facturacion = (string) date('Y', strtotime($parametros['MBFechaI']));
        $CodigoL = G_NINGUNO;
        $Cta_Ventas = G_NINGUNO;
        try {
            $tmp = $this->modelo->ProcGrabarMult($parametros, $NoMes, $Periodo_Facturacion, $FA, $CodigoL, $Cta_Ventas);
            $Res = 1;
            return array('Mensaje' => $tmp['mensaje'], 'Res' => $Res, 'datos' => $tmp['datos'], 'numRegistros' => $tmp['numRegistros']);
        } catch (Exception $e) {
            $Mensaje = "No se puede grabar la Factura" . $e->getMessage();
            $Res = 0;
            return array('Mensaje' => $Mensaje, 'Res' => $Res);
        }

    }

    public function Command5_Click($parametros)
    {
        $AdoAux = $this->modelo->Command5_Click($parametros, $parametros['ListaDeCampos']);
        $TMailPara = "";
        $TMailAsunto = "";
        $TMailDestinatario = "";
        $TMailMensaje = "";
        $TMailTipoDeEnvio = "";
        if (strlen($parametros['TxtAsunto']) > 1) {
            $TMailAsunto = $parametros['TxtAsunto'];
        }
        if (count($AdoAux) > 0) {
            foreach ($AdoAux as $key => $value) {
                $NombreRepresentante = $value['Representante'];
                $NombreCli = $value['Cliente'];
                $Codigo_Banco = $value['CI_RUC'];
                $Curso = $value['Detalle_Grupo'];
                $ListaMails = Insertar_Mail($TMailPara, $value['EmailR']);
                $ListaMails .= Insertar_Mail($TMailPara, $value['Email']);
                $TMailPara = $value['Email'];
                $Grupo_No = $value['GrupoNo'];
                $TMailDestinatario = $NombreRepresentante;

                if (strlen($parametros['TxtMensaje']) > 1) {
                    $TMailMensaje = $parametros['TxtMensaje'];
                }

                if ($parametros['CheqConDeuda'] <> 0) {
                    $CadDeuda = "";
                    $SubTotal = 0;
                    $columnNames = array_keys($value); // Obtener los nombres de las columnas del array asociativo
                    for ($J = 2; $J < count($columnNames) - 7; $J++) {
                        $columnName = $columnNames[$J]; // El nombre de la columna en la posición J
                        $SubTotal = $SubTotal + $value["Total"];
                        $Cadena = number_format($value[$columnName], 2, ".", ",");
                        $Cadena = str_repeat(" ", 14 - strlen($Cadena)) . $Cadena;
                        if (floatval($value[$columnName]) > 0) {
                            $CadDeuda .= $columnName . " USD " . $Cadena . "\n"; // Añade el nombre de la columna, el valor formateado y un salto de línea
                        }
                    }
                    if (strlen($CadDeuda) > 1) {
                        $TMailMensaje .= "\n";
                        if (strlen($NombreRepresentante) > 1) {
                            $TMailMensaje .= "Estimado(a): " . $NombreRepresentante . ", de su representado(a) " . $NombreCli . " del " . $Curso . ", ";
                        } else {
                            $TMailMensaje .= "Estimado(a), su representado(a) " . $NombreCli . ", Ubicacion: " . $Grupo_No . ", ";
                        }
                        $TMailMensaje .= "tiene los siguientes pendientes por cancelar:\n" . $CadDeuda .
                            "SU CODIGO DE REFERENCIA ES: " . $Codigo_Banco . "\n" .
                            "Cualquier consulta comuniquese al teléfono: " . $_SESSION['INGRESO']['Telefono1'];
                        //$this->email->enviar_email(false, $TMailPara, $TMailMensaje, $TMailAsunto, false);
                    }
                }
                $TMailTipoDeEnvio = "CE";
            }
        }
        return array('TMailPara' => $TMailPara, 'TMailAsunto' => $TMailAsunto, 'TMailDestinatario' => $TMailDestinatario, 'TMailMensaje' => $TMailMensaje, 'TMailTipoDeEnvio' => $TMailTipoDeEnvio);
    }

    public function tmp($parametros)
    {
        try {
            $AdoAux = $this->modelo->Command5_Click($parametros, $parametros['ListaDeCampos']);
            $TMailPara = "";
            $TMailAsunto = "";
            $TMailDestinatario = "";
            $TMailMensaje = "";
            if (strlen($parametros['TxtAsunto']) > 1) {
                $TMailAsunto = $parametros['TxtAsunto'];
            }
            if (count($AdoAux) > 0) {
                foreach ($AdoAux as $key => $value) {
                    $NombreRepresentante = $value['Representante'];
                    $NombreCli = $value['Cliente'];
                    $Codigo_Banco = $value['CI_RUC'];
                    $Curso = $value['Detalle_Grupo'];
                    $ListaMails = Insertar_Mail($TMailPara, $value['EmailR']);
                    $ListaMails .= Insertar_Mail($TMailPara, $value['Email']);
                    $TMailPara = $value['Email'];
                    $Grupo_No = $value['GrupoNo'];
                    $TMailDestinatario = $NombreRepresentante;

                    if (strlen($parametros['TxtMensaje']) > 1) {
                        $TMailMensaje = $parametros['TxtMensaje'];
                    }

                    if ($parametros['CheqConDeuda'] <> 0) {
                        $CadDeuda = "";
                        $SubTotal = 0;
                        $columnNames = array_keys($value); // Obtener los nombres de las columnas del array asociativo
                        for ($J = 2; $J < count($columnNames) - 7; $J++) {
                            $columnName = $columnNames[$J]; // El nombre de la columna en la posición J
                            $SubTotal = $SubTotal + $value["Total"];
                            $Cadena = number_format($value[$columnName], 2, ".", ",");
                            $Cadena = str_repeat(" ", 14 - strlen($Cadena)) . $Cadena;
                            if (floatval($value[$columnName]) > 0) {
                                $CadDeuda .= $columnName . " USD " . $Cadena . "\n"; // Añade el nombre de la columna, el valor formateado y un salto de línea
                            }
                        }
                        if (strlen($CadDeuda) > 1) {
                            $TMailMensaje .= "\n";
                            if (strlen($NombreRepresentante) > 1) {
                                $TMailMensaje .= "Estimado(a): " . $NombreRepresentante . ", de su representado(a) " . $NombreCli . " del " . $Curso . ", ";
                            } else {
                                $TMailMensaje .= "Estimado(a), su representado(a) " . $NombreCli . ", Ubicacion: " . $Grupo_No . ", ";
                            }
                            $TMailMensaje .= "tiene los siguientes pendientes por cancelar:\n" . $CadDeuda .
                                "SU CODIGO DE REFERENCIA ES: " . $Codigo_Banco . "\n" .
                                "Cualquier consulta comuniquese al teléfono: " . $_SESSION['INGRESO']['Telefono1'];
                            if (isset($parametros['archivoEmail'])) {
                                $this->email->enviar_email(array(baseName($parametros['archivoEmail'])), $TMailPara, $TMailMensaje, $TMailAsunto, false);
                                return array('res' => 1, 'mensaje' => 'Correo enviado correctamente');
                            } else {
                                $this->email->enviar_email(false, $TMailPara, $TMailMensaje, $TMailAsunto, false);
                                return array('res' => 1, 'mensaje' => 'Correo enviado correctamente');
                            }
                        } else {
                            throw new Exception("No se encontraron deudas para enviar");
                        }
                    }else{
                        return array('res' => 1, 'mensaje' => 'Datos actualizados correctamente');
                    }
                }
            } else {
                throw new Exception("No se puede enviar correos porque no hay datos");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function Listar_Clientes_Email($parametros)
    {
        $Codigo1 = $parametros['Codigo1'];
        $Codigo2 = $parametros['Codigo2'];
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $SubTotal = 0;
        $Diferencia = 0;
        $TotalIngreso = 0;
        $ListaCampos = '.';
        $dataSP = Reporte_CxC_Cuotas_SP($Codigo1, $Codigo2, $MBFechaI, $MBFechaF, $SubTotal, $Diferencia, $TotalIngreso, $ListaCampos, $parametros['CheqResumen'], $parametros['CheqVenc']);
        $SubTotal = $dataSP['SubTotal'];
        $Diferencia = $dataSP['TotalAnticipo'];
        $TotalIngreso = $dataSP['TotalCxC'];
        $ListaDeCampos = $dataSP['ListaDeCampos'];
        $ListaDeCampos = str_replace("Cliente,", "RCC.Cliente", $ListaDeCampos);
        $ListaDeCampos = str_replace("GrupoNo,", "RCC.GrupoNo", $ListaDeCampos);
        $tmp = $this->modelo->Listar_Clientes_Grupo($parametros);
        $AdoQuery = $tmp['AdoQuery'];
        $LstClientes = [];
        $LstClientes[] = array('Cliente' => 'TODOS', 'Email' => 'EMAIL', 'SaldoPendiente' => 'SALDO PENDIENTE');
        if ($parametros['PorGrupo'] || $parametros['PorDireccion']) {
            if (count($AdoQuery) > 0) {
                foreach ($AdoQuery as $key => $value) {
                    $sSaldo_Pendiente = number_format($value['Saldo_Pendiente'], 2, '.', ',');
                    //$DeudaCliente = $value['Cliente'] . str_pad(" ", 80 - strlen($value['Cliente'])) . str_pad(" ", 15 - strlen($sSaldo_Pendiente)) . $sSaldo_Pendiente;
                    $Email = '';
                    if (strlen($value['EmailR']) > 1) {
                        $Email = $value['EmailR'];
                    } else {
                        $Email = $value['Email'];
                    }
                    $LstClientes[] = array('Cliente' => $value['Cliente'], 'Email' => $Email, 'SaldoPendiente' => $sSaldo_Pendiente);
                }
            }
        }
        return array('LstClientes' => $LstClientes, 'numRegistros' => count($LstClientes), 'AdoQuery' => $AdoQuery, 'ListaDeCampos' => $ListaDeCampos);
    }

    public function Resumen_Pensiones_Mes($parametros)
    {
        $FechaIni = BuscarFecha($parametros['MBFechaI']);
        $FechaFin = BuscarFecha($parametros['MBFechaF']);
        $AdoQuery = $this->modelo->Resumen_Pensiones_Mes($parametros, $FechaIni, $FechaFin);
        return array('tbl' => $AdoQuery['datos'], 'numRegistros' => count($AdoQuery['AdoQuery']), 'AdoQuery' => $AdoQuery['AdoQuery']);
    }

    public function Nomina_Alumnos($parametros)
    {
        $AdoQuery = $this->modelo->Nomina_Alumnos($parametros);
        return array('tbl' => $AdoQuery['datos'], 'numRegistros' => count($AdoQuery['AdoQuery']), 'AdoQuery' => $AdoQuery['AdoQuery']);
    }

    public function Pensiones_Mensuales_Anio($parametros)
    {
        $FechaIni = BuscarFecha($parametros['MBFechaI']);
        $FechaFin = BuscarFecha($parametros['MBFechaF']);
        $Codigo1 = $parametros['Codigo1'];
        $Codigo2 = $parametros['Codigo2'];
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $SubTotal = 0;
        $Diferencia = 0;
        $TotalIngreso = 0;
        $ListaCampos = '.';
        $dataSP = Reporte_CxC_Cuotas_SP($Codigo1, $Codigo2, $MBFechaI, $MBFechaF, $SubTotal, $Diferencia, $TotalIngreso, $ListaCampos, $parametros['CheqResumen'], $parametros['CheqVenc']);
        $SubTotal = $dataSP['SubTotal'];
        $Diferencia = $dataSP['TotalAnticipo'];
        $TotalIngreso = $dataSP['TotalCxC'];
        $ListaCampos = $dataSP['ListaDeCampos'];
        $AdoQuery = $this->modelo->Pensiones_Mensuales_Anio($ListaCampos);
        $Caption9 = number_format($SubTotal, 2, '.', ',');
        $Caption10 = number_format($Diferencia, 2, '.', ',');
        $Caption4 = number_format($TotalIngreso, 2, '.', ',');
        return array('tbl' => $AdoQuery['datos'], 'numRegistros' => count($AdoQuery['AdoQuery']), 'Caption9' => $Caption9, 'Caption10' => $Caption10, 'Caption4' => $Caption4, 'AdoQuery' => $AdoQuery['AdoQuery']);
    }

    public function Listado_Becados($parametros)
    {
        $FechaIni = BuscarFecha($parametros['MBFechaI']);
        $FechaFin = BuscarFecha($parametros['MBFechaF']);
        $AdoQuery = $this->modelo->Listado_Becados($parametros, $FechaIni, $FechaFin);
        return array('tbl' => $AdoQuery['datos'], 'numRegistros' => count($AdoQuery['AdoQuery']), 'AdoQuery' => $AdoQuery['AdoQuery']);
    }

    public function Listar_Deuda_por_Api($parametros)
    {
        $FechaTope = BuscarFecha(FechaSistema());
        if ($parametros['CheqVenc'] <> 0) {
            $FechaTope = BuscarFecha($parametros['MBFechaF']);
        }
        $data = $this->modelo->Listar_Deuda_por_Api($parametros, $FechaTope);
        $Total = 0;
        if (count($data['AdoQuery']) > 0) {
            foreach ($data['AdoQuery'] as $key => $value) {
                $Total += $value['Saldo_Pendiente'];
            }
        }
        return array('tbl' => $data['datos'], 'Caption' => number_format($Total, 2, '.', ','), 'numRegistros' => count($data['AdoQuery']), 'AdoQuery' => $data['AdoQuery']);
    }

    public function DCLinea_LostFocus($parametros)
    {
        $FA = variables_tipo_factura();
        if (isset($parametros['FA']) && is_array($parametros['FA'])) {
            // Fusionar los valores de $FA con los valores de $parametros['FA']
            $FA = array_merge($FA, $parametros['FA']);
        }
        $tmp = Lineas_De_CxC($FA);
        $Caption = "Linea de Facturacion: " . str_repeat(" ", 8) . $tmp['TFA']['Serie'] . "-" . sprintf("%09d", ReadSetDataNum($tmp['TFA']['TC'] . "_SERIE_" . $tmp['TFA']['Serie'], true, false));
        return array('tmp' => $tmp, 'Caption' => $Caption);
    }

    public function Listar_Grupo($parametros)
    {
        return $this->modelo->Listar_Grupo($parametros);
    }

    public function MBFecha_LostFocus($parametros)
    {
        return $this->modelo->MBFecha_LostFocus($parametros);
    }

    public function Listar_Clientes_Grupo($parametros)
    {
        $AdoQuery = $this->modelo->Listar_Clientes_Grupo($parametros);
        return array('tbl' => $AdoQuery['datos'], 'numRegistros' => count($AdoQuery['AdoQuery']), 'AdoQuery' => $AdoQuery['AdoQuery']);
    }

    public function DCLinea($parametros)
    {
        $datos = $this->modelo->DCLinea($parametros);
        $fecha = date('Y-m-d', strtotime($parametros['MBFechaI'] . ' + 365 days'));
        return array('datos' => $datos, 'fecha' => $fecha);
    }

    public function DCProductos()
    {
        return $this->modelo->DCProductos();
    }

    public function ActualizarDatosRepresentantes()
    {
        Actualizar_Datos_Representantes_SP($_SESSION['INGRESO']['Mas_Grupos']);
    }

    public function DCGrupos()
    {
        return $this->modelo->DCGrupos();
    }

    public function DCTipoPago()
    {
        return $this->modelo->DCTipoPago();
    }
}