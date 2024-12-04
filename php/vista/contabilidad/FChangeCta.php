
<div class="modal fade bd-example-modal" id="ModalChangeCa" tabindex="-1" role="dialog" aria-labelledby="ModalChangeCa" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="Label6ModalChangeCa"><b>CAMBIO DE VALORES DE LA CUENTA</b></h5>
      </div>
      <div class="modal-body" style="padding-top: 0px;">
       	<div class="row">
					<div class="col-md-9">
						<div class="row">
							<div class="col-sm-12 text-center">
								<b id="cambiar_select"></b>
							</div>
						</div>
						<select class="form-control input-sm" id="DCCuentaChangeCa">
							<option>Seleccionar Cuenta</option>
						</select> 
						<input type="hidden" name="Codigo1" id="Codigo1ChangeCta">           
						<input type="hidden" name="Asiento" id="AsientoChangeCta">           
						<input type="hidden" name="Producto" id="ProductoChangeCta">           
						<input type="hidden" name="TP" id="TPChangeCta">           
						<input type="hidden" name="Numero" id="NumeroChangeCta">           
					</div>
					<div class="col-md-3 text-center">
						<div class="row">
							<div class="col-md-12 col-sm-6 col-xs-2">                
								<button type="button" class="btn btn-default" onclick="CambiarCta()">
									<img src="../../img/png/agregar.png"><br>
									Aceptar
								</button>
							</div>
							<div class="col-md-12 col-sm-6 col-xs-2">
								<br>
								<button type="button" class="btn btn-default" title="Cerrar" data-dismiss="modal">
									<img src="../../img/png/salire.png"><br>&nbsp; &nbsp;Salir&nbsp;&nbsp;&nbsp;
								</button>
							</div>              
						</div>
					</div>
				</div>
      </div>
    </div>
  </div>
</div>

<script src="../../dist/js/FChangeValores.js"></script>
