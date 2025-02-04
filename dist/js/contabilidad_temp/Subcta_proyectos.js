$(document).ready(function () {
    $('[data-bs-toggle="tooltip"]').tooltip();
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
  if($.fn.dataTable.isDataTable('#tbl_tabla')){ 
    $('#tbl_tabla').DataTable().clear().destroy();
  }
    $.ajax({
      type: "POST",
      url: '../controlador/contabilidad/Subcta_proyectosC.php?DGCostos=true',
      data: {parametros: parametros},
      dataType:'json',
      success: function(response)
      {			
          tbl_table = $('#tbl_tabla').DataTable({
            language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            }, 
            data: ProcesarDatos(response.data),
            columns: [
              { data: null, 
                render: function(data, type, row){
                  return data[0] || '';
                }, 
                orderable: false,
                className: 'text-center'
               }, 
              { data: 'Cta', className: "text-center" }, 
              { data: 'Cuenta', className: "text-center" }, 
              { data: 'Detalle', className: "text-center" }, 
              { data: 'Codigo', className: "text-center" }, 
              { data: 'ID', className: "text-center" }
            ],
            createdRow: function(row, data){
              alignEnd(row, data);
            }
          });
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
function insertar()
{
  var pro = $('#ddl_proyecto').val();
  var cta = $('#ddl_cuenta_pro').val();
  var sub = $('#ddl_sub_pro').val();


  var cta_n = $('#ddl_cuenta_pro option:selected').text();
  var sub_n = $('#ddl_sub_pro option:selected').text();

  if(pro!='' && cta!='' && sub!='')
  {
      Swal.fire({
        title: 'Esta seguro de Inserta en la cuenta! '+cta_n+" El centro de costo: "+sub_n,
        text: '',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!'
        }).then((result) => {
          if (result.value==true) {
          agregar();

          }else
          {
          DGCostos();
          }
        })
  }

}

function eliminar(id)
{
  
      Swal.fire({
        title: 'Esta seguro de eliminar!',
        text: 'Este registro sera eliminado',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!'
        }).then((result) => {
          if (result.value==true) {
          eliminar_item(id);

          }
        })
}


function agregar()
{
    var pro = $('#ddl_proyecto').val();
    var cta = $('#ddl_cuenta_pro').val();
    var sub = $('#ddl_sub_pro').val();
    var cta_n = $('#ddl_cuenta_pro option:selected').text();
    var sub_n = $('#ddl_sub_pro option:selected').text();
    var parametros = 
    {
    'cta':cta,
    'codigo':sub,
    }
  $.ajax({
    type: "POST",
    url: '../controlador/contabilidad/Subcta_proyectosC.php?agregar=true',
    data: {parametros: parametros},
    dataType:'json',
    success: function(data)
    {
      if(data==null)
      {
        Swal.fire('Item agregado','','success');
        DGCostos();
      }else if(data==2)
      {
          DGCostos();
        Swal.fire('Item existente','','info');
      }
      
    }
  });

}