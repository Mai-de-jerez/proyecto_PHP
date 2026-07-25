<?php
require_once __DIR__ . '/../../models/categorias.php';
require_once __DIR__ . '/../../includes/conexion.php';

if (isset($_POST) && !empty($_POST["nombre"])) {

    $nombre = $_POST["nombre"];
    $descripcion = !empty($_POST["descripcion"]) ? $_POST["descripcion"] : null;

    $creado = crearCategoria($conexion, $nombre, $descripcion);
    mysqli_close($conexion);

    if ($creado) {
        header("Location: ../../views/admin/categorias/admin-listado-categorias.php?status=success");
        exit();
    } else {
        header("Location: ../../views/admin/categorias/admin-alta-categoria.php?status=error");
        exit();
    }

} else {
    header("Location: ../../views/admin/categorias/admin-alta-categoria.php");
    exit();
}
?>