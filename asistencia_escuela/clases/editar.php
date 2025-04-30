<?php
include('../includes/conexion.php');
$id = $_GET['id'];
$resultado = mysqli_query($conn, "SELECT * FROM clases WHERE id = $id");
$clase = mysqli_fetch_assoc($resultado);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre_clase'];
    $fecha = $_POST['fecha'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    mysqli_query($conn, "UPDATE clases SET nombre_clase='$nombre', fecha='$fecha', hora_inicio='$hora_inicio', hora_fin='$hora_fin' WHERE id=$id");
    header("Location: listar.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Clase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            padding-top: 80px;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <?php include('../includes/navbar.php'); ?>
    
    <div class="container">
        <div class="card shadow form-container">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0"><i class="bi bi-journal-text"></i> Editar Clase</h2>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="nombre_clase" class="form-label">Nombre de la Clase</label>
                        <input type="text" class="form-control" id="nombre_clase" name="nombre_clase" 
                               value="<?= htmlspecialchars($clase['nombre_clase']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" 
                               value="<?= $clase['fecha'] ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="hora_inicio" class="form-label">Hora de Inicio</label>
                            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" 
                                   value="<?= $clase['hora_inicio'] ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="hora_fin" class="form-label">Hora de Fin</label>
                            <input type="time" class="form-control" id="hora_fin" name="hora_fin" 
                                   value="<?= $clase['hora_fin'] ?>" required>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="listar.php" class="btn btn-secondary me-md-2">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-arrow-repeat"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>