<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hacer Examen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }
        .examen-container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        h1, h2 {
            color: #333;
            text-align: center;
        }
        .pregunta {
            margin-bottom: 15px;
        }
        .opciones {
            margin-top: 10px;
        }
        .opcion {
            display: block;
            margin: 5px 0;
        }
        .submit-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }
        .resultado {
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
        }
        .back-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #337ab7;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="examen-container">
        <h1>Hacer Examen</h1>

        <?php
        $archivoExamenes = 'examenes.txt';
        $archivoPreguntas = 'examen.txt';
        $fechaHoraActual = new DateTime();
        $examenDisponible = false;

        // Leer y verificar el archivo de examenes
        $contenidoExamenes = file($archivoExamenes);
        foreach ($contenidoExamenes as $linea) {
            list($nombreExamen, $grupo, $fechaInicio, $horaInicio, $fechaFin, $horaFin) = explode(' ', trim($linea), 6);
            $fechaHoraInicio = DateTime::createFromFormat('Y-m-d H:i', "$fechaInicio $horaInicio");
            $fechaHoraFin = DateTime::createFromFormat('Y-m-d H:i', "$fechaFin $horaFin");

            if ($fechaHoraActual >= $fechaHoraInicio && $fechaHoraActual <= $fechaHoraFin) {
                $examenDisponible = true;
                break;
            }
        }

        // Verificar si el examen está disponible
        if ($examenDisponible) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Procesar respuestas y calcular la puntuación
                $contenidoPreguntas = file($archivoPreguntas);
                $puntuacionTotal = 0;
                $puntuacionObtenida = 0;

                foreach ($contenidoPreguntas as $index => $linea) {
                    list($pregunta, $opcionesTexto, $respuestaCorrecta, $puntuacion) = explode(';', trim($linea));
                    $puntuacionTotal += $puntuacion;

                    if (isset($_POST["pregunta_$index"]) && $_POST["pregunta_$index"] == $respuestaCorrecta) {
                        $puntuacionObtenida += $puntuacion;
                    }
                }

                echo "<div class='resultado'>";
                echo "<h2>Resultado del Examen</h2>";
                echo "<p>Has obtenido una puntuación de <strong>$puntuacionObtenida</strong> sobre <strong>$puntuacionTotal</strong>.</p>";
                echo "</div>";
                echo '<a href="examenes_alumno.php" class="back-btn">Volver a mis Exámenes</a>';
            } else {
                // Mostrar el formulario de examen
                echo "<h2>Examen: $nombreExamen</h2>";
                $contenidoPreguntas = file($archivoPreguntas);
                echo '<form action="" method="POST">';
                
                foreach ($contenidoPreguntas as $index => $linea) {
                    list($pregunta, $opcionesTexto, $respuestaCorrecta, $puntuacion) = explode(';', trim($linea));
                    $opciones = explode('|', $opcionesTexto);

                    echo "<div class='pregunta'>";
                    echo "<strong>Pregunta " . ($index + 1) . ":</strong> $pregunta";
                    echo "<div class='opciones'>";

                    foreach ($opciones as $opcionIndex => $opcion) {
                        echo "<label class='opcion'><input type='radio' name='pregunta_$index' value='$opcionIndex' required> $opcion</label>";
                    }
                    echo "</div>";
                    echo "</div>";
                }

                echo '<button type="submit" class="submit-btn">Enviar Examen</button>';
                echo '</form>';
            }
        } else {
            echo "<p>El examen no está disponible en este momento. Por favor, revise las fechas y horas de acceso.</p>";
        }
        ?>
    </div>
</body>
</html>
