<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $grupo = $_POST['grupo'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $rol = $_POST['rol'];

    $nuevoUsuario = "$nombre $apellido $grupo $usuario $contrasena $rol\n";
    file_put_contents('usuarios.txt', $nuevoUsuario, FILE_APPEND);

    echo "<p>Usuario $nombre $apellido agregado con éxito.</p>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - Crear Usuarios</title>
</head>
<body>
    <h1>Crear Usuarios</h1>
    <form action="admin.php" method="POST">
        <label>Nombre:</label>
        <input type="text" name="nombre" required>
        <label>Apellido:</label>
        <input type="text" name="apellido" required>
        <label>Grupo (solo para alumnos):</label>
        <input type="text" name="grupo">
        <label>Usuario:</label>
        <input type="text" name="usuario" required>
        <label>Contraseña:</label>
        <input type="password" name="contrasena" required>
        <label>Rol:</label>
        <select name="rol" required>
            <option value="Alumno">Alumno</option>
            <option value="Profesor">Profesor</option>
        </select>
        <button type="submit">Crear Usuario</button>
    </form>
    <p><a href="login.php">Cerrar Sesión</a></p>
</body>
</html>
