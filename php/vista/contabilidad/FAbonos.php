<?php

?>
<script type="text/javascript"></script>
<script src="../../dist/js/FAbonos.js"></script>
<script type="text/javascript">


</script>
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
					<input type="text" aria-label="First name" class="form-control form-control-sm w-50" name="LblCliente" id="LblCliente" disabled>
					<span class="input-group-text">Grupo No</span>
					<input type="text" aria-label="Last name" class="form-control form-control-sm" name="LblGrupo" id="LblGrupo" disabled>
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
				<div class="col-sm-5 col-xs-6 mt-3 py-2 border border-3 rounded bg-body-secondary">
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
<script type="text/javascript">

</script>