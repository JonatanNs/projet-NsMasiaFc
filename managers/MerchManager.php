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

    public function getAllProductsById(int $id) : array{
        $query = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $parameters = [
            'id' => $id
        ];
        $query->execute($parameters);
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

    public function getAllProductsByName(string $name) : array{
        $query = $this->db->prepare("SELECT * FROM products WHERE name = :name");
        $parameters = [
            'name' => $name
        ];
        $query->execute($parameters);
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
