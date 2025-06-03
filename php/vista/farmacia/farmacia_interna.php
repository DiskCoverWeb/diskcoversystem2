<script src="../../dist/js/farmacia/farmacia_interna.js"></script>
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

<div class="row row-cols-auto  mb-2">
   <div class="btn-group">
      <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0]);?>" title="Salir de modulo" class="btn btn-outline-secondary btn-sm">
        <img src="../../img/png/salire.png">
      </a>
      <a href="./inicio.php?mod=28&acc=pacientes" type="button" class="btn btn-outline-secondary btn-sm" id="imprimir_pdf" title="Pacientes">
            <img src="../../img/png/pacientes.png">
      </a>           
      <a href="./inicio.php?mod=28&acc=vis_descargos" type="button" class="btn btn-outline-secondary btn-sm" id="imprimir_excel" title="Descargos">
            <img src="../../img/png/descargos.png">
      </a>         
      <a href="./inicio.php?mod=28&acc=articulos" title="Ingresar Articulosr"  class="btn btn-outline-secondary btn-sm" onclick="">
        <img src="../../img/png/articulos.png" >
      </a>
      <button type="button" class="btn btn-outline-secondary btn-sm" title="Generar pdf" onclick="reporte_pdf()"><img src="../../img/png/pdf.png"></button>
      <button type="button" class="btn btn-outline-secondary btn-sm" title="Generar pdf" onclick="reporte_excel()"><img src="../../img/png/table_excel.png"></button>
 	</div>
</div>
<div class="row mb-1">
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-6">
          <b>Tipo de reporte</b>
          <select class="form-select form-select-sm" onchange="cargar_tablas()" name="ddl_opciones" id="ddl_opciones">
            <option value="">Seleccione opcion</option>
            <option value="1">INGRESOS</option>
            <option value="2">LISTADO DEL CATALOGO</option>
            <!-- <option value="3">EGRESOS O DESCARGOS DE PACIENTES</option> -->
            <option value="4">DESCARGOS PARA VISUALIZAR POR PACIENTE</option>
            <option value="5">VISUALIZACION DE DESCARGOS DE FARMACIA INTERNA</option>
          </select>     
        </div>            
      </div>
    </div>    
  </div>
</div>
<div class="row mb-2">
  <div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12" id="opcion1" style="display:none;">
              <form id="form_1">
                <div class="row">
                  <div class="col-sm-6 col-lg-4">
                    <b>Proveedor</b>
                    <div class="d-flex align-items-center"> 
                        <select class="form-control input-sm" id="ddl_proveedor" name="ddl_proveedor" onchange="tabla_ingresos()">
                           <option value="">Seleccione un proveedor</option>
                        </select>             
                        <button type="button" class="btn btn-danger btn-sm p-1" onclick="$('#ddl_proveedor').empty();tabla_ingresos()" title="Borrar seleccion"><i class="bx bx-x me-0"></i></button>
                    </div>
                  </div>  
                  <div class="col-sm-2">
                    <b>Serie</b>
                    <input type="text" class="form-control form-control-sm" name="txt_serie" id="txt_serie" placeholder="001001" onkeyup="tabla_ingresos()">
                  </div>  
                  <div class="col-sm-3">
                   <b> Facturas</b>
                    <input type="text" class="form-control form-control-sm" name="txt_factura" id="txt_factura" placeholder="Numero de factura" onkeyup="tabla_ingresos()">
                  </div>  
                  <div class="col-sm-3">
                   <b> No. Comprobante</b>
                    <input type="text" class="form-control form-control-sm" name="txt_comprobante" id="txt_comprobante" onkeyup="tabla_ingresos()" placeholder="Numero de Comprobante">
                  </div>    
                </div>
              </form> 
              <div class="row">
                <div class="col-sm-12">
                  <table class="table" id="tbl_opcion1">
                    <thead>
                      <th></th>
                      <th>Fecha</th>
                      <th>Proveedor</th>
                      <th>Factura</th>
                      <th>Serie_No</th>
                      <th>Comprobante</th>
                      <th>Total</th>
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
                      </tr>                      
                    </tbody>                    
                  </table>
                </div>
              </div>    
            </div>

            <!-- ---------------------------------------------opcion 2 ---------------------------------------- -->
            <div  class="col-sm-12" id="opcion2" style="display:none">              
              <form id="form_2">
                <div class="row">
                  <div class="col-sm-4">
                    <b>Codigo</b>
                    <div class="d-flex align-items-center"> 
                      <select class="form-select form-select-sm" id="ddl_referencia" name="ddl_referencia" onchange="tabla_catalogo('ref')">
                         <option value="">Seleccione un proveedor</option>
                      </select>             
                       <button type="button" class="btn-danger btn-sm  btn p-1" onclick="$('#ddl_referencia').empty();tabla_catalogo('ref')" title="Borrar seleccion"><i class="bx bx-x me-0"></i></button>
                    </div>
                  </div>  
                  <div class="col-sm-4">
                    Descripcion
                    <div class="d-flex align-items-center"> 
                        <select class="form-select form-select-sm" id="ddl_descripcion" name="ddl_descripcion" onchange="tabla_catalogo('ref')">
                           <option value="">Seleccione un proveedor</option>
                        </select>             
                         <button type="button" class="btn btn-danger btn-sm p-1" onclick="$('#ddl_descripcion').empty();tabla_catalogo('ref')" title="Borrar seleccion"><i class="bx bx-x me-0"></i></button>
                    </div>
                  </div>       
                </div>
              </form>  
              <div class="row">
                <div class="table-responsive col-sm-12">
                  <table class="table table-hover" id="tbl_opcion2">
                    <thead>
                      <th>Codigo</th>
                      <th>Producto</th>
                      <th>Cantidad Actual</th>
                      <th>Precio</th>
                      <th>Fecha de compra</th>
                      <th>Ingresado</th>
                    </thead>
                    <tbody id="tbl_op2">
                      
                    </tbody>
                    
                  </table>
                </div>
                
              </div>  
            </div>

            <!-- ------------------------------------ opcion 3----------------------------------------- -->
            <div id="opcion3" class="col-sm-12">
              <div class="row">
                <div class="col-sm-12">
                  
                </div>      
              </div>
              <div class="row">
                <div class="col-sm-12">
                  
                </div>      
              </div>
            </div>

            <!-- ------------------------------------ opcion 4---------------------------------------------->

              <div id="opcion4" style="display:none" class="col-sm-12">
                <form id="form_4">
                  <div class="row">
                    <div class="col-sm-3">
                      <b>Paciente</b>
                      <input type="text"  class="form-control form-control-sm" name="txt_paciente" id="txt_paciente" onkeyup="cargar_pedidos();">
                    </div>  
                    <div class="col-sm-2">
                      <b>Numero de Cedula</b>       
                      <input type="text"  class="form-control form-control-sm" name="txt_ci" id="txt_ci" onkeyup="cargar_pedidos();">
                    </div>  
                    <div class="col-sm-2">
                      <b>Historia Clinica</b>        
                      <input type="text"  class="form-control form-control-sm" name="txt_historia" id="txt_historia" onkeyup="cargar_pedidos();">
                    </div>  
                    <div class="col-sm-3">
                      <b>Departamento</b>   
                      <input type="text"  class="form-control form-control-sm" name="txt_departamento" id="txt_departamento" onkeyup="cargar_pedidos();">
                    </div>  
                    <div class="col-sm-2">
                      <b>Procedimiento</b>       
                      <input type="text"  class="form-control form-control-sm" name="txt_procedimiento" id="txt_procedimiento" onkeyup="cargar_pedidos();">
                    </div>  
                    <div class="col-sm-2">
                      <b>Desde</b>        
                      <input type="date"  class="form-control form-control-sm" name="txt_desde" value="<?php echo date('Y-m-d'); ?>" id="txt_desde" onblur="cargar_pedidos();">
                    </div>  
                    <div class="col-sm-2">
                      <b>Hasta</b>        
                      <input type="date"  class="form-control form-control-sm" name="txt_hasta" value="<?php echo date('Y-m-d'); ?>" id="txt_hasta" onblur="cargar_pedidos();">
                    </div> 
                    <div class="col-sm-2"><br>
                        <label><input type="checkbox" name="rbl_fecha" id="rbl_fecha" onchange="cargar_pedidos()">Por Fecha</label>
                    </div>
                  </div>
                </form> 
                <hr>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="table-responsive">
                        <table class="table table display nowrap" id="tbl_descargos">
                          <thead>
                            <th></th>
                            <th>Fecha</th>
                            <th>Paciente</th>
                            <th>Cedula</th>
                            <th>Historia</th>
                            <th>Departamento</th>
                            <th>importe</th>
                            <th>Procedimiento</th>
                            <th>Orden_No</th>
                            <th>comprobante</th>
                          </thead>
                          <tbody></tbody>
                          
                        </table>
                        
                      </div>
                    </div>      
                  </div>
                  
              </div>


            <!-- ------------------------------------ opcion 5---------------------------------------------->

              <div id="opcion5" style="display:none" class="col-sm-12">
                <form id="form_5"> 
                  <div class="row">
                    <div class="col-sm-2">
                      <b>Desde</b>
                      <input type="date" name="txt_desde5" class="form-control form-control-sm" id="txt_desde5" value="<?php echo date('Y-m-d');?>" onblur="cargar_medicamentos()">      
                       <label><input type="checkbox" name="rbl_fecha5" id="rbl_fecha5" onchange="cargar_medicamentos()">Por Fecha</label>          
                    </div> 
                    <div class="col-sm-2">
                      <b>Hasta</b>
                      <input type="date" name="txt_hasta5" class="form-control form-control-sm" id="txt_hasta5" value="<?php echo date('Y-m-d');?>" onblur="cargar_medicamentos()">        
                    </div> 
                    <div class="col-sm-3">
                      <b>Medicamento o insumo</b>
                      <input type="text" name="txt_medicamento" id="txt_medicamento" class="form-control form-control-sm" onkeyup="cargar_medicamentos()">        
                    </div> 
                    <div class="col-sm-3">
                      <b>Paciente</b>
                      <input type="text" name="txt_paciente5" id="txt_paciente5" class="form-control form-control-sm" onkeyup="cargar_medicamentos()">        
                    </div> 
                     <div class="col-sm-2">
                      <b>Numero de cedula</b>
                      <input type="text" name="txt_ci_ruc" id="txt_ci_ruc" class="form-control form-control-sm" onkeyup="cargar_medicamentos()">        
                    </div> 
                     <div class="col-sm-3">
                      <b>Departamento</b>
                      <input type="text" name="txt_departamento5" id="txt_departamento5" class="form-control form-control-sm" onkeyup="cargar_medicamentos()">       
                    </div> 
                  </div>
                </form>
                  <div class="row">      
                    <div class="col-sm-12">
                     
                    </div>
                  </div>
                  <div class="row" >
                    <div class="col-sm-12" id="cantidad_consu" style="display:none">
                      Cantidad consumida desde :<b id="des"></b> hasta <b id="has"></b>  es de  <b id="consumido"></b>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="table-responsive">
                        <table class="table" id="tbl_medicamentos">
                          <thead>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Cliente</th>
                            <th>Cedula</th>
                            <th>Matricula</th>
                            <th>Departamento</th>
                            <th>Cantidad</th>
                          </thead>
                          <tbody></tbody>
                        </table>
                      </div>               
                    </div>      
                  </div>
                </div>




          
      </div>
    </div>    
  </div>
</div>


	

