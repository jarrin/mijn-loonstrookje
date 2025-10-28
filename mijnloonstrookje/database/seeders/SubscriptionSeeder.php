<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subscriptions = [
            [
                'name' => 'simpel plan',
                'description' => 'Simpel subscription plan with basic features',
                'price' => 9.99,
                'subscription_plan' => 'simple',
            ],
            [
                'name' => 'basic plan',
                'description' => 'Basic subscription with essential features',
                'price' => 29.99,
                'subscription_plan' => 'basic',
            ],
            [
                'name' => 'premium plan',
                'description' => 'Premium subscription with advanced features and priority support',
                'price' => 99.99,
                'subscription_plan' => 'premium',
            ],
        ];

        foreach ($subscriptions as $subscription) {
            Subscription::create($subscription);
        }
    }
}
