  const hoy = new Date(); 
  const mes = hoy.getMonth(); 
  
  $(document).ready(function () {
    ddl_grupo_identificador();
    ddl_indicador_gestion_grupo();

        //enviar datos del cliente
    $('#ddl_indicador_gestion').on('select2:select', function (e) {
        
        var data = e.params.data.data;
        $('#txt_valor_0').val(data.Enero)
        $('#txt_valor_1').val(data.Febrero)
        $('#txt_valor_2').val(data.Marzo)
        $('#txt_valor_3').val(data.Abril)
        $('#txt_valor_4').val(data.Mayo)
        $('#txt_valor_5').val(data.Junio)
        $('#txt_valor_6').val(data.Julio)
        $('#txt_valor_7').val(data.Agosto)
        $('#txt_valor_8').val(data.Septiembre)
        $('#txt_valor_9').val(data.Octubre)
        $('#txt_valor_10').val(data.Noviembre)
        $('#txt_valor_11').val(data.Diciembre)



    

        // guardar valor 
        for (var i = 0; i < 12; i++) {
            if(mes==i)
            {            
                $('#btn_save_'+i).removeClass('d-none');
                $('#txt_valor_'+i).prop('readonly',false)
            }else
            {
                $('#btn_save_'+i).addClass('d-none');   
                $('#txt_valor_'+i).prop('readonly',false)             
            }
        }

        // editar valores
        for (var i = 0; i < 12; i++) {
            if(mes!=i)
            {            
                $('#btn_edit_'+i).removeClass('d-none');
            }else
            {
                $('#btn_edit_'+i).addClass('d-none');            
            }
        }

    });

  })

    function ddl_indicador_gestion_grupo(){
         $('#ddl_indicador_gestion_grupo').select2({
          placeholder: 'Centro costo',
          ajax: {
            url: '../controlador/contratos/registro_GFNC.php?ddl_indicador_gestion_grupo=true',
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

    function ddl_grupo_identificador(){
         $('#ddl_grupo_identificador').select2({
          placeholder: 'Seleccione grupo',
          dropdownParent: $('#nuevo_indicador'),
          width: '100%',        
          ajax: {
            url: '../controlador/contratos/registro_GFNC.php?ddl_indicador_gestion_grupo=true',
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

    function ddl_indicador_gestion(){
         $('#ddl_indicador_gestion').select2({
          placeholder: 'Centro costo',
          ajax: {
            url: '../controlador/contratos/registro_GFNC.php?ddl_indicador_gestion=true&grupo='+$('#ddl_indicador_gestion_grupo').val(),
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
    var valor = $('#txt_valor_'+mes).val(); 
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

    if($('input[name="rbl_tipo"]:checked').val()=='D' && $('#ddl_grupo_identificador').val()=="")
    {
            Swal.fire("Llene todos los campos","","info")
            return false;
    }

    var parametros = 
        {
            'tipo':$('input[name="rbl_tipo"]:checked').val(),
            'grupo':$('#ddl_grupo_identificador').val(),
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
            } else if(data==-2)
            {
                Swal.fire('El codigo ya esta registrado','','error');
            }     
        }
    });   

}


function cambiar_tipo()
{
    var tipo = $('input[name="rbl_tipo"]:checked').val();
    console.log(tipo);
    if(tipo=='D')
    {
        $('#pnl_grupo').removeClass('d-none');
    }else
    {        
        $('#pnl_grupo').addClass('d-none');
    }
}

let botonEditarSeleccionado;
function editar_valor(mes)
{
    $('#clave_supervisor').modal('show');
    botonEditarSeleccionado = mes;
}

function resp_clave_ingreso(response)
{
    console.log(response)
    if (response.respuesta == 1) {
        guardar_valor(botonEditarSeleccionado);
    }
    // console.log(botonEditarSeleccionado)
}