// window.addEventListener('beforeunload', function (e) {
//     // Mensaje personalizado (no será visible en la mayoría de los navegadores)
//     const message = "¿Estás seguro de que deseas abandonar la página?";
//     e.preventDefault();
//     e.returnValue = message;
//     return message;
// });
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
    // tipoCompra();
    $('#beneficiario').on('select2:select', function (e) {
        var data = e.params.data;//Datos beneficiario seleccionado
        // console.log(data.data);
        tipoCompra(data.data)
        listaAsignacion();

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
            url: '../controlador/inventario/asignacion_osC.php?Beneficiario=true',
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
            url: '../controlador/inventario/asignacion_osC.php?Beneficiario_new=true',
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
                listaAsignacion();
                limpiar();
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

function tipoCompra(benefi)
{
    $.ajax({
        url: '../controlador/inventario/asignacion_osC.php?tipo_asignacion=true',
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
                        '<button type="button" class="btn btn-light btn-sm"><img src="../../img/png/'+item.Picture+'.png" onclick="cambiar_empaque(\''+item.ID+'\')"  style="width: 60px;height: 60px;"></button><br>'+
                        '<b>'+item.Proceso+'</b>'+
                    '</div>';

            op+='<option value="'+item.ID+'">'+item.Proceso+'</option>';
        })

        $('#tipoCompra').html(op); 
        $('#pnl_tipo_empaque').html(option);   

        llenarDatos(benefi);


            // llenarComboList(data,'tipoCompra');
            // console.log(data);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

function llenarDatos(datos) {
    // console.log(datos);
        // await tipoCompra();
    
    // $('#beneficiario').val(datos.Beneficiario);
    // $('#fechAten').val(datos.Fecha_Atencion);//Fecha de Atencion
    $('#tipoEstado').val(datos.Estado);//Tipo de Estado
    $('#tipoEntrega').val(datos.TipoEntega);//Tipo de Entrega
    $('#horaEntrega').val(datos.Hora); //Hora de Entrega
    // $('#diaEntr').val(datos.Dia_Entrega.toUpperCase());//Dia de Entrega
    $('#frecuencia').val(datos.Frecuencia);//Frecuencia
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


function listaAsignacion() {
    tbl_asignacion_os.destroy();

    tbl_asignacion_os = $('#tbl_asignacion_os').DataTable({
        // responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        /*columnDefs: [
            { targets: [8,9,10,11,12,13], className: 'text-end' } // Alinea las columnas 0, 2 y 4 a la derecha
        ],*/
        ajax: {
            url: '../controlador/inventario/asignacion_osC.php?listaAsignacion=true',
            type: 'POST',  // Cambia el método a POST    
            data: function(d) {
                /*var parametros = {
                  'codigoCliente': '',
                    'tamanioTblBody': altoContTbl <= 25 ? 0 : altoContTbl - 12,
                };*/
                var param = {
                    'beneficiario':$('#beneficiario').val(),
                }
                return { param: param };
            },              
              dataSrc: function(json) {
              
                // var diff = parseFloat(json.cantidad);
                // if(diff < 0)
                // {
                //   diff = diff*(-1);
                // }
                // $('#txt_primera_vez').val(json.primera_vez);

                // var ingresados_en_pedidos =  $('#txt_cant_total_pedido').val();
                // var ingresados_en_kardex =  $('#txt_cant_total').val(diff);
                // var total_pedido = $('#txt_cant').val();
                // var faltantes = parseFloat(total_pedido)-parseFloat(json.cant_total);

                let cantidad = parseFloat(json.cantidad);

                $('#CantGlobDist').val(parseInt(cantidad));

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
                    return data ? parseInt(data) : '';
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

    
    // $.ajax({
    //     url: '../controlador/inventario/asignacion_osC.php?listaAsignacion=true',
    //     type: 'POST',
    //     dataType: 'json',
    //     data: { param: param },
    //     success: function (data) {
    //         $('#tbl_body').html(data.tabla);
    //         $('#CantGlobDist').val(data.cantidad);
    //     },
    //     error: function (error) {
    //         console.log(error);
    //     }
    // });
}

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
            Swal.fire( '','Registro eliminado','success').then(function(){ listaAsignacion();});
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

function llenarCamposPoblacion() {
    var Codigo = $('#beneficiario').val();
    if(Codigo=='' || Codigo==null)
    {
        Swal.fire("Seleccione un beneficiario","","info")
        return false;
    }

    tablaPoblacion.destroy();

    tablaPoblacion = $('#tablaPoblacion').DataTable({
        // responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        /*columnDefs: [
            { targets: [8,9,10,11,12,13], className: 'text-end' } // Alinea las columnas 0, 2 y 4 a la derecha
        ],*/
        ajax: {
            url: '../controlador/inventario/asignacion_osC.php?llenarCamposPoblacion=true',
            type: 'POST',  // Cambia el método a POST    
            data: function(d) {
                return { valor: Codigo };
            },              
            dataSrc: function(json) {
                $('#modalBtnGrupo').modal('show');
                // var diff = parseFloat(json.cantidad);
                // if(diff < 0)
                // {
                //   diff = diff*(-1);
                // }
                // $('#txt_primera_vez').val(json.primera_vez);

                // var ingresados_en_pedidos =  $('#txt_cant_total_pedido').val();
                // var ingresados_en_kardex =  $('#txt_cant_total').val(diff);
                // var total_pedido = $('#txt_cant').val();
                // var faltantes = parseFloat(total_pedido)-parseFloat(json.cant_total);

                let tabla = [];
                if(json.datos.length > 0){
                    for(let p of json.poblacion){
                        let clave = json.datos.map(obj => obj.Cmds).indexOf(p.Cmds);
                        
                        let item = {};
                        if(!clave){
                            item['Hombres']=0; item['Mujeres']=0; item['Total']=0;
                        }else{
                            item = json.datos[clave];
                        }
                        let objeto = {
                            'Poblacion': p.Proceso,
                            'Hombres': item.Hombres,
                            'Mujeres': item.Mujeres,
                            'Total': item.Total
                        }
                        tabla.push(objeto);
                    }
                }
                // let cantidad = parseFloat(json.cantidad);

                // $('#CantGlobDist').val(parseInt(cantidad));

                // console.log(json);

                // Devolver solo la parte de la tabla para DataTables
                return tabla;
            }        
        },
        //scrollX: true,  // Habilitar desplazamiento horizontal
        paging:false,
        searching:false,
        info:false,
        scrollY: 330,
        scrollCollapse: true,
        columns: [
            { data: 'Poblacion'},
            { data: 'Hombres'},
            { data: 'Mujeres' },
            { data: 'Total' },
        ]
    });

    // $.ajax({
    //     url: '../controlador/inventario/asignacion_osC.php?llenarCamposPoblacion=true',
    //     type: 'post',
    //     dataType: 'json',
    //     data: { valor: Codigo },
    //     success: function (datos) {
    //         $('#modalBtnGrupo').modal('show');
    //         $('#tbl_body_poblacion').html(datos);
    //         console.log(datos);
    //     }
    // });
}

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