<?php

class MerchManager extends AbstractManager{

    public function getAllProducts() : array{
        $query = $this->db->prepare("SELECT * FROM products");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
        $products = [];
    
        foreach($result as $item){
            $newProduct = new Products(
                $item["name"], 
                $item["img_url"], 
                $item["img_alt"], 
                $item["descriptions"], 
                $item["prices"],
                $item["other_img_url"],
                $item["other_img_alt"],
            );
            $newProduct->setId($item["id"]);
            $products[] = $item;
        }
        return $products;
    }
}
