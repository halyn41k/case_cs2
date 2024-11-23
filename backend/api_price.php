<?php
// Дозволяє доступ до ресурсів з вашого фронтенду
header("Access-Control-Allow-Origin: http://cases2.ct.ws"); // Вказати правильний домен
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// Підключаємо автозавантажувач залежностей через Composer
require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;

// Перевірка, чи передано параметр 'market_hash_name' у запиті
if (isset($_GET['market_hash_name'])) {
    // Кодування назви кейсу для правильного запиту
    $marketHashName = rawurlencode($_GET['market_hash_name']);
    $url = "https://steamcommunity.com/market/priceoverview/?currency=18&appid=730&market_hash_name={$marketHashName}";

    // Ініціалізація клієнта Guzzle
    $client = new Client();

    try {
        // Отримуємо відповідь від Steam API
        $response = $client->get($url);
        $data = $response->getBody()->getContents();
        
        // Відправляємо відповідь у форматі JSON
        header('Content-Type: application/json');
        echo $data;
    } catch (\Exception $e) {
        // Якщо сталася помилка при запиті до API, відправляємо код 500 і повідомлення про помилку
        http_response_code(500);
        echo json_encode([
            "error" => "Не вдалося отримати дані від Steam",
            "details" => $e->getMessage()
        ]);
    }
} else {
    // Якщо параметр 'market_hash_name' відсутній, відправляємо код 400 і помилку
    http_response_code(400);
    echo json_encode(["error" => "Відсутній параметр 'market_hash_name'"]);
}
?>
