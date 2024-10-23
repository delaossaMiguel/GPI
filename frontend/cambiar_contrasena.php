<?php
require_once '../bbdd/conexion.php';

session_start();
$con = conexion();

if (!isset($_SESSION['rol'])) {
    header('Location: login.php');
    exit();
}

$contrasena_actual = $_POST['contrasena_actual'];
$nueva_contrasena = $_POST['nueva_contrasena'];
$confirmar_contrasena = $_POST['confirmar_contrasena'];

$query = "SELECT passwd FROM usuario WHERE nombre = ? AND apellido1 = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("ss", $_SESSION['nombre'], $_SESSION['apellido']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
        if($contrasena_actual===$row['passwd']) {
        if ($nueva_contrasena === $confirmar_contrasena) {
            $contrasena_hash = password_hash($nueva_contrasena, PASSWORD_BCRYPT);
            
            $update_query = "UPDATE usuario SET passwd = ? WHERE nombre = ? AND apellido1 = ?";
            $update_stmt = $con->prepare($update_query);
            $update_stmt->bind_param("sss", $contrasena_hash, $_SESSION['nombre'], $_SESSION['apellido']);
            
            if ($update_stmt->execute()) {
                echo "Contraseña cambiada con éxito.";
            } else {
                echo "Error al cambiar la contraseña: " . $con->error;
            }
        } else {
            echo "Las contraseñas nuevas no coinciden.";
        }
    } else {
        echo "Contraseña actual incorrecta.";
    }
} else {
    echo "Usuario no encontrado.";
}

$stmt->close();
$update_stmt->close();
$con->close();

switch ($_SESSION['rol']) {
    case 'Administrador':
        $redirect_page = 'admin.php';
        break;
    case 'Profesor':
        $redirect_page = 'profesor.php';
        break;
    case 'Alumno':
        $redirect_page = 'alumno.php';
        break;
    default:
        $redirect_page = 'login.php';
}

echo "<br><a href=\"$redirect_page\">Volver al perfil</a>";
