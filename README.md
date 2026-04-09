# Realisatie

Klein project met een MySQL/MariaDB database op basis van de meegeleverde ERD.

## Database scripts

- `database.sql`: maakt de database `realisatie` + alle tabellen (zonder dummy data).
- `data.sql`: vult de tabellen met dummy data (uitvoeren na `database.sql`).

## Uitvoeren (XAMPP / phpMyAdmin)

1. Start **MySQL** in XAMPP.
2. Open phpMyAdmin → tab **SQL**
3. Voer `database.sql` uit.
4. Voer daarna `data.sql` uit.

## Snelle test queries

Advertenties met boek + eigenaar:

```sql
SELECT a.id AS advertentie_id, a.status, b.titel, u.naam AS eigenaar
FROM advertentie a
JOIN boek b ON b.id = a.boek_id
JOIN gebruiker u ON u.id = a.gebruiker_id
ORDER BY a.id;
```

Transacties met koper/verkoper/boek:

```sql
SELECT t.id, t.status, bk.titel, koper.naam AS koper, verkoper.naam AS verkoper
FROM transactie t
JOIN boek bk ON bk.id = t.boek_id
JOIN gebruiker koper ON koper.id = t.koper_id
JOIN gebruiker verkoper ON verkoper.id = t.verkoper_id
ORDER BY t.id;
```

