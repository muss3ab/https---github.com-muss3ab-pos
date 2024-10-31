<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'company_name' => 'My POS System',
            'company_address' => '123 Main Street',
            'company_phone' => '123-456-7890',
            'tax_rate' => '10',
            'currency' => 'USD',
            'default_language' => 'en',
            'receipt_footer' => 'Thank you for your business!',
            'low_stock_alert' => '10',
            'enable_email_notifications' => '1'
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

    }
}
