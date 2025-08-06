<!--
    AUTOR DE RUTINA : Javier farinango
    MODIFICADO POR : Javier Farinango
    FECHA CREACION : 16/02/2024
    FECHA MODIFICACION :10/02/2025
    DESCIPCION : Interfaz de modulo Gestion Social/Registro Beneficiario
 -->
<script src="../../dist/js/inventario/reporte_constructora_detalle.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		contratistas()
		orden();
		meses();
		Calcularsemanas()
  	});	
</script>
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
    <div class="col-sm-6">
        <div class="btn-group" role="group" aria-label="Basic example">
            <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);	print_r($ruta[0]); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
                <img src="../../img/png/salire.png">
            </a>
            <button type="button" class="btn btn-outline-secondary" id="imprimir_pdf" title="Descargar PDF" onclick="imprimir_pdf()">
	            <img src="../../img/png/pdf.png">
	          </button>      
            <button type="button" class="btn btn-outline-secondary" id="imprimir_excel" title="Descargar Excel" onclick="imprimir_excel()">
            <img src="../../img/png/table_excel.png">
          </button>  
        </div>
    </div>
</div>
<div class="row mb-2">
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-sm-4">
					<b>Contratista</b>
					<div class="d-flex align-items-center">
						<select class="form-control" id="ddl_contratista" onchange="cargar_datos();orden()">
							<option value="">Selecciones</option>
						</select>
						<span class="input-group-btn">
								<button class="btn btn-danger btn-flat p-0" style="height: 22pt;padding-top: 3px;" onclick="limpiar_contra()"><i class="bx bx-x me-0"></i></button>
						</span>
					</div>
				</div>
				<div class="col-sm-3">
					<b>Orden</b>
					<div class="d-flex align-items-center">
						<select class="form-select form-select-sm" id="ddl_orden">
							<option value="">Selecciones</option>
						</select>
								<button class="btn btn-danger btn-flat p-0" style="height: 22pt;padding-top: 3px;" onclick="limpiar_orden()"><i class="bx bx-x me-0"></i></button>
					</div>
				</div>
				<div class="col-sm-2">
					<b>Meses</b>
					<div class="input-group">
						<select class="form-select form-select-sm" id="ddl_meses" onchange="Calcularsemanas();">
							<option value="">Selecciones</option>
						</select>
								<button class="btn btn-danger btn-flat p-0" onclick="$('#ddl_meses').val('');Calcularsemanas()"><i class="bx bx-x me-0"></i></button>
					</div>
				</div>
				<div class="col-sm-2">
					<b>Semana</b>
					<div class="input-group">
						<select class="form-select form-select-sm" id="ddl_semanas">
							<option value="">Selecciones</option>
						</select>
									<button class="btn btn-danger btn-flat p-0" onclick="$('#ddl_semanas').val('')"><i class="bx bx-x me-0"></i></button>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="row">
						<div class="col-sm-6">
							Desde
							<div class="input-group">
								<input type="date" name="txt_fecha" id="txt_fecha" class="form-control form-control-sm">
									<button class="btn btn-danger p-0" onclick="$('#txt_fecha').val('')"><i class="bx bx-x me-0"></i></button>
							</div>
						</div>
						<div class="col-sm-6">
								Hasta
							<div class="input-group">
								<input type="date" name="txt_fecha" id="txt_fecha_hasta" class="form-control form-control-sm">
								<button class="btn btn-danger p-0" onclick="$('#txt_fecha_hasta').val('')"><i class="bx bx-x me-0"></i></button>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-8 text-end">
					<br>
					<button class="btn btn-primary btn-sm" onclick="cargar_datos()"><i class="fa fa-search"></i>Buscar</button>		
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
					<table class="table table-sm" id="tbl_data">
						<thead>
							<th>#ORDEN</th>
							<th>SOLICITANTE</th>
							<th>FECHA SOLICITUD</th>
							<th>VALOR REF</th>
							<th>VALOR COMPRA</th>
							<th>AHORRO</th>
							<tbody id="tbl_body">
								
							</tbody>

						</thead>
					</table>		
				</div>
				<div class="col-sm-12" id="pnl_tablas">
						
				</div>	
			</div>

		</div>		
	</div>	
</div>




  <div id="myModal_detalle" class="modal fade myModalNuevoCliente" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Detalle de orden</h4>
            </div>
            <div class="modal-body">
            	<div class="row">
            		<div class="col-sm-12">
            			<div class="card">
	            			<div class="card-body">
	            				<div class="row">
		            				<div class="col-sm-12">
		            					<b># COMPROBANTE: </b><span id="lbl_comprobante"></span>
		            				</div> 
		            				<div class="col-sm-12">
		            					<b>Precio Referencial Total:</b><span id="lbl_referencial"></span>
		            				</div>
		            				<div class="col-sm-12">
		            					<b>Precio compra: </b><span id="lbl_compra"></span>
		            				</div>
		            				<div class="col-sm-12">
		            					<b>ahorro: </b>	<span id="lbl_ahorro"></span>
		            				</div>           
		            				<div class="col-sm-12">
		            					<b>Proveedor:</b><span id="lbl_proveedor"></span>
		            				</div>				
		            			</div>
	            			</div>            			
	            		</div>            			
            		</div>
            		 
            		<div class="col-sm-12">
            			<div class="card">
            				<div class="card-body">
            					<div class="table-responsive">
				            			<table class="table table-sm" id="tbl_detalle">
				            				<thead>
				            					<th>No</th>
				            					<th>FAMILIA</th>
				            					<th>CODIGO</th>
				            					<th>ITEM</th>
				            					<th>MARCAS</th>
				            					<th>CANT</th>
				            					<th>P. UNI</th>
				            					<th>PRECIO REFERENCIAL</th>
				            					<th>P. UNI</th>
				            					<th>PREVIO COMPRA</th>
				            					<th>AHO/UNI</th>
				            				</thead>
				            				<tbody id="tbl_detalle_body">
				            				</tbody>            				
				            			</table>      
		            			</div>      	
            					
            				</div>            				
            			</div>
            					
            		</div>          		
            	</div>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
  </div>