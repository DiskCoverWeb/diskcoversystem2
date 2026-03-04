<?php date_default_timezone_set('America/Guayaquil'); $ordenNo = '-1'; if(isset($_GET['ordenNo']) && $_GET['ordenNo']!=''){$ordenNo = $_GET['ordenNo'];}?>

<script type="text/javascript">
 var ordenNo = '<?php echo $ordenNo; ?>';
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
<script type="text/javascript" src="../../dist/js/inventario/aprob_orden_trabajo_const.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
   contratistas();
   ddl_cuenta_contable();
   ddl_Proceso();
   ddl_Grupo();
   ddl_Rubro();
  })

</script>
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
        <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
                print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
            <img src="../../img/png/salire.png">
        </a>
        <button type="button" class="btn btn-outline-secondary" title="Informe excel" onclick="imprimir_excel()" >
          <img src="../../img/png/excel2.png">
        </button>
        <button type="button" class="btn btn-outline-secondary" title="Informe pdf" onclick="imprimir_pdf()">
          <img src="../../img/png/pdf.png">
        </button>
        <button title="Guardar"  class="btn btn-outline-secondary" onclick="grabar_solicitud_proveedor()">
          <img src="../../img/png/grabar.png" >
        </button>
      </div>
  </div>
</div>
<div class="row mb-2">
  <div class="card">
    <div class="card-body">
       <div class="row">
         <div class="col-sm-12">
              <div class="table-responsive">
                <table class="table w-100" id="tbl_lista_solicitud">
                  <thead>
                     <th>No</th>
                     <th>Contratista</th>
                     <th>Orden de trabajo</th>
                     <th>Fecha</th>
                     <th></th>
                  </thead>
                  <tbody>
                    <tr>
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
    </div>    
  </div>  
</div>
