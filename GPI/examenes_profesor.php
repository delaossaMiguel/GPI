<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Exámenes</title>
</head>
<body>
    <h1>Gestión de Exámenes</h1>

    <?php
    session_start();
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Profesor') {
        header('Location: login.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        $fileContent = file('examenes.txt');
        $examenes = [];

        foreach ($fileContent as $line) {
            $examenes[] = trim($line);
        }

        if ($_POST['action'] === 'create') {
            $nombreExamen = $_POST['nombreExamen'];
            $grupo = $_POST['grupo'];
            $fechaInicio = $_POST['fechaInicio'];
            $horaInicio = $_POST['horaInicio'];
            $fechaFin = $_POST['fechaFin'];
            $horaFin = $_POST['horaFin'];

            // Guardar el nuevo examen en examenes.txt sin preguntas
            $newExamen = "$nombreExamen $grupo $fechaInicio $horaInicio $fechaFin $horaFin";
            $examenes[] = $newExamen;
            file_put_contents('examenes.txt', implode("\n", $examenes) . "\n");

            echo '<p>Examen creado con éxito. Puedes asociar preguntas a este examen.</p>';
        }
    }

    // Leer los exámenes existentes
    $fileContent = file_get_contents('examenes.txt');
    $examenes = explode("\n", trim($fileContent));
    ?>

    <h2>Exámenes</h2>
    <ul>
        <?php foreach ($examenes as $index => $examen): ?>
            <?php
                list($nombreExamen, $grupo, $fechaInicio, $horaInicio, $fechaFin, $horaFin) = explode(' ', $examen, 7);
            ?>
            <li>
                <strong><?php echo $nombreExamen; ?></strong> - Grupo: <?php echo $grupo; ?> - Inicio: <?php echo "$fechaInicio $horaInicio"; ?> - Fin: <?php echo "$fechaFin $horaFin"; ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="index" value="<?php echo $index; ?>">
                    <button type="button" onclick="window.location.href='asociar_preguntas.php?nombreExamen=<?php echo urlencode($nombreExamen); ?>'">Asociar Preguntas</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Crear Examen</h2>
    <form action="" method="POST">
        <input type="hidden" name="action" value="create">
        <label>Nombre del Examen: <input type="text" name="nombreExamen" required></label><br>
        <label>Grupo: <input type="text" name="grupo" required></label><br>
        <label>Fecha de Inicio: <input type="date" name="fechaInicio" required></label><br>
        <label>Hora de Inicio: <input type="time" name="horaInicio" required></label><br>
        <label>Fecha de Fin: <input type="date" name="fechaFin" required></label><br>
        <label>Hora de Fin: <input type="time" name="horaFin" required></label><br>
        <button type="submit">Crear Examen</button>
    </form>

</body>
</html>
