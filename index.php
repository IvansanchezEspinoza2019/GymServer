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
    case 'getLastId':
        getLastId($datos);
        break;
    case 'addCliente':
        addCliente($datos);
        break;
    case 'getAllCustomers':
        getAllCustomers();
        break;
    case 'getForeignData':
        getForeignData($datos);
        break;
    case 'existImg':
        fileExists2($datos);
        break;
    case 'eliminarCliente':
        eliminarCliente($datos);
        break;
    case 'activarCliente':
        activarCliente($datos);
        break;
    case 'getForeignDataModif':
        getForeignDataModif($datos);
        break;
    default:
        echo json_encode("-1");


}

function getForeignDataModif($datos){
    include "Conexion.php";
    $consulta = "select user from access where id=".$datos['id_access']." Union select nombre from colonia where id=".$datos['id_col']." Union select codigo from cp where id=".$datos['id_cp']." Union select password from access where id=".$datos['id_access']."  Union select nombre from cliente where id_cliente=".$datos['id_cliente']." Union select apellido_p from cliente where id_cliente=".$datos['id_cliente']." Union select apellido_m from cliente where id_cliente=".$datos['id_cliente']." ;";
    $get = $db->query($consulta);
    if($get){
        $data= array();
        while($res = mysqli_fetch_assoc($get)):
            $data[]=$res;
        endwhile;
        echo json_encode($data);
    }
    else{
        echo json_encode(mysqli_error($db));
    }


}
function eliminarCliente($datos){
    include "Conexion.php";
    $consulta = "update cliente set activo=0 where id_cliente=".$datos['id_cliente'].";";
    $elim = $db->query($consulta);
    if($elim){
        echo json_encode("exito");
    }else{
        echo json_encode("-1");
    }
}
function activarCliente($datos){
    include "Conexion.php";
    $consulta = "update cliente set activo=1 where id_cliente=".$datos['id_cliente'].";";
    $elim = $db->query($consulta);
    if($elim){
        echo json_encode("exito");
    }else{
        echo json_encode("-1");
    }
}
function fileExists2($datos) {
    $filePath='C:\xampp\htdocs\gymdb\imgs\customers\\'.$datos['path'];
    if(is_file($filePath) && file_exists($filePath)){
        echo json_encode("existe");
    }
    else{
        echo json_encode("no existe");
    }

} 

function getForeignData($datos){
    include "Conexion.php";
    $consulta = "select user from access where id=".$datos['id_access']." Union select nombre from colonia where id=".$datos['id_col']." Union select codigo from cp where id=".$datos['id_cp'].";";
    $get = $db->query($consulta);
    if($get){
        $data= array();
        while($res = mysqli_fetch_assoc($get)):
            $data[]=$res;
        endwhile;
        echo json_encode($data);
    }
    else{
        echo json_encode(mysqli_error($db));
    }



}

function getAllCustomers(){
    include "Conexion.php";
    $consulta = "Select id_cliente,foto,calle,numero_calle,telefono,fecha_nacimiento,fecha_ingreso,id_cp,id_colonia,id_access,activo,numero_interior,genero,CONCAT(apellido_p,' ',apellido_m,' ',nombre) as Nombre from cliente;";
    $get = $db->query($consulta);
    if($get){
        $clientes=array();
        $clientes2=array();
        
        while($res = mysqli_fetch_assoc($get)):
            $clientes2[]=$res;
        endwhile;
        $clientes['clientes']=$clientes2;
        echo json_encode($clientes);
    }
    else{
        echo json_encode(mysqli_error($db));
    }




}
function insertCliente($datos1,$cp1,$col1,$accesso1)
{
    include "Conexion.php";
    //$f = "insert into cliente values(NULL,'".$datos1['nombre']."','".$datos1['apellidoP']."','".$datos1['apellidoM']."','".$datos1['foto']."','".$datos1['calle']."','".$datos1['numero']."','".$datos1['telefono']."',CURDATE(),".$cp1['id'].",".$col1['id'].",".$accesso1['id'].",'".$datos1['fechanac']."',1,'".$datos1['numeroint']."','".$datos1['gender']."');";
    $f = "insert into cliente values(NULL,'".$datos1['nombre']."','".$datos1['apellidoP']."','".$datos1['apellidoM']."','".$datos1['foto']."','".$datos1['calle']."','".$datos1['numero']."','".$datos1['telefono']."',CURDATE(),".$cp1.",".$col1.",".$accesso1.",'".$datos1['fechanac']."',1,'".$datos1['numeroint']."','".$datos1['gender']."');";
    //$f = "insert into cliente values(NULL,'edgar','sanchez','esp','cac','vfrv','15','33',CURDATE(),5,5,10,'03-05-21',1,'78','M');";
    //echo json_encode($f);
    $d = $db->query($f);
    if($d){
        echo json_encode("exito");
    }
    else{
        echo json_encode("-1");
    }
    mysqli_close($db);
}

function addCliente($datos)
{
    $valAccesso = getAcceso($datos);    //verifica si el id es repetido
    $accesso="0";
    if($valAccesso=="0"){           //si no existe ese usuario

        $accesso = setAcceso($datos);  //inserta los nuevos datos
        $valCp=getCP($datos);  //verifica si ya existe el cp
        $cp ="0";
        if($valCp=="0"){  // si no existe lo inerta
            $cp = setCP($datos);
         }
         else{      
            $cp = $valCp['id'];  // si existe recupera la llave primaria
        }

        $valCol=getCol($datos);   //verifica si existe la colonia
        $col = "0";
        if($valCol=="0"){ //si no, la inserta
            $col = setCol($datos);
        }
        else{
            $col = $valCol['id'];  //si ya existe obtiene su llave primaria
        }

        if($cp!="0" & $col!="0" & $accesso!="0"){  /// si las llaves son distintas de cero
            insertCliente($datos,$cp,$col,$accesso); 
         }
         else{

             echo json_encode("-1"); 
            } 
    }
    else{  /// si el id es repetido
        echo json_encode("id_rep");
    }  
}
function getAcceso($datos)
{
    include "Conexion.php";
    $f = "select id from access where user='".$datos['user']."';";
    $d = $db->query($f);
    if($d){
        if($res = mysqli_fetch_assoc($d)){
            return $res;
        }
    }
    else{
        echo json_encode(mysqli_error($db));
    }
    return "0";
}
function setAcceso($datos)
{
    include "Conexion.php";
    $f = "insert into access values(NULL,'".$datos['user']."','".$datos['password']."','1');";
    $d = $db->query($f);
    if($d){
        return mysqli_insert_id($db);
    }
    else{
        echo json_encode(mysqli_error($db));
    }
    
}
function getCP($datos)
{
    include "Conexion.php";
    $f = "select id from cp where codigo='".$datos['cp']."';";
    $d = $db->query($f);
    if($d){
        if($res = mysqli_fetch_assoc($d)){
            return $res;
        }
    }
    else{
        echo json_encode(mysqli_error($db));
    }
    return "0";
}
function setCP($datos)
{
    include "Conexion.php";
    $f = "insert into cp values(NULL,'".$datos['cp']."');";
    $d = $db->query($f);
    if($d){
        return mysqli_insert_id($db);
    }
    else{
        echo json_encode(mysqli_error($db));
    }
    
}

function getCol($datos)
{
    include "Conexion.php";
    $f = "select id from colonia where nombre='".$datos['colonia']."';";
    $d = $db->query($f);
    if($d){
        if($res = mysqli_fetch_assoc($d)){
            return $res;
        }
    }
    else{
        echo json_encode(mysqli_error($db));
    }
    return "0";
    
}
function setCol($datos)
{
    include "Conexion.php";
    $f = "insert into colonia values(NULL,'".$datos['colonia']."');";
    $d = $db->query($f);
    if($d){
        return mysqli_insert_id($db);
    }
    else{
        echo json_encode(mysqli_error($db));
    }
   
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
    mysqli_close($db);
    
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
    mysqli_close($db);
}

function getLastId($datos){

    include "Conexion.php";


    $f = "select max(id_cliente) from cliente;";
    $d = $db->query($f);

    if($d){
        if($res = mysqli_fetch_assoc($d)){
            echo json_encode($res);
        }
        else{
            echo json_encode("0");
        }
    }
    else{
        echo json_encode(mysqli_error($db));
    }
    mysqli_close($db);
}



?>