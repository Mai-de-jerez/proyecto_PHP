<?php
$rolNecesario = 'ADMIN';
require_once __DIR__ . '/../../includes/seguridad.php';

require_once __DIR__ . '/../../models/productos.php';
require_once __DIR__ . '/../../includes/conexion.php';

$idProducto = (isset($_POST['id_producto']) && ctype_digit($_POST['id_producto'])) ? (int) $_POST['id_producto'] : null;

if ($idProducto === null) {
    mysqli_close($conexion);
    header("Location: ../../views/admin/productos/admin-listado-productos.php");
    exit();
}

$eliminado = eliminarProductoLogico($conexion, $idProducto);
mysqli_close($conexion);

if ($eliminado) {
    header("Location: ../../views/admin/productos/admin-listado-productos.php?status=deleted");
    exit();
} else {
    header("Location: ../../views/admin/productos/admin-listado-productos.php?status=error");
    exit();
}
?>