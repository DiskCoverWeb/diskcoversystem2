<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$idEntidad = $_SESSION['INGRESO']['IDEntidad'] ?? null;
$item = $_SESSION['INGRESO']['item'] ?? null;

if ($idEntidad === null || $item === null) {
     $_SESSION = [];
}
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

/**
 * 
 */
class resumen_existenciasM
{
	private $db;
	function __construct()
	{
	    $this->db = new db();
	}

	function Listatabla()
	{
		$sql ="SELECT TC, Codigo_Inv, Producto, Unidad, Stock_Anterior, Entradas, Salidas, Stock_Actual, Promedio, PVP, Valor_Total
	       FROM Catalogo_Productos 
	       WHERE Item ='" . $_SESSION['INGRESO']['item'] . "' 
	       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
	       ORDER BY Codigo_Inv ASC";

	    return $this->db->datos($sql);
	}

	function DCBodega($query=false)
	{
		$sql = "SELECT TOP 25 CodBod,(CodBod + ' - ' + Bodega) As Bodegas 
        FROM Catalogo_Bodegas 
        WHERE Item  ='" . $_SESSION['INGRESO']['item'] . "' 
        AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' ";
        if($query)
        {
        	$sql.=" AND (CodBod like '%".$query."%' OR Bodega like '%".$query."%')";
        }
        $sql.=" ORDER BY CodBod ";

	    return $this->db->datos($sql);
	}

	function DCTInv($query=false)
	{
		$sql = "SELECT Codigo_Inv, Producto 
       	FROM Catalogo_Productos 
       	WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
       	AND Periodo ='".$_SESSION['INGRESO']['periodo']."' 
       	AND TC = 'I' 
       	AND INV <> 0 ";

        if($query)
        {
        	$sql.=" AND (Codigo_Inv like '%".$query."%' OR Producto like '%".$query."%')";
        }
        $sql.=" ORDER BY Codigo_Inv  ";

	    return $this->db->datos($sql);
	}

	function Catalogo_Marcas()
	{
		$sql = "SELECT  TOP 50 CodMar As Codigo, Marca As Producto 
        FROM Catalogo_Marcas 
        WHERE Item =  '" . $_SESSION['INGRESO']['item'] . "' 
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND CodMar <> '".G_NINGUNO."' 
        ORDER BY Marca";

	    return $this->db->datos($sql);

	}

	function Trans_Kardex_barras()
	{
		$sql = "SELECT TOP 50 Codigo_Barra As Codigo, Codigo_Barra As Producto 
        FROM Trans_Kardex 
        WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        GROUP BY Codigo_Barra 
        ORDER BY Codigo_Barra ";

	    return $this->db->datos($sql);

	}

	function Trans_Kardex_lote()
	{
		$sql = "SELECT Lote_No As Codigo, Lote_No As Producto 
    	FROM Trans_Kardex 
    	WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
    	AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
   		GROUP BY Lote_No 
    	ORDER BY Lote_No ";

	    return $this->db->datos($sql);
	}
	function Catalogo_Productos($Buscar_Grupo_Inventario)
	{
		$sql = "SELECT TOP 50 Codigo_Inv As Codigo, Producto 
        FROM Catalogo_Productos 
        WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
        AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' 
        AND LEN(Cta_Inventario) > 2 
        AND Codigo_Inv LIKE '".$Buscar_Grupo_Inventario."%' 
        AND TC = 'P' 
        ORDER BY Codigo_Inv ";

        // print_r($sql);die();

	    return $this->db->datos($sql);
	}

	function DCCtaInvOp1()
	{
		 $sql = "SELECT CC.Cuenta,TK.Cta_Inv
         FROM Catalogo_Cuentas As CC, Trans_Kardex As TK
         WHERE CC.Item = '" . $_SESSION['INGRESO']['item'] . "' 
         AND CC.Periodo =  '".$_SESSION['INGRESO']['periodo']."' 
         AND LEN(TK.Cta_Inv) > 1
         AND CC.Codigo = TK.Cta_Inv
         AND CC.Item = TK.Item
         AND CC.Periodo = TK.Periodo
         GROUP BY CC.Cuenta,TK.Cta_Inv
         ORDER BY CC.Cuenta,TK.Cta_Inv ";

	    return $this->db->datos($sql);

	}

	function DCCtaInvOp2()
	{
		$sql = "SELECT CC.Cuenta,TK.Contra_Cta as Cta_Inv
        FROM Catalogo_Cuentas As CC, Trans_Kardex As TK 
        WHERE CC.Item = '" . $_SESSION['INGRESO']['item'] . "' 
        AND CC.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND LEN(TK.Contra_Cta) > 1 
        AND CC.Codigo = TK.Contra_Cta 
        AND CC.Item = TK.Item 
        AND CC.Periodo = TK.Periodo 
        GROUP BY CC.Cuenta,TK.Contra_Cta 
        ORDER BY CC.Cuenta,TK.Contra_Cta";

	    return $this->db->datos($sql);

	}
	function DCSubModuloOp1()
	{
		$sql = "SELECT TC, Codigo, Detalle As SubModulo 
        FROM Catalogo_SubCtas 
        WHERE Item ='" . $_SESSION['INGRESO']['item'] . "' 
        AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' 
        AND Detalle <> '".G_NINGUNO."' 
        ORDER BY TC,Detalle ";

	    return $this->db->datos($sql);
	}
	function DCSubModuloOp2()
	{
		$sql = "SELECT CP.TC, CP.Codigo, CP.Cta, (C.Cliente + REPLICATE(' ', 60 - LEN(C.Cliente)) + CP.Cta) As SubModulo 
        FROM Catalogo_CxCxP As CP, Clientes As C 
        WHERE CP.Item = '" . $_SESSION['INGRESO']['item'] . "' 
        AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND C.Cliente <> '".G_NINGUNO."' 
        AND CP.TC = 'P' 
        AND CP.Codigo = C.Codigo 
        ORDER BY C.Cliente,CP.Cta ";

	    return $this->db->datos($sql);
	}
	function Resumen_QR($FechaIni,$FechaFin)
	{
		$sql = "SELECT Codigo_Barra, 
       	SUM(Entrada) As Entradas, SUM(Salida) As Salidas, 
       	SUM(Entrada-Salida) As Stock_QR, AVG(Valor_Unitario) As Valor_Unit, 
       	((SUM(Entrada)-SUM(Salida)) * AVG(Valor_Unitario)) As Total_Inventario 
       	FROM Trans_Kardex 
       	WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
       	AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       	AND Fecha BETWEEN '".$FechaIni."' and '".$FechaFin."'
       	GROUP BY Codigo_Barra 
       	HAVING SUM(Entrada-Salida) <> 0 
       	ORDER BY Codigo_Barra ";

	    return $this->db->datos($sql);

	}

	function Resumen_Barras($FechaIni,$FechaFin,$SQL_Tipo_Busqueda)
	{
		$sql = "SELECT TK.Codigo_Inv, CP.Producto, TK.CodBodega, TK.Codigo_Barra, CP.Reg_Sanitario, 
       	SUM(TK.Entrada) As Entradas, SUM(TK.Salida) As Salidas, 
       	SUM(TK.Entrada-TK.Salida) As Stock_Lote, AVG(TK.Valor_Unitario) As Valor_Unit, 
       	((SUM(TK.Entrada)-SUM(TK.Salida)) * AVG(TK.Valor_Unitario)) As Total_Inventario 
       	FROM Catalogo_Productos As CP, Trans_Kardex As TK 
       	WHERE CP.Item = '" . $_SESSION['INGRESO']['item'] . "' 
       	AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND TK.Fecha BETWEEN '".$FechaIni."' and '".$FechaFin."' 
        ".$SQL_Tipo_Busqueda."
        AND CP.Item = TK.Item 
        AND CP.Periodo = TK.Periodo 
        AND CP.Codigo_Inv = TK.Codigo_Inv 
        GROUP BY TK.Codigo_Inv, CP.Producto, TK.CodBodega, TK.Codigo_Barra, CP.Reg_Sanitario 
        HAVING SUM(TK.Entrada-TK.Salida) <> 0 
        ORDER BY TK.Codigo_Inv, TK.Codigo_Barra ";

        // print_r($sql);die();

	    return $this->db->datos($sql);
	}

	function Resumen_Lote($FechaIni,$FechaFin,$SQL_Tipo_Busqueda)
	{
		$sql = "SELECT TK.Codigo_Inv, CP.Producto, TK.CodBodega, TK.Lote_No, TK.Fecha_Fab, TK.Fecha_Exp, CP.Reg_Sanitario, 
       TK.Modelo, TK.Procedencia, TK.Serie_No, SUM(TK.Entrada) As Entradas, SUM(TK.Salida) As Salidas, 
       SUM(TK.Entrada-TK.Salida) As Stock_Lote,  AVG(Valor_Unitario) As Valor_Unit, 
       (SUM(TK.Entrada-TK.Salida) * AVG(Valor_Unitario)) As Total_Inventario 
       FROM Catalogo_Productos As CP, Trans_Kardex As TK 
       WHERE CP.Item = '" . $_SESSION['INGRESO']['item'] . "' 
       AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       AND TK.Fecha BETWEEN '".$FechaIni."' and '".$FechaFin."' 
       ".$SQL_Tipo_Busqueda."
       AND CP.Item = TK.Item 
       AND CP.Periodo = TK.Periodo 
       AND CP.Codigo_Inv = TK.Codigo_Inv 
       GROUP BY TK.Codigo_Inv, CP.Producto, TK.CodBodega, TK.Lote_No, TK.Fecha_Fab, TK.Fecha_Exp, CP.Reg_Sanitario, 
       TK.Modelo, TK.Procedencia, TK.Serie_No 
       ORDER BY TK.Codigo_Inv, TK.Lote_No ";
      // print_r($sql);die();

	    return $this->db->datos($sql);
	}

	function Stock_Catalogo_Productos($CheqGrupo,$SQL_Tipo_Busqueda_CP)
	{
		  // 'StockInvent StockSuperior
        $sql = "SELECT TC,Codigo_Inv,Producto,Unidad,Stock_Anterior,Entradas,Salidas,Stock_Actual, Promedio As Costo_Unit,Valor_Total, 0 As Diferencias, Ubicacion, Bodega 
            FROM Catalogo_Productos As CP 
            WHERE Item =  '" . $_SESSION['INGRESO']['item'] . "' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
              ".$SQL_Tipo_Busqueda_CP;
         if($CheqGrupo <> "false"){ $sql.=" AND Codigo_Inv LIKE '".$Buscar_Grupo_Inventario."%' "; }
         $sql.=" ORDER BY Codigo_Inv ";

         // print_r($sql);die();
         
	    return $this->db->datos($sql);
	}


}
?>