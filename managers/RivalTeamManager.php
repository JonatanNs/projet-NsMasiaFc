<?php 

class RivalTeamManager extends AbstractManager{

    /**********************************************************
                             * CREATE *
    **********************************************************/
    public function createRivalTeam(RivalTeam $rivalTeam) : int {
        try {
            $query = $this->db->prepare("INSERT INTO rivalsTeam (team, 
                                                                logo_url, 
                                                                logo_alt, 
                                                                ranking_points,
                                                                match_play,
                                                                match_win,
                                                                match_lose,
                                                                match_nul) 
                                        VALUES (:team, 
                                                :logo_url, 
                                                :logo_alt, 
                                                :ranking_points,
                                                :match_play,
                                                :match_win,
                                                :match_lose,
                                                :match_nul)");
            $parameters = [
                'team' => $rivalTeam->getTeam(), 
                'logo_url' => $rivalTeam->getLogoUrl(), 
                'logo_alt' => $rivalTeam->getLogoAlt(), 
                'ranking_points' => $rivalTeam->getRankingPoints(), 
                'match_play' => $rivalTeam->getMatchsPlay(),
                'match_win' => $rivalTeam->getMatchsWin(),
                'match_lose' => $rivalTeam->getMatchsLose(),
                'match_nul' => $rivalTeam->getMatchsNul()
            ];
    
            $query->execute($parameters);
    
            // Retourner le dernier ID inséré
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to create rival team.");
        }
    }
    

    public function createLocation(Location $location) : void {
        try {
            $query = $this->db->prepare("INSERT INTO locations (
                                            rivalTeam_id,
                                            stadium,
                                            city
                                        ) 
                                        VALUES (
                                            :rivalTeam_id,  
                                            :stadium,  
                                            :city)");
            $parameters = [
                'rivalTeam_id' => $location->getRivalTeamId()->getId(), 
                'stadium' => $location->getStadium(), 
                'city' => $location->getCity()
            ];
    
            $query->execute($parameters);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to create location.");
        }
    }
    

    /**********************************************************
                             * UPDATE *
    **********************************************************/

    public function checkChangeStatusRivalTeam(
                                                int $id, 
                                                string $status, 
                                            ) : void {
        try{
            $query = $this->db->prepare("UPDATE rivalsTeam
                                        SET status = :status
                                        WHERE id = :id");
            $parameters = [
                'id' => $id,
                'status' => $status
            ];
            $query->execute($parameters);
        } catch (PDOException $e) {
            // Log the error or handle it as necessary
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to change status rival team in the database.");
        }
    }
    public function checkChangeNameRivalTeam(
                                            int $id, 
                                            string $team, 
                                        ) : void {
        try{
            $query = $this->db->prepare("UPDATE rivalsTeam
                                        SET team = :team
                                        WHERE id = :id");
            $parameters = [
                'id' => $id,
                'team' => $team
            ];
            $query->execute($parameters);
        } catch (PDOException $e) {
            // Log the error or handle it as necessary
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to change name rival team in the database.");
        }
    }
    public function checkChangeLogoRivalTeam(
                                                int $id, 
                                                string $logo_url, 
                                                string $logo_alt
                                            ) : void {
        try{
            $query = $this->db->prepare("UPDATE rivalsTeam
                                        SET logo_url = :logo_url,
                                            logo_alt = :logo_alt
                                        WHERE id = :id");
            $parameters = [
                'id' => $id,
                'logo_url' => $logo_url,
                'logo_alt' => $logo_alt
            ];
            $query->execute($parameters);
        } catch (PDOException $e) {
            // Log the error or handle it as necessary
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to change logo rival team in the database.");
        }
    }

    public function checkChangePointRivalTeam(
                                                int $id, 
                                                int $rankings_point, 
                                                int $match_play, 
                                                int $match_win,
                                                int $match_lose,
                                                int $match_nul

                                            ) : void {
    try{
        $query = $this->db->prepare("UPDATE rivalsTeam
                                    SET ranking_points = :ranking_points,
                                        match_play = :match_play,
                                        match_win = :match_win,
                                        match_lose = :match_lose,
                                        match_nul = :match_nul
                                    WHERE id = :id");
        $parameters = [
                        'id' => $id,
                        'ranking_points' => $rankings_point, 
                        'match_play' => $match_play,
                        'match_win' => $match_win,
                        'match_lose' => $match_lose,
                        'match_nul' => $match_nul
                    ];

        $query->execute($parameters);

    } catch (PDOException $e) {
    //Log the error or handle it as necessary
        error_log("Database error : " . $e->getMessage());
        throw new Exception("Failed to change rival team in the database.");
    }
    }

    /**********************************************************
                             * DELETE *
    **********************************************************/

    public function removeRivalTeam(int $id) : bool {
        try {
            // Prepare the SQL DELETE statement
            $query = $this->db->prepare("DELETE FROM rivalsTeam WHERE id = :id");
            $parameters = ['id' => $id];
            
            // Execute the DELETE statement with the given parameters
            $query->execute($parameters);
            
            // Return true if any rows were deleted, otherwise false
            return $query->rowCount() > 0;
        } catch (PDOException $e) {
            // Log the database error and return false
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
    
    

    /**********************************************************
                             * FETCH *
    **********************************************************/

    public function getAllRivalTeams() : array{
        try{
            $query = $this->db->prepare("SELECT * FROM rivalsTeam");

            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
        
            $team = [];
        
            foreach($results as $result){
                $newTeam = new RivalTeam(
                    $result["team"], 
                    $result["logo_url"], 
                    $result["logo_alt"]
                );
                $newTeam->setId($result["id"]);
                $newTeam->setRankingPoints($result["ranking_points"] ?? null);
                $newTeam->setMatchsPlay($result["match_play"] ?? null);
                $newTeam->setMatchsWin($result["match_win"] ?? null);
                $newTeam->setMatchsLose($result["match_lose"] ?? null);
                $newTeam->setMatchsNul($result["match_nul"] ?? null);
                $team[] = $result;
            }
            return $team;
        } catch (PDOException $e) {
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch all rival team.");
        }
    }

    public function getAllRivalTeamsByName(string $team) : ? RivalTeam {
        try{
            $query = $this->db->prepare("SELECT * FROM rivalsTeam WHERE team = :team");
            $parameters = [
                'team' => $team
            ];
            $query->execute($parameters);
            $result = $query->fetch(PDO::FETCH_ASSOC);
            
            if($result) {
                $newTeam = new RivalTeam($result["team"], $result["logo_url"], $result["logo_alt"]);
                $newTeam->setId($result["id"]);
                $newTeam->setRankingPoints($result["ranking_points"] ?? null);
                $newTeam->setMatchsPlay($result["match_play"] ?? null);
                $newTeam->setMatchsWin($result["match_win"] ?? null);
                $newTeam->setMatchsLose($result["match_lose"] ?? null);
                $newTeam->setMatchsNul($result["match_nul"] ?? null);
                return $newTeam;
            }
            return null;
        } catch (PDOException $e) {
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch all rival team by name.");
        }
    }


    public function getAllRivalTeamsById(int $id) : ? RivalTeam {
        try{
            $query = $this->db->prepare("SELECT * FROM rivalsTeam WHERE id = :id");
            $parameters = [
                'id' => $id
            ];
            $query->execute($parameters);
            $result = $query->fetch(PDO::FETCH_ASSOC);
            
            if($result) {
                $newTeam = new RivalTeam($result["team"], $result["logo_url"], $result["logo_alt"]);
                $newTeam->setId($result["id"]);
                $newTeam->setRankingPoints($result["ranking_points"] ?? null);
                $newTeam->setMatchsPlay($result["match_play"] ?? null);
                $newTeam->setMatchsWin($result["match_win"] ?? null);
                $newTeam->setMatchsLose($result["match_lose"] ?? null);
                $newTeam->setMatchsNul($result["match_nul"] ?? null);
                return $newTeam;
            }
            return null;
        } catch (PDOException $e) {
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch all rival team by id.");
        }
    }

    public function getAllTeams() : array {
        try {
            // Préparation de la requête SQL
            $query = $this->db->prepare("
                SELECT team, logo_url, logo_alt, status, id, ranking_points, match_play, match_win, match_lose, match_nul
                FROM rivalsTeam
                UNION
                SELECT name AS team, logo_url, logo_alt, 'ACTIF' AS status, id, ranking_points, match_play, match_win, match_lose, match_nul
                FROM nsMasia
                ORDER BY ranking_points DESC;
            ");
            
            // Exécution de la requête
            $query->execute();
            
            // Récupération des résultats
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            
            return $result;
        } catch (PDOException $e) {
            // Journalisation de l'erreur et levée d'une exception
            error_log("Erreur de base de données : " . $e->getMessage());
            throw new Exception("Échec de la récupération de toutes les équipes.");
        }
    }
    
}