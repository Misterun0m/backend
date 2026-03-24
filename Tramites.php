<?php
/**
 * ============================================================
 *  GET /api/tramites.php
 *
 *  Devuelve todos los trámites de la tabla `tramite`
 *  junto con sus requisitos de la tabla `requisito`.
 *
 *  Respuesta:
 *  {
 *    "ok": true,
 *    "data": [
 *      {
 *        "tram_id": 1,
 *        "tram_tip": "Credencial de elector (INE)",
 *        "tram_imp": "...",
 *        "tram_saber": "...",
 *        "requisito": {
 *          "re_id": 1,
 *          "descripcion": "...",
 *          "portal_oficial": "https://..."
 *        }
 *      }, ...
 *    ]
 *  }
 * ============================================================
 */

require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/config.php';

try {
    $pdo = getDB();

    // JOIN tramite ← requisito (LEFT para no perder trámites sin requisito aún)
    $sql = "
        SELECT
            t.tram_id,
            t.tram_tip,
            t.tram_imp,
            t.tram_saber,
            r.re_id,
            r.descripcion       AS req_descripcion,
            r.portal_oficial    AS req_portal
        FROM tramite t
        LEFT JOIN requisito r ON r.tram_id = t.tram_id
        ORDER BY t.tram_id ASC
    ";

    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();

    // Construir respuesta estructurada
    $tramites = [];
    foreach ($rows as $row) {
        $tramites[] = [
            'tram_id'    => (int) $row['tram_id'],
            'tram_tip'   => $row['tram_tip'],
            'tram_imp'   => $row['tram_imp'],
            'tram_saber' => $row['tram_saber'],
            'requisito'  => $row['re_id'] ? [
                're_id'          => (int) $row['re_id'],
                'descripcion'    => $row['req_descripcion'],
                'portal_oficial' => $row['req_portal'],
            ] : null,
        ];
    }

    responder($tramites);

} catch (PDOException $e) {
    responderError('Error de base de datos: ' . $e->getMessage(), 500);
}
