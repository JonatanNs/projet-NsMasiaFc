<?php

class Order_products {

    private ? int $id = null;
    public function __construct(private Users $user_id, private Products $products_id, private Addresses $addresses_id, private int $prices) {
    }

    public function getId(): ? int {
        return $this->id;
    }
    public function setId(? int $id): void {
        $this->id = $id;
    }

    public function getUsersId(): Users {
        return $this->user_id;
    }
    public function setUsersId( Users $user_id): void {
        $this->user_id = $user_id;
    }

    public function getProductsId(): Products {
        return $this->products_id;
    }
    public function setProductsId(Products $products_id):  void {
        $this->products_id = $products_id;
    }


    public function getAddressesId(): Addresses {
        return $this->addresses_id;
    }
    public function setAddressesId( Addresses $addresses_id): void {
        $this->addresses_id = $addresses_id;
    }

    public function getPrices(): int {
        return $this->prices;
    }
    public function setPrices( int $prices ): void {
        $this->prices = $prices;
    }
}