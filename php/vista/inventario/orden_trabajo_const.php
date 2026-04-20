<?php date_default_timezone_set('America/Guayaquil'); $ordenNo = '-1'; if(isset($_GET['ordenNo']) && $_GET['ordenNo']!=''){$ordenNo = $_GET['ordenNo'];}?>

<script type="text/javascript">
 var ordenNo = '<?php echo $ordenNo; ?>';
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
<script type="text/javascript" src="../../dist/js/inventario/orden_trabajo_const.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
   contratistas();
   ddl_cuenta_contable();
   ddl_Proceso();
   ddl_Grupo();
   ddl_Rubro();
  })

</script>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3"><?php echo $NombreModulo; ?></div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0"  id="ruta_menu">
          <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
          </li>
        </ol>
      </nav>
    </div>          
</div>
<div class="row mb-2">
  <div class="col-sm-6">
     <div class="btn-group" role="group" aria-label="Basic example">
        <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
                print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
            <img src="../../img/png/salire.png">
        </a>
        <button type="button" class="btn btn-outline-secondary" title="Informe excel" onclick="imprimir_excel()" >
          <img src="../../img/png/excel2.png">
        </button>
        <button type="button" class="btn btn-outline-secondary" title="Informe pdf" onclick="imprimir_pdf()">
          <img src="../../img/png/pdf.png">
        </button>
        <button title="Guardar"  class="btn btn-outline-secondary" onclick="grabar_orden_trabajo()">
          <img src="../../img/png/grabar.png" >
        </button>
      </div>
  </div>
</div>
<div class="row mb-2">
  <div class="card">
    <div class="card-body">
        <div class="row">
          <div class="col-lg-4">
            <b>Contratista </b><br>
            <select class="form-select form-select-sm" id="ddl_contratista" name="ddl_contratista" onchange="cargar_contratos()">
              <option value="">Seleccione</option>
            </select>
          </div>
          <div class="col-lg-4">
            <b>Rubro </b><br>
            <select class="form-select form-select-sm" id="ddl_Rubro" name="ddl_Rubro">
              <option value="">seleccione</option>
            </select>
          </div>
          <div class="col-lg-4">
            <b>Contrato</b><br>
            <select class="form-select form-select-sm" id="ddl_Contrato" name="ddl_Contrato" disabled>
              <option value="">Seleccione</option>
            </select>
          </div>
          
        </div>
    </div>
  </div>
</div>
<div class="row mb-2 d-none" id="pnl_detalle_proyecto">
  <div class="card">
    <div class="card-body">
       <div class="row">
            <div class="col-sm-4">              
              <b>Proyectos: </b><br>
              <label id="lbl_proyecto">Proyecto de aaa</label>
            </div>   
           <!--  <div class="col-sm-12">
              <b>Contratista: </b>    <br>         
              <label>Proyecto de aaa</label>
            </div> -->
            <div class="col-sm-3">
                <b>Se entrega a cargo material: </b><br>
                <span class=" text-center" id="lbl_material">SI / NO</span>
            </div>
            <div class="col-sm-5">
              <b>El contratista ejecuta el trabajo con mas de una persona:</b><br>
              <span class=" text-center" id="lbl_mas_personas"> SI / NO </span>
            </div>
           
           <div class="col-sm-4">
            <b>Tipo Contrato: </b><br>
            <label id="lbl_categoria">Adicionales</label>
          </div>
          <div class="col-sm-4">
            <b>Fecha inicio: </b>  <br>
            <label id="lbl_fecha">2025-02-01</label>          
          </div>
          <div class="col-sm-4">
            <b>Fecha fin: </b> <br>
            <label id="lbl_fecha_v">2025-02-01</label>
          </div>
        </div>
    </div>
  </div>
</div>
<div class="row mb-2 d-none" id="pnl_centro_costos_proyecto">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-1">
          <input type="hidden" name="txt_centro_costos" id="txt_centro_costos">
          <input type="hidden" name="txt_id_centro_costos" id="txt_id_centro_costos">
        </div>
        <div class="col-sm-3">
          <b>Sub Rubro</b>
          <select class="form-select form-select-sm w-100" id="ddl_sub_rubro" onchange="ddl_sub_rubro()">
            <option value="">Seleccione sub rubor</option>
          </select>
        </div>   
        <div class="col-sm-1">
          <b>Unidad</b>
          <input type="" class="form-control form-control-sm" name="txt_unidad" id="txt_unidad">
        </div>   
        <div class="col-sm-1">
           <b>Cantidad</b>
          <input type="" class="form-control form-control-sm" name="txt_cantidad" id="txt_cantidad" onblur="calcular_costo_total()">
        </div>   
        <div class="col-sm-2">
           <b>Costo Unitario</b>
          <input type="" class="form-control form-control-sm" name="txt_costo_pvp" id="txt_costo_pvp" onblur="calcular_costo_total()">
        </div>   

        <div class="col-sm-2">
           <b>Costo total</b>
          <input type="" class="form-control form-control-sm" name="txt_costo_total" id="txt_costo_total" readonly>
        </div>   
         <div class="col-sm-2 text-end">
           <br>
           <button type="button" class="btn btn-primary btn-sm" onclick="add_subRubro()">Agregar</button>
        </div>            
      </div>
      <hr>
      <div class="row">
        <div class="col-sm-12">
          <div class="d-flex align-items-start">
            <div class="nav flex-column nav-pills nav-pills-warning me-3" role="tablist" aria-orientation="vertical" id="tab_button">
            </div>
            <div class="tab-content w-75 text-center" id="tab_content">
             
              
            </div>
          </div>
          
        </div>
      </div>
    </div>    
  </div>
</div>

<div class="modal fade" id="myModal_periodo_trabajo" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-12">
                  <b>Semana</b>
                  <select class="form-select form-select-sm" id="ddl_semana" name="ddl_semana">
                    <option value="">Selecione Semana</option>
                  </select>
                </div>
                 <div class="col-sm-12">
                  <b>Fecha Inicio</b>
                  <input type="date" name="txt_fecha_inicio" id="txt_fecha_inicio" class="form-control form-control-sm">
                </div>
                 <div class="col-sm-12">
                  <b>Fecha Fin</b>
                  <input type="date" name="txt_fecha_fin" id="txt_fecha_fin" class="form-control form-control-sm">
                </div>
                 <div class="col-sm-12">
                  <b>Observaciones</b>
                  <textarea name="txt_observacion" id="txt_observacion" class="form-control form-control-sm"></textarea>
                </div>                
              </div>
            </div>
             <div class="modal-footer">
                  <button type="button" class="btn btn-primary" id="btn_guardar_periodo" name="btn_guardar_periodo" onclick="guardar_periodo()"><i class="bx bx-save"></i>Guardar</button>        
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>
              </div>
        </div>
    </div>
</div>
