<?php
include(dirname(__DIR__,2)."/db/db1.php");
@session_start(); 

class RegistroHorasLaboradas {

   private $conn;
	function __construct()
	{
	   $this->conn = new db();
	}

   function generarDias($parametros){
      $sSQL = "DELETE FROM Trans_Rol_Horas 
      WHERE Item = '".$_SESSION['INGRESO']['item']."'
      AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
      AND Fecha BETWEEN '".$parametros['FechaIni']."' AND '".$parametros['FechaFin']."'";
      $this->conn->String_Sql($sSQL);
      
      $sSQL = "INSERT INTO Trans_Rol_Horas (Periodo, Item, T, Dias, Codigo, Fecha, Horas, Horas_Exts, Porc_Hr_Ext, Valor_Hora, Ing_Liquido, Ing_Horas_Ext, Orden, X) ";
      if($parametros['OpcIngreso'] == "Semanal"){
         $sSQL = $sSQL."SELECT Periodo, Item, T, 30, Codigo, '".$parametros['FechaFin']."', Horas_Sem, 0 ,0, Valor_Hora, ROUND(Salario/4,2,0), 0, '".$parametros['Orden']."' ,'.' ";
      } else if ($parametros['OpcIngreso'] == "Quincenal") {
         $sSQL = $sSQL."SELECT Periodo, Item, T, 30, Codigo, '".$parametros['FechaFin']."', Horas_Sem*2, 0 ,0, Valor_Hora, ROUND(Salario/2,2,0), 0, '".$parametros['Orden']."' ,'.' ";
      } else if ($parametros['OpcIngreso'] == "Mensual"){
         $sSQL = $sSQL."SELECT Periodo, Item, T, 30, Codigo, '".$parametros['FechaFin']."', Horas_Sem*4, 0 ,0, Valor_Hora, Salario, 0, '".$parametros['Orden']."' ,'.' ";
      }    
      $sSQL = $sSQL."FROM Catalogo_Rol_Pagos
        WHERE Item = '".$_SESSION['INGRESO']['item']."' 
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
        AND Fecha <= '".$parametros['FechaFin']."'
        AND Salario > 0
        AND T = 'N' 
        ORDER BY Ejecutivo ";
      $this->conn->String_Sql($sSQL);

      $sSQL = "INSERT INTO Trans_Rol_Horas (Periodo, Item, T, Dias, Codigo, Fecha, Horas, Horas_Exts, Porc_Hr_Ext, Valor_Hora, Ing_Liquido, Ing_Horas_Ext, Orden, X) ";
      

      if($parametros['OpcIngreso'] == "Semanal"){
         $sSQL = $sSQL."SELECT Periodo, Item, T, 30, Codigo, '".$parametros['FechaFin']."', Horas_Sem, 0 ,0, Valor_Hora, ROUND(Salario/4,2,0), 0, '".$parametros['Orden']."' ,'.' ";
      } else if ($parametros['OpcIngreso'] == "Quincenal") {
         $sSQL = $sSQL."SELECT Periodo, Item, T, 30, Codigo, '".$parametros['FechaFin']."', Horas_Sem*2, 0 ,0, Valor_Hora, ROUND(Salario/2,2,0), 0, '".$parametros['Orden']."' ,'.' ";
      } else if ($parametros['OpcIngreso'] == "Mensual"){
         $sSQL = $sSQL."SELECT Periodo, Item, T, 30, Codigo, '".$parametros['FechaFin']."', Horas_Sem*4, 0 ,0, Valor_Hora, Salario, 0, '".$parametros['Orden']."' ,'.' ";
      }
      
      $sSQL = $sSQL."FROM Catalogo_Rol_Pagos 
        WHERE Item = '".$_SESSION['INGRESO']['item']."'
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
        AND FechaC BETWEEN '".$parametros['FechaIni']."' AND '".$parametros['FechaFin']."'
        AND Salario > 0
        AND T = 'R'
        ORDER BY Ejecutivo ";
       $this->conn->String_Sql($sSQL);
      
      $sSQL = "UPDATE Trans_Rol_Horas
        SET Dias = DATEDIFF(DAY,CRP.Fecha,TRH.Fecha)+1
        FROM Trans_Rol_Horas As TRH, Catalogo_Rol_Pagos As CRP
        WHERE TRH.Item = '".$_SESSION['INGRESO']['item']."'
        AND TRH.Periodo = '".$_SESSION['INGRESO']['periodo']."'
        AND TRH.Fecha BETWEEN '".$parametros['FechaIni']."' AND '".$parametros['FechaFin']."'
        AND DATEDIFF(DAY,CRP.Fecha,TRH.Fecha) < 30
        AND TRH.Item = CRP.Item
        AND TRH.Periodo = CRP.Periodo
        AND TRH.Codigo = CRP.Codigo";
      $this->conn->String_Sql($sSQL);
      
      $sSQL = "UPDATE Trans_Rol_Horas
        SET Dias = DATEDIFF(DAY,'".$parametros['FechaIni']."',CRP.FechaC) + 1
        FROM Trans_Rol_Horas As TRH, Catalogo_Rol_Pagos As CRP
        WHERE TRH.Item = '".$_SESSION['INGRESO']['item']."'
        AND TRH.Periodo = '".$_SESSION['INGRESO']['periodo']."'
        AND TRH.Fecha BETWEEN '".$parametros['FechaIni']."' AND '".$parametros['FechaFin']."'
        AND CRP.T = 'R'
        AND DATEDIFF(DAY,'".$parametros['FechaIni']."',CRP.FechaC) < 30
        AND TRH.Item = CRP.Item
        AND TRH.Periodo = CRP.Periodo
        AND TRH.Codigo = CRP.Codigo";
      $this->conn->String_Sql($sSQL);
      
      $sSQL = "UPDATE Trans_Rol_Horas
        SET Ing_Liquido = ROUND((Ing_Liquido/30)*Dias,2,0), Horas = ROUND((Horas_Sem*4/30)*Dias,0,0)
        FROM Trans_Rol_Horas As TRH, Catalogo_Rol_Pagos As CRP
        WHERE TRH.Item = '".$_SESSION['INGRESO']['item']."'
        AND TRH.Periodo = '".$_SESSION['INGRESO']['periodo']."'
        AND TRH.Fecha BETWEEN '".$parametros['FechaIni']."' AND '".$parametros['FechaFin']."'
        AND Dias < 30
        AND TRH.Item = CRP.Item
        AND TRH.Periodo = CRP.Periodo
        AND TRH.Codigo = CRP.Codigo";
      return $this->conn->String_Sql($sSQL);
   }
   function obtenerBeneficiarios($parametros){
   $sSQL = "SELECT RP.T, C.Cliente, C.CI_RUC, C.Grupo, RP.Codigo, RP.Horas_Ext, RP.Valor_Hora, RP.Fecha, RP.FechaC, RP.Horas_Sem, RP.Salario, RP.Valor_Hora
      FROM Clientes As C,Catalogo_Rol_Pagos As RP
      WHERE RP.Item = '".$_SESSION['INGRESO']['item']."'
      AND RP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
      AND RP.T = 'N'
      AND RP.Fecha <= '".$parametros['FechaFin']."'
      AND RP.Salario > 0
      AND C.Codigo = RP.Codigo
      UNION
      SELECT RP.T, C.Cliente, C.CI_RUC, C.Grupo, RP.Codigo, RP.Horas_Ext, RP.Valor_Hora, RP.Fecha, RP.FechaC, RP.Horas_Sem, RP.Salario, RP.Valor_Hora
      FROM Clientes As C,Catalogo_Rol_Pagos As RP
      WHERE RP.Item = '".$_SESSION['INGRESO']['item']."'
      AND RP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
      AND RP.T = 'R'
      AND RP.FechaC BETWEEN '".$parametros['FechaIni']."' AND '".$parametros['FechaFin']."'
      AND RP.Salario > 0
      AND C.Codigo = RP.Codigo
      ORDER BY C.Cliente";
      return $this->conn->datos($sSQL);
   }

   function ListarHorasTrabajadas($fechaini, $fechafin, $codigo){
      $sSQL = "SELECT Codigo,Dias,Fecha,Horas,Horas_Exts,Porc_Hr_Ext,Valor_Hora,Ing_Liquido,Ing_Horas_Ext,Orden
      FROM Trans_Rol_Horas
      WHERE Fecha BETWEEN '".$fechaini."' AND '".$fechafin."'
      AND Codigo = '".$codigo."'
      AND Item = '".$_SESSION['INGRESO']['item']."'
      AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
      ORDER BY Fecha DESC,Orden DESC ";
      return $this->conn->datos($sSQL);
   }

   function getValorHora($parametros){
      $sSQL = "SELECT TOP 1 Codigo,Valor_Hora
         FROM Trans_Rol_Horas
         WHERE Fecha <= '".$parametros['Fecha']."'
         AND Codigo = '".$parametros['Codigo']."'
         AND Item = '".$_SESSION['INGRESO']['item']."'
         AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
         ORDER BY Fecha DESC,Orden DESC";
      return $this->conn->datos($sSQL);
   }

   function ListarNovedades($parametros): array|bool{
      $sSQL = "SELECT Fecha,Hora,Proceso,Tarea as Novedades,Codigo
              FROM Trans_Entrada_Salida
              WHERE Fecha BETWEEN '".$parametros['FechaIni']."' AND '".$parametros['FechaFin']."'
              AND Codigo = '".$parametros['Codigo']."'
              AND Item = '".$_SESSION['INGRESO']['item']."'
              AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
              AND ES = 'R'
              ORDER BY Fecha DESC";
      return $this->conn->datos($sSQL);
   }
}
?>    