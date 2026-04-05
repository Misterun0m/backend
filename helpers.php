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

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}
