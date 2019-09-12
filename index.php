<?php
    header("Access-Control-Allow-Origin: *");

    if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
        echo json_encode(array('status'=> false));
        exit;
    }
$postdata = file_get_contents("php://input");
$datos = json_decode($postdata, true);

switch($datos['funcion']){
    case 'mostrar':
        mostrar($datos);
        break;
    case 'login':
        login($datos);
        break;
    default:
        echo json_encode("-1");


}



function login($datos){
    include "Conexion.php";
    $f = "select id,user,password,tipo from access where user='".$datos['user']."' and password='".$datos['password']."';";
    $d = $db->query($f);
    $var;
   
    if($d){
        if($res =mysqli_fetch_assoc($d)){
            $var = $res;
            echo json_encode($var);
        }
        else{
            echo json_encode("-1");
        }
    }
    else{
        echo json_encode(mysqli_error($db));
    }
    
}

function mostrar($datos){
    include "Conexion.php";
    
    $f = "select id,user,password,tipo from access;";
    $d = $db->query($f);
    $var = array();
    if($d){
        while($res = mysqli_fetch_assoc($d)):
            $var[] = $res;

        endwhile;
        echo json_encode($var);

    }
    else{
        echo json_encode(mysqli_error($db));
    }
}


?>