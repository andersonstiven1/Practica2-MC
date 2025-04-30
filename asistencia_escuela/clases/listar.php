<?php
include('../includes/autenticacion.php');
include('../includes/conexion.php');

$resultado = mysqli_query($conn, "SELECT * FROM clases ORDER BY fecha DESC, hora_inicio ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clases</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            padding-top: 80px;
        }
        .table-responsive {
            margin: 20px 0;
        }
        .badge-horario {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>
<body>
    <?php include('../includes/navbar.php'); ?>
    
    <div class="container">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0"><i class="bi bi-journal-text"></i> Gestión de Clases</h2>
                <a href="crear.php" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Agregar Clase
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Fecha</th>
                                <th>Horario</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                            <tr>
                                <td><?= $fila['id'] ?></td>
                                <td><?= htmlspecialchars($fila['nombre_clase']) ?></td>
                                <td><?= date('d/m/Y', strtotime($fila['fecha'])) ?></td>
                                <td>
                                    <span class="badge badge-horario rounded-pill">
                                        <?= date('H:i', strtotime($fila['hora_inicio'])) ?> - <?= date('H:i', strtotime($fila['hora_fin'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="editar.php?id=<?= $fila['id'] ?>" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </a>
                                    <a href="eliminar.php?id=<?= $fila['id'] ?>" class="btn btn-danger btn-sm" 
                                       onclick="return confirm('¿Estás seguro de eliminar esta clase?')">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <a href="../dashboard.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Panel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>