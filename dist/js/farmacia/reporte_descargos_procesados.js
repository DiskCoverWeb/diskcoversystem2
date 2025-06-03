  var tbl_devoluciones = null;
  $( document ).ready(function() {
    cargar_pedidos();
    cargar_ficha();
    autocoplet_paci();
    autocoplet_area();
    autocoplet_area();
    // cargar_ficha();
  });

  function cargar_pedidos22(f='')
  {
     var paginacion = 
    {
      '0':$('#pag').val(),
      '1':$('#ddl_reg').val(),
      '2':'cargar_pedidos',
    }
    $('#txt_tipo_filtro').val(f);
    var ruc = ci;
    var nom = $('#txt_query').val();
    var ci = ruc.substring(0,10);
    var desde=$('#txt_desde').val();
      var  parametros = 
      { 
        'codigo':ci,
        'nom':$('#txt_nombre').val(),
        'query':nom,
        'tipo':$('input:radio[name=rbl_buscar]:checked').val(),
        'desde':desde,
        'hasta':$('#txt_hasta').val(),
        'busfe':f,
        'area':$('#ddl_areas').val(),
      }    
     // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros,paginacion:paginacion},
      url:   '../controlador/farmacia/reporte_descargos_procesadosC.php?cargar_pedidos=true',
      type:  'post',
      dataType: 'json',
        beforeSend: function () {   
          var spiner = '<img src="../../img/gif/loader4.1.gif" width="20%">';   
          $('#tbl_body').html(spiner);
         },
      success:  function (response) { 
        if(response)
        {
          $('#tbl_body').html(response.tabla);
        }
      }
    });
  }

  function cargar_pedidos(f='')
  {
       if(tbl_devoluciones!=null)
    {
      if ($.fn.DataTable.isDataTable('#tbl_body')) {
        $('#tbl_body').DataTable().destroy();
      }
    }
    tbl_devoluciones = $('#tbl_devoluciones').DataTable({
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
          url:   '../controlador/farmacia/reporte_descargos_procesadosC.php?cargar_pedidos=true',
          type: 'POST',  // Cambia el método a POST   
          data: function(d) {
             $('#txt_tipo_filtro').val(f);
            var ruc = ci;
            var nom = $('#txt_query').val();
            var ci = ruc.substring(0,10);
            var desde=$('#txt_desde').val();
              var  parametros = 
              { 
                'codigo':ci,
                'nom':$('#txt_nombre').val(),
                'query':nom,
                'tipo':$('input:radio[name=rbl_buscar]:checked').val(),
                'desde':desde,
                'hasta':$('#txt_hasta').val(),
                'busfe':f,
                'area':$('#ddl_areas').val(),
              }    
            
              return { parametros: parametros };
          },   
          dataSrc: function(json) {
            // console.LOG(json)
            // $('#lineas').val(json.lineas)
            // return json.tr;
          }             
        },
         scrollX: true,  // Habilitar desplazamiento horizontal
         columnDefs: [
           { width: "150px", targets: 1 },  // Ajusta la columna 0 (la primera)
          { width: "200px", targets: 2 }  // Ajusta la columna 0 (la primera)
          ],     
        columns: [
          { data: null,
            render: function(data, type, item) {
             return `
                    <button class="btn btn-danger btn-sm p-1" title="Ver detalle" onclick="Ver_detalle('${item.Numero}')">
                        <span class="bx bx-trash me-0"></span>
                    </button>
                   <button class="btn btn-danger btn-sm p-1" title="Ver detalle" onclick="Ver_Comprobante('${item.Numero}')">
                        <span class="bx bx-trash me-0"></span>
                    </button>`;
            }
          },
          { data:'Numero'},
          { data: null,
            render: function(data, type, item) {
              return formatoDate(data.Fecha.date)      
            }
          },
          { data:'Concepto'},
          { data:'Monto_Total'},
          { data:'Cliente'},
          { data:'Area'},    
        ],
      });
  }

   function cargar_pedidos_detalle(f='')
  {
    $('#txt_tipo_filtro').val(f);
    if(f!='')
    {
      $('#titulo_detalle').text('desde: '+$('#txt_hasta').val()+' hasta: '+$('#txt_desde').val());
    }else
    {

      $('#titulo_detalle').text('');
    }
    var ruc = '<?php echo $ci; ?>';
    var nom = $('#txt_query').val();
    var ci = ruc.substring(0,10);
    var desde=$('#txt_desde').val();
      var  parametros = 
      { 
        'codigo':ci,
        'nom':$('#txt_nombre').val(),
        'query':nom,
        'tipo':$('input:radio[name=rbl_buscar]:checked').val(),
        'desde':desde,
        'hasta':$('#txt_hasta').val(),
        'busfe':f,
      }    
     // console.log(parametros);

     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/reporte_descargos_procesadosC.php?tabla_detalles=true',
      type:  'post',
      dataType: 'json',
       beforeSend: function () {   
          var spiner = '<div class="text-center"><img src="../../img/gif/loader4.1.gif" width="20%"> </div>';   
          $('#tbl_detalle').html(spiner);
         },
      success:  function (response) { 
        console.log(response);
        if(response)
        {
          $('#tbl_detalle').html(response);
        }
      }
    });
  }


  function cargar_ficha()
  {
    var parametros=
    {
      'cod':cod,
      'ci':ci,
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/descargosC.php?pedido=true',
      type:  'post',
      dataType: 'json',

      success:  function (response) { 
        
          console.log(response);
        if(response)
        {
          if(cod!='0')
          {
            $('#ddl_paciente').append($('<option>',{value: response[0].CI_RUC, text:response[0].Cliente,selected: true }));
            // $('#txt_nombre').val(response[0].Cliente);
            $('#txt_codigo').val(response[0].Matricula);
            cargar_pedidos();
          }else
          {
            var url = "../vista/farmacia.php?mod=Farmacia&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu&cod="+response[0].ORDEN+"&ci="+ci;
            $(location).attr('href',url);
          }
        }
      }
    });
  }

  function nuevo_pedido()
  {
    var cod_cli = $('#txt_codigo').val();
    var area = $('#ddl_areas').val();
    var pro = $('#txt_procedimiento').val();
    if(cod_cli!='' && area !='' && pro!='')
    {
      var href="../vista/farmacia.php?mod=Farmacia&acc=ingresar_descargos&acc1=Ingresar%20Descargos&b=1&po=subcu&cod="+cod_cli+"&area="+area+"-"+$('#txt_procedimiento').val()+"#";
      $(location).attr('href',href);
    }else
    {
      Swal.fire('','Paciente, procedimiento o Area no seleccionada.','info');
    }
  }


   function autocoplet_paci(){
      $('#ddl_paciente').select2({
        placeholder: 'Seleccione una paciente',
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?paciente=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
   
  }

  function autocoplet_area(){
      $('#ddl_areas').select2({
        placeholder: 'Seleccione Area',
        width:'80%',
        ajax: {
          url:   '../controlador/farmacia/descargosC.php?areas=true',
          dataType: 'json',
          width:'90px',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
   
  }


  function buscar_cod()
  {
      var  parametros = 
      { 
        'query':$('#ddl_paciente').val(),
        'tipo':'R1',
        'codigo':'',
      }    
      //console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?buscar_edi=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response.matricula == 0)
        {
           $('#txt_codigo').val(response.matricula);
          Swal.fire({
            title: 'Este Paciente no tiene Historial!',
            text: "Desea actualizar el numero de historial clinico?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Actualizar!'
          }).then((result) => {
            if (result.value) {
              $('#num_historial').modal('show');
            }else
            {
              $('#ddl_paciente').empty();
              $('#txt_codigo').val('');
            }
          })
        }else
        {
           $('#ddl_paciente').append($('<option>',{value: response.ci, text:response.nombre,selected: true }));
           $('#txt_codigo').val(response.matricula);

        }
       
           // $('#txt_nombre').val(response[0].Cliente);
           // $('#txt_ruc').val(response[0].CI_RUC);
      }
    });
  }

  function limpiar()
  {
    
    var href="../vista/farmacia.php?mod=Farmacia&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu#";
    $(location).attr('href',href);
    $('#txt_query').val('');
    $("#ddl_paciente").empty();
    $("#txt_codigo").val('');
  }

  function actualizar_num_historia()
  {
    var num_hi = $('#txt_histo_actu').val();
    console.log(num_hi);
    var ci = $('#ddl_paciente').val();
    if(num_hi=='')
    {
      Swal.fire('','Ingrese un numero de historial.','info');
      return false;
    }
    $('#txt_codigo').val(num_hi);
     var  parametros = 
      { 
        'ci':ci,
        'num':num_hi,
      }    
     // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/reporte_descargos_procesadosC.php?actualizar_his=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response==1)
        {
           var href="../vista/farmacia.php?mod=Farmacia&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu&cod="+num_hi+"&ci="+ci+"#";
           $(location).attr('href',href);
        }else if(response == -2)
        {
           Swal.fire('','Numero ingresado ya esta registrado.','info');
        }
      }
    });


  }

  function eliminar_pedido(ped,area)
  {
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
              'area':area,
              'ped':ped,
            }
             $.ajax({
              data:  {parametros:parametros},
              url:   '../controlador/farmacia/reporte_descargos_procesadosC.php?eli_pedido=true',
              type:  'post',
              dataType: 'json',
              success:  function (response) { 
                if(response==1)
                {
                  cargar_pedidos();
                }
              }
            });
        }
      });
  }
function reporte_pdf()
{
   var url = '../controlador/farmacia/reporte_descargos_procesadosC.php?imprimir_pdf=true&';
   var datos =  $("#filtro_bus").serialize();
    window.open(url+datos, '_blank');
     // $.ajax({
     //     data:  {datos:datos},
     //     url:   url,
     //     type:  'post',
     //     dataType: 'json',
     //     success:  function (response) {  
          
     //      } 
     //   });

}

function formatoEgreso()
{
   var url = '../controlador/farmacia/reporte_descargos_procesadosC.php?formatoEgreso=true&';
   var datos =  $("#filtro_bus").serialize();
    window.open(url+datos, '_blank');
     $.ajax({
         data:  {datos:datos},
         url:   url,
         type:  'post',
         dataType: 'json',
         success:  function (response) {  
          
          } 
       });

}

function reporte_excel()
{
   var url = '../controlador/farmacia/reporte_descargos_procesadosC.php?imprimir_excel=true&';
   var datos =  $("#filtro_bus").serialize();
    window.open(url+datos, '_blank');
     // $.ajax({
     //     data:  {datos:datos},
     //     url:   url,
     //     type:  'post',
     //     dataType: 'json',
     //     success:  function (response) {  
          
     //      } 
     //   });

}


function Ver_Comprobante(comprobante)
{
    url='../controlador/farmacia/reporte_descargos_procesadosC.php?Ver_comprobante=true&comprobante='+comprobante;
    window.open(url, '_blank');
}
function Ver_detalle(comprobante)
{
    url='../vista/farmacia.php?mod=28&acc=facturacion_insumos&acc1=Utilidad insumos&b=1&po=subcu&comprobante='+comprobante;
    window.open(url, '_blank');
}
