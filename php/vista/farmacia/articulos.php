<?php  $_SESSION['INGRESO']['modulo_']='99';?>
<script src="../../dist/js/farmacia/articulos.js"></script>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3"><?php echo $NombreModulo; ?></div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0"  id="ruta_menu">
          <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
          </li>
        </ol>
      </nav>
    </div>          
</div>

<div class="row row-cols-auto  mb-2">
  <div class="btn-group">
    <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip">
      <img src="../../img/png/salire.png">
    </a>
    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Nuevo articulo" onclick=" abrir_modal()">
      <img src="../../img/png/add_articulo.png">
    </button>          
    <a href="./inicio.php?mod=28&acc=pacientes" type="button" class="btn btn-outline-secondary btn-sm" id="imprimir_pdf" title="Pacientes" data-toggle="tooltip">
      <img src="../../img/png/pacientes.png">
    </a>           
    <a href="./inicio.php?mod=28&acc=vis_descargos" type="button" class="btn btn-outline-secondary btn-sm" id="imprimir_excel" title="Descargos" data-bs-toggle="tooltip">
      <img src="../../img/png/descargos.png">
    </a>         
    <a href="./inicio.php?mod=28&acc=articulos" title="Ingresar Articulos"  class="btn btn-outline-secondary btn-sm" data-toggle="tooltip">
      <img src="../../img/png/articulos.png" >
    </a>     
    </div>
</div>

<div class="card">
  <div class="card-body">
    <div class="row mb-1">
      <div class="col-sm-6 text-end"><b>INGRESAR ARTICULOS</b></div>         
      <div class="col-sm-6 text-end"> No. COMPROBANTE  <u id="num"></u></div>        
    </div>
    <hr>
    <form id="form_add_producto">
      <div class="row">
        <div class="col-sm-4">
            <b>Proveedor:</b>
            <div class="d-flex align-items-center"> 
                <select class="form-select form-select-sm" id="ddl_proveedor" name="ddl_proveedor" onchange="cargar_datos_prov()">
                   <option value="">Seleccione un proveedor</option>
                </select>             
                 <button type="button" class="btn btn-outline-secondary btn-sm p-1" title="Buscar" data-bs-toggle="modal" data-bs-target="#myModal_provedor"><i class="bx bx-plus p-0 me-0"></i></button>
            </div>
        </div>            
        <div class="col-sm-3">
            <b>Nombre comercial</b><br>
            <label id="lbl_nom_comercial"></label>
        </div> 
        <div class="col-sm-1">
          <b>Serie</b>
          <input type="text" name="txt_serie" id="txt_serie" class="form-control form-control-sm" onkeyup="num_caracteres('txt_serie',6)">            
        </div>
            <div class="col-sm-2">
              <b>Numero de factura</b>
              <input type="text" name="txt_num_fac" id="txt_num_fac" class="form-control form-control-sm">            
            </div>           
             <div class="col-sm-2">
              <b>Fecha:</b>
              <input type="date" name="txt_fecha" id="txt_fecha" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>" onblur="num_comprobante(); DCPorcenIva('txt_fecha', 'PorcIVA');">
           </div>
          </div>
        <div class="row">
           <div class="col-md-2">
              <b>Referencia:</b>
              <input type="text" name="txt_referencia" id="txt_referencia" class="form-control form-control-sm" readonly="">
           </div>
           <div class="col-sm-5">
              <b>Producto:</b>
              <select class="form-control form-control-sm" id="ddl_producto" name="ddl_producto" onchange="cargar_detalles()">
                <option value="">Seleccione una producto</option>
              </select>
           </div>
          
           <div class=" col-sm-3">
              <b>Familia:</b>
                <select class="form-control form-control-sm" id="ddl_familia" name="ddl_familia" disabled="">
                  <option>Seleccione una familia</option>
                </select>     
           </div>
           <div class="col-sm-1">
            <b>Unidad</b>
            <input type="" name="txt_unidad" id="txt_unidad" class="form-control form-control-sm">             
           </div>
           <div class="col-sm-1" style="padding: 0px;">
              <b>Lleva iva</b><br>
              <label class="online-radio"><input type="radio" name="rbl_radio" id="rbl_no" checked="" onchange="calculos()"> No</label>
              <label class="online-radio"><input type="radio" name="rbl_radio" id="rbl_si" onchange="calculos()"> Si</label>            
            </div>   
        </div>
        <div class="row">
          <div class="col-sm-1">
            <b>I.V.A</b>
            <select class="form-control form-control-sm" name="PorcIVA" id="PorcIVA" onchange="calculos()"></select>
          </div>
            <div class="col-sm-1">
               <b>Existente</b>
                  <input type="text" name="txt_existencias" id="txt_existencias" class="form-control form-control-sm" readonly="">
            </div>
            <div class="col-sm-2">
               <b>Fecha Elab</b>
                  <input type="date" name="txt_fecha_ela" id="txt_fecha_ela" class="form-control form-control-sm" >
            </div>
            <div class="col-sm-2">
               <b>Fecha Exp</b>
                  <input type="date" name="txt_fecha_exp" id="txt_fecha_exp" class="form-control form-control-sm" >
            </div>
            <div class="col-sm-2">
               <b>Reg. Sanitario</b>
                  <input type="text" name="txt_reg_sani" id="txt_reg_sani" class="form-control form-control-sm" readonly="" value=".">
            </div>
            <div class="col-sm-2">
               <b>Procedencia</b>
                  <input type="text" name="txt_procedencia" id="txt_procedencia" class="form-control form-control-sm">
            </div>
            <div class="col-sm-2">
               <b>Lote</b>
                  <input type="text" name="txt_lote" id="txt_lote" class="form-control form-control-sm">
            </div>              
        </div>
        <div class="row">
          <div class="col-sm-1">
               <b>Max</b>
                  <input type="text" name="txt_max_in" id="txt_max_in" class="form-control form-control-sm" readonly="">
            </div>
            <div class="col-sm-1">
               <b>Min</b>
                  <input type="text" name="txt_min_in" id="txt_min_in" class="form-control form-control-sm" readonly="">
            </div>
              <div class="col-sm-2">
               <b>Ubicacion</b>
               <input type="text" name="txt_ubicacion" id="txt_ubicacion" class="form-control form-control-sm" readonly="">
            </div>       
          <div class="col-sm-1">
               <b>Cantidad</b>
                  <input type="text" name="txt_canti" id="txt_canti" class="form-control form-control-sm"  value="1" onblur="calculos()">
            </div>
            <div class="col-sm-1">
               <b>Precio</b>
                  <input type="text" name="txt_precio" id="txt_precio" class="form-control form-control-sm"  value="0" onblur="calculos()">
            </div>
            <div class="col-sm-1">
               <b>Pvp Ref</b>
                  <input type="text" name="txt_precio_ref" id="txt_precio_ref" class="form-control form-control-sm"  value="0" readonly="">
            </div>
            <div class="col-sm-1">
               <b>% descto</b>
                  <input type="text" name="txt_descto" id="txt_descto" class="form-control form-control-sm"  value="0" onblur="calculos()">
            </div>             
            <div class="col-sm-1">
               <b>Subtotal</b>
                  <input type="text" name="txt_subtotal" id="txt_subtotal" class="form-control form-control-sm" readonly="" value="0">
            </div>
            <div class="col-sm-1">
               <b>Iva</b>
                  <input type="text" name="txt_iva" id="txt_iva" class="form-control form-control-sm" readonly="" value="0">
            </div>  
            <div class="col-sm-1">
               <b>Total</b>
                  <input type="text" name="txt_total" id="txt_total" class="form-control form-control-sm" readonly="" value="0">
            </div>          
        </div>
        <div class="row">
          <div class="col-sm-12 text-end"><br>
               <button type="button" class="btn btn-primary btn-sm" onclick="agregar()"><i class="fa fa-plus"></i> Agregar a ingreso</button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="limpiar()"><i class="fa fa-paint-brush"></i> Limpiar</button>
            </div>
        </div>
        <input type="hidden" id="A_No" name ="A_No" value="0">
        </form> 
  </div>
</div>

<div class="row">
  <div class="card">
    <div class="card-body">
      <div class="row">
          <div  class="col-sm-12" id="tbl_ingresados">

          </div>        
      </div>

    </div>  
  </div>  
</div>



<div class="modal fade" id="Nuevo_proveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Nuevo proveedor</h4>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
      </div>
      <div class="modal-body">
         <form id="form_nuevo_proveedor">
            <div class="row">
              <div class="col-sm-8">
                <b>Nombre de proveedor</b>
                <input type="hidden" id="txt_id_prove" name="txt_id_prove" class="form-control form-control-sm">  
                <input type="text" id="txt_nombre_prove" name="txt_nombre_prove" class="form-control form-control-sm" onkeyup="limpiar_t()" onblur="nombres(this.value)">  
              </div> 
              <div class="col-sm-4">
                <b>CI / RUC</b>
                <input type="text" id="txt_ruc" name="txt_ruc" class="form-control form-control-sm">              
              </div>           
            </div>
            <div class="row">
              <div class="col-sm-12">
                <b>Direccion</b>
                <input type="text" id="txt_direccion" name="txt_direccion" class="form-control form-control-sm">  
              </div>        
            </div>
            <div class="row">
              <div class="col-sm-8">
                <b>Email</b>
                <input type="text" id="txt_email" name="txt_email" class="form-control form-control-sm">  
              </div> 
              <div class="col-sm-4">
                <b>Telefono</b>
                <input type="txt_telefono" id="txt_telefono" name="txt_telefono" class="form-control form-control-sm">              
              </div> 
            </div>
        </form>
      </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-primary" onclick="guardar_proveedor()">Guardar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div id="Nuevo_producto" class="modal fade" role="dialog" aria-bs-labelledby="exampleModalLabel" data-bs-backdrop="static">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-md">
      <div class="modal-header">
        <h4 class="modal-title">Nuevo producto</h4>
      </div>
      <div class="modal-body">
        <form id="form_nuevo_producto">
        <div class="row">
          <div class="col-sm-6">
              <b>Familiar</b>
            <div class="input-group">   
              <select id="ddl_familia_modal" name="ddl_familia_modal" class="form-select form-select-sm" onchange="familia_modal()">
                 <option value="">seleccione familia</option>
              </select>
                  <button type="button" class="btn btn-success btn-sm" onclick="$('#modal_nueva_familia').modal('show')"><i class="fa fa-plus"></i></button>
            </div>   
          </div>
          <div class="col-sm-6"> 
                <b>Cuenta Inv</b> <br>             
              <div class="input-group" style="display: flex;">   
                <select class="form-control form-control-sm"  name="ddl_cta_inv" id="ddl_cta_inv"></select>
                  <span class="">
                    <button type="button" class="btn btn-info btn-flat btn-xs" onclick="limpiar_cta('ddl_cta_inv')"><i class="fa fa-close"></i></button>
                  </span>
              </div>
          </div> 
        </div>
        <div class="row">
          <div class="col-sm-8">
             <b>Nombre de producto</b>
            <input type="text" id="txt_nombre" name="txt_nombre" class="form-control form-control-sm">              
          </div>
          <div class="col-sm-4">
            <b>Referencia</b>
            <input type="text" name="txt_ref" id="txt_ref" class="form-control form-control-sm" readonly="">              
          </div>
        </div>
        <div class="row">        
          <div class="col-sm-2">
             <b>Max</b>
            <input type="text" id="txt_max" name="txt_max" class="form-control form-control-sm">              
          </div>
          <div class="col-sm-2">
             <b>Min</b>
            <input type="text" id="txt_min" name="txt_min" class="form-control form-control-sm">              
          </div> 
          <div class="col-sm-2">
            <b>Unid Med.</b>
            <input type="text" id="txt_uni" name="txt_uni" class="form-control form-control-sm">              
          </div>
          <div class="col-sm-3">
             <b>Cod Barras</b>
            <input type="text" name="txt_cod_barras" class="form-control form-control-sm">              
          </div>
          <div class="col-sm-3">
             <b>Reg. Sanitario</b>
            <input type="text" name="txt_reg_sanitario" id="txt_reg_sanitario" class="form-control form-control-sm">              
          </div>              
        </div>
        <div class="row">
            <div class="col-sm-6">
              <b>Cuenta Costo venta</b>
               <div class="input-group" style="display:flex;">   
                <select class="form-control"  name="ddl_cta_CV" id="ddl_cta_CV" ></select>
                    <span>
                      <button type="button" class="btn btn-info btn-flat btn-xs" onclick="limpiar_cta('ddl_cta_CV')"><i class="fa fa-close"></i></button>
                    </span>
               </div>    
            </div>  
            <div class="col-sm-6">           
               <b>Cuenta Ventas</b><br>
                <div class="input-group" style="display:flex;">      
                <select class="form-control" name="ddl_cta_venta" id="ddl_cta_venta"></select>
                    <span>
                      <button type="button" class="btn btn-info btn-flat btn-xs" onclick="limpiar_cta('ddl_cta_venta')"><i class="fa fa-close"></i></button>
                    </span>
               </div>   
            </div>         
        </div>
        <div class="row">
          <div class="col-sm-6">
            <b>Cuenta Ventas 0</b>
             <div class="input-group" style="display:flex;">   
                <select class="form-control" name="ddl_cta_ventas_0" id="ddl_cta_ventas_0"></select>
                    <span>
                      <button type="button" class="btn btn-info btn-flat btn-xs" onclick="limpiar_cta('ddl_cta_ventas_0')"><i class="fa fa-close"></i></button>
                    </span>
               </div>   
          </div>  
          <div class="col-sm-6">
            <b>Cuenta Ventas Anti</b>  
             <div class="input-group" style="display:flex;">     
                <select class="form-control"  name="ddl_cta_vnt_anti" id="ddl_cta_vnt_anti"></select>
                    <span>
                      <button type="button" class="btn btn-info btn-flat btn-xs"  onclick="limpiar_cta('ddl_cta_vnt_anti')"><i class="fa fa-close"></i></button>
                    </span>
               </div>          
            </div>
        </div>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="guardar_producto()">Guardar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div id="modal_de_foto" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cargar imagen de factura</h4>
      </div>
      <div class="modal-body">
          <div class="row">
             <div class="col-sm-12">
                <p>asegurese de que su archivo sea .jpg .png .pdf</p>
                <form enctype="multipart/form-data" id="form_img" method="post">
                    <div class="custom-file">
                        <input type="file" class="form-control" id="file_img" name="file_img">
                        <input type="hidden" name="txt_nom_img" id="txt_nom_img" value="">
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" style="width: 100%" id="subir_imagen"> Subir Imagen</button>
                </form>  
                <br> 
              </div>            
          </div>       
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-primary" onclick="guardar_proveedor()">Guardar</button> -->
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="modal_nueva_familia" class="modal fade" role="dialog">
  <div class="modal-dialog modal-centered modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Nueva Familia</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <form enctype="multipart/form-data" id="form_fami" method="post">
            <div class="col-sm-12">
              <b>Codigo</b>
              <input type="text" name="txt_cod_familia" id="txt_cod_familia" class="form-control form-control-sm">
              <b>Nombre</b>
              <input type="text" name="txt_nombre_familia_new" id="txt_nombre_familia_new" class="form-control form-control-sm" >              
            </div>
          </form>  
          <br> 
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="guardar_familia()">Guardar</button>
        <button type="button" class="btn btn-default" onclick="$('#modal_nueva_familia').modal('hide')">Cancelar</button>
      </div>
    </div>
  </div>
</div>
</div>

<script type="text/javascript">
  function guardar_familia()
  {
    var cod = $('#txt_cod_familia').val();
    var nom = $('#txt_nombre_familia_new').val();
    if(cod=='' || nom=='')
    {
      Swal.fire('Llene todo los campos','','info');
      return false;
    }

    var parametros = 
    {
      'codigo':cod,
      'nombre':nom, 
    }
     $.ajax({
       data:  {parametros:parametros},
      url:   '../controlador/farmacia/articulosC.php?familia_new=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        if(response==1)
        {
          Swal.fire('Familia Agregada','','success').then(function(){            
            $('#modal_nueva_familia').modal('hide');
            $('#txt_cod_familia').val('');
            $('#txt_nombre_familia_new').val('');
          })
        }else if(response==-2)
        {
          Swal.fire(cod+' Ya esta registrado','','info');
        }else if(response==-3)
        {
          Swal.fire(nom+' Ya esta registrado','','info');
        }
      }
    });


  }
</script>


