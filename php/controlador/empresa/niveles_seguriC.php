<?php 
require_once(dirname(__DIR__,2).'/modelo/empresa/niveles_seguriM.php');
require_once(dirname(__DIR__,3).'/lib/phpmailer/enviar_emails.php');
//require_once(dirname(__DIR__)."/modelo/facturacion/lista_facturasM.php");

/**
 * 
 */
$controlador = new niveles_seguriC();
if (isset($_GET['modulos'])) {
	echo json_encode($controlador->modulos($_POST['parametros']));
}
if (isset($_GET['empresas'])) {
	echo json_encode($controlador->empresas($_POST['entidad']));
}
if (isset($_GET['usuarios'])) {
	if(!isset($_GET['q']))
	{
		$_GET['q'] =''; 
	}
	$parametros = array('entidad'=>$_GET['entidad'],'query'=>$_GET['q']);
	echo json_encode($controlador->usuarios($parametros));
}
if (isset($_GET['entidades'])) {
	if(!isset($_GET['q']))
	{
		$_GET['q']='';
	}
	echo json_encode($controlador->entidades($_GET['q']));
}
if(isset($_GET['mod_activos']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->mod_activos($parametros['entidad'],$parametros['empresa'],$parametros['usuario']));
}
if(isset($_GET['usuario_data']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->data_usuario($parametros['entidad'],$parametros['usuario']));
}
if(isset($_GET['guardar_datos']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->guardar_datos_modulo($parametros));
}
if(isset($_GET['bloqueado']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->bloqueado_usurio($parametros));
}
if(isset($_GET['desbloqueado']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->desbloqueado_usurio($parametros));
}
if(isset($_GET['nuevo_usuario']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->nuevo_usurio($parametros));
}
if(isset($_GET['buscar_ruc']))
{
	$parametros=$_POST['ruc'];
	echo json_encode($controlador->buscar_ruc($parametros));
}
if(isset($_GET['usuario_empresa']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->modulos_usuario($parametros['entidad'],$parametros['usuario']));
	// echo json_encode($controlador->usuario_empresa($parametros['entidad'],$parametros['usuario']));
}

if(isset($_GET['acceso_todos']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->accesos_todos($parametros));
	// echo json_encode($controlador->usuario_empresa($parametros['entidad'],$parametros['usuario']));
}


if(isset($_GET['acceso_todos_empresa']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->accesos_todos_empresa($parametros));
	// echo json_encode($controlador->usuario_empresa($parametros['entidad'],$parametros['usuario']));
}

if(isset($_GET['enviar_email']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->enviar_email($parametros));
	// echo json_encode($controlador->usuario_empresa($parametros['entidad'],$parametros['usuario']));
}
if(isset($_GET['confirmar_enviar_email']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->confirmar_enviar_email($parametros));
	// echo json_encode($controlador->usuario_empresa($parametros['entidad'],$parametros['usuario']));
}

if(isset($_GET['enviar_email_masivo']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->enviar_email_masivo($parametros));
	// echo json_encode($controlador->usuario_empresa($parametros['entidad'],$parametros['usuario']));
}

if(isset($_GET['todos_modulos']))
{
	// $parametros=$_POST['parametros'];
	echo json_encode($controlador->todos_los_modulos());
	// echo json_encode($controlador->usuario_empresa($parametros['entidad'],$parametros['usuario']));
}

if(isset($_GET['paginas_acceso']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->paginas_acceso($parametros));
}
if(isset($_GET['savepag']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->savepag($parametros));
}
if(isset($_GET['buscarPuntoVenta']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->buscarPuntoVenta($parametros));
}




class niveles_seguriC
{
	private $modelo;
	private $email;
	
	function __construct()
	{
		$this->modelo = new niveles_seguriM();	
		$this->email = new enviar_emails();	
		//$this->modeloFac = new lista_facturasM();
		// $this->empresaGeneral = $this->modelo->Empresa_data();
	}
	function entidades($valor)
	{
		$entidades = $this->modelo->entidades($valor);
		echo json_encode($entidades);
		exit();
		return $entidades;

	}
	// function empresas($entidad)
	// {
	// 	 $this->acceso_modulos($entidad);
	// 	// $empresas = $this->modelo->empresas($entidad);
	// 	// $items = '';
	// 	// $linea = '';
	// 	// foreach ($empresas as $key => $value) {
	// 	// 	$linea.= $value['id'].',';
	// 	// 	$items .= '<label class="checkbox-inline" id="lbl_'.$value['id'].'"><input type="checkbox" name="empresas[]" id="emp_'.$value['id'].'" value="'.$value['id'].'" onclick="empresa_select(\''.$value['id'].'\')"><i class="fa fa-circle-o text-red" style="display:none" id="indice_'.$value['id'].'"></i><b>'.utf8_decode($value['text']).'</b></label><br>';
			
	// 	// }
	// 	// $linea = substr($linea,0,-1);
	// 	// return  array('items' => $items,'linea'=>$linea);

	// }
	function usuarios($parametros)
	{
		$parametros['entidad'] = '';
		$usuarios = $this->modelo->usuarios($parametros['entidad'],$parametros['query']);
		// print_r($usuarios);die();
		return $usuarios;
	}
	function modulos($parametros)
	{
		// print_r($parametros);die();
		$conjunto_empresa = substr($parametros['empresa'],0,-1);
		$empresa_selec =explode(',', $conjunto_empresa);
		$items = '';
		$tabs = '';
		foreach ($empresa_selec as $key => $value) {
			$datos_empresas = $this->modelo->empresas_datos($parametros['entidad'],$value);
			if(count($datos_empresas)>0)
			{
				if($key==0)
				{
					$tabs.='<li class="active" id="tab_'.$value.'" onclick="activo(\''.$value.'\')"><a data-toggle="tab" href="#'.$datos_empresas[0]['Item'].'">'.$datos_empresas[0]['text'].'</a></li>';

				}else
				{
					$tabs.='<li><a data-toggle="tab" href="#'.$datos_empresas[0]['id'].'">'.$datos_empresas[0]['text'].'</a></li>';
				}
				 $modulos = $this->modelo->modulos_todo();
				 if(count($datos_empresas)>0)
				 {				 	
				 	if($key==0)
				 	{
				 		$items.='<div id="'.$datos_empresas[0]['Item'].'" class="tab-pane fade in active">';
				 	}else
				 	{
				 		$items.='<div id="'.$datos_empresas[0]['Item'].'" class="tab-pane fade">';
				 	}
				 	$items.='<form id="form_'.$value.'">';
				 	$mod = $this->modelo->acceso_empresas($parametros['entidad'],$value,$parametros['usu']);
				 	$existente = 0;
				 		// print_r($mod);die();
				 	foreach ($modulos as $key1 => $value1) {
				 		if(count($mod)>0)
				 		{
				 			foreach ($mod as $key2 => $value2) {
				 				if ($value2['Modulo'] == $value1['modulo']) {
				 					$existente = 1;
				 					break;
				 				}
				 			}
				 			if($existente == 1)
				 			 {
				 			 	$items .= '<label class="checkbox-inline"><input type="checkbox" name="modulos_'.$value.'_'.$value1['modulo'].'" id="modulos_'.$value.'_'.$value1['modulo'].'" value="'.$value1['modulo'].'" checked><b>'.$value1['aplicacion'].'</b></label><br>';				 				
				 			 }else
				 			 {
				 			 	$items .= '<label class="checkbox-inline"><input type="checkbox" name="modulos_'.$value.'_'.$value1['modulo'].'" id="modulos_'.$value.'_'.$value1['modulo'].'" value="'.$value1['modulo'].'"><b>'.$value1['aplicacion'].'</b></label><br>';
				 			 }
				 			 $existente = 0;
				 		}else
				 		{
				 			$items .= '<label class="checkbox-inline"><input type="checkbox" name="modulos_'.$value.'_'.$value1['modulo'].'" id="modulos_'.$value.'_'.$value1['modulo'].'" value="'.$value1['modulo'].'"><b>'.$value1['aplicacion'].'</b></label><br>';
				 		}
				 	}
				 	$items.='</form></div>';
				 }

			}			
		}
		$contenido = array('header'=>$tabs,'body'=>'<div class="tab-content" id="tab-content">'.$items.'</div>');
		// print_r($contenido);die();		
		return $contenido;

	}
	function mod_activos($entidad,$empresa,$usuario)
	{
		$mod = $this->modelo->acceso_empresas($entidad,$empresa,$usuario);
		return $mod;

	}
	function data_usuario($entidad,$usuario)
	{
		$data = $this->modelo->datos_usuario($entidad,$usuario);
		return $data;
	}

	function guardar_datos_modulo($parametros)
	{

		// print_r($parametros);die();

		$niveles = array('1'=>$parametros['n1'],'2'=>$parametros['n2'],'3'=>$parametros['n3'],'4'=>$parametros['n4'],'5'=>$parametros['n5'],'6'=>$parametros['n6'],'7'=>$parametros['n7'],'super'=>$parametros['super']);
		 $this->modelo->update_acceso_usuario($niveles,$parametros['usuario'],$parametros['pass'],$parametros['entidad'],$parametros['CI_usuario'],$parametros['email'],$parametros['serie']);

		 $mensaje = '';
		$respuesta = 1;
		$server_estado = 1;

		if(isset($parametros['empresas']))
		{
			$modulos = $parametros['modulos'];
			$empresas = $parametros['empresas'];
			$empresas = array_unique($empresas);
			$modulos_sqlserver = array();
			// filtra los check de son de las empresas seleccionadas
			$modulos_filtrados = array_filter($modulos, function($modulo) use ($empresas) {
			    return in_array($modulo[1], $empresas);
			});
			// escoje solo los checks en true
			$array_filtrado = array_filter($modulos_filtrados, function($elem) {
				// print_r($elem);die();
			    return $elem[2] == 'true';
			});

		


			// elimina en mysql
			foreach ($empresas as $key => $value) {
				$this->modelo->delete_modulos_mysql($parametros['entidad'],$value,$parametros['CI_usuario'],false);
			}

			foreach ($array_filtrado as $key => $value) 
			{
				// print_r($value)
				// $value[0] =>modulo  $value[1] => item empresa $value[2] ==> si esta activo o inactivo					
				$r = $this->modelo->guardar_acceso_empresa($value[0],$parametros['entidad'],$value[1],$parametros['CI_usuario']);
				array_push($modulos_sqlserver, array('modulo'=>$value[0],'item'=>$value[1]));
			}

	

		// fin de ingreso en MYSQL
		// print_r($empresas);die();

		// --------------------------ingreso sql server-----------------------------------
			foreach ($empresas as $key => $value) 
			{
				// validamos si tiene datos de conexion
				$empresa = $value;
				$datos = $this->modelo->empresas_datos($parametros['entidad'],$value);
				if(count($datos)>0)
				{
					// valida que datos de conexion no esten en vacio o con punto
					if($datos[0]['host']!='' && $datos[0]['usu']!='' && $datos[0]['pass']!='' && $datos[0]['base']!='' && $datos[0]['Puerto']!='' && $datos[0]['host']!='.' && $datos[0]['usu']!='.' && $datos[0]['pass']!='.' && $datos[0]['base']!='.' && $datos[0]['Puerto']!='.')
						{
							$resp = $this->modelo->comprobar_conexion($datos[0]['host'],$datos[0]['usu'],$datos[0]['pass'],$datos[0]['base'],$datos[0]['Puerto']);
							if($resp!='-1')
							{
								// entra a hacer todo el proceso
								//busca en tabla  accesos de sqlserver para actualizar o crear
								$respuesta = $this->modelo->existe_en_SQLSERVER($parametros['entidad'],$empresa,$parametros['CI_usuario']);

								switch ($respuesta['resp']) {
									case '1':
										// si encuentra datos actualiza en sql server
										$res = $this->modelo->actualizar_en_sql_tercero($parametros['entidad'],$empresa,$parametros['CI_usuario'],$parametros);
										if($res==-1)
										{
											$mensaje.='No se puedo actualizar en SQLServer: items'.$empresa.' Entidad:'.$parametros['entidad'];
											$resp = 0;
										}else if($res==-2)
										{
											$mensaje.='<br>Base de datos o credenciales SQLServer no valida en items'.$empresa.' Entidad:'.$parametros['entidad'];
											$resp = 0;
										}	
										 // $this->modelo->update_acceso_usuario($niveles,$parametros['usuario'],$parametros['pass'],$parametros['entidad'],$parametros['CI_usuario'],$parametros['email'],$parametros['serie']);
										break;
									case '-2':
										//cuando tiene credenciales validas pero el usuario no existe
										$res = $this->modelo->insertat_en_sql_terceros($parametros['entidad'],$empresa,$parametros['CI_usuario'],$parametros);
										if($res==-1)
										{
											$mensaje.='No se puedo Insertar en SQLServer: items'.$empresa.' Entidad:'.$parametros['entidad'];
											$resp = 0;
										}else if($res==-2)
										{
											$mensaje.='<br>Base de datos o credenciales SQLServer no valida en items'.$empresa.' Entidad:'.$parametros['entidad'];
											$resp = 0;
										}	
										break;				
									default:
									    // cuando las conexiones del sqlserver o base de datos no son las correctas
										$mensaje.='<br>Base de datos o credenciales SQLServer no valida en items'.$empresa.' Entidad:'.$parametros['entidad'];
										$resp = 0;
										break;
								}


								//busca en clientes para actualizar o insertar
								$respuesta = $this->modelo->existe_en_SQLSERVER_cliente($parametros['entidad'],$empresa,$parametros['CI_usuario']);
								switch ($respuesta['resp']) {
									case '1':
										// si encuentra datos actualiza en sql server
										$res = $this->modelo->actualizar_cliente_en_sql_tercero($parametros['entidad'],$empresa,$parametros['CI_usuario'],$parametros);
										if($res==-1)
										{
											$mensaje.='No se puedo actualizar Cliente en SQLServer: items'.$empresa.' Entidad:'.$parametros['entidad'];
											$resp = 0;
										}else if($res==-2)
										{
											$mensaje.='<br>Base de datos o credenciales SQLServer no valida en items'.$empresa.' Entidad:'.$parametros['entidad'];
											$resp = 0;
										}	
										break;
									case '-2':
										//cuando tiene credenciales validas pero el usuario no existe
										$res = $this->modelo->insertar_cliente_en_sql_terceros($parametros['entidad'],$empresa,$parametros['CI_usuario'],$parametros);
										if($res==-1)
										{
											$mensaje.='No se puedo Insertar Cliente en SQLServer: items'.$empresa.' Entidad:'.$parametros['entidad'];
											$resp = 0;
										}else if($res==-2)
										{
											$mensaje.='<br>Base de datos o credenciales SQLServer no valida en items'.$empresa.' Entidad:'.$parametros['entidad'];
											$resp = 0;
										}	
										break;				
									default:
									    // cuando las conexiones del sqlserver o base de datos no son las correctas
										$mensaje.='<br>Base de datos o credenciales SQLServer no valida en items'.$empresa.' Entidad:'.$parametros['entidad'];
										$resp = 0;
										break;
								}

								//verifica si esta en acceso_empresa
								 $this->modelo->eliminar_en_SQLSERVER_acceso_empresa($parametros['entidad'],$empresa,$parametros['CI_usuario']);


								foreach ($modulos_sqlserver as $key => $value3) 
								{
									// print_r($empresa);print_r($modulos_sqlserver);die();
									if($value3['item']==$empresa)
									{
										$modulo = $value3['modulo'];

											//cuando tiene credenciales validas pero el usuario no existe
											$res = $this->modelo->insertar_acceso_en_sql_terceros($parametros['entidad'],generaCeros($empresa,3),$parametros['CI_usuario'],generaCeros($modulo,2));
											if($res==-1)
											{
												$mensaje.='No se puedo Insertar Cliente en SQLServer: items'.$empresa.' Entidad:'.$parametros['entidad'];
												$resp = 0;
											}else if($res==-2)
											{
												$mensaje.='<br>Base de datos o credenciales SQLServer no valida en items'.$empresa.' Entidad:'.$parametros['entidad'];
												$resp = 0;
											}
											// $this->modelo->guardar_acceso_empresa($modulo,$parametros['entidad'],$empresa,$parametros['CI_usuario']);
									}	
											
								}
								
							}else
							{
								// $this->modelo->guardar_acceso_empresa($modulo,$parametros['entidad'],$empresa,$parametros['CI_usuario']);
								$mensaje.='<br>Base de datos o credenciales SQLServer no valida en items'.$empresa.' Entidad:'.$parametros['entidad'];
							}
						}else
						{
							// $this->modelo->guardar_acceso_empresa($modulo,$parametros['entidad'],$empresa,$parametros['CI_usuario']);
							$mensaje.='<br>Credenciales SQLServer no valida en items'.$empresa.' Entidad:'.$parametros['entidad'];
						}
				}
			}
		}else{
			// actualiza solo las credenciales
			$empresas = $this->modelo->item_empresas_usuarios($parametros['entidad'],$parametros['CI_usuario'],$item=false,$modulo=false);
			foreach ($empresas as $key => $value) {
				$empresa = $value['Item'];
				$datos = $this->modelo->empresas_datos($parametros['entidad'],$value['Item']);
				// print_r($datos);die();
				if(count($datos)>0)
				{
					// valida que datos de conexion no esten en vacio o con punto
					if($datos[0]['host']!='' && $datos[0]['usu']!='' && $datos[0]['pass']!='' && $datos[0]['base']!='' && $datos[0]['Puerto']!='' && $datos[0]['host']!='.' && $datos[0]['usu']!='.' && $datos[0]['pass']!='.' && $datos[0]['base']!='.' && $datos[0]['Puerto']!='.')
						{
							$resp = $this->modelo->comprobar_conexion($datos[0]['host'],$datos[0]['usu'],$datos[0]['pass'],$datos[0]['base'],$datos[0]['Puerto']);
							if($resp!='-1')
							{
								// entra a hacer todo el proceso
								//busca en tabla  accesos de sqlserver para actualizar o crear
								$respuesta = $this->modelo->existe_en_SQLSERVER($parametros['entidad'],$empresa,$parametros['CI_usuario']);

								switch ($respuesta['resp']) {
									case '1':
										// si encuentra datos actualiza en sql server
										$res = $this->modelo->actualizar_en_sql_tercero($parametros['entidad'],$empresa,$parametros['CI_usuario'],$parametros);
										if($res==-1)
										{
											$mensaje.='No se puedo actualizar en SQLServer: items'.$empresa.' Entidad:'.$parametros['entidad'];
											$resp = 0;
										}else if($res==-2)
										{
											$mensaje.='<br>Base de datos o credenciales SQLServer no valida en items'.$empresa.' Entidad:'.$parametros['entidad'];
											$resp = 0;
										}	
										 // $this->modelo->update_acceso_usuario($niveles,$parametros['usuario'],$parametros['pass'],$parametros['entidad'],$parametros['CI_usuario'],$parametros['email'],$parametros['serie']);
										break;
									case '-2':
										//cuando tiene credenciales validas pero el usuario no existe
										$res = $this->modelo->insertat_en_sql_terceros($parametros['entidad'],$empresa,$parametros['CI_usuario'],$parametros);
										if($res==-1)
										{
											$mensaje.='No se puedo Insertar en SQLServer: items'.$empresa.' Entidad:'.$parametros['entidad'];
											$resp = 0;
										}else if($res==-2)
										{
											$mensaje.='<br>Base de datos o credenciales SQLServer no valida en items'.$empresa.' Entidad:'.$parametros['entidad'];
											$resp = 0;
										}	
										break;				
									default:
									    // cuando las conexiones del sqlserver o base de datos no son las correctas
										$mensaje.='<br>Base de datos o credenciales SQLServer no valida en items'.$empresa.' Entidad:'.$parametros['entidad'];
										$resp = 0;
										break;
								}
							}
						}
					}

				
			}
			// print_r($empresa);die();
		}
		// print_r($mensaje.'-'.$resp);die();
		 $r =  array('estado_proceso'=>$respuesta,'mensaje'=>$mensaje);	
		 return $r;
		
	}
	function guardar_datos_modulo2($parametros)
	{

		 // print_r($parametros);die();
		$modulos_empresa = array();
		$empresas_eli = array();

		//si viene marcado los modulos;
		if($parametros['modulos']!='')
		{ 
			$modulos_empresa = str_replace('rbl_','',$parametros['modulos']); 
			$modulos_empresa = str_replace('=on','',$modulos_empresa); 
			$modulos_empresa = str_replace('&',',',$modulos_empresa); 
			$modulos_empresa = str_replace('_','-',$modulos_empresa);
			$modulos_empresa = explode(',',$modulos_empresa);
		}else
		{
			// en el caso de que se haya borrado tyodo los acceso entra y consulta todos los acceso de la entidad y usuario
			$modulos_empresa_delete =  $this->modelo->accesos_modulos($parametros['entidad'],$parametros['CI_usuario']);
			foreach ($modulos_empresa_delete as $key => $value) {
			$modulo = $value['Modulo'];
			$empresa = $value['Item'];
			array_push($empresas_eli,$empresa);			
			}
		}
		$mensaje = '';
		$resp = 1;
		$server_estado = 1;

		$niveles = array('1'=>$parametros['n1'],'2'=>$parametros['n2'],'3'=>$parametros['n3'],'4'=>$parametros['n4'],'5'=>$parametros['n5'],'6'=>$parametros['n6'],'7'=>$parametros['n7'],'super'=>$parametros['super']);
		
		foreach ($modulos_empresa as $key => $value) {
			$datos = explode('-', $value);
			$modulo = $datos[0];
			$empresa = $datos[1];
			array_push($empresas_eli,$empresa);			
		}
		$empresas_eli =  array_unique($empresas_eli);

		// print_r($empresas_eli);die();
		foreach ($empresas_eli as $key => $value) {
			$this->modelo->delete_modulos_mysql($parametros['entidad'],$value,$parametros['CI_usuario']);
		}

		 // print_r($modulos_empresa);die();
		foreach ($modulos_empresa as $key => $value) 
		{

			$datos = explode('-', $value);
			$modulo = $datos[0];
			$empresa = $datos[1];
			$server_estado = 1;		

			//busca en tabla  accesos de sqlserver para actualizar o crear
			$respuesta = $this->modelo->existe_en_SQLSERVER($parametros['entidad'],$empresa,$parametros['CI_usuario']);

			if($respuesta['resp']!=-1)
			{
				
				switch ($respuesta['resp']) {
					case '1':
						// si encuentra datos actualiza en sql server
						$res = $this->modelo->actualizar_en_sql_tercero($parametros['entidad'],$empresa,$parametros['CI_usuario'],$parametros);
						if($res==-1)
						{
							$mensaje.='No se puedo actualizar en SQLServer: items'.$empresa.' Entidad:'.$parametros['entidad'];
							$resp = 0;
						}else if($res==-2)
						{
							$mensaje.='<br>Base de datos o credenciales SQLServer no valida en items'.$empresa.' Entidad:'.$parametros['entidad'];
							$resp = 0;
						}	
						 $this->modelo->update_acceso_usuario($niveles,$parametros['usuario'],$parametros['pass'],$parametros['entidad'],$parametros['CI_usuario'],$parametros['email'],$parametros['serie']);
						break;
					case '-2':
						//cuando tiene credenciales validas pero el usuario no existe
						$res = $this->modelo->insertat_en_sql_terceros($parametros['entidad'],$empresa,$parametros['CI_usuario'],$parametros);
						if($res==-1)
						{
							$mensaje.='No se puedo Insertar en SQLServer: items'.$empresa.' Entidad:'.$parametros['entidad'];
							$resp = 0;
						}else if($res==-2)
						{
							$mensaje.='<br>Base de datos o credenciales SQLServer no valida en items'.$empresa.' Entidad:'.$parametros['entidad'];
							$resp = 0;
						}	
						break;				
					default:
					    // cuando las conexiones del sqlserver o base de datos no son las correctas
						$mensaje.='<br>Base de datos o credenciales SQLServer no valida en items'.$empresa.' Entidad:'.$parametros['entidad'];
						$resp = 0;
						break;
				}
			
				//busca en clientes para actualizar o insertar
				$respuesta = $this->modelo->existe_en_SQLSERVER_cliente($parametros['entidad'],$empresa,$parametros['CI_usuario']);
				switch ($respuesta['resp']) {
					case '1':
						// si encuentra datos actualiza en sql server
						$res = $this->modelo->actualizar_cliente_en_sql_tercero($parametros['entidad'],$empresa,$parametros['CI_usuario'],$parametros);
						if($res==-1)
						{
							$mensaje.='No se puedo actualizar Cliente en SQLServer: items'.$empresa.' Entidad:'.$parametros['entidad'];
							$resp = 0;
						}else if($res==-2)
						{
							$mensaje.='<br>Base de datos o credenciales SQLServer no valida en items'.$empresa.' Entidad:'.$parametros['entidad'];
							$resp = 0;
						}	
						break;
					case '-2':
						//cuando tiene credenciales validas pero el usuario no existe
						$res = $this->modelo->insertar_cliente_en_sql_terceros($parametros['entidad'],$empresa,$parametros['CI_usuario'],$parametros);
						if($res==-1)
						{
							$mensaje.='No se puedo Insertar Cliente en SQLServer: items'.$empresa.' Entidad:'.$parametros['entidad'];
							$resp = 0;
						}else if($res==-2)
						{
							$mensaje.='<br>Base de datos o credenciales SQLServer no valida en items'.$empresa.' Entidad:'.$parametros['entidad'];
							$resp = 0;
						}	
						break;				
					default:
					    // cuando las conexiones del sqlserver o base de datos no son las correctas
						$mensaje.='<br>Base de datos o credenciales SQLServer no valida en items'.$empresa.' Entidad:'.$parametros['entidad'];
						$resp = 0;
						break;
				}

			//verifica si esta en acceso_empresa
				$respuesta = $this->modelo->existe_en_SQLSERVER_acceso_empresa($parametros['entidad'],$empresa,$parametros['CI_usuario'],$modulo);
				// print_r($respuesta);die();
				switch ($respuesta['resp']) {
					case '1':
						// verifica si esta en mysql
					    $this->modelo->guardar_acceso_empresa($modulo,$parametros['entidad'],$empresa,$parametros['CI_usuario']);
						break;
					case '-2':
						//cuando tiene credenciales validas pero el usuario no existe
						$res = $this->modelo->insertar_acceso_en_sql_terceros($parametros['entidad'],generaCeros($empresa,3),$parametros['CI_usuario'],generaCeros($modulo,2));
						if($res==-1)
						{
							$mensaje.='No se puedo Insertar Cliente en SQLServer: items'.$empresa.' Entidad:'.$parametros['entidad'];
							$resp = 0;
						}else if($res==-2)
						{
							$mensaje.='<br>Base de datos o credenciales SQLServer no valida en items'.$empresa.' Entidad:'.$parametros['entidad'];
							$resp = 0;
						}
						$this->modelo->guardar_acceso_empresa($modulo,$parametros['entidad'],$empresa,$parametros['CI_usuario']);	
						break;				
					default:
					    // cuando las conexiones del sqlserver o base de datos no son las correctas
						$mensaje.='<br>Base de datos o credenciales SQLServer no valida en items'.$empresa.' Entidad:'.$parametros['entidad'];
						$resp = 0;
						break;
				}
			}else
			{
				$this->modelo->guardar_acceso_empresa($modulo,$parametros['entidad'],$empresa,$parametros['CI_usuario']);
				$mensaje.='<br>Base de datos o credenciales SQLServer no valida en items'.$empresa.' Entidad:'.$parametros['entidad'];
			}
		    // return $r;	
			//validamos si existe en sql 

		 } 

		    $r =  array('estado_proceso'=>$resp,'mensaje'=>$mensaje);	

		    return $r;

	// 	 	print_r($r);die();

	// 	print_r($modulos_empresa);die();

	// 	///falta optimizar de aqui 
	// 	$r = $this->modelo->existe_en_SQLSERVER($parametros);
	// 	// print_r($r);die();
	// 	if($r['respuesta']==1)
	// 	{		

	// 	$niveles = array('1'=>$parametros['n1'],'2'=>$parametros['n2'],'3'=>$parametros['n3'],'4'=>$parametros['n4'],'5'=>$parametros['n5'],'6'=>$parametros['n6'],'7'=>$parametros['n7'],'super'=>$parametros['super']);

	// 	// $insert = $this->modelo->guardar_acceso_empresa($modulos,$parametros['entidad'],$empresa,$parametros['CI_usuario']);


	// 	$update = $this->modelo->update_acceso_usuario($niveles,$parametros['usuario'],$parametros['pass'],$parametros['entidad'],$parametros['CI_usuario'],$parametros['email'],$parametros['serie']);
	// 	if($update == 1)
	// 	{
	// 		return $r['respuesta'];
	// 	}else
	// 	{
	// 		return -1 ;
	// 	}
	// }else
	// {
	// 	return $r['respuesta'];
	// }


	}


	function bloqueado_usurio($parametros)
	{
		$rest = $this->modelo->bloquear_usuario($parametros['entidad'],$parametros['usuario']);
		return $rest;

	}

	function desbloqueado_usurio($parametros)
	{
		$rest = $this->modelo->desbloquear_usuario($parametros['entidad'],$parametros['usuario']);
		return $rest;

	}

	function nuevo_usurio($parametros)
	{
		// print_r($parametros);die();
		$parametros['n1'] = 0;
		$parametros['n2'] = 0;
		$parametros['n3'] = 0;
		$parametros['n4'] = 0;
		$parametros['n5'] = 0;
		$parametros['n6'] = 0;
		$parametros['n7'] = 0;
		$parametros['super'] = 0;
		// $parametros['email'] = '.';

		if($parametros['id']!='')
		{
			return $this->editar_insertar_catalogo_lineas($parametros);			

		}else{

			$existe = $this->modelo->usuario_existente($parametros['usu'],$parametros['cla'],$parametros['ent']);
			if($existe == 1)
			{
				return -2;
			}else
			{
				$op = $this->modelo->nuevo_usuario($parametros);
				if($op==1)
				{
					return 1;
				}else if($op == -3)
				{
					return -3;
				}
				else
				{
					return -1;
				}			
			}
		}
		// $rest = $this->modelo->nuevo_usuario();
		// return $rest;

	}

	function buscar_ruc($parametros)
	{
		// print_r($parametros);die();
		$existe = $this->modelo->buscar_ruc($parametros);
		if(count($existe)>0)
		{
			return $existe;
		}else
		{
			return -1;
		}

	}

	function editar_insertar_catalogo_lineas($parametros)
	{
		// print_r($parametros);die();
		$Vencimiento=date('Y-m-d', strtotime('+1 year'));
		$serie = $parametros['estab'].$parametros['emision'];

			// $this->modelo->actualizar_datos_basicos($id,$parametros);
		$empresa = $this->modelo->empresas_datos($parametros['ent'],$parametros['emp']);
		if(isset($parametros['idCL']))
		{
			if($parametros['idCL']=='')
			{
			$sql="INSERT INTO Catalogo_Lineas (
						Codigo,Concepto,Fecha,
						Vencimiento,Fact,Serie,
				        Secuencial,Autorizacion,CxC,
				        CxC_Anterior,Logo_Factura,Largo,
				        Ancho,Nombre_Establecimiento,Direccion_Establecimiento,
				        Email_Establecimiento,Telefono_Estab,Logo_Tipo_Estab,
				        RUC_Establecimiento,TL,Individual,
				        Periodo,Item
			    	) values ( 
	          			'S".$serie."','CXC ELECTRONICOS ".$serie."','".date('Y-m-d')."','"
	          			.$Vencimiento."', 'FA','".$serie."',
	          			1,'".$empresa[0]['RUC']."','1.1.03.01', 
	          			'1.1.03.01', 'FactMult',9.3,
	          			10,'".$parametros['nom']."','".$parametros['direc']."','".
	          			$parametros['email2']."','".$parametros['tel']."','".$parametros['logo']."','".
	          			$parametros['ced']."001',1,0,
	          			'.','".$parametros['emp']."') ";
	         }else
	         {
	         	$sql="UPDATE  Catalogo_Lineas SET
						Codigo = 'S".$serie."',
						Concepto = 'CXC ELECTRONICOS ".$serie."',
						Serie = '".$serie."',
				        Nombre_Establecimiento = '".$parametros['nom']."',
				        Direccion_Establecimiento = '".$parametros['direc']."',
				        Email_Establecimiento = '".$parametros['email2']."',
				        Telefono_Estab = '".$parametros['tel']."',
				        Logo_Tipo_Estab = '".$parametros['logo']."',
				        RUC_Establecimiento = '".$parametros['ced']."001'
	          			WHERE ID = '".$parametros['idCL']."'
	          			";
	         }

	         // print_r($sql);die();
			$resp = $this->modelo->ejecutar_sql_terceros($sql,$parametros['ent'],$parametros['emp']);
			return $resp;
		}

	}

	// function usuario_empresa($entidad,$usuario)
	// {
	// 	$emp = $this->modelo->usuario_empresas($entidad,$usuario);
	// 	$linea = '';
	// 	foreach ($emp as $key => $value) {
	// 		$linea.=$value['Item'].',';
	// 	}

	// 	// print_r($linea);die();
	// 	return $linea;
	// }

	function todos_los_modulos()
	{
		$parametros = $_POST['parametros'];
		// print_r($parametros);

		$tbl2 = '';
		$modulos = $this->modelo->modulos_todo();
				$tbl2.='<div class="row">
								<div class=" col-xs-2 col-sm-3 col-lg-3" style="background-color:#e2fbff;">
								Aplicar a todas las empresas
									<br>							
								</div>
            				<div class="col-xs-10 col-lg-9" style="overflow-x:scroll;">
              					<div class="row"><div class="col-sm-12">';


			   $tbl2.='			    
			   <table class="table-sm" style="margin-bottom:0px;font-size:11px;white-space: nowrap;"><tr>';
			foreach ($modulos as $key2 => $value2) {	
				$server = '';
				// if($value1['dbSQLSERVER']==0){$server = 'Disabled';}
				// $numero = floatval($value2['codMenu']);
				if($parametros['entidad']!=0 && $parametros['entidad']!=1 && $parametros['entidad']!=''&& $parametros['entidad']!=null) 
				{
					// print_r('diferente a prisma');
					if($value2['modulo']<90)
					{							
						$tbl2.='<td class="text-center" style="border: solid 1px; width: 50px;">
								'.$value2['aplicacion'].'</br>
				            <input type="checkbox" name="rbl_'.$value2['modulo'].'" id="rbl_'.$value2['modulo'].'" title="'.$value2['aplicacion'].'" onclick="marcar_acceso_todos(\''.$value2['modulo'].'\')">
				        </td>';
				    }
				}else
				{

					// print_r('diferente a prisma');
					$tbl2.='<td class="text-center" style="border: solid 1px; width: 50px;">
								'.$value2['aplicacion'].'</br>
				            <input type="checkbox" name="rbl_'.$value2['modulo'].'" id="rbl_'.$value2['modulo'].'" title="'.$value2['aplicacion'].'" onclick="marcar_acceso_todos(\''.$value2['modulo'].'\')">
				        </td>';

				}
			}
			$tbl2.='</tr></table></div></div></div>';

			return $tbl2;

	}


	function empresas($entidad)
	{
		$tbl2 = '';
		$modulos = $this->modelo->modulos_todo();
		$empresas = $this->modelo->empresas($entidad);
		$empresas_unicas = filtra_datos_unico_array($empresas, 'id');
		$usuarios_reg = $this->modelo->usuarios_registrados_entidad($entidad);
		$mensaje = '';
		$count_emp = 0;

		//validando que los items sean unicos
		foreach ($empresas_unicas as $key => $value2) {
			// $item_temp = $value2['id'];
			foreach ($empresas as $key3 => $value3) {
				if($value2['id']==$value3['id'])
				{
					$count_emp++;		
				}
			}
			$count_emp = $count_emp-1;
			if($count_emp>=1)
			{
				$mensaje.='Item de empresa:<br> '.$value2['id'].' '.$value2['text'].' Veces Repetido: '.$count_emp.'<br>';
				$count_emp = 0;
			}
		}
		// creamos el grid de modulos y empresas

			foreach ($empresas as $key1 => $value1) {
				$server = '<p><i class="fa fa-circle text-success"></i> En linea</p>';
				if($value1['dbSQLSERVER']==0)
				{$server = '<p><i class="fa fa-circle text-danger"></i> Acceso SQLServer no configurado</p>';}
				else if($value1['dbSQLSERVER']==2)
				{
					$server = '<p><i class="fa fa-circle text-yellow"></i> Base de datos o nonexion no establecida</p>';
				}
				$tbl2.='<div class="row">
								<div class=" col-xs-2 col-sm-3 col-lg-3" style="background-color:#e2fbff;">
										<div class="row">
										  <div class="col-sm-12">
										  	<b>'.$value1['text'].'</b>
										  </div>
										  <div class="col-sm-6">
										  	<b>RUC:</b>'.$value1['CI_RUC'].'<br>
										  </div>
										  <div class="col-sm-6">
										  	<b>Item:</b>'.$value1['id'].'<br>
										  </div>
										  <div class="col-sm-12">
										  	'.$server.'	
										  </div>
										  <div class="col-sm-12">
										  	 <button type="button" class="btn btn-primary btn-xs" onclick="acceso_pagina(\''.$entidad.'\',\''.$value1['id'].'\')">Asignar acceso a paginas</button>
										  </div>
										</div>									
								</div>
            				<div class="col-xs-10 col-lg-9" style="overflow-x:scroll;">
              					<div class="row"><div class="col-sm-12">';


			   $tbl2.='

			   <table class="table-sm" style="margin-bottom:0px;font-size:11px;white-space: nowrap;"><tr>';
			foreach ($modulos as $key2 => $value2) {	
				$server = '';
				// if($value1['dbSQLSERVER']==0){$server = 'Disabled';}
				if($value1['ID_Empresa']!=0 && $value1['ID_Empresa']!=1 && $value1['ID_Empresa']!=''&& $value1['ID_Empresa']!=null) 
				{
					// print_r('diferente a prisma');
					if($value2['modulo']<90)
					{							
						$tbl2.='<td class="text-center" style="border: solid 1px; width: 50px;">
								'.$value2['aplicacion'].'</br>
				            <input type="checkbox" name="rbl_'.$value2['modulo'].'_'.$value1['id'].'" id="rbl_'.$value2['modulo'].'_'.$value1['id'].'" title="'.$value2['aplicacion'].'" onclick="listar_empresa_modificada(\''.$value1['id'].'\')" '.$server.' >
				        </td>';
				    }
				}else
				{

					$tbl2.='<td class="text-center" style="border: solid 1px; width: 50px;">
								'.$value2['aplicacion'].'</br>
				            <input type="checkbox" name="rbl_'.$value2['modulo'].'_'.$value1['id'].'" id="rbl_'.$value2['modulo'].'_'.$value1['id'].'" title="'.$value2['aplicacion'].'" onclick="listar_empresa_modificada(\''.$value1['id'].'\')" '.$server.' >
				        </td>';

				}

				
			}
			$tbl2.='</tr></table></div></div></div></div>';	

			// print_r($tbl2);die();
		}



		$usuarios = '';
		foreach ($usuarios_reg as $key => $value) {
			$usuarios.='<tr><td><b>'.$value['CI_NIC'].'</b></td><td>'.$value['Nombre_Usuario'].'</td><td>'.$value['Email'].'</td></tr>';
		}
		// print_r($tbl);die();
		// return utf8_decode($tbl);
		$tbl = array('tbl'=>$tbl2,'usuarios'=>$usuarios,'empresas'=>$empresas_unicas,'alerta'=>$mensaje);

		// print_r($tbl);die();
		return $tbl;
	}

	function modulos_usuario($entidad,$usuario)
	{
		$datos = $this->modelo->accesos_modulos($entidad,$usuario);
		$rbl = array();
		foreach ($datos as $key => $value) {
			// print_r($value);die();
			$rbl[] = 'rbl_'.$value['Modulo'].'_'.$value['Item'];
		}
		return $rbl;
	}
	function accesos_todos($parametros)
	{
		// print_r($parametros);die();
		if($parametros['item']!='' && $parametros['modulo']=='')
		{
			if( $parametros['check']=='true')
			{
			     $this->modelo->delete_modulos($parametros['entidad'],$parametros['item'],$parametros['usuario']);
			     $modulos = $this->modelo->modulos_todo();
			     $m = '';
			     foreach ($modulos as $key => $value) {
				     $m.=$value['modulo'].',';
			     }

			     $m = substr($m,0,-1);
			     $res = $this->modelo->guardar_acceso_empresa($m,$parametros['entidad'],$parametros['item'],$parametros['usuario']);
			     return $res;
		    }else
		    {
		    	// print_r('elimi');
			   $resp =   $this->modelo->delete_modulos($parametros['entidad'],$parametros['item'],$parametros['usuario']);
			    return $resp;
		    }
		}else
		{
			if($parametros['check']=='true')
			{
				 $res = $this->modelo->guardar_acceso_empresa($parametros['modulo'],$parametros['entidad'],$parametros['item'],$parametros['usuario']);
				 return $res;
			}else
			{
				// print_r('ss');die();
			   $resp = 	$this->modelo->delete_modulos($parametros['entidad'],$parametros['item'],$parametros['usuario'],$parametros['modulo']);
			   return $resp;

			}

		}
	}


	// function accesos_todos_empresa($parametros)
	// {
	// 	// print_r($parametros);die();
	// 	if($parametros['item']!='' && $parametros['modulo']=='')
	// 	{
	// 		if( $parametros['check']=='true')
	// 		{
	// 		     $this->modelo->delete_modulos($parametros['entidad'],$parametros['item'],$parametros['usuario']);
	// 		     $modulos = $this->modelo->modulos_todo();
	// 		     $m = '';
	// 		     foreach ($modulos as $key => $value) {
	// 			     $m.=$value['modulo'].',';
	// 		     }

	// 		     $m = substr($m,0,-1);
	// 		     $res = $this->modelo->guardar_acceso_empresa($m,$parametros['entidad'],$parametros['item'],$parametros['usuario']);
	// 		     return $res;
	// 	    }else
	// 	    {
	// 		   $resp =   $this->modelo->delete_modulos($parametros['entidad'],$parametros['item'],$parametros['usuario']);
	// 		    return $resp;
	// 	    }
	// 	}else
	// 	{
	// 		if($parametros['check']=='true')
	// 		{
	// 			 $res = $this->modelo->guardar_acceso_empresa($parametros['modulo'],$parametros['entidad'],$parametros['item'],$parametros['usuario']);
	// 			 return $res;
	// 		}else
	// 		{
	// 		   $resp = 	$this->modelo->delete_modulos($parametros['entidad'],$parametros['item'],$parametros['usuario'],$parametros['modulo']);
	// 		   return $resp;

	// 		}

	// 	}
	// }




  	function enviar_email($parametros)
  	{
  		$empresaGeneral = array_map(array($this, 'encode1'), $this->modelo->Empresa_data());
	  	$this->modelo->actualizar_correo($parametros['email'],$parametros['CI_usuario']);
	    $datos = $this->modelo->entidades_usuario($parametros['CI_usuario']);

	    // print_r($datos);die();

	  	$email_conexion = 'info@diskcoversystem.com'; //$empresaGeneral[0]['Email_Conexion'];
	    $email_pass =  'info2021DiskCover'; //$empresaGeneral[0]['Email_Contraseña'];
	    // print_r($empresaGeneral[0]);die();
	    //$Nombre_Usuario
	  	$correo_apooyo="credenciales@diskcoversystem.com"; //correo que saldra ala do del emisor
	  	$cuerpo_correo = '
<pre>
Este correo electronico fue generado automaticamente del Sistema Administrativo Financiero Contable DiskCover System a usted porque figura como correo electronico alternativo en nuestra base de datos.

A solicitud de El(a) Sr(a) '.$parametros['usuario'].' se envian sus credenciales de acceso:
</pre> 
<br>
<table>
<tr><td><b>Link:</b></td><td>https://erp.diskcoversystem.com</td></tr>
<tr><td><b>Nombre Usuario:</b></td><td>'.$datos[0]['Nombre_Usuario'].'</td></tr>
<tr><td><b>Usuario:</b></td><td>'.$datos[0]['Usuario'].'</td></tr>
<tr><td><b>Clave:</b></td><td>'.$datos[0]['Clave'].'</td></tr>
<tr><td><b>Email:</b></td><td>'.$datos[0]['Email'].'</td></tr>
</table>
A este usuario se asigno las siguientes Entidades: 
<br>
<table>
<tr><td width="30%"><b>Codigo</b></td><td width="50%"><b>Entidad</b></td></tr>
';
foreach ($datos as $value) {
	$cuerpo_correo .= '<tr><td>'.$value['id'].'</td><td>'.$value['text'].'</td></tr>';
}
$cuerpo_correo.='</table><br>';
$cuerpo_correo .= ' 
<pre>
Nosotros respetamos su privacidad y solamente se utiliza este correo electronico para mantenerlo informado sobre nuestras ofertas, promociones, claves de acceso y comunicados. No compartimos, publicamos o vendemos su informacion personal fuera de nuestra empresa.

Esta direccion de correo electronico no admite respuestas. En caso de requerir atencion personalizada por parte de un asesor de servicio al cliente; Usted podra solicitar ayuda mediante los canales de atencion al cliente oficiales que detallamos a continuacion:

Telefonos: (+593) 098-652-4396/099-965-4196/098-910-5300.
Emails: recepcion@diskcoversystem.com o prisma_net@hotmail.es
</pre>
<table width="100%">
<tr>
 <td align="center">
 <hr>
    SERVIRLES ES NUESTRO COMPROMISO, DISFRUTARLO ES EL SUYO
<hr>
    </td>
    </tr>
    <tr>   
 <td align="center">
    www.diskcoversystem.com
    </td>
    </tr>
     <tr>   
 <td align="center">
        QUITO - ECUADOR
    </td>
    </tr>
  </table>
';

	  	$titulo_correo = 'Credenciales de acceso al sistema DiskCover System';
	  	$archivos = false;
	  	$correo = $parametros['email'];
	  	// print_r($correo);die();
	  	// $resp = $this->modelo->ingresar_update($datos,'Clientes',$where);  	
	  	
	  	// if($resp==1)
	  	// {

	  	// print_r($empresaGeneral);die();
	  	if($this->email->enviar_credenciales($archivos,$correo,$cuerpo_correo,$titulo_correo,$correo_apooyo,'Credenciales de acceso al sistema DiskCover System',$email_conexion,$email_pass,$html=1,$empresaGeneral)==1){
	  		return 1;
	  	}else{
	  		// echo json_encode(-1);
	  		return -1;
	  	}
	  	// }else
	  	// {
	  		// return -1;
	  	// }
  	}

  	function confirmar_enviar_email($parametros)
  	{
  		$empresaGeneral = array_map(array($this, 'encode1'), $this->modelo->Empresa_data());
	  	$this->modelo->actualizar_correo($parametros['email'],$parametros['CI_usuario']);
	    $datos = $this->modelo->entidades_usuario($parametros['CI_usuario']);

	    // print_r($datos);die();

	  	$email_conexion = 'info@diskcoversystem.com'; //$empresaGeneral[0]['Email_Conexion'];
	    $email_pass =  'info2021DiskCover'; //$empresaGeneral[0]['Email_Contraseña'];
	    // print_r($empresaGeneral[0]);die();
	    //$Nombre_Usuario
	  	$correo_apooyo="credenciales@diskcoversystem.com"; //correo que saldra ala do del emisor
	  	$cuerpo_correo = '

Este correo electronico fue generado automaticamente del Sistema Administrativo Financiero Contable DiskCover System a usted porque figura como correo electronico alternativo en nuestra base de datos.

A solicitud de El(a) Sr(a) '.$parametros['usuario'].' se envian sus credenciales de acceso:
<br>
<b>Link:</b>https://erp.diskcoversystem.com<br>
<b>Nombre Usuario:</b></td><td>'.$datos[0]['Nombre_Usuario'].'<br>
<b>Usuario:</b></td><td>'.$datos[0]['Usuario'].'<br>
<b>Clave:</b></td><td>'.$datos[0]['Clave'].'<br>
<b>Email:</b></td><td>'.$datos[0]['Email'].'<br>
A este usuario se asigno las siguientes Entidades: 
<br>
<table>
<tr><td width="30%"><b>Codigo</b></td><td width="50%"><b>Entidad</b></td></tr>';
foreach ($datos as $value) {
	$cuerpo_correo .= '<tr><td>'.$value['id'].'</td><td>'.$value['text'].'</td></tr>';
}
$cuerpo_correo.='</table><br>';
$cuerpo_correo .= '
Nosotros respetamos su privacidad y solamente se utiliza este correo electronico para mantenerlo informado sobre nuestras ofertas, promociones, claves de acceso y comunicados. No compartimos, publicamos o vendemos su informacion personal fuera de nuestra empresa.

Esta direccion de correo electronico no admite respuestas. En caso de requerir atencion personalizada por parte de un asesor de servicio al cliente; Usted podra solicitar ayuda mediante los canales de atencion al cliente oficiales que detallamos a continuacion:
<div class="text-center">
    SERVIRLES ES NUESTRO COMPROMISO, DISFRUTARLO ES EL SUYO<br>
    www.diskcoversystem.com <br>
    QUITO - ECUADOR
</div>';

	  	return $cuerpo_correo;
  	}


  	function enviar_email_masivo($parametros)
  	{
  		$empresaGeneral = array_map(array($this, 'encode1'), $this->modelo->Empresa_data());
	  	$fallo = false;
	    $usuarios = $this->modelo->entidades_usuarios($parametros['ruc']);

	    // print_r($usuarios);die();
	    foreach ($usuarios as $datos) {
  			$datos0 = $this->modelo->entidades_usuario($datos['CI_NIC']);
		  	$email_conexion = 'info@diskcoversystem.com';
		    $email_pass =  'info2021DiskCover';
		  	$correo_apooyo="credenciales@diskcoversystem.com";
		  	$cuerpo_correo = '
<pre>
Este correo electronico fue generado automaticamente del Sistema Administrativo Financiero Contable DiskCover System a usted porque figura como correo electronico alternativo en nuestra base de datos.

A solicitud de El(a) Sr(a) '.$datos['Nombre_Usuario'].' se envian sus credenciales de acceso:
</pre> 
<br>
<table>
<tr><td><b>Link:</b></td><td>https://erp.diskcoversystem.com</td></tr>
<tr><td><b>Nombre Usuario:</b></td><td>'.$datos['Nombre_Usuario'].'</td></tr>
<tr><td><b>Usuario:</b></td><td>'.$datos['Usuario'].'</td></tr>
<tr><td><b>Clave:</b></td><td>'.$datos['Clave'].'</td></tr>
<tr><td><b>Email:</b></td><td>'.$datos['Email'].'</td></tr>
</table>
A este usuario se asigno las siguientes Entidades: 
<br>
<table>
<tr><td width="30%"><b>Codigo</b></td><td width="50%"><b>Entidad</b></td></tr>
';
foreach ($datos0 as $value) {
	$cuerpo_correo .= '<tr><td>'.$value['id'].'</td><td>'.mb_convert_encoding($value['text'], 'ISO-8859-1','UTF-8').'</td></tr>';
}
$cuerpo_correo.='</table><br>';
$cuerpo_correo .= ' 
<pre>
Nosotros respetamos su privacidad y solamente se utiliza este correo electronico para mantenerlo informado sobre nuestras ofertas, promociones, claves de acceso y comunicados. No compartimos, publicamos o vendemos su informacion personal fuera de nuestra empresa.

Esta direccion de correo electronico no admite respuestas. En caso de requerir atencion personalizada por parte de un asesor de servicio al cliente; Usted podra solicitar ayuda mediante los canales de atencion al cliente oficiales que detallamos a continuacion:

Telefonos: (+593) 098-652-4396/099-965-4196/098-910-5300.
Emails: recepcion@diskcoversystem.com o prisma_net@hotmail.es
</pre>
<table width="100%">
<tr>
 <td align="center">
 <hr>
    SERVIRLES ES NUESTRO COMPROMISO, DISFRUTARLO ES EL SUYO
<hr>
    </td>
    </tr>
    <tr>   
 <td align="center">
    www.diskcoversystem.com
    </td>
    </tr>
     <tr>   
 <td align="center">
        QUITO - ECUADOR
    </td>
    </tr>
  </table>
';

		  	$titulo_correo = 'Credenciales de acceso al sistema DiskCover System';
		  	$archivos = false;
		  	$correo = $datos['Email'];
		  	$resp=1;

		  	if($correo!='.'){
		  	$resp = $this->email->enviar_credenciales($archivos,$correo,$cuerpo_correo,$titulo_correo,$correo_apooyo,'Credenciales de acceso al sistema DiskCover System',$email_conexion,$email_pass,$html=1,$empresaGeneral);
		     }
		    if($resp!=1)
		    {
		    	$fallo = true;
		    }
	    }

	    if($fallo==true)
	    {
	    	return -2;
	    }else
	    {
	    	return 1;
	    }
		//echo json_encode(1);
  	}

  	function encode1($arr) {
    $new = array(); 
    foreach($arr as $key => $value) {
      if(!is_object($value))
      {
      	if($key=='Archivo_Foto')
      		{
      			if (!file_exists('../../img/img_estudiantes/'.$value)) 
      				{
      					$value='';
      					//$new[utf8_encode($key)] = utf8_encode($value);
      					$new[$key] = $value;
      				}
      		} 
         if($value == '.')
         {
         	$new[$key] = '';
         }else{
         	//$new[utf8_encode($key)] = utf8_encode($value);
         	$new[$key] = $value;
         }
      }else
        {
          //print_r($value);
          $new[$key] = $value->format('Y-m-d');          
        }
     }
     return $new;
    }


    function accesos_todos_empresa($parametros)
    {
    	// print_r($parametros);die();
    	$datos = $this->modelo->empresas($parametros['entidad']);

    	// print_r($datos);die();
    	foreach ($datos as $key => $value) {
    		$parametros2 = array('item'=>$value['id'],'modulo'=>$parametros['modulo'],'entidad'=>$parametros['entidad'],'usuario'=>$parametros['usuario'],'check'=>$parametros['check']);

    		// print_r($parametros2);die();
    		$this->accesos_todos($parametros2);
    	}

    	return 1;
    }

    function paginas_acceso($parametros)
    {
    	// print_r($parametros);die();
    	$op='<div class="col-sm-12">
					<ul class="nav nav-tabs">';
				$modulos = $this->modelo->todos_modulos($parametros['entidad'],$parametros['item'],$parametros['usuario']);
				foreach ($modulos as $key => $value) {
					$ver = '';
					if($key==0){ $ver = 'active';}
					$op.='<li class="'.$ver.'"><a data-toggle="tab" href="#'.str_replace(' ','_',$value['aplicacion']).'">'.$value['aplicacion'].'</a></li>';
				}
				$encontrado = 0;
				$op.='</ul>
					  <div class="tab-content">';
				foreach ($modulos as $key => $value) {
					$ver = '';
					if($key==0){$ver = 'in active';}
					$op.='<div id="'.str_replace(' ','_',$value['aplicacion']).'" class="tab-pane fade '.$ver.'"><br>';
					$pag = $this->modelo->paginas($value['modulo']);
					$pag_select = pagina_acceso_hijos($parametros['usuario'],$parametros['entidad'],$parametros['item'],$value['modulo']);
					foreach ($pag as $key => $value1) {
						$encontrado = 0;
						// print_r($pag);
						// print_r($pag_select);die();
						if(count($pag_select)>0)
						{
							foreach ($pag_select as $key2 => $value2) {
								if($value1['ID']==$value2['Pagina'])
								{
									$op.='<label><input type="checkbox" checked id="rbl_'.$value1['ID'].'" onclick="guardar_pag(\''.$parametros['entidad'].'\',\''.$parametros['item'].'\',\''.$parametros['usuario'].'\',\''.$value1['ID'].'\',\''.$value['modulo'].'\')"> '.$value1['descripcionMenu'].'</label><br>';
									$encontrado = 1;
								}
							}

						}
						if($encontrado==0)
						{
						$op.='<label><input type="checkbox" id="rbl_'.$value1['ID'].'" onclick="guardar_pag(\''.$parametros['entidad'].'\',\''.$parametros['item'].'\',\''.$parametros['usuario'].'\',\''.$value1['ID'].'\',\''.$value['modulo'].'\')"> '.$value1['descripcionMenu'].'</label><br>';
						}
					}
					$op.='</div>';
				}
				$op.='</div>';

				return $op;
    }

    function savepag($parametros)
    {
    	if($parametros['estado']=='true')
    	{
    		$existe = $this->modelo->existe_acceso_pag($parametros['entidad'],$parametros['usuario'],$parametros['modulo'],$parametros['empresa'],$parametros['pagina']);
    		if(count($existe)==0)
    		{
	    		$datos[0]['campo'] = 'ID_Empresa';
	    		$datos[0]['dato']  = $parametros['entidad'];
	    		$datos[1]['campo'] = 'CI_NIC';
	    		$datos[1]['dato']  = $parametros['usuario'];
	    		$datos[1]['tipo']  = 'string';
	    		$datos[2]['campo'] = 'Modulo';
	    		$datos[2]['dato']  = $parametros['modulo'];
	    		$datos[2]['tipo']  = 'string';
	    		$datos[3]['campo'] = 'Item';
	    		$datos[3]['dato']  = generaCeros($parametros['empresa'],3);
	    		$datos[3]['tipo']  = 'string';
	    		$datos[4]['campo'] = 'Pagina';
	    		$datos[4]['dato']  = $parametros['pagina'];
	    		$this->modelo->add_accesos('acceso_empresas',$datos);
    		}
    	}else
    	{
    		$this->modelo->delete_acceso($parametros['entidad'],$parametros['usuario'],$parametros['modulo'],$parametros['empresa'],$parametros['pagina']);
    		// elimina el acceso
    	}
    	print_r($parametros);die();
    }


    function buscarPuntoVenta($parametros)
    {  	
		$sql = "SELECT * FROM Catalogo_Lineas where RUC_Establecimiento = '".$parametros['ci']."001' ";
		// print_r($parametros);die();
		$datos = $this->modelo->ejecutar_datos_terceros($sql,$parametros['entidad'],$parametros['item']);
		$datoPuntoVenta = array();
		if(count($datos)>0)
		{
			$estab = substr($datos[0]['Serie'],0,3) ;
			$punto = substr($datos[0]['Serie'],3,6) ;

			$datoPuntoVenta = array('estab'=>$estab,
				'punto'=>$punto,
				'correo'=>$datos[0]['Email_Establecimiento'],
				'direccion'=>$datos[0]['Direccion_Establecimiento'],'telefono'=>$datos[0]['Telefono_Estab'],'logo'=>$datos[0]['Logo_Tipo_Estab'],'id'=>$datos[0]['ID']);
		}
		return $datoPuntoVenta;
	}
}
?>