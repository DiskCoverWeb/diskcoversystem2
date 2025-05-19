<!--
    AUTOR DE RUTINA : Javier Farinango
    MODIFICADO POR : Javier Farinango
    FECHA CREACION : 26/02/2025
    FECHA MODIFICACION :26/02/2025
    DESCIPCION : Interfaz de modulo Gestion Social/Registro Beneficiario
 -->
<script src="../../dist/js/gestion_social/asignacion_familias.js"></script>

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
            <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);	print_r($ruta[0]); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
                <img src="../../img/png/salire.png">
            </a>
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Guardar" onclick="guardar()">
                <img src="../../img/png/grabar.png">
            </button>
        </div>
    </div>
</div>


<form id="form_asignacion">
    <div class="row mb-1">
        <div class="card" style="background-color: #fffacd;" id="rowGeneral">
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-lg-3 col-md-4 col-sm-12">
                        <b>Programa</b>
                        <select class="form-select form-select-sm" id="ddl_programas" onchange="grupos()"></select>
                    </div>
                    <div class="col-lg-4 col-md-8 col-sm-12">
                        <b>Grupo</b>
                        <div class="d-flex align-items-center input-group-sm">
                            <select name="ddl_grupos" id="ddl_grupos" class="form-select form-select-sm" onchange="listaAsignacion();IntegrantesGrupo()"></select>
                             <button type="button" title="Agregar beneficiario"  class="btn btn-success btn-sm" onclick="add_beneficiario()">
                                <i class="fa fa-plus m-0" style="font-size:8pt;"></i>
                            </button>
                            <button type="button" title="Eliminar Beneficiario"  class="btn btn-danger btn-sm" onclick="eliminar_asignacion_beneficiario()">
                                <i class="fa fa-times m-0" style="font-size:8pt;"></i>
                            </button>

                        </div>                                    
                    </div>
                     <div class="col-lg-2 col-md-4 col-sm-12">
                        <b>Atención:</b>
                        <input type="date" name="fechAten" id="fechAten" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">    
                    </div>
                     <div class="col-lg-3 col-md-4 col-sm-12">
                        <br>
                        <div class="input-group input-group-sm">                          
                            <button type="button" class="btn btn-outline-secondary" onclick="onclicktipoCompra()">
                                <img id="img_tipoCompra"  src="../../img/png/TipoCompra.png" style="width: 20px;" />
                            </button>
                            <select name="tipoCompra" id="tipoCompra" class="form-select form-select-sm" onchange="autocoplet_pro2()">
                            </select>
                        </div>                        
                    </div> 
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                                               
                        <div class="row">                    
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><b>Cantidad  de usuarios:</b></span>
                                
                                <input type="text" name="totalPersAten" id="totalPersAten" class="form-control form-control-sm" readonly>
                                <button type="button" class="btn btn-outline-secondary" onclick="llenarCamposPoblacion()">
                                    <img id="img_tipoBene"  src="../../img/png/Personas_atendidas.png" style="width: 32px;" />
                                </button>                                
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="row align-items-center">
                            <div class="col-sm-8">
                                <div class="row align-items-center">
                                    <div class="col-lg-3  col-md-3 col-sm-2">                        
                                        <img  src="../../img/png/cantidad_global.png" style="width: 100%;" />
                                    </div>  
                                    <div class="col-lg-8 col-md-9 col-sm-10" style="padding:0px">                        
                                        <b>Cantidad global a distribuir</b>
                                    </div>                     
                                </div> 
                            </div>
                            <div class="col-sm-4">
                                <input type="number" name="CantGlobDist" id="CantGlobDist" readonly style=""
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                     <div class="col-lg-4 col-md-6 col-sm-12">
                                               
                        <div class="row">                    
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><b>Codigo pedido:</b></span>
                                <input type="text" name="txt_codigo_pedido" id="txt_codigo_pedido" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-6 col-sm-12 mt-1">
                    	<div class="input-group input-group-sm"> 
	                        <textarea class="form-control form-control-sm" placeholder="comentario general de clasificación..." id="comeGeneAsig" name="comeGeneAsig" rows="1" style="resize: none;"></textarea>                           
	                        <button type="button" class="btn btn-success btn-sm btn-block"><i class="fa fa-save"></i>
	                        </button>
                        </div>                         
                    </div>
                </div>
            </div>
        </div>            
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="row"> 
                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <button type="button" class="btn btn-outline-secondary" onclick="show_producto();">
                                    <img src="../../img/png/Grupo_producto.png" /><b>Grupo producto</b></button>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7">
                                <b>Grupo producto:</b>
                                 <select name="grupProd" id="grupProd" class="form-select form-select-sm" onchange="buscar_producto(this.value)"></select>
                            </div>
                        </div>
                    </div>                   
                    <div class="col-lg-1 col-md-2 col-sm-12">
                        <label for="stock">
                            <b>Stock</b>
                        </label>
                        <input type="text" name="stock" id="stock" class="form-control form-control-sm" readonly>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="row">
                            <div class="col-sm-4">
                                <button type="button" class="btn btn-outline-secondary" onclick="show_cantidad()"
                                    id="btn_cantidad">
                                    <img src="../../img/png/kilo.png" style="width: 42px;height: 42px;" />
                                </button>
                            </div>
                            <div class="col-sm-4">
                                <b>Cantidad c/p</b>
                                <input type="number" name="cant_cp" id="cant_cp" class="form-control form-control-sm" onblur="calcular()">
                            </div>
                            <div class="col-sm-4">
                                <b>Total</b>
                                <input type="number" name="cant" id="cant" class="form-control form-control-sm" readonly>
                            </div>                            
                        </div>
                    </div>
                    
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label for="comeAsig">
                            <b>Comentario de Asignación</b>
                        </label>
                        <input type="text" name="comeAsig" id="comeAsig" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="row text-end">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <button type="button" class="btn btn-primary btn-sm" onclick="agregar();" style="width: fit-content;"><b>Agregar</b>
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" onclick="limpiar();" style="width: fit-content;"><b>Limpiar</b>
                        </button>                        
                    </div>                   
                </div>
            </div>                
        </div>
    </div>
</form>

<div class="row">
    <div class="card">
        <div class="card-body">
            <div class="row" id="panel_add_productos"><!-- DEFINIR EL ID SEGUN SEA NECESARIO -->
                <div class="col-sm-12">
                    <div class="box">
                        <div class="card_body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-hover" id="tbl_asignacion_os">
                                        <thead>
                                            <tr>
                                                <th style="width:7%;">ITEM</th>
                                                <th>PRODUCTO</th>
                                                <th>CANTIDAD</th>
                                                <th>TOTAL</th>
                                                <th>COMENTARIO DE ASIGNACIÓN</th>
                                                <th>ELIMINAR</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbl_body"></tbody>
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

<div id="modal_producto" class="modal fade myModalNuevoCliente" role="dialog" data-keyboard="false"
    data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Producto</h4>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="background: antiquewhite;">
                <div class="row">
                    <div class="col-sm-3">
                        <b>Referencia:</b>
                        <input type="text" name="txt_referencia" id="txt_referencia" class="form-control form-control-sm"
                            readonly="">
                    </div>
                    <div class="col-sm-9">
                        <b>Producto:</b>
                        <select class="form-select form-select-sm" id="ddl_producto" name="ddl_producto" style="width: 100%;" onchange="buscar_producto(this.value)" >
                            <option value="">Seleccione una producto</option>
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer" style="background-color:antiquewhite;">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div id="modal_cantidad" class="modal fade myModalNuevoCliente" role="dialog" data-keyboard="false"
    data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cantidad</h4>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="background: antiquewhite;">
                <b>Cantidad</b>
                <input type="" name="txt_cantidad2" id="txt_cantidad2" class="form-control" placeholder="0"
                    onblur="cambiar_cantidad()">
            </div>
            <div class="modal-footer" style="background-color:antiquewhite;">
                <button type="button" class="btn btn-primary" onclick="cambiar_cantidad()">OK</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


    <div id="modalBtnGrupo" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">INtegrantes de grupo</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px; background: antiquewhite;">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-striped" id="tblClientes">
                            <thead>
                                <th>ITEM</th>
                                <th>USUARIO</th>
                                <th>CEDULA</th>
                            </thead>
                            <tbody id="tbl_integrantes"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer"  style="background-color:antiquewhite;">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

<div id="modal_tipoCompra" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title">Tipo Asignacion</h4>
              <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" style="background: antiquewhite;">
            <div class="row text-center" id="pnl_tipo_empaque">
            </div>                       
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <!-- <button type="button" class="btn btn-primary" onclick="cambiar_tipo_asig()">OK</button> -->
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>
<div id="modal_addBeneficiario" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title">Agregar Beneficiario</h4>
              <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" style="background: antiquewhite;">
            <div class="row">
                <div class="col-sm-12">
                    <b>Beneficiario / Usuario</b>
                    
                    <select name="beneficiario_new" id="beneficiario_new" class="form-select form-select-sm" style="width:100%"></select>
                </div>
            </div>                       
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-primary" onclick="asignar_beneficiario()">Asignar Beneficiario</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>

