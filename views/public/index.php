<?php
require_once __DIR__ . '/../../models/productos.php';
require_once __DIR__ . '/../../includes/conexion.php';

$listado_inicio = obtenerUltimosProductosInicio($conexion); 
mysqli_close($conexion); 

$titulo = "Sonido Interior | Cuencos Tibetanos";
$pagina = "inicio";
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/menu.php';
?>

<main>
    <section class="hero">
        <div class="hero-texto">
            <h2>Armonía que<br>se siente</h2>
            <p>Cuencos tibetanos artesanales para meditación, relajación y bienestar interior.</p>
            <a href="views/public/productos/catalogo.php" class="boton principal">Ver catálogo</a>
        </div>
    </section>

    <section class="beneficios-superiores">
        <article>
            <div class="icono">♨</div>
            <h3>Artesanía auténtica</h3>
            <p>Cuencos seleccionados de forma ética y consciente.</p>
        </article>
        <article>
            <div class="icono">≋</div>
            <h3>Vibración y equilibrio</h3>
            <p>Cada cuenco tiene una vibración única y transformadora.</p>
        </article>
        <article>
            <div class="icono">☘</div>
            <h3>Bienestar y conexión</h3>
            <p>Herramientas para tu práctica diaria y crecimiento personal.</p>
        </article>
    </section>

    <section class="seccion">
        <h2 class="titulo-seccion">Productos destacados</h2>
        <div class="grid-productos destacados">
            <?php if (empty($listado_inicio)): ?>
                <!-- Mensaje por si la base de datos estuviera vacía temporalmente -->
                <p style="text-align: center; grid-column: 1 / -1; color: #8a735f; padding: 20px;">Próximamente nuevos cuencos disponibles.</p>
            <?php else: ?>
                <?php foreach ($listado_inicio as $prod): ?>
                    <article class="tarjeta-producto">
                        <?php if (!empty($prod['imagen'])): ?>
                            <img src="img/productos/<?php echo htmlspecialchars($prod['imagen']); ?>" alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
                        <?php else: ?>
                            <img src="img/cuenco-12.svg" alt="Por defecto">
                        <?php endif; ?>
                        
                        <h3><?php echo htmlspecialchars($prod['nombre']); ?></h3>
                        <p class="precio"><?php echo number_format($prod['precio'], 2, ',', '.'); ?> €</p>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="franja-servicios">
        <article>
            <strong>🚚 Envíos 24/48h</strong>
            <p>A toda la península</p>
        </article>
        <article>
            <strong>↩ Devoluciones fáciles</strong>
            <p>Hasta 14 días</p>
        </article>
        <article>
            <strong>☏ Atención personalizada</strong>
            <p>Te ayudamos a elegir</p>
        </article>
    </section>
</main>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
