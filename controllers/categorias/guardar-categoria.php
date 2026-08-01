<?php
require_once __DIR__ . '/../../includes/seguridad.php';
require_once __DIR__ . '/../../models/categorias.php';
require_once __DIR__ . '/../../includes/conexion.php';

if (isset($_POST) && !empty($_POST["nombre"])) {

    $nombre = $_POST["nombre"];
    $descripcion = !empty($_POST["descripcion"]) ? $_POST["descripcion"] : null;
    $id_categoria = isset($_POST["id_categoria"]) && ctype_digit($_POST["id_categoria"]) ? (int) $_POST["id_categoria"] : null;

    if ($id_categoria) {
        // Es una actualización
        $resultado = actualizarCategoria($conexion, $id_categoria, $nombre, $descripcion);
        $mensajeExito = "Categoría actualizada con éxito.";
        $mensajeError = "Error al actualizar la categoría.";
    } else {
        // Es un alta nueva
        $resultado = crearCategoria($conexion, $nombre, $descripcion);
        $mensajeExito = "Categoría guardada con éxito.";
        $mensajeError = "Error al guardar la categoría.";
    }

    mysqli_close($conexion);

    if ($resultado) {
        $_SESSION['mensaje_exito'] = $mensajeExito;
        header("Location: ../../views/admin/categorias/admin-listado-categorias.php");
        exit();
    } else {
        $_SESSION['mensaje_error'] = $mensajeError;
        $urlRedireccion = $id_categoria ? "../../views/admin/categorias/admin-alta-categoria.php?id=" . $id_categoria : "../../views/admin/categorias/admin-alta-categoria.php";
        header("Location: " . $urlRedireccion);
        exit();
    }

} else {
    header("Location: ../../views/admin/categorias/admin-alta-categoria.php");
    exit();
}
?>