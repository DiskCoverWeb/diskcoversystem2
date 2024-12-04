function Form_Activate_ModalChangeValores(NomCta, Cta, Cuenta_No, Debe, Haber, NomCtaSup, NoCheque, Asiento, TP, Numero, Fecha) {
    if (Cta !== '.') {
        $('#Label6ModalChangeValores').text("FECHA: " + Fecha + " DEL COMPROBANTE " + TP + "-" + Numero + "\n" + Cta + " - " + Cuenta_No);
        $('#TxtConceptoChangeValores').val(NomCta);
        $('#TxtConceptoChangeValoresOld').val(NomCta);
        $('#TxtDebeChangeValores').val(Debe);
        TextoValido($('#TxtDebeChangeValores'), true, false, 2)
        $('#TxtHaberChangeValores').val(Haber);
        TextoValido($('#TxtHaberChangeValores'), true, false, 2)
        $('#TxtDetalleChangeValores').val(NomCtaSup);
        $('#TxtDepositoChangeValores').val(NoCheque);
        $('#TPChangeValores').val(TP);
        $('#NumeroChangeValores').val(Numero);
        $('#AsientoChangeValores').val(Asiento);
        $('#CtaChangeValores').val(Cta);
        setTimeout(function(){$('#TxtConceptoChangeValores').focus();}, 500);
    } else {
        Swal.fire({
            title: 'No existe Cuenta para cambiar',
            type: 'warning',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ok'
        }).then((result) => {
            $('#ModalChangeValores').modal('hide');
        })
    }
}

function Cambiar_Valores_Modal() {
    $('#myModal_espera').modal('show');
    $.ajax({
        url:   '../controlador/contabilidad/FChangeValoresC.php?CambiarValores=true',
        type:  'post',
        dataType: 'json',
        data:  $("#FormChangeValores").serialize(),
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