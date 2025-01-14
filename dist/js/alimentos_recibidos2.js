var video;
	var canvasElement;
	var canvas;
	var scanning = false;
  $(document).ready(function () {
    video = document.createElement("video");
    canvasElement = document.getElementById("qr-canvas");
    canvas = canvasElement.getContext("2d", { willReadFrequently: true });
    notificaciones();
    cargar_paquetes();
     setInterval(function() {
         notificaciones();
          }, 5000); 
    $('#txt_fecha').focus();

    $(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
      $(this).closest(".select2-container").siblings('select:enabled').select2('open');
    });

  	 window.addEventListener("message", function(event) {
            if (event.data === "closeModal") {
                autocoplet_ingreso();
            }
        });    
  	autocoplet_alimento();
  	autocoplet_ingreso();
  	pedidos();

    $('#modal_cantidad').on('shown.bs.modal', function () {
        $('#txt_cantidad2').focus();
    })  

     $('#modal_producto').on('shown.bs.modal', function () {
           $('#txt_referencia').focus();
    })  
     $('#txt_cantidad2').keydown( function(e) { 
          var keyCode1 = e.keyCode || e.which; 
          if (keyCode1 == 13) { 
             cambiar_cantidad();
             $('#txt_cantidad').focus();
          }
      });


  	
  	$('#ddl_producto').on('select2:select', function (e) {
      var data = e.params.data.data;
      cargar_pedido2();
      $('#txt_unidad').val(data[0].Unidad);
      $('#txt_producto').append($('<option>',{value: data[0].Codigo_Inv, text:data[0].Producto,selected: true }));
      
      $('#txt_referencia').val(data[0].Codigo_Inv);
      $('#txt_grupo').val(data[0].Item_Banco);
      $('#txt_costo').val(data[0].PVP);
      $('#txt_cta_inv').val(data[0].Cta_Inventario);
      $('#txt_TipoSubMod').val(data[0].TDP);
      $('#txt_producto').prop('disabled',true);
      $('#modal_producto').modal('hide');

      primera_vez = $('#txt_primera_vez').val();

      if(data[0].TDP=='R')
      {
	      setTimeout(() => {  
	      		$('#txt_titulo_mod').text(data[0].Producto);
	      		if(primera_vez!=1)
	      		{
			      	$('#modal_calendar').modal('show');
			      }else{
			      	$('#modal_producto_2').modal('show');
			      }
	    	}, 1000);     	
	      
      }else{
      	$('#txt_TipoSubMod').val('.')
      }
      costeo(data[0].Codigo_Inv);
      $('#txt_grupo').focus();
    });

    $('#ddl_producto2').on('select2:select', function (e) {
      var data = e.params.data.data;
      console.log(data);
        $('#lbl_unidad').text(data[0].Unidad);
      });


   $('#txt_producto').on('select2:select', function (e) {
      var data = e.params.data.data;
      $('#txt_unidad').val(data[0].Unidad);
      $('#txt_producto').append($('<option>',{value: data[0].Codigo_Inv, text:data[0].Producto,selected: true }));
      
      $('#txt_referencia').val(data[0].Codigo_Inv);
      $('#txt_grupo').val(data[0].Item_Banco);
      $('#txt_costo').val(data[0].PVP);
      $('#txt_cta_inv').val(data[0].Cta_Inventario);
      $('#txt_TipoSubMod').val(data[0].TDP);
      $('#txt_producto').prop('disabled',true);
      $('#modal_producto').modal('hide');

      primera_vez = $('#txt_primera_vez').val();

      cargar_pedido2();

      if(data[0].TDP=='R')
      {
	      setTimeout(() => {  
	      		$('#txt_titulo_mod').text(data[0].Producto);
			      if(primera_vez!=1)
	      		{
			      	$('#modal_calendar').modal('show');
			      }else{
			      	$('#modal_producto_2').modal('show');
			      }
	    	}, 1000);     	
	      
      }
      costeo(data[0].Codigo_Inv);
    });

    $('#txt_codigo').on('select2:select', function (e) {
      var data = e.params.data.data;
    	setearCamposPedidos(data);
    });

  })

  function setearCamposPedidos(data){
    limpiar();
    	limpiar_reciclaje();

      console.log(data);

      $('#txt_id').val(data.ID); // display the selected text
      $('#txt_fecha').val(formatoDate(data.Fecha_P.date)); // display the selected text
      $('#txt_ci').val(data.CI_RUC); // save selected id to input
      $('#txt_donante').val(data.Cliente); // save selected id to input
      $('#txt_tipo').val(data.Actividad); // save selected id to input
      $('#txt_cant').val(parseFloat(data.TOTAL).toFixed(2)); // save selected id to input
      $('#txt_comentario').val(data.Mensaje); // save selected id to input
      $('#txt_ejec').val(data.Cod_Ejec); // save selected id to input

      $('#txt_contra_cta').val(data.Cta_Haber); // save selected id to input
      $('#txt_cta_inv').val(data.Cta_Debe); // save selected id to input

      $('#txt_codigo_p').val(data.CodigoP)      
      $('#txt_TipoSubMod').val(data.Giro_No)
      $('#txt_responsable').val(data.Responsable);
      $('#txt_comentario2').val(data.Llamadas);
      // if(data.Giro_No!='R')
      // {
      // 	$('#btn_cantidad').prop('disabled',false);
      // 	$('#txt_producto').prop('disabled',false);
      // }else
      // {
      // 	$('#btn_cantidad').prop('disabled',true);
      // 	$('#txt_producto').prop('disabled',true);
      // 	$('#modal_producto_2').modal('show');
      // }

      if(data.Cod_R=='0' || data.Cod_R=='.')
      {
      	$('#img_estado').attr('src','../../img/png/bloqueo.png');
      }else
      {

      	$('#img_estado').attr('src','../../img/png/aprobar.png');
      }
      $('#txt_temperatura').val(data.Porc_C); // save selected id to input
      $('#ddl_alimento').append($('<option>',{value: data.Cod_C, text:data.Proceso,selected: true }));
      	cargar_sucursales();      
      	// cargar_pedido();
   
      	 // $('#pnl_normal').css('display','none');
        
            cargar_pedido();

         setInterval(function() {
         cargar_pedido2();
         cargar_pedido();
          }, 5000); 
  }

   function pedidos(){
  $('#txt_codigo').select2({
    placeholder: 'Seleccione una beneficiario',
    width:'100%',
    ajax: {
      url:   '../controlador/inventario/alimentos_recibidosC.php?search=true',          
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

function pedidosPorQR(codigo){
		$.ajax({
			url:   '../controlador/inventario/alimentos_recibidosC.php?search=true&q='+codigo,          
			method: 'GET',
			dataType: 'json',
			success: (data) => {
				console.log(data);
        if(data.length > 0){
          let datos = data[0];
          // Crear una nueva opción con los 3 parámetros y asignarla al select2
          const nuevaOpcion = new Option(datos.text.trim(), datos.id, true, true);
  
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
        var option = '';
        data.forEach(function(item,i){

          option+= '<div class="col-md-6 col-sm-6">'+
                      '<button type="button" class="btn btn-default btn-sm"><img src="../../img/png/'+item.Picture+'.png" onclick="cambiar_empaque(\''+item.ID+'\')"  style="width: 60px;height: 60px;"></button><br>'+
                      '<b>'+item.Proceso+'</b>'+
                    '</div>';

           op+='<option value="'+item.ID+'">'+item.Proceso+'</option>';
        })

        $('#txt_paquetes').html(op); 
        $('#pnl_tipo_empaque').html(option);        
      }
  });

  
}


 function costeo(cta_inv)
  {
  	 var parametros = 
  	 {
  	 	 'cta_inv':cta_inv,
  	 }
  	  $.ajax({
	      type: "POST",
	      url: '../controlador/inventario/alimentos_recibidosC.php?producto_costo=true',
	      data:{parametros:parametros},
          dataType:'json',
	      success: function(data)
	      {
	      	// console.log(data)
	      	if(parseFloat(data.Costo)!=0)
	      	{
	      		$('#txt_costo').val(data.Costo);
	      	}
	      
	      }
	  });
  }


  function guardar()
  {
  		var ingresados_kardex = $('#txt_cant_total').val();
  		var ingresados_pedido = $('#txt_cant_total_pedido').val();

  		var total = $('#txt_cant').val();
      var faltantes = $('#txt_faltante').val();
  		
  		if((parseFloat(ingresados_kardex)+parseFloat(ingresados_pedido))< parseFloat(total))
  		{
  			 Swal.fire('No se ha completa todo el pedido ','Asegurese de que el pedido este completo','info');
  			 return false;
  		}
      if(parseFloat(faltantes)<0)
      {
         Swal.fire('No se pudo guardar ','El ingreso realizado necesita de revision y correccion en sus valores la diferencia debe ser 0','error');
         return false;
      }
  	 var parametros = $('#form_correos').serialize();
  	  $.ajax({
	      type: "POST",
	      url: '../controlador/inventario/alimentos_recibidosC.php?guardar2=true',
	      data:parametros,
          dataType:'json',
	      success: function(data)
	      {
	      	if(data==1)
	      	{
	      		Swal.fire('Registro Guardado','','success').then(function(){
	      			location.reload();
	      		});
	      	}
	      
	      }
	  });
  }

  function guardar_pedido()
  {
  		var total_ingresado_pedido = $('#txt_cant_total_pedido').val();
  		var total_ingresado_kardex = $('#txt_cant_total').val(); 
  		var total_recibir = $('#txt_cant').val();


  		var cant = $('#txt_cantidad_pedido').val();


  		var produc = $('#ddl_producto2').val();


  		if(cant==0 || cant=='')
  		{
  			Swal.fire('Ingrese una cantidad valida','','info')
  			return false;
  		}  	
  		if(produc==null || produc=='')
  		{
  			Swal.fire('Seleccione un producto','','info')
  			return false;
  		}  	

  		total_final = parseFloat(cant)+parseFloat(total_ingresado_kardex)+parseFloat(total_ingresado_pedido);
  		cant_suge = parseFloat(total_recibir);
  		


  	 if(total_final>cant_suge)
  	 {
  	 	// console.log(total_final);
  	 	// console.log(cant_suge);
  	 		Swal.fire('La cantidad Ingresada supera a la cantidad registrada','','info');
  	 		return false
  	 }
  	  var parametros = $('#form_correos').serialize();
  	  $.ajax({
	      type: "POST",
	      url: '../controlador/inventario/alimentos_recibidosC.php?guardar_pedido=true',
	      data:parametros+'&producto_pedido='+$('#ddl_producto2').val()+'&cantidad_pedido='+$('#txt_cantidad_pedido').val()+'&total_pedido='+$('#txt_cant_total_pedido').val(),
          dataType:'json',
	      success: function(data)
	      {	      	
	      		cargar_pedido2();	
	      }
	  });
  }
 function autocoplet_alimento(){
  $('#ddl_alimento').select2({
    placeholder: 'Seleccione una beneficiario',
    width:'100%',
    ajax: {
      url:   '../controlador/inventario/alimentos_recibidosC.php?alimentos=true',
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
function autocoplet_ingreso()
  {
  	 // var parametros = $('#form_correos').serialize();
  	  $.ajax({
	    type: "POST",
      	url:   '../controlador/inventario/alimentos_recibidosC.php?detalle_ingreso=true',
	    // data:{parametros:parametros},
        dataType:'json',
	    success: function(data)
	    {
	    	// console.log(data);
	    	option = '';
	    	data.forEach(function(item,i){
	    		// console.log(item);
	    		option+='<option value="'+item.Codigo+'">'+item.Cliente+'</option>';
	    	})	 
	    	$('#ddl_ingreso').html(option);     
	    }
	});
  }
// function autocoplet_ingreso(){
//   $('#ddl_ingreso').select2({
//     placeholder: 'Seleccione',
//     // width:'90%',
//     ajax: {
//       url:   '../controlador/inventario/alimentos_recibidosC.php?donante=true',
//       dataType: 'json',
//       delay: 250,
//       processResults: function (data) {
//         // console.log(data);
//         return {
//           results: data
//         };
//       },
//       cache: true
//     }
//   });
// }

  function nuevo_proveedor()
  {
  	$('#myModal_provedor').modal('show');
  }
  function option_select()
  {
	  	var id = $('#ddl_ingreso').val();
	  	$.ajax({
		    type: "POST",
	      	url:   '../controlador/inventario/alimentos_recibidosC.php?datos_ingreso=true',
		    data:{id:id},
	        dataType:'json',
		    success: function(data)
		    {
		    	// console.log(data);
		    	$('#txt_codigo').val(data.Cod_Ejec)
		    	$('#txt_ci').val(data.CI_RUC)
		    	$('#txt_donante').val(data.Cliente)
		    	$('#txt_tipo').val(data.Actividad)
		    }
		});  	
  }

  function generar_codigo()
  {
  	 var cod = $('#txt_codigo').val();
  	 var partes = cod.split('-');
  	 cod = partes[0];
  	 var fecha = $('#txt_fecha').val();
  	 if(fecha!='')
  	 {
	  	 var fecha_formato = new Date(fecha);
	  	 // $('#txt_codigo').val('');
	  	 year = fecha_formato.getFullYear().toString();
	  	 mes = fecha_formato.getMonth()+1;
	  	 if(mes<10)
	  	 {
	  	 	mes = '0'+mes; 
	  	 }
	  	 day = fecha_formato.getDate()+1
	  	 if(day<10)
	  	 {
	  	 	day = '0'+day; 
	  	 }
	  	 // console.log(year.substr(2,4))
	  	 $('#txt_codigo').val(cod+'-'+year.substr(2,4)+''+mes+''+day)
	  	 autoincrementable();
  		}
  }
  function autoincrementable(){
  		parametros = 
  		{
  			'fecha':$('#txt_fecha').val(),
  		}
	  	$.ajax({
		    type: "POST",
	      	url:   '../controlador/inventario/alimentos_recibidosC.php?autoincrementable=true',
		    data:{parametros:parametros},
	        dataType:'json',
		    success: function(data)
		    {
		    	// console.log(data);
		    	var cod = $('#txt_codigo').val();
		    	$('#txt_codigo').val(cod+'-'+data)
		    	
		    }
		});  	
  }
  function show_panel()
  {
  	 var id = $('#txt_id').val();
  	 var cant_suge = $('#txt_cant').val();
  	 var cant_ing = $('#txt_cantidad').val();
  	 var cant_total = $('#txt_cant_total').val();
  	 var fe_exp = $('#txt_fecha_exp').val();


  	 	var cant_total = $('#txt_cant_total_pedido').val();
  		var cant_total_kardex = $('#txt_cant_total').val();  		

	  	 var producto = $('#txt_producto').val();
	  	 // console.log(producto);
		   	if(producto=='' || fe_exp=='' || cant_ing=='' || cant_ing==0)
		  	 {
		  	 	Swal.fire('Ingrese todo los datos','','info');
		  	 		return false
		  	 }


  		total_final = (parseFloat(cant_ing)+parseFloat(cant_total_kardex)+parseFloat(cant_total));
  		cant_suge = parseFloat(cant_suge);
	   if($('#txt_TipoSubMod').val()!='R')
	   {
  	 	if(total_final >cant_suge)
  	 	{
        // console.log(total_final+'-'+cant_suge);
  	 			Swal.fire('La cantidad Ingresada supera a la cantidad registrada','','info');
  	 			return false
  	 	}
  	 }

      var sucur = $('#ddl_sucursales').val();
     if($("#pnl_sucursal").is(":visible")==true && sucur=='')
      {
         Swal.fire('Seleccione una sucursal ','','info');
         return false;
      }

      var tipo_empaque = $('#txt_paquetes').val();
      if(tipo_empaque=='')
      {
         Swal.fire('Seleccione el tipo de empaque ','','info');
         return false;
      }
  	 if(id=='')
  	 {
  	 		Swal.fire('Seleccione un registro','','info');
  	 		return false;
  	 }else
  	 {
  	 		agregar();
  	 }
  }

  function show_calendar()
  {
  	$('#modal_calendar').modal('show');
  }
  function show_producto()
  {  	
  		$('#modal_producto').modal('show');
  }
  function show_producto2(id)
  {
  	$('#txt_id_linea_pedido').val(id);
  	$('#modal_producto_2').modal('show');
  }
  function show_cantidad()
  {
  	$('#modal_cantidad').modal('show');
    $("#modal_cantidad #txt_cantidad2").focus();
    // $('#txt_cantidad2').trigger( "focus");
  }
  function show_empaque()
  {
    $('#modal_empaque').modal('show');
    // $('#txt_cantidad2').trigger( "focus");
  }

  function cambiar_cantidad()
  {
  	var can = $('#txt_cantidad2').val();
  	$('#txt_cantidad').val(can);
  	$('#modal_cantidad').modal('hide');
    $('#txt_cantidad').focus();
  }
   function cambiar_sucursal()
  {
  	var can = $('#ddl_sucursales2').val();
    $('#ddl_sucursales').val(can);    
    $('#modal_sucursal').modal('hide');
    if($('#txt_TipoSubMod').val()=='R')
    {
        $('#modal_producto_2').modal('show');     
    }
  }

  function cambiar_empaque(can)
  {
    $('#modal_empaque').modal('hide');
    $('#txt_paquetes').val(can);

    if($("#pnl_sucursal").is(":visible")==true && $('#ddl_sucursales').val()=='')
    {
      $('#modal_sucursal').modal('show');
    }else{
      if($('#txt_TipoSubMod').val()=='R')
      {
          $('#modal_producto_2').modal('show');     
      }  
    }

    // if($('#txt_TipoSubMod').val()=='R')
    // {
    //     $('#modal_producto_2').modal('show');     
    // }

  }

  function ocultar_comentario()
  {

  	 var cbx = $('input[type=radio][name=cbx_evaluacion]:checked').val();
  	 if(cbx=='R')
  	 {
  	 	 $('#pnl_comentario').css('display','block');
  	 }else
  	 {
  	 	 // $('#pnl_comentario').css('display','none');
  	 }
  	 // console.log(cbx);
  }

  function limpiar_reciclaje()
  {  	

  	$('#txt_producto').val(null).trigger('change');
  	$('#ddl_producto').val(null).trigger('change');
  	$('#txt_producto').attr('readonly',false);
  	$('#txt_referencia').val('');

  	$('#btn_cantidad').prop('disabled',false)  	
  	$('#txt_producto').prop('disabled',false)


  	$('#txt_TipoSubMod').val('.');
  	$('#txt_grupo').val('');
  	$('#txt_unidad').val('');

  	//reciclaje
  	$('#txt_producto2').val(null).trigger('change');
  	$('#txt_referencia2').val('');
  }

  function limpiar_reciclaje2()
  {  	
  	$('#txt_producto2').val(null).trigger('change');
  	$('#ddl_producto2').val(null).trigger('change');
  	$('#txt_producto2').attr('readonly',false);
  	$('#txt_referencia2').val('');
  }
  function cargar_sucursales()
 	{    
    var parametros = {
        'ruc':$('#txt_ci').val(),
    }
     $.ajax({
      data:  {parametros,parametros},
      url:   '../controlador/modalesC.php?sucursales=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        op = '<option value="">Seleccione sucursal</option>';
        var sucursal = 0;
        response.forEach(function(item,i){
            sucursal = 1;
            op+="<option value=\""+item.ID+"\">"+item.TP+' - '+item.Direccion+"</option>";
        })

        if(sucursal==1)
        {
            $('#pnl_sucursal').css('display','block');
        }else{            
            $('#pnl_sucursal').css('display','none');
        }

        $('#ddl_sucursales').html(op);
        $('#ddl_sucursales2').html(op);
        // console.log(response);
        
      }, 
      error: function(xhr, textStatus, error){
        $('#myModal_espera').modal('hide');           
      }
    });

 }

  function cargar_sucursales2()
 	{    
    var parametros = {
        'ruc':$('#txt_ci').val(),
    }
     $.ajax({
      data:  {parametros,parametros},
      url:   '../controlador/modalesC.php?sucursales=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        let op = '<option value="">Seleccione sucursal</option>';
        var sucursal = 0;
        response.forEach(function(item,i){
            sucursal = 1;
            op+="<option value=\""+item.ID+"\">"+item.Direccion+"</option>";
        })

        if(sucursal==1)
        {
            $('#pnl_sucursal').css('display','block');
        }else{            
            $('#pnl_sucursal').css('display','none');
        }

        $('#ddl_sucursales2').html(op);
        // console.log(response);
        
      }, 
      error: function(xhr, textStatus, error){
        $('#myModal_espera').modal('hide');           
      }
    });

 }

 function show_sucursal()
 {
 		cargar_sucursales2();
 	 $('#modal_sucursal').modal('show');
 }

 function notificar()
 {
   var codigo = $('#txt_codigo').val();
   console.log(codigo);
    if(codigo=='')
    {
       Swal.fire("Seleccione un pedido","","info");
       return false;
    }

    if($('#txt_notificar').val()=='')
    {
      Swal.fire("Ingrese un texto","","info");
       return false;
    }

    var parametros = {
        'notificar':$('#txt_notificar').val(),
        'id':$('#txt_id').val(),
        'asunto':'De Clasificacion a Recepcion',
        'pedido':$('#txt_codigo').val(),
        'de_proceso':2, 
        'pa_proceso':1, 
    }
     $.ajax({
      data:  {parametros,parametros},
      url:   '../controlador/inventario/alimentos_recibidosC.php?notificar_clasificacion=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response==1)
        {
          Swal.fire("","Notificacion enviada","success");
          $('#modal_notificar').modal('hide');        
        }
        // console.log(response);
        
      }, 
      error: function(xhr, textStatus, error){
        $('#myModal_espera').modal('hide');           
      }
    });
 }

 function comentar()
 {
   var codigo = $('#txt_codigo').val();
   console.log(codigo);
    if(codigo=='')
    {
       Swal.fire("Seleccione un pedido","","info");
       return false;
    }

    var parametros = {
        'notificar':$('#txt_comentario2').val(),
        'id':$('#txt_id').val(),
        'asunto':'Recepcion',
        'pedido':$('#txt_codigo').val(),
    }
     $.ajax({
      data:  {parametros,parametros},
      url:   '../controlador/inventario/alimentos_recibidosC.php?comentar_clasificacion=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response==1)
        {
          Swal.fire("","Comentario guardado","success");
          $('#modal_notificar').modal('hide');        
        }
        // console.log(response);
        
      }, 
      error: function(xhr, textStatus, error){
        $('#myModal_espera').modal('hide');           
      }
    });
 }


   function notificaciones()
  {
    $.ajax({
        type: "POST",
          url:   '../controlador/inventario/alimentos_recibidosC.php?listar_notificaciones=true',
          // data:datos,
          dataType:'json',
        success: function(data)
        {               
          if(data.length>0)
          {
            var mensajes = '';
            var cantidad  = 0;
             $('#pnl_notificacion').css('display','block');
             data.forEach(function(item,i){
              mensajes+='<li>'+
                      '<a href="#" data-toggle="modal" onclick="mostrar_notificacion(\''+item.Texto_Memo+'\',\''+item.ID+'\',\''+item.Pedido+'\')">'+
                        '<h4 style="margin:0px">'+
                          item.Asunto+
                          '<small>'+formatoDate(item.Fecha.date)+' <i class="fa fa-calendar-o"></i></small>'+
                        '</h4>'+
                        '<p>'+item.Texto_Memo.substring(0,15)+'...</p>'+
                      '</a>'+
                    '</li>';
                    cantidad = cantidad+1;
             })

             $('#pnl_mensajes').html(mensajes);
             $('#cant_mensajes').text(cantidad);
          }else
          {

             $('#pnl_notificacion').css('display','none');
          }
          // console.log(data);
        }
    });   

  }

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

  function nueva_notificacion()
  {
    $('#modal_notificar').modal('show');
  }

  function reporte_pdf()
  {  
     var num_ped = $('#txt_codigo').val();
     if(num_ped.trim()==''){
      Swal.fire('Seleccione codigo de Ingreso', '', 'warning');
      return;
     }
     var url = '../controlador/inventario/alimentos_recibidosC.php?imprimir_pdf=true&num_ped='+num_ped;  
      window.open(url, '_blank');
  }

  function imprimir_etiquetas_pdf()
  {  
     var num_ped = $('#txt_codigo').val();
     if(num_ped.trim()==''){
      Swal.fire('Seleccione codigo de Ingreso', '', 'warning');
      return;
     }
      $('#myModal_espera').modal('show');
      $.ajax({
        type: "POST",
        url: '../controlador/inventario/alimentos_recibidosC.php?imprimir_etiquetas=true',
        data: {num_ped}, 
        dataType:'json',
        success: function(data)
        {
          $('#myModal_espera').modal('hide');
          /*let host = location.pathname;

          let url = "";
          if (host.includes('diskcover')) {
            
            //  let indiceFinal = indiceInicial + subcadena.length - 1;
              url = '/'+host.split('/')[1]+'/TEMP/' + data.pdf + '.pdf';
          } else {
              url = '/TEMP/' + data.pdf + '.pdf';
          }

          printJS({ 
            printable: url,
            type: 'pdf'
          });*/

          var url = '../../TEMP/' + data.pdf + '.pdf';
          
          window.open(url, '_blank');
        }
      })
  }

  function imprimir_etiquetas()
  {  
     var num_ped = $('#txt_codigo').val();
     if(num_ped.trim()==''){
      Swal.fire('Seleccione codigo de Ingreso', '', 'warning');
      return;
     }
      $('#myModal_espera').modal('show');
      $.ajax({
        type: "POST",
        url: '../controlador/inventario/alimentos_recibidosC.php?imprimir_etiquetas=true',
        data: {num_ped}, 
        dataType:'json',
        success: function(data)
        {
          $('#myModal_espera').modal('hide');
          let host = location.pathname;

          let url = "";
          if (host.includes('diskcover')) {
            
            //  let indiceFinal = indiceInicial + subcadena.length - 1;
              url = '/'+host.split('/')[1]+'/TEMP/' + data.pdf + '.pdf';
          } else {
              url = '/TEMP/' + data.pdf + '.pdf';
          }

          printJS({ 
            printable: url,
            type: 'pdf'
          });

          /*var url = '../../TEMP/' + data.pdf + '.pdf';
          
          window.open(url, '_blank');*/
        }
      })
  }

  function imprimir_etiquetas_prueba()
  {  
     var num_ped = $('#txt_codigo').val();
     if(num_ped.trim()==''){
      Swal.fire('Seleccione codigo de Ingreso', '', 'warning');
      return;
     }
      $('#myModal_espera').modal('show');
      $.ajax({
        type: "POST",
        url: '../controlador/inventario/alimentos_recibidosC.php?imprimir_etiquetas_prueba=true',
        data: {num_ped}, 
        dataType:'json',
        success: function(data)
        {
          $('#myModal_espera').modal('hide');
          let host = location.pathname;

          let url = "";
          if (host.includes('diskcover')) {
            
            //  let indiceFinal = indiceInicial + subcadena.length - 1;
              url = '/'+host.split('/')[1]+'/TEMP/' + data.pdf + '.pdf';
          } else {
              url = '/TEMP/' + data.pdf + '.pdf';
          }
          //var url = "http://localhost/diskcoversystem/TEMP/ETIQUETAPRUEBA_65001_GMERK231213004.pdf";
          /*var url = '../../TEMP/' + data.pdf + '.pdf';*/

          printJS({ 
            printable: url,
            type: 'pdf'
          });
          //console.log(data)
          //window.open(url, '_blank');
        }
      })
  }
	 

  function escanear_qr(){
		$('#modal_qr_escaner').modal('show');
		navigator.mediaDevices
		.getUserMedia({ video: { facingMode: "environment" } })
		.then(function (stream) {
      $('#qrescaner_carga').hide();
			scanning = true;
			//document.getElementById("btn-scan-qr").hidden = true;
			canvasElement.hidden = false;
			video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
			video.srcObject = stream;
			video.play();
			tick();
      		scan();
		});
	}

	//funciones para levantar las funiones de encendido de la camara
	function tick() {
		canvasElement.height = video.videoHeight;
		canvasElement.width = video.videoWidth;
		//canvasElement.width = canvasElement.height + (video.videoWidth - video.videoHeight);
		canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

		scanning && requestAnimationFrame(tick);
	}

	function scan() {
		try {
			qrcode.decode();
		} catch (e) {
			setTimeout(scan, 300);
		}
	}

	const cerrarCamara = () => {
		video.srcObject.getTracks().forEach((track) => {
			track.stop();
		});
		canvasElement.hidden = true;
    $('#qrescaner_carga').show();
		$('#modal_qr_escaner').modal('hide');
	};

	//callback cuando termina de leer el codigo QR
	qrcode.callback = (respuesta) => {
		if (respuesta) {
			//console.log(respuesta);
			//Swal.fire(respuesta)
			pedidosPorQR(respuesta);
			//activarSonido();
			//encenderCamara();    
			cerrarCamara();    
		}
	};