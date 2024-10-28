<?php

require_once (dirname(__DIR__, 2) . "/db/db1.php");
require_once (dirname(__DIR__, 2) . "/funciones/funciones.php");

class asignacion_osM
{

    private $db;

    public function __construct()
    {

        $this->db = new db();

    }

    public function tipoBeneficiario($query = '',$estado = '1',$dia=false)
    {
        $sql = "SELECT DISTINCT TOP 100 C.Codigo, C.CodigoA,CP5.Proceso AS 'Estado', C.Cliente, C.CI_RUC, CD.Fecha_Registro, CD.Envio_No,CP3.Proceso as 'Frecuencia',CD.CodigoA as CodigoACD,CP4.Proceso as'TipoEntega' ,CD.Beneficiario, CD.No_Soc, CD.Area, CD.Acreditacion,CP1.Proceso as 'AccionSocial', CD.Tipo, CD.Cod_Fam,CP2.Proceso as 'TipoAtencion', CD.Salario, CD.Descuento, CD.Evidencias, CD.Item,C.Actividad,CP.Proceso as 'TipoBene',CP.Color,CP.Picture,CD.Hora_Ent as 'Hora',CD.Tipo_Dato as 'CodVulnera',CP6.Proceso AS 'vulnerabilidad',CD.Hora_Ent,C.Dia_Ent,C.Estado as 'ClienteEstado' 
            FROM Clientes as C 
            INNER JOIN Clientes_Datos_Extras as CD ON C.Codigo = CD.Codigo 
            LEFT JOIN Catalogo_Proceso CP ON C.Actividad = CP.Cmds 
            LEFT JOIN Catalogo_Proceso CP1 ON CD.Acreditacion = CP1.Cmds 
            LEFT JOIN Catalogo_Proceso CP2 ON CD.Cod_Fam = CP2.Cmds 
            LEFT JOIN Catalogo_Proceso CP3 ON CD.Envio_No = CP3.Cmds 
            LEFT JOIN Catalogo_Proceso CP4 ON CD.CodigoA = CP4.Cmds 
            LEFT JOIN Catalogo_Proceso CP5 ON C.CodigoA = CP5.Cmds 
            LEFT JOIN Catalogo_Proceso CP6 ON CD.Tipo_Dato= CP6.Cmds 
            WHERE CD.Item = '" . $_SESSION['INGRESO']['item'] . "'
            AND CD.Item = CP.Item 
            AND CD.Item = CP1.Item 
            AND CD.Item = CP2.Item 
            AND CD.Item = CP3.Item 
            AND CD.Item = CP4.Item  
            AND CD.Item = CP5.Item";
        if ($query != '') {
            if (!is_numeric($query)) {
                $sql .= " AND C.Cliente LIKE '%" . $query . "%'";
            } else {
                $sql .= " AND C.CI_RUC LIKE '%" . $query . "%'";
            }
        }

        if($dia)
        {
            $sql.=" AND  ( C.Dia_Ent = '".$dia."'";
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

    public function datosExtra($consulta)
    {
        $sql = "SELECT Proceso, Cmds, TP, Color 
                FROM Catalogo_Proceso
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND CMDS IN " . $consulta . "
                ORDER BY TP";
        try{
            return $this->db->datos($sql);
        }catch(Exception $e){
            throw new Exception($e);
        }
    }

    function listaAsignacion($beneficiario,$T=false,$tipo=false)
    {
         $sql = "SELECT ".Full_Fields("Detalle_Factura")."
                FROM Detalle_Factura
                WHERE Item = '".$_SESSION['INGRESO']['item']."' 
                AND Periodo='".$_SESSION['INGRESO']['periodo']."'
                AND CodigoC = '".$beneficiario."'
                AND Fecha = '".date('Y-m-d')."'";
                if($T)
                {
                    $sql.="AND T = '".$T."'";
                }
                if($tipo)
                {
                    $sql.=" AND No_Hab = '".$tipo."'";
                }
        try{
            return $this->db->datos($sql);
        }catch(Exception $e){
            throw new Exception($e);
        }
    }

    function eliminarLinea($id)
    {
        $sql = "DELETE FROM Detalle_Factura WHERE ID = '".$id."'";
        return $this->db->String_Sql($sql);
    }

    function llenarCamposPoblacion($codigo)
    {
        $sqlFecha = "SELECT MAX(FechaM) AS UltimaFecha FROM Trans_Tipo_Poblacion 
                     WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                     AND CodigoC = '" . $codigo . "'";

        $resultadoFecha = $this->db->datos($sqlFecha);

        $ultimaFecha = $resultadoFecha[0]['UltimaFecha']->format('Y-m-d');

        $sqlRegistros = "SELECT ".Full_Fields('Trans_Tipo_Poblacion')." FROM Trans_Tipo_Poblacion TP
                        WHERE  TP.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                              AND CodigoC =  '" . $codigo. "' 
                              AND FechaM =  (SELECT MAX(FechaM) FROM Trans_Tipo_Poblacion) ";
                         // print_r($sqlRegistros);die();

        return $this->db->datos($sqlRegistros);
    }

    function tipo_poblacion()
    {
        $sql = "SELECT ".Full_Fields('Catalogo_Proceso')." 
                FROM Catalogo_Proceso 
                WHERE TP = 'POBLACIO'";
        return $this->db->datos($sql);    
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

    function alimentosRecibidoscompra($query)
    {
        $sql = "SELECT DISTINCT TK.Codigo_Inv,CP.Producto,CP.* 
                FROM Trans_kardex TK
                INNER JOIN Catalogo_Productos CP ON TK.Codigo_Inv = CP.Codigo_Inv
                INNER JOIN Trans_Correos TC ON TK.Orden_No = TC.Envio_No
                WHERE Cod_C = 'AR01'
                AND CP.Item = TK.Item
                AND CP.Periodo = TK.Periodo";
                if($query)
                {
                    $sql = " AND CP.Producto like '%".$query."%'";
                }
         return $this->db->datos($sql);    
    }

    function cambiar_estado($codigo)
    {
        $sql = "UPDATE Clientes SET Estado = 1 WHERE Codigo = '".$codigo."'";
        return $this->db->String_Sql($sql);
    }
    function cambiar_estado_all()
    {
        $sql = "UPDATE Clientes SET Estado = 0 WHERE 1 = 1";
        return $this->db->String_Sql($sql);
    }
    function cambiar_estado_eliminado($codigo)
    {
        $sql = "UPDATE Clientes SET Estado = 0 WHERE Codigo = '".$codigo."'";
        return $this->db->String_Sql($sql);
    }





}