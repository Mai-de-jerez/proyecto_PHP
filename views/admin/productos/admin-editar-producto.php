<?php
// comprobamos el rol del usuario logueado antes de mostrar la página
$rolNecesario = 'ADMIN';
require_once __DIR__ . '/../../../includes/seguridad.php';
require_once __DIR__ . '/../../../models/categorias.php';
require_once __DIR__ . '/../../../models/productos.php';
require_once __DIR__ . '/../../../includes/conexion.php';

// Leemos el id de la URL; si no es válido o no existe el producto, no seguimos
$idProducto = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? (int) $_GET['id'] : null;
$producto = $idProducto !== null ? obtenerProductoPorIdAdmin($conexion, $idProducto) : null;

$listado_categorias = obtenerCategoriasActivas($conexion);
mysqli_close($conexion);

if (!$producto) {
    header("Location: ../../views/admin/productos/admin-listado-productos.php?status=notfound");
    exit();
}

$titulo = "Editar producto | Administración";
$bodyClass = "admin-body";
$paginaAdmin = "productos";
include __DIR__ . '/../../../includes/header.php';
include __DIR__ . '/../../../includes/menu-admin.php';
?>

<main class="admin-main">
    <header class="admin-topbar">
        <div>
            <h2>Editar producto</h2>
            <p>Inicio › Productos › Editar</p>
        </div>
        <div class="admin-usuario">Administrador</div>
    </header>

    <section class="admin-contenido dos-columnas-admin">
        <form class="formulario-admin" action="controllers/productos/actualizar-producto.php" method="post" enctype="multipart/form-data">

            <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">

            <div class="form-grid">
                <div class="campo ancho-completo">
                    <label for="nombre">Nombre del producto *</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>">
                </div>

                <div class="campo">
                    <label for="id_categoria">Categoría *</label>
                    <select id="id_categoria" name="id_categoria">
                        <option value="">Selecciona una categoría</option>
                        <?php foreach ($listado_categorias as $cat): ?>
                            <option value="<?php echo $cat['id_categoria']; ?>"
                                <?php echo ($cat['id_categoria'] == $producto['id_categoria']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="campo">
                    <label for="precio">Precio (€) *</label>
                    <input type="number" id="precio" name="precio" step="0.01" value="<?php echo htmlspecialchars($producto['precio']); ?>">
                </div>

                <div class="campo">
                    <label for="stock">Stock *</label>
                    <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($producto['stock']); ?>">
                </div>

                <div class="campo">
                    <label for="diametro">Diámetro (cm)</label>
                    <input type="number" id="diametro" name="diametro" value="<?php echo htmlspecialchars($producto['diametro'] ?? ''); ?>">
                </div>

                <div class="campo">
                    <label for="peso">Peso (g)</label>
                    <input type="number" id="peso" name="peso" value="<?php echo htmlspecialchars($producto['peso'] ?? ''); ?>">
                </div>

                <div class="campo">
                    <label for="nota">Melodía (opcional, deja vacío para mantener la actual)</label>
                    <input type="file" id="nota" name="nota">
                </div>

                <div class="campo">
                    <label for="material">Material</label>
                    <input type="text" id="material" name="material" value="<?php echo htmlspecialchars($producto['material'] ?? ''); ?>">
                </div>

                <div class="campo">
                    <label for="procedencia">Procedencia</label>
                    <input type="text" id="procedencia" name="procedencia" value="<?php echo htmlspecialchars($producto['procedencia'] ?? ''); ?>">
                </div>

                <div class="campo ancho-completo">
                    <label for="descripcion">Descripción *</label>
                    <textarea id="descripcion" name="descripcion"><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
                </div>
            </div>

            <div class="bloque-imagen-form">
                <label for="imagen">Imagen del producto (opcional, deja vacío para mantener la actual)</label>
                <div class="zona-subida">
                    <input type="file" id="imagen" name="imagen">
                    <p>▧</p>
                    <strong>Subir nueva imagen</strong>
                    <span>JPG, PNG o WEBP. Máx. 2MB</span>
                </div>
            </div>

            <div class="acciones-formulario">
                <a href="views/admin/productos/admin-listado-productos.php" class="boton cancelar">Cancelar</a>
                <button type="submit" class="boton principal">Guardar cambios</button>
            </div>
        </form>

        <aside class="vista-previa-admin">
            <h3>Imagen actual</h3>
            <article class="tarjeta-producto">
                <?php if (!empty($producto['imagen'])): ?>
                    <img src="img/productos/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                <?php else: ?>
                    <img src="img/cuenco-12.svg" alt="Sin imagen">
                <?php endif; ?>
                <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                <p class="precio"><?php echo number_format($producto['precio'], 2, ',', '.'); ?> €</p>
            </article>
        </aside>
    </section>
</main>
<?php include __DIR__ . "/../../../includes/footer-simple.php"; ?>