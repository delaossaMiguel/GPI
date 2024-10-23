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
    <title>Asociar Preguntas</title>
</head>
<body>
    <h1>Asociar Preguntas al Examen</h1>

    <?php

    if (isset($_GET['nombreExamen'])) {
        $nombreExamen = $_GET['nombreExamen'];
    } else {
        echo '<p>No se ha especificado un examen.</p>';
        exit();
    }

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

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        $preguntas = $_POST['preguntas'] ?? [];

        foreach ($preguntas as $idPregunta) {
            $consultaAsociar = $conexion->prepare("INSERT INTO examen_pregunta (id_examen, id_pregunta) VALUES (?, ?)");
            $consultaAsociar->bind_param('ii', $idExamen, $idPregunta);
            $consultaAsociar->execute();
        }

        $preguntasDesarrollo = $_POST['preguntasD'] ?? [];
        foreach ($preguntasDesarrollo as $idPreguntaDesarrollo) {
            $consultaAsociarDesarrollo = $conexion->prepare("INSERT INTO examen_preguntad (id_examen, id_preguntaD) VALUES (?, ?)");
            $consultaAsociarDesarrollo->bind_param('ii', $idExamen, $idPreguntaDesarrollo);
            $consultaAsociarDesarrollo->execute();
    }

        echo '<p>Preguntas asociadas al examen con éxito.</p>';
    }

    //Consultas
    $consultaPreguntas = $conexion->query("SELECT id_pregunta, pregunta FROM pregunta");
    $preguntasDisponibles = $consultaPreguntas->fetch_all(MYSQLI_ASSOC);

    $consultaPreguntas = $conexion->query("SELECT id_preguntaD, pregunta FROM preguntadesarrollo");
    $preguntasDisponiblesDesarrollo = $consultaPreguntas->fetch_all(MYSQLI_ASSOC);

    ?>

    <h2>Asociar Preguntas a <?php echo $nombreExamen ?></h2>
    <form action="" method="POST">
        <input type="hidden" name="action" value="associate">
        <h3>Preguntas Disponibles</h3>
        <h4>Tipo test</h4>
        <?php foreach ($preguntasDisponibles as $pregunta): ?>
            <label>
                <input type="checkbox" name="preguntas[]" value="<?php echo $pregunta['id_pregunta']; ?>">
                <?php echo $pregunta['pregunta'] ?>
            </label><br>
        <?php endforeach; ?>
        <br>
        <h4>Desarrollo</h4>
        <?php foreach ($preguntasDisponiblesDesarrollo as $pregunta): ?>
            <label>
                <input type="checkbox" name="preguntasD[]" value="<?php echo $pregunta['id_preguntaD']; ?>">
                <?php echo $pregunta['pregunta']; ?>
            </label><br>
        <?php endforeach; ?>
        <br>
        <button type="submit">Asociar Preguntas</button>
    </form>

    <a href="examenes_profesor.php">Volver a la gestión de exámenes</a>

    <?php
    $conexion->close();
    ?>
</body>
</html>
