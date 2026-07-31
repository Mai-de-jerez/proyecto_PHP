<?php
require_once __DIR__ . '/../../includes/seguridad.php';
require_once __DIR__ . '/../../includes/conexion.php';
require_once __DIR__ . '/../../models/carrito.php';
require_once __DIR__ . '/../../models/checkout.php';

// Solo se permite acceso por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../views/public/carrito.php");
    exit();
}

// Sanitizar y validar la dirección de envío
$direccionEnvio = trim($_POST['direccion_envio'] ?? '');

if (empty($direccionEnvio)) {
    $_SESSION['mensaje_error'] = "Por favor, introduce una dirección de envío válida.";
    header("Location: ../../views/public/checkout.php");
    exit();
}

$idUsuario = $_SESSION['id_usuario'];
$idCarrito = obtenerOCrearCarrito($conexion, $idUsuario);
$lineasCarrito = obtenerProductosCarrito($conexion, $idCarrito);

// validar que el carrito no esté vacío
if (empty($lineasCarrito)) {
    $_SESSION['mensaje_error'] = "Tu carrito está vacío.";
    header("Location: ../../views/public/carrito.php");
    exit();
}

// INICIAR TRANSACCIÓN ATÓMICA
mysqli_begin_transaction($conexion);

try {
    $totalPedido = 0;

    // primera pasada: Comprobar stock en tiempo real con bloqueo FOR UPDATE
    foreach ($lineasCarrito as $linea) {
        $productoBD = obtenerStockProductoParaUpdate($conexion, $linea['id_producto']);

        if (!$productoBD || $linea['cantidad'] > $productoBD['stock']) {
            $nombreProd = $productoBD['nombre'] ?? $linea['nombre'];
            $stockDisponible = $productoBD['stock'] ?? 0;
            
            throw new Exception("El stock del producto '{$nombreProd}' ha cambiado. Disponibles: {$stockDisponible}.");
        }

        $totalPedido += $linea['cantidad'] * $linea['precio_unitario'];
    }

    // crear la cabecera del pedido en la tabla `pedidos`
    $idPedido = crearPedido($conexion, $idUsuario, $totalPedido, $direccionEnvio);

    if (!$idPedido) {
        throw new Exception("No se pudo registrar la cabecera del pedido.");
    }

    // insertar detalles y descontar stock de cada producto
    foreach ($lineasCarrito as $linea) {
        $detalleOk = crearDetallePedido(
            $conexion, 
            $idPedido, 
            $linea['id_producto'], 
            $linea['cantidad'], 
            $linea['precio_unitario']
        );

        $stockOk = descontarStockProducto($conexion, $linea['id_producto'], $linea['cantidad']);

        if (!$detalleOk || !$stockOk) {
            throw new Exception("Error al procesar el desglose del pedido.");
        }
    }

    // vaciar el carrito tras confirmarse todo el pedido
    vaciarCarrito($conexion, $idCarrito);

    // resetear la cantidad de sesión para el icono del menú con su clave correcta
    if (isset($_SESSION['cantidades_carrito'])) {
        $_SESSION['cantidades_carrito'] = 0;
    }

    // CONFIRMAR TRANSACCIÓN
    mysqli_commit($conexion);
    mysqli_close($conexion);

    // guardar id_pedido en sesión para la pantalla de éxito y redirigir
    $_SESSION['ultimo_pedido_id'] = $idPedido;
    $_SESSION['mensaje_exito'] = "¡Pedido #{$idPedido} realizado con éxito!";
    header("Location: ../../views/public/pedido-exito.php");
    exit();

} catch (Exception $e) {
    // si algo falla: Deshacer todos los cambios en la BD
    mysqli_rollback($conexion);
    mysqli_close($conexion);

    $_SESSION['mensaje_error'] = $e->getMessage();
    header("Location: ../../views/public/carrito.php");
    exit();
}