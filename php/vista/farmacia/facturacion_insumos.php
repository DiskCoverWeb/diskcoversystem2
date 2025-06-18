<?php 
$cod = ''; $ci =''; if(isset($_GET['comprobante'])){$cod = $_GET['comprobante'];} if(isset($_GET['ci'])){$ci = $_GET['ci'];}?>
<script>
  var ci = '<?php echo $ci; ?>';
  var cod = '<?php echo $cod; ?>';
</script>
<script src="../../dist/js/farmacia/facturacion_insumos.js"></script>

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
    <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0]);?>#" title="Salir de modulo" class="btn btn-outline-secondary">
      <img src="../../img/png/salire.png">
    </a>
    <a href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0]);?>&acc=pacientes" type="button" class="btn btn-outline-secondary" id="imprimir_pdf" title="Pacientes">
      <img src="../../img/png/pacientes.png">
    </a>      
    <a href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0]);?>&acc=vis_descargos" type="button" class="btn btn-outline-secondary" id="imprimir_excel" title="Descargos">
      <img src="../../img/png/descargos.png">
    </a>
    <a href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0]);?>&acc=articulos" title="Ingresar Articulosr"  class="btn btn-outline-secondary" onclick="">
      <img src="../../img/png/articulos.png" >
    </a>
    <button type="button" class="btn btn-outline-secondary"  data-bs-toggle="dropdown" title="Generar pdf" aria-expanded="true">
       <i class="fa fa-caret-down"></i>
       <img src="../../img/png/pdf.png"></button>
    <ul class="dropdown-menu">
     <li><a href="#"  class="dropdown-item"id="imprimir_pdf" onclick="Ver_Comprobante_clinica('<?php echo $cod; ?>')" >Para Clinica</a></li>
      <li><a href="#" class="dropdown-item" id="imprimir_pdf_2" onclick="Ver_Comprobante('<?php echo $cod; ?>')" >Para paciente</a></li>
    </ul>
    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="dropdown" aria-expanded="true" title="Generar pdf"> 
     <i class="fa fa-caret-down"></i>
      <img src="../../img/png/table_excel.png">
    </button>
     <ul class="dropdown-menu">
     <li><a href="#" class="dropdown-item" id="imprimir_pdf" onclick="reporte_excel_clinica('<?php echo $cod; ?>')">Para Clinica</a></li>
      <li><a href="#" class="dropdown-item" id="imprimir_pdf_2" onclick="reporte_excel('<?php echo $cod; ?>')" >Para paciente</a></li>
    </ul>
 	</div>
</div>
<div class="row mb-2">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Datos de paciente</h5>
        <div class="row">
          <div class="col-sm-4">
            <b>Paciente: </b><i id="paciente">asdasd</i>            
          </div>
          <div class="col-sm-4">
            <b>Comprobante: </b><i id="comp">asdasd</i>           
          </div>
          <div class="col-sm-4">
            <b>Fecha: </b><i id="fecha">asdasd</i>            
          </div>          
        </div>
        <div class="row">
          <div class="col-sm-8">
            <b>Detalle: </b><i id="detalle">asdasd</i>    
          </div>
          <div class="col-sm-4">
            <b>Numero Factura: </b><i id="factura">01</i>    
          </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12 text-end">
              <button class="btn btn-primary btn-sm" onclick="preview()">Guardar utilidad</button>   
            </div>
            <div class="col-sm-12 text-end">
              <h4><b>Gran Total:</b> <b id="txt_total2">0.00</b></h4>
            </div>    
        </div>
    </div>    
  </div>  
</div>

<div class="row">
  <div class="card">
    <div class="card-body">
      <div class="col-sm-12">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <th>Codigo</th>
              <th>Producto</th>
              <th>Cantidad</th>
              <th>Precio Uni</th>
              <th>Precio Total</th>
              <th> % Utilidad</th>
              <th>Valor utilidad</th>
              <th>Gran total</th>
              <th></th>
            </thead>
            <tbody id="tbl_body">
              
            </tbody>
          </table>
        </div>
      </div>
    </div>    
  </div>  
</div>


