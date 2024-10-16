<?php
session_start();

// Verifica si el usuario está autenticado y es un administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Administrador</title>
</head>
<body>
    <h1>Bienvenido, <?php echo $_SESSION['nombre'] . ' ' . $_SESSION['apellido']; ?>!</h1>
    <h2>Perfil de Administrador</h2>
    
    <h3>Opciones</h3>
    <ul>
        <li><a href="crear_usuarios.php">Crear Usuario</a></li>
    </ul>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
