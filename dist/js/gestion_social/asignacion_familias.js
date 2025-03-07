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


 function guardar()
    {
        ben = $('#beneficiario').val();
        distribuir = $('#CantGlobDist').val();
        if(ben=='' || ben==null){Swal.fire("","Seleccione un Beneficiario","info");return false;}
        if(distribuir==0 || distribuir==''){ Swal.fire("","No se a agregado nigun grupo de producto","info");return false;}
        var parametros = {
            'beneficiario':ben,
            'fecha':$('#fechAten').val(),
        }
         $.ajax({
            url: '../controlador/inventario/asignacion_osC.php?GuardarAsignacion=true',
            type: 'post',
            dataType: 'json',
            data: { parametros: parametros },
            success: function (datos) {
                if(datos==1)
                {
                    Swal.fire("Asignacion Guardada","","success").then(function(){
                        location.reload();
                    });
                }

            }
        });
    }

    function add_beneficiario(){
        $('#modal_addBeneficiario').modal('show');
    }