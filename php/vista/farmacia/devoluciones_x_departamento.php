<?php $cod='';$area='';$sub='';$cc='';$cc_no='';if(isset($_GET['comprobante'])){$cod =$_GET['comprobante'];}
if(isset($_GET['subcta'])){$sub =$_GET['subcta'];}
if(isset($_GET['centroc'])){$cc =$_GET['centroc'];}
if(isset($_GET['cc_no'])){$cc_no =$_GET['cc_no'];}
if(isset($_GET['area'])){$area =$_GET['area'];}
 $_SESSION['INGRESO']['modulo_']='99'; date_default_timezone_set('America/Guayaquil'); 
      unset($_SESSION['NEGATIVOS']['CODIGO_INV']);?>
<script type="text/javascript">
  var sub= '<?php echo $sub; ?>';
  var no = '<?php echo $area; ?>';
  var cc = '<?php echo $cc; ?>';
  var cc_no = '<?php echo $cc_no; ?>';
  var cod = '<?php echo $cod; ?>';
</script>
<script src="../../dist/js/farmacia/devoluciones_x_departamento.js"></script>
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
<div class="row-cols-auto  mb-2">
  <div class="btn-group">
    <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0]);?>" title="Salir de modulo" class="btn btn-outline-secondary">
      <img src="../../img/png/salire.png">
    </a>
    <button href="#" title="Generar reporte pdf"  class="btn btn-outline-secondary" onclick="generar_informe()">
        <img src="../../img/png/impresora.png" >
      </button>
    <a href="./inicio.php?mod=28&acc=pacientes" type="button" class="btn btn-outline-secondary" id="imprimir_pdf" title="Pacientes">
      <img src="../../img/png/pacientes.png">
    </a>   
    <a href="./inicio.php?mod=28&acc=vis_descargos" type="button" class="btn btn-outline-secondary" id="imprimir_excel" title="Descargos">
      <img src="../../img/png/descargos.png">
    </a>
    <a href="./inicio.php?mod=28&acc=articulos" title="Ingresar Articulosr"  class="btn btn-outline-secondary" onclick="">
      <img src="../../img/png/articulos.png" >
    </a> 
  </div>
</div>
<div class="row">
     <div class="card">
      <div class="card-body">
        <div class="card-title">
          <div class="row">
             <div class="col-sm-6 text-end"><b>Devoluciones de insumos por departamento</b></div>         
             <div class="col-sm-6 text-end"> No. COMPROBANTE  <u id="num"></u></div>        
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-sm-3">
              <button id="" class="btn btn-primary btn-sm" onclick="generar_factura('<?php echo $cod;?>')"><i class="icon fa fa-cogs"></i> Procesar devolucion</button>                       
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
              <b>No Orden</b>
               <input type="text" name="txt_orden" id="txt_orden" class="form-control form-control-sm" readonly>
            </div>
             <!-- <div class="col-sm-3"> 
                <b>Persona solicitante:</b>
                <select class="form-control" name="txt_soli" id="txt_soli">
                  <option value="">Seleccione solicitante</option>
                </select>
                <input type="text" name="txt_soli" id="txt_soli" class="form-control input-sm" value="." onfocus="$('#txt_soli').select()">            
              </div>    -->
            <div class="col-sm-4"> 
              <b>Centro de costos:</b>
              <select class="form-select form-select-sm" id="ddl_cc" onchange="">
                <option value="">Seleccione Centro de costos</option>
              </select>           
            </div>             
            <div class="col-sm-4">
                <b>Area de devolucion:</b>
                <select class="form-select form-select-sm" id="ddl_areas" name="ddl_areas">
                  <option value="">Seleccionar producto</option>
                </select>      
            </div>
        </div>
        <div class="row">
        	 <div class="col-sm-2">
           	  <b>Codigo</b>
              <input type="text" name="txt_codigo" id="txt_codigo" class="form-control form-control-sm" readonly>
           </div>         
            <div class="col-sm-4"> 
            	<b>Productos</b>
              <select class="form-select form-select-sm" id="ddl_producto" name="ddl_producto" onchange="cargar_detalles()">
              	<option value="">Seleccionar producto</option>
              </select>      
            </div>
            <div class="col-sm-1">
             	<b>Cantidad</b>
               <input type="text" name="txt_cant" id="txt_cant" value="0" class="form-control form-control-sm" onblur="calcular()">
            </div>
            <div class="col-sm-1">
             	<b>Stock</b>
               <input type="text" name="txt_stok" id="txt_stok" value="0" class="form-control form-control-sm" readonly>
            </div>
            <div class="col-sm-1">
             	<b>Precio</b>
               <input type="text" name="txt_precio" id="txt_precio" value="0" class="form-control form-control-sm" readonly>
            </div>
            <div class="col-sm-1">
             	<b>Total</b>
               <input type="text" name="txt_total" id="txt_total" value="0" class="form-control form-control-sm" readonly>
            </div>
            <div class="col-sm-2">
              <b>Fecha</b>
               <input type="date" name="txt_fecha" id="txt_fecha" value="<?php echo date('Y-m-d'); ?>" class="form-control form-control-sm" readonly>
            </div>          
        </div>
        <div class="row">
          <div class="col-sm-12 text-end">
            <br>
             <button type="button" class="btn btn-primary btn-sm" onclick="guardar_devolucion()"><i class="icon fa  fa-arrow-down"></i> Agregar devolucion</button>             
          </div>
        	
        </div>
      </div>
  </div>
</div>
<div class="row">
  <div class="card">
    <div class="card-body">
       <div class="col-sm-12">
          <input type="hidden" name="lineas" id="lineas" value="0">
          <div class="table-responsive">
            <table class="table" id="tbl_devoluciones">
              <thead>
                <th></th>
                <th>CODIGO PRODUCTO</th>
                <th>PRODUCTO</th>
                <th>CANTIDAD</th>
                <th>VALOR UNITARIO</th>
                <th>VALOR TOTAL</th>
                <th>FECHA</th>
                <th>AREA</th>
                <th>A_No</th>
                <th>ORDEN</th>
              </thead>
              <tbody></tbody>
            </table>
            
          </div>  
        </div>
      
    </div>    
  </div>
  
</div>
 

<div class="modal fade" id="modal_procedimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Cambiar procedimiento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <div class="row">
          <div class="col-sm-12">
            Nombre de procedimiento
            <input type="text" class="form-control input-sm" name="txt_new_proce" id="txt_new_proce">
          </div>        
         </div> 
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="guardar_new_pro();">Guardar Todo</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cerrar</button>
        </div>
    </div>
  </div>
</div>
