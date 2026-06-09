<?php
require_once(dirname(__DIR__,2).'/modelo/inventario/orden_ejecucionM.php');
require_once(dirname(__DIR__,2).'/modelo/inventario/contrato_trabajo_detalle_constM.php');
require_once(dirname(__DIR__,2).'/modelo/inventario/orden_trabajo_constM.php');

$controlador = new orden_ejecucionC();
if(isset($_GET['lista_orden_ejecucion']))
{
    echo json_encode($controlador->lista_orden_ejecucion());
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

if(isset($_GET['lista_semanas']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->lista_semanas($parametros));
}

if(isset($_GET['cargar_lista_subrubros']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->cargar_lista_subrubros($parametros));
}

if(isset($_GET['detalleContrato']))
{
    $parametros = $_POST['parametros'];
     $parametros['tipo'] = 'E';
    echo json_encode($controlador->detalleContrato($parametros));
}
if(isset($_GET['detalleContratoEjec']))
{
    $parametros = $_POST['parametros'];
    $parametros['tipo'] = 'A';
    echo json_encode($controlador->detalleContrato($parametros));
}

if(isset($_GET['guardar_subrubro_ejecucion']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->guardar_subrubro_ejecucion($parametros));
}

if(isset($_GET['cargar_fecha_periodo']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->cargar_fecha_periodo($parametros));
}

if(isset($_GET['guardar_periodo']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->guardar_periodo($parametros));
}

if(isset($_GET['contratistasBuscar']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->contratistasBuscar($parametros));
}

if(isset($_GET['finalizar_ejecucion']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->finalizar_ejecucion($parametros));
}

// control de avances

if(isset($_GET['contratistasAvances']))
{
    $query = false;
    if(isset($_GET['q'])){$query = $_GET['q'];}
    echo json_encode($controlador->contratistasAvances($query));
}


if(isset($_GET['contratosAvances']))
{
    $query = false;
    if(isset($_GET['q'])){$query = $_GET['q'];}
    $contratista = $_GET['CotraAvance'];
    echo json_encode($controlador->contratosAvances($contratista,$query));
}


if(isset($_GET['rubrosAvances']))
{
    $query = false;
    if(isset($_GET['q'])){$query = $_GET['q'];}
    $contrato = $_GET['CotratoAvance'];
    echo json_encode($controlador->rubrosAvances($contrato,$query));
}

if(isset($_GET['mesesAvances']))
{
    $query = false;
    if(isset($_GET['q'])){$query = $_GET['q'];}
    $contrato = $_GET['CtaRubroAvance'];
    echo json_encode($controlador->mesesAvances($contrato,$query));
}


if(isset($_GET['cargar_lista_subrubros_avance']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->cargar_lista_subrubros_avance($parametros));
}





class orden_ejecucionC
{
    private $modelo;
    private $contratos;
    private $orden;
    function __construct(){
        $this->modelo = new orden_ejecucionM();
        $this->contratos = new contrato_trabajo_detalle_constM();
        $this->orden = new orden_trabajo_constM();
    }

    function lista_orden_ejecucion(){

        $result = $this->contratos->detalleContrato_ejecucion(false,'E');

        // $result = $this->modelo->lista_orden_ejecucion();
        // print_r($result);die();
        return $result;
    }

    function contratistas($query)
    {
       return $this->modelo->contratistas($query);
    }

    function contratistasBuscar($parametros)
    {
        // print_r($parametros);die();
        $codigo = $parametros['contratista'];
       return $this->modelo->contratistas(false,$codigo);
    }

    function contratistasAvances($query)
    {
          return $this->modelo->contratistas($query,false,'N');
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

    function lista_semanas($parametros)
    {
        // print_r($parametros);die();
         $data = $this->orden->SemanasXcentrosCostocXRubro($parametros['contrato'],$parametros['rubro']);
        // print_r($data);die();
        return $data;
    }

   function cargar_lista_subrubros($parametros)
    {            
        $tbl = '';
        $CentroCostos = $this->orden->centrosCostocXRubro($parametros['Contrato'],$parametros['rubro'],$parametros['semana']);
        // print_r($CentroCostos);die();
        foreach ($CentroCostos as $key => $value) {
            // print_r($value);die();
            $tbl.='<div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <h5>'.$value['Detalle'].'</h5>
                        </div>
                       
                        <div class="col-sm-12">


                <table class="table table-hover">
                    <thead>
                      <th></th>
                      <th>Sub Rubros</th>
                      <th>Unidad</th>
                      <th>Orden</th>
                      <th>Costo total de Orden </th>
                      <th>Por Ejecut - Ejecutado</th>
                      <th>Costo unitario ejecutado</th>
                      <th>Costo total ejecutado</th>
                      <th>Diferencia</th>
                     <!-- <th></th> -->
                    </thead><tbody>';
            $data = $this->orden->cargar_lista_subrubros_procesar($parametros['Contrato'],$parametros['rubro'],$subrubro=false,$value['Centro_Costos'],$parametros['contratista'],$parametros['semana']);
            foreach ($data as $key => $value) {
                // print_r($value);die();
                $tbl.='<tr> 
                            <td><button type="button" class="btn btn-primary btn-sm" onclick="add_periodo(\''.$value['ID'].'\');"><i class="bx bx-save me-0"></i></button></td>
                            <td>'.$value['Detalle'].'</td> 
                            <td>'.$value['Unidad'].'</td>
                            <td>'.$value['No_Contrato'].'</td>
                            <td>'.$value['Total'].'</td>
                            <td>
                                <div class="input-group">
                                  <input type="hidden" class="form-control form-control-sm" id="txt_pvp_'.$value['ID'].'" value="'.$value['PVP'].'" readonly />
                                    <input type="text" class="form-control form-control-sm" id="txt_cantidad_'.$value['ID'].'" value="'.$value['Cantidad'].'" readonly />
                                    <input type="text" class="form-control form-control-sm" id="txt_ejecucion_'.$value['ID'].'" onblur="calcular_ejecutado('.$value['ID'].')" value="'.$value['Cant_Ejec'].'" />
                                </div>
                            </td>
                            <td><input type="text" class="form-control form-control-sm" id="txt_ejecutado_pvp_'.$value['ID'].'" value="'.$value['Costo_Unit_Ejec'].'" readonly /></td>
                            <td><input type="text" class="form-control form-control-sm" id="txt_ejecutado_total_'.$value['ID'].'" value="'.$value['Costo_Total_Ejec'].'" readonly /></td>
                            <td><input type="text" class="form-control form-control-sm" id="txt_ejecutado_dif_'.$value['ID'].'"  value="'.$value['Diferencia'].'"readonly /></td>
                            <!-- <td> <button type="button" onclick="add_periodo(\''.$value['ID'].'\')" class="btn btn-primary btn-sm"><i class="bx bx-calendar"></i> Periodos</button></td> -->
                        </tr>';
            }

        // print_r($data);die();
            $tbl.='</table></div> </div>
                    </div>';
        }

        return $tbl;
        // print_r($data);die();
    }

    function detalleContrato($parametros)
    {
        $contrato = $parametros['contrato'];
        return $this->contratos->detalleContrato($contrato,$parametros['tipo']);
        // print_r($parametros);die();
    }

    function guardar_subrubro_ejecucion($parametros)
    {

        // print_r($parametros);die();
        SetAdoAddNew("Entidad_Rubro_Contratista");
        SetAdoFields("Cant_Ejec",$parametros['ejec']);
        SetAdoFields("Diferencia",$parametros['ejec_dif']);
        SetAdoFields("Costo_Unit_Ejec",$parametros['pvp_ejec']);
        SetAdoFields("Costo_Total_Ejec",$parametros["total_ejec"]);


        SetAdoFields("Fecha_Inicio_Ejec",$parametros['iniejec']);
        SetAdoFields("Fecha_Fin_Ejec",$parametros['finejec']);
        SetAdoFields("Observacion",$parametros["observacion"]);


        SetAdoFields("TC",'A'); //este es el identificador para que pase a control de avances


        SetAdoFieldsWhere('ID',$parametros["id"]);
        return SetAdoUpdateGeneric(); 
    }

    function cargar_fecha_periodo($parametros)
    {
        $data = $this->contratos->Trans_Contratistas($parametros['id']);
        return $data;
        // print_r($data);die();
    }

    function guardar_periodo($parametros)
    {

        // print_r($parametros);die();
        SetAdoAddNew("Entidad_Rubro_Contratista");
        SetAdoFields("Fecha_Inicio_Ejec",$parametros['fechaInicio']);
        SetAdoFields("Fecha_Fin_Ejec",$parametros['fechaFin']);
        SetAdoFields("Observacion",$parametros["observacion"]);


        SetAdoFieldsWhere('ID',$parametros["idrubro"]);
        return SetAdoUpdateGeneric(); 
    }

    function finalizar_ejecucion($parametros)
    {
        SetAdoAddNew("Trans_Contratistas");
        SetAdoFields("TP",'N');

        SetAdoFieldsWhere('Codigo',$parametros["contratista"]);
        SetAdoFieldsWhere('No_Contrato',$parametros["contrato"]);
        SetAdoFieldsWhere('Item',$_SESSION['INGRESO']["item"]);
        SetAdoFieldsWhere('Periodo',$_SESSION['INGRESO']["periodo"]);

        return SetAdoUpdateGeneric(); 
    }

    function contratosAvances($contratista,$query)
    {

       $lista =  $this->modelo->lista_orden_ejecucion_finalizada($query,$contratista);
       $contratos = array();

       foreach ($lista as $key => $value) {
           $contratos[] = array('id'=>$value['No_Contrato'],'text'=>$value['No_Contrato']);
       }
       return $contratos;
    }


    function rubrosAvances($contratista,$query)
    {

       $lista =  $this->modelo->rubrosXcontratista($query,false,$contratista);
       $contratos = array();

       // print_r($lista);die();
       foreach ($lista as $key => $value) {
           $contratos[] = array('id'=>$value['Cta'],'text'=>$value['Cuenta']);
       }
       return $contratos;
    }

    function mesesAvances($rubrocta,$query)
    {

        $lista = $this->contratos->Trans_Contratistas_meses($id=false,$contrato=false,$rubrocta);

        $mesesAvances = array();
        foreach ($lista as $key => $value) {
            $mesesAvances[] = array('id'=>$value['Mes'],'text'=> mes_X_nombre($value['Mes']));
        }

        return $mesesAvances;
        print_r($lista);die();
        // print_r($query);die();
        // mes_X_nombre($num)
    }

    function cargar_lista_subrubros_avance($parametros)
    {
        // print_r($parametros);die();
        $data = $this->modelo->rubrosXcontratistaAll(false,$parametros['contratista'],$parametros['contrato'],$parametros['rubro'],$parametros['mes']);

        return $data;

        // print_r($data);die();
    }
}



?>