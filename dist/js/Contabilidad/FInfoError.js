$(document).ready(function() {
    FInfoErrorShowView()
  });

  function FInfoErrorShowView(){
    $.ajax({
      type: "POST",                 
      url: '../controlador/modalesC.php?FInfoErrorShow=true',
      dataType:'json', 
      success: function(datos)             
      {
        var tbody = $("#DGInfoError tbody");
        if(datos.length<=0){
          window.parent.$("#myModalInfoError").modal("hide");
        }
        for (var i = 0; i < datos.length; i++) {
            var fila = $("<tr>");
            fila.append($("<td>").text(datos[i]['Texto']));
            tbody.append(fila);
        }
      },
      error: function (e) {
        alert("Disculpe, ocurrio un error inesperado en InfoErrorShow")
      }
    });
  }

  function fEliminarTablaTemporal() {
    $.ajax({
      type: "POST",                 
      url: '../controlador/modalesC.php?FInfoErrorEliminarTablaTemporal=true',
      dataType:'json', 
      beforeSend: function () {
        $("#myModal_espera").modal('show');
      },
      success: function(response)             
      {
        window.parent.$("#myModalInfoError").modal("hide");
        $("#DGInfoError tbody").empty()
        $("#myModal_espera").modal('hide');
      },
      error: function (e) {
        alert("Disculpe, ocurrio un error inesperado en EliminarTablaTemporal")
      }
    });
  }

  function GenerarExcel() {
    var url = '../controlador/modalesC.php?ExcelFInfoError=true';
      window.open(url, '_blank');
  }
