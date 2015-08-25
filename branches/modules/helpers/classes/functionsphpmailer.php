
<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Funciones especiales para uso domestico
 *
 * @author Hardlick
 */
class Helper_functionsphpmailer {
     /**
     *
     * Funcion Send Email
     * Sirve para enviar correos electronicos a un destinatario
     * parametros Aceptados: fromName,toEmail,mensaje,asunto,
     * @author Hardlick
     */
         static function sendEmail($args=array())
	{
            global $smarty;
            //include_once ('applicationlibraries/phpmailer/class.phpmailer.php');
            //include("libraries/phpmailer/class.smtp.php");
            $mail = new PHPMailer();
            $mail->SMTPSecure= "ssl";
            $mail->IsSMTP();
            $mail->Host = "smtp.gmail.com"; // SMTP server
            $mail->Timeout=200;
            //$mail->SMTPDebug = 2;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "ssl";
            $mail->Port = 465;
            $mail->Username = "hcasanova@perfectumdata.com";
            $mail->Password = "04379800";
            $mail->From     = "hcasanova@perfectumdata.com";    
            $mail->FromName = $args['fromName'];
            $mail->AddAddress($args['toEmail']);
            $mail->Subject  = $args['asunto'];
            $mail->Body     = $args['mensaje'];
            $mail->AltBody = $args['mensaje'];
            $mail->WordWrap = 50;
            $mail->IsHTML(true);
          if(!$mail->Send()) {
                        return $mail->ErrorInfo;
   	 	}else {
                        return "1";
 		}
	}

     /**
     *
     * Funcion Send Email with attachment
     * Sirve para enviar correos electronicos a un destinatario con un archivo adjunto
     * parametros Aceptados: fromName,toEmail,mensaje,asunto, file
     * @author Hardlick
     */
         static function sendEmailattach($args=array())
	{
            global $smarty;
            include_once ('libraries/phpmailer/class.phpmailer.php');
            include("libraries/phpmailer/class.smtp.php");
            $mail = new PHPMailer();


            $mail->SMTPSecure= "ssl";
            $mail->IsSMTP();
            $mail->Host = "smtp.gmail.com"; // SMTP server
            $mail->Timeout=200;
            //$mail->SMTPDebug = 2;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "ssl";
            $mail->Port = 465;
            $mail->Username = "hcasanova@perfectumdata.com";
            $mail->Password = "04379800";
            $mail->From     = "hcasanova@perfectumdata.com";            
            $mail->FromName = $args['fromName'];
            $mail->AddAddress($args['toEmail']);
            $mail->Subject  = $args['subject'];
            $mail->Body     = $args['message'];
            $mail->AltBody = $args['message'];
            $mail->WordWrap = 50;
            $mail->AddAttachment("".$args['file']);
            $mail->IsHTML(true);
           if(!$mail->Send()) {

                return $mail->ErrorInfo;

   	 	}

                else {

 		return "1";

 		}
	}
        static function getRealIP()
	{
          $client_ip =
         ( !empty($_SERVER['REMOTE_ADDR']) ) ?
            $_SERVER['REMOTE_ADDR']
            :
            ( ( !empty($_ENV['REMOTE_ADDR']) ) ?
               $_ENV['REMOTE_ADDR']
               :
               "unknown" );
            return $client_ip;

	}
}
?>
