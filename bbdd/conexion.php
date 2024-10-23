<?php
	function conexion(){
		$servidor = "127.0.0.1";
		$bd = "gpi_bbdd";
		$user = "root";
		$password = "";

		// Establecer la conexión a la base de datos
		$con = mysqli_connect($servidor, $user, $password, $bd);

		if (!$con) {
			echo "Error de conexión de base de datos <br>";
			echo "Error número: " . mysqli_connect_errno() . "<br>";
			echo "Texto error: " . mysqli_connect_error() . "<br>";
			exit;
		}
		return $con;
	}

