<?php

class AuthController extends AbstractController
{
    public function home()
    {
        $nsMasiaManager = new NsMasiaManager();
        $matchManager = new MatchManager();
        $rivalTeamManager = new RivalTeamManager();
        $articleManager = new ArticleManager();

        $matchPlays = $matchManager->getMatchsPlay();
        $resultMatchs = $matchManager->getAllResultMatch();

        $allTeam = $rivalTeamManager->getAllTeams();

        $matchs = $matchManager->getAllMatchs();

        $nsMasia = $nsMasiaManager->getNsMasia();

        $articles = $articleManager->getAllArticle();

        $userId = isset($_SESSION["userId"]) ? $_SESSION["userId"] : null;
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        $adminRnd7sX23 =  isset($_ENV['ConnexionAdmin_35as3ENm7LV3nz3Nej4R']) ? $_ENV['ConnexionAdmin_35as3ENm7LV3nz3Nej4R'] : null;
        
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);
        $this->render("home.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage,
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'userId' => $userId,
            'rolesUser' => $rolesUser,
            'nsMasia' => $nsMasia,
            'matchs' => $matchs,
            'adminRnd7sX23' => $adminRnd7sX23,
            'allTeam' => $allTeam,
            'matchPlays' => $matchPlays,
            'resultMatchs' => $resultMatchs,
            'articles' => $articles
        ]);
    }

    public function allRanking(){
        $nsMasiaManager = new NsMasiaManager();
        $matchManager = new MatchManager();
        $rivalTeamManager = new RivalTeamManager();

        $matchPlays = $matchManager->getMatchsPlay();
        $resultMatchs = $matchManager->getAllResultMatch();

        $allTeam = $rivalTeamManager->getAllTeams();

        $matchs = $matchManager->getAllMatchs();

        $nsMasia = $nsMasiaManager->getNsMasia();

        $userId = isset($_SESSION["userId"]) ? $_SESSION["userId"] : null;
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        $adminRnd7sX23 =  isset($_ENV['ConnexionAdmin_35as3ENm7LV3nz3Nej4R']) ? $_ENV['ConnexionAdmin_35as3ENm7LV3nz3Nej4R'] : null;
        
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);
        $this->render("ranking.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage,
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'userId' => $userId,
            'rolesUser' => $rolesUser,
            'nsMasia' => $nsMasia,
            'matchs' => $matchs,
            'adminRnd7sX23' => $adminRnd7sX23,
            'allTeam' => $allTeam,
            'matchPlays' => $matchPlays,
            'resultMatchs' => $resultMatchs
        ]);
    }

    public function form() {
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);
        $this->render("form.html.twig", [
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser
        ]);
    }

    public function checkSignup() {
        if(isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["emailSignup"]) && 
        isset($_POST["passwordSignup"]) && isset($_POST["confirmPasswordSignup"])){

            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){ 
                
                $password_condition = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%#%§*?&]{8,}$/';
                //si le mot de passe et le confirm mot de passe est le meme 
                if ($_POST["passwordSignup"] === $_POST["confirmPasswordSignup"]) {

                    //verifie si le mot de passe respect bien les conditions du password_condition
                    if(preg_match($password_condition, $_POST["passwordSignup"])){
                        $userManager = new UserManager();
                        $users = $userManager->getAllUserByEmail($_POST["emailSignup"]);
                        if( $users === null){
                            $first_name = htmlspecialchars($_POST["first_name"]);
                            $last_name = htmlspecialchars($_POST["last_name"]);
                            $email = htmlspecialchars($_POST["emailSignup"]);
                            $password = password_hash($_POST["passwordSignup"], PASSWORD_BCRYPT); 
                            $user = new User($first_name, $last_name, $email, $password);

                            //insert a la base de donner
                            $userManager->SignUpUser($user);

                            $_SESSION["valide"] = "Inscription réussi, connectez-vous !";
                            header("Location: index.php?route=form");
                            exit;

                        } else{
                            $_SESSION["error"] = "Cette adresse e-mail est déjà utilisée par un autre utilisateur.";
                            header("Location: index.php?route=form");
                            exit;
                        }
                    } else {
                        $_SESSION["error"] = "Le mot de passe doit contenir minimum 8 caractères, un caractère spécial, un chiffre, une lettre majuscule et minuscule.";
                        header("Location: index.php?route=form");
                        exit;
                    }  
                } else {
                    $_SESSION["error"] = "Le mot de passe et sa confirmation ne correspondent pas.";
                    header("Location: index.php?route=form");
                    exit;
                }
            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: index.php?route=form");
                exit;
            }
        }
    }

    public function checkLogin() {
        if(isset($_POST["emailLogin"]) && isset($_POST["passwordLogin"])){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){ 
                
                $userManager = new UserManager();
                $users = $userManager->getAllUserByEmail($_POST["emailLogin"]);

                if($users === null){
                    $_SESSION["error"] = "Identifiant ou le mot de passe est incorrect.";
                    header("Location: index.php?route=form");
                    exit;
                } else{
                    if(password_verify($_POST["passwordLogin"], $users->getPassword())){
                        unset($_SESSION["error-message"]);
                        $_SESSION["user"] = $users->getFirstName() . ' ' . $users->getLastName();
                        $_SESSION["firstName"] = $users->getFirstName();
                        $_SESSION["lastName"] = $users->getLastName();
                        $_SESSION["userId"] = $users->getId();
                        $_SESSION["userEmail"] = $users->getEmail();
                        $_SESSION['userRoles'] = $users->getRoles();
                        $_SESSION["valide"] = "Connexion reussie.";
                        if( $_SERVER['HTTP_REFERER'] === "http://localhost:3000/projet-3wa/projet-NsMasiaFc/index.php?route=form"){
                            header('Location: index.php?route=home');
                            exit;
                        } else{
                            header('Location: ' . $_SERVER['HTTP_REFERER']);
                            exit;
                        }
                        
                    } else {
                        $_SESSION["error"] = "Identifiant mot de passe incorrect.";
                        header("Location: index.php?route=form");
                    exit;
                    }          
                }
            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: index.php?route=form");
                exit;
            } 
        }
    }

    public function logout() : void
    {
        session_destroy();
        header("Location: index.php?route=home");
    }

}