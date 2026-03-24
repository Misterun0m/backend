<?php
declare(strict_types=1);

header("Access-Control-Allow-Origin: https://jovenes-tramites.vercel.app");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once "conexion.php";

$action = $_GET['action'] ?? '';

switch ($action) {

    // ── Obtener info de usuario ───────────────────────────
    case 'obtener':
        $user_id = intval($_GET['user_id'] ?? 0);
        $stmt = $pdo->prepare("
            SELECT user_id, user_nom, user_sex, user_correo, fecha_nacimiento, created_at
            FROM usuario WHERE user_id = ?
        ");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($user ?: []);
        break;

    // ── Actualizar datos usuario ──────────────────────────
    case 'actualizar':
        $user_id = intval($_GET['user_id'] ?? 0);
        $data = json_decode(file_get_contents("php://input"), true);

        $user_nom         = $data['user_nom']         ?? '';
        $user_sex         = $data['user_sex']         ?? '';
        $fecha_nacimiento = $data['fecha_nacimiento'] ?? null;

        $stmt = $pdo->prepare("
            UPDATE usuario SET user_nom=?, user_sex=?, fecha_nacimiento=? WHERE user_id=?
        ");
        if ($stmt->execute([$user_nom, $user_sex, $fecha_nacimiento, $user_id])) {
            echo json_encode(["success" => true, "message" => "Usuario actualizado"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al actualizar"]);
        }
        break;

    // ── Obtener trámites del usuario ──────────────────────
    case 'tramites':
        $user_id = intval($_GET['user_id'] ?? 0);
        $stmt = $pdo->prepare("
            SELECT ut.user_idtramite, t.tram_id, t.tram_tip, t.tram_imp, t.tram_saber,
                   ut.estado_tramite, ut.fecha_inicio, ut.fecha_finalizacion
            FROM user_tramite ut
            JOIN tramite t ON t.tram_id = ut.tram_id
            WHERE ut.user_id = ?
            ORDER BY t.tram_id ASC
        ");
        $stmt->execute([$user_id]);
        $tramites = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($tramites);
        break;

    // ── Actualizar estado de un trámite ───────────────────
    case 'actualizar_estado':
        $data = json_decode(file_get_contents("php://input"), true);
        $user_idtramite = intval($data['user_idtramite'] ?? 0);
        $estado_tramite = $data['estado_tramite']        ?? '';

        if ($estado_tramite === 'Finalizado') {
            $stmt = $pdo->prepare("
                UPDATE user_tramite SET estado_tramite=?, fecha_finalizacion=CURDATE()
                WHERE user_idtramite=?
            ");
        } else {
            $stmt = $pdo->prepare("
                UPDATE user_tramite SET estado_tramite=?, fecha_finalizacion=NULL
                WHERE user_idtramite=?
            ");
        }

        if ($stmt->execute([$estado_tramite, $user_idtramite])) {
            echo json_encode(["success" => true, "message" => "Estado actualizado"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al actualizar estado"]);
        }
        break;

    // ── Requisitos de un trámite ──────────────────────────
    case 'requisitos':
        $tram_id = intval($_GET['tram_id'] ?? 0);
        $stmt = $pdo->prepare("
            SELECT re_id, descripcion, portal_oficial
            FROM requisito
            WHERE tram_id = ?
            ORDER BY re_id ASC
        ");
        $stmt->execute([$tram_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($rows);
        break;

    // ── Eliminar cuenta ───────────────────────────────────
    case 'eliminar':
        $user_id = intval($_GET['user_id'] ?? 0);

        if ($user_id <= 0) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "user_id inválido"]);
            break;
        }

        try {
            $pdo->beginTransaction();

            $stmt1 = $pdo->prepare("DELETE FROM user_tramite WHERE user_id = ?");
            $stmt1->execute([$user_id]);

            $stmt2 = $pdo->prepare("DELETE FROM usuario WHERE user_id = ?");
            $stmt2->execute([$user_id]);

            $pdo->commit();
            echo json_encode(["success" => true, "message" => "Cuenta eliminada correctamente"]);

        } catch (\Exception $e) {
            $pdo->rollBack();
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Error al eliminar la cuenta"]);
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Acción no válida"]);
}
?>
