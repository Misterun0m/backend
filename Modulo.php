<?php
// ── CORS ─────────────────────────────────────────────────────
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// ── Conexión ──────────────────────────────────────────────────
require_once __DIR__ . '/conexion.php';

// ── Funciones auxiliares ──────────────────────────────────────
function responder($data) {
    echo json_encode(['ok' => true, 'data' => $data]);
    exit;
}

function responderError($mensaje, $codigo = 400) {
    http_response_code($codigo);
    echo json_encode(['ok' => false, 'message' => $mensaje]);
    exit;
}

// ── Lógica principal ──────────────────────────────────────────
try {
    $tramId = filter_input(INPUT_GET, 'tipo', FILTER_VALIDATE_INT);
    $lat    = filter_input(INPUT_GET, 'lat',  FILTER_VALIDATE_FLOAT);
    $lng    = filter_input(INPUT_GET, 'lng',  FILTER_VALIDATE_FLOAT);

    if (!$tramId || $tramId <= 0) {
        responderError('Parámetro tipo debe ser un entero positivo (tram_id).', 400);
    }

    $check = $pdo->prepare('SELECT tram_id, tram_tip FROM tramite WHERE tram_id = ?');
    $check->execute([$tramId]);
    $tramite = $check->fetch();

    if (!$tramite) {
        responderError("Trámite con id $tramId no encontrado.", 404);
    }

    if ($lat !== false && $lng !== false && $lat !== null && $lng !== null) {
        $sql = "
            SELECT
                m.modulo_id,
                m.nombre,
                m.direccion,
                m.lat,
                m.lng,
                m.horario,
                m.telefono,
                m.url_cita,
                ROUND(
                    6371 * 2 * ASIN(SQRT(
                        POWER(SIN(RADIANS(m.lat - :lat) / 2), 2) +
                        COS(RADIANS(:lat2)) * COS(RADIANS(m.lat)) *
                        POWER(SIN(RADIANS(m.lng - :lng) / 2), 2)
                    )), 2
                ) AS distancia_km
            FROM modulo m
            INNER JOIN modulo_tramite mt ON mt.modulo_id = m.modulo_id
            WHERE mt.tram_id = :tram_id
            ORDER BY distancia_km ASC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':lat'     => $lat,
            ':lat2'    => $lat,
            ':lng'     => $lng,
            ':tram_id' => $tramId,
        ]);
    } else {
        $sql = "
            SELECT
                m.modulo_id,
                m.nombre,
                m.direccion,
                m.lat,
                m.lng,
                m.horario,
                m.telefono,
                m.url_cita,
                NULL AS distancia_km
            FROM modulo m
            INNER JOIN modulo_tramite mt ON mt.modulo_id = m.modulo_id
            WHERE mt.tram_id = :tram_id
            ORDER BY m.nombre ASC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':tram_id' => $tramId]);
    }

    $modulos = $stmt->fetchAll();
    $modulos = array_map(fn($m) => [
        'modulo_id'    => (int)   $m['modulo_id'],
        'nombre'       =>         $m['nombre'],
        'direccion'    =>         $m['direccion'],
        'lat'          => (float) $m['lat'],
        'lng'          => (float) $m['lng'],
        'horario'      =>         $m['horario'],
        'telefono'     =>         $m['telefono'],
        'url_cita'     =>         $m['url_cita'],
        'distancia_km' => $m['distancia_km'] !== null ? (float) $m['distancia_km'] : null,
    ], $modulos);

    responder([
        'tramite' => $tramite,
        'total'   => count($modulos),
        'modulos' => $modulos,
    ]);

} catch (PDOException $e) {
    responderError('Error de base de datos: ' . $e->getMessage(), 500);
}