
<?php
  date_default_timezone_set('America/Guayaquil');
?>
<script>
    var ruc_empresa = '<?php echo $_SESSION['INGRESO']['RUC']; ?>'
</script>
<script src="../../dist/js/facturacion/guia_remision.js"></script>
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
    <div class="col-sm-4">
        <div class=" btn-group">
             <a href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-outline-secondary">
                <img src="../../img/png/salire.png">
            </a>
        </div>
    </div>
</div>
<form id="form_guia">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-2">
                        <b>Fecha emision guia</b>
                        <input type="date" name="MBoxFechaGRE" id="MBoxFechaGRE" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>" onblur="MBoxFechaGRE_LostFocus(); DCPorcenIva('MBoxFechaGRE', 'DCPorcIVA');">
                    </div>
                    <div class="col-sm-1">
                        <b>I.V.A</b>
                        <select class="form-select form-select-sm" name="DCPorcIVA" id="DCPorcIVA" onblur="cambiar_iva()"></select>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <b>Cliente</b>
                        <div class="d-flex align-items-center">
                            <select class="form-select form-select-sm" id="cliente" name="cliente">
                                <option value="">Seleccione un cliente</option>
                            </select>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default btn-sm btn-flat" disabled >
                                   <span class="fa" id="txt_tc" style="color:coral;" name="txt_tc">-</span>
                                </button>
                            </span>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-success btn-sm btn-flat" onclick="addCliente()" title="Nuevo cliente">
                                    <span class="fa fa-user-plus"></span>
                                </button>   
                            </span> 
                        </div>
                        <input type="hidden" name="codigoCliente" id="codigoCliente">
                        <input type="hidden" name="direccion" id="direccion">
                        <input type="hidden" name="ci" id="ci_ruc">
                        <input type="hidden" name="fechaEmision" id="fechaEmision" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="col-sm-3">
                        <b>Email:</b>
                        <input type="text" class="form-control form-control-sm" placeholder="Email" name="email" id="email" readonly>
                    </div>
                    <div class="col-sm-2">
                        <b>Telefono:</b>
                        <input type="text" class="form-control form-control-sm" placeholder="Telefono" name="telefono" id="telefono">
                    </div>
                    <div class="col-sm-1" style="padding-right: 0px;">
                        <b>No. Fac</b>
                        <input type="text" class="form-control form-control-sm" placeholder="1" name="txt_num_fac" id="txt_num_fac" onblur="default_numero()">            
                    </div>                     
                </div>
                <div class="row">
                    <div class="col-sm-1">
                        <b>Serie Fac</b>
                        <input type="text" class="form-control form-control-sm" placeholder="001001" name="txt_serie_fac" id="txt_serie_fac" onblur="default_serie()">            
                    </div> 
                    <div class="col-sm-3">
                        <b>Autorizacion Factura:</b>
                        <input type="text" class="form-control form-control-sm" placeholder="" name="txt_auto_fac" id="txt_auto_fac" onblur="default_auto()">            
                    </div>  
                    <div class="col-sm-2">
                        <b>Guia de remision No.</b><br>
                        <select class="form-select form-select-sm" id="DCSerieGR" name="DCSerieGR" onblur="DCSerieGR_LostFocus()">
                            <option value="">No Existe</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <b>Numero</b>
                        <input type="text" name="LblGuiaR_" id="LblGuiaR_" class="form-control form-control-sm"  value="000000">
                    </div>
                    <div class="col-sm-4">
                          <b>AUTORIZACION GUIA DE REMISION</b>
                          <input type="text" name="LblAutGuiaRem_" id="LblAutGuiaRem_" class="form-control form-control-sm" value="0">
                    </div>
                </div>
                <div class="row mb-2 mt-2">
                    <div class="col-sm-3">
                        <div class="input-group input-group-sm"> 
                            <label class="input-group-text"><b>Iniciacion traslados</b></label>
                            <input type="date" name="MBoxFechaGRI" id="MBoxFechaGRI" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="col-sm-3">                        
                        <div class="input-group-sm d-flex"> 
                            <label class="input-group-text"><b>Ciudad</b></label>
                            <select class="form-select form-select-sm" id="DCCiudadI" name="DCCiudadI">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group input-group-sm"> 
                            <label class="input-group-text"><b>Finalizacion traslados</b></label>
                            <input type="date" name="MBoxFechaGRF" id="MBoxFechaGRF" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="col-sm-3">                        
                        <div class="input-group-sm d-flex"> 
                            <label class="input-group-text"><b>Ciudad</b></label>
                            <select class="form-select form-select-sm" id="DCCiudadF" name="DCCiudadF">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <b>Nombre o razon socila (Transportista)</b>
                                <select class="form-select form-select-sm" id="DCRazonSocial" name="DCRazonSocial" style="width:100%">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <b>Placa</b>
                                <input type="text" name="TxtPlaca" id="TxtPlaca" class="form-control form-control-sm"
                                      value="XXX-999">
                            </div>
                            <div class="col-sm-4">
                                <b>Pedido</b>
                                <input type="text" name="TxtPedido" id="TxtPedido" class="form-control form-control-sm">
                            </div>
                            <div class="col-sm-4">
                                <b>Zona</b>
                                <input type="text" name="TxtZona_" id="TxtZona_" class="form-control form-control-sm">
                            </div>
                            <div class="col-sm-12">
                                <b>Motivo del traslado</b>
                                <input type="text" name="txt_observacion" id="txt_observacion" class="form-control form-control-sm">
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <b>Empresa de Transporte</b>
                                <select class="form-select form-select-sm" id="DCEmpresaEntrega" name="DCEmpresaEntrega" style="width:100%">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-sm-12">
                                <b>Lugar entrega</b>
                                <input type="text" name="TxtLugarEntrega" id="TxtLugarEntrega" class="form-control form-control-sm">
                            </div>
                            <div class="col-sm-12">
                                <b>Nota Auxiliar</b>
                                <input type="text" name="txt_nota" id="txt_nota" class="form-select form-select-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
        </div>        
    </div>
</form>

<style>
    .select2-container *:focus {
        outline: solid 1px !important;
    }
  </style>
<div class="row">
    <div class="card">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <label>PRODUCTO</label>
                    <select class="form-select form-select-sm" id="producto" onchange="Articulo_Seleccionado();">
                    </select>
                </div>
                <div class="col-sm-1">
                    <label>Stock</label>
                    <input type="text" name="stock" id="stock" value="0.00" class="form-control form-control-sm text-end" readonly>
                </div>
                <div class="col-sm-1">
                    <label>Cant</label>
                    <input type="text" name="cantidad" id="cantidad" value="0.00" class="form-control form-control-sm text-end" onblur="calcular_totales()">
                </div>
                <div class="col-sm-1">
                    <label>Peso</label>
                    <input type="text" name="preciounitario" id="preciounitario" value="0.00" class="form-control form-control-sm text-end" onblur="calcular_totales()">
                </div>
                <div class="col-sm-1">
                    <label>Total</label>
                    <input type="text" name="total" id="total" value="0.00" class="form-control form-control-sm text-end" readonly onblur="validar_datos()">
                </div>
            </div>

            <div class="row"> 
                <div class="col-sm-9">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-hover" id="tbl_guia_remision">
                                <thead>
                                    <th></th>
                                    <th>CODIGO</th>
                                    <th>CANTIDAD</th>
                                    <th>PRODUCTO</th>
                                    <th>PRECIO</th>
                                    <th>TOTAL</th>
                                    <th>ID</th>
                                    <th>TOTAL_IVA</th>
                                </thead>
                                <tbody></tbody>                                                     

                            </table>                         
                        </div>                        
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="row">
                        <div class="col-sm-6">
                            <label><b>Total Tarifa 0%</b></label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="total0" id="total0" class="form-control form-control-sm red text-end" value="0.00" readonly>
                        </div>              
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label id="LabelTotTarifa"><b>Total Tarifa</b></label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="total12" id="total12" class="form-control form-control-sm red text-end" value="0.00" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label id="LabelIVA"><b>I.V.A.</b></label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="iva12" id="iva12" class="form-control form-control-sm red text-end" value="0.00" readonly>
                        </div>                  
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label><b>Total Fact. (ME)</b></label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="totalFacMe" id="totalFacMe" class="form-control form-control-sm red text-end" value="0.00" readonly>
                        </div>    
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label><b>Total Factura</b></label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="totalFac" id="totalFac" class="form-control form-control-sm red text-end" value="0.00" readonly>
                        </div>              
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label><b>EFECTIVO</b></label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="efectivo" id="efectivo" class="form-control form-control-sm red text-end" value="0.00" onkeyup="calcularSaldo();" readonly>
                        </div>              
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label><b>Cambio</b></label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="cambio" id="cambio" class="form-control form-control-sm red text-end" value="0.00">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <button type="button" title="Guardar" class="btn btn-outline-secondary" onclick="guardarFactura();">
                                <img src="../../img/png/save.png" width="25" height="30" >
                            </button>
                        </div>
                    </div>
                </div>
            </div>




        </div>        
    </div>
</div>



        <br>
      

  <div class="row">
    <input type="hidden" name="" id="txt_pag" value="0">
    <div id="tbl_pag"></div>    
  </div>
</div>

<!-- Modal cliente nuevo -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cliente Nuevo</h4>
      </div>
      <div class="modal-body">
          <iframe  id="FCliente" width="100%" height="400px" marginheight="0" frameborder="0"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>
 <!-- Fin Modal cliente nuevo-->

 <!-- buscar y reimprimir -->
<div id="reimprimir" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Comprobantes procesados</h4>
      </div>
      <div class="modal-body">
         <div class="row">
           <div class="col-sm-8">
             <b>Nombre Cliente / CI / RUC</b>
             <input type="text" name="txt_buscar" id="txt_buscar" class="form-control input-xs" placeholder="Nombre - CI - RUC " onkeyup="cargar_facs()">
           </div>
            <div class="col-sm-4">
             <b>Numero de comprobante</b>
             <input type="text" name="txt_fac" id="txt_fac" class="form-control input-xs" placeholder="Numero comprobante" onkeyup="cargar_facs()">
           </div>
         </div>
           <br>
         <div class="row">
           <div class="col-sm-12">
            <div id="re_frame" style="display: none;">
              
            </div>
           
           </div>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>
<div class="modal fade" id="cambiar_nombre" role="dialog" data-keyboard="false" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-dialog-centered modal-sm"
        style="margin-left: 300px; margin-top: 245px;">
        <div class="modal-content">
            <div class="modal-body text-center">
                <textarea class="form-control" style="resize: none;" rows="4" id="TxtDetalle" name="TxtDetalle"
                    onblur="cerrar_modal_cambio_nombre()"></textarea>
                <button style="border:0px"></button>
            </div>
        </div>
    </div>
</div>


 <!-- Fin Modal cliente nuevo-->