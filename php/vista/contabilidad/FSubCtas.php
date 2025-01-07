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
    titulos(tc);
//     cargar_tablas_sc();
    carga_ddl();

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

  function cargar_submodulos(nivel)
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
</script>

<div class="card">
    <div class="card-body">
       <div class="row">
          <div class="col-sm-4">
            <b id="titulo">Sub cuenta por cobrar</b>
            <select class="form-control input-sm" id="ddl_subcta" >
              <option value="">Seleccione una sub cuenta</option>
            </select> 
           
          </div>
          <div class="col-sm-2">
            <b>Fecha Venc</b>
            <input type="date" name="txt_fecha_ven" id="txt_fecha_ven" class="form-control input-sm" value="<?php echo date('Y-m-d');?>">
          </div>
        <div class="col-sm-1" style=" padding: 0px;">
            <b>Serie</b>
            <input type="input" name="txt_serie" id="txt_serie" class="form-control input-sm" value="" placeholder="001001">
          </div>
          <div class="col-sm-2">
            <b>Factura No</b>
            <input type="input" name="txt_factura" id="txt_factura" class="form-control form-control-sm" onkeyup="solo_numeros(this)" value="0">
          </div>
          <div class="col-sm-1">
            <b>Meses</b>
            <input type="text" name="txt_mes" id="txt_mes" class="form-control input-sm" value="0" onkeyup="solo_numeros(this)">
          </div>
          <div class="col-sm-2">
            <b>Valor M/N</b>
            <input type="text" name="txt_valor" id="txt_valor" class="form-control input-sm" value="0" onblur="agregar_sc()" onkeyup="validar_numeros_decimal(this)" onblur="validar_float(this,2)">
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
              <input type="text" class="form-control"  id="ddl_aux"  name="ddl_aux">
              <!-- <select class="form-control input-sm" id="ddl_aux">
                <option value="">Seleccione detalle auxiliar de sub modulo</option>
              </select> -->
            </div>
        </div>
         <div class="row" style="overflow-x: scroll;">
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
        <div class="row mt-4">
             <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="generar_asiento();">Continuar</button>
                <button type="button" class="btn btn-outline-secondary" onclick="cerrarModal();">Salir</button>           
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
</script>