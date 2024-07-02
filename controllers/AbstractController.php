<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;
//use Twig\Extra\Intl\IntlExtension; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;


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
        //$twig->addExtension(new IntlExtension());

        $this->twig = $twig;

        $this->mail = new PHPMailer(true);
    }
    protected function render(string $template, array $data): void {
        echo $this->twig->render($template, $data);
    }
    protected function sendEmail(string $addAddress, string $name, string $subject, string $body, ?string $qrCodeImagePath = null): void {
        try {
            $nsMasiaManager = new NsMasiaManager();
            $nsMasia = $nsMasiaManager->getNsMasia();
    
            $this->mail->isSMTP();                                            // Send using SMTP
            $this->mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
            $this->mail->Port       = 465;                                    // TCP port to connect to
            $this->mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $this->mail->Username   = $nsMasia->getEmail();                   // SMTP username
            $this->mail->Password   = $nsMasia->getPasswordEmail();           // SMTP password
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // Enable implicit TLS encryption
            $this->mail->CharSet    = "utf-8";                                // Set charset to UTF-8
    
            // Recipients
            $this->mail->setFrom($nsMasia->getEmail(), $nsMasia->getName());
            $this->mail->addAddress($addAddress, $name);
    
            // Attachments
            if ($nsMasia->getBannerEmail()) {
                $this->mail->addAttachment($nsMasia->getBannerEmail());       // Add banner attachment if exists
            }
            if ($qrCodeImagePath !== null) {
                $this->mail->addEmbeddedImage($qrCodeImagePath, 'qrcode', 'qrcode.png');  // Add QR code attachment if exists
            }
    
            // Content
            $this->mail->isHTML(true);                                        // Set email format to HTML
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;
            $this->mail->AltBody = strip_tags($body);                         // Set alternate body for non-HTML mail clients
    
            $this->mail->send();
        } catch (Exception $e) {
            $_SESSION["error"] = "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }
    
    protected function baseEmailSignup(string $addAddress, string $nameUser) : void{
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
        // Sending the email with the generated content
        $this->sendEmail($addAddress, $nameUser, $subject, $emailContent);
    }

    protected function baseEmailTicket(string $addAddress, string $nameUser, Order_ticket $order_ticket): void {

        // Initialisation des managers et récupération des données nécessaires
        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();
        $nsName = $nsMasia->getName();
    
        $orderManager = new OrderManager();
        $order = $orderManager->getOrderTicketById($order_ticket);
        $numberOrder = $order_ticket->getNumberOrder();
            
        foreach($order_ticket->getTicketsId() as $ticket){
            $tribune = $ticket["tribune"];
        }
        foreach($order_ticket->getMatchId() as $match){
            $matchName = $match["name"] . "VS" . $match["team"];
            $matchDate = $match["date"] . " à " . $match["time"];
            $matchLocation = $match["home_outside"];
            $matchIsAtStadium = $match["matchIsAtStadium_name"];
        }

        $dateObj = DateTime::createFromFormat('Y-m-d', $order->getDate());
        $dateFormatee = $dateObj->format('d/m/Y');
        $subject = "Merci pour votre achat !";
    
        // Génération du QR code basé sur le numéro de commande
        $qrCodeOptions = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'   => QRCode::ECC_L,
            'size'       => 150,
            'imageBase64' => false // Générer l'image en tant que fichier
        ]);
    
        $qrCode = new QRCode($qrCodeOptions);

        $qrCodeData = "
                        Numéro de commande : $numberOrder.
                        $matchName.
                        Date : $matchDate.
                        Vous etes placé : $tribune.
                        Le match sera à $matchLocation.
                        Le match se tiendra au stade $matchIsAtStadium.
                        Achat effectué le $dateFormatee.
                    ";
        $qrCodeImage = $qrCode->render($qrCodeData);
        
        // Enregistrer l'image QR code en tant que fichier temporaire
        $tempDir = sys_get_temp_dir();
        $qrCodeImagePath = $tempDir . '/qrcode.png';
        file_put_contents($qrCodeImagePath, $qrCodeImage);
    
        // Construction du contenu HTML de l'email avec le QR code inclus
        $emailContent = "
            <!DOCTYPE html>
            <html lang='fr'>
            <head>
                <meta charset='UTF-8'>
                <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>$subject</title>
            </head>
            <body>
                <p>Cher/Chère $nameUser,</p>
            
                <p>
                    Nous sommes ravis de vous informer que votre achat de ticket pour le prochain match de $nsName a bien été enregistré. 
                    Merci pour votre soutien continu à notre équipe !
                </p>
    
                <p>Scannez le QR code ci-dessous pour accéder à votre commande :</p>
    
                <img src='cid:qrcode' alt='QR code' />
    
                <p>
                    Nous sommes impatients de vous accueillir au stade et de partager ensemble la passion du football. 
                    Pour toute question ou assistance supplémentaire, n'hésitez pas à nous contacter.
                </p>
    
                <ul>
                    <li>Votre numéro de commande : $numberOrder</li>
                    <li>Achat effectué le $dateFormatee </li>
                </ul>
            
                <div>
                    <p>Cordialement,</p>
                    <p>L'équipe $nsName</p>
                </div>
            </body>
            </html>
        ";
    
        // Sending the email with the generated content
        $this->sendEmail($addAddress, $nameUser, $subject, $emailContent, $qrCodeImagePath);
    
        // Delete the temporary file after sending the email
        unlink($qrCodeImagePath);
    }  
}


