<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");
require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;

if (isset($_GET['market_hash_name'])) {
    // Кодування назви кейсу для правильного запиту
    $marketHashName = rawurlencode($_GET['market_hash_name']);
    $url = "https://steamcommunity.com/market/priceoverview/?currency=18&appid=730&market_hash_name={$marketHashName}";

    // Ініціалізація клієнта Guzzle
    $client = new Client();
    try {
        $response = $client->get($url);
        $data = $response->getBody()->getContents();
        header('Content-Type: application/json');
        echo $data;
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode([
            "error" => "Не вдалося отримати дані від Steam",
            "details" => $e->getMessage()
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "Відсутній параметр 'market_hash_name'"]);
}
