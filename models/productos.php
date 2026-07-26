<?php
//==============================================
// ----------CONSULTAS A PRODUCTOS-------------
//==============================================

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
 
    $sql = "SELECT id_producto, imagen, nombre, precio 
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

// Función para obtener productos ACTIVOS para el catálogo público, con filtro, orden y paginación
// Solo trae la porción de datos que el usuario está viendo en ese momento: ni todos los productos, ni la descripción completa
function obtenerProductosCatalogo(mysqli $conexion, ?int $idCategoria = null, string $orden = 'recientes', int $pagina = 1, int $porPagina = 12) {

    $offset = ($pagina - 1) * $porPagina;

    $sql = "SELECT id_producto, nombre, precio, imagen, SUBSTRING(descripcion, 1, 150) AS descripcion 
            FROM productos 
            WHERE activo = 1";

    $tipos = "";
    $parametros = [];

    if ($idCategoria !== null) {
        $sql .= " AND id_categoria = ?";
        $tipos .= "i";
        $parametros[] = $idCategoria;
    }

    switch ($orden) {
        case 'precio_asc':
            $sql .= " ORDER BY precio ASC";
            break;
        case 'precio_desc':
            $sql .= " ORDER BY precio DESC";
            break;
        default:
            $sql .= " ORDER BY id_producto DESC";
    }

    $sql .= " LIMIT ? OFFSET ?";
    $tipos .= "ii";
    $parametros[] = $porPagina;
    $parametros[] = $offset;

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param($tipos, ...$parametros);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $productos = [];
    while ($fila = $resultado->fetch_assoc()) {
        $productos[] = $fila;
    }
    $stmt->close();

    return $productos;
}

// Función para contar productos ACTIVOS según el filtro (necesaria para calcular el nº de páginas en catalogo.php)
function contarProductosCatalogo(mysqli $conexion, ?int $idCategoria = null): int {

    $sql = "SELECT COUNT(*) AS total FROM productos WHERE activo = 1";
    $tipos = "";
    $parametros = [];

    if ($idCategoria !== null) {
        $sql .= " AND id_categoria = ?";
        $tipos .= "i";
        $parametros[] = $idCategoria;
    }

    $stmt = $conexion->prepare($sql);
    if ($tipos !== "") {
        $stmt->bind_param($tipos, ...$parametros);
    }
    $stmt->execute();
    $fila = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return (int) $fila['total'];
}
// Función para obtener un producto por su ID, incluyendo la categoría, solo si está activo
function obtenerProductoPorId(mysqli $conexion, int $idProducto): ?array {

    $sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.stock, p.imagen,
                   p.diametro, p.peso, p.material, p.nota_musical, p.procedencia,
                   c.nombre AS nombre_categoria
            FROM productos p
            INNER JOIN categorias c ON p.id_categoria = c.id_categoria
            WHERE p.id_producto = ? AND p.activo = 1";

    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idProducto);
    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);
    $producto = mysqli_fetch_assoc($resultado);

    mysqli_stmt_close($stmt);

    return $producto ?: null;
}

// Función para obtener un producto por su ID, incluyendo la categoría, sin importar si está activo o no (para admin)
function obtenerProductoPorIdAdmin(mysqli $conexion, int $idProducto): ?array {

    $sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.stock, p.imagen,
                   p.diametro, p.peso, p.material, p.nota_musical, p.procedencia, p.id_categoria
            FROM productos p
            WHERE p.id_producto = ?";

    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idProducto);
    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);
    $producto = mysqli_fetch_assoc($resultado);

    mysqli_stmt_close($stmt);

    return $producto ?: null;
}

// Función para actualizar un producto en la base de datos, incluyendo la imagen y la melodía
function actualizarProducto(mysqli $conexion, int $idProducto, string $nombre, int $idCategoria, float $precio, int $stock, ?int $diametro, ?float $peso, ?string $material, ?string $procedencia, string $descripcion, string $imagen, ?string $nota): bool {

    $sql = "UPDATE productos SET
                nombre = ?, id_categoria = ?, precio = ?, stock = ?, diametro = ?, peso = ?,
                material = ?, procedencia = ?, descripcion = ?, imagen = ?, nota_musical = ?
            WHERE id_producto = ?";

    $stmt = mysqli_prepare($conexion, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sidiidsssssi",
        $nombre, $idCategoria, $precio, $stock, $diametro, $peso,
        $material, $procedencia, $descripcion, $imagen, $nota, $idProducto
    );
    $resultado = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $resultado;
}

// Función para hacer un borrado LÓGICO: no elimina el producto de la BD,
// solo lo marca como inactivo para que deje de aparecer en la parte pública
function eliminarProductoLogico(mysqli $conexion, int $idProducto): bool {
 
    $sql = "UPDATE productos SET activo = 0 WHERE id_producto = ?";
 
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idProducto);
    $resultado = mysqli_stmt_execute($stmt);
 
    mysqli_stmt_close($stmt);
 
    return $resultado;
}

// Función para reactivar un producto que estaba desactivado (proceso inverso al borrado lógico)
function reactivarProducto(mysqli $conexion, int $idProducto): bool {
 
    $sql = "UPDATE productos SET activo = 1 WHERE id_producto = ?";
 
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idProducto);
    $resultado = mysqli_stmt_execute($stmt);
 
    mysqli_stmt_close($stmt);
 
    return $resultado;
}
?>