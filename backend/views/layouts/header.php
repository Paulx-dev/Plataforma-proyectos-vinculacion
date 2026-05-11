<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Módulo Coordinador</title>

    <style>

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body{
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .dashboard{
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */

        .sidebar{
            width: 250px;
            background-color: #1e293b;
            padding: 20px;
        }

        .sidebar h2{
            color: white;
            margin-bottom: 30px;
            font-size: 22px;
        }

        .menu{
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .menu a{
            background-color: #334155;
            color: white;
            text-decoration: none;
            padding: 15px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .menu a:hover{
            background-color: #2563eb;
        }

        .back-button{
            margin-top: 250px;
        }

        .back-button a{
            display: block;
            background-color: #dc2626;
            color: white;
            text-decoration: none;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            transition: 0.3s;
        }

        .back-button a:hover{
            background-color: #b91c1c;
        }

        /* CONTENIDO */

        .content{
            flex: 1;
            padding: 30px;
        }

        .content h1{
            margin-bottom: 20px;
        }

        /* TABLA */

        table{
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        table th,
        table td{
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }

        table th{
            background-color: #e2e8f0;
        }

        /* BOTONES */

        .btn{
            display: inline-block;
            margin-bottom: 20px;
            background-color: #2563eb;
            color: white;
            padding: 12px 18px;
            text-decoration: none;
            border-radius: 8px;
        }

        .btn:hover{
            background-color: #1d4ed8;
        }

    </style>

</head>

<body>

<div class="dashboard">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <h2>Hola, Jean Paul</h2>

        <div class="menu">

            <a href="#">Lista de proyectos</a>

            <a href="#">Editor de proyectos</a>

            <a href="#">Solicitudes</a>

    </div>

    <div class="back-button">

        <a href="#">
          ← Regresar
        </a>

    </div>

    </aside>

    <!-- CONTENIDO -->

    <main class="content">