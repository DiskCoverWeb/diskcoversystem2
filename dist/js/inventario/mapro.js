function mayorizar_productos(){
    $.ajax({
        type: 'GET',
        url: '../controlador/inventario/mayorizar_productoC.php?mayorizar_producto=true',
        beforeSend: function () {
            $('#myModal_espera').modal('show');
        },
        success: function (data) {
            $('#myModal_espera').modal('hide');
            if (data == '1'){
                Swal.fire({
                    icon: 'success',
                    title: 'Terminado!',
                    text: 'Proceso de mayorización listo.'}).then((result) => {
                    if (result.value) {
                        location.href ='inicio.php?mod=03';
                    }
                });;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Proceso de mayorización fallido.'}).then((result) => {
                    if (result.value) {
                        location.href ='inicio.php?mod=03';
                    }
                });;
            }
            
        },
        error: function (error) {
            console.error('Error en la solicitud AJAX:', error);
            reject(error);
        }
    });
}