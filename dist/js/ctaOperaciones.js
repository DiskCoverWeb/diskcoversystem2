$(document).ready(function()
    {
    
    var h = (screen.height)-280;
    $('#tabla').css('height',h);
    meses();
    cargar_cuentas();
    tipo_pago();
    copy_empresa();

    $(document).keyup(function(e){  
        if(e.keyCode==46)
        {
        eliminar_cuenta();
        }
    })


    $('#MBoxCta').keyup(function(e){ 
    if(e.keyCode != 46 && e.keyCode !=8)
    {
        validar_cuenta(this);
    }
    })

    $('#MBoxCtaAcreditar').keyup(function(e){ 
    if(e.keyCode != 46 && e.keyCode !=8)
    {
        validar_cuenta(this);
    }
    })

    });

function cargar_cuentas()
{    
    // $('#myModal_espera').modal('hide');   
   // $('#myModal_espera').modal('show');   
  $.ajax({
 // data:  {parametros:parametros},
 url:   '../php/controlador/contabilidad/ctaOperacionesC.php?cuentas=true',
 type:  'post',
 dataType: 'json',
 // beforeSend: function () {   
 // },
 success:  function (response) { 
  if(response)
  {
    $('#tabla').html(response);
    // $('#myModal_espera').modal('hide');   
  }

}
});
}

function copiar_op($op)
{
  
  if($('#DLEmpresa').val() !='')
  {
    Swal.fire({
        title: 'Seguro de Copiar el catalogo de:?',
        text: "("+$('#DLEmpresa').val()+") "+$('#DLEmpresa option:selected').text(),
        footer: "Este proceso remplazara el catalogo actual",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Estoy seguro!'
      }).then((result) => {
        if (result.value) {
            if($op == 'true')
            {
              $('#modal_periodo').modal('show');
            }else
            {
              copiar();
            }                        
        }
      })
  }else
  {
     Swal.fire('Seleccione una empresa');
     $('#modal_copiar').modal('show');   
  }
 
}

 function cambiar_op()
{
  var parametros = 
  {
    'n_codigo':$('#DLEmpresa_').val(),
    'codigo':$('#MBoxCta').val().slice(0,-1),
    'producto':'Catalogo',
  }
  $.ajax({
   data:  {parametros:parametros},
   url:   '../php/controlador/contabilidad/ctaOperacionesC.php?cambiar_op=true',
   type:  'post',
   dataType: 'json',
   beforeSend: function () {
     $('#myModal_espera').modal('show');
     },
   success:  function (response) {
      if(response==1)
        {
            Swal.fire(
                'Proceso terminado?',
                'Se a cambiado cuenta',
                'success')
          $('#myModal_espera').modal('hide');
          $('#modal_cambiar').modal('hide');
        }else if(response == -2)
        {
          Swal.fire(
                'Proceso terminado?',
                'No se puede cambiar cuenta',
                'info')
        }
      }
    });
}

function copiar()
{
  var parametros = 
  {
    "CheqCatalogo":$('#CheqCatalogo').is(':checked'),
    "CheqFact":$('#CheqFact').is(':checked'),
    "CheqSubCta":$('#CheqSubCta').is(':checked'),
    "CheqSubCP":$('#CheqSubCP').is(':checked'),
    "CheqSetImp":$('#CheqSetImp').is(':checked'),
    'empresa':$('#DLEmpresa').val(),
    'periodo':$('#txt_perido_c').val(),
    'si_no':'false',

  }
   $.ajax({
            data:  {parametros:parametros},
            url:   '../php/controlador/contabilidad/ctaOperacionesC.php?copiar=true',
            type:  'post',
            dataType: 'json',
            beforeSend: function () {   
              $('#myModal_espera').modal('show');   
            },
            success:  function (response) { 
             if(response==1)
             {
               // $('#tabla').html(response);
               cargar_cuentas();
              $('#myModal_espera').modal('hide');
              Swal.fire(
                'Proceso terminado?',
                'Se a copia con exito el catalo de cuentas',
                'success'
)   
             }else
             {
                alert('el proceso se finalizo opero con errores');
                 cargar_cuentas();
              $('#myModal_espera').modal('hide');   
             }
            }
          });
}


 function copy_empresa()
{
  var empresas = '<option value="">Elija empresa a copiar el catalogo</option>';
  $.ajax({
 // data:  {parametros:parametros},
 url:   '../php/controlador/contabilidad/ctaOperacionesC.php?copy_empresa=true',
 type:  'post',
 dataType: 'json',
 // beforeSend: function () {   
 //   // $('#myModal_espera').modal('show');   
 // },
 success:  function (response) { 
  if(response)
  {
     $.each(response, function(i, item){
        // console.log(item);
        empresas+='<option value="'+item.Item+'">'+item.Empresa+'</option>';
      }); 
    if(response.length==0)
    {
      $('#btn_copiar_cata').attr('disabled',true);
    }
    $('#DLEmpresa').html(empresas);
  }

}
});
}

  function cambio_empresa(cta)
{
  var empresas = '<option value="">Seleccione la cuenta a cambiar</option>';
  $.ajax({
  data:  {cta:cta},
 url:   '../controlador/contabilidad/ctaOperacionesC.php?cambiar_empresa=true',
 type:  'post',
 dataType: 'json',
 beforeSend: function () {   
   $('#myModal_espera').modal('show');   
 },
 success:  function (response) { 
  if(response)
  {
     $.each(response, function(i, item){
        // console.log(item);
        empresas+='<option value="'+item.Codigo+'">'+item.Ctas+'</option>';
      }); 
    $('#DLEmpresa_').html(empresas);
   
    $('#myModal_espera').modal('hide');  
  }

}
});
}

function ingresar_presu()
{
  if($('#DCMes').val() != '' && $('#txt_val_pre').val() != ''){
  var parametros=
  {
    'mes':$('#DCMes').val(),
    'mes1':$('#DCMes option:selected').text(),
    'valor':$('#txt_val_pre').val(),
    'Cta':$('#MBoxCta').val(),
  }
 
  $.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/contabilidad/ctaOperacionesC.php?ingresar_presu=true',
    type:  'post',
    dataType: 'json',
    // beforeSend: function () {   
    //   $('#myModal_espera').modal('show');   
    // },
    success:  function (response) { 
      if(response == 1)
        {
          // console.log($('#MBoxCta').val().slice(0,-1));
           cargar_presupuesto($('#MBoxCta').val().slice(0,-1));
         $('#exampleModalCenter').modal('hide');

        }else
        {
          alert('no sss');
        }
    }
  });
}else
{
  Swal.fire({
    type: 'error',
    title: 'Algo salio mal',
    text: 'Debe llenar todo los campos!'
  });
}
}

function meses()
{
  var meses='<option value="">Seleccione mes</option>'
  $.ajax({
    // data:  {parametros:parametros},
    url:   '../controlador/contabilidad/ctaOperacionesC.php?meses=true',
    type:  'post',
    dataType: 'json',
    success:  function (response) { 
       $.each(response, function(i, item){
        // console.log(item);
        meses+='<option value="'+item.acro+'">'+item.mes+'</option>';
      }); 
  $('#DCMes').html(meses);   
      }
  });
}

function tipo_pago()
{
  var pago = '<option value="">Selecciopne tipo de pago</option>';
  $.ajax({
 // data:  {parametros:parametros},
 url:   '../controlador/contabilidad/ctaOperacionesC.php?tipo_pago=true',
 type:  'post',
 dataType: 'json',
 success:  function (response) { 
  $.each(response, function(i, item){
        // console.log(item);
        pago+='<option value="'+item.Codigo+'">'+item.CTipoPago+'</option>';
      }); 
  $('#DCTipoPago').html(pago);              
}
});
}

function grabar_cuenta()
{
  var num = $('#MBoxCta').val();
  var nom = $('#TextConcepto').val();
  if(nom =='')
  {
    nom = 'Sin nombre';
    $('#TextConcepto').val(nom);
  }
  Swal.fire({
    title: 'Esta seguro de guardar?',
    text: "la cuenta N°"+num+' '+nom+' ',
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si'
  }).then((result) => {
    if (result.value) {        
     grabar();
   }
 })
}

function grabar()
{

  // $('#myModal_espera').modal('show');
  var acre = $('#MBoxCtaAcreditar').val();
  if(acre == ''){acre = 0;}

  var parametros=  {
    'OpcG':$('#OpcG').is(':checked'),
    'OpcD':$('#OpcD').is(':checked'),
    'CheqTipoPago':$('#CheqTipoPago').is(':checked'),
    'DCTipoPago':$('#DCTipoPago').val(),
    'TextPresupuesto':$('#TextPresupuesto').val(),
    'Numero': 0,
    'LstSubMod':$('#LstSubMod').val(),
    'TextConcepto':$('#TextConcepto').val(),
    'MBoxCta':$('#MBoxCta').val(),
    'LabelCtaSup':$('#LabelCtaSup').val(),
    'MBoxCtaAcreditar':acre,
    'OpcNoAplica':$('#OpcNoAplica').is(':checked'),
    'OpcIEmp':$('#OpcIEmp').is(':checked'),
    'OpcEEmp':$('#OpcEEmp').is(':checked'),
    'CheqConIESS':$('#CheqConIESS').is(':checked'),
    'CheqUS':$('#CheqUS').is(':checked'),
    'CheqFE':$('#CheqFE').is(':checked'),
    'CheqModGastos':$('#CheqModGastos').is(':checked'),
    'TxtCodExt':$('#TxtCodExt').val(),

  }
  $.ajax({   
   url:   '../controlador/contabilidad/ctaOperacionesC.php?grabar=true',
   type:  'post',
   dataType: 'json',
   data:{parametros:parametros} ,     
   success:  function (response) { 
    $('#myModal_espera').modal('hide');
    if(response == 1)
    {
     cargar_cuentas();
   }else
   {
    alert('oops!');
  }

}
});
}
function forma_pago()
{
  if($('#CheqTipoPago').is(':checked'))
  {
   $('#DCTipoPago').show();
  // alert('sss');
}else
{
  // alert('ffff');
  $('#DCTipoPago').hide();
}
}

function presupuesto_act(tip)
{
if(tip == 'CC' || tip == 'G' || tip == 'I')
{
   $('#btn_ingresar_pre').prop('disabled', false);
   $('#btn_ingresar_pre').prop('disabled', false);
}else
{
  $('#btn_ingresar_pre').prop('disabled', true);
  $('#btn_ingresar_pre').prop('disabled', true);
}
}

function cargar_presupuesto(cod)
{
var pago = '<tr><td>No se a encontrado ningun presupuesto</td><tr>';
var suma = 0.00;
  $.ajax({
  data:  {cod:cod},
 url:   '../controlador/contabilidad/ctaOperacionesC.php?presupuesto=true',
 type:  'post',
 dataType: 'json',
 success:  function (response) { 
  if(response != 0)
  {
    console.log(response);
    pago='';
  $.each(response, function(i, item){
        suma +=item.Presupuesto;
        pago+='<tr><td>'+item.Mes+'</td><td>'+item.Presupuesto+'</td>';
      }); 
 }
 $('#TextPresupuesto').val(suma)
  $('#table_pre').html(pago);              
}
});

}

function cargar_datos_cuenta(cod)
{
// console.log(cod);
cod1 = cod.split('.').join('_');  
var ant = $('#txt_anterior').val();
if(ant=='') { $('#txt_anterior').val(cod1);  }

$('#niv_'+cod1).css('border','2px solid black');
$('#niv_'+ant).css('border','1px solid white');
$('#txt_anterior').val(cod1); 
console.log(cod1);
$.ajax({
  data:  {cod:cod},
 url:   '../controlador/contabilidad/ctaOperacionesC.php?datos_cuenta=true',
 type:  'post',
 dataType: 'json',
 success:  function (response) { 
  if(response != 0)
  {
    console.log(response);  
    // $('#LstSubMod').val('"'+response.TC+'"');    //
    $('#LabelNumero').val(response[0].Clave);
    if(response[0].DG=='G')
    {
      $('#OpcG').prop('checked',true);
      $('#txt_ti').val('G');
    }else
    {
      $('#OpcD').prop('checked',true);
      $('#txt_ti').val('D');
    }
    if(response[0].Con_IESS != 0)
    {        
      $('#CheqConIESS').prop('checked',true);
    }
    if(response[0].Con_IESS != 0)
    {        
      $('#CheqConIESS').prop('checked',true);
    }else
    {
      $('#CheqConIESS').prop('checked',false);
    }
    if(response[0].I_E_Emp == 'I')
    {        
      $('#OpcIEmp').prop('checked',true);
    }
    if(response[0].I_E_Emp == 'E')
    {        
      $('#OpcEEmp').prop('checked',true);
    }
    if(response[0].I_E_Emp == '.')
    {        
      $('#OpcNoAplica').prop('checked',true);
    }
    if(response[0].Mod_Gastos != 0)
    {        
      $('#CheqModGastos').prop('checked',true);
    }else
    {
       $('#CheqModGastos').prop('checked',false);
    }
    if(response[0].ME != 0)
    {        
      $('#CheqUS').prop('checked',true);
    }else
    {
      $('#CheqUS').prop('checked',false);
    }
     if(response[0].Listar != 0)
    {        
      $('#CheqFE').prop('checked',true);
    }else
    {
      $('#CheqFE').prop('checked',false);
    }
    if(response[0].Tipo_Pago!='.')
    {
      if(response[0].Tipo_Pago!='00')
      {        
        $('#CheqTipoPago').prop('checked',true);
        forma_pago();
        $("#DCTipoPago option[value='"+response[0].Tipo_Pago+"']").attr("selected", true); 
      }else
      {

        $('#CheqTipoPago').prop('checked',true);
        forma_pago();
      }
    }else
    {
      console.log('entra');
      $('#CheqTipoPago').prop('checked',false);
      forma_pago();
      $("#DCTipoPago option[value='']").attr("selected", true); 
    }

    if(response[0].Codigo==1)
    {
      $('#LabelTipoCta').val('ACTIVO');
    }else if(response[0].Codigo==2)
    {
      $('#LabelTipoCta').val('PASIVO');
    }else if(response[0].Codigo==3)
    {
      $('#LabelTipoCta').val('CAPITAL');
    }else if(response[0].Codigo==4)
    {
      $('#LabelTipoCta').val('INGRESO');
    }else if(response[0].Codigo==5)
    {
      $('#LabelTipoCta').val('EGRESO');
    }else
    {
      $('#LabelTipoCta').val('NINGUNA');
    }

    $('#TxtCodExt').val(response[0].Codigo_Ext);
   
    // $("#LstSubMod option[value='"+response[0].TC+"']").attr("selected", true);   
    $("#LstSubMod").val(response[0].TC);
    presupuesto_act($('#LstSubMod').val());  
}
}
});

}
function validar_cambiar()
{
if($('#txt_ti').val() != 'G')
{
  $('#cambiar_select').text($('#MBoxCta').val()+'-'+$('#TextConcepto').val());
  cambio_empresa($('#MBoxCta').val());
  $('#modal_cambiar').modal('show');


}else
{
  Swal.fire(
'Solo se puede cambiar cuentas de Detalle',
'',
'question')
}
}

function eliminar_cuenta()
{
cuenta = $('#MBoxCta').val();
nombre = $('#TextConcepto').val();
if(cuenta=='')
{
   return false;
}
  Swal.fire({
       title: 'Esta seguro de eliminar?',
       text: cuenta+" "+nombre+" y sus grupos",
       type: 'warning',
       showCancelButton: true,
       confirmButtonColor: '#3085d6',
       cancelButtonColor: '#d33',
       confirmButtonText: 'Si!'
  }).then((result) => {
     if (result.value==true) {
        validar_eliminar();       
      } 
  })
}
function validar_eliminar()
{
$('#myModal_espera').modal('show');
codigo = $('#MBoxCta').val();
if(codigo!='')
{
   parametros = 
   {
     'codigo':codigo,
   }
   $.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/contabilidad/ctaOperacionesC.php?eliminar_cuentas=true',
    type:  'post',
    dataType: 'json',
    success:  function (response) { 

      $('#myModal_espera').modal('hide');
      if(response==1)
      {
        cargar_cuentas();
      }else{
        $('#myModal_espera').modal('hide');
        $('#movimientos_cta').modal('show');
        $('#lista_transacciones').html(response);       
      } 
    }

  })

}
}

function mostrarModalPass(){
IngClave('Supervisor');
resp_clave_ingreso(response);
}

function resp_clave_ingreso(response){
if (response.respuesta == 1) {
    $('#clave_supervisor').modal('hide');
    $('#modal_copiar').modal('show');
}
}