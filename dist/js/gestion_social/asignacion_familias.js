$(document).ready(function () {
     programas();
     tipoCompra();

})
function programas()
{
    $('#ddl_programas').select2({
        placeholder: 'Seleccione programa',
        // dropdownAutoWidth: true,
      //  selectionCssClass: 'form-control form-control-sm h-100',  // Para el contenedor de Select2
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?programas=true',
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
              results: data
            };
          },
          cache: true
        }
    });
}


function tipoCompra()
{
    $.ajax({
        url: '../controlador/inventario/asignacion_osC.php?tipo_asignacion=true',
        type: 'POST',
        dataType: 'json',
        // data: { param: datos },
    success: function (data) {
        console.log(data);

        var op = '';
        var option = '';
        data.forEach(function(item,i){
        // console.log(item);
            option+= '<div class="col-md-6 col-sm-6">'+
                        '<button type="button" class="btn btn-light btn-sm"><img src="../../img/png/'+item.Picture+'.png" onclick="cambiar_tipo_asig(\''+item.ID+'\')"  style="width: 60px;height: 60px;"></button><br>'+
                        '<b>'+item.Proceso+'</b>'+
                    '</div>';

            op+='<option value="'+item.ID+'">'+item.Proceso+'</option>';
        })

        $('#tipoCompra').html(op); 
        $('#pnl_tipo_empaque').html(option);   

        // llenarDatos(benefi);


            // llenarComboList(data,'tipoCompra');
            // console.log(data);
        },
        error: function (error) {
            console.log(error);
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