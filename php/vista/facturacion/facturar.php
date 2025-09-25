<?php date_default_timezone_set('America/Guayaquil'); //print_r($_SESSION);die();//print_r($_SESSION['INGRESO']);die();
$servicio = $_SESSION['INGRESO']['Servicio'];
?>
<script src="../../dist/js/facturar.js"></script>
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
	<div class="col-sm-4">
		<div class=" btn-group">
			<a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
				print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
				<img src="../../img/png/salire.png">
			</a>
			<button type="button" class="btn btn-outline-secondary" id="btnGrabar" data-bs-toggle="tooltip" title="Grabar factura" onclick="boton1()">
				<img src="../../img/png/grabar.png">
			</button>
			<button type="button" class="btn btn-outline-secondary" id="btnActualizar" data-bs-toggle="tooltip" title="Actualizar Productos, Marcas y Bodegas" onclick="boton2()">
				<img src="../../img/png/update.png">
			</button>
			<button type="button" class="btn btn-outline-secondary" id="btnOrden" data-bs-toggle="tooltip" title="Asignar orden de trabajo" onclick="boton3()">
				<img src="../../img/png/taskboard.png">
			</button>
			<button type="button" class="btn btn-outline-secondary" id="btnGuia" data-bs-toggle="tooltip" title="Asignar guía de remisión" onclick="boton4()">
				<img src="../../img/png/ats.png">
			</button>
			<button type="button" class="btn btn-outline-secondary" id="btnSuscripcion" data-bs-toggle="tooltip" title="Asignar suscripción/contrato" onclick="boton5()">
				<img src="../../img/png/file2.png">
			</button>
			<button type="button" class="btn btn-outline-secondary" id="btnReserva" data-bs-toggle="tooltip" title="Asignar reserva" onclick="boton6()">
				<img src="../../img/png/archivero2.png">
			</button>
			
		<!-- 	<button type="button" class="btn btn-outline-secondary" id="btnReserva" data-bs-toggle="tooltip" title="Asignar reserva" onclick="boton7()">
				<img src="../../img/png/archivero2.png">
			</button> -->


		</div>
	</div>
	<div class="col-sm-8">
		<div class="row">
			<div class="col-sm-2 col-6">
				<div class="form-check">
					<input class="form-check-input" type="checkbox" name="Check1" id="Check1">
					<label class="form-check-label" for="Check1">
						Factura en ME
					</label>
				</div>
			</div>
			<div class="col-sm-2 col-6 p-0" id="CheqSPFrom">
				<div class="form-check">
					<input class="form-check-input" type="checkbox" name="CheqSP" id="CheqSP">
					<label class="form-check-label" for="CheqSP">
						Sector publico
					</label>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="input-group input-group-sm">
					<label for="TxtCompra" class="input-group-text"><b>Orden Compra No</b></label>
					<input type="" aria-label="TxtCompra" name="TxtCompra" id="TxtCompra" class="form-control form-control-sm text-end" value="0">
				</div>
			</div>
			<div class="col-sm-3">
				<select class="form-select form-select-sm" id="DCMod" name="DCMod">
					<option value="">Seleccione</option>
				</select>
			</div>
			<div class="col-sm-1">
				<input type="text" name="LabelCodigo" id="LabelCodigo" class="form-control form-control-sm" readonly=""
					value=".">
			</div>
		</div>
	</div>
</div>
<div class="row mb-2">
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-sm-7">
					<div class="row">
						<div class="col-sm-7">
							<div class="input-group input-group-sm">
								<label for="MBoxFecha" class="input-group-text"><b>Emisión</b></label>
								<input type="date" aria-label="FechaEmision" name="MBoxFecha" id="MBoxFecha" class="form-control form-control-sm p-1" min="01-01-2000" max="31-12-2050"
									value="<?php echo date('Y-m-d'); ?>" onblur="DCPorcenIva('MBoxFecha', 'DCPorcenIVA');">
								<label for="MBoxFechaV" class="input-group-text"><b>Vencimiento</b></label>
								<input type="date" aria-label="FechaVenc" name="MBoxFechaV" id="MBoxFechaV" class="form-control form-control-sm p-1" min="01-01-2000" max="31-12-2050"
									value="<?php echo date('Y-m-d'); ?>" onblur="MBoxFechaV_LostFocus()">
							</div>
						</div>
						<div class="col-sm-5">
							<div class="input-group input-group-sm">
								<label for="DCLineas" class="input-group-text"><b>Cuenta x Cobrar</b></label>
								<select aria-label="DCLineas" name="DCLineas" id="DCLineas" class="form-select form-select-sm" onchange="DCLinea_LostFocus()">
									<option value="">Seleccione</option>
								</select>
							</div>
							<input type="hidden" name="DCLineasV" id="DCLineasV">
						</div>					
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="input-group input-group-sm">
								<label for="DCTipoPago" class="input-group-text"><b>Tipo de pago</b></label>
								<select aria-label="DCTipoPago" name="DCTipoPago" id="DCTipoPago" class="form-select form-select-sm">
									<option value="">Seleccione</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 col-md-12">							
							<div class=" input-group-sm d-flex">
								<label for="DCCliente" class="input-group-text"><b>Cliente</b></label>
								<select class="form-select form-select-sm" id="DCCliente" name="DCCliente">
									<option value="">Seleccione</option>
								</select>
								<button class="btn btn-primary btn-sm d-flex align-items-center" type="button" onclick="addCliente();"><i
										class="bx bx-plus"></i></button>
							</div>
						</div>
						
					</div>
				</div>
				<div class="col-sm-5">
					<div class="row">
						<div class="col-sm-3">
							<div class="input-group input-group-sm">
								<label for="DCPorcenIVA" class="input-group-text"><b>I.V.A:</b></label>
								<select aria-label="DCPorcenIVA" name="DCPorcenIVA" id="DCPorcenIVA" class="form-select-sm" onblur="cambiar_iva(this.value)">
									<option value="">-</option>
								</select>
							</div>
						</div>
						<div class="col-sm-9">
							<div class="input-group input-group-sm">
								<span class="input-group-text text-danger fw-bold" id="label2">0000000000000 NOTA DE VENTA No. 001001-</span>
								<input type="text" name="TextFacturaNo" id="TextFacturaNo" class="form-control form-control-sm"
									value="000000">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class=" input-group-sm d-flex">
								<label for="DCGrupo_No" class="input-group-text"><b>Grupo</b></label>
								<select  id="DCGrupo_No" name="DCGrupo_No" class="w-100" 
									onchange="autocomplete_cliente()">
									<option value="">Seleccione</option>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="input-group input-group-sm">
								<label for="LblSaldo" class="input-group-text"><b>Saldo pendiente</b></label>
								<input type="text" aria-label="LblSaldo" name="LblSaldo" id="LblSaldo" class="form-control form-control-sm text-end" value="0.00" readonly>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 col-6">
							<div class="input-group input-group-sm">
								<label for="LabelRUC" class="input-group-text"><b>C.I / R.U.C</b></label>
								<input type="text" aria-label="LabelRUC" name="LabelRUC" id="LabelRUC" class="form-control form-control-sm" readonly="" value=".">
							</div>
						</div>
						<div class="col-sm-6 col-6">
							<div class="input-group input-group-sm">
								<label for="LabelTelefono" class="input-group-text"><b>Telefono</b></label>
								<input type="text" aria-label="LabelTelefono" name="LabelTelefono" id="LabelTelefono" class="form-control form-control-sm">
							</div>
						</div>
						
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-7 col-12">
					<div class="row">
						<div class="col-sm-9">
							<div class="input-group input-group-sm">
								<span class="input-group-text"><b>Direccion</b></span>
								<input type="text" name="Label24" id="Label24" class="form-control form-control-sm" value="" readonly="">
							</div>
						</div>
						<div class="col-sm-3">
							<div class="input-group input-group-sm">
								<span class="input-group-text"><b>No</b></span>
								<input type="text" name="Label21" id="Label21" class="form-control form-control-sm" value="" readonly="">
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-5 col-12">
					<div class="input-group input-group-sm">
						<label for="TxtEmail" class="input-group-text"><b>CORREO</b></label>
						<input type="text" aria-label="TxtEmail" name="TxtEmail" id="TxtEmail" class="form-control form-control-sm">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<select class="form-select form-select-sm" id="DCMedico" name="DCMedico">
						<option value="">Seleccione</option>
					</select>
				</div>
				<div class="col-sm-6">
					<div id="DCEjecutivoFrom" class="row p-0">
						<div class="col-sm-4">
							<div class="form-check p-2">
								<input class="form-check-input" type="checkbox" name="CheqEjec" id="CheqEjec">
								<label class="form-check-label" for="CheqEjec">
									<b> Ejecutivo de venta</b>
								</label>
							</div>
						</div>
						<div class="col-sm-8">
							<select class="form-select form-select-sm" name="DCEjecutivo" id="DCEjecutivo">
								<option value="">Seleccione</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="input-group input-group-sm" style="display:none;">
						<b class="input-group-text">comision%</b>
						<input type="text" name="TextComision" id="TextComision" value="0" class="form-control form-control-sm text-end">
					</div>
						
				</div>
			</div>
			<div class="row">
				<div class="col-sm-5">
					<div class="input-group input-group-sm">
						<label for="TextObs" class="input-group-text"><b>Observacion</b></label>
						<input aria-label="TextObs" class="form-control form-control-sm" name="TextObs" id="TextObs">
					</div>
				</div>
				<div class="col-sm-4">
					<div class="input-group input-group-sm">
						<label for="TextNota" class="input-group-text"><b>Nota</b></label>
						<input aria-label="TextNota" class="form-control form-control-sm" name="TextNota" id="TextNota">
					</div>
				</div>
				<div class="col-sm-3">
					<div class="input-group input-group-sm">
						<label for="DCBodega" class="input-group-text"><b>Bodega</b></label>
						<select aria-label="DCBodega" class="form-select form-select-sm" name="DCBodega" id="DCBodega">
							<option value="">Seleccione</option>
						</select>
					</div>
				</div>
			</div>
			
			<hr class="border-primary">
			<div class="row bg-body-secondary p-2">
				<div class="col-sm-4 col-lg-2">
					<label for="DCMarca" class="form-label mb-0"><b>Marca</b></label>
					<div class="col-sm-12">
						<select class="form-select form-select-sm" id="DCMarca" name="DCMarca">
							<option value="">Seleccione</option>
						</select>
					</div>
				</div>
				<div class="col-sm-8 col-lg-4">
					<label id="LabelStockArt" for="DCArticulos" class="form-label mb-0"><b>Producto</b></label>
					<div class="col-sm-12">
						<select class="form-select form-select-sm" name="DCArticulos" id="DCArticulos"
							onchange="DCArticulo_LostFocus()">
							<option value="">Seleccione</option>
						</select>
					</div>
				</div>
				<div class="col-sm-2 col-lg-1">
					<b>Stock</b>
					<input type="text" name="LabelStock" id="LabelStock" class="form-control form-control-sm text-end" readonly="">
				</div>
				<div class="col-sm-2 col-lg-1">
					<b>Ord./lote</b>
					<input type="text" name="TextComEjec" id="TextComEjec" class="form-control form-control-sm text-end">
				</div>
				<div class="col-sm-2 col-lg-1">
					<b>Desc%</b>
					<select class="form-select form-select-sm text-end" id="CDesc1" name="CDesc1">
						<option value="">Seleccione</option>
					</select>
				</div>
				<div class="col-sm-2 col-lg-1">
					<b>Cantidad</b>
					<input type="text" name="TextCant" id="TextCant" class="form-control form-control-sm text-end" onblur="" value="0">
				</div>
				<div class="col-sm-2 col-lg-1">
					<b>P.V.P</b>
					<input type="text" name="TextVUnit" id="TextVUnit" class="form-control form-control-sm text-end"
						onblur="TextVUnit_LostFocus(); TextCant_Change();" value="0">
				</div>
				<div class="col-sm-2 col-lg-1">
					<b>TOTAL</b>
					<input type="text" name="LabelVTotal" id="LabelVTotal" class="form-control form-control-sm text-end" readonly=""
						value="0">
				</div>
			</div>
		</div>		
	</div>
</div>
<div class="row mb-2">
	<div class="card">
		<div class="card-body">
			<div class="interfaz_tabla" id="interfaz_tabla">
				<table id="tbl" class="table fs-8" style="width:100%">
					<thead>
						<th></th>
						<th>CODIGO</th>          
						<th>CANT</th>
						<th>CANT_BONIF</th>
						<th>PRODUCTO</th>
						<th>PRECIO</th>
						<th>Total_Desc</th>
						<th>Total_Desc2</th>
						<th>Total_IVA</th>
						<th>SERVICIO</th>
						<th>TOTAL</th>
						<th>VALOR_TOTAL</th>
						<th>COSTO</th>
						<th>Fecha_IN</th>
						<th>Fecha_OUT</th>
						<th>Cant_Hab</th>
						<th>Tipo_Hab</th>
						<th>Orden_No</th>
						<th>Mes</th>
						<th>Cod_Ejec</th>
						<th>Porc_C</th>
						<th>REP</th>
						<th>FECHA</th>
						<th>CODIGO_L</th>
						<th>HABIT</th>
						<th>RUTA</th>
						<th>TICKET</th>
						<th>Cta</th>
						<th>Cta_SubMod</th>
						<th>Item</th>
						<th>CodigoU</th>
						<th>CodBod</th>
						<th>BodMar</th>
						<th>TONELAJE</th>
						<th>CORTE</th>
						<th>A_No</th>
						<th>Codigo_Cliente</th>
						<th>Numero</th>
						<th>Serie</th>
						<th>Autorizacion</th>
						<th>Codigo_B</th>
						<th>PRECIO2</th>
						<th>COD_BAR</th>
						<th>Fecha_V</th>
						<th>Lote_No</th>
						<th>Fecha_Fab</th>
						<th>Fecha_Exp</th>
						<th>Reg_Sanitario</th>
						<th>Modelo</th>
						<th>Procedencia</th>
						<th>Serie_No</th>
						<th>Cta_Inv</th>
						<th>Cta_Costo</th>
						<th>Estado</th>
						<th>NoMes</th>
						<th>Cheking</th>
						<th>ID</th>
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
							<td></td>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>		
	</div>
</div>
<div class="row mb-2">
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-sm-9">
					<div class="row">
						<div class="col-sm-2">
							<b>Total sin Iva</b>
							<input type="text" name="LabelSubTotal" id="LabelSubTotal" class="form-control form-control-sm text-end text-danger fw-bold">
						</div>
						<div class="col-sm-2">
							<b>Total con IVA</b>
							<input type="text" name="LabelConIVA" id="LabelConIVA" class="form-control form-control-sm text-end text-danger fw-bold">
						</div>
						<div class="col-sm-2">
							<b>Total Desc</b>
							<input type="text" name="TextDesc" id="TextDesc" class="form-control form-control-sm text-end text-danger fw-bold">
						</div>
						<div class="col-sm-2">
							<b id="label36"></b>
							<input type="text" name="LabelServ" id="LabelServ" class="form-control form-control-sm text-end text-danger fw-bold">
						</div>
						<div class="col-sm-2">
							<b id="label3">I.V.A</b>
							<input type="text" name="LabelIVA" id="LabelIVA" class="form-control form-control-sm text-end text-danger fw-bold">
						</div>
						<div class="col-sm-2">
							<b>Total Facturado</b>
							<input type="text" name="LabelTotal" id="LabelTotal" class="form-control form-control-sm text-end text-danger fw-bold">
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<br>
					<input type="text" name="LblGuia" id="LblGuia" class="form-control form-control-sm" readonly>
				</div>
			</div>
		</div>		
	</div>	
</div>


<div id="interfaz_facturacion">

	<div class="interfaz_campos">
		<div class="row">
			<div class="col-sm-12">
				<!-- //valiable  -->
				<input type="hidden" name="Mod_PVP" id="Mod_PVP" value="0">
				<input type="hidden" name="DatInv_Serie_No" id="DatInv_Serie_No" value="">
				<input type="hidden" name="BanIVA" id="BanIVA">
				<input type="hidden" name="Reprocesar" id="Reprocesar"=value="0">
                <input type="hidden" name="Servicio" id="Servicio" value="<?php echo $servicio; ?>">

				<form id="FA" style="display:none;">

					<input type="text" name="TC" id="TC" value="">
					<input type="text" name="Cant_Item" id="Cant_Item">

					<input type="text" name="Autorizacion" id="Autorizacion">
					<input type="text" name="CantFact" id="CantFact">
					<!-- <input type="text" name="Cant_Item" id="Cant_Item"> -->
					<input type="text" name="Cod_CxC" id="Cod_CxC">
					<input type="text" name="Cta_CxP" id="Cta_CxP">
					<input type="text" name="Cta_CxP_Anterior" id="Cta_CxP_Anterior">
					<input type="text" name="Cta_Venta" id="Cta_Venta">
					<input type="text" name="CxC_Clientes" id="CxC_Clientes">

					<input type="text" name="DireccionEstab" id="DireccionEstab">
					<input type="text" name="Fecha" id="Fecha" value="">
					<input type="text" name="Fecha_Aut" id="Fecha_Aut">
					<input type="text" name="Fecha_NC" id="Fecha_NC">
					<input type="text" name="Imp_Mes" id="Imp_Mes">


					<input type="text" name="NoFactura" id="NoFactura">
					<input type="text" name="NombreEstab" id="NombreEstab">
					<input type="text" name="Porc_IVA" id="Porc_IVA">
					<input type="text" name="Porc_Serv" id="Porc_Serv">
					<input type="text" name="Pos_Copia" id="Pos_Copia">
					<input type="text" name="Pos_Factura" id="Pos_Factura">
					<input type="text" name="Serie" id="Serie">
					<input type="text" name="TelefonoEstab" id="TelefonoEstab">
					<input type="text" name="Vencimiento" id="Vencimiento">

					<!-- guia -->
					<input type="text" name="ClaveAcceso_GR" id="ClaveAcceso_GR" value="">
					<input type="text" name="Autorizacion_GR" id="Autorizacion_GR" value="">
					<input type="text" name="Serie_GR" id="Serie_GR" value="">
					<input type="text" name="Remision" id="Remision" value="">
					<input type="text" name="FechaGRE" id="FechaGRE" value="">
					<input type="text" name="FechaGRI" id="FechaGRI" value="">
					<input type="text" name="FechaGRF" id="FechaGRF" value="">
					<input type="text" name="Placa_Vehiculo" id="Placa_Vehiculo" value="">
					<input type="text" name="Lugar_Entrega" id="Lugar_Entrega" value="">
					<input type="text" name="Zona" id="Zona" value="">
					<input type="text" name="CiudadGRI" id="CiudadGRI" value="">
					<input type="text" name="CiudadGRF" id="CiudadGRF" value="">
					<input type="text" name="Comercial" id="Comercial" value="">
					<input type="text" name="CIRUCComercial" id="CIRUCComercial" value="">
					<input type="text" name="Entrega" id="Entrega" value="">
					<input type="text" name="CIRUCEntrega" id="CIRUCEntrega" value="">
					<input type="text" name="Dir_EntregaGR" id="Dir_EntregaGR" value="">
					<!-- fin guia -->
				</form>


				<!-- //fin de variables -->
				<input type="hidden" name="TipoFactura" id="TipoFactura">
				
			
				
				
				
			</div>
		</div>
	</div>

<div class="modal fade" id="cambiar_nombre" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1">
	<div class="modal-dialog modal-dialog modal-sm"
		style="margin-left: 25%; margin-top: 30%;">
		<div class="modal-content">
			<div class="modal-body text-center">
				<textarea class="form-control form-control-sm" style="resize: none;" rows="4" id="TxtDetalle" name="TxtDetalle"
					onblur="cerrar_modal_cambio_nombre()"></textarea>
			</div>
		</div>
	</div>
</div>

<!-- Modal cliente nuevo -->
<div id="myModal_guia" class="modal fade" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">DATOS DE GUIA DE REMISION</h4>
				<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<form id="form_guia">
					<div class="row">
						<div class="row align-items-center col-sm-12 pe-0 pb-1">
							<div class="col-sm-6">
								<b>Fecha de emisión de guía</b>
							</div>
							<div class="col-sm-6 px-0">
								<input type="date" name="MBoxFechaGRE" id="MBoxFechaGRE" class="form-control form-control-sm"
									value="<?php echo date('Y-m-d'); ?>" onblur="MBoxFechaGRE_LostFocus();">
							</div>
						</div>
						<div class="row align-items-center col-sm-12 pe-0 pb-1">
							<div class="col-sm-6">
								<b>Guía de remisión No.</b>
							</div>
							<div class="col-sm-6 px-0">
								<div class="input-group input-group-sm">
									<select class="form-select form-select-sm" id="DCSerieGR" name="DCSerieGR"
										onchange="DCSerieGR_LostFocus()">
										<option value="">No Existe</option>
									</select>
									<input type="text" name="LblGuiaR" id="LblGuiaR" class="form-control form-control-sm"
										value="000000">
								</div>
							</div>
						</div>
						<div class="col-sm-12 pb-1">
							<b>AUTORIZACION GUIA DE REMISION</b>
							<input type="text" name="LblAutGuiaRem" id="LblAutGuiaRem" class="form-control form-control-sm"
								value="0">
						</div>
						<div class="row align-items-center col-sm-12 pe-0 pb-1">
							<div class="col-sm-6">
								<b>Iniciación del traslados</b>
							</div>
							<div class="col-sm-6 px-0">
								<input type="date" name="MBoxFechaGRI" id="MBoxFechaGRI" class="form-control form-control-sm"
									value="<?php echo date('Y-m-d'); ?>">
							</div>
						</div>
						<div class="row align-items-center col-sm-12 pe-0 pb-1">
							<div class="col-sm-3">
								<b>Ciudad</b>
							</div>
							<div class="col-sm-9 px-0">
								<select class="form-select form-select-sm" style="width:100%" id="DCCiudadI" name="DCCiudadI">
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="row align-items-center col-sm-12 pe-0 pb-1">
							<div class="col-sm-6">
								<b>Finalización del traslados</b>
							</div>
							<div class="col-sm-6 px-0">
								<input type="date" name="MBoxFechaGRF" id="MBoxFechaGRF" class="form-control form-control-sm"
									value="<?php echo date('Y-m-d'); ?>">
							</div>
						</div>
						<div class="row align-items-center col-sm-12 pe-0 pb-1">
							<div class="col-sm-3">
								<b>Ciudad</b>
							</div>
							<div class="col-sm-9 px-0">
								<select class="form-select form-select-sm" style="width:100%" id="DCCiudadF" name="DCCiudadF">
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-sm-12 pb-1">
							<b>Nombre o razón social (Transportista)</b>
							<select class="form-select form-select-sm" style="width:100%" id="DCRazonSocial"
								name="DCRazonSocial">
								<option value=""></option>
							</select>
						</div>
						<div class="col-sm-12 pb-1">
							<b>Empresa de Transporte</b>
							<select class="form-select form-select-sm" style="width:100%" id="DCEmpresaEntrega"
								name="DCEmpresaEntrega">
								<option value=""></option>
							</select>
						</div>
						<div class="col-sm-4">
							<b>Placa</b>
							<input type="text" name="TxtPlaca" id="TxtPlaca" class="form-control form-control-sm" value="XXX-999">
						</div>
						<div class="col-sm-4">
							<b>Pedido</b>
							<input type="text" name="TxtPedido" id="TxtPedido" class="form-control form-control-sm">
						</div>
						<div class="col-sm-4">
							<b>Zona</b>
							<input type="text" name="TxtZona" id="TxtZona" class="form-control form-control-sm">
						</div>
						<div class="col-sm-12 pt-1">
							<b>Lugar entrega</b>
							<input type="text" name="TxtLugarEntrega" id="TxtLugarEntrega" class="form-control form-control-sm">
						</div>
					</div>
				</form>


			</div>
			<div class="modal-footer">
				<button class="btn btn-primary btn-block" onclick="Command8_Click();">Aceptar</button>
				<button type="button" class="btn btn-secondary btn-block" data-bs-dismiss="modal">Cerrar</button>
			</div>
		</div>

	</div>
</div>



<div id="myModal_suscripcion" class="modal fade" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">FORMULARIO DE SUSCRIPCION</h4>
				<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-10">
						<form id="form_suscripcion">
							<input type="hidden" name="LblClienteCod" id="LblClienteCod">
							<div class="row mb-1">
								<div class="col-sm-12">
									<input type="text" name="LblCliente" id="LblCliente" class="form-control form-control-sm mb-1"
										readonly>
									<select class="form-select form-select-sm" id="DCCtaVenta" name="DCCtaVenta">
										<option value="">Seleccione</option>
									</select>
								</div>
							</div>
							<div class="row mb-1">
								<div class="col-sm-8 mb-1">
									<div class="row">
										<div class="col-sm-7">
											<b>Periodo</b>
											<div class="row">
												<div class="col-sm-6" style="padding-right: 1px;">
													<input type="date" name="MBDesde" id="MBDesde"
														class="form-control form-control-sm"
														value="<?php echo date('Y-m-d') ?>">
												</div>
												<div class="col-sm-6" style="padding-left: 1px;">
													<input type="date" name="MBHasta" id="MBHasta"
														class="form-control form-control-sm"
														value="<?php echo date('Y-m-d') ?>">
												</div>
											</div>
										</div>
										<div class="col-sm-3" style="padding: 1px;">
											<b>Contrato No.</b>
											<input type="text" name="TextContrato" id="TextContrato"
												class="form-control form-control-sm" value=".">
										</div>
										<div class="col-sm-2" style="padding-left: 1px; padding-top: 1px;">
											<b>Sector</b>
											<input type="text" name="TextSector" id="TextSector"
												class="form-control form-control-sm" value=".">
										</div>
									</div>
									<div class="row mb-1">
										<div class="col-sm-3" style="padding-right: 1px; padding-top: 1px;">
											<b>Ent. hasta</b>
											<input type="text" name="TxtHasta" id="TxtHasta"
												class="form-control form-control-sm" value="0.00">
										</div>
										<div class="col-sm-3" style="padding: 1px;">
											<b>Tipo</b>
											<input type="text" name="TextTipo" id="TextTipo"
												class="form-control form-control-sm" value=".">
										</div>
										<div class="col-sm-3" style="padding: 1px;">
											<b>Comp. Venta</b>
											<input type="text" name="TextFact" id="TextFact"
												class="form-control form-control-sm" value="0">
										</div>
										<div class="col-sm-3" style="padding-left: 1px;  padding-top: 1px;">
											<b>Valor suscr</b>
											<input type="text" name="TextValor" id="TextValor"
												class="form-control form-control-sm" value="0.00">
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<b> Atención /Entregar a:</b>
											<input type="text" name="TxtAtencion" id="TxtAtencion"
												class="form-control form-control-sm">
										</div>
									</div>

								</div>
								<div class="col-sm-4 mb-1">
									<div class="row py-2">
										<div class="col-sm-6">
											<div class="form-check">
												<input class="form-check-input" type="radio" name="opc" id="OpcMensual" value="OpcMensual" checked>
												<label class="form-check-label" for="OpcMensual">
													Mensual
												</label>
											</div>
											<!-- <div class="checkbox">
												<label>
													<input type="radio" name="opc" value="OpcMensual" id="OpcMensual"
														checked> Mensual
												</label>
											</div> -->
										</div>
										<div class="col-sm-6">
											<div class="form-check">
												<input class="form-check-input" type="radio" name="opc" id="OpcAnual" value="OpcAnual">
												<label class="form-check-label" for="OpcAnual">
													Anual
												</label>
											</div>
											<!-- <div class="checkbox">
												<label>
													<input type="radio" name="opc" value="OpcAnual" id="OpcAnual"> Anual
												</label>
											</div> -->
										</div>
									</div>
									<div class="row py-2">
										<div class="col-sm-6">
											<div class="form-check">
												<input class="form-check-input" type="radio" name="opc" id="OpcQuincenal" value="OpcQuincenal">
												<label class="form-check-label" for="OpcQuincenal">
													Quincenal
												</label>
											</div>
											<!--<div class="radio">
												<label>
													<input type="radio" name="opc" value="OpcQuincenal"
														id="OpcQuincenal">Quincenal
												</label>
											</div>-->
										</div>
										<div class="col-sm-6">
											<div class="form-check">
												<input class="form-check-input" type="radio" name="opc" id="OpcTrimestral" value="OpcTrimestral">
												<label class="form-check-label" for="OpcTrimestral">
													Trimestral
												</label>
											</div>
											<!--<div class="radio">
												<label>
													<input type="radio" name="opc" value="OpcTrimestral"
														id="OpcTrimestral"> Trimestral
												</label>
											</div>-->
										</div>
									</div>
									<div class="row py-2">
										<div class="col-sm-6">
											<div class="form-check">
												<input class="form-check-input" type="radio" name="opc" id="OpcSemanal" value="OpcSemanal">
												<label class="form-check-label" for="OpcSemanal">
													Semanal
												</label>
											</div>
											<!-- <div class="radio">
												<label>
													<input type="radio" name="opc" value="OpcSemanal" id="OpcSemanal">
													Semanal
												</label>
											</div> -->
										</div>
										<div class="col-sm-6">
											<div class="form-check">
												<input class="form-check-input" type="radio" name="opc" id="OpcSemestral" value="OpcSemestral">
												<label class="form-check-label" for="OpcSemestral">
													Semestral
												</label>
											</div>
											<!-- <div class="radio">
												<label>
													<input type="radio" name="opc" value="OpcSemestral"
														id="OpcSemestral"> Semestral
												</label>
											</div> -->
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="row align-items-end">
										<div class="col-sm-8">
											<div class="row">
												<div class="col-sm-6">
													<b>Ejecutivo de Venta</b>
													<select class="form-control form-control-sm" id="DCEjecutivoModal"
														name="DCEjecutivoModal">
														<option value="">Seleccione</option>
													</select>
												</div>
												<div class="col-sm-6">
													<b>Comisión %</b>
													<input type="text" name="TextComisionModal" id="TextComisionModal"
														class="form-control form-control-sm" onblur="TextComision_LostFocus()">
												</div>
											</div>
										</div>
										<div class="col-sm-4">
											<div class="row">
												<div class="col-sm-6">
													<div class="form-check">
														<input class="form-check-input" type="radio" name="opc2" id="OpcN" value="OpcN" checked>
														<label class="form-check-label" for="OpcN">
															Nuevo
														</label>
													</div>
													<!-- <div class="checkbox">
														<label>
															<input type="radio" name="opc2" value='OpcN' id="OpcN"
																checked>
															Nuevo
														</label>
													</div> -->
												</div>
												<div class="col-sm-6">
													<div class="form-check">
														<input class="form-check-input" type="radio" name="opc2" id="OpcR" value="OpcR">
														<label class="form-check-label" for="OpcR">
															Renovación
														</label>
													</div>
													<!-- <div class="checkbox">
														<label>
															<input type="radio" name="opc2" value='OpcR' id="OpcR">
															Renovación
														</label>
													</div> -->
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-12" style="padding-top: 5px;">
									<div class="row">
										<div class="col-sm-12 text-center table table-hover">
											<table class="table table-sm table-hover" id="tbl_suscripcion" style="width:100%">
												<thead>
													<th>Ejemplar</th>
													<th>Fecha</th>
													<th>Entregado</th>
													<th>Sector</th>
													<th>Comisión</th>
													<th>Capital</th>
													<th>T_No</th>
													<th>Item</th>
													<th>CodigoU</th>
												</thead>
												<tbody></tbody>
											</table>
										</div>
										<br>
										<div class="row g-3 align-items-center">
											<div class="col-auto">
												<label for="txtperiodo" class="col-form-label"><b>Periodo:</b></label>
											</div>
											<div class="col-auto">
												<input type="texto" name="txtperiodo" id="txtperiodo" class="form-control form-control-sm">
											</div>
											
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="col-sm-2">
						<div class="row">
							<div class="col-sm-12">
								<button class="btn btn-outline-secondary btn-block w-100" id="btn_g" onclick="Command1();">
									<img src="../../img/png/grabar.png"><br> Guardar
								</button>
							</div>
							<div class="col-sm-12 pt-1">
								<button class="btn btn-outline-secondary btn-block w-100" data-dismiss="modal"
									onclick="delete_asientoP();">
									<img src="../../img/png/bloqueo.png"><br> Cancelar
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> -->
			</div>
		</div>

	</div>
</div>

<!-- Modal reserva -->
<div id="myModal_reserva" class="modal fade" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Datos de la reserva</h4>
				<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-4">
						<b>Entrada</b>
						<input type="date" name="ResvEntrada" id="ResvEntrada" class="form-control form-control-sm"
							value="<?php echo date('Y-m-d') ?>">
					</div>
					<div class="col-sm-4">
						<b>Salida</b>
						<input type="date" name="ResvSalida" id="ResvSalida" class="form-control form-control-sm"
							value="<?php echo date('Y-m-d') ?>">
					</div>
					<div class="col-sm-4">
						<b>Noches</b>
						<input type="text" name="cantNoches" id="cantNoches" class="form-control form-control-sm" value="1">
					</div>
				</div>
				<div class="row pt-1">
					<div class="col-sm-6">
						<b>Cantidad de Habitaciones</b>
						<input type="text" name="TxtCantHab" id="TxtCantHab" class="form-control form-control-sm" value="0">
					</div>
					<div class="col-sm-6">
						<b>Tipo de Habitación</b>
						<input type="text" name="TxtTipoHab" id="TxtTipoHab" class="form-control form-control-sm">
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button class="btn btn-primary" onclick="abrirDetalle()">Aceptar</button>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="divTxtDetalleReserva" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static"
	tabindex="-1">
	<div class="modal-dialog modal-dialog modal-dialog-centered modal-sm"
		style="margin-left: 300px; margin-top: 345px;">
		<div class="modal-content">
			<div class="modal-body text-center">
				<textarea class="form-control" style="resize: none;" rows="4" id="TxtDetalleReserva" name="TxtDetalle"
					onblur="txtDetalleLostFocus()"></textarea>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function abrirDetalle() {
		$('#divTxtDetalleReserva').on('shown.bs.modal', function () {
			$('#TxtDetalleReserva').focus();
		})
		$('#divTxtDetalleReserva').modal('show', function () {
			$('#TxtDetalleReserva').focus();
		})

		$('#myModal_reserva').modal('hide');
		var noches = $('#cantNoches').val();
		$('#TextCant').val(noches);
		$('#TxtDetalleReserva').val(producto);
		if (detalle.length > 3) {
			$('#TxtDetalleReserva').val($('#TxtDetalleReserva').val() + '\n' + detalle);
		}
	}

	function txtDetalleLostFocus() {
		$('#divTxtDetalleReserva').modal('hide');
	}
</script>

<!-- Modal ordenes produccion -->
<div id="myModal_ordenesProd" class="modal fade" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
	<div class="modal-dialog modal-md" style="width: 30%;min-width:350px;">
		<div class="modal-content">.
			<div class="modal-header">
				<h4 class="modal-title">Ordenes de Producción</h4>
				<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<select id="selectOrden" form="form-control">
				</select>
			</div>

			<div class="modal-footer px-3">
				<div class="col-sm-12">
					<button class="btn btn-primary btn-block w-100" onclick="CommandButton1_Click()">Imprimir Detalle
						Orden</button>
				</div>
				<div class="col-sm-12">
					<button class="btn btn-primary btn-block w-100" onclick="llenarOrden()">Procesar Selección</button>
				</div>
				<div class="col-sm-12">
					<button type="button" class="btn btn-outline-secondary btn-block w-100" data-bs-dismiss="modal">Cancelar</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	function llenarOrden() {
		var LstOrdenP = document.getElementById("selectOrden");
		if (LstOrdenP.length > 0) {
			var selectedOptions = LstOrdenP.selectedOptions;
			var ordenSeleccionadaText = "";
			let cantOrdenes = LstOrden.length;
			for (var i = 0; i < selectedOptions.length; i++) {
				var option = selectedOptions[i];
				ordenSeleccionadaText = option.text;
				switch (ordenSeleccionadaText.substring(0, 4)) {
					case "Lote":
						dataInv.fecha_exp = fechaSistema();
						dataInv.fecha_fab = fechaSistema();
						dataInv.modelo = "Ninguno";

						let stockLote = 0;

						var parametros = {
							"lote_no": ordenSeleccionadaText
						};

						$.ajax({
							type: "POST",
							url: '../controlador/facturacion/facturarC.php?case_lote=true',
							data: { parametros: parametros },
							dataType: 'json',
							success: function (data) {
								// console.log(data);
								if (data.length > 0) {
									dataInv.procedencia = data['procedencia'];
									dataInv.modelo = data['modelo'];
									dataInv.serie_no = data['serie_no'];
									dataInv.fecha_exp = data['fecha_exp'];
									dataInv.fecha_fab = data['fecha_fab'];
									stockLote = data['totStock'];
								}
							}
						});
						break;
					case "Orde":

						let cadena = ordenSeleccionadaText;
						var parametros = {
							'cadena': cadena,
							'cod_cxc': document.getElementById("Cod_CxC"),
							'cta': document.getElementById("Cta_CxP")
						};

						$.ajax({
							type: "POST",
							url: '../controlador/facturacion/facturarC.php?case_orde=true',
							data: { parametros: parametros },
							dataType: 'json',
							success: function (data) {
								// console.log(data);
								if (data != '1') {
									Swal.fire('Se han procesado las ordenes', '', 'info');
								} else {
									Swal.fire('No existen Ã³rdenes para procesar', '', 'error');
								}
							}
						});
						break;
				}

				console.log("Opcion seleccionada: ", ordenSeleccionadaText);
			}
		} else {

			lineas_factura();
		}

	}
</script>

<div id="my_modal_abonos" class="modal" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">INGRESO DE CAJA</h4>
				<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-11">
						<form id="form_abonos">
							<div class="row">
								<div class="col-sm-auto col-auto">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" name="CheqRecibo" id="CheqRecibo" checked>
										<label class="form-check-label" for="CheqRecibo">
											<b>INGRESO CAJA No.</b>
										</label>
									</div>
									
									<!--<label class="control-label"
										style="font-size: 11.5px;padding-right: 0px; white-space: nowrap;"><input type="checkbox"
											name="CheqRecibo" id="CheqRecibo" checked> INGRESO CAJA No.</label>-->
								</div>
								<div class="col-sm-2 col-2">
									<input type="text" aria-label="Recibo" name="TxtRecibo" id="TxtRecibo" class="form-control form-control-sm text-end"
										value="0000000">
								</div>
								<!--<div class="col-sm-2 col-xs-4">
									<input type="text" name="TxtRecibo" id="TxtRecibo" class="form-control input-xs" value="0000000"
										style="padding-right: 0;">
								</div>-->
								<div class="col-sm-3 col-3">
									<div class="input-group input-group-sm">
											<label for="LabelDolares" class="input-group-text"><b>COTIZACION</b></label>
											<input type="text" aria-label="Dolares" name="LabelDolares" id="LabelDolares" class="form-control form-control-sm text-end"
												value="0.00">
									</div>
									<!--<div class="col-sm-6">
										<label for="LabelDolares" style="font-size: 11.5px; padding-top:5px">COTIZACION</label>
									</div>
									<div class="col-sm-6 col-5">
										<input type="text" name="LabelDolares" id="LabelDolares"
											class="form-control input-xs text-right" value="0.00" style="padding:0;">
									</div>-->
								</div>
								<div class="col-sm-4 col-4">
									<div class="input-group input-group-sm">
											<label for="MBFecha" class="input-group-text"><b>FECHA DEL ABONO</b></label>
											<input type="date" aria-label="Fecha" name="MBFecha" id="MBFecha" class="form-control form-control-sm"
												value="<?php echo date('Y-m-d'); ?>">
									</div>
									<!--<div class="col-sm-6" style="padding:0;">
										<label for="MBFecha" style="font-size: 11.5px;">Fecha
											del
											abono</label>
									</div>
									<div class="col-sm-6 col-xs-6" style="padding: 0;">
										<input type="date" name="MBFecha" id="MBFecha" class="form-control input-xs"
											value="<?php //echo date('Y-m-d'); ?>" style="padding:0;">
									</div>-->
								</div>
							</div>
							<div class="row mt-2">
								<div class="col-sm-4 col-4">
									<div class="input-group input-group-sm">
										<label for="DCTipo" class="input-group-text">Tipo de Documento.</label>
										<select class="form-select" id="DCTipo" name="DCTipo" onblur="buscarDCSerie()">
											<option value="FA" selected>FA</option>
										</select>
									</div>
								</div>
								<div class="col-sm-2 col-2">
									<div class="input-group input-group-sm">
										<label for="DCSerie" class="input-group-text">Serie.</label>
										<select class="form-select" id="DCSerie" name="DCSerie" onblur="DCFactura_()">
										</select>
									</div>
								</div>
								<div class="col-sm-3 col-3">
									<div class="input-group input-group-sm">
										<label for="DCFactura" class="input-group-text" id="Label2" name="Label2">No.</label>
										<select class="form-select" id="DCFactura" name="DCFactura" onblur="DCAutorizacionF();DCFactura1()">
										</select>
									</div>
								</div>
								<div class="col-sm-3 col-3">
									<div class="input-group input-group-sm">
										<label for="LabelSaldo" class="input-group-text">Saldo</label>
										<input type="text" aria-label="Saldo" name="LabelSaldo" id="LabelSaldo" class="form-control form-control-sm text-end"
												value="0.00">
									</div>
								</div>
								<!--<div class="col-sm-4 col-4">
									
									<div class="col-sm-6" style="padding:0">
										<label for="DCTipo" style="font-size: 11.5px; white-space: nowrap" class="text-left" for="DCTipo">Tipo
											de
											Documento.</label>
									</div>
									<div class="col-sm-5 col-4">
										<select class="form-control input-xs" id="DCTipo" name="DCTipo" style="padding: 0;" onblur="buscarDCSerie()">
											<option value="FA">FA</option>
										</select>
									</div>
								</div>-->
								<!--<div class="col-sm-3 col-3">
									<div class="col-sm-3">
										<label for="DCSerie" class="text-left">Serie.</label>
									</div>
									<div class="col-sm-9">
										<select class="form-control input-xs" id="DCSerie" name="DCSerie" onblur="DCFactura_()">
										</select>
									</div>
								</div>
								<div class="col-sm-3 col-3">
									<div class="col-sm-4" style="padding:0;">
										<label for="DCFactura" style="font-size: 11.5px; white-space: nowrap;" id="Label2"
											name="Label2">No.</label>
									</div>
									<div class="col-sm-8 col-9" style="padding-right: 0;">
										<select class="form-control input-xs" id="DCFactura" name="DCFactura"
											onblur="DCAutorizacionF();DCFactura1()">
										</select>
									</div>
								</div>
								<div class="col-sm-2 col-2" style="padding:0px">
									<div class="col-sm-4">
										<label for="LabelSaldo">Saldo</label>
									</div>
									<div class="col-sm-8 col-8">
										<input type="text" name="LabelSaldo" id="LabelSaldo" class="form-control input-xs text-right"
											value="0.00">
									</div>
								</div>-->
							</div>
							<div class="row mt-2">
								<div class="col-sm-12 col-10 input-group input-group-sm">
									<label for="DCAutorizacion" class="input-group-text">Autorizacion.</label>
									<select class="form-select" id="DCAutorizacion" name="DCAutorizacion">
										<!--<option value="" selected disabled>Seleccione un vendedor</option>-->
									</select>
								</div>
								<!--<div class="col-sm-12 col-10" style="padding:0px">
									<div class="col-sm-2 col-2">
										<label for="DCAutorizacion">Autorizacion.</label>
									</div>
									<div class="col-sm-10 col-10">
										<select class="form-control input-xs" id="DCAutorizacion" name="DCAutorizacion">
										</select>
									</div>
								</div>-->
							</div>
							<div class="row mt-2">
								<div class="input-group input-group-sm">
									<input type="hidden" name="CodigoC" id="CodigoC" placeholder="Cliente">
									<input type="hidden" name="CI_RUC" id="CI_RUC" style="padding:5px 0px 0px 0px">
									<span class="input-group-text">Cliente</span>
									<input type="text" aria-label="First name" class="form-control form-control-sm w-50" name="LblCliente" id="LblCliente" readonly>
									<span class="input-group-text">Grupo No</span>
									<input type="text" aria-label="Last name" class="form-control form-control-sm" name="LblGrupo" id="LblGrupo" readonly>
								</div>
								<!--<div class="col-sm-9 col-xs-8">
									<input type="text" name="LblCliente" id="LblCliente" class="form-control input-xs"
										placeholder="Cliente">
									<input type="hidden" name="CodigoC" id="CodigoC" class="form-control input-xs"
										placeholder="Cliente">
									<input type="hidden" name="CI_RUC" id="CI_RUC" style="padding:5px 0px 0px 0px">
								</div>
								<div class="col-sm-3 col-xs-4">
									<input type="text" name="LblGrupo" id="LblGrupo" class="form-control input-xs"
										placeholder="Grupo No">
								</div>-->
							</div>


							<div class="row">
								<div class="col-sm-2 col-2">
									<label for="TxtSerieRet" class="col-form-label pb-0">Serie Retencion</label>
									<input type="text" name="TxtSerieRet" id="TxtSerieRet" class="form-control form-control-sm"
										placeholder="001" value="001001">
								</div>
								<div class="col-sm-2 col-2">
									<label for="TextCompRet" class="col-form-label pb-0">Retencion No</label>
									<input type="text" name="TextCompRet" id="TextCompRet" class="form-control form-control-sm text-end"
										placeholder="00000000" value="99999999">
								</div>
								<div class="col-sm-8 col-8">
									<label for="TxtAutoRet" class="col-form-label pb-0" id="LabelAutorizacion">Autorizacion </label>
									<input type="text" name="TxtAutoRet" id="TxtAutoRet" class="form-control form-control-sm"
										placeholder="Grupo No" value="000000000">
								</div>
							</div>


							<div class="row">
								<div class="col-sm-12 col-7">
									<div class="row">
										<div class="col-sm-6 col-8">
											<label for="DCRetIBienes" class="col-form-label pb-0">RETENCION DEL I.V.A. EN BIENES</label>
											<input type="hidden" name="DCRetIBienesNom" id="DCRetIBienesNom">
											<select class="form-select form-select-sm" id="DCRetIBienes" name="DCRetIBienes"
												onchange="$('#DCRetIBienesNom').val($('#DCRetIBienes option:selected').text())"
												placeholder="Retencion en bienes">
											</select>
										</div>
										<div class="col-sm-1" style="padding:0px">
											<label for="CBienes" class="col-form-label pb-0">%</label>
											<select class="form-select form-select-sm" id="CBienes" name="CBienes">
												<option value="0">0</option>
												<option value="10">10</option>
												<option value="30">30</option>
												<option value="100">100</option>
											</select>
										</div>
										<div class="col-sm-5">
											<div class="row">
												<div class="col-sm-12">
													<label for="" style="visibility: hidden;">ESPACIADO</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-7 col-6 text-end">
													<label for="TextRetIVAB" class="col-form-label fs-6"><b>VALOR RETENIDO.</b></label>
												</div>
												<div class="col-sm-5">
													<input type="text" name="TextRetIVAB" id="TextRetIVAB"
														class="form-control text-end" placeholder="0.00" value="0.00"
														onblur="Calculo_Saldo()">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-7">
									<div class="row">
										<div class="col-sm-6 col-6">
											<label for="DCRetISer" class="col-form-label pb-0">RETENCION DEL I.V.A. EN SERVICIO </label>
											<input type="hidden" name="DCRetISerNom" id="DCRetISerNom">
											<select class="form-select form-select-sm" id="DCRetISer" name="DCRetISer"
												onchange="$('#DCRetISerNom').val($('#DCRetISer option:selected').text())">
												<option value="">Retencion en servicios</option>
											</select>
										</div>
										<div class="col-sm-1 col-1" style="padding:0;">
											<label for="CServicio" class="col-form-label pb-0">%</label>
											<select class="form-select form-select-sm" id="CServicio" name="CServicio">
												<option value="0">0</option>
												<option value="20">20</option>
												<option value="70">70</option>
												<option value="100">100</option>
											</select>
										</div>
										<div class="col-sm-5">
											<div class="row">
												<div class="col-sm-12">
													<label for="" style="visibility: hidden;">ESPACIADO</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-7 col-6 text-end">
													<label for="TextRetIVAS" class="col-form-label fs-6"><b>VALOR RETENIDO.</b></label>
												</div>
												<div class="col-sm-5">
													<input type="text" name="TextRetIVAS" id="TextRetIVAS"
														class="form-control text-end" placeholder="0.00"
														onblur="Calculo_Saldo()" value="0.00">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-7">
									<div class="row">
										<div class="col-sm-4 col-7">
											<label for="DCRetFuente" class="col-form-label pb-0">RETENCION EN LA FUENTE</label>
											<select class="form-select form-select-sm" id="DCRetFuente" name="DCRetFuente">
											</select>
										</div>
										<div class="col-sm-2 col-3" style="padding: 0;">
											<label for="DCCodRet" class="col-form-label pb-0">CODIGO</label>
											<select class="form-select form-select-sm" id="DCCodRet" name="DCCodRet">
											</select>
										</div>
										<div class="col-sm-1 col-2" style="padding-right: 0;">
											<label for="TextPorc" class="col-form-label pb-0">%</label>
											<input type="text" name="TextPorc" id="TextPorc" class="form-control form-control-sm"
												placeholder="000">
										</div>
										<div class="col-sm-5">
											<div class="row">
												<div class="col-sm-12">
													<label for="" style="visibility: hidden;">ESPACIADO</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-7 text-end">
													<label for="TextRet" class="col-form-label fs-6"><b>VALOR RETENIDO.</b></label>
												</div>
												<div class="col-sm-5">
													<input type="text" name="TextRet" id="TextRet"
														class="form-control text-end" placeholder="00000000"
														onblur="Calculo_Saldo()" value="0.00">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-8">
									<div class="row">
										<div class="col-sm-5 col-5">
											<label for="DCBancoNom" class="col-form-label pb-0">CUENTA DEL BANCO </label>
											<input type="hidden" name="DCBancoNom" id="DCBancoNom">
											<select class="form-select form-select-sm" id="DCBanco" name="DCBanco"
												onchange="$('#DCBancoNom').val($('#DCBanco option:selected').text())">
											</select>
										</div>
										<div class="col-sm-1 col-3" style="padding:0;">
											<label for="TextCheqNo" class="col-form-label pb-0">CHEQUE </label>
											<input type="text" name="TextCheqNo" id="TextCheqNo" class="form-control form-control-sm"
												placeholder="00000000">
										</div>
										<div class="col-sm-3 col-4">
											<label for="TextBanco" class="col-form-label pb-0">NOMBRE DE BANCO</label>
											<input type="text" name="TextBanco" id="TextBanco" class="form-control form-control-sm">
										</div>
										<div class="col-sm-3">
											<div class="row">
												<div class="col-sm-12">
													<label for="" style="visibility: hidden;">ESPACIADO</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-4 text-end">
													<label for="TextCheque" class="col-form-label fs-6"><b>VALOR.</b></label>
												</div>
												<div class="col-sm-8">
													<input type="text" name="TextCheque" id="TextCheque"
														class="form-control input-xs text-end" placeholder="0.00"
														onblur="Calculo_Saldo()" value="0.00">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-8">
									<div class="row">
										<div class="col-sm-5 col-5">
											<label for="DCTarjeta" class="col-form-label pb-0">TARJETA DE CREDITO</label>
											<input type="hidden" name="DCTarjetaNom" id="DCTarjetaNom">
											<select class="form-select form-select-sm" id="DCTarjeta" name="DCTarjeta"
												onchange="$('#DCTarjetaNom').val($('#DCTarjeta option:selected').text())">
												<option value="">Tarjeta credito</option>
											</select>
										</div>
										<div class="col-sm-2 col-3" style="padding:0;">
											<label for="TextBaucher" class="col-form-label pb-0">BAUCHER</label>
											<input type="text" name="TextBaucher" id="TextBaucher" class="form-control form-control-sm"
												placeholder="00000000">
										</div>
										<div class="col-sm-2 col-4">
											<label for="TextInteres" class="col-form-label pb-0">INTERES TARJETA</label>
											<input type="text" name="TextInteres" id="TextInteres"
												class="form-control form-control-sm text-end" placeholder="00000000" value="0"
												onblur="calcTextInteres()">
										</div>
										<div class="col-sm-3">
											<div class="row">
												<div class="col-sm-12">
													<label for="" style="visibility: hidden;">ESPACIADO</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-4 text-end">
													<label for="TextTotalBaucher" class="col-form-label fs-6"><b>VALOR.</b></label>
												</div>
												<div class="col-sm-8">
													<input type="text" name="TextTotalBaucher" id="TextTotalBaucher"
														class="form-control text-end" placeholder="00000000"
														onblur="Calculo_Saldo()" value="0.00">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-7 col-6">
									<div class="row">
										<div class="row row-cols-auto gy-2 align-items-center" >
											<div class="col-12 form-floating">
												<textarea class="form-control form-control-sm px-2" placeholder="" id="TextObservacion" rows="2" style="resize: none;height:80px"></textarea>
												<label for="TextObservacion">Observacion</label>
											</div>
											<div class="col-12 form-floating">
												<textarea class="form-control form-control-sm px-2" placeholder="" id="TextNota" rows="2" style="resize: none;height:80px"></textarea>
												<label for="TextNota">Nota</label>
											</div>
											<div class="col-12">
												<label for="DCVendedor" class="col-form-label pb-0">Vendedor</label>
												<select class="form-select" id="DCVendedor" name="DCVendedor">
													<!--<option value="" selected disabled>Seleccione un vendedor</option>-->
												</select>
												
											</div>
											<!--<div class="col-12">
												<label for="DCVendedor" class="col-3 col-form-label"><b>Vendedor</b></label>
												<select class="col-9 form-select form-select-sm" id="DCVendedor" name="DCVendedor" aria-label="Select DCVendedor">
													
												</select>
											</div>-->
											<!--<label for="DCVendedor">Vendedor</label>-->
											<!--<textarea placeholder="Observacion" rows="2" style="resize: none;"
												class="form-control input-xs"></textarea>
											<textarea placeholder="Nota" rows="2" style="resize: none;"
												class="form-control input-xs"></textarea>
											<label for="DCVendedor">Vendedor</label>
											<select class="form-control input-xs" id="DCVendedor" name="DCVendedor">
											</select>-->
										</div>
									</div>
								</div>
								<div class="col-sm-5 col-6 mt-3 py-2 border border-3 rounded bg-body-secondary">
									<div class="row pb-1 border-1">
										<label for="TextCajaMN" class="col-6 col-form-label"><b>Caja MN.</b></label>
										<div class="col-6">
											<input type="text" name="TextCajaMN" id="TextCajaMN"
												class="form-control text-end" placeholder="00000000" value="0.00">
										</div>
									</div>
									<div class="row pb-1 border-1">
										<label for="TextCajaME" class="col-6 col-form-label"><b>Caja ME.</b></label>
										<div class="col-6">
											<input type="text" name="TextCajaME" id="TextCajaME"
												class="form-control text-end" placeholder="00000000" value="0.00">
										</div>
									</div>
									<div class="row pb-1 border-1">
										<label for="LabelPend" class="col-6 col-form-label"><b>SALDO ACTUAL.</b></label>
										<div class="col-6">
											<input type="text" name="LabelPend" id="LabelPend"
												class="form-control text-end text-danger" placeholder="00000000" value="0.00">
										</div>
									</div>
									<div class="row pb-1 border-1">
										<label for="TextRecibido" class="col-6 col-form-label"><b>VALOR RECIBIDO.</b></label>
										<div class="col-6">
											<input type="text" name="TextRecibido" id="TextRecibido"
												class="form-control text-end" placeholder="00000000" value="0.00">
										</div>
									</div>
									<div class="row pb-1 border-1">
										<label for="LabelCambio"
											class="col-6 col-form-label"><b>CAMBIO A ENTREGAR.</b></label>
										<div class="col-6 ">
											<input type="text" name="LabelCambio" id="LabelCambio"
												class="form-control text-end text-danger" placeholder="00000000" value="0.00">
										</div>
									</div>

								</div>
							</div>
							<input type="hidden" name="Cta_Cobrar" id="Cta_Cobrar">
						</form>
					</div>
					<div class="col-sm-1 ps-0">
						<button class="btn btn-light border border-2" id="btn_g" onclick="guardar_abonos();"> <img
								src="../../img/png/grabar.png"><br>&nbsp;Guardar&nbsp;</button>
						<!-- <button type="button" class="btn btn-light" data-dismiss="modal">Cerrar</button> --><br> <br>
						<button class="btn btn-light border border-2" onclick="cerrar_modal();"> <img src="../../img/png/bloqueo.png"><br>
							Cancelar</button>
					</div>
				</div>
				<!--<iframe src="" id="frame" width="100%" height="560px" marginheight="0" frameborder="0"></iframe>-->
			</div>
		</div>

	</div>
</div>

<div id="my_modal_abono_anticipado" class="modal fade" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">INGRESO DE ABONOS ANTICIPADOS</h4>
				<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<!--<iframe src="" id="frame_anticipado" width="100%" height="500px" marginheight="0"
					frameborder="0"></iframe>-->
				<div class="row">
					<div class="col-sm-10">
						<form id="form_abonos_anti">
							<div class="row align-items-center mb-2">
								<div class="col-sm-auto">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="CheqRecibo" checked>
										<label class="form-check-label" for="CheqRecibo">RECIBO CAJA No.</label>
									</div>
								</div>
								<div class="col-sm-2 ps-0">
									<input type="" class="form-control form-control-sm" id="TxtRecibo" value="0">
									
								</div>
								<div class="col-sm-4">
									<div class="input-group input-group-sm">
										<label class="input-group-text" for="MBFecha">FECHA</label>
										<input class="form-control form-control-sm" type="date" class="form-control" id="MBFecha" name="MBFecha"
											value="<?php echo date('Y-m-d'); ?>">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12" style="display: none;" id="Frame1">
									<div class="panel panel-default">
										<div class="panel-heading">
										</div>
										<div class="panel-body">
	
											<div class="row">
												<div class="col-sm-6">
													<div class="input-group">
														<label class="input-group-addon" for="DCTipo" style="color: red;">TIPO</label>
														<select class="form-control" id="DCTipo" name="DCTipo" style="width: 100%;" onchange="Listar_Facturas_Pendientes()">
															<option value="">Seleccione</option>
														</select>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="input-group">
														<label class="input-group-addon" for="DCFactura">Factura No.</label>
														<select class="form-control" id="DCFactura" name="DCFactura" style="width: 100%;">
															<option value="">Factura</option>
														</select>
													</div>
												</div>
											</div>
											<div style="padding: 5px;"></div>
											<div class="row">
												<div class="col-sm-5">
													<div class="form-control">
														<label  id="Label4">FECHA DE EMISION</label>
													</div>
												</div>
												<div class="col-sm-3">                               
													<div class="form-control">
														<label  id="Label8"></label>
													</div>                              
												</div>
												<div class="col-sm-4">
													<div class="form-control" style="background-color: red;">
														<label id="Label1" ></label>
													</div>
												</div>
											</div>
											<div style="padding: 5px;"></div>
											<div class="row">
												<div class="col-sm-12">
													<div class="form-control">
														<label id="Label3"></label>
													</div>
												</div>
											</div>
											<div style="padding: 5px;"></div>
											<div class="row">
												<div class="col-sm-6">
													<div class="form-control">
														<label id="Label6">Saldo Pendiente</label>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-control">
														<label id="LabelSaldo"></label>
													</div>
												</div>
											</div>
											<div style="padding: 5px;"></div>
											<div class="row">
												<div class="col-sm-12" >
													<div class="form-control " style="padding-bottom: 50px;">
														<label id="LblObs" style="color: violet;">Observacion</label>
													</div>
												</div>
											</div>
											<div style="padding: 5px;"></div>
											<div class="row">
												<div class="col-sm-12">
													<div class="form-control " style="padding-bottom: 50px;">
														<label id="LblNota" style="color: violet;">Nota</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-12" id="Frame2">
									<div class="card">
										<div class="card-body">
											<h3 class="card-title">Abono Anticipado</h3>
											<div class="row mb-2">
												<div class="col-sm-12">
													<div class="input-group input-group-sm">
														<label class="input-group-text" for="DCCliente">Cliente</label>
														<select class="form-select form-select-sm" id="DCClientes" name="DCCliente">
															<option value="">Seleccione</option>
														</select>
													</div>
												</div>
											</div>
											<div class="row mb-2">
												<div class="col-sm-12">
													<label for="TxtConcepto" class="text-primary">USTED ESTA INGRESANDO ABONOS ANTICIPADOS, SE EMITIRA UN COMPROBANTE DE INGRESO DE RESPALDO A SU ABONO</label>
													<textarea class="form-control" id="TxtConcepto" rows="3"></textarea>
													
												</div>
											</div>
											<div class="row mb-2">
												<div class="col-sm-12">
													<label for="DCBanco">Cuenta Contable del Ingreso</label>
													<select class="form-select form-select-sm" id="DCBanco" name="DCBanco"
														style="width: 100%;">
														<option value="">Banco</option>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<label for="DCCtaAnt">Cuenta Contable de Anticipo</label>
													<select class="form-select form-select-sm" id="DCCtaAnt" name="DCCtaAnt"
														style="width: 100%;">
														<option value="">Banco</option>
													</select>
													
												</div>
											</div>
										</div>

									</div>
									<!-- <div class="panel panel-default">
										<div class="panel-heading">
										</div>
										<div class="panel-body">
	
										</div>
									</div> -->
								</div>
							</div>
							
							<div class="offset-sm-6 col-sm-6">
								<div class="input-group input-group-sm">
									<label for="TextCajaMN"  class="input-group-text" style="width:50%">Caja MN.</label>
									<input type="text" class="form-control form-control-sm" id="TextCajaMN" placeholder="00000000" value="0.00">
									
								</div>
								<div class="input-group input-group-sm">
									<label for="LabelPend"  class="input-group-text" id="Label10" style="width:50%">Saldo Actual</label>
									<input type="text" class="form-control form-control-sm" id="LabelPend" placeholder="00000000" value="0.00">
									
								</div>
							</div>
						</form>
					</div>
					<div class="col-sm-1">
						<button class="btn btn-outline-secondary btn-block mb-1" id="btn_g" onclick="Command1_Click()">
							<img src="../../img/png/grabar.png"><br>&nbsp;Guardar&nbsp;
						</button>
						<button class="btn btn-outline-secondary btn-block" onclick="cerrar_modal()">
							<img src="../../img/png/bloqueo.png"><br>Cancelar
						</button>
					</div>
				</div>
			</div>
			<div class="modal-footer"> </div>
		</div>
	</div>
</div>