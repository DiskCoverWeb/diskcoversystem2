	var scanning = false;
	var campo_qr = '';
  $(document).ready(function () {
		cargar_bodegas()
  	cargar_paquetes()
  	pedidos();

  	 $('#txt_cod_lugar').keydown( function(e) { 
          var keyCode1 = e.keyCode || e.which; 
          if (keyCode1 == 13) { 
          	codigo = $('#txt_cod_lugar').val();
          	codigo = codigo.trim();
          	$('#txt_cod_lugar').val(codigo);
          	buscar_ruta();
          }
      });  
  


  
    $('#txt_codigo').on('select2:select', function (e) {
      var data = e.params.data.data;
	  setearCamposPedidos(data);
    });


  })

  function setearCamposPedidos(data){
	console.log(data);

      $('#txt_id').val(data.ID); 
      $('#txt_fecha_exp').val(formatoDate(data.Fecha_Exp.date));
      $('#txt_fecha').val(formatoDate(data.Fecha.date));
      $('#txt_donante').val(data.Cliente);
      $('#txt_paquetes').val(data.Tipo_Empaque);

      var cantidad = parseFloat(data.Entrada).toFixed(2)
      $('#txt_cant').val(cantidad); // save selected id to input
      if(cantidad>=500)
      {
      	 $('#btn_alto_stock').css('display','initial');
      	 $('#txt_cant').css('color','green');
      	 $('#img_alto_stock').attr('src','../../img/gif/alto_stock_titi.gif');
      }else
      {

      	 $('#btn_alto_stock').css('display','none');
      	 $('#txt_cant').css('color','#000000');
      	 $('#img_alto_stock').attr('src','../../img/png/alto_stock.png');
      }

		  var fecha1 = new Date();
      var fecha2 = new Date(formatoDate(data.Fecha_Exp.date));
			var diferenciaEnMilisegundos = fecha2 - fecha1;
			var diferenciaEnDias = ((diferenciaEnMilisegundos/ 1000)/86400);
			diferenciaEnDias = parseInt(diferenciaEnDias);

			console.log(diferenciaEnDias);
			if(diferenciaEnDias<0)
      {
      	 $('#btn_expired').css('display','initial');
      	 $('#txt_fecha_exp').css('color','red');
      	 $('#img_por_expirar').attr('src','../../img/gif/expired_titi2.gif');
      	 $('#btn_titulo').text('Expirado')
      	 $('#txt_fecha_exp').css('background','#ffff');
      }else if(diferenciaEnDias<=10 && diferenciaEnDias>0){
      	 $('#btn_expired').css('display','initial');
      	 $('#txt_fecha_exp').css('color','yellow');
      	 $('#img_por_expirar').attr('src','../../img/gif/expired_titi.gif');
      	 $('#btn_titulo').text('Por Expirar')
      	 $('#txt_fecha_exp').css('background','#a6a5a5');
      }else
      {
      	 $('#btn_expired').css('display','none');
      	 $('#txt_fecha_exp').css('color','#000000');
      	 $('#img_por_expirar').attr('src','../../img/png/expired.png');
      	 $('#txt_fecha_exp').css('background','#ffff');
      }
      
  	lineas_pedidos();
  }

  function pedidosPorQR(codigo){
		$.ajax({
			url:   '../controlador/inventario/almacenamiento_bodegaC.php?search_contabilizado=true&q='+codigo,          
			method: 'GET',
			dataType: 'json',
			success: (data) => {
				console.log(data);
		        if(data.length > 0){
		          let datos = data[0];
		          // Crear una nueva opción con los 3 parámetros y asignarla al select2
		          const nuevaOpcion = new Option('<div style="background:'+datos.fondo+'"><span style="color:'+datos.texto+';font-weight: bold;">' + datos.text + '</span></div>', datos.id, true, true);
		  
		          // Agregar el atributo `data` a la opción
		          //$(nuevaOpcion).data('data', datos.data);
		  
		          // Añadir y seleccionar la nueva opción
		          $('#txt_codigo').append(nuevaOpcion).trigger('change');//'select2:select'
		          setearCamposPedidos(datos.data);
		        }else{
		          Swal.fire('No se encontró información para el codigo: '+codigo, '', 'error');
		        }
			}
		});
	}

	function lugarPorQr(codigo,item){
		if(item=='')
		{
			$('#txt_cod_lugar').val(codigo.trim());
			$('#txt_cod_lugar').trigger('blur');
		}else
		{
			$('#txt_cod_lugar_div_'+item).val(codigo.trim());
			$('#txt_cod_lugar_div_'+item).trigger('blur');
		}
	}

  function cargar_nombre_bodega(nombre,cod)
  {

	linea = $('#txt_busqueda_manual_lin').val();
	if(linea=='')
	{
	  	$('#txt_bodega_title').text();
	  	$('#txt_bodega_title').text(nombre);
	  	$('#txt_cod_bodega').val(cod);
	  	$('#txt_cod_lugar').val(cod);
	  	if(cod!='.')
	  	{
	  		contenido_bodega();
	  	}
	  }else
	  {
	  	$('#txt_bodega_title_'+linea).text();
	  	$('#txt_bodega_title_'+linea).text(nombre);
	  	$('#txt_cod_lugar_div_'+linea).val(cod);
	  	// $('#txt_cod_lugar_'+linea).val(cod);
	  	if(cod!='.')
	  	{
	  		contenido_bodega();
	  	}
	  }

  	// console.log(nombre)
  }

  function pedidos(){
 
  	$('#txt_codigo').select2({
    placeholder: 'Seleccione una beneficiario',
    width:'100%',
    ajax: {
      url: '../controlador/inventario/almacenamiento_bodegaC.php?search_contabilizado=true',
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        return {
          results: data.map(function (item) {
            return {
              id: item.id,
              text: '<div style="background:'+item.fondo+'"><span style="color:'+item.texto+';font-weight: bold;">' + item.text + '</span></div>',
              data : item.data,
            };
          })
        };
      },
      cache: true
    },
    escapeMarkup: function (markup) {
      return markup;
    }
  });
}

function lineas_pedidos()
{
	var parametros = {
		'num_ped':$('#txt_codigo').val(),
	}
 	$.ajax({
	    type: "POST",
       	url:   '../controlador/inventario/almacenamiento_bodegaC.php?lineas_pedido=true',
	   	data:{parametros:parametros},
       	dataType:'json',
	    success: function(data)
	    {
	    	tr = '';
	    	data.forEach(function(item,i){
	    		tdp = ''; 
	    		cortar = 1;
	    		if(item.TDP=='R'){tdp ='-'+item.TDP; cortar=0}
	    		tr+=`<tr id="tr_principal">
						<td>`
						if(cortar==1)
						{
	    					tr+=`<button type="button" class="btn btn-warning btn-sm" title="Divivir" onclick="dividir_pedido('`+item.Producto+`')">
	    						<i class="bx bx-cut m-0"></i>
	    					</button>`
	    				}
	    				tr+=`</td>
						<td>
						<input type="hidden" value="`+item.ID+tdp+`" id="txt_id_producto" />
						`+item.Producto+`
						</td>
						<td><b id="cant_pedido">`+item.Entrada+'</b> '+item.Unidad+`<span style="display:none" id="dif_dividido"> / <b id="cant_div">`+item.Entrada+`</b>`+item.Unidad+`</span></td>
						<td>
							<div class="row" id="pnl_principal_ruta">									
									<div class="col-sm-12">
										<b>Codigo de lugar</b>
										<div class="d-flex align-items-center input-group-sm">
											<input type="" class="form-control form-control-sm" id="txt_cod_lugar" name="txt_cod_lugar" onblur="buscar_ruta();productos_asignados()">	
											<button type="button" class="btn btn-info btn-sm" style="font-size:8pt;" onclick="abrir_modal_bodegas()"><i class="fa fa-sitemap" style="font-size:8pt;"></i></button>
											<button type="button" class="btn btn-primary btn-sm" style="font-size:8pt;" title="Escanear QR" onclick="escanear_qr('lugar')">
												<i class="fa fa-qrcode" style="font-size:8pt;" aria-hidden="true"></i>
											</button>									
										</div>
									</div>
									<div class="col-sm-12">
										<h6 class="box-title" id="txt_bodega_title">Ruta: </h6>
										<input type="hidden" class="form-control input-xs" id="txt_cod_bodega" name="txt_cod_bodega" readonly>
									</div>				
								</div>
							</td>
					</tr>`

	    	})
	    	$('#lista_pedido').html(tr);
	    }
	});

  
}

numerodiviciones = 1;
function dividir_pedido(Producto)
{
	$('#pnl_principal_ruta').css('display','none')
	$('#dif_dividido').css('display','initial')

	tr =`<tr id="tr_divido_`+numerodiviciones+`">
		<td><button type="button" class="btn btn-sm btn-danger " title="Eliminar linea" onclick="eliminar_linea('`+numerodiviciones+`')"><i class="bx bx-x m-0" style="font-size:8pt"></i></button></td>
		<td>`+Producto+`</td>
		<td><input name="dividido" id="txt_cant_div_`+numerodiviciones+`" class="form-control form-control-sm" value="0" onblur="calcular_divicion(this)" /></td>
		<td>
			<div class="row">									
				<div class="col-sm-12">
					<div class="d-flex align-items-center input-group-sm">
						<input type="" class="form-control form-control-sm" id="txt_cod_lugar_div_`+numerodiviciones+`" name="txt_cod_lugar_div_`+numerodiviciones+`" onblur="buscar_ruta_linea('`+numerodiviciones+`');" placeholder="Codigo de lugar">	
						<button type="button" class="btn btn-info btn-sm" style="font-size:8pt;" onclick="abrir_modal_bodegas('`+numerodiviciones+`')"><i class="fa fa-sitemap" style="font-size:8pt;"></i></button>
						<button type="button" class="btn btn-primary btn-sm" style="font-size:8pt;" title="Escanear QR" onclick="escanear_qr('lugar','`+numerodiviciones+`')">
							<i class="fa fa-qrcode" style="font-size:8pt;" aria-hidden="true"></i>
						</button>									
					</div>
				</div>
				<div class="col-sm-12">
					<h6 class="box-title" id="txt_bodega_title_`+numerodiviciones+`">Ruta: </h6>
					<input type="hidden" class="form-control input-xs" id="txt_cod_bodega_`+numerodiviciones+`" name="txt_cod_bodega" readonly>
				</div>
									
			</div>
			
		</td>
	</tr>`
	numerodiviciones = numerodiviciones+1;

	$('#lista_pedido').append(tr);

}

function eliminar_linea(num)
{
	$('#tr_divido_'+num).remove();
	let filas = document.querySelectorAll("#lista_pedido tr");
	if (filas.length === 1) {
		$('#pnl_principal_ruta').css('display','block')
		$('#dif_dividido').css('display','none')

	}
}

function calcular_divicion(elemento)
{
	pedido = $('#cant_pedido').text();
	let pedido_total = $('#cant_pedido').text();
	let canti_dividido = 0;
	var catidad_div = 0;
	$("input[name='dividido']").each(function() {
	    valor = $(this).val();
	    canti_dividido = canti_dividido+parseInt(valor)
	    if(canti_dividido>parseInt(pedido_total))
	    {
	    	catidad_div = canti_dividido-parseInt(valor)
	    }
	});

	canti = parseInt(pedido_total)-parseInt(canti_dividido);
	if(canti>=0)
	{
		$('#cant_div').text(canti)		
	}else
	{
		var cant = parseInt(pedido_total)-catidad_div
		Swal.fire("El el valor total supera al del pedido","","info").then(function(){
			$("#"+elemento.id).val(0);
			calcular_divicion(elemento);
			$("#"+elemento.id).focus();
		})
	}
}


function cargar_bodegas(nivel=1,padre='')
{
	linea = $('#txt_busqueda_manual_lin').val();
	var parametros = {
		'nivel':nivel,
		'padre':padre,
	}
 	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?lista_bodegas_arbol=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	// console.log(data);
	    	if(nivel==1)
	    	{
	    	 $('#arbol_bodegas').html(data);
	    	}else
	    	{
	    		 $('#h'+padre).html(data);
	    	}
	    }
	});

  
}
function cargar_paquetes()
{
	
 	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?cargar_empaques=true',
	     // data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	var op = '<option value="">Seleccione empaque</option>';
	    	data.forEach(function(item,i){
	    		 op+='<option value="'+item.ID+'">'+item.Proceso+'</option>';
	    	})

	    	$('#txt_paquetes').html(op);	    	
	    }
	});

  
}

function validar_asignacion_bodega(linea='')
{
	id = $('#txt_codigo').val();
	console.log(id);
	if(id=='')
	{
		Swal.fire('Seleccione un producto','','info');
		return false;
	}
	let filas = document.querySelectorAll("#lista_pedido tr");
	if (filas.length === 1) {

		id = $('#txt_id_producto').val();
		bodega = $('#txt_cod_bodega').val();
		console.log(bodega)
		console.log(id)
		if(bodega=='.' || bodega =='')
		{
			Swal.fire('Seleccione una bodega','','info');
			return false;
		}

		if(id=='')
		{
			Swal.fire('Seleccione un pedido','','info');
			return false;
		}
		asignar_bodega(id,bodega)
	}else{
		var valido = 1;
		var total = 0;
		var pedido = $('#cant_pedido').text();
		var parametros = []
		$("input[name='dividido']").each(function(i,item) {

			linea = item.id.split('_');
			li = linea[linea.length-1];

			var linea = parseInt(i)+parseInt(1);
			valor = $(this).val();
			total = parseInt(total)+parseInt(valor);
			cod_bodega = $('#txt_cod_lugar_div_'+li).val();

			if(valor=='' || valor==0 || cod_bodega=='' || cod_bodega=='.')
			{
				valido = 0;
			}
			parametros.push({'cantidad':valor,'codigoBod':cod_bodega})
		});

		console.log(parametros);

		// return false;

		if(parseInt(total)!=parseInt(pedido))
		{
			Swal.fire("Las cantidades no suman el total del pedido","","error")
			return false;
		}

		if(valido==1)
		{
			id = $('#txt_id_producto').val();
			console.log(parametros)
			asignar_bodega_partes(id,parametros)

		}else
		{
			Swal.fire("Uno o mas campos de valor o codigo de bodega estan vacios","","error")
			return false;
		}

		// return false;
	}


}

function asignar_bodega(id,bodega)
{
	$('#myModal_espera').modal('show');
	var parametros = {
		'id':id,
		'bodegas':bodega,
	}
	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?asignar_bodega=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {

			$('#myModal_espera').modal('hide');
			Swal.fire('Asignado a bodega','','success').then(function(){
				location.reload();
			});
	    	// lineas_pedidos()   	
	    	// contenido_bodega();
	    	// productos_asignados();
	    }
	});
	
}

function asignar_bodega_partes(id,parametros)
{
	$('#myModal_espera').modal('show');
	var parametros = {
		'id':id,
		'parametros':parametros,
	}
	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?asignar_bodega_partes=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {

			$('#myModal_espera').modal('hide');
			Swal.fire('Asignado a bodega','','success').then(function(){
				location.reload();
			});
	    	// lineas_pedidos()   	
	    	// contenido_bodega();
	    	// productos_asignados();
	    }
	});
	
}

function desasignar_bodega()
{
	 id = '';
	 $('.rbl_pedido_des').each(function() {
	    const checkbox = $(this);
	    const isChecked = checkbox.prop('checked'); 
	    if (isChecked) {
	        id+= checkbox.val()+',';
	    }
	});

	 bodega = $('#txt_cod_bodega').val();

	if(bodega=='.' || bodega =='')
	{
		Swal.fire('Seleccione una bodega','','info');
		return false;
	}
	if(id=='')
	{
		Swal.fire('Seleccione un pedido','','info');
		return false;
	}

	var parametros = {
		'id':id,
		'bodegas':bodega,
	}
	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?desasignar_bodega=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	lineas_pedidos()   	
	    	contenido_bodega();	    	
	    	productos_asignados();
	    }
	});
	
}

function contenido_bodega()
{
	var parametros = {
		'num_ped':$('#txt_codigo').val(),
		'bodega':$('#txt_cod_bodega').val(),
	}
 	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?contenido_bodega=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	$('#arbol_bodegas li span.bg-success').removeClass('bg-success');
	    	id = $('#txt_cod_bodega').val();
	    	id = id.replaceAll('.','_');
	    	$('#contenido_bodega').html(data);
	    	$('#c_'+id).addClass('bg-success');	
	    	productos_asignados();
	    }
	});

}

function productos_asignados()
{
	var parametros = {
		'bodegas':$('#txt_cod_lugar').val(),
	}
 	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?productos_asignados=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	$('#tbl_asignados').html(data);
	    }
	});

}

function  eliminar_bodega(id)
{
	var parametros = {
		'id':id,
	}
	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?eliminar_bodega=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	lineas_pedidos()   	 	
	    	productos_asignados();
	    	$('#contenido_bodega').html('');
	    	$('#txt_cod_bodega').val('.');
	    	$('#txt_bodega_title').text('Ruta: ');
	    }
	});

}

function cargar_info(codigo)
{
	var parametros = {
		'codigo':codigo,
	}
	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/almacenamiento_bodegaC.php?cargar_info=true',
	     data:{parametros:parametros},
       dataType:'json',
	    success: function(data)
	    {
	    	$('#pnl_contenido').html(data)
	    }
	});

}

function abrir_modal_bodegas(linea='')
{
	 $('#txt_busqueda_manual_lin').val(linea)
	 $('#myModal_arbol_bodegas').modal('show');
}

async function buscar_ruta()
{  
	// if($('#txt_cod_bodega').val()!='' && $('#txt_cod_bodega').val()!='.' ){cargar_bodegas();}

	 codigo = $('#txt_cod_lugar').val();
	 codigo = codigo.trim();
	  $('#txt_cod_lugar').val(codigo);
	 // pasos = codigo.split('.');	 
	 // let ruta = '';
	 // let bodega = '';
	 // for (var i=0 ; i <= pasos.length ; i++) {
	 // 		bodega+=pasos[i]+'_';
	// 		let pasos2 = bodega.substring(0 ,bodega.length-1);
	// 		$('#c'+pasos2).prop('checked',false);
   //  	$('#c_'+pasos2).click();
	// 		await sleep(3000);
	// 		console.log('espera');
	 // }
	// await pasos.forEach(function(item,i){
	// 		bodega+=item+'_';
	// 		let pasos2 = bodega.substring(0 ,bodega.length-1);
  //   	$('#c_'+pasos2).click();
	// 		await sleep(7000);
	// 		console.log('espera');
	//  })
	 var parametros = {
			'codigo':codigo,
		}
		$.ajax({
		    type: "POST",
	       url:   '../controlador/inventario/almacenamiento_bodegaC.php?cargar_lugar=true',
		     data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	$('#txt_bodega_title').text('Ruta:'+data);
		    	$('#txt_cod_bodega').val(codigo);
		    	$('#txt_cod_lugar').val(codigo);
		    	productos_asignados();
		    }
		});
}


async function buscar_ruta_linea(item)
{  
	// if($('#txt_cod_bodega').val()!='' && $('#txt_cod_bodega').val()!='.' ){cargar_bodegas();}

	 codigo = $('#txt_cod_lugar_div_'+item).val();
	 codigo = codigo.trim();
	 $('#txt_cod_lugar_div_'+item).val(codigo);
	 var parametros = {
			'codigo':codigo,
		}
		$.ajax({
		    type: "POST",
	       url:   '../controlador/inventario/almacenamiento_bodegaC.php?cargar_lugar=true',
		     data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	$('#txt_bodega_title_'+item).text('Ruta:'+data);
		    	$('#txt_cod_bodega_'+item).val(codigo);
		    	$('#txt_cod_lugar_div_'+item).val(codigo);
		    	// productos_asignados();
		    }
		});
}




 function escanear_qr(campo,item){
 	console.log(campo);
    	iniciarEscanerQR(campo,item);
        $('#modal_qr_escaner').modal('show');
    }

 let scanner; 
 let NumCamara = 0;
 function iniciarEscanerQR(campo_qr,item='') {

 	console.log(campo_qr);
    NumCamara = $('#ddl_camaras').val();
    scanner = new Html5Qrcode("reader");
    $('#qrescaner_carga').hide();
    Html5Qrcode.getCameras().then(devices => {
       op = '';
       devices.forEach((camera, index) => {
         op+='<option value="'+index+'">Camara '+(index+1)+'</option>'
       });
       $('#ddl_camaras').html(op)

        if (devices.length > 0) {
            let cameraId = devices[NumCamara].id; // Usa la primera cámara disponible
            scanner.start(
                cameraId,
                {
                    fps: 10, // Velocidad de escaneo
                    qrbox: { width: 250, height: 250 } // Tamaño del área de escaneo
                },
                (decodedText) => {
                	console.log(campo_qr)
                    if(campo_qr == 'ingreso'){
											pedidosPorQR(decodedText);
										}else if(campo_qr == 'lugar'){						
											lugarPorQr(decodedText,item);
										}
                    scanner.stop(); // Detiene la cámara después de leer un código
                    $('#modal_qr_escaner').modal('hide');
                },
                (errorMessage) => {
                    console.log("Error de escaneo:", errorMessage);
                }
            );
        } else {
            alert("No se encontró una cámara.");
        }
    }).catch(err => console.error("Error al obtener cámaras:", err));
}




