<?php
  $tc = '';
  if(isset($_GET['tipo_subcta'])){  $tc = $_GET['tipo_subcta']; }
  if(isset($_GET['OpcTM'])){  $OpcTM = $_GET['OpcTM']; }
  if(isset($_GET['OpcDH'])){  $OpcDH = $_GET['OpcDH']; }
  if(isset($_GET['cta'])){  $cta = $_GET['cta']; }
  if(isset($_GET['tipoc'])){  $tipoc = $_GET['tipoc']; }
  if(isset($_GET['fecha'])){  $fecha = $_GET['fecha']; }
 ?>
  <style type="text/css">
    .ui-helper-hidden-accessible {
            display: none;
        }
  </style>

 <script type="text/javascript">
$(document).ready(function () {
    limpiar_asiento_sc();
    var tc = '<?php echo $tc; ?>';
    var Cuenta = "";
    titulos(tc);
//     cargar_tablas_sc();
    carga_ddl();
    cargar_submodulos()
    mostrarCuenta(parent.Cuenta);

    datos_carga_ddl();

    $("#ddl_aux").on("focus", function() {
      $(this).autocomplete("search",'%'); 
    });

    $("#txt_factura").on("focus", function() {
      $(this).autocomplete("search", "%");
    });

      $( "#ddl_aux" ).autocomplete({
      source: function( request, response ) {

        $( "#ddl_aux" ).removeClass("ui-helper-hidden-accessible");
      var tc = '<?php echo $tc; ?>';
      var OpcDH = '<?php echo $OpcDH; ?>';
      var OpcTM = '<?php echo $OpcTM; ?>';
      var cta = '<?php echo $cta; ?>';
                
            $.ajax({
                url:   '../controlador/contabilidad/incomC.php?modal_detalle_aux=true&tc='+tc+'&OpcDH='+OpcDH+'&OpcTM='+OpcTM+'&cta='+cta,
                type: 'post',
                dataType: "json",
                data: {
                    q: request.term,
                    tipo:0
                },
                success: function( data ) {
                  // console.log(data);
                    response( data );
                }
            });
        },
        select: function (event, ui) {
          
            $("#ddl_aux").val(ui.item.label);
            return false;
        },
        focus: function(event, ui){
            // $("#ddl_aux").val(ui.item.label);
            return false;
        },
    });

    $('#ddl_subcta').on('select2:select', function (e) {
      // console.log(e)
      var data = e.params.data.data;
      cargar_submodulos(data.Nivel);            
      $('#Pnl_DLSubCta').css('display','block');     
      $('#DLSubCta').select2('open');
      // console.log(data);

    });

    $( "#txt_factura" ).autocomplete({
      source: function( request, response ) {
        var tc = '<?php echo $tc; ?>';
        var OpcDH = '<?php echo $OpcDH; ?>';
        var OpcTM = '<?php echo $OpcTM; ?>';
        var cta = '<?php echo $cta; ?>';
        var fecha = $('#txt_fecha_ven').val();
            $.ajax({
                url:   '../controlador/contabilidad/incomC.php?facturas_pendientes=true&SubCta='+tc+'&fecha='+fecha+'&cta='+cta+'&Codigo='+$('#ddl_subcta').val(),
                type: 'post',
                dataType: "json",
                data: {
                    q: request.term
                },
                success: function( data ) {
                  // console.log(data);
                    response( data );
                }
            });
        },
        select: function (event, ui) {
            $("#txt_factura").val(ui.item.label);
            $("#txt_valor").val(ui.item.id);
            return false;
        },
        focus: function(event, ui){
            $("#txt_factura").val(ui.item.label);
            return false;
        },
    });

});




//   function cargar_tablas_sc2()
//     {
//        var tc = '<?php echo $tc; ?>';
//       var OpcDH = '<?php echo $OpcDH; ?>';
//       var OpcTM = '<?php echo $OpcTM; ?>';
//       var cta = '<?php echo $cta; ?>';
//       var val = $('#txt_total').val();
//       var fec = $('#txt_fecha_ven').val();
//        var parametros = 
//       {
//         'cta':cta,
//         'tc':tc,
//         'tm':OpcTM,
//         'dh':OpcDH,
//         'fec':fec,
//         'val':val,
//       }           
//       $.ajax({
//           data:  {parametros:parametros},
//           url:   '../controlador/contabilidad/incomC.php?tabs_sc_modal=true',
//           type:  'post',
//           dataType: 'json',
//             success:  function (response) {    
//             $('#subcuentas').html(response);      
//           }
//         });

//     }

  function mostrarCuenta(valor){
    $('#Cuenta_C').html(valor);
  }

  function cargar_tablas_sc()
  {
    if($.fn.dataTable.isDataTable('#subcuentas')){
      $('#subcuentas').DataTable().clear().destroy();
    }
      var tc = '<?php echo $tc; ?>';
      var OpcDH = '<?php echo $OpcDH; ?>';
      var OpcTM = '<?php echo $OpcTM; ?>';
      var cta = '<?php echo $cta; ?>';
      var val = $('#txt_total').val();
      var fec = $('#txt_fecha_ven').val();
       var parametros = 
      {
        'cta':cta,
        'tc':tc,
        'tm':OpcTM,
        'dh':OpcDH,
        'fec':fec,
        'val':val,
      }           

   $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/contabilidad/incomC.php?tabs_sc_modal=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
            if (response!='') 
            {
              $('#subcuentas').DataTable({
                searching: false,
                paging: false,   
                info: false,     
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                columnDefs: [
                    { targets: 2, width: "300px" }, // Ajusta el ancho de la columna "Detalle"
                    {
                      targets: [7,8],
                      render: function(data, type, row) {
                          return parseFloat(data).toFixed(2)
                      }
                   },
                  //  {
                  //     targets: 4,
                  //     render: function(data, type, row) {
                  //         return parseFloat(data).toFixed(2)
                  //     }
                  // }
                ],
                data: response,
                scrollY: '250px',
                scrollX: true, 
                scrollCollapse: true,
                columns: [
                  {data:null, 
                    render: function(data, type, row){
                      return `<button type="button" class="btn btn-sm btn-danger" onclick="Eliminar_Gasto('${row.ID}','${row.Codigo}')" title="Eliminar linea retencion">
                      <i class="fa fa-trash me-0"></i>
                      </button>`;
                    },
                    orderable: false,
                    className: 'text-center'
                  },
                  {data: 'Codigo'},
                  {data: 'Beneficiario'},
                  {data: 'Serie',className: 'text-end'},
                  {data: 'Factura'}, 
                  {data: 'Prima',className: 'text-end'},
                  {data: 'DH'}, 
                  {data: 'Valor'},
                  {data: 'Valor_ME'},
                  {data: 'Detalle_SubCta'}, 
                  {data:  null,
                    render: function(data, type, row){
                      if(row.FECHA_V!=null)
                      {
                        return formatoDate(row.FECHA_V.date);
                      }
                      return '';
                    }
                  },
                  {data:  null,
                    render: function(data, type, row){
                      if(row.FECHA_E!=null)
                      {
                        return formatoDate(row.FECHA_E.date);
                      }
                      return '';
                    }
                  },
                  {data: 'TC'}, 
                  {data: 'Cta'},
                  {data: 'TM'},
                  {data: 'T_No'},
                  {data: 'SC_No'}, 
                  {data:  null,
                    render: function(data, type, row){
                      if(row.Fecha_D!=null)
                      {
                        return formatoDate(row.Fecha_D.date);  
                      }                   
                      return ''; 
                    }
                  },
                  {data:  null,
                    render: function(data, type, row){
                      if(row.Fecha_H!=null)
                      {
                        return formatoDate(row.Fecha_H.date);
                      }
                      return '';
                    }
                  },
                  {data: 'Bloquear'},
                  {data: 'Item'}, 
                  {data: 'CodigoU'},
                  {data: 'ID'},
                  ]
              });
              // $('#txt_total_retencion').val(response.total);          
            }
         
      }
    });
  }

  function carga_ddl()
  {
      var tc = '<?php echo $tc; ?>';
      var OpcDH = '<?php echo $OpcDH; ?>';
      var OpcTM = '<?php echo $OpcTM; ?>';
      var cta = '<?php echo $cta; ?>';
      $('#ddl_subcta').select2({
        placeholder: 'Seleccione cuenta efectivo',
        width: 'resolve',
        selectionCssClass: 'form-control form-control-sm h-100',
        ajax: {
          url:   '../controlador/contabilidad/incomC.php?modal_subcta_catalogo=true&tc='+tc+'&OpcDH='+OpcDH+'&OpcTM='+OpcTM+'&cta='+cta,
          dataType: 'json',
          // data:  {parametros:parametros},
          // type:  'post',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
    }


  function datos_carga_ddl()
  {
      var tc = '<?php echo $tc; ?>';
      var OpcDH = '<?php echo $OpcDH; ?>';
      var OpcTM = '<?php echo $OpcTM; ?>';
      var cta = '<?php echo $cta; ?>';

      $.ajax({
          // data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?modal_subcta_catalogo=true&tc='+tc+'&OpcDH='+OpcDH+'&OpcTM='+OpcTM+'&cta='+cta,
          type:  'post',
          dataType: 'json',
            success:  function (response){
              if(response.length==0)
              {
                Swal.fire("No existen datos asignados para procesar","","info").then(function(){
                  cerrarModal();
                })
              }
            
            console.log(response);
          }
        });
  }

 
  function agregar_sc()
    { var tc = '<?php echo $tc; ?>';
      var OpcDH = '<?php echo $OpcDH; ?>';
      var OpcTM = '<?php echo $OpcTM; ?>';
      var cta = '<?php echo $cta; ?>';
      var tipoc = '<?php echo $tipoc; ?>';
      var ben = $('#ddl_subcta  option:selected').text();
      var codigo = $('#ddl_subcta').val();
      var aux = $('#ddl_aux').val();
      var val = $('#txt_valor').val();
      var fac = $('#txt_factura').val();
      var mes = $('#txt_mes').val();
      var fec = $('#txt_fecha_ven').val();
      var seri = $('#txt_serie').val();
      if(tc=='G')
      {
         if($('#DLSubCta').val()==''){
          Swal.fire('Seleccione una subcuenta','','info');
          return false;
         }else{
          codigo = $('#DLSubCta').val();
          ben = $('#DLSubCta  option:selected').text();
         }
      }
      if(aux=='')
      {
        aux = '.';
      }
      if(val==0 || codigo =='')
      {
        Swal.fire('Sub cuenta no seleccionada o valor pendiente','','info')
        return false;
      }
      
      var parametros = 
      {
        'cta':cta,
        'tc':tc,
        'tm':OpcTM,
        'dh':OpcDH,
        'ben':ben,
        'codigo':codigo,
        'aux':aux,
        'fec':fec,
        'val':val,
        'tipoc':tipoc,
        'fac':fac,
        'mes':mes,
        'serie':seri,
      }
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?modal_generar_sc=true',
          type:  'post',
          dataType: 'json',
            success:  function (response){
            if(response.resp==1)
            {    
              cargar_tablas_sc();
            }  
          }
        });
    }

    function generar_asiento()
    {
      var tc = '<?php echo $tc; ?>';
      var OpcDH = '<?php echo $OpcDH; ?>';
      var OpcTM = '<?php echo $OpcTM; ?>';
      var cta = '<?php echo $cta; ?>';
      var val = $('#txt_total').val();
      var fec = $('#txt_fecha_ven').val();
       var parametros = 
      {
        'cta':cta,
        'tc':tc,
        'tm':OpcTM,
        'dh':OpcDH,
        'fec':fec,
        'val':val,
      }      
      $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?modal_ingresar_asiento=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
              if(response==1)
              {
                // Swal.fire('Registrado','','success');                
                 window.parent.postMessage('closeModalSubCta', '*');
                // parent.location.reload();
                $('#iframe').css('display','none');
              }
          }
        });

    }


    function limpiar_asiento_sc()
    {
      var tc = '<?php echo $tc; ?>';
      var OpcDH = '<?php echo $OpcDH; ?>';
      var OpcTM = '<?php echo $OpcTM; ?>';
      var cta = '<?php echo $cta; ?>';
      var val = $('#txt_total').val();
      var fec = $('#txt_fecha_ven').val();
       var parametros = 
      {
        'cta':cta,
        'tc':tc,
        'tm':OpcTM,
        'dh':OpcDH,
        'fec':fec,
        'val':val,
      }      
      $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?modal_limpiar_asiento=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
              if(response==1)
              {
                Swal.fire('Registrado','','success');
                $('#iframe').css('display','none');
              }
          }
        });

    }

    function titulos(tc)
    {
      switch(tc) {
        case 'C':
           $('#titulo').text("SUBCUENTAS POR COBRAR");
          break;
        case 'P':
           $('#titulo').text("SUBCUENTAS POR PAGAR");
          break;
          case 'G':
           $('#titulo').text("SUBCUENTAS DE GASTOS");
           // $('#DLSubCta').css('display','block');
           $('#Pnl_DLSubCta').css('display','initial');
          break;
          case 'I':
           $('#titulo').text("SUBCUENTAS DE INGRESO");
          break;
          case 'CP':
           $('#titulo').text("SUBCUENTAS POR COBRAR PRESTAMOS");
          break;
          case 'PM':
           $('#titulo').text("SUBCUENTAS DE PRIMAS");
          break;
    }
  }

  function cargar_submodulos(nivel=false)
  {
      var tc = '<?php echo $tc; ?>';
      $('#DLSubCta').select2({
        placeholder: 'Seleccione cuenta',
        width:'resolve',
        ajax: {
          url:   '../controlador/contabilidad/incomC.php?modal_subcta_cta=true&nivel='+nivel+'&tc='+tc,
          dataType: 'json',
          // data:  {parametros:parametros},
          // type:  'post',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
  }


  function lista_clientes()
  {
      $('#ddl_cliente_lista').select2({
        placeholder: 'Seleccione cliente',
        width:'100%',        
        dropdownParent: $('#modal_clientes_lista'),
        ajax: {
          url:   '../controlador/contabilidad/incomC.php?lista_clientes=true',
          dataType: 'json',
          // data:  {parametros:parametros},
          // type:  'post',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
  }

  // function Eliminar_Gasto(id,codigo)
  // {
  //   var parametros = 
  //   {
  //     'tabla':'asientoSC',
  //     'Codigo':codigo,
  //     'ID':id,
  //   }

  //   Swal.fire({
  //     title: 'Esta seguro de eliminar este registro',
  //     text: "",
  //     type: 'info',
  //     showCancelButton: true,
  //     confirmButtonColor: '#3085d6',
  //     cancelButtonColor: '#d33',
  //     confirmButtonText: 'OK!'
  //   }).then((result) => {
  //     if (result.value==true) {
  //       $.ajax({
  //         data:  {parametros:parametros},
  //         url:   '../controlador/contabilidad/incomC.php?eliminarregistro=true',
  //         type:  'post',
  //         dataType: 'json',
  //           success:  function (response) { 
  //             if(response==1)
  //             {
                 
  //             cargar_tablas_sc();
  //             }

  //         }
  //       });                    
  //     }
  //   });

  // }

  function validar_serie()
  {
    var serie = $("#txt_serie").val();
    if(serie == '' || serie =='0' || serie=='.')
    {
      $("#txt_serie").val("001001")
    }

  }

  function Insertar_CxP()
  {
     var tc = '<?php echo $tc; ?>';
     var cta = '<?php echo $cta; ?>';
     
       var parametros = 
      {
        'SubCtaGen':cta,
        'SubCta':tc,
        'CodigoCliente': $('#ddl_cliente_lista').val(),       
      }      
      $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?Insertar_CxP=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
              if(response==1)
              {
                Swal.fire('Registrado','','success').then(function(){
                    $('#modal_clientes_lista').modal('hide');
                });
              }
          }
        });

  }

  function show_panel_cliente() {
    if($('#rbl_pnl_nuevo_cli').prop('checked'))
    {
      $('#pnl_nuevo_cliente').removeClass('d-none');
      $('#pnl_guardar_proceso').addClass('d-none');
      nacionalidades();
    }else
    {      
      $('#pnl_nuevo_cliente').addClass('d-none');
      $('#pnl_guardar_proceso').removeClass('d-none');
    }
  }

  function validar_ci()
  {
    var ci_ruc = $('#txt_ci_ruc').val();
    if(ci_ruc.length==10){$('#lbl_tipo_cliente').text('APELLIDOS Y NOMBRES')}else{$('#lbl_tipo_cliente').text('RAZON SOCIAL')}
    if(ci_ruc!='')
    {
       var parametros = 
        {
          'CI_RUC':ci_ruc,
          'TxtApellidosS':$('#TxtApellidosS').val(),
          'TxtEmail1':$('#TxtEmail1').val(),
          'TxtEmail2':$('#TxtEmail2').val(),
          'ddl_Nacion':$('#ddl_Nacion').val(),
          'ddl_Provincia':$('#ddl_Provincia').val(),
          'ddl_CiudadS':$('#ddl_CiudadS').val(),
        }      
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?TxtCI_RUC_LostFocus=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
              if(response.resp==-1)
              {
                Swal.fire('Registrado',response.msj,'info').then(function(){
                    $('#pnl_nuevo_cliente').addClass('d-none');
                    $('#pnl_guardar_proceso').removeClass('d-none');
                    $('#rbl_pnl_nuevo_cli').prop('checked',false);
                });
              }
          }
        });
    }
  }

  function boton_guardarCliente()
  {
    var ci_ruc = $('#txt_ci_ruc').val();
    var apellidos = $('#TxtApellidosS').val();
    var tc = '<?php echo $tc; ?>';
    var cta = '<?php echo $cta; ?>';
    

    if(ci_ruc!='' && apellidos)
    {
       var parametros = 
        {
          'CI_RUC':ci_ruc,
          'TxtApellidosS':$('#TxtApellidosS').val(),
          'TxtEmail1':$('#TxtEmail1').val(),
          'TxtEmail2':$('#TxtEmail2').val(),
          'ddl_Nacion':$('#ddl_Nacion').val(),
          'ddl_Provincia':$('#ddl_Provincia').val(),
          'ddl_CiudadS':$('#ddl_CiudadS').val(),
          'CiudadS':$('#ddl_CiudadS option:selected').text(),
          'SubCtaGen':cta,
          'SubCta':tc,
        }      
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?boton_guardarCliente=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
              if(response.resp==-1)
              {
                Swal.fire('Error',response.msj,'info').then(function(){
                    $('#pnl_nuevo_cliente').addClass('d-none');
                    $('#pnl_guardar_proceso').removeClass('d-none');
                    $('#rbl_pnl_nuevo_cli').prop('checked',false);
                });
              }else
              {
                Swal.fire("Guardado","Registro guardado","success").then(function()
                {
                   $('#pnl_nuevo_cliente').addClass('d-none');
                   $('#pnl_guardar_proceso').removeClass('d-none');
                })
              }
          }
        });
    }else
    {
      Swal.fire("Error","No se puede grabar, La C.I./R.U.C. deben tener valores","error");
    }
  }

  function nacionalidades()
  {
    $.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/contabilidad/incomC.php?nacionalidad=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          var op = '<option value="">Seleccione nacionalidad</option>';
          response.forEach(function(item,i){
            op+='<option value="'+item.Codigo+'">'+item.Descripcion_Rubro+'</option>';
          })
          $('#ddl_Nacion').html(op);

          $('#ddl_Nacion').val('593');
          provincias();
      }
    });
  }

  function provincias()
  {
    var parametros = 
    {
      'nacion':$('#ddl_Nacion').val()
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/contabilidad/incomC.php?provincias=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          var op = '<option value="">Seleccione nacionalidad</option>';
          response.forEach(function(item,i){
            op+='<option value="'+item.Codigo+'">'+item.Descripcion_Rubro+'</option>';
          })
          $('#ddl_Provincia').html(op);

          $('#ddl_Provincia').val('01');
          ciudad();
          console.log(response);
      }
    });
  }

  function ciudad()
  {
    var parametros = 
    {
      'provincia':$('#ddl_Provincia').val()
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/contabilidad/incomC.php?todas_ciudad=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          var op = '<option value="">Seleccione nacionalidad</option>';
          response.forEach(function(item,i){
            op+='<option value="'+item.Codigo+'">'+item.Descripcion_Rubro+'</option>';
          })
          $('#ddl_CiudadS').html(op);

          // $('#ddl_CiudadS').val('');
          console.log(response);
      }
    });
  }

  function cancelar_cliente()
  {
    $('#txt_ci_ruc').val("");
    $('#TxtApellidosS').val("")
    $('#TxtEmail1').val("")
    $('#TxtEmail2').val("")
    $('#pnl_nuevo_cliente').addClass('d-none');
    $('#pnl_guardar_proceso').removeClass('d-none');
  }
</script>

<div class="card">
    <div class="card-body">
       <div class="row">
          <div class="col-sm-4">
            <b id="titulo" class="">Sub cuenta por cobrar</b>
            <div class="input-group">
              <select class="form-select" id="ddl_subcta" >
                <option value="">Seleccione una sub cuenta</option>
              </select> 
              <button type="button" class="btn btn-sm btn-primary" onclick="Modal_addCliente()"><i class="bx bx-user"></i></button>
            </div>
          </div>
          <div class="col-sm-2">
            <label class="btn btn-outline-secondary btn-lg" title="Nuevo Beneficiario">
              <input type="checkbox" name="rbl_pnl_nuevo_cli" id="rbl_pnl_nuevo_cli" onchange="show_panel_cliente()"><i class="bx bx-user-plus"></i>
            </label>
            <!-- <button class="btn btn-outline-secondary btn-lg" title="Nuevo Beneficiario"></button> -->
          </div>
          <div class="col-sm-6">
            <b class="text-danger" id="Cuenta_C">Cuenta seleccionada</b>
          </div>
          <div class="col-sm-2 offset-sm-4">
            <b>Fecha Venc</b>
            <input type="date" name="txt_fecha_ven" id="txt_fecha_ven" class="form-control form-control-sm" value="<?php echo date('Y-m-d');?>">
          </div>
          <div class="col-sm-1" style=" padding: 0px;">
            <b>Serie</b>
            <input type="input" name="txt_serie" id="txt_serie" class="form-control form-control-sm" value="" placeholder="001001" onblur="validar_serie()">
          </div>
          <div class="col-sm-2">
            <b>Factura No</b>
            <input type="input" style="z-index: auto;" name="txt_factura" id="txt_factura" class="form-control form-control-sm" onkeyup="solo_numeros(this)" value="0">
          </div>
          <div class="col-sm-1">
            <b>Meses</b>
            <input type="text" name="txt_mes" id="txt_mes" class="form-control form-control-sm" value="0" onkeyup="solo_numeros(this)">
          </div>
          <div class="col-sm-2">
            <b>Valor M/N</b>
            <input type="text" name="txt_valor" id="txt_valor" class="form-control form-control-sm" value="0" onblur="agregar_sc()" onkeyup="validar_numeros_decimal(this)" onblur="validar_float(this,2)">
          </div>
        </div>
          <div class="row">
            <div class="col-sm-4">
              <div  id="Pnl_DLSubCta" style="display: none;" >
                <select class="form-control input-sm" id="DLSubCta" style="width: 100%;" >
                  <option value="">Seleccione una sub cuenta</option>
                </select>                 
              </div>
                 
              
            </div>
            <div class="col-sm-8">
              <b>DETALLE AUXILIAR DE SUB MODULO</b>
              <input type="text" class="form-control"  id="ddl_aux"  name="ddl_aux" style="z-index: auto;">
              <!-- <select class="form-control input-sm" id="ddl_aux">
                <option value="">Seleccione detalle auxiliar de sub modulo</option>
              </select> -->
            </div>
        </div>
         <div class="row mb-2" style="overflow-x: scroll;">
          <!-- <div class="col-sm-12" id="subcuentass"></div> -->
          <!-- <div class="" > -->
              <table class="table table-sm" id="subcuentas">
                <thead>
                  <tr>
                    <th class="text-center"></th>
                    <th class="text-center">Codigo</th>  
                    <th class="text-center">Beneficiario</th>
                    <th class="text-center">Serie</th>
                    <th class="text-center">Factura</th>
                    <th class="text-center">Prima</th>
                    <th class="text-center">DH</th>
                    <th class="text-center">Valor</th>
                    <th class="text-center">Valor_ME</th>
                    <th class="text-center">Detalle_SubCta</th>
                    <th class="text-center">FECHA_V</th>
                    <th class="text-center">FECHA_E</th>
                    <th class="text-center">TC</th>
                    <th class="text-center">Cta</th>
                    <th class="text-center">TM</th>
                    <th class="text-center">T_No</th>
                    <th class="text-center">SC_No</th>
                    <th class="text-center">Fecha_D</th>
                    <th class="text-center">Fecha_H</th>
                    <th class="text-center">Bloquear</th>
                    <th class="text-center">Item</th>
                    <th class="text-center">CodigoU</th>
                    <th class="text-center">ID</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            <!-- </div> -->
        </div>
        <div class="row mb-2 d-none" id="pnl_nuevo_cliente">
          <div class="col-sm-10">
            <div class="row">
              <div class="col-sm-4">
                <b>C.I / R.U.C</b>
                <input type="" name="txt_ci_ruc" id="txt_ci_ruc" onblur="validar_ci()" class="form-control form-control-sm">
              </div>   
              <div class="col-sm-8">
                <b id="lbl_tipo_cliente">APELLIDOS Y NOMBRES</b>
                <input type="" name="TxtApellidosS" id="TxtApellidosS" class="form-control form-control-sm">
              </div>   
              <div class="col-sm-6">
                <b>CORREOS ELECTRONICOS</b>
                <input type="" name="TxtEmail1" id="TxtEmail1" class="form-control form-control-sm">
              </div>   
              <div class="col-sm-6">
                <br>
                <input type="" name="TxtEmail2" id="TxtEmail2" class="form-control form-control-sm">
              </div>   
              <div class="col-sm-3">
                <b>NACIONALIDAD</b>
                <select class="form-select form-select-sm" id="ddl_Nacion" name="ddl_Nacion">
                  <option>Seleciones nacionalidad</option>
                </select>
              </div>   
              <div class="col-sm-3">
                <b>PROVINCIA</b>
                <select class="form-select form-select-sm" id="ddl_Provincia" name="ddl_Provincia">
                  <option>Seleciones Provincia</option>
                </select>
              </div>   
              <div class="col-sm-6">
                <b>CIUDAD</b>
                <select class="form-select form-select-sm" id="ddl_CiudadS" name="ddl_CiudadS">
                  <option>Seleciones ciudad</option>
                </select>
              </div>   
            </div>
          </div>
          <div class="col-sm-2">
            <button type="button" class="btn w-80 btn-md btn-outline-secondary" onclick="boton_guardarCliente()">
              <img src="../../img/png/grabar.png">
              <br>
              Grabar
            </button>
            <br>
            <button type="button" class="btn w-80 btn-md btn-outline-secondary" onclick="cancelar_cliente()">
              <img src="../../img/png/cxc.png">
              <br>
              No Grabar
            </button>
          </div>
        </div>
        <div class="row mt-4" id="pnl_guardar_proceso">
             <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="generar_asiento();">Continuar</button>
                <button type="button" class="btn btn-outline-secondary" onclick="cerrarModal();">Salir</button>           
            </div>          
        </div>
    </div>
</div>


<div class="modal fade" id="modal_clientes_lista" tabindex="-1" role="dialog" data-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <h5 class="modal-title" id="titulo_frame"></h5> -->
      </div>
      <div class="modal-body">
       
        <select class="form-select form-select-sm" id="ddl_cliente_lista" name="ddl_cliente_lista">
          <option>Seleccione</option>
        </select>
        
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="Insertar_CxP();">Guardar</button>
          <button type="button" class="btn btn-default" data-bs-dismiss="modal" class="btn btn-default">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  function cerrarModal() {
       // window.parent.document.getElementById('modal_subcuentas').style.display = 'none';
       // window.parent.document.getElementById('modal_subcuentas').click();
   window.parent.postMessage("closeModal", "*");
    // $('#modal_subcuentas').hide();
  }

  // lo que hace visual al colocar el CTRl+s
  function Modal_addCliente()
  {
    lista_clientes();
    $('#modal_clientes_lista').modal('show');
  }
</script>