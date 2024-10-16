<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Profesor') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['nombre'])) {
    $nombreExamen = $_GET['nombre'];

    // Leer el archivo y buscar el examen
    $fileContent = file_get_contents('examenes.txt');
    $examenes = explode("\n", trim($fileContent));
    $examenEncontrado = null;
    foreach ($examenes as $examen) {
        list($nombre, $grupo, $fechaInicio, $horaInicio, $fechaFin, $horaFin, $preguntas) = explode(' ', $examen, 7);
        if ($nombre === $nombreExamen) {
            $examenEncontrado = $examen;
            break;
        }
    }

    if ($examenEncontrado) {
        list($nombre, $grupo, $fechaInicio, $horaInicio, $fechaFin, $horaFin, $preguntas) = explode(' ', $examenEncontrado, 7);
        $preguntasArray = explode(',', $preguntas); // Preguntas separadas por comas
    } else {
        echo "Examen no encontrado.";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoNombre = $_POST['nombre'];
    $nuevoGrupo = $_POST['grupo'];
    $nuevoFechaInicio = $_POST['fechaInicio'];
    $nuevaHoraInicio = $_POST['horaInicio'];
    $nuevoFechaFin = $_POST['fechaFin'];
    $nuevaHoraFin = $_POST['horaFin'];
    $nuevasPreguntas = implode(',', $_POST['preguntas']);

    // Actualizar el examen
    foreach ($examenes as &$examen) {
        if (strpos($examen, $nombreExamen) === 0) {
            $examen = "$nuevoNombre $nuevoGrupo $nuevoFechaInicio $nuevaHoraInicio $nuevoFechaFin $nuevaHoraFin $nuevasPreguntas";
            break;
        }
    }

    file_put_contents('examenes.txt', implode("\n", $examenes) . "\n");
    header('Location: examenes_profesor.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Examen</title>
</head>
<body>
    <h1>Editar Examen: <?php echo $nombreExamen; ?></h1>
    <form method="POST" action="">
        <label>Nombre: <input type="text" name="nombre" value="<?php echo $nombre; ?>" required></label><br>
        <label>Grupo: <input type="text" name="grupo" value="<?php echo $grupo; ?>" required></label><br>
        <label>Fecha de Inicio: <input type="date" name="fechaInicio" value="<?php echo $fechaInicio; ?>" required></label><br>
        <label>Hora de Inicio: <input type="time" name="horaInicio" value="<?php echo $horaInicio; ?>" required></label><br>
        <label>Fecha de Fin: <input type="date" name="fechaFin" value="<?php echo $fechaFin; ?>" required></label><br>
        <label>Hora de Fin: <input type="time" name="horaFin" value="<?php echo $horaFin; ?>" required></label><br>
        <label>Preguntas: <input type="text" name="preguntas[]" value="<?php echo implode(',', $preguntasArray); ?>" required></label><br>
        <button type="button" onclick="agregarPregunta()">Añadir otra pregunta</button><br>
        <button type="submit">Guardar Cambios</button>
    </form>

    <script>
        function agregarPregunta() {
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'preguntas[]';
            input.placeholder = 'Nueva Pregunta';
            document.body.appendChild(input);
        }
    </script>
</body>
</html>
