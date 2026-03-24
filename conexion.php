<?php
// ------------------------------
// Archivo: conexion.php
// Descripción: Este archivo realiza la conexión
// a la base de datos MySQL usando PDO.
// ------------------------------

// Servidor donde se encuentra la base de datos
$host = "localhost";

// Nombre de la base de datos
$db   = "jovenestramites";

// Usuario de MySQL (por defecto en XAMPP es root)
$user = "root";

// Contraseña del usuario (en XAMPP normalmente está vacía)
$pass = "";

// Tipo de codificación de caracteres (permite acentos, ñ y emojis)
$charset = "utf8mb4";

// DSN (Data Source Name)
// Aquí se especifica el tipo de base de datos, el host,
// el nombre de la base y la codificación
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {

  // Se crea la conexión usando PDO
  // $pdo será la variable que usaremos para hacer consultas
  $pdo = new PDO($dsn, $user, $pass, [

    // Configuración para que PDO muestre errores como excepciones
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

    // Configuración para que los resultados se devuelvan
    // como arreglos asociativos (columna => valor)
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);

} catch (Throwable $e) {

  // Si ocurre un error en la conexión,
  // se envía un código HTTP 500 (error del servidor)
  http_response_code(500);

  // Se envía un mensaje en formato JSON
  echo json_encode(["error" => "No se pudo conectar a la BD"]);

  // Se detiene la ejecución del programa
  exit;
}