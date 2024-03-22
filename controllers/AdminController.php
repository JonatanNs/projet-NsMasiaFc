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

        $this->render("admin.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser,
            'teamRival' => $teamRival
        ]);
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