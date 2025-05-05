<?php
require_once("constants.php");

#WARNING: queste funzioni aiutano per lo scripting della sezione,assicurarsi di richiamare nella propria pagina session_start() prima di usarle!
#https://www.w3schools.com/php/php_sessions.asp

#wrapper per iniziare e terminare la sezione
function caricaSessione(){
    session_start();
}

function terminaSessione(){
    session_unset();
}

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
function isUserOf($type) {
    return getUserType() === $type;
}

#restituisce l'email di session se l'utente è loggato,o una stringa vuota se l'utente non è loggato.
function getSessionEmail() {
    include 'constants.php';
    return utenteLoggato()? $_SESSION[$ID_UTENTE] : '';
}

#setta l'email di sessione.
function setSession($email,$type) {
    include 'constants.php';
    $_SESSION[$ID_UTENTE] = $email;
    setUserType($type);
}

# restituisce il tipo utente dalla sessione
function getUserType() {
    include 'constants.php';
    return isset($_SESSION[$TIPO_UTENTE]) ? $_SESSION[$TIPO_UTENTE] : UserType::NOT_LOGGED->value;
}

# setta il tipo utente nella sessione
function setUserType($type) {
    include 'constants.php';
    if($type>=UserType::BUYER->value && $type<=UserType::ADMIN->value){
        $_SESSION[$TIPO_UTENTE] = $type;
    }
}
?>