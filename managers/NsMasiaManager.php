<?php

class NsMasiaManager extends AbstractManager{

    /**********************************************************
                             * CREATE PLAYER *
    **********************************************************/
    public function createPlayer(string $first_name, string $last_name,  $name_jersay, int $number, string $position) : void {
        $query = $this->db->prepare("INSERT INTO playersNsMasia (id, first_name, last_name, name_jersay, number, position) 
        VALUES (null, :first_name, :last_name, :name_jersay, :number, :position)");
        $parameters = [
            'first_name' =>$first_name, 
            'last_name' => $last_name, 
            'name_jersay' => $name_jersay, 
            'number' => $number, 
            'position' => $position
        ];
        $query->execute($parameters); 
    }
    
    /**********************************************************
                             * CHANGE PLAYER *
    **********************************************************/
    public function changePlayer(int $id, string $first_name, string $last_name, string $name_jersay, int $number, string $position) : void {
        $query = $this->db->prepare("UPDATE playersNsMasia SET first_name = :first_name, last_name = :last_name, name_jersay = :name_jersay, number = :number, position = :position WHERE id = :id");

        $parameters = [
            'id' => $id,
            'first_name' =>$first_name, 
            'last_name' => $last_name, 
            'name_jersay' => $name_jersay, 
            'number' => $number, 
            'position' => $position
        ];
        $query->execute($parameters); 
    }

    public function changeNamePlayer(int $id, string $first_name, string $last_name) : void {
        $query = $this->db->prepare("UPDATE playersNsMasia SET first_name = :first_name, last_name = :last_name WHERE id = :id");

        $parameters = [
            'id' => $id,
            'first_name' =>$first_name, 
            'last_name' => $last_name
        ];
        $query->execute($parameters); 
    }

    public function changeNameJerseyPlayer(int $id, string $name_jersay) : void {
        $query = $this->db->prepare("UPDATE playersNsMasia SET name_jersay = :name_jersay WHERE id = :id");

        $parameters = [
            'id' => $id,
            'name_jersay' => $name_jersay
        ];
        $query->execute($parameters); 
    }

    public function changeNumberPlayer(int $id, int $number) : void {
        $query = $this->db->prepare("UPDATE playersNsMasia SET number = :number WHERE id = :id");

        $parameters = [
            'id' => $id,
            'number' => $number
        ];
        $query->execute($parameters); 
    }

    public function changePositionPlayer(int $id, string $position) : void {
        $query = $this->db->prepare("UPDATE playersNsMasia SET position = :position WHERE id = :id");

        $parameters = [
            'id' => $id, 
            'position' => $position
        ];
        $query->execute($parameters); 
    }

    /**********************************************************
                             * REMOVE PLAYER *
    **********************************************************/
    public function removePlayer(int $id) : void {
        $query = $this->db->prepare("DELETE FROM playersNsMasia WHERE id = :id");
        $parameters = [
            'id' =>  $id, 
        ];
        $query->execute($parameters); 
    }

    /**********************************************************
                             * FETCH *
    **********************************************************/

    public function getNsMasia() : NsMasia {
        $query = $this->db->prepare("SELECT * FROM nsMasia");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach($result as $item){
            $newMatch = new NsMasia($item["name"], $item["logo_url"], $item["logo_alt"], $item["email"], $item["stadium"] );
            $newMatch->setId($item["id"]);
            $newMatch->setRankingPoints($item["ranking_points"] ?? null);
            $newMatch->setMatchsPlay($item["match_play"] ?? null);
            $newMatch->setMatchsWin($item["match_win"] ?? null);
            $newMatch->setMatchsLose($item["match_lose"] ?? null);
            $newMatch->setMatchsNul($item["match_nul"] ?? null);
        }
        return $newMatch ;
    }

    public function getNsMasiaById(int $id) : NsMasia {
        $query = $this->db->prepare("SELECT * FROM nsMasia WHERE id = :id");
        $parameters = [
            'id' => $id
        ];

        $query->execute($parameters);
        $item = $query->fetch(PDO::FETCH_ASSOC);

        if($item){
            $newMatch = new NsMasia($item["name"], $item["logo_url"], $item["logo_alt"], $item["email"], $item["stadium"] );
            $newMatch->setId($item["id"]);
            $newMatch->setRankingPoints($item["ranking_points"] ?? null);
            $newMatch->setMatchsPlay($item["match_play"] ?? null);
            $newMatch->setMatchsWin($item["match_win"] ?? null);
            $newMatch->setMatchsLose($item["match_lose"] ?? null);
            $newMatch->setMatchsNul($item["match_nul"] ?? null);
        }
        return $newMatch ;
    }

    /**********************************
        * FETCH PLAYER NS MASIA *
    *********************************/

    public function getPlayerNsMasia() : array{
        $query = $this->db->prepare("SELECT * FROM playersNsMasia");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $players = [];

        foreach($result as $item){
            $newPlayer = new PlayerNsMasia($item["first_name"], $item["last_name"], $item["name_jersay"], $item["number"], $item["position"] );
            $newPlayer->setId($item["id"]);
            $players[] = $item;
        }
        return $players ;
    }

    public function isJerseyNumberExists($number): bool {
        $query = $this->db->prepare("SELECT number FROM playersNsMasia WHERE number = :number");
        $query->bindParam(':number', $number, PDO::PARAM_INT);
        $query->execute();
        $count = $query->fetchColumn();
        
        return $count > 0;
    }
    
    
    public function getPlayerNsMasiaById(int $id) : ? PlayerNsMasia{
        $query = $this->db->prepare("SELECT * FROM playersNsMasia WHERE id = :id");
        $parameters = [
            'id' => $id
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result){
            $newPlayer = new PlayerNsMasia($result["first_name"], $result["last_name"], $result["name_jersay"], $result["number"], $result["position"] );
            $newPlayer->setId($result["id"]);
        }
        return $newPlayer ;
    }
}