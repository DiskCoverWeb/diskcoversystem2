
  $(document).ready(function () {

      tbl_pedidos_all = $('#tbl_body').DataTable({
          // responsive: true,
          language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
          },
          ajax: {
               url:    '../controlador/farmacia/devoluciones_insumosC.php?cargar_pedidos=true',
              type: 'POST',  // Cambia el método a POST    
              data: function(d) {
                  
                  var ruc = CI;
                  var ci = ruc.substring(0,10);
                  var  parametros = 
                    { 
                      'codigo':ci,
                      'nom':$('#txt_nombre').val(),
                      'query':$('#txt_query').val(),
                      'tipo':$('input:radio[name=rbl_buscar]:checked').val(),
                      'desde':$('#txt_desde').val(),
                      'hasta':$('#txt_hasta').val(),
                    }    

                  return { parametros: parametros };
              },              
              dataSrc: function(json) {
              

                // Devolver solo la parte de la tabla para DataTables
                return json.tabla.data;
            }        
          },
           scrollX: true,  // Habilitar desplazamiento horizontal
   
          columns: [
              { data: null,
                 render: function(data, type, item) {
                      var botones = `<button class="btn btn-sm btn-primary" title="Eliminar linea"  onclick="Ver_detalle('${data.Numero}')" ><i class="bx bx-list-ul m-0"></i></button>`;                      
                    return botones;         
                  }
              },
              { data: 'Numero' },
              { data: 'Fecha.date' },
              { data: 'Concepto' },
              { data: 'Monto_Total'},
              { data: 'Cliente' },
              
              
          ]
     });
  })

  function cargar_pedidos(f='')
  {
     var paginacion = 
    {
      '0':$('#pag').val(),
      '1':$('#ddl_reg').val(),
      '2':'cargar_pedidos',
    }
    $('#txt_tipo_filtro').val(f);
    var ruc = CI;
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
      data:  {parametros:parametros,paginacion:paginacion},
      url:   '../controlador/farmacia/devoluciones_insumosC.php?cargar_pedidos=true',
      type:  'post',
      dataType: 'json',
        beforeSend: function () {   
          var spiner = '<tr><td colspan="5"><img src="../../img/gif/loader4.1.gif" width="20%"></td> </tr>';   
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
      url:   '../controlador/farmacia/devoluciones_insumosC.php?tabla_detalles=true',
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
    var cod ='<?php echo $cod; ?>';
    var ci = '<?php echo $ci; ?>';
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

        var mod = "<?php echo $_GET['mod'] ?>";
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
            var url = "../vista/farmacia.php?mod="+mod+"&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu&cod="+response[0].ORDEN+"&ci="+ci;
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
      var mod = '<?php echo $_SESSION["INGRESO"]["modulo_"]; ?>';
      var href="../vista/farmacia.php?mod="+mod+"&acc=ingresar_descargos&acc1=Ingresar%20Descargos&b=1&po=subcu&cod="+cod_cli+"&area="+area+"-"+$('#txt_procedimiento').val()+"#";
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

    var mod = '<?php echo $_SESSION["INGRESO"]["modulo_"]; ?>';    
    var href="../vista/farmacia.php?mod="+mod+"&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu#";
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
      url:   '../controlador/farmacia/devoluciones_insumosC.php?actualizar_his=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response==1)
        {
          var mod = '<?php echo $_SESSION["INGRESO"]["modulo_"]; ?>'; 
           var href="../vista/farmacia.php?mod="+mod+"&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu&cod="+num_hi+"&ci="+ci+"#";
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
              url:   '../controlador/farmacia/devoluciones_insumosC.php?eli_pedido=true',
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
   var url = '../controlador/farmacia/devoluciones_insumosC.php?imprimir_pdf=true&';
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

function formatoEgreso()
{
   var url = '../controlador/farmacia/devoluciones_insumosC.php?formatoEgreso=true&';
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
   var url = '../controlador/farmacia/devoluciones_insumosC.php?imprimir_excel=true&';
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


function Ver_Comprobante(comprobante)
{
    url='../controlador/farmacia/devoluciones_insumosC.php?Ver_comprobante=true&comprobante='+comprobante;
    window.open(url, '_blank');
}
function Ver_detalle(comprobante)
{

    url='../vista/inicio.php?mod='+ModuloActual+'&acc=devoluciones_detalle&acc1=Detalle de devolucion&b=1&po=subcu&comprobante='+comprobante;
    window.open(url, '_blank');
}
