<?php  
  $formatter = new IntlDateFormatter('es_ES', IntlDateFormatter::FULL, IntlDateFormatter::NONE); 
  $fecha = new DateTime();
  $mes = $fecha->format('n');
  $formatter->setPattern('MMMM');
  $mesEspanol = $formatter->format($fecha);

// require_once("panel.php");
?>
<script type="text/javascript">

</script>

<div class="container-lg">
  <div class="row">
    <div class="col-lg-8 col-sm-10 col-md-8 col-xs-12"> 
     <div class="col-xs-1 col-md-1 col-sm-1 col-lg-1">
      <a  href="./contabilidad.php?mod=contabilidad#" title="Salir de modulo" class="btn btn-default">
        <img src="../../img/png/salire.png">
     </a>
    </div>    
     <div class="col-xs-1 col-md-1 col-sm-1 col-lg-1">
       <button type="button" class="btn btn-default dropdown-toggle" title="Año" data-toggle="dropdown">
        <img src="../../img/png/year.png">
         <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu" id="year">
      <li><a href="#">Tablet</a></li>
      <li><a href="#">Smartphone</a></li>
    </ul>
    </div>
    <div class="col-xs-1 col-md-1 col-sm-1 col-lg-1">                 
     <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" title="Mes">
       <img src="../../img/png/mes.png">
        <span class="caret"></span>
     </button>
     <ul class="dropdown-menu" role="menu" id="meses">      
      <li><a href="#">Smartphone</a></li>
    </ul>
   </div>
   <div class="col-xs-1 col-md-1 col-sm-1 col-lg-1">
     <button title="ATS"  class="btn btn-default" onclick="generar_ats()">
       <img src="../../img/png/ats.png" >
     </button>
   </div>
   <div class="col-xs-1 col-md-1 col-sm-1 col-lg-1">
     <button title="ATS Financ"  class="btn btn-default" onclick="vista_ATS()">
       <img src="../../img/png/ats_fin.png" >
     </button>
   </div>
   <div class="col-xs-1 col-md-1 col-sm-1 col-lg-1">
     <button title="REOC"  class="btn btn-default" onclick="downloadURI()">
       <img src="../../img/png/es.png" >
     </button>
   </div>
   <div class="col-xs-1 col-md-1 col-sm-1 col-lg-1">
     <button title="RDEP"  class="btn btn-default" onclick="grabar_cuenta()">
       <img src="../../img/png/bc.png" >
     </button>
   </div>  
 </div>
</div>
<div class="row">

 
  <input type="hidden" name="" value=" <?php echo $mesEspanol; ?>" id="txt_mes">
  <input type="hidden" name="" value="<?php echo date('Y');?>" id="txt_year">
      
  <div class="container-lg" style="padding: 20px">
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#home" id="home_">ANEXO TRANSACCIONAL</a></li>
      <li><a data-toggle="tab" href="#menu1">ANEXO DE RELACION DE DEPENDENCIA</a></li>
    </ul>
    <div class="tab-content">
        <div id="home" class="tab-pane fade in active" style="overflow-y: scroll; min-height:450px; width: auto; background: gray;">
         
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

