<?php
require_once("modelo.php");

function mostrarUsuarios($resultado){
    if(!is_object($resultado)){

    }
    else{
        $listado = file_get_contents("listado.html");
        $trozos = explode("##fila##",$listado);
        $cuerpo = "";
        while($datos = $resultado->fetch_assoc()){
            $aux = $trozos[1];
            $aux = str_replace("##nombre##",$datos["nombre"],$aux);
            $aux = str_replace("##apellido1##",$datos["apellido1"],$aux);
            $aux = str_replace("##apellido2##",$datos["apellido2"],$aux);
            $aux = str_replace("##rol##",$datos["rol"],$aux);
            $aux = str_replace("##email##",$datos["email"],$aux);
            $aux = str_replace("##passwd##",$datos["passwd"],$aux);
            $aux = str_replace("##id##",$datos["id_usuario"],$aux);

            $cuerpo .= $aux;
        }
        echo $trozos[0] . $cuerpo . $trozos[2];
    }
}

function mostrarModificar($resultado){
    if(!is_object($resultado)){

    }
    else{
        $listado = file_get_contents("plantillaModificar.html");
       
        $cuerpo = "";
        while($datos = $resultado->fetch_assoc()){
            $aux = $listado;
            $aux = str_replace("##nombre##",$datos["nombre"],$aux);
            $aux = str_replace("##apellido1##",$datos["apellido1"],$aux);
            $aux = str_replace("##apellido2##",$datos["apellido2"],$aux);
            $aux = str_replace("##rol##",$datos["rol"],$aux);
            $aux = str_replace("##email##",$datos["email"],$aux);
            $aux = str_replace("##passwd##",$datos["passwd"],$aux);
            $aux = str_replace("##id##",$datos["id_usuario"],$aux);

            $cuerpo .= $aux;
        }
        echo $cuerpo;
    }
}
?>