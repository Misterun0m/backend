<?php
declare(strict_types=1);

header("Content-Type: application/json; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

define('GROQ_API_KEY', getenv('GROQ_API_KEY') ?: '');

$raw  = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!$data || empty($data['messages'])) {
    http_response_code(400);
    echo json_encode(["error" => "Mensajes requeridos"]);
    exit;
}

$system   = $data['system'] ?? '';
$messages = [];

if ($system) {
    $messages[] = ["role" => "system", "content" => $system];
}

foreach ($data['messages'] as $msg) {
    $messages[] = [
        "role"    => $msg['role'],
        "content" => $msg['content']
    ];
}

$payload = json_encode([
    "model"       => "llama-3.1-8b-instant",
    "messages"    => $messages,
    "max_tokens"  => 1000,
    "temperature" => 0.7
]);

$ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . GROQ_API_KEY
    ],
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_SSL_VERIFYPEER => false,
]);

$response  = curl_exec($ch);
$httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    http_response_code(500);
    echo json_encode(["error" => "Error de conexión", "detalle" => $curlError]);
    exit;
}

$groqData = json_decode($response, true);

if (isset($groqData['error'])) {
    http_response_code(500);
    echo json_encode([
        "error"   => "Error de Groq",
        "detalle" => $groqData['error']['message'] ?? 'Error desconocido',
        "codigo"  => $httpCode
    ]);
    exit;
}

if (empty($groqData['choices'][0]['message']['content'])) {
    http_response_code(500);
    echo json_encode([
        "error"   => "Respuesta vacía de Groq",
        "detalle" => $groqData
    ]);
    exit;
}

$texto = $groqData['choices'][0]['message']['content'];

http_response_code(200);
echo json_encode([
    "content" => [["type" => "text", "text" => $texto]]
]);
?>