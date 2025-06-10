<?php 

function sendAddedProduct($connection, string $sellerEmail, string $product_name){
    sendSellerNotification($connection, $sellerEmail, "Aggiunta", "Aggiunto: ".$product_name);
}

function sendModifiedProduct($connection, string $sellerEmail, string $product_name){
    sendSellerNotification($connection, $sellerEmail, "Modifica", "Modificato: ".$product_name);
}

function sendRemovedProduct($connection, string $sellerEmail){
    sendSellerNotification($connection, $sellerEmail, "Rimozione",  "Rimosso un prodotto");
}


function sendSellerNotification($connection, string $sellerEmail, string $title, string $description){
    $query_add_notification = "INSERT INTO Notifica(titolo, descrizione, emailMittente) VALUES (?,?,?)";
    $stmt = mysqli_prepare($connection, $query_add_notification);
    mysqli_stmt_bind_param($stmt, "sss", $title, $description, $sellerEmail);
    mysqli_stmt_execute($stmt);
}

function sendSellerNotificationSpecific($connection, string $sellerEmail, string $title, string $description, string $buyerEmail){
    $query_add_notification = "INSERT INTO Notifica(titolo, descrizione, emailMittente, emailDestinatario) VALUES (?,?,?,?)";
    $stmt = mysqli_prepare($connection, $query_add_notification);
    mysqli_stmt_bind_param($stmt, "ssss", $title, $description, $sellerEmail,$buyerEmail);
    mysqli_stmt_execute($stmt);
}


function sendAdminNotification($connection, string $title, string $description){
    $query_add_notification = "INSERT INTO Notifica(titolo, descrizione) VALUES (?,?)";
    $stmt = mysqli_prepare($connection, $query_add_notification);
    mysqli_stmt_bind_param($stmt, "ss", $title, $description);
    mysqli_stmt_execute($stmt);
}

function sendAdminNotificationSpecific($connection, string $title, string $description,string $Buyer){
    $query_add_notification = "INSERT INTO Notifica(titolo, descrizione, emailDestinatario) VALUES (?,?,?)";
    $stmt = mysqli_prepare($connection, $query_add_notification);
    mysqli_stmt_bind_param($stmt, "sss", $title, $description,$Buyer);
    mysqli_stmt_execute($stmt);
}


?>
