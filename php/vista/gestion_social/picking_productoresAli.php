<!--
    AUTOR DE RUTINA : Dallyana Vanegas
    MODIFICADO POR : Javier Farinango
    FECHA CREACION : 16/02/2024
    FECHA MODIFICACION :10/02/2025
    DESCIPCION : Interfaz de modulo Gestion Social/Registro Beneficiario
 -->
<script src="../../dist/js/gestion_social/picking_productoresAli.js"></script>
<script type="text/javascript">
    function guardar()
    {
        ben = $('#beneficiario').val();
        distribuir = $('#CantGlobDist').val();
        if(ben=='' || ben==null){Swal.fire("","Seleccione un Beneficiario","info");return false;}
        if(distribuir==0 || distribuir==''){ Swal.fire("","No se a agregado nigun grupo de producto","info");return false;}
        var parametros = {
            'beneficiario':ben,
            'fecha':$('#fechAten').val(),
            'comentario':$('#comeGeneAsig').val(),
        }
         $.ajax({
            url: '../controlador/inventario/asignacion_osC.php?GuardarAsignacion=true',
            type: 'post',
            dataType: 'json',
            data: { parametros: parametros },
            success: function (datos) {
                if(datos==1)
                {
                    Swal.fire("Asignacion Guardada","","success").then(function(){
                        location.reload();
                    });
                }

            }
        });
    }
</script>
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
             <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Listar Checking" onclick="lista_picking()">
                <img src="../../img/png/taskboard.png">
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
                         <div class="input-group input-group-sm">
                            <span class="input-group-text"><b>Día Entrega</b></span>
                            <select class="form-select form-select-sm" id="diaEntr">
                                <option value="Lun">Lunes</option>
                                <option value="Mar">Martes</option>
                                <option value="Mie">Miercoles</option>
                                <option value="Jue">Jueves</option>
                                <option value="Vie">Viernes</option>
                                <option value="Sab">Sabado</option>
                                <option value="Dom">Domingo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-8 col-sm-12">
                        <div class="d-flex align-items-center input-group-sm">
                            <select name="beneficiario" id="beneficiario" class="form-select form-select-sm" onchange="listaAsignacion()"></select>
                             <button type="button" title="Agregar beneficiario"  class="btn btn-success btn-sm" onclick="add_beneficiario()">
                                <i class="fa fa-plus m-0" style="font-size:8pt;"></i>
                            </button>
                            <button type="button" title="Eliminar Beneficiario"  class="btn btn-danger btn-sm" onclick="eliminar_asignacion_beneficiario()">
                                <i class="fa fa-times m-0" style="font-size:8pt;"></i>
                            </button>

                        </div>                                    
                    </div>
                     <div class="col-lg-3 col-md-4 col-sm-12">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><b>Fecha Atención:</b></span>
                            <input type="date" name="fechAten" id="fechAten" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
                        </div>                                
                    </div>
                     <div class="col-lg-2 col-md-4 col-sm-12">
                        <div class="input-group input-group-sm">                          
                            <button type="button" class="btn btn-outline-secondary" onclick="onclicktipoCompra()">
                                <img id="img_tipoCompra"  src="../../img/png/TipoCompra.png" style="width: 20px;" />
                            </button>
                            <select name="tipoCompra" id="tipoCompra" class="form-select form-select-sm" onchange="autocoplet_pro2()">
                            </select>
                        </div>
                        
                    </div>               
                    <div class="col-lg-3 col-md-4 col-sm-12">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><b>Estado</b></span>
                            <input type="tipoEstado" name="tipoEstado" id="tipoEstado" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><b><i class="fa fa-clock-o"></i> Hora de Entrega</b></span>
                            
                            <input type="time" name="horaEntrega" id="horaEntrega" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><b>Frecuencia</b></span>
                            
                            <input type="text" name="frecuencia" id="frecuencia" class="form-control form-control-sm">
                        </div>                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="row">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><b>Tipo de productor:</b></span>
                                <input type="text" name="tipoBenef" id="tipoBenef" class="form-control form-control-sm" readonly>
                                <button type="button" class="btn btn-outline-secondary">
                                    <img id="img_tipoBene"  src="../../img/png/cantidad_global.png" style="width: 20px;" />
                                </button>
                                
                            </div>
                        </div>
                        
                        <div class="row">                    
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><b>Animales y requerimientos<br> alimenticios:</b></span>
                                
                                <input type="text" name="totalPersAten" id="totalPersAten" class="form-control form-control-sm" readonly>
                                <button type="button" class="btn btn-outline-secondary" onclick="llenarCamposPoblacion()">
                                    <img id="img_tipoBene"  src="../../img/png/req_alimenticio.png" style="width: 32px;" />
                                </button>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6 col-sm-12">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <div class="row align-items-center">
                                    <div class="col-sm-4">                        
                                        <img  src="../../img/png/cantidad_global.png" style="width: 100%;" />
                                    </div>  
                                    <div class="col-sm-8" style="padding:0px">                        
                                        <b>Cantidad global sugerida a distribuir</b>
                                    </div>                     
                                </div> 
                            </div>
                            <div class="col-sm-6">
                                <input type="number" name="CantGlobSugDist" id="CantGlobSugDist" readonly style=""
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <textarea class="form-control form-control-sm" placeholder="comentario general de asignación...(40 caracteres)" id="comeGeneAsig" maxlength="50" name="comeGeneAsig" rows="3" style="resize: none;"></textarea>                           
                        <button type="button" class="btn btn-success btn-sm btn-block w-100"><i class="fa fa-save"></i>
                        </button>                         
                    </div>
                </div>
            </div>
        </div>            
    </div>
    <div class="row">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <button type="button" class="btn btn-outline-secondary w-100" onclick="show_producto();"><img
                                src="../../img/png/Grupo_producto.png" /> <br> <b>Grupo producto</b></button>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12">
                            <b>Grupo producto:</b>
                            <select name="ddlgrupoProducto" id="ddlgrupoProducto" class="form-select form-select-sm" onchange="cargarProductosGrupo()"></select>
                            <b>Codigo</b>
                            <div class="input-group input-group-sm">
                                <select name="txt_codigo" id="txt_codigo" class="form-select form-select-sm" onchange="validar_codigo()"></select>
                                <button type="button" class="btn btn-primary btn-sm" style="font-size:8pt;" title="Escanear QR" onclick="escanear_qr()">
                                    <i class="fa fa-qrcode" aria-hidden="true" style="font-size:8pt;"></i> Escanear QR
                                </button>
                            </div>
                            <input type="hidden" id="txt_id" name="txt_id">
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-12">
                    <div class="row">
                        <div class="col-lg-7 col-md-7 col-sm-12">
                            <b>Proveedor / Donante</b>
                            <input type=""  class="form-control form-control-sm" placeholder="Proveedor / Donante" id="txt_donante" name="txt_donante">
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12">
                            <b>Stock:</b>
                            <input type="" name="txt_stock" id="txt_stock" class="form-control form-control-sm" placeholder="0" readonly>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-12">
                            <b>Fecha expiracion</b>
                            <input type="date" name="txt_fecha_exp" id="txt_fecha_exp" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="col-lg-9 col-md-8 col-sm-12">
                            <b>Ubicacion</b>
                            <input type="" name="txt_ubicacion" id="txt_ubicacion" class="form-control form-control-sm" placeholder="Proveedor / Donante" readonly>                                     
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 p-0">
                            <br> 
                            <button class="btn btn-light align-items-center justify-content-center" type="button" id="btn_alto_stock" style="display:none;">
                            <img id="img_alto_stock"  src="../../img/gif/alto_stock_titi.gif" style="width:32px">
                            Alto Stock
                        </button>
                        <button class="btn btn-light align-items-center justify-content-center" type="button" id="btn_expired" style="display:none;">
                            <img id="img_por_expirar" src="../../img/gif/expired_titi.gif" style="width:32px">
                            <b id="btn_titulo">Por Expirar</b>
                        
                            
                        </button>

                        </div>  
                    </div>
                </div>
            
            </div>
            <div class="row">
                <div class="col-sm-1 pe-0">
                    <button type="button" style="width: initial;" class="btn btn-outline-secondary w-100" onclick="show_cantidad()"
                        id="btn_cantidad">
                        <img src="../../img/png/kilo.png" style="width: 42px;height: 42px;" />
                    </button>
                </div>
                <div class="col-sm-2">
                    <b>Cantidad</b>
                    <input type="number" name="cant" id="cant" class="form-control form-control-sm">
                </div>
                <div class="offset-sm-6 col-sm-3 g-2 d-flex justify-content-end align-items-end">
                    <button class="btn btn-primary btn-sm me-2 px-3" onclick="agregar_picking()" style="width: fit-content; height: fit-content;">Ingreso</button>
                    <button class="btn btn-primary btn-sm px-3" onclick="limpiar();" style="width: fit-content; height: fit-content;">Borrar</button>
                </div>
            </div>
        </div>
    </div>      
</div>  
<div class="row">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">     
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-hover" id="tbl_picking_os">
                                <thead>
                                    <tr>
                                        <th width="10%"></th>
                                        <th>FECHA ATENCION</th>
                                        <th>FECHA PICKING</th>
                                        <th>DESCRIPCION</th>
                                        <th>CODIGO</th>
                                        <th>USUARIO</th>
                                        <th>CANTIDAD (KG)</th>
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




<div id="modalDetalleCantidad" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ver detalle</h4>
                <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="overflow-y: auto; max-height: 300px;"  id="pnl_detalle">
                 
                               
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-success" id="btnGuardarGrupo">Aceptar</button> -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
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