<?php

$servidor = "127.0.0.1";
$usuario = "root";
$password = "Nemrac1985";
$baseDatos = "sonido_interior";

$conexion = mysqli_connect($servidor, $usuario, $password, $baseDatos);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8");

?>