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
    <title> Productos Vendidos </title>
</head>
<body> 
    <p class="h1"> Consultar entre fechas </p> <br>
<?php
if (!isset($_POST) || empty($_POST)) {        
?>
    <form action="" method="post">
        <label for="fecha1"> <p class='h4'> Elige las fechas a consultar de: </p></label>
        <input type="date" name="fecha1" required>
        <label for="fecha2"> a </label>
        <input type="date" name="fecha2" required>
        <input type="submit" value="Consultar" class="btn btn-primary">
    </form>
<?php
}else {

    $fecha1 = $_POST['fecha1'];
    $fecha2 = $_POST['fecha2'];

    if ($fecha1 > $fecha2) {
        echo "<p class='h2'> Error: Intervalo de fechas incorrecto </p> ";
    }else {
        obtenerStock($db, $fecha1, $fecha2);
    }
} 
?>
    <br>
    <a href="pe_topprod.php"><li class="list-group-item"> Elegir fechas denuevo </li></a>
    <a href="pe_inicio.php"><li class="list-group-item"> Volver al menu </li></a>
</body>
</html>

<?php
//Funciones
function obtenerStock($db, $fecha1, $fecha2) {
    while ($fecha1 <= $fecha2) {
        $sql = mysqli_query($db, "SELECT ORDERNUMBER FROM ORDERS WHERE ORDERDATE='$fecha1'");
        $row = mysqli_fetch_assoc($sql);
        $numeroOrden = $row['ORDERNUMBER'];
        echo "<p class=h5> Ventas en la Fecha: $fecha1 </p>";
        $sql2 = mysqli_query($db, "SELECT PRODUCTCODE, QUANTITYORDERED FROM ORDERDETAILS WHERE ORDERNUMBER='$numeroOrden'");
        if ($sql2) {
            while ($row2 = mysqli_fetch_assoc($sql2)) {
                echo "Codigo de producto: ".$row2['PRODUCTCODE'];
                echo " Cantidad Pedida : ".$row2['QUANTITYORDERED']."<br>";
            }
        }
        $fecha1 = date("Y-m-d", strtotime($fecha1."+ 1days"));
    }
}

?>