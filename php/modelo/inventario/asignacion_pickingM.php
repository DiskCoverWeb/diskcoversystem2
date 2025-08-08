<?php
require_once (dirname(__DIR__, 2) . "/db/db1.php");
require_once (dirname(__DIR__, 2) . "/funciones/funciones.php");

class asignacion_pickingM
{

    private $db;

    public function __construct()
    {

        $this->db = new db();

    }

    function tipoBeneficiario($codigo = '', $fecha = '')
    {
        // $sql = "SELECT DISTINCT DF.Ruta,No_Hab,C.Codigo, C.CodigoA,CP5.Proceso AS 'Estado', C.Cliente, C.CI_RUC, CD.Fecha_Registro, CD.Envio_No,CP3.Proceso as 'Frecuencia',CD.CodigoA as CodigoACD,CP4.Proceso as'TipoEntega' ,CD.Beneficiario, CD.No_Soc, CD.Area, CD.Acreditacion,CP1.Proceso as 'AccionSocial', CD.Tipo, CD.Cod_Fam,CP2.Proceso as 'TipoAtencion', CD.Salario, CD.Descuento, CD.Evidencias, CD.Item,C.Actividad,CP.Proceso as 'TipoBene',CP.Color,CP.Picture,CD.Hora_Ent as 'Hora',CD.Tipo_Dato as 'CodVulnera',CP6.Proceso AS 'vulnerabilidad',CD.Observaciones,CD.Hora_Ent,CD.Dia_Ent,CP7.Proceso as 'Tipo Asignacion',DF.CodigoU,A.Nombre_Completo,DF.Orden_No 
        //     FROM Detalle_Factura DF
        //     INNER JOIN Accesos A on DF.CodigoU = A.Codigo
        //     INNER JOIN Clientes C on DF.CodigoC = C.Codigo
        //     INNER JOIN Clientes_Datos_Extras as CD ON C.Codigo = CD.Codigo 
        //     LEFT JOIN Catalogo_Proceso CP ON C.Actividad = CP.Cmds 
        //     LEFT JOIN Catalogo_Proceso CP1 ON CD.Acreditacion = CP1.Cmds 
        //     LEFT JOIN Catalogo_Proceso CP2 ON CD.Cod_Fam = CP2.Cmds 
        //     LEFT JOIN Catalogo_Proceso CP3 ON CD.Envio_No = CP3.Cmds 
        //     LEFT JOIN Catalogo_Proceso CP4 ON CD.CodigoA = CP4.Cmds 
        //     LEFT JOIN Catalogo_Proceso CP5 ON C.CodigoA = CP5.Cmds 
        //     LEFT JOIN Catalogo_Proceso CP6 ON CD.Tipo_Dato= CP6.Cmds 
        //     LEFT JOIN Catalogo_Proceso CP7 ON DF.No_Hab= CP7.Cmds 
		// 	WHERE DF.Item = '".$_SESSION['INGRESO']['item']."'
		// 	AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
		// 	if($fecha)
		// 	{
		// 		$sql.=" AND DF.Fecha = '".$fecha."'";
		// 	}else{
		// 		$sql.=" AND DF.Fecha = '".date('Y-m-d')."'";
        //     }
		// 	if($codigo)
		// 	{
		// 		$sql.=" AND C.Cliente like '%".$codigo."%'";
		// 	}
		// 	$sql.=" AND DF.TC = 'OP'
		// 	AND DF.T = 'K'
        //     AND DF.Item = CD.Item
        //     AND CD.Item = CP.Item 
        //     AND CD.Item = CP1.Item 
        //     AND CD.Item = CP2.Item 
        //     AND CD.Item = CP3.Item 
        //     AND CD.Item = CP4.Item  
        //     AND CD.Item = CP5.Item  
        //     AND CD.Item = CP6.Item  
        //     AND CD.Item = CP7.Item";     

        //     print_r($sql);die();   

        // try {
        //     return $this->db->datos($sql);
        // } catch (Exception $e) {
        //     throw new Exception($e);
        // }

        $sql = "SELECT DISTINCT DF.Ruta,No_Hab,C.Codigo, C.CodigoA, C.Cliente, C.CI_RUC, CD.Fecha_Registro, CD.Envio_No,CD.CodigoA as CodigoACD,CD.Beneficiario, CD.No_Soc, CD.Area, CD.Acreditacion, CD.Tipo, 
            CD.Cod_Fam,CD.Salario, CD.Descuento, CD.Evidencias, 
            CD.Item,C.Actividad,CD.Hora_Ent as 'Hora',
            CD.Tipo_Dato as 'CodVulnera',CD.Observaciones,CD.Hora_Ent,CD.Dia_Ent,
            CP7.Proceso as 'Tipo Asignacion',DF.CodigoU,A.Nombre_Completo,DF.Orden_No 
            FROM Detalle_Factura DF 
            INNER JOIN Accesos A on DF.CodigoU = A.Codigo 
            INNER JOIN Clientes C on DF.CodigoC = C.Codigo 
            INNER JOIN Clientes_Datos_Extras as CD ON C.Codigo = CD.Codigo 
            LEFT JOIN Catalogo_Proceso CP7 ON DF.No_Hab= CP7.Cmds   
            WHERE DF.Item = '".$_SESSION['INGRESO']['item']."'
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND LEN(CD.Dia_Ent)=3";
             if($fecha)
             {
                 $sql.=" AND DF.Fecha = '".$fecha."'";
             }else{
                 $sql.=" AND DF.Fecha = '".date('Y-m-d')."'";
                }
             if($codigo)
             {
                 $sql.=" AND C.Cliente like '%".$codigo."%'";
             }
                 $sql.=" AND DF.TC = 'OP'
                 AND DF.T = 'K'
                 AND DF.Item = CD.Item 
                 AND CD.Item = CP7.Item"; 
                 // print_r($sql);die();
            return $this->db->datos($sql);
    }

    function tipoBeneficiarioPickFac($codigo = '', $fecha = '')
    {
        $sql = "SELECT DISTINCT DF.Ruta,No_Hab,C.Codigo, C.CodigoA, C.Cliente, C.CI_RUC, CD.Fecha_Registro, CD.Envio_No,CD.CodigoA as CodigoACD,CD.Beneficiario, CD.No_Soc, CD.Area, CD.Acreditacion, CD.Tipo, 
            CD.Cod_Fam,CD.Salario, CD.Descuento, CD.Evidencias, 
            CD.Item,C.Actividad,CD.Hora_Ent as 'Hora',
            CD.Tipo_Dato as 'CodVulnera',CD.Observaciones,CD.Hora_Ent,CD.Dia_Ent,
            CP7.Proceso as 'Tipo Asignacion',DF.CodigoU,A.Nombre_Completo,DF.Orden_No,DF.T 
            FROM Detalle_Factura DF 
            INNER JOIN Accesos A on DF.CodigoU = A.Codigo 
            LEFT JOIN Clientes C on DF.CodigoC = C.Codigo 
            LEFT JOIN Clientes_Datos_Extras as CD ON C.Codigo = CD.Codigo 
            LEFT JOIN Catalogo_Proceso CP7 ON DF.No_Hab= CP7.Cmds   
            WHERE DF.Item = '".$_SESSION['INGRESO']['item']."'
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND LEN(CD.Dia_Ent)=3";
            if($fecha)
            {
                $sql.=" AND DF.Fecha = '".$fecha."'";
            }else{
                $sql.=" AND DF.Fecha = '".date('Y-m-d')."'";
            }
            if($codigo)
            {
                $sql.=" AND C.Cliente like '%".$codigo."%'";
            }
            $sql.=" AND DF.TC = 'OP'
            AND (DF.T = 'K' OR DF.T = 'KF' )
            ";     

            // print_r($sql);die();   

        try {
            return $this->db->datos($sql);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

     function tipoBeneficiarioPickFacFam($codigo = '', $fecha = '')
    {
        $sql = "SELECT DISTINCT  Orden_No,CodigoB,CP.Proceso as grupo,DF.CodigoL,CP2.Proceso as familia,DF.No_Hab,CP3.Proceso as TipoEntega,DF.T  
            FROM Detalle_Factura DF
            INNER JOIN Accesos A on DF.CodigoU = A.Codigo
            INNER JOIN Clientes C on DF.CodigoC = C.Codigo
            LEFT JOIN Catalogo_Proceso CP ON DF.CodigoB = CP.Cmds 
            LEFT JOIN Catalogo_Proceso CP2 ON DF.CodigoL = CP2.Cmds 
            INNER JOIN Catalogo_Proceso CP3 on DF.No_Hab = CP3.ID 
            WHERE DF.Item = '".$_SESSION['INGRESO']['item']."'
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
            if($fecha)
            {
                $sql.=" AND DF.Fecha = '".$fecha."'";
            }else{
                $sql.=" AND DF.Fecha = '".date('Y-m-d')."'";
            }
            if($codigo)
            {
                $sql.=" AND C.Cliente like '%".$codigo."%'";
            }
            $sql.=" AND DF.TC = 'OF'
            AND (DF.T = 'K' OR DF.T = 'KF' )
            AND CP.Item = DF.Item ";     

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

    function listaAsignacion($beneficiario,$T=false,$tipo=false,$tipoVenta=false,$fecha=false,$orden=false)
    {
         $sql = "SELECT ".Full_Fields("Detalle_Factura")."
                FROM Detalle_Factura
                WHERE Item = '".$_SESSION['INGRESO']['item']."' 
                AND Periodo='".$_SESSION['INGRESO']['periodo']."'
                AND CodigoC = '".$beneficiario."'
                AND Fecha =  '".$fecha."'
                AND Factura =  '0'";
                if($orden)
                {
                    $sql.=" AND Orden_No = '".$orden."'";
                }
                if($T)
                {
                    $sql.="AND T = '".$T."'";
                }
                if($tipo)
                {
                    $sql.=" AND Codigo = '".$tipo."'";
                }
                if($tipoVenta)
                {
                    $sql.=" AND No_Hab = '".$tipoVenta."'";
                }
                print_r($sql);die();
        try{
            return $this->db->datos($sql);
        }catch(Exception $e){
            throw new Exception($e);
        }
    }

    function eliminarLinea($id)
    {
        $sql = "DELETE FROM Trans_Comision WHERE ID = '".$id."'";
        // print_r($sql);
        return $this->db->String_Sql($sql);
    }

    function cargar_asignacion($bene,$tipo,$T,$fecha=false,$orden=false)
    {
        $sql = "SELECT TC.ID,TC.Fecha,TC.Fecha_C,A.Nombre_Completo,TC.Total,TC.CodBodega,T,Codigo_Barra
                FROM Trans_Comision TC
                INNER JOIN Accesos A ON TC.CodigoU = A.Codigo
                WHERE Item = '".$_SESSION['INGRESO']['item']."'
                AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."'
                AND TC.CodigoC = '".$bene."'
                AND TC.Cta = '".$tipo."'
                AND TC.T = '".$T."'
                AND TC.Orden_No = '".$orden."'";
                if($fecha)
                {
                    //fecha_A es la fecha de asignacion
                    $sql.=" AND TC.Fecha_A = '".$fecha."'";
                }
                // print_r($sql);die();
        return $this->db->datos($sql);    
    }

    function total_ingresados($bene,$tipo,$tipoventa,$fecha,$orden)
    {
        $sql = "SELECT SUM(TC.Total) as Total
                FROM Trans_Comision TC
                INNER JOIN Accesos A ON TC.CodigoU = A.Codigo
                WHERE CodigoC = '".$bene."'
                AND Codigo_Inv = '".$tipo."'
                AND Cta = '".$tipoventa."'
                AND T = 'P'
                AND Fecha >= '".$fecha."' 
                AND Orden_No  = '".$orden."'";

                // print_r($sql);die();
        return $this->db->datos($sql);   
    }

    function asignaciones_hechas($beneficiario)
    {
        $sql = "SELECT DISTINCT No_Hab
                FROM Detalle_Factura 
                WHERE CodigoC = '".$beneficiario."'
                AND T= 'K'
                AND Item='".$_SESSION['INGRESO']['item']."'
                AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
        return $this->db->datos($sql);    
    }

    function catalogo_bodetagas($cod)
    {
        $sql = "SELECT * FROM Catalogo_Bodegas 
        WHERE Item = '".$_SESSION['INGRESO']['item']."'
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
        AND CodBod in (".$cod.")";

        // print_r($sql);
        return $this->db->datos($sql);    
    }

    function lineasKArdex($codBarras)
    {
        $sql ="SELECT TK.Codigo_Barra,TK.Fecha,CP.Producto
            FROM trans_kardex TK
            INNER JOIN Catalogo_Productos CP on TK.Codigo_Inv = CP.Codigo_Inv 
            WHERE  TK.item = '".$_SESSION['INGRESO']['item']."'
            AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND TK.Item = CP.Item 
            AND TK.Periodo = CP.Periodo
            ANd TK.Codigo_Barra = '".$codBarras."'";
               // print_r($sql);die();

        return $this->db->datos($sql);   
    }

     function lista_stock_ubicado($bodega=false,$cod_barras  =false,$grupo=false)
    {
        $sql="select TK.*,Producto
            FROM Trans_Kardex TK
            INNER JOIN Catalogo_Productos CP on TK.Codigo_Inv = CP.Codigo_Inv
            where TK.Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND TK.Item = '".$_SESSION['INGRESO']['item']."'
            AND TK.Item = CP.Item
            AND TK.Orden_No <> '0'
            AND TK.Orden_No <> '.'
            AND TK.Orden_No <> '0.'
            AND TK.CodBodega <> '-1'
            AND TK.T = 'N'
            AND TK.Salida = '0'";
            if($bodega)
            {
                $sql.=" AND CodBodega = '".$bodega."'";
            }
            if($cod_barras)
            {
                $sql.=" AND  TK.Codigo_Barra like '%".$cod_barras."%'";
            }
            if($grupo)
            {
                $sql.=" AND CP.Codigo_Inv = '".$grupo."' ";

            }

            // print_r($sql);die();
        return $this->db->datos($sql);
    }


    function lista_lineas_pickking($fecha,$codigoC,$tipo)
    {
        $sql = "SELECT   ".Full_Fields("Detalle_Factura")."
                FROM Detalle_Factura
                WHERE  Periodo = '".$_SESSION['INGRESO']['periodo']."' 
                AND Item = '".$_SESSION['INGRESO']['item']."' 
                AND T = 'K' 
                AND Fecha = '".$fecha."' 
                AND CodigoC = '".$codigoC."' 
                AND No_Hab = '".$tipo."'";

                // print_r($sql);die();
        return $this->db->datos($sql);

    }

    function delete_lineas($fecha,$codigoC,$tipo)
    {
        $sql = "DELETE FROM Detalle_Factura
                WHERE  Periodo = '".$_SESSION['INGRESO']['periodo']."' 
                AND Item = '".$_SESSION['INGRESO']['item']."' 
                AND T = 'K' 
                AND Fecha = '".$fecha."' 
                AND CodigoC = '".$codigoC."' 
                AND No_Hab = '".$tipo."'";

        // print_r($sql);die();
        return $this->db->String_Sql($sql);
    }

    function delete_lineasFam($codigoC,$tipo)
    {
        $sql = "DELETE FROM Detalle_Factura
                WHERE  Periodo = '".$_SESSION['INGRESO']['periodo']."' 
                AND Item = '".$_SESSION['INGRESO']['item']."' 
                AND T = 'K' 
                AND Orden_No = '".$codigoC."' 
                AND No_Hab = '".$tipo."'";
        $this->db->String_Sql($sql);

        $sql = "DELETE FROM Trans_Comision
                WHERE  Periodo = '".$_SESSION['INGRESO']['periodo']."' 
                AND Item = '".$_SESSION['INGRESO']['item']."' 
                AND T = 'P' 
                AND Orden_No = '".$codigoC."' 
                AND Cta = '".$tipo."'";
        return $this->db->String_Sql($sql);
    }

    function buscar_trans_kardex($id)
    {
         $sql ="SELECT * FROM Trans_kardex WHERE 
         Periodo = '".$_SESSION['INGRESO']['periodo']."'
         AND Item = '".$_SESSION['INGRESO']['item']."'
         AND ID = '".$id."'";
               // print_r($sql);die();

        return $this->db->datos($sql);   

    }
}