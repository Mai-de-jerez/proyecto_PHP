<?php
require_once __DIR__ . '/../../includes/seguridad.php';
require_once __DIR__ . '/../../models/carrito.php';
require_once __DIR__ . '/../../includes/conexion.php';

$idCarritoProducto = (isset($_POST['id_carrito_producto']) && ctype_digit($_POST['id_carrito_producto'])) ? (int) $_POST['id_carrito_producto'] : null;
$accion = $_POST['accion'] ?? null;

// 1. Validar que tengamos un ID válido y la acción sea 'sumar' o 'restar'
if ($idCarritoProducto === null || !in_array($accion, ['sumar', 'restar'], true)) {
    mysqli_close($conexion);
    header("Location: ../../views/public/carrito.php");
    exit();
}

// 2. Seguridad: Comprobar pertenencia al usuario
if (!lineaPerteneceAUsuario($conexion, $idCarritoProducto, $_SESSION['id_usuario'])) {
    mysqli_close($conexion);
    header("Location: ../../views/public/carrito.php?status=error");
    exit();
}

// 3. Buscar la cantidad actual usando tus funciones de modelo existentes
$idCarrito = obtenerOCrearCarrito($conexion, $_SESSION['id_usuario']);
$lineas = obtenerProductosCarrito($conexion, $idCarrito);

$cantidadActual = null;
foreach ($lineas as $linea) {
    if ((int)$linea['id_carrito_producto'] === $idCarritoProducto) {
        $cantidadActual = (int)$linea['cantidad'];
        break;
    }
}

// 4. Calcular el nuevo valor en PHP, actualizar BD y la SESIÓN
if ($cantidadActual !== null) {
    if ($accion === 'sumar') {
        $nuevaCantidad = $cantidadActual + 1;
        actualizarCantidadCarrito($conexion, $idCarritoProducto, $nuevaCantidad);
        
        // Sumamos 1 a la  sesión
        $_SESSION['cantidades_carrito'] = ($_SESSION['cantidades_carrito'] ?? 0) + 1;

    } elseif ($accion === 'restar' && $cantidadActual > 1) {
        $nuevaCantidad = $cantidadActual - 1;
        actualizarCantidadCarrito($conexion, $idCarritoProducto, $nuevaCantidad);
        
        // Restamos 1 a la sesión
        $_SESSION['cantidades_carrito'] = max(0, ($_SESSION['cantidades_carrito'] ?? 0) - 1);
    }
}

mysqli_close($conexion);
header("Location: ../../views/public/carrito.php");
exit();
?>