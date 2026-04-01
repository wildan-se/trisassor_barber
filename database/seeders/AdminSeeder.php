<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@trisassor.com'],
            [
                'name'     => 'Admin Trisassor',
                'email'    => 'admin@trisassor.com',
                'phone'    => '081234567890',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Admin seeded: admin@trisassor.com / admin123');
    }
}
