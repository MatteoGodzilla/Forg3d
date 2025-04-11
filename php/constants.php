<?php
enum UserType : int {
    case NOT_LOGGED = -1;
    case BUYER = 0;
    case SELLER = 1;
    case ADMIN = 2;
}

#NOMI DELLE CHIAVI DI SESSIONE
$ID_UTENTE = "email"; #email dell'utente
$TIPO_UTENTE = "tipo_utente";
?>