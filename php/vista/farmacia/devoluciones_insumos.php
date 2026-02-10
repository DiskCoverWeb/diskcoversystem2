<?php
$cod = ''; $ci =''; if(isset($_GET['cod'])){$cod = $_GET['cod'];} if(isset($_GET['ci'])){$ci = $_GET['ci'];}?>
<script type="text/javascript">
 var CI = '<?php echo $ci; ?>';
</script>
<script src="../../dist/js/farmacia/devoluciones_insumos.js"></script>
<script type="text/javascript">
   $( document ).ready(function() {
    // cargar_pedidos();
    cargar_ficha();
    autocoplet_paci();
    autocoplet_area();
    // cargar_ficha();
  });


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
<div class="row row-cols-auto  mb-2">
  <div class="btn-group">
    <a  href="./farmacia.php?mod=28" title="Salir de modulo" class="btn btn-outline-secondary btn-sm">
        <img src="../../img/png/salire.png">
    </a>
    <a href="./farmacia.php?mod=28&acc=pacientes&acc1=Visualizar%20paciente&b=1&po=subcu#" type="button" class="btn btn-outline-secondary btn-sm" id="imprimir_pdf" title="Pacientes">
      <img src="../../img/png/pacientes.png">
    </a>      
    <a href="./farmacia.php?mod=28&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu#" type="button" class="btn btn-outline-secondary btn-sm" id="imprimir_excel" title="Descargos">
        <img src="../../img/png/descargos.png">
    </a>
    <a href="./farmacia.php?mod=28&acc=articulos&acc1=Visualizar%20articulos&b=1&po=subcu#" title="Ingresar Articulos"  class="btn btn-outline-secondary btn-sm" onclick="">
      <img src="../../img/png/articulos.png" >
    </a>
 </div>
</div>

<div class="row mb-2">
  <div class="card">
    <div class="card-body">
      <form method="post" id="filtro_bus" enctype="multipart/form-data">
        <div class="row">
                  <div class="col-sm-6">
                     <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_nombre" checked="" value="N"> Nombre</label>
                     <!-- <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_ruc" value="C"> CI / RUC</label> -->
                     <!-- <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_pedido" value="P"> Pedido</label> -->
                     <br>
                    <b>NOMBRE DE PACIENTE</b>
                    <input type="text" name="txt_query" id="txt_query" class="form-control form-control-sm" placeholder="Nombre paciente" onkeypress="cargar_pedidos()" onblur="cargar_pedidos();cargar_pedidos_detalle()">
                  </div>
                  <div class="col-sm-3">
                    <br>
                    <b>FECHA INICIO</b>
                    <input type="date" name="txt_desde" id="txt_desde" class="form-control form-control-sm" value="<?php echo date('Y-m-d')?>" onblur="cargar_pedidos('f');cargar_pedidos_detalle('f')">
                  </div>
                  <div class="col-sm-3">
                    <br>
                    <b>FECHA FIN</b>
                    <input type="date" name="txt_hasta" id="txt_hasta" class="form-control form-control-sm" value="<?php echo date('Y-m-d')?>" onblur="cargar_pedidos('f');cargar_pedidos_detalle('f')">
                  </div>
                    <input type="hidden" name="txt_tipo_filtro" id="txt_tipo_filtro" value="">  
          </div>
      </form> 
    </div>
  </div>
</div>

<div class="row mb-2"> 
  <div class="card">
    <div class="card-body">
      <ul class="nav nav-pills nav-pills-success mb-3" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" data-bs-toggle="pill" href="#home" role="tab" aria-selected="true">
            <div class="d-flex align-items-center">
              <div class="tab-icon"><i class="bx bx-home font-18 me-1"></i>
              </div>
              <div class="tab-title">Descargos Realizados</div>
            </div>
          </a>
        </li>                 
      </ul>
      <div class="tab-content">
        <div id="home" class="tab-pane fade show active">
         
           <div class="row">
              <div class="col-sm-12">
                <div class="table-responsive">
                  <table class="table table-hover w-100" id="tbl_body">
                    <thead>
                      <th></th>
                      <th>Numero</th>
                      <th>Fecha</th>
                      <th>Concepto</th>
                      <th>Monto_Total</th>
                      <th>Cliente</th>
                    </thead>      
                    <tbody></tbody>              
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
            <input type="txt_nombre" name="txt_histo_actu" id = "txt_histo_actu" class="form-control input-sm">  
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

