function FormActivate() {
  $('#fecha1').focus();
  // numero_comprobante();
  cargar_totales_aseintos();
  autocoplet_bene();
  cargar_cuenta_efectivo();
  cargar_cuenta_banco();
  cargar_cuenta();
  cargar_tablas_contabilidad();
  cargar_tablas_tab4();
  cargar_tablas_retenciones();
  cargar_tablas_sc();
  ListarAsientoB();
  $('#codigo').val('');
}

function autocoplet_bene(){
  $('#beneficiario1').select2({
    placeholder: 'Seleccione una beneficiario',
    width:'75%',
    dropdownAutoWidth: true,
    selectionCssClass: 'form-control form-control-sm h-100',  // Para el contenedor de Select2
    ajax: {
      url:   '../controlador/contabilidad/incomC.php?beneficiario=true&q=.',
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

function benefeciario_edit()
    {
      var bene = $("#beneficiario1").val();
      var parametros = 
      {
        'beneficiario' : bene,
      }
      $.ajax({
        data:  {parametros:parametros},
         url:   '../controlador/contabilidad/incomC.php?edit_beneficiario=true',
        type:  'post',
        dataType: 'json',
          success:  function (response) {
            bene = bene.split('-');
            $('#ruc').val(bene[0]);
            $('#email').val(bene[1]);

        }
      });       
    }

function cargar_beneficiario(ci)
{
  var opcion = '';
  $.ajax({
  // data:  {parametros:parametros},
    url:   '../controlador/contabilidad/incomC.php?beneficiario_C=true&q='+ci+"#",
  type:  'get',
  dataType: 'json',
    success:  function (response) {
      var valor = response[0].id;
      var parte = valor.split('-');
        $('#ruc').val(parte[0]);
        $('#email').val(parte[1]);
        $('#beneficiario1').append($('<option>',{value:  response[0].id, text: response[0].text,selected: true }));
  }
}); 
}

function guardar_diferencia()
{      
    var form = $('#formu1').serialize();
    form = form+'&Trans_No='+Trans_No+'&Ln_No='+Ln_No;;
    $.ajax({
      data:  form,
      url:   '../controlador/contabilidad/incomC.php?guardar_diferencia=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) {
        if(response==1)
        {
          FormActivate();
        }
        
      }
    }); 
}
function mostrar_efectivo()
    {
      if($('#efec').prop('checked'))
      {
        $('#rbl_efec').css("background-color",'#286090');
        $('#rbl_efec').css("color",'#FFFFFF');
        $('#rbl_efec').css("border-radius",'5px');
        $('#rbl_efec').css("padding",'3px');
        $('#ineg1').addClass('d-block').removeClass('d-none');
      }else
      {
        $('#rbl_efec').css("background-color",'');
        $('#rbl_efec').css("color",'black');
        $('#rbl_efec').css("border-radius",'');
        $('#rbl_efec').css("padding",'');
        $('#ineg1').addClass('d-none').removeClass('d-block');
      }
    }

function mostrar_banco()
{
  if($('#ban').prop('checked'))
  {
    $('#rbl_banco').css("background-color",'#286090');
    $('#rbl_banco').css("color",'#FFFFFF');
    $('#rbl_banco').css("border-radius",'5px');
    $('#rbl_banco').css("padding",'3px');
    $('#ineg2').addClass('d-block').removeClass('d-none');
    $('#ineg3').addClass('d-block').removeClass('d-none');
  }else
  {
    $('#rbl_banco').css("background-color",'');
    $('#rbl_banco').css("color",'black');
    $('#rbl_banco').css("border-radius",'');
    $('#rbl_banco').css("padding",'');
    $('#ineg2').removeClass('d-block').addClass('d-none');
    $('#ineg3').removeClass('d-block').addClass('d-none');
  }
}

function cargar_cuenta_efectivo()
{
  var opcion = '';
  $.ajax({
  // data:  {parametros:parametros},
    url:   '../controlador/contabilidad/incomC.php?cuentas_efectivo=true',
  type:  'post',
  dataType: 'json',
    success:  function (response) {
      // console.log(response);
      $.each(response,function(i,item){
        if(i==0)
        {
          ini = item.id;
        }
        opcion+='<option value="'+item.id+'">'+item.text+'</option>';
      })
      $('#conceptoe').html(opcion);
      $('#conceptoe').val(ini);
                // console.log(response);
  }
}); 

  // $('#conceptoe').select2({
  //   placeholder: 'Seleccione cuenta efectivo',
  //   ajax: {
  //     url:   '../controlador/contabilidad/incomC.php?cuentas_efectivo=true',
  //     dataType: 'json',
  //     delay: 250,
  //     processResults: function (data) {
  //       console.log(data);
  //       return {
  //         results: data
  //       };
  //     },
  //     cache: true
  //   }
  // });
}       

function cargar_cuenta_banco()
{
  var opcion = '';
  $.ajax({
  // data:  {parametros:parametros},
  url:   '../controlador/contabilidad/incomC.php?cuentas_banco=true',
  type:  'post',
  dataType: 'json',
    success:  function (response) {
      // console.log(response);
      $.each(response,function(i,item){
        if(i==0)
        {
          ini = item.id;
        }
        opcion+='<option value="'+item.id+'">'+item.text+'</option>';
      })
      $('#conceptob').html(opcion);
      $('#conceptob').val(ini);
      DCBanco_LostFocus();
                // console.log(response);
  }
}); 

  // $('#conceptob').select2({
  //   placeholder: 'Seleccione cuenta banco',
  //   ajax: {
  //     url:   '../controlador/contabilidad/incomC.php?cuentas_banco=true',
  //     dataType: 'json',
  //     delay: 250,
  //     processResults: function (data) {
  //       console.log(data);
  //       return {
  //         results: data
  //       };
  //     },
  //     cache: true
  //   }
  // });
}

//Consultar el total de comprobantes para saber cual fue el ultimo. 
function comprobantes(tp, callback)
{
  console.log(tp);
  var parametros = 
  {
    'MesNo':'0',
    'TP':tp,
  }
  $.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/contabilidad/contabilidad_controller.php?comprobantes_lista',
    type:  'post',
    dataType: 'json',
    success:  function (response) {
      const numeros = response.map(item => item.Numero);
      callback(numeros);
    },
    error: function(error){
      console.error("No se logro realizar la solicitud: ", error);
    }
  }); 
}

function cargar_cuenta()
{
  $('#cuentar').select2({
    placeholder: 'Seleccione cuenta',
    ajax: {
      url:   '../controlador/contabilidad/incomC.php?cuentasTodos=true',
      data: function (term, page) { return {q: term,  tip:$('#codigo').val()}},
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

function reset_1(concepto,tipo)
{
  // $('#fecha1').select();
  $('#fecha1').focus();
  var sel = $('#tipoc').val();
  $('#'+sel).removeClass("active");
  if (tipo=='CD') 
  {
    $('#ineg').css('display','none');
    $('#tipoc').val(tipo);
    numero_comprobante();

  }else if(tipo=='CI')
  { 
    $('#tipoc').val(tipo);
    $('#CI').addClass("active");
    $('#tipoc').val(tipo);
    $('#ineg').css('display','block');
    $('#no_cheque').css('display','none');
    $('#ingreso_val_banco').css('display','block');
    $('#deposito_no').css('display','block');
    numero_comprobante();

  }else if(tipo=='CE')
  {
  $('#myModal_espera').modal('show');
    $('#tipoc').val(tipo);
    $('#CE').addClass("active");
    $('#tipoc').val(tipo);
    $('#ineg').css('display','block');
    $('#no_cheque').css('display','block');
    $('#ingreso_val_banco').css('display','none');
    $('#deposito_no').css('display','none');
    numero_comprobante();
    eliminar_todo_asisntoB();

  }else if(tipo=='ND')
  {
    $('#tipoc').val(tipo);
    $('#ND').addClass("active");
    $('#tipoc').val(tipo);
    $('#ineg').css('display','none');
    numero_comprobante();

  }else if(tipo=='NC')
  {
    $('#tipoc').val(tipo);
    $('#NC').addClass("active");
    $('#tipoc').val(tipo);
    $('#ineg').css('display','none');
    numero_comprobante();

  }
  String_Header(tipo);
}

function eliminar_todo_asisntoB()
{
    $.ajax({
      //data:  {parametros:parametros},
      url:   '../controlador/contabilidad/incomC.php?EliAsientoBTodos=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
        if(response == 1)
        {
        
          ListarAsientoB();
        setTimeout(function(){
          $('#myModal_espera').modal('hide');
        }, 500)
        } 
      }
    });

}

function xml()
{
  var parametros = 
  {
    'ruc':'1190068753',
    'numero':'9000147',
    'comp':'CD'
  }
   $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/contabilidad/incomC.php?generar_xml=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
        if(response == 1)
        {
        
        } 
      }
    });

}
// function numero_comprobante()
//   {
//     var tip = $('#tipoc').val();
//     var fecha = $('#fecha1').val();
//     if(tip=='CD'){ tip = 'Diario';}
//     else if(tip=='CI'){tip = 'Ingresos'}
//     else if(tip=='CE'){tip = 'Egresos';}
//     else if(tip=='ND'){tip = 'NotaDebito';}
//     else if(tip=='NC'){tip= 'NotaCredito';}
//     var parametros = 
//      {      
//        'tip': tip,
//        'fecha': fecha,                    
//      };
//   $.ajax({
//     data:  {parametros:parametros},
//      url:   '../controlador/contabilidad/incomC.php?num_comprobante=true',
//     type:  'post',
//     dataType: 'json',
//     // beforeSend: function () {
//     //    $("#num_com").html("");
//     // },
//     success:  function (response) {
//         $("#num_com").html("");
//         $("#num_com").html('Comprobante de '+tip+' No. <?php echo date('Y');?>-'+response);
//         // var valor = $("#subcuenta1").html(); 
//     }
//   });
//   }
function agregar_depo()
{
  var banco = $('#conceptob').val();
  let nom_banco = $('#conceptob option:selected').text();
  nombre = nom_banco.replace(banco,'');
  // console.log(nombre);
  var parametros = 
  {
    'banco':banco,
    'bancoC':nombre,
    'cheque':$('#no_cheq').val(),
    'valor':$('#vab').val(),
    'fecha':$('#fecha1').val(),
    'T_no':$('#tipoc').val(),
  }
  if(banco =='')
  {
    Swal.fire({
    type: 'info',
    title: 'Oops...',
    text: 'Seleccione cuenta de banco!'
        });
    return false;
  }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/contabilidad/incomC.php?asientoB=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
        if(response == 1)
        {
    //       Swal.fire({
    // type: 'success',
    // title: 'Agregado',
    // text: 'ingresado!'
    //     });           
          ListarAsientoB();
        }  else
        {
          Swal.fire({
    type: 'error',
    title: 'Oops...',
    text: 'debe agregar beneficiario!'
        });
        }       
      }
    });
}

function validarc(cta,cheque)
{     
  
  var parametros = 
  {
    'cta':cta,
    'cheque':cheque,
  }
  Swal.fire({
              title: 'Esta seguro?',
              text: "Esta usted seguro de que quiere borrar este registro!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Si!'
            }).then((result) => {
              if (result.value==true) {
              Eliminar(parametros);
              }else
              {
              document.getElementById(id).checked = false;
              }
            })
}

function Eliminar(parametros)
{
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/contabilidad/incomC.php?EliAsientoB=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
        if(response == 1)
        {
          Swal.fire({
            type: 'success',
            title: 'Eliminado',
            text: 'Registro eliminado!'
          });
          ListarAsientoB();
          //
        }  else
        {
          Swal.fire({
    type: 'error',
    title: 'Oops...',
    text: 'No se pudo ejecutar la solicitud!'
        });
        }       
      }
    });
}

function abrir_modal_cuenta()
{      
  $('#modal_cuenta').modal('show');
  var codigo = $('#cuentar').val();      
  $('#codigo').val(codigo);     
  tipo_cuenta(codigo);
}

function tipo_cuenta(codigo)
{
  $.ajax({
      data:  {codigo:codigo},
      url:   '../controlador/contabilidad/incomC.php?TipoCuenta=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
        $("#txt_cuenta").val(response.cuenta);
        $("#txt_codigo").val(response.codigo);
        $("#txt_tipocta").val(response.tipocta);
        $("#txt_subcta").val(response.subcta);
        $("#txt_tipopago").val(response.tipopago);
        $("#txt_moneda_cta").val(response.moneda);
        if(response.subcta =='BA')
        {
          $('#panel_banco').css('display','block');
            $('#modal_cuenta').on('shown.bs.modal', function (){
              $('#txt_efectiv').select();
            });
        }else{
          $('#panel_banco').css('display','none');
            $('#modal_cuenta').on('shown.bs.modal', function (){
              $('#txt_moneda').select();
            });
        }
  }
    });
}
function restingir(campo)
{
  var valor = $('#'+campo).val();
  var cant = valor.length;
  if(cant>1)
  {
    var num = valor.substr(0,1);
    if(num<3 && num>0)
    {
        $('#'+campo).val(num);
    }else
    {
      $('#'+campo).val('');
    }
  }else
  {
    if(valor<3 && valor>0)
    {
      $('#'+campo).val(valor);
    }else
    {
      $('#'+campo).val('');
    }
  }
}
function cambia_foco()
{
  // alert('ss');
  $('#modal_cuenta').modal('hide');
  $('#va').select();
}

function cargar_tablas_contabilidad()
{
  if($.fn.DataTable.isDataTable('#tbl_contabilidad')){
    $('#tbl_contabilidad').DataTable().clear().destroy();
  }
  tbl_contabilidad = $('#tbl_contabilidad').DataTable({
      searching: false,
      paging: false,   
      info: false,   
      autoWidth: false,
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
    },
    ajax: {
      url:   '../controlador/contabilidad/incomC.php?tabs_contabilidad=true',
      type: 'get',
      cache: true, 
      dataSrc: function(response){
        response.data = ProcesarDatos(response.data);
        return response.data;
      },
      error: function(xhr, status, error){
        console.error("Error en la solicitud: ", xhr, status, error);
      }
    }, 
    scrollX: true, 
    scrollY: '300px',
    scrollCollapse: true,
    columns: [
      { data: null,
        render: function(data, type, row){
          return data[0] || '';
        },
        orderable: false,
        className: 'text-center' },
      { data: 'CODIGO' },
      { data: 'CUENTA' },
      { data: 'PARCIAL_ME' },
      { data: 'DEBE' },
      { data: 'HABER' },
      { data: 'CHEQ_DEP' },
      { data: 'DETALLE' },
      { data: 'EFECTIVIZAR',
        render: function(data, type, item) {
          const fecha = data?.date;
          return fecha ? new Date(fecha).toLocaleDateString() : '';
        }
       },
      { data: 'CODIGO_C' },
      { data: 'CODIGO_CC' },
      { data: 'BENEFICIARIO' },
      { data: 'ME' },
      { data: 'T_No' },
      { data: 'Item' },
      { data: 'CodigoU' },
      { data: 'A_No' },
      { data: 'TC' },
      { data: 'X' },
      { data: 'ID' }
    ], 
    order: [
      [0, 'asc']
    ], 
    createdRow: function(row, data){
      alignEnd(row, data);
    }
  });
  

}
function cargar_tablas_sc()
{
  if($.fn.DataTable.isDataTable('#tbl_subcuentas')){
    $('#tbl_subcuentas').DataTable().clear().destroy();
  }
  tbl_subcuentas = $('#tbl_subcuentas').DataTable({
    searching: false,
    paging: false,   
    info: false,   
    autoWidth: false,
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
    },
    ajax: {
      url: '../controlador/contabilidad/incomC.php?tabs_sc=true',
      dataSrc: function(response){
        response.data = ProcesarDatos(response.data);
        return response.data;
      },
      error: function(xhr, status, error){ 
        console.error("Error en la consulta: ", xhr, status, error);      }
    }, 
    scrollX: true, 
    scrollCollapse: true, 
    scrollY: '300px',
    columns: [
      { data: null,
        render: function(data, type, row){
          return data[0] || '';
        },
        orderable: false,
        className: 'text-center' }, 
      { data: 'Codigo' }, 
      { data: 'Beneficiario' }, 
      { data: 'Serie' }, 
      { data: 'Factura' }, 
      { data: 'Prima' }, 
      { data: 'DH' }, 
      { data: 'Valor' }, 
      { data: 'Valor_ME' }, 
      { data: 'Detalle_SubCta' }, 
      { data: 'FECHA_V',
        render: function(data, type, item) {
          const fecha = data?.date;
          return fecha ? new Date(fecha).toLocaleDateString() : '';
        }
       }, 
      { data: 'FECHA_E',
        render: function(data, type, item) {
          const fecha = data?.date;
          return fecha ? new Date(fecha).toLocaleDateString() : '';
        }
       }, 
      { data: 'TC' }, 
      { data: 'Cta' }, 
      { data: 'TM' }, 
      { data: 'T_No' }, 
      { data: 'SC_No' }, 
      { data: 'Fecha_D',
        render: function(data, type, item) {
          const fecha = data?.date;
          return fecha ? new Date(fecha).toLocaleDateString() : '';
        }
       }, 
      { data: 'Fecha_H',
        render: function(data, type, item) {
          const fecha = data?.date;
          return fecha ? new Date(fecha).toLocaleDateString() : '';
        }
       }, 
      { data: 'Bloquear' }, 
      { data: 'Item' }, 
      { data: 'CodigoU' }, 
      { data: 'ID' }
    ],
    order: [
      [0, 'asc']
    ], 
    createdRow: function(row, data){
      alignEnd(row, data);
    }
  });
}

function cargar_tablas_retenciones()
{
  if($.fn.dataTable.isDataTable('#tbl_ac') && $.fn.dataTable.isDataTable('#tbl_asientoR')){
    $('#tbl_ac').DataTable().clear().destroy();
    $('#tbl_asientoR').DataTable().clear().destroy();    
  }
  
  $.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/contabilidad/incomC.php?tabs_retencion=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        
          tbl_ac = $('#tbl_ac').DataTable({ 
            searching: false,
            paging: false,   
            info: false,   
            autoWidth: false,
            language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            data: ProcesarDatos(response.b.data),
            scrollX: true,
            scrollY: '150px',
            scrollCollapse: true,
            destroy: true,
            columns: [
              { data: null,
                render: function(data, type, row){
                  return data[0] || '';
                },
                orderable: false,
                className: 'text-center' },
              { data: 'IdProv' },
              { data: 'DevIva' },
              { data: 'CodSustento' },
              { data: 'TipoComprobante' },
              { data: 'Establecimiento' },
              { data: 'PuntoEmision' },
              { data: 'Secuencial' },
              { data: 'Autorizacion' },
              { data: 'FechaEmision',
                render: function(data, type, item) {
                  const fecha = data?.date;
                  return fecha ? new Date(fecha).toLocaleDateString() : '';
                }
               },
              { data: 'FechaRegistro',
                render: function(data, type, item) {
                  const fecha = data?.date;
                  return fecha ? new Date(fecha).toLocaleDateString() : '';
                }
               },
              { data: 'FechaCaducidad',
                render: function(data, type, item) {
                  const fecha = data?.date;
                  return fecha ? new Date(fecha).toLocaleDateString() : '';
                }
               },
              { data: 'BaseNoObjIVA' },       
              { data: 'BaseImponible' },
              { data: 'BaseImpGrav' },
              { data: 'PorcentajeIva' },
              { data: 'MontoIva' },
              { data: 'BaseImpIce' },
              { data: 'PorcentajeIce' },
              { data: 'MontoIce' },
              { data: 'MontoIvaBienes' },
              { data: 'PorRetBienes' },
              { data: 'ValorRetBienes' },
              { data: 'MontoIvaServicios' },
              { data: 'PorRetServicios' },
              { data: 'ValorRetServicios' },
              { data: 'Cta_Servicio' },
              { data: 'Cta_Bienes' },
              { data: 'Porc_Bienes' },
              { data: 'Porc_Servicios' },
              { data: 'DocModificado' },
              { data: 'FechaEmiModificado',
                render: function(data, type, item) {
                  const fecha = data?.date;
                  return fecha ? new Date(fecha).toLocaleDateString() : '';
                }
               },
              { data: 'EstabModificado' },
              { data: 'PtoEmiModificado' },
              { data: 'SecModificado' },
              { data: 'AutModificado' },
              { data: 'ContratoPartidoPolitico' },
              { data: 'MontoTituloOneroso' },
              { data: 'MontoTituloGratuito' },
              { data: 'Item' },
              { data: 'CodigoU' },
              { data: 'A_No' },
              { data: 'T_No' },
              { data: 'PagoLocExt' },
              { data: 'PaisEfecPago' },
              { data: 'AplicConvDobTrib' },
              { data: 'PagExtSujRetNorLeg' },
              { data: 'FormaPago' },
              { data: 'Clave_Acceso_NCD' },
              { data: 'Devolucion' }            
            ],
            createdRow: function(row, data){
              alignEnd(row, data);
            }
          });
          tbl_asientosR = $('#tbl_asientoR').DataTable({
              searching: false,
              paging: false,   
              info: false,   
              autoWidth: false,
              destroy: true,
            language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            }, 
            data: ProcesarDatos(response.r.data), 
            scrollX: true, 
            scrollY: '150px', 
            scrollCollapse: true, 
            columns: [
              { data: null,
                render: function(data, type, row){
                  return data[0] || '';
                },
                orderable: false,
                className: 'text-center' },
              { data: 'CodRet' },
              { data: 'Detalle' },
              { data: 'BaseImp' },
              { data: 'Porcentaje' },
              { data: 'ValRet' },
              { data: 'EstabRetencion' },
              { data: 'PtoEmiRetencion' },
              { data: 'SecRetencion' },
              { data: 'AutRetencion' },
              { data: 'FechaEmiRet',
                render: function(data, type, item) {
                  const fecha = data?.date;
                  return fecha ? new Date(fecha).toLocaleDateString() : '';
                }
               },
              { data: 'Cta_Retencion' },
              { data: 'EstabFactura' },
              { data: 'PuntoEmiFactura' },
              { data: 'Factura_No' },
              { data: 'IdProv' },
              { data: 'Item' },
              { data: 'CodigoU' },
              { data: 'A_No' },
              { data: 'T_No' },
              { data: 'Tipo_Trans' },
            ],
            createdRow: function(row, data){
              alignEnd(row, data);
            }
          });
          if (response.datos[0]) {
            $('#Autorizacion_R').val(response.datos[0].AutRetencion); 
            $('#Serie_R').val(response.datos[0].EstabRetencion+''+response.datos[0].PtoEmiRetencion); 
            $('#Retencion').val(response.datos[0].SecRetencion);      
          }
      }
    });

}

function cargar_tablas_tab4()
{
  if ($.fn.dataTable.isDataTable('#tbl_av')) {
      $('#tbl_av').DataTable().clear().destroy();
  }
  if ($.fn.dataTable.isDataTable('#tbl_ae')) {
      $('#tbl_ae').DataTable().clear().destroy();
  }
  if ($.fn.dataTable.isDataTable('#tbl_ai')) {
      $('#tbl_ai').DataTable().clear().destroy();
  }
  $.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/contabilidad/incomC.php?tabs_tab4=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          const commonConfig = {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
          };   
          let tbl_av = $('#tbl_av').DataTable({
            ...commonConfig,
            searching: false,
            paging: false,   
            info: false,   
            destroy: true, 
            language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            }, 
            data: ProcesarDatos(response.AV.data), 
            columns: [
              { data: null,
                render: function(data, type, row){
                  return data[0] || '';
                },
                orderable: false,
                className: 'text-center' },
              { data: 'IdProv' },
              { data: 'TipoComprobante' },
              { data: 'FechaRegistro',
                render: function(data, type, item) {
                  const fecha = data?.date;
                  return fecha ? new Date(fecha).toLocaleDateString() : '';
                }
               },
              { data: 'Establecimiento' },
              { data: 'PuntoEmision' },
              { data: 'Secuencial' },
              { data: 'NumeroComprobantes' },
              { data: 'FechaEmision',
                render: function(data, type, item) {
                  const fecha = data?.date;
                  return fecha ? new Date(fecha).toLocaleDateString() : '';
                }
               },
              { data: 'BaseImponible' },
              { data: 'IvaPresuntivo' },
              { data: 'BaseImpGrav' },
              { data: 'PorcentajeIva' },
              { data: 'MontoIva' },
              { data: 'BaseImpIce' },
              { data: 'PorcentajeIce' },
              { data: 'MontoIce' },
              { data: 'MontoIvaBienes' },
              { data: 'PorRetBienes' },
              { data: 'ValorRetBienes' },
              { data: 'MontoIvaServicios' },
              { data: 'PorRetServicios' },
              { data: 'ValorRetServicios' },
              { data: 'RetPresuntiva' },
              { data: 'TP' },
              { data: 'Cta_Servicio' },
              { data: 'Cta_Bienes' },
              { data: 'Numero' },
              { data: 'Item' },
              { data: 'CodigoU' },
              { data: 'A_No' },
              { data: 'T_No' },
              { data: 'Porc_Bienes' },
              { data: 'Porc_Servicios' },
              { data: 'Tipo_Pago' }
            ], 
            createdRow: function(row, data){
              alignEnd(row, data);
            }
          });
          let tbl_ae = $('#tbl_ae').DataTable({
            ...commonConfig,
            searching: false,
            paging: false,   
            info: false,   
            destroy: true,
            language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            }, 
            data: ProcesarDatos(response.AE.data),
            columns: [
              { data: null,
                render: function(data, type, row){
                  return data[0] || '';
                },
                orderable: false,
                className: 'text-center' },
              { data: 'Codigo' },
              { data: 'CtasxCobrar' },
              { data: 'ExportacionDe' },
              { data: 'TipoComprobante' },
              { data: 'FechaEmbarque',
                render: function(data, type, item) {
                  const fecha = data?.date;
                  return fecha ? new Date(fecha).toLocaleDateString() : '';
                }
               },
              { data: 'NumeroDctoTransporte' },
              { data: 'IdFiscalProv' },
              { data: 'ValorFOB' },
              { data: 'DevIva' },
              { data: 'FacturaExportacion' },
              { data: 'ValorFOBComprobante' },
              { data: 'DistAduanero' },
              { data: 'Anio' },
              { data: 'Regimen' },
              { data: 'Correlativo' },
              { data: 'Verificador' },
              { data: 'Establecimiento' },
              { data: 'PuntoEmision' },
              { data: 'Secuencial' },
              { data: 'Autorizacion' },
              { data: 'FechaEmision',
                render: function(data, type, item) {
                  const fecha = data?.date;
                  return fecha ? new Date(fecha).toLocaleDateString() : '';
                }
               },
              { data: 'FechaRegistro',
                render: function(data, type, item) {
                  const fecha = data?.date;
                  return fecha ? new Date(fecha).toLocaleDateString() : '';
                }
               },
              { data: 'Item' },
              { data: 'CodigoU' },
              { data: 'A_No' },
              { data: 'T_No' }
            ],
            createdRow: function(row, data){
              alignEnd(row, data);
            }
          });
          let tbl_ai = $('#tbl_ai').DataTable({
            ...commonConfig,
            searching: false,
            paging: false,   
            info: false,   
            destroy: true,
            language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            }, 
            data: ProcesarDatos(response.AI.data),
            columns: [ 
              { data: null,
                render: function(data, type, row){
                  return data[0] || '';
                },
                orderable: false,
                className: 'text-center' },
              { data:'CodSustento' },
              { data:'ImportacionDe' },
              { data:'FechaLiquidacion',
                render: function(data, type, item) {
                  const fecha = data?.date;
                  return fecha ? new Date(data).toLocaleDateString() : '';
                }
               },
              { data:'TipoComprobante' },
              { data:'DistAduanero' },
              { data:'Anio' },
              { data:'Regimen' },
              { data:'Correlativo' },
              { data:'Verificador' },
              { data:'IdFiscalProv' },
              { data:'ValorCIF' },
              { data:'BaseImponible' },
              { data:'BaseImpGrav' },
              { data:'PorcentajeIva' },
              { data:'MontoIva' },
              { data:'BaseImpIce' },
              { data:'PorcentajeIce' },
              { data:'MontoIce' },
              { data:'Item' },
              { data:'CodigoU' },
              { data:'A_No' },
              { data:'T_No' },
            ],
            createdRow: function(row, data){
              alignEnd(row, data);
            }
          });
      }
    });

}

function cargar_totales_aseintos()
{
  
  $.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/contabilidad/incomC.php?totales_asientos=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 

        // console.log(response);     
        $('#txt_diferencia').val(response.diferencia.toFixed(2));  
        $('#txt_debe').val(response.debe.toFixed(2));  
        $('#txt_haber').val(response.haber.toFixed(2));  
        $('#txt_cta_modificar').val(response.Ctas_Modificar);  
      }
    });

}

function ingresar_asiento()
{
  var partes = '';
  if($('#cuentar option:selected').text().length > 0){
    partes = $('#cuentar option:selected').text();
  }else{
    partes = $('#aux').val();
  }
  var bene = $('#beneficiario1').val();
  var partes = partes.split('-');
  var dconcepto1 = partes[1].trim();
  var codigo = $("#codigo").val();
  var efectivo_as = $("#txt_efectiv").val();
  var chq_as = $("#txt_cheq_dep").val();
  var moneda = $("#txt_moneda").val();
  var cotizacion = $("#cotizacion").val();
  var con = $("#con").val();
  var tipo_cue = $("#txt_tipo").val();
  var valor = $('#va').val();
  if(moneda==2)
  {
    Swal.fire({type: 'error', title: 'Oops...', text: 'No se puede agregar cotizacion vacia o cero!'});
  }

  var parametros = 
    {
      "va" : valor,
      "dconcepto1" : '.',
      "codigo" : codigo,
      "cuenta" : dconcepto1,
      "efectivo_as" : efectivo_as,
      "chq_as" : chq_as,
      "moneda" : moneda,
      "tipo_cue" : tipo_cue,
      "cotizacion" : cotizacion,
      "con" : con,
      "t_no" : '1',
      "bene":bene,
      "ajax_page": 'ing1',                        
    };
  $.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/contabilidad/incomC.php?ing1=true',
    type:  'post',
    dataType: 'json',
    // beforeSend: function () {
    //    $("#tab1default").html("");
    // },
    success:  function (response) {

      // console.log(response.resp);
      if(response.resp==1)
      {
        cargar_tablas_contabilidad();
        cargar_totales_aseintos();
        $('#codigo').val('');      
        $('#cuentar').empty();
        $('#va').val('0.00');  
        $('#codigo').select();  
        $('#aux').val('');              
      }else if(response.resp==-2)
      {
        Swal.fire('Puede ser que ya exista un registro','','info');
      } else if(response.resp==-3)
      {
        Swal.fire(response.obs,'','warning');
      }                 
    }
  });
}

function subcuenta_frame()
{
  var deha = $('#txt_tipo').val();
  var moneda = $('#txt_moneda').val();
  if(deha=='' || moneda=='')
  {
    return false;
  }

  var tipo = $('#txt_subcta').val();
  var cta = $('#txt_codigo').val();
  var tipoc = $('#tipoc').val();
  $('#modal_cuenta').modal('hide');
  if(tipo == 'C' || tipo =='P' || tipo == 'G' || tipo=='I' || tipo=='PM' || tipo=='CP')
  {
    titulos(tipo);
    var src ="../vista/modales.php?FSubCtas=true&mod=&tipo_subcta="+tipo+"&OpcDH="+deha+"&OpcTM="+moneda+"&cta="+cta+"&tipoc="+tipoc+"&fecha="+$('#fecha1').val()+"#";
    $('#modal_subcuentas').modal('show');
    $('#titulo_frame').text('Ingreso de sub cuenta por cobras');
    $('#frame').attr('src',src).show();
      adjustIframeHeight(300);
  }else if(tipo=="CC")
  {
    $('#modal_CC').modal('show');
    $('#titulo_frame_cc').text('Ingresar Subcuentas de Proceso');
    var tmp = $('#codigo').val();
    $('#titulo_aux').text('CENTRO DE COSTOS PARA: ' + tmp + " - " + $('#txt_cuenta').val());
      load_subcuentas();
  }else
  {
    cambia_foco();
  }
}

function titulos(tc)
{
  switch(tc) {
    case 'C':
        $('#titulo_frame').text("Ingreso se Subcuenta por Cobrar");
      break;
    case 'P':
        $('#titulo_frame').text("Ingreso se Subcuenta por Pagar");
      break;
      case 'G':
        $('#titulo_frame').text("Ingreso se Subcuenta de Gastos");
      break;
      case 'I':
        $('#titulo_frame').text("Ingreso se Subcuenta de Ingreso");
      break;
      case 'CP':
        $('#titulo_frame').text("Ingreso se Subcuenta por Cobrar");
      break;
      case 'PM':
        $('#titulo_frame').text("Ingreso se Subcuenta de Ingreso");
        break;
}
}

function load_subcuentas()
{
  var tmp = $('#cuentar option:selected').text();
  $('#aux').val(tmp);
  if ($('#myTable tbody tr').length > 0) {
      // La tabla ya tiene datos, por lo que no hacemos nada
      return;
  }
  parametros = {
    'SubCtaGen':$('#codigo').val(),
    'SubCta':"CC",
    'OpcTM':$('#txt_moneda').val(),
    'OpcDH':$('#txt_tipo').val()
  }
  $.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/contabilidad/incomC.php?load_subcuentas=true',
    type:  'post',
    dataType: 'json',
    success:  function (data) {
        $('#tablaContenedor').html(data);
        table_lost_focus();
        get_cell_focus();
    }
  });
}

function table_lost_focus(){
  $('#myTable td[contenteditable="true"]').on('focusout', function(){
    var suma = 0;
    $('#myTable td[contenteditable="true"]').each(function() {
      var valor = parseFloat($(this).text()) || 0; // Convertir el texto a número y asegurar que sea 0 si no es numérico
      suma += valor;
    });
    $('#total_cc').val(suma.toFixed(2));
  });
}

function get_cell_focus(){
  $('#myTable').on('focus', 'td[contenteditable="true"]', function() {
      var $this = $(this);
      setTimeout(function() {
          var range = document.createRange();
          var selection = window.getSelection();
          range.selectNodeContents($this.get(0));
          selection.removeAllRanges();
          selection.addRange(range);
      }, 1);
  });

  // Mover el foco a la siguiente celda editable cuando se presiona Tab
  $('#myTable').on('keydown', 'td[contenteditable="true"]', function(e) {
      if (e.keyCode === 9) { // Tecla Tab
          e.preventDefault(); // Prevenir el comportamiento predeterminado
          var $next = $(this).next('td[contenteditable="true"]');
          if ($next.length) {
              $next.focus(); // Mover el foco a la siguiente celda editable
          } else {
              // Si es la última celda de la fila, moverse a la primera celda de la siguiente fila
              var $nextRowFirstCell = $(this).closest('tr').next().find('td[contenteditable="true"]').first();
              if ($nextRowFirstCell.length) {
                  $nextRowFirstCell.focus();
              } else {
                  // Si es la última celda de la última fila, volver a la primera celda de la tabla
                  $('#myTable td[contenteditable="true"]').first().focus();
              }
          }
      }
  });
}

function Commandl_Click(){
  parametros = {
    'SubCtaGen':$('#codigo').val(),
    'SubCta':"CC",
    'OpcTM':$('#txt_moneda').val(),
    'OpcDH':$('#txt_tipo').val()
  }
  $.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/contabilidad/incomC.php?Commandl_Click=true',
    type:  'post',
    dataType: 'json',
    success:  function (data) {
      $('#modal_CC').modal('hide');
      var SumatoriaSC = $('#total_cc').val();
      $('#va').val(SumatoriaSC);
      // $('#va').focus();
      // $('#cuentar').empty();        
      // $('#codigo').val('');
      ingresar_asiento();
    }
  });
}



function Command2_Click(){
  parametros = {
    'SubCtaGen':$('#codigo').val(),
    'SubCta':"CC",
    'OpcTM':$('#txt_moneda').val(),
    'OpcDH':$('#txt_tipo').val()
  }
  $.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/contabilidad/incomC.php?Command2_Click=true',
    type:  'post',
    dataType: 'json',
    success:  function (data) {
      $('#modal_CC').modal('hide');
      $('#cuentar').empty();
      //console.log($('#aux').val());
    }
  });
}



function recarar()
{
  cargar_tablas_contabilidad();
  cargar_tablas_tab4();
  cargar_tablas_retenciones();
  cargar_tablas_sc();
}
function cargar_modal()
{
  var cod = $('#codigo').val();
  $('#tablaContenedor').html('');
  $('#total_cc').val('0.00');
  switch(cod) {
    case 'AC':
    case 'ac':
        var prv = '000000000';
        var ben = '.';
        if($('#beneficiario1').val()!='')
        {
          prv = $('#ruc').val();
          ben = $('#beneficiario1 option:selected').text();
        }else
        {
          Swal.fire('Seleccione beneficiario','','info');
          return false;
        }
        eliminar_ac();
          //borrar_asientos();
        $('#titulo_frame').text("COMPRAS");
      
        var fec = $('#fecha1').val();
        var opc_mult = $('#con').val();
        var src ="../vista/modales.php?FCompras=true&mod=&prv="+prv+"&ben="+ben+"&fec="+fec+"&opc_mult="+opc_mult+"&tipo=";
        $('#frame').attr('src',src).show();

        // $('#frame').css('height','100%').show();
        adjustIframeHeight();

        $('#modal_subcuentas').modal('show');
      break;
    case 'AV':
    case 'av':
        var prv = '000000000';
        var ben = '.';
        if($('#beneficiario1').val()!='')
        {
          prv = $('#ruc').val();
          ben = $('#beneficiario1 option:selected').text();
        }
        var fec = $('#fecha1').val();
        var src ="../vista/modales.php?FVentas=true&mod=&prv="+prv+"&ben="+ben+"&fec="+fec+"#";
        $('#frame').attr('src',src).show();

        // $('#frame').css('height','100%').show();
        adjustIframeHeight();

        $('#titulo_frame').text("VENTAS");
        $('#modal_subcuentas').modal('show');
      break;
      case 'AI':
      case 'ai':
        var src ="../vista/modales.php?FImportaciones#";
        $('#frame').attr('src',src).show();
        // $('#frame').css('height','450px').show();
        adjustIframeHeight(); 
        $('#titulo_frame').text("IMPORTACIONES");
        $('#modal_subcuentas').modal('show');
      break;
      case 'AE':
      case 'ae':
      var src ="../vista/modales.php?FExportaciones#";
        $('#frame').attr('src',src).show();
        // $('#frame').css('height','500px').show();

        adjustIframeHeight();
        $('#titulo_frame').text("EXPORTACIONES");
        $('#modal_subcuentas').modal('show');

      break;
      default:
          $('#cuentar').select2('open');
      break;
  }
}

  // function validar_comprobante()
  // {

  //   numero_comprobante();
  //   var debe =$('#txt_debe').val();
  //   var haber = $('#txt_haber').val(); 
  //   var ben = $('#beneficiario1').val();
  //   var fecha = $('#fecha1').val();
  //   var tip = $('#tipoc').val();
  //   var ruc = $('#ruc').val();
  //   var concepto = $('#concepto').val();
  //   var haber = $('#txt_haber').val();
  //   var com = $('#num_com').text();
  //    var modificar = '<?php echo $NuevoComp; ?>';
  //   // var comprobante = com.split('.');
  //   if((debe != haber) || (debe==0 && haber==0) )
  //   {
  //     Swal.fire( 'Las transacciones no cuadran correctamente corrija los resultados de las cuentas','','info');
  //     return false;
  //   }
  //   if(ben =='')
  //   {      
  //     ben = '.';
  //   }

  //   var parametros = 
  //   {
  //     'ruc': ruc, //codigo del cliente que sale co el ruc del beneficiario codigo
  //     'tip':tip,//tipo de cuenta contable cd, etc
  //     "fecha": fecha,// fecha actual 2020-09-21
  //     'concepto':concepto, //detalle de la transaccion realida
  //     'totalh': haber, //total del haber
  //     'num_com':com,
  //     'CodigoB':$('#ruc').val(),
  //     'Serie_R':$('#Serie_R').val(),
  //     'Retencion':$('#Retencion').val(),
  //     'Autorizacion_R':$('#Autorizacion_R').val(),
  //     'Autorizacion_LC':$('#Autorizacion_LC').val(),
  //     'TD':'C',
  //     'bene':$('select[name="beneficiario1"] option:selected').text(),
  //     'email':$('#email').val(),
  //     'Cta_modificar':$('#txt_cta_modificar').val(),
  //     'T':'N',
  //     'monto_total':$('#VT').val(),
  //     'Abono':$('#vae').val(),
  //     'TextCotiza':$("#cotizacion").val(),
  //     'NuevoComp':modificar,
  //   }


  //   // console.log(parametros);
  //   // return false;

  //   Swal.fire({
  //     title: "Esta seguro de Grabar el "+$('#num_com').text(),
  //     text: "con fecha: "+$('#fecha1').val(),
  //     type: 'warning',
  //     showCancelButton: true,
  //     confirmButtonColor: '#3085d6',
  //     cancelButtonColor: '#d33',
  //     confirmButtonText: 'Si!'
  //   }).then((result) => {
  //     if (result.value==true) {
  //        grabar_comprobante(parametros);
  //     }else
  //     {
  //       // alert('cancelado');
  //     }
  //   })
  // }
function grabar_comprobante(parametros)
{  

  if($('#beneficiario1').val()=='')
  {
    Swal.fire('seleccione un beneficiario','','info')
    return false;
  }    
      $('#myModal_espera').modal('show');  
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/contabilidad/incomC.php?generar_comprobante=true',
        type:  'post',
        dataType: 'json',
          success:  function (response) {
              $('#myModal_espera').modal('hide');
      if(response.respuesta == '3')
      {
        Swal.fire('Este documento electronico ya esta autorizado','','error');

        }else if(response.respuesta == 1)
        {
          // Swal.fire('Este documento electronico autorizado','','success');
            eliminar_ac();
            var texto ="";
            var tipo ="success";
            if(response.aut_res==1){texto = ' y Documento electronico Autorizado'; tipo ='success'; }
            if (response.aut_res==2) { tipo_error_sri(response.clave); texto = ' y Documento electronico No autorizado'; tipo = 'warning';
          }
            Swal.fire( ((parametros.NuevoComp==0)?'Comprobante Modificado '+texto: "Comprobante Generado "+texto),"",tipo).then(function(){ 
            eliminar_todo_asisntoB();
            cargar_tablas_contabilidad();
            cargar_tablas_tab4();
            cargar_tablas_retenciones();
            cargar_tablas_sc();
            numero_comprobante();
            url = "../controlador/contabilidad/comproC.php?reporte&comprobante="+response.NumCom+"&TP="+parametros['tip'];
            window.open(url,"_blank");

            });
                        
        }else if(response.respuesta == '2')
        {
          Swal.fire('XML devuelto','','info',);
          descargar_archivos(response.url,response.ar);

        }else if(response.respuesta == '-2')
        {
          Swal.fire('Falta de Ingresar datos.','','info',);
        }
        else
        {
          Swal.fire('Error por: '+response.respuesta,'','info');
        }

        // if(response.respuesta==1 || response==1)
        // {
        //    Swal.fire('Retencion ingresada','','success');
        //    eliminar_todo_asisntoB();
        //    cargar_tablas_contabilidad();
        //    cargar_tablas_tab4();
        //    cargar_tablas_retenciones();
        //    cargar_tablas_sc();
        //    numero_comprobante();
        //    url = "../controlador/contabilidad/comproC.php?reporte&comprobante=1000195&TP=CD";
        //    window.open(url,"_blank");
        // }           

        }
      });
}

function descargar_archivos(url,archivo)
{

      // <a href="../comprobantes/entidades/entidad_001/CE_001/No_autorizados/1006202107179033180600110010011220000001234567815.xml" download="1006202107179033180600110010011220000001234567815.xml"><span class="info-box-text">
      //    Aplicacion PUCE para android</span>
      //    </a>
    var url1 = url+archivo;

    // console.log(url1);
          var link = document.createElement("a");
          link.download = archivo;
          link.href =url1;
          link.click();            
}

function eliminar_ac()
{
  $.ajax({
        // data:  {parametros:parametros},
        url:   '../controlador/contabilidad/incomC.php?eliminar_retenciones=true',
        type:  'post',
        dataType: 'json',
          success:  function (response) { 
            if(response==1)
            {
              
            }else
            {
              Swal.fire( 'No se pudo Eliminar','','error');
            }

        }
      });

}

function borrar_asientos()
{
  $.ajax({
        // data:  {parametros:parametros},
        url:   '../controlador/contabilidad/incomC.php?borrar_asientos=true',
        type:  'post',
        dataType: 'json',
          success:  function (response) { 
            if(response==1)
            {
              cargar_tablas_contabilidad();
              cargar_tablas_retenciones();
              cargar_tablas_sc();
              cargar_tablas_tab4();
              cargar_totales_aseintos();                
            }else
            {
              Swal.fire( 'No se pudo Eliminar','','error');
            }

        }
      });

}



function eliminar(codigo,tabla,ID)
{
    var parametros = 
  {
    'tabla':tabla,
    'Codigo':codigo,
    'ID':ID,
  }
  //console.log(parametros);

  Swal.fire({
    title: 'Esta seguro de eliminar este registro',
    text: "",
    type: 'info',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'OK!'
  }).then((result) => {
    if (result.value==true) {
      $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/contabilidad/incomC.php?eliminarregistro=true',
        type:  'post',
        dataType: 'json',
          success:  function (response) { 
            if(response==1)
            {
                cargar_tablas_contabilidad();
                cargar_tablas_tab4();
                cargar_tablas_retenciones();
                cargar_tablas_sc();
                cargar_totales_aseintos();
            }

        }
      });                    
    }
  });
}

function ListarAsientoB()
{
  if($.fn.dataTable.isDataTable('#div_tabla')){
    $('#div_tabla').DataTable().destroy(); 
  }
  tbl_div_table = $('#div_tabla').DataTable({
    language:{
      url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'    
    },
    ajax: {
      url: '../controlador/contabilidad/incomC.php?ListarAsientoB=true',
      type: 'get',
      dataSrc: function(response){
        response.data = ProcesarDatos(response.data);
        return response.data;
      }
    }, 
    scrollY: '300px',
    scrollCollapse: true,
    scrollX: true,
    searching: false,
    info: false,
    paging: false,
    columns: [
      { data: null,
        render: function(data, type, row){
          return data[0] || '';
        },
        orderable: false,
        className: 'text-center'
       },
      { data: 'CTA_BANCO' },
      { data: 'BANCO' },
      { data: 'CHEQ_DEP' },
      { data: 'EFECTIVIZAR',
        render: function(data, type, item) {
          const fecha = data?.date;
          return fecha ? new Date(fecha).toLocaleDateString() : '';
        }
       },
      { data: 'VALOR' },
      { data: 'ME' },
      { data: 'T_No' },
      { data: 'Item' },
      { data: 'CodigoU' }
    ],
    createdRow: function(row, data){
      alignEnd(row, data);
    }
  })

}

function Llenar_Encabezado_Comprobante()
{
    var parametros = $('#NuevoComp').val();
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/contabilidad/incomC.php?Llenar_Encabezado_Comprobante=true',
        type:  'post',
        dataType: 'json',
          success:  function (response) { 
            // console.log(response);
            $('#beneficiario1').append($('<option>',{value: response.CodigoB, text:response.beneficiario,selected: true }));
            $('#ruc').val(response.RUC_CI);
            $('#concepto').val(response.Concepto);
            $('#email').val(response.email);
            $('#fecha1').val(response.fecha);

        }
      });

}
function listar_comprobante()
{
  var parametros = $('#NuevoComp').val();
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/contabilidad/incomC.php?listar_comprobante=true',
        type:  'post',
        dataType: 'json',
          success:  function (response) { 

            // console.log(response);     
          $('#div_tabla').html(response);  
        }
      });
}



function saltar()
{
  valor = $('#txt_moneda').val();
  hd = $('#txt_tipo').val();
  if(valor=='')
  {
    $('#txt_moneda').select();
  }
  if(hd=='')
  {
    $('#txt_moneda').select();
  }

  if(valor!='' && hd!='')
  {
    subcuenta_frame();
  }
}

function adjustIframeHeight(medida=false) {
  var iframe = window.parent.document.getElementById('frame'); // Reemplaza 'miIframe' con el ID de tu iframe
  menos = 200;
  if(medida)
  {
      menos = medida;
  }
  if (iframe) {
    iframe.style.height = (document.documentElement.scrollHeight-menos) + 'px';
  }
}

function DCBanco_LostFocus()
{
  var CBanco = $('#conceptob').val();
  var parametros = {
    'CBanco':CBanco,
    'MBoxFecha':$('#fecha1').val(),
  }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/contabilidad/incomC.php?DCBanco_LostFocus=true',
        type:  'post',
        dataType: 'json',
          success:  function (response) { 
            $('#no_cheq').val(response);
            //console.log(response);     
        }
      });
  
}

function salir_todo()
{
  alert('ccera')
}

function Num_Nuevo_Comp(tip, fecha, callback){
  var parametros = {
    'tip': tip,
    'fecha': fecha
  }
  $.ajax({
    data: {parametros:parametros},
    url: '../controlador/contabilidad/incomC.php?generar_num_comp=true',
    type: 'post',
    dataType: 'json',
    success: function(response){
      callback(response);
    },
    error: function(error, xhr, status){
      console.error("Error en la solicitud: "+error);
      callback(null);
    }
  })
}

function Disable_Buttons(){
  ids = ['CD', 'CI', 'CE', 'ND', 'NC'];
  ids.forEach(id=>{
    document.getElementById(id).classList.add("disable");
    document.getElementById(id).disabled = true; 
  });
}