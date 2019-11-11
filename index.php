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

    //aparatos
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
    case 'updateAdminAparato':
        updateAdminAparato($datos);
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

    // empleado
    case 'addEmpleado':
        addEmpleado($datos);
        break;
    case 'getAllEmployees':
        getAllEmployees($datos);
        break;
    case 'activarEmpleado':
        activarEmpleado($datos);
        break;
    case 'eliminarEmpleado':
        eliminarEmpleado($datos);
        break;
    case 'getAccesoEmployee';
        getAccesoEmployee($datos);
        break;
    case 'actualizarEmpleado':
        modifEmpleado($datos);
        break;

    //ASISTENCIA
    case 'getAsistToday':
        getAsistToday($datos);
        break;

    case 'asistencia':
        asistencia($datos);
        break;

    //Historial
    case 'getHistorial':
        getHistorial();
        break;

    //Reportes
    case 'getReportePagos':
        getReportePagos($datos['dias']);
        break;
    case 'getReporteAsistencia':
        getReporteAsistencia($datos['dias']);
        break;
    case 'getReporteVenta':
        getReporteVenta($datos['dias']);
        break;
    case 'getReportePagosRange':
        getReportePagosRange($datos);
        break;
    case 'getReporteAsistenciasRange':
        getReporteAsistenciasRange($datos);
        break;
    case 'getReporteVentasRange':
        getReporteVentasRange($datos);
        break;
    

    /// MODULO CLIENTE 
    case 'customerAsist7':
        customerAsist7($datos);
        break;
    case 'customerAsist15':
        customerAsist15($datos);
        break;
    case 'customerAsist30':
        customerAsist30($datos);
        break;
    case 'getCostumerPays':
        getCostumerPays($datos);
        break;
    
    // TIENDA  //
    case 'getProductosProveedores':
        getProductosProveedores();
        break;
    case 'addProducto':
        addProducto($datos);
        break;
    case 'modifProducto':
        modifProducto($datos);
        break;
    //////// COMPRA   /////
    case 'getProductosActivos':
        getProductosActivos();
        break;
        
    case 'comprar':
       comprar($datos);
       break;
    //NEW
    case 'getAllProducts':
        getAllProducts();
        break;
    case 'eliminarProducto':
        eliminarProducto($datos);
        break;
    case 'activarProducto':
        activarProducto($datos);
        break;
    default:
        echo json_encode("-1");
}


//NEW
function getAllProducts(){
    include "Conexion.php";
        
    $consulta = "select i.id_producto,i.nombre,i.descripcion,i.precio_entrada,i.precio_salida,i.activo,i.disponibles as cantidad,i.id_proveedor, i2.nombre as proveedor
    from producto i inner join proveedor i2 on i.id_proveedor=i2.id;";
    $get = $db->query($consulta);
    if($get){
        $products=array();
        $products2=array();

        while($res = mysqli_fetch_assoc($get)):    
            $products2[]=$res;
        endwhile;
        $products['products']=$products2;
        echo json_encode($products);
    }
    else{
        echo json_encode(mysqli_error($db));
    }
}

function eliminarProducto($datos){
    include "Conexion.php";
    $elimination= "0";
    $consulta = "update producto set activo=".$elimination." where id_producto = ".$datos['id_producto']." ;";
    $elim = $db->query($consulta);
    if($elim){
        echo json_encode("exito");
    }else{
        echo json_encode("-1");
    }
}

function activarProducto($datos){
    include "Conexion.php";
    $elimination= "1";
    $consulta = "update producto set activo=".$elimination." where id_producto = ".$datos['id_producto']." ;";
    $elim = $db->query($consulta);
    if($elim){
        echo json_encode("exito");
    }else{
        echo json_encode("-1");
    }
}
//FIN NEW

/////////////////////////// TIENDA /////////////////////////

//////////////// COMPRA   ///////////////////
function comprar($datos){

    $id_admin=$datos['id_admin'];                                               // obtiene el id del admin

    foreach($datos['productos'] as $value){                                     // recorre el arreglo
         insertarRegistroCompra($value,$id_admin);                               // inserta registro de compra
         actualizarDisponibles($value['id_producto'],$value['cantidad']);        // actualiza disponibilidad de productos
    }

    getProductosActivos();
}

/// FUNCION INSERTAR REGISTROS DE COMPRA /////
function insertarRegistroCompra($datos,$id_admin){
    include "Conexion.php";
    $f="insert into empleado_producto values(NULL,".$id_admin.",".$datos['id_producto'].",".$datos['cantidad'].",CURDATE(),".$datos['p_v'].",".$datos['p_c'].");";
    $d = $db->query($f);
    if($d){
       
    }else{
        echo json_encode(mysqli_error($db));
    }
}


/////// ACTUALIZAR EXISTENCIA PRODUCTOS //////
function actualizarDisponibles($id_p,$cantidad){
    include "Conexion.php";
    $f="update producto set disponibles = disponibles - ".$cantidad." where id_producto=".$id_p.";";
    $d = $db->query($f);
    if($d){
       
    }else{
        echo json_encode(mysqli_error($db));
    }
}


////OBTIENE TODOS LOS PRODUCTOS ACTIVOS Y QUE TENGAN EXISTENCIA   ///
function getProductosActivos(){
    include "Conexion.php";
    $f="select * from producto where activo=1 and disponibles > 0;";
    $d = $db->query($f);

    if($d){
        $lista=array();
        $registros=array();
        
        while($registro = mysqli_fetch_assoc($d)):
            $registros[]=$registro;
        endwhile;
        $lista['productos']=$registros;
        echo json_encode($lista);
    }else{
        echo json_encode(mysqli_error($db));
    }
}
//////////// FIN COMPRA /////////////

///// MODIFICAR PRODUCTO  ////
/// verifica si el nuevo nombre ya está asignado a otro producto //
function verifNameProduct($datos){
    include "Conexion.php";
    $f="select * from producto where nombre='".$datos['nuevo_producto']."' and id_producto!=".$datos['producto'].";";
    $d = $db->query($f);
    if($d){
        if($res = mysqli_fetch_assoc($d)){
            return "1";   // existe otro producto con el mismo nombre
        }else{
            return "0"; // no existe ese producto
        }
    }else{
        echo json_encode(mysqli_error($db));
    }

}
// inserta modificacion //
function insertModif($datos,$id_prov){
    include "Conexion.php";
    $f="update producto set nombre='".$datos['nuevo_producto']."', descripcion='".$datos['descripcion']."', precio_entrada=".$datos['precio_entrada'].",
     precio_salida=".$datos['precio_salida'].", disponibles=".$datos['cantidad'].", id_proveedor=".$id_prov." where id_producto=".$datos['producto'].";";
    $d = $db->query($f);
    if($d){
        echo json_encode("exito");
    }else{
        echo json_encode(mysqli_error($db));
    }
}

// funcion modificar
function modifProducto($datos){
    $verif_product= verifNameProduct($datos); // verifica si el nombre le podría pertenecer a otro producto
    if($verif_product == "0"){   // si no 
        $id_prov= getIdProveedor($datos); // obtiene el id del proveedor
        if($id_prov=="0"){ // no existe proveedor
            $id_prov=insertProveedor($datos);  // lo inserta y recupera su llave
        }
       insertModif($datos,$id_prov);   // actualiza los nuevos datos
    }else{
        echo json_encode("product_rep"); //mensaje de error, nombre repetido de productos
    }
}
////// FIN MODIFICAR PRODUCTO ///


////AGREGAR PRODUCTO //////////
// Inserta un nuevo producto al sistema //
function insertarNuevoProducto($datos,$id_prov){
    include "Conexion.php";
    $f="insert into producto values(NULL,'".$datos['nuevo_producto']."','".$datos['descripcion']."',".$datos['precio_entrada'].",".$datos['precio_salida'].",'".$datos['cantidad']."',".$id_prov.",1);";
    $d = $db->query($f);
    if($d){
        echo json_encode("exito");
    }else{
        echo json_encode(mysqli_error($db));
    }
}
// verifica si existe el proveedor que se le envia por parámetro
function getIdProveedor($datos){
    include "Conexion.php";
    $f="select id from proveedor where nombre='".$datos['proveedor']."';";
    $d = $db->query($f);
    if($d){
        if($res = mysqli_fetch_assoc($d)){
            return $res['id'];
        }else{
            return "0";
        }
    }else{
        echo json_encode(mysqli_error($db));
    }
}

// inserta un nuevo proveedor al sistema
function insertProveedor($datos){
    include "Conexion.php";
    $f="insert into proveedor values(NULL,'".$datos['proveedor']."');";
    $d = $db->query($f);
    if($d){
        return mysqli_insert_id($db);
    }else{
        echo json_encode(mysqli_error($db));
    }
}
// suma la cantidad de un producto existente con la cantidad actual
function sumaCantidad($datos){
    include "Conexion.php";
    $f="update producto set disponibles = disponibles +".$datos['cantidad']." where id_producto =".$datos['producto'].";";
    $d = $db->query($f);
    if($d){
       echo json_encode("exito");
    }else{
        echo json_encode(mysqli_error($db));
    }
}
// verifica si ya existe el producto en el sistema
function verifExisteProducto($datos){
    include "Conexion.php";
    $f="select * from producto where nombre ='".$datos['nuevo_producto']."';";
    $d = $db->query($f);
    if($d){
        if($res = mysqli_fetch_assoc($d)){
            return "-1";// ya existe un producto con ese nombre
        }else{
            return "0"; // no existe ese producto
        }  
    }else{
        echo json_encode(mysqli_error($db));
    }
       
}
function addProducto($datos){
    if($datos['producto']=='0'){ // si es un  nuevo producto
        $existe =verifExisteProducto($datos);
        if($existe=="0"){  // verifica si ya existe el producto en el sistema
            $id_proveedor=getIdProveedor($datos); // verifica si existe proveedor, 0=NO
            if($id_proveedor=="0"){ // si no existe, lo inserta
                $id_proveedor = insertProveedor($datos);
            }
            insertarNuevoProducto($datos,$id_proveedor); // inserta un nuevo producto
        }
        else{
            echo json_encode("product_rep"); // producto repetido
        }
    }else{ /// si ya existe ese producto
        sumaCantidad($datos);  // le suma la  cantidad nueva a la cantidad  existente
    }
}
////// FIN AGREGAR PRODUCTO  ////

// OBTIENE DATOS DE PRODUCTOS  //
function getProductosProveedores(){
    include "Conexion.php";
    $f="select id_producto as id,nombre from producto;";
    $d = $db->query($f);

    if($d){
        $lista=array();
        $registros=array();
        
        while($registro = mysqli_fetch_assoc($d)):
            $registros[]=$registro;
        endwhile;
        $lista['productos']=$registros;
        echo json_encode($lista);
    }else{
        echo json_encode(mysqli_error($db));
    }
}

/////////////////////////////   FIN TIENDA      //////////////////////


/////// /////////////////////   REPORTES         /////////////////////
function getReporteVentasRange($datos){
    include "Conexion.php";
    $f = "select i.id, i.fecha, i.id_empleado, i.id_producto, i.cantidad, i.p_c, i.p_v, i2.nombre
    from empleado_producto i inner join producto i2 on i.id_producto=i2.id_producto  and i.fecha between '".$datos['inicio']."' and '".$datos['fin']."';";
    $d = $db->query($f);
    if($d){
        if($res = mysqli_fetch_assoc($d)){
            $lista=array();
            $registros=array();
            $registros[]=$res;
            while($registro = mysqli_fetch_assoc($d)):
                $registros[]=$registro;
            endwhile;
            $lista['ventas']=$registros;
            echo json_encode($lista);
        }
        else{
            echo json_encode("null");
        }  
    }
    else{
        echo json_encode(mysqli_error($db));   // responde un mensaje de error
    }
}

/// OBTIENE TODOS LOS REGISTROS DENTRO DE UN RANGO    ////
function getReporteAsistenciasRange($datos){
    include "Conexion.php";
    $f = "select i.id_fecha,i.id_cliente, CONCAT(i2.apellido_p,' ',i2.apellido_m,' ',i2.nombre) as Nombre, i3.fecha 
    from asistencia i inner join cliente i2 on i.id_cliente=i2.id_cliente inner join fecha i3 on i.id_fecha=i3.id and i3.fecha between '".$datos['inicio']."' and '".$datos['fin']."';";
    $d = $db->query($f);
    if($d){
        if($res = mysqli_fetch_assoc($d)){
            $lista=array();
            $registros=array();
            $registros[]=$res;
            while($registro = mysqli_fetch_assoc($d)):
                $registros[]=$registro;
            endwhile;
            $lista['asistencias']=$registros;
            echo json_encode($lista);
        }
        else{
            echo json_encode("null");
        }  
    }
    else{
        echo json_encode(mysqli_error($db));   // responde un mensaje de error
    }
}

/// OBTIENE TODOS LOS REGISTROS DENTRO DE UN RANGO    ////
function getReportePagosRange($datos){
    include "Conexion.php";
    $f = "select i.id_pago,i.fecha_pago,i.fecha_vencimiento,i.id_cliente,i.id_paquete,i.monto,i.modo, CONCAT(i2.apellido_p,' ',i2.apellido_m,' ',i2.nombre) as Nombre, i3.nombre as paquete
    from cliente_paquete i inner join cliente i2 on i.id_cliente=i2.id_cliente inner join paquete i3 on i.id_paquete=i3.id and i.fecha_pago between '".$datos['inicio']."' and '".$datos['fin']."';";
    $d = $db->query($f);
    if($d){
        if($res = mysqli_fetch_assoc($d)){
            $lista=array();
            $registros=array();
            $registros[]=$res;
            while($registro = mysqli_fetch_assoc($d)):
                $registros[]=$registro;
            endwhile;
            $lista['pagos']=$registros;
            echo json_encode($lista);
        }
        else{
            echo json_encode("null");
        }  
    }
    else{
        echo json_encode(mysqli_error($db));   // responde un mensaje de error
    }
}

/// OBTIENE TODOS LOS REGISTROS DE PAGOS    ////

function getReporteVenta($dias){
    include "Conexion.php";
    $f = "select i.id, i.fecha, i.id_empleado, i.id_producto, i.cantidad, i.p_c, i.p_v, i2.nombre
    from empleado_producto i inner join producto i2 on i.id_producto=i2.id_producto  and i.fecha between date_sub(curdate(), interval ".$dias." day) and curdate();";
    $d = $db->query($f);
    if($d){
        $lista=array();
        $registros=array();
        while($registro = mysqli_fetch_assoc($d)):
            $registros[]=$registro;
        endwhile;
        $lista['ventas']=$registros;
        echo json_encode($lista);
    }
    else{
        echo json_encode(mysqli_error($db));   // responde un mensaje de error
    }

}

function getReportePagos($dias){
    include "Conexion.php";
    $f = "select i.id_pago,i.fecha_pago,i.fecha_vencimiento,i.id_cliente,i.id_paquete,i.monto,i.modo, CONCAT(i2.apellido_p,' ',i2.apellido_m,' ',i2.nombre) as Nombre, i3.nombre as paquete
    from cliente_paquete i inner join cliente i2 on i.id_cliente=i2.id_cliente inner join paquete i3 on i.id_paquete=i3.id and i.fecha_pago between date_sub(curdate(), interval ".$dias." day) and curdate();";
    $d = $db->query($f);
    if($d){
        $lista=array();
        $registros=array();
        while($registro = mysqli_fetch_assoc($d)):
            $registros[]=$registro;
        endwhile;
        $lista['pagos']=$registros;
        echo json_encode($lista);
    }
    else{
        echo json_encode(mysqli_error($db));   // responde un mensaje de error
    }
}

/// OBTIENE TODOS LOS REGISTROS DE ASISTENCIAS    ////
function getReporteAsistencia($dias){
    include "Conexion.php";
    $f = "select i.id_fecha,i.id_cliente, CONCAT(i2.apellido_p,' ',i2.apellido_m,' ',i2.nombre) as Nombre, i3.fecha 
    from asistencia i inner join cliente i2 on i.id_cliente=i2.id_cliente inner join fecha i3 on i.id_fecha=i3.id and i3.fecha between date_sub(curdate(), interval ".$dias." day) and curdate();";
    $d = $db->query($f);
    if($d){
        $lista=array();
        $registros=array();
        while($registro = mysqli_fetch_assoc($d)):
            $registros[]=$registro;
        endwhile;
        $lista['asistencias']=$registros;
        echo json_encode($lista);
    }
    else{
        echo json_encode(mysqli_error($db));   // responde un mensaje de error
    }
}


//////////////////   FIN REPORTES   ////////////////////////////

/////////// funcion que obtiene el historial de modificaciones de aparatos  //////////////
function getHistorial(){
    include "Conexion.php";
    $f = "select i.id_aparato,i.id_admin,i.fecha,i.accion,i2.id_categoria, i3.nombre,CONCAT(i4.apellido_p,' ',i4.apellido_m,' ',i4.nombre) as Nombre from admin_aparato i inner join info_aparato i2 on id_aparato=i2.id
     inner join aparato i3 on i2.id_categoria=i3.id inner join empleado i4 on i.id_admin=i4.id_empleado;";
    $d = $db->query($f);
    if($d){
        $lista=array();
        $registros=array();
        while($registro = mysqli_fetch_assoc($d)):
            $registros[]=$registro;
        endwhile;
        $lista['historial']=$registros;
        echo json_encode($lista);

    }
    else{
        echo json_encode(mysqli_error($db));   // responde un mensaje de error
    }

}


/////////////////   MODIFICAR EMPLEADO   ////////////////////////
function insertModifEmpleado($datos1,$cp1,$col1,$accesso1,$puesto){
    include "Conexion.php";
    $f = "update empleado set nombre='".$datos1['nombre']."',apellido_p='".$datos1['apellidoP']."',apellido_m='".$datos1['apellidoM']."',
    foto='".$datos1['foto']."',calle='".$datos1['calle']."',numero_calle='".$datos1['numero']."',telefono='".$datos1['telefono']."',
    id_cp=".$cp1.",id_colonia=".$col1.",id_acceso=".$accesso1.",id_puesto=".$puesto.",fecha_nacimiento='".$datos1['fechanac']."',
    activo=1,numero_interior='".$datos1['numeroint']."',genero='".$datos1['gender']."' where id_empleado=".$datos1['id_empleado'].";";
    
    $d = $db->query($f);
    if($d){
        echo json_encode("exito");
    }
    else{
        echo json_encode("-1");
    }
}

// elimina un usuario ///
function deleteUser($llave){
    include "Conexion.php";
    $f = "delete from access where id=".$llave.";";
    $d = $db->query($f);
    if($d){
        
    }
    else{
        echo json_encode(mysqli_error($db));   // responde un mensaje de error
    }
}
function actualizarEmpleado($datos){
        $accesso = $datos['id_access'];
        $puesto = $datos['puesto']; // id puesto

        if($datos['puesto']=="0"){  //si es un nuevo puesto de trabajo
            $puesto = setPuesto($datos); // inserta el nuevo puesto y obtiene su id
        }
        
        $valCp=getCP($datos);  //verifica si ya existe el cp
        $cp ="0";
        if($valCp=="0"){  // si no existe lo inserta
            $cp = setCP($datos);    // recupera la llave primaria
         }
         else{      
            $cp = $valCp['id'];  // si existe recupera la llave primaria
        }

        $valCol=getCol($datos);   //verifica si existe la colonia
        $col = "0";
        if($valCol=="0"){ //si no, la inserta
            $col = setCol($datos); // recupera la llave primaria
        }
        else{
            $col = $valCol['id'];  //si ya existe obtiene su llave primaria
        }

        if($cp!="0" & $col!="0" & $accesso!="0"){  /// si las llaves son distintas de cero
            insertModifEmpleado($datos,$cp,$col,$accesso,$puesto); 
         }
         else{
             echo json_encode("-1"); 
        } 
   
}

// verifica si un empleado tiene previa cuenta en el sistema ///
/*function existAcount($datos){
    include "Conexion.php";
    $f = "select * from access where id=".$datos['id_access'].";";
    $get = $db->query($f);
    if($get){
        if($res = mysqli_fetch_assoc($get)){  // verifica si hay un registro con ese id
            return "1"; // si existe
        }
        else{
            return "0";    // no existe
        }
    }
    else{
        echo json_encode(mysqli_error($db));   // responde un mensaje de error
    }
}*/

// crea una cuenta nueva ///
function createAcount($datos,$tipo){
    include "Conexion.php";
    $f = "insert into access values(NULL,'".$datos['user']."','".$datos['password']."','".$tipo."');";
    $d = $db->query($f);
    if($d){
        return mysqli_insert_id($db);
    }
    else{
        echo json_encode(mysqli_error($db));   // responde un mensaje de error
    }
}

// funcion previa a insertar la actualizacion del empleado, verifica ciertas validaciones
function modifEmpleado($datos){
    $valAccesso="0";
    
    if($datos['admin']=="1"){       // administrador
        if($datos['id_access']){   // verifica si ya tiene previa cuenta
            $verif_user= verifSelfUser($datos);    //verifica si el empleado tiene cuenta previa, si su usuario es igual o si cambio su usuario
            if($verif_user=="1"){// si cambio su usuario (ya tenia cuenta previa) 
                $valAccesso = getAcceso($datos);    //verifica si el id nuevo ya esta en el sistema
                if($valAccesso=="0"){   //si no existe ese usuario, entonces se hará la actualizacion
                    //actualiza
                    actUser($datos);        // actualiza el usuario del empleado
                    actualizarEmpleado($datos);    /// llama a la funcion que actualiza los datos del empleado
                 }else{  /// si el id es repetido
                    echo json_encode("id_rep"); // ERROR
                }
            }elseif($verif_user=="0"){           // si no cambio su usuario(ya tenia cuenta previa), es decir si es el mismo usuario
                //actualiza
                actualizarEmpleado($datos);/// llama a la funcion que actualiza los datos
            }
        }
        else{   // si el epleado no tenia cuenta previa
             //crea nueva cuenta
            $valAccesso= getAcceso($datos);   // verifica si ya existe el usuario
            if($valAccesso=="0"){   //si no existe ese usuario
                $id_cuenta = createAcount($datos,"2");      // crea cuenta de administrador y recupera el id
                $datos['id_access']=$id_cuenta; // asigna el campo id_access con la llave id_cuenta
                
                //actualiza
               actualizarEmpleado($datos);    /// llama a la funcion que actualiza los datos del empleado
             }else{  /// si el id es repetido
                echo json_encode("id_rep"); // ERROR
            }
        }
    }
    else{           // si el empleado no va a tener cuenta de administrador
        if($datos['id_access']){

            $llave = $datos['id_access'];
            $datos['id_access']="NULL";          // se pone nulo ya que no tendrá cuenta 
       
            actualizarEmpleado($datos);  // actualiza los datos del empleado
            deleteUser($llave); // elimina la cuenta del empleado
            
          
        }else{   // si no hubo cambio es decir, antes no tenia cuenta y ahora tampoco creará cuenta
            $datos['id_access']="NULL";          // se pone nulo ya que no tendrá cuenta 
            actualizarEmpleado($datos);
        }
          
    }  
}

/////////////   FIN MODIFICAR EMPLEADO   ///////////////////////





function getAccesoEmployee($datos){
    include "Conexion.php";
    $consulta = "select user,password from access where id=".$datos['id_access'].";";
    $get = $db->query($consulta);
    if($get){
        if($res =mysqli_fetch_assoc($get)){
            $var = $res;
            echo json_encode($var);
        }
        else{
            echo json_encode("-1");
        }
    }else{
        echo json_encode("-1");
    }

}

/// ELIMINA CLIENTE ////
function eliminarEmpleado($datos){
    include "Conexion.php";
    $consulta = "update empleado set activo=0 where id_empleado=".$datos['id_empleado'].";";
    $elim = $db->query($consulta);
    if($elim){
        echo json_encode("exito");
    }else{
        echo json_encode("-1");
    }

}


// ACTIVA UN CLIENTE ///
function activarEmpleado($datos){
    include "Conexion.php";
    $consulta = "update empleado set activo=1 where id_empleado=".$datos['id_empleado'].";";
    $elim = $db->query($consulta);
    if($elim){
        echo json_encode("exito");
    }else{
        echo json_encode("-1");
    }


}


// obtiene todos los datos de empleado
function getAllEmployees($datos){
    include "Conexion.php";
    $consulta = "Select i.id_empleado,i.nombre,i.apellido_p,i.apellido_m,i.foto,i.calle,i.numero_calle,i.telefono,i.fecha_nacimiento,
    i.fecha_ingreso,i.id_cp,i.id_colonia,i.id_acceso,i.id_puesto,i.activo,i.numero_interior,i.genero,CONCAT(i.apellido_p,' ',i.apellido_m,' ',i.nombre) as Nombre
    ,i3.codigo,i4.nombre as colonia,i5.id as id_puesto,i5.puesto,i5.sueldo from empleado i inner join cp i3 on i.id_cp=i3.id inner join colonia i4 on i.id_colonia=i4.id inner join puesto i5 on i.id_puesto=i5.id;";
    $get = $db->query($consulta);
    if($get){
        $empleados=array();
        $resultado=array();
        
        while($res = mysqli_fetch_assoc($get)):
            $resultado[]=$res;
        endwhile;
        $empleados['empleados']=$resultado;
        echo json_encode($empleados);
    }
    else{
        echo json_encode(mysqli_error($db));
    }
}


//////////////////  AGREGAR EMPLEADO    //////////////////////

function insertEmpleado($datos1,$cp1,$col1,$accesso1,$puesto){  // recibe los datos y las llaves foráneas
    include "Conexion.php";
    $f = "insert into empleado values(NULL,'".$datos1['nombre']."','".$datos1['apellidoP']."','".$datos1['apellidoM']."','".$datos1['foto']."','".$datos1['calle']."','".$datos1['numero']."','".$datos1['telefono']."',CURDATE(),".$cp1.",".$col1.",".$accesso1.",".$puesto.",'".$datos1['fechanac']."',1,'".$datos1['numeroint']."','".$datos1['gender']."');";
    
    $d = $db->query($f);
    if($d){
        echo json_encode("exito");
    }
    else{
        echo json_encode("-1");
    }

}

function setPuesto($datos){  /// funcion que inserta un nuevo puesto en la base de datos
    include "Conexion.php";
    $f = "insert into puesto values(NULL,'".$datos['otro']."',".$datos['sueldo'].");";
    $d = $db->query($f);
    if($d){
        return mysqli_insert_id($db);
    }
    else{
        echo json_encode(mysqli_error($db));
    }
}

function addEmpleado($datos){
    $accesso="0";
    $valAccesso="0";
    if($datos['admin']=='1'){       // si tiene permiso de administrador
        $valAccesso = getAcceso($datos);    //verifica si el id es repetido
        if($valAccesso=="0"){// si no es repetido
            $accesso = setAcceso($datos,"2");  //inserta los nuevos datos y obtiene el id, se manda por parametro el tipo de cuenta
        }
    }
    else{
        $accesso = "NULL";// si no tiene cuenta, la llave foranea de accesso será nula
    }
    if($valAccesso=="0"){          //si no existe ese usuario
        $puesto = $datos['puesto']; // id puesto

        if($datos['puesto']=="0"){  //si es un nuevo puesto de trabajo
            $puesto = setPuesto($datos); // inserta el nuevo puesto y obtiene su id
        }
        
        $valCp=getCP($datos);  //verifica si ya existe el cp
        $cp ="0";
        if($valCp=="0"){  // si no existe lo inserta
            $cp = setCP($datos);    // recupera la llave primaria
         }
         else{      
            $cp = $valCp['id'];  // si existe recupera la llave primaria
        }

        $valCol=getCol($datos);   //verifica si existe la colonia
        $col = "0";
        if($valCol=="0"){ //si no, la inserta
            $col = setCol($datos); // recupera la llave primaria
        }
        else{
            $col = $valCol['id'];  //si ya existe obtiene su llave primaria
        }

        if($cp!="0" & $col!="0" & $accesso!="0"){  /// si las llaves son distintas de cero
            insertEmpleado($datos,$cp,$col,$accesso,$puesto); 
         }
         else{
             echo json_encode("-1"); 
            } 
    }
    else{  /// si el id es repetido
        echo json_encode("id_rep");   // responde un mensaje de error
    }  
}

/////////////////   FIN AGREGAR EMPLEADO   ////////////////////////////


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
        echo json_encode("-1");
    }
}

// funcion que verifica si determinada administrador modificó el mismo aparato previamente en el mismo dia
function verifSameDate_Admin($datos){
    include "Conexion.php";
    $consulta = "SELECT * FROM admin_aparato WHERE (id_admin = ".$datos['id_admin']." AND id_aparato = ".$datos['id']." AND fecha=CURDATE() AND accion='2');";
    $get = $db->query($consulta);
    if($get){
       if($res = mysqli_fetch_assoc($get)){
            return "1"; // un mismo admin ya ha modificado el mismo aparato, el mismo dia 
        }
        else{
            return "0";  // el admin no habia modificado el mismo aparato el mismo dia
        }
    }
    else{
       return "0";
    }  

}
// funcion que registra a los administradores que modifican o agregan nuevos aparatos y las fechas en que lo hicieron
function updateAdminAparato($datos,$id_aparato){
    include "Conexion.php";
    $consulta = "insert into admin_aparato values(NULL,".$id_aparato.",".$datos['id_admin'].",CURDATE(),'".$datos['accion']."');";
    $get = $db->query($consulta);
    if($get){
       
    }
    else{
       
    }
}
/////////// modificar aparato ///////////
function modifDetalleAparato($datos,$id_cat){
    include "Conexion.php";
    $consulta = "update info_aparato set id_categoria=".$id_cat.", estado='".$datos['estado']."',descripcion='".$datos['descripcion']."' where id=".$datos['id'].";";
    $get = $db->query($consulta);
    if($get){
        $var="0";
        $var =verifSameDate_Admin($datos); 
        if($var=="0"){  // verifica si ya se habia modificado este aparato por el mismo administrador
            updateAdminAparato($datos,$datos['id']);
        }
        echo json_encode("exito");
    }
    else{
        echo json_encode(mysqli_error($db));
    }
}
// funcion previa a modificar aparato
function modifAparato($datos){
    $id_cat=$datos['categoria'];
    if($datos['categoria']=='0'){ // si es una  nueva categoria
        $id_cat=insertarCat($datos['otro']);
    }
    modifDetalleAparato($datos,$id_cat);
}
/////////////// fin modificar ///////////


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
    $id_aparato="0";
    include "Conexion.php";
    $consulta = "insert into info_aparato VALUES(NULL,".$id_cat.",'".$datos['estado']."','".$datos['descripcion']."');";
    $get = $db->query($consulta);
    if($get){
        
        $id_aparato = mysqli_insert_id($db); //obtiene el ultimo registro insertado
        updateAdminAparato($datos,$id_aparato);
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
            return "2"; // si no tiene cuenta
        }
    }
    else{
        echo json_encode(mysqli_error($db));
        //return "2";
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

/////////////// NUEVA FUNCION   ///////////////////
function getAllCustomers(){
    include "Conexion.php";
    $consulta = "Select i.id_cliente,i.nombre,i.apellido_p,i.apellido_m,i.foto,i.calle,i.numero_calle,i.telefono,i.fecha_nacimiento,
    i.fecha_ingreso,i.id_cp,i.id_colonia,i.id_access,i.activo,i.numero_interior,i.genero,CONCAT(i.apellido_p,' ',i.apellido_m,' ',i.nombre) as Nombre,
    i3.codigo,i4.nombre as colonia, i5.id, i5.user, i5.password from cliente i inner join cp i3 on i.id_cp=i3.id inner join colonia i4 on i.id_colonia=i4.id inner join access i5 on i.id_access=i5.id;";
    $get = $db->query($consulta);
    if($get){
        $empleados=array();
        $resultado=array();
        
        while($res = mysqli_fetch_assoc($get)):
            $resultado[]=$res;
        endwhile;
        $empleados['clientes']=$resultado;
        echo json_encode($empleados);
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

        $accesso = setAcceso($datos,"1");  //inserta los nuevos datos
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
function setAcceso($datos,$tipo)
{
    include "Conexion.php";
    $f = "insert into access values(NULL,'".$datos['user']."','".$datos['password']."','".$tipo."');";
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
function getDataAdmin($datos){
    include "Conexion.php";
    $f = "select *, concat (nombre, ' ', apellido_p, ' ', apellido_m) as name from empleado inner join puesto on empleado.id_puesto = puesto.id where id_acceso = '".$datos['id_cuenta']."';";
    $d = $db->query($f);
    $var;
   
    if($d){
        if($res =mysqli_fetch_assoc($d)){
            $var = $res;
            $var['tipo']=$datos['tipo'];
            $var['user']=$datos['user'];
            $var['password']=$datos['password'];
            echo json_encode($var);
        }
        else{
            echo json_encode("-1");
        }

    }else{

    }

}
function getDataClient($datos){
    include "Conexion.php";
   
    $consulta = "select id_cliente, colonia.nombre, codigo, calle, numero_calle, numero_interior, genero,
     telefono, fecha_ingreso, fecha_nacimiento, numero_interior, activo, ultimo_pago,
      concat (cliente.nombre, ' ', apellido_p, ' ', apellido_m) as name from cliente inner join colonia on cliente.id_colonia = colonia.id
       inner join cp on cliente.id_cp = cp.id where id_access='".$datos['id_cuenta']."';";
    $get = $db->query($consulta);
    if($get){
        if($res =mysqli_fetch_assoc($get)){
            $var = $res;
            $var['tipo']=$datos['tipo'];
            $var['user']=$datos['user'];
            $var['password']=$datos['password'];
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
function login($datos){
    include "Conexion.php";
    $f = "select id as id_cuenta,user,password,tipo from access where user='".$datos['user']."' and password='".$datos['password']."';";
    $d = $db->query($f);
    $var;
   
    if($d){
        if($res = mysqli_fetch_assoc($d)){
            $var = $res;
            if($var['tipo']=="2"){
                getDataAdmin($var);
            }
            if($var['tipo']=='1'){
                getDataClient($var);
            }
           // echo json_encode($var);
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
    
    else if ($validation == "0"){
        insertEditPaquete($datos);
    }
    
    else{
        echo json_encode("Nombre Invalido");
    }
}
//MODIFICACIONES
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


//ASISTENCIA

function asistencia($datos){   
    $validation = getValidUserAsist($datos);
 
    if($validation == "0"){
        echo json_encode("Cliente Invalido");
    }
    else if($validation['activo'] == 0){
        echo json_encode("Cliente Inactivo");
    }
    else if($validation['ultimo_pago'] == ""){
        echo json_encode("Cliente Pago");
    }
    else{
        validationDate($datos);
    }
}

function getValidUserAsist($datos){
    include "Conexion.php";
    $f = "select activo, ultimo_pago, CONCAT(apellido_p,' ',apellido_m,' ',nombre) as name from cliente where id_cliente='".$datos['id_usuario']."';";
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

function validationDate($datos){   
    $validation = gDate();
    
    if($validation == "0"){
        sDate($datos);
    }
    else{
        insertAsistencia($datos);
    }
}

function gDate(){
    include "Conexion.php";
    $f = "select id from FECHA where fecha = CURDATE();";
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

function sDate($datos){
    include "Conexion.php";
   $f = "insert into fecha (fecha) values(CURDATE());";
    $d = $db->query($f);
    if($d){
        insertAsistencia($datos);
    }
    else{
        echo json_encode("Error");
    }
    mysqli_close($db);  
}

function insertAsistencia($datos){
    $validation = gDate();
    include "Conexion.php";
    
   $f = "insert into asistencia (id_fecha, id_cliente) values ('".$validation['id']."' , '".$datos['id_usuario']."');";
    $d = $db->query($f);
    if($d){
       resPay($datos);
    }
    else{
        echo json_encode("Error");
    }
    mysqli_close($db); 
}

function resPay($datos){
    
    include "Conexion.php";
    $validation = getValidUserAsist($datos);
    $validation = $validation['ultimo_pago'];
    $var = array();
    
    $f = "select * from CLIENTE_PAQUETE where id_pago = '".$validation."';";
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

function getAsistToday(){
    include "Conexion.php";

    $date = gDate();
        
    $consulta = "Select cliente.activo, cliente_paquete.fecha_vencimiento, concat(nombre, ' ', apellido_p, ' ', apellido_m) as name from asistencia inner join cliente on asistencia.id_cliente = cliente.id_cliente inner join cliente_paquete on cliente.ultimo_pago = cliente_paquete.id_pago where asistencia.id_fecha = '".$date['id']."';";
    $get = $db->query($consulta);
    if($get){
        $asistencia=array();
        $asistencia2=array();
        
        while($res = mysqli_fetch_assoc($get)):
            $asistencia2[]=$res;
        endwhile;
        $asistencia['asistencia']=$asistencia2;
        echo json_encode($asistencia);
    }
    else{
        echo json_encode(mysqli_error($db));
    }
}

//FIN ASISTENCIA

///////////////////////////// MODULO CLIENTE 
function getCostumerPays($datos){
    include "Conexion.php";
        
    $consulta = "Select * from cliente_paquete inner join paquete on cliente_paquete.id_paquete = paquete.id where id_cliente = '".$datos['id']."';";
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

function customerAsist7($datos){
    include "Conexion.php";

    $consulta = "select * from asistencia inner join fecha on asistencia.id_fecha = fecha.id where id_cliente = '".$datos['id']."' and fecha.fecha between date_sub(curdate(), interval 7 day) and curdate();";
    $get = $db->query($consulta);
    if($get){
        $asistencia=array();
        $asistencia2=array();
        
        while($res = mysqli_fetch_assoc($get)):
            $asistencia2[]=$res;
        endwhile;
        $asistencia['asistencia']=$asistencia2;
        echo json_encode($asistencia);
    }
    else{
        echo json_encode(mysqli_error($db));
    }
}

function customerAsist15($datos){
    include "Conexion.php";

    $consulta = "select * from asistencia inner join fecha on asistencia.id_fecha = fecha.id where id_cliente = '".$datos['id']."' and fecha.fecha between date_sub(curdate(), interval 15 day) and curdate();";
    $get = $db->query($consulta);
    if($get){
        $asistencia=array();
        $asistencia2=array();
        
        while($res = mysqli_fetch_assoc($get)):
            $asistencia2[]=$res;
        endwhile;
        $asistencia['asistencia']=$asistencia2;
        echo json_encode($asistencia);
    }
    else{
        echo json_encode(mysqli_error($db));
    }
}

function customerAsist30($datos){
    include "Conexion.php";

    $consulta = "select * from asistencia inner join fecha on asistencia.id_fecha = fecha.id where id_cliente = '".$datos['id']."' and fecha.fecha between date_sub(curdate(), interval 30 day) and curdate();";
    $get = $db->query($consulta);
    if($get){
        $asistencia=array();
        $asistencia2=array();
        
        while($res = mysqli_fetch_assoc($get)):
            $asistencia2[]=$res;
        endwhile;
        $asistencia['asistencia']=$asistencia2;
        echo json_encode($asistencia);
    }
    else{
        echo json_encode(mysqli_error($db));
    }
}
?>