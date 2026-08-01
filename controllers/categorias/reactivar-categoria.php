<?php
$rolNecesario = 'ADMIN';
require_once __DIR__ . '/../../includes/seguridad.php';
require_once __DIR__ . '/../../models/categorias.php';
require_once __DIR__ . '/../../includes/conexion.php';

// con ctype_digit verificamos que sea un numero entero positivo, si no lo es, devolvemos null
$idCategoria = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? (int) $_GET['id'] : null;

if ($idCategoria === null) {
    mysqli_close($conexion);
    header("Location: ../../views/admin/categorias/admin-listado-categorias.php");
    exit();
}

$reactivado = reactivarCategoria($conexion, $idCategoria);
mysqli_close($conexion);

if ($reactivado) {
    $_SESSION['mensaje_exito'] = "Categoría reactivada correctamente.";
    header("Location: ../../views/admin/categorias/admin-listado-categorias.php?status=reactivated");
    exit();
} else {
    $_SESSION['mensaje_error'] = "No se pudo reactivar la categoría. Por favor, inténtalo de nuevo.";
    header("Location: ../../views/admin/categorias/admin-listado-categorias.php?status=error");
    exit();
}
?>