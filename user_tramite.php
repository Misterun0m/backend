<?php
/**
 * ============================================================
 *  /api/user_tramites.php
 *
 *  GET  ?user_id=26
 *       → Trámites del usuario con detalle + estado
 *
 *  PATCH  body: { user_idtramite, estado_tramite }
 *         estados válidos: 'Pendiente' | 'En proceso' | 'Finalizado'
 *         Si estado = 'Finalizado', guarda fecha_finalizacion = HOY
 *
 *  POST   body: { user_id, tram_id }
 *         → Registra un nuevo trámite para el usuario (estado: Pendiente)
 * ============================================================
 */

require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/config.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    $pdo = getDB();

    // ── GET: Trámites de un usuario ───────────────────────────
    if ($method === 'GET') {
        $userId = filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT);

        if (!$userId) {
            responderError('Parámetro user_id requerido y debe ser entero.', 400);
        }

        $sql = "
            SELECT
                ut.user_idtramite,
                ut.estado_tramite,
                ut.fecha_inicio,
                ut.fecha_finalizacion,
                t.tram_id,
                t.tram_tip,
                t.tram_imp,
                t.tram_saber,
                r.re_id,
                r.descripcion    AS req_descripcion,
                r.portal_oficial AS req_portal
            FROM user_tramite ut
            INNER JOIN tramite t  ON t.tram_id  = ut.tram_id
            LEFT  JOIN requisito r ON r.tram_id = t.tram_id
            WHERE ut.user_id = :user_id
            ORDER BY ut.fecha_inicio DESC, ut.user_idtramite DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $rows = $stmt->fetchAll();

        $resultado = array_map(fn($row) => [
            'user_idtramite'     => (int) $row['user_idtramite'],
            'estado_tramite'     => $row['estado_tramite'],
            'fecha_inicio'       => $row['fecha_inicio'],
            'fecha_finalizacion' => $row['fecha_finalizacion'],
            'tramite' => [
                'tram_id'    => (int) $row['tram_id'],
                'tram_tip'   => $row['tram_tip'],
                'tram_imp'   => $row['tram_imp'],
                'tram_saber' => $row['tram_saber'],
            ],
            'requisito' => $row['re_id'] ? [
                're_id'          => (int) $row['re_id'],
                'descripcion'    => $row['req_descripcion'],
                'portal_oficial' => $row['req_portal'],
            ] : null,
        ], $rows);

        responder($resultado);
    }

    // ── POST: Registrar nuevo trámite para usuario ────────────
    if ($method === 'POST') {
        $body   = bodyJson();
        $userId = isset($body['user_id'])  ? (int) $body['user_id']  : 0;
        $tramId = isset($body['tram_id'])  ? (int) $body['tram_id']  : 0;

        if (!$userId || !$tramId) {
            responderError('Se requieren user_id y tram_id.', 400);
        }

        // Verificar que el trámite existe
        $check = $pdo->prepare('SELECT tram_id FROM tramite WHERE tram_id = ?');
        $check->execute([$tramId]);
        if (!$check->fetch()) {
            responderError("El trámite con id $tramId no existe.", 404);
        }

        // Evitar duplicados activos (mismo user + mismo trámite pendiente/en proceso)
        $dup = $pdo->prepare("
            SELECT user_idtramite FROM user_tramite
            WHERE user_id = ? AND tram_id = ? AND estado_tramite != 'Finalizado'
        ");
        $dup->execute([$userId, $tramId]);
        if ($dup->fetch()) {
            responderError('El usuario ya tiene ese trámite activo.', 409);
        }

        $insert = $pdo->prepare("
            INSERT INTO user_tramite (user_id, estado_tramite, fecha_inicio, tram_id)
            VALUES (:user_id, 'Pendiente', CURDATE(), :tram_id)
        ");
        $insert->execute([':user_id' => $userId, ':tram_id' => $tramId]);

        responder(['user_idtramite' => (int) $pdo->lastInsertId()], 201);
    }

    // ── PATCH: Actualizar estado de un trámite ────────────────
    if ($method === 'PATCH') {
        $body          = bodyJson();
        $userIdTramite = isset($body['user_idtramite'])  ? (int) $body['user_idtramite'] : 0;
        $nuevoEstado   = trim($body['estado_tramite'] ?? '');

        $estadosValidos = ['Pendiente', 'En proceso', 'Finalizado'];

        if (!$userIdTramite) {
            responderError('Se requiere user_idtramite.', 400);
        }
        if (!in_array($nuevoEstado, $estadosValidos, true)) {
            responderError('estado_tramite debe ser: ' . implode(', ', $estadosValidos), 400);
        }

        // Si finaliza, guardar la fecha de hoy; si no, limpiarla
        if ($nuevoEstado === 'Finalizado') {
            $sql = "
                UPDATE user_tramite
                SET estado_tramite = :estado, fecha_finalizacion = CURDATE()
                WHERE user_idtramite = :id
            ";
        } else {
            $sql = "
                UPDATE user_tramite
                SET estado_tramite = :estado, fecha_finalizacion = NULL
                WHERE user_idtramite = :id
            ";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':estado' => $nuevoEstado, ':id' => $userIdTramite]);

        if ($stmt->rowCount() === 0) {
            responderError("No se encontró user_idtramite $userIdTramite.", 404);
        }

        responder(['updated' => $userIdTramite, 'estado_tramite' => $nuevoEstado]);
    }

    // Método no soportado
    responderError('Método no permitido.', 405);

} catch (PDOException $e) {
    responderError('Error de base de datos: ' . $e->getMessage(), 500);
}
