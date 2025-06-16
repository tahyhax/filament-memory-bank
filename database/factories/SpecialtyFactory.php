<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Specialty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specialty>
 */
class SpecialtyFactory extends Factory
{
    protected $model = Specialty::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $specialtyNames = [
            'Full Stack Developer',
            'Data Scientist',
            'DevOps Engineer',
            'UX/UI Designer',
            'Cybersecurity Analyst',
            'Machine Learning Engineer',
            'Cloud Architect',
            'Database Administrator',
            'Software Quality Assurance',
            'Mobile App Developer',
            'Business Intelligence Analyst',
            'System Administrator',
            'Product Manager',
            'Digital Marketing Specialist',
            'Network Engineer',
            'Frontend Developer',
            'Backend Developer',
            'API Developer',
            'Technical Writer',
            'Agile Coach',
        ];

        $industries = [
            'Technology',
            'Healthcare',
            'Finance',
            'E-commerce',
            'Education',
            'Manufacturing',
            'Consulting',
            'Media & Entertainment',
            'Government',
            'Retail',
        ];

        $skills = [
            'technical' => ['JavaScript', 'Python', 'Java', 'React', 'Vue.js', 'Node.js', 'SQL', 'Docker', 'Kubernetes', 'AWS'],
            'business' => ['Project Management', 'Strategic Planning', 'Business Analysis', 'Market Research', 'Leadership'],
            'creative' => ['Adobe Creative Suite', 'Figma', 'Sketch', 'Prototyping', 'User Research', 'Visual Design'],
            'healthcare' => ['Medical Terminology', 'HIPAA Compliance', 'Clinical Research', 'Healthcare IT'],
            'education' => ['Curriculum Development', 'Learning Management Systems', 'Educational Technology', 'Assessment Design'],
        ];

        $certificationBodies = [
            'AWS Certification',
            'Microsoft Azure',
            'Google Cloud Platform',
            'Cisco Networking',
            'CompTIA Security+',
            'Scrum Alliance',
            'Adobe Certified Expert',
            'Oracle Certified Professional',
            'Salesforce Certified',
            'Industry Standards Board',
        ];

        $name = $this->faker->randomElement($specialtyNames);
        $category = $this->faker->randomElement(['technical', 'business', 'creative', 'healthcare', 'education']);
        $code = strtoupper($this->faker->lexify('???')) . '-' . $this->faker->numberBetween(100, 999);

        return [
            'name' => $name,
            'description' => $this->faker->paragraph(2),
            'code' => $code,
            'specialty_category' => $category,
            'industry' => $this->faker->randomElement($industries),
            'required_experience_years' => $this->faker->randomElement([0, 1, 2, 3, 5, 8]),
            'certification_required' => $this->faker->boolean(40), // 40% require certification
            'is_active' => $this->faker->boolean(85), // 85% chance of being active
            'skills_required' => $this->faker->randomElements($skills[$category] ?? $skills['technical'], $this->faker->numberBetween(2, min(4, count($skills[$category] ?? $skills['technical'])))),
            'certification_body' => $this->faker->optional(0.4)->randomElement($certificationBodies),
        ];
    }

    /**
     * Indicate that the specialty is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the specialty is technical.
     */
    public function technical(): static
    {
        return $this->state(fn (array $attributes) => [
            'specialty_category' => 'technical',
        ]);
    }

    /**
     * Indicate that the specialty requires certification.
     */
    public function requiresCertification(): static
    {
        return $this->state(fn (array $attributes) => [
            'certification_required' => true,
            'certification_body' => $this->faker->randomElement([
                'AWS Certification',
                'Microsoft Azure',
                'Google Cloud Platform',
                'Cisco Networking',
                'CompTIA Security+',
            ]),
        ]);
    }

    /**
     * Indicate that the specialty is entry level.
     */
    public function entryLevel(): static
    {
        return $this->state(fn (array $attributes) => [
            'required_experience_years' => 0,
        ]);
    }

    /**
     * Indicate that the specialty is senior level.
     */
    public function seniorLevel(): static
    {
        return $this->state(fn (array $attributes) => [
            'required_experience_years' => $this->faker->numberBetween(5, 10),
        ]);
    }
}
