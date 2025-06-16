<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('specialties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('code')->unique();
            $table->enum('specialty_category', ['technical', 'business', 'creative', 'healthcare', 'education']);
            $table->string('industry')->nullable();
            $table->integer('required_experience_years')->default(0);
            $table->boolean('certification_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('skills_required')->nullable();
            $table->string('certification_body')->nullable();
            $table->timestamps();
            
            $table->index(['is_active', 'specialty_category']);
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specialties');
    }
};
