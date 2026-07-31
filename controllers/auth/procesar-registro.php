<?php
require_once __DIR__ . '/../../models/usuarios.php';
require_once __DIR__ . '/../../includes/conexion.php';

if (
    isset($_POST) &&
    !empty($_POST["email"]) &&
    !empty($_POST["usuario"]) &&
    !empty($_POST["password"]) &&
    !empty($_POST["password2"])
) {

    $email = $_POST["email"];
    $usuario = $_POST["usuario"];
    $password = $_POST["password"];
    $password2 = $_POST["password2"];

    // Las dos contraseñas tienen que coincidir antes de seguir
    if ($password !== $password2) {
        header("Location: ../../views/public/registro.php?status=error");
        exit();
    }

    // Comprobamos que no exista ya ese usuario
    $usuarioExistente = obtenerUsuarioPorUsername($conexion, $usuario);

    if ($usuarioExistente) {
        mysqli_close($conexion);
        header("Location: ../../views/public/registro.php?status=existe");
        exit();
    }

    // Hasheamos la contraseña antes de guardarla en la base de datos
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $creado = registroUsuario($conexion, $usuario, $email, $passwordHash);
    mysqli_close($conexion);

    if ($creado) {
        header("Location: ../../views/public/login.php?status=registrado");
        exit();
    } else {
        header("Location: ../../views/public/registro.php?status=error");
        exit();
    }

} else {
    header("Location: ../../views/public/registro.php");
    exit();
}
?>