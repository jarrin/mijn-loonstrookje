# 🚀 Quick Start - Employee Invitation Feature

## Stap 1: Start Services
```bash
# Start Docker containers (database, phpMyAdmin, Mailhog)
docker-compose up -d

# Start Laravel development server
php artisan serve
```

## Stap 2: Toegang tot Services
- **Laravel App**: http://localhost:8000
- **Mailhog Web UI**: http://localhost:8025 (bekijk verzonden e-mails)
- **phpMyAdmin**: http://localhost:8080

## Stap 3: Test de Workflow

### Als Werkgever:
1. Login als werkgever op http://localhost:8000
2. Ga naar "Medewerkers" in het menu
3. Klik op de groene knop **"Medewerker Toevoegen"**
4. Voer een e-mailadres in (bijv. `test@example.com`)
5. Klik op **"Uitnodiging Versturen"**
6. Je ziet een succesbericht

### E-mail Bekijken:
1. Open http://localhost:8025 in je browser
2. Je ziet de uitnodigingsmail
3. Klik op de mail om de inhoud te bekijken
4. Kopieer de **activatielink** uit de mail

### Als Werknemer (Account Activeren):
1. Plak de activatielink in je browser
2. Vul je **volledige naam** in
3. Kies een **wachtwoord** (minimaal 8 tekens)
4. Bevestig het wachtwoord
5. Klik op **"Account Activeren"**
6. Je wordt automatisch doorgestuurd naar **Two-Factor Authentication** setup

### 2FA Instellen:
1. Scan de QR-code met een authenticator app (bijv. Google Authenticator, Authy)
2. Voer de 6-cijferige code in die de app toont
3. Klik op "Bevestig"
4. Je account is nu volledig geactiveerd! 🎉

### Inloggen:
1. Log uit als werkgever
2. Login met het nieuwe employee account
3. Voer je e-mail en wachtwoord in
4. Voer de 2FA code uit je authenticator app in
5. Je bent nu ingelogd als werknemer!

## 🔍 Troubleshooting

### E-mails worden niet verzonden
- Check of Mailhog draait: `docker ps | grep mailhog`
- Herstart Mailhog: `docker-compose restart mailhog`
- Check `.env`: `MAIL_HOST=127.0.0.1` en `MAIL_PORT=1025`

### Database errors
- Check of MySQL draait: `docker ps | grep mysql`
- Herstart database: `docker-compose restart db`
- Run migrations: `php artisan migrate`

### "Invitation expired" error
- Invitations zijn 7 dagen geldig
- Maak een nieuwe invitation aan
- Check `invitations` tabel in database

## 📋 Handige Commando's

```bash
# Stop alle Docker containers
docker-compose down

# Start alle Docker containers
docker-compose up -d

# Bekijk logs van Mailhog
docker logs mijnloonstrookje_mailhog

# Reset database
php artisan migrate:fresh --seed

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## 🎯 Belangrijke Info

- **Invitation geldigheid**: 7 dagen
- **2FA**: Verplicht voor alle nieuwe accounts
- **Mailhog**: Vangt alle uitgaande e-mails op (geen echte e-mails verzonden)
- **E-mail duplicaten**: Kan niet dezelfde e-mail twee keer uitnodigen

## ✅ Checklist

- [ ] Docker containers draaien
- [ ] Laravel app draait op http://localhost:8000
- [ ] Mailhog UI bereikbaar op http://localhost:8025
- [ ] Kan inloggen als werkgever
- [ ] Kan uitnodiging versturen
- [ ] E-mail verschijnt in Mailhog
- [ ] Kan account activeren via link
- [ ] 2FA setup werkt
- [ ] Kan inloggen als nieuwe werknemer
