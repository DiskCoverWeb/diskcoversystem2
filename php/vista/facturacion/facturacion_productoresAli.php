<!--
	AUTOR DE RUTINA	: Teddy Moreira
	FECHA CREACION : 19/06/2024
	FECHA MODIFICACION : 16/01/2025
	DESCIPCION : Interfaz de modulo Facturacion/Facturas Distribucion
-->
<?php date_default_timezone_set('America/Guayaquil');  //print_r($_SESSION);die();//print_r($_SESSION['INGRESO']);die();
$TC = 'FA';
if (isset ($_GET['tipo'])) {
	$TC = $_GET['tipo'];
}

// print_r($_SESSION['INGRESO']);
?>

<script src="../../dist/js/facturacion/facturas_productoresAli.js"></script>
	
</style>
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
<input type="hidden" name="hiddenTC" id="hiddenTC" class="form-control form-control-sm" value="<?php echo $TC; ?>">
<div id="interfaz_facturacion">
	<div class="interfaz_botones">
		<input type="hidden" name="CodDoc" id="CodDoc" class="form-control form-control-sm" value="00">
		<div class="row row-cols-auto gx-3 pb-2 d-flex align-items-center ps-2">
			<div class="row row-cols-auto btn-group">
				<a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
					print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
					<img src="../../img/png/salire.png">
				</a>
				<!--<a title="BOUCHE" class="btn btn-outline-secondary" onclick="$('#modalBoucher').modal('show');">
					<img src="../../img/png/adjuntar-archivo.png" height="32px">
				</a>-->
				<button title="IMPRIMIR NOTA DE DONACIÓN" id="imprimir_nd" class="btn btn-outline-secondary" onclick="imprimirNotaDonacion()" disabled>
					<img src="../../img/png/paper.png" height="32px">
				</button>
				<button title="FACTURAR" class="btn btn-outline-secondary" onclick="generar()">
					<img src="../../img/png/facturar.png" height="32px">
				</button>
			
			</div>
			<div class="row row-cols-auto col-10 d-flex justify-content-end">
				<div class="col-auto" style="display:none">
					<div class="input-group input-group-sm">
						<label for="DCLinea" class="input-group-text" id="title"><b></b></label>
						<input type="hidden" id="Autorizacion">
						<input type="hidden" id="Cta_CxP">
						<input type="hidden" id="CodigoL">
						<select class="form-select form-select-sm" name="DCLinea" id="DCLinea" tabindex="1"
							onchange="numeroFactura(); tipo_documento();"></select>
					</div>
				</div>
				<div class="col-auto">
					<div class="input-group input-group-sm">
						<label for="MBFecha" class="input-group-text"><b>Fecha</b></label>
						<input type="date" name="MBFecha" id="MBFecha" class="form-control form-control-sm"
							value="<?php echo date('Y-m-d'); ?>" onblur="DCPorcenIvaFD();">
					</div>
				</div>
				<div class="col-auto">
					<div class="input-group input-group-sm">
						<label for="HoraDespacho" class="input-group-text"><b>Hora de despacho:</b></label>
						<input type="time" class="form-control form-control-sm" id="HoraDespacho" placeholder="HH:mm" value="<?php echo date('H:i');?>">
					</div>
				</div>
			</div>
		</div>
		<div id="re_frame" style="display:none;"></div>
	</div>
	<div id="interfaz_campos">
		<div class="row pb-2">
			<div class="row col-sm-9">
				<div class="col-sm-4 pb-2">
					<div class="input-group input-group-sm">
						<label for="DCTipoFact2" class="input-group-text"><b>Tipo de Facturacion</b></label>
						<!-- <select class="form-select form-select-sm" name="DCTipoFact2" id="DCTipoFact2" onblur="tipo_facturacion(this.value)">

						</select> -->
						<input type="text" id="DCTipoFact2" class="form-control form-control-sm" value="NPA" disabled>
					</div>
				</div>
				<div class="col-sm-8 pb-2">
					<div class="input-group input-group-sm">
						<label for="TextFacturaNo" class="input-group-text"><b id="Label1">FACTURA No.</b></label>
						<span class="input-group-text" id="LblSerie"><b></b></span>
						<input type="" class="form-control form-control-sm" id="TextFacturaNo" name="TextFacturaNo" readonly>
					</div>
				</div>
				<div class="col-sm-8 pb-2">
					<div class="input-group input-group-sm">
						<label for="DCCliente" class="input-group-text"><b>Nombre del cliente</b></label>
						<select class="form-select form-select-sm" id="DCCliente" name="DCCliente" onchange="pedido_seleccionado()">
							<option value="">Seleccione Cliente</option>
						</select>
						<button type="button" class="btn btn-danger btn-sm" style="font-size: 8pt;" onclick="anular_picking()"
							title="Anular picking"><span class="fa fa-times" style="font-size: 8pt;"></span></button>
					</div>
					<!-- <div class="input-group" id="ddl" style="width:100%">
						<select class="form-control" id="DCCliente" name="DCCliente" onchange="select()">
							<option value="">Seleccione Cliente</option>
						</select>
						<span class="input-group-btn">
							<button type="button" class="btn btn-success btn-xs btn-flat" onclick="addCliente()"
								title="Nuevo cliente"><span class="fa fa-user-plus"></span></button>
						</span>
					</div> -->
					<input type="hidden" name="codigoCliente" id="codigoCliente" class="form-control input-xs">
					<input type="hidden" name="LblT" id="LblT" class="form-control input-xs">
					<input type="hidden" name="txt_fecha_ing" id="txt_fecha_ing" class="form-control input-xs">
				</div>
				<div class="col-sm-4 ps-0 pb-2">
					<div class="input-group input-group-sm">
						<label for="LblRUC" class="input-group-text"><b>CI/RUC/PAS</b></label>
						<input type="" name="LblRUC" id="LblRUC" class="form-control form-control-sm" readonly>
					</div>
				</div>
				<div class="col-sm-8 pb-2">
					<div class="input-group input-group-sm">
						<label for="Lblemail" class="input-group-text"><b>Correo Electrónico</b></label>
						<input type="email" name="Lblemail" id="Lblemail" class="form-control form-control-sm">
					</div>
				</div>
				
				<div class="col-sm-4 ps-0 pb-2">
					<div class="input-group input-group-sm">
						<label for="saldoP" class="input-group-text"><b>Saldo Pendiente</b></label>
						<input name="saldoP" id="saldoP" class="form-control form-control-sm text-end" readonly value="0.00">
					</div>
				</div>
				<div class="col-sm-12">
					<div class="input-group input-group-sm">
						<label for="DCDireccion" class="input-group-text"><b>Dirección</b></label>
						<input type="" id="DCDireccion" name="DCDireccion"  class="form-control form-control-sm">
					</div>
				</div>
				<!-- <div class="row">
				</div>
				<div class="row">
				</div>
				<div class="row">
				</div> -->
			</div>
			<div class="col-sm-3 p-2">
				<div class="card">
					<div class="card-body">
						<div class="d-flex flex-column justify-content-center">
							<div class="row">
								<div class="col-sm-7 text-end">
									<b>Hora de atención:</b>
								</div>
								<div class="col-sm-5">
									<input type="time" class="form-control form-control-sm" id="HoraAtencion" name="HoraAtencion" onchange="compararHoras()">
								</div>
							</div>
							<div class="row">
								<div class="col-sm-7 text-end">
									<b>Hora de llegada:</b>
								</div>
								<div class="col-sm-5">
									<input type="time" class="form-control form-control-sm" id="HoraLlegada" name="HoraLlegada" onchange="compararHoras()">
								</div>
							</div>
							<div class="row">
								<div class="col-sm-7 text-end">
									<b><img src="../../img/png/hora_estado.png" height="40px" alt="">Estado:</b>
								</div>
								<div class="col-sm-5">
									<input type="text" class="form-control form-control-sm" id="HorarioEstado" name="HorarioEstado" disabled>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="input-group input-group-sm">
						<label for="txtRecalcular" class="input-group-text"><b>Valor a Recalcular</b></label>
						<input type="text" class="form-control form-control-sm" name="txtRecalcular" id="txtRecalcular" value="" onchange="recalcularLineaFact()" disabled>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-sm-12">
		<div class="row text-center" style="padding-bottom:10px;height:250px;overflow-y:auto;">
			<div class="col-sm-12" id="tbl_DGAsientoF">
				<table class="table-sm table-hover table bg-light">
					<thead class="text-center bg-primary text-white">
						<tr>
							<th>Código</th>
							<th>Usuario</th>
							<th>Producto</th>
							<th>Cantidad (<span id="tablaProdCU">.</span>)</th> <!-- TODO: (Kg) dinámico -->
							<th>Costo unitario P.V.P</th>
							<th>Costo total</th>
							<th>Recalcular</th>
							<th>Cheking</th>
						</tr>
					</thead>
					<tbody id="cuerpoTablaDistri"></tbody>
					
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-8"></div>
			<div class="col-sm-4 text-end">
				<button class="btn btn-outline-primary border border-1 btn-sm" onclick="$('#modalInfoFactura').modal('show')">Seleccionar métodos de pago</button>
				<div class="row">
					<div class="col-sm-6">
						<br>
						<b>Total Factura</b>
					</div>
					<div class="col-sm-6">
						<input type="text" name="LabelTotal" id="LabelTotal2" class="form-control form-control-sm text-danger"
							value="0.00" readonly>
					</div>					
				</div>				
			</div>			
		</div>
	</div>
	
</div>

<div id="modalEvaluacionFundaciones" class="modal fade">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title text-white">Evaluación Fundaciones</h4>
				<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body" style="overflow-y: auto; max-height: 300px; margin:5px">
				<div style="overflow-x: auto; margin-bottom:20px">
					<table class="table-sm table-hover table bg-light" id="tablaEvalFundaciones" style="width:fit-content;margin:0 auto;">
						<thead class="text-center bg-primary text-white">
							<tr>
								<th>ITEM</th>
								<th>BUENO</th>
								<th>MALO</th>
							</tr>
						</thead>
						<tbody id="cuerpoTablaEFund">
							
						</tbody>
					</table>
				</div>
				<div class="row">
					<div class="col-sm-3">
						<b>COMENTARIO:</b>
					</div>
					<div class="col-sm-9">
						<textarea class="form-control" name="comentario_eval" id="comentario_eval" style="height:80px;"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="btnAceptarEvaluacion" data-bs-dismiss="modal">Aceptar</button>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>

<div id="modalGavetasInfo" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title text-white">Gavetas</h4>
				<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body" style="overflow-y: auto; height: fit-content; margin:5px">
				<div style="overflow-x:auto;">
					<table class="table-sm table-hover table bg-light" id="tablaGavetas">
						<thead class="text-center bg-primary text-white">
							<tr>
								<th>GAVETAS</th>
								<th>ENTREGADAS</th>
								<th>DEVUELTAS</th>
								<th>PENDIENTES</th>
							</tr>
						</thead>
						<tbody id="cuerpoTablaGavetas">
							
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-bs-dismiss="modal" id="btnAceptarInfoGavetas">Aceptar</button>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="resetInputsGavetas()">Cancelar</button>
			</div>
		</div>
	</div>
</div>

<div id="modalInfoFactura" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title text-white">Información Facturar</h4>
				<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body" style="overflow-y: auto; height: fit-content; margin:5px">

				<div class="row col-sm-12 justify-content-center">
					<div class="row">
						<div class="col-sm-5">
							<b>Cuenta x Cobrar</b>
						</div>
						<div class="col-sm-7">
							<select class="form-select form-select-sm" id="DCLineas" name="DCLineas"
								>
								<option value="">Seleccione</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-5">
							<b>I.V.A %</b>
						</div>
						<div class="col-sm-7">
							<select class="form-select form-select-sm" style="width:100%" name="DCPorcenIVA" id="DCPorcenIVA" onblur="cambiar_iva(this)">

							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-5">
							<b>Total Tarifa 0%</b>
						</div>
						<div class="col-sm-7">
							<input type="text" name="LabelSubTotal" id="LabelSubTotal" class="form-control form-control-sm text-end text-danger"
								value="0.00" readonly onblur="TarifaLostFocus();">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-5">
							<b>Total Tarifa 12%</b>
						</div>
						<div class="col-sm-7">
							<input type="text" name="LabelConIVA" id="LabelConIVA" class="form-control form-control-sm text-end text-danger"
								value="0.00" readonly>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-5">
							<b id="Label3">I.V.A</b>
						</div>
						<div class="col-sm-7">
							<input type="text" name="LabelIVA" id="LabelIVA" class="form-control form-control-sm text-end text-danger" value="0.00"
							 readonly>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-5">
							<b>Total Factura</b>
						</div>
						<div class="col-sm-7">
							<input type="text" name="LabelTotal" id="LabelTotal" class="form-control form-control-sm text-end text-danger"
								value="0.00" readonly>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-5">
							<b>Total Fact (ME)</b>
						</div>
						<div class="col-sm-7">
							<input type="text" name="LabelTotalME" id="LabelTotalME" class="form-control form-control-sm text-end text-danger"
								value="0.00" readonly>
						</div>
					</div>
					<div class="row row-cols-auto d-flex justify-content-center align-items-center gap-4 py-3">
						<button class="col-auto btn btn-primary btn-block" id="btnToggleInfoEfectivo" stateval="1" onclick="toggleInfoEfectivo()">EFECTIVO</button>
						<button class="col-auto btn btn-light border border-1 btn-block" id="btnToggleInfoBanco" stateval="0" onclick="toggleInfoBanco()">BANCO</button>
						
					</div>
					<div id="campos_fact_efectivo" style="display:block;">
						<div class="row">
							<div class="col-sm-5">
								<b>EFECTIVO</b>
							</div>
							<div class="col-sm-7">
								<input type="text" name="TxtEfectivo" id="TxtEfectivo" class="form-control form-control-sm text-end"
									value="0.00" onblur="calcular_pago()">
							</div>
						</div>
					</div>
					<div id="campos_fact_banco" style="display:none;">
						<div class="row">
							<div class="col-sm-5">
								<b>CUENTA DEL BANCO</b>
								
							</div>
							<div class="col-sm-7">
								<select class="form-select form-select-sm select2" id="DCBanco" name="DCBanco">
									<option value="">Seleccione Banco</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-5">
								<b>Documento</b>
							</div>
							<div class="col-sm-7">
								<input type="text" name="TextCheqNo" id="TextCheqNo" class="form-control form-control-sm" value=".">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<b>NOMBRE DEL BANCO</b>
								<input type="text" name="TextBanco" id="TextBanco" class="form-control form-control-sm" value=".">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-5">
								<b>VALOR BANCO</b>
							</div>
							<div class="col-sm-7">
								<input type="text" name="TextCheque" id="TextCheque" class="form-control form-control-sm text-end"
									value="0.00" onblur="calcular_pago()">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-5">
							<b>CAMBIO</b>
						</div>
						<div class="col-sm-7">
							<input type="text" name="LblCambio" id="LblCambio" class="form-control form-control-sm text-end"
								style="color: red;" value="0.00" readonly>
						</div>
					</div>
					<div class="row" id="bouche_banco_input" style="margin:10px 0;display:none;">
						
							<b>ADJUNTAR BOUCHE:</b>
							<input type="file" class="form-control" id="archivoAdd" accept=".pdf,.jpg,.png" onchange="agregarArchivo()">
						
					</div>
					
					
					

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-bs-dismiss="modal" id="btnAceptarInfoGavetas">Aceptar</button>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="resetInputsGavetas()">Cancelar</button>
			</div>
		</div>
	</div>
</div>

<div id="modalGavetasVer" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title text-white">Gavetas</h4>
				<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body" style="overflow-y: auto; height: fit-content; margin:5px">
				<div style="overflow-x:auto;">
					<table class="table-sm table-hover table bg-light" id="tablaGavetasVer">
						<thead class="text-center bg-primary text-white">
							<tr>
								<th>GAVETAS</th>
								<!--<th>ENTREGADAS</th>
								<th>DEVUELTAS</th>-->
								<th>PENDIENTES</th>
							</tr>
						</thead>
						<tbody id="cuerpoTablaGavetasVer" class="text-center">
							
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				
			</div>
		</div>
	</div>
</div>



<div id="myModal_boletos" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title text-white">Ingrese el rango de boletos</h4>
			</div>
			<div class="modal-body">
				<b>Desde:</b>
				<input type="text" name="TxtRifaD" id="TxtRifaD" class="form-control form-control-sm" value="0">
				<b>Hasta:</b>
				<input type="text" name="TxtRifaH" id="TxtRifaH" class="form-control form-control-sm" value="0">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light border border-1" data-bs-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" onclick="Command2_Click();">Aceptar</button>
			</div>
		</div>
	</div>
</div>