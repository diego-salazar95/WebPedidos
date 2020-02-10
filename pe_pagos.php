<?php
    require "conexion.php";
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title> Finalizar Compra </title>
</head>
<body>
<?php

    $carrito = [];
    $carrito = $_SESSION['carrito'];

    if (empty($carrito)) {
?>
    <p class="h1"> Finalizar Compra </p>
    <p class="h5"> No puedes finalizar la compra </p> <br>
    <p class="h5"> No has seleccionado ningun producto </p>
    <a href="pe_inicio.php"> <li class="list-group-item"> Volver al menu </li> </a>
<?php
    }else {
        if(!isset($_POST['boton_pago']) || empty($_POST['numero'])) {
?>
    <p class="h1"> Finalizar Compra </p>
    <p class="h5"> Realizar pago </p> <br>
    <form action="" method="post">
        <label for="numero"> N tarjeta: </label>
        <input type="text" size="16" name="numero" required> <br>
        <label for="fecha"> Fecha de pedido </label>
        <input type="date" name="fecha" required> <br>
        <input type="submit" class="btn btn-primary mb-2" value="Realizar Pago" name="boton_pago">
    </form>
<?php
    }else {
?>
    <p class="h1"> Finalizar Compra </p>
    <p class="h5"> Realizar pago </p> <br>
<?php
    //Declaracion de variables
        $vTarjeta = $_POST['numero'];
        $fecha_Pedido = $_POST['fecha'];

    //Comprobaciones
    //Llama a la funcion que compueba el formato de la tarjeta de credito; $vTarjeta contiene el numero introduccido en el formulario
        $correcto = comprobarTarjeta($vTarjeta);
    //Fecha actual del sistema
        $fecha_actual = gmdate('Y-m-d');
        
        if ($correcto == true) {
            ?> <p class="h4"> Datos Correctos </p> <?php
            ?> <p class="h4"> Pedido realizado correctamente </p> <?php
            //Insertar en la tabla Orders
            $maximo_orderNumber =  insertOrder($db, $fecha_Pedido, $fecha_actual);
            //Obtener Array de los importes de los productos
            $vImporte = ObtenerImporte($db);
            //Insertar en OrderDetails 
            insertarOderDetails($db, $maximo_orderNumber ,$vImporte);
            //Calcular importe total
            $importeTotal =  importeTotal($vImporte);
            //Insertar en la tabla payments
            insertarPayments($db, $vTarjeta, $fecha_actual, $importeTotal);
            //Update del Stock de los productos
            quitarStock($db);
            //Borrar carrito una vez realizada la compra
            echo "<p class='h4'> Importe total del pedido  $importeTotal </p>";

            borrarCarrito();
        }else {
            ?> <p class="h4"> Datos Incorrectos no se puede realizar el pedido </p> <?php
        }

        ?> <a href="pe_inicio.php"> <li class="list-group-item"> Volver al menu </li> </a> <?php
    }
}

//Funciones

//Funcion que comprueba el numero de tarjeta
function comprobarTarjeta($vTarjeta) { 
    $longitud = strlen($vTarjeta);
    $letras = substr($vTarjeta, 0, 2);
    $numeros = substr($vTarjeta, 2);
    $vCorrecto = false;

    if ($longitud != 8) {
        ?> <p class="h4"> Longitud incorrecta de Numero de la tarjeta </p> <?php
    }else if(!is_numeric($numeros)) {
        ?> <p class="h4"> Los digitos de apartir del segundo puesto solo pueden ser numeros </p> <?php
    }else if(!ctype_alpha($letras)){
        ?> <p class="h4"> Los 2 primeros digitos tienen que ser letas </p> <?php
    }else {
        $vCorrecto = true;
    }

    return $vCorrecto;
}

//Funcion que se encarga de introduccir la orden de compra nueva
//$db = base de datos
//$fecha_pedido $fecha_actual = fechas obtenidas con relacion al pedido
 function insertOrder($db, $fecha_Pedido, $fecha_actual) {
    $sql = mysqli_query($db, "SELECT MAX(orderNumber) as a FROM orders");
    $row = mysqli_fetch_assoc($sql);
    $maximo_orderNumber = $row['a'];
    $maximo_orderNumber++;

    $username = $_SESSION['username'];

    $sql1 = mysqli_query($db, "SELECT ID FROM ADMIN WHERE USERNAME='$username'");
    $row1 = mysqli_fetch_assoc($sql1);
    $id_user = $row1['ID'];

    $insertar_OrderNumber = "INSERT INTO ORDERS(ORDERNUMBER, ORDERDATE, REQUIREDDATE,
        SHIPPEDDATE, STATUS, COMMENTS, CUSTOMERNUMBER) VALUES
        ('$maximo_orderNumber', '$fecha_Pedido', '$fecha_actual', NULL, 'In Process', NULL, $id_user)";
    $db -> query($insertar_OrderNumber);

    return $maximo_orderNumber;
}

//Funcion que vacia el carrito de la compra
function borrarCarrito(){
    $carrito = $_SESSION['carrito'];
            
    $contador = count($carrito);

    for ($i=0; $i < $contador; $i++) { 
        unset($carrito[$i]);
    }

    $_SESSION['carrito'] = $carrito;
 }

 function insertarPayments($db, $vTarjeta, $fecha_actual, $importeTotal){
    $username = $_SESSION['username'];

    $sql1 = mysqli_query($db, "SELECT ID FROM ADMIN WHERE USERNAME='$username'");
    $row1 = mysqli_fetch_assoc($sql1);
    $id_user = $row1['ID'];

    $insert = "INSERT INTO PAYMENTS(CUSTOMERNUMBER, CHECKNUMBER, PAYMENTDATE, AMOUNT)
        values ('$id_user', '$vTarjeta', '$fecha_actual', '$importeTotal')";
    $db -> query($insert); 
}

function ObtenerImporte($db) {
    $carrito = $_SESSION['carrito'];

    foreach ($carrito as $key) {
        $product = $key['producto'];
        $sql = mysqli_query($db, "SELECT BUYPRICE FROM PRODUCTS WHERE PRODUCTNAME ='$product'");
        $row = mysqli_fetch_assoc($sql);
        $precio[] = $row['BUYPRICE']; 
    }

    return $precio;
}

function insertarOderDetails($db, $maximo_orderNumber ,$vImporte) {
    $carrito = $_SESSION['carrito'];
    $contadorLine = 1;
    $contadorImporte = 0;

    foreach ($carrito as $key) {
        $product = $key['producto'];
        $unidades = $key['unidades'];
        $importeSolo = $vImporte[$contadorImporte];

        $sql = mysqli_query($db, "SELECT PRODUCTCODE FROM PRODUCTS WHERE PRODUCTNAME ='$product'");
        $row = mysqli_fetch_assoc($sql);
        $product_code = $row['PRODUCTCODE'];

        $insertarOderDetailsSql = "INSERT INTO ORDERDETAILS(ORDERNUMBER, PRODUCTCODE,
        QUANTITYORDERED, PRICEEACH, ORDERLINENUMBER) VALUES ('$maximo_orderNumber', '$product_code',
        '$unidades', $importeSolo, '$contadorLine')";
        
        $db -> query($insertarOderDetailsSql);

        $contadorLine++;
        $contadorImporte++;
    }
}

function importeTotal($vImporte) {
    $carrito = $_SESSION['carrito'];
    $total = 0;

    for ($i=0; $i < count($vImporte); $i++) {
        $unidades = $carrito[$i]['unidades']; 
        
        $sum = $vImporte[$i] * $unidades;

        $total = $total+$sum;
    }

    return $total;
}

function quitarStock($db) {
    $carrito = $_SESSION['carrito'];
    foreach ($carrito as $key) {
        $nombre_Producto = $key['producto'];
        $cantidad_Producto = $key['unidades'];
        $update = mysqli_query($db, "UPDATE PRODUCTS SET QUANTITYINSTOCK = QUANTITYINSTOCK-$cantidad_Producto WHERE PRODUCTNAME = '$nombre_Producto'");
        $db -> query($update);
    }
}

?>

</body>
</html>