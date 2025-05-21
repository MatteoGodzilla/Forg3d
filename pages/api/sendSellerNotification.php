<?php 

//public
function sendAddedProduct($connection, string $sellerEmail, string $product_name){
    sendSellerNotification($connection, $sellerEmail, "Aggiunta", "Aggiunto: ".$product_name);
}

//public
function sendModifiedProduct($connection, string $sellerEmail, string $product_name){
    sendSellerNotification($connection, $sellerEmail, "Modifica", "Modificato: ".$product_name);
}

//public
function sendRemovedProduct($connection, string $sellerEmail){
    sendSellerNotification($connection, $sellerEmail, "Rimozione",  "Rimosso un prodotto");
}

//private 
function sendSellerNotification($connection, string $sellerEmail, string $title, string $description){
    $query_add_notification = "INSERT INTO Notifica(titolo, descrizione, emailVenditore) VALUES (?,?,?)";
    $stmt = mysqli_prepare($connection, $query_add_notification);
    mysqli_stmt_bind_param($stmt, "sss", $title, $description, $sellerEmail);
    mysqli_stmt_execute($stmt);
}

?>
