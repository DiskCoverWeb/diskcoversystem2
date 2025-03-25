<!--
    AUTOR DE RUTINA : Dallyana Vanegas
    MODIFICADO POR : Javier Farinango
    FECHA CREACION : 16/02/2024
    FECHA MODIFICACION :10/02/2025
    DESCIPCION : Interfaz de modulo Gestion Social/Registro Beneficiario
 -->
<script src="../../dist/js/asignacion_os.js"></script>
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

    function add_beneficiario(){
        $('#modal_addBeneficiario').modal('show');

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
                            <span class="input-group-text"><b>Tipo Entrega</b></span>
                            <input type="text" name="tipoEntrega" id="tipoEntrega" class="form-control form-control-sm">
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
                                <span class="input-group-text"><b>Tipo de Beneficiario:</b></span>
                                <input type="text" name="tipoBenef" id="tipoBenef" class="form-control form-control-sm" readonly>
                                <button type="button" class="btn btn-outline-secondary">
                                    <img id="img_tipoBene"  src="../../img/png/cantidad_global.png" style="width: 20px;" />
                                </button>
                                
                            </div>
                        </div>
                        
                        <div class="row">                    
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><b>Total, Personas Atendidas:</b></span>
                                
                                <input type="text" name="totalPersAten" id="totalPersAten" class="form-control form-control-sm" readonly>
                                <button type="button" class="btn btn-outline-secondary" onclick="llenarCamposPoblacion()">
                                    <img id="img_tipoBene"  src="../../img/png/Personas_atendidas.png" style="width: 32px;" />
                                </button>
                                
                            </div>
                        </div>
                       
                        <div class="row">                    
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><b>Acción Social:</b></span>
                                
                                <input type="text" name="acciSoci" id="acciSoci" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                        <div class="row">                   
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><b>Vulnerabalidad:</b></span>
                                
                                <input type="text" name="vuln" id="vuln" class="form-control form-control-sm" readonly>
                            </div>
                            
                        </div>
                        <div class="row">                    
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><b>Tipo de Atención:</b></span>
                                
                                <input type="text" name="tipoAten" id="tipoAten" class="form-control form-control-sm" readonly>
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
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <label for="CantGlobDist" style="white-space: nowrap;">
                                    <b>Cantidad global a distribuir</b>
                                </label>
                            </div>
                            <div class="col-sm-6">
                                <input type="number" name="CantGlobDist" id="CantGlobDist" style=""
                                    class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-sm-6 d-flex gap-2">
                                <img  src="../../img/png/info_nutricional.png" style="width: 25%;" />
                                <b>Información Nutricional</b>
                            </div>
                            <div class="col-sm-6">
                                <textarea name="infoNutr" id="infoNutr" rows="3" class="form-control form-control-sm" placeholder="">
                                </textarea>
                                
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
                    <div class="col-lg-5 col-md-6 col-sm-12">
                        <div class="row"> 
                            <div class="col-lg-5 col-md-5 col-sm-5">
                                <button type="button" class="btn btn-outline-secondary" onclick="show_producto();">
                                    <b>Grupo producto</b><img src="../../img/png/Grupo_producto.png" /></button>
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
                    <div class="col-lg-3 col-md-4">
                        <div class="row">
                            <div class="col-sm-4">
                                <button type="button" class="btn btn-outline-secondary" onclick="show_cantidad()"
                                    id="btn_cantidad">
                                    <img src="../../img/png/kilo.png" style="width: 42px;height: 42px;" />
                                </button>
                            </div>
                            <div class="col-sm-8">
                                <b>Cantidad</b>
                                <input type="number" name="cant" id="cant" class="form-control form-control-sm">
                            </div>                            
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 col-sm-12">
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tipo de población</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px;">
                    <div class="table-responsive">
                        <table class="table" id="tablaPoblacion">
                            <thead>
                                <tr>
                                    <th scope="col" colspan="2">Tipo de Población</th>
                                    <th scope="col">Hombres</th>
                                    <th scope="col">Mujeres</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnGuardarGrupo">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>


   <!--  <div id="modalBtnGrupo" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tipo de población</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px; background: antiquewhite;">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover" id="tablaPoblacion">
                            <thead>
                                <tr>
                                    <th scope="col" colspan="2">Tipo de Población</th>
                                    <th scope="col">Hombres</th>
                                    <th scope="col">Mujeres</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_body_poblacion">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer"  style="background-color:antiquewhite;">
                    <button type="button" class="btn btn-primary" id="btnGuardarGrupo">Aceptar</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div> -->

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

<div id="modal_lista_picking" class="modal fade myModalNuevoCliente"  role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title">Lista de Asignaciones</h4>
              <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-sm-3">
                    <input type="date" class="form-control" name="txtFechaAsign" id="txtFechaAsign" value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-sm-9 text-end">
                    <button onclick="lista_picking_all()" class="btn btn btn-primary">Buscar</button>                    
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-hover">
                         <thead>
                             <th>Beneficiario</th>
                             <th></th>
                         </thead>
                         <tbody id="tbl_body_asignacion">
                             
                         </tbody>
                        
                    </table>
                    
                </div>                
            </div>
          </div>
          <div class="modal-footer" style="background-color:antiquewhite;">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">OK</button>
          </div>
      </div>
  </div>
</div>

