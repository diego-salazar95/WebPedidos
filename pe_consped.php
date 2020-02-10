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
    <title> Consultar Cliente </title>
</head>
<body>
<?php
    $clientesId = obtenerClientes($db);

    if (!isset($_POST) || empty($_POST)) {
?>
    <p class="h2"> Consulta de clientes </p>
    <form action="" method="post">
        <br>
        <label for="cliente"> <p class="h5"> Cliente : </p> </label>
        <select name="cliente">
        <?php  foreach ($clientesId as $cliente) {
        ?>   <option> <?php echo $cliente ?> </option>
        <?php } ?>
        </select> <br>
        <br>
        <input type="submit" class="btn btn-primary mb-2" value="Enviar">
    </form>
        <br>
    <a href="pe_inicio.php"> <li class="list-group-item"> Volver al menu  </li> </a>
<?php
}else {

    ?> <p class="h2"> Consulta de clientes </p> <br><?php

    $cliente = $_POST['cliente'];
    obtenerInfoClientes($db, $cliente);

?>  <br>    
    <a href="pe_consped.php"> <li class="list-group-item"> Consultar Mas Clientes </li> </a>
<?php
}
?>
</body>
</html>

<?php
//Funciones

function obtenerClientes($db) {
    $clientesId = array();

    $sql = mysqli_query($db, "SELECT ID FROM ADMIN");
    if ($sql) {
        while ($row = mysqli_fetch_assoc($sql)) {
            $clientesId[] = $row['ID'];
        }
    }
    return $clientesId;
}

function obtenerInfoClientes($db, $cliente) {
    $contador = 1;

    $sql = mysqli_query($db, "SELECT ORDERNUMBER, ORDERDATE, STATUS FROM ORDERS WHERE CUSTOMERNUMBER='$cliente'");
    if ($sql) {
        while ($row = mysqli_fetch_assoc($sql)) {
            echo "<p class='h5'> Pedido $contador del Cliente $cliente</p><br>";
            echo "<p class='h5'>Numero de Orden: ".$row['ORDERNUMBER']."</p><br>";
            echo "<p class='h5'>Fecha de Orden: ".$row['ORDERDATE']."</p><br>";
            echo "<p class='h5'>Estado: ".$row['STATUS']."</p><br>";
            echo "<p class='h5'>--------------Productos del pedido--------------</p><br>";
            $contador++;

            $codigoProducto = $row['ORDERNUMBER'];
            $sql2 = mysqli_query($db, "SELECT PRODUCTCODE, QUANTITYORDERED, PRICEEACH, ORDERLINENUMBER FROM ORDERDETAILS WHERE ORDERNUMBER='$codigoProducto'");
            if ($sql2) {
                while ($row2 = mysqli_fetch_assoc($sql2)) {
                    $nombreProducto = $row2['PRODUCTCODE'];
                    $sql1 = mysqli_query($db, "SELECT PRODUCTNAME FROM PRODUCTS WHERE PRODUCTCODE ='$nombreProducto'");
                    $row1 = mysqli_fetch_assoc($sql1);
                    echo "<p class='h5'> Nombre del producto: ".$row1['PRODUCTNAME']. "</p><br>";
                    echo "<p class='h5'> Cantidad Pedida: ".$row2['QUANTITYORDERED']. "</p><br>";
                    echo "<p class='h5'> Precio por unidad: ".$row2['PRICEEACH']. "</p><br>";
                    echo "<p class='h5'> Numero de linea: ".$row2['ORDERLINENUMBER']. "</p><br>";
                }
            }

            echo "<p class='h5'> -------------------------------FIN DEL PEDIDO-------------------------------</p><br>";
        }
    }
}
?>