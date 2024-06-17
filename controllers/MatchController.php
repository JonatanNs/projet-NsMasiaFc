<?php

class MatchController extends AbstractController
{
    public function billeterie() : void
    {

        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        
        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();

        $matchManager = new MatchManager();
        $matchs = $matchManager->getAllMatchs();
        $this->render("biletterie/billeterie.html.twig", [
            'userIsConect' => $userIsConect,
            'matchs' => $matchs,
            'rolesUser' => $rolesUser,
            'nsMasia' => $nsMasia
        ]);
    }

    public function reservation($id) : void
    {
        $secret = $_ENV['SECRET'];
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        $matchManager = new MatchManager();
        $matchs = $matchManager->getAllMatchsByIdNoPlay($id);
        $tickets = $matchManager->getAllTickets();

        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();

        $this->render("biletterie/reservation.html.twig", [
            'userIsConect' => $userIsConect,
            'matchs' => $matchs,
            'tickets' => $tickets,
            'secret' => $secret,
            'rolesUser' => $rolesUser,
            'nsMasia' => $nsMasia
        ]);
    }

    public function payementTicket() : void
    {
        $secret = $_ENV['SECRET'];
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $matchManager = new MatchManager();
        $tickets = $matchManager->getAllTickets();

        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();

        $this->render("biletterie/payementTicket.html.twig", [
            'userIsConect' => $userIsConect,
            'tickets' => $tickets,
            'tokenCSRF' => $tokenCSRF,
            'secret' => $secret,
            'rolesUser' => $rolesUser,
            'nsMasia' => $nsMasia
        ]);
    }

    public function checkAddResult() : void {
        $secret = $_ENV["SECRET"];
        if(isset($_POST["resultTeamNsMasia"]) && 
            isset($_POST["resultTeamRival"]) && 
            isset($_POST["matchId"])
        ){

            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                $matchManager = new MatchManager();

                $match = $matchManager->getMatchsByIdPlay($_POST["matchId"]);
                $score_nsMasia = htmlspecialchars($_POST["resultTeamNsMasia"]);
                $score_rivalTeam = htmlspecialchars($_POST["resultTeamRival"]);

                $matchManager->addResulteMatch($match, $_POST["matchId"], $score_nsMasia, $score_rivalTeam);
                
                $_SESSION["valide"] = "Resultat match Ajouter.";
                header("Location: index.php?route=checkAdmin&secret=$secret");
                exit;

            } else {
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: index.php?route=checkAdmin&secret=$secret");
                exit;
            }
        } else {
            $_SESSION["error"] = "Veuillez remplir tous les champs.";
            header("Location: index.php?route=checkAdmin&secret=$secret");
            exit;
        }
    }

    public function checkChangeResult() : void {
        $secret = $_ENV["SECRET"];
        
    }

    public function checkRemoveResult() : void {
        $secret = $_ENV["SECRET"];
        
    }
}