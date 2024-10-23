<?php
require_once '../bbdd/conexion.php';
require_once 'mail.php';

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

            // Redirigir al usuario a su página según su rol
            if ($_SESSION['rol'] === 'Administrador') {
                header('Location: ../backend/plantillaModificar.html');
            } elseif ($_SESSION['rol'] === 'Profesor') {
                header('Location: profesor.php');
            } elseif ($_SESSION['rol'] === 'Alumno') {
                header('Location: alumno.php');
            }
            exit();
            /*
            //Generar token
            $token = bin2hex(random_bytes(32));
            $token_expira = time() + 600;

            $_SESSION['token_2fa'] = $token;
            $_SESSION['token_2fa_expira'] = $token_expira;

            $verificacion_link = "http://localhost/g1-7/frontend/verificar.php?token=" . $token;

            $asunto = "Verificación de acceso";
            $mensaje = "Hola $nombre,\n\nPor favor, confirma tu acceso haciendo clic en el siguiente enlace:\n\n$verificacion_link\n\nEste enlace es válido por 10 minutos.";
            
            enviarCorreo('migueeldelaossa@gmail.com', $asunto, $mensaje);

            // Redirigir a una página de mensaje de verificación
            header('Location: verificar_mensaje.php');
            exit();*/
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
