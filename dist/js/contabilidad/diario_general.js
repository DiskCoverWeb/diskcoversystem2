
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
  cargar_libro_general_tbl();
  libro_submodulo();
}

function cargar_libro_general_tbl()
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
  
  if ($.fn.dataTable.isDataTable('#tbl_DiarioGeneral')){
    $('#tbl_DiarioGeneral').DataTable().destroy();
    }

  tbl_diarioGeneral = $('#tbl_DiarioGeneral').DataTable({
    lengthChange: false, // Desactiva el menú de selección de registros por página
    paging: false,        // Mantiene la paginación habilitada
    searching: false,
    language:{
      url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'    
    },
    ajax: {
      url: '../controlador/contabilidad/diario_generalC.php?consultar_libro=true',
      type: 'post',
      data: function(d){
        return { parametros:parametros }
      },
      dataSrc: function(response){
        libro_general_saldos(); 
        response.data = ProcesarDatos(response.data);
        return response.data;             
      },
      error: function(xhr, status, error){
        console.log("Error en la solicutud: ", status, error);
      }
    },
    columns: [
      { data: 'Fecha',
        render: function(data, type, item) {
          const fecha = data?.date;
          return fecha ? new Date(fecha).toLocaleDateString() : '';
        }
      }, 
      { data: 'TP'},
      { data: 'Numero'},
      { data: 'Beneficiario'},
      { data: 'Concepto'},
      { data: 'Cta'}, 
      { data: 'Cuenta'},
      { data: 'Parcial_ME'},
      { data: 'Debe'},
      { data: 'Haber'},
      { data: 'Detalle'}, 
      { data: 'Nombre_Completo'},
      { data: 'CodigoU'},
      { data: 'Autorizado'},
      { data: 'Item'},
      { data: 'ID'}
    ],
    order: [
      [0, 'asc']
    ], 
    createdRow: function(row, data){ 
      alignEnd(row, data);
    }
  }); 
  
}

function libro_submodulo()
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
  
  if ($.fn.dataTable.isDataTable('#tbl_Submodulos')){
    $('#tbl_Submodulos').DataTable().destroy();
  }

  tbl_submodulos = $('#tbl_Submodulos').DataTable({
    lengthChange: false, // Desactiva el menú de selección de registros por página
    paging: false,
    searching: false,
    language:{
      url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'    
    },
    ajax: {
      url: '../controlador/contabilidad/diario_generalC.php?consultar_submodulo=true',
      type: 'post',
      data: function(d){
        return { parametros:parametros }
      },
      dataSrc: function(response){
        response.data = ProcesarDatos(response.data);
        return response.data;
      },
      error: function(xhr, status, error){
        console.log("Error en la solicutud: ", status, error);
      }
    },
    columns: [
      { data: 'Fecha',
        render: function(data, type, item) {
          const fecha = data?.date;
          return fecha ? new Date(fecha).toLocaleDateString() : '';
        }
      }, 
      { data: 'TP'},
      { data: 'Numero'},
      { data: 'Cliente'},
      { data: 'Cta'},
      { data: 'TC'}, 
      { data: 'Factura'},
      { data: 'Debitos'},
      { data: 'Creditos'},
      { data: 'Prima'}
    ],
    createdRow: function(row, data){
      alignEnd(row, data);
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
  // libro_submodulo();
  libro_general_saldos();
  $('[data-bs-toggle="tooltip"]').tooltip();

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