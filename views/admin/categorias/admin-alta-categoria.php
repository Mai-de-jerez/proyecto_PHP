<?php
// comprobamos el rol del usuario logueado antes de mostrar la página
$rolNecesario = 'ADMIN';
require_once __DIR__ . '/../../../includes/seguridad.php';

$titulo = "Añadir categoría | Administración";
$bodyClass = "admin-body";
$paginaAdmin = "categorias";
include __DIR__ . '/../../../includes/header.php';
include __DIR__ . '/../../../includes/menu-admin.php';
?>

<main class="admin-main">
    <header class="admin-topbar">
        <div>
            <h2>Añadir nueva categoría</h2>
            <p>Inicio › Categorías › Añadir</p>
        </div>
        <div class="admin-usuario">Administrador</div>
    </header>

    <section class="admin-contenido">
        <form class="formulario-admin" action="controllers/categorias/guardar-categoria.php" method="post">
            <div class="form-grid">
                <div class="campo ancho-completo">
                    <label for="nombre">Nombre de la categoría *</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej: Cuencos grandes">
                </div>

                <div class="campo ancho-completo">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Describe brevemente esta categoría..."></textarea>
                </div>
            </div>

            <div class="acciones-formulario">
                <a href="views/admin/categorias/admin-listado-categorias.php" class="boton cancelar">Cancelar</a>
                <button type="submit" class="boton principal">Guardar categoría</button>
            </div>
        </form>
    </section>
</main>
<?php include __DIR__ . "/../../../includes/footer-simple.php"; ?>