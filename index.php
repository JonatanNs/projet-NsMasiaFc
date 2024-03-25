<?php

require "vendor/autoload.php";
// charge le contenu du .env dans $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

session_start();

$api = $_ENV['API_KEY'];
$stripe = new \Stripe\StripeClient($api);

if(!isset($_SESSION["csrf-token"])) {
    $tokenManager = new CSRFTokenManager();
    $token = $tokenManager->generateCSRFToken();
    $_SESSION["csrf-token"] = $token;
}

$router = new Router();
$router->handleRequest($_GET);