<script type="text/javascript">
  $(document).ready(function()
  {
  //$('#TipoSuper_MYSQL').val('Supervisor');
  //$('#clave_supervisor').modal('show');
  IngClave('Gerente');
  })

</script>
<script src="../../dist/js/cierre_mes.js"></script>
<div class="container-lg">
  <div class="row">
    <!-- <button class="" onclick="abrir_modal()">o</button> -->
  </div>
</div>
<div class="" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="">
    <div class="">
      <div class="">
        <h4 class="">Cierre de mes</h4>
      </div>
      <div class="">
         <div class="">
           <div class="">
            <button class="" title="Año de proceso" data-toggle="tooltip" onclick="cambiar_year()">Año de proceso</button>    
              <div  class="">
                <div class="" id="LstMeses">
                  
                </div>                
              </div>             
           </div>
           <div class="">
             <button class="" title="Grabar" data-toggle="tooltip" onclick="guardar()"> <img src="../../img/png/grabar.png" ><br>&nbsp;&nbsp;Grabar&nbsp;&nbsp;</button>     
              <button class=""  data-dismiss="modal" onclick="limpiar_IngresoClave_MYSQL();"> <img src="../../img/png/bloqueo.png" ><br> Cancelar</button>     
             
           </div>
         </div>
      </div>
    </div>

  </div>
</div>