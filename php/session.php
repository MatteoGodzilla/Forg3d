<?php
require_once("./constants");

#WARNING: queste funzioni aiutano per lo scripting della sezione,assicurarsi di richiamare nella propria pagina session_start() prima di usarle!
#https://www.w3schools.com/php/php_sessions.asp

#restituisce true se la richiesta avviene da parte di un utente loggato,false altrimenti
function utenteLoggato() {
    include 'constants.php';
    return isset($_SESSION[$ID_UTENTE]);
}

#ridireziona al parametro url passato se l'utente non è loggato
#WARNING! se dell'html è gia stato renderizzato, questa funzione non andrà
#https://www.php.net/manual/it/function.header.php
function ridirezionaSeNonLoggato($url){
    if(!utenteLoggato()) {
        header("Location: ".$url);
        exit;
    }
}
#restituisce true se la richiesta avviene da parte di un utente loggato come amministratore,false altrimenti
function isAdmin() {
    return getTipoUtente() === "admin";
}

#restituisce l'email di session se l'utente è loggato,o una stringa vuota se l'utente non è loggato.
function getSessionEmail() {
    include 'constants.php';
    return utenteLoggato()? $_SESSION[$ID_UTENTE] : '';
}

#setta l'email di sessione.
function setSessionEmail($email) {
    include 'constants.php';
    $_SESSION[$ID_UTENTE] = $email;
}

#setta i privilegi di amministratore nella sessione
function setAdmin($admin) {
    include 'constants.php';
    $_SESSION[$IS_ADMIN] = $email;
}

# restituisce il tipo utente dalla sessione
function getTipoUtente() {
    include 'constants.php';
    return isset($_SESSION[$TIPO_UTENTE]) ? $_SESSION[$TIPO_UTENTE] : '';
}

# setta il tipo utente nella sessione
function setTipoUtente($tipo) {
    include 'constants.php';
    $_SESSION[$TIPO_UTENTE] = $tipo;
}
?>