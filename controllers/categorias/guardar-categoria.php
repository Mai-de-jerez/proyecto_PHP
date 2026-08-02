<?php
require_once __DIR__ . '/../../includes/seguridad.php';
require_once __DIR__ . '/../../models/categorias.php';
require_once __DIR__ . '/../../includes/conexion.php';

if (isset($_POST) && !empty($_POST["nombre"])) {

    $nombre = trim($_POST["nombre"] ?? '');
    $descripcion = trim($_POST["descripcion"] ?? '');
    $id_categoria = isset($_POST["id_categoria"]) && ctype_digit($_POST["id_categoria"]) ? (int) $_POST["id_categoria"] : null;

    $urlVuelta = $id_categoria
        ? "../../views/admin/categorias/admin-alta-categoria.php?id=" . $id_categoria
        : "../../views/admin/categorias/admin-alta-categoria.php";

    $errores = [];

    if ($nombre === '') {
        $errores['nombre'] = "El nombre es obligatorio.";
    } elseif (mb_strlen($nombre) < 3 || mb_strlen($nombre) > 50) {
        $errores['nombre'] = "El nombre debe tener entre 3 y 50 caracteres.";
    }

    if ($descripcion === '') {
        $errores['descripcion'] = "La descripción es obligatoria.";
    } elseif (mb_strlen($descripcion) < 15 || mb_strlen($descripcion) > 300) {
        $errores['descripcion'] = "La descripción debe tener entre 15 y 300 caracteres.";
    }

    if (!empty($errores)) {
        mysqli_close($conexion);
        $_SESSION['errores'] = $errores;
        header("Location: " . $urlVuelta);
        exit();
    }

    if ($id_categoria) {
        $resultado = actualizarCategoria($conexion, $id_categoria, $nombre, $descripcion);
        $mensajeExito = "Categoría actualizada con éxito.";
        $mensajeError = "Error al actualizar la categoría.";
    } else {
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
        header("Location: " . $urlVuelta);
        exit();
    }

} else {
    header("Location: ../../views/admin/categorias/admin-alta-categoria.php");
    exit();
}