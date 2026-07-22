<?php

/** 
 * AUTOR DE RUTINA : Javier farinango
 * FECHA CREACION : 12/06/2026
 * FECHA MODIFICACION : 12/06/2026
 * DESCIPCION : Clase que se encarga de manejar impostacion de datos desde excel
 */
require_once (dirname(__DIR__, 2) . "/db/db1.php");
require_once (dirname(__DIR__, 2) . "/funciones/funciones.php");


class importar_desde_excelM
{
	private $db;
	
	function __construct()
	{
		$this->db = new db();
	}

	function DCLineas($modulo,$query=false)
	{
		switch ($modulo) {
			case "INVENTARIO":
				$sql="	SELECT Codigo, Cuenta 
              			FROM Catalogo_Cuentas 
              			WHERE Item = '".$_SESSION['INGRESO']['item']."' 
              			AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
              			AND TC = 'P' ";
              			if($query)
              			{
              				$sql.=" AND Cuenta like '%".$query."%'";
              			}
              			$sql.="	ORDER BY Cuenta ";
              		return $this->db->datos($sql);
				break;
			case "FACTURACION":
				$sql = "SELECT *
              			FROM Catalogo_Lineas
              			WHERE TL <> 0
              			AND Item = '".$_SESSION['INGRESO']['item']."'
              			AND Fact IN ('FA','NV')
              			AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
              			if($query)
              			{
              				$sql.=" AND Concepto like '%".$query."%'";
              			}
              			$sql.="	ORDER BY Serie, Codigo ";
              		return $this->db->datos($sql);
				break;
			case "EDUCATIVO":
				 $sql = "SELECT * 
	              		FROM Catalogo_Cursos 
	              		WHERE Item = '".$_SESSION['INGRESO']['item']."'
	              		AND Periodo =  '".$_SESSION['INGRESO']['periodo']."'";
	              		if($query)
	              		{
	              			$sql.=" AND Descripcion like '%".$query."%'";
	              		}
	              		$sql.="	AND LEN(Curso)>4 
	              		ORDER BY Curso ";

              		return $this->db->datos($sql);
				break;
			
			default:
				// code...
				break;
		}
	}

	function Clientes()
	{
		$sql= "SELECT Codigo,CI_RUC,Cliente
		FROM Clientes 
        WHERE LEN(Codigo) > 0 
        ORDER BY Cliente ";
        return $this->db->datos($sql);
	}

	function Tabla_Temporal()
	{
		 $sql = "DELETE 
         FROM Tabla_Temporal 
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND Modulo = '".$_SESSION['INGRESO']['modulo_']."'
         AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'";
        return $this->db->String_Sql($sql);
	}

	function Importar_Facturas()
	{

	}
	function Importar_Contabilidad($parametros)
	{

		// print_r($parametros);die();
    
    	Importar_Contabilidad_SP($parametros['CTP']);
    
    	$sql = "SELECT Codigo, Cliente, CI_RUC, ID 
        FROM Clientes 
        WHERE Codigo LIKE '--%' 
        ORDER BY Cliente";
        $datos = $this->db->datos($sql);
        if(count($datos)>0)
        {
        	foreach ($datos as $key => $value) {
        		$ID_Trans = $value["ID"];
	            $CodigoOld = $value["Codigo"];
	            $CodigoNew = substr($value["CI_RUC"], 1, 10);
	            If(strtoupper(GetUrlSource(urlEsUnRUC.$value["CI_RUC"])) == "TRUE" )
	            {
	            	print_r('expression');die();
	               $sql = "UPDATE Clientes 
                    	SET Codigo = '".$CodigoNew."', TD = 'R' 
                    	WHERE Codigo = '".$CodigoOld."' ";
	               $this->db->String_Sql($sql);
	               
	               $sql = "UPDATE Comprobantes 
                    	SET Codigo_B = '".$CodigoNew."' 
                    	WHERE Codigo_B = '".$CodigoOld."' ";
	               $this->db->String_Sql($sql);
	            
	               $sql = "UPDATE Transacciones 
                    	SET Codigo_C = '".$CodigoNew."' 
                    	WHERE Codigo_C = '".$CodigoOld."' ";
	               $this->db->String_Sql($sql);
	            }
              
        	}
        }


//     Select_AdoDB AdoCompDB, sSQL
//     With AdoCompDB
//      If .RecordCount > 0 Then
//          Progreso_Barra.Valor_Maximo = .RecordCount
//          Progreso_Barra.Incremento = 1
//          Do While Not .EOF
//             Progreso_Barra.Mensaje_Box = "Grabando las Transacciones del " & FechaTexto & ", CD No. " & Comp_No
//             Progreso_Esperar
//             ID_Trans = .fields("ID")
//             CodigoOld = .fields("Codigo")
//             CodigoNew = MidStrg(.fields("CI_RUC"), 1, 10)
//             If UCase(GetUrlSource(urlEsUnRUC & .fields("CI_RUC"))) = "TRUE" Then
//                SQL1 = "UPDATE Clientes " _
//                     & "SET Codigo = '" & CodigoNew & "', TD = 'R' " _
//                     & "WHERE Codigo = '" & CodigoOld & "' "
//                Ejecutar_SQL_SP SQL1
               
//                SQL1 = "UPDATE Comprobantes " _
//                     & "SET Codigo_B = '" & CodigoNew & "' " _
//                     & "WHERE Codigo_B = '" & CodigoOld & "' "
//                Ejecutar_SQL_SP SQL1
            
//                SQL1 = "UPDATE Transacciones " _
//                     & "SET Codigo_C = '" & CodigoNew & "' " _
//                     & "WHERE Codigo_C = '" & CodigoOld & "' "
//                Ejecutar_SQL_SP SQL1
//             Else
               
//             End If
//            .MoveNext
//          Loop
//      End If
//     End With
//     AdoCompDB.Close
//     ConectarAdodc AdoExcelAdodc
//     Select_Adodc AdoExcelAdodc, "SELECT * FROM Asiento_CSV_" & CodigoUsuario
//     DGExcelAdodc.Visible = True
//     Progreso_Final
//     If Len(TextoImprimio) > 2 Then FInfoError.Show


	}
	function Importar_Abonos_Transferencias()
	{

	}
	function Importar_Compras_Diarias()
	{

	}
	function Importar_Contabilidad_SubModulos()
	{

	}
	function Importar_Plan_Cuentas()
	{

	}
    function Importar_Abonos()
    {

    }
    function Importar_Inventarios()
    {

    }
    function Importar_Empleados()
    {

    }
    function Importar_Descuento_Empleados()
    {

    }
    function Importar_Facturas_Farmacias()
    {

    }
    function Importar_Retenciones_Farmacia()
    {

    }
    function Generar_Asiento_Compras($True)
    {

    }
    function Importar_Autorizacion_Electronica()
    {

    }
    function Importar_Estudiantes_Representantes()
    {

    }
    function Importar_Personas()
    {

    }
    function Importar_Estudiantes_PreFacturas()
    {

    }
    function Importar_Actualizacion_Estudiantes()
    {

    }
    function Importar_Activos()
    {

    }
}
?>