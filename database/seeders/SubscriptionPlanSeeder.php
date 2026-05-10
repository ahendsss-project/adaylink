<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubscriptionPlan::updateOrCreate(
            ['name' => 'Starter'],
            [
                'price' => 50000,
                'max_tours' => 5,
                'max_vehicles' => 3,
                'max_pages' => 5,
                'allowed_template_tier' => 'Basic',
                'is_active' => true,
                'features' => [
                    'floating_whatsapp' => true,
                    'social_share' => false,
                    'gallery_lightbox' => false,
                    'reviews' => false,
                    'multilanguage' => false,
                ],
            ]
        );

        SubscriptionPlan::updateOrCreate(
            ['name' => 'Pro Agent'],
            [
                'price' => 150000,
                'max_tours' => 999,
                'max_vehicles' => 999,
                'max_pages' => 999,
                'allowed_template_tier' => 'All',
                'is_active' => true,
                'features' => [
                    'floating_whatsapp' => true,
                    'social_share' => true,
                    'gallery_lightbox' => true,
                    'reviews' => true,
                    'multilanguage' => true,
                    'custom_domain' => true,
                ],
            ]
        );

        $this->command->info('✅ Subscription Plans seeded: Starter (Rp50.000), Pro Agent (Rp150.000)');
    }
}
