<?php
// Con esto compruebo que la sesión esté iniciada antes de usar $_SESSION, 
// para evitar errores si se incluye este archivo en páginas donde no se haya iniciado la sesión.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($titulo)) {
    $titulo = "Sonido Interior | Cuencos Tibetanos";
}
if (!isset($bodyClass)) {
    $bodyClass = "";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title>
    <base href="/sonido-interior/">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="<?php echo $bodyClass; ?>">
