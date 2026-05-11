<?php require 'views/layouts/header.php'; ?>

<h1>Solicitudes de cupos</h1>
<div class="solicitudes-lista">

    <?php
    $solicitudes = $_SESSION['solicitudes'] ?? [];
    $pendientes  = array_filter($solicitudes, fn($s) => $s['estado'] === 'pendiente');

    if (empty($pendientes)): ?>
        <p style="color:#888;">No hay solicitudes pendientes.</p>
    <?php else: ?>
        <?php foreach ($pendientes as $s): ?>
            <div class="solicitud-card">
                <div class="solicitud-info">
                    <strong><?= htmlspecialchars($s['estudiante']) ?></strong>
                    <p>
                        El estudiante <strong><?= htmlspecialchars($s['estudiante']) ?></strong>
                        solicitó entrar al proyecto
                        <strong><?= htmlspecialchars($s['nombre_proyecto']) ?></strong>
                    </p>
                </div>
                <button
                    class="btn btn-aceptar"
                    onclick="confirmarAccion('aceptar', <?= $s['id'] ?>, '<?= htmlspecialchars($s['estudiante'], ENT_QUOTES) ?>', '<?= htmlspecialchars($s['nombre_proyecto'], ENT_QUOTES) ?>')">
                    Aceptar
                </button>
                <button
                    class="btn btn-denegar"
                    onclick="confirmarAccion('denegar', <?= $s['id'] ?>, '<?= htmlspecialchars($s['estudiante'], ENT_QUOTES) ?>', '<?= htmlspecialchars($s['nombre_proyecto'], ENT_QUOTES) ?>')">
                    Denegar
                </button>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Historial de procesadas -->
    <?php
    $procesadas = array_filter($solicitudes, fn($s) => $s['estado'] !== 'pendiente');
    if (!empty($procesadas)): ?>
        <h2 style="margin-top:30px; font-size:16px; color:#64748b;">Solicitudes procesadas</h2>
        <?php foreach ($procesadas as $s): ?>
            <div class="solicitud-card solicitud-procesada">
                <div class="solicitud-info">
                    <strong><?= htmlspecialchars($s['estudiante']) ?></strong>
                    <p>
                        El estudiante <strong><?= htmlspecialchars($s['estudiante']) ?></strong>
                        solicitó entrar al proyecto
                        <strong><?= htmlspecialchars($s['nombre_proyecto']) ?></strong>
                    </p>
                </div>
                <div class="solicitud-acciones">
                    <span class="badge badge-<?= $s['estado'] ?>">
                        <?= $s['estado'] === 'aceptado' ? '✓ Aceptado' : '✗ Denegado' ?>
                    </span>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>
<script>
    function confirmarAccion(accion, id, estudiante, proyecto) {
        const mensajes = {
            aceptar: `¿Estás seguro de que quieres aceptar la solicitud de ${estudiante} para el proyecto "${proyecto}"?`,
            denegar: `¿Estás seguro de que quieres denegar la solicitud de ${estudiante} para el proyecto "${proyecto}"?`
        };

        if (confirm(mensajes[accion])) {
            window.location.href = `?page=solicitudes&action=${accion}&id=${id}`;
        }
    }
</script>
<?php require 'views/layouts/footer.php'; ?>