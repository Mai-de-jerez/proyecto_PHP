<?php
require_once __DIR__ . '/../../includes/seguridad.php';
require_once __DIR__ . '/../../funciones/funciones.php';
require_once __DIR__ . '/../../includes/conexion.php';
require_once __DIR__ . '/../../models/productos.php';

    // Verificamos que los datos vengan por POST y que al menos el nombre esté relleno
    //isset -> si esta instanciada?  - unset .> la deisntancia  
    //empty -> esta vacia?
    if (isset($_POST) && !empty($_POST["nombre"])) {

        // Ya no hace falta addslashes, usamos prepared statements en la consulta SQL,
        // pero aún así lo pondré porque me lo enseñó mi profe este truco y no olvidarlo
        $nombre = addslashes($_POST["nombre"]);
        $categoria = addslashes($_POST["id_categoria"]);
        $precio = floatval($_POST["precio"]); // Convertimos a float para evitar problemas de tipo
        $stock = intval($_POST["stock"]); // Convertimos a entero para evitar problemas de tipo
        $diametro = intval($_POST["diametro"]); // Convertimos a entero para evitar problemas de tipo
        $peso = floatval($_POST["peso"]); // Convertimos a float para evitar problemas de tipo
        $material = addslashes($_POST["material"]);
        $procedencia = addslashes($_POST["procedencia"]);
        $descripcion = addslashes($_POST["descripcion"]);

        // Procesamos la foto y la melodía (subida al servidor y rutas)
        $imagen = subirFoto($_FILES['imagen'], $nombre, 10000000);
        $nota = subirMP3($_FILES["nota"], $nombre);

        // Si la imagen o la melodía han fallado (formato no válido, demasiado pesada, etc.), no seguimos
        if ($imagen === false || $nota === false) {
            $_SESSION['mensaje_error'] = "Error con el archivo de imagen o la melodía (formato no válido o peso superior al permitido).";
            header("Location: ../../views/admin/productos/admin-alta-producto.php?status=error");
            exit();
        }

        // Llamamos a la función del modelo para insertar
        $exito = insertarProducto($conexion, $nombre, $categoria, $precio, $stock, $diametro, $peso, $material, $procedencia, $descripcion, $imagen, $nota);

        mysqli_close($conexion);

        if ($exito) {
            $_SESSION['mensaje_exito'] = "Producto guardado con éxito.";
            header("Location: ../../views/admin/productos/admin-listado-productos.php?status=success");
            exit();
        } else {
            $_SESSION['mensaje_error'] = "Error al guardar el producto.";
            header("Location: ../../views/admin/productos/admin-alta-producto.php?status=error");
            exit();
        }

    } else {
        header("Location: ../../views/admin/productos/admin-alta-producto.php");
        exit();
    }
?>