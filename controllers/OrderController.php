<?php
class OrderController extends AbstractController {
    public function panier() : void {
        $secret = $_ENV["SECRET"];
        $userId = isset($_SESSION["userId"]) ? $_SESSION["userId"] : null;
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);

        $merchManager = new MerchManager();
        $products = $merchManager->getAllProducts();
        $userManager = new UserManager();
        $orderManager = new OrderManager();

        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();

        $user = isset($userId) ? $userManager->getAllUserById($userId) : "";
        $addresse = isset($userId) ? $orderManager->getAllAddressesByUserId($user) : "";

        $this->render("Boutique/panier.html.twig", [
            'userIsConect' => $userIsConect,
            'tokenCSRF' => $tokenCSRF,
            'userId' => $userId,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'products' => $products,
            'addresse' => $addresse,
            'secret' => $secret,
            'nsMasia' => $nsMasia
        ]);
    }
    public function payement() : void{
        $secret = $_ENV["SECRET"];
        $userId = isset($_SESSION["userId"]) ? $_SESSION["userId"] : null;
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);

        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();
        
        $merchManager = new MerchManager();
        $products = $merchManager->getAllProducts();
        $userManager = new UserManager();

        $user = isset($userId) ? $userManager->getAllUserById($_SESSION["userId"]) : "";
        $orderManager = new OrderManager();
        $addresse = isset($userId) ? $orderManager->getAllAddressesByUserId($user) : "";
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        $this->render("Boutique/payement.html.twig", [
            'userIsConect' => $userIsConect,
            'tokenCSRF' => $tokenCSRF,
            'userId' => $userId,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'products' => $products,
            'addresse' => $addresse,
            'secret' => $secret,
            'rolesUser' => $rolesUser,
            'nsMasia' => $nsMasia
        ]);
    }

    public function succesPay() :void {
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $userId = isset($_SESSION["userId"]) ? $_SESSION["userId"] : null;
        $userEmail = isset($_SESSION["userEmail"]) ? $_SESSION["userEmail"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;

        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();

        echo $this->render('succes.html.twig', [
            'tokenCSRF' => $tokenCSRF,
            'userId' => $userId,
            'userEmail' => $userEmail,
            'rolesUser' => $rolesUser,
            'nsMasia' => $nsMasia
        ]);
    } 
    
    public function checkAddress() : void{
        if(isset($_POST["address"]) && 
           isset($_POST["orderZipCode"]) && isset($_POST["orderCity"]) 
           && isset($_POST["pays"]) && isset($_POST["user_id"])) {
           
            // Validez le jeton CSRF
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
    
                // Récupérez les données du formulaire
                $address = htmlspecialchars_decode($_POST["address"]);
                $complements = htmlspecialchars_decode($_POST["orderComplements"]); 
                $postal_code = htmlspecialchars($_POST["orderZipCode"]); 
                $city = htmlspecialchars($_POST["orderCity"]);
                $pays = htmlspecialchars($_POST["pays"]);
    
                $user = new UserManager();
                $users = $user->getAllUserById($_POST["user_id"]);

                $order = new OrderManager();
                $addressesId = $order->createAddresses($users, $address, $postal_code, $city, $pays, $complements);
                
                $newAddresses = new Addresse($users, $address, $postal_code , $city, $pays);
                $newAddresses->setId($addressesId);
                $newAddresses->setComplements($complements);
                $_SESSION['Addresses'] = $newAddresses->getId();

                $_SESSION["valide"] = "Addresse enregistrer";
                header("Location: Paiement");
                exit;

            } else {
                $_SESSION["error"] = "Une erreur est survenue";
                header("Location: Paiement");
                exit;
            }
        } else {
            $_SESSION["error"] = "Une erreur est survenue";
            header("Location: Paiement");
            exit;
        }
    }
    public function checkSucces() : void { 
        if(isset($_POST["products"])) {
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
                $orderManager = new OrderManager();
                $userManager = new UserManager();
                $merch = new MerchManager();
                $users = $userManager->getAllUserById($_SESSION["userId"]);
                $address = $orderManager->getAllAddressesByUserId($users);
                $users = $userManager->getAllUserById((int)$_SESSION["userId"]); 

                $articles = [];
                $quantities = [];
                $sizes = [];
                $totals = [];
    
                // Parcourir les produits pour collecter les informations
                foreach($_POST['products'] as $product){
                    $data = json_decode($product, true);
                    $articles[] = $merch->getAllProductsByName($data['name']);
                    $quantities[] = $data['quantity'];
                    $sizes[] = $data['size'];
                    $totals[] = $data['total'];
                }

                $nextOrderId = $this->generatorNumberOrderBoutique();
            
                    for($i = 0; $i < count($articles); $i++){
                        foreach($articles[$i] as $product){
                       $orderManager->createOrderFromProduct(
                                                                        $nextOrderId,
                                                                        $product['id'],
                                                                        $quantities[$i],
                                                                        $sizes[$i],
                                                                        $totals[$i]
                                                                    ); 
                        }            
                    }

                $orderManager->createOrder($nextOrderId, $address, array_sum($totals));

                $userManager = new UserManager();
                $users = $userManager->getAllUserByEmail($_SESSION["userEmail"]);
                $userEmail = $users->getEmail();
                $name = $users->getFirstName() . ' ' . $users->getLastName();

                $nsMasiaManager = new NsMasiaManager();
                $nsMasia = $nsMasiaManager->getNsMasia();
                $nsName = $nsMasia->getName();
                $totalPrices = array_sum($totals);
                $date = new DateTime();
                $formatted_date = $date->format('d-m-Y H:i');
            
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
                            <p>Bonjour $name,</p>
                        
                            <p>
                                Nous vous remercions sincèrement d'avoir choisi $nsName pour votre récente acquisition ! 
                                C'est un honneur de vous avoir comme client(e).
                            </p>
        
                            <p>
                                Si vous avez des questions ou des préoccupations concernant votre achat, n'hésitez pas à nous contacter. 
                                Nous sommes là pour vous assister.
                            </p>
        
                            <ul>
                                <li>Votre numéro de commande : $nextOrderId</li>
                                <li>Achat éffectuer le $formatted_date </li>
                                <li>Total : $totalPrices €</li>
                            </ul>
                        
                            <div>
                                <p>Cordialement,</p>
                                <p>L'équipe $nsName</p>
                            </div>
                        </body>
                        </html>
        
                ";
                // Sending the email with the generated content
                $this->sendEmail($userEmail, $name, $subject, $emailContent);

                $_SESSION["valide"] = "Achat réalisé avec succès";
                header("Location: Boutique");
                exit;
            }        
        } else if(isset($_POST["arrayTickets"])){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {

                // Parcourir les produits pour collecter les informations
                foreach($_POST['arrayTickets'] as $ticket){
                    $data = json_decode($ticket, true);
                    (int)$quantities = $data['quantity'];
                    (int)$totalPrices = $data['totalPrices'];
                    $ticket_id = $data['ticketId'];
                    $match_id = $data['matchId'];
                }

                $userManager = new UserManager();
                $users = $userManager->getAllUserByEmail($_SESSION["userEmail"]);
                
                $numberOrder = $this->generatorNumberOrderTicket();

                $matchManager = new MatchManager(); 
                $ticket = $matchManager->getTicketsById($ticket_id);

                $matchManager->ChangeStock($ticket->getId(), $ticket->getStock() - 1);

                $orderManager = new OrderManager();
                $orderManager->createOrderTicket($numberOrder, $users, $ticket_id, $match_id, $quantities, $totalPrices);
                $name = $users->getFirstName() . ' ' . $users->getLastName();
                $order = $orderManager->getAllOrderTicketByOrderNumber($users, $numberOrder);

                $this->baseEmailTicket($users->getEmail(), $name, $order);

                $_SESSION["valide"] = "Achat réalisé avec succès";
                header("Location: Billetterie");
                exit;
            }
        } else{
            $_SESSION["error"] = "Une erreur est survenue";
            header("Location: Paiement");
            exit;
        }
    }
    
    public function generatorNumberOrderBoutique() : string{
        // Increment the order number
        $uniqueNumber = bin2hex(random_bytes(5));
    
        // Return the new order number
        return "nsB" . $uniqueNumber;
    }

    public function generatorNumberOrderTicket() {
        // Increment the order number
        $uniqueNumber = bin2hex(random_bytes(10));
    
        // Return the new order number
        return "nsT" . $uniqueNumber;
    }
        
    public function stripePayement() : void{;
        $products = $_POST['products'];

        $api = $_ENV['API_KEY'];
        \Stripe\Stripe::setApiKey($api);
        
        header('Content-Type: application/json');
        
        $YOUR_DOMAIN = 'https://nsmasiafc.alwaysdata.net';

        $line_items = [];
        
        foreach ($products as $product) {
            $data = json_decode($product, true);
            $line_item = [
                'quantity' => $data['quantity'], 
                'price_data' => [
                    'currency' => 'EUR',
                    'product_data' => [
                        'name' => $data['name'],
                        'description' => isset($data['size']) ? $data['size'] : "",
                    ],
                'unit_amount' => $data['prices'] 
                ],
            ];
            $line_items[] = $line_item;
        }
        
        $checkout_session = \Stripe\Checkout\Session::create([
            'submit_type' => 'pay',
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/Payement-valide',
            'cancel_url' => $YOUR_DOMAIN . '/Paiement',
        ]);
        
        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkout_session->url);     
    }  

    public function stripePayTicket() : void{

        $tickets = $_POST['arrayTicket'];

        $api = $_ENV['API_KEY'];
        \Stripe\Stripe::setApiKey($api);
        
        header('Content-Type: application/json');
        
        $YOUR_DOMAIN = 'https://nsmasiafc.alwaysdata.net';
        
        $line_items = [];
        
        foreach ($tickets as $ticket) {
            $data = json_decode($ticket, true);
            $line_item = [
                'quantity' => $data['quantity'], 
                'price_data' => [
                    'currency' => 'EUR',
                    'product_data' => [
                        'name' => $data['match'],
                        'description' => $data['tribune'],
                    ],
                'unit_amount' => $data['prices'] 
                ],
            ];
            $line_items[] = $line_item;
        }
        
        
        $checkout_session = \Stripe\Checkout\Session::create([
            'submit_type' => 'pay',
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/Payement-valide',
            'cancel_url' => $YOUR_DOMAIN . '/Paiement-Billet',
        ]);
        
        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkout_session->url);     
    } 
}

    

