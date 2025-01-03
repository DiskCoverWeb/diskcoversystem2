<script src="../../dist/js/asignacion_os.js"></script>
<style>
    label {
        text-align: left;
    }

    input,
    select {
        width: 98%;
        max-width: 98%;
        text-align: left;
    }

    .form-group {
        padding: 0;
        display: flex;
        align-items: center;
        margin-bottom: 2px;
        /* Alinea los elementos verticalmente en el centro */
    }

    .form-group label {
        flex-basis: auto;
        flex-grow: 0;
        /* No permite que el label crezca */
        flex-shrink: 0;
        /* No permite que el label se encoja */
        margin-right: 10px;
        /* Añade un poco de espacio entre el label y el input */
    }

    /* Ajustar el ancho de los inputs si es necesario */
    .form-group input[type="text",type="datetime-local"] {
        flex-grow: 1;
        /* Permite que el input crezca para ocupar el espacio disponible */
    }

    .alineado {
        padding-top: 1vh;
    }

    @media(max-width: 768px) {
        #container-estado {
            padding: 0vw 3vw 0vw 3vw !important;
        }

        .centrar {
            text-align: center;
        }
    }
</style>
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
    // function eliminar_beneficiario(){
    //     beneficiario = $('#beneficiario').val();
    //     if(beneficiario=='' || beneficiario==null)
    //     {
    //         Swal.fire('Seleccione un beneficiario','','error');
    //         return false;
    //     }
    // }

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
<div class="row row-cols-auto gx-3 pb-2 d-flex align-items-center ps-2">
    <div class="row row-cols-auto btn-group">
      <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
					print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
            <img src="../../img/png/salire.png">
      </a>
      <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Guardar" onclick="guardar()">
        <img src="../../img/png/grabar.png">
      </button>
    </div>
</div>
<form id="form_asignacion" class="mb-2">
    <div class="row px-1 border border-1 pb-2" style="background-color: #fffacd;" id="rowGeneral">
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><b>Día Entrega</b></span>
                    <select class="form-select form-select-sm" id="diaEntr">
                        <option value="Lun">Lunes</option>
                        <option value="Mar">Martes</option>
                        <option value="Mie">Miercoles</option>
                        <option value="Jue">Jueves</option>
                        <option value="Vie">Viernes</option>
                        <option value="Sáb">Sabado</option>
                        <option value="Dom">Domingo</option>
                    </select>
                </div>
                <!--<div class="row align-items-center">                    
                    <div class="col-auto">  
                        <label for="diaEntr" class="col-form-label"><b>Día Entrega</b></label>
                    </div>
                    <div class="col-auto">
                        
                    </div>
                </div>-->
            </div>
            <div class="col-auto">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><b>Beneficiario</b></span>
                    <select name="beneficiario" id="beneficiario" style="min-width:130px;max-width:150px;" class="form-select form-select-sm" onchange="listaAsignacion()"></select>
                    <button type="button" class="btn btn-outline-secondary" onclick="add_beneficiario()">
                        <img id="img_tipoCompra"  src="../../img/png/mostrar.png" style="width: 20px;" />
                    </button>
                    <!--<span class="input-group-text">
                    </span>-->
                    <button type="button" class="btn btn-outline-secondary" onclick="eliminar_asignacion_beneficiario()">
                        <img id="img_tipoCompra"  src="../../img/png/close.png" style="width: 20px;" />
                    </button>
                    <!--<span class="input-group-btn">
                    </span>-->
                </div>
                <!--<div class="row align-items-center">
                    <div class="col-auto">
                        <label for="diaEntr" class="col-form-label"><b>Beneficiario/ Usuario:</b></label>
                    </div>              
                    <div class="col-auto">  
                    </div>
                </div>-->
            </div>           
            <div class="col-auto">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fa fa-calendar"></i> <b>Fecha de Atención:</b></span>
                    <input type="date" name="fechAten" id="fechAten" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>
            <div class="col-auto">
                <div class="input-group input-group-sm">                          
                    <button type="button" class="btn btn-outline-secondary" onclick="onclicktipoCompra()">
                        <img id="img_tipoCompra"  src="../../img/png/TipoCompra.png" style="width: 20px;" />
                    </button>
                    <select name="tipoCompra" id="tipoCompra" class="form-select form-select-sm" style="min-width: 120px;" onchange="autocoplet_pro2()">
                    </select>
                </div>
            </div>

        </div>
        <!--<div class="row">
            <div class="col-sm-2">
                <div class="row">                    
                    <div class="col-md-12 col-sm-6 col-xs-6" style="padding-right: 0px;">  
                        <div class="input-group">
                            <div class="input-group-addon input-xs">
                                <b>Día Entrega</b>
                            </div>
                            <select class="form-control input-xs" id="diaEntr">
                                <option value="Lun">Lunes</option>
                                <option value="Mar">Martes</option>
                                <option value="Mie">Miercoles</option>
                                <option value="Jue">Jueves</option>
                                <option value="Vie">Viernes</option>
                                <option value="Sáb">Sabado</option>
                                <option value="Dom">Domingo</option>
                            </select>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="row">                   
                     <div class="col-md-12 col-sm-6 col-xs-6">  
                        <div class="input-group">
                            <div class="input-group-addon input-xs">
                                <b>Beneficiario/ Usuario:</b>
                            </div>
                             <select name="beneficiario" id="beneficiario" class="form-control input-xs" onchange="listaAsignacion()"></select>
                             <span class="input-group-btn">
                            <button type="button" class="" onclick="add_beneficiario()">
                                <img id="img_tipoCompra"  src="../../img/png/mostrar.png" style="width: 20px;" />
                            </button>
                        </span>
                        <span class="input-group-btn">
                            <button type="button" class="" onclick="eliminar_asignacion_beneficiario()">
                                <img id="img_tipoCompra"  src="../../img/png/close.png" style="width: 20px;" />
                            </button>
                        </span>
                        </div>
                    </div>
                </div>
            </div>           
            <div class="col-sm-3">
                <div class="row">                   
                    <div class="col-md-12 col-sm-6 col-xs-6">  
                        <div class="input-group">
                            <div class="input-group-addon input-xs">                   
                                <i class="fa fa-calendar"></i>
                                <b>Fecha de Atención:</b>
                            </div>
                            <input type="date" name="fechAten" id="fechAten" class="form-control input-xs" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                </div>
            </div>
             <div class="col-sm-2">
                 <div class="row">
                    <div class="col-md-12 col-sm-6 col-xs-6">  
                        <div class="input-group">                          
                        <span class="input-group-btn">
                            <button type="button" class="" onclick="onclicktipoCompra()">
                                <img id="img_tipoCompra"  src="../../img/png/TipoCompra.png" style="width: 20px;" />
                            </button>
                        </span>
                         <select name="tipoCompra" id="tipoCompra" class="form-control input-xs" onchange="autocoplet_pro2()">
                         </select>
                        </div>
                    </div>
                </div>
            </div>

        </div>-->
        <div class="row g-2 align-items-center">
            <div class="col-sm-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><b>Estado</b></span>
                    <input type="tipoEstado" name="tipoEstado" id="tipoEstado" class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><b>Tipo Entrega</b></span>
                    <input type="text" name="tipoEntrega" id="tipoEntrega" class="form-control form-control-sm">
                </div>
                
            </div>
            <div class="col-sm-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><b><i class="fa fa-clock-o"></i> Hora de Entrega</b></span>
                    
                    <input type="time" name="horaEntrega" id="horaEntrega" class="form-control form-control-sm">
                </div>
                
            </div>
            <div class="col-sm-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><b>Frecuencia</b></span>
                    
                    <input type="text" name="frecuencia" id="frecuencia" class="form-control form-control-sm">
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-6 col-xs-6">  
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-2 align-items-center">
            <div class="col-sm-4">
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
                <!-- <div class="row">                   
                    <div class="col-md-12 col-sm-6 col-xs-6">  
                        <div class="input-group">
                            <div class="input-group-addon input-xs">
                                <b>Tipo de Población:</b>
                            </div>
                            <input type="text" name="tipoPobl" id="tipoPobl" class="form-control input-xs" readonly>
                        </div>
                    </div>
                </div> -->
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
            <div class="col-sm-4">
                <div class="row align-items-center">
                    <div class="col-sm-6"  style="font-size: 13px; ">
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
                        <label for="CantGlobDist" style="font-size: 13px; white-space: nowrap;">
                            <b>Cantidad global a distribuir</b>
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <input type="number" name="CantGlobDist" id="CantGlobDist" style=""
                            class="form-control form-control-sm" readonly>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-sm-6 d-flex gap-2"  style="font-size: 13px;">
                        <img  src="../../img/png/info_nutricional.png" style="width: 25%;" />
                        <b>Información Nutricional</b>
                        
                        
                        
                    </div>
                    <div class="col-sm-6">
                        <textarea name="infoNutr" id="infoNutr" rows="3" class="form-control form-control-sm" placeholder="">
                        </textarea>
                        
                    </div>
                </div>
            </div>
            <div class="col-sm-4 h-90">
                <!--<div class="row centrar">
                    <label for="comeGeneAsig">
                        Comentario General de Asignación
                    </label>
                </div>-->
                <div class="row h-100 d-flex">
                    <div class="col-10 pe-0 form-floating">
                        <textarea class="form-control form-control-sm h-100" placeholder="comentario general de clasificación..." id="comeGeneAsig" name="comeGeneAsig" rows="5" style="resize: none;height:80px"></textarea>
                        <label for="comeGeneAsig" class="ps-4 fw-medium">Comentario General de Asignación</label>
                    </div>
                    <div class="col-2 ps-0 align-self-end">
                        <button type="button" class="btn btn-success btn-sm" onclick=""><i class="fa fa-save"></i>
                        </button>
                    </div>
                    <!--<div class="input-group">
                        <textarea name="comeGeneAsig" id="comeGeneAsig" rows="5"
                            placeholder="comentario general de clasificación..." class="form-control">
                            </textarea>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-primary btn-sm" onclick=""><i class="fa fa-save"></i>
                            </button>
                        </span>
                    </div>-->
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="padding-top: 1rem">
        
        <div class="col-sm-2 pe-0">
            <button type="button" class="btn btn-outline-secondary w-100" onclick="show_producto();"><img
           src="../../img/png/Grupo_producto.png" /> <br> <b>Grupo producto</b></button>
            
        </div>
        <div class="col-sm-3">
            <b>Grupo producto:</b>
             <select name="grupProd" id="grupProd" class="form-select form-select-sm" onchange="buscar_producto(this.value)"></select>

        </div>
        <div class="col-sm-1">
            <label for="stock">
                <b>Stock</b>
            </label>
            <input type="text" name="stock" id="stock" class="form-control form-control-sm" readonly>
        </div>
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
        <div class="col-sm-3">
            <label for="comeAsig">
                <b>Comentario de Asignación</b>
            </label>
            <input type="text" name="comeAsig" id="comeAsig" class="form-control form-control-sm">
        </div>
    </div>
    <div class="row g-2 d-flex justify-content-end" style="text-align: right; padding-right: 1.5vw;">
        <button type="button" class="btn btn-primary btn-sm me-2 px-2" onclick="agregar();" style="width: fit-content;"><b>Agregar</b></button>
        <button type="button" class="btn btn-primary btn-sm px-2" onclick="limpiar();" style="width: fit-content;"><b>Limpiar</b></button>
    </div>
</form>

<div class="row" id="panel_add_productos"><!-- DEFINIR EL ID SEGUN SEA NECESARIO -->
    <div class="col-sm-12">
        <div class="box">
            <div class="card_body" style="background:antiquewhite;">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-hover" style="width:100%">
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
<br><br>

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
                    <h4 class="modal-title">Tipo de población</h4>
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: 300px; background: antiquewhite;">
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
              <button type="button" class="btn btn-primary" onclick="cambiar_empaque()">OK</button>
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

