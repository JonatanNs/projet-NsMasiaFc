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

        $this->render("boutique/panier.html.twig", [
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
        $this->render("boutique/payement.html.twig", [
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
                header("Location: index.php?route=payement");
                exit;

            } else {
                $_SESSION["error"] = "Une erreur est survenue";
                header("Location: index.php?route=payement");
                exit;
            }
        } else {
            $_SESSION["error"] = "Une erreur est survenue";
            header("Location: index.php?route=payement");
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
                        $lastId = $orderManager->createOrderFromProduct(
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
                $name = $users->getFirstName() . ' ' . $users->getLastName();
                
                $order = $orderManager->getAllOrdersProductById($lastId);

                $this->baseEmailPurchases($users->getEmail(), $name, $order);

                $_SESSION["valide"] = "Achat réalisé avec succès";
                header("Location: index.php?route=boutique");
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
                header("Location: index.php?route=billeterie");
                exit;
            }
        } else{
            $_SESSION["error"] = "Une erreur est survenue";
            header("Location: index.php?route=payement");
            exit;
        }
    }
    
    public function generatorNumberOrderBoutique() : string{
        // Incrémenter le numéro de commande
        $uniqueNumber = bin2hex(random_bytes(10));
    
        // Retourner le nouveau numéro de commande
        return "nsboutique" . $uniqueNumber;
    }

    public function generatorNumberOrderTicket() {
        // Incrémenter le numéro de commande
        $uniqueNumber = bin2hex(random_bytes(10));
    
        // Retourner le nouveau numéro de commande
        return "nsTicket" . $uniqueNumber;
    }
        
    public function stripePayement() : void{;
        $products = $_POST['products'];

        $api = $_ENV['API_KEY'];
        \Stripe\Stripe::setApiKey($api);
        
        header('Content-Type: application/json');
        
        $YOUR_DOMAIN = 'http://localhost';

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
            'success_url' => $YOUR_DOMAIN . '/projet-NsMasiaFc/index.php?route=succes',
            'cancel_url' => $YOUR_DOMAIN . '/projet-NsMasiaFc/index.php?route=payement',
        ]);
        
        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkout_session->url);     
    }  

    public function stripePayTicket() : void{

        $tickets = $_POST['arrayTicket'];

        $api = $_ENV['API_KEY'];
        \Stripe\Stripe::setApiKey($api);
        
        header('Content-Type: application/json');
        
        $YOUR_DOMAIN = 'http://localhost';
        
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
            'success_url' => $YOUR_DOMAIN . '/projet-NsMasiaFc/index.php?route=succes',
            'cancel_url' => $YOUR_DOMAIN . '/projet-NsMasiaFc/index.php?route=payementTicket',
        ]);
        
        header("HTTP/1.1 303 See Other");
         header("Location: " . $checkout_session->url);     
    } 
}

    

