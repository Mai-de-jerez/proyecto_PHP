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
        <a href="index.php" class="<?php echo ($pagina == 'inicio') ? 'activo' : ''; ?>">Inicio</a>
        <a href="catalogo.php" class="<?php echo ($pagina == 'catalogo') ? 'activo' : ''; ?>">Catálogo</a>
        <a href="#">Sonoterapia</a>
        <a href="#">Sobre nosotros</a>
        <a href="#">Contacto</a>
    </nav>

    <div class="acciones-header">
        <a href="#">🔍</a>
        <a href="login.php">👤</a>
        <a href="#">🛒</a>
    </div>
</header>
