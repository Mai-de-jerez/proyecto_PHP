<?php
if (!isset($paginaAdmin)) {
    $paginaAdmin = "";
}
?>
<aside class="sidebar">
    <div class="logo admin-logo">
        <div class="logo-icono">◉</div>
        <div>
            <h1>Sonido Interior</h1>
            <p>Admin</p>
        </div>
    </div>

    <nav class="menu-admin">
        <a href="#">⌂ Panel</a>
        <a href="views/admin/productos/admin-listado-productos.php" class="<?php echo ($paginaAdmin == 'productos') ? 'activo' : ''; ?>">▣ Productos</a>
        <a href="views/admin/productos/admin-alta-producto.php" class="sub <?php echo ($paginaAdmin == 'alta-producto') ? 'activo-sub' : ''; ?>">Añadir producto</a>
        <a href="views/admin/#">◇ Categorías</a>
        <a href="#">✉ Mensajes</a>
        <a href="#">⚙ Configuración</a>
        <a href="../../public/login.php">↩ Cerrar sesión</a>
    </nav>
</aside> 
