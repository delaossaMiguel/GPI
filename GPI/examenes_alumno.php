<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exámenes Disponibles</title>
</head>
<body>
    <h1>Exámenes Disponibles</h1>
    <?php
    function estaEnPeriodo($fechaInicio, $horaInicio, $fechaFin, $horaFin) {
        $hoy = date("Y-m-d");
        $horaActual = date("H:i");
        $enPeriodo = false;

        if ($hoy == $fechaInicio && $horaActual >= $horaInicio) {
            // Día de inicio y hora actual igual o mayor que la hora de inicio
            $enPeriodo = true;
        } elseif ($hoy == $fechaFin && $horaActual <= $horaFin) {
            // Día de fin y hora actual igual o menor que la hora de fin
            $enPeriodo = true;
        } elseif ($hoy > $fechaInicio && $hoy < $fechaFin) {
            // Entre las fechas de inicio y fin
            $enPeriodo = true;
        }

        return $enPeriodo;
    }

    session_start();
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Alumno') {
        header('Location: login.php');
        exit();
    }

    $grupoAlumno = '1'; // Actualiza esto para que provenga de la sesión del alumno
    $fileContent = file_get_contents('examenes.txt');
    $examenes = explode("\n", trim($fileContent));

    echo '<ul>';
    foreach ($examenes as $examen) {
        $examenData = explode(' ', $examen, 6); 
        if (count($examenData) === 6) {
            list($nombreExamen, $grupo, $fechaInicio, $horaInicio, $fechaFin, $horaFin) = $examenData;

            // Comprobamos si el grupo coincide con el del alumno
            if ($grupo === $grupoAlumno) {
                echo "<li>$nombreExamen - Cierre: $fechaFin $horaFin";
                if (estaEnPeriodo($fechaInicio, $horaInicio, $fechaFin, $horaFin)) {
                    echo " - <a href='hacer_examen.php?nombre=" . urlencode($nombreExamen) . "'>Realizar Examen</a>";
                }
                echo "</li>";
            }
        } else {
            echo "<li>Examen mal formateado: $examen</li>";
        }
    }
    echo '</ul>';
    ?>

    <p><a href="index.php">Volver a inicio</a></p>
</body>
</html>
