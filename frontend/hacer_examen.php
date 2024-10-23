<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./hacer_examen.css">
    <title>Hacer Examen</title>
</head>
<body>
    <div class="examen-container">
        <h1>Hacer Examen</h1>

        <?php
        require_once '../backend/modelo.php';
        $conn = conexion();

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $examenDisponible = false;
        $nombreExamen = '';

        // Consulta para verificar el examen y si está en el período correcto
        $sqlExamen = "SELECT nombre, fecha_inicial, hora_inicio, fecha_fin, hora_fin FROM examen";
        $resultadoExamen = $conn->query($sqlExamen);

        if ($resultadoExamen->num_rows > 0) {
            while ($fila = $resultadoExamen->fetch_assoc()) {
                if (estaEnPeriodo($fila['fecha_inicial'], $fila['hora_inicio'], $fila['fecha_fin'], $fila['hora_fin'])) {
                    $examenDisponible = true;
                    $nombreExamen = $fila['nombre'];
                    // Guardamos la fecha y hora final del examen en la sesión
                    $_SESSION['fecha_fin'] = $fila['fecha_fin'];
                    $_SESSION['hora_fin'] = $fila['hora_fin'];
                    break;
                }
            }
        }

        if ($examenDisponible) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $puntuacionTotal = 0;
                $puntuacionObtenida = 0;

                // Consulta para obtener las preguntas tipo test asociadas al examen
                $sqlPreguntas = "SELECT p.pregunta, p.opcion1, p.opcion2, p.opcion3, p.opcion4, p.respuesta, p.puntuacion
                                 FROM examen_pregunta ep
                                 JOIN pregunta p ON ep.id_pregunta = p.id_pregunta
                                 WHERE ep.id_examen = (SELECT id_examen FROM examen WHERE nombre = '$nombreExamen')";
                $resultadoPreguntas = $conn->query($sqlPreguntas);
                $index = 0;

                while ($fila = $resultadoPreguntas->fetch_assoc()) {
                    $puntuacionTotal += $fila['puntuacion'];

                    if (isset($_POST["pregunta_$index"]) && $_POST["pregunta_$index"] == $fila['respuesta']) {
                        $puntuacionObtenida += $fila['puntuacion'];
                    }
                    $index++;
                }

                echo "<div class='resultado'>";
                echo "<h2>Resultado del Examen</h2>";
                echo "<p>Has obtenido una puntuación de <strong>$puntuacionObtenida</strong> sobre <strong>$puntuacionTotal</strong> en las preguntas de test.</p>";
                echo "</div>";
                echo '<a href="examenes_alumno.php" class="back-btn">Volver a mis Exámenes</a>';
            } else {
                echo "<h2>Examen: $nombreExamen</h2>";
                echo '<form action="" method="POST" id="examen-form">';

                // Mostrar preguntas tipo test
                $sqlPreguntas = "SELECT p.id_pregunta, p.pregunta, p.opcion1, p.opcion2, p.opcion3, p.opcion4 
                                 FROM examen_pregunta ep
                                 JOIN pregunta p ON ep.id_pregunta = p.id_pregunta
                                 WHERE ep.id_examen = (SELECT id_examen FROM examen WHERE nombre = '$nombreExamen')";
                $resultadoPreguntas = $conn->query($sqlPreguntas);
                $index = 0;

                while ($fila = $resultadoPreguntas->fetch_assoc()) {
                    $pregunta = $fila['pregunta'];
                    $opciones = [$fila['opcion1'], $fila['opcion2'], $fila['opcion3'], $fila['opcion4']];
                    echo "<div class='pregunta'>";
                    echo "<strong>Pregunta " . ($index + 1) . ":</strong> $pregunta";
                    echo "<div class='opciones'>";
                    $i=1;
                    foreach ($opciones as $opcionIndex => $opcion) {
                        echo "<label class='opcion'><input type='radio' name='pregunta_$index' value='$i'> $opcion</label>";
                        $i++;
                    }
                    echo "</div>";
                    echo "</div>";
                    $index++;
                }

                // Mostrar preguntas de desarrollo
                $sqlPreguntasDesarrollo = "SELECT pd.id_preguntaD, pd.pregunta 
                                           FROM examen_preguntad epd
                                           JOIN preguntadesarrollo pd ON epd.id_preguntaD = pd.id_preguntaD
                                           WHERE epd.id_examen = (SELECT id_examen FROM examen WHERE nombre = '$nombreExamen')";
                $resultadoPreguntasDesarrollo = $conn->query($sqlPreguntasDesarrollo);
                $indexDesarrollo = 0;

                while ($filaDesarrollo = $resultadoPreguntasDesarrollo->fetch_assoc()) {
                    $preguntaDesarrollo = $filaDesarrollo['pregunta'];
                    echo "<div class='pregunta'>";
                    echo "<strong>Pregunta de Desarrollo " . ($indexDesarrollo + 1) . ":</strong> $preguntaDesarrollo";
                    echo "<div class='opciones'>";
                    echo "<textarea name='preguntaD_$indexDesarrollo' rows='4' cols='50'></textarea>";
                    echo "</div>";
                    echo "</div>";
                    $indexDesarrollo++;
                }

                echo '<button type="submit" class="submit-btn">Enviar Examen</button>';
                echo '</form>';
            }
        } else {
            echo "<p>El examen no está disponible en este momento. Por favor, revise las fechas y horas de acceso.</p>";
        }

        $conn->close();
        ?>
    </div>
    <?php if ($examenDisponible): ?>
    <div id="contador"></div>

    <script>
        const fechaFin = "<?php echo $_SESSION['fecha_fin']; ?>";
        const horaFin = "<?php echo $_SESSION['hora_fin']; ?>";
        const fechaHoraFinal = new Date(`${fechaFin}T${horaFin}`);

        const contadorElement = document.getElementById("contador");

        const countdown = setInterval(() => {
            const ahora = new Date();
            const tiempoRestante = fechaHoraFinal - ahora;

            if (tiempoRestante <= 0) {
                clearInterval(countdown);
                alert("El tiempo ha terminado. Enviando el examen automáticamente.");
                document.forms['examen-form'].submit();
            } else {
                const horas = Math.floor(tiempoRestante / (1000 * 60 * 60));
                const minutos = Math.floor((tiempoRestante % (1000 * 60 * 60)) / (1000 * 60));
                const segundos = Math.floor((tiempoRestante % (1000 * 60)) / 1000);

                contadorElement.textContent = `Tiempo restante: ${horas}h ${minutos}m ${segundos}s`;
            }
        }, 1000);

        formElement.addEventListener('submit', function() {
            clearInterval(countdown);
        });

    </script>
    <?php endif; ?>
</body>
</html>
