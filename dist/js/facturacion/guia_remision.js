    let ivaprod = 0;
    let tablaguia;
  $(document).ready(function() {
    // limpiar_grid();
    $('#MBoxFechaGRE').select();
      autocomplete_cliente()
      DCCiudadI()
      DCCiudadF()
      autocomplete_cliente();
      AdoPersonas()
      DCEmpresaEntrega()
      cargar_grilla()
      productos()
      DCPorcenIva('MBoxFechaGRE', 'DCPorcIVA');


 $('#cliente').on('select2:select', function (e) {
      var data = e.params.data.data;
      $('#email').val(data.email);
      $('#direccion').val(data.direccion);
      $('#telefono').val(data.telefono);
      $('#ci_ruc').val(data.ci_ruc);
      $('#codigoCliente').val(data.codigo);
      $('#celular').val(data.celular);
      $('#txt_tc').text(data.tdCliente);
      console.log(data);
    });


   tablaguia = $('#tbl_guia_remision').DataTable({
        scrollX: true,
        searching: false,
        responsive: false,
        paging: false,   
        info: false,   
        autoWidth: false,  
        ajax: {
            url: '../controlador/facturacion/lista_guia_remisionC.php?cargarLineas=true',
            type: "POST",
            data: function(d) {
                parametros = 
                {
                    'guia':$('#LblGuiaR_').val(),
                    'serie':$('#DCSerieGR').val(),
                }
                return { parametros: parametros };
            },      
             dataSrc: function(data) {
                 $("#total0").val(parseFloat(data.total).toFixed(2));
                $("#totalFac").val(parseFloat(data.total + data.iva_total).toFixed(2));
                $("#efectivo").val(parseFloat(data.total + data.iva_total).toFixed(2));
                $('#iva12').val(parseFloat(data.iva_total).toFixed(2));
                $('#myModal_espera').modal('hide');

                console.log(data);
                // Devolver solo la parte de la tabla para DataTables
                return data.tabla;
            }        
        },
        columns: [

            { data: null,
                 render: function(data, type, row) {
                    return `<button type="button" class="btn btn-sm btn-danger" onclick="Eliminar('${data.ID}')"><i class="bx bx-trash me-0"></i></button>`;
                }
            },
            { data: 'Codigo' },
            { data: 'CANTIDAD' },
            { data: 'Producto' },
            { data: 'PRECIO' },
            { data: 'Total' },
            { data: 'ID' },
            { data: 'Total_IVA' },
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        }
    });






  });

  function cambiar_iva(){
    var iva = $('#DCPorcIVA').val();
    $('#LabelTotTarifa').html('<b>Total Tarifa ' + iva + '%</b>');
    $('#LabelIVA').html('<b>IVA ' + iva + '%</b>');
  }

  function cerrar_modal_cambio_nombre() {
    var nuevo = $('#TxtDetalle').val();
    var dcart = $('#producto').val();
    $('#producto').append($('<option>', {
        value: dcart,
        text: nuevo,
        selected: true
    }));
    $('#cantidad').focus();

    $('#cambiar_nombre').modal('hide');

}


   function calcular_totales(){
    var TextVUnit = parseFloat($("#preciounitario").val());
    var TextCant = parseFloat($("#cantidad").val());
    if(TextCant==0 || TextCant =='')
    {
      $("#cantidad").val(1);
    }
    var producto = $("#producto").val();
    if (TextVUnit <= 0) {
      $("#preciounitario").val(1);
    }
    var TextVTotal = TextVUnit*TextCant;
     
    $("#total").val(parseFloat(TextVTotal).toFixed(4));
  }

  function autocomplete_cliente(){
    $('#cliente').select2({
      placeholder: 'Seleccione un cliente',
      ajax: {
        url:   '../controlador/facturacion/divisasC.php?cliente=true',
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


 function eliminar_lineas()
 {
    parametros = {
        'guia':$('#LblGuiaR_').val(),
        'serie':$('#DCSerieGR').val(),
        'auto':$('#LblAutGuiaRem_').val(),
    }
     $.ajax({
        type: "POST",
        url: '../controlador/facturacion/lista_guia_remisionC.php?eliminar_lineas=true',
        data:{parametros:parametros}, 
        dataType: 'json',
        success: function(data) {
            
        }
    });
 }

  function AdoPersonas() {
    $('#DCRazonSocial').select2({
        placeholder: 'Seleccione un Grupo',
        ajax: {
            url: '../controlador/facturacion/lista_guia_remisionC.php?AdoPersonas=true',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}

function DCEmpresaEntrega() {
    $('#DCEmpresaEntrega').select2({
        placeholder: 'Seleccione la Empresa',
        ajax: {
            url: '../controlador/facturacion/lista_guia_remisionC.php?DCEmpresaEntrega=true',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}




  function DCCiudadI() {
    $('#DCCiudadI').select2({
        placeholder: 'Seleccione la ciudad',
        ajax: {
            url: '../controlador/facturacion/facturarC.php?DCCiudadI=true',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}

function DCCiudadF() {
    $('#DCCiudadF').select2({
        placeholder: 'Seleccione la ciudad',
        ajax: {
            url: '../controlador/facturacion/facturarC.php?DCCiudadF=true',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}

function MBoxFechaGRE_LostFocus()
{

    fechaEmision =  $('#MBoxFechaGRE').val()
    fechaVencimiento =  $('#MBoxFechaGRE').val()
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturar_pensionC.php?catalogo=true',
        data: {
            'fechaVencimiento': fechaVencimiento,
            'fechaEmision': fechaEmision,
            'tipo':'GR',
        },
        dataType: 'json',
        success: function(data) {
            if (data.length > 0) {
                
                console.log(data);
                opcion = '';
                // Limpiamos el select
                data.forEach(function(item,i){
                    opcion+='<option value="' +item.id + '">'+item.text + '</option>';
                })
                $('#DCSerieGR').html(opcion);
            } else {
                Swal.fire({
                    type: 'info',
                    title: 'Usted no tiene un punto de emsion asignado  o esta mal configurado, contacte con la administracion del sistema',
                    text: '',
                    allowOutsideClick: false,
                }).then(() => {
                    console.log('ingresa');
                    location.href = '../vista/modulos.php';
                });

            }
            DCSerieGR_LostFocus();
        }
    });
}

/*
function MBoxFechaGRE_LostFocus() {
    var parametros = {
        'MBoxFechaGRE': $('#MBoxFechaGRE').val(),
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?MBoxFechaGRE_LostFocus=true',
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(data) {
            if (data.length > 0) {
                llenarComboList(data, 'DCSerieGR');
            }
            DCSerieGR_LostFocus();
        }
    })
}
  */
  function DCSerieGR_LostFocus() {
    var DCserie = $('#DCSerieGR').val();
    serie = DCserie.split(' ');
    var parametros = {
        'DCSerieGR': serie[1],
        'MBoxFechaGRE': $('#MBoxFechaGRE').val(),
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/facturarC.php?DCSerieGR_LostFocus=true',
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(data) {
            console.log(data);
           
                //llenarComboList(data, 'DCSerieGR');
                $('#LblGuiaR_').val(data['Guia']);
                $('#LblAutGuiaRem_').val(data['Auto']);
                eliminar_lineas();
           
        }
        // success: function(response) {
        //     console.log(response);
        //     $('#LblGuiaR_').val(response[0]['Guia']);
        // }

    })

}


function validar_datos()
{
    if($('#txt_num_fac').val()=='')
    {
      Swal.fire('coloque un numero de factura','','info').then(function()
        {
           $('#txt_num_fac').select2('focus');
        });
      return false;
    }
    if($('#txt_serie_fac').val()=='')
    {
      Swal.fire('Coloque una serie de factura','','info').then(function()
        {
           $('#txt_serie_fac').select2('focus');
        });
      return false;
    }
    if($('#txt_auto_fac').val()=='')
    {
      Swal.fire('Autorizacion de factura invalida','','info').then(function()
        {
           $('#txt_auto_fac').select2('focus');
        });
      return false;
    }
    producto = $("#producto").val();
    if(producto=='')
    {
      Swal.fire('Seleccione un producto','','info').then(function()
        {
           $('#producto').select2('focus');
        });
      return false;
    }
    cliente = $("#cliente").val();
    if(cliente=='')
    {
      Swal.fire('Seleccione un cliente','','info').then(function()
        {
          $('#cliente').select2('focus');
        });
      return false;
    }
    ciudad1 = $('#DCCiudadI').val();
    if(ciudad1=='')
    {
       Swal.fire('Seleccione Ciudad de inicio','','info').then(function()
        {
            $('#DCCiudadI').select2('focus');
        });
       return false;
    }   
    ciudad2 = $('#DCCiudadF').val();
    if(ciudad2=='')
    {
       Swal.fire('Seleccione Ciudad de Fin','','info').then(function()
        {
            $('#DCCiudadF').select2('focus');
        });
       return false;
    }   

    DCRazonSocial = $('#DCRazonSocial').val();
    if(DCRazonSocial=='')
    {
       Swal.fire('Seleccione Transportista','','info').then(function()
        {
           // $('#DCRazonSocial').select();
            $('#DCRazonSocial').select2('focus');
        });
       return false;
    }   
    DCEmpresaEntrega = $('#DCEmpresaEntrega').val();
    if(DCEmpresaEntrega=='')
    {
       Swal.fire('Seleccione empresa Transportista','','info').then(function()
        {
            $('#DCEmpresaEntrega').select2('focus');
           // $('#DCEmpresaEntrega').select();
        });
       return false;
    }   
    TxtPlaca = $('#TxtPlaca').val();
    if(TxtPlaca=='')
    {
       Swal.fire('Ingrese un numero de placa','','info').then(function()
        {
           $('#TxtPlaca').select();
        });
       return false;
    }   
    TxtPedido = $('#TxtPedido').val();
    if(TxtPedido=='')
    {
       Swal.fire('Ingrese un numero de pedido','','info').then(function()
        {
           $('#TxtPedido').select();
        });
       return false;
    }   
    TxtZona_ = $('#TxtZona_').val();
    if(TxtZona_=='')
    {
       Swal.fire('Ingrese una Zona','','info').then(function()
        {
           $('#TxtZona_').select();
        });
       return false;
    }   
    TxtLugarEntrega = $('#TxtLugarEntrega').val();
    if(TxtLugarEntrega=='')
    {
       Swal.fire('Ingrese un lugar de entrega','','info').then(function()
        {
           $('#TxtLugarEntrega').select();
        });
       return false;
    }   
    aceptar();
}



function aceptar(){
   
    var year = new Date().getFullYear();
    producto = $("#producto").val();
    productoDes = $("#producto option:selected").text();
    cliente = $("#cliente").val();
    codigoCliente = $("#codigoCliente").val();
    // $('#myModal_espera').modal('show');
    parametros = $('#form_guia').serialize();
    var lineas = 
    {
        'Producto' : productoDes,
        'productoCod':producto,
        'Precio' :$('#preciounitario').val(),
        'Total_Desc' : 0,
        'Total_Desc2' : 0,
        'Iva' : 0,
        'Total':$('#total').val(),
        'MiMes': '',
        'Periodo' :year,
        'Cantidad' :$('#cantidad').val(),
        'codigoCliente':codigoCliente,
    }
    if(ivaprod){
      lineas.Iva = $('#DCPorcIVA').val();
    }
    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/lista_guia_remisionC.php?guardarLineas=true&'+parametros+'&T='+$('#txt_tc').text(),
      data: {lineas:lineas}, 
      success: function(data)
      {
        $('#producto').empty();
        cargar_grilla();
      }
    });
  }


  function cargar_grilla()
  {

    if ( $.fn.DataTable.isDataTable('#tbl_guia_remision') ) {
        tablaguia.ajax.reload(); // Si ya existe, recarga los datos
    } 

    // parametros = 
    // {
    //     'guia':$('#LblGuiaR_').val(),
    //     'serie':$('#DCSerieGR').val(),
    // }
    // $.ajax({
    //   type: "POST",
    //   data:{parametros:parametros},
    //   url: '../controlador/facturacion/lista_guia_remisionC.php?cargarLineas=true',
    //   dataType: 'json',
    //   success: function(data)
    //   {
    //     console.log(data);
    //     $('#tbl_divisas').html(data.tbl);   
    //     $("#total0").val(parseFloat(data.total).toFixed(2));
    //     $("#totalFac").val(parseFloat(data.total + data.iva_total).toFixed(2));
    //     $("#efectivo").val(parseFloat(data.total + data.iva_total).toFixed(2));
    //     $('#iva12').val(parseFloat(data.iva_total).toFixed(2));
    //     $('#myModal_espera').modal('hide');
    //   }
    // });
  }

  function productos(){
    $('#producto').select2({
        placeholder: 'Seleccione un Producto',
        ajax: {
             url: '../controlador/facturacion/divisasC.php?productos2=true',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
  }


  function Articulo_Seleccionado() {
    var parametros = {
        'codigo': $('#producto').val(),
        'fecha': $('#MBoxFechaGRE').val(),
        'CodBod': '1',
    }
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/punto_ventaC.php?ArtSelec=true',
        data: {
            parametros: parametros
        },
        dataType: 'json',
        success: function(data) {
            console.log(data);
            if (data.respueta == true) {
              ivaprod = data.datos.IVA;
                if (data.datos.Stock < 0) {
                    Swal.fire(data.datos.Producto + ' ES UN PRODUCTO SIN EXISTENCIA', '', 'info').then(
                        function() {
                            $('#producto').empty();
                            // $('#LabelStock').val(0);
                        });

                } else {

                    $('#stock').val(data.datos.Stock);
                    $('#preciounitario').val(data.datos.PVP);
                    $('#LabelStock').focus();
                    $('#TxtDetalle').val(data.datos.Producto);

                    $('#cambiar_nombre').on('shown.bs.modal', function() {
                        $('#TxtDetalle').focus();
                    })

                    $('#cambiar_nombre').modal('show', function() {
                        $('#TxtDetalle').focus();
                    })
                }

            }
        }
    });

}
function Eliminar(cod)
{
     $.ajax({
      url:'../controlador/facturacion/lista_guia_remisionC.php?Eliminar=true',
      type:'post',
      dataType:'json',
      data:{cod:cod},
      success: function(response){
        cargar_grilla()  
       }
    });
}
  function limpiar_grid()
  {   
     $.ajax({
      url:'../controlador/facturacion/lista_guia_remisionC.php?limpiar_grid=true',
      type:'post',
      dataType:'json',
      // data:{idpro:idpro},
      success: function(response){
        cargar_grilla(); 
     
    }
    });
  }

 function guardarFactura(){


     producto = $("#producto").val();
    if(producto=='')
    {
      Swal.fire('Seleccione un producto','','info').then(function()
        {
           $('#producto').select2('focus');
        });
      return false;
    }
    if($('#txt_num_fac').val()=='')
    {
      Swal.fire('coloque un numero de factura','','info').then(function()
        {
           $('#txt_num_fac').select2('focus');
        });
      return false;
    }
    if($('#txt_serie_fac').val()=='')
    {
      Swal.fire('Coloque una serie de factura','','info').then(function()
        {
           $('#txt_serie_fac').select2('focus');
        });
      return false;
    }
    if($('#txt_auto_fac').val()=='')
    {
      Swal.fire('Autorizacion de factura invalida','','info').then(function()
        {
           $('#txt_auto_fac').select2('focus');
        });
      return false;
    }
    cliente = $("#cliente").val();
    if(cliente=='')
    {
      Swal.fire('Seleccione un cliente','','info').then(function()
        {
          $('#cliente').select2('focus');
        });
      return false;
    }
    ciudad1 = $('#DCCiudadI').val();
    if(ciudad1=='')
    {
       Swal.fire('Seleccione Ciudad de inicio','','info').then(function()
        {
            $('#DCCiudadI').select2('focus');
        });
       return false;
    }   
    ciudad2 = $('#DCCiudadF').val();
    if(ciudad2=='')
    {
       Swal.fire('Seleccione Ciudad de Fin','','info').then(function()
        {
            $('#DCCiudadF').select2('focus');
        });
       return false;
    }   

    DCRazonSocial = $('#DCRazonSocial').val();
    if(DCRazonSocial=='')
    {
       Swal.fire('Seleccione Transportista','','info').then(function()
        {
           // $('#DCRazonSocial').select();
            $('#DCRazonSocial').select2('focus');
        });
       return false;
    }   
    DCEmpresaEntrega = $('#DCEmpresaEntrega').val();
    if(DCEmpresaEntrega=='')
    {
       Swal.fire('Seleccione empresa Transportista','','info').then(function()
        {
            $('#DCEmpresaEntrega').select2('focus');
           // $('#DCEmpresaEntrega').select();
        });
       return false;
    }   
    TxtPlaca = $('#TxtPlaca').val();
    if(TxtPlaca=='')
    {
       Swal.fire('Ingrese un numero de placa','','info').then(function()
        {
           $('#TxtPlaca').select();
        });
       return false;
    }   
    TxtPedido = $('#TxtPedido').val();
    if(TxtPedido=='')
    {
       Swal.fire('Ingrese un numero de pedido','','info').then(function()
        {
           $('#TxtPedido').select();
        });
       return false;
    }   
    TxtZona_ = $('#TxtZona_').val();
    if(TxtZona_=='')
    {
       Swal.fire('Ingrese una Zona','','info').then(function()
        {
           $('#TxtZona_').select();
        });
       return false;
    }   
    TxtLugarEntrega = $('#TxtLugarEntrega').val();
    if(TxtLugarEntrega=='')
    {
       Swal.fire('Ingrese un lugar de entrega','','info').then(function()
        {
           $('#TxtLugarEntrega').select();
        });
       return false;
    } 

    $('#myModal_espera').modal('show');

    parametros = $('#form_guia').serialize();
    parametros = parametros+'&Comercial='+$('#DCRazonSocial option:selected').text()+'&Entrega='+$('#DCEmpresaEntrega option:selected').text();
    $.ajax({
        type: "POST",
        url: '../controlador/facturacion/lista_guia_remisionC.php?guardarFactura=true',
        dataType: 'json',
        data: parametros, 
        success: function(response)
        {
          console.log(response);
          $('#myModal_espera').modal('hide');
          cargar_grilla();
            if(response[0] == 1)
              {
                 Swal.fire({
                  icon: 'success',
                  title: 'Documento electronico autorizado',
                  allowOutsideClick: false,
                }).then(function(){

                  var url = '../../TEMP/' + response['pdf'] + '.pdf'; 
                 window.open(url,'_blank');                      
                  location.reload();
                  // imprimir_ticket_fac(0,TextCI,TextFacturaNo,serie[1]);
                });
              }else if(response.resp==2)
              {
                tipo_error_sri(response[1]);
                Swal.fire('XML devuelto','','error').then(() => {
                  location.reload();                 
                });
                //descargar_archivos(response.url,response.ar);

              }else if(response[0] == 4)
              {
                 Swal.fire({
                  icon: 'success',
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
              }else if(response[0]==5)
              {
                Swal.fire({
                  icon: 'error',
                  title: 'Numero de documento repetido se recargara la pagina para colocar el numero correcto',
                  // text:''
                  allowOutsideClick: false,
                }).then(function(){
                  location.reload();
                })
              }else if(response[0] == -1)
              {
                tipo_error_sri(response[1]);
              }
              else
              {
                
                Swal.fire({
                  icon: 'error',
                  title: 'XML NO AUTORIZADO',
                  text: response[3],
                  allowOutsideClick: false,
                }).then(function(){
                  location.reload();
                })
              }              
        }
    });
  }

 function default_numero()
      {
         num = $('#txt_num_fac').val();
         if(num=='')
         {
            guia = $('#LblGuiaR_').val();
            if(guia!='')
            {
                $('#txt_num_fac').val(guia);
            }else
            {
                $('#txt_num_fac').val(1)
            }
         }
      }
      function default_serie()
      {
         num = $('#txt_serie_fac').val();
         if(num=='')
         {            
            $('#txt_serie_fac').val('001001'); 
         }
      }
      function default_auto()
      {
         num = $('#txt_auto_fac').val();
         if(num=='')
         {            
            auto = ruc_empresa;
            $('#txt_auto_fac').val(auto); 
         }
      }