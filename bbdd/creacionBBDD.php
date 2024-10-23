<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Redirección</title>
</head>
<body>

<?php
    // Conexión a la base de datos
    $servidor = "localhost";
    $bd = "gpi_bbdd";
    $user = "root";
    $password = "";

    $con = mysqli_connect($servidor, $user, $password, $bd);

    // Verificar la conexión
    if ($con->connect_error) {
        die("Conexión fallida: " . $con->connect_error);
    }

    // Crear tabla Examen
    $crear_examenes = "
    CREATE TABLE IF NOT EXISTS examen (
        id_examen INT(11) NOT NULL AUTO_INCREMENT,
        nombre VARCHAR(30) NOT NULL,
        fecha_inicial DATE NOT NULL,
        fecha_fin DATE NOT NULL,
        hora_inicio TIME NOT NULL,
        hora_fin TIME NOT NULL,
        PRIMARY KEY (id_examen)
    ) ENGINE=InnoDB;
    ";
    if ($con->query($crear_examenes) === TRUE) {
        echo "La tabla Examen se creó correctamente.<br>";
    } else {
        echo "Error al crear la tabla Examen: " . $con->error . "<br>";
    }

    // Crear tabla Pregunta
    $crear_preguntas = "
    CREATE TABLE IF NOT EXISTS pregunta (
        id_pregunta INT(11) NOT NULL AUTO_INCREMENT,
        pregunta VARCHAR(255) NOT NULL,
        opcion1 VARCHAR(10) NOT NULL,
        opcion2 VARCHAR(10) NOT NULL,
        opcion3 VARCHAR(10),
        opcion4 VARCHAR(10),
        respuesta INT(1) NOT NULL,
        puntuacion INT(11) NOT NULL,
        PRIMARY KEY (id_pregunta)
    ) ENGINE=InnoDB;
    ";
    if ($con->query($crear_preguntas) === TRUE) {
        echo "La tabla Pregunta se creó correctamente.<br>";
    } else {
        echo "Error al crear la tabla Pregunta: " . $con->error . "<br>";
    }

    // Crear tabla Pregunta de desarrollo
    $crear_preguntasD = "
    CREATE TABLE IF NOT EXISTS preguntaDesarrollo (
        id_preguntaD INT(11) NOT NULL AUTO_INCREMENT,
        pregunta VARCHAR(255) NOT NULL,
        respuestaCorrecta VARCHAR(255) NOT NULL,
        puntuacion INT(11) NOT NULL,
        PRIMARY KEY (id_preguntaD)
    ) ENGINE=InnoDB;
    ";
    if ($con->query($crear_preguntasD) === TRUE) {
        echo "La tabla Pregunta de Desarrollo se creó correctamente.<br>";
    } else {
        echo "Error al crear la tabla Pregunta de Desarrollo: " . $con->error . "<br>";
    }

    // Crear tabla Usuario
    $crear_usuarios = "
    CREATE TABLE IF NOT EXISTS usuario (
        id_usuario INT(11) NOT NULL AUTO_INCREMENT,
        email VARCHAR(50) NOT NULL,
        passwd VARCHAR(80) NOT NULL,
        nombre VARCHAR(30) NOT NULL,
        apellido1 VARCHAR(30) NOT NULL,
        apellido2 VARCHAR(30) NOT NULL,
        rol VARCHAR(15) DEFAULT NULL,
        PRIMARY KEY (id_usuario)
    );
    ";
    if ($con->query($crear_usuarios) === TRUE) {
        echo "La tabla Usuario se creó correctamente.<br>";
    } else {
        echo "Error al crear la tabla Usuario: " . $con->error . "<br>";
    }

    // Crear tabla Asignatura
    $crear_asignaturas = "
    CREATE TABLE IF NOT EXISTS asignatura (
        id_asignatura INT(11) NOT NULL AUTO_INCREMENT,
        nombre VARCHAR(30) NOT NULL,
        carrera VARCHAR(30) NOT NULL,
        PRIMARY KEY (id_asignatura)
    );
    ";
    if ($con->query($crear_asignaturas) === TRUE) {
        echo "La tabla Asignatura se creó correctamente.<br>";
    } else {
        echo "Error al crear la tabla Asignatura: " . $con->error . "<br>";
    }

    // Tabla intermedia para relación Usuario - Asignatura
    $crear_usuario_asignatura = "
    CREATE TABLE IF NOT EXISTS usuario_asignatura (
        id_usuario INT(11) NOT NULL,
        id_asignatura INT(11) NOT NULL,
        PRIMARY KEY (id_usuario, id_asignatura),
        FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
        FOREIGN KEY (id_asignatura) REFERENCES asignatura(id_asignatura) ON DELETE CASCADE
    );
    ";
    if ($con->query($crear_usuario_asignatura) === TRUE) {
        echo "La tabla usuario_asignatura se creó correctamente.<br>";
    } else {
        echo "Error al crear la tabla usuario_asignatura: " . $con->error . "<br>";
    }

    // Tabla intermedia para relación Examen - Pregunta
    $crear_examen_pregunta = "
    CREATE TABLE IF NOT EXISTS examen_pregunta (
        id_examen INT(11) NOT NULL,
        id_pregunta INT(11) NOT NULL,
        PRIMARY KEY (id_examen, id_pregunta),
        FOREIGN KEY (id_examen) REFERENCES examen(id_examen) ON DELETE CASCADE,
        FOREIGN KEY (id_pregunta) REFERENCES pregunta(id_pregunta) ON DELETE CASCADE
    );
    ";
    if ($con->query($crear_examen_pregunta) === TRUE) {
        echo "La tabla examen_pregunta se creó correctamente.<br>";
    } else {
        echo "Error al crear la tabla examen_pregunta: " . $con->error . "<br>";
    }

    // Tabla intermedia para relación Examen - PreguntaD
    $crear_examen_preguntaD = "
    CREATE TABLE IF NOT EXISTS examen_preguntaD (
        id_examen INT(11) NOT NULL,
        id_preguntaD INT(11) NOT NULL,
        PRIMARY KEY (id_examen, id_preguntaD),
        FOREIGN KEY (id_examen) REFERENCES examen(id_examen) ON DELETE CASCADE,
        FOREIGN KEY (id_preguntaD) REFERENCES preguntaDesarrollo(id_preguntaD) ON DELETE CASCADE
    );
    ";
    if ($con->query($crear_examen_preguntaD) === TRUE) {
        echo "La tabla examen_preguntaD se creó correctamente.<br>";
    } else {
        echo "Error al crear la tabla examen_preguntaD: " . $con->error . "<br>";
    }

    // Tabla intermedia para relación Examen - Alumno
    $crear_examen_alumno = "
    CREATE TABLE IF NOT EXISTS examen_alumno (
        id_examen INT(11) NOT NULL,
        id_usuario INT(11) NOT NULL,
        PRIMARY KEY (id_examen, id_usuario),
        FOREIGN KEY (id_examen) REFERENCES examen(id_examen) ON DELETE CASCADE,
        FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE
    );
    ";
    if ($con->query($crear_examen_alumno) === TRUE) {
        echo "La tabla examen_alumno se creó correctamente.<br>";
    } else {
        echo "Error al crear la tabla examen_alumno: " . $con->error . "<br>";
    }

    // Tabla intermedia para relación Examen - Asignatura
    $crear_examen_asignatura = "
    CREATE TABLE examen_asignatura (
        id_examen INT,
        id_asignatura INT,
        PRIMARY KEY (id_examen, id_asignatura),
        FOREIGN KEY (id_examen) REFERENCES examen(id_examen) ON DELETE CASCADE,
        FOREIGN KEY (id_asignatura) REFERENCES asignatura(id_asignatura) ON DELETE CASCADE
    );
    ";
    if ($con->query($crear_examen_asignatura) === TRUE) {
        echo "La tabla examen_asignatura se creó correctamente.<br>";
    } else {
        echo "Error al crear la tabla examen_asignatura: " . $con->error . "<br>";
    }


    // Insertar datos en la tabla Usuario
    $insert_usuarios = "
    INSERT INTO usuario (email, passwd, nombre, apellido1, apellido2, rol) VALUES 
    ('juan.perez@email.com', '1234', 'Juan', 'Pérez', 'González', 'Profesor'),
    ('maria.gomez@email.com', 'abcd', 'María', 'Gómez', 'López', 'Alumno'),
    ('ana.lopez@email.com', '5678', 'Ana', 'López', 'Martínez', 'Administrador');
    ";
    if ($con->multi_query($insert_usuarios) === TRUE) {
        echo "Datos de usuario insertados correctamente.<br>";
    } else {
        echo "Error al insertar datos de usuario: " . $con->error . "<br>";
    }

    // Insertar datos en la tabla Asignatura
    $insert_asignaturas = "
    INSERT INTO asignatura (nombre, carrera) VALUES 
    ('Matemáticas', 'Ingeniería'),
    ('Física', 'Ciencias'),
    ('Programación', 'Informática');
    ";
    if ($con->multi_query($insert_asignaturas) === TRUE) {
        echo "Datos de asignatura insertados correctamente.<br>";
    } else {
        echo "Error al insertar datos de asignatura: " . $con->error . "<br>";
    }

    // Insertar datos en la tabla Examen
    $insert_examenes = "
    INSERT INTO examen (nombre, fecha_inicial, fecha_fin, hora_inicio, hora_fin) VALUES 
    ('Examen Matemáticas', '2024-12-01', '2024-12-01', '10:00:00', '12:00:00'),
    ('Examen Física', '2024-12-05', '2024-12-05', '14:00:00', '16:00:00');
    ";
    if ($con->multi_query($insert_examenes) === TRUE) {
        echo "Datos de examen insertados correctamente.<br>";
    } else {
        echo "Error al insertar datos de examen: " . $con->error . "<br>";
    }

    // Insertar datos en la tabla Pregunta
    $insert_preguntas = "
    INSERT INTO pregunta (pregunta, opcion1, opcion2, opcion3, opcion4, respuesta, puntuacion) VALUES 
    ('¿Cuál es el resultado de 2+2?', '1', '2', '3', '4', 4, 1),
    ('¿Cuál es la capital de Francia?', 'Madrid', 'París', 'Londres', 'Berlín', 2, 1),
    ('¿Qué lenguaje de programación es usado en aplicaciones web?', 'HTML', 'Java', 'Python', 'JavaScript', 4, 2);
    ";
    if ($con->multi_query($insert_preguntas) === TRUE) {
        echo "Datos de pregunta insertados correctamente.<br>";
    } else {
        echo "Error al insertar datos de pregunta: " . $con->error . "<br>";
    }

    // Insertar datos en la tabla Pregunta de desarrollo
    $insert_preguntasD = "
    INSERT INTO preguntaDesarrollo (pregunta, respuestaCorrecta, puntuacion) VALUES 
    ('¿Como te llamas?', 'Me llamo Juan', 3),
    ('¿Que carrera haces?', 'Curso ingeniería informatica', 2);
    ";
    if ($con->multi_query($insert_preguntasD) === TRUE) {
        echo "Datos de pregunta de desarrollo insertados correctamente.<br>";
    } else {
        echo "Error al insertar datos de pregunta de desarrollo: " . $con->error . "<br>";
    }

    // Insertar datos en la tabla intermedia examen_pregunta
    $insert_examen_pregunta = "
    INSERT INTO examen_pregunta (id_examen, id_pregunta) VALUES 
    (1, 1),
    (1, 2),
    (2, 3);
    ";
    if ($con->multi_query($insert_examen_pregunta) === TRUE) {
        echo "Datos de examen_pregunta insertados correctamente.<br>";
    } else {
        echo "Error al insertar datos de examen_pregunta: " . $con->error . "<br>";
    }

    // Insertar datos en la tabla intermedia examen_alumno
    $insert_examen_alumno = "
    INSERT INTO examen_alumno (id_examen, id_usuario) VALUES 
    (1, 2)
    ";
    if ($con->multi_query($insert_examen_alumno) === TRUE) {
        echo "Datos de examen_alumno insertados correctamente.<br>";
    } else {
        echo "Error al insertar datos de examen_alumno: " . $con->error . "<br>";
    }

    // Insertar datos en la tabla intermedia examen_preguntaD
    $insert_examen_preguntaD = "
    INSERT INTO examen_preguntaD (id_examen, id_preguntaD) VALUES 
    (1, 1),
    (1, 2),
    (2, 2);
    ";
    if ($con->multi_query($insert_examen_preguntaD) === TRUE) {
        echo "Datos de examen_preguntaD insertados correctamente.<br>";
    } else {
        echo "Error al insertar datos de examen_preguntaD: " . $con->error . "<br>";
    }

    // Insertar datos en la tabla intermedia usuario_asignatura
    $insert_usuario_asignatura = "
    INSERT INTO usuario_asignatura (id_usuario, id_asignatura) VALUES 
    (1, 1),
    (1, 2),
    (2, 1),
    (2, 3),
    (3, 2);
    ";
    if ($con->multi_query($insert_usuario_asignatura) === TRUE) {
        echo "Datos de usuario_asignatura insertados correctamente.<br>";
    } else {
        echo "Error al insertar datos de usuario_asignatura: " . $con->error . "<br>";
    }

    // Cerrar la conexión a la base de datos
    $con->close();
    ?>
</body>
</html>