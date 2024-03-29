<?php 

class RivalTeamManager extends AbstractManager{

    public function getAllRivalTeams() : array{
        $query = $this->db->prepare("SELECT * FROM rivalsTeam");

        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
        $team = [];
    
        foreach($result as $result){
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
    }

    public function getAllRivalTeamsByName(string $team) : ? RivalTeam {
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

        return null ;
    }


    public function getAllRivalTeamsById(int $id) : ? RivalTeam {
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
        return null ;
    }

    public function getAllTeams() : array{
        $query = $this->db->prepare("SELECT team, logo_url, logo_alt, id, ranking_points, match_play, match_win, match_lose, match_nul
        FROM rivalsTeam
        UNION
        SELECT name, logo_url, logo_alt, id, ranking_points, match_play, match_win, match_lose, match_nul
        FROM nsMasia
        ORDER BY ranking_points DESC;
        ");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
    }
}