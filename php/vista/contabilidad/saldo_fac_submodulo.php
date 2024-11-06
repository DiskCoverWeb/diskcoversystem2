<script src="../../dist/js/saldo_fac_submodulo.js"></script>

<div class="row">
   <div class="col-sm-12">
	  <div class="row">          
  	    <div class="col-5">
        	<a  href="./contabilidad.php?mod=contabilidad#" data-toggle="tooltip"  title="Salir de modulo" class="btn btn-default w-auto h-auto border border-2">
        		<img src="../../img/png/salire.png">
        	</a>
        	<button title="Consultar SubModulo" data-toggle="tooltip"   class="btn btn-default w-auto h-auto border border-2" onclick="consultar_datos();">
        		<img src="../../img/png/archivero1.png" >
        	</button>
        	<button title="Consultar SubModulo por Meses" data-toggle="tooltip"   class="btn btn-default w-auto h-auto border border-2" onclick="consultar_datos_x_meses();">
        		<img src="../../img/png/sub_mod_mes.png" >
        	</button>
        	<a href="" title="Presenta Resumen de costos"  data-toggle="tooltip"  class="btn btn-default w-auto h-auto border border-2">
        		<img src="../../img/png/resumen.png">
        	</a>   
        	 <a href="#" class="btn btn-default w-auto h-auto border border-2" id='descargar_pdf' data-toggle="tooltip"  title="Descargar PDF">
        		<img src="../../img/png/pdf.png">
        	</a>
        	<a href="#"  class="btn btn-default w-auto h-auto border border-2"  data-toggle="tooltip" title="Descargar excel" id='descargar_excel'>
        		<img src="../../img/png/table_excel.png">
        	</a> 
        	<input type="hidden" name="reporte_tipo" id="reporte_tipo" value="0">        	
  	    </div>
	  	<div class="col-sm-4 m-0 p-0">
	  		<div class="row">
	  			<div class="col-4">
	         		<b>Desde:</b>
	         		<br>
	         	   <input type="date" class="border border-1" style="width:125px; height: 48%" name="txt_desde" id="txt_desde" value="<?php echo date("Y-m-d");?>" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);">
	         	</div>
	            <div class="col-4">
	         	   <b>Hasta:</b>
	         	<br>
	         	   <input type="date" class="border border-1" style="width:125px; height: 48%" name="txt_hasta" id="txt_hasta" value="<?php echo date("Y-m-d");?>" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);"> 
	         	</div>             	
	         	<div class="col-3">
	         	<br>
	         		<select id="tipo_cuenta" name="tipo_cuenta" class="border border-1" onchange="cargar_cbx()">
	         			<option value="">Seleccione</option>
	         	   	<option value="C">CxC</option>
	         	   	<option value="P">CxP</option>
	         	   	<option value="I">Ingresos</option>
	         	   	<option value="G">Egresos</option>
	         	   	<option value="CC">Centro de costos</option>             	   	
	         	   </select>             		
	             </div>	  		
	  			
	  		</div>             	
	  	</div>
	  	<div class="col-sm-3 text-center ps-4">
	     	<div class="row">
	     		<div class="col-sm-12">
	     	    <label class="radio-inline" style="font-size: 0.8rem;"><input class="" type="radio" name="OpcP" value="1" id="OpcP" checked=""><b class="ps-1">Pendientes</b></label>
	  		    <label class="radio-inline" style="font-size: 0.8rem;"><input class="" type="radio" name="OpcP" value="" id="OpcC"><b class="ps-1">Canceladas</b></label>              			
	     		</div>
	     		<div class="col-sm-12">
	     		<label class="form-check-label fw-bold" style="font-size: 12px;margin: 0px;"><input type="checkbox" name="chekSubCta" id="chekSubCta"> Procesar con Detalle de SubModulo</label>
	     		</div>             		
	        </div>
	     </div>	
	  </div>
	  <div class="row pb-2 pt-2 fw-bold">	  		
	  	<div class="col-sm-4">
	  	   <label class="form-check-label"><input type="checkbox" name="CheqCta" id="CheqCta" onchange="cuenta()" value="true"> Por Cta.</label>
	  		<select class="form-control input-xs" id="select_cuenta" style="display: none;">
	  			<option value="">Seleccione cuenta</option>
	  		</select>
	 	</div>
		  <div class="col-sm-4">
		  	<label class="form-check-label"><input type="checkbox" name="CheqDet" id="CheqDet" onchange="detalle()"> Por det</label>	  	 
	  		  <select class="form-control input-xs"   id="select_detalle" style="display: none;">
	  			 <option value="">Seleccione detalle</option>
	  		  </select>
		  </div>
		  <div class="col-sm-4">
		  	<label class="form-check-label"><input type="checkbox" name="CheqIndiv" id="CheqIndiv" onchange="beneficiario()"><span id="lbl_bene"> Beneficiario</span></label> 
			   <select  class="form-control input-xs" id="select_beneficiario" style="display: none;">
		     	<option value="">Seleccione Beneficiario</option>
		       </select> 
		  </div>
	  </div>	
	</div>
</div>
	 
	  <!--seccion de panel-->
	  <div class="row">
	  <br>
	  	<input type="input" name="activo" id="activo" value="1" hidden="">
	  	<div class="col-sm-12">
	  		<ul class="nav nav-tabs pb-2">
	  		   <li class="active">
	  		   	<a data-toggle="tab" href="#home" class="h6 fw-normal p-2 pb-1" onclick="activar(this)" id="titulo_tab">SALDO DE CUENTAS POR COBRAR</a></li>
	  		   <li>
	  		   	<a data-toggle="tab" href="#menu1" class="h6 fw-normal p-2 pb-1" onclick="activar(this);consultar_datos_tempo()" id="titulo2_tab">SALDO DE CUENTAS POR COBRAR TEMPORIZADO</a></li>
	  		</ul>
	  	    <div class="tab-content" style="background-color:#E7F5FF">
	  	    	<div id="home" class="tab-pane fade in active">
	  	    	   <div class="row" >
	  	    	   	<div class="col-sm-12" id="tabla_">
	  	    	   		
	  	    	   	</div>
	  	    	   		  	    	   	
	  	    	   </div>
	  	    	 </div>
	  	    	 <div id="menu1" class="tab-pane fade">
	  	    	   <div class="row" >
	  	    	   	<div class="col-sm-12" id="tabla_temp">
	  	    	   		
	  	    	   	</div>
	  	    	   	  	    	   	
	  	    	   </div>
	  	    	 </div>
	  	    </div>
	  	</div>
	  </div>
	  <div class="row">
	  	<div class="">
	  		
	  	</div>
	  	<!-- <table>
	  		<tr><td width="75px"><b>Total MN</b></td><td width="75px" ></td><td width="75px"><b>Saldo MN</b></td><td width="75px" id="saldo_mn"></td></tr>
	  	</table>	  	 -->
	  </div>
	  <div class="row">
	  	<div class="col-sm-5">
	  		<b>total MN: </b><i id="total_mn"></i>	  		
	  	</div>
	  	<div class="col-sm-5">
	  		<b>Saldo MN: </b><i id="saldo_mn"></i>	  		
	  	</div>
	  </div>