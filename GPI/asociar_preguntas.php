<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asociar Preguntas</title>
</head>
<body>
    <h1>Asociar Preguntas al Examen</h1>

    <?php
    session_start();
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Profesor') {
        header('Location: login.php');
        exit();
    }

    // Obtener el nombre del examen a partir de la URL
    if (isset($_GET['nombreExamen'])) {
        $nombreExamen = $_GET['nombreExamen'];
    } else {
        echo '<p>No se ha especificado un examen.</p>';
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        $preguntas = $_POST['preguntas'] ?? [];

        // Cargar preguntas existentes
        $fileContent = file('preguntas.txt');
        $preguntasExistentes = [];
        
        foreach ($fileContent as $line) {
            $preguntasExistentes[] = trim($line);
        }

        // Asociar preguntas al examen
        $nombreArchivoExamen = str_replace(' ', '_', strtolower($nombreExamen)) . '.txt';
        $contenidoArchivoExamen = file_get_contents($nombreArchivoExamen);
        $contenidoArchivoExamen .= "\nPreguntas Asociadas:\n";

        foreach ($preguntas as $preguntaIndex) {
            if (isset($preguntasExistentes[$preguntaIndex])) {
                $contenidoArchivoExamen .= $preguntasExistentes[$preguntaIndex] . "\n";
            }
        }

        // Guardar las preguntas asociadas en el archivo del examen
        file_put_contents($nombreArchivoExamen, $contenidoArchivoExamen);

        echo '<p>Preguntas asociadas al examen con éxito.</p>';
    }

    // Cargar preguntas disponibles
    $fileContent = file_get_contents('preguntas.txt');
    $preguntasDisponibles = explode("\n", trim($fileContent));
    ?>

    <h2>Asociar Preguntas a <?php echo htmlspecialchars($nombreExamen); ?></h2>
    <form action="" method="POST">
        <input type="hidden" name="action" value="associate">
        <h3>Preguntas Disponibles</h3>
        <?php foreach ($preguntasDisponibles as $index => $pregunta): ?>
            <label>
                <input type="checkbox" name="preguntas[]" value="<?php echo $index; ?>">
                <?php echo htmlspecialchars($pregunta); ?>
            </label><br>
        <?php endforeach; ?>
        <button type="submit">Asociar Preguntas</button>
    </form>

    <a href="examenes_profesor.php">Volver a la gestión de exámenes</a>
</body>
</html>
