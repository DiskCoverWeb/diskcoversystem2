$(document).ready(function () {
     programas();

})
function programas()
{
    $('#ddl_programas').select2({
        placeholder: 'Seleccione programa',
        // dropdownAutoWidth: true,
      //  selectionCssClass: 'form-control form-control-sm h-100',  // Para el contenedor de Select2
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?LlenarSelects_Val=true&valor=85',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data.respuesta
            };
          },
          cache: true
        }
    });
}

function grupos()
{
    programa = $('#ddl_programas').val();
    $('#ddl_grupos').select2({
        placeholder: 'Seleccione grupo',
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?ddl_grupos=true&programa='+programa,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data.respuesta
            };
          },
          cache: true
        }
    });
}
