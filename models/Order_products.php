<?php
class Order_products {

private ?int $id = null;

public function __construct(
    private Users $user_id,
    private array $products,
    private int $quantity,
    private Addresses $addresses_id,
    private array $prices,
    private string $date,
    private int $total_prices
) {
}

public function getId(): ?int {
    return $this->id;
}

public function setId(?int $id): void {
    $this->id = $id;
}

public function getUsersId(): Users {
    return $this->user_id;
}

public function setUsersId(Users $user_id): void {
    $this->user_id = $user_id;
}

public function getProducts(): array {
    return $this->products;
}

public function setProducts(array $products): void {
    $this->products = $products;
}

public function getQuantity(): int {
    return $this->quantity;
}

public function setQuantity(int $quantity): void {
    $this->quantity = $quantity;
}

public function getAddressesId(): Addresses {
    return $this->addresses_id;
}

public function setAddressesId(Addresses $addresses_id): void {
    $this->addresses_id = $addresses_id;
}

public function getPrices(): array {
    return $this->prices;
}

public function setPrices(array $prices): void {
    $this->prices = $prices;
}

public function getDate(): string {
    return $this->date;
}

public function setDate(string $date): void {
    $this->date = $date;
}

public function getTotalPrices(): int {
    return $this->total_prices;
}

public function setTotalPrices(int $total_prices): void {
    $this->total_prices = $total_prices;
}
}
