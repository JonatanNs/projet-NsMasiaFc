<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;
use Twig\Extra\Intl\IntlExtension; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


abstract class AbstractController {
    private Environment $twig;
    private PHPMailer $mail;

    public function __construct() {
        $loader = new FilesystemLoader('templates');
        $twig = new Environment($loader, [
            'debug' => true,
        ]);
        
        // Configurez le fuseau horaire pour Twig
        $twig->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone('Europe/Paris');

        //$twig->addGlobal('session', $_SESSION['csrf-token']);
        $twig->addExtension(new DebugExtension());
        // Ajoutez l'extension IntlExtension à l'environnement Twig
        $twig->addExtension(new IntlExtension());

        $this->twig = $twig;

        // Instanciez PHPMailer
        $this->mail = new PHPMailer(true);
    }
    protected function render(string $template, array $data): void {
        echo $this->twig->render($template, $data);
    }
    protected function sendEmail(string $addAddress, string $name, string $subject, string $body): void {
        try {
            //Server settings
            //$this->mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $this->mail->isSMTP();                                            //Send using SMTP
            $this->mail->Host       = 'smtp.gmail.com';  //Set the SMTP server to send through
            $this->mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $this->mail->Username   = $_ENV['emailSiteWeb'];                     //SMTP username
            $this->mail->Password   = $_ENV['passwordEmail']; 
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $this->mail->CharSet       = "utf-8";                                   
            
            //Recipients
            $this->mail->setFrom($_ENV['emailSiteWeb'], 'NS MASIA FC');
            $this->mail->addAddress($addAddress, $name);     //Add a recipient
            $this->mail->addReplyTo('no-reply@gmail.com', 'Information');
            //$this->mail->addCC('cc@example.com');
            //$this->mail->addBCC('bcc@example.com');
            
            //Attachments
            $this->mail->addAttachment('assets/img/logo-ns_masia.png');         //Add attachments
            //$this->mail->addAttachment('');    //Optional name
            
            //Content
            $this->mail->isHTML(true);                                  //Set email format to HTML
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;
            $this->mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            
            $this->mail->send();

        } catch (Exception $e) {
            $_SESSION["error"] = "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }        
    
}


