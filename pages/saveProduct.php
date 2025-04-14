<?php
session_start();
require_once("session_utils.php");
require_once("file_utils.php");
require_once("db.php");
require_once("constants.php");

if (!utenteLoggato() || $_SESSION[$TIPO_UTENTE] !== TipoUtente::VENDITORE->value) {
    die("Accesso negato.");
}

if (!isset($_POST['id'])) {
    die("ID prodotto mancante.");
}

$id = $_POST['id'];
$email = getSessionEmail();

// Verifica che sia suo il prodotto
$query = "SELECT * FROM Prodotto WHERE id = ? AND emailVenditore = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "is", $id, $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result->num_rows === 0) {
    die("Prodotto non trovato o accesso non autorizzato.");
}

// Aggiorna nome e visibilità
$nome = $_POST['nome'] ?? '';
$visibile = isset($_POST['visibile']) ? 1 : 0;

$query_update = "UPDATE Prodotto SET nome = ?, visibile = ? WHERE id = ?";
$stmt = mysqli_prepare($connection, $query_update);
mysqli_stmt_bind_param($stmt, "sii", $nome, $visibile, $id);
mysqli_stmt_execute($stmt);


//-------DA RIVEDERE ---------


// Upload nuovo file 3D se esiste
if (isset($_FILES['modello3d']) && $_FILES['modello3d']['error'] === UPLOAD_ERR_OK) {
    $percorso3D = store_file($_FILES['modello3d']['tmp_name']);
    $query3d = "UPDATE Prodotto SET fileModello = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query3d);
    mysqli_stmt_bind_param($stmt, "si", $percorso3D, $id);
    mysqli_stmt_execute($stmt);
}

// Upload immagini
if (!empty($_FILES['immagini']['name'][0])) {
    foreach ($_FILES['immagini']['tmp_name'] as $index => $tmpName) {
        if ($_FILES['immagini']['error'][$index] === UPLOAD_ERR_OK) {
            $path = store_file($tmpName);
            $query_img = "INSERT INTO Immagine (percorso, idProdotto) VALUES (?, ?)";
            $stmt = mysqli_prepare($connection, $query_img);
            mysqli_stmt_bind_param($stmt, "si", $path, $id);
            mysqli_stmt_execute($stmt);
        }
    }
}

header("Location: ../modificaProdotto.php?id=$id");
?>