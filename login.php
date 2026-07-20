<?php
$titulo = "Login Administración | Sonido Interior";
$bodyClass = "fondo-login";
include("includes/header.php");
include("includes/menu-login.php");
?>

<main class="login-contenedor">
    <section class="login-card">
        <div class="simbolo-login">☯</div>
        <h2>Sonido Interior</h2>
        <p class="subtitulo-login">Administración</p>

        <h3>Acceso al panel de administración</h3>
        <p>Introduce tus credenciales para continuar</p>

        <form class="formulario-login" action="#" method="post">
            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario" placeholder="Introduce tu usuario">

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Introduce tu contraseña">

            <div class="opciones-login">
                <label class="checkbox"><input type="checkbox" name="recordarme"> Recordarme</label>
                <a href="#">¿Has olvidado tu contraseña?</a>
            </div>

            <button type="submit" class="boton principal bloque">Entrar</button>
        </form>
    </section>
</main>

<?php include("includes/footer.php"); ?>
