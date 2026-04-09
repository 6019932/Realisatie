-- MySQL / MariaDB init script gebaseerd op de ERD
-- Uit te voeren in phpMyAdmin (SQL tab) of via mysql cli.

SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE DATABASE IF NOT EXISTS `realisatie`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE `realisatie`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `transactie`;
DROP TABLE IF EXISTS `notificatie`;
DROP TABLE IF EXISTS `beoordeling`;
DROP TABLE IF EXISTS `bericht`;
DROP TABLE IF EXISTS `advertentie`;
DROP TABLE IF EXISTS `boek`;
DROP TABLE IF EXISTS `gebruiker`;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `gebruiker` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `naam` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `wachtwoord` VARCHAR(255) NOT NULL,
  `rol` ENUM('student', 'beheerder') NOT NULL DEFAULT 'student',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_gebruiker_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `boek` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titel` VARCHAR(255) NOT NULL,
  `auteur` VARCHAR(255) NOT NULL,
  `conditie` ENUM('nieuw', 'goed', 'gebruikt') NOT NULL,
  `prijs` DECIMAL(10,2) NOT NULL,
  `categorie` VARCHAR(100) NOT NULL,
  `locatie` VARCHAR(100) NOT NULL,
  `eigenaar_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_boek_eigenaar_id` (`eigenaar_id`),
  CONSTRAINT `fk_boek_eigenaar`
    FOREIGN KEY (`eigenaar_id`) REFERENCES `gebruiker` (`id`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `advertentie` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `status` ENUM('actief', 'verwijderd', 'verkocht') NOT NULL DEFAULT 'actief',
  `boek_id` INT NOT NULL,
  `gebruiker_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_advertentie_boek` (`boek_id`),
  KEY `idx_advertentie_gebruiker_id` (`gebruiker_id`),
  CONSTRAINT `fk_advertentie_boek`
    FOREIGN KEY (`boek_id`) REFERENCES `boek` (`id`)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT `fk_advertentie_gebruiker`
    FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruiker` (`id`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `bericht` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `inhoud` TEXT NOT NULL,
  `verzender_id` INT NOT NULL,
  `ontvanger_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_bericht_verzender_id` (`verzender_id`),
  KEY `idx_bericht_ontvanger_id` (`ontvanger_id`),
  CONSTRAINT `fk_bericht_verzender`
    FOREIGN KEY (`verzender_id`) REFERENCES `gebruiker` (`id`)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT `fk_bericht_ontvanger`
    FOREIGN KEY (`ontvanger_id`) REFERENCES `gebruiker` (`id`)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `beoordeling` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `score` INT NOT NULL,
  `recensie` TEXT NULL,
  `beoordelaar_id` INT NOT NULL,
  `beoordeelde_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_beoordeling_beoordelaar_id` (`beoordelaar_id`),
  KEY `idx_beoordeling_beoordeelde_id` (`beoordeelde_id`),
  CONSTRAINT `fk_beoordeling_beoordelaar`
    FOREIGN KEY (`beoordelaar_id`) REFERENCES `gebruiker` (`id`)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT `fk_beoordeling_beoordeelde`
    FOREIGN KEY (`beoordeelde_id`) REFERENCES `gebruiker` (`id`)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT `chk_beoordeling_score` CHECK (`score` BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `notificatie` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `bericht` VARCHAR(255) NOT NULL,
  `gelezen` BOOLEAN NOT NULL DEFAULT 0,
  `gebruiker_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_notificatie_gebruiker_id` (`gebruiker_id`),
  CONSTRAINT `fk_notificatie_gebruiker`
    FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruiker` (`id`)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `transactie` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `status` ENUM('in behandeling', 'voltooid', 'geannuleerd') NOT NULL DEFAULT 'in behandeling',
  `koper_id` INT NOT NULL,
  `verkoper_id` INT NOT NULL,
  `boek_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_transactie_koper_id` (`koper_id`),
  KEY `idx_transactie_verkoper_id` (`verkoper_id`),
  KEY `idx_transactie_boek_id` (`boek_id`),
  CONSTRAINT `fk_transactie_koper`
    FOREIGN KEY (`koper_id`) REFERENCES `gebruiker` (`id`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT `fk_transactie_verkoper`
    FOREIGN KEY (`verkoper_id`) REFERENCES `gebruiker` (`id`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT `fk_transactie_boek`
    FOREIGN KEY (`boek_id`) REFERENCES `boek` (`id`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================
-- Dummy data
-- =====================

INSERT INTO `gebruiker` (`naam`, `email`, `wachtwoord`, `rol`) VALUES
  ('Adam Student', 'adam@student.local', '$2y$10$dummyhashadam', 'student'),
  ('Bram Student', 'bram@student.local', '$2y$10$dummyhashbram', 'student'),
  ('Celine Student', 'celine@student.local', '$2y$10$dummyhashceline', 'student'),
  ('Beheerder', 'admin@school.local', '$2y$10$dummyhashadmin', 'beheerder');

INSERT INTO `boek` (`titel`, `auteur`, `conditie`, `prijs`, `categorie`, `locatie`, `eigenaar_id`) VALUES
  ('Clean Code', 'Robert C. Martin', 'goed', 25.00, 'Informatica', 'Antwerpen', 1),
  ('Discrete Wiskunde', 'Rosen', 'gebruikt', 18.50, 'Wiskunde', 'Gent', 2),
  ('Databases 101', 'Korth', 'nieuw', 32.99, 'Informatica', 'Brussel', 1),
  ('Marketing Basics', 'Kotler', 'goed', 15.00, 'Economie', 'Leuven', 3);

INSERT INTO `advertentie` (`status`, `boek_id`, `gebruiker_id`) VALUES
  ('actief', 1, 1),
  ('actief', 2, 2),
  ('verkocht', 3, 1),
  ('verwijderd', 4, 3);

INSERT INTO `bericht` (`inhoud`, `verzender_id`, `ontvanger_id`) VALUES
  ('Hoi, is Clean Code nog beschikbaar?', 2, 1),
  ('Ja, nog beschikbaar. Wil je afhalen of verzenden?', 1, 2),
  ('Kan ik korting krijgen op Discrete Wiskunde?', 3, 2);

INSERT INTO `beoordeling` (`score`, `recensie`, `beoordelaar_id`, `beoordeelde_id`) VALUES
  (5, 'Vlotte verkoop en correct boek.', 2, 1),
  (4, 'Goede communicatie.', 1, 2);

INSERT INTO `notificatie` (`bericht`, `gelezen`, `gebruiker_id`) VALUES
  ('Je advertentie "Clean Code" heeft een nieuw bericht.', 0, 1),
  ('Je advertentie "Discrete Wiskunde" heeft een nieuw bericht.', 1, 2),
  ('Nieuwe beoordeling ontvangen.', 0, 2);

INSERT INTO `transactie` (`status`, `koper_id`, `verkoper_id`, `boek_id`) VALUES
  ('in behandeling', 2, 1, 1),
  ('voltooid', 2, 1, 3);

-- =====================
-- Snelle tests (FK’s + joins)
-- =====================

-- Alle advertenties met boek + eigenaar
SELECT a.id AS advertentie_id, a.status, b.titel, u.naam AS eigenaar
FROM advertentie a
JOIN boek b ON b.id = a.boek_id
JOIN gebruiker u ON u.id = a.gebruiker_id
ORDER BY a.id;

-- Transacties met koper/verkoper/boek
SELECT t.id, t.status, bk.titel, koper.naam AS koper, verkoper.naam AS verkoper
FROM transactie t
JOIN boek bk ON bk.id = t.boek_id
JOIN gebruiker koper ON koper.id = t.koper_id
JOIN gebruiker verkoper ON verkoper.id = t.verkoper_id
ORDER BY t.id;

