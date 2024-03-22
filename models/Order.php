<?php 

class Order{

    private ? int $id = null;
    public function __construct(private string $numberOrder, private Addresse $addresses,private string $date, private int $totalTtc){

    }

    public function getId(): ? int {
        return $this->id;
    }
    public function setId(? int $id): void {
        $this->id = $id;
    }

    public function getNumberOrder(): string {
        return $this->numberOrder;
    }
    public function setNumberOrder(string $numberOrder): void {
        $this->numberOrder = $numberOrder;
    }

    public function getAddresses(): Addresse {
        return $this->addresses;
    }
    public function setAddresses(Addresse $addresses): void {
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



