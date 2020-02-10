<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title> Realizar pedido </title>
</head>
<body>
    <p class="h1"> Bienvenido <?php echo  $_SESSION['username']; ?> </p>
    <p class="h3"> Menu </p>
        <ul class="list-group">
            <a href="pe_altaped.php"><li class="list-group-item"> Ver productos </li></a>
            <a href="pe_carrito.php"><li class="list-group-item"> Ver Carrito de la Compra </li></a>
            <a href="pe_consped.php"><li class="list-group-item"> Consultar Cliente </li></a>
            <a href="pe_consprodstock.php"><li class="list-group-item"> Consultar Stock </li></a>
            <a href="pe_constock.php"><li class="list-group-item"> Consultar Lineas de Productos </li></a>
            <a href="pe_topprod.php"><li class="list-group-item"> Consultar Ventas entre fechas </li></a>
            <a href="pe_conspago.php"><li class="list-group-item"> Ver pagos realizados </li></a>
            <a href="pe_login.php"><li class="list-group-item"> Salir </li></a>
        </ul>   
</body>
</html>