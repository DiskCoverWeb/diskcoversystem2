<?php
 $orden = '';
if(isset($_GET['orden']))
{
  $orden = $_GET['orden'];
}
 date_default_timezone_set('America/Guayaquil'); ?>
<script type="text/javascript">
    var orden = '<?php echo $orden; ?>';
  $(document).ready(function () {  
    if(orden!='')
    {
      pedidos_solicitados(orden);
      lineas_pedido_aprobacion_solicitados_proveedor(orden)
    }  
  })
</script>
<style>
  .select2-selection__choice {
    display: block !important;
  }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
<script type="text/javascript" src="../../dist/js/inventario/aprobar_solicitud_proveedor.js"></script>
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
        <button type="button" class="btn btn-outline-secondary" title="informe Excel" onclick="imprimir_excel()" >
          <img src="../../img/png/excel2.png">
        </button>
        <button type="button" class="btn btn-outline-secondary" title="Informe pdf" onclick="imprimir_pdf()">
          <img src="../../img/png/pdf.png">
        </button>
        <button title="Guardar"  class="btn btn-outline-secondary" onclick="grabar_compra_pedido()">
          <img src="../../img/png/grabar.png" >
        </button>
      </div>
  </div>
</div>
<div class="row">
  <div class="card">
    <div class="card-body">
      <div class="row">
         <div class="col-lg-4">
          <b>Numero de orden </b><br>
          <span id="lbl_orden"></span>
        </div>
         <div class="col-lg-3">
          <b>Contratista</b><br>
          <span id="lbl_contratista"></span>
        </div>
        <div class="col-lg-3">
          <b>Total</b><br>
          <span id="lbl_total"></span>
        </div>
      </div>
    </div>
  </div>  
</div>
<div class="row mb-2">
  <div class="card">
    <div class="card-body">
        <div class="row">
          <form id="form_lineas">
            <div class="col-sm-12">      
              <input type="hidden" name="txt_linea_Select" id="txt_linea_Select" value="">
              <div class="table-responsive">
                <table class="table" id="tbl_lista_solicitud">
                  <thead>
                    <thead>
                      <th>item</th>
                      <th>Codigo</th>
                      <th>Producto</th>
                      <th>Cantidad</th>
                      <th>Unidad</th>
                      <th>Precio ref</th>
                      <th>Total ref</th>
                      <th>Fecha Solicitud</th>
                      <th>Fecha Entrega</th>
                      <th>Observacion</th>
                      <th width="28%">Proveedores proforma</th>
                      <th width="28%">Proveedor Seleccionado</th>
                    </thead>
                    <tbody id="tbl_body">
                    
                    </tbody>
                  </thead>
                </table>
                
              </div>
            </div>    
          </form>
      </div>  
      
    </div>    
  </div>  
</div>


<div id="myModal_provedor" class="modal fade myModal_provedor" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title">Seleccionar proveedor</h4>
            </div>
            <div class="modal-body" style="background: antiquewhite;">
              <form id="form_proveedor_seleccionado">
              <input type="hidden" name="txt_id_linea" id="txt_id_linea">
              <input type="hidden" name="txt_id_prove" id="txt_id_prove">
              <div class="row">
                <div class="col-sm-12">
                  <table class="table text-sm">
                    <thead>
                      <th>Proveedor</th>
                      <th>Cantidad</th>
                      <th>Costo Ref</th>
                      <th>Costo Real</th>
                    </thead>
                    <tbody id="tbl_body_prov">
                      
                    </tbody>
                  </table>
                </div>               
              </div>
              </form>
            </div>
             <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="guardar_seleccion_proveedor()">Guardar</button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
  </div>