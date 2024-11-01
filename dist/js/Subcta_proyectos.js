$(document).ready(function () {
    DGCostos();
    DCProyecto();
    DCSubModulos();

});
  
function DGCostos(todas=false)
{
    var parametros = 
  {
    'query':false,
    'TodasCtas':todas,
    'CodSubCta': $('#ddl_proyecto').val(),
    'SubCta':$('#ddl_cuenta_pro').val(),
  }
    $.ajax({
      type: "POST",
      url: '../controlador/contabilidad/Subcta_proyectosC.php?DGCostos=true',
      data: {parametros: parametros},
      dataType:'json',
      success: function(data)
      {			
          console.log(data);
          $('#tbl_tabla').html(data);
      }
  });
}

function DCProyecto()
{
    
    $.ajax({
      type: "POST",
      url: '../controlador/contabilidad/Subcta_proyectosC.php?DCProyecto=true',
      //data: {parametros: parametros},
      dataType:'json',
      success: function(data)
      {			
          llenarComboList(data,'ddl_proyecto');
          // console.log(data);
          // $('#tbl_tabla').html(data);
      }
  });
}


function DCCtasProyecto()
{
  var parametros = {
    'codigo':$('#ddl_proyecto').val(),
  }    
  $.ajax({
  type: "POST",
  url: '../controlador/contabilidad/Subcta_proyectosC.php?DCCtasProyecto=true',
  data: {parametros: parametros},
  dataType:'json',
  success: function(data)
  {     
    console.log(data);
    llenarComboList(data,'ddl_cuenta_pro');
    // console.log(data);
    // $('#tbl_tabla').html(data);
  }
});
}

function DCSubModulos()
{
    
    $.ajax({
      type: "POST",
      url: '../controlador/contabilidad/Subcta_proyectosC.php?DCSubModulos=true',
      //data: {parametros: parametros},
      dataType:'json',
      success: function(data)
      {			
          llenarComboList(data,'ddl_sub_pro');
          // console.log(data);
          // $('#tbl_tabla').html(data);
      }
  });
}

function eliminar_item(id)
{
  var parametros = 
  {
    'id':id,
  }
  $.ajax({
  type: "POST",
  url: '../controlador/contabilidad/Subcta_proyectosC.php?eliminar=true',
  data: {parametros: parametros},
  dataType:'json',
  success: function(data)
  { 
    if(data==1)
    {   
      DGCostos();
    }else
    {
      Swal.fire('Algo inesperado a pasado','','error')
    }
  }
});
}

function todas()
{
  var proyecto = $('#ddl_proyecto').val();
  if(proyecto!='')
  {
    $('#ddl_cuenta_pro').val('');
    DGCostos(true);
  }else
  {
    Swal.fire('Seleccione un proyecto','','info');
  }
}

function imprimir_excel()
{
  var proyecto = $('#ddl_proyecto').val();
  if(proyecto=='')
  {
    Swal.fire('Seleccione un proyecto','','info');
  }else{

  var para = $('#form_filtros').serialize();   
  var url = '../controlador/contabilidad/Subcta_proyectosC.php?imprimir_excel=true&'+para;
    window.open(url, '_blank');
  }
}