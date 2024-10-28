<?php
require_once(dirname(__DIR__, 2) . "/db/db1.php");
require_once(dirname(__DIR__, 2) . "/funciones/funciones.php");

/*
    AUTOR DE RUTINA	: Leonardo Súñiga
    FECHA CREACION	: 04/03/2024
    FECHA MODIFICACION: 06/03/2024
    DESCIPCIÓN		: Modelo del modal FAsignaFact, se encarga de consultar con la base de datos.
*/

class FAsignaFactM
{
    private $db;

    public function __construct()
    {
        $this->db = new db();
    }

    public function AdoRubros(): array
    {
        try {
            $sql = "SELECT * 
                    FROM Tabla_Dias_Meses 
                    WHERE Tipo = 'M' 
                    AND No_D_M > 0 
                    ORDER BY No_D_M ";
            return $this->db->datos($sql);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function DCInv(): array
    {
        $sql = "SELECT Codigo_Inv + '  ' + Producto As NomProd, *
                FROM Catalogo_Productos 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND TC = 'P' 
                AND LEN(Cta_Inventario) = 1 
                AND INV <> 0 
                ORDER BY Codigo_Inv";
        try {
            return $this->db->datos($sql);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function Listar_Rubros_Grupo($parametros): array
    {
        try {
            $sql = "SELECT Mes, Codigo_Inv, Valor, Descuento, Descuento2, Periodo, Fecha, Codigo, Item 
                    FROM Clientes_Facturacion 
                    WHERE Codigo = '" . $parametros['CodigoCliente'] . "' 
                    AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    ORDER BY Periodo, Num_Mes, Codigo_Inv ";
            $AdoRubros = $this->db->datos($sql);
            $datos = grilla_generica_new($sql, 'Clientes_Facturacion', '', '', false, false, false, 1, 1, 1, 100);
            return array('datos' => $datos, 'AdoRubros' => $AdoRubros);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function Command1_Click($parametros): void
    {
        try {
            $sql = "DELETE 
                    FROM Clientes_Facturacion 
                    WHERE Codigo_Inv = '" . $parametros['CodigoP'] . "' 
                    AND Codigo = '" . $parametros['CodigoCliente'] . "' 
                    AND Num_Mes = 0 
                    AND Item = '" . $_SESSION['INGRESO']['item'] . "' ";
            Ejecutar_SQL_SP($sql);
            for ($i = 0; $i < count($parametros['LstMeses']); $i++) {
                $Dia = 1;
                $Anio = $parametros['Anio'];
                switch ($i) {
                    case 4:
                    case 6:
                    case 9:
                    case 11:
                        if ($Dia > 30) {
                            $Dia = 30;
                        }
                        break;
                    case 2:
                        if (($Anio % 4 <> 0) and ($Dia > 28)) {
                            $Dia = 28;
                        }
                        if (($Anio % 4 == 0) and ($Dia > 29)) {
                            $Dia = 29;
                        }
                        break;
                    default:
                        $Dia = 30;
                        break;
                }
                $FechaSigMes = date('d/m/Y', strtotime($Dia . '/' . $i . '/' . $Anio));
                $sql = "DELETE 
                        FROM Clientes_Facturacion 
                        WHERE Codigo_Inv = '" . $parametros['CodigoP'] . "' 
                        AND Codigo = '" . $parametros['CodigoCliente'] . "' 
                        AND Periodo = '" . $parametros['Anio'] . "' 
                        AND Num_Mes = '" . $parametros['LstMeses'][$i] . "' 
                        AND Item = '" . $_SESSION['INGRESO']['item'] . "' ";
                Ejecutar_SQL_SP($sql);
                SetAdoAddNew('Clientes_Facturacion');
                SetAdoFields('T', G_NORMAL);
                SetAdoFields('Codigo', $parametros['CodigoCliente']);
                SetAdoFields('Valor', $parametros['TxtArea']);
                SetAdoFields('Descuento', $parametros['Descuento']);
                SetAdoFields('Descuento2', $parametros['Descuento2']);
                SetAdoFields('Codigo_Inv', $parametros['CodigoP']);
                SetAdoFields('Num_Mes', $parametros['LstMeses'][$i]);
                SetAdoFields('Mes', MesesLetras($parametros['LstMeses'][$i]));
                SetAdoFields('GrupoNo', $parametros['Codigo2']);
                SetAdoFields('Fecha', $FechaSigMes);
                SetAdoFields('Item', $_SESSION['INGRESO']['item']);
                SetAdoFields('Periodo', $parametros['Anio']);
                SetAdoFields('CodigoU', $_SESSION['INGRESO']['CodigoU']);
                SetAdoUpdate();
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function UpdateValor($parametros): void
    {
        try {
            $sql = "UPDATE Clientes_Facturacion 
                    SET Valor = '" . $parametros['NuevoValor'] . "' 
                    WHERE Codigo = '" . $parametros['CodigoCliente'] . "'
                    AND Codigo_Inv = '" . $parametros['Codigos'] . "' 
                    AND Item = '" . $_SESSION['INGRESO']['item'] . "'";
            Ejecutar_SQL_SP($sql);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function UpdateDescuento($parametros): void
    {
        try {
            $sql = "UPDATE Clientes_Facturacion 
                    SET Descuento = '" . $parametros['NuevoValor'] . "' 
                    WHERE Codigo = '" . $parametros['CodigoCliente'] . "'
                    AND Codigo_Inv = '" . $parametros['Codigos'] . "' 
                    AND Item = '" . $_SESSION['INGRESO']['item'] . "'";
            Ejecutar_SQL_SP($sql);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function UpdateDescuento2($parametros): void
    {
        try {
            $sql = "UPDATE Clientes_Facturacion 
                    SET Descuento2 = '" . $parametros['NuevoValor'] . "' 
                    WHERE Codigo = '" . $parametros['CodigoCliente'] . "'
                    AND Codigo_Inv = '" . $parametros['Codigos'] . "' 
                    AND Item = '" . $_SESSION['INGRESO']['item'] . "'";
            Ejecutar_SQL_SP($sql);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


}