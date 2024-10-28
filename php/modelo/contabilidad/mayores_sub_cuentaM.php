<?php
include(dirname(__DIR__,2).'/funciones/funciones.php');
@session_start(); 
/**
 * 
 */
class mayores_sub_cuentaM
{	
		
    private $db ;
	function __construct()
	{
	   $this->db = new db();
	}

	function usuario()
	{
		$sql = "SELECT Codigo,(Nombre_Completo+'  '+ Codigo) As CodUsuario 
       	FROM Accesos 
       	WHERE Codigo <> '*' 
       	ORDER BY Nombre_Completo ";
       	return $this->db->datos($sql);
	}

	function sucursal()
	{
		$sql= "SELECT (Sucursal +'  ' + Empresa) As NomEmpresa,Sucursal as 'Item'
		FROM Acceso_Sucursales
		INNER JOIN Empresas ON Acceso_Sucursales.Sucursal = Empresas.Item
		WHERE Acceso_Sucursales.Item ='".$_SESSION['INGRESO']['item']."'
		ORDER BY Acceso_Sucursales.Item,Empresa";


		 // $sql = "SELECT (Item & '  ' & Empresa) As NomEmpresa 
   //        FROM Empresas 
   //        WHERE Item IN ('" & ListSucursales & "') 
   //        ORDER BY Item,Empresa ";
          return $this->db->datos($sql);
	}

	function lista_cuentas($TipoM,$DCCtas)
	{
		$Codigo = $DCCtas;
		  if($Codigo == ""){$Codigo = G_NINGUNO;}
		  switch ($TipoM) {
		  	case 'C':
		  	case 'P':

		  	 $sql = "SELECT C.Cliente As Nombre_Cta,C.Codigo 
		            FROM Catalogo_CxCxP As CP,Clientes As C 
		            WHERE CP.TC = '".$TipoM."' 
		            AND CP.Item = '".$_SESSION['INGRESO']['item']."' 
		            AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		            AND CP.Cta = '".$Codigo."' 
		            AND CP.Codigo = C.Codigo 
		            GROUP BY C.Cliente,C.Codigo ";

		  		break;
		  	case "I":
		  	case "G":
		  	case "PM":
		  	case "CC":
		  	 $sql = "SELECT Detalle As Nombre_Cta,Codigo 
		             FROM Catalogo_SubCtas 
		             WHERE TC = '".$TipoM."' 
		             AND Item = '".$_SESSION['INGRESO']['item']."' 
		             AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		             ORDER BY Detalle,Codigo ";
		  	break;	  	
		  	
		  }
		return $this->db->datos($sql);	          
	}

	function Ctas_SubMod($TipoM,$codigo=false)
	{
	  $sql = "SELECT Codigo,Codigo+ '    ' +Cuenta As Nombre_Cta 
	       FROM Catalogo_Cuentas
	       WHERE TC = '".$TipoM."'
	       AND Item = '".$_SESSION['INGRESO']['item']."' 
	       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
	       if($codigo)
	       {
	         $sql.=" AND Codigo = '".$codigo."'";
	  	}
	       $sql.=" ORDER BY Codigo ";
	       // print_r($sql);die();
		return $this->db->datos($sql);	

	}

	function Consultar_Un_Submodulo($parametro)
	{
		$Codigo1 = $parametro['DLCtas'];

	// print_r($parametro);die();
	// 'Consultamos el SubModulo
	     if($Codigo1 == ""){$Codigo1 = G_NINGUNO;}
	     $sql='';
	     switch ($parametro['tipoM']) {
	     	case 'C':
	     	case 'P':
	     	$sql.= "SELECT TSC.Cta,TSC.Fecha,TSC.TP,TSC.Numero,C.Cliente,Concepto,Debitos,Creditos,";
	     		break;
	     	default:
	     	$sql = "SELECT TSC.Cta,TSC.Fecha,TSC.TP,TSC.Numero,C.Detalle As Cliente,Concepto,Debitos,Creditos,";
	     		break;
	     }

	     if($parametro['tipoM'] == "PM"){
	        $sql.=" TSC.Saldo_MN,TSC.Prima,";
	     }else{
	        $sql.=" TSC.Saldo_MN,TSC.Factura,";
	     }

	     switch ($parametro['tipoM']) {
	     	case 'C':
	     	case 'P':
	     	  $sql.="Parcial_ME,TSC.Detalle_SubCta,TSC.Fecha_V,TSC.Codigo,TSC.Item 
                 FROM Trans_SubCtas As TSC,Comprobantes As Co,Clientes As C 
                 WHERE TSC.Fecha BETWEEN '".$parametro['desde']."' and '".$parametro['hasta']."' ";

                 $from = 'Trans_SubCtas As TSC,Comprobantes As Co,Clientes As C'; 
	     		break;
	     	
	     	default:
	     	$sql.="Parcial_ME,TSC.Detalle_SubCta,TSC.Fecha_V,TSC.Codigo,TSC.Item 
                 FROM Trans_SubCtas As TSC,Comprobantes As Co,Catalogo_SubCtas As C
                 WHERE TSC.Fecha BETWEEN '".$parametro['desde']."' and '".$parametro['hasta']."' ";
                 $from = 'Trans_SubCtas As TSC,Comprobantes As Co,Catalogo_SubCtas As C';
	     		break;
	     }


     if($parametro['unoTodos']==1){
        $sql.=" AND TSC.Cta = '".$parametro['DCCtas']."' 
             AND TSC.Codigo = '".$Codigo1."' ";
     }

     if($parametro['estado'] =='N'){
        $sql.=" AND TSC.T = 'N' ";
     }else If($parametro['estado']=='A'){
        $sql.=" AND TSC.T = 'A' ";
     }
     if( $parametro['checkagencia'] == 'true'){
        $sql.= " AND TSC.Item = '".$parametro['agencia']."' ";
     }else{
        $sql.=" AND TSC.Item = '".$_SESSION['INGRESO']['item']."' ";
     }

     if($parametro['checkusu'] == 'true'){ $sql.=" AND Co.CodigoU = '".$parametro['usuario']."' ";}
     $sql.= " AND TSC.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          AND TSC.TC = '".$parametro['tipoM']."' 
          AND Co.TP = TSC.TP 
          AND Co.Numero = TSC.Numero 
          AND Co.Item = TSC.Item 
          AND Co.Periodo = TSC.Periodo ";


          switch ($parametro['tipoM']) {
          	case 'C':
          	case 'P':
          		// code...
          		break;
          	
          	default:
          	$sql.=" AND C.Item = TSC.Item 
                 AND C.Periodo = TSC.Periodo ";
          		break;
          }
     
     $sql.=" AND TSC.Codigo = C.Codigo 
          ORDER BY TSC.Codigo,TSC.Cta,TSC.Fecha,TSC.TP,TSC.Numero,Factura,Debitos DESC,Creditos,TSC.ID ";

     $medida = medida_pantalla($_SESSION['INGRESO']['Height_pantalla'])-245;
     $tbl = grilla_generica_new($sql,$from,'tbl_lib',false,$botones=false,$check=false,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,$medida);

          // print_r($parametro);
          // print_r($sql);die();

       $datos = $this->db->datos($sql);


     // DGMayor.Visible = False
     $Debe = 0;
     $Haber = 0;
     $Saldo = 0;
     $SaldoAnterior = 0;
     $Cta = G_NINGUNO;
     foreach ($datos as $key => $value) {
     	$Debe+= $value["Debitos"];
       $Haber+= $value["Creditos"];
       $Saldo = $value["Saldo_MN"];
       $Cta = $value["Cta"];
     }
     $SaldoAnt = CalculosSaldoAnt($Cta,$Debe,$Haber,$Saldo);

     return array('tbl'=>$tbl,'SaldoAnt'=>$SaldoAnt,'Debe'=>number_format($Debe,2,'.',''),'Haber'=>number_format($Haber,2,'.',''),'Saldo'=>number_format($Saldo,2,'.',''));

     // print_r($datos);
     // print_r($SaldoAnt);die();

   }


   function Consultar_Un_Submodulo_datos($parametro)
	{
		$Codigo1 = $parametro['DLCtas'];

	// print_r($parametro);die();
	// 'Consultamos el SubModulo
	     if($Codigo1 == ""){$Codigo1 = G_NINGUNO;}
	     $sql='';
	     switch ($parametro['tipoM']) {
	     	case 'C':
	     	case 'P':
	     	$sql.= "SELECT TSC.Cta,TSC.Fecha,TSC.TP,TSC.Numero,C.Cliente,Concepto,Debitos,Creditos,";
	     		break;
	     	default:
	     	$sql = "SELECT TSC.Cta,TSC.Fecha,TSC.TP,TSC.Numero,C.Detalle As Cliente,Concepto,Debitos,Creditos,";
	     		break;
	     }

	     if($parametro['tipoM'] == "PM"){
	        $sql.=" TSC.Saldo_MN,TSC.Prima,";
	     }else{
	        $sql.=" TSC.Saldo_MN,TSC.Factura,";
	     }

	     switch ($parametro['tipoM']) {
	     	case 'C':
	     	case 'P':
	     	  $sql.="Parcial_ME,TSC.Detalle_SubCta,TSC.Fecha_V,TSC.Codigo,TSC.Item 
                 FROM Trans_SubCtas As TSC,Comprobantes As Co,Clientes As C 
                 WHERE TSC.Fecha BETWEEN '".$parametro['desde']."' and '".$parametro['hasta']."' ";

                 $from = 'Trans_SubCtas As TSC,Comprobantes As Co,Clientes As C'; 
	     		break;
	     	
	     	default:
	     	$sql.="Parcial_ME,TSC.Detalle_SubCta,TSC.Fecha_V,TSC.Codigo,TSC.Item 
                 FROM Trans_SubCtas As TSC,Comprobantes As Co,Catalogo_SubCtas As C
                 WHERE TSC.Fecha BETWEEN '".$parametro['desde']."' and '".$parametro['hasta']."' ";
                 $from = 'Trans_SubCtas As TSC,Comprobantes As Co,Catalogo_SubCtas As C';
	     		break;
	     }


     if($parametro['unoTodos']==1){
        $sql.=" AND TSC.Cta = '".$parametro['DCCtas']."' 
             AND TSC.Codigo = '".$Codigo1."' ";
     }

     if($parametro['estado'] =='N'){
        $sql.=" AND TSC.T = 'N' ";
     }else If($parametro['estado']=='A'){
        $sql.=" AND TSC.T = 'A' ";
     }
     if( $parametro['checkagencia'] == 'true'){
        $sql.= " AND TSC.Item = '".$parametro['agencia']."' ";
     }else{
        $sql.=" AND TSC.Item = '".$_SESSION['INGRESO']['item']."' ";
     }

     if($parametro['checkusu'] == 'true'){ $sql.=" AND Co.CodigoU = '".$parametro['usuario']."' ";}
     $sql.= " AND TSC.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          AND TSC.TC = '".$parametro['tipoM']."' 
          AND Co.TP = TSC.TP 
          AND Co.Numero = TSC.Numero 
          AND Co.Item = TSC.Item 
          AND Co.Periodo = TSC.Periodo ";


          switch ($parametro['tipoM']) {
          	case 'C':
          	case 'P':
          		// code...
          		break;
          	
          	default:
          	$sql.=" AND C.Item = TSC.Item 
                 AND C.Periodo = TSC.Periodo ";
          		break;
          }
     
     $sql.=" AND TSC.Codigo = C.Codigo 
          ORDER BY TSC.Codigo,TSC.Cta,TSC.Fecha,TSC.TP,TSC.Numero,Factura,Debitos DESC,Creditos,TSC.ID ";


     // $tbl = grilla_generica_new($sql,$from,'tbl_lib',false,$botones=false,$check=false,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,250);

          // print_r($parametro);
          // print_r($sql);die();

       $datos = $this->db->datos($sql);


     // DGMayor.Visible = False
     $Debe = 0;
     $Haber = 0;
     $Saldo = 0;
     $SaldoAnterior = 0;
     $Cta = G_NINGUNO;
     foreach ($datos as $key => $value) {
     	$Debe+= $value["Debitos"];
       $Haber+= $value["Creditos"];
       $Saldo = $value["Saldo_MN"];
       $Cta = $value["Cta"];
     }
     $SaldoAnt = CalculosSaldoAnt($Cta,$Debe,$Haber,$Saldo);

     return array('datos'=>$datos,'SaldoAnt'=>$SaldoAnt,'Debe'=>number_format($Debe,2,'.',''),'Haber'=>number_format($Haber,2,'.',''),'Saldo'=>number_format($Saldo,2,'.',''));

     // print_r($datos);
     // print_r($SaldoAnt);die();

   }

}
?>