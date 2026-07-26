<?php
session_start();
require_once __DIR__ . '/../../models/usuarios.php';
require_once __DIR__ . '/../../includes/conexion.php';


if (isset($_POST) && !empty($_POST["usuario"]) && !empty($_POST["password"])) {

    // obtenemos los datos del formulario
    $usuario = $_POST["usuario"];
    $password = $_POST["password"];

    // usamos mi consulta para obtener el usuario por su nombre de usuario
    $usuarioEncontrado = obtenerUsuarioPorUsername($conexion, $usuario);


    // usamos password_verify para comparar la contraseña ingresada con la almacenada en la base de datos
    if ($usuarioEncontrado && password_verify($password, $usuarioEncontrado["password"])) {

    session_regenerate_id(true); // Regeneramos el ID de sesión para evitar el ataque de fijación de sesión

    $_SESSION["id_usuario"] = $usuarioEncontrado["id_usuario"];
    $_SESSION["usuario"] = $usuarioEncontrado["usuario"];
    $_SESSION["rol"] = $usuarioEncontrado["rol"];

        // Según el rol, mandamos a un sitio o a otro
        if ($usuarioEncontrado["rol"] === "ADMIN") {
            header("Location: ../../views/admin/dashboard.php");
        } else {
            header("Location: ../../views/public/index.php");
        }
        exit();

    } else {
        header("Location: ../../views/public/login.php?status=error");
        exit();
    }


} else {
    // Si intentan entrar de forma directa al archivo sin pasar por el formulario, denegamos el paso
    header("Location: ../../views/public/login.php");
    exit();
}
           
?>