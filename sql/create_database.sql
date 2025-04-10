CREATE DATABASE IF NOT EXISTS `Forg3d` ;
USE `Forg3d`;

/* Utenti */

CREATE TABLE IF NOT EXISTS Utente (
    email VARCHAR(254) PRIMARY KEY,
    password BINARY(60) NOT NULL,
    nome VARCHAR(32) NOT NULL,
    cognome VARCHAR(32) NOT NULL,
    telefono VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS Compratore (
    emailUtente VARCHAR(254) NOT NULL,
    FOREIGN KEY (emailUtente) REFERENCES Utente(email) ON DELETE CASCADE
);

/* TODO: aggiungere verifica del venditore */
CREATE TABLE IF NOT EXISTS Venditore (
    emailUtente VARCHAR(254) NOT NULL,
    stato TINYINT DEFAULT 0 COMMENT "0: In attesa, 1: Verificato, 2: Bannato",
    motivoBan VARCHAR(1024),
    FOREIGN KEY (emailUtente) REFERENCES Utente(email) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS Admin (
    emailUtente VARCHAR(254) NOT NULL,
    FOREIGN KEY (emailUtente) REFERENCES Utente(email) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Follow (
    emailCompratore VARCHAR(254),
    emailVenditore VARCHAR(254),
    PRIMARY KEY (emailCompratore, emailVenditore),
    FOREIGN KEY (emailCompratore) REFERENCES Compratore(emailUtente) ON DELETE CASCADE,
    FOREIGN KEY (emailVenditore) REFERENCES Venditore(emailUtente) ON DELETE CASCADE
);

/* Notifiche */

CREATE TABLE IF NOT EXISTS Notifica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titolo VARCHAR(64),
    descrizione VARCHAR(512),
    emailVenditore VARCHAR(254),
    FOREIGN KEY (emailVenditore) REFERENCES Venditore(emailUtente)
);

CREATE TABLE IF NOT EXISTS NotificaLetta (
    idNotifica INT,
    emailCompratore VARCHAR(254),
    PRIMARY KEY (idNotifica, emailCompratore),
    FOREIGN KEY (idNotifica) REFERENCES Notifica(id) ON DELETE CASCADE,
    FOREIGN KEY (emailCompratore) REFERENCES Compratore(emailUtente) ON DELETE CASCADE
);

/* Prodotti */
CREATE TABLE IF NOT EXISTS Prodotto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    emailVenditore VARCHAR(254) NOT NULL,
    nome VARCHAR(64) NOT NULL,
    fileModello VARCHAR(254) NOT NULL,
    visibile BOOLEAN DEFAULT FALSE,
    varianteDefault INT COMMENT "nullable",
    FOREIGN KEY (emailVenditore) REFERENCES Venditore(emailUtente)
);

CREATE TABLE IF NOT EXISTS Materiale (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipologia VARCHAR(32) NOT NULL,
    nomeColore VARCHAR(64) NOT NULL,
    hexColore CHAR(6) NOT NULL COMMENT "codice esadecimale senza # (es. ff3433)"
);

CREATE TABLE IF NOT EXISTS Variante (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idProdotto INT NOT NULL,
    idMateriale INT NOT NULL,
    prezzo INT COMMENT "centesimi",
    FOREIGN KEY (idProdotto) REFERENCES Prodotto(id),
    FOREIGN KEY (idMateriale) REFERENCES Materiale(id)
);

CREATE TABLE IF NOT EXISTS ImmaginiProdotto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idProdotto INT NOT NULL,
    nomeFile VARCHAR(256) NOT NULL,
    FOREIGN KEY (idProdotto) REFERENCES Prodotto(id)
);

/* ALTER TABLE Prodotto ADD FOREIGN KEY (varianteDefault) REFERENCES Variante(id); */

/* TODO:Check che la variante e il prodotto facciano riferimento circolare tra loro */
/* TODO:Decidere che cosa succede quando il venditore vuole rimuovere un prodotto/variante/materiale */

/* Ordini */
/* Carrello = 1+ Ordini associati ad un compratore con status 0 */
/* L'ordine è associato soltanto a un singolo venditore */
CREATE TABLE IF NOT EXISTS Ordine (
    id INT AUTO_INCREMENT PRIMARY KEY,
    emailCompratore VARCHAR(254) NOT NULL,
    emailVenditore VARCHAR(254) NOT NULL,
    stato TINYINT DEFAULT 0 COMMENT "0: Carrello, 1: Pagato, 2: Spedito",
    dataCreazione DATETIME DEFAULT CURRENT_TIMESTAMP,
    prezzo INT NOT NULL,
    FOREIGN KEY (emailCompratore) REFERENCES Compratore(emailUtente),
    FOREIGN KEY (emailVenditore) REFERENCES Venditore(emailUtente)
);

CREATE TABLE IF NOT EXISTS InfoOrdine (
    idOrdine INT NOT NULL,
    idVariante INT NOT NULL,
    quantita TINYINT DEFAULT 1,
    FOREIGN KEY (idOrdine) REFERENCES Ordine(id),
    FOREIGN KEY (idVariante) REFERENCES Variante(id)
);

/* Recensioni */
CREATE TABLE IF NOT EXISTS Recensione (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(254) NOT NULL,
    valutazione TINYINT NOT NULL,
    titolo VARCHAR(64),
    testo VARCHAR(1024),
    dataCreazione DATETIME DEFAULT CURRENT_TIMESTAMP,
    inRispostaA INT,
    FOREIGN KEY (email) REFERENCES Utente(email), /* è voluto che qua ci sia utente e non compratore */
    FOREIGN KEY (inRispostaA) REFERENCES Recensione(id)
);

/* Segnalazioni */

CREATE TABLE IF NOT EXISTS Segnalazione (
    id INT AUTO_INCREMENT PRIMARY KEY,
    emailSegnalatore VARCHAR(254) NOT NULL,
    motivo VARCHAR(1024)
);

CREATE TABLE IF NOT EXISTS SegnalazioneProdotto(
    idSegnalazione INT NOT NULL,
    idProdotto INT NOT NULL,
    FOREIGN KEY (idSegnalazione) REFERENCES Segnalazione(id),
    FOREIGN KEY (idProdotto) REFERENCES Prodotto(id)
);

CREATE TABLE IF NOT EXISTS SegnalazioneVenditore(
    idSegnalazione INT NOT NULL,
    emailVenditore VARCHAR(254) NOT NULL,
    FOREIGN KEY (idSegnalazione) REFERENCES Segnalazione(id),
    FOREIGN KEY (emailVenditore) REFERENCES Venditore(emailUtente)
);

/*Admin registration token*/
Create Table IF NOT EXISTS AdminToken(
    token varchar(64),
    email varchar(254),
    used boolean DEFAULT false,
    FOREIGN KEY (email) REFERENCES Admin(emailUtente),
    PRIMARY KEY(token));