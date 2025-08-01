var valTC = 'FA';
	var datosFact = "NDO";
	var url_nd;
	var docbouche;
	eliminar_linea('', '');
	$(document).ready(function () {
		$.ajax({
			type: "GET",
			url: '../controlador/facturacion/facturas_distribucionC.php?Sesion=true',
			dataType: 'json',
			success: function (datos) {
				console.log(datos);
			}
		})
		valTC = $('#hiddenTC').val();
		let area = $('#contenedor-pantalla').parent();
    	//area.css('background-color', 'rgb(247, 232, 175)');
		//catalogoLineas();
		//toggleInfoEfectivo();
		DCLineas();
		preseleccionar_opciones();
		//eventos_select();
		autocomplete_cliente();
		autocomplete_producto();
		//serie();
		//tipo_documento();
		DCBodega();
		DGAsientoF();
		AdoLinea();
		AdoAuxCatalogoProductos();
		construirTablaGavetas();
		construirTablaEvalFundaciones();
		//DCDireccion();
		$('#DCDireccion').on('blur', function () {
			var DireccionAux = $('#DCDireccion').val();
			DCDireccion(DireccionAux);
		});


		DCBanco();

		$(document).keyup(function (e) {
			if (e.key === "Escape") { // escape key maps to keycode `27`
				//ingresar_total();
			}
			console.log(e.key);
		});


		// $('#DCCliente').on('select2:select', function (e) {
		//     var data = e.params.data.data;
		//     console.log(e);
		//     $('#Lblemail').val(data[0].Email);
		//     $('#LblRUC').val(data[0].CI_RUC);
		//     $('#codigoCliente').val(data[0].Codigo);
		//     $('#LblT').val(data[0].T);

		//     console.log(data);
		//   });

		//DCTipoFact2();
		//DCPorcenIvaFD();
	});

	function imprimirNotaDonacion(){
		if(url_nd != undefined){
			window.open(url_nd, '_blank');
		}else{
			Swal.fire('Error', 'No se creó correcatamente la Nota de Donación', 'error');
		}
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
				'fecha': $('#MBFecha').val()
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

	function alertaDesarrollo(msg){
		Swal.fire('Funcionalidad en Desarrollo', msg, 'info');
	}

	function Imprimir_Punto_Venta(Tipo_Facturas){
		alertaDesarrollo('El proceso de impresion aun se encuentra en desarrollo');
	}
	
	function FacturarAsignacion(Tipo_Facturas){
		alertaDesarrollo('El proceso de facturar aun se encuentra en desarrollo');
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

	function agregarArchivo(){
		let archivoBouche = $('#archivoAdd')[0].files[0];
		/*let descHtml = `
			<div class="col-sm-12" style="position:relative;display:flex; flex-direction:column; align-items:center;">
				<div style="position: relative;height: 60px;width: 60px;">                            
					<button style="padding: 0;background: none;border: none;color: red;position: absolute;right: -7px;top: -10px;" onclick="borrarArchivoBouche()"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
					<img src="../../img/png/document.png" style="width: 100%;height: 100%;">
				</div>
				<p style="margin-top:3px;">${archivoBouche.name}</p>
			</div>
		`;
		$('#modalDescContainer div').html(descHtml);*/
	}

	function borrarArchivoBouche(){
		$('#archivoAdd')[0].value='';
		$('#modalDescContainer div').html('');
	}

	function resetInputsGavetas(){
		let gavetasInputs = $('.gavetas_info');
		for (let gavInp of gavetasInputs){
			gavInp.value = '';
			if(gavInp.id.includes('total')){
				gavInp.value = 0;
			}
			$('#gavetas_entregadas').val(0);
			$('#gavetas_devueltas').val(0);
			$('#gavetas_pendientes').val(0);
		}
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
			/*td = (accion=='Editar')?$('<td></td>'):$(`<td id="gavetas_${colorGaveta.toLowerCase()}_entregadas_ver"></td>`).text('0');
			if(accion=='Editar'){
				td.append($(`<input type="text" class="form-control gavetas_info" id="gavetas_${colorGaveta.toLowerCase()}_entregadas" onchange="calcularTotalGavetas()">`));
			}
			tr.append(td);
			td = (accion=='Editar')?$('<td></td>'):$(`<td id="gavetas_${colorGaveta.toLowerCase()}_devueltas_ver"></td>`).text('0');
			if(accion=='Editar'){
				td.append($(`<input type="text" class="form-control gavetas_info" id="gavetas_${colorGaveta.toLowerCase()}_devueltas" onchange="calcularTotalGavetas()">`));
			}
			tr.append(td);
			td = (accion=='Editar')?$('<td></td>'):$(`<td id="gavetas_${colorGaveta.toLowerCase()}_pendientes_ver"></td>`).text('0');
			if(accion=='Editar'){
				td.append($(`<input type="text" class="form-control gavetas_info" id="gavetas_${colorGaveta.toLowerCase()}_pendientes" onchange="calcularTotalGavetas()">`));
			}
			tr.append(td);*/
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
		/*tr.html(totalGavetasHTML);
		tBody.append(tr);
		$('#tablaGavetas').append(tBody);*/
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

	/*function createTableFromContent(datos, elemento) {
		let indice = 0;
		let table = $('<table class="table"></table>')
		let tHead = $('<thead></thead>');
		let tBody = $('<tbody></tbody>');
		for(let fila of datos){
			let cols = Object.getOwnPropertyNames(fila);
			if(indice === 0){
				let tr = $('<tr></tr>');
				for(let col of cols){
					let th = $('<th></th>').text(col);
					tr.append(th);
				}
				tHead.append(tr);
			}

			let tr = $('<tr></tr>');
			for(let col of cols){
				let td = $('<td></td>').text(isNaN(parseInt(fila[col])) ? fila[col].trim() : fila[col]);
				tr.append(td);
			}
			tBody.append(tr);
			
			indice += 1;
		}
		table.append(tHead);
		table.append(tBody);
		$(`#${elemento}`).append(table);
	}*/

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

	/*function aceptarInfoGavetas(){
		let gavetasInfo = document.querySelectorAll('.gavetas_info');
		gavetasInfo.forEach((ginfo) => {
			if(ginfo.id.includes('total')){
				$(`#${ginfo.id}_ver`).html(`<b>${ginfo.value==''?0:ginfo.value}</b>`);
			}else{
				$(`#${ginfo.id}_ver`).html(`${ginfo.value==''?0:ginfo.value}`);
			}
		})
		$('#modalGavetasInfo').modal('hide');
	}*/

	// Deja preseleccionadas las opciones de los selects
	function preseleccionar_opciones(){
		$.ajax({
			type: "GET",
			url: '../controlador/facturacion/facturas_distribucionC.php?LlenarSelectIVA=true',
			dataType: 'json',
			success: function(data){
				//Escoge el primer registro
				let opcion = data[0]

				//Crea la opcion y la agrega como predefinida al select
				for(let d of data){
					let newOption = new Option(d['text'], d['id'], true, true);
					$("#DCPorcenIVA").append(newOption);
				}
				let DCPorcenIVA = document.getElementById('DCPorcenIVA');
				DCPorcenIVA.selectedIndex = 0;
				//$("#DCPorcenIVA").trigger('change');
				//cambiar_iva(DCPorcenIVA);
				let valor = DCPorcenIVA.selectedOptions[0].text;
				console.log(valor);
				$('#Label3').text('I.V.A. '+parseFloat(valor).toFixed(2)+'%');
				tipo_documento();
			}
		});
		
		/*$.ajax({
			type: "GET",
			url: '../controlador/facturacion/facturas_distribucionC.php?LlenarSelectTipoFactura=true',
			dataType: 'json',
			success: function(data){
				//Escoge el primer registro
				let opcion = data[0]
				
				console.log(opcion);
				//Crea la opcion y la agrega como predefinida al select
				let newOption = new Option(opcion['text'], opcion['id'], true, true);
				$("#DCTipoFact2").append(newOption).trigger('change');
				
				//Agrega el resto de datos al option predefinido
				let masDetalles = opcion['data'];
				$("#DCTipoFact2").select2("data")[0]['data'] = masDetalles;

				//Muestra Fact de la opcion predefinida en Label1
				$("#Label1").text(`FACTURA (${masDetalles[0]['Fact']}) NO.`);
			}
		});*/
	}

	//Agrega eventos a selects
	function eventos_select(){
		$("#DCTipoFact2").on('select2:select', (e)=> {
			datosFact = e.params.data['data'][0]['Fact'];
			console.log(datosFact);
			$("#Label1").text(`FACTURA (${datosFact}) NO.`);
			AdoLinea();
		})
	}

	function DCPorcenIvaFD(){
		let fecha = $("#MBFecha").val();
		$('#DCPorcenIVA').select2({
			placeholder: 'Seleccione IVA',
			dropdownParent: $('#modalInfoFactura'),
			ajax: {
				url: '../controlador/facturacion/facturas_distribucionC.php?LlenarSelectIVA=true',
				dataType: 'json',
				delay: 250,
				data: function (params) {
                    return {
                        query: params.term,
                        fecha: fecha
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

	function DCTipoFact2(){
		//let fecha = $("#MBFecha").val().replaceAll("-","");
		/*let newOption = new Option("Nota de Donacion Organizaciones", "CXCNDO", true, true);
		$("#DCTipoFact2").append(newOption).trigger('change');*/
		$('#DCTipoFact2').select2({
			width: '57%',
			placeholder: 'Seleccione Tipo de Factura',
			ajax: {
				url: '../controlador/facturacion/facturas_distribucionC.php?LlenarSelectTipoFactura=true',
				dataType: 'json',
				delay: 250,
				data: function (params) {
                    return {
                        query: params.term,
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

	function compararHoras(){
		if($("#HoraAtencion").val().trim() !== "" && $("#HoraLlegada").val().trim() !== ""){
			const [hours1, minutes1] = $("#HoraAtencion").val().split(':').map(Number);
			const [hours2, minutes2] = $("#HoraLlegada").val().split(':').map(Number);
	
			const date1 = new Date();
			const date2 = new Date();
	
			date1.setHours(hours1, minutes1, 0, 0);
			date2.setHours(hours2, minutes2, 0, 0);
			console.log(date1);
			console.log(date2);
	
			if (date1 < date2){
				$("#HorarioEstado").val("Atraso");
			}
		}
	}

	function AdoLinea() {
		var parametros =
		{
			'TipoFactura': datosFact
		};
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucionC.php?AdoLinea=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				if (data.mensaje.length > 0) {
					Swal.fire({
						icon: 'warning',
						title: data.mensaje,
						text: '',
						allowOutsideClick: false,
					});
				}
				$('#LblSerie').text(data.Serie);
				$('#TextFacturaNo').val(data.NumComp);
				$('#Cta_CxP').val(data.Cta_Cobrar);
				$('#Autorizacion').val(data.Autorizacion);
				$('#CodigoL').val(data.CodigoL);
			}
		});
	}

	function AdoAuxCatalogoProductos() {
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucionC.php?AdoAuxCatalogoProductos=true',
			dataType: 'json',
			success: function (data) {
				if (data.length > 0) {
					$('#OpcMult').prop('disabled', false);
					$('#OpcDiv').prop('disabled', false);
					$('#TextCotiza').prop('readonly', false);
				}
			}
		});
	}


	/*function usar_cliente(nombre, ruc, codigo, email, t = 'N') {
		$('#Lblemail').val(email);
		$('#LblRUC').val(ruc);
		$('#codigoCliente').val(codigo);
		$('#LblT').val(t);
		// $('#DCCliente').append('<option value="' +codi+ ' ">' + datos[indice].text + '</option>');
		$('#DCCliente').append($('<option>', { value: codigo, text: nombre, selected: true }));
		$('#myModal').modal('hide');
	}*/

	function select() {
		var seleccionado = $('#DCCliente').select2("data");
		var SaldoPendiente = 0;
		var CantSaldoPEndiente = 0;
		var dataS = seleccionado[0].data;
		console.log(dataS);
		$('#Lblemail').val(dataS[0].Email);
		$('#LblRUC').val(dataS[0].CI_RUC);
		$('#codigoCliente').val(dataS[0].Codigo);
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
				eliminar_linea('', '');
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
				$('#HoraAtencion').val(res['horarioEnt']);
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

	function formatearUnidadesProductos(unidad){
		//console.log("prueba unidad");
		//console.log(unidad);
		let unidades = {
			Kilos: "Kg"
		};
		return unidades[unidad];
	}

	function cargarRegistrosProductos(){
		$('#myModal_espera').modal('show');
		let codigoC = $('#codigoCliente').val();
		let parametros = {
			'beneficiario': codigoC,
			'fecha': $('#MBFecha').val()
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucionC.php?ConsultarProductos=true',
			data: { parametros },
			dataType: 'json',
			success: function (datos) {
				$('#cuerpoTablaDistri').remove();
				setTimeout(()=>{
					$('#myModal_espera').modal('hide');
				}, 1000)
				
				if (datos['res'] == 1) {
					$('#txtRecalcular').attr('disabled', true);
					let cTotalProds = 0;
					let tTotalProds = 0;
					let tBody = $('<tbody id="cuerpoTablaDistri"></tbody>');
					console.log(datos['contenido'][0]['FechaIng']);
					$('#txt_fecha_ing').val(datos['contenido'][0]['FechaIng']);
					for(let fila of datos['contenido']){
						color ='';
						if(color!='.')
						{
							color = 'style="background: '+fila['Tipo'][0]['Color'].replace('Hex_','#')+';"'
						}
						console.log(fila)
						let td;
						let tr = $('<tr '+color+'class="asignTablaDistri"></tr>');
						tr.append($('<td></td>').text(fila['Detalles']['CodBodega']));
						tr.append($('<td></td>').text(fila['Detalles']['Nombre_Completo']));
						tr.append($('<td></td>').text(fila['Productos']['Producto']));
						//tr.append($('<td></td>').text(parseInt(fila['Detalles']['Total'])));
						tr.append($('<td><div class="row justify-content-center"><input id="txt_cant_'+fila['Detalles']['ID']+'" class="form-control form-control-sm" onchange="modificarLineaFac(this)" style="max-width:95px;" value="'+fila['Detalles']['Total']+'" disabled></div></td>'));
						//tr.append($('<td></td>').text(parseFloat(fila['Productos']['PVP']).toFixed(2)));
						tr.append($('<td><div class="row justify-content-center"><input id="txt_pvp_'+fila['Detalles']['ID']+'" class="form-control form-control-sm" onchange="modificarLineaFac(this)" style="max-width:85px;" value="'+fila['Productos']['PVP']+'" valAnterior="'+fila['Productos']['PVP']+'" disabled></div></td>'));
						let totalProducto = fila['Detalles']['Total'] * fila['Productos']['PVP'];
						tr.append($('<td valAnterior="'+parseFloat(totalProducto).toFixed(8)+'"></td>').text(parseFloat(totalProducto).toFixed(8)));
						tr.append($('<td style="display:none;"></td>').text(fila['Detalles']['CodBodega2']));
						tr.append($('<td style="display:none;"></td>').text(fila['Productos']['Codigo_Inv']));
						tr.append($('<td></td>').html('<input type="checkbox" id="producto_recalcular_'+fila['Detalles']['ID']+'" name="producto_recalcular" onchange="recalcularLineaFact(this)" class="form-check-input border-secondary" disabled>'));
						tr.append($('<td></td>').html('<input type="checkbox" id="producto_cheking_'+fila['Detalles']['ID']+'" name="producto_cheking" class="form-check-input border-secondary" disabled>'));
						//tr.append($('<td></td>').html('<button style="width:50px" class="btn btn-sm btn-primary" onclick="modificarLineaFac(this)"><i class="bx bxs-pencil"></i></button>'));
						tr.append($('<td style="display:none;"></td>').text(fila['Detalles']['CodigoU']));
						tr.append($('<td style="display:none;"></td>').text(fila['Detalles']['Cta']));
						tBody.append(tr);

						cTotalProds += parseFloat(fila['Detalles']['Total']);
						tTotalProds += parseFloat(totalProducto);
						// console.log(tTotalProds);
					}
					let tr = $('<tr class="bg-primary-subtle"></tr>');
					tr.append($('<td colspan="3"></td>').html('<b>Total</b>'));
					tr.append($('<td id="ADCantTotal"></td>').html(`<b>${cTotalProds}</b>`));
					tr.append($('<td></td>'));
					tr.append($('<td id="ADTotal"></td>').html(`<b>${tTotalProds.toFixed(8)}</b>`));
					tr.append($('<td colspan="2"></td>'));
					tBody.append(tr);
					$('#tbl_DGAsientoF table').append(tBody);
					$('#kilos_distribuir').val(cTotalProds);
					let unidadProd = formatearUnidadesProductos(datos['contenido'][0]['Productos']['Unidad']);
					$('#tablaProdCU').text(unidadProd);
					$('#unidad_dist').val(unidadProd);
					buscarValoresGavetas();
					ingresarAsientoF();
				}
			}
		})
	}
	/*var nuevoValor = null;
	var filaTbl = null;*/

	function recalcularLineaFact(elemento = null){
		
		if(elemento && $(elemento).prop('checked') == false){
			let cbxFila = elemento.parentElement.parentElement;
			cbxFila.childNodes[5].innerText = cbxFila.childNodes[5].getAttribute('valAnterior');
			cbxFila.childNodes[4].children[0].children[0].value = cbxFila.childNodes[4].children[0].children[0].getAttribute('valAnterior');

				let campoid = elemento.id.split('_');
				console.log(campoid);
				let idTC = campoid[2];

			let tc = datosFact;
			let parametros =
			{
				//'opc': $('input[name="radio_conve"]:checked').val(),
				//'comentario': comentario,
				'TextVUnit': cbxFila.childNodes[4].children[0].children[0].value,
				'TextCant': cbxFila.childNodes[3].children[0].children[0].value,
				'TC': tc,
				'TxtDocumentos': '.',
				'Codigo': cbxFila.childNodes[7].textContent,
				'fecha': $('#MBFecha').val(),
				'CodBod': cbxFila.childNodes[6].textContent,
				'CodBod2': cbxFila.childNodes[0].textContent,
				'VTotal': cbxFila.childNodes[5].textContent,
				/*'TxtRifaD': $('#TxtRifaD').val(),
				'TxtRifaH': $('#TxtRifaH').val(),*/
				'Serie': $('#LblSerie').text(),
				'CodigoCliente': $('#codigoCliente').val(),
				'TextServicios': '.',
				'TextVDescto': 0,
				'PorcIva': document.getElementById('DCPorcenIVA').selectedOptions[0].text,
				'cheking': cbxFila.childNodes[8].children[0].checked==true?1:0,
				'IdTransComi':idTC,
			}
			console.log(parametros);
			$('#myModal_espera').modal('show');
			$.ajax({
				type: "POST",
				url: '../controlador/facturacion/facturas_distribucionC.php?ActualizarAsientoF=true',
				data: { parametros: parametros },
				dataType: 'json',
				success: function (data) {
					setTimeout(()=>{
						$('#myModal_espera').modal('hide');
					}, 500);

					if (data == 2) {
						Swal.fire('Ya no puede ingresar mas productos', '', 'info');
					} else if (data == 1) {
						//DGAsientoF();
						Calculos_Totales_Factura();
					} else {
						Swal.fire('Intente mas tarde', '', 'info');
					}
				}
			});
		}

		
		let recalculados = 0;
		let valorRecalcular = $('#txtRecalcular').val();
		if(valorRecalcular.trim() == ""){
			$('#txtRecalcular').val(0);
			valorRecalcular = 0;
		}else{
			valorRecalcular = parseFloat(valorRecalcular);
		}
		let cantRecalcular = $("input[name='producto_recalcular']:checked").length;
		let totalRecal = valorRecalcular / cantRecalcular;
		
		if(cantRecalcular > 0){
			$('#myModal_espera').modal('show');

			$("input[name='producto_recalcular']:checked").each((i,x)=>{
				let fila = $(x)[0].parentElement.parentElement;
				fila.childNodes[5].innerText = totalRecal.toFixed(8);
				let nuevoValor = fila.childNodes[3].children[0].children[0].value;

				// console.log(fila);

				console.log(fila.childNodes[4].children[0].children[0]);
				let campoid = fila.childNodes[4].children[0].children[0].id.split('_');
				console.log(campoid);
				let idTC = campoid[2];

	
				if(nuevoValor.trim() == ""){
					fila.childNodes[3].children[0].children[0].value = "0";
					nuevoValor = 0;
				}
		
				let nuevoPVP = totalRecal / parseFloat(nuevoValor);
		
				fila.childNodes[4].children[0].children[0].value = nuevoPVP.toFixed(8);
	
				let tc = datosFact;
				//console.log(comentario);
				let parametros =
				{
					//'opc': $('input[name="radio_conve"]:checked').val(),
					//'comentario': comentario,
					'TextVUnit': fila.childNodes[4].children[0].children[0].value,
					'TextCant': fila.childNodes[3].children[0].children[0].value,
					'TC': tc,
					'TxtDocumentos': '.',
					'Codigo': fila.childNodes[7].textContent,
					'fecha': $('#MBFecha').val(),
					'CodBod': fila.childNodes[6].textContent,
					'CodBod2': fila.childNodes[0].textContent,
					'VTotal': fila.childNodes[5].textContent,
					/*'TxtRifaD': $('#TxtRifaD').val(),
					'TxtRifaH': $('#TxtRifaH').val(),*/
					'Serie': $('#LblSerie').text(),
					'CodigoCliente': $('#codigoCliente').val(),
					'TextServicios': '.',
					'TextVDescto': 0,
					'PorcIva': document.getElementById('DCPorcenIVA').selectedOptions[0].text,
					'cheking': fila.childNodes[8].children[0].checked==true?1:0,
					'IdTransComi':idTC,
				}
				console.log(parametros);
				
				$.ajax({
					type: "POST",
					url: '../controlador/facturacion/facturas_distribucionC.php?ActualizarAsientoF=true',
					data: { parametros: parametros },
					dataType: 'json',
					success: function (data) {
						recalculados += 1;
						if(cantRecalcular == recalculados){
							setTimeout(()=>{
								$('#myModal_espera').modal('hide');
							}, 500);
						}
	
						if (data == 2) {
							Swal.fire('Ya no puede ingresar mas productos', '', 'info');
						} else if (data == 1) {
							//DGAsientoF();
							Calculos_Totales_Factura();
						} else {
							Swal.fire('Intente mas tarde', '', 'info');
						}
					}
				});
			});
		}

		





		//let nuevoPVP = fila.childNodes[4].children[0].children[0].value;
		

		/*let costoTotal = parseInt(nuevoValor) * parseFloat(nuevoPVP);
		console.log(costoTotal);
		fila.childNodes[5].innerText = costoTotal.toFixed(2);*/

		let filas = $('.asignTablaDistri');
		let totalCant = 0;
		let ADTotal = 0;
		for(let f of filas){
			console.log(f);
			totalCant += parseInt(f.children[3].children[0].children[0].value);
			//console.log(f.children[3].children[0].value);
			ADTotal += parseFloat(f.children[5].innerText);
		}
		console.log(totalCant);
		$('#ADCantTotal').html(`<b>${totalCant}</b>`);
		$('#ADTotal').html(`<b>${ADTotal.toFixed(8)}</b>`);

		
		/*fila.childNodes[3].innerHTML = `
			<input type="text" class="form-control form-control-sm text-center" style="max-width:136px;" placeholder="Cambie la cantidad">
		`; //name = cod_prod + usuario_q_agg
		fila.childNodes[9].innerHTML = `
			<div class="input-group input-group-sm" style="min-width: 176px;">
				<input type="text" class="form-control form-control-sm" placeholder="Coloque un comentario">
				<button class="btn btn-sm btn-success" style="font-size:8pt;" onclick="aceptarModificarLF(this)"><i class="fa fa-check" aria-hidden="true" style="font-size:8pt;"></i></button>
				<button class="btn btn-sm btn-danger" style="font-size:8pt;" onclick="cancelarModificarLF(this, ${valAnt})"><i class="fa fa-times" aria-hidden="true" style="font-size:8pt;"></i></button>
			</div>
		`;*/
	}

	function modificarLineaFac(campo){
		// console.log(campo);
		let campoid = campo.id.split('_');
		let fila = campo.parentElement.parentElement.parentElement;
		let nuevoValor = fila.childNodes[3].children[0].children[0].value;
		let nuevoPVP = fila.childNodes[4].children[0].children[0].value;
		if(nuevoValor.trim() == ""){
			fila.childNodes[3].children[0].children[0].value = "0";
			nuevoValor = 0;
		}

		if(nuevoPVP.trim() == ""){
			fila.childNodes[4].children[0].children[0].value = "0";
			nuevoPVP = 0;
		}


		let costoTotal = parseFloat(nuevoValor) * parseFloat(nuevoPVP);
		// console.log(costoTotal);
		fila.childNodes[5].innerText = costoTotal.toFixed(2);

		let filas = $('.asignTablaDistri');
		let totalCant = 0;
		let ADTotal = 0;
		for(let f of filas){
			console.log(f);
			totalCant += parseFloat(f.children[3].children[0].children[0].value);
			// console.log(f.children[3].children[0].value);
			ADTotal += parseFloat(f.children[5].innerText);
		}
		// console.log(totalCant);
		$('#ADCantTotal').html(`<b>${totalCant}</b>`);
		$('#ADTotal').html(`<b>${ADTotal.toFixed(2)}</b>`);

		let tc = datosFact;
		let idTC = campoid[2];
		//console.log(comentario);
		let parametros =
		{
			//'opc': $('input[name="radio_conve"]:checked').val(),
			//'comentario': comentario,
			'TextVUnit': fila.childNodes[4].children[0].children[0].value,
			'TextCant': fila.childNodes[3].children[0].children[0].value,
			'TC': tc,
			'TxtDocumentos': '.',
			'Codigo': fila.childNodes[7].textContent,
			'fecha': $('#MBFecha').val(),
			'CodBod': fila.childNodes[6].textContent,
			'CodBod2': fila.childNodes[0].textContent,
			'VTotal': fila.childNodes[5].textContent,
			/*'TxtRifaD': $('#TxtRifaD').val(),
			'TxtRifaH': $('#TxtRifaH').val(),*/
			'Serie': $('#LblSerie').text(),
			'CodigoCliente': $('#codigoCliente').val(),
			'TextServicios': '.',
			'TextVDescto': 0,
			'PorcIva': document.getElementById('DCPorcenIVA').selectedOptions[0].text,
			'cheking': fila.childNodes[8].children[0].checked==true?1:0,
			'IdTransComi':idTC,
		}
		console.log(parametros);
		$('#myModal_espera').modal('show');
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucionC.php?ActualizarAsientoF=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				setTimeout(()=>{
					$('#myModal_espera').modal('hide');
				}, 1000)
				if (data == 2) {
					Swal.fire('Ya no puede ingresar mas productos', '', 'info');
				} else if (data == 1) {
					//DGAsientoF();
					Calculos_Totales_Factura();
				} else {
					Swal.fire('Intente mas tarde', '', 'info');
				}
			}
		});
		/*fila.childNodes[3].innerHTML = `
			<input type="text" class="form-control form-control-sm text-center" style="max-width:136px;" placeholder="Cambie la cantidad">
		`; //name = cod_prod + usuario_q_agg
		fila.childNodes[9].innerHTML = `
			<div class="input-group input-group-sm" style="min-width: 176px;">
				<input type="text" class="form-control form-control-sm" placeholder="Coloque un comentario">
				<button class="btn btn-sm btn-success" style="font-size:8pt;" onclick="aceptarModificarLF(this)"><i class="fa fa-check" aria-hidden="true" style="font-size:8pt;"></i></button>
				<button class="btn btn-sm btn-danger" style="font-size:8pt;" onclick="cancelarModificarLF(this, ${valAnt})"><i class="fa fa-times" aria-hidden="true" style="font-size:8pt;"></i></button>
			</div>
		`;*/
	}

	var elementoGlob;
	function aceptarModificarLF(campo){
		let fila = campo.parentElement.parentElement.parentElement;
		let nuevoValor = fila.childNodes[3].children[0].value; //corregir aqui
		//let comentario = fila.childNodes[9].childNodes[1].childNodes[1].value;
		let costoTotal = parseInt(nuevoValor) * parseFloat(fila.childNodes[4].innerText);
		console.log(nuevoValor);
		fila.childNodes[3].innerText = nuevoValor;
		fila.childNodes[5].innerText = costoTotal.toFixed(2);
		fila.childNodes[9].innerHTML = `
			<button style="width:50px" class="btn btn-sm btn-primary" onclick="modificarLineaFac(this)"><i class="bx bxs-pencil" aria-hidden="true"></i></button>
		`;

		let filas = $('.asignTablaDistri');
		let totalCant = 0;
		let ADTotal = 0;
		for(let f of filas){
			console.log(f);
			totalCant += parseInt(f.children[3].children[0].value);
			console.log(f.children[3].children[0].value);
			ADTotal += parseFloat(f.children[5].children[0].value);
		}
		console.log(totalCant);
		$('#ADCantTotal').html(`<b>${totalCant}</b>`);
		$('#ADTotal').html(`<b>${ADTotal.toFixed(2)}</b>`);

		let tc = datosFact;
		//console.log(comentario);
		let parametros =
		{
			//'opc': $('input[name="radio_conve"]:checked').val(),
			'comentario': comentario,
			'TextVUnit': fila.childNodes[4].textContent,
			'TextCant': fila.childNodes[3].textContent,
			'TC': tc,
			'TxtDocumentos': '.',
			'Codigo': fila.childNodes[7].textContent,
			'fecha': $('#MBFecha').val(),
			'CodBod': fila.childNodes[6].textContent,
			'VTotal': fila.childNodes[5].textContent,
			/*'TxtRifaD': $('#TxtRifaD').val(),
			'TxtRifaH': $('#TxtRifaH').val(),*/
			'Serie': $('#LblSerie').text(),
			'CodigoCliente': $('#codigoCliente').val(),
			'TextServicios': '.',
			'TextVDescto': 0,
			'PorcIva': document.getElementById('DCPorcenIVA').selectedOptions[0].text,
			'cheking': fila.childNodes[8].children[0].checked==true?1:0,
		}
		console.log(parametros);
		$('#myModal_espera').modal('show');
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucionC.php?ActualizarAsientoF=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				setTimeout(()=>{
					$('#myModal_espera').modal('hide');
				}, 1000)
				if (data == 2) {
					Swal.fire('Ya no puede ingresar mas productos', '', 'info');
				} else if (data == 1) {
					//DGAsientoF();
					Calculos_Totales_Factura();
				} else {
					Swal.fire('Intente mas tarde', '', 'info');
				}
			}
		});
	}

	function cancelarModificarLF(campo, valor){
		let fila = campo.parentElement.parentElement.parentElement;
		console.log(fila);
		fila.childNodes[3].innerText = valor;
		fila.childNodes[9].innerHTML = `
			<button style="width:50px" class="btn btn-sm btn-primary" onclick="modificarLineaFac(this)"><i class="bx bxs-pencil" aria-hidden="true"></i></button>
		`;
	}

	function ingresarAsientoF() {
		let filas = $('.asignTablaDistri');
		let subidos = 0;
		let cantFilas = filas.length;

		for(let fila of filas){
			fila = fila.children;
			// console.log(fila);
			//console.log(fila[0].textContent + " => " + fila[1].textContent + " => " + fila[2].textContent + " => " + fila[3].textContent + " => " + fila[4].textContent + " => " + fila[5].textContent + " => " + fila[6].textContent + " => " + fila[7].textContent);
			let tc = datosFact;
			let parametros =
			{
				//'opc': $('input[name="radio_conve"]:checked').val(),
				'TextVUnit': fila[4].children[0].children[0].value,
				'TextCant': parseFloat(fila[3].children[0].children[0].value),
				'TC': tc,
				'TxtDocumentos': '.',
				'Codigo': fila[7].textContent,
				'fecha': $('#MBFecha').val(),
				'CodBod': fila[6].textContent,
				'CodBod2': fila[0].textContent,
				'VTotal': fila[5].textContent,
				/*'TxtRifaD': $('#TxtRifaD').val(),
				'TxtRifaH': $('#TxtRifaH').val(),*/
				'Serie': $('#LblSerie').text(),
				'CodigoCliente': $('#codigoCliente').val(),
				'TextServicios': '.',
				'TextVDescto': 0,
				'PorcIva': document.getElementById('DCPorcenIVA').selectedOptions[0].text,
				'cheking': fila[8].children[0].checked==true?1:0,
				'tipoTC':fila[11].innerText,
			}
			$.ajax({
				type: "POST",
				url: '../controlador/facturacion/facturas_distribucionC.php?IngresarAsientoF=true',
				data: { parametros: parametros },
				dataType: 'json',
				success: function (data) {
					subidos += 1;

					if(subidos >= cantFilas){
						$('#tbl_DGAsientoF .form-control, #tbl_DGAsientoF .form-check-input').attr('disabled', false);
						$('#txtRecalcular').attr('disabled', false);
					}

					if (data == 2) {
						Swal.fire('Ya no puede ingresar mas productos', '', 'info');
					} else if (data == 1) {
						//DGAsientoF();
						Calculos_Totales_Factura();
					} else {
						Swal.fire('Intente mas tarde', '', 'info');
					}
				}
			});
		}
		/*var cli = $('#DCCliente').val();
		if (cli == '') {
			Swal.fire('Seleccione un cliente', '', 'info');
			return false;
		}*/
		/*var tc = $('#DCLinea').val();
		tc = tc.split(' ');*/
		/*var tc = valTC;
		var parametros =
		{
			'opc': $('input[name="radio_conve"]:checked').val(),
			'TextVUnit': $('#TextVUnit').val(),
			'TextCant': $('#TextCant').val(),
			'TC': tc,
			'TxtDocumentos': $('#TxtDocumentos').val(),
			'Codigo': $('#DCArticulo').val(),
			'fecha': $('#MBFecha').val(),
			'CodBod': $('#DCBodega').val(),
			'VTotal': $('#LabelVTotal').val(),
			'TxtRifaD': $('#TxtRifaD').val(),
			'TxtRifaH': $('#TxtRifaH').val(),
			'Serie': $('#LblSerie').text(),
			'CodigoCliente': $('#codigoCliente').val(),
			'TextServicios': '.',
			'TextVDescto': 0,
			'PorcIva': $('#DCPorcenIVA').val()
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucionC.php?IngresarAsientoF=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				if (data == 2) {
					Swal.fire('Ya no puede ingresar mas productos', '', 'info');
				} else if (data == 1) {
					DGAsientoF();
					Calculos_Totales_Factura();
				} else {
					Swal.fire('Intente mas tarde', '', 'info');
				}
			}
		});*/

	}

	function editarRegistroProducto(elem){
		let registro = elem.parentElement.parentElement;
		console.log(registro);
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
	function tipo_documento() {
		/*var tc = $('#DCLinea').val();
		tc = tc.split(' ');*/

		var TipoFactura = datosFact;

		//var TipoFactura = tc[0];

		var Porc_IVA = document.getElementById('DCPorcenIVA').selectedOptions[0].text;
		Porc_IVA = parseFloat(Porc_IVA) * 100;
		if (TipoFactura == "PV") {
			// FacturasPV.Caption = "INGRESAR TICKET"
			$('#Label1').text(" TICKET No.");
			//$('#Label3').text(" I.V.A. " + Porc_IVA.toFixed(2) + "%")
			$('#title').text('Ticket');
		} else if (TipoFactura == "CP") {
			// FacturasPV.Caption = "INGRESAR CHEQUES PROTESTADOS"
			$('#Label1').text(" COMPROBANTE No.");
			$('#Label3').text(" I.V.A. 0.00%")
			$('#title').text('TICKET');
		} else if (TipoFactura == "NV") {
			// FacturasPV.Caption = "INGRESAR NOTA DE VENTA"
			$('#Label1').text(" NOTA DE VENTA No.");
			$('#Label3').text(" I.V.A. 0.00%");
			$('#title').text('Nota de Venta');
		} else if (TipoFactura == "NDO" || TipoFactura == "NDU") {
			// FacturasPV.Caption = "INGRESAR NOTA DE DONACION"
			$('#Label1').text(" NOTA DE DONACION ORGANIZACION No.");
			$('#Label3').text(" I.V.A. 0.00%");
			$('#title').text('Donaciones');
		} else if (TipoFactura == "LC") {
			// FacturasPV.Caption = "INGRESAR LIQUIDACION DE COMPRAS"
			$('#Label1').text(" LIQUIDACION DE COMPRAS No.");
			$('#Label3').text(" I.V.A. 0.00%")
			$('#title').text('Liquidación de Compras');
			OpcDiv.value = True
			// 'If Len(Opc_Grupo_Div) > 1 Then Grupo_Inv = Opc_Grupo_Div
		} else {
			// FacturasPV.Caption = "INGRESAR FACTURA"
			$('#Label1').text(" FACTURA No.");
			//$('#Label3').text(" I.V.A. " + Porc_IVA.toFixed(2) + "%")
			$('#CodDoc').val("01");
			$('#title').text('Facturas');
		}
	}

	function cambiar_iva(elemento)
{
	let valor = elemento.selectedOptions[0].text;
    $('#Label3').text('I.V.A. '+parseFloat(valor).toFixed(2)+'%');
}

function tipo_facturacion(valor)
{
    $('#Label3').text('I.V.A. '+parseFloat(valor).toFixed(2)+'%');
}

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
						v_donacion: datosFact,
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

	function DCDireccion(dirAux) {
		var parametros = {
			'DireccionAux': dirAux.toUpperCase(),
			'CodigoCliente': $('#codigoCliente').val(),
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucionC.php?DCDireccion=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				if (data.length <= 0) {
					Swal.fire({
						icon: 'info',
						title: "Formulario de Grabación",
						text: `Esta dirección no está registrada: ${parametros['DireccionAux']}, desea registrarla?`,
						showCancelButton: true,
						confirmButtonText: 'Sí!',
						cancelButtonText: 'No!',
						allowOutsideClick: false,
					}).then((result) => {
						if (result.value) {
							ingresarDir(parametros);
						} else {
							$('#TxtNota').focus();
						}
					});

				}
			}
		});
	}

	function ingresarDir(parametros) {
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucionC.php?ingresarDir=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				if (data == 1) {
					Swal.fire({
						icon: 'success',
						title: 'Dirección ingresada correctamente',
						text: '',
						allowOutsideClick: false,
					});
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Error al ingresar la dirección',
						text: '',
						allowOutsideClick: false,
					});
				}
			}
		});
	}

	function Command2_Click() {
		$('#myModal_boletos').modal('hide');
		$('#LabelSubTotal').focus();
	}

	function TarifaLostFocus() {
		var value = $('#LabelSubTotal').val();
		$('#TxtEfectivo').val(value);
	}




	function autocomplete_producto() {
		var parametros = '&TC=' + valTC;
		$('#DCArticulo').select2({
			placeholder: 'Seleccione un producto',
			ajax: {
				url: '../controlador/facturacion/facturas_distribucionC.php?DCArticulo=true' + parametros,
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


	function serie() {
		var TC = valTC;
		var parametros =
		{
			'TC': TC,
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

	function DCBodega() {
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucionC.php?DCBodega=true',
			//data: {parametros: parametros},
			dataType: 'json',
			success: function (data) {
				llenarComboList(data, 'DCBodega');
			}
		});

	}
	function DGAsientoF() {
		//TODO: REVISAR CONTROLADOR PORQUE DA PROBLEMAS EN OTRAS TABLAS
		/*$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucionC.php?DGAsientoF=true',
			//data: {parametros: parametros},
			dataType: 'json',
			beforeSend: function () { $('#tbl_DGAsientoF').html('<img src="../../img/gif/loader4.1.gif" width="40%"> '); },
			success: function (data) {
				$('#tbl_DGAsientoF').html(data);
			}
		});*/

	}

	function Articulo_Seleccionado() {
		var parametros = {
			'codigo': $('#DCArticulo').val(),
			'fecha': $('#MBFecha').val(),
			'CodBod': $('#DCBodega').val(),
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucionC.php?ArtSelec=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				// console.log(data);
				if (data.respueta == true) {
					if (data.datos.Stock <= 0) {
						Swal.fire(data.datos.Producto + ' ES UN PRODUCTO SIN EXISTENCIA', '', 'info').then(function () {
							$('#DCArticulo').empty();
							// $('#LabelStock').val(0);
						});

					} else {

						$('#LabelStock').val(data.datos.Stock);
						$('#TextVUnit').val(data.datos.PVP);
						$('#LabelStock').focus();
						// $('#').val(data.datos.);
					}

				}
			}
		});

	}

	function calcular() {
		var VUnit = $('#TextVUnit').val();
		if (VUnit == '' || VUnit == 0) {
			Swal.fire('INGRESE UN PRECIO VALIDO', 'PUNTO VENTA', 'info').then(function () { $('#TextVUnit').select() })
		}
		var Cant = $('#TextCant').val();
		var OpcMult = $('#OpcMult').prop('checked');
		var ban = $('#TextCheque').val()
		if (is_numeric(VUnit) && is_numeric(Cant)) {
			if (VUnit == 0) { VUnit = "0.01"; }
			if (OpcMult) { Real1 = parseFloat(Cant) * parseFloat(VUnit); } else { Real1 = parseFloat(Cant) / parseFloat(VUnit); }
			// console.log(Real1);
			$('#LabelVTotal').val(Real1.toFixed(4));
		} else {
			$('#LabelVTotal').val(0.0000);
		}
	}

	function valida_Stock() {
		var Cantidad = $('#TextCant').val();
		if (Cantidad == '' || Cantidad == 0) { Swal.fire('INGRESE UNA CANTIDAD VALIDA', 'PUNTO DE VENTA', 'info').then(function () { $('#TextCant').select(); }); }
		var DifStock = parseFloat($('#LabelStock').val()) - parseFloat(Cantidad);
		var producto = $('#DCArticulo option:selected').text();
		if (DifStock.toFixed(2) < 0) {
			Swal.fire(producto + ' NO PUEDE QUEDAR EXISTENCIA NEGATIVA, SOLICITE ALIMENTACION DE STOCK', 'PUNTO DE VENTA', 'info').then(function () {
				$('#TextCant').select();
			});
			// $('#DCArticulo').focus();
		}
	}

	function checkModal() {
		/*var tc = $('#DCLinea').val();
		tc = tc.split(' ');*/
		var TipoFactura = valTC;
		if (TipoFactura === 'PV') {
			$('#myModal_boletos').modal('show');
			$('#myModal_boletos').on('hidden.bs.modal', function () {
				ingresar();
			});
		} else {
			ingresar();
		}
	}

	

	function Eliminar(A_no, cod) {
		Swal.fire({
			title: 'Esta seguro?',
			text: "Esta usted seguro de eliminar este registro!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Si!'
		}).then((result) => {
			if (result.value == true) {
				eliminar_linea(cod, A_no);
			}
		})
	}
	function eliminar_linea(cod, A_no) {
		var parametros =
		{
			'cod': cod,
			'A_no': A_no,
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucionC.php?eliminar_linea=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				if (data == 1) {
					DGAsientoF();
				}
			}
		});

	}

	function Calculos_Totales_Factura() {
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucionC.php?Calculos_Totales_Factura=true',
			// data: {parametros: parametros},
			dataType: 'json',
			success: function (data) {
				// console.log(data)
				$('#LabelSubTotal').val(parseFloat(data.SubTotal).toFixed(2));
				$('#LabelConIVA').val(parseFloat(data.Con_IVA).toFixed(2));
				$('#LabelIVA').val(parseFloat(data.Total_IVA).toFixed(2));
				$('#LabelTotal').val(parseFloat(data.Total_MN).toFixed(2));
				$('#LabelTotal2').val(parseFloat(data.Total_MN).toFixed(2));

			}
		});
	}

	function ingresar_total() {
		Swal.fire({
			allowOutsideClick: false,
			title: 'INGRESE EL TOTAL DEL RECIBO',
			input: 'text',
			inputValue: 0,
			inputAttributes: {
				autocapitalize: 'off',
			},
			showCancelButton: true,
			confirmButtonText: 'Aceptar',
			showLoaderOnConfirm: true,
		}).then((result) => {
			if (result.value >= 0) {
				var total = result.value;
				editar_factura(total);
			} else {

			}
		})
	}

	function editar_factura(total) {
		var parametros =
		{
			'total': total,
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucionC.php?editar_factura=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				DGAsientoF();
				Calculos_Totales_Factura();
			}
		});
	}

	function generar() {
		/*var bod = $('#DCBodega').val();
		if (bod == '') {

			Swal.fire('Ingrese o Seleccione una bodega', '', 'info').then(function () { $('#TextFacturaNo').focus() });
			return false;
		}*/
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

		total_abono = parseFloat(efectivo+banco);
		
		if(total_abono!=total)
		{

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
			'TC': datosFact,
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
					if($('#archivoAdd')[0].files.length > 0){
						guardar_bouche();
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

	function crearAsientoFFA(parametros){
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucionC.php?IngresarAsientoF=true',
			data: { parametros: parametros},
			dataType: 'json',
			success: function (data) {
				if (data == 2) {
					Swal.fire('Ya no puede ingresar mas productos', '', 'info');
				} else if (data == 1) {
					var parametros =
					{
						'MBFecha': $('#MBFecha').val(),
						'TxtEfectivo': $('#btnToggleInfoEfectivo').attr('stateval')=="1" ? $('#TxtEfectivo').val() : 0.00,
						'TextFacturaNo': $('#TextFacturaNo').val(),
						//'TxtNota': $('#TxtNota').val(),
						//'TxtObservacion': $('#TxtObservacion').val(),
						'TipoFactura': tc,
						//'TxtGavetas': $('#TxtGavetas').val(),
						'CodigoCliente': $('#codigoCliente').val(),
						'email': $('#Lblemail').val(),
						'CI': $('#LblRUC').val(),
						'NombreCliente': $('#DCCliente option:selected').text(),
						'TC': tc,
						'Serie': $('#LblSerie').text(),
						'DCBancoN': $('#btnToggleInfoBanco').attr('stateval')=="1" ? $('#DCBanco option:selected').text() : "",
						'DCBancoC': $('#btnToggleInfoBanco').attr('stateval')=="1" ? $('#DCBanco').val() : "",
						'T': $('#LblT').val(),
						'TextBanco': $('#btnToggleInfoBanco').attr('stateval')=="1" ? $('#TextBanco').val() : "",
						'TextCheqNo': $('#btnToggleInfoBanco').attr('stateval')=="1" ? $('#TextCheqNo').val() : "",
						'CodDoc': $('#CodDoc').val(),
						'valorBan':  $('#btnToggleInfoBanco').attr('stateval')=="1" ? $('#TextCheque').val() : 0.00,
						'Cta_Cobrar': $('#Cta_CxP').val(),
						'Autorizacion': $('#Autorizacion').val(),
						'CodigoL': $('#CodigoL').val(),
						'PorcIva': document.getElementById('DCPorcenIVA').selectedOptions[0].text,
						//'cheking': $('#DCPorcenIVA').val(),
						//'PorcIva': $('#DCPorcenIVA').val()
					}
					//generar_factura();
				} else {
					Swal.fire('Intente mas tarde', '', 'info');
				}
			}
		});
	}

	function generar_factura() {
		//$('#myModal_espera').modal('show');

		
		
		/*var tc = $('#DCLinea').val();
		tc = tc.split(' ');*/

		var tc = datosFact;
		var parametros =
		{
			'MBFecha': $('#MBFecha').val(),
			'TxtEfectivo': $('#btnToggleInfoEfectivo').attr('stateval')=="1" ? $('#TxtEfectivo').val() : 0.00,
			'TextFacturaNo': $('#TextFacturaNo').val(),
			//'TxtNota': $('#TxtNota').val(),
			//'TxtObservacion': $('#TxtObservacion').val(),
			'TipoFactura': tc,
			//'TxtGavetas': $('#TxtGavetas').val(),
			'CodigoCliente': $('#codigoCliente').val(),
			'email': $('#Lblemail').val(),
			'CI': $('#LblRUC').val(),
			'NombreCliente': $('#DCCliente option:selected').text(),
			'TC': tc,
			'Serie': $('#LblSerie').text(),
			'DCBancoN': $('#btnToggleInfoBanco').attr('stateval')=="1" ? $('#DCBanco option:selected').text() : "",
			'DCBancoC': $('#btnToggleInfoBanco').attr('stateval')=="1" ? $('#DCBanco').val() : "",
			'T': $('#LblT').val(),
			'TextBanco': $('#btnToggleInfoBanco').attr('stateval')=="1" ? $('#TextBanco').val() : "",
			'TextCheqNo': $('#btnToggleInfoBanco').attr('stateval')=="1" ? $('#TextCheqNo').val() : "",
			'CodDoc': $('#CodDoc').val(),
			'valorBan':  $('#btnToggleInfoBanco').attr('stateval')=="1" ? $('#TextCheque').val() : 0.00,
			'Cta_Cobrar': $('#Cta_CxP').val(),
			'Autorizacion': $('#Autorizacion').val(),
			'CodigoL': $('#CodigoL').val(),
			'PorcIva': document.getElementById('DCPorcenIVA').selectedOptions[0].text,
			'FATextVUnit': (parseFloat($('#LabelTotal').val())/1).toFixed(2),
			'FAVTotal': $('#LabelTotal').val(),
			'FACodLinea': $('#DCLineas').val(),
			'CodigoU': $('.asignTablaDistri')[0].children[10].textContent,
			// 'FechaIng': $('#').val(),
			//'PorcIva': $('#DCPorcenIVA').val()
		}

		if(docbouche != undefined && docbouche.trim() != ''){
			parametros['Comprobante'] = docbouche;
		}

		console.log(parametros);

		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucionC.php?generar_factura=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				if(data.respuesta=='-999'){
					Swal.fire("Asigne una serie a la empresa","Serie_FA no esta agregada","error");
					return false;
				}

				console.log(data);
				setTimeout(()=>{
					$('#myModal_espera').modal('hide');
				}, 1000)
				
				// console.log(data);
				if(data.length == 1){
					if (data.respuesta[0] == 1) {
						Swal.fire({
							icon: 'success',
							title: 'Factura Creada',
							confirmButtonText: 'Ok!',
							allowOutsideClick: false,
						}).then(function () {
							var url = '../../TEMP/' + data.pdf + '.pdf';
							window.open(url, '_blank');
							AdoLinea();
							eliminar_linea('', '');
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
					}
					else if (data.respuesta == 4) {
						Swal.fire('SRI intermitente intente mas tarde', '', 'info');
					} else {
						Swal.fire(data.text, '', 'error');
					}
				}else{
					if (data[1].respuesta == 1) {
						Swal.fire({
							icon: 'success',
							title: 'Nota de Venta y Factura Creadas',
							confirmButtonText: 'Ok!',
							allowOutsideClick: false,
						}).then(function () {

							// imprimir baucher	
							var  id = data[0].factura
							var serie =   $('#LblSerie').text(); //data[0].serie
							var ci =  data[0].codigoc
							var aut =  data[0].auto
							var tc =  'NDO';
							Ver_nd(id,serie,ci,aut,tc);
			
							$('#imprimir_nd').removeAttr('disabled');
							var url = '../../TEMP/' + data[0].pdf + '.pdf';							
							window.open(url, '_blank');

							

							AdoLinea();
							eliminar_linea('', '');
							//crearAsientoFFA(parametros);
						})
					} else if (data[1].respuesta == -1) {
						if(data[1].text=='' || data[1].text == null)
						{
							Swal.fire({
								icon: 'error',
								title: 'XML devuelto',
								text:'Error al generar XML o al firmar',
								confirmButtonText: 'Ok!',
								allowOutsideClick: false,
							}).then(function () {
								location.reload();
							})
						
							tipo_error_sri(data[1].clave);
						}else{
						Swal.fire({
								icon: 'error',
								title: data[1].text,
								confirmButtonText: 'Ok!',
								allowOutsideClick: false,
							}).then(function () {
								location.reload();
							})
						}
					} else if (data[1].respuesta == 2) {
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
					
						tipo_error_sri(data[1].clave);
					}
					else if (data[1].respuesta == 4) {
						Swal.fire('SRI intermitente intente mas tarde', '', 'info');
					} else {
						Swal.fire(data[1].text, '', 'error');
					}
				}
				

			},
			error: (err) => {
				setTimeout(()=>{
					$('#myModal_espera').modal('hide');
				}, 1000)
				Swal.fire('Error', 'Hubo un problema al guardar la factura.','info');
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
				var ca = efectivo - Total_Factura2 + Total_banco;
				$('#LblCambio').val(ca.toFixed(2));
				$('#btn_g').focus();
			}
		}

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

	function catalogoLineas() {
		$('#myModal_espera').modal('show');
		var cursos = $("#DCLinea");
		fechaEmision = $('#MBFecha').val();
		fechaVencimiento = $('#MBFecha').val();
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturar_pensionC.php?catalogo=true',
			data: { 'fechaVencimiento': fechaVencimiento, 'fechaEmision': fechaEmision },
			dataType: 'json',
			success: function (data) {
				if (data) {
					datos = data;
					// Limpiamos el select
					cursos.find('option').remove();
					for (var indice in datos) {
						cursos.append('<option value="' + datos[indice].id + " " + datos[indice].text + ' ">' + datos[indice].text + '</option>');
					}
				} else {
					console.log("No tiene datos");
				}

				tipo_documento();
				//numeroFactura();
			}
		});
		setTimeout(()=>{
			$('#myModal_espera').modal('hide');
		}, 1000)
	}
	function validar_bodega() {
		var ddl = $('DCBodega').val();
		if (ddl == '') {
			Swal.fire('Ingrese o Seleccione una bodega', '', 'info').then(function () { $('#TextFacturaNo').focus() });
		}
	}


	function ListaFacturas()
	{
		$('#myModal_listaFacturas').modal('show');
	}

	function cuadrarNdo(factura,serie)
	{
		var parametros = 
		{
			'factura':factura,
			'serie':serie,
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturar_pensionC.php?cuadrarNdo=true',
			data: {parametros,parametros},
			dataType: 'json',
			success: function (data) {
				
				console.log(data);
			}
		});

	}