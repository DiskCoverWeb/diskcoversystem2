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
<div class="overflow-auto">
  <div class="">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">
        <?php echo $NombreModulo; ?>
      </div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">Diario General</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <div class="row row-cols-auto gx-3 pb-2 d-flex align-items-center ps-2">
    <div class="row row-cols-auto btn-group">
            <a  href="./contabilidad.php?mod=contabilidad#"   data-bs-toggle="tooltip" title="Salir de modulo" class="btn btn-outline-secondary">
              <img src="../../img/png/salire.png">
            </a>
          <button title="Consultar Catalogo de cuentas"  class="btn btn-outline-secondary" data-bs-toggle="tooltip" onclick="cargar_libro_general();">
            <img src="../../img/png/consultar.png" >
          </button>
          <button type="button" class="btn btn-outline-secondary"  data-bs-toggle="dropdown" title="Descargar PDF">
            <img src="../../img/png/pdf.png">
          </button>
            <ul class="dropdown-menu">
              <li><a href="#" class="dropdown-item" id="imprimir_pdf">Diario General</a></li>
              <li><a href="#" class="dropdown-item" id="imprimir_pdf_2">Libro Diario</a></li>
            </ul>
          <button type="button" class="btn btn-outline-secondary"   data-bs-toggle="dropdown" title="Descargar Excel">
            <img src="../../img/png/table_excel.png">
          </button>
          <ul class="dropdown-menu">
            <li><a href="#" class="dropdown-item" id="imprimir_excel">Diario General</a></li>
            <li><a href="#" class="dropdown-item" id="imprimir_excel_2">Libro Diario</a></li>
          </ul>
          <button data-bs-toggle="tooltip"class="btn btn-outline-secondary" title="Autorizar" onclick="Swal.fire('No tiene accesos a esta opcion','','info')">
            <img src="../../img/png/autorizar1.png">
          </button>
        <?php if ($_SESSION['INGRESO']['Nombre_Completo']=="Administrador de Red"): ?>
            <button data-bs-toggle="tooltip"class="btn btn-outline-secondary" title="Eliminar Comprobantes Incompletos" onclick="Eliminar_ComprobantesIncompletos()">
              <img src="../../img/png/borrar_archivo.png" style="max-width: 32px;">
            </button>
        <?php endif ?>
        </div>
    <div class="row row-cols-auto col-7">
      <div class="col-6">
        <b>Desde:</b><br>
          <input class="form-control form-control-sm h-25" type="date" min="01-01-2000" max="31-12-2050"  name="txt_desde" id="txt_desde" value="<?php echo date("Y-m-d");?>" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);cargar_libro_general();">  
      </div>  
      <div class="col-6">
        <b>Hasta:</b><br>
          <input class="form-control form-control-sm h-25" type="date"  min="01-01-2000" max="31-12-2050" name="txt_hasta" id="txt_hasta" value="<?php echo date("Y-m-d");?>" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id);cargar_libro_general();"> 
      </div>   
    </div>
  </div>
    <div class="">  
      <div class="">
        <div class="">
            <div class="m-0"><b>COMPROBANTES DE<b></div>
          <div class="row col-12 m-0 p-2">
          <div class="col-auto">
            <label class="form-check-label"><input class="form-check-input" type="radio" name="OpcP" id="OpcT" onchange="cargar_libro_general();" checked=""><b class="ps-2">Todos</b></label> 
          </div>
          <div class="col-auto">
              <label class="form-check-label"><input class="form-check-input" type="radio" name="OpcP" id="OpcCI" onchange="cargar_libro_general();"><b class="ps-2">Ingresos</b></label>
          </div>
          <div class="col-auto">
              <label class="form-check-label"><input class="form-check-input" type="radio" name="OpcP" id="OpcCE" onchange="cargar_libro_general();"><b class="ps-2">Egreso</b></label>          
          </div>
          <div class="col-auto">
              <label class="form-check-label"><input class="form-check-input" type="radio" name="OpcP" id="OpcCD" onchange="cargar_libro_general();"><b class="ps-2">Diario</b></label>
          </div>
          <div class="col-auto">
              <label class="form-check-label"><input class="form-check-input" type="radio" name="OpcP" id="OpcND" onchange="cargar_libro_general();"><b class="ps-2">Notas de debito</b></label>
          </div>
          <div class="col-auto">
            <label class="form-check-label"><input class="form-check-input" type="radio" name="OpcP" id="OpcNC" onchange="cargar_libro_general();"><b class="ps-2">Notas de credito</b></label>
          </div>
          <div class="col-auto">
              <label class="form-check-label"><input class="form-check-input" type="radio" name="OpcP" id="OpcA" onchange="cargar_libro_general();"><b class="ps-2">Anulado</b></label>
          </div>
          <div class="col-auto">
            <label class="form-check-label"><input class="form-check-input" type="checkbox" name="CheckNum" id="CheckNum" onchange="mostrar_campos();"><b class="ps-2"> Desde el No: </b></label>                      
          </div>
          <div class="col-1" id="campos" style="display: none">
            <input type="text" class="form-control form-control-sm" name="TextNumNo" id="TextNumNo" value="0">
            <input type="text" class="form-control form-control-sm" name="TextNumNo1" id="TextNumNo1" value="0"> 
          </div>  
          </div>
      </div>                
    </div>
  </div>
  <div class="row p-2">
    <div class="row row-cols-auto m-0">
      <div class="row row-cols-auto w-50">
        <div class="col-4 p-2">
          <label class="radio-inline"><input type="checkbox" name="CheckUsuario" id="CheckUsuario" onchange="cargar_libro_general();"> <b class="h-75">Por Usuario</b></label>          
        </div>
        <div class="col-8 d-flex align-items-center">
            <select class="form-select form-select-sm  h-75 w-75 pb-1" id="DCUsuario" >
              <option value="" class="align-items-center">Seleccione usuario</option>
            </select>           
        </div> 
      </div>
      <div class="row row-cols-auto w-50">
        <div class="col-4 p-2">        
          <label class="radio-inline" id='lblAgencia'><input type="checkbox" name="CheckAgencia" id="CheckAgencia" onchange="cargar_libro_general();"> <b class="h-75">Por Agencia</b></label>
        </div>
        <div class="col-8 d-flex align-items-center">
          <select class="form-select form-select-sm h-75 w-75 pb-1" id="DCAgencia">
            <option value="" class="align-items-center">Seleccione Agencia</option>
          </select>
      </div>
    </div> 
    </div>             
  </div>       
  <!--seccion de panel-->
  <div class="row">
    <input type="input" name="activo" id="activo" value="1" hidden="">
    <div class="col-12">
      <ul class="nav nav-pills pt-1" role="tablist">
          <li class="nav-item" role="presentation">
          <a data-bs-toggle="pill" href="#Primary-DG" id="titulo_tab" class="nav-link active">
            <div class="tab-title">DIARIO GENERAL</div>
          </a>
        </li>
          <li class="nav-item">
          <a data-bs-toggle="pill" href="#Primary-SM" id="titulo2_tab" class="nav-link">
            <div class="tab-title">SUB MODULOS</div>
          </a>
        </li>
      </ul>       
       <div class="tab-content pt-2">
              <div class="tab-pane fade active show p-1" id="Primary-DG">
                <table class="table text-sm w-100" id="tbl_DiarioGeneral">
                  <thead>
                    <tr>
                      <th class="text-center">Fecha</th>
                      <th class="text-center">TP</th>
                      <th class="text-center">Número</th>
                      <th class="text-center">Beneficiario</th>
                      <th class="text-center">Concepto</th>
                      <th class="text-center">Cta</th>
                      <th class="text-center">Cuenta</th>
                      <th class="text-center">Parcial_ME</th>
                      <th class="text-center">Debe</th>
                      <th class="text-center">Haber</th>
                      <th class="text-center">Detalle</th>
                      <th class="text-center">Nombre_Completo</th>
                      <th class="text-center">CodigoU</th>
                      <th class="text-center">Autorizado</th>
                      <th class="text-center">Item</th>
                      <th class="text-center">ID</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="tab-pane fade" id="Primary-SM">
                <table class="table text-sm w-100" id="tbl_Submodulos">
                  <thead>
                    <tr>
                      <th class="text-center">Fecha</th>
                      <th class="text-center">TP</th>
                      <th class="text-center">Numero</th>
                      <th class="text-center">Cliente</th>
                      <th class="text-center">Cta</th>
                      <th class="text-center">TC</th>
                      <th class="text-center">Factura</th>
                      <th class="text-center">Debitos</th>
                      <th class="text-center">Creditos</th>
                      <th class="text-center">Prima</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tbody>
                </table>   
              </div>
            </div>
      </div>
    </div>
    <div class="row row-cols-auto p-0 m-0">
      <div class="col-2">
        <b class="h-75 ">Total Debe</b>
      </div>
      <div class="col-2">
        <input type="text" id="debe" class="text-right rounded border form-control h-75" size="8" readonly/>
      </div>
      <div class="col-2">
        <b class="h-75 ">Total Haber</b>
      </div>
      <div class="col-2">
        <input type="text" id="haber" class="text-right rounded border form-control h-75" size="8" readonly/>
      </div>
      <div class="col-2">
        <b class="h-75">Debe - Haber</b>
      </div>
      <div class="col-2 text-right rounded border border-3 h-75">  
        <label id="Saldo"></label>       
      </div>
    </div>
    <div class="row p-0 m-0">
      <div class="col-2">
        <b class="h-75">Total Debe ME</b>
      </div>
      <div class="col-2">
        <input type="text" id="debe_me" class="text-right rounded border form-control h-75" size="8" readonly/>
      </div>
      <div class="col-2">
        <b class="h-75">Total Haber ME</b>
      </div>
      <div class="col-2">
        <input type="text" id="haber_me" class="text-right rounded border form-control h-75" size="8" readonly/>
      </div>
      <div class="col-2">
        <b class="h-75">Debe - Haber ME</b>
      </div>
      <div class="col-2 text-right rounded border border-3 bg-light h-75">
        <label id="SaldoME"></label>       
      </div>
    </div>
</div>

