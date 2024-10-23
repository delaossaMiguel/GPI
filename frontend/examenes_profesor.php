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
    require_once '../bbdd/conexion.php'; 

    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Profesor') {
        header('Location: login.php');
        exit();
    }

    $con = conexion(); 

    $asignaturasResult = $con->query("SELECT id_asignatura, nombre FROM asignatura");
    $asignaturas = $asignaturasResult->fetch_all(MYSQLI_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        if ($_POST['action'] === 'crear_examen') {
            $nombreExamen = $_POST['nombreExamen'];
            $fechaInicio = $_POST['fechaInicio'];
            $horaInicio = $_POST['horaInicio'];
            $fechaFin = $_POST['fechaFin'];
            $horaFin = $_POST['horaFin'];
            $asignaturaId = $_POST['asignatura']; 

            $stmt = $con->prepare("INSERT INTO examen (nombre, fecha_inicial, hora_inicio, fecha_fin, hora_fin) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nombreExamen, $fechaInicio, $horaInicio, $fechaFin, $horaFin);
            
            if ($stmt->execute()) {
                $idExamen = $stmt->insert_id; 

                $stmtAsignatura = $con->prepare("INSERT INTO examen_asignatura (id_examen, id_asignatura) VALUES (?, ?)");
                $stmtAsignatura->bind_param("ii", $idExamen, $asignaturaId);
                $stmtAsignatura->execute();
                $stmtAsignatura->close();

                echo '<p>Examen creado con éxito y asociado a la asignatura.</p>';
            } else {
                echo '<p>Error al crear el examen: ' . $stmt->error . '</p>';
            }

            $stmt->close();
        }
        elseif ($_POST['action'] === 'crear_pregunta') {
            $pregunta = $_POST['pregunta'];
            $opcionA = $_POST['a'];
            $opcionB = $_POST['b'];
            $opcionC = $_POST['c'];
            $opcionD = $_POST['d'];
            $respuesta = $_POST['respuesta'];
            $puntuacion = $_POST['puntuacion'];
            // Preparar y ejecutar la consulta para insertar la pregunta
            $stmt = $con->prepare("INSERT INTO pregunta (pregunta, opcion1, opcion2, opcion3, opcion4, respuesta, puntuacion) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $pregunta, $opcionA, $opcionB, $opcionC, $opcionD, $respuesta, $puntuacion);
            
            if ($stmt->execute()) {
                echo '<p>Pregunta creada con éxito.</p>';
            } else {
                echo '<p>Error al crear la pregunta: ' . $stmt->error . '</p>';
            }
    
            $stmt->close();
        }
        elseif ($_POST['action'] === 'crear_desarrollo') {
            $pregunta = $_POST['pregunta'];
            $respuesta = $_POST['respuesta'];
            $puntuacion = $_POST['puntuacion'];
            // Preparar y ejecutar la consulta para insertar la pregunta
            $stmt = $con->prepare("INSERT INTO preguntadesarrollo (pregunta, respuestaCorrecta, puntuacion) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $pregunta,$respuesta, $puntuacion);
            
            if ($stmt->execute()) {
                echo '<p>Pregunta de desarrollo creada con éxito.</p>';
            } else {
                echo '<p>Error al crear la pregunta: ' . $stmt->error . '</p>';
            }
    
            $stmt->close();
        }
    }
    

    $result = $con->query("SELECT e.*, a.nombre AS asignatura_nombre FROM examen e LEFT JOIN examen_asignatura ea ON e.id_examen = ea.id_examen LEFT JOIN asignatura a ON ea.id_asignatura = a.id_asignatura");

    if ($result->num_rows > 0) {
    ?>

    <h2>Exámenes</h2>
    <ul>
        <?php while ($examen = $result->fetch_assoc()): ?>
            <li>
                <strong><?php echo $examen['nombre'] ?></strong> - Asignatura: <?php echo htmlspecialchars($examen['asignatura_nombre']); ?> - Inicio: <?php echo htmlspecialchars($examen['fecha_inicial'] . ' ' . $examen['hora_inicio']); ?> - Fin: <?php echo htmlspecialchars($examen['fecha_fin'] . ' ' . $examen['hora_fin']); ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="index" value="<?php echo $examen['id_examen']; ?>">
                    <button type="button" onclick="window.location.href='asociar_preguntas.php?nombreExamen=<?php echo urlencode($examen['nombre']); ?>'">Asociar Preguntas</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="index" value="<?php echo $examen['id_examen']; ?>">
                    <button type="button" onclick="window.location.href='asociar_alumnos.php?nombreExamen=<?php echo urlencode($examen['nombre']); ?>'">Asociar Alumnos</button>
                </form>
            </li>
        <?php endwhile; ?>
    </ul>

    <?php
    } else {
        echo '<p>No hay exámenes registrados.</p>';
    }
    ?>

    <h2>Crear Examen</h2>
    <form action="" method="POST">
        <input type="hidden" name="action" value="crear_examen">
        <label>Nombre del Examen: <input type="text" name="nombreExamen" required></label><br>
        <label>Fecha de Inicio: <input type="date" name="fechaInicio" required></label><br>
        <label>Hora de Inicio: <input type="time" name="horaInicio" required></label><br>
        <label>Fecha de Fin: <input type="date" name="fechaFin" required></label><br>
        <label>Hora de Fin: <input type="time" name="horaFin" required></label><br>
        <label>Asignatura:
            <select name="asignatura" required>
                <option value="">Selecciona una asignatura</option>
                <?php foreach ($asignaturas as $asignatura): ?>
                    <option value="<?php echo $asignatura['id_asignatura']; ?>"><?php echo $asignatura['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </label><br>
        <button type="submit">Crear Examen</button>
    </form>

    <h2>Crear pregunta</h2>
    <form action="" method="POST">
        <input type="hidden" name="action" value="crear_pregunta">
        <label>Pregunta: <input type="text" name="pregunta" required></label><br>
        <label>opcion 1: <input type="text" name="a" required></label><br>
        <label>opcion 2: <input type="text" name="b" required></label><br>
        <label>opcion 3: <input type="text" name="c" required></label><br>
        <label>opcion 4: <input type="text" name="d" required></label><br>
        <label>Respuesta: <select name ="respuesta" id="respuesta">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
        </select>
        <label>Puntuacion: <input type="text" name="puntuacion" required></label><br>
        </label><br>
        <button type="submit">Crear pregunta</button>
    </form>

    <h2>Crear pregunta de desarrollo</h2>
    <form action="" method="POST">
        <input type="hidden" name="action" value="crear_desarrollo">
        <label>Pregunta: <input type="text" name="pregunta" required></label><br>
        <label>Respuesta: <input type="text" name="respuesta" required></label><br>
        <label>Puntuacion: <input type="text" name="puntuacion" required></label><br>
        </label><br>
        <button type="submit">Crear pregunta</button>
    </form>
    <?php $con->close(); 
     ?>
</body>
</html>
