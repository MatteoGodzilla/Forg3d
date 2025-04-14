<?php
    function get_folder($file){
        include 'constants.php';
        $tipo = strtolower(pathinfo($file,PATHINFO_EXTENSION));
        $folder = $_SERVER['DOCUMENT_ROOT'];
        if(in_array($tipo,$ALLOWED_IMAGE_EXTENSIONS)){
            return $folder."immagini/";
        }
        else if(in_array($tipo,$ALLOWED_3DFILE_EXTENSIONS)){
            return $folder."modelli/";
        }
        return $folder."/altro";
    }

    function isValidExtension($file){
        return in_array($tipo,$ALLOWED_IMAGE_EXTENSIONS) | in_array($tipo,$ALLOWED_3DFILE_EXTENSIONS);
    }

    #returns the file path where the file is stored
    function store_file($file){
        if(isValidExtension($file)){
            $file_extension = strtolower(pathinfo($file,PATHINFO_EXTENSION));
            $file_name = $cartella.bin2hex(random_bytes(32));
            $file_folder = get_folder($file);
            $full_name = $file_folder.$file_name.$file_extension;
            move_uploaded_file($file, $full_name);
            return $full_name;
        }
        return "failure";
    }
?>

