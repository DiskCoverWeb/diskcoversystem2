var video;
var canvasElement;
var canvas;
var scanning = false;
$(document).ready(function () {
video = document.createElement("video");
canvasElement = document.getElementById("qr-canvas");
canvas = canvasElement.getContext("2d", { willReadFrequently: true });

   window.addEventListener("message", function(event) {
        if (event.data === "closeModal") {
            autocoplet_ingreso();
        }
    });    
  autocoplet_alimento();
  autocoplet_ingreso();
  pedidos();

  notificaciones();





  $('#txt_codigo').on('select2:select', function (e) {
          var data = e.params.data.data;

          setearCamposPedidos(data);

       });


})

function setearCamposPedidos(data){
console.log(data);
          $('#txt_id').val(data.ID); // display the selected text
          $('#txt_fecha').val(formatoDate(data.Fecha_P.date)); // display the selected text
          $('#txt_ci').val(data.CI_RUC); // save selected id to input
          $('#txt_donante').val(data.Cliente); // save selected id to input
          $('#txt_tipo').val(data.Actividad); // save selected id to input
          $('#txt_cant').val(data.TOTAL); // save selected id to input
          $('#txt_comentario').val(data.Mensaje); // save selected id to input
          $('#txt_comentario_clas').val(data.Llamadas); // save selected id to input
          $('#txt_ejec').val(data.Cod_Ejec); // save selected id to input

          $('#txt_contra_cta').val(data.Cta_Haber); // save selected id to input
          $('#txt_cta_inv').val(data.Cta_Debe); // save selected id to input

          $('#txt_codigo_p').val(data.CodigoP)
          $('#txt_responsable').val(data.Responsable)

          $('#btn_estado_trasporte').css('display','block');
          $('#btn_estado_gavetas').css('display','block');
          if(data.Cod_R=='0')
          {
              $('#img_estado').attr('src','../../img/png/bloqueo.png');
          }else
          {

              $('#img_estado').attr('src','../../img/png/aprobar.png');
          }
          $('#txt_temperatura').val(data.Porc_C); // save selected id to input
          $('#ddl_alimento').append($('<option>',{value: data.Cod_C, text:data.Proceso,selected: true }));
          if(data.Proceso.toUpperCase() =='COMPRAS' || data.Proceso.toUpperCase() =='COMPRA')
        {
            $('#pnl_factura').css('display','block');
        }else
        {

            $('#pnl_factura').css('display','none');
        }

      // setInterval(function() {         	
                // cargar_pedido();
      // }, 5000); 
}

function pedidos(){
$('#txt_codigo').select2({
placeholder: 'Seleccione una beneficiario',
// width:'90%',
ajax: {
  url:   '../controlador/inventario/alimentos_recibidosC.php?pedidos_proce=true',          
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
        url:   '../controlador/inventario/alimentos_recibidosC.php?pedidos_proce=true&q='+codigo,          
        method: 'GET',
        dataType: 'json',
        success: (data) => {
            console.log(data);
            let datos = data[0];
            if(data.length > 0){
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


function guardar()
{
  var todos = 1;
   $('.rbl_conta').each(function() {
    const checkbox = $(this);
    const isChecked = checkbox.prop('checked'); 
    if (!isChecked) {
        todos = 0;
    }
});

  if(todos==0)
  {
      Swal.fire('Asegurese de que todos los productos esten seleccionados','','info');
      return false;
  }else
  {
      contabilizar();
  }
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
        console.log(data);
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
            console.log(data);
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
            console.log(data);
            var cod = $('#txt_codigo').val();
            $('#txt_codigo').val(cod+'-'+data)
            
        }
    });  	
}


function show_calendar()
{
  $('#modal_calendar').modal('show');
}
function show_producto()
{
  $('#modal_producto').modal('show');
}
function show_cantidad()
{
  $('#modal_cantidad').modal('show');
}

function cambiar_cantidad()
{
  var can = $('#txt_cantidad2').val();
  $('#txt_cantidad').val(can);
  $('#modal_cantidad').modal('hide');
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
   console.log(cbx);
}

function recalcular(id)
{
  var cant =  $('#txt_cant_ped_'+id).text();
  var pvp =  $('#txt_pvp_linea_'+id).val();
  var total = parseFloat(cant)* parseFloat(pvp);
  $('#txt_total_linea_'+id).val(total.toFixed(4));
  console.log(total);
}

function editar_precio(id)
{
      parametros = 
      {
          'id':id,
          'pvp':$('#txt_pvp_linea_'+id).val(),
          'total':$('#txt_total_linea_'+id).val(),
      }
      $.ajax({
        type: "POST",
          url:   '../controlador/inventario/alimentos_recibidosC.php?editar_precio=true',
        data:{parametros:parametros},
        dataType:'json',
        success: function(data)
        {
            cargar_pedido();
            
        }
    });  	

}

function guardar_check()
{
  var checked = '';
  var no_checked = '';
   $('.rbl_conta').each(function() {
        const checkbox = $(this);
        const isChecked = checkbox.prop('checked'); 
        if (isChecked) {
           checked+=checkbox[0].defaultValue+',';
        }else
        {
            no_checked+=checkbox[0].defaultValue+',';
        }
        });
   // if(checked=='')
   // {
   // 	Swal.fire('No se ha seleccionado ningun item','','info');
   // 	return false;
   // }

   parametros = 
      {
          'check':checked,
          'no_check':no_checked,
      }
      $.ajax({
        type: "POST",
          url:   '../controlador/inventario/alimentos_recibidosC.php?editar_checked=true',
        data:{parametros:parametros},
        dataType:'json',
        success: function(data)
        {
            // if(data==1)
            // {
            // 	Swal.fire('Items Seleccionados Guardados','','success');
            // }
            
        }
    });  	

   // console.log(checked)

}

function cargar_tras_pedidos(nombre,pedido)
{
  $('#lbl_titulo').text(nombre)
   var parametros=
{
  'num_ped':pedido,
}
 $.ajax({
  data:  {parametros:parametros},
  url:   '../controlador/inventario/alimentos_recibidosC.php?pedido_trans_datos=true',
  type:  'post',
  dataType: 'json',
  success:  function (response) {
    console.log(response);
    var lista = '';
    response.forEach(function(item,i){
        lista+='<li style="font-size: large;"><a href="#" style="padding-right:0px"><label>'+item.Producto+'</label><span class="label label-danger pull-right">'+item.Cantidad+' - '+item.UNIDAD+'</span></a></li>';
    })
    $('#lista_pedido').html(lista);    
  }
});

  $('#myModal_trans_pedido').modal('show');

}

function abrir_modal_notificar(codigoU)
{
  $('#txt_codigo_usu').val(codigoU);
  $('#myModal_notificar_usuario').modal('show');
}

function guardar_comentario_check()
{
var codigo = $('#txt_codigo').val();
console.log(codigo);
if(codigo=='')
{
   Swal.fire("Seleccione un pedido","","info");
   return false;
}

if($('#txt_comentario2').val()=='')
{
     Swal.fire("Escriba un mensaje","","info");
   return false;
}

var parametros = {
    'notificar':$('#txt_comentario2').val(),
    'id':$('#txt_id').val(),
}
 $.ajax({
  data:  {parametros,parametros},
  url:   '../controlador/inventario/alimentos_recibidosC.php?guardar_comentario_check=true',
  type:  'post',
  dataType: 'json',
  success:  function (response) { 
    if(response==1)
    {
      Swal.fire("","Comentario Guardado","success");
    }
    console.log(response);
    
  }, 
  error: function(xhr, textStatus, error){
    $('#myModal_espera').modal('hide');           
  }
});
}


function editar_comentario(mod)
{

  id = $('#txt_id').val();
  if(id=='')
  {
       Swal.fire("","Seleccione un pedido","info");
      return  false;
  }
 var texto = '';
 var asunto = '';
 var enviar = 0;
  if(mod)
  {
       if($('#txt_comentario_clas').prop('readonly'))
       {
           $('#txt_comentario_clas').prop('readonly',false)
           $('#icon_comentario1').removeClass();
           $('#icon_comentario1').addClass('fa fa-save'); 	 	 	
           enviar = 0;
           // $('#txt_comentario_clas').prop('readonly',true)
       }else{

           $('#txt_comentario_clas').prop('readonly',true)
           $('#icon_comentario1').removeClass();
           $('#icon_comentario1').addClass('fa fa-pencil');
           asunto = 'Clasificacion';
           texto = $('#txt_comentario_clas').val();
           enviar = 1;
       }
  }else
  {
       if($('#txt_comentario').prop('readonly'))
       {
           $('#txt_comentario').prop('readonly',false)
           $('#icon_comentario').removeClass();
           $('#icon_comentario').addClass('fa fa-save'); 	 	 
           enviar = 0;
           // $('#txt_comentario').prop('readonly',true);
       }else{
           $('#txt_comentario').prop('readonly',true)
           $('#icon_comentario').removeClass();
           $('#icon_comentario').addClass('fa fa-pencil');
           asunto = 'Recepcion';
           texto = $('#txt_comentario').val();
           enviar = 1;
       }
  }

  if(enviar)
  {
           var parametros = {
    'notificar':texto,
    'id':$('#txt_id').val(),
        'asunto':asunto,
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
            }
            console.log(response);
            
          }, 
          error: function(xhr, textStatus, error){
            $('#myModal_espera').modal('hide');           
          }
        });


    }

}

function ver_detalle_gavetas()
{
  codigo = $('#txt_codigo option:selected').text();
  var parametros = {
    'pedido':codigo,
        }
         $.ajax({
          data:  {parametros,parametros},
          url:   '../controlador/inventario/alimentos_recibidosC.php?estado_gaveta=true',
          type:  'post',
          dataType: 'json',
          success:  function (response) { 
              test = '';
              console.log(response);

                  response.forEach(function(item,i){

                  test+='<li class="list-group-item">'+
                                        '<a href="#" style="padding:0px">'+
                                                '<label>'+item.Producto+'</label>'+
                                                 '<div class="btn-group pull-right">'+
                                                         '<span class="label-success btn-sm btn">'+item.Entrada+'</span>'											 		
                                                 test+='</div>'+
                                         '</a>'+
                                    '</li>';
              })

              $('#lista_gavetas').html(test);

              $('#modal_estado_gavetas').modal('show');

          }
        });


}

function ver_detalle_trasorte()
{
  codigo = $('#txt_codigo option:selected').text();
  var parametros = {
    'pedido':codigo,
        }
         $.ajax({
          data:  {parametros,parametros},
          url:   '../controlador/inventario/alimentos_recibidosC.php?estado_trasporte=true',
          type:  'post',
          dataType: 'json',
          success:  function (response) { 
              var test = '';

              if(response[0].Conductor!='.')
              {		 
                  test+='<li class="list-group-item">'+
                                        '<a href="#" style="padding:0px">'+
                                                '<label>Vehiculo</label>'+
                                                 '<div class="btn-group pull-right">';
                                                 
                                                     test+='<span class="label-warning btn-sm btn">'+response[0].Conductor+'</span>'
                                                 
                                                 test+='</div>'+
                                         '</a>'+
                                    '</li>'
                    }


              test+='<li class="list-group-item">'+
                                        '<a href="#" style="padding:0px">'+
                                                '<label>Tipo de Vehiculo</label>'+
                                                 '<div class="btn-group pull-right">';
                                                 
                                                     test+='<span class="label-warning btn-sm btn">'+response[0].Carga+'</span>'
                                                 
                                                 test+='</div>'+
                                         '</a>'+
                                    '</li>'

              if(response[0].placa!='.')
                  {		  
                      test+='<li class="list-group-item">'+
                                        '<a href="#" style="padding:0px">'+
                                                '<label>Placa del Vehiculo</label>'+
                                                 '<div class="btn-group pull-right">';
                                                 
                                                     test+='<span class="label-warning btn-sm btn">'+response[0].placa+'</span>'
                                                 
                                                 test+='</div>'+
                                         '</a>'+
                                    '</li>'
                  }
              response.forEach(function(item,i){

                  console.log(item);

                  test+='<li class="list-group-item">'+
                                        '<a href="#" style="padding:0px">'+
                                                '<label>'+item.Proceso+'</label>'+
                                                 '<div class="btn-group pull-right">';
                                                 if(item.Cumple==1)
                                                 {
                                                     test+='<span class="label-success btn-sm btn">Cumple</span>'
                                                 }else{
                                                     test+='<span class="label-danger btn-sm btn">No cumple</span>'
                                                 }
                                                 test+='</div>'+
                                         '</a>'+
                                    '</li>';
              })
              $('#lista_preguntas').html(test);
              $('#modal_estado_transporte').modal('show');
            
          }, 
          error: function(xhr, textStatus, error){
            $('#myModal_espera').modal('hide');           
          }
        });
}

function nueva_notificacion()
{
$('#modal_notificar').modal('show');
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
            console.log(data);
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
        console.log(respuesta);
        pedidosPorQR(respuesta);
        //activarSonido();
        //encenderCamara();    
        cerrarCamara();    
    }
};


$( document ).ready(function() {
    cargar_pedido();
// cargar_productos();
autocoplet_pro();
})
 
function cargar_pedido()
{
var parametros=
{
  'num_ped':$('#txt_codigo').val(),
}
 $.ajax({
  data:  {parametros:parametros},
  url:   '../controlador/inventario/alimentos_recibidosC.php?pedido_checking=true',
  type:  'post',
  dataType: 'json',
  success:  function (response) {
    console.log(response);
    $('#tbl_body').html(response.tabla);
    $('#txt_cant_total').val(response.cant_total);
   
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

function contabilizar()
{ 
var parametros2 = $("#form_correos").serialize();
   $.ajax({
     data:  parametros2,
     url:   '../controlador/inventario/alimentos_recibidosC.php?contabilizar=true',
     type:  'post',
     dataType: 'json',
       success:  function (response) { 

        // console.log(response);
       if(response==1)
       {
          Swal.fire({
            type:'success',
            title: 'Pedido contabilizado',
            text :'',
          }).then( function() {
                      location.reload();
            });
       }else
       {
        Swal.fire('','Algo extraño a pasado.','error');
       }           
     }
   });    
}


function limpiar()
{
  $("#ddl_familia").empty();
  $("#ddl_descripcion").empty();
  $("#ddl_pro").empty();
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

function eliminar_lin(num)
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
          url:   '../controlador/inventario/alimentos_recibidosC.php?lin_eli=true',
          type:  'post',
          dataType: 'json',
          success:  function (response) { 
            if(response==1)
            {
              cargar_pedido();
            }
          }
        });
    }
  });
}

function notificar(usuario = false)
{
     var mensaje = $('#txt_notificar').val();
     var para_proceso = 1;
     var encargado = '';
     if(usuario=='usuario')
     {
         mensaje = $('#txt_texto').val();
         encargado = $('#txt_codigo_usu').val();

         para_proceso = 2;
     }

var parametros = {
    'notificar':mensaje,
    'id':$('#txt_id').val(),
    'asunto':'De Checking a Clasificacion',
    'pedido':$('#txt_codigo').val(),
    'de_proceso':3,
    'pa_proceso':para_proceso,
    'encargado':encargado,
}

// var parametros = {
//     'notificar':$('#txt_texto').val(),
//     'asunto':'De Checking a Clasificacion',
//     'pedido':$('#txt_codigo').val(),
// }
 $.ajax({
  data:  {parametros,parametros},
  url:   '../controlador/inventario/alimentos_recibidosC.php?notificar_clasificacion=true',
  type:  'post',
  dataType: 'json',
  success:  function (response) { 
    if(response==1)
    {

        if(usuario=='usuario')
                 {		 		
          cambiar_a_clasificacion('C');
                 }else{
          cambiar_a_clasificacion();
                 }
        
          Swal.fire("","Notificacion enviada","success").then(function(){
          $('#myModal_notificar_usuario').modal('hide'); 
          $('#txt_texto').val('');   
          $('#txt_codigo_usu').val('') 
      });
    }
    console.log(response);
    
  }, 
  error: function(xhr, textStatus, error){
    $('#myModal_espera').modal('hide');           
  }
});
}

function cambiar_a_clasificacion(T = 'R')
{   
     var t = T;
var parametros = {
    'pedido':$('#txt_codigo').val(),
    'T':t,
}
 $.ajax({
  data:  {parametros,parametros},
  url:   '../controlador/inventario/alimentos_recibidosC.php?cambiar_a_clasificacion=true',
  type:  'post',
  dataType: 'json',
  success:  function (response) { 
    if(response==1)
    {
      location.reload();
    }
    console.log(response);
    
  }, 
  error: function(xhr, textStatus, error){
    $('#myModal_espera').modal('hide');           
  }
});
}