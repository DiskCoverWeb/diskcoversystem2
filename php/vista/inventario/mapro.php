<?php
?>
<script>
	modulo ='<?php echo $_GET['mod'];?>';
	$(document).ready(function(){
    console.log(modulo);
    if (modulo == '03'){
        mayorizar_productos();
    }
	});
</script>	
<script src="../../dist/js/inventario/mapro.js"></script>