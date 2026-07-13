let tablaKardex;
  $(document).ready(function() {
    DCBodega();
    DCTInv();
    DCTipoBusqueda();
    DCCtaInv();
    DCSubModulo();


    tablaKardex = $('#tbl_existencias').DataTable({
        // responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        ajax: {           
            url: '../controlador/inventario/resumen_existenciasC.php?Listatabla=true',
            type: 'POST',  // Cambia el método a POST  
            data: function(d) {
                  var parametros = {
                    'ci':'sss'
                  };
                  return { parametros: parametros };
            },  
            dataSrc: '',             
        },         
        info: false, 
        searching: false,  
        paging: false,  
        columns: [
            { data:'TC'},
            { data: 'Codigo_Inv'},
            { data: 'Producto'},
            { data: 'Unidad' },
            { data: 'Stock_Anterior'},
            { data: 'Entradas'},
            { data: 'Salidas' },
            { data: 'Stock_Actual'},
            { data: 'Promedio'},
            { data: 'PVP' , 
                render: function(data, type, item) {
                    return data ? parseFloat(data).toFixed(2) : '0.00';
                }
            },
            { data: 'Valor_Total', 
                render: function(data, type, item) {
                    return data ? parseFloat(data).toFixed(2) : '0.00';
                }
            }
        ], 
        order: [
            [1, 'asc']
        ]
    });
  });

function DCBodega(){
    $('#DCBodega').select2({
        placeholder: 'Seleccione',
        allowClear: true,
        // width:'resolve',
        // selectionCssClass: 'form-control form-control-sm h-100',  // Para el contenedor de Select2
        ajax: {
          url:   '../controlador/inventario/resumen_existenciasC.php?DCBodega=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });
}

function DCTInv(){
    $('#DCTInv').select2({
        placeholder: 'Seleccione',
        allowClear: true,
        // width:'resolve',
        // selectionCssClass: 'form-control form-control-sm h-100',  // Para el contenedor de Select2
        ajax: {
          url:   '../controlador/inventario/resumen_existenciasC.php?DCTInv=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });
}

function DCTipoBusqueda(){
    var cbx = $('input[name="rbx_producto"]:checked').val();
    var DCInv = $('#DCTInv').val();
    $('#DCTipoBusqueda').select2({
        placeholder: 'Seleccione',
        allowClear: true,
        // width:'resolve',
        // selectionCssClass: 'form-control form-control-sm h-100',  // Para el contenedor de Select2
        ajax: {
          url:   '../controlador/inventario/resumen_existenciasC.php?DCTipoBusqueda=true&cbx='+cbx+'&DCInvSelec='+DCInv,
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });
}

function DCCtaInv(){
    var cbx = $('input[name="rbx_tipo_cta"]:checked').val();
    $('#DCCtaInv').select2({
        placeholder: 'Seleccione',
        allowClear: true,
        // width:'resolve',
        // selectionCssClass: 'form-control form-control-sm h-100',  // Para el contenedor de Select2
        ajax: {
          url:   '../controlador/inventario/resumen_existenciasC.php?DCCtaInv=true&cbx='+cbx,
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });
}

function DCSubModulo(){
    var cbx = $('input[name="rbx_subModulo"]:checked').val();
    $('#DCSubModulo').select2({
        placeholder: 'Seleccione',
        allowClear: true,
        // width:'resolve',
        // selectionCssClass: 'form-control form-control-sm h-100',  // Para el contenedor de Select2
        ajax: {
          url:   '../controlador/inventario/resumen_existenciasC.php?DCSubModulo=true&cbx='+cbx,
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });
}

function Resumen_QR()
{
    var parametros = {
        'inicial':$('#txt_inicial').val(),
        'final':$('#txt_final').val(),
    };
    $.ajax({
      url: '../controlador/inventario/resumen_existenciasC.php?Resumen_QR=true',
      type: 'POST',
      data: {'parametros': parametros},
      dataType:'json',
      success: function(response) {


        // console.log(response);

         // 1. Destruir la DataTable actual
            if ($.fn.DataTable.isDataTable('#tbl_existencias')) {
                $('#tbl_existencias').DataTable().destroy();
            }
            
            // 2. Limpiar el tbody y el thead
            $('#tbl_existencias thead').empty();
            $('#tbl_existencias tbody').empty();
            
            // 3. Construir dinámicamente las columnas según la respuesta
            if (response.data.length > 0) {
                // Obtener las claves del primer objeto como columnas
                var columnas = Object.keys(response.data[0]);
                
                // Construir el thead
                var theadHtml = '<tr>';
                columnas.forEach(function(col) {
                    theadHtml += '<th>' + col.replace(/_/g, ' ') + '</th>';
                });
                theadHtml += '</tr>';
                $('#tbl_existencias thead').html(theadHtml);
                
                // console.log(columnas);
                // Construir columns array para DataTable
                var columns = columnas.map(function(col) {
                    return { data: col };
                });
                
                // 4. Recrear DataTable con las nuevas columnas
                $('#tbl_existencias').DataTable({
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                    },
                    data: response.data,
                    columns: columns,
                    info: false,
                    searching: false,
                    paging: false
                });
        }

        // console.log(response);
      }
    });
}

function Resumen_Barras()
{
    var parametros = {
        'inicial':$('#txt_inicial').val(),
        'final':$('#txt_final').val(),
        'cbxpro': $('input[name="rbx_producto"]:checked').val(),
        'CheqBod':$('#CheqBod').prop('checked'),
        'CheqProducto':$('#CheqProducto').prop('checked'),
        'CheqMonto':$('#CheqMonto').prop('checked'),
        'CheqExist':$('#CheqExist').prop('checked'),
        'DCTipoBusqueda':$('#DCTipoBusqueda').val(),
        'TxtMonto':$('#TxtMonto').val(),
        'DCInv':$('#DCTInv').val(),
    };
    $.ajax({
      url: '../controlador/inventario/resumen_existenciasC.php?Resumen_Barras=true',
      type: 'POST',
      data: {'parametros': parametros},
      dataType:'json',
      success: function(response) {


        // console.log(response);

         // 1. Destruir la DataTable actual
            if ($.fn.DataTable.isDataTable('#tbl_existencias')) {
                $('#tbl_existencias').DataTable().destroy();
            }
            
            // 2. Limpiar el tbody y el thead
            $('#tbl_existencias thead').empty();
            $('#tbl_existencias tbody').empty();
            
            // 3. Construir dinámicamente las columnas según la respuesta
            if (response.data.length > 0) {
                // Obtener las claves del primer objeto como columnas
                var columnas = Object.keys(response.data[0]);
                
                // Construir el thead
                var theadHtml = '<tr>';
                columnas.forEach(function(col) {
                    theadHtml += '<th>' + col.replace(/_/g, ' ') + '</th>';
                });
                theadHtml += '</tr>';
                $('#tbl_existencias thead').html(theadHtml);
                
                // console.log(columnas);
                // Construir columns array para DataTable
                var columns = columnas.map(function(col) {
                    return { data: col };
                });
                
                // 4. Recrear DataTable con las nuevas columnas
                $('#tbl_existencias').DataTable({
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                    },
                    data: response.data,
                    columns: columns,
                    info: false,
                    searching: false,
                    paging: false
                });
        }

        // console.log(response);
      }
    });
}

function Resumen_Lote()
{
     var parametros = {
        'inicial':$('#txt_inicial').val(),
        'final':$('#txt_final').val(),
        'cbxpro': $('input[name="rbx_producto"]:checked').val(),
        'CheqBod':$('#CheqBod').prop('checked'),
        'CheqProducto':$('#CheqProducto').prop('checked'),
        'CheqMonto':$('#CheqMonto').prop('checked'),
        'CheqExist':$('#CheqExist').prop('checked'),
        'DCTipoBusqueda':$('#DCTipoBusqueda').val(),
        'TxtMonto':$('#TxtMonto').val(),
        'DCInv':$('#DCTInv').val(),
    };
    $.ajax({
      url: '../controlador/inventario/resumen_existenciasC.php?Resumen_Lote=true',
      type: 'POST',
      data: {'parametros': parametros},
      dataType:'json',
      success: function(response) {


        // console.log(response);

         // 1. Destruir la DataTable actual
            if ($.fn.DataTable.isDataTable('#tbl_existencias')) {
                $('#tbl_existencias').DataTable().destroy();
            }
            
            // 2. Limpiar el tbody y el thead
            $('#tbl_existencias thead').empty();
            $('#tbl_existencias tbody').empty();
            
            // 3. Construir dinámicamente las columnas según la respuesta
            if (response.data.length > 0) {
                // Obtener las claves del primer objeto como columnas
                var columnas = Object.keys(response.data[0]);
                
                // Construir el thead
                var theadHtml = '<tr>';
                columnas.forEach(function(col) {
                    theadHtml += '<th>' + col.replace(/_/g, ' ') + '</th>';
                });
                theadHtml += '</tr>';
                $('#tbl_existencias thead').html(theadHtml);
                
                // console.log(columnas);
                // Construir columns array para DataTable
                var columns = columnas.map(function(col) {
                    return { data: col };
                });
                
                // 4. Recrear DataTable con las nuevas columnas
                $('#tbl_existencias').DataTable({
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                    },
                    data: response.data,
                    columns: columns,
                    info: false,
                    searching: false,
                    paging: false
                });
        }

        // console.log(response);
      }
    });

}

function Stock(StockSuperior)
{
     var parametros = {
        'inicial':$('#txt_inicial').val(),
        'final':$('#txt_final').val(),
        'cbxpro': $('input[name="rbx_producto"]:checked').val(),
        'CheqBod':$('#CheqBod').prop('checked'),
        'CheqProducto':$('#CheqProducto').prop('checked'),
        'CheqMonto':$('#CheqMonto').prop('checked'),
        'CheqExist':$('#CheqExist').prop('checked'),
        'CheqGrupo':$('#CheqGrupo').prop('checked'),
        'DCTipoBusqueda':$('#DCTipoBusqueda').val(),
        'TxtMonto':$('#TxtMonto').val(),
        'DCInv':$('#DCTInv').val(),
        'StockSuperior':StockSuperior,
    };
    $.ajax({
      url: '../controlador/inventario/resumen_existenciasC.php?Stock=true',
      type: 'POST',
      data: {'parametros': parametros},
      dataType:'json',
      success: function(response) {


        // console.log(response);

         // 1. Destruir la DataTable actual
            if ($.fn.DataTable.isDataTable('#tbl_existencias')) {
                $('#tbl_existencias').DataTable().destroy();
            }
            
            // 2. Limpiar el tbody y el thead
            $('#tbl_existencias thead').empty();
            $('#tbl_existencias tbody').empty();
            
            // 3. Construir dinámicamente las columnas según la respuesta
            if (response.data.length > 0) {
                // Obtener las claves del primer objeto como columnas
                var columnas = Object.keys(response.data[0]);
                
                // Construir el thead
                var theadHtml = '<tr>';
                columnas.forEach(function(col) {
                    theadHtml += '<th>' + col.replace(/_/g, ' ') + '</th>';
                });
                theadHtml += '</tr>';
                $('#tbl_existencias thead').html(theadHtml);
                
                // console.log(columnas);
                // Construir columns array para DataTable
                var columns = columnas.map(function(col) {
                    return { data: col };
                });
                
                // 4. Recrear DataTable con las nuevas columnas
                $('#tbl_existencias').DataTable({
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                    },
                    data: response.data,
                    columns: columns,
                    info: false,
                    searching: false,
                    paging: false
                });
        }

        // console.log(response);
      }
    });

}