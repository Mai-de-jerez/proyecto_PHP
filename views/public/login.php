<?php
$titulo = "Login Administración | Sonido Interior";
$bodyClass = "fondo-login";
include __DIR__ . "/../../includes/header.php";
include __DIR__ . "/../../includes/menu-login.php";
?>

<main class="login-contenedor">
    <section class="login-card">
        <div class="simbolo-login">☯</div>
        <h2>Sonido Interior</h2>
        <p class="subtitulo-login">Administración</p>

        <h3>Acceso al panel de administración</h3>
        <p>Introduce tus credenciales para continuar</p>
        
        <form class="formulario-login" action="controllers/auth/procesar-login.php" method="post">
            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario" placeholder="Introduce tu usuario">

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Introduce tu contraseña">

            <div class="opciones-login">
                <label class="checkbox"><input type="checkbox" name="recordarme"> Recordarme</label>
            </div>

            <button type="submit" class="boton principal bloque">Entrar</button>

            <div class="opciones-login">
                <a href="views/public/registro.php">¿No tienes una cuenta? Crea aquí una</a>
                <a href="#">¿Has olvidado tu contraseña?</a>
            </div>
        </form>
    </section>
</main>

<?php include __DIR__ . "/../../includes/footer.php"; ?>
