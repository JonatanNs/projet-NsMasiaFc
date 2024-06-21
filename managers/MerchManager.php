<?php

class MerchManager extends AbstractManager{

    /**********************************************************
                             * CREATE PRODUCT *
    **********************************************************/
    public function createProduct( Product $product ) : void {
        try{
            $query = $this->db->prepare("INSERT INTO products (
                                                                id, 
                                                                name, 
                                                                img_url, 
                                                                img_alt, 
                                                                other_img_url, 
                                                                other_img_alt, 
                                                                descriptions, 
                                                                prices
                                                            ) 
                                        VALUES (null, 
                                        :name, 
                                        :img_url, 
                                        :img_alt, 
                                        :other_img_url, 
                                        :other_img_alt, 
                                        :descriptions, 
                                        :prices)"
                                        );
            $parameters = [
                'name' => $product->getName(), 
                'img_url' => $product->getImgUrl(), 
                'img_alt' => $product->getImgAlt(), 
                'other_img_url' => $product->getOtherImgUrl(), 
                'other_img_alt' => $product->getOtherImgAlt(), 
                'descriptions' =>  $product->getDescriptions(),
                'prices' => $product->getPrices()
            ];
            $query->execute($parameters);
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to create product.");
        } 
    }
    
    /**********************************************************
                             * CHANGE PRODUCT *
    **********************************************************/
    public function changeProduct(
                                    int $id, 
                                    string $name, 
                                    string $img_url, 
                                    string $img_alt, 
                                    string $other_img_url, 
                                    string $other_img_alt, 
                                    string $descriptions, 
                                    int $prices
                                ) : void {
        try{
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
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to change product.");
        }
    }

    public function changeProductStatus(
                                            int $id, 
                                            string $status
                                        ) : void {
    try{
        $query = $this->db->prepare("UPDATE products 
                                     SET status = :status 
                                     WHERE id = :id");

        $parameters = [
            'id' => $id,
            'status' => $status
        ];
        $query->execute($parameters); 
    } catch (PDOException $e){
        error_log("Database error : " . $e->getMessage());
        throw new Exception("Failed to change product.");
    }
}

    /**********************************************************
                             * REMOVE PRODUCT *
    **********************************************************/
    public function removeProduct(int $id) : bool {
        try{
            $query = $this->db->prepare("DELETE FROM products WHERE id = :id");
            $parameters = [
                'id' =>  $id
            ];
            $query->execute($parameters);

            return $query->rowCount() > 0;

        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            return false;
        } 
    }

    /**********************************************************
                             * FETCH *
    **********************************************************/

    public function getAllProducts() : array{
        try{
            $query = $this->db->prepare("SELECT * FROM products");
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
        
            $products = [];
        
            foreach($result as $item){
                $newProduct = new Product(
                    $item["name"], 
                    $item["img_url"], 
                    $item["img_alt"], 
                    $item["descriptions"],
                    $item["prices"]
                );
                $newProduct->setId($item["id"]);
                $newProduct->setOtherImgUrl($item["other_img_url"] ?? '');
                $newProduct->setOtherImgAlt($item["other_img_alt"] ?? '');
                $products[] = $item;
            }
            return $products;
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch all product.");
        }
    }

    public function getProductsById(int $id) : ? Product{
        try{
            $query = $this->db->prepare("SELECT * FROM products WHERE id = :id");
            $parameters = [
                'id' => $id
            ];
            $query->execute($parameters);
            $item = $query->fetch(PDO::FETCH_ASSOC);
        
            if($item){
                $newProduct = new Product(
                    $item["name"], 
                    $item["img_url"], 
                    $item["img_alt"],
                    $item["descriptions"],
                    $item["prices"]
                );
                $newProduct->setId($item["id"]);
                $newProduct->setOtherImgUrl($item["other_img_url"] ?? '');
                $newProduct->setOtherImgAlt($item["other_img_alt"] ?? '');
                return $newProduct;
            }
            return null;

        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch all product by id.");
        }
    }

    public function getAllProductsByName(string $name) : array{
        try{
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
                    $item["descriptions"],
                    $item["prices"]
                );
                $newProduct->setId($item["id"]);
                $newProduct->setOtherImgUrl($item["other_img_url"] ?? '');
                $newProduct->setOtherImgAlt($item["other_img_alt"] ?? '');
                $products[] = $item;
            }
            return $products;
        } catch (PDOException $e){
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch all product by name.");
        }
    }   
}
