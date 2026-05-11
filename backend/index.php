<?php
/**
 * ====================================================================
 * API REST - Plataforma de Proyectos de Vinculación
 * --------------------------------------------------------------------
 * Almacena los datos en archivos JSON en disco, así NO dependemos de
 * cookies de sesión (que fallan con cross-origin en php -S).
 *
 * FUTURO: cuando se conecte MySQL, los $datos vendrán de SQL.
 * ====================================================================
 */

// ─── CORS ─────────────────────────────────────────────────────────────────────
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ─── ALMACENAMIENTO EN ARCHIVOS JSON ──────────────────────────────────────────
// Los datos se guardan al lado de index.php
$rutaProyectos   = __DIR__ . '/data_proyectos.json';
$rutaSolicitudes = __DIR__ . '/data_solicitudes.json';

/** Lee un archivo JSON o devuelve los datos por defecto si no existe */
function leerJson(string $ruta, array $porDefecto): array {
    if (!file_exists($ruta)) {
        file_put_contents($ruta, json_encode($porDefecto, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        return $porDefecto;
    }
    $contenido = file_get_contents($ruta);
    return json_decode($contenido, true) ?? $porDefecto;
}

/** Guarda los datos en un archivo JSON */
function guardarJson(string $ruta, array $datos): void {
    file_put_contents($ruta, json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

// Datos iniciales (se crean solo la primera vez)
$proyectosPorDefecto = [
    [
        'id'           => 1,
        'nombre'       => 'Alfabetización Digital',
        'descripcion'  => 'Proyecto de apoyo digital a comunidades rurales.',
        'tutor'        => 'Ing. Juan Pérez',
        'facultad'     => 'Ciencias Matemáticas y Físicas',
        'carrera'      => 'Software',
        'cupos_max'    => 30,
        'cupos_usados' => 25,
    ],
    [
        'id'           => 2,
        'nombre'       => 'Salud Comunitaria Web',
        'descripcion'  => 'Plataforma web para gestión de salud comunitaria.',
        'tutor'        => 'Ing. María López',
        'facultad'     => 'Ciencias Matemáticas y Físicas',
        'carrera'      => 'TIC',
        'cupos_max'    => 25,
        'cupos_usados' => 18,
    ],
];

$solicitudesPorDefecto = [
    [
        'id' => 1, 'estudiante' => 'Carlos Mendoza',
        'id_proyecto' => 1, 'nombre_proyecto' => 'Alfabetización Digital',
        'estado' => 'pendiente',
    ],
    [
        'id' => 2, 'estudiante' => 'Ana Torres',
        'id_proyecto' => 2, 'nombre_proyecto' => 'Salud Comunitaria Web',
        'estado' => 'pendiente',
    ],
    [
        'id' => 3, 'estudiante' => 'Luis Ramírez',
        'id_proyecto' => 1, 'nombre_proyecto' => 'Alfabetización Digital',
        'estado' => 'pendiente',
    ],
];

$proyectos   = leerJson($rutaProyectos,   $proyectosPorDefecto);
$solicitudes = leerJson($rutaSolicitudes, $solicitudesPorDefecto);

// ─── HELPERS ──────────────────────────────────────────────────────────────────

function jsonResponse($data, int $status = 200): void {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function getJsonBody(): array {
    $raw = file_get_contents('php://input');
    return json_decode($raw, true) ?? [];
}

// ─── ENRUTADO ─────────────────────────────────────────────────────────────────
$recurso = $_GET['recurso'] ?? '';
$accion  = $_GET['accion']  ?? '';
$id      = isset($_GET['id']) ? (int)$_GET['id'] : null;
$metodo  = $_SERVER['REQUEST_METHOD'];

// ─── PROYECTOS ────────────────────────────────────────────────────────────────
if ($recurso === 'proyectos') {

    // GET → lista
    if ($metodo === 'GET' && $accion === '') {
        jsonResponse($proyectos);
    }

    // POST → crear
    if ($metodo === 'POST' && $accion === '') {
        $data = getJsonBody();

        // Calcula el siguiente id de forma segura
        $nuevoId = 1;
        if (!empty($proyectos)) {
            $nuevoId = max(array_column($proyectos, 'id')) + 1;
        }

        $nuevoProyecto = [
            'id'           => $nuevoId,
            'nombre'       => $data['nombre']      ?? '',
            'descripcion'  => $data['descripcion'] ?? '',
            'tutor'        => $data['tutor']       ?? '',
            'facultad'     => $data['facultad']    ?? '',
            'carrera'      => $data['carrera']     ?? '',
            'cupos_max'    => min((int)($data['cupos_max'] ?? 0), 60),
            'cupos_usados' => 0,
        ];

        $proyectos[] = $nuevoProyecto;
        guardarJson($rutaProyectos, $proyectos);

        jsonResponse($nuevoProyecto, 201);
    }

    // PUT → actualizar
    if ($metodo === 'PUT' && $id !== null) {
        $data = getJsonBody();

        foreach ($proyectos as &$p) {
            if ($p['id'] === $id) {
                $p['nombre']      = $data['nombre']      ?? $p['nombre'];
                $p['descripcion'] = $data['descripcion'] ?? $p['descripcion'];
                if (!empty($data['tutor'])) {
                    $p['tutor'] = $data['tutor'];
                }
                guardarJson($rutaProyectos, $proyectos);
                jsonResponse($p);
            }
        }
        unset($p);

        jsonResponse(['error' => 'Proyecto no encontrado'], 404);
    }

    // DELETE → eliminar
    if ($metodo === 'DELETE' && $id !== null) {
        $proyectos = array_values(
            array_filter($proyectos, fn($p) => $p['id'] !== $id)
        );
        guardarJson($rutaProyectos, $proyectos);
        jsonResponse(['ok' => true]);
    }
}

// ─── SOLICITUDES ──────────────────────────────────────────────────────────────
if ($recurso === 'solicitudes') {

    if ($metodo === 'GET' && $accion === '') {
        jsonResponse($solicitudes);
    }

    if ($metodo === 'PUT' && $id !== null) {
        $nuevoEstado = $accion === 'aceptar' ? 'aceptado' :
                       ($accion === 'denegar' ? 'denegado' : null);

        if ($nuevoEstado === null) {
            jsonResponse(['error' => 'Acción inválida'], 400);
        }

        foreach ($solicitudes as &$s) {
            if ($s['id'] === $id) {
                $s['estado'] = $nuevoEstado;

                if ($nuevoEstado === 'aceptado') {
                    foreach ($proyectos as &$p) {
                        if ($p['id'] === $s['id_proyecto']) {
                            $p['cupos_usados']++;
                            break;
                        }
                    }
                    unset($p);
                    guardarJson($rutaProyectos, $proyectos);
                }

                guardarJson($rutaSolicitudes, $solicitudes);
                jsonResponse($s);
            }
        }
        unset($s);

        jsonResponse(['error' => 'Solicitud no encontrada'], 404);
    }

    if ($metodo === 'POST' && $accion === 'reset') {
        // Borra los archivos para que se regeneren con los datos por defecto
        if (file_exists($rutaSolicitudes)) unlink($rutaSolicitudes);
        jsonResponse(['ok' => true, 'mensaje' => 'Solicitudes reseteadas']);
    }
}

jsonResponse(['error' => 'Endpoint no encontrado', 'recurso' => $recurso, 'metodo' => $metodo], 404);