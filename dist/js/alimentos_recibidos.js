var tbl_pedidos_all ;
var tbl_procesados_all;
$(document).ready(function () {
    
    preguntas_transporte();
    
     $(document).on('focus', '.sele+ct2-selection.select2-selection--single', function (e) {
    $(this).closest(".select2-container").siblings('select:enabled').select2('open');
  });
     window.addEventListener("message", function(event) {
        if (event.data === "closeModal") {
            autocoplet_ingreso();
        }
    });    
    autocoplet_alimento();
    autocoplet_ingreso();
    autocoplet_ingreso_donante();
    autocoplet_ingreso2();

     $('#txt_cantidad2').keydown( function(e) { 
        var keyCode1 = e.keyCode || e.which; 
        if (keyCode1 == 13) { 
             cambiar_cantidad();
             $('#txt_cant').focus();
        }
    });


     $('#txt_temperatura2').keydown( function(e) { 
        var keyCode1 = e.keyCode || e.which; 
        if (keyCode1 == 13) { 
            cambiar_temperatura();
             $('#txt_temperatura').focus();
        }
    });


    $('#modal_cantidad').on('shown.bs.modal', function () {
          $('#txt_cantidad_add').focus();
      })  

      $('#modal_temperatura').on('shown.bs.modal', function () {
          $('#txt_temperatura2').focus();
      })  
      $('#modal_proveedor').on('shown.bs.modal', function () {
          $('#ddl_ingreso').focus();
      })  


      tbl_pedidos_all = $('#tbl_body').DataTable({
          // responsive: true,
          language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
          },
          ajax: {
              url:   '../controlador/inventario/alimentos_recibidosC.php?cargar_datos=true',
              type: 'POST',  // Cambia el método a POST    
              data: function(d) {
                  var parametros = {                    
                    fecha:$('#txt_fecha_b').val(),
                    fechah:$('#txt_fecha_bh').val(),
                    query:$('#txt_query').val(),
                  };
                  return { parametros: parametros };
              },
              dataSrc: '',             
          },
           scrollX: true,  // Habilitar desplazamiento horizontal
   
          columns: [
              { data: null, // Columna autoincremental
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // meta.row es el índice de la fila
                    }
              },
              { data: 'Envio_No' },
              { data: 'Fecha_P.date',  
                  render: function(data, type, item) {
                      return data ? new Date(data).toLocaleDateString() : '';
                  }
              },
              { data:  null,
                render: function(data, type, item) {
                    return `${data.Cliente}<br>${data.notificaciones}`;                    
                  }

              },
              { data: 'Proceso' },
              { data: 'TOTAL' },
              { data: 'Porc_C' },
              { data: null,
                 render: function(data, type, item) {
                    return `
                    <button type="button" title="Imprimir Etiqueta" class="btn-sm btn-warning btn" onclick="imprimir_pedido_pdf('${data.Envio_No}','${data.CodigoP}')"><i class="bx bx-printer m-0"></i></button>
                    <button type="button" title="Editar Pedido" class="btn-sm btn-primary btn" onclick="editar_pedido('${data.ID}')"><i class="bx bx-pencil m-0"></i></button>
                    <button type="button" title="Eliminar Pedido" class="btn-sm btn-danger btn" onclick="eliminar_pedido('${data.ID}')"><i class="bx bx-trash m-0"></i></button>`;                    
                  }
              },
              
          ],
          order: [
              [1, 'asc']
          ]
      });


       tbl_procesados_all = $('#tbl_body_procesados').DataTable({
          // responsive: true,
          language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
          },
          ajax: {
              url:   '../controlador/inventario/alimentos_recibidosC.php?cargar_datos_procesados=true',
              type: 'POST',  // Cambia el método a POST    
              data: function(d) {
                  var parametros = {                    
                    fecha:$('#txt_fecha_b').val(),
                    fechah:$('#txt_fecha_bh').val(),
                    query:$('#txt_query').val(),
                  };
                  return { parametros: parametros };
              },
              dataSrc: '',             
          },
           scrollX: true,  // Habilitar desplazamiento horizontal
   
          columns: [
              { data: null, // Columna autoincremental
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // meta.row es el índice de la fila
                    }
              },
              { data: 'Envio_No' },
              { data: 'Fecha_P.date',  
                  render: function(data, type, item) {
                      return data ? new Date(data).toLocaleDateString() : '';
                  }
              },
              { data:  null,
                render: function(data, type, item) {
                    return `${data.Cliente}<br>${data.notificaciones}`;                    
                  }

              },
              { data: 'Proceso' },
              { data: 'TOTAL' },
              { data: 'Porc_C' },
              { data: 'proceso' },
              { data: null,
                 render: function(data, type, item) {
                    return `
                     <button type="button" title="Imprimir Etiqueta" class="btn-sm btn-warning btn" onclick="imprimir_pedido_pdf('${data.Envio_No}','${data.CodigoP}')"><i class="bx bx-printer m-0"></i></button>
                    <button type="button" title="Editar Pedido" class="btn-sm btn-primary btn" onclick="editar_pedido('${data.ID}')"><i class="bx bx-pencil m-0"></i></button>
                    <button type="button" title="Eliminar Pedido" class="btn-sm btn-danger btn" onclick="eliminar_pedido('${data.ID}')"><i class="bx bx-trash m-0"></i></button>`;                    
                  }
              },
              
          ],
          order: [
              [1, 'asc']
          ]
      });
  

  

     setInterval(function() {
       cargar_datos();
        }, 5000); 
})


function validar_trasporte_lleno()
{

    var todos = 1;
    $('.rbl_opciones').each(function() {
              const checkbox = $(this);
              var isChecked = $('input[name="' + checkbox[0]['name'] + '"]').is(':checked');
              if (!isChecked) {
                  todos = 0;
              }
          });
     if(todos==0)
        {
            Swal.fire('Estado de trasporte incompleto','','info');
            return false;
        }else
        {
            $('#modal_estado_transporte').modal('hide');
        }

}


function guardar()
{
    var donante = $('#txt_donante').val();
    var tempe = $('#txt_temperatura').val();
    var tipo = $('#ddl_tipo_alimento').val();
    var can = $('#txt_cant').val();
    var cod = $('#txt_codigo').val();
    if(donante=='' || tempe=='' || tipo=='' || can =='' || cod=='' || can ==0)
    {
        Swal.fire('Ingrese todos los datos','','info')
        return false;
    }

    var gaveta_ingreso = 0;
     const textInputs = document.querySelectorAll('#form_gavetas input[type="text"]');
    textInputs.forEach(input => {
        if(input.value!='' && input.value!=0)
        {
             gaveta_ingreso = 1;
        }
    });


    var gave = $('input[name="rbx_gaveta"]:checked').val();  

    if(gaveta_ingreso==0 && gave =='SI')
    {
        Swal.fire("","Ingrese numero de gavetas","info");
        return false;
    }

    console.log(gaveta_ingreso);
    console.log($('#rbx_gaveta').val());



     var parametros = $('#form_correos').serialize();
     var estado_trans = $('#form_estado_transporte').serialize();
     var estado_gavetas = $('#form_gavetas').serialize();
     var todos = 1;
     $('.rbl_opciones').each(function() {
              const checkbox = $(this);
              var isChecked = $('input[name="' + checkbox[0]['name'] + '"]').is(':checked');
              if (!isChecked) {
                  todos = 0;
              }
          });
     if(todos==0  && $('#rbx_trasporte').val()=='SI')
        {
            Swal.fire('Estado de trasporte No ingresada o incompleto','','info');
            return false;
        }

     // parametros+='&'+estado_trans;
     parametros+='&ddl_ingreso='+$('#txt_donante').val();
      $.ajax({
        type: "POST",
        url: '../controlador/inventario/alimentos_recibidosC.php?guardar=true',
        data:{parametros:parametros,transporte:estado_trans,gavetas:estado_gavetas},
        dataType:'json',
        success: function(data)
        {
            if(data==1)
            {
                Swal.fire('Alimento Recibido Guardado','','success').then(function()
                    {
                        limpiar();
                        cargar_datos();
                        preguntas_transporte();
                    });
            }
        
        }
    });
}


function limpiar()
{
    $('#txt_donante').val('');
    $('#txt_tipo').val('');
    $('#txt_temperatura').val('');
    $('#ddl_alimento_text').val('');
    $('#txt_cant').val('');
    $('#txt_codigo').val('');
    $('#txt_ci').val('');
    $('#txt_comentario').val('');
    $('#ddl_tipo_alimento').val('');
    $('#txt_donante').val(null).trigger('change');

    $('#ddl_tipo_alimento').prop('disabled',false);
    $('#txt_donante').prop('disabled',false);

    // modales
    $('#ddl_ingreso').val(null).trigger('change');
    $('#txt_temperatura2').val('');
    $('#txt_cantidad2').val('');
}

function autocoplet_alimento()
{
     // var parametros = $('#form_correos').serialize();
      $.ajax({
      type: "POST",
     url:   '../controlador/inventario/alimentos_recibidosC.php?alimentos=true',
      // data:{parametros:parametros},
      dataType:'json',
      success: function(data)
      {
          // console.log(data);
          option = '';
          opt = '<option value="">Tipo de donacion</option>';
          data.forEach(function(item,i){
              // console.log(item);
              option+= '<div class="col-md-6 col-sm-6">'+
                                          '<button type="button" class="btn btn-light border border-1 btn-sm"><img src="../../img/png/'+item.picture+'.png" onclick="cambiar_tipo_alimento(\''+item.id+'\',\''+item.text+'\')"  style="width: 60px;height: 60px;"></button><br>'+
                                          '<b>'+item.text+'</b>'+
                                      '</div>';
              // option+='<option value="'+item.Codigo+'">'+item.Cliente+'</option>';
                  opt+='<option value="'+item.id+'">'+item.text+'</option>';
          })	 
          $('#pnl_tipo_alimento').html(option);   
          $('#ddl_tipo_alimento').html(opt);     
          $('#ddl_tipo_alimento_edi').html(opt);     
      }
  });
}

//  function autocoplet_alimento(){
//   $('#ddl_alimento').select2({
//     placeholder: 'Seleccione una beneficiario',
//     // width:'90%',
//     ajax: {
//       url:   '../controlador/inventario/alimentos_recibidosC.php?alimentos=true',
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

// function autocoplet_ingreso()
//   {
//   	 // var parametros = $('#form_correos').serialize();
//   	  $.ajax({
// 	    type: "POST",
//       	url:   '../controlador/inventario/alimentos_recibidosC.php?detalle_ingreso=true',
// 	    // data:{parametros:parametros},
//         dataType:'json',
// 	    success: function(data)
// 	    {
// 	    	console.log(data);
// 	    	option = '';
// 	    	data.forEach(function(item,i){
// 	    		console.log(item);
// 	    		option+='<option value="'+item.Codigo+'">'+item.Cliente+'</option>';
// 	    	})	 
// 	    	$('#ddl_ingreso').html(option);     
// 	    }
// 	});
//   }

function autocoplet_ingreso(){
$('#ddl_ingreso').select2({
  placeholder: 'Seleccione',
  width:'100%',
  ajax: {
   url:   '../controlador/inventario/alimentos_recibidosC.php?detalle_ingreso2=true',
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

function autocoplet_ingreso2(){
$('#ddl_ingreso_edi').select2({
  placeholder: 'Seleccione',
  width:'100%',
  ajax: {
   url:   '../controlador/inventario/alimentos_recibidosC.php?detalle_ingreso2=true',
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
function autocoplet_ingreso_donante(){
$('#txt_donante').select2({
  placeholder: 'Seleccione',
  width:'100%',
  ajax: {
   url:   '../controlador/inventario/alimentos_recibidosC.php?detalle_ingreso2=true',
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

function nuevo_proveedor()
{  	   
     $('#myModal_provedor').modal('show');
     $('#FProveedor').contents().find('body').css('background-color', 'antiquewhite');

}
function option_select()
{
        var id = $('#ddl_ingreso').val();
        if(id==null || id=='')
        {
            return false;
        }
        $.ajax({
          type: "POST",
            url:   '../controlador/inventario/alimentos_recibidosC.php?datos_ingreso=true',
          data:{id:id},
          dataType:'json',
          success: function(data)
          {

            $('#txt_donante').append($('<option>',{value: data.Codigo, text:data.Cliente,selected: true }));
            $('#txt_donante').prop('disabled',true);
              // console.log(data);
              $('#txt_codigo').val(data.Cod_Ejec)
              $('#txt_ci').val(data.CI_RUC)
              // $('#txt_donante').val(data.Cliente)
              $('#txt_tipo').val(data.Actividad)
              $('#txt_tipo').focus();
              $('#modal_proveedor').modal('hide');
              generar_codigo();
          }
      });  	
}

function option_select2()
{
        var id = $('#txt_donante').val();
        if(id==null || id=='')
        {
            return false;
        }
        $.ajax({
          type: "POST",
            url:   '../controlador/inventario/alimentos_recibidosC.php?datos_ingreso=true',
          data:{id:id},
          dataType:'json',
          success: function(data)
          {

            $('#txt_donante').append($('<option>',{value: data.Codigo, text:data.Cliente,selected: true }));
            $('#txt_donante').prop('disabled',true);
              // console.log(data);
              $('#txt_codigo').val(data.Cod_Ejec)
              $('#txt_ci').val(data.CI_RUC)
              // $('#txt_donante').val(data.Cliente)
              $('#txt_tipo').val(data.Actividad)
              $('#modal_proveedor').modal('hide');
              generar_codigo();
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
              console.log(data);
              var cod = $('#txt_codigo').val();
              $('#txt_codigo').val(cod+'-'+data)
              
          }
      });  	
}

function cargar_datos2(){
        parametros = 
        {
            'fecha':$('#txt_fecha_b').val(),
            'fechah':$('#txt_fecha_bh').val(),
            'query':$('#txt_query').val(),
        }
        $.ajax({
          type: "POST",
            url:   '../controlador/inventario/alimentos_recibidosC.php?cargar_datos=true',
          data:{parametros:parametros},
          dataType:'json',
          success: function(data)
          {
              $('#tbl_body').html(data);
              // console.log(data);
              // var cod = $('#txt_codigo').val();
              // $('#txt_codigo').val(cod+'-'+data)
              
          }
      });  	
}

function cargar_datos()
{
     tbl_pedidos_all.ajax.reload(null, false);
}



 function cargar_datos_procesados2(){
        parametros = 
        {
            'fecha':$('#txt_fecha_b').val(),
            'fechah':$('#txt_fecha_bh').val(),
            'query':$('#txt_query').val(),
        }
        $.ajax({
          type: "POST",
            url:   '../controlador/inventario/alimentos_recibidosC.php?cargar_datos_procesados=true',
          data:{parametros:parametros},
          dataType:'json',
          success: function(data)
          {
              $('#tbl_body_procesados').html(data);
              // console.log(data);
              // var cod = $('#txt_codigo').val();
              // $('#txt_codigo').val(cod+'-'+data)
              
          }
      });  	
}

 function cargar_datos_procesados(){
       tbl_procesados_all.ajax.reload(null, false);
}

function preguntas_transporte(){
        
        $.ajax({
          type: "POST",
            url:   '../controlador/inventario/alimentos_recibidosC.php?preguntas_transporte=true',
          // data:{parametros:parametros},
          dataType:'json',
          success: function(data)
          {
              $('#lista_preguntas').html(data);		    	
          }
      });  	
}

function show_proveedor()
{
    $('#modal_proveedor').modal('show');
}
function show_cantidad()
{
    $('#modal_cantidad').modal('show');
}
function show_estado_transporte()
{
    // preguntas_transporte();
    $('#modal_estado_transporte').modal('show');
}

function show_gaveta()
{
        var transporte = $('input[name="rbx_gaveta"]:checked').val();  	
        if(transporte=='SI')
        {
            $('#btn_gavetas').css('display','block');
                $.ajax({
                      type: "POST",
                        url:   '../controlador/inventario/alimentos_recibidosC.php?gavetas=true',
                        // data:{ID:ID},
                      dataType:'json',
                      success: function(data)
                      {
                              $('#tbl_gavetas').html(data);  
                      }
                  });  		

        }else
        {
            $('#btn_gavetas').css('display','none');
        }


        
}
function show_temperatura()
{
    $('#modal_temperatura').modal('show');
}

function show_tipo_donacion()
{
    $('#modal_tipo_donacion').modal('show');
}
 function ocultar_comentario()
{

     var cbx = $('input[type=radio][name=cbx_estado_tran]:checked').val();
     if(cbx=='0')
     {
          $('#pnl_comentario').css('display','block');
     }else
     {
          // $('#pnl_comentario').css('display','none');
     }
     console.log(cbx);
}

function cambiar_cantidad()
{
    total = 0;
    
    $('.input-numero').each(function() {
          var valor = parseFloat($(this).val());
          if (!isNaN(valor)) {
              total += valor;
              console.log(valor);
          }
  });
//	var can = $('#txt_cantidad2').val();
    $('#txt_cantidad2').val(total);
}

function cerrar_modal_cant()
{
    var total = $('#txt_cantidad2').val();
    $('#txt_cant').val(total);
    $('#modal_cantidad').modal('hide');
    $('#txt_cant').focus();
}
function cambiar_temperatura()
{
    var can = $('#txt_temperatura2').val();
    $('#txt_temperatura').val(can);
    $('#modal_temperatura').modal('hide');
    $('#txt_temperatura').focus();
}

function cambiar_tipo_alimento(cod,texto)
{

    $('#ddl_alimento_text').val(texto);
    $('#ddl_alimento').val(cod);
    $('#ddl_tipo_alimento').val(cod)
    $('#ddl_tipo_alimento').prop('disabled',true);
    $('#modal_tipo_donacion').modal('hide');
    $('#btn_cantidad').focus();
}
function eliminar_pedido(ID)
{
     Swal.fire({
     title: 'Esta seguro?',
     text: "Esta usted seguro de que quiere eliminar este registro!",
     type: 'warning',
     showCancelButton: true,
     confirmButtonColor: '#3085d6',
     cancelButtonColor: '#d33',
     confirmButtonText: 'Si!'
   }).then((result) => {
     if (result.value==true) {
      eliminar(ID);
     }
   })

}

function eliminar(ID)
{
    
        $.ajax({
          type: "POST",
            url:   '../controlador/inventario/alimentos_recibidosC.php?eliminar_pedido=true',
            data:{ID:ID},
          dataType:'json',
          success: function(data)
          {
              if(data ==1)
              {
                  Swal.fire('Registro eliminado','','info');
                  cargar_datos();
              }    	
          }
      });  	
}

function limpiar_donante()
{  	
    $('#ddl_ingreso').val(null).trigger('change');
    $('#txt_donante').val(null).trigger('change');
    $('#txt_donante').prop('disabled',false);
}

function tipo_seleccion()
{
     var nom = $('#ddl_tipo_alimento option:selected').text();
     var cod = $('#ddl_tipo_alimento').val();

    $('#ddl_alimento_text').val(nom);
    $('#ddl_alimento').val(cod);

    $('#ddl_tipo_alimento').prop('disabled',true);
}

function limpiar_alimento_rec()
{
    autocoplet_alimento();
    $('#ddl_tipo_alimento').prop('disabled',false);
    $('#ddl_alimento_text').value('');
      $('#ddl_alimento').value('');
}

function editar_pedido(ID)
{

        $.ajax({
          type: "POST",
            url:   '../controlador/inventario/alimentos_recibidosC.php?datos_pedido_edi=true',
            data:{ID:ID},
          dataType:'json',
          success: function(data)
          {
              if(data.length>0)
              {
                  data = data[0];
                  console.log(data);
                  $('#txt_id_edi').val(data.ID);
                      $('#ddl_ingreso_edi').append($('<option>',{value: data.CodigoP, text:data.Cliente,selected: true }));
                      $('#txt_codigo_edi').val(data.Envio_No);
                      $('#ddl_tipo_alimento_edi').val(data.Cod_C);
                      $('#txt_cant_edi').val(data.TOTAL);
                      $('#txt_cant_veri').val(data.ingresados);
                      $('#txt_temperatura_edi').val(data.Porc_C);
                  $('#modal_editar_pedido').modal('show'); 	
              }
          }
      });  	

}

function guardar_edicion()
{
    var motivo_edit = $('#txt_motivo_edit').val();
    if(motivo_edit=='' || motivo_edit=='.')
    {
        Swal.fire("Coloque un motivo de edicion","","info");
        return false;
    }
    datos = $("#form_editar").serialize();
    $.ajax({
          type: "POST",
            url:   '../controlador/inventario/alimentos_recibidosC.php?editar_pedido=true',
            data:datos,
          dataType:'json',
          success: function(data)
          {		    	    	
              if(data==1)
              {
                  Swal.fire('Pedido Editado','','success').then(function(){
                          cargar_datos_procesados();
                          cargar_datos();
                  });
              }
              
              $('#modal_editar_pedido').modal('hide');		
          }
      });  	


}

function validar_cantidad()
{
     can = $('#txt_cant_edi').val();
     ing = $('#txt_cant_veri').val();
     if(parseFloat(can)<parseFloat(ing))
     {
             Swal.fire("No se puede Cambiar la cantidad","Los articulos ingresados son "+ing,"error").then(function(){
                 $('#txt_cant_edi').val(ing);
             })
     }
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

                cargar_datos_procesados();
        notificaciones();
      }
  });   

}

function mas_input()
{
    $('#div_cantidad').append('<input type="text" name="txt_cantidad_add" id="txt_cantidad_add" class="form-control input-numero" placeholder="0" onblur="cambiar_cantidad()" onKeyPress="return soloNumerosDecimales(event)">');

}

function mostraTransporte()
{
    var transporte = $('input[name="rbx_trasporte"]:checked').val();  	
    if(transporte=='SI')
    {
        $('#btn_transporte').css('display','block');
    }else
    {
        $('#btn_transporte').css('display','none');
    }

}

function imprimir_pedido()
{  
  var id = $('#txt_donante').val();
   var codigo = $('#txt_codigo').val();

   if(id==null || id=='')
  {
      Swal.fire('Seleccione un detalle de ingreso', '', 'error');
      return;
  }
   if(codigo==null || codigo=='')
  {
      Swal.fire('No se ha generado un codigo de ingreso', '', 'error');
      return;
  }

    $('#myModal_espera').modal('show');
    $.ajax({
      type: "POST",
      url: '../controlador/inventario/alimentos_recibidosC.php?imprimir_pedido=true',
      data: {id, codigo}, 
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

function imprimir_pedido_pdf(codigo,id)
{  

//     if(id==null || id=='')
//       {
//           Swal.fire('Seleccione un detalle de ingreso', '', 'error');
//           return;
//       }
//        if(codigo==null || codigo=='')
//       {
//           Swal.fire('No se ha generado un codigo de ingreso', '', 'error');
//           return;
//       }

      var url = '../controlador/inventario/alimentos_recibidosC.php?imprimir_pedido=true&codigo='+codigo+'&id='+id;
       window.open(url, '_blank');

}