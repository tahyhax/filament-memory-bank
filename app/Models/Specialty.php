<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $code
 * @property string $specialty_category
 * @property string|null $industry
 * @property int $required_experience_years
 * @property bool $certification_required
 * @property bool $is_active
 * @property array|null $skills_required
 * @property string|null $certification_body
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Specialty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'code',
        'specialty_category',
        'industry',
        'required_experience_years',
        'certification_required',
        'is_active',
        'skills_required',
        'certification_body',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'certification_required' => 'boolean',
        'required_experience_years' => 'integer',
        'skills_required' => 'array',
    ];

    // Polymorphic relationship to Courses
    public function courses(): MorphToMany
    {
        return $this->morphToMany(
            Course::class, 
            'relatable', 
            'course_relations'
        )->withPivot(['relation_type', 'notes', 'is_required', 'weight'])
         ->withTimestamps();
    }

    // Specific relation types
    public function prerequisiteForCourses(): MorphToMany
    {
        return $this->courses()->wherePivot('relation_type', 'prerequisite');
    }

    public function recommendedCourses(): MorphToMany
    {
        return $this->courses()->wherePivot('relation_type', 'recommended');
    }

    public function corequisiteCourses(): MorphToMany
    {
        return $this->courses()->wherePivot('relation_type', 'corequisite');
    }

    // Accessors
    protected function formattedExperience(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if ($this->required_experience_years === 0) {
                    return 'No experience required';
                }
                
                $years = $this->required_experience_years;
                return $years . ' year' . ($years !== 1 ? 's' : '') . ' experience required';
            },
        );
    }

    protected function skillsList(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->skills_required 
                ? implode(', ', $this->skills_required) 
                : 'No specific skills listed',
        );
    }

    protected function categoryFormatted(): Attribute
    {
        return Attribute::make(
            get: fn (): string => ucfirst(str_replace('_', ' ', $this->specialty_category)),
        );
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('specialty_category', $category);
    }

    public function scopeRequiresCertification($query)
    {
        return $query->where('certification_required', true);
    }

    public function scopeByExperienceLevel($query, int $maxYears)
    {
        return $query->where('required_experience_years', '<=', $maxYears);
    }

    public function scopeByIndustry($query, string $industry)
    {
        return $query->where('industry', $industry);
    }
}
