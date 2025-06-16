<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

/**
 * @property int $id
 * @property int $course_id
 * @property int $relatable_id
 * @property string $relatable_type
 * @property string $relation_type
 * @property string|null $notes
 * @property bool $is_required
 * @property int $weight
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Course $course
 * @property Credit|Specialty $relatable
 */
class CourseRelation extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'relatable_id',
        'relatable_type',
        'relation_type',
        'notes',
        'is_required',
        'weight',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'weight' => 'integer',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function relatable(): MorphTo
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopePrerequisites($query)
    {
        return $query->where('relation_type', 'prerequisite');
    }

    public function scopeCorequisites($query)
    {
        return $query->where('relation_type', 'corequisite');
    }

    public function scopeRecommended($query)
    {
        return $query->where('relation_type', 'recommended');
    }

    public function scopeAwarded($query)
    {
        return $query->where('relation_type', 'awarded');
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeOrderedByWeight($query)
    {
        return $query->orderBy('weight');
    }
}
