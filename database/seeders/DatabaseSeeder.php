<?php
namespace Database\Seeders;
use App\Models\City;
use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@ecolocate.test'],
            [
                'name' => 'EcoLocate Admin',
                'password' => Hash::make('admin12345'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
        $cities = [
            ['name' => 'Vapi', 'state' => 'Gujarat'],
            ['name' => 'Surat', 'state' => 'Gujarat'],
            ['name' => 'Valsad', 'state' => 'Gujarat'],
            ['name' => 'Ahmedabad', 'state' => 'Gujarat'],
            ['name' => 'Mumbai', 'state' => 'Maharashtra'],
        ];
        foreach ($cities as $city) {
            City::firstOrCreate(
                ['name' => $city['name']],
                ['state' => $city['state'], 'is_active' => true]
            );
        }
        $devices = [
            [
                'brand' => 'Vivo',
                'model_name' => 'V29',
                'category' => 'Smartphone',
                'description' => 'Mid-range smartphone with AMOLED display.',
                'materials' => 'Copper, Aluminium, Gold (trace), Plastic',
                'harmful_components' => 'Lithium-ion battery, Lead solder',
                'estimated_recycling_value' => 180.00,
                'eco_credits' => 150,
                'recycling_information' => 'Battery must be removed separately before recycling.',
            ],
            [
                'brand' => 'Dell',
                'model_name' => 'Inspiron 15',
                'category' => 'Laptop',
                'description' => 'Standard consumer laptop.',
                'materials' => 'Aluminium, Copper, Plastic, Rare earth metals',
                'harmful_components' => 'Lithium-ion battery, Mercury (in older backlights)',
                'estimated_recycling_value' => 450.00,
                'eco_credits' => 300,
                'recycling_information' => 'Hard drive should be wiped before recycling.',
            ],
            [
                'brand' => 'Samsung',
                'model_name' => 'Galaxy Tab A8',
                'category' => 'Tablet',
                'description' => 'Entry-level Android tablet.',
                'materials' => 'Aluminium, Glass, Copper',
                'harmful_components' => 'Lithium-ion battery',
                'estimated_recycling_value' => 220.00,
                'eco_credits' => 180,
                'recycling_information' => 'Screen glass recycled separately.',
            ],
        ];
        foreach ($devices as $device) {
            Device::firstOrCreate(
                ['brand' => $device['brand'], 'model_name' => $device['model_name']],
                $device
            );
        }
    }
}