<?php
require "./funciones/consultas_db.php";
require "includes/conexion.php"; 
 
$productos = obtenerProductosCatalogo($conexion);
mysqli_close($conexion); 

$titulo = "Catálogo | Sonido Interior";
$pagina = "catalogo";
include("includes/header.php");
include("includes/menu.php");
?>
<main class="contenedor">
    <section class="encabezado-pagina">
        <h2>Catálogo de productos</h2>
        <p>Explora nuestra selección de cuencos tibetanos, accesorios y sets de meditación.</p>
    </section>

    <section class="filtros-catalogo">
        <div>
            <label>Categorías</label>
            <select>
                <option>Todas</option>
                <option>Cuencos pequeños</option>
                <option>Cuencos medianos</option>
                <option>Cuencos grandes</option>
                <option>Accesorios</option>
            </select>
        </div>
        <div>
            <label>Ordenar por</label>
            <select>
                <option>Más recientes</option>
                <option>Precio menor</option>
                <option>Precio mayor</option>
            </select>
        </div>
    </section>
    <section class="grid-productos catalogo-grid">
        <?php if (empty($productos)): ?>
            <!-- Por si no hay nada activo en la DB -->
            <p style="text-align: center; grid-column: 1 / -1; color: #8a735f; padding: 20px;">No hay productos disponibles en este momento.</p>
        <?php else: ?>
            <?php foreach ($productos as $producto): ?>
                <article class="tarjeta-producto">
                    <!-- Si el producto tiene imagen en la DB se muestra, si no, una por defecto -->
                    <?php if (!empty($producto['imagen'])): ?>
                        <img src="cuencos/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                    <?php else: ?>
                        <img src="img/cuenco-12.svg" alt="Por defecto">
                    <?php endif; ?>
                    
                    <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                    
                    <!-- Mostramos la descripción dinámica corta -->
                    <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                    
                    <p class="precio"><?php echo number_format($producto['precio'], 2, ',', '.'); ?> €</p>
                    
                    <!-- Enlazamos dinámicamente pasándole el id por la URL -->
                    <a href="producto.php?id=<?php echo $producto['id_producto']; ?>" class="boton secundario">Ver producto</a>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</main>

<?php 
include("includes/footer.php");
?>
