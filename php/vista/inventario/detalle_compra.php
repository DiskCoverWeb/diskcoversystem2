<?php $orden = '';  if(isset($_GET['orden'])){  $orden = $_GET['orden']; } ?>

<script type="text/javascript">
  var orden = '<?php echo $orden; ?>';
  $(document).ready(function () {
    if(orden!='')
    {
      pedidos_compra_solicitados(orden);
      lineas_compras_solicitados(orden);
    }
  })
</script>

<script type="text/javascript" src="../../dist/js/inventario/detalle_compra.js"></script>
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
        <a href="inicio.php?mod=<?php echo $_SESSION['INGRESO']['modulo_']; ?>" title="Salir de modulo" class="btn btn-outline-secondary">
              <img src="../../img/png/salire.png">
        </a>
         <button type="button" title="Informe excel" onclick="imprimir_excel()" class="btn btn-outline-secondary">
            <img src="../../img/png/excel2.png">
          </button>
           <button type="button" title="Informe pdf" onclick="imprimir_pdf()" class="btn btn-outline-secondary">
            <img src="../../img/png/pdf.png">
          </button>
           <button title="Generar comrpobante" onclick="grabar_kardex()" class="btn btn-outline-secondary">
            <img src="../../img/png/grabar.png" >
          </button>
      </div>
  </div>
</div>
<div class="row mb-2">
  <div class="card">
    <div class="card-body">
      <div class="row">
         <div class="col-sm-4">
          <b>Numero de orden </b><br>
          <span id="lbl_orden"></span>
        </div>
         <div class="col-sm-3">
          <b>Contratista</b><br>
          <span id="lbl_contratista"></span>
        </div>
        <div class="col-sm-3">
          <b>Total</b><br>
          <span id="lbl_total"></span>
        </div>
      </div> 
    </div>  
  </div>
</div>
<div class="row mb-2">
  <div class="card">
    <div class="card-body">
      <div class="row">
          <form id="form_aprobacion">
          <div class="col-sm-12" id="tbl_body">
             
          </div> 
          </form>   
        </div>  
    </div>    
  </div>  
</div>
  
