<?php
$titulo = "Sonido Interior | Recuperar Contraseña";
$pagina = "recuperar";

include __DIR__ . '/../../includes/header.php';
include __DIR__ . "/../../includes/menu-login.php";
?>

<main class="contenedor">
    <div class="encabezado-pagina">
        <h2>Recuperar Contraseña</h2>
        <div class="linea-adorno-centro"></div>
        <p>Introduce tu correo electrónico y te enviaremos las instrucciones.</p>
    </div>

    <div style="max-width: 450px; margin: 0 auto;">
        <?php if (isset($_SESSION['recuperacion_mensaje'])): ?>
            <p style="text-align: center; color: #356b2f; font-weight: bold; margin-bottom: 20px;">
                <?= htmlspecialchars($_SESSION['recuperacion_mensaje']); ?>
            </p>
            <?php unset($_SESSION['recuperacion_mensaje']); ?>
        <?php endif; ?>

        <section class="tarjeta-beneficio">
            <form action="controllers/auth/solicitar-recuperacion.php" method="POST">
                <div class="campo-form">
                    <label for="email">Correo electrónico *</label>
                    <input type="email" id="email" name="email" placeholder="tu@email.com" required>
                </div>

                <button type="submit" class="boton principal bloque">Enviar enlace</button>
            </form>
        </section>
    </div>
</main>

<?php include __DIR__ . '/../../includes/footer.php'; ?>