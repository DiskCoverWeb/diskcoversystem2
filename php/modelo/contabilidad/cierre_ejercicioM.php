<?php

require_once(dirname(__DIR__,2)."/db/db1.php");
/**
 * 
 */
class cierre_ejercicioM
{	
	private $db;
	
	function __construct()
	{
		$this->db = new db();
	}

	function UPDATE_Trans_SubCtas_C()
	{
		$sql = "UPDATE Trans_SubCtas 
        SET Fecha_V = Fecha 
        WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND Item = '".$_SESSION['INGRESO']['item']."' 
        AND Fecha <= '".$_SESSION['INGRESO']['Fechaf']."'
        AND Fecha < Fecha_V 
        AND TC = 'C' 
        AND Creditos > 0 ";
        return $this->db->String_Sql($sql);
	}

	function UPDATE_Trans_SubCtas_P()
	{
		$sql= "UPDATE Trans_SubCtas 
        SET Fecha_V = Fecha 
        WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND Item = '".$_SESSION['INGRESO']['item']."' 
        AND Fecha <= '".$_SESSION['INGRESO']['Fechaf']."'
        AND Fecha < Fecha_V 
        AND TC = 'P' 
        AND Debitos > 0";
        return $this->db->String_Sql($sql);

	}

	function UPDATE_Transacciones()
	{
		$sql = "UPDATE Transacciones
         SET C = 1 
         WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
         AND Item = '".$_SESSION['INGRESO']['item']."' 
         AND Fecha <='".$_SESSION['INGRESO']['Fechaf']."' ";
         return $this->db->String_Sql($sql);
	}
	function DELETE_Asiento_K($Trans_No)
	{
		$sql = "DELETE 
       	FROM Asiento_K 
       	WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       	AND T_No = ".$Trans_No." 
       	AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' ";
        return $this->db->String_Sql($sql);
	}
	function DELETE_Asiento_R($Trans_No)
	{
		 $sql = "DELETE *
	       FROM Asiento_R
	       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
	       AND T_No = ".$Trans_No." 
	       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' ";
        return $this->db->String_Sql($sql);
	}

	function SELECT_Catalogo_Cuentas($Codigo)
	{
		$sql = "SELECT * 
	   		FROM Catalogo_Cuentas 
	   		WHERE Item = '".$_SESSION['INGRESO']['item']."' 
	   		AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
	   		AND DG = 'D' 
	   		AND MidStrg(".$Codigo.",1,1) IN ('1','2','3','8','9') 
	   		AND Saldo_Total <> 0 
	   		ORDER BY Codigo ";
        return $this->db->datos($sql);
	}

	function SELECT_Transacciones($Codigo,$FechaFin)
	{
		$sql = "SELECT C.Cliente,T.Fecha_Efec,T.Cheq_Dep,T.Debe,T.Haber,T.TP,T.Numero,T.Cta,CC.Cuenta,CC.TC,T.Codigo_C 
         	FROM Transacciones As T,Catalogo_Cuentas As CC,Clientes As C 
         	WHERE T.Item = '".$_SESSION['INGRESO']['item']."' 
         	AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
         	AND T.Fecha <= '".$FechaFin."' 
         	AND T.C = 0 
         	AND T.Cta = '".$Codigo."' 
         	AND T.T <> 'A' 
         	AND CC.TC = 'BA' 
         	AND T.Item = CC.Item 
         	AND T.Periodo = CC.Periodo 
         	AND T.Cta = CC.Codigo 
         	AND T.Codigo_C = C.Codigo 
         	ORDER BY T.Cta,C.Cliente,T.Fecha_Efec,T.Cheq_Dep ";         	
        return $this->db->datos($sql);
	}

	function SELECT_Trans_SubCtas($FechaFin,$Codigo)
	{
		 $sql = "SELECT Codigo, Factura, (SUM(Debitos)-SUM(Creditos)) AS TSaldo 
            FROM Trans_SubCtas 
            WHERE Fecha <= '".$FechaFin."'
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND Item = '".$_SESSION['INGRESO']['item']."' 
            AND Cta = '".$Codigo."' 
            AND T <> 'A' 
            GROUP BY Codigo, Factura ";
          return $this->db->datos($sql);
	}
	function SELECT_Trans_SubCtas2($FechaFin,$Codigo)
	{
		$sql = "SELECT Codigo, Factura, (SUM(Creditos)-SUM(Debitos)) AS TSaldo 
          		FROM Trans_SubCtas 
          		WHERE Fecha <= '".$FechaFin."' 
          		AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          		AND Item = '".$_SESSION['INGRESO']['item']."' 
          		AND Cta ='".$Codigo."' 
          		AND T <> 'A' 
          		GROUP BY Codigo, Factura ";
          return $this->db->datos($sql);
	}
	function SELECT_Asiento($Trans_No)
	{
		$sql = "SELECT * 
       			FROM Asiento 
       			WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       			AND T_No = ".$Trans_No." 
       			AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' 
       			ORDER BY Codigo,DEBE DESC,HABER ";
          return $this->db->datos($sql);
	}
	function SELECT_Asiento_SC($Trans_No)
	{
		$sql = "SELECT * 
	       	FROM Asiento_SC 
	       	WHERE Item ='".$_SESSION['INGRESO']['item']."' 
	       	AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' 
	       	AND T_No = ".$Trans_No."  ";
	    return $this->db->datos($sql);
	}
	function SELECT_Clientes($FechaFin)
	{
	   $sql = "SELECT TS.Codigo, C.Cliente, TS.Factura, TS.TC, TS.Cta, MIN(TS.Fecha_V) As Fecha_Venc, 
       		SUM(TS.Debitos) As TDebitos, SUM(TS.Creditos) As TCreditos 
       		FROM Clientes As C, Catalogo_Cuentas As CC, Trans_SubCtas As TS 
       		WHERE TS.Item ='".$_SESSION['INGRESO']['item']."' 
       		AND TS.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       		AND TS.Fecha <= '".$FechaFin."'
       		AND TS.TC IN ('C','P') 
       		AND TS.Codigo = C.Codigo 
       		AND TS.Cta = CC.Codigo 
       		AND TS.Item = CC.Item 
       		AND TS.Periodo = CC.Periodo 
       		GROUP BY TS.Codigo,C.Cliente,TS.Factura,TS.TC,TS.Cta 
       		HAVING (SUM(TS.Debitos)-SUM(TS.Creditos)) <> 0 
       		ORDER BY TS.TC,TS.Cta,C.Cliente,TS.Factura,SUM(TS.Debitos) DESC, SUM(TS.Creditos) DESC ";
	    return $this->db->datos($sql);
	}
}
?>