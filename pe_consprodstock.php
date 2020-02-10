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
    <title> Consultar Stock de un producto </title>
</head>
<body>
    <p class="h1"> Consultar Stock </p> <br>
<?php
    $productos = obtenerProductos($db);
    if (!isset($_POST) || empty($_POST)) {   
?>
    <form action="" method="post">
        <label for="producto"> <p class="h4"> Selecciona el Producto : </p> </label>
        <select name="producto">
            <?php foreach ($productos as $key) { ?>
                <option> <?php echo $key ?>  </option>
            <?php } ?>
        </select>
        <input type="submit" value="Consultar" class="btn btn-primary mb-2">
    </form>
    
<?php
}else {
    $vProducto = $_POST['producto'];
    obtenerStock($db, $vProducto);
}

?>
    <br>
    <a href="pe_inicio.php"> <li class="list-group-item"> Volver al menu  </li> </a>

</body>
</html>

<?php

//FUNCIONES

function obtenerProductos($db) {
    $productos = array();
    
    $sql = mysqli_query($db, "SELECT PRODUCTNAME FROM PRODUCTS");
    if ($sql) {
        while ($row = mysqli_fetch_assoc($sql)) {
            $productos[] = $row['PRODUCTNAME'];
        }
    }
    return $productos;
}

function obtenerStock($db, $vProducto) {
    $sql = mysqli_query($db, "SELECT QUANTITYINSTOCK FROM PRODUCTS WHERE PRODUCTNAME = '$vProducto'");
    $row = mysqli_fetch_assoc($sql);

    echo "<p class='h4'> El Stock de $vProducto es ".$row['QUANTITYINSTOCK']."</p>";
}

?>