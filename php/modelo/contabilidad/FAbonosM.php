<?php
include(dirname(__DIR__,2).'/funciones/funciones.php');
@session_start(); 
/**
 * 
 */
class FAbonosM
{	
		
    private $db;
	function __construct()
	{
	   $this->db = new db();
	}

	function DCVendedor($query=false)
	{
	   $sql = "SELECT RP.Codigo, A.Usuario, A.Nombre_Completo 
       FROM Accesos As A, Catalogo_Rol_Pagos As RP 
       WHERE RP.Item = '".$_SESSION['INGRESO']['item']."' 
       AND RP.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       AND A.Codigo = RP.Codigo ";
       if($query)
       {
       	 $sql.=" AND A.Nombre_Completo like '%".$query."%' ";
       }
       $sql.="ORDER BY A.Nombre_Completo ";
       return $this->db->datos($sql);

	}

	function DCBanco($query=false)
	{
	   $sql = "SELECT Codigo,Codigo+'  '+Cuenta As NomCuenta 
       FROM Catalogo_Cuentas 
       WHERE TC IN ('BA','CJ','CP','C','P') 
       AND DG = 'D' 
       AND Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
       if($query)
       {
       	 $sql.=" AND Codigo+'  '+Cuenta like '%".$query."%' ";
       }
       $sql.="ORDER BY Codigo ";
       return $this->db->datos($sql);
		
	}
	function DCTarjeta($query=false)
	{
	   $sql = "SELECT Codigo,Codigo +'  '+ Cuenta As NomCuenta 
       FROM Catalogo_Cuentas 
       WHERE TC = 'TJ' 
       AND DG = 'D' 
       AND Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
       if($query)
       {
       	 $sql.=" AND Codigo+'  '+Cuenta like '%".$query."%' ";
       }
       $sql.="ORDER BY Codigo ";
       return $this->db->datos($sql);
		
	}
	function DCRetFuente($query=false)
	{
	   $sql= "SELECT (Codigo+'  '+Cuenta) As Cuentas  
       FROM Catalogo_Cuentas 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       AND TC = 'CF' 
       AND DG = 'D'";
       if($query)
       {
       	 $sql.=" AND Codigo+'  '+Cuenta like '%".$query."%' ";
       }
       $sql.="ORDER BY Codigo ";
       return $this->db->datos($sql);
		
	}
	function DCRetISer($query=false)
	{
	   $sql = "SELECT Codigo,(Codigo+'  '+ Cuenta) As Cuentas  
       FROM Catalogo_Cuentas 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       AND TC = 'CI' 
       AND DG = 'D' ";
       if($query)
       {
       	 $sql.=" AND Codigo+'  '+Cuenta like '%".$query."%' ";
       }
       $sql.="ORDER BY Codigo ";
       return $this->db->datos($sql);
		
	}
	function DCRetIBienes($query)
	{
	   $sql = "SELECT Codigo,(Codigo+'  '+Cuenta) As Cuentas  
       FROM Catalogo_Cuentas 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       AND TC = 'CB' 
       AND DG = 'D' ";
       if($query)
       {
       	 $sql.=" AND Codigo+'  '+Cuenta like '%".$query."%' ";
       }
       $sql.="ORDER BY Codigo ";
       return $this->db->datos($sql);
		
	}
	function DCCodRet($query=false,$MBFecha="")
	{
	   $sql = "SELECT * 
       FROM Tipo_Concepto_Retencion 
       WHERE Codigo <> '.' 
       AND Fecha_Inicio <= '".BuscarFecha($MBFecha)."' 
       AND Fecha_Final >= '". BuscarFecha($MBFecha)."'";
       if($query)
       {
       	 $sql.=" AND Codigo like '%".$query."%' ";
       }
       $sql.="
       ORDER BY Codigo ";
       return $this->db->datos($sql);
		
	}
	function DCTipo()
	{
	   $sql = "SELECT TC 
       FROM Facturas
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
       AND T = '".G_PENDIENTE."' 
       AND NOT TC IN ('OP','C','P') 
       GROUP BY TC 
       ORDER BY TC ";
       return $this->db->datos($sql);
		
	}

    function DCSerie($TC)
    {
       $sql= "SELECT Serie 
        FROM Facturas
        WHERE Item = '".$_SESSION['INGRESO']['item']."' 
        AND Periodo ='".$_SESSION['INGRESO']['periodo']."'
        AND T = '".G_PENDIENTE."' 
        AND TC = '".$TC."' 
        GROUP BY Serie 
        ORDER BY Serie ";
        return $this->db->datos($sql);
/*
        $sql = "SELECT Serie 
        FROM Facturas
        WHERE Item = '".$_SESSION['INGRESO']['item']."' 
        AND Periodo ='".$_SESSION['INGRESO']['periodo']."' 
        AND TC = '".$TC."' 
        GROUP BY Serie 
        ORDER BY Serie ";*/
    
   
    }

    function DCFactura($TC,$Serie,$factura=false)
    {
        $sql = "SELECT F.TC,F.Factura,F.Autorizacion,F.Serie,F.CodigoC,F.Fecha,F.Fecha_V,
        F.Total_MN,F.Total_ME,F.Saldo_MN,F.Saldo_ME,F.Cta_CxP,F.Nota,F.Observacion,F.Cotizacion,
        C.Cliente,C.Direccion,C.CI_RUC,C.Telefono,C.Grupo 
        FROM Facturas As F 
        INNER JOIN Clientes As C
        ON F.CodigoC = C.Codigo 
        WHERE F.T = '".G_PENDIENTE."' 
        AND F.Item = '".$_SESSION['INGRESO']['item']."' 
        AND F.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND F.Serie = '".$Serie."' 
        AND F.TC = '".$TC."' 
        AND F.Saldo_MN > 0 ";
        if($factura)
        {
            $sql.=" AND F.Factura ='".$factura."' ";
        }
        $sql.=" ORDER BY F.TC,F.Factura";
        // print_r($sql);die();
        return $this->db->datos($sql);
    }

    function DCAutorizacion($DCFactura,$TC,$Serie,$Evaluar=false,$Autorizacion='')
    {
        $sql = "SELECT Autorizacion 
         FROM Facturas 
         WHERE Item =  '".$_SESSION['INGRESO']['item']."' 
         AND Periodo ='".$_SESSION['INGRESO']['periodo']."' 
         AND TC = '".$TC."' 
         AND Serie = '".$Serie."' 
         AND Factura = ".$DCFactura." ";
      if($Evaluar){
         $sql.=" AND Autorizacion = '".$Autorizacion."' ";
      }else{
        // '     sSQL = sSQL & "AND CodigoC = '" & CodigoCliente & "' "
      }
      $sql.= " AND Saldo_MN > 0 
            AND T <> 'A' 
            GROUP BY Autorizacion 
            ORDER BY Autorizacion ";

        return $this->db->datos($sql);
    }

    function Actualizar_factura($SaldoDisp,$T,$TA)
    {
         $sql = "UPDATE Facturas 
          SET Saldo_MN = ".$SaldoDisp.",T = '".$T."' 
          WHERE Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          AND TC = '".$TA['TP']."' 
          AND Serie = '".$TA['Serie']."' 
          AND Autorizacion = '".$TA['Autorizacion']."' 
          AND Factura = ".$TA['Factura']." 
          AND CodigoC = '".$TA['CodigoC']."' ";
           return $this->db->String_Sql($sql);
    
    }


	
}
?>