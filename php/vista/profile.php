<?php require_once(dirname(__Dir__,2).'/headers/header.php');

?>
<script>
    var nombre_o = '<?php echo $_SESSION['INGRESO']['Nombre_Completo']?>';
    
    $(window).on("beforeunload", function (event){
        cancelar();
    });
    
    function guardar_imagen(){
        var form = new FormData(document.getElementById("form_img"));
        // Aseguramos que el formulario existe y tiene un archivo seleccionado
        if (!form) {
            console.error("Formulario no encontrado.");
            return;
        }
        var inputFile = document.getElementById("file_img");
        if (inputFile.files.length === 0) {
            console.log("No se ha seleccionado ningún archivo.");
            return;
        }
        $.ajax({
            url: '../controlador/panel.php?cargar_imagen=true',
            type: 'post',
            data: form,
            contentType: false,
            processData: false,
            dataType:'json',
         // beforeSend: function () {
         //        $("#foto_alumno").attr('src',"../img/gif/proce.gif");
         //     },
            success: function(response) {
                if(response==-1){
                    
                }else if(response ==-2){
                    Swal.fire(
                    '',
                    'Asegurese que el archivo subido sea una imagen.',
                    'error')
                }else{
                    swal.fire({
                        title: '¡Éxito!',
                        text: 'Imagen actualizada',
                        icon: 'success',
                        confirmButtonText: 'OK',
                    });
                    $('#img_foto').prop('src','../../img/usuarios/'+response+'?'+Math.random());
                    $('#img_foto1').prop('src','../../img/usuarios/'+response+'?'+Math.random());
                    $('#file_img').val('');  
                } 
            }
        });
    }
</script>
<h4 class="border-bottom">Perfil de usuario</h4>
<br>
<div class="row">
    <div class="col-4 text-center">
        <image id="img_foto1" src="../../img/usuarios/<?php echo $_SESSION['INGRESO']['Foto']?>" class="img-fluid border rounded-circle"></image>
        <div class="pt-2">
            <form enctype="multipart/form-data" id="form_img" method="post">
                <input type="file" class="d-none" id="file_img" name="file_img" onchange="guardar_imagen()">
                <label class="btn btn-primary btn-sm" for="file_img">Actualizar imagen</label>
            </form>
        </div>
    </div>
    <div class="col-8">
        <div>
            <div class="col-12">
                <ul class="nav nav-tabs justify-content-start" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a data-bs-toggle="pill" href="#Primary-US" id="Titulo_usuario" class="nav-link active">
                            <div class="tab-title">DATOS DE USUARIO</div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a data-bs-toggle="pill" href="#Primary-EP" id="Titulo_empresa" class="nav-link">
                            <div class="tab-title">DATOS DE EMPRESA</div>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="Primary-US">
                        <div class="col-12 border rounded-bottom p-2">
                            <div class="row g-2 row-cols-auto">
                                <div>
                                    <i class="bx bx-user fs-4"></i>
                                </div>
                                <div class="col-2 d-flex align-items-center">
                                    <b>Nombre:</b>
                                </div>
                                <div class="d-flex flex-grow-1">
                                    <input id="nombre_prof" type="input" disabled class="form-control form-control-sm" value='<?php echo $_SESSION['INGRESO']['Nombre_Completo']?>'>  
                                </div>
                            </div>
                            <br>
                            <div class="row g-2 row-cols-auto">
                                <div>
                                    <i class="bx bx-id-card fs-4"></i>
                                </div>
                                <div class="col-2 d-flex align-items-center">
                                    <b class="">ID:</b>
                                </div>
                                <div class="d-flex flex-grow-1"> 
                                    <input type="input" class="form-control form-control-sm" disabled value='<?php echo $_SESSION['INGRESO']['Id']?>'>
                                </div>
                            </div>
                            <br>
                            <div class="row g-2 row-cols-auto">
                                <div>
                                    <i class="bx bx-user fs-4"></i>
                                </div>
                                <div class="col-2 d-flex align-items-center">
                                    <b class="">Usuario :</b>
                                </div>
                                <div class="d-flex flex-grow-1"> 
                                    <input type="input" class="form-control form-control-sm" disabled value='<?php echo $_SESSION['INGRESO']['usuario']?>'>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade" id="Primary-EP">
                        <div class="col-12 border rounded-bottom p-2">
                            <div class="row g-2 row-cols-auto">
                                <div>
                                    <i class="bx bxs-building fs-4"></i>
                                </div>
                                <div class="d-flex col-2 align-items-center">
                                    <b>Razon Social:</b>
                                </div>
                                <div class="d-flex flex-grow-1">
                                    <input type="input" class="form-control form-control-sm" disabled value='<?php echo $_SESSION['INGRESO']['Razon_Social']?>'>
                                </div>
                            </div>
                            <br>
                            <div class="row g-2 row-cols-auto">
                                <div>
                                    <i class="bx bxs-institution fs-4"></i>
                                </div>
                                <div class="d-flex col-2 align-items-center">
                                    <b>Entidad:</b>
                                </div>
                                <div class="d-flex flex-grow-1">
                                    <input type="input" class="form-control form-control-sm" disabled value='<?php echo $_SESSION['INGRESO']['Entidad']?>'>
                                </div>
                            </div>
                            <br>
                            <div class="row g-2 row-cols-auto">
                                <div>
                                    <i class="bx bxs-building fs-4"></i>
                                </div>
                                <div class="d-flex col-2 align-items-center">
                                    <b>Empresa:</b>
                                </div>
                                <div class="d-flex flex-grow-1">
                                    <input type="input" class="form-control form-control-sm" disabled value='<?php echo $_SESSION['INGRESO']['noempr']?>'>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
<?php require_once(dirname(__DIR__, 2).'/headers/footer.php');?>