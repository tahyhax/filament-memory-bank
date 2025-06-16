<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Credit;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CreditSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏆 Creating Credits...');

        // Create specific credits with predefined data for better testing
        $specificCredits = [
            [
                'name' => 'Web Development Foundation Certificate',
                'description' => 'Certification awarded upon completion of basic web development skills including HTML, CSS, and JavaScript.',
                'code' => 'WD-1001',
                'credit_points' => 3,
                'credit_type' => 'academic',
                'issuing_authority' => 'Tech Education Board',
                'is_active' => true,
                'requirements' => ['completed_courses' => ['minimum' => 1], 'assessment_score' => 75],
            ],
            [
                'name' => 'Database Design Professional Credit',
                'description' => 'Advanced certification for database design and optimization expertise.',
                'code' => 'DB-3001',
                'credit_points' => 8,
                'credit_type' => 'professional',
                'issuing_authority' => 'Professional Development Institute',
                'is_active' => true,
                'requirements' => ['experience_years' => 2, 'portfolio_projects' => 5],
            ],
            [
                'name' => 'Machine Learning Specialist Certificate',
                'description' => 'Professional certification in machine learning algorithms and implementation.',
                'code' => 'ML-2001',
                'credit_points' => 6,
                'credit_type' => 'professional',
                'issuing_authority' => 'Digital Skills Academy',
                'is_active' => true,
                'requirements' => ['capstone_project' => true, 'practical_exam' => true],
            ],
            [
                'name' => 'Programming Fundamentals Credit',
                'description' => 'Basic programming certification for beginners.',
                'code' => 'PG-1001',
                'credit_points' => 2,
                'credit_type' => 'academic',
                'issuing_authority' => 'Education Standards Authority',
                'is_active' => true,
                'requirements' => ['completed_courses' => ['minimum' => 1], 'quiz_score' => 80],
            ],
            [
                'name' => 'Expired Legacy Technology Credit',
                'description' => 'Credit for legacy system maintenance. No longer active.',
                'code' => 'LG-1999',
                'credit_points' => 4,
                'credit_type' => 'continuing_education',
                'issuing_authority' => 'Industry Standards Board',
                'is_active' => false, // Inactive credit
                'requirements' => ['mentor_recommendation' => true],
            ],
        ];

        // Create the specific credits first
        foreach ($specificCredits as $creditData) {
            Credit::factory()->create($creditData);
            $this->command->info("✅ Created credit: {$creditData['name']}");
        }

        // Create different types of credits
        $this->command->info('📚 Creating Academic Credits...');
        Credit::factory(12)->academic()->create();

        $this->command->info('💼 Creating Professional Credits...');
        Credit::factory(15)->professional()->create();

        $this->command->info('🎓 Creating Continuing Education Credits...');
        Credit::factory(8)->create(['credit_type' => 'continuing_education']);

        $remainingCount = 40 - count($specificCredits) - 12 - 15 - 8;
        if ($remainingCount > 0) {
            Credit::factory($remainingCount)->create();
            $this->command->info("✅ Created {$remainingCount} additional random credits");
        }

        $this->command->info("🎉 Total: 40 credits created successfully!");
    }
}
