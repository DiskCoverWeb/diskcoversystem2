<?php
require_once(dirname(__DIR__, 2) . "/db/db1.php");
require_once(dirname(__DIR__, 2) . "/funciones/funciones.php");
require_once(dirname(__DIR__, 3) . "/lib/fpdf/reporte_de.php");
require_once(dirname(__DIR__, 3) . "/lib/phpmailer/enviar_emails.php");

class facturarM
{
  private $db;
  private $email;

  public function __construct()
  {
    $this->db = new db();
    $this->email = new enviar_emails();
  }

  function case_lote($lote_no)
  {
    $sql = "SELECT Lote_No, Fecha_Fab, Fecha_Exp, Procedencia, 
    Modelo, Serie_No, SUM(Entrada-Salida) As TotStock
    FROM Trans_Kardex
    WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
    AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
    AND T <> 'A'
    AND Lote_No = '" . SinEspaciosDer($lote_no) . "'
    GROUP BY Lote_No, Fecha_Fab, Fecha_Exp, Procedencia, Modelo, Serie_No
    HAVING SUM(Entrada-Salida) <> 0
    ORDER BY Lote_No, Fecha_Fab, Fecha_Exp, Procedencia, Modelo, Serie_No";

    return $this->db->datos($sql);
  }

  function case_orde($cadena)
  {
    $sep = MidStrg($cadena, 11, strlen($cadena));
    $ordenP = intval(SinEspaciosIzq($sep));
    $sql = "SELECT *
    FROM Asiento_F
    WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
    AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'
    AND Numero = '" . $ordenP . "' ";

    $data = $this->db->datos($sql);

    if (count($data) <= 0) {

      $sql2 = "SELECT * 
             FROM Detalle_Factura
             WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
             AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
             AND T <> 'A'
             AND TC <> 'OP'
             AND Factura = '" . $ordenP . "'
             ORDER BY ID,Codigo";

      $data2 = $this->db->datos($sql2);

      if (count($data2) > 0) {
        return ['1', $data];
      }
      
    }

    return ['0', $data2];

  }



  function lineas_factura($tabla = false, $altoTabla=0)
  {
    $sql = "SELECT * 
            FROM Asiento_F 
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
            AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
            ORDER BY A_No";
    if ($tabla) {
      $altoTabla = $altoTabla == 0 ? 100 : $altoTabla;
      $botones[0] = array('boton' => 'Eliminar linea', 'icono' => '<i class="fa fa-trash"></i>', 'tipo' => 'danger', 'id' => 'A_No,CODIGO');
      $datos = grilla_generica_new($sql, 'Asiento_F', '', $titulo = false, $botones, $check = false, $imagen = false, 1, 1, 1,$altoTabla,2,false,false,1,'text-center',G_NINGUNO,1);
    } else {
      $datos = $this->db->datos($sql);
    }
    return $datos;
  }

  function DCFactura($TC,$Serie,$factura=false)
  {
      $sql = "SELECT F.TC,F.Factura,F.Autorizacion,F.Serie,F.CodigoC,F.Fecha,F.Fecha_V,
      F.Total_MN,F.Total_ME,F.Saldo_MN,F.Saldo_ME,F.Cta_CxP,F.Cod_CxC,F.Nota,F.Observacion,F.Cotizacion,
      C.Cliente,C.Direccion,C.CI_RUC,C.Telefono,C.Grupo 
      FROM Facturas As F 
      INNER JOIN Clientes As C
      ON F.CodigoC = C.Codigo 
      WHERE F.T = '".G_PENDIENTE."' 
      AND F.Item = '".$_SESSION['INGRESO']['item']."' 
      AND F.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
      AND F.Serie = '".$Serie."' 
      AND F.TC = '".$TC."' 
      AND F.Saldo_MN > 0 ";
      if($factura)
      {
          $sql.=" AND F.Factura ='".$factura."' ";
      }
      $sql.=" ORDER BY F.TC,F.Factura";
      // print_r($sql);die();
      return $this->db->datos($sql);
  }

  function DCMod()
  {
    $sql = "SELECT Detalle, Codigo, TC 
       FROM Catalogo_SubCtas 
       WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
       AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
       AND TC IN ('I','CC') 
       ORDER BY Detalle ";
    return $this->db->datos($sql);
  }
  function DCLinea($TC, $Fecha)
  {
    $sql = "SELECT Codigo, Concepto, CxC, Serie, Autorizacion 
      FROM Catalogo_Lineas 
      WHERE TL <> 0 
      AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
      AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
      AND Fact = '" . $TC . "' 
      AND Fecha <= '" . $Fecha . "' 
      AND Vencimiento >='" . $Fecha . "'
      ORDER BY Codigo ";
    return $this->db->datos($sql);
  }

  function DCTipoPago($cod = false)
  {
    $sql = "SELECT (Codigo+' '+ Descripcion) As CTipoPago,Codigo 
       FROM Tabla_Referenciales_SRI 
       WHERE Tipo_Referencia = 'FORMA DE PAGO' ";
    if ($cod) {
      $sql .= "AND Codigo = '" . $cod . "'";
    }else{
      $sql .= "AND Codigo IN ('01','16','17','18','19','20','21') ";
    }
      $sql .= "ORDER BY Codigo ";
    return $this->db->datos($sql);
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

  function DCGrupo_No($query = false)
  {
    $sql = "SELECT Grupo 
       FROM Clientes 
       WHERE T = 'N' 
       AND FA <> 0";
    if ($query) {
      $sql .= " AND Grupo LIKE '%" . $query . "%' ";

    }
    $sql .= "
       GROUP BY Grupo 
       ORDER BY Grupo";
    //print_r($sql);die();
    return $this->db->datos($sql);
  }

  function Listar_Tipo_Beneficiarios($query = false, $grupo = G_NINGUNO, $ci = false)
  {
    $sql = "SELECT TOP 50 Cliente,CI_RUC,Codigo,Cta_CxP,Grupo,Cod_Ejec
         FROM Clientes
         WHERE FA <> 0
         AND T = 'N' ";
    if (!is_numeric($query)) {
      $sql .= " AND Cliente LIKE '%" . $query . "%'";
    } else {
      $sql .= " AND CI_RUC like '" . $query . "%'";
    }
    if ($grupo <> G_NINGUNO) {
      $sql .= " AND Grupo = '" . $grupo . "' ";
    }
    $sql .= " ORDER BY Cliente ";
    //print_r($sql);die();
    return $this->db->datos($sql);
  }

  function bodega()
  {
    $sql = "SELECT * 
      FROM Catalogo_Bodegas 
      WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
      AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
      ORDER BY Bodega ";
    return $this->db->datos($sql);

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

    $datos_cli_edu = array();
    $datos_trans = array();
    if (count($AdoDBFA) > 0 && count($tipo_con) > 0) {
      $AdoDBFA['Tipo_contribuyente'] = $tipo_con;
    
      // array_push($datos_fac, $tipo_con);
      // print_r($TFA);
      $datos_cli_edu = $this->Cliente($TFA['CodigoC']);
      $datos_trans = $this->Cliente(false, false, false, false, $AdoDBFA[0]['CIRUC_Comercial']);
    }

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

  function DCMarca($query="")
  {
    $sql = "SELECT * 
      FROM Catalogo_Marcas 
      WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
      AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' ";

    if($query){
      $sql .= "AND Marca LIKE '%" . $query . "%' ";
    }
      $sql .= "ORDER BY Marca ";
    return $this->db->datos($sql);
  }
  function CDesc1()
  {
    $sql = "SELECT *
          FROM Catalogo_Interes 
          WHERE TP = 'D' 
          ORDER BY Interes ";
    return $this->db->datos($sql);
  }

  function DCMedico()
  {

    $sql = "SELECT Cliente, CI_RUC, TD, Codigo 
       FROM Clientes 
       WHERE Asignar_Dr <> 0 
       ORDER BY Cliente";
    return $this->db->datos($sql);
  }

  function DCEjecutivo()
  {

    $sql = "SELECT CR.Codigo,C.Cliente,C.CI_RUC,CR.Porc_Com 
        FROM Catalogo_Rol_Pagos As CR, Clientes As C 
        WHERE CR.Item = '" . $_SESSION['INGRESO']['item'] . "' 
        AND CR.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
        AND CR.Codigo = C.Codigo 
        ORDER BY C.Cliente ";
    return $this->db->datos($sql);
  }


  function Listar_Productos($Cod_Marca, $OpcServicio = false, $PatronDeBusqueda = false, $NombreMarca = false, $SQL_Server = false)
  {
    // print_r('es');die();
    if ($NombreMarca == '.') {
      $NombreMarca = '';
    }
    if ($Cod_Marca <> G_NINGUNO) {
      if ($SQL_Server) {
        $sql = "UPDATE Catalogo_Productos 
                SET Marca = '" . $NombreMarca . "' 
                FROM Catalogo_Productos As CP,Trans_Kardex As TK ";
      } else {
        $sql = "UPDATE Catalogo_Productos As CP,Trans_Kardex As TK 
                SET CP.Marca = '" . $NombreMarca . "' ";
      }
      $sql .= "
             WHERE CP.Item = '" . $_SESSION['INGRESO']['item'] . "'
             AND CP.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
             AND TK.CodMarca = '" . $Cod_Marca . "'
             AND CP.Codigo_Inv = TK.Codigo_Inv
             AND CP.Item = TK.Item
             AND CP.Periodo = TK.Periodo ";
      $this->db->String_Sql($sql);
    }

    $sql = "SELECT Producto,Codigo_Inv,Codigo_Barra 
          FROM Catalogo_Productos 
          WHERE TC = 'P' 
          AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
          AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
          AND T <> 'I' ";
    if ($OpcServicio) {
      $sql .= " AND LEN(Cta_Inventario) <= 1 ";
    }
    if ($PatronDeBusqueda <> "") {
      $sql .= " AND Producto LIKE '%" . $PatronDeBusqueda . "%' ";
    }
    if ($NombreMarca <> "") {
      $sql .= " AND Marca LIKE '%" . $NombreMarca . "%' ";
    }
    $sql .= " ORDER BY Producto,Codigo_Inv,Codigo_Barra ";

    // print_r($sql);die();
    $respuest = $this->db->datos($sql);
    return $respuest;

  }
  function LstOrden()
  {
    $sql = "SELECT Lote_No 
        FROM Trans_Kardex 
        WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
        AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
        AND T <> 'A' 
        AND LEN(Lote_No) > 1 
        GROUP BY Lote_No 
        ORDER BY Lote_No ";
    $respuest = $this->db->datos($sql);
    return $respuest;
  }

  function Listar_Productos_all($PatronDeBusqueda = false, $codigo = false)
  {
    $sql = "SELECT *
          FROM Catalogo_Productos 
          WHERE TC = 'P' 
          AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
          AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
          AND INV <> 0 ";
    if ($PatronDeBusqueda <> "") {
      $sql .= " AND Producto LIKE '%" . $PatronDeBusqueda . "%' ";
    }
    if ($codigo <> "") {
      $sql .= " AND Codigo_Inv = '" . $codigo . "' ";
    }

    // print_r($sql);die();
    $respuest = $this->db->datos($sql);
    return $respuest;

  }

  function delete_asientoF($ln_No = false)
  {
    $sql = "DELETE 
        FROM Asiento_F 
        wHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
        AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' ";
    if ($ln_No) {
      $sql .= ' AND A_No=' . $ln_No;
    }
    $respuest = $this->db->String_Sql($sql);
    return $respuest;

  }
  function delete_asientoTK()
  {
    $sql = "DELETE
         FROM Asiento_TK
         WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
         AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' ";
    $respuest = $this->db->String_Sql($sql);
    return $respuest;
  }
  //------------------------------------panel suscripcion------------------

  function delete_asientoP()
  {
    $Trans_No = 250;
    $sql = "DELETE 
           FROM Asiento_P 
           WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
           AND T_No = " . $Trans_No . " 
           AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' ";
    $respuest = $this->db->String_Sql($sql);
    return $respuest;
  }

  function Trans_Suscripciones($PatronDeBusqueda = false, $codigo = false)
  {
    $sql = "SELECT * 
             FROM Trans_Suscripciones 
             WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
             AND Fecha = '" . BuscarFecha(date('Y-m-d')) . "' ";

    // print_r($sql);die();
    $respuest = $this->db->datos($sql);
    return $respuest;

  }

  function DGSuscripcion($tabla = false)
  {

    $Trans_No = 250;
    $sql = "SELECT Cuotas As Ejemplar,Fecha,Pagos As Entregado,Cta As Sector,Comision,Capital,T_No,Item,CodigoU
         FROM Asiento_P
         WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
         AND T_No = " . $Trans_No . "
         AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'
         ORDER BY Cuotas,Fecha ";
    if ($tabla) {
      // $botones[0] = array('boton'=>'Eliminar Suscripcion', 'icono'=>'<i class="fa fa-trash"></i>', 'tipo'=>'danger', 'id'=>'' );
      $respuest = grilla_generica_new($sql, 'Asiento_P', '', $titulo = false, $botones = false, $check = false, $imagen = false, 1, 1, 1, 100);
    } else {
      $respuest = $this->db->datos($sql);
    }

    return $respuest;

  }

  function DCCtaVenta()
  {
    $sql = "SELECT DISTINCT CP.Cta_Ventas,CC.Cuenta
          FROM Catalogo_Productos As CP,Catalogo_Cuentas As CC
          WHERE CP.Item = '" . $_SESSION['INGRESO']['item'] . "'
          AND CP.Item = CC.Item
          AND CP.Cta_Ventas = CC.Codigo
          ORDER BY CP.Cta_Ventas ";
    $respuest = $this->db->datos($sql);
    return $respuest;

  }

  function DCEjecutivoModal($query)
  {
    $sql = "SELECT CR.Codigo,C.Cliente,C.CI_RUC,C.Porc_C
          FROM Clientes As C,Catalogo_Rol_Pagos As CR
          WHERE CR.Item = '" . $_SESSION['INGRESO']['item'] . "'
          AND CR.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
          AND C.Codigo = CR.Codigo ";
    if ($query) {
      $sql .= " AND C.Cliente like '%" . $query . "%' ";
    }
    $sql .= "ORDER BY C.Cliente ";
    // print_r($sql);die();
    $respuest = $this->db->datos($sql);
    return $respuest;

  }

  function delete_command1($TipoDoc, $Credito_No)
  {

    $sql = "DELETE
        FROM Prestamos 
        WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
        AND TP = '" . $TipoDoc . "'
        AND Credito_No = '" . $Credito_No . "' ";

    $sql .= "DELETE 
        FROM Trans_Suscripciones 
        WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
        AND TP = '" . $TipoDoc . "'
        AND Contrato_No = '" . $Credito_No . "' ";
    $respuest = $this->db->String_Sql($sql);
    return $respuest;

  }

  function AdoAux2()
  {
    $sql = "SELECT * 
            FROM Prestamos 
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
            AND Fecha = '" . date('Y-m-d') . "' ";
    $respuest = $this->db->datos($sql);
    return $respuest;

  }
  function AdoAux1()
  {
    $sql = "SELECT * 
            FROM Trans_Suscripciones 
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
            AND Fecha = '" . date('Y-m-d') . "' ";
    $respuest = $this->db->datos($sql);
    return $respuest;

  }
  //-------------------------------------fin de panel suscripcion-----------

  //----------------guia------------
  function DCCiudad($query = false)
  {
    $sql = "SELECT Descripcion_Rubro 
         FROM Tabla_Naciones 
         WHERE TR = 'C'";
    if ($query) {
      $sql .= " and Descripcion_Rubro like '%" . $query . "%'";
    }
    $sql .= "ORDER BY Descripcion_Rubro ";
    $respuest = $this->db->datos($sql);
    return $respuest;

  }
  function AdoPersonas($query)
  {
    $sql = "SELECT Cliente,CI_RUC,TD,Direccion,Codigo 
          FROM Clientes 
          WHERE TD IN ('C','R') ";
    if ($query) {
      $sql .= " and Cliente like '%" . $query . "%'";
    }
    $sql .= "ORDER BY Cliente OFFSET 0 ROWS FETCH NEXT 30 ROWS ONLY;";
    $respuest = $this->db->datos($sql);
    return $respuest;

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

  function MBoxFechaGRE_LostFocus($fecha, $serie = false)
  {
    $sql = "SELECT *
      FROM Catalogo_Lineas
      WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
      AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
      AND Fact = 'GR'
      AND '" . BuscarFecha($fecha) . "' BETWEEN Fecha and Vencimiento";
    if ($serie) {
      $sql .= " AND Serie='" . $serie . "'";
    }

    $sql .= " ORDER BY Serie ";

    // print_r($sql);die();
    $respuest = $this->db->datos($sql);
    return $respuest;
  }
  //-------------------fin guia-------------------

  //-----------------Listar_Ordenes---------------

  function Listar_Ordenes()
  {

    $sql = "SELECT OP.Factura,OP.CodigoC,OP.Fecha,C.Cliente,C.Grupo,C.CI_RUC,C.TD
         FROM Facturas As OP,Clientes As C
         WHERE OP.Item = '" . $_SESSION['INGRESO']['item'] . "'
         AND OP.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
         AND OP.TC = 'OP'
         AND OP.T <> 'A'
         AND OP.CodigoC = C.Codigo
         ORDER BY OP.Factura ";
    $respuest = $this->db->datos($sql);
    return $respuest;
  }
  //--------------fin Listar_Ordenes--------------

  function Detalle_impresion($OrdenNo){   
    //$sql = "SELECT Fecha,Producto,Cantidad,Precio,A,L,S   //error no existe columna L en la tabla Trans_Ticket
    $sql = "SELECT Fecha,Producto,Cantidad,Precio,A,S
          FROM Trans_Ticket
          WHERE Item = '".$_SESSION['INGRESO']['item']."'
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
          AND Ticket= $OrdenNo 
          AND TC = 'OP'       
          ORDER BY Producto ";
          $respuest  = $this->db->datos($sql);
          return $respuest;
   }


  function actualizar_Facturas_Auxiliares($FA)
  {
    $sql = "UPDATE Facturas_Auxiliares
      SET Fecha_Aut_GR = '" . BuscarFecha($FA['Fecha_Aut_GR']) . "', 
      Autorizacion_GR = '" . $FA['Autorizacion_GR'] . "', 
      Clave_Acceso_GR = '" . $FA['ClaveAcceso_GR'] . "', 
      Estado_SRI_GR = '" . $FA['Estado_SRI_GR'] . "', 
      Hora_Aut_GR = '" . $FA['Hora_GR'] . "' 
      WHERE Factura = " . $FA['Factura'] . "
      AND TC = '" . $FA['TC'] . "'
      AND Serie = '" . $FA['Serie'] . "'
      AND Autorizacion = '" . $FA['Autorizacion'] . "'
      AND Item = '" . $_SESSION['INGRESO']['item'] . "'
      AND Periodo ='" . $_SESSION['INGRESO']['periodo'] . "'";
      //print_r($sql);die();
    $respuest = $this->db->String_Sql($sql);
    return $respuest;
  }

  function Facturas_Impresas($TFA)
  {
    $sql = "UPDATE Facturas 
          SET P = 1 
          WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
          AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
          AND TC = '" . $TFA['TC'] . "' 
          AND Serie = '" . $TFA['Serie'] . "' 
          AND P = 0 ";
    if (($TFA['Desde'] + $TFA['Hasta']) > 0 && ($TFA['Desde'] <= $TFA['Hasta'])) {
      $sql .= " AND Factura BETWEEN " . $TFA['Desde'] . " and " . $TFA['Hasta'] . " ";
    } else {
      $sql .= " AND Factura = " . $TFA['Factura'] . " ";
    }
    $respuest = $this->db->String_Sql($sql);
    return $respuest;
  }


  function FechaInicialHistoricoFacturas()
  {
    $sSQL = "SELECT MIN(Fecha) As MinFecha " .
      "FROM Facturas " .
      "WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' ";
    return $this->db->datos($sSQL);
  }

}

?>