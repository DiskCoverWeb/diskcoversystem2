 
  $(document).ready(function()
  {
  	autocmpletar_cliente()
  })


 function cargar_registros()
 {
 	 $('#myModal_espera').modal('show');

      // Recargar los datos de la tabla
      tbl_guias_all.ajax.reload(function() {
          // Cerrar el modal después de que se hayan recargado los datos
      	setTimeout(function() {
		$('#myModal_espera').modal('hide');
		}, 500); 
      }, false);
  
  

 }

  function Ver_guia_remision(TC,Serie,Factura,Autorizacion,Autorizacion_GR)
  {    
    var url = '../controlador/facturacion/lista_guia_remisionC.php?Ver_guia_remision=true&tc='+TC+'&factura='+Factura+'&serie='+Serie+'&Auto='+Autorizacion+'&AutoGR='+Autorizacion_GR;   
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
      data: {'fechaVencimiento' : fechaVencimiento , 'fechaEmision' : fechaEmision,'tipo':'GR'},      
      dataType:'json', 
      success: function(data)             
      {
        if (data.length>0) {
          datos = data;
          // Limpiamos el select
          console.log(datos);
          $("#DCLinea").find('option').remove();
          if(data.length>1)
          {
            $("#DCLinea").append('<option value="">Todos</option>');
          }
          data.forEach(function(item,i){
            serie = item.id.split(' ');
            serie = serie[1];
             $("#DCLinea").append('<option value="' + item.id +" "+item.text+ ' ">' + serie + '</option>');

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
      'nota':factura,
      'serie':serie,
      'Fecha':fecha,
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/facturacion/lista_guia_remisionC.php?autorizar_nota=true',
      type:  'post',
      dataType: 'json',
       success:  function (response) {
        $('#myModal_espera').modal('hide');
        // console.log(data);

         if(response.resp == 1)
              {
                 Swal.fire({
                  type: 'success',
                  title: 'Documento electronico autorizado',
                  allowOutsideClick: false,
                }).then(function(){

                  var url = '../../TEMP/' + response.pdf + '.pdf'; 
                 window.open(url,'_blank');                      
                  location.reload();
                  // imprimir_ticket_fac(0,TextCI,TextFacturaNo,serie[1]);
                });
              }else if(response.resp==2)
              {
                tipo_error_sri(response.clave);
                Swal.fire('XML devuelto','','error').then(() => {
                  
                });
                //descargar_archivos(response.url,response.ar);

              }else if(response.resp == 4)
              {
                 Swal.fire({
                  type: 'success',
                  title: 'Factura guardada',
                  allowOutsideClick: false,
                }).then(() => {
                  serie = DCLinea.split(" ");
                  cambio = $("#cambio").val();
                  efectivo = $("#efectivo").val();
                  var url = '../controlador/facturacion/divisasC.php?ticketPDF=true&fac='+TextFacturaNo+'&serie='+serie[1]+'&CI='+TextCI+'&TC='+serie[0]+'&efectivo='+efectivo+'&saldo='+cambio;
                  window.open(url,'_blank');
                  location.reload();
                  //imprimir_ticket_fac(0,TextCI,TextFacturaNo,serie[1]);
                });
              }else if(response.resp==5)
              {
                Swal.fire({
                  type: 'error',
                  title: 'Numero de documento repetido se recargara la pagina para colocar el numero correcto',
                  // text:''
                  allowOutsideClick: false,
                }).then(function(){
                  location.reload();
                })
              }else if(response.resp == -1)
              {
                tipo_error_sri(response.clave);
              }
              else
              {
                if(response.clave!='')
                {
                    tipo_error_sri(response.clave);
                }
                Swal.fire({
                  type: 'error',
                  title: 'XML NO AUTORIZADO',
                  allowOutsideClick: false,
                })
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
        url:   '../controlador/facturacion/lista_guia_remisionC.php?descargar_xml=true',
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

   function descargar_guia(factura,serie,auto,auto_gr,guia,serie_gr)
  {
    var parametros = 
    {
        'factura':factura,
        'serie':serie,
        'autorizacion':auto,
        'autorizacion_gr':auto_gr,
        'guia':guia,
        'serie_gr':serie_gr,
    }
     $.ajax({
        data: {parametros:parametros},
        url:   '../controlador/facturacion/lista_guia_remisionC.php?descargar_guia=true',
        dataType:'json',      
        type:  'post',
        // dataType: 'json',
        success:  function (response) { 
        	if(response!='-1')
        	{
            console.log(response);
              var link = document.createElement("a");
              link.download = response;
              link.href = '../../TEMP/'+response;
              link.click();
            }else
            {
            	Swal.fire("","Factura de guia de remision no encontrada","info")
            }
        
         
        }
      });

  }

function modal_email_guia(Remision,Serie_GR,Factura,Serie,Autorizacion_GR,Autorizacion,emails)
{

    $('#myModal_email').modal('show'); 
    $('#txt_fac').val(Remision);
    $('#txt_serie').val(Serie);
    $('#txt_seriegr').val(Serie_GR);
    $('#txt_numero').val(Factura);
    $('#txt_autorizacion').val(Autorizacion);
    $('#txt_autorizaciongr').val(Autorizacion_GR);

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
    var factura = $('#txt_numero').val();
    var serie = $('#txt_serie').val();
    var seriegr = $('#txt_seriegr').val();
    var remision = $('#txt_fac').val();
    var autoriza = $('#txt_autorizacion').val();
    var autorizagr = $('#txt_autorizaciongr').val();

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
        'factura':factura,
        'serie':serie,
        'seriegr':seriegr,
        'remision':remision,
        'autoriza':autoriza,
        'autorizagr':autorizagr,
    }
     $.ajax({
        data: {parametros:parametros},
        url:   '../controlador/facturacion/lista_guia_remisionC.php?enviar_email_detalle=true',
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


