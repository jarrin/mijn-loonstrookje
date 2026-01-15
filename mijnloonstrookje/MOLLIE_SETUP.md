# Mollie Betalingen - Setup Instructies

## ✅ Wat is er geïmplementeerd

Ik heb een complete Mollie betalingsintegratie toegevoegd aan je Laravel applicatie:

### 1. **Mollie PHP Library**
- ✅ `mollie/mollie-api-php` geïnstalleerd via Composer

### 2. **Configuratie**
- ✅ Mollie config toegevoegd aan `config/services.php`
- ✅ CSRF uitzondering voor webhook endpoint

### 3. **PaymentController**
- ✅ `app/Http/Controllers/PaymentController.php` aangemaakt met:
  - `startPayment()` - Start een nieuwe betaling
  - `returnFromPayment()` - Waar gebruiker terugkomt na betaling
  - `webhook()` - Ontvangt Mollie status updates

### 4. **Routes**
- ✅ `POST /payment/start/{subscription}` - Start betaling
- ✅ `GET /payment/return/{subscription}` - Return URL
- ✅ `POST /payment/webhook` - Webhook endpoint

### 5. **View Updates**
- ✅ Website abonnement knoppen linken nu naar betaling

## 🔧 Setup Stappen

### Stap 1: Voeg je Mollie Test API Key toe

1. Log in op [Mollie Dashboard](https://www.mollie.com/dashboard)
2. Ga naar **Developers** → **API keys**
3. Kopieer je **Test API key** (begint met `test_...`)
4. Voeg deze toe aan je `.env` bestand:

```bash
MOLLIE_API_KEY=test_jouwApiKeyHier
```

### Stap 2: Login als employer

Je moet ingelogd zijn als employer om een abonnement te kunnen kopen. Gebruik een van de test accounts uit de seeder.

### Stap 3: Test de integratie

1. Start je Laravel development server als deze nog niet draait:
```bash
php artisan serve
```

2. Ga naar de website pagina:
```
http://localhost:8000/website
```

3. Klik op een "Start [plan naam]" knop

### Stap 3: Test de betaalflow

Je wordt nu doorgestuurd naar Mollie's checkout pagina. Op de test omgeving kun je testbetalingen doen:

- **Test creditcard**: Gebruik kaart `5555 5555 5555 4444`
- **Andere test methoden**: Mollie biedt ook test iDEAL, PayPal, etc.

## 📋 Hoe het werkt

### Flow:
1. **Gebruiker klikt** op "Start [plan]"
2. **POST request** naar `/payment/start/{subscription}`
3. **PaymentController** maakt Mollie betaling aan
4. **Redirect** naar Mollie checkout
5. **Gebruiker betaalt** (of annuleert)
6. **Return** naar `/payment/return/{subscription}`
7. **Mollie webhook** updatet status in achtergrond
8. **Abonnement** wordt gekoppeld aan bedrijf bij succesvolle betaling

### Belangrijke Code Locaties:

- **Controller**: `app/Http/Controllers/PaymentController.php`
- **Routes**: `routes/web.php` (regels ~124-127)
- **Config**: `config/services.php`
- **View**: `resources/views/website/website.blade.php` (regel ~216)
- **CSRF Middleware**: `app/Http/Middleware/VerifyCsrfToken.php`

## 🔍 Logging & Debugging

Alle Mollie acties worden gelogd naar `storage/logs/laravel.log`:

```bash
tail -f storage/logs/laravel.log
```

Je ziet logs voor:
- Payment creation
- Webhook calls
- Subscription updates
- Errors

## 🏠 Lokale Ontwikkeling

Voor **lokale ontwikkeling** (localhost/127.0.0.1) wordt de webhook automatisch **overgeslagen** omdat Mollie deze niet kan bereiken. In plaats daarvan wordt de betaling status direct gecontroleerd wanneer de gebruiker terugkomt van Mollie.

Dit betekent:
- ✅ Je kunt direct testen zonder ngrok of tunnel
- ✅ Het abonnement wordt geactiveerd na succesvolle test betaling
- ⚠️ Voor productie met een publieke URL wordt de webhook wel gebruikt

## 🚀 Volgende Stappen (optioneel)

Als je de rest zelf wilt implementeren:

1. **Status pagina** - Maak een pagina waar gebruikers hun betaling status kunnen zien
2. **E-mail notificaties** - Stuur emails bij succesvolle betaling
3. **Recurring payments** - Implementeer maandelijkse automatische betalingen
4. **Facturen** - Genereer PDF facturen na betaling
5. **Admin panel** - Toon alle betalingen in superadmin dashboard

## 🧪 Webhook Testen (lokaal)

Mollie kan niet naar `localhost` webhooks sturen. Opties:

1. **ngrok** (aanbevolen voor locale tests):
```bash
ngrok http 8000
```
Gebruik de ngrok URL als webhook URL

2. **Deploy naar test server** met publieke URL

3. **Mollie webhook simulator** in het dashboard

## ⚠️ Let op

- De webhook URL moet **publiek toegankelijk** zijn voor Mollie
- Test API keys werken alleen in test mode
- Ga voor productie naar live API keys in Mollie dashboard
- Webhooks zijn **asynchroon** - return page ≠ betaling voltooid

## 💡 Tips

- Check Mollie dashboard voor alle test betalingen
- Webhook logs in `storage/logs/laravel.log`
- Test altijd met verschillende betaalmethodes
- Gebruik metadata om extra info op te slaan in Mollie

## 📞 Support

Mollie documentatie: https://docs.mollie.com/
