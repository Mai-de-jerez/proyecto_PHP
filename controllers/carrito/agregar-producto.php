<?php
require_once __DIR__ . '/../../includes/seguridad.php';
require_once __DIR__ . '/../../models/carrito.php';
require_once __DIR__ . '/../../models/productos.php';
require_once __DIR__ . '/../../includes/conexion.php';

$idProducto = (isset($_POST['id_producto']) && ctype_digit($_POST['id_producto'])) ? (int) $_POST['id_producto'] : null;
$cantidad = (isset($_POST['cantidad']) && ctype_digit($_POST['cantidad'])) ? (int) $_POST['cantidad'] : 1;

if ($idProducto === null) {
    mysqli_close($conexion);
    header("Location: ../../views/public/productos/catalogo.php");
    exit();
}

// Traemos el producto para coger su precio actual y comprobar que existe y está activo
$producto = obtenerProductoPorId($conexion, $idProducto);

if (!$producto) {
    mysqli_close($conexion);
    header("Location: ../../views/public/productos/catalogo.php?status=error");
    exit();
}

$idCarrito = obtenerOCrearCarrito($conexion, $_SESSION['id_usuario']);
agregarProductoAlCarrito($conexion, $idCarrito, $idProducto, $cantidad, $producto['precio']);
mysqli_close($conexion);

// Va a la sesión inmediatamente
$_SESSION['cantidades_carrito'] = ($_SESSION['cantidades_carrito'] ?? 0) + $cantidad;

// Guardamos el aviso para la vista
$_SESSION['mensaje_exito'] = "¡Producto añadido al carrito correctamente!";

$origen = $_SERVER['HTTP_REFERER'] ?? '../../views/public/productos/catalogo.php';
header("Location: " . $origen);
exit();
?>