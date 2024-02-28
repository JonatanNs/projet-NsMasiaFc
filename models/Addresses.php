<?php

class Addresses {

    private ? int $id = null;
    public function __construct(private Users $user_id, private string $street, private int $number, private string $complements, private string $postal_code, private int $city) {
    }

    public function getId(): ? int {
        return $this->id;
    }
    public function setId(? int $id): void {
        $this->id = $id;
    }

    public function getUser_id(): Users {
        return $this->user_id;
    }

    public function setUser_id(Users $user_id): void {
        $this->user_id = $user_id;
    }

    public function getStreet(): string {
        return $this->street;
    }
    public function setStreet(string $street): void {
        $this->street = $street;
    }

    public function getNumber(): int {
        return $this->number;
    }
    public function setNumber(int $number): void {
        $this->number = $number;
    }

    public function getComplements(): string {
        return $this->complements;
    }
    public function setComplements(string $complements): void {
        $this->complements = $complements;
    }

    public function getPostalCode(): string {
        return $this->postal_code;
    }
    public function setPostalCode(string $postal_code): void {
        $this->postal_code = $postal_code;
    }

    public function getCity(): int {
        return $this->city;
    }
    public function setCity(int $city): void {
        $this->city = $city;
    }
}
