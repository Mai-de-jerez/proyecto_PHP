<?php 
// comprobamos el rol del usuario logueado antes de mostrar la página
$rolNecesario = 'ADMIN';
require_once __DIR__ . '/../../../includes/seguridad.php';
// llamamos a la conexion y al modelo de categorías para obtener el listado de categorías activas
require_once __DIR__ . '/../../../models/categorias.php';
require_once __DIR__ . '/../../../includes/conexion.php';
// obtenemos el listado de categorías activas para el select del formulario
$listado_categorias = obtenerCategoriasActivas($conexion);
mysqli_close($conexion); 

$titulo = "Añadir producto | Administración";
$bodyClass = "admin-body"; 
$paginaAdmin = "alta-producto";
include __DIR__ . '/../../../includes/header.php';
include __DIR__ . '/../../../includes/menu-admin.php';
?>

<main class="admin-main">
    <header class="admin-topbar">
        <div>
            <h2>Añadir nuevo producto</h2>
            <p>Inicio › Productos › Añadir</p>
        </div>
        <div class="admin-usuario">Administrador</div>
    </header>

    <section class="admin-contenido dos-columnas-admin">
        <form class="formulario-admin" action="controllers/productos/guardar-producto.php" method="post" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="campo ancho-completo">
                    <label for="nombre">Nombre del producto *</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej: Cuenco tibetano artesanal 18 cm">
                </div>

                <div class="campo">
                    <label for="id_categoria">Categoría *</label>
                    <select id="id_categoria" name="id_categoria">
                        <option value="">Selecciona una categoría</option>
                        
                        <?php foreach ($listado_categorias as $cat): ?>
                            <option value="<?php echo $cat['id_categoria']; ?>">
                                <?php echo htmlspecialchars($cat['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                        
                    </select>
                </div>

                <div class="campo">
                    <label for="precio">Precio (€) *</label>
                    <input type="number" id="precio" name="precio" placeholder="Ej: 79.90">
                </div>

                <div class="campo">
                    <label for="stock">Stock *</label>
                    <input type="number" id="stock" name="stock" placeholder="Ej: 5">
                </div>

                <div class="campo">
                    <label for="diametro">Diámetro (cm)</label>
                    <input type="number" id="diametro" name="diametro" placeholder="Ej: 18">
                </div>

                <div class="campo">
                    <label for="peso">Peso (g)</label>
                    <input type="number" id="peso" name="peso" placeholder="Ej: 850">
                </div>

                <div class="campo">
                    <label for="nota">Melodia </label>
                    <input type="file" id="nota" name="nota" placeholder="Ej: Fa">
                </div>

                <div class="campo">
                    <label for="material">Material</label>
                    <input type="text" id="material" name="material" placeholder="Ej: Aleación de metales">
                </div>

                <div class="campo">
                    <label for="procedencia">Procedencia</label>
                    <input type="text" id="procedencia" name="procedencia" placeholder="Ej: Nepal">
                </div>

                <div class="campo ancho-completo">
                    <label for="descripcion">Descripción *</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Describe el producto, sus características, beneficios y usos..."></textarea>
                </div>
            </div>

            <div class="bloque-imagen-form">
                <label for="imagen">Imagen del producto *</label>
                <div class="zona-subida">
                    <input type="file" id="imagen" name="imagen">
                    <p>▧</p>
                    <strong>Subir imagen</strong>
                    <span>JPG, PNG o WEBP. Máx. 2MB</span>
                </div>
            </div>

            <div class="acciones-formulario">
                <a href="views/admin/productos/admin-listado-productos.php" class="boton cancelar">Cancelar</a>
                <button type="submit" class="boton principal">Guardar producto</button>
            </div>
        </form>

        <aside class="vista-previa-admin">
            <h3>Vista previa</h3>
            <article class="tarjeta-producto">
                <img src="img/cuenco-18.svg" alt="Vista previa cuenco">
                <h3>Cuenco tibetano artesanal 18 cm</h3>
                <p class="precio">79,90 €</p>
                <p>Diámetro: 18 cm</p>
                <p>Peso: 850 g</p>
                <p>Procedencia: Nepal</p>
            </article>
        </aside>
    </section>
</main>
<?php include __DIR__ . "/../../../includes/footer-simple.php";?>