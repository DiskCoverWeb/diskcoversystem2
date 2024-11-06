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
<script src="../../dist/js/lista_facturas.js"></script>
<script type="text/javascript">

   function cargar_registros()
   {
   
    var per = $('#ddl_periodo').val();
    var serie = $('#DCLinea').val();
    if(serie!='' && serie!='.')
    {
     var serie = serie.split(' ');
     var serie = serie[1];
    }else
    {
      serie = '';
    }
    var tipo = '<?php echo $tipo; ?>'
    var parametros = 
    {
      'ci':$('#ddl_cliente').val(),
      'per':per,      
      'desde':$('#txt_desde').val(),
      'hasta':$('#txt_hasta').val(),
      'tipo':tipo,
      'serie':serie,
    }
     $.ajax({
       data:  {parametros:parametros},
      url:   '../controlador/facturacion/lista_facturasC.php?tabla=true',
      type:  'post',
      dataType: 'json',
      beforeSend: function () {
        $("#tbl_tabla").html('<tr class="text-center"><td colspan="16"><img src="../../img/gif/loader4.1.gif" width="20%">');
      },
       success:  function (response) { 
        // console.log(response);
       $('#tbl_tabla').html(response);
       $('#myModal_espera').modal('hide');
      }
    });

   }

   function cargar_lineas()
   {   
    var per = $('#ddl_periodo').val();
    var serie = $('#DCLinea').val();
    if(serie!='' && serie!='.')
    {
     var serie = serie.split(' ');
     var serie = serie[1];
    }else
    {
      serie = '';
    }
    var tipo = '<?php echo $tipo; ?>'
    var parametros = 
    {
      'ci':$('#ddl_cliente').val(),
      'per':per,      
      'desde':$('#txt_desde').val(),
      'hasta':$('#txt_hasta').val(),
      'tipo':tipo,
      'serie':serie,
    }
     $.ajax({
       data:  {parametros:parametros},
      url:   '../controlador/facturacion/lista_facturasC.php?tabla_lineas=true',
      type:  'post',
      dataType: 'json',
      beforeSend: function () {
        $("#tbl_tabla_detalle").html('<tr class="text-center"><td colspan="16"><img src="../../img/gif/loader4.1.gif" width="20%">');
      },
       success:  function (response) { 
        // console.log(response);
       $('#tbl_tabla_detalle').html(response);
       $('#myModal_espera').modal('hide');
      }
    });

   }



   function cargar_registrosAu(AU)
   {
   
    var per = $('#ddl_periodo').val();
    var serie = $('#DCLinea').val();
    if(serie!='' && serie!='.')
    {
     var serie = serie.split(' ');
     var serie = serie[1];
    }else
    {
      serie = '';
    }
    var tipo = '<?php echo $tipo; ?>'
    var parametros = 
    {
      'ci':$('#ddl_cliente').val(),
      'per':per,      
      'desde':$('#txt_desde').val(),
      'hasta':$('#txt_hasta').val(),
      'tipo':tipo,
      'serie':serie,
      'auto':AU,
    }
     $.ajax({
       data:  {parametros:parametros},
      url:   '../controlador/facturacion/lista_facturasC.php?tablaAu=true',
      type:  'post',
      dataType: 'json',
      beforeSend: function () {
         if(AU==1)
        {         
          $("#tbl_tablaAu").html('<tr class="text-center"><td colspan="16"><img src="../../img/gif/loader4.1.gif" width="20%">');
        }else
        {
           $("#tbl_tablaNoAu").html('<tr class="text-center"><td colspan="16"><img src="../../img/gif/loader4.1.gif" width="20%">');
        }
       
      },
       success:  function (response) { 
        // console.log(response);
        if(AU==1)
        {
          $('#tbl_tablaAu').html(response);
        }else
        {
          $('#tbl_tablaNoAu').html(response);
        }
       $('#myModal_espera').modal('hide');
      }
    });

   }

 
	function validar()
	{
		var cli = $('#ddl_cliente').val();
		var cla = $('#txt_clave').val();
    var tip = '<?php echo $tipo; ?>';
    var ini = $('#txt_desde').val();
    var fin = $('#txt_hasta').val();
    var periodo = $('#ddl_periodo').val();
    //si existe periodo valida si esta en el rango
    if(periodo!='.')
    {
      const fechaInicio=new Date(periodo+'-01-01');
      const fechaFin=new Date(periodo+'-01-30');
      var ini = new Date(ini);
      var fin = new Date(fin);

      // console.log(fechaInicio)
      // console.log(fechaFin)
      // console.log(ini)
      // console.log(fin)

      if(ini>fechaFin || ini<fechaInicio)
      {
        Swal.fire('la fecha desde:'+ini,'No esta en el rango','info').then(function(){
           $('#txt_desde').val(periodo+'-01-01');
           return false;
        })
      }
      if(fin>fechaFin || fin<fechaInicio)
      {
        Swal.fire('la fecha hasta:'+fin,'No esta en el rango','info').then(function(){
           $('#txt_hasta').val(periodo+'-01-30');
           return false;
        })
      }

    }

    
      if(cli=='')
      {
        Swal.fire('Seleccione un cliente','','error');
        return false;
      }
    if(tip=='')
    {
  		if(cla=='')
  		{
  			Swal.fire('Clave no ingresados','','error');
  			return false;
  		}
    }
		var parametros = 
		{
			'cli':cli,
			'cla':cla,
      'tip':tip,
		}
		 $.ajax({
             data:  {parametros:parametros},
             url:   '../controlador/facturacion/lista_facturasC.php?validar=true',
             type:  'post',
             dataType: 'json',
             success:  function (response) {
             if(response == 1)
             {
             	$('#myModal_espera').modal('show');
             	cargar_registros();
              cargar_registrosAu(1);
              cargar_registrosAu(2);
             }else
             {
             	Swal.fire('Clave incorrecta.','Asegurese de que su clave sea correcta','error');
             }
          } 
        });
	}

  </script>
  <div class="row">
    <div class="col-lg-4 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="reporte_pdf()"><img src="../../img/png/pdf.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="generar_excel()"><img src="../../img/png/table_excel.png"></button>
        </div>
 </div>
</div>
		<div class="row">
      <form id="filtros">
        <div class="col-sm-12">
          <div class="row">
            <div class="col-sm-2">
              <b>GRUPO</b>
              <select class="form-control input-xs" id="ddl_grupo" name="ddl_grupo" onchange="autocmpletar_cliente()">
                <option value=".">TODOS</option>
              </select>
              <!-- <input type="text" name="txt_grupo" id="txt_grupo" class="form-control input-sm"> -->
            </div>
            <div class="col-sm-5">
              <b>CI / RUC</b>
              <select class="form-control input-xs" id="ddl_cliente" name="ddl_cliente" onchange="periodos(this.value);rangos();">
                <option value="">Seleccione Cliente</option>
              </select>
            </div>
            <div class="col-sm-1" style="padding: 0px;">
              <b>Serie</b>
                <select class="form-control input-xs" name="DCLinea" id="DCLinea" tabindex="1" style="padding-left:8px">
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
                <input type="date" name="txt_desde" id="txt_desde" class="form-control input-xs" value="<?php echo date('Y-m-d')?>">
            </div>  
            <div class="col-sm-2">
              <b>Hasta</b>
                <input type="date" name="txt_hasta" id="txt_hasta" class="form-control input-xs" value="<?php echo date('Y-m-d')?>">
            </div>                         
          </div>
          <div class="row">            
          <div class="col-sm-6 text-right">
            </div>       
            <div class="col-sm-6 text-right">
              <button class="btn btn-primary btn-xs" type="button" onclick="validar()"><i class="fa fa-search"></i> Buscar</button>
            </div>
            
          </div>
          <div></div>
        </div>
      </form>

		</div>
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
    <br>
  <div class="row">
    <div class="col-sm-12">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Todos</a></li>
          <li class="" id="tab_2_"><a href="#tab_2" data-toggle="tab" aria-expanded="false">Autorizados</a></li>
          <li class="" id="tab_3_"><a href="#tab_3" data-toggle="tab" aria-expanded="false">No Autorizados</a></li>
          <li class="" id="tab_4_" onclick="cargar_lineas()"><a href="#tab_4" data-toggle="tab" aria-expanded="false">Detalle Factura</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
              <div class="row">
                <div class="col-sm-6">
                  <h2 style="margin-top: 0px;">Listado de facturas</h2>
                </div>
                <div class="col-sm-6 text-right" id="panel_pag">
                  
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
                    <tbody  id="tbl_tabla">
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

            <div class="tab-pane" id="tab_2">
              <div class="row">
                <div class="col-sm-6">
                  <h2 style="margin-top: 0px;">Listado de facturas</h2>
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
                            <!-- <li><a href="#">Something else here</a></li> -->
                          </ul>
                      </div>
                  </div>
                  <h2 style="margin-top: 0px;">Listado de facturas</h2>
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
                  <h2 style="margin-top: 0px;">Listado de facturas</h2>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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