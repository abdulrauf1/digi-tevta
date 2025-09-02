<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Trainer;
use Illuminate\Support\Facades\Hash;

class TrainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run()
    {
        $trainers = [
            [
                'name' => 'Dr. Michael Trainer',
                'email' => 'michael.trainer@trainingsystem.com',
                'password' => Hash::make('trainer123'),
                'email_verified_at' => now(),
                'trainer_data' => [
                    'cnic' => '12345-6786012-3',
                    'gender' => 'male',
                    'contact' => '+1234567890',
                    'specialization' => 'Web Development',
                    'experience_years' => 8,
                    'qualification' => 'MSc Computer Science',
                    'phone' => '+1234567890',
                    'address' => '123 Trainer Street, City'
                ]
            ],
            [
                'name' => 'Prof. Sarah Johnson',
                'email' => 'sarah.johnson@trainingsystem.com',
                'password' => Hash::make('trainer123'),
                'email_verified_at' => now(),
                'trainer_data' => [
                    'cnic' => '98765-4321098-7',
                    'gender' => 'female',
                    'contact' => '+0987654321',
                    'specialization' => 'Data Science',
                    'experience_years' => 12,
                    'qualification' => 'PhD Data Analytics',
                    'phone' => '+0987654321',
                    'address' => '456 Expert Avenue, Town'
                ]
            ],
            [
                'name' => 'Coach David Wilson',
                'email' => 'david.wilson@trainingsystem.com',
                'password' => Hash::make('trainer123'),
                'email_verified_at' => now(),
                'trainer_data' => [
                    'cnic' => '12345-6784012-3',
                    'gender' => 'male',
                    'contact' => '+1122334455',
                    'specialization' => 'Mobile Development',
                    'experience_years' => 6,
                    'qualification' => 'BSc Software Engineering',
                    'phone' => '+1122334455',
                    'address' => '789 Mobile Road, Village'
                ]
            ]
        ];

        foreach ($trainers as $trainerData) {
            $user = User::create([
                'name' => $trainerData['name'],
                'email' => $trainerData['email'],
                'password' => $trainerData['password'],
                'email_verified_at' => $trainerData['email_verified_at'],
            ]);

            $user->assignRole('trainer');
            
            $trainer = Trainer::create(array_merge(
                ['user_id' => $user->id],
                $trainerData['trainer_data']
            ));

            
        }
    }
}
