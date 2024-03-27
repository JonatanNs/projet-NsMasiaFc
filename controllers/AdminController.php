<?php

class AdminController extends AbstractController{

    public function checkAdmin(){
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);
        $rivalTeam = new RivalTeamManager();
        $teamRival = $rivalTeam->getAllRivalTeams();

        $rivalTeamManager = new RivalTeamManager();
        $nsMasiaManager = new NsMasiaManager();


        $this->render("Admin/admin.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser,
            'teamRival' => $teamRival
        ]);
    }

    public function adminHome(){
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);
        $rivalTeam = new RivalTeamManager();
        $teamRival = $rivalTeam->getAllRivalTeams();

        $this->render("Admin/adminHome.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser,
            'teamRival' => $teamRival
        ]);
    }

    public function adminClub(){
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);
        $rivalTeam = new RivalTeamManager();
        $teamRival = $rivalTeam->getAllRivalTeams();

        $this->render("Admin/adminClub.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser,
            'teamRival' => $teamRival
        ]);
    }

    public function adminActualite(){
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);
        $rivalTeam = new RivalTeamManager();
        $teamRival = $rivalTeam->getAllRivalTeams();

        $this->render("Admin/adminActualite.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser,
            'teamRival' => $teamRival
        ]);
    }

    public function adminBoutique(){
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);
        $rivalTeam = new RivalTeamManager();
        $teamRival = $rivalTeam->getAllRivalTeams();

        $this->render("Admin/adminBoutique.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser,
            'teamRival' => $teamRival
        ]);
    }

    public function adminBiletterie(){
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $userIsConect = isset($_SESSION["user"]) ? $_SESSION["user"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);
        $rivalTeam = new RivalTeamManager();
        $teamRival = $rivalTeam->getAllRivalTeams();

        $matchManager = new MatchManager();
        $matchPlay = $matchManager->getMatchsPlay();
        $resultMatchs = $matchManager->getAllResultMatch();

        $this->render("Admin/adminBiletterie.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser,
            'teamRival' => $teamRival,
            'matchPlays' => $matchPlay,
            'resultMatchs' => $resultMatchs
        ]);
    }

    public function checkAddResult(){
        if(isset($_POST["teamNsMasia"]) && isset($_POST["teamRival"]) && isset($_POST["matchId"]) && isset($_POST["nsMasiaTeam"]) && isset($_POST["rivalTeam_id"])){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                $matchManager = new MatchManager();
                $match = $matchManager->getAllMatchsByIdPlay($_POST["matchId"]);
                
                $score_nsMasia = htmlspecialchars($_POST["teamNsMasia"]);
                $score_rivalTeam = htmlspecialchars($_POST["teamRival"]);
                $nsMasia = htmlspecialchars($_POST["nsMasiaTeam"]);
                $rivalTeam = htmlspecialchars($_POST["rivalTeam_id"]);

                $matchManager->addResulteMatch($match, $score_nsMasia, $score_rivalTeam, $nsMasia, $rivalTeam);
                
                $_SESSION["valide"] = "Resultat match Ajouter.";
                header('Location: index.php?route=checkAdmin');
                exit;

            } else {
                $_SESSION["error"] = "Une erreur est survenue.";
                header('Location: index.php?route=checkAdmin');
                exit;
            }
        } else {
            $_SESSION["error"] = "Veuillez remplir tous les champs.";
            header('Location: index.php?route=checkAdmin');
            exit;
        }
    }

    public function checkAddMatchs(){
        var_dump($_POST);
        if(isset($_POST["location"]) && isset($_POST["rivalTeam"]) && isset($_POST["date"]) && isset($_POST["time"])){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                $location = htmlspecialchars($_POST["location"]);
                $rivalTeam = htmlspecialchars($_POST["rivalTeam"]);
                $heures_input = htmlspecialchars($_POST["time"]);
                $date_input = htmlspecialchars($_POST["date"]);
                //mettre la date format sql
                $date = new DateTime($date_input);
                $formatted_date = $date->format('Y-m-d');
                
                $matchManager = new MatchManager();
                $nsManager = new NsMasiaManager();
                $rivalTeamManager = new RivalTeamManager();
                $nsMasia = $nsManager->getNsMasia();
                //var_dump($nsMasia[0]['id']);

                $team = $rivalTeamManager->getAllRivalTeamsByName($rivalTeam);
                //var_dump($team->getId());
                $matchManager->createMatch($nsMasia, $team->getId(), $location, $heures_input, $formatted_date);

                $_SESSION["valide"] = "Nouveau match Ajouter.";
                header('Location: index.php?route=checkAdmin');
                exit;
            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header('Location: index.php?route=checkAdmin');
                exit;
            }
        } else{
            $_SESSION["error"] = "Une erreur est survenue.";
            header('Location: index.php?route=checkAdmin');
            exit;
        } 
    }
}