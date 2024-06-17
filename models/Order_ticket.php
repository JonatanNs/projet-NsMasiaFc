<?php

class Order_ticket {

    private ? int $id = null;
    public function __construct(
                                    private User $user_id, 
                                    private string $numberOrder, 
                                    private array $ticket_id, 
                                    private array $match_id, 
                                    private int $quantity, 
                                    private string $date, 
                                    private int $total_prices
                                ) {
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

    public function getNumberOrder(): string {
        return $this->numberOrder;
    }
    
    public function setNumberOrder(string $numberOrder): void {
        $this->numberOrder = $numberOrder;
    }

    public function getTicketsId(): array {
        return $this->ticket_id;
    }
    public function setTicketsId(array $ticket_id):  void {
        $this->ticket_id = $ticket_id;
    }

    public function getMatchId(): array {
        return $this->match_id;
    }
    public function setMatchId(array $match_id): void {
        $this->match_id = $match_id;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }
    
    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;
    }

    public function getDate(): string {
        return $this->date;
    }
    
    public function setDate(string $date): void {
        $this->date = $date;
    }

    public function gettotal_Prices(): int {
        return $this->total_prices;
    }
    public function settotal_Prices( int $total_prices ): void {
        $this->total_prices = $total_prices;
    }
}