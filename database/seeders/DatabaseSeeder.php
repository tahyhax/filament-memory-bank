<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Database Seeding...');
        
        // Create basic entities first
        $this->call([
            CourseSeeder::class,
            CreditSeeder::class,
            SpecialtySeeder::class,
        ]);
        
        // Create relationships after entities exist
        $this->call([
            CourseRelationSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('📊 Database Seeding Summary:');
        $this->command->info('✅ 20 Courses created');
        $this->command->info('✅ 40 Credits created');
        $this->command->info('✅ 40 Specialties created');
        $this->command->info('✅ Polymorphic relationships established');
        $this->command->info('');
        $this->command->info('🎉 Database seeding completed successfully!');
        $this->command->info('🔗 Visit http://localhost:8080/admin to manage data');
    }
}
