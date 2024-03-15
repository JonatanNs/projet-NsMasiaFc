<?php 

class RivalTeamManager extends AbstractManager{

    public function getAllRivalTeams() : array{
        $query = $this->db->prepare("SELECT * FROM rivalsTeam");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
        $products = [];
    
        foreach($result as $item){
            $newProduct = new Products(
                $item["name"], 
                $item["img_url"], 
                $item["img_alt"], 
                $item["prices"]
            );
            $newProduct->setId($item["id"]);
            $newProduct->setDescriptions($item["descriptions"] ?? '');
            $newProduct->setOtherImgUrl($item["other_img_url"] ?? '');
            $newProduct->setOtherImgAlt($item["other_img_alt"] ?? '');
            $products[] = $item;
        }
        return $products;
    }
}