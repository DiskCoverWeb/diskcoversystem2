<?php  $tipo = ''; if(isset($_GET['tipo'])){$tipo = $_GET['tipo'];}?>
<script type="text/javascript">
    var TipoProveedor = '<?= echo $tipo; ?>'
</script>
<script src="../../dist/js/FProveedores.js"></script>
<form id="form_nuevo_proveedor">
    <div class="panel">
        <div class="panel-body" style="background-color:antiquewhite;">
            <div class="row">
                <div class="col-sm-9">
                    <div class="row">
                        <div class="col-sm-4 col-xs-4">
                            <b>CI / RUC</b>
                            <input type="text" id="txt_ruc" name="txt_ruc"  style="z-index:auto" class="form-control input-sm">              
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-1">
                            <br>
                            <button type="button" class="btn btn-sm" onclick="validar_sriC($('#txt_ruc').val())">
                                <img src="../../img/png/SRI.jpg" style="width: 100%">
                            </button>                    
                        </div>        
                        <div class="col-xs-6 col-sm-6">
                            <b>Nombre de proveedor</b>
                            <input type="hidden" id="txt_id_prove" name="txt_id_prove" class="form-control input-sm">  
                            <div class="input-group">
                                <input type="text"  style="z-index:auto"  id="txt_nombre_prove" name="txt_nombre_prove" class="form-control input-sm" onkeyup="limpiar_t();mayusculasevent(this)" onblur="nombres(this.value)">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat btn-sm" title="Sucursales" onclick="mostrar_ingreso_sucursal()"><i class="fa fa-building-o"></i>&nbsp;<i class="fa fa-plus"></i></button>
                                </span>
                            </div>
                        </div>                      
                    </div>
                    <div class="row">
                        <div class="col-sm-3 col-xs-6">
                            <b>Abreviado del donante</b>
                            <input type="" style="z-index:auto" name="txt_ejec" id="txt_ejec" class="form-control input-sm" onkeyup="mayusculasevent(this)" onblur="validar_abrev()">
                        </div>
                        <div class="col-sm-9 col-xs-6">
                            <b>Tipo de proveedor / Donante</b>
                            <select class="form-control input-xs" id="txt_actividad" name="txt_actividad">
                            </select>
                            <!-- <input type="" name="txt_actividad" id="txt_actividad" class="form-control input-sm form-select"  onkeyup="mayusculasevent(this)"> -->
                        </div>          
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                        <b>Direccion</b>
                        <input type="text" id="txt_direccion" name="txt_direccion" class="form-control input-xs"  onkeyup="mayusculasevent(this)">  
                        </div>        
                    </div>
                    <div class="row">
                        <div class="col-sm-8 col-xs-8">
                            <b>Email</b>
                            <input type="text" id="txt_email" name="txt_email" class="form-control input-sm">  
                        </div> 
                        <div class="col-sm-4 col-xs-4">
                            <b>Telefono</b>
                            <input type="txt_telefono" id="txt_telefono" name="txt_telefono" class="form-control input-sm" >              
                        </div> 
                    </div>
                        <div class="row">
                            <div class="col-sm-8 col-xs-8">
                            <b>Email 2</b>
                            <input type="text" id="txt_email2" name="txt_email2" class="form-control input-sm">  
                            </div> 
                        <div class="col-sm-4 col-xs-4">
                            <div class="row">
                                <div class="col-sm-8">
                                    <b>Tipo Proveedor</b> 
                                    <select class="form-control input-xs" id="CTipoProv" name="CTipoProv">
                                        <option value="00" selected >OTROS</option>
                                        <option value="01">PERSONA NATURAL</option>
                                        <option value="02">SOCIEDAD</option>
                                    </select>
                                </div>
                                <div class="col-sm-4" style="padding-left: 0px;">
                                    <b>Parte Relacionada</b>
                                    <select class="form-control input-xs" id="CParteR" name="CParteR">
                                        <option value="NO">NO</option>
                                        <option value="SI">SI</option>
                                    </select>
                                </div>
                            </div>         
                        </div> 
                    </div>                
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <br>
                            <?php $modulo = str_replace(' ', '',$_SESSION['INGRESO']['modulo_']); ?>
                            <button type="button" class="btn btn-sm btn-primary" onclick="guardar_proveedor()">Guardar</button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="eliminar_proveedor()">Eliminar</button>
                            <a type="button" class="btn btn-sm btn-default" href="../vista/inicio.php?mod=<?php echo($modulo);?>">Cerrar</a>
                        </div>
                    </div>
                    <div class="row" id="pnl_sucursal" style="display:none">
                        <div class="col-sm-12">                        
                        <hr>
                            <h3>Sucursales</h3>
                        </div>
                            <div class=" col-xs-3 col-sm-4 col-md-3">
                            <b>Codigo Sucursal</b>
                            <input type="" name="txt_cod_sucursal" id="txt_cod_sucursal" class="form-control input-sm">
                        </div>
                        <div class=" col-xs-6 col-sm-4 col-md-7">
                            <b>Direccion</b>
                            <input type="" name="txt_sucursal_dir" id="txt_sucursal_dir" class="form-control input-sm">
                        </div>
                        
                        <div class=" col-xs-3 col-sm-2 col-md-2 text-right">
                            <br>
                            <button type="button"  class="btn btn-primary" onclick="add_sucursal()">Guardar sucursal</button> 
                        </div> 
                        <div class="col-sm-12 col-xs-12">
                            <table class="table table-hover text-sm">
                                <thead>
                                    <th>Direccion</th>
                                    <th>TP</th>
                                    <th></th>
                                </thead>
                                <tbody id="tbl_sucursales">
                                </tbody>
                            </table>
                        </div>                 
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="text-left LblSRI" style="background-color:rgb(226 251 255)">
                        Datos SRI Proveedor.
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- </div> -->
