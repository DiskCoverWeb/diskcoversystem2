<script src="/../../dist/js/mayores_sub_cuenta.js"></script>
<div class="row">
	<div class="col-sm-4">
    	<a  href="./contabilidad.php?mod=contabilidad#" data-toggle="tooltip"  title="Salir de modulo" class="btn btn-default">
    		<img src="../../img/png/salire.png">
    	</a>
    	<button title="Consultar SubModulo" data-toggle="tooltip"   class="btn btn-default" onclick="Consultar_Un_Submodulo();">
    		<img src="../../img/png/archivero1.png" >
    	</button>
    	 <a href="#" class="btn btn-default" id='descargar_pdf' data-toggle="tooltip"  title="Descargar PDF">
    		<img src="../../img/png/pdf.png">
    	</a>
    	<a href="#"  class="btn btn-default"  data-toggle="tooltip" title="Descargar excel" id='descargar_excel'>
    		<img src="../../img/png/table_excel.png">
    	</a>        	
    	<a href="" title="Presenta Resumen de costos"  data-toggle="tooltip"  class="btn btn-default">
    		<img src="../../img/png/resumen.png">
    	</a>            	
	</div>
</div>
<form id="form_filtros">
<div class="row">
	<div class="col-md-3 col-sm-2 col-xs-2">
       <div class="input-group">
	         <div class="input-group-addon input-xs">
	           <b>Desde:</b>
	         </div>
        	<input type="date" name="txt_desde" id="txt_desde" value="<?php echo date("Y-m-d");?>" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);" class="form-control input-xs">
       </div>
       <div class="input-group">
	         <div class="input-group-addon input-xs">
	           <b>Hasta:</b>
	         </div>
        	<input type="date"  name="txt_hasta" id="txt_hasta" value="<?php echo date("Y-m-d");?>" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);" class="form-control input-xs">
       </div>
    </div>
    <div class="col-sm-6">
    	<div class="input-group">
	         <div class="input-group-addon input-xs">
	          <label style="margin:-3px"><input type="checkbox" name="check_usu" id="check_usu"> Por Usuario</label>
	         </div>
        	<select class="form-control input-xs" id="DCUsuario" name="DCUsuario">
        		<option value="">Seleccione </option>
        	</select>
       	</div>
       	<div class="input-group" id="panel_agencia">
	         <div class="input-group-addon input-xs">	           
	          <label style="margin:-3px"><input type="checkbox" name="check_agencia" id="check_agencia"> Agencia</label>
	         </div>
        	<select class="form-control input-xs" id="DCAgencia" name="DCAgencia">
        		<option value="">Seleccione </option>
        	</select>
       </div>     	
    </div>
    <div class="col-sm-3">
    	<div class="panel panel-primary"  style="margin:0px">     		
    		<div class="panel-heading" style="padding: 0px 0px 0px 6px;">
				Estado
			</div> 		
    		<div class="panel-body" style="padding: 0px 6px 0px 6px;">
    			<label><input type="radio" name="rbl_estado" value="N" checked> Normal</label>
    			<label><input type="radio" name="rbl_estado" value="A"> Anulado</label>
    			<label><input type="radio" name="rbl_estado" value="T"> Todos</label>
    		</div>
    	</div>
    </div>   
</div>
<div class="row">
	<div class="col-sm-3">
		<div class="panel panel-primary" style="margin:0px">    
			<div class="panel-heading" style="padding: 0px 0px 0px 6px;">
				Sub Cuenta
			</div> 		
    		<div class="panel-body" style="padding: 0px 6px 0px 6px;">
    			<label><input type="radio" name="rbl_subcta" value="C"  onclick="FDCCtas()" checked> CxC</label>
    			<label><input type="radio" name="rbl_subcta" value="P" onclick="FDCCtas()" > CXP</label>
    			<label><input type="radio" name="rbl_subcta" value="PM" onclick="FDCCtas()" > Prima</label>
    			<label><input type="radio" name="rbl_subcta" value="I" onclick="FDCCtas()" > Ingreso</label>
    			<label><input type="radio" name="rbl_subcta" value="G" onclick="FDCCtas()" > Gastos</label>
    			<label><input type="radio" name="rbl_subcta" value="CC" onclick="FDCCtas()" > C. de C.</label>
    		</div>
    	</div>		
	</div>
	<div class="col-sm-5">
		<select class="form-control input-xs" id="DCCtas" name="DCCtas" onchange="FDLCtas(this.value)">
   		<option value="">Seleccione</option>
   	</select>      
   	<select class="form-control input-xs" id="DLCtas" name="DLCtas">
   		<option value="">Seleccione</option>
   	</select>        	 
	</div>
	<div class="col-sm-4">
		<div class="panel panel-primary" style="margin:0px"> 
    		<div class="panel-body" style="padding: 0px 6px 0px 6px;">
    			<label><input type="radio" name="rbl_opc" id="rbl_opc" value="1" checked> Una sola cuenta</label>
    			<label><input type="radio" name="rbl_opc" id="rbl_opcT" value="0"> Todas las cuentas</label>
    		</div>
    	</div><br>
    	<div class="input-group">
	         <div class="input-group-addon input-xs">
	           <b>Saldo Anterior:</b>
	         </div>
        	<input type="text" class="form-control input-xs" value="0.00" name="txt_saldo_ant" id="txt_saldo_ant">
       </div>		
	</div>
</div>
</form>
<br>
<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-primary">
			<div class="panel-heading text-center"  style="padding: 0px 0px 0px 6px;">
				SUB MAYOR
			</div>
			<div class="panel-body" id="tbl_body">
				<!-- <div class="col-sm-12" id="tbl_body">
					
				</div>	 -->			
			</div>			
		</div>		
	</div>
	<div class="col-sm-12">
				<div class="row">
					<div class="col-sm-3">
						<div class="input-group">
			         <div class="input-group-addon input-sm">
			           <b>Debito:</b>
			         </div>
		        	<input type="" name="txt_debito" id="txt_debito" value="0">
		        </div>						
					</div>
					<div class="col-sm-3">
						<div class="input-group">
			         <div class="input-group-addon input-sm">
			           <b>Credito:</b>
			         </div>
		        	<input type="" name="txt_credito" id="txt_credito" value="0">
		        </div>						
					</div>
					<div class="col-sm-3">
						<div class="input-group">
			         <div class="input-group-addon input-sm">
			           <b>Saldo Actual:</b>
			         </div>
		        	<input type="" name="txt_saldo_actual" id="txt_saldo_actual" value="0">
		        </div>						
					</div>
				</div>
			</div>	
</div>
