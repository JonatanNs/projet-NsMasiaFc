<?php 

class HomeController extends AbstractController{
    public function home() :void
    {
        $nsMasiaManager = new NsMasiaManager();
        $matchManager = new MatchManager();
        $rivalTeamManager = new RivalTeamManager();
        $articleManager = new ArticleManager();

        $matchPlays = $matchManager->getMatchsPlay();
        $resultMatchs = $matchManager->getAllResultMatch();

        $allTeam = $rivalTeamManager->getAllTeams();

        $matchs = $matchManager->getAllMatchs();

        if (!empty($matchs)) {
            // Fonction pour comparer les dates
            usort($matchs, function($a, $b) {
                return strtotime($a['date']) - strtotime($b['date']);
            });
        
            // Trouver le premier match qui n'est pas passé
            $now = time();
            $next_match = null;
        
            foreach ($matchs as $match) {
                if (strtotime($match['date']) >= $now) {
                    $next_match = $match;
                    break;
                }
            }
        } else {
            $next_match = null;
        }
        
        $nsMasia = $nsMasiaManager->getNsMasia();

        $articles = $articleManager->getAllArticle();

        $players = $nsMasiaManager->getPlayerNsMasia();

        $userId = isset($_SESSION["userId"]) ? $_SESSION["userId"] : null;
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);

        $secret = $_ENV["SECRET"];
        $this->render("home.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage,
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'userId' => $userId,
            'rolesUser' => $rolesUser,
            'nsMasia' => $nsMasia,
            'next_match' => $next_match,
            'allTeam' => $allTeam,
            'matchPlays' => $matchPlays,
            'resultMatchs' => $resultMatchs,
            'articles' => $articles,
            'players' => $players,
            'secret' => $secret
        ]);
    }

    public function allRanking() :void{
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

        $this->render("ranking.html.twig", [
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

    public function legalNotice() : void {
        $userId = isset($_SESSION["userId"]) ? $_SESSION["userId"] : null;
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);

        $secret = $_ENV["SECRET"];

        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();

        $this->render("LegalNotice/legalNotice.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage,
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'userId' => $userId,
            'rolesUser' => $rolesUser,
            'nsMasia' => $nsMasia,
            'secret' => $secret
        ]);
    }

    public function generalConditionsSale() : void {
        $userId = isset($_SESSION["userId"]) ? $_SESSION["userId"] : null;
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);

        $secret = $_ENV["SECRET"];

        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();

        $this->render("LegalNotice/generalConditionsSale.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage,
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'userId' => $userId,
            'rolesUser' => $rolesUser,
            'nsMasia' => $nsMasia,
            'secret' => $secret
        ]);
    }

    public function cookies() : void {
        $userId = isset($_SESSION["userId"]) ? $_SESSION["userId"] : null;
        $userIsConect = isset($_SESSION["firstAndLastName"]) ? $_SESSION["firstAndLastName"] : null;
        $tokenCSRF = isset($_SESSION["csrf-token"]) ? $_SESSION["csrf-token"] : null;
        $rolesUser = isset($_SESSION['userRoles']) ? $_SESSION['userRoles'] : null;
        
        $errorMessage = isset($_SESSION["error"]) ? $_SESSION["error"] : null;
        $valideMessage = isset($_SESSION["valide"]) ? $_SESSION["valide"] : null;
        unset($_SESSION["error"]);
        unset($_SESSION["valide"]);

        $secret = $_ENV["SECRET"];

        $nsMasiaManager = new NsMasiaManager();
        $nsMasia = $nsMasiaManager->getNsMasia();


        $this->render("LegalNotice/cookies.html.twig", [
            'userIsConect' => $userIsConect,
            'errorMessage' => $errorMessage,
            'valideMessage' => $valideMessage,
            'tokenCSRF' => $tokenCSRF,
            'userId' => $userId,
            'rolesUser' => $rolesUser,
            'nsMasia' => $nsMasia,
            'secret' => $secret
        ]);
    }
}