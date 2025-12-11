<?php  ?>

<script type="text/javascript" src="../../dist/js/contratos/reporte_GFN.js"></script>
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
        <button type="button" class="btn btn-outline-secondary" title="Informe excel" id="imprimir_excel" >
          <img src="../../img/png/excel2.png">
        </button>
        <button type="button" class="btn btn-outline-secondary" title="Informe pdf" id="imprimir_pdf">
          <img src="../../img/png/pdf.png">
        </button>
        <!-- <button title="Guardar"  class="btn btn-outline-secondary" onclick="grabar_solicitud_proveedor()">
          <img src="../../img/png/grabar.png" >
        </button> -->
      </div>
  </div>
</div>
<div class="row mb-2">
  <div class="card">
    <div class="card-body">
      <div class="row">
         <div class="col-sm-6 mb-2">
          <b>Indicador de gestion</b><br>
          <div class="d-flex align-items-center">
             <select class="form-select form-select-sm" id="ddl_indicador_gestion" onchange="cargar_lista()">
               <option value="">Seleccione</option>
             </select> 
             <button class="btn btn-sm btn-outline-secondary  p-1" onclick="limpiar()"><i class="bx bx-x"></i></button>
          </div>       
        </div>
        <div class="col-sm-6 text-end">
          <button class="btn btn-sm btn-primary" onclick="cargar_lista()"><i class="bx bx-search"></i> Buscar</button>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <table class="table" id="tbl_lista">
            <thead>
              <th>#</th>
              <th>Codigo</th>
              <th>Descripcion</th>
              <th>Enero</th>
              <th>Febrero</th>
              <th>Marzo</th>
              <th>Abril</th>
              <th>Mayo</th>
              <th>Junio</th>
              <th>Julio</th>
              <th>Agosto</th>
              <th>Septiembre</th>
              <th>Octubre</th>
              <th>Noviembre</th>
              <th>Diciembre</th>
              <th>Total_Meses</th>
            </thead>
            <tbody></tbody>
          </table>        
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="nuevo_indicador" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header gap-2">
        <div class="position-relative popup-search w-100">
          <h3>Nuevo indicador</h3>
        </div>
        <button type="button" class="btn-close d-md-none" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-4">
            <b>Codigo</b>
            <input type="text" class="form-control form-control-sm" id="txt_codigo" name="txt_codigo">
            
          </div>
          <div class="col-sm-8">
            <b>Identificador de gestion</b>
            <input type="text" class="form-control form-control-sm" id="txt_identificador" name="txt_identificador">
            
          </div>          
        </div>
        
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="guardar_indicador()">Guardar</button>
          <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
