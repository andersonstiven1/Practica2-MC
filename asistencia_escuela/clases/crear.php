<?php
session_start();
include('../includes/conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre_clase'];
    $fecha = $_POST['fecha'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];

    // Validación 1: Hora fin debe ser posterior a hora inicio
    if (strtotime($hora_fin) <= strtotime($hora_inicio)) {
        $_SESSION['mensaje'] = [
            'texto' => "La hora de fin debe ser posterior a la hora de inicio",
            'tipo' => 'danger'
        ];
        $_SESSION['form_data'] = $_POST; // Guardar datos para repoblar el formulario
        header("Location: crear.php");
        exit();
    }

    // Validación 2: No debe haber clases solapadas
    $solapamiento = mysqli_query($conn, 
        "SELECT id FROM clases 
         WHERE fecha = '$fecha' 
         AND (
            (hora_inicio <= '$hora_inicio' AND hora_fin > '$hora_inicio') OR
            (hora_inicio < '$hora_fin' AND hora_fin >= '$hora_fin') OR
            (hora_inicio >= '$hora_inicio' AND hora_fin <= '$hora_fin')
         )");

    if (mysqli_num_rows($solapamiento) > 0) {
        $_SESSION['mensaje'] = [
            'texto' => "Existe un conflicto de horario con otra clase programada",
            'tipo' => 'danger'
        ];
        $_SESSION['form_data'] = $_POST;
        header("Location: crear.php");
        exit();
    }

    // Si pasa las validaciones, proceder con la inserción
    $sql = "INSERT INTO clases (nombre_clase, fecha, hora_inicio, hora_fin) 
            VALUES (?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $nombre, $fecha, $hora_inicio, $hora_fin);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['mensaje'] = [
            'texto' => "Clase agregada correctamente",
            'tipo' => 'success'
        ];
        header("Location: listar.php");
        exit();
    } else {
        $_SESSION['mensaje'] = [
            'texto' => "Error al agregar clase: " . mysqli_error($conn),
            'tipo' => 'danger'
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Clase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 80px; }
        .form-container { max-width: 600px; margin: 0 auto; }
    </style>
</head>
<body>
    <?php include('../includes/navbar.php'); ?>
    
    <div class="container">
        <div class="card shadow form-container">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0"><i class="bi bi-journal-plus"></i> Agregar Nueva Clase</h2>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['mensaje'])): ?>
                    <div class="alert alert-<?= $_SESSION['mensaje']['tipo'] ?>">
                        <?= $_SESSION['mensaje']['texto'] ?>
                    </div>
                    <?php unset($_SESSION['mensaje']); ?>
                <?php endif; ?>
                
                <form method="post">
                    <div class="mb-3">
                        <label for="nombre_clase" class="form-label">Nombre de la Clase</label>
                        <input type="text" class="form-control" id="nombre_clase" name="nombre_clase" required
                               value="<?= isset($_SESSION['form_data']['nombre_clase']) ? htmlspecialchars($_SESSION['form_data']['nombre_clase']) : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" required
                               min="<?= date('Y-m-d') ?>" 
                               value="<?= isset($_SESSION['form_data']['fecha']) ? $_SESSION['form_data']['fecha'] : '' ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="hora_inicio" class="form-label">Hora de Inicio</label>
                            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required
                                   value="<?= isset($_SESSION['form_data']['hora_inicio']) ? $_SESSION['form_data']['hora_inicio'] : '08:00' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="hora_fin" class="form-label">Hora de Fin</label>
                            <input type="time" class="form-control" id="hora_fin" name="hora_fin" required
                                   value="<?= isset($_SESSION['form_data']['hora_fin']) ? $_SESSION['form_data']['hora_fin'] : '09:00' ?>">
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="listar.php" class="btn btn-secondary me-md-2">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Clase
                        </button>
                    </div>
                </form>
                <?php unset($_SESSION['form_data']); ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación en cliente para mejor experiencia de usuario
        document.querySelector('form').addEventListener('submit', function(e) {
            const inicio = document.getElementById('hora_inicio').value;
            const fin = document.getElementById('hora_fin').value;
            
            if (inicio >= fin) {
                e.preventDefault();
                alert('La hora de fin debe ser posterior a la hora de inicio');
            }
        });
    </script>
</body>
</html>