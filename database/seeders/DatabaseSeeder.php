<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ApiKey;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User']
        );

        $plainKey = 'test_api_key_123';

        ApiKey::updateOrCreate(
            ['name' => 'Default Key'],
            [
                'key_prefix' => substr($plainKey, 0, 8),
                'key_hash' => Hash::make($plainKey),
                'is_active' => true,
            ]
        );

        echo "API KEY: " . $plainKey . PHP_EOL;
    }
}