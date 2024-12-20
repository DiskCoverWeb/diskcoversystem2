<?php include('../../headers/header_modales.php');?>
<script type="text/javascript">
	 $( document ).ready(function() {
	$('body').css('padding-top','0px');
});
</script>

<!-- <div class="content-wrapper"> -->
<?php

if(isset($_GET['FSubCtas']))
{
	require_once('contabilidad/FSubCtas.php');
}
if(isset($_GET['FCompras']))
{
	require_once('contabilidad/FCompras.php');
}
if(isset($_GET['FExportaciones']))
{
	require_once('contabilidad/FExportaciones.php');
}
if(isset($_GET['FImportaciones']))
{
	require_once('contabilidad/FImportaciones.php');
}
if(isset($_GET['FVentas']))
{
	require_once('contabilidad/FVentas.php');
}
if(isset($_GET['FCliente']))
{
	require_once('contabilidad/FCliente.php');
}
if(isset($_GET['FProveedores']))
{
	require_once('contabilidad/FProveedores.php');
}
if(isset($_GET['FAbonos']))
{
	require_once('contabilidad/FAbonos.php');
}
if(isset($_GET['FAbonoAnticipado']))
{
	require_once('contabilidad/FAbonoAnticipado.php');
}
if(isset($_GET['FInfoError']))
{
	require_once('contabilidad/FInfoError.php');
}
if(isset($_GET['Ftransporte']))
{
	require_once('gestion_social/Ftransporte.php');
}

?>

<!-- </section> -->
  <!-- </div> -->
<!--Ocultar el footer porque tiene funciones integradas, no eliminar-->
	<?php
	require_once("../../headers/footer.php");
	?>

<!-- 
<div class="modal fade" id="myModal_espera" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body text-center">
        	<img src="../../img/gif/loader4.1.gif" width="80%"> 	
        </div>
      </div>
    </div>
  </div>	 -->			