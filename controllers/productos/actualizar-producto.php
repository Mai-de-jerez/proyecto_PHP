<?php
require_once __DIR__ . '/../../funciones/funciones.php';
require_once __DIR__ . '/../../models/productos.php';
require_once __DIR__ . '/../../includes/conexion.php';

if (isset($_POST) && !empty($_POST["id_producto"]) && !empty($_POST["nombre"])) {

    $idProducto = intval($_POST["id_producto"]);
    $nombre = addslashes($_POST["nombre"]);
    $categoria = intval($_POST["id_categoria"]);
    $precio = floatval($_POST["precio"]);
    $stock = intval($_POST["stock"]);
    $diametro = intval($_POST["diametro"]);
    $peso = floatval($_POST["peso"]);
    $material = addslashes($_POST["material"]);
    $procedencia = addslashes($_POST["procedencia"]);
    $descripcion = addslashes($_POST["descripcion"]);

    // Traemos el producto tal cual está ahora, por si no suben imagen/mp3 nuevos
    // y hay que conservar los que ya tenía
    $productoActual = obtenerProductoPorIdAdmin($conexion, $idProducto);

    if (!$productoActual) {
        mysqli_close($conexion);
        header("Location: ../../views/admin/productos/admin-listado-productos.php?status=notfound");
        exit();
    }

    // Imagen: si no se ha subido una nueva, mantenemos la actual
    if ($_FILES['imagen']['error'] === UPLOAD_ERR_NO_FILE) {
        $imagen = $productoActual['imagen'];
    } else {
        $imagen = subirFoto($_FILES['imagen'], $nombre, 10000000);

        // Si se intentó subir una imagen pero falló (formato no válido, etc.), no seguimos
        if ($imagen === false) {
            mysqli_close($conexion);
            header("Location: ../../views/admin/productos/admin-editar-producto.php?id=" . $idProducto . "&status=error");
            exit();
        }
    }

    // Melodía: mismo criterio que la imagen
    if ($_FILES['nota']['error'] === UPLOAD_ERR_NO_FILE) {
        $nota = $productoActual['nota_musical'];
    } else {
        $nota = subirMP3($_FILES['nota'], $nombre);
    }

    $actualizado = actualizarProducto(
        $conexion,
        $idProducto,
        $nombre,
        $categoria,
        $precio,
        $stock,
        $diametro,
        $peso,
        $material,
        $procedencia,
        $descripcion,
        $imagen,
        $nota
    );

    mysqli_close($conexion);

    if ($actualizado) {
        header("Location: ../../views/admin/productos/admin-listado-productos.php?status=updated");
        exit();
    } else {
        header("Location: ../../views/admin/productos/admin-editar-producto.php?id=" . $idProducto . "&status=error");
        exit();
    }

} else {
    header("Location: ../../views/admin/productos/admin-listado-productos.php");
    exit();
}
?>