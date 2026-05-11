<?php

session_start();

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

$page   = $_GET['page']   ?? 'projects';
$action = $_GET['action'] ?? 'index';

if ($page === 'projects') {

    if ($action === 'create') {
        require 'views/projects/formulario1.php';

    } elseif ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {

        $nuevoProyecto = [
            'id'           => count($_SESSION['proyectos']) + 1,
            'nombre'       => $_POST['nombre'],
            'descripcion'  => $_POST['descripcion'],
            'tutor'        => $_POST['tutor'],
            'facultad'     => $_POST['facultad'],
            'carrera'      => $_POST['carrera'],
            'cupos_max'    => min((int)$_POST['cupos'], 60), // nunca más de 60
            'cupos_usados' => 0,
        ];

        $_SESSION['proyectos'][] = $nuevoProyecto;
        header('Location: ?page=editor');
        exit;

    } else {
        require 'views/projects/index.php';
    }

} elseif ($page === 'editor') {

    // FUTURO: ProyectoController::update()
    if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)$_POST['id'];

        foreach ($_SESSION['proyectos'] as &$p) {
            if ($p['id'] === $id) {
                $p['nombre']      = $_POST['nombre'];
                $p['descripcion'] = $_POST['descripcion'];
                // Solo actualiza tutor si se envió uno nuevo
                if (!empty($_POST['tutor'])) {
                    $p['tutor'] = $_POST['tutor'];
                }
                break;
            }
        }
        unset($p); // buena práctica: liberar referencia del foreach

        header('Location: ?page=editor');
        exit;

    // FUTURO: ProyectoController::delete()
    } elseif ($action === 'delete') {
        $id = (int)$_GET['id'];

        // Filtramos fuera el proyecto con ese id
        // FUTURO: DELETE FROM proyectos WHERE id = ?
        $_SESSION['proyectos'] = array_values(
            array_filter($_SESSION['proyectos'], fn($p) => $p['id'] !== $id)
        );

        header('Location: ?page=editor');
        exit;

    } else {
        require 'views/projects/editor.php';
    }
}