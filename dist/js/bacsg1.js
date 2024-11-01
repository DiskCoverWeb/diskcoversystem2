$(document).ready(function()
{
    tipo_balance();
    cargar_datos('1','Balance de comprobacion');

    $('#imprimir_excel').click(function(){

    var bal_ext = '00';
    var mes = 0;
    if($('#tbalan').prop('checked'))
    {
        bal_ext = $('#balance_ext').val();
    }
    if($('#txt_item').val()==2)
    {
        mes=1;
    }

        var url = '../controlador/contabilidad/contabilidad_controller.php?datos_balance_excel=true&desde='+$('#desde').val()+'&hasta='+$('#hasta').val()+'&ext='+bal_ext+'&check='+$('#tbalan').prop('checked')+'&tipo_p='+$('input:radio[name=optionsRadios]:checked').val()+'&tipo_b='+$('#txt_item').val()+'&coop=0&sucur=0&balMes='+mes+'&nom='+$('#lbl_titulo').text()+'&imp=true';                 
        window.open(url, '_blank');


    });


    $('#imprimir_pdf').click(function(){
        var bal_ext = '00';
        var mes = 0;
        if($('#tbalan').prop('checked'))
        {
            bal_ext = $('#balance_ext').val();
        }
        if($('#txt_item').val()==2)
        {
            mes=1;
        }
        var url = '../controlador/contabilidad/contabilidad_controller.php?reporte_pdf_bacsg=true&desde='+$('#desde').val()+'&hasta='+$('#hasta').val()+'&ext='+bal_ext+'&check='+$('#tbalan').prop('checked')+'&tipo_p='+$('input:radio[name=optionsRadios]:checked').val()+'&tipo_b='+$('#txt_item').val()+'&coop=0&sucur=0&balMes='+mes+'&nom='+$('#lbl_titulo').text()+'&imp=true';           
        window.open(url, '_blank');


    });


});



function mostrar_select()
{
    if($('#tbalan').prop('checked'))
    {
        $('#balance_ext').css('display','block');
    }else
    {

        $('#balance_ext').css('display','none');
    }
}


function tipo_balance()
{
    var option = '<option value="00">Selecione Tipo</option>'
    $.ajax({
        //data:  {parametros:parametros},
        url:   '../controlador/contabilidad/contabilidad_controller.php?tipo_balance=true',
        type:  'post',
        dataType: 'json',			
            success:  function (response) {
                $.each(response,function(i,item){
                    option+='<option value="'+item.Codigo+'">'+item.Detalle+'</option>';
                })

                $('#balance_ext').html(option);
            
        }
    });
}
function cargar_datos(item,nombre,imprimir=false)
{
    $('#txt_item').val(item);
    var bal_ext = '00';
    var mes = 0;
    if($('#tbalan').prop('checked'))
    {
        bal_ext = $('#balance_ext').val();
    }
    if(item==2)
    {
        mes=1;
    }
    var parametros = 
    {
        'desde':$('#desde').val(),
        'hasta':$('#hasta').val(),
        'ext':bal_ext,
        'check':$('#tbalan').prop('checked'),
        'tipo_p':$('input:radio[name=optionsRadios]:checked').val(),
        'tipo_b':item,
        'coop':0,
        'sucur':0,
        'balMes':mes,
        'nom':nombre,
        'imp':imprimir,
    }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/contabilidad/contabilidad_controller.php?datos_balance=true',
        type:  'post',
        dataType: 'json',
            beforeSend: function () {   
                $('#myModal_espera').modal('show');
            },		
            success:  function (response) {

                console.log(response);
                // $.each(response,function(i,item){
                // 	option+='<option value="'+item.Codigo+'">'+item.Detalle+'</option>';
                // })

                $('#tabla').html(response);
                $('#myModal_espera').modal('hide');
            
        }
    });
}


function cargar_tabla()
{
    
    $.ajax({
        // data:  {parametros:parametros},
        url:   '../controlador/contabilidad/contabilidad_controller.php?datos_tabla=true',
        type:  'post',
        dataType: 'json',
            beforeSend: function () {   
                $('#myModal_espera').modal('show');
            },		
            success:  function (response) {

                console.log(response);
                $('#tabla').html(response);
                $('#myModal_espera').modal('hide');
            
        }
    });


}