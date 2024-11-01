var Individual = false;
$(document).ready(function()
{
    sucursal_exis();
    llenar_combobox();
    llenar_combobox_cuentas();    
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
        $('#imprimir_pdf').click(function(){
        var url = '../controlador/contabilidad/mayor_auxiliarC.php?imprimir_pdf=true&CheckUsu='+$("#CheckUsu").is(':checked')+'&CheckAgencia='+$("#CheckAgencia").is(':checked')+'&txt_CtaI='+$('#txt_CtaI').val()+'&txt_CtaF='+$('#txt_CtaF').val()+'&desde='+$('#desde').val()+'&hasta='+$('#hasta').val()+'&DCAgencia='+$('#DCAgencia').val()+'&DCUsuario='+$('#DCUsuario').val()+'&DCCtas='+$('#DCCtas').val()+'&OpcUno='+$('#OpcU').val()+'&PorConceptos='+Individual+'&submodulo=false';
                
        window.open(url, '_blank');
    });

        $('#imprimir_pdf_2').click(function(){
        var url = '../controlador/contabilidad/mayor_auxiliarC.php?imprimir_pdf=true&CheckUsu='+$("#CheckUsu").is(':checked')+'&CheckAgencia='+$("#CheckAgencia").is(':checked')+'&txt_CtaI='+$('#txt_CtaI').val()+'&txt_CtaF='+$('#txt_CtaF').val()+'&desde='+$('#desde').val()+'&hasta='+$('#hasta').val()+'&DCAgencia='+$('#DCAgencia').val()+'&DCUsuario='+$('#DCUsuario').val()+'&DCCtas='+$('#DCCtas').val()+'&OpcUno='+$('#OpcU').val()+'&PorConceptos='+Individual+'&submodulo=true';
                
        window.open(url, '_blank');
    });

    $('#imprimir_excel').click(function(){
        var url = '../controlador/contabilidad/mayor_auxiliarC.php?imprimir_excel=true&CheckUsu='+$("#CheckUsu").is(':checked')+'&CheckAgencia='+$("#CheckAgencia").is(':checked')+'&txt_CtaI='+$('#txt_CtaI').val()+'&txt_CtaF='+$('#txt_CtaF').val()+'&desde='+$('#desde').val()+'&hasta='+$('#hasta').val()+'&DCAgencia='+$('#DCAgencia').val()+'&DCUsuario='+$('#DCUsuario').val()+'&DCCtas='+$('#DCCtas').val()+'&OpcUno='+$('#OpcU').val()+'&PorConceptos='+Individual+'&submodulo=false';
                
        window.open(url, '_blank');
    });

    $('#imprimir_excel_2').click(function(){
        var url = '../controlador/contabilidad/mayor_auxiliarC.php?imprimir_excel=true&CheckUsu='+$("#CheckUsu").is(':checked')+'&CheckAgencia='+$("#CheckAgencia").is(':checked')+'&txt_CtaI='+$('#txt_CtaI').val()+'&txt_CtaF='+$('#txt_CtaF').val()+'&desde='+$('#desde').val()+'&hasta='+$('#hasta').val()+'&DCAgencia='+$('#DCAgencia').val()+'&DCUsuario='+$('#DCUsuario').val()+'&DCCtas='+$('#DCCtas').val()+'&OpcUno='+$('#OpcU').val()+'&PorConceptos='+Individual+'&submodulo=true';
                
        window.open(url, '_blank');
    });


});
    
function consultar_datos(OpcUno,PorConceptos)
{
    $('#OpcU').val(OpcUno);
    var parametros =
    {
        'CheckUsu':$("#CheckUsu").is(':checked'),
        'CheckAgencia':$("#CheckAgencia").is(':checked'),
        'txt_CtaI':$('#txt_CtaI').val(),
        'txt_CtaF':$('#txt_CtaF').val(),
        'desde':$('#desde').val(),
        'hasta':$('#hasta').val(),	
        'DCAgencia':$('#DCAgencia').val(),
        'DCUsuario':$('#DCUsuario').val(),	
        'DCCtas':$('#DCCtas').val(),
        'OpcUno':OpcUno,
        'PorConceptos':PorConceptos				
    }
    $titulo = 'Mayor de '+$('#DCCtas option:selected').html(),
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/contabilidad/mayor_auxiliarC.php?consultar=true',
        type:  'post',
        dataType: 'json',
        beforeSend: function () {		
                $('#myModal_espera').modal('show');
        },
            success:  function (response) {
            
                $('#debe').val(addCommas(formatearNumero(response.SumaDebe)));				 
                $('#haber').val(addCommas(formatearNumero(response.SumaHaber)));							 
                $('#saldo').val(addCommas(formatearNumero(response.SaldoTotal)));
                $('#LabelTotSaldoAnt').val(addCommas(formatearNumero(response.SaldoAnterior)));
            
                $('#DGMayor').html(response.DGMayor);
                var nFilas = $("#DGMayor tr").length;
                // $('#num_r').html(nFilas-1);	
                $('#myModal_espera').modal('hide');	
                $('#tit').text($titulo+" (Registros: "+response.TotalRegistros+")");			    
            
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
    
function llenar_combobox()
{	

    var agencia='<option value="">Seleccione Agencia</option>';
    var usu='<option value="">Seleccione Usuario</option>';
    $.ajax({
        //data:  {parametros:parametros},
        url:   '../controlador/contabilidad/diario_generalC.php?drop=true',
        type:  'post',
        dataType: 'json',
            success:  function (response) {
            console.log(response);		
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

    var agencia='<option value="">Seleccione Cuenta</option>';
    var ini = $('#txt_CtaI').val();
    var fin = $('#txt_CtaF').val();
    $.ajax({
        data:  {ini:ini,fin:fin},
        url:   '../controlador/contabilidad/mayor_auxiliarC.php?cuentas=true',
        type:  'post',
        dataType: 'json',
            success:  function (response) {	
            $.each(response, function(i, item){
                    agencia+='<option value="'+response[i].Codigo+'" '+((i==0)?'selected':'')+'>'+response[i].Nombre_Cta+'</option>';
            });				

            $('#DCCtas').html(agencia);					    
        consultar_datos(true,Individual);				
        }
    });

}