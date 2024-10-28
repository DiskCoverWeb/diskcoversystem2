<?php
require_once(dirname(__DIR__, 2) . "/db/db1.php");
require_once(dirname(__DIR__, 2) . "/funciones/funciones.php");
@session_start();

class FAbonosAnticipadoM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function SelectDB_Combo_DCCtaAnt() //Para DCCtaAnt
    {
        $sql = "SELECT (Codigo + ' ' + Cuenta) As NomCuenta
                FROM Catalogo_Cuentas
                WHERE TC = 'P'
                AND DG = 'D'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                ORDER BY TC DESC,Codigo";
        return $this->db->datos($sql);
    }

    function SelectDB_Combo_DCBanco()
    {
        $sql = "SELECT (Codigo + ' ' + Cuenta) as NomCuenta
                FROM Catalogo_Cuentas
                WHERE TC IN ('CJ','BA','TJ')
                AND DG = 'D'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                ORDER BY TC DESC,Codigo";
        return $this->db->datos($sql);
    }

    function SelectDB_Combo_DCTipo($fa_factura)
    {
        $sql = "SELECT TC
                FROM Facturas
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND TC = 'OP'
                AND Factura = '" . $fa_factura . "'
                GROUP BY TC
                ORDER BY TC DESC";
        return $this->db->datos($sql);
    }

    function SelectDB_Combo_DCClientes($grupo = G_NINGUNO)
    {
        $sql = "SELECT TOP 50 Grupo,Codigo,Cliente,Email,Email2
                FROM Clientes
                WHERE FA != 0";
        if ($grupo != G_NINGUNO) {
            $sql .= " AND GRUPO = '" . $grupo . "'";
        }
        $sql .= " ORDER BY Cliente"; // <> adFalse linea 56
        //print_r($sql);
        return $this->db->datos($sql);
    }

    function Select_Adodc_AdoIngCaja_Catalogo_CxCxp($codigo_cliente, $sub_cta_gen)
    {
        $sql = "SELECT *
                FROM Catalogo_CxCxp
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND Codigo = '" . $codigo_cliente . "'
                AND Cta = '" . $sub_cta_gen . "'
                AND TC = 'P' ";
        $data = $this->db->datos($sql);
        if (count($data) <= 0) {
            SetAdoAddNew('Catalogo_CxCxp');
            SetAdoFields('Item', $_SESSION['INGRESO']['item']);
            SetAdoFields('Periodo', $_SESSION['INGRESO']['periodo']);
            SetAdoFields('Codigo', $codigo_cliente);
            SetAdoFields('Cta', $sub_cta_gen);
            SetAdoFields('TC', 'P');
            SetAdoUpdate();
            return 1;
        }
        return $data;
    }

    function Select_Adodc_AdoIngCaja_Asiento_SC($parametros)
    {
        $sql = "INSERT INTO Asiento_SC
        (Prima,Fecha_V,TC,Factura,Codigo,Beneficiario,Detalle_SubCta,Cta,
         DH,Valor,Valor_ME,TM,Item,T_No,SC_No,CodigoU,Serie,Fecha_D,Fecha_H,Bloquear)
         VALUES
         (0,
         '" . $parametros['Fecha_V'] . "',
         'P',
          0,
          '" . $parametros['CodigoC'] . "',
          '" . $parametros['NombreC'] . "',
          'Abono Anticipado',
          '" . $parametros['SubCtaGen'] . "',
          '2',
          '" . $parametros['Total'] . "',
          0,
          '1',
          '" . $_SESSION['INGRESO']['item'] . "',
          '" . $parametros['Trans_No'] . "',
          1,
          '" . $_SESSION['INGRESO']['CodigoU'] . "',
          null,
          null,
          null,
          null
         )";
        return $this->db->String_Sql($sql);
    }

    function Select_Adocdc_AdoIngCaja_Asiento($trans_no)
    {
        $sql = "SELECT *
                FROM Asiento
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'
                AND T_No = '" . $trans_no . "'";
        return $this->db->datos($sql);
    }

    function SelectDB_Combo_DCFactura_AdoFactura($TipoFactura, $fa_factura = false)
    {
        $sql = "SELECT F.TC,F.Factura,F.CodigoC,F.Fecha,F.Fecha_V,F.Saldo_MN,
        F.Cta_CxP,F.Nota,F.Observacion,C.Cliente,C.Direccion,C.CI_RUC,C.Telefono,
        C.Grupo
        FROM Facturas As F, Clientes As C
        WHERE F.T = 'P'
        AND F.Item = '" . $_SESSION['INGRESO']['item'] . "'
        AND F.TC = '" . $TipoFactura . "'
        AND F.Periodo =  '" . $_SESSION['INGRESO']['periodo'] . "'";
        if ($TipoFactura == "OP") {
            $sql .= "AND F.Factura = '" . $fa_factura . "'";
        }
        $sql .= "AND F.CodigoC = C.Codigo
                 ORDER BY F.TC,F.Factura";
        return $this->db->datos($sql);

    }

    function InsertarAsientos($CodCta, $Parcial_MEs, $Debes, $Habers, $CodigoCli)
    {
        $InsAsiento = False;
        $Cuenta = '';
        $Ln_No = 0;
        if ($Debes > 0 || $Habers > 0 && $CodCta <> "") {
            if ($CodCta <> "0") {
                $sql = "SELECT TC, Codigo, Cuenta
                        FROM Catalogo_Cuentas
                        WHERE Codigo = '" . $CodCta . "'
                        AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                        AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'";
                $AdoReg = $this->db->datos($sql);
                if (count($AdoReg) > 0) {
                    $InsAsiento = True;
                    $Cuenta = $AdoReg['Cuenta'];
                    $SubCta = $AdoReg['TC'];
                }
            }
            /*if (!$InsAsiento || strlen($CodCta) > 2) {
                $sql = "SELECT TC, Codigo, Cuenta
                        FROM Catalogo_Cuentas
                        WHERE Codigo = '" . $CodCta . "'
                        AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                        AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'";
                $AdoReg = $this->db->datos($sql);
                if (count($AdoReg) > 0) {
                    $InsAsiento = True;
                    $CodCta = $AdoReg['Codigo'];
                    $Cuenta = $AdoReg['Cuenta'];
                    $SubCta = $AdoReg['TC'];
                }
            }*/
            if ($InsAsiento) {
                SetAdoAddNew('Asiento');
                SetAdoFields('CODIGO', $CodCta);
                SetAdoFields('CUENTA', $Cuenta);
                SetAdoFields('DETALLE', null); //Donde se llena detalle?
                SetAdoFields('PARCIAL_ME', round($Parcial_MEs, 2, PHP_ROUND_HALF_UP));
                SetAdoFields('DEBE', round($Debes, 2, PHP_ROUND_HALF_UP));
                SetAdoFields('HABER', round($Habers, 2, PHP_ROUND_HALF_UP));
                SetAdoFields('Item', $_SESSION['INGRESO']['item']);
                SetAdoFields('T_No', 200);
                SetAdoFields('ME', False);
                SetAdoFields('EFECTIVIZAR', null);
                SetAdoFields('CODIGO_C', $CodigoCli);
                SetAdoFields('CODIGO_CC', null);
                SetAdoFields('CHEQ_DEP', null);
                SetAdoFields('CodigoU', $_SESSION['INGRESO']['CodigoU']);
                SetAdoFields('A_No', $Ln_No);
                SetAdoFields('TC', $SubCta);
                SetAdoUpdate();
                $Ln_No = $Ln_No + 1;
                return 1;
            }
            return -1;
        }
        return -1;
    }

}
?>