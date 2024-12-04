<?php date_default_timezone_set('America/Guayaquil'); //print_r($_SESSION);die();//print_r($_SESSION['INGRESO']);die();
$servicio = $_SESSION['INGRESO']['Servicio'];
?>
<script src="../../dist/js/facturar.js"></script>
<style>
	*{
		box-sizing: border-box;
	}
	body {
		padding-right: 0px !important;
	}
	@media screen and (max-width: 600px) {
			.table{
				border: 0px;
			}
			.table caption {
				font-size: 14px;
			}
			.table thead{
				display: none;
			}
			.table tr{
				margin-bottom: 8px;
				border-bottom: 4px solid #ddd;
				display: block;
			}
			.table th, .table td{
				font-size: 12px;
			}
			.table td{
				display: block;
				border-bottom: 1px solid #ddd;
				text-align: right;
			}
			.table td:last-child{
				border-bottom: 0px;
			}
			.table td::before{
				content: attr(data-label);
				font-weight: bold;
				text-transform: uppercase;
				float: left;
			}
		}
</style>
<!--<div id="interfaz_facturacion" style="display:flex; flex-direction:column; min-height:inherit;">-->
<div id="interfaz_facturacion">

	<div class="interfaz_botones">
		<!--<div class="row row-no-gutters">-->
			<?php
				function createButton($title, $imagePath, $onclickFunction, $id)
				{
					echo '
							<button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" id="' . $id . '" title="' . $title . '" onclick="' . $onclickFunction . '">
								<img src="' . $imagePath . '">
							</button>
						';
				}
			?>
			
			<div class="row row-cols-auto gx-3 pb-2 d-flex align-items-center ps-2">
				

				
				<div class="row row-cols-auto btn-group">
					<a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
					print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
						<img src="../../img/png/salire.png">
					</a>
				

                    <?php createButton("Grabar factura", "../../img/png/grabar.png", "boton1()", "btnGrabar"); ?>
                    <?php createButton("Actualizar Productos, Marcas y Bodegas", "../../img/png/update.png", "boton2()", "btnActualizar"); ?>
                    <?php createButton("Asignar orden de trabajo", "../../img/png/taskboard.png", "boton3()", "btnOrden"); ?>
                    <?php createButton("Asignar guía de remisión", "../../img/png/ats.png", "boton4()", "btnGuia"); ?>
                    <?php createButton("Asignar suscripción/contrato", "../../img/png/file2.png", "boton5()", "btnSuscripcion"); ?>
                    <?php createButton("Asignar reserva", "../../img/png/archivero2.png", "boton6()", "btnReserva"); ?>
                    <!-- Example of a commented-out button
                    <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
                        <button type="button" class="btn btn-default" title="Grabar factura" onclick="boton1()"><img
                                src="../../img/png/grabar.png"></button>
                    </div>
                    <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
                        <button type="button" class="btn btn-default" title="Actualizar Productos, Marcas y Bodegas"
                            onclick="boton2()"><img src="../../img/png/update.png"></button>
                    </div>
                    <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
                        <button type="button" class="btn btn-default" title="Asignar orden de trabajo" onclick="boton3()"><img
                                src="../../img/png/taskboard.png"></button>
                    </div>
                    <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
                        <button type="button" class="btn btn-default" title="Asignar guia de remision" onclick="boton4()"><img
                                src="../../img/png/ats.png"></button>
                    </div>
                    <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
                        <button type="button" class="btn btn-default" title="Asignar suscripcion / contrato" onclick="boton5()"><img
                                src="../../img/png/file2.png"></button>
                    </div>
                    <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
                        <button id="btnReserva" type="button" class="btn btn-default" title="Asignar reserva"
                            onclick="boton6()"><img src="../../img/png/archivero2.png"></button>
                    </div>
                    <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
                        <a href="#" class="btn btn-default" title="Asignar reserva" onclick="Autorizar_Factura_Actual2();" target="_blank" ><img src="../../img/png/archivero2.png"></a>
                    </div> -->
                </div>
                <div class="row row-cols-auto col-7">
                    <div class="col-6">
						<b >Emision</b><br>
                        <input type="date" name="MBoxFecha" id="MBoxFecha" class="form-control form-control-sm h-25" min="01-01-2000" max="31-12-2050"
                            value="<?php echo date('Y-m-d'); ?>" onblur="DCPorcenIva('MBoxFecha', 'DCPorcenIVA');">
						<!--<div class="col-lg-8 col-sm-12" style="padding-right: 0px">
						</div>-->
					</div>
					<div class="col-6">
						<b >Vencimiento</b><br>
                        <input type="date" name="MBoxFechaV" id="MBoxFechaV" class="form-control form-control-sm h-25" min="01-01-2000" max="31-12-2050"
                            value="<?php echo date('Y-m-d'); ?>">
						<!--<div class="col-lg-6 col-sm-12" style="padding-right: 0px">
						</div>-->
					</div>
                </div>
            </div>
			<!--<div class="col-lg-3 col-sm-10 col-md-6 col-xs-12">-->
				<?php //createButton("Asignar guía de remisión", "../../img/png/ats.png", "boton4()", "btnGuia",'2','2','1','4'); ?>
				<?php //createButton("Asignar suscripción/contrato", "../../img/png/file2.png", "boton5()", "btnSuscripcion",'2','2','1','4'); ?>
				<?php //createButton("Asignar reserva", "../../img/png/archivero2.png", "boton6()", "btnReserva",'2','2','1','4'); ?>
			<!--</div>-->
			
		<!--</div>-->
	</div>


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
				<div class="row row-cols-auto px-2">
					<div class="col-sm-2 col-6 d-flex align-items-center pe-0">
						<label><input type="checkbox" name="Check1" id="Check1"> Factura en ME</label>
					</div>
					<div class="col-sm-2 col-6 d-flex align-items-center p-0 invisible" id="CheqSPFrom">
						<label><input type="checkbox" name="CheqSP" id="CheqSP"> Sector publico</label>
					</div>
					<div class="offset-sm-0 col-sm-2 offset-1 col-10 p-0">
						<b>Orden Compra No</b>
						<input type="" name="TxtCompra" id="TxtCompra" class="form-control form-control-sm text-end" value="0">
						
					</div>
					<div class="offset-sm-0 col-sm-5 offset-1 col-10 d-flex align-items-end">
						<select class="form-select form-select-sm" id="DCMod" name="DCMod">
							<option value="">Seleccione</option>
						</select>
					</div>
					<div class="offset-sm-0 col-sm-1 offset-1 col-10 d-flex align-items-end">
						<input type="text" name="LabelCodigo" id="LabelCodigo" class="form-control form-control-sm" readonly=""
							value=".">
					</div>
				</div>
				<div class="row px-2 pb-2">
					
					<div class="offset-lg-0 col-lg-4 offset-sm-0 col-sm-4 offset-1 col-10">
						<b>Cuenta x Cobrar</b>
						<div class="col-lg-12 col-sm-12 col-12 pe-0">
							<select class="form-select form-select-sm" id="DCLineas" name="DCLineas"
								onblur="DCLinea_LostFocus()">
								<option value="">Seleccione</option>
							</select>

							<input type="hidden" name="DCLineasV" id="DCLineasV">
						</div>
					</div>
					<div class="offset-1 col-lg-7 offset-sm-1 col-sm-7 col-12 d-flex align-items-end">
						<div class="row row-cols-auto">
							<div>
								<b style="color:red" id="label2">0000000000000 NOTA DE VENTA No. 001001-</b>
							</div>
							<div class="col-lg-3 col-sm-12 col-5 ps-2">
								<input type="text" name="TextFacturaNo" id="TextFacturaNo" class="form-control form-control-sm py-0 px-2"
									value="000000">
							</div>
						</div>
					</div>
					<!--<div class="col-sm-4">
						<b class="col-sm-4 control-label" style="padding: 0px">Saldo pendiente</b>
						<div class="col-sm-6">
							<input type="text" name="LblSaldo" id="LblSaldo" class="form-control input-xs" value="0.00"
								readonly>
						</div>
					</div>-->
				</div>
				<div class="row row-cols-auto px-2 pb-2">
					<div class="col-12 col-sm-6 col-lg-7">
						<b>Tipo de pago</b>
						<select class="form-select form-select-sm" id="DCTipoPago" name="DCTipoPago">
							<option value="">Seleccione</option>
						</select>
						<!--<div class="col-sm-9 col-lg-10">
						</div>-->
					</div>
					<div class="col-4 col-sm-1 col-lg-1">
						<!--<div class="col-lg-offset-2 col-lg-4 col-sm-4 col-xs-5 text-right" style="padding: 0">-->
							<b><label for="DCPorcenIVA">I.V.A:</label></b>
						<!--</div>-->
						<!--<div class="col-sm-8 col-lg-6 col-xs-12" style="padding-right:0;padding-left:10px;">-->
							<select class="form-select form-select-sm" name="DCPorcenIVA" id="DCPorcenIVA" onblur="cambiar_iva(this.value)"> </select>
						<!--</div>-->
					</div>
					<div>
						<b>Saldo pendiente</b>
						<input type="text" name="LblSaldo" id="LblSaldo" class="form-control form-control-sm text-end" value="0.00" style="text-align:right;"
							readonly>
						
					</div>
				</div>
				<div class="row row-cols-auto px-2 pb-2">
					<!--<div class="col-sm-3">
						<b>Grupo</b>
						<select class="form-control input-xs" id="DCGrupo_No" name="DCGrupo_No"
							onchange="autocomplete_cliente()">
							<option value="">Seleccione</option>
						</select>
					</div>
					<div class="col-sm-9">
						<b>Cliente</b>
						<div class="input-group">
							<select class="form-control input-xs" id="DCCliente" name="DCCliente">
								<option value="">Seleccione</option>
							</select>
							<span class="input-group-btn">
								<button type="button" class="btn btn-info btn-flat btn-xs" onclick="addCliente();"><i
										class="fa fa-plus"></i></button>
							</span>
						</div>
					</div>-->
					<div class="col-sm-3 col-md-3">
						<b>Grupo</b>
						<select class="form-select form-select-sm" id="DCGrupo_No" name="DCGrupo_No"
							onchange="autocomplete_cliente()">
							<option value="">Seleccione</option>
						</select>
					</div>
					<div class="col-sm-9 col-md-9">
						<b>Cliente</b>
						<!--<div class="col-sm-10 col-md-10 input-group" style="padding-left: 8px;">-->
						<div class="input-group">
							<select class="form-select form-select-sm" id="DCCliente" name="DCCliente">
								<option value="">Seleccione</option>
							</select>
							<button class="btn btn-primary btn-sm d-flex align-items-center" type="button" onclick="addCliente();"><i
									class="bx bx-plus"></i></button>
							<!--<span class="input-group-btn">
								<button type="button" class="btn btn-info btn-flat btn-xs" onclick="addCliente();"><i
										class="fa fa-plus"></i></button>
							</span>-->
						</div>
					</div>
				</div>
				<div class="row row-cols-auto px-2 pb-2">
					<!--<div class="col-sm-8">
						<b>ACTUALICE SU CORREO ELECTRONICO</b>
						<input type="text" name="TxtEmail" id="TxtEmail" class="form-control input-xs">
					</div>
					<div class="col-sm-2">
						<b id="Label13">C.I / R.U.C</b>
						<input type="text" name="LabelRUC" id="LabelRUC" class="form-control input-xs" readonly=""
							value=".">
					</div>
					<div class="col-sm-2">
						<b>Telefono</b>
						<input type="text" name="LabelTelefono" id="LabelTelefono" class="form-control input-xs" readonly=""
							value=".">
					</div>-->
					<div class="col-sm-6 col-12">
						<b class="control-label">ACTUALICE SU CORREO</b>
						<input type="text" name="TxtEmail" id="TxtEmail" class="form-control form-control-sm">
					</div>
					<div class="col-sm-3 col-6">
						<b id="Label13" class="control-label">C.I / R.U.C</b>
						<input type="text" name="LabelRUC" id="LabelRUC" class="form-control form-control-sm" readonly=""
							value=".">
					</div>
					<div class="col-sm-3 col-6">
						<b class="control-label">Telefono</b>
						<input type="text" name="LabelTelefono" id="LabelTelefono" class="form-control form-control-sm" readonly=""
							value=".">
					</div>
					
				</div>
				<!--<div class="row">
					<div class="col-sm-4">
						<b class="col-sm-4 control-label" style="padding: 0px">Cuenta x Cobrar</b>
						<div class="col-sm-8" style="padding: 0px">
							<select class="form-control input-xs" id="DCLineas" name="DCLineas"
								onblur="DCLinea_LostFocus()">
								<option value="">Seleccione</option>
							</select>

							<input type="hidden" name="DCLineasV" id="DCLineasV">
						</div>
					</div>
					<div class="col-sm-4">
						<div class="row">
							<div class="col-sm-9" style="padding-right: 0px;">
								<b style="color:red" id="label2">0000000000000 NOTA DE VENTA No. 001001-</b>
							</div>
							<div class="col-sm-3" style="padding-left: 0px;">
								<input type="text" name="TextFacturaNo" id="TextFacturaNo" class="form-control input-xs"
									value="0">
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<b class="col-sm-4 control-label" style="padding: 0px">Saldo pendiente</b>
						<div class="col-sm-6">
							<input type="text" name="LblSaldo" id="LblSaldo" class="form-control input-xs" value="0.00"
								readonly>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3">
						<b class="col-sm-5 control-label" style="padding: 0px">Fecha Emision</b>
						<div class="col-sm-7" style="padding: 0px">
							<input type="date" name="MBoxFecha" id="MBoxFecha" class="form-control input-xs"
								value="<?php //echo date('Y-m-d'); ?>" onblur="DCPorcenIva('MBoxFecha', 'DCPorcenIVA');">
						</div>
					</div>
					<div class="col-sm-3">
						<b class="col-sm-6 control-label" style="padding: 0px">Fecha Vencimiento</b>
						<div class="col-sm-6" style="padding: 0px">
							<input type="date" name="MBoxFechaV" id="MBoxFechaV" class="form-control input-xs"
								value="<?php //echo date('Y-m-d'); ?>">
						</div>
					</div>
					<div class="col-sm-2">
						<div class="col-sm-4 text-right" style="padding: 0">
							<label for="DCPorcenIVA">I.V.A:</label>
						</div>
						<div class="col-sm-8">
							<select class="form-control input-xs" name="DCPorcenIVA" id="DCPorcenIVA" onblur="cambiar_iva(this.value)"> </select>
						</div>
					</div>
					<div class="col-sm-4">
						<b class="col-sm-3 control-label" style="padding: 0px">Tipo de pago</b>
						<div class="col-sm-8">
							<select class="form-control input-xs" id="DCTipoPago" name="DCTipoPago">
								<option value="">Seleccione</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-2">
						<b>Grupo</b>
						<select class="form-control input-xs" id="DCGrupo_No" name="DCGrupo_No"
							onchange="autocomplete_cliente()">
							<option value="">Seleccione</option>
						</select>
					</div>
					<div class="col-sm-3">
						<b>Cliente</b>
						<div class="input-group">
							<select class="form-control input-xs" id="DCCliente" name="DCCliente">
								<option value="">Seleccione</option>
							</select>
							<span class="input-group-btn">
								<button type="button" class="btn btn-info btn-flat btn-xs" onclick="addCliente();"><i
										class="fa fa-plus"></i></button>
							</span>
						</div>
					</div>
					<div class="col-sm-2">
						<b id="Label13">C.I / R.U.C</b>
						<input type="text" name="LabelRUC" id="LabelRUC" class="form-control input-xs" readonly=""
							value=".">
					</div>
					<div class="col-sm-2">
						<b>Telefono</b>
						<input type="text" name="LabelTelefono" id="LabelTelefono" class="form-control input-xs" readonly=""
							value=".">
					</div>
					<div class="col-sm-3">
						<b>ACTUALICE SU CORREO ELECTRONICO</b>
						<input type="text" name="TxtEmail" id="TxtEmail" class="form-control input-xs">
					</div>
				</div>-->
				<div class="row row-cols-auto px-2 pb-2">
					<div class="col-sm-6 col-12">
						<b class="control-label">Direccion</b>
						<input type="text" name="Label24" id="Label24" class="form-control form-control-sm" value="" readonly="">
						
					</div>
					<div class="col-sm-2 col-12">
						<b class="col-sm-2 control-label" style="padding:0;">No</b>
						<input type="text" name="Label21" id="Label21" class="form-control form-control-sm" value="" readonly="">
						
					</div>
					<div class="offset-sm-0 col-sm-4 offset-1 col-11">
						<select class="form-select form-select-sm" id="DCMedico" name="DCMedico">
							<option value="">Seleccione</option>
						</select>
					</div>
				</div>
				<!--<div class="row">
					<div class="col-sm-6">
						<b class="col-sm-2 control-label" style="padding:0;">Direccion</b>
						<div class="col-sm-10" style="padding-right:0px">
							<input type="text" name="Label24" id="Label24" class="form-control input-xs" value="QUINTO AÑO DE EDUCACIÓN GENERAL BASICA B">
						</div>
					</div>
					<div class="col-sm-2">
						<b class="col-sm-2 control-label" style="padding:0;">No</b>
						<div class="col-sm-10" style="padding:0px">
							<input type="text" name="Label21" id="Label21" class="form-control input-xs" value="" readonly="">
						</div>
					</div>
					<div class="col-sm-4"> 
						<select class="form-control input-xs" id="DCMedico" name="DCMedico">
							
						<option value="1801096718001-R-BI01096718">POZO AVALOS LUIS FERNANDO</option></select>
					</div>
				</div>-->
				<div class="row row-cols-auto px-2 pb-2">
					<div class="col-sm-6">
						<div id="DCEjecutivoFrom">
							<b class="control-label"><input type="checkbox" name=""> Ejecutivo
								de
								venta</b>
							<select class="form-select form-select-sm" name="DCEjecutivo" id="DCEjecutivo">
								<option value="">Seleccione</option>
							</select>
							
						</div>
					</div>
					<div class="col-sm-2">
						<div id="TextComisionForm" style="display:none;">
							<b class="control-label">comision%</b>
							<input type="text" name="TextComision" id="TextComision" value="0"
								class="form-control form-control-sm text-end">
							<div class="col-sm-7">
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<b class="control-label">Bodega</b>
						<select class="form-select form-select-sm" name="DCBodega" id="DCBodega">
							<option value="">Seleccione</option>
						</select>
					</div>
				</div>
				<!--<div class="row">
					<div class="col-sm-6">
						<b>Observacion</b>
						<input type="text" name="TextObs" id="TextObs" class="form-control input-xs">
					</div>
					<div class="col-sm-6">
						<b>Nota</b>
						<input type="text" name="TextNota" id="TextNota" class="form-control input-xs">
					</div>
				</div>-->
				<div class="row" style="margin-top:5px;">
					<div class="col-sm-12">
						<b class="col-sm-1 control-label" style="padding:0;">Observacion</b>
						<div class="col-sm-11" style="padding-right:0px">
							<input type="text" name="TextObs" id="TextObs" class="form-control input-xs">
						</div>
					</div>
				</div>
				<div class="row" style="margin-top:5px;">
					<div class="col-sm-12">
						<b class="col-sm-1 control-label" style="padding:0;">Nota</b>
						<div class="col-sm-11" style="padding-right:0px">
							<input type="text" name="TextNota" id="TextNota" class="form-control input-xs">
						</div>
					</div>
				</div>
				<div class="row box box-success" style="padding-bottom: 7px; margin-left: 0px; margin-bottom:0; margin-top:5px;">
					<div class="col-sm-4 col-lg-2">
						<b>Marca</b>
						<select class="form-control input-xs" id="DCMarca" name="DCMarca">
							<option value="">Seleccione</option>
						</select>
					</div>
					<div class="col-sm-8 col-lg-4">
						<b id="LabelStockArt">Producto</b>
						<select class="form-control input-xs" name="DCArticulos" id="DCArticulos"
							onchange="DCArticulo_LostFocus()">
							<option value="">Seleccione</option>
						</select>
					</div>
					<div class="col-sm-2 col-lg-1">
						<b>Stock</b>
						<input type="text" name="LabelStock" id="LabelStock" class="form-control input-xs" readonly="" style="text-align: right;">
					</div>
					<div class="col-sm-2 col-lg-1">
						<b>Ord./lote</b>
						<input type="text" name="TextComEjec" id="TextComEjec" class="form-control input-xs" style="text-align: right;">
					</div>
					<div class="col-sm-2 col-lg-1">
						<b>Desc%</b>
						<select class="form-control input-xs" id="CDesc1" name="CDesc1" style="text-align: right;">
							<option value="">Seleccione</option>
						</select>
					</div>
					<div class="col-sm-2 col-lg-1">
						<b>Cantidad</b>
						<input type="text" name="TextCant" id="TextCant" class="form-control input-xs" onblur="" value="0" style="text-align: right;">
					</div>
					<div class="col-sm-2 col-lg-1">
						<b>P.V.P</b>
						<input type="text" name="TextVUnit" id="TextVUnit" class="form-control input-xs"
							onblur="TextVUnit_LostFocus(); TextCant_Change();" value="0" style="text-align: right;">
					</div>
					<div class="col-sm-2 col-lg-1">
						<b>TOTAL</b>
						<input type="text" name="LabelVTotal" id="LabelVTotal" class="form-control input-xs" readonly=""
							value="0" style="text-align: right;">
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="interfaz_tabla" id="interfaz_tabla" style="flex-grow:1;padding-top:10px">
		<div id="tbl">

		</div>
		
	</div>

	<div class="interfaz_totales">
		<div class="row">
			<div class="col-sm-12">
				<div class="col-sm-2 col-lg-1" style="padding: 2px">
					<b style="letter-spacing: -1.0px;">Total sin Iva</b>
					<input type="text" name="LabelSubTotal" id="LabelSubTotal" class="form-control input-xs" style="text-align: right;">
				</div>
				<div class="col-sm-2 col-lg-1" style="padding: 2px">
					<b>Total con IVA</b>
					<input type="text" name="LabelConIVA" id="LabelConIVA" class="form-control input-xs" style="text-align: right;">
				</div>
				<div class="col-sm-2 col-lg-1" style="padding: 2px">
					<b>Total Desc</b>
					<input type="text" name="TextDesc" id="TextDesc" class="form-control input-xs" style="text-align: right;">
				</div>
				<div class="col-sm-2 col-lg-1" style="padding: 2px">
					<b id="label36"></b>
					<input type="text" name="LabelServ" id="LabelServ" class="form-control input-xs" style="text-align: right;">
				</div>
				<div class="col-sm-2 col-lg-1" style="padding: 2px">
					<b id="label3">I.V.A</b>
					<input type="text" name="LabelIVA" id="LabelIVA" class="form-control input-xs" style="text-align: right;">
				</div>
				<div class="col-sm-2 col-lg-2" style="padding: 2px">
					<b>Total Facturado</b>
					<input type="text" name="LabelTotal" id="LabelTotal" class="form-control input-xs" style="text-align: right;">
				</div>
				<div class="col-sm-offset-8 col-sm-4 col-lg-offset-0 col-lg-5">
					<!-- <b>P.V.P</b> -->
					<br>
					<input type="text" name="LblGuia" id="LblGuia" class="form-control input-xs" readonly>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="cambiar_nombre" role="dialog" data-keyboard="false" data-backdrop="static" tabindex="-1">
	<div class="modal-dialog modal-dialog modal-dialog-centered modal-sm"
		style="margin-left: 25%; margin-top: 30%;">
		<div class="modal-content">
			<div class="modal-body text-center">
				<textarea class="form-control" style="resize: none;" rows="4" id="TxtDetalle" name="TxtDetalle"
					onblur="cerrar_modal_cambio_nombre()"></textarea>
			</div>
		</div>
	</div>
</div>

<!-- Modal cliente nuevo -->
<div id="myModal_guia" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-md" style="width: 30%;min-width:350px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">DATOS DE GUIA DE REMISION</h4>
			</div>
			<div class="modal-body">
				<form id="form_guia">
					<div class="row">
						<div class="col-sm-12">
							<b class="col-sm-6 control-label" style="padding: 0px">Fecha de emisión de guía</b>
							<div class="col-sm-6" style="padding: 0px">
								<input type="date" name="MBoxFechaGRE" id="MBoxFechaGRE" class="form-control input-xs"
									value="<?php echo date('Y-m-d'); ?>" onblur="MBoxFechaGRE_LostFocus()">
							</div>
						</div>
						<div class="col-sm-12" style="padding-top:5px">
							<b class="col-sm-6 control-label" style="padding: 0px">Guía de remisión No.</b>
							<div class="col-sm-3" style="padding: 0px">
								<select class="form-control input-xs" id="DCSerieGR" name="DCSerieGR"
									onblur="DCSerieGR_LostFocus()">
									<option value="">No Existe</option>
								</select>
							</div>
							<div class="col-sm-3" style="padding: 0px">
								<input type="text" name="LblGuiaR" id="LblGuiaR" class="form-control input-xs"
									value="000000">
							</div>
						</div>
						<div class="col-sm-12">
							<b>AUTORIZACION GUIA DE REMISION</b>
							<input type="text" name="LblAutGuiaRem" id="LblAutGuiaRem" class="form-control input-xs"
								value="0">
						</div>
						<div class="col-sm-12" style="padding-top:5px">
							<b class="col-sm-6 control-label" style="padding: 0px">Iniciación del traslados</b>
							<div class="col-sm-6" style="padding: 0px">
								<input type="date" name="MBoxFechaGRI" id="MBoxFechaGRI" class="form-control input-xs"
									value="<?php echo date('Y-m-d'); ?>">
							</div>
						</div>
						<div class="col-sm-12" style="padding-top:5px">
							<b class="col-sm-3 control-label" style="padding: 0px">Ciudad</b>
							<div class="col-sm-9" style="padding: 0px">
								<select class="form-control input-xs" style="width:100%" id="DCCiudadI" name="DCCiudadI">
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-sm-12" style="padding-top:5px">
							<b class="col-sm-6 control-label" style="padding: 0px">Finalización del traslados</b>
							<div class="col-sm-6" style="padding: 0px">
								<input type="date" name="MBoxFechaGRF" id="MBoxFechaGRF" class="form-control input-xs"
									value="<?php echo date('Y-m-d'); ?>">
							</div>
						</div>
						<div class="col-sm-12" style="padding-top:5px">
							<b class="col-sm-3 control-label" style="padding: 0px">Ciudad</b>
							<div class="col-sm-9" style="padding: 0px">
								<select class="form-control input-xs" style="width:100%" id="DCCiudadF" name="DCCiudadF">
									<option value=""></option>
								</select>
							</div>
						</div>
						<div class="col-sm-12" style="padding-top:5px">
							<b>Nombre o razón social (Transportista)</b>
							<select class="form-control input-xs" style="width:100%" id="DCRazonSocial"
								name="DCRazonSocial">
								<option value=""></option>
							</select>
						</div>
						<div class="col-sm-12" style="padding-top:5px">
							<b>Empresa de Transporte</b>
							<select class="form-control input-xs" style="width:100%" id="DCEmpresaEntrega"
								name="DCEmpresaEntrega">
								<option value=""></option>
							</select>
						</div>
						<div class="col-sm-4">
							<b>Placa</b>
							<input type="text" name="TxtPlaca" id="TxtPlaca" class="form-control input-xs" value="XXX-999">
						</div>
						<div class="col-sm-4">
							<b>Pedido</b>
							<input type="text" name="TxtPedido" id="TxtPedido" class="form-control input-xs">
						</div>
						<div class="col-sm-4">
							<b>Zona</b>
							<input type="text" name="TxtZona" id="TxtZona" class="form-control input-xs">
						</div>
						<div class="col-sm-12">
							<b>Lugar entrega</b>
							<input type="text" name="TxtLugarEntrega" id="TxtLugarEntrega" class="form-control input-xs">
						</div>
					</div>
				</form>


			</div>
			<div class="modal-footer">
				<button class="btn btn-primary btn-block" onclick="Command8_Click();">Aceptar</button>
				<button type="button" class="btn btn-default btn-block" data-dismiss="modal">Cerrar</button>
			</div>
		</div>

	</div>
</div>

<!--script type="text/javascript">
	function Command8_Click() {
		if ($('#DCCiudadI').val() == '' || $('#DCCiudadF').val() == '' || $('#DCRazonSocial').val() == '' || $('#DCEmpresaEntrega').val() == '') {
			swal.fire('Llene todo los campos', '', 'info');
			return false;
		}
		$('#ClaveAcceso_GR').val('.');
		$('#Autorizacion_GR').val($('#LblAutGuiaRem').val());
		var DCserie = $('#DCSerieGR').val();
		if (DCserie == '') { DCserie = '0_0'; }
		var serie = DCserie.split('_');
		$('#Serie_GR').val(serie[1]);
		$('#Remision').val($('#LblGuiaR').val());
		$('#FechaGRE').val($('#MBoxFechaGRE').val());
		$('#FechaGRI').val($('#MBoxFechaGRI').val());
		$('#FechaGRF').val($('#MBoxFechaGRF').val());
		$('#Placa_Vehiculo').val($('#TxtPlaca').val());
		$('#Lugar_Entrega').val($('#TxtLugarEntrega').val());
		$('#Zona').val($('#TxtZona').val());
		$('#CiudadGRI').val($('#DCCiudadI option:selected').text());
		$('#CiudadGRF').val($('#DCCiudadF option:selected').text());

		var nom = $('#DCRazonSocial').val();
		ci = nom.split('_');
		$('#Comercial').val($('#DCRazonSocial option:selected').text());
		$('#CIRUCComercial').val(ci[0]);
		var nom1 = $('#DCEmpresaEntrega').val();
		ci1 = nom1.split('_');
		$('#Entrega').val($('#DCEmpresaEntrega option:selected').text());
		$('#CIRUCEntrega').val(ci1[0]);
		$('#Dir_EntregaGR').val(ci1[1]);
		sms = "Guia de Remision: " + serie[1] + "-" + $('#LblGuiaR').val() + "  Autorizacion: " + $('#LblAutGuiaRem').val();
		$('#LblGuia').val(sms);
		$('#myModal_guia').modal('hide');

	}
</script-->

<div id="myModal_suscripcion" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-md" style="width: 55%;min-width:350px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">FORMULARIO DE SUSCRIPCION</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-10">
						<form id="form_suscripcion">
							<input type="hidden" name="LblClienteCod" id="LblClienteCod">
							<div class="row">
								<div class="col-sm-12">
									<input type="text" name="LblCliente" id="LblCliente" class="form-control input-xs"
										readonly>
									<select class="form-control input-xs" id="DCCtaVenta" name="DCCtaVenta">
										<option value="">Seleccione</option>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-8">
									<div class="row">
										<div class="col-sm-7">
											<b>Periodo</b>
											<div class="row">
												<div class="col-sm-6" style="padding-right: 1px;">
													<input type="date" name="MBDesde" id="MBDesde"
														class="form-control input-xs"
														style="font-size: 10.5px; padding: 2px;"
														value="<?php echo date('Y-m-d') ?>">
												</div>
												<div class="col-sm-6" style="padding-left: 1px;">
													<input type="date" name="MBHasta" id="MBHasta"
														class="form-control input-xs"
														style="font-size: 10.5px; padding: 2px;"
														value="<?php echo date('Y-m-d') ?>">
												</div>
											</div>
										</div>
										<div class="col-sm-3" style="padding: 1px;">
											<b>Contrato No.</b>
											<input type="text" name="TextContrato" id="TextContrato"
												class="form-control input-xs" value=".">
										</div>
										<div class="col-sm-2" style="padding-left: 1px; padding-top: 1px;">
											<b>Sector</b>
											<input type="text" name="TextSector" id="TextSector"
												class="form-control input-xs" value=".">
										</div>
									</div>
									<div class="row">
										<div class="col-sm-3" style="padding-right: 1px; padding-top: 1px;">
											<b>Ent. hasta</b>
											<input type="text" name="TxtHasta" id="TxtHasta"
												class="form-control input-xs" value="0.00">
										</div>
										<div class="col-sm-3" style="padding: 1px;">
											<b>Tipo</b>
											<input type="text" name="TextTipo" id="TextTipo"
												class="form-control input-xs" value=".">
										</div>
										<div class="col-sm-3" style="padding: 1px;">
											<b>Comp. Venta</b>
											<input type="text" name="TextFact" id="TextFact"
												class="form-control input-xs" value="0">
										</div>
										<div class="col-sm-3" style="padding-left: 1px;  padding-top: 1px;">
											<b>Valor suscr</b>
											<input type="text" name="TextValor" id="TextValor"
												class="form-control input-xs" value="0.00">
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<b> Atención /Entregar a:</b>
											<input type="text" name="TxtAtencion" id="TxtAtencion"
												class="form-control input-xs">
										</div>
									</div>

								</div>
								<div class="col-sm-4">
									<div class="row">
										<div class="col-sm-6" style="padding: 0px;">
											<div class="checkbox">
												<label style="padding: 0px;">
													<input type="radio" name="opc" value="OpcMensual" id="OpcMensual"
														checked> Mensual
												</label>
											</div>
										</div>
										<div class="col-sm-6" style="padding: 0px;">
											<div class="checkbox">
												<label style="padding: 0px;">
													<input type="radio" name="opc" value="OpcAnual" id="OpcAnual"> Anual
												</label>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6" style="padding: 0px;">
											<div class="checkbox">
												<label style="padding: 0px;">
													<input type="radio" name="opc" value="OpcQuincenal"
														id="OpcQuincenal">Quincenal
												</label>
											</div>
										</div>
										<div class="col-sm-6" style="padding: 0px;">
											<div class="checkbox">
												<label style="padding: 0px;">
													<input type="radio" name="opc" value="OpcTrimestral"
														id="OpcTrimestral"> Trimestral
												</label>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6" style="padding: 0px;">
											<div class="checkbox">
												<label style="padding: 0px;">
													<input type="radio" name="opc" value="OpcSemanal" id="OpcSemanal">
													Semanal
												</label>
											</div>
										</div>
										<div class="col-sm-6" style="padding: 0px;">
											<div class="checkbox">
												<label style="padding: 0px;">
													<input type="radio" name="opc" value="OpcSemestral"
														id="OpcSemestral"> Semestral
												</label>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="row">
										<div class="col-sm-8">
											<div class="row">
												<div class="col-sm-6">
													<b>Ejecutivo de Venta</b>
													<select class="form-control input-xs" id="DCEjecutivoModal"
														name="DCEjecutivoModal">
														<option value="">Seleccione</option>
													</select>
												</div>
												<div class="col-sm-6">
													<b>Comisión %</b>
													<input type="text" name="TextComisionModal" id="TextComisionModal"
														class="form-control input-xs" onblur="TextComision_LostFocus()">
												</div>
											</div>
										</div>
										<div class="col-sm-4">
											<div class="row">
												<div class="col-sm-6" style="padding: 0px;">
													<div class="checkbox">
														<label style="padding: 0px;">
															<input type="radio" name="opc2" value='OpcN' id="OpcN"
																checked>
															Nuevo
														</label>
													</div>
												</div>
												<div class="col-sm-6" style="padding: 0px;">
													<div class="checkbox">
														<label style="padding: 0px;">
															<input type="radio" name="opc2" value='OpcR' id="OpcR">
															Renovación
														</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-12" style="padding-top: 5px;">
									<div class="row">
										<div class="col-sm-12 text-center" id="tbl_suscripcion" style="height:170px">
										</div>
										<br>
										<div class="col-sm-12">
											<label>Periodo:<input type="texto" name="txtperiodo"
													id="txtperiodo"></label>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="col-sm-2">
						<div class="row">
							<div class="col-sm-12">
								<button class="btn btn-default btn-block" id="btn_g" onclick="Command1();">
									<img src="../../img/png/grabar.png"><br> Guardar
								</button>
							</div>
							<div class="col-sm-12" style="padding-top: 5px;">
								<button class="btn btn-default btn-block" data-dismiss="modal"
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
<div id="myModal_reserva" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-md" style="width: 30%;min-width:350px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Datos de la reserva</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-4">
						<b>Entrada</b>
						<input type="date" name="ResvEntrada" id="ResvEntrada" class="form-control input-xs"
							style="font-size: 12px;" value="<?php echo date('Y-m-d') ?>">
					</div>
					<div class="col-sm-4">
						<b>Salida</b>
						<input type="date" name="ResvSalida" id="ResvSalida" class="form-control input-xs"
							style="font-size: 12px;" value="<?php echo date('Y-m-d') ?>">
					</div>
					<div class="col-sm-4">
						<b>Noches</b>
						<input type="text" name="cantNoches" id="cantNoches" class="form-control input-xs" value="1">
					</div>
				</div>
				<div class="row" style="padding-top:5px">
					<div class="col-sm-6">
						<b>Cantidad de Habitaciones</b>
						<input type="text" name="TxtCantHab" id="TxtCantHab" class="form-control input-xs" value="0">
					</div>
					<div class="col-sm-6">
						<b>Tipo de Habitación</b>
						<input type="text" name="TxtTipoHab" id="TxtTipoHab" class="form-control input-xs">
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button class="btn btn-primary" onclick="abrirDetalle()">Aceptar</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="divTxtDetalleReserva" role="dialog" data-keyboard="false" data-backdrop="static"
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
<div id="myModal_ordenesProd" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-md" style="width: 30%;min-width:350px;">
		<div class="modal-content">.
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Ordenes de Producción</h4>
			</div>
			<div class="modal-body">
				<select id="selectOrden" form="form-control">
				</select>
			</div>

			<div class="modal-footer">
				<button class="btn btn-primary btn-block" onclick="CommandButton1_Click()">Imprimir Detalle
					Orden</button>
				<button class="btn btn-primary btn-block" onclick="llenarOrden()">Procesar Selección</button>
				<button type="button" class="btn btn-default btn-block" data-dismiss="modal">Cancelar</button>
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

<div id="my_modal_abonos" class="modal" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header" style="padding: 6px 0px 6px 15px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">INGRESO DE CAJA</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-10">
						<form id="form_abonos">
							<div class="row">
								<div class="col-sm-2">
									<label class="control-label"
										style="font-size: 11.5px;padding-right: 0px; white-space: nowrap;"><input type="checkbox"
											name="CheqRecibo" id="CheqRecibo" checked> INGRESO CAJA No.</label>
								</div>
								<div class="col-sm-2 col-xs-4">
									<input type="text" name="TxtRecibo" id="TxtRecibo" class="form-control input-xs" value="0000000"
										style="padding-right: 0;">
								</div>
								<div class="col-sm-3 col-xs-3">
									<div class="col-sm-6">
										<label for="LabelDolares" style="font-size: 11.5px; padding-top:5px">COTIZACION</label>
									</div>
									<div class="col-sm-6 col-xs-5">
										<input type="text" name="LabelDolares" id="LabelDolares"
											class="form-control input-xs text-right" value="0.00" style="padding:0;">
									</div>
								</div>
								<div class="col-sm-4 col-xs-5">
									<div class="col-sm-6" style="padding:0;">
										<label for="MBFecha" style="font-size: 11.5px;">Fecha
											del
											abono</label>
									</div>
									<div class="col-sm-6 col-xs-6" style="padding: 0;">
										<input type="date" name="MBFecha" id="MBFecha" class="form-control input-xs"
											value="<?php echo date('Y-m-d'); ?>" style="padding:0;">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4 col-xs-4">
									<div class="col-sm-6" style="padding:0">
										<label for="DCTipo" style="font-size: 11.5px; white-space: nowrap;" class="text-left" for="DCTipo">Tipo
											de
											Documento.</label>
									</div>
									<div class="col-sm-5 col-xs-4">
										<select class="form-control input-xs" id="DCTipo" name="DCTipo" style="padding: 0;" onblur="buscarDCSerie();">
											<option value="FA">FA</option>
										</select>
									</div>
								</div>
								<div class="col-sm-3 col-xs-3">
									<div class="col-sm-3">
										<label for="DCSerie" class="text-left">Serie.</label>
									</div>
									<div class="col-sm-9">
										<select class="form-control input-xs" id="DCSerie" name="DCSerie" onblur="DCFactura_()">
										</select>
									</div>
								</div>
								<div class="col-sm-3 col-xs-3">
									<div class="col-sm-4" style="padding:0;">
										<label for="DCFactura" style="font-size: 11.5px; white-space: nowrap;" id="Label2"
											name="Label2">No.</label>
									</div>
									<div class="col-sm-8 col-xs-9" style="padding-right: 0;">
										<select class="form-control input-xs" id="DCFactura" name="DCFactura"
											onblur="DCAutorizacionF();DCFactura1()">
										</select>
									</div>
								</div>
								<div class="col-sm-2 col-xs-2" style="padding:0px">
									<div class="col-sm-4">
										<label for="LabelSaldo">Saldo</label>
									</div>
									<div class="col-sm-8 col-xs-8">
										<input type="text" name="LabelSaldo" id="LabelSaldo" class="form-control input-xs text-right"
											value="0.00">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-xs-10" style="padding:0px">
									<div class="col-sm-2 col-xs-2">
										<label for="DCAutorizacion">Autorizacion.</label>
									</div>
									<div class="col-sm-10 col-xs-10">
										<select class="form-control input-xs" id="DCAutorizacion" name="DCAutorizacion">
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-9 col-xs-8">
									<input type="text" name="LblCliente" id="LblCliente" class="form-control input-xs"
										placeholder="Cliente">
									<input type="hidden" name="CodigoC" id="CodigoC" class="form-control input-xs"
										placeholder="Cliente">
									<input type="hidden" name="CI_RUC" id="CI_RUC" style="padding:5px 0px 0px 0px">
								</div>
								<div class="col-sm-3 col-xs-4">
									<input type="text" name="LblGrupo" id="LblGrupo" class="form-control input-xs"
										placeholder="Grupo No">
								</div>
							</div>


							<div class="row">
								<div class="col-sm-3 col-xs-2">
									<label for="TxtSerieRet">Serie Retencion</label>
									<input type="text" name="TxtSerieRet" id="TxtSerieRet" class="form-control input-xs"
										placeholder="001" value="001001">
								</div>
								<div class="col-sm-3 col-xs-2">
									<label for="TextCompRet">Retencion No</label>
									<input type="text" name="TextCompRet" id="TextCompRet" class="form-control input-xs text-right"
										placeholder="00000000" value="99999999">
								</div>
								<div class="col-sm-6 col-xs-8">
									<label for="TxtAutoRet" id="LabelAutorizacion">Autorizacion </label>
									<input type="text" name="TxtAutoRet" id="TxtAutoRet" class="form-control input-xs"
										placeholder="Grupo No" value="000000000">
								</div>
							</div>


							<div class="row">
								<div class="col-sm-12 col-xs-7">
									<div class="row">
										<div class="col-sm-6 col-xs-8">
											<label for="DCRetIBienes">RETENCION DEL I.V.A. EN BIENES</label>
											<input type="hidden" name="DCRetIBienesNom" id="DCRetIBienesNom">
											<select class="form-control input-xs" id="DCRetIBienes" name="DCRetIBienes"
												onchange="$('#DCRetIBienesNom').val($('#DCRetIBienes option:selected').text())"
												placeholder="Retencion en bienes">
											</select>
										</div>
										<div class="col-sm-1" style="padding:0px">
											<label for="CBienes">%</label>
											<select class="form-control input-xs" id="CBienes" name="CBienes">
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
												<div class="col-sm-7 col-xs-6 text-right">
													<label for="TextRetIVAB">VALOR RETENIDO.</label>
												</div>
												<div class="col-sm-5">
													<input type="text" name="TextRetIVAB" id="TextRetIVAB"
														class="form-control input-xs text-right" placeholder="0.00" value="0.00"
														onblur="formatearValor(this);Calculo_Saldo()">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-xs-7">
									<div class="row">
										<div class="col-sm-6 col-xs-6">
											<label for="DCRetISer">RETENCION DEL I.V.A. EN SERVICIO </label>
											<input type="hidden" name="DCRetISerNom" id="DCRetISerNom">
											<select class="form-control input-xs" id="DCRetISer" name="DCRetISer"
												onchange="$('#DCRetISerNom').val($('#DCRetISer option:selected').text())">
												<option value="">Retencion en servicios</option>
											</select>
										</div>
										<div class="col-sm-1 col-xs-1" style="padding:0;">
											<label for="CServicio">%</label>
											<select class="form-control input-xs" id="CServicio" name="CServicio">
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
												<div class="col-sm-7 col-xs-6 text-right">
													<label for="TextRetIVAS">VALOR RETENIDO.</label>
												</div>
												<div class="col-sm-5">
													<input type="text" name="TextRetIVAS" id="TextRetIVAS"
														class="form-control input-xs text-right" placeholder="0.00"
														onblur="formatearValor(this);Calculo_Saldo()" value="0.00">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-xs-7">
									<div class="row">
										<div class="col-sm-4 col-xs-7">
											<label for="DCRetFuente">RETENCION EN LA FUENTE</label>
											<select class="form-control input-xs" id="DCRetFuente" name="DCRetFuente">
											</select>
										</div>
										<div class="col-sm-2 col-xs-3" style="padding: 0;">
											<label for="DCCodRet">CODIGO</label>
											<select class="form-control input-xs" id="DCCodRet" name="DCCodRet">
											</select>
										</div>
										<div class="col-sm-1 col-xs-2" style="padding-right: 0;">
											<label for="TextPorc">%</label>
											<input type="text" name="TextPorc" id="TextPorc" class="form-control input-xs"
												placeholder="000">
										</div>
										<div class="col-sm-5">
											<div class="row">
												<div class="col-sm-12">
													<label for="" style="visibility: hidden;">ESPACIADO</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-7 text-right">
													<label for="TextRet">VALOR RETENIDO.</label>
												</div>
												<div class="col-sm-5">
													<input type="text" name="TextRet" id="TextRet"
														class="form-control input-xs text-right" placeholder="00000000"
														onblur="formatearValor(this);Calculo_Saldo()" value="0.00">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-xs-8">
									<div class="row">
										<div class="col-sm-5 col-xs-5">
											<label for="DCBancoNom">CUENTA DEL BANCO </label>
											<input type="hidden" name="DCBancoNom" id="DCBancoNom">
											<select class="form-control input-xs" id="DCBanco" name="DCBanco"
												onchange="$('#DCBancoNom').val($('#form_abonos #DCBanco option:selected').text())">
											</select>
										</div>
										<div class="col-sm-1 col-xs-3" style="padding:0;">
											<label for="TextCheqNo">CHEQUE </label>
											<input type="text" name="TextCheqNo" id="TextCheqNo" class="form-control input-xs"
												placeholder="00000000">
										</div>
										<div class="col-sm-3 col-xs-4">
											<label for="TextBanco">NOMBRE DE BANCO</label>
											<input type="text" name="TextBanco" id="TextBanco" class="form-control input-xs">
										</div>
										<div class="col-sm-3">
											<div class="row">
												<div class="col-sm-12">
													<label for="" style="visibility: hidden;">ESPACIADO</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6 text-right">
													<label for="TextCheque">VALOR.</label>
												</div>
												<div class="col-sm-6">
													<input type="text" name="TextCheque" id="TextCheque"
														class="form-control input-xs text-right" placeholder="0.00"
														onblur="formatearValor(this);Calculo_Saldo()" value="0.00">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-xs-8">
									<div class="row">
										<div class="col-sm-5 col-xs-5">
											<label for="DCTarjeta">TARJETA DE CREDITO</label>
											<input type="hidden" name="DCTarjetaNom" id="DCTarjetaNom">
											<select class="form-control input-xs" id="DCTarjeta" name="DCTarjeta"
												onchange="$('#DCTarjetaNom').val($('#DCTarjeta option:selected').text())">
												<option value="">Tarjeta credito</option>
											</select>
										</div>
										<div class="col-sm-2 col-xs-3" style="padding:0;">
											<label for="TextBaucher">BAUCHER</label>
											<input type="text" name="TextBaucher" id="TextBaucher" class="form-control input-xs"
												placeholder="00000000">
										</div>
										<div class="col-sm-2 col-xs-4">
											<label for="TextInteres" style="font-size: 11.5px;">INTERES TARJETA</label>
											<input type="text" name="TextInteres" id="TextInteres"
												class="form-control input-xs text-right" placeholder="00000000" value="0"
												onblur="calcTextInteres()">
										</div>
										<div class="col-sm-3">
											<div class="row">
												<div class="col-sm-12">
													<label for="" style="visibility: hidden;">ESPACIADO</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6 text-right">
													<label for="TextTotalBaucher">VALOR.</label>
												</div>
												<div class="col-sm-6">
													<input type="text" name="TextTotalBaucher" id="TextTotalBaucher"
														class="form-control input-xs text-right" placeholder="00000000"
														onblur="formatearValor(this);Calculo_Saldo()" value="0.00">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-7 col-xs-6">
									<div class="row">
										<div class="col-sm-12 col-xs-12" style="padding-top:10px">
											<textarea placeholder="Observacion" rows="2" style="resize: none;"
												class="form-control input-xs"></textarea>
											<textarea placeholder="Nota" rows="2" style="resize: none;"
												class="form-control input-xs"></textarea>
											<label for="DCVendedor">Vendedor</label>
											<select class="form-control input-xs" id="DCVendedor" name="DCVendedor">
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-5 col-xs-6" style="padding-top:10px">
									<div class="row">
										<label for="TextCajaMN" class="col-sm-6 col-xs-6 control-label">Caja MN.</label>
										<div class="col-sm-6 col-xs-6">
											<input type="text" name="TextCajaMN" id="TextCajaMN"
												class="form-control input-xs text-right" placeholder="00000000" value="0.00">
										</div>
									</div>
									<div class="row">
										<label for="TextCajaME" class="col-sm-6 col-xs-6 control-label">Caja ME.</label>
										<div class="col-sm-6 col-xs-6">
											<input type="text" name="TextCajaME" id="TextCajaME"
												class="form-control input-xs text-right" placeholder="00000000" value="0.00">
										</div>
									</div>
									<div class="row">
										<label for="LabelPend" class="col-sm-6 col-xs-6 control-label">SALDO ACTUAL.</label>
										<div class="col-sm-6 col-xs-6">
											<input type="text" name="LabelPend" style="color:red;" id="LabelPend"
												class="form-control input-xs text-right" placeholder="00000000" value="0.00">
										</div>
									</div>
									<div class="row">
										<label for="TextRecibido" class="col-sm-6 col-xs-6 control-label">VALOR RECIBIDO.</label>
										<div class="col-sm-6 col-xs-6">
											<input type="text" name="TextRecibido" id="TextRecibido"
												class="form-control input-xs text-right" placeholder="00000000" value="0.00">
										</div>
									</div>
									<div class="row">
										<label for="LabelCambio" style="font-size: 11.5px;"
											class="col-sm-6  col-xs-6 control-label">CAMBIO A ENTREGAR.</label>
										<div class="col-sm-6 col-xs-6 ">
											<input type="text" name="LabelCambio" style="color:red;" id="LabelCambio"
												class="form-control input-xs text-right" placeholder="00000000" value="0.00">
										</div>
									</div>

								</div>
							</div>
							<input type="hidden" name="Cta_Cobrar" id="Cta_Cobrar">
						</form>
					</div>
					<div class="col-sm-2">
						<button class="btn btn-default" id="btn_g" onclick="guardar_abonos();"> <img
								src="../../img/png/grabar.png"><br>&nbsp;Guardar&nbsp;</button>
						<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> --><br> <br>
						<button class="btn btn-default" data-dismiss="modal"> <img src="../../img/png/bloqueo.png"><br>
							Cancelar</button>
					</div>
				</div>
				<!--<iframe src="" id="frame" width="100%" height="560px" marginheight="0" frameborder="0"></iframe>-->
			</div>
			<div class="modal-footer">
				<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> -->
			</div>
		</div>

	</div>
</div>

<div id="my_modal_abono_anticipado" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">INGRESO DE ABONOS ANTICIPADOS</h4>
			</div>
			<div class="modal-body">
				<!--<iframe src="" id="frame_anticipado" width="100%" height="500px" marginheight="0"
					frameborder="0"></iframe>-->
				<div class="row">
					<div class="col-sm-10">
						<form id="form_abonos_anti" class="row">

							<div class="form-inline col-sm-12">
								<div class="checkbox col-sm-4">
									<input type="checkbox" id="CheqRecibo" name="CheqRecibo" checked>
									<label for="CheqRecibo">RECIBO CAJA No.</label>
								</div>
								<div class="form-group col-sm-4">
									<input type="" class="form-control" id="TxtRecibo" value="0">
								</div>
								<div class="form-group col-sm-4">
									<label for="MBFecha">FECHA</label>
									<input type="date" class="form-control" id="MBFecha" name="MBFecha"
										value="<?php echo date('Y-m-d'); ?>">
								</div>
							</div>

							<div class="col-sm-12" style="padding-top: 5px;" id="Frame1">
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

							<div class="col-sm-12" style="padding-top: 5px;" id="Frame2">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title">Abono Anticipado</h3>
									</div>
									<div class="panel-body">

										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
													<label for="DCCliente">Cliente</label>
													<select class="form-select form-select-sm" id="DCClientes" name="DCCliente"
														style="width: 100%;">
														<option value="">Seleccione</option>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
													<label for="TxtConcepto">Observación</label>
													<textarea class="form-control" id="TxtConcepto" rows="3"></textarea>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
													<label for="DCBanco">Cuenta Contable del Ingreso</label>
													<select class="form-select form-select-sm" id="DCBanco" name="DCBanco"
														style="width: 100%;">
														<option value="">Banco</option>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
													<label for="DCCtaAnt">Cuenta Contable de Anticipo</label>
													<select class="form-select form-select-sm" id="DCCtaAnt" name="DCCtaAnt"
														style="width: 100%;">
														<option value="">Banco</option>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label for="TextCajaMN" class="col-sm-5 control-label">Caja MN.</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" id="TextCajaMN" placeholder="00000000" value="0.00">
									</div>
								</div>
								<div class="form-group">
									<label for="LabelPend" class="col-sm-5 control-label" id="Label10">Saldo Actual</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" id="LabelPend" placeholder="00000000" value="0.00">
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="col-sm-2">
						<button class="btn btn-default btn-block" id="btn_g" onclick="Command1_Click()">
							<img src="../../img/png/grabar.png"><br>&nbsp;Guardar&nbsp;
						</button>
						<button class="btn btn-default btn-block" data-dismiss="modal">
							<img src="../../img/png/bloqueo.png"><br>Cancelar
						</button>
					<scrv>
				</div>
			</div>
			<div class="modal-footer"> </div>
		</div>
	</div>
</div>