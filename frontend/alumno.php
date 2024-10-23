<?php
    session_start();

    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Alumno') {
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

    <h3>Opciones</h3>
    <ul>
        <li><a href="examenes_alumno.php">Ver Exámenes</a></li>
    </ul>

    <h3>Cambiar Contraseña</h3>
    <form action="cambiar_contrasena.php" method="POST">
        <label>Contraseña Actual:</label>
        <input type="password" name="contrasena_actual" required><br>
        <label>Nueva Contraseña:</label>
        <input type="password" name="nueva_contrasena" required><br>
        <label>Confirmar Nueva Contraseña:</label>
        <input type="password" name="confirmar_contrasena" required><br>
        <button type="submit">Cambiar Contraseña</button>
    </form>

    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>