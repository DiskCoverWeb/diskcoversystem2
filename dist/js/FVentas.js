$(document).ready(function()
  {    
    ddl_DCRetIBienes();
    ddl_DCRetISer();
    ddl_DCRetFuente();
    ddl_DCTipoPago();
    cargar_grilla();
  });

 function ddl_DCRetIBienes() {
    var opcion = '<option value="">Seleccione tipo de retencion</option>';
    $.ajax({
      //data:  {parametros:parametros},
      url:   '../controlador/contabilidad/FVentasC.php?DCRetIBienes=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
            $.each(response,function(i,item){
                opcion+='<option value="'+item.Codigo+'">'+item.Cuentas+'</option>';
            })
            $('#DCRetIBienes').html(opcion);
                    // console.log(response);
      }
    }); 
}

function limpiar_retencion()
{
  $('#modal_cuenta').modal('hide');
   window.parent.postMessage('closeModal', '*');
}


function ddl_DCRetISer() {
    var opcion = '<option value="">Seleccione tipo de retencion</option>';
    $.ajax({
      //data:  {parametros:parametros},
      url:   '../controlador/contabilidad/FVentasC.php?DCRetISer=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
            //console.log(response);
            $.each(response,function(i,item){
                opcion+='<option value="'+item.Codigo+'">'+item.Cuentas+'</option>';
            })
            $('#DCRetISer').html(opcion);
                    // console.log(response);
      }
    }); 
}

function habilitar_bienes()
{
  if($('#ChRetB').prop('checked'))
  {
    $('#TxtIvaBienMonIva').prop('readonly', false);
    $('#TxtIvaBienValRet').prop('readonly', false); 
    $('#DCPorcenRetenIvaBien').prop('disabled', false);

  }else
  {
    $('#TxtIvaBienMonIva').prop('readonly', true);
    $('#TxtIvaBienValRet').prop('readonly', true); 
    $('#DCPorcenRetenIvaBien').prop('disabled', true);

  }
}

function habilitar_servicios()
{
  if($('#ChRetS').prop('checked'))
  {
    $('#TxtIvaSerMonIva').prop('readonly', false);
    $('#TxtIvaSerValRet').prop('readonly', false); 
    $('#DCPorcenRetenIvaServ').prop('disabled', false);

  }else
  {
    $('#TxtIvaSerMonIva').prop('readonly', true);
    $('#TxtIvaSerValRet').prop('readonly', true); 
    $('#DCPorcenRetenIvaServ').prop('disabled', true);

  }
}

function ddl_DCPorcenIvaV(fecha) {
    var opcion = '<option value="0">0</option>';
    var parametros = 
    {
        'fecha':fecha,
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/contabilidad/FVentasC.php?DCPorcenIvaV=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        //  console.log(response);
            $.each(response,function(i,item){
                opcion+='<option value="'+item.Porc+'">'+item.Porc+'</option>';
            })
            $('#DCPorcenIvaV').html(opcion);
                    // console.log(response);
      }
    }); 
}

function ddl_DCPorcenIceV(fecha) {
    var opcion = '<option value="0">0</option>';
    var parametros = 
    {
        'fecha':fecha,
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/contabilidad/FVentasC.php?DCPorcenIceV=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
            //console.log(response);
            $.each(response,function(i,item){
                opcion+='<option value="'+item.Porc.toFixed(2)+'">'+item.Porc.toFixed(2)+'</option>';
            })
            $('#DCPorcenIceV').html(opcion);
                    // console.log(response);
      }
    }); 
}

function calcular_iva()
{
  var iva = $('#DCPorcenIvaV').val();
  var Total_IVA = $('#TxtBaseImpGravV').val();
  if(iva != 'I')
  {
    valor = (Total_IVA*iva)/100;
    $('#TxtMontoIvaV').val(valor.toFixed(2));
  }else
  {
     $('#TxtMontoIvaV').val(0);
  }
}

function calcular_ice()
{
  var ice = $('#DCPorcenIceV').val();
  var Total_ice = $('#TxtBaseImpoIceV').val();
  if(ice != 'I')
  {
    valor = (Total_ice*ice)/100;
    $('#TxtMontoIceV').val(valor);
  }else
  {
     $('#TxtMontoIceV').val(0);
  }
  if($('#ChRetB').prop('checked')==false && $('#ChRetS').prop('checked')==false)
  {
    $('#TxtIvaBienMonIva').val($('#TxtMontoIvaV').val());
  }
  if($('#ChRetB').prop('checked'))
  {
    $('#TxtIvaBienMonIva').val($('#TxtMontoIvaV').val());
    $('#TxtIvaSerMonIva').val(0);
  }else{
   if($('#ChRetS').prop('checked'))
  {
    $('#TxtIvaBienMonIva').val(0);
    $('#TxtIvaSerMonIva').val($('#TxtMontoIvaV').val());
  }
 }
}


function mostra_select()
{
    if($('#ChRetF').prop('checked'))
    { 
    $('#lbl_rbl').css('border','0px');
        $('#DCRetFuente').show();
    }else{
        $('#DCRetFuente').hide();
    }
}



function ddl_DCRetFuente() {
    var opcion = '<option value="">Seleccione tipo de Retencion</option>';
    $.ajax({
      //data:  {parametros:parametros},
      url:   '../controlador/contabilidad/FventasC.php?DCRetFuente=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
            //console.log(response);
            $.each(response,function(i,item){
                opcion+='<option value="'+item.Codigo+'">'+item.Cuentas+'</option>';
            })
            $('#DCRetFuente').html(opcion);
                    // console.log(response);
      }
    }); 
}

function ddl_DCConceptoRet(fecha) {
    var opcion = '<option value="">Seleccione Codigo de retencion</option>';
    var parametros = 
    {
        'fecha':fecha,
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/contabilidad/FVentasC.php?DCConceptoRet=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
            console.log(response);
            $.each(response,function(i,item){
                opcion+='<option value="'+item.Codigo+'-'+item.Porc+'">'+item.Detalle_Conceptos+'</option>';
            })
            $('#DCConceptoRet').html(opcion);
                    // console.log(response);
      }
    }); 
}
function ddl_DCTipoPago() {
    var opcion = '<option value="">Seleccione tipo de pago</option>';
    $.ajax({
      //data:  {parametros:parametros},
      url:   '../controlador/contabilidad/FVentasC.php?DCTipoPago=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
            //console.log(response);
            $.each(response,function(i,item){
                opcion+='<option value="'+item.Codigo+'">'+item.CTipoPago+'</option>';
            })
            $('#DCTipoPago').html(opcion);
                    // console.log(response);
      }
    }); 
}
function validar_num_retencion()
{
  var TxtSuma = 0;
  var le = $('#TxtNumTresComRet').val().length;
  var v = $('#TxtNumTresComRet').val();
   if($('#TxtNumTresComRet').val() <= 0 || $('#TxtNumTresComRet').val()=="")
    {
      $('#TxtNumTresComRet').val("000000001");
    }else
    {

    while(v.length < 9)
    {
      v = '0'+v;
    }
    $('#TxtNumTresComRet').val(v);
  }

   // var TxtNumTresComRet = Format(Round(Val(TxtNumTresComRet)), "000000000")
  // 'Calcula la sumatoria de Monto Iva Bienes, Monto Iva Servicios y Base Imponible
  TxtSuma = parseFloat($('#TxtBaseImpV').val())+parseFloat($('#TxtBaseImpGravV').val());
  $('#TxtSumatoria').val(TxtSuma);
  $('#TxtBimpConA').val(TxtSuma);
  // 'TxtSumatoria = TxtBaseImpoGrav 
}

function solo_3_numeros(id)
{  
  var v = $('#'+id).val();
  if(v.length >3)
  {
   val  = v.substr(0,3);
    $('#'+id).val(val);
  }else{
    $('#'+id).val(v);
  }
}


function calcular_porc_ret()
{
  $('#DCConceptoRet').css('border','1px solid #d2d6de');
  var valor =$('#DCConceptoRet').val();
  valor = valor.split('-');
  valor = valor[1];
  var t = $('#TxtBimpConA').val();
  $('#TxtPorRetConA').val(valor);
  var tt = (t*valor)/100;
  $('#TxtValConA').val(tt);

}
function autocompletar_serie_num(id)
{
  var v = $('#'+id).val();
  if($('#'+id).val()<=0 || $('#'+id).val()=="")
  {
     $('#'+id).val("001");
  }else
  {
     while(v.length < 3 )
    {
      v = '0'+v;
    }
    $('#'+id).val(v);
  }
}
function solo_9_numeros(id)
{  
  var v = $('#'+id).val();
  if(v.length >9)
  {
   val  = v.substr(0,9);
    $('#'+id).val(val);
  }else{
    $('#'+id).val(v);
  }
}

function insertar_grid()
{
  if($.fn.dataTable.isDataTable('#tbl_retencion')){
    $('#tbl_retencion').DataTable().clear().destroy();
    $('#tbl_retencion').empty();
  }
  var nom = $('select[name="DCConceptoRet"] option:selected').text().split('-');
  console.log(nom);
  var  parametros= 
  {
      "CodRet": nom[0].trim(),
      "Detalle": nom[1],
      "BaseImp":$('#TxtBimpConA').val(), // CTNumero 2
      "Porcentaje":$('#TxtPorRetConA').val(), // CTNumero 2
      "ValRet": $('#TxtValConA').val(), // CTNumero 2
      "EstabRetencion": $('#TxtNumUnoComRet').val(),
      "PtoEmiRetencion": $('#TxtNumDosComRet').val(),
      "SecRetencion": $('#TxtNumTresComRet').val(),
      "AutRetencion": $('#TxtNumUnoAutComRet').val(),
      "FechaEmiRet":$('#txt_fecha').val(),
      "Cta_Retencion": $('#DCRetFuente').val(),
      "EstabFactura": $('#TxtNumSerieUno').val(),
      "PuntoEmiFactura":  $('#TxtNumSerieDos').val(),
      "Factura_No":  $('#TxtNumSerietres').val(),
      "IdProv": $('#DCProveedor').val(), //revisar el id
      "T_No": '1',
      "Tipo_Trans":"V",
  }
   $.ajax({
      data:  {parametros:parametros},
     url:   '../controlador/inventario/registro_esC.php?Insertar_DataGrid=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
            if (response==1) 
            {
              cargar_grilla();          
            }
         
      }
    });
}

function cambiar_air()
{
  //$('a[href="#menu1"]').click();
  var tab = new bootstrap.Tab(document.querySelector('a[href="#menu1"'));
  tab.show();
}

function cargar_grilla()
{
  if($.fn.dataTable.isDataTable('#tbl_retencion_ventas')){
    $('#tbl_retencion_ventas').DataTable().clear().destroy();
    $('#tbl_retencion_ventas').empty();
  }
  var  parametros= 
  {
    'Trans_No':'1',
  }
   $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/contabilidad/FVentasC.php?Cargar_DataGrid=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
            if (response!='') 
            {
              $('#tbl_retencion_ventas').DataTable({
                language:{
                  url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                }, 
                data: response.data, 
                columns: [
                  {data: 'CodRet', title: 'CodRet'},
                  {data: 'Detalle', title: 'Detalle'},
                  {data: 'BaseImp', title: 'BaseImp'},
                  {data: 'Porcentaje', title: 'Porcentaje'},  
                  {data: 'ValRet', title: 'ValRet'},
                  {data: 'EstabRetencion', title: 'EstabRetencion'},
                  {data: 'PtoEmiRetencion', title: 'PtoEmiRetencion'},
                  {data: 'SecRetencion', title: 'SecRetencion'},
                  {data: 'AutoRetencion', title: 'AutoRetencion'},
                  {data: 'FechaEmiRet', title: 'FechaEmiRet',
                    render: function(data, type, item) {
                      const fecha = data?.date;
                      return fecha ? new Date(fecha).toLocaleDateString() : '';
                    }
                  },
                  {data: 'Cta_Retencion', title: 'Cta_Retencion'},
                  {data: 'EstabFactura', title: 'EstabFactura'},
                  {data: 'PuntoEmiFactura', title: 'PuntoEmiFactura'},
                  {data: 'Factura_No', title: 'Factura_No'},
                  {data: 'IdProv', title: 'IdProv'},
                  {data: 'Item', title: 'Item'},
                  {data: 'codigoU', title: 'codigoU'},
                  {data: 'A_No', title: 'A_No'},
                  {data: 'T_no', title: 'T_no'},
                  {data: 'Tipo_Trans', title: 'Tipo_Trans'}
                 ],
                scrollX: true, 
                scrollY: '300px',
                searching: false,
                info: false,
                paging: false,
                destroy: true
              });          
            }
         
      }
    });
}  

function eliminar_air()
{
  var  parametros= 
  {
    'Trans_No':'1',
  }
   $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/contabilidad/FVentasC.php?eliminar_air=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
            if (response!='') 
            {
              cargar_grilla();
            }
         
      }
    });
}  


function grabacion()
{

    $('#myModal_espera').modal('show');
    let numcom = '';
    let Secu = '';
    if($('#TxtNumComprobante').val()>1)
    {
        numcom = $('#TxtNumComprobante').val();
        Secu = 0;
    }else
    {
        numcom = 1;
        Secu =  $('#TxtNumSerietres').val();
    }
  var  parametros= 
  {
    "IdProv":$('#DCProveedor').val(), 
    // "DevIva":$('input:radio[name=cbx_iva]:checked').val(),
    // "CodSustento":$('#DCSustento').val(), 
    "TipoComprobante":$('#DCTipoComprobanteV').val(), 
    "FechaRegistro":$('#MBFechaRegistroV').val(),
    "FechaEmision":$('#MBFechaEmiV').val(),
    "BaseImponible":$('#TxtBaseImpV').val(), //CTNumero 2 decimales

    "IvaPresuntivo":$('input:radio[name=rbl_presuntivo]:checked').val(), //CTNumero 2 decimales
    
    "Establecimiento":$('#TxtNumSerieUno').val(), 
    "PuntoEmision":$('#TxtNumSerieDos').val(),
    "NumeroComprobantes":numcom,
    'Secuencial':Secu,
    "BaseImpGrav":$('#TxtBaseImpGravV').val(), //CTNumero 2 decimales
    "PorcentajeIva": $('#DCPorcenIvaV').val(), 
    "MontoIva": $('#TxtMontoIvaV').val(), //CTNumero 2 decimales
    "BaseImpIce": $('#TxtBaseImpoIceV').val(), //CTNumero 2 decimales
    "PorcentajeIce": $('#DCPorcenIceV').val(),
    "MontoIce": $('#TxtMontoIceV').val(), //CTNumero 2 decimales
    "MontoIvaBienes": $('#TxtIvaBienMonIva').val(), //CTNumero 2 decimales
    "PorRetBienes":  $('#DCPorcenRetenIvaBien').val(),                  //ojo la varable puedee cambiar
    "ValorRetBienes": $('#TxtIvaBienValRet').val(), //CTNumero 2 decimales
    "Porc_Servicios":$('#DCPorcenRetenIvaServ').val(),
    "MontoIvaServicios":$('#TxtIvaSerMonIva').val(), //CTNumero 2 decimales
    "PorRetServicios": $('#DCPorcenRetenIvaServ').val(),                //ojo la varable puedee cambiar
    "ValorRetServicios": $('#TxtIvaSerValRet').val(), //CTNumero 2 decimales

    "RetPresuntivo":$('input:radio[name=rbl_ret_presuntivo]:checked').val(), //CTNumero 2 decimales

    "ChRetB":$('#ChRetB').prop('checked'),
    "ChRetS":$('#ChRetS').prop('checked'),
    "Bienes":$("#DCRetIBienes").val(),
    "Servicio":$("#DCRetISer").val(),

    "Porc_Bienes": $('#DCPorcenRetenIvaBien').val(),


    "Tipo_pago":$("#DCTipoPago").val(),

  }
   $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/contabilidad/FVentasC.php?grabacion=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
            if (response==1) 
            {
              Swal.fire( 'Retenciones ingresadas','','success');
              // generar_asiento();           
              parent.location.reload();
              $('#myModal_espera').modal('hide');
            }else
            {

              Swal.fire( 'no se pudo guardar','','error');
              $('#myModal_espera').modal('hide');
            }
         
      }
    });  
}

function DCBenef_LostFocus(bene,cta,contr)
{
     var parametros =
    {
        'DCBenef':bene,
        'cta' :cta,
        'contra' :contr,
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/registro_esC.php?DCBenef_Data=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
            if (response.length !=0) 
            {
            console.log(response)
             $("#grupo_no").val(response.grupo_no); 
             $("#Tipodoc").val(response.tipodoc);
             $("#TipoBenef").val(response.TipoBenef);
             $("#cod_benef").val(response.cod_benef); 
             $("#InvImp").val(response.InvImp);  
             $("#TipoBenef").val(response.TipoBenef); 
             $("#ci").val(response.CICLIENTE); 

             $('#TextConcepto').val(response.text);
             $('#LblNumIdent').val(response.CICLIENTE);
             $('#LblTD').val(response.TipoBenef);
             $('#DCProveedor').html('<option value="'+response.id+'">'+response.text+'</option>');

             $("#si_no").val(response.si_no); 
             // ddl_DCTipoComprobante();
            }
         
    $('#myModal_espera').modal('hide');
      }
    });  
}