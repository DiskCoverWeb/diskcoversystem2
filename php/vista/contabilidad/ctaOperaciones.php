<!--SCRIPT IMPORTADO-->
<script src="../../dist/js/ctaOperaciones.js"></script>
<!--Comienzo de la vista-->
<div class="overflow-auto"> 
  <div class="">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3"><?php echo $NombreModulo; ?>
      </div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0"  id="ruta_menu">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
          </ol>
        </nav>
      </div>          
    </div>
	</div>

  <div class="row row-cols-auto">
    <div class="d-flex justify-content-center align-items-center">
        <div class="btn-group p-0">
          <a href="inicio.php?mod=<?php echo $_SESSION['INGRESO']['modulo_']; ?>" title="Salir de modulo" class="btn btn-sm btn-outline-secondary">
            <img src="../../img/png/salire.png">
          </a>
          <button type="button" class="btn btn-sm btn-outline-secondary" title="Copiar Catalogo" onclick="mostrarModalPass()" >
            <img src="../../img/png/copiar_1.png">
          </button>
          <button type="button" class="btn btn-sm btn-outline-secondary" title="Cambiar Cuentas" onclick="validar_cambiar()">
            <img src="../../img/png/pbcs.png">
          </button>
          <button title="Guardar"  class="btn btn-sm btn-outline-secondary" onclick="grabar_cuenta()">
            <img src="../../img/png/grabar.png" >
          </button>
        </div>
    </div>
  </div>
  <div class="row p-2"><br>
    <input type="hidden" name="txt_anterior" id="txt_anterior">
    <div class="col-sm-4" id="tabla" style="overflow-y: scroll;"></div>
    <div class="col-sm-8">      
      <!-- <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#home">DATOS PRINCIPALES</a></li>
        <li><a data-toggle="tab" href="#menu1">PRESUPUESTOS DE SUBMODULOS</a></li>
      </ul> -->
      <div class="tab-content"><br>
        <div id="home" class="tab-pane fade show active">
          <div class="row">
            <div class="col-sm-4">
            <b>Codigo de cuenta</b><br>
            <input type="" name="MBoxCta" class="form-control form-control-sm" id="MBoxCta" placeholder="<?php 
            echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" onblur="tip_cuenta(this.value)" ><br>
          </div>
          <div class="col-sm-8">
            <b>Nombre de cuenta</b> <br>
            <input type="" name="TextConcepto" class="form-control form-control-sm" id="TextConcepto"> <br>           
          </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
              <b>Cuenta superior</b><br>
              <input type="" name="LabelCtaSup" class="form-control form-control-sm" id="LabelCtaSup" readonly=""><br>         
            </div>
            <div class="col-sm-3">
              <b>Tipo de cuenta</b>
                <input type="" name="LabelTipoCta" class="form-control form-control-sm" id="LabelTipoCta" readonly>                       
            </div>
              <div class="col-sm-3">
              <b>Codigo Externo</b>
              <input type="" name="TxtCodExt" class="form-control form-control-sm" id="TxtCodExt" value="0">        
            </div>
            <div class="col-sm-3">
              <b>Numero</b>
              <input type="" name="LabelNumero" class="form-control form-control-sm" id="LabelNumero" value="0">
            </div>     
        </div> 
        <div class="row">  
          <div class="col-sm-3">
              <b>Tipo de cuenta</b><br>
            <div class="form-check form-check-inline d-dlex align-items-center">
              <input class="form-check-input" type="radio" name="rbl_t" id="OpcG" value="G" checked>
              <label class="form-check-label" for="OpcG"><b>Grupo</b></label>
            </div>
            <br>
            <div class="form-check form-check-inline">
              <label class="form-check-label"><input type="radio" name="rbl_t" id="OpcD" class="form-check-input"> <b>Detalle</b> </label>
            </div>
            <br>
            <div class="form-check form-check-inline">
            <label class="form-check-label"><input class="form-check-input" type="checkbox" name="CheqModGastos" id="CheqModGastos"> <b>Para gastos de caja chica</b></label> <br> 
            <label class="form-check-label"><input class="form-check-input" type="checkbox" name="CheqUS" id="CheqUS"> <b>Cuenta M/E</b></label>  <br>
            <label class="form-check-label"><input class="form-check-input" type="checkbox" name="CheqFE" id="CheqFE"> <b>Flujo efectivo</b></label>  <br>              
            </div>
            
          </div>
          <div class="col-sm-3">
            <b>Tipo de cuenta</b><br>
            <select class=" form-select form-select-sm overflow-auto" id="LstSubMod" onchange="presupuesto_act($('#LstSubMod').val())" row="11" multiple>
              <option class="list-group-item list-group-item-action" value='N' selected>General/Normal</option>
              <option class="list-group-item list-group-item-action" value='CJ'>Cuenta de Caja</option>
              <option class="list-group-item list-group-item-action" value='BA'>Cuenta de Bancos</option>
              <option class="list-group-item list-group-item-action" value='C'>Modulo de CxC</option>
              <option class="list-group-item list-group-item-action" value='P'>Modulo de CxP</option>
              <option class="list-group-item list-group-item-action" value='I'>Modulo de Ingresos</option>
              <option class="list-group-item list-group-item-action" value='G'>Modulo de Gastos</option>
              <option class="list-group-item list-group-item-action" value='CS'>CxC Sin Submódulo</option>
              <option class="list-group-item list-group-item-action" value='PS'>CxP Sin Submódulo</option>
              <option class="list-group-item list-group-item-action" value='RF'>Retención en la Fuente</option>
              <option class="list-group-item list-group-item-action" value='RI'>Retención del I.V.A Servicios</option>
              <option class="list-group-item list-group-item-action" value='RB'>Retencion del I.V.A Bienes</option>
              <option class="list-group-item list-group-item-action" value='CF'>Crédito Retencion en la Funete</option>
              <option class="list-group-item list-group-item-action" value='CI'>Crédito Retencion del I.V.A. Servicio</option>
              <option class="list-group-item list-group-item-action" value='CB'>Crédito Retencion del I.V.A. Bienes</option>
              <option class="list-group-item list-group-item-action" value='CP'>Caja Cheques Posfechados</option>
              <option class="list-group-item list-group-item-action" value='PM'>Modulo de Primas</option>
              <option class="list-group-item list-group-item-action" value='RP'>Modulo de Inventario</option>
              <option class="list-group-item list-group-item-action" value='TJ'>Opcion Tarjeta de Credito</option>
              <option class="list-group-item list-group-item-action" value='CC'>Modulo Centro de Costos</option>
            </select><br>
            <b  style="display: none;">Codigo acreditar</b>
            <input type="" name="MBoxCtaAcreditar" class="form-control input-sm" id="MBoxCtaAcreditar" placeholder="<?php 
            echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" style="display: none;">             
          </div>
          <div class="col-sm-4">
            <div class="panel panel-default">
            <div class="panel-heading form-check-inline"><b>Rol de Pagos para Empleados</b></div>
            <label class="form-check-label"><input class="form-check-input" type="radio" name="rbl_rol" id="OpcNoAplica" checked=""> <b>No Aplica</b> </label><br>
            <label class="form-check-label"><input class="form-check-input" type="radio" name="rbl_rol" id="OpcIEmp"> <b>Ingreso</b> </label><br>
            <label class="form-check-label"><input class="form-check-input" type="radio" name="rbl_rol" id="OpcEEmp"> <b>Descuentos</b> </label><br>
            <label class="form-check-label"><input class="form-check-input" type="checkbox" name="rbl_rol" id="CheqConIESS"> <b>Ingreso extra con Aplicacion al IESS</b></label>
          </div>  
        </div> 
        <div class="col-sm-2">
            <b>PRESUPUESTOS</b>          
            <input type="" name="TextPresupuesto" class="form-control" id="TextPresupuesto" value="0.0">  
        </div>   
        </div>
        <div class="row">
          
        <div class="col-sm-3">
          <label class="form-check-label"><input class="form-check-input" type="checkbox" name="CheqTipoPago" id="CheqTipoPago" onclick="forma_pago()"> TIPO DE PAGO</label>
        </div>
          <div class="col-sm-9">
              <select class="form-select form-select-sm" id="DCTipoPago" style="display: none;">
                <option>Seleccione tipo de pago</option>
              </select>           
          </div>
        </div>
        <div class="row" style="display:none">
          <div class="col-sm-10">
            <table class="table table-responsive col-md-4">
              <th>Mes</th>
              <th>Presupuesto</th>
              <tbody id="table_pre">
                <td>-</td>
                <td>-</td>
              </tbody>
            </table>          
          </div>
          <div class="col-sm-2"><br>
            <input type="button" name="" id="btn_ingresar_pre" disabled="" class="btn btn-primary btn-xs" value="Ingresar" data-toggle="modal" data-target="#exampleModalCenter">
          </div>
        
      </div>
    </div>
    <div id="menu1" class="tab-pane fade">
      <div class="row">
        <div class="col-sm-12">
          <h3>Menu 1</h3>
      <p>Some content in menu 1.</p>
      <input type="" name="">
        </div>
      </div>
    </div>
  </div>  
  </div>

  </div>
  </div>


  <div class="modal fade bd-example-modal-sm" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Ingrese Presupuesto</h5>
        </div>
        <div class="modal-body">
          <select class="form-control input-sm" id="DCMes">
            <option>Seleccione mes</option>
          </select>
          <input type="" name="" id="txt_val_pre" class="form-control input-sm" placeholder="0.00">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" onclick="ingresar_presu()">Ingresar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade bd-example-modal-sm" id="modal_copiar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle"><B>COPIAR CATALOGO DE OTRA EMPRESA</B></h5>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-9">
                <select class="form-control input-sm" id="DLEmpresa">
                  <option>Elija empresa a copiar el catalogo</option>
                </select><br>
                <label class="checkbox-inline"><input type="checkbox" name="CheqCatalogo" id="CheqCatalogo"> Catalogo de cuentas</label><br>
                <label class="checkbox-inline"><input type="checkbox" name="CheqSetImp" id="CheqSetImp"> Seteos de impresion</label><br>
                <label class="checkbox-inline"><input type="checkbox" name="CheqFact" id="CheqFact"> Seteos de facturacion</label><br>
                <label class="checkbox-inline"><input type="checkbox" name="CheqSubCta" id="CheqSubCta"> SubCuentas de Ingreso, Gastos y costos</label><br>
                <label class="checkbox-inline"><input type="checkbox" name="CheqSubCP" id="CheqSubCP"> SubCuentas de CxC y CxP</label>            
            </div>
            <div class="col-md-3 text-center">
              <div class="row">
                <div class="col-md-12 col-sm-6 col-xs-2">                
                  <button type="button" class="btn btn-default" id="btn_copiar_cata" title="Copiar Catalogo" data-toggle="modal" data-target="#modal_copiar" onclick="copiar_op('false')">
                    <img src="../../img/png/agregar.png"><br>
                    Aceptar
                  </button>
                </div>
                <div class="col-md-12 col-sm-6 col-xs-2">
                  <br>
                  <button type="button" class="btn btn-default" title="Cerrar" data-dismiss="modal">
                    <img src="../../img/png/salire.png"><br>&nbsp; &nbsp;Salir&nbsp;&nbsp;&nbsp;
                  </button>
                </div>              
              </div>
              
            </div>
          </div>
        </div>
      <!--  <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" onclick="ingresar_presu()">Ingresar</button>
        </div> -->
      </div>
    </div>
  </div>

  <div class="modal fade bd-example-modal-sm" id="modal_periodo" tabindex="-1" role="dialog" aria-labelledby="modal_periodo" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Ingrese Periodo</h5>
        </div>
        <div class="modal-body">
          <input type="" name="" id="txt_perido_c" class="form-control input-sm" value=".">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" onclick="copiar()">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade bd-example-modal" id="modal_cambiar" tabindex="-1" role="dialog" aria-labelledby="modal_cambiar" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle"><b>CAMBIO DE VALORES DE LA CUENTA</b></h5>
        </div>
        <div class="modal-body">
        
          <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-sm-12 text-center">
                      <b id="cambiar_select"></b>
                    </div>
                  </div>
                <select class="form-control input-sm" id="DLEmpresa_">
                  <option>Seleccione la cuenta a cambiar</option>
                </select>            
            </div>
            <div class="col-md-3 text-center">
              <div class="row">
                <div class="col-md-12 col-sm-6 col-xs-2">                
                  <button type="button" class="btn btn-default" onclick="cambiar_op()">
                    <img src="../../img/png/agregar.png"><br>
                    Aceptar
                  </button>
                </div>
                <div class="col-md-12 col-sm-6 col-xs-2">
                  <br>
                  <button type="button" class="btn btn-default" title="Cerrar" data-dismiss="modal">
                    <img src="../../img/png/salire.png"><br>&nbsp; &nbsp;Salir&nbsp;&nbsp;&nbsp;
                  </button>
                </div>              
              </div>
              
            </div>
          </div>
        </div>
      <!--  <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" onclick="ingresar_presu()">Ingresar</button>
        </div> -->
      </div>
    </div>
  </div>



  <div class="modal fade bd-example-modal" id="movimientos_cta" tabindex="-1" role="dialog" aria-labelledby="modal_cambiar" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle"><b>No se pueden borrar esta(s) cuenta(s) </b></h5>
        </div>
        <div class="modal-body">       
          <div class="row" >
            <div class="col-sm-12" id="lista_transacciones" style="height:200px; overflow-y: scroll;">
              
            </div>         
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <!-- <button type="button" class="btn btn-primary" onclick="ingresar_presu()">Ingresar</button> -->
        </div>
      </div>
    </div>
  </div>



<!-- partial:index.partial.html -->

<!-- partial -->
<!-- //<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->
