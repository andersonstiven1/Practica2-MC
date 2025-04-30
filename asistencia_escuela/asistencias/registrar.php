<?php
include('../includes/autenticacion.php');
include('../includes/conexion.php');

$estudiantes = mysqli_query($conn, "SELECT * FROM estudiantes ORDER BY apellido, nombre");
$clases = mysqli_query($conn, "SELECT * FROM clases ORDER BY fecha DESC, hora_inicio DESC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $estudiante_id = $_POST['estudiante_id'];
    $clase_id = $_POST['clase_id'];
    $hora_llegada = $_POST['hora_llegada'];
    $presente = $_POST['presente'];

    $sql = "INSERT INTO asistencias (estudiante_id, clase_id, hora_llegada, presente) 
            VALUES ('$estudiante_id', '$clase_id', '$hora_llegada', '$presente')";
    mysqli_query($conn, $sql);
    header("Location: ver.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Asistencia</title>
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
        .badge-presente {
            background-color: #28a745;
        }
        .badge-ausente {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <?php include('../includes/navbar.php'); ?>
    
    <div class="container">
        <div class="card shadow form-container">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0"><i class="bi bi-clipboard-check"></i> Registrar Asistencia</h2>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="estudiante_id" class="form-label">Estudiante</label>
                            <select class="form-select" id="estudiante_id" name="estudiante_id" required>
                                <option value="" selected disabled>Seleccione un estudiante</option>
                                <?php while ($estudiante = mysqli_fetch_assoc($estudiantes)): ?>
                                    <option value="<?= $estudiante['id'] ?>">
                                        <?= htmlspecialchars($estudiante['apellido']) ?>, <?= htmlspecialchars($estudiante['nombre']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="clase_id" class="form-label">Clase</label>
                            <select class="form-select" id="clase_id" name="clase_id" required>
                                <option value="" selected disabled>Seleccione una clase</option>
                                <?php while ($clase = mysqli_fetch_assoc($clases)): ?>
                                    <option value="<?= $clase['id'] ?>">
                                        <?= htmlspecialchars($clase['nombre_clase']) ?> - 
                                        <?= date('d/m/Y', strtotime($clase['fecha'])) ?> 
                                        (<?= date('H:i', strtotime($clase['hora_inicio'])) ?>-<?= date('H:i', strtotime($clase['hora_fin'])) ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="hora_llegada" class="form-label">Hora de Llegada</label>
                            <input type="time" class="form-control" id="hora_llegada" name="hora_llegada" required>
                        </div>
                        <div class="col-md-6">
                            <label for="presente" class="form-label">Estado</label>
                            <select class="form-select" id="presente" name="presente" required>
                                <option value="1">Presente</option>
                                <option value="0">Ausente</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="ver.php" class="btn btn-secondary me-md-2">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Registrar Asistencia
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>