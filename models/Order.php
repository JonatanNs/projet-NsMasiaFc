<?php 

class Order{

    private ? int $id = null;
    public function __construct(private string $numberOrder, private Addresses $addresses,private string $date, private int $totalTtc){

    }

    public function getId(): ? int {
        return $this->id;
    }
    public function setId(? int $id): void {
        $this->id = $id;
    }

    public function getOrderProducts(): string {
        return $this->numberOrder;
    }
    public function setOrderProducts(string $numberOrder): void {
        $this->numberOrder = $numberOrder;
    }

    public function getAddresses(): Addresses {
        return $this->addresses;
    }
    public function setAddresses(Addresses $addresses): void {
        $this->addresses = $addresses;
    }

    public function getDate(): string {
        return $this->date;
    }
    
    public function setDate(string $date): void {
        $this->date = $date;
    }

    public function getTotalTtc(): int {
        return $this->totalTtc;
    }
    public function setTotalTCC( int $totalTtc): void {
        $this->totalTtc = $totalTtc;
    }
}


