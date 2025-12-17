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
        <!-- <button type="button" class="btn btn-outline-secondary" title="Informe excel" onclick="imprimir_excel()" >
          <img src="../../img/png/excel2.png">
        </button>
        <button type="button" class="btn btn-outline-secondary" title="Informe pdf" onclick="imprimir_pdf()">
          <img src="../../img/png/pdf.png">
        </button> -->
         <button title="Guardar"  class="btn btn-outline-secondary" onclick="nuevo_indicador_modal()">
          <img src="../../img/png/mostrar.png" >
        </button>
      </div>
  </div>
</div>
<div class="row mb-2">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-4 mb-2">
          <b>Indicador de gestion</b><br>
          <div class="input-group">
            <select class="form-select form-select-sm" id="ddl_indicador_gestion_grupo" onchange="ddl_indicador_gestion()">
              <option value="">Seleccione</option>
            </select> 
               <!-- <button class="btn btn-sm btn-success" onclick="nuevo_indicador_modal()"><i class="bx bx-plus"></i></button> -->
          </div>       
        </div>
        <div class="col-sm-6 mb-2">
          <b>Indicador de gestion de</b><br>
          <div class="input-group">
             <select class="form-select form-select-sm" id="ddl_indicador_gestion">
               <option value="">Seleccione</option>
             </select> 
          </div>       
        </div>
      </div>
     
      
      <div class="col-sm-6">
        <table class="table">
          <tr>
            <td><b>Enero</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_valor_0" id="txt_valor_0" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_0" onclick="editar_valor(0)"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_0" onclick="guardar_valor(0)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Febrero</b></td>
            <td>
              <div class="input-group">
                  <input type="text" class="form-control form-control-sm" name="txt_valor_1" id="txt_valor_1" readonly>
                  <button class="btn btn-sm d-none" id="btn_edit_1" onclick="editar_valor(1)"><i class="bx bx-pencil"></i></button>
                  <button class="btn btn-sm d-none" id="btn_save_1" onclick="guardar_valor(1)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Marzo</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_valor_2" id="txt_valor_2" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_2" onclick="editar_valor(2)"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_2" onclick="guardar_valor(2)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Abril</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_valor_3" id="txt_valor_3" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_3" onclick="editar_valor(3)"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_3" onclick="guardar_valor(3)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Mayo</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_valor_4" id="txt_valor_4" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_4" onclick="editar_valor(4)"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_4" onclick="guardar_valor(4)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Junio</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_valor_5" id="txt_valor_5" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_5" onclick="editar_valor(5)"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_5" onclick="guardar_valor(5)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Julio</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_valor_6" id="txt_valor_6" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_6" onclick="editar_valor(6)"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_6" onclick="guardar_valor(6)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Agosto</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_valor_7" id="txt_valor_7" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_7" onclick="editar_valor(7)"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_7" onclick="guardar_valor(7)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Septiembre</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_valor_8" id="txt_valor_8" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_8" onclick="editar_valor(8)"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_8" onclick="guardar_valor(8)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Octubre</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_valor_9" id="txt_valor_9" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_9" onclick="editar_valor(9)"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_9" onclick="guardar_valor(9)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Noviembre</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_valor_10" id="txt_valor_10" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_10" onclick="editar_valor(10)"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_10" onclick="guardar_valor(10)"><i class="bx bx-save"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Diciembre</b></td>
            <td>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="txt_valor_11" id="txt_valor_11" readonly>
                <button class="btn btn-sm d-none" id="btn_edit_11" onclick="editar_valor(11)"><i class="bx bx-pencil"></i></button>
                <button class="btn btn-sm d-none" id="btn_save_11" onclick="guardar_valor(11)"><i class="bx bx-save"></i></button>
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
          <div class="col-sm-12">
            <label><input type="radio" name="rbl_tipo" value="G" id="rbl_tipo_grupo" checked onclick="cambiar_tipo()">Grupo</label>    
            <label><input type="radio" name="rbl_tipo" value="D" id="rbl_tipo_detalle" onclick="cambiar_tipo()">Detalle</label>   
          </div>       
        </div>
        <div class="row d-none" id="pnl_grupo">
          <div class="col-sm-12">
            <b>Grupo de indicadores</b> <br>
            <select class="form-select form-select-sm" id="ddl_grupo_identificador">
              <option value="">Seleccione grupo</option>
            </select>            
          </div>
        </div>
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
