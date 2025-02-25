$(document).ready(function()
{
    let param_datatable = {
        // responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        scrollX: true,
        paging:false,
        searching:false,
        info:false,
        scrollY: 330,
        scrollCollapse: true,
    };

    tbl_DGQuery2 = $('#tbl_DGQuery2').DataTable(param_datatable);
    tbl_DGQuery3 = $('#tbl_DGQuery3').DataTable(param_datatable);
    //tbl_DGQuery = $('#tbl_DGQuery').DataTable(param_datatable);


    asignarHeightPantalla($("#DCSubModulo"), $("#heightDisponible"))
    document.title = "Diskcover | RESUMEN DE EXISTENCIAS";
    Listar_DCBodega();
    ListarProductosResumenK();

    Listar_X_Producto()
    Listar_X_Tipo_SubModulo()
    Listar_X_Tipo_Cta()

    cargar_tabla();
    

    $('input[name="ProductoPor"]').change(function() {
        Listar_X_Producto()
    });

    $('input[name="TipoCuentaDe"]').change(function() {
        Listar_X_Tipo_Cta()
    });

    $('input[name="SuModeloDe"]').change(function() {
        Listar_X_Tipo_SubModulo()
    });

    $("#MBoxFechaI").focus()
});

function cargar_tabla(){
    tbl_DGQuery = $('#tbl_DGQuery').DataTable({
        // responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        /*columnDefs: [
            { targets: [8,9,10,11,12,13], className: 'text-end' } // Alinea las columnas 0, 2 y 4 a la derecha
        ],*/
        ajax: {
            url: '../controlador/inventario/ResumenKC.php?Form_Activate=true',
            type: 'POST',  // Cambia el método a POST    
            data: function(d) {
                return $("#FormResumenK").serialize();
            }, 
            dataSrc: ''
        },
        scrollX: true,  // Habilitar desplazamiento horizontal
        //     paging:false,
        //     searching:false,
        //     info:false,
        fixedHeader: true,
        responsive: true,
        scrollY: '330px',
        scrollCollapse: true,
        columns: [
            // { data: 'TC' , width:'40px'},
            // { data: 'Codigo_Inv' , width:'200px'},
            // { data: 'Producto' , width:'300px'},
            // { data: 'Unidad' , width:'136px'},
            // { data: 'Stock_Anterior' , width:'64px', className: 'text-end'},
            // { data: 'Entradas' , width:'64px', className: 'text-end'},
            // { data: 'Salidas' , width:'64px', className: 'text-end'},
            // { data: 'Stock_Actual' , width:'64px', className: 'text-end'},
            // { data: 'Promedio' , width:'64px', className: 'text-end'},
            // { data: 'PVP' , width:'64px', className: 'text-end'},
            // { data: 'Valor_Total' , width:'64px', className: 'text-end'}
            { data: 'TC' , },
            { data: 'Codigo_Inv' , },
            { data: 'Producto' , },
            { data: 'Unidad' , },
            { data: 'Stock_Anterior' , },
            { data: 'Entradas' , },
            { data: 'Salidas' , },
            { data: 'Stock_Actual' , },
            { data: 'Promedio' , },
            { data: 'PVP' , },
            { data: 'Valor_Total',  
                render: function(data, type, item) {
                    return data ? data : 0;
                }
            }
        ]
    });

    // $('#myModal_espera').modal('show');
    // $.ajax({
    //     type: "POST",
    //     url: '../controlador/inventario/ResumenKC.php?Form_Activate=true',
    //     dataType: 'json',
    //     data: $("#FormResumenK").serialize(), 
    //     success: function(data)             
    //     {
    //         //$('#DGQuery').html(data.DGQuery);   
    //         $('#myModal_espera').modal('hide');     
    //     }
    // });
}

function Listar_DCBodega(){
    //let codigoProducto = '';
    $('#DCBodega').select2({
        placeholder: '** Seleccionar Bodega **',
        dropdownParent: $('#FormResumenK'),
        ajax: {
            url: '../controlador/inventario/ResumenKC.php?bodegas=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}

function ListarProductosResumenK(){
    //let codigoProducto = '';
    $('#DCTInv').select2({
        placeholder: '** Seleccionar Grupo **',
        dropdownParent: $('#FormResumenK'),
        ajax: {
            url: '../controlador/inventario/ResumenKC.php?ListarProductosResumenK=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}

function ConsultarStock(StockSuperior) {
    $('#DGQuery').hide();
    tbl_DGQuery.destroy();
    $('#DGQuery3').hide();
    tbl_DGQuery3.destroy();

    $('#DGQuery2').show();
    tbl_DGQuery2.destroy();
    tbl_DGQuery2 = $('#tbl_DGQuery2').DataTable({
        // responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        /*columnDefs: [
            { targets: [8,9,10,11,12,13], className: 'text-end' } // Alinea las columnas 0, 2 y 4 a la derecha
        ],*/
        ajax: {
            url: '../controlador/inventario/ResumenKC.php?ConsultarStock=true&StockSuperior='+StockSuperior,
            type: 'POST',  // Cambia el método a POST    
            data: function(d) {
                return $("#FormResumenK").serialize();
            }, 
            dataSrc: function(json) {
                $('#LabelTot').val(json.LabelTot); 
                return json.DGQuery;
            }                     
        },
        scrollX: true,  // Habilitar desplazamiento horizontal
        //     paging:false,
        //     searching:false,
        //     info:false,
        fixedHeader: true,
        responsive: true,
        scrollY: '330px',
        scrollCollapse: true,
        columns: [
            { data: 'TC' , width:'40px'},
            { data: 'Codigo_Inv' , width:'200px'},
            { data: 'Producto' , width:'300px'},
            { data: 'Unidad' , width:'136px'},
            { data: 'Stock_Anterior' , width:'64px'},
            { data: 'Entradas' , width:'64px'},
            { data: 'Salidas' , width:'64px'},
            { data: 'Stock_Actual' , width:'64px'},
            { data: 'Costo_Unit' , width:'64px'},
            { data: 'Total' , width:'112px', className: 'text-end'},
            { data: 'Diferencias' , width:'136px', className: 'text-end'},
            { data: 'Bodega' , width:'0px'},
            { data: 'Ubicacion' , width:'400px'},
        ]
    });
    // $('#myModal_espera').modal('show');
    // $.ajax({
    //     type: "POST",                 
    //     url: '../controlador/inventario/ResumenKC.php?ConsultarStock=true&StockSuperior='+StockSuperior,
    //     dataType: 'json',
    //     data: $("#FormResumenK").serialize(), 
    //     success: function(data)             
    //     {
    //         if (data.error) {
    //             Swal.fire({
    //                 type: 'warning',
    //                 title: '',
    //                 text: data.mensaje
    //             });
    //         }else{
    //             $('#DGQuery').html(data.DGQuery);   
    //             $('#LabelTot').val(data.LabelTot); 
    //         }   
    //         $('#myModal_espera').modal('hide');     
    //     }
    // });
}
function Imprimir_ResumenK() {
    url = '../controlador/inventario/ResumenKC.php?Imprimir_ResumenK=true&'+$("#FormResumenK").serialize();
    window.open(url, '_blank');
}

function generarExcelResumenK(){
    url = '../controlador/inventario/ResumenKC.php?generarExcelResumenK=true&'+$("#FormResumenK").serialize();
    window.open(url, '_blank');
}

function Listar_X_Producto() {
    $('#DCTipoBusqueda').select2({
        placeholder: '** Seleccionar**',
        dropdownParent: $('#FormResumenK'),
        ajax: {
            url: '../controlador/inventario/ResumenKC.php?Listar_Por_Producto=true&'+$("#FormResumenK").serialize(),
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data.DCTipoBusqueda
                };
            },
            cache: true
        }
    });
    // $.ajax({
    //     type: 'POST',
    //     dataType: 'json',
    //     url: '../controlador/inventario/ResumenKC.php?Listar_Por_Producto=true',
    //     data: $("#FormResumenK").serialize(), 
    //     beforeSend: function () {   
    //         $('#myModal_espera').modal('show');
    //     },    
    //     success: function(response)
    //     { 
    //         if(response.DCTipoBusqueda){
    //             $('#myModal_espera').modal('hide');
    //             llenarComboList(response.DCTipoBusqueda,'DCTipoBusqueda')
    //             agregarOpcionPorDefecto('DCTipoBusqueda');
    //             $("#DCTipoBusqueda").focus();
    //         }else{
    //             $('#myModal_espera').modal('hide');
    //             Swal.fire('¡Oops!', response.mensaje, 'warning')
    //         }
    //     },
    //     error: function () {
    //         $('#myModal_espera').modal('hide');
    //         alert("Ocurrio un error inesperado, por favor contacte a soporte.");
    //     }
    // });
}

function Listar_X_Tipo_SubModulo() {
    $('#DCSubModulo').select2({
        placeholder: '** Seleccionar Modulo**',
        dropdownParent: $('#FormResumenK'),
        ajax: {
            url: '../controlador/inventario/ResumenKC.php?Listar_Por_Tipo_SubModulo=true&'+$("#FormResumenK").serialize(),
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data.DCSubModulo
                };
            },
            cache: true
        }
    });

    // $.ajax({
    //     type: 'POST',
    //     dataType: 'json',
    //     url: '../controlador/inventario/ResumenKC.php?Listar_Por_Tipo_SubModulo=true',
    //     data: $("#FormResumenK").serialize(), 
    //     beforeSend: function () {   
    //         $('#myModal_espera').modal('show');
    //     },    
    //     success: function(response)
    //     { 
    //         if(response.DCSubModulo){
    //             $('#myModal_espera').modal('hide');
    //             llenarComboList(response.DCSubModulo,'DCSubModulo')
    //             agregarOpcionPorDefecto('DCSubModulo');
    //             $("#DCSubModulo").focus();
    //         }else{
    //             $('#myModal_espera').modal('hide');
    //             Swal.fire('¡Oops!', response.mensaje, 'warning')
    //         }
    //     },
    //     error: function () {
    //         $('#myModal_espera').modal('hide');
    //         alert("Ocurrio un error inesperado, por favor contacte a soporte.");
    //     }
    // });
}

function Listar_X_Tipo_Cta() {
    $('#DCCtaInv').select2({
        placeholder: '** Seleccionar Cuenta**',
        dropdownParent: $('#FormResumenK'),
        ajax: {
            url: '../controlador/inventario/ResumenKC.php?Listar_Por_Tipo_Cta=true&'+$("#FormResumenK").serialize(),
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data.DCCtaInv
                };
            },
            cache: true
        }
    });

    // $.ajax({
    //     type: 'POST',
    //     dataType: 'json',
    //     url: '../controlador/inventario/ResumenKC.php?Listar_Por_Tipo_Cta=true',
    //     data: $("#FormResumenK").serialize(), 
    //     beforeSend: function () {   
    //         $('#myModal_espera').modal('show');
    //     },    
    //     success: function(response)
    //     { 
    //         if(response.DCCtaInv){
    //             $('#myModal_espera').modal('hide');
    //             llenarComboList(response.DCCtaInv,'DCCtaInv')
    //             agregarOpcionPorDefecto('DCCtaInv');
    //             $("#DCCtaInv").focus();
    //         }else{
    //             $('#myModal_espera').modal('hide');
    //             Swal.fire('¡Oops!', response.mensaje, 'warning')
    //         }
    //     },
    //     error: function () {
    //         $('#myModal_espera').modal('hide');
    //         alert("Ocurrio un error inesperado, por favor contacte a soporte.");
    //     }
    // });
}

function ConsultarResumen(tipo) {
    $('#DGQuery').hide();
    tbl_DGQuery.destroy();
    $('#DGQuery2').hide();
    tbl_DGQuery2.destroy();

    $('#DGQuery3').show();
    tbl_DGQuery3.destroy();
    tbl_DGQuery3 = $('#tbl_DGQuery3').DataTable({
        // responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        /*columnDefs: [
            { targets: [8,9,10,11,12,13], className: 'text-end' } // Alinea las columnas 0, 2 y 4 a la derecha
        ],*/
        ajax: {
            url: '../controlador/inventario/ResumenKC.php?Resumen_'+tipo+'=true',
            type: 'POST',  // Cambia el método a POST    
            data: function(d) {
                return $("#FormResumenK").serialize();
            }, 
            dataSrc: function(json) {
                $('#LabelStock').val(json.LabelStock); 
                return json.DGQuery;
            }                     
        },
        scrollX: true,  // Habilitar desplazamiento horizontal
        //     paging:false,
        //     searching:false,
        //     info:false,
        fixedHeader: true,
        responsive: true,
        scrollY: '330px',
        scrollCollapse: true,
        columns: [
            { data: 'Serie_No'},
            { data: 'Detalle'},
            { data: 'Promedio'},
            { data: 'Saldo_Ant'},
            { data: 'Entradas'},
            { data: 'Salidas'},
            { data: 'Stock_Act'}
        ]
    });

    // $('#myModal_espera').modal('show');
    // $.ajax({
    //     type: "POST",                 
    //     url: '../controlador/inventario/ResumenKC.php?Resumen_Lote=true',
    //     dataType: 'json',
    //     data: $("#FormResumenK").serialize(), 
    //     success: function(data)             
    //     {
    //         $('#tbl_DGQuery3').html(data.DGQuery);   
    //         $('#LabelStock').val(data.LabelStock); 
    //         $('#myModal_espera').modal('hide');     
    //     }
    // });
}

function ConsultarResumen_Barras() {
    $('#DGQuery').hide();
    tbl_DGQuery.destroy();
    $('#DGQuery2').hide();
    tbl_DGQuery2.destroy();

    $('#DGQuery3').show();
    tbl_DGQuery3.destroy();
    tbl_DGQuery3 = $('#tbl_DGQuery3').DataTable({
        // responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        /*columnDefs: [
            { targets: [8,9,10,11,12,13], className: 'text-end' } // Alinea las columnas 0, 2 y 4 a la derecha
        ],*/
        ajax: {
            url: '../controlador/inventario/ResumenKC.php?Resumen_Lote=true',
            type: 'POST',  // Cambia el método a POST    
            data: function(d) {
                return $("#FormResumenK").serialize();
            }, 
            dataSrc: function(json) {
                $('#LabelStock').val(json.LabelStock); 
                return json.DGQuery;
            }                     
        },
        scrollX: true,  // Habilitar desplazamiento horizontal
        //     paging:false,
        //     searching:false,
        //     info:false,
        fixedHeader: true,
        responsive: true,
        scrollY: '330px',
        scrollCollapse: true,
        columns: [
            { data: 'Serie_No'},
            { data: 'Detalle'},
            { data: 'Promedio'},
            { data: 'Saldo_Ant'},
            { data: 'Entradas'},
            { data: 'Salidas'},
            { data: 'Stock_Act'}
        ]
    });
    
    $('#myModal_espera').modal('show');
    $.ajax({
        type: "POST",                 
        url: '../controlador/inventario/ResumenKC.php?Resumen_Barras=true',
        dataType: 'json',
        data: $("#FormResumenK").serialize(), 
        success: function(data)             
        {
            $('#DGQuery').html(data.DGQuery);   
            $('#LabelStock').val(data.LabelStock); 
            $('#myModal_espera').modal('hide');     
        }
    });
}