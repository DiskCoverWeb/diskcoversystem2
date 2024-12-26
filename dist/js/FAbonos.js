let FechaCorte = '.';

$(document).ready(function () {
	DCVendedor();
	DiarioCaja();
	DCBanco();
	DCTarjeta();
	DCRetFuente();
	DCRetISer();
	DCRetIBienes();
	DCCodRet();
	DCTipo();
	$('#DCTipo').on('change', DCSerie);

});

function DCVendedor() {
	$('#DCVendedor').select2({
		placeholder: 'Vendedor',
		width: 'resolve',
		ajax: {
			url: '../controlador/contabilidad/FAbonosC.php?DCVendedor=true',
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

function DCBanco() {
	$('#DCBanco').select2({
		placeholder: 'Cuenta Banco',
		ajax: {
			url: '../controlador/contabilidad/FAbonosC.php?DCBanco=true',
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
function DCTarjeta() {
	$('#DCTarjeta').select2({
		placeholder: 'Cuenta Banco',
		ajax: {
			url: '../controlador/contabilidad/FAbonosC.php?DCTarjeta=true',
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
function DCRetFuente() {
	$('#DCRetFuente').select2({
		placeholder: 'Cuenta Banco',
		ajax: {
			url: '../controlador/contabilidad/FAbonosC.php?DCRetFuente=true',
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
function DCRetISer() {
	$('#DCRetISer').select2({
		placeholder: 'Cuenta Banco',
		ajax: {
			url: '../controlador/contabilidad/FAbonosC.php?DCRetISer=true',
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
function DCRetIBienes() {
	$('#DCRetIBienes').select2({
		placeholder: 'Cuenta Banco',
		ajax: {
			url: '../controlador/contabilidad/FAbonosC.php?DCRetIBienes=true',
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
function DCCodRet() {
	var MBFecha = $('#MBFecha').val();
	$('#DCCodRet').select2({
		placeholder: 'Cuenta Banco',
		ajax: {
			url: '../controlador/contabilidad/FAbonosC.php?DCCodRet=true&MBFecha=' + MBFecha,
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

	$('#DCCodRet').on('select2:select', function (e) {
		var data = e.params.data.datos;
		$('#TextPorc').val(data.Porcentaje);
	});
}
function DCTipo() {
	$.ajax({
		type: "POST",
		url: '../controlador/contabilidad/FAbonosC.php?DCTipo=true',
		// data: {parametros: parametros},
		dataType: 'json',
		success: function (data) {
			llenarComboList(data, 'DCTipo');
			buscarDCSerie();
		}
	});

}

function buscarDCSerie() {
	var parametros =
	{
		'tipo': $('#DCTipo').val(),
	}
	$.ajax({
		type: "POST",
		url: '../controlador/contabilidad/FAbonosC.php?DCSerie=true',
		data: { parametros: parametros },
		dataType: 'json',
		success: function (data) {
			if ($('#DCTipo').val() == 'PV') {
				$('#Label2').text('Punto de Venta No.');
			} else if ($('#DCTipo').val() == 'NV') {
				$('#Label2').text('Nota de Venta No.');
			} else {
				$('#Label2').text('Factura No.');
			}
			llenarComboList(data, 'DCSerie');
			DCFactura_();
		}
	});

}

function DCFactura_() {
	var parametros =
	{
		'tipo': $('#DCTipo').val(),
		'serie': $('#DCSerie').val(),
	}
	$.ajax({
		type: "POST",
		url: '../controlador/contabilidad/FAbonosC.php?DCFactura=true',
		data: { parametros: parametros },
		dataType: 'json',
		success: function (data) {
			llenarComboList(data, 'DCFactura');
		}
	});
}

function DCFactura1() {
	var parametros =
	{
		'tipo': $('#DCTipo').val(),
		'serie': $('#DCSerie').val(),
		'factura': $('#DCFactura').val(),
	}
	$.ajax({
		type: "POST",
		url: '../controlador/contabilidad/FAbonosC.php?DCFactura1=true',
		data: { parametros: parametros },
		dataType: 'json',
		success: function (data) {
			$('#LabelSaldo').val(parseFloat(data[0].Saldo_MN).toFixed(2));
			$('#TextCajaMN').val(parseFloat(data[0].Saldo_MN).toFixed(2));
			$('#LblCliente').val(data[0].Cliente);
			$('#LblGrupo').val(data[0].Grupo);
			$('#LabelDolares').val(parseFloat(data[0].Cotizacion).toFixed(2));
			$('#Cta_Cobrar').val(data[0].Cta_CxP);
			$('#CodigoC').val(data[0].CodigoC);
			$('#CI_RUC').val(data[0].CI_RUC);
			Calculo_Saldo();
			// $('#').val(data[0].);
			FechaCorte = new Date(data[0].Fecha.date.split(' ')[0]);
			const fechaInput = new Date($('#MBFecha').val());
			if (fechaInput < FechaCorte) {
				Swal.fire({
					title: 'Error',
					text: 'No se puede grabar abonos con fecha inferior a la emision de la factura',
					type: 'error',
				});
			}
			var dia = FechaCorte.getDate();
			var mes = FechaCorte.getMonth() + 1;
			var anio = FechaCorte.getFullYear();
			FechaCorte = `${anio}-${mes}-${dia}`;
			$('#LabelAutorizacion').text(`Autorizacion Fecha de Emisión ${FechaCorte}`);
		}
	});
}

function DiarioCaja() {
	var parametros =
	{
		'CheqRecibo': $('#CheqRecibo').prop('checked'),
	}
	$.ajax({
		type: "POST",
		url: '../controlador/contabilidad/FAbonosC.php?DiarioCaja=true',
		data: { parametros: parametros },
		dataType: 'json',
		success: function (data) {
			$('#TxtRecibo').val(data);
		}
	});
}


function DCAutorizacionF() {
	var parametros =
	{
		'tipo': $('#DCTipo').val(),
		'serie': $('#DCSerie').val(),
		'factura': $('#DCFactura').val(),
	}
	$.ajax({
		type: "POST",
		url: '../controlador/contabilidad/FAbonosC.php?DCAutorizacion=true',
		data: { parametros: parametros },
		dataType: 'json',
		success: function (data) {
			llenarComboList(data, 'DCAutorizacion');
		}
	});

}

function Calculo_Saldo() {

	var TotalCajaMN = $('#TextCajaMN').val();
	var TotalCajaME = $('#TextCajaME').val();
	var Total_IVA = 0;
	var Total_Bancos = $('#TextCheque').val();
	var Total_Tarjeta = $('#TextTotalBaucher').val();
	var Total_Ret = $('#TextRet').val();
	var Total_RetIVAB = $('#TextRetIVAB').val();
	var Total_RetIVAS = $('#TextRetIVAS').val();
	var Saldo = $('#LabelSaldo').val();
	// console.log(TotalCajaMN);
	// console.log(TotalCajaME);
	// console.log(Total_IVA);
	// console.log(Total_Bancos);
	// console.log(Total_Tarjeta);
	// console.log(Total_Ret);
	// console.log(Total_RetIVAB);
	// console.log(Total_RetIVAS);
	// console.log(Saldo);
	// console.log()


	var TotalAbonos = parseFloat(TotalCajaMN) + parseFloat(TotalCajaME) + parseFloat(Total_Bancos) + parseFloat(Total_Tarjeta) + parseFloat(Total_IVA) + parseFloat(Total_Ret) + parseFloat(Total_RetIVAB) + parseFloat(Total_RetIVAS);
	var SaldoDisp = parseFloat(Saldo) - parseFloat(TotalAbonos);
	var TotalRecibido = TotalAbonos + parseFloat($('#TextInteres').val());
	$('#LabelPend').val(SaldoDisp.toFixed(2));
	$('#TextRecibido').val(TotalRecibido.toFixed(2));
}

function calcTextInteres() {
	var TextInteres = $('#TextInteres').val();
	if (TextInteres.substring(TextInteres.length, 1) == "%") {
		var Valor = TextInteres.substring(0, TextInteres.length - 1);
		console.log($('#LabelPend').val());
		TextInteres = parseFloat(Valor) * parseFloat(($('#LabelPend').val()) / 100);
		$('#TextInteres').val(TextInteres.toFixed(2));
	} else {
		console.log(TextInteres);
	}
	TextRecibido();
}

function TextRecibido() {
	var TotalCajaMN = $('#TextCajaMN').val();
	var TotalCajaME = $('#TextCajaME').val();
	var Total_IVA = 0;
	var Total_Bancos = $('#TextCheque').val();
	var Total_Tarjeta = $('#TextTotalBaucher').val();
	var Total_Ret = $('#TextRet').val();
	var Total_RetIVAB = $('#TextRetIVAB').val();
	var Total_RetIVAS = $('#TextRetIVAS').val();
	var Saldo = $('#LabelSaldo').val();
	var TotalAbonos = parseFloat(TotalCajaMN) + parseFloat(TotalCajaME) + parseFloat(Total_Bancos) + parseFloat(Total_Tarjeta) + parseFloat(Total_IVA) + parseFloat(Total_Ret) + parseFloat(Total_RetIVAB) + parseFloat(Total_RetIVAS);
	var TextInteres = parseFloat($('#TextInteres').val());
	var TextRecibido = TotalAbonos + TextInteres;
	$('#TextRecibido').val(TextRecibido.toFixed(2));
}

function guardar_abonos() {

	const fechaInput = new Date($('#MBFecha').val());
	if (fechaInput < FechaCorte) {
		Swal.fire({
			title: 'Error',
			text: 'No se puede grabar abonos con fecha inferior a la emision de la factura',
			type: 'error',
		});
		return;
	
	}

	Swal.fire({
		title: 'Esta Seguro que desea grabar estos pagos.',
		text: '',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Si!'
	}).then((result) => {
		if (result.value == true) {
			Grabar_abonos();
		}
	})
}

function Grabar_abonos() {
	var datos = $('#form_abonos').serialize();
	var fac = $('#DCSerie').val() + '-' + $('#DCFactura').val();
	$.ajax({
		type: "POST",
		url: '../controlador/contabilidad/FAbonosC.php?Grabar_abonos=true',
		data: datos,
		dataType: 'json',
		success: function (data) {
			Swal.fire('Abono Guardado', '', 'success').then(function () {
				var url_compro = '../../TEMP/' + data.TJ + '.pdf';
				window.open(url_compro, '_blank');
				cerrar_modal();
			})
		}
	});

}

function cerrar_modal() {
	parent.cerrarModal();
}







