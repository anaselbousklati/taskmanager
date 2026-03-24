# TaskManager

Een simpele taakbeheer applicatie gebouwd in PHP en MySQL volgens het MVC-patroon.
Gebruikers kunnen taken aanmaken, aanpassen, bekijken en verwijderen. Elke taak kan een categorie, prioriteit, status en deadline hebben.

---

## Functionaliteiten

* Registreren, inloggen en uitloggen
* Taken aanmaken, bewerken en verwijderen
* Overzicht van alle taken
* Filteren op status, prioriteit en deadline
* Simpele dashboard met statistieken
* Categorieën toevoegen aan taken
* Visuele indicatie voor verlopen deadlines
* Werkt op desktop en mobiel

---

## Technische stack

* Frontend: HTML, CSS, JavaScript
* Backend: PHP (8+)
* Database: MySQL / MariaDB
* Architectuur: MVC
* Beveiliging: PDO, password hashing, CSRF tokens

---

## Installatie

### Vereisten

* XAMPP of vergelijkbare setup (Apache, PHP, MySQL)
* Git

### Stappen

1. Repo klonen

```bash
git clone https://github.com/anaselbousklati/taskmanager.git
cd taskmanager
```

2. Environment bestand maken

```bash
cp .env.example .env
```

Pas de database gegevens aan in `.env`.

3. Database importeren

```bash
mysql -u root -p < database/db.sql
```

Of via phpMyAdmin importeren.

4. Project in htdocs zetten

Bijvoorbeeld:

```
C:\xampp\htdocs\taskmanager\
```

5. Openen in browser

```
http://localhost/taskmanager/public/index.php
```

Demo account:

* email: [demo@example.com](mailto:demo@example.com)
* wachtwoord: demo1234

---

## Structuur

```
taskmanager/
├── app/
│   ├── controllers/
│   ├── models/
│   ├── views/
│   └── helpers.php
├── config/
├── database/
├── public/
├── .env.example
├── .gitignore
└── README.md
```

---

## Beveiliging

* Prepared statements (PDO)
* Wachtwoorden gehasht met `password_hash`
* CSRF tokens in formulieren
* Sessies worden vernieuwd bij login
* Input wordt gesaneerd
* Controle of taken bij de juiste gebruiker horen
* `.env` staat buiten public map
* httpOnly cookies
* Basis bescherming via `.htaccess`

---

## Git workflow

* `main` → stabiele versie
* `develop` → actieve development
* `feature/*` → nieuwe functionaliteiten

---

## User stories


Als gebruiker wil ik kunnen registreren zodat ik een eigen account heb | ✅ Gedaan |
Als gebruiker wil ik kunnen inloggen met e-mail en wachtwoord | ✅ Gedaan |
Als gebruiker wil ik taken aanmaken met titel, beschrijving en deadline | ✅ Gedaan |
Als gebruiker wil ik taken een prioriteit geven (laag/normaal/hoog) | ✅ Gedaan |
Als gebruiker wil ik de status van taken bijhouden (open/bezig/gedaan) | ✅ Gedaan |
Als gebruiker wil ik taken filteren op status en prioriteit | ✅ Gedaan |
Als gebruiker wil ik een overzicht zien van mijn voortgang | ✅ Gedaan |
Als gebruiker wil ik taken indelen in categorieën | ✅ Gedaan |
Als gebruiker wil ik verlopen deadlines zien | ✅ Gedaan |
Als gebruiker wil ik taken verwijderen | ✅ Gedaan |
