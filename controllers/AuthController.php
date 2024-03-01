<?php

class AuthController extends AbstractController
{
    public function home()
    {
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
        $this->render("home.html.twig", [
            'userIsConect' => $userIsConect
        ]);
    }

    public function form() {
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);
        $this->render("form.html.twig", [
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage     
        ]);
    }

    public function checkSignup() {
        if(isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["emailSignup"]) && 
        isset($_POST["passwordSignup"]) && isset($_POST["confirmPasswordSignup"])){

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
                        $password = password_hash($_POST["passwordSignup"], PASSWORD_BCRYPT); //RequiPuigdu35# //LeoMessi10# //KyllianMbaape10#
                        $user = new Users($first_name, $last_name, $email, $password);
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
        } 
    }

    public function checkLogin() {
        if(isset($_POST["emailLogin"]) && isset($_POST["passwordLogin"])){
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
                    header("Location: index.php?route=home");
                    exit;
                } else {
                    $_SESSION["error"] = "Identifiant ou le mot de passe est incorrect.";
                    header("Location: index.php?route=form");
                exit;
                }          
            }
        }
    }
    public function logout() : void
    {
        session_destroy();

        header("Location: index.php?route=home");
    }

}