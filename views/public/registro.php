<?php
$titulo = "Crear cuenta | Sonido Interior";
$bodyClass = "fondo-login";
include __DIR__ . "/../../includes/header.php";
include __DIR__ . "/../../includes/menu-login.php";
?>

<main class="registro-contenedor">
    <section class="registro-card">
        <div class="simbolo-registro">☯</div>
        <h2>Sonido Interior</h2>
        <p class="subtitulo-registro">Crear cuenta</p>

        <h3>Únete a Sonido Interior</h3>
        <p>Rellena tus datos para crear tu cuenta</p>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
            <p style="color: #b03030; text-align: center;">Ha habido un problema al crear la cuenta. Revisa los datos e inténtalo de nuevo.</p>
        <?php endif; ?>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'existe'): ?>
            <p style="color: #b03030; text-align: center;">Ese usuario o email ya está registrado.</p>
        <?php endif; ?>

        <form class="formulario-registro" action="controllers/auth/procesar-registro.php" method="post">           

            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario" placeholder="Elige un nombre de usuario">

             <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Introduce tu email">

            <div class="agrupacion-passwords">
                <div>
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Elige una contraseña">
                </div>                

                <div>
                    <label for="password2">Repite la contraseña</label>
                    <input type="password" id="password2" name="password2" placeholder="Repite la contraseña">
                </div>
            </div>
            

            <button type="submit" class="boton principal bloque">Crear cuenta</button>
        </form>
    </section>
</main>

<?php include __DIR__ . "/../../includes/footer.php"; ?>