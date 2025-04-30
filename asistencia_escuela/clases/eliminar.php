<?php
include('../includes/conexion.php');
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM clases WHERE id=$id");
header("Location: listar.php");
mysqli_close($conn);
?>