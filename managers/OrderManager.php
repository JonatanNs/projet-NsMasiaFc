<?php 

class OrderManager extends AbstractManager{

    public function createOrder(Users $user, array $product, int $quantity, array $sizes, Addresses $addresses, array $prices) {
        $date = new DateTime();
        $formatted_date = $date->format('Y-m-d H:i:s');
    
        // Extraire l'identifiant unique du tableau $product
        
        for($i=0 ; $i<(int)$_POST["productLength"]; $i++){
            $price = $prices[$i];
            $product_id = $product[$i];
    
            $query = $this->db->prepare("INSERT INTO order_products (id, user_id, product_id, quantity, sizes, addresses_id, prices, date, total_prices) 
                                        VALUES (NULL, :user_id, :product_id, :quantity, :addresses_id, :prices, :date, :total_prices)");
            $parameters = [
                'user_id' => $user->getId(), 
                'product_id' => $product_id, 
                'quantity' => (int)$quantity, 
                'size' => $sizes,
                'addresses_id'=> $addresses->getId(), 
                'prices' => $price,
                'date' => $formatted_date,
                'total_prices' => (int)$_POST["totalPrices1"]
            ];
            $query->execute($parameters);
        }
    }
    
    

    public function createAddresses(Users $users){
        $query = $this->db->prepare("INSERT INTO addresses (id, user_id, street, streetNumber, complements, postal_code, city) 
                                    VALUES (NULL, :user_id, :street, :streetNumber, :complements, :postal_code, :city)");
        $parameters = [
            'user_id' => $users->getId(),
            'street' => $_POST["orderStreet"],	
            'streetNumber' => (int)$_POST["orderStreetNumber"],	
            'complements' => $_POST["orderComplements"], 
            'postal_code' => (int)$_POST["orderZipCode"], 
            'city' => $_POST["orderCity"]
        ];
        $query->execute($parameters);

        return $this->db->lastInsertId();
    }

    public function getAllAddressesById( int $users){
        $query = $this->db->prepare("SELECT * FROM addresses WHERE user_id = :user_id");
        $parameters = [
            'user_id' => $users
         ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result) {
            $addresses = new Addresses( $users , $result["street"], $result["streetNumber"], $result["complements"], $result["postal_code"], $result["city"]);
            $addresses->setId($result["id"]);
            return $addresses;
        }

        return null ;
    }
    
    
}


