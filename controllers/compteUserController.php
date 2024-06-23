<?php
class CompteUserController extends AbstractController{

    public function compteUser() :void {
        $secret = $_ENV["SECRET"];

        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        $userId = isset($_SESSION["userId"]) ? $_SESSION["userId"] : null;
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);
        $orderManager = new OrderManager();
        $userManager = new UserManager();
        $matchManager = new MatchManager();
        $merchManager = new MerchManager();

        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();
    
        $user = $userManager->getAllUserById($userId); 
        $address = $orderManager->getAllAddressesByUserId($user);
        // Vérifier si l'adresse est nulle
        if ($address !== null) {
            $allOrdersProducts = $orderManager->getOrdersByAddresse($address);
            $arrayNumberOrder = [];
            if (!is_null($allOrdersProducts)) {
                foreach ($allOrdersProducts as $allOrdersProduct) {
                    $arrayNumberOrder[] = $allOrdersProduct["order_number"];
                }
            }

            $ordersProducts = [];
    
            foreach ($arrayNumberOrder as $item) {
                $ordersProducts[] = $orderManager->getordersProductByOrderNumber($item);
            }
    
            $allProducts = $merchManager->getAllProducts();
        } else {
            // If the address is null, set the variables to default values
            $allOrdersProducts = [];
            $ordersProducts = [];
            $allProducts = [];
        }
    
        $orderTickets = $orderManager->getAllOrderTicketByUser($user);
        $matchs = [];
        foreach($orderTickets as $orderTicket){
            $matchs[] = $matchManager->getAllMatchsByIdNoPlay($orderTicket['match_id']);
        }

        $this->render("compteUser.html.twig", [
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'userIsConect' => $userIsConect,
            'tokenCSRF' => $tokenCSRF,
            'userId' => $userId,
            'rolesUser' => $rolesUser, 
            'orderTickets' => $orderTickets,
            'matchs' => $matchs,
            'ordersProducts' => $ordersProducts,
            'allOrdersProducts' => $allOrdersProducts,
            'allProducts' => $allProducts,
            'address' => $address,
            'secret' => $secret,
            'nsMasia' => $nsMasia
        ]);
    }

    public function checkChangeAddress() : void{
        if(isset($_POST["address"]) && isset($_POST["orderZipCode"]) && isset($_POST["orderCity"]) 
           && isset($_POST["pays"]) && isset($_POST["user_id"]) && isset($_POST["address_id"])) {
           
            // Validez le jeton CSRF
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
    
                // Récupérez les données du formulaire
                $address = htmlspecialchars($_POST["address"]);
                $complements = htmlspecialchars($_POST["orderComplements"]); 
                $postal_code = htmlspecialchars($_POST["orderZipCode"]); 
                $city = htmlspecialchars($_POST["orderCity"]);
                $pays = htmlspecialchars($_POST["pays"]);
                $addressId = htmlspecialchars($_POST["address_id"]);
    
                $userManager = new UserManager();
                $users = $userManager->getAllUserById($_POST["user_id"]);

                $orderManager = new OrderManager();
                $idAddress = $orderManager->getAddressesById($addressId, $users);

                $orderManager = new OrderManager();
                $orderManager->ChangeAddress($idAddress, $users, $address, $postal_code, $city, $pays, $complements );
                
                $newAddresses = new Addresse($users, $address, $postal_code , $city, $pays);
                $newAddresses->setId($addressId);
                $newAddresses->setComplements($complements);
                $_SESSION['Addresses'] = $newAddresses->getId();

                $_SESSION["valide"] = "Addresse enregistrer";
                header("Location: Compte-Utilisateur");
                exit;

            } else {
                $_SESSION["error"] = "Une erreur est survenue";
                header("Location: Compte-Utilisateur");
                exit;
            }
        } else {
            $_SESSION["error"] = "Une erreur est survenue";
            header("Location: 404");
            exit;
        }
    }

    public function checkChangeName() :void{
        if(isset($_POST["changeFirstName"]) && isset($_POST["changeLast_name"]) && isset($_POST["passwordForChangeName"])){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                $newFistName = htmlspecialchars($_POST["changeFirstName"]);
                $newLastName = htmlspecialchars($_POST["changeLast_name"]);

                $userManager = new UserManager();
                $user = $userManager->getAllUserById($_SESSION["userId"]);

                if(password_verify($_POST["passwordForChangeName"], $user->getPassword())){

                        $userManager->changeName($user->getId(), $newFistName, $newLastName);
                        new User($newFistName, $newLastName, $_SESSION["userEmail"], $user->getPassword());
                        $_SESSION["user"] = $newFistName . ' ' . $newLastName;

                        $_SESSION["valide"] = "Vos informations ont bien été changer";
                        header("Location: Compte-Utilisateur");
                        exit;

                } else {
                    $_SESSION["error"] = "Mot de passe incorrect";
                    header("Location: Compte-Utilisateur");
                    exit;
                }
            } else{
                $_SESSION["error"] = "Une erreur est survenue";
                header("Location: Compte-Utilisateur");
                exit;
            }
        } else {
            $_SESSION["error"] = "Veuillez remplir tous les champs";
            header("Location: Compte-Utilisateur");
            exit;
        }
    }

    public function checkChangerPassword() :void{
        if(isset($_POST["emailForChangePassword"]) && isset($_POST["actualPassword"]) && isset($_POST["changePassword"]) && isset($_POST["confirmChangePassword"])){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                
                $userManager = new UserManager();
                $user = $userManager->getAllUserByEmail($_POST["emailForChangePassword"]);

                if($user === null){
                    $_SESSION["error"] = "Identification incorrect.";
                    header("Location: Compte-Utilisateur");
                    exit;
                } else{
                    if($_POST["changePassword"] === $_POST["confirmChangePassword"]){
                        if(password_verify($_POST["actualPassword"], $user->getPassword())){

                            $password_condition = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%#%§*?&]{8,}$/';

                            if(preg_match($password_condition, $_POST["changePassword"])){

                                $newPassword = password_hash($_POST["changePassword"], PASSWORD_BCRYPT); 

                                $newUser = new User(
                                                        $user->getFirstName(), 
                                                        $user->getLastName(), 
                                                        $user->getEmail(),
                                                        $newPassword
                                                    );
                                $newUser->setId($user->getId());

                                $userManager->changePassword($newUser->getId(), $newUser->getPassword());

                                $nsMasiaManager = new NsMasiaManager();
                                $nsMasia = $nsMasiaManager->getNsMasia();
                                $nsMasiaName = $nsMasia->getName();
                                $nameUser = $newUser->getFirstName() . ' ' . $newUser->getLastName();
                                $emailUser = $newUser->getEmail();
                                $css = $this->css();
                                $subject =  "Vous avez modifier vos informations !";

                                $emailContent = "
                                                <!DOCTYPE html>
                                                <html lang='fr'>
                                                <head>
                                                    <meta charset='UTF-8'>
                                                    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                                                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                                                    <title>$subject</title>
                                                    <style>
                                                        $css
                                                    </style>
                                                </head>
                                                
                                                <body>
                                                    <p>Bonjour $nameUser,</p>
                                                
                                                    <p>Nous vous informons que votre mot de passe sur $nsMasiaName a été modifié avec succès.</p>
                                                    <p>Si vous n'êtes pas à l'origine de cette modification, veuillez contacter notre support immédiatement pour sécuriser votre compte.</p>
                                                
                                                    <div>
                                                        <p>Cordialement,</p>
                                                        <p>L'équipe $nsMasiaName</p>
                                                    </div>
                                                </body>
                                                </html>
                                ";
                                $this->sendEmail($emailUser, $nameUser, $subject, $emailContent);

                                $_SESSION["valide"] = "Vos informations ont bien été changer.";
                                header("Location: Déconnexion");
                                exit;

                            } else{
                                $_SESSION["error"] = "Le mot de passe doit contenir minimum 8 caractères, un caractère spécial, un chiffre, une lettre majuscule et minuscule.";
                                header("Location: Compte-Utilisateur");
                                exit;
                            }
                        } else {
                            $_SESSION["error"] = "Mot de passe incorrect.";
                            header("Location: Compte-Utilisateur");
                            exit;
                        }
                    } else {
                        $_SESSION["error"] = "Le mot de passe et sa confirmation ne correspondent pas.";
                        header("Location: Compte-Utilisateur");
                        exit;
                    }   
                }
            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: Compte-Utilisateur");
                exit;
            }
        } else {
            $_SESSION["error"] = "Veuillez remplir tous les champs.";
            header("Location: Compte-Utilisateur");
            exit;
        }
    }

    public function checkChangerEmail() :void{
        if(isset($_POST["emailForChange"]) && isset($_POST["newEmail"]) && isset($_POST["confirmNewEmail"]) && isset($_POST["passwordForNewEmail"])){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                $userManager = new UserManager();
                $user = $userManager->getAllUserByEmail($_POST["emailForChange"]);

                if($user === null){
                    $_SESSION["error"] = "Email inconnu.";
                    header("Location: Compte-Utilisateur");
                    exit;
                } else {
                    if($_POST["newEmail"] === $_POST["confirmNewEmail"]){
                        if(password_verify($_POST["passwordForNewEmail"], $user->getPassword())){

                            $newEmail = htmlspecialchars($_POST["newEmail"]);

                            $newUser = new User($user->getFirstName(),  
                                                $user->getLastName(),  
                                                $newEmail, 
                                                $user->getPassword()
                                            );
                            $newUser->setId($user->getId());

                            $userManager->changeEmail($newUser->getId(), $newUser->getEmail());

                            $nsMasiaManager = new NsMasiaManager();
                            $nsMasia = $nsMasiaManager->getNsMasia();
                            $nsMasiaName = $nsMasia->getName();
                            $nameUser = $user->getFirstName() . ' ' . $user->getLastName();
                                $emailUser = $user->getEmail();
                                $css = $this->css();
                                
                                $subject =  "Vous avez modifier vos informations !";

                                $emailContent = "
                                                <!DOCTYPE html>
                                                <html lang='fr'>
                                                <head>
                                                    <meta charset='UTF-8'>
                                                    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                                                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                                                    <title>$subject</title>
                                                    <style>
                                                        $css
                                                    </style>
                                                </head>
                                                
                                                <body>
                                                    <p>Bonjour $nameUser,</p>
                                                
                                                    <p>Nous vous informons que votre adresse email sur $nsMasiaName a été modifiée avec succès.</p>
                                                    <p>Si vous n'êtes pas à l'origine de cette modification, veuillez contacter notre support immédiatement pour sécuriser votre compte.</p>
                                                
                                                    <div>
                                                        <p>Cordialement,</p>
                                                        <p>L'équipe $nsMasiaName</p>
                                                    </div>
                                                </body>
                                                </html>
                                
                                ";
                                $this->sendEmail($emailUser, $nameUser, $subject, $emailContent);

                                $authC = new AuthController();
                                $authC->logout();
                            
                            $_SESSION["valide"] = "Vos informations ont bien été changer.";
                            header("Location: Déconnexion");
                            exit;

                        } else {
                            $_SESSION["error"] = "Mot de passe incorrect.";
                            header("Location: Compte-Utilisateur");
                            exit;
                        }
                    } else {
                        $_SESSION["error"] = "L'email et sa confirmation ne correspondent pas.";
                        header("Location: Compte-Utilisateur");
                        exit;
                    }
                } 
            } else {
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: Compte-Utilisateur");
                exit;
            }
        } else {
            $_SESSION["error"] = "Veuillez remplir tous les champs.";
            header("Location: Compte-Utilisateur");
            exit;
        }
    }

}