<style>
   .small-text{
    font-size: 0.7rem; 
   }
   .text-box{
    font-size: 0.8rem;
   }
   .btn_f {  background-color: #CFE9EF;  color: #444;  border-color: #ddd;}
   .input-group .input-group-addon 
    {
      background-color: #CFE9EF;  color: #444;  border-color: #ddd;  border-bottom-left-radius: 5px;  border-top-left-radius:  5px;}
    #select2-cuentar-results .select2-results__option {   white-space: pre;  }
</style>
  <!-- =========================================INICIO DE PROGRAMACION =================================== -->
<?php
if (!isset($_SESSION))
  session_start();
$T_No = 1;
$SC_No = 0;
$variables_mod = '';
$ModificarComp = 0;
$CopiarComp = 0;
$NuevoComp = 1;
$load = 0;
if (isset($_GET["modificar"])) {
  $variables_mod = $_GET["TP"] . '-' . $_GET["com"];
  $ModificarComp = 1;
  $NuevoComp = 0;
}
if (isset($_GET["copiar"])) {
  $variables_mod = $_GET["TP"] . '-' . $_GET["com"];
  $CopiarComp = 1;
}
if (isset($_GET["num_load"])) {
  $load = 1;
}
?>

<script src="../../dist/js/incom.js"></script>
<script type="text/javascript">
var Trans_No = 1; var Ln_No = 1; var Ret_No = 1; var LnSC_No = 1;
function Form_Activate()
{ 
    var ModificarComp = '<?php echo $ModificarComp; ?>';
    var CopiarComp = '<?php echo $CopiarComp; ?>';
    var NuevoComp    = '<?php echo $NuevoComp; ?>';

  if(ModificarComp==1){
     // Control_Procesos Normal, "Modificar Comprobante de: " & Co.TP & " No. " & Co.Numero
    var comprobante = '<?php echo @$_GET["com"]; ?>';
    var tp = '<?php  echo @$_GET["TP"]; ?>';
    if(tp=='CD'){ tip = 'Diario';}
    else if(tp=='CI'){tip = 'Ingresos'}
    else if(tp=='CE'){tip = 'Egresos';}
    else if(tp=='ND'){tip = 'NotaDebito';}
    else if(tp=='NC'){tip= 'NotaCredito';}

    $("#num_com").html('Comprobante de '+tip+' No. <?php echo date('Y');?>-'+comprobante);
    Listar_Comprobante_SP(comprobante,tp);
  }
  
  if(CopiarComp==1)
  {
     // Control_Procesos Normal, "Copiando Comprobante de: " & Co.TP & " No. " & Co.Numero
    var comprobante = '<?php echo @$_GET["com"]; ?>';
    var tp = '<?php  echo @$_GET["TP"]; ?>';
     Listar_Comprobante_SP(comprobante,tp);
     $('#NuevoComp').val(1);
  }

  if(NuevoComp==1)
  {
    // console.log('ingresa nuevo');
     var Numero = 0;
     ExistenMovimientos();
  }
  
  // TipoBusqueda = "%"
  
  FormActivate();

 //  Llenar_Encabezado_Comprobante
 //  CalculosTotalAsientos AdoAsientos, LabelDebe, LabelHaber, LabelDiferencia
 //  Una_Vez = True
 // // 'Listamos lista de clientes para procesar comprobantes
  
 //  If UCaseStrg(Modulo) = "GASTOS" Then
 //     OpcTP(0).Visible = True
 //     OpcTP(1).Visible = False
 //     OpcTP(2).Visible = False
 //     OpcTP(3).Visible = False
 //     OpcTP(4).Visible = False
 //  End If
 //  If Bloquear_Control Then CmdGrabar.Enabled = False
 //  RatonNormal
 //  FComprobantes.WindowState = vbMaximized

 //  MBoxFecha.SetFocus
}

</script>
<script type="text/javascript">
    var cli = '<?php if (isset($_GET["cliente"])) {
      echo $_GET["cliente"];
    } ?>';    
    // console.log(cli);
    if(cli!='')
    {
      cargar_beneficiario(cli);
    }

   function ExistenMovimientos()
   {
     var CopiarComp = '<?php echo $CopiarComp; ?>';
      $.ajax({
        url:   '../controlador/contabilidad/incomC.php?ExistenMovimientos=true',
        type:  'post',
        data: {
          'Trans_No': Trans_No,
        },
        dataType: 'json',
        success:  function (response) {
         
         if(response ==1 && CopiarComp == 0){
               Swal.fire({
                 title: 'El Sistema se cerro de forma inesperada, existen movimientos en transito con su codigo de usuario. Desea recuperarlos? ',
                 text: "",
                 type: 'warning',
                 showCancelButton: true,
                 confirmButtonColor: '#3085d6',
                 cancelButtonColor: '#d33',
                 confirmButtonText: 'Si!',
                 allowOutsideClick: false
               }).then((result) => {
                 if (result.value!=true) {
                  borrar_asientos();
                 }
               })
                
             }

        }
      });

   }

  function Listar_Comprobante_SP(com,tp)
  {
    // console.log(com);
    // console.log(tp)
     var modificar = '<?php echo $variables_mod; ?>';
      $('#NuevoComp').val(modificar);
      $.ajax({
        url:   '../controlador/contabilidad/incomC.php?CallListar_Comprobante_SP=true',
        type:  'post',
        data: {
          'NumeroComp': com,
          'TP': tp,
        },
        dataType: 'json',
        success:  function (response) {
          if(cli=='')
          {
           Llenar_Encabezado_Comprobante();
          }
          FormActivate()
        }
      });
  }

  
  $(document).ready(function () {
    Form_Activate();
    cargar_cuenta();
    var modificar = '<?php echo $variables_mod; ?>';
    var load = '<?php echo $load; ?>';

    $('#codigo').on('keyup', function(event) {
        var codigoTecla = event.which || event.keyCode;
        console.log(codigoTecla);
        if(codigoTecla==27)
        {
          // tecla escape
          if($('#tipoc').val()=='CE')
          {
            $('#codigo').val('-1');
            agregar_diferencia();
          }
        }else if(codigoTecla==113){
          // tecla f2
          // cargar_modal();
        }
        
    });

    if(modificar!='')
    {
     
    }
    // else
    // {
    //   numero_comprobante();
    //   FormActivate()
    // }
    //Valida solo decimales en el modal CC
    $(document).on('keypress', 'td.editable-decimal', function(e) {
      var charCode = e.which || e.keyCode; // Obtiene el código del carácter
      var charTyped = String.fromCharCode(charCode);

      // Permite solo números y el punto decimal
      if (!charTyped.match(/[\d.]/) && charCode !== 8 && charCode !== 46) {
          e.preventDefault(); // Evita el ingreso del carácter
      }
    });

    $(document).on('input', 'td.editable-decimal', function() {
        var text = $(this).text();
        if (!/^[\d.]*$/.test(text)) {
            var newText = text.replace(/[^\d.]/g, '');
            $(this).text(newText);
        }
    });

     $("#btn_acep").blur(function () { if($('#modal_cuenta').hasClass('in')){if($('#txt_efectiv').is(':visible')){$('#txt_efectiv').trigger( "focus" );}else{$('#txt_moneda').trigger( "focus" );}}});


     window.addEventListener("message", function(event) {
        if (event.data === "closeModal") {
            $('#modal_subcuentas').modal('hide');
            $("#codigo").val('');            
            $("#cuentar").empty();
        }
    });
     window.addEventListener("message", function(event) {
        if (event.data === "closeModalG") {
            $('#modal_subcuentas').modal('hide');
            cargar_tablas_retenciones();
            cargar_tablas_contabilidad();
            cargar_totales_aseintos();
            $("#codigo").val('');            
            $("#cuentar").empty();
        }
    });

     //subcuenta
   window.addEventListener("message", function(event) {
        if (event.data === "closeModalSubCta") {
            $('#modal_subcuentas').modal('hide');
             cargar_tablas_contabilidad();
             cargar_totales_aseintos();
             cargar_tablas_sc();
            $("#codigo").val('');
            $("#cuentar").empty();
        }
    });

  });

    function agregar_diferencia()
    {
      modulo = '<?php echo $_SESSION['INGRESO']['modulo_']; ?>';
      if(modulo=='5')
      {

      }else
      {
        tp = $('#tipoc').val();
        if(tp=='CI' || tp=='CE')
        {
          guardar_diferencia();
        }
      }
    }
   
  function numero_comprobante(callback) {
  var tip = $('#tipoc').val();
  var fecha = $('#fecha1').val();

  if (tip == 'CD') {
    tip = 'Diario';
  } else if (tip == 'CI') {
    tip = 'Ingresos';
  } else if (tip == 'CE') {
    tip = 'Egresos';
  } else if (tip == 'ND') {
    tip = 'NotaDebito';
  } else if (tip == 'NC') {
    tip = 'NotaCredito';
  }

  modificado = '<?php echo $ModificarComp; ?>';
  if(modificado)
  {
     if (callback && typeof callback === 'function') {
        callback();
      }
      return false;
  }


  var parametros = {
    'tip': tip,
    'fecha': fecha,
  };

  $.ajax({
    data: { parametros: parametros },
    url: '../controlador/contabilidad/incomC.php?num_comprobante=true',
    type: 'post',
    dataType: 'json',
    success: function (response) {
      $("#num_com").html("");
      $("#num_com").html('Comprobante de ' + tip + ' No. <?php echo date('Y');?>-' + response);

      // Ejecuta la función de retorno de llamada si se proporciona
      if (callback && typeof callback === 'function') {
        callback();
      }
    },
    error: function (error) {
      console.error('Error en numero_comprobante:', error);
      // Puedes manejar el error aquí si es necesario
    },
  });
}

    function validar_comprobante() 
    {
      numero_comprobante(function () {
        var debe =$('#txt_debe').val();
        var haber = $('#txt_haber').val(); 
        var ben = $('#beneficiario1').val();
        var fecha = $('#fecha1').val();
        var tip = $('#tipoc').val();
        var ruc = $('#ruc').val();
        var concepto = $('#concepto').val();
        var haber = $('#txt_haber').val();
        var com = $('#num_com').text();
        var modificar = '<?php echo $NuevoComp; ?>';
        // var comprobante = com.split('.');
        if((debe != haber) || (debe==0 && haber==0) )
        {
          Swal.fire( 'Las transacciones no cuadran correctamente corrija los resultados de las cuentas','','info');
          return false;
        }
        if(ben =='')
        {      
          ben = '.';
        }

        var parametros = 
        {
          'ruc': ruc, //codigo del cliente que sale co el ruc del beneficiario codigo
          'tip':tip,//tipo de cuenta contable cd, etc
          "fecha": fecha,// fecha actual 2020-09-21
          'concepto':concepto, //detalle de la transaccion realida
          'totalh': haber, //total del haber
          'num_com':com,
          'CodigoB':$('#ruc').val(),
          'Serie_R':$('#Serie_R').val(),
          'Retencion':$('#Retencion').val(),
          'Autorizacion_R':$('#Autorizacion_R').val(),
          'Autorizacion_LC':$('#Autorizacion_LC').val(),
          'TD':'C',
          'bene':$('select[name="beneficiario1"] option:selected').text(),
          'email':$('#email').val(),
          'Cta_modificar':$('#txt_cta_modificar').val(),
          'T':'N',
          'monto_total':$('#VT').val(),
          'Abono':$('#vae').val(),
          'TextCotiza':$("#cotizacion").val(),
          'NuevoComp':modificar,
        }

        // Continuar con el resto de la lógica después de numero_comprobante
        Swal.fire({
          title: "Esta seguro de Grabar el " + $('#num_com').text(),
          text: "con fecha: " + $('#fecha1').val(),
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si!'
        }).then((result) => {
          if (result.value == true) {
            grabar_comprobante(parametros);
          } else {
            // alert('cancelado');
          }
        });
  });
}

  // function Tipo_De_Comprobante_No()
  // {
  //   var parametros = $('#NuevoComp').val();
  //    $.ajax({
  //         data:  {parametros:parametros},
  //         url:   '../controlador/contabilidad/incomC.php?Tipo_De_Comprobante_No=true',
  //         type:  'post',
  //         dataType: 'json',
  //           success:  function (response) { 

  //           $("#num_com").html("");
  //           $("#num_com").html(response);
  //         }
  //       });
  // }

  
  function validar_fecha()
  {
    if($('#beneficiario1').val()=='')
    {
      $('#beneficiario1').select2('open');
    }

    var modificar = '<?php echo @$variables_mod; ?>';
    if(modificar=='')
    {
      numero_comprobante();
    }
  }
</script>
  <div class="box-body">
    <div>
      <div class="row p-1">
        <div class="mb-1 col-sm-4 col-md-4 col-lg-4 text-start container">
            <button type="button" class="btn btn-default btn-sm border border-2 small-text" onclick="reset_1('comproba','CD');" 
            id='CD' title='Comprobante diario'>Diario</button>
            <button type="button" class="btn btn-default btn-sm border border-2 small-text" onclick="reset_1('comproba','CI');" 
            id='CI' title='Comprobante de ingreso'>Ingreso</button>
            <button type="button" class="btn btn-default btn-sm border border-2 small-text" onclick="reset_1('comproba','CE');" 
            id='CE' title='Comprobante de egreso'>Egreso</button>
            <button type="button" class="btn btn-default btn-sm border border-2 small-text" onclick="reset_1('comproba','ND');" 
            id='ND' title='Comprobante nota de debito'>N/D</button>
            <button type="button" class="btn btn-default btn-sm border border-2 small-text" onclick="reset_1('comproba','NC');" 
            id='NC' title='Comprobante nota de credito'>N/C</button>
            <input id="tipoc" name="tipoc" type="hidden" value="CD">
            <input type="hidden" name="NuevoComp" id="NuevoComp">
            <input type="hidden" name="num_load" id="num_load" value="0">
        </div>                      
        <div class="mt-1 col col-sm-4 col-md-4 col-lg-4">
          <div align='top' style="float: top;">
            <h4 align='center' id='num_com'
            class="h6">Comprobante de Diario No. 0000-00000000
            </h4>
          </div>
        </div>
      
        <div class="mt-1 col col-sm-4 col-md-4 col-lg-4 text-end">
          <label>
            <input type="checkbox"> Imprimir copia
          </label>
        </div>
      </div>
    </div>
    <div class="box table-responsive border p-2">
    
            <div class="box-header">      
        
                <form action="#" class="credit-card-div" id='formu1'>
                  <div class="panel panel-default" >
                    
                    <div class="panel-heading">
                      <div class="row " style="padding-bottom: 5px;">
                        
                        <div class="col col-sm-3 col-md-3 col-lg-3">                          
                          <!-- <div class="form-group"> -->
                               <div class="input-group">
                                 <div class="input-group-addon p-2 text-box">
                                   <b>FECHA:</b>
                                 </div>
                                 <input type="date" class="form-control input-xs" name="fecha1" id="fecha1" placeholder="01/01/2019" value='<?php echo date('Y-m-d') ?>' maxlength='10' size='15' onblur="validar_fecha();fecha_valida(this)">
                               </div>
                          <!-- </div> -->
                        </div>
                        <div class="col col-sm-6 col-md-6 col-lg-6">
                          <!-- <div class="form-group"> -->
                               <div class="input-group">
                                 <div class="input-group-addon p-2 text-box">
                                   <b>BENEFICIARIO:</b>
                                 </div>                        
                              <select id="beneficiario1" name='beneficiario1' class='form-control' onchange="benefeciario_edit()">
                                <option value="">Seleccione beneficiario</option>                                
                              </select>
                              <input type="hidden" name="beneficiario2" id="beneficiario2" value='' />
                               </div>
                          <!-- </div> -->
                        </div>
                        
                        <div class="col-md-3 col-sm-3 col-lg-3">
                          <!-- <div class="form-group"> -->
                               <div class="input-group">
                                 <div class="input-group-addon p-2 text-box">
                                   <b>R.U.C / C.I:</b>
                                 </div>
                                 <input type="text" class=" form-control input-xs" id="ruc" name='ruc' placeholder="R.U.C / C.I" value='000000000' maxlength='30' size='25' onblur="" onkeyup="solo_numeros(this)">
                               </div>
                          <!-- </div> -->
                        </div>
                        
                      </div>
                      <div class="row ">
                         <div class="col col-sm-3 col-md-3 col-lg-3">
                               <div class="input-group">
                                 <div class="input-group-addon p-2 text-box">
                                   <b>Email:</b>
                                 </div>
                                 <input type="email" class="form-control input-xs" id="email" name="email" placeholder="prueba@prueba.com" 
                            maxlength='255' size='100'/>
                               </div>
                        </div>        


                      <!--   <div class="col-md-6 col-sm-6 col-xs-6">
                          <div class="input-group">
                            <div class="btn_f input-xs col-sm-12 text-center">
                              <b>EMAIL:</b>
                            </div>
                                <input type="email" class="form-control input-xs" id="email" name="email" placeholder="prueba@prueba.com" 
                            maxlength='255' size='100'/>
                          </div>
                        </div> -->
                        <div class="col-md-3 col-sm-3 col-lg-3">
                               <div class="input-group">
                                 <div class="input-group-addon p-2 text-box">
                                   <b>COTIZACION:</b>
                                 </div>
                                 <input type="text" class="form-control input-xs" id="cotizacion" name='cotizacion' placeholder="0.00" onkeyup="validar_numeros_decimal(this)" onblur="validar_float(this,2)" style="text-align:right; width: 70px;" maxlength='20' />
                               </div>
                        </div>     

                      <!--   <div class="col-md-2 col-sm-2 col-xs-2">
                          <div class="input-group">
                            <div class="btn_f input-xs col-sm-12 text-center">
                              <b>COTIZACIÓN:</b>
                            </div>
                                <input type="text" class="form-control input-xs" id="cotizacion" name='cotizacion' placeholder="0.00" onKeyPress='return soloNumerosDecimales(event)' style="text-align:right;" maxlength='20' size='25' />
                          </div>
                        </div> -->

                        <div class="col-md-3 col-sm-3 col-xs-3">
                                <div class="input-group">
                                  <div class="input-group-addon p-2 text-box">
                                    <b>Tipo de conversión  :</b>
                                  </div>
                                  <div class="pt-2 ps-2">
                                    <label class="customradio" style="margin-bottom: 1px;"><span class="radiotextsty">(/)</span>
                                    <input type="radio" checked="checked" name="con" id='con' value='/'>
                                    <span class="checkmark"></span>
                                    </label>        
                                    <label class="customradio" style="margin-bottom: 1px;"><span class="radiotextsty">(X)</span>
                                      <input type="radio" name="con" id='con' value='X'>
                                      <span class="checkmark"></span>
                                    </label>
                                  </div> 
                                </div>
                          </div>   

                    <!--     <div class="" style="float: left;position:relative;left:1%;width: 10%;margin-bottom: 1px;">
                            <label class="labeltext" style="margin-bottom: 1px;">Tipo de conversión</label><br>
                            <div class="">
                              <label class="customradio" style="margin-bottom: 1px;"><span class="radiotextsty">(/)</span>
                                <input type="radio" checked="checked" name="con" id='con' value='/'>
                                <span class="checkmark"></span>
                              </label>        
                              <label class="customradio" style="margin-bottom: 1px;"><span class="radiotextsty">(X)</span>
                                <input type="radio" name="con" id='con' value='X'>
                                <span class="checkmark"></span>
                              </label>
                            </div>
                        </div> -->

                         <div class="col-md-3 col-sm-3 col-lg-3">
                               <div class="input-group">
                                 <div class="input-group-addon p-2 text-box">
                                   <b>VALOR TOTAL:</b>
                                 </div>
                                <input type="text" class="form-control input-xs" id="VT" name='VT' placeholder="0.00" style="text-align:right;" onKeyPress='return soloNumerosDecimales(event)' maxlength='20' size='33' readonly="">
                               </div>
                         </div>     


                        <!-- <div class="col-md-2 col-sm-2 col-xs-2">
                          <div class="input-group">
                            <div class="btn_f input-xs col-sm-12 text-center">
                              <b>VALOR TOTAL:</b>
                            </div>
                                <input type="text" class="form-control input-xs" id="VT" name='VT' placeholder="0.00" style="text-align:right;" onKeyPress='return soloNumerosDecimales(event)' maxlength='20' size='33' readonly="">
                          </div>
                        </div> -->
                      </div>
                      <div id='ineg' class="row" style="display: none;"> <br>
                        <div class="row">
                          <div class="col-sm-1" style="padding-right: 0px;">
                            <label class="label-inline" id="rbl_efec"><input type="checkbox" id='efec' name='efec'onclick="mostrar_efectivo()" /> Efectivo</label>
                          </div>
                          <div class="col-sm-10" id="ineg1" style="display: none;">
                            <div class="row">
                              <div class="col-sm-9">
                                <div class="input-group">
                                  <div class="input-group-addon input-xs">
                                    <b>CUENTA:</b>
                                  </div>
                                  <select class="form-control input-xs" name="conceptoe" id='conceptoe'>
                                   <option value="">Seleccione cuenta de efectivo</option>
                                  </select>
                                </div>                            
                              </div>
                              <div class="col-sm-3">
                                <div class="input-group">
                                     <div class="input-group-addon input-xs">
                                       <b><?php echo $_SESSION['INGRESO']['S_M']; ?>:</b>
                                     </div>
                                     <input type="text" class="form-control input-xs" id="vae" name='vae' placeholder="0.00" style="text-align:right;" onkeyup="validar_numeros_decimal(this)" onblur="validar_float(this,2)" maxlength='20' size='13'>
                                   </div>                           
                              </div>                               
                            </div>                                                  
                          </div>                        
                        </div>
                        <div class="row">
                          <div class="col-sm-1" style="padding-right: 0px;">
                            <label class="label-inline" id="rbl_banco" style="background:rgb(40, 96, 144) ;color: #FFFFFF;padding:5px;border-radius: 5px;"><input type="checkbox" id='ban' name='ban'onclick="mostrar_banco()" checked="" /> Banco</label>
                          </div>
                          <div class="col-sm-10" id='ineg2'>
                            <div class="row">
                              <div class="col-md-9">
                              <div class="input-group">
                                     <div class="input-group-addon input-xs">
                                         <b>CUENTA:</b>
                                     </div>
                                     <select class="form-control input-xs" name="conceptob" id='conceptob'onchange="DCBanco_LostFocus()">
                                         <option value="">Seleccione cuenta de banco</option>
                                    </select>
                                  </div>                            
                            </div>
                            <div class="col-md-3"  id="ingreso_val_banco">
                              <div class="input-group">
                                     <div class="input-group-addon input-xs">
                                         <b><?php echo $_SESSION['INGRESO']['S_M']; ?>:</b>
                                     </div>
                                     <input type="text" class="form-control input-xs" id="vab" name='vab' placeholder="0.00" 
                                style="text-align:right;"  onkeyup="validar_numeros_decimal(this)" onblur="validar_float(this,2)"
                                maxlength='20' size='13' value='0.00'>
                                  </div>  
                            </div> 
                            <div class="col-md-3" id="no_cheque" style="display: none;">
                              <div class="input-group">
                                     <div class="input-group-addon input-xs">
                                         <b>No. Cheq:</b>
                                     </div>
                                     <input type="text" class="form-control input-xs" id="no_cheq" name='no_cheq' placeholder="00000001" 
                                style="text-align:right;"  onKeyPress='return soloNumerosDecimales(event)' 
                                maxlength='20' size='13' value='00000001' onblur="agregar_depo()">
                                  </div>  
                            </div>
                            </div>
                            


                            
                          </div>                          
                        </div>
                        <div class="row" id='ineg3' >
                          <div class="col-md-8">
                            <div id="div_tabla">
                             
                            </div>
                            <input type="hidden" id='reg1' name='reg1'  value='' />
                          </div>
                          <div class="col-md-2">
                            <div class="input-group">
                                <div class="btn_f input-xs col-sm-12 text-center">
                                  <b>Efectivizar:</b>
                                </div>
                                <input type="date" class="form-control input-xs" id="efecti" name='efecti' placeholder="01/01/2019" value='<?php echo date('Y-m-d') ?>' onblur="fecha_valida(this)">
                              </div>                            
                          </div>
                          <div class="col-md-2">
                            <div class="input-group" id="deposito_no">
                                <div class="btn_f input-xs col-sm-12 text-center">
                                  <b>Deposito No:</b>
                                </div>
                                <input type="text" class="form-control input-xs" id="depos" onkeyup="solo_numeros(this)" name='depos' placeholder="12345" onblur="agregar_depo()">
                              </div>
                          </div>                          
                        </div>                      
                      </div>

                      <div class="row " style="padding-bottom: 5px;"><br> 
                        <div class="col-md-12 col-sm-12 col-lg-12 pt-2 pb-2 ps-3 pe-3">
                               <div class="input-group">
                                 <div class="input-group-addon p-2 text-box">
                                   <b>CONCEPTO:</b>
                                 </div>
                                <input type="text" class="form-control input-xs" id="concepto" name="concepto" placeholder="concepto" maxlength='150'/>
                               </div>
                        </div>                        
                      </div>

                      <div class="row">
                        <div class="col-md-2 col-sm-1 col-xs-1">
                          <div class="input-group">
                            <div class="text-box col-md-12 btn_f text-center">
                              <b>CODIGO:</b>
                            </div>
                             <input type="text" class="form-control input-xs" title="Teclas especiales
CE: ESC" id="codigo" name='codigo' placeholder="codigo" maxlength='30' size='12' onblur="cargar_modal();" onkeyup="mayusculas('codigo',this.value)" />
                          </div>
                        </div>
                        <div class="col-md-8 col-sm-8 col-lg-8">
                               <div class="input-group" style="display: block;">
                                 <div class="btn_f text-box col-md-12 text-center">
                                  <b>DIGITE LA CLAVE O SELECCIONE LA CUENTA:</b>
                                 </div>
                                 <select id="cuentar" class=" form-control" style="width:100%" onchange="abrir_modal_cuenta()">
                                  <option value="">Seleccione una cuenta</option>   
                                 </select>
                                   <!--  <input type="text" class="xs" id="cuenta" name='cuenta' placeholder="cuenta" maxlength='70' size='153'/>
                                    <input type="hidden" id='codigo_cu' name='codigo_cu' value='' />-->
                                    <input type="hidden" id='aux' name='TC'  value='' />
                               </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2">
                               <div class="input-group">
                                 <div class="btn_f text-box col-md-12 text-center">
                                  <b>VALOR:</b>
                                 </div>
                                   <input type="text" class="form-control input-xs" id="va" name='va' 
                              placeholder="0.00" style="text-align:right;"  onkeyup="validar_numeros_decimal(this)" onblur="ingresar_asiento();validar_float(this,2)" value="0.00">
                               </div>
                        </div>
                      </div>
                      <div class="row">
                        <input type="hidden" name="txt_cuenta" id="txt_cuenta">
                        <input type="hidden" name="txt_codigo" id="txt_codigo">
                        <input type="hidden" name="txt_tipocta" id="txt_tipocta">
                        <input type="hidden" name="txt_subcta" id="txt_subcta">
                        <input type="hidden" name="txt_tipopago" id="txt_tipopago">
                        <input type="hidden" name="txt_moneda_cta" id="txt_moneda_cta">   
                        <input type="hidden" name="Serie_R" id="Serie_R" value=".">  
                        <input type="hidden" name="Retencion" id="Retencion" value="."> 
                        <input type="hidden" name="Autorizacion_R" id="Autorizacion_R" value=".">  
                        <input type="hidden" name="Autorizacion_LC" id="Autorizacion_LC" value="."> 
                        <input type="hidden" name="txt_cta_modificar" id="txt_cta_modificar" value="."> 
                      </div>
                      <div class="row">
                          <div class="col-12 p-3">
                            <div class="panel-heading">
                              <ul class="nav nav-tabs">
                                <li class="active p-2 border "><a href="#contabilidad" data-toggle="tab">4. Contabilización</a></li>
                                <li class="p-2 border "><a href="#subcuentas" data-toggle="tab" onclick="cargar_tablas_sc();">5. Subcuentas</a></li>
                                <li class="p-2 border "><a href="#retenciones" data-toggle="tab" onclick="cargar_tablas_retenciones();">6. Retenciones</a></li>
                                <li class="p-2 border "><a href="#ac_av_ai_ae" data-toggle="tab" onclick="cargar_tablas_tab4();">7. AC-AV-AI-AE</a></li>
                              </ul>
                            </div>
                            <div class="panel-body" style="padding-top: 2px;">
                              <div class="tab-content">
                                <div class="tab-pane fade in active" id="contabilidad">
                                  <div class="text-center">
                                    <img src="../../img/gif/loader4.1.gif" width="10%">                                        
                                  </div>                          
                                </div>
                                <div class="tab-pane fade" id="subcuentas">
                                  <div class="text-center">
                                    <img src="../../img/gif/loader4.1.gif" width="10%">                                        
                                  </div>  
                                </div>
                                <div class="tab-pane fade" id="retenciones">
                                  <div class="text-center">
                                    <img src="../../img/gif/loader4.1.gif" width="10%">                                        
                                  </div>  
                                </div>
                                <div class="tab-pane fade" id="ac_av_ai_ae">
                                  <div class="text-center">
                                    <img src="../../img/gif/loader4.1.gif" width="10%">                                        
                                  </div>  </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-6 pt-4">
                             <button type="button"  class="btn btn-primary" id='grabar1' onclick="validar_comprobante()">Guardar</button>
                             <a  href="./inicio.php?mod=<?php echo @$_GET['mod']; ?>" title="Salir de modulo" class="btn btn-danger">
                                Cancelar
                              </a>
                             <!-- 
                              <button type="button"  class="btn btn-danger" id='' onclick="xml()">xml</button>
                             -->                            
                          </div>
                          <div class="row col-sm-6">
                            <div class="col-2">
                              <b>Diferencia</b>
                                <input type="text" name="txt_diferencia" id="txt_diferencia" class="form-control input-xs text-right" readonly="" value="0">
                            </div>
                            <div class="col-2">
                              <b>Totales</b>
                               <input type="text" name="txt_debe" id="txt_debe" class="form-control input-xs text-right" readonly="" value="0">
                            </div>
                            <div class="col-2"><br>
                                <input type="text" name="txt_haber" id="txt_haber" class="form-control input-xs text-right" readonly="" value="0">
                            </div>
                          </div>
                        </div>                        
                    </div>
                                          
                      
                    </div>
                  </form>   
                    
                </div>
            </div>
  </div>
</div>

<div class="modal fade" id="modal_cuenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document" style="margin-right: 50px; margin-top: 200px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"></h5>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
      </div>
      <div class="modal-body">
        <div id="panel_banco" style=" display: none">
        <div class="row">
          <div class="col-sm-6">
            <b>Efectiv.</b>
          </div>
          <div class="col-sm-6">
            <input type="date" name="txt_efectiv" id="txt_efectiv" class="form-control input-xs" value="<?php echo date('Y-m-d');?>">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <b> Cheq / Dep</b>
          </div>
          <div class="col-sm-6">
            <input type="text" name="txt_cheq_dep" id="txt_cheq_dep" class="form-control input-xs">
          </div>
        </div>
        </div>
        <div class="row">
          <div class="col-sm-6"><br>
            <b>Valores</b>
          </div>
          <div class="col-sm-6">
            <b>M/N = 1 | M/E=2</b>
            <input type="text" name="txt_moneda" id="txt_moneda" class="form-control input-xs" onkeyup="restingir('txt_moneda')" value="1">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6"><br>
            <b>Debe / Haber</b>
          </div>
          <div class="col-sm-6">
            <b>Debe = 1 | Haber=2</b>
            <input type="text" name="txt_tipo" id="txt_tipo" class="form-control input-xs" onkeyup="restingir('txt_tipo')" value="1" onblur="saltar()">
              <button type="button" class="btn btn-primary" onclick="subcuenta_frame();" id="btn_acep" style="background: white;border: 0px;">Aceptar</button>
          </div>
        </div>
      </div>
      <!-- <div class="modal-footer"> -->
        
          <!-- <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cerrar</button> -->
        <!-- </div> -->
    </div>
  </div>
</div>

<div class="modal fade" id="modal_subcuentas" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="titulo_frame">SUB CUENTAS</h5>
      </div>
      <div class="modal-body" style="padding-top: 0px;">
        <!-- <div class="container-fluid"> -->
          <iframe  id="frame" width="100%" marginheight="0" frameborder="0"></iframe>
          
        <!-- </div> -->
        <!-- <iframe src="../vista/contabilidad/FSubCtas.php"></iframe> -->
        
      </div>
      <div class="modal-footer">
          <!-- <button type="button" class="btn btn-primary" onclick="cambia_foco();">Guardar</button> -->
          <button style="display: none;" onclick="salir_todo()" id="btn_salir" id="btn_cerrar_sub" type="button" class="btn btn-default">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- TODO: Modal CC-->

<div class="modal fade" id="modal_CC" data-backdrop="static" tabindex="-1" role="dialog" style="display: none;">
  <div class="modal-dialog modal-lg" role="document" style="max-width: 475px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="titulo_frame_cc"></h5>
      </div>
      <div class="modal-body">
        <h5 class="modal-title" id="titulo_aux" style="padding-top: 10px; padding-bottom: 10px;"></h5>
      <div class="row">
        <div class="col-sm-12" style="overflow-x: scroll;height: 300px; padding: 10px; ">
            <div id="tablaContenedor">

            </div>   
        </div>
      </div>                     
        <div class="row">
          <div class="col-sm-6" style="padding: 10px;">
            <button type="button" class="btn btn-primary" onclick="Commandl_Click()">Aceptar</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="Command2_Click()">Cancelar</button>
          </div>
          <div class="col-sm-6">
            <div class="col-sm-6" style="padding: 10px;">
              <b>TOTAL</b>
            </div>
            <div class="col-sm-6" style="padding: 10px;">
              <input type="text" name="total_cc" id="total_cc" class="form-control input-xs text-right" readonly="" value="0.00" wfd-id="id35">
            </div>
          </div>

        </div>
      </div>
      <div class="modal-footer">
          <!-- <button type="button" class="btn btn-primary" onclick="cambia_foco();">Guardar</button> -->
          <!-- <button style="display: none;" id="btn_salir_cc" id="btn_cerrar_sub_cc" type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>-->
      </div>
    </div>
  </div>
</div>
