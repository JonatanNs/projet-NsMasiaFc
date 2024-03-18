<?php

class Order_ticket {

    private ? int $id = null;
    public function __construct(private User $user_id, private Ticket $ticket_id, private Addresse $addresses_id, private int $prices) {
    }

    public function getId(): ? int {
        return $this->id;
    }
    public function setId(? int $id): void {
        $this->id = $id;
    }

    public function getUsersId(): User {
        return $this->user_id;
    }
    public function setUsersId( User $user_id): void {
        $this->user_id = $user_id;
    }

    public function getTicketsId(): Ticket {
        return $this->ticket_id;
    }
    public function setTicketsId(Ticket $ticket_id):  void {
        $this->ticket_id = $ticket_id;
    }

    public function getAddressesId(): Addresse {
        return $this->addresses_id;
    }
    public function setAddressesId(Addresse $addresses_id): void {
        $this->addresses_id = $addresses_id;
    }

    public function getPrices(): int {
        return $this->prices;
    }
    public function setPrices( int $prices ): void {
        $this->prices = $prices;
    }
}