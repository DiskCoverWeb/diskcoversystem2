<?php  //print_r( $_SESSION['SETEOS']);die();?>
<style type="text/css">
    @media only screen and (max-width: 600px) {
    body {
       .detalles_pro {
            margin-top: 225px;
        }
    }
}

/* Estilos para pantallas grandes (escritorio) */
@media only screen and (min-width: 601px) {
    body {
       .detalles_pro {
            margin-left: 300px; margin-top: 200px;
        }
    }
}
</style>

<script src="../../dist/js/facturacion/notas_credito.js"></script>
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
  			 <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo"  class="btn btn-outline-secondary">
      		<img src="../../img/png/salire.png">
      	</a>
     </div>
   </div>
</div>

<form id="form_nc">
  <div class="row mb-2">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-sm-3">
              <b>Fecha NC</b>
              <input type="date" name="MBoxFecha" id="MBoxFecha" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
          </div>
          <div class="col-sm-3">
            <input type="hidden" name="ReIngNC" id="ReIngNC" value="0">
            <b>Lineas de Nota de Credito</b>
            <select class="form-select form-select-sm" id="DCLineas" name="DCLineas" onchange="numero_autorizacion();autocoplete_clinete()" onblur="valida_cxc();">
                    <option value="">Seleccione</option>
                </select>
          </div>
          <div class="col-sm-3">
            <b>Autorizacion Nota de Credito</b>
            <input type="text" name="TextBanco" id="TextBanco" class="form-control form-control-sm" value="." readonly>
          </div>
          <div class="col-sm-1" style="padding:0px">
            <b>Serie</b>
            <input type="text" name="TextCheqNo" id="TextCheqNo" class="form-control form-control-sm" value="001001" readonly>
          </div>
          <div class="col-sm-1" style="padding: 0px;">
            <b>Comp No.</b>
            <input type="text" name="TextCompRet" id="TextCompRet" class="form-control form-control-sm" value="00000000" onblur="validar_procesar()" readonly>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-8">
              <b>Cliente</b><br> 
              <select class="form-select form-select-sm" id="DCClientes" name="DCClientes" onchange="">
                <option>Seleccione cliente</option>
              </select>
          </div>  
          <div class="col-sm-4">
            <b>Contra Cuenta a aplicar a la Nota de Credito</b>
            <select class="form-select form-select-sm" id="DCContraCta" name="DCContraCta">
                  <option value="">Seleccione cuenta</option>
                </select>
          </div>
        </div>
        <div class="row pt-2 pb-1">
          <div class="col-sm-12">
            <div class="input-group input-group-sm"> 
              <span class="input-group-text" id="basic-addon3"><b>Motivo de la Nota de credito</b></span>
                  <input type="text" name="TxtConcepto" id="TxtConcepto" class="form-control form-control-sm">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-1">
            <b>T.D.</b>
            <select class="form-select form-select-sm" id="DCTC" name="DCTC" onchange="DCSerie()">
                  <option>Seleccione cliente</option>
                </select>
          </div>
          <div class="col-sm-1" style="padding: 2px">
            <b>Serie</b>
            <select class="form-select form-select-sm" id="DCSerie" name="DCSerie" onchange="DCFactura()">
                  <option>Seleccione cliente</option>
                </select>
          </div>
          <div class="col-sm-2">
            <b>No.</b>
            <select class="form-select form-select-sm" id="DCFactura" name="DCFactura" onchange="Detalle_Factura()">
                  <option>Seleccione cliente</option>
                </select>
          </div>
          <div class="col-sm-4">
            <b>Autorizacion del documento</b>
            <input type="text" name="TxtAutorizacion" id="TxtAutorizacion" class="form-control form-control-sm">
          </div>
          <div class="col-sm-2">
            <b>Total de Factura</b>
            <input type="text" name="LblTotal" id="LblTotal" class="form-control form-control-sm" value="0.00">
          </div>
          <div class="col-sm-2">
            <b>Saldo de Factura</b>
            <input type="text" name="LblSaldo" id="LblSaldo" class="form-control form-control-sm" value="0.00">
          </div>  
        </div>
        <div class="row">
          <div class="col-sm-6">
             <div class="input-group input-group-sm"> 
                <span class="input-group-text" id="basic-addon3"><b>Bodega</b></span>
                <select class="form-select form-select-sm" id="DCBodega" name="DCBodega">
                  <option>Seleccione bodega</option>
                </select>
            </div>
          </div>
          <div class="col-sm-6">
             <div class="input-group input-group-sm"> 
                <span class="input-group-text" id="basic-addon3"><b>Marca</b></span>
                  <select class="form-select form-select-sm" id="DCMarca" name="DCMarca">
                  <option>Seleccione marca</option>
                </select>
              </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-sm-12">
            <div class="panel panel-primary" style="margin-bottom: 0px;">     
              <div class="panel-body">
                <div class="row bg-body-secondary">
                  <div class="col-sm-7">
                    <b>Producto</b> <br>
                    <select class="form-select form-select-sm" id="DCArticulo" name="DCArticulo" onchange="Articulo_Seleccionado()">
                            <option>Seleccione producto</option>
                        </select>
                  </div>
                  <div class="col-sm-1" style="padding:3px">
                    <b>Cantidad</b>
                    <input type="text" name="TextCant" id="TextCant" class="form-control form-control-sm" value="0">
                  </div>
                  <div class="col-sm-1" style="padding:3px">
                    <b>P.V.P.</b>
                    <input type="text" name="TextVUnit" id="TextVUnit" class="form-control form-control-sm" value="0.00" onblur="calcular()">
                  </div>
                  <div class="col-sm-1" style="padding:3px; display: none;">
                    
                    <input type="text" name="porc_iva" id="porc_iva" class="form-control form-control-sm" value="0.00" >          
                    <input type="text" name="TextIva" id="TextIva" class="form-control form-control-sm" value="0.00" >       
                    <input type="text" name="TextIvaTotal" id="TextIvaTotal" class="form-control form-control-sm" value="0.00" >
                  </div>
                  <div class="col-sm-1" style="padding:3px">
                    <b>DESC</b>
                    <input type="text" name="TextDesc" id="TextDesc" class="form-control form-control-sm" value="0.00" onblur="TextDesc_lost()">
                  </div>
                  <div class="col-sm-2">
                    <b>TOTAL</b>    
                    <input type="text" name="LabelVTotal" id="LabelVTotal" class="form-control form-control-sm" value="0.00">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mb-2">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-hover" id="tbl_nota_credito">
                  <thead>
                    <th></th>
                    <th>CODIGO</th>
                    <th>PRODUCTO</th>
                    <th>CANT</th>
                    <th>PVP</th>
                    <th>SUBTOTAL</th>
                    <th>TOTAL_IVA</th>
                    <th>DESCUENTO</th>
                    <th>CodBod</th>
                    <th>CodMar</th>
                    <th>Item</th>
                    <th>CodigoU</th>
                    <th>Codigo_C</th>
                    <th>Ok</th>
                    <th>COSTO</th>
                    <th>Cod_Ejec</th>
                    <th>Porc_C</th>
                    <th>Porc_IVA</th>
                    <th>Mes Mes_No</th>
                    <th>Anio</th>
                    <th>Cta_Inventario</th>
                    <th>Cta_Costo</th>
                    <th>A_No</th>
                  </thead>
                  <tbody></tbody>
                </table>      
            </div>
          </div>          
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-sm-3"></div>
    <div class="col-sm-3">
      <div class="row">
          <div class="input-group input-group-sm"> 
            <label class="col-sm-6" style="padding:0px"><b>sub total sin iva</b></label>
              <input type="text" name="TxtSinIVA" id="TxtSinIVA" class="form-control form-control-sm">
          </div>
          <div class="input-group input-group-sm"> 
            <label class="col-sm-6" style="padding:0px"><b>sub total con iva</b></label>
              <input type="text" name="TxtConIVA" id="TxtConIVA" class="form-control form-control-sm">
          </div>
          <div class="input-group input-group-sm"> 
            <label class="col-sm-6" style="padding:0px"><b>Total descuento</b></label>
            <input type="text" name="TxtDescuento" id="TxtDescuento" class="form-control form-control-sm">
          </div>
       </div>    
    </div>
    <div class="col-sm-3">
      <div class="row">
          <div class="input-group input-group-sm"> 
            <label class="col-sm-6" style="padding:0px"><b>Sub total</b></label>
             <input type="text" name="TxtSaldo" id="TxtSaldo" class="form-control form-control-sm">
           </div>       
          <div class="input-group input-group-sm"> 
            <label class="col-sm-6" style="padding:0px"><b>Total del I.V.A</b></label>
            <input type="text" name="TxtIVA" id="TxtIVA" class="form-control form-control-sm">
          </div>
          <div class="input-group input-group-sm"> 
                <label class="col-sm-6" style="padding:0px"><b>Total Nota Credito</b></label>
                <input type="text" name="LblTotalDC" id="LblTotalDC" class="form-control form-control-sm">
          </div>
      </div>    
    </div>
    <div class="col-sm-3">    
      <button type="button" class="btn btn-outline-secondary" onclick="generar_nc()">
        <img src="../../img/png/grabar.png">
        <br>
        <b>Nota de credito</b>
      </button>
    </div>
  </div>
      </div>    
    </div>  
  </div>
</form>



<div class="modal fade " id="cambiar_nombre" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog modal-dialog-centered modal-sm detalles_pro">
        <div class="modal-content">
            <div class="modal-body text-center">
                <textarea class="form-control" style="resize: none;" rows="4" id="TxtDetalle" name="TxtDetalle"
                    onblur="cerrar_modal_cambio_nombre()"></textarea>
                <button style="border:0px"></button>
            </div>
        </div>
    </div>
</div>