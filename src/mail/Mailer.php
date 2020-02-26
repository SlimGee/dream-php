<?php
namespace Dream\Mail;

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dream\Views\View;

require 'lib/phpmailer/src/Exception.php';
require 'lib/phpmailer/src/PHPMailer.php';
require 'lib/phpmailer/src/SMTP.php';
/**
 *
 */
class Mailer
{
    public static function mail($name,$to,$message,$from = "givenyslim12@gmail.com",$fname="Welodge Marketplace")
    {

        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();                                      // Send using SMTP
            $mail->Host       = $_ENV['SMTP_SERVER'];                    // Set the SMTP server
            $mail->SMTPAuth   = true;                                     // Enable SMTP authentication
            $mail->Username   = $_ENV['SMTP_USER'];                     // SMTP username
            $mail->Password   = $_ENV['SMTP_PASS'];                               // SMTP password

            PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = $_ENV['SMTP_PORT'];                                    // TCP port to connect to

            //Recipients
            $mail->setFrom($from, $fname);
            $mail->addAddress($to, $name);     // Add a recipient
            $mail->addReplyTo($from, $fname);

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Welodge Marketplace';
            $mail->Body    = $message;
            $mail->AltBody = $message;

            $mail->send();
            return true;
        } catch (Exception $e) {
            self::mail('Admin', 'givenyslim12@gmail.com', "Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }

    public static function make($view, $data = [])
    {
        register_view_data($data);
        $default_action_view = app()->registry()->get('action_view');
        app()->registry()->set('action_view', 'mail/' . $view);
        ob_start();
        $view = new View('mailer');
        $content = ob_get_contents();
        ob_end_clean();
        app()->registry()->set('action_view', $default_action_view);
        return $content;
    }
}
