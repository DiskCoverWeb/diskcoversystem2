<?php  ?>

<script type="text/javascript" src="../../dist/js/contratos/registro_GFN.js"></script>
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
<div class="row mb-2">
  <div class="col-sm-6">
     <div class="btn-group" role="group" aria-label="Basic example">
        <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
                print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
            <img src="../../img/png/salire.png">
        </a>
        <button type="button" class="btn btn-outline-secondary" title="Informe excel" onclick="imprimir_excel()" >
          <img src="../../img/png/excel2.png">
        </button>
        <button type="button" class="btn btn-outline-secondary" title="Informe pdf" onclick="imprimir_pdf()">
          <img src="../../img/png/pdf.png">
        </button>
        <button title="Guardar"  class="btn btn-outline-secondary" onclick="grabar_solicitud_proveedor()">
          <img src="../../img/png/grabar.png" >
        </button>
      </div>
  </div>
</div>
<div class="row mb-2">
  <div class="card">
    <div class="card-body">
      <div class="col-sm-6 mb-2">
        <b>Indicador de gestion</b><br>
        <div class="input-group">
           <select class="form-select form-select-sm" id="ddl_indicador_gestion">
             <option value="">Seleccione</option>
           </select> 
           <button class="btn btn-sm btn-success" onclick="nuevo_indicador_modal()"><i class="bx bx-plus"></i></button>
        </div>       
      </div>
      <div class="col-sm-6">
        <table class="table">
          <tr>
            <td><b>Enero</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_enero" id="txt_enero" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_enero"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_enero" onclick="guardar_valor(1)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Febrero</b></td>
            <td>
              <div class="input-group">
                  <input type="text" class="form-control form-control-sm" name="txt_febrero" id="txt_febrero" readonly>
                  <button class="btn btn-sm d-none" id="btn_edit_febrero"><i class="bx bx-pencil"></i></button>
                  <button class="btn btn-sm d-none" id="btn_save_febrero" onclick="guardar_valor(2)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Marzo</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_marzo" id="txt_marzo" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_marzo"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_marzo" onclick="guardar_valor(3)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Abril</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_abril" id="txt_abril" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_abril"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_abril" onclick="guardar_valor(4)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Mayo</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_mayo" id="txt_mayo" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_mayo"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_mayo" onclick="guardar_valor(5)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Junio</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_junio" id="txt_junio" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_junio"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_junio" onclick="guardar_valor(6)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Julio</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_julio" id="txt_julio" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_julio"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_julio" onclick="guardar_valor(7)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Agosto</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_agosto" id="txt_agosto" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_agosto"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_agosto" onclick="guardar_valor(8)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Septiembre</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_septiembre" id="txt_septiembre" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_septiembre"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_septiembre" onclick="guardar_valor(9)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Octubre</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_octubre" id="txt_octubre" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_octubre"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_octubre" onclick="guardar_valor(10)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Noviembre</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_noviembre" id="txt_noviembre" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_noviembre"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_noviembre" onclick="guardar_valor(11)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Diciembre</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_diciembre" id="txt_diciembre" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_diciembre"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_diciembre" onclick="guardar_valor(12)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
        </table>        
      </div>
    </div>
  </div>
</div>

<div class="modal" id="nuevo_indicador" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header gap-2">
        <div class="position-relative popup-search w-100">
          <h3>Nuevo indicador</h3>
        </div>
        <button type="button" class="btn-close d-md-none" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-4">
            <b>Codigo</b>
            <input type="text" class="form-control form-control-sm" id="txt_codigo" name="txt_codigo">
            
          </div>
          <div class="col-sm-8">
            <b>Identificador de gestion</b>
            <input type="text" class="form-control form-control-sm" id="txt_identificador" name="txt_identificador">
            
          </div>          
        </div>
        
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="guardar_indicador()">Guardar</button>
          <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
