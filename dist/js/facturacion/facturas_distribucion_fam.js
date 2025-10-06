	var ListaIntegrantesXProducto = {};
	var ListaAbonos = {};
	$(document).ready(function () {
		 autocomplete_pedidos();
		 DCLineasNDU(); 
		 // serie();
		 DCLineas(); 
		 DCPorcenIvaFD()
		 DCBanco()
		 DCEfectivo();


		 $('#ddl_pedidos').on('select2:select', function (e) {
        var datos = e.params.data.data;//Datos beneficiario seleccionado
        console.log(datos);
        $('#MBFechaChek').val(formatoDate(datos.Fecha.date));//Fecha de Atencion
        datos.Fecha = formatoDate(datos.Fecha.date);
        $('#ddl_programas').html('<option value="'+datos.CodigoL+'" >'+datos.Programa+'</option>');
        $('#ddl_grupos').html('<option value="'+datos.CodigoB+'" >'+datos.Grupo+'</option>');
        cargar_asignacion();

    	});
	})


	function autocomplete_pedidos() {
		$('#ddl_pedidos').select2({
			// width: '100%',
			placeholder: 'Seleccione programa',
			ajax: {
				url: '../controlador/facturacion/facturas_distribucion_famC.php?ddl_pedidos_familia=true',
				dataType: 'json',
				delay: 250,
				/*data: function (params) {
                    return {
                        query: params.term,
						v_donacion: datosFact,
						fecha: $("#MBFecha").val()
                    }
                },*/
				processResults: function (data) {
					return {
						results: data
					};
				},
				cache: true
			}
		});
	}

	function  DCLineasNDU() {
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
					$('#TextNDUNo').val(data.NumCom);
				} else {
					numeroFactura();
				}
				validar_cta();
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


	function cargar_asignacion()
	{
		if ($.fn.DataTable.isDataTable('#tbl_lineas_factura')) {
	      $('#tbl_lineas_factura').DataTable().destroy();
	  	}
	 var   tbl_asignados_all = $('#tbl_lineas_factura').DataTable({
	    	scrollX: true,
          	searching: false,
          	responsive: false,
          	// paging: false,   
          	info: false,   
          	autoWidth: false,   
	        language: {
	          url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
	        },
	        ajax: {
	          url: '../controlador/facturacion/facturas_distribucion_famC.php?cargar_asignacion=true',
	          type: 'POST',  // Cambia el método a POST   
	          data: function(d) {
	              var parametros = {                    
	               orden:$('#ddl_pedidos').val(),         
	               fechaPick:$('#MBFechaChek').val(),     
	               grupo:$('#ddl_grupos').val(),
	              };
	              return { parametros: parametros };
	          },   
	           // dataSrc: function(json) {
	           // 	console.log(json);
	           // },
	          dataSrc: '',             
	        },
         	scrollX: true,  // Habilitar desplazamiento horizontal
     
        columns: [
         	{data:'CodBodega'},
	        {data: 'Producto'},
	        {data: 'cantidad'},
	        {data: 'NumIntegrante'},
	        {data:'PVP',
	        	render: function(data, type, row) {
			    	return parseFloat(data).toFixed(2);
			  	}
			},
	        {data:'total',
	       	 render: function(data, type, row) {
			    return parseFloat(data).toFixed(2);
			  }
			},
	       
          
        ],
      });


	 tbl_asignados_all.on('draw', function() {
		let sumaTotal = 0;
		tbl_asignados_all.rows().data().each(function(row) {
			// console.log(row)
		    sumaTotal += parseFloat(row.total) || 0;
		});

		$("#LabelTotal").val(sumaTotal.toFixed(2));
		$("#LabelTotal2").val(sumaTotal.toFixed(2));

	})


	    
	}

	function serie() {
		var parametros =
		{
			'Fecha': $('#MBFecha').val(),
			'TC': 'FA'
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucionC.php?LblSerie=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {

				console.log(data);

				$('#LblSerieFA').text(data.serie)
				$('#TextFacturaNo').val(data.NumCom);
				$('#TextNDUNo').val(data.NumCom)

				// llenarComboList(data, 'DCLineas');
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

	// function DCPorcenIvaFD(){
	// 	let fecha = $("#MBFecha").val();
	// 	$('#DCPorcenIVA').select2({
	// 		placeholder: 'Seleccione IVA',
	// 		dropdownParent: $('#modalInfoFactura'),
	// 		ajax: {
	// 			url: '../controlador/facturacion/facturas_distribucionC.php?LlenarSelectIVA=true',
	// 			dataType: 'json',
	// 			delay: 250,
	// 			data: function (params) {
    //                 return {
    //                     query: params.term,
    //                     fecha: fecha
    //                 }
    //             },
	// 			processResults: function (data) {
	// 				return {
	// 					results: data
	// 				};
	// 			},
	// 			cache: true
	// 		}
	// 	});
	// }

	async function validarProductos(codigoC) {
		$('#cbx_all_producto').prop('checked',false);
		if($('#rbl_facturar_'+codigoC).prop('checked'))
		{
			$('#txt_integrate_produ').val(codigoC);
			await cargarOrden();
			if(Object.keys(ListaIntegrantesXProducto).length==0)
			{				
				$('#modal_grupoAlimentos').modal('show');
			}else
			{
				if(ListaIntegrantesXProducto[codigoC]!=undefined)
				{
					$('#txt_integrate_produ').val(codigoC);					
					ListaIntegrantesXProducto[codigoC].forEach(function(item,i){
						console.log(item);
						$('#txt_cantidad_entregada_'+item.Codigo.replaceAll('.','_')).val(item.cantidad);
						$('#txt_pvp_entregada_'+item.Codigo.replaceAll('.','_')).val(item.pvp);
						$('#txt_total_entregada_'+item.Codigo.replaceAll('.','_')).val(item.total);
						$('#rbl_prod_entregada_'+item.Codigo.replaceAll('.','_')).prop('checked',true);
					})
				}
				$('#modal_grupoAlimentos').modal('show');
			}

		}else
		{
			Swal.fire("Habilite al integrante","","info")
		}
	}

	function generar() 
	{
		IntegrantesGrupo();
		// cargarOrden();
		$('#modal_grupoIntegrantes').modal('show');
	}

	function cargarOrden()
	{
		var parametros =
		{
			'programa': $('#ddl_programas').val(),
			'grupo': $('#ddl_grupos').val(),
			'pedido':$('#ddl_pedidos').val(),
		}
	return	$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucion_famC.php?cargarOrden=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				console.log(data);
				tr = '';
				var total = 0;
				data.forEach(function(item,i){
					tr+= `<tr>
							<td>
								<input class="class_productos" type="checkbox" value="`+item.Codigo+`" id="rbl_prod_entregada_`+item.Codigo.replaceAll('.','_')+`">
							</td>
							<td>`+item.Producto+`</td>
							<td>`+item.Cantidad+`</td>
							<td>
								<input type="text" onblur="calcular_totales_x_integrante('`+item.Codigo.replaceAll('.','_')+`')" id="txt_cantidad_entregada_`+item.Codigo.replaceAll('.','_')+`" class="form-control form-control-sm" value="`+item.Cantidad+`">
							</td>
							<td>
								<input type="text" onblur="calcular_totales_x_integrante('`+item.Codigo.replaceAll('.','_')+`')" id="txt_pvp_entregada_`+item.Codigo.replaceAll('.','_')+`" class="form-control form-control-sm" value="`+item.Precio+`">
							</td>
							<td>
								<input type="text" onblur="recalcular_pvp('`+item.Codigo.replaceAll('.','_')+`')" id="txt_total_entregada_`+item.Codigo.replaceAll('.','_')+`" class="form-control form-control-sm" value="`+(item.Precio*item.Cantidad)+`">
							</td>
						</tr>`;
						total+=parseFloat(item.Cant_Hab);
				})
				console.log(data)
				// console.log(data.detalle);
				$('#tbl_grupoAlimento').html(tr);
				$('#txt_total_fam').val(total.toFixed(2));
			}
		});
	}

	function recalcular_pvp(codigo)
	{
		var total = $('#txt_total_entregada_'+codigo).val();
		var cant = $('#txt_cantidad_entregada_'+codigo).val();
		var pvp = parseFloat(total)/parseFloat(cant);
		$('#txt_pvp_entregada_'+codigo).val(pvp.toFixed(4));

	}

	function calcular_totales_x_integrante(codigoC)
	{
		var cant = $('#txt_cantidad_entregada_'+codigoC).val();
		var pvp = $('#txt_pvp_entregada_'+codigoC).val();

		var total = parseFloat(cant)*parseFloat(pvp);
		$('#txt_total_entregada_'+codigoC).val(total.toFixed(2));

	}

	function IntegrantesGrupo()
	{
		var parametros =
		{
			'programa': $('#ddl_programas').val(),
			'grupo': $('#ddl_grupos').val(),
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucion_famC.php?IntegrantesGrupo=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				tr = '';
				data.forEach(function(item,i){
					tr+=`<tr>
						<td class="text-center"><input class="class_integrante" type='checkbox' onclick="SelectionarIntegerante()" value="`+item.Codigo+`" id="rbl_facturar_`+item.Codigo+`" name="rbl_facturar_`+item.Codigo+`" checked="" ></td>
						<td>`+(i+1)+`</td>
						<td>`+item.Cliente+`</td>
						<td>`+item.CI_RUC+`</td>
						<td>
							<input type="" id="txt_cant_cp_`+item.Codigo+`" name ="" class="form-control form-control-sm" value="0" readonly>
						</td>
						<td>
							<input type="" id="txt_total_cp_`+item.Codigo+`" name ="" class="form-control form-control-sm" value="0" readonly>
						</td>
						<td class="text-center">
							<button type="button" class="btn" onclick="validarProductos('`+item.Codigo+`')">
                            	<img id="img_tipoBene" src="../../img/png/cantidad_global.png" style="width: 32px;">
                            </button>    
						</td>
						<td>
							<div class="input-group">
								<input type="" readonly="" id="txt_abono_cp_`+item.Codigo+`" name ="" class="form-control form-control-sm" value="0" >
								<button class="btn btn-light border border-1 btn-sm" onclick="modal_forma_pago('`+item.Codigo+`')">Forma de pago</button>
							</div>
						</td>
						
					</tr>`;
				})

				tr+=`<tr>
						<td>
							<br>
							<b></b>
						</td>
						<td>
							<b>No Familias</b>
							<label>`+data.length+` / <span id="lbl_cantiInte">`+data.length+`</span></label>
						</td>
						<td>
							<b>Total Asignado</b>
							<input type="" id="txt_total_fam" name ="" class="form-control form-control-sm" value="0">
						</td>
						<td>
							<b>Total Entregado</b>
							<input type="" id="txt_total_fam_ent" name ="" class="form-control form-control-sm" value="0">
						</td>
						<td>
						</td>
					</tr>`
				$('#tbl_integrantes').html(tr);
			}
		});
	}

	function SeleccionarIntegrante()
	{

		var productos = [];
		var codigoC = $('#txt_integrate_produ').val();

		var valor_entre = 0;
		var total_entre = 0;
		$('.class_productos').each(function() {
		    const checkbox = $(this);
		    const isChecked = checkbox.prop('checked'); 
		    if (isChecked) {
		    	codigoInv = checkbox[0].value;
		    	var cant = $('#txt_cantidad_entregada_'+codigoInv.replaceAll('.','_')).val()
		    	var pvp = $('#txt_pvp_entregada_'+codigoInv.replaceAll('.','_')).val()
		    	var total = $('#txt_total_entregada_'+codigoInv.replaceAll('.','_')).val()
		    	productos.push({'Codigo':codigoInv,'cantidad':cant,'pvp':pvp,'total':total})
		    	valor_entre+=parseFloat(cant);
		    	total_entre+=parseFloat(total);
		    }
		});

		ListaIntegrantesXProducto[codigoC] = productos;

		$('#txt_cant_cp_'+codigoC).val(valor_entre.toFixed(2))
		$('#txt_total_cp_'+codigoC).val(total_entre.toFixed(2));
		// $('#txt_abono_cp_'+codigoC).val(total_entre.toFixed(2));
		// console.log(codigoC)
		// console.log(productos);
		// console.log(ListaIntegrantesXProducto)
		calcular_totales()
		$('#modal_grupoAlimentos').modal('hide');

	}

	function SelectionarIntegerante()
	{
		var cant = 0;
		$('.class_integrante').each(function() {
		    const checkbox = $(this);
		    const isChecked = checkbox.prop('checked'); 
		    if (isChecked) {
		    	cant++;
		    }else
		    {
		    	var codigoC = checkbox[0].value;
		    	$('#txt_cant_cp_'+codigoC).val(0)
		    	$('#txt_total_cp_'+codigoC).val(0)
		    	delete ListaIntegrantesXProducto[codigoC]
		    	calcular_totales();
		    	console.log(ListaIntegrantesXProducto)
		    }
		});
		$('#lbl_cantiInte').text(cant);		
	}

	function calcular_totales()
	{		
		console.log(ListaIntegrantesXProducto);
		var total_ent = 0;
		for(let key in ListaIntegrantesXProducto )
		{
			lineas = ListaIntegrantesXProducto[key];
			lineas.forEach(function(item,i){
				total_ent+=parseFloat(item.cantidad);
				console.log(item);
			})
			console.log(ListaIntegrantesXProducto[key]);
		}
		
		$('#txt_total_fam_ent').val(total_ent.toFixed(2));

	}

	function finalizar_factura()
	{
			var parametros =
		{
			'programa': $('#ddl_programas').val(),
			'grupo': $('#ddl_grupos').val(),
			'orden': $('#ddl_pedidos').val(),
			'fechaPick': $('#MBFechaChek').val(),
			'fecha': $('#MBFecha').val(),
		}

		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucion_famC.php?finalizarFactura=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				console.log(data);
				

				if(data==1)
				{
					Swal.fire("Factura Finalizada","","success").then(function(){
						// editar para que no aparesca la factura 
						finalizarFactura()
						// mostrar los tiquets y facturas  
					})
				}

			},
		    error: function(error){
		      console.error("Error revisar: ", error);
		    }
		});
	}

	function Generar_factura()
	{

		if(Object.keys(ListaIntegrantesXProducto).length==0)
		{
			Swal.fire("Seleccione algun Integrante de grupo","","info");
			return false;
		}

		var integrantes = JSON.stringify(ListaIntegrantesXProducto);
		var ListaAbonosInte = JSON.stringify(ListaAbonos);
		// console.log(integrantes)

		// console.log(ListaAbonos)


		var parametros =
		{
			'programa': $('#ddl_programas').val(),
			'grupo': $('#ddl_grupos').val(),
			'orden': $('#ddl_pedidos').val(),
			'fechaPick': $('#MBFechaChek').val(),
			'fecha': $('#MBFecha').val(),
			'integrantes':integrantes,
			'Abonointegrantes':ListaAbonosInte,

			'TxtEfectivo': $('#btnToggleInfoEfectivo').attr('stateval')=="1" ? $('#TxtEfectivo').val() : 0.00,
			'TextFacturaNo': $('#TextFacturaNo').val(),
			'TipoFactura': 'FA',
			'TextNDUNo': $('#TextNDUNo').val(),
			'TipoNDU': $('#DCTipoFact2').val(),
			'TC':$('#DCTipoFact2').val(),
			'Serie': $('#LblSerie').text(),
			'SerieFA': $('#LblSerieFA').text(),
			'DCBancoN': $('#btnToggleInfoBanco').attr('stateval')=="1" ? $('#DCBanco option:selected').text() : "",
			'DCBancoC': $('#btnToggleInfoBanco').attr('stateval')=="1" ? $('#DCBanco').val() : "",
			'TextBanco': $('#btnToggleInfoBanco').attr('stateval')=="1" ? $('#TextBanco').val() : "",
			'TextCheqNo': $('#btnToggleInfoBanco').attr('stateval')=="1" ? $('#TextCheqNo').val() : "",
			'CodDoc': $('#CodDoc').val(),
			'valorBan':  $('#btnToggleInfoBanco').attr('stateval')=="1" ? $('#TextCheque').val() : 0.00,
			'Cta_Cobrar': $('#Cta_CxP').val(),
			'Autorizacion': $('#Autorizacion').val(),
			'PorcIva':$('#DCPorcenIVA').text(),
		}

		$('#myModal_espera').modal("show");
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucion_famC.php?GenerarFactura=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				console.log(data);
				todobien = 1;
				data.forEach(function(item,i){
					var resp = item.respuesta[0];
					if(!resp==1)
					{
						todobien = 0;
					}
				})

				if(todobien==1)
				{
					Swal.fire("Factura Generada","","success").then(function(){
						// editar para que no aparesca la factura 
						// finalizarFactura()
						// mostrar los tiquets y facturas  
						$('#myModal_espera').modal("hide");
					})
				}

			},
		    error: function(error){
		      console.error("Error revisar: ", error);
				$('#myModal_espera').modal("hide");
		    }
		});

	}

	function finalizarFactura()
	{
		var parametros = 
		{
			'programa': $('#ddl_programas').val(),
			'grupo': $('#ddl_grupos').val(),
			'orden': $('#ddl_pedidos').val(),
			'fechaPick': $('#MBFechaChek').val(),
			'fecha': $('#MBFecha').val(),
		}
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucion_famC.php?finalizarFactura=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				if(data==1)
				{
					location.reload();
				}
			}
		});

	}


	function all_Producto()
	{
		var estado = true;
		if(!$('#cbx_all_producto').prop('checked'))
		{
			estado = false;
		}

		$('.class_productos').each(function() {
		    	const checkbox = $(this);
		    	console.log(checkbox);
		    	checkbox.prop('checked',estado);
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

	function guardarAbonos()
	{
		beneficiario = $('#txtBeneficiarioAbono').val();
		ctaEfectivo = $('#DCEfectivo').val();
		valorEfectivo = $('#TxtEfectivo').val();

		ctaBancos = $('#DCBanco').val();
		documento = $('#TextCheqNo').val();
		nombreBanco = $('#TextBanco').val();
		valorBanco = $('#TextCheque').val();

		ListaAbonos[beneficiario] = 
		{
			'ctaEfectivo': ctaEfectivo,
			'valorEfectivo': valorEfectivo,
			'ctaBancos':ctaBancos,
			'documento': documento,
			'nombreBanco':nombreBanco,
			'valorBanco':valorBanco
		};

		total = parseFloat(valorEfectivo)+parseFloat(valorBanco);

		$('#txt_abono_cp_'+beneficiario).val(total.toFixed(2))
		$('#modalInfoFactura').modal('hide')

		limpiarAbonos();
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

	function modal_forma_pago(codigo)
	{
		if($('#txt_total_cp_'+codigo).val()!=0)
		{
			$('#txtBeneficiarioAbono').val(codigo);
			$('#modalInfoFactura').modal('show')
			apagar = $('#txt_total_cp_'+codigo).val();
			$('#LabelTotal').val(apagar);
		}else
		{
			Swal.fire('Seleccione productos','El total de productos no puede ser cero ( 0 )','info');
		}
	}

	function quitar_de_facturar()
	{
		pedido = $('#ddl_pedidos').val();
		if(pedido=='' || pedido==null || pedido==undefined)
		{
			Swal.fire("Seleccione Un pedido","","info");
			return false;
		}else
		{
			var parametros = 
			{
				'orden': $('#ddl_pedidos').val(),
			}
			$.ajax({
				type: "POST",
				url: '../controlador/facturacion/facturas_distribucion_famC.php?quitar_de_facturar=true',
				data: { parametros: parametros },
				dataType: 'json',
				success: function (data) {
					if(data==1)
					{
						location.reload();
					}
				}
			});

		}
	}





	






	// ====================================================================================================================



	

	// // Deja preseleccionadas las opciones de los selects
	// function preseleccionar_opciones(){
	// 	$.ajax({
	// 		type: "GET",
	// 		url: '../controlador/facturacion/facturas_distribucion_famC.php?LlenarSelectIVA=true',
	// 		dataType: 'json',
	// 		success: function(data){
	// 			//Escoge el primer registro
	// 			let opcion = data[0]

	// 			//Crea la opcion y la agrega como predefinida al select
	// 			for(let d of data){
	// 				let newOption = new Option(d['text'], d['id'], true, true);
	// 				$("#DCPorcenIVA").append(newOption);
	// 			}
	// 			let DCPorcenIVA = document.getElementById('DCPorcenIVA');
	// 			DCPorcenIVA.selectedIndex = 0;
	// 			//$("#DCPorcenIVA").trigger('change');
	// 			//cambiar_iva(DCPorcenIVA);
	// 			let valor = DCPorcenIVA.selectedOptions[0].text;
	// 			console.log(valor);
	// 			$('#Label3').text('I.V.A. '+parseFloat(valor).toFixed(2)+'%');
	// 			//tipo_documento();
	// 		}
	// 	});
		
	// }

	//Agrega eventos a selects
	function eventos_select(){
		$("#DCTipoFact2").on('select2:select', (e)=> {
			datosFact = e.params.data['data'][0]['Fact'];
			// console.log(datosFact);
			// $("#Label1").text(`FACTURA (${datosFact}) NO.`);
			if(datosFact == 'NDO'){
				$("#Label1").text(`NOTA DE DONACIÓN ORGANIZACIONES`);
			}else if(datosFact == 'NDU'){
				$("#Label1").text(`NOTA DE DONACIÓN USUARIOS`);
			}
			AdoLinea();
		})
	}



	function DCTipoFact2(){
		//let fecha = $("#MBFecha").val().replaceAll("-","");
		/*let newOption = new Option("Nota de Donacion Organizaciones", "CXCNDO", true, true);
		$("#DCTipoFact2").append(newOption).trigger('change');*/
		$('#DCTipoFact2').select2({
			width: '57%',
			placeholder: 'Seleccione Tipo de Factura',
			ajax: {
				url: '../controlador/facturacion/facturas_distribucion_famC.php?LlenarSelectTipoFactura=true',
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
			url: '../controlador/facturacion/facturas_distribucion_famC.php?AdoLinea=true',
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
				$('#LblSerie').text(data.SerieFactura);
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
			url: '../controlador/facturacion/facturas_distribucion_famC.php?AdoAuxCatalogoProductos=true',
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

		var parametros = {
			'CodigoCliente': dataS[0].Codigo,
		};

		//TODO: Aplicar select2
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucion_famC.php?ClienteDatosExtras=true',
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
				$('#HoraAtencion').val(res['horarioEnt']);
			}
		});

		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucion_famC.php?ClienteSaldoPendiente=true',
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

		//cargarRegistrosProductos();
	}

	function formatearUnidadesProductos(unidad){
		//console.log("prueba unidad");
		//console.log(unidad);
		let unidades = {
			Kilos: "Kg"
		};
		return unidades[unidad];
	}

	// function cargarRegistrosProductos(){
		
	// 	let codigoPrograma = $('#ddl_programas').val();
	// 	let codigoGrupo = $('#ddl_grupos').val();
	// 	let parametros = {
	// 		'programa': codigoPrograma,
	// 		'grupo': codigoGrupo,
	// 		'fecha': $('#MBFecha').val()
	// 	}
	// 	$.ajax({
	// 		type: "POST",
	// 		url: '../controlador/facturacion/facturas_distribucion_famC.php?ConsultarProductos=true',
	// 		data: { parametros },
	// 		dataType: 'json',
	// 		beforeSend: () => {
	// 			$('#myModal_espera').modal('show');
	// 		},
	// 		success: function (datos) {
	// 			setTimeout(()=>{
	// 				$('#myModal_espera').modal('hide');
	// 			}, 1000);

	// 			$('#cuerpoTablaDistri').remove();
	// 			if (datos['res'] == 1) {
	// 				let cTotalProds = 0;
	// 				let tTotalProds = 0;
	// 				let tBody = $('<tbody id="cuerpoTablaDistri"></tbody>');
	// 				for(let fila of datos['contenido']){
	// 					let td;
	// 					let tr = $('<tr class="asignTablaDistri"></tr>');
	// 					tr.append($('<td></td>').text(fila['Detalles']['CodBodega']));
	// 					tr.append($('<td></td>').text(fila['Detalles']['Nombre_Completo']));
	// 					tr.append($('<td></td>').text(fila['Productos']['Producto']));
	// 					let cantEntregas = parseFloat(fila['Detalles']['Cantidad']);
	// 					tr.append($('<td></td>').text(cantEntregas.toFixed(2)));
	// 					tr.append($('<td></td>').text(parseInt(fila['Detalles']['Cantidad'])));
	// 					//tr.append($('<td></td>').text(parseFloat(fila['Productos']['PVP']).toFixed(2)));
	// 					tr.append($('<td></td>').text(parseFloat(fila['Detalles']['Precio']).toFixed(2)));
	// 					tr.append($('<td></td>').text(parseFloat(fila['Detalles']['Total']).toFixed(2)));
	// 					//let totalProducto = fila['Detalles']['Total'] * fila['Productos']['PVP'];
	// 					//tr.append($('<td></td>').text(parseFloat(totalProducto).toFixed(2)));
	// 					tr.append($('<td style="display:none;"></td>').text(fila['Detalles']['CodBodega']));
	// 					tr.append($('<td style="display:none;"></td>').text(fila['Productos']['Codigo_Inv']));
	// 					tr.append($('<td></td>').html('<input type="checkbox" id="producto_cheking" name="producto_cheking" class="form-check-input border-secondary">'));
	// 					tr.append($('<td></td>').html('<button style="width:50px" class="btn btn-sm btn-primary" onclick="modificarLineaFac(this)"><i class="bx bxs-pencil"></i></button>'));
	// 					tr.append($('<td style="display:none;"></td>').text(fila['Detalles']['CodigoU']));
	// 					tBody.append(tr);

	// 					cTotalProds += cantEntregas;
	// 					tTotalProds += parseFloat(fila['Detalles']['Total']);
	// 					console.log(tTotalProds);
	// 				}
	// 				let tr = $('<tr class="bg-primary-subtle"></tr>');
	// 				tr.append($('<td colspan="3"></td>').html('<b>Total</b>'));
	// 				tr.append($('<td id="ADCantTotal"></td>').html(`<b>${cTotalProds}</b>`));
	// 				tr.append($('<td colspan="2"></td>'));
	// 				//tr.append($('<td></td>'));
	// 				tr.append($('<td id="ADTotal"></td>').html(`<b>${tTotalProds.toFixed(2)}</b>`));
	// 				tr.append($('<td colspan="2"></td>'));
	// 				tBody.append(tr);
	// 				$('#tbl_DGAsientoF table').append(tBody);
	// 				$('#kilos_distribuir').val(cTotalProds);
	// 				let unidadProd = formatearUnidadesProductos(datos['contenido'][0]['Productos']['Unidad']);
	// 				$('#tablaProdCU').text(unidadProd);
	// 				$('#unidad_dist').val(unidadProd);
	// 				//buscarValoresGavetas();
	// 				ingresarAsientoF();
	// 			}
	// 			$('#myModal_espera').modal('hide');
	// 		}
	// 	})
	// }

	function modificarLineaFac(campo){
		let fila = campo.parentElement.parentElement;
		let valAnt = parseInt(fila.childNodes[3].innerText);
		console.log(valAnt);
		fila.childNodes[3].innerHTML = `
			<input type="text" class="form-control form-control-sm text-center" style="max-width:136px;" placeholder="Cambie la cantidad">
		`; //name = cod_prod + usuario_q_agg
		fila.childNodes[10].innerHTML = `
			<div class="input-group input-group-sm" style="min-width: 176px;">
				<input type="text" class="form-control form-control-sm" placeholder="Coloque un comentario">
				<button class="btn btn-sm btn-success" style="font-size:8pt;" onclick="aceptarModificarLF(this)"><i class="fa fa-check" aria-hidden="true" style="font-size:8pt;"></i></button>
				<button class="btn btn-sm btn-danger" style="font-size:8pt;" onclick="cancelarModificarLF(this, ${valAnt})"><i class="fa fa-times" aria-hidden="true" style="font-size:8pt;"></i></button>
			</div>
		`;
	}

	var elementoGlob;
	function aceptarModificarLF(campo){
		let fila = campo.parentElement.parentElement.parentElement;
		let nuevoValor = fila.childNodes[3].children[0].value; //corregir aqui
		let comentario = fila.childNodes[10].childNodes[1].childNodes[1].value;
		let costoTotal = parseInt(nuevoValor) * parseFloat(fila.childNodes[4].innerText);
		console.log(nuevoValor);
		fila.childNodes[3].innerText = nuevoValor;
		fila.childNodes[6].innerText = costoTotal.toFixed(2);
		fila.childNodes[10].innerHTML = `
			<button style="width:50px" class="btn btn-sm btn-primary" onclick="modificarLineaFac(this)"><i class="bx bxs-pencil" aria-hidden="true"></i></button>
		`;

		let filas = $('.asignTablaDistri');
		let totalCant = 0;
		let ADTotal = 0;
		for(let f of filas){
			console.log(f);
			totalCant += parseInt(f.children[3].innerText);
			console.log(f.children[3].innerText);
			ADTotal += parseFloat(f.children[6].innerText);
		}
		console.log(totalCant);
		$('#ADCantTotal').html(`<b>${totalCant}</b>`);
		$('#ADTotal').html(`<b>${ADTotal.toFixed(2)}</b>`);

		let tc = datosFact;
		console.log(comentario);
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
			url: '../controlador/facturacion/facturas_distribucion_famC.php?ActualizarAsientoF=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				$('#myModal_espera').modal('hide');
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
		fila.childNodes[10].innerHTML = `
			<button style="width:50px" class="btn btn-sm btn-primary" onclick="modificarLineaFac(this)"><i class="bx bxs-pencil" aria-hidden="true"></i></button>
		`;
	}

	function ingresarAsientoF() {
		let filas = $('.asignTablaDistri');

		for(let fila of filas){
			fila = fila.children;
			//console.log(fila[0].textContent + " => " + fila[1].textContent + " => " + fila[2].textContent + " => " + fila[3].textContent + " => " + fila[4].textContent + " => " + fila[5].textContent + " => " + fila[6].textContent + " => " + fila[7].textContent);
			let tc = datosFact;
			let parametros =
			{
				//'opc': $('input[name="radio_conve"]:checked').val(),
				'TextVUnit': fila[4].textContent,
				'TextCant': fila[3].textContent,
				'TC': tc,
				'TxtDocumentos': '.',
				'Codigo': fila[7].textContent,
				'fecha': $('#MBFecha').val(),
				'CodBod': fila[6].textContent,
				'VTotal': fila[5].textContent,
				/*'TxtRifaD': $('#TxtRifaD').val(),
				'TxtRifaH': $('#TxtRifaH').val(),*/
				'Serie': $('#LblSerie').text(),
				'CodigoCliente': $('#codigoCliente').val(),
				'TextServicios': '.',
				'TextVDescto': 0,
				'PorcIva': document.getElementById('DCPorcenIVA').selectedOptions[0].text,
				'cheking': fila[8].children[0].checked==true?1:0
			}
			$.ajax({
				type: "POST",
				url: '../controlador/facturacion/facturas_distribucion_famC.php?IngresarAsientoF=true',
				data: { parametros: parametros },
				dataType: 'json',
				success: function (data) {
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
			url: '../controlador/facturacion/facturas_distribucion_famC.php?IngresarAsientoF=true',
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
			$('#Label1').text(" NOTA DE DONACION No.");
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

/*function tipo_facturacion(valor)
{
    $('#Label3').text('I.V.A. '+parseFloat(valor).toFixed(2)+'%');
}*/

	function autocomplete_cliente() {
		$('#DCCliente').select2({
			width: '62%',
			placeholder: 'Seleccione un cliente',
			ajax: {
				url: '../controlador/facturacion/facturas_distribucion_famC.php?DCCliente=true',
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
			url: '../controlador/facturacion/facturas_distribucion_famC.php?DCDireccion=true',
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
			url: '../controlador/facturacion/facturas_distribucion_famC.php?ingresarDir=true',
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
				url: '../controlador/facturacion/facturas_distribucion_famC.php?DCArticulo=true' + parametros,
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



	
	function DCBodega() {
		$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturas_distribucion_famC.php?DCBodega=true',
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
			url: '../controlador/facturacion/facturas_distribucion_famC.php?DGAsientoF=true',
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
			url: '../controlador/facturacion/facturas_distribucion_famC.php?ArtSelec=true',
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
			url: '../controlador/facturacion/facturas_distribucion_famC.php?eliminar_linea=true',
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
			url: '../controlador/facturacion/facturas_distribucion_famC.php?Calculos_Totales_Factura=true',
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
			url: '../controlador/facturacion/facturas_distribucion_famC.php?editar_factura=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				DGAsientoF();
				Calculos_Totales_Factura();
			}
		});
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
			url: '../controlador/facturacion/facturas_distribucion_famC.php?GuardarBouche=true',
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
					$('#myModal_espera').modal('hide');
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
			url: '../controlador/facturacion/facturas_distribucion_famC.php?GrabarEvaluaciones=true',
			data: { parametros: parametros },
			dataType: 'json',
			success: function (data) {
				if(data['res'] == 1){
					grabar_gavetas();
				}else{
					$('#myModal_espera').modal('hide');
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
			url: '../controlador/facturacion/facturas_distribucion_famC.php?GrabarGavetas=true',
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
					$('#myModal_espera').modal('hide');
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
			url: '../controlador/facturacion/facturas_distribucion_famC.php?IngresarAsientoF=true',
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

	// function generar_factura() {
	// 	//$('#myModal_espera').modal('show');

		
		
	// 	/*var tc = $('#DCLinea').val();
	// 	tc = tc.split(' ');*/

	// 	var tc = datosFact;
	// 	var parametros =
	// 	{
	// 		'MBFecha': $('#MBFecha').val(),
	// 		'TxtEfectivo': $('#btnToggleInfoEfectivo').attr('stateval')=="1" ? $('#TxtEfectivo').val() : 0.00,
	// 		'TextFacturaNo': $('#TextFacturaNo').val(),
	// 		//'TxtNota': $('#TxtNota').val(),
	// 		//'TxtObservacion': $('#TxtObservacion').val(),
	// 		'TipoFactura': tc,
	// 		//'TxtGavetas': $('#TxtGavetas').val(),
	// 		'CodigoCliente': $('#codigoCliente').val(),
	// 		'email': $('#Lblemail').val(),
	// 		'CI': $('#LblRUC').val(),
	// 		'NombreCliente': $('#DCCliente option:selected').text(),
	// 		'TC': tc,
	// 		'Serie': $('#LblSerie').text(),
	// 		'DCBancoN': $('#btnToggleInfoBanco').attr('stateval')=="1" ? $('#DCBanco option:selected').text() : "",
	// 		'DCBancoC': $('#btnToggleInfoBanco').attr('stateval')=="1" ? $('#DCBanco').val() : "",
	// 		'T': $('#LblT').val(),
	// 		'TextBanco': $('#btnToggleInfoBanco').attr('stateval')=="1" ? $('#TextBanco').val() : "",
	// 		'TextCheqNo': $('#btnToggleInfoBanco').attr('stateval')=="1" ? $('#TextCheqNo').val() : "",
	// 		'CodDoc': $('#CodDoc').val(),
	// 		'valorBan':  $('#btnToggleInfoBanco').attr('stateval')=="1" ? $('#TextCheque').val() : 0.00,
	// 		'Cta_Cobrar': $('#Cta_CxP').val(),
	// 		'Autorizacion': $('#Autorizacion').val(),
	// 		'CodigoL': $('#CodigoL').val(),
	// 		'PorcIva': document.getElementById('DCPorcenIVA').selectedOptions[0].text,
	// 		'FATextVUnit': (parseFloat($('#LabelTotal').val())/1).toFixed(2),
	// 		'FAVTotal': $('#LabelTotal').val(),
	// 		'FACodLinea': $('#DCLineas').val(),
	// 		'CodigoU': $('.asignTablaDistri')[0].children[10].textContent,
	// 		//'cheking': $('#DCPorcenIVA').val(),
	// 		//'PorcIva': $('#DCPorcenIVA').val()
	// 	}

	// 	if(docbouche != undefined && docbouche.trim() != ''){
	// 		parametros['Comprobante'] = docbouche;
	// 	}

	// 	console.log(parametros);

	// 	$.ajax({
	// 		type: "POST",
	// 		url: '../controlador/facturacion/facturas_distribucion_famC.php?generar_factura=true',
	// 		data: { parametros: parametros },
	// 		dataType: 'json',
	// 		success: function (data) {
	// 			$('#myModal_espera').modal('hide');
	// 			// console.log(data);
	// 			if(data.length == 1){
	// 				if (data.respuesta == 1) {
	// 					Swal.fire({
	// 						icon: 'success',
	// 						title: 'Factura Creada',
	// 						confirmButtonText: 'Ok!',
	// 						allowOutsideClick: false,
	// 					}).then(function () {
	// 						var url = '../../TEMP/' + data.pdf + '.pdf';
	// 						window.open(url, '_blank');
	// 						/*parametros = {
	// 							'TextVUnit': (parseFloat($('#LabelTotal').val())/1).toFixed(2),
	// 							'TextCant': 1,
	// 							'TC': valTC,
	// 							'TxtDocumentos': '.',
	// 							'Codigo': 'FA.99',
	// 							'fecha': $('#MBFecha').val(),
	// 							'CodBod': '',
	// 							'VTotal': $('#LabelTotal').val(),
	// 							'CodigoCliente': $('#codigoCliente').val(),
	// 							'TextServicios': '.',
	// 							'TextVDescto': 0,
	// 							'PorcIva': document.getElementById('DCPorcenIVA').selectedOptions[0].text
	// 						};*/
	// 						AdoLinea();
	// 						eliminar_linea('', '');
	// 						//crearAsientoFFA(parametros);
	// 					})
	// 				} else if (data.respuesta == -1) {
	// 					if(data.text=='' || data.text == null)
	// 					{
	// 						Swal.fire({
	// 							icon: 'error',
	// 							title: 'XML devuelto',
	// 							text:'Error al generar XML o al firmar',
	// 							confirmButtonText: 'Ok!',
	// 							allowOutsideClick: false,
	// 						}).then(function () {
	// 							location.reload();
	// 						})
						
	// 						tipo_error_sri(data.clave);
	// 					}else{
	// 					Swal.fire({
	// 							icon: 'error',
	// 							title: data.text,
	// 							confirmButtonText: 'Ok!',
	// 							allowOutsideClick: false,
	// 						}).then(function () {
	// 							location.reload();
	// 						})
	// 					}
	// 				} else if (data.respuesta == 2) {
	// 					// Swal.fire('XML devuelto', '', 'error');
	// 					Swal.fire({
	// 						icon: 'error',
	// 						title: 'XML devuelto',
	// 						text:'Error al generar XML o al firmar',
	// 						confirmButtonText: 'Ok!',
	// 						allowOutsideClick: false,
	// 					}).then(function () {
	// 						location.reload();
	// 					})
					
	// 					tipo_error_sri(data.clave);
	// 				}
	// 				else if (data.respuesta == 4) {
	// 					Swal.fire('SRI intermitente intente mas tarde', '', 'info');
	// 				} else {
	// 					Swal.fire(data.text, '', 'error');
	// 				}
	// 			}else{
	// 				if (data[1].respuesta == 1) {
	// 					Swal.fire({
	// 						icon: 'success',
	// 						title: 'Nota de Venta y Factura Creadas',
	// 						confirmButtonText: 'Ok!',
	// 						allowOutsideClick: false,
	// 					}).then(function () {
	// 						$('#imprimir_nd').removeAttr('disabled');
	// 						var url = '../../TEMP/' + data[0].pdf + '.pdf';
	// 						url_nd = '../../TEMP/' + data[1].pdf + '.pdf';
							
	// 						window.open(url, '_blank');
	// 						Swal.fire('Puede imprimir la nota de donacion en el botón con icono de impresora', '', 'info');
	// 						/*parametros = {
	// 							'TextVUnit': (parseFloat($('#LabelTotal').val())/1).toFixed(2),
	// 							'TextCant': 1,
	// 							'TC': valTC,
	// 							'TxtDocumentos': '.',
	// 							'Codigo': 'FA.99',
	// 							'fecha': $('#MBFecha').val(),
	// 							'CodBod': '',
	// 							'VTotal': $('#LabelTotal').val(),
	// 							'CodigoCliente': $('#codigoCliente').val(),
	// 							'TextServicios': '.',
	// 							'TextVDescto': 0,
	// 							'PorcIva': document.getElementById('DCPorcenIVA').selectedOptions[0].text
	// 						};*/
	// 						AdoLinea();
	// 						eliminar_linea('', '');
	// 						//crearAsientoFFA(parametros);
	// 					})
	// 				} else if (data[1].respuesta == -1) {
	// 					if(data[1].text=='' || data[1].text == null)
	// 					{
	// 						Swal.fire({
	// 							icon: 'error',
	// 							title: 'XML devuelto',
	// 							text:'Error al generar XML o al firmar',
	// 							confirmButtonText: 'Ok!',
	// 							allowOutsideClick: false,
	// 						}).then(function () {
	// 							location.reload();
	// 						})
						
	// 						tipo_error_sri(data[1].clave);
	// 					}else{
	// 					Swal.fire({
	// 							icon: 'error',
	// 							title: data[1].text,
	// 							confirmButtonText: 'Ok!',
	// 							allowOutsideClick: false,
	// 						}).then(function () {
	// 							location.reload();
	// 						})
	// 					}
	// 				} else if (data[1].respuesta == 2) {
	// 					// Swal.fire('XML devuelto', '', 'error');
	// 					Swal.fire({
	// 						icon: 'error',
	// 						title: 'XML devuelto',
	// 						text:'Error al generar XML o al firmar',
	// 						confirmButtonText: 'Ok!',
	// 						allowOutsideClick: false,
	// 					}).then(function () {
	// 						location.reload();
	// 					})
					
	// 					tipo_error_sri(data[1].clave);
	// 				}
	// 				else if (data[1].respuesta == 4) {
	// 					Swal.fire('SRI intermitente intente mas tarde', '', 'info');
	// 				} else {
	// 					Swal.fire(data[1].text, '', 'error');
	// 				}
	// 			}
				

	// 		},
	// 		error: (err) => {
	// 			Swal.fire('Error', 'Hubo un problema al guardar la factura.','info');
	// 		}
	// 	});

	// }

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

				//tipo_documento();
				//numeroFactura();
			}
		});
		$('#myModal_espera').modal('hide');
	}
	function validar_bodega() {
		var ddl = $('DCBodega').val();
		if (ddl == '') {
			Swal.fire('Ingrese o Seleccione una bodega', '', 'info').then(function () { $('#TextFacturaNo').focus() });
		}
	}

	function cambiar_all()
	{
		if($('#cbx_all').prop('checked'))
		{
			$('.class_integrante').each(function() {
			    const checkbox = $(this);
			    checkbox.prop('checked',true); 
			    
			});
		}else
		{
			$('.class_integrante').each(function() {
			    const checkbox = $(this);
			    checkbox.prop('checked',false); 
			    
			});

		}
	}

