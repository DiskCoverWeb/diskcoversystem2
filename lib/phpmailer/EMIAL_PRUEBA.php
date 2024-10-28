<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);
 $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    

// host://Username://pass://Puerto://Secure:ssl

    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    // $mail->Host       = "smtp.diskcoversystem.com";                     //Set the SMTP server to send through
    // $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    // $mail->Username   = 'admin';                     //SMTP username
    // $mail->Password   = 'Admin@2023';                               //SMTP password
    // $mail->SMTPSecure = '';//PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    // $mail->SMTPAutoTLS = false;
    // $mail->Port       = 26;                                    //TCP port to connect to, use 465 for PHPMailer::ENCRYPTION_SMTPS` above
    $mail->Helo = 'smtp.diskcoversystem.com';    
    $mail->Host = 'smtp.diskcoversystem.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'admin';
    $mail->Password = 'Admin@2023';
    $mail->SMTPSecure = false; // Dejar en blanco para 'tls'
    $mail->SMTPAutoTLS = true; // Desactivar el inicio automático de TLS
    $mail->Port = 26;

   


    //Recipients
    $mail->setFrom('electronicos@smtp.diskcoversystem.com', 'Mailer');
    $mail->addAddress('javier.farinango92@gmail.com');     //Add a recipient
    // $mail->addAddress('javier.farinango92@gmail.com');     //Add a recipient
    // $mail->addAddress('diskcoversystem@msn.com');     //Add a recipient
    // $mail->addAddress('ellen@example.com');               //Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
 