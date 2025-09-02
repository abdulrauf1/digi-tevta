<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Trainee;
use App\Models\Course;
use Illuminate\Support\Facades\Hash;


class TraineeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $courses = Course::all();
        
        $trainees = [
            [
                'name' => 'John Student',
                'email' => 'john.student@trainingsystem.com',
                'password' => Hash::make('trainee123'),
                'email_verified_at' => now(),
                'trainee_data' => [
                    'cnic' => '13245-2589012-3',
                    'gender' => 'male',
                    'date_of_birth' => '2000-05-15',
                    'contact' => '+1112223333',
                    'emergency_contact' => '+4445556666',
                    'domicile' => 'Cityville',
                    'education_level' => 'Bachelor\'s Degree',
                    'address' => '101 Student Lane, City'
                ]
            ],
            [
                'name' => 'Emma Learner',
                'email' => 'emma.learner@trainingsystem.com',
                'password' => Hash::make('trainee123'),
                'email_verified_at' => now(),
                'trainee_data' => [
                    'cnic' => '56345-2589012-3',
                    'gender' => 'male',
                    'date_of_birth' => '1998-12-10',
                    'contact' => '+2223334444',
                    'emergency_contact' => '+7778889999',
                    'domicile' => 'Cityville',
                    'education_level' => 'High School',
                    'address' => '202 Learning Street, Town'                    
                ]
            ],
            [
                'name' => 'Alex Trainee',
                'email' => 'alex.trainee@trainingsystem.com',
                'password' => Hash::make('trainee123'),
                'email_verified_at' => now(),
                'trainee_data' => [
                    'cnic' => '13245-2589012-3',
                    'gender' => 'male',
                    'date_of_birth' => '1995-08-22',
                    'contact' => '+3334445555',
                    'emergency_contact' => '+0001112222',
                    'domicile' => 'Cityville',
                    'education_level' => 'Master\'s Degree',
                    'address' => '303 Training Road, Village'                    
                ]
            ],
            // Add more trainees...
            [
                'name' => 'Lisa Novice',
                'email' => 'lisa.novice@trainingsystem.com',
                'password' => Hash::make('trainee123'),
                'email_verified_at' => now(),
                'trainee_data' => [                    
                    'cnic' => '12345-6789012-3',
                    'gender' => 'female',
                    'date_of_birth' => '1999-03-30',
                    'contact' => '+4445556666',
                    'emergency_contact' => '+3332221111',
                    'domicile' => 'Cityville',
                    'education_level' => 'Diploma',
                    'address' => '404 Beginner Avenue, City'
                    
                ]
            ]
        ];

        foreach ($trainees as $traineeData) {
            $user = User::create([
                'name' => $traineeData['name'],
                'email' => $traineeData['email'],
                'password' => $traineeData['password'],
                'email_verified_at' => $traineeData['email_verified_at'],
            ]);

            $user->assignRole('trainee');
            
            // Create trainee profile
            $trainee = Trainee::create(array_merge(
                ['user_id' => $user->id],
                $traineeData['trainee_data']
            ));

            // Assign trainee to a random course
            if ($courses->isNotEmpty()) {
                $trainee->courses()->attach($courses->random()->id);
            }
        }
    }
}
