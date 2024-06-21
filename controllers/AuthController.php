<?php

class AuthController extends AbstractController
{
    

    public function page404() :void{
        $nsMasiaManager = new NsMasiaManager();
        $matchManager = new MatchManager();
        $rivalTeamManager = new RivalTeamManager();

        $matchPlays = $matchManager->getMatchsPlay();
        $resultMatchs = $matchManager->getAllResultMatch();

        $allTeam = $rivalTeamManager->getAllTeams();

        $matchs = $matchManager->getAllMatchs();

        $nsMasia = $nsMasiaManager->getNsMasia();

        $userId = isset($_SESSION["userId"]) ? $_SESSION["userId"] : null;
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);

        $secret = $_ENV["SECRET"];

        $this->render("page404.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage,
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'userId' => $userId,
            'rolesUser' => $rolesUser,
            'nsMasia' => $nsMasia,
            'matchs' => $matchs,
            'allTeam' => $allTeam,
            'matchPlays' => $matchPlays,
            'resultMatchs' => $resultMatchs,
            'secret' => $secret
        ]);
    }

    public function form() :void {
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);

        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();

        $secret = $_ENV["SECRET"];
        $this->render("form.html.twig", [
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser,
            'secret' => $secret,
            'nsMasia' => $nsMasia
        ]);
    }

    public function checkSignup() : void{
        if(
            isset($_POST["first_name"]) && 
            isset($_POST["last_name"]) && 
            isset($_POST["emailSignup"]) && 
            isset($_POST["passwordSignup"]) && 
            isset($_POST["confirmPasswordSignup"])
        ){

            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){ 
                
                $password_condition = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%#%§*?&]{8,}$/';
                //if the password and the confirm password is the same
                if ($_POST["passwordSignup"] === $_POST["confirmPasswordSignup"]) {

                    //checks if the password complies with the password_condition conditions
                    if(preg_match($password_condition, $_POST["passwordSignup"])){
                        $userManager = new UserManager();
                        $users = $userManager->getAllUserByEmail($_POST["emailSignup"]);
                        if( $users === null){
                            $first_name = htmlspecialchars($_POST["first_name"]);
                            $last_name = htmlspecialchars($_POST["last_name"]);
                            $email = htmlspecialchars($_POST["emailSignup"]);
                            $password = password_hash($_POST["passwordSignup"], PASSWORD_BCRYPT); 
                            $user = new User($first_name, $last_name, $email, $password);

                            $userManager->SignUpUser($user);

                            $name = $first_name . ' ' . $last_name;

                            $this->baseEmailSignup($email, $name);

                            $_SESSION["valide"] = "Inscription réussi, connectez-vous ! Un email vous a été envoyé.";
                            header("Location: Formulaire");
                            exit;  
                            
                        } else{
                            $_SESSION["error"] = "Cette adresse e-mail est déjà utilisée par un autre utilisateur.";
                            header("Location: Formulaire");
                            exit;
                        }
                    } else {
                        $_SESSION["error"] = "Le mot de passe doit contenir minimum 8 caractères, un caractère spécial, un chiffre, une lettre majuscule et minuscule.";
                        header("Location: Formulaire");
                        exit;
                    }  
                } else {
                    $_SESSION["error"] = "Le mot de passe et sa confirmation ne correspondent pas.";
                    header("Location: Formulaire");
                    exit;
                }
            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: Formulaire");
                exit;
            }
        } else{
            $_SESSION["error"] = "Une erreur est survenue.";
            header("Location: 404");
            exit;
        }
    }

    public function checkLogin() : void {
        if(isset($_POST["emailLogin"]) && isset($_POST["passwordLogin"])){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){ 
                
                $email = htmlspecialchars($_POST["emailLogin"]);
                $password = htmlspecialchars($_POST["passwordLogin"]);

                $userManager = new UserManager();
                $users = $userManager->getAllUserByEmail($email);

                if($users === null){
                    $_SESSION["error"] = "Identifiant ou le mot de passe est incorrect.";
                    header("Location: Formulaire");
                    exit;
                } else{
                    if(password_verify($password, $users->getPassword())){
                        unset($_SESSION["error"]);
                        $_SESSION["firstAndLastName"] = $users->getFirstName() . ' ' . $users->getLastName();
                        $_SESSION["firstName"] = $users->getFirstName();
                        $_SESSION["lastName"] = $users->getLastName();
                        $_SESSION["userId"] = $users->getId();
                        $_SESSION["userEmail"] = $users->getEmail();
                        $_SESSION['userRoles'] = $users->getRoles();

                        $_SESSION["valide"] = "Connexion reussie.";
                        header('Location: Accueil');
                        exit; 
                    } else {
                        $_SESSION["error"] = "Identifiant ou mot de passe incorrect.";
                        header("Location: Formulaire");
                    exit;
                    }          
                }
            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: Formulaire");
                exit;
            } 
        }
    }

    public function logout() : void
    {
        session_destroy();
        header("Location: Accueil");
    }

}