<?php

class Addresses {

    private ? int $id = null;
    private ? string $complements = null;
    public function __construct(private Users $user_id, private string $addresse, private int $postal_code, private string $city, private string $pays) {
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

    public function getAddresse(): string {
        return $this->addresse;
    }
    public function setAddresse(string $addresse): void {
        $this->addresse = $addresse;
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

    public function getPays(): string {
        return $this->pays;
    }
    public function setPays(string $pays): void {
        $this->pays = $pays;
    }
}
