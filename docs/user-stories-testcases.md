# User stories en testcases

## User stories

**US-01 - Account registreren**  
Als student wil ik een account kunnen aanmaken met mijn naam, e-mailadres en wachtwoord, zodat ik kan inloggen en de boekenmarktplaats kan gebruiken.

**US-02 - Inloggen**  
Als student wil ik kunnen inloggen met mijn e-mailadres en wachtwoord, zodat ik toegang krijg tot mijn profiel, boeken en advertenties.

**US-03 - Boek toevoegen**  
Als ingelogde student wil ik een boek kunnen toevoegen met titel, auteur, conditie, prijs, categorie en locatie, zodat andere gebruikers kunnen zien welk boek ik aanbied.

**US-04 - Advertentie plaatsen**  
Als ingelogde student wil ik voor mijn eigen boek een advertentie kunnen plaatsen, zodat het boek zichtbaar wordt op de advertentiepagina.

**US-05 - Profiel bewerken**  
Als ingelogde student wil ik mijn naam, e-mailadres en eventueel wachtwoord kunnen aanpassen, zodat mijn profielgegevens actueel blijven.

---

## Testcase US-01 - Account registreren

| Veld | Waarde |
|---|---|
| Project | Realisatie |
| Test ID | TC-01 |
| Test name | Account registreren |
| Use Case ID | UC-01 |
| Use Case name | Account aanmaken |
| Description | Controleer of een nieuwe student een account kan registreren en daarna automatisch op de profielpagina terechtkomt. |

**Pre-conditions**

De database is beschikbaar. De gebruiker is nog niet ingelogd. Het e-mailadres `teststudent@example.com` bestaat nog niet in de tabel `gebruiker`.

**Steps**

| # | Step | Expected result | Test data | Actual result |
|---|---|---|---|---|
| Steps normal flow |||||
| 1 | Open de registratiepagina. | De pagina `Registreren` wordt getoond. | `/Realisatie/public/register.php` | |
| 2 | Vul de naam in. | De naam staat in het veld. | `Test Student` | |
| 3 | Vul het e-mailadres in. | Het e-mailadres staat in het veld. | `teststudent@example.com` | |
| 4 | Vul het wachtwoord in. | Het wachtwoord is ingevuld. | `Welkom123` | |
| 5 | Klik op `Account aanmaken`. | De gebruiker wordt aangemaakt en ingelogd. | - | |
| 6 | Controleer de profielpagina. | De profielpagina toont naam, e-mail en rol `student`. | `Test Student`, `teststudent@example.com` | |

**Post-conditions**

Er is een nieuwe gebruiker aangemaakt met rol `student` en de gebruiker is ingelogd.

---

## Testcase US-02 - Inloggen

| Veld | Waarde |
|---|---|
| Project | Realisatie |
| Test ID | TC-02 |
| Test name | Inloggen |
| Use Case ID | UC-02 |
| Use Case name | Gebruiker inloggen |
| Description | Controleer of een bestaande student kan inloggen met geldige gegevens. |

**Pre-conditions**

De database is beschikbaar. Er bestaat een gebruiker met e-mailadres `teststudent@example.com` en wachtwoord `Welkom123`. De gebruiker is uitgelogd.

**Steps**

| # | Step | Expected result | Test data | Actual result |
|---|---|---|---|---|
| Steps normal flow |||||
| 1 | Open de loginpagina. | De pagina `Inloggen` wordt getoond. | `/Realisatie/public/login.php` | |
| 2 | Vul het e-mailadres in. | Het e-mailadres staat in het veld. | `teststudent@example.com` | |
| 3 | Vul het wachtwoord in. | Het wachtwoord is ingevuld. | `Welkom123` | |
| 4 | Klik op `Inloggen`. | De login wordt verwerkt. | - | |
| 5 | Controleer de redirect. | De gebruiker komt op `profile.php`. | - | |
| 6 | Controleer de profielgegevens. | Naam, e-mail en rol van de gebruiker worden getoond. | `teststudent@example.com` | |

**Post-conditions**

De gebruiker is ingelogd en bevindt zich op de profielpagina.

---

## Testcase US-03 - Boek toevoegen

| Veld | Waarde |
|---|---|
| Project | Realisatie |
| Test ID | TC-03 |
| Test name | Boek toevoegen |
| Use Case ID | UC-03 |
| Use Case name | Nieuw boek opslaan |
| Description | Controleer of een ingelogde student een boek kan toevoegen en dat het boek daarna zichtbaar is in het boekenoverzicht. |

**Pre-conditions**

De database is beschikbaar. De gebruiker `teststudent@example.com` is ingelogd.

**Steps**

| # | Step | Expected result | Test data | Actual result |
|---|---|---|---|---|
| Steps normal flow |||||
| 1 | Open de pagina `Boek toevoegen`. | Het formulier voor een nieuw boek wordt getoond. | `/Realisatie/public/add_book.php` | |
| 2 | Vul titel en auteur in. | Beide velden zijn ingevuld. | Titel: `Clean Code`, Auteur: `Robert C. Martin` | |
| 3 | Selecteer de conditie. | De conditie is geselecteerd. | `goed` | |
| 4 | Vul prijs, categorie en locatie in. | De velden zijn ingevuld. | Prijs: `15.50`, Categorie: `Programmeren`, Locatie: `Amsterdam` | |
| 5 | Klik op `Opslaan`. | Het boek wordt opgeslagen. | - | |
| 6 | Controleer het boekenoverzicht. | `Clean Code` staat in de tabel met de juiste eigenaar en prijs. | `Clean Code`, `15.50`, `Test Student` | |

**Post-conditions**

Het boek is aangemaakt en zichtbaar in het boekenoverzicht.

---

## Testcase US-04 - Advertentie plaatsen

| Veld | Waarde |
|---|---|
| Project | Realisatie |
| Test ID | TC-04 |
| Test name | Advertentie plaatsen |
| Use Case ID | UC-04 |
| Use Case name | Advertentie aanmaken voor eigen boek |
| Description | Controleer of een ingelogde student een advertentie kan plaatsen voor een eigen boek dat nog geen advertentie heeft. |

**Pre-conditions**

De database is beschikbaar. De gebruiker `teststudent@example.com` is ingelogd. Het boek `Clean Code` bestaat en heeft nog geen advertentie.

**Steps**

| # | Step | Expected result | Test data | Actual result |
|---|---|---|---|---|
| Steps normal flow |||||
| 1 | Open de pagina `Advertentie plaatsen`. | Het advertentieformulier wordt getoond. | `/Realisatie/public/add_ad.php` | |
| 2 | Selecteer het boek. | Het boek is geselecteerd in de keuzelijst. | `Clean Code` | |
| 3 | Selecteer de status. | Status `actief` is geselecteerd. | `actief` | |
| 4 | Klik op `Plaatsen`. | De advertentie wordt opgeslagen. | - | |
| 5 | Controleer de redirect. | De gebruiker komt op het advertentieoverzicht. | `/Realisatie/public/ads.php` | |
| 6 | Controleer de advertentietabel. | De advertentie toont boek, prijs, eigenaar en status `actief`. | `Clean Code`, `15.50`, `Test Student`, `actief` | |

**Post-conditions**

Er is een actieve advertentie aangemaakt voor het boek.

---

## Testcase US-05 - Profiel bewerken

| Veld | Waarde |
|---|---|
| Project | Realisatie |
| Test ID | TC-05 |
| Test name | Profiel bewerken |
| Use Case ID | UC-05 |
| Use Case name | Profielgegevens aanpassen |
| Description | Controleer of een ingelogde student zijn naam en e-mailadres kan aanpassen zonder verplicht een nieuw wachtwoord in te vullen. |

**Pre-conditions**

De database is beschikbaar. De gebruiker `teststudent@example.com` is ingelogd.

**Steps**

| # | Step | Expected result | Test data | Actual result |
|---|---|---|---|---|
| Steps normal flow |||||
| 1 | Open de pagina `Profiel bewerken`. | Het formulier met de huidige profielgegevens wordt getoond. | `/Realisatie/public/profile_edit.php` | |
| 2 | Wijzig de naam. | De nieuwe naam staat in het veld. | `Test Student Gewijzigd` | |
| 3 | Wijzig het e-mailadres. | Het nieuwe e-mailadres staat in het veld. | `teststudent2@example.com` | |
| 4 | Laat het veld nieuw wachtwoord leeg. | Het wachtwoordveld blijft leeg. | Leeg | |
| 5 | Klik op `Opslaan`. | De melding `Profiel bijgewerkt.` wordt getoond. | - | |
| 6 | Open de profielpagina en controleer de gegevens. | De nieuwe naam en het nieuwe e-mailadres worden getoond. | `Test Student Gewijzigd`, `teststudent2@example.com` | |

**Post-conditions**

De profielgegevens zijn bijgewerkt en de sessie bevat de nieuwe naam en het nieuwe e-mailadres.
