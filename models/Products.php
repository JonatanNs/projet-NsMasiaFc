<?php

class Products {

    private ? int $id = null;
    public function __construct(private string $name, private string $img_url, private string $img_alt, private string $descriptions, private int $details) {
    }

    public function getId(): ? int {
        return $this->id;
    }
    public function setId(? int $id): void {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }
    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getImgUrl(): string {
        return $this->img_url;
    }
    public function setImgUrl(string $img_url): void {
        $this->img_url = $img_url;
    }

    public function getImgAlt(): string {
        return $this->img_alt;
    }
    public function setImgAlt(string $img_alt): void {
        $this->img_alt = $img_alt;
    }

    public function getDescriptions(): string {
        return $this->descriptions;
    }
    public function setDescriptions(string $descriptions): void {
        $this->descriptions = $descriptions;
    }

    public function getDetails(): int {
        return $this->details;
    }
    public function setDetails(int $details): void {
        $this->details = $details;
    }
}