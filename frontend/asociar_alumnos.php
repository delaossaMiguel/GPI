<?php
require_once '../bbdd/conexion.php';
session_start(); 
$conexion = conexion();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asociar Alumnos</title>
</head>
<body>
    <h1>Asociar Alumnos al Examen</h1>

    <?php
    if (isset($_GET['nombreExamen'])) {
        $nombreExamen = $_GET['nombreExamen'];
    } else {
        echo '<p>No se ha especificado un examen.</p>';
        exit();
    }

    // Obtener el ID del examen
    $consultaExamen = $conexion->prepare("SELECT id_examen FROM examen WHERE nombre = ?");
    $consultaExamen->bind_param('s', $nombreExamen);
    $consultaExamen->execute();
    $resultadoExamen = $consultaExamen->get_result();
    $examen = $resultadoExamen->fetch_assoc();

    if (!$examen) {
        echo '<p>Examen no encontrado.</p>';
        exit();
    }

    $idExamen = $examen['id_examen'];

    // Procesar el formulario cuando se envía
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alumnos'])) {
        $alumnosSeleccionados = $_POST['alumnos'];

        // Insertar en la base de datos la asociación de los alumnos con el examen
        $consultaInsert = $conexion->prepare("INSERT INTO examen_alumno (id_examen, id_usuario) VALUES (?, ?)");

        foreach ($alumnosSeleccionados as $idAlumno) {
            $consultaInsert->bind_param('ii', $idExamen, $idAlumno);
            $consultaInsert->execute();
        }

        echo '<p>Alumnos asociados al examen con éxito.</p>';
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo '<p>No se seleccionaron alumnos.</p>';
    }

    // Consultar los alumnos disponibles
    $consultaAlumnos = $conexion->query("SELECT id_usuario, nombre, apellido1, apellido2 FROM usuario WHERE rol='Alumno'");
    $AlumnosDisponibles = $consultaAlumnos->fetch_all(MYSQLI_ASSOC);
    ?>

    <h2>Asociar Alumnos a <?php echo $nombreExamen ?></h2>
    <form action="" method="POST">
        <input type="hidden" name="action" value="associate">
        <h3>Alumnos</h3>
        <?php foreach ($AlumnosDisponibles as $alumno): ?>
            <label>
                <input type="checkbox" name="alumnos[]" value="<?php echo $alumno['id_usuario']; ?>">
                <?php echo $alumno['nombre'] . ' ' . $alumno['apellido1'] . ' ' . $alumno['apellido2']; ?>
            </label><br>
        <?php endforeach; ?>
        <br>
        <button type="submit">Asociar alumnos</button>
    </form>

    <a href="examenes_profesor.php">Volver a la gestión de exámenes</a>

    <?php
    $conexion->close();
    ?>
</body>
</html>
