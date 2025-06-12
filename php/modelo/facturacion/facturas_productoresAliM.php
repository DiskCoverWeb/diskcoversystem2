<?php

require_once(dirname(__DIR__, 2) . "/db/db1.php");
require_once(dirname(__DIR__, 2) . "/funciones/funciones.php");
require_once(dirname(__DIR__, 3) . "/lib/fpdf/reporte_de.php");
require_once(dirname(__DIR__, 3) . "/lib/phpmailer/enviar_emails.php");

class facturas_productoresAliM
{
    private $db;
    private $email;
    private $pdf;

    public function __construct()
    {

      $this->db = new db();
      $this->email = new enviar_emails();
      $this->pdf = new cabecera_pdf();
    }

    function DCCliente()
    {
      $sql = "SELECT C.Cliente as text,Orden_No as id,C.Email,C.Telefono,C.Direccion,C.CI_RUC,C.Codigo,C.TD
              FROM            Trans_Comision TC
              inner join Clientes C on TC.CodigoC = C.Codigo
              WHERE  Item = '".$_SESSION['INGRESO']['item']."'
              AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
              AND (Orden_No LIKE 'PA_%')
              group by C.Cliente,Orden_No,C.Email,C.Telefono,C.Direccion,C.CI_RUC,C.Codigo,C.TD";
      return $this->db->datos($sql);

    }

    function pedido_seleccionado($orden)
    {
      $sql = "SELECT CP.Producto,Nombre_Completo,TC.Periodo, TC.T, TC.TC, Cta, TC.Fecha, Fecha_C, CodigoC, TC.TP, Numero, TC.Factura, TC.Codigo_Inv, Total, Porc, Valor_Pagar, TC.Item, TC.CodigoU, TC.X, CodBodega, TC.ID, Orden_No, Fecha_A, TC.Codigo_Barra,CP.PVP
              FROM            Trans_Comision TC
              inner join Accesos A on TC.CodigoU = A.Codigo
              INNER JOIN Catalogo_Productos CP on TC.Codigo_Inv = CP.Codigo_Inv
              WHERE  TC.Item = '".$_SESSION['INGRESO']['item']."'
              AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."'
              AND CP.Item = TC.Item
              AND CP.Periodo = TC.Periodo
              AND  Orden_No = '".$orden."'";

      return $this->db->datos($sql);
    }
}

?>