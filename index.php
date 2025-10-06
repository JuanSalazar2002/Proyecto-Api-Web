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

// si está definida entonces que devuelva el valor extra
// el $_SERVER['PATH_INFO'] se encarga de devolver la parte extra de la url
$path= isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'/'; // ejm: puede devolver "/5"
// divide la parte obtenida en partes, lo convierte todo en un array
$buscarId= explode('/', $path);
// obtiene el ultimo valor del array
$id= ($path!=='/') ? end($buscarId):null;

switch($metodo){
    // Consulta del tipo SELECT
    case 'GET':
        // echo "consulta de registros - GET";
        consulta($conexion, $id);
        break;
    // Consulta del tipo INSERT
    case 'POST':
        // echo "insertar de registros - POST";
        insertar($conexion);
        break;
    // Consulta del tipo UPDATE
    case 'PUT':
        // echo "editacion de registros - PUT";
        actualizar($conexion, $id);
        break;
    // Consulta del tipo DELETE
    case 'DELETE':
        // echo "borrado de registros - DELETE";
        borrar($conexion, $id);
        break;
    default:
        echo "Método no permitido";
        break;
}

function consulta($conexion, $id){
    $sql= ($id !== null)?"SELECT * FROM usuarios WHERE id=$id":"SELECT * FROM usuarios";
    $resultado= $conexion->query($sql);

    if($resultado){
        $datos= array();
        while($fila= $resultado->fetch_assoc()){ // <- fetch_assoc() se encarga de devolver la siguiente fila, si ya no hay devuelve un null
            $datos[]= $fila; // <- esto es una manera "MODERNA" de añadir elementos a un array
        }
        echo json_encode($datos); // <- aca convertimos el array en un json
    }
}

function insertar($conexion){
    // el file_get_contents('php://input') lee lo que enviamos en el boddy y despues de leerlo lo decodificamos a un array
    $dato= json_decode(file_get_contents('php://input'), true);
    // obtenemos el nombre
    $nombre= $dato['nombre'];
    
    // inserccion en la base de datos
    $sql= "INSERT INTO usuarios(nombre) VALUES ('$nombre')";
    $resutlado= $conexion->query($sql);

    if($resutlado){
        // obtiene el ultimo id y agregará el campo id al array en memoria
        $dato['id']= $conexion->insert_id;
        // codificamos el array devuelta a un json y lo mostramos
        echo json_encode($dato);
    }else{
        // si todo sale mal que muestre un json de error
        echo json_encode(array('error' => 'Error al crear el usuario'));
    }
}

function borrar($conexion, $id){

    $sql= "DELETE FROM usuarios WHERE id=$id";
    $resutlado= $conexion->query($sql);

    if($resutlado){
        echo json_encode(array('Mensaje' => 'Usuario eliminado'));
    }else{
        echo json_encode(array('Error' => 'Error al eliminar usuario'));
    }

}

function actualizar($conexion, $id){
    $dato= json_decode(file_get_contents('php://input'), true);
    $nombre= $dato['nombre'];

    echo "El id a editar es: {$id} con el dato {$nombre}";

    $sql= "UPDATE usuarios SET nombre = '$nombre' WHERE id=$id";
    $resutlado= $conexion->query($sql);

    if($resutlado){
        echo json_encode(array('Mensaje' => 'Usuario actualizado'));
    }else{
        echo json_encode(array('Error' => 'Error al actualizar usuario'));
    }
}

?>