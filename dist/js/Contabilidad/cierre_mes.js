var chekTrue = [];
var chekFalse = [];

function resp_clave_ingreso(response)
{
if(response.respuesta==1)
{
    $('#clave_supervisor').modal('hide');
    llenar_meses();
    abrir_modal();
}
}

function abrir_modal()
{
$('#myModal_espera').modal('hide');
$('#modal_cierre').modal('show');
}

function llenar_meses()
{
$.ajax({
    // data:  {parametros:parametros},
    url:   '../controlador/contabilidad/cierre_mesC.php?lista=true',
    type:  'post',
    dataType: 'json',
    success:  function (response) {

        $('#LstMeses').html(response);               
    
    }
});
}

function guardar()
{
parametros = 
{
    'chekTrue':chekTrue,
    'chekFalse':chekFalse,
}
    $.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/contabilidad/cierre_mesC.php?grabar=true',
    type:  'post',
    dataType: 'json',
    success:  function (response) {
        if(response==1)
        {
        Swal.fire('Guardado','','success');
        }

    
    }
});    
}


function validar(chek)
{
var da = $('#'+chek).val();
if($('#'+chek).prop('checked'))
{
    chekTrue.push(da);
    var myIndex = chekFalse.indexOf(da);
    if (myIndex !== -1) {
        chekFalse.splice(myIndex, 1);
        }
}else
{

    chekFalse.push(da);
    var myIndex = chekTrue.indexOf(da);
    if (myIndex !== -1) {
        chekTrue.splice(myIndex, 1);
        }

}
}

function cambiar_year()
{
var currentTime = new Date();
var year = currentTime.getFullYear()

    Swal.fire({
    title: 'Ingrese año a procesar',
    text: "",
    input:'text',
    // type: '',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'OK!',
    inputValue:year,
    }).then((result) => {
    if (result.value || result.value=='') {
        cambiar_year_cierre(result.value);
    }
    });
}

function cambiar_year_cierre(anio)
{
var parametros = 
{
    'year':anio,
}
$.ajax({
    data:  {parametros:parametros},
    url:   '../controlador/contabilidad/cierre_mesC.php?cierre_mes=true',
    type:  'post',
    dataType: 'json',
    success:  function (response) {
        $('#LstMeses').html(response);               
    
    }
});
}