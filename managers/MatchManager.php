<?php 

class MatchManager extends AbstractManager{

    public function createMatch(int $nsMasia, int $rivalTeam, string $domicileExterieur, string $time, string $date) {  
        // Insérer le nouveau match
        $query = $this->db->prepare("INSERT INTO matchs (id, ns_masia_id, rivalTeam_id, domicileExterieur, time, date) 
                                            VALUES (null, :ns_masia_id, :rivalTeam_id, :domicileExterieur, :time, :date)");
        $parameters = [ 
            'ns_masia_id' => $nsMasia,
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
                                    END AS stadium_name
                                        FROM matchs 
                                        JOIN nsMasia ON nsMasia.id = matchs.ns_masia_id 
                                        JOIN rivalsTeam ON rivalsTeam.id = matchs.rivalTeam_id 
                                        LEFT JOIN locations ON locations.rivalTeam_id = rivalsTeam.id
                                        LEFT JOIN match_location ON match_location.location_id = locations.id AND match_location.match_id = matchs.id
                                        WHERE matchs.date >= CURRENT_DATE
                                        ORDER BY matchs.date ASC ");
        $query->execute();
        $matchs = $query->fetchAll(PDO::FETCH_ASSOC);
    
        return $matchs; 
    }
    
    public function getAllMatchsById(int $id) {  

        $query = $this->db->prepare("SELECT matchs.id AS match_id, matchs.*, nsMasia.*, rivalsTeam.*, 
                                    CASE 
                                        WHEN matchs.domicileExterieur = 'domicile' THEN nsMasia.stadium
                                        WHEN matchs.domicileExterieur = 'exterieur' THEN locations.stadium
                                    END AS stadium_name
                                        FROM matchs 
                                        JOIN nsMasia ON nsMasia.id = matchs.ns_masia_id 
                                        JOIN rivalsTeam ON rivalsTeam.id = matchs.rivalTeam_id 
                                        LEFT JOIN locations ON locations.rivalTeam_id = rivalsTeam.id
                                        LEFT JOIN match_location ON match_location.location_id = locations.id AND match_location.match_id = matchs.id
                                    WHERE matchs.id = :id");
        $parameters = [
            'id' => $id
        ];
        $query->execute($parameters);
        $matchs = $query->fetchAll(PDO::FETCH_ASSOC);
    
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


    

}