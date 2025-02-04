
	var canvasElement;
	var canvas;
	var scanning = false;
  var tbl_pedidos_all;
  $(document).ready(function () {
    // video = document.createElement("video");
    // canvasElement = document.getElementById("qr-canvas");
    // canvas = canvasElement.getContext("2d", { willReadFrequently: true });
    // notificaciones();
    cargar_paquetes();
    
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


     tbl_pedidos_all = $('#tbl_body').DataTable({
          // responsive: true,
          language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
          },
          ajax: {
               url:   '../controlador/inventario/alimentos_recibidosC.php?pedido=true',
              type: 'POST',  // Cambia el método a POST    
              data: function(d) {
                  var parametros = {                    
                    num_ped:$('#txt_codigo').val(),
                  };
                  return { parametros: parametros };
              },              
              dataSrc: function(json) {
              
                var diff = parseFloat(json.cant_total)-parseFloat(json.reciclaje);
                if(diff < 0)
                {
                  diff = diff*(-1);
                }
                $('#txt_primera_vez').val(json.primera_vez);

                var ingresados_en_pedidos =  $('#txt_cant_total_pedido').val();
                var ingresados_en_kardex =  $('#txt_cant_total').val(diff);
                var total_pedido = $('#txt_cant').val();
                var faltantes = parseFloat(total_pedido)-parseFloat(json.cant_total);


                $('#txt_faltante').val(faltantes.toFixed(2));

                // console.log(json);

                // Devolver solo la parte de la tabla para DataTables
                return json.tabla;
            }        
          },
           scrollX: true,  // Habilitar desplazamiento horizontal
   
          columns: [
              { data: null, // Columna autoincremental
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // meta.row es el índice de la fila
                    }
              },
               { data: 'Fecha_Fab.date',  
                  render: function(data, type, item) {
                      return data ? new Date(data).toLocaleDateString() : '';
                  }
              },
              { data: 'Fecha_Exp.date',  
                  render: function(data, type, item) {
                      return data ? new Date(data).toLocaleDateString() : '';
                  }
              },
              { data: 'Producto' },
              { data: 'Entrada' },
              { data: 'Nombre_Completo' },
              { data: 'Codigo_Barra'},
              { data: 'sucursal' },
              { data: null,
                 render: function(data, type, row) {
                    return `<div id="qr_${data.Codigo_Barra}"></div>
                            <script>
                                setTimeout(() => {
                                    new QRCode(document.getElementById('qr_${data.Codigo_Barra}'), {
                                        text: '${data.Codigo_Barra}',
                                        width: 70,
                                        height: 70
                                    });
                                }, 100);
                            </script>`;
                }
              },
              { data: null,
                 render: function(data, type, item) {
                      var botones = `<button class="btn btn-sm btn-danger" title="Eliminar linea"  onclick="eliminar_lin('${data.ID}','${data.TDP}')" ><i class="bx bx-trash m-0"></i></button>`;                      
                    
                    if(data.TDP !='.'){
                      botones+=`<button class="btn btn-sm btn-primary" title="Agregar a ${data.Producto}"  onclick=" show_producto2('${data.ID}')" ><i class=" bx bx-list-ol m-0"></i></button>`;  
                    }

                    return botones;         
                  }
              },
              
          ]
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
        var op = '<option value="">Empaques</option>';
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

    var  url = '../controlador/inventario/alimentos_recibidosC.php?imprimir_etiquetas=true&num_ped='+num_ped;
    window.open(url, '_blank');


      // $('#myModal_espera').modal('show');
      // $.ajax({
      //   type: "POST",
      //   url: '../controlador/inventario/alimentos_recibidosC.php?imprimir_etiquetas=true',
      //   data: {num_ped}, 
      //   dataType:'json',
      //   success: function(data)
      //   {
      //     $('#myModal_espera').modal('hide');
      //     /*let host = location.pathname;

      //     let url = "";
      //     if (host.includes('diskcover')) {
            
      //       //  let indiceFinal = indiceInicial + subcadena.length - 1;
      //         url = '/'+host.split('/')[1]+'/TEMP/' + data.pdf + '.pdf';
      //     } else {
      //         url = '/TEMP/' + data.pdf + '.pdf';
      //     }

      //     printJS({ 
      //       printable: url,
      //       type: 'pdf'
      //     });*/

      //     var url = '../../TEMP/' + data.pdf + '.pdf';
          
      //     window.open(url, '_blank');
      //   }
      // })
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
                    pedidosPorQR(decodedText);
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


  function cargar_pedido()
  {

     tbl_pedidos_all.ajax.reload(null, false);
  }


  function cargar_pedido2()
  {
    var parametros=
    {
      'num_ped':$('#txt_codigo').val(),
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/alimentos_recibidosC.php?pedido_trans=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) {
        console.log(response);
        $('#tbl_body_pedido').html(response.tabla);
        $('#txt_cant_total_pedido').val(response.cant_total);   
        $('#txt_total_lin_pedido').val(response.num_lin);       
      }
    });
  }

 function calculos()
   {
     let cant = parseFloat($('#txt_canti').val());
     let pre = parseFloat($('#txt_precio').val());
     let des = 0; //parseFloat($('#txt_descto').val());
     if($('#rbl_si').prop('checked'))
     {
       let subtotal = pre*cant;
       let dscto = (subtotal*des)/100;
       let total = (subtotal-dscto)*1.12;

       let iva = parseFloat($('#txt_iva').val()); 
       $('#txt_subtotal').val(subtotal-dscto);
       $('#txt_total').val(total);
       $('#txt_iva').val(total-(subtotal-dscto));

     }else
     {
      $('#txt_iva').val(0);
       let iva = parseFloat($('#txt_iva').val());       
       let sub = (pre*cant);
       let dscto = (sub*des)/100;

       let total = (sub-dscto);
       $('#txt_subtotal').val(sub-dscto);
       $('#txt_total').val(total);
     }
   }

   function limpiar_nuevo_producto()
   {
     $('#ddl_cta_inv').empty();
     $('#ddl_cta_CV').empty();
     $('#ddl_cta_venta').empty();
     $('#ddl_cta_ventas_0').empty();
     $('#ddl_cta_vnt_anti').empty();
     $('#ddl_familia_modal').empty();
     $('#txt_ref').val('');
     $('#txt_nombre').val('');
     $('#txt_max').val('');
     $('#txt_min').val('');
     $('#txt_reg_sanitario').val('');
     $('#txt_cod_barras').val('');
   }

  function agregar()
  {
    var reci = $('#txt_TipoSubMod').val();
    
    var parametros = $("#form_add_producto").serialize();    
    var parametros2 = $("#form_correos").serialize();
       $.ajax({
         data:  parametros2+'&txt_referencia='+$('#txt_referencia').val()+'&txt_referencia2='+$('#txt_referencia2').val(),
         url:   '../controlador/inventario/alimentos_recibidosC.php?guardar_recibido=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 

            // console.log(response);
           if(response.resp==1)
           {
            $('#txt_pedido').val(response.ped);
              Swal.fire({
                icon:'success',
                title: 'Agregado a pedido',
                text :'',
              }).then( function() {                 
                   cargar_pedido(); 
                   $('#txt_paquetes').val('');
                   $('#ddl_sucursales').val('');                
              });

            // Swal.fire('','Agregado a pedido.','success');
            limpiar();
            // location.reload();
           }else
           {
            Swal.fire('','Algo extraño a pasado.','error');
           }           
         }
       });    

    
  }


  function limpiar()
  {
     var tpd = $('#txt_TipoSubMod').val();
      $("#txt_cantidad").val('');
      $("#txt_unidad").val('');
      $("#txt_grupo").val('');
      $("#txt_fecha_exp").val('');


     if(tpd=='R')
     {

      $("#txt_referencia2").val('');
      $("#txt_producto2").val(null).trigger('change');
      $("#ddl_producto2").val(null).trigger('change');
      $("#txt_producto2").prop('disabled',false);

      $("#txt_producto").prop('disabled',false);      
      $("#txt_producto").val(null).trigger('change');

     }else{
      $("#txt_producto").val('');
    
      $("#txt_cantidad2").val('');
      $("#txt_referencia").val('');
      $("#ddl_producto").val(null).trigger('change');
      $("#txt_producto").prop('disabled',false);      
      $("#txt_producto").val(null).trigger('change');
      
    }
  }


  function autocoplet_pro(){
    $('#ddl_producto').select2({
      placeholder: 'Seleccione una producto',
      ajax: {
        url:   '../controlador/inventario/alimentos_recibidosC.php?autocom_pro=true',
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

   function autocoplet_producto(){
    $('#txt_producto').select2({
      placeholder: 'Seleccione una producto',
      ajax: {
        url:   '../controlador/inventario/alimentos_recibidosC.php?autocom_pro=true',
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

  function autocoplet_pro2(){
    $('#ddl_producto2').select2({
      placeholder: 'Seleccione una producto',
      dropdownParent: $('#modal_producto_2'),
      ajax: {
        url:   '../controlador/inventario/alimentos_recibidosC.php?autocom_pro2=true',
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

function eliminar_lin(num,tipo)
{
  pedido = $('#txt_codigo').val();
  // console.log(cli);

    if(tipo=='R')
    {
      Swal.fire({
        title: 'Quiere eliminar este registro?',
        text: "Al eliminar este registro se borrara tambien los productos ligados a este item!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si'
      }).then((result) => {
          if (result.value) {
            eliminar_all_pedido(pedido);
            eliminar_linea_trans(num,'1');
            $('#txt_TipoSubMod').val('.');    
            $('#btn_cantidad').prop('disabled',false);    
            limpiar();
            limpiar_reciclaje();         
          }
        });
    }else{
    eliminar_linea_trans(num);
  }
}

function eliminar_linea_trans(num,tpd=0)
{
   var parametros=
    {
      'lin':num,
      'TPD':tpd,
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/alimentos_recibidosC.php?lin_eli=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response==1)
        {
          cargar_pedido();
          cargar_pedido2();
        }
      }
    });
}


function eliminar_all_pedido(pedido)
{
    var parametros=
      {
        'pedido':pedido,
      }
       $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/inventario/alimentos_recibidosC.php?eli_all_pedido=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
          if(response==1)
          {
            cargar_pedido2();
          }
        }
      });

}

  function eliminar_lin_pedido(num)
  {
    var ruc = $('#txt_ruc').val();
    var cli = $('#ddl_paciente').text();
    // console.log(cli);
    Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Esta seguro de eliminar este registro!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
            var parametros=
            {
              'lin':num,
            }
             $.ajax({
              data:  {parametros:parametros},
              url:   '../controlador/inventario/alimentos_recibidosC.php?lin_eli_pedido=true',
              type:  'post',
              dataType: 'json',
              success:  function (response) { 
                if(response==1)
                {
                  cargar_pedido2();
                }
              }
            });
        }
      });
  }

  function terminar_pedido()
  {
    pro = $('#txt_producto option:selected').text();
    var id = $('#txt_id_linea_pedido').val(); 
    
    if($('#txt_cant_total_pedido').val()==0 || $('#txt_cant_total_pedido').val()=='')
    {
      Swal.fire('Agrege productos para terminar','','info')
      return false;
    }

    primera_vez = $('#txt_primera_vez').val();

    if(primera_vez==0 || primera_vez==''){
      $('#txt_cantidad').val($('#txt_cant_total_pedido').val());
      // $('#btn_cantidad').prop('disabled',true);
      if($('#txt_cantidad').val()==0 || $('#txt_cantidad').val()=='')
      {
        Swal.fire('No se olvide de agregar: '+pro,'','info')
        return false;
      }
      show_panel();
      limpiar();

         $('#modal_producto_2').modal('hide');
    }else{

         $('#modal_producto_2').modal('hide');
          Swal.fire('Cantidad Modificada automaticamente','','success');

     total = $('#txt_cant_total_pedido').val();
    
      var parametros=
        {
          'txt_codigo':$('#txt_codigo').val(),
          'total_cantidad':$('#txt_cant_total_pedido').val(),
          'id':id,
          'producto': $('#txt_producto option:selected').text(),
        }
         $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/inventario/alimentos_recibidosC.php?actualizar_trans_kardex=true',
          type:  'post',
          dataType: 'json',
          success:  function (response) { 
            if(response==1)
            {
              cargar_pedido();
              cargar_pedido2();
            }
          }
        });

          limpiar();
    }






  }