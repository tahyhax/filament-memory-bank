<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Course;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class CourseExporter extends Exporter
{
    protected static ?string $model = Course::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            
            ExportColumn::make('title')
                ->label('Course Title'),
            
            ExportColumn::make('description')
                ->label('Description'),
            
            ExportColumn::make('code')
                ->label('Course Code'),
            
            ExportColumn::make('duration_hours')
                ->label('Duration (Hours)'),
            
            ExportColumn::make('difficulty_level')
                ->label('Difficulty Level')
                ->formatStateUsing(fn (string $state): string => ucfirst($state)),
            
            ExportColumn::make('is_active')
                ->label('Status')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive'),
            
            ExportColumn::make('price')
                ->label('Price')
                ->formatStateUsing(fn (?float $state): string => $state ? '$' . number_format($state, 2) : 'Free'),
            
            ExportColumn::make('start_date')
                ->label('Start Date')
                ->formatStateUsing(fn ($state): string => $state ? $state->format('Y-m-d') : 'Not set'),
            
            ExportColumn::make('end_date')
                ->label('End Date')
                ->formatStateUsing(fn ($state): string => $state ? $state->format('Y-m-d') : 'Not set'),
            
            ExportColumn::make('instructor')
                ->label('Instructor'),
            
            // Relationship counts
            ExportColumn::make('credits_count')
                ->label('Related Credits')
                ->counts('credits'),
            
            ExportColumn::make('specialties_count')
                ->label('Related Specialties')
                ->counts('specialties'),
            
            // Prerequisites
            ExportColumn::make('prerequisite_credits')
                ->label('Prerequisite Credits')
                ->state(function (Course $record): string {
                    $prerequisites = $record->prerequisiteCredits()->pluck('title');
                    return $prerequisites->implode(', ') ?: 'None';
                }),
            
            ExportColumn::make('prerequisite_specialties')
                ->label('Prerequisite Specialties')
                ->state(function (Course $record): string {
                    $prerequisites = $record->prerequisiteSpecialties()->pluck('name');
                    return $prerequisites->implode(', ') ?: 'None';
                }),
            
            // Awarded items
            ExportColumn::make('awarded_credits')
                ->label('Awarded Credits')
                ->state(function (Course $record): string {
                    $awarded = $record->awardedCredits()->pluck('title');
                    return $awarded->implode(', ') ?: 'None';
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
        $body = 'Your course export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
