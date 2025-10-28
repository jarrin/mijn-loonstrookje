<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscriptions</title>
</head>
<body>
    <div class="subscriptions-container">
        <div class="subscription-item subscription-1">
            <div class="subscription-name">
                <h2>{{ $subscriptions[0]->name ?? 'N/A' }}</h2>
            </div>
            <div class="subscription-description">
                <p>{{ $subscriptions[0]->description ?? 'N/A' }}</p>
            </div>
            <div class="subscription-price">
                <p>€{{ $subscriptions[0]->price ?? '0.00' }}</p>
            </div>
        </div>

        <div class="subscription-item subscription-2">
            <div class="subscription-name">
                <h2>{{ $subscriptions[1]->name ?? 'N/A' }}</h2>
            </div>
            <div class="subscription-description">
                <p>{{ $subscriptions[1]->description ?? 'N/A' }}</p>
            </div>
            <div class="subscription-price">
                <p>€{{ $subscriptions[1]->price ?? '0.00' }}</p>
            </div>
        </div>

        <div class="subscription-item subscription-3">
            <div class="subscription-name">
                <h2>{{ $subscriptions[2]->name ?? 'N/A' }}</h2>
            </div>
            <div class="subscription-description">
                <p>{{ $subscriptions[2]->description ?? 'N/A' }}</p>
            </div>
            <div class="subscription-price">
                <p>€{{ $subscriptions[2]->price ?? '0.00' }}</p>
            </div>
        </div>
    </div>
</body>
</html>
