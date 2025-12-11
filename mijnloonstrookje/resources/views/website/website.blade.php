<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscriptions</title>
    <link rel="stylesheet" href="{{ asset('css/showcase/showcase.css') }}">
</head>
<body>
    <!-- Header Section -->
    <header class="header-section">
        <div class="header-container">
            <h1 class="header-title">Loonstrookjes beheren, simpel en veilig</h1>
            <p class="header-subtitle">De complete oplossing voor het digitaal beheren van loonstroken. Voor bedrijven van elke omvang en administratiekantoren.</p>
        </div>
    </header>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title">Alles wat je nodig hebt</h2>
            <p class="section-subtitle">Een complete oplossing voor loonstrookbeheer</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📋</div>
                    <h3>Digitale Loonstroken</h3>
                    <p>Upload en beheer alle loonstroken. 24/7 toegang tot het archief voor medewerkers.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">⚙️</div>
                    <h3>Automatische Verwerking</h3>
                    <p>Verstuurt automatisch nieuwe loonstroken via email en slaat alles op.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3>Medewerkerportaal</h3>
                    <p>Een persoonlijk portal waar medewerkers hun loonstroken kunnen bekijken.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📥</div>
                    <h3>Eenvoudig Downloaden</h3>
                    <p>Medewerkers kunnen in bulk. Perfect voor loonbrieven en archiefering.</p>
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
                    <div class="subscription-badge subscription-badge-a">
                        <h4>Pakket A</h4>
                        <p>€1 medewerkers</p>
                    </div>
                    <div class="subscription-badge subscription-badge-b">
                        <h4>Pakket B</h4>
                        <p>25 medewerkers</p>
                    </div>
                    <div class="subscription-badge subscription-badge-c">
                        <h4>Pakket C</h4>
                        <p>50 medewerkers</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Security Section -->
    <section class="security-section">
        <div class="container">
            <h2 class="section-title">Veiligheid staat voorop</h2>
            <p class="section-subtitle">Loongegevens zijn gevoelig. Daarom nemen wij bescherming serieus. Jouw data is bij ons in veilige handen.</p>
            
            <div class="security-grid">
                <div class="security-card">
                    <div class="security-icon">🔒</div>
                    <h3>SSL Encryptie</h3>
                    <p>Alle gegevens worden via encrypted verzonden en SSL/TLS versleuteld.</p>
                </div>
                <div class="security-card">
                    <div class="security-icon">✓</div>
                    <h3>AVG Compliant</h3>
                    <p>Volledig conform de AVG verordening, zoals gestipuleerd in Europa.</p>
                </div>
                <div class="security-card">
                    <div class="security-icon">⏱️</div>
                    <h3>Toegangscontrole</h3>
                    <p>Medewerkers zien alleen hun eigen gegevens. Geen zichtbaarheid voor collega's.</p>
                </div>
                <div class="security-card">
                    <div class="security-icon">💾</div>
                    <h3>Dagelijks Backup</h3>
                    <p>Automatische dagelijkse backups zorgen ervoor dat je alles veilig hebt.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Subscriptions Section -->
    <section class="subscriptions-section">
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
</body>
</html>
