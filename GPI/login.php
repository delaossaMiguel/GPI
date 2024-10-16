<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $usuarios = file('users.txt');
    foreach ($usuarios as $user) {
        list($email, $apellido, $rol, $grupo, $userContrasena, $nombre) = explode(' ', trim($user));

        if ($email === $usuario && $userContrasena === $contrasena) {
            $_SESSION['rol'] = $rol;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['grupo'] = $grupo;
            $_SESSION['apellido'] = $apellido;
            // Redirigir según el rol
            if ($rol === 'Administrador') {
                header('Location: admin.php');
                exit();
            } elseif ($rol === 'Profesor') {
                header('Location: examenes_profesor.php');
                exit();
            } elseif ($rol === 'Alumno') {
                header('Location: examenes_alumno.php');
                exit();
            }
        }
    }
    $error = "Usuario o contraseña incorrectos";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
</head>
<body>
    <h1>Iniciar Sesión</h1>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form action="login.php" method="POST">
        <label>Usuario:</label>
        <input type="text" name="usuario" required>
        <label>Contraseña:</label>
        <input type="password" name="contrasena" required>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
