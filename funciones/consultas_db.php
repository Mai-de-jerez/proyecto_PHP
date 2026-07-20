<?php
// Función para obtener las categorías activas desde la base de datos
function obtenerCategoriasActivas(mysqli $conexion) {
    $sql = "SELECT id_categoria, nombre FROM categorias WHERE activo = 1";
    $resultado = mysqli_query($conexion, $sql);
    
    $categorias = [];
    if ($resultado) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $categorias[] = $fila;
        }
        mysqli_free_result($resultado);
    }
    
    return $categorias;
}

// Función para obtener todos los productos activos con sus categorías en el admin
function obtenerProductosAdmin(mysqli $conexion) {

    $sql = "SELECT p.id_producto, p.nombre, p.precio, p.stock, p.imagen, p.nota_musical, p.activo, c.nombre AS nombre_categoria 
            FROM productos p 
            INNER JOIN categorias c ON p.id_categoria = c.id_categoria 
            ORDER BY p.id_producto DESC";
            
    $resultado = mysqli_query($conexion, $sql);
    
    $productos = [];
    if ($resultado) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $productos[] = $fila;
        }
        mysqli_free_result($resultado);
    }
    
    return $productos;
}


// Función para obtener solo los 4 últimos productos ACTIVOS con imagen, nombre y precio para el inicio
function obtenerUltimosProductosInicio(mysqli $conexion) {

    $sql = "SELECT imagen, nombre, precio 
            FROM productos 
            WHERE activo = 1 
            ORDER BY id_producto DESC 
            LIMIT 4";
            
    $resultado = mysqli_query($conexion, $sql);
    
    $productos = [];
    if ($resultado) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $productos[] = $fila;
        }
        mysqli_free_result($resultado);
    }
    
    return $productos;
}

// Función para obtener TODOS los productos ACTIVOS para el catálogo público
function obtenerProductosCatalogo(mysqli $conexion) {

    $sql = "SELECT id_producto, nombre, precio, imagen, descripcion 
            FROM productos 
            WHERE activo = 1 
            ORDER BY id_producto DESC";
            
    $resultado = mysqli_query($conexion, $sql);
    
    $productos = [];
    if ($resultado) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $productos[] = $fila;
        }
        mysqli_free_result($resultado);
    }
    
    return $productos;
}
?>