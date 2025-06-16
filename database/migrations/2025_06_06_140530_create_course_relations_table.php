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
        Schema::create('course_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->morphs('relatable'); // relatable_id, relatable_type
            $table->enum('relation_type', ['prerequisite', 'corequisite', 'recommended', 'awarded']);
            $table->text('notes')->nullable();
            $table->boolean('is_required')->default(false);
            $table->integer('weight')->default(1); // for ordering/priority
            $table->timestamps();
            
            $table->unique(['course_id', 'relatable_id', 'relatable_type', 'relation_type'], 'course_relations_unique');
            $table->index(['course_id', 'relation_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_relations');
    }
};
