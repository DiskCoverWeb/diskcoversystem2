<?php
require_once (dirname(__DIR__, 2) . "/db/db1.php");
require_once (dirname(__DIR__, 2) . "/funciones/funciones.php");

/*
    AUTOR DE RUTINA	: Leonardo Súñiga
    FECHA CREACION	: 01/03/2024
    FECHA MODIFICACION: P/01/2024
    DESCIPCIÓN		: Modelo del modal FPensiones, se encarga de consultar a la base de datos.
*/

class FPensionesM
{

    private $db;

    public function __construct()
    {

        $this->db = new db();

    }

    public function DCInv($query = false): array
    {
        $sql = "SELECT Codigo_Inv + '  ' + Producto As NomProd, *
                FROM Catalogo_Productos 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND TC = 'P' 
                AND LEN(Cta_Inventario) = 1 
                AND INV <> 0 ";
        if (is_numeric($query)) {
            $sql .= "AND Codigo_Inv LIKE '%" . $query . "%' ";
        } else {
            $sql .= "AND Producto LIKE '%" . $query . "%' ";
        }
        $sql .= "ORDER BY Producto ";
        try {
            return $this->db->datos($sql);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function AdoAuxInsertar($parametros, $Mifecha): array
    {
        $sql = "SELECT TOP 10 Codigo 
                FROM Clientes_Facturacion 
                WHERE Codigo_Inv = '" . SinEspaciosIzq($parametros['CodigoP']) . "' 
                AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Fecha BETWEEN '" . BuscarFecha($parametros['FechaTexto']) . "' AND '" . BuscarFecha($Mifecha) . "' ";
        if ($parametros['CheqRangos'] <> 0) {
            $sql .= "AND GrupoNo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "' ";
        }
        return $this->db->datos($sql);
    }

    public function DeleteClientesFacturacion($parametros, $Mifecha): void
    {
        $sql = "DELETE 
                FROM Clientes_Facturacion 
                WHERE Codigo_Inv = '" . SinEspaciosIzq($parametros['CodigoP']) . "' 
                AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Fecha BETWEEN '" . BuscarFecha($parametros['FechaTexto']) . "' AND '" . BuscarFecha($Mifecha) . "' ";
        if ($parametros['CheqRangos'] <> 0) {
            $sql .= "AND GrupoNo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "' ";
        }
        try {
            Ejecutar_SQL_SP($sql);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function InsertClientesFacturacion($parametros, $NoAnio, $NoMes, $Mifecha, $Mesl): void
    {
        $sql = "INSERT INTO Clientes_Facturacion (T, GrupoNo, Codigo, Codigo_Inv, Valor, Descuento, 
        Descuento2, CodigoU, Item, Periodo, Num_Mes, Mes, Fecha) 
        SELECT 'N', Grupo, Codigo, '" . SinEspaciosIzq($parametros['CodigoP']) . "', 
        '" . $parametros['Valor'] . "', '" . $parametros['Total_Desc'] . "', '" . $parametros['Total_Desc2'] . "', 
        '" . $_SESSION['INGRESO']['CodigoU'] . "', '" . $_SESSION['INGRESO']['item'] . "', '" . $NoAnio . "', 
        '" . $NoMes . "', '" . $Mesl . "', '" . BuscarFecha($Mifecha) . "' 
        FROM Clientes 
        WHERE FA <> 0";
        if ($_SESSION['INGRESO']['Mas_Grupos']) {
            $sql .= "AND DirNumero = '" . $_SESSION['INGRESO']['item'] . "' ";
        }
        if ($parametros['CheqRangos'] <> 0) {
            $sql .= "AND Grupo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "' ";
        }
        $sql .= "ORDER BY Grupo, Cliente, Sexo ";
        try {
            Ejecutar_SQL_SP($sql);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    function Tipo_Cambio_Valor($parametros, $Mifecha): void
    {
        try {
            $sql = "UPDATE Clientes_Facturacion ";
            switch ($parametros['Tipo_Cambio']) {
                case "Pension":
                    $sql .= "SET Valor = Valor ";
                    break;
                case "Descuento":
                    $sql .= "SET Descuento = Descuento ";
                    break;
                case "Descuento2":
                    $sql .= "SET Descuento2 = Descuento2 ";
                    break;
            }

            if (floatval($parametros['Valor_Cambiar']) > 0) {
                $sql .= " + " . $parametros['Valor_Cambiar'] . " ";
            } else {
                $sql .= $parametros['Valor_Cambiar'] . " ";
            }

            $sql .= "WHERE Codigo_Inv = '" . SinEspaciosIzq($parametros['CodigoP']) . "' 
                 AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                 AND Fecha BETWEEN '" . BuscarFecha($parametros['FechaTexto']) . "' AND '" . BuscarFecha($Mifecha) . "' ";
            if ($parametros['Tipo_Cambio'] === 'Descuento2') {
                $sql .= "AND Descuento = 0";
            }

            if ($parametros['CheqRangos'] <> 0) {
                $sql .= "AND GrupoNo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "' ";
            }

            Ejecutar_SQL_SP($sql);

            $sql = "";
            switch ($parametros['Tipo_Cambio']) {
                case "Pension":
                    $sql = "DELETE 
                            FROM Clientes_Facturacion 
                            WHERE Valor <= 0 ";
                    break;
                case "Descuento":
                    $sql = "UPDATE Clientes_Facturacion
                            SET Descuento = 0 
                            WHERE Descuento < 0 ";
                    break;
                case "Descuento2":
                    $sql = "UPDATE Clientes_Facturacion
                            SET Descuento2 = 0 
                            WHERE Descuento2 < 0 ";
                    break;
            }

            $sql .= "AND Item = '" . $_SESSION['INGRESO']['item'] . "' ";
            Ejecutar_SQL_SP($sql);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function Copiar_Mes(): array
    {
        $sql = "SELECT Periodo, Num_Mes 
                FROM Clientes_Facturacion 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                GROUP BY Periodo, Num_Mes 
                ORDER BY Periodo, Num_Mes ";
        try {
            return $this->db->datos($sql);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function Multas_Delete_Update($parametros, $Mifecha): void
    {
        try {
            $sql = "DELETE  
                FROM Clientes_Facturacion 
                WHERE Codigo_Inv = '" . SinEspaciosIzq($parametros['CodigoP']) . "' 
                AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Fecha BETWEEN '" . BuscarFecha($parametros['FechaTexto']) . "' AND '" . BuscarFecha($Mifecha) . "' ";
            if ($parametros['CheqRangos'] <> 0) {
                $sql .= "AND GrupoNo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "' ";
            }
            Ejecutar_SQL_SP($sql);

            $sql = "UPDATE Clientes 
                SET X = 'M' 
                WHERE FA <> 0 ";
            Ejecutar_SQL_SP($sql);

            $sql = "UPDATE Clientes 
                    SET X = 'F' 
                    FROM Clientes As C, Facturas As F 
                    WHERE F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND F.TC IN ('FA','FM','NV') 
                    AND F.T <> 'A' 
                    AND F.Fecha BETWEEN '" . BuscarFecha($parametros['FechaTexto']) . "' AND '" . BuscarFecha($Mifecha) . "' ";
            if ($parametros['CheqRangos'] <> 0) {
                $sql .= "AND C.Grupo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "' ";
            }
            $sql .= "AND C.Codigo = F.CodigoC ";
            Ejecutar_SQL_SP($sql);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    public function Multas_Insert($parametros, $NoAnio, $NoMes, $Mifecha, $Mesl): void
    {
        $sql = "INSERT INTO Clientes_Facturacion (T, GrupoNo, Codigo, Codigo_Inv, Valor, CodigoU, Item, 
        Periodo, Num_Mes, Mes, Fecha)
        SELECT 'N', Grupo, Codigo, '" . SinEspaciosIzq($parametros['CodigoP']) .
            "', '" . $parametros['Valor'] . "', '" . $_SESSION['INGRESO']['CodigoU'] . "', '" . $_SESSION['INGRESO']['item'] .
            "', '" . $NoAnio . "', '" . $NoMes . "', '" . $Mesl . "', '" . BuscarFecha($Mifecha) . "' 
        FROM Clientes 
        WHERE FA <> 0
        AND X = 'M' ";
        if ($_SESSION['INGRESO']['Mas_Grupos']) {
            $sql .= "AND DirNumero = '" . $_SESSION['INGRESO']['item'] . "' ";
        }
        if ($parametros['CheqRangos'] <> 0) {
            $sql .= "AND Grupo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "' ";
        }
        $sql .= "ORDER BY Grupo, Cliente, Sexo ";
        try {
            Ejecutar_SQL_SP($sql);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function KeyDown_Delete($parametros, $Mifecha): void
    {
        $sql = "DELETE 
                FROM Clientes_Facturacion 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Fecha BETWEEN '" . BuscarFecha($parametros['FechaTexto']) . "' AND '" . BuscarFecha($Mifecha) . "' ";
        if ($parametros['CheqRangos'] <> 0) {
            $sql .= "AND GrupoNo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "' ";
        }
        try {
            Ejecutar_SQL_SP($sql);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function KeyDown_Insert($parametros, $NoAnio, $NoMes, $Mesl, $Mifecha, $Copiar_Periodo, $Copiar_Mes): void
    {
        $sql = "INSERT INTO Clientes_Facturacion (T, GrupoNo, Codigo, Codigo_Inv, Valor, Item, CodigoU, Periodo, Num_Mes, Mes, Fecha, D)
                SELECT 'N', GrupoNo, Codigo, Codigo_Inv, Valor, Item, '" . $_SESSION['INGRESO']['CodigoU'] . "', '" . $NoAnio . "', 
                '" . $NoMes . "', '" . $Mesl . "', '" . BuscarFecha($Mifecha) . "', 0 
                FROM Clientes_Facturacion 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo = '" . $Copiar_Periodo . "' 
                AND Num_Mes = '" . $Copiar_Mes . "' ";
        if ($parametros['CheqRangos'] <> 0) {
            $sql .= "AND GrupoNo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "' ";
        }
        $sql .= "ORDER BY GrupoNo, Codigo, Codigo_Inv ";
        try {
            Ejecutar_SQL_SP($sql);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }




}