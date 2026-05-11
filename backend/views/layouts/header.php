<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Módulo Coordinador</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        .form-grid {
            display: flex;
            gap: 40px;
            margin-top: 20px;
        }

        .form-left {
            flex: 1;
        }

        .form-right {
            flex: 1;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            color: #555;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            background-color: white;
        }

        .form-group textarea {
            resize: vertical;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            justify-content: center;
        }

        .btn-secondary {
            background-color: #64748b;
        }

        .btn-secondary:hover {
            background-color: #475569;
        }

        /* SIDEBAR */

        .sidebar {
            width: 250px;
            background-color: #1e293b;
            padding: 20px;
        }

        .sidebar h2 {
            color: white;
            margin-bottom: 30px;
            font-size: 22px;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .menu a {
            background-color: #334155;
            color: white;
            text-decoration: none;
            padding: 15px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .menu a:hover {
            background-color: #2563eb;
        }

        .back-button {
            margin-top: 250px;
        }

        .back-button a {
            display: block;
            background-color: #dc2626;
            color: white;
            text-decoration: none;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            transition: 0.3s;
        }

        .back-button a:hover {
            background-color: #b91c1c;
        }

        /* CONTENIDO */

        .content {
            flex: 1;
            padding: 30px;
        }

        .content h1 {
            margin-bottom: 20px;
        }

        /* TABLA */

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }

        table th {
            background-color: #e2e8f0;
        }

        /* BOTONES */

        .btn {
            display: inline-block;
            margin-bottom: 20px;
            background-color: #2563eb;
            color: white;
            padding: 12px 18px;
            text-decoration: none;
            border-radius: 8px;
        }

        .btn:hover {
            background-color: #1d4ed8;
        }

        .editor-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .filtros {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 15px;
        }

        .filtros select {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .proyectos-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .proyecto-card {
            background-color: #b87070;
            border-radius: 10px;
            padding: 15px;
            width: 220px;
            color: white;
        }

        .card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .btn-eliminar {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: white;
        }

        .btn-editar {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            background-color: #7a4a4a;
            color: white;
            border: none;
            padding: 8px;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-editar:hover {
            background-color: #5a3030;
        }

        .tutor-info {
            font-size: 12px;
            color: #f0d0d0;
        }

        /* FORMULARIO */

        .form-grid {
            display: flex;
            gap: 40px;
            margin-top: 20px;
        }

        .form-left,
        .form-right {
            flex: 1;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            color: #555;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            background-color: white;
        }

        .form-group textarea {
            resize: vertical;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            justify-content: center;
        }

        .btn-secondary {
            background-color: #64748b;
        }

        .btn-secondary:hover {
            background-color: #475569;
        }

        .error-msg {
            display: block;
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
        }

        .input-error {
            border: 1px solid #dc2626 !important;
            background-color: #fff5f5;
        }

        /* MODAL */

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-box {
            background: white;
            border-radius: 12px;
            padding: 30px;
            width: 700px;
            max-width: 95%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .modal-box h2 {
            margin-bottom: 20px;
            font-size: 22px;
        }

        /* Tarjetas de estadísticas dentro del modal */

        .modal-stats {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 15px 25px;
            flex: 1;
        }

        .stat-icon {
            font-size: 30px;
        }

        .stat-card small {
            display: block;
            color: #64748b;
            font-size: 12px;
        }

        .stat-card strong {
            font-size: 28px;
            color: #1e293b;
        }

        /* Grid del formulario dentro del modal */

        .modal-form-grid {
            display: flex;
            gap: 30px;
            margin-bottom: 10px;
        }

        .modal-left,
        .modal-right {
            flex: 1;
        }

        .btn-warning {
            background-color: #d97706;
            margin-top: 8px;
        }

        .btn-warning:hover {
            background-color: #b45309;
        }

        .modal-cerrar-x {
            position: absolute;
            top: 15px;
            right: 20px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #64748b;
            line-height: 1;
        }

        .modal-cerrar-x:hover {
            color: #dc2626;
        }

        /* El modal-box necesita position relative para que la X se posicione bien */
        .modal-box {
            position: relative;
        }

        /* SOLICITUDES */

        .solicitudes-lista {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        .solicitud-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 15px 20px;
        }

        .solicitud-procesada {
            opacity: 0.6;
        }

        .solicitud-info strong {
            font-size: 15px;
        }

        .solicitud-info p {
            margin: 4px 0 0;
            font-size: 13px;
            color: #64748b;
        }

        .solicitud-acciones {
            display: flex;
            gap: 10px;
        }

        .btn-aceptar {
            background-color: #16a34a;
        }

        .btn-aceptar:hover {
            background-color: #15803d;
        }

        .btn-denegar {
            background-color: #dc2626;
        }

        .btn-denegar:hover {
            background-color: #b91c1c;
        }

        .badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .badge-aceptado {
            background: #dcfce7;
            color: #16a34a;
        }

        .badge-denegado {
            background: #fee2e2;
            color: #dc2626;
        }

        .btn-regresar {
            display: inline-block;
            margin-bottom: 15px;
        }
    </style>

</head>

<body>

    <div class="dashboard">

        <!-- SIDEBAR -->

        <aside class="sidebar">

            <h2>Hola, Jean Paul</h2>

            <div class="menu">
                <a href="?page=projects">Lista de proyectos</a>
                <a href="?page=editor">Editor de proyectos</a>
                <a href="?page=solicitudes">Solicitudes</a>
            </div>
            <div class="back-button">
                <a href="#">
                    ← Regresar
                </a>
            </div>

        </aside>

        <!-- CONTENIDO -->
        <main class="content">