<?php

class Products {

private ?int $id = null;

public function __construct(
    private string $name, 
    private string $img_url, 
    private string $img_alt, 
    private string $descriptions,
    private string $prices, 
    private ?string $other_img_url = null,
    private ?string $other_img_alt = null

) {
    
}

public function getId(): ?int {
    return $this->id;
}

public function setId(?int $id): void {
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

public function getPrices(): string {
    return $this->prices;
}

public function setPrices(string $prices): void {
    $this->prices = $prices;
}

public function getOtherImgUrl(): ?string {
    return $this->other_img_url;
}

public function setOtherImgUrl(?string $other_img_url): void {
    $this->other_img_url = $other_img_url;
}

public function getOtherImgAlt(): ?string {
    return $this->other_img_alt;
}

public function setOtherImgAlt(?string $other_img_alt): void {
    $this->other_img_alt = $other_img_alt;
}

}
