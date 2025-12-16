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
?>

<script src="../../dist/js/facturacion/facturas_distribucion_fam.js">
	


</script>
<style>
	
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
						<label for="MBFecha" class="input-group-text"><b>Fecha checking</b></label>
						<input type="date" name="MBFechaChek" id="MBFechaChek" class="form-control form-control-sm"
							value="<?php echo date('Y-m-d'); ?>" readonly>
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
		
	</div>
	<div id="interfaz_campos">
		<div class="row pb-2">
			<div class="row col-sm-12">
				<div class="col-sm-6 pb-2">
					<div class="input-group input-group-sm">
						<label for="DCTipoFact2" class="input-group-text"><b>Tipo de Facturacion</b></label>
						<!-- <select class="form-select form-select-sm" name="DCTipoFact2" id="DCTipoFact2">

						</select> -->
						<input type="text" class="form-control form-control-sm" name="DCTipoFact2" id="DCTipoFact2" value="NDU" readonly>
					</div>
				</div>
				<div class="col-sm-6 pb-2">
					<div class="input-group input-group-sm">
						<label for="TextFacturaNo" class="input-group-text"><b id="Label1">NOTA DE DONACIÓN DE USUARIOS</b></label>
						<span class="input-group-text" id="LblSerie"><b></b></span>
						<input type="" class="form-control form-control-sm" id="TextNDUNo" name="TextNDUNo" readonly>
						<span class="input-group-text d-none" id="LblSerieFA"><b></b></span>
						<input type="" class="form-control form-control-sm d-none" id="TextFacturaNo" name="TextFacturaNo" readonly>
					</div>
				</div>
				<div class="col-sm-12 pb-2">
					<div class="row">
						<div class="col-sm-4">
							<b>Pedido</b>	
							<div class="d-flex align-items-center">
								<select class="form-select form-select-sm" id="ddl_pedidos" name="ddl_pedidos">
									<option value="">Seleccione Prog</option>
								</select>		
								<button class="btn btn-danger btn-sm pe-0 ps-1" onclick="quitar_de_facturar()"><i class="bx bx-x"></i></button>						
							</div>				
							
						</div>
						<div class="col-sm-4">
							<b>Programa</b>				
							<select class="form-select form-select-sm w-100" id="ddl_programas" name="ddl_programas" disabled>
								<option value="">Seleccione Programa</option>
							</select>
						</div>
						<div class="col-sm-4">
							<b>Grupo</b>
							<select class="form-select form-select-sm" id="ddl_grupos" name="ddl_grupos" disabled>
								<option value="">Seleccione Grupo</option>
							</select>				
						</div>
					</div>
					
					<input type="hidden" name="codigoCliente" id="codigoCliente" class="form-control input-xs">
					<input type="hidden" name="LblT" id="LblT" class="form-control input-xs">
				</div>
				<div class="col-sm-4 ps-0 pb-2" style="display: none;">
					<div class="input-group input-group-sm">
						<label for="LblRUC" class="input-group-text"><b>CI/RUC/PAS</b></label>
						<input type="" name="LblRUC" id="LblRUC" class="form-control form-control-sm" readonly>
					</div>
				</div>
				<div class="col-sm-8 pb-2" style="display: none;">
					<div class="input-group input-group-sm">
						<label for="Lblemail" class="input-group-text"><b>Correo Electrónico</b></label>
						<input type="email" name="Lblemail" id="Lblemail" class="form-control form-control-sm">
					</div>
				</div>
				
				<div class="col-sm-4 ps-0 pb-2" style="display: none;">
					<div class="input-group input-group-sm">
						<label for="saldoP" class="input-group-text"><b>Saldo Pendiente</b></label>
						<input name="saldoP" id="saldoP" class="form-control form-control-sm text-end" readonly value="0.00">
					</div>
				</div>
				<div class="col-sm-12" style="display: none;">
					<div class="input-group input-group-sm">
						<label for="DCDireccion" class="input-group-text"><b>Dirección</b></label>
						<select class="form-select form-select-sm" id="DCDireccion" name="DCDireccion" onchange="">
							<option value="."></option>
						</select>
					</div>
				</div>
				<!-- <div class="row">
				</div>
				<div class="row">
				</div>
				<div class="row">
				</div> -->
			</div>
			<div class="col-sm-3 p-2" style="display: none;">
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
			</div>
		</div>
		<div class="card" style="display: none;">
			<div class="card-body">
					<div class="row p-2 d-flex align-items-center pb-1">
					<div class="col-sm-3" style="width:fit-content">
						<b>Evaluación de fundaciones:</b>
					</div>
					<div class="col-sm-1" style="padding: 0;cursor:pointer;" onclick="$('#modalEvaluacionFundaciones').modal('show');">
						<div>
							<img src="../../img/png/eval_fundaciones.png" width="50px" alt="Evaluacion fundaciones" title="EVALUACIÓN FUNDACIONES">
						</div>
					</div>
					<div class="row row-cols-auto d-flex align-items-center col-sm-8">
						<div class="col-auto text-end">
							<b>Gavetas pendientes:</b>
						</div>
						<div class="col-2">
							<input type="text" name="gavetas_pendientes" id="gavetas_pendientes2" class="form-control form-control-sm" readonly>
						</div>
						<div class="col-auto">
							<button type="button" id="btn_detalle" class="btn btn-primary btn-sm btn-block" onclick="$('#modalGavetasVer').modal('show')"> Ver detalle <i class="fa fa-eye"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-sm-12">
		<div class="row text-center" style="padding-bottom:10px;height:250px;overflow-y:auto;">
			<div class="col-sm-12">
				<table class="table-sm table-hover table bg-light" id="tbl_lineas_factura">
					<thead class="text-center bg-primary text-white">
						<tr>
							<th>Código</th>
							<th>Producto</th>
							<th>Total Asignado</th> <!-- TODO: (Kg) dinámico -->
							<th>No Entregadas(<span id="tablaProdCU">.</span>)</th> <!-- TODO: (Kg) dinámico -->
							<th>pvp</th>
							<th>Aporte total</th>
							</tr>
					</thead>
					<!-- <tbody id="cuerpoTablaDistri"></tbody> -->
					
				</table>
			</div>
		</div>
		<div class="row d-flex align-items-center p-2">
			<!-- <div class="row col-sm-9">
				<div class="col-sm-3">
					<label for="gavetas_entregadas"><b>Gavetas entregadas:</b></label>
					<input type="text" class="form-control form-control-sm" id="gavetas_entregadas" value="0" disabled>
				</div>
				<div class="col-sm-3">
					<label for="gavetas_devueltas"><b>Gavetas devueltas:</b></label>
					<input type="text" class="form-control form-control-sm" id="gavetas_devueltas" value="0" disabled>
				</div>
				<div class="col-sm-3">
					<label for="gavetas_pendientes"><b>Gavetas pendientes:</b></label>
					<input type="text" class="form-control form-control-sm" id="gavetas_pendientes" value="0" disabled>
				</div>
				<div class="col-sm-1" style="cursor:pointer;" onclick="$('#modalGavetasInfo').modal('show')">
					<img src="../../img/png/gavetas.png" height="50px" alt="">
				</div>
			</div> -->
			<div class="offset-sm-9 col-sm-3" style="display:flex;flex-direction:column;justify-content:center">
				<div class="row">
					<div class="col-sm-12 mb-2">
						<button class="btn btn-light border border-1 btn-sm" onclick="$('#modalInfoFactura').modal('show')">Seleccionar métodos de pago</button>
						
					</div>
				</div>
				<div class="row align-items-center">
					<div class="col-sm-6">
						<b>Total Factura</b>
					</div>
					<div class="col-sm-6">
						<input type="text" name="LabelTotal2" id="LabelTotal2" class="form-control form-control-sm text-danger"
							value="0.00" readonly>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>
<br><br>



<div id="modal_grupoIntegrantes" class="modal fade"  role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title text-white">Integrantes de Grupos</h4>
				<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12 text-end">
						<button type="button" class="btn btn-primary"  onclick="validar_cliente_factura()">Generar Facturas</button>
					</div>
				</div>
                <div class="row">
                	<div class="col-sm-12" style="overflow-y: scroll; height: 350px;">
                		<table class="table table-sm table-hover table-striped" id="tblClientes">
	                        <thead>
                                <th> <input type="checkbox" name="cbx_all" id="cbx_all" onchange="cambiar_all()" checked>  </th>
                                <th>ITEM</th>
                                <th>USUARIO</th>
                                <th>CEDULA</th>
                                <th>CANT ENTR</th>
                                <th>TOTAL</th>
                                <th>PRODUCTO</th>
                                <th>APORTE</th>
	                        </thead>
	                        <tbody id="tbl_integrantes"></tbody>
	                    </table>
                	</div>                    
                </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning"  onclick="finalizar_factura()">Finalizar pedidos</button>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<div id="modal_grupoAlimentos" class="modal fade"  role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title text-white">Grupo de alimentos</h4>
				<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
               	<input type="hidden" name="txt_integrate_produ" id="txt_integrate_produ">
                <div class="row">
                	<div class="col-sm-12" id="">
                		<table class="table table-sm table-hover table-striped" id="tblClientes">
	                        <thead>
                                <th><input id="cbx_all_producto" name="cbx_all_producto" type="checkbox" onclick="all_Producto()"> </th>
                                <th>PRODUCTO</th>
                                <th>CANTIDAD SUG</th>
                                <th>CANTIDAD ENT</th>
                                <th>PVP</th>
                                <th>TOTAL</th>
	                        </thead>
	                        <tbody id="tbl_grupoAlimento"></tbody>
	                    </table>
                		
                	</div>                	
                </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary"  onclick="SeleccionarIntegrante()">Ok</button>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>





<div id="modalInfoFactura" class="modal fade"  role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title text-white">Información Facturar - Familias</h4>
				<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body" style="overflow-y: auto; height: fit-content; margin:5px">

				<div class="row col-sm-12 justify-content-center">
					<div class="row">
						<div class="col-sm-5">
							<b>Cuenta x Cobrar</b>
						</div>
						<div class="col-sm-7">
							<select class="form-control form-control-sm" id="DCLineas" name="DCLineas"
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
								<b>CUENTA</b>
							</div>
							<div class="col-sm-7">
								<select class="form-select form-select-sm" id="DCEfectivo" name="DCEfectivo">
									<option>Seleccion opciones</option>
								</select>
							</div>
						</div>
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
						<hr>
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
								style="color: red;" value="0.00">
						</div>
					</div>
					<div class="row" id="bouche_banco_input" style="margin:10px 0;display:none;">
							<b>ADJUNTAR BOUCHE:</b>
							<input type="file" class="form-control" id="archivoAdd" accept=".pdf,.jpg,.png" onchange="agregarArchivo()">
					</div>
					<input type="hidden" name="txtBeneficiarioAbono" id="txtBeneficiarioAbono">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-sm" onclick="guardarAbonos()">Aceptar</button>
				<button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal" id="btnAceptarInfoGavetas">Cancelar</button>
				<!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="resetInputsGavetas()">Cancelar</button> -->
			</div>
		</div>
	</div>
</div>