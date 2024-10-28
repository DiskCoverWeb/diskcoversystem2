<?php 

require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

/**
 * 
 */
class recuperar_facturaM
{
    private $db;
    
    function __construct()
    {
        $this->db = new db();
    }
    function entidad($query=false,$IDempresa,$ciudad=false)
    {

        if($ciudad)
        {
            $sql = "SELECT *
                  FROM lista_empresas
                  WHERE ID_Empresa = '".$IDempresa."' AND Ciudad='".$ciudad."' ";

        }else{
            
            $sql = "SELECT *
                  FROM lista_empresas
                  WHERE ID_Empresa = '".$IDempresa."' ";
        }
        if($query)
        {
            $sql.=" and Empresa like '".$query."%' ";
        }

        $sql.='ORDER BY Empresa;';      
        return $this->db->datos($sql,'MYSQL');
    }

    function datos_empresa($ID)
    {       
        $sql = "SELECT *
          FROM lista_empresas
          WHERE ID = '".$ID."';";
        return $this->db->datos($sql,'MYSQL');
    }

    function facturas_a_recuperar($item,$periodo,$serie=false,$factura=false,$autorizacion=false,$desde=false,$hasta=false)
    {
        $sql ="SELECT *
              FROM Trans_Documentos
              WHERE Item = '".$item."'
              AND TD = 'FA'";              
        if($serie)
        {
            $sql.=" AND Serie = '".$serie."'";
        }
        if($factura)
        {
            $sql.=" AND Documento = '".$factura."'";
        }
        if($autorizacion)
        {
            $sql.=" AND Clave_Acceso = '".$autorizacion."'";
        }
        if($desde && $hasta)
        {
            $sql.=" AND Fecha BETWEEN '".$desde."' AND '".$hasta."'";
        }else
        {
            if($periodo)
            {
                $sql.=" AND Periodo = '".$periodo."'";
            }else
            {
                $sql.=" AND Periodo = '.'";
            }

        }

        // print_r($sql);die();
        return $this->db->datos($sql);

    }
    function facturas_emitidas($item,$codigo='T',$desde=false,$hasta=false,$serie=false)
    {
            
            $sql ="SELECT T,TC,Serie,Autorizacion,Factura,Fecha,SubTotal,Con_IVA,IVA,Descuento+Descuento2 as Descuentos,Total_MN as Total,Saldo_MN as Saldo,RUC_CI,TB,Razon_Social,CodigoC,ID 
            FROM Facturas 
            WHERE Item = '".$item."'";
            if($codigo!='T')
            {
                // si el codigo es T se refiere a todos
               $sql.=" AND CodigoC ='".$codigo."'";
            } 
            if($serie)
            {
                // si el codigo es T se refiere a todos
               $sql.=" AND Serie ='".$serie."'";
            }             
            if($desde!='' && $hasta!='')
           {
             $sql.= " AND Fecha BETWEEN   '".$desde."' AND '".$hasta."' ";
           }

           $sql.="ORDER BY ID DESC "; 
        // print_r($_SESSION['INGRESO']);
        // print_r($sql);die();    
          return $this->db->datos($sql);

           // return $datos;
     }

     function lista_facturas_faltantes($item,$periodo=FALSE,$desde=false,$hasta=false,$serie=false)
     {

        $sql= "SELECT Serie,Factura,Fecha,Clave_Acceso FROM Facturas WHERE Item = '".$item."' AND TC = 'FA' AND Serie = '".$serie."' AND Periodo = '".$periodo."'";
        if($desde!='' && $hasta!='')
        {
            $sql.=" AND Fecha BETWEEN '".$desde."' AND '".$hasta."'";
        }
        $sql2 = "SELECT ID,Serie,Documento,Fecha,Clave_Acceso FROM Trans_Documentos WHERE Item = '".$item."' AND TD = 'FA' AND Serie = '".$serie."' AND Periodo = '".$periodo."'";
        if($desde!='' && $hasta!='')
        {
            $sql2.=" AND Fecha BETWEEN '".$desde."' AND '".$hasta."'";
        }

        // print_r($sql);print_r($sql2);die();

        $facturas =  $this->db->datos($sql);
        $xmls =  $this->db->datos($sql2);

        $lista = array();

        $existe = false;
        foreach ($xmls as $key => $value) {
            $existe = false;
            foreach ($facturas as $key2 => $value2) {
                if($value2['Factura']==$value['Documento'])
                {
                    $existe = true;
                }
            }
            if($existe==false)
            {
                $lista[] = $value;
            }
        }

        // print_r( $lista);die();


        // $sql1 = "SELECT T1.Serie,T1.Factura,T1.Fecha,T1.Autorizacion,T2.Serie as 'Serie_TD',T2.Documento as 'Documento',T2.Fecha as 'Fecha_TD',T2.Clave_Acceso
        // FROM Facturas T1
        // LEFT JOIN Trans_Documentos T2 ON  T1.Factura = T2.Documento
        // WHERE   T2.Documento IS NULL";        
        // if($serie)
        // {
        //     // si el codigo es T se refiere a todos
        //    $sql1.=" AND T1.Serie ='".$serie."'";
        // }             
        // if($desde!='' && $hasta!='')
        //  {
        //   $sql1.= " AND T1.Fecha BETWEEN   '".$desde."' AND '".$hasta."' ";
        //  }

        // if($periodo)
        // {
        //     $sql1.=" AND T1.Periodo = '".$periodo."'";
        // }else
        // {
        //     $sql1.=" AND T1.Periodo = '.' ";
        // }


        // $sql2="SELECT T1.Serie,T1.Factura,T1.Fecha,T1.Autorizacion,T2.Serie as 'Serie_TD',T2.Documento as 'Documento',T2.Fecha as 'Fecha_TD',T2.Clave_Acceso
        // FROM Trans_Documentos T2
        // LEFT JOIN Facturas T1
        // ON  T1.Factura = T2.Documento
        // WHERE   TD = 'FA' AND  T1.Factura IS NULL";
        // if($serie)
        // {
        //     // si el codigo es T se refiere a todos
        //    $sql2.=" AND T2.Serie ='".$serie."'";
        // }             
        // if($desde!='' && $hasta!='')
        //  {
        //   $sql2.= " AND T2.Fecha BETWEEN   '".$desde."' AND '".$hasta."' ";
        //  }

        // if($periodo)
        // {
        //     $sql2.=" AND T2.Periodo = '".$periodo."'";
        // }else
        // {
        //     $sql2.=" AND T2.Periodo = '.' ";
        // }

        //  // $sql3 =$sql1.' UNION '.$sql2;

        //  // print_r($sql2);die();

          return $lista;
     }


function catalogo_lineas($TC,$item,$periodo,$SerieFactura=false)
  {
    $sql = "SELECT *
         FROM Catalogo_Lineas
         WHERE Item = '".$item."'
         AND Periodo = '".$periodo."'
         AND Fact = '".$TC."'";
         if($SerieFactura)
         {
            $sql.=" AND Serie = '".$SerieFactura."'";
         }
         $sql.="AND Autorizacion = '".$_SESSION['INGRESO']['RUC']."'
         ORDER BY Codigo ";
         // print_r($sql);die();
         return $this->db->datos($sql);

  }




}

?>