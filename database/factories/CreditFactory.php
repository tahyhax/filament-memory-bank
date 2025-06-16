<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Credit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Credit>
 */
class CreditFactory extends Factory
{
    protected $model = Credit::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $creditNames = [
            'Software Engineering Credit',
            'Database Administration Certification',
            'Web Development Certificate',
            'Data Analytics Professional Credit',
            'Cybersecurity Specialist Certificate',
            'Project Management Credit',
            'Digital Marketing Certificate',
            'Cloud Computing Professional Credit',
            'Machine Learning Specialist Certificate',
            'Mobile Development Credit',
            'UX/UI Design Certificate',
            'DevOps Engineering Credit',
            'Business Analysis Certificate',
            'Quality Assurance Credit',
            'Network Security Certificate',
            'Software Testing Credit',
            'Agile Development Certificate',
            'Database Design Credit',
            'API Development Certificate',
            'System Architecture Credit',
        ];

        $authorities = [
            'Tech Education Board',
            'Professional Development Institute',
            'Digital Skills Academy',
            'Certification Authority',
            'Industry Standards Board',
            'Technology Institute',
            'Skills Development Council',
            'Professional Certification Body',
            'Education Standards Authority',
            'Technical Competency Board',
        ];

        $requirements = [
            ['completed_courses' => ['minimum' => 2], 'experience_years' => 1],
            ['portfolio_projects' => 3, 'assessment_score' => 80],
            ['practical_exam' => true, 'theory_exam' => true],
            ['mentor_recommendation' => true, 'peer_review' => true],
            ['capstone_project' => true, 'presentation' => true],
        ];

        $name = $this->faker->randomElement($creditNames);
        $code = strtoupper($this->faker->lexify('??')) . '-' . $this->faker->numberBetween(1000, 9999);

        return [
            'name' => $name,
            'description' => $this->faker->paragraph(2),
            'code' => $code,
            'credit_points' => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 8, 10]),
            'credit_type' => $this->faker->randomElement(['academic', 'professional', 'continuing_education']),
            'issuing_authority' => $this->faker->randomElement($authorities),
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
            'valid_from' => $this->faker->optional(0.8)->dateTimeBetween('-1 year', 'now'),
            'valid_until' => $this->faker->optional(0.6)->dateTimeBetween('+6 months', '+3 years'),
            'requirements' => $this->faker->randomElement($requirements),
        ];
    }

    /**
     * Indicate that the credit is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the credit is academic type.
     */
    public function academic(): static
    {
        return $this->state(fn (array $attributes) => [
            'credit_type' => 'academic',
        ]);
    }

    /**
     * Indicate that the credit is professional type.
     */
    public function professional(): static
    {
        return $this->state(fn (array $attributes) => [
            'credit_type' => 'professional',
        ]);
    }

    /**
     * Indicate that the credit has no expiration.
     */
    public function permanent(): static
    {
        return $this->state(fn (array $attributes) => [
            'valid_until' => null,
        ]);
    }
}
