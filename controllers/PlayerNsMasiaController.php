<?php

class PlayerNsMasiaController extends AbstractController{

    public function club() : void{
        $secret = $_ENV["SECRET"];
        $nsMasiaManager = new NsMasiaManager();
        $playersNs = $nsMasiaManager->getPlayerNsMasia();
        $nsMasia = $nsMasiaManager->getNsMasia();

        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;

        $this->render("club.html.twig", [
            'userIsConect' => $userIsConect,
            'playersNs' => $playersNs,
            'secret' => $secret,
            'rolesUser' => $rolesUser,
            'nsMasia' => $nsMasia
        ]);
    }

    public function adminClub() : void {
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
        $players = $nsMasiaManager->getPlayerNsMasia();

        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();

        $this->render("Admin/adminClub.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage, 
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'rolesUser' => $rolesUser,
            'teamRival' => $teamRival,
            'players' => $players,
            'secret' => $secret,
            'nsMasia' => $nsMasia 
        ]);
    }

    public function checkAddPlayer() {
        $secret = $_ENV["SECRET"];
        if (
            isset($_POST["addFirst_name"]) && 
            isset($_POST["addLast_name"]) &&
            isset($_POST["addNameJersay"]) && 
            isset($_POST["addNumberJersay"]) &&
            isset($_POST["addPoste"])
        ) {
            $tokenManager = new CSRFTokenManager(); 
            if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
                $firstName = htmlspecialchars($_POST["addFirst_name"]);
                $lastName = htmlspecialchars($_POST["addLast_name"]);
                $nameJersay = htmlspecialchars($_POST["addNameJersay"]);
                $number = htmlspecialchars($_POST["addNumberJersay"]);
                $position = htmlspecialchars($_POST["addPoste"]);
    
                $nsmasiaManager = new NsMasiaManager();
    
                if ($nsmasiaManager->isJerseyNumberExists($number)) {
                    $_SESSION["error"] = "Un joueur a déjà ce numéro de maillot.";
                } else {
                    $nsmasiaManager->createPlayer($firstName, $lastName, $nameJersay, $number, $position);
                    $_SESSION["valide"] = "Joueur ajouté.";
                }
            } else {
                $_SESSION["error"] = "Une erreur est survenue.";
            }
        } else {
            $_SESSION["error"] = "Une erreur est survenue.";
        }
    
        header("Location: index.php?route=adminClub&secret=$secret");
        exit;
    }

    public function checkRemovePlayer() : void {
        $secret = $_ENV["SECRET"];
        if ( isset($_POST["playerId"])) {
            $tokenManager = new CSRFTokenManager(); 
            if (isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
    
                $nsmasiaManager = new NsMasiaManager();
                $nsmasiaManager->removePlayer($_POST["playerId"]);

                $_SESSION["valide"] = "Joueur retirer.";

            }

        } else {
            $_SESSION["error"] = "Une erreur est survenue.";
        }
    
        header("Location: index.php?route=adminClub&secret=$secret");
        exit;
    }
}