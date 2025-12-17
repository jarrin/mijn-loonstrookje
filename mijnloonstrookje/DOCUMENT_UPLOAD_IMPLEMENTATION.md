# Document Upload Functionaliteit

## Overzicht
Complete document upload functionaliteit voor Mijn Loonstrookje applicatie met versleutelde opslag en rolgebaseerde toegang.

## Geïmplementeerde Features

### 1. **Documentbeheer**
- ✅ PDF upload functionaliteit
- ✅ Document type selectie: Loonstrook (payslip), Jaaroverzicht (annual_statement), Overig (other)
- ✅ Koppeling aan werknemer en periode (maand/week/jaar)
- ✅ Versleutelde opslag met Laravel Crypt
- ✅ Inline bekijken in browser
- ✅ Download functionaliteit
- ✅ Soft delete voor documenten

### 2. **Beveiligingsmaatregelen**
- ✅ Documenten worden versleuteld opgeslagen op de server
- ✅ Alleen werkgever en medewerker kunnen documenten bekijken
- ✅ Rolgebaseerde toegangscontrole
- ✅ Company-level isolatie (gebruikers zien alleen documenten van hun bedrijf)

### 3. **Gebruikersrollen en Toegang**
- **Werkgever**: Upload, bekijk, download en verwijder documenten
- **Medewerker**: Bekijk en download eigen documenten
- **Administratiekantoor**: Upload, bekijk en download documenten
- **Super Admin**: Volledige toegang tot alle documenten

## Database Wijzigingen

### Nieuwe Kolommen in `documents` tabel:
```sql
- original_filename (string) - Originele bestandsnaam
- file_size (integer) - Bestandsgrootte in bytes
- type enum updated: 'payslip', 'annual_statement', 'other'
```

## Nieuwe Bestanden

### Controllers
- `app/Http/Controllers/DocumentController.php` - Hoofdcontroller voor document management

### Views
- `resources/views/documents/upload.blade.php` - Upload formulier
- `resources/views/employee/EmployeeDocuments.blade.php` - Medewerker document overzicht

### Models
- `app/Models/Document.php` (updated) - Encryptie logica toegevoegd

## Routes

### Werkgever Routes
```php
GET  /documents/upload/{employee?} - Upload formulier
POST /documents/upload - Document opslaan
```

### Document Routes (alle rollen)
```php
GET    /documents/{document}/view - Inline bekijken
GET    /documents/{document}/download - Downloaden
DELETE /documents/{document} - Verwijderen
```

### Medewerker Routes
```php
GET /employee/documents - Eigen documenten bekijken
```

## Installatie Instructies

### 1. Database Migreren
```bash
cd /home/roan/Documents/SCHOOL/LEVEL\ 10/mijn-loonstrookje/mijnloonstrookje
php artisan migrate
```

### 2. Storage Directory Maken
De applicatie slaat documenten op in `storage/app/private/documents/`
```bash
mkdir -p storage/app/private/documents
chmod -R 775 storage/app/private
```

### 3. Configuratie Controleren
Zorg dat `APP_KEY` is ingesteld in `.env` voor encryptie:
```bash
php artisan key:generate
```

## Gebruik

### Document Uploaden (Werkgever)
1. Ga naar Medewerkers lijst
2. Klik op "Document Uploaden" of selecteer een medewerker
3. Vul het formulier in:
   - Selecteer medewerker
   - Kies document type (Loonstrook/Jaaroverzicht/Overig)
   - Selecteer periode type
   - Vul jaar (en maand/week indien van toepassing) in
   - Upload PDF bestand (max 10MB)
   - Voeg optioneel een notitie toe
4. Klik op "Document Uploaden"

### Document Bekijken (Alle Rollen)
- Klik op het oog icoon (👁️) om inline te bekijken
- Klik op de download pijl (⬇️) om te downloaden
- Klik op de prullenbak om te verwijderen (alleen uploader)

### Medewerker
1. Ga naar dashboard
2. Klik op "Mijn Documenten" (link moet nog toegevoegd worden aan dashboard)
3. Bekijk en download eigen documenten

## Technische Details

### Encryptie
Documenten worden versleuteld opgeslagen met Laravel's `Crypt` facade:
```php
// Opslaan
$encryptedContents = Crypt::encrypt($contents);

// Ophalen
$content = Crypt::decrypt($encryptedContent);
```

### Bestandsstructuur
```
storage/app/private/documents/
  ├── {company_id}/
  │   ├── {employee_id}/
  │   │   ├── {timestamp}_{unique_id}.pdf
```

### Validatie
- Alleen PDF bestanden toegestaan
- Maximum bestandsgrootte: 10MB
- Verplichte velden: medewerker, type, periode, jaar
- Maand verplicht voor maandelijkse periode
- Week verplicht voor wekelijkse periode

## API Endpoints Overzicht

| Method | Route | Beschrijving | Rollen |
|--------|-------|--------------|--------|
| GET | `/documents/upload/{employee?}` | Upload formulier tonen | employer, administration |
| POST | `/documents/upload` | Document opslaan | employer, administration |
| GET | `/documents/{id}/view` | Document inline bekijken | alle rollen |
| GET | `/documents/{id}/download` | Document downloaden | alle rollen |
| DELETE | `/documents/{id}` | Document verwijderen | uploader, super_admin |
| GET | `/employer/employees/{id}/documents` | Documenten van medewerker | employer |
| GET | `/employer/documents` | Alle documenten van bedrijf | employer |
| GET | `/employee/documents` | Eigen documenten | employee |

## Model Helpers

### Document Model
```php
// Ophalen van gedecrypteerde content
$document->getDecryptedContent()

// Display naam
$document->display_name // "Loonstrook - Januari 2024"

// Geformatteerde bestandsgrootte
$document->formatted_size // "2.5 MB"
```

## Toekomstige Verbeteringen
- [ ] Email notificaties bij nieuwe documenten
- [ ] Bulk upload functionaliteit
- [ ] Document preview thumbnails
- [ ] Zoek- en filterfunctionaliteit
- [ ] Export naar ZIP
- [ ] Versiehistorie voor documenten
- [ ] OCR voor doorzoekbare PDFs
- [ ] Digitale handtekening functionaliteit

## Troubleshooting

### "Unable to decrypt payload"
- Controleer of APP_KEY consistent is
- Zorg dat bestanden niet handmatig zijn gewijzigd

### "File not found"
- Controleer storage permissions: `chmod -R 775 storage`
- Controleer of storage/app/private directory bestaat

### Upload fails
- Controleer max upload size in php.ini: `upload_max_filesize` en `post_max_size`
- Controleer disk ruimte op server

## Security Checklist
- ✅ Versleutelde opslag
- ✅ Rolgebaseerde toegangscontrole
- ✅ Company-level isolatie
- ✅ File type validatie (alleen PDF)
- ✅ Bestandsgrootte limiet
- ✅ Soft deletes
- ✅ Authorization checks in controller

## Ondersteuning
Voor vragen of problemen, neem contact op met het ontwikkelteam.
