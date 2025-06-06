function consultar_datos()
{
    $('#myModal_espera').modal('show');
    if($.fn.dataTable.isDataTable('#tbl_tablaCta')){
        $('#tbl_tablaCta').DataTable().clear().destroy();
    }
    var columnasIdxNum = [];
    tbl_catalogoCta = $('#tbl_tablaCta').DataTable({
        paging: false,
        searching: false,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        ajax: {
            url: '../controlador/contabilidad/catalogoCtaC.php?consultar=true',
            type: 'post',
            data: function(d){
                var parametros = {
                    'OpcT': $('#OpcT').prop('checked'),
                    'OpcG': $('#OpcG').prop('checked'),
                    'OpcD': $('#OpcD').prop('checked'),
                    'txt_CtaI': $('#txt_CtaI').val() || "",
                    'txt_CtaF': $('#txt_CtaF').val() || ""
                };
                return { parametros:parametros }
            },
            dataSrc: function(response){
                response = ProcesarDatos(response);
                return response;
            },
            // beforeSend: function(){
            //     $('#myModal_espera').modal('show');
            // },
            complete: function(){
                setTimeout(()=>{
                    $('#myModal_espera').modal('hide');
                }, 2000);
                tbl_catalogoCta.columns.adjust().draw();
                //consulta ajax dentro del dataTable, hacer esto
            },
            error: function(xhr, status, error){
                console.log("Error en la solicitud: ", status, error);
            }
        },
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
        createdRow: function(row, data){
            alignEnd(row, data);
        },
        order: [
            [0, 'asc']
        ]
    });

    // tbl_catalogoCtaGrupos = $('#tbl_tablaCtaGrupos').DataTable({
    //     responsive: true, 
    //     language: {
    //         url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
    //     },
    //     ajax: {
    //         url: '../controlador/contabilidad/catalogoCtaC.php?consultar=true',
    //         type: 'post',
    //         data: function(d){
    //             var parametros = {
    //                 'OpcT': false,
    //                 'OpcG': true, 
    //                 'OpcD': false, 
    //                 'txt_CtaI': $('#txt_CtaI').val(),
    //                 'txt_CtaF': $('#txt_CtaF').val()
    //             };
    //             return { parametros:parametros }
    //         },
    //         dataSrc: function(response){
    //             response = ProcesarDatos(response);
    //             return response;
    //         },
    //         beforeSend: function(){
    //             $('#myModal_espera').modal('show');
    //         },
    //         complete: function(){
    //             $('#myModal_espera').modal('hide');
    //             tbl_catalogoCtaGrupos.columns.adjust().draw();
    //         },
    //         error: function(xhr, status, error){
    //             console.log("Error en la solicitud: ", status, error);
    //         }
    //     },
    //     searching: false,
    //     scrollX: true,
    //     scrollY: '400px', 
    //     scrollCollapse: true,
    //     columns: [
    //         { data: 'Clave' },
    //         { data: 'TC' },
    //         { data: 'ME' },
    //         { data: 'DG' },
    //         { data: 'Codigo' },
    //         { data: 'Cuenta' },
    //         { data: 'Presupuesto' },
    //         { data: 'Codigo_Ext' }
    //     ],
    //     createdRow: function(row, data){
    //         alignEnd(row, data);
    //     },
    //     order: [
    //         [0, 'asc']
    //     ]
    // });

    // tbl_catalogoCtaDetalles = $('#tbl_tablaCtaDetalles').DataTable({
    //     responsive: true, 
    //     language: {
    //         url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
    //     },
    //     ajax: {
    //         url: '../controlador/contabilidad/catalogoCtaC.php?consultar=true',
    //         type: 'post',
    //         data: function(d){
    //             var parametros = {
    //                 'OpcT': false,
    //                 'OpcG': false,
    //                 'OpcD': true,
    //                 'txt_CtaI': $('#txt_CtaI').val(),
    //                 'txt_CtaF': $('#txt_CtaF').val()
    //             };
    //             return { parametros:parametros }
    //         },
    //         dataSrc: function(response){
    //             //Procesar Datos en Js Globales, para cambiar datos numericos. 
    //             response = ProcesarDatos(response);
    //             return response;
    //         },
    //         beforeSend: function(){
    //             $('#myModal_espera').modal('show');
    //         },
    //         complete: function(){
    //             $('#myModal_espera').modal('hide');
    //         },
    //         error: function(xhr, status, error){
    //             console.log("Error en la solicitud: ", status, error);
    //         }
    //     },
    //     scrollX: true,
    //     scrollY: '400px', 
    //     scrollCollapse: true,
    //     searching: false,
    //     columns: [
    //         { data: 'Clave' },
    //         { data: 'TC' },
    //         { data: 'ME' },
    //         { data: 'DG' },
    //         { data: 'Codigo' },
    //         { data: 'Cuenta' },
    //         { data: 'Presupuesto' },
    //         { data: 'Codigo_Ext' }
    //     ],
    //     createdRow: function(row, data){
    //         alignEnd(row, data);
    //     },
    //     order: [
    //         [0, 'asc']
    //     ]
    // });
}
$(document).ready(function()
{
    var timeout; 
    $('[data-bs-toggle="tooltip"]').tooltip();
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


