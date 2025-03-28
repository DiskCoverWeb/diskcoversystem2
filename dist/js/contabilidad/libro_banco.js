var Individual = false;
$(document).ready(function()
{
    $('[data-bs-toggle="tooltip"]').tooltip(); 
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
        var date = new Date($fecha);
        var primerDia = new Date(date.getFullYear(), date.getMonth(), 1);
        var ultimoDia = new Date(partes[0],partes[1],0);
        var mes= date.getMonth()+1;

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
        setTimeout(()=>{
            $('#myModal_espera').modal('hide');
          }, 2000);	
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

    }
    $titulo = 'Mayor de '+$('#DCCtas option:selected').html();
    $.ajax({
        data: { parametros:parametros },
        url: '../controlador/contabilidad/libro_bancoC.php?consultar=true',
        type: 'post', 
        dataType: 'json',
        beforeSend: function(){
        }, 
        success: function(response){
            $('#debe').val(addCommas(response.LabelTotDebe));
                $('#haber').val(addCommas(response.LabelTotHaber));					
                $('#saldo_ant').val(addCommas(response.LabelSaldoAntMN));
                $('#saldo').val(addCommas(response.LabelTotSaldo));
    
                $('#debe_').val(addCommas(response.LabelTotDebeME));
                $('#haber_').val(addCommas(response.LabelTotHaberME));
                $('#saldo_ant_').val(addCommas(response.LabelSaldoAntME));
                $('#saldo_').val(addCommas(response.LabelTotSaldoME));
                $('#tit').text($titulo+" (Registros: "+response.TotalRegistros+")");
                $('#tbl_libro_banco').DataTable({
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                    },
                    data: ProcesarDatos(response.DGBanco.data),
                    destroy: true,
                    paging: false,
                    searching: false,   
                    'columns': [
                        {data: "Cta"},
                        {data: "Fecha",
                            render: function(data, type, item) {
                                const fecha = data?.date;
                                return fecha ? new Date(fecha).toLocaleDateString() : '';
                            }
                        },
                        {data: "TP"},
                        {data: "Numero"},
                        {data: "Cheq_Dep"},
                        {data: "Cliente"},
                        {data: "Concepto"},
                        {data: "Debe"},
                        {data: "Haber"},
                        {data: "Saldo"},
                        {data: "Parcial_ME"},
                        {data: "Saldo_ME"},
                        {data: "T"},
                        {data: "Item"},                        
                    ],
                    order: [
                        [0, 'asc']
                    ],
                    
                });
        }, 
        error: function(xhr, status, error){ 
            console.error(xhr, status, error); 
        }
    })
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
            setTimeout(()=>{
                $('#myModal_espera').modal('hide');
            }, 2000);			
            $.each(response.agencia, function(i, item){
                agencia+='<option value="'+response.agencia[i].Item+'">'+response.agencia[i].NomEmpresa+'</option>';
            });				
            $('#DCAgencia').html(agencia);
            $.each(response.usuario, function(i, item){
                usu+='<option value="'+response.usuario[i].Codigo+'">'+response.usuario[i].CodUsuario+'</option>';
            });					
            $('#DCUsuario').html(usu);			    
            
        },
        error: function(xhr, status, error){
            setTimeout(()=>{
                $('#myModal_espera').modal('hide');
            }, 2000);
            console.error("Error en la solicitud: ", status, error);
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
            setTimeout(()=>{
                $('#myModal_espera').modal('hide');
            }, 2000);	
            $.each(response, function(i, item){
                cuentas+='<option value="'+response[i].Codigo+'" '+((i==0)?'selected':'')+'>'+response[i].Nombre_Cta+'</option>';
            });				
            if($.trim(cuentas) === ''){
                cuentas='<option value=".">Sin Cuentas</option>';
            }
            $('#DCCtas').html(cuentas);					    
            ConsultarDatosLibroBanco();				
        },
        error: function(xhr, status, error){
            setTimeout(()=>{
                $('#myModal_espera').modal('hide');
            }, 2000);
            console.error("Error en la solicitud: ", status, error);
        }
    });

}