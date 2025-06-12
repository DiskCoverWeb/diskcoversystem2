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
        $sql = "select C.*,CP.Proceso as 'TipoBene',CP.Color,CP.Picture,CP3.Proceso as 'Frecuencia'  
        	FROM Clientes C          	
            INNER JOIN Clientes_Datos_Extras as CD ON C.Codigo = CD.Codigo 
			LEFT JOIN Catalogo_Proceso CP ON C.Actividad = CP.Cmds 
			LEFT JOIN Catalogo_Proceso CP3 ON CD.Envio_No = CP3.Cmds 
            WHERE CP.Item = '" . $_SESSION['INGRESO']['item'] . "'
            AND CD.Item = CP3.Item
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

    function grupoProducto()
    {

        $sql = "SELECT Periodo, TC, Codigo_Inv, Producto, Detalle, Codigo_Barra, Unidad, Minimo, Maximo, PVP, IVA, Cta_Inventario, Cta_Costo_Venta, Cta_Ventas, Cta_Ventas_0, Cta_Venta_Anticipada, INV, Stock_Anterior, Entradas, 
                         Salidas, Promedio, Stock_Actual, Valor_Total, Item, PX, PY, Item_Banco, Desc_Item, Valor_Corte, Ayuda, Utilidad, Valor_Compra, Gramaje, Agrupacion, Cta_Ventas_Ant, Bodega, TP, Div, Receta, PVP_2, PVP_3, Codigo_IESS, 
                         Codigo_RES, Marca, TDP, Fecha, Codigo_P, Codigo_R, Factura, Vida_Util, Valor_Actual, Valor_Deprecicacion, Tipo, Ubicacion, Departamento, Cantidad, Servicio, Por_Reservas, X, Codigo_Sup, Procesado, Valor_Historico, 
                         Reg_Sanitario, Estado, PF, Consignacion, T, Codigo_Barra_K, Tipo_SubMod, Stock, Costo, Valor_Unit, Con_Kardex, Categorias, ID
                FROM Catalogo_Productos
                WHERE Codigo_Inv LIKE '02.9%'
                AND Item='".$_SESSION['INGRESO']['item']."'
                AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
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
            AND TK.T = 'E'";
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

    function cargar_asignacion($bene,$tipo,$T,$fecha=false)
    {
        $sql = "SELECT TC.ID,TC.Fecha,TC.Fecha_C,A.Nombre_Completo,TC.Total,TC.CodBodega,T
                FROM Trans_Comision TC
                INNER JOIN Accesos A ON TC.CodigoU = A.Codigo
                WHERE CodigoC = '".$bene."'
                AND Cta = '".$tipo."'
                AND T = '".$T."'";
                if($fecha)
                {
                    //fecha_A es la fecha de asignacion
                    $sql.=" AND Fecha_A = '".$fecha."'";
                }
                // print_r($sql);die();
        return $this->db->datos($sql);    
    }

    function lineasKArdex($codBarras)
    {
        $sql ="SELECT TK.Codigo_Barra,TK.Fecha,CP.Producto
            FROM trans_kardex TK
            INNER JOIN Catalogo_Productos CP on TK.Codigo_Inv = CP.Codigo_Inv 
            WHERE TK.Codigo_Barra = '".$codBarras."'";
               // print_r($sql);die();

        return $this->db->datos($sql);   
    }


}

?>