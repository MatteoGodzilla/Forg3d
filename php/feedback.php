<?php
    function feedback($baseUrl,$type,$message,$notFirstParam = FALSE){
        if($notFirstParam){
            return  $baseUrl."&message=".urlencode($message)."&messageType=".$type;
        }
        return $baseUrl."?message=".urlencode($message)."&messageType=".$type;
    }
?>