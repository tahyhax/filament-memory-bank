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
 * @property int $credit_points
 * @property string $credit_type
 * @property string $issuing_authority
 * @property bool $is_active
 * @property Carbon|null $valid_from
 * @property Carbon|null $valid_until
 * @property array|null $requirements
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Credit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'code',
        'credit_points',
        'credit_type',
        'issuing_authority',
        'is_active',
        'valid_from',
        'valid_until',
        'requirements',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credit_points' => 'integer',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'requirements' => 'array',
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

    public function awardedByCourses(): MorphToMany
    {
        return $this->courses()->wherePivot('relation_type', 'awarded');
    }

    public function recommendedForCourses(): MorphToMany
    {
        return $this->courses()->wherePivot('relation_type', 'recommended');
    }

    // Accessors
    protected function formattedCreditPoints(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->credit_points . ' credit' . ($this->credit_points !== 1 ? 's' : ''),
        );
    }

    protected function isValid(): Attribute
    {
        return Attribute::make(
            get: function (): bool {
                $now = now();
                $validFrom = $this->valid_from === null || $now->gte($this->valid_from);
                $validUntil = $this->valid_until === null || $now->lte($this->valid_until);
                
                return $validFrom && $validUntil;
            },
        );
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('credit_type', $type);
    }

    public function scopeValid($query)
    {
        $now = now();
        return $query->where('is_active', true)
                    ->where(function ($q) use ($now) {
                        $q->whereNull('valid_from')
                          ->orWhere('valid_from', '<=', $now);
                    })
                    ->where(function ($q) use ($now) {
                        $q->whereNull('valid_until')
                          ->orWhere('valid_until', '>=', $now);
                    });
    }
}
