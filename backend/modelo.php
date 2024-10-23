<?php

	include "../bbdd/conexion.php";

	/*
	Obtenemos el listado de comunidades.
	Devuelve un resultset con las comunidades
	Un -1 en caso de error en la consulta
	*/

	function listadoAlumnos () {
		$con = conexion();

		$consulta = "select * from usuario";
		if ($resultado = $con->query($consulta)) {
			return $resultado;

		} else {
			return -1;
		}
	}
	function usuarioModificar ($id) {
		$con = conexion();

		$consulta = "select * from usuario where id_usuario = $id";
		if ($resultado = $con->query($consulta)) {
			return $resultado;

		} else {
			return -1;
		}
	}
	function eliminarUsuarios ($id) {
		$con = conexion();

		$consulta = "delete from usuario where id_usuario = $id";
		if ($resultado = $con->query($consulta)) {
			return $resultado;

		} else {
			return -1;
		}
	}
	function anadirUsuario(){
		$con = conexion();

		$email = $_POST['email'];
		$passwd = $_POST['passwd'];
		$passwd = ($passwd);
		$nombre = $_POST['nombre'];
		$apellido1 = $_POST['apellido1'];
		$apellido2 = $_POST['apellido2'];
		$rol = $_POST['rol'];
	
		
		$consulta = "insert into usuario( email, passwd, nombre, apellido1, apellido2, rol) values ('$email','$passwd','$nombre','$apellido1','$apellido2','$rol')";
		if ($resultado = $con->query($consulta)) {
			return 1;

		} else {
			return -1;
		}
	}

	function modificarUsuario($id_usuario){
		$con = conexion();

		$email = $_POST['email'];
		$passwd = $_POST['passwd'];
		$nombre = $_POST['nombre'];
		$apellido1 = $_POST['apellido1'];
		$apellido2 = $_POST['apellido2'];
		$rol = $_POST['rol'];
		
		$consulta_con ="select passwd from usuario where id_usuario = $id_usuario";

		if ($resultado = $con->query($consulta_con)->fetch_assoc()) {
			
			/*if($resultado['passwd'] == $passwd){
			}else{
				$passwd = sha1($passwd);
			}*/
		} 
		
		$consulta = "UPDATE usuario SET email = '$email',
			passwd = '$passwd',
			nombre = '$nombre',
			apellido1 = '$apellido1',
			apellido2 = '$apellido2',
			rol = '$rol'
		WHERE id_usuario = $id_usuario";
		
		if ($resultado = $con->query($consulta)) {
			return 1;

		} else {
			return -1;
		}
	}
	function estaEnPeriodo($fechaInicio, $horaInicio, $fechaFin, $horaFin) {
        $hoy = date("Y-m-d");
        $horaActual = date("H:i");
        $enPeriodo = false;

        if ($hoy == $fechaInicio && $horaActual >= $horaInicio) {
            $enPeriodo = true;
        } elseif ($hoy == $fechaFin && $horaActual <= $horaFin) {
            $enPeriodo = true;
        } elseif ($hoy > $fechaInicio && $hoy < $fechaFin) {
            $enPeriodo = true;
        }

        return $enPeriodo;
    }
?>
