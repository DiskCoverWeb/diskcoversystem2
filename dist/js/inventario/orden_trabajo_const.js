  $(document).ready(function () {

      tbl_pedidos_all = $('#tbl_lista_solicitud').DataTable({
          // responsive: true,
          language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
          },
          ajax: {
              url:   '../controlador/inventario/orden_trabajo_constC.php?cargar_lista=true',
              type: 'POST',  // Cambia el método a POST    
              data: function(d) {
                  var parametros = {                    
                    orden:ordenNo,
                  };
                  return { parametros: parametros };
              },
              dataSrc: '',             
          },
          // searching: false,
          // responsive: true,
          // paging: false,   
          // info: false,   
          // autoWidth: true,
          scrollX:true,
          columns: [
              { data: null, // Columna autoincremental
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // meta.row es el índice de la fila
                    }
              },
              { data: 'Codigo' },
              { data: 'Comp_No' },
              { data: 'No_Contrato' },
              { data: 'Proceso' },
              { data: 'Grupo' },
              { data: 'Cuenta' },
              { data: 'Rubro' },
              { data: 'Fecha.date',  
                  render: function(data, type, item) {
                      return data ? new Date(data).toLocaleDateString() : '';
                  }
              },
              { data: 'Fecha_V.date',  
                  render: function(data, type, item) {
                      return data ? new Date(data).toLocaleDateString() : '';
                  }
              },    
              { data: 'Categoria_Contrato'},
              { data: 'Detalle_SubCta' },
              { data: 'UnidadMed' },
              { data: 'Cantidad' },
              { data: 'CantidadOrd' },
              { data: 'Diferencia' },
              { data: null,
                 render: function(data, type, item) {
                    // <button type="button" title="Imprimir Etiqueta" class="btn btn-warning btn-sm p-0 m-0" onclick="imprimir_pedido_pdf()"><i class="bx bx-printer m-0"></i></button>
                    // <button type="button" title="Editar Pedido" class="btn btn-primary btn-sm p-0 m-0" onclick="editar_pedido()"><i class="bx bx-pencil m-0"></i></button>
                 
                    return `
                     <button type="button" title="Eliminar Pedido" class="btn btn-danger btn-sm p-0 m-0" onclick="eliminar_pedido('${data.ID}')"><i class="bx bx-trash m-0"></i></button>`;                    
                  }
              },
              
          ],
          order: [
              [1, 'asc']
          ]
      });


      $("#ddl_Rubro").on('select2:select', function (e) {
         var data = e.params.data.data;
         console.log(data);
         $('#ddl_Contrato').append('<option value="' + data.Orden_Trabajo +'">' + data.Orden_Trabajo+ '</option>');         
         $('#ddl_Contrato').val(data.Orden_Trabajo);
         detalleContrato(data.Orden_Trabajo)
         centrosCostocXRubro(data.Orden_Trabajo)
         ddl_sub_rubro();
         // console.log(data);

       });

  })



function detalleContrato(ordenNo)
{
     var parametros = 
     {
        'contrato':ordenNo,
     }
      $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/inventario/orden_trabajo_constC.php?detalleContrato=true',
        type:  'post',
        dataType: 'json',
          success:  function (response) { 
            console.log(response)
              var mate = 'NO';
              var mas_per = 'NO';
              $('#lbl_proyecto').text(response[0].proyecto);
              $('#lbl_contratista').text(response[0].Cliente)
              if(response[0].Cargo_Mat==1){mate = 'SI';}
              if(response[0].mas_per==1){mate = 'SI';}
              $('#lbl_material').text(mate)
              $('#lbl_mas_personas').text(mas_per);
              $('#lbl_nombre_contrato').text(response[0].Nombre_Contrato)
              $('#lbl_categoria').text(response[0].Proceso)
              // $('#lbl_tipo_costo').text(response[0].tipo_costo)
              // $('#lbl_cuenta_contable').text(response[0].cuenta_contable)
              $('#lbl_fecha').text(formatoDate(response[0].Fecha.date))
              $('#lbl_fecha_v').text(formatoDate(response[0].Fecha_V.date))
              $('#txt_cuenta_proyecto').val(response[0].ProyectoID);

              $('#pnl_detalle_proyecto').removeClass("d-none");

              console.log(response);
        }
      });

}

function centrosCostocXRubro(ordenNo)
{
     var parametros = 
     {
        'contrato':ordenNo,
        'rubro':$('#ddl_Rubro').val()
     }
      $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/inventario/orden_trabajo_constC.php?centrosCostocXRubro=true',
        type:  'post',
        dataType: 'json',
          success:  function (response) { 
            console.log(response)
            var pnl_tab = '';
            var pnl_cont = '';
            var estado_tab = '';
            var estado_cont = '';
            response.forEach(function(item,i){
              console.log(item)
              if(i==0){estado_tab = 'active'; estado_cont = 'show'; $('#txt_centro_costos').val(item.Centro_Costos) }
              pnl_tab+=`<button class="nav-link `+estado_tab+`" id="tab_`+item.Centro_Costos+`" data-bs-toggle="pill" data-bs-target="#content_tab_`+item.Centro_Costos+`" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true" onclick="$('#txt_centro_costos').val('`+item.Centro_Costos+`');cargar_lista_subrubros()">`+item.Detalle+`</button>`
              pnl_cont+=`<div class="tab-pane fade `+estado_cont+` `+estado_tab+`" id="content_tab_`+item.Centro_Costos+`" role="tabpanel" aria-labelledby="v-pills-home-tab">
                              <table class="table table-hover">
                                <thead>
                                  <th></th>
                                  <th>Detalle</th>
                                  <th>Unidad</th>
                                  <th>Cantidad</th>
                                  <th>Costo unitario</th>
                                  <th>costo total</th>
                                </thead>
                                <tbody id="tbl_`+item.Centro_Costos+`"></tbody>
                              </table>
                          </div>`
              estado_tab = '';
              estado_cont = '';
            })

            $('#tab_button').html(pnl_tab)
            $('#tab_content').html(pnl_cont)

            cargar_lista_subrubros();
            $('#pnl_centro_costos_proyecto').removeClass("d-none");
             
        }
      });

}


function contratistas()
{
  $('#ddl_Contrato').val("");
	$('#ddl_contratista').select2({
    placeholder: 'Seleccione contratista',
    width:'resolve',
    allowClear: true,
    ajax: {
	    url:   '../controlador/inventario/orden_trabajo_constC.php?contratistas=true',
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

function cargar_contratos()
{

  $('#ddl_Contrato').val("");
  $('#ddl_Rubro').empty();
  // $('#ddl_Rubro').val("");
  var contratista = $('#ddl_contratista').val();
  $('#ddl_Rubro').select2({
    placeholder: 'Seleccione contratista',
    allowClear: true,
    width:'resolve',
    ajax: {
      url:   '../controlador/inventario/orden_trabajo_constC.php?contratos=true&ContratosContratista='+contratista,
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

function ddl_cuenta_contable(){

     $('#ddl_cuenta_contable').select2({
      placeholder: 'Centro costo',
      ajax: {
        url: '../controlador/inventario/orden_trabajo_constC.php?ddl_cuenta_contable=true',
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

function ddl_Proceso(){

     $('#ddl_Proceso').select2({
      placeholder: 'Centro costo',
      ajax: {
        url: '../controlador/inventario/orden_trabajo_constC.php?ddl_Proceso=true',
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


function ddl_Grupo(){

     $('#ddl_Grupo').select2({
      placeholder: 'Centro costo',
      ajax: {
        url: '../controlador/inventario/orden_trabajo_constC.php?ddl_Grupo=true',
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


function ddl_Rubro(){

     $('#ddl_Rubro').select2({
      placeholder: 'Centro costo',
      ajax: {
        url: '../controlador/inventario/orden_trabajo_constC.php?ddl_Rubro=true',
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

function agregar_tabla()
{
  var orden = ordenNo; 
  var data = $('#form_datos').serialize();
  $.ajax({
    url: '../controlador/inventario/orden_trabajo_constC.php?agregar_tabla=true',
    type:'post',
    dataType:'json',
    data:{parametros:data,orden:orden},     
    success: function(response){
      if(response.resp==1)
      {
        Swal.fire("Agregado","","success").then(function(){
          if(orden==-1)
          {
              var url = new URL(window.location.href);
              var params = new URLSearchParams(url.search);
              params.set('ordenNo',response.orden);
              window.location.href = url.pathname + '?' + params.toString();

           }else
           {
            cargar_lista();
           }

        })
      }else
      {
        Swal.fire("Algo salio mal","","error")
      }
    // console.log(response);
    }
  });
}

function cargar_lista()
{
  tbl_pedidos_all.ajax.reload(null, false);
}

function eliminar_pedido(id)
{

     Swal.fire({
     title: 'Esta seguro?',
     text: "Esta usted seguro de que quiere eliminar este registro!",
     icon: 'warning',
     showCancelButton: true,
     confirmButtonColor: '#3085d6',
     cancelButtonColor: '#d33',
     confirmButtonText: 'Si!'
   }).then((result) => {
     if (result.value==true) {
      eliminar(id);
     }
   })

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

function ddl_sub_rubro()
{
  var rubro = $('#ddl_Rubro').val();  
  $('#ddl_sub_rubro').select2({
    placeholder: 'Seleccione contratista',
    width:'100%',
    allowClear: true,
    ajax: {
      url:   '../controlador/inventario/orden_trabajo_constC.php?subrubro=true&rubro='+rubro,
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

function calcular_costo_total()
{
  var cantidad = $('#txt_cantidad').val();
  var pvp = $('#txt_costo_pvp').val();
  var total = cantidad * pvp;
  if(cantidad=='' || pvp=='')
  {
    $('#txt_costo_total').val(0);
  }else
  {
    $('#txt_costo_total').val(total.toFixed(2));
  }
}

function add_subRubro()
{
    var contratista = $('#ddl_contratista').val();
    var rubro = $('#ddl_Rubro').val();
    var Contrato=$('#ddl_Contrato').val();
    var subRubro = $('#ddl_sub_rubro').val();
    var unidad = $('#txt_unidad').val();
    var cantidad = $('#txt_cantidad').val();
    var pvp = $('#txt_costo_pvp').val();
    var total = $('#txt_costo_total').val();
    var centroCostos = $('#txt_centro_costos').val();

    var parametros = 
    {
      'contratista':contratista,
      'rubro':rubro,
      'centroCostos':centroCostos,
      'Contrato':Contrato,
      'subRubro':subRubro,
      'unidad':unidad,
      'cantidad':cantidad,
      'pvp':pvp,
      'total':total,
    }

     $.ajax({
        type: "POST",
        url: '../controlador/inventario/orden_trabajo_constC.php?add_subRubro=true',
        data:{parametros:parametros},
        dataType:'json',
        success: function(data)
        {
            if(data ==1)
            {
                Swal.fire('Sub rubro ingresado','','success').then(function(){
                  cargar_lista_subrubros();
                });
            }     
        }
    });   
}

function cargar_lista_subrubros()
{
  var contratista = $('#ddl_contratista').val();
  var rubro = $('#ddl_Rubro').val();
  var Contrato=$('#ddl_Contrato').val();
  var centroCostos=$('#txt_centro_costos').val();
    
  var parametros = 
  {
    'contratista': contratista,
    'rubro': rubro,
    'Contrato': Contrato,
    'centroCostos':centroCostos,

  }
   $.ajax({
        type: "POST",
        url: '../controlador/inventario/orden_trabajo_constC.php?cargar_lista_subrubros=true',
        data:{parametros:parametros},
        dataType:'json',
        success: function(data)
        {
       
            table = '';
              data.forEach(function(item,i){
                table+=`<tr> 
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminar_subrubro('`+item.ID+`')"><i class="bx bx-trash me-0"></i></button></td>
                            <td>`+item.Detalle+`</td> 
                            <td>`+item.Unidad+`</td>
                            <td>`+item.Cantidad+`</td>
                            <td>`+item.PVP+`</td>
                            <td>`+item.Total+`</td>
                        </tr>`
              })
              $('#tbl_'+centroCostos).html(table);
        }
    });   

}

function eliminar_subrubro(id)
{
    Swal.fire({
          title: 'Esta seguro de eliminar',
          text: "Se eliminara este registro",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Continuar'
        }).then((result) => {
          if (result.value) {
            delete_subrubro(id)
          }
        })

}

function delete_subrubro(id)
{
  var parametros = 
  {
    'id': id,
  }
   $.ajax({
        type: "POST",
        url: '../controlador/inventario/orden_trabajo_constC.php?delete_subrubro=true',
        data:{parametros:parametros},
        dataType:'json',
        success: function(data)
        {
          if(data==1)
          {
            cargar_lista_subrubros();
          }       
          
        }
    });   

}



