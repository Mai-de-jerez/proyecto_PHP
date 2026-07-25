<?php
//==============================================
// ----------CONSULTAS A CATEGORIAS-------------
//==============================================

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

// Función para obtener TODAS las categorías (activas e inactivas) para el listado de administración
function obtenerCategoriasAdmin(mysqli $conexion) {
    $sql = "SELECT id_categoria, nombre, descripcion, activo FROM categorias ORDER BY id_categoria DESC";
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

// Función para crear una nueva categoría
function crearCategoria(mysqli $conexion, string $nombre, ?string $descripcion): bool {
    $sql = "INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)";
 
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $nombre, $descripcion);
    $resultado = mysqli_stmt_execute($stmt);
 
    mysqli_stmt_close($stmt);
 
    return $resultado;
}
?>
