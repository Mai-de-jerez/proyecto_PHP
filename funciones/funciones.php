<?php

function ver(mixed $dato){

    echo "<pre>";
    print_r($dato);
    echo "</pre>";

}

function subirFoto(array $foto, $nombreProducto = "", $pesoMaximo = 5000000) {

    // 1. Si la subida dio error en el servidor, frenamos
    if (!isset($foto["error"]) || $foto["error"] !== UPLOAD_ERR_OK) {
        return false;
    }

    // 2. Comprobar peso
    if ($foto["size"] > $pesoMaximo) {
        return false;
    }

    // 3. Obtener el tipo real del archivo con finfo 
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $tipoReal = $finfo->file($foto["tmp_name"]);

    $extensiones = [
        "image/png"  => ".png",
        "image/jpeg" => ".jpg",
        "image/webp" => ".webp"
    ];

    // Si el tipo no coincide con la lista, salimos
    if (!isset($extensiones[$tipoReal])) {
        return false;
    }

    $extension = $extensiones[$tipoReal];

    // 4. Nombre base (manteniendo tus funciones limpiar y cortar)
    if ($nombreProducto != "") {
        $nombreArchivo = limpiar_caracteres_especiales($nombreProducto);
    } else {
        $nombreArchivo = limpiar_caracteres_especiales($foto["name"]);
        $nombreArchivo = cortarCadenaFinal($nombreArchivo, "."); 
    }  

    // Si la limpieza deja la cadena vacía, asignamos un nombre por defecto
    if (empty($nombreArchivo)) {
        $nombreArchivo = "producto";
    }

    // 5. Gestión de nombre único si ya existe
    $directorioDestino = __DIR__ . "/../img/productos/";
    $nombreFinal = $nombreArchivo . $extension;

    if (file_exists($directorioDestino . $nombreFinal)) {
        $random = time();
        $nombreFinal = $nombreArchivo . $random . $extension; 
    }

    // 6. Mover archivo
    if (!move_uploaded_file($foto["tmp_name"], $directorioDestino . $nombreFinal)) {
        return false;
    }

    // Retorna siempre el nombre final si la subida fue correcta
    return $nombreFinal;
}


function subirMP3(array $nota, $nombreProducto = "", $pesoMaximo = 5000000) {
 
    // 1. Si no se ha subido ningún archivo, no es un error: el campo es opcional
    if (!isset($nota["error"]) || $nota["error"] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
 
    // 2. Si la subida dio error en el servidor, frenamos
    if ($nota["error"] !== UPLOAD_ERR_OK) {
        return false;
    }
 
    // 3. Comprobar peso
    if ($nota["size"] > $pesoMaximo) {
        return false;
    }
 
    // 4. Obtener el tipo real del archivo con finfo 
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $tipoReal = $finfo->file($nota["tmp_name"]);
 
    if ($tipoReal !== "audio/mpeg") {
        return false;
    }
 
    $extension = ".mp3";
 
    // 5. Nombre base 
    if ($nombreProducto != "") {
        $nombreArchivo = limpiar_caracteres_especiales($nombreProducto);
    } else {
        $nombreArchivo = limpiar_caracteres_especiales($nota["name"]);
        $nombreArchivo = cortarCadenaFinal($nombreArchivo, ".");
    }
 
    if (empty($nombreArchivo)) {
        $nombreArchivo = "producto";
    }
 
    // 6. Gestión de nombre único si ya existe
    $directorioDestino = __DIR__ . "/../sonidos/";
    $nombreFinal = $nombreArchivo . $extension;
 
    if (file_exists($directorioDestino . $nombreFinal)) {
        $random = time();
        $nombreFinal = $nombreArchivo . $random . $extension;
    }
 
    // 7. Mover archivo
    if (!move_uploaded_file($nota["tmp_name"], $directorioDestino . $nombreFinal)) {
        return false;
    }
 
    return $nombreFinal;
}

function limpiar_caracteres_especiales(string $cadena) {

    //preg_replace($patrones, $sustituciones, $cadena);
    //$cadena =  preg_replace("/[^a-zA-Z0-9\_\-]+/", "",$cadena);


    //IMPORTANTE
    //$cadena = mb_convert_encoding($cadena, 'ISO-8859-1', 'UTF-8');

    $cadena = str_replace(
        array('?', '¿'),
        array('_', '_'),
        $cadena
    );
    $cadena = str_replace(
        array(' '),
        array('_'),
        $cadena
    );
    $cadena = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $cadena
    );

    $cadena = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $cadena );

    $cadena = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $cadena );

    $cadena = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $cadena );

    $cadena = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $cadena );

    $cadena = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C'),
        $cadena
    );
    
    return $cadena;
} 

function cortarCadenaFinal(string $cadena, $caracter = "."){
        // localicamos en que posición se haya la $subcadena, en nuestro caso la posicion es "7"
        $posicionsubcadena = strrpos ($cadena, $caracter);
        // eliminamos los caracteres desde $subcadena hacia la izq, y le sumamos 1 para borrar tambien el @ en este caso
        $nombre = substr ($cadena, 0, ($posicionsubcadena));
        return $nombre;
    }
?>