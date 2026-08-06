$(document).ready(function() {
    if ($.fn.dataTable) {

        var formateadorNumero = new Intl.NumberFormat('es-ES', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        var columnasTexto = [
            "RUC_CI", "AUTORIZACION", "FACTURA", "CODIGO", 
            "PRODUCTO", "ITEM", "A_NO", "ID", "NOMBRE", "DESCRIPCION",'ORDEN'
        ];

        var palabrasClaveFecha = ["FECHA", "DATE", "FEC_", "CREATED_AT", "UPDATED_AT"];

        // 1. HELPER PARA EXTRAER EL VALOR REAL (Procesa objetos Carbon/DateTime de PHP)
        function normalizarValor(valor) {
            if (valor !== null && typeof valor === 'object' && valor.date) {
                // Extrae "2026-08-06" de "2026-08-06 00:00:00.000000"
                return valor.date.split(' ')[0]; 
            }
            return valor;
        }

        function obtenerNombreColumna(settings, colIndex) {
            if (!settings || !settings.aoColumns || !settings.aoColumns[colIndex]) return '';
            var colData = settings.aoColumns[colIndex].data;
            if (typeof colData === 'string') return colData;
            if (typeof colData === 'number') return String(colData);
            return '';
        }

        function esColumnaExcluida(nombreColumna) {
            if (!nombreColumna) return false;
            return columnasTexto.includes(String(nombreColumna).toUpperCase());
        }

        function esFecha(nombreColumna, valor) {
            var valorNormal = normalizarValor(valor);
            var colUpper = String(nombreColumna || '').toUpperCase();
            
            var esNombreFecha = palabrasClaveFecha.some(function(keyword) {
                return colUpper.includes(keyword);
            });
            if (esNombreFecha) return true;

            if (typeof valorNormal === 'string') {
                var patronFecha = /^(\d{2}[-/]\d{2}[-/]\d{4}|\d{4}[-/]\d{2}[-/]\d{2})/;
                return patronFecha.test(valorNormal.trim());
            }

            return false;
        }

        function esNumeroValido(valor) {
            var valorNormal = normalizarValor(valor);
            if (valorNormal === null || valorNormal === undefined || valorNormal === '') return false;
            if (typeof valorNormal === 'object') return false;
            
            if (typeof valorNormal === 'string') {
                var textoLimpio = valorNormal.trim().replace(',', '.');
                if (/^0\d+/.test(textoLimpio)) return false;
                valorNormal = textoLimpio;
            }
            
            var num = Number(valorNormal);
            return !isNaN(num) && isFinite(num);
        }

        $.extend(true, $.fn.dataTable.defaults, {
            classes: {
                sTable: "table table-striped table-hover"
            },
            
            columnDefs: [{
                targets: '_all',
                render: function (data, type, row, meta) {
                    var valorLimpio = normalizarValor(data);
                    var nombreColumna = obtenerNombreColumna(meta.settings, meta.col);

                    // Si es una fecha (extrae solo 'YYYY-MM-DD' o la cadena limpia)
                    if (esFecha(nombreColumna, data)) {
                        return valorLimpio;
                    }

                    if (esColumnaExcluida(nombreColumna)) {
                        return valorLimpio;
                    }

                    if (type === 'display' && esNumeroValido(valorLimpio)) {
                        var numVal = typeof valorLimpio === 'string' ? Number(valorLimpio.trim().replace(',', '.')) : Number(valorLimpio);
                        return formateadorNumero.format(numVal);
                    }

                    return valorLimpio;
                },
                createdCell: function (td, cellData, rowData, rowIndex, colIndex) {
                    var api = this.api();
                    var nombreColumna = obtenerNombreColumna(api.settings()[0], colIndex);
                    var valorLimpio = normalizarValor(cellData);

                    // CASO 1: Es FECHA -> Centrar
                    if (esFecha(nombreColumna, cellData)) {
                        $(td).css('text-align', 'center');
                        $(td).css('color', '');
                    } 
                    // CASO 2: Es NÚMERO -> Derecha
                    else if (!esColumnaExcluida(nombreColumna) && esNumeroValido(valorLimpio)) {
                        var numVal = typeof valorLimpio === 'string' ? Number(valorLimpio.trim().replace(',', '.')) : Number(valorLimpio);
                        
                        $(td).css('text-align', 'right');
                        if (numVal < 0) {
                            $(td).css('color', '#dc3545');
                        } else {
                            $(td).css('color', '');
                        }
                    } 
                    // CASO 3: TEXTO -> Izquierda
                    else {
                        $(td).css('text-align', 'left');
                        $(td).css('color', '');
                    }
                }
            }]
        });
    }
});