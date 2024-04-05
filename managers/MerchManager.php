<?php

class MerchManager extends AbstractManager{

    /**********************************************************
                             * CREATE PRODUCT *
    **********************************************************/
    public function createProduct(string $name, string $img_url, string $img_alt, string $other_img_url, string $other_img_alt, string $descriptions, int $prices) : void {
        $query = $this->db->prepare("INSERT INTO products (id, name, img_url, img_alt, other_img_url, other_img_alt, descriptions, prices ) 
        VALUES (null, :name, :img_url, :img_alt, :other_img_url, :other_img_alt, :descriptions, :prices)");
        $parameters = [
            'name' => $name, 
            'img_url' => $img_url, 
            'img_alt' => $img_alt, 
            'other_img_url' => isset($other_img_url) ? $other_img_url : "", 
            'other_img_alt' => isset($other_img_alt) ? $other_img_alt : "", 
            'descriptions' => isset($descriptions) ? $descriptions : "", 
            'prices' => $prices
        ];
        $query->execute($parameters); 
    }
    
    /**********************************************************
                             * CHANGE PRODUCT *
    **********************************************************/
    public function changeProduct(int $id, string $name, string $img_url, string $img_alt, string $other_img_url, string $other_img_alt, string $descriptions, int $prices) : void {
        $query = $this->db->prepare("UPDATE products 
        SET name = :name, img_url = :img_url, img_alt = :img_alt, other_img_url = :other_img_url, 
                    other_img_alt = :other_img_alt, descriptions = :descriptions, prices = :prices 
        WHERE id = :id");

        $parameters = [
            'id' => $id,
            'name' => $name, 
            'img_url' => $img_url, 
            'img_alt' => $img_alt, 
            'other_img_url' => $other_img_url, 
            'other_img_alt' => $other_img_alt, 
            'descriptions' => $descriptions, 
            'prices' => $prices
        ];
        $query->execute($parameters); 
    }

    /**********************************************************
                             * REMOVE PRODUCT *
    **********************************************************/
    public function removeProduct(int $id) : void {
        $query = $this->db->prepare("DELETE FROM products WHERE id = :id");
        $parameters = [
            'id' =>  $id, 
        ];
        $query->execute($parameters); 
    }

    /**********************************************************
                             * FETCH *
    **********************************************************/

    public function getAllProducts() : array{
        $query = $this->db->prepare("SELECT * FROM products");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
        $products = [];
    
        foreach($result as $item){
            $newProduct = new Product(
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

    public function getAllProductsById(int $id) {
        $query = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $parameters = [
            'id' => $id
        ];
        $query->execute($parameters);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
        $products = [];
    
        foreach($result as $item){
            $newProduct = new Product(
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
            $newProduct = new Product(
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
