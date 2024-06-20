<?php 

class MatchManager extends AbstractManager{

    /**********************************************************
                             * CREATE *
    **********************************************************/
    public function createMatch( MatchNs $match ) : void {  
        try{ 
            $query = $this->db->prepare("INSERT INTO matchs (
                                                                id, 
                                                                ns_masia_id, 
                                                                rivalTeam_id, 
                                                                home_outside, 
                                                                time, 
                                                                date
                                                            ) 
                                                VALUES (null, 
                                                        :ns_masia_id, 
                                                        :rivalTeam_id, 
                                                        :home_outside, 
                                                        :time, 
                                                        :date)");
            $parameters = [ 
                'ns_masia_id' => $match->getNsMasiaId()->getId(),
                'rivalTeam_id' => $match->getRivalTeamId()->getId(), 
                'home_outside' => $match->gethome_outside(), 
                'time' => $match->getTime(), 
                'date' => $match->getDate()
            ];
            $query->execute($parameters);
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to create match.");
        }
    
        // Retrieve the newly inserted match ID
        $matchId = $this->db->lastInsertId();
    
        if ($match->gethome_outside() === "exterieur") {
            // Select the ID of the location corresponding to the rival team
            $query = $this->db->prepare("SELECT id FROM locations WHERE rivalTeam_id = :rivalTeam_id");
            $parameters = [
                'rivalTeam_id' => $match->getRivalTeamId()->getId()
            ];
            $query->execute($parameters);
            $locationId = $query->fetchColumn();
            
            // Insert the relationship in match_location
            try{
                $query = $this->db->prepare("INSERT INTO match_location (match_id, location_id) 
                                            VALUES (:match_id, :location_id)");
                $parameters = [
                    'match_id' => $matchId,
                    'location_id' => $locationId
                ];
                $query->execute($parameters);
            } catch (PDOException $e){
                error_log("Database error : " . $e->getMessage());
                throw new Exception("Failed to insert match location.");
            }
        }  
    }

    /**********************************************************
                             * Update Match *
    **********************************************************/

    public function changeMatch(    
                                    int $id,
                                    NsMasia $nsMasia, 
                                    RivalTeam $rivalTeam, 
                                    string $home_outside, 
                                    string $time, 
                                    string $date

                                ) : void {
        try{
            $query = $this->db->prepare("UPDATE matchs 
                                        SET ns_masia_id = :ns_masia_id, 
                                            rivalTeam_id = :rivalTeam_id, 
                                            home_outside = :home_outside, 
                                            time = :time,  
                                            date = :date
                                        WHERE id = :id");
            
            $parameters = [
                'id' => $id,
                'ns_masia_id' => $nsMasia->getId(),
                'rivalTeam_id' => $rivalTeam->getId(),
                'home_outside' => $home_outside,
                'time' => $time,
                'date' =>  $date
            ];
            $query->execute($parameters); 
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to change match.");
        }
    }
    
    /**********************************************************
                             * REMOVE Match *
    **********************************************************/
    public function removeMatch(int $id) : void {
        try{
            $query = $this->db->prepare("DELETE FROM matchs WHERE id = :id");
            $parameters = [
                'id' =>  $id, 
            ];
            $query->execute($parameters);
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to remove match.");
        } 
    }

    /**********************************************************
                             * FETCH Match*
    **********************************************************/
    
    public function getAllMatchs() : array {  
        $query = $this->db->prepare("SELECT matchs.id AS match_id, matchs.*, nsMasia.*, rivalsTeam.*, 
                                    CASE 
                                        WHEN matchs.home_outside = 'domicile' THEN nsMasia.stadium
                                        WHEN matchs.home_outside = 'exterieur' THEN locations.stadium
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
    
            $match = new MatchNs($nsMasia, 
                                $rivalTeam, 
                                $result["home_outside"], 
                                $result["time"],  
                                $result["date"]
                            );
            $match->setId($result["id"]);
            $matchs[] = $result;
        }
        
        return $matchs; 
    }

    public function getAllMatchsByIdNoPlay(int $id) : array { 
        try{ 
            $query = $this->db->prepare("SELECT matchs.id AS match_id, matchs.*, nsMasia.*, rivalsTeam.*, 
                                        CASE 
                                            WHEN matchs.home_outside = 'domicile' THEN nsMasia.stadium
                                            WHEN matchs.home_outside = 'exterieur' THEN locations.stadium
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
        
                $match = new MatchNs($nsMasia, 
                                    $rivalTeam, 
                                    $result["home_outside"], 
                                    $result["time"],  
                                    $result["date"]
                                );
                $match->setId($result["id"]);
                $matchs[] = $result;
            }
            
            return $matchs; 
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch all matchs by id no play.");
        }
    }

    public function getMatchsByIdPlay(int $id) : ? MatchNs {  
        try{
            $query = $this->db->prepare("SELECT matchs.id AS match_id, matchs.*, nsMasia.*, rivalsTeam.*, 
                                        CASE 
                                            WHEN matchs.home_outside = 'domicile' THEN nsMasia.stadium
                                            WHEN matchs.home_outside = 'exterieur' THEN locations.stadium
                                        END AS matchIsAtStadium_name
                                            FROM matchs 
                                            JOIN nsMasia ON nsMasia.id = matchs.ns_masia_id 
                                            JOIN rivalsTeam ON rivalsTeam.id = matchs.rivalTeam_id 
                                            LEFT JOIN locations ON locations.rivalTeam_id = rivalsTeam.id
                                            LEFT JOIN match_location ON match_location.location_id = locations.id AND match_location.match_id = matchs.id
                                            WHERE matchs.id = :id 
                                            ORDER BY matchs.date ASC");
            $parameters = [
                'id' => $id
            ];
            $query->execute($parameters);
            $result = $query->fetch(PDO::FETCH_ASSOC);
        
            $nsMasiaMasia = new NsMasiaManager();
            $rivalTeamManager = new RivalTeamManager();
            
            if($result) {
                $nsMasia = $nsMasiaMasia->getNsMasia();
                $rivalTeam = $rivalTeamManager->getAllRivalTeamsById($result["rivalTeam_id"]);
        
                $match = new MatchNs($nsMasia, 
                                    $rivalTeam, 
                                    $result["home_outside"], 
                                    $result["time"],  
                                    $result["date"]
                                );
                $match->setId($result["id"]);
                return $match;
            }
            
            return null; 
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch match by id play.");
        }
    }
    //returns the last game play
    public function getMatchsPlay() : array {  
        try{
            $query = $this->db->prepare("SELECT matchs.id AS match_id, matchs.*, nsMasia.*, rivalsTeam.*, 
                                            CASE 
                                                WHEN matchs.home_outside = 'domicile' THEN nsMasia.stadium
                                                WHEN matchs.home_outside = 'exterieur' THEN locations.stadium
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
        
                $match = new MatchNs($nsMasia, 
                                    $rivalTeam, 
                                    $result["home_outside"], 
                                    $result["time"],  
                                    $result["date"]
                                );
                $match->setId($result["id"]);
                $matchs[] = $result;
            }
            
            return $matchs; 
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch match play.");
        }
    }

    /***************************************
            * TICKET MATCH *
    **************************************/

    public function ChangeStock( int $id, int $number ) : void{
        try{
            $query = $this->db->prepare("UPDATE tickets 
                                        SET stock = :stock
                                        WHERE id = :id ");
            $parameters = [
                'id' => $id,
                'stock' => $number
            ];
            $query->execute($parameters);
        } catch (PDOException $e) {
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to change stock.");
        }
    }

    public function getAllTickets() : array {  
        try{
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
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch all tickets.");
        }
    }

    public function getAllTicketsById(int $id) : array{  
        try{
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
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch all tickets by id.");
        }
    } 

    public function getTicketsById(int $id) : ? Ticket{  
        try{
            $query = $this->db->prepare("SELECT * FROM tickets WHERE id = :id");
            $parameters = [
                'id' => $id
            ];
            $query->execute($parameters);
            $item = $query->fetch(PDO::FETCH_ASSOC); 

            if($item){
                $newTicket = new Ticket($item["tribune"], $item["prices"], $item["stock"]);
                $newTicket->setId($item["id"]);
                return $newTicket; 
            }
             return null;
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch all tickets by id.");
        }
    }

/***************************************
        * RESULT MATCH *
 **************************************/

    public function addResulteMatch(
                                    MatchNs $matchNs, 
                                    int $matchId, 
                                    int $score_nsMasia, 
                                    int $score_rivalTeam
                                ) : void{  
        try{ 
            $query = $this->db->prepare("INSERT INTO result_match (id, match_id, score_nsMasia, score_rivalTeam) 
                    VALUES (null, :match_id, :score_nsMasia, :score_rivalTeam)");
            $parameters = [
                'match_id' => $matchId, 
                'score_nsMasia' => $score_nsMasia, 
                'score_rivalTeam' => $score_rivalTeam
            ];
            $query->execute($parameters);
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to add result match.");
        }
            $rivalTeam = $matchNs->getRivalTeamId();
            $nsMasia = $matchNs->getNsMasiaId();

            $rankingPoint = $rivalTeam->getRankingPoints();
            $matchNul = $rivalTeam->getMatchsNul();
            $matchPlay = $rivalTeam->getMatchsPlay();
            $matchWin = $rivalTeam->getMatchsWin();
            $matchLose = $rivalTeam->getMatchsLose();

            $matchLoseNs = $nsMasia->getMatchsLose();
            $matchWinNs = $nsMasia->getMatchsWin();
            $matchNulNs = $nsMasia->getMatchsNul();
            $matchPlayNs = $nsMasia->getMatchsPlay();
            $rankingPointNs = $nsMasia->getRankingPoints();

        if($score_nsMasia === $score_rivalTeam){ /*************** SI NS MASIA draw **************/
        try{
            $query = $this->db->prepare("UPDATE rivalsTeam 
                                        SET ranking_points = :ranking_points,
                                            match_play = :match_play, 
                                            match_nul = :match_nul 
                                        WHERE id = :id");
            $parameters = [
                'id' => $rivalTeam->getId(),
                'ranking_points' => $rankingPoint + 1,
                'match_play' => $matchPlay + 1,
                'match_nul' => $matchNul + 1,
            ];
            $query->execute($parameters);
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to update match nul for rival team.");
        }
        try{
            $query = $this->db->prepare("UPDATE nsMasia 
                                        SET ranking_points = :ranking_points, 
                                            match_play = :match_play, 
                                            match_nul = :match_nul 
                                        WHERE id = :id");
            $parameters = [
                'id' => $nsMasia->getId(),
                'ranking_points' => $rankingPointNs + 1,
                'match_play' => $matchPlayNs + 1,
                'match_nul' => $matchNulNs + 1
            ];
            $query->execute($parameters);
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to update match lose.");
        }

        } else if($score_nsMasia > $score_rivalTeam){ /*************** IF NS MASIA Win the match **************/
        try{
            $query = $this->db->prepare("UPDATE rivalsTeam 
                                        SET match_play = :match_play, match_lose = :match_lose 
                                        WHERE id = :id");
            $parameters = [
                'id' => $rivalTeam->getId(),
                'match_play' => $matchPlay + 1,
                'match_lose' => $matchLose + 1
            ];
            $query->execute($parameters);
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to update match win for rival team.");
        }
        try{
            $query = $this->db->prepare("UPDATE nsMasia 
                                        SET ranking_points = :ranking_points, 
                                            match_play = :match_play, 
                                            match_win = :match_win 
                                        WHERE id = :id");
            $parameters = [
                'id' => $nsMasia->getId(),
                'ranking_points' => $rankingPointNs + 3,
                'match_play' => $matchPlayNs + 1,
                'match_win' => $matchWinNs + 1
            ];
            $query->execute($parameters);
        } catch (PDOException $e){
        error_log("Database error : " . $e->getMessage());
        throw new Exception("Failed to update match nul for Ns Masia.");
        }

        } else if($score_nsMasia < $score_rivalTeam){ /*************** IF NS MASIA Loses the match **************/
        try{
            $query = $this->db->prepare("UPDATE rivalsTeam 
                                        SET ranking_points = :ranking_points, 
                                            match_play = :match_play, 
                                            match_win = :match_win 
                                        WHERE id = :id");
            $parameters = [
                'id' => $rivalTeam->getId(),
                'ranking_points' => $rankingPoint + 3,
                'match_play' => $matchPlay + 1,
                'match_win' => $matchWin + 1
            ];
            $query->execute($parameters);
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to update match lose for rival team.");
        }
        try{
            $query = $this->db->prepare("UPDATE nsMasia 
                    SET match_play = :match_play, match_lose = :match_lose 
                    WHERE id = :id");
            $parameters = [
                'id' => $nsMasia->getId(),
                'match_play' => $matchPlayNs + 1,
                'match_lose' => $matchLoseNs + 1
            ];
            $query->execute($parameters);
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to update match lose for Ns Masia.");
        }
        }
    }

    public function changeResult(    
                                int $id,
                                int $score_nsMasia,
                                int $score_rivalTeam

                            ) : void {
    try{
        $query = $this->db->prepare("UPDATE result_match 
                                    SET score_nsMasia = :score_nsMasia, 
                                        score_rivalTeam = :score_rivalTeam 
                                    WHERE id = :id");

        $parameters = [
                        'id' => $id,
                        'score_nsMasia' => $score_nsMasia,
                        'score_rivalTeam' => $score_rivalTeam,
        ];
        $query->execute($parameters); 
        } catch (PDOException $e){
           error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to change result match.");
        }
    }

    public function getAllResultMatch() : array {  
        try{
            $query = $this->db->prepare("SELECT * FROM result_match");
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC); 
            
            $resultMatch = [];
            $matchManager = new MatchManager();

            foreach($result as $item){
                $match = $matchManager->getMatchsByIdPlay($item["match_id"]);

                $newResultMatch = new ResultMatch( $match, $item["score_nsMasia"], $item["score_rivalTeam"]);
                $newResultMatch->setId($item["id"]);
                $resultMatch[] = $item;
            }
            return $resultMatch; 
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch all result match.");
        }
    }

    public function getAllResultMatchByMatch(array $match) : array {  
        try{
            $query = $this->db->prepare("SELECT * FROM result_match");
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC); 

            $resultMatch = [];
            foreach($result as $item){
                $newResultMatch = new ResultMatch($match, $item["score_nsMasia"], $item["score_rivalTeam"]);
                $newResultMatch->setId($item["id"]);
                $resultMatch[] = $item;
            }
            return $resultMatch; 
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch all result match by match.");
        }
    }
}