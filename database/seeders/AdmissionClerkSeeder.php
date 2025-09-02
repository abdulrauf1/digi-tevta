<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdmissionClerkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $clerks = [
            [
                'name' => 'Alice Clerk',
                'email' => 'alice.clerk@trainingsystem.com',
                'password' => Hash::make('clerk123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Bob Admissions',
                'email' => 'bob.admissions@trainingsystem.com',
                'password' => Hash::make('clerk123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Carol Registrar',
                'email' => 'carol.registrar@trainingsystem.com',
                'password' => Hash::make('clerk123'),
                'email_verified_at' => now(),
            ]
        ];

        foreach ($clerks as $clerkData) {
            $user = User::create($clerkData);
            $user->assignRole('admission-clerk');
        }
    }
}
