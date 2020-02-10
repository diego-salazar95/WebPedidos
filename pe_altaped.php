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
    <title> Realizar Compra </title>
</head>
<body>
<p class="h4"> Sesion de <?php echo  $_SESSION['username']; ?> </p>

<?php
if(!isset($_POST) || empty($_POST)) {
    $productos = obtenerProductos($db);
?>
<form action="pe_altaped.php" method="post">
    <div class="form-group">
        <label for="producto"> Producto: </label>
        <select name="producto">
        <?php foreach($productos as $producto) : ?>
			<option> <?php echo $producto ?> </option>
		<?php endforeach; ?>
	</select>
    <label for="unidades"> Unidades: </label>
    <input type="text" name="unidades" size="10" required>
    <input type="submit" class="btn btn-primary mb-2" value="Añadir al carrito"> <br>
    <br>
    <a href="pe_inicio.php"><input type="button" class="btn btn-primary mb-2" value="Volver al menu"></a>
    </div>
</form>
</body>
</html>
<?php
}else {

    $vProducto = $_POST['producto'];
    $vUnidades = $_POST['unidades'];

    $unidades_disponibles = obtenerUnidades($db, $vProducto);

    if ($unidades_disponibles < $vUnidades) {
        echo "No hay suficientes unidades de ese producto <br>";
        echo "Unidades disponibles de $vProducto son $vUnidades <br>";
    }else {
    $carrito = [];

    $objetos = array('producto' => $vProducto, 'unidades' => $vUnidades);
    $carrito = $_SESSION['carrito'];
    array_push($carrito, $objetos);
    $_SESSION['carrito'] = $carrito;
?>    
    <p class="h5"> Producto añadido al carrito </p>
<?php
}

?>   
    <a href="pe_altaped.php"> <li class="list-group-item"> Seguir Comprando </li> </a>
    <a href="pe_carrito.php"> <li class="list-group-item"> Ver carrito  </li> </a>
    <a href="pe_pagos.php"> <li class="list-group-item"> Finalizar Compra </li> </a>
<?php
    
}


//Funciones
function obtenerProductos($db) {
    $productos = array();

    $sql = mysqli_query($db, "SELECT PRODUCTNAME FROM PRODUCTS WHERE QUANTITYINSTOCK>0");
    if ($sql) {
        while($row = mysqli_fetch_assoc($sql)) {
            $productos[] = $row['PRODUCTNAME'];
        }
    }

    return $productos;
}

function obtenerUnidades($db, $vProducto) {
    $unidades = 0;

    $sql = mysqli_query($db, "SELECT QUANTITYINSTOCK FROM PRODUCTS WHERE PRODUCTNAME = '$vProducto'");
    if ($sql) {
        while($row = mysqli_fetch_assoc($sql)) {
            $unidades = $row['QUANTITYINSTOCK'];
        }
    }

    return $unidades;
}

?>