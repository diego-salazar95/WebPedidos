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
    <title> Finalizar compra </title>
</head>
<body>
<?php
    $carrito = [];
    $carrito = $_SESSION['carrito'];
?>
    <p class ="h2"> Productos Añadidos Al carrito</p>
<?php
    if (empty($carrito)) {
    ?>    <p class="h3"> Todavia no has seleccionado ningun producto </p> <?php
    }else {
    foreach ($carrito as $key) {
        echo "Producto ".$key['producto']."<br>";
        echo " Unidades ".$key['unidades']."<br>";
        echo "<br>";
        }
        //Formulario que controla si se pulsa el boton de Vaciar el carrito
        if (!isset($_POST['Vaciar'])) {
            ?> 
            <form action="" method="post">        
              <input type="submit" class="btn btn-primary mb-2" value="Vaciar" name="Vaciar"><br>
            </form>
            <br>
            <?php
        }else {

            //Borra el carrito
            $carrito = $_SESSION['carrito'];
            
            $contador = count($carrito);

            for ($i=0; $i < $contador; $i++) { 
                unset($carrito[$i]);
            }

            $_SESSION['carrito'] = $carrito;
        }
    }

    //Mas opciones para navegar
?>
    <a href="pe_pagos.php"> <li class="list-group-item"> Finalizar Compra </li> </a>
    <a href="pe_altaped.php"> <li class="list-group-item"> Seguir Comprando </li> </a>
    <a href="pe_inicio.php"> <li class="list-group-item"> Volver al menu  </li> </a>

</body>
</html>