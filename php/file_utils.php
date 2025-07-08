<?php
    require_once("constants.php");
    function get_folder($ext){
        if(in_array($ext,Constants::$ALLOWED_IMAGE_EXTENSIONS)){
            return $_SERVER['DOCUMENT_ROOT']."/files/images/";
        }
        else if(in_array($ext,Constants::$ALLOWED_3DFILE_EXTENSIONS)){
            return $_SERVER['DOCUMENT_ROOT']."/files/models/";
        }
        return $_SERVER['DOCUMENT_ROOT']."/files/other";
    }

    #returns the file path where the file is stored
    function store_file($name,$tmp, $validExtensions){
        $fileExtension = strtolower(pathinfo($name,PATHINFO_EXTENSION));
        if(in_array($fileExtension, $validExtensions)){
            $fileFolder = get_folder($fileExtension);
            $fileName = bin2hex(random_bytes(32));
            $fullName = $fileFolder.$fileName.".".$fileExtension;
            if(move_uploaded_file($tmp, $fullName)){
                return str_replace($_SERVER['DOCUMENT_ROOT'],"",$fullName);
            }
        }
        return "";
    }
?>
