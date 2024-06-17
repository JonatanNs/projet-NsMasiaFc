<?php

class AdminController extends AbstractController{

    public function checkAdmin() : void {
        $secret = $_ENV["SECRET"];
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);

        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        
        $rivalTeam = new RivalTeamManager();
        $teamRival = $rivalTeam->getAllRivalTeams();
            
        $rivalTeamManager = new RivalTeamManager();
        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();
    
        $this->render("Admin/admin.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser,
            'teamRival' => $teamRival,
            'secret' => $secret,
            'nsMasia' => $nsMasia
        ]);
    }

    public function adminHome() : void {
        $secret = $_ENV["SECRET"];
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
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

        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();

        $this->render("Admin/adminHome.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser,
            'teamRival' => $teamRival,
            'matchPlays' => $matchPlay,
            'resultMatchs' => $resultMatchs,
            'secret' => $secret,
            'nsMasia' => $nsMasia
        ]);
    }  

}