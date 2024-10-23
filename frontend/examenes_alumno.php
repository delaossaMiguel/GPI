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
    require '../backend/modelo.php';
    $con = conexion(); 
    session_start();
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Alumno') {
        header('Location: login.php');
        exit();
    }
    $usuarioId = $_SESSION['id_usuario']; 

    // Obtener exámenes disponibles por asignatura
    $asignaturasQuery = $con->prepare("
        SELECT a.id_asignatura, a.nombre 
        FROM usuario_asignatura ua
        JOIN asignatura a ON ua.id_asignatura = a.id_asignatura
        WHERE ua.id_usuario = ?
    ");
    $asignaturasQuery->bind_param("i", $usuarioId);
    $asignaturasQuery->execute();
    $asignaturasResult = $asignaturasQuery->get_result();

    $examenes = [];
    while ($asignatura = $asignaturasResult->fetch_assoc()) {
        $examenesQuery = $con->prepare("
            SELECT e.nombre, e.fecha_inicial, e.hora_inicio, e.fecha_fin, e.hora_fin 
            FROM examen e
            JOIN examen_asignatura ae ON e.id_examen = ae.id_examen
            WHERE ae.id_asignatura = ?
        ");
        $examenesQuery->bind_param("i", $asignatura['id_asignatura']);
        $examenesQuery->execute();
        $examenesResult = $examenesQuery->get_result();

        while ($examen = $examenesResult->fetch_assoc()) {
            $examenes[] = array_merge($examen, ['asignatura' => $asignatura['nombre']]);
        }
    }

    // Mostrar exámenes por asignatura
    if (empty($examenes)) {
        echo '<p>No hay exámenes disponibles para tus asignaturas.</p>';
    } else {
        echo '<h2>Exámenes por Asignatura</h2>';
        echo '<ul>';
        foreach ($examenes as $examen) {
            echo "<li><strong>{$examen['nombre']}</strong> (Asignatura: {$examen['asignatura']}) - Cierre: {$examen['fecha_fin']} {$examen['hora_fin']}";
            if (estaEnPeriodo($examen['fecha_inicial'], $examen['hora_inicio'], $examen['fecha_fin'], $examen['hora_fin'])) {
                echo " - <a href='hacer_examen.php?nombre=" . urlencode($examen['nombre']) . "'>Realizar Examen</a>";
            }
            echo "</li>";
        }
        echo '</ul>';
    }

    // Exámenes asociados personalmente al alumno
    $examenesPersonalesQuery = $con->prepare("
        SELECT e.nombre, e.fecha_inicial, e.hora_inicio, e.fecha_fin, e.hora_fin
        FROM examen e
        JOIN examen_alumno ea ON e.id_examen = ea.id_examen
        WHERE ea.id_usuario = ?
    ");
    $examenesPersonalesQuery->bind_param("i", $usuarioId);
    $examenesPersonalesQuery->execute();
    $examenesPersonalesResult = $examenesPersonalesQuery->get_result();

    $examenesPersonales = $examenesPersonalesResult->fetch_all(MYSQLI_ASSOC);

    // Mostrar exámenes asociados al alumno
    if (empty($examenesPersonales)) {
        echo '<h2>No tienes exámenes personales asignados.</h2>';
    } else {
        echo '<h2>Exámenes Personales Asignados</h2>';
        echo '<ul>';
        foreach ($examenesPersonales as $examenPersonal) {
            echo "<li><strong>{$examenPersonal['nombre']}</strong> - Cierre: {$examenPersonal['fecha_fin']} {$examenPersonal['hora_fin']}";
            if (estaEnPeriodo($examenPersonal['fecha_inicial'], $examenPersonal['hora_inicio'], $examenPersonal['fecha_fin'], $examenPersonal['hora_fin'])) {
                echo " - <a href='hacer_examen.php?nombre=" . urlencode($examenPersonal['nombre']) . "'>Realizar Examen</a>";
            }
            echo "</li>";
        }
        echo '</ul>';
    }

    $con->close();
    ?>

    <p><a href="alumno.php">Volver a inicio</a></p>
</body>
</html>
