<?php
if (!isset($pagina)) {
    $pagina = "";
}
?>
<header class="cabecera">
    <div class="logo">
        <div class="logo-icono">◉</div>
        <div>
            <h1>Sonido Interior</h1>
            <p>Cuencos Tibetanos</p>
        </div>
    </div>

    <nav class="menu">
        <a href="views/public/index.php" class="<?php echo ($pagina == 'inicio') ? 'activo' : ''; ?>">Inicio</a>
        <a href="views/public/productos/catalogo.php" class="<?php echo ($pagina == 'catalogo') ? 'activo' : ''; ?>">Catálogo</a>
        <a href="#">Sonoterapia</a>
        <a href="#">Sobre nosotros</a>
        <a href="#">Contacto</a> 
    </nav>

    <div class="acciones-header">
        <a href="#">🔍</a>
        <?php if (isset($_SESSION['id_usuario'])): ?>
            <a href="controllers/auth/logout.php" title="Cerrar sesión">⏻</a>
            <a href="views/public/carrito.php" title="Mi carrito">🛒</a>
        <?php else: ?>
            <a href="views/public/login.php" title="Iniciar sesión">👤</a>
        <?php endif; ?>
    </div>
</header>
