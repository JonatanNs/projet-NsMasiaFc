<?php

class Ticket {
    private ?int $id = null;

    public function __construct(private string $tribune, private int $prices, private int $stock) {}

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function getTribune(): string {
        return $this->tribune;
    }

    public function setTribune(string $tribune): void {
        $this->tribune = $tribune;
    }

    public function getStock(): int {
        return $this->stock;
    }

    public function setStock(int $stock): void {
        $this->stock = $stock;
    }

    public function getPrices(): int {
        return $this->prices;
    }

    public function setPrices(int $prices): void {
        $this->prices = $prices;
    }
}
