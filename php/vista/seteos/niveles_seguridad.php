<?php  
  $entidad ='' ;$item ='' ;$nombre = '';
  if(isset($_SESSION['INGRESO']['item']))
  {
    $item =$_SESSION['INGRESO']['item'];
  }
  if(isset($_SESSION['INGRESO']['Entidad']))
  {
    $nombre =$_SESSION['INGRESO']['Entidad'];
  }
  if(isset($_SESSION['INGRESO']['IDEntidad']))
  {
    $entidad =$_SESSION['INGRESO']['IDEntidad'];
  }

?>
<script>
  var entidad = '<?php echo $entidad;?>';
  var nombre = '<?php echo $nombre;?>';
   $(document).ready(function(){

    $('#lbl_enti').text(entidad);
    $('#ddl_entidad').append($('<option>',{value:  entidad, text: nombre,selected: true }));
    cargar_empresas_seteos();
   autocmpletar_usuario();

   })
</script>
 <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script> -->
<script type="text/javascript" src="../../dist/js/empresa/niveles_seguri.js"></script>
<!--Indice de la navegación-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3"><?php echo $NombreModulo; ?>
    </div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0"  id="ruta_menu">
          <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
          </li>
        </ol>
      </nav>
    </div>          
</div>
<!--Inicio de la pantalla (HTML)-->
<div class="row">
  <div class="card">
    <div class="card-body">

      <!-- <?php print_r($_SESSION['INGRESO']); ?> -->

         <div class="row">
          <div class="col-sm-5">
            <b>Entidad:</b><b id="lbl_enti"></b> <br>
             <div class="d-flex align-items-center" id="ddl">   
                <select class="form-control form-control-sm w-100" id="ddl_entidad" name="ddl_entidad" onchange="todos_modulos();cargar_empresas();" disabled ><option value="" >Seleccione entidad</option></select>
                  <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#mymodal_user"><span class="fa fa-user"></span></button>
                  <button type="button" class="btn btn-success btn-sm" style="display:inline-flex;" data-bs-toggle="modal" data-bs-target="#myModal_ruc"><span class="fa fa-search p-1"></span> RUC</button>
              </div>
          </div>
          <div class="col-sm-3">    
           <b>Usuario</b> <br>
            <div class="d-flex align-items-center" id="ddl">   
                  <select class="form-control form-control-sm" id="ddl_usuarios"  name="ddl_usuarios" onchange="habilitarEdit();todos_modulos();buscar_permisos();">
                    <option value="">Seleccione Usuario</option>
                  </select>
                 <button type="button" id="btn_nuevo" class="btn btn-danger btn-sm" style="display:inline-flex;" data-bs-toggle="modal" data-bs-target="#myModal" onclick="nuevoUsuario()"><span class="fa fa-user-plus p-1"></span></button>
                 <button type="button"   id="btn_edit" style="display:none;" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#myModal" onclick="EditUsuario()" ><span class="fa fa-edit"></span></button>

              </div>
              <div class="input-group">
                <div class="row">
                  <div class="col-sm-6">
                    <b> Serie</b>
                     <input type="" name="serie" id="serie" class="form-control form-control-sm" placeholder="001001">
                  </div>
                </div>
              </div>
             
          </div>
          <div class="col-sm-4">
            <div class="row">
              <div class="col-sm-6">
                <b>Usuario</b> <br>
                <input type="input" name="txt_usuario" class="form-control form-control-sm" id="txt_usuario">
              </div>
              <div class="col-sm-6">
                <b>Clave</b> <br>
                <input type="input" name="txt_pass" class="form-control form-control-sm" id="txt_pass"> 
              </div>
            </div>
            <div class="col-sm-12">
              <b>Email</b><br>
              <div class="input-group">
                <input type="input" name="txt_email" class="form-control form-control-sm" id="txt_email"> 
                <div class="input-group-btn">
                  <button type="button" class="btn btn-primary btn-sm" onclick="confirmar_email()"><span class="fa fa-send-o"></span> Enviar correo</button>
                </div>
              </div> 
            </div>  
          </div>
        </div>
      
    </div>    
  </div>  
</div>
<div class="row">
	<div class="col-sm-12"><br>
		<div class="card">
      <div class="card-header" style="padding: 5px"><b>Niveles de seguridad</b></div>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-1">
              <label class="form-check-label"><input class="form-check-input" type="checkbox" name="rbl_n1" id="rbl_n1"><b>&nbsp;No. 1</b></label>
            </div>
            <div class="col-sm-1">
              <label class="form-check-label"><input class="form-check-input" type="checkbox" name="rbl_n2" id="rbl_n2"><b>&nbsp;No. 2</b></label>
            </div>
            <div class="col-sm-1">
                <label class="form-check-label"><input class="form-check-input" type="checkbox" name="rbl_n3" id="rbl_n3"><b>&nbsp;No. 3</b></label>
            </div>
            <div class="col-sm-1">
              <label class="form-check-label"><input class="form-check-input" type="checkbox" name="rbl_n4" id="rbl_n4"><b>&nbsp;No. 4</b></label>
            </div>
            <div class="col-sm-1">
              <label class="form-check-label"><input class="form-check-input" type="checkbox" name="rbl_n5" id="rbl_n5"><b>&nbsp;No. 5</b></label>
            </div>
            <div class="col-sm-1">
              <label class="form-check-label"><input class="form-check-input" type="checkbox" name="rbl_n6" id="rbl_n6"><b>&nbsp;No. 6</b></label>
            </div>
            <div class="col-sm-1">
              <label class="form-check-label"><input class="form-check-input" type="checkbox" name="rbl_n7" id="rbl_n7"><b>&nbsp;No. 7</b></label>
            </div>
            <div class="col-sm-2">
              <label class="form-check-label"><input class="form-check-input" type="checkbox" name="rbl_super" id="rbl_super"><b>&nbsp;Supervisor</b></label>
            </div>
            <div class="col-sm-3 text-right">
              <!-- <button class="btn btn-primary btn-sm" onclick="acceso_pagina()">Asignar acceso a paginas</button> -->
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-sm-12">
                <ul class="nav nav-pills nav-pills-warning mb-3" role="tablist" id="pnl_nav_empresas">
                 
                </ul>
                <hr>
                <div class="tab-content" id="pnl_modulo_empresas">
                  
                </div>
              </div>
            
          </div>
        </div>
      </div>
	  </div>      
  </div>


<div id="myModal" class="modal fade">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Nuevo usuario</h4>
      </div>
      <div class="modal-body">        
        <input type="hidden" name="txt_id_usu" id="txt_id_usu" class="form-control form-control-sm">
        <b>Usuario</b><br>
        <input type="text" name="" id="txt_usu" class="form-control form-control-sm">
        <b>Clave</b><br>
        <input type="text" name="" id="txt_cla" class="form-control form-control-sm">
        <b>Nombre completo</b><br>
        <input type="text" name="" id="txt_nom" class="form-control form-control-sm">
        <div class="row">
            <div class="col-sm-6">
                <b>Cedula</b><br>
                <input type="text" name="" id="txt_ced" class="form-control form-control-sm">
              
            </div>
            <div class="col-sm-6">
              <b>Email</b><br>
               <input type="text" name="" id="txt_ema" class="form-control form-control-sm">
              
            </div>
        </div>
        <div class="row" id="pnl_punto_emision_check" style="display:none;">
          <div class="col-sm-12">
              <label><input type="checkbox" name="rbl_emision" id="rbl_emision" onclick="showemision()"> Asignar Usuario a establecimiento o punto de emision</label>
          </div>
        </div>
        <div class="row" id="pnl_punto_emision" style="display: none;">
          <div class="col-sm-12">
            <b>Empresa</b>
            <select class="form-select form-select-sm" id="ddl_empresa_puntoEmi" name="ddl_empresa_puntoEmi" onchange="buscarPuntoVenta(this.value)" >
              <option value="">Seleccione Empresa</option>
            </select>            
          </div>
          <div class="row">
            <div class="col-sm-3">
              <b>Serie</b>           
              <input type="hidden" name="txt_id_CatLin" id="txt_id_CatLin">
              <div class="input-group">
                  <input type="" name="txt_estab" id="txt_estab" class="form-control form-control-sm w-25" onkeyup=" solo_3_numeros(this.id);solo_numeros(this)">
                  <input type="" name="txt_emision" id="txt_emision" class="form-control form-control-sm w-25" onkeyup=" solo_3_numeros(this.id);solo_numeros(this)">
              </div>            
            </div>
            <div class="col-sm-9">
                <b>Correo electronico</b>
                <input type="" name="txt_email2" id="txt_email2" class="form-control form-control-sm">            
            </div>
          </div>
          <div class="col-sm-12">
              <b>Direccion</b>
              <input type="" name="txt_direccion" id="txt_direccion" class="form-control form-control-sm">    
              <br>        
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="input-group">
                  <div class="input-group-text form-control-sm small-text">
                    <b>Telefono:</b>
                  </div>          
                  <input type="text" name="txt_telefono" id="txt_telefono" value='' class="form-control form-control-sm" />
              </div>            
            </div>
            <div class="col-sm-6">
              <div class="input-group">
                  <div class="input-group-text form-control-sm small-text">
                    <b>Logotipo (GIF):</b>
                  </div>          
                  <input type="text" name="txt_logo" id="txt_logo" value='' class="form-control form-control-sm" />
              </div>            
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">        
        <button type="button" class="btn btn-success btn-sm" onclick="guardarN()">Guardar</button>
        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="myModal_ruc" class="modal fade" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Buscar empresa por ruc</h4>
      </div>
      <div class="modal-body">
        <div class="input-group">
          <input type="text" name="" id="ruc_empresa" class="form-control" placeholder="Ingrese RUC de empresa">
          <div class="input-group-btn">
            <button type="button" class="btn btn-primary" onclick="buscar_empresa_ruc()">Buscar</button>
          </div>          
        </div>
        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
          <table class="table table-hover">
            <thead>
              <th></th>
              <th>Empresa</th>
              <th>Item</th>
              <th>Ruc asociado</th>
              <th>Estado</th>
              <th>Entidad</th>
              <th>Ruc Entidad</th>
            </thead>
            <tbody id="list_empre">
              <tr class="text-center">
                <td colspan="6"> No encontrado... </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-success" onclick="usar_busqueda()">Usar</button>
      </div>
    </div>

  </div>
</div>


<div id="mymodal_user" class="modal fade" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Usuarios de la entidad</h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
            <table class="table table-hover">
            <thead>
              <th>CI / RUC</th>
              <th>NOMBRE</th>
              <th>EMAIL</th>
            </thead>
            <tbody id="usuarios_tbl">
              <tr class="text-center">
                <td colspan="2"> No encontrado... </td>
              </tr>
            </tbody>
        </table>                  
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Cerrar</button>
        <!-- <button type="button" class="btn btn-success" onclick="usar_busqueda()">Usar</button> -->
      </div>
    </div>
  </div>
</div>


<div id="mymodal_email" class="modal fade" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Enviar email</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-sm-12" id="div_email">
                    
            </div>
        </div>        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-success" onclick="enviar_email()">Enviar</button>
      </div>
    </div>

  </div>
</div>

<div id="mymodal_acceso_pag" class="modal fade" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-xl">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Accesos para pagina</h4>
      </div>
      <div class="modal-body">
        <div class="row" id="panel_paginas">
           
        </div>        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
        <!-- <button type="button" class="btn btn-success" onclick="enviar_email()">Enviar</button> -->
      </div>
    </div>

  </div>
</div>
