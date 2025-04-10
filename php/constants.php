<?php
enum TipoUtente : int {
    case NOT_LOGGED = -1;
    case COMPRATORE = 0;
    case VENDITORE = 1;
    case ADMIN = 2;
}
enum TipoUtenteStr : string {
    case NOT_LOGGED = "not_logged";
    case COMPRATORE = "buyer";
    case VENDITORE = "seller";
    case ADMIN = "admin";
}

#NOMI DELLE CHIAVI DI SESSIONE
$ID_UTENTE = "email"; #email dell'utente
$IS_ADMIN = "admin"; #check se l'utente è admin o meno
$TIPO_UTENTE = "tipo_utente";
?>