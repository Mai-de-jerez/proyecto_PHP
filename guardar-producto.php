<?php
require "./funciones/funciones.php";
require "includes/conexion.php"; 

// Verificamos que los datos vengan por POST y que al menos el nombre esté relleno
//isset -> si esta instanciada?  - unset .> la deisntancia  
//empty -> esta vacia?
if (isset($_POST) && !empty($_POST["nombre"])) {

    $nombre = $_POST["nombre"];
    $categoria = $_POST["id_categoria"];
    $precio = $_POST["precio"];
    $stock = $_POST["stock"];
    $diametro = $_POST["diametro"];
    $peso = $_POST["peso"];
    $material = $_POST["material"];
    $procedencia = $_POST["procedencia"];
    $descripcion = $_POST["descripcion"];

    // Procesamos la foto y la melodía (subida al servidor y rutas)
    $imagen = subirFoto($_FILES['imagen'], $nombre, 10000000);
    $nota = subirMP3($_FILES["nota"], $nombre);

    // Modo PRO Seria con POO y MVC *

    // Modo estructurado (Scripting)
    
    /*
        contectarme
        crear la query
        ejecutarla y lo guardara en la base de datos
    */ 
    $sql = "INSERT INTO productos (nombre, id_categoria, precio, stock, diametro, peso, material, procedencia, descripcion, imagen, nota_musical) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
    $statement = mysqli_prepare($conexion, $sql);

    mysqli_stmt_bind_param(
        $statement,
        "sidiidsssss", 
        $nombre,
        $categoria,    
        $precio,
        $stock,
        $diametro,
        $peso,
        $material,
        $procedencia,
        $descripcion,
        $imagen,        
        $nota         
    );
    // Ejecutamos la inserción estructurada
    if (mysqli_stmt_execute($statement)) {
        // Cierre de flujos estructurados antes de redirigir
        mysqli_stmt_close($statement);
        mysqli_close($conexion);

        // Si ha funcionado, mandamos al usuario al listado con un aviso de éxito
        header("Location: admin-listado-productos.php?status=success");
        exit();
    } else {
        mysqli_stmt_close($statement);
        mysqli_close($conexion);

        // Si falla la base de datos, volvemos al alta mostrando el fallo
        header("Location: admin-alta-producto.php?status=error");
        exit();
    }

} else {
    // Si intentan entrar de forma directa al archivo sin pasar por el formulario, denegamos el paso
    header("Location: admin-alta-producto.php");
    exit();
}
?>