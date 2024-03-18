<?php 

class MatchManager extends AbstractManager{

    public function createMatch( int $nsMasia, int $rivalTeam, string $domicileExterieur, string $date) {  

            $query = $this->db->prepare("INSERT INTO matchs (id, ns_masia_id, rivalTeam_id, domicileExterieur, date) 
                                        VALUES (null, :ns_masia_id, :rivalTeam_id, :domicileExterieur, :date)");
            $parameters = [ 
                'ns_masia_id' => $nsMasia,
                'rivalTeam_id' => $rivalTeam, 
                'domicileExterieur' => $domicileExterieur,  
                'date' => $date
            ];
            $query->execute($parameters);

    }
}