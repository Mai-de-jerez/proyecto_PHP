<?php
$rolNecesario = 'ADMIN';
require_once __DIR__ . '/../../../includes/seguridad.php';
require_once __DIR__ . '/../../../includes/conexion.php';
require_once __DIR__ . '/../../../models/categorias.php';
require_once __DIR__ . '/../../../models/productos.php';

$producto = null;
$esEdicion = false;

if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $idProducto = (int) $_GET['id'];
    $producto = obtenerProductoPorIdAdmin($conexion, $idProducto);
    if ($producto) {
        $esEdicion = true;
    }
}

$listado_categorias = obtenerCategoriasActivas($conexion);
mysqli_close($conexion);

if (isset($_GET['id']) && !$esEdicion) {
    header("Location: ../../views/admin/productos/admin-listado-productos.php?status=notfound");
    exit();
}

$titulo = $esEdicion ? "Editar producto | Administración" : "Añadir producto | Administración";
$bodyClass = "admin-body";
$paginaAdmin = $esEdicion ? "productos" : "alta-producto";
include __DIR__ . '/../../../includes/header.php';
include __DIR__ . '/../../../includes/menu-admin.php';

$errores = $_SESSION['errores'] ?? [];
unset($_SESSION['errores']);
?>

<main class="admin-main">
    <header class="admin-topbar">
        <div>
            <h2><?php echo $esEdicion ? "Editar producto" : "Añadir nuevo producto"; ?></h2>
            <p>Inicio › Productos › <?php echo $esEdicion ? "Editar" : "Añadir"; ?></p>
        </div>
        <div class="admin-usuario">Administrador</div>
    </header>

    <section class="admin-contenido dos-columnas-admin">
        <form class="formulario-admin"
              action="controllers/productos/guardar-producto.php" 
              method="post" enctype="multipart/form-data"
              data-es-edicion="<?php echo $esEdicion ? '1' : '0'; ?>">

            <?php if ($esEdicion): ?>
                <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="campo ancho-completo">
                    <label for="nombre">Nombre del producto *</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej: Cuenco tibetano artesanal 18 cm"
                           value="<?php echo $esEdicion ? htmlspecialchars($producto['nombre']) : ''; ?>">
                    <span class="mensaje-error" id="error-nombre"><?= isset($errores['nombre']) ? htmlspecialchars($errores['nombre']) : '' ?></span>
                </div>

                <div class="campo">
                    <label for="id_categoria">Categoría *</label>
                    <select id="id_categoria" name="id_categoria">
                        <option value="">Selecciona una categoría</option>
                        <?php foreach ($listado_categorias as $cat): ?>
                            <option value="<?php echo $cat['id_categoria']; ?>"
                                <?php echo ($esEdicion && $cat['id_categoria'] == $producto['id_categoria']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="mensaje-error" id="error-id_categoria"><?= isset($errores['id_categoria']) ? htmlspecialchars($errores['id_categoria']) : '' ?></span>
                </div>

                <div class="campo">
                    <label for="precio">Precio (€) *</label>
                    <input type="number" id="precio" name="precio" step="0.01" placeholder="Ej: 79.90"
                           value="<?php echo $esEdicion ? htmlspecialchars($producto['precio']) : ''; ?>">
                    <span class="mensaje-error" id="error-precio"><?= isset($errores['precio']) ? htmlspecialchars($errores['precio']) : '' ?></span>
                </div>

                <div class="campo">
                    <label for="stock">Stock *</label>
                    <input type="number" id="stock" name="stock" placeholder="Ej: 5"
                           value="<?php echo $esEdicion ? htmlspecialchars($producto['stock']) : ''; ?>">
                    <span class="mensaje-error" id="error-stock"><?= isset($errores['stock']) ? htmlspecialchars($errores['stock']) : '' ?></span>
                </div>

                <div class="campo">
                    <label for="diametro">Diámetro (cm)</label>
                    <input type="number" id="diametro" name="diametro" placeholder="Ej: 18"
                           value="<?php echo $esEdicion ? htmlspecialchars($producto['diametro'] ?? '') : ''; ?>">
                    <span class="mensaje-error" id="error-diametro"><?= isset($errores['diametro']) ? htmlspecialchars($errores['diametro']) : '' ?></span>
                </div>

                <div class="campo">
                    <label for="peso">Peso (g) *</label>
                    <input type="number" id="peso" name="peso" placeholder="Ej: 850"
                           value="<?php echo $esEdicion ? htmlspecialchars($producto['peso'] ?? '') : ''; ?>">
                    <span class="mensaje-error" id="error-peso"><?= isset($errores['peso']) ? htmlspecialchars($errores['peso']) : '' ?></span>
                </div>

                <div class="campo">
                    <label for="nota"><?php echo $esEdicion ? "Melodía (opcional, deja vacío para mantener la actual)" : "Melodía"; ?></label>
                    <input type="file" id="nota" name="nota">
                </div>

                <div class="campo">
                    <label for="material">Material *</label>
                    <input type="text" id="material" name="material" placeholder="Ej: Aleación de metales"
                           value="<?php echo $esEdicion ? htmlspecialchars($producto['material'] ?? '') : ''; ?>">
                    <span class="mensaje-error" id="error-material"><?= isset($errores['material']) ? htmlspecialchars($errores['material']) : '' ?></span>
                </div>

                <div class="campo">
                    <label for="procedencia">Procedencia *</label>
                    <input type="text" id="procedencia" name="procedencia" placeholder="Ej: Nepal"
                           value="<?php echo $esEdicion ? htmlspecialchars($producto['procedencia'] ?? '') : ''; ?>">
                    <span class="mensaje-error" id="error-procedencia"><?= isset($errores['procedencia']) ? htmlspecialchars($errores['procedencia']) : '' ?></span>
                </div>

                <div class="campo ancho-completo">
                    <label for="descripcion">Descripción *</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Describe el producto, sus características, beneficios y usos..."><?php echo $esEdicion ? htmlspecialchars($producto['descripcion']) : ''; ?></textarea>
                    <span class="mensaje-error" id="error-descripcion"><?= isset($errores['descripcion']) ? htmlspecialchars($errores['descripcion']) : '' ?></span>
                </div>
            </div>

            <div class="bloque-imagen-form">
                <label for="imagen"><?php echo $esEdicion ? "Imagen del producto (opcional, deja vacío para mantener la actual)" : "Imagen del producto *"; ?></label>
                <div class="zona-subida">
                    <input type="file" id="imagen" name="imagen">
                    <p>▧</p>
                    <strong><?php echo $esEdicion ? "Subir nueva imagen" : "Subir imagen"; ?></strong>
                    <span>JPG, PNG o WEBP. Máx. 2MB</span>
                </div>
                <span class="mensaje-error" id="error-imagen"><?= isset($errores['imagen']) ? htmlspecialchars($errores['imagen']) : '' ?></span>
            </div>

            <div class="acciones-formulario">
                <a href="views/admin/productos/admin-listado-productos.php" class="boton cancelar">Cancelar</a>
                <button type="submit" class="boton principal"><?php echo $esEdicion ? "Guardar cambios" : "Guardar producto"; ?></button>
            </div>
        </form>

        <aside class="vista-previa-admin">
            <h3><?php echo $esEdicion ? "Imagen actual" : "Vista previa"; ?></h3>
            <article class="tarjeta-producto">
                <?php if ($esEdicion): ?>
                    <?php if (!empty($producto['imagen'])): ?>
                        <img src="img/productos/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                    <?php else: ?>
                        <img src="img/cuenco-12.svg" alt="Sin imagen">
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                    <p class="precio"><?php echo number_format($producto['precio'], 2, ',', '.'); ?> €</p>
                <?php else: ?>
                    <img src="img/cuenco-18.svg" alt="Vista previa cuenco">
                    <h3>Cuenco tibetano artesanal 18 cm</h3>
                    <p class="precio">79,90 €</p>
                    <p>Diámetro: 18 cm</p>
                    <p>Peso: 850 g</p>
                    <p>Procedencia: Nepal</p>
                <?php endif; ?>
            </article>
        </aside>
    </section>
</main>

<script>
const formProducto = document.querySelector('.formulario-admin');
if (formProducto) {
    const esEdicion = formProducto.dataset.esEdicion === '1';

    function marcarError(input, idSpan, mensaje) {
        input.classList.add('input-error');
        document.getElementById(idSpan).textContent = mensaje;
    }
    function limpiarError(input, idSpan) {
        input.classList.remove('input-error');
        document.getElementById(idSpan).textContent = '';
    }

    function validarTexto(idInput, idSpan, nombreCampo, min, max) {
        const input = document.getElementById(idInput);
        const valor = input.value.trim();
        if (valor === '') {
            marcarError(input, idSpan, `${nombreCampo} es obligatorio.`);
            return false;
        }
        if (valor.length < min || valor.length > max) {
            marcarError(input, idSpan, `${nombreCampo} debe tener entre ${min} y ${max} caracteres.`);
            return false;
        }
        limpiarError(input, idSpan);
        return true;
    }

    function validarNumero(idInput, idSpan, nombreCampo, min, max, obligatorio) {
        const input = document.getElementById(idInput);
        const valorStr = input.value.trim();
        if (valorStr === '') {
            if (obligatorio) {
                marcarError(input, idSpan, `${nombreCampo} es obligatorio.`);
                return false;
            }
            limpiarError(input, idSpan);
            return true;
        }
        const valor = parseFloat(valorStr);
        if (isNaN(valor) || valor <= min || valor > max) {
            marcarError(input, idSpan, `${nombreCampo} debe ser mayor que ${min} y no superar ${max}.`);
            return false;
        }
        limpiarError(input, idSpan);
        return true;
    }

    function validarCategoria() {
        const input = document.getElementById('id_categoria');
        if (input.value === '') {
            marcarError(input, 'error-id_categoria', 'Selecciona una categoría.');
            return false;
        }
        limpiarError(input, 'error-id_categoria');
        return true;
    }

    function validarImagen() {
        const input = document.getElementById('imagen');
        const span = document.getElementById('error-imagen');
        if (!esEdicion && input.files.length === 0) {
            span.textContent = 'La imagen es obligatoria.';
            return false;
        }
        span.textContent = '';
        return true;
    }

    function enganchar(idInput, evento, validador) {
        const input = document.getElementById(idInput);
        input.addEventListener(evento, validador);
        input.addEventListener('input', () => {
            if (input.classList.contains('input-error')) validador();
        });
    }

    enganchar('nombre', 'blur', () => validarTexto('nombre', 'error-nombre', 'El nombre', 3, 50));
    enganchar('id_categoria', 'change', validarCategoria);
    enganchar('precio', 'blur', () => validarNumero('precio', 'error-precio', 'El precio', 0, 2000, true));
    enganchar('stock', 'blur', () => validarNumero('stock', 'error-stock', 'El stock', 0, 10000, true));
    enganchar('peso', 'blur', () => validarNumero('peso', 'error-peso', 'El peso', 0, 10000, true));
    enganchar('diametro', 'blur', () => validarNumero('diametro', 'error-diametro', 'El diámetro', 0, 100, false));
    enganchar('material', 'blur', () => validarTexto('material', 'error-material', 'El material', 3, 50));
    enganchar('procedencia', 'blur', () => validarTexto('procedencia', 'error-procedencia', 'La procedencia', 3, 50));
    enganchar('descripcion', 'blur', () => validarTexto('descripcion', 'error-descripcion', 'La descripción', 15, 300));

    formProducto.addEventListener('submit', (e) => {
        const ok1 = validarTexto('nombre', 'error-nombre', 'El nombre', 3, 50);
        const ok2 = validarCategoria();
        const ok3 = validarNumero('precio', 'error-precio', 'El precio', 0, 2000, true);
        const ok4 = validarNumero('stock', 'error-stock', 'El stock', 0, 10000, true);
        const ok5 = validarNumero('peso', 'error-peso', 'El peso', 0, 10000, true);
        const ok6 = validarNumero('diametro', 'error-diametro', 'El diámetro', 0, 100, false);
        const ok7 = validarTexto('material', 'error-material', 'El material', 3, 50);
        const ok8 = validarTexto('procedencia', 'error-procedencia', 'La procedencia', 3, 50);
        const ok9 = validarTexto('descripcion', 'error-descripcion', 'La descripción', 15, 300);
        const ok10 = validarImagen();

        if (!ok1 || !ok2 || !ok3 || !ok4 || !ok5 || !ok6 || !ok7 || !ok8 || !ok9 || !ok10) {
            e.preventDefault();
        }
    });
}
</script>

<?php include __DIR__ . "/../../../includes/footer-simple.php"; ?>