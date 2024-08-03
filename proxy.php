<?php

header("Access-Control-Allow-Origin: *");  // Allow any origin for testing purposes
header("Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['url'])) {
    $url = urldecode($_GET['url']);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($response === false) {
        echo json_encode(['success' => false, 'error' => 'Curl error: ' . $error]);
    } else {
        $http_code = http_response_code();
        if ($http_code >= 200 && $http_code < 300) {
            echo $response;
        } else {
            echo json_encode(['success' => false, 'error' => 'HTTP error: ' . $http_code, 'response' => $response]);
        }
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No URL specified or invalid request method']);
}
?>
