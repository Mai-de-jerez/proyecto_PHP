<?php
$rolNecesario = 'ADMIN';
require_once __DIR__ . '/../../includes/seguridad.php';
require_once __DIR__ . '/../../models/productos.php';
require_once __DIR__ . '/../../includes/conexion.php';

// con ctype_digit verificamos que sea un numero entero positivo, si no lo es, devolvemos null
$idProducto = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? (int) $_GET['id'] : null;

if ($idProducto === null) {
    mysqli_close($conexion);
    header("Location: ../../views/admin/productos/admin-listado-productos.php");
    exit();
}

$reactivado = reactivarProducto($conexion, $idProducto);
mysqli_close($conexion);

if ($reactivado) {
    $_SESSION['mensaje_exito'] = "Producto reactivado correctamente.";
    header("Location: ../../views/admin/productos/admin-listado-productos.php?status=reactivated");
    exit();
} else {
    $_SESSION['mensaje_error'] = "No se pudo reactivar el producto. Por favor, inténtalo de nuevo.";
    header("Location: ../../views/admin/productos/admin-listado-productos.php?status=error");
    exit();
}
?>