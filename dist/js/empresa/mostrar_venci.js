$(document).ready(function () {
  	

});

function mostrarEmpresa()
{
  //$(".loader1").show();
  var x = document.getElementById('mostraE');
  var x1 = document.getElementById('mostraEm');
  
  if (x.style.display === 'none') 
  {
    x.style.display = 'block';
    $("#mostraEm").show();
    $("#mostraEm1").show();
  } 
  else 
  {
    x.style.display = 'none';
    //$("#mostraEm").hide();
    $("#mostraEm1").hide();
  }
}

function consultar_datos()
  {
    let desde= document.getElementById('desde');
    let hasta= document.getElementById('hasta');
    ///alert(desde.value+' '+hasta.value);
    var parametros =
    {
      'desde':desde.value,
      'hasta':hasta.value,
      'repor':'',     
    }
    $titulo = 'Mayor de '+$('#DCCtas option:selected').html(),
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/contabilidad/contabilidad_controller.php?consultar=true',
      type:  'post',
      dataType: 'json',
      beforeSend: function () {   
        //    var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'      
         // $('#tabla_').html(spiner);
         $('#myModal_espera').modal('show');
      },
        success:  function (response) {

          /*var tr ='';
          response.forEach(function(item,i)
          {
            tr+='<tr><td>'+item.tipo+'</td><td>'+item.Item+'</td><td>'+item.Empresa+'</td><td>'+item.Fecha+'</td><td>'+item.enero+'</td></tr>';
          })
              $('#tbl_vencimiento').html(tr);*/
            $('#tbl_vencimiento').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json',
                },
                data: ProcesarDatos(response),
                destroy: true,
                columns: [
                    {data: 'tipo'},
                    {data: 'Item'},
                    {data: 'Empresa'},
                    {data: 'Fecha'},
                    {data: 'enero'}
                ],
                createdRow: function (row, data){
                    alignEnd(row, data);
                }
            })
            setTimeout(()=>{
              $('#myModal_espera').modal('hide');
            }, 2000);
        
      
      }
    });
  }

function reporte()
{
  let desde= $('#desde').val();
  let hasta= $('#hasta').val();
  var tit = 'ADMINISTRAR EMPRESA';
  var url = ' ../controlador/contabilidad/contabilidad_controller.php?consultar_reporte=true&desde='+desde+'&hasta='+hasta+'&repor=2';
  window.open(url,'_blank');
}