<script src="../../dist/js/contabilidad/Subcta_proyectos.js"></script>
<!--Mas pequeño de la clase definia por BS5 (Para acoplar el contenido de los boxes)-->
<style>
  .font-box { 
    font-size: 0.73rem
  }
</style>
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
<div class="row row-cols-auto">
  <div class="btn-group">
      <a  href="./contabilidad.php?mod=contabilidad#" data-bs-toggle="tooltip" title="Salir de modulo" class="btn btn-outline-secondary btn-sm">
        <img src="../../img/png/salire.png">
      </a>
      <a href="#" onclick="imprimir_excel()" data-bs-toggle="tooltip" class="btn btn-outline-secondary btn-sm" title="Descargar excel" id='imprimir_excel'>
            <img src="../../img/png/table_excel.png">
      </a>      
      <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Autorizar" onclick="todas();">
        <img src="../../img/png/autorizar1.png">
      </button>
  </div>
</div>
<div class="card">
  <div class="card-body"> 
    <form  id="form_filtros"> 
      <div class="row">
        <div class="col-12 col-md-4">
          <b>Proyecto</b>
          <select class="form-select form-select-sm font-box" id="ddl_proyecto" name="ddl_proyecto" onchange="DCCtasProyecto()">
            <option value="">Seleccione</option>
          </select>                 
        </div>
        <div class="col-12 col-md-4">
            <b>Cuentas de Proyecto</b>
            <select class="form-select form-select-sm font-box" id="ddl_cuenta_pro" name="ddl_cuenta_pro">
              <option value="">Seleccione</option>
            </select>                 
        </div>
        <div class="col-12 col-md-4">
            <b>SubModulos de proyecto</b>
            <select class="form-select form-select-sm font-box" id="ddl_sub_pro" name="ddl_sub_pro" onblur="insertar()">
              <option value="">Seleccione</option>
            </select>                 
        </div>
      </div>
    </form>
  </div>
</div>
<div class="card">
  <div class="card-body">
  	<div class="table-responsive" >
  		<table class="table text-sm w-100" id="tbl_tabla">
        <thead>
          <tr>
            <th class="text-center"></th>
            <th class="text-center">Cta</th>
            <th class="text-center">Cuenta</th>
            <th class="text-center">Detalle</th>
            <th class="text-center">Codigo</th>
            <th class="text-center">ID</th>
          </tr>
        </thead>
      </table>  
  	</div>    	
  </div>
</div>
    