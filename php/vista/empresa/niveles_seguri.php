<?php  
?>
<!--styles para elementos selected2-->
<style>
  .select2-selection__rendered {
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
  }
  /*Clase personalizada, en caso de que una clase cambie el tamaño, este lo vuelve al tamaño original*/
  .small-text {
    font-size: 14px;
  }
</style>
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
<div class="row row-cols-auto">
    <div class="btn-group">
        <!--Botón salir del modulo-->    
        <a  href="./empresa.php?mod=empresa#" data-bs-toggle="tooltip" title="Salir de modulo" class="btn btn-outline-secondary btn-sm">
            <img src="../../img/png/salire.png">
        </a>
        <!--Botón Cambiar Numero-->        
        <button type="button" data-bs-toggle="tooltip" class="btn btn-outline-secondary btn-sm" title="Cambiar Numero">
            <img src="../../img/png/change_number.png">        
        </button>      
        <!--Botón Cambio de periodo-->         
        <button type="button" data-bs-toggle="tooltip" class="btn btn-outline-secondary btn-sm"  title="Cambiar item y periodo">
        <img src="../../img/png/change_period.png">       
        </button>     
        <!--Botón Eliminar Periodo-->    
        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Eliminar periodo">
        <img src="../../img/png/delet_period.png" >
        </button>
        <!--Botón Cerrar Educativo-->    
        <button title="Cerrar Educativo" data-bs-toggle="tooltip" class="btn btn-outline-secondary btn-sm" >
        <img src="../../img/png/close_house.png" >
        </button>
        <!--Botón Cerrar Facturación-->    
        <button title="Cerrar Facturacion" data-bs-toggle="tooltip" class="btn btn-outline-secondary btn-sm">
        <img src="../../img/png/close_billing.png" >
        </button>
        <!--Botón Limpiar base de datos-->    
        <button title="Limpiar Base de datos" data-bs-toggle="tooltip" class="btn btn-outline-secondary btn-sm">
        <img src="../../img/png/limpiar.png" >
        </button>
        <!--Botón  copiar catalogos de periodo-->    
        <button title="Copiar catalogos de periodo" data-bs-toggle="tooltip" class="btn btn-outline-secondary btn-sm" onclick="guardar_accesos()">
        <img src="../../img/png/copiar_1.png" >
        </button>
        <!--Botón Guardar-->    
        <button title="Guardar" data-bs-toggle="tooltip" class="btn btn-outline-secondary btn-sm" onclick="guardar()">
        <img src="../../img/png/grabar.png" >
        </button>
        <!--Botón bloquear-->    
        <button title="Bloquear" data-bs-toggle="tooltip" class="btn btn-outline-secondary btn-sm" onclick="bloquear()">
        <img src="../../img/png/lock.png" >
        </button>
        <!--Botón desbloquear-->    
        <button title="Desbloquear" data-bs-toggle="tooltip" class="btn btn-outline-secondary btn-sm" onclick="desbloquear()">
        <img src="../../img/png/unlock.png" >
        </button>
        <!--Botón enviar credenciales masivos-->    
        <div title="Enviar credenciales masivos" data-bs-toggle="tooltip">
            <button class="btn btn-outline-secondary btn-sm rounded-0 rounded-end" onclick="" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="../../img/png/email.png" >
                <span class="fa fa-caret-down"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#" class="dropdown-item" onclick="enviar_email_masivo();">Enviar credenciales masivos</a></li>
                <!-- <li><a href="#" data-toggle="modal" data-target="#myModal_ruc" >Redactar email</a></li> -->
                    <!-- <li><a href="#">Something else here</a></li> -->
                <!-- <li class="divider"></li> -->
                <!-- <li><a href="#">Recupera</a></li> -->
            </ul>
        </div>
    </div>
</div>
 <div class="row">
	<div class="col-sm-4">
    <b>Entidad:</b><b id="lbl_enti"></b> <br>
    <div class="input-group input-group-sm" id="ddl">
        <select class="form-control form-control-sm" id="ddl_entidad" name="ddl_entidad" onchange="todos_modulos();cargar_empresas();"><option value="">Seleccione entidad</option></select>
        <span>
            <div title="Usuarios de la entidad" data-bs-toggle="tooltip" class="d-inline">
              <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#mymodal_user"><span class="fa fa-user"></span></button>
            </div>
            <div class="d-inline" title="Buscar usuario por RUC" data-bs-toggle="tooltip">
              <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#myModal_ruc"><span class="fa fa-search"></span> RUC</button>
            </div>
        </span>
    </div>  
  </div>
	<div class="col-sm-4">    
   <b>Usuario</b> <br>
    <div class="input-group">
        <select class="form-control form-control-sm" id="ddl_usuarios"  name="ddl_usuarios" onchange="habilitarEdit();todos_modulos();buscar_permisos();">
          <option value="">Seleccione Usuario</option>
        </select>
        <!-- <div class="input-group-btn"> -->
        <span class="input-group-btn" id="btn_nuevo">        
            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#myModal" onclick="nuevoUsuario()"><span class="fa fa-plus"></span> Nuevo</button>
        </span>
         <span class="input-group-btn" id="btn_edit" style="display:none;" title="Editar usuario" data-bs-toggle="tooltip">        
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#myModal" onclick="EditUsuario()" ><span class="fa fa-edit"></span></button>
        </span>
        <!-- </div> -->
      </div>
      <div class="input-group">
        <div class="row">
          <div class="col-sm-6">
             Serie
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
        </div>
      </div>
	  </div>      
  </div>


 <div class="row">
  <div class="col-sm-12">
    <div class="card">      
       <div class="card-header" style="padding: 5px">
        <div class="row">
          <div class="col-sm-6">
            <b>Lista de empresas</b>            
          </div>
          <div class="col-sm-6">
          </div>          
        </div>
       </div>
       <div class="card-body">
        <div class="box" id="todo_modulos" style="overflow-x: hidden; margin-bottom: 0px;">
          
        </div>      

        <form id="form_modulos_check">
          <div class="box" id="tbl_modulos" style="overflow-y:scroll; height: 500px;">
            
          </div>
        </form>      
       </div>
      </div> 
      <ul class="nav nav-tabs" id="tabs_titulo">
    </ul>     
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
        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-sm" onclick="guardarN()">Guardar</button>
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
  <div class="modal-dialog">
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
