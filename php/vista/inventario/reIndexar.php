<?php
?>
<script>
	modulo ='<?php echo $_GET['mod'];?>';
	$(document).ready(function(){
    console.log(modulo);
    if (modulo == '03'){
        reindexar();
    }
	});
</script>	
<script src="../../dist/js/inventario/mapro.js"></script>