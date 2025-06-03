var video;
var tbl_picking_os;
$(document).ready(function () {
     // programas();
    ddl_asignaciones_fam()

    $('#ddl_asignaciones_fam').on('select2:select', function (e) {
        var datos = e.params.data.data;//Datos beneficiario seleccionado
        console.log(datos);
        $('#txt_fechaAsig').val(formatoDate(datos.Fecha.date));//Fecha de Atencion
        datos.Fecha = formatoDate(datos.Fecha.date);
        $('#ddl_programas').html('<option>'+datos.Programa+'</option>');
        $('#ddl_grupos').html('<option>'+datos.Grupo+'</option>');
        // $('#horaEntrega').val(datos.Hora); //Hora de Entrega
        // $('#diaEntr').val(datos.Dia_Ent.toUpperCase());//Dia de Entrega
        // $('#frecuencia').val(datos.Frecuencia);//Frecuencia
        // $('#tipoBenef').val(datos.TipoBene);//Tipo de Beneficiario
        $('#totalPersAten').val(datos.NoGrupoInt);//Total, Personas Atendidas
        // $('#tipoPobl').val(datos.Area);//Tipo de Poblacion
        // $('#acciSoci').val(datos.AccionSocial);//Accion Social
        // $('#vuln').val(datos.vulnerabilidad);//Vulnerabilidad
        // $('#tipoAten').val(datos.TipoAtencion);//Tipo de Atencion
        // $('#CantGlobSugDist').val(datos.Salario);//Cantidad global sugerida a distribuir
        // $('#CantGlobDist').val(datos.Descuento);//Cantidad global a distribuir
        // $('#infoNutr').val(datos.InfoNutri);
        // $('#comeGeneAsign').val(datos.Ruta);
        $('#txt_responsable').val(datos.Nombre_Completo)
        cargarOrden(datos);
        cargar_asignacion();

    });

})

function ver_detalle()
{
    orden = $('#ddl_asignaciones_fam').val();
    parte = orden.split('-');
    datos = 
    {
        'Orden_No':parte[0],
        'No_Hab':parte[1],
        'Fecha':$('#txt_fechaAsig').val(),
    }
    cargarOrden(datos);
    $('#modalDetalleCantidad').modal('show');
}

function cargarOrden(datos) {
    // fecha = datos['Fecha'];
    
    var param = {
        'orden':datos['Orden_No'],
        'tipo':datos['No_Hab'],
        'Fecha_asig':datos['Fecha'],
    }
    $.ajax({
        url: '../controlador/inventario/asignacion_familiasC.php?cargarOrden=true',
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
            url: '../controlador/inventario/asignacion_familiasC.php?cargar_asignacion=true',
            type: 'POST',  // Cambia el método a POST    
            data: function(d) {
                /*var parametros = {
                  'codigoCliente': '',
                    'tamanioTblBody': altoContTbl <= 25 ? 0 : altoContTbl - 12,
                };*/
                var param = {
                    'beneficiario':$('#ddl_asignaciones_fam').val(),
                    'FechaAte':$('#txt_fechaAsig').val(),
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
                    return `<button type="button" class="btn btn-sm btn-danger" onclick="eliminarlinea('${item.ID}')" title="Eliminar linea"><i class="bx bx-trash me-0"></i></button>`;
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

function ddl_asignaciones_fam()
{
    $('#ddl_asignaciones_fam').select2({
        placeholder: 'Seleccione asignacion',
        // dropdownAutoWidth: true,
      //  selectionCssClass: 'form-control form-control-sm h-100',  // Para el contenedor de Select2
        ajax: {
            url:   '../controlador/inventario/asignacion_familiasC.php?ddl_asignaciones_fam=true',
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
                    $('#txt_codBarras').val(data.Codigo_Barra)
                    $('#txt_cmds').val(data.Cmds);

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

    if($('#ddl_asignaciones_fam').val()=='' || $('#ddl_asignaciones_fam').val()==null)
    {
        Swal.fire("Seleccione una Asignacion valida","","info");
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
    'asignacion':$('#ddl_asignaciones_fam').val(),
    'CodigoInv':$('#txt_id').val(),
    'Cantidad':$('#cant').val(),
    'FechaAte':$('#fechAten').val(),
    'FechaAsig':$('#txt_fechaAsig').val(),
    'codigoProducto':$('#txt_codigo').val(),
    'id':$('#txt_codigo').val(),
    'codBarras':$('#txt_codBarras').val(),
    'cmds':$('#txt_cmds').val(),
    }
    $.ajax({
        type: "POST",
        url:   '../controlador/inventario/asignacion_familiasC.php?agregar_picking=true',
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
            }else
            {                
                Swal.fire("El producto ya esta registrado","","error")
            }
            console.log(data);
        }
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

function guardar() {
    
    if(parseInt($('#txt_total_ing').val()) > 0){
        Swal.fire('La cantidad total de productos del picking no cubre el total de productos asignados.', '', 'error');
        return;
    }else if(parseInt($('#txt_total_ing').val()) < 0){
        Swal.fire('La cantidad total de productos del picking excede el total de productos asignados.', '', 'error');
        return;
    }

    codigo = $('#ddl_asignaciones_fam').val();
    beneficiario = codigo.split('-');
    orden = beneficiario[2];
    var parametros = {
        'orden':beneficiario[0],
        'tipo':beneficiario[1],
        'fecha':$('#fechAten').val(),
        'fechaAsi':$('#txt_fechaAsig').val(),
    }
    $.ajax({
        url: '../controlador/inventario/asignacion_familiasC.php?GuardarPicking=true',
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