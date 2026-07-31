<?php
require __DIR__ . "/../../../models/productos.php";
require __DIR__ . "/../../../includes/conexion.php";

// Leemos y saneamos el id que llega por la URL
$idProducto = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? (int) $_GET['id'] : null;

$producto = null;
if ($idProducto !== null) {
    $producto = obtenerProductoPorId($conexion, $idProducto);
}
// Construimos la URL de "volver" al catálogo, incluyendo los filtros y la página actual si vienen en la URL
$urlVolver = "views/public/productos/catalogo.php";
if (!empty($_GET['volver'])) {
    $urlVolver .= "?" . $_GET['volver'];
}

mysqli_close($conexion);

$titulo = $producto ? htmlspecialchars($producto['nombre']) . " | Sonido Interior" : "Producto no encontrado | Sonido Interior";
$pagina = "catalogo";
include __DIR__ . "/../../../includes/header.php";
include __DIR__ . "/../../../includes/menu.php";
?>
<main class="contenedor">

    <?php if (!$producto): ?>

        <!-- Si no existe el producto (id inválido, borrado o inactivo) -->
        <section class="encabezado-pagina">
            <h2>Producto no encontrado</h2>
            <p>Puede que se haya agotado o ya no esté disponible.</p>
            <p style="text-align: center;">
                <a href="<?php echo htmlspecialchars($urlVolver); ?>" class="boton principal">Volver al catálogo</a>
            </p>
        </section>

    <?php else: ?>

        <section class="detalle-producto">

            <div class="detalle-producto-imagen">
                <?php if (!empty($producto['imagen'])): ?>
                    <img src="img/productos/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                <?php else: ?>
                    <img src="img/cuenco-12.svg" alt="Por defecto">
                <?php endif; ?>
            </div>

            <div class="detalle-producto-info">
                <h2><?php echo htmlspecialchars($producto['nombre']); ?></h2>
                <span class="precio"><?php echo number_format($producto['precio'], 2, ',', '.'); ?> €</span>

                <p class="descripcion"><?php echo htmlspecialchars($producto['descripcion']); ?></p>

                <ul class="detalle-producto-caracteristicas">
                    <?php if (!empty($producto['material'])): ?>
                        <li><strong>Material</strong><?php echo htmlspecialchars($producto['material']); ?></li>
                    <?php endif; ?>

                    <?php if (!empty($producto['procedencia'])): ?>
                        <li><strong>Procedencia</strong><?php echo htmlspecialchars($producto['procedencia']); ?></li>
                    <?php endif; ?>

                    <?php if (!empty($producto['diametro'])): ?>
                        <li><strong>Diámetro</strong><?php echo htmlspecialchars($producto['diametro']); ?> cm</li>
                    <?php endif; ?>

                    <?php if (!empty($producto['peso'])): ?>
                        <li><strong>Peso</strong><?php echo htmlspecialchars($producto['peso']); ?> g</li>
                    <?php endif; ?>
                </ul>

                <?php if (!empty($producto['nota_musical'])): ?>
                    <div style="margin-bottom: 22px;">
                        <strong style="display:block; font-size:12px; text-transform:uppercase; color:#8a735f; margin-bottom:6px;">Melodía</strong>
                        <audio controls style="width: 100%;">
                            <source src="sonidos/<?php echo htmlspecialchars($producto['nota_musical']); ?>" type="audio/mpeg">
                            Tu navegador no soporta audio.
                        </audio>
                    </div>
                <?php endif; ?>

                <?php if ($producto['stock'] > 0): ?>
                    <p class="detalle-producto-stock disponible">✓ Disponible (<?php echo $producto['stock']; ?> unidades)</p>
                    <form action="controllers/carrito/agregar-producto.php" method="post">
                        <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                        <input type="hidden" name="cantidad" value="1">
                        <button type="submit" class="boton principal bloque">Añadir al carrito</button>
                    </form>
                <?php else: ?>
                    <p class="detalle-producto-stock agotado">✕ Agotado temporalmente</p>
                <?php endif; ?>

                <a href="<?php echo htmlspecialchars($urlVolver); ?>" class="boton cancelar bloque">Volver al catálogo</a>
            </div>

        </section>

    <?php endif; ?>

</main>

<?php include __DIR__ . "/../../../includes/footer.php"; ?>