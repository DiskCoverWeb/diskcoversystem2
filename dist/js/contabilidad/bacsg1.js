var columnasNumber = ['Saldo_Anterior', 'Debitos', 'Creditos', 'Saldo_Total', 'Total_N6', 'Total_N5', 'Total_N4', 'Total_N3', 'Total_N2', 'Total_N1'];
$(document).ready(function()
{
    $('[data-bs-toggle="tooltip"]').tooltip();
    tipo_balance();
    cargar_datos('1','Balance de comprobacion');
    checkSucursal();
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

    $('#BC_BC').click(function(){

    })

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

function cargar_balance_consolidado(type){
    if ($.fn.DataTable.isDataTable('#tbl_datos')) {
        $('#tbl_datos').DataTable().destroy();
        $('#tbl_datos').empty();
    }
    var hasta = $('#hasta').val();
    var parametros = {
        'tipo_bal': type,
        'hasta': hasta,
    };
    $.ajax({
        data: {parametros:parametros},
        url: '../controlador/contabilidad/contabilidad_controller.php?datos_balance_consolidado=true',
        type: 'post',
        dataType: 'json',
        beforeSend: function(){
            $('#myModal_espera').modal('show');
        },
        success: function(response){
            if (response.length === 0) {
                console.warn("Datos vacios");
            } else {
                $('#tbl_datos').DataTable({
                    language:{
                        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json',
                    },
                    data: ProcesarDatos(response),
                    columns: Object.keys(response[0]).map(key => ({
                        data: key,
                        title: key,
                    })),
                    scrollX: true,
                    scrollY: '400px',
                    scrollCollapse: true,
                    drawCallback: function (){
                        Formato_datos_numericos();
                    }
                })
            }
            $('#myModal_espera').modal('hide');
        }
    });
}

function cargar_datos(item,nombre,imprimir=false)
{
    if ($.fn.DataTable.isDataTable('#tbl_datos')) {
        $('#tbl_datos').DataTable().destroy();
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
                if (response.data.length === 0) {
                    console.warn("Datos vacios");
                } else {
                    $('#tbl_datos').DataTable({
                        language:{
                            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json',
                        },
                        data: ProcesarDatos(response.data),
                        columns: Object.keys(response.data[0]).map(key => ({
                            data: key,
                            title: key,
                        })),
                        scrollX: true,
                        scrollY: '400px',
                        scrollCollapse: true,
                        drawCallback: function (){
                            Formato_datos_numericos();
                        }
                    })
                }
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
                if (response = '') {
                }
                else{
                    var table = $('#tbl_datos').DataTable({
                        language: { 
                            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                        },
                        data: ProcesarDatos(response.data), 
                        columns: Object.keys(response.data[0]).map(key => ({
                            data: key,
                            title: key,
                        })),
                        destroy: true,
                        drawCallback: function (){
                            Formato_datos_numericos();
                        },
                        scrollX: true,
                        scrollY: '400px',
                        scrollCollapse: true,
                    });
                }
                
                $('#myModal_espera').modal('hide');
        }
    });
}

function checkSucursal(){
    $.ajax({
        url: '../controlador/contabilidad/contabilidad_controller.php?check_sucursal=true',
        type: 'post',
        dataType: 'json',
        success: function(response){
            if(response.length > 0){
                $('#bc_btn').prop('disabled', false);
            } else {
                $('#bc_btn').prop('disabled', true);
            }
        }
    })
}

function Formato_datos_numericos(){
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
}