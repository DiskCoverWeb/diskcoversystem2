function Form_Activate_ModalChangeCa(Codigo1, Asiento, Producto, Codigo3, TP, Numero) {
    if(Codigo1 != '<?php echo G_NINGUNO ?>'){
        $("#Codigo1ChangeCta").val(Codigo1)
        $("#AsientoChangeCta").val(Asiento)
        $("#ProductoChangeCta").val(Producto)
        $("#TPChangeCta").val(TP)
        $("#NumeroChangeCta").val(Numero)
        $("#Label6ModalChangeCa").html("<b>"+Codigo3+"<br>SELECCIONE LA CUENTA A CAMBIAR</b>")
        $('#DCCuentaChangeCa').select2({
            placeholder: 'Seleccionar Cuenta',
            width:'90%',
            ajax: {
              url:   '../controlador/contabilidad/FChangeCtaC.php?CargarDCCuenta=true&Codigo1='+Codigo1,
              dataType: 'json',
              delay: 250,
              processResults: function (data) {
                return {
                  results: data
                };
              },
              cache: true
            }
          });
    }else{
        Swal.fire({
             title: 'No existe Cuenta para cambiar',
             type: 'warning',
             showCancelButton: false,
             confirmButtonColor: '#3085d6',
             cancelButtonColor: '#d33',
             confirmButtonText: 'Ok'
           }).then((result) => {
                 $('#ModalChangeCa').modal('hide');
           })
    }
}

function CambiarCta() {
    let Codigo1 = $("#Codigo1ChangeCta").val()
    let Asiento = $("#AsientoChangeCta").val()
    let Producto = $("#ProductoChangeCta").val()
    let TP = $("#TPChangeCta").val()
    let Numero = $("#NumeroChangeCta").val()
    let Codigo2 = $("#DCCuentaChangeCa").val()
    $('#myModal_espera').modal('show');
    $.ajax({
        url:   '../controlador/contabilidad/FChangeCtaC.php?CambiarCta=true',
        type:  'post',
        dataType: 'json',
        data:  {
            'Codigo1':Codigo1,
            'Asiento':Asiento,
            'Producto':Producto,
            'Codigo2':Codigo2,
            'TP': TP,
            'Numero': Numero
        },
        success:  function (response) {
            $('#myModal_espera').modal('hide');
            Swal.fire({
             title: response.message,
             type: response.ico,
             showCancelButton: false,
             confirmButtonColor: '#3085d6',
             cancelButtonColor: '#d33',
             confirmButtonText: 'Ok'
           }).then((result) => {
                 $('#ModalChangeCa').modal('hide');
                 if(response.ico=='success' && typeof listar_comprobante === 'function' && listar_comprobante !== null && listar_comprobante !== undefined){
                     listar_comprobante()
                 }
           })
        },
          error: function (e) {
            $('#myModal_espera').modal('hide');
            alert("error inesperado al Cambiar la Cuenta")
          }
    });

}