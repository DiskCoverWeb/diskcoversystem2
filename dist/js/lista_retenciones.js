$(document).ready(function(){

  	catalogoLineas();
  	autocmpletar_cliente()
})

function cargar_registros()
{ 
      if($('#ddl_cliente').val()=="")
      {
        Swal.fire("Seleccione una Nombre","","info");
         return false;
      }

   	 $('#myModal_espera').modal('show');

      // Recargar los datos de la tabla
      tbl_retenciones_all.ajax.reload(function() {
          // Cerrar el modal después de que se hayan recargado los datos
      	setTimeout(function() {
		$('#myModal_espera').modal('hide');
		}, 500); 
      }, false);

}

  function Ver_retencion(retencion,serie,numero,tp)
  {    
    var url = '../controlador/facturacion/lista_retencionesC.php?Ver_retencion=true&retencion='+retencion+'&serie='+serie+'&numero='+numero+'&tp='+tp;   
    window.open(url,'_blank');
  }




  function autocmpletar_cliente(){
  	   var g = '.';
      $('#ddl_cliente').select2({
        placeholder: 'RUC / CI / Nombre',
        width:'resolve',
	    // minimumResultsForSearch: Infinity,
        ajax: {
          url: '../controlador/facturacion/lista_facturasC.php?clientes2=true&g='+g,
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


function catalogoLineas(){
    fechaEmision = fecha_actual();
    fechaVencimiento = fecha_actual();
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?catalogo=true',
      data: {'fechaVencimiento' : fechaVencimiento , 'fechaEmision' : fechaEmision},      
      dataType:'json', 
      success: function(data)             
      {
        if (data.length>0) {
          datos = data;
          // Limpiamos el select
          console.log(datos);
          $("#DCLinea").find('option').remove();
          if(data.length>0)
          {
            $("#DCLinea").append('<option value="">Todos</option>');
          }
          data.forEach(function(item,i){
            serie = item.id.split(' ');
            serie = serie[1];
             $("#DCLinea").append('<option value="'+serie+'">' + serie + '</option>');

            // console.log(item);
             // console.log(i);
          })
        }else{
          Swal.fire({
            type:'info',
            title: 'Usted no tiene un punto de venta asignado o esta mal configurado, contacte con la administracion del sistema',
            text:'',
            allowOutsideClick: false,
          }).then(()=>{
            console.log('ingresa');
                location.href = '../vista/modulos.php';
              });

        }         
      }
    });
  }


  function autorizar(factura,serie,fecha)
  { 
    // $('#myModal_espera').modal('show');
    var parametros = 
    {
      'Retencion':factura,
      'serie':serie,
      'Fecha':fecha,
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/facturacion/lista_retencionesC.php?autorizar_retencion=true',
      type:  'post',
      dataType: 'json',
       success:  function (data) {

    $('#myModal_espera').modal('hide');
      // console.log(data);
      if(data.respuesta==1)
      { 
        Swal.fire({
          type:'success',
          title: 'Retencion Procesada y Autorizada',
          confirmButtonText: 'Ok!',
          allowOutsideClick: false,
        }).then(function(){
          // var url=  '../../TEMP/'+data.pdf+'.pdf';
          // window.open(url, '_blank'); 
          // location.reload();    

        })
      }else if(data.respuesta==-1)
      {
        if(data.text==2 || data.text==null)
          {

          Swal.fire('XML devuleto','XML DEVUELTO','error').then(function(){ 
            // var url=  '../../TEMP/'+data.pdf+'.pdf';    window.open(url, '_blank');             
          }); 
            tipo_error_sri(data.clave);
          }else
          {

            Swal.fire(data.text,'XML DEVUELTO','error').then(function(){ 
              // var url=  '../../TEMP/'+data.pdf+'.pdf';    window.open(url, '_blank');             
            }); 
          }
      }else if(data.respuesta==2)
      {
        // tipo_error_comprobante(clave)
        Swal.fire('XML devuelto','','error'); 
        tipo_error_sri(data.clave);
      }
      else if(data.respuesta==4)
      {
        Swal.fire('SRI intermitente intente mas tarde','','info');  
      }else
      {
        if(data==-1)
        {
           Swal.fire('Revise CI_RUC de factura en base','Cliente no encontrado','info');
         }else{
          Swal.fire('XML devuelto por:'+data.text,'','error');  
        }
      }


      }
    });
  }

  function descargar_xml(xml)
  {
    var parametros = 
    {
        'xml':xml,
    }
     $.ajax({
        data: {parametros:parametros},
        url:   '../controlador/facturacion/lista_retencionesC.php?descargar_xml=true',
        dataType:'json',      
        type:  'post',
        // dataType: 'json',
        success:  function (response) { 
          if(response!='-1')
          {
            console.log(response);
              var link = document.createElement("a");
              link.download = response.xml;
              link.href ='../../php/'+response.ruta;
              link.click();
              console.log(link.href)
          }else
          {
            Swal.fire('No se encontro el xml','','info');
          }
        }
      });

  }

   function descargar_ret(retencion,serie,numero)
  {
    var parametros = 
    {
        'retencion':retencion,
        'serie':serie,
        'numero':numero,
    }
     $.ajax({
        data: {parametros:parametros},
        url:   '../controlador/facturacion/lista_retencionesC.php?descargar_retencion=true',
        dataType:'json',      
        type:  'post',
        // dataType: 'json',
        success:  function (response) { 
            console.log(response);
              var link = document.createElement("a");
              link.download = response;
              link.href = '../../TEMP/'+response;
              link.click();
        
         
        }
      });

  }


function modal_email_ret(retencion,serie,numero,autorizacion,emails)
  {
    $('#myModal_email').modal('show'); 
    $('#txt_fac').val(retencion);
    $('#txt_serie').val(serie);
    $('#txt_codigoc').val(numero);
    $('#txt_autorizacion').val(autorizacion);

    var to = emails.substring(0,emails.length-1);
    var ema = to.split(',');
    var t = ''
    ema.forEach(function(item,i)
    {
      t+='<div class="emails emails-input"><span role="email-chip" class="email-chip"><span>'+item+'</span><a href="#" class="remove">×</a></span><input type="text" role="emails-input" placeholder="añadir email ...">       </div>';
       console.log(item);
    })
   $('#emails-input').html(t)
   $('#txt_to').val(emails);

  }

  function enviar_email()
  {
     $('#myModal_espera').modal('show');
    var to = $('#txt_to').val();
    var cuerpo = $('#txt_texto').val();
    var pdf_fac = $('#cbx_factura').prop('checked');
    var titulo = $('#txt_titulo').val();
    var retencion = $('#txt_fac').val();
    var serie = $('#txt_serie').val();
    var numero = $('#txt_codigoc').val();
    var autoriza = $('#txt_codigoc').val();

    // var adjunto =  new FormData(document.getElementById("form_img"));

    // console.log()
// return false;
    console.log(to);
    parametros = 
    {
        'to':to,
        'cuerpo':cuerpo,
        'pdf_fac':pdf_fac,
        'titulo':titulo,
        'retencion':retencion,
        'serie':serie,
        'numero':numero,
        'autoriza':autoriza,
    }
     $.ajax({
        data: {parametros:parametros},
        url:   '../controlador/facturacion/lista_retencionesC.php?enviar_email_detalle=true',
        dataType:'json',      
        type:  'post',
        // dataType: 'json',
        success:  function (response) { 
           $('#myModal_espera').modal('hide');
            if(response==1)
            {
                Swal.fire('Email enviado','','success').then(function(){
                    $('#myModal_email').modal('hide');
                })
            }else
            {
                Swal.fire('Email no enviado','Revise que sea un correo valido','info');
            }
         
        }, 
        error: function(xhr, textStatus, error){
        $('#myModal_espera').modal('hide');
            // $('#lbl_mensaje').text(xhr.statusText);
            // alert(xhr.statusText);
            // alert(textStatus);
            // alert(error);
        }
      });

  }

