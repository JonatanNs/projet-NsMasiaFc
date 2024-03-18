<?php 

class Location{
    private ? int $id = null;
    public function __construct(private string $stadium, private string $city) {
    }

    public function getId(): ? int {
        return $this->id;
    }
    public function setId(? int $id): void {
        $this->id = $id;
    }

    public function getStadium(): string {
        return $this->stadium;
    }

    public function setStadium(string $stadium): void {
        $this->stadium = $stadium;
    }
    public function getCity(): string {
        return $this->city;
    }

    public function setCity(string $city): void {
        $this->city = $city;
    }

}