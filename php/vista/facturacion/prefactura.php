<!-- INICIO MODULO PRE-FACTURA -->
<?php
  /*if(!isset($facturar)){
    $facturar = new facturar_pensionC();
  }*/

  if(!isset($mostrar_medidor)){
    $mostrar_medidor = false;
    switch ($_SESSION['INGRESO']['modulo_']) {
      case '07': //AGUA POTABLE
        $mostrar_medidor =  true;
        break;  
    }
  }

  define('cantidadProductoPreFacturar', 3);
?>
<style type="text/css">
  .check-group-xs{
    padding: 3px 6px !important;
    font-size: 5px !important;
  }
  .padding-3{
    padding: 3px !important;
  }

  .padding-l-5{
    padding-left: 5px !important;
  }

  #swal2-content{
    font-weight: 500;
    font-size: 1.3em;
  }
</style>
<!-- <div class="col" class="btn-group" >
  <a href="#" title="Insertar Prefacturacion Mensual"  class="btn btn-outline-secondary" onclick="OpenModalPreFactura(<?php echo cantidadProductoPreFacturar ?>)">
    <img src="../../img/png/doc-green.png" width="25" height="30">
  </a>
</div> -->
<div id="myModalPreFactura" class="modal fade" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">Registrar Prefacturacion Mensual: <b><span id="PFnombreCliente"></span></b></h4>
      </div>
      <div class="modal-body">
        <form role="form" id="FInsPreFacturas" name="FInsPreFacturas">
          <div class="box-body">

            <?php if ($mostrar_medidor): ?>
              <div class="row">
                <div class="col-xs-4  no-padding" style="text-align: center;">
                  <label for="CMedidorPrefactura" class="input-xs">Medidor No.</label>
                </div>
                <div class="col-xs-8 no-padding">
                  <select class="form-control input-xs" id="CMedidorPrefactura" name="CMedidorPrefactura">  <option value="<?php echo G_NINGUNO ?>">NINGUNO</option>
                  </select>
                </div>
              </div>
              <hr>
            <?php endif ?>

            <?php $tabindex=55; 
            for ($prod = 1; $prod<=cantidadProductoPreFacturar; $prod++){  ?>
              <div class="row">
                <div class="col-xs-4  no-padding">
                  <div class="input-group">
                    <span class="input-group-addon input-xs check-group-xs">
                      <input type="checkbox" class="PFcheckProducto" name="PFcheckProducto[<?php echo $prod ?>]" id="PFcheckProducto<?php echo $prod ?>" data-indice="<?php echo $prod ?>">
                    </span>
                    <label for="PFcheckProducto<?php echo $prod ?>" class=" input-xs">Producto <?php echo $prod ?>: </label>
                  </div>
                </div>
                <div class="col-xs-8 no-padding">
                  <select class="form-control input-xs PFselectProducto" id="PFselectProducto<?php echo $prod ?>" name="PFselectProducto[<?php echo $prod ?>]" data-indice="<?php echo $prod ?>" >
                    <option value="">Seleccione un producto</option>
                  </select>
                </div>
              </div>
              <div class="row bg-success mt-1 ContenedorDataPFCheck<?php echo $prod ?>">
                <div class="col-md-3 p-1">
                    <label for="PFfechaInicial<?php echo $prod ?>" class="padding-l-5">Fecha Inic.</label>
                    <input disabled type="date" name="PFfechaInicial[<?php echo $prod ?>]" id="PFfechaInicial<?php echo $prod ?>" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-xs-6 col-md-2 p-1">
                  <div class="form-group bg-success">
                    <label for="PFcantidad<?php echo $prod ?>" class="padding-l-5">Cant.</label>
                    <input disabled type="tel" name="PFcantidad[<?php echo $prod ?>]" id="PFcantidad<?php echo $prod ?>" class="form-control  form-control-sm inputNumero text-right" maxlength="2" placeholder="0">
                  </div>
                </div>
                <div class="col-xs-6 col-md-2 p-1">
                    <label for="PFvalor<?php echo $prod ?>" class="padding-l-5">Valor</label>
                    <input disabled type="tel" name="PFvalor[<?php echo $prod ?>]" id="PFvalor<?php echo $prod ?>" class="form-control  form-control-sm inputMoneda text-right" placeholder="0.00">
                </div>
                <div class="col-xs-6 col-md-2 p-1">
                    <label for="PFdescuento<?php echo $prod ?>" class="padding-l-5">Descuento</label>
                    <input disabled type="tel" name="PFdescuento[<?php echo $prod ?>]" id="PFdescuento<?php echo $prod ?>" class="form-control form-control-sm inputMoneda text-right" placeholder="0.00" >
                </div>
                <div class="col-xs-6 col-md-3 p-1">
                    <label for="PFdescuento2_<?php echo $prod ?>" class="padding-l-5">Descuento 2</label>
                    <input disabled type="tel" name="PFdescuento2[<?php echo $prod ?>]" id="PFdescuento2_<?php echo $prod ?>" class="form-control form-control-sm inputMoneda text-right" placeholder="0.00">
                </div>
              </div>
              <hr>
            <?php } ?>
            <input type="hidden" name="PFcodigoCliente" id="PFcodigoCliente">
            <input type="hidden" name="PFGrupoNo" id="PFGrupoNo">
          </div>
        </form>
      </div>
      <div class="modal-footer">
          
        <button class="btn btn-success" title="Inserta Rubros a Facturar" onclick="GuardarPreFactura()">
          <img  src="../../img/png/grabar.png" width="25" height="30">
        </button>
        <button  class="btn btn-danger" title="Eliminar Rubros" onclick="EliminarPreFactura()">
          <img src="../../img/png/delete_file.png" width="25" height="30">
        </button>
        <button class="btn btn-warning" id="btnSalirModuloPF" title="Salir del Modulo" data-bs-dismiss="modal">
          <img  src="../../img/png/salire.png" width="25" height="30">
        </button>
      </div>
    </div>
  </div>
</div>
<!-- FIN MODULO PRE-FACTURA -->
<script type="text/javascript">
  var cantidadProductoPreFacturar = <?php echo cantidadProductoPreFacturar ?>;
</script>
