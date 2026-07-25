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
?>
