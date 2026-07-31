<?php
session_start();

echo "<pre>";
echo "<strong>ID de Sesión Actual:</strong> " . session_id() . "\n\n";

echo "<strong>Variables guardadas en \$_SESSION:</strong>\n";
print_r($_SESSION);

echo "\n--------------------------------------------------\n";
$totalArticulos = $_SESSION['cantidades_carrito'] ?? 0;
echo "<strong>TOTAL DE ARTÍCULOS EN EL CARRO:</strong> " . $totalArticulos . "\n";
echo "--------------------------------------------------\n";
echo "</pre>";
?>