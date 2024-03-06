<?php

class Addresses {

    private ? int $id = null;
    public function __construct(private int $user_id, private string $street, private int $streetNumber, private string $complements, private int $postal_code, private string $city) {
    }

    public function getId(): ? int {
        return $this->id;
    }
    public function setId(? int $id): void {
        $this->id = $id;
    }

    public function getUser_id(): int {
        return $this->user_id;
    }

    public function setUser_id(int $user_id): void {
        $this->user_id = $user_id;
    }

    public function getStreet(): string {
        return $this->street;
    }
    public function setStreet(string $street): void {
        $this->street = $street;
    }

    public function getStreetNumber(): int {
        return $this->streetNumber;
    }
    public function setStreetNumber(int $streetNumber): void {
        $this->streetNumber = $streetNumber;
    }

    public function getComplements(): string {
        return $this->complements;
    }
    public function setComplements(string $complements): void {
        $this->complements = $complements;
    }

    public function getPostalCode(): int {
        return $this->postal_code;
    }
    public function setPostalCode(int $postal_code): void {
        $this->postal_code = $postal_code;
    }

    public function getCity(): string {
        return $this->city;
    }
    public function setCity(string $city): void {
        $this->city = $city;
    }
}
