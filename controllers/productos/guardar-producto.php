<?php
require_once __DIR__ . '/../../includes/seguridad.php';
require_once __DIR__ . '/../../funciones/funciones.php';
require_once __DIR__ . '/../../models/productos.php';
require_once __DIR__ . '/../../models/categorias.php';
require_once __DIR__ . '/../../includes/conexion.php';

if (isset($_POST) && !empty($_POST["nombre"])) {

    session_start();

    $idProducto = isset($_POST["id_producto"]) && ctype_digit($_POST["id_producto"]) ? (int) $_POST["id_producto"] : null;

    $nombre = trim($_POST["nombre"] ?? '');
    $idCategoriaPost = $_POST["id_categoria"] ?? '';
    $precio = trim($_POST["precio"] ?? '');
    $stock = trim($_POST["stock"] ?? '');
    $diametro = trim($_POST["diametro"] ?? '');
    $peso = trim($_POST["peso"] ?? '');
    $material = trim($_POST["material"] ?? '');
    $procedencia = trim($_POST["procedencia"] ?? '');
    $descripcion = trim($_POST["descripcion"] ?? '');

    $urlVuelta = $idProducto
        ? "../../views/admin/productos/admin-editar-producto.php?id=" . $idProducto
        : "../../views/admin/productos/admin-alta-producto.php";

    $errores = [];

    // --- Nombre ---
    if ($nombre === '') {
        $errores['nombre'] = "El nombre es obligatorio.";
    } elseif (mb_strlen($nombre) < 3 || mb_strlen($nombre) > 50) {
        $errores['nombre'] = "El nombre debe tener entre 3 y 50 caracteres.";
    }

    // --- Categoría: debe existir en BD ---
    $categoriasActivas = obtenerCategoriasActivas($conexion);
    $idsCategoriasValidas = array_column($categoriasActivas, 'id_categoria');
    if ($idCategoriaPost === '' || !in_array((int) $idCategoriaPost, $idsCategoriasValidas)) {
        $errores['id_categoria'] = "Selecciona una categoría válida.";
    }
    $categoria = (int) $idCategoriaPost;

    // --- Precio ---
    if ($precio === '') {
        $errores['precio'] = "El precio es obligatorio.";
    } elseif (!is_numeric($precio) || $precio <= 0 || $precio > 2000) {
        $errores['precio'] = "El precio debe ser mayor que 0 y no superar 2000€.";
    }

    // --- Stock ---
    if ($stock === '') {
        $errores['stock'] = "El stock es obligatorio.";
    } elseif (!ctype_digit($stock) || (int) $stock <= 0 || (int) $stock > 10000) {
        $errores['stock'] = "El stock debe ser mayor que 0 y no superar 10000 unidades.";
    }

    // --- Peso ---
    if ($peso === '') {
        $errores['peso'] = "El peso es obligatorio.";
    } elseif (!is_numeric($peso) || $peso <= 0 || $peso > 10000) {
        $errores['peso'] = "El peso debe ser mayor que 0 y no superar 10000g.";
    }

    // --- Diámetro (opcional) ---
    if ($diametro !== '' && (!is_numeric($diametro) || $diametro <= 0 || $diametro > 100)) {
        $errores['diametro'] = "El diámetro debe ser mayor que 0 y no superar 100cm.";
    }

    // --- Material ---
    if ($material === '') {
        $errores['material'] = "El material es obligatorio.";
    } elseif (mb_strlen($material) < 3 || mb_strlen($material) > 50) {
        $errores['material'] = "El material debe tener entre 3 y 50 caracteres.";
    } elseif (!preg_match('/^[A-Za-zÀ-ÿñÑ0-9\s\-]+$/u', $material)) {
        $errores['material'] = "El material contiene caracteres no válidos.";
    }

    // --- Procedencia ---
    if ($procedencia === '') {
        $errores['procedencia'] = "La procedencia es obligatoria.";
    } elseif (mb_strlen($procedencia) < 3 || mb_strlen($procedencia) > 50) {
        $errores['procedencia'] = "La procedencia debe tener entre 3 y 50 caracteres.";
    } elseif (!preg_match('/^[A-Za-zÀ-ÿñÑ0-9\s\-]+$/u', $procedencia)) {
        $errores['procedencia'] = "La procedencia contiene caracteres no válidos.";
    }

    // --- Descripción ---
    if ($descripcion === '') {
        $errores['descripcion'] = "La descripción es obligatoria.";
    } elseif (mb_strlen($descripcion) < 15 || mb_strlen($descripcion) > 300) {
        $errores['descripcion'] = "La descripción debe tener entre 15 y 300 caracteres.";
    }

    // --- Imagen: obligatoria en alta; en edición, si no suben nueva, se mantiene la actual ---
    if (!$idProducto && (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] === UPLOAD_ERR_NO_FILE)) {
        $errores['imagen'] = "La imagen es obligatoria.";
    }

    if (!empty($errores)) {
        mysqli_close($conexion);
        $_SESSION['errores'] = $errores;
        header("Location: " . $urlVuelta);
        exit();
    }

    // A partir de aquí, pasamos a tipos correctos para el modelo
    $nombre = addslashes($nombre);
    $precio = floatval($precio);
    $stock = intval($stock);
    $diametro = ($diametro !== '') ? intval($diametro) : null;
    $peso = floatval($peso);
    $material = addslashes($material);
    $procedencia = addslashes($procedencia);
    $descripcion = addslashes($descripcion);

    if ($idProducto) {
        // --- EDICIÓN ---

        $productoActual = obtenerProductoPorIdAdmin($conexion, $idProducto);

        if (!$productoActual) {
            mysqli_close($conexion);
            header("Location: ../../views/admin/productos/admin-listado-productos.php?status=notfound");
            exit();
        }

        if ($_FILES['imagen']['error'] === UPLOAD_ERR_NO_FILE) {
            $imagen = $productoActual['imagen'];
        } else {
            $imagen = subirFoto($_FILES['imagen'], $nombre, 10000000);
            if ($imagen === false) {
                mysqli_close($conexion);
                $_SESSION['mensaje_error'] = "Error con el archivo de imagen (formato no válido o peso superior al permitido).";
                header("Location: " . $urlVuelta);
                exit();
            }
        }

        if ($_FILES['nota']['error'] === UPLOAD_ERR_NO_FILE) {
            $nota = $productoActual['nota_musical'];
        } else {
            $nota = subirMP3($_FILES['nota'], $nombre);
            if ($nota === false) {
                mysqli_close($conexion);
                $_SESSION['mensaje_error'] = "Error con el archivo de melodía (formato no válido o peso superior al permitido).";
                header("Location: " . $urlVuelta);
                exit();
            }
        }

        $resultado = actualizarProducto(
            $conexion, $idProducto, $nombre, $categoria, $precio, $stock,
            $diametro, $peso, $material, $procedencia, $descripcion, $imagen, $nota
        );
        $mensajeExito = "Producto actualizado con éxito.";
        $mensajeError = "Error al actualizar el producto.";

    } else {
        // --- ALTA ---

        $imagen = subirFoto($_FILES['imagen'], $nombre, 10000000);
        $nota = subirMP3($_FILES["nota"], $nombre);

        if ($imagen === false || $nota === false) {
            mysqli_close($conexion);
            $_SESSION['mensaje_error'] = "Error con el archivo de imagen o la melodía (formato no válido o peso superior al permitido).";
            header("Location: " . $urlVuelta);
            exit();
        }

        $resultado = insertarProducto(
            $conexion, $nombre, $categoria, $precio, $stock,
            $diametro, $peso, $material, $procedencia, $descripcion, $imagen, $nota
        );
        $mensajeExito = "Producto guardado con éxito.";
        $mensajeError = "Error al guardar el producto.";
    }

    mysqli_close($conexion);

    if ($resultado) {
        $_SESSION['mensaje_exito'] = $mensajeExito;
        header("Location: ../../views/admin/productos/admin-listado-productos.php");
        exit();
    } else {
        $_SESSION['mensaje_error'] = $mensajeError;
        header("Location: " . $urlVuelta);
        exit();
    }

} else {
    header("Location: ../../views/admin/productos/admin-alta-producto.php");
    exit();
}