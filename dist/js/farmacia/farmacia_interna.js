  var tbl_pedidos_all = null;
  var tbl_opcion2 = null;
  var tbl_opcion4 = null;
  var tbl_opcion5 = null;
  $( document ).ready(function() {
   
  });
   function autocoplet_prov(){
      $('#ddl_proveedor').select2({
        width:'300px',
        placeholder: 'Seleccione una proveedor',
        ajax: {
          url:   '../controlador/farmacia/articulosC.php?proveedores=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            // console.log(data);
            if(data != -1)
            {
              return {
                results: data
              };
            }else
            {
              Swal.fire('','Defina una cuenta "Cta_Proveedores" en Cta_Procesos.','info');
            }
          },
          cache: true
        }
      });
   
  }


   function tabla_ingresos()
   {

    if(tbl_pedidos_all!=null)
    {
      if ($.fn.DataTable.isDataTable('#tbl_opcion1')) {
        $('#tbl_opcion1').DataTable().destroy();
      }
    }
    tbl_pedidos_all = $('#tbl_opcion1').DataTable({
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
          url:   '../controlador/farmacia/farmacia_internaC.php?tabla_ingresos=true',
          type: 'POST',  // Cambia el método a POST   
          data: function(d) {
             var parametros=
              {
                'proveedor':$('#ddl_proveedor').val(),
                'factura':$('#txt_factura').val(),
                'comprobante':$('#txt_comprobante').val(),
                'serie':$('#txt_serie').val(),
              }
              return { parametros: parametros };
          },   
          dataSrc: '',             
        },
         scrollX: true,  // Habilitar desplazamiento horizontal
         columnDefs: [
            { width: "100px", targets: 1 }  // Ajusta la columna 0 (la primera)
          ],     
        columns: [
            { data:  null,
              render: function(data, type, item) {
                return `
                        <button class="btn btn-outline-secondary btn-sm p-1" title="Ver detalle" onclick="Ver_detalle('1','${item.Factura}','${item.Comprobante}')">
                            <span class="bx bx-show me-0"></span>
                        </button>`;   
              }

            },
           { data: null,
            render: function(data, type, item) {
              return formatoDate(data.Fecha.date)      
            }
          },
          { data:'Proveedor'},
          { data:'Factura'},
          { data:'Serie_No'},
          { data:'Comprobante'},
          { data: null,
           render: function(data, type, item) {
              return parseFloat(data.Total).toFixed(2)},
            }
        ],
      });

   }

 //-----------------------opcion2-----------------------------
  function autocoplet_desc(){
      $('#ddl_descripcion').select2({
        width:'310px',
        placeholder: 'Escriba Descripcion',
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

   function autocoplet_ref(){
      $('#ddl_referencia').select2({
        width:'310px',
        placeholder: 'Escriba Referencia',
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?producto=true&tipo=ref',
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



   function tabla_catalogo(tipo)
   {
    $('#tbl_op2').html('<div class="text-center"><img src="../../img/gif/loader4.1.gif"></div>');
   	 var parametros=
    {
      'descripcion':$('#ddl_descripcion').val(),
      'referencia':$('#ddl_referencia').val(),
      'tipo':tipo,
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/farmacia_internaC.php?tabla_catalogo=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
      	console.log(response);
        $('#tbl_op2').html(response);
      }
    });

   }

  function tabla_catalogo(tipo)
  {
    if(tbl_opcion2!=null)
    {
      if ($.fn.DataTable.isDataTable('#tbl_opcion2')) {
        $('#tbl_opcion2').DataTable().destroy();
      }
    }
    tbl_opcion2 = $('#tbl_opcion2').DataTable({
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
          url:   '../controlador/farmacia/farmacia_internaC.php?tabla_catalogo=true',
          type: 'POST',  // Cambia el método a POST   
          data: function(d) {
             var parametros=
              {
                'descripcion':$('#ddl_descripcion').val(),
                'referencia':$('#ddl_referencia').val(),
                'tipo':tipo,
              }
              return { parametros: parametros };
          },   
          dataSrc: '',             
        },
         scrollX: true,  // Habilitar desplazamiento horizontal
         columnDefs: [
            { width: "100px", targets: 1 }  // Ajusta la columna 0 (la primera)
          ],     
        columns: [
          { data:'Codigo'},
          { data:'Producto'},
          { data:'Existencia'},
          { data:'Valor_Unitario'},
          { data: null,
           render: function(data, type, item) {
              return formatoDate(data.Fecha)
            }      
          },

          { data:'Entrada'}
          
        ],
      });
   }




function Ver_Comprobante(comprobante)
{
    url='../controlador/farmacia/reporte_descargos_procesadosC.php?Ver_comprobante=true&comprobante='+comprobante;
    window.open(url, '_blank');
}
function Ver_detalle(comprobante)
{
    var mod = '<php echo $_SESSION["INGRESO"]["modulo_"]; ?>'; 
    url='../vista/inicio.php?mod='+mod+'&acc=utilidad_insumos&comprobante='+comprobante;
    window.open(url, '_blank');
}
function cargar_pedidos1(f='')
{
   
    $('#tbl_descargos').html('<div class="text-center"><img src="../../img/gif/loader4.1.gif"></div>');   
      var  parametros = 
      { 
        'nom':$('#txt_paciente').val(),
        'ci':$('#txt_ci').val(),
        'historia':$('#txt_historia').val(),
        'depar':$('#txt_departamento').val(),
        'proce':$('#txt_procedimiento').val(),
        'desde':$('#txt_desde').val(),
        'hasta':$('#txt_hasta').val(),
        'busfe':$('#rbl_fecha').prop('checked'),
        // 'numero':$('input[name="rbl_proce"]:checked').val(),
      }    
     // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/farmacia_internaC.php?cargar_pedidos=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response)
        if(response)
        {
          $('#tbl_descargos').html(response.tbl);
        }
      }
    });
}

function cargar_pedidos(f='')
{
   if(tbl_opcion4!=null)
    {
      if ($.fn.DataTable.isDataTable('#tbl_descargos')) {
        $('#tbl_descargos').DataTable().destroy();
      }
    }
    tbl_opcion4 = $('#tbl_descargos').DataTable({
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
          url:   '../controlador/farmacia/farmacia_internaC.php?cargar_pedidos=true',
          type: 'POST',  // Cambia el método a POST   
          data: function(d) {
             var parametros=
              {
                 'nom':$('#txt_paciente').val(),
                  'ci':$('#txt_ci').val(),
                  'historia':$('#txt_historia').val(),
                  'depar':$('#txt_departamento').val(),
                  'proce':$('#txt_procedimiento').val(),
                  'desde':$('#txt_desde').val(),
                  'hasta':$('#txt_hasta').val(),
                  'busfe':$('#rbl_fecha').prop('checked'),
              }
              return { parametros: parametros };
          },   
          dataSrc: '',             
        },
         scrollX: true,  // Habilitar desplazamiento horizontal
         columnDefs: [
           { width: "150px", targets: 1 },  // Ajusta la columna 0 (la primera)
          { width: "200px", targets: 2 }  // Ajusta la columna 0 (la primera)
          ],     
        columns: [
          {data:  null,
              render: function(data, type, item) {
                return `
                    <button class="btn btn-outline-secondary btn-sm p-1" title="Ver detalle" onclick="Ver_pedido('4','${item.Orden_No}','${item.comprobante}')">
                        <span class="bx bx-show me-0"></span>
                    </button>`;   
              }
          },
          { data: null,
            render: function(data, type, item) {
              return formatoDate(data.Fecha.date)      
            }
          },
          { data:'Paciente'},
          { data:'Cedula'},
          { data:'Historia'},
          { data:'Departamento'},
          { data:'importe'},
          { data:'Procedimiento'},
          { data:'Orden_No'},
          { data:'comprobante'},          
        ],
      });
  
}




  //----------------------------  opcion 5 ----------------
  function cargar_medicamentos1()
  {
     $('#tbl_medicamentos').html('<div class="text-center"><img src="../../img/gif/loader4.1.gif"></div>');
     if($('#rbl_fecha5').prop('checked'))
     {
       $('#cantidad_consu').css('display','initial');       
       $('#des').text($('#txt_desde5').val());  
       $('#has').text($('#txt_hasta5').val());
     }else
     {
      $('#cantidad_consu').css('display','none');     
     }
     var paginacion = 
    {
      '0':$('#pag').val(),
      '1':$('#ddl_reg').val(),
      '2':'cargar_medicamentos',
    }   
      var  parametros = 
      { 
        'nom':$('#txt_paciente5').val(),
        'ci':$('#txt_ci_ruc').val(),
        'medicamento':$('#txt_medicamento').val(),
        'depar':$('#txt_departamento5').val(),
        'desde':$('#txt_desde5').val(),
        'hasta':$('#txt_hasta5').val(),
        'busfe':$('#rbl_fecha5').prop('checked'),
      }    
     // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros,paginacion:paginacion},
      url:   '../controlador/farmacia/farmacia_internaC.php?descargos_medicamentos=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        if(response)
        {
          $('#tbl_medicamentos').html(response.tbl);
          $('#consumido').html(response.Total_consumido);
        }
      }
    });
  }

  function cargar_medicamentos()
  {
     if(tbl_opcion5!=null)
    {
      if ($.fn.DataTable.isDataTable('#tbl_medicamentos')) {
        $('#tbl_medicamentos').DataTable().destroy();
      }
    }
    tbl_opcion5 = $('#tbl_medicamentos').DataTable({
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
          url:   '../controlador/farmacia/farmacia_internaC.php?descargos_medicamentos=true',
          type: 'POST',  // Cambia el método a POST   
          data: function(d) {
             var parametros=
              {
                'nom':$('#txt_paciente5').val(),
                'ci':$('#txt_ci_ruc').val(),
                'medicamento':$('#txt_medicamento').val(),
                'depar':$('#txt_departamento5').val(),
                'desde':$('#txt_desde5').val(),
                'hasta':$('#txt_hasta5').val(),
                'busfe':$('#rbl_fecha5').prop('checked'),
              }
              return { parametros: parametros };
          },   
          dataSrc: '',             
        },
         scrollX: true,  // Habilitar desplazamiento horizontal
         columnDefs: [
           { width: "150px", targets: 1 },  // Ajusta la columna 0 (la primera)
          { width: "200px", targets: 2 }  // Ajusta la columna 0 (la primera)
          ],     
        columns: [
          { data: null,
            render: function(data, type, item) {
              return formatoDate(data.Fecha.date)      
            }
          },
          { data:'Producto'},
          { data:'Cliente'},
          { data:'Cedula'},
          { data:'Matricula'},
          { data:'Departamento'},
          { data:'Cantidad'},       
        ],
      });
  
  }


  function cargar_tablas()
  {
    $('#estilo_tabla').remove();
    var opcion = $('#ddl_opciones').val();

      $('#opcion5').css('display','none')  
      $('#opcion4').css('display','none')
      $('#opcion2').css('display','none')
      $('#opcion1').css('display','none')
    if(opcion==1)
    {
         $('#opcion1').css('display','initial')
         autocoplet_prov();
         tabla_ingresos();
    }else if(opcion==2)
    {
       autocoplet_desc();
       autocoplet_ref();
       tabla_catalogo('ref');
       $('#opcion2').css('display','block')

    }else if(opcion==3)
    {
      
    }else if(opcion==4)
    {   
    cargar_pedidos();
    $('#opcion4').css('display','block')

    }else if(opcion==5)
    {
      cargar_medicamentos(); 
      $('#opcion5').css('display','block')       
    }
  }


function reporte_pdf()
{
   var url = '../controlador/farmacia/farmacia_internaC.php?imprimir_pdf=true&';
   var opcion = $('#ddl_opciones').val();
   if(opcion=='')
   {
    Swal.fire('Seleccione una tipo de informe','','info');
    return false;
   }
   if(opcion==1)
   {
    url+='opcion=1&';
     var datos =  $("#form_1").serialize();
   }  
   if(opcion==2)
   {
    url+='opcion=2&';
     var datos =  $("#form_2").serialize();
   }  
   if(opcion==4)
   {
    url+='opcion=4&';
     var datos =  $("#form_4").serialize();
   }  
   if(opcion==5)
   {
    url+='opcion=5&';
     var datos =  $("#form_5").serialize();
   }  

    window.open(url+datos, '_blank');
}

function reporte_excel()
{
   var url = '../controlador/farmacia/farmacia_internaC.php?imprimir_excel=true&';
   var opcion = $('#ddl_opciones').val();
   if(opcion=='')
   {
    Swal.fire('Seleccione una tipo de informe','','info');
    return false;
   }
   if(opcion==1)
   {
    url+='opcion=1&';
     var datos =  $("#form_1").serialize();
   }  
   if(opcion==2)
   {
    url+='opcion=2&';
     var datos =  $("#form_2").serialize();
   }  
   if(opcion==4)
   {
    url+='opcion=4&';
     var datos =  $("#form_4").serialize();
   }  
   if(opcion==5)
   {
    url+='opcion=5&';
     var datos =  $("#form_5").serialize();
   }  

    window.open(url+datos, '_blank');
}


function Ver_detalle(reporte,factura='',comprobante='')
{
   location.href = 'inicio.php?mod='+ModuloActual+'&acc=farmacia_interna_detalle&reporte='+reporte+'&comprobante='+comprobante+'&factura='+factura;
}

function Ver_pedido(reporte,factura='',comprobante='')
{
   location.href = 'inicio.php?mod='+ModuloActual+'&acc=farmacia_interna_detalle&reporte='+reporte+'&comprobante='+comprobante+'&factura='+factura;
}
