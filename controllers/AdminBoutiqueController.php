<?php 

class AdminBoutiqueController extends AbstractController{

    public function adminBoutique() : void {
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

        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();

        $this->render("Admin/adminBoutique.html.twig", [
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

    public function checkAddproduct() : void {
        $secret = $_ENV["SECRET"];

    }

    public function checkRemoveproduct() : void {
        $secret = $_ENV["SECRET"];
        
    }
}