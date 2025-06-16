<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $code
 * @property int $duration_hours
 * @property string $difficulty_level
 * @property bool $is_active
 * @property float|null $price
 * @property Carbon|null $start_date
 * @property Carbon|null $end_date
 * @property string|null $instructor
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'code',
        'duration_hours',
        'difficulty_level',
        'is_active',
        'price',
        'start_date',
        'end_date',
        'instructor',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'duration_hours' => 'integer',
    ];

    // Polymorphic relationships to Credits
    public function credits(): MorphToMany
    {
        return $this->morphedByMany(
            Credit::class, 
            'relatable', 
            'course_relations'
        )->withPivot(['relation_type', 'notes', 'is_required', 'weight'])
         ->withTimestamps();
    }

    // Polymorphic relationships to Specialties
    public function specialties(): MorphToMany
    {
        return $this->morphedByMany(
            Specialty::class, 
            'relatable', 
            'course_relations'
        )->withPivot(['relation_type', 'notes', 'is_required', 'weight'])
         ->withTimestamps();
    }

    // All course relations
    public function courseRelations(): HasMany
    {
        return $this->hasMany(CourseRelation::class);
    }

    // Specific relation types
    public function prerequisiteCredits(): MorphToMany
    {
        return $this->credits()->wherePivot('relation_type', 'prerequisite');
    }

    public function prerequisiteSpecialties(): MorphToMany
    {
        return $this->specialties()->wherePivot('relation_type', 'prerequisite');
    }

    public function awardedCredits(): MorphToMany
    {
        return $this->credits()->wherePivot('relation_type', 'awarded');
    }

    public function recommendedSpecialties(): MorphToMany
    {
        return $this->specialties()->wherePivot('relation_type', 'recommended');
    }

    // Accessors
    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn (?float $value): string => $this->price ? '$' . number_format($this->price, 2) : 'Free',
        );
    }

    protected function durationFormatted(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->duration_hours . ' hours',
        );
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByDifficulty($query, string $level)
    {
        return $query->where('difficulty_level', $level);
    }
}
