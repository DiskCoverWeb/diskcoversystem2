  var tbl_pedidos_all = null;

  $( document ).ready(function() {
    // cargar_pedidos();
    cargar_ficha();
    autocoplet_paci();
    autocoplet_area();
    autocoplet_area_filtro();
    autocoplet_desc();
    // cargar_ficha();

  });

    function autocoplet_desc(){
      $('#ddl_articulo').select2({
        placeholder: 'Escriba Descripcion',
        width:'85%',
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?producto=true&tipo=desc',
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

  function cargar_pedidos(f='')
  {
    if(tbl_pedidos_all!=null)
    {
    if ($.fn.DataTable.isDataTable('#tbl_descargos')) {
      $('#tbl_descargos').DataTable().destroy();
    }
  }
    tbl_pedidos_all = $('#tbl_descargos').DataTable({
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
          url:   '../controlador/farmacia/descargosC.php?cargar_pedidos=true',
          type: 'POST',  // Cambia el método a POST   
          data: function(d) {
              $('#txt_tipo_filtro').val(f);
              var nom = $('#txt_query').val();
              var ciCli = ci.substring(0,10);
              var desde=$('#txt_desde').val();
              var  parametros = 
              { 
                'codigo':ciCli,
                'nom':$('#txt_nombre').val(),
                'query':nom,
                'tipo':$('input:radio[name=rbl_buscar]:checked').val(),
                'desde':desde,
                'hasta':$('#txt_hasta').val(),
                'busfe':f,
                'area':$('#ddl_areas_filtro').val(),
                'arti':$('#ddl_articulo').val(),
                'nega':$('#rbl_negativos').prop('checked'),
              }    
              return { parametros: parametros };
          },   
          dataSrc: '',             
        },
         scrollX: true,  // Habilitar desplazamiento horizontal
     
        columns: [
          { data: null,
            render: function(data, type, item,meta) {
              return (meta.row + 1);   
                           
            }
          },
          { data:'ORDEN'},
          { data:null,
            render: function(data, type, item) {
              return item.negativo+' '+item.nombre 
            }
          },
          { data:'subcta'},
          { data:'importe'},
          { data: null,
            render: function(data, type, item) {
              return formatoDate(data.Fecha_Fab.date)      
            }
          },
          { data: null,
            render: function(data, type, item) {
              return `E`;   
                           
            }
          },
          { data:  null,
            render: function(data, type, item) {
              return `<a href="../vista/inicio.php?mod=`+ModuloActual+`&acc=ingresar_descargos&num_ped=${item.ORDEN}&area=${item.area}-${item.Detalle}&cod=${item.his}" class="btn btn-sm btn-primary" title="Editar pedido">
                        <span class="bx bx-pencil"></span>
                      </a>
                      <button class="btn btn-sm btn-danger" onclick="eliminar_pedido('${item.ORDEN}','${item.area}')">
                          <span class="bx bx-trash"></span>
                      </button>`;   
                           
            }

          },
         
          
        ],
      });

    // if ($.fn.DataTable.isDataTable('#tbl_descargos')) {
    //   $('#tbl_descargos').DataTable().destroy();
    // }
    // $('#txt_tipo_filtro').val(f);
    // // var ruc = ci;
    // var nom = $('#txt_query').val();
    // var ciCli = ci.substring(0,10);
    // var desde=$('#txt_desde').val();
    //   var  parametros = 
    //   { 
    //     'codigo':ciCli,
    //     'nom':$('#txt_nombre').val(),
    //     'query':nom,
    //     'tipo':$('input:radio[name=rbl_buscar]:checked').val(),
    //     'desde':desde,
    //     'hasta':$('#txt_hasta').val(),
    //     'busfe':f,
    //     'area':$('#ddl_areas_filtro').val(),
    //     'arti':$('#ddl_articulo').val(),
    //     'nega':$('#rbl_negativos').prop('checked'),
    //   }    
    //  // console.log(parametros);
    //  $.ajax({
    //   data:  {parametros:parametros},
    //   url:   '../controlador/farmacia/descargosC.php?cargar_pedidos=true',
    //   type:  'post',
    //   dataType: 'json',
    //   // beforeSend: function () {
    //   //           $("#tbl_body").html('<tr class="text-center"><td colspan="7"><img src="../../img/gif/loader4.1.gif" width="25%"></td></tr>');
    //   //        },
    //   success:  function (response) { 
    //     if(response)
    //     {
    //       $('#tbl_body').html(response.tabla);
    //       $('#tbl_descargos').DataTable({
    //           scrollX: true,
    //           scrollCollapse: true, 
    //           searching: false,
    //           responsive: false,
    //           paging: false,   
    //           info: false,   
    //           autoWidth: false,  
    //           order: [[1, 'asc']], // Ordenar por la segunda columna
    //           language: {
    //           url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
    //           },
    //           initComplete: function() {
    //               // Ajustar columnas después de la inicialización
    //               this.api().columns.adjust().draw();
    //           }
    //         });

    //     }
    //   }
    // });
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
    // var ruc = '<?php echo $ci; ?>';
    var nom = $('#txt_query').val();
    var ciCli = ci.substring(0,10);
    var desde=$('#txt_desde').val();
      var  parametros = 
      { 
        'codigo':ciCli,
        'nom':$('#txt_nombre').val(),
        'query':nom,
        'tipo':$('input:radio[name=rbl_buscar]:checked').val(),
        'desde':desde,
        'hasta':$('#txt_hasta').val(),
        'area':$('#txt_area').val(),
        'arti':$('#ddl_articulo').val(),
        'busfe':f,
      }    
     // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/descargosC.php?tabla_detalles=true',
      type:  'post',
      dataType: 'json',
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
            mod = ModuloActual;
            var url = "../vista/inicio.php?mod="+mod+"&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu&cod="+response[0].ORDEN+"&ci="+ci;
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
       mod = ModuloActual;
      var href="../vista/inicio.php?mod="+mod+"&acc=ingresar_descargos&acc1=Ingresar%20Descargos&b=1&po=subcu&cod="+cod_cli+"&area="+area+"-"+$('#txt_procedimiento').val()+"#";
      $(location).attr('href',href);
    }else
    {
      Swal.fire('Paciente, procedimiento o Area no seleccionada.','','info');
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
        placeholder: 'Seleccione una Area de descargo',
        ajax: {
          url:   '../controlador/farmacia/descargosC.php?areas=true',
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

   function autocoplet_area_filtro(){
      $('#ddl_areas_filtro').select2({
        placeholder: 'Seleccione una Area de descargo',
        width:'100%',
        ajax: {
          url:   '../controlador/farmacia/descargosC.php?areas=true',
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
            icon: 'warning',
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
    mod = ModuloActual;
    var href="../vista/inicio.php?mod="+mod+"&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu#";
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
      Swal.fire('Ingrese un numero de historial.','','info');
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
      url:   '../controlador/farmacia/descargosC.php?actualizar_his=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response==1)
        {
           mod = ModuloActual;
           var href="../vista/inicio.php?mod="+mod+"&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu&cod="+num_hi+"&ci="+ci+"#";
           $(location).attr('href',href);
        }else if(response == -2)
        {
           Swal.fire('Numero ingresado ya esta registrado.','','info');
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
      icon: 'warning',
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
              url:   '../controlador/farmacia/descargosC.php?eli_pedido=true',
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
   var url = '../controlador/farmacia/descargosC.php?imprimir_pdf=true&';
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
   var url = '../controlador/farmacia/descargosC.php?imprimir_excel=true&';
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

function reporte_excel_nega()
{
   var url = '../controlador/farmacia/descargosC.php?imprimir_excel_nega=true&';
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

function reporte_pdf_nega()
{
   var url = '../controlador/farmacia/descargosC.php?imprimir_pdf_nega=true&';
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

 function mayorizar_inventario()
  {
    $('#myModal_espera').modal('show');
     $.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/farmacia/ingreso_descargosC.php?mayorizar=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        $('#myModal_espera').modal('hide');
        Swal.fire('Mayorizacion completada','','success');
      
      }
    });
  }

