<?php

session_start();

// Datos iniciales de proyectos
// FUTURO: SELECT * FROM proyectos
if (!isset($_SESSION['proyectos'])) {
    $_SESSION['proyectos'] = [
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
}

// Datos iniciales de solicitudes
// FUTURO: SELECT * FROM solicitudes
if (!isset($_SESSION['solicitudes'])) {
    $_SESSION['solicitudes'] = [
        [
            'id'              => 1,
            'estudiante'      => 'Carlos Mendoza',
            'id_proyecto'     => 1,
            'nombre_proyecto' => 'Alfabetización Digital',
            'estado'          => 'pendiente',
        ],
        [
            'id'              => 2,
            'estudiante'      => 'Ana Torres',
            'id_proyecto'     => 2,
            'nombre_proyecto' => 'Salud Comunitaria Web',
            'estado'          => 'pendiente',
        ],
        [
            'id'              => 3,
            'estudiante'      => 'Luis Ramírez',
            'id_proyecto'     => 1,
            'nombre_proyecto' => 'Alfabetización Digital',
            'estado'          => 'pendiente',
        ],
    ];
}

$page   = $_GET['page']   ?? 'projects';
$action = $_GET['action'] ?? 'index';

// ─── PROYECTOS ────────────────────────────────────────────────────────────────
if ($page === 'projects') {

    if ($action === 'create') {
        require 'views/projects/formulario1.php';

    } elseif ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {

        // FUTURO: INSERT INTO proyectos (nombre, descripcion, tutor, facultad, carrera, cupos_max)
        $nuevoProyecto = [
            'id'           => count($_SESSION['proyectos']) + 1,
            'nombre'       => $_POST['nombre'],
            'descripcion'  => $_POST['descripcion'],
            'tutor'        => $_POST['tutor'],
            'facultad'     => $_POST['facultad'],
            'carrera'      => $_POST['carrera'],
            'cupos_max'    => min((int)$_POST['cupos'], 60), // máximo 60 siempre
            'cupos_usados' => 0,
        ];

        $_SESSION['proyectos'][] = $nuevoProyecto;

        header('Location: ?page=editor');
        exit;

    } else {
        require 'views/projects/index.php';
    }

// ─── EDITOR ───────────────────────────────────────────────────────────────────
} elseif ($page === 'editor') {

    if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {

        $id = (int)$_POST['id'];

        // FUTURO: UPDATE proyectos SET nombre=?, descripcion=?, tutor=? WHERE id=?
        foreach ($_SESSION['proyectos'] as &$p) {
            if ($p['id'] === $id) {
                $p['nombre']      = $_POST['nombre'];
                $p['descripcion'] = $_POST['descripcion'];
                if (!empty($_POST['tutor'])) {
                    $p['tutor'] = $_POST['tutor'];
                }
                break;
            }
        }
        unset($p);

        header('Location: ?page=editor');
        exit;

    } elseif ($action === 'delete') {

        $id = (int)$_GET['id'];

        // FUTURO: DELETE FROM proyectos WHERE id=?
        $_SESSION['proyectos'] = array_values(
            array_filter($_SESSION['proyectos'], fn($p) => $p['id'] !== $id)
        );

        header('Location: ?page=editor');
        exit;

    } else {
        require 'views/projects/editor.php';
    }

// ─── SOLICITUDES ──────────────────────────────────────────────────────────────
} elseif ($page === 'solicitudes') {

    if ($action === 'aceptar') {

        $id = (int)$_GET['id'];

        // FUTURO: UPDATE solicitudes SET estado='aceptado' WHERE id=?
        foreach ($_SESSION['solicitudes'] as &$s) {
            if ($s['id'] === $id) {
                $s['estado'] = 'aceptado';

                // FUTURO: UPDATE proyectos SET cupos_usados = cupos_usados + 1 WHERE id=?
                foreach ($_SESSION['proyectos'] as &$p) {
                    if ($p['id'] === $s['id_proyecto']) {
                        $p['cupos_usados']++;
                        break;
                    }
                }
                unset($p);
                break;
            }
        }
        unset($s);

        header('Location: ?page=solicitudes');
        exit;

    } elseif ($action === 'denegar') {

        $id = (int)$_GET['id'];

        // FUTURO: UPDATE solicitudes SET estado='denegado' WHERE id=?
        foreach ($_SESSION['solicitudes'] as &$s) {
            if ($s['id'] === $id) {
                $s['estado'] = 'denegado';
                break;
            }
        }
        unset($s);

        header('Location: ?page=solicitudes');
        exit;

    } else {
        require 'views/projects/solicitudes.php';
    }
}