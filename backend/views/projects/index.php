<?php require 'views/layouts/header.php'; ?>

<h1>Lista de Proyectos</h1>

<a href="#" class="btn">
    Nuevo Proyecto
</a>

<table>

    <thead>

        <tr>
            <th>ID</th>
            <th>Proyecto</th>
            <th>Tutor</th>
            <th>Facultad</th>
            <th>Carrera</th>
            <th>Cupos</th>
        </tr>

    </thead>

    <tbody>

        <tr>
            <td>1</td>
            <td>Alfabetización Digital</td>
            <td>Ing. Juan Pérez</td>
            <td>Ciencias Matemáticas y Físicas</td>
            <td>Software</td>
            <td>25/30</td>
        </tr>

        <tr>
            <td>2</td>
            <td>Salud Comunitaria Web</td>
            <td>Ing. María López</td>
            <td>Ciencias Matemáticas y Físicas</td>
            <td>TIC</td>
            <td>18/25</td>
        </tr>

    </tbody>

</table>

<?php require 'views/layouts/footer.php'; ?>