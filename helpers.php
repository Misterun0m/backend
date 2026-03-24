<?php
/**
 * cors.php — Incluir al inicio de CADA archivo PHP de la API
 *
 * Uso:
 *   require_once __DIR__ . '/../config/cors.php';
 *
 * Coloca este archivo en:  /jovenestramites/api/config/cors.php
 */

// En producción reemplaza '*' por tu dominio: 'https://tudominio.com'
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}
