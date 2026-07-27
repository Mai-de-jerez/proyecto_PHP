<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../models/mensajes.php';
require_once __DIR__ . '/../../includes/conexion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
if (
    isset($_POST) &&
    !empty($_POST["nombre"]) &&
    !empty($_POST["email"]) &&
    !empty($_POST["mensaje"])
) {
 
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $telefono = !empty($_POST["telefono"]) ? trim($_POST["telefono"]) : null;
    $motivo = !empty($_POST["asunto"]) ? $_POST["asunto"] : null;
    $mensaje = $_POST["mensaje"];
 
    // 1. Guardamos el mensaje en la base de datos
    $guardado = guardarMensaje($conexion, $nombre, $email, $telefono, $motivo, $mensaje);
    mysqli_close($conexion);
 
    if (!$guardado) {
        $_SESSION['contacto_status'] = 'error';
        header("Location: ../../views/public/contacto.php");
        exit();
    }
 
    // 2. Enviamos el email usando PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración para Mailpit en XAMPP local
        $mail->isSMTP();
        $mail->Host       = 'localhost'; 
        $mail->Port       = 1025;        
        $mail->SMTPAuth   = false;       

        // Codificación de caracteres para acentos y eñes
        $mail->CharSet    = 'UTF-8';

        // Remitente y Destinatario
        $mail->setFrom('web@sonidointerior.com', 'Sonido Interior Web');
        $mail->addAddress('hola@sonidointerior.com', 'Atención al Cliente');
        $mail->addReplyTo($email, $nombre); // Responder directamente al cliente

        // Contenido en HTML limpio
        $mail->isHTML(true);
        $mail->Subject = "Nuevo mensaje de contacto" . ($motivo ? ": " . $motivo : "");

        $mail->Body = "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <style>
                    body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; color: #333; }
                    .container { max-width: 600px; background: #ffffff; padding: 25px; border-radius: 8px; border: 1px solid #ddd; }
                    h2 { color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-top: 0; }
                    .info { margin-bottom: 15px; font-size: 14px; }
                    .info strong { color: #555; }
                    .mensaje-box { background: #f9f9f9; padding: 15px; border-left: 4px solid #356b2f; margin-top: 15px; font-style: italic; white-space: pre-line; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h2>Nuevo mensaje desde la Web</h2>
                    <div class='info'><strong>Nombre:</strong> " . htmlspecialchars($nombre) . "</div>
                    <div class='info'><strong>Email:</strong> " . htmlspecialchars($email) . "</div>
                    <div class='info'><strong>Teléfono:</strong> " . htmlspecialchars($telefono ?? '(No proporcionado)') . "</div>
                    <div class='info'><strong>Asunto:</strong> " . htmlspecialchars($motivo ?? '(Sin asunto)') . "</div>
                    <div class='mensaje-box'>" . htmlspecialchars($mensaje) . "</div>
                </div>
            </body>
            </html>
        ";

        // Versión en texto plano por si el gestor de correo no soporta HTML
        $mail->AltBody = "Nuevo mensaje de contacto:\n\n" .
                         "Nombre: $nombre\n" .
                         "Email: $email\n" .
                         "Teléfono: " . ($telefono ?? '(No proporcionado)') . "\n" .
                         "Asunto: " . ($motivo ?? '(Sin asunto)') . "\n\n" .
                         "Mensaje:\n$mensaje";

        $mail->send();

    } catch (Exception $e) {
        // En caso de fallo con el servidor SMTP, guardamos el log para depurar
        error_log("Error PHPMailer: {$mail->ErrorInfo}");
    }

    $_SESSION['contacto_status'] = 'success';
    header("Location: ../../views/public/contacto.php");
    exit();
 
} else {

    $_SESSION['contacto_status'] = 'error';
    header("Location: ../../views/public/contacto.php");
    exit();
}
?>