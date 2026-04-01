<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrNew(['email' => 'admin@trisassor.com']);
        $admin->name = 'Admin Trisassor';
        $admin->phone = '081234567890';
        $admin->password = Hash::make('admin123');
        $admin->role = 'admin';
        $admin->email_verified_at = now();
        $admin->save();

        $this->command->info('✅ Admin seeded: admin@trisassor.com / admin123');
    }
}
