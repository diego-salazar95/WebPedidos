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
    <title> Consultar Linea </title>
</head>
<body>
    <p class="h1"> Consultar Linea de Productos </p> <br>
<?php
    $lines = obtenerLineas($db);
    if (!isset($_POST) || empty($_POST)) {
?>
    <form action="" method="post">
        <label for="linea"> <p class="h4"> Linea a consultar: </p></label>
        <select name="linea">
            <?php  foreach ($lines as $lin) { ?>
                <option> <?php echo $lin ?> </option>
            <?php } ?>
        </select>
        <input type="submit" value="Consultar" class="btn btn-primary">
    </form>
<?php
}else {
    $vLinea = $_POST['linea'];

    obtenerProductos($db, $vLinea);

}
?>
    <br>
    <a href="pe_inicio.php"> <li class="list-group-item"> Volver al menu  </li> </a>
</body>
</html>

<?php
//FUNCIONES
function obtenerLineas($db) {
    $lines = array();

    $sql = mysqli_query($db, "SELECT PRODUCTLINE FROM PRODUCTS group by(PRODUCTLINE)");
    if ($sql) {
        while ($row = mysqli_fetch_assoc($sql)) {
            $lines[] = $row['PRODUCTLINE'];
        }
    }

    return $lines;
}

function obtenerProductos($db, $vLinea) {
    $sql = mysqli_query($db, "SELECT PRODUCTNAME, QUANTITYINSTOCK FROM PRODUCTS order by(QUANTITYINSTOCK) DESC");
    if ($sql) {
        echo "<p class='h2'> Productos $vLinea </p> <br>";
        echo "<p class='h2'> -------------------------------------------------- </p> <br>";
        while ($row = mysqli_fetch_assoc($sql)) {
            echo "<p class='h4'> Nombre del producto: ".$row['PRODUCTNAME']."</p> <br>";
            echo "<p class='h4'> Stock del producto: ".$row['QUANTITYINSTOCK']."</p> <br>";
            echo "<p class='h2'> -------------------------------------------------- </p> <br>";
        }
    }
}
?>