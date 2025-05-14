<?php
    function error($baseUrl,$error,$notFirstParam = FALSE){
        if($notFirstParam){
            return $baseUrl."&error=".urlencode($error);
        }
        return $baseUrl."?error=".urlencode($error);
    }
?>