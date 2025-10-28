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
            <div class="subscription-features">
                <ul>
                    <li>{{ $subscriptions[0]->feature_1 ?? 'N/A' }}</li>
                    <li>{{ $subscriptions[0]->feature_2 ?? 'N/A' }}</li>
                    <li>{{ $subscriptions[0]->feature_3 ?? 'N/A' }}</li>
                </ul>
            </div>
            <div class="subscription-price">
                <p>€{{ $subscriptions[0]->price ?? '0.00' }}</p>
            </div>
        </div>

        <div class="subscription-item subscription-2">
            <div class="subscription-name">
                <h2>{{ $subscriptions[1]->name ?? 'N/A' }}</h2>
            </div>
            <div class="subscription-features">
                <ul>
                    <li>{{ $subscriptions[1]->feature_1 ?? 'N/A' }}</li>
                    <li>{{ $subscriptions[1]->feature_2 ?? 'N/A' }}</li>
                    <li>{{ $subscriptions[1]->feature_3 ?? 'N/A' }}</li>
                </ul>
            </div>
            <div class="subscription-price">
                <p>€{{ $subscriptions[1]->price ?? '0.00' }}</p>
            </div>
        </div>

        <div class="subscription-item subscription-3">
            <div class="subscription-name">
                <h2>{{ $subscriptions[2]->name ?? 'N/A' }}</h2>
            </div>
            <div class="subscription-features">
                <ul>
                    <li>{{ $subscriptions[2]->feature_1 ?? 'N/A' }}</li>
                    <li>{{ $subscriptions[2]->feature_2 ?? 'N/A' }}</li>
                    <li>{{ $subscriptions[2]->feature_3 ?? 'N/A' }}</li>
                </ul>
            </div>
            <div class="subscription-price">
                <p>€{{ $subscriptions[2]->price ?? '0.00' }}</p>
            </div>
        </div>
    </div>
</body>
</html>
