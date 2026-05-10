<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            ['name' => 'Luxury', 'folder_name' => 'luxury', 'tier' => 'Premium', 'is_active' => true],
            ['name' => 'Minimalis', 'folder_name' => 'minimalis', 'tier' => 'Basic', 'is_active' => true],
            ['name' => 'Clean', 'folder_name' => 'clean', 'tier' => 'Basic', 'is_active' => true],
            ['name' => 'Adventure', 'folder_name' => 'adventure', 'tier' => 'Basic', 'is_active' => true],
            ['name' => 'Modern', 'folder_name' => 'modern', 'tier' => 'Basic', 'is_active' => true],
            ['name' => 'Card', 'folder_name' => 'card', 'tier' => 'Basic', 'is_active' => true],
            ['name' => 'Quick', 'folder_name' => 'quick', 'tier' => 'Basic', 'is_active' => true],
        ];

        foreach ($templates as $template) {
            Template::updateOrCreate(
                ['name' => $template['name']],
                [
                    'folder_name' => $template['folder_name'],
                    'tier' => $template['tier'],
                    'thumbnail_url' => null,
                    'is_active' => $template['is_active'],
                    'config_schema' => null,
                ]
            );
        }

        // Deactivate old templates that no longer exist
        Template::whereNotIn('name', collect($templates)->pluck('name')->toArray())
            ->update(['is_active' => false]);

        $this->command->info('✅ Templates seeded: Luxury (Premium), Minimalis (Basic), Clean (Basic), Adventure (Basic), Modern (Basic), Card (Basic), Quick (Basic)');
    }
}
