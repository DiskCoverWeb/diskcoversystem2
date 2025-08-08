var valTC = 'FA';

$(document).ready(function () {
	serie();
	autocomplete_cliente();
	DCLineas()
	DCPorcenIvaFD();
	DCBanco();
	DCBanco2();

	DCEfectivo();

	construirTablaGavetas();
	construirTablaEvalFundaciones();


})

function autocomplete_cliente() {
	$('#DCCliente').select2({
		width: '62%',
		placeholder: 'Seleccione un cliente',
		ajax: {
			url: '../controlador/facturacion/facturas_distribucionC.php?DCCliente=true',
			dataType: 'json',
			delay: 250,
			data: function (params) {
                return {
                    query: params.term,
					v_donacion: 'NDO',
					fecha: $("#MBFecha").val()
                }
            },
			processResults: function (data) {
				return {
					results: data
				};
			},
			cache: true
		}
	});
}

function select() {
	var seleccionado = $('#DCCliente').select2("data");
	var SaldoPendiente = 0;
	var CantSaldoPEndiente = 0;
	var dataS = seleccionado[0].data;
	console.log(dataS);
	$('#Lblemail').val(dataS[0].Email);
	$('#LblRUC').val(dataS[0].CI_RUC);
	$('#codigoCliente').val(dataS[0].Codigo);
	$('#txt_pedido').val(dataS[0].Orden_No);
	$('#LblT').val(dataS[0].T);
	$('#txtRecalcular').val(0);

	var parametros = {
		'CodigoCliente': dataS[0].Codigo,
	};

	//TODO: Aplicar select2
	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturas_distribucionC.php?ClienteDatosExtras=true',
		data: { parametros: parametros },
		dataType: 'json',
		success: function (res) {
			if (res['direcciones'].length <= 0) {
				$('#DCDireccion').empty();
				var nuevoOption = $('<option>', {
					value: dataS[0].Direccion,
					text: dataS[0].Direccion
				});
				$('#DCDireccion').append(nuevoOption);
			} else {
				$('#DCDireccion').empty();
				var nuevoOption = $('<option>', {
					value: res['direcciones'][0].Direccion,
					text: res['direcciones'][0].Direccion
				});
				$('#DCDireccion').append(nuevoOption);
			}
			if(res['horarioEnt']!='.')
			{
				$('#HoraAtencion').val(res['horarioEnt']);
			}
		}
	});

	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturas_distribucionC.php?ClienteSaldoPendiente=true',
		data: { parametros: parametros },
		dataType: 'json',
		success: function (res) {
			if (res.length > 0) {
				//if is null put 0.00
				var value = res[0].TSaldo_MN;
				if (value == null) {
					value = 0.00;
				} else {
					value = parseFloat(res[0].TSaldo_MN);
				}
				$('#saldoP').val(value.toFixed(2));
			}
		}
	});

	cargarRegistrosProductos();
}

function cargarRegistrosProductos(){
	$('#myModal_espera').modal('show');
	let codigoC = $('#codigoCliente').val();
	let parametros = {
		'beneficiario': codigoC,
		'fecha': $('#MBFecha').val(),
		'orden':$('#txt_pedido').val(),
	}
	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturas_distribucionC.php?ConsultarProductos=true',
		data: { parametros },
		dataType: 'json',
		success: function (datos) {

			setTimeout(()=>{
				$('#myModal_espera').modal('hide');
			}, 1000)
			
			if (datos['res'] == 1) 
			{
				var lineas = datos['contenido'];
				tr = '';
				cTotalProds = 0;
				tTotalProds = 0;
				lineas.forEach(function(item,i){
					color ='';
					if(color!='.'){	color = 'style="background: '+item['Tipo'][0]['Color'].replace('Hex_','#')+';"'	}


					let totalProducto =parseFloat(item['Detalles']['Total'])*parseFloat(item['Productos']['PVP']);

					tr+=`<tr `+color+` class="asignTablaDistri">
							<td>`+item['Detalles']['Codigo_Barra']+`</td>
							<td>`+item['Detalles']['Nombre_Completo']+`</td>
							<td>`+item['Productos']['Producto']+`</td>
							<td><input id="txt_cant_`+item['Detalles']['ID']+`" class="form-control form-control-sm" onchange="modificarLineaFac(`+item['Detalles']['ID']+`)" style="max-width:95px;" value="`+item['Detalles']['Total']+`"></td>
							<td><input id="txt_pvp_`+item['Detalles']['ID']+`" class="form-control form-control-sm" onchange="modificarLineaFac(`+item['Detalles']['ID']+`)" style="max-width:85px;" value="`+item['Productos']['PVP']+`" valAnterior="`+item['Productos']['PVP']+`"></td>
							<td><span id="txt_total_linea_`+item['Detalles']['ID']+`">`+totalProducto.toFixed(8)+`</span></td>
							<td><input type="checkbox" id="producto_recalcular_`+item['Detalles']['ID']+`" name="producto_recalcular" onchange="recalcularLineaFact(`+item['Detalles']['ID']+`)" class="form-check-input border-secondary rbl_producto"></td>
							<td><input type="checkbox" id="producto_cheking_`+item['Detalles']['ID']+`" name="producto_cheking" class="form-check-input border-secondary"><td>
						 </tr>`;
					cTotalProds += parseFloat(item['Detalles']['Total']);
					tTotalProds += parseFloat(totalProducto);

				})
				tr+=`<tr class="bg-primary-subtle">
						<td colspan="3"><b>Total</b></td>
						<td><b>`+cTotalProds+`</b></td>
						<td></td>
						<td><b id="ADTotal">`+tTotalProds.toFixed(8)+`</b></td>
						<td colspan="2"></td>
					</tr>`

				$('#kilos_distribuir').val(cTotalProds);
				var unidadProd = '';
				if( datos['contenido'][0]['Productos']['Unidad'].toUpperCase()=='KILOS') { unidadProd = 'Kg';}
				$('#tablaProdCU').text(unidadProd);
				$('#unidad_dist').val(unidadProd);
				$('#LabelTotal2').val(tTotalProds.toFixed(4));
				$('#LabelSubTotal').val(tTotalProds.toFixed(4));
				$('#LabelTotal').val(tTotalProds.toFixed(4));
				buscarValoresGavetas();

				$('#cuerpoTablaDistri').html(tr);
			}
		}
	})
}

function buscarValoresGavetas(){
		let parametros = {
			codigo: $('#codigoCliente').val()
		};
		$('#myModal_espera').modal('show');
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucionC.php?ValoresGavetas=true',
			data: {'parametros': parametros},
			dataType: 'json',
			success: function (datos) {
				setTimeout(()=>{
					$('#myModal_espera').modal('hide');
				}, 1000)
				$('.gdet_pendientes').text('0');
				$('#gavetas_total_pendientes_ver b').text('0');
				$('#gavetas_pendientes2').val('0');
				if (datos['res'] == 1) {
					let valoresGavetas = datos['contenido']
					let estadovGavs = "";
					for(let vGavs of valoresGavetas){
						if(estadovGavs != vGavs['Codigo_Inv']){
							document.getElementById(`gdet_pendientes_${vGavs['Codigo_Inv']}`).innerText = vGavs['Existencia'];
							//console.log(vGavs['Existencia']);
							estadovGavs = vGavs['Codigo_Inv'];
						}
					}
				}
				let gDetPendientes = $('.gdet_pendientes');
				let totalGDetPendientes = 0;
				for(let gdp of gDetPendientes){
					totalGDetPendientes += parseInt(gdp.textContent);
				}
				$('#gavetas_total_pendientes_ver b').text(totalGDetPendientes);
				$('#gavetas_pendientes2').val(totalGDetPendientes)
			}
		})
	}

function recalcularLineaFact(id)
{
	$('#rbl_recalcular_all').prop('checked',false)
	if($('#txtRecalcular').val()==0 || $('#txtRecalcular').val()=='')
	{
		Swal.fire("Ingrese un valor para recalcular","","info");
		$('#producto_recalcular_'+id).prop('checked',false)
		return false;
	}
	if($('#producto_recalcular_'+id).prop('checked'))
	{
		 recalcular_todo();
	}else
	{
		valores_por_defecto(id);
	}
}


function recalcular_todo()
{
	var  valor_calcular = $('#txtRecalcular').val();
	var cantidad_check_recalcular = 0;
	var total_sin_recalculo = 0;
	lista = [];
	lista_sin_cal = [];
	$('.rbl_producto').each(function() {
	    const checkbox = $(this);
	    const isChecked = checkbox.prop('checked'); 
	    if (isChecked) {
	    	cantidad_check_recalcular++;
	    	var id = checkbox[0].id.replaceAll('producto_recalcular_','');
	    	lista.push(id);
	    }else
	    {
	    	var id = checkbox[0].id.replaceAll('producto_recalcular_','');
	    	lista_sin_cal.push(id);
	    }
	});


	// calculo de los que no son recalculando
	lista_sin_cal.forEach(function(item,i){
		var valor = $('#txt_total_linea_'+item).text();
		total_sin_recalculo = total_sin_recalculo+parseFloat(valor);
	})

	// console.log(lista_sin_cal);


	// calculo de totales en recalculado
	// console.log(lista);
	var total_total = 0;
	lista.forEach(function(item,i){
		var valor = valor_calcular/cantidad_check_recalcular
		var cantidad = $('#txt_cant_'+item).val();
		pvp = valor/cantidad;

		$('#txt_pvp_'+item).val(pvp.toFixed(4));
		$('#txt_total_linea_'+item).text(valor.toFixed(8));
		total_total = total_total+valor;
	})

	var total_de_totales = parseFloat(total_total)+parseFloat(total_sin_recalculo);

	console.log(total_de_totales);
	$('#LabelTotal2').val(total_de_totales.toFixed(4));
	$('#ADTotal').text(total_de_totales.toFixed(8))
	$('#LabelSubTotal').val(total_de_totales.toFixed(4));
	$('#LabelTotal').val(total_de_totales.toFixed(4));

}

function valores_por_defecto(id)
{
	var parametros =
	{
		'id': id,
		'beneficiario':$('#DCCliente').val(),
		'fecha':$('#MBFecha').val(),
	}
	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturas_distribucionC.php?buscar_linea=true',
		data: { parametros: parametros },
		dataType: 'json',
		success: function (data) {
			// console.log(data);
			if(data.length>0)
			{
				var cant = data[0].Total;
				var pvp = data[0].PVP;
				$('#txt_pvp_'+id).val(data[0].PVP);
				var Total = parseFloat(cant)*parseFloat(pvp)
				$('#txt_total_linea_'+id).text(Total.toFixed(8));
			}
			 recalcular_todo();
			// console.log(data);
		}
	});
}

function recalcular_all()
{
	if($('#txtRecalcular').val()==0 || $('#txtRecalcular').val()=='')
	{
		Swal.fire("Ingrese un valor para recalcular","","info");
		$('#rbl_recalcular_all').prop('checked',false)
		return false;
	}else{
		if($('#rbl_recalcular_all').prop('checked'))
		{
			$('.rbl_producto').each(function() {
			    const checkbox = $(this);
			    checkbox.prop('checked',true); 
			});
			recalcular_todo();
		}else
		{
			$('.rbl_producto').each(function() {
			    const checkbox = $(this);
			    checkbox.prop('checked',false); 
		    	var id = checkbox[0].id.replaceAll('producto_recalcular_','');
			    valores_por_defecto(id);
			});
		}
	}
}

function  modificarLineaFac(id)
{
	var cant  = $('#txt_cant_'+id).val();
	var pvp = $('#txt_pvp_'+id).val();
	var total = parseFloat(cant)*parseFloat(pvp);
	$('#txt_total_linea_'+id).text(total.toFixed(8));
	recalcular_todo();
}

function serie() {
	var parametros =
	{
		'TC':'NDO',
	}
	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturas_distribucion_famC.php?LblSerieNDU=true',
		data: { parametros: parametros },
		dataType: 'json',
		success: function (data) {
			if (data.serie != '.') {
				$('#LblSerie').text(data.serie);
				$('#TextFacturaNo').val(data.NumCom);
			} else {
				numeroFactura();
			}
			validar_cta();
		}
	});
}

function validar_cta() {
	var parametros =
	{
		'TC': valTC,
		'Serie': $('#LblSerie').text(),
		'Fecha': $('#MBFecha').val(),
	}
	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturas_distribucionC.php?validar_cta=true',
		data: { parametros: parametros },
		dataType: 'json',
		success: function (data) {
			if (data != 1) {
				Swal.fire({
					icon: 'info',
					title: data,
					text: '',
					allowOutsideClick: false,
				});
			}
		}
	});
}


function DCLineas() {
	var parametros =
	{
		'Fecha': $('#MBFecha').val(),
		'TC': 'FA'
	}
	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturas_distribucionC.php?DCLineas=true',
		data: { parametros: parametros },
		dataType: 'json',
		success: function (data) {
			console.log(data);
			var opcion = '';
			data.forEach(function(item,i){
				opcion+="<option value='"+item.codigo+"' data-caja='"+item.caja+"'>"+item.nombre+"</option>"
			})
			// llenarComboList(data, 'DCLineas');
			//$('#Cod_CxC').val(data[0].nombre);  //FA
			//Lineas_De_CxC();
			$('#DCLineas').html(opcion);
			$('#DCLineas').val('F001003X');
		}
	});
}

function cta_caja()
{
	const select = document.getElementById('DCLineas');
	const selectedOption = select.options[select.selectedIndex];
	const cta = selectedOption.getAttribute('data-caja');
	if(cta=='0')
	{
		$('#pnl_cta_caja').removeClass("d-none")
	}else
	{
		$('#pnl_cta_caja').addClass("d-none")
	}
}
function DCPorcenIvaFD() {
	
	$.ajax({
		type: "GET",
		url: '../controlador/facturacion/facturas_distribucionC.php?LlenarSelectIVA=true',
		data: { fecha:  $('#MBFecha').val() },
		dataType: 'json',
		success: function (data) {
			console.log(data);
			response = [];
			data.forEach(function(item,i){
				response.push({'codigo':item.id,'nombre':item.text})
			})

			llenarComboList(response, 'DCPorcenIVA');
			//$('#Cod_CxC').val(data[0].nombre);  //FA
			//Lineas_De_CxC();
		}
	});
}

function toggleInfoBanco(){
	let btn = $('#btnToggleInfoBanco');
	if(btn.attr('stateval') == "1"){
		$('#campos_fact_banco').hide();
		// $('#campos_fact_banco_2').hide();
		$('#bouche_banco_input').hide();
		btn.addClass('btn-light');
		btn.addClass('border');
		btn.addClass('border-1');
		btn.removeClass('btn-primary');
		btn.attr('stateval', '0');
	}else{
		$('#campos_fact_banco').show();
		// $('#campos_fact_banco_2').show();
		$('#bouche_banco_input').show();
		btn.addClass('btn-primary');
		btn.removeClass('btn-light');
		btn.removeClass('border');
		btn.removeClass('border-1');
		btn.attr('stateval', '1');
		$('#TextCheque').val(0);
	}
}
function toggleInfoEfectivo(){
	let btn = $('#btnToggleInfoEfectivo');
	if(btn.attr('stateval') == "1"){
		$('#campos_fact_efectivo').hide();
		btn.addClass('btn-light');
		btn.addClass('border');
		btn.addClass('border-1');
		btn.removeClass('btn-primary');
		btn.attr('stateval', '0');
	}else{
		$('#campos_fact_efectivo').show();
		btn.addClass('btn-primary');
		btn.removeClass('btn-light');
		btn.removeClass('border');
		btn.removeClass('border-1');
		btn.attr('stateval', '1');
		$('#TxtEfectivo').val(0);
	}
}

 function limpiarAbonos()
{
	$('#txtBeneficiarioAbono').val('');
	$('#DCEfectivo').val(null).trigger('change');
	$('#TxtEfectivo').val('0');

	$('#DCBanco').val(null).trigger('change');
	$('#TextCheqNo').val('.');
	$('#TextBanco').val('.');
	$('#TextCheque').val(0);

	 $('#btnToggleInfoEfectivo').attr('stateval','0')
	 $('#btnToggleInfoBanco').attr('stateval','1')
	 toggleInfoEfectivo()
	 toggleInfoBanco();
}

function DCBanco() {
	// alert('das');
	$('#DCBanco').select2({
		placeholder: 'Seleccione un banco',
		dropdownParent: $('#modalInfoFactura'),
		width: '100%',
		ajax: {
			url: '../controlador/facturacion/facturas_distribucionC.php?DCBanco=true',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {

				return {
					results: data
				};
			},
			cache: true
		}
	});
}

function DCBanco2() {
	// alert('das');
	$('#DCBanco2').select2({
		placeholder: 'Seleccione un banco',
		dropdownParent: $('#modalInfoFactura'),
		width: '100%',
		ajax: {
			url: '../controlador/facturacion/facturas_distribucionC.php?DCBanco=true',
			dataType: 'json',
			delay: 250,
			processResults: function (data) {

				return {
					results: data
				};
			},
			cache: true
		}
	});
}
function calcular_pago() {

	var valBanco = $('#TextCheque').val();
	var valEfectivo = $('#TxtEfectivo').val();
	if(valBanco=='')
	{
		$('#TextCheque').val(0);			
	}
	if(valEfectivo=='')
	{
		$('#TxtEfectivo').val(0);			
	}
	var cotizacion = parseInt($('#TextCotiza').val());
	var efectivo =  $('#btnToggleInfoEfectivo').attr('stateval') == "1" ? parseFloat($('#TxtEfectivo').val()) : 0.00;
	var Total_Factura = parseFloat($('#LabelTotalME').val());
	var Total_Factura2 = parseFloat($('#LabelTotal').val());
	var Total_banco = $('#btnToggleInfoBanco').attr('stateval') == "1" ? parseFloat($('#TextCheque').val()) : 0.00;
	var Total_banco2 = $('#btnToggleInfoBanco').attr('stateval') == "1" ? parseFloat($('#TextCheque2').val()) : 0.00;

	if (cotizacion > 0) {
		if (parseFloat(efectivo) > 0) {
			var ca = efectivo - Total_Factura + Total_banco;
			$('#LblCambio').val(ca.toFixed(2));
			$('#btn_g').focus();
		}
	} else {
		if (efectivo > 0 || Total_banco > 0 || Total_banco > 0) {
			var ca = (parseFloat(efectivo)-parseFloat(Total_Factura2)+parseFloat(Total_banco)+parseFloat(Total_banco2));
			if(ca.toFixed(2) != 0)
			{
				$('#LblCambio').val(ca.toFixed(2));
			}else
			{
				$('#LblCambio').val('0.00');
			}
			if(ca.toFixed(2) > 0)
			{
				$('#LblCambio').css('color','green');
			}else
			{
				$('#LblCambio').css('color','red');
			}
			$('#btn_g').focus();
		}
	}

}

// gavetas

function construirTablaGavetas(){
	//$('#myModal_espera').modal('show');
	/*let codigoC = $('#codigoCliente').val();
	let parametros = {
		'beneficiario': codigoC
	}*/
	$.ajax({
		type: "GET",
		url: '../controlador/facturacion/facturas_distribucionC.php?ConsultarGavetas=true',
		dataType: 'json',
		success: function (datos) {
			//$('#myModal_espera').modal('hide');
			if (datos['res'] == 1) {
				tablaGavetas("Visualizar", datos['contenido']);
				tablaGavetas("Editar", datos['contenido']);
				//buscarValoresGavetas();
			}
		}
	})
}

function tablaGavetas(accion, datos){
	if(accion == 'Editar'){
		$('#cuerpoTablaGavetas').remove();
	}else if(accion == 'Visualizar'){
		$('#cuerpoTablaGavetasVer').remove();
	}
	let tBody = $(`<tbody id="${accion=='Editar'?"cuerpoTablaGavetas":"cuerpoTablaGavetasVer"}" class="text-center"></tbody>`);
	for(let fila of datos){
		let td;
		let colorGaveta = fila['Producto'].replace('Gaveta ', '');
		let tr = (accion=='Editar')?$(`<tr id="${fila['Codigo_Inv']}"></tr>`):$('<tr></tr>');
		tr.append($('<td></td>').html(`<b>${colorGaveta}</b>`));
		if(accion=='Editar'){
			td = $('<td></td>');
			td.append($(`<input type="text" class="form-control gavetas_info" name="${fila['Codigo_Inv']}" id="gavetas_${colorGaveta.toLowerCase()}_entregadas" onchange="calcularTotalGavetas('${fila['Codigo_Inv']}')">`));
			tr.append(td);
			td = $('<td></td>');
			td.append($(`<input type="text" class="form-control gavetas_info" name="${fila['Codigo_Inv']}" id="gavetas_${colorGaveta.toLowerCase()}_devueltas" onchange="calcularTotalGavetas('${fila['Codigo_Inv']}')">`));
			tr.append(td);
			td = $('<td></td>');
			td.append($(`<input type="text" class="form-control gavetas_info" name="${fila['Codigo_Inv']}" id="gavetas_${colorGaveta.toLowerCase()}_pendientes" disabled>`));
			tr.append(td);
		}else{
			td = $(`<td id="gdet_pendientes_${fila['Codigo_Inv']}" class="gdet_pendientes"></td>`).text('0');
			tr.append(td);
		}
		tBody.append(tr);
	}
	let td;
	let tr = $(`<tr></tr>`);
	let totalGavetasHTML = "";
	
	if(accion=='Editar'){
		totalGavetasHTML = `
			<td><b>TOTAL</b></td>
			<td>
				<input type="text" class="form-control gavetas_info" id="gavetas_total_entregadas" value="0" style="font-weight:bold" onchange="calcularTotalGavetas()" disabled>
			</td>
			<td>
				<input type="text" class="form-control gavetas_info" id="gavetas_total_devueltas" value="0" style="font-weight:bold" onchange="calcularTotalGavetas()" disabled>
			</td>
			<td>
				<input type="text" class="form-control gavetas_info" id="gavetas_total_pendientes" value="0" style="font-weight:bold" onchange="calcularTotalGavetas()" disabled>
			</td>
		`;
		tr.html(totalGavetasHTML);
		tBody.append(tr);
		$('#tablaGavetas').append(tBody);
	}else{
		totalGavetasHTML = `
			<td><b>TOTAL</b></td>
			<td id="gavetas_total_pendientes_ver">
				<b>0</b>
			</td>
		`;
		tr.html(totalGavetasHTML);
		tBody.append(tr);
		$('#tablaGavetasVer').append(tBody);
	}
}

function calcularTotalGavetas(codInv){
	let totalGEntregadas = 0;
	let totalGDevueltas = 0;
	let totalGPendientes = 0;
	const inputsGavetas = document.querySelectorAll('.gavetas_info');

	const gavetasxcolores = document.querySelectorAll(`input[name="${codInv}"]`);
	let gAnteriores = parseInt(document.getElementById(`gdet_pendientes_${codInv}`).innerText); //valor de: Ver Detalle -> Pendientes[Color]
	let gEntregadas = parseInt(gavetasxcolores[0].value.trim()==""?0:gavetasxcolores[0].value);
	let gDevueltas = parseInt(gavetasxcolores[1].value.trim()==""?0:gavetasxcolores[1].value);
	let gPendientes = gAnteriores + gEntregadas - gDevueltas;
	gavetasxcolores[2].value = gPendientes;

	inputsGavetas.forEach(inGaveta => {
		/*console.log("---------- in for ----------");
		console.log(totalGDevueltas);
		console.log(totalGEntregadas);
		console.log(totalGPendientes);*/
		let tipoGaveta = inGaveta.id;
		console.log(tipoGaveta);
		
		if(!tipoGaveta.includes('total')){
			if(tipoGaveta.includes('entregadas')){
				totalGEntregadas += parseInt(inGaveta.value.trim()==""?0:inGaveta.value);
			}else if(tipoGaveta.includes('devueltas')){
				totalGDevueltas += parseInt(inGaveta.value.trim()==""?0:inGaveta.value);
			}else if(tipoGaveta.includes('pendientes')){
				totalGPendientes += parseInt(inGaveta.value.trim()==""?0:inGaveta.value);
			}
		}
	});

	$('#gavetas_total_entregadas').val(totalGEntregadas);
	$('#gavetas_entregadas').val(totalGEntregadas);
	$('#gavetas_total_devueltas').val(totalGDevueltas);
	$('#gavetas_devueltas').val(totalGDevueltas);
	$('#gavetas_total_pendientes').val(totalGPendientes);
	$('#gavetas_pendientes').val(totalGPendientes);
}

function construirTablaEvalFundaciones(){
		//$('#myModal_espera').modal('show');
		$.ajax({
			type: "GET",
			url: '../controlador/facturacion/facturas_distribucionC.php?consultarEvaluacionFundaciones=true',
			dataType: 'json',
			success: function (datos) {
				//$('#myModal_espera').modal('hide');
				$('#cuerpoTablaEFund').remove();
				if (datos['res'] == 1) {
					let tBody = $('<tbody id="cuerpoTablaEFund" class="text-center"></tbody>');
					for(let fila of datos['contenido']){
						let td;
						//let colorGaveta = fila['Producto'].replace('Gaveta ', '');
						let tr = $(`<tr id="${fila['Cmds']}"></tr>`);
						tr.append($('<td></td>').html(`<b>${fila['Proceso']}</b>`));
						td = $('<td></td>');
						td.append($(`<input type="radio" class="form-check-input" id="${fila['Cmds']}_bueno" name="${fila['Cmds']}" checked>`));
						tr.append(td);
						td = $('<td></td>');
						td.append($(`<input type="radio" class="form-check-input" id="${fila['Cmds']}_malo" name="${fila['Cmds']}">`));
						tr.append(td);
						tBody.append(tr);
					}
					$('#tablaEvalFundaciones').append(tBody);
				}
			}
		})
	}

// generar factura 

	function generar() {
	var Cli = $('#DCCliente').val();
	if (Cli == '') {
		Swal.fire('Seleccione un cliente', '', 'info');
		return false;
	}

	Swal.fire({
		allowOutsideClick: false,
		title: 'Esta Seguro que desea grabar: \n Comprobante  No. ' + $('#TextFacturaNo').val(),
		text: '',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Si!'
	}).then((result) => {
		if (result.value == true) {
			 validar_pago();	
			
		}
	})
	
}

function validar_pago()
{
	var total = parseFloat($('#LabelTotal').val()).toFixed(4);
	var efectivo = parseFloat($('#TxtEfectivo').val()).toFixed(4);
	var banco = parseFloat($('#TextCheque').val()).toFixed(4);
	var banco2 = parseFloat($('#TextCheque2').val()).toFixed(4);
	total_abono = parseFloat(efectivo)+parseFloat(banco)+parseFloat(banco2);
	
	if(total_abono<total)
	{
		console.log(total_abono)
		console.log(total)

		Swal.fire({
			allowOutsideClick: false,
			title: 'El Comprobante  No. ' + $('#TextFacturaNo').val()+"No tiene abonos",
			text: "Desea continuar ?",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Si!'
		}).then((result) => {
			if (result.value == true) {
				grabar_evaluacion();
			}
		})
	}else
	{
		grabar_evaluacion();
	}

}

function grabar_evaluacion(){
	$('#myModal_espera').modal('show');
	let parametros = {
		'cliente': $('#codigoCliente').val(),
		'fecha': $('#MBFecha').val(),
		'comentario': $('#comentario_eval').val().trim() == "" ? "." : $('#comentario_eval').val()
	};
	let objEvaluaciones = [];
	let filasTablaEvaluaciones = $('#cuerpoTablaEFund')[0].childNodes;
	for(let fte of filasTablaEvaluaciones){
		let fteId = fte.id;
		if(fteId != ''){
			let evalFundaciones = $(`input[name="${fteId}"]`);
			/*for(let gaveta of evalFundaciones){

			}*/
			let p = {
				'cod_inv': fteId,
				'bueno': evalFundaciones[0].checked,
				'malo': evalFundaciones[1].checked,
			}
			objEvaluaciones.push(p);
		}
	}
	parametros['evaluaciones'] = objEvaluaciones;
	console.log(parametros);

	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturas_distribucionC.php?GrabarEvaluaciones=true',
		data: { parametros: parametros },
		dataType: 'json',
		success: function (data) {
			if(data['res'] == 1){
				grabar_gavetas();
			}else{
				setTimeout(()=>{
					$('#myModal_espera').modal('hide');
				}, 1000)
				Swal.fire('Error', 'Hubo un error al guardar la evaluacion');
			}
		}
	});
}

function grabar_gavetas(){
	//$('#myModal_espera').modal('show');
	let parametros = {
		'TC': 'NDO',
		'cliente': $('#codigoCliente').val(),
		'fecha': $('#MBFecha').val(),
		'serie': $('#LblSerie').text()
	};
	let objGavetas = [];
	let filasTablaGavetas = $('#cuerpoTablaGavetas')[0].childNodes;
	for(let ftg of filasTablaGavetas){
		let ftgId = ftg.id;
		if(ftgId != ''){
			let gavetas = $(`input[name="${ftgId}"]`);
			/*for(let gaveta of gavetas){

			}*/
			let gav_entregadas = gavetas[0].value.trim()=="" ? 0 : parseInt(gavetas[0].value);
			let gav_devueltas = gavetas[1].value.trim()=="" ? 0 : parseInt(gavetas[1].value);
			let gav_pendientes = gavetas[2].value.trim()=="" ? 0 : parseInt(gavetas[2].value);
			if(gav_entregadas != 0 || gav_devueltas != 0){
				let p = {
					'cod_inv': ftgId,
					'entregadas': gav_entregadas,
					'devueltas': gav_devueltas,
					'pendientes': gav_pendientes 
				}
				objGavetas.push(p);
			}
		}
	}
	parametros['gavetas'] = objGavetas;
	console.log(parametros);

	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturas_distribucionC.php?GrabarGavetas=true',
		data: { parametros: parametros },
		dataType: 'json',
		success: function (data) {
			if(data['res'] == 1){
				//generar_factura()
				if($('#archivoAdd2')[0].files.length > 0){
					guardar_bouche2();
				}
				if($('#archivoAdd')[0].files.length > 0){
					setTimeout(()=>{

						guardar_bouche();
					}, 1000)
				}else{
					generar_factura();
				}
			}else{
				setTimeout(()=>{
					$('#myModal_espera').modal('hide');
				}, 1000)
				Swal.fire('Error', 'Hubo un problema al subir la actualizacion de gavetas.');
			}
		},
		error: (err) => {
			Swal.fire('Error', 'Hubo un problema al subir la actualizacion de gavetas.');
		}
	});
}
function generar_factura() 
{
	var lista = [];
	$('.rbl_producto').each(function() {
	    const checkbox = $(this);
	    const isChecked = checkbox.prop('checked'); 
	    	var id = checkbox[0].id.replaceAll('producto_recalcular_','');
	    	lista.push(id);
	});

	var lineas = [];
	lista.forEach(function(item,i){
		var cant = $('#txt_cant_'+item).val();
		var pvp = $('#txt_pvp_'+item).val();
		var total = $('#txt_total_linea_'+item).text();

		lineas.push({'id':item,'cant':cant,'pvp':pvp,'total':total})
	})

	const select = document.getElementById('DCLineas');
	const selectedOption = select.options[select.selectedIndex];
	const cta = selectedOption.getAttribute('data-caja');
	if(cta=="0")
	{
		cta = $('#DCEfectivo').val();
	}


	console.log(lineas);

	var parametros = {
		'lineas':JSON.stringify(lineas),
		'pedido':$('#txt_pedido').val(),
		'beneficiario': $('#DCCliente').val(),
		'fecha':$('#MBFecha').val(),
		'TC':$('#DCTipoFact2').val(),
		'serie':$('#LblSerie').text(),
		'NoFactura':$('#TextFacturaNo').val(),
		'ctaEfectivo':$('#DCEfectivo').val(),
		'valorEfectivo':$('#TxtEfectivo').val(),
		'DCBancoC':$('#DCBanco').val(),
		'DCEfectivo':cta,
		'TextCheqNo':$('#TextCheqNo').val(),
		'TextBanco':$('#TextBanco').val(),
		'valorBanco':$('#TextCheque').val(),
		'PorcIva':$('#DCPorcenIVA').val(),
		'CI':$('#LblRUC').val(),
		'cbxBanco':$('#cbx_banco_2').prop('checked'),
		'DCBancoC2':$('#DCBanco2').val(),
		'TextCheqNo2':$('#TextCheqNo2').val(),
		'TextBanco2':$('#TextBanco2').val(),
		'valorBanco2':$('#TextCheque2').val(),


	}
	$('#myModal_espera').modal('show');
	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturas_distribucionC.php?generar_factura=true',
		data: { parametros:parametros},
		dataType: 'json',
		success: function (data) {
		setTimeout(()=>{
			$('#myModal_espera').modal('hide');
		}, 1000)
			console.log(data);
			if (data.respuesta[0] == 1) {
				Swal.fire({
					icon: 'success',
					title: 'Factura Generada',
					confirmButtonText: 'Ok!',
					allowOutsideClick: false,
				}).then(function () {
						
						// imprimir baucher	
					  	nombre_pdf = [data.pdf+'.pdf'];
                    	clave_Acceso = [data.clave+'.xml'];
						enviar_email_comprobantes(nombre_pdf,clave_Acceso);
						var  id = data.factura
						var serie =   $('#LblSerie').text(); //data[0].serie
						var ci =  data.codigoc
						var aut =  data.auto
						var tc =  'NDO';
						Ver_nd(id,serie,ci,aut,tc);
		
						$('#imprimir_nd').removeAttr('disabled');
						var url = '../../TEMP/' + data.pdf + '.pdf';							
						window.open(url, '_blank');


				})
			} else if (data.respuesta[0] == -1) {
				if(data.text=='' || data.text == null)
				{
					Swal.fire({
						icon: 'error',
						title: 'XML devuelto',
						text:'Error al generar XML o al firmar',
						confirmButtonText: 'Ok!',
						allowOutsideClick: false,
					}).then(function () {
						// location.reload();
					})
				
					tipo_error_sri(data.clave);
				}else{
				Swal.fire({
						icon: 'error',
						title: data.text,
						confirmButtonText: 'Ok!',
						allowOutsideClick: false,
					}).then(function () {
						location.reload();
					})
				}
			} else if (data.respuesta == 2) {
				// Swal.fire('XML devuelto', '', 'error');
				Swal.fire({
					icon: 'error',
					title: 'XML devuelto',
					text:'Error al generar XML o al firmar',
					confirmButtonText: 'Ok!',
					allowOutsideClick: false,
				}).then(function () {
					location.reload();
				})
			
				tipo_error_sri(data.clave);
			}else if (data.respuesta == 4) {
				Swal.fire('SRI intermitente intente mas tarde', '', 'info');
			} else {
				Swal.fire(data.text, '', 'error');
			}
		},
      error: function(xhr, status, error){
        	console.error("Error en la solicitud: ", xhr, status, error);
			setTimeout(()=>{
				$('#myModal_espera').modal('hide');
			}, 1000)
		}
	});

}

function Ver_nd(id,serie,ci,aut,tc)
{
	 
    var peri = '.';
    var url = '../controlador/facturacion/lista_ndo_nduC.php?ver_fac=true&codigo='+id+'&ser='+serie+'&ci='+ci+'&per='+peri+'&auto='+aut+'&tc='+tc;
    var html='<iframe style="width: 48mm; height: 100vh; border: none;" src="'+url+'&pdf=no" frameborder="0" allowfullscreen id="re_ticket"></iframe>';
    $('#re_frame').html(html);
    // document.getElementById('re_ticket').contentWindow.print();
    const iframeWindow = document.getElementById('re_ticket').contentWindow;
    
    iframeWindow.onbeforeprint = function() {
	    iframeWindow.document.title = "ND_"+serie+"_"+generar_ceros(id,7)+".pdf"; // Nombre deseado
	};
	iframeWindow.onafterprint = function() {
	    window.location.reload(); // Recarga la página principal
	};
	iframeWindow.print();
}



	function DCEfectivo() {
		// alert('das');
		$('#DCEfectivo').select2({
			placeholder: 'Seleccione cuenta',
			dropdownParent: $('#modalInfoFactura'),
			width: '100%',
			ajax: {
				url: '../controlador/facturacion/facturas_distribucion_famC.php?DCEfectivo=true',
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					return {
						results: data
					};
				},
				cache: true
			}
		});


        $('#DCEfectivo').append($('<option>',{value:'1.1.01.01.04', text: '1.1.01.01.04 Caja Fundaciones',selected: true }));
	}


	function guardar_bouche(){
		//$('#myModal_espera').modal('show');
		let formData = new FormData();
		formData.append('file', $('#archivoAdd')[0].files[0]);
		formData.append('n_factura', $('#TextFacturaNo').val());
		formData.append('serie', $('#LblSerie').text());
		formData.append('fecha', $('#MBFecha').val().replaceAll('-',''));

		$.ajax({
			type: 'POST',
			url: '../controlador/facturacion/facturas_distribucionC.php?GuardarBouche=true',
			processData: false,
			contentType: false,
			data: formData,
			dataType: 'json',
			success: function (respuesta) {
				if(respuesta['res'] == 1){
					/*let ruta = '../../TEMP/catalogo_procesos/' + respuesta['imagen'];
					$('#picture').val(respuesta['imagen'].split('.')[0]);
					$('#imageElement').prop('src',ruta);*/
					docbouche = respuesta['documento'];
					generar_factura();
				}else{
					setTimeout(()=>{
						$('#myModal_espera').modal('hide');
					}, 1000)
					/*$('#picture').val(".");
					$('#imageElement').prop('src','');
					Swal.fire("Error", "Hubo un problema al mostrar la imagen", "error");*/
					Swal.fire('Error al subir archivo', '', 'error');
				}
			},
			error: function (error) {
				/*$('#myModal_espera').modal('hide');
				$('#picture').val(".");
				$('#imagenPicker').val('');
				$('#imageElement').prop('src','');*/
				Swal.fire("Error al procesar el archivo", "Error: " + error, "error");
			}
		});
	}

	function guardar_bouche2(){
		// if($('#archivoAdd2').val()!='')
		// {
			//$('#myModal_espera').modal('show');
			let formData = new FormData();
			formData.append('file', $('#archivoAdd2')[0].files[0]);
			formData.append('n_factura', $('#TextFacturaNo').val());
			formData.append('serie', $('#LblSerie').text());
			formData.append('fecha', $('#MBFecha').val().replaceAll('-',''));

			$.ajax({
				type: 'POST',
				url: '../controlador/facturacion/facturas_distribucionC.php?GuardarBouche=true',
				processData: false,
				contentType: false,
				data: formData,
				dataType: 'json',
				success: function (respuesta) {
					if(respuesta['res'] == 1){
						/*let ruta = '../../TEMP/catalogo_procesos/' + respuesta['imagen'];
						$('#picture').val(respuesta['imagen'].split('.')[0]);
						$('#imageElement').prop('src',ruta);*/
						docbouche = respuesta['documento'];
						// generar_factura();
					}else{
						setTimeout(()=>{
							$('#myModal_espera').modal('hide');
						}, 1000)
						/*$('#picture').val(".");
						$('#imageElement').prop('src','');
						Swal.fire("Error", "Hubo un problema al mostrar la imagen", "error");*/
						Swal.fire('Error al subir archivo', '', 'error');
					}
				},
				error: function (error) {
					/*$('#myModal_espera').modal('hide');
					$('#picture').val(".");
					$('#imagenPicker').val('');
					$('#imageElement').prop('src','');*/
					Swal.fire("Error al procesar el archivo", "Error: " + error, "error");
				}
			});
		// }
	}

	function anular_picking(){
		Swal.fire({
			title: "¿Está seguro que desea anular?",
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'SI',
			cancelButtonText: 'NO'
		}).then((result) => {
			if (result.value) {
				IngClave('Supervisor');
			}
		});
		
		// $.ajax({
		// 	type: "POST",
		// 	url: '../controlador/facturacion/facturas_distribucionC.php?AnularPicking=true',
		// 	data: { parametros: parametros },
		// 	dataType: 'json',
		// 	success: function (data) {
		// 		if(data == 1){

		// 		}
		// 		//$('#Cod_CxC').val(data[0].nombre);  //FA
		// 		//Lineas_De_CxC();
		// 	}
		// });
	}

	function resp_clave_ingreso(response) {
		if (response.respuesta == 1) {
			let parametros = {
				'beneficiario': $('#DCCliente').val(),
				'fecha': $('#MBFecha').val(),
				'orden':$('#txt_pedido').val(),
			};
			$.ajax({
				type: "POST",
				url: '../controlador/facturacion/facturas_distribucionC.php?AnularPicking=true',
				data: { parametros: parametros },
				dataType: 'json',
				success: function (data) {
					$('#clave_supervisor').modal('hide');
					if(data == 1){
						Swal.fire('Anulado correctamente', '', 'success').then((result)=>{
							location.reload();
						});
					}else{
						Swal.fire('Ocurrio un problema al anular', '', 'error');
					}
					//$('#Cod_CxC').val(data[0].nombre);  //FA
					//Lineas_De_CxC();
				}
			});
        }
	}

	function ListaFacturas()
	{
		$('#myModal_listaFacturas').modal('show');
		var parametros = 
		{
			'fecha':$('#txt_date_facturas').val(),
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucionC.php?ListaFacturas=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				
				$('#tbl_lista_facturas').html(data);
			}
		});
	}

	function cuadrarNdo(factura,serie,beneficiario)
	{
		$('#myModal_espera').modal('show');
		var parametros = 
		{
			'factura':factura,
			'serie':serie,
			'CodigoC':beneficiario,
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucionC.php?cuadrarNdo=true',
			data: {parametros,parametros},
			dataType: 'json',
			success: function (data) {

				$('#myModal_espera').modal('hide');
				if(data==1)
				{
					ListaFacturas();
				}
				
			}
		});

	}

	function view_banco()
	{
		if($('#cbx_banco_2').prop('checked'))
		{
			$('#campos_fact_banco_2').show()
		}else
		{
			$('#campos_fact_banco_2').hide()			
		}
	}


	function enviar_email_comprobantes(nombre_pdf,clave_Acceso)
	{
		if($('#Lblemail').val()!=""){
		    $('#myModal_envio_Email').modal('show');
		    var parametros = {
		        'clave':clave_Acceso,
		        'pdf':nombre_pdf,
		        'correo':$('#Lblemail').val(),
		    }
		    $.ajax({
		        type: "POST",
		        url: '../controlador/facturacion/punto_ventaC.php?enviar_email_comprobantes=true',
		        data: {
		            parametros: parametros
		        },
		        dataType: 'json',
		        success: function(data) {
		            console.log(data);
		            $('#myModal_envio_Email').modal('hide');        
		            if(data==1)
		            {
		                Swal.fire('Email enviado','','success').then(function(){
		                    location.reload();
		                });
		            }   
		        }
		    });
		}
	}