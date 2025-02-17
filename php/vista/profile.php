<?php require_once(dirname(__Dir__,2).'/headers/header2.php');?>
<script>
    var nombreNuevaFoto = '<?php echo $_SESSION['INGRESO']['Foto']?>';
    $(document).ready(function(){
        $('#file_img').on("change", function(){
            var input = this;
            if(input.files && input.files[0]){
                var reader = new FileReader();
                nombreNuevaFoto = input.files[0].name; 
                reader.onload = function(e){
                    $('#img_foto1').attr("src", e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        });
    });

    var foto_o = '<?php echo $_SESSION['INGRESO']['Foto']?>';
    var nombre_o = '<?php echo $_SESSION['INGRESO']['Nombre_Completo']?>';
   
    
    function cancelar(){
        let nombre = $('#nombre_prof').val();  
        let foto = nombreNuevaFoto;
        if (foto == foto_o && nombre == nombre_o){
            window.location.href = 'modulos.php';
        } else {
            Swal.fire({
                title: 'Datos sin guardar, ¿Continuar?',
                text: '',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si!'
            }).then((result) => {
                if(result.isConfirmed){
                    window.location.href = 'modulos.php';
                }
            })
        }
    }

    function confirmar(){
        let nombre = $('#nombre_prof').val();
        let foto = nombreNuevaFoto;
        var formData = new FormData();
        formData.append('Foto', $('#file_img')[0].files[0]);
        formData.append('Nombre_Completo', nombre);
        if (foto == foto_o && nombre == nombre_o){
            Swal.fire({
                title: 'Error',
                text: 'Información no modificada, modifica para actualizar',
                icon: 'error',
                confirmButtonText: 'Aceptar',
            })
        } else {
            Swal.fire({
                title: '¿Estás seguro de actualizar la información?',
                text: '',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si!'
            }).then((result) => {
                if(result.isConfirmed){
                    actualizar_datos(formData);
                }
            })
        }
        
    }

    function actualizar_datos(formData){
        $.ajax({
            type: 'POST',
            url: '../controlador/panel.php?actualizar_datos=true',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response){
                if (response==1){
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'Datos actualizados',
                        icon: 'success',
                        confirmButtonText: 'Aceptar',
                    }).then((result)=>{
                        if (result.isConfirmed){
                            window.location.href = 'modulos.php';
                        }
                    });
                } else if (response == -1){
                    Swal.fire({
                        title: 'Error',
                        text: 'Ocurrio un problema, por favor intente nuevamente',
                        icon: 'error',
                        confirmButtonText: 'Aceptar',
                    })
                } else if (response == -2) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Formato no admitido, por favor insertar una imagen',
                        icon: 'error',
                        confirmButtonText: 'Aceptar',
                    })
                }
            }
        })
    }

</script>
<div class="text-center">
    <h3>Perfil de usuario</h3>
    <image id="img_foto1" src="../../img/usuarios/<?php echo $_SESSION['INGRESO']['Foto']?>" class="user-img-lg"></image>
    <div>
        <input type="file" class="d-none" id="file_img" name="file_img">
        <label class="btn btn-primary btn-sm" for="file_img">Cambiar imagen</label>
    </div>
    <div class="d-flex justify-content-center mt-4">
        <div class="col-12 col-lg-4">
            <div class="text-start">
                <h6><b>Datos Personales:</b></h6>
            </div>
            <div class="row row-cols-auto">
                <div class="col-12">
                    <div class="row row-cols-auto">
                        <div>
                            <i class="bx bx-user fs-4"></i>
                        </div>
                        <div class="d-flex align-items-center">
                            <b class="text-start">Nombre: </b>
                        </div>
                        <div class="d-flex align-items-center flex-grow-1">
                            <input id="nombre_prof" type="input" class="form-control form-control-sm" value='<?php echo $_SESSION['INGRESO']['Nombre_Completo']?>'>  
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row row-cols-auto">
                <div>
                    <i class="bx bx-id-card fs-4"></i>
                </div>
                <div class="d-flex align-items-center">
                    <b class="text-start">ID: </b>
                </div>
                <div class="d-flex align-items-center flex-grow-1">
                    <input type="input" class="form-control form-control-sm" disabled value='<?php echo $_SESSION['INGRESO']['Id']?>'>
                </div>
            </div>
            <br>
            <button class="btn btn-sm btn-danger" onclick="cancelar()">Cancelar</button>
            <button class="btn btn-sm btn-primary" onclick="confirmar()">Cambiar Datos</button>
        </div>
    </div>
</div>
<?php require_once(dirname(__DIR__, 2).'/headers/footer2.php');?>