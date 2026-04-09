-- data.sql
-- Dummy data om de tabellen te vullen (voer dit uit NA database.sql)

USE `realisatie`;

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
  ('Je advertentie \"Clean Code\" heeft een nieuw bericht.', 0, 1),
  ('Je advertentie \"Discrete Wiskunde\" heeft een nieuw bericht.', 1, 2),
  ('Nieuwe beoordeling ontvangen.', 0, 2);

INSERT INTO `transactie` (`status`, `koper_id`, `verkoper_id`, `boek_id`) VALUES
  ('in behandeling', 2, 1, 1),
  ('voltooid', 2, 1, 3);

