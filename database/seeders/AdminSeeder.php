<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@trainingsystem.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        $admin->assignRole('admin');

        // Create additional admin users
        $admins = [
            [
                'name' => 'John Admin',
                'email' => 'john.admin@trainingsystem.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Sarah Manager',
                'email' => 'sarah.manager@trainingsystem.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        ];

        foreach ($admins as $adminData) {
            $user = User::create($adminData);
            $user->assignRole('admin');
        }
    }
}
