$(document).ready(function () {
     programas();
     autocoplet_pro2();

})
function autocoplet_pro2() {
    tipo = $('#tipoCompra').val();
    var url_ = '';
    if(tipo=='84.02')
    {
        console.log('sss');
        url_ = '../controlador/inventario/asignacion_osC.php?autocom_pro=true';
        console.log(url_);
    }else
    {
        url_ = '../controlador/inventario/alimentos_recibidosC.php?autocom_pro=true';
        console.log(url_);
        
    }

    $('#grupProd').select2({
        placeholder: 'Seleccione una producto',
        width:'100%',
        ajax: {
            url: url_,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                // console.log(data);
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}

function buscar_producto(codigo)
{
    var parametros = {
        'codigo':codigo,
    }
    $.ajax({
        type: "POST",
        url:   '../controlador/inventario/asignacion_osC.php?Codigo_Inv_stock=true',
        data:{parametros:parametros},
        dataType:'json',
        success: function(data)
        {
            if(data.respueta)
            {
                $('#stock').val(data.datos.Stock);
            }
            console.log(data);
        }
    });
}
function programas()
{
    $('#ddl_programas').select2({
        placeholder: 'Seleccione programa',
        // dropdownAutoWidth: true,
      //  selectionCssClass: 'form-control form-control-sm h-100',  // Para el contenedor de Select2
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?programas=true',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });
}

function grupos()
{
    programa = $('#ddl_programas').val();
    $('#ddl_grupos').select2({
        placeholder: 'Seleccione grupo',
        ajax: {
            url:   '../controlador/inventario/registro_beneficiarioC.php?ddl_grupos=true&programa='+programa,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
    });
}


function tipoCompra()
{
    var datos = 
    {
        'grupo':$('#ddl_grupos').val(),
        'fecha':$('#fechAten').val(),
    }
    $.ajax({
        url: '../controlador/inventario/asignacion_familiasC.php?tipo_asignacion=true',
        type: 'POST',
        dataType: 'json',
        data: { parametros: datos },
    success: function (data) {
        console.log(data);

        var op = '';
        var option = '';
        data.forEach(function(item,i){
        // console.log(item);
            option+= '<div class="col-md-6 col-sm-6">'+
                        '<button type="button" class="btn btn-light btn-sm"><img src="../../img/png/'+item.Picture+'.png" onclick="cambiar_tipo_asig(\''+item.ID+'\')"  style="width: 60px;height: 60px;"></button><br>'+
                        '<b>'+item.Proceso+'</b>'+
                    '</div>';

            op+='<option value="'+item.ID+'">'+item.Proceso+'</option>';
        })

        $('#tipoCompra').html(op); 
        $('#pnl_tipo_empaque').html(option);   

        // llenarDatos(benefi);


            // llenarComboList(data,'tipoCompra');
            // console.log(data);
        },
        error: function (error) {
            console.log(error);
        }
    });
}



function guardar()
{
    // ben = $('#beneficiario').val();
    // distribuir = $('#CantGlobDist').val();
    // if(ben=='' || ben==null){Swal.fire("","Seleccione un Beneficiario","info");return false;}
    // if(distribuir==0 || distribuir==''){ Swal.fire("","No se a agregado nigun grupo de producto","info");return false;}
    // var parametros = {
    //     'beneficiario':ben,
    //     'fecha':$('#fechAten').val(),
    // }
    //  $.ajax({
    //     url: '../controlador/inventario/asignacion_osC.php?GuardarAsignacion=true',
    //     type: 'post',
    //     dataType: 'json',
    //     data: { parametros: parametros },
    //     success: function (datos) {
    //         if(datos==1)
    //         {
    //             Swal.fire("Asignacion Guardada","","success").then(function(){
    //                 location.reload();
    //             });
    //         }

    //     }
    // });
}

function calcular()
{
    var numeroIntegrantes = $('#totalPersAten').val();
    var cantidadCP = $('#cant_cp').val();
    var totalParaIntegrantes = parseFloat(numeroIntegrantes)*parseFloat(cantidadCP);

    $('#cant').val(totalParaIntegrantes.toFixed(3));
}

function add_beneficiario(){
    $('#modal_addBeneficiario').modal('show');
}

function agregar() {
    if($('#ddl_grupos').val()=='' || $('#ddl_grupos').val()==null)
    {
        Swal.fire("","Seleccione Grupo","info").then(function(){
            return false;
        })
    }

    var stock = $('#stock').val();
    var cant =  $('#cant').val();
    console.log(cant)
    console.log(stock);
    if(cant=='' || cant==null || cant <= 0)
    { 
        Swal.fire("Cantidad no valida","","info");
        return false;
    }
    if(parseFloat(cant)> parseFloat(stock))
    { 
        Swal.fire("Cantidad supera al stock","","info")
        return false;
    }
    var datos = {
        'Codigo': $('#grupProd').val(),
        'Producto': $('#grupProd option:selected').text(),
        'Cantidad': $('#cant').val(),
        'CantidadCp': $('#cant_cp').val(),
        'Comentario': $('#comeAsig').val(),
        'programa':$('#ddl_programas').val(), 
        'programaNombre':$('#ddl_programas option:selected').text(),   
        'grupo':$('#ddl_grupos').val(), 
        'grupoNombre':$('#ddl_grupos option:selected').text(),   
        'FechaAte':$('#fechAten').val(),   
        'asignacion':$('#tipoCompra').val(),
    };       

    if($('#tipoCompra').val()=='' || $('#tipoCompra').val()==null)
    {
        Swal.fire("Seleccione Tipo de asignacion","","info")
            return false;
    }


    $.ajax({
        url: '../controlador/inventario/asignacion_familiasC.php?addAsignacionFamilias=true',
        type: 'POST',
        dataType: 'json',
        data: { param: datos },
        success: function (data) {
            if(data==1)
            {
                listaAsignacion();
                limpiar();
            }
        },
        error: function (error) {
            console.log(error);
        }
    });
}
function limpiar() {
    $("#grupProd").empty();
    $("#ddl_producto").empty();
    $("#txt_referencia").val("");
    $('#stock').val("");
    $('#cant').val("");
    $("#comeAsig").val("");
}

function IntegrantesGrupo()
{

    if ($.fn.DataTable.isDataTable('#tblClientes')) {
      $('#tblClientes').DataTable().destroy();
  }

    tipoCompra();
    var grupo = $('#ddl_grupos').val();
    var fech = $('#fechAten').val();
    grupo = grupo.replace(/\./g,'_');
    fech = fech.replace(/-/g,'')
    codigo = grupo+'_'+fech;
    $('#txt_codigo_pedido').val(codigo);
   
    var parametros =
        {
            'programa': $('#ddl_programas').val(),
            'grupo': $('#ddl_grupos').val(),
        }
        $.ajax({
            type: "POST",
            url: '../controlador/facturacion/facturas_distribucion_famC.php?IntegrantesGrupo=true',
            data: { parametros: parametros },
            dataType: 'json',
            success: function (data) {
                tr = '';
                data.forEach(function(item,i){
                    tr+=`<tr>
                        <td>`+(i+1)+`</td>
                        <td>`+item.Cliente+`</td>
                        <td>`+item.CI_RUC+`</td>
                    </tr>`;
                })
                $('#tbl_integrantes').html(tr);


                $('#tbl_body_motivo').html(tr);
       
                  $('#tblClientes').DataTable({
                      scrollX: true,
                      searching: false,
                      responsive: false,
                      paging: false,   
                      info: false,   
                      autoWidth: false,  
                      order: [[1, 'asc']], // Ordenar por la segunda columna
                      /*autoWidth: false,
                      responsive: true,*/
                      language: {
                      url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                    },
                    columnDefs: [
                    ],
                    dom: 'Bfrtip', // Define la posición de los botones
                     buttons: [
                       {
                            extend: 'excelHtml5',
                            title: 'BANCO DE ALIMENTOS QUITO - GESTIÓN SOCIAL',   // Título dentro del archivo
                            text: 'Excel',         // Texto del botón
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'BANCO DE ALIMENTOS QUITO - GESTIÓN SOCIAL',
                        },

                    ],
                  });



                $('#totalPersAten').val(data.length);
            }
        });
}

function llenarCamposPoblacion()
{
    IntegrantesGrupo()
    $('#modalBtnGrupo').modal('show');
}
function listaAsignacion() {
     if ($.fn.DataTable.isDataTable('#tbl_asignacion_os')) {
      $('#tbl_asignacion_os').DataTable().destroy();
    }
    tbl_asignacion_os = $('#tbl_asignacion_os').DataTable({
        // responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        /*columnDefs: [
            { targets: [8,9,10,11,12,13], className: 'text-end' } // Alinea las columnas 0, 2 y 4 a la derecha
        ],*/
        ajax: {
            url: '../controlador/inventario/asignacion_familiasC.php?listaAsignacion=true',
            type: 'POST',  // Cambia el método a POST    
            data: function(d) {
                /*var parametros = {
                  'codigoCliente': '',
                    'tamanioTblBody': altoContTbl <= 25 ? 0 : altoContTbl - 12,
                };*/
                var param = {
                    'grupo':$('#ddl_grupos').val(),
                    'fecha':$('#fechAten').val(),
                    'tipo':$('#tipoCompra').val(),
                }
                return { param: param };
            },              
              dataSrc: function(json) {
              
                let cantidad = parseFloat(json.cantidad);

                $('#CantGlobDist').val(parseFloat(cantidad).toFixed(2));

                // console.log(json);

                // Devolver solo la parte de la tabla para DataTables
                return json.tabla;
            }        
        },
          //scrollX: true,  // Habilitar desplazamiento horizontal
            paging:false,
            searching:false,
            info:false,
            scrollY: 330,
            scrollCollapse: true,
        columns: [
            { data: null, // Columna autoincremental
                render: function (data, type, row, meta) {
                    return meta.row + 1; // meta.row es el índice de la fila
                }
            },

            // { data: null,
            //     render: function(data, type, item) {
            //         return `<button type="button" class="btn btn-sm btn-danger" onclick="Eliminar_linea('${item.A_No}','${item.CODIGO}')" title="Eliminar linea"><i class="bx bx-trash"></i></button>`;
            //     } 
            // },
            { data: 'Producto'},
            { data: 'Cantidad',  
                render: function(data, type, item) {
                    return data ? parseFloat(data) : '';
                }
            },
            { data: 'Cant_Hab',  
                render: function(data, type, item) {
                    return data ? parseFloat(data) : '';
                }
            },
            { data: 'Procedencia' },
            { data: null,
                render: function(data, type, item) {
                    return `<button type="button" class="btn btn-sm btn-danger" onclick="eliminar_linea('${item.ID}')" title="Eliminar linea"><i class="bx bx-trash"></i></button>`;
                } 
            }
        ]
    });
}

function eliminar_linea(id)
{
        Swal.fire({
        title: 'Esta seguro?',
        text: "Esta usted seguro de que quiere borrar este registro!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!'
    }).then((result) => {
            if (result.value==true) {
            eliminarLinea(id)
            }  
    })   

}

function eliminarLinea(id)
{
    var parametros = 
    {
        'id':id,
    }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/inventario/asignacion_osC.php?eliminarLinea=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
            if(response==1)
            {
                Swal.fire( '','Registro eliminado','success').then(function(){ listaAsignacion();});
            }
        }
    });
}

function guardar()
{
    programa = $('#ddl_programas').val();
    grupo = $('#ddl_grupos').val();
    distribuir = $('#CantGlobDist').val();
    if(programa=='' || programa==null){Swal.fire("","Seleccione un programa","info");return false;}
    if(grupo=='' || grupo==null){Swal.fire("","Seleccione un grupo","info");return false;}
    if(distribuir==0 || distribuir==''){ Swal.fire("","No se a agregado nigun grupo de producto","info");return false;}
    var parametros = {
        'grupo':$('#ddl_grupos').val(),
        'fecha':$('#fechAten').val(),
        'tipo':$('#tipoCompra').val(),
        'comentario':$('#comeGeneAsig').val(),
    }
     $.ajax({
        url: '../controlador/inventario/asignacion_familiasC.php?GuardarAsignacion=true',
        type: 'post',
        dataType: 'json',
        data: { parametros: parametros },
        success: function (datos) {
            if(datos==1)
            {
                Swal.fire("Asignacion Guardada","","success").then(function(){
                    location.reload();
                });
            }

        }
    });
}

function lista_picking()
{
    lista_picking_all();
    $('#modal_lista_picking').modal('show');
}

function lista_picking_all()
{
     $.ajax({
        url: '../controlador/inventario/asignacion_pickingC.php?BeneficiarioPickFacFam=true&fecha='+$('#txtFechaAsign').val(),
        type: 'POST',
        dataType: 'json',
        // data: { param: param },
        success: function (data) {
            tr = '';
            data.forEach(function(item,i){
                console.log(item)
                tr+=`<tr>
                        <td>`+item.text+`</td>
                        <td>`;
                        if(item.data.T =='K')
                        {
                            tr+=`<button class="btn btn-danger btn-sm"><i class="bx bx-trash" onclick="eliminar_picking_fam('`+item.id+`')"></i></button></td>`;
                        }
                    tr+=`</tr>`
            })
            $('#tbl_body_asignacion').html(tr);
          console.log(data);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function eliminar_picking_fam(id)
{
     Swal.fire({
      title: "Esta seguro de eliminar este registro",
      text: "",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si!'
    }).then((result) => {
      if (result.value) {
        eliminar_picking_all_fam(id)        
      }
    });
}

function eliminar_picking_all_fam(id)
{
    var parametros = 
    {
        'idBeneficiario':id,
        'fecha':$('#txtFechaAsign').val(),
    }
      $.ajax({
        url: '../controlador/inventario/asignacion_pickingC.php?eliminarPickingAsigfam=true',
        type: 'POST',
        dataType: 'json',
        data: {parametros:parametros},
        success: function (data) {
            if(data==1)
            {
                Swal.fire("Registro eliminado","","success").then(function()
                {
                    lista_picking_all();
                })
            }
          
        },
        error: function (error) {
            console.log(error);
        }
    });
}