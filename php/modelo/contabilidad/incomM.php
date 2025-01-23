<?php 

@session_start(); 
include_once(dirname(__DIR__,2).'/db/db1.php');
include_once(dirname(__DIR__,3).'/lib/TCPDF/Reportes/reportes_all.php');

if(!include_once(dirname(__DIR__,2).'/funciones/funciones.php'))
{
	include_once(dirname(__DIR__,2).'/funciones/funciones.php');
}



/**
 * 
 */
class incomM
{
	
	private $conn ;
	private $reportes;
	function __construct()
	{
	   $this->conn = new db();
	   $this->reportes = new reportes_all();
	}

	function beneficiarios($query)
	{
		$sql="SELECT TOP 50 Cliente AS nombre, CI_RUC as id, email,Codigo
		   FROM Clientes 
		   WHERE  T='N'";
		   if($query != '' and !is_numeric($query))
		   {
		   	$sql.=" AND Cliente LIKE '%".$query."%'";
		   }else
		   {
		   		$sql.=" AND CI_RUC like '".$query."%'";
		   }
		  $sql.=" ORDER BY Cliente";
		  // print_r($sql);die();
		 $result = $this->conn->datos($sql);
	   return $result;
	}

	function beneficiarios_c($query)
	{
		$sql="SELECT TOP 25 Cliente AS nombre, CI_RUC as id, email
		   FROM Clientes 
		   WHERE T <> '.' ";
		   if($query != '')
		   {
		   if(is_numeric($query))
		   	{
		   		$sql.=" AND CI_RUC='".$query."'";
		   	}else
		   	{
		   		$sql.=" AND Cliente like '%".$query."%'";
		   	}
		   }
		  $sql.=" ORDER BY Cliente";
		   $result = $this->conn->datos($sql);
	   return $result;
	}

	function beneficiarios_pro($query)
	{
		$sql="SELECT TOP 25 Cliente AS nombre, CI_RUC as id, email,TD,CI_RUC,Codigo
		   FROM Clientes 
		   WHERE T <> '.' ";
		   if($query != '')
		   {
		   	if(is_numeric($query))
		   	{
		   		$sql.=" AND CI_RUC='".$query."'";
		   	}else
		   	{
		   		$sql.=" AND Cliente like '%".$query."%'";
		   	}
		   }
		  $sql.=" ORDER BY Cliente";

		  // print_r($sql);die();
		   $result = $this->conn->datos($sql);
	   return $result;
	}

	function cuentas_efectivo($query)
	{
		$sql="SELECT Codigo,Codigo+' '+Cuenta  as 'cuenta' 
		FROM Catalogo_Cuentas
		WHERE TC = 'CJ' AND DG = 'D' ";
		if($query)
		{
			$sql.= " AND Codigo+' '+Cuenta LIKE '%".$query."%' ";
		}
		$sql.="AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item = '".$_SESSION['INGRESO']['item']."'  ORDER BY Cuenta";
		// print_r($sql);die();
		 $result = $this->conn->datos($sql);
	   return $result;
	}

	function cuentas_banco($query)
	{
		$sql="SELECT Codigo,Codigo+' '+Cuenta  as 'cuenta' 
		FROM Catalogo_Cuentas
		WHERE TC ='BA' AND DG ='D' ";
		if($query)
		{
			$sql.= " AND Codigo+' '+Cuenta LIKE '%".$query."%' ";
		}
		$sql.="AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item = '".$_SESSION['INGRESO']['item']."'  ORDER BY Cuenta";
		// print_r($sql);die();
		  $result = $this->conn->datos($sql);
	   return $result;
	}

	function cuentas_todos($query=false,$codigo=false,$clave=false,$tipocta=false)
	{
		$sql="SELECT Codigo+Space(19-LEN(Codigo))+'   '+TC+Space(3-LEN(TC))+'   '+cast( Clave as varchar(5))+'  '
					+Space(5-LEN(cast( Clave as varchar(5))))+'  -'+
					Cuenta As Nombre_Cuenta,Codigo,Cuenta,TC 
			   FROM Catalogo_Cuentas 
			   WHERE DG = 'D' 
			   AND Cuenta <> '".$_SESSION['INGRESO']['ninguno']."' AND Item = '".$_SESSION['INGRESO']['item']."'
			   AND Periodo = '".$_SESSION['INGRESO']['periodo']."'  ";
			   if($query)
			   {
			   	$sql.="AND Codigo+Space(19-LEN(Codigo))+' '+TC+Space(3-LEN(TC))+' '+cast( Clave as varchar(5))+' '+Space(5-LEN(cast( Clave as varchar(5))))+' '+Cuenta LIKE '%".$query."%'";			
			   }
			   if($codigo)
			   {
			   	$sql.=" AND Codigo like '".$codigo."%'";
			   }
			   if($clave)
			   {
			   	$sql.=" AND Clave = '".$clave."'";
			   }
		  
		   	if($tipocta)
		   	{
		   		$sql.=" AND TC = '".$tipocta."'";
		   	}
		   	$sql.="ORDER BY Codigo ASC";
			   	// print_r($query);print_r($tipo);print_r($tipocta);
		// print_r($sql);die();
		 $result = $this->conn->datos($sql);
	   return $result;
	}

	function cargar_asientosB()
	{
		$sql="SELECT CTA_BANCO, BANCO, CHEQ_DEP, EFECTIVIZAR, VALOR, ME, T_No, Item, CodigoU
			FROM Asiento_B
			WHERE Item = '".$_SESSION['INGRESO']['item']."' 
			AND CodigoU = '".$_SESSION['INGRESO']['Id']."' 
			AND T_No = '".$_SESSION['INGRESO']['modulo_']."'";
		// print_r($sql);die();
		  $result = $this->conn->datos($sql);
	   return $result;
	}


	function delete_asientoB($cta,$cheq)
	{
		$cid = $this->conn;
		$sql="Delete from Asiento_B ".
		   "WHERE Item = '".$_SESSION['INGRESO']['item']."' ".
		   "AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ".
		   "AND CTA_BANCO='".$cta."' ".
		   "AND CHEQ_DEP='".$cheq."' ";
		// print_r($sql);die();
         return  $this->conn->String_Sql($sql);

	}

	function delete_asientoBTodos()
	{
		$sql="Delete from Asiento_B";
		// print_r($sql);die();
		  $result = $this->conn->String_Sql($sql);
	   return $result;
	}

	function ListarAsientoTemSQL($ti,$Opcb,$b,$ch)
	{
		//opciones para generar consultas (asientos bancos)
		$cid = $this->conn;
		if($Opcb=='1')
		{
			$sql="SELECT CTA_BANCO, BANCO, CHEQ_DEP, EFECTIVIZAR, VALOR, ME, T_No, Item, CodigoU
			FROM Asiento_B
			WHERE 
			Item = '".$_SESSION['INGRESO']['item']."' 
			AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
			$sql.=" ORDER BY CTA_BANCO ";
			$ta='Asiento_B';
		}
		else
		{
			$sql="SELECT A_No,CODIGO,CUENTA,PARCIAL_ME,DEBE ,HABER ,CHEQ_DEP,DETALLE
				FROM Asiento
				WHERE
					T_No=".$_SESSION['INGRESO']['modulo_']." AND
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
			
			$sql=$sql." ORDER BY A_No ";
			$ta='Asiento';
		}
		$camne=array();
		$botones[0] = array('boton'=>'validarc', 'icono'=>'<i class="bx bx-trash bx-xs p-0 m-0"></i>', 'tipo'=>'danger', 'id'=>'CTA_BANCO,CHEQ_DEP' );

	    $tbl = grilla_generica_new($sql);
			if(!empty($tbl['data'])){ 
        foreach ($tbl['data'] as &$fila){ 
          $ids = explode(',', $botones[0]['id']); // Extrae las claves de $fila que se usarán

          // Mapeamos cada ID correctamente con su valor en $fila
          $parametros = array_map(function($id) use ($fila) {
              return $fila[$id] ?? ''; // Asigna el valor correspondiente o una cadena vacía si no existe
          }, $ids);
          $fila[] = '<button type="button" class="btn btn-sm py-0 px-1 btn-'.$botones[0]['tipo'].'"
                      onclick="'.$botones[0]['boton']. '(\''.implode("', '", $parametros). '\')" 
                      title="'.$botones[0]['boton'].'">'.
                      $botones[0]['icono'].
                      '</button>';
        }
      }
		return $tbl;




		// $tabla =  grilla_generica($stmt,$ti,NULL,$b,$ch,$ta);
		// return $tabla;
	}

	function ListarAsientoScSQL($ti,$Opcb,$b,$ch)
	{
		$cid = $this->conn;
		//opciones para generar consultas (asientos bancos)
		$sql="SELECT Codigo, Beneficiario, Factura, Prima, DH, Valor, Valor_ME, Detalle_SubCta,T_No, SC_No,Item, CodigoU
			FROM Asiento_SC
			WHERE 
				Item = '".$_SESSION['INGRESO']['item']."' 
				AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
		//	$stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			else
			{
				$camne=array();
				return grilla_generica($stmt,null,NULL,'1','8,9,10,11,clave1','asi_sc');
			}
	}

	function DG_asientos()
	{
		$cid = $this->conn;
	  $sql = "SELECT *
       FROM Asiento
       WHERE Item = '".$_SESSION['INGRESO']['item']. "'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND T_No = ".$_SESSION['INGRESO']['modulo_']." ";
       $botones[0] = array('boton'=>'eliminar', 'icono'=>'<i class="bx bx-trash bx-xs p-0 m-0"></i>', 'tipo'=>'danger', 'id'=>'CODIGO,asiento,ID' );
       $tbl = grilla_generica_new($sql);
       if(!empty($tbl['data'])){ 
        foreach ($tbl['data'] as &$fila){ 
          $ids = explode(',', $botones[0]['id']);
          $parametros = array_map(fn($id, $index) => $index === 0 ? ($fila[$id] ?? ''): $id,
          $ids, 
          array_keys($ids));
          $fila[] = '<button type="button" class="btn btn-sm py-0 px-1 btn-'.$botones[0]['tipo'].'"
                      onclick="'.$botones[0]['boton']. '(\''.implode("', '", $parametros). '\')" 
                      title="'.$botones[0]['boton'].'">'.
                      $botones[0]['icono'].
                      '</button>';
        }
      }
			 // print_r($tbl);die();
		return $tbl;
  }

    function DG_asientos_SC()
    {
    	$cid = $this->conn;
       $sql = "SELECT *
       FROM Asiento_SC
       WHERE Item = '".$_SESSION['INGRESO']['item']. "'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND T_No = ".$_SESSION['INGRESO']['modulo_']." ";

        $botones[0] = array('boton'=>'eliminar','icono'=>'<i class="bx bx-trash bx-xs p-0 m-0"></i>', 'tipo'=>'danger', 'id'=>'Codigo,asientoSC' );

        $tbl = grilla_generica_new($sql);
        if(!empty($tbl['data'])){ 
          foreach ($tbl['data'] as &$fila){ 
            $ids = explode(',', $botones[0]['id']);
            $parametros = array_map(fn($id, $index) => $index === 0 ? ($fila[$id] ?? ''): $id,
            $ids, 
            array_keys($ids));
            $fila[] = '<button type="button" class="btn btn-sm py-0 px-1 btn-'.$botones[0]['tipo'].'"
                        onclick="'.$botones[0]['boton']. '(\''.implode("', '", $parametros). '\')" 
                        title="'.$botones[0]['boton'].'">'.
                        $botones[0]['icono'].
                        '</button>';
          }
        }
			 // print_r($tbl);die();
		return $tbl;
    }

    function DG_asientoB()
    {
    	$cid = $this->conn;
       $sql = "SELECT *
       FROM Asiento_B
       WHERE Item = '".$_SESSION['INGRESO']['item']. "'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND T_No = ".$_SESSION['INGRESO']['modulo_']." ";
      $stmt = $this->conn->devolver_stmt($sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			else
			{
				$camne=array();
				// return grilla_generica($stmt,null,NULL,'1','8,9,10,11,clave1','asi_sc');
				 $button[0] = array('nombre'=>'eliminar','tipo'=>'danger','icon'=>'fa fa-trash','dato'=>array('0,asientoB'));
				 grilla_generica($stmt,null,null,1,null,null,null,false,$button);
			}
    }

    function DG_asientoR()
    {
       $sql = "SELECT *
       FROM Asiento_Air
       WHERE Item = '".$_SESSION['INGRESO']['item']. "'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND T_No = ".$_SESSION['INGRESO']['modulo_']." ";
       $datos = $this->conn->datos($sql);

       $botones[0] = array('boton'=>'eliminar', 'icono'=>'<i class="bx bx-trash bx-xs p-0 m-0"></i>', 'tipo'=>'danger', 'id'=>'CodRet,air');
	   $tbl = grilla_generica_new($sql);
     if(!empty($tbl['data'])){ 
      foreach ($tbl['data'] as &$fila){ 
        $ids = explode(',', $botones[0]['id']);
        $parametros = array_map(fn($id, $index) => $index === 0 ? ($fila[$id] ?? ''): $id,
        $ids, 
        array_keys($ids));
        $fila[] = '<button type="button" class="btn btn-sm py-0 px-1 btn-'.$botones[0]['tipo'].'"
                    onclick="'.$botones[0]['boton']. '(\''.implode("', '", $parametros). '\')" 
                    title="'.$botones[0]['boton'].'">'.
                    $botones[0]['icono'].
                    '</button>';
      }
    }
			 // print_r($tbl);die();
		return array('tbl'=>$tbl,'datos'=>$datos);
    }
     function DG_asientoR_datos()
    {
    	$cid = $this->conn;
       $sql = "SELECT *
       FROM Asiento_Air
       WHERE Item = '".$_SESSION['INGRESO']['item']. "'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND T_No = ".$_SESSION['INGRESO']['modulo_']." ";
       $result = $this->conn->datos($sql);
	   return $result;
    }

    function DG_AC()
    {
    	$cid = $this->conn;

       $sql = "SELECT *
       FROM Asiento_Compras
       WHERE Item = '".$_SESSION['INGRESO']['item']. "'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND T_No = ".$_SESSION['INGRESO']['modulo_']." ";

	   $botones[0] = array('boton'=>'eliminar', 'icono'=>'<i class="bx bx-trash bx-xs p-0 m-0"></i>', 'tipo'=>'danger', 'id'=>'IdProv,compras' );
	   $tbl = grilla_generica_new($sql);
     if(!empty($tbl['data'])){ 
      foreach ($tbl['data'] as &$fila){ 
        $ids = explode(',', $botones[0]['id']);
        $parametros = array_map(fn($id, $index) => $index === 0 ? ($fila[$id] ?? ''): $id,
        $ids, 
        array_keys($ids));
        $fila[] = '<button type="button" class="btn btn-sm py-0 px-1 btn-'.$botones[0]['tipo'].'"
                    onclick="'.$botones[0]['boton']. '(\''.implode("', '", $parametros). '\')" 
                    title="'.$botones[0]['boton'].'">'.
                    $botones[0]['icono'].
                    '</button>';
      }
    }
	   return $tbl;
	
    }

    function DG_AV()
    {
    	$cid = $this->conn;
       $sql = "SELECT *
       FROM Asiento_Ventas
       WHERE Item = '".$_SESSION['INGRESO']['item']. "'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND T_No = ".$_SESSION['INGRESO']['modulo_']." ";
   
	   $botones[0] = array('boton'=>'eliminar', 'icono'=>'<i class="bx bx-trash bx-xs p-0 m-0"></i>', 'tipo'=>'danger', 'id'=>'IdProv,ventas' );
	   $tbl = grilla_generica_new($sql);
     if(!empty($tbl['data'])){ 
      foreach ($tbl['data'] as &$fila){ 
        $ids = explode(',', $botones[0]['id']);
        $parametros = array_map(fn($id, $index) => $index === 0 ? ($fila[$id] ?? ''): $id,
        $ids, 
        array_keys($ids));
        $fila[] = '<button type="button" class="btn btn-sm py-0 px-1 btn-'.$botones[0]['tipo'].'"
                    onclick="'.$botones[0]['boton']. '(\''.implode("', '", $parametros). '\')" 
                    title="'.$botones[0]['boton'].'">'.
                    $botones[0]['icono'].
                    '</button>';
      }
    }
	   return $tbl;
    }

    function DG_AE()
    {
    	$cid = $this->conn;
        $sql = "SELECT *
       FROM Asiento_Exportaciones
       WHERE Item = '".$_SESSION['INGRESO']['item']. "'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND T_No = ".$_SESSION['INGRESO']['modulo_']." ";
   //     $stmt = sqlsrv_query( $cid, $sql);
			// if( $stmt === false)  
			// {  
			// 	 echo "Error en consulta PA.\n";  
			// 	 die( print_r( sqlsrv_errors(), true));  
			// }
			// else
			// {
			// 	$camne=array();
			// 	// return grilla_generica($stmt,null,NULL,'1','8,9,10,11,clave1','asi_sc');
			// 	 $button[0] = array('nombre'=>'eliminar','tipo'=>'danger','icon'=>'fa fa-trash','dato'=>array('0,expo'));
			// 	 grilla_generica($stmt,null,null,1,null,null,null,false,$button);
			// }

     $botones[0] = array('boton'=>'eliminar', 'icono'=>'<i class="bx bx-trash bx-xs p-0 m-0"></i>', 'tipo'=>'danger', 'id'=>'Codigo,expo' );
	   $tbl = grilla_generica_new($sql);
     if(!empty($tbl['data'])){ 
      foreach ($tbl['data'] as &$fila){ 
        $ids = explode(',', $botones[0]['id']);
        $parametros = array_map(fn($id, $index) => $index === 0 ? ($fila[$id] ?? ''): $id,
        $ids, 
        array_keys($ids));
        $fila[] = '<button type="button" class="btn btn-sm py-0 px-1 btn-'.$botones[0]['tipo'].'"
                    onclick="'.$botones[0]['boton']. '(\''.implode("', '", $parametros). '\')" 
                    title="'.$botones[0]['boton'].'">'.
                    $botones[0]['icono'].
                    '</button>';
      }
    }
	   return $tbl;
    }

    function DG_AI()
    {
    	$cid = $this->conn;

      $sql = "SELECT *
       FROM Asiento_Importaciones
       WHERE Item = '".$_SESSION['INGRESO']['item']. "'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND T_No = ".$_SESSION['INGRESO']['modulo_']." ";
   //     $stmt = sqlsrv_query( $cid, $sql);
			// if( $stmt === false)  
			// {  
			// 	 echo "Error en consulta PA.\n";  
			// 	 die( print_r( sqlsrv_errors(), true));  
			// }
			// else
			// {
			// 	$camne=array();
			// 	// return grilla_generica($stmt,null,NULL,'1','8,9,10,11,clave1','asi_sc');
			// 	 $button[0] = array('nombre'=>'eliminar','tipo'=>'danger','icon'=>'fa fa-trash','dato'=>array('0,inpor'));
			// 	 grilla_generica($stmt,null,null,1,null,null,null,false,$button);
			// }

     $botones[0] = array('boton'=>'eliminar', 'icono'=>'<i class="bx bx-trash bx-xs p-0 m-0"></i>', 'tipo'=>'danger', 'id'=>'CodSustento,inpor' );
	   $tbl = grilla_generica_new($sql);
     if(!empty($tbl['data'])){ 
      foreach ($tbl['data'] as &$fila){ 
        $ids = explode(',', $botones[0]['id']);
        $parametros = array_map(fn($id, $index) => $index === 0 ? ($fila[$id] ?? ''): $id,
        $ids, 
        array_keys($ids));
        $fila[] = '<button type="button" class="btn btn-sm py-0 px-1 btn-'.$botones[0]['tipo'].'"
                    onclick="'.$botones[0]['boton']. '(\''.implode("', '", $parametros). '\')" 
                    title="'.$botones[0]['boton'].'">'.
                    $botones[0]['icono'].
                    '</button>';
      }
    }
	   return $tbl;

    }

    function LeerCta($CodigoCta)
    {
    	
    	$cid = $this->conn;
    	if(strlen(substr($CodigoCta,0,1))>=1)
    	{
    	  $sql="SELECT Codigo, Cuenta, TC, ME, DG, Tipo_Pago 
          FROM Catalogo_Cuentas 
          WHERE Codigo = '".$CodigoCta."'
          AND Item = '".$_SESSION['INGRESO']['item']."'
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
          $result = $this->conn->datos($sql);
		  return $result;
    	}
    }

    function catalogo_subcta_grid($tc,$SubCtaGen,$OpcDH,$OpcTM)
    {
    	$cid = $this->conn;
    	

        $sql = "SELECT * 
         FROM Asiento_SC
         WHERE TC = '".$tc."'
         AND Cta = '".$SubCtaGen."'
         AND DH = '".$OpcDH."'
         AND TM = '".$OpcTM."'
         AND T_No = '".$_SESSION['INGRESO']['modulo_']."'
         AND Item = '".$_SESSION['INGRESO']['item']."'
         AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'";
          $result = $this->conn->datos($sql);

			 // print_r($tbl);die();
		     return $result;
    }

    function catalogo_subcta($SubCta,$agrupado=false,$nivel=false)
    { 
    	$cid = $this->conn;
    	
    	$sql = "SELECT Detalle,Codigo, Nivel
        FROM Catalogo_SubCtas
        WHERE TC = '".$SubCta."'
        AND Item = '".$_SESSION['INGRESO']['item']."'
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
        AND Agrupacion <>  ".intval($agrupado);
        if($nivel)
        {
        	 $sql.=" AND Nivel ='".$nivel."'";
        }
        $sql.=" AND Codigo <> '.' 
        ORDER BY Nivel,Detalle ";
// print_r($sql);die();
         $result = $this->conn->datos($sql);
		  return $result;
    }
    function Catalogo_CxCxP($SubCta,$SubCtaGen,$query=false)
    {

    	$cid = $this->conn;
    	 $sql= "SELECT Cl.Cliente As NomCuenta, CP.Codigo, Cl.Credito 
         FROM Catalogo_CxCxP As CP,Clientes As Cl 
         WHERE CP.TC = '".$SubCta."' 
         AND CP.Cta = '".$SubCtaGen."' 
         AND CP.Item = '".$_SESSION['INGRESO']['item']."'
         AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
         AND Cl.Codigo <> '.' 
         AND CP.Codigo = Cl.Codigo "; 
         if($query)
         {
         	$sql.= "AND Cl.Cliente LIKE '%".$query."%' ";

         }
         $sql.=" ORDER BY Cl.Cliente ";
        
         // print_r($sql);die();
        $result = $this->conn->datos($sql);
		  return $result;
    }


     function detalle_aux_submodulo($query = false,$SubCta=false)
     {

    	$cid = $this->conn;
     	 $sql = "SELECT TOP 20 Detalle_SubCta
         FROM Trans_SubCtas
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
         if($SubCta)
         {
         	$sql.=" AND TC = '".$SubCta."' ";
         }
         if($query)
         {
         	if($query!='')
         	{
         		$sql.=" AND Detalle_SubCta LIKE '%".$query."%' ";
         	}
         }
         $sql.="GROUP BY Detalle_SubCta
         ORDER BY Detalle_SubCta ";

         // print_r($sql);die();
           $result = $this->conn->datos($sql);
		  return $result;
     }

     function DG_asientos_SC_total($dh)
    {
    	$cid = $this->conn;
       $sql = "SELECT SUM(Valor) as 'total'
       FROM Asiento_SC
       WHERE Item = '".$_SESSION['INGRESO']['item']. "'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND T_No = ".$_SESSION['INGRESO']['modulo_']." 
       AND DH = ".$dh;
       $result = $this->conn->datos($sql);
		  return $result;
    }
    function limpiar_asiento_SC($SubCta,$SubCtaGen,$OpcDH,$OpcTM)
    {

    	$cid = $this->conn;
    	 $sql = "DELETE 
         FROM Asiento_SC 
         WHERE TC = '".$SubCta."'
         AND Cta = '".$SubCtaGen."'
         AND DH = '".$OpcDH."'
         AND TM = '".$OpcTM."'
         AND T_No = ".$_SESSION['INGRESO']['modulo_']."
         AND Item = '".$_SESSION['INGRESO']['item']. "'
         AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' ;";


         $sql.= "DELETE 
         FROM Asiento
         WHERE TC = '".$SubCta."'
         AND CODIGO = '".$SubCtaGen."'
         AND T_No = ".$_SESSION['INGRESO']['modulo_']."
         AND Item = '".$_SESSION['INGRESO']['item']. "'
         AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'";
         if($OpcDH==1)
        {
          $sql.=" AND DEBE <> 0 and HABER = 0 ";
        }else
        {
          $sql.=" AND HABER <> 0 and DEBE = 0";
        }
        // print_r($sql);die();

          $result = $this->conn->String_Sql($sql);
          // print_r($sql);die();
	     return $result;

  //        // print_r($sql);die();
  //        $stmt = sqlsrv_query( $cid, $sql);
  //        if( $stmt === false)  
		// {  
		// 	 echo "Error en consulta PA.\n";  
		// 	 die( print_r( sqlsrv_errors(), true));  
		// }
		   
		// return 1;
    }
    function asientos($id=false,$A_No=false)
    { 
    	$cid = $this->conn;
		$result = array();
    	$sql = "SELECT *
         FROM Asiento
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
         AND T_No = ".$_SESSION['INGRESO']['modulo_'];
         if($A_No)
         {
         	$sql.=" AND A_No='".$A_No."'";
         }
         $sql.=" ORDER BY A_No ";
         // print_r($sql);die();
         $result = $this->conn->datos($sql);
	   return $result;
 

    }
    function asientos_SC()
    {
    	
    	$cid = $this->conn;
		$result = array();
    	$sql = "SELECT *
         FROM Asiento_SC
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
         AND T_No = ".$_SESSION['INGRESO']['modulo_']."";
         $result = $this->conn->datos($sql);
	   return $result;

    }
    function eliminacion_retencion()
    {
    	
    	$cid = $this->conn;
    	$sql = "DELETE FROM Asiento_Air
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
         AND T_No = ".$_SESSION['INGRESO']['modulo_'].";";

         $sql.= "DELETE FROM Asiento_Compras
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
         AND T_No = ".$_SESSION['INGRESO']['modulo_'].";";
         $result = $this->conn->String_Sql($sql);
	     return $result;

    }

    function eliminar_registros($tabla,$Codigo)
    {
    	$cid = $this->conn;
    	$sql = "DELETE FROM ".$tabla."
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND ".$Codigo."
         AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
         AND T_No = ".intval($_SESSION['INGRESO']['modulo_']).";";

         //print_r($sql);die();
          $result = $this->conn->String_Sql($sql);
	     return $result;

    }
    function retencion_compras($numero,$tipoCom)
    {
    	$cid = $this->conn;
		$result = array();
        $sql = "SELECT C.Cliente,C.CI_RUC,C.TD,C.Direccion,C.Telefono,C.Email,TC.* 
        FROM Trans_Compras As TC, Clientes As C 
        WHERE TC.Item = '".$_SESSION['INGRESO']['item']."' 
        AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND TC.Numero = ".$numero." 
        AND TC.TP = '".$tipoCom."' 
        AND LEN(TC.AutRetencion) = 13 
        AND TC.IdProv = C.Codigo 
        ORDER BY Serie_Retencion,SecRetencion ";
        // print_r($sql);die();
         $result = $this->conn->datos($sql);
	     return $result;
    }

    function validar_porc_iva($FechaIVA)
    {
   // 'Carga la Tabla de Porcentaje IVA'
    $cid = $this->conn;
    if ($FechaIVA == "00/00/0000"){ $FechaIVA = date('Y-m-d');}
     $sql = "SELECT * 
           FROM Tabla_Por_ICE_IVA 
           WHERE IVA <> 0 
           AND Fecha_Inicio <= '".$FechaIVA."' 
           AND Fecha_Final >= '".$FechaIVA."'
           ORDER BY Porc ";
         $result = $this->conn->datos($sql);
		 if(count($result)>0)
		 {
		 	$Porc_IVA = round($result[0]["Porc"] / 100, 2);
		 	return $Porc_IVA;
		 }
	}

	function Asiento_Air_Com ($Autorizacion_R,$T_No)
	{

    $cid = $this->conn;
    $result = array();
	 if(strlen($Autorizacion_R) >= 13){
      $sql = "SELECT * 
             FROM Asiento_Air 
             WHERE Item = '".$_SESSION['INGRESO']['item']."' 
             AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' 
             AND T_No = ".$T_No." 
             ORDER BY Tipo_Trans, A_No ";
             $result = $this->conn->datos($sql);
		 
     }
      return $result;
	}
	function Actualizar_Ctas_a_mayorizar($TP,$Numero)
	{
		$cid = $this->conn;
		$result = array();
		$sql = "SELECT Codigo_Inv
        FROM Trans_Kardex
        WHERE TP = '".$TP."'
        AND Numero = ".$Numero."
        AND Item = '".$_SESSION['INGRESO']['item']."'
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
        $result = $this->conn->datos($sql);
		  return $result;
	}
	function Actualiza_Procesado_Kardex($CodigoInv)
	{
		 if(strlen($CodigoInv) > 2){ 
         $sql = "UPDATE Trans_Kardex
               SET Procesado = 0
               WHERE Item = '".$_SESSION['INGRESO']['item']."'
               AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
               AND Codigo_Inv = '".$CodigoInv."' ";
      	 $this->conn->String_Sql($sql);
       }
	}

	function EliminarComprobantes($TP,$Numero)
	{
       $sql = "DELETE  
       FROM Comprobantes 
       WHERE TP = '".$TP."' 
       AND Numero = ".$Numero."  
       AND Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";

       $sql2 = "DELETE  
       FROM Transacciones 
       WHERE TP = '".$TP."' 
       AND Numero = ".$Numero."
       AND Item = '".$_SESSION['INGRESO']['item']."'
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";

       $sql3 = "DELETE  
       FROM Trans_SubCtas 
       WHERE TP = '".$TP."' 
       AND Numero = ".$Numero." 
       AND Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";


       $sql4 = "DELETE  
       FROM Trans_Kardex 
       WHERE TP = '".$TP."' 
       AND Numero = ".$Numero." 
       AND Item = '".$_SESSION['INGRESO']['item']."'
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       AND LEN(TC) <= 1 ";

       $sql5 = "DELETE  
       FROM Trans_Compras 
       WHERE TP = '".$TP."' 
       AND Numero = ".$Numero." 
       AND Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";

       $sql6 = "DELETE  
       FROM Trans_Air 
       WHERE TP = '".$TP."' 
       AND Numero = ".$Numero."
       AND Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";

       $sql7 = "DELETE  
       FROM Trans_Ventas 
       WHERE TP = '".$TP."' 
       AND Numero = ".$Numero." 
       AND Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";

       $sql8 = "DELETE  
       FROM Trans_Exportaciones 
       WHERE TP = '".$TP."' 
       AND Numero = ".$Numero." 
       AND Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";

       $sql9 = "DELETE 
       FROM Trans_Importaciones 
       WHERE TP = '".$TP."' 
       AND Numero = ".$Numero." 
       AND Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
  
       $sql10 = "DELETE 
       FROM Trans_Rol_Pagos 
       WHERE TP = '".$TP."' 
       AND Numero = ".$Numero." 
       AND Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";

       $sqlT = $sql.$sql2.$sql3.$sql4.$sql5.$sql6.$sql7.$sql8.$sql9.$sql10;

       // print_r($sqlT);die();
         $result = $this->conn->String_Sql($sql);
	   return $result;

	} 

	function Por_Bodegas()
	{

       $cid = $this->conn;
       $result = array();
		$sql = "SELECT CodBod
		FROM Catalogo_Bodegas
        WHERE Item = '".$_SESSION['INGRESO']['item']."'
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ;";
          $result = $this->conn->datos($sql);
	   return $result;

	}

	function Grabamos_SubCtas($T_No)
	{
		
       $cid = $this->conn;
       $result = array();
       $sql = "SELECT *
       FROM Asiento_SC
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND T_No = ".$T_No." 
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' 
       ORDER BY TC,Cta,Codigo ";
         $result = $this->conn->datos($sql);
	   return $result;

	}

	function RETENCIONES_COMPRAS($T_No)
	{
       $result = array();
		$sql = "SELECT *
     FROM Asiento_Compras
     WHERE Item = '".$_SESSION['INGRESO']['item']."'
     AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
     AND T_No = ".$T_No."
     ORDER BY T_No ";
       $result = $this->conn->datos($sql);
	   return $result;

	}

	function RETENCIONES_VENTAS($T_No)
	{
		 $cid = $this->conn;
       $result = array();
		$sql = "SELECT *
     FROM Asiento_Ventas
     WHERE Item = '".$_SESSION['INGRESO']['item']."'
     AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
     AND T_No = ".$T_No."
     ORDER BY T_No DESC ";
      $result = $this->conn->datos($sql);
	   return $result;
	}
	
	function RETENCIONES_EXPORTACION($T_No)
	{
       $result = array();
		$sql = "SELECT *
     FROM Asiento_Exportaciones
     WHERE Item = '".$_SESSION['INGRESO']['item']."'
     AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
     AND T_No = ".$T_No."
     ORDER BY T_No DESC ";
      $result = $this->conn->datos($sql);
	   return $result;
	}

	function  RETENCIONES_IMPORTACIONES($T_No)
	{
       $result = array();
		$sql = "SELECT *
     FROM Asiento_Importaciones
     WHERE Item = '".$_SESSION['INGRESO']['item']."'
     AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
     AND T_No = ".$T_No."
     ORDER BY T_No DESC ";
      $result = $this->conn->datos($sql);
		  return $result;
	}

	function RETENCIONES_AIR($T_No)
	{
		 $cid = $this->conn;
       $result = array();
  $sql = "SELECT *
     FROM Asiento_Air
     WHERE Item = '".$_SESSION['INGRESO']['item']."'
     AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
     AND T_No = ".$T_No."
     ORDER BY Tipo_Trans,A_No ";
      $result = $this->conn->datos($sql);
	   return $result;
   }

   function Retencion_Rol_Pagos($T_No){
       $result = array();
  $sql = "SELECT *
     FROM Asiento_RP
     WHERE Item = '".$_SESSION['INGRESO']['item']."'
     AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
     AND T_No = ".$T_No."
     ORDER BY Codigo ";
      $result = $this->conn->datos($sql);
      $stmt = $this->conn->devolver_stmt($sql);
	  return  array('res'=>$result,'smtp'=>$stmt);
   }

   function Grabamos_Inventarios($T_No)
   {
       $result = array();
  $sql = "SELECT *
     FROM Asiento_K
     WHERE Item = '".$_SESSION['INGRESO']['item']."'
     AND T_No = ".$T_No."
     AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' ";
       $result = $this->conn->datos($sql);
	   return $result;
   }

   function  Grabamos_Prestamos($T_No)
   {
       $result = array();
  $sql = "SELECT *
     FROM Asiento_P
     WHERE Item = '".$_SESSION['INGRESO']['item']."'
     AND T_No = ".$T_No."
     AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' ";
      $result = $this->conn->datos($sql);
	   return $result;
   }

   function Grabamos_Transacciones($T_No)
   {
       $result = array();
  $sql = "SELECT *
     FROM Asiento
     WHERE Item = '".$_SESSION['INGRESO']['item']."'
     AND T_No = ".$T_No."
     AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
     ORDER BY A_No,DEBE DESC,CODIGO ";
      $result = $this->conn->datos($sql);
	   return $result;
   }

   function actualizar_email($email,$codigoB)
   {
   	$sql ="UPDATE Clientes
   	      SET Email = '".$email."'
   	      WHERE Codigo = '".$codigoB."' ";
	  $result = $this->conn->String_Sql($sql);
	   return $result;
   }

   function cuentas_a_mayorizar($cta)
   {
   	    $sql = "UPDATE Transacciones 
               SET Procesado = 0 
               WHERE Item = '".$_SESSION['INGRESO']['item']."'
               AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
               AND Cta = '".$cta."' ";
               // print_r($sql);
          $result = $this->conn->String_Sql($sql);
	   return $result;

   }

   function BorrarAsientos($Trans_No,$B_Asiento=false)
   {   	
   	$sql='';
   	if($Trans_No <= 0){$Trans_No = 1;}
  if($B_Asiento){
     $sql.= "DELETE
       FROM Asiento
       WHERE Item = '".$_SESSION['INGRESO']['item']."'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND T_No = ".$Trans_No." ";
  }
  $sql.= "DELETE
    FROM Asiento_SC
    WHERE Item = '".$_SESSION['INGRESO']['item']."'
    AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
    AND T_No = ".$Trans_No." ";

  $sql.= "DELETE
    FROM Asiento_B
    WHERE Item = '".$_SESSION['INGRESO']['item']."'
    AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
    AND T_No = ".$Trans_No." ";


  $sql.= "DELETE
    FROM Asiento_R
    WHERE Item = '".$_SESSION['INGRESO']['item']."'
    AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
    AND T_No = ".$Trans_No." ";


  $sql.= "DELETE
    FROM Asiento_RP
    WHERE Item = '".$_SESSION['INGRESO']['item']."'
    AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
    AND T_No = ".$Trans_No." ";


  $sql.= "DELETE
    FROM Asiento_K
    WHERE Item = '".$_SESSION['INGRESO']['item']."'
    AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
    AND T_No = ".$Trans_No." ";

  $sql.= "DELETE
    FROM Asiento_P
    WHERE Item = '".$_SESSION['INGRESO']['item']."'
    AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
    AND T_No = ".$Trans_No." ";

  $sql.= "DELETE
    FROM Asiento_Air
    WHERE Item = '".$_SESSION['INGRESO']['item']."'
    AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
    AND T_No = ".$Trans_No." ";

  $sql.= "DELETE
    FROM Asiento_Compras
    WHERE Item = '".$_SESSION['INGRESO']['item']."'
    AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
    AND T_No = ".$Trans_No." ";

  $sql.= "DELETE
    FROM Asiento_Exportaciones
    WHERE Item = '".$_SESSION['INGRESO']['item']."'
    AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
    AND T_No = ".$Trans_No." ";

  $sql.= "DELETE
    FROM Asiento_Importaciones
    WHERE Item = '".$_SESSION['INGRESO']['item']."'
    AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
    AND T_No = ".$Trans_No." ";

  $sql.= "DELETE
    FROM Asiento_Ventas
    WHERE Item = '".$_SESSION['INGRESO']['item']."'
    AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
    AND T_No = ".$Trans_No." ";
    // print_r($sql);die();
      $result = $this->conn->String_Sql($sql);
	   return $result;
   }


   function Encabezado_Comprobante($parametros)
   {

   	 $sql = "SELECT C.*,Cl.CI_RUC,Cl.Cliente,Cl.Email,Cl.TD
       FROM Comprobantes As C, Clientes As Cl
       WHERE C.TP = '".$parametros['TP']."'
       AND C.Numero = ".$parametros['Numero']."
       AND C.Item = '".$parametros['Item']."'
       AND C.Periodo = '".$_SESSION['INGRESO']['periodo']."'
       AND C.Codigo_B = Cl.Codigo ";
       // print_r($sql);die();
		  $result = $this->conn->datos($sql);
	   return $result;
   }

    function transacciones_comprobante($tp,$numero,$item)
     {
     	 $sql = "SELECT T.Cta,Ca.Cuenta,T.Parcial_ME,T.Debe,T.Haber,T.Detalle,T.Cheq_Dep,T.Fecha_Efec,T.Codigo_C,Ca.Item,T.TP,T.Numero,T.Fecha,T.ID 
             FROM Transacciones As T, Catalogo_Cuentas As Ca 
             WHERE T.TP = '".$tp."' 
             AND T.Numero = ".$numero." 
             AND T.Item = '".$item."' 
             AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
             AND T.Item = Ca.Item 
             AND T.Periodo = Ca.Periodo 
             AND T.Cta = Ca.Codigo 
             ORDER BY T.ID,Debe DESC,T.Cta ";
               $result = $this->conn->datos($sql);
	   return $result;


     } 
     
     function retenciones_comprobantes($tp,$numero,$item)
     {
   	     $cid = $this->conn;
     	 // 'Listar las Retenciones
        $sql = "SELECT * 
         FROM Trans_Air 
         WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' 
         AND Item = '".$item."' 
         AND Numero = ".$numero." 
         AND TP = '".$tp."' ";
          $stmt = $this->conn->devolver_stmt($sql);
          $result = $this->conn->datos($sql);

             return array('respuesta'=>$result,'stmt'=>$stmt);

     }

     function Listar_Compras($tp,$numero,$item)
     {
   	     $cid = $this->conn;
     	 $sql = "SELECT * 
         FROM Trans_Compras 
         WHERE Numero = ".$numero." 
         AND TP = '".$tp."' 
         AND Item = '".$item."' 
         AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
         $stmt = $this->conn->devolver_stmt($sql);
         $result = $this->conn->datos($sql);

        return array('respuesta'=>$result,'stmt'=>$stmt);

     }

     function Listar_Ventas($tp,$numero,$item)
     {
   	     $cid = $this->conn;
     	$sql = "SELECT * 
         FROM Trans_Ventas 
         WHERE Numero = ".$numero." 
         AND TP = '".$tp."' 
         AND Item = '".$item."' 
         AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
         $stmt = $this->conn->devolver_stmt($sql);
         $result = $this->conn->datos($sql);

        return array('respuesta'=>$result,'stmt'=>$stmt);
     }
     function Listar_Importaciones($tp,$numero,$item)
     {
   	     $cid = $this->conn;
     	  $sql = "SELECT *
          FROM Trans_Importaciones
          WHERE Numero = ".$numero."
          AND TP = '".$tp."'
          AND Item = '".$item."'
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
           $stmt = $this->conn->devolver_stmt($sql);
           $result = $this->conn->datos($sql);
        return array('respuesta'=>$result,'stmt'=>$stmt);
     	
     }

     function Listar_las_Compras($tp,$numero,$item)
     {

   	     $cid = $this->conn;
     	$sql = "SELECT * 
        FROM Trans_Exportaciones 
        WHERE Numero = ".$numero." 
        AND TP = '".$tp."' 
        AND Item = '".$item."' 
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
        $stmt = $this->conn->devolver_stmt($sql);
         $result = $this->conn->datos($sql);
        return array('respuesta'=>$result,'stmt'=>$stmt);
     	
     }
     function Llenar_SubCuentas($tp,$numero,$item)
     {
   	     $cid = $this->conn;
     	  $sql = "SELECT TSC.TC,TSC.Cta,Detalle,Detalle_SubCta,Factura,Prima,Fecha_V,Be.Codigo
           Parcial_ME,(Debitos-Creditos) As VALOR
           FROM Trans_SubCtas As TSC, Catalogo_SubCtas As Be
           WHERE TSC.Numero = ".$numero."
           AND TSC.TP = '".$tp."'
           AND TSC.Item = '".$item."'
           AND TSC.Periodo = '".$_SESSION['INGRESO']['periodo']."'
           AND TSC.TC NOT IN ('C','P')
           AND TSC.Codigo = Be.Codigo
           AND TSC.Item = Be.Item
           AND TSC.Periodo = Be.Periodo
           ORDER BY TSC.Cta,Detalle ";
           $result = $this->conn->datos($sql);
           return $result;
     	
     }
     function Llenar_SubCuentas2($tp,$numero,$item)
     {
   	     $cid = $this->conn;
     	  $sql = "SELECT TSC.TC,TSC.Cta,Cliente As Detalle,Detalle_SubCta,Factura,Prima,Fecha_V,Be.Codigo,Parcial_ME,(Debitos-Creditos) As VALOR 
          FROM Trans_SubCtas As TSC, Clientes As Be 
          WHERE TSC.TP = '".$tp."' 
          AND TSC.Numero = ".$numero." 
          AND TSC.Item = '".$item."' 
          AND TSC.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          AND TSC.TC IN ('C','P') 
          AND TSC.Codigo = Be.Codigo 
          ORDER BY TSC.Cta,Cliente ";
        $result = $this->conn->datos($sql);
           return $result;
     	
     }

     function verificar_existente($codigo,$va)
     {
     	$sql="SELECT CODIGO, CUENTA
		FROM Asiento
		WHERE (CODIGO = '".$codigo."') 
		AND (Item = '".$_SESSION['INGRESO']['item']."') 
		AND (CodigoU = '".$_SESSION['INGRESO']['CodigoU']."') 
		AND (DEBE = '".$va."') 
		AND T_No=".$_SESSION['INGRESO']['modulo_']." 
		ORDER BY A_No ASC ";
		$result = $this->conn->datos($sql);
        return $result;
     }

     function valor_siguiente()
     {
     	$sql="SELECT TOP 1 A_No FROM Asiento
		WHERE (Item = '".$_SESSION['INGRESO']['item']."')
		ORDER BY A_No DESC";
		$result = $this->conn->datos($sql);
        return $result;
     }

     function insertar_aseinto($codigo,$cuenta,$parcial,$debe,$haber,$chq_as,$dconcepto1,$efectivo_as,$t_no,$A_No,$bene)
     {
     	$sql="INSERT INTO Asiento
			(CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE,EFECTIVIZAR,CODIGO_C,CODIGO_CC,ME,T_No,Item,CodigoU,A_No,BENEFICIARIO)
				VALUES
			('".$codigo."','".$cuenta."',".$parcial.",".$debe.",".$haber.",'".$chq_as."','".$dconcepto1."',
				'".$efectivo_as."','.','.',0,".$t_no.",'".$_SESSION['INGRESO']['item']."','".$_SESSION['INGRESO']['CodigoU']."',".$A_No.",'".$bene."')";
		return $this->conn->String_Sql($sql);

     }

     function listar_asientos($tabla=false)
     {
     	$sql="SELECT A_No,CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE
			FROM Asiento
			WHERE 
			T_No=".$_SESSION['INGRESO']['modulo_']." 
			AND	Item = '".$_SESSION['INGRESO']['item']."' 
			AND CodigoU = '".$_SESSION['INGRESO']['Id']."' 
			ORDER BY A_No ASC ";
		if($tabla)
		{

			$tbl = grilla_generica_new($sql);
			return $tbl;
		}else{
			$result = $this->conn->datos($sql);
        	return $result;
        }
     }

     function actualizar_trans_compras($tp,$retencion,$serie,$autorizacion,$autAnte)
     {
     	$sql ="UPDATE Trans_Compras SET AutRetencion='".$autorizacion."' 
     		WHERE Item = '".$_SESSION['INGRESO']['item']."' 
			AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			AND TP = '".$tp."' 
			AND SecRetencion = '".$retencion."'
			AND Serie_Retencion = '".$serie."' 
			AND LEN(AutRetencion) = 13
			AND AutRetencion = '".$autAnte."'";
		return $this->conn->String_Sql($sql);

     }
     function atualizar_trans_air($tp,$retencion,$serie,$autorizacion,$autAnte)
     {
     	$sql="UPDATE Trans_Air SET AutRetencion='".$autorizacion."' 
     	WHERE Item = '".$_SESSION['INGRESO']['item']."' 
		AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' 
		AND TP = '".$tp."' 
		AND EstabRetencion = '".substr($serie,0,3)."'
		AND PtoEmiRetencion =  '".substr($serie,3,6)."'
		AND SecRetencion = '".$retencion."'
		AND LEN(AutRetencion) = 13  
		AND AutRetencion ='".$autAnte."'";
		return $this->conn->String_Sql($sql);
     }

     function guardar_documento($autorizacion,$cadena2,$serie,$retencion)
     {
     	$sql="INSERT INTO Trans_Documentos
		    (Item,Periodo,Clave_Acceso,Documento_Autorizado,TD,Serie,Documento,X)
			 VALUES
		    ('".$_SESSION['INGRESO']['item']."' 
		    ,'".$_SESSION['INGRESO']['periodo']."' 
		    ,'".$autorizacion."'
		    ,'".$cadena2."'
		    ,'RE' 
		    ,'".$serie."' 
		    ,".$retencion." 
			,'.');";
		return $this->conn->String_Sql($sql);

     }

  function reporte_retencion($numero,$TP,$retencion,$serie,$imp=0)
	{
		$datos = array();
		$detalle = array(); 
		$cliente = array();
		$TFA = array();



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
	   $datos = $this->conn->datos($sql);

	   // print_r($sql);die();
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
	  
	  $tipo_con = Tipo_Contribuyente_SP_MYSQL($_SESSION['INGRESO']['RUC']);

		if(count($datos)>0 && count($tipo_con)>0)
			{
				$datos_fac['Tipo_contribuyente'] = $tipo_con;
			}


	   // print_r($TFA);die();

   // 'Determinamos el Tipo de Comprobante
    $sql = "SELECT Tipo_Comprobante_Codigo, Descripcion 
        FROM Tipo_Comprobante 
        WHERE TC = 'TDC' 
        AND Tipo_Comprobante_Codigo = ".intval($TFA['Tipo_Comp']);
     $datos1 = $this->conn->datos($sql);
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
    	$datos2 = $this->conn->datos($sql);

    	$resultado = array();
			foreach ($datos as $key => $value) {
			    $resultado[] = array_merge($value, $TFA);
			}
		$datos = $resultado;
 		$datos[0]['TC'] = 'RE';
    $sucursal = $this->catalogo_lineas('RE',$serie);
	  $this->reportes->imprimirDocEle_ret($datos,$datos2,'Retencion',$imp,$sucursal);

	}	

  function load_subcuentas($parametros){
    $cid = $this->conn;
	  $sql = "SELECT Codigo, Beneficiario, Valor, DH, TC, Cta, TM, T_No, SC_No, Item, CodigoU
       FROM Asiento_SC
       WHERE TC = '".$parametros['SubCta']."'
       AND Cta = '".$parametros['SubCtaGen']."'
       AND DH = '".$parametros['OpcDH']."'
       AND TM = '".$parametros['OpcTM']."'
       AND T_No = ".$_SESSION['INGRESO']['modulo_']."
       AND Item = '".$_SESSION['INGRESO']['item']."'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       ORDER BY Codigo ";
    //print_r($sql);die();
		return $cid->datos($sql);
  }

  function activate_cc($parametros){
    $sql = "UPDATE Catalogo_SubCtas
            SET X = '.' 
            WHERE Item = '".$_SESSION['INGRESO']['item']."'
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
    Ejecutar_SQL_SP($sql);

    $sql = "UPDATE Catalogo_SubCtas
            SET X = 'I'
            FROM Catalogo_SubCtas As CS, Asiento_SC As A
            WHERE CS.Item = '".$_SESSION['INGRESO']['item']."'
            AND CS.Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND CS.TC = 'CC'
            AND A.Cta = '".$parametros['SubCtaGen']."'
            AND A.DH = '".$parametros['OpcDH']."'
            AND A.TM = '".$parametros['OpcTM']."'
            AND A.T_No = ".$_SESSION['INGRESO']['modulo_']."
            AND A.CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
            AND CS.Item = A.Item
            AND CS.Codigo = A.Codigo
            AND CS.TC = A.TC";
    Ejecutar_SQL_SP($sql);

    $sql = "DELETE *
            FROM Asiento_SC
            WHERE TC = '".$parametros['SubCta']."'
            AND Cta = '".$parametros['SubCtaGen']."'
            AND DH = '".$parametros['OpcDH']."'
            AND TM = '".$parametros['OpcTM']."'
            AND T_No = ".$_SESSION['INGRESO']['modulo_']."
            AND Item = '".$_SESSION['INGRESO']['item']."'
            AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'";
    Ejecutar_SQL_SP($sql);

    //TODO: En el BuscarFecha va un Co.Fecha, de donde se obtiene?
    $sql = "INSERT INTO Asiento_SC (Codigo, Beneficiario, FECHA_V, TC, TM, DH, Cta, T_No, Item, CodigoU, Detalle_SubCta, Factura, Prima, Valor, Valor_ME, SC_No, Fecha_D, Fecha_H, Bloquear)
            SELECT Codigo, Detalle, '" . BuscarFecha(date('Y-m-d')) . "', 'CC' , '" . $parametros['OpcTM']. "', '" . $parametros['OpcDH'] . "', 
            '" . $parametros['SubCtaGen'] . "', " . $_SESSION['INGRESO']['modulo_'] . ", '".$_SESSION['INGRESO']['item']."', '".$_SESSION['INGRESO']['CodigoU']."', '.', 0, 0, 0, 0, 0, Fecha_D, Fecha_H, Bloquear
            FROM Catalogo_SubCtas
            WHERE Item = '".$_SESSION['INGRESO']['item']."'
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND TC = 'CC' 
            AND X = '.' 
            AND Agrupacion = 0
            ORDER BY Codigo, Detalle";
    Ejecutar_SQL_SP($sql);

     //TODO: En el BuscarFecha va un Co.Fecha, de donde se obtiene?
    $sql = "DELETE *
            FROM Asiento_SC
            WHERE TC = '".$parametros['SubCta']."'
            AND Cta = '".$parametros['SubCtaGen']."'
            AND DH = '".$parametros['OpcDH']."'
            AND TM = '".$parametros['OpcTM']."'
            AND T_No = ".$_SESSION['INGRESO']['modulo_']." 
            AND Item = '".$_SESSION['INGRESO']['item']."'
            AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
            AND Bloquear <> 0
            AND Fecha_D <= '" . BuscarFecha(date('Y-m-d')) . "'
            AND Fecha_H >= '" . BuscarFecha(date('Y-m-d')) . "'";
    Ejecutar_SQL_SP($sql);
    
  }

  function commandl_click($parametros){

    $sql = "DELETE * 
            FROM Asiento_SC 
            WHERE TC = '".$parametros['SubCta']."' 
            AND Cta = '".$parametros['SubCtaGen']."' 
            AND DH = '".$parametros['OpcDH']."' 
            AND TM = '".$parametros['OpcTM']."' 
            AND T_No = ".$_SESSION['INGRESO']['modulo_']." 
            AND Item = '".$_SESSION['INGRESO']['item']."' 
            AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
            AND Valor = 0";
    Ejecutar_SQL_SP($sql);
    $sql = "UPDATE Asiento_SC 
            SET Valor = ROUND(Valor,2,0) 
            WHERE TC = '".$parametros['SubCta']."' 
            AND Cta = '".$parametros['SubCtaGen']."' 
            AND DH = '".$parametros['OpcDH']."' 
            AND TM = '".$parametros['OpcTM']."' 
            AND T_No = ".$_SESSION['INGRESO']['modulo_']." 
            AND Item = '".$_SESSION['INGRESO']['item']."' 
            AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'";
    Ejecutar_SQL_SP($sql);
  }

  function command2_click($parametros){
    
    $sql = "DELETE * 
            FROM Asiento_SC 
            WHERE TC = '".$parametros['SubCta']."' 
            AND Cta = '".$parametros['SubCtaGen']."' 
            AND DH = '".$parametros['OpcDH']."' 
            AND TM = '".$parametros['OpcTM']."' 
            AND T_No = ".$_SESSION['INGRESO']['modulo_']." 
            AND Item = '".$_SESSION['INGRESO']['item']."' 
            AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'";
    Ejecutar_SQL_SP($sql);
  }


  function ExistenMovimientos($Trans_No)
  {
    	$cid = $this->conn;
  		$SQL1 = "SELECT Item, CodigoU, T_No
      	FROM Asiento
      	WHERE Item = '".$_SESSION['INGRESO']['item']. "'
      	AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'
      	AND T_No = " .$Trans_No. "
      	UNION
      	SELECT Item, CodigoU, T_No
      	FROM Asiento_SC
      	WHERE Item = '".$_SESSION['INGRESO']['item']. "'
      	AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'
      	AND T_No = " .$Trans_No. "
      	UNION
      	SELECT Item, CodigoU, T_No
      	FROM Asiento_B
      	WHERE Item = '".$_SESSION['INGRESO']['item']. "'
      	AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'
      	AND T_No = " .$Trans_No. "
      	UNION
      	SELECT Item, CodigoU, T_No
      	FROM Asiento_R
      	WHERE Item = '".$_SESSION['INGRESO']['item']. "'
      	AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'
      	AND T_No = " .$Trans_No. " ";
       
	  $SQL2 = "SELECT Item, CodigoU, T_No 
	     FROM Asiento_RP 
	     WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
	     AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "' 
	     AND T_No = " .$Trans_No. " 
	     UNION 
	     SELECT Item, CodigoU, T_No 
	     FROM Asiento_K 
	     WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
	     AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "' 
	     AND T_No = " .$Trans_No. " 
	     UNION 
	     SELECT Item, CodigoU, T_No 
	     FROM Asiento_P 
	     WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
	     AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "' 
	     AND T_No = " .$Trans_No. " 
	     UNION 
	     SELECT Item, CodigoU, T_No 
	     FROM Asiento_Air 
	     WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
	     AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "' 
	     AND T_No = " .$Trans_No. " ";
       
	  $SQL3 = "SELECT Item, CodigoU, T_No 
	     FROM Asiento_Compras 
	     WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
	     AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "' 
	     AND T_No = " .$Trans_No. " 
	     UNION 
	     SELECT Item, CodigoU, T_No 
	     FROM Asiento_Exportaciones 
	     WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
	     AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "' 
	     AND T_No = " .$Trans_No. " 
	     UNION 
	     SELECT Item, CodigoU, T_No 
	     FROM Asiento_Importaciones 
	     WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
	     AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "' 
	     AND T_No = " .$Trans_No. " 
	     UNION 
	     SELECT Item, CodigoU, T_No 
	     FROM Asiento_Ventas 
	     WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
	     AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "' 
	     AND T_No = " .$Trans_No. " ";

	   $sql = $SQL1 ." UNION ".$SQL2." UNION ".$SQL3;

	   // print_r($sql);die();
	   return  $cid->datos($sql);
  }
  function Transacciones_BA($Cta_Aux,$MBoxFecha)
  {
  	 $sql = "SELECT MAX(Cheq_Dep) As Ultimo_Chep 
	        FROM Transacciones 
	        WHERE Item = '".$_SESSION['INGRESO']['item']."' 
	        AND Periodo = '".$_SESSION['INGRESO']['periodo']. "' 
	        AND Cta = '".$Cta_Aux."' 
	        AND TP = 'CE' 
	        AND ISNUMERIC(Cheq_Dep) <> 0 
	        AND Haber > 0 
	        AND Fecha <= '".$MBoxFecha."' ";
	   return  $this->conn->datos($sql);
  }

  function Facturas_Pendientes_SC($AgruparSubMod,$SubCta,$Codigo,$cta,$fecha)
	{
		$sql = '';
	    // $FechaTexto = MBoxFechaV
	    // If Not IsDate(FechaTexto) Then FechaTexto = FechaSistema
	    if($AgruparSubMod){
	       switch ($SubCta) {	        	
	         Case "C": $sql = "SELECT Factura,Detalle_SubCta,(SUM(Debitos)-SUM(Creditos)) As Saldos_MN,SUM(Parcial_ME) As Saldos_ME ";
	         break;
	         Case "P": $sql = "SELECT Factura,Detalle_SubCta,(SUM(Creditos)-SUM(Debitos)) As Saldos_MN,-SUM(Parcial_ME) As Saldos_ME ";
	         break;
	         default:
	          $sql = "SELECT Factura,Detalle_SubCta,(SUM(Debitos)-SUM(Creditos)) As Saldos_MN,-SUM(Parcial_ME) As Saldos_ME ";
	         break;
	       }
	    }else{
	    	switch ($SubCta) {	    		
	         Case "C": $sql = "SELECT Factura,(SUM(Debitos)-SUM(Creditos)) As Saldos_MN,SUM(Parcial_ME) As Saldos_ME ";
	         break;
	         Case "P": $sql = "SELECT Factura,(SUM(Creditos)-SUM(Debitos)) As Saldos_MN,-SUM(Parcial_ME) As Saldos_ME ";
	         break;
	         default:
	          $sql = "SELECT Factura,(SUM(Debitos)-SUM(Creditos)) As Saldos_MN,-SUM(Parcial_ME) As Saldos_ME ";
	         break;
	     }
	    }
	    $sql = $sql." FROM Trans_SubCtas 
	         WHERE Codigo = '".$Codigo."' 
	         AND TC = '".$SubCta."' 
	         AND Cta = '".$cta."' 
	         AND Item = '".$_SESSION['INGRESO']['item']."' 
	         AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
	         AND Fecha <= '".BuscarFecha($fecha)."' 
	         AND T <> 'A' ";
	    if($AgruparSubMod){
	       $sql = $sql." GROUP BY Factura,Detalle_SubCta ";
	    }else{
	       $sql = $sql." GROUP BY Factura ";
	    }
	    switch ($SubCta) {	    	
	      Case "C": 
	      $sql.=" HAVING SUM(Debitos)-SUM(Creditos) > 0 ";
	      break;
	      Case "P": 
	      $sql.=" HAVING SUM(Creditos)-SUM(Debitos) > 0 ";
	      break;
	      default: 
	      	$sql.=" HAVING SUM(Debitos)-SUM(Creditos) = 0 ";
	      	break;
	    }
	    $sql.=" ORDER BY Factura ";

	   return $this->conn->datos($sql);
	   
	}

	function catalogo_lineas($TC,$SerieFactura)
  {
  	$sql = "SELECT *
         FROM Catalogo_Lineas
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
         AND Fact = '".$TC."'
         AND Serie = '".$SerieFactura."'
         AND Autorizacion = '".$_SESSION['INGRESO']['RUC']."'
         AND TL <> 0
         ORDER BY Codigo ";
         // print_r($sql);die();
	  return $this->conn->datos($sql);

  }


}
?>