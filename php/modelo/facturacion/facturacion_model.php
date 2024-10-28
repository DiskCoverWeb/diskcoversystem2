<?php
require_once("../db/db.php");
require_once("../funciones/funciones.php");
class contabilidad_model{
    private $db;
	private $dbs;
    private $contacto;
	private $ID_Entidad     ="";
  private $Entidad        ="";
  private $Nombre_Entidad ="";

	private $ID_Usuario     ="";
  private $Nombre_Usuario ="";
  private $Mail           ="";
  private $Contrasena     ="";
  private $IP_Usuario     ="";
  
  private $Hora           ="";
  private $Fecha          ="";
  public $Mensaje        ="";
	var $vQuery;
 
    public function __construct(){
        $this->db=Conectar::conexion();
        $this->contacto=array();
    }
	//para conexion sql server
	public function conexionSQL(){
        $this->dbs=Conectar::conexionSQL();
        $this->contacto=array();
    }
    public function get_contacto(){
        $consulta=$this->db->query("select * from contacto;");
        while($filas=$consulta->fetch_assoc()){
            $this->contacto[]=$filas;
        }
        return $this->contacto;
    }

	public function set_contacto($sTabla, $vValores, $sCampos=NULL){
		$sInsert="";
		if ($sCampos==NULL):
			$sInsert = "INSERT INTO {$sTabla} VALUES({$vValores});";			
		else:
			$sInsert = "INSERT INTO {$sTabla} ({$sCampos}) VALUES ({$vValores});";
		endif;
		//echo $sInsert;
		
		$this->vQuery = $this->db->query($sInsert);
		return $this->vQuery;
	}

		
	private function EscribirMsg($Alerta,$Mensajes){
	  $MsgStrong= "<div class=\'alert alert-danger alert-dismissible fade in\' role=\'alert\'>".
	  "<button type=\'button\' class=\'close\' data-dismiss=\'alert\' aria-label=\'Close\'>".
				   "<span aria-hidden=\'true\'>x</span>".
				   "</button>".
				   "<strong>".$Alerta." </strong>".$Mensajes."".
				   "</div>";
	  return $MsgStrong;
	}
		
	function MsgBox($Mensajes){
		echo "<script>alert('".$Mensajes."');</script>";
	}
	//devuelve empresas asociadas a la entidad del usuario

	function getEmpresas($id_entidad){
		 $consulta=$this->db->query("SELECT * 
									 FROM `lista_empresas` 
									 WHERE IP_VPN_RUTA<>'.' 
									 AND Base_Datos<>'.' 
									 AND Usuario_DB<>'.' 
									 AND Contraseña_DB<>'.' 
									 AND Tipo_Base<>'.' 
									 AND Puerto<>'0'									 
									 AND `ID_Empresa`='".$id_entidad."';");
        while($filas=$consulta->fetch_assoc()){
            $empresa[]=$filas;
        }
        return $empresa;
	}
	//devuelve empresa seleccionada por id 
	function getEmpresasId($id_empresa){
		 $consulta=$this->db->query("SELECT * 
									FROM `lista_empresas` 
									WHERE IP_VPN_RUTA<>'.' 
									 AND Base_Datos<>'.' 
									 AND Usuario_DB<>'.' 
									 AND Contraseña_DB<>'.' 
									 AND Tipo_Base<>'.' 
									 AND Puerto<>'0' 
									 AND`ID`=".$id_empresa.";");
		//echo "SELECT * FROM `Lista_Empresas` 
		//							WHERE `ID`=".$id_empresa.";";
		//$filas=$consulta->fetch_assoc();
		//echo $filas['IP_VPN_RUTA'];
        while($filas=$consulta->fetch_assoc()){
            $empresa[]=$filas;
			//echo ' vvv '.$filas['IP_VPN_RUTA'];
        }
        return $empresa;
	}
	function ListarEmpresasMYSQL($ti=null,$Opcb=null,$Opcem=null,$OpcDG=null,$b=null,$opcr=null,$OpcCE=null,$desde=null,$hasta=null)
	{
		$sql='SELECT A.Id_Empresa,A.Item,Empresa,A.Fecha,
		 A.enero,
		 A.febrero,
		 A.marzo,
		 A.abril,
		 A.mayo,
		 A.junio,
		 A.julio,
		 A.agosto,
		 A.septiembre,
		 A.octubre,
		 A.noviembre,
		 A.diciembre 
		FROM
		(SELECT Id_Empresa,Item,Empresa,Fecha,
		IF((MONTH(Fecha) = 01 AND YEAR(Fecha) = 2020), Fecha, "") AS enero,
		IF((MONTH(Fecha) = 02 AND YEAR(Fecha) = 2020), Fecha, "") AS febrero,
		IF((MONTH(Fecha) = 03 AND YEAR(Fecha) = 2020), Fecha, "") AS marzo,
		IF((MONTH(Fecha) = 04 AND YEAR(Fecha) = 2020), Fecha, "") AS abril,
		IF((MONTH(Fecha) = 05 AND YEAR(Fecha) = 2020), Fecha, "") AS mayo,
		IF((MONTH(Fecha) = 06 AND YEAR(Fecha) = 2020), Fecha, "") AS junio,
		IF((MONTH(Fecha) = 07 AND YEAR(Fecha) = 2020), Fecha, "") AS julio,
		IF((MONTH(Fecha) = 08 AND YEAR(Fecha) = 2020), Fecha, "") AS agosto,
		IF((MONTH(Fecha) = 09 AND YEAR(Fecha) = 2020), Fecha, "") AS septiembre,
		IF((MONTH(Fecha) = 10 AND YEAR(Fecha) = 2020), Fecha, "") AS octubre,
		IF((MONTH(Fecha) = 11 AND YEAR(Fecha) = 2020), Fecha, "") AS noviembre,
		IF((MONTH(Fecha) = 12 AND YEAR(Fecha) = 2020), Fecha, "") AS diciembre FROM lista_empresas
		union
		SELECT Id_Empresa,Item,Empresa,Fecha_CE AS fecha,
		IF((MONTH(Fecha_CE) = 01 AND YEAR(Fecha_CE) = 2020), Fecha_CE, "") AS enero,
		IF((MONTH(Fecha_CE) = 02 AND YEAR(Fecha_CE) = 2020), Fecha_CE, "") AS febrero,
		IF((MONTH(Fecha_CE) = 03 AND YEAR(Fecha_CE) = 2020), Fecha_CE, "") AS marzo,
		IF((MONTH(Fecha_CE) = 04 AND YEAR(Fecha_CE) = 2020), Fecha_CE, "") AS abril,
		IF((MONTH(Fecha_CE) = 05 AND YEAR(Fecha_CE) = 2020), Fecha_CE, "") AS mayo,
		IF((MONTH(Fecha_CE) = 06 AND YEAR(Fecha_CE) = 2020), Fecha_CE, "") AS junio,
		IF((MONTH(Fecha_CE) = 07 AND YEAR(Fecha_CE) = 2020), Fecha_CE, "") AS julio,
		IF((MONTH(Fecha_CE) = 08 AND YEAR(Fecha_CE) = 2020), Fecha_CE, "") AS agosto,
		IF((MONTH(Fecha_CE) = 09 AND YEAR(Fecha_CE) = 2020), Fecha_CE, "") AS septiembre,
		IF((MONTH(Fecha_CE) = 10 AND YEAR(Fecha_CE) = 2020), Fecha_CE, "") AS octubre,
		IF((MONTH(Fecha_CE) = 11 AND YEAR(Fecha_CE) = 2020), Fecha_CE, "") AS noviembre,
		IF((MONTH(Fecha_CE) = 12 AND YEAR(Fecha_CE) = 2020), Fecha_CE, "") AS diciembre FROM lista_empresas
		union
		SELECT Id_Empresa,Item,Empresa,Fecha_VPN AS fecha,
		IF((MONTH(Fecha_VPN) = 01 AND YEAR(Fecha_VPN) = 2020), Fecha_VPN, "") AS enero,
		IF((MONTH(Fecha_VPN) = 02 AND YEAR(Fecha_VPN) = 2020), Fecha_VPN, "") AS febrero,
		IF((MONTH(Fecha_VPN) = 03 AND YEAR(Fecha_VPN) = 2020), Fecha_VPN, "") AS marzo,
		IF((MONTH(Fecha_VPN) = 04 AND YEAR(Fecha_VPN) = 2020), Fecha_VPN, "") AS abril,
		IF((MONTH(Fecha_VPN) = 05 AND YEAR(Fecha_VPN) = 2020), Fecha_VPN, "") AS mayo,
		IF((MONTH(Fecha_VPN) = 06 AND YEAR(Fecha_VPN) = 2020), Fecha_VPN, "") AS junio,
		IF((MONTH(Fecha_VPN) = 07 AND YEAR(Fecha_VPN) = 2020), Fecha_VPN, "") AS julio,
		IF((MONTH(Fecha_VPN) = 08 AND YEAR(Fecha_VPN) = 2020), Fecha_VPN, "") AS agosto,
		IF((MONTH(Fecha_VPN) = 09 AND YEAR(Fecha_VPN) = 2020), Fecha_VPN, "") AS septiembre,
		IF((MONTH(Fecha_VPN) = 10 AND YEAR(Fecha_VPN) = 2020), Fecha_VPN, "") AS octubre,
		IF((MONTH(Fecha_VPN) = 11 AND YEAR(Fecha_VPN) = 2020), Fecha_VPN, "") AS noviembre,
		IF((MONTH(Fecha_VPN) = 12 AND YEAR(Fecha_VPN) = 2020), Fecha_VPN, "") AS diciembre FROM lista_empresas) AS A
		ORDER BY A.Id_Empresa ,A.Item';
		
	}
	//consulta empresa
	function ListarEmpresasSQL($ti=null,$Opcb=null,$Opcem=null,$OpcDG=null,$b=null,$opcr=null,$OpcCE=null,$desde=null,$hasta=null){
		
		$cid = Conectar::conexion('MYSQL');
		//echo $desde.'  '.$hasta;
		$f1 = new DateTime($desde);
		$f2 = new DateTime($hasta);

		$cant_meses = $f2->diff($f1);
		$cant_meses = $cant_meses->format('%m'); //devuelve el numero de meses entre ambas fechas.
		$listaMeses = array($f1->format('Y-m-d'));
		$mes = explode('-',$listaMeses[0]);
		$sql1='';
		$sql2='';
		$sql3='';
		$sql4='';
		if($mes[1]=='01')
		{
			$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS enero,';
			$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS enero,';
			$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS enero,';
			$sql4=$sql4.' A.enero,';
		}
		if($mes[1]=='02')
		{
			$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS febrero,';
			$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS febrero,';
			$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS febrero,';
			$sql4=$sql4.' A.febrero,';
		}
		if($mes[1]=='03')
		{
			$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS marzo,';
			$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS marzo,';
			$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS marzo,';
			$sql4=$sql4.' A.marzo,';
		}
		if($mes[1]=='04')
		{
			$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS abril,';
			$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS abril,';
			$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS abril,';
			$sql4=$sql4.' A.abril,';
		}
		if($mes[1]=='05')
		{
			$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS mayo,';
			$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS mayo,';
			$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS mayo,';
			$sql4=$sql4.' A.mayo,';
		}
		if($mes[1]=='06')
		{
			$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS junio,';
			$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS junio,';
			$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS junio,';
			$sql4=$sql4.' A.junio,';
		}
		if($mes[1]=='07')
		{
			$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS julio,';
			$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS julio,';
			$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS julio,';
			$sql4=$sql4.' A.julio,';
		}
		if($mes[1]=='08')
		{
			$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS agosto,';
			$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS agosto,';
			$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS agosto,';
			$sql4=$sql4.' A.agosto,';
		}
		if($mes[1]=='09')
		{
			$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS septiembre,';
			$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS septiembre,';
			$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS septiembre,';
			$sql4=$sql4.' A.septiembre,';
		}
		if($mes[1]=='10')
		{
			$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS octubre,';
			$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS octubre,';
			$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS octubre,';
			$sql4=$sql4.' A.octubre,';
		}
		if($mes[1]=='11')
		{
			$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS noviembre,';
			$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS noviembre,';
			$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS noviembre,';
			$sql4=$sql4.' A.noviembre,';
		}
		if($mes[1]=='12')
		{
			$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS diciembre,';
			$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS diciembre,';
			$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS diciembre,';
			$sql4=$sql4.' A.diciembre,';
		}
		for ($i = 1; $i <= $cant_meses; $i++) {
			$ultimaFecha = end($listaMeses);
			$ultimaFecha = new DateTime($ultimaFecha);
			$nuevaFecha = $ultimaFecha->add(new DateInterval("P1M"));
			$nuevaFecha = $nuevaFecha->format('Y-m-d');
			$mes = explode('-',$nuevaFecha);
			if($mes[1]=='01')
			{
				$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS enero,';
				$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS enero,';
				$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS enero,';
				$sql4=$sql4.' A.enero,';
			}
			if($mes[1]=='02')
			{
				$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS febrero,';
				$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS febrero,';
				$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS febrero,';
				$sql4=$sql4.' A.febrero,';
			}
			if($mes[1]=='03')
			{
				$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS marzo,';
				$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS marzo,';
				$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS marzo,';
				$sql4=$sql4.' A.marzo,';
			}
			if($mes[1]=='04')
			{
				$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS abril,';
				$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS abril,';
				$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS abril,';
				$sql4=$sql4.' A.abril,';
			}
			if($mes[1]=='05')
			{
				$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS mayo,';
				$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS mayo,';
				$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS mayo,';
				$sql4=$sql4.' A.mayo,';
			}
			if($mes[1]=='06')
			{
				$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS junio,';
				$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS junio,';
				$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS junio,';
				$sql4=$sql4.' A.junio,';
			}
			if($mes[1]=='07')
			{
				$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS julio,';
				$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS julio,';
				$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS julio,';
				$sql4=$sql4.' A.julio,';
			}
			if($mes[1]=='08')
			{
				$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS agosto,';
				$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS agosto,';
				$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS agosto,';
				$sql4=$sql4.' A.agosto,';
			}
			if($mes[1]=='09')
			{
				$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS septiembre,';
				$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS septiembre,';
				$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS septiembre,';
				$sql4=$sql4.' A.septiembre,';
			}
			if($mes[1]=='10')
			{
				$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS octubre,';
				$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS octubre,';
				$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS octubre,';
				$sql4=$sql4.' A.octubre,';
			}
			if($mes[1]=='11')
			{
				$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS noviembre,';
				$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS noviembre,';
				$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS noviembre,';
				$sql4=$sql4.' A.noviembre,';
			}
			if($mes[1]=='12')
			{
				$sql1=$sql1.' IF((MONTH(Fecha) = '.$mes[1].' AND YEAR(Fecha) = '.$mes[0].'), Fecha, "") AS diciembre,';
				$sql2=$sql2.' IF((MONTH(Fecha_CE) = '.$mes[1].' AND YEAR(Fecha_CE) = '.$mes[0].'), Fecha_CE, "") AS diciembre,';
				$sql3=$sql3.' IF((MONTH(Fecha_VPN) = '.$mes[1].' AND YEAR(Fecha_VPN) = '.$mes[0].'), Fecha_VPN, "") AS diciembre,';
				$sql4=$sql4.' A.diciembre,';
			}
			array_push($listaMeses, $nuevaFecha) ;
		}
		$longitud_cad = strlen($sql1); 
		$sql1 = substr_replace($sql1," ",$longitud_cad-1,1); 
		$longitud_cad = strlen($sql2); 
		$sql2 = substr_replace($sql2," ",$longitud_cad-1,1); 
		$longitud_cad = strlen($sql3); 
		$sql3 = substr_replace($sql3," ",$longitud_cad-1,1); 
		$longitud_cad = strlen($sql4); 
		$sql4 = substr_replace($sql4," ",$longitud_cad-1,1); 
		//$stmt = str_replace("ï»¿", "", $stmt);
		//var_dump($listaMeses);
		//echo $sql1.' '.$sql2.' '.$sql3;
		//die();
		 $sql ='SELECT A.tipo,A.Item,A.Empresa,A.Fecha,
		 '.$sql4.' 
		FROM
		(SELECT "Licencia" as tipo,Item,Id_Empresa,Empresa,Fecha,
		'.$sql1.' FROM lista_empresas
		union
		SELECT "CE" as tipo,Item,Id_Empresa,Empresa,Fecha_CE AS fecha,
		'.$sql2.' FROM lista_empresas
		union
		SELECT "VPN" as tipo,Item,Id_Empresa,Empresa,Fecha_VPN AS fecha,
		'.$sql3.' FROM lista_empresas) AS A
		where (Fecha BETWEEN "'.$desde.'" AND "'.$hasta.'" )
		ORDER BY A.fecha,A.Id_Empresa ,A.Item';
		//echo $sql;
		//die();
		$consulta=$cid->query($sql) or die($cid->error);
		//grilla_generica($consulta,null,NULL,'1',null,null,'MYSQL');
		
		//grilla_generica($consulta,null,NULL,'1',null,null,'MYSQL');
		//para saber si es excel o grilla
		if($opcr==null or $opcr==1)
		{
			//grilla_generica($stmt,$ti,$camne,$b,null,null,null,true);
			grilla_generica($consulta,null,NULL,'1',null,null,'MYSQL');
		}
		if($opcr==2)
		{
			//die();
			exportar_excel_generico($consulta,$ti,null,null,'MYSQL');
		}
		
	}
	//consulta listar balance sql server
	function ListarTipoDeBalanceSQL($ti=null,$Opcb=null,$Opcem=null,$OpcDG=null,$b=null,$opcr=null,$OpcCE=null){
		//opciones para generar consultas
		if($Opcb==null)
		{
			if($OpcCE=='1')
			{
				$sql="SELECT DG,Codigo_Ext AS Codigo ,Cuenta,Saldo_Anterior,Debitos,Creditos,Saldo_Total,TC ";
			}
			else
			{
				$sql="SELECT DG,Codigo,Cuenta,Saldo_Anterior,Debitos,Creditos,Saldo_Total,TC ";
			}
				$sql=$sql."  FROM Catalogo_Cuentas 
				  WHERE (Debitos<>0 OR Creditos<>0 OR Saldo_Total<>0) 
				  AND Item = '".$_SESSION['INGRESO']['item']."' 
				  AND Periodo = '".$_SESSION['INGRESO']['periodo']."'  ";
			if($OpcDG!=null AND $OpcDG!='')
			{
				$sql=$sql." AND DG = '".$OpcDG."' ";
		    }
			
			//configuracion de campos para que tome diseño distinto
			//titulo accion
			$camne['TITULO'][0]='color_fila';
			$camne['TITULO'][1]='italica';
			$camne['TITULO'][2]='subrayar';
			//campo a evaluar clave=valor, 
			$camne['CAMPOE'][0]='0=G';
			$camne['CAMPOE'][1][0]='7=P';
			$camne['CAMPOE'][1][1]='7=C';
			$camne['CAMPOE'][1][2]='7=G';
			$camne['CAMPOE'][1][3]='7=I';
			$camne['CAMPOE'][1][4]='7=N';
			$camne['CAMPOE'][2][0]='7=P';
			$camne['CAMPOE'][2][1]='7=C';
			$camne['CAMPOE'][2][2]='7=CJ';
			$camne['CAMPOE'][2][3]='7=CR';
			$camne['CAMPOE'][2][4]='7=CT';
			$camne['CAMPOE'][2][5]='7=BA';
			$camne['CAMPOE'][2][6]='7=CF';
			$camne['CAMPOE'][2][7]='7=CB';
			//campo a afectar si es fila TODOS o vacio
			$camne['CAMPOA'][0]='TODOS';
			$camne['CAMPOA'][1][0]='2';
			//$camne['CAMPOA'][1][1]='1';
			//$camne['CAMPOA'][1][2]='2';
			$camne['CAMPOA'][2][0]='2';
			//$camne['CAMPOA'][2][1]='1';
			//signo para compara valores
			$camne['SIGNO'][0]='=';
			$camne['SIGNO'][1][0]='<>';
			$camne['SIGNO'][1][1]='<>';
			$camne['SIGNO'][1][2]='<>';
			$camne['SIGNO'][1][3]='<>';
			$camne['SIGNO'][1][4]='<>';
			$camne['SIGNO'][2][0]='=';
			$camne['SIGNO'][2][1]='=';
			$camne['SIGNO'][2][2]='=';
			$camne['SIGNO'][2][3]='=';
			$camne['SIGNO'][2][4]='=';
			$camne['SIGNO'][2][5]='=';
			$camne['SIGNO'][2][6]='=';
			$camne['SIGNO'][2][7]='=';
			//valor adicional ejemplo color de fila
			$camne['ADICIONAL'][0]='black';	 
		}
		if($Opcb=='1' OR $Opcb=='2'  OR $Opcb=='4')
		{
			if($OpcCE=='1')
			{
				$sql="SELECT DG,Codigo_Ext AS Codigo ,Cuenta,Saldo_Anterior,Debitos,Creditos,Saldo_Total,TC ";
			}
			else
			{
				$sql="SELECT DG,Codigo,Cuenta,Saldo_Anterior,Debitos,Creditos,Saldo_Total,TC ";
			}
				$sql=$sql." FROM Catalogo_Cuentas 
				  WHERE (Debitos<>0 OR Creditos<>0 OR Saldo_Total<>0) 
				  AND Item = '".$_SESSION['INGRESO']['item']."' 
				  AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
			if($OpcDG!=null AND $OpcDG!='')
			{
				$sql=$sql." AND DG = '".$OpcDG."' ";
		    }
			
			//configuracion de campos para que tome diseño distinto
			//titulo accion
			$camne['TITULO'][0]='color_fila';
			$camne['TITULO'][1]='italica';
			$camne['TITULO'][2]='subrayar';
			//campo a evaluar clave=valor, 
			$camne['CAMPOE'][0]='0=G';
			$camne['CAMPOE'][1][0]='7=P';
			$camne['CAMPOE'][1][1]='7=C';
			$camne['CAMPOE'][1][2]='7=G';
			$camne['CAMPOE'][1][3]='7=I';
			$camne['CAMPOE'][1][4]='7=N';
			$camne['CAMPOE'][2][0]='7=P';
			$camne['CAMPOE'][2][1]='7=C';
			$camne['CAMPOE'][2][2]='7=CJ';
			$camne['CAMPOE'][2][3]='7=CR';
			$camne['CAMPOE'][2][4]='7=CT';
			$camne['CAMPOE'][2][5]='7=BA';
			$camne['CAMPOE'][2][6]='7=CF';
			$camne['CAMPOE'][2][7]='7=CB';
			//campo a afectar si es fila TODOS o vacio
			$camne['CAMPOA'][0]='TODOS';
			$camne['CAMPOA'][1][0]='2';
			//$camne['CAMPOA'][1][1]='1';
			$camne['CAMPOA'][2][0]='2';
			//signo para compara valores
			$camne['SIGNO'][0]='=';
			$camne['SIGNO'][1][0]='<>';
			$camne['SIGNO'][1][1]='<>';
			$camne['SIGNO'][1][2]='<>';
			$camne['SIGNO'][1][3]='<>';
			$camne['SIGNO'][1][4]='<>';
			$camne['SIGNO'][2][0]='=';
			$camne['SIGNO'][2][1]='=';
			$camne['SIGNO'][2][2]='=';
			$camne['SIGNO'][2][3]='=';
			$camne['SIGNO'][2][4]='=';
			$camne['SIGNO'][2][5]='=';
			$camne['SIGNO'][2][6]='=';
			$camne['SIGNO'][2][7]='=';
			//valor adicional ejemplo color de fila
			$camne['ADICIONAL'][0]='black';	  
		}
		if($Opcb=='5' )
		{
			/*
			"SELECT Codigo,Cuenta,Total_N6,Total_N5,Total_N4,Total_N3,Total_N2,Total_N1,DG,TC " _
              & "FROM Catalogo_Cuentas " _
              & "WHERE Item = '" & NumEmpresa & "' " _
              & "AND Periodo = '" & Periodo_Contable & "' " _
              & "AND (Total_N6+Total_N5+Total_N4+Total_N3+Total_N2+Total_N1)<>0 " _
              & "AND TB = 'ES' "
         If OpcG.value Then sSQL = sSQL & "AND DG = 'G' "
         If OpcD.value Then sSQL = sSQL & "AND DG = 'D' "
         sSQL = sSQL & "ORDER BY Codigo "
			*/
			if($OpcCE=='1')
			{
				$sql="SELECT Codigo_Ext AS Codigo ,Cuenta,Total_N6,Total_N5,Total_N4,Total_N3,Total_N2,Total_N1,DG,TC ";
			}
			else
			{
				$sql="SELECT Codigo,Cuenta,Total_N6,Total_N5,Total_N4,Total_N3,Total_N2,Total_N1,DG,TC ";
			}
				$sql=$sql." FROM Catalogo_Cuentas 
				  WHERE  Item = '".$_SESSION['INGRESO']['item']."' 
				  AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
				  AND (Total_N6+Total_N5+Total_N4+Total_N3+Total_N2+Total_N1)<>0 
				  AND TB = 'ES' ";
			if($OpcDG!=null AND $OpcDG!='')
			{
				$sql=$sql." AND DG = '".$OpcDG."' ";
		    }
			$camne=array();
			//configuracion de campos para que tome diseño distinto
			//titulo accion
			$camne['TITULO'][0]='color_fila';
			$camne['TITULO'][1]='indentar';
			$camne['TITULO'][2]='italica';
			$camne['TITULO'][3]='subrayar';
			//campo a evaluar clave=valor, 
			$camne['CAMPOE'][0]='8=G';
			$camne['CAMPOE'][1]='0=contar';
			$camne['CAMPOE'][2][0]='9=P';
			$camne['CAMPOE'][2][1]='9=C';
			$camne['CAMPOE'][2][2]='9=G';
			$camne['CAMPOE'][2][3]='9=I';
			$camne['CAMPOE'][2][4]='9=N';
			$camne['CAMPOE'][3][0]='9=P';
			$camne['CAMPOE'][3][1]='9=C';
			$camne['CAMPOE'][3][2]='9=CJ';
			$camne['CAMPOE'][3][3]='9=CR';
			$camne['CAMPOE'][3][4]='9=CT';
			$camne['CAMPOE'][3][5]='9=BA';
			$camne['CAMPOE'][3][6]='9=CF';
			$camne['CAMPOE'][3][7]='9=CB';
			//campo a afectar si es fila TODOS o vacio
			$camne['CAMPOA'][0]='TODOS';
			$camne['CAMPOA'][1]='1';
			$camne['CAMPOA'][2][0]='1';
			//$camne['CAMPOA'][2][1]='1';
			$camne['CAMPOA'][3][0]='1';
			//$camne['CAMPOA'][3][1]='1';
			//signo para compara valores
			$camne['SIGNO'][0]='=';
			$camne['SIGNO'][1]='=';
			$camne['SIGNO'][2][0]='<>';
			$camne['SIGNO'][2][1]='<>';
			$camne['SIGNO'][2][2]='<>';
			$camne['SIGNO'][2][3]='<>';
			$camne['SIGNO'][2][4]='<>';
			$camne['SIGNO'][3][0]='=';
			$camne['SIGNO'][3][1]='=';
			$camne['SIGNO'][3][2]='=';
			$camne['SIGNO'][3][3]='=';
			$camne['SIGNO'][3][4]='=';
			$camne['SIGNO'][3][5]='=';
			$camne['SIGNO'][3][6]='=';
			$camne['SIGNO'][3][7]='=';
			//valor adicional ejemplo color de fila
			$camne['ADICIONAL'][0]='black';	  
		}
		if($Opcb=='6' )
		{
			if($OpcCE=='1')
			{
				$sql="SELECT Codigo_Ext AS Codigo,Cuenta,Total_N6,Total_N5,Total_N4,Total_N3,Total_N2,Total_N1,DG,TC ";
			}
			else
			{
				$sql="SELECT Codigo,Cuenta,Total_N6,Total_N5,Total_N4,Total_N3,Total_N2,Total_N1,DG,TC ";
			}
				$sql=$sql." FROM Catalogo_Cuentas 
				  WHERE  Item = '".$_SESSION['INGRESO']['item']."' 
				  AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
				  AND (Total_N6+Total_N5+Total_N4+Total_N3+Total_N2+Total_N1)<>0
				  AND TB = 'ER' ";
			if($OpcDG!=null AND $OpcDG!='')
			{
				$sql=$sql." AND DG = '".$OpcDG."' ";
		    }
			
			$camne=array();		
			//configuracion de campos para que tome diseño distinto
			//titulo accion
			$camne['TITULO'][0]='color_fila';
			$camne['TITULO'][1]='indentar';
			$camne['TITULO'][2]='italica';
			$camne['TITULO'][3]='subrayar';
			//campo a evaluar clave=valor, 
			$camne['CAMPOE'][0]='8=G';
			$camne['CAMPOE'][1]='0=contar';
			$camne['CAMPOE'][2][0]='9=P';
			$camne['CAMPOE'][2][1]='9=C';
			$camne['CAMPOE'][2][2]='9=G';
			$camne['CAMPOE'][2][3]='9=I';
			$camne['CAMPOE'][2][4]='9=N';
			$camne['CAMPOE'][3][0]='9=P';
			$camne['CAMPOE'][3][1]='9=C';
			$camne['CAMPOE'][3][2]='9=CJ';
			$camne['CAMPOE'][3][3]='9=CR';
			$camne['CAMPOE'][3][4]='9=CT';
			$camne['CAMPOE'][3][5]='9=BA';
			$camne['CAMPOE'][3][6]='9=CF';
			$camne['CAMPOE'][3][7]='9=CB';
			//campo a afectar si es fila TODOS o vacio
			$camne['CAMPOA'][0]='TODOS';
			$camne['CAMPOA'][1]='1';
			$camne['CAMPOA'][2][0]='1';
			//$camne['CAMPOA'][2][1]='1';
			$camne['CAMPOA'][3][0]='1';
			//$camne['CAMPOA'][3][1]='1';
			//signo para compara valores
			$camne['SIGNO'][0]='=';
			$camne['SIGNO'][1]='=';
			$camne['SIGNO'][2][0]='<>';
			$camne['SIGNO'][2][1]='<>';
			$camne['SIGNO'][2][2]='<>';
			$camne['SIGNO'][2][3]='<>';
			$camne['SIGNO'][2][4]='<>';
			$camne['SIGNO'][3][0]='=';
			$camne['SIGNO'][3][1]='=';
			$camne['SIGNO'][3][2]='=';
			$camne['SIGNO'][3][3]='=';
			$camne['SIGNO'][3][4]='=';
			$camne['SIGNO'][3][5]='=';
			$camne['SIGNO'][3][6]='=';
			$camne['SIGNO'][3][7]='=';
			//valor adicional ejemplo color de fila
			$camne['ADICIONAL'][0]='black';	   
		}
		if($OpcCE=='1')
		{
			$sql=$sql." AND Codigo_Ext <> '.' ";
		}
		else
		{
			$sql=$sql." AND Codigo <> '.' ";
		}
		$sql=$sql." ORDER BY Codigo ";
		//echo $sql.' '.$_SESSION['INGRESO']['IP_VPN_RUTA'];
		$stmt = sqlsrv_query( $this->dbs, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		//para saber si es excel o grilla
		if($opcr==null or $opcr==1)
		{
			grilla_generica($stmt,$ti,$camne,$b,null,null,null,true);
		}
		if($opcr==2)
		{
			//die();
			exportar_excel_generico($stmt,$ti,$camne,$b);
		}
		/*while( $obj = sqlsrv_fetch_object( $stmt)) 
		{
			$cam=date_format($obj->Fecha_Inicial,'Y-m-d H:i:s');
			$empresa[$i]['Fecha_Inicial']=$cam;
			$cam=date_format($obj->Fecha_Final,'Y-m-d H:i:s');
			$empresa[$i]['Fecha_Final']=$cam;
			//echo $empresa[$i]['Fecha_Inicial'];
			$i++;
		}*/
		//sqlsrv_close( $this->dbs );
        //return $empresa;
	}
	//consulta listar balance mysql
	function ListarTipoDeBalanceMYSQL($ti=null,$Opcb=null,$Opcem=null,$OpcDG=null,$b=null,$opcr=null,$OpcCE=null){
		//opciones para generar consultas
		if($Opcb==null)
		{
			$sql="SELECT DG,Codigo,Cuenta,Saldo_Anterior,Debitos,Creditos,Saldo_Total,TC 
				  FROM Catalogo_Cuentas 
				  WHERE (Debitos<>0 OR Creditos<>0 OR Saldo_Total<>0) 
				  AND Item = '".$_SESSION['INGRESO']['item']."' 
				  AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
				  ORDER BY Codigo ";
				 
		}
		if($Opcb=='1' OR $Opcb=='2'  OR $Opcb=='4')
		{
			$sql="SELECT DG,Codigo,Cuenta,Saldo_Anterior,Debitos,Creditos,Saldo_Total,TC 
				  FROM Catalogo_Cuentas 
				  WHERE (Debitos<>0 OR Creditos<>0 OR Saldo_Total<>0) 
				  AND Item = '".$_SESSION['INGRESO']['item']."' 
				  AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
				  ORDER BY Codigo ";
				  
		}
		if($Opcb=='5' )
		{
			/*
			"SELECT Codigo,Cuenta,Total_N6,Total_N5,Total_N4,Total_N3,Total_N2,Total_N1,DG,TC " _
              & "FROM Catalogo_Cuentas " _
              & "WHERE Item = '" & NumEmpresa & "' " _
              & "AND Periodo = '" & Periodo_Contable & "' " _
              & "AND (Total_N6+Total_N5+Total_N4+Total_N3+Total_N2+Total_N1)<>0 " _
              & "AND TB = 'ES' "
         If OpcG.value Then sSQL = sSQL & "AND DG = 'G' "
         If OpcD.value Then sSQL = sSQL & "AND DG = 'D' "
         sSQL = sSQL & "ORDER BY Codigo "
			*/
			$sql="SELECT Codigo,Cuenta,Total_N6,Total_N5,Total_N4,Total_N3,Total_N2,Total_N1,DG,TC
				  FROM Catalogo_Cuentas 
				  WHERE  Item = '".$_SESSION['INGRESO']['item']."' 
				  AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
				  AND (Total_N6+Total_N5+Total_N4+Total_N3+Total_N2+Total_N1)<>0 
				  AND TB = 'ES' 
				  ORDER BY Codigo ";
				  
		}
		//echo $sql;
		$consulta=$this->db->query($sql);
		 $cant = mysqli_num_fields($consulta);
      $campos="";
	  ?>
		<div class="box-body no-padding">
            <table class="table table-striped">
				<tr>
					<th colspan='<?php echo $cant; ?>' style='text-align: center;'><?php echo $ti; ?></th>
				</tr>
                <tr>
					<?php
					//obtenemos los campos
				  for ($x=0;$x<$cant;$x++){
					  $campo[$x] = $consulta->fetch_field_direct($x);
					  echo "<th>".$campo[$x]->name."</th>";
				  }
				  ?>
				</tr>
					<?php
					while($filas=$consulta->fetch_array(MYSQLI_NUM)){
						$empresa[]=$filas;
						?>
						<tr>
						<?php
						 for ($x=0;$x<$cant;$x++){
							  
							  echo "<td>".$filas[$x]."</td>";
						  }
						  ?>
							</tr>
							<?php
						//echo ' vvv '.$filas['IP_VPN_RUTA'];
					}
				
 ?>
			</table>
		</div>
		  <?php	
        die();	
		$stmt = sqlsrv_query( $this->dbs, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		} 
		//cantidad de campos
		$cant=0;
		//guardamos los campos
		$campo='';
		//obtenemos los campos 
		foreach( sqlsrv_field_metadata( $stmt ) as $fieldMetadata ) {
			foreach( $fieldMetadata as $name => $value) {
				if(!is_numeric($value))
				{
					if($value!='')
					{
						$cant++;
					}
				}
			}
		}
		?>
		<div class="box-body no-padding">
            <table class="table table-striped">
				<tr>
					<th colspan='<?php echo $cant; ?>' style='text-align: center;'><?php echo $ti; ?></th>
				</tr>
                <tr>
					<?php
					//cantidad campos
					$cant=0;
					//guardamos los campos
					$campo='';
					//obtenemos los campos 
					foreach( sqlsrv_field_metadata( $stmt ) as $fieldMetadata ) {
						//$camp='';
						foreach( $fieldMetadata as $name => $value) {
							if(!is_numeric($value))
							{
								if($value!='')
								{
									echo "<th>".$value."</th>";
									$camp=$value;
									$campo[$cant]=$camp;
									//echo ' dd '.$campo[$cant];
									$cant++;
									//echo $value.' cc '.$cant.' ';
								}
							}
						   //echo "$name: $value<br />";
						}
						
						  //echo "<br />";
					}
					?>
				</tr>
				
                 
					<?php
					//echo $cant.' fffff ';
					$i=0;
					while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
						
							?>
							<tr>
							<?php
						for($i=0;$i<$cant;$i++)
						{
							echo "<td>".$row[$i]."</td>";
						}
						/*$cam=$campo[$i];
						echo "<td>".$row['DG']."</td>";
						echo "<td>".$row['Codigo']."</td>";
						echo "<td>".$row['Cuenta']."</td>";
						echo "<td>".$row['Saldo_Anterior']."</td>";
						echo "<td>".$row['Debitos']."</td>";
						echo "<td>".$row['Creditos']."</td>";
						echo "<td>".$row['Saldo_Total']."</td>";
						echo "<td>".$row['TC']."</td>";*/
						 ?>
						  </tr>
						  <?php
						
						//$campo
						  //echo $row[$i].", <br />";
						  $i++;
						  if($cant==($i))
						  {
							  
							  //echo $cant.' ddddd '.$i;
							  $i=0;
							 
						  }
					}
		 ?>
			</table>
		</div>
		  <?php

		/*while( $obj = sqlsrv_fetch_object( $stmt)) 
		{
			$cam=date_format($obj->Fecha_Inicial,'Y-m-d H:i:s');
			$empresa[$i]['Fecha_Inicial']=$cam;
			$cam=date_format($obj->Fecha_Final,'Y-m-d H:i:s');
			$empresa[$i]['Fecha_Final']=$cam;
			//echo $empresa[$i]['Fecha_Inicial'];
			$i++;
		}*/
		//sqlsrv_close( $this->dbs );
        //return $empresa;
	}
	//listar asiento
	function ListarAsientoSQL($ti,$Opcb,$Opcem,$OpcDG,$b)
	{
		//opciones para generar consultas
		if($Opcb==null)
		{
			$sql="SELECT CODIGO,CUENTA,PARCIAL_ME,DEBE ,HABER ,CHEQ_DEP,DETALLE,EFECTIVIZAR ,CODIGO_C,ME
					,T_No,Item,CodigoU,A_No
				FROM Asiento
				WHERE 
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '1726704230' ";
			if($OpcDG!=null AND $OpcDG!='')
			{
				$sql=$sql." AND DG = '".$OpcDG."' ";
		    }
			$sql=$sql." ORDER BY CODIGO ";
			/*$sql='SELECT TSiNo ,TByte ,TEntero,TEntero Largo ,TSimple,TDouble,TMoneda ,TDecimal,TIddeReplica
						  ,TAutonumerico ,TMemo ,TTexto,TFechaHora
					  FROM Tipo_Access';*/
			//echo $sql;
			//configuracion de campos para que tome diseño distinto
			//titulo accion
			
		}
		$stmt = sqlsrv_query( $this->dbs, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$camne=array();
		grilla_generica($stmt,$ti,NULL,$b);
	}
	//exportar excel balance sql server
	/*function exportarExcelSQL($ti=null,$Opcb=null,$Opcem=null,$OpcDG=null,$b=null){
		//opciones para generar consultas
		
		if($Opcb==null)
		{
			$sql="SELECT DG,Codigo,Cuenta,Saldo_Anterior,Debitos,Creditos,Saldo_Total,TC 
				  FROM Catalogo_Cuentas 
				  WHERE (Debitos<>0 OR Creditos<>0 OR Saldo_Total<>0) 
				  AND Item = '".$_SESSION['INGRESO']['item']."' 
				  AND Periodo = '".$_SESSION['INGRESO']['periodo']."'  ";
			if($OpcDG!=null AND $OpcDG!='')
			{
				$sql=$sql." AND DG = '".$OpcDG."' ";
		    }
			$sql=$sql." ORDER BY Codigo ";
			//configuracion de campos para que tome diseño distinto
			//titulo accion
			$camne['TITULO'][0]='color_fila';
			$camne['TITULO'][1]='italica';
			$camne['TITULO'][2]='subrayar';
			//campo a evaluar clave=valor, 
			$camne['CAMPOE'][0]='0=G';
			$camne['CAMPOE'][1][0]='7=P';
			$camne['CAMPOE'][1][1]='7=C';
			$camne['CAMPOE'][1][2]='7=G';
			$camne['CAMPOE'][1][3]='7=I';
			$camne['CAMPOE'][1][4]='7=N';
			$camne['CAMPOE'][2][0]='7=P';
			$camne['CAMPOE'][2][1]='7=C';
			$camne['CAMPOE'][2][2]='7=CJ';
			$camne['CAMPOE'][2][3]='7=CR';
			$camne['CAMPOE'][2][4]='7=CT';
			$camne['CAMPOE'][2][5]='7=BA';
			$camne['CAMPOE'][2][6]='7=CF';
			$camne['CAMPOE'][2][7]='7=CB';
			//campo a afectar si es fila TODOS o vacio
			$camne['CAMPOA'][0]='TODOS';
			$camne['CAMPOA'][1][0]='2';
			//$camne['CAMPOA'][1][1]='1';
			//$camne['CAMPOA'][1][2]='2';
			$camne['CAMPOA'][2][0]='2';
			//$camne['CAMPOA'][2][1]='1';
			//signo para compara valores
			$camne['SIGNO'][0]='=';
			$camne['SIGNO'][1][0]='<>';
			$camne['SIGNO'][1][1]='<>';
			$camne['SIGNO'][1][2]='<>';
			$camne['SIGNO'][1][3]='<>';
			$camne['SIGNO'][1][4]='<>';
			$camne['SIGNO'][2][0]='=';
			$camne['SIGNO'][2][1]='=';
			$camne['SIGNO'][2][2]='=';
			$camne['SIGNO'][2][3]='=';
			$camne['SIGNO'][2][4]='=';
			$camne['SIGNO'][2][5]='=';
			$camne['SIGNO'][2][6]='=';
			$camne['SIGNO'][2][7]='=';
			//valor adicional ejemplo color de fila
			$camne['ADICIONAL'][0]='black';	 
		}
		if($Opcb=='1' OR $Opcb=='2'  OR $Opcb=='4')
		{
			$sql="SELECT DG,Codigo,Cuenta,Saldo_Anterior,Debitos,Creditos,Saldo_Total,TC 
				  FROM Catalogo_Cuentas 
				  WHERE (Debitos<>0 OR Creditos<>0 OR Saldo_Total<>0) 
				  AND Item = '".$_SESSION['INGRESO']['item']."' 
				  AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
			if($OpcDG!=null AND $OpcDG!='')
			{
				$sql=$sql." AND DG = '".$OpcDG."' ";
		    }
			$sql=$sql." ORDER BY Codigo ";
			//configuracion de campos para que tome diseño distinto
			//titulo accion
			$camne['TITULO'][0]='color_fila';
			$camne['TITULO'][1]='italica';
			$camne['TITULO'][2]='subrayar';
			//campo a evaluar clave=valor, 
			$camne['CAMPOE'][0]='0=G';
			$camne['CAMPOE'][1][0]='7=P';
			$camne['CAMPOE'][1][1]='7=C';
			$camne['CAMPOE'][1][2]='7=G';
			$camne['CAMPOE'][1][3]='7=I';
			$camne['CAMPOE'][1][4]='7=N';
			$camne['CAMPOE'][2][0]='7=P';
			$camne['CAMPOE'][2][1]='7=C';
			$camne['CAMPOE'][2][2]='7=CJ';
			$camne['CAMPOE'][2][3]='7=CR';
			$camne['CAMPOE'][2][4]='7=CT';
			$camne['CAMPOE'][2][5]='7=BA';
			$camne['CAMPOE'][2][6]='7=CF';
			$camne['CAMPOE'][2][7]='7=CB';
			//campo a afectar si es fila TODOS o vacio
			$camne['CAMPOA'][0]='TODOS';
			$camne['CAMPOA'][1][0]='2';
			//$camne['CAMPOA'][1][1]='1';
			$camne['CAMPOA'][2][0]='2';
			//signo para compara valores
			$camne['SIGNO'][0]='=';
			$camne['SIGNO'][1][0]='<>';
			$camne['SIGNO'][1][1]='<>';
			$camne['SIGNO'][1][2]='<>';
			$camne['SIGNO'][1][3]='<>';
			$camne['SIGNO'][1][4]='<>';
			$camne['SIGNO'][2][0]='=';
			$camne['SIGNO'][2][1]='=';
			$camne['SIGNO'][2][2]='=';
			$camne['SIGNO'][2][3]='=';
			$camne['SIGNO'][2][4]='=';
			$camne['SIGNO'][2][5]='=';
			$camne['SIGNO'][2][6]='=';
			$camne['SIGNO'][2][7]='=';
			//valor adicional ejemplo color de fila
			$camne['ADICIONAL'][0]='black';	  
		}
		if($Opcb=='5' )
		{
			
			$sql="SELECT Codigo,Cuenta,Total_N6,Total_N5,Total_N4,Total_N3,Total_N2,Total_N1,DG,TC
				  FROM Catalogo_Cuentas 
				  WHERE  Item = '".$_SESSION['INGRESO']['item']."' 
				  AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
				  AND (Total_N6+Total_N5+Total_N4+Total_N3+Total_N2+Total_N1)<>0 
				  AND TB = 'ES' ";
			if($OpcDG!=null AND $OpcDG!='')
			{
				$sql=$sql." AND DG = '".$OpcDG."' ";
		    }
			$sql=$sql." ORDER BY Codigo ";
			$camne=array();
			//configuracion de campos para que tome diseño distinto
			//titulo accion
			$camne['TITULO'][0]='color_fila';
			$camne['TITULO'][1]='indentar';
			$camne['TITULO'][2]='italica';
			$camne['TITULO'][3]='subrayar';
			//campo a evaluar clave=valor, 
			$camne['CAMPOE'][0]='8=G';
			$camne['CAMPOE'][1]='0=contar';
			$camne['CAMPOE'][2][0]='9=P';
			$camne['CAMPOE'][2][1]='9=C';
			$camne['CAMPOE'][2][2]='9=G';
			$camne['CAMPOE'][2][3]='9=I';
			$camne['CAMPOE'][2][4]='9=N';
			$camne['CAMPOE'][3][0]='9=P';
			$camne['CAMPOE'][3][1]='9=C';
			$camne['CAMPOE'][3][2]='9=CJ';
			$camne['CAMPOE'][3][3]='9=CR';
			$camne['CAMPOE'][3][4]='9=CT';
			$camne['CAMPOE'][3][5]='9=BA';
			$camne['CAMPOE'][3][6]='9=CF';
			$camne['CAMPOE'][3][7]='9=CB';
			//campo a afectar si es fila TODOS o vacio
			$camne['CAMPOA'][0]='TODOS';
			$camne['CAMPOA'][1]='1';
			$camne['CAMPOA'][2][0]='1';
			//$camne['CAMPOA'][2][1]='1';
			$camne['CAMPOA'][3][0]='1';
			//$camne['CAMPOA'][3][1]='1';
			//signo para compara valores
			$camne['SIGNO'][0]='=';
			$camne['SIGNO'][1]='=';
			$camne['SIGNO'][2][0]='<>';
			$camne['SIGNO'][2][1]='<>';
			$camne['SIGNO'][2][2]='<>';
			$camne['SIGNO'][2][3]='<>';
			$camne['SIGNO'][2][4]='<>';
			$camne['SIGNO'][3][0]='=';
			$camne['SIGNO'][3][1]='=';
			$camne['SIGNO'][3][2]='=';
			$camne['SIGNO'][3][3]='=';
			$camne['SIGNO'][3][4]='=';
			$camne['SIGNO'][3][5]='=';
			$camne['SIGNO'][3][6]='=';
			$camne['SIGNO'][3][7]='=';
			//valor adicional ejemplo color de fila
			$camne['ADICIONAL'][0]='black';	  
		}
		if($Opcb=='6' )
		{
			$sql="SELECT Codigo,Cuenta,Total_N6,Total_N5,Total_N4,Total_N3,Total_N2,Total_N1,DG,TC
				  FROM Catalogo_Cuentas 
				  WHERE  Item = '".$_SESSION['INGRESO']['item']."' 
				  AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
				  AND (Total_N6+Total_N5+Total_N4+Total_N3+Total_N2+Total_N1)<>0
				  AND TB = 'ER' ";
			if($OpcDG!=null AND $OpcDG!='')
			{
				$sql=$sql." AND DG = '".$OpcDG."' ";
		    }
			$sql=$sql." ORDER BY Codigo ";
			$camne=array();		
			//configuracion de campos para que tome diseño distinto
			//titulo accion
			$camne['TITULO'][0]='color_fila';
			$camne['TITULO'][1]='indentar';
			$camne['TITULO'][2]='italica';
			$camne['TITULO'][3]='subrayar';
			//campo a evaluar clave=valor, 
			$camne['CAMPOE'][0]='8=G';
			$camne['CAMPOE'][1]='0=contar';
			$camne['CAMPOE'][2][0]='9=P';
			$camne['CAMPOE'][2][1]='9=C';
			$camne['CAMPOE'][2][2]='9=G';
			$camne['CAMPOE'][2][3]='9=I';
			$camne['CAMPOE'][2][4]='9=N';
			$camne['CAMPOE'][3][0]='9=P';
			$camne['CAMPOE'][3][1]='9=C';
			$camne['CAMPOE'][3][2]='9=CJ';
			$camne['CAMPOE'][3][3]='9=CR';
			$camne['CAMPOE'][3][4]='9=CT';
			$camne['CAMPOE'][3][5]='9=BA';
			$camne['CAMPOE'][3][6]='9=CF';
			$camne['CAMPOE'][3][7]='9=CB';
			//campo a afectar si es fila TODOS o vacio
			$camne['CAMPOA'][0]='TODOS';
			$camne['CAMPOA'][1]='1';
			$camne['CAMPOA'][2][0]='1';
			//$camne['CAMPOA'][2][1]='1';
			$camne['CAMPOA'][3][0]='1';
			//$camne['CAMPOA'][3][1]='1';
			//signo para compara valores
			$camne['SIGNO'][0]='=';
			$camne['SIGNO'][1]='=';
			$camne['SIGNO'][2][0]='<>';
			$camne['SIGNO'][2][1]='<>';
			$camne['SIGNO'][2][2]='<>';
			$camne['SIGNO'][2][3]='<>';
			$camne['SIGNO'][2][4]='<>';
			$camne['SIGNO'][3][0]='=';
			$camne['SIGNO'][3][1]='=';
			$camne['SIGNO'][3][2]='=';
			$camne['SIGNO'][3][3]='=';
			$camne['SIGNO'][3][4]='=';
			$camne['SIGNO'][3][5]='=';
			$camne['SIGNO'][3][6]='=';
			$camne['SIGNO'][3][7]='=';
			//valor adicional ejemplo color de fila
			$camne['ADICIONAL'][0]='black';	   
		}
		//echo $sql;
		$stmt = sqlsrv_query( $this->dbs, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		
		exportar_excel_generico($stmt,$ti,$camne,$b);

		
		//sqlsrv_close( $this->dbs );
        //return $empresa;
	}*/
	function ListarAsientoMYSQL($ti,$Opcb,$Opcem,$OpcDG,$b)
	{
		
	}
	//listar documento electronico $ch para opcion de checkbox $filtro para filtros sql
	function ListarDocEletronicoSQL($ti,$Opcb,$Opcem,$OpcDG,$b,$opcr=null,$ch=null,$start_from=null, 
	$record_per_page=null,$filtro=null)
	{
		/*select m.rownum 
		  from(SELECT ROW_NUMBER() OVER(ORDER BY ID) AS rownum,*
		  FROM Clientes) m 
		  WHERE m.rownum BETWEEN 11 AND 20*/
		//opciones para generar consultas
		//echo $filtro.' ggg ';
		if($filtro==null or $filtro=='')
		{
			$filtro=' 1=1 ';
		}
		if($Opcb==null)
		{
			if($record_per_page==null)
			{
				$sql="SELECT CASE
					WHEN
					  TD = 'FA'
						THEN
						   (select top(1) Cliente from Clientes where Codigo in (
						  select CodigoC from Facturas 
						  where Facturas.Factura=Trans_Documentos.Documento
						  AND Facturas.Serie=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
					WHEN
					  TD = 'NC'
						THEN
						  (select top(1) Cliente from Clientes where Codigo in (
						  select CodigoC from Trans_Abonos 
						  where Trans_Abonos.Factura=Trans_Documentos.Documento
						  AND Trans_Abonos.Serie=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
					 WHEN
					  TD = 'ND'
						THEN
						  (select top(1) Cliente from Clientes where Codigo in (
						  select CodigoC from Trans_Abonos 
						  where Trans_Abonos.Factura=Trans_Documentos.Documento
						  AND Trans_Abonos.Serie=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
					 WHEN
					  TD = 'NV'
						THEN
						  (select top(1) Cliente from Clientes where Codigo in (
						  select CodigoC from Facturas 
						  where Facturas.Factura=Trans_Documentos.Documento
						  AND Facturas.Serie=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
					 WHEN
					  TD = 'GR'
						THEN
						  (select top(1) Cliente from Clientes where Codigo in (
						  select CodigoC from Facturas 
						  where Facturas.Factura=Trans_Documentos.Documento
						  AND Facturas.Serie=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
					ELSE
					  (select top(1) Cliente from Clientes where Codigo in (
						  select IdProv from Trans_Compras 
						  where Trans_Compras.SecRetencion=Trans_Documentos.Documento
						  AND Trans_Compras.Serie_Retencion=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
				  END AS cliente, Clave_Acceso, TD, Serie, Documento
						FROM  Trans_Documentos WHERE
						".$filtro." ";
				$sql=$sql." ORDER BY Documento ";
			}
			else
			{
				$sql="SELECT m.numero, Clave_Acceso, m.cliente, TD, Serie, Documento 
				  from(SELECT ROW_NUMBER() OVER(ORDER BY Documento) AS numero,*,CASE
					WHEN
					  TD = 'FA'
						THEN
						   (select top(1) Cliente from Clientes where Codigo in (
						  select CodigoC from Facturas 
						  where Facturas.Factura=Trans_Documentos.Documento
						  AND Facturas.Serie=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
					WHEN
					  TD = 'NC'
						THEN
						  (select top(1) Cliente from Clientes where Codigo in (
						  select CodigoC from Trans_Abonos 
						  where Trans_Abonos.Factura=Trans_Documentos.Documento
						  AND Trans_Abonos.Serie=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
					 WHEN
					  TD = 'ND'
						THEN
						  (select top(1) Cliente from Clientes where Codigo in (
						  select CodigoC from Trans_Abonos 
						  where Trans_Abonos.Factura=Trans_Documentos.Documento
						  AND Trans_Abonos.Serie=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
					 WHEN
					  TD = 'NV'
						THEN
						  (select top(1) Cliente from Clientes where Codigo in (
						  select CodigoC from Facturas 
						  where Facturas.Factura=Trans_Documentos.Documento
						  AND Facturas.Serie=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
					 WHEN
					  TD = 'GR'
						THEN
						  (select top(1) Cliente from Clientes where Codigo in (
						  select CodigoC from Facturas 
						  where Facturas.Factura=Trans_Documentos.Documento
						  AND Facturas.Serie=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
					ELSE
					  (select top(1) Cliente from Clientes where Codigo in (
						  select IdProv from Trans_Compras 
						  where Trans_Compras.SecRetencion=Trans_Documentos.Documento
						  AND Trans_Compras.Serie_Retencion=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
				  END AS cliente
				  FROM Trans_Documentos  WHERE ".$filtro." ) m
				  WHERE ".$filtro." AND numero BETWEEN ".$start_from." AND ".$record_per_page." 
				  ORDER BY Documento ";
			}
			/*$sql='SELECT Item, Periodo, Clave_Acceso, Documento_Autorizado, TD, Serie, Documento, ID
					FROM  Trans_Documentos WHERE
					Item = '".$_SESSION['INGRESO']['item']."'  
					AND (ID=1 
						OR ID=2)';*/
			//echo $sql;
			//configuracion de campos para que tome diseño distinto
			//titulo accion
			
		}
		else
		{
			if($record_per_page==null)
			{
				$sql="SELECT CASE
					WHEN
					  TD = 'FA'
						THEN
						   (select top(1) Cliente from Clientes where Codigo in (
						  select CodigoC from Facturas 
						  where Facturas.Factura=Trans_Documentos.Documento
						  AND Facturas.Serie=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
					WHEN
					  TD = 'NC'
						THEN
						  (select top(1) Cliente from Clientes where Codigo in (
						  select CodigoC from Trans_Abonos 
						  where Trans_Abonos.Factura=Trans_Documentos.Documento
						  AND Trans_Abonos.Serie=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
					 WHEN
					  TD = 'ND'
						THEN
						  (select top(1) Cliente from Clientes where Codigo in (
						  select CodigoC from Trans_Abonos 
						  where Trans_Abonos.Factura=Trans_Documentos.Documento
						  AND Trans_Abonos.Serie=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
					 WHEN
					  TD = 'NV'
						THEN
						  (select top(1) Cliente from Clientes where Codigo in (
						  select CodigoC from Facturas 
						  where Facturas.Factura=Trans_Documentos.Documento
						  AND Facturas.Serie=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
					 WHEN
					  TD = 'GR'
						THEN
						  (select top(1) Cliente from Clientes where Codigo in (
						  select CodigoC from Facturas 
						  where Facturas.Factura=Trans_Documentos.Documento
						  AND Facturas.Serie=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
					ELSE
					  (select top(1) Cliente from Clientes where Codigo in (
						  select IdProv from Trans_Compras 
						  where Trans_Compras.SecRetencion=Trans_Documentos.Documento
						  AND Trans_Compras.Serie_Retencion=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
				  END AS cliente, Clave_Acceso, TD, Serie, Documento
						FROM  Trans_Documentos WHERE
						".$filtro." ";
				$sql=$sql." ORDER BY Documento ";
			}
			else
			{
				$sql="SELECT m.numero, Clave_Acceso, m.cliente, TD, Serie, Documento 
				  from(SELECT ROW_NUMBER() OVER(ORDER BY Documento) AS numero,*,CASE
					WHEN
					  TD = 'FA'
						THEN
						   (select top(1) Cliente from Clientes where Codigo in (
						  select CodigoC from Facturas 
						  where Facturas.Factura=Trans_Documentos.Documento
						  AND Facturas.Serie=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
					WHEN
					  TD = 'NC'
						THEN
						  (select top(1) Cliente from Clientes where Codigo in (
						  select CodigoC from Trans_Abonos 
						  where Trans_Abonos.Factura=Trans_Documentos.Documento
						  AND Trans_Abonos.Serie=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
					 WHEN
					  TD = 'ND'
						THEN
						  (select top(1) Cliente from Clientes where Codigo in (
						  select CodigoC from Trans_Abonos 
						  where Trans_Abonos.Factura=Trans_Documentos.Documento
						  AND Trans_Abonos.Serie=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
					 WHEN
					  TD = 'NV'
						THEN
						  (select top(1) Cliente from Clientes where Codigo in (
						  select CodigoC from Facturas 
						  where Facturas.Factura=Trans_Documentos.Documento
						  AND Facturas.Serie=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
					 WHEN
					  TD = 'GR'
						THEN
						  (select top(1) Cliente from Clientes where Codigo in (
						  select CodigoC from Facturas 
						  where Facturas.Factura=Trans_Documentos.Documento
						  AND Facturas.Serie=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
					ELSE
					   (select top(1) Cliente from Clientes where Codigo in (
						  select IdProv from Trans_Compras 
						  where Trans_Compras.SecRetencion=Trans_Documentos.Documento
						  AND Trans_Compras.Serie_Retencion=Trans_Documentos.Serie 
						  AND Item = '".$_SESSION['INGRESO']['item']."'  
						  ) )
				  END AS cliente
				  FROM Trans_Documentos  WHERE ".$filtro." ) m
				  WHERE ".$filtro." AND numero BETWEEN ".$start_from." AND ".$record_per_page." 
				  ORDER BY Documento ";
			}
			//echo $sql;
		}
		//echo $sql;
		$_SESSION['INGRESO']['sql']=$sql;
		$stmt = sqlsrv_query( $this->dbs, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$camne=array();
		//para saber si es excel o grilla
		if($opcr==null or $opcr==1)
		{
			grilla_generica($stmt,$ti,NULL,$b,$ch);
		}
		if($opcr==2)
		{
			exportar_excel_generico($stmt,$ti,NULL,$b);
		}
	}
	//listar documento electronico
	function ListarDocEletronicoMYSQL($ti,$Opcb,$Opcem,$OpcDG,$b,$opcr=null,$ch=null,$start_from=null, $record_per_page=null)
	{
		
	}
	//listar facturacion $ch para opcion de checkbox $filtro para filtros sql
	//$ord para ordenar $like para filtrar por campos
	function ListarFacturacionSQL($ti,$Opcb,$Opcem,$OpcDG,$b,$opcr=null,$ch=null,$start_from=null, 
	$record_per_page=null,$filtro=null,$ord=null,$like=null)
	{
		/*select m.rownum 
		  from(SELECT ROW_NUMBER() OVER(ORDER BY ID) AS rownum,*
		  FROM Clientes) m 
		  WHERE m.rownum BETWEEN 11 AND 20*/
		//opciones para generar consultas
		//echo $filtro.' ggg ';
		/*
			 Item = '".$_SESSION['INGRESO']['item']."' 
				  AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
				  $_SESSION['INGRESO']['CodigoU']
		*/
		/*if(substr($_SESSION['INGRESO']['CodigoU'], 5)<>'999')
		{
			//$subcta="(SubCta = '".substr($_SESSION['INGRESO']['CodigoU'], 5)."')";
			$subcta='1=1';
		}
		else
		{
			$subcta='1=1';
		}*/
		if($filtro==null or $filtro=='')
		{
			$filtro=' 1=1 ';
		}
		//$filtro = str_replace("BETWEEN", " BETWEEN", $filtro);
		$sql="select T, Serie, Autorizacion, Factura, Fecha_Emitida, SubTotal, Con_IVA, Sin_IVA, IVA, 
				Descuento, Descuento2, Total_MN, RUC_CI, Razon_Social, Email, Direccion, Telefono, m.numero from 
				(SELECT  Facturas.T, 
				 Serie, Autorizacion, Factura, Facturas.Fecha as Fecha_Emitida, SubTotal, Con_IVA, Sin_IVA, IVA, Facturas.Descuento, Descuento2, Total_MN, RUC_CI, Razon_Social,
				 Clientes.Email,clientes.Direccion,Clientes.Telefono,ROW_NUMBER() OVER(ORDER BY Facturas.Fecha DESC ) AS numero FROM Facturas 
				 inner join clientes on clientes.codigo=Facturas.codigoc
				WHERE ".$filtro." ) m ";
		if($Opcb==null) //
		{
			if($record_per_page!=null)
			{
				$sql=$sql. "  
				WHERE  numero BETWEEN ".$start_from." AND ".$record_per_page." 
				";
			}
		}
		else
		{
			if($record_per_page!=null)
			{
				$sql=$sql. "  
				WHERE  numero BETWEEN ".$start_from." AND ".$record_per_page." 
				";
			}
			//echo $sql;
		}
		//echo $sql;
		//echo $ord;
		$stmt = sqlsrv_query( $this->dbs, $sql);
		//echo "";
		//si ahy like buscar
		if($like!=null)
		{
			$like_ = explode(":", $like);
			$cant=0;
			$like1=null;
			$like=null;
			foreach( sqlsrv_field_metadata( $stmt ) as $fieldMetadata ) 
			{
			    //echo $fieldMetadata['Name'].' '.$fieldMetadata['Type'].' ';
				foreach( $fieldMetadata as $name => $value) 
				{				
					if(!is_numeric($value))
					{
						if($value!='')
						{
							//echo "".$value."";
							$value1=$value;
							if($value=='Fecha')
							{
								$value="Facturas.Fecha";
								$value1="Fecha";
							}
							if($value=='Descuento')
							{
								$value="Facturas.Descuento";
								$value1="Descuento";
							}
							if($value=='T')
							{
								$value="Facturas.T";
								$value1="T";
							}
							if($like_[0]==$cant)
							{
								if(count($like_)!=1)
								{
									$like=' '.$value.' LIKE "%'.$like_[1].'%"  ';
									$like1=' '.$value1.' LIKE "%'.$like_[1].'%"  ';
									$filtro= $filtro." AND ".$value." LIKE '%".$like_[1]."%'  ";
								}
							}
							$cant++;
						}
					}
				   //echo "<br />";
				}
			}
			//echo $sql;
		}
		//echo $sql;
		$stmt = sqlsrv_query( $this->dbs, $sql);
		if($ord!=null and $ord!='17_A' and $ord!='17_D' and $ord!='17')
		{
			$ord_ = explode("_", $ord);
			//echo substr($_SESSION['INGRESO']['CodigoU'], 5); 
			$_SESSION['INGRESO']['sql']=$sql;
			
			$cant=0;
			$orde='';
			$value1='';
			$orde1='';
			foreach( sqlsrv_field_metadata( $stmt ) as $fieldMetadata ) 
			{
				/*foreach( $fieldMetadata as $name => $value) 
				{
				   echo "$name: $value<br />";
				}
				
				echo "<br />";*/
				foreach( $fieldMetadata as $name => $value) 
				{
									
					if(!is_numeric($value))
					{
						if($value!='')
						{
							//echo "".$value."";
							$value1=$value;
							if($value=='Fecha')
							{
								$value="Facturas.Fecha";
								$value1="Fecha";
							}
							if($value=='Descuento')
							{
								$value="Facturas.Descuento";
								$value1="Descuento";
							}
							if($value=='T')
							{
								$value="Facturas.T";
								$value1="T";
							}
							if($ord_[0]==$cant)
							{
								if(count($ord_)!=1)
								{
									if($ord_[1]=='A')
									{
										$orde=$value.' ASC ';
										$orde1=$value1.' ASC ';
									}
									if($ord_[1]=='D')
									{
										$orde=$value.' DESC ';
										$orde1=$value1.' DESC ';
									}
								}
							}
							$cant++;
						}
					}
				   //echo "<br />";
				}
			}
			//echo ' tttb '.count($ord_);
			//echo $orde;
			if($Opcb==null)
			{
				$sql=$sql. "  
				 ORDER BY ".$orde1;
			}
			else
			{
				$sql=$sql. "  
				 ORDER BY ".$orde1;
				//echo $sql;
			}
			//echo $sql;
			$stmt = sqlsrv_query( $this->dbs, $sql);
		}
		//echo $sql;
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$camne=array();
		//para saber si es excel o grilla
		if($opcr==null or $opcr==1)
		{
			//grilla_generica($stmt,$ti,NULL,$b,$ch);
			//grilla_generica($stmt,$ti,NULL,'1',$ch);
			grilla_generica($stmt,$ti,NULL,'1',$ch,null,null,'1');
		}
		if($opcr==2)
		{
			exportar_excel_generico($stmt,$ti,NULL,$b);
		}
	}
	//listar documento electronico
	function ListarFacturacionMYSQL($ti,$Opcb,$Opcem,$OpcDG,$b,$opcr=null,$ch=null,$start_from=null, $record_per_page=null,$filtro=null,$ord=null,$like=null)
	{
		
	}
	function ExportarExcelUsuario($ti,$Opcb,$Opcem,$OpcDG,$b,$opcr=null,$OpcCE,$base=null,$generico,$proceso,$arr)
	{
		//echo $sql;
		if($base=='MYSQL')
		{
			$this->db=Conectar::conexion('MYSQL');
			$sql = "select IP_Acceso,CodigoU,
				(select Nombre_usuario from acceso_usuarios 
				where acceso_pcs.CodigoU=acceso_usuarios.CI_NIC 
				limit 1 ) as usuario,Item,RUC,Fecha,Hora,Aplicacion,Tarea,Proceso,Credito_No,ES,Periodo 
				from acceso_pcs where 1=1 ";
			//$consulta=$this->db->query($sql);
			// $cant = mysqli_num_fields($consulta);
			$ci_nic='';
			$filtro='';
			$item='';
			if($arr['ch2']==1)
			{
				$sql1 = "SELECT *
					  FROM acceso_usuarios
					  WHERE ID = '".$arr['value3']."' ";
					  $consulta=$this->db->query($sql1) or die($this->db->error);
				while($filas=$consulta->fetch_array())
				{
					$ci_nic=$filas['CI_NIC'];
				}
				$filtro=$filtro." AND CodigoU = '".$ci_nic."' ";
			}
			if($arr['ch3']==1)
			{
				$sql1 = "SELECT *
					  FROM lista_empresas
					  WHERE Item = '".$arr['value7']."' AND ID_Empresa = '".$arr['value1']."'";
					  $consulta=$this->db->query($sql1) or die($this->db->error);
				while($filas=$consulta->fetch_array())
				{
					$ruc=$filas['RUC_CI_NIC'];
				}
				$filtro=$filtro." AND Item = '".$arr['value7']."' AND RUC='".$ruc."'";
			}
			else
			{
				if($arr['ch1']==1 and $arr['ch3']==0)
				{
					$sql1 = "SELECT *
					FROM lista_empresas
					WHERE ID_Empresa = '".$arr['value1']."' ORDER BY Empresa;";
					  $consulta=$this->db->query($sql1) or die($this->db->error);
					  $item='';
					  $i=0;
					  $filtro=$filtro.' AND ( ';
					while($filas=$consulta->fetch_array())
					{
						$item=$item." (Item in 
						(select Item from lista_empresas 
						where lista_empresas.Item='".$filas['Item']."' 
						and lista_empresas.RUC_CI_NIC='".$filas['RUC_CI_NIC']."' AND ID_Empresa='".$arr['value1']."')
						AND 
						RUC in 
						(select RUC_CI_NIC from lista_empresas 
						where lista_empresas.Item='".$filas['Item']."' 
						and lista_empresas.RUC_CI_NIC='".$filas['RUC_CI_NIC']."' AND ID_Empresa='".$arr['value1']."')
						) OR";
						//$item=$item."'".$filas['Item']."',";
					}
					
					//echo $item.'<br>';
					$longitud_cad = strlen($item); 
					$item = substr_replace($item,"",$longitud_cad-1,1);
					$longitud_cad = strlen($item); 
					$item = substr_replace($item,"",$longitud_cad-1,2); 				
					$filtro=$filtro.$item;
					$filtro=$filtro.' )';
					
					//echo $item.'<br>';
				}
			}
			/*$date =$_POST['value5'];
			$now = new DateTime($date);
			$hoy=$now->format('Ymd'); 
			
			$date1 =$_POST['value5'];
			$now1 = new DateTime($date1);
			$hoy1=$now1->format('Ymd');*/
			$fi=date("Ymd", strtotime($arr['value5']));
			$ff=date("Ymd", strtotime($arr['value6']));
			//echo $fi.' '.$ff;
			//$filtro=$filtro." AND (Fecha >= '".date("Y-m-d")."' AND Fecha<='".date("Y-m-d")."') ";
			//$filtro=$filtro." AND (Fecha >= '".date("Ymd", strtotime($_POST['value5']))."' AND fecha<='".date("Ymd", strtotime($_POST['value6']))."') ";
			//$filtro=$filtro." AND (Fecha >= '".$hoy."' AND fecha<='".$hoy1."') ";
			$filtro=$filtro." AND Fecha BETWEEN '$fi' AND '$ff' ";
			//$filtro=$filtro." AND (Fecha >= STR_TO_DATE(\"2019-10-23\", \"%Y-%m-%d\") AND Fecha<=STR_TO_DATE(\"2019-10-23\", \"%Y-%m-%d\")) ";
			
			$sql=$sql.$filtro.'  ORDER BY CodigoU; ';
			//echo $sql.'<br>';
			/*echo $_POST['value1'].' '.$_POST['value3'].' '.$_POST['value5'].' '.$_POST['value6']
			.' '.$_POST['value7'].' '.$_POST['ch1'].' '.$_POST['ch2'].' '.$_POST['ch3'];*/
			
			//paginador('acceso_pcs',$filtro,'empresa.php?mod=empresa&acc=cambiou&acc1=Administrar%20Usuario&cod='.$ci_nic.'&item='.$item.'');		
			$consulta=$this->db->query($sql) or die($this->db->error);
			//$row_cnt = $consulta->num_rows;
			//echo $row_cnt.' cccc ';
			$camne=array();
			/*while($filas=$consulta->fetch_array())
			{
				echo $filas['CodigoU'].'<br>';
			}
			while ($row = $consulta->fetch_row())
			{
				echo $row[0].'<br>';
			}*/		
			//grilla_generica_mysql($consulta,null,NULL,'1',null,null);
			//grilla_generica($consulta,null,NULL,'1',null,null,'MYSQL');
			exportar_excel_generico($consulta,$ti,NULL,$b,$base);
		}
	}
	//imprimir documentos xml,txt,csv etc ejemplo $formato=xml $tabla=Trans_Documentos $campo=Documento_Autorizado $campob=Clave_Acceso
	function ImprimirDocEletronicoSQL($id,$formato=null,$tabla=null,$campo=null,$campob=null,$imp=null)
	{
		if($id=='macom')
		{
			$sql=" SELECT Texto FROM Tabla_Temporal 
			WHERE Item = '".$_SESSION['INGRESO']['item']."' 
			 AND Modulo = '".$_SESSION['INGRESO']['modulo_']."' 
			AND CodigoU = '".$_SESSION['INGRESO']['Id']."'
			ORDER BY ID";
			
			//echo $sql;
			$stmt = sqlsrv_query( $this->dbs, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			//echo ' mm '.$imp;
			ImprimirDocError($stmt,$id,$formato,null,$imp);
		}
		else
		{
			$sql="SELECT ".$campo."
					FROM  ".$tabla." WHERE
					Item = '".$_SESSION['INGRESO']['item']."'  
					AND ".$campob."='".$id."' ";
			$sql=$sql."  ";
				//echo $sql;
			$stmt = sqlsrv_query( $this->dbs, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			//echo ' mm '.$imp;
			ImprimirDoc($stmt,$id,$formato,null,$imp);
		}
		
		
	}
	//imprimir documentos xml,txt,csv etc
	function ImprimirDocEletronicoMYSQL($id,$formato=null,$tabla=null,$campo=null,$campob=null,$imp=null)
	{
		
	}
	//contar registros caso paginador por ejemplo (sql server) 
	function cantidaREGSQL($tabla,$filtro=null)
	{
		//echo $filtro.' gg ';
		if($filtro!=null AND $filtro!='')
		{
			$sql = "SELECT count(*) as regis FROM ".$tabla." WHERE ".$filtro." ";
		}
		else
		{
			$sql = "SELECT count(*) as regis FROM ".$tabla;
		}
		//echo $sql;
		$stmt = sqlsrv_query( $this->dbs, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$row_count=0;
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$row_count = $row[0];
			//echo $row[0];
		}
		//numero de columnas
		//$row_count = sqlsrv_num_rows( $stmt );
		return $row_count;
	}
	//contar registros caso paginador por ejemplo (mysql) 
	function cantidaREGMYSQL($tabla,$filtro=null)
	{
		if($filtro!=null and $filtro!='')
		{
			$sql = "SELECT * FROM ".$tabla." WHERE ".$filtro." ";
		}
		else
		{
			$sql = "SELECT * FROM ".$tabla;
		}
		
		$consulta=$this->db->query($sql);
		$total_records = mysqli_num_rows($consulta);
		return $total_records ;
	}
	//buscar correo por doc. elec sql server
	function buscarCorreoDocSQL($id)
	{
		//echo $filtro.' gg ';
		$sql="select * from Trans_Documentos where Trans_Documentos.clave_acceso='".$id."'";
		
		//echo $sql;
		$stmt = sqlsrv_query( $this->dbs, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$serie='';
		$num='';
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$serie = $row[5];
			$num = $row[6];
			$td = $row[4];
			//echo $row[0];
		}
		if($td=='FA' OR $td=='NV' OR $td=='GR')
		{
			$sql="select Clientes.Email,Clientes.Email2 
			from clientes
			where clientes.Codigo in 
			(select CodigoC from Facturas 
			where Facturas.serie='".$serie."' 
			and Facturas.Factura='".$num."' 
			and  Facturas.Item='".$_SESSION['INGRESO']['item']."' )";
			
		}
		if($td=='NC' OR $td=='ND' )
		{
			$sql="select Clientes.Email,Clientes.Email2 
			from clientes
			where clientes.Codigo in 
			(select CodigoC from Trans_Abonos 
			where Trans_Abonos.serie='".$serie."' 
			and Trans_Abonos.Factura='".$num."' 
			and  Trans_Abonos.Item='".$_SESSION['INGRESO']['item']."' )";
		}
		if($td=='RE' )
		{
			$sql="select Clientes.Email,Clientes.Email2 
			from clientes
			where clientes.Codigo in 
			(select IdProv from Trans_Compras 
			where Trans_Compras.Serie_Retencion='".$serie."' 
			and Trans_Compras.SecRetencion='".$num."' 
			and  Trans_Compras.Item='".$_SESSION['INGRESO']['item']."' )";
		}
		//echo $sql;
		//die();
		$stmt = sqlsrv_query( $this->dbs, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$correo='';
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$correo = $row[0];
			$correo = $correo.';'.$row[1];
			//echo $row[0];
		}
		//echo $correo;
		//die();
		//numero de columnas
		//$row_count = sqlsrv_num_rows( $stmt );
		return $correo;
	}
	//buscar correo por doc. elec mysql
	function buscarCorreoDocMYSQL($tabla,$filtro=null)
	{
		if($filtro!=null and $filtro!='')
		{
			$sql = "SELECT * FROM ".$tabla." WHERE ".$filtro." ";
		}
		else
		{
			$sql = "SELECT * FROM ".$tabla;
		}
		
		$consulta=$this->db->query($sql);
		$total_records = mysqli_num_rows($consulta);
		return $total_records ;
	}
	//Mayorizar sql server
	function sp_Mayorizar_CuentasSQL($opc,$sucursal,$item,$periodo,$codigo=null)
	{
		//echo $filtro.' gg ';
		if($codigo==null)
		{
			$tsql_callSP = "{call sp_Mayorizar_Cuentas(?,?,?,?)}";
			$params = array(
			array($opc, SQLSRV_PARAM_IN),
			array($sucursal, SQLSRV_PARAM_IN),
			array($item, SQLSRV_PARAM_IN),
			array($periodo, SQLSRV_PARAM_IN));
			
			$stmt3 = sqlsrv_query( $this->dbs, $tsql_callSP, $params);
			if( $stmt3 === false )
			{
				echo "Error en consulta de Mayorizacion.\n";
				die( print_r( sqlsrv_errors(), true));
			}
			else
			{
				$sal='';
				$tsql_callSP = "{call sp_Presenta_Errores_Contabilidad(?,?,?,?,?)}";
				$params = array(
				array($item, SQLSRV_PARAM_IN),
				array($periodo, SQLSRV_PARAM_IN),
				array($_SESSION['INGRESO']['Id'], SQLSRV_PARAM_IN),
				array($_SESSION['INGRESO']['modulo_'], SQLSRV_PARAM_IN),
				array(&$sal, SQLSRV_PARAM_INOUT));
				$stmt3 = sqlsrv_query( $this->dbs, $tsql_callSP, $params);
				if( $stmt3 === false )
				{
					echo "Error en consulta de Errores.\n";
					die( print_r( sqlsrv_errors(), true));
				}
				else
				{
					//echo "Procesado";
				}
			}
		}
		else
		{
			//ejecutamos ciclo
			
			$sql="select Codigo from catalogo_cuentas 
				where DG='D'  and item='".$_SESSION['INGRESO']['item']."' 
				and periodo='".$_SESSION['INGRESO']['periodo']."'
				order by codigo ";
			
			//echo $sql;
			//die();
			$stmt = sqlsrv_query( $this->dbs, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
			{
				$cod = $row[0];
				$tsql_callSP = "{call sp_Mayorizar_Cuentas(?,?,?,?,?)}";
				$params = array(
				array($opc, SQLSRV_PARAM_IN),
				array($sucursal, SQLSRV_PARAM_IN),
				array($item, SQLSRV_PARAM_IN),
				array($periodo, SQLSRV_PARAM_IN),
				array($codigo, SQLSRV_PARAM_IN));
				
				$stmt3 = sqlsrv_query( $this->dbs, $tsql_callSP, $params);
				if( $stmt3 === false )
				{
					echo "Error en consulta de Mayorizacion.\n";
					die( print_r( sqlsrv_errors(), true));
				}
				else
				{
					$tsql_callSP = "{call sp_Presenta_Errores_Contabilidad(?,?,?,?,?)}";
					$sal='';
					$params = array(
					array($item, SQLSRV_PARAM_IN),
					array($periodo, SQLSRV_PARAM_IN),
					array($_SESSION['INGRESO']['Id'], SQLSRV_PARAM_IN),
					array($_SESSION['INGRESO']['modulo_'], SQLSRV_PARAM_IN),
					array(&$sal, SQLSRV_PARAM_INOUT));
					$stmt3 = sqlsrv_query( $this->dbs, $tsql_callSP, $params);
					if( $stmt3 === false )
					{
						echo "Error en consulta de Errores.\n";
						die( print_r( sqlsrv_errors(), true));
					}
					else
					{
						//echo "Procesado";
					}
				}
				//echo $row[0];
			}
			
		}
	}
	//Mayorizar mysql
	function sp_Mayorizar_CuentasMYSQL($opc,$sucursal,$item,$periodo,$codigo)
	{
		
	}
	//procesar balance sql server
	
	function sp_Procesar_BalanceSQL($opc,$sucursal,$item,$periodo,$fechai,$fechaf,$bm,$cc)
	{
		//echo $filtro.' gg ';
		/*
			$server = "...the server address..."; 
			
			$options = array("UID"=>"...the username...","PWD"=>"...the password...", "Database" => "...the database..." ); 
			$conn = sqlsrv_connect($server, $options); 
			if ($conn === false) {die("".print_r(sqlsrv_errors(), true));}
			$tsql_callSP = "{call ...the stored proc...( ?, ?)}";
			$params = array( array("...first value in...", SQLSRV_PARAM_IN),
			array("...second value in...", SQLSRV_PARAM_IN) );
			$stmt3 = sqlsrv_query( $conn, $tsql_callSP, $params); 
			if( $stmt3 === false ) 
			{ echo "Error in executing statement 3.\n"; 
				die( print_r( sqlsrv_errors(), true));
			} 
			print_r( $stmt3); 
			//attempting to print the return but all i get is Resource id #3 
			echo "test echo"; 
			sqlsrv_free_stmt( $stmt3); 
			sqlsrv_close( $conn);
		*/
		//echo $opc.' '.$sucursal.' '.$item.' '.$periodo.' '.$fechai.' '.$fechaf.' '.$bm;
		//die();
		//EXEC dbo.sp_Procesar_Balance  '003','.','20190101','20191231',0,0,0
		//echo $fechai.' '.$fechaf;
		$tsql_callSP = "{call sp_Procesar_Balance(?,?,?,?,?,?,?,?)}";
		/*$params = array(
        array($opc, SQLSRV_PARAM_IN),
        array($sucursal, SQLSRV_PARAM_IN),
        array($item, SQLSRV_PARAM_IN),
        array($periodo, SQLSRV_PARAM_IN),
		array($fechai, SQLSRV_PARAM_IN),
        array($fechaf, SQLSRV_PARAM_IN),
        array($bm, SQLSRV_PARAM_IN));*/
		/*
		@Item AS VARCHAR(3), @Periodo AS VARCHAR(10), @FechaDesde AS VARCHAR(10), @FechaHasta AS VARCHAR(10), 
		@EsCoop AS BIT, @ConSucursal AS BIT, @EsBalanceMes AS BIT, @CentroCostos AS VARCHAR(5) AS
	*/
		$params = array(
        array($item, SQLSRV_PARAM_IN),
        array($periodo, SQLSRV_PARAM_IN),
        array($fechai, SQLSRV_PARAM_IN),
        array($fechaf, SQLSRV_PARAM_IN),
		array($opc, SQLSRV_PARAM_IN),
        array($sucursal, SQLSRV_PARAM_IN),
        array($bm, SQLSRV_PARAM_IN),
		array($cc, SQLSRV_PARAM_IN));
		$procedure_params = array(
		array(&$opc, SQLSRV_PARAM_IN),
		array(&$sucursal, SQLSRV_PARAM_IN),
		array(&$item, SQLSRV_PARAM_IN),
		array(&$periodo, SQLSRV_PARAM_IN),
		array(&$fechai, SQLSRV_PARAM_IN),
		array(&$fechaf, SQLSRV_PARAM_IN),
		array(&$bm, SQLSRV_PARAM_IN),
		array(&$cc, SQLSRV_PARAM_IN)
		);
		/*
			insert into Tabla_temp_spk (spk, cantidad,	camp1 ,	camp2 ,	camp3 ,	camp4 ,	camp5 ,	camp6 ,	camp7 ,
			camp8 ,	camp9 ,	camp10)
			values('sp_Procesar_Balance','7','0','0','002','.','20190101','20190228','0','','','');
		*/
		
		/*$sucursal=0;
		$item='002';
		$periodo='.';
		$fechai='20190101';
		$fechaf='20190130';
		$bm=0;
		$sql = "EXEC DiskCover_Prismanet.dbo.sp_Procesar_Balance 0, {$sucursal}, '{$item}', '{$periodo}', '{$fechai}', '{$fechaf}', {$bm};";
		$stmt = sqlsrv_query($this->dbs, $sql);*/
		
		/*sleep(3);
		$sql = $sql = "USE DiskCover_Prismanet;
		EXEC dbo.sp_Procesar_Balance {$opc}, {$sucursal}, '{$item}', '{$periodo}', '{$fechai}', '{$fechaf}', {$bm};";
		$stmt = sqlsrv_query($this->dbs, $sql);
		
		$sql = $sql = "USE DiskCover_Prismanet;
		EXEC dbo.sp_Procesar_Balance {$opc}, {$sucursal}, '{$item}', '{$periodo}', '{$fechai}', '{$fechaf}', {$bm};";
		$stmt = sqlsrv_query($this->dbs, $sql);
		if (!$stmt) {
			echo 'Your code is fail.';
		}
		else {
			echo 'Success!';
		}*/
		/*$sql = "EXEC sp_Procesar_Balance  ?, ?, ?, ?, ?, ?, ?";
		$stmt = sqlsrv_prepare($this->dbs, $sql, array(&$opc, &$sucursal, &$item, &$periodo, &$fechai, &$fechaf, &$bm));
		//foreach($someArray as $key => $var3) {
			if(sqlsrv_execute($stmt) === false) {
				echo 'mucho fail.';
			}
		//}*/
		// EXEC the procedure, {call stp_Create_Item (@Item_ID = ?, @Item_Name = ?)} seems to fail with various errors in my experiments
		//$sql = "EXEC sp_Procesar_Balance @EsCoop = ?, @ConSucursal = ?,@Item = ?, @Periodo = ?,@FechaDesde = ?, @FechaHasta = ?,@EsBalanceMes = ?";
		
	   // $sql = "EXEC sp_Procesar_Balance ".$opc.", ".$sucursal.",'".$item."', '".$periodo."','".$fechai."', '".$fechaf."',".$bm."";
		//echo $sql;
		/*if ($stmt = sqlsrv_prepare($this->dbs, $sql, $procedure_params)) {
			echo "Statement prepared.<br><br>\n";  

		} else {  
			echo "Statement could not be prepared.\n";  
			die(print_r(sqlsrv_errors(), true));  
		} */
		if ($stmt = sqlsrv_prepare($this->dbs, $tsql_callSP, $params)) {
			//echo "Statement prepared.<br><br>\n";  

		} else {  
			echo "Statement could not be prepared.\n";  
			die(print_r(sqlsrv_errors(), true));  
		} 

		if( sqlsrv_execute( $stmt ) === false ) {

			die( print_r( sqlsrv_errors(), true));

		}else{

			//print_r(sqlsrv_fetch_array($stmt));
			//print_r("ejecuto");
		}
		/*$stmt3 = sqlsrv_query( $this->dbs, $sql);
		if( $stmt3 === false )
		{
			echo "Error en consulta de procesar balance.\n";
			die( print_r( sqlsrv_errors(), true));
		}*/
		
		 /* Display any warnings. */  
		// DisplayWarnings();  
		/*$stmt3 = sqlsrv_query( $this->dbs, $tsql_callSP, $params);
		if( $stmt3 === false )
		{
			echo "Error en consulta de procesar balance.\n";
			die( print_r( sqlsrv_errors(), true));
		}*/
		//echo $stmt;
		
	}
	//procesar balance mysql
	function sp_Procesar_BalanceMYSQL($opc,$sucursal,$item,$periodo,$fechai,$fechaf,$bm,$cc)
	{
		
	}
	//errores SQL SERVER
	function sp_erroresSQL($item,$modulo,$id)
	{
		$sql=" SELECT Texto FROM Tabla_Temporal 
		WHERE Item = '".$item."' 
         AND Modulo = '".$modulo."' 
        AND CodigoU = '".$id."'
        ORDER BY ID";
	
		$stmt = sqlsrv_query( $this->dbs, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$texto=array();
		$i=0;
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$texto[$i] = $row[0];
			$i++;
		}
		//echo $correo;
		//die();
		//numero de columnas
		//$row_count = sqlsrv_num_rows( $stmt );
		return $texto;
	}
	//listar asientos temporales sql
	//$Opcb = tipo de asiento si es asiento o asiento_b por ejemplo
	function ListarAsientoTemSQL($ti,$Opcb,$b,$ch)
	{
		//opciones para generar consultas (asientos bancos)
		if($Opcb=='1')
		{
			$sql="SELECT CTA_BANCO, BANCO, CHEQ_DEP, EFECTIVIZAR, VALOR, ME, T_No, Item, CodigoU
			FROM Asiento_B
			WHERE 
			Item = '".$_SESSION['INGRESO']['item']."' 
			AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
			$ta='asi_b';
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
			$ta='asi';
		}
		//echo $sql;
		$stmt = sqlsrv_query( $this->dbs, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$camne=array();
		grilla_generica($stmt,$ti,NULL,$b,$ch,$ta);
	}
	//listar asientos temporales sql
	function ListarAsientoTemMYSQL($ti,$Opcb,$b,$ch)
	{
		
	}
	//listar asiento sub cuenta
	function ListarAsientoScSQL($ti,$Opcb,$b,$ch)
	{
		//opciones para generar consultas (asientos bancos)
		$sql="SELECT Codigo, Beneficiario, Factura, Prima, DH, Valor, Valor_ME, Detalle_SubCta,T_No, SC_No,Item, CodigoU
			FROM Asiento_SC
			WHERE 
				Item = '".$_SESSION['INGRESO']['item']."' 
				AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
			$stmt = sqlsrv_query(  $this->dbs, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			else
			{
				$camne=array();
				grilla_generica($stmt,null,NULL,'1','8,9,10,11,clave1','asi_sc');
			}
	}
	//listar asientos temporales sql
	function ListarAsientoScSQLMYSQL($ti,$Opcb,$b,$ch)
	{
		
	}
	//listar totales temporales sql
	//$Opcb = tipo de asiento si es asiento o asiento_b por ejemplo
	function ListarTotalesTemSQL($ti,$Opcb,$b,$ch)
	{
		//opciones para generar consultas (asientos bancos)
		if($Opcb=='1')
		{
			/*$sql="SELECT CTA_BANCO, BANCO, CHEQ_DEP, EFECTIVIZAR, VALOR, ME, T_No, Item, CodigoU
			FROM Asiento_B
			WHERE 
			Item = '".$_SESSION['INGRESO']['item']."' 
			AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
			$ta='asi_b';*/
		}
		else
		{
			$sql="SELECT (SUM(DEBE)-SUM(HABER)) AS DIFERENCIA, SUM(DEBE) AS DEBE ,SUM(HABER) AS HABER 
				FROM Asiento
				WHERE 
					T_No=".$_SESSION['INGRESO']['modulo_']." AND
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
			$ta='asi';
			//echo $sql;
		}
		$stmt = sqlsrv_query( $this->dbs, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		else
		{
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
			{
				?>
				<div class="row ">			
					<div class="col-md-4 col-sm-4 col-xs-4">
						
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2">
						<div class="input-group">
						
							<div class="input-group-btn">
								<button type="button" class="btn btn-default btn-xs btn_f" id='b_dif' tabindex="-1"><b>Diferencia:</b></button>
							
							</div>
							
							<input type="text" class="xs" id="diferencia" name='diferencia' 
							placeholder="0.00" value='<?php echo number_format($row[0],2, '.', ','); ?>' style='width:100%;text-align:right; '>
							
						</div>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2">
						<div class="input-group">
							<div class="input-group-btn">
								<button type="button" class="btn btn-default btn-xs btn_f" tabindex="-1"><b>Totales</b></button>
							
							</div>
							<input type="text" class="xs" id="totald" name='totald' 
							placeholder="0.00" value='<?php echo number_format($row[1],2, '.', ','); ?>' maxlength='20' size='21' style='text-align:right;'>
							
						</div>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-2">
						<div class="input-group">
							<input type="text" class="xs" id="totalh" name='totalh' placeholder="0.00" 
							value='<?php echo number_format($row[2],2, '.', ','); ?>' maxlength='20' size='21' style='text-align:right;'>
						</div>
					</div>
					
				</div>
				<?php
			}
		}
	}
	//Pago. Fact No. 001-011-012277827
	//listar totales temporales sql
	function ListarTotalesTemSQLMYSQL($ti,$Opcb,$b,$ch)
	{
		
	}
	//errores mysql
	function sp_erroresMYSQL($item,$modulo,$id)
	{
		
	}
	function cerrarSQLSERVER()
	{
		sqlsrv_close( $this->dbs );
	}
}

?>