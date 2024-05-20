<?php 

class OrderManager extends AbstractManager{

    /**********************************************************
                             * CREATE *
    **********************************************************/

    /***************************
        * CREATE ORDER PRODUCT *
    ****************************/

    public function createOrderFromProduct(
                                            string $numberOrder, 
                                            int $products, 
                                            int $quantities, 
                                            string $sizes, 
                                            int $totalPrices
                                            
                                            ) : int{  

        $date = new DateTime();
        $formatted_date = $date->format('Y-m-d H:i:s');
            $query = $this->db->prepare("INSERT INTO order_products (
                                                                    id, 
                                                                    order_number, 
                                                                    product_id, 
                                                                    quantity, 
                                                                    sizes, 
                                                                    date, 
                                                                    total_prices
                                                                    ) 
                                        VALUES (
                                                null, 
                                                :order_number, 
                                                :product_id, 
                                                :quantity, 
                                                :sizes, 
                                                :date, 
                                                :total_prices)"
                                        );
            $parameters = [ 
                'order_number' => $numberOrder,
                'product_id' => $products, 
                'quantity' => $quantities, 
                'sizes' => $sizes, 
                'date' => $formatted_date,
                'total_prices' => $totalPrices
            ];
            $query->execute($parameters);

            return $this->db->lastInsertId();
    }

    public function createOrder( string $numberOrder, Addresse $addresses, int $totalTtc) : void {  
        $date = new DateTime();
        $formatted_date = $date->format('Y-m-d H:i:s');
        
            $query = $this->db->prepare("INSERT INTO orders (id, order_number, addresse_id, date, totalTtc) 
                                        VALUES (null, :order_number, :addresse_id, :date, :totalTtc)");
            $parameters = [ 
                'order_number' => $numberOrder, 
                'addresse_id'=> $addresses->getId(), 
                'date' => $formatted_date,
                'totalTtc' => $totalTtc
            ];
            $query->execute($parameters);
    }

    /***************************
        * CREATE ADDRESS *
    ****************************/

    public function createAddresses(
                                        User $users, 
                                        string $address, 
                                        int $postal_code,
                                        string $city, 
                                        string $pays, 
                                        ? string $completement 

                                    ) : int {

        $query = $this->db->prepare("INSERT INTO addresses (
                                                                id,
                                                                user_id, 
                                                                addresse, 
                                                                complements, 
                                                                postal_code, 
                                                                city, 
                                                                pays
                                                            ) 
                                    VALUES (
                                                NULL, 
                                                :user_id, 
                                                :addresse, 
                                                :complements, 
                                                :postal_code, 
                                                :city, 
                                                :pays
                                            )"
                                    );
        $parameters = [
            'user_id' => $users->getId(),
            'addresse' => $address,		
            'complements' => isset($completement) ? $completement : "", 
            'postal_code' => $postal_code, 
            'city' => $city,
            'pays' => $pays
        ];
        $query->execute($parameters);

        return $this->db->lastInsertId();
    }

    /***************************
        * CREATE TICKET *
    ****************************/

    public function createOrderTicket(
                                        string $numberOrder, 
                                        User $user, 
                                        int $ticket_id, 
                                        int $match_id, 
                                        int $quantity, 
                                        int $totalPrices

                                    ) : void{

        $date = new DateTime();
        $formatted_date = $date->format('Y-m-d H:i:s');
        $query = $this->db->prepare("INSERT INTO order_tickets (
                                                                id, 
                                                                order_number, 
                                                                user_id, 
                                                                ticket_id,
                                                                match_id, 
                                                                quantity, 
                                                                date, 
                                                                total_prices
                                                                ) 
                                    VALUES (
                                                null, 
                                                :order_number, 
                                                :user_id, 
                                                :ticket_id, 
                                                :match_id, 
                                                :quantity, 
                                                :date, 
                                                :total_prices)"
                                            );
        $parameters = [ 
            'order_number' => $numberOrder, 
            'user_id' => $user->getId(), 
            'ticket_id' => $ticket_id, 
            'match_id' => $match_id, 
            'quantity' => $quantity, 
            'date' => $formatted_date, 
            'total_prices' => $totalPrices
        ];
        $query->execute($parameters);
    }

    /**********************************************************
                             * CHECK *
    **********************************************************/


    public function checkChangeAddress(
                                            Addresse $address_id, 
                                            User $users , 
                                            string $addresses , 
                                            int $postal_code,
                                            string $city, 
                                            string $pays, 
                                            ? string $completement 

                                        ) : void{

        $query = $this->db->prepare("UPDATE addresses 
                                        SET user_id = :user_id, 
                                        addresse = :addresse, 
                                        complements = :complements, 
                                        postal_code = :postal_code, 
                                        city = :city, 
                                        pays = :pays 
                                        WHERE id = :id ");
        $parameters = [
            'id' => $address_id->getId(),
            'user_id' => $users->getId(),
            'addresse' => $addresses,		
            'complements' => isset($completement) ? $completement : "", 
            'postal_code' => $postal_code, 
            'city' => $city,
            'pays' => $pays
        ];
        $query->execute($parameters);
    }

    /**********************************************************
                             * FETCH *
    **********************************************************/

    /***************************
        * FETCH ADDRESS *
    ****************************/

    public function getAllAddressesByUserId(User $users) : ? Addresse{
        $query = $this->db->prepare("SELECT * FROM addresses WHERE user_id = :user_id");
        $parameters = [
            'user_id' => $users->getId()
         ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result) {
            $addresses = new Addresse( $users , $result["addresse"], $result["postal_code"], $result["city"], $result["pays"]);
            $addresses->setComplements($result["complements"]);
            $addresses->setId($result["id"]);
            return $addresses;
        }

        return null ;
    }

    public function getAddressesById(int $id, User $users) : ? Addresse{
        $query = $this->db->prepare("SELECT * FROM addresses WHERE id = :id AND user_id = :user_id");
        $parameters = [
            'id' => $id,
            'user_id' => $users->getId()
         ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result) {
            $addresses = new Addresse( $users, $result["addresse"], $result["postal_code"], $result["city"], $result["pays"]);
            $addresses->setComplements($result["complements"]);
            $addresses->setId($result["id"]);
            return $addresses;
        }
        return null ;
    }

    public function getOrdersByAddresse(Addresse $addresse) : ? array{
        $query = $this->db->prepare("SELECT * FROM orders WHERE addresse_id = :addresse_id");
        $parameters = [
            'addresse_id' => $addresse->getId()
         ];
        $query->execute($parameters);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach( $results as $result){
            $newOrders = new Order($result["order_number"], $addresse , $result["date"], $result["totalTtc"]);
            $newOrders->setId($result["id"]);
            return $results;
        }
        return null ;
    }

    /***************************
        * FETCH TICKET *
    ****************************/

    public function getAllOrderTicketByUser(User $user) : array{

        $query = $this->db->prepare("SELECT * FROM order_tickets WHERE user_id = :user_id");
        $parameters = [ 
            'user_id' => $user->getId()
        ];
        $query->execute($parameters);

        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $matchManager = new MatchManager();
        
        foreach( $results as $result){
            $match = $matchManager->getAllMatchsByIdNoPlay($result["match_id"]);
            $ticket = $matchManager->getAllTicketsById($result["ticket_id"]);
            $orderTicket = new Order_ticket( 
                                                $user, 
                                                $result["order_number"], 
                                                $ticket, 
                                                $match , 
                                                $result["quantity"], 
                                                $result["date"], 
                                                $result["total_prices"]
                                            );
            $orderTicket->setId($result["id"]);
        }

        return $results ;
    }

    public function getOrderTicketById(Order_ticket $order_ticket) : ? Order_ticket{

        $query = $this->db->prepare("SELECT * FROM order_tickets WHERE id = :id");
        $parameters = [ 
            'id' => $order_ticket->getId()
        ];
        $query->execute($parameters);

        $result = $query->fetch(PDO::FETCH_ASSOC);

        $matchManager = new MatchManager();
        $userManager = new UserManager();
        if($result){
            $user = $userManager->getAllUserById($result["user_id"]);
            $match = $matchManager->getAllMatchsByIdNoPlay($result["match_id"]);
            $ticket = $matchManager->getAllTicketsById($result["ticket_id"]);
            $orderTicket = new Order_ticket( 
                                                $user, 
                                                $result["order_number"], 
                                                $ticket, 
                                                $match , 
                                                $result["quantity"], 
                                                $result["date"], 
                                                $result["total_prices"]
                                            );
            $orderTicket->setId($result["id"]);

            return $orderTicket;
        }

        return null ;
    }

    /***************************
        * FETCH PRODUCT *
    ****************************/

    public function getOrdersProductByOrderNumber( string $order_number) : array{

        $query = $this->db->prepare("SELECT * FROM order_products WHERE order_number = :order_number");
        $parameters = [
            'order_number' => $order_number
        ];
        $query->execute($parameters);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach($results as $result){
            $newOrdersProduct = new Order_product(
                                                    $order_number, 
                                                    $result["product_id"]  ,
                                                    $result["quantity"], 
                                                    $result["sizes"], 
                                                    $result["date"], 
                                                    $result["total_prices"]
                                                );
            $newOrdersProduct->setId($result["id"]);
        }
        return $results ;
    }

    public function getAllOrdersProduct() : array{

        $query = $this->db->prepare("SELECT * FROM order_products");
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach($results as $result){
            $newOrdersProduct = new Order_product(
                                                    $result["product_id"], 
                                                    $result["product_id"],
                                                    $result["quantity"], 
                                                    $result["sizes"], 
                                                    $result["date"], 
                                                    $result["total_prices"]
                                                );
            $newOrdersProduct->setId($result["id"]);
        }
        return $results;
    }

    public function getAllOrdersProductById(int $order_product) : ? Order_product{

        $query = $this->db->prepare("SELECT * FROM order_products WHERE id = :id");
        $parameters = [
            'id' => $order_product
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result){
            $newOrdersProduct = new Order_product(
                                                    $result["product_id"], 
                                                    $result["product_id"],
                                                    $result["quantity"], 
                                                    $result["sizes"], 
                                                    $result["date"], 
                                                    $result["total_prices"]
                                                );
            $newOrdersProduct->setId($result["id"]);
            return $newOrdersProduct;
        }
        return null;
    }
}


