<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class EmailSender {
    private $mailer;

    public function __construct(){
        /**
         * configuraciones del servidor
         */
        require '../vendor/autoload.php';

        // true activa excepciones en caso de errores
        $this->mailer = new PHPMailer();
        $this->mailer->SMTPDebug = SMTP::DEBUG_OFF;
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.gmail.com'; //establecer servicio de correo
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'ardillogm34@gmail.com';
        $this->mailer->Password = 'pcukpcopzimbrwpi';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mailer->Port = 465;
        
    }

    public function send_email($addressee, $message) {
        try {
            // Configuración del correo
            $this->mailer->setFrom('ardillogm34@gmail.com', 'Key Chest');
            $this->mailer->isHTML(true);
            $this->mailer->addAddress($addressee);
            $this->mailer->Subject = 'Restablecer clave';
            $this->mailer->Body = $message;

            // Envío del correo
            $this->mailer->send();
            return true;
            
        } catch (Exception $e) {
            // Manejo de errores en el envío del correo
            echo 'Error al enviar el correo: ' . $this->mailer->ErrorInfo;
            return false;
        }
    }
}