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
        $twig->addExtension(new DebugExtension());
        $twig->addExtension(new IntlExtension());

        $this->twig = $twig;

        $this->mail = new PHPMailer(true);
    }
    protected function render(string $template, array $data): void {
        echo $this->twig->render($template, $data);
    }
    protected function sendEmail(string $addAddress, string $name, string $subject, string $body): void {
        try {
            $nsMasiaManager = new NsMasiaManager();
            $nsMasia = $nsMasiaManager->getNsMasia();

            $this->mail->isSMTP();                                            //Send using SMTP
            $this->mail->Host       = 'smtp.gmail.com';  //Set the SMTP server to send through
            $this->mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $this->mail->Username   = $nsMasia->getEmail();                     //SMTP username
            $this->mail->Password   = $_ENV['passwordEmail']; 
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $this->mail->CharSet       = "utf-8";                                   
            
            //Recipients
            $this->mail->setFrom($nsMasia->getEmail(), $nsMasia->getName());
            $this->mail->addAddress($addAddress, $name);    
            //$this->mail->addReplyTo('no-reply@gmail.com', 'Information');
            //$this->mail->addCC('cc@example.com');
            //$this->mail->addBCC('bcc@example.com');
            
            //Attachments
            //$this->mail->addAttachment();         //Add attachments
            
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
    
    protected function baseEmailSignup(string $addAddress, string $nameUser){
        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();
        $nsName = $nsMasia->getName();

        $subject =  "Merci pour votre inscription !";

        $emailContent = "
                <!DOCTYPE html>
                <html lang='fr'>
                <head>
                    <meta charset='UTF-8'>
                    <meta http-equiv='X-UA-Compatible'content='IE=edge'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>$subject</title>
                </head>
                <body>
                    <p>Bonjour $nameUser,</p>
                
                    <p>Merci d'avoir rejoint $nsName !</p>
                    <p>Nous sommes ravis de vous accueillir parmi nous.</p>
                    <p>
                        Votre inscription marque le début d'une belle aventure. 
                        N'hésitez pas à explorer notre site et à participer à nos activités.
                    </p>

                    <div>
                        <p>Cordialement,</p>
                        <p>L'équipe $nsName</p>
                    </div>
                </body>
                </html>

        ";
        $this->sendEmail($addAddress, $nameUser, $subject, $emailContent);
    }

    protected function baseEmailPurchases(string $addAddress, string $nameUser,Order_product $order_product){
        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();
        $nsName = $nsMasia->getName();

        $orderManager = new OrderManager();
        $order = $orderManager->getAllOrdersProductById($order_product->getId());
        $numberOrder = $order->getNumberOrder();
        $dateObj = DateTime::createFromFormat('Y-m-d', $order->getDate());
        // Formater la date en français
        $dateFormatee = $dateObj->format('d/m/Y');

        $totalPrices = $order->getTotalPrices();

        $subject =  "Merci pour votre achat !";

        $emailContent = "
                <!DOCTYPE html>
                <html lang='fr'>
                <head>
                    <meta charset='UTF-8'>
                    <meta http-equiv='X-UA-Compatible'content='IE=edge'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>$subject</title>
                </head>
                <body>
                    <p>Bonjour $nameUser,</p>
                
                    <p>
                        Nous vous remercions sincèrement d'avoir choisi $nsName pour votre récente acquisition ! 
                        C'est un honneur de vous avoir comme client(e).
                    </p>

                    <p>
                        Si vous avez des questions ou des préoccupations concernant votre achat, n'hésitez pas à nous contacter. 
                        Nous sommes là pour vous assister.
                    </p>

                    <ul>
                        <li>Votre numéro de commande : $numberOrder</li>
                        <li>Achat éffectuer le $dateFormatee </li>
                        <li>Total : $totalPrices €</li>
                    </ul>
                
                    <div>
                        <p>Cordialement,</p>
                        <p>L'équipe $nsName</p>
                    </div>
                </body>
                </html>

        ";
        $this->sendEmail($addAddress, $nameUser, $subject, $emailContent);
    }

    protected function baseEmailTicket(string $addAddress, string $nameUser,Order_ticket $order_ticket){
        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();
        $nsName = $nsMasia->getName();

        $orderManager = new OrderManager();
        $order = $orderManager->getOrderTicketById($order_ticket);

        $numberOrder = $order->getNumberOrder();

        $dateObj = DateTime::createFromFormat('Y-m-d', $order->getDate());
        $dateFormatee = $dateObj->format('d/m/Y');

        $subject =  "Merci pour votre achat !";

        $emailContent = "
                <!DOCTYPE html>
                <html lang='fr'>
                <head>
                    <meta charset='UTF-8'>
                    <meta http-equiv='X-UA-Compatible'content='IE=edge'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>$subject</title>
                </head>
                <body>
                    <p>Cher/Chère $nameUser,</p>
                
                    <p>
                        Nous sommes ravis de vous informer que votre achat de ticket pour le prochain match de $nsName a bien été enregistré. 
                        Meci pour votre soutien continu à notre équipe !
                    </p>

                    <p>
                        Votre billet électronique est désormais disponible. 
                        Vous pouvez le télécharger et l'imprimer en utilisant le lien suivant : [lien de téléchargement du billet].
                    </p>

                    <p>
                        Nous sommes impatients de vous accueillir au stade et de partager ensemble la passion du football. 
                        Pour toute question ou assistance supplémentaire, n'hésitez pas à nous contacter.
                    </p>

                    <ul>
                        <li>Votre numéro de commande : $numberOrder</li>
                        <li>Achat éffectuer le $dateFormatee </li>
                    </ul>
                
                    <div>
                        <p>Cordialement,</p>
                        <p>L'équipe $nsName</p>
                    </div>
                </body>
                </html>

        ";
        $this->sendEmail($addAddress, $nameUser, $subject, $emailContent);
    }
    
}


