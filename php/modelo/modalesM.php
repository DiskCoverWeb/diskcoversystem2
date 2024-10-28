<?php 
include(dirname(__DIR__,1).'/funciones/funciones.php');
require_once(dirname(__DIR__,2)."/lib/fpdf/reporte_de.php");
require_once("../db/db1.php");

/**
 * 
 */
class modalesM
{
	private $db;	
	function __construct()
	{
		$this->db = new db();
	}

	function buscar_cliente($ci=false,$nombre=false,$id=false,$exacto=false)
	{
		$sql="SELECT ID,Cliente AS nombre, CI_RUC as id,TD, email,Direccion,Telefono,Codigo,Grupo,Ciudad,Prov,DirNumero,ID,FA,Cod_Ejec,Actividad
		    FROM Clientes  C
		    WHERE T <> '.' ";

		    if($id)
		    {
		    	$sql.=" AND ID='".$id."'";
		    }

		    if($nombre)
		    {
		    	if($exacto)
		    	{
		    		$sql.=" AND  Cliente = '".$nombre."' ";
		    	}else
		    	{
		    		$sql.=" AND  Cliente LIKE '%".$nombre."%' ";		    		
		    	}
		    }
		    if($ci)
		    {
		    	if($exacto)
		    	{
		    		$sql.=" AND CI_RUC = '".$ci."' ";
		    	}else{
		    		$sql.=" AND CI_RUC LIKE '".$ci."%' ";
		    	}
		    }	
		$sql.=" ORDER BY Cliente OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY;";

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}


function DLGasto($SubCta,$query=false)
{ 
    $sql = "SELECT Codigo+'  .  '+Cuenta As Nombre_Cta, TC, Codigo
            FROM Catalogo_Cuentas
            WHERE Item = '" .$_SESSION['INGRESO']['item']."'
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND DG = 'D' ";
            if($query)
            {
            	$sql.=" AND Cuenta LIKE '%".$query."%'";
            }
	  if($SubCta == "C"){ $sql.=" AND TC IN ('CC','I') "; }else{ $sql.=" AND TC IN ('CC','G') ";}
	  $sql.=" ORDER BY Codigo ";
	  $datos = $this->db->datos($sql);
	  return $datos;
 }

 function DLSubModulo($SubCta,$query=false)
 {  
   $sql= "SELECT Detalle, Codigo 
        FROM Catalogo_SubCtas 
        WHERE Item = '".$_SESSION['INGRESO']['item']."' 
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
        if($query)
        {
        	$sql.=" AND Detalle LIKE '%".$query."%'";
        }
	  if($SubCta == "C" ){ $sql.=" AND TC IN ('CC','I') "; }else{ $sql.=" AND TC IN ('CC','G') ";}
	  $sql.=" ORDER BY Detalle ";
	  $datos = $this->db->datos($sql);
	  return $datos;
}

function DLCxCxP($SubCta,$query=false)
{	  
  $sql = "SELECT Codigo+'  .  '+Cuenta As Nombre_Cta, Codigo
       	 FROM Catalogo_Cuentas
       	 WHERE Item = '".$_SESSION['INGRESO']['item']."'
       	 AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
       	 AND DG = 'D'
       	 AND TC = '".$SubCta."'";
       	 if($query)
        {
        	$sql.=" AND Cuenta LIKE '%".$query."%'";
        }
       	 $sql.=" ORDER BY Codigo ";
       	   $datos = $this->db->datos($sql);
	  return $datos;
}


 function buscar_cta($cta)
 {
 	$sql = "SELECT * 
 			FROM Ctas_Proceso 
 			WHERE Detalle = '".$cta."' 
 			AND Periodo = '".$_SESSION['INGRESO']["periodo"]."' 
 			AND Item = '".$_SESSION['INGRESO']['item']."'";
 	$datos = $this->db->datos($sql);
	return $datos;
 }

     function LeerCta($CodigoCta)
	{

		$Cuenta = G_NINGUNO;
		$Codigo = G_NINGUNO;
		$TipoCta = "G";
		$SubCta = "N";
		$TipoPago = "01";
		$Moneda_US = False;
		$datos= array();
		if (strlen(substr($CodigoCta, 1, 1)) >= 1){
			$sql = "SELECT Codigo, Cuenta, TC, ME, DG, Tipo_Pago
              FROM Catalogo_Cuentas 
              WHERE Codigo = '" .$CodigoCta. "'
              AND Item = '".$_SESSION['INGRESO']['item']."' 
              AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
          $datos = $this->db->datos($sql);
		  return $datos;
       }

       return $datos;
    }
	function catalogo_Cxcxp($Codigo)
	{
		$sql="SELECT * FROM Catalogo_CxCxP WHERE Codigo = '".$Codigo."' AND TC='P' AND Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
		
	   $datos = $this->db->datos($sql);
       return $datos;

	}

	function reporte_retencion($numero,$TP,$retencion,$serie,$imp=0)
	{
		$datos = array();
		$detalle = array(); 
		$cliente = array();
		$TFA = array();


		$sql2 = "SELECT * FROM lista_tipo_contribuyente WHERE RUC = '".$_SESSION['INGRESO']['RUC']."'";
	    $tipo_con = $this->db->datos($sql2, 'MYSQL');
		

		$sql = "SELECT C.Cliente,C.CI_RUC,C.TD,C.Direccion,C.Email,C.Ciudad,C.DirNumero,C.Telefono,TC.* 
        FROM Trans_Compras As TC,Clientes As C 
        WHERE TC.Item = '".$_SESSION['INGRESO']['item']."'
        AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."'
        AND TC.Numero = ".$numero." 
        AND TC.TP = '".$TP."' 
        AND TC.SecRetencion = ".$retencion."
        AND TC.Serie_Retencion = '".$serie."' 
        AND TC.IdProv = C.Codigo 
        ORDER BY Cta_Servicio,Cta_Bienes";
	   $datos = $this->db->datos($sql);

	   // print_r($datos);die();
	   if(count($datos)>0)
	   {
	   	 $TFA['Fecha'] = $datos[0]["Fecha"];
         $TFA['Cliente'] = $datos[0]["Cliente"];
         $TFA['Razon_Social'] = $datos[0]["Cliente"];
         $TFA['CI_RUC'] = $datos[0]["CI_RUC"];
         $TFA['RUC_CI'] = $datos[0]["CI_RUC"];
         $TFA['DireccionC'] = $datos[0]["Direccion"];
        // 'TFA.Serie_R
        // 'TFA.Retencion
         $TFA['Fecha_Aut'] = $datos[0]["Fecha_Aut"];
         $TFA['Hora'] = $datos[0]["Hora_Aut"];
         $TFA['Autorizacion_R'] = $datos[0]["AutRetencion"];
         $TFA['ClaveAcceso'] = $datos[0]["Clave_Acceso"];
         $TFA['Serie'] = $datos[0]["Establecimiento"].$datos[0]["PuntoEmision"];
         $TFA['Factura'] = $datos[0]["Secuencial"];
         $TFA['Fecha'] = $datos[0]["Fecha"];
         $TFA['Tipo_Comp'] = strval($datos[0]["TipoComprobante"]);
         $FechaTexto = $datos[0]["Fecha"]->format('Y-m-d');
         $EjercicioFiscal = strval($datos[0]["Fecha"]->format('Y'));
         $Porc_IVA = Validar_Porc_IVA($datos[0]["Fecha"]->format('Y-m-d'));
         $ConsultarDetalle = True;
	   }
	  if(count($datos)>0 && count($tipo_con)>0)
	  {
	    $TFA['Tipo_contribuyente'] = $tipo_con;
	  }

	   // print_r($TFA);die();


   // 'Determinamos el Tipo de Comprobante
    $sql = "SELECT Tipo_Comprobante_Codigo, Descripcion 
        FROM Tipo_Comprobante 
        WHERE TC = 'TDC' 
        AND Tipo_Comprobante_Codigo = ".intval($TFA['Tipo_Comp']);
     $datos1 = $this->db->datos($sql);
     if(count($datos1)>0)
     {
     	$TFA['Tipo_Comp'] = $datos1[0]["Descripcion"];
     }
   

 	// print_r($TFA);die();
    // 'Listar las Retenciones de la Fuente
    $sql = "SELECT TIV.Concepto,R.* 
    	FROM Trans_Air As R,Tipo_Concepto_Retencion As TIV 
    	WHERE R.Item = '".$_SESSION['INGRESO']['item']."' 
    	AND R.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
    	AND R.Numero = ".$numero." 
    	AND R.TP = '".$TP."' 
    	AND R.SecRetencion = ".$retencion." 
    	AND R.EstabRetencion = '".substr($serie, 0, 3)."' 
    	AND R.PtoEmiRetencion = '".substr($serie, 3, 6)."' 
    	AND R.Tipo_Trans IN ('C','I') 
    	AND TIV.Fecha_Inicio <= '".BuscarFecha($FechaTexto)."' 
    	AND TIV.Fecha_Final >= '".BuscarFecha($FechaTexto)."'
    	AND R.CodRet = TIV.Codigo 
    	ORDER BY R.Cta_Retencion ";
    	$datos2 = $this->db->datos($sql);
   // 'Encabezado Factura

 	// print_r($TFA);
 	// print_r($datos2);
 	// die();    

	  imprimirDocEle_ret($datos,$datos2,'Retencion',$imp);

	}	

	function GetMedidor($parametros)
	{
		extract($parametros);
        $sql = "SELECT Codigo 
        FROM Clientes_Datos_Extras ".
        "WHERE Cuenta_No = '" . $Cuenta_No . "' ".
        "AND Item = '" . $_SESSION['INGRESO']['item'] . "' ".
        "AND Tipo_Dato = 'MEDIDOR' ";
        return $this->db->datos($sql);
	}	

	function AddMedidor($parametros)
	{
		extract($parametros);
        SetAdoAddNew("Clientes_Datos_Extras");
        SetAdoFields("T", G_NORMAL);
        SetAdoFields("Codigo", $TxtCodigo);
        SetAdoFields("Tipo_Dato", "MEDIDOR");
        SetAdoFields("Cuenta_No", $Cuenta_No);
        SetAdoFields("Acreditacion", $LecturaInicial);
        return SetAdoUpdate();
	}	

	function DeleteMedidor($parametros)
	{
		extract($parametros);
        $sSQL = "DELETE * ".
            "FROM Clientes_Datos_Extras ".
            "WHERE Codigo = '" . $TxtCodigo . "' ".
            "AND Item = '" . $_SESSION['INGRESO']['item'] . "' ".
            "AND Cuenta_No = '" . $Cuenta_No . "' ".
            "AND Tipo_Dato = 'MEDIDOR' ";
        return Ejecutar_SQL_SP($sSQL);
	}

	function Listar_Medidores($codigoDelCliente) {
	    $cMedidor = array();

	    if ($codigoDelCliente != G_NINGUNO && strlen($codigoDelCliente) > 1) {
	        $sSQL = "SELECT Cuenta_No " .
	                "FROM Clientes_Datos_Extras " .
	                "WHERE Codigo = '" . $codigoDelCliente . "' " .
            		"AND Item = '" . $_SESSION['INGRESO']['item'] . "' ".
	                "AND Tipo_Dato = 'MEDIDOR' " .
	                "AND T = '".G_NORMAL."' " .
	                "ORDER BY Fecha_Registro";
	        $cMedidor = $this->db->datos($sSQL);
	        if (count($cMedidor) <= 0) {
	            $cMedidor[]['Cuenta_No'] = G_NINGUNO;
	        }
	    } else {
	        $cMedidor[]['Cuenta_No'] = G_NINGUNO;
	    }
	    
	    return $cMedidor;
	}


	function editar_cliente($parametro)
	{
		$fa = 1;
		if($parametro['rbl']=='false')
		{
			$fa = 0;
		}
		// print_r($parametros);die();
		$sql = "UPDATE Clientes SET Cliente = '".$parametro['nombrec']."',Direccion = '".$parametro['direccion']."',Telefono = '".$parametro['telefono']."',DirNumero = '".$parametro['nv']."',Email='".$parametro['email']."',Prov = '".$parametro['prov']."',Grupo='".$parametro['grupo']."',Ciudad = '".$parametro['ciu']."', FA = ".$fa." WHERE ID = '".$parametro['txt_id']."'";

		// print_r($sql);die();
		return $this->db->String_Sql($sql);
	}

	function editar_Catalogo_CxCxP($parametros)
	{
		$sql = "UPDATE Catalogo_CxCxP SET 
				TC = 'P',
				Codigo = '".$parametro['codigoc']."',
				Cta = '".$_SESSION['SETEOS']['Cta_Proveedores']."',
				Item = '".$_SESSION['INGRESO']['item']."',
				Periodo = '".$_SESSION['INGRESO']['periodo']."'
				where Codigo = '".$parametro['codigoc']."' ";

		return $this->db->String_Sql($sql);
	}

	
	
	function FInfoError($ejecutar = true)
	{
		$cSQL = "SELECT Texto
				FROM Tabla_Temporal
				WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
				AND Modulo = '" . $_SESSION['INGRESO']['modulo_'] . "'
				AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'
				ORDER BY ID";
		if(!$ejecutar){
			return $cSQL;
		}
		return $this->db->datos($cSQL);
	}
	
	function EliminarTablaTemporal()
	{
		$sSQL = "DELETE * FROM Tabla_Temporal 
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
			AND Modulo = '" . $_SESSION['INGRESO']['modulo_'] . "'
			AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'";
    	return Ejecutar_SQL_SP($sSQL);
	}

	function tipo_proveedor($TP='')
	{
		$sql = "SELECT TP, Proceso,ID
				FROM Catalogo_Proceso
				WHERE Item ='".$_SESSION['INGRESO']['item']."' 
				AND Nivel = 98";

		if($TP!=''){
			$sql .= " AND TP = '$TP' ";
		}

		$sql .= " ORDER BY Proceso";

		return $this->db->datos($sql);
	}
	function sucursales($query = false,$codigo=false,$id=false)
	{
		$sql = "SELECT ID,Codigo, Direccion, TP
			FROM Clientes_Datos_Extras 
			WHERE Item = '".$_SESSION['INGRESO']['item']."'";
			if($codigo)
			{  
				$sql.=" AND Codigo = '".$codigo."' ";
			} 
			if($id)
			{
				$sql.= " AND ID = '".$id."' ";
			}
			$sql.=" AND Tipo_Dato = 'TIPO_PROV' 
			ORDER BY Direccion, Fecha_Registro DESC";

		return $this->db->datos($sql);

	}
	function delete_sucursal($id)
	{
		$sql = "DELETE FROM Clientes_Datos_Extras WHERE ID ='".$id."'";
		return $this->db->String_Sql($sql);
	}

	function ListarCuenta($parametros){
		$Cliente = array();
		$lista = array();
		$sql = "SELECT TOP 50 * 
				FROM Clientes
				WHERE Cliente = '".$parametros['cliente']."'";
		$AdoListCtas = $this->db->datos($sql);
		$Codigo = "";
		if(count($AdoListCtas)>0)
		{
			array_push($Cliente, $AdoListCtas[0]);
			$Codigo = $AdoListCtas[0]['Codigo'];
			$sql = "SELECT Tipo_Dato, Fecha_Registro, Direccion, Telefono, Ciudad, Descuento, Cuenta_No
					FROM Clientes_Datos_Extras
					WHERE Codigo = '".$Codigo."'
					AND Item = '".$_SESSION['INGRESO']['item']."'
					AND Tipo_Dato IN ('DIRECCION','LIBRETAS')
					ORDER BY Tipo_Dato, Fecha_Registro DESC, Cuenta_No";
			$AdoAux = $this->db->datos($sql);
			if(count($AdoAux) > 0){
				foreach ($AdoAux as $key => $value) {
					if($value["Tipo_Dato"] != "DIRECCION"){
						array_push($lista, "Cta. Ahorro No. ".$value["Cuenta_No"]);
					}
				}
			}
			$sql = "SELECT TC, Cta
					FROM Catalogo_CxCxP
					Where Codigo = '".$Codigo."'
					AND Item = '".$_SESSION['INGRESO']['item']."'
					AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
					ORDER BY TC, Cta";
			$AdoAux = $this->db->datos($sql);
			if(count($AdoAux) > 0){
				foreach ($AdoAux as $key => $value) {
					array_push($lista, "Cta. Contable (" . $value["TC"] . "): " . $value["Cta"]);
				}
			}

			$sql = "SELECT Ejecutivo
					FROM Catalogo_Rol_Pagos
					WHERE Codigo = '".$Codigo."'
					AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
					AND Item = '".$_SESSION['INGRESO']['item']."'";
			$AdoAux = $this->db->datos($sql);
			if(count($AdoAux) > 0){
				array_push($lista, "Asignado a Rol de Pago");
			}

			$sql = "SELECT TP, Fecha, Credito_No
					FROM Prestamos
					WHERE Cuenta_No = '".$Codigo."'
					AND TP = 'SUSC'
					ORDER BY Fecha,Credito_No";
			$AdoAux = $this->db->datos($sql);
			if(count($AdoAux) > 0){
				foreach ($AdoAux as $key => $value) {
					array_push($lista, "Suscripción: [" . $value["Fecha"] . "] " . $value["Credito_No"]);
				}
			}

			$sql = "SELECT TP, Fecha, Credito_No
					FROM Prestamos
					WHERE Cuenta_No = '".$Codigo."'
					AND TP <> 'SUSC'
					ORDER BY Fecha,Credito_No";
			$AdoAux = $this->db->datos($sql);
			if(count($AdoAux) > 0){
				foreach ($AdoAux as $key => $value) {
					array_push($lista, $value['TP'] . " [" . $value['Fecha'] . "] " . $value['Credito_No']);
				}
			}
		}

		
		return array("Cliente" => $Cliente, "Lista" => $lista);
	}
}
?>