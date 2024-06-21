<?php

class PlayerNsMasia {

    private ? int $id = null;
    public function __construct(
                                    private string $first_name, 
                                    private string $last_name, 
                                    private string $name_jersay, 
                                    private int $number, 
                                    private string $position,
                                    private string $img
                                ) {
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

    public function getNameJersay(): string {
        return $this->name_jersay;
    }
    public function setNameJersay(string $name_jersay){
        $this->name_jersay = $name_jersay;
    }

    public function getNumber(): int {
        return $this->number;
    }
    public function setNumber(int $number){
        $this->number = $number;
    }

    public function getPosition(): string {
        return $this->position;
    }
    public function setPosition(string $position){
        $this->position = $position;
    }

    public function getImg(): string {
        return $this->img;
    }
    public function setImg(string $img){
        $this->img = $img;
    }
}