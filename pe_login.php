<?php
    //iniciar sesion
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title> Web Pedidos </title>
</head>
<body>

<!-- Formulario para inicio de sesion -->

<?php
require "conexion.php";  

if(!isset($_POST) || empty($_POST)) {
?>
<p class="h1"> Login </p>
<form action="" method="post">
<div class="form-group">
<label for="username"> Username: </label>
<input type="text" name="username" class="form-control"> <br>
<label for="passcode"> Password: </label>
<input type="password" name="passcode" class="form-control"> <br>
<input type="submit" value="Entrar" class="btn btn-primary">
</div>
</form>

<?php
}else {
    //Recoger variables y ponerlas en mayusculas
    $vUsername = $_POST['username'];
    $vUsername = strtoupper($vUsername);
    $vPassword = $_POST['passcode'];

    //Todos los usuarios $arrayUsuarios
    $arrayUsuarios = obtenerUsuario($db);

    //Guardamos variables de sesion
    $_SESSION['username'] = $vUsername;

    if (!in_array($vUsername, $arrayUsuarios)) {
        die("El usuario no se encuentra registrado");
    }else {
            $contraseña = obtenerContraseña($db, $vUsername);
        if ($vPassword === $contraseña) {
            $carrito = [];
            $_SESSION['carrito'] = $carrito;
            header("location: pe_inicio.php");
        }else {
            die("Contraseña incorrecta");
        }
    }

}
?>

<?php
//Funciones

//Obtenemos los usuarios para comprobar las credenciales
function obtenerUsuario($db) {
    $arrayUsuarios = array();
    
    $sql = mysqli_query($db, "SELECT USERNAME FROM ADMIN");
    if ($sql) {
        while($row = mysqli_fetch_assoc($sql)) {
            $mayusculas = $row['USERNAME'];
            $mayusculas = strtoupper($mayusculas);
            $arrayUsuarios[] = $mayusculas;
        }
    }
    return $arrayUsuarios;
}

function obtenerContraseña($db, $vUsername) {
    $sql = mysqli_query($db, "SELECT PASSCODE FROM ADMIN WHERE USERNAME = '$vUsername'");
    if ($sql) {
        while($row = mysqli_fetch_assoc($sql)) {
            $contraseña = $row['PASSCODE'];
        }
    }
    return $contraseña;
}


?>

</body>
</html>