<?php 
    require_once(dirname(__DIR__, 2) . "/db/db1.php");
    require_once(dirname(__DIR__,2)."/funciones/funciones.php");
    @session_start();

    class InscVoluntariosM{
        private $db;

        function __construct(){
            $this->db = new db();
        }

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