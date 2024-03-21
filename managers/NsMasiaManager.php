<?php

class NsMasiaManager extends AbstractManager{

    public function getNsMasia() : NsMasia {
        $query = $this->db->prepare("SELECT * FROM nsMasia");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $nsMasia = [];
        foreach($result as $item){
            $newMatch = new NsMasia($item["name"], $item["logo_url"], $item["logo_alt"], $item["stadium"] );
            $newMatch->setId($item["id"]);
            $newMatch->setRankingPoints($item["ranking_points"] ?? null);
            $newMatch->setMatchsPlay($item["match_play"] ?? null);
            $newMatch->setMatchsWin($item["match_win"] ?? null);
            $newMatch->setMatchsLose($item["match_lose"] ?? null);
            $newMatch->setMatchsNul($item["match_nul"] ?? null);
            $nsMasia[] = $item;
        }
        return $newMatch ;
    }

    public function getNsMasiaById(int $id) : NsMasia{
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
}