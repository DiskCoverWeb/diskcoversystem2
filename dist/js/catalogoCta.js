function consultar_datos()
{
    tbl_catalogoCta = $('#tbl_tablaCta').DataTable({
        autoWidth: true, 
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
                    'txt_CtaI': $('#txt_CtaI').val(),
                    'txt_CtaF': $('#txt_CtaF').val()
                };
                console.log("Parametros: ",parametros)
                return { parametros:parametros }
            },
            dataSrc: function(response){
                console.log(response);
                return response.data;
            },
            beforeSend: function(){
                $('#myModal_espera').modal('show');
            },
            complete: function(){
                $('#myModal_espera').modal('hide');
            },
            error: function(xhr, status, error){
                console.log("Error en la solicitud: ", status, error);
            }
        },
        scrollX: true,
        scrollY: '300px', 
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

    tbl_catalogoCta = $('#tbl_tablaCtaGrupos').DataTable({
        autoWidth: true, 
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
                console.log("Parametros: ",parametros)
                return { parametros:parametros }
            },
            dataSrc: function(response){
                return response.data;
            },
            beforeSend: function(){
                $('#myModal_espera').modal('show');
            },
            complete: function(){
                $('#myModal_espera').modal('hide');
            },
            error: function(xhr, status, error){
                console.log("Error en la solicitud: ", status, error);
            }
        },
        scrollX: true,
        scrollY: '300px', 
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

    tbl_catalogoCta = $('#tbl_tablaCtaDetalles').DataTable({
        autoWidth: true, 
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
                console.log("Parametros: ",parametros)
                return { parametros:parametros }
            },
            dataSrc: function(response){
                return response.data;
            },
            beforeSend: function(){
                $('#myModal_espera').modal('show');
            },
            complete: function(){
                $('#myModal_espera').modal('hide');
            },
            error: function(xhr, status, error){
                console.log("Error en la solicitud: ", status, error);
            }
        },
        scrollX: true,
        scrollY: '300px', 
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
}
$(document).ready(function()
{
    consultar_datos();

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

    var url = '../controlador/contabilidad/catalogoCtaC.php?imprimir_excel=true&OpcT='+$("#OpcT").is(':checked')+'&OpcG='+$("#OpcG").is(':checked')+'&OpcD='+$("#OpcD").is(':checked')+'&txt_CtaI='+$('#txt_CtaI').val()+'&txt_CtaF='+$('#txt_CtaF').val();
        window.open(url, '_blank');
    });

    $('#imprimir_pdf').click(function(){      		

    var url = '../controlador/contabilidad/catalogoCtaC.php?imprimir_pdf=true&OpcT='+$("#OpcT").is(':checked')+'&OpcG='+$("#OpcG").is(':checked')+'&OpcD='+$("#OpcD").is(':checked')+'&txt_CtaI='+$('#txt_CtaI').val()+'&txt_CtaF='+$('#txt_CtaF').val();
        window.open(url, '_blank');
    });

    
});


