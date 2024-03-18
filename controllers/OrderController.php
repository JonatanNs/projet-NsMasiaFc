<?php
class OrderController extends AbstractController
{
    public function panier() {
        $userId = isset($_SESSION["userId"]) ? $_SESSION["userId"] : null;
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        $merchManager = new MerchManager();
        $products = $merchManager->getAllProducts();
        $userManager = new UserManager();
        $orderManager = new OrderManager();

        $user = isset($userId) ? $userManager->getAllUserById($userId) : "";
        $addresse = isset($userId) ? $orderManager->getAllAddressesByUserId($user) : "";

        $this->render("panier.html.twig", [
            'userIsConect' => $userIsConect,
            'tokenCSRF' => $tokenCSRF,
            'userId' => $userId,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'products' => $products,
            'addresse' => $addresse
        ]);
    }
    public function payement() {
        $userId = isset($_SESSION["userId"]) ? $_SESSION["userId"] : null;
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        $merchManager = new MerchManager();
        $products = $merchManager->getAllProducts();
        $userManager = new UserManager();

        $user = isset($userId) ? $userManager->getAllUserById($_SESSION["userId"]) : "";
        $orderManager = new OrderManager();
        $addresse = isset($userId) ? $orderManager->getAllAddressesByUserId($user) : "";
        $this->render("payement.html.twig", [
            'userIsConect' => $userIsConect,
            'tokenCSRF' => $tokenCSRF,
            'userId' => $userId,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'products' => $products,
            'addresse' => $addresse
        ]);
    }

    public function succesPay() {
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $userId = isset($_SESSION["userId"]) ? $_SESSION["userId"] : null;
        // Rendu de la vue avec Twig
        echo $this->render('succes.html.twig', [
            'tokenCSRF' => $tokenCSRF,
            'userId' => $userId
        ]);
        
    } 
    
    public function checkAddress(){
        if(isset($_POST["address"]) && 
           isset($_POST["orderZipCode"]) && isset($_POST["orderCity"]) 
           && isset($_POST["pays"]) && isset($_POST["user_id"])) {
           
            // Validez le jeton CSRF
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
    
                // Récupérez les données du formulaire
                $address = htmlspecialchars($_POST["address"]);
                $complements = htmlspecialchars($_POST["orderComplements"]); 
                $postal_code = htmlspecialchars($_POST["orderZipCode"]); 
                $city = htmlspecialchars($_POST["orderCity"]);
                $pays = htmlspecialchars($_POST["pays"]);
    
                $user = new UserManager();
                $users = $user->getAllUserById($_POST["user_id"]);

                $order = new OrderManager();
                $addressesId = $order->createAddresses($users, $address, $postal_code, $city, $pays, $complements);
                
                $newAddresses = new Addresses($users, $address, $postal_code , $city, $pays);
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

    public function checkSucces() { 
        var_dump($_POST);
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
                $sizes = [];;
                $totals = [];
    
                // Parcourir les produits pour collecter les informations
                foreach($_POST['products'] as $product){
                    $data = json_decode($product, true);
                    $articles[] = $merch->getAllProductsByName($data['name']);
                    $quantities[] = $data['quantity'];
                    $sizes[] = $data['size'];
                    $totals[] = $data['total'];
                }
                
                var_dump($totals);

                $nextOrderId = $this->generatorNumberOrder();
                var_dump($nextOrderId);
            
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
                

                $_SESSION["valide"] = "Achat réalisé avec succès";
                header("Location: index.php?route=boutique");
                exit;
            }        
        } 
    }
    
    public function generatorNumberOrder() {
        // Incrémenter le numéro de commande
        $uniqueNumber = bin2hex(random_bytes(10));
    
        // Retourner le nouveau numéro de commande
        return "nsfc" . $uniqueNumber;
    }
        
    public function stripePayement() {;
        var_dump($_POST);
        $products = $_POST['products'];

        $api = $_ENV['API_KEY'];
        \Stripe\Stripe::setApiKey($api);
        
        header('Content-Type: application/json');
        
        $YOUR_DOMAIN = 'http://localhost:3000';
        
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
            'success_url' => $YOUR_DOMAIN . '/projet-3wa/projet-NsMasiaFc/index.php?route=succes',
            'cancel_url' => $YOUR_DOMAIN . '/projet-3wa/projet-NsMasiaFc/index.php?route=payement',
        ]);
        
        header("HTTP/1.1 303 See Other");
         header("Location: " . $checkout_session->url);     
    }  
}

    

