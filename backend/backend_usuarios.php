<?php
require_once("modelo.php");
require_once("vista.php");


if (isset($_GET['accion'])) {
    $accion = $_GET['accion'];
} else {
    $accion = "inicio";
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = 1;
}


if ($accion == "inicio") {
    switch ($id) {
        case 1:
            echo mostrarUsuarios(listadoAlumnos());
            break;
    }
}
if ($accion == "eliminar_usuario") {
    switch ($id) {
        case 1:
            if (isset($_GET['id_usuario'])) {
                $id_usuario = $_GET['id_usuario'];
            } else {
                echo "error id_usuario eliminando";
            }
            eliminarUsuarios($id_usuario);
            echo mostrarUsuarios(listadoAlumnos());
            break;
        }
   
}


if ($accion == "modificar_usuario") {
    switch ($id) {
        case 1:
            if (isset($_GET['id_usuario'])) {
                $id_usuario = $_GET['id_usuario'];
            } else {
                echo "error id_usuario eliminando";
            }
            //eliminarUsuarios($id_usuario);
            echo mostrarModificar(usuarioModificar($id_usuario));
            break;
        }
   
}
if ($accion == "anadir_usuario") {
    switch ($id) {
        case 1:
           anadirUsuario();/**Datos llegan con post, ver lo de sha1 para clave**/
           echo mostrarUsuarios(listadoAlumnos());
            break;
    }
}if ($accion == "validar_modificar_usuario") {
    switch ($id) {
        case 1:
            if (isset($_GET['id_usuario'])) {
                $id_usuario = $_GET['id_usuario'];
            } else {
                echo "error id_usuario modificando";
            }
           modificarUsuario($id_usuario);/**Datos llegan con post, ver lo de sha1 para clave**/
           echo mostrarUsuarios(listadoAlumnos());
            break;
    }
}

?>