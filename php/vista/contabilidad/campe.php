<?php  date_default_timezone_set('America/Guayaquil');  //print_r($_SESSION);die();//print_r($_SESSION['INGRESO']);die(); 
$_SESSION['INGRESO']['ti']='Cambio Periodo'; ?>

<script type="text/javascript">
  $(document).ready(function () {	
		lista_periodos();
		$('#myModal').modal('show');  
  });

function lista_periodos()
{
	var modulo = '<?php $_SESSION['INGRESO']['modulo']; ?>';
     $.ajax({
       data:  {modulo:modulo},
      url:   '../controlador/contabilidad/campeC.php?lista=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        $('#listado').html(response);
      }
    });
}
function seleccionar_periodo(periodo='.')
{
    // Swal.fire(periodo,'','success'); 	 
    var seleccionado = $('#opcion').val();
    if(periodo!='.')
    {
        $('#item_'+periodo).addClass('alert-info');
    $('#opcion').val(periodo);
        if(seleccionado!='')
        {
            $('#item_'+seleccionado).removeClass('alert-info');
            $('#item_'+seleccionado).addClass('alert-default');
        }
    }else
    {

        $('#item_actual').addClass('alert-info');
        $('#opcion').val('actual');
            if(seleccionado!='')
                {
                    $('#item_'+seleccionado).removeClass('alert-info');
                    $('#item_'+seleccionado).addClass('alert-default');
                }
    }
}
function cambiarPeriodo()
{
    var periodo = $('#opcion').val();
        $.ajax({
        data:  {periodo:periodo},
        url:   '../controlador/contabilidad/campeC.php?cambiarPeriodo=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
        // console.log(response);
        if(response==1)
        {
            Swal.fire('Periodo Cambiado','','success').then(function(){ location.href ="inicio.php?mod=01"; })
        }
        }
    });


    // var periodo = document.getElementById('opcion');
    // $.post('ajax/vista_ajax.php'
    // , {ajax_page: 'cambiarPeriodo', campo: periodo.value }, function(data){
    // 	//$('div.'+idMensaje).html(data); 
    // 	//alert('entrooo ');
    // 	Swal.fire({
    // 		  title: 'Cambio periodo!',
    // 		  text: 'periodo cambiado con existo.',
                
    // 		  animation: false
    // 		}).then((result) => {
    // 				  if (
    // 					result.value
    // 				  ) {
    // 					console.log('I was closed by the timer');
    // 					location.href ="contabilidad.php?mod=contabilidad";
    // 				  }
    // 				});
    // });
}

</script>

<div class="container-lg">
  <div class="row">
    <div class="col-lg-6 col-sm-10 col-md-6 col-xs-12">
       <!-- <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Grabar factura" onclick="boton1()"><img src="../../img/png/grabar.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Actualizar Productos, Marcas y Bodegas" onclick="boton2()"><img src="../../img/png/update.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Asignar orden de trabajo" onclick="boton3()"><img src="../../img/png/taskboard.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Asignar guia de remision" onclick="boton4()"><img src="../../img/png/ats.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Asignar suscripcion / contrato" onclick="boton5()"><img src="../../img/png/file2.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Asignar reserva" onclick="boton6()"><img src="../../img/png/archivero2.png"></button>
        </div> -->
         <!-- <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a href="#" class="btn btn-default" title="Asignar reserva" onclick="Autorizar_Factura_Actual2();" target="_blank" ><img src="../../img/png/archivero2.png"></a>
        </div> -->
 </div>
</div>
<div class="container" style="height:300px">
	
	
</div>
<div class="modal fade" id="myModal" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" >
    		<div class="modal-content">
    				<div class="modal-header">
				  		<button type="button" class="close" data-dismiss="modal">&times;</button>
				  		<h4 class="modal-title"><img  width='5%'  height='5%' src="../../img/jpg/logo.jpg"> Cambio de periodo</h4>
						</div>
						<div class="modal-body">
								<div class="form-group">
									<div class="row" style="height:300px; overflow-y:scroll;">
										<input type="hidden" name="opcion" id="opcion">
										<div class="col-sm-12" id="listado">
											<!-- <div class="alert alert-default alert-dismissible" style="border:1px solid">
				                <h4><i class="icon fa fa-info"></i> Alert!</h4>
				                Info alert preview. This alert is dismissable.
				              </div>	 -->											
										</div>																				
									</div>
									<div class="row">
										<div class="col-sm-12">
											<p  align='left'><img  width='5%'  height='5%' src="../../img/jpg/logo.jpg">En caso de dudas,comuniquese al centro de atención al cliente, a los telefonos:<br>+593-2-321-0051 / +593-9-8035-5483</p>											
										</div>										
									</div>
								

									<!-- <p align='left' id='texto'> -->
										<?php
											// for($i=0;$i<count($texto);$i++)
											// {
											// 	echo ''.$texto[$i].'<br>';														
											// }
										?>	
									<!-- </p> -->
								</div>
						</div>
						<div class="modal-footer" style="background-color: #fff;">
							<button id="btnCopiar" class="btn btn-outline-primary" onclick='cambiarPeriodo();'>Cambiar</button>
						    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
						</div>
			  </div>			  
			</div>
		</div>
			