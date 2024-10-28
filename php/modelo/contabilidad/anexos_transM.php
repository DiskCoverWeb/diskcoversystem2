<?php 
include(dirname(__DIR__,2).'/funciones/funciones.php');
// include(dirname(__DIR__).'/db/variables_globales.php');
@session_start(); 
/**
 * 
 */
class anexos_transM
{
	
	private $conn ;
	function __construct()
	{
	   $this->conn = new db();
	}

  function year($ConSucursal=false,$No_ATS=false)
  {
    
  // $cid = $this->conn;
     $sql = "SELECT YEAR(Fecha) As Anio FROM Trans_Compras 
           WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
       if($ConSucursal){
          if(strlen($No_ATS) > 3){
            $sql.="AND Item NOT IN (".$No_ATS.") ";
          }
       }else{
          $sql.="AND Item = '".$_SESSION['INGRESO']['item']."' ";
       }
       $sql.="UNION 
       SELECT YEAR(Fecha) As Anio 
       FROM Trans_Ventas 
       WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
        if($ConSucursal){
          if(strlen($No_ATS) > 3){
            $sql.="AND Item NOT IN (".$No_ATS.") ";
          }
       }else{
          $sql.="AND Item = '".$_SESSION['INGRESO']['item']."' ";
       }
       $sql.="GROUP BY YEAR(Fecha)
       ORDER BY YEAR(Fecha) DESC ";
    //      $stmt = sqlsrv_query($cid, $sql);
    //     $datos =  array();
    //  if( $stmt === false)  
    //  {  
    //  echo "Error en consulta PA.\n";  
    //  return '';
    //  die( print_r( sqlsrv_errors(), true));  
    //  }
    //   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
    //  {
    // $datos[] = $row;
    //  }

       // print_r($sql);die();
       $datos = $this->conn->datos($sql);
       return $datos;

  }

	function codigo_anexo($FechaMid)
	{

	// $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT Codigo, Concepto 
          FROM Tipo_Concepto_Retencion 
          WHERE Codigo <> '.' 
          AND Fecha_Inicio <= '".$FechaMid."'
          AND Fecha_Final >= '".$FechaMid ."'
          ORDER BY Codigo ";
        $datos = $this->conn->datos($sql);
       return $datos;
	}


	function encerar_lineas_sri($FechaIni,$FechaFin,$No_ATS,$ConSucursal=false)
	{
		 $respuesta = 1;
	$cid = $this->conn;
		// 'Encera las lineas del SRI de las Compras, Ventas, EXportaciones e Importaciones
    $sql = "UPDATE Trans_Anulados SET Linea_SRI = -1 WHERE FechaAnulacion Between '".$FechaIni."' AND '".$FechaFin."' ";
    if($ConSucursal)
    {
    	if(strlen($No_ATS) > 3)
    	{ 
    		$sql.= "AND Item NOT IN (".$No_ATS.") ";
    	}
    }else{
       $sql .= "AND Item = '".$_SESSION['INGRESO']['item']."' ";
    }
    $sql .="AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";

    $sql .= "UPDATE Trans_Compras SET Linea_SRI = -1 WHERE Fecha Between '".$FechaIni."' AND '".$FechaFin."' ";
     if($ConSucursal)
    {
    	if(strlen($No_ATS) > 3)
    	{ 
    		$sql.= "AND Item NOT IN (".$No_ATS.") ";
    	}
    }else{
       $sql .= "AND Item = '".$_SESSION['INGRESO']['item']."' ";
    }
    $sql .="AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";

    

    $sql .= "UPDATE Trans_Ventas 
       SET Linea_SRI = -1 
       WHERE Fecha Between '".$FechaIni."' AND '".$FechaFin."' ";
    if($ConSucursal)
    {
    	if(strlen($No_ATS) > 3)
    	{ 
    		$sql.= "AND Item NOT IN (".$No_ATS.") ";
    	}
    }else{
       $sql .= "AND Item = '".$_SESSION['INGRESO']['item']."' ";
    }
    $sql .="AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";    
    // 'Conectar_Ado_Execute $sql

    $sql .= "UPDATE Trans_Importaciones SET Linea_SRI = -1 WHERE Fecha Between '".$FechaIni."' AND '".$FechaFin."' ";
     if($ConSucursal)
    {
    	if(strlen($No_ATS) > 3)
    	{ 
    		$sql.= "AND Item NOT IN (".$No_ATS.") ";
    	}
    }else{
       $sql .= "AND Item = '".$_SESSION['INGRESO']['item']."' ";
    }
    $sql .="AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";

    

    $sql .= "UPDATE Trans_Exportaciones SET Linea_SRI = -1 WHERE Fecha Between '".$FechaIni."' AND '".$FechaFin."' ";
    if($ConSucursal)
    {
    	if(strlen($No_ATS) > 3)
    	{ 
    		$sql.= "AND Item NOT IN (".$No_ATS.") ";
    	}
    }else{
       $sql .= "AND Item = '".$_SESSION['INGRESO']['item']."' ";
    }
    $sql .="AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";

    
 
    $sql .= "UPDATE Trans_Air SET Linea_SRI = -1 WHERE Fecha Between '".$FechaIni."' AND '".$FechaFin."' ";
     if($ConSucursal)
    {
    	if(strlen($No_ATS) > 3)
    	{ 
    		$sql.= "AND Item NOT IN (".$No_ATS.") ";
    	}
    }else{
       $sql .= "AND Item = '".$_SESSION['INGRESO']['item']."' ";
    }
    $sql .="AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";  
 
   // 'Pasamos a averiguar que beneficiario es incorrecto
    if($_SESSION['INGRESO']['Tipo_Base'] == 'SQL SERVER')
    {
       $sql .= "UPDATE Trans_Air SET Linea_SRI = 0 FROM Trans_Air As T,Clientes As C ";
    }else{
       $sql .= "UPDATE Trans_Air As T,Clientes As C SET Linea_SRI = 0 ";
    }
    $sql .= "WHERE T.Fecha Between '".$FechaIni."' AND '".$FechaFin."' ";
    if($ConSucursal)
    {
    	if(strlen($No_ATS) > 3)
    	{ 
    		$sql.= "AND Item NOT IN (".$No_ATS.") ";
    	}
    }else{
       $sql .= "AND Item = '".$_SESSION['INGRESO']['item']."' ";
    }
    $sql .= "AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' AND T.IdProv = C.Codigo ";
        
    if($_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER'){
       $sql .= "UPDATE Trans_Compras SET Linea_SRI = 0 FROM Trans_Compras As T,Clientes As C ";
    }else{
       $sql .= "UPDATE Trans_Compras As T,Clientes As C SET Linea_SRI = 0 ";
    }
    $sql .= "WHERE T.Fecha Between '".$FechaIni."' AND '".$FechaFin."' ";
    if($ConSucursal)
    {
    	if(strlen($No_ATS) > 3)
    	{ 
    		$sql.= "AND Item NOT IN (".$No_ATS.") ";
    	}
    }else{
       $sql .= "AND Item = '".$_SESSION['INGRESO']['item']."' ";
    }
    $sql .= "AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' AND T.IdProv = C.Codigo ";
        
    if($_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER')
    {
       $sql .= "UPDATE Trans_Ventas SET Linea_SRI = 0 FROM Trans_Ventas As T,Clientes As C ";
    }else{
       $sql.= "UPDATE Trans_Ventas As T,Clientes As C SET Linea_SRI = 0 ";
    }
    $sql .= "WHERE T.Fecha Between '".$FechaIni."' AND '".$FechaFin."' ";
     if($ConSucursal)
    {
    	if(strlen($No_ATS) > 3)
    	{ 
    		$sql.= "AND Item NOT IN (".$No_ATS.") ";
    	}
    }else{
       $sql .= "AND Item = '".$_SESSION['INGRESO']['item']."' ";
    }
    $sql .= "
       AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' AND T.IdProv = C.Codigo ";
    // 'Conectar_Ado_Execute $sql
        
   if($_SESSION['INGRESO']['Tipo_Base']='SQL SERVER')
   {
       $sql .= "UPDATE Trans_Importaciones SET Linea_SRI = 0 FROM Trans_Importaciones As T,Clientes As C ";
   }else{
       $sql .= "UPDATE Trans_Importaciones As T,Clientes As C SET Linea_SRI = 0 ";
   }
    $sql .="WHERE T.Fecha Between '".$FechaIni."' AND '".$FechaFin."' ";
     if($ConSucursal)
    {
    	if(strlen($No_ATS) > 3)
    	{ 
    		$sql.= "AND Item NOT IN (".$No_ATS.") ";
    	}
    }else{
       $sql .= "AND Item = '".$_SESSION['INGRESO']['item']."' ";
    }
    $sql .= "AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' AND T.IdFiscalProv = C.Codigo ";
        
   if($_SESSION['INGRESO']['Tipo_Base'] = 'SQL SERVER'){
       $sql .= "UPDATE Trans_Exportaciones SET Linea_SRI = 0 FROM Trans_Exportaciones As T,Clientes As C ";
   }else{
       $sql .= "UPDATE Trans_Exportaciones As T,Clientes As C SET Linea_SRI = 0 ";
   }
    $sql .= "WHERE T.Fecha Between '".$FechaIni."' AND '".$FechaFin."' ";
    if($ConSucursal)
    {
    	if(strlen($No_ATS) > 3)
    	{ 
    		$sql.= "AND Item NOT IN (".$No_ATS.") ";
    	}
    }else{
       $sql .= "AND Item = '".$_SESSION['INGRESO']['item']."' ";
    }
    $sql .= "AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' AND T.IdFiscalProv = C.Codigo ";

    return $this->conn->String_Sql($sql);

	}

	function Numerar_Lineas_SRI($Tabla,$TipoTrans,$FechaIni,$FechaFin,$ConSucursal=false)
	{

		$Contador_AT = 1;
	// $cid = $this->conn;
		if($Tabla <> "")
		{
			if($Tabla == "Trans_Anulados")
			{
				$sql = "SELECT * 
             FROM Trans_Anulados 
             WHERE FechaAnulacion Between '".$FechaIni."' AND '".$FechaFin."' ";
             if($ConSucursal)
             {
             	if(strlen($No_ATS)>3)
             	{
             		$sql.="AND Item NOT IN (".$No_ATS.") ";
             	}
             }else
             {
             	 $sql.="AND Item = '".$_SESSION['INGRESO']['item']."' ";
             }
              $sql .="AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
              ORDER BY FechaAnulacion ";
                $datos = $this->conn->datos($sql);
               	  if(count($datos)>0)
               	    {
               	     foreach ($datos as $key => $value) 
               	        {
               	  	       $dato[0]['campo']='Linea_SRI';
               	  	       $dato[0]['dato']=$Contador_AT;
               	  	       $campoWhere[0]['campo']='ID';
               	  	       $campoWhere[0]['valor']=$value['ID'];
               	            update_generico($dato,$Tabla,$campoWhere);
               	           $Contador_AT = $Contador_AT+1;
               	        }     
               	   } 
			}else
			{
				$sql =  "SELECT * FROM ".$Tabla." WHERE Fecha Between '".$FechaIni."' AND '".$FechaFin."' ";
				if($ConSucursal)
				{
					if(strlen($No_ATS)>3)
					{
						$sql.="AND Item NOT IN (".$No_ATS.") ";
					}else
					{
						$sql.= "AND Item = '".$_SESSION['INGRESO']['item']."' ";
					}
				}
				$sql.= "AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
				switch ($TipoTrans) {
					case ($TipoTrans =='C') || ($TipoTrans=='V'):
					     $sql.= "ORDER BY TipoComprobante,IdProv,Fecha ";						
					    break;
					case ($TipoTrans=='I') || ($TipoTrans== 'E'):
						$sql.="ORDER BY IdFiscalProv,Fecha ";
						break;
				} 
            $datos = $this->conn->datos($sql);
               	  if(count($datos)>0)
               	    {
               	     foreach ($datos as $key => $value) 
               	        {
               	        	$Mifecha = $value['Fecha'];
               	        	if($_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER')
               	        	{
               	        		$Mifecha=$value['Fecha']->format('Ymd');
               	        	}               	  	       
               	  	       $Numero = $value['Numero'];
               	  	       $TipoDoc = $value['TP'];
               	  	       switch ($TipoTrans) 
               	  	       {
               	  	       		case ($TipoTrans =='C') || ($TipoTrans=='V'):
               	  	       		    $CodigoCliente = $value["IdProv"];
                                    $Factura_No = $value["Secuencial"];
                                    $Codigo1 = $value["Establecimiento"];
                                    $Codigo2 = $value["PuntoEmision"];
               	  	       		    break;
               	  	       		case ($TipoTrans=='I')||($TipoTrans== 'E'):
               	  	       			$CodigoCliente = $value["IdFiscalProv"];
                                    $Factura_No = $value["Correlativo"];
               	  	       			break;
               	  	       	}

               	  	       $dato[0]['campo']='Linea_SRI';
               	  	       $dato[0]['dato']=$Contador_AT;
               	  	       $campoWhere[0]['campo']='ID';
               	  	       $campoWhere[0]['valor']=$value['ID'];
               	           update_generico($dato,$Tabla,$campoWhere);
               	           $Contador_AT = $Contador_AT+1;


               	           switch ($TipoTrans) {
               	           	case ($TipoTrans == 'C'):
               	           		$sql = "UPDATE Trans_Air SET Linea_SRI = ".$Contador_AT."WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
                            if ($ConSucursal){
                               if(strlen($No_ATS) > 3){
                                $sql.="AND Item NOT IN (".$No_ATS.") ";
                                 }
                            }else{
                               $sql.= "AND Item = '".$_SESSION['INGRESO']['item']."' ";
                            }
                            $sql .="AND IdProv = '".$CodigoCliente."' AND Numero = ".$Numero." AND TP = '".$TipoDoc."' AND Tipo_Trans = 'C' ";
                           
               	           		break;
               	           	case 'I':
               	           	    $sql = "UPDATE Trans_Air
                                 SET Linea_SRI = ".$Contador_AT." 
                                 WHERE Fecha = '".$Mifecha & "'' ";
                                 if ($ConSucursal){
                                 	if(strlen($No_ATS) > 3){
                                 		$sql.="AND Item NOT IN (".$No_ATS.") ";
                                 	     }
                                 }else{
                                      $sql.= "AND Item = '".$_SESSION['INGRESO']['item']."' ";
                                }

                                 $sql.="AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
                                 AND IdProv = '".$CodigoCliente."'
                                 AND Numero = ".$Numero."
                                 AND TP = '".$TipoDoc. "'
                                 AND Tipo_Trans = 'I' ";
               	           		break;              	           	
               	        
               	           }

                              $datos = $this->conn->String_Sql($sql);

               	        }  
               	   } 




			}
		}
}

  function traer_datos($sql)
  {
       $datos = $this->conn->datos($sql);
       return $datos;

  }

  function generar_grilla($sql)
  {
	$cid = $this->conn;
  	 $stmt = sqlsrv_query($cid, $sql);
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }

	  
        $tabla = grilla_generica($stmt,null,NULL,'1',null,null,null,true);

       return $tabla;
  }

  function Vista_Compras($Fecha_Inicio,$Fecha_Fin,$ConSucursal=false,$No_ATS=false)
  {
    // $cid = $this->conn;
    $sql ="SELECT TC.TipoComprobante,TCC.Descripcion,COUNT(TipoComprobante) As Cant,SUM(BaseImponible) As BI,SUM(BaseImpGrav) As BIG,SUM(MontoIva) As MI 
      FROM Trans_Compras As TC,Clientes As C,Tipo_Comprobante As TCC
      WHERE TC.Fecha Between '".$Fecha_Inicio."' AND '".$Fecha_Fin."' ";
      if($ConSucursal){
          if (strlen($No_ATS) > 3)
             {
               $sql.="AND TC.Item NOT IN (".$No_ATS.") ";
             }
      }else{
        $sql.= "AND TC.Item = '".$_SESSION['INGRESO']['item']."' ";
        }
        $sql.=" AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
         AND TCC.TC = 'TDC' 
         AND TC.TipoComprobante = TCC.Tipo_Comprobante_Codigo  
         AND TC.IdProv = C.Codigo 
         GROUP BY TC.TipoComprobante,TCC.Descripcion 
         ORDER BY TC.TipoComprobante "; 
        return $this->traer_datos($sql);  
  }

  function Vista_Ventas($Fecha_Inicio,$Fecha_Fin,$ConSucursal=false,$No_ATS=false)
  {
    // $cid = $this->conn;
    $sql = "SELECT TV.TipoComprobante,TCC.Descripcion,COUNT(TipoComprobante) As Cant,SUM(BaseImponible) As BI,SUM(BaseImpGrav) As BIG,SUM(MontoIva) As MI 
      FROM Trans_Ventas As TV,Clientes As C,Tipo_Comprobante As TCC 
      WHERE TV.Fecha Between '".$Fecha_Inicio."' AND '".$Fecha_Fin."'  ";
      if($ConSucursal){
          if (strlen($No_ATS) > 3)
             {
               $sql.="AND TV.Item NOT IN (".$No_ATS.") ";
             }
      }else{
        $sql.= "AND TV.Item = '".$_SESSION['INGRESO']['item']."' ";
        }
        $sql.="AND TV.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
               AND TCC.TC = 'TDC' 
               AND TV.TipoComprobante = TCC.Tipo_Comprobante_Codigo  
               AND TV.IdProv = C.Codigo 
               GROUP BY TV.TipoComprobante,TCC.Descripcion 
               ORDER BY TV.TipoComprobante DESC ";
        return $this->traer_datos($sql);  
  }

  function Vista_Anulados($Fecha_Inicio,$Fecha_Fin,$ConSucursal=false,$No_ATS=false)
  {
    // $cid = $this->conn;
    $sql = "SELECT COUNT(T) As Cantidad 
        FROM Trans_Anulados 
        WHERE Fecha Between '".$Fecha_Inicio."' AND '".$Fecha_Fin."'  ";
      if($ConSucursal){
          if (strlen($No_ATS) > 3)
             {
               $sql.="AND Item NOT IN (".$No_ATS.") ";
             }
      }else{
        $sql.= "AND Item = '".$_SESSION['INGRESO']['item']."' ";
        }
        $sql.= "AND Periodo = '".$_SESSION['INGRESO']['periodo']."' GROUP BY T ";
        return $this->traer_datos($sql);  
  }

   function Vista_Retencion_Impuesto_Renta($Fecha_Inicio,$Fecha_Fin,$Fecha_Mit,$ConSucursal=false,$No_ATS=false)
  {
    // $cid = $this->conn;
    $sql="SELECT TA.CodRet,CCR.Concepto,COUNT(Concepto) As Cant,SUM(BaseImp) As BI,SUM(ValRet) As VR 
      FROM Trans_Air As TA,Tipo_Concepto_Retencion As CCR 
      WHERE TA.Fecha Between '".$Fecha_Inicio."' AND '".$Fecha_Fin."'  ";
      if($ConSucursal){
          if (strlen($No_ATS) > 3)
             {
               $sql.="AND TA.Item NOT IN (".$No_ATS.") ";
             }
      }else{
        $sql.= "AND TA.Item = '".$_SESSION['INGRESO']['item']."' ";
        }
         $sql.= "AND TA.Periodo = '" .$_SESSION['INGRESO']['periodo']."'
                 AND TA.Tipo_Trans = 'C'
                 AND CCR.Fecha_Inicio <= '".$Fecha_Mit."' 
                 AND CCR.Fecha_Final >= '".$Fecha_Mit."' 
                 AND TA.CodRet = CCR.Codigo  
                 GROUP BY TA.CodRet,CCR.Concepto 
                 ORDER BY TA.CodRet ";
        return $this->traer_datos($sql);  
  }

   function Vista_Retencion_Fuente_Iva($Fecha_Inicio,$Fecha_Fin,$ConSucursal=false,$No_ATS=false)
  {
    // $cid = $this->conn;
    $Vista_Retencion = array();
    //COMPRAS
    $sql = "SELECT PorRetBienes,PorRetServicios,COUNT(PorRetBienes) As Cant,
            SUM(MontoIvaBienes) As BIB,SUM(MontoIvaServicios) As BIS, 
            SUM(ValorRetBienes)As VRB,SUM(ValorRetServicios)As VRS 
            FROM Trans_Compras 
            WHERE Fecha Between '".$Fecha_Inicio."' AND '".$Fecha_Fin."'  ";
      if($ConSucursal){
          if (strlen($No_ATS) > 3)
             {
               $sql.="AND Item NOT IN (".$No_ATS.") ";
             }
      }else{
        $sql.= "AND Item = '".$_SESSION['INGRESO']['item']."' ";
        }
        $sql.="AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
               AND (PorRetBienes + PorRetServicios) > 0 
               GROUP BY PorRetBienes,PorRetServicios 
               ORDER BY PorRetBienes,PorRetServicios ";
      $COMPRAS = $this->traer_datos($sql);  

    //VENTAS
      $sql = "SELECT PorRetBienes,PorRetServicios, COUNT(PorRetBienes) As Cant, SUM(PorRetServicios) As Cant1,
        SUM(MontoIvaBienes) As BIB, SUM(MontoIvaServicios) As BIS, 
        SUM(ValorRetBienes) As VRB, SUM(ValorRetServicios) As VRS 
        FROM Trans_Ventas 
        WHERE Fecha Between '".$Fecha_Inicio."' AND '".$Fecha_Fin."'  ";
  if ($ConSucursal){
     if(strlen($No_ATS) > 3)
       {
        $sql.="AND Item NOT IN (".$No_ATS.") ";
       }
    }else
    {
      $sql.="AND Item = '".$_SESSION['INGRESO']['item']."' ";
    }

    $sql.= "AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          AND (PorRetBienes + PorRetServicios) > 0 
          GROUP BY PorRetBienes,PorRetServicios 
          ORDER BY PorRetBienes,PorRetServicios ";
   $VENTAS = $this->traer_datos($sql); 

   // 'Retenciones en la Fuente que le efectuaron
 $sql = "SELECT TA.CodRet, TR.Concepto, TR.Porcentaje, COUNT(TA.CodRet) As Cant, SUM(TA.BaseImp) As BI, SUM(TA.ValRet) As VR 
      FROM Trans_Air As TA,Tipo_Concepto_Retencion As TR 
      WHERE TA.Fecha Between '".$Fecha_Inicio."' AND '".$Fecha_Fin."'  ";
 if($ConSucursal){ 
    if (strlen(No_ATS) > 3){
      $sql.= "AND TA.Item NOT IN (".$No_ATS.") ";
     }
  }else{
    $sql.="AND TA.Item = '".$_SESSION['INGRESO']['item']."' ";
  }
  $sql.= "AND TA.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       AND TA.Tipo_Trans = 'V' 
       AND TR.Fecha_Inicio <= '".$Fecha_Inicio."' 
       AND TR.Fecha_Final >= '".$Fecha_Inicio."' 
       AND TA.CodRet = TR.Codigo 
       GROUP BY TA.CodRet,TR.Concepto,TR.Porcentaje 
       ORDER BY TA.CodRet,TR.Concepto,TR.Porcentaje ";
  $RETENCION = $this->traer_datos($sql); 

  $Vista_Retencion = array('compras'=>$COMPRAS,'ventas'=>$VENTAS,'retencion'=>$RETENCION);
        return $Vista_Retencion;
  }
  function base_imponible($Fecha_Inicio,$Fecha_Fin,$ConSucursal=false,$No_ATS=false)
  {
     $sql = "SELECT SUM(BaseImponible) As BI,SUM(BaseImpGrav) As BIG,SUM(MontoIva) As MI
             FROM Trans_Ventas As TV,Clientes As C 
             WHERE TV.Fecha Between '".$Fecha_Inicio."' AND '".$Fecha_Fin."'  ";
    if($ConSucursal)
    {
      if (strlen(No_ATS) > 3)
      {
        $sql.= "AND TV.Item NOT IN (".$No_ATS.") ";
      } 
    }else{
     $sql.=  "AND TV.Item = '".$_SESSION["INGRESO"]["item"]."' ";
    }

   $sql.= "AND TV.Periodo = '".$_SESSION['INGRESO']['periodo']."'
      AND TV.IdProv = C.Codigo
      GROUP BY BaseImponible,BaseImpGrav,MontoIva ";
   return $this->traer_datos($sql);  
  }
  function transacciones_compras($Fecha_Inicio,$Fecha_Fin,$ConSucursal=false,$No_ATS=false)
  {
    $sql = "SELECT TC.CodSustento,TCC.Descripcion,COUNT(TipoComprobante) As Cant,SUM(BaseImponible) As BI,SUM(BaseImpGrav) As BIG,SUM(MontoIva) As MI 
      FROM Trans_Compras As TC,Clientes As C,Tipo_Tributario As TCC
      WHERE TC.Fecha Between '".$Fecha_Inicio."' AND '".$Fecha_Fin."'  ";
 if($ConSucursal){
     if(strlen($No_ATS) > 3 )
     {
      $sql.="AND TC.Item NOT IN (".$No_ATS.") ";
     }
   }else{
        $sql.="AND TC.Item = '".$_SESSION["INGRESO"]["item"]."' ";
   }
   $sql.="AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          AND TC.CodSustento IN ('01','03','06','08','09') 
          AND TC.CodSustento = TCC.Credito_Tributario 
          AND TC.IdProv = C.Codigo 
          GROUP BY TC.CodSustento,TCC.Descripcion 
          ORDER BY TC.CodSustento ";
           return $this->traer_datos($sql); 
  }
  function importaciones($Fecha_Inicio,$Fecha_Fin,$ConSucursal=false,$No_ATS=false)
  {
      $sql = "SELECT TI.TipoComprobante,TCC.Descripcion,COUNT(TipoComprobante) As Cant,SUM(ValorCIF) As VC,SUM(MontoIva) As MI 
              FROM Trans_Importaciones As TI,Clientes As C,Tipo_Comprobante As TCC 
              WHERE TI.Fecha Between '".$Fecha_Inicio."' AND '".$Fecha_Fin."'  ";
       if($ConSucursal)
       {
          if(strlen($No_ATS) > 3)
            {
              $sql.= "AND TI.Item NOT IN (".$No_ATS.") ";
            }
        }else
        {
          $sql.= "AND TI.Item = '".$_SESSION['INGRESO']['item']."' ";
        }
        $sql.= "AND TI.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
                AND TCC.TC = 'TDC' 
                AND TI.TipoComprobante = TCC.Tipo_Comprobante_Codigo  
                AND TI.IdFiscalProv = C.Codigo 
                GROUP BY TI.TipoComprobante,TCC.Descripcion 
                ORDER BY TI.TipoComprobante ";
                 return $this->traer_datos($sql); 
  }
  function exportaciones($Fecha_Inicio,$Fecha_Fin,$ConSucursal=false,$No_ATS=false)
  {

     $sql= "SELECT TE.TipoComprobante,TCC.Descripcion,COUNT(TE.TipoComprobante) As Cant,SUM(ValorFOB) As VF 
            FROM Trans_Exportaciones As TE,Clientes As C,Tipo_Comprobante As TCC 
            WHERE TE.Fecha Between '".$Fecha_Inicio."' AND '".$Fecha_Fin."'  ";
      if($ConSucursal)
      {
        if (strlen($No_ATS) > 3)
        {
          $sql.= "AND TE.Item NOT IN (".$No_ATS.") ";
        }
      }else
      {
        $sql.= "AND TE.Item = '".$_SESSION['INGRESO']['item']."' ";
      }
       $sql.= "AND TE.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
               AND TCC.TC = 'TDC'
               AND TE.TipoComprobante = TCC.Tipo_Comprobante_Codigo  
               AND TE.IdFiscalProv = C.Codigo 
               GROUP BY TE.TipoComprobante,TCC.Descripcion 
               ORDER BY TE.TipoComprobante ";
                return $this->traer_datos($sql); 
  }
  function Trans_Importaciones($Fecha_Inicio,$Fecha_Fin,$ConSucursal=false,$No_ATS=false)
  {
    $sql="SELECT TI.CodSustento,TCC.Descripcion,COUNT(TipoComprobante) As Cant,SUM(BaseImponible) As BI,SUM(BaseImpGrav) As BIG,SUM(MontoIva) As MI 
      FROM Trans_Importaciones As TI,Clientes As C,Tipo_Tributario As TCC 
      WHERE TI.Fecha Between '".$Fecha_Inicio."' AND '".$Fecha_Fin."'  ";
    if($ConSucursal)
    {
      if(strlen($No_ATS) > 3)
      {
        $sql.="AND TI.Item NOT IN (".$No_ATS.") ";
      }
    }else{
      $sql.="AND TI.Item = '".$_SESSION['INGRESO']['item']."' ";
    }
    $sql.= "AND TI.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND TI.CodSustento IN ('01','03','06') 
            AND TI.CodSustento = TCC.Credito_Tributario  
            AND TI.IdFiscalProv = C.Codigo 
            GROUP BY TI.CodSustento,TCC.Descripcion 
            ORDER BY TI.CodSustento ";
      return $this->traer_datos($sql); 

  }

  function Trans_exportaciones($Fecha_Inicio,$Fecha_Fin,$ConSucursal=false,$No_ATS=false)
  {
    $sql= "SELECT TE.IdFiscalProv,SUM(ValorFOB) As VF 
      FROM Trans_Exportaciones As TE,Clientes As C 
      WHERE TE.Fecha Between '".$Fecha_Inicio."' AND '".$Fecha_Fin."'  ";
    if($ConSucursal)
    {
      if(strlen($No_ATS) > 3)
      {
        $sql.="AND TE.Item NOT IN (".$No_ATS.") ";
      }
    }else{
      $sql.="AND TE.Item = '".$_SESSION['INGRESO']['item']."' ";
    }
    $sql.= "AND TE.Periodo = '".$_SESSION['INGRESO']['periodo']."'
       AND TE.IdFiscalProv = C.Codigo  GROUP BY TE.IdFiscalProv ";
      return $this->traer_datos($sql); 

  }
}
?>