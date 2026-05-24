<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use updateOrCreate so if you run the seeder twice, it doesn't crash
        User::updateOrCreate(
            ['email' => 'admin@yourstore.com'], // The email you will use to log in
            [
                'name' => 'Store Admin',
                'password' => Hash::make('password123'), // Change this to a strong password!
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created successfully!');
    }
}
