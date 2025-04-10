<?php
require_once("../php/db.php"); // Connessione al database
require_once("../php/constants.php"); // Costanti per il tipo di utente

// Verifica che l'utente sia loggato
session_start();
if (!isset($_SESSION[$ID_UTENTE])) {
    header("Location:");
    exit();
}

// Verifica che il form sia stato inviato
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Controlla che i campi necessari siano presenti
    if (isset($_POST['recensione'], $_POST['idProdotto'])) {
        $emailUtente = $_SESSION[$ID_UTENTE];
        $idProdotto = $_POST['idProdotto'];
        $recensione = $_POST['recensione'];

        // Query per inserire la recensione nel database
        $query = "INSERT INTO Recensione (email, testo, dataCreazione, inRispostaA) 
                  VALUES (?, ?, NOW(), NULL)";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "ss", $emailUtente, $recensione);
        if (mysqli_stmt_execute($stmt)) {
            echo "Recensione salvata con successo!";
            header("Location: prodotto.php?id=$idProdotto");
        } else {
            echo "Errore nel salvataggio della recensione.";
        }
    } else {
        echo "Dati mancanti!";
    }
}
?>
