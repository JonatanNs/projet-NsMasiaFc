<?php

class NsMasiaManager extends AbstractManager{

    /**********************************************************
                             * CREATE PLAYER *
    **********************************************************/
    public function createPlayer(PlayerNsMasia $playerNsMasia ) : void {
        try{
            $query = $this->db->prepare("INSERT INTO playersNsMasia (id, first_name, last_name, name_jersay, number, position, img) 
            VALUES (null, :first_name, :last_name, :name_jersay, :number, :position, :img)");
            $parameters = [
                'first_name' => $playerNsMasia->getFirstName(), 
                'last_name' => $playerNsMasia->getLastName(),  
                'name_jersay' => $playerNsMasia->getNameJersay(), 
                'number' => $playerNsMasia->getNumber(),  
                'position' => $playerNsMasia->getPosition(), 
                'img' => $playerNsMasia->getImg() 
            ];
            $query->execute($parameters); 
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to create player.");
        }
    }

    /**********************************************************
                             * UPDATE NS MASIA FC *
    **********************************************************/

    public function updateNsMasiaName(int $id, string $name) : void {
        try {
            $query = $this->db->prepare("UPDATE nsMasia SET name = :name WHERE id = :id");
            $parameters = [
                'id' => $id, 
                'name' => $name, 
            ];
            $query->execute($parameters);
        } catch (PDOException $e) {
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to update name.");
        }
    }

    public function updateNsMasiaLogo( 
                                            int $id, 
                                            string $logo_url,
                                            string $logo_alt
                                        ) : void{
        try {
            $query = $this->db->prepare("   UPDATE nsMasia 
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
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to update logo.");
        }
    }

    public function updateNsMasiaEmail(int $id, string $email, string $passwordEmail) : void {
        try {
            $query = $this->db->prepare("UPDATE nsMasia 
                                         SET email = :email, 
                                         passwordEmail = :passwordEmail 
                                         WHERE id = :id");
            $parameters = [
                'id' => $id, 
                'email' => $email, 
                'passwordEmail' => $passwordEmail
            ];
            $query->execute($parameters);
        } catch (PDOException $e) {

            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to update NsMasia email and password.");
        }
    }

    public function updatebannerEmail(int $id, string $bannerEmail) : void {
        try {
            $query = $this->db->prepare("UPDATE nsMasia 
                        SET bannerEmail = :bannerEmail
                        WHERE id = :id"
            );
            $parameters = [
                'id' => $id,  
                'bannerEmail' => $bannerEmail
            ];
            $query->execute($parameters);
        } catch (PDOException $e) {
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to update banner email.");
        }
    }
    
    /**********************************************************
                             * CHANGE PLAYER *
    **********************************************************/
    public function changePlayer(
                                    int $id, 
                                    string $first_name, 
                                    string $last_name, 
                                    string $name_jersay, 
                                    int $number, 
                                    string $position
                                ) : void {
        try{
            $query = $this->db->prepare("UPDATE playersNsMasia 
                                        SET first_name = :first_name, 
                                            last_name = :last_name, 
                                            name_jersay = :name_jersay, 
                                            number = :number, 
                                            position = :position 
                                        WHERE id = :id"
                                        );

            $parameters = [
                'id' => $id,
                'first_name' =>$first_name, 
                'last_name' => $last_name, 
                'name_jersay' => $name_jersay, 
                'number' => $number, 
                'position' => $position
            ];
            $query->execute($parameters); 
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to change player.");
        }
    }

    public function changeNamePlayer(int $id, 
                                     string $first_name, 
                                     string $last_name) : void {
        try{
            $query = $this->db->prepare("UPDATE playersNsMasia 
                                        SET first_name = :first_name, last_name = :last_name 
                                        WHERE id = :id");

            $parameters = [
                'id' => $id,
                'first_name' =>$first_name, 
                'last_name' => $last_name
            ];
            $query->execute($parameters); 
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to change name player.");
        }
    }

    public function changeNameJerseyPlayer(int $id, string $name_jersay) : void {
        try{
            $query = $this->db->prepare("UPDATE playersNsMasia 
                                         SET name_jersay = :name_jersay 
                                         WHERE id = :id");

            $parameters = [
                'id' => $id,
                'name_jersay' => $name_jersay
            ];
            $query->execute($parameters); 
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to change name jersey player.");
        }
    }

    public function changeNumberPlayer(int $id, int $number) : void {
        try{
            $query = $this->db->prepare("UPDATE playersNsMasia 
                                         SET number = :number 
                                         WHERE id = :id");

            $parameters = [
                'id' => $id,
                'number' => $number
            ];
            $query->execute($parameters);
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to change number player.");
        } 
    }

    public function changePositionPlayer(int $id, string $position) : void {
        try{
            $query = $this->db->prepare("UPDATE playersNsMasia 
                                         SET position = :position 
                                         WHERE id = :id");

            $parameters = [
                'id' => $id, 
                'position' => $position
            ];
            $query->execute($parameters); 
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to change position player.");
        }
    }

    /**********************************************************
                             * REMOVE PLAYER *
    **********************************************************/
    public function removePlayer(int $id) : void {
        try{
            $query = $this->db->prepare("DELETE FROM playersNsMasia WHERE id = :id");
            $parameters = [
                'id' =>  $id, 
            ];
            $query->execute($parameters); 
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to remove player.");
        }
    }

    /**********************************************************
                             * FETCH *
    **********************************************************/

    public function getNsMasia() : NsMasia {
        try{
            $query = $this->db->prepare("SELECT * FROM nsMasia");
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach($result as $item){
                $newMatch = new NsMasia(
                                            $item["name"], 
                                            $item["logo_url"], 
                                            $item["logo_alt"], 
                                            $item["email"], 
                                            $item["passwordEmail"], 
                                            $item["stadium"] 
                                        );
                $newMatch->setId($item["id"]);
                $newMatch->setBannerEmail($item["bannerEmail"] ?? null);
                $newMatch->setRankingPoints($item["ranking_points"] ?? null);
                $newMatch->setMatchsPlay($item["match_play"] ?? null);
                $newMatch->setMatchsWin($item["match_win"] ?? null);
                $newMatch->setMatchsLose($item["match_lose"] ?? null);
                $newMatch->setMatchsNul($item["match_nul"] ?? null);
            }
            return $newMatch;
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch player Ns Masia.");
        }
    }

    public function getNsMasiaById(int $id) : NsMasia {
        try{
            $query = $this->db->prepare("SELECT * FROM nsMasia WHERE id = :id");
            $parameters = [
                'id' => $id
            ];

            $query->execute($parameters);
            $item = $query->fetch(PDO::FETCH_ASSOC);

            if($item){
                $newMatch = new NsMasia(
                                            $item["name"], 
                                            $item["logo_url"], 
                                            $item["logo_alt"], 
                                            $item["email"], 
                                            $item["passwordEmail"], 
                                            $item["stadium"] 
                                        );
                $newMatch->setId($item["id"]);
                $newMatch->setBannerEmail($item["bannerEmail"] ?? null);
                $newMatch->setRankingPoints($item["ranking_points"] ?? null);
                $newMatch->setMatchsPlay($item["match_play"] ?? null);
                $newMatch->setMatchsWin($item["match_win"] ?? null);
                $newMatch->setMatchsLose($item["match_lose"] ?? null);
                $newMatch->setMatchsNul($item["match_nul"] ?? null);
            }
            return $newMatch ;
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch player Ns Masia by id.");
        }
    }

    /**********************************
        * FETCH PLAYER NS MASIA *
    *********************************/

    public function getPlayerNsMasia() : array{
        try{
            $query = $this->db->prepare("SELECT * FROM playersNsMasia");
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            $players = [];

            foreach($result as $item){
                $newPlayer = new PlayerNsMasia(
                                                $item["first_name"], 
                                                $item["last_name"], 
                                                $item["name_jersay"], 
                                                $item["number"], 
                                                $item["position"],
                                                $item["img"]
                                            );
                $newPlayer->setId($item["id"]);
                $players[] = $item;
            }
            return $players;
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch player Ns Masia.");
        }
    }

    public function isJerseyNumberExists($number): bool {
        try{
            $query = $this->db->prepare("SELECT number FROM playersNsMasia WHERE number = :number");
            $parameters = [
                'number' => $number
            ];
            $query->execute($parameters);
            $count = $query->fetchColumn();
            
            return $count > 0;
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch player Ns Masia by id.");
        }
    }
    
    
    public function getPlayerNsMasiaById(int $id) : ? PlayerNsMasia{
        try{
            $query = $this->db->prepare("SELECT * FROM playersNsMasia WHERE id = :id");
            $parameters = [
                'id' => $id
            ];
            $query->execute($parameters);
            $result = $query->fetch(PDO::FETCH_ASSOC);

            if($result){
                $newPlayer = new PlayerNsMasia(
                                                $result["first_name"], 
                                                $result["last_name"], 
                                                $result["name_jersay"], 
                                                $result["number"], 
                                                $result["position"],
                                                $result["img"]  
                                            );
                $newPlayer->setId($result["id"]);
            }
            return $newPlayer;
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch player Ns Masia by id.");
        }
    }
}