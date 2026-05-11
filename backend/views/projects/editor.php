<?php require 'views/layouts/header.php'; ?>

<div class="editor-header">
    <h1>Editor de proyectos</h1>
    <div class="filtros">
        <strong>Filtros ☰</strong>
        <select id="filtroCarrera" onchange="filtrarProyectos()">
            <option value="">Todas las carreras</option>
            <option value="Software">Software</option>
            <option value="TIC">TIC</option>
            <option value="Sistemas">Sistemas</option>
        </select>
    </div>
</div>

<div class="proyectos-grid" id="proyectosGrid">
    <?php
    $proyectos = $_SESSION['proyectos'] ?? [];

    if (empty($proyectos)): ?>
        <p style="color:#888;">No hay proyectos registrados aún.</p>
    <?php else: ?>
        <?php foreach ($proyectos as $p): ?>
            <div class="proyecto-card" data-carrera="<?= htmlspecialchars($p['carrera']) ?>">
                <div class="card-top">
                    <strong><?= htmlspecialchars($p['nombre']) ?></strong>
                    <button
                        class="btn-eliminar"
                        title="Eliminar"
                        onclick="confirmarEliminar(<?= $p['id'] ?>, '<?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?>')">
                        🗑
                    </button>
                </div>
                <button
                    class="btn btn-editar"
                    onclick="abrirEditor(
                        <?= $p['id'] ?>,
                        '<?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?>',
                        '<?= htmlspecialchars($p['descripcion'], ENT_QUOTES) ?>',
                        '<?= htmlspecialchars($p['tutor'], ENT_QUOTES) ?>',
                        <?= $p['cupos_usados'] ?>,
                        <?= $p['cupos_max'] ?>
                    )">
                    Editar
                </button>
                <p class="tutor-info">tutor asignado: <?= htmlspecialchars($p['tutor']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- ===== MODAL DE EDICIÓN ===== -->
<div id="modalEditar" class="modal-overlay" style="display:none;">
    <div class="modal-box">

        <!-- X arriba a la izquierda: cierra el modal completamente -->
        <button class="modal-cerrar-x" onclick="cerrarModal()">✕</button>

        <h2 id="modalTitulo"></h2>

        <!-- Tarjetas de estadísticas -->
        <div class="modal-stats">
            <div class="stat-card">
                <span class="stat-icon">👤</span>
                <div>
                    <small>Estudiantes actuales</small>
                    <strong id="statEstudiantes"></strong>
                </div>
            </div>
            <div class="stat-card">
                <span class="stat-icon">📋</span>
                <div>
                    <small>Cupos Restantes</small>
                    <strong id="statCupos"></strong>
                </div>
            </div>
        </div>

        <!-- Formulario de edición -->
        <form method="POST" action="?page=editor&action=update">
            <input type="hidden" id="editId" name="id">

            <div class="modal-form-grid">
                <div class="modal-left">
                    <div class="form-group">
                        <label for="editNombre">Nombre del proyecto</label>
                        <input type="text" id="editNombre" name="nombre">
                    </div>
                    <div class="form-group">
                        <label for="editDescripcion">Descripción</label>
                        <textarea id="editDescripcion" name="descripcion" rows="4"></textarea>
                    </div>
                </div>

                <div class="modal-right">
                    <div class="form-group">
                        <label for="editTutor">Tutor</label>
                        <!-- Bloqueado por defecto, se desbloquea con Cambiar tutor -->
                        <select id="editTutor" name="tutor" disabled>
                            <option value="Ing. Juan Pérez">Ing. Juan Pérez</option>
                            <option value="Ing. María López">Ing. María López</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-warning" onclick="habilitarTutor()">
                        Cambiar tutor
                    </button>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Guardar</button>
                <!-- Cancelar abajo: revierte cambios pero deja el modal abierto -->
                <button type="button" class="btn btn-secondary" onclick="revertirCambios()">Cancelar</button>
            </div>
        </form>

    </div>
</div>

<script>
    let _originalNombre = '';
    let _originalDescripcion = '';
    let _originalTutor = '';

    function abrirEditor(id, nombre, descripcion, tutor, usados, max) {
        // Guardamos originales ANTES de cargar en el form
        _originalNombre = nombre;
        _originalDescripcion = descripcion;
        _originalTutor = tutor;

        document.getElementById('modalTitulo').textContent = nombre;
        document.getElementById('editId').value = id;
        document.getElementById('editNombre').value = nombre;
        document.getElementById('editDescripcion').value = descripcion;
        document.getElementById('statEstudiantes').textContent = usados;
        document.getElementById('statCupos').textContent = max - usados;

        const selectTutor = document.getElementById('editTutor');
        selectTutor.value = tutor;
        selectTutor.disabled = true;

        document.getElementById('modalEditar').style.display = 'flex';
    }

    // X arriba: solo cierra, sin tocar nada
    function cerrarModal() {
        document.getElementById('modalEditar').style.display = 'none';
    }

    // Cancelar abajo: revierte campos al estado original, modal queda abierto
    function revertirCambios() {
        if (confirm('¿Estás seguro de que quieres cancelar? Se perderán los cambios realizados.')) {
            document.getElementById('editNombre').value = _originalNombre;
            document.getElementById('editDescripcion').value = _originalDescripcion;

            const selectTutor = document.getElementById('editTutor');
            selectTutor.value = _originalTutor;
            selectTutor.disabled = true;
        }
        // Si el usuario dice No, el modal queda abierto con los cambios intactos
    }

    function habilitarTutor() {
        document.getElementById('editTutor').disabled = false;
    }

    function filtrarProyectos() {
        const filtro = document.getElementById('filtroCarrera').value;
        document.querySelectorAll('.proyecto-card').forEach(card => {
            card.style.display =
                (filtro === '' || card.dataset.carrera === filtro) ? 'block' : 'none';
        });
    }

    function confirmarEliminar(id, nombre) {
        if (confirm('¿Estás seguro de que quieres eliminar el proyecto "' + nombre + '"?')) {
            window.location.href = '?page=editor&action=delete&id=' + id;
        }
    }
</script>

<?php require 'views/layouts/footer.php'; ?>