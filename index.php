<?php

$host= "localhost";
$user= "juan";
$pass= "admin123";
$bd= "api";

$conexion= new mysqli($host, $user, $pass, $bd);
if($conexion->connect_error){
    die("Conexion No Establecida".$conexion->connect_error);
}

// Con esto decimos que la respuesta que devolveremos será un json
header("Content-Type: application/json");
// Esto tomará el método http que se haga en ese instante
$metodo= $_SERVER['REQUEST_METHOD'];
// Acá imprimimos el nombre de ese método
print_r($metodo);

?>