var valTC = 'FA';

$(document).ready(function () {
	serie();

})

function serie() {
	var parametros =
	{
		'TC':'NDO',
	}
	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturas_distribucionC.php?LblSerie=true',
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