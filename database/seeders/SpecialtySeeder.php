<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SpecialtySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('⭐ Creating Specialties...');

        // Create specific specialties with predefined data for better testing
        $specificSpecialties = [
            [
                'name' => 'Frontend Web Developer',
                'description' => 'Specialization in creating user interfaces and user experiences for web applications using modern frameworks.',
                'code' => 'FE-101',
                'specialty_category' => 'technical',
                'industry' => 'Technology',
                'required_experience_years' => 1,
                'certification_required' => false,
                'is_active' => true,
                'skills_required' => ['HTML', 'CSS', 'JavaScript', 'React', 'Vue.js'],
            ],
            [
                'name' => 'Senior Database Architect',
                'description' => 'Advanced specialization in designing and optimizing complex database systems for enterprise applications.',
                'code' => 'DBA-301',
                'specialty_category' => 'technical',
                'industry' => 'Technology',
                'required_experience_years' => 8,
                'certification_required' => true,
                'is_active' => true,
                'skills_required' => ['PostgreSQL', 'MySQL', 'MongoDB', 'Redis', 'Database Design'],
                'certification_body' => 'Oracle Certified Professional',
            ],
            [
                'name' => 'Machine Learning Specialist',
                'description' => 'Expertise in developing and implementing machine learning solutions for various business applications.',
                'code' => 'ML-201',
                'specialty_category' => 'technical',
                'industry' => 'Technology',
                'required_experience_years' => 3,
                'certification_required' => true,
                'is_active' => true,
                'skills_required' => ['Python', 'TensorFlow', 'PyTorch', 'Scikit-learn', 'Data Analysis'],
                'certification_body' => 'AWS Certification',
            ],
            [
                'name' => 'Entry Level Programmer',
                'description' => 'Beginning programmer role suitable for recent graduates or career changers.',
                'code' => 'PG-001',
                'specialty_category' => 'technical',
                'industry' => 'Technology',
                'required_experience_years' => 0,
                'certification_required' => false,
                'is_active' => true,
                'skills_required' => ['Programming Logic', 'Basic Syntax', 'Problem Solving'],
            ],
            [
                'name' => 'Legacy COBOL Developer',
                'description' => 'Maintenance of legacy COBOL systems. Currently not in demand.',
                'code' => 'LG-199',
                'specialty_category' => 'technical',
                'industry' => 'Finance',
                'required_experience_years' => 10,
                'certification_required' => false,
                'is_active' => false, // Inactive specialty
                'skills_required' => ['COBOL', 'Mainframe', 'Legacy Systems'],
            ],
        ];

        // Create the specific specialties first
        foreach ($specificSpecialties as $specialtyData) {
            $specialty = Specialty::firstOrCreate(
                ['code' => $specialtyData['code']], // Find by unique code
                $specialtyData // Create with this data if not found
            );
            
            if ($specialty->wasRecentlyCreated) {
                $this->command->info("✅ Created specialty: {$specialtyData['name']}");
            } else {
                $this->command->info("ℹ️ Specialty already exists: {$specialtyData['name']}");
            }
        }

        // Create specialties by category
        $this->command->info('💻 Creating Technical Specialties...');
        Specialty::factory(20)->technical()->create();

        $this->command->info('💼 Creating Business Specialties...');
        Specialty::factory(8)->create(['specialty_category' => 'business']);

        $this->command->info('🎨 Creating Creative Specialties...');
        Specialty::factory(4)->create(['specialty_category' => 'creative']);

        $this->command->info('🏥 Creating Healthcare Specialties...');
        Specialty::factory(2)->create(['specialty_category' => 'healthcare']);

        $this->command->info('🎓 Creating Education Specialties...');
        Specialty::factory(1)->create(['specialty_category' => 'education']);

        $remainingCount = 40 - count($specificSpecialties) - 20 - 8 - 4 - 2 - 1;
        if ($remainingCount > 0) {
            Specialty::factory($remainingCount)->create();
            $this->command->info("✅ Created {$remainingCount} additional random specialties");
        }

        $finalCount = Specialty::count();
        $this->command->info("🎉 Total: {$finalCount} specialties in database!");
    }
}
