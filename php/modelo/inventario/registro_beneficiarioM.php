<?php

/** 
 * AUTOR DE RUTINA : Dallyana Vanegas
 * MODIFICADO POR: Teddy Moreira
 * FECHA CREACION : 16/02/2024
 * FECHA MODIFICACION : 02/07/2024
 * DESCIPCION : Clase modelo para llenar campos y guardar registros de Agencia
 */

include (dirname(__DIR__, 2) . '/funciones/funciones.php');
@session_start();

class registro_beneficiarioM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function EliminarEvidencias($nombre, $codigo)
    {
        $sql = "UPDATE Clientes_Datos_Extras 
            SET Evidencias = REPLACE(Evidencias, '" . $nombre . ",', '')
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
            AND Codigo = '" . $codigo . "'";
        $this->db->datos($sql);

        $sql = "SELECT Evidencias FROM Clientes_Datos_Extras 
                         WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                         AND Codigo = '" . $codigo . "' 
                         AND Evidencias IS NOT NULL";
        $result = $this->db->datos($sql);

        if ($result) {
            return $result[0]['Evidencias'];
        } else {
            return 1;
        }

    }

    function LlenarTblPoblacion()
    {
        $sql = "SELECT CP.Proceso AS Poblacion,
                SUM(CASE WHEN C.Sexo = 'M' THEN 1 ELSE 0 END) AS Hombres,
                SUM(CASE WHEN C.Sexo = 'F' THEN 1 ELSE 0 END) AS Mujeres,
                COUNT(*) AS Total
                FROM Clientes AS C
                JOIN Clientes_Datos_Extras AS CD ON C.Codigo = CD.Codigo
                JOIN Catalogo_Proceso AS CP ON CD.Area = CP.Cmds
                WHERE CP.Cmds LIKE '91.%'
                GROUP BY CP.Proceso
                ORDER BY CP.Proceso";
        return $this->db->datos($sql);
    }

    function LlenarCalendario($valor)
    {
        $sql = "SELECT TOP 100 C.TB, C.Cliente, F.Envio_No,  F.Dia_Ent, F.Hora_Ent       
                    FROM Clientes AS C
                    LEFT JOIN Clientes_Datos_Extras AS F ON C.Codigo = F.Codigo
                    WHERE C.Cliente <> '.'
                    AND TB = '" . $valor . "'
                    AND Envio_No != '.'
                    ORDER BY Hora_Ent";
        return $this->db->datos($sql);
    }

    function ObtenerColor($valor)
    {
        if ($valor) {
            $sql = "SELECT " . Full_Fields('Catalogo_Proceso') . "
                FROM Catalogo_Proceso
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Cmds = '" . $valor . "'";
            $resultado = $this->db->datos($sql);
            if (!empty($resultado)) {
                return $resultado[0];
            } else {
                return 0;
            }
        }
    }

    function llenarEstadoCivil(){
        $sql = "SELECT Cuenta FROM Catalogo_Auditor WHERE Codigo LIKE '01.09.%';";
        $resultado = $this->db->datos($sql);
        return $resultado;
    }

    function LlenarSelectSexo()
    {
        $sql = "SELECT Tipo_Referencia, Codigo, Descripcion
                FROM Tabla_Referenciales_SRI
                WHERE (Tipo_Referencia = 'SEXO')
                ORDER BY Tipo_Referencia, Descripcion";
        return $this->db->datos($sql);
    }

    function LlenarSelectDiaEntrega()
    {
        $sql = "SELECT Dia_Mes, Dia_Mes_C, Zip, ID
                FROM Tabla_Dias_Meses
                WHERE (Tipo = 'D')
                ORDER BY No_D_M ";

        return $this->db->datos($sql);
    }


    function LlenarSelectRucCliente($query)
    {
        $sql = "SELECT TOP 100 Cliente, CI_RUC, Codigo
                FROM Clientes
                WHERE Cliente <> '.'";

        if (!is_numeric($query)) {
            $sql .= " AND Cliente LIKE '%" . $query . "%'";
        } else {
            $sql .= " AND CI_RUC LIKE '%" . $query . "%'";
        }
        return $this->db->datos($sql);
    }

    function llenarCamposInfo($valor)
    {
        $sql = "SELECT Codigo, CodigoA, Representante, CI_RUC_R, Telefono_R, Contacto, Sexo,
                    Profesion, Email, Email2, Telefono, TelefonoT, Dia_Ent, 
                    Hora_Ent, Prov, Ciudad, Canton, Parroquia, Barrio,
                    Direccion, DireccionT,  Referencia, Calificacion, TB
                FROM  Clientes 
                WHERE Cliente <> '.'
                AND Codigo = '" . $valor . "'";
        $resultado = $this->db->datos($sql);
        if (!empty($resultado)) {
            return $resultado[0];
        } else {
            return 0;
        }
    }

    function llenarCamposInfoAdd($valor)
    {
        $sql = "SELECT CodigoA AS CodigoA2, Dia_Ent AS Dia_Ent2, Hora_Ent AS Hora_Ent2,
                    Envio_No, No_Soc, Area, Acreditacion, Tipo_Dato,
                    Cod_Fam, Evidencias, Observaciones, Item, Etapa_Procesal
                FROM  Clientes_Datos_Extras
                WHERE Codigo = '" . $valor . "'";
        $resultado = $this->db->datos($sql);
        if (!empty($resultado)) {
            return $resultado[0];
        } else {
            return 0;
        }
    }

    function sqlComunDonacion()
    {
        return "SELECT Codigo, Concepto, Picture
                FROM Catalogo_Lineas
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND TL <> 0
                AND LEN(Fact) = 3";
    }

    function LlenarTipoDonacion($valor)
    {
        $sql = $this->sqlComunDonacion();

        if (!is_numeric($valor) && $valor !='') {
            $sql .= " AND Concepto LIKE '%" . $valor . "%'";
        }

        $sql .= " ORDER BY Fact";
        return $this->db->datos($sql);
    }

    function actualizarSelectDonacion($valor)
    {
        if ($valor) {
            $sql = $this->sqlComunDonacion();
            $sql .= " AND Codigo LIKE '%" . $valor . "%'";

            return $this->db->datos($sql);
        }
    }

    function LlenarSelects_Val($query, $valor, $valor2)
    {

        // print_r($valor.'-'.$valor2.'-'.$query);die();
        $sql = "SELECT " . Full_Fields('Catalogo_Proceso') . "
                    FROM Catalogo_Proceso
                    WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'";

        if (strlen($valor) > 2) {
            $sql .= " AND Cmds LIKE '" . $valor . "'";
        } else {
            $sql .= " AND Cmds LIKE '" . $valor . ".%'";
        }
        if(!is_numeric($query))
        {
            $sql.=" AND Cmds LIKE '%" . $query . "%' ";
        }else
        {
            if($query!='')
            {
                $sql.=" AND Proceso LIKE '%" . $query . "%'";
            }
        }

        $sql .= " ORDER BY Cmds";
        //print_r($sql);die();
        if ($valor2) {
            return $this->actualizarSelectDonacion($valor);
        }
        return $this->db->datos($sql);
    }

    function ActualizarClientes($parametros)
    {
        $sql = "UPDATE Clientes SET
                Actividad = '".$parametros['TB']."',
                TB = '" . $parametros['TB'] . "',
                Calificacion = '" . $parametros['Calificacion'] . "',
                CodigoA = '" . $parametros['CodigoA'] . "', 
                Representante = '" . $parametros['Representante'] . "', 
                CI_RUC_R = '" . $parametros['CI_RUC_R'] . "', 
                Telefono_R = '" . $parametros['Telefono_R'] . "', 
                Contacto = '" . $parametros['Contacto'] . "',   
                Profesion = '" . $parametros['Profesion'] . "', 
                Hora_Ent = '" . $parametros['Hora_Ent'] . "', 
                Dia_Ent = '" . $parametros['Dia_Ent'] . "', 
                Sexo = '" . $parametros['Sexo'] . "', 
                Email = '" . $parametros['Email'] . "', 
                Email2 = '" . $parametros['Email2'] . "',                 
                Telefono = '" . $parametros['Telefono'] . "', 
                TelefonoT = '" . $parametros['TelefonoT'] . "', 
                Prov = '" . $parametros['Provincia'] . "', 
                Ciudad = '" . $parametros['Ciudad'] . "', 
                Canton = '" . $parametros['Canton'] . "', 
                Parroquia = '" . $parametros['Parroquia'] . "', 
                Barrio = '" . $parametros['Barrio'] . "', 
                Direccion = '" . $parametros['CalleP'] . "', 
                DireccionT = '" . $parametros['CalleS'] . "', 
                Referencia = '" . $parametros['Referencia'] . "'
                WHERE CI_RUC = '" . $parametros['CI_RUC'] . "'";

                // print_r($sql);
        return $this->db->datos($sql);
    }

    function ActualizarClientesDatosExtra($parametros)
    {
        //print_r($parametros);
        $sql = "UPDATE Clientes_Datos_Extras SET
                CodigoA = '" . $parametros['CodigoA2'] . "', 
                Dia_Ent = '" . $parametros['Dia_Ent2'] . "', 
                Hora_Ent = '" . $parametros['Hora_Registro'] . "', 
                Envio_No = '" . $parametros['Envio_No'] . "',
                Etapa_Procesal = '" . $parametros['Comentario'] . "',
                No_Soc = '" . $parametros['No_Soc'] . "', 
                Acreditacion = '" . $parametros['Acreditacion'] . "', 
                Tipo_Dato = '" . $parametros['Tipo_Dato'] . "', 
                Cod_Fam = '" . $parametros['Cod_Fam'] . "', 
                Observaciones = '" . $parametros['Observaciones'] . "'";
        if ($parametros['NombreArchivo']!='') {
            $sql .= ", Evidencias = CONCAT(Evidencias, '" . $parametros['NombreArchivo'] . "')";
        }else
        {
             // $sql .= ", Evidencias = '.' ";
        }
        $sql .= " WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Codigo = '" . $parametros['Codigo'] . "'";
    
        //print_r($sql);die();
        return $this->db->datos($sql);
    }
    

    function CrearClienteDatosExtra($parametros)
    {
        $sql2 = "INSERT INTO Clientes_Datos_Extras (Codigo, CodigoA, Dia_Ent, Hora_Ent, 
        Envio_No, Etapa_Procesal, No_Soc, Acreditacion, Tipo_Dato, 
        Cod_Fam, Evidencias, Observaciones, Item) 
        VALUES ('" . $parametros['Codigo'] . "', 
                '" . $parametros['CodigoA2'] . "', 
                '" . $parametros['Dia_Ent2'] . "', 
                '" . $parametros['Hora_Registro'] . "', 
                '" . $parametros['Envio_No'] . "', 
                '" . $parametros['Comentario'] . "', 
                '" . $parametros['No_Soc'] . "',                  
                '" . $parametros['Acreditacion'] . "', 
                '" . $parametros['Tipo_Dato'] . "', 
                '" . $parametros['Cod_Fam'] . "', 
                '" . $parametros['NombreArchivo'] . "', 
                '" . $parametros['Observaciones'] . "',
                '" . $_SESSION['INGRESO']['item'] . "')";
                //print_r($sql2);die();
        return $this->db->datos($sql2);
    }

    function llenarCamposPoblacion($codigo)
    {
        $sqlFecha = "SELECT MAX(FechaM) AS UltimaFecha FROM Trans_Tipo_Poblacion 
                     WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                     AND CodigoC = '" . $codigo . "'";

        $resultadoFecha = $this->db->datos($sqlFecha);

        $ultimaFecha = $resultadoFecha[0]['UltimaFecha']->format('Y-m-d');

        $sqlRegistros = "SELECT * FROM Trans_Tipo_Poblacion 
                         WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                         AND CodigoC = '" . $codigo . "' 
                         AND FechaM = '" . $ultimaFecha . "'";

        return $this->db->datos($sqlRegistros);
    }


    function CrearTipoPoblacion($parametros)
    {
        $tipoPoblacion = json_decode($parametros['TipoPoblacion'], true);

        $sql = "INSERT INTO Trans_Tipo_Poblacion (Item, Periodo, Fecha, FechaM, CodigoC, Cmds, Hombres, Mujeres, Total, CodigoU, X) VALUES ";
        $values = array();

        foreach ($tipoPoblacion as $poblacion) {
            $values[] = "('" . $_SESSION['INGRESO']['item'] . "', '" . $_SESSION['INGRESO']['periodo'] . "',
                        '" . date('Y-m-d H:i:s') . "', '" . date('Y-m-d H:i:s') . "',
                        '" . $parametros['Codigo'] . "', '" . $poblacion['valueData'] . "',
                        '" . $poblacion['hombres'] . "', '" . $poblacion['mujeres'] . "',
                        '" . $poblacion['total'] . "', '" . $_SESSION['INGRESO']['CodigoU'] . "', '.')";
        }

        $sql .= implode(', ', $values);
        return $this->db->datos($sql);
    }



    function guardarAsignacion($parametros)
    {
        
        $sql = "SELECT COUNT(*) AS count FROM Clientes_Datos_Extras WHERE Codigo = '" . $parametros['Codigo'] . "' AND Item = '".$_SESSION['INGRESO']['item']."'";
        //print_r($sql);die();
        $result = $this->db->datos($sql);

        if ($result[0]['count'] > 0) {
            $this->ActualizarClientes($parametros);
            $this->ActualizarClientesDatosExtra($parametros);
            $this->CrearTipoPoblacion($parametros);
        } else {
            $this->ActualizarClientes($parametros);
            $this->CrearClienteDatosExtra($parametros);
            $this->CrearTipoPoblacion($parametros);
        }

        Eliminar_Nulos_SP("Clientes");
        Eliminar_Nulos_SP("Clientes_Datos_Extras");

        $sql = "SELECT Evidencias FROM Clientes_Datos_Extras 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Codigo = '" . $parametros['Codigo'] . "' 
                AND Evidencias IS NOT NULL";
        $result = $this->db->datos($sql);


        return ['result' => $result[0]['Evidencias']];
    }

    //Agregado por: Teddy Moreira
    function getCatalogoForm(){
        $sql = "SELECT * 
        FROM Catalogo_Auditor
        WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
        AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
        ORDER BY Codigo";
        return $this->db->datos($sql);
    }

    function consultarCliente($cedula){
        $sql = "SELECT Codigo,Cliente,Telefono,CI_RUC,Sexo,Fecha_N,Plan_Afiliado,Est_Civil,Calificacion,Gestacion,Especial,Referencia,Dosis,Asignar_Dr,DireccionT,Representante,CodigoA,Contacto,Telefono_R,Tipo_Cta,Canton,Parroquia,Barrio,Direccion,DirNumero,Credito,No_Dep,Matricula,Cod_Banco,Descuento,Profesion,Porc_C,FAX,FactM,Casilla,Cod_Ejec,Cta_CxP,Lugar_Trabajo,Tipo_Cliente,Bono_Desarrollo,IESS,Actividad,Tipo_Vivienda,Servicios_Basicos,Archivo_CI_RUC_PAS,Archivo_Record_Policial,Archivo_Planilla,Archivo_Carta_Recom,Archivo_Certificado_Medico,Archivo_VIH,Archivo_Reglamento
        FROM Clientes
        WHERE CI_RUC = '".$cedula."'
        AND TB = '93.03'";
        return $this->db->datos($sql);
    }

    function crearInscripcion($param){
        SetAdoAddNew("Clientes");
        foreach($param as $key => $value){
            SetAdoFields($key, $value);
        }
        SetAdoFields("T", '.');
        SetAdoFields("FA", 0);
        SetAdoFields("Codigo", $param['CI_RUC']);
        SetAdoFields("TD", "C");
        SetAdoUpdate();
        return 1;
    }

    function actualizarInscripcion($param){
        $TB = $param['TB'];
        $cedula = $param['CI_RUC'];
        
        unset($param['TB']);
        unset($param['CI_RUC']);

        SetAdoAddNew("Clientes");
        foreach($param as $key => $value){
            SetAdoFields($key, $value);
        }
        SetAdoFieldsWhere("TB", $TB);
        SetAdoFieldsWhere("CI_RUC", $cedula);
        SetAdoUpdateGeneric();
        return 1;
    }
}

?>