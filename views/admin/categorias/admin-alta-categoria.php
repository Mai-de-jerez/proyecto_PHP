<?php
$rolNecesario = 'ADMIN';
require_once __DIR__ . '/../../../includes/seguridad.php';
require_once __DIR__ . '/../../../includes/conexion.php';
require_once __DIR__ . '/../../../models/categorias.php';

$categoria = null;
$esEdicion = false;

// Si viene un ID por GET, estamos editando
if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $id_categoria = (int) $_GET['id'];
    $categoria = obtenerCategoriaPorId($conexion, $id_categoria);
    if ($categoria) {
        $esEdicion = true;
    }
}

$titulo = $esEdicion ? "Editar categoría | Administración" : "Añadir categoría | Administración";
$bodyClass = "admin-body";
$paginaAdmin = "categorias";
include __DIR__ . '/../../../includes/header.php';
include __DIR__ . '/../../../includes/menu-admin.php';
?>

<main class="admin-main">
    <header class="admin-topbar">
        <div>
            <h2><?php echo $esEdicion ? "Editar categoría" : "Añadir nueva categoría"; ?></h2>
            <p>Inicio › Categorías › <?php echo $esEdicion ? "Editar" : "Añadir"; ?></p>
        </div>
        <div class="admin-usuario">Administrador</div>
    </header>

    <section class="admin-contenido">
        <form class="formulario-admin" action="controllers/categorias/guardar-categoria.php" method="post">
            
            <?php if ($esEdicion): ?>
                <input type="hidden" name="id_categoria" value="<?php echo $categoria['id_categoria']; ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="campo ancho-completo">
                    <label for="nombre">Nombre de la categoría *</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej: Cuencos grandes" value="<?php echo $esEdicion ? htmlspecialchars($categoria['nombre']) : ''; ?>">
                </div>

                <div class="campo ancho-completo">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Describe brevemente esta categoría..."><?php echo $esEdicion ? htmlspecialchars($categoria['descripcion']) : ''; ?></textarea>
                </div>
            </div>

            <div class="acciones-formulario">
                <a href="views/admin/categorias/admin-listado-categorias.php" class="boton cancelar">Cancelar</a>
                <button type="submit" class="boton principal"><?php echo $esEdicion ? "Actualizar categoría" : "Guardar categoría"; ?></button>
            </div>
        </form>
    </section>
</main>
<?php include __DIR__ . "/../../../includes/footer-simple.php"; ?>