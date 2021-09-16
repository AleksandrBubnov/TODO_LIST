<?php

namespace service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class SendEmail
{
    static  public function send($email, $id)
    {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'newtest.temperary@gmail.com';                     //SMTP username
            #region
            $mail->Password   = 'pk&j9N.a4dHw';                               //SMTP password
            #endregion
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  //tls -> ssl             //Enable implicit TLS encryption
            $mail->Port       = 587;     //465 -> 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('newtest.temperary@gmail.com', 'admin');                // Mailer -> admin
            $mail->addAddress($email, 'dear User');     //Add a recipient
            // $mail->addAddress('ellen@example.com');               //Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Registration';
            $mail->Body    = "To confirm Email, follow the link <br><a href='http://localhost/user/confirm?id=$id' target='_blank'>Link</a>";
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            // echo 'Message has been sent';
            return true;
        } catch (Exception $e) {
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            // die();
            return false;
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
