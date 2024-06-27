<?php

class AdminBilleterieController extends AbstractController{
    public function adminBiletterie() : void {
        $secret = $_ENV["SECRET"];
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);
        $rivalTeamManager = new RivalTeamManager();
        $teamRival = $rivalTeamManager->getAllRivalTeams();

        $matchNsManager = new MatchManager();
        $matchs = $matchNsManager->getAllMatchs();
        $tickets = $matchNsManager->getAllTickets();

        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();

        $this->render("Admin/adminBilletterie.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser,
            'teamRival' => $teamRival,
            'secret' => $secret,
            'nsMasia' => $nsMasia,
            'tickets' => $tickets,
            'matchs' => $matchs
        ]);
    }

     /***************************** Admin Billeterie ***************************/
     public function checkAddMatchs() : void {
        $secret = $_ENV["SECRET"];
        var_dump($_POST);
        if(
            isset($_POST["location"]) && 
            isset($_POST["rivalTeam"]) && 
            isset($_POST["date"]) && 
            isset($_POST["time"]) 
            ){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                $location = htmlspecialchars($_POST["location"]);
                $rivalTeam = htmlspecialchars($_POST["rivalTeam"]);
                $heures_input = htmlspecialchars($_POST["time"]);
                $date_input = htmlspecialchars($_POST["date"]);

                $date = new DateTime($date_input);
                $formatted_date = $date->format('Y-m-d');
                
                $matchManager = new MatchManager();
                $nsManager = new NsMasiaManager();
                $rivalTeamManager = new RivalTeamManager();

                $nsMasia = $nsManager->getNsMasia();
                $team = $rivalTeamManager->getAllRivalTeamsByName($rivalTeam);
                $newMatch = new MatchNs(
                                            $nsMasia, 
                                            $team, 
                                            $location, 
                                            $heures_input, 
                                            $formatted_date
                                        );
                $matchManager->createMatch($newMatch);       

                $_SESSION["valide"] = "Nouveau match Ajouter.";
                header("Location: Admin-Billetterie-$secret");
                exit;
            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: Admin-Billetterie-$secret");
                exit;
            }
        } else{
            $_SESSION["error"] = "Une erreur est survenue.";
            header("Location: Admin-Billetterie-$secret");
            exit;
        } 
    }

    public function checkChangeMatch() : void {
        $secret = $_ENV["SECRET"];
        if(
            isset($_POST["location"]) && 
            isset($_POST["rivalTeam"]) && 
            isset($_POST["date"]) && 
            isset($_POST["time"]) &&
            isset($_POST["selectMatch"])
            ){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                $location = htmlspecialchars_decode($_POST["location"]);
                $rivalTeam = htmlspecialchars($_POST["rivalTeam"]);
                $heures_input = htmlspecialchars($_POST["time"]);
                $date_input = htmlspecialchars($_POST["date"]);
                $matchId = htmlspecialchars($_POST["selectMatch"]);

                $date = new DateTime($date_input);
                $formatted_date = $date->format('Y-m-d');
                
                $nsManager = new NsMasiaManager();
                $nsMasia = $nsManager->getNsMasia();

                $rivalTeamManager = new RivalTeamManager();
                $team = $rivalTeamManager->getAllRivalTeamsByName($rivalTeam);

                $matchManager = new MatchManager();
                $matchManager->changeMatch( $matchId, 
                                            $nsMasia, 
                                            $team, 
                                            $location, 
                                            $heures_input, 
                                            $formatted_date);

                $_SESSION["valide"] = "Match modifier.";
                header("Location: Admin-Billetterie-$secret");
                exit;
            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: Admin-Billetterie-$secret");
                exit;
            }
        } else{
            $_SESSION["error"] = "Une erreur est survenue.";
            header("Location: Admin-Billetterie-$secret");
            exit;
        } 
    }

    public function checkRemoveMatch() : void {
        $secret = $_ENV["SECRET"];
        if(
            isset($_POST["selectMatch"]) 
            ){
            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){

                $matchId = htmlspecialchars($_POST["selectMatch"]);
                
                $matchManager = new MatchManager();
                $matchManager->removeMatch($matchId);

                $_SESSION["valide"] = "Match retirer.";
                header("Location: Admin-Billetterie-$secret");
                exit;
            } else{
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: Admin-Billetterie-$secret");
                exit;
            }
        } else{
            $_SESSION["error"] = "Une erreur est survenue.";
            header("Location: Admin-Billetterie-$secret");
            exit;
        } 
    }
}