<?php
// configuramos los datos para hablar con mi base de datos de mysql
$servidor = "127.0.0.1";
$usuario = "root";
$password = getenv('PASSWORD_DB');
$baseDatos = "sonido_interior";

$conexion = mysqli_connect($servidor, $usuario, $password, $baseDatos);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8");
?>