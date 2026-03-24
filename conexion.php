<?php
// ------------------------------
// Archivo: conexion.php
// Descripción: Este archivo realiza la conexión
// a la base de datos MySQL usando PDO.
// ------------------------------

$host    = "centerbeam.proxy.rlwy.net";
$port    = 16749;
$db      = "railway";
$user    = "root";
$pass    = "ycrtevFnWGNbedBPSMIRQbEHWkqVXPNl";
$charset = "utf8mb4";

// DSN incluyendo el puerto para Railway
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

try {

  // Se crea la conexión usando PDO
  $pdo = new PDO($dsn, $user, $pass, [

    // PDO muestra errores como excepciones
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,

    // Resultados como arreglos asociativos
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);

} catch (Throwable $e) {

  http_response_code(500);
  echo json_encode(["error" => "No se pudo conectar a la BD"]);
  exit;
}
