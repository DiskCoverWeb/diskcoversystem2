function cuenta()
{
    if($("#CheqCta").is(':checked'))
    {
        $('#select_cuenta').css('display','block')

    }else
    {
        $('#select_cuenta').css('display','none')
    }
}

function detalle()
{
    if($("#CheqDet").is(':checked'))
    {
        $('#select_detalle').css('display','block')

    }else
    {
        $('#select_detalle').css('display','none')
    }
}
function beneficiario()
{

    if($("#CheqIndiv").is(':checked'))
    {
        $('#select_beneficiario').css('display','block')

    }else
    {
        $('#select_beneficiario').css('display','none')
    }

}

function cargar_cbx()
{

 $('#myModal_espera').modal('show');  
var select = $('#tipo_cuenta').val();
if(select =='G' || select =='I'|| select == 'CC')
{
    $('#lbl_bene').text('SubModulo');
}
else
{
    $('#lbl_bene').text('Beneficiario');
}
var cta='<option value="">Seleccione cuenta</option>';
var det='<option value="">Seleccione Detalle</option>';
var bene='<option value="">Seleccione Beneficiario</option>';
$.ajax({
    data:  {select:select},
    url:   '../controlador/contabilidad/saldo_fac_submoduloC.php?cargar=true',
    type:  'post',
    dataType: 'json',
    beforeSend: function () {					
        $('#titulo_tab').text('');
        $('#titulo2_tab').text('');
    },
    success:  function (response) {
        // consultar_datos();

        $('#titulo_tab').text(response.titulo);
        $('#titulo2_tab').text(response.titulo+' TEMPORIZADO');
        //llena select de cta				
        $('#select_cuenta').html(cta);
        $.each(response.cta, function(i, item){
            cta+='<option value="'+response.cta[i].Nombre_Cta+'">'+response.cta[i].Nombre_Cta+'</option>';
        });
        $('#select_cuenta').html(cta);

        //llena select de detalle				
        $('#select_detalle').html(cta);
        $.each(response.det, function(i, item){
            det+='<option>'+response.det[i].Detalle_SubCta+'</option>';
        });
        $('#select_detalle').html(det);
        //llena select de beneficiario				
        $('#select_beneficiario').html(bene);
        $.each(response.beneficiario, function(i, item){
            bene+='<option value="'+response.beneficiario[i].Codigo+'">'+response.beneficiario[i].Cliente+'</option>';
        });
        $('#select_beneficiario').html(bene);
        console.log(response);
        //console.log(response.titulo);
        
 $('#myModal_espera').modal('hide');  
    }

});
}

function consultar_datos()
{		
$('#reporte_tipo').val(0);
var parametros =
{
    'tipocuenta':$('#tipo_cuenta').val(),
    'ChecksubCta':$("#ChecksubCta").is(':checked'),
    'OpcP':$("#OpcP").is(':checked'),
    'CheqCta':$("#CheqCta").is(':checked'),
    'CheqDet':$("#CheqDet").is(':checked'),
    'CheqIndiv':$("#CheqIndiv").is(':checked'),
    'fechaini':$('#txt_desde').val(),
    'fechafin':$('#txt_hasta').val(),
    'Cta':$('#select_cuenta').val(),
    'CodigoCli':$('#select_beneficiario').val(),
    'DCDet':$('#select_detalle').val(),
}
$.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/contabilidad/saldo_fac_submoduloC.php?consultar=true',
    type:  'post',
    dataType: 'json',
    beforeSend: function () {		
      //    var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'			
         // $('#tabla_').html(spiner);

 $('#myModal_espera').modal('show');  
    },
        success:  function (response) {
        totales();
         $('#tabla_').html(response);				 	
        // consultar_datos_tempo();
        
 $('#myModal_espera').modal('hide');  
        
        
    console.log(response);
        //console.log(response.titulo);
    }
});

}

function consultar_datos_x_meses()
{
$('#reporte_tipo').val(1);
if($('#tipo_cuenta').val()=='')
{
    Swal.fire('Seleccione tipo de Cuenta','','info');
    return false;
}
if($("#CheqCta").prop('checked')==false)
{
    Swal.fire('Active opcion Por Cta.','','info');
    return false;
}
if($('#select_cuenta').val()=='')
{
    Swal.fire('Seleccione una Cuenta','','info');
    return false;
}
if($('#txt_hasta').val()=='')
{
    Swal.fire('Fecha hasta invalida','','info');
    return false;
}
var parametros =
{
    'tipocuenta':$('#tipo_cuenta').val(),
    'ChecksubCta':$("#ChecksubCta").is(':checked'),
    'OpcP':$("#OpcP").is(':checked'),
    'CheqCta':$("#CheqCta").is(':checked'),
    'fechaini':$('#txt_desde').val(),
    'fechafin':$('#txt_hasta').val(),
    'Cta':$('#select_cuenta').val(),
}
$.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/contabilidad/saldo_fac_submoduloC.php?consultar_x_meses=true',
    type:  'post',
    dataType: 'json',
    beforeSend: function () {		
      //    var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'			
         // $('#tabla_').html(spiner);

 $('#myModal_espera').modal('show');  
    },
        success:  function (response) {
         $('#tabla_').html(response);				 	
        // consultar_datos_tempo();
        
 $('#myModal_espera').modal('hide');  
        
        
    console.log(response);
        //console.log(response.titulo);
    }
});

}

function totales()
{
    var parametros =
{
    'tipocuenta':$('#tipo_cuenta').val(),
    'ChecksubCta':$("#ChecksubCta").is(':checked'),
    'OpcP':$("#OpcP").is(':checked'),
    'CheqCta':$("#CheqCta").is(':checked'),
    'CheqDet':$("#CheqDet").is(':checked'),
    'CheqIndiv':$("#CheqIndiv").is(':checked'),
    'fechaini':$('#txt_desde').val(),
    'fechafin':$('#txt_hasta').val(),
    'Cta':$('#select_cuenta').val(),
    'CodigoCli':$('#select_beneficiario').val(),
    'DCDet':$('#select_detalle').val(),
}
$.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/contabilidad/saldo_fac_submoduloC.php?consultar_totales=true',
    type:  'post',
    dataType: 'json',
    beforeSend: function () {		
      //    var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'			
         // $('#tabla_').html(spiner);

 // $('#myModal_espera').modal('show');  
    },
        success:  function (response) {
            // num.toString().match(/^-?\d+(?:\.\d{0,1})?/)[0];

            $('#total_mn').text(addCommas(Number(response.Total.toString().match(/^-?\d+(?:\.\d{0,1})?/)[0])));
            $('#saldo_mn').text(addCommas(Number(response.Saldo.toString().match(/^-?\d+(?:\.\d{0,1})?/)[0])));

        console.log(response);
        console.log(addCommas(response.Total));
        
 // $('#myModal_espera').modal('hide');  
        
        
        //console.log(response.titulo);
    }
});

}

function addCommas(nStr) {
nStr += '';
var x = nStr.split('.');
var x1 = x[0];
var x2 = x.length > 1 ? '.' + x[1] : '';
var rgx = /(\d+)(\d{3})/;
while (rgx.test(x1)) {
x1 = x1.replace(rgx, '$1' + ',' + '$2');
}
return x1 + x2;
}

function consultar_datos_tempo()
{
var parametros =
{
    'tipocuenta':$('#tipo_cuenta').val(),
    'ChecksubCta':$("#ChecksubCta").is(':checked'),
    'OpcP':$("#OpcP").is(':checked'),
    'CheqCta':$("#CheqCta").is(':checked'),
    'CheqDet':$("#CheqDet").is(':checked'),
    'CheqIndiv':$("#CheqIndiv").is(':checked'),
    'fechaini':$('#txt_desde').val(),
    'fechafin':$('#txt_hasta').val(),
    'Cta':$('#select_cuenta').val(),
    'CodigoCli':$('#select_beneficiario').val(),
    'DCDet':$('#select_detalle').val(),
}
$.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/contabilidad/saldo_fac_submoduloC.php?consultar_tempo=true',
    type:  'post',
    dataType: 'json',
    beforeSend: function () {		
         var spiner = '<div class="text-center"><img src="../../img/gif/loader4.1.gif" width="30%"></div>'			
         $('#tabla_temp').html(spiner);
    },			
    success:  function (response) {
        
         $('#tabla_temp').html(response);
        
        //$('#tabla_temp').html(response.tabla_2);
        
    //console.log(response);
        //console.log(response.titulo);
    }
});

}

function activar($this)
{
var tab = $this.id;
if(tab=='titulo2_tab')
{
    $('#activo').val('2');		

}else
{
    $('#activo').val('1');
}

}

$(document).ready(function()
{

$("#descargar_pdf").click(function(){

   if($('#reporte_tipo').val()==1)
   {
       var url ='../../lib/fpdf/reportes_Saldo_fac_subMod.php?pdf_submodulo_mes=true&tipocuenta='+$('#tipo_cuenta').val()+'&ChecksubCta='+$("#ChecksubCta").is(':checked')+'&CheqCta='+$("#CheqCta").is(':checked')+'&fechaini='+$('#txt_desde').val()+'&fechafin='+$('#txt_hasta').val()+'&Cta='+$('#select_cuenta').val();       		
   }
   if($('#activo').val()=='1' && $('#reporte_tipo').val()==0){   

   var url ='../../lib/fpdf/reportes_Saldo_fac_subMod.php?Mostrar_pdf=true&tipocuenta='+$('#tipo_cuenta').val()+'&ChecksubCta='+$("#ChecksubCta").is(':checked')+'&OpcP='+$("#OpcP").is(':checked')+'&CheqCta='+$("#CheqCta").is(':checked')+'&CheqDet='+$("#CheqDet").is(':checked')+'&CheqIndiv='+$("#CheqIndiv").is(':checked')+'&CodigoCli='+$('#select_beneficiario').val()+'&Cta='+$('#select_cuenta').val()+'&DCDet='+$('#select_detalle').val()+'&fechaini='+$('#txt_desde').val()+'&fechafin='+$('#txt_hasta').val()+'&tipo=mostrar&tabla=normal'; 
}else if($('#activo').val()=='2' && $('#reporte_tipo').val()==0){

  var url ='../../lib/fpdf/reportes_Saldo_fac_subMod.php?Mostrar_pdf=true&tipocuenta='+$('#tipo_cuenta').val()+'&ChecksubCta='+$("#ChecksubCta").is(':checked')+'&OpcP='+$("#OpcP").is(':checked')+'&CheqCta='+$("#CheqCta").is(':checked')+'&CheqDet='+$("#CheqDet").is(':checked')+'&CheqIndiv='+$("#CheqIndiv").is(':checked')+'&CodigoCli='+$('#select_beneficiario').val()+'&Cta='+$('#select_cuenta').val()+'&DCDet='+$('#select_detalle').val()+'&fechaini='+$('#txt_desde').val()+'&fechafin='+$('#txt_hasta').val()+'&tipo=imprimir&tabla=temp';  
  } 	    
     
      window.open(url, '_blank');
});



$('#descargar_excel').click(function(){

   if($('#reporte_tipo').val()==1)
   {
        var url ='../../lib/fpdf/reportes_Saldo_fac_subMod.php?excel_submodulo_mes=true&tipocuenta='+$('#tipo_cuenta').val()+'&ChecksubCta='+$("#ChecksubCta").is(':checked')+'&CheqCta='+$("#CheqCta").is(':checked')+'&fechaini='+$('#txt_desde').val()+'&fechafin='+$('#txt_hasta').val()+'&Cta='+$('#select_cuenta').val();
   }

   if($('#activo').val()=='1' && $('#reporte_tipo').val()==0 ){   

   var url ='../../lib/fpdf/reportes_Saldo_fac_subMod.php?Mostrar_excel=true&tipocuenta='+$('#tipo_cuenta').val()+'&ChecksubCta='+$("#ChecksubCta").is(':checked')+'&OpcP='+$("#OpcP").is(':checked')+'&CheqCta='+$("#CheqCta").is(':checked')+'&CheqDet='+$("#CheqDet").is(':checked')+'&CheqIndiv='+$("#CheqIndiv").is(':checked')+'&CodigoCli='+$('#select_beneficiario').val()+'&Cta='+$('#select_cuenta').val()+'&DCDet='+$('#select_detalle').val()+'&fechaini='+$('#txt_desde').val()+'&fechafin='+$('#txt_hasta').val()+'&tipo=mostrar&tabla=normal'; 
}else if($('#activo').val()=='2' && $('#reporte_tipo').val()==0){

  var url ='../../lib/fpdf/reportes_Saldo_fac_subMod.php?Mostrar_excel=true&tipocuenta='+$('#tipo_cuenta').val()+'&ChecksubCta='+$("#ChecksubCta").is(':checked')+'&OpcP='+$("#OpcP").is(':checked')+'&CheqCta='+$("#CheqCta").is(':checked')+'&CheqDet='+$("#CheqDet").is(':checked')+'&CheqIndiv='+$("#CheqIndiv").is(':checked')+'&CodigoCli='+$('#select_beneficiario').val()+'&Cta='+$('#select_cuenta').val()+'&DCDet='+$('#select_detalle').val()+'&fechaini='+$('#txt_desde').val()+'&fechafin='+$('#txt_hasta').val()+'&tipo=imprimir&tabla=temp';  
  } 	    
     
      window.open(url, '_blank');
});


});


