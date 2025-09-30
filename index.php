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
// print_r($metodo);

switch($metodo){
    // Consulta del tipo SELECT
    case 'GET':
        // echo "consulta de registros - GET";
        consulta($conexion);
        break;
    // Consulta del tipo INSERT
    case 'POST':
        // echo "insertar de registros - POST";
        insertar($conexion);
        break;
    // Consulta del tipo UPDATE
    case 'PUT':
        echo "editacion de registros - PUT";
        break;
    // Consulta del tipo DELETE
    case 'DELETE':
        echo "borrado de registros - DELETE";
        break;
    default:
        echo "Método no permitido";
        break;
}

function consulta($conexion){
    $sql= "SELECT * FROM usuarios";
    $resultado= $conexion->query($sql);

    if($resultado){
        $datos= array();
        while($fila= $resultado->fetch_assoc()){ // <- fetch_assoc() se encarga de devolver la siguiente fila, si ya no hay devuelve un null
            $datos[]= $fila; // <- esto es una manera "MODERNA" de añadir elementos a un array
        }
        echo json_encode($datos);
    }
}

function insertar($conexion){
    $dato= json_decode(file_get_contents('php://input'), true);
    $nombre= $dato['nombre'];
    
    $sql= "INSERT INTO usuarios(nombre) VALUES ('$nombre')";
    $resutlado= $conexion->query($sql);

    if($resutlado){
        $dato['id']= $conexion->insert_id;
        echo json_encode($dato);
    }else{
        echo json_encode(array('error' => 'Error al crear el usuario'));
    }
}

?>