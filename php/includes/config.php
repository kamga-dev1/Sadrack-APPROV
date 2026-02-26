<?php
// URL de l'API Node.js
define('API_URL', 'http://localhost:3000/api');

// Démarrer la session
session_start();

// Fonction pour appeler l'API
function api_call(string $endpoint, string $method = 'GET', array $data = []): array {
    $url = API_URL . $endpoint;
    $ch  = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
    ]);
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    } elseif ($method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    } elseif ($method === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true) ?? [];
}
?>