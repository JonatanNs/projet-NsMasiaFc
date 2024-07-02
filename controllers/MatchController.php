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

        $this->render("Biletterie/billeterie.html.twig", [
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

        $this->render("Biletterie/reservation.html.twig", [
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

        $this->render("Biletterie/payementTicket.html.twig", [
            'userIsConect' => $userIsConect,
            'tickets' => $tickets,
            'tokenCSRF' => $tokenCSRF,
            'secret' => $secret,
            'rolesUser' => $rolesUser,
            'nsMasia' => $nsMasia
        ]);
    }

    public function checkAddResult() : void {
        var_dump($_POST);
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

                $newResultMatch = new ResultMatch(  
                                                    $match, 
                                                    $score_nsMasia, 
                                                    $score_rivalTeam
                                                );

                $matchManager->addResulteMatch(
                                                $match, 
                                                $_POST["matchId"],
                                                $newResultMatch->getScoreNsMasia(), 
                                                $newResultMatch->getScoreRivalTeam()
                                            );

                if (isset($_POST['resultOtherRivalTeam']) && is_array($_POST['resultOtherRivalTeam'])) {
                    $results = [];
                                            
                    foreach ($_POST['resultOtherRivalTeam'] as $result) {
                        // Separates outcome type and team ID
                        list($resultType, $teamId) = explode(',', $result);
                                            
                        // Stocke le résultat dans un tableau associatif
                        $results[] = [
                            'team_id' => $teamId,
                            'result_type' => $resultType
                        ];
                        $rivalTeamManager = new RivalTeamManager();     
                        $rivalTeam = $rivalTeamManager->getAllRivalTeamsById($teamId);           
                        // Check the result type
                        if ($resultType === "Win") {
                            // Treat here the case where the result is "Win
                                          
                            $rivalTeamManager->checkChangePointRivalTeam(
                                $teamId,
                                $rivalTeam->getRankingPoints() + 3,
                                $rivalTeam->getMatchsPlay() + 1,
                                $rivalTeam->getMatchsWin() + 1,
                                $rivalTeam->getMatchsLose(),
                                $rivalTeam->getMatchsNul()
                            );                
                                                        
                        } else if($resultType === "Lose"){
                            $rivalTeamManager->checkChangePointRivalTeam(
                                $teamId,
                                $rivalTeam->getRankingPoints(),
                                $rivalTeam->getMatchsPlay() + 1,
                                $rivalTeam->getMatchsWin(),
                                $rivalTeam->getMatchsLose() + 1,
                                $rivalTeam->getMatchsNul()
                            );  
                        } else if($resultType === "Nul"){
                            $rivalTeamManager->checkChangePointRivalTeam(
                                $teamId,
                                $rivalTeam->getRankingPoints() + 1,
                                $rivalTeam->getMatchsPlay() + 1,
                                $rivalTeam->getMatchsWin(),
                                $rivalTeam->getMatchsLose(),
                                $rivalTeam->getMatchsNul() +1
                            );
                        } 
                    }
                }
                                            
                $_SESSION["valide"] = "Resultat match Ajouter.";
                header("Location: Admin-Match-$secret");
                exit;

            } else {
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: Admin-Match-$secret");
                exit;
            }
        } else {
            $_SESSION["error"] = "Veuillez remplir tous les champs.";
            header("Location: Admin-Match-$secret");
            exit;
        }
    }

    public function checkChangeResult() : void {
        $secret = $_ENV["SECRET"];
        if(isset($_POST["resultMatchId"]) && 
            isset($_POST["resultTeamRival"]) && 
            isset($_POST["resultTeamNsMasia"])
        ){

            $tokenManager = new CSRFTokenManager(); 
            if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])){
                $matchManager = new MatchManager();

                $resultId = htmlspecialchars($_POST["resultMatchId"]);
                $score_nsMasia = htmlspecialchars($_POST["resultTeamNsMasia"]);
                $score_rivalTeam = htmlspecialchars($_POST["resultTeamRival"]);

                $matchManager->changeResult($resultId, $score_nsMasia, $score_rivalTeam);
                
                $_SESSION["valide"] = "Resultat match modifier.";
                header("Location: Admin-Match-$secret");
                exit;

            } else {
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: Admin-Match-$secret");
                exit;
            }
        } else {
            $_SESSION["error"] = "Une erreur est survenu.";
            header("Location: 404");
            exit;
        } 
    }
}