var tbl_picking_os = null;
function eliminarTildes(cadena) {
    return cadena.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}   

var tbl_asignacion_os;
var tablaPoblacion;
let diccionarioTP =
    [
        { 'TP': 'BENEFICI', 'inputname': 'tipoBenef' },
        { 'TP': 'VULNERAB', 'inputname': 'vuln' },
        { 'TP': 'POBLACIO', 'inputname': 'tipoPobl' },
        { 'TP': 'ACCIONSO', 'inputname': 'acciSoci' },
        { 'TP': 'ATENCION', 'inputname': 'tipoAten' },
        { 'TP': 'ENTREGA', 'inputname': 'tipoEntrega' },
        { 'TP': 'ESTADO', 'inputname': 'tipoEstado' },
        { 'TP': 'FRECUENC', 'inputname': 'frecuencia' }
    ];

$(document).ready(function () {

    const today = new Date();
    const dayOfWeek = today.toLocaleDateString('es-Mx', { weekday: 'short' });
    const DiaActual =  eliminarTildes(dayOfWeek.charAt(0).toUpperCase() + dayOfWeek.slice(1).toLowerCase());

    tbl_asignacion_os = $('#tbl_asignacion_os').DataTable({
        // responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        paging:false,
        searching:false,
        info:false,
    });

    tablaPoblacion = $('#tablaPoblacion').DataTable({
        // responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        paging:false,
        searching:false,
        info:false,
    });

    $('#diaEntr').val(DiaActual);

    if($('#diaEntr').val()!='')
    {
        // console.log(DiaActual);
        initPAge();
        beneficiario();

            const selectElement = document.getElementById('diaEntr');
        let previousValue = selectElement.value; // Guardar valor actual

        selectElement.addEventListener('change', function (e) {
            const confirmChange = confirm("¿Estás seguro de que deseas cambiar la opción es posible que los nuevos Beneficiario agregados se pierdan?");
            
            if (!confirmChange) {
                selectElement.value = previousValue;
            } else {
                previousValue = selectElement.value;
                initPAge();
                $('#beneficiario').empty();
            }
        });

    }




    // beneficiario();
    beneficiario_new();
    grupoProducto(); 
    // tipoCompra();
    $('#beneficiario').on('select2:select', function (e) {
        var data = e.params.data;//Datos beneficiario seleccionado
        console.log(data.data);
        tipoCompra(data.data)
        // listaAsignacion();
        // llenarCamposPoblacion(data.data.Codigo);

    });

    autocoplet_pro();
    autocoplet_pro2();

    $('#ddl_producto').on('select2:select', function (e) {
        var data = e.params.data.data;
        // console.log(data);
        $('#grupProd').append($('<option>', { value: data[0].Codigo_Inv, text: data[0].Producto, selected: true }));
        $('#txt_referencia').val(data[0].Codigo_Inv);
    });

});

function add_beneficiario(){
    $('#modal_addBeneficiario').modal('show');

}


function initPAge()
{
    var parametros = 
    {
        'dia':$('#diaEntr').val(),
    }
        $.ajax({
        url: '../controlador/inventario/asignacion_osC.php?initPAge=true',
        type: 'POST',
        dataType: 'json',
        data: { parametros: parametros },
        success: function (data) {
            
        },
        error: function (error) {
            console.log(error);
        }
    });
}

//Metodos
function beneficiario() {
    $('#beneficiario').select2({
        placeholder: 'Beneficiario',
        ajax: {
            url: '../controlador/inventario/picking_productoresAliC.php?Beneficiario=true',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    query: params.term,
                    dia: $('#diaEntr').val(),
                }
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}

function beneficiario_new() {
    $('#beneficiario_new').select2({
        dropdownParent: $('#modal_addBeneficiario'),
        placeholder: 'Beneficiario',
        ajax: {
            url: '../controlador/inventario/picking_productoresAliC.php?Beneficiario_new=true',
            dataType: 'json',
            dropdownParent: $('#modal_addBeneficiario'),
            delay: 250,
            data: function (params) {
                return {
                    query: params.term,
                }
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}


function cambiar_cantidad() {
    var can = $('#txt_cantidad2').val();
    $('#cant').val(can);
    $('#modal_cantidad').modal('hide');
    $('#cant').focus();
}

function autocoplet_pro() {
    tipo = $('#tipoCompra').val();
    url_ = '';
    if(tipo=='84.02')
    {
        // console.log('sss');
        let url_ = '../controlador/inventario/asignacion_osC.php?autocom_pro=true';
        // console.log(url_);
    }else
    {
        let url_ = '../controlador/inventario/alimentos_recibidosC.php?autocom_pro=true';
        // console.log(url_);

    }
    $('#ddl_producto').select2({
        dropdownParent: $('#modal_producto'),
        placeholder: 'Seleccione una producto',
        ajax: {
            url: url_,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                console.log(url_);
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}

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

function show_producto() {
    $('#modal_producto').modal('show');
}

function show_cantidad() {
    $('#modal_cantidad').modal('show');
}

function agregar() {
    if($('#beneficiario').val()=='' || $('#beneficiario').val()==null)
    {
        Swal.fire("","Seleccione Beneficiario","info").then(function(){
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
        'Comentario': $('#comeAsig').val(),
        'beneficiarioCodigo':$('#beneficiario').val(), 
        'beneficiarioN':$('#beneficiario option:selected').text(),   
        'FechaAte':$('#fechAten').val(),   
        'asignacion':$('#tipoCompra').val(),
    };       

    if($('#tipoCompra').val()=='' || $('#tipoCompra').val()==null)
    {
        Swal.fire("Seleccione Tipo de asignacion","","info")
            return false;
    }


    $.ajax({
        url: '../controlador/inventario/asignacion_osC.php?addAsignacion=true',
        type: 'POST',
        dataType: 'json',
        data: { param: datos },
        success: function (data) {
            if(data==1)
            {
                // listaAsignacion();
                cargar_asignacion();
                limpiar();
            }else if(data=="-2")
            {
                swal.fire("El grupo de producto ya esta registrado","","info");
            }
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function eliminar(btn) {
    var row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);
}

function limpiar() {
    $("#grupProd").empty();
    $("#ddl_producto").empty();
    $("#txt_referencia").val("");
    $('#stock').val("");
    $('#cant').val("");
    $("#comeAsig").val("");
}

function removeOptionByValue(value) {
    var selectElement = document.getElementById('tipoCompra');
    for (var i = 0; i < selectElement.options.length; i++) {
        if (selectElement.options[i].value === value) {
            selectElement.remove(i);
            break;
        }
    }
}


function onclicktipoCompra()
{
    $('#modal_tipoCompra').modal('show');
}

function tipoCompra()
{
    $.ajax({
        url: '../controlador/inventario/picking_productoresAliC.php?tipo_asignacion=true',
        type: 'POST',
        dataType: 'json',
        // data: { param: datos },
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
        // $('#pnl_tipo_empaque').html(option);   


            // llenarComboList(data,'tipoCompra');
            // console.log(data);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function llenarDatos(datos) {
    console.log(datos);
        // await tipoCompra();
    
    // $('#beneficiario').val(datos.Beneficiario);
    // $('#fechAten').val(datos.Fecha_Atencion);//Fecha de Atencion
    $('#tipoEstado').val(datos.Estado);//Tipo de Estado
    $('#horaEntrega').val(datos.Hora_Ent); //Hora de Entrega
    $('#frecuencia').val(datos.Frecuencia);//Frecuencia
    $('#tipoEntrega').val(datos.TipoEntega);//Tipo de Entrega
    // $('#diaEntr').val(datos.Dia_Entrega.toUpperCase());//Dia de Entrega
    $('#tipoBenef').val(datos.TipoBene);//Tipo de Beneficiario
    $('#totalPersAten').val(datos.No_Soc);//Total, Personas Atendidas
    $('#tipoPobl').val(datos.Area);//Tipo de Poblacion
    $('#acciSoci').val(datos.AccionSocial);//Accion Social
    $('#vuln').val(datos.vulnerabilidad);//Vulnerabilidad
    $('#tipoAten').val(datos.TipoAtencion);//Tipo de Atencion
    $('#CantGlobSugDist').val(datos.Salario);//Cantidad global sugerida a distribuir
    $('#CantGlobDist').val(datos.Descuento);//Cantidad global a distribuir
    $('#infoNutr').val(datos.InfoNutri);
    const params = [datos.CodigoA, datos.CodigoACD, datos.Envio_No, datos.Beneficiario, datos.Area, datos.Acreditacion, datos.Tipo, datos.Cod_Fam];
    color = datos.Color.replace('Hex_','');
    $('#rowGeneral').css('background-color', '#' + color);
    $('#img_tipoBene').attr('src','../../img/png/'+datos.Picture+'.png')

        datos.asignaciones_hechas.forEach(function(item,i){
        removeOptionByValue(item.No_Hab)
        })

    //  datosExtras(params);


}

function datosExtras(param) {
    $.ajax({
        url: '../controlador/inventario/asignacion_osC.php?datosExtra=true',
        type: 'POST',
        dataType: 'json',
        data: { param: param },
        success: function (data) {
            if (data.result == 1) {
                const tmp = relacionarListas(data.datos, diccionarioTP);
                for (let i = 0; i < tmp.length; i++) {
                    $('#' + tmp[i].inputname).val(tmp[i].Proceso);
                    if (tmp[i].Color != '.') {
                        const color = tmp[i].Color.substring(4);
                        console.log(color);
                        $('#rowGeneral').css('background-color', '#' + color);
                    }
                }
            }
        },
        error: function (error) {
            console.log(error);
        }
    });
}


// function listaAsignacion() {
//     tbl_asignacion_os.destroy();

//     tbl_asignacion_os = $('#tbl_asignacion_os').DataTable({
//         // responsive: true,
//         language: {
//             url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
//         },
//         /*columnDefs: [
//             { targets: [8,9,10,11,12,13], className: 'text-end' } // Alinea las columnas 0, 2 y 4 a la derecha
//         ],*/
//         ajax: {
//             url: '../controlador/inventario/asignacion_osC.php?listaAsignacion=true',
//             type: 'POST',  // Cambia el método a POST    
//             data: function(d) {
//                 /*var parametros = {
//                   'codigoCliente': '',
//                     'tamanioTblBody': altoContTbl <= 25 ? 0 : altoContTbl - 12,
//                 };*/
//                 var param = {
//                     'beneficiario':$('#beneficiario').val(),
//                 }
//                 return { param: param };
//             },              
//               dataSrc: function(json) {
              
//                 // var diff = parseFloat(json.cantidad);
//                 // if(diff < 0)
//                 // {
//                 //   diff = diff*(-1);
//                 // }
//                 // $('#txt_primera_vez').val(json.primera_vez);

//                 // var ingresados_en_pedidos =  $('#txt_cant_total_pedido').val();
//                 // var ingresados_en_kardex =  $('#txt_cant_total').val(diff);
//                 // var total_pedido = $('#txt_cant').val();
//                 // var faltantes = parseFloat(total_pedido)-parseFloat(json.cant_total);

//                 let cantidad = parseFloat(json.cantidad);

//                 $('#CantGlobDist').val(parseFloat(cantidad).toFixed(2));

//                 // console.log(json);

//                 // Devolver solo la parte de la tabla para DataTables
//                 return json.tabla;
//             }        
//         },
//           //scrollX: true,  // Habilitar desplazamiento horizontal
//             paging:false,
//             searching:false,
//             info:false,
//             scrollY: 330,
//             scrollCollapse: true,
//         columns: [
//             { data: null, // Columna autoincremental
//                 render: function (data, type, row, meta) {
//                     return meta.row + 1; // meta.row es el índice de la fila
//                 }
//             },

//             // { data: null,
//             //     render: function(data, type, item) {
//             //         return `<button type="button" class="btn btn-sm btn-danger" onclick="Eliminar_linea('${item.A_No}','${item.CODIGO}')" title="Eliminar linea"><i class="bx bx-trash"></i></button>`;
//             //     } 
//             // },
//             { data: 'Producto'},
//             { data: 'Cantidad',  
//                 render: function(data, type, item) {
//                     return data ? parseFloat(data) : '';
//                 }
//             },
//             { data: 'Procedencia' },
//             { data: null,
//                 render: function(data, type, item) {
//                     return `<button type="button" class="btn btn-sm btn-danger" onclick="eliminar_linea('${item.ID}')" title="Eliminar linea"><i class="bx bx-trash"></i></button>`;
//                 } 
//             }
//         ]
//     });

    
//     // $.ajax({
//     //     url: '../controlador/inventario/asignacion_osC.php?listaAsignacion=true',
//     //     type: 'POST',
//     //     dataType: 'json',
//     //     data: { param: param },
//     //     success: function (data) {
//     //         $('#tbl_body').html(data.tabla);
//     //         $('#CantGlobDist').val(data.cantidad);
//     //     },
//     //     error: function (error) {
//     //         console.log(error);
//     //     }
//     // });
// }

/**
 * Relaciona las listas de datos
 * @param {Array} lista1 los datos extras obtenidos de la tabla Catalogo_Procesos
 * @param {Array} lista2 la relacion que tienen cada Tipo de Proceso con el input que se muestra
 * @returns {Array}
 */
function relacionarListas(lista1, lista2) {
    const relacion = {};
    lista1.forEach(element => {
        const tp = element.TP;
        relacion[tp] = { ...element };
    });

    lista2.forEach(element2 => {
        const tp = element2.TP;
        if (relacion[tp]) {
            relacion[tp] = { ...relacion[tp], ...element2 };
        }
    });

    return Object.values(relacion);
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
            Swal.fire( '','Registro eliminado','success').then(function(){ cargar_asignacion();});
            }

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

valoresFilas = [];
function llenarCamposPoblacion(Codigo) {
    $('#modalBtnGrupo').modal('show');
    $.ajax({
        url: '../controlador/inventario/registro_beneficiarioC.php?llenarCamposPoblacion=true',
        type: 'post',
        dataType: 'json',
        data: { valor: Codigo },
        success: function (datos) {
            if (datos != 0) {
                total_global = 0;
                datos.forEach(function (registro) {
                    var hombres = registro.Hombres;
                    var mujeres = registro.Mujeres;
                    var total = registro.Total;
                    total_global = total+total_global;
                    var valueData = registro.Cmds;
                    valoresFilas.push({ hombres, mujeres, total, valueData });
                });

                // dibujar();
                console.log(valoresFilas)

                $('#totalPersonas').val(total_global);
            }
        }
    });
}

function dibujar(){
    var filas = $('#tablaPoblacion tbody tr');
    var totalSum = 0;
    filas.each(function () {
        var hombres = parseInt($(this).find('.hombres').val()) || 0;
        var mujeres = parseInt($(this).find('.mujeres').val()) || 0;
        var total = parseInt($(this).find('.total').val()) || 0;
        var textoFila = $(this).find('td:first-child').text();
        var valueData = $(this).attr('valueData');

        if (hombres > 0 || mujeres > 0 || total > 0) {
            totalSum += total;
            valoresFilas.push({ hombres, mujeres, total, valueData });
        }
    });
    $('#totalPersonas').val(totalSum);
    $('#modalBtnGrupo').modal('hide');
}


// function llenarCamposPoblacion() {
//     var Codigo = $('#beneficiario').val();
//     if(Codigo=='' || Codigo==null)
//     {
//         Swal.fire("Seleccione un beneficiario","","info")
//         return false;
//     }

//     tablaPoblacion.destroy();

//     tablaPoblacion = $('#tablaPoblacion').DataTable({
//         // responsive: true,
//         language: {
//             url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
//         },
//         /*columnDefs: [
//             { targets: [8,9,10,11,12,13], className: 'text-end' } // Alinea las columnas 0, 2 y 4 a la derecha
//         ],*/
//         ajax: {
//             url: '../controlador/inventario/asignacion_osC.php?llenarCamposPoblacion=true',
//             type: 'POST',  // Cambia el método a POST    
//             data: function(d) {
//                 return { valor: Codigo };
//             },              
//             dataSrc: function(json) {
//                 $('#modalBtnGrupo').modal('show');
//                 // var diff = parseFloat(json.cantidad);
//                 // if(diff < 0)
//                 // {
//                 //   diff = diff*(-1);
//                 // }
//                 // $('#txt_primera_vez').val(json.primera_vez);

//                 // var ingresados_en_pedidos =  $('#txt_cant_total_pedido').val();
//                 // var ingresados_en_kardex =  $('#txt_cant_total').val(diff);
//                 // var total_pedido = $('#txt_cant').val();
//                 // var faltantes = parseFloat(total_pedido)-parseFloat(json.cant_total);

//                 let tabla = [];
//                 total_global = 0;
//                 if(json.datos.length > 0){
//                     for(let p of json.poblacion){
//                         let clave = json.datos.map(obj => obj.Cmds).indexOf(p.Cmds);
                        
//                         let item = {};
//                         if(!clave){
//                             item['Hombres']=0; item['Mujeres']=0; item['Total']=0;
//                         }else{
//                             item = json.datos[clave];
//                         }
//                         let objeto = {
//                             'Poblacion': p.Proceso,
//                             'Hombres': item.Hombres,
//                             'Mujeres': item.Mujeres,
//                             'Total': item.Total
//                         }

//                         tabla.push(objeto);
//                     }
//                 }
//                 total_global= item.Total+total_global

//                 $('#totalPersAten').val(total_global);
//                 // let cantidad = parseFloat(json.cantidad);

//                 // $('#CantGlobDist').val(parseInt(cantidad));

//                 // console.log(json);

//                 // Devolver solo la parte de la tabla para DataTables
//                 return tabla;
//             }        
//         },
//         //scrollX: true,  // Habilitar desplazamiento horizontal
//         paging:false,
//         searching:false,
//         info:false,
//         scrollY: 330,
//         scrollCollapse: true,
//         columns: [
//             { data: 'Poblacion'},
//             { data: 'Hombres'},
//             { data: 'Mujeres' },
//             { data: 'Total' },
//         ]
//     });

//     // $.ajax({
//     //     url: '../controlador/inventario/asignacion_osC.php?llenarCamposPoblacion=true',
//     //     type: 'post',
//     //     dataType: 'json',
//     //     data: { valor: Codigo },
//     //     success: function (datos) {
//     //         $('#modalBtnGrupo').modal('show');
//     //         $('#tbl_body_poblacion').html(datos);
//     //         console.log(datos);
//     //     }
//     // });
// }

function asignar_beneficiario()
{
    id = $('#beneficiario_new').val();
    parametros = {
        'cliente':id,
    }
        $.ajax({
        url: '../controlador/inventario/asignacion_osC.php?asignar_beneficiario=true',
        type: 'post',
        dataType: 'json',
        data: { parametros: parametros },
        success: function (datos) {
            if(datos)
            {
                Swal.fire('Beneficiario Agregado','','success').then(function(){
                    $('#modal_addBeneficiario').modal('hide');
                })
            }
        }
    });
}

function eliminar_asignacion_beneficiario()
{
    id = $('#beneficiario').val();
    if(id=='' || id== null)
    {
        Swal.fire("Seleccione un beneficiario","","info");
        return false;
    }
    var parametros = {
        'cliente':id,
    }
    Swal.fire({
                title: 'Esta seguro?',
                text: "Esta usted seguro de quitar este registro!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si!'
            }).then((result) => {
                if (result.value==true) {
                $.ajax({
                    url: '../controlador/inventario/asignacion_osC.php?eliminar_asignacion_beneficiario=true',
                    type: 'post',
                    dataType: 'json',
                    data: { parametros: parametros },
                    success: function (datos) {
                        if(datos)
                        {
                            Swal.fire('Beneficiario Eliminado','','success');
                            $('#beneficiario').empty();
                        }
                    }
                });     
                }
            })



    
                    
}

function cambiar_tipo_asig(id)
{
    $('#tipoCompra').val(id)
    $('#modal_tipoCompra').modal("hide")
}


function lista_picking()
{
    lista_picking_all();
    $('#modal_lista_picking').modal('show');
}

function lista_picking_all()
{
     $.ajax({
        url: '../controlador/inventario/asignacion_pickingC.php?BeneficiarioPickFac=true&fecha='+$('#txtFechaAsign').val(),
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
                            tr+=`<button class="btn btn-danger btn-sm"><i class="bx bx-trash" onclick="eliminar_picking('`+item.id+`')"></i></button></td>`;
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


function eliminar_picking(id)
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
        eliminar_picking_all(id)        
      }
    });
}

function eliminar_picking_all(id)
{
    var parametros = 
    {
        'idBeneficiario':id,
        'fecha':$('#txtFechaAsign').val(),
    }
      $.ajax({
        url: '../controlador/inventario/asignacion_pickingC.php?eliminarPickingAsig=true',
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

function grupoProducto() {
    $.ajax({
        url: '../controlador/inventario/picking_productoresAliC.php?grupoProducto=true',
        type: 'POST',
        dataType: 'json',
        // data: { param: param },
        success: function (data) {
            llenarComboList(data,'ddlgrupoProducto');
            cargarProductosGrupo() 
        },
        error: function (error) {
            console.log(error);
        }
    });
}


function cargarProductosGrupo() {
    grupo = $('#ddlgrupoProducto').val();
    // console.log('ss');
    $('#txt_codigo').select2({
    placeholder: 'Seleccione una beneficiario',
    width:'100%',
    ajax: {
    url: '../controlador/inventario/picking_productoresAliC.php?cargarProductosGrupo=true',   
    dataType: 'json',
    delay: 250, 
    data: function (params) {
        return {
            query: params.term,
            grupo: grupo,
        }
    },
    processResults: function (data) {
        return {
          results: data.map(function (item) {
            return {
              id: item.id,
              text: '<div style="background:'+item.fondo+'"><span style="color:'+item.texto+';font-weight: bold;">' + item.text + '</span></div>',
              data : item.data,
            };
          })
        };
      },
      cache: true
    },
    escapeMarkup: function (markup) {
      return markup;
    }
  });
}

function validar_codigo()
{
    codigo = $('#txt_codigo').val();
    if(codigo=='')
    {
        return false;
    }
    grupo = $('#ddlgrupoProducto').val();
    var parametros = {
    'codigo':codigo,
    'grupo':grupo,
    }
    $.ajax({
        type: "POST",
        url:   '../controlador/inventario/asignacion_pickingC.php?buscar_producto=true',
            data:{parametros:parametros},
        dataType:'json',
        success: function(data)
        {
            if(data.validado_grupo==0)
            {
                Swal.fire("Codigo Ingresado no pertenece al grupo de produto","","error").then(function(){
                    $('#txt_codigo').val("");

                });
            }else{

                data = data.producto[0];
                 console.log(data);
                if(data!=undefined)
                {
                    $('#txt_id').val(data.Codigo_Inv)
                    $('#txt_ubicacion').val(data.ubicacion)
                    $('#txt_donante').val(data.Cliente)
                    // $('#txt_grupo').val(data.Producto)
                    $('#txt_stock').val(data.Entrada)
                    $('#txt_unidad').val(data.Unidad)
                    $('#txt_fecha_exp').val(formatoDate(data.Fecha.date));

                    var fecha1 = new Date();
                    var fecha2 = new Date(formatoDate(data.Fecha_Exp.date));
                    var diferenciaEnMilisegundos = fecha2 - fecha1;
                    var diferenciaEnDias = ((diferenciaEnMilisegundos/ 1000)/86400);
                    diferenciaEnDias = parseInt(diferenciaEnDias);

                    if(diferenciaEnDias<0)
                    {
                         $('#btn_expired').css('display','initial');
                         $('#txt_fecha_exp').css('color','red');
                         $('#img_por_expirar').attr('src','../../img/gif/expired_titi2.gif');
                         $('#btn_titulo').text('Expirado')
                         $('#txt_fecha_exp').css('background','#ffff');
                    }else if(diferenciaEnDias<=10 && diferenciaEnDias>0){
                         $('#btn_expired').css('display','initial');
                         $('#txt_fecha_exp').css('color','#e9bd11');
                         $('#img_por_expirar').attr('src','../../img/gif/expired_titi.gif');
                         $('#btn_titulo').text('Por Expirar')
                         $('#txt_fecha_exp').css('background','#a6a5a5');
                    }else
                    {
                        $('#btn_expired').css('display','none');
                        $('#txt_fecha_exp').css('color','#000000');
                        $('#img_por_expirar').attr('src','../../img/png/expired.png');
                        $('#txt_fecha_exp').css('background','#ffff');
                    }


                }else
                {
                    Swal.fire("Codigo de producto no encontrado","","info");
                    limpiar_data();
                }
            }
        }
    });
}

function agregar_picking()
{
    stock = $('#txt_stock').val();
    cant =$('#cant').val();

    if($('#beneficiario').val()=='' || $('#beneficiario').val()==null)
    {
        Swal.fire("Seleccione una Beneficiario valida","","info");
        return false;
    }
    if($('#txt_id').val()=='' || $('#txt_id').val()== null || $('#txt_id').val()=='0')
    {
        Swal.fire("Seleccione una producto","","info");
        return false;
    }
    if($('#cant').val()=='' || $('#cant').val()== null || $('#cant').val()=='0')
    {
        Swal.fire("Seleccione una cantidad valida","","info");
        return false;
    }
    
    if(parseFloat(cant)>parseFloat(stock))
    {
        Swal.fire("Cantidad Supera al stock","","info");
        return false;
    }


    var parametros = {
    'beneficiario':$('#beneficiario').val(),
    'CodigoInv':$('#txt_id').val(),
    'Cantidad':$('#cant').val(),
    'FechaAte':$('#fechAten').val(),
    'codigoProducto':$('#txt_codigo').val(),
    'asignacion':$('#tipoCompra').val(),
    'id':$('#txt_codigo').val(),
    }
    $.ajax({
        type: "POST",
        url:   '../controlador/inventario/picking_productoresAliC.php?agregar_picking=true',
            data:{parametros:parametros},
        dataType:'json',
        success: function(data)
        {
            if(data==1)
            {
                Swal.fire("Producto agregado","","success")
                cargar_asignacion();
            }else if(data==-2)
            {
                Swal.fire("El producto no se puede ingresar por que supera el total de Grupo","","error")
            }
            console.log(data);
        }
    });
}

function cargar_asignacion()
{
    if ($.fn.DataTable.isDataTable('#tbl_picking_os')) {
          $('#tbl_picking_os').DataTable().destroy();
    }

    tbl_picking_os = $('#tbl_picking_os').DataTable({
        // responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        /*columnDefs: [
            { targets: [8,9,10,11,12,13], className: 'text-end' } // Alinea las columnas 0, 2 y 4 a la derecha
        ],*/
        ajax: {
            url: '../controlador/inventario/picking_productoresAliC.php?cargar_asignacion=true',
            type: 'POST',  // Cambia el método a POST    
            data: function(d) {
                /*var parametros = {
                  'codigoCliente': '',
                    'tamanioTblBody': altoContTbl <= 25 ? 0 : altoContTbl - 12,
                };*/
                var param = {
                    'beneficiario':$('#beneficiario').val(),
                    'FechaAte':$('#fechAten').val(),
                    'asignacion':$('#tipoCompra').val(),
                }
                return { parametros: param };
            },              
              dataSrc: function(json) {
              
                var to = parseFloat( $('#txt_total').val());
                var ing = parseFloat(json.total);
                // console.log(to);
                // console.log(ing);

                
                //$('#txt_total_ing').val(to-ing);
                $('#txt_total_ing').val(to-ing);
                
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
            { data: null,
                render: function(data, type, item) {
                    return `<button type="button" class="btn btn-sm btn-danger" onclick="eliminarlinea('${item.ID}')" title="Eliminar linea"><i class="bx bx-trash"></i></button>`;
                } 
            },
            
            { data: 'Fecha.date',  
                render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                }
            },
            { data: 'Fecha_C.date',  
                render: function(data, type, item) {
                    return data ? new Date(data).toLocaleDateString() : '';
                }
            },
            { data: 'Producto' },
            { data: 'Codigo_Barra' },
            { data: 'Nombre_Completo' },
            { data: 'Total' },
            
            
        ]
    });

}

function eliminarlinea(id)
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
            Eliminar(id);
            }
        })
}

function Eliminar(id)
{       
    var parametros = {
    'id':id,
    }
    $.ajax({
        type: "POST",
        url:   '../controlador/inventario/asignacion_pickingC.php?eliminarLinea=true',
            data:{parametros:parametros},
        dataType:'json',
        success: function(data)
        {
            cargar_asignacion();                
        }
    });
}

function guardar() 
{
    var parametros = {
        'beneficiario':$('#beneficiario').val(),
        'FechaAte':$('#fechAten').val(),
        'asignacion':$('#tipoCompra').val(),
    }
    $.ajax({
        url: '../controlador/inventario/picking_productoresAliC.php?GuardarPicking=true',
        type: 'POST',
        dataType: 'json',
        data: { parametros: parametros },
        success: function (data) {
            Swal.fire("Picking Guardado","","success").then(function (){
                location.reload();
            })
        },
        error: function (error) {
            console.log(error);
        }
    });
}
