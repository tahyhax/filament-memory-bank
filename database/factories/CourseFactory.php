<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $courseTitles = [
            'Introduction to Programming',
            'Advanced Database Design',
            'Web Development Fundamentals',
            'Machine Learning Basics',
            'Digital Marketing Strategy',
            'Project Management Professional',
            'Cybersecurity Essentials',
            'Data Science with Python',
            'UI/UX Design Principles',
            'Cloud Computing Architecture',
            'Mobile App Development',
            'Business Analytics',
            'Software Testing Automation',
            'DevOps and CI/CD',
            'Artificial Intelligence Ethics',
            'Blockchain Technology',
            'Digital Transformation',
            'Leadership and Management',
            'Financial Analysis',
            'Quality Assurance',
        ];

        $instructors = [
            'Dr. Sarah Johnson',
            'Prof. Michael Chen',
            'Dr. Emily Rodriguez',
            'John Anderson',
            'Dr. Lisa Park',
            'Mark Thompson',
            'Dr. Ahmed Hassan',
            'Jennifer Lee',
            'Dr. Robert Smith',
            'Maria Gonzalez',
        ];

        $title = $this->faker->randomElement($courseTitles);
        $code = strtoupper($this->faker->lexify('???')) . $this->faker->numberBetween(100, 999);

        return [
            'title' => $title,
            'description' => $this->faker->paragraph(3),
            'code' => $code,
            'duration_hours' => $this->faker->randomElement([16, 24, 32, 40, 48, 64, 80]),
            'difficulty_level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
            'is_active' => $this->faker->boolean(85), // 85% chance of being active
            'price' => $this->faker->optional(0.3)->randomFloat(2, 99, 2999), // 30% have price
            'start_date' => $this->faker->optional(0.7)->dateTimeBetween('now', '+6 months'),
            'end_date' => $this->faker->optional(0.7)->dateTimeBetween('+1 month', '+12 months'),
            'instructor' => $this->faker->randomElement($instructors),
        ];
    }

    /**
     * Indicate that the course is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the course is for beginners.
     */
    public function beginner(): static
    {
        return $this->state(fn (array $attributes) => [
            'difficulty_level' => 'beginner',
        ]);
    }

    /**
     * Indicate that the course is free.
     */
    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => null,
        ]);
    }

    /**
     * Indicate that the course is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => $this->faker->randomFloat(2, 199, 1999),
        ]);
    }
}
