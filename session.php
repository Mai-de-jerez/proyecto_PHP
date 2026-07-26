<?php
session_start();

echo "<pre>";
echo "<strong>ID de Sesión Actual:</strong> " . session_id() . "\n\n";

echo "<strong>Variables guardadas en \$_SESSION:</strong>\n";
print_r($_SESSION);
echo "</pre>";
?>