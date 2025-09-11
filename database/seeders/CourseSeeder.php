<?php
// database/seeders/CourseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Trainer; // Add this import

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all trainers to assign to courses
        $trainers = Trainer::all();
        
        if ($trainers->isEmpty()) {
            $this->command->error('No trainers found! Please seed trainers first.');
            return;
        }

        $courses = [
            [
                'code' => 'WDF101',
                'name' => 'Web Development Fundamentals',
                'description' => 'Learn the basics of web development including HTML, CSS, JavaScript and modern frameworks.',
                'duration' => 120, // hours
                'method' => 'CBT',
                'field' => 'Information Technology',
                'trainer_id' => $trainers->where('field', 'like', '%IT%')->first()->id ?? $trainers->random()->id
            ],
            [
                'code' => 'DSP202',
                'name' => 'Data Science with Python',
                'description' => 'Comprehensive course on data analysis, machine learning, and data visualization using Python.',
                'duration' => 160,
                'method' => 'Traditional',
                'field' => 'Data Science',
                'trainer_id' => $trainers->where('field', 'like', '%Data%')->first()->id ?? $trainers->random()->id
            ],
            [
                'code' => 'MAD303',
                'name' => 'Mobile App Development',
                'description' => 'Build native and cross-platform mobile applications for iOS and Android platforms.',
                'duration' => 140,
                'method' => 'CBT',
                'field' => 'Mobile Development',
                'trainer_id' => $trainers->where('field', 'like', '%Mobile%')->first()->id ?? $trainers->random()->id
            ],
            [
                'code' => 'DMK404',
                'name' => 'Digital Marketing',
                'description' => 'Master digital marketing strategies including SEO, SEM, social media marketing and analytics.',
                'duration' => 100,
                'method' => 'Traditional',
                'field' => 'Marketing',
                'trainer_id' => $trainers->where('field', 'like', '%Marketing%')->first()->id ?? $trainers->random()->id
            ],
            [
                'code' => 'GDF505',
                'name' => 'Graphic Design',
                'description' => 'Learn professional graphic design skills using industry-standard tools and software.',
                'duration' => 90,
                'method' => 'CBT',
                'field' => 'Design',
                'trainer_id' => $trainers->where('field', 'like', '%Design%')->first()->id ?? $trainers->random()->id
            ],
            [
                'code' => 'NWA606',
                'name' => 'Network Administration',
                'description' => 'Comprehensive training in network setup, configuration, security and maintenance.',
                'duration' => 180,
                'method' => 'Traditional',
                'field' => 'Networking',
                'trainer_id' => $trainers->where('field', 'like', '%Network%')->first()->id ?? $trainers->random()->id
            ],
            [
                'code' => 'DBM707',
                'name' => 'Database Management',
                'description' => 'Learn SQL, database design, optimization and administration of relational databases.',
                'duration' => 110,
                'method' => 'CBT',
                'field' => 'Database',
                'trainer_id' => $trainers->where('field', 'like', '%Database%')->first()->id ?? $trainers->random()->id
            ],
            [
                'code' => 'CCP808',
                'name' => 'Cloud Computing',
                'description' => 'Introduction to cloud platforms, services, deployment models and cloud security.',
                'duration' => 130,
                'method' => 'Traditional',
                'field' => 'Cloud Computing',
                'trainer_id' => $trainers->where('field', 'like', '%Cloud%')->first()->id ?? $trainers->random()->id
            ],
            [
                'code' => 'CSF909',
                'name' => 'Cybersecurity Fundamentals',
                'description' => 'Learn essential cybersecurity concepts, threat detection, and protection mechanisms.',
                'duration' => 150,
                'method' => 'CBT',
                'field' => 'Security',
                'trainer_id' => $trainers->where('field', 'like', '%Security%')->first()->id ?? $trainers->random()->id
            ],
            [
                'code' => 'PMT010',
                'name' => 'Project Management',
                'description' => 'Master project management methodologies, tools, and best practices for successful project delivery.',
                'duration' => 95,
                'method' => 'Traditional',
                'field' => 'Management',
                'trainer_id' => $trainers->where('field', 'like', '%Management%')->first()->id ?? $trainers->random()->id
            ]
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }

        $this->command->info('Courses seeded successfully with trainer assignments!');
    }
}