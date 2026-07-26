<?php
$rolNecesario = 'ADMIN';
require_once __DIR__ . '/../../includes/seguridad.php';

require_once __DIR__ . '/../../models/productos.php';
require_once __DIR__ . '/../../includes/conexion.php';

$idProducto = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? (int) $_GET['id'] : null;

if ($idProducto === null) {
    mysqli_close($conexion);
    header("Location: ../../views/admin/productos/admin-listado-productos.php");
    exit();
}

$reactivado = reactivarProducto($conexion, $idProducto);
mysqli_close($conexion);

if ($reactivado) {
    header("Location: ../../views/admin/productos/admin-listado-productos.php?status=reactivated");
    exit();
} else {
    header("Location: ../../views/admin/productos/admin-listado-productos.php?status=error");
    exit();
}
?>