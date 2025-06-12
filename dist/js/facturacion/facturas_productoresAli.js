$(document).ready(function () {
	autocomplete_cliente();
	DCLineasNPA();

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
			data.forEach(function(item,i){
				var total_costo = parseFloat(item.Total)*parseFloat(item.PVP);
				tr+=`<tr>
						<td>`+item.CodBodega+`</td>
						<td>`+item.Nombre_Completo+`</td>
						<td>`+item.Producto+`</td>
						<td><input class="form-control form-control-sm" id="txt_cant_`+item.ID+`" value="`+item.Total+`"></td>
						<td><input class="form-control form-control-sm" id="txt_pvp_`+item.ID+`" value="`+item.PVP+`"></td>
						<td>`+total_costo.toFixed(4)+`</td>
						<td><input type="checkbox" id="producto_recalcular_`+item.ID+`" name="producto_recalcular" onchange="recalcularLineaFact(this)" class="form-check-input border-secondary"></td>
						<td><input type="checkbox" id="producto_cheking_`+item.ID+`" name="producto_cheking" class="form-check-input border-secondary"></td>
					</tr>`;
			})
			
			$('#cuerpoTablaDistri').html(tr);
		}
	});

}