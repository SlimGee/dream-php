<?php
namespace Dream\Mail;

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; PHPMailer::ENCRYPTION_SMTPS
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
            self::mail('givenyslim12@gmail.com', 'Admin', "Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
