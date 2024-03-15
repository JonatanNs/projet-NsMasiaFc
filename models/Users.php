<?php

class Users {

    private ? int $id = null;
    private string $roles = "USER";
    public function __construct(private string $first_name, private string $last_name, private string $email, private string $password) {
    }

    public function getId(): ? int {
        return $this->id;
    }
    public function setId(? int $id): void {
        $this->id = $id;
    }

    public function getFirstName(): string {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): void {
        $this->first_name = $first_name;
    }
    public function getLastName(): string {
        return $this->last_name;
    }

    public function setLastName(string $last_name): void {
        $this->last_name = $last_name;
    }

    public function getEmail(): string {
        return $this->email;
    }
    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getPassword(): string {
        return $this->password;
    }
    public function setPassword(string $password): void {
        $this->password = $password;
    } 
    
    public function getRoles(): string {
        return $this->roles;
    }
    public function setRoles(string $roles): void {
        $this->roles = $roles;
    }
}
