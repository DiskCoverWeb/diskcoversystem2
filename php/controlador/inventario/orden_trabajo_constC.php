    <?php
    require_once(dirname(__DIR__,2).'/modelo/inventario/orden_trabajo_constM.php');
    require_once(dirname(__DIR__,2).'/modelo/inventario/contrato_trabajo_detalle_constM.php');

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
    if(isset($_GET['aprob_cargar_lista']))
    {
        $parametros = $_POST['parametros'];
        echo json_encode($controlador->aprob_cargar_lista($parametros));
    }
    if(isset($_GET['eliminar_linea']))
    {
        $id = $_POST['ID'];
        echo json_encode($controlador->eliminar_linea($id));
    }

    if(isset($_GET['contratistas']))
    {
        $query = false;
        if(isset($_GET['q'])){$query = $_GET['q'];}
        echo json_encode($controlador->contratistas($query));
    }

    if(isset($_GET['contratos']))
    {
        $query = false;
        if(isset($_GET['q'])){$query = $_GET['q'];}
        if(isset($_GET['ContratosContratista'])){$contratista = $_GET['ContratosContratista'];}
        echo json_encode($controlador->contratos($contratista,$query));
    }

    if(isset($_GET['detalleContrato']))
    {
        $parametros = $_POST['parametros'];
        echo json_encode($controlador->detalleContrato($parametros));
    }

    if(isset($_GET['centrosCostocXRubro']))
    {
        $parametros = $_POST['parametros'];
        echo json_encode($controlador->centrosCostocXRubro($parametros));
    }

    if(isset($_GET['subrubro']))
    {
        $query='';
        $rubro='';
        if(isset($_GET['q'])){$query = $_GET['q'];}
        if(isset($_GET['rubro'])){$rubro = $_GET['rubro'];}
        echo json_encode($controlador->subrubro($query,$rubro));
    }

    if(isset($_GET['add_subRubro']))
    {
        $parametros = $_POST['parametros'];
        echo json_encode($controlador->add_subRubro($parametros));
    }

    if(isset($_GET['cargar_lista_subrubros']))
    {
        $parametros = $_POST['parametros'];
        echo json_encode($controlador->cargar_lista_subrubros($parametros));
    }

    if(isset($_GET['delete_subrubro']))
    {
        $parametros = $_POST['parametros'];
        echo json_encode($controlador->delete_subrubro($parametros));
    }

    if(isset($_GET['guardar_periodo']))
    {
        $parametros = $_POST['parametros'];
        echo json_encode($controlador->guardar_periodo($parametros));
    }

    if(isset($_GET['grabar_orden_trabajo']))
    {
        $parametros = $_POST['parametros'];
        echo json_encode($controlador->grabar_orden_trabajo($parametros));
    }




    class orden_trabajo_constC
    {
        private $modelo;
        private $contrato;
        function __construct(){
            $this->modelo = new orden_trabajo_constM();
            $this->contrato = new contrato_trabajo_detalle_constM();
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

            SetAdoAddNew("Trans_Contratistas");
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
        function aprob_cargar_lista($parametros)
        {
            return $this->modelo->aprob_cargar_lista($parametros['orden']);
        }
        function eliminar_linea($id)
        {
            return $this->modelo->eliminar_linea($id);
        }

        function contratistas($query)
        {
           return $this->modelo-> contratistas($query);
        }
        function contratos($contratista,$query)
        {
           $rubro = array();
           $data =  $this->modelo->rubrosXcontratista($query,$contratista);
           foreach ($data as $key => $value) {
               $rubro[] = array('id'=>$value['Cta'],'text'=>$value['Cuenta'],'data'=>$value);
           }
           return $rubro;
        }

        function detalleContrato($parametros)
        {
            $contrato = $parametros['contrato'];
            return $this->contrato->detalleContrato($contrato,"A");
            // print_r($parametros);die();
        }

        function centrosCostocXRubro($parametros)
        {
            $data = $this->modelo->centrosCostocXRubro($parametros['contrato'],$parametros['rubro']);

            return $data;
            // print_r($data);die();
            // print_r($parametros);die();
        }

        function subrubro($query,$rubro)
        {
            $lista = array();
            $data = $this->modelo->subrubro($rubro,$query);
            foreach ($data as $key => $value) {
                $lista[] = array('id'=>$value['ID'],'text'=>$value['Detalle'],'data'=>$value);
            }

            return $lista;
        }

        function add_subRubro($parametros)
        {
            // print_r($parametros);die();

            $data = $this->modelo->cargar_lista_subrubros($parametros['Contrato'],$parametros['rubro'],$parametros['subRubro'],$parametros['centroCostos'],$parametros["contratista"]);
            if(count($data)==0)
            {
                SetAdoAddNew("Entidad_Rubro_Contratista");
                SetAdoFields("Rubro",$parametros['rubro']);
                SetAdoFields("Sub_Rubro",$parametros['subRubro']);
                SetAdoFields("Centro_Costo",$parametros['centroCostos']);
                SetAdoFields("Contratista",$parametros["contratista"]);
                SetAdoFields("No_Contrato",$parametros['Contrato']);

                SetAdoFields("Unidad",$parametros['unidad']);
                SetAdoFields("Cantidad",$parametros['cantidad']);
                SetAdoFields("PVP",$parametros['pvp']);
                SetAdoFields("Total",$parametros['total']);

                SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
                SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
                SetAdoFields("Item",$_SESSION['INGRESO']['item']);
                return SetAdoUpdate(); 
            }else
            {
                return -2;
            }

        }

        function cargar_lista_subrubros($parametros)
        {            
            $data = $this->modelo->cargar_lista_subrubros($parametros['Contrato'],$parametros['rubro'],$subrubro=false,$parametros['centroCostos'],$parametros['contratista']);

            return $data;
            // print_r($data);die();
        }

        function delete_subrubro($parametros)
        {
            return $this->modelo->delete_subrubro($parametros['id']);
            // print_r($parametros);die();
        }

        function guardar_periodo($parametros)
        {

            // print_r($parametros);die();
            SetAdoAddNew("Trans_Contratistas_Rubros");
            SetAdoFields("Semana",$parametros['semana']);
            SetAdoFields("Fecha_Inicio",$parametros['fechaInicio']);
            SetAdoFields("Fecha_Fin",$parametros['fechaFin']);
            SetAdoFields("Observacion",$parametros["observacion"]);


            SetAdoFieldsWhere('ID',$parametros["idCentroCostos"]);
            return SetAdoUpdateGeneric(); 
        }

        function grabar_orden_trabajo($parametros)
        {

            $mensaje = '';
            $existeSubRubroAll = 1;
            $existePeriodoAll = 1;

            $centroCostos = $this->modelo->centrosCostocXRubro($parametros['contrato'],$parametros['rubro']);
            foreach ($centroCostos as $key => $value) {
                // print_r($value);die();
                $subrubro = $this->modelo->cargar_lista_subrubros($parametros['contrato'],$parametros['rubro'],false,$value['Centro_Costos'],false);
                if(count($subrubro)==0)
                {
                    $existeSubRubroAll = 0;
                    $mensaje.='<b>'.$value['Detalle'].':</b> no tiene Sub Rubros asignados <br>';
                }
                if($value['Semana']=='' || $value['Semana']==0 || $value['Fecha_Inicio']=='' || $value['Fecha_Fin']=='' || $value['Fecha_Inicio']==null || $value['Fecha_Fin']==null )
                {                    
                    $mensaje.='<b>'.$value['Detalle'].':</b> Periodos no asignados <br>';
                    $existePeriodoAll = 0;
                }
            }

            $resp = -1;
            if($existePeriodoAll == 1 && $existeSubRubroAll == 1)
            {

                $this->modelo->grabar_orden_trabajo($parametros['contrato']);
                $resp = 1;
            }

            return array('respuesta'=>$resp,'mensaje'=>$mensaje);
        }
    }

    ?>