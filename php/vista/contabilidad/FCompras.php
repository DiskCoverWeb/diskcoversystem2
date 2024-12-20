<?php
date_default_timezone_set('America/Guayaquil');  
$prv ='.';
$ben = '.';
$fec = date('Y-m-d');
$tipo2 = ''; if(isset($_GET['tipo']) && $_GET['tipo']!=''){$tipo2 = 1;}
if(isset($_GET['prv']))
{
	$prv = $_GET['prv'];
}
if(isset($_GET['ben']))
{
	$ben = $_GET['ben'];
} 
if(isset($_GET['fec']))
   $fec = $_GET['fec'];
?>
<style type="text/css">
    button:focus {
    box-shadow: 0 0 0 0px white, 0 0 0 2px black;
 }
  input[type=text]:focus {
    box-shadow: 0 0 0 0px white, 0 0 0 2px black;
 }
 input[type=date]:focus {
    box-shadow: 0 0 0 0px white, 0 0 0 2px black;
 }
 input[type=radio]:focus {
    box-shadow: 0 0 0 0px white, 0 0 0 2px black;
 }
 input[type=checkbox]:focus {
    box-shadow: 0 0 0 0px white, 0 0 0 2px black;
 }
 select :active {
    box-shadow: 0 0 0 0px white, 0 0 0 2px black;
}
.input-xs:focus
{
    box-shadow: 0 0 0 0px white, 0 0 0 2px black;
}
</style>
<script src="../../dist/js/kardex_ing.js"></script>
<script type="text/javascript">
    var tipo2 = '<?php echo $tipo2; ?>'
</script>
<script src="../../dist/js/FCompras.js">
<script type="text/javascript">
    Ini_Iva = '<?php echo $_SESSION['INGRESO']['porc']*100; ?>';
    console.log(Ini_Iva)
</script>
<script type="text/javascript">
  $(document).ready(function()
  {
    $('#footerInfo').addClass('d-none');
    $('#switcher').addClass('d-none');
    $('#ChRetB').focus();    
    $('#myModal_espera').modal('show');
    familias();
    contracuenta();
    Trans_Kardex();
    bodega();
    marca();
    var ben = '<?php echo $ben;?>';
    var fecha = '<?php echo $fec;?>';
    codigo_beneficiario('<?php echo $prv; ?>');
    DCBenef_LostFocus(ben,'','');
    ddl_DCSustento(fecha);
    ddl_DCConceptoRet(fecha);
    ddl_DCPorcenIce(fecha);
    ddl_DCPorcenIva(fecha);
    $('#MBFechaEmi').val(fecha);
    $('#MBFechaRegis').val(fecha);
    $('#MBFechaCad').val(fecha);
    if(tipo2!='')
    {
        autocoplet_bene();
        Tipo_De_Comprobante_No();
    }
    
  });
</script>

<div class="overflow-auto p-2">
  <div class="row">
      <?php if($tipo2!=''){?>
          <div class="col-sm-12 text-center">
            <h4  style="float: top;padding: 5px 10px 5px 10px;vertical-align:top; margin-top: 1px; margin-bottom: 1px;" id='num_com'>
              Comprobante de Diario No. 0000-00000000
            </h4>
          </div>
      <?php }?>
            <div class="col-sm-12">
                <div class="row">
                  <div class="col-sm-8" style="margin-bottom: auto;">
                      <div class="box box-info" style="margin-bottom: auto;">
                          <div class="box-header" style="padding:0px">
                              <h3 class="box-title">Retencion de IVA por</h3>
                          </div>
                          <div class="box-body" style="padding-top: 0px;">
                              <div class="row">
                                  <div class="col-sm-3">
                                    <input type="hidden" name="txt_opc_mult" id="txt_opc_mult" value="<?php if(isset($_GET['opc_mult'])){ echo $_GET['opc_mult']; }?>">
                                      <label class="form-check-label" onclick="habilitar_bienes()"><input class="form-check-input" type="checkbox" name="ChRetB" id="ChRetB"> Bienes</label>
                                  </div>
                                  <div class="col-sm-9">
                                      <select class="form-select form-select-sm" id="DCRetIBienes" style="display:none; width: 100%;">
                                          <option>Seleccione Tipo Retencion</option>
                                      </select>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-sm-3">
                                      <label class="form-check-label" onclick="habilitar_servicios()"><input class="form-check-input" type="checkbox" name="ChRetS" id="ChRetS"> Servicios</label>
                                  </div>
                                  <div class="col-sm-9">
                                      <select class="form-select form-select-sm" id="DCRetISer" style="display: none; width: 100%;">
                                          <option>Seleccione Tipo Retencion</option>
                                      </select>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="col-sm-4 text-center">
                    <button class="btn btn-outline-secondary btn-sm" id="btn_g" tabindex="15"> <img src="../../img/png/grabar.png"  onclick="validar_formulario();"><br> Guardar</button>
                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> -->
                    <button class="btn btn-outline-secondary btn-sm" data-dismiss="modal" onclick="limpiar_retencion();"> <img src="../../img/png/bloqueo.png" ><br> Cancelar</button>

                    <!-- <button class="btn btn-default" onclick="pdf_retencion();"> <img src="../../img/png/bloqueo.png" ><br> pdf</button> -->

                  </div>            
              </div>
              <div class="row">
                <div class="col-12 col-sm-8">
                  <b>TIPO DE PROVEEDOR: </b><span id="agente"></span>
                  <?php if($tipo2!=''){ ?>
                      <!-- <div class="input-group"> -->
                        <select class="form-select form-select-sm" id="DCProveedor" onchange="datos_pro()">
                          <option value="">No seleccionado</option>
                        </select>
                      <button type="button" class="btn btn-success btn-sm" onclick="addCliente()" title="Nuevo cliente">
                        <span class="fa fa-user-plus"></span>
                      </button>   
                      <!-- </div> -->
                  <?php }else{ ?>
                      <select class="form-select form-select-sm" id="DCProveedor">
                            <option value="">No seleccionado</option>
                          </select>
                  <?php } ?>
                </div>
                <div class="col-12 col-sm-1"><br>
                  <input type="text" class="form-control form-control-sm" name="" id="LblTD" style="color: red" readonly="">
                </div>
                <div class="col-12 col-sm-3">
                <b>Estado:</b><span id="lbl_estado"></span>              
                  <input type="text" class="form-control form-control-sm" name="" id="LblNumIdent" readonly="">
                  <input type="hidden" class="form-control form-control-sm" name="" id="txtemail" readonly="">
                </div>
              </div>
            </div><br>
            <div class="col-sm-12">
              <ul class="nav nav-pills">
                <li class="nav-item">
                  <a class="nav-link active" data-bs-toggle="tab" href="#home">Comprobante de compra: FORMULARIO 104</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-bs-toggle="tab" href="#menu1">Conceptos AIR</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-bs-toggle="tab" href="#menu2">Partidos politicos</a>
                </li>
              </ul>
                <!-- Tab panes -->
              <div class="tab-content p-1">
                <div class="tab-pane modal-body active border-5" id="home" role="tabpanel">
                  <div class="row">
                    <div class="col-sm-10">
                      <div class="row">
                        <div class="col-sm-4 text-sm">
                          <b>Devolucion del IVA:</b>
                        </div>
                        <div class="col-sm-8">
                          <label class="form-check-label"><input class="form-check-input" type="radio" name="cbx_iva" id="iva_si" value="S"> SI</label>
                          <label class="form-check-label"><input class="form-check-input" type="radio" name="cbx_iva" id="iva_no" value="N" checked=""> NO</label>
                        </div>                                    
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <b>Tipo de sustento Tributario</b>
                          <select class="w-100 form-select form-select-sm" id="DCSustento" onchange="ddl_DCTipoComprobante('<?php echo $fec?>');ddl_DCDctoModif();" onblur="cambiar_fecha_sus();ddl_DCTipoComprobante('<?php echo $fec?>') ">
                            <option value="">seleccione sustento </option>
                          </select>
                        </div>                                    
                      </div>
                    </div>
                    <div class="col-sm-2">
                        <br>
                        <button class="btn btn-outline-secondary text-center" onclick="cambiar_air()" id="btn_air"><i class="fa fa-arrow-right"></i><br>AIR</button>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12 pt-2">
                      <div class="box box-info mb-auto">
                        <div class="box-header p-0 border-top border-3 border-primary">
                          <h5 class="box-title"><b>INGRESE LOS DATOS DE LA FACTURA, NOTA DE VENTA, ETC</b></h5>
                        </div>
                        <div class="box-body" style="padding-top:0px">
                          <div class="row">
                            <div class="col-sm-5">
                              <b>Tipo de comprobate</b>
                              <select class="form-select form-select-sm" id="DCTipoComprobante" onchange="mostrar_panel()" onblur="selec_tipo_comp()">
                                  <option value="1">Factura</option>
                              </select>
                            </div>
                            <div class="col-sm-2">
                              <b>Serie</b>
                              <div class="row">
                                  <div class="col-sm-6 p-0">
                                      <input type="text" name="" class="form-control form-control-sm" id="TxtNumSerieUno" placeholder="001" onblur="autocompletar_serie_num_fac(this.id)" onkeyup=" solo_3_numeros(this.id);solo_numeros(this)" autocomplete="off">
                                  </div>
                                  <div class="col-sm-6 p-0">
                                      <input type="text" name="" class="form-control form-control-sm" id="TxtNumSerieDos" placeholder="001" onblur="autocompletar_serie_num_fac(this.id)" onkeyup=" solo_3_numeros(this.id);solo_numeros(this)"  autocomplete="off">
                                  </div>
                              </div>                                
                            </div>
                            <div class="col-sm-2">
                              <b>Numero</b>
                              <input type="text" name="" class="form-control form-control-sm" id="TxtNumSerietres" onblur="validar_num_factura(this.id)" placeholder="000000001" onkeyup="solo_9_numeros(this.id);solo_numeros(this)"  autocomplete="off">
                            </div>
                            <div class="col-sm-3">
                              <b>Autorizacion</b>
                              <input type="text" name="" class="form-control form-control-sm text-right" id="TxtNumAutor" onblur="autorizacion_factura();solo_numeros(this);validar_cantidad_numeros_blur(this,9,49)" onkeyup="solo_numeros(this)" placeholder="0000000001"  autocomplete="off"> <!--onkeyup="solo_10_numeros(this.id)"-->
                            </div>
                          </div>
                          <div class="row ms-2">
                            <div class="row col-sm-12">
                              <div class="row col-sm-6">
                                <div class="col-sm-4 ps-0 pe-0">
                                  <b>Emision</b>
                                  <input type="date" name="" class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>" id="MBFechaEmi" onblur="fecha_valida(this);ddl_DCPorcenIva(this.value)" autocomplete="off" />
                                </div>
                                <div class="col-sm-4 ps-0 pe-0">
                                  <b>Registro</b>
                                  <input type="date" name="" class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>" id="MBFechaRegis" onblur="validar_fecha();fecha_valida(this)"  autocomplete="off">
                                </div>                                            
                                <div class="col-sm-4 ps-0 pe-0">
                                  <b>Caducidad</b>
                                  <input type="date" name="" class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>" id="MBFechaCad"  autocomplete="off" onblur="fecha_valida(this)">
                                </div>
                              </div>
                              <div class="col-sm-2">
                                <b>No Obj. IVA</b>
                                <input type="text" name="" class="form-control form-control-sm text-right" value="0.00" id="TxtBaseImpoNoObjIVA"  autocomplete="off" onblur="base_impo_cal();validar_float(this,2)" onkeyup="validar_numeros_decimal(this)">
                              </div>
                              <div class="col-sm-1 ps-2 pe-2">
                                <b>Tarifa 0</b>
                                <input type="text" name="" class="form-control form-control-sm text-right" value="0.00" id="TxtBaseImpo"  autocomplete="off"  onblur="base_impo_cal();validar_float(this,2)" onkeyup="validar_numeros_decimal(this)">
                              </div>
                              <div class="col-sm-1 ps-2 pe-2">
                                <b>Tarifa 12</b>
                                <input type="text" name="" class="form-control form-control-sm text-right" value="0.00" id="TxtBaseImpoGrav"  autocomplete="off"  onblur="base_impo_cal();validar_float(this,2)" onkeyup="validar_numeros_decimal(this)">
                              </div>
                              <div class="col-sm-2">
                                <b>Valor ICE</b>
                                <input type="text" name="" class="form-control form-control-sm  text-right" value="0.00"  id="TxtBaseImpoIce"  onblur="validar_float(this,2)" autocomplete="off" onkeyup="validar_numeros_decimal(this)">
                              </div>  
                            </div>                          
                          </div>
                        </div>
                      </div>
                    </div>            
                  </div> 
                  <div class="row p-2">
                    <div class="col-sm-6 p-1 border-top border-primary border-3">
                      <div class="box box-info">
                        <div class="box-header" style="padding:0px">
                          <h5 class="box-title">Porcentajes de las bases Imponibles</h5>
                        </div>
                        <div class="box-body">
                          <div class="row">
                            <div class="col-sm-1">
                              IVA
                            </div>
                            <div class="col-sm-4">
                              <select class="form-control form-control-sm" id="DCPorcenIva" onchange="calcular_iva()" onblur="calcular_iva();calcular_ice();">
                                  <option value="I">Iva</option>
                              </select>
                            </div>
                            <div class="col-sm-3">
                              Valor I.V.A
                            </div>
                            <div class="col-sm-4">
                              <input type="text" name="" class="form-control form-control-sm  text-right" id="TxtMontoIva" value="0"  autocomplete="off" onblur="validar_float(this,2)" onkeyup="validar_numeros_decimal(this)">
                            </div>                            
                          </div>
                          <div class="row">
                            <div class="col-sm-1">
                              ICE
                            </div>
                            <div class="col-sm-4">
                              <select class="form-control input-xs" id="DCPorcenIce" onchange="calcular_ice()" onblur="calcular_iva();calcular_ice();">
                                  <option value="0">ICE</option>
                              </select>
                            </div>
                            <div class="col-sm-3">
                              Valor ICE
                            </div>
                            <div class="col-sm-4">
                              <input type="text" name="" class="form-control form-control-sm text-right" id="TxtMontoIce"  value="0.00" readonly=""  autocomplete="off" onblur="validar_float(this,2)" onkeyup="validar_numeros_decimal(this)">
                            </div>       
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6 p-1" style="border-top: 3px solid #FFA500;">
                      <div class="box box-warning" style="margin-bottom:0px">
                        <div class="box-header" style="padding:0px">
                          <h5 class="box-title">Retencion del IVA por Bienes Y/O Servicios </h5>
                        </div>
                        <div class="box-body"style="padding-top: 0px;" >
                          <div class="row">
                            <div class="col-sm-4"><br>
                              Monto
                            </div>
                            <div class="col-sm-4">
                              <b>BIENES</b>
                              <input type="text" name="" class="form-control form-control-sm text-right" id="TxtIvaBienMonIva" readonly="" value="0"  autocomplete="off" onkeyup="validar_numeros_decimal(this)" onblur="validar_float(this,2)">
                            </div>                            
                            <div class="col-sm-4">
                              <b>SERVICIOS</b>
                              <input type="text" name="" class="form-control form-control-sm text-right" id="TxtIvaSerMonIva" readonly="" value="0"  autocomplete="off" onkeyup="validar_numeros_decimal(this)" onblur="validar_float(this,2)">
                            </div>       
                          </div>
                          <div class="row">
                            <div class="col-sm-4">
                              Porcentaje
                            </div>
                            <div class="col-sm-4">
                              <select class="form-select form-select-sm" style="width:100%" id="DCPorcenRetenIvaBien" disabled="" onchange="calcular_retencion_porc_bienes()" onblur="calcular_retencion_porc_bienes()">
                                  <option value="0">0</option>
                              </select>
                            </div>                            
                            <div class="col-sm-4">
                              <select class="form-select form-select-sm" style="width:100%"  id="DCPorcenRetenIvaServ" disabled="" onchange="calcular_retencion_porc_serv()" onblur="calcular_retencion_porc_serv()">
                                <option value="0">0</option>
                              </select>
                            </div>       
                          </div>
                          <div class="row">
                            <div class="col-sm-4">
                              Valor RET
                            </div>
                            <div class="col-sm-4">
                              <input type="text" name="" class="form-control form-control-sm text-right" id="TxtIvaBienValRet" value="0" autocomplete="off" readonly>
                            </div>                            
                            <div class="col-sm-4">
                              <input type="text" name="" class="form-control form-control-sm text-right" id="TxtIvaSerValRet" value="0" autocomplete="off" readonly>
                            </div>       
                          </div>
                        </div>
                      </div>
                    </div>            
                  </div>
                  <div class="row" id="panel_notas" style="display: none">
                    <div class="col-sm-12">
                      <div class="box box-info">
                        <div class="box-header" style="padding:0px">
                          <h5 class="box-title"><b>NOTAS DE DEBITO / NOTAS DE CREDITO</b></h5>
                        </div>
                        <div class="box-body" style="padding-top:0px">
                          <div class="row">
                            <div class="col-sm-4">
                              <b>tipo de comprobate</b>
                              <select class="form-select form-select-sm" id="DCDctoModif">
                                <option>Seleccione tipo de comprobante</option>
                              </select>
                            </div>
                            <div class="col-sm-2">
                              <b>Serie</b>
                              <div class="row">
                                <div class="col-sm-6" style="padding: 0px">
                                  <input type="text" name="" class="form-control form-control-sm" id="TxtNumSerieUnoComp" placeholder="001" onblur="autocompletar_serie_num(this.id)" onkeyup="solo_3_numeros(this.id);solo_numeros(this)"  autocomplete="off">
                                </div>
                                <div class="col-sm-6" style="padding: 0px">
                                  <input type="text" name="" class="form-control form-control-sm" id="TxtNumSerieDosComp" placeholder="001" onblur="autocompletar_serie_num(this.id)" onkeyup="solo_3_numeros(this.id);solo_numeros(this)"  autocomplete="off">
                                </div>
                              </div>                                
                            </div>
                            <div class="col-sm-1" style="padding-left: 5px;padding-right: 5px">
                              <b>Numero</b>
                              <input type="text" name="" class="form-control form-control-sm" id="CNumSerieTresComp" onkeyup="solo_9_numeros(this.id);solo_numeros(this)" onblur="validar_num_factura(this.id)" placeholder="000000001"  autocomplete="off">
                            </div>
                            <div class="col-sm-2" style="padding-left: 5px;padding-right: 5px">
                              <b>Fecha</b>
                              <input type="date" name="" class="form-control form-control-sm" id="MBFechaEmiComp"  autocomplete="off">
                            </div>
                            <div class="col-sm-3" style="padding-right: 5px;">
                              <b>Autorizacion sri</b>
                              <input type="text" name="" class="form-control form-control-sm" id="TxtNumAutComp"  autocomplete="off" onkeyup="solo_numeros(this)">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>            
                  </div> 
                </div>
                <div class="tab-pane modal-body fade" id="menu1" style="padding-top:0px" >
                    <div class="row">
                      <div class="col-sm-4">
                        <b>Forma de pago</b>
                        <select class="form-select form-select-sm" style="width: 100%;" onchange="mostrar_panel_ext()" id="CFormaPago" tabindex="1">
                          <option value="">Seleccione forma de pago</option>
                          <option value="1" selected="">Local</option>
                          <option value="2">Exterior</option>
                        </select>
                      </div>
                      <div class="col-sm-8">
                        <b>Tipo de pago</b>
                        <select class="form-select form-select-sm" style="width: 100%;" id="DCTipoPago" onchange="$('#DCTipoPago').css('border','1px solid #d2d6de');" tabindex="2">
                          <option value="">Seleccione tipo de pago</option>
                        </select>                    
                      </div>
                    </div>
                    <div class="row" id="panel_exterior" style="display: none;">
                      <div class="col-sm-4">
                        <b>Pais al que se efectua el pago</b>
                        <select class="form-select form-select-sm" id="DCPais">
                          <option>Seleccione Pais</option>
                        </select>
                      </div>
                      <div class="col-sm-6"><br>
                        Aplica convenio de doble tributacion?                   
                        <br>
                        Pago sujeto a retencion en aplicacion de la forma legal?
                        <br>
                      </div>
                      <div class="col-sm-2 text-right"><br>
                        <label class="form-check-label"><input class="form-check-input" type="radio" name="rbl_convenio" checked="" value="SI">SI</label>
                        <label class="form-check-label"><input class="form-check-input" type="radio" name="rbl_convenio" value="NO">NO</label>
                        <br>
                        <label class="form-check-label"><input class="form-check-input" type="radio" name="rbl_pago_retencion" checked="" value="SI">SI</label>
                        <label class="form-check-label"><input class="form-check-input" type="radio" name="rbl_pago_retencion" value="NO">NO</label>
                      </div>
                    </div>
                    <div class="row">
                          <div class="col-sm-12">
                              <div class="box box-info" style="margin-bottom: 0px;" >
                                  <div class="box-header" style="padding:0px">
                                      <h5 class="box-title"><b>INGRESE LOS DATOS DE LA RETENCION_________________FORMULARIO 103</b></h5>
                                  </div>
                                  <div class="box-body" style="padding-top:0px">
                                      <div class="row">
                                          <div class="col-sm-4">
                                            <label class="form-check-label" onclick="mostra_select()" id="lbl_rbl"><input class="form-check-input" type="checkbox" name="ChRetF" id="ChRetF" tabindex="3"> Retencion en la fuente</label>
                                          </div>
                                          <div class="col-sm-8">
                                            <select class="form-select form-select-sm" id="DCRetFuente" style="display: none; width: 100%" onchange="$('#DCRetFuente').css('border','1px solid #d2d6de');" tabindex="4">
                                              <option value=""> Seleccione Tipo de retencion</option>
                                            </select>
                                          </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-sm-2">
                                          Serie
                                          <div class="row">
                                            <div class="col-sm-6"style="padding-left: 0px;padding-right: 0px;">
                                              <input type="text" class="form-control form-control-sm" name="TxtNumUnoComRet" id="TxtNumUnoComRet" onkeyup="solo_3_numeros(this.id);solo_numeros(this)" placeholder="001" onblur="autocompletar_serie_num(this.id)"  autocomplete="off" tabindex="5">
                                            </div>
                                            <div class="col-sm-6"style="padding-left: 0px;padding-right: 0px;">
                                                  <input type="text" class="form-control form-control-sm" name="TxtNumDosComRet" id="TxtNumDosComRet" onkeyup="solo_3_numeros(this.id);solo_numeros(this)" placeholder="001" onblur="autocompletar_serie_num(this.id)"  autocomplete="off" tabindex="6">
                                              </div>
                                          </div>
                                        </div>
                                        <div class="col-sm-2">
                                          Numero
                                          <input type="text" class="form-control form-control-sm" name="TxtNumTresComRet" id="TxtNumTresComRet" onblur="validar_num_retencion()" onkeyup="solo_9_numeros(this.id);solo_numeros(this)" placeholder="000000001"  autocomplete="off" tabindex="7">
                                          <input type="hidden" name="val_num" id="val_num" value="0"  autocomplete="off">
                                        </div>
                                        <div class="col-sm-4">
                                          Autorizacion
                                          <input type="text" name="TxtNumUnoAutComRet" class="form-control form-control-sm" id="TxtNumUnoAutComRet" onblur="validar_autorizacion()" onkeyup="solo_numeros(this);"  autocomplete="off" tabindex="8">
                                        </div>
                                        <script type="text/javascript">                                            
                                        function selec_tipo_comp()
                                          {
                                            if($('#DCTipoComprobante').val()=='')
                                            {
                                            Swal.fire('Seleccione tipo de comprobante','','info').then(()=>{ $('#DCTipoComprobante').focus();});
                                            }

                                          }                                             
                                          function cambiar_fecha()
                                          {

                                            var sus = $('#DCSustento').val();
                                            if(sus=='00')
                                            { 
                                              if(Date.parse($('#MBFechaEmi').val()) <= Date.parse('2000-01-01')){
                                                $('#MBFechaRegis').val($('#MBFechaEmi').val());       
                                            }else{
                                                Swal.fire('La Fecha de Emisión debe ser menor a 2000-01-01','','info').then(()=>{ $('#MBFechaEmi').focus();});
                                                today = '2000-01-01';
                                                // console.log('2000/01/01');
                                                $('#MBFechaEmi').val(today);
                                            }

                                              

                                            }else
                                            {        
                                            var fecha = '<?php echo $fec;?>';                                            
                                                $('#MBFechaEmi').val(fecha);
                                                $('#MBFechaRegis').val($('#MBFechaEmi').val());
                                            }
                                            $('#MBFechaRegis').val($('#MBFechaEmi').val());
                                          }
                                          function cambiar_fecha_sus()
                                          {
                                            var sus = $('#DCSustento').val();
                                            if(sus=='00')
                                            {                                                   
                                                today = '2000-01-01';
                                                // console.log('2000/01/01');
                                                $('#MBFechaEmi').val(today);
                                                $('#MBFechaRegis').val($('#MBFechaEmi').val());

                                            }else
                                            {           
                                            var fecha = '<?php echo $fec;?>';                                         
                                                $('#MBFechaEmi').val(fecha);
                                                $('#MBFechaRegis').val($('#MBFechaEmi').val());
                                            }
                                          }
                                          function validar_fecha(){
                                          var fr = new Date($('#MBFechaRegis').val());
                                          var fe = new Date($('#MBFechaEmi').val());
                                          if(fr<fe)
                                          {
                                            Swal.fire('La Fecha de Registro debe ser mayor o igual que la Fecha de Emisión','','info');
                                            $('#MBFechaRegis').val($('#MBFechaEmi').val());
                                          }
                                        }
                                      </script>
                                        <div class="col-sm-4">
                                          <div class="row">
                                            <div class="col-sm-4"><br>
                                              SUMATORIA
                                            </div>
                                            <div class="col-sm-8"><br>
                                              <input type="text" name="" class="form-control form-control-sm text-right" id="TxtSumatoria"  autocomplete="off" tabindex="9" onkeyup="validar_numeros_decimal(this)" onblur="validar_float(this,2)" >
                                            </div>
                                          </div>                                      
                                        </div>                          
                                      </div>
                                      <div class="row">
                                        <div class="col-sm-7">
                                          <b>CODIGO DE RETENCION</b>
                                          <select class="form-select form-selec-sm" tabindex="10" id="DCConceptoRet" style="width:100%" name="DCConceptoRet" onchange="calcular_porc_ret()" onblur="calcular_porc_ret()">
                                            <option value="">Seleccione Codigo de retencion</option>
                                          </select>    
                                          <textarea class="form-control form-control-sm" rows="2" id="lbl_retencion_all_text" readonly></textarea>                                    
                                        </div>
                                        <div class="col-sm-2">
                                          <b>BASE IMP</b>
                                          <input type="text" class="form-control form-control-sm  text-right" name="TxtBimpConA" id="TxtBimpConA"  autocomplete="off" onblur="validar_base_impo();validar_float(this,2)" onkeyup="validar_numeros_decimal(this)" tabindex="11">
                                        </div>
                                        <div class="col-sm-1" style="padding-left: 0px;padding-right: 0px">
                                          <b>PORC</b>
                                          <input type="text" class="form-control form-control-sm text-right" name="TxtPorRetConA" id="TxtPorRetConA" onblur="insertar_grid()" readonly=""  autocomplete="off" tabindex="12">
                                        </div>
                                        <div class="col-sm-2">
                                          <b>VALOR RET</b>
                                          <input type="text" class="form-control form-control-sm text-right" name="TxtValConA" id="TxtValConA" readonly=""  autocomplete="off" tabindex="13">
                                        </div>
                                      </div>
                                  </div>
                              </div>
                          </div>            
                    </div>
                    <div class="row">
                      <div class="" >
                      <table class="Table text-center w-100" id="tbl_retencion">
                        <thead>
                          <tr>
                            <th class="text-center"></th>
                            <th class="text-center">CodRet</th>
                            <th class="text-center">Detalle</th>
                            <th class="text-center">BaseImp</th>
                            <th class="text-center">Porcentaje</th>
                            <th class="text-center">ValRet</th>
                            <th class="text-center">EstabRetencion</th>
                            <th class="text-center">PtoEmiRetencion</th>
                            <th class="text-center">SecRetencio</th>
                            <th class="text-center">AutRetencion</th>
                            <th class="text-center">FechaEmiRet</th>
                            <th class="text-center">Cta_Retencion</th>
                            <th class="text-center">EstabFactura</th>
                            <th class="text-center">PuntoEmiFactura</th>
                            <th class="text-center">Factura_No</th>
                            <th class="text-center">IdProv</th>
                            <th class="text-center">Item</th>
                            <th class="text-center">CodigoU</th>
                            <th class="text-center">A_No</th>
                            <th class="text-center">T_No</th>
                            <th class="text-center">Tipo_Trans</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 text-right">
                        <b>Total Retencion</b>
                        <input type="text" class="input-xs" name="" id="txt_total_retencion" readonly=""  autocomplete="off" tabindex="14">
                      </div>
                    </div>        
                </div>
                <div class="tab-pane modal-body fade" id="menu2">
                  <div class="row text">
                    <div class="col-sm-12">
                      <div class="row">
                        <div class="col-sm-8">
                          <b>NUMERO DEL CONTRATO DEL PARTIDO POLITICO</b>
                        </div>
                        <div class="col-sm-4">
                          <input type="text"  class="form-control form-control-sm" name="" id="TxtNumConParPol"  autocomplete="off">
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12">                     
                      <div class="row">
                        <div class="col-sm-8">
                          <b>MONTO TITULO ONEROSO</b>
                        </div>
                        <div class="col-sm-4">
                          <input type="text"  class="form-control form-control-sm" name="TxtMonTitOner" id="TxtMonTitOner"  autocomplete="off">
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="row">
                        <div class="col-sm-8">
                          <b>MONTO DEL CONTRATO</b>
                        </div>
                        <div class="col-sm-4">
                          <input type="text"  class="form-control form-control-sm" name="TxtMonTitGrat" id="TxtMonTitGrat"  autocomplete="off">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
            </div>
  </div>
</div>
     