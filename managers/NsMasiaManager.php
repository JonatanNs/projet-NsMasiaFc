<?php

class NsMasiaManager extends AbstractManager{

    public function getNsMasia() : array {
        $query = $this->db->prepare("SELECT * FROM nsMasia");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $nsMasia = [];
        foreach($result as $item){
            $newMatch = new NsMasia($item["name"], $item["logo_url"], $item["logo_alt"]);
            $newMatch->setId($item["id"]);
            $newMatch->setRankingPoints($item["ranking_points"] ?? null);
            $newMatch->setMatchsPlay($item["match_play"] ?? null);
            $newMatch->setMatchsWin($item["match_win"] ?? null);
            $newMatch->setMatchsLose($item["match_lose"] ?? null);
            $newMatch->setMatchsNul($item["match_nul"] ?? null);
            $nsMasia[] = $item;
        }
        return $nsMasia ;
    }

}