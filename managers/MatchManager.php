<?php 

class MatchManager extends AbstractManager{

    /***************************************
                * MATCHS *
    **************************************/
    public function createMatch(NsMasia $nsMasia, int $rivalTeam, string $domicileExterieur, string $time, string $date) {  
        // Insérer le nouveau match
        $query = $this->db->prepare("INSERT INTO matchs (id, ns_masia_id, rivalTeam_id, domicileExterieur, time, date) 
                                            VALUES (null, :ns_masia_id, :rivalTeam_id, :domicileExterieur, :time, :date)");
        $parameters = [ 
            'ns_masia_id' => $nsMasia->getId(),
            'rivalTeam_id' => $rivalTeam, 
            'domicileExterieur' => $domicileExterieur, 
            'time' => $time, 
            'date' => $date
        ];
        $query->execute($parameters);
    
        // Récupérer l'ID du match nouvellement inséré
        $matchId = $this->db->lastInsertId();
    
        if ($domicileExterieur === "exterieur") {
            // Sélectionner l'ID de l'emplacement correspondant à l'équipe rivale
            $query = $this->db->prepare("SELECT id FROM locations WHERE rivalTeam_id = :rivalTeam_id");
            $parameters = [
                'rivalTeam_id' => $rivalTeam
            ];
            $query->execute($parameters);
            $locationId = $query->fetchColumn();
            
            // Insérer la relation dans match_location
            $query = $this->db->prepare("INSERT INTO match_location (match_id, location_id) VALUES (:match_id, :location_id)");
            $parameters = [
                'match_id' => $matchId,
                'location_id' => $locationId
            ];
            $query->execute($parameters);
        }  
    }
    
    public function getAllMatchs() {  
        $query = $this->db->prepare("SELECT matchs.id AS match_id, matchs.*, nsMasia.*, rivalsTeam.*, 
                                    CASE 
                                        WHEN matchs.domicileExterieur = 'domicile' THEN nsMasia.stadium
                                        WHEN matchs.domicileExterieur = 'exterieur' THEN locations.stadium
                                    END AS matchIsAtStadium_name
                                        FROM matchs 
                                        JOIN nsMasia ON nsMasia.id = matchs.ns_masia_id 
                                        JOIN rivalsTeam ON rivalsTeam.id = matchs.rivalTeam_id 
                                        LEFT JOIN locations ON locations.rivalTeam_id = rivalsTeam.id
                                        LEFT JOIN match_location ON match_location.location_id = locations.id AND match_location.match_id = matchs.id
                                        WHERE matchs.date >= CURRENT_DATE
                                        ORDER BY matchs.date ASC ");
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
    
        $matchs = [];
        $nsMasiaMasia = new NsMasiaManager();
        $rivalTeamManager = new RivalTeamManager();
        
        foreach ($results as $result) {
            $nsMasia = $nsMasiaMasia->getNsMasia();
            $rivalTeam = $rivalTeamManager->getAllRivalTeamsById($result["rivalTeam_id"]);
    
            $match = new MatchNs($nsMasia, $rivalTeam, $result["domicileExterieur"], $result["time"],  $result["date"]);
            $match->setId($result["id"]);
            $matchs[] = $result;
        }
        
        return $matchs; 
    }

    public function getAllMatchsByIdNoPlay(int $id) {  
        $query = $this->db->prepare("SELECT matchs.id AS match_id, matchs.*, nsMasia.*, rivalsTeam.*, 
                                    CASE 
                                        WHEN matchs.domicileExterieur = 'domicile' THEN nsMasia.stadium
                                        WHEN matchs.domicileExterieur = 'exterieur' THEN locations.stadium
                                    END AS matchIsAtStadium_name
                                        FROM matchs 
                                        JOIN nsMasia ON nsMasia.id = matchs.ns_masia_id 
                                        JOIN rivalsTeam ON rivalsTeam.id = matchs.rivalTeam_id 
                                        LEFT JOIN locations ON locations.rivalTeam_id = rivalsTeam.id
                                        LEFT JOIN match_location ON match_location.location_id = locations.id AND match_location.match_id = matchs.id
                                        WHERE matchs.date >= CURRENT_DATE AND matchs.id = :id 
                                        ORDER BY matchs.date ASC ");
        $parameters = [
            'id' => $id
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);
    
        $matchs = [];
        $nsMasiaMasia = new NsMasiaManager();
        $rivalTeamManager = new RivalTeamManager();
        
        if($result) {
            $nsMasia = $nsMasiaMasia->getNsMasia();
            $rivalTeam = $rivalTeamManager->getAllRivalTeamsById($result["rivalTeam_id"]);
    
            $match = new MatchNs($nsMasia, $rivalTeam, $result["domicileExterieur"], $result["time"],  $result["date"]);
            $match->setId($result["id"]);
            $matchs[] = $result;
        }
        
        return $matchs; 
    }

    public function getAllMatchsByIdPlay(int $id) {  
        $query = $this->db->prepare("SELECT matchs.id AS match_id, matchs.*, nsMasia.*, rivalsTeam.*, 
                                    CASE 
                                        WHEN matchs.domicileExterieur = 'domicile' THEN nsMasia.stadium
                                        WHEN matchs.domicileExterieur = 'exterieur' THEN locations.stadium
                                    END AS matchIsAtStadium_name
                                        FROM matchs 
                                        JOIN nsMasia ON nsMasia.id = matchs.ns_masia_id 
                                        JOIN rivalsTeam ON rivalsTeam.id = matchs.rivalTeam_id 
                                        LEFT JOIN locations ON locations.rivalTeam_id = rivalsTeam.id
                                        LEFT JOIN match_location ON match_location.location_id = locations.id AND match_location.match_id = matchs.id
                                        WHERE matchs.id = :id 
                                        ORDER BY matchs.date ASC ");
        $parameters = [
            'id' => $id
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);
    
        $matchs = [];
        $nsMasiaMasia = new NsMasiaManager();
        $rivalTeamManager = new RivalTeamManager();
        
        if($result) {
            $nsMasia = $nsMasiaMasia->getNsMasia();
            $rivalTeam = $rivalTeamManager->getAllRivalTeamsById($result["rivalTeam_id"]);
    
            $match = new MatchNs($nsMasia, $rivalTeam, $result["domicileExterieur"], $result["time"],  $result["date"]);
            $match->setId($result["id"]);
            $matchs[] = $result;
        }
        
        return $matchs; 
    }

    public function getMatchsPlay() {  
        $query = $this->db->prepare("SELECT matchs.id AS match_id, matchs.*, nsMasia.*, rivalsTeam.*, 
                                        CASE 
                                            WHEN matchs.domicileExterieur = 'domicile' THEN nsMasia.stadium
                                            WHEN matchs.domicileExterieur = 'exterieur' THEN locations.stadium
                                        END AS matchIsAtStadium_name
                                    FROM matchs 
                                    JOIN nsMasia ON nsMasia.id = matchs.ns_masia_id 
                                    JOIN rivalsTeam ON rivalsTeam.id = matchs.rivalTeam_id 
                                    LEFT JOIN locations ON locations.rivalTeam_id = rivalsTeam.id
                                    LEFT JOIN match_location ON match_location.location_id = locations.id AND match_location.match_id = matchs.id
                                    WHERE matchs.date <= CURRENT_DATE()
                                    ORDER BY matchs.date DESC
                                    ");

        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
    
        $matchs = [];
        $nsMasiaMasia = new NsMasiaManager();
        $rivalTeamManager = new RivalTeamManager();
        
        if($result) {
            $nsMasia = $nsMasiaMasia->getNsMasia();
            $rivalTeam = $rivalTeamManager->getAllRivalTeamsById($result["rivalTeam_id"]);
    
            $match = new MatchNs($nsMasia, $rivalTeam, $result["domicileExterieur"], $result["time"],  $result["date"]);
            $match->setId($result["id"]);
            $matchs[] = $result;
        }
        
        return $matchs; 
    }

    /***************************************
            * TICKET MATCH *
    **************************************/

    public function getAllTickets() : array {  

        $query = $this->db->prepare("SELECT * FROM tickets");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC); 

        $tickets = [];
        foreach($result as $item){
            $user = new Ticket($item["tribune"], $item["prices"], $item["stock"]);
            $user->setId($item["id"]);
            $tickets[] = $item;
        }
        return $tickets;
        
    }

    public function getAllTicketsById(int $id) {  

        $query = $this->db->prepare("SELECT * FROM tickets WHERE id = :id");
        $parameters = [
            'id' => $id
        ];
        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC); 

        $tickets = [];

        foreach($result as $item){
            $ticket = new Ticket($item["tribune"], $item["prices"], $item["stock"]);
            $ticket->setId($item["id"]);
            $tickets[] = $item;
        }
        return $tickets;  
    } 

/***************************************
        * RESULT MATCH *
 **************************************/

    public function getAllResultMatch() : array {  

        $query = $this->db->prepare("SELECT * FROM result_match");
        $query->execute();
        $resultMatch = $query->fetchAll(PDO::FETCH_ASSOC); 

        return $resultMatch; 
    }

    public function getAllResultMatchByMatch(array $match) : array {  

        $query = $this->db->prepare("SELECT * FROM result_match");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC); 

        $resultMatch = [];
        foreach($result as $item){
            $user = new ResultMatch( $match , $item["score_nsMasia"], $item["score_rivalTeam"]);
            $user->setId($item["id"]);
            $resultMatch[] = $item;
        }
        return $resultMatch; 
    }


    public function addResulteMatch(array $matchNs, int $score_nsMasia, int $score_rivalTeam, int $rivalTeamId, int $nsMasiaId) {  
        foreach($matchNs as $match){
           $matchId = $match["match_id"];
        }
        $query = $this->db->prepare("INSERT INTO result_match (id, match_id, score_nsMasia, score_rivalTeam) 
                                        VALUES (null, :match_id, :score_nsMasia, :score_rivalTeam)");
        $parameters = [
            'match_id' => $matchId, 
            'score_nsMasia' => $score_nsMasia, 
            'score_rivalTeam' => $score_rivalTeam
        ];
        $query->execute($parameters);

        $rivalTeamManager = new RivalTeamManager();
        $nsMasiaManager = new NsMasiaManager();

        $rival = $rivalTeamManager->getAllRivalTeamsById($rivalTeamId);
        $nsMasia = $nsMasiaManager->getNsMasia();

        $rankingPoint = $rival->getRankingPoints();
        $matchNul = $rival->getMatchsNul();
        $matchPlay = $rival->getMatchsPlay();
        $matchWin = $rival->getMatchsWin();
        $matchLose = $rival->getMatchsLose();

        $matchLoseNs = $nsMasia->getMatchsLose();
        $matchWinNs = $nsMasia->getMatchsWin();
        $matchNulNs = $nsMasia->getMatchsNul();
        $matchPlayNs = $nsMasia->getMatchsPlay();
        $rankingPointNs = $nsMasia->getRankingPoints();

        if($score_nsMasia === $score_rivalTeam){ /*************** SI NS MASIA match nul **************/

            $query = $this->db->prepare("UPDATE rivalsTeam SET ranking_points = :ranking_points, match_play = :match_play, match_nul = :match_nul WHERE id = :id");
            $parameters = [
                'id' => $rival->getId(),
                'ranking_points' => $rankingPoint + 1,
                'match_play' => $matchPlay + 1,
                'match_nul' => $matchNul + 1,
            ];
            $query->execute($parameters);

            $query = $this->db->prepare("UPDATE nsMasia SET ranking_points = :ranking_points, match_play = :match_play, match_nul = :match_nul WHERE id = :id");
            $parameters = [
                'id' => $nsMasia->getId(),
                'ranking_points' => $rankingPointNs + 1,
                'match_play' => $matchPlayNs + 1,
                'match_nul' => $matchNulNs + 1
            ];
            $query->execute($parameters);

        } else if($score_nsMasia > $score_rivalTeam){ /*************** SI NS MASIA Gagne le match **************/

            $query = $this->db->prepare("UPDATE rivalsTeam SET match_play = :match_play, match_lose = :match_lose WHERE id = :id");
            $parameters = [
                'id' => $rival->getId(),
                'match_play' => $matchPlay + 1,
                'match_lose' => $matchLose + 1
            ];
            $query->execute($parameters);

            $query = $this->db->prepare("UPDATE nsMasia SET ranking_points = :ranking_points, match_play = :match_play, match_win = :match_win WHERE id = :id");
            $parameters = [
                'id' => $nsMasia->getId(),
                'ranking_points' => $rankingPointNs + 3,
                'match_play' => $matchPlayNs + 1,
                'match_win' => $matchWinNs + 1
            ];
            $query->execute($parameters);

        } else if($score_nsMasia < $score_rivalTeam){ /*************** SI NS MASIA Perd le match **************/
            
            $query = $this->db->prepare("UPDATE rivalsTeam SET ranking_points = :ranking_points, match_play = :match_play, match_win = :match_win WHERE id = :id");
            $parameters = [
                'id' => $rival->getId(),
                'ranking_points' => $rankingPoint + 3,
                'match_play' => $matchPlay + 1,
                'match_win' => $matchWin + 1
            ];
            $query->execute($parameters);

            $query = $this->db->prepare("UPDATE nsMasia SET match_play = :match_play, match_lose = :match_lose WHERE id = :id");
            $parameters = [
                'id' => $nsMasia->getId(),
                'match_play' => $matchPlayNs + 1,
                'match_lose' => $matchLoseNs + 1
            ];
            $query->execute($parameters);
        }
    }  
}