<?php
class Order_product {

private ?int $id = null;

public function __construct(
    private string $numberOrder,
    private Product $products,
    private int $quantity,
    private array $sizes,
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

public function getNumberOrder(): string {
    return $this->numberOrder;
}

public function setNumberOrder(string $numberOrder): void {
    $this->numberOrder = $numberOrder;
}

public function getProducts(): Product {
    return $this->products;
}

public function setProducts(Product $products): void {
    $this->products = $products;
}

public function getQuantity(): int {
    return $this->quantity;
}

public function setQuantity(int $quantity): void {
    $this->quantity = $quantity;
}

public function getTailles(): array {
    return $this->sizes;
}

public function setTailles(array $sizes): void {
    $this->sizes = $sizes;
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
