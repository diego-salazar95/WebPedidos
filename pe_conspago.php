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
    <title> Pagos Realizados </title>
</head>
<body>
    <p class="h1"> Pagos Relizados </p>
    <br>
<?php
    $clientesId = obtenerClientes($db);
    if(!isset($_POST) || empty($_POST)) {    
?>
    <form action="" method="post">
        <br>
        <label for="cliente"> <p class="h5"> Cliente : </p> </label>
        <select name="cliente">
        <?php  foreach ($clientesId as $cliente) {
        ?>   <option> <?php echo $cliente ?> </option>
        <?php } ?>
        </select> <br>
        <br>
        <label for="fecha1"> Fecha de: </label>
        <input type="date" name="fecha1">
        <label for="fecha2"> a: </label>
        <input type="date" name="fecha2"> <br>
        <br>
        <input type="submit" class="btn btn-primary mb-2" value="Enviar">
    </form>
        <br>
<?php
}else{

    $cliente = $_POST['cliente'];
    $fecha1 = $_POST['fecha1'];
    $fecha2 = $_POST['fecha2'];
    
    if (empty($fecha1) || empty($fecha2)) {
        echo "<p class='h5'> Registro historico de pagos </p>";
        mostrarSinFechas($db, $cliente);
    }else {
        echo "<p class='h5'> Registro entre $fecha1 y $fecha2 </p>";
        mostrarFechas($db, $cliente,$fecha1, $fecha2);
    }

}    
?>
    <a href="pe_conspago.php"><li class="list-group-item"> Elegir de nuevo </li></a>
    <a href="pe_inicio.php"><li class="list-group-item"> Volver al menu </li></a> 
</body>
</html>

<?php

//Functions
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


function mostrarFechas($db, $cliente,$fecha1, $fecha2) {
    while ($fecha1 <= $fecha2) {
        echo "<p class=h5> Pagos en la Fecha: $fecha1 </p>";
        $sql = mysqli_query($db, "SELECT AMOUNT FROM PAYMENTS WHERE PAYMENTDATE='$fecha1' AND CUSTOMERNUMBER='$cliente'");
        if ($sql) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $cantidad = $row['AMOUNT'];
                echo "<p class=h5> -- Cantidad Pagada $cantidad </p>";
            }
        }
        $fecha1 = date("Y-m-d", strtotime($fecha1."+ 1days"));
    }
}

function mostrarSinFechas($db, $cliente) {
    $sql = mysqli_query($db, "SELECT sum(AMOUNT) as a FROM PAYMENTS WHERE CUSTOMERNUMBER='$cliente'");
    $row = mysqli_fetch_assoc($sql);
    echo "<p class=h5>La suma de todos los pagos de usuario $cliente es ".$row['a']."</p>";
}
?>