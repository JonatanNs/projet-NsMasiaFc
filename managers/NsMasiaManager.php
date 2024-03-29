<?php

class NsMasiaManager extends AbstractManager{

    /**********************************************************
                             * FETCH *
    **********************************************************/

    public function getNsMasia() : NsMasia {
        $query = $this->db->prepare("SELECT * FROM nsMasia");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach($result as $item){
            $newMatch = new NsMasia($item["name"], $item["logo_url"], $item["logo_alt"], $item["stadium"] );
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
            $newMatch = new NsMasia($item["name"], $item["logo_url"], $item["logo_alt"], $item["stadium"] );
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
            $newPlayer = new PlayerNsMasia($item["first_name"], $item["last_name"], $item["name_jersay"], $item["number"] );
            $newPlayer->setId($item["id"]);
            $players[] = $item;
        }
        return $players ;
    }

    public function getPlayerNsMasiaById(int $id) : ? PlayerNsMasia{
        $query = $this->db->prepare("SELECT * FROM playersNsMasia WHERE id = :id");
        $parameters = [
            'id' => $id
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result){
            $newPlayer = new PlayerNsMasia($result["first_name"], $result["last_name"], $result["name_jersay"], $result["number"] );
            $newPlayer->setId($result["id"]);
        }
        return $newPlayer ;
    }
}