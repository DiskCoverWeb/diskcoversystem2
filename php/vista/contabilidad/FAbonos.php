<?php

?>
<script type="text/javascript"></script>
<script src="../../dist/js/FAbonos.js"></script>
<script type="text/javascript">


</script>
<div class="row">
	<div class="col-sm-8">
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
						<label for="DCTipo" style="font-size: 11.5px; white-space: nowrap" class="text-left" for="DCTipo">Tipo
							de
							Documento.</label>
					</div>
					<div class="col-sm-5 col-xs-4">
						<select class="form-control input-xs" id="DCTipo" name="DCTipo" style="padding: 0;" onblur="buscarDCSerie()">
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
										onblur="Calculo_Saldo()">
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
										onblur="Calculo_Saldo()" value="0.00">
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
										onblur="Calculo_Saldo()" value="0.00">
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
								onchange="$('#DCBancoNom').val($('#DCBanco option:selected').text())">
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
										onblur="Calculo_Saldo()" value="0.00">
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
										onblur="Calculo_Saldo()" value="0.00">
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
		<button class="btn btn-default" onclick="cerrar_modal();"> <img src="../../img/png/bloqueo.png"><br>
			Cancelar</button>
	</div>
</div>
<script type="text/javascript">

</script>