<?php
session_start();
require_once __DIR__ . '/../../models/auth.php';
require_once __DIR__ . '/../../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['token']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])) {
    
    $token           = $_POST['token'];
    $password        = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // 1. Validar coincidencia de contraseñas
    if ($password !== $confirmPassword) {
        $_SESSION['error_reset'] = "Las contraseñas no coinciden.";
        header("Location: ../../views/public/restablecer-password.php?token=" . urlencode($token));
        exit();
    }

    // 2. Comprobar que el token exista y no haya caducado
    $email = obtenerEmailPorToken($conexion, $token);

    if (!$email) {
        $_SESSION['error_reset'] = "El enlace ha caducado o es inválido. Solicita uno nuevo.";
        header("Location: ../../views/public/recuperar-password.php");
        exit();
    }

    // 3. Hashear la nueva contraseña
    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

    // 4. Actualizar en BD y eliminar token
    if (actualizarPasswordYBorrarToken($conexion, $email, $hashPassword)) {
        $_SESSION['login_mensaje'] = "¡Contraseña cambiada con éxito! Ya puedes acceder.";
        header("Location: ../../views/public/login.php");
        exit();
    } else {
        $_SESSION['error_reset'] = "Error al actualizar la contraseña. Inténtalo de nuevo.";
        header("Location: ../../views/public/restablecer-password.php?token=" . urlencode($token));
        exit();
    }
} else {
    header("Location: ../../views/public/recuperar-password.php");
    exit();
}