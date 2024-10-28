<?php
/**
 * 
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';
if (!class_exists('db')) {
  include(dirname(__DIR__, 2) . '/php/db/db1.php');
}

class enviar_emails
{
  // private $mail;
  private $db;
  function __construct()
  {
    $this->db = new db();
  }


  // funcion de envios enviando datos por correo (funciona)
  function enviar_email($archivos = false, $to_correo = "", $cuerpo_correo = "", $titulo_correo = "", $HTML = false)
  {

    // print_r('ingresa');die();
    $empresaGeneral = $this->Empresa_data();
    $server_externo = 0;

    if ($empresaGeneral[0]["smtp_Servidor"] == "relay.dnsexit.com" ||  $empresaGeneral[0]["smtp_Servidor"] == "mail.diskcoversystem.com") 
    {

      $server_externo = 1;
      $empresaGeneral[0]['smtp_Servidor'] = "smtp.diskcoversystem.com";
      $empresaGeneral[0]['Email_Conexion'] = "admin";
      $empresaGeneral[0]['Email_Contraseña'] = "Admin@2023";
      $empresaGeneral[0]['smtp_SSL'] = 0;
      $empresaGeneral[0]['smtp_Puerto'] = 26;
    }

    $res = 1;
    // print_r($empresaGeneral);die();
    if ($empresaGeneral[0]['Email_CE_Copia'] == 1) {
      if ($empresaGeneral[0]['Email_Procesos'] != '' && $empresaGeneral[0]['Email_Procesos'] != '.') {
        $to_correo .= ',' . $empresaGeneral[0]['Email_Procesos'];
      }
    }
    // print_r($_SESSION['INGRESO']);die();
    // print_r($to_correo);die();
    $to = explode(',', $to_correo);
    foreach ($to as $key => $value) {
      if ($value != '.' && $value != '') {
        $mail = new PHPMailer(true);
        $mail->SMTPOptions = array(
          'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
          )
        );

        try {
          //Server settings
          // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                 //Enable verbose debug output
          $mail->isSMTP(); //Send using SMTP
          $mail->Host = $empresaGeneral[0]['smtp_Servidor']; //Set the SMTP server to send through
          $mail->SMTPAuth = true; //Enable SMTP authentication
          $mail->Username = $empresaGeneral[0]['Email_Conexion']; //SMTP username
          $mail->Password = $empresaGeneral[0]['Email_Contraseña'];
          if ($server_externo == 0) //SMTP password
          {
            if ($empresaGeneral[0]['smtp_SSL'] == 1) {
              $mail->SMTPSecure = 'ssl';
              $mail->Port = 465;
            } else {
              $mail->SMTPSecure = 'tls';
              $mail->Port = 587;
            }
          } else {
            if ($empresaGeneral[0]['smtp_SSL'] == 1) {
              $mail->SMTPSecure = 'ssl';
            } else {
              $mail->SMTPSecure = 'tls';
            }
            $mail->Port = $empresaGeneral[0]['smtp_Puerto'];
          }

          $from = str_replace("@diskcoversystem.com","@smtp.diskcoversystem.com", $_SESSION['INGRESO']['Email_Conexion_CE']);

          $mail->setFrom($from, 'DiskCover System');
          $mail->addAddress($value); //Add a recipient
          $mail->addReplyTo($from, 'Informacion');
          //$mail->addCC('cc@example.com');
          //$mail->addBCC('bcc@example.com');

          //Attachments
          // print_r($archivos);die();
          if ($archivos) {

            foreach ($archivos as $key => $value) {
              if (file_exists(dirname(__DIR__, 2) . '/TEMP/' . $value)) {
                $mail->AddAttachment(dirname(__DIR__, 2) . '/TEMP/' . $value);
              }
              if (file_exists(dirname(__DIR__, 2) . '/php/comprobantes/entidades/entidad_' . generaCeros($_SESSION['INGRESO']['IDEntidad'], 3) . '/CE' . generaCeros($_SESSION['INGRESO']['item'], 3) . '/Autorizados/' . $value)) {
                $mail->AddAttachment(dirname(__DIR__, 2) . '/php/comprobantes/entidades/entidad_' . generaCeros($_SESSION['INGRESO']['IDEntidad'], 3) . '/CE' . generaCeros($_SESSION['INGRESO']['item'], 3) . '/Autorizados/' . $value);
              }

              // if(file_exists(dirname(__DIR__,2).'/php/comprobantes/entidades/entidad_'.generaCeros($_SESSION['INGRESO']['IDEntidad'],3).'/CE'.generaCeros($_SESSION['INGRESO']['item'],3).'/Generados/'.$value))
              // {                   
              //   $mail->AddAttachment(dirname(__DIR__,2).'/php/comprobantes/entidades/entidad_'.generaCeros($_SESSION['INGRESO']['IDEntidad'],3).'/CE'.generaCeros($_SESSION['INGRESO']['item'],3).'/Generados/'.$value);                       
              // }

              if (file_exists(dirname(__DIR__, 2) . '/php/comprobantes/entidades/entidad_' . generaCeros($_SESSION['INGRESO']['IDEntidad'], 3) . '/CE' . generaCeros($_SESSION['INGRESO']['item'], 3) . '/Autorizados/' . $value)) {

                $mail->AddAttachment(dirname(__DIR__, 2) . '/php/comprobantes/entidades/entidad_' . generaCeros($_SESSION['INGRESO']['IDEntidad'], 3) . '/CE' . generaCeros($_SESSION['INGRESO']['item'], 3) . '/Autorizados/' . $value);
              }


            }
          }

          //Content
          if ($HTML) {
            $mail->isHTML(true);
          } //Set email format to HTML
          $mail->Subject = $titulo_correo;
          $mail->Body = $cuerpo_correo;
          // print_r($mail);
          // die();

          // print_r('host:'.$mail->Host.'//Username:'.$mail->Username.'//pass:'.$mail->Password.'//Puerto:'.$mail->Port.'//Secure:'.$mail->SMTPSecure);die();

          if ($mail->send()) {
            $res = 1;
          }

        } catch (Exception $e) {
          // print_r($mail);
          // print_r($e);
          // die();
          return -1;
        }

      }
    }

    return $res;
  }

    // funcion de envios enviando datos por correo (funciona)
  function enviar_email_generico($archivos = false, $to_correo = "", $cuerpo_correo = "", $titulo_correo = "", $HTML = false)
  {

    // print_r('ingresa');die();
    $empresaGeneral = $this->Empresa_data();
    $server_externo = 0;

    if ($empresaGeneral[0]["smtp_Servidor"] == "relay.dnsexit.com" ||  $empresaGeneral[0]["smtp_Servidor"] == "mail.diskcoversystem.com") 
    {
      $server_externo = 1;
      $empresaGeneral[0]['smtp_Servidor'] = "smtp.diskcoversystem.com";
      $empresaGeneral[0]['Email_Conexion'] = "admin";
      $empresaGeneral[0]['Email_Contraseña'] = "Admin@2023";
      $empresaGeneral[0]['smtp_SSL'] = 0;
      $empresaGeneral[0]['smtp_Puerto'] = 26;
    }

    $res = 1;
    // print_r($empresaGeneral);die();
    if ($empresaGeneral[0]['Email_CE_Copia'] == 1) {
      if ($empresaGeneral[0]['Email_Procesos'] != '' && $empresaGeneral[0]['Email_Procesos'] != '.') {
        $to_correo .= ',' . $empresaGeneral[0]['Email_Procesos'];
      }
    }
    // print_r($_SESSION['INGRESO']);die();
    // print_r($to_correo);die();
    $to_correo = trim($to_correo);
    $to_correo = str_replace(';',',',$to_correo);
    $to = explode(',', $to_correo);
    foreach ($to as $key => $value) {
      if ($value != '.' && $value != '') {
        $mail = new PHPMailer(true);
        $mail->SMTPOptions = array(
          'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
          )
        );

        try {
          //Server settings
          // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                 //Enable verbose debug output
          $mail->isSMTP(); //Send using SMTP
          $mail->Host = $empresaGeneral[0]['smtp_Servidor']; //Set the SMTP server to send through
          $mail->SMTPAuth = true; //Enable SMTP authentication
          $mail->Username = $empresaGeneral[0]['Email_Conexion']; //SMTP username
          $mail->Password = $empresaGeneral[0]['Email_Contraseña'];
          if ($server_externo == 0) //SMTP password
          {
            if ($empresaGeneral[0]['smtp_SSL'] == 1) {
              $mail->SMTPSecure = 'ssl';
              $mail->Port = 465;
            } else {
              $mail->SMTPSecure = 'tls';
              $mail->Port = 587;
            }
          } else {
            if ($empresaGeneral[0]['smtp_SSL'] == 1) {
              $mail->SMTPSecure = 'ssl';
            } else {
              $mail->SMTPSecure = 'tls';
            }
            $mail->Port = $empresaGeneral[0]['smtp_Puerto'];
          }
          $from = str_replace("@diskcoversystem.com","@smtp.diskcoversystem.com", $_SESSION['INGRESO']['Email_Conexion_CE']);
          $mail->setFrom($from, 'DiskCover System');
          $mail->addAddress($value); //Add a recipient
          $mail->addReplyTo($from, 'Informacion');
          //$mail->addCC('cc@example.com');
          //$mail->addBCC('bcc@example.com');

          //Attachments
          // print_r($archivos);die();
          if ($archivos) {

              foreach ($archivos as $key => $value) {
                $mail->AddAttachment($value);              
            }
          }

          //Content
          if ($HTML) {
            $mail->isHTML(true);
          } //Set email format to HTML
          $mail->Subject = $titulo_correo;
          $mail->Body = $cuerpo_correo;
          if ($mail->send()) {
            $res = 1;
          }

        } catch (Exception $e) {
          // print_r($mail);
          // print_r($e);
          // die();
          return -1;
        }

      }
    }

    return $res;
  }
  // funcion de envios enviando datos por correo (funciona)
  function enviar_credenciales($archivos = false, $to_correo = "", $cuerpo_correo = "", $titulo_correo = "", $correo_apooyo = "", $nombre = "", $EMAIL_CONEXION = "", $EMAIL_CONTRASEÑA = "", $HTML = false, $empresaGeneral = "")
  {


    // print_r($empresaGeneral);die();
    //Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);
    $mail->SMTPOptions = array(
      'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
      )
    );

    try {
      //Server settings
      // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                 //Enable verbose debug output
      $mail->isSMTP(); //Send using SMTP
      $mail->Host = $empresaGeneral[0]['smtp_Servidor']; //Set the SMTP server to send through
      $mail->SMTPAuth = true; //Enable SMTP authentication
      $mail->Username = $empresaGeneral[0]['Email_Conexion']; //SMTP username
      $mail->Password = $empresaGeneral[0]['Email_Contraseña']; //SMTP password
      if ($empresaGeneral[0]['smtp_SSL'] == 1) {
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
      } else {
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
      }

      $mail->setFrom($empresaGeneral[0]['Email_Conexion'], 'DiskCover System');
      $mail->addAddress($to_correo); //Add a recipient
      $mail->addReplyTo($empresaGeneral[0]['Email_Conexion'], 'Informacion');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
      //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

      //Content
      $mail->isHTML(true); //Set email format to HTML
      $mail->Subject = $titulo_correo;
      $mail->Body = $cuerpo_correo;
      // print_r($mail);
      // die();

      // print_r('host:'.$mail->Host.'//Username:'.$mail->Username.'//pass:'.$mail->Password.'//Puerto:'.$mail->Port.'//Secure:'.$mail->SMTPSecure);die();

      if ($mail->send()) {
        return 1;
      }

    } catch (Exception $e) {
      // print_r($mail);die();
      return -1;
    }
  }

  function enviar_historial($archivos = false, $to_correo = "", $cuerpo_correo = "", $titulo_correo = "", $nombre = "")
  {
    //Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);
    $mail->SMTPOptions = array(
      'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
      )
    );

    try {
      //Server settings
      //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                 //Enable verbose debug output
      $mail->isSMTP(); //Send using SMTP
      $mail->Host = 'mail.diskcoversystem.com'; //Set the SMTP server to send through
      $mail->SMTPAuth = true; //Enable SMTP authentication
      $mail->Username = 'info@diskcoversystem.com'; //SMTP username
      $mail->Password = 'info2021DiskCover'; //SMTP password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
      $mail->Port = 465; //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
      //$mail->SMTPSecure = 'tls';
      //$mail->SMTPSecure='STARTTLS';
      //Recipients
      $mail->setFrom('info@diskcoversystem.com', 'DiskCover System');
      $mail->addAddress('jdavalos450@gmail.com', 'Jonathan Avalos'); //Add a recipient
      $mail->addAddress('jd-avalos@hotmail.com', 'Jonathan Avalos'); //Add a recipient
      //$mail->addAddress('info@diskcoversystem.com', 'DiskCover');     //Add a recipient
      //$mail->addAddress('diskcover@msn.com', 'DiskCover MSN');     //Add a recipient
      //$mail->addAddress('ramiro_ron@hotmail.com', 'Ron Ramiro');     //Add a recipient
      //$mail->addAddress('diskcover.system@gmail.com', 'Ron Ramiro');     //Add a recipient
      $mail->addAddress($to_correo, $nombre); //Add a recipient
      $mail->addReplyTo('info@diskcoversystem.com', 'Informacion');
      //$mail->addCC('cc@example.com');
      //$mail->addBCC('bcc@example.com');

      //Attachments
      $mail->addAttachment($archivos[0]); //Add attachments
      $mail->addAttachment($archivos[1]); //Add attachments
      //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

      //Content
      $mail->isHTML(true); //Set email format to HTML
      $mail->Subject = $titulo_correo;
      $mail->Body = $cuerpo_correo;
      $mail->send();
      return true;
    } catch (Exception $e) {
      return false;
    }
  }

  function recuperar_clave($archivos = false, $to_correo = "", $cuerpo_correo = "", $titulo_correo = "", $correo_apooyo = "", $nombre = "", $EMAIL_CONEXION = "", $EMAIL_CONTRASEÑA = "")
  {
    $to = explode(',', $to_correo);
    foreach ($to as $key => $value) {
      $mail = new PHPMailer();
      $mail->SMTPOptions = array(
        'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
        )
      );

      //Server settings
      // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
      $mail->isSMTP(); //Send using SMTP
      $mail->Host = 'mail.diskcoversystem.com'; //Set the SMTP server to send through
      $mail->SMTPAuth = true; //Enable SMTP authentication
      // $mail->Username   = 'matriculas@diskcoversystem.com';                     //SMTP username
      // $mail->Password   = 'DiskCover1210';                               //SMTP password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
      $mail->SMTPSecure = 'ssl';
      $mail->Port = 465; //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
      $mail->Username = $EMAIL_CONEXION; //EMAIL_CONEXION DE TABLA EMPRESA
      $mail->Password = $EMAIL_CONTRASEÑA; //EMAIL_CONTRASEÑA DE LA TABLA EMPRESA
      $mail->setFrom($correo_apooyo, $nombre);

      $mail->addAddress($value);
      $mail->Subject = $titulo_correo;
      $mail->Body = $cuerpo_correo; // Mensaje a enviar


      if ($archivos) {
        foreach ($archivos as $key => $value) {
          if (file_exists('../../php/vista/TEMP/' . $value)) {
            //    print_r('../vista/TEMP/'.$value);

            $mail->AddAttachment('../../php/vista/TEMP/' . $value);
          }
        }
      }
      if (!$mail->send()) {
        return -1;
      } else {
        return 1;
      }
    }
  }

  function Empresa_data()
  {
    $sql = "SELECT * FROM Empresas where Item='" . $_SESSION['INGRESO']['item'] . "'";
    $datos = $this->db->datos($sql);
    return $datos;
  }

  function FEnviarCorreos($TMail, $Lista_De_Correos = null, $itemEmpresa = '')
  {
    $Si_Enviar = false;
    if ($TMail->de == CorreoDiskCover) {

      /*$TMail->Usuario = 'c16a0e373e6a95';
      $TMail->PassWord = '8945dda5de5381';
      $TMail->servidor = 'sandbox.smtp.mailtrap.io';
      $TMail->puerto = 2525;*/

      $TMail->Usuario = CorreoDiskCover;
      $TMail->PassWord = ContrasenaDiskCover;
      $TMail->servidor = 'mail.diskcoversystem.com';
      $TMail->puerto = 465;

      $TMail->UseAuntentificacion = true;
      $TMail->ssl = true;
      $Si_Enviar = true;
    } else {
      if ($itemEmpresa == '') {
        $itemEmpresa = @$_SESSION['INGRESO']['item'];
      }

      $conn = new db();
      $sSQL = "SELECT smtp_Servidor, smtp_Puerto, smtp_UseAuntentificacion, smtp_SSL, smtp_Secure, " .
        "Email_Conexion, Email_Contraseña, Email_Conexion_CE, Email_Contraseña_CE, Email_CE_Copia " .
        "FROM Empresas " .
        "WHERE Item = '" . $itemEmpresa . "' " .
        "AND LEN(smtp_Servidor) > 1 " .
        "AND smtp_Puerto > 0 ";
      $AdoSMTP = $conn->datos($sSQL);
      ;
      if (count($AdoSMTP) > 0) {
        $TMail->UseAuntentificacion = boolval($AdoSMTP[0]["smtp_UseAuntentificacion"]);
        $TMail->ssl = boolval($AdoSMTP[0]["smtp_SSL"]);
        $TMail->puerto = $AdoSMTP[0]["smtp_Puerto"];
        switch ($TMail->TipoDeEnvio) {
          case "CE":
            $TMail->de = $AdoSMTP[0]["Email_Conexion_CE"];
            $TMail->Usuario = $AdoSMTP[0]["Email_Conexion_CE"];
            $TMail->PassWord = $AdoSMTP[0]["Email_Contraseña_CE"];
            break;
          default:
            // En caso de que se envie desde otro correo por default
            if (0 <= $TMail->ListaMail && $TMail->ListaMail <= 6 && $TMail->de == "") {
              $TMail->de = $Lista_De_Correos[$TMail->ListaMail]->Correo_Electronico;
              $TMail->Usuario = $Lista_De_Correos[$TMail->ListaMail]->Correo_Electronico;
              $TMail->PassWord = $Lista_De_Correos[$TMail->ListaMail]->Contraseña;
            } else {
              $TMail->de = $AdoSMTP[0]["Email_Conexion"];
              $TMail->Usuario = $AdoSMTP[0]["Email_Conexion"];
              $TMail->PassWord = $AdoSMTP[0]["Email_Contraseña"];
            }
        }

        if (strpos($TMail->Usuario, "gmail.com") !== false) {
          $TMail->servidor = "smtp.gmail.com";
        } elseif (strpos($TMail->Usuario, "diskcoversystem.com") !== false) {
          $TMail->servidor = "mail.diskcoversystem.com";
        } else {
          $TMail->servidor = $AdoSMTP[0]["smtp_Servidor"];
        }

        $Si_Enviar = true;
      } else {
        $rps = ['error' => true, 'mensaje' => "Credenciales no asignadas para el envio de Correos electronicos, solicite ayuda al Administrador del Sistema"];
      }
    }

    if ($TMail->Subject == "") {
      $TMail->Subject = "Sin asunto";
    }
    if ($Si_Enviar) {
      $mail = new PHPMailer(true);
      $mail->SMTPOptions = array(
        'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
        )
      );

      $recipients = explode(";", $TMail->para);

      /*echo '<script>';
      echo 'console.log("De: ' . $TMail->de . '");';
      echo '</script>';

      echo '<script>';
      echo 'console.log("Para:");';
      echo '</script>';

      foreach ($recipients as $recipient) {
        echo '<script>';
        echo 'console.log("' . $recipient . '");';
        echo '</script>';
      }*/

      $recipients = explode(";", $TMail->para);
      $rps = [];
      foreach ($recipients as $recipient) {
        if (strpos($recipient, '@') !== false) {
          try {
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = $TMail->servidor;
            $mail->SMTPAuth = $TMail->UseAuntentificacion;
            $mail->Username = $TMail->Usuario;
            $mail->Password = $TMail->PassWord;
            $mail->Port = $TMail->puerto;
            /*if ($TMail->ssl)
              $mail->SMTPSecure = 'ssl';
            else
              $mail->SMTPSecure = 'tls';
            $mail->Port = $TMail->puerto;*/

            $mail->setFrom($TMail->de, 'DiskCover System');
            $mail->addAddress($recipient);
            $mail->addReplyTo($TMail->de, 'Informacion');

            if (is_array($TMail->Adjunto)) {
              foreach ($TMail->Adjunto as $archivo) { //TODO LS esto no se ha testeado
                if (file_exists(dirname(__DIR__, 2) . '/' . $archivo))
                  $mail->AddAttachment(dirname(__DIR__, 2) . '/' . $archivo);
              }
            }

            $mail->isHTML(true);
            $mail->Subject = $TMail->Subject;
            $mail->Body = $TMail->Mensaje;

            /*echo '<script>';
              echo 'console.log("HOST: ' . $mail->Host . '");';
              echo '</script>';

              echo '<script>';
              echo 'console.log("USER AUTH: ' . $mail->SMTPAuth . '");';
              echo '</script>';

              echo '<script>';
              echo 'console.log("USER: ' . $mail->Username . '");';
              echo '</script>';

              echo '<script>';
              echo 'console.log("PASS: ' . $mail->Password . '");';
              echo '</script>';

              echo '<script>';
              echo 'console.log("TITLE: ' . $mail->Subject . '");';
              echo '</script>';

              echo '<script>';
              echo 'console.log("MESSAGE: ' . $mail->Body . '");';
              echo '</script>';
            */
            $a = $mail->send();
            $rps[] = array('para' => $recipient, 'rps' => $a);

          } catch (Exception $e) {
            $rps[] = array('para' => $recipient, 'rps' => false);
          }
        }
      }
      return $rps;
    }
  }
}

?>