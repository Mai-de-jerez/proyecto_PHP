<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../models/usuarios.php';
require_once __DIR__ . '/../../models/auth.php';
require_once __DIR__ . '/../../includes/conexion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email'])) {
    $email = trim($_POST['email']);

    // Comprobamos si el email existe en la tabla usuarios
    $usuario = obtenerUsuarioPorEmail($conexion, $email); 

    if ($usuario) {
        // Generamos token de 64 caracteres criptográficamente seguro
        $token = bin2hex(random_bytes(32));

        if (guardarTokenRecuperacion($conexion, $email, $token)) {
            // Enviamos el correo con el enlace
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host     = 'localhost';
                $mail->Port     = 1025;
                $mail->CharSet  = 'UTF-8';

                $mail->setFrom('web@sonidointerior.com', 'Sonido Interior');
                $mail->addAddress($email, $usuario['usuario'] ?? 'Usuario');

                $enlace = "http://localhost/sonido-interior/views/public/restablecer-password.php?token=" . $token;

                $mail->isHTML(true);
                $mail->Subject = "Restablece tu contraseña - Sonido Interior";
                $mail->Body    = "
                    <p>Hola,</p>
                    <p>Has solicitado restablecer tu contraseña. Haz clic en el siguiente enlace para crear una nueva:</p>
                    <p><a href='{$enlace}'>Restablecer mi contraseña</a></p>
                    <p>Este enlace caducará en 30 minutos. Si no has sido tú, ignora este mensaje.</p>
                ";

                $mail->send();
            } catch (Exception $e) {
                error_log("Error al enviar email de recuperación: " . $e->getMessage());
            }
        }
    }

    // SIEMPRE mostramos el mismo mensaje, independientemente de si el email existía o no
    $_SESSION['recuperacion_mensaje'] = "Si el correo introducido está registrado, recibirás las instrucciones en tu bandeja de entrada.";
    header("Location: ../../views/public/recuperar-password.php");
    exit();
}