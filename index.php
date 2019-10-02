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
    case 'existImg2':
        fileExists3($datos);
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
    case 'actualizarCliente':
        modifCliente($datos);
        break;
    case 'getCategoria':
        getCategorias();
        break;
    case 'addMaquina':
        addMaquina($datos);
        break;
    case 'getInfoAparato':
        getInfoAparato();
        break;
    case 'modifAparato':
        modifAparato($datos);
        break;
    case 'getPuestos':
        getPuestos();
        break;

     // PAGOS
    case 'addPago':
        addPago($datos);
        break;
    case 'getPaquete':
        getPaquete($datos);
        break;
    case 'getUsuario':
        getUsuario($datos);
        break; 
    case 'getAllPays':
        getAllPays();
        break;
    case 'getRecibe':
        getRecibe($datos);
        break;
    case 'getClientePay':
        getClientePay($datos);
        break;
    case 'paqueteNombre':
        getPNombre($datos);
        break;
    case 'eliminarPago':
        eliminarPago($datos);
        break;
    case 'addCambioPago':
        addCambioPago($datos);
        break;

    //PAQUETES
    case 'addPaquete':
        addPaquete($datos);
        break;
    case 'getAllPacks':
        getAllPacks($datos);
        break;
    case 'eliminarPaquete':
        eliminarPaquete($datos);
        break;
    case 'activarPaquete':
        activarPaquete($datos);
        break;
    case 'addCambioPack':
        addCambioPack($datos);
        break;
    default:
        echo json_encode("-1");
}
// funcion que obbtiene los puestos

function getPuestos(){
    include "Conexion.php";
    $consulta = "select * from puesto;";
    $get = $db->query($consulta);
    if($get){
        $resultado= array();
        $puestos= array();
        while($res = mysqli_fetch_assoc($get)):
            $resultado[]=$res;
        endwhile;
       $puestos['puestos']=$resultado;
       echo json_encode($puestos);
    }
    else{
        echo json_encode("pnja");
    }

}

///// modificar aparato
function modifDetalleAparato($datos,$id_cat){
    include "Conexion.php";
    $consulta = "update info_aparato set id_categoria=".$id_cat.", estado='".$datos['estado']."',descripcion='".$datos['descripcion']."' where id=".$datos['id'].";";
    $get = $db->query($consulta);
    if($get){
       echo json_encode("exito");
    }
    else{
        echo json_encode(mysqli_error($db));
    }
}
function modifAparato($datos){
    $id_cat=$datos['categoria'];
    if($datos['categoria']=='0'){ // si es una  nueva categoria
        $id_cat=insertarCat($datos['otro']);
    }
    modifDetalleAparato($datos,$id_cat);
}

///// fin modificar / / // / 
function getInfoAparato(){
    include "Conexion.php";
    $consulta = "select i.id,i.id_categoria,i.estado,i.descripcion,i2.nombre from info_aparato i inner join aparato i2 on i.id_categoria=i2.id;";
    $get = $db->query($consulta);
    if($get){
        $aparatos=array();
        $apar=array();
        
        while($res = mysqli_fetch_assoc($get)):
            $apar[]=$res;
        endwhile;
        $aparatos['aparatos']=$apar;
        echo json_encode($aparatos);
    }
    else{
        echo json_encode(mysqli_error($db));
    }

}

/////////////////////// aparato /////////////////////////////
function insertarCat($dato){
    include "Conexion.php";
    $consulta = "insert into aparato VALUES(NULL,'".$dato."');";
    $get = $db->query($consulta);
    if($get){
        return mysqli_insert_id($db); //obtiene el ultimo registro insertado
    }
    else{
        echo json_encode(mysqli_error($db));
    }

}
function agregarDetalleMaquina($datos,$id_cat){
    include "Conexion.php";
    $consulta = "insert into info_aparato VALUES(NULL,".$id_cat.",'".$datos['estado']."','".$datos['descripcion']."');";
    $get = $db->query($consulta);
    if($get){
       echo json_encode("exito");
    }
    else{
        echo json_encode(mysqli_error($db));
    }
}
function addMaquina($datos){
    $id_cat=$datos['categoria'];
    if($datos['categoria']=='0'){ // si es una  nueva categoria
        $id_cat=insertarCat($datos['otro']);
    }
    agregarDetalleMaquina($datos,$id_cat);

}

function getCategorias(){
    include "Conexion.php";
    $consulta = "Select * from aparato;";
    $get = $db->query($consulta);
    if($get){
        $cat=array();
        $cat2=array();
        while($res = mysqli_fetch_assoc($get)):
            $cat[]=$res;
        endwhile;
        $cat2['categoria']=$cat;
        echo json_encode($cat2);
    }
    else{
        echo json_encode(mysqli_error($db));
    }

}


/////////////////  FUNCIONES MODIFICAR CLIENTE /////////////////////////////

// verifica si se modifico el usuario de un cliente
function verifSelfUser($datos){
    include "Conexion.php";
    $consulta = "select user from access where id=".$datos['id_access'].";";
    $get = $db->query($consulta);
    if($get){
        if($res = mysqli_fetch_assoc($get)){
            if($res['user']==$datos['user']){
                return "0";   // no cambio su id
            }else{
                return "1";  // cambio su id
            }
        }else{
            return "0";
        }
    }
    else{
        echo json_encode(mysqli_error($db));
    }
    return "0";
}
function actUser($datos){
    include "Conexion.php";
    $f = "update access set user='".$datos['user']."', password='".$datos['password']."' where id=".$datos['id_access'].";";
    $d = $db->query($f);
    if($d){
        
    }
    else{
        
    }
}

function insertModific($datos,$cp,$col){
    include "Conexion.php";
    $f = "update cliente set nombre='".$datos['nombre']."', apellido_p='".$datos['apellidoP']."', apellido_m='".$datos['apellidoM']."', foto='".$datos['foto']."', calle='".$datos['calle']."', numero_calle='".$datos['numero']."', telefono='".$datos['telefono']."',id_cp=".$cp.",id_colonia=".$col.", fecha_nacimiento='".$datos['fechanac']."', numero_interior='".$datos['numeroint']."', genero='".$datos['gender']."' where id_cliente=".$datos['id_cliente'].";";
    $d = $db->query($f);
    if($d){
        echo json_encode("exito");
    }
    else{
        echo json_encode("-1");
    }
}
function getKeysMidif($datos){
    $valCp=getCP($datos);  //verifica si ya existe el cp
    $col = "0";
    if($valCp=="0"){  // si no existe lo inserta
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
    
    if($cp!="0" & $col!="0" ){  /// si las llaves son distintas de cero
        insertModific($datos,$cp,$col);
    }else{
        echo json_encode("-1"); 
    }
}

function modifCliente($datos){  
    $verif_self_user = verifSelfUser($datos);
    if($verif_self_user=="0"){  /// si es cero, no se modificó su usuario
        getKeysMidif($datos);
        actUser($datos);
    }
    else{ // si lo modifico
        $valAccesso = getAcceso($datos);    //verifica si el id es repetido
        $accesso="0";
        if($valAccesso=="0"){   //si no existe ese usuario
            getKeysMidif($datos);
            actUser($datos);
         }else{  /// si el id es repetido
            echo json_encode("id_rep");
        }
    } 
}
////////////////// FIN MODIFICAR CLIENTE  ///////////////////////

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
function fileExists3($datos) {
    $filePath='C:\xampp\htdocs\gymdb\imgs\employees\\'.$datos['path'];
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
    $f = "insert into cliente values(NULL,'".$datos1['nombre']."','".$datos1['apellidoP']."','".$datos1['apellidoM']."','".$datos1['foto']."','".$datos1['calle']."','".$datos1['numero']."','".$datos1['telefono']."',CURDATE(),".$cp1.",".$col1.",".$accesso1.",'".$datos1['fechanac']."',1,'".$datos1['numeroint']."','".$datos1['gender']."',NULL);";
    //$f = "insert into cliente values(NULL,'edgar','sanchez','esp','cac','vfrv','15','33',CURDATE(),5,5,10,'03-05-21',1,'78','M');";
    //echo json_encode($f);
    $d = $db->query($f);
    if($d){
        echo json_encode("exito");
    }
    else{
        echo json_encode("-2");
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
        if($valCp=="0"){  // si no existe lo inserta
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
        $f="0";
        if($res = mysqli_fetch_assoc($d)){
            return $res;
        }else{
            return $f;
        }
    }
    else{
        echo json_encode(mysqli_error($db));
    }
    return $f;
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
        }else{
            return "0";
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
    //mysqli_close($db);
}


///////////////////////////////////////////////
//PAGOS

function getRecibe($datos){
    include "Conexion.php";
    $consulta = "select nombre, descripcion, precio from paquete where id=".$datos['id_paquete'].";";
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

function getClientePay($datos){
    include "Conexion.php";
    $consulta = "select * from cliente where id_cliente=".$datos['id_cliente'].";";
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

function getPaquete($datos){
    $query="SELECT * FROM paquete where activo = 1";
    execQuery($query);
}

function imprime($resultado){
    echo json_encode($resultado);
}

function execQuery($query){
    include "Conexion.php";
    $consulta=$db->query($query);
    $res=array();
    if($consulta){
        while($resultado=mysqli_fetch_assoc($consulta)):
            $res[]=$resultado;
        endwhile;
        imprime($res);
    } else{
        imprime(mysqli_error($db));
    }
}

function addPago($datos){   
    $validation = getPack2($datos);
    $validUser = getValidUser($datos);
    
    if($validation == "0"){
        echo json_encode("Paquete Invalido"); 
    }
    else if($validation['activo']==0){
        echo json_encode("Paquete Inactivo"); 
    }
    else{
        if($validUser == "0"){
           echo json_encode("Cliente Invalido"); 
        }
        else{
            insertPago($datos, $validation);
            }
        } 
}

function insertPago($datos1, $validation1){
    include "Conexion.php";
    $f = "insert into cliente_paquete values(NULL,CURDATE(),ADDDATE(CURDATE(), interval '".$validation1['duracion']."' day),'".$datos1['id_usuario']."','".$validation1['id']."','".$datos1['monto']."','".$datos1['modo']."');";
    $d = $db->query($f);
    if($d){
        payActivarCliente($datos1);
    }
    else{
        echo json_encode($validation1['duracion']);
    }
    mysqli_close($db);
}

function getPack2($datos){
    include "Conexion.php";
    $f = "select id, precio, duracion,activo from paquete where nombre='".$datos['paquete']."';";
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

function getValidUser($datos){
    include "Conexion.php";
    $f = "select nombre, activo from cliente where id_cliente='".$datos['id_usuario']."';";
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

function getAllPays(){
    include "Conexion.php";
        
    $consulta = "Select id_pago, fecha_pago, fecha_vencimiento, id_cliente, id_paquete, monto,modo from cliente_paquete;";
    $get = $db->query($consulta);
    if($get){
        $pagos=array();
        $pagos2=array();
        
        while($res = mysqli_fetch_assoc($get)):
            $pagos2[]=$res;
        endwhile;
        $pagos['pagos']=$pagos2;
        echo json_encode($pagos);
    }
    else{
        echo json_encode(mysqli_error($db));
    }
}

function eliminarPago($datos){
    include "Conexion.php";
    $elimination= "0";
    $consulta = "update cliente_paquete set monto=".$elimination.", fecha_vencimiento= null where id_pago=".$datos['id_pago'].";";
    $elim = $db->query($consulta);
    if($elim){
        echo json_encode("exito");
    }else{
        echo json_encode("-1");
    }
}

function addCambioPago($datos){
    $validation = getPack($datos);
    $validUser = getValidUser($datos);
    
    if($validation == "0"){
        echo json_encode("Paquete Invalido"); 
    }
    else if($validation['activo']==0){
        echo json_encode("Paquete Inactivo"); 
    }
    else{
        if($validUser == "0"){
           echo json_encode("Cliente Invalido"); 
        }
        else if($validUser['activo'] == 0){
           echo json_encode("Cliente Inactivo"); 
        }
        else{
            insertCambioPago($datos, $validation);
            }
        } 
}

function getPack($datos){
    include "Conexion.php";
    $f = "select nombre, precio, duracion,activo from paquete where id='".$datos['paquete']."';";
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

function insertCambioPago($datos1, $validation1){
    include "Conexion.php";
    $id = $datos1['id'];
    $usuario = $datos1['id_usuario'];
    $monto = $datos1['monto'];
    $modo = $datos1['modo'];
    $paquete = $datos1['paquete'];
    
    $f = "update cliente_paquete set id_cliente = $usuario, id_paquete = $paquete, monto = $monto, modo = '".$modo."' where id_pago = $id ;";
    $d = $db->query($f);
    if($d){
        echo json_encode("Pago exitoso");
    }
    else{
        echo json_encode("Error en insertCambioPago");
    }
    mysqli_close($db);
}

function payActivarCliente($datos1){
    include "Conexion.php";
    
    $id = $datos1['id_usuario'];
    $status = 1;
    $lastPay = getLastPay($id);

    $f = "update cliente set activo = $status, ultimo_pago= '".$lastPay['id_pago']."' where id_cliente = $id ;";
    $d = $db->query($f);
    if($d){
        echo json_encode("Pago exitoso");
    }
    else{
        echo json_encode("Error en insertCambioPago");
    }
    mysqli_close($db);
}

function getLastPay($datos){
    
    include "Conexion.php";
    $f = "select id_pago from cliente_paquete where id_cliente='".$datos."' and fecha_pago = CURDATE();";
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

//FIN PAGOS

//PAQUETES

function addPaquete($datos){   
    $validation = getNamePack($datos);

    if($datos['duracion'] > 365){
        echo json_encode("Duracion Invalida");
    }
    
    else if($validation != "0"){
        echo json_encode("Nombre Invalido");
    }
    
    else{
        insertPaquete($datos);
    }
}


function getNamePack($datos){
    include "Conexion.php";
    $f = "select id from paquete where nombre='".$datos['nombre']."';";
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

function insertPaquete($datos1){
    include "Conexion.php";
   $f = "insert into paquete (nombre, descripcion, precio, duracion, activo) values('".$datos1['nombre']."','".$datos1['descripcion']."','".$datos1['precio']."','".$datos1['duracion']."','1');";
    $d = $db->query($f);
    if($d){
       echo json_encode("Paquete Exitoso");
    }
    else{
        echo json_encode("Error");
    }
    mysqli_close($db);  
}

function getAllPacks(){
    include "Conexion.php";
        
    $consulta = "Select id, nombre, descripcion, precio, duracion, activo from paquete;";
    $get = $db->query($consulta);
    if($get){
        $packs=array();
        $packs2=array();

        while($res = mysqli_fetch_assoc($get)):    
            $packs2[]=$res;
        endwhile;
        $packs['packs']=$packs2;
        echo json_encode($packs);
    }
    else{
        echo json_encode(mysqli_error($db));
    }
}

function eliminarPaquete($datos){
    include "Conexion.php";
    $elimination= "0";
    $consulta = "update paquete set activo=".$elimination." where id = ".$datos['id']." ;";
    $elim = $db->query($consulta);
    if($elim){
        echo json_encode("exito");
    }else{
        echo json_encode("-1");
    }
}

function activarPaquete($datos){
    include "Conexion.php";
    $elimination= "1";
    $consulta = "update paquete set activo=".$elimination." where id = ".$datos['id']." ;";
    $elim = $db->query($consulta);
    if($elim){
        echo json_encode("exito");
    }else{
        echo json_encode("-1");
    }
}

function addCambioPack($datos){   
    $validation = getNamePack($datos);

    if($datos['duracion'] > 365){
        echo json_encode("Duracion Invalida");
    }
    
    else{
        insertEditPaquete($datos);
    }
}

function insertEditPaquete($datos1){
    include "Conexion.php";
   $f = "update paquete set nombre = '".$datos1['nombre']."', descripcion = '".$datos1['descripcion']."', precio = '".$datos1['precio']."', duracion = '".$datos1['duracion']."',activo = '1' where id = '".$datos1['id']."';";
    $d = $db->query($f);
    if($d){
       echo json_encode("Paquete Exitoso");
    }
    else{
        echo json_encode("Error");
    }
    mysqli_close($db);  
}

//FIN PAQUETES

//EXTRA
function getUsuario($datos){
    include "Conexion.php";
    $f = "select id,user,password,tipo from access where user='".$datos['user']."';";
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

function checkValidUser($datos){
    include "Conexion.php";
    $f = "update cliente set activo = 0 where id_cliente= '".$datos['usuario']."';";
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

function getPP($datos){
    include "Conexion.php";
    $f = "select id, precio, duracion from paquete where nombre='".$datos['paquete']."';";
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

function getPNombre($datos){
    include "Conexion.php";
    $nombre = $datos['id_paquete'];
    $var = array();
    
    $f = "select nombre from paquete where id= $nombre ;";
    $d = $db->query($f);
    if($d){
         while($res = mysqli_fetch_assoc($d)):
            $var[] = $res;

        endwhile;
        echo json_encode($var);
    }
    else{
        echo json_encode(mysqli_error($db));
    }
    return "0";
}

function getNombrePaquete($datos){
    include "Conexion.php";
    $f = "select nombre from paquete where id='".$datos['id_paquete']."';";
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
//FIN EXTRA
?>