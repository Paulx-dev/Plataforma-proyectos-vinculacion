<?php require 'views/layouts/header.php'; ?>

<h1>Lista de Proyectos</h1>

<a href="?page=projects&action=create" class="btn">
    Nuevo Proyecto
</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Proyecto</th>
            <th>Descripción</th>
            <th>Tutor</th>
            <th>Facultad</th>
            <th>Carrera</th>
            <th>Cupos</th>
        </tr>
    </thead>

    <tbody>
        <?php
        // FUTURO: $proyectos vendrá de Proyecto::getAll() que hará SELECT * FROM proyectos
        $proyectos = $_SESSION['proyectos'] ?? [];

        if (empty($proyectos)): ?>
            <tr>
                <td colspan="7" style="text-align:center; color:#888;">
                    No hay proyectos registrados aún.
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($proyectos as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                    <td><?= htmlspecialchars($p['descripcion']) ?></td>
                    <td><?= htmlspecialchars($p['tutor']) ?></td>
                    <td><?= htmlspecialchars($p['facultad']) ?></td>
                    <td><?= htmlspecialchars($p['carrera']) ?></td>
                    <td><?= $p['cupos_usados'] ?>/<?= $p['cupos_max'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php require 'views/layouts/footer.php'; ?>