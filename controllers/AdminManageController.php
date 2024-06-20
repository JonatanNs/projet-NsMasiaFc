<?php 

class AdminManageController extends AbstractController{
    
    public function adminManage() : void{
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
    
            $this->render("Admin/adminManage.html.twig", [
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

    public function adminNsMasia() : void{
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
    
            $this->render("Admin/adminInfo.html.twig", [
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
    public function checkChangeRole() : void {
        $secret = $_ENV["SECRET"];
        if (isset($_POST["emailUser"]) && isset($_POST["choiceRole"])) {
            $tokenManager = new CSRFTokenManager(); 
            if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
    
                $email = htmlspecialchars($_POST["emailUser"]);
                $choiceRole = htmlspecialchars($_POST["choiceRole"]);
    
                $userManager = new UserManager();
                $user = $userManager->getAllUserByEmail($email);
    
                // Check if the user exists
                if ($user) {
                    $userManager->changeRoles($user->getId(), $choiceRole);
    
                    $_SESSION["valide"] = "Mise à jour du rôle de " .  $user->getFirstName() . " " . $user->getLastName() . ".";
                    header("Location: index.php?route=manageAdmin&secret=$secret");
                    exit;
                } else {
                    $_SESSION["error"] = "L'utilisateur avec l'email $email n'existe pas.";
                    header("Location: index.php?route=manageAdmin&secret=$secret");
                    exit;
                }
    
            } else {
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: index.php?route=manageAdmin&secret=$secret");
                exit;
            }
        } else {
            $_SESSION["error"] = "Une erreur est survenue.";
            header("Location: index.php?route=manageAdmin&secret=$secret");
            exit;
        } 
    }
    
}