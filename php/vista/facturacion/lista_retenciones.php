<?php  @session_start();  date_default_timezone_set('America/Guayaquil');  //print_r($_SESSION['INGRESO']);die();
$tipo='';
?>
<script src="../../dist/js/lista_retenciones.js"></script>
<script type="text/javascript">
$(document).ready(function(){

      tbl_retenciones_all = $('#tbl_retenciones').DataTable({
          // responsive: true,
          language: {
              url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
          },
          ajax: {
              url:   '../controlador/facturacion/lista_retencionesC.php?tabla=true',
              type: 'POST',  // Cambia el método a POST    
              data: function(d) {
                  var parametros = {
                      ci: $('#ddl_cliente').val(),
                      desde: $('#txt_desde').val(),
                      hasta: $('#txt_hasta').val(),
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
                                <a href="#" class="dropdown-item" onclick="Ver_retencion('${item.SecRetencion}','${item.Serie_Retencion}','${item.Numero}','${item.TP}')"><i class="bx bx-show-alt"></i> Ver Retencion</a>
                            </li>
                            <li>

                              <a href="#" class="dropdown-item" onclick=" modal_email_ret('${item.SecRetencion}','${item.Serie_Retencion}','.${item.Numero}','${item.AutRetencion}','${email}')"><i class="bx bx-envelope"></i> Enviar Retencion por email</a>
                            </li>
                            <li>
                                <a href="#" class="dropdown-item" onclick="descargar_ret('${item.SecRetencion}','${item.Serie_Retencion}','${item.Numero}')"><i class="bx bx-download"></i> Descargar Retencion</a>
                            </li>`;
                  
                    if (item.ExisteSerie =='Si'  && item.Autorizacion_NC.length == 13) 
                    {
                        options+=`<li>
                          <a href="#" class="dropdown-item" onclick="autorizar('${item.SecRetencion}','${item.Serie_Retencion}','${formatoDate(item.Fecha.date)}')" ><i class="bx bx-paper-plane"></i>Autorizar</a>
                          </li>`;
                    }else
                    {
                       options+=`<li><a href="#"  class="dropdown-item btn-danger"><i class="fa fa-info"></i>Para autorizar Asigne en catalo de lineas la serie: ${item.Serie_Retencion}</a></li>`;

                    }
                    
                    if (item.T != 'A') 
                    {
                          options+=`<li><a href="#" class="dropdown-item" onclick="anular_factura('${item.SecRetencion}','${item.Serie_Retencion}','${item.IdProv}')"><i class="bx bx-x"></i>Anular Retencion</a></li>`;
                    }
                    if (item.AutRetencion.length > 13) 
                    {
                        options+=`<li><a href="#" class="dropdown-item" onclick="descargar_xml('${item.AutRetencion}')">
                        <i class="bx bx-download"></i> Descargar XML</a></li>`;
                    }


                  
                    return `<div class="input-group-btn">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="true">Acciones
                                </button>
                                <ul class="dropdown-menu">`+options+`</ul>
                            </div>`;                    
                  } 
              },
              { data: 'T' },
              { data: 'Cliente' },
              { data: 'TD' },
              { data: 'Serie_Retencion' },
              { data: 'AutRetencion' },
              { data: 'SecRetencion' },
              { data: 'Fecha.date',  
                  render: function(data, type, item) {
                      return data ? new Date(data).toLocaleDateString() : '';
                  }
              },              
              { data: 'BaseImponible' },
              { data: 'IdProv' },
              { data : null,
                     render: function(data, type, item) {return 'RE'; }
              },
              { data: 'Numero' },
              { data: 'TP' }
          ],
          order: [
              [1, 'asc']
          ]
      });
       
   })

</script>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
  <div class="breadcrumb-title pe-3"><?php echo $NombreModulo; ?></div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0" id="ruta_menu">
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
<form id="filtros">  
  <div class="row">       
      <div class="col-sm-5">
        <b>Nombre</b>
        <select class="form-select form-select-sm" id="ddl_cliente" name="ddl_cliente">
          <option value="">Seleccione Cliente</option>
        </select>
      </div>
      <div class="col-sm-1" style="padding: 0px;">
        <b>Serie</b>
          <select class="form-select form-select-sm" name="DCLinea" id="DCLinea" tabindex="1" style="padding-left:8px">
            <option value=""></option>
          </select>
      </div>
      <div class="col-sm-2">
        <b>Desde</b>
          <input type="date" name="txt_desde" id="txt_desde" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">           
      </div>
      <div class="col-sm-2">
        <b>Hasta</b>
          <input type="date" name="txt_hasta" id="txt_hasta" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">            
      </div>          
      <div class="col-sm-2"><br>
        <button class="btn btn-primary btn-sm" type="button" onclick="cargar_registros();"><i class="bx bx-search"></i> Buscar</button>
      </div>
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
        <table class="table text-sm" style="width: 100%;" id="tbl_retenciones">
          <thead>
            <th></th>
            <th>T</th>          
            <th>Razon_Social</th>
            <th>TC</th>
            <th>Serie</th>
            <th>Autorizacion</th>
            <th>Retencion</th>
            <th>Fecha</th>
            <th>SubTotal</th>
            <th>RUC_CI</th>
            <th>TB</th>
            <th>Numero</th>
            <th>TP</th>
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
            </tr>
          </tbody>
        </table>
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
                        <input type="hidden" name="txt_autorizacion" id="txt_autorizacion">
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





  <script src="../../dist/js/utils.js"></script>
  <script src="../../dist/js/emails-input.js"></script>
  <script src="../../dist/js/multiple_email.js"></script>