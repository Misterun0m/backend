<?php
/**
 * ============================================================
 *  config.php — Configuración de base de datos
 *  Coloca este archivo en: backend/config/config.php
 *  ¡NO expongas este archivo públicamente!
 * ============================================================
 */

define('DB_HOST',     '127.0.0.1');
define('DB_USER',     'root');       // Cambia por tu usuario MySQL
define('DB_PASS',     '');           // Cambia por tu contraseña MySQL
define('DB_NAME',     'jovenestramites');
define('DB_PORT',     3306);
define('DB_CHARSET',  'utf8mb4');

/**
 * Retorna una conexión PDO lista para usar.
 * Lanza excepción si no puede conectar.
 */
function getDB(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
        );
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    }

    return $pdo;
}
