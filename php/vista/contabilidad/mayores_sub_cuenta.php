<script src="../../dist/js/contabilidad/mayores_sub_cuenta.js"></script>
	<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
		<div class="breadcrumb-title pe-3"><?php echo $NombreModulo; ?>
		</div>
		<div class="ps-3">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0"  id="ruta_menu">
			<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
			</li>
			</ol>
		</nav>
		</div>          
	</div>
	<div class="row row-cols-auto">
		<div class="btn-group">
			<a href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>" data-bs-toggle="tooltip"  title="Salir de modulo" class="btn btn-outline-secondary btn-sm">
				<img src="../../img/png/salire.png">
			</a>
			<button title="Consultar SubModulo" data-bs-toggle="tooltip"   class="btn btn-outline-secondary btn-sm" onclick="Consultar_Un_Submodulo();">
				<img src="../../img/png/archivero1.png" >
			</button>
			<a href="#" class="btn btn-outline-secondary btn-sm" id='descargar_pdf' data-bs-toggle="tooltip"  title="Descargar PDF">
				<img src="../../img/png/pdf.png">
			</a>
			<a href="#"  class="btn btn-outline-secondary btn-sm"  data-bs-toggle="tooltip" title="Descargar excel" id='descargar_excel'>
				<img src="../../img/png/table_excel.png">
			</a>        	
			<a href="" title="Presenta Resumen de costos"  data-bs-toggle="tooltip"  class="btn btn-outline-secondary btn-sm">
				<img src="../../img/png/resumen.png">
			</a>            	
		</div>
	</div>
	<form id="form_filtros">
	<div class="row">
		<div class="col-md-3 col-sm-2">
		<div class="input-group">
				<div class="input-group-addon col-3">
				<b>Desde:</b>
				</div>
				<input type="date" name="txt_desde" id="txt_desde" class="col-6 form-control form-control-sm" value="<?php echo date("Y-m-d");?>" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);" class="form-control">
		</div>
		<div class="input-group">
				<div class="input-group-addon col-3">
				<b>Hasta:</b>
				</div>
				<input type="date"  name="txt_hasta" id="txt_hasta" class="col-6 form-control form-control-sm" value="<?php echo date("Y-m-d");?>" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);" class="form-control">
		</div>
		</div>
		<div class="col-sm-6">
			<div class="input-group">
				<div class="input-group-addon col-3 d-flex align-items-center">
				<label><input type="checkbox" name="check_usu" id="check_usu"> Por Usuario</label>
				</div>
				<select class="form-select form-select-sm col-6 border" id="DCUsuario" name="DCUsuario">
					<option value="">Seleccione </option>
				</select>
			</div>
			<div class="input-group" id="panel_agencia">
				<div class="input-group-addon col-3 d-flex align-items-center">	           
					<label><input type="checkbox" name="check_agencia" id="check_agencia"> Agencia</label>
				</div>
				<select class="form-select form-select-sm" id="DCAgencia" name="DCAgencia">
					<option value="">Seleccione </option>
				</select>
			</div>     	
		</div>
		<div class="col-sm-3">
			<div class="panel panel-primary"  style="margin:0px">     		
				<div class="panel-heading bg-primary border border-primary rounded-top text-white">
					Estado
				</div> 		
				<div class="panel-body border border-primary rounded-bottom">
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
				<div class="panel-heading border border-primary bg-primary rounded-top text-white">
					Sub Cuenta
				</div> 		
				<div class="panel-body border border-primary rounded-bottom">
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
			<select class="form-select form-select-sm" id="DCCtas" name="DCCtas" onchange="FDLCtas(this.value)">
			<option value="">Seleccione</option>
		</select>      
		<select class="form-select form-select-sm" id="DLCtas" name="DLCtas">
			<option value="">Seleccione</option>
		</select>        	 
		</div>
		<div class="col-sm-4">
			<div class="panel panel-primary border border-primary rounded" style="margin:0px"> 
				<div class="panel-body">
					<label><input type="radio" name="rbl_opc" id="rbl_opc" value="1" checked> Una sola cuenta</label>
					<label><input type="radio" name="rbl_opc" id="rbl_opcT" value="0"> Todas las cuentas</label>
				</div>
			</div><br>
			<div class="input-group">
				<div class="input-group-addon d-flex align-items-center" style="font-size: 0.8rem">
				<b>Saldo Anterior:</b>
				</div>
				<input type="text" class="form-control form-control-sm border rounded" value="0.00" name="txt_saldo_ant" id="txt_saldo_ant">
		</div>		
		</div>
	</div>
	</form>
	<br>
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-primary">
				<div class="panel-heading text-center border border-primary bg-primary text-white rounded-top">
					SUB MAYOR
				</div>
				<div class="panel-body border border-primary rounded-bottom">
					<div class="table-responsive" style="max-height: 400px;">
						<table class="table text-sm w-100" id="tbl_body">
							<thead>
								<tr>
									<th class="text-center">Cta</th>
									<th class="text-center">Fecha</th>
									<th class="text-center">TP</th>
									<th class="text-center">Numero</th>
									<th class="text-center">Cliente</th>
									<th class="text-center">Concepto</th>
									<th class="text-center">Debitos</th>
									<th class="text-center">Creditos</th>
									<th class="text-center">Saldo_MN</th>
									<th class="text-center">Factura</th>
									<th class="text-center">Parcial_ME</th>
									<th class="text-center">Detalle_SubCta</th>
									<th class="text-center">Fecha_V</th>
									<th class="text-center">Codigo</th>
									<th class="text-center">Item</th>
								</tr> 
							</thead> 
						</table>
					</div>
				</div>			
			</div>		
		</div>
		<div class="col-sm-12">
					<div class="row row-cols-auto">
						<div class="">
							<div class="input-group">
						<div class="input-group-addon form-control form-control-sm d-flex align-items-center">
						<b style="font-size: 0.75rem">Debito:</b>
						</div>
						<input type="" class="form-control form-control-sm" name="txt_debito" id="txt_debito" value="0">
					</div>						
						</div>
						<div class="">
							<div class="input-group">
						<div class="input-group-addon form-control form-control-sm d-flex align-items-center">
						<b style="font-size: 0.75rem">Credito:</b>
						</div>
						<input type="" class="form-control form-control-sm" name="txt_credito" id="txt_credito" value="0">
					</div>						
						</div>
						<div class="">
							<div class="input-group">
						<div class="input-group-addon form-control form-control-sm d-flex align-items-center">
						<b style="font-size: 0.75rem">Saldo Actual:</b>
						</div>
						<input class="form-control form-control-sm"type="" name="txt_saldo_actual" id="txt_saldo_actual" value="0">
					</div>						
						</div>
					</div>
				</div>	
	</div>