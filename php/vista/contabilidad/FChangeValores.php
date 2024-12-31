<style type="text/css">
	#ModalChangeValores .btn_f
  {
    background-color: #ffff00;
    color: #000080;
    border-color: #ddd;
  }

  #ModalChangeValores .input-group{
  	width: 100%;
  }
	#ModalChangeValores .input-group .input-group-addon 
    {
      background-color: #ffff00;
      color: #000080;
      border-color: #ddd;
      border-bottom-left-radius: 5px;
      border-top-left-radius:  5px;
    }
</style>
<script src="../../dist/js/FChangeValores.js"></script>
<div class="modal fade bd-example-modal" id="ModalChangeValores" tabindex="-1" role="dialog" aria-labelledby="ModalChangeValores" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#c0c000;color: #000080;">
        <h5 class="modal-title" id="Label6ModalChangeValores"><b>ELIJA LA EMPRESA A COPIAR EL CATALOGO</b></h5>
      </div>
      <div class="modal-body" style="padding-top: 15px;">
       	<div class="row">
					<div class="col-md-10">
						<form id="FormChangeValores" name="FormChangeValores">
							<div class="row">
								<div class="col-md-12 text-center" style="margin-bottom: 10px;">
	                <div class="input-group">
	                  <div class="input-xs col-md-12 btn_f text-center" >
	                    <b>CONCEPTO DEL COMPROBANTE:</b>
	                  </div>
	                  <textarea class="form-control input-xs" id="TxtConceptoChangeValores" name="TxtConceptoChangeValores" onblur="TextoValido(this)"></textarea>
								<input type="hidden" id="TxtConceptoChangeValoresOld" name="TxtConceptoChangeValoresOld">
	                </div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4 text-center">
	                <div class="input-group">
	                  <div class="input-xs col-md-12 btn_f text-center" >
	                    <b>Cheq./Dep.No.</b>
	                  </div>
	                  <input class="form-control input-xs" id="TxtDepositoChangeValores" name="TxtDepositoChangeValores"/>
	                </div>
								</div>
								<div class="col-md-4 text-center">
	                <div class="input-group">
	                  <div class="input-xs col-md-12 btn_f text-center" >
	                    <b>VALOR DEL DEBE</b>
	                  </div>
	                  <input class="form-control input-xs" id="TxtDebeChangeValores" name="TxtDebeChangeValores" onblur="TextoValido(this, true, false, 2)"/>
	                </div>
								</div>
								<div class="col-md-4 text-center">
	                <div class="input-group">
	                  <div class="input-xs col-md-12 btn_f text-center" >
	                    <b>VALOR DEL HABER</b>
	                  </div>
	                  <input class="form-control input-xs" id="TxtHaberChangeValores" name="TxtHaberChangeValores" onblur="TextoValido(this, true, false, 2)"/>
	                </div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 text-center"style="margin-top: 10px;">
	                <div class="input-group">
	                  <div class="input-xs col-md-12 btn_f text-center" >
	                    <b>DETALLE AUXILIAR</b>
	                  </div>
	                  <input class="form-control input-xs" id="TxtDetalleChangeValores" name="TxtDetalleChangeValores"/>
	                </div>
								</div>
								<input type="hidden" id="TPChangeValores" name="TPChangeValores">
								<input type="hidden" id="NumeroChangeValores" name="NumeroChangeValores">
								<input type="hidden" id="AsientoChangeValores" name="AsientoChangeValores">
								<input type="hidden" id="CtaChangeValores" name="CtaChangeValores">
							</div>
						</form>          
					</div>
					<div class="col-md-2 text-center">
						<div class="row">
							<div class="col-md-12 col-sm-6">                
								<button type="button" class="btn btn-default" onclick="Cambiar_Valores_Modal()">
									<img src="../../img/png/agregar.png"><br>
									Aceptar
								</button>
							</div>
							<div class="col-md-12 col-sm-6">
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

