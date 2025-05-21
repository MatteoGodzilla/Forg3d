<?php

enum UserType : int {
    case NOT_LOGGED = -1;
    case BUYER = 0;
    case SELLER = 1;
    case ADMIN = 2;
}

enum AlertType : int{
    case SUCCESS = 0;
    case WARNING = 1;
    case ERROR = 2;
}

class Constants {
    #NOMI DELLE CHIAVI DI SESSIONE
    public const ID_UTENTE = "email";
    public const TIPO_UTENTE = "tipo_utente";

    #ESTENSIONI FILE AMMESSE
    public static array $ALLOWED_IMAGE_EXTENSIONS = ["png", "jpeg", "webp"];
    public static array $ALLOWED_3DFILE_EXTENSIONS = ["stl"];
}

?>
