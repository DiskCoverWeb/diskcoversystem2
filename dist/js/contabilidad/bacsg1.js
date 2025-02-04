var columnasNumber = ['Saldo_Anterior', 'Debitos', 'Creditos', 'Saldo_Total', 'Total_N6', 'Total_N5', 'Total_N4', 'Total_N3', 'Total_N2', 'Total_N1'];
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

function DataTableColums(firstRow){
    if(!firstRow) return [];

    return Object.keys(firstRow).map(function(key){
        return {
            data: key, 
            title: key.charAt(0).toUpperCase() + key.slice(1).replace(/_/g, '')
        };
    });
}

function cargar_datos(item,nombre,imprimir=false)
{
    if($.fn.dataTable.isDataTable('#tbl_datos')){
        console.log("Destruyendo la tabla...")
        $('#tbl_datos').DataTable().clear().destroy(); 
        $('#tbl_datos').empty();
    }
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
                $('#tbl_datos').DataTable({
                    paging: false,     // Desactiva la paginación
                    info: false,       // Opcional: Desactiva la información (ej. "Mostrando 1 a 10 de 100 registros")        
                    language:{
                        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                    },
                    data: response.data, 
                    columns: Object.keys(response.data[0]).map(key => ({
                        data: key,
                        title: key
                    })), 
                    scrollY: '300px',
                });
                $('#tbl_datos').on('init.dt', function() {
                    $('#tbl_datos th').each(function(index) {
                        var columnName = $(this).attr('aria-label')?.split(':')[0] || $(this).text().trim();
                        if(columnasNumber.includes(columnName)){
                            $('#tbl_datos tbody tr').each(function(){
                                var cell = $(this).find('td').eq(index);
                                var numericValue = parseFloat($(cell).text());
                                if (isFinite(numericValue)){
                                    var formattedValue = numericValue.toFixed(2);
                                    $(cell).text(formattedValue);
                                    if(numericValue < 0 ){ 
                                        $(cell).css('color', 'red');
                                    }
                                }
                            })
                        }
                    });
                });
                $('#myModal_espera').modal('hide');
        }
    });
}



function cargar_tabla()
{
    console.log("cargar tabla")
    $.ajax({
        // data:  {parametros:parametros},
        url:   '../controlador/contabilidad/contabilidad_controller.php?datos_tabla=true',
        type:  'post',
        dataType: 'json',
            beforeSend: function () {   
                $('#myModal_espera').modal('show');
            },		
            success:  function (response) {
                if ($.fn.dataTable.isDataTable('#tbl_datos')){ 
                    $('#tbl_datos').DataTable().clear().destroy(true);
                    $('$tbl_datos').html('');
                }
                var columnas = Object.keys(response.data[0]).map(function(key){
                    return { data: key, title: key };
                });


                console.log("Columnas: ", columnas);
                console.log("Datos muestra: ", response.data[0]);
                var table = $('#tbl_datos').DataTable({
                    language: { 
                        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                    },
                    data: response.data, 
                    scrollX: true, 
                    scrollY: '300px',
                    scrollCollapse: true,
                    columns: columnas
                });
                /**/
                $('#myModal_espera').modal('hide');
            
        }
    });


}