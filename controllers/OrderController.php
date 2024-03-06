<?php


class OrderController extends AbstractController
{
    public function payement() {
        $userId = isset($_SESSION["userId"]) ? $_SESSION["userId"] : null;
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        $addresses = isset($_SESSION["adresse"]) ? $_SESSION["adresse"] : null;
        $price = isset($_SESSION['prices']) ? $_SESSION['prices'] : null;
        $merchManager = new MerchManager();
        $products = $merchManager->getAllProducts();
        
        $this->render("payement.html.twig", [
            'userIsConect' => $userIsConect,
            'tokenCSRF' => $tokenCSRF,
            'userId' => $userId,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'products' => $products,
            'addresses' => $addresses,
            'prices' => $price
        ]);
    }

    public function checkPayement(){
        // Vérifiez la soumission du formulaire
        if(isset($_POST["orderFirstName"]) && isset($_POST["orderLastName"]) && 
           isset($_POST["orderStreet"]) && isset($_POST["orderStreetNumber"]) && 
           isset($_POST["orderComplements"]) && isset($_POST["orderZipCode"]) && 
           isset($_POST["orderCity"]) && isset($_POST["pays"])) {
           
            // Validez le jeton CSRF
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
    
                // Récupérez les données du formulaire
                $firstName = htmlspecialchars($_POST["orderFirstName"]);
                $lastName = htmlspecialchars($_POST["orderLastName"]);
                $street = htmlspecialchars($_POST["orderStreet"]);
                $complements = htmlspecialchars($_POST["orderComplements"]); 
                $city = htmlspecialchars($_POST["orderCity"]);
    
                
                $user = new UserManager();
                $users = $user->getAllUserById($_POST["user_id"]);
                $_SESSION["usersId"] = $users;
                
                $newAddresses = new Addresses($_POST["user_id"], $street, $_POST["orderStreetNumber"], $complements,  $_POST["orderZipCode"], $city);
                
                $orderManager = new OrderManager();

                $_SESSION["adresse"] = $orderManager->getAllAddressesById($_POST["user_id"]);

                $newAddresses->setId($orderManager->createAddresses($users)) ;
                
                
                if(isset($_POST["totalPrices1"]) && isset($_POST["productLength"])) {
                    $date = new DateTime();
                    $formatted_date = $date->format('Y-m-d H:i:s');
                    $mm = new MerchManager();
                    for($i = 1; $i <= (int)$_POST["productLength"]; $i++) {
                        $prices = [];
                        array_push($prices, (int)$_POST["prices" . $i]); // Ajoutez le prix à votre tableau de prix
                        $productId = $_POST["products" . $i]; 
                        $quantity = $_POST["quantity" . $i]; 
                        $products = [];
                        //$product = $mm->getAllProductsById((int)$productId); // Récupérez les détails du produit à partir de la base de données
                        array_push($products, (int)$productId); // Ajoutez le produit à votre tableau de produits
                        // Assurez-vous que $products contient un produit avant de continuer
                        
                        if($products) {
                            // Créez une nouvelle commande pour ce produit
                            $order_products = new Order_products($users, $products, (int)$quantity, $newAddresses, $prices, $formatted_date,(int)$_POST["totalPrices1"]);
                            // Ajoutez la commande à la base de données
                            $orderManager->createOrder($users, $products, (int)$quantity, $newAddresses, $prices);
                            $_SESSION['prices'] = $prices;
                        } else {
                            // Gérez le cas où le produit n'existe pas ou ne peut pas être récupéré
                            echo "Le produit avec l'ID $productId n'existe pas ou n'a pas pu être récupéré.";
                        }
                    }
                    // Redirigez après avoir traité toutes les commandes
                    $_SESSION["valide"] = "Adresse enregistrée";
                    header("Location: index.php?route=payement");
                    exit;
                }
                
                
                    
                } else {
                $_SESSION["error"] = "Remplissez votre adresse de livraison";
                header("Location: index.php?route=payement");
                exit;
                }
            } else {
            $_SESSION["error"] = "Une erreur est survenue";
            header("Location: index.php?route=payement");
            exit;
            }
        }

        public function stripePayement() {
            $api = $_ENV['API_KEY'];
        
            $stripe = new \Stripe\StripeClient($api);
        
            function calculateOrderAmount(int $amount): int {
                // Remplacer cette constante par un calcul du montant de la commande
                // Calculer le total de la commande sur le serveur pour éviter
                // personnes de manipuler directement le montant sur le client
                return $amount * 100;
            }
        
            header('Content-Type: application/json');
        
            try {
                // retrieve JSON from POST body
                $jsonStr = file_get_contents('php://input');
                $jsonObj = json_decode($jsonStr);
                
                // TODO : Create a PaymentIntent with amount and currency in '$paymentIntent'
                $paymentIntent = $stripe->paymentIntents->create([ 
                    'amount' => calculateOrderAmount($jsonObj->amount),
                    'currency' => 'eur'
                ]);
        
                $output = [
                    'clientSecret' => $paymentIntent->client_secret,
                ];
        
                // Rediriger avant d'envoyer la réponse JSON
                echo json_encode($output);
                
            } catch (Error $e) {
                http_response_code(500);
                echo json_encode(['error' => $e->getMessage()]);
            }
        }
        
}
        
    


