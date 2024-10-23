<?php
require_once '../bbdd/conexion.php';

session_start();
$con = conexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $query = "SELECT id_usuario, email, apellido1, rol, nombre, passwd FROM usuario WHERE email = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id_usuario, $email, $apellido, $rol, $nombre, $hash);
        $stmt->fetch();

        if (($contrasena)== $hash) {
            $_SESSION['id_usuario'] = $id_usuario; 
            $_SESSION['rol'] = $rol;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['apellido'] = $apellido;

            if ($rol === 'Administrador') {
                header('Location: plantillaModificar.html');
            } elseif ($rol === 'Profesor') {
                header('Location: profesor.php');
            } elseif ($rol === 'Alumno') {
                header('Location: alumno.php');
            }
            exit();
        } else {
            $error = "Usuario o contraseña incorrectos";
        }
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
    $stmt->close();
}
$con->close();
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
