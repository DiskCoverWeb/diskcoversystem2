$(document).ready(function () {
     
})

function procesar()
{
    var parametros = 
    {
        'CheqSinConc':$('#CheqSinConc').prop('checked'),
        'CheqDetalle':$('#CheqDetalle').prop('checked'),
        'CheqRenumerar':$('#CheqRenumerar').prop('checked'),
        'MBoxCtaI':$('#MBoxCtaI').val(),
        'LabelTotInv':$('#LabelTotInv').val()
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/contabilidad/cierre_ejercicioC.php?procesar=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
            if(response==2)
            {
                Swal.fire('Cuenta no asignada en el Catalogo de Cuentas','','error');
            }
      }
    });
}
function grabar()
{
    $.ajax({
  // data:  {parametros:parametros},
  url:   '../controlador/contabilidad/cierre_ejercicioC.php?grabar=true',
  type:  'post',
  dataType: 'json',
    success:  function (response) {

      $('#LstMeses').html(response);               
    
  }
});

}
function actualizar()
{
    $.ajax({
  // data:  {parametros:parametros},
  url:   '../controlador/contabilidad/cierre_ejercicioC.php?actualizar=true',
  type:  'post',
  dataType: 'json',
    success:  function (response) {

      $('#LstMeses').html(response);               
    
  }
});

}
function imprimir()
{
    $.ajax({
  // data:  {parametros:parametros},
  url:   '../controlador/contabilidad/cierre_ejercicioC.php?imprimir=true',
  type:  'post',
  dataType: 'json',
    success:  function (response) {

      $('#LstMeses').html(response);               
    
  }
});

}