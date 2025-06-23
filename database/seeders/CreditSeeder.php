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
            $credit = Credit::firstOrCreate(
                ['code' => $creditData['code']], // Find by unique code
                $creditData // Create with this data if not found
            );
            
            if ($credit->wasRecentlyCreated) {
                $this->command->info("✅ Created credit: {$creditData['name']}");
            } else {
                $this->command->info("ℹ️ Credit already exists: {$creditData['name']}");
            }
        }

        // Create additional credits only if needed
        $existingCount = Credit::count();
        $targetCount = 40;
        $remainingCount = max(0, $targetCount - $existingCount);
        
        if ($remainingCount > 0) {
            // Distribute remaining credits by type
            $academicCount = min(12, $remainingCount);
            $professionalCount = min(15, max(0, $remainingCount - $academicCount));
            $continuingCount = min(8, max(0, $remainingCount - $academicCount - $professionalCount));
            $randomCount = max(0, $remainingCount - $academicCount - $professionalCount - $continuingCount);
            
            if ($academicCount > 0) {
                $this->command->info("📚 Creating {$academicCount} Academic Credits...");
                Credit::factory($academicCount)->academic()->create();
            }
            
            if ($professionalCount > 0) {
                $this->command->info("💼 Creating {$professionalCount} Professional Credits...");
                Credit::factory($professionalCount)->professional()->create();
            }
            
            if ($continuingCount > 0) {
                $this->command->info("🎓 Creating {$continuingCount} Continuing Education Credits...");
                Credit::factory($continuingCount)->create(['credit_type' => 'continuing_education']);
            }
            
            if ($randomCount > 0) {
                Credit::factory($randomCount)->create();
                $this->command->info("✅ Created {$randomCount} additional random credits");
            }
        } else {
            $this->command->info("ℹ️ Target count already reached");
        }

        $finalCount = Credit::count();
        $this->command->info("🎉 Total: {$finalCount} credits in database!");
    }
}
