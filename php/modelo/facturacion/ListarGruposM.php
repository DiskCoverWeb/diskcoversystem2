<?php
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;

require_once (dirname(__DIR__, 2) . "/db/db1.php");
require_once (dirname(__DIR__, 2) . "/funciones/funciones.php");

/*
    AUTOR DE RUTINA	: Leonardo Súñiga
    FECHA CREACION	: 06/01/2024
    FECHA MODIFICACION: 20/01/2024
    DESCIPCIÓN		: Modelo ListarGrupos
*/

class ListarGruposM
{
    private $db;

    public function __construct()
    {

        $this->db = new db();

    }

    public function DCGrupos()
    {
        $sql = "SELECT Grupo
                FROM Clientes
                WHERE FA <> 0";
        if ($_SESSION['INGRESO']['Mas_Grupos']) {
            $sql .= " AND DirNumero = '" . $_SESSION['INGRESO']['item'] . "'";
        }
        $sql .= "GROUP BY Grupo
                ORDER BY Grupo";
        return $this->db->datos($sql);
    }

    public function DCTipoPago()
    {
        $sql = "SELECT (Codigo + ' ' + Descripcion) As CTipoPago
                FROM Tabla_Referenciales_SRI
                WHERE Tipo_Referencia = 'FORMA DE PAGO'
                AND Codigo IN ('01','16','17','18','19','20','21')
                ORDER BY Codigo";
        return $this->db->datos($sql);
    }

    public function DCProductos()
    {
        $sql = "SELECT *
                FROM Catalogo_Productos
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND TC = 'P'
                AND LEN(Cta_Inventario) = 1 
                AND INV <> 0
                ORDER BY Producto";
        return $this->db->datos($sql);
    }

    public function DCLinea($parametros)
    {
        $sql = "SELECT *
                FROM Catalogo_Lineas
                WHERE TL <> 0 
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND Fact = '" . $parametros['TipoFactura'] . "'
                AND Fecha <= '" . BuscarFecha($parametros['MBFechaI']) . "'
                AND Vencimiento >= '" . BuscarFecha($parametros['MBFechaI']) . "'
                ORDER BY Codigo";
        return $this->db->datos($sql);
    }

    public function MBFecha_LostFocus($parametros)
    {
        $sql = "SELECT *
                FROM Catalogo_Lineas
                WHERE TL <> 0 
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND Fact = '" . $parametros['TipoFactura'] . "'
                AND Fecha <= '" . BuscarFecha($parametros['MBFecha']) . "'
                AND Vencimiento >= '" . BuscarFecha($parametros['MBFecha']) . "'
                ORDER BY Codigo";
        return $this->db->datos($sql);
    }

    public function Listar_Grupo($parametros)
    {
        if ($parametros['PorDireccion']) {
            $sql = "SELECT TOP 100 Direccion
                    FROM Clientes
                    WHERE FA <> 0";
            if ($_SESSION['INGRESO']['Mas_Grupos']) {
                $sql .= " AND DirNumero = '" . $_SESSION['INGRESO']['item'] . "'";
            }
            $sql .= "GROUP BY Direccion
                    ORDER BY Direccion";
            return $this->db->datos($sql);
        } else {
            $sql = "SELECT TOP 100 Grupo
                    FROM Clientes
                    WHERE FA <> 0";
            if ($_SESSION['INGRESO']['Mas_Grupos']) {
                $sql .= " AND DirNumero = '" . $_SESSION['INGRESO']['item'] . "'";
            }
            $sql .= "GROUP BY Grupo
                    ORDER BY Grupo";
            return $this->db->datos($sql);
        }
    }

    public function Listar_Clientes_Grupo($parametros)
    {
        $titulo = "";
        $sql = "SELECT TOP 100 T,Cliente,Grupo,Direccion,Codigo,CI_RUC,Email,Email2,Fecha_N,Representante,TD_R, CI_RUC_R,DireccionT,Telefono_R,TelefonoT,EmailR,Saldo_Pendiente
                FROM Clientes
                WHERE Cliente <> '.' ";
        if ($_SESSION['INGRESO']['Mas_Grupos']) {
            $sql .= " AND DirNumero = '" . $_SESSION['INGRESO']['item'] . "'";
        }
        if ($parametros['CheqRangos'] <> 0) {
            $sql .= " AND Grupo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "'";
        } else {
            if ($parametros['PorGrupo']) {
                $titulo = "LISTADO DE CLIENTES (Grupo No. " . $parametros['DCCliente'] . ")";
                $sql .= " AND Grupo = '" . $parametros['DCCliente'] . "'";
            } else if ($parametros['PorDireccion']) {
                $titulo = "LISTADO DE CLIENTES (Direccion: " . $parametros['DCCliente'] . ")";
                $sql .= " AND Direccion = '" . $parametros['DCCliente'] . "'";
            } else {
                $titulo = "LISTADO DE CLIENTES";
            }
        }
        $sql .= "AND FA <> 0
                ORDER BY Grupo,Cliente";
        $AdoQuery = $this->db->datos($sql);
        $datos = grilla_generica_new($sql, 'Clientes', '', $titulo, false, false, false, 1, 1, 1, 100);
        return array('datos' => $datos, 'AdoQuery' => $AdoQuery);
    }

    public function Listar_Deuda_Por_Api($parametros, $FechaTope)
    {
        $sql = "UPDATE Clientes
                SET Saldo_Pendiente = 0, Credito = 0
                WHERE Codigo <> '.'";
        Ejecutar_SQL_SP($sql);

        $sql = "UPDATE Clientes
                SET Saldo_Pendiente = (SELECT ROUND(SUM(CF.Valor-CF.Descuento-CF.Descuento2),2,0)
                                       FROM Clientes_Facturacion As CF
                                       WHERE CF.Item = '" . $_SESSION['INGRESO']['item'] . "'
                                       AND CF.Fecha <= '" . $FechaTope . "'
                                       AND CF.Codigo = Clientes.Codigo)
                WHERE Codigo <> '.'";
        if ($parametros['CheqRangos']) {
            $sql .= " AND Grupo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "'";
        }
        Ejecutar_SQL_SP($sql);

        $sql = "UPDATE Clientes
                SET Fecha_Cad = (SELECT MIN(CF.Fecha)
                                 FROM Clientes_Facturacion As CF
                                 WHERE CF.Item = '" . $_SESSION['INGRESO']['item'] . "'
                                 AND CF.Fecha <= '" . $FechaTope . "'
                                 AND CF.Codigo = Clientes.Codigo)
                WHERE Codigo <> '.'";
        if ($parametros['CheqRangos']) {
            $sql .= " AND Grupo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "'";
        }
        Ejecutar_SQL_SP($sql);

        $sql = "UPDATE Clientes
                SET Saldo_Pendiente = 0
                WHERE Saldo_Pendiente IS NULL";
        Ejecutar_SQL_SP($sql);

        $sql = "UPDATE Clientes
                SET Fecha_Cad = '" . $FechaTope . "'
                WHERE Fecha_Cad IS NULL";
        Ejecutar_SQL_SP($sql);

        $sql = "UPDATE Clientes
                SET Credito = DATEDIFF(day,Fecha_Cad,'" . $FechaTope . "')
                WHERE Codigo <> '.'";
        Ejecutar_SQL_SP($sql);

        $sql = "SELECT TOP 100 Grupo, Cliente As Estudiante, CI_RUC As Cedula, Saldo_Pendiente, Credito As Dias_Mora, EmailR, Codigo
                FROM Clientes
                WHERE FA <> 0";
        if ($parametros['CheqRangos']) {
            $sql .= " AND Grupo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "'";
        }
        $sql .= "ORDER BY Grupo, Cliente";
        $AdoQuery = $this->db->datos($sql);
        $datos = grilla_generica_new($sql, 'Clientes', '', 'LISTADO DE CLIENTES', false, false, false, 1, 1, 1, 100);
        return array('datos' => $datos, 'AdoQuery' => $AdoQuery);
    }

    public function Pensiones_Mensuales_Anio($ListaCampos)
    {
        $sql = "SELECT TOP 100 " . $ListaCampos . " 
                FROM Reporte_CxC_Cuotas
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'
                ORDER BY GrupoNo,Cliente";
        //print_r($sql);die();
        $AdoQuery = $this->db->datos($sql);
        $datos = grilla_generica_new($sql, 'Reporte_CxC_Cuotas', '', 'PENSIONES MENSUALES DEL AÑO', false, false, false, 1, 1, 1, 100);
        return array('datos' => $datos, 'AdoQuery' => $AdoQuery);
    }

    public function Listado_Becados($parametros, $FechaIni, $FechaFin)
    {
        $sql = "SELECT TOP 100 C.Cliente As Estudiantes,C.Grupo,CF.Mes,CF.Valor,CF.Descuento,CF.Descuento2,(CF.Valor-(CF.Descuento+CF.Descuento2)) As Total_Pagar,(((CF.Descuento+CF.Descuento2)/CF.Valor)*100) As Porc
                FROM Clientes As C, Clientes_Facturacion As CF
                WHERE CF.Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CF.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                AND (CF.Descuento+CF.Descuento2) <> 0";
        if ($parametros['CheqRangos'] <> 0) {
            $sql .= "AND C.Grupo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "'";
        } else {
            if ($parametros['PorGrupo']) {
                $sql .= "AND C.Grupo = '" . $parametros['DCCliente'] . "'";
            } else if ($parametros['PorDireccion']) {
                $sql .= "AND C.Direccion = '" . $parametros['DCCliente'] . "'";
            }
        }
        $sql .= "AND CF.Codigo = C.Codigo
                 ORDER BY C.Grupo,C.Cliente,CF.Num_Mes";
        //print_r($sql);die();
        $AdoQuery = $this->db->datos($sql);
        $datos = grilla_generica_new($sql, 'Clientes_Facturacion', '', 'LISTADO DE BECADOS', false, false, false, 1, 1, 1, 100);
        return array('datos' => $datos, 'AdoQuery' => $AdoQuery);
    }

    public function Nomina_Alumnos($parametros)
    {
        $sql = "SELECT TOP 100 C.Cliente As Estudiantes,' ' As T_1,' ' As T_2,' ' As T_3,' ' As T_4,' ' As T_5,C.Grupo,C.Direccion,C.Email,Count(DF.Codigo) As No_Facturas
                FROM Clientes AS C,Detalle_Factura As DF
                WHERE C.Cliente <> '.'";
        if ($parametros['PorGrupo']) {
            $sql .= "AND C.Grupo = '" . $parametros['DCCliente'] . "'";
        } elseif ($parametros['PorDireccion']) {
            $sql .= "AND C.Direccion = '" . $parametros['DCCliente'] . "'";
        }
        if ($parametros['CheqRangos'] <> 0) {
            $sql .= "AND C.Grupo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "'";
        }
        /*TODO: Ver donde se define Codigo3
        if($parametros['DCProductosVisible']){
            $sql .= "AND DF.Codigo ="
        }*/
        if ($parametros['OpcActivos']) {
            $sql .= "AND C.T = 'N'";
        } else {
            $sql .= "AND C.T <> 'N'";
        }
        $sql .= "AND C.FA <> 0
                 AND DF.T <> '" . G_ANULADO . "'
                 AND DF.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                 AND DF.Item = '" . $_SESSION['INGRESO']['item'] . "'
                 AND C.Codigo = DF.CodigoC
                 GROUP BY C.Grupo,C.Cliente,C.Direccion,C.Email
                 ORDER BY C.Grupo,C.Cliente";
        $AdoQuery = $this->db->datos($sql);
        $datos = grilla_generica_new($sql, 'Clientes', '', 'NOMINA DE ALUMNOS', false, false, false, 1, 1, 1, 100);
        return array('datos' => $datos, 'AdoQuery' => $AdoQuery);
    }

    public function Resumen_Pensiones_Mes($parametros, $FechaIni, $FechaFin)
    {
        $sql = "SELECT TOP 100 CF.Periodo,COUNT(CP.Producto) AS Cant,CF.GrupoNo,CP.Producto,SUM(CF.Valor-(CF.Descuento+CF.Descuento2)) As Total
                FROM Clientes_Facturacion As CF,Catalogo_Productos As CP
                WHERE CP.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND CP.Item = '" . $_SESSION['INGRESO']['item'] . "'";
        if (date('m', strtotime($parametros['MBFechaI'])) == date('m', strtotime($parametros['MBFechaF']))) {
            $sql .= "AND CF.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'";
        } else {
            $sql .= "AND CF.Fecha <= '" . $FechaFin . "'";
        }
        $sql .= "AND CF.GrupoNo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "'
                 AND CF.Codigo_Inv = CP.Codigo_Inv
                 AND CF.Item = CP.Item
                 GROUP BY CF.Periodo,CF.GrupoNo,CP.Producto
                 UNION
                 SELECT 'x' As Periodo,COUNT(CP.Producto) AS Cant,' ==> ' As GrupoNo,'Total por Cobrar' As Producto,SUM(CF.Valor-CF.Descuento) As Total
                 FROM Clientes_Facturacion As CF,Catalogo_Productos As CP
                 WHERE CP.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                 AND CP.Item = '" . $_SESSION['INGRESO']['item'] . "'";
        if (date('m', strtotime($parametros['MBFechaI'])) == date('m', strtotime($parametros['MBFechaF']))) {
            $sql .= "AND CF.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'";
        } else {
            $sql .= "AND CF.Fecha <= '" . $FechaFin . "'";
        }
        $sql .= "AND CF.GrupoNo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "'
                AND CF.Codigo_Inv = CP.Codigo_Inv
                AND CF.Item = CP.Item
                ORDER BY CF.Periodo,CF.GrupoNo,CP.Producto";
        //print_r($sql);die();
        $AdoQuery = $this->db->datos($sql);
        $datos = grilla_generica_new($sql, 'Clientes_Facturacion', '', 'RESUMEN DE PENSIONES DEL MES', false, false, false, 1, 1, 1, 100);
        return array('datos' => $datos, 'AdoQuery' => $AdoQuery);
    }

    public function Command5_Click($parametros, $ListaDeCampos)
    {
        $sql = "";
        if ($parametros['CheqConDeuda']) {
            $sql = "UPDATE Reporte_CxC_Cuotas 
                    SET E = 0 
                    WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'";
        } else {
            $sql = "UPDATE Clientes 
                    SET FactM = 0 
                    WHERE FA <> 0";
        }
        Ejecutar_SQL_SP($sql);

        for ($i = 0; $i < count($parametros['LstClientes']); $i++) {
            $NombreCliente = $parametros['LstClientes'][$i]['Cliente'];
            if ($parametros['CheqConDeuda']) {
                $sql = "UPDATE Reporte_CxC_Cuotas
                        SET E = 1
                        WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                        AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'
                        AND Cliente = '" . $NombreCliente . "'";
            } else {
                $sql = "UPDATE Clientes 
                        SET FactM = 1 
                        WHERE FA <> 0 
                        AND Cliente LIKE '" . $NombreCliente . "%'";
            }
            Ejecutar_SQL_SP($sql);
        }

        if ($parametros['CheqConDeuda']) {
            $sql = "SELECT " . $ListaDeCampos . ", C.Representante, C.CI_RUC, C.Email, C.EmailR, C.Cliente 
                    FROM Reporte_CxC_Cuotas As RCC, Clientes As C 
                    WHERE RCC.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND RCC.CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
                    AND RCC.E <> 0 
                    AND RCC.Codigo = C.Codigo 
                    ORDER BY RCC.GrupoNo, RCC.Cliente";
        } else {
            $sql = "SELECT Cliente, 0 As CxC_20XX, 0 As SubTotal, 0 As Anticipos, 0 As Total, Direccion As Detalle_Grupo, Grupo As GrupoNo , Representante, CI_RUC, Email, EmailR 
                    FROM Clientes 
                    WHERE FA <> 0 
                    AND FactM <> 0 
                    ORDER BY GrupoNo, Cliente ";
        }
        //print_r($sql);die();
        $AdoAux = $this->db->datos($sql);
        return $AdoAux;
    }

    public function ProcGrabarMult($parametros, $NoMes, $Periodo_Facturacion, $FA, $CodigoL, $Cta_Ventas)
    {
        $mensaje = "";
        $sql = "SELECT TOP 100 C.Grupo,C.Cliente,C.Codigo,CF.Periodo,CF.Num_Mes,SUM(CF.Valor) As TValor
                FROM Clientes As C,Clientes_Facturacion As CF
                WHERE CF.Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND C.T = 'N'";
        if ($parametros['PorGrupo'] <> 0) {
            $sql .= "AND C.Grupo = '" . $parametros['DCCliente'] . "' ";
        } else {
            if ($parametros['CheqRangos'] <> 0)
                $sql .= "AND C.Grupo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "' ";
        }
        if ($parametros['CheqFA'] == 0) {
            $sql .= "AND CF.Num_Mes = '" . $NoMes . "' 
                     AND CF.Periodo = '" . $Periodo_Facturacion . "' ";
        } else {
            $sql .= "AND CF.Fecha BETWEEN '" . $parametros['MBFechaI'] . "' AND '" . $parametros['MBFechaF'] . "' ";
        }
        $sql .= "AND C.Codigo = CF.Codigo 
                 GROUP BY C.Grupo,C.Cliente,C.Codigo,CF.Periodo,CF.Num_Mes 
                 ORDER BY C.Grupo,C.Cliente,CF.Periodo,CF.Num_Mes";
        $AdoQuery = $this->db->datos($sql);
        $Contador = 0;
        if (count($AdoQuery) > 0) {
            $datos = grilla_generica_new($sql, 'Clientes', '', '', false, false, false, 1, 1, 1, 100);
            $FechaTexto = "";
            if ($parametros['CheqFA'] == 0) {
                $FechaTexto = $parametros['MBFechaI'];
            } else {
                $FechaTexto = $parametros['MBFechaF'];
            }
            $FA['T'] = G_PENDIENTE;
            $FA['Nuevo_Doc'] = 1;
            $FA['Factura'] = ReadSetDataNum($FA['TC'] . "_SERIE_" . $FA['Serie'], True, False);
            $FA['Fecha'] = $FechaTexto;
            $Factura_No = $FA['Factura'];
            $Factura_Desde = $Factura_No;
            $Factura_Hasta = $Factura_No + count($AdoQuery);
            $sql = "DELETE *
                    FROM Facturas
                    WHERE Factura BETWEEN '" . $Factura_Desde . "' AND '" . $Factura_Hasta . "'
                    AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                    AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                    AND TC = '" . $FA['TC'] . "'
                    AND Serie = '" . $FA['Serie'] . "'
                    AND Autorizacion = '" . $FA['Autorizacion'] . "'";
            Ejecutar_SQL_SP($sql);

            foreach ($AdoQuery as $key => $value) {
                $FA['Factura'] = ReadSetDataNum($FA['TC'] . "_SERIE_" . $FA['Serie'], True, False);
                $FA['CodigoC'] = $value['Codigo'];
                $FA['Cliente'] = $value['Cliente'];
                $FA['Grupo'] = $value['Grupo'];
                $NoMes = $value['Num_Mes'];
                $MiMes = MesesLetras($NoMes);
                $Periodo_Facturacion = $value['Periodo'];
                $FA['EmailC'] = G_NINGUNO;
                $FA['Fecha'] = $FechaTexto;
                $FA['Nota'] = "Facturas del mes de " . date('m', strtotime($FechaTexto));
                $sql = "DELETE *
                        FROM Asiento_F 
                        WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                        AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'";
                Ejecutar_SQL_SP($sql);
                $sql = "SELECT C.Cliente,CF.Codigo_Inv,CP.Producto,CF.Valor,CF.Descuento,CF.Descuento2,CP.IVA,C.Codigo,C.Grupo
                        FROM Clientes_Facturacion As CF,Clientes As C,Catalogo_Productos CP
                        WHERE CF.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                        AND CP.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                        AND C.Codigo = '" . $FA['CodigoC'] . "' 
                        AND CF.Num_Mes = '" . $NoMes . "' 
                        AND CF.Periodo = '" . $Periodo_Facturacion . "' 
                        AND CF.Codigo_Inv = CP.Codigo_Inv 
                        AND CF.Codigo = C.Codigo 
                        AND CF.Item = CP.Item 
                        ORDER BY CF.Codigo_Inv ";
                $AdoParte = $this->db->datos($sql);
                if (count($AdoParte) > 0) {
                    foreach ($AdoParte as $key2 => $value2) {
                        if ($value2['Valor'] > 0) {
                            SetAdoAddNew("Asiento_F");
                            SetAdoFields("CODIGO", $value2['Codigo_Inv']);
                            SetAdoFields("CODIGO_L", $CodigoL);
                            SetAdoFields("PRODUCTO", $value2['Producto']);
                            SetAdoFields("CANT", 1);
                            SetAdoFields("PRECIO", $value2['Valor']);
                            SetAdoFields("Total_Desc", $value2['Descuento']);
                            SetAdoFields("Total_Desc2", $value2['Descuento2']);
                            SetAdoFields("TOTAL", $value2['Valor']);
                            if ($value2['IVA']) {
                                $Total_IVAFM = round($value2['Valor'] * $FA['Porc_IVA'], 2);
                            } else {
                                $Total_IVAFM = 0;
                            }
                            SetAdoFields("Total_IVA", $Total_IVAFM);
                            SetAdoFields("Cta", $Cta_Ventas);
                            SetAdoFields("Item", $_SESSION['INGRESO']['item']);
                            SetAdoFields("Codigo_Cliente", $FA['CodigoC']);
                            SetAdoFields("Mes", $MiMes);
                            SetAdoFields("TICKET", $Periodo_Facturacion);
                            SetAdoFields("CodigoU", $_SESSION['INGRESO']['CodigoU']);
                            SetAdoFields("A_No", $Contador);
                            SetAdoUpdate();
                        }
                    }
                    $Factura_Hasta = $FA['Factura'];
                    $FA['Tipo_PRN'] = "FM";
                    $tmp = Calculos_Totales_Factura();
                    $FA = array_merge($FA, $tmp);
                    $FA['Nota'] = "FACTURA PENDIENTE DE PAGO";
                    if ($FA['Vencimiento'] instanceof DateTime) {
                        $tmp = new DateTime($FA['Vencimiento']['date'], new DateTimeZone('America/Guayaquil'));
                        $FA['Vencimiento'] = $tmp->format('Y-m-d');
                    }
                    if ($FA['Fecha_Aut'] instanceof DateTime) {
                        $tmp = new DateTime($FA['Fecha_Aut']['date'], new DateTimeZone('America/Guayaquil'));
                        $FA['Fecha_Aut'] = $tmp->format('Y-m-d');
                    }
                    Grabar_Factura1($FA);
                    $sql = "DELETE *
                            FROM Clientes_Facturacion 
                            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                            AND Codigo = '" . $FA['CodigoC'] . "'
                            AND Num_Mes = '" . $NoMes . "'
                            AND Periodo = '" . $Periodo_Facturacion . "'";
                    Ejecutar_SQL_SP($sql);
                }
                $Contador++;
            }
            if ($parametros['TipoFactura'] == "NV") {
                $mensaje = "IMPRIMIR NOTAS DE VENTA \n";
            } else {
                $mensaje = "IMPRIMIR FACTURAS (FM) \n";
            }
            $mensaje .= "DESDE: " . $Factura_Desde .
                "\n HASTA: " . $Factura_Hasta .
                "\n SON UN TOTAL DE: " . number_format($Factura_Hasta - $Factura_Desde + 1, 2, ',', '.') .
                "\n EN EL MENU: \n" .
                "ARCHIVOS -> LISTAR ANULAR FACTURAS \n
                        Opción: En Bloque";

            return array('mensaje' => $mensaje, 'datos' => $datos, 'numRegistros' => count($AdoQuery));
        } else {
            $mensaje = "No se puede grabar la factura, no existen datos en el periodo seleccionado";
            return array('mensaje' => $mensaje, 'datos' => array(), 'numRegistros' => 0);
        }
    }

    public function Listado_x_Grupos()
    {
        $sql = "SELECT Grupo,Direccion,COUNT(Grupo) As Alumnos
                FROM Clientes 
                WHERE FA <> 0 ";
        if ($_SESSION['INGRESO']['Mas_Grupos']) {
            $sql .= "AND DirNumero = '" . $_SESSION['INGRESO']['item'] . "' ";
        }
        $sql .= "GROUP BY Grupo,Direccion
                 ORDER BY Grupo,Direccion";
        $AdoNiveles = $this->db->datos($sql);
        if (count($AdoNiveles) > 0) {
            return $AdoNiveles;
        } else {
            throw new Exception("No se encontraron clientes para listar por grupos");
        }
    }

    public function Recalcular_Fechas()
    {
        $sql = "SELECT Periodo 
                FROM Clientes_Facturacion 
                WHERE ISNUMERIC(Periodo) <> 0 
                AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                GROUP BY Periodo 
                ORDER BY Periodo ";
        $AdoAux = $this->db->datos($sql);
        if (count($AdoAux) > 0) {
            foreach ($AdoAux as $key => $value) {
                $Anio = $value['Periodo'];
                for ($i = 1; $i <= 12; $i++) {
                    $Mifecha = BuscarFecha(date('Y-m-t', strtotime("01/" . sprintf("%02d", $i) . "/" . $Anio)));
                    $sql = "UPDATE Clientes_Facturacion
                                SET Fecha = '" . $Mifecha . "'
                                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                                AND Periodo = '" . $Anio . "'
                                AND Num_Mes = '" . $i . "'";
                    Ejecutar_SQL_SP($sql);
                }
            }
        } else {
            throw new Exception("No se encontraron datos para recalcular las fechas");
        }


    }

    public function Imprimir_Recibos_Cobros($parametros)
    {
        $sql = "SELECT SUM(Valor) As SaldoPend, Codigo 
                FROM Clientes_Facturacion 
                WHERE Fecha BETWEEN '" . $parametros['MBFechaI'] . "' AND '" . $parametros['MBFechaF'] . "' 
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'";
        if ($parametros['CheqRangos'] <> 0) {
            $sql .= "AND Grupo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "'";
        }
        $sql .= "GROUP BY Codigo";
        return $this->db->datos($sql);
    }

    public function Recibos_Case1($parametros, $Codigo1, $Codigo2)
    {
        $sql = "SELECT SUM(Valor) As SaldoPend,Codigo 
                FROM Clientes_Facturacion 
                WHERE Fecha BETWEEN '" . $parametros['MBFechaI'] . "' AND '" . $parametros['MBFechaF'] . "' ";
        if ($parametros['CheqRangos'] <> 0) {
            $sql .= "AND Grupo BETWEEN '" . $Codigo1 . "' AND '" . $Codigo2 . "' ";
        }
        $sql .= "GROUP BY Codigo ";
        return $this->db->datos($sql);
    }

    public function Update_Direccion($parametros)
    {
        try {
            $sql = "UPDATE Clientes 
                    SET Direccion = '" . $parametros['Codigo2'] . "' 
                    WHERE Grupo = '" . $parametros['Codigo1'] . "' ";
            if ($_SESSION['INGRESO']['Mas_Grupos']) {
                $sql .= "AND DirNumero = '" . $_SESSION['INGRESO']['item'] . "'";
            }
            Ejecutar_SQL_SP($sql);
        } catch (Exception $e) {
            throw new Exception("Error al actualizar la dirección");
        }
    }

    public function Update_Grupo($parametros)
    {
        try {
            $sql = "UPDATE Clientes 
                    SET Grupo = '" . $parametros['Codigo2'] . "' 
                    WHERE Grupo = '" . $parametros['Codigo1'] . "' ";
            if ($_SESSION['INGRESO']['Mas_Grupos']) {
                $sql .= "AND DirNumero = '" . $_SESSION['INGRESO']['item'] . "'";
            }
            Ejecutar_SQL_SP($sql);
        } catch (Exception $e) {
            throw new Exception("Error al actualizar el grupo");
        }
    }

    public function Desactivar_Grupo($parametros): void
    {
        try {
            $sql = "UPDATE Clientes_Facturacion 
                    SET GrupoNo = C.Grupo 
                    FROM Clientes_Facturacion As CF, Clientes As C
                    WHERE CF.Item = '" . $_SESSION['INGRESO']['item'] . "' ";
            if ($_SESSION['INGRESO']['Mas_Grupos']) {
                $sql .= "AND C.DirNumero = '" . $_SESSION['INGRESO']['item'] . "' ";
            }
            $sql .= "AND CF.Codigo = C.Codigo";
            Ejecutar_SQL_SP($sql);
            $sql = "UPDATE Trans_Notas 
                    SET CodE = C.Grupo 
                    FROM Trans_Notas As CF,Clientes As C 
                    WHERE CF.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND CF.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                    AND CF.Codigo = C.Codigo ";
            Ejecutar_SQL_SP($sql);
            $sql = "UPDATE Trans_Asistencia 
                    SET CodE = C.Grupo 
                    FROM Trans_Asistencia As CF, Clientes As C
                    WHERE CF.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND CF.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                    AND CF.Codigo = C.Codigo";
            Ejecutar_SQL_SP($sql);
            $sql = "UPDATE Trans_Notas_Auxiliares 
                    SET CodE = C.Grupo 
                    FROM Trans_Notas_Auxiliares As CF, Clientes As C 
                    WHERE CF.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND CF.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                    AND CF.Codigo = C.Codigo";
            Ejecutar_SQL_SP($sql);
            $sql = "UPDATE Trans_Notas_Grado 
                    SET CodE = C.Grupo 
                    FROM Trans_Notas_Grado As CF, Clientes As C 
                    WHERE CF.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND CF.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                    AND CF.Codigo = C.Codigo";
            Ejecutar_SQL_SP($sql);
            $sql = "UPDATE Trans_Actas 
                    SET CodE = C.Grupo 
                    FROM Trans_Actas As CF, Clientes As C
                    WHERE CF.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND CF.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                    AND CF.Codigo = C.Codigo";
            Ejecutar_SQL_SP($sql);
            $sql = "DELETE * 
                    FROM Clientes_Facturacion 
                    WHERE GrupoNo = '" . $parametros['Codigo1'] . "' 
                    AND Item = '" . $_SESSION['INGRESO']['item'] . "'";
            Ejecutar_SQL_SP($sql);
            $sql = "DELETE * 
                    FROM Trans_Notas 
                    WHERE CodE = '" . $parametros['Codigo1'] . "' 
                    AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'";
            Ejecutar_SQL_SP($sql);
            $sql = "DELETE * 
                    FROM Trans_Asistencia 
                    WHERE CodE = '" . $parametros['Codigo1'] . "' 
                    AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'";
            Ejecutar_SQL_SP($sql);
            $sql = "DELETE * 
                    FROM Trans_Notas_Auxiliares 
                    WHERE CodE = '" . $parametros['Codigo1'] . "' 
                    AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'";
            Ejecutar_SQL_SP($sql);
            $sql = "DELETE * 
                    FROM Trans_Notas_Grado 
                    WHERE CodE = '" . $parametros['Codigo1'] . "' 
                    AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'";
            Ejecutar_SQL_SP($sql);
            $sql = "DELETE * 
                    FROM Trans_Actas 
                    WHERE CodE = '" . $parametros['Codigo1'] . "' 
                    AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'";
            Ejecutar_SQL_SP($sql);
            $sql = "UPDATE Clientes 
                    SET FA = 0
                    WHERE Grupo = '" . $parametros['Codigo1'] . "' ";
            if ($_SESSION['INGRESO']['Mas_Grupos']) {
                $sql .= "AND DirNumero = '" . $_SESSION['INGRESO']['item'] . "'";
            }
            Ejecutar_SQL_SP($sql);
        } catch (Exception $e) {
            throw new Exception("Error al desactivar el grupo");
        }
    }

    public function Eliminar_Rubros_Facturacion()
    {
        try {
            $sql = "DELETE * 
                    FROM Clientes_Facturacion 
                    WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                    AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'";
            Ejecutar_SQL_SP($sql);
        } catch (Exception $e) {
            throw new Exception("Error al eliminar los rubros de facturación");
        }
    }

    public function Retirar_Beneficiarios($parametros)
    {
        try {
            $sql = "UPDATE Clientes 
                    SET X = 'R' 
                    WHERE Codigo <> '" . G_NINGUNO . "' ";
            Ejecutar_SQL_SP($sql);
            $sql = "UPDATE Clientes 
                    SET X = '.' 
                    FROM Clientes As C, Clientes_Facturacion As CF 
                    WHERE C.Codigo = CF.Codigo";
            Ejecutar_SQL_SP($sql);
            $sql = "UPDATE Clientes 
                    SET FA = 0 
                    WHERE X = 'R' 
                    AND Grupo = '" . $parametros['Codigo1'] . "' ";
            Ejecutar_SQL_SP($sql);
        } catch (Exception $e) {
            throw new Exception("Error al retirar los beneficiarios");
        }
    }
}