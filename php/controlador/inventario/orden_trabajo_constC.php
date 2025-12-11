    <?php
    require_once(dirname(__DIR__,2).'/modelo/inventario/orden_trabajo_constM.php');

    $controlador = new orden_trabajo_constC();
    if(isset($_GET['ddl_cuenta_contable']))
    {
        $query = false;
        if(isset($_GET['q'])){$query = $_GET['q'];}
        echo json_encode($controlador->ddl_cuenta_contable($query));
    }

    if(isset($_GET['ddl_Proceso']))
    {
        $query = false;
        if(isset($_GET['q'])){$query = $_GET['q'];}
        echo json_encode($controlador->ddl_Proceso($query));
    }

    if(isset($_GET['ddl_Grupo']))
    {
        $query = false;
        if(isset($_GET['q'])){$query = $_GET['q'];}
        echo json_encode($controlador->ddl_Grupo($query));
    }
    if(isset($_GET['ddl_Rubro']))
    {
        $query = false;
        if(isset($_GET['q'])){$query = $_GET['q'];}
        echo json_encode($controlador->ddl_Rubro($query));
    }
    if(isset($_GET['agregar_tabla']))
    {
        $orden = $_POST['orden'];
        parse_str($_POST['parametros'], $parametros);
        echo json_encode($controlador->agregar_tabla($parametros,$orden));
    }
    if(isset($_GET['cargar_lista']))
    {
        $parametros = $_POST['parametros'];
        echo json_encode($controlador->cargar_lista($parametros));
    }
    if(isset($_GET['eliminar_linea']))
    {
        $id = $_POST['ID'];
        echo json_encode($controlador->eliminar_linea($id));
    }


    class orden_trabajo_constC
    {
        private $modelo;
        function __construct(){
            $this->modelo = new orden_trabajo_constM();
        }

        function ddl_cuenta_contable($query){
           $data = $this->modelo->ddl_cuenta_contable($query);
           return $data;
        }

        function ddl_Proceso($query){
           $data = $this->modelo->ddl_Proceso($query);
           return $data;
        }

        function ddl_Grupo($query){
           $data = $this->modelo->ddl_Grupo($query);
           return $data;
        }
        function ddl_Rubro($query){
           $data = $this->modelo->ddl_Rubro($query);
           return $data;
        }
        function agregar_tabla($parametros,$orden)
        {
            // print_r($parametros);
            // print_r($orden);
            // die();

            if($orden==-1)
            {
                $dia = date('Ymd');
                $numero_secuencial = numero_comprobante1("OrdenT".$dia,$_SESSION['INGRESO']['item'],true,date('Y-m-d'));
                $registro = generaCeros(intval($numero_secuencial),3);
                $orden = $dia.'_'.$parametros['ddl_contratista'].'_'.$registro;
                SetAdoAddNew("Catalogo_SubCtas");
                SetAdoFields("TC","OT");
                SetAdoFields("Codigo",$parametros['ddl_contratista']);
                SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
                SetAdoFields("Item",$_SESSION['INGRESO']['item']);
                SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
                SetAdoFields("Fecha_D",$parametros['txt_fechaIni']);
                SetAdoFields("Fecha_H",$parametros['txt_fechaFin']);
                SetAdoFields("Detalle",$orden);
                if(!SetAdoUpdate())
                {
                     return array("resp"=>-1,'orden'=>$orden);
                } 

            }

            SetAdoAddNew("Trans_SubCtas");
            SetAdoFields("T","N");
            SetAdoFields("TC","OT");
            SetAdoFields("Cta",$parametros["ddl_cuenta_contable"]);
            SetAdoFields("Fecha",$parametros['txt_fechaIni']);
            SetAdoFields("Fecha_V",$parametros['txt_fechaFin']);
            SetAdoFields("Codigo",$parametros['ddl_contratista']);
            SetAdoFields("Numero","-1"); 
            SetAdoFields("Factura","-1"); 
            SetAdoFields("Proceso",$parametros["ddl_Proceso"]); 
            SetAdoFields("Grupo",$parametros["ddl_Grupo"]); 
            SetAdoFields("Rubro",$parametros["ddl_Rubro"]); 
            SetAdoFields("UnidadMed",$parametros["txt_unidadMed"]); 
            SetAdoFields("Cantidad",$parametros["txt_cantidad"]); 
            SetAdoFields("CantidadOrd",$parametros["txt_cantidadOrd"]); 
            SetAdoFields("Diferencia",$parametros["txt_diferencia"]); 
            SetAdoFields("Detalle_SubCta",$parametros["txt_observacion"]); 
            SetAdoFields("Comp_No",$parametros["rbl_contrato"]); 
            SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
            SetAdoFields("Item",$_SESSION['INGRESO']['item']);
            SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
            SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
            SetAdoFields("Autorizacion",$orden);
            SetAdoFields("Categoria_Contrato",$parametros["txt_categoria"]); 
            SetAdoFields("No_Contrato",$parametros["txt_NoContrato"]); 
            SetAdoUpdate(); 

            return array("resp"=>1,'orden'=>$orden);
        }

        function cargar_lista($parametros)
        {
            return $this->modelo->cargar_lista($parametros['orden']);
        }
        function eliminar_linea($id)
        {
            return $this->modelo->eliminar_linea($id);
        }
    }

    ?>