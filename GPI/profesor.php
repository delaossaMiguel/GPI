<?php
session_start();

// Verifica si el usuario está autenticado y es un profesor
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Profesor') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Profesor</title>
</head>
<body>
    <h1>Bienvenido, <?php echo $_SESSION['nombre'] . ' ' . $_SESSION['apellido']; ?>!</h1>
    <h2>Perfil de Profesor</h2>
    <p>Grupo: <?php echo $_SESSION['grupo']; ?></p>

    <h3>Opciones</h3>
    <ul>
        <li><a href="examenes_profesor.php">Ver Exámenes</a></li>
    </ul>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
