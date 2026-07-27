<?php
$titulo = "Sonido Interior | Nueva Contraseña";
$pagina = "restablecer";

include __DIR__ . '/../../includes/header.php';
include __DIR__ . "/../../includes/menu-login.php";

$token = $_GET['token'] ?? '';
?>

<main class="contenedor">
    <div class="encabezado-pagina">
        <h2>Crear Nueva Contraseña</h2>
        <div class="linea-adorno-centro"></div>
    </div>

    <div style="max-width: 450px; margin: 0 auto;">
        <?php if (isset($_SESSION['error_reset'])): ?>
            <p style="text-align: center; color: #b03030; font-weight: bold; margin-bottom: 20px;">
                <?= htmlspecialchars($_SESSION['error_reset']); ?>
            </p>
            <?php unset($_SESSION['error_reset']); ?>
        <?php endif; ?>

        <?php if (empty($token)): ?>
            <p style="text-align: center; color: #b03030;">El enlace de recuperación no es válido.</p>
        <?php else: ?>
            <section class="tarjeta-beneficio">
                <form action="controllers/auth/procesar-nueva-password.php" method="POST">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token); ?>">

                    <div class="campo-form">
                        <label for="password">Nueva contraseña *</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="campo-form">
                        <label for="confirm_password">Confirmar nueva contraseña *</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>

                    <button type="submit" class="boton principal bloque">Guardar nueva contraseña</button>
                </form>
            </section>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/../../includes/footer.php'; ?>