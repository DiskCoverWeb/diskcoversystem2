<?php 
$cod = ''; $ci =''; if(isset($_GET['cod'])){$cod = $_GET['cod'];} if(isset($_GET['ci'])){$ci = $_GET['ci'];}?>
<script type="text/javascript">
   var ci = '<?php echo $ci; ?>';
   var cod = '<?php echo $cod; ?>';

</script>
<script src="../../dist/js/farmacia/reporte_descargos_procesados.js"></script>
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

<div class="row-cols-auto  mb-2">
  <div class="btn-group">
    <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0]);?>" title="Salir de modulo" class="btn btn-outline-secondary">
      <img src="../../img/png/salire.png">
    </a>
   <a href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0]);?>&acc=pacientes" type="button" class="btn btn-outline-secondary" id="imprimir_pdf" title="Pacientes">
      <img src="../../img/png/pacientes.png">
    </a>           
   <a href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0]);?>&acc=vis_descargos" type="button" class="btn btn-outline-secondary" id="imprimir_excel" title="Descargos">
    <img src="../../img/png/descargos.png">
  </a>         
  <a href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0]);?>&acc=articulos" title="Ingresar Articulosr"  class="btn btn-outline-secondary" onclick="">
    <img src="../../img/png/articulos.png" >
  </a>
  <button type="button" class="btn btn-outline-secondary" title="Generar pdf" onclick="reporte_pdf()"><img src="../../img/png/pdf.png"></button>
  <button type="button" class="btn btn-outline-secondary" title="Generar excel" onclick="reporte_excel()"><img src="../../img/png/table_excel.png"></button>
 </div>
</div>
<div class="row mb-2">
  <div class="card">
    <div class="card-body"> 
        <form method="post" id="filtro_bus" enctype="multipart/form-data">
          <div class="row">
            <div class="col-sm-4">
               <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_nombre" checked="" value="N"> Nombre</label>
               <br>
              <b>NOMBRE DE PACIENTE</b>
              <input type="text" name="txt_query" id="txt_query" class="form-control form-control-sm" placeholder="Nombre paciente" onkeyup="cargar_pedidos();cargar_pedidos_detalle()">
            </div>
            <div class="col-sm-2">
              <br>
              <b>FECHA INICIO</b>
              <input type="date" name="txt_desde" id="txt_desde" class="form-control form-control-sm" value="<?php echo date('Y-m-d')?>" onblur="cargar_pedidos('f');cargar_pedidos_detalle('f')">
            </div>
            <div class="col-sm-2">
              <br>
              <b>FECHA FIN</b>
              <input type="date" name="txt_hasta" id="txt_hasta" class="form-control form-control-sm" value="<?php echo date('Y-m-d')?>" onblur="cargar_pedidos('f');cargar_pedidos_detalle('f')">
            </div>
            <div class="col-sm-4">
              <br>
              <b>Area de descargo</b> <br>
              <select class="form-select form-select-sm" id="ddl_areas" onchange="cargar_pedidos();cargar_pedidos_detalle()">
                <option value="">Seleccione area de ingreso</option>
              </select> 
               <button type="button" class="btn btn-xs btn-default btn-flat" onclick="$('#ddl_areas').val(null).trigger('change');"><i class="fa fa-close"></i></button>                 
            </div>

              <input type="hidden" name="txt_tipo_filtro" id="txt_tipo_filtro" value=""> 
          </div>
        </form>   
    </div>
</div>
<div class="row">
  <div class="card">
    <div class="card-body">
      <ul class="nav nav-tabs nav-warning" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" data-bs-toggle="tab" href="#warninghome" role="tab" aria-selected="true">
            <div class="d-flex align-items-center">
              <div class="tab-icon"><i class="bx bx-home font-18 me-1"></i>
              </div>
              <div class="tab-title">Descargos Realizados</div>
            </div>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" data-bs-toggle="tab" href="#warningprofile" role="tab" aria-selected="false" tabindex="-1">
            <div class="d-flex align-items-center">
              <div class="tab-icon"><i class="bx bx-user-pin font-18 me-1"></i>
              </div>
              <div class="tab-title">Detalle de descargos</div>
            </div>
          </a>
        </li>
      </ul>
      <div class="tab-content py-3">
        <div class="tab-pane fade show active" id="warninghome" role="tabpanel">
         <div class="row">
            <div class="col-sm-12">
              <div class="table-responsive">
                <table class="table" id="tbl_body">
                  <thead>
                    <th></th>
                    <th>Numero</th>
                    <th>Fecha</th>
                    <th>Concepto</th>
                    <th>Monto_Total</th>
                    <th>Cliente</th>
                    <th>Area</th>
                  </thead>
                  <tbody></tbody>
                </table>                
              </div>
            </div>      
          </div>
        </div>
        <div class="tab-pane fade" id="warningprofile" role="tabpanel">
          <div class="row">
            <div class="col-sm-12 text-center">
                <h4><b id="titulo_detalle"></b></h4>
            </div>
            <div class="col-sm-12" >
              <br>
              <table class="table table-hover" id="tbl_detalle">
               
              </table>
              
            </div>
          </div>
        </div>
       
      </div>
    </div>
  </div>  
</div>


<div id="num_historial" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Numero historial</h4>
      </div>
      <div class="modal-body">
        <form>
        <div class="row text-center">
          <div class="col-sm-12">
            <b>Numero de Historia clinica</b>
            <input type="txt_nombre" name="txt_histo_actu" id = "txt_histo_actu" class="form-control input-xs">  
          </div> 
      </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="actualizar_num_historia()">Guardar</button>
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
      </div>
    </div>
  </div>
</div>

