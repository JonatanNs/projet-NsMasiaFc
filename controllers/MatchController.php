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
                        // Sépare le type de résultat et l'ID de l'équipe
                        list($resultType, $teamId) = explode(',', $result);
                                            
                        // Stocke le résultat dans un tableau associatif
                        $results[] = [
                            'team_id' => $teamId,
                            'result_type' => $resultType
                        ];
                        $rivalTeamManager = new RivalTeamManager();     
                        $rivalTeam = $rivalTeamManager->getAllRivalTeamsById($teamId);           
                        // Vérifie le type de résultat
                        if ($resultType === "Win") {
                            // Traitez ici le cas où le résultat est "Win"
                                          
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
                        } else {
                            $rivalTeamManager->checkChangePointRivalTeam(
                                $teamId,
                                $rivalTeam->getRankingPoints(),
                                $rivalTeam->getMatchsPlay() ,
                                $rivalTeam->getMatchsWin(),
                                $rivalTeam->getMatchsLose(),
                                $rivalTeam->getMatchsNul()
                            );
                        }
                    }
                }
                                            
                $_SESSION["valide"] = "Resultat match Ajouter.";
                header("Location: index.php?route=adminMatch&secret=$secret");
                exit;

            } else {
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: index.php?route=adminMatch&secret=$secret");
                exit;
            }
        } else {
            $_SESSION["error"] = "Veuillez remplir tous les champs.";
            header("Location: index.php?route=adminMatch&secret=$secret");
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
                header("Location: index.php?route=adminMatch&secret=$secret");
                exit;

            } else {
                $_SESSION["error"] = "Une erreur est survenue.";
                header("Location: index.php?route=adminMatch&secret=$secret");
                exit;
            }
        } else {
            $_SESSION["error"] = "Une erreur est survenu.";
            header("Location: index.php?route=page404");
            exit;
        } 
    }
}