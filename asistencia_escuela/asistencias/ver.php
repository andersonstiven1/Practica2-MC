<?php
include('../includes/autenticacion.php');
include('../includes/conexion.php');

$asistencias = mysqli_query($conn, "SELECT a.id, e.nombre, e.apellido, c.nombre_clase, c.fecha, a.hora_llegada, a.presente 
                                  FROM asistencias a 
                                  JOIN estudiantes e ON a.estudiante_id = e.id 
                                  JOIN clases c ON a.clase_id = c.id
                                  ORDER BY c.fecha DESC, a.hora_llegada DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Asistencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            padding-top: 80px;
        }
        .table-responsive {
            margin: 20px 0;
        }
        .badge-presente {
            background-color: #28a745;
        }
        .badge-ausente {
            background-color: #dc3545;
        }
        .action-buttons {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <?php include('../includes/navbar.php'); ?>
    
    <div class="container">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0"><i class="bi bi-list-check"></i> Registro de Asistencias</h2>
                <a href="registrar.php" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Nueva Asistencia
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Estudiante</th>
                                <th>Clase</th>
                                <th>Fecha</th>
                                <th>Hora Llegada</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($asistencia = mysqli_fetch_assoc($asistencias)): ?>
                            <tr>
                                <td><?= $asistencia['id'] ?></td>
                                <td><?= htmlspecialchars($asistencia['nombre']) ?> <?= htmlspecialchars($asistencia['apellido']) ?></td>
                                <td><?= htmlspecialchars($asistencia['nombre_clase']) ?></td>
                                <td><?= date('d/m/Y', strtotime($asistencia['fecha'])) ?></td>
                                <td><?= date('H:i', strtotime($asistencia['hora_llegada'])) ?></td>
                                <td>
                                    <span class="badge <?= $asistencia['presente'] ? 'bg-success' : 'bg-danger' ?>">
                                        <?= $asistencia['presente'] ? 'Presente' : 'Ausente' ?>
                                    </span>
                                </td>
                                <td class="action-buttons">
                                    <a href="editar.php?id=<?= $asistencia['id'] ?>" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-square"></i> Editar
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