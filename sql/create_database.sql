-- --------------------------------------------------------
-- Host:                         matteogodzilla.net
-- Versione server:              10.11.11-MariaDB-0+deb12u1 - Debian 12
-- S.O. server:                  debian-linux-gnu
-- HeidiSQL Versione:            11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dump della struttura del database Forg3d
CREATE DATABASE IF NOT EXISTS `Forg3d` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `Forg3d`;

-- Dump della struttura di tabella Forg3d.Admin
CREATE TABLE IF NOT EXISTS `Admin` (
  `emailUtente` varchar(254) NOT NULL,
  KEY `emailUtente` (`emailUtente`),
  CONSTRAINT `Admin_ibfk_1` FOREIGN KEY (`emailUtente`) REFERENCES `Utente` (`email`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella Forg3d.AdminToken
CREATE TABLE IF NOT EXISTS `AdminToken` (
  `token` varchar(64) NOT NULL,
  `email` varchar(254) DEFAULT NULL,
  `used` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`token`),
  KEY `email` (`email`),
  CONSTRAINT `AdminToken_ibfk_1` FOREIGN KEY (`email`) REFERENCES `Admin` (`emailUtente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella Forg3d.Carrello
CREATE TABLE IF NOT EXISTS `Carrello` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emailCompratore` varchar(254) NOT NULL,
  `idVariante` int(11) NOT NULL,
  `quantita` int(11) NOT NULL DEFAULT 1,
  `tsAggiunta` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `Carrello_Compratore_FK` (`emailCompratore`),
  KEY `Carrello_Variante_FK` (`idVariante`),
  CONSTRAINT `Carrello_Compratore_FK` FOREIGN KEY (`emailCompratore`) REFERENCES `Compratore` (`emailUtente`),
  CONSTRAINT `Carrello_Variante_FK` FOREIGN KEY (`idVariante`) REFERENCES `Variante` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella Forg3d.Compratore
CREATE TABLE IF NOT EXISTS `Compratore` (
  `emailUtente` varchar(254) NOT NULL,
  PRIMARY KEY (`emailUtente`),
  KEY `emailUtente` (`emailUtente`),
  CONSTRAINT `Compratore_ibfk_1` FOREIGN KEY (`emailUtente`) REFERENCES `Utente` (`email`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella Forg3d.Follow
CREATE TABLE IF NOT EXISTS `Follow` (
  `emailCompratore` varchar(254) NOT NULL,
  `emailVenditore` varchar(254) NOT NULL,
  PRIMARY KEY (`emailCompratore`,`emailVenditore`),
  KEY `emailVenditore` (`emailVenditore`),
  CONSTRAINT `Follow_ibfk_1` FOREIGN KEY (`emailCompratore`) REFERENCES `Compratore` (`emailUtente`) ON DELETE CASCADE,
  CONSTRAINT `Follow_ibfk_2` FOREIGN KEY (`emailVenditore`) REFERENCES `Venditore` (`emailUtente`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella Forg3d.ImmaginiProdotto
CREATE TABLE IF NOT EXISTS `ImmaginiProdotto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idProdotto` int(11) NOT NULL,
  `nomeFile` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idProdotto` (`idProdotto`),
  CONSTRAINT `ImmaginiProdotto_ibfk_1` FOREIGN KEY (`idProdotto`) REFERENCES `Prodotto` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella Forg3d.InfoOrdine
CREATE TABLE IF NOT EXISTS `InfoOrdine` (
  `idOrdine` int(11) NOT NULL,
  `idVariante` int(11) NOT NULL,
  `prezzo` int(11) NOT NULL,
  `quantita` tinyint(4) NOT NULL DEFAULT 1,
  KEY `idOrdine` (`idOrdine`),
  KEY `idVariante` (`idVariante`),
  CONSTRAINT `InfoOrdine_ibfk_1` FOREIGN KEY (`idOrdine`) REFERENCES `Ordine` (`id`),
  CONSTRAINT `InfoOrdine_ibfk_2` FOREIGN KEY (`idVariante`) REFERENCES `Variante` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella Forg3d.Materiale
CREATE TABLE IF NOT EXISTS `Materiale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipologia` varchar(32) NOT NULL,
  `nomeColore` varchar(64) NOT NULL,
  `hexColore` char(6) NOT NULL COMMENT 'codice esadecimale senza # (es. ff3433)',
  `idVenditore` varchar(254) NOT NULL,
  `visibile` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`id`),
  KEY `Materiale_Venditore_FK` (`idVenditore`),
  CONSTRAINT `Materiale_Venditore_FK` FOREIGN KEY (`idVenditore`) REFERENCES `Venditore` (`emailUtente`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella Forg3d.Notifica
CREATE TABLE IF NOT EXISTS `Notifica` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titolo` varchar(64) DEFAULT NULL,
  `descrizione` varchar(512) DEFAULT NULL,
  `emailMIttente` varchar(254) DEFAULT NULL,
  `creazione` timestamp NOT NULL DEFAULT current_timestamp(),
  `emailDestinatario` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `emailVenditore` (`emailMIttente`),
  CONSTRAINT `Notifica_ibfk_1` FOREIGN KEY (`emailMIttente`) REFERENCES `Venditore` (`emailUtente`)
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella Forg3d.NotificaLetta
CREATE TABLE IF NOT EXISTS `NotificaLetta` (
  `idNotifica` int(11) NOT NULL,
  `destinatario` varchar(254) NOT NULL,
  `visibile` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`idNotifica`,`destinatario`),
  KEY `NotificaLetta_ibfk_2_idx` (`destinatario`),
  CONSTRAINT `NotificaLetta_ibfk_1` FOREIGN KEY (`idNotifica`) REFERENCES `Notifica` (`id`) ON DELETE CASCADE,
  CONSTRAINT `NotificaLetta_ibfk_2` FOREIGN KEY (`destinatario`) REFERENCES `Utente` (`email`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella Forg3d.Ordine
CREATE TABLE IF NOT EXISTS `Ordine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emailCompratore` varchar(254) NOT NULL,
  `emailVenditore` varchar(254) NOT NULL,
  `stato` tinyint(4) DEFAULT 0 COMMENT '0, 1 spedito,2 confermato arrivo',
  `dataCreazione` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `emailCompratore` (`emailCompratore`),
  KEY `emailVenditore` (`emailVenditore`),
  CONSTRAINT `Ordine_ibfk_1` FOREIGN KEY (`emailCompratore`) REFERENCES `Compratore` (`emailUtente`),
  CONSTRAINT `Ordine_ibfk_2` FOREIGN KEY (`emailVenditore`) REFERENCES `Venditore` (`emailUtente`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella Forg3d.Prodotto
CREATE TABLE IF NOT EXISTS `Prodotto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emailVenditore` varchar(254) NOT NULL,
  `nome` varchar(64) NOT NULL,
  `descrizione` varchar(1024) DEFAULT NULL,
  `fileModello` varchar(254) DEFAULT NULL,
  `visibile` tinyint(8) NOT NULL DEFAULT 0 COMMENT '0: Finta eliminazione dal sistema, 1: Solo elenco venditore, 2: Visibile',
  `varianteDefault` int(11) DEFAULT NULL COMMENT 'nullable',
  `ultimaModifica` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `emailVenditore` (`emailVenditore`),
  CONSTRAINT `Prodotto_ibfk_1` FOREIGN KEY (`emailVenditore`) REFERENCES `Venditore` (`emailUtente`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella Forg3d.Recensione
CREATE TABLE IF NOT EXISTS `Recensione` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(254) NOT NULL,
  `idProdotto` int(11) NOT NULL,
  `valutazione` tinyint(4) NOT NULL COMMENT 'da 0 a 5, solo interi',
  `titolo` varchar(64) DEFAULT NULL,
  `testo` varchar(1024) DEFAULT NULL,
  `dataCreazione` datetime DEFAULT current_timestamp(),
  `inRispostaA` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `inRispostaA` (`inRispostaA`),
  CONSTRAINT `Recensione_ibfk_1` FOREIGN KEY (`email`) REFERENCES `Utente` (`email`),
  CONSTRAINT `Recensione_ibfk_2` FOREIGN KEY (`inRispostaA`) REFERENCES `Recensione` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella Forg3d.Segnalazione
CREATE TABLE IF NOT EXISTS `Segnalazione` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emailSegnalatore` varchar(254) NOT NULL,
  `motivo` varchar(1024) DEFAULT NULL,
  `ispezionata` bit(1) NOT NULL DEFAULT b'0',
  `ultimaModifica` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella Forg3d.SegnalazioneProdotto
CREATE TABLE IF NOT EXISTS `SegnalazioneProdotto` (
  `idSegnalazione` int(11) NOT NULL,
  `idProdotto` int(11) NOT NULL,
  PRIMARY KEY (`idSegnalazione`,`idProdotto`),
  KEY `idSegnalazione` (`idSegnalazione`),
  KEY `idProdotto` (`idProdotto`),
  CONSTRAINT `SegnalazioneProdotto_ibfk_1` FOREIGN KEY (`idSegnalazione`) REFERENCES `Segnalazione` (`id`),
  CONSTRAINT `SegnalazioneProdotto_ibfk_2` FOREIGN KEY (`idProdotto`) REFERENCES `Prodotto` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella Forg3d.SegnalazioneVenditore
CREATE TABLE IF NOT EXISTS `SegnalazioneVenditore` (
  `idSegnalazione` int(11) NOT NULL,
  `emailVenditore` varchar(254) NOT NULL,
  KEY `idSegnalazione` (`idSegnalazione`),
  KEY `emailVenditore` (`emailVenditore`),
  CONSTRAINT `SegnalazioneVenditore_ibfk_1` FOREIGN KEY (`idSegnalazione`) REFERENCES `Segnalazione` (`id`),
  CONSTRAINT `SegnalazioneVenditore_ibfk_2` FOREIGN KEY (`emailVenditore`) REFERENCES `Venditore` (`emailUtente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella Forg3d.Utente
CREATE TABLE IF NOT EXISTS `Utente` (
  `email` varchar(254) NOT NULL,
  `password` binary(60) NOT NULL,
  `nome` varchar(32) NOT NULL,
  `cognome` varchar(32) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `dataCreazione` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella Forg3d.Variante
CREATE TABLE IF NOT EXISTS `Variante` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idProdotto` int(11) NOT NULL,
  `idMateriale` int(11) NOT NULL,
  `prezzo` int(11) NOT NULL DEFAULT 0 COMMENT 'centesimi',
  `visibile` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`id`),
  KEY `idProdotto` (`idProdotto`),
  KEY `idMateriale` (`idMateriale`),
  CONSTRAINT `Variante_ibfk_1` FOREIGN KEY (`idProdotto`) REFERENCES `Prodotto` (`id`),
  CONSTRAINT `Variante_ibfk_2` FOREIGN KEY (`idMateriale`) REFERENCES `Materiale` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=302 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella Forg3d.Venditore
CREATE TABLE IF NOT EXISTS `Venditore` (
  `emailUtente` varchar(254) NOT NULL,
  `stato` tinyint(4) DEFAULT 0 COMMENT '0: In attesa, 1: Verificato, 2: Rifiutato, 3: Bannato',
  `motivoBan` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`emailUtente`),
  KEY `emailUtente` (`emailUtente`),
  CONSTRAINT `Venditore_ibfk_1` FOREIGN KEY (`emailUtente`) REFERENCES `Utente` (`email`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- L’esportazione dei dati non era selezionata.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
