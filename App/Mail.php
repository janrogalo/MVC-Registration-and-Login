<?php

namespace App;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Config;

class Mail {


    public static function send($to, $subject, $text, $html)
    {
        $mail = new PHPMailer(true);



        try {
            $mail->isSMTP();
            $mail->Host       = Config::HOST_EMAIL;
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Username   = Config::USERNAME_EMAIL;
            $mail->Password   = Config::PASSWORD_EMAIL;
            $mail->Port       = 465;


            $mail->setFrom(Config::USERNAME_EMAIL);
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $html;
            $mail->AltBody = $text;

            $mail->send();
            echo 'Message has been sent';
        }

        catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}