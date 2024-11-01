var Individual = false;
$(document).ready(function()
{

    // console.log(screen.height);
    sucursal_exis();
    llenar_combobox();
    llenar_combobox_cuentas();    
        $('#imprimir_pdf').click(function(){
        var url = '../controlador/contabilidad/libro_bancoC.php?imprimir_pdf=true&CheckUsu='+$("#CheckUsu").is(':checked')+'&CheckAgencia='+$("#CheckAgencia").is(':checked')+'&desde='+$('#desde').val()+'&hasta='+$('#hasta').val()+'&DCAgencia='+$('#DCAgencia').val()+'&DCUsuario='+$('#DCUsuario').val()+'&DCCtas='+$('#DCCtas').val();
                
        window.open(url, '_blank');
    });


    $('#imprimir_excel').click(function(){
        var url = '../controlador/contabilidad/libro_bancoC.php?imprimir_excel=true&CheckUsu='+$("#CheckUsu").is(':checked')+'&CheckAgencia='+$("#CheckAgencia").is(':checked')+'&txt_CtaI='+$('#txt_CtaI').val()+'&txt_CtaF='+$('#txt_CtaF').val()+'&desde='+$('#desde').val()+'&hasta='+$('#hasta').val()+'&DCAgencia='+$('#DCAgencia').val()+'&DCUsuario='+$('#DCUsuario').val()+'&DCCtas='+$('#DCCtas').val()+'&OpcUno='+$('#OpcU').val()+'&PorConceptos='+Individual+'&submodulo=false';
                
        window.open(url, '_blank');
    });

});



function fecha_fin()
{
    $fecha = $('#desde').val();
    partes = $fecha.split('-');
    var fecha = new Date();
    var ano = fecha.getFullYear();
    if(partes[0] <= (ano+30) && partes[0]>1999)
    {
        console.log(ano+10);
        var date = new Date($fecha);
        var primerDia = new Date(date.getFullYear(), date.getMonth(), 1);
        var ultimoDia = new Date(partes[0],partes[1],0);
        var mes= date.getMonth()+1;
        console.log(ultimoDia);

        if(mes <10)
        {
            mes = '0'+mes;
        }
        $('#hasta').val(partes[0]+"-"+partes[1]+"-"+ultimoDia.getDate());
        ConsultarDatosLibroBanco();
    }else
    {
        alert('Procure que la fecha no sea mayor a '+(ano+30)+' y menor a 2000');
    }


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
    
function ConsultarDatosLibroBanco()
{
    var parametros =
    {
        'CheckUsu':$("#CheckUsu").is(':checked'),
        'CheckAgencia':$("#CheckAgencia").is(':checked'),
        'desde':$('#desde').val(),
        'hasta':$('#hasta').val(),	
        'DCAgencia':$('#DCAgencia').val(),
        'DCUsuario':$('#DCUsuario').val(),	
        'DCCtas':$('#DCCtas').val(),
        'height':screen.height,			
    }
    $titulo = 'Mayor de '+$('#DCCtas option:selected').html();
    $('#myModal_espera').modal('show');
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/contabilidad/libro_bancoC.php?consultar=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) {
            $('#debe').val(addCommas(response.LabelTotDebe));
            $('#haber').val(addCommas(response.LabelTotHaber));					
            $('#saldo_ant').val(addCommas(response.LabelSaldoAntMN));
            $('#saldo').val(addCommas(response.LabelTotSaldo));

            $('#debe_').val(addCommas(response.LabelTotDebeME));
            $('#haber_').val(addCommas(response.LabelTotHaberME));
            $('#saldo_ant_').val(addCommas(response.LabelSaldoAntME));
            $('#saldo_').val(addCommas(response.LabelTotSaldoME));
            
                $('#tabla_').html(response.DGBanco);
                var nFilas = $("#tabla_ tr").length;
                $('#myModal_espera').modal('hide');	
                $('#tit').text($titulo+" (Registros: "+response.TotalRegistros+")");			    
        }
    });

}
    
function llenar_combobox()
{	

    var agencia='<option value="">Seleccione Agencia</option>';
    var usu='<option value="">Seleccione Usuario</option>';
    $.ajax({
        //data:  {parametros:parametros},
        url:   '../controlador/contabilidad/diario_generalC.php?drop=true',
        type:  'post',
        dataType: 'json',
        /*beforeSend: function () {		
                var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'			
                $('#tabla_').html(spiner);
        },*/
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

function llenar_combobox_cuentas()
{	
    var cuentas='';
    $.ajax({
        url:   '../controlador/contabilidad/libro_bancoC.php?cuentas=true',
        type:  'post',
        dataType: 'json',
            success:  function (response) {	
            $.each(response, function(i, item){
                cuentas+='<option value="'+response[i].Codigo+'" '+((i==0)?'selected':'')+'>'+response[i].Nombre_Cta+'</option>';
            });				
            if($.trim(cuentas) === ''){
                cuentas='<option value=".">Sin Cuentas</option>';
            }
            $('#DCCtas').html(cuentas);					    
            ConsultarDatosLibroBanco();				
        }
    });

}