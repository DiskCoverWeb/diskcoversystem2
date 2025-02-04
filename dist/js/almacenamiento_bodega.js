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

	function lugarPorQr(codigo){
		$('#txt_cod_lugar').val(codigo.trim());
		$('#txt_cod_lugar').trigger('blur');
	}

  function cargar_nombre_bodega(nombre,cod)
  {

  	$('#txt_bodega_title').text();
  	$('#txt_bodega_title').text(nombre);
  	$('#txt_cod_bodega').val(cod);
  	$('#txt_cod_lugar').val(cod);
  	if(cod!='.')
  	{
  		contenido_bodega();
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
	    	$('#lista_pedido').html(data);
	    }
	});

  
}


function cargar_bodegas(nivel=1,padre='')
{
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

function asignar_bodega()
{

	 id = '';
	 $('.rbl_pedido').each(function() {
	    const checkbox = $(this);
	    const isChecked = checkbox.prop('checked'); 
	    if (isChecked) {
	        id+= checkbox.val()+',';
	    }
	});

	 bodega = $('#txt_cod_bodega').val();
	 paquete = $('#txt_paquetes').val();

	if(bodega=='.' || bodega =='')
	{
		Swal.fire('Seleccione una bodega','','info');
		return false;
	}

	// if(paquete=='.' || paquete =='')
	// {
	// 	Swal.fire('Seleccione Paquete','','info');
	// 	return false;
	// }
	if(id=='')
	{
		Swal.fire('Seleccione un pedido','','info');
		return false;
	}
	// $('#myModal_espera').modal('show');

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
				Swal.fire('Asignado a bodega','','success');
	    	lineas_pedidos()   	
	    	contenido_bodega();
	    	productos_asignados();
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

function abrir_modal_bodegas()
{
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

function cambiarCamara()
{
    cerrarCamara();
    setTimeout(() => {
        iniciarEscanerQR();
        $('#modal_qr_escaner').modal('show');
         $('#qrescaner_carga').hide();
    }, 1000);
}

 function escanear_qr(campo){
    iniciarEscanerQR(campo);
        $('#modal_qr_escaner').modal('show');
    }

 let scanner; 
 let NumCamara = 0;
 function iniciarEscanerQR(campo_qr) {

    NumCamara = $('#ddl_camaras').val();
    scanner = new Html5Qrcode("reader");
    $('#qrescaner_carga').hide();
    Html5Qrcode.getCameras().then(devices => {
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
						lugarPorQr(decodedText);
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

  function cerrarCamara() {
    if (scanner) {
        scanner.stop().then(() => {            
          $('#qrescaner_carga').show();
          $('#modal_qr_escaner').modal('hide');
        }).catch(err => {
            console.error("Error al detener el escáner:", err);
        });
    }
}


