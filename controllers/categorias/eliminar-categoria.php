<?php
$rolNecesario = 'ADMIN';
require_once __DIR__ . '/../../includes/seguridad.php';

require_once __DIR__ . '/../../models/categorias.php';
require_once __DIR__ . '/../../includes/conexion.php';

$idCategoria = (isset($_POST['id_categoria']) && ctype_digit($_POST['id_categoria'])) ? (int) $_POST['id_categoria'] : null;

if ($idCategoria === null) {
    mysqli_close($conexion);
    header("Location: ../../views/admin/categorias/admin-listado-categorias.php");
    exit();
}

$eliminado = eliminarCategoriaLogica($conexion, $idCategoria);
mysqli_close($conexion);

if ($eliminado) {
    $_SESSION['mensaje_exito'] = "Categoría eliminada correctamente.";
    header("Location: ../../views/admin/categorias/admin-listado-categorias.php?status=deleted");
    exit();
} else {
    $_SESSION['mensaje_error'] = "No se pudo eliminar la categoría. Por favor, inténtalo de nuevo.";
    header("Location: ../../views/admin/categorias/admin-listado-categorias.php?status=error");
    exit();
}
?>