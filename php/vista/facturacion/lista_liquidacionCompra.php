<?php  @session_start();  date_default_timezone_set('America/Guayaquil');  //print_r($_SESSION['INGRESO']);die();
    $cartera_usu ='';
    $cartera_pass = '';
    if(isset($_SESSION['INGRESO']['CARTERA_USUARIO']))
    {
     $cartera_usu = $_SESSION['INGRESO']['CARTERA_USUARIO'];
     $cartera_pass = $_SESSION['INGRESO']['CARTERA_PASS'];
    }
    $tipo = '';

    if(isset($_GET['tipo']) && $_GET['tipo']==2)
    {
      $tipo=2;
    }
?>
<script type="text/javascript">
	tipoGlobal = '<?php echo $tipo; ?>'
	cartera_usu =  '<?php echo $cartera_usu; ?>'
	cartera_pas = '<?php echo $cartera_pass; ?>'
</script>
<script type="text/javascript">
   $(document).ready(function() {

          tbl_facturas_all = $('#tbl_facturas').DataTable({
          // responsive: true,
          language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
          },
          ajax: {
              url:   '../controlador/facturacion/lista_liquidacionCompraC.php?tabla=true',
              type: 'POST',  // Cambia el método a POST    
              data: function(d) {
                  var parametros = {
                      ci: $('#ddl_cliente').val(),
                      per: $('#ddl_periodo').val(),
                      desde: $('#txt_desde').val(),
                      hasta: $('#txt_hasta').val(),
                      tipo: tipoGlobal,
                      serie: $('#DCLinea').val()
                  };
                  return { parametros: parametros };
              },
              dataSrc: '',             
          },
           scrollX: true,  // Habilitar desplazamiento horizontal
   
          columns: [
              { data: null,
                  render: function(data, type, item) {
                       email = '';
                       if (item.Email != '.' && item.Email != '') {
                          email+= item.Email+',';
                        }
                        if (item.EmailR != '.' && item.EmailR != '') {
                          email+= item.EmailR+',';
                        }
                        if (item.Email2 != '.' && item.Email2 != '') {
                          email+= item.Email2+ ',';
                        }
                    options=`<li>
                                <a href="#" class="dropdown-item" onclick="Ver_factura('${item.Factura}','${item.Serie}','${item.CodigoC}')"><i class="bx bx-show-alt"></i> Ver Liquidacion de Compra</a>
                            </li>
                            <li>
                              <a href="#" class="dropdown-item" onclick=" modal_email_fac('${item.Factura}','${item.Serie}','${item.CodigoC}','${email}')"><i class="bx bx-envelope"></i> Enviar Liquidacion de compra por email</a>
                            </li>
                            <li>
                                <a href="#" class="dropdown-item" onclick="descargar_fac('${item.Factura}','${item.Serie}','${item.CodigoC}')"><i class="bx bx-download"></i> Descargar Liquidacion de compra</a>
                            </li>`;
                    if (item.ExisteSerie =='Si'  && item.Autorizacion.length == 13 && TipoGlobal != '') 
                    {
                        options+=`<li>
                          <a href="#" class="dropdown-item" onclick="autorizar('${item.TC}','${item.Factura}','${item.Serie}','${formatoDate(item.Fecha.date)}')" ><i class="bx bx-paper-plane"></i>Autorizar</a>
                          </li>`;
                    }
                    if (item.T != 'A' && tipoGlobal != '') 
                    {
                          options+=`<li><a href="#" class="dropdown-item" onclick="anular_factura('${item.Factura}','${item.Serie}','${item.CodigoC}')"><i class="bx bx-x"></i>Anular Liquidacion de compra</a></li>`;
                    }
                    if (item.Autorizacion.length > 13) 
                    {
                        options+=`<li><a href="#" class="dropdown-item" onclick="descargar_xml('${item.Autorizacion}')">
                        <i class="bx bx-download"></i> Descargar XML</a></li>`;
                    }
                  

                    return `<div class="input-group-btn">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="true">Acciones
                                </button>
                                <ul class="dropdown-menu">`+options
                                  
                                options+=`</ul>
                            </div>`;                    
                  }
              },
              { data: 'T' },
              { data: 'Razon_Social' },
              { data: 'TC' },
              { data: 'Serie' },
              { data: 'Autorizacion' },
              { data: 'Factura' },
              { data: 'Fecha.date',  
                  render: function(data, type, item) {
                      return data ? new Date(data).toLocaleDateString() : '';
                  }
              },
              { data: 'SubTotal' },
              { data: 'Con_IVA' },
              { data: 'IVA' },
              { data: 'Descuentos' },
              { data: 'Total' },
              { data: 'Saldo' },
              { data: 'RUC_CI' },
              { data: 'TB' }
          ],
          order: [
              [1, 'asc']
          ]
      });


 tbl_facturas_autorizadas = $('#tbl_tablaAu').DataTable({
          // responsive: true,
          language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
          },
          ajax: {
              url:   '../controlador/facturacion/lista_liquidacionCompraC.php?tabla=true',
              type: 'POST',  // Cambia el método a POST    
              data: function(d) {
                  var parametros = {
                      ci: $('#ddl_cliente').val(),
                      per: $('#ddl_periodo').val(),
                      desde: $('#txt_desde').val(),
                      hasta: $('#txt_hasta').val(),
                      tipo: tipoGlobal,
                      serie: $('#DCLinea').val(),
                      auto:1
                  };
                  return { parametros: parametros };
              },
              dataSrc: '',             
          },
           scrollX: true,  // Habilitar desplazamiento horizontal
   
          columns: [
              {
                  data: null,
                   render: function(data, type, item) {
                       email = '';
                       if (item.Email != '.' && item.Email != '') {
                          email+= item.Email+',';
                        }
                        if (item.EmailR != '.' && item.EmailR != '') {
                          email+= item.EmailR+',';
                        }
                        if (item.Email2 != '.' && item.Email2 != '') {
                          email+= item.Email2+ ',';
                        }
                    options=`<li>
                                <a href="#" class="dropdown-item" onclick="Ver_factura('${item.Factura}','${item.Serie}','${item.CodigoC}')"><i class="bx bx-show-alt"></i> Ver Liquidacion de Compra</a>
                            </li>
                            <li>
                              <a href="#" class="dropdown-item" onclick=" modal_email_fac('${item.Factura}','${item.Serie}','${item.CodigoC}','${email}')"><i class="bx bx-envelope"></i> Enviar Liquidacion de compra por email</a>
                            </li>
                            <li>
                                <a href="#" class="dropdown-item" onclick="descargar_fac('${item.Factura}','${item.Serie}','${item.CodigoC}')"><i class="bx bx-download"></i> Descargar Liquidacion de compra</a>
                            </li>`;
                    if (item.ExisteSerie =='Si'  && item.Autorizacion.length == 13 && TipoGlobal != '') 
                    {
                        options+=`<li>
                          <a href="#" class="dropdown-item" onclick="autorizar('${item.TC}','${item.Factura}','${item.Serie}','${formatoDate(item.Fecha.date)}')" ><i class="bx bx-paper-plane"></i>Autorizar</a>
                          </li>`;
                    }
                    if (item.T != 'A' && tipoGlobal != '') 
                    {
                          options+=`<li><a href="#" class="dropdown-item" onclick="anular_factura('${item.Factura}','${item.Serie}','${item.CodigoC}')"><i class="bx bx-x"></i>Anular Liquidacion de compra</a></li>`;
                    }
                    if (item.Autorizacion.length > 13) 
                    {
                        options+=`<li><a href="#" class="dropdown-item" onclick="descargar_xml('${item.Autorizacion}')">
                        <i class="bx bx-download"></i> Descargar XML</a></li>`;
                    }
                  
                  

                    return `<div class="input-group-btn">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="true">Acciones
                                    <span class="fa fa-caret-down"></span>
                                </button>
                                <ul class="dropdown-menu">`+options
                                  
                                options+=`</ul>
                            </div>`;                    
                  }
              },
              { data: 'T' },
              { data: 'Razon_Social' },
              { data: 'TC' },
              { data: 'Serie' },
              { data: 'Autorizacion' },
              { data: 'Factura' },
              { data: 'Fecha.date',  
                  render: function(data, type, item) {
                      return data ? new Date(data).toLocaleDateString() : '';
                  }
              },
              { data: 'SubTotal' },
              { data: 'Con_IVA' },
              { data: 'IVA' },
              { data: 'Descuentos' },
              { data: 'Total' },
              { data: 'Saldo' },
              { data: 'RUC_CI' },
              { data: 'TB' }
          ],
          order: [
              [1, 'asc']
          ]
      });


tbl_facturas_Noautorizadas = $('#tbl_tablaNoAu').DataTable({
          // responsive: true,
          language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
          },
          ajax: {
              url:   '../controlador/facturacion/lista_liquidacionCompraC.php?tabla=true',
              type: 'POST',  // Cambia el método a POST    
              data: function(d) {
                  var parametros = {
                      ci: $('#ddl_cliente').val(),
                      per: $('#ddl_periodo').val(),
                      desde: $('#txt_desde').val(),
                      hasta: $('#txt_hasta').val(),
                      tipo: tipoGlobal,
                      serie: $('#DCLinea').val(),
                      auto:2
                  };
                  return { parametros: parametros };
              },
              dataSrc: '',             
          },
           scrollX: true,  // Habilitar desplazamiento horizontal
   
          columns: [
              {
                  data: null,
                   render: function(data, type, item) {
                       email = '';
                       if (item.Email != '.' && item.Email != '') {
                          email+= item.Email+',';
                        }
                        if (item.EmailR != '.' && item.EmailR != '') {
                          email+= item.EmailR+',';
                        }
                        if (item.Email2 != '.' && item.Email2 != '') {
                          email+= item.Email2+ ',';
                        }
                    options=`<li>
                                <a href="#" class="dropdown-item" onclick="Ver_factura('${item.Factura}','${item.Serie}','${item.CodigoC}')"><i class="bx bx-show-alt"></i> Ver Liquidacion de Compra</a>
                            </li>
                            <li>
                              <a href="#" class="dropdown-item" onclick=" modal_email_fac('${item.Factura}','${item.Serie}','${item.CodigoC}','${email}')"><i class="bx bx-envelope"></i> Enviar Liquidacion de compra por email</a>
                            </li>
                            <li>
                                <a href="#" class="dropdown-item" onclick="descargar_fac('${item.Factura}','${item.Serie}','${item.CodigoC}')"><i class="bx bx-download"></i> Descargar Liquidacion de compra</a>
                            </li>`;
                    if (item.ExisteSerie =='Si'  && item.Autorizacion.length == 13 && TipoGlobal != '') 
                    {
                        options+=`<li>
                          <a href="#" class="dropdown-item" onclick="autorizar('${item.TC}','${item.Factura}','${item.Serie}','${formatoDate(item.Fecha.date)}')" ><i class="bx bx-paper-plane"></i>Autorizar</a>
                          </li>`;
                    }
                    if (item.T != 'A' && tipoGlobal != '') 
                    {
                          options+=`<li><a href="#" class="dropdown-item" onclick="anular_factura('${item.Factura}','${item.Serie}','${item.CodigoC}')"><i class="bx bx-x"></i>Anular Liquidacion de compra</a></li>`;
                    }
                    if (item.Autorizacion.length > 13) 
                    {
                        options+=`<li><a href="#" class="dropdown-item" onclick="descargar_xml('${item.Autorizacion}')">
                        <i class="bx bx-download"></i> Descargar XML</a></li>`;
                    }
                  
                  

                    return `<div class="input-group-btn">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="true">Acciones
                                    <span class="fa fa-caret-down"></span>
                                </button>
                                <ul class="dropdown-menu">`+options
                                  
                                options+=`</ul>
                            </div>`;                    
                  }
              },
              { data: 'T' },
              { data: 'Razon_Social' },
              { data: 'TC' },
              { data: 'Serie' },
              { data: 'Autorizacion' },
              { data: 'Factura' },
              { data: 'Fecha.date',  
                  render: function(data, type, item) {
                      return data ? new Date(data).toLocaleDateString() : '';
                  }
              },
              { data: 'SubTotal' },
              { data: 'Con_IVA' },
              { data: 'IVA' },
              { data: 'Descuentos' },
              { data: 'Total' },
              { data: 'Saldo' },
              { data: 'RUC_CI' },
              { data: 'TB' }
          ],
          order: [
              [1, 'asc']
          ]
      });




  })

</script>
<script src="../../dist/js/lista_liquidacionCompra.js"></script>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
  <div class="breadcrumb-title pe-3"><?php echo $NombreModulo; ?></div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0"  id="ruta_menu">
          <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
          </li>
        </ol>
      </nav>
    </div>          
</div>
<div class="row">
  	<div class="col-lg-6 col-md-6 col-sm-12">
    	<div class="btn-group" role="group" aria-label="Basic example">
    		 <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-outline-secondary">
              <img src="../../img/png/salire.png">
            </a>
             <button type="button" class="btn btn-outline-secondary" title="Generar pdf" onclick="reporte_pdf()"><img src="../../img/png/pdf.png"></button>
              <button type="button" class="btn btn-outline-secondary" title="Generar pdf" onclick="generar_excel()"><img src="../../img/png/table_excel.png"></button>
    	</div>
	</div>
</div>

  <form id="filtros" class="row">
    <div class="col-lg-2 col-md-2 col-sm-12">
       <b>GRUPO</b>
        <select class="form-select form-select-sm" id="ddl_grupo" name="ddl_grupo" onchange="autocmpletar_cliente()">
          <option value=".">TODOS</option>
        </select>      
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
        <b>CI / RUC</b>
        <select class="form-select form-select-sm" id="ddl_cliente" name="ddl_cliente" onchange="periodos(this.value);rangos();">
          <option value="">Seleccione Cliente</option>
        </select>     
    </div>
    <div class="col-lg-2 col-md-2 col-sm-12">
        <b>Serie</b>
        <select class="form-select form-select-sm" name="DCLinea" id="DCLinea" tabindex="1" style="padding-left:8px">
          <option value=""></option>
        </select>     
    </div>
    <div class="col-lg-2 col-md-2 col-sm-12" id="campo_clave">
      <b>CLAVE</b>
      <input type="password" name="txt_clave" id="txt_clave" class="form-control form-control-sm">
      <a href="#" onclick="recuperar_clave()"><i class="fa fa-key"></i> Recupera clave</a>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-12" style="display:none;" >
      <b>Periodo</b>
      <select class="form-select form-select-sm" id="ddl_periodo" name="ddl_periodo" onchange="rangos()">
        <option value=".">Seleccione perido</option>
      </select>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-12">
      <b>Desde</b>
      <input type="date" name="txt_desde" id="txt_desde" class="form-control form-control-sm" value="<?php echo date('Y-m-d')?>">
    </div>  
    <div class="col-lg-2 col-md-2 col-sm-12">
      <b>Hasta</b>
        <input type="date" name="txt_hasta" id="txt_hasta" class="form-control form-control-sm" value="<?php echo date('Y-m-d')?>">
    </div> 
    <div class="col-lg-12 col-md-2 col-sm-6 text-end mt-1">
      <button class="btn btn-primary btn-sm" type="button" onclick="validar()"><i class="bx bx-search"></i> Buscar</button>
    </div>    
  </form>

<div class="panel" id="panel_datos" style="display:none;margin-bottom: 1px;">
  <div class="row">
    <div class="col-sm-4">
      <b>Cliente: </b><i id="lbl_cliente"></i>
    </div>
     <div class="col-sm-3">
      <b>CI / RUC: </b><i id="lbl_ci_ruc"></i>
    </div>
     <div class="col-sm-3">
      <b>Telefono: </b><i id="lbl_tel"></i>
    </div>
     <div class="col-sm-4">
      <b>Email: </b><i id="lbl_ema"></i>
    </div>
     <div class="col-sm-8">
      <b>Direccion: </b><i id="lbl_dir"></i>
    </div>
  </div>      
</div>
 <div class="row mt-2">
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-pills mb-3" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link active" data-bs-toggle="pill" href="#primary-pills-home" role="tab" aria-selected="true">
              <div class="d-flex align-items-center">
                <div class="tab-icon"><i class="bx bx-list-ul font-18 me-1"></i>
                </div>
                <div class="tab-title">Todos</div>
              </div>
            </a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="pill" href="#primary-pills-profile" role="tab" aria-selected="false" tabindex="-1">
              <div class="d-flex align-items-center">
                <div class="tab-icon"><i class="bx bx-check font-18 me-1"></i>
                </div>
                <div class="tab-title">Autorizados</div>
              </div>
            </a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="pill" href="#primary-pills-contact" role="tab" aria-selected="false" tabindex="-1">
              <div class="d-flex align-items-center">
                <div class="tab-icon"><i class="bx bx-x font-18 me-1"></i>
                </div>
                <div class="tab-title">No autorizados</div>
              </div>
            </a>
          </li>
          
        </ul>
        <div class="tab-content" id="pills-tabContent">
          <div class="tab-pane fade active show" id="primary-pills-home" role="tabpanel">
            <div class="row">
               <div  class="col-sm-12">    
                  <table class="table text-sm" style=" white-space: nowrap;" id="tbl_facturas">
                    <thead>
                      <th></th>
                      <th>T</th>          
                      <th>Razon_Social</th>
                      <th>TC</th>
                      <th>Serie</th>
                      <th>Autorizacion</th>
                      <th>Factura</th>
                      <th>Fecha</th>
                      <th>SubTotal</th>
                      <th>Con_IVA</th>
                      <th>IVA</th>
                      <th>Descuento</th>
                      <th>Total</th>
                      <th>Saldo</th>
                      <th>RUC_CI</th>
                      <th>TB</th>
                    </thead>
                    <tbody >
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>              
                </div>                  
            </div>
          </div>
          <div class="tab-pane fade" id="primary-pills-profile" role="tabpanel">
            <div class="row">
              <div  class="col-sm-12">    
                  <table class="table text-sm" style=" white-space: nowrap;" id="tbl_tablaAu">
                    <thead>
                      <th></th>
                      <th>T</th>          
                      <th>Razon_Social</th>
                      <th>TC</th>
                      <th>Serie</th>
                      <th>Autorizacion</th>
                      <th>Factura</th>
                      <th>Fecha</th>
                      <th>SubTotal</th>
                      <th>Con_IVA</th>
                      <th>IVA</th>
                      <th>Descuento</th>
                      <th>Total</th>
                      <th>Saldo</th>
                      <th>RUC_CI</th>
                      <th>TB</th>
                    </thead>
                    <tbody>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
              
                </div>    
              
            </div>
          </div>
           <div class="tab-pane fade" id="primary-pills-contact" role="tabpanel">
               <div class="row">
                <div class="col-sm-12 text-end">
                  <div class="input-group margin">
                      <div class="input-group-btn open">
                          <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="true">Acciones
                              <span class="fa fa-caret-down"></span>
                          </button>
                          <ul class="dropdown-menu">
                            <li onclick="autorizar_blo()"><a href="#" class="dropdown-item">Autorizar en bloque</a></li>
                            <li onclick=""><a href="#" class="dropdown-item">Anular en bloque</a></li>
                            <!-- <li><a href="#">Something else here</a></li> -->
                          </ul>
                      </div>
                  </div>
                </div>
              </div>
              <div class="row">           
                <div  class="col-sm-12">    
                  <table class="table text-sm" style=" white-space: nowrap;"   id="tbl_tablaNoAu">
                    <thead>
                      <th></th>
                      <th>T</th>          
                      <th>Razon_Social</th>
                      <th>TC</th>
                      <th>Serie</th>
                      <th>Autorizacion</th>
                      <th>Factura</th>
                      <th>Fecha</th>
                      <th>SubTotal</th>
                      <th>Con_IVA</th>
                      <th>IVA</th>
                      <th>Descuento</th>
                      <th>Total</th>
                      <th>Saldo</th>
                      <th>RUC_CI</th>
                      <th>TB</th>
                    </thead>
                    <tbody>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
              
                </div>    
              </div>
              
            </div>
           </div>           
          </div>
        </div>
      </div>
    </div>  
</div>

<div id="modal_email" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content modal-sm">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Recuperar Clave</h4>
      </div>
      <div class="modal-body">
        <div class="col-sm-12">
          <p>Su nueva clave se enviara al correo:</p>
          <h5 id="lbl_email">El usuario no tien un Email registrado contacte con la institucion</h5>
          <input type="hidden" name="txt_email" id="txt_email">
          <!-- <form enctype="multipart/form-data" id="form_img" method="post"> -->
           
          <!-- </form>   -->
          <br> 
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-sm btn-block"  id="btn_email" onclick="enviar_mail()"> Enviar Email</button>
        <button type="button" class="btn btn-default btn-sm btn-block"   data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal_email" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enviar email</h5>
            </div>
            <div class="modal-body">
                <div class="row"> 
                    <div class="col-sm-12">
                        <div id="emails-input" name="emails-input" placeholder="añadir email"></div>
                        <input type="hidden" name="txt_fac" id="txt_fac">
                        <input type="hidden" name="txt_serie" id="txt_serie">
                        <input type="hidden" name="txt_codigoc" id="txt_codigoc">
                        <input type="hidden" name="txt_to" id="txt_to">
                    </div>
                    <div class="col-sm-12">
                      <input type="" id="txt_titulo" name="txt_titulo" class="form-control form-control-sm" placeholder="titulo de correo" value="comprobantes">
                    </div>
                    <div class="col-sm-12">
                        <textarea class="form-control" rows="3" style="resize:none" placeholder="Texto" id="txt_texto" name="txt_texto"></textarea>
                    </div>                                                  
                    <div class="col-sm-3">
                        <label><input type="checkbox" name="cbx_factura" id="cbx_factura" checked>Enviar Factura</label>
                    </div>  
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="enviar_email()" >Enviar</button>
            </div>
        </div>
  </div>
</div>



<div class="modal fade" id="myModal_bloque" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Respuesta autorizacion en bloque</h5>
            </div>
            <div class="modal-body">
                <div class="row" > 
                  <div class="col-sm-12" id="bloque_resp" style="height:350px; overflow-y: scroll;">
                    
                  </div>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
  </div>
</div>






  <script src="../../dist/js/utils.js"></script>
  <script src="../../dist/js/emails-input.js"></script>
  <script src="../../dist/js/multiple_email.js"></script>