# Employee Invitation Flow - Implementatie

## ✅ Geïmplementeerd

De volledige workflow voor het toevoegen van werknemers is geïmplementeerd:

### Workflow
1. **Werkgever nodigt werknemer uit**
   - Drukt op "Medewerker Toevoegen" knop
   - Voert e-mailadres van werknemer in
   
2. **Werknemer ontvangt uitnodiging**
   - E-mail met activatielink (7 dagen geldig)
   - Link: `/invitation/accept/{token}`
   
3. **Werknemer maakt account aan**
   - Klikt op activatielink in e-mail
   - Voert naam en wachtwoord in
   - Account wordt automatisch aangemaakt als 'employee'
   
4. **Twee-factor authenticatie**
   - Na registratie wordt gebruiker doorgestuurd naar 2FA setup
   - Moet 2FA instellen voordat inloggen mogelijk is
   
5. **Inloggen**
   - Werknemer kan nu inloggen met e-mail en wachtwoord
   - Moet 2FA code invoeren bij inloggen

## 📁 Nieuwe Bestanden

### Models
- `app/Models/Invitation.php` - Invitation model met token generation en validatie

### Controllers
- `app/Http/Controllers/InvitationController.php` - Handles invitation flow

### Migrations
- `database/migrations/2025_12_10_112534_create_invitations_table.php`

### Views
- `resources/views/employer/invite-employee.blade.php` - Formulier om werknemer uit te nodigen
- `resources/views/auth/register-invited.blade.php` - Registratie formulier voor uitgenodigde werknemer
- `resources/views/mails/employee-invitation.blade.php` - E-mail template

### Mail
- `app/Mail/EmployeeInvitation.php` - Mailable class voor uitnodigingsmail

## 🔧 Configuratie

### Mailhog Setup
De applicatie is geconfigureerd om Mailhog te gebruiken voor e-mails (`.env`):
```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@mijnloonstrookje.nl"
MAIL_FROM_NAME="${APP_NAME}"
```

### Mailhog Opstarten

**Optie 1: Via Docker (aanbevolen)**
```bash
docker run -d -p 1025:1025 -p 8025:8025 mailhog/mailhog
```

**Optie 2: Standalone installatie**
```bash
# Op Ubuntu/Debian
sudo apt-get install golang-go
go install github.com/mailhog/MailHog@latest
~/go/bin/MailHog
```

**Optie 3: Via Homebrew (macOS)**
```bash
brew install mailhog
mailhog
```

Mailhog web interface: http://localhost:8025

## 🛣️ Routes

### Employer Routes (auth + role:employer required)
- `GET /employer/invite-employee` - Toon uitnodigingsformulier
- `POST /employer/invite-employee` - Verstuur uitnodiging

### Public Routes (no auth required)
- `GET /invitation/accept/{token}` - Toon registratieformulier
- `POST /invitation/register/{token}` - Verwerk registratie

## 📊 Database Schema

### invitations table
```
- id
- email (unique)
- token (unique)
- employer_id (foreign key to users)
- company_id (foreign key to companies, nullable)
- status (enum: pending, accepted, expired)
- expires_at (timestamp)
- created_at
- updated_at
```

## 🧪 Testen

1. **Start Mailhog**
   ```bash
   docker run -d -p 1025:1025 -p 8025:8025 mailhog/mailhog
   ```

2. **Start Laravel applicatie**
   ```bash
   php artisan serve
   ```

3. **Login als werkgever**
   - Gebruik een bestaande employer account

4. **Nodig werknemer uit**
   - Ga naar "Medewerkers Lijst"
   - Klik op "Medewerker Toevoegen"
   - Voer e-mailadres in
   - Klik "Uitnodiging Versturen"

5. **Bekijk e-mail in Mailhog**
   - Open http://localhost:8025
   - Bekijk de uitnodigingsmail
   - Kopieer de activatielink

6. **Activeer account**
   - Open de activatielink
   - Vul naam en wachtwoord in
   - Klik "Account Activeren"

7. **Setup 2FA**
   - Word automatisch doorgestuurd naar 2FA setup
   - Scan QR code met authenticator app
   - Voer code in om te bevestigen

8. **Login**
   - Logout
   - Login met nieuwe credentials
   - Voer 2FA code in

## 🔒 Beveiliging

- Invitations hebben unieke tokens (64 random characters)
- Invitations verlopen na 7 dagen
- Kan alleen gebruikt worden als status = 'pending' en niet expired
- E-mailadres moet uniek zijn (mag niet al bestaan in users of invitations)
- Alleen employers kunnen invitations versturen
- 2FA is verplicht na registratie

## 📝 Validatie

### Invite Employee Form
- E-mail is verplicht
- E-mail moet geldig formaat hebben
- E-mail moet uniek zijn in users en invitations tables

### Register Invited Employee Form
- Naam is verplicht (max 255 karakters)
- Wachtwoord is verplicht (min 8 karakters)
- Wachtwoord confirmatie moet matchen

## 🎨 UI Updates

- Groene "Medewerker Toevoegen" knop toegevoegd aan EmployerEmployeeList
- Success/error meldingen in EmployerEmployeeList
- Informatieve meldingen in formulieren over volgende stappen
- Responsive design met Tailwind CSS classes

## 🚀 Volgende Stappen (Optioneel)

1. **Herinneringsmails** - Verstuur reminder als invitation bijna verloopt
2. **Invitation overzicht** - Toon lijst van pending invitations voor employer
3. **Invitation intrekken** - Mogelijkheid om invitation te cancelen
4. **Bulk invitations** - Meerdere werknemers tegelijk uitnodigen
5. **Custom expiration time** - Employer kan zelf expiration instellen
