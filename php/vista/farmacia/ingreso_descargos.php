<?php  $num_ped = '';$cod=''; $area = ''; $pro=''; if(isset($_GET['num_ped'])){$num_ped =$_GET['num_ped'];} if(isset($_GET['cod'])){$cod =$_GET['cod'];} if(isset($_GET['area'])){$area1 = explode('-', $_GET['area']); $area =$area1[0];$pro=$area1[1]; } $_SESSION['INGRESO']['modulo_']='99'; date_default_timezone_set('America/Guayaquil'); 
      unset($_SESSION['NEGATIVOS']['CODIGO_INV']);?>
<script type="text/javascript">
    var c = '<?php echo $cod; ?>';
    var area = '<?php echo $area; ?>';
    var pro = '<?php echo $pro; ?>';
    var cod = '<?php echo $cod; ?>';
    var num_ped = '<?php echo $num_ped; ?>';
</script>
<script src="../../dist/js/farmacia/ingreso_descargos.js"></script>
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
    <a  href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>" title="Salir de modulo"  class="btn btn-outline-secondary btn-sm">
      <img src="../../img/png/salire.png">
    </a>
    <a href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>&acc=pacientes" type="button" class="btn btn-outline-secondary btn-sm" id="imprimir_pdf" title="Pacientes">
      <img src="../../img/png/pacientes.png">
    </a>
    <a href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>&acc=vis_descargos" type="button" class="btn btn-outline-secondary btn-sm" id="imprimir_excel" title="Descargos">
      <img src="../../img/png/descargos.png">
    </a>        
    <a href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>&acc=articulos" title="Ingresar Articulos"  class="btn btn-outline-secondary btn-sm" onclick="">
      <img src="../../img/png/articulos.png" >
    </a>
    <button title="Mayorizar Articulos"  class="btn btn-outline-secondary btn-sm" onclick="mayorizar_inventario()">
      <img src="../../img/png/update.png" >
    </button>
  </div>
</div>
<div class="row mb-2">
  <div class="card card-primary">
    <div class="card-body">
      <div class="row">
          <div class="col-sm-6">
            <h6 class="card-title text-end">NUEVO DESCARGO </h6>   
          </div>
          <div class="col-sm-6 text-end">
            <h7 class="ms-auto"> No. COMPROBANTE  <u id="num"></u></h7>               
          </div>       
      </div>
      <div class="row">
        <div class="col-sm-3"> 
            <b>Num Historia clinica:</b>
            <input type="text" name="txt_codigo" id="txt_codigo" class="form-control form-control-sm" readonly="">      
        </div>
        <div class="col-sm-6">
          <b>Nombre:</b>
          <select class="form-select form-select-sm" id="ddl_paciente" onchange="buscar_cod()">
            <option value="">Seleccione paciente</option>
          </select>
        </div>
        <div class="col-sm-3">
          <b>RUC:</b>
          <input type="text" name="txt_ruc" id="txt_ruc" class="form-control form-control-sm" readonly>             
        </div>   
      </div>
      <hr>
      <div class="row">
          <div class="col-sm-4"> 
            <b>Centro de costos:</b>
            <select class="form-control form-control-sm" id="ddl_cc" onchange="">
              <option value="">Seleccione Centro de costos</option>
            </select>           
          </div>
          <div class="col-sm-2">    
          <b>Numero de pedido</b>
          <input type="text" name="" id="txt_pedido" readonly="" class="form-control form-control-sm" value="<?php echo $num_ped;?>">     
          </div>
          <div class="col-sm-3">
             <b>Fecha:</b>
            <input type="date" name="txt_fecha" id="txt_fecha" class="form-control form-control-sm" value="<?php echo date('Y-m-d')  ?>" onblur="num_comprobante()">                 
          </div>
          <div class="col-sm-3">
            <b>Area de descargo</b>
            <select class="form-select form-select-sm" id="ddl_areas" onchange="validar_area()">
              <option value="">Seleccione motivo de ingreso</option>
            </select>            
          </div>          
      </div>
      <div class="row">
          <div class="col-sm-4"> 
            <b>Cod Producto:</b>
            <select class="form-select form-select-sm" id="ddl_referencia" onchange="producto_seleccionado('R')">
              <option value="">Escriba referencia</option>
            </select>           
          </div>
          <div class="col-sm-5"> 
                <b>Descripcion:</b>
                <select class="form-select form-select-sm" id="ddl_descripcion" onchange="producto_seleccionado('D')">
                  <option value="">Escriba descripcion</option>
                </select>          
              </div> 
          <div class="col-sm-3"> 
            <b>Procedimiento:</b>
            <div class="input-group input-group-sm">
                <textarea class="form-control form-control-sm" style="resize: none;" name="txt_procedimiento" id="txt_procedimiento" readonly=""></textarea>          
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat" onclick="cambiar_procedimiento()"><i class="bx bx-pencil"></i></button>
                    </span>
              </div>
           
          </div>           
      </div>
       <div class="row">
        <div class="col-sm-3"> 
          <div class="row">
             <div class="col-sm-6"> 
              <b>MIN:</b>
              <input type="text" name="txt_min" id="txt_min" class="form-control form-control-sm"readonly="">
            </div>
            <div class="col-sm-6"> 
              <b>MAX:</b>
              <input type="text" name="txt_max" id="txt_max" class="form-control form-control-sm"readonly="">
            </div>               
          </div>
        </div>               
        <div class="col-sm-2"> 
          <b>Costo:</b>
          <input type="text" name="txt_precio" id="txt_precio" class="form-control form-control-sm" value="0" onblur="calcular_totales();" readonly="">            
        </div>   
        <div class="col-sm-2"> 
          <b>Cantidad:</b>
          <input type="text" name="txt_cant" id="txt_cant" class="form-control form-control-sm" value="1" onblur="calcular_totales();">
        </div>   
        <div class="col-sm-1"> 
          <b>UNI:</b>
          <input type="text" name="txt_unidad" id="txt_unidad" class="form-control form-control-sm" readonly="">            
        </div>
        <div class="col-sm-1"> 
          <b>Stock:</b>
          <input type="text" name="txt_Stock" id="txt_Stock" class="form-control form-control-sm" readonly="">            
        </div>    
        <div class="col-sm-1"> 
          <b>Importe:</b>
          <input type="text" name="txt_importe" id="txt_importe" class="form-control form-control-sm" readonly="">
          <input type="hidden" name="txt_iva" id="txt_iva" class="form-control form-control-sm">            
        </div> 
        <div class="col-sm-2 text-end"><br>
          <button class="btn btn-primary btn-sm" onclick="calcular_totales();Guardar()"><i class="fa fa-arrow-down"></i> Agregar</button>
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
          <div class="table-responsive">
            <input type="hidden" name="" id="txt_num_lin" value="0">
            <input type="hidden" name="" id="txt_num_item" value="0">
            <input type="hidden" name="txt_neg" id="txt_neg" value="false">
            <div id="tabla">
              
            </div>
            

               
          </div>
        </div>
      </div>
      
    </div>
  </div>
</div>
  

<div class="modal fade" id="modal_procedimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Cambiar procedimiento</h5>
      </div>
      <div class="modal-body">
         <div class="row">
          <div class="col-sm-12">
            Nombre de procedimiento
            <input type="text" class="form-control form-control-sm" name="txt_new_proce" id="txt_new_proce">
          </div>        
         </div> 
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="guardar_new_pro();">Guardar</button>
          <button type="button" class="btn btn-default btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cerrar</button>
        </div>
    </div>
  </div>
</div>
