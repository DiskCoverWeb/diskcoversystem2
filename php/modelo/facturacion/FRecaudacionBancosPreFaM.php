<?php
require_once(dirname(__DIR__, 2) . "/db/db1.php");
require_once(dirname(__DIR__, 2) . "/funciones/funciones.php");

/*
    AUTOR DE RUTINA	: Leonardo Súñiga
    FECHA CREACION	: 03/01/2024
    FECHA MODIFICACION: 17/01/2024
    DESCIPCION : Clase que se encarga de manejar la conexión con la base de datos de la pantalla de recaudacion de bancos
*/


class FRecaudacionBancosPreFaM
{
    private $db;


    public function __construct()
    {

        $this->db = new db();

    }

    public function DCLinea()
    {
        $sql = "SELECT *
                FROM Catalogo_Lineas
                WHERE TL <> 0
                AND Fact NOT IN ('CP','NC','LC')
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                ORDER BY Codigo";
        return $this->db->datos($sql);
    }

    public function AdoProducto()
    {
        $sql = "SELECT * 
                FROM Catalogo_Productos
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND TC = 'P'
                ORDER BY Codigo_Inv";
        return $this->db->datos($sql);
    }

    public function DCBanco()
    {
        $sql = "SELECT *
                FROM Catalogo_Cuentas
                WHERE TC = 'BA'
                AND DG = 'D' 
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                ORDER BY Codigo";
        return $this->db->datos($sql);
    }

    public function DCGrupos()
    {
        $sql = "SELECT Grupo, Count(Grupo) As Cantidad
                FROM Clientes
                WHERE FA <> 0";
        if ($_SESSION['INGRESO']['Mas_Grupos']) {
            $sql .= " AND DirNumero = '" . $_SESSION['INGRESO']['item'] . "'";
        }
        $sql .= "GROUP BY Grupo
                ORDER BY Grupo";
        return $this->db->datos($sql);
    }

    public function DCEntidadBancaria()
    {
        $sql = "SELECT Descripcion, Abreviado, ID
                FROM Tabla_Referenciales_SRI
                WHERE Tipo_Referencia = 'BANCOS Y COOP'
                AND Abreviado <> '.'
                AND TPFA <> '0'
                ORDER BY Descripcion";
        return $this->db->datos($sql);
    }

    public function MBFechaI_LostFocus($parametros)
    {
        $sql = "SELECT * 
                FROM Catalogo_Lineas
                WHERE TL <> '0'
                AND '" . BuscarFecha($parametros['MBFechaI']) . "' <= Vencimiento
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND Fact IN ('NV','FA')
                ORDER BY Codigo";
        return $this->db->datos($sql);

    }

    public function Command1_Click_Delete_AsientoF()
    {
        $sql = "DELETE * 
                FROM Asiento_F
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'";
        Ejecutar_SQL_SP($sql);
    }

    public function Command1_Click_Delete_TablaTemporal()
    {
        $sql = "DELETE * 
                FROM Tabla_Temporal
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Modulo = '" . $_SESSION['INGRESO']['modulo_'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'";
        Ejecutar_SQL_SP($sql);
    }

    public function Command1_Click_Update_ClientesFacturacion()
    {
        $sql = "UPDATE Clientes_Facturacion
                SET X = '.'
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'";
        Ejecutar_SQL_SP($sql);
    }

    public function AdoCliDB($CodigoCli)
    {
        $sql = "SELECT Codigo,Cliente,Grupo, CI_RUC
                FROM Clientes
                WHERE CI_RUC = '" . $CodigoCli . "'";
        return $this->db->datos($sql);
    }

    public function AdoAuxProducto($CodigoInv)
    {
        $sql = "SELECT Producto
                FROM Catalogo_Productos
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND Codigo_Inv = '" . $CodigoInv . "'
                AND TC = 'P'";
        return $this->db->datos($sql);
    }

    public function AdoAuxClientes_Facturacion($CodigoCli, $NoMeses, $NoAnio, $CodigoInv, $FechaTope)
    {
        $sql = "SELECT * 
                FROM Clientes_Facturacion
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Codigo = '" . $CodigoCli . "'";
        if ($NoMeses > 0) {
            $sql .= " AND Num_Mes = '" . $NoMeses . "'";
        }
        if ($NoAnio > 0) {
            $sql .= " AND Periodo = '" . $NoAnio . "'";
        }
        if ($CodigoInv <> G_NINGUNO) {
            $sql .= " AND Codigo_Inv = '" . $CodigoInv . "'";
        }
        $sql .= "AND Fecha <= '" . BuscarFecha($FechaTope) . "'
                ORDER BY Periodo, Num_Mes";
        return $this->db->datos($sql);

    }

    public function AdoAuxClientes_FacturacionUpdate($CodigoCli, $NoMeses, $NoAnio, $CodigoInv, $FechaTope)
    {
        $sql = "UPDATE Clientes_Facturacion
                SET X = 'X'
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Codigo = '" . $CodigoCli . "'";
        if ($NoMeses > 0) {
            $sql .= " AND Num_Mes = '" . $NoMeses . "'";
        }
        if ($NoAnio > 0) {
            $sql .= " AND Periodo = '" . $NoAnio . "'";
        }
        if ($CodigoInv <> G_NINGUNO) {
            $sql .= " AND Codigo_Inv = '" . $CodigoInv . "'";
        }
        $sql .= "AND Fecha <= '" . BuscarFecha($FechaTope) . "'";
        $this->db->String_Sql($sql);

    }

    public function AdoFactura()
    {
        $sql = "SELECT * 
                FROM Asiento_F
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'
                ORDER BY FECHA,Codigo_Cliente,Numero";
        return $this->db->datos($sql);
    }

    public function AdoFacturaUpdate()
    {
        $sql = "UPDATE Asiento_F
                SET CodBod = 'X'
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'";
        $this->db->String_Sql($sql);
    }

    public function AdoAuxClientesFacturacion2($Codigo_Cliente, $CODIGO, $A_No, $HABIT)
    {
        $sql = "SELECT Codigo,Num_Mes,Periodo,SUM(Valor) AS TValor 
                FROM Clientes_Facturacion
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Codigo = '" . $Codigo_Cliente . "'
                AND Codigo_Inv = '" . $CODIGO . "'
                AND Num_Mes = '" . $A_No . "'
                AND Periodo <= '" . $HABIT . "'
                GROUP BY Codigo,Num_Mes,Periodo";
        return $this->db->datos($sql);
    }

    public function DeleteAsientoF()
    {
        $sql = "DELETE * 
                FROM Asiento_F
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodBod = 'X'";
        Ejecutar_SQL_SP($sql);
    }

    public function AdoFactura2()
    {
        $sql = "SELECT * 
                FROM Asiento_F
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'
                ORDER BY RUTA,Codigo_Cliente,FECHA,Numero";
        return $this->db->datos($sql);
    }

    public function AdoFacturaUpdate2($Factura_No)
    {
        $sql = "UPDATE Asiento_F
                SET Numero = '" . $Factura_No . "'
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'";
        $this->db->String_Sql($sql);
    }

    public function DGFactura()
    {
        $sql = "SELECT FECHA,Numero As FACTURA,RUTA As BENEFICIARIO,CANT,CODIGO,PRODUCTO,TOTAL,COSTO As COMISION,
                HABIT As PERIODO,Mes,A_No As NO_MES,Codigo_Cliente,Serie,Autorizacion,Cta As Trans_No,
                CODIGO_L As Forma_P,TICKET As No_Cta,Cod_Ejec As Cod_Banco,CodigoU,Item
                FROM Asiento_F
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'
                ORDER BY Numero";
        $AdoAsientoF = $this->db->datos($sql);
        $datos = grilla_generica_new($sql, 'Asiento_F', '', 'PREFACTURACION DEL DIA', false, false, false, 1, 1, 1, 100);
        return array('datos' => $datos, 'AdoAsientoF' => $AdoAsientoF);
    }

    public function UpdateClientesFacturacion()
    {
        $sql = "UPDATE Clientes_Facturacion
                SET AlDia = 1
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'";
        Ejecutar_SQL_SP($sql);
    }

    public function UpdateClientesFacturacion2($MBFechaI)
    {
        $sql = "UPDATE Clientes_Facturacion
                SET AlDia = 0
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Fecha < '" . BuscarFecha($MBFechaI) . "'";
        Ejecutar_SQL_SP($sql);
    }

    public function UpdateClientesFacturacion3()
    {
        $sql = "UPDATE Clientes_Facturacion
                SET Fecha = CONVERT(datetime, '01/' + STR(Num_Mes) + '/' + Periodo, 103)
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Fecha <> CONVERT(datetime, '01/' + STR(Num_Mes) + '/' + Periodo, 103)";
        Ejecutar_SQL_SP($sql);
    }

    public function Tipo_Carga($parametros)
    {
        $sql = "";
        switch ($parametros['Tipo_Carga']) {
            case 2:
                $sql .= "SELECT F.Codigo,C.Grupo,C.Cliente,C.CI_RUC,C.Direccion,C.Casilla,C.Actividad,F.Periodo,F.Num_Mes,F.Fecha,CC.Codigo_Inv,
                         C.Representante,C.CI_RUC_R,C.TD_R,C.Telefono_R,C.Tipo_Cta,C.Cod_Banco,C.Cta_Numero,C.DireccionT,C.Fecha_Cad,C.Email2,
                         C.Saldo_Pendiente,CC.Producto,(F.Valor-(F.Descuento+F.Descuento2)) As Valor_Cobro
                         FROM Clientes_Facturacion As F,Clientes As C,Catalogo_Productos As CC 
                         WHERE F.Item = '" . $_SESSION['INGRESO']['item'] . "'
                         AND CC.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                         AND (F.Valor-(F.Descuento+F.Descuento2)) > 0";
                break;
            case 3:
                $sql .= "SELECT F.Codigo,C.Grupo,C.Cliente,C.CI_RUC,C.Direccion,C.Casilla,C.Actividad,C.Plan_Afiliado,C.Saldo_Pendiente,
                         C.Representante,C.CI_RUC_R,C.TD_R,C.Telefono_R,C.Tipo_Cta,C.Cod_Banco,C.Cta_Numero,C.DireccionT,C.Fecha_Cad,C.Email2,'PENSION ' As Producto,
                         '01.01' As Codigo_Inv,SUM(F.Valor-(F.Descuento+F.Descuento2)) As Valor_Cobro 
                         FROM Clientes_Facturacion As F,Clientes As C 
                         WHERE F.Item = '" . $_SESSION['INGRESO']['item'] . "'";
                break;
            default:
                $sql = "SELECT F.Codigo,C.Grupo,C.Cliente,C.CI_RUC,C.Direccion,C.Casilla,C.Actividad,C.Plan_Afiliado,F.Periodo,F.Num_Mes,F.Fecha,
                        C.Representante,C.CI_RUC_R,C.TD_R,C.Telefono_R,C.Tipo_Cta,C.Cod_Banco,C.Cta_Numero,C.DireccionT,C.Fecha_Cad,C.Email2,C.EmailR,
                        F.Codigo_Inv,C.Saldo_Pendiente,SUM(F.Valor-(F.Descuento+F.Descuento2)) As Valor_Cobro,";
                if ($parametros['CheqMatricula']) {
                    $sql .= "'MATRICULAS Y PENSION DE ' As Producto ";
                } else {
                    $sql .= "'PENSION DE ' As Producto ";
                }
                $sql .= "FROM Clientes_Facturacion As F, Clientes As C 
                         WHERE F.Item = '" . $_SESSION['INGRESO']['item'] . "'";
                break;

        }

        $sql .= "AND F.Fecha BETWEEN '" . BuscarFecha($parametros['MBFechaI']) . "' and '" . BuscarFecha($parametros['MBFechaF']) . "'";
        if ($parametros['CheqRangos'] <> 0) {
            $sql .= "AND C.Grupo BETWEEN '" . $parametros['DCGrupoI'] . "' and '" . $parametros['DCGrupoF'] . "'";
        }
        if ($parametros['CheqAlDia'] <> 0) {
            $sql .= "AND F.AlDia <> 0";
        }
        switch ($parametros['TextoBanco']) {
            case 'PACIFICO':
                break;
            case 'PICHINCHA':
                break;
            case 'BOLIVARIANO':
                break;
            case 'GUAYAQUIL':
                break;
            case 'PRODUBANCO':
                break;
            case 'TARJETAS':
                $sql .= "AND C.Tipo_Cta = 'TARJETA'
                         AND LEN(C.Cta_Numero) >= 14";
                break;
            case 'INTERNACIONAL':
                break;
            default:
                $sql .= "AND C.Cod_Banco > 0
                         AND LEN(C.Cta_Numero) > 1
                         AND C.Tipo_Cta <> '.' ";
                break;
        }

        switch ($parametros['Tipo_Carga']) {
            case 2:
                $sql .= "AND F.Codigo = C.Codigo
                         AND F.Codigo_Inv = CC.Codigo_Inv
                         AND F.Item = CC.Item
                         ORDER BY C.Grupo,C.Cliente,F.Fecha,F.Codigo_Inv";
                break;
            case 3:
                $sql .= "AND F.Codigo = C.Codigo
                         GROUP BY F.Codigo,C.Grupo,C.Cliente,C.CI_RUC,C.Direccion,C.Casilla,C.Actividad,C.Plan_Afiliado,C.Saldo_Pendiente,
                         C.Representante,C.CI_RUC_R,C.TD_R,C.Telefono_R,C.Tipo_Cta,C.Cod_Banco,C.Cta_Numero,C.DireccionT,C.Fecha_Cad,C.Email2
                         HAVING SUM(F.Valor-(F.Descuento+F.Descuento2)) > 0
                         ORDER BY C.Grupo,C.Cliente";
                break;
            default:
                $sql .= "AND F.Codigo = C.Codigo
                         GROUP BY F.Codigo,C.Grupo,C.Cliente,C.CI_RUC,C.Direccion,C.Casilla,C.Actividad,C.Plan_Afiliado,F.Periodo,F.Codigo_Inv,F.Num_Mes,F.Fecha,
                         C.Representante,C.CI_RUC_R,C.TD_R,C.Telefono_R,C.Tipo_Cta,C.Cod_Banco,C.Cta_Numero,C.DireccionT,C.Fecha_Cad,C.Email2,C.EmailR,C.Saldo_Pendiente
                         HAVING SUM(F.Valor-(F.Descuento+F.Descuento2)) > 0 
                         ORDER BY C.Grupo,C.Cliente,F.Fecha";
                break;
        }
        //print_r($sql);
        return $this->db->datos($sql);
    }

    public function AdoFactura3()
    {
        $sql = "SELECT FECHA, Numero, Codigo_Cliente, SUM(TOTAL) As Total_Abonos
                FROM Asiento_F
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'
                GROUP BY FECHA, Numero, Codigo_Cliente
                ORDER BY FECHA, Numero, Codigo_Cliente";
        return $this->db->datos($sql);
    }

    public function ClientesFacturacion($CodigoCli)
    {
        $sql = "UPDATE Clientes_Facturacion
                SET D = 0
                WHERE Codigo = '" . $CodigoCli . "'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'";
        Ejecutar_SQL_SP($sql);
    }

    public function AdoProducto2($CodigoCli)
    {
        $sql = "SELECT CF.*,AF.Codigo_Cliente,AF.CODIGO_L,AF.TICKET,AF.PRODUCTO,AF.HABIT,AF.A_No
                FROM Clientes_Facturacion As CF, Asiento_F As AF
                WHERE CF.Codigo = '" . $CodigoCli . "'
                AND CF.Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CF.Codigo_Inv = AF.CODIGO
                AND CF.Num_Mes = AF.A_No
                AND CF.Periodo = AF.HABIT
                AND CF.Item = AF.Item
                AND CF.Codigo = AF.Codigo_Cliente
                ORDER BY CF.Fecha, CF.Codigo_Inv, Periodo, Num_Mes, CF.ID, CF.Valor DESC";
        return $this->db->datos($sql);
    }

    public function ClientesFacturacionUpdate($CodigoCli, $CodigoInv, $NumMes, $Periodo)
    {
        $sql = "UPDATE Clientes_Facturacion
                SET D = 1
                WHERE Codigo = '" . $CodigoCli . "'
                AND Codigo_Inv = '" . $CodigoInv . "'
                AND Num_Mes = " . $NumMes . "
                AND Periodo = '" . $Periodo . "'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'";
        Ejecutar_SQL_SP($sql);
    }

    public function ClientesFacturacionDelete($CodigoCli)
    {
        $sql = "DELETE * 
                FROM Clientes_Facturacion
                WHERE Codigo = '" . $CodigoCli . "'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND D <> 0";
        Ejecutar_SQL_SP($sql);
    }

    public function DetalleFacturaUpdate($FATC, $FASerie, $Factura_Desde, $Factura_Hasta)
    {
        $sql = "UPDATE Detalle_Factura
                SET Producto = CP.Producto
                FROM Detalle_Factura As DF, Catalogo_Productos As CP
                WHERE DF.Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND DF.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND DF.TC = '" . $FATC . "'
                AND DF.Serie = '" . $FASerie . "'
                AND DF.Factura BETWEEN " . $Factura_Desde . " AND " . $Factura_Hasta . "
                AND DF.Item = CP.Item
                AND DF.Periodo = CP.Periodo
                AND DF.Codigo = CP.Codigo_Inv";
        Ejecutar_SQL_SP($sql);
    }

    public function DeleteAsientoF2()
    {
        $sql = "DELETE * 
                FROM Asiento_F
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'";
        Ejecutar_SQL_SP($sql);
    }

    public function DGFactura2()
    {
        $sql = "SELECT FECHA,Numero As FACTURA,RUTA As BENEFICIARIO,CODIGO,CANT,PRODUCTO,TOTAL,
                HABIT As PERIODO,Mes,A_No As NO_MES,Codigo_Cliente,CodigoU,Item 
                FROM Asiento_F
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'";
        $AdoAsientoF = $this->db->datos($sql);
        $datos = grilla_generica_new($sql, 'Asiento_F', '', '', false, false, false, 1, 1, 1, 100);
        return array('datos' => $datos, 'AdoAsientoF' => $AdoAsientoF);
    }

    public function AdoAux($parametros)
    {
        $sql = "SELECT Codigo,GrupoNo,Periodo,Mes,Num_Mes,Fecha
                FROM Clientes_Facturacion
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Fecha BETWEEN " . BuscarFecha($parametros['MBFechaI']) . " AND " . BuscarFecha($parametros['MBFechaF']) . "";
        if ($parametros['CheqRangos'] <> 0) {
            $sql .= "AND GrupoNo BETWEEN '" . $parametros['DCGrupoI'] . "' AND '" . $parametros['DCGrupoF'] . "'";
        }
        $sql .= "ORDER BY Codigo, Fecha";
        return $this->db->datos($sql);
    }

    public function AdoClientes($parametros)
    {
        $sql = "SELECT * 
                FROM Clientes
                WHERE FA <> 0 ";
        if ($parametros['CheqRangos'] <> 0) {
            $sql .= "AND Grupo BETWEEN '" . $parametros['DCGrupoI'] . "' AND '" . $parametros['DCGrupoF'] . "'";
        }
        $sql .= "ORDER BY CI_RUC,Cliente,Grupo";
        return $this->db->datos($sql);
    }

    public function UpdateAdoCliente($parametros, $Contador)
    {
        $sql = "UPDATE Clientes
                SET Num_Lista = " . sprintf("%03d", $Contador) . "
                WHERE FA <> 0 ";
        if ($parametros['CheqRangos'] <> 0) {
            $sql .= "AND Grupo BETWEEN '" . $parametros['DCGrupoI'] . "' AND '" . $parametros['DCGrupoF'] . "'";
        }
        Ejecutar_SQL_SP($sql);
    }

    public function AdoClientes2($parametros)
    {
        $sql = "SELECT CI_RUC,Cliente,Codigo,Grupo,TD,Num_Lista
                FROM Clientes
                WHERE FA <> 0";
        if ($parametros['CheqRangos'] <> 0) {
            $sql .= "AND Grupo BETWEEN '" . $parametros['DCGrupoI'] . "' AND '" . $parametros['DCGrupoF'] . "'";
        }
        $sql .= "ORDER BY CI_RUC,Cliente,Grupo";
        return $this->db->datos($sql);
    }

    public function AdoClientes3()
    {
        $sql = "SELECT Codigo,CI_RUC,Grupo,TD
                FROM Clientes
                WHERE TD NOT IN ('C','R')
                AND FA = 0
                AND Cliente <> 'CONSUMIDOR FINAL'
                ORDER BY Grupo,Cliente,CI_RUC";
        return $this->db->datos($sql);
    }

    public function UpdateAdoClientes3($Contador)
    {
        $sql = "UPDATE Clientes
                SET CI_RUC = " . sprintf("%08d", $Contador) . "
                WHERE TD NOT IN ('C','R')
                AND FA = 0
                AND Cliente <> 'CONSUMIDOR FINAL'";
        Ejecutar_SQL_SP($sql);
    }

    public function AdoClientes4()
    {
        $sql = "SELECT Codigo,CI_RUC,Grupo,TD
                FROM Clientes
                WHERE FA <> 0
                AND Cliente <> 'CONSUMIDOR FINAL'
                ORDER BY Grupo,Cliente,CI_RUC";
        return $this->db->datos($sql);
    }

    public function UpdateAdoClientes4($Contador)
    {
        $sql = "UPDATE Clientes
                SET CI_RUC = " . sprintf("%08d", $Contador) . "
                WHERE FA <> 0
                AND Cliente <> 'CONSUMIDOR FINAL'";
        Ejecutar_SQL_SP($sql);
    }

    public function AdoClientes5($parametros)
    {
        $sql = "SELECT Codigo,CI_RUC,Direccion,Cliente,Grupo,TD
                FROM Clientes
                WHERE FA <> 0";
        if ($parametros['CheqRangos'] <> 0) {
            $sql .= "AND Grupo BETWEEN '" . $parametros['DCGrupoI'] . "' AND '" . $parametros['DCGrupoF'] . "'";
        }
        $sql .= "AND Cliente <> 'CONSUMIDOR FINAL'
                ORDER BY Grupo,Cliente,Direccion,CI_RUC";
        return $this->db->datos($sql);
    }

    public function UpdateAdoClientes5($parametros, $param)
    {
        $sql = "UPDATE Clientes
                SET CI_RUC = " . $param . "
                WHERE FA <> 0 ";
        if ($parametros['CheqRangos'] <> 0) {
            $sql .= "AND Grupo BETWEEN '" . $parametros['DCGrupoI'] . "' AND '" . $parametros['DCGrupoF'] . "'";
        }
        $sql .= "AND Cliente <> 'CONSUMIDOR FINAL'";
        Ejecutar_SQL_SP($sql);
    }

    public function Procesar_Saldo_De_Facturas($PorX = false)
    {
        /*$Contador = 0;
        $sql = "";
        if ($PorX) {
            $sql = "UPDATE Detalle_Factura 
                    SET Total = ROUND(Cantidad * Precio, " . $_SESSION['INGRESO']['Dec_PVP'] . " , 0)
                    WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                    AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'";
            Ejecutar_SQL_SP($sql);

            $sql = "UPDATE Detalle_Factura
                    SET Total_IVA = ROUND((Total-(Total_Desc+Total_Desc2)) * ROUND(Total_IVA/(Total-(Total_Desc+Total_Desc2)),2,0),4,0)
                    WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                    AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                    AND T <> '" . G_ANULADO . "'
                    AND Total_IVA <> 0";
            Ejecutar_SQL_SP($sql);

            if ($_SESSION['INGRESO']['SQL_Server']) {
                $sql = "UPDATE Detalle_Factura
                        SET CodigoC = F.CodigoC
                        FROM Detalle_Factura As DF, Facturas As F";
            } else {
                $sql = "UPDATE Detalle_Factura As DF, Facturas As F
                        SET DF.CodigoC = F.CodigoC";
            }

            $sql .= "WHERE F.Item = '" . $_SESSION['INGRESO']['item'] . "'
                    AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                    AND F.Autorizacion = DF.Autorizacion
                    AND F.Fecha = DF.Fecha
                    AND F.Serie = DF.Serie
                    AND F.Factura = DF.Factura
                    AND F.Item = DF.Item
                    AND F.Periodo = DF.Periodo
                    AND F.TC = DF.TC
                    AND F.CodigoC <> DF.CodigoC";
            Ejecutar_SQL_SP($sql);

            if ($_SESSION['INGRESO']['SQL_Server']) {
                $sql = "UPDATE Detalle_Factura
                        SET Fecha = F.Fecha
                        FROM Detalle_Factura As DF, Facturas As F";
            } else {
                $sql = "UPDATE Detalle_Factura As DF, Facturas As F
                        SET DF.Fecha = F.Fecha";
            }
            $sql .= "WHERE F.Item = '" . $_SESSION['INGRESO']['item'] . "'
                    AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                    AND F.Autorizacion = DF.Autorizacion
                    AND F.Serie = DF.Serie
                    AND F.Factura = DF.Factura
                    AND F.Item = DF.Item
                    AND F.Periodo = DF.Periodo
                    AND F.TC = DF.TC
                    AND F.Fecha <> DF.Fecha";
            Ejecutar_SQL_SP($sql);

            if ($_SESSION['INGRESO']['SQL_Server']) {
                $sql = "UPDATE Trans_Abonos
                        SET CodigoC = F.CodigoC
                        FROM Trans_Abonos As DF,Facturas As F";
            } else {
                $sql = "UPDATE Trans_Abonos As DF,Facturas As F
                        SET DF.CodigoC = F.CodigoC";
            }

            $sql .= "WHERE F.Item = '" . $_SESSION['INGRESO']['item'] . "'
                    AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                    AND F.Autorizacion = DF.Autorizacion
                    AND F.Serie = DF.Serie
                    AND F.Factura = DF.Factura
                    AND F.Item = DF.Item
                    AND F.Periodo = DF.Periodo
                    AND F.TC = DF.TC
                    AND F.CodigoC <> DF.CodigoC";
            Ejecutar_SQL_SP($sql);

            if ($_SESSION['INGRESO']['periodo'] === G_NINGUNO) {
                for ($i = 1; $i <= 12; $i++) {
                    $sql = "UPDATE Facturas
                            SET IVA = (SELECT ROUND(SUM(Total_IVA),2,0)
                                        FROM Detalle_Factura
                                        WHERE Detalle_Factura.Total_IVA > 0
                                        AND Detalle_Factura.TC = Facturas.TC
                                        AND Detalle_Factura.Item = Facturas.Item
                                        AND Detalle_Factura.Periodo = Facturas.Periodo
                                        AND Detalle_Factura.Factura = Facturas.Factura
                                        AND Detalle_Factura.CodigoC = Facturas.CodigoC
                                        AND Detalle_Factura.Serie = Facturas.Serie
                                        AND Detalle_Factura.Autorizacion = Facturas.Autorizacion)
                            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                            AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                            AND MONTH(Fecha) = " . $i . "";
                    Ejecutar_SQL_SP($sql);

                    $sql = "UPDATE Facturas
                            SET Con_IVA = (SELECT ROUND(SUM(Total),2,0) 
                                            FROM Detalle_Factura
                                            WHERE Detalle_Factura.Total_IVA > 0
                                            AND Detalle_Factura.TC = Facturas.TC
                                            AND Detalle_Factura.Item = Facturas.Item
                                            AND Detalle_Factura.Periodo = Facturas.Periodo
                                            AND Detalle_Factura.Factura = Facturas.Factura
                                            AND Detalle_Factura.CodigoC = Facturas.CodigoC
                                            AND Detalle_Factura.Serie = Facturas.Serie
                                            AND Detalle_Factura.Autorizacion = Facturas.Autorizacion)
                            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                            AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                            AND MONTH(Fecha) = " . $i . "";
                    Ejecutar_SQL_SP($sql);

                    $sql = "UPDATE Facturas
                            SET Sin_IVA = (SELECT ROUND(SUM(Total),2,0) 
                                            FROM Detalle_Factura
                                            WHERE Detalle_Factura.Total_IVA <= 0
                                            AND Detalle_Factura.TC = Facturas.TC
                                            AND Detalle_Factura.Item = Facturas.Item
                                            AND Detalle_Factura.Periodo = Facturas.Periodo
                                            AND Detalle_Factura.Factura = Facturas.Factura
                                            AND Detalle_Factura.CodigoC = Facturas.CodigoC
                                            AND Detalle_Factura.Serie = Facturas.Serie
                                            AND Detalle_Factura.Autorizacion = Facturas.Autorizacion)
                            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                            AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                            AND MONTH(Fecha) = " . $i . "";
                    Ejecutar_SQL_SP($sql);

                    $sql = "UPDATE Facturas
                            SET Descuento = (SELECT ROUND(SUM(Total_Desc),2,0) 
                                            FROM Detalle_Factura
                                            WHERE Detalle_Factura.Total_Desc > 0
                                            AND Detalle_Factura.TC = Facturas.TC
                                            AND Detalle_Factura.Item = Facturas.Item
                                            AND Detalle_Factura.Periodo = Facturas.Periodo
                                            AND Detalle_Factura.Factura = Facturas.Factura
                                            AND Detalle_Factura.CodigoC = Facturas.CodigoC
                                            AND Detalle_Factura.Serie = Facturas.Serie
                                            AND Detalle_Factura.Autorizacion = Facturas.Autorizacion)
                            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                            AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                            AND MONTH(Fecha) = " . $i . "";
                    Ejecutar_SQL_SP($sql);

                    $sql = "UPDATE Facturas
                            SET Descuento2 = (SELECT ROUND(SUM(Total_Desc2),2,0) 
                                            FROM Detalle_Factura
                                            WHERE Detalle_Factura.Total_Desc2 > 0
                                            AND Detalle_Factura.TC = Facturas.TC
                                            AND Detalle_Factura.Item = Facturas.Item
                                            AND Detalle_Factura.Periodo = Facturas.Periodo
                                            AND Detalle_Factura.Factura = Facturas.Factura
                                            AND Detalle_Factura.CodigoC = Facturas.CodigoC
                                            AND Detalle_Factura.Serie = Facturas.Serie
                                            AND Detalle_Factura.Autorizacion = Facturas.Autorizacion)
                            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                            AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                            AND MONTH(Fecha) = " . $i . "";
                    Ejecutar_SQL_SP($sql);

                    $sql = "UPDATE Facturas
                            SET Desc_0 = (SELECT SUM(Total_Desc+Total_Desc2)
                                            FROM Detalle_Factura 
                                            WHERE Detalle_Factura.Total_IVA = 0 
                                            AND MONTH(Detalle_Factura.Fecha) = " . $i . " 
                                            AND Detalle_Factura.TC = Facturas.TC 
                                            AND Detalle_Factura.Item = Facturas.Item 
                                            AND Detalle_Factura.Periodo = Facturas.Periodo 
                                            AND Detalle_Factura.Fecha = Facturas.Fecha 
                                            AND Detalle_Factura.Factura = Facturas.Factura 
                                            AND Detalle_Factura.CodigoC = Facturas.CodigoC 
                                            AND Detalle_Factura.Serie = Facturas.Serie 
                                            AND Detalle_Factura.Autorizacion = Facturas.Autorizacion)
                            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                            AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                            AND TC IN ('FA','NV','LC')
                            AND Desc_0 = 0
                            AND MONTH(Fecha) = " . $i . "";
                    Ejecutar_SQL_SP($sql);

                    $sql = "UPDATE Facturas 
                            SET Desc_X = (SELECT SUM(Total_Desc+Total_Desc2) 
                                            FROM Detalle_Factura 
                                            WHERE Detalle_Factura.Total_IVA > 0 
                                            AND MONTH(Detalle_Factura.Fecha) = " . $i . " 
                                            AND Detalle_Factura.TC = Facturas.TC 
                                            AND Detalle_Factura.Item = Facturas.Item 
                                            AND Detalle_Factura.Periodo = Facturas.Periodo 
                                            AND Detalle_Factura.Fecha = Facturas.Fecha 
                                            AND Detalle_Factura.Factura = Facturas.Factura 
                                            AND Detalle_Factura.CodigoC = Facturas.CodigoC 
                                            AND Detalle_Factura.Serie = Facturas.Serie 
                                            AND Detalle_Factura.Autorizacion = Facturas.Autorizacion) 
                            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                            AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                            AND TC IN ('FA','NV','LC') 
                            AND Desc_X = 0 
                            AND MONTH(Fecha) = " . $i . "";
                    Ejecutar_SQL_SP($sql);
                    //TODO

                }
            }


        }*/
    }
}
