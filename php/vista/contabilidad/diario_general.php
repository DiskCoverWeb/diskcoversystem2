<?php  
//require_once("panel.php");
?>
<script src="../../dist/js/diario_general.js"></script>
<style>
  .font-box{
    font-size: 0.85rem;
  }
</style>
<script type="text/javascript">
    <?php if ($_SESSION['INGRESO']['Nombre_Completo']=="Administrador de Red"): ?>
    function Eliminar_ComprobantesIncompletos() {
        Swal.fire({
    title: 'Esta seguro?',
    text: "Eliminar Comprobantes Incompletos",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, Eliminar'
    }).then((result) => {
    if (result.value == true) {
        $('#myModal_espera').modal('show');
        $.ajax({
        type: "POST",
        url: '../controlador/contabilidad/diario_generalC.php?EliminarComprobantesIncompletos=true',
        dataType: 'json',
        success: function (response) {
            Swal.fire({
                type: response ?'success':'error',
                title: response ?'Fin del Proceso':'Ocurrio un error inesperando realizando el proceso',
                text: ''
            });

            $('#myModal_espera').modal('hide');
        },
        error: function (e) {
            $('#myModal_espera').modal('hide');
            alert("error inesperado en EliminarComprobantesIncompletos")
        }
        });
    }
    })
    }
    <?php endif ?>
</script>

   <div>
    <div class="p-2 row col-12">
      <div class="row col-5 pt-2">
        <div class="col-2">
            <a  href="./contabilidad.php?mod=contabilidad#"   data-toggle="tooltip" title="Salir de modulo" class="btn btn-default border border-3 rounded-2">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-2">
          <button title="Consultar Catalogo de cuentas"  class="btn btn-default border border-3 rounded-2" data-toggle="tooltip" onclick="cargar_libro_general();">
            <img src="../../img/png/consultar.png" >
          </button>
        </div>
        <div class="col-2">
          <button type="button" class="btn btn-default border border-3 rounded-2"  data-toggle="dropdown" title="Descargar PDF">
            <img src="../../img/png/pdf.png">
          </button>
            <ul class="dropdown-menu">
              <li><a href="#" id="imprimir_pdf">Diario General</a></li>
              <li><a href="#" id="imprimir_pdf_2">Libro Diario</a></li>
            </ul>
        </div>
        <div class="col-2">
          <button type="button" class="btn btn-default border border-3 rounded-2"   data-toggle="dropdown" title="Descargar Excel">
            <img src="../../img/png/table_excel.png">
          </button>
          <ul class="dropdown-menu">
            <li><a href="#" id="imprimir_excel">Diario General</a></li>
            <li><a href="#" id="imprimir_excel_2">Libro Diario</a></li>
          </ul>
        </div>
        <div class="col-2">
          <button data-toggle="tooltip"class="btn btn-default border border-3 rounded-2" title="Autorizar" onclick="Swal.fire('No tiene accesos a esta opcion','','info')">
            <img src="../../img/png/autorizar1.png">
          </button>
        </div>
        <?php if ($_SESSION['INGRESO']['Nombre_Completo']=="Administrador de Red"): ?>
          <div class="border col-2">
            <button data-toggle="tooltip"class="btn btn-default border border-3 rounded-2" title="Eliminar Comprobantes Incompletos" onclick="Eliminar_ComprobantesIncompletos()">
              <img src="../../img/png/borrar_archivo.png" style="max-width: 32px;">
            </button>
          </div>
        <?php endif ?>
      </div>
      <div class="row col-7">
        <div class="col-6">
          <b>Desde:</b><br>
            <input class="form-control input-sm h-50" type="date" min="01-01-2000" max="31-12-2050"  name="txt_desde" id="txt_desde" value="<?php echo date("Y-m-d");?>" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);cargar_libro_general();">  
        </div>  
        <div class="col-6">
          <b>Hasta:</b><br>
            <input class="form-control input-sm h-50" type="date"  min="01-01-2000" max="31-12-2050" name="txt_hasta" id="txt_hasta" value="<?php echo date("Y-m-d");?>" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);cargar_libro_general();"> 
        </div>   
      </div>
    </div>
     <div class="">  
        <div class="">
          <div class="">
             <div class="m-0"><b>COMPROBANTES DE<b></div>
            <div class="row col-12 border m-0 p-2">
            <div class="col-auto">
              <label class="radio-inline"><input type="radio" name="OpcP" id="OpcT" onchange="cargar_libro_general();" checked=""><b class="ps-2">Todos</b></label> 
            </div>
            <div class="col-auto">
                <label class="radio-inline"><input type="radio" name="OpcP" id="OpcCI" onchange="cargar_libro_general();"><b class="ps-2">Ingresos</b></label>
            </div>
            <div class="col-auto">
                <label class="radio-inline"><input type="radio" name="OpcP" id="OpcCE" onchange="cargar_libro_general();"><b class="ps-2">Egreso</b></label>          
            </div>
            <div class="col-auto">
                <label class="radio-inline"><input type="radio" name="OpcP" id="OpcCD" onchange="cargar_libro_general();"><b class="ps-2">Diario</b></label>
            </div>
            <div class="col-auto">
                <label class="radio-inline"><input type="radio" name="OpcP" id="OpcND" onchange="cargar_libro_general();"><b class="ps-2">Notas de debito</b></label>
            </div>
            <div class="col-auto">
              <label class="radio-inline"><input type="radio" name="OpcP" id="OpcNC" onchange="cargar_libro_general();"><b class="ps-2">Notas de credito</b></label>
            </div>
            <div class="col-auto">
                <label class="radio-inline"><input type="radio" name="OpcP" id="OpcA" onchange="cargar_libro_general();"><b class="ps-2">Anulado</b></label>
            </div>
            <div class="col-auto">
              <label class="radio-inline"><input type="checkbox" name="CheckNum" id="CheckNum" onchange="mostrar_campos();"><b class="ps-2"> Desde el No: </b></label>                      
            </div>
            <div class="" id="campos" style="display: none">
              <input type="text" class="form-control input-sm" name="TextNumNo" id="TextNumNo" value="0">
              <input type="text" class="form-control input-sm" name="TextNumNo1" id="TextNumNo1" value="0"> 
            </div>  
           </div>
        </div>                
      </div>
    </div>
    <div class="row p-2">
      <div class="row col-7 m-0">
        <div class="col-4 p-2">
          <label class="radio-inline input-sm"><input type="checkbox" name="CheckUsuario" id="CheckUsuario" onchange="cargar_libro_general();"> <b class="h-75">Por Usuario</b></label>          
        </div>
        <div class="col-8">
            <select class="form-control form-control-sm h-50 w-75 font-box pb-1" id="DCUsuario" >
              <option value="" class="">Seleccione usuario</option>
            </select>           
        </div>  
<!-- No esta en el Diskcoversystem1
      <div class="col-sm-2">        
        <label class="radio-inline input-sm" id='lblAgencia'><input type="checkbox" name="CheckAgencia" id="CheckAgencia" onchange="cargar_libro_general();"> <b>Por Agencia</b></label>
      </div>
      <div class="col-sm-4">
        <select class="form-control input-sm" id="DCAgencia">
          <option value="">Seleccione Agencia</option>
        </select>
     </div> 
-->
     </div>             
    </div>       
    <!--seccion de panel-->
    <div class="row">
      <input type="input" name="activo" id="activo" value="1" hidden="">
      <div class="col-12">
        <ul class="nav nav-tabs pt-1">
           <li class="active">
            <a data-toggle="tab" href="#home" id="titulo_tab" class="" onclick="activar(this)"><h6 class="">DIARIO GENERAL</h6></a></li>
           <li class="">
            <a data-toggle="tab" href="#menu1" id="titulo2_tab" class="" onclick="activar(this)"><h6 class="ps-3">SUB MODULOS</h6></a></li>
        </ul>       
          <div class="tab-content" >
            <div id="home" class="tab-pane fade in active">
               <div class="table-responsive" id="tabla_">
                                
               </div>
             </div>
             <div id="menu1" class="tab-pane fade">
               <div class="table-responsive" id="tabla_submodulo">
                              
               </div>
             </div>           
          </div>
      </div>
    </div>
    <div class="row p-0 m-0">
      <div class="col-2">
        <b class="h-75 ">Total Debe</b>
      </div>
      <div class="col-2">
        <input type="text" id="debe" class="text-right rounded border border-primary form-control h-75" size="8" readonly/>
      </div>
      <div class="col-2">
        <b class="h-75 ">Total Haber</b>
      </div>
      <div class="col-2">
        <input type="text" id="haber" class="text-right rounded border border-primary form-control h-75" size="8" readonly/>
      </div>
      <div class="col-2">
        <b class="h-75">Debe - Haber</b>
      </div>
      <div class="col-2 text-right rounded border bg-light h-75">  
        <label id="Saldo"></label>       
      </div>
    </div>
    <div class="row p-0 m-0">
      <div class="col-2">
        <b class="h-75">Total Debe ME</b>
      </div>
      <div class="col-2">
        <input type="text" id="debe_me" class="text-right rounded border border-primary form-control h-75" size="8" readonly/>
      </div>
      <div class="col-2">
        <b class="h-75">Total Haber ME</b>
      </div>
      <div class="col-2">
        <input type="text" id="haber_me" class="text-right rounded border border-primary form-control h-75" size="8" readonly/>
      </div>
      <div class="col-2">
        <b class="h-75">Debe - Haber ME</b>
      </div>
      <div class="col-2 text-right rounded border bg-light h-75">
        <label id="SaldoME"></label>       
      </div>
    </div>