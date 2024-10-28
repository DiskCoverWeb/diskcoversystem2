<?php

require_once(dirname(__DIR__, 2) . "/db/db1.php");
require_once(dirname(__DIR__, 2) . "/funciones/funciones.php");
require_once(dirname(__DIR__, 3) . "/lib/fpdf/reporte_de.php");
require_once(dirname(__DIR__, 3) . "/lib/phpmailer/enviar_emails.php");

class facturas_distribucionM
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

  function ConsultarProductos($params){
    
    $sql = "SELECT DISTINCT TK.CodBodega AS CodBodega2, TC.ID,TC.Fecha,TC.Fecha_C,A.Nombre_Completo,TC.Total,TC.CodBodega,CodigoC,TC.Codigo_Inv,TC.CodigoU
            FROM Trans_Comision TC 
            INNER JOIN Accesos A ON TC.CodigoU = A.Codigo 
            INNER JOIN Trans_Kardex TK ON TK.Codigo_Barra = TC.CodBodega
            WHERE CodigoC = '".$params['beneficiario']."' 
            AND TC.Item = '".$_SESSION['INGRESO']['item']."' 
            AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND TC.Fecha = '".$params['fecha']."' 
            AND TC.T='F'";
    return $this->db->datos($sql);
  }

  /*function ConsultarKardex($codBarras){
    $sql ="SELECT TK.CodBodega, TK.Codigo_Barra,CP.Producto,CP.PVP, CP.Unidad
            FROM trans_kardex TK
            INNER JOIN Catalogo_Productos CP on TK.Codigo_Inv = CP.Codigo_Inv 
            WHERE TK.Codigo_Barra = '".$codBarras."'";
    return $this->db->datos($sql);
  }*/

  function DCLinea($TC, $Fecha, $Codigo = false)
  {
    $sql = "SELECT Codigo, Concepto, CxC, Serie, Autorizacion 
      FROM Catalogo_Lineas 
      WHERE TL <> 0 
      AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
      AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
      AND Fact = '" . $TC . "' ";
      
    if($Codigo){
      $sql .= "AND Codigo = '".$Codigo."' ";
    }
    $sql .= "AND Fecha <= '" . $Fecha . "' 
      AND Vencimiento >='" . $Fecha . "' 
      ORDER BY Codigo ";
    //print_r($sql);die();
    return $this->db->datos($sql);
  }

  function consultarGavetas(){
    $sql = "SELECT Periodo,TC,Codigo_Inv,Producto
            FROM Catalogo_Productos
            WHERE Item='".$_SESSION['INGRESO']['item']."' 
            AND Periodo='".$_SESSION['INGRESO']['periodo']."'
            AND Codigo_Inv LIKE 'GA%' 
            AND TC = 'P'
            ORDER BY Codigo_Inv";
    return $this->db->datos($sql);
  }

  function valoresGavetas($parametros){
    $sql = "SELECT Codigo_Inv, Existencia 
            FROM Trans_Kardex TK 
            WHERE Codigo_P = '".$parametros['codigo']."' 
            AND Codigo_Inv LIKE 'GA.%' "./*AND Fecha = '".$parametros['fecha']."'*/"
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND Item = '".$_SESSION['INGRESO']['item']."' 
            GROUP BY Codigo_P, Codigo_Inv, Existencia
            ORDER BY Codigo_Inv";
            
    return $this->db->datos($sql);
  }
  function existenciaProducto($codigo, $cod_inv){
    $sql = "SELECT TOP 1 Existencia FROM Trans_Kardex
            WHERE Codigo_Inv = '".$cod_inv."'
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND Item = '".$_SESSION['INGRESO']['item']."'
            ORDER BY Fecha DESC;";
    $existencia = $this->db->datos($sql);
    if(count($existencia) <= 0){
      $existencia = 0;
    }else{
      $existencia = $existencia[0]['Existencia'];
    }
    return $existencia;
    //return $this->db->datos($sql)[0]['Existencia'];
  }
  function consultaTrans_Ticket($TFA){
    $sql = "SELECT F.*,C.Cliente,C.CI_RUC,C.Telefono,C.Direccion,C.Ciudad,C.Grupo,C.Email 
					FROM Trans_Ticket As F,Clientes As C 
					WHERE F.Ticket = ".$TFA['Factura']." 
					AND F.TC = '".$TFA['TC']."' 
					AND F.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
					AND F.Item = '".$_SESSION['INGRESO']['item']."' 
					AND C.Codigo = F.CodigoC ";
    
    return $this->db->datos($sql);
  }

  function consultaFactura($TFA){
    $sql = "SELECT F.*,C.Cliente,C.CI_RUC,C.Telefono,C.Direccion,C.Ciudad,C.Grupo,C.Email FROM Facturas As F,Clientes As C 
					WHERE F.Factura = ".$TFA['Factura']." 
					AND F.TC = '".$TFA['TC']."' 
					AND F.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
					AND F.Item = '".$_SESSION['INGRESO']['item']."' 
					AND C.Codigo = F.CodigoC ";
    
    return $this->db->datos($sql);
  }

  function consultarDetalleTicketProds($TFA){
    $sql = "SELECT DF.*,CP.Detalle,CP.Codigo_Barra 
			FROM Trans_Ticket As DF,Catalogo_Productos As CP 
			WHERE DF.Ticket = " . $TFA['Factura'] . " 
			AND DF.TC = '" . $TFA['TC'] . "' 
			AND DF.Item = '" . $_SESSION['INGRESO']['item'] . "' 
			AND DF.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
			AND DF.Item = CP.Item 
			AND DF.Periodo = CP.Periodo 
			AND DF.Codigo_Inv = CP.Codigo_Inv 
			ORDER BY DF.D_No";
    
    return $this->db->datos($sql);
  }

  function consultarDetalleFactProds($TFA){
    $sql = "SELECT DF.*,CP.Detalle,CP.Codigo_Barra 
			FROM Detalle_Factura As DF,Catalogo_Productos As CP 
			WHERE DF.Factura = " . $TFA['Factura'] . " 
			AND DF.TC = '" . $TFA['TC'] . "' 
			AND DF.Item = '" . $_SESSION['INGRESO']['item'] . "' 
			AND DF.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
			AND DF.Item = CP.Item 
			AND DF.Periodo = CP.Periodo 
			AND DF.Codigo = CP.Codigo_Inv 
			ORDER BY DF.Codigo";
    
    return $this->db->datos($sql);
  }

  function consultarEvaluacionFundaciones(){
    $sql = "SELECT TOP (200) Item, Nivel, TP, Proceso, DC, Cheque, Mi_Cta, Cmds, Cta_Debe, Cta_Haber, Picture, Color, X, ID 
            FROM Catalogo_Proceso 
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Cmds LIKE '83%' 
            AND TP = 'EVALUACI' 
            ORDER BY Cmds";
    return $this->db->datos($sql);
  }

  function LlenarSelectIVA($fecha)
  {
    $sql = "SELECT Codigo, Porc 
          FROM Tabla_Por_ICE_IVA 
          WHERE IVA <> 0 
          AND Fecha_Inicio <= '".$fecha."' 
          AND Fecha_Final >= '".$fecha."' 
          ORDER BY Porc DESC ";
    return $this->db->datos($sql);
  }

  function LlenarSelectTipoFactura()
  {
    $sql = "SELECT  Codigo, Concepto, Fact, CxC, Cta_Venta, Serie, Autorizacion, Vencimiento, Fecha
            FROM Catalogo_Lineas
            WHERE Item = '".$_SESSION['INGRESO']['item']."'
            AND Periodo ='".$_SESSION['INGRESO']['periodo']."'
            AND TL <> 0
            AND LEN(Fact) = 3
            ORDER BY Codigo";
    return $this->db->datos($sql);
  }

  function EliminarTransComision($fecha, $cliente, $usuario){
    $sql = "DELETE Trans_Comision
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND Fecha = '".$fecha."' 
            AND CodigoC = '".$cliente."' 
            AND CodigoU = '".$usuario."'";
    return $this->db->String_Sql($sql);
  }

  function Listar_Clientes_PV($query, $parametros)
  {
    $sql = "SELECT Cliente, Codigo, CI_RUC,TD,Grupo,Email,C.T,Direccion,DirNumero,Calificacion 
                FROM Trans_Comision TC 
                INNER JOIN Clientes C ON TC.CodigoC = C.Codigo 
                WHERE TC.T = 'F' 
                AND TC.Fecha = '".$parametros['fecha']."'
                AND Cliente <> '.' 
                AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
                AND Item = '".$_SESSION['INGRESO']['item']."'";
    if (!is_numeric($query)) {
      $sql .= "AND Cliente LIKE '%" . $query . "%' ";
    } else {
      $sql .= "AND CI_RUC LIKE '%" . $query . "%' ";
    }
    // print_r($sql);die();
      
    if($parametros['donacion']!=''){
      $sql .= "AND Calificacion = '".$parametros['donacion']."'";
    }

    $sql .= "GROUP BY Cliente, Codigo, CI_RUC,TD,Grupo,Email,C.T,Direccion,DirNumero,Calificacion";
    // $sql.=" UNION 
    // SELECT Cliente,Codigo,CI_RUC,TD,Grupo,Email,T 
    // FROM Clientes 
    // WHERE Codigo = '9999999999' 
    // ORDER BY Cliente ";
    // print_r($sql);die();

    return $this->db->datos($sql);
  }
  function Listar_Clientes_PV_exacto($query)
  {
    $sql = "SELECT TOP 100 Cliente,Codigo,CI_RUC,TD,Grupo,Email,T 
        FROM Clientes 
        WHERE Cliente <> '.' 
        AND FA <> 0 ";
    if (!is_numeric($query)) {
      $sql .= " AND Cliente LIKE '%" . $query . "%'";
    } else {
      $sql .= " AND CI_RUC LIKE '%" . $query . "%'";
    } // print_r($sql);die();

    return $this->db->datos($sql);
  }

  function DCBodega()
  {
    $sql = "SELECT *
        FROM Catalogo_Bodegas
        WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
        AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
        ORDER BY CodBod ";
    return $this->db->datos($sql);
  }
  function DCBanco($query)
  {
    $sql = "SELECT Codigo +Space(2)+Cuenta As NomCuenta,Codigo 
       FROM Catalogo_Cuentas 
       WHERE TC IN ('BA','CJ','CP','C','P') 
       AND DG = 'D' 
       AND Item = '" . $_SESSION['INGRESO']['item'] . "'
       AND Periodo =  '" . $_SESSION['INGRESO']['periodo'] . "' ";
    if ($query) {
      $sql .= " AND Cuenta LIKE '%" . $query . "%'";
    }
    $sql .= " ORDER BY Codigo ";
    return $this->db->datos($sql);

  }

  function DCArticulos($Grupo_Inv, $TipoFactura, $query)
  {
    $sql = "SELECT Producto,Codigo_Inv,Codigo_Barra 
        FROM Catalogo_Productos 
        WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
        AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
        AND TC = 'P' ";
    if (strlen($Grupo_Inv) > 1) {
      $sql .= "AND MidStrg(Codigo_Inv,1,2) = '" . $Grupo_Inv . "' ";
    }
    // if($TipoFactura == "CP"){
    //    $sql.=" AND Cta_Inventario = '0' ";
    // }else{
    //    $sql.=" AND LEN(Cta_Inventario) > 1 ";
    // }
    if ($query) {
      $sql .= " AND Producto like '%" . $query . "%'";
    }
    $sql .= " ORDER BY Producto,Codigo_Inv ";

    // print_r($sql);die();
    return $this->db->datos($sql);
  }

  function DGAsientoF($grilla = false)
  {
    $sql = "SELECT * 
       FROM Asiento_F 
       WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
       AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'
       ORDER BY A_No Asc";
    $datos = $this->db->datos($sql);
    $ln = count($datos);
    $tbl = '';
    if ($grilla) {
      $botones[0] = array('boton' => 'Eliminar', 'icono' => '<i class="fa fa-trash"></i>', 'tipo' => 'danger', 'id' => 'A_No,CODIGO');
      $tbl = grilla_generica_new($sql, 'Asiento_F', false, $titulo = false, $botones, $check = false, $imagen = false, 1, 1, 1, 270);
    }
    return array('datos' => $datos, 'tbl' => $tbl, 'ln' => $ln);
  }

  function catalogo_lineas($TC, $SerieFactura, $emision, $vencimiento, $electronico = false)
  {
    $sql = "SELECT *
         FROM Catalogo_Lineas
         WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
         AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
         AND Fact = '" . $TC . "'
         AND Serie = '" . $SerieFactura . "'
         AND TL <> 0
         AND CONVERT(DATE,Fecha) <= '" . $emision . "'
         AND CONVERT(DATE,Vencimiento) >= '" . $vencimiento . "'";
    if ($electronico) {
      $sql .= " AND len(Autorizacion)=13";
    }
    $sql .= " ORDER BY Codigo ";
    // print_r($sql);die();
    return $this->db->datos($sql);
  }

  function catalogo_lineas_($TC, $SerieFactura)
  {
    $sql = "SELECT *
         FROM Catalogo_Lineas
         WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
         AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
         AND Fact = '" . $TC . "'
         AND Serie = '" . $SerieFactura . "'
         AND Autorizacion = '" . $_SESSION['INGRESO']['RUC'] . "'
         AND TL <> 0
         ORDER BY Codigo ";
    // print_r($sql);die();
    return $this->db->datos($sql);

  }

  function ELIMINAR_ASIENTOF($codigo = false, $A_no = false)
  {
    $sql = "DELETE
        FROM Asiento_F 
        WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
        AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'";
    if ($codigo) {
      $sql .= " AND CODIGO ='" . $codigo . "'";
    }
    if ($A_no) {
      $sql .= " AND A_No ='" . $A_no . "'";
    }
    // print_r($sql);die();

    return $this->db->String_Sql($sql);
  }

  function delete_factura($TipoFactura, $Factura_No)
  {
    $sql = "DELETE
        FROM Detalle_Factura 
        WHERE Factura = " . $Factura_No . " 
        AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
        AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
        AND TC = '" . $TipoFactura . "'; ";

    $sql .= "DELETE
        FROM Facturas 
        WHERE Factura = " . $Factura_No . " 
        AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
        AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
        AND TC = '" . $TipoFactura . "';";
    $this->db->String_Sql($sql);
  }

  function cargar_pedidos_factura($orden, $cliente = false)
  {
    // 'LISTA DE CODIGO DE ANEXOS
    $sql = "SELECT D.*,P.Producto 
     FROM Detalle_Factura  D ,Catalogo_Productos P
     WHERE D.Item = '" . $_SESSION['INGRESO']['item'] . "'
      AND D.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
     AND D.Factura = '" . $orden . "' 
     AND D.CodigoC = '" . $cliente . "'
     AND D.Item = P.Item
     AND D.Periodo = P.Periodo
    AND D.Codigo = P.Codigo_Inv";

    $sql .= " ORDER BY D.ID DESC";
    // print_r($sql);die();

    return $this->db->datos($sql);

  }
  function lista_hijos_id($query)
  {
    // $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
    $sql = "SELECT CP.Codigo_Inv,CP.Producto,CP.TC,TK.Costo as 'Valor_Total',CP.Unidad, SUM(Entrada-Salida) As Stock_Actual ,CP.Cta_Inventario
        FROM Catalogo_Productos As CP, Trans_Kardex AS TK 
        WHERE CP.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
        AND CP.Item = '" . $_SESSION['INGRESO']['item'] . "'
        AND LEN(CP.Cta_Inventario)>3 
        AND CP.Codigo_Inv LIKE '" . $query . "' 
        AND TK.T<> 'A' 
        AND CP.Periodo = TK.Periodo 
        AND CP.Item = TK.Item 
        AND CP.Codigo_Inv = TK.Codigo_Inv 
        group by CP.Codigo_Inv,CP.Producto,CP.TC,CP.Valor_Total,CP.Unidad,TK.Costo,CP.Cta_Inventario having SUM(TK.Entrada-TK.Salida) <> 0
        order by CP.Codigo_Inv,CP.Producto,CP.TC,CP.Valor_Total,CP.Unidad,CP.Cta_Inventario";

    // print_r($sql);die();
    $datos1 = $this->db->datos($sql);
    $datos = array();
    foreach ($datos1 as $key => $value) {
      $datos[] = array('id' => $value['Codigo_Inv'] . ',' . $value['Unidad'] . ',' . $value['Stock_Actual'], 'text' => $value['Producto']);
    }
    return $datos;
  }

  function pdf_guia_remision_elec($TFA, $nombre_archivo, $periodo = false, $aprobado = false, $descargar = false)
  {
    $res = 1;
    $sql = "SELECT DF.*,CP.Reg_Sanitario,CP.Marca
        FROM Detalle_Factura As DF, Catalogo_Productos As CP
        WHERE DF.Item = '" . $_SESSION['INGRESO']['item'] . "'
        AND DF.Periodo =  '" . $_SESSION['INGRESO']['periodo'] . "'
        AND DF.TC = '" . $TFA['TC'] . "'
        AND DF.Serie = '" . $TFA['Serie'] . "'
        AND DF.Autorizacion = '" . $TFA['Autorizacion'] . "'
        AND DF.Factura = " . $TFA['Factura'] . "
        AND LEN(DF.Autorizacion) >= 13
        AND DF.T <> 'A'
        AND DF.Item = CP.Item
        AND DF.Periodo = CP.Periodo
        AND DF.Codigo = CP.Codigo_Inv
        ORDER BY DF.ID,DF.Codigo ";
    // print_r($sql);die();
    $AdoDBDet = $this->db->datos($sql);

    // 'Encabezado de la Guia de Remision
    $sql2 = "SELECT F.*,GR.Remision,GR.Comercial,GR.CIRUC_Comercial,GR.Entrega,GR.CIRUC_Entrega,GR.CiudadGRI,GR.CiudadGRF,
        GR.Placa_Vehiculo,GR.FechaGRE,GR.FechaGRI,GR.FechaGRF,GR.Pedido,GR.Zona,GR.Serie_GR,GR.Autorizacion_GR,
        GR.Clave_Acceso_GR,GR.Hora_Aut_GR,GR.Estado_SRI_GR,GR.Error_FA_SRI,GR.Fecha_Aut_GR,GR.Lugar_Entrega 
        FROM Facturas As F, Facturas_Auxiliares As GR 
        WHERE F.Item = '" . $_SESSION['INGRESO']['item'] . "'
        AND F.Periodo =  '" . $_SESSION['INGRESO']['periodo'] . "'
        AND F.TC = '" . $TFA['TC'] . "' 
        AND F.Serie = '" . $TFA['Serie'] . "' 
        AND F.Autorizacion = '" . $TFA['Autorizacion'] . "' 
        AND F.Factura = " . $TFA['Factura'] . " 
        AND LEN(GR.Autorizacion_GR) >= 13 
        AND GR.Remision > 0 
        AND F.T <> 'A' 
        AND F.Item = GR.Item 
        AND F.Periodo = GR.Periodo 
        AND F.Serie = GR.Serie 
        AND F.Factura = GR.Factura ";
    // print_r($sql2);die();
    $AdoDBFA = $this->db->datos($sql2);
    // print_r($AdoDBFA);die();

    $tipo_con = Tipo_Contribuyente_SP_MYSQL($_SESSION['INGRESO']['RUC']);

    if (count($AdoDBFA) > 0 && count($tipo_con) > 0) {
      $AdoDBFA['Tipo_contribuyente'] = $tipo_con;
    }
    // array_push($datos_fac, $tipo_con);
    // print_r($TFA);
    $datos_cli_edu = $this->Cliente($TFA['CodigoC']);
    $datos_trans = $this->Cliente(false, false, false, false, $AdoDBFA[0]['CIRUC_Comercial']);

    // print_r($datos_trans);die();
    $datos_cli_edu[0]['Direccion_tras'] = '.';
    $datos_cli_edu[0]['Email_tras'] = '.';
    if (count($datos_trans) > 0) {
      // print_r($datos_trans);die();
      $datos_cli_edu[0]['Direccion_tras'] = $datos_trans[0]['Direccion'];
      $datos_cli_edu[0]['Email_tras'] = $datos_trans[0]['Email'];
    }
    $archivos = array('0' => $nombre_archivo . '.pdf', '1' => $TFA['Autorizacion_GR'] . '.xml');
    $to_correo = '';
    if (count($datos_cli_edu) > 0) {
      if ($datos_cli_edu[0]['Email'] != '.' && $datos_cli_edu[0]['Email'] != '') {
        $to_correo .= $datos_cli_edu[0]['Email'] . ',';
      }
      if ($datos_cli_edu[0]['Email2'] != '.' && $datos_cli_edu[0]['Email2'] != '') {
        $to_correo .= $datos_cli_edu[0]['Email2'] . ',';
      }
      if ($datos_cli_edu[0]['EmailR'] != '.' && $datos_cli_edu[0]['EmailR'] != '') {
        $to_correo .= $datos_cli_edu[0]['EmailR'] . ',';
      }
      // $to_correo = substr($to_correo, 0,-1);
    }
    $sucursal = $this->catalogo_lineas_('GR', $TFA['Serie']);
    $forma_pago = $this->DCTipoPago($AdoDBFA[0]['Tipo_Pago']);

    if (count($forma_pago) > 0) {
      $AdoDBFA[0]['Tipo_Pago'] = $forma_pago[0]['CTipoPago'];
    }

    imprimirDocEle_guia($AdoDBFA, $AdoDBDet, $datos_cli_edu, $nombre_archivo, null, 'factura', null, null, $imp = $descargar, $sucursal);
    if ($to_correo != '') {
      $titulo_correo = 'comprobantes electronicos';
      $cuerpo_correo = 'comprobantes electronico';
      if ($aprobado) {
        $r = $this->email->enviar_email($archivos, $to_correo, $cuerpo_correo, $titulo_correo, $HTML = false);

        // print_r($r);die();
        return $r;
      }
      // print_r($r);
    }
    return $res;
  }

  function pdf_guia_remision_elec_sin_fac($TFA, $nombre_archivo, $periodo = false, $aprobado = false, $descargar = false)
  {

    $res = 1;
    $sql = "SELECT DF.*,CP.Reg_Sanitario,CP.Marca
        FROM Detalle_Factura As DF, Catalogo_Productos As CP
        WHERE DF.Item = '" . $_SESSION['INGRESO']['item'] . "'
        AND DF.Periodo =  '" . $_SESSION['INGRESO']['periodo'] . "'
        AND DF.TC = '" . $TFA['TC'] . "'
        AND DF.Serie = '" . $TFA['Serie'] . "'
        AND DF.Autorizacion = '" . $TFA['Autorizacion'] . "'";
    if ($TFA['TC'] == 'GR') {
      $sql .= " AND DF.Factura = " . $TFA['Remision'];
    } else {
      $sql .= " AND DF.Factura = " . $TFA['Factura'];
    }

    $sql .= " AND LEN(DF.Autorizacion) >= 4
        AND LEN(DF.Autorizacion) <= 49
        AND DF.T <> 'A'
        AND DF.Item = CP.Item
        AND DF.Periodo = CP.Periodo
        AND DF.Codigo = CP.Codigo_Inv
        ORDER BY DF.ID,DF.Codigo ";
    // print_r($sql);die();
    $AdoDBDet = $this->db->datos($sql);

    // 'Encabezado de la Guia de Remision
    $sql2 = "SELECT GR.Remision,GR.Comercial,GR.CIRUC_Comercial,GR.Entrega,GR.CIRUC_Entrega,GR.CiudadGRI,GR.CiudadGRF,
            GR.Placa_Vehiculo,GR.FechaGRE,GR.FechaGRI,GR.FechaGRF,GR.Pedido,GR.Zona,GR.Serie_GR,GR.Autorizacion_GR,
            GR.Clave_Acceso_GR,GR.Hora_Aut_GR,GR.Estado_SRI_GR,GR.Error_FA_SRI,GR.Fecha_Aut_GR,GR.Fecha,GR.FechaGRE as 'Fecha_Aut',Autorizacion,Clave_Acceso_GR as 'Clave_Acceso'
            FROM Facturas_Auxiliares As GR 
            WHERE GR.Item = '" . $_SESSION['INGRESO']['item'] . "'
            AND GR.Periodo =  '" . $_SESSION['INGRESO']['periodo'] . "'
            AND GR.TC = '" . $TFA['TC'] . "' 
            AND GR.Serie = '" . $TFA['Serie'] . "' 
            AND GR.Autorizacion = '" . $TFA['Autorizacion'] . "' 
            AND GR.Factura =" . $TFA['Factura'] . " 
            AND Remision = '" . $TFA['Remision'] . "'
            AND LEN(GR.Autorizacion_GR) >= 13 
            AND GR.Remision > 0 ";
    // print_r($sql2);die();
    $AdoDBFA = $this->db->datos($sql2);
    // print_r($AdoDBFA);die();
    $AdoDBFA[0]['Serie'] = $TFA['Serie'];
    $AdoDBFA[0]['Factura'] = $TFA['Factura'];
    $AdoDBFA[0]['Factura_Aut'] = $TFA['Autorizacion'];
    $AdoDBFA[0]['Razon_Social'] = $TFA['Razon_Social'];
    $AdoDBFA[0]['RUC_CI'] = $TFA['RUC_CI'];
    $AdoDBFA[0]['Lugar_Entrega'] = $TFA['Lugar_Entrega'];
    $AdoDBFA[0]['Nota'] = '';
    $AdoDBFA[0]['Direccion_RS'] = $TFA['Direccion_RS'];
    $AdoDBFA[0]['Imp_Mes'] = '.';
    $AdoDBFA[0]['Nota'] = $TFA['Nota'];
    $AdoDBFA[0]['Observacion'] = $TFA['Observacion'];

    $tipo_con = Tipo_Contribuyente_SP_MYSQL($_SESSION['INGRESO']['RUC']);

    if (count($AdoDBFA) > 0 && count($tipo_con) > 0) {
      $AdoDBFA['Tipo_contribuyente'] = $tipo_con;
    }
    // array_push($datos_fac, $tipo_con);
    $datos_cli_edu = $this->Cliente($TFA['CodigoC']);
    $datos_trans = $this->Cliente(false, false, false, false, $AdoDBFA[0]['CIRUC_Comercial']);

    // print_r($datos_trans);die();
    $datos_cli_edu[0]['Direccion_tras'] = '.';
    $datos_cli_edu[0]['Email_tras'] = '.';
    if (count($datos_trans) > 0) {
      // print_r($datos_trans);die();
      $datos_cli_edu[0]['Direccion_tras'] = $datos_trans[0]['Direccion'];
      $datos_cli_edu[0]['Email_tras'] = $datos_trans[0]['Email'];
    }

    $archivos = array('0' => $nombre_archivo . '.pdf', '1' => $TFA['Autorizacion_GR'] . '.xml');
    $to_correo = '';
    if (count($datos_cli_edu) > 0) {
      if ($datos_cli_edu[0]['Email'] != '.' && $datos_cli_edu[0]['Email'] != '') {
        $to_correo .= $datos_cli_edu[0]['Email'] . ',';
      }
      if ($datos_cli_edu[0]['Email2'] != '.' && $datos_cli_edu[0]['Email2'] != '') {
        $to_correo .= $datos_cli_edu[0]['Email2'] . ',';
      }
      if ($datos_cli_edu[0]['EmailR'] != '.' && $datos_cli_edu[0]['EmailR'] != '') {
        $to_correo .= $datos_cli_edu[0]['EmailR'] . ',';
      }
      // $to_correo = substr($to_correo, 0,-1);
    }
    $sucursal = $this->catalogo_lineas_('GR', $TFA['Serie']);
    $forma_pago = $this->DCTipoPago('01');

    if (count($forma_pago) > 0) {
      $AdoDBFA[0]['Tipo_Pago'] = $forma_pago[0]['CTipoPago'];
    }

    imprimirDocEle_guia($AdoDBFA, $AdoDBDet, $datos_cli_edu, $nombre_archivo, null, 'factura', null, null, $imp = $descargar, $sucursal);
    if ($to_correo != '') {
      $titulo_correo = 'comprobantes electronicos';
      $cuerpo_correo = 'comprobantes electronico';
      if ($aprobado) {
        $r = $this->email->enviar_email($archivos, $to_correo, $cuerpo_correo, $titulo_correo, $HTML = false);

        // print_r($r);die();
        return $r;
      }
      // print_r($r);
    }
    return $res;
  }

  function pdf_factura_elec_rodillo($cod, $ser, $ci, $nombre, $clave_acceso, $periodo = false, $aprobado = false, $descargar = false)
  {
    $res = 1;
    $sql = "SELECT * 
    FROM Facturas 
    WHERE Serie='" . $ser . "' 
    AND Factura='" . $cod . "' 
    AND CodigoC='" . $ci . "' 
    AND Item = '" . $_SESSION['INGRESO']['item'] . "' ";
    if ($periodo == false || $periodo == '.') {
      $sql .= " AND Periodo =  '" . $_SESSION['INGRESO']['periodo'] . "' ";
    } else {
      $sql .= " AND Periodo BETWEEN '01/01/" . $periodo . "' AND '31/12" . $periodo . "'";
    }

    // print_r($sql);die();
    $datos_fac = $this->db->datos($sql);

    $sql1 = "SELECT * 
    FROM Detalle_Factura 
    WHERE Factura = '" . $cod . "' 
    AND CodigoC='" . $ci . "' 
    AND Serie='" . $ser . "' 
    AND Item = '" . $_SESSION['INGRESO']['item'] . "'
    AND Periodo =  '" . $_SESSION['INGRESO']['periodo'] . "' ";
    $detalle_fac = $this->db->datos($sql1);

    // $sql2 = "SELECT * FROM lista_tipo_contribuyente WHERE RUC = '".$_SESSION['INGRESO']['RUC']."'";
    $tipo_con = Tipo_Contribuyente_SP_MYSQL($_SESSION['INGRESO']['RUC']);

    $cliente =
      $sql2 = "SELECT * 
    FROM Trans_Abonos 
    WHERE Factura = '" . $cod . "' 
    AND CodigoC='" . $ci . "' 
    AND Item = '" . $_SESSION['INGRESO']['item'] . "'
    AND Autorizacion = '" . $clave_acceso . "'
    AND Periodo =  '" . $_SESSION['INGRESO']['periodo'] . "' ";
    $detalle_abonos = $this->db->datos($sql2);

    if (count($datos_fac) > 0 && count($tipo_con) > 0) {
      $datos_fac['Tipo_contribuyente'] = $tipo_con;
    }
    // array_push($datos_fac, $tipo_con);
    $datos_cli_edu = $this->Cliente($ci);
    $archivos = array('0' => $nombre . '.pdf', '1' => $clave_acceso . '.xml');
    $to_correo = '';
    if (count($datos_cli_edu) > 0) {
      if ($datos_cli_edu[0]['Email'] != '.' && $datos_cli_edu[0]['Email'] != '') {
        $to_correo .= $datos_cli_edu[0]['Email'] . ',';
      }
      if ($datos_cli_edu[0]['Email2'] != '.' && $datos_cli_edu[0]['Email2'] != '') {
        $to_correo .= $datos_cli_edu[0]['Email2'] . ',';
      }
      if ($datos_cli_edu[0]['EmailR'] != '.' && $datos_cli_edu[0]['EmailR'] != '') {
        $to_correo .= $datos_cli_edu[0]['EmailR'] . ',';
      }
      // $to_correo = substr($to_correo, 0,-1);
    }
    $sucursal = $this->catalogo_lineas_('FA', $ser);
    $forma_pago = $this->DCTipoPago($datos_fac[0]['Tipo_Pago']);

    if (count($forma_pago) > 0) {
      $datos_fac[0]['Tipo_Pago'] = $forma_pago[0]['CTipoPago'];
    }

    $TFA['factura'] = $datos_fac;
    $TFA['lineas'] = $detalle_fac;
    $TFA['CLAVE'] = $datos_fac[0]['Autorizacion'];
    $TFA['factura'][0]['Telefono'] = $datos_cli_edu[0]['Telefono'];
    $TFA['factura'][0]['Email'] = $datos_cli_edu[0]['Email'];

    $this->pdf->Imprimir_Punto_Venta_Grafico($TFA, 0);
  }

  function pdf_factura_elec($cod, $ser, $ci, $nombre, $clave_acceso, $periodo = false, $aprobado = false, $descargar = false)
  {
    $res = 1;
    $sql = "SELECT * 
    FROM Facturas 
    WHERE Serie='" . $ser . "' 
    AND Factura='" . $cod . "' 
    AND CodigoC='" . $ci . "' 
    AND Item = '" . $_SESSION['INGRESO']['item'] . "' ";
    if ($periodo == false || $periodo == '.') {
      $sql .= " AND Periodo =  '" . $_SESSION['INGRESO']['periodo'] . "' ";
    } else {
      $sql .= " AND Periodo BETWEEN '01/01/" . $periodo . "' AND '31/12" . $periodo . "'";
    }

    $datos_fac = $this->db->datos($sql);

    $datos_fac[0] = $TFA = Leer_Datos_FA_NV($datos_fac[0]);
    $sSQL = "SELECT DF.*, CP.Reg_Sanitario, CP.Marca, CP.Desc_Item, CP.Codigo_Barra As Cod_Barras
         FROM Detalle_Factura DF, Catalogo_Productos CP 
         WHERE DF.Item = '" . $_SESSION['INGRESO']['item'] . "' 
         AND DF.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
         AND DF.TC = '" . $TFA['TC'] . "' 
         AND DF.Serie = '" . $TFA['Serie'] . "' 
         AND DF.Autorizacion = '" . $TFA['Autorizacion'] . "' 
         AND DF.Factura = " . $TFA['Factura'] . " 
         AND DF.Item = CP.Item 
         AND DF.Periodo = CP.Periodo 
         AND DF.Codigo = CP.Codigo_Inv 
         ORDER BY DF.ID, DF.Codigo";
    $detalle_fac = $this->db->datos($sSQL);

    $sSQL = "SELECT * " .
      "FROM Trans_Abonos " .
      "WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' " .
      "AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
      "AND TP = '" . $TFA['TC'] . "' " .
      "AND Serie = '" . $TFA['Serie'] . "' " .
      "AND Autorizacion = '" . $TFA['Autorizacion'] . "' " .
      "AND Factura = " . $TFA['Factura'] . " " .
      "ORDER BY Fecha,ID";
    $detalle_abonos = $this->db->datos($sSQL);

    $tipo_con = Tipo_Contribuyente_SP_MYSQL($_SESSION['INGRESO']['RUC']);
    if (count($datos_fac) > 0 && count($tipo_con) > 0) {
      $datos_fac['Tipo_contribuyente'] = $tipo_con;
    }
    // array_push($datos_fac, $tipo_con);
    $datos_cli_edu = $this->Cliente($ci);
    $archivos = array('0' => $nombre . '.pdf', '1' => $clave_acceso . '.xml');
    $to_correo = '';
    if (count($datos_cli_edu) > 0) {
      if ($datos_cli_edu[0]['Email'] != '.' && $datos_cli_edu[0]['Email'] != '') {
        $to_correo .= $datos_cli_edu[0]['Email'] . ',';
      }
      if ($datos_cli_edu[0]['Email2'] != '.' && $datos_cli_edu[0]['Email2'] != '') {
        $to_correo .= $datos_cli_edu[0]['Email2'] . ',';
      }
      if ($datos_cli_edu[0]['EmailR'] != '.' && $datos_cli_edu[0]['EmailR'] != '') {
        $to_correo .= $datos_cli_edu[0]['EmailR'] . ',';
      }
      // $to_correo = substr($to_correo, 0,-1);
    }
    $sucursal = $this->catalogo_lineas_('FA', $ser);
    $forma_pago = $this->DCTipoPago($datos_fac[0]['Tipo_Pago']);

    if (count($forma_pago) > 0) {
      $datos_fac[0]['Tipo_Pago'] = $forma_pago[0]['CTipoPago'];
    }

    imprimirDocEle_fac($datos_fac, $detalle_fac, $datos_cli_edu, $nombre, null, 'factura', null, null, $imp = $descargar, $detalle_abonos, $sucursal);
    if ($to_correo != '') {
      $titulo_correo = 'comprobantes electronicos';
      $cuerpo_correo = 'comprobantes electronico';
      if ($aprobado) {
        $r = $this->email->enviar_email($archivos, $to_correo, $cuerpo_correo, $titulo_correo, $HTML = false);

        // print_r($r);die();
        return $r;
      }
      // print_r($r);
    }
    return $res;
  }

  function Cliente($cod, $grupo = false, $query = false, $clave = false, $ci = false)
  {
    $sql = "SELECT * from Clientes WHERE T='N' ";
    if ($cod) {
      $sql .= " and Codigo= '" . $cod . "'";
    }
    if ($grupo) {
      $sql .= " and Grupo= '" . $grupo . "'";
    }
    if ($query) {
      $sql .= " and Cliente +' '+ CI_RUC like '%" . $query . "%'";
    }
    if ($clave) {
      $sql .= " and Clave= '" . $clave . "'";
    }
    if ($ci) {
      $sql .= " and CI_RUC= '" . $ci . "'";
    }

    $sql .= " ORDER BY ID OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY;";

    // print_r($sql);die();

    $result = $this->db->datos($sql);

    // $result =  encode($result);
    // print_r($result);die();
    return $result;
  }

  function getSerieUsuario($codigoU)
  {
    $sql = "SELECT * FROM Accesos WHERE Codigo = '" . $codigoU . "'";
    // print_r($sql);die();
    $stmt = $this->db->datos($sql);
    return $stmt;
  }
  function getCatalogoLineas13($fecha, $vencimiento)
  {
    $sql = "  SELECT * FROM Catalogo_Lineas 
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
            AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
            AND Fact = 'FA'
            AND CONVERT(DATE,Fecha) <= '" . $fecha . "'
            AND CONVERT(DATE,Vencimiento) >= '" . $vencimiento . "'
            AND len(Autorizacion)>=13
            ORDER BY Codigo";
    $stmt = $this->db->datos($sql);
    return $stmt;
  }

  function DCTipoPago($cod = false)
  {
    $sql = "SELECT Codigo,(Codigo + ' ' + Descripcion) As CTipoPago
         FROM Tabla_Referenciales_SRI
         WHERE Tipo_Referencia = 'FORMA DE PAGO' ";
    if ($cod) {
      $sql .= " AND Codigo = '" . $cod . "'";
    }
    $sql .= " ORDER BY Codigo ";
    // print_r($sql);die();
    $stmt = $this->db->datos($sql);
    return $stmt;
  }

  function AdoLinea($parametros){
    $sql = "SELECT CxC, Codigo, Autorizacion, Fact, Serie
            FROM Catalogo_Lineas
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
            AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
            AND Fact = '" . $parametros['TipoFactura'] . /*"'
            AND Serie = '" . $parametros['SerieFactura'] . */"'
            AND TL <> 0
            ORDER BY Codigo";
            // print_r($sql);die();
    return $this->db->datos($sql);
  }

  function AdoAuxCatalogoProductos(){
    $sql = "SELECT TOP 1 Div
            FROM Catalogo_Productos
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
            AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
            AND TC = 'P'
            AND Div <> 0";
    return $this->db->datos($sql);
  }

  function ClientesDEDireccion($parametros){
    $sql = "SELECT Direccion
            FROM Clientes_Datos_Extras
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
            AND Codigo =  '" . $parametros['CodigoCliente'] . "'
            AND Tipo_Dato = 'DIRECCION'
            ORDER BY Direccion, Fecha_Registro DESC";
    return $this->db->datos($sql);
  }
  function ClientesDEHoraEntrega($parametros){
    $sql = "SELECT Hora_Ent
            FROM Clientes_Datos_Extras
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
            AND Codigo =  '" . $parametros['CodigoCliente'] . "'
            ORDER BY Direccion, Fecha_Registro DESC";
    return $this->db->datos($sql);
  }

  function ClienteSaldoPendiente($parametros){
    $sql = "SELECT COUNT(Factura) CantFact, SUM(Saldo_MN) As TSaldo_MN
            FROM Facturas
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
            AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
            AND CodigoC =  '" . $parametros['CodigoCliente'] . "'";
    return $this->db->datos($sql);
  }

  function DCDireccion($parametros){
    $sql = "SELECT ". Full_Fields("Clientes_Datos_Extras") ."
            FROM Clientes_Datos_Extras
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
            AND Codigo =  '" . $parametros['CodigoCliente'] . "'
            AND Direccion = '".$parametros['DireccionAux']."'
            AND Tipo_Dato = 'DIRECCION'";
    return $this->db->datos($sql);
  }




}

?>