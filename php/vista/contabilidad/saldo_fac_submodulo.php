<script src="../../dist/js/saldo_fac_submodulo.js"></script>

<div class="overflow-auto p-1">
	<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
		<div class="breadcrumb-title pe-3">
			<?php echo $NombreModulo; ?>
		</div>
		<div class="ps-3">
			<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
				<li class="breadcrumb-item active" aria-current="page">Saldo de facturas en submodulos</li>
			</ol>
			</nav>
		</div>
	</div>


   <div class="row row-cols-auto">
	   <div class="col-12 d-flex align-items-center">
	    <div class="row row-cols-auto btn-group col-4">          
        	<a  href="./contabilidad.php?mod=contabilidad#" data-bs-toggle="tooltip"  title="Salir de modulo" class="btn btn-outline-secondary">
        		<img src="../../img/png/salire.png">
        	</a>
        	<button title="Consultar SubModulo" data-bs-toggle="tooltip"   class="btn btn-outline-secondary" onclick="consultar_datos();">
        		<img src="../../img/png/archivero1.png" >
        	</button>
        	<button title="Consultar SubModulo por Meses" data-bs-toggle="tooltip"   class="btn btn-outline-secondary" onclick="consultar_datos_x_meses();">
        		<img src="../../img/png/sub_mod_mes.png" >
        	</button>
        	<a href="" title="Presenta Resumen de costos"  data-bs-toggle="tooltip"  class="btn btn-outline-secondary">
        		<img src="../../img/png/resumen.png">
        	</a>   
        	 <a href="#" class="btn btn-outline-secondary" id='descargar_pdf' data-bs-toggle="tooltip"  title="Descargar PDF">
        		<img src="../../img/png/pdf.png">
        	</a>
        	<a href="#"  class="btn btn-outline-secondary"  data-bs-toggle="tooltip" title="Descargar excel" id='descargar_excel'>
        		<img src="../../img/png/table_excel.png">
        	</a> 
        	<input type="hidden" name="reporte_tipo" id="reporte_tipo" value="0">        	
	    </div>
	  	<div class="ps-5">
	  		<div class="row row-cols-auto">
	  			<div class="col-4">
	         		<b>Desde:</b>
	         		<br>
	         	   <input type="date" class="form-control form-control-sm" style="width:125px; height: 48%" name="txt_desde" id="txt_desde" value="<?php echo date("Y-m-d");?>" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);">
	         	</div>
	            <div class="col-4">
	         	   <b>Hasta:</b>
	         	<br>
	         	   <input type="date" class="form-control form-control-sm" style="width:125px; height: 48%" name="txt_hasta" id="txt_hasta" value="<?php echo date("Y-m-d");?>" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);"> 
	         	</div>             	
	         	<div class="col-4">
	         	<br>
	         		<select id="tipo_cuenta" name="tipo_cuenta" class="form-select form-select-sm" onchange="cargar_cbx()">
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
	  <div class="col-12 row fw-bold pt-2">
			  		
	  	<div class="col-4">
		  	<label class="form-check-label"><input type="checkbox" name="CheqCta" id="CheqCta" onchange="cuenta()" value="true"> Por Cta.</label>
	  		<select class="form-select form-select-sm" id="select_cuenta" style="display: none;">
	  			<option value="">Seleccione cuenta</option>
	  		</select>
	 	</div>
		<div class="col-4">
		      <label class="form-check-label"><input type="checkbox" name="CheqDet" id="CheqDet" onchange="detalle()"> Por det</label>	  	 
	  		  <select class="form-select form-select-sm"  id="select_detalle" style="display: none;">
	  			 <option value="">Seleccione detalle</option>
	  		  </select>
		</div>
		<div class="col-4">
		       <label class="form-check-label"><input type="checkbox" name="CheqIndiv" id="CheqIndiv" onchange="beneficiario()"><span id="lbl_bene"> Beneficiario</span></label> 
			   <select  class="form-select form-select-sm" id="select_beneficiario" style="display: none;">
		     	<option value="">Seleccione Beneficiario</option>
		       </select> 
		</div>
		<div>
		</div>
	  </div>	
	</div>
	 
	  <!--seccion de panel-->
	<div class="row pt-3">
	  <br>
	  	<input type="input" name="activo" id="activo" value="1" hidden="">
	  	<div class="col-sm-12">
	  		<ul class="nav nav-pills pb-2">
	  		    <li class="nav-item" role="presentation">
					<a data-bs-toggle="tab" href="#home" class="nav-link active" onclick="activar(this)" id="titulo_tab">
						<div class="d-flex align-items-center">
							<div class="tab-title">SALDO DE CUENTAS POR COBRAR</div>
						</div>
					</a>
				</li>
	  		    <li class="nav-item" role="presentation">
					<a data-bs-toggle="tab" href="#menu1" class="nav-link" onclick="activar(this);consultar_datos_tempo()" id="titulo2_tab">
						<div class="d-flex align-items-center">
							<div class="tab-title">SALDO DE CUENTAS POR COBRAR TEMPORIZADO</div>
						</div> 
					</a>
				</li>
	  		</ul>
	  	    <div class="tab-content">
	  	    	<div id="home" class="tab-pane fade active show">
						<div class="table-responsive">
							<table class="table text-sm w-100" id="tbl_saldo_meses">
								<thead>
									<tr>
										<th class="text-center">Cta</th>
										<th class="text-center">Beneficiario</th>
										<th class="text-center">Anio</th>
										<th class="text-center">Mes</th>
										<th class="text-center">Valor_x_Mes</th>
										<th class="text-center">Categoria</th>	
									</tr>
								</thead> 
								<tbody>
									<tr>
										<td></td> 
										<td></td> 
										<td></td> 
										<td></td> 
										<td></td> 
										<td></td> 
									</tr>
								</tbody> 
							</table>
						</div>    	   	
	  	    	</div>
	  	    	<div id="menu1" class="tab-pane fade">
						<div class="table-responsive">
							<table class="table text-sm w-100" id="tbl_saldo_temporal">
								<thead>
									<tr>
										<th class="text-center">Cuenta</th> 
										<th class="text-center">Cliente</th> 
										<th class="text-center">Fecha_Venc</th>
										<th class="text-center">Factura</th>  
										<th class="text-center">Ven 1 a 7</th> 
										<th class="text-center">Ven 8 a 30</th> 
										<th class="text-center">Ven 31 a 60</th> 
										<th class="text-center">Ven 61 a 90</th> 
										<th class="text-center">Ven 91 a 180</th> 
										<th class="text-center">Ven 181 a 360</th> 
										<th class="text-center">Ven mas de 360</th> 
									</tr>
								</thead>
								<tbody>
									<tr>
										<td></td> 
										<td></td> 
										<td></td> 
										<td></td> 
										<td></td> 
										<td></td> 
										<td></td> 
										<td></td> 
										<td></td> 
										<td></td> 
										<td></td> 
									</tr>
								</tbody> 
							</table>
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
</div>
</div>