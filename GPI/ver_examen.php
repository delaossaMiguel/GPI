<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Examen</title>
</head>
<body>
    <h1>Ver Examen</h1>

    <?php
    $nombreExamen = $_GET['nombre'];
    $fileContent = file_get_contents('examenes.txt');
    $examenes = explode("\n", trim($fileContent));
    $found = false;

    foreach ($examenes as $examen) {
        list($examenNombre, $grupo, $fechaInicio, $fechaFin) = explode(' ', $examen, 4);
        
        if ($examenNombre === $nombreExamen) {
            echo "<h2>$examenNombre</h2>";
            echo "<p>Grupo: $grupo</p>";
            echo "<p>Fecha de Inicio: $fechaInicio</p>";
            echo "<p>Fecha de Fin: $fechaFin</p>";

            $archivoPreguntas = "$nombreExamen.txt";

            if (file_exists($archivoPreguntas)) {
                $preguntasContent = file_get_contents($archivoPreguntas);
                $preguntas = explode("\n", trim($preguntasContent));

                echo '<ol>';
                foreach ($preguntas as $pregunta) {
                    list($textoPregunta, $opciones, $respuestaCorrecta) = explode(';', $pregunta);
                    $opcionesArray = explode(' | ', $opciones);

                    echo "<li><strong>$textoPregunta</strong>";
                    echo '<ul>';
                    foreach ($opcionesArray as $opcion) {
                        echo "<li>$opcion</li>";
                    }
                    echo '</ul>';
                    echo "</li>";
                }
                echo '</ol>';
            } else {
                echo "<p>No se encontraron preguntas para este examen.</p>";
            }

            $found = true;
            break;
        }
    }

    if (!$found) {
        echo "<p>Examen no encontrado.</p>";
    }
    ?>

    <p><a href="examenes_alumno.php">Volver a exámenes disponibles</a></p>
</body>
</html>
