
	var scanning = false;
	var tbl_asignados_all;
  $(document).ready(function () {
  	validar_ingreso();
  	areas();  
  	motivo_egreso()	
  	notificaciones();


	  tbl_asignados_all = $('#tbl_asignados_all').DataTable({
			searching: false,
      responsive: true,
      paging: false,   
      info: false,   
      autoWidth: false,   
		language: {
			url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
		},
		ajax: {
			url:   '../controlador/inventario/egreso_alimentosC.php?listar_egresos=true',
			type: 'POST',  // Cambia el método a POST    
			dataSrc: '',             
		},
		 scrollX: true,  // Habilitar desplazamiento horizontal
 
		columns: [
			{ data: null, // Columna autoincremental
				  render: function (data, type, row, meta) {
					  return meta.row + 1; // meta.row es el índice de la fila
				  }
			},
			{ data: 'Fecha.date',  
				render: function(data, type, item) {
					return data ? new Date(data).toLocaleDateString() : '';
				}
			},
			{ data: 'Producto' },
			{ data:  null,
			  render: function(data, type, item) {
				  return `${data.Salida} ${data.Unidad}`;                    
				}

			},
			{ data: null,
			   render: function(data, type, item) {
				  return `<button type="button" class="btn-sm btn-danger btn" onclick="eliminar_egreso('${data.ID}')"><i class="bx bx-trash m-0"></i></button>`;                    
				}
			},
			
		],
		order: [
			[1, 'asc']
		]
	});
  })

  // function notificaciones()
  // {
  // 	$.ajax({
	// 	    type: "POST",
	//       	url:   '../controlador/inventario/alimentos_recibidosC.php?listar_notificaciones=true',
	// 	      // data:datos,
	//         dataType:'json',
	// 	    success: function(data)
	// 	    {		    	    	
	// 	    	if(data.length>0)
	// 	    	{
	// 	    		var mensajes = '';
	// 	    		var cantidad  = 0;
	// 	    		 $('#pnl_notificacion').css('display','block');
	// 	    		 data.forEach(function(item,i){
	// 	    		 	mensajes+='<li>'+
	// 										'<a href="#" data-toggle="modal" onclick="mostrar_notificacion(\''+item.Texto_Memo+'\',\''+item.ID+'\',\''+item.Pedido+'\')">'+
	// 											'<h4 style="margin:0px">'+
	// 												item.Asunto+
	// 												'<small>'+formatoDate(item.Fecha.date)+' <i class="fa fa-calendar-o"></i></small>'+
	// 											'</h4>'+
	// 											'<p>'+item.Texto_Memo.substring(0,15)+'...</p>'+
	// 										'</a>'+
	// 									'</li>';
	// 									cantidad = cantidad+1;
	// 	    		 })

	// 	    		 $('#pnl_mensajes').html(mensajes);
	// 	    		 $('#cant_mensajes').text(cantidad);
	// 	    	}else
	// 	    	{

	// 	    		 $('#pnl_notificacion').css('display','none');
	// 	    	}
	// 	    	console.log(data);
	// 	    }
	// 	});  	

  // }

  function mostrar_notificacion(text,id,pedido)
  {

  	cargar_notificacion(id);
  	$('#myModal_notificar').modal('show');
  	$('#txt_mensaje').html(text);  	
  	$('#txt_id_noti').val(id); 	
  	$('#txt_cod_pedido').val(pedido);
  }

  function cambiar_estado()
  {
  	respuesta = $('#txt_respuesta').val();
  	if(respuesta=='' || respuesta=='.')
  	{
  		 Swal.fire("Ingrese una respuesta","",'info');
  		 return false;
  	}
  	parametros = 
  	{
  		'noti':$("#txt_id_noti").val(),
  		'respuesta':respuesta,
  		'pedido':$('#txt_cod_pedido').val(),
  	}
  	$.ajax({
		    type: "POST",
	      	url:   '../controlador/inventario/alimentos_recibidosC.php?cambiar_estado=true',
		      data:{parametros:parametros},
	        dataType:'json',
		    success: function(data)
		    {		    
		    	$('#myModal_notificar').modal('hide');
		    	$('#txt_respuesta').val('');
		    	notificaciones();
		    }
		});  	

  }

  function solucionado()
  {
   
    parametros = 
    {
      'noti':$("#txt_id_noti").val(),
    }
    $.ajax({
        type: "POST",
          url:   '../controlador/inventario/alimentos_recibidosC.php?cambiar_estado_solucionado=true',
          data:{parametros:parametros},
          dataType:'json',
        success: function(data)
        {       
          $('#myModal_notificar').modal('hide');
          notificaciones();
        }
    });   

  }


  function validar_ingreso()
  {
  	$.ajax({
		    type: "POST",
	       	url:   '../controlador/inventario/egreso_alimentosC.php?listar_egresos=true',
		    // data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {

		    	$('#tbl_asignados').html(data);	 
		    	if(!data=='')
		    	{ 
	    		Swal.fire({
	                 title: 'Datos encontrados?',
	                 text: "Se encontraron datos sin guardar desea cargarlos?",
	                 icon: 'warning',
	                 showCancelButton: true,
	                 confirmButtonColor: '#3085d6',
	                 cancelButtonColor: '#d33',
	                 confirmButtonText: 'Si!'
	               }).then((result) => {
	                 if (result.value!=true) {

	                  	
	                 	eliminar_egreso_all();
	                 }
	               })
		    	} 	
		    }
		});


  }


  function eliminar_egreso(id)
  {
 	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/egreso_alimentosC.php?eliminar_egreso=true',
	     data:{id:id},
       dataType:'json',
	    success: function(data)
	    {
	    	lista_egreso();
	    }
	});
  }


  function eliminar_egreso_all()
  {
 	$.ajax({
	    type: "POST",
       url:   '../controlador/inventario/egreso_alimentosC.php?eliminar_egreso_all=true',
	     // data:{id:id},
       dataType:'json',
	    success: function(data)
	    {
	    	lista_egreso();
	    }
	});
  }

   function areas(){
	  $('#ddl_areas').select2({
	    placeholder: 'Seleccione una beneficiario',
	    width:'100%',
	    ajax: {
	      url:   '../controlador/inventario/egreso_alimentosC.php?areas=true',          
	      dataType: 'json',
	      delay: 250,
	      processResults: function (data) {
	        // console.log(data);
	        return {
	          results: data
	        };
	      },
	      cache: true
	    }
	  });
	}
	function area_egreso_modal(){
		
	 	$.ajax({
		    type: "POST",
	       url:   '../controlador/inventario/egreso_alimentosC.php?areas=true',          
		     // data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	var option = '';
		    	 data.forEach(function(item,i){
	         	img = 'simple';
		    	 	if(item.data.Picture!='.'){	 		img = item.data.Picture; 	 	}
	          option+= '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">'+
	                      '<button type="button" class="btn btn-default btn-sm"><img src="../../img/png/'+img+'.png" onclick="cambiar_area(\''+item.id+'\',\''+item.text+'\')"  style="width: 60px;height: 60px;"></button><br>'+
	                      '<b style="white-space: nowrap;">'+item.text+'</b>'+
	                    '</div>';
	        })
	        // $('#txt_paquetes').html(op); 
	        $('#pnl_opciones').html(option);      
		    }
		});
	}

	function cambiar_area(id,text)
	{
		$('#ddl_areas').append($('<option>',{value:  id, text: text,selected: true }));
		$('#myModal_opciones').modal('hide');
	} 

	function motivo_egreso(){
	  $('#ddl_motivo').select2({
	    placeholder: 'Seleccione una beneficiario',
	    width:'100%',
	    ajax: {
	      url:   '../controlador/inventario/egreso_alimentosC.php?motivos=true',          
	      dataType: 'json',
	      delay: 250,
	      processResults: function (data) {
	        // console.log(data);
	        return {
	          results: data
	        };
	      },
	      cache: true
	    }
	  });
	}

	function motivo_egreso_modal(){
		
	 	$.ajax({
		    type: "POST",
	       url:   '../controlador/inventario/egreso_alimentosC.php?motivos=true',          
		     // data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	 var option = '';
		    	 data.forEach(function(item,i){
	         	img = 'simple';
		    	 	if(item.data.Picture!='.'){	 		img = item.data.Picture; 	 	}
	          option+= '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">'+
	                      '<button type="button" class="btn btn-default btn-sm"><img src="../../img/png/'+img+'.png" onclick="cambiar_motivo(\''+item.id+'\',\''+item.text+'\')"  style="width: 60px;height: 60px;"></button><br>'+
	                      '<b>'+item.text+'</b>'+
	                    '</div>';
	        })
	        // $('#txt_paquetes').html(op); 
	        $('#pnl_opciones').html(option);       	 
		    }
		});
	}

	function cambiar_motivo(id,text)
	{
		$('#ddl_motivo').append($('<option>',{value:  id, text: text,selected: true }));
		$('#myModal_opciones').modal('hide');
	}

	function buscar_producto(codigo)
	{
		if(codigo=='')
		{
			return false;
		}
		var parametros = {
		'codigo':$('#txt_cod_producto').val(),
		}
	 	$.ajax({
		    type: "POST",
	       url:   '../controlador/inventario/egreso_alimentosC.php?buscar_producto=true',
		     data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	data = data[0];

		    	$('#txt_id').val(data.ID)
		    	$('#txt_cod_producto').val(data.Codigo_Barra)
				$('#txt_donante').val(data.Cliente)
				$('#txt_grupo').val(data.Producto)
				$('#txt_stock').val(data.Entrada)
				$('#txt_unidad').val(data.Unidad)
		    }
		});
	}

	function productoPorQR(codigo){
		$('#txt_cod_producto').val(codigo).trigger('blur');
	}

	function add_egreso()
	{
		var stock = $('#txt_stock').val(); 
		var cant = $('#txt_cantidad').val(); 
		console.log(cant);
		console.log(stock);
		if(parseFloat(stock) < parseFloat(cant))
		{
			Swal.fire("La catidad supera al Stock","","info")
			return false;
		}else if(parseFloat(cant)<=0  || cant=='' )
		{
			Swal.fire("La catidad invalida","","info")
			return false;
		}
		var parametros = 
		{
			'codigo':$('#txt_cod_producto').val(),
			'id':$('#txt_id').val(),
			'donante':$('#txt_donante').val(),
			'grupo':$('#txt_grupo').val(),
			'stock':$('#txt_stock').val(),
			'unidad':$('#txt_unidad').val(),
			'cantidad':$('#txt_cantidad').val(),
			'fecha':$('#txt_fecha').val(),
			'area':$('#ddl_areas').val(),
			'motivo':$('#ddl_motivo').val(),
			'detalle':$('#txt_detalle').val(),
		}
	 	$.ajax({
		    type: "POST",
	       	url:   '../controlador/inventario/egreso_alimentosC.php?add_egresos=true',
		    data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	if(data==1)
		    	{
		    		Swal.fire("Ingresado","","success");
		    		lista_egreso();
		    	}		    	
		    }
		});

	}
	function lista_egreso2()
	{		
	 	$.ajax({
		    type: "POST",
	       	url:   '../controlador/inventario/egreso_alimentosC.php?listar_egresos=true',
		    // data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	$('#tbl_asignados').html(data);	
		    }
		});
	}
	
	function lista_egreso()
	{		
		tbl_asignados_all.ajax.reload(null, false);
	}

  	function guardar()
	{
		var archivo = $('#file_doc')[0].files[0];
    
	    // Verificar si se seleccionó un archivo
	    if (!archivo) {

	    	Swal.fire("Seleccione un archivo","","info");
	      // alert('Por favor, seleccione un archivo.');
	      return false;
	    }
	    if($('#txt_detalle').val()=='')
	    {
	    	Swal.fire("","Ingrese un detalle de egreso","info");
	    	return false;
	    }
	    if($('#ddl_areas').val()=='')
	    {
	    	Swal.fire("","Seleccione un Area de egreso","info");
	    	return false;
	    }
	    if($('#ddl_motivo').val()=='')
	    {
	    	Swal.fire("","Seleccione un motivo de egreso","info");
	    	return false;
	    }

	    // Crear un objeto FormData
	    var formData = new FormData();
	    formData.append('archivo', archivo);
        
		console.log(formData);
	 	$.ajax({
		    type: "POST",
	       	url:   '../controlador/inventario/egreso_alimentosC.php?guardar_egreso=true',
		    // data:{parametros:parametros},
		    type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType:'json',
		    success: function(data)
		    {
		    	if(data==1)
		    	{
		    		Swal.fire('Guardado','','success').then(function(){
		    			location.reload();
		    		})
		    	}else if(data==-2)
		    	{
		    		Swal.fire('formato de evidencia incorrecto','asegurese de que sea una imagen','error');
		    	}
		    }
		});
	}

	function abrir_modal(op)
	{
		if(op=='M')
		{
			$('#lbl_titulo_modal').text('MOTIVO DE EGRESO');
		 	motivo_egreso_modal()
		}else
		{
			$('#lbl_titulo_modal').text('AREA DE EGRESO');
		 	area_egreso_modal();
		}
		$('#myModal_opciones').modal('show');
	}

	function lista_egreso_checking()
	{		
		var parametros = {
			'desde':$('#txt_desde').val(),
			'hasta':$('#txt_hasta').val()
		}
	 	$.ajax({
		    type: "POST",
	       	url:   '../controlador/inventario/egreso_alimentosC.php?lista_egreso_checking_reportados=true',
		    data:{parametros:parametros},
	       dataType:'json',
		    success: function(data)
		    {
		    	$('#tbl_asignados_check').html(data);	
		    }
		});
	}  	

	function myModal_historial()
	{
		lista_egreso_checking();
		$('#myModal_historial').modal('show');
	}

	function escanear_qr(){
	   iniciarEscanerQR();
		$('#modal_qr_escaner').modal('show');
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


 let scanner;
 let NumCamara = 0;
 function iniciarEscanerQR() {

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
                    // document.getElementById("resultado").innerText = decodedText;
                    productoPorQR(decodedText);
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