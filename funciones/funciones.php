<?php

function ver($dato){

    echo "<pre>";
    print_r($dato);
    echo "</pre>";

}


function subirFoto($foto, $nombreProducto = "", $pesoMaximo = 5000000 ){
 
    if($nombreProducto != ""){
            $nombreArchivo = limpiar_caracteres_especiales($nombreProducto);
    }else{
           $nombreArchivo = limpiar_caracteres_especiales($foto["name"]);
           $nombreArchivo = cortarCadenaFinal($nombreArchivo, "."); 
    }
   //    


    // nombrearchivo.png
    // nombrearchivo_3443434.png
    //nombrearchivo_3443434_34343.png
    if(strpos($foto["type"],"png") || strpos($foto["type"],"jpeg") || strpos($foto["type"],"webp") && $foto["size"] <= $pesoMaximo ){

        if(strpos($foto["type"],"png")){
             $extension = ".png";
        }else if(strpos($foto["type"],"jpeg")){
              $extension = ".jpg";
        }else{
            $extension = ".webp";
        }

        $nombreFinal = $nombreArchivo.$extension;
        if(file_exists("./cuencos/".$nombreFinal)){
            //esto es si existe
            $ramdom = time();
            $nombreFinal = $nombreArchivo.$ramdom.$extension;
        }

        if(!move_uploaded_file($foto["tmp_name"], "./cuencos/".$nombreFinal)){
            echo "Error del servidor";
        }else{
            // esto es la victoria
            return $nombreFinal;
        }

    }else{

        echo "La foto debe ser jpg, png o webp";
    }

}

function subirMP3($nota, $nombreProducto = "", $pesoMaximo = 5000000 ){
 
    if($nombreProducto != ""){
            $nombreArchivo = limpiar_caracteres_especiales($nombreProducto);
    }else{
           $nombreArchivo = limpiar_caracteres_especiales($nota["name"]);
           $nombreArchivo = cortarCadenaFinal($nombreArchivo, "."); 
    }

    if((strpos($nota["type"],"mp3") || strpos($nota["type"],"mpeg")) && $nota["size"] <= $pesoMaximo ){

        $extension =  ".mp3";
        $nombreFinal = $nombreArchivo.$extension;
        if(file_exists("./sonidos/".$nombreFinal)){
            //esto es si existe
            $ramdom = time();
            $nombreFinal = $nombreArchivo.$ramdom.$extension;
        }

        if(!move_uploaded_file($nota["tmp_name"], "./sonidos/".$nombreFinal)){
            echo "Error del servidor";
        }else{
            // esto es la victoria
            return $nombreFinal;
        }

    }else{
        echo "El archivo de sonido debe ser un MP3 válido y no superar el tamaño máximo.";
    }

}

function limpiar_caracteres_especiales($cadena) {

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
//para ampliar los caracteres a reemplazar agregar lineas de este tipo:
//$archivo = str_replace("caracter-que-queremos-cambiar","caracter-por-el-cual-lo-vamos-a-cambiar",$archivo);
    return $cadena;
}


function cortarCadenaFinal($cadena, $caracter = "."){

// localicamos en que posición se haya la $subcadena, en nuestro caso la posicion es "7"
    $posicionsubcadena = strrpos ($cadena, $caracter);
// eliminamos los caracteres desde $subcadena hacia la izq, y le sumamos 1 para borrar tambien el @ en este caso
    $nombre = substr ($cadena, 0, ($posicionsubcadena));
    return $nombre;

}

?>