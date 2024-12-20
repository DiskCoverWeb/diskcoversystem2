function consultar_datos()
{
    if($.fn.dataTable.isDataTable('#tbl_tablaCta') && $.fn.dataTable.isDataTable('#tbl_tablaCtaGrupos') && $.fn.dataTable.isDataTable('#tbl_tablaCtaDetalles')){
        $('#tbl_tablaCta').DataTable().clear().destroy();
        $('#tbl_tablaCtaGrupos').DataTable().clear().destroy();
        $('#tbl_tablaCtaDetalles').DataTable().clear().destroy(); 
    }
    
    tbl_catalogoCta = $('#tbl_tablaCta').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        ajax: {
            url: '../controlador/contabilidad/catalogoCtaC.php?consultar=true',
            type: 'post',
            data: function(d){
                var parametros = {
                    'OpcT': true,
                    'OpcG': false,
                    'OpcD': false,
                    'txt_CtaI': $('#txt_CtaI').val() || "",
                    'txt_CtaF': $('#txt_CtaF').val() || ""
                };
                return { parametros:parametros }
            },
            dataSrc: function(response){
                return response;
            },
            beforeSend: function(){
                $('#myModal_espera').modal('show');
            },
            complete: function(){
                $('#myModal_espera').modal('hide');
                tbl_catalogoCta.columns.adjust().draw();
            },
            error: function(xhr, status, error){
                console.log("Error en la solicitud: ", status, error);
            }
        },
        scrollX: true,
        scrollY: '400px', 
        scrollCollapse: true,
        searching: false,
        columns: [
            { data: 'Clave' },
            { data: 'TC' },
            { data: 'ME' },
            { data: 'DG' },
            { data: 'Codigo' },
            { data: 'Cuenta' },
            { data: 'Presupuesto' },
            { data: 'Codigo_Ext' }
        ],
        order: [
            [0, 'asc']
        ]
    });

    tbl_catalogoCtaGrupos = $('#tbl_tablaCtaGrupos').DataTable({
        responsive: true, 
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        ajax: {
            url: '../controlador/contabilidad/catalogoCtaC.php?consultar=true',
            type: 'post',
            data: function(d){
                var parametros = {
                    'OpcT': false,
                    'OpcG': true, 
                    'OpcD': false, 
                    'txt_CtaI': $('#txt_CtaI').val(),
                    'txt_CtaF': $('#txt_CtaF').val()
                };
                return { parametros:parametros }
            },
            dataSrc: function(response){
                return response;
            },
            beforeSend: function(){
                $('#myModal_espera').modal('show');
            },
            complete: function(){
                $('#myModal_espera').modal('hide');
                tbl_catalogoCtaGrupos.columns.adjust().draw();
            },
            error: function(xhr, status, error){
                console.log("Error en la solicitud: ", status, error);
            }
        },
        searching: false,
        scrollX: true,
        scrollY: '400px', 
        scrollCollapse: true,
        columns: [
            { data: 'Clave' },
            { data: 'TC' },
            { data: 'ME' },
            { data: 'DG' },
            { data: 'Codigo' },
            { data: 'Cuenta' },
            { data: 'Presupuesto' },
            { data: 'Codigo_Ext' }
        ],
        order: [
            [0, 'asc']
        ]
    });

    tbl_catalogoCtaDetalles = $('#tbl_tablaCtaDetalles').DataTable({
        responsive: true, 
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        ajax: {
            url: '../controlador/contabilidad/catalogoCtaC.php?consultar=true',
            type: 'post',
            data: function(d){
                var parametros = {
                    'OpcT': false,
                    'OpcG': false,
                    'OpcD': true,
                    'txt_CtaI': $('#txt_CtaI').val(),
                    'txt_CtaF': $('#txt_CtaF').val()
                };
                return { parametros:parametros }
            },
            dataSrc: function(response){
                return response;
            },
            beforeSend: function(){
                $('#myModal_espera').modal('show');
            },
            complete: function(){
                $('#myModal_espera').modal('hide');
                tbl_catalogoCtaDetalles.columns.adjust().draw();
            },
            error: function(xhr, status, error){
                console.log("Error en la solicitud: ", status, error);
            }
        },
        scrollX: true,
        scrollY: '400px', 
        scrollCollapse: true,
        searching: false,
        columns: [
            { data: 'Clave' },
            { data: 'TC' },
            { data: 'ME' },
            { data: 'DG' },
            { data: 'Codigo' },
            { data: 'Cuenta' },
            { data: 'Presupuesto' },
            { data: 'Codigo_Ext' }
        ],
        order: [
            [0, 'asc']
        ]
    });
}
$(document).ready(function()
{
    var timeout; 
    consultar_datos();

    $('#txt_CtaI').keyup(function(e){ 
            clearTimeout(timeout)
            validar_cuenta(this);
            timeout = setTimeout(function(){
                consultar_datos(); 
            }, 500);
            
        })

    $('#txt_CtaF').keyup(function(e){ 
            clearTimeout(timeout)
            validar_cuenta(this);
            timeout = setTimeout(function(){
                consultar_datos(); 
            }, 500);
        })


    $('#imprimir_excel').click(function(){      		

    var url = '../controlador/contabilidad/catalogoCtaC.php?imprimir_excel=true&OpcT='+$("#OpcT").is(':checked')+'&OpcG='+$("#OpcG").is(':checked')+'&OpcD='+$("#OpcD").is(':checked')+'&txt_CtaI='+$('#txt_CtaI').val()+'&txt_CtaF='+$('#txt_CtaF').val();
        window.open(url, '_blank');
    });

    $('#imprimir_pdf').click(function(){      		

    var url = '../controlador/contabilidad/catalogoCtaC.php?imprimir_pdf=true&OpcT='+$("#OpcT").is(':checked')+'&OpcG='+$("#OpcG").is(':checked')+'&OpcD='+$("#OpcD").is(':checked')+'&txt_CtaI='+$('#txt_CtaI').val()+'&txt_CtaF='+$('#txt_CtaF').val();
        window.open(url, '_blank');
    });

    
});


