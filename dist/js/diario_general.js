
function activar($this)
{
  var tab = $this.id;
  if(tab=='tabla_submodulo')
  {
    $('#activo').val('2');

  }else
  {
    $('#activo').val('1');
  }

}
  
function consultarDatosAgenciaUsuario()
{ 
  var agencia='<option value="">Seleccione Agencia</option>';
  var usu='<option value="">Seleccione Usuario</option>';
  $.ajax({
    url:   '../controlador/contabilidad/diario_generalC.php?drop=true',
    type:  'post',
    dataType: 'json',
      success:  function (response) {       
      $.each(response.agencia, function(i, item){
        agencia+='<option value="'+response.agencia[i].Item+'">'+response.agencia[i].NomEmpresa+'</option>';
      });       
      $('#DCAgencia').html(agencia);
      $.each(response.usuario, function(i, item){
        usu+='<option value="'+response.usuario[i].Codigo+'">'+response.usuario[i].CodUsuario+'</option>';
      });         
      $('#DCUsuario').html(usu);          
    }
  });
}

function sucursal_exis()
{ 

   $.ajax({
    //data:  {parametros:parametros},
    url:   '../controlador/contabilidad/diario_generalC.php?sucu_exi=true',
    type:  'post',
    dataType: 'json',
    /*beforeSend: function () {   
         var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
       $('#tabla_').html(spiner);
    },*/
      success:  function (response) { 
      if(response == 1)
      {
        $("#CheckAgencia").show();
        $('#DCAgencia').show();
        $('#lblAgencia').show();
      } else
      {
        $("#CheckAgencia").hide();
        $('#DCAgencia').hide();
        $('#lblAgencia').hide();
      }     
      
    }
  });

}


function cargar_libro_general()
{ 
        var parametros= {
          'OpcT':$("#OpcT").is(':checked'),
          'OpcCI':$("#OpcCI").is(':checked'),
          'OpcCE':$("#OpcCE").is(':checked'),
          'OpcCD':$("#OpcCD").is(':checked'),
          'OpcA':$("#OpcA").is(':checked'),
          'OpcND':$("#OpcND").is(':checked'),
          'OpcNC':$("#OpcNC").is(':checked'),
          'CheckNum':$("#CheckNum").is(':checked'),
          'TextNumNo':$('#TextNumNo').val(),
          'TextNumNo1':$('#TextNumNo1').val(),
          'CheckUsuario':$("#CheckUsuario").is(':checked'),
          'DCUsuario':$('#DCUsuario').val(),
          'CheckAgencia':$("#CheckAgencia").is(':checked'),
          'DCAgencia':$('#DCAgencia').val(),
          'DCAgencia':$('#DCAgencia').val(),
          'Fechaini':$('#txt_desde').val(),
          'Fechafin':$('#txt_hasta').val(),
        }

          $.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/contabilidad/diario_generalC.php?consultar_libro=true',
    type:  'post',
    dataType: 'json',
    beforeSend: function () {   
         var spiner = '<div class="text-center"><img src="../../img/gif/loader4.1.gif" width="200" height="100"></div>'  
      $('#tabla_').html(spiner);
    },
      success:  function (response) { 
      libro_general_saldos(); 
      libro_submodulo();  
      $('#tabla_').html(response);
    }
  });

}

function libro_submodulo()
{ 
  $('#myModal_espera').modal('show');
        var parametros= {
          'OpcT':$("#OpcT").is(':checked'),
          'OpcCI':$("#OpcCI").is(':checked'),
          'OpcCE':$("#OpcCE").is(':checked'),
          'OpcCD':$("#OpcCD").is(':checked'),
          'OpcA':$("#OpcA").is(':checked'),
          'OpcND':$("#OpcND").is(':checked'),
          'OpcNC':$("#OpcNC").is(':checked'),
          'CheckNum':$("#CheckNum").is(':checked'),
          'TextNumNo':$('#TextNumNo').val(),
          'TextNumNo1':$('#TextNumNo1').val(),
          'CheckUsuario':$("#CheckUsuario").is(':checked'),
          'DCUsuario':$('#DCUsuario').val(),
          'CheckAgencia':$("#CheckAgencia").is(':checked'),
          'DCAgencia':$('#DCAgencia').val(),
          'DCAgencia':$('#DCAgencia').val(),
          'Fechaini':$('#txt_desde').val(),
          'Fechafin':$('#txt_hasta').val(),
        }

          $.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/contabilidad/diario_generalC.php?consultar_submodulo=true',
    type:  'post',
    dataType: 'json',
    beforeSend: function () {   
         var spiner = '<div class="text-center"><img src="../../img/gif/loader4.1.gif" width="100" height="100"></div>';     
         $('#tabla_submodulo').html(spiner);
    },
      success:  function (response) { 
        // if(response)
        // {
          $('#tabla_submodulo').html(response);
        // }
           $('#myModal_espera').modal('hide');   
    }
  });

}

function libro_general_saldos()
    { 
    var parametros= {
    '   OpcT':$("#OpcT").is(':checked'),
    'OpcCI':$("#OpcCI").is(':checked'),
    'OpcCE':$("#OpcCE").is(':checked'),
    'OpcCD':$("#OpcCD").is(':checked'),
    'OpcA':$("#OpcA").is(':checked'),
    'OpcND':$("#OpcND").is(':checked'),
    'OpcNC':$("#OpcNC").is(':checked'),
    'CheckNum':$("#CheckNum").is(':checked'),
    'TextNumNo':$('#TextNumNo').val(),
    'TextNumNo1':$('#TextNumNo1').val(),
    'CheckUsuario':$("#CheckUsuario").is(':checked'),
    'DCUsuario':$('#DCUsuario').val(),
    'CheckAgencia':$("#CheckAgencia").is(':checked'),
    'DCAgencia':$('#DCAgencia').val(),
    'DCAgencia':$('#DCAgencia').val(),
    'Fechaini':$('#txt_desde').val(),
    'Fechafin':$('#txt_hasta').val(),
    }

    $.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/contabilidad/diario_generalC.php?consultar_libro_1=true',
    type:  'post',
    dataType: 'json',
    beforeSend: function () {   
            //var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
        // $('#tabla_').html(spiner);
    },
        success:  function (response){
        if(response)
        {
        $('#debe').val(addCommas(response.debe.toFixed(2)));
        $('#haber').val(addCommas(response.haber.toFixed(2)));
        $('#Saldo').html(addCommas(response.saldo.toFixed(2)));

        $('#debe_me').val(addCommas(response.debe_me.toFixed(2)));          
        $('#haber_me').val(addCommas(response.haber_me.toFixed(2)));
        $('#SaldoME').html(addCommas(response.saldo_me.toFixed(2)));

        }       
        
        
    }
});

}

/*function reporte_libro_1()
{ 
        var parametros= {
          'OpcT':$("#OpcT").is(':checked'),
          'OpcCI':$("#OpcCI").is(':checked'),
          'OpcCE':$("#OpcCE").is(':checked'),
          'OpcCD':$("#OpcCD").is(':checked'),
          'OpcA':$("#OpcA").is(':checked'),
          'OpcND':$("#OpcND").is(':checked'),
          'OpcNC':$("#OpcNC").is(':checked'),
          'CheckNum':$("#CheckNum").is(':checked'),
          'TextNumNo':$('#TextNumNo').val(),
          'TextNumNo1':$('#TextNumNo1').val(),
          'CheckUsuario':$("#CheckUsuario").is(':checked'),
          'DCUsuario':$('#DCUsuario').val(),
          'CheckAgencia':$("#CheckAgencia").is(':checked'),
          'DCAgencia':$('#DCAgencia').val(),
          'DCAgencia':$('#DCAgencia').val(),
          'Fechaini':$('#txt_desde').val(),
          'Fechafin':$('#txt_hasta').val(),
        }

          $.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/contabilidad/diario_generalC.php?reporte_libro_1=true',
    type:  'post',
    dataType: 'json',
    beforeSend: function () {   
         //var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
      // $('#tabla_').html(spiner);
    },
      success:  function (response){
      if(response)
      {
      //  $('#debe').html(response.debe.toFixed(2));
      //  $('#haber').html(response.haber.toFixed(2));
      //  $('#debe_me').html(response.debe_me.toFixed(2));          
      //  $('#haber_me').html(response.haber_me.toFixed(2));

      }       
       
      
    }
  });

}
*/


function mostrar_campos()
{
  // console.log($("#CheckNum").is(':checked'));

  if($("#CheckNum").is(':checked'))
  {
    $('#campos').css('display','block');
    //$('#TextNumNo1').css('display','block');
  }else
  {
    $('#campos').css('display','none');
  //  $('#TextNumNo1').css('display','none');
  }
}



$(document).ready(function()
{
  consultarDatosAgenciaUsuario();
  cargar_libro_general();
  sucursal_exis();

  $('#txt_CtaI').keyup(function(e){ 
    if(e.keyCode != 46 && e.keyCode !=8)
    {
      validar_cuenta(this);
    }
   })

  $('#txt_CtaF').keyup(function(e){ 
    if(e.keyCode != 46 && e.keyCode !=8)
    {
      validar_cuenta(this);
    }
   })


     $('#imprimir_excel').click(function(){         

      var url = '../controlador/contabilidad/diario_generalC.php?reporte_libro_1_excel=true&OpcT='+$("#OpcT").is(':checked')+'&OpcCI='+$("#OpcCI").is(':checked')+'&OpcCE='+$("#OpcCE").is(':checked')+'&OpcCD='+$("#OpcCD").is(':checked')+'&OpcA='+$("#OpcA").is(':checked')+'&OpcND='+$("#OpcND").is(':checked')+'&OpcNC='+$("#OpcNC").is(':checked')+'&CheckNum='+$("#CheckNum").is(':checked')+'&TextNumNo='+$('#TextNumNo').val()+'&TextNumNo1='+$('#TextNumNo1').val()+'&CheckUsuario='+$("#CheckUsuario").is(':checked')+'&DCUsuario='+$('#DCUsuario').val()+'&CheckAgencia='+$("#CheckAgencia").is(':checked')+'&DCAgencia='+$('#DCAgencia').val()+'&DCAgencia='+$('#DCAgencia').val()+'&Fechaini='+$('#txt_desde').val()+'&Fechafin='+$('#txt_hasta').val();
          window.open(url, '_blank');
     });

     $('#imprimir_excel_2').click(function(){         

      var url = '../controlador/contabilidad/diario_generalC.php?reporte_libro_2_excel=true&OpcT='+$("#OpcT").is(':checked')+'&OpcCI='+$("#OpcCI").is(':checked')+'&OpcCE='+$("#OpcCE").is(':checked')+'&OpcCD='+$("#OpcCD").is(':checked')+'&OpcA='+$("#OpcA").is(':checked')+'&OpcND='+$("#OpcND").is(':checked')+'&OpcNC='+$("#OpcNC").is(':checked')+'&CheckNum='+$("#CheckNum").is(':checked')+'&TextNumNo='+$('#TextNumNo').val()+'&TextNumNo1='+$('#TextNumNo1').val()+'&CheckUsuario='+$("#CheckUsuario").is(':checked')+'&DCUsuario='+$('#DCUsuario').val()+'&CheckAgencia='+$("#CheckAgencia").is(':checked')+'&DCAgencia='+$('#DCAgencia').val()+'&DCAgencia='+$('#DCAgencia').val()+'&Fechaini='+$('#txt_desde').val()+'&Fechafin='+$('#txt_hasta').val();
          window.open(url, '_blank');
     });

     $('#imprimir_pdf').click(function(){
     var url = '../controlador/contabilidad/diario_generalC.php?reporte_libro_1=true&OpcT='+$("#OpcT").is(':checked')+'&OpcCI='+$("#OpcCI").is(':checked')+'&OpcCE='+$("#OpcCE").is(':checked')+'&OpcCD='+$("#OpcCD").is(':checked')+'&OpcA='+$("#OpcA").is(':checked')+'&OpcND='+$("#OpcND").is(':checked')+'&OpcNC='+$("#OpcNC").is(':checked')+'&CheckNum='+$("#CheckNum").is(':checked')+'&TextNumNo='+$('#TextNumNo').val()+'&TextNumNo1='+$('#TextNumNo1').val()+'&CheckUsuario='+$("#CheckUsuario").is(':checked')+'&DCUsuario='+$('#DCUsuario').val()+'&CheckAgencia='+$("#CheckAgencia").is(':checked')+'&DCAgencia='+$('#DCAgencia').val()+'&DCAgencia='+$('#DCAgencia').val()+'&Fechaini='+$('#txt_desde').val()+'&Fechafin='+$('#txt_hasta').val();
               
         window.open(url, '_blank');
     });

     $('#imprimir_pdf_2').click(function(){
     var url = '../controlador/contabilidad/diario_generalC.php?reporte_libro_2=true&OpcT='+$("#OpcT").is(':checked')+'&OpcCI='+$("#OpcCI").is(':checked')+'&OpcCE='+$("#OpcCE").is(':checked')+'&OpcCD='+$("#OpcCD").is(':checked')+'&OpcA='+$("#OpcA").is(':checked')+'&OpcND='+$("#OpcND").is(':checked')+'&OpcNC='+$("#OpcNC").is(':checked')+'&CheckNum='+$("#CheckNum").is(':checked')+'&TextNumNo='+$('#TextNumNo').val()+'&TextNumNo1='+$('#TextNumNo1').val()+'&CheckUsuario='+$("#CheckUsuario").is(':checked')+'&DCUsuario='+$('#DCUsuario').val()+'&CheckAgencia='+$("#CheckAgencia").is(':checked')+'&DCAgencia='+$('#DCAgencia').val()+'&DCAgencia='+$('#DCAgencia').val()+'&Fechaini='+$('#txt_desde').val()+'&Fechafin='+$('#txt_hasta').val();
               
         window.open(url, '_blank');
     });

     
  });