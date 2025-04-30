<?php
include('../includes/autenticacion.php');
include('../includes/conexion.php');

// Obtener el ID de la asistencia a editar
$id = $_GET['id'];

// Obtener los datos actuales de la asistencia
$asistencia = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM asistencias WHERE id = $id"));

// Obtener listado de estudiantes y clases para los selects
$estudiantes = mysqli_query($conn, "SELECT * FROM estudiantes ORDER BY apellido, nombre");
$clases = mysqli_query($conn, "SELECT * FROM clases ORDER BY fecha DESC, hora_inicio DESC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Procesar el formulario de edición
    $estudiante_id = $_POST['estudiante_id'];
    $clase_id = $_POST['clase_id'];
    $hora_llegada = $_POST['hora_llegada'];
    $presente = $_POST['presente'];

    $sql = "UPDATE asistencias SET 
            estudiante_id = '$estudiante_id',
            clase_id = '$clase_id',
            hora_llegada = '$hora_llegada',
            presente = '$presente'
            WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: ver.php");
        exit();
    } else {
        $error = "Error al actualizar la asistencia: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Asistencia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            padding-top: 80px;
        }
        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <?php include('../includes/navbar.php'); ?>
    
    <div class="container">
        <div class="card shadow form-container">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0"><i class="bi bi-pencil-square"></i> Editar Asistencia</h2>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                
                <form method="post">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="estudiante_id" class="form-label">Estudiante</label>
                            <select class="form-select" id="estudiante_id" name="estudiante_id" required>
                                <?php while ($estudiante = mysqli_fetch_assoc($estudiantes)): ?>
                                    <option value="<?= $estudiante['id'] ?>" <?= $estudiante['id'] == $asistencia['estudiante_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($estudiante['apellido']) ?>, <?= htmlspecialchars($estudiante['nombre']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="clase_id" class="form-label">Clase</label>
                            <select class="form-select" id="clase_id" name="clase_id" required>
                                <?php while ($clase = mysqli_fetch_assoc($clases)): ?>
                                    <option value="<?= $clase['id'] ?>" <?= $clase['id'] == $asistencia['clase_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($clase['nombre_clase']) ?> - 
                                        <?= date('d/m/Y', strtotime($clase['fecha'])) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="hora_llegada" class="form-label">Hora de Llegada</label>
                            <input type="time" class="form-control" id="hora_llegada" name="hora_llegada" 
                                   value="<?= date('H:i', strtotime($asistencia['hora_llegada'])) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="presente" class="form-label">Estado</label>
                            <select class="form-select" id="presente" name="presente" required>
                                <option value="1" <?= $asistencia['presente'] ? 'selected' : '' ?>>Presente</option>
                                <option value="0" <?= !$asistencia['presente'] ? 'selected' : '' ?>>Ausente</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="ver.php" class="btn btn-secondary me-md-2">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>