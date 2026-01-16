<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="nl">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <meta name="x-apple-disable-message-reformatting">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="telephone=no" name="format-detection">
  <title>Uitnodiging voor Mijn Loonstrookje - Custom Abonnement</title>
  <style type="text/css">
    body {
      width: 100%;
      font-family: 'Arial', 'Helvetica Neue', Helvetica, sans-serif;
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
      padding: 0;
      margin: 0;
    }
    table {
      mso-table-lspace: 0pt;
      mso-table-rspace: 0pt;
      border-collapse: collapse;
      border-spacing: 0px;
    }
    .es-button {
      mso-style-priority: 100 !important;
      text-decoration: none !important;
    }
    a {
      text-decoration: underline;
    }
    .content-block {
      padding: 20px;
    }
    .main-container {
      max-width: 600px;
      margin: 0 auto;
      background-color: #ffffff;
    }
    .header {
      background-color: #4A90E2;
      padding: 30px 20px;
      text-align: center;
    }
    .header h1 {
      color: #ffffff;
      margin: 0;
      font-size: 28px;
    }
    .button {
      background-color: #4A90E2;
      color: #ffffff;
      padding: 15px 40px;
      text-decoration: none;
      border-radius: 5px;
      display: inline-block;
      margin: 20px 0;
      font-weight: bold;
    }
    .subscription-details {
      background-color: #f9fafb;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      padding: 15px;
      margin: 20px 0;
    }
    .footer {
      background-color: #f4f4f4;
      padding: 20px;
      text-align: center;
      font-size: 12px;
      color: #666666;
    }
  </style>
</head>
<body>
  <table width="100%" cellspacing="0" cellpadding="0" style="background-color: #f4f4f4; padding: 20px 0;">
    <tr>
      <td align="center">
        <table class="main-container" width="600" cellspacing="0" cellpadding="0">
          <!-- Header -->
          <tr>
            <td class="header">
              <h1>Mijn Loonstrookje</h1>
            </td>
          </tr>
          
          <!-- Content -->
          <tr>
            <td class="content-block">
              <h2 style="color: #333333;">Welkom bij Mijn Loonstrookje!</h2>
              
              <p style="color: #555555; line-height: 1.6;">
                Hallo,
              </p>
              
              <p style="color: #555555; line-height: 1.6;">
                Je bent uitgenodigd om een account aan te maken op het Mijn Loonstrookje platform met een speciaal custom abonnement.
              </p>
              
              <div class="subscription-details">
                <h3 style="color: #333333; margin-top: 0;">Jouw Custom Abonnement</h3>
                <p style="color: #555555; margin: 5px 0;">
                  <strong>Prijs:</strong> €{{ number_format($customSubscription->price, 2, ',', '.') }}
                </p>
                <p style="color: #555555; margin: 5px 0;">
                  <strong>Betalingstermijn:</strong> {{ ucfirst($customSubscription->billing_period) }}
                </p>
                <p style="color: #555555; margin: 5px 0;">
                  <strong>Max gebruikers:</strong> {{ $customSubscription->max_users }}
                </p>
              </div>
              
              <p style="color: #555555; line-height: 1.6;">
                Om je account te activeren en aan de slag te gaan, klik je op de onderstaande knop:
              </p>
              
              <p style="text-align: center;">
                <a href="{{ $activationUrl }}" class="button">Activeer Mijn Account</a>
              </p>
              
              <p style="color: #555555; line-height: 1.6; font-size: 14px;">
                Of kopieer deze link naar je browser:<br>
                <a href="{{ $activationUrl }}" style="color: #4A90E2; word-break: break-all;">{{ $activationUrl }}</a>
              </p>
              
              <p style="color: #555555; line-height: 1.6;">
                Na activatie doorloop je de volgende stappen:
              </p>
              <ol style="color: #555555; line-height: 1.6;">
                <li>Account aanmaken</li>
                <li>E-mailadres verifiëren</li>
                <li>Twee-factor authenticatie instellen</li>
                <li>Betaling voltooien voor jouw custom abonnement</li>
              </ol>
              
              <p style="color: #555555; line-height: 1.6;">
                Deze uitnodiging is <strong>7 dagen</strong> geldig.
              </p>
              
              <p style="color: #999999; font-size: 12px; line-height: 1.6; margin-top: 30px; border-top: 1px solid #eeeeee; padding-top: 20px;">
                Als je deze uitnodiging niet hebt verwacht, kun je deze e-mail negeren.
              </p>
            </td>
          </tr>
          
          <!-- Footer -->
          <tr>
            <td class="footer">
              <p style="margin: 0;">© 2026 Mijn Loonstrookje. Alle rechten voorbehouden.</p>
              <p style="margin: 10px 0 0 0;">
                Dit is een automatisch gegenereerde e-mail. Reageer hier niet op.
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
