<?php require 'views/layouts/header.php'; ?>
<!-- Regresa directamente a lista de proyectos -->
<a href="?page=projects" class="btn btn-secondary btn-regresar">← Regresar</a>

<h1>Creación de proyectos</h1>

<form id="formProyecto" method="POST" action="?page=projects&action=store" onsubmit="return validarFormulario()">
    <div class="form-grid">

        <div class="form-left">

            <div class="form-group">
                <label for="nombre">Nombre del proyecto</label>
                <input type="text" id="nombre" name="nombre" oninput="limpiarEntradaNombre(this)">
                <span class="error-msg" id="errorNombre"></span>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="5"></textarea>
                <span class="error-msg" id="errorDescripcion"></span>
            </div>

        </div>

        <div class="form-right">

            <div class="form-group">
                <label for="facultad">Facultad</label>
                <select id="facultad" name="facultad">
                    <option value="">-- Seleccionar --</option>
                    <option value="Ciencias Matemáticas y Físicas">Ciencias Matemáticas y Físicas</option>
                    <option value="Medicina">Medicina</option>
                    <option value="Jurisprudencia">Jurisprudencia</option>
                </select>
                <span class="error-msg" id="errorFacultad"></span>
            </div>

            <div class="form-group">
                <label for="carrera">Carrera</label>
                <select id="carrera" name="carrera">
                    <option value="">-- Seleccionar --</option>
                    <option value="Software">Software</option>
                    <option value="TIC">TIC</option>
                    <option value="Sistemas">Sistemas</option>
                </select>
                <span class="error-msg" id="errorCarrera"></span>
            </div>

            <div class="form-group">
                <label for="tutor">Tutores disponibles</label>
                <select id="tutor" name="tutor">
                    <option value="">-- Seleccionar --</option>
                    <option value="Ing. Juan Pérez">Ing. Juan Pérez</option>
                    <option value="Ing. María López">Ing. María López</option>
                </select>
                <span class="error-msg" id="errorTutor"></span>
            </div>

            <div class="form-group">
                <label for="cupos">Cupos disponibles</label>
                <input type="number" id="cupos" name="cupos" min="1" max="60">
                <span class="error-msg" id="errorCupos"></span>
            </div>

        </div>

    </div>

    <div class="form-actions">
        <button type="submit" class="btn">Guardar</button>
        <button type="button" class="btn btn-secondary" onclick="confirmarCancelar()">Cancelar</button>
    </div>

</form>

<script>
    // Solo permite letras y espacios mientras escribe
    function limpiarEntradaNombre(input) {
        input.value = input.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
    }

    // Verifica que el nombre tenga sentido (mínimo 3 letras por palabra, no puras letras repetidas)
    function nombreTieneSentido(nombre) {
        const palabras = nombre.trim().split(/\s+/);

        // Mínimo 2 palabras
        if (palabras.length < 2) return false;

        for (let p of palabras) {
            // Cada palabra debe tener al menos 3 letras
            if (p.length < 3) return false;

            // No puede ser una sola letra repetida (aaaa, bbbb)
            if (/^(.)\1+$/.test(p)) return false;
        }

        return true;
    }

    function limpiarErrores() {
        document.querySelectorAll('.error-msg').forEach(e => e.textContent = '');
        document.querySelectorAll('input, select, textarea').forEach(e => e.classList.remove('input-error'));
    }

    function marcarError(campoId, mensajeId, mensaje) {
        document.getElementById(campoId).classList.add('input-error');
        document.getElementById(mensajeId).textContent = mensaje;
    }

    function validarFormulario() {
        limpiarErrores();
        let valido = true;

        const nombre = document.getElementById('nombre').value.trim();
        const descripcion = document.getElementById('descripcion').value.trim();
        const facultad = document.getElementById('facultad').value;
        const carrera = document.getElementById('carrera').value;
        const tutor = document.getElementById('tutor').value;
        const cupos = document.getElementById('cupos').value;

        if (nombre === '') {
            marcarError('nombre', 'errorNombre', 'El nombre del proyecto es obligatorio.');
            valido = false;
        } else if (!nombreTieneSentido(nombre)) {
            marcarError('nombre', 'errorNombre', 'Ingresa un nombre válido con al menos dos palabras significativas.');
            valido = false;
        }

        if (descripcion === '') {
            marcarError('descripcion', 'errorDescripcion', 'La descripción es obligatoria.');
            valido = false;
        }

        if (facultad === '') {
            marcarError('facultad', 'errorFacultad', 'Selecciona una facultad.');
            valido = false;
        }

        if (carrera === '') {
            marcarError('carrera', 'errorCarrera', 'Selecciona una carrera.');
            valido = false;
        }

        if (tutor === '') {
            marcarError('tutor', 'errorTutor', 'Selecciona un tutor.');
            valido = false;
        }

        if (cupos === '' || cupos < 1 || cupos > 60) {
            marcarError('cupos', 'errorCupos', 'Los cupos deben ser entre 1 y 60.');
            valido = false;
        }

        return valido;
    }

    function confirmarCancelar() {
    if (confirm('¿Estás seguro de que quieres cancelar? Se perderán los datos ingresados.')) {
        limpiarFormulario();
    }
}

function limpiarFormulario() {
    document.getElementById('formProyecto').reset();
    limpiarErrores();
}
</script>

<?php require 'views/layouts/footer.php'; ?>