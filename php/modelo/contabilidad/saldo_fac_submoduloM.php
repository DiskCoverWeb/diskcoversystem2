<?php
include(dirname(__DIR__,2).'/funciones/funciones.php');//
@session_start(); 

/**
 * 
 */
class Saldo_fac_sub_C 
{
	private $conn ;
	function __construct()
	{
	   $this->conn = new db();
	}
	function mensaje()
	{
		$lista =array();
		for ($i=0; $i <10 ; $i++) { 
			$lista[] = $i;
		}
	  return $lista;

		//var_dump($lista);

	}
	function select_cta($tipocuenta)
	{
		$sql = "SELECT (TS.Cta+' '+CC.Cuenta) As Nombre_Cta
       FROM Catalogo_Cuentas As CC, Trans_SubCtas As TS 
       WHERE CC.Item = '".$_SESSION['INGRESO']['item']."'
       AND CC.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       AND CC.TC = '".$tipocuenta."'
       AND CC.Codigo = TS.Cta 
       AND CC.Item = TS.Item 
       AND CC.Periodo = TS.Periodo
       AND CC.TC = TS.TC 
       GROUP BY TS.Cta,CC.Cuenta
       ORDER BY TS.Cta ";

       return $this->conn->datos($sql);


	}
	function select_det($tipocuenta)
	{
		$sql = "SELECT Detalle_SubCta FROM Trans_SubCtas 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       AND TC = '".$tipocuenta."'
       GROUP BY Detalle_SubCta
       ORDER BY Detalle_SubCta ";
       return $this->conn->datos($sql);
  
	}

	function select_beneficiario($tipocuenta)
	{
		if($tipocuenta=='C' || $tipocuenta=='P')
		{
         $sql = "SELECT C.Cliente As Cliente,TS.Codigo as Codigo  
         FROM Trans_SubCtas As TS,Clientes As C
              WHERE TS.Item = '".$_SESSION['INGRESO']['item']."' 
              AND TS.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
              AND TS.TC = '".$tipocuenta."'
              AND TS.Codigo = C.Codigo 
              GROUP BY C.Cliente,TS.Codigo 
              ORDER BY C.Cliente,TS.Codigo ";
           }
           else if($tipocuenta=='G' || $tipocuenta =='I' || $tipocuenta=='CC')
           {
          // 	print_r('expression');
            $sql = "SELECT C.Detalle As Cliente,TS.Codigo as Codigo 
            FROM Trans_SubCtas As TS,Catalogo_SubCtas As C 
              WHERE TS.Item = '".$_SESSION['INGRESO']['item']."' 
              AND TS.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
              AND TS.TC = '".$tipocuenta."'
              AND TS.Codigo = C.Codigo 
              AND TS.TC = C.TC 
              AND TS.Item = C.Item 
              AND TS.Periodo = C.Periodo 
              GROUP BY C.Detalle,TS.Codigo 
              ORDER BY C.Detalle,TS.Codigo ";
          }

          $result = array();
          $datos = $this->conn->datos($sql);
          foreach ($datos as $key => $value) {
              $result[]=  array('Cliente'=>$value['Cliente'],'Codigo'=>$value['Codigo']);
          }
       
       return $result;



	}

    function cargar_consulta_x_meses($tabla=false)
    {
        $sql = "SELECT Cta, Beneficiario, Anio, Mes, Valor_x_Mes, Categoria
            FROM Reporte_CxCxP_x_Meses
            WHERE Item ='".$_SESSION['INGRESO']['item']."'
            AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
            ORDER BY Beneficiario, Anio, Mes_No";
            if($tabla)
            {
                $medida = medida_pantalla($_SESSION['INGRESO']['Height_pantalla'])-115;
                $datos = grilla_generica_new($sql,' Reporte_CxCxP_x_Meses','',$titulo=false,$botones=false,$check=false,$imagen=false,1,1,1,$medida);
            }else
            {
                $datos = $this->conn->datos($sql);
            }
        return $datos;
    }

	function consulta_c_p_datos($tipocuenta,$ChecksubCta,$OpcP,$CheqCta,$CheqDet,$CheqIndiv,$fechaini,$fechafin,$Cta,$CodigoCli,$DCDet,$reporte=false)
	{

         $sql = "SELECT CC.Cuenta,C.Cliente,C.Telefono,TS.Factura,MIN(TS.Fecha) As Fecha_Emi,MIN(TS.Fecha_V) As Fecha_Ven,";
         if($ChecksubCta == 'true')
         {
         	$sql.= "TS.Detalle_SubCta As Beneficiario,";
         }
         if($tipocuenta == 'C')
         {
         	 $sql.= "SUM(TS.Debitos) As Total,SUM(TS.Creditos) As Abonos,SUM(TS.Debitos-TS.Creditos) As Saldo,";
                $SQL1 = "HAVING SUM(TS.Debitos-TS.Creditos) ";
         	
         }
         if($tipocuenta == 'P')
         {
         	$sql.="SUM(TS.Creditos) As Total,SUM(TS.Debitos) As Abonos,SUM(TS.Creditos-TS.Debitos) As Saldo,";
            $SQL1 = "HAVING SUM(TS.Creditos-TS.Debitos) ";

         }
        //  If OpcP.value Then SQL1 = SQL1 & " <> 0 " Else SQL1 = SQL1 & " = 0 "
         if($OpcP=='true')
         {
         	$SQL1.=" <> 0 ";
         }else
         {
         	$SQL1.=" = 0 ";
         }
        
        $sql.="TS.TC,TS.Codigo,TS.Cta 
              FROM Clientes As C, Catalogo_Cuentas As CC, Trans_SubCtas As TS
              WHERE TS.Fecha BETWEEN '".$fechaini."' AND '".$fechafin."'
              AND TS.Item = '".$_SESSION['INGRESO']['item']."' 
              AND TS.Periodo =  '".$_SESSION['INGRESO']['periodo']."' 
              AND TS.TC = '".$tipocuenta."' ";

         // If CheqCta.value = 1 Then sSQL = sSQL & "AND CC.Codigo = '" & Cta & "' "    

        if($CheqCta =='true')
        {
        	$sql.="AND CC.Codigo = '".$Cta."' ";
        }

        // If CheqIndiv.value = 1 Then sSQL = sSQL & "AND TS.Codigo = '" & CodigoCli & "' "
        if($CheqIndiv=='true')
        {
        	$sql.="AND TS.Codigo = '".$CodigoCli."' ";
        }

        // If CheqDet.value = 1 Then sSQL = sSQL & "AND TS.Detalle_SubCta = '" & DCDet & "' "
        if($CheqDet =='true')
        {
        	"AND TS.Detalle_SubCta = '".$DCDet."' ";
        }

         $sql .="AND TS.Codigo = C.Codigo AND TS.Cta = CC.Codigo AND TS.Item = CC.Item AND TS.Periodo = CC.Periodo ";

         if($ChecksubCta =='true')
         {
         	$sql.="GROUP BY C.Cliente,TS.Codigo,CC.Cuenta,TS.Factura,C.Telefono,TS.Detalle_SubCta,TS.TC,TS.Cta "
         	      .$SQL1.
         	      "ORDER BY CC.Cuenta,C.Cliente,TS.Detalle_SubCta,TS.Factura ";
         }else
         {
         	$sql.="GROUP BY C.Cliente,TS.Codigo,CC.Cuenta,TS.Factura,C.Telefono,TS.TC,TS.Cta "
         	      .$SQL1.
         	       "ORDER BY CC.Cuenta,C.Cliente,TS.Factura ";

         }

         // print_r($sql);die();
        

        $datos = $this->conn->datos($sql);
	    if($reporte==false)
	    {
            // $datos = $this->conn->datos($sql);
          return $datos;
	    }else
	    {
             $titulo = 'B A L A N C E   D E   C O M P R O B A C I O N';
             $tablaHTML =array();
             $tablaHTML[0]['medidas']=array(30,50,30,20,20,20,20,20,20,20,20,20);
             $tablaHTML[0]['datos']=array('Cuenta','Cliente','Telefono','Factura','Fecha_Emi','Fecha_Ven','Total','Abonos','Saldo','TC','Codigo','Cta');
             $tablaHTML[0]['tipo'] ='C';
             $pos = 1;
             $compro1='';
            foreach ($datos as $key => $value) {
                  $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
                  $tablaHTML[$pos]['datos']=array($value['Cuenta'],$value['Cliente'],$value['Telefono'],$value['Factura'],$value['Fecha_Emi']->format('Y-m-d'),$value['Fecha_Ven']->format('Y-m-d'),$value['Total'],$value['Abonos'],$value['Saldo'],$value['TC'],$value['Codigo'],$value['Cta']);
                  $tablaHTML[$pos]['tipo'] ='N';          
                  $pos+=1;
            }
          excel_generico($titulo,$tablaHTML);  

	    }
      
     
	}
	
	function consulta_c_p_tabla($tipocuenta,$ChecksubCta,$OpcP,$CheqCta,$CheqDet,$CheqIndiv,$fechaini,$fechafin,$Cta,$CodigoCli,$DCDet)
	{

         $sql = "SELECT CC.Cuenta,C.Cliente,C.Telefono,TS.Factura,MIN(TS.Fecha) As Fecha_Emi,MIN(TS.Fecha_V) As Fecha_Ven,";
         if($ChecksubCta == 'true')
         {
         	$sql.= "TS.Detalle_SubCta As Beneficiario,";
         }
         if($tipocuenta == 'C')
         {
         	 $sql.= "SUM(TS.Debitos) As Total,SUM(TS.Creditos) As Abonos,SUM(TS.Debitos-TS.Creditos) As Saldo,";
                $SQL1 = "HAVING SUM(TS.Debitos-TS.Creditos) ";
         	
         }
         if($tipocuenta == 'P')
         {
         	$sql.="SUM(TS.Creditos) As Total,SUM(TS.Debitos) As Abonos,SUM(TS.Creditos-TS.Debitos) As Saldo,";
            $SQL1 = "HAVING SUM(TS.Creditos-TS.Debitos) ";

         }
        //  If OpcP.value Then SQL1 = SQL1 & " <> 0 " Else SQL1 = SQL1 & " = 0 "//
         if($OpcP=='true')
         {
         	$SQL1.=" <> 0 ";
         }else
         {
         	$SQL1.=" = 0 ";
         }
        
        $sql.="TS.TC,TS.Codigo,TS.Cta 
              FROM Clientes As C, Catalogo_Cuentas As CC, Trans_SubCtas As TS
              WHERE TS.Fecha BETWEEN '".$fechaini."' AND '".$fechafin."'
              AND TS.Item = '".$_SESSION['INGRESO']['item']."' 
              AND TS.Periodo =  '".$_SESSION['INGRESO']['periodo']."' 
              AND TS.TC = '".$tipocuenta."' ";

         // If CheqCta.value = 1 Then sSQL = sSQL & "AND CC.Codigo = '" & Cta & "' "    

        if($CheqCta =='true')
        {
        	$sql.="AND CC.Codigo = '".$Cta."' ";
        }

        // If CheqIndiv.value = 1 Then sSQL = sSQL & "AND TS.Codigo = '" & CodigoCli & "' "
        if($CheqIndiv=='true')
        {
        	$sql.="AND TS.Codigo = '".$CodigoCli."' ";
        }

        // If CheqDet.value = 1 Then sSQL = sSQL & "AND TS.Detalle_SubCta = '" & DCDet & "' "
        if($CheqDet =='true')
        {
        	"AND TS.Detalle_SubCta = '".$DCDet."' ";
        }

         $sql .="AND TS.Codigo = C.Codigo AND TS.Cta = CC.Codigo AND TS.Item = CC.Item AND TS.Periodo = CC.Periodo ";

         if($ChecksubCta =='true')
         {
         	$sql.="GROUP BY C.Cliente,TS.Codigo,CC.Cuenta,TS.Factura,C.Telefono,TS.Detalle_SubCta,TS.TC,TS.Cta "
         	      .$SQL1.
         	      "ORDER BY CC.Cuenta,C.Cliente,TS.Detalle_SubCta,TS.Factura ";
         }else
         {
         	$sql.="GROUP BY C.Cliente,TS.Codigo,CC.Cuenta,TS.Factura,C.Telefono,TS.TC,TS.Cta "
         	      .$SQL1.
         	       "ORDER BY CC.Cuenta,C.Cliente,TS.Factura ";

         }

         // print_r($sql);die();

        // $botones[0] = array('boton'=>'Eliminar linea', 'icono'=>'<i class="fa fa-trash"></i>', 'tipo'=>'danger', 'id'=>'A_No,CODIGO' );

        $medida = medida_pantalla($_SESSION['INGRESO']['Height_pantalla'])-115;
        $datos = grilla_generica_new($sql,' Clientes As C, Catalogo_Cuentas As CC, Trans_SubCtas As TS','',$titulo=false,$botones=false,$check=false,$imagen=false,1,1,1,$medida);

        // print_r($datos);die();


        return $datos;
     
	}


	 function consulta_ing_egre_datos($tipocuenta,$ChecksubCta,$OpcP,$CheqCta,$CheqDet,$CheqIndiv,$fechaini,$fechafin,$Cta,$CodigoCli,$DCDet,$reporte=false)
   {
   	   $sql= "SELECT CC.Cuenta,C.Detalle As Sub_Modulos,MIN(TS.Fecha) As Fecha_Emi,";

       //If CheqDSubCta.value = 1 Then sSQL = sSQL & "TS.Detalle_SubCta As Beneficiario,"
   	   if($ChecksubCta=='true')
   	   {
   	   	$sql.="TS.Detalle_SubCta As Beneficiario,";

   	   }
   	   /*Select Case TipoCta
           Case "I"
                sSQL = sSQL & "SUM(TS.Creditos) As Total,"
           Case "G"
                sSQL = sSQL & "SUM(TS.Debitos) As Total,"
         End Select*/

   	   if($tipocuenta =='I')
   	   {
   	   	$sql.="SUM(TS.Creditos) As Total,";
   	   }
   	   if($tipocuenta=='G')
   	   {
   	   	 $sql.="SUM(TS.Debitos) As Total,";
   	   }

       /*  sSQL = sSQL & "TS.TC,TS.Codigo,TS.Cta " _
              & "FROM Catalogo_SubCtas As C, Catalogo_Cuentas As CC, Trans_SubCtas As TS " _
              & "WHERE TS.Fecha BETWEEN #" & FechaInicial & "# AND #" & FechaFinal & "# " _
              & "AND TS.Item = '" & NumEmpresa & "' " _
              & "AND TS.Periodo = '" & Periodo_Contable & "' " _
              & "AND TS.TC = '" & TipoCta & "' "*/
        
        $sql.= "TS.TC,TS.Codigo,TS.Cta  FROM Catalogo_SubCtas As C, Catalogo_Cuentas As CC, Trans_SubCtas As TS
              WHERE TS.Fecha BETWEEN '".$fechaini."' AND '".$fechafin."'
              AND TS.Item = '".$_SESSION['INGRESO']['item']."' 
              AND TS.Periodo =  '".$_SESSION['INGRESO']['periodo']."' 
               AND TS.TC = '".$tipocuenta."'";

         //If CheqCta.value = 1 Then sSQL = sSQL & "AND CC.Codigo = '" & Cta & "' "
         if($CheqCta=='true')
         {
         	$sql.="AND CC.Codigo = '".$Cta. "' ";
         }

        // If CheqIndiv.value = 1 Then sSQL = sSQL & "AND TS.Codigo = '" & CodigoCli & "' "
          if($CheqIndiv=='true')
          {
          	$sql.= "AND TS.Codigo = '".$CodigoCli."' ";
          }

         //If CheqDet.value = 1 Then sSQL = sSQL & "AND TS.Detalle_SubCta = '" & DCDet & "' "
          if($CheqDet=='true')
          {
          	$sql.="AND TS.Detalle_SubCta = '".$DCDet."'";
          }

          $sql.="AND TS.Codigo = C.Codigo 
              AND TS.Cta = CC.Codigo
              AND TS.Item = CC.Item 
              AND TS.Periodo = CC.Periodo
              GROUP BY CC.Cuenta,C.Detalle,TS.Detalle_SubCta,TS.TC,TS.Codigo,TS.Cta
              ORDER BY CC.Cuenta,C.Detalle,TS.Detalle_SubCta ";
       // echo $sql;       

        
         // print_r($sql);die();
        $datos = $this->conn->datos($sql);
       if($reporte==false)
	    {
          return $datos;
	    }else
	    {
              $titulo = 'S A L D O   D E   E G R E S O';
             $tablaHTML =array();
             $tablaHTML[0]['medidas']=array(50,50,20,20,10,20,20);
             $tablaHTML[0]['datos']=array('Cuenta','Sub_Modulos','Fecha_Emi','Total','TC','Codigo','Cta');
             $tablaHTML[0]['tipo'] ='C';
             $pos = 1;
             $compro1='';
            foreach ($datos as $key => $value) {
                  $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
                  $tablaHTML[$pos]['datos']=array($value['Cuenta'],$value['Sub_Modulos'],$value['Fecha_Emi']->format('Y-m-d'),$value['Total'],$value['TC'],$value['Codigo'],$value['Cta']);
                  $tablaHTML[$pos]['tipo'] ='N';          
                  $pos+=1;
            }
          excel_generico($titulo,$tablaHTML);  
	    }

   }



   function consulta_ing_egre_tabla($tipocuenta,$ChecksubCta,$OpcP,$CheqCta,$CheqDet,$CheqIndiv,$fechaini,$fechafin,$Cta,$CodigoCli,$DCDet)
   {
   	   $sql= "SELECT CC.Cuenta,C.Detalle As Sub_Modulos,MIN(TS.Fecha) As Fecha_Emi,";

       //If CheqDSubCta.value = 1 Then sSQL = sSQL & "TS.Detalle_SubCta As Beneficiario,"
   	   if($ChecksubCta=='true')
   	   {
   	   	$sql.="TS.Detalle_SubCta As Beneficiario,";

   	   }
   	   /*Select Case TipoCta
           Case "I"
                sSQL = sSQL & "SUM(TS.Creditos) As Total,"
           Case "G"
                sSQL = sSQL & "SUM(TS.Debitos) As Total,"
         End Select*/

   	   if($tipocuenta =='I')
   	   {
   	   	$sql.="SUM(TS.Creditos) As Total,";
   	   }
   	   if($tipocuenta=='G')
   	   {
   	   	 $sql.="SUM(TS.Debitos) As Total,";
   	   }

       /*  sSQL = sSQL & "TS.TC,TS.Codigo,TS.Cta " _
              & "FROM Catalogo_SubCtas As C, Catalogo_Cuentas As CC, Trans_SubCtas As TS " _
              & "WHERE TS.Fecha BETWEEN #" & FechaInicial & "# AND #" & FechaFinal & "# " _
              & "AND TS.Item = '" & NumEmpresa & "' " _
              & "AND TS.Periodo = '" & Periodo_Contable & "' " _
              & "AND TS.TC = '" & TipoCta & "' "*/
        
        $sql.= "TS.TC,TS.Codigo,TS.Cta  FROM Catalogo_SubCtas As C, Catalogo_Cuentas As CC, Trans_SubCtas As TS
              WHERE TS.Fecha BETWEEN '".$fechaini."' AND '".$fechafin."'
              AND TS.Item = '".$_SESSION['INGRESO']['item']."' 
              AND TS.Periodo =  '".$_SESSION['INGRESO']['periodo']."' 
               AND TS.TC = '".$tipocuenta."'";

         //If CheqCta.value = 1 Then sSQL = sSQL & "AND CC.Codigo = '" & Cta & "' "
         if($CheqCta=='true')
         {
         	$sql.="AND CC.Codigo = '".$Cta. "' ";
         }

        // If CheqIndiv.value = 1 Then sSQL = sSQL & "AND TS.Codigo = '" & CodigoCli & "' "
          if($CheqIndiv=='true')
          {
          	$sql.= "AND TS.Codigo = '".$CodigoCli."' ";
          }

         //If CheqDet.value = 1 Then sSQL = sSQL & "AND TS.Detalle_SubCta = '" & DCDet & "' "
          if($CheqDet=='true')
          {
          	$sql.="AND TS.Detalle_SubCta = '".$DCDet."'";
          }

          $sql.="AND TS.Codigo = C.Codigo 
              AND TS.Cta = CC.Codigo
              AND TS.Item = CC.Item 
              AND TS.Periodo = CC.Periodo
              GROUP BY CC.Cuenta,C.Detalle,TS.Detalle_SubCta,TS.TC,TS.Codigo,TS.Cta
              ORDER BY CC.Cuenta,C.Detalle,TS.Detalle_SubCta ";


         // print_r($sql);die();

        $medida = medida_pantalla($_SESSION['INGRESO']['Height_pantalla'])-115;
        $datos = grilla_generica_new($sql,'Catalogo_SubCtas As C, Catalogo_Cuentas As CC, Trans_SubCtas As TS','',$titulo=false,$botones=false,$check=false,$imagen=false,1,1,1,$medida);
        return $datos;

   }


   function eliminar_saldo_diario()
   {
   	 $sql= "DELETE FROM Saldo_Diarios
       WHERE CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND Item = '".$_SESSION['INGRESO']['item']."' 
       AND TP = 'CCXP'"; 

       return $this->conn->String_Sql($sql);
   }

   function tabla_temporizada($fechafin)
   {
   	$sql ="SELECT Dato_Aux1 As Cuenta,Comprobante As Cliente,Fecha_Venc,Numero As Factura,
       Ven_1_a_7 as 'Ven 1 a 7',
       Ven_8_a_30 as 'Ven 8 a 30',
       Ven_31_a_60 as 'Ven 31 a 60',
       Ven_61_a_90 as 'Ven 61 a 90',
       Ven_91_a_180 as 'Ven 91 a 180',
       Ven_181_a_360 as 'Ven 181 a 360',
       Ven_mas_de_360 as 'Ven mas de 360'
       FROM Saldo_Diarios
       WHERE Fecha_Venc <= '".$fechafin."'
       AND Item = '".$_SESSION['INGRESO']['item']."' 
       AND TP = 'CCXP'
       ORDER BY TC,Dato_Aux1,Comprobante,Cta,Numero";
       //echo $sql;


        $medida = medida_pantalla($_SESSION['INGRESO']['Height_pantalla'])-115;
        $datos = grilla_generica_new($sql,'Saldo_Diarios','',$titulo=false,$botones=false,$check=false,$imagen=false,1,1,1,$medida);
        return $datos;
   }

    function tabla_temporizada_datos($fechafin,$reporte=false)
   {
   	$sql ="SELECT Dato_Aux1 As Cuenta,Comprobante As Cliente,Fecha_Venc,Numero As Factura,
       Ven_1_a_7 as 'Ven 1 a 7',
       Ven_8_a_30 as 'Ven 8 a 30',
       Ven_31_a_60 as 'Ven 31 a 60',
       Ven_61_a_90 as 'Ven 61 a 90',
       Ven_91_a_180 as 'Ven 91 a 180',
       Ven_181_a_360 as 'Ven 181 a 360',
       Ven_mas_de_360 as 'Ven mas de 360'
       FROM Saldo_Diarios
       WHERE Fecha_Venc <= '".$fechafin."'
       AND Item = '".$_SESSION['INGRESO']['item']."' 
       AND TP = 'CCXP'
       ORDER BY TC,Dato_Aux1,Comprobante,Cta,Numero";
       $datos = $this->conn->datos($sql);
  
       if($reporte==false)
	    {
          return $datos; 
	    }else
	    {
             $titulo = 'S A L D O   D E   E G R E S O   T E M P O R I Z A D O';
             $tablaHTML =array();
             $tablaHTML[0]['medidas']=array(50,50,20,20,20,20,20,20,20,20,20);
             $tablaHTML[0]['datos']=array('Cuenta','Cliente','Fecha_Venc','Factura','Ven 1 a 7','Ven 8 a 30','Ven 31 a 60','Ven 61 a 90','Ven 91 a 180','Ven 181 a 360','Ven mas de 360');
             $tablaHTML[0]['tipo'] ='C';
             $pos = 1;
             $compro1='';
            foreach ($datos as $key => $value) {
                  $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
                  $tablaHTML[$pos]['datos']=array($value['Cuenta'],$value['Cliente'],$value['Factura'],$value['Fecha_Venc']->format('Y-m-d'),$value['Ven 1 a 7'],$value['Ven 8 a 30'],$value['Ven 31 a 60'],$value['Ven 61 a 90'],$value['Ven 91 a 180'],$value['Ven 181 a 360'],$value['Ven mas de 360']);
                  $tablaHTML[$pos]['tipo'] ='N';          
                  $pos+=1;
            }
          excel_generico($titulo,$tablaHTML);  
	    }


   }
}

?>