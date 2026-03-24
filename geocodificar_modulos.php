<?php
/**
 * ============================================================
 *  geocodificar_modulos.php
 *  Ejecutar UNA SOLA VEZ desde el navegador o CLI:
 *    http://localhost/geocodificar_modulos.php
 *  Geocodifica direcciones reales usando Google Maps API
 *  e inserta los módulos en la BD con lat/lng correctos.
 * ============================================================
 */

// ── CONFIGURACIÓN ─────────────────────────────────────────────
define('GOOGLE_API_KEY', 'AIzaSyAwIcOEpRj6SDvrHD4IZIw7eEwwkT1j6XY');
define('DELAY_MS', 200); // ms entre peticiones para no exceder límite

// ── AJUSTA ESTA RUTA si conexion.php está en otro lugar ──────
require_once __DIR__ . '/conexion.php';

// ── MÓDULOS OFICIALES DE MÉXICO ───────────────────────────────
// Formato: [nombre, direccion_completa, horario, telefono, url_cita, [tram_ids]]
$modulos = [

    // ══════════════════════════════════════════════
    //  TRAM_ID = 1 → INE (Credencial de elector)
    // ══════════════════════════════════════════════
    ['Módulo INE Iztapalapa',
     'Av. Ermita Iztapalapa 3315, Santa María Aztahuacán, Iztapalapa, CDMX',
     'Lun-Vie 9:00-17:00', '800 433 2000', 'https://inetel-citas.ine.mx/', [1]],

    ['Módulo INE Gustavo A. Madero',
     'Av. Insurgentes Norte 1173, Gustavo A. Madero, CDMX',
     'Lun-Vie 9:00-17:00', '800 433 2000', 'https://inetel-citas.ine.mx/', [1]],

    ['Módulo INE Coyoacán',
     'Av. Universidad 1449, Copilco Universidad, Coyoacán, CDMX',
     'Lun-Vie 9:00-17:00', '800 433 2000', 'https://inetel-citas.ine.mx/', [1]],

    ['Módulo INE Tlalpan',
     'Insurgentes Sur 4411, Tlalpan, CDMX',
     'Lun-Vie 9:00-17:00', '800 433 2000', 'https://inetel-citas.ine.mx/', [1]],

    ['Módulo INE Cuauhtémoc',
     'Av. Hidalgo 77, Centro Histórico, Cuauhtémoc, CDMX',
     'Lun-Vie 9:00-17:00', '800 433 2000', 'https://inetel-citas.ine.mx/', [1]],

    ['Módulo INE Ecatepec',
     'Av. Central 405, Valle de Anáhuac, Ecatepec de Morelos, Estado de México',
     'Lun-Vie 9:00-17:00', '800 433 2000', 'https://inetel-citas.ine.mx/', [1]],

    ['Módulo INE Guadalajara Norte',
     'Av. Federalismo Norte 951, Mezquitan Country, Guadalajara, Jalisco',
     'Lun-Vie 9:00-17:00', '800 433 2000', 'https://inetel-citas.ine.mx/', [1]],

    ['Módulo INE Monterrey Centro',
     'Washington 2000 Ote, Obispado, Monterrey, Nuevo León',
     'Lun-Vie 9:00-17:00', '800 433 2000', 'https://inetel-citas.ine.mx/', [1]],

    ['Módulo INE Puebla Centro',
     '11 Oriente 1204, El Carmen, Puebla, Puebla',
     'Lun-Vie 9:00-17:00', '800 433 2000', 'https://inetel-citas.ine.mx/', [1]],

    ['Módulo INE Tijuana',
     'Blvd. Agua Caliente 4558, Aviación, Tijuana, Baja California',
     'Lun-Vie 9:00-17:00', '800 433 2000', 'https://inetel-citas.ine.mx/', [1]],

    ['Módulo INE León',
     'Blvd. Francisco Villa 302, Los Gavilanes, León, Guanajuato',
     'Lun-Vie 9:00-17:00', '800 433 2000', 'https://inetel-citas.ine.mx/', [1]],

    ['Módulo INE Mérida',
     'Calle 65 492, Centro, Mérida, Yucatán',
     'Lun-Vie 9:00-17:00', '800 433 2000', 'https://inetel-citas.ine.mx/', [1]],

    // ══════════════════════════════════════════════
    //  TRAM_ID = 2 → SAT (RFC)
    // ══════════════════════════════════════════════
    ['SAT CDMX Centro',
     'Av. Hidalgo 77, Centro Histórico, Cuauhtémoc, CDMX',
     'Lun-Vie 8:30-16:30', '55 8852 2222', 'https://citas.sat.gob.mx/', [2]],

    ['SAT CDMX Insurgentes',
     'Insurgentes Sur 954, Nápoles, Benito Juárez, CDMX',
     'Lun-Vie 8:30-16:30', '55 8852 2222', 'https://citas.sat.gob.mx/', [2]],

    ['SAT Tlalnepantla',
     'Blvd. Manuel Ávila Camacho 2566, Tlalnepantla de Baz, Estado de México',
     'Lun-Vie 8:30-16:30', '55 8852 2222', 'https://citas.sat.gob.mx/', [2]],

    ['SAT Guadalajara',
     'Av. Américas 1211, Country Club, Guadalajara, Jalisco',
     'Lun-Vie 8:30-16:30', '33 3678 3000', 'https://citas.sat.gob.mx/', [2]],

    ['SAT Monterrey',
     'Av. Lázaro Cárdenas 2321, Residencial Roma, Monterrey, Nuevo León',
     'Lun-Vie 8:30-16:30', '81 8150 5000', 'https://citas.sat.gob.mx/', [2]],

    ['SAT Puebla',
     '16 de Septiembre 1202, El Carmen, Puebla, Puebla',
     'Lun-Vie 8:30-16:30', '22 2309 3500', 'https://citas.sat.gob.mx/', [2]],

    ['SAT Tijuana',
     'Paseo de los Héroes 9415, Zona Río, Tijuana, Baja California',
     'Lun-Vie 8:30-16:30', '66 4647 4400', 'https://citas.sat.gob.mx/', [2]],

    ['SAT Mérida',
     'Calle 61 476, Centro, Mérida, Yucatán',
     'Lun-Vie 8:30-16:30', '99 9930 2030', 'https://citas.sat.gob.mx/', [2]],

    // ══════════════════════════════════════════════
    //  TRAM_ID = 3 → IMSS (NSS)
    // ══════════════════════════════════════════════
    ['IMSS Subdelegación CDMX Norte',
     'Av. San Isidro 16, San Pedro Xalpa, Azcapotzalco, CDMX',
     'Lun-Vie 8:00-20:00', '800 623 2323', NULL, [3]],

    ['IMSS Subdelegación CDMX Sur',
     'Av. del Imán 151, Pedregal de Carrasco, Coyoacán, CDMX',
     'Lun-Vie 8:00-20:00', '800 623 2323', NULL, [3]],

    ['IMSS Subdelegación CDMX Oriente',
     'Av. Ermita Iztapalapa 5230, Santa María Aztahuacán, Iztapalapa, CDMX',
     'Lun-Vie 8:00-20:00', '800 623 2323', NULL, [3]],

    ['IMSS Subdelegación Guadalajara',
     'Av. Federalismo Sur 330, Mexicaltzingo, Guadalajara, Jalisco',
     'Lun-Vie 8:00-20:00', '800 623 2323', NULL, [3]],

    ['IMSS Subdelegación Monterrey',
     'Av. Constitución 2000 Ote, Centro, Monterrey, Nuevo León',
     'Lun-Vie 8:00-20:00', '800 623 2323', NULL, [3]],

    ['IMSS Subdelegación Puebla',
     '5 de Mayo 1602, El Carmen, Puebla, Puebla',
     'Lun-Vie 8:00-20:00', '800 623 2323', NULL, [3]],

    ['IMSS Subdelegación Tijuana',
     'Blvd. Cuauhtémoc Sur 668, Hipodromo, Tijuana, Baja California',
     'Lun-Vie 8:00-20:00', '800 623 2323', NULL, [3]],

    ['IMSS Subdelegación Mérida',
     'Calle 27 No. 321, Itzimna, Mérida, Yucatán',
     'Lun-Vie 8:00-20:00', '800 623 2323', NULL, [3]],

    // ══════════════════════════════════════════════
    //  TRAM_ID = 4 → Cartilla Militar
    // ══════════════════════════════════════════════
    ['Junta de Reclutamiento CDMX Centro',
     'Av. Juárez 92, Centro Histórico, Cuauhtémoc, CDMX',
     'Lun-Vie 9:00-14:00', '55 5130 1300', NULL, [4]],

    ['Junta de Reclutamiento CDMX Sur',
     'Insurgentes Sur 4411, Tlalpan, CDMX',
     'Lun-Vie 9:00-14:00', '55 5130 1300', NULL, [4]],

    ['Junta de Reclutamiento Guadalajara',
     'Av. 16 de Septiembre 530, Centro, Guadalajara, Jalisco',
     'Lun-Vie 9:00-14:00', '33 3613 7700', NULL, [4]],

    ['Junta de Reclutamiento Monterrey',
     'Zuazua 1200 Nte, Centro, Monterrey, Nuevo León',
     'Lun-Vie 9:00-14:00', '81 8340 4000', NULL, [4]],

    ['Junta de Reclutamiento Puebla',
     '11 Norte 1104, Centro, Puebla, Puebla',
     'Lun-Vie 9:00-14:00', '22 2246 8500', NULL, [4]],

    ['Junta de Reclutamiento Tijuana',
     'Av. Obregón 1310, Centro, Tijuana, Baja California',
     'Lun-Vie 9:00-14:00', '66 4685 2200', NULL, [4]],

    ['Junta de Reclutamiento Mérida',
     'Calle 59 501, Centro, Mérida, Yucatán',
     'Lun-Vie 9:00-14:00', '99 9924 0500', NULL, [4]],

    // ══════════════════════════════════════════════
    //  TRAM_ID = 5 → Licencia de conducir
    // ══════════════════════════════════════════════
    ['Módulo Licencias CDMX Vallejo',
     'Av. Insurgentes Norte 20, Vallejo, Gustavo A. Madero, CDMX',
     'Lun-Sáb 8:00-18:00', '55 5208 9898', 'https://www.semovi.cdmx.gob.mx/', [5]],

    ['Módulo Licencias CDMX Xochimilco',
     'Periférico Oriente 9058, Santiago Tulyehualco, Xochimilco, CDMX',
     'Lun-Sáb 8:00-18:00', '55 5208 9898', 'https://www.semovi.cdmx.gob.mx/', [5]],

    ['Módulo Licencias CDMX Cuauhtémoc',
     'Av. Insurgentes Centro 149, Doctores, Cuauhtémoc, CDMX',
     'Lun-Sáb 8:00-18:00', '55 5208 9898', 'https://www.semovi.cdmx.gob.mx/', [5]],

    ['Módulo Licencias Guadalajara',
     'Av. Mariano Otero 3507, Verde Valle, Guadalajara, Jalisco',
     'Lun-Sáb 8:00-18:00', '33 3030 0660', 'https://semov.jalisco.gob.mx/', [5]],

    ['Módulo Licencias Monterrey',
     'Av. Constitución 4000 Ote, Fierro, Monterrey, Nuevo León',
     'Lun-Sáb 8:00-18:00', '81 2020 3200', NULL, [5]],

    ['Módulo Licencias Puebla',
     'Blvd. Norte 2902, San Bartolo, Puebla, Puebla',
     'Lun-Sáb 8:00-17:00', '22 2303 2900', NULL, [5]],

    ['Módulo Licencias Tijuana',
     'Blvd. Agua Caliente 11200, Aviación, Tijuana, Baja California',
     'Lun-Vie 8:00-17:00', '66 4973 7500', NULL, [5]],

    ['Módulo Licencias Mérida',
     'Calle 90 No. 500, Centro, Mérida, Yucatán',
     'Lun-Vie 8:00-16:00', '99 9930 3270', NULL, [5]],
];

// ── FUNCIÓN: Geocodificar con Google Maps API (cURL) ─────────
function geocodificar(string $direccion): ?array {
    $url = 'https://maps.googleapis.com/maps/api/geocode/json?' . http_build_query([
        'address'  => $direccion,
        'key'      => GOOGLE_API_KEY,
        'region'   => 'mx',
        'language' => 'es'
    ]);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_USERAGENT      => 'Mozilla/5.0',
    ]);
    $res = curl_exec($ch);
    curl_close($ch);

    if (!$res) return null;

    $data = json_decode($res, true);
    if (($data['status'] ?? '') !== 'OK' || empty($data['results'])) return null;

    $loc = $data['results'][0]['geometry']['location'];
    return ['lat' => $loc['lat'], 'lng' => $loc['lng']];
}

// ── EJECUCIÓN ─────────────────────────────────────────────────
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Geocodificador de Módulos</title>
<style>
  body { font-family: sans-serif; max-width: 900px; margin: 30px auto; padding: 0 20px; background: #f9fafb; }
  h1   { color: #7c3aed; }
  .ok  { color: #16a34a; }
  .err { color: #dc2626; }
  .warn{ color: #d97706; }
  table{ width:100%; border-collapse:collapse; margin-top:20px; font-size:.85rem; }
  th   { background:#7c3aed; color:white; padding:8px; text-align:left; }
  td   { padding:7px 8px; border-bottom:1px solid #e5e7eb; }
  tr:hover td { background:#f3e8ff; }
  .resumen { background:#ede9fe; border-radius:12px; padding:16px; margin-top:20px; }
</style>
</head>
<body>
<h1>🗺️ Geocodificador de Módulos</h1>
<p>Procesando <?= count($modulos) ?> módulos…</p>
<table>
<thead><tr><th>#</th><th>Nombre</th><th>Dirección</th><th>Lat</th><th>Lng</th><th>Estado</th></tr></thead>
<tbody>
<?php

try {
    // $pdo ya viene definido desde conexion.php

    // Limpiar tablas anteriores para re-insertar limpio
    // (comenta estas líneas si no quieres borrar datos existentes)
    $pdo->exec('DELETE FROM modulo_tramite');
    $pdo->exec('DELETE FROM modulo');
    $pdo->exec('ALTER TABLE modulo AUTO_INCREMENT = 1');

    $stmtModulo = $pdo->prepare('
        INSERT INTO modulo (nombre, direccion, lat, lng, horario, telefono, url_cita)
        VALUES (:nombre, :direccion, :lat, :lng, :horario, :telefono, :url_cita)
    ');

    $stmtRel = $pdo->prepare('
        INSERT IGNORE INTO modulo_tramite (modulo_id, tram_id) VALUES (:modulo_id, :tram_id)
    ');

    $ok    = 0;
    $error = 0;

    foreach ($modulos as $i => [$nombre, $dir, $horario, $tel, $url, $tram_ids]) {

        usleep(DELAY_MS * 1000); // respetar límite de rate

        $coords = geocodificar($dir);

        if (!$coords) {
            echo "<tr><td>" . ($i+1) . "</td><td>$nombre</td><td>$dir</td>
                  <td>-</td><td>-</td>
                  <td class='err'>❌ No geocodificado</td></tr>\n";
            flush();
            $error++;
            continue;
        }

        // Insertar módulo
        $stmtModulo->execute([
            ':nombre'    => $nombre,
            ':direccion' => $dir,
            ':lat'       => $coords['lat'],
            ':lng'       => $coords['lng'],
            ':horario'   => $horario,
            ':telefono'  => $tel,
            ':url_cita'  => $url,
        ]);
        $moduloId = (int) $pdo->lastInsertId();

        // Insertar relaciones con trámites
        foreach ($tram_ids as $tramId) {
            $stmtRel->execute([':modulo_id' => $moduloId, ':tram_id' => $tramId]);
        }

        echo "<tr>
            <td>" . ($i+1) . "</td>
            <td><b>$nombre</b></td>
            <td style='font-size:.78rem;color:#6b7280'>$dir</td>
            <td class='ok'>{$coords['lat']}</td>
            <td class='ok'>{$coords['lng']}</td>
            <td class='ok'>✅ Insertado (ID: $moduloId)</td>
        </tr>\n";
        flush();
        $ok++;
    }

} catch (Exception $e) {
    echo "<tr><td colspan='6' class='err'>❌ Error BD: " . $e->getMessage() . "</td></tr>";
}
?>
</tbody>
</table>

<div class="resumen">
    <h3>📊 Resumen</h3>
    <p class="ok">✅ Insertados correctamente: <b><?= $ok ?></b></p>
    <p class="err">❌ Errores de geocodificación: <b><?= $error ?></b></p>
    <p>Total procesados: <b><?= count($modulos) ?></b></p>
    <?php if ($ok > 0): ?>
    <p>✅ <b>¡Listo!</b> Ahora prueba el mapa en:
       <a href="https://jovenes-tramites.vercel.app/mapa-tramites?tram_id=1&tram_tip=INE" target="_blank">
       localhost:4200/mapa-tramites?tram_id=1</a>
    </p>
    <?php endif; ?>
</div>
</body>
</html>
