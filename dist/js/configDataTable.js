$(document).ready(function() {
    if ($.fn.dataTable) {

        var formateadorNumero = new Intl.NumberFormat('es-ES', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        function esNumeroValido(valor) {
            if (valor === null || valor === undefined || valor === '') return false;
            if (typeof valor === 'string') {
                var textoLimpio = valor.trim();
                if (/^0\d+/.test(textoLimpio)) return false;
            }
            var num = Number(valor);
            return !isNaN(num) && isFinite(num);
        }

        $.extend(true, $.fn.dataTable.defaults, {
            // 1. ASIGNAR CLASES DE BOOTSTRAP AUTOMÁTICAMENTE
            // 'table' es la base, 'table-striped' da el efecto rayado y 'table-hover' resalta la fila al pasar el cursor
            classes: {
                sTable: "table table-striped table-hover"
            },
            
            columnDefs: [{
                targets: '_all',
                render: function (data, type, row,meta) {
                    var nombreColumna = meta.settings.aoColumns[meta.col].data;
                    if (nombreColumna === "RUC_CI" || nombreColumna === "Autorizacion") {
                        return data;
                    }

                    if (type === 'display' && esNumeroValido(data)) {
                        return formateadorNumero.format(Number(data));
                    }

                    return data;
                },
                createdCell: function (td, cellData, rowData, rowIndex, colIndex) {
                    if (esNumeroValido(cellData)) {
                        var num = Number(cellData);
                        $(td).css('text-align', 'right');
                        if (num < 0) {
                            $(td).css('color', '#dc3545');
                        } else {
                            $(td).css('color', '');
                        }
                    } else {
                        $(td).css('text-align', 'left');
                        $(td).css('color', '');
                    }
                }
            }]
        });
    }
});