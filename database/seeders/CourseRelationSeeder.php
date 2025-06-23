<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Credit;
use App\Models\Specialty;
use App\Models\CourseRelation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CourseRelationSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔗 Creating Course Relations...');

        $courses = Course::all();
        $credits = Credit::all();
        $specialties = Specialty::all();

        if ($courses->isEmpty() || $credits->isEmpty() || $specialties->isEmpty()) {
            $this->command->error('❌ Please run Course, Credit, and Specialty seeders first!');
            return;
        }

        // Create specific meaningful relationships
        $this->createSpecificRelationships();

        // Create random relationships
        $this->createRandomRelationships($courses, $credits, $specialties);

        $this->command->info("🎉 Course relations created successfully!");
    }

    private function createSpecificRelationships(): void
    {
        $this->command->info('📌 Creating specific relationships...');

        // Web Development Course relationships
        $webCourse = Course::where('code', 'WEB101')->first();
        $webCredit = Credit::where('code', 'WD-1001')->first();
        $frontendSpecialty = Specialty::where('code', 'FE-101')->first();
        $entryProgrammer = Specialty::where('code', 'PG-001')->first();

        if ($webCourse && $webCredit) {
            // Check if relationship already exists
            $exists = $webCourse->credits()
                ->wherePivot('relatable_id', $webCredit->id)
                ->wherePivot('relatable_type', Credit::class)
                ->wherePivot('relation_type', 'awarded')
                ->exists();
                
            if (!$exists) {
                // Web course awards web development credit
                $webCourse->credits()->attach($webCredit->id, [
                    'relation_type' => 'awarded',
                    'is_required' => true,
                    'weight' => 1,
                    'notes' => 'Automatically awarded upon course completion with 80% grade',
                ]);
                $this->command->info("✅ {$webCourse->title} → awards → {$webCredit->name}");
            } else {
                $this->command->info("ℹ️ Relationship already exists: {$webCourse->title} → awards → {$webCredit->name}");
            }
        }

        if ($webCourse && $frontendSpecialty) {
            $exists = $webCourse->specialties()
                ->wherePivot('relatable_id', $frontendSpecialty->id)
                ->wherePivot('relatable_type', Specialty::class)
                ->wherePivot('relation_type', 'recommended')
                ->exists();
                
            if (!$exists) {
                // Web course is recommended for frontend specialty
                $webCourse->specialties()->attach($frontendSpecialty->id, [
                    'relation_type' => 'recommended',
                    'is_required' => false,
                    'weight' => 1,
                    'notes' => 'Strongly recommended for frontend development path',
                ]);
                $this->command->info("✅ {$webCourse->title} → recommended for → {$frontendSpecialty->name}");
            } else {
                $this->command->info("ℹ️ Relationship already exists: {$webCourse->title} → recommended for → {$frontendSpecialty->name}");
            }
        }

        if ($webCourse && $entryProgrammer) {
            $exists = $webCourse->specialties()
                ->wherePivot('relatable_id', $entryProgrammer->id)
                ->wherePivot('relatable_type', Specialty::class)
                ->wherePivot('relation_type', 'prerequisite')
                ->exists();
                
            if (!$exists) {
                // Entry programmer specialty is prerequisite for web course
                $webCourse->specialties()->attach($entryProgrammer->id, [
                    'relation_type' => 'prerequisite',
                    'is_required' => true,
                    'weight' => 1,
                    'notes' => 'Basic programming knowledge required',
                ]);
                $this->command->info("✅ {$entryProgrammer->name} → prerequisite for → {$webCourse->title}");
            } else {
                $this->command->info("ℹ️ Relationship already exists: {$entryProgrammer->name} → prerequisite for → {$webCourse->title}");
            }
        }

        // Database Course relationships
        $dbCourse = Course::where('code', 'DB301')->first();
        $dbCredit = Credit::where('code', 'DB-3001')->first();
        $dbSpecialty = Specialty::where('code', 'DBA-301')->first();

        if ($dbCourse && $dbCredit) {
            $exists = $dbCourse->credits()
                ->wherePivot('relatable_id', $dbCredit->id)
                ->wherePivot('relatable_type', Credit::class)
                ->wherePivot('relation_type', 'awarded')
                ->exists();
                
            if (!$exists) {
                $dbCourse->credits()->attach($dbCredit->id, [
                    'relation_type' => 'awarded',
                    'is_required' => true,
                    'weight' => 1,
                    'notes' => 'Professional certification awarded upon completion',
                ]);
                $this->command->info("✅ {$dbCourse->title} → awards → {$dbCredit->name}");
            } else {
                $this->command->info("ℹ️ Relationship already exists: {$dbCourse->title} → awards → {$dbCredit->name}");
            }
        }

        if ($dbCourse && $dbSpecialty) {
            $exists = $dbCourse->specialties()
                ->wherePivot('relatable_id', $dbSpecialty->id)
                ->wherePivot('relatable_type', Specialty::class)
                ->wherePivot('relation_type', 'recommended')
                ->exists();
                
            if (!$exists) {
                $dbCourse->specialties()->attach($dbSpecialty->id, [
                    'relation_type' => 'recommended',
                    'is_required' => false,
                    'weight' => 1,
                    'notes' => 'Advanced database architecture specialization',
                ]);
                $this->command->info("✅ {$dbCourse->title} → recommended for → {$dbSpecialty->name}");
            } else {
                $this->command->info("ℹ️ Relationship already exists: {$dbCourse->title} → recommended for → {$dbSpecialty->name}");
            }
        }

        // Machine Learning Course relationships
        $mlCourse = Course::where('code', 'ML201')->first();
        $mlCredit = Credit::where('code', 'ML-2001')->first();
        $mlSpecialty = Specialty::where('code', 'ML-201')->first();

        if ($mlCourse && $mlCredit) {
            $exists = $mlCourse->credits()
                ->wherePivot('relatable_id', $mlCredit->id)
                ->wherePivot('relatable_type', Credit::class)
                ->wherePivot('relation_type', 'awarded')
                ->exists();
                
            if (!$exists) {
                $mlCourse->credits()->attach($mlCredit->id, [
                    'relation_type' => 'awarded',
                    'is_required' => true,
                    'weight' => 1,
                    'notes' => 'ML specialist certification upon successful completion',
                ]);
                $this->command->info("✅ {$mlCourse->title} → awards → {$mlCredit->name}");
            } else {
                $this->command->info("ℹ️ Relationship already exists: {$mlCourse->title} → awards → {$mlCredit->name}");
            }
        }

        if ($mlCourse && $mlSpecialty) {
            $exists = $mlCourse->specialties()
                ->wherePivot('relatable_id', $mlSpecialty->id)
                ->wherePivot('relatable_type', Specialty::class)
                ->wherePivot('relation_type', 'recommended')
                ->exists();
                
            if (!$exists) {
                $mlCourse->specialties()->attach($mlSpecialty->id, [
                    'relation_type' => 'recommended',
                    'is_required' => false,
                    'weight' => 1,
                    'notes' => 'Perfect for ML career advancement',
                ]);
                $this->command->info("✅ {$mlCourse->title} → recommended for → {$mlSpecialty->name}");
            } else {
                $this->command->info("ℹ️ Relationship already exists: {$mlCourse->title} → recommended for → {$mlSpecialty->name}");
            }
        }
    }

    private function createRandomRelationships($courses, $credits, $specialties): void
    {
        $this->command->info('🎲 Creating random relationships...');

        $relationTypes = ['prerequisite', 'corequisite', 'recommended', 'awarded'];
        $relationshipCount = 0;

        // Each course gets 2-4 relationships with credits
        foreach ($courses as $course) {
            $creditCount = rand(2, 4);
            $selectedCredits = $credits->random($creditCount);

            foreach ($selectedCredits as $credit) {
                // Avoid duplicates
                $exists = $course->credits()
                    ->wherePivot('relatable_id', $credit->id)
                    ->wherePivot('relatable_type', Credit::class)
                    ->exists();

                if (!$exists) {
                    $relationType = fake()->randomElement($relationTypes);
                    $isRequired = $relationType === 'prerequisite' || $relationType === 'corequisite';

                    $course->credits()->attach($credit->id, [
                        'relation_type' => $relationType,
                        'is_required' => $isRequired,
                        'weight' => rand(1, 5),
                        'notes' => fake()->optional(0.3)->sentence(),
                    ]);
                    $relationshipCount++;
                }
            }
        }

        // Each course gets 1-3 relationships with specialties
        foreach ($courses as $course) {
            $specialtyCount = rand(1, 3);
            $selectedSpecialties = $specialties->random($specialtyCount);

            foreach ($selectedSpecialties as $specialty) {
                // Avoid duplicates
                $exists = $course->specialties()
                    ->wherePivot('relatable_id', $specialty->id)
                    ->wherePivot('relatable_type', Specialty::class)
                    ->exists();

                if (!$exists) {
                    $relationType = fake()->randomElement($relationTypes);
                    $isRequired = $relationType === 'prerequisite' || $relationType === 'corequisite';

                    $course->specialties()->attach($specialty->id, [
                        'relation_type' => $relationType,
                        'is_required' => $isRequired,
                        'weight' => rand(1, 5),
                        'notes' => fake()->optional(0.3)->sentence(),
                    ]);
                    $relationshipCount++;
                }
            }
        }

        $this->command->info("✅ Created {$relationshipCount} random relationships");
    }
}
