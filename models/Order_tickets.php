<?php

class Order_tickets {

    private ? int $id = null;
    public function __construct(private Users $user_id, private Tickets $ticket_id, private Addresses $addresses_id, private int $prices) {
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

    public function getTicketsId(): Tickets {
        return $this->ticket_id;
    }
    public function setTicketsId(Tickets $ticket_id):  void {
        $this->ticket_id = $ticket_id;
    }

    public function getAddressesId(): Addresses {
        return $this->addresses_id;
    }
    public function setAddressesId(Addresses $addresses_id): void {
        $this->addresses_id = $addresses_id;
    }

    public function getPrices(): int {
        return $this->prices;
    }
    public function setPrices( int $prices ): void {
        $this->prices = $prices;
    }
}