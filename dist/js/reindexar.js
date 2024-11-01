
$(document).ready(function () {
    $('#myModal').modal('show');

     $('#imprimir_excel').click(function(){      		

      var url = '../controlador/contabilidad/reindexarC.php?imprimir_excel=true';
          window.open(url, '_blank');
   });

    $('#imprimir_pdf').click(function(){      		

      var url = '../controlador/contabilidad/reindexarC.php?imprimir_pdf=true';
          window.open(url, '_blank');
   });



})

function reindexar()
{		
    $('#myModal_espera').modal('show');
    $.ajax({
        url: '../controlador/contabilidad/reindexarC.php?reindexarT=true',
        type: 'POST',
        dataType: 'json',
        // data: { param: param },
        success: function (data) {
            $('#myModal').modal('hide');
            $('#myModal_espera').modal('hide');
            if(data.resp==1)
            {
                $('#lista_errores').html(data.tr);
                Swal.fire("Reindexado","","success");
            }
        },
        error: function (error) {
            console.log(error);
            $('#myModal_espera').modal('hide');
        }
    });
}