<?php  
  $formatter = new IntlDateFormatter('es_ES', IntlDateFormatter::FULL, IntlDateFormatter::NONE); 
  $fecha = new DateTime();
  $mes = $fecha->format('n');
  $formatter->setPattern('MMMM');
  $mesEspanol = $formatter->format($fecha);

// require_once("panel.php");
?>
<script src="../../dist/js/contabilidad/anexos_trans.js"></script>

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

<div class="container-lg">
  <div class="row row-cols auto">
    <div class="col-lg-8 col-sm-10 col-md-8"> 
      <a  href="./contabilidad.php?mod=contabilidad#" title="Salir de modulo" class="btn btn-outline-secondary btn-sm">
        <img src="../../img/png/salire.png">
      </a>
      <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" title="Año" data-bs-toggle="dropdown">
      <img src="../../img/png/year.png">
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu" id="year">
        <li><a class="dropdown-item" href="#">Tablet</a></li>
        <li><a class="dropdown-item" href="#">Smartphone</a></li>
      </ul>
      <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" title="Mes">
        <img src="../../img/png/mes.png">
          <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu" id="meses">      
        <li><a class="dropdown-item" href="#">Smartphone</a></li>
      </ul>
      <button title="ATS"  class="btn btn-outline-secondary btn-sm" onclick="generar_ats()">
        <img src="../../img/png/ats.png" >
      </button>
      <button title="ATS Financ"  class="btn btn-outline-secondary btn-sm" onclick="vista_ATS()">
        <img src="../../img/png/ats_fin.png" >
      </button>
      <button title="REOC"  class="btn btn-outline-secondary btn-sm" onclick="downloadURI()">
        <img src="../../img/png/es.png" >
      </button>
      <button title="RDEP"  class="btn btn-outline-secondary btn-sm" onclick="grabar_cuenta()">
        <img src="../../img/png/bc.png" >
      </button>
 </div>
</div>
<div class="row">

 
  <input type="hidden" name="" value=" <?php echo $mesEspanol; ?>" id="txt_mes">
  <input type="hidden" name="" value="<?php echo date('Y');?>" id="txt_year">
      
  <div class="container-lg p-2 ">
    <ul class="nav nav-pills mb-3" role="tablist">
      <li class="active nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#home" id="home_">ANEXO TRANSACCIONAL</a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#menu1">ANEXO DE RELACION DE DEPENDENCIA</a></li>
    </ul>
    <div class="tab-content">
        <div id="home" class="tab-pane fade show active" style="overflow-y: scroll; min-height:450px; width: auto; background: gray;">
         
        </div>
        <div id="menu1" class="tab-pane fade">
          <h3>ANEXO DE RELACION DE DEPENDENCIA</h3>
            <p>Some content in menu 1.</p>
        </div>
      </div>
  </div>  
</div>  
</div>


<!-- partial:index.partial.html -->

<!-- partial -->
<!-- //<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->

