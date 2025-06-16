<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Credit;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class CreditExporter extends Exporter
{
    protected static ?string $model = Credit::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            
            ExportColumn::make('name')
                ->label('Credit Name'),
            
            ExportColumn::make('description')
                ->label('Description'),
            
            ExportColumn::make('code')
                ->label('Credit Code'),
            
            ExportColumn::make('credit_points')
                ->label('Credit Points'),
            
            ExportColumn::make('credit_type')
                ->label('Credit Type')
                ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state))),
            
            ExportColumn::make('issuing_authority')
                ->label('Issuing Authority'),
            
            ExportColumn::make('is_active')
                ->label('Status')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive'),
            
            ExportColumn::make('requirements')
                ->label('Requirements')
                ->formatStateUsing(fn (?array $state): string => 
                    $state ? implode(', ', $state) : 'None'
                ),
            
            // Relationship counts
            ExportColumn::make('courses_count')
                ->label('Related Courses')
                ->counts('courses'),
            
            // Prerequisites and awards
            ExportColumn::make('prerequisite_courses')
                ->label('Prerequisite For Courses')
                ->state(function (Credit $record): string {
                    $courses = $record->courses()
                        ->wherePivot('relation_type', 'prerequisite')
                        ->pluck('title');
                    return $courses->implode(', ') ?: 'None';
                }),
            
            ExportColumn::make('awarded_by_courses')
                ->label('Awarded By Courses')
                ->state(function (Credit $record): string {
                    $courses = $record->courses()
                        ->wherePivot('relation_type', 'awarded')
                        ->pluck('title');
                    return $courses->implode(', ') ?: 'None';
                }),
            
            ExportColumn::make('valid_from')
                ->label('Valid From')
                ->formatStateUsing(fn ($state): string => $state ? $state->format('Y-m-d') : 'Not set'),
            
            ExportColumn::make('valid_until')
                ->label('Valid Until')
                ->formatStateUsing(fn ($state): string => $state ? $state->format('Y-m-d') : 'Not set'),
            
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
        $body = 'Your credit export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
