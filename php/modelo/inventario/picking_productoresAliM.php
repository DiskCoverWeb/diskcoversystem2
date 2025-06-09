<?php

require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");
/**
 */
/**
 * 
 */
class picking_productoresAliM 
{
	
	private $db;
	function __construct()
	{
	    $this->db = new db();
	}

	public function tipoBeneficiario($query = '',$estado = '1',$dia=false)
    {
        $sql = "select *,CP.Proceso as 'TipoBene',CP.Color,CP.Picture  FROM Clientes C  
			LEFT JOIN Catalogo_Proceso CP ON C.Actividad = CP.Cmds 
            WHERE CP.Item = '" . $_SESSION['INGRESO']['item'] . "'
            AND Actividad = '93.04'";
        if ($query != '') {
            if (!is_numeric($query)) {
                $sql .= " AND C.Cliente LIKE '%" . $query . "%'";
            } else {
                $sql .= " AND C.CI_RUC LIKE '%" . $query . "%'";
            }
        }

        if($dia)
        {
            $sql.=" AND  ( Dia_Ent = '".$dia."'";
        }

        if($estado==1)
        {
            $sql.=" OR Estado = ".$estado." )";
        }else
        {
            $sql.=" AND Estado = ".$estado;
        }     

        $sql .= " ORDER BY C.Cliente";

        // print_r($sql);die();
        try {
            return $this->db->datos($sql);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    function tipo_asignacion()
    {
        $sql = "SELECT ".Full_Fields('Catalogo_Proceso')." 
                FROM Catalogo_Proceso 
                WHERE TP = 'TIPOASIG' 
                AND Item='".$_SESSION['INGRESO']['item']."' ";
        return $this->db->datos($sql);    
    }

    function asignaciones_hechas($beneficiario)
    {
        $sql = "SELECT DISTINCT No_Hab
                FROM Detalle_Factura 
                WHERE CodigoC = '".$beneficiario."'
                AND T= 'K'
                AND Fecha = '".date('Y-m-d')."'
                AND Item='".$_SESSION['INGRESO']['item']."'
                AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
                // print_r($sql);die();
        return $this->db->datos($sql);    
    }

}

?>