  const hoy = new Date(); 
  const mes = hoy.getMonth(); 
  
  $(document).ready(function () {
    ddl_indicador_gestion();

        //enviar datos del cliente
    $('#ddl_indicador_gestion').on('select2:select', function (e) {
        
        var data = e.params.data.data;
        $('#txt_enero').val(data.Enero)
        $('#txt_febrero').val(data.Febrero)
        $('#txt_marzo').val(data.Marzo)
        $('#txt_abril').val(data.Abril)
        $('#txt_mayo').val(data.Mayo)
        $('#txt_junio').val(data.Junio)
        $('#txt_julio').val(data.Julio)
        $('#txt_agosto').val(data.Agosto)
        $('#txt_septiembre').val(data.Septiembre)
        $('#txt_octubre').val(data.Octubre)
        $('#txt_noviembre').val(data.Noviembre)
        $('#txt_diciembre').val(data.Diciembre)

        switch(mes)
        {
            case 0:
            $('#txt_enero').prop('readonly',false);
            $('#btn_save_enero').removeClass('d-none');
                break;
            case 1:
            $('#txt_febrero').prop('readonly',false);
            $('#btn_save_febrero').removeClass('d-none');
                break;
            case 2:
            $('#txt_marzo').prop('readonly',false);
            $('#btn_save_marzo').removeClass('d-none');
                break;
            case 3:
            $('#txt_abril').prop('readonly',false);
            $('#btn_save_abril').removeClass('d-none');
        
                break;
            case 4:
            $('#txt_mayo').prop('readonly',false);
            $('#btn_save_mayo').removeClass('d-none');
                break;
            case 5:
            $('#txt_junio').prop('readonly',false);
            $('#btn_save_junio').removeClass('d-none');
                break;
            case 6:
            $('#txt_julio').prop('readonly',false);
            $('#btn_save_julio').removeClass('d-none');
                break;
            case 7:
            $('#txt_agosto').prop('readonly',false);
            $('#btn_save_agosto').removeClass('d-none');
                break;
            case 8:
            $('#txt_septiembre').prop('readonly',false);
            $('#btn_save_septiembre').removeClass('d-none');
                break;
            case 9:
            $('#txt_octubre').prop('readonly',false);
            $('#btn_save_octubre').removeClass('d-none');
                break;
            case 10:
            $('#txt_noviembre').prop('readonly',false);
            $('#btn_save_noviembre').removeClass('d-none');
                break;
            case 11:
            $('#txt_diciembre').prop('readonly',false);
            $('#btn_save_diciembre').removeClass('d-none');
                break;
            default:
                break;

        }

      console.log(data);
     
    });

  })

    function ddl_indicador_gestion(){
         $('#ddl_indicador_gestion').select2({
          placeholder: 'Centro costo',
          ajax: {
            url: '../controlador/contratos/registro_GFNC.php?ddl_indicador_gestion=true',
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


function eliminar(ID)
{   
      $.ajax({
        type: "POST",
        url: '../controlador/inventario/orden_trabajo_constC.php?eliminar_linea=true',
        data:{ID:ID},
        dataType:'json',
        success: function(data)
        {
            if(data ==1)
            {
                Swal.fire('Registro eliminado','','info');
              cargar_lista();
            }     
        }
    });   
}

function guardar_valor(mes)
{
    valor = 0;
     switch(mes)
        {
            case 1:
            valor = $('#txt_enero').val();
                break;
            case 2:
            valor = $('#txt_febrero').val();
                break;
            case 3:
            valor = $('#txt_marzo').val();
                break;
            case 4:
            valor = $('#txt_abril').val();
                break;
            case 5:
            valor = $('#txt_mayo').val();
                break;
            case 6:
            valor = $('#txt_junio').val();
                break;
            case 7:
            valor = $('#txt_julio').val();
                break;
            case 8:
            valor = $('#txt_agosto').val();
                break;
            case 9:
            valor = $('#txt_septiembre').val();
                break;
            case 10:
            valor = $('#txt_octubre').val();
                break;
            case 11:
            valor = $('#txt_noviembre').val();
                break;
            case 12:
            valor = $('#txt_diciembre').val();
                break;
            default:
                break;
        }

        var parametros = 
        {
            'valor':valor,
            'mes':mes,
            'indicador':$('#ddl_indicador_gestion').val()
        }

        $.ajax({
        type: "POST",
        url: '../controlador/contratos/registro_GFNC.php?guardar_valor=true',
        data:{parametros:parametros},
        dataType:'json',
        success: function(data)
        {
            if(data ==1)
            {
                Swal.fire('Registro Guardado','','success');
            }     
        }
    });   

}

function nuevo_indicador_modal()
{
    $('#nuevo_indicador').modal('show');
}

function guardar_indicador()
{
    if($('#txt_codigo').val()=="" || $('#txt_identificador').val()=="")
    {
        Swal.fire("Llene todos los campos","","info")
        return false;
    }
     var parametros = 
        {
            'codigo':$('#txt_codigo').val(),
            'indicador':$('#txt_identificador').val()
        }
        $.ajax({
        type: "POST",
        url: '../controlador/contratos/registro_GFNC.php?guardar_indicador=true',
        data:{parametros:parametros},
        dataType:'json',
        success: function(data)
        {
            if(data ==1)
            {
                Swal.fire('Registro Guardado','','success').then(function()
                    {
                        $('#nuevo_indicador').modal('hide');
                        $('#txt_codigo').val("")
                        $('#txt_identificador').val("")

                    });
            }     
        }
    });   

}

