<script type="text/javascript">
 
</script>
<script src="../../dist/js/contabilidad/cierre_mes.js"></script>
<div class="container-lg">
  <div class="row">
    <!-- <button class="" onclick="abrir_modal()">o</button> -->
  </div>
</div>
<div id="modal_cierre" class="modal fade" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content p-2">
      <div class="modal-header">
        <h4 class="modal-title">Cierre de mes</h4>
      </div>
      <div class="modal-body">
         <div class="row">
           <div class="col-8">
            <button class="btn btn-outline-secondary btn-sm" title="Año de proceso" data-toggle="tooltip" onclick="cambiar_year()">Año de proceso</button>    
              <div  class="row">
                <div class="col-12" id="LstMeses">                   
                </div>                
              </div>             
           </div>
           <div class="col-sm-4 text-end">
              <button class="btn btn-outline-secondary btn-sm" title="Grabar" data-bs-toggle="tooltip" onclick="guardar()"> <img src="../../img/png/grabar.png" ><br>&nbsp;&nbsp;Grabar&nbsp;&nbsp;</button>     
              <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal" onclick="limpiar_IngresoClave_MYSQL();"> <img src="../../img/png/bloqueo.png" ><br> Cancelar</button>     
           </div>
         </div>
      </div>
    </div>
  </div>
</div>