$(document).ready(function () {
	autocomplete_cliente();
	DCLineasNPA();

	DCLineas()
	DCPorcenIvaFD();

	DCBanco();
	DCEfectivo();

	$('#DCCliente').on('select2:select', function (e) {
	    var data = e.params.data.data;
	    console.log(data);
	    $('#Lblemail').val(data.Email);
	    $('#LblRUC').val(data.CI_RUC);
	    $('#codigoCliente').val(data.Codigo);
	    $('#LblT').val(data.T);
	    $('#DCDireccion').val(data.Direccion);

	    console.log(data);
	  });

})

function autocomplete_cliente() {
	$('#DCCliente').select2({
		width: '62%',
		placeholder: 'Seleccione un cliente',
		ajax: {
			url: '../controlador/facturacion/facturas_productoresAliC.php?DCCliente=true',
			dataType: 'json',
			delay: 250,
			data: function (params) {
                return {
                    query: params.term,
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


function  DCLineasNPA() {
	var TC = $('#DCTipoFact2').val();
	var parametros =
	{
		'TC': TC,
	}
	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturas_distribucion_famC.php?LblSerieNDU=true',
		data: { parametros: parametros },
		dataType: 'json',
		success: function (data) {
			console.log(data);
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


function numeroFactura() {
	DCLinea = $("#DCLinea").val();
	// console.log(DCLinea);
	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturar_pensionC.php?numFactura=true',
		data: {
			'DCLinea': DCLinea,
		},
		dataType: 'json',
		success: function (data) {
			datos = data;
			document.querySelector('#LblSerie').innerText = datos.serie;
			$("#TextFacturaNo").val(datos.codigo);
		}
	});
}


function validar_cta() {
	var parametros =
	{
		'TC': $('#DCTipoFact2').val(),
		'Serie': $('#LblSerie').text(),
		'Fecha': $('#MBFecha').val(),
	}
	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturas_distribucion_famC.php?validar_cta=true',
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

function pedido_seleccionado()
{
	var parametros =
	{
		'Pedido': $('#DCCliente').val(),
	}
	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturas_productoresAliC.php?pedido_seleccionado=true',
		data: { parametros: parametros },
		dataType: 'json',
		success: function (data) {
			var tr = '';
			var total_total = 0;
			var cantidad = 0;
			data.forEach(function(item,i){
				var total_costo = parseFloat(item.Total)*parseFloat(item.PVP);
				tr+=`<tr>
						<td>`+item.CodBodega+`</td>
						<td>`+item.Nombre_Completo+`</td>
						<td>`+item.Producto+`</td>
						<td><input class="form-control form-control-sm" id="txt_cant_`+item.ID+`" value="`+item.Total+`"></td>
						<td><input class="form-control form-control-sm" id="txt_pvp_`+item.ID+`" value="`+item.PVP+`" onblur="recalcular_linea(`+item.ID+`)"></td>
						<td><span id="txt_total_linea_`+item.ID+`">`+total_costo.toFixed(4)+`</span></td>
						<td><input type="checkbox" id="producto_recalcular_`+item.ID+`" name="producto_recalcular" onchange="recalcularLineaFact(`+item.ID+`)" class="rbl_producto"></td>
						<td><input type="checkbox" id="producto_cheking_`+item.ID+`" name="producto_cheking" class="form-check-input border-secondary"></td>
					</tr>`;
					total_total = total_total+total_costo;
					cantidad = cantidad+parseFloat(item.Total);
			})

			tr+=`<tr>
					<td colspan="3"><b>Total</b></td>
					<td>`+cantidad.toFixed(4)+`</td>
					<td></td>
					<td><b id="txt_total_tabla">`+total_total.toFixed(4)+`</b></td>
					<td></td>
					<td></td>
				</tr>`;

			console.log(total_total)
			$('#LabelTotal2').val(total_total)
			$('#LabelSubTotal').val(total_total.toFixed(4));
			$('#LabelTotal').val(total_total.toFixed(4));
			$('#cuerpoTablaDistri').html(tr);
		}
	});

}

function recalcularLineaFact(id)
{
	if($('#txtRecalcular').val()=='' || $('#txtRecalcular').val()=='')
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

function  recalcular_linea(id)
{
	var cant  = $('#txt_cant_'+id).val();
	var pvp = $('#txt_pvp_'+id).val();
	var total = parseFloat(cant)*parseFloat(pvp);
	$('#txt_total_linea_'+id).text(total.toFixed(4));
	recalcular_todo();
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
		$('#txt_total_linea_'+item).text(valor.toFixed(4));
		total_total = total_total+valor;
	})

	var total_de_totales = parseFloat(total_total)+parseFloat(total_sin_recalculo);
	$('#LabelTotal2').val(total_de_totales.toFixed(4));
	$('#txt_total_tabla').text(total_de_totales.toFixed(4))
	$('#LabelSubTotal').val(total_de_totales.toFixed(4));
	$('#LabelTotal').val(total_de_totales.toFixed(4));

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
	if (cotizacion > 0) {
		if (parseFloat(efectivo) > 0) {
			var ca = efectivo - Total_Factura + Total_banco;
			$('#LblCambio').val(ca.toFixed(2));
			$('#btn_g').focus();
		}
	} else {
		if (efectivo > 0 || Total_banco > 0) {
			var ca = (parseFloat(efectivo)-parseFloat(Total_Factura2)+parseFloat(Total_banco));
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

function valores_por_defecto(id)
{
	var parametros =
	{
		'Pedido': $('#DCCliente').val(),
		'id': id,
	}
	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturas_productoresAliC.php?buscar_linea=true',
		data: { parametros: parametros },
		dataType: 'json',
		success: function (data) {
			if(data.length>0)
			{
				var cant = data[0].Total;
				var pvp = data[0].PVP;
				$('#txt_pvp_'+id).val(data[0].PVP);
				var Total = parseFloat(cant)*parseFloat(pvp)
				$('#txt_total_linea_'+id).text(Total.toFixed(4));
			}
			 recalcular_todo();
			// console.log(data);
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
			llenarComboList(data, 'DCLineas');
			//$('#Cod_CxC').val(data[0].nombre);  //FA
			//Lineas_De_CxC();
		}
	});
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
}

function toggleInfoBanco(){
	let btn = $('#btnToggleInfoBanco');
	if(btn.attr('stateval') == "1"){
		$('#campos_fact_banco').hide();
		$('#bouche_banco_input').hide();
		btn.addClass('btn-light');
		btn.addClass('border');
		btn.addClass('border-1');
		btn.removeClass('btn-primary');
		btn.attr('stateval', '0');
	}else{
		$('#campos_fact_banco').show();
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

	total_abono = parseFloat(efectivo)+parseFloat(banco);
	
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
				generar_factura();
			}
		})
	}else
	{
		generar_factura();
	}

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

	console.log(lineas);

	var parametros = {
		'lineas':JSON.stringify(lineas),
		'pedido': $('#DCCliente').val(),
		'MBFecha':$('#MBFecha').val(),
		'TC':$('#DCTipoFact2').val(),
		'serie':$('#LblSerie').text(),
		'NoFactura':$('#TextFacturaNo').val(),
		'ctaEfectivo':$('#DCEfectivo').val(),
		'valorEfectivo':$('#TxtEfectivo').val(),
		'DCBancoC':$('#DCBanco').val(),
		'TextCheqNo':$('#TextCheqNo').val(),
		'TextBanco':$('#TextBanco').val(),
		'valorBanco':$('#TextCheque').val(),
		'PorcIva':$('#DCPorcenIVA').val(),
		'CI':$('#LblRUC').val(),
	}
	$('#myModal_espera').modal('show');
	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturas_productoresAliC.php?generar_factura=true',
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
					var url = '../../TEMP/' + data.pdf + '.pdf';
					window.open(url, '_blank');
					location.reload();
					//crearAsientoFFA(parametros);
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