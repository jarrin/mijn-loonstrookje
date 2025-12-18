<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MijnLoonstrookje - Loonstrookjes Beheren</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <img src="{{ asset('images/loonstrookje-breed-logo-upscale-transparent.png') }}" alt="Mijn Loonstrookje">
            </div>
            <ul class="nav-menu">
                <li><a href="#functies">Functionaliteiten</a></li>
                <li><a href="#veiligheid">Veiligheid</a></li>
                <li><a href="#abonnement">Abonnementen</a></li>
            </ul>
            <button class="nav-button">Inloggen</button>
        </div>
    </nav>

    <!-- Header Section -->
    <header class="header-section">
        <div class="header-container">
            <h1 class="header-title">Loonstrookjes beheren, simpel en veilig</h1>
            <p class="header-subtitle">De complete oplossing voor het digitaal beheren van loonstroken. Voor bedrijven van elke omvang en administratiekantoren.</p>
        </div>
    </header>

    <!-- Features Section -->
    <section class="features-section" id="functies">
        <div class="container">
            <h2 class="section-title">Alles wat je nodig hebt</h2>
            <p class="section-subtitle">Een complete oplossing voor loonstrookbeheer</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0052cc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text-icon lucide-file-text"><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"/><path d="M14 2v5a1 1 0 0 0 1 1h5"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                    </div>
                    <h3>Digitale Loonstroken</h3>
                    <p>Upload en beheer alle loonstroken op één centrale plek. Medewerkers hebben 24/7 toegang tot hun archief.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="9" stroke="#0052cc" stroke-width="2" fill="none"/>
                            <path d="M12 6V12L16 14" stroke="#0052cc" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3>Automatische Verwerking</h3>
                    <p>Importeer loongegevens direct vanuit je salarissysteem. Bespaar tijd en voorkomen fouten.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0052cc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>
                    </div>
                    <h3>Medewerkerportaal</h3>
                    <p>Een persoonlijk portal waar medewerkers hun loonstroken kunnen inkijken en downloaden.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2Z" stroke="#0052cc" stroke-width="2" fill="none"/>
                            <path d="M8 12L11 15L16 8" stroke="#0052cc" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3>Eenvoudig Downloaden</h3>
                    <p>Download loonstroken individueel of in bulk. Perfect voor loonbrieven en archivering.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Admin Section -->
    <section class="admin-section">
        <div class="container">
            <h2 class="section-title">Ook voor administratiekantoren</h2>
            <p class="section-subtitle">Beheer loonstroken voor al je klanten vanuit één overzichtelijk platform. Perfect voor administratie- en salariskantoren die meerdere bedrijven beheren.</p>
            
            <div class="admin-content">
                <div class="admin-features">
                    <div class="admin-feature">
                        <span class="admin-icon">👥</span>
                        <div>
                            <h4>Multi-tenant beheer</h4>
                            <p>Beheer meerdere bedrijven vanuit één account</p>
                        </div>
                    </div>
                    <div class="admin-feature">
                        <span class="admin-icon">🔐</span>
                        <div>
                            <h4>Centrale ingang</h4>
                            <p>Sluit klanten in en geef hen volledige Hinzugsystemen</p>
                        </div>
                    </div>
                    <div class="admin-feature">
                        <span class="admin-icon">📊</span>
                        <div>
                            <h4>Uitgebreide rapportage</h4>
                            <p>Inzicht in gebruik en activiteit per klant</p>
                        </div>
                    </div>
                </div>
                <div class="admin-subscription-cards">
                    <div class="subscription-badge">
                        <div class="badge-color-dot" style="background: #0052cc;"></div>
                        <div class="badge-content">
                            <h4>Bedrijf A</h4>
                            <p>45 medewerkers</p>
                        </div>
                    </div>
                    <div class="subscription-badge">
                        <div class="badge-color-dot" style="background: #2ecc71;"></div>
                        <div class="badge-content">
                            <h4>Bedrijf B</h4>
                            <p>28 medewerkers</p>
                        </div>
                    </div>
                    <div class="subscription-badge">
                        <div class="badge-color-dot" style="background: #9b59b6;"></div>
                        <div class="badge-content">
                            <h4>Bedrijf C</h4>
                            <p>12 medewerkers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Security Section -->
    <section class="security-section" id="veiligheid">
        <div class="container">
            <div class="security-header">
                <span class="security-icon-large"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-icon lucide-shield"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg></span>
                <h2 class="section-title">Veiligheid staat voorop</h2>
            </div>
            <p class="section-subtitle">Loongegevens zijn gevoelig. Daarom nemen wij bescherming serieus. Jouw data is bij ons in veilige handen.</p>
            
            <div class="security-grid">
                <div class="security-card">
                    <div class="security-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L3 6V12C3 18.6 12 22 12 22C12 22 21 18.6 21 12V6L12 2Z" stroke="#0052cc" stroke-width="2" fill="none"/>
                            <path d="M8 12L11 15L16 9" stroke="#0052cc" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3>SSL Encryptie</h3>
                    <p>Alle gegevens worden versleuteld verzonden en SSL/TLS versleuteld opgeslagen.</p>
                </div>
                <div class="security-card">
                    <div class="security-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2Z" stroke="#0052cc" stroke-width="2" fill="none"/>
                            <path d="M8 12L11 15L16 8" stroke="#0052cc" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3>AVG Compliant</h3>
                    <p>Volledig conform de AVG verordening, zoals gestipuleerd in Europa.</p>
                </div>
                <div class="security-card">
                    <div class="security-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" stroke="#0052cc" stroke-width="2" fill="none"/>
                            <path d="M12 7V12L15.5 14.5" stroke="#0052cc" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3>Toegangscontrole</h3>
                    <p>Medewerkers zien alleen hun eigen gegevens. Geen zichtbaarheid voor collega's.</p>
                </div>
                <div class="security-card">
                    <div class="security-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C6.5 2 2 6.5 2 12V20C2 21.1 2.9 22 4 22H20C21.1 22 22 21.1 22 20V12C22 6.5 17.5 2 12 2Z" stroke="#0052cc" stroke-width="2" fill="none"/>
                            <path d="M12 13V18M9 15H15" stroke="#0052cc" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3>Dagelijks Backup</h3>
                    <p>Automatische dagelijkse backups zorgen ervoor dat je alles veilig hebt.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Subscriptions Section -->
    <section class="subscriptions-section" id="abonnement">
        <div class="container">
            <h2 class="section-title">Kies je abonnement</h2>
            <p class="section-subtitle">Transparante prijzen, geen verborgen kosten. Altijd opzegbaar.</p>
            
            <div class="subscriptions-container">
                @foreach($subscriptions as $index => $subscription)
                    <div class="subscription-card @if($index === 1) featured @endif">
                        @if($index === 1)
                            <div class="featured-badge">Meest populair</div>
                        @endif
                        
                        <h3 class="subscription-name">{{ $subscription->name ?? 'N/A' }}</h3>
                        
                        <div class="subscription-price">
                            <span class="price-amount">€{{ $subscription->price ?? '0.00' }}</span>
                            <span class="price-period">/maand</span>
                        </div>
                        
                        <ul class="subscription-features">
                            <li class="feature-item">{{ $subscription->feature_1 ?? 'N/A' }}</li>
                            <li class="feature-item">{{ $subscription->feature_2 ?? 'N/A' }}</li>
                            <li class="feature-item">{{ $subscription->feature_3 ?? 'N/A' }}</li>
                        </ul>
                        
                        <button class="subscription-button">Start {{ $subscription->name ?? 'plan' }}</button>
                    </div>
                @endforeach
            </div>
            
            <p class="subscriptions-footer">Alle abonnementen inclusief 30 dagen gratis proefperiode</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-column footer-main">
                <div class="footer-brand">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="2" y="2" width="36" height="36" rx="8" fill="#0052cc"/>
                        <path d="M20 10V30M12 20H28" stroke="white" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                    <span>MijnLoonstrookje</span>
                </div>
                <p>De complete oplossing voor digitaal loonstrookbeheer.</p>
            </div>
            <div class="footer-column">
                <h5>Product</h5>
                <ul>
                    <li><a href="#">Functionaliteiten</a></li>
                    <li><a href="#">Prijzen</a></li>
                    <li><a href="#">Integraties</a></li>
                    <li><a href="#">Demo</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h5>Ondersteuning</h5>
                <ul>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Documentatie</a></li>
                    <li><a href="#">Status</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h5>Juridisch</h5>
                <ul>
                    <li><a href="#">Algemene voorwaarden</a></li>
                    <li><a href="#">Privacy beleid</a></li>
                    <li><a href="#">Cookies</a></li>
                    <li><a href="#">AVG</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 MijnLoonstrookje. Alle rechten voorbehouden.</p>
            <div class="footer-payments">
                <span class="payment-label">Betaalmethoden:</span>
                <span class="payment-item">iDEAL</span>
                <span class="payment-item">🇳🇱 VISA</span>
                <span class="payment-item">🇳🇱 MC</span>
                <button class="payment-paypal">PayPal</button>
            </div>
        </div>
    </footer>
</body>
</html>
