<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CourseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🎓 Creating Courses...');

        // Create specific courses with predefined data for better testing
        $specificCourses = [
            [
                'title' => 'Introduction to Web Development',
                'description' => 'Learn the fundamentals of web development including HTML, CSS, and JavaScript. Perfect for beginners who want to start their journey in web development.',
                'code' => 'WEB101',
                'duration_hours' => 40,
                'difficulty_level' => 'beginner',
                'is_active' => true,
                'price' => 299.99,
                'instructor' => 'Dr. Sarah Johnson',
            ],
            [
                'title' => 'Advanced Database Design',
                'description' => 'Master advanced database concepts including normalization, indexing, and performance optimization. Suitable for experienced developers.',
                'code' => 'DB301',
                'duration_hours' => 64,
                'difficulty_level' => 'advanced',
                'is_active' => true,
                'price' => 899.99,
                'instructor' => 'Prof. Michael Chen',
            ],
            [
                'title' => 'Machine Learning Fundamentals',
                'description' => 'Introduction to machine learning algorithms, data preprocessing, and model evaluation using Python and scikit-learn.',
                'code' => 'ML201',
                'duration_hours' => 80,
                'difficulty_level' => 'intermediate',
                'is_active' => true,
                'price' => 1299.99,
                'instructor' => 'Dr. Emily Rodriguez',
            ],
            [
                'title' => 'Free Programming Bootcamp',
                'description' => 'A comprehensive free course covering programming fundamentals. No prerequisites required.',
                'code' => 'FREE101',
                'duration_hours' => 32,
                'difficulty_level' => 'beginner',
                'is_active' => true,
                'price' => null, // Free course
                'instructor' => 'John Anderson',
            ],
            [
                'title' => 'Legacy System Maintenance',
                'description' => 'Working with legacy systems and outdated technologies. Currently not offered.',
                'code' => 'LEG199',
                'duration_hours' => 24,
                'difficulty_level' => 'advanced',
                'is_active' => false, // Inactive course
                'price' => 599.99,
                'instructor' => 'Mark Thompson',
            ],
        ];

        // Create the specific courses first
        foreach ($specificCourses as $courseData) {
            $course = Course::firstOrCreate(
                ['code' => $courseData['code']], // Find by unique code
                $courseData // Create with this data if not found
            );
            
            if ($course->wasRecentlyCreated) {
                $this->command->info("✅ Created course: {$courseData['title']}");
            } else {
                $this->command->info("ℹ️ Course already exists: {$courseData['title']}");
            }
        }

        // Create additional random courses to reach 20 total
        $remainingCount = 20 - count($specificCourses);
        
        Course::factory($remainingCount)->create();
        
        $this->command->info("✅ Created {$remainingCount} additional random courses");
        $this->command->info("🎉 Total: 20 courses created successfully!");
    }
}
