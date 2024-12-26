<?php 

?>
<script type="text/javascript">
</script>
<script type="text/javascript">
  $(document).ready(function()
  {
       
  });
 
  </script>
<div class="row">
    <div class="col-sm-12">
            <div class="col-sm-8">
                <div class="box box-info">
                    <div class="box-header" style="padding:0px">
                        <h3 class="box-title">CUENTA POR COBRAR</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">                            
                            <div class="col-sm-12">
                                <select class="form-control input-sm" id="DCRetIBienes">
                                    <option>Seleccione Tipo Retencion</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 text-center">
                <button class="btn btn-default"> <img src="../../img/png/grabar.png"  onclick="validar_formulario();"><br> Guardar</button>
                <button class="btn btn-default"  data-dismiss="modal" onclick="limpiar_retencaion()"> <img src="../../img/png/bloqueo.png" ><br> Cancelar</button>
            </div>        
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
    		<div class="col-sm-8">
    			<b>RAZON SOCIAL O NOMBRE DEL PROVEEDOR</b>
                <select class="form-control input-sm" id="DCProveedor">
                  <option value="">No seleccionado</option>
                </select>
            </div>
            <div class="col-sm-1"><br>
                <input type="text" class="form-control input-sm" name="" id="LblTD" style="color: red" readonly="">
            </div>
            <div class="col-sm-3"><br>                
                <input type="text" class="form-control input-sm" name="" id="LblNumIdent" readonly="">
            </div>
    </div>
</div>    
<div class="row">
    <div class="col-sm-12">
    	<div class="col-sm-4">
    		<b> EXPORTACION DE</b>
    		<select class="form-control input-sm">
    			<option>Seleccione</option>
    		</select>
    	</div>
    	<div class="col-sm-4">
    		<b> TIPO COMPROBANTE</b>
    		<select class="form-control input-sm">
    			<option>Seleccione</option>
    		</select>
    	</div>
    	<div class="col-sm-4">
    		<b>FECHA</b>
    		<input type="date" name="txt_fecha" id="txt_fecha" class="form-control input-sm">
    	</div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12"><br>
    		<div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title">Refrendo</h3>
                </div>
                <div class="box-body">
                	<div class="col-sm-2" style="padding-left: 0px;">
                		<b>Distrito</b>
                		<select class="form-control input-sm">
                			<option value="">seleccione</option>
                		</select>
                	</div>
                	<div class="col-sm-1" style="padding-left: 0px;">
                		<b>Año</b>
                		<input type="text" name="" class="form-control input-sm">
                	</div>
                	<div class="col-sm-2" style="padding-left: 0px;">
                		<b>Regimen</b>
                		<select class="form-control input-sm">
                			<option value="">seleccione</option>
                		</select>
                	</div>
                	<div class="col-sm-1" style="padding-left: 0px;">
                		<b>Correla</b>                		
                		<input type="text" name="" class="form-control input-sm">
                	</div>
                	<div class="col-sm-1" style="padding-left: 0px;">
                		<b>Verific</b>                		
                		<input type="text" name="" class="form-control input-sm">
                	</div>
                	<div class="col-sm-3" style="padding-left: 0px;">
                		<b>NUMERO DE COMPROBANTE</b>
                		<input type="text" name="" class="form-control input-sm">
                	</div>
                	<div class="col-sm-1" style="padding-left: 0px;">
                		<b>F.O.B</b>
                		<input type="text" name="" class="form-control input-sm">
                	</div>
                	<div class="col-sm-1" style="padding-left: 0px; padding-right: 0px;">
                		<b>Devolu. IVA</b>
                		<label><input type="radio" name="rbl_devolucion" id="rbl_devolucion_s" value="S">SI</label>
                		<label><input type="radio" name="rbl_devolucion" id="rbl_devolucion_n" value="N" checked="">NO</label>
                		
                	</div>
                </div>
            </div>    		
    </div>               
</div>
<div class="row">
	<div class="col-sm-6">
		<b>Factura de Exportaciones</b>
		<div class="col-sm-12">
			<b>Tipo de</b> 
			<select class="form-control input-sm">
				<option value="">Selecione</option>
			</select>			
		</div>
		<div class="col-sm-8">
			<div class="col-sm-12 text-center">
			<b>No. COMPROBANTE MODIF.</b></div>
			<div class="col-sm-4" style="padding-left: 0px;">
				<input type="text" name="" class="form-control input-sm">
			</div>
			<div class="col-sm-4" style="padding-left: 0px;">
				<input type="text" name="" class="form-control input-sm">
			</div>
			<div class="col-sm-4" style="padding-left: 0px;">
				<input type="text" name="" class="form-control input-sm">
			</div>
		</div>		
		<div class="col-sm-4">
			<b> No. Autorización</b>
			<input type="text" name="" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-6"><br><br>
		<div class="col-sm-3"  style="padding-left: 0px;">
			<b> FOB COMPRO.</b>
			<input type="text" name="" class="form-control input-sm">
		</div>
		<div class="col-sm-5"  style="padding-left: 0px;">
			<b>EMISION</b>
			<input type="date" name="" class="form-control input-sm">
		</div>
		<div class="col-sm-4"  style="padding-left: 0px; padding-right: 0px;">
			<b>REGISTRO</b>
			<input type="date" name="" class="form-control input-sm">
		</div>

	</div>
</div>
     