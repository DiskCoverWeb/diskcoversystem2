var video;
var tbl_picking_os;

$(document).ready(function () {

    tbl_picking_os = $('#tbl_picking_os').DataTable({
        // responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        paging:false,
        searching:false,
        info:false,
    });

    beneficiario();

        $('#beneficiario').on('select2:select', function (e) {
        var datos = e.params.data.data;//Datos beneficiario seleccionado
        console.log(datos);
        // $('#fechAten').val(datos.Fecha_Atencion);//Fecha de Atencion
        $('#tipoEstado').val(datos.Estado);//Tipo de Estado
        $('#tipoEntrega').val(datos.TipoEntega);//Tipo de Entrega
        $('#horaEntrega').val(datos.Hora); //Hora de Entrega
        $('#diaEntr').val(datos.Dia_Ent.toUpperCase());//Dia de Entrega
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
        $('#comeGeneAsign').val(datos.Ruta);
        $('#txt_responsable').val(datos.Nombre_Completo)
        cargarOrden();
        cargar_asignacion();

    });

})

function beneficiario() {
    $('#beneficiario').select2({
    placeholder: 'Seleccione una beneficiario',
    width: 'resolve',
    ajax: {
        url: '../controlador/inventario/asignacion_pickingC.php?Beneficiario=true',
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                query: params.term,
                fecha: $('#txtFechaAsign').val(),
            }
        },
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

function cargarOrden() {
    codigo = $('#beneficiario').val();
    beneficiario = codigo.split('-');
    var param = {
        'beneficiario':beneficiario[0],
        'tipo':beneficiario[1],
        'fecha':$('#txtFechaAsign').val(),
        'orden':beneficiario[2],
    }
    $.ajax({
        url: '../controlador/inventario/asignacion_pickingC.php?cargarOrden=true',
        type: 'POST',
        dataType: 'json',
        data: { param: param },
        success: function (data) {
            $('#pnl_detalle').html(data.detalle);
            $('#ddlgrupoProducto').html(data.ddl);
            $('#txt_total').val(data.total);
            $('#CantGlobDist').val(data.cantidad);
            cargarProductosGrupo() 
        },
        error: function (error) {
            console.log(error);
        }
    });
}


function cargarProductosGrupo() {
    $('#txt_codigo').empty();
    grupo = $('#ddlgrupoProducto').val();
    // console.log('ss');
    $('#txt_codigo').select2({
    placeholder: 'Seleccione una beneficiario',
    width:'100%',
    ajax: {
    url: '../controlador/inventario/asignacion_pickingC.php?cargarProductosGrupo=true',   
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

function productosPorQR(codigo){
    let grupo = $('#ddlgrupoProducto').val();
    /*if(grupo == ""){
        Swal.fire('Antes de realizar esta consulta, seleccione un grupo de producto.', '', 'warning');
        return;
    }*/
    $.ajax({
        url: '../controlador/inventario/asignacion_pickingC.php?cargarProductosGrupo=true&query='+codigo+'&grupo='+grupo,           
        method: 'GET',
        dataType: 'json',
        success: (data) => {
            console.log(data);
            let datos = data[0];
            if(data.length > 0){
                // Crear una nueva opción con los 3 parámetros y asignarla al select2
                const nuevaOpcion = new Option(datos.text.trim(), datos.id, true, true);

                // Agregar el atributo `data` a la opción
                //$(nuevaOpcion).data('data', datos.data);

                // Añadir y seleccionar la nueva opción
                $('#txt_codigo').append(nuevaOpcion).trigger('change');//'select2:select'
                //setearCamposPedidos(datos.data);
            }else{
                Swal.fire('No se encontró información para el codigo: '+codigo, '', 'error');
            }
        }
    });
}

function ver_detalle()
{
    cargarOrden();
    $('#modalDetalleCantidad').modal('show');
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


function limpiar_data()
{
    $('#txt_id').val("")
    $('#txt_ubicacion').val("")
    $('#txt_donante').val("")
    // $('#txt_grupo').val(da.Producto)
    $('#txt_stock').val("")
    $('#txt_unidad').val("")
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
    'FechaAsign':$('#txtFechaAsign').val(),
    'codigoProducto':$('#txt_codigo').val(),
    'id':$('#txt_codigo').val(),
    }
    $.ajax({
        type: "POST",
        url:   '../controlador/inventario/asignacion_pickingC.php?agregar_picking=true',
            data:{parametros:parametros},
        dataType:'json',
        success: function(data)
        {
            if(data==1)
            {
                Swal.fire("Producto agregado","","success")
                $('#txt_codigo').empty();
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
    tbl_picking_os.destroy();

    tbl_picking_os = $('#tbl_picking_os').DataTable({
        // responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        /*columnDefs: [
            { targets: [8,9,10,11,12,13], className: 'text-end' } // Alinea las columnas 0, 2 y 4 a la derecha
        ],*/
        ajax: {
            url: '../controlador/inventario/asignacion_pickingC.php?cargar_asignacion=true',
            type: 'POST',  // Cambia el método a POST    
            data: function(d) {
                /*var parametros = {
                  'codigoCliente': '',
                    'tamanioTblBody': altoContTbl <= 25 ? 0 : altoContTbl - 12,
                };*/
                var param = {
                    'beneficiario':$('#beneficiario').val(),
                    'FechaAte':$('#txtFechaAsign').val(),
                }
                return { parametros: param };
            },              
              dataSrc: function(json) {
              
                var to = parseFloat( $('#txt_total').val());
                var ing = parseFloat(json.total);
                // console.log(to);
                // console.log(ing);

                
                //$('#txt_total_ing').val(to-ing);
                var gran_dif = to-ing;
                $('#txt_total_ing').val(gran_dif.toFixed(2));
                
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

    // var parametros = {
    // 'beneficiario':$('#beneficiario').val(),
    // 'FechaAte':$('#fechAten').val(),
    // }
    // $.ajax({
    //     type: "POST",
    //     url:   '../controlador/inventario/asignacion_pickingC.php?cargar_asignacion=true',
    //         data:{parametros:parametros},
    //     dataType:'json',
    //     success: function(data)
    //     {
    //         $('#tbl_body').html(data.tabla);

    //         var to = parseFloat( $('#txt_total').val());
    //         var ing = parseFloat(data.total);

    //         console.log(to);
    //         $('#txt_total_ing').val(to-ing);
    //         console.log(data);
    //     }
    // });

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
function guardar() {
    
    if(parseInt($('#txt_total_ing').val()) > 0){
        Swal.fire('La cantidad total de productos del picking no cubre el total de productos asignados.', '', 'error');
        return;
    }else if(parseInt($('#txt_total_ing').val()) < 0){
        Swal.fire('La cantidad total de productos del picking excede el total de productos asignados.', '', 'error');
        return;
    }

    codigo = $('#beneficiario').val();
    beneficiario = codigo.split('-');
    orden = beneficiario[2];
    var parametros = {
        'beneficiario':beneficiario[0],
        'tipo':beneficiario[1],
        'fecha':$('#fechAten').val(),
        'fechaAsi':$('#txtFechaAsign').val(),
        'orden':orden,
    }
    $.ajax({
        url: '../controlador/inventario/asignacion_pickingC.php?GuardarPicking=true',
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

function escanear_qr(){
    iniciarEscanerQR();
    $('#modal_qr_escaner').modal('show');
}

let scanner;
 let NumCamara = 0;
 function iniciarEscanerQR() {
    NumCamara = $('#ddl_camaras').val();
    scanner = new Html5Qrcode("reader");
    $('#qrescaner_carga').hide();
    Html5Qrcode.getCameras().then(devices => {
        op = '';
        devices.forEach((camera, index) => {
            op+='<option value="'+index+'">Camara '+(index+1)+'</option>'
        });
        $('#ddl_camaras').html(op)

        if (devices.length > 0) {
            let cameraId = devices[NumCamara].id; // Usa la primera cámara disponible
            scanner.start(
                cameraId,
                {
                    fps: 10, // Velocidad de escaneo
                    qrbox: { width: 250, height: 250 } // Tamaño del área de escaneo
                },
                (decodedText) => {
                    productosPorQR(decodedText);
                    scanner.stop(); // Detiene la cámara después de leer un código
                    $('#modal_qr_escaner').modal('hide');
                },
                (errorMessage) => {
                    console.log("Error de escaneo:", errorMessage);
                }
            );
        } else {
            alert("No se encontró una cámara.");
        }
    }).catch(err => console.error("Error al obtener cámaras:", err));
}
