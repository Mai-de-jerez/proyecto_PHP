<?php
require __DIR__ . "/../../../models/productos.php";
require __DIR__ . "/../../../models/categorias.php";
require __DIR__ . "/../../../includes/conexion.php";

$porPagina = 8;

// Leemos y saneamos los filtros que vienen de la URL
$categoriaSeleccionada = (isset($_GET['categoria']) && ctype_digit($_GET['categoria'])) ? (int) $_GET['categoria'] : null;
$ordenSeleccionado = in_array($_GET['orden'] ?? '', ['recientes', 'precio_asc', 'precio_desc']) ? $_GET['orden'] : 'recientes';
$paginaActual = (isset($_GET['pag']) && ctype_digit($_GET['pag']) && (int) $_GET['pag'] >= 1) ? (int) $_GET['pag'] : 1;

// Categorías para el select
$categorias = obtenerCategoriasActivas($conexion);

// Total de productos según el filtro, para calcular cuántas páginas hay
$totalProductos = contarProductosCatalogo($conexion, $categoriaSeleccionada);
$totalPaginas = (int) ceil($totalProductos / $porPagina);

// Si alguien fuerza en la URL una página que no existe (ej. ?pag=999), la recortamos a la última válida
if ($totalPaginas > 0 && $paginaActual > $totalPaginas) {
    $paginaActual = $totalPaginas;
}

// Productos de la página actual, ya filtrados/ordenados/paginados en la propia consulta
$productos = obtenerProductosCatalogo($conexion, $categoriaSeleccionada, $ordenSeleccionado, $paginaActual, $porPagina);
mysqli_close($conexion);

$titulo = "Catálogo | Sonido Interior";
$pagina = "catalogo";
include __DIR__ . "/../../../includes/header.php";
include __DIR__ . "/../../../includes/menu.php";
?>
<main class="contenedor">
    <section class="encabezado-pagina">
        <h2>Catálogo de productos</h2>
        <p>Explora nuestra selección de cuencos tibetanos, accesorios y sets de meditación.</p>
    </section>
    <!-- Filtros de categoría y ordenamiento -->
    <form class="filtros-catalogo" method="GET" action="views/public/productos/catalogo.php">
        <div>
            <label for="categoria">Categorías</label>
            <select name="categoria" id="categoria" onchange="this.form.submit()">
                <option value="">Todas</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?php echo $categoria['id_categoria']; ?>"
                        <?php echo ($categoriaSeleccionada === (int) $categoria['id_categoria']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($categoria['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="orden">Ordenar por</label>
            <select name="orden" id="orden" onchange="this.form.submit()">
                <option value="recientes" <?php echo ($ordenSeleccionado === 'recientes') ? 'selected' : ''; ?>>Más recientes</option>
                <option value="precio_asc" <?php echo ($ordenSeleccionado === 'precio_asc') ? 'selected' : ''; ?>>Precio menor</option>
                <option value="precio_desc" <?php echo ($ordenSeleccionado === 'precio_desc') ? 'selected' : ''; ?>>Precio mayor</option>
            </select>
        </div>
        <noscript><button type="submit" class="boton">Filtrar</button></noscript>
    </form>
    <!-- Mostramos los productos en un grid -->
    <section class="grid-productos catalogo-grid">
        <?php if (empty($productos)): ?>
            <!-- Por si no hay nada activo en la DB -->
            <p style="text-align: center; grid-column: 1 / -1; color: #8a735f; padding: 20px;">No hay productos disponibles en este momento.</p>
        <?php else: ?>
            <?php foreach ($productos as $producto): ?>
                <article class="tarjeta-producto">
                    <!-- Si el producto tiene imagen en la DB se muestra, si no, una por defecto -->
                    <?php if (!empty($producto['imagen'])): ?>
                        <img src="img/productos/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                    <?php else: ?>
                        <img src="img/cuenco-12.svg" alt="Por defecto">
                    <?php endif; ?>
                    
                    <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                    
                    <!-- Mostramos la descripción dinámica corta -->
                    <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                    
                    <p class="precio"><?php echo number_format($producto['precio'], 2, ',', '.'); ?> €</p>
                    
                    <?php $queryActual = http_build_query(['categoria' => $categoriaSeleccionada, 'orden' => $ordenSeleccionado, 'pag' => $paginaActual]); ?>
                    <!-- Enlazamos dinámicamente pasándole el id por la URL -->
                    <a href="views/public/productos/detalle-producto.php?id=<?php echo $producto['id_producto']; ?>&volver=<?php echo urlencode($queryActual); ?>" class="boton secundario">Ver producto</a>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
    <?php include __DIR__ . "/../../../includes/paginacion.php"; ?>
</main>

<?php 
include __DIR__ . "/../../../includes/footer.php";
?>
