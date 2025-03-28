<?php  @session_start();  date_default_timezone_set('America/Guayaquil');  //print_r($_SESSION['INGRESO']);die();
    $cartera_usu ='';
    $cartera_pass = '';
    $periodo = $_SESSION['INGRESO']['periodo'];;

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

<script src="../../dist/js/lista_ndo_ndu.js"></script>
  <!--<div class="row">
    <div class="col-lg-4 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <a  href="<?php //$ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="reporte_pdf()"><img src="../../img/png/pdf.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <button type="button" class="btn btn-default" title="Generar excel" onclick="generar_excel()"><img src="../../img/png/table_excel.png"></button>
        </div>
    </div>
  </div>-->
  <input type="hidden" id="hidden_cartera_usu" value="<?php echo $cartera_usu; ?>">
  <input type="hidden" id="hidden_cartera_pass" value="<?php echo $cartera_pass; ?>">
  <input type="hidden" id="hidden_tipo" value="<?php echo $tipo; ?>">
  <input type="hidden" id="hidden_periodo" value="<?php echo $periodo; ?>">

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
  <div class="row row-cols-auto gx-3 pb-2 d-flex align-items-center ps-2">
    <div class="row row-cols-auto btn-group">
      <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
					print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
        <img src="../../img/png/salire.png">
      </a>
      <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Generar pdf" onclick="reporte_pdf()">
        <img src="../../img/png/pdf.png">
      </button>
      <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Generar excel" onclick="generar_excel()">
        <img src="../../img/png/table_excel.png">
      </button>
    </div>
  </div>
  <form id="filtros" class="row">
    <div class="col-lg-2 col-md-2 col-sm-12">
      <b>GRUPO</b>
        <select class="form-select form-select-sm" id="ddl_grupo" name="ddl_grupo" onchange="autocmpletar_cliente()">
          <option value=".">TODOS</option>
        </select>      
    </div>
    <iframe id="pdfFrame" style="display:none;"></iframe>
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
          <!--<b>GRUPO</b>
          <select class="form-control input-xs" id="ddl_grupo" name="ddl_grupo" onchange="autocmpletar_cliente()">
            <option value=".">TODOS</option>
          </select>-->
          <!-- <input type="text" name="txt_grupo" id="txt_grupo" class="form-control input-sm"> -->
        <!--</div>-->
        <!--<div class="col-sm-5">
          <b>CI / RUC</b>
          <select class="form-control input-xs" id="ddl_cliente" name="ddl_cliente" onchange="periodos(this.value);rangos();">
            <option value="">Seleccione Cliente</option>
          </select>
        </div>
        <div class="col-sm-1" style="padding: 0px;">
          <b>Serie</b>
            <select class="form-control input-xs" name="DCLinea" id="DCLinea" tabindex="1" style="padding-left:8px" onchange="autocmpletar_cliente_tipo2()">
              <option value=""></option>
            </select>
        </div>
        <div class="col-sm-2" id="campo_clave">
          <b>CLAVE</b>
          <input type="password" name="txt_clave" id="txt_clave" class="form-control input-xs">
          <a href="#" onclick="recuperar_clave()"><i class="fa fa-key"></i> Recupera clave</a>
        </div>
        <div class="col-sm-2" style="display:none;" >
          <b>Periodo</b>
          <select class="form-control input-xs" id="ddl_periodo" name="ddl_periodo" onchange="rangos()">
            <option value=".">Seleccione perido</option>
          </select>
        </div>
        <div class="col-sm-2">
          <b>Desde</b>
            <input type="date" name="txt_desde" id="txt_desde" class="form-control input-xs" value="<?php //echo date('Y-m-d')?>">
        </div>  
        <div class="col-sm-2">
          <b>Hasta</b>
            <input type="date" name="txt_hasta" id="txt_hasta" class="form-control input-xs" value="<?php //echo date('Y-m-d')?>">
        </div>      -->                   
      
      <!--<div class="row">            
        <div class="col-sm-6 text-right">
        </div>       
        <div class="col-sm-6 text-right">
          <button class="btn btn-primary btn-xs" type="button" onclick="validar()"><i class="fa fa-search"></i> Buscar</button>
        </div>
        
      </div>-->
      
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
  <div class="row">
    <div class="col-sm-12">
      <div class="nav-tabs-custom" style="padding:25px 20px">
        <!--<ul class="nav nav-tabs">
          <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Todos</a></li>
          <li class="" id="tab_2_"><a href="#tab_2" data-toggle="tab" aria-expanded="false">Autorizados</a></li>
          <li class="" id="tab_3_"><a href="#tab_3" data-toggle="tab" aria-expanded="false">No Autorizados</a></li>
          <li class="" id="tab_4_" onclick="cargar_lineas()"><a href="#tab_4" data-toggle="tab" aria-expanded="false">Detalle Factura</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">-->
              <div class="row">
                <div class="col-sm-12">
                  <h2 style="margin-top: 0px;">Listado de Notas de Donación</h2>
                </div>
                <!--<div class="col-sm-3 d-flex justify-content-end" id="panel_pag">
                  
                </div>-->
                <div  class="col-sm-12" style="overflow-x: scroll;height: 500px;">    
                  <table id="tbl_tabla" class="table fs-8" style="width:100%;">
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
            <!--</div>

            <div class="tab-pane" id="tab_2">
              <div class="row">
                <div class="col-sm-6">
                  <h2 style="margin-top: 0px;">Listado de Notas de Donación</h2>
                </div>
                <div class="col-sm-6 text-right" id="panel_pagAu">
                  
                </div>
                <div  class="col-sm-12" style="overflow-x: scroll;height: 500px;">    
                  <table class="table text-sm" style=" white-space: nowrap;">
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
                    <tbody  id="tbl_tablaAu">
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

            <div class="tab-pane" id="tab_3">
              <div class="row">
                <div class="col-sm-6">

                  <div class="input-group margin">
                      <div class="input-group-btn open">
                          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Acciones
                              <span class="fa fa-caret-down"></span>
                          </button>
                          <ul class="dropdown-menu">
                            <li onclick="autorizar_blo()"><a href="#">Autorizar en bloque</a></li>
                            <li onclick=""><a href="#">Anular en bloque</a></li>
                          </ul>
                      </div>
                  </div>
                  <h2 style="margin-top: 0px;">Listado de Notas de Donación</h2>
                </div>
                <div class="col-sm-6 text-right" id="panel_pagNoAu">
                  
                </div>
                <div  class="col-sm-12" style="overflow-x: scroll;height: 500px;">    
                  <table class="table text-sm" style=" white-space: nowrap;">
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
                    <tbody  id="tbl_tablaNoAu">
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
            <div class="tab-pane" id="tab_4">
              <div class="row">
                <div class="col-sm-6">
                  <h2 style="margin-top: 0px;">Listado de Notas de Donación</h2>
                </div>
                <div class="col-sm-6 text-right" id="panel_pag">
                  
                </div>
                <div  class="col-sm-12" style="overflow-x: scroll;height: 500px;">    
                  <table class="table text-sm" style=" white-space: nowrap;">
                    <thead>
                      <th>T</th>          
                      <th>Producto</th>
                      <th>TC</th>
                      <th>Serie</th>
                      <th>Autorizacion</th>
                      <th>Factura</th>
                      <th>Fecha</th>
                      <th>Mes</th>
                      <th>Año</th>
                      <th>IVA</th>
                      <th>Descuento</th>
                      <th>Total</th>
                      <th>RUC_CI</th>
                    </thead>
                    <tbody  id="tbl_tabla_detalle">
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
            </div>-->
        <!--</div>-->
      </div>
    </div>
  </div> 
</div>


<div id="modal_email" class="modal fade" role="dialog" tabindex="-1" aria-labelledby="modal_email" aria-hidden="true">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content modal-sm">
      <div class="modal-header">
        <h4 class="modal-title">Recuperar Clave</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-sm btn-block"  id="btn_email" onclick="enviar_mail()"> Enviar Email</button>
        <button type="button" class="btn btn-default btn-sm btn-block"   data-dismiss="modal">Cerrar</button>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row"> 
                    <div class="col-sm-12 mb-2">
                        <input class="form-control form-control-sm" id="emails-input" name="emails-input" placeholder="añadir email...">
                        <input type="hidden" name="txt_fac" id="txt_fac">
                        <input type="hidden" name="txt_serie" id="txt_serie">
                        <input type="hidden" name="txt_codigoc" id="txt_codigoc">
                        <input type="hidden" name="txt_to" id="txt_to">
                        <input type="hidden" name="txt_NDauto" id="txt_NDauto">
                        <input type="hidden" name="txt_NDtc" id="txt_NDtc">
                    </div>
                    <div class="col-sm-12 mb-2">
                      <input type="" id="txt_titulo" name="txt_titulo" class="form-control form-control-sm" placeholder="Título de correo" value="Comprobantes">
                    </div>
                    <div class="col-sm-12 mb-2">
                        <textarea class="form-control form-control-sm" rows="3" style="resize:none" placeholder="Texto" id="txt_texto" name="txt_texto"></textarea>
                    </div>                                                  
                    <div class="col-sm-12">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="cbx_factura" id="cbx_factura" checked>
                        <label class="form-check-label" for="cbx_factura">
                          <b>Enviar Nota de Donación</b>
                        </label>
                      </div>
                        <!--<label><input type="checkbox" name="cbx_factura" id="cbx_factura" checked>Enviar Nota de Donación</label>-->
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
  </div>
</div>
<div id="re_frame"></div>




  <script src="../../dist/js/utils.js"></script>
  <script src="../../dist/js/emails-input.js"></script>
  <script src="../../dist/js/multiple_email.js"></script>