<?php 

class MatchManager extends AbstractManager{

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

    public function getAllMatchsById(int $id) {  
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


    public function getAllTickets() {  

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

}