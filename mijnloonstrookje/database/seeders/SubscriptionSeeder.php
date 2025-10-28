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
                'feature_1' => 'Tot 5 werknemers',
                'feature_2' => 'Automatische loonstroken',
                'feature_3' => 'Email ondersteuning',
                'price' => 9.99,
                'subscription_plan' => 'simple',
            ],
            [
                'name' => 'basic plan',
                'feature_1' => 'Tot 25 werknemers',
                'feature_2' => 'Automatische loonstroken en belastingdocumenten',
                'feature_3' => 'Prioriteit email ondersteuning',
                'price' => 29.99,
                'subscription_plan' => 'basic',
            ],
            [
                'name' => 'premium plan',
                'feature_1' => 'Onbeperkt werknemers',
                'feature_2' => 'Volledige automatisering en rapportages',
                'feature_3' => '24/7 telefoon en email ondersteuning',
                'price' => 99.99,
                'subscription_plan' => 'premium',
            ],
        ];

        foreach ($subscriptions as $subscription) {
            Subscription::create($subscription);
        }
    }
}
