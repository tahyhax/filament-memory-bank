<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Specialty;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class SpecialtyExporter extends Exporter
{
    protected static ?string $model = Specialty::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            
            ExportColumn::make('name')
                ->label('Specialty Name'),
            
            ExportColumn::make('description')
                ->label('Description'),
            
            ExportColumn::make('code')
                ->label('Specialty Code'),
            
            ExportColumn::make('specialty_category')
                ->label('Category')
                ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state))),
            
            ExportColumn::make('industry')
                ->label('Industry'),
            
            ExportColumn::make('required_experience_years')
                ->label('Required Experience (Years)'),
            
            ExportColumn::make('certification_required')
                ->label('Certification Required')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No'),
            
            ExportColumn::make('certification_body')
                ->label('Certification Body'),
            
            ExportColumn::make('is_active')
                ->label('Status')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive'),
            
            ExportColumn::make('skills_required')
                ->label('Required Skills')
                ->formatStateUsing(function ($state): string {
                    if (is_array($state)) {
                        return implode(', ', $state);
                    }
                    if (is_string($state)) {
                        // Try to decode if it's JSON
                        $decoded = json_decode($state, true);
                        if (is_array($decoded)) {
                            return implode(', ', $decoded);
                        }
                        return $state;
                    }
                    return 'None';
                }),
            

            
            // Relationship counts
            ExportColumn::make('courses_count')
                ->label('Related Courses')
                ->counts('courses'),
            
            // Prerequisites and recommendations
            ExportColumn::make('prerequisite_courses')
                ->label('Prerequisite For Courses')
                ->state(function (Specialty $record): string {
                    $courses = $record->courses()
                        ->wherePivot('relation_type', 'prerequisite')
                        ->pluck('title');
                    return $courses->implode(', ') ?: 'None';
                }),
            
            ExportColumn::make('recommended_courses')
                ->label('Recommended For Courses')
                ->state(function (Specialty $record): string {
                    $courses = $record->courses()
                        ->wherePivot('relation_type', 'recommended')
                        ->pluck('title');
                    return $courses->implode(', ') ?: 'None';
                }),
            
            ExportColumn::make('created_at')
                ->label('Created At')
                ->formatStateUsing(fn ($state): string => $state->format('Y-m-d H:i:s')),
            
            ExportColumn::make('updated_at')
                ->label('Updated At')
                ->formatStateUsing(fn ($state): string => $state->format('Y-m-d H:i:s')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your specialty export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
