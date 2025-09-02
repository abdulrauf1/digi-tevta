<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'name' => 'Web Development Fundamentals',
                'description' => 'Learn the basics of web development including HTML, CSS, JavaScript and modern frameworks.',
                'duration' => 120, // hours
                'method' => 'CBT',
                'field' => 'Information Technology'
            ],
            [
                'name' => 'Data Science with Python',
                'description' => 'Comprehensive course on data analysis, machine learning, and data visualization using Python.',
                'duration' => 160,
                'method' => 'Traditional',
                'field' => 'Data Science'
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'Build native and cross-platform mobile applications for iOS and Android platforms.',
                'duration' => 140,
                'method' => 'CBT',
                'field' => 'Mobile Development'
            ],
            [
                'name' => 'Digital Marketing',
                'description' => 'Master digital marketing strategies including SEO, SEM, social media marketing and analytics.',
                'duration' => 100,
                'method' => 'Traditional',
                'field' => 'Marketing'
            ],
            [
                'name' => 'Graphic Design',
                'description' => 'Learn professional graphic design skills using industry-standard tools and software.',
                'duration' => 90,
                'method' => 'CBT',
                'field' => 'Design'
            ],
            [
                'name' => 'Network Administration',
                'description' => 'Comprehensive training in network setup, configuration, security and maintenance.',
                'duration' => 180,
                'method' => 'Traditional',
                'field' => 'Networking'
            ],
            [
                'name' => 'Database Management',
                'description' => 'Learn SQL, database design, optimization and administration of relational databases.',
                'duration' => 110,
                'method' => 'CBT',
                'field' => 'Database'
            ],
            [
                'name' => 'Cloud Computing',
                'description' => 'Introduction to cloud platforms, services, deployment models and cloud security.',
                'duration' => 130,
                'method' => 'Traditional',
                'field' => 'Cloud Computing'
            ],
            [
                'name' => 'Cybersecurity Fundamentals',
                'description' => 'Learn essential cybersecurity concepts, threat detection, and protection mechanisms.',
                'duration' => 150,
                'method' => 'CBT',
                'field' => 'Security'
            ],
            [
                'name' => 'Project Management',
                'description' => 'Master project management methodologies, tools, and best practices for successful project delivery.',
                'duration' => 95,
                'method' => 'Traditional',
                'field' => 'Management'
            ]
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }

        $this->command->info('Courses seeded successfully!');
    }
}
